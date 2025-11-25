<?php

class LicensemodificationController extends Controller
{
    private $db;
    
    // Constants for magic values
    private const ACTIVE_STATUS = 'Y';
    private const STATUS_ANNULATED = 'ANNULATED';
    private const STATUS_MODIFIED = 'MODIFIED';
    private const DEFAULT_PAGE_LENGTH = 25;
    private const MAX_PAGE_LENGTH = 100;
    
    private const LICENSE_TYPES = ['EB', 'IB', 'ES', 'IS', 'RC'];
    
    private const TABLE_MAP = [
        'annulation' => ['table' => 'license_annulations', 'alias' => 'la', 'title' => 'Annulation', 'status' => self::STATUS_ANNULATED],
        'modification' => ['table' => 'license_modifications', 'alias' => 'lm', 'title' => 'Modification', 'status' => self::STATUS_MODIFIED],
        'prorogation' => ['table' => 'license_prorogations', 'alias' => 'lp', 'title' => 'Prorogation', 'status' => null]
    ];

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Main index page - displays the license modification interface
     */
    public function index(): void
    {
        try {
            $data = [
                'title' => 'License Modifications',
                'licenses' => $this->getAllActiveLicenses(),
                'banks' => $this->getAllBanks(),
                'clients' => $this->getAllClients()
            ];

            $this->viewWithLayout('licenses/licensemodification', $data);
            
        } catch (Exception $e) {
            $this->handleError('License Modification Index Error', $e);
            $this->viewWithLayout('licenses/licensemodification', [
                'title' => 'License Modifications',
                'error' => 'Failed to load data',
                'licenses' => [],
                'banks' => [],
                'clients' => []
            ]);
        }
    }

    /**
     * Main CRUD handler for AJAX requests
     */
    public function crudData(string $action = 'listing'): void
    {
        header('Content-Type: application/json');

        try {
            $actions = [
                'listing' => 'listRecords',
                'getLicense' => 'getLicense',
                'getBanks' => 'getBanks',
                'getLicenses' => 'getLicenses',
                'annulation' => 'saveAnnulation',
                'modification' => 'saveModification',
                'prorogation' => 'saveProrogation',
                'delete' => 'deleteRecord',
                'getRecord' => 'getRecord',
                'statistics' => 'getStatistics',
                'exportRecord' => 'exportRecord',
                'exportAll' => 'exportAll',
                'generatePDF' => 'generatePDF'
            ];

            if (isset($actions[$action]) && method_exists($this, $actions[$action])) {
                $this->{$actions[$action]}();
            } else {
                $this->jsonResponse(false, 'Invalid action');
            }
        } catch (Exception $e) {
            $this->handleError("CRUD Error [{$action}]", $e);
            $this->jsonResponse(false, 'Server error: ' . $e->getMessage());
        }
        exit;
    }

    /**
     * Get all active licenses with related data
     */
    private function getAllActiveLicenses(): array
    {
        $sql = "SELECT l.*, 
                       b.bank_name, 
                       b.bank_code,
                       c.short_name as client_name,
                       c.id_nat_number
                FROM licenses_t l
                LEFT JOIN banklist_master_t b ON l.bank_id = b.id
                LEFT JOIN clients_t c ON l.client_id = c.id
                WHERE l.display = :status
                ORDER BY l.license_number ASC";
        
        return $this->db->customQuery($sql, [':status' => self::ACTIVE_STATUS]) ?: [];
    }

    /**
     * Get all active banks
     */
    private function getAllBanks(): array
    {
        return $this->db->selectData(
            'banklist_master_t', 
            'id, bank_name, bank_code', 
            ['display' => self::ACTIVE_STATUS], 
            'bank_name ASC'
        ) ?: [];
    }

    /**
     * Get all clients
     */
    private function getAllClients(): array
    {
        return $this->db->selectData(
            'clients_t', 
            'id, short_name, id_nat_number', 
            [], 
            'short_name ASC'
        ) ?: [];
    }

    /**
     * Get statistics for all modification types
     */
    private function getStatistics(): void
    {
        try {
            $stats = [
                'total_annulations' => $this->getRecordCount('license_annulations'),
                'total_modifications' => $this->getRecordCount('license_modifications'),
                'total_prorogations' => $this->getRecordCount('license_prorogations')
            ];
            
            $this->jsonResponse(true, 'Statistics retrieved successfully', $stats);
        } catch (Exception $e) {
            $this->handleError('Statistics Error', $e);
            $this->jsonResponse(false, 'Failed to load statistics');
        }
    }

    /**
     * Get record count from table
     */
    private function getRecordCount(string $table): int
    {
        $result = $this->db->customQuery("SELECT COUNT(*) as count FROM {$table}");
        return (int) ($result[0]['count'] ?? 0);
    }

    /**
     * List records with DataTables server-side processing
     */
    private function listRecords(): void
    {
        $draw = $this->sanitizeInt($_GET['draw'] ?? 1);
        $start = $this->sanitizeInt($_GET['start'] ?? 0);
        $length = min($this->sanitizeInt($_GET['length'] ?? self::DEFAULT_PAGE_LENGTH), self::MAX_PAGE_LENGTH);
        $searchValue = $this->sanitizeString($_GET['search']['value'] ?? '');
        $type = $this->sanitizeString($_GET['type'] ?? 'annulation');

        if (!$this->isValidType($type)) {
            echo json_encode(['error' => 'Invalid type']);
            return;
        }

        $config = self::TABLE_MAP[$type];
        $table = $config['table'];
        $alias = $config['alias'];

        $sql = "SELECT {$alias}.*, l.license_number 
                FROM {$table} {$alias}
                LEFT JOIN licenses_t l ON {$alias}.license_id = l.id 
                WHERE 1=1";

        $params = [];

        if (!empty($searchValue)) {
            $sql .= " AND (l.license_number LIKE :search 
                      OR {$alias}.bank_name LIKE :search 
                      OR {$alias}.transmission_number LIKE :search)";
            $params[':search'] = "%{$searchValue}%";
        }

        $recordsTotal = $this->getFilteredCount($table, $alias, $searchValue);
        $sql .= " ORDER BY {$alias}.created_at DESC LIMIT {$start}, {$length}";
        $data = $this->db->customQuery($sql, $params);

        echo json_encode([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data ?: []
        ]);
    }

    /**
     * Get filtered record count
     */
    private function getFilteredCount(string $table, string $alias, string $searchValue): int
    {
        $countSql = "SELECT COUNT(*) as total FROM {$table} {$alias}
                     LEFT JOIN licenses_t l ON {$alias}.license_id = l.id 
                     WHERE 1=1";
        
        $params = [];
        if (!empty($searchValue)) {
            $countSql .= " AND (l.license_number LIKE :search 
                          OR {$alias}.bank_name LIKE :search 
                          OR {$alias}.transmission_number LIKE :search)";
            $params[':search'] = "%{$searchValue}%";
        }

        $result = $this->db->customQuery($countSql, $params);
        return (int) ($result[0]['total'] ?? 0);
    }

    /**
     * Get single license details
     */
    private function getLicense(): void
    {
        $licenseId = $this->sanitizeInt($_GET['id'] ?? 0);

        if ($licenseId <= 0) {
            $this->jsonResponse(false, 'Invalid license ID');
            return;
        }

        $sql = "SELECT l.*, 
                       c.short_name as client_name,
                       c.id_nat_number,
                       b.bank_name,
                       b.bank_code
                FROM licenses_t l
                LEFT JOIN clients_t c ON l.client_id = c.id
                LEFT JOIN banklist_master_t b ON l.bank_id = b.id
                WHERE l.id = :id";

        $license = $this->db->customQuery($sql, [':id' => $licenseId]);

        if (!empty($license)) {
            $this->jsonResponse(true, 'License retrieved successfully', $license[0]);
        } else {
            $this->jsonResponse(false, 'License not found');
        }
    }

    /**
     * Get all banks
     */
    private function getBanks(): void
    {
        $banks = $this->getAllBanks();
        $this->jsonResponse(true, 'Banks retrieved successfully', $banks);
    }

    /**
     * Get licenses for a specific bank
     */
    private function getLicenses(): void
    {
        $post = $this->getJsonInput();
        $bankId = $this->sanitizeInt($post['bank_id'] ?? 0);

        if ($bankId <= 0) {
            $this->jsonResponse(false, 'Invalid bank ID');
            return;
        }

        $sql = "SELECT l.*, 
                       c.short_name as client_name,
                       c.id_nat_number
                FROM licenses_t l
                LEFT JOIN clients_t c ON l.client_id = c.id
                WHERE l.bank_id = :bank_id AND l.display = :status
                ORDER BY l.license_number ASC";

        $licenses = $this->db->customQuery($sql, [
            ':bank_id' => $bankId,
            ':status' => self::ACTIVE_STATUS
        ]);

        $this->jsonResponse(true, 'Licenses retrieved successfully', $licenses ?: []);
    }

    /**
     * Save annulation record
     */
    private function saveAnnulation(): void
    {
        if (!$this->isPostRequest()) {
            $this->jsonResponse(false, 'Invalid request method');
            return;
        }

        $post = $this->getJsonInput();
        
        $validation = $this->validateAnnulationData($post);
        if (!$validation['valid']) {
            $this->jsonResponse(false, $validation['message']);
            return;
        }

        try {
            $this->db->beginTransaction();

            $data = $this->prepareAnnulationData($post);
            $insertId = $this->db->insertData('license_annulations', $data);

            if (!$insertId) {
                throw new Exception('Failed to save annulation record');
            }

            $this->updateLicenseStatus($post['license_id'], self::STATUS_ANNULATED);
            $this->db->commit();

            $this->jsonResponse(true, 'Annulation saved successfully!', ['id' => $insertId]);

        } catch (Exception $e) {
            $this->db->rollback();
            $this->handleError('Annulation Error', $e);
            $this->jsonResponse(false, $e->getMessage());
        }
    }

    /**
     * Validate annulation data
     */
    private function validateAnnulationData(array $data): array
    {
        $requiredFields = [
            'license_id', 'license_number', 'bank_name', 'bank_code', 
            'transmission_number', 'processing_fee', 'agent_name', 'national_id'
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return ['valid' => false, 'message' => "Field {$field} is required"];
            }
        }

        if (!is_numeric($data['processing_fee']) || $data['processing_fee'] <= 0) {
            return ['valid' => false, 'message' => 'Invalid processing fee'];
        }

        return ['valid' => true];
    }

    /**
     * Prepare annulation data for insertion
     */
    private function prepareAnnulationData(array $post): array
    {
        return [
            'license_id' => $this->sanitizeInt($post['license_id']),
            'license_number' => $this->sanitizeString($post['license_number']),
            'processing_fee' => $this->sanitizeFloat($post['processing_fee']),
            'bank_name' => $this->sanitizeString($post['bank_name']),
            'bank_code' => $this->sanitizeString($post['bank_code']),
            'transmission_number' => $this->sanitizeString($post['transmission_number']),
            'license_types' => json_encode($post['license_types'] ?? []),
            'agent_name' => $this->sanitizeString($post['agent_name']),
            'id_nat_number' => $this->sanitizeString($post['national_id']),
            'created_by' => $this->getCurrentUserId(),
            'updated_by' => $this->getCurrentUserId(),
            'created_at' => $this->getCurrentTimestamp(),
            'updated_at' => $this->getCurrentTimestamp()
        ];
    }

    /**
     * Save modification record
     */
    private function saveModification(): void
    {
        if (!$this->isPostRequest()) {
            $this->jsonResponse(false, 'Invalid request method');
            return;
        }

        $post = $this->getJsonInput();
        
        $validation = $this->validateModificationData($post);
        if (!$validation['valid']) {
            $this->jsonResponse(false, $validation['message']);
            return;
        }

        try {
            $this->db->beginTransaction();

            $data = $this->prepareModificationData($post);
            $insertId = $this->db->insertData('license_modifications', $data);

            if (!$insertId) {
                throw new Exception('Failed to save modification record');
            }

            $this->updateLicenseStatus($post['license_id'], self::STATUS_MODIFIED);
            $this->db->commit();

            $this->jsonResponse(true, 'Modification saved successfully!', ['id' => $insertId]);

        } catch (Exception $e) {
            $this->db->rollback();
            $this->handleError('Modification Error', $e);
            $this->jsonResponse(false, $e->getMessage());
        }
    }

    /**
     * Validate modification data
     */
    private function validateModificationData(array $data): array
    {
        $requiredFields = [
            'license_id', 'license_number', 'bank_name', 'bank_code', 
            'transmission_number', 'processing_fee', 'agent_name', 'national_id',
            'before_modification', 'after_modification'
        ];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                return ['valid' => false, 'message' => "Field {$field} is required"];
            }
        }

        if (!is_numeric($data['processing_fee']) || $data['processing_fee'] <= 0) {
            return ['valid' => false, 'message' => 'Invalid processing fee'];
        }

        return ['valid' => true];
    }

    /**
     * Prepare modification data for insertion
     */
    private function prepareModificationData(array $post): array
    {
        $baseData = $this->prepareAnnulationData($post);
        $baseData['before_modification'] = $post['before_modification'];
        $baseData['after_modification'] = $post['after_modification'];
        
        return $baseData;
    }

    /**
     * Save prorogation record
     */
    private function saveProrogation(): void
    {
        if (!$this->isPostRequest()) {
            $this->jsonResponse(false, 'Invalid request method');
            return;
        }

        $post = $this->getJsonInput();
        
        $validation = $this->validateProrogationData($post);
        if (!$validation['valid']) {
            $this->jsonResponse(false, $validation['message']);
            return;
        }

        try {
            $this->db->beginTransaction();

            $data = $this->prepareProrogationData($post);
            $insertId = $this->db->insertData('license_prorogations', $data);

            if (!$insertId) {
                throw new Exception('Failed to save prorogation record');
            }

            $this->updateLicenseExpiryDate($post['license_id'], $post['new_expiry_date']);
            $this->db->commit();

            $this->jsonResponse(true, 'Prorogation saved successfully!', ['id' => $insertId]);

        } catch (Exception $e) {
            $this->db->rollback();
            $this->handleError('Prorogation Error', $e);
            $this->jsonResponse(false, $e->getMessage());
        }
    }

    /**
     * Validate prorogation data
     */
    private function validateProrogationData(array $data): array
    {
        $requiredFields = [
            'license_id', 'license_number', 'bank_name', 'bank_code', 
            'transmission_number', 'processing_fee', 'agent_name', 'national_id',
            'new_expiry_date'
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return ['valid' => false, 'message' => "Field {$field} is required"];
            }
        }

        if (!is_numeric($data['processing_fee']) || $data['processing_fee'] <= 0) {
            return ['valid' => false, 'message' => 'Invalid processing fee'];
        }

        if (!empty($data['initial_expiry_date'])) {
            if (strtotime($data['new_expiry_date']) <= strtotime($data['initial_expiry_date'])) {
                return ['valid' => false, 'message' => 'New expiry date must be after the initial expiry date'];
            }
        }

        return ['valid' => true];
    }

    /**
     * Prepare prorogation data for insertion
     */
    private function prepareProrogationData(array $post): array
    {
        $baseData = $this->prepareAnnulationData($post);
        $baseData['initial_expiry_date'] = $post['initial_expiry_date'] ?? null;
        $baseData['new_expiry_date'] = $post['new_expiry_date'];
        
        return $baseData;
    }

    /**
     * Update license status
     */
    private function updateLicenseStatus(int $licenseId, string $status): bool
    {
        return $this->db->updateData('licenses_t', [
            'status' => $status,
            'updated_by' => $this->getCurrentUserId(),
            'updated_at' => $this->getCurrentTimestamp()
        ], ['id' => $licenseId]);
    }

    /**
     * Update license expiry date
     */
    private function updateLicenseExpiryDate(int $licenseId, string $expiryDate): bool
    {
        return $this->db->updateData('licenses_t', [
            'license_expiry_date' => $expiryDate,
            'updated_by' => $this->getCurrentUserId(),
            'updated_at' => $this->getCurrentTimestamp()
        ], ['id' => $licenseId]);
    }

    /**
     * Delete a record
     */
    private function deleteRecord(): void
    {
        $id = $this->sanitizeInt($_POST['id'] ?? $_GET['id'] ?? 0);
        $type = $this->sanitizeString($_POST['type'] ?? $_GET['type'] ?? '');

        if ($id <= 0 || !$this->isValidType($type)) {
            $this->jsonResponse(false, 'Invalid parameters');
            return;
        }

        try {
            $table = self::TABLE_MAP[$type]['table'];
            $success = $this->db->deleteData($table, ['id' => $id]);

            if ($success) {
                $this->jsonResponse(true, 'Record deleted successfully!');
            } else {
                $this->jsonResponse(false, 'Failed to delete record');
            }
        } catch (Exception $e) {
            $this->handleError('Delete Error', $e);
            $this->jsonResponse(false, $e->getMessage());
        }
    }

    /**
     * Get single record details
     */
    private function getRecord(): void
    {
        $id = $this->sanitizeInt($_GET['id'] ?? 0);
        $type = $this->sanitizeString($_GET['type'] ?? '');

        if ($id <= 0 || !$this->isValidType($type)) {
            $this->jsonResponse(false, 'Invalid parameters');
            return;
        }

        $config = self::TABLE_MAP[$type];
        $table = $config['table'];
        $alias = $config['alias'];

        $sql = "SELECT {$alias}.*, l.license_number 
                FROM {$table} {$alias}
                LEFT JOIN licenses_t l ON {$alias}.license_id = l.id
                WHERE {$alias}.id = :id";

        try {
            $record = $this->db->customQuery($sql, [':id' => $id]);

            if (!empty($record)) {
                if (isset($record[0]['license_types'])) {
                    $record[0]['license_types'] = json_decode($record[0]['license_types'], true) ?? [];
                }
                
                $this->jsonResponse(true, 'Record retrieved successfully', $record[0]);
            } else {
                $this->jsonResponse(false, 'Record not found');
            }
        } catch (Exception $e) {
            $this->handleError('Get Record Error', $e);
            $this->jsonResponse(false, $e->getMessage());
        }
    }

    /**
     * Export single record to Excel
     */
    private function exportRecord(): void
    {
        $id = $this->sanitizeInt($_GET['id'] ?? 0);
        $type = $this->sanitizeString($_GET['type'] ?? '');

        if ($id <= 0 || !$this->isValidType($type)) {
            header('Content-Type: application/json');
            $this->jsonResponse(false, 'Invalid parameters');
            return;
        }

        try {
            $this->requirePhpSpreadsheet();
            
            $config = self::TABLE_MAP[$type];
            $record = $this->getRecordForExport($id, $config);
            
            if (empty($record)) {
                throw new Exception('Record not found');
            }

            $spreadsheet = $this->createSpreadsheetForRecord($record, $config, $type);
            $this->outputSpreadsheet($spreadsheet, $config['title'], $record['license_number']);
            
        } catch (Exception $e) {
            $this->handleError('Export Record Error', $e);
            header('Content-Type: application/json');
            $this->jsonResponse(false, 'Export failed: ' . $e->getMessage());
        }
        exit;
    }

    /**
     * Export all records to Excel
     */
    private function exportAll(): void
    {
        $type = $this->sanitizeString($_GET['type'] ?? '');

        if (!$this->isValidType($type)) {
            header('Content-Type: application/json');
            $this->jsonResponse(false, 'Invalid type');
            return;
        }

        try {
            $this->requirePhpSpreadsheet();
            
            $config = self::TABLE_MAP[$type];
            $records = $this->getAllRecordsForExport($config);

            if (empty($records)) {
                header('Content-Type: application/json');
                $this->jsonResponse(false, 'No records found to export');
                return;
            }

            $spreadsheet = $this->createSpreadsheetForAllRecords($records, $config, $type);
            $this->outputSpreadsheet($spreadsheet, 'All_' . $config['title'], 'Export');
            
        } catch (Exception $e) {
            $this->handleError('Export All Error', $e);
            header('Content-Type: application/json');
            $this->jsonResponse(false, 'Export failed: ' . $e->getMessage());
        }
        exit;
    }

    /**
     * Generate PDF
     */
    private function generatePDF(): void
    {
        $id = $this->sanitizeInt($_GET['id'] ?? 0);
        $type = $this->sanitizeString($_GET['type'] ?? '');

        if ($id <= 0 || !$this->isValidType($type)) {
            die('Invalid parameters');
        }

        try {
            $this->requireMpdf();

            $config = self::TABLE_MAP[$type];
            $record = $this->getRecordForPdf($id, $config);
            
            if (empty($record)) {
                throw new Exception('Record not found');
            }

            $licenseTypes = json_decode($record['license_types'] ?? '[]', true) ?? [];
            $html = $this->generatePdfHtml($type, $record, $licenseTypes);

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 20,
                'margin_right' => 20,
                'margin_top' => 20,
                'margin_bottom' => 20,
            ]);

            $mpdf->WriteHTML($html);

            $filename = strtoupper($type) . '_' . ($record['license_number'] ?? 'DOC') . '_' . date('Ymd') . '.pdf';
            $mpdf->Output($filename, 'I');
            exit;

        } catch (Exception $e) {
            $this->handleError('Generate PDF Error', $e);
            die('Error generating PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF HTML based on type
     */
    private function generatePdfHtml(string $type, array $data, array $licenseTypes): string
    {
        switch ($type) {
            case 'annulation':
                return $this->generateAnnulationPDF($data, $licenseTypes);
            case 'modification':
                return $this->generateModificationPDF($data, $licenseTypes);
            case 'prorogation':
                return $this->generateProrogationPDF($data, $licenseTypes);
            default:
                throw new Exception('Invalid PDF type');
        }
    }

    /**
     * Generate common PDF header
     */
    private function generatePdfHeader(array $data): string
    {
        return '
        <table class="header-table" cellpadding="3" cellspacing="0">
            <tr>
                <td class="header-label" width="150">BANQUE AGRÉÉE :</td>
                <td class="header-value">' . $this->escape($data['bank_name'] ?? '') . '</td>
            </tr>
            <tr>
                <td class="header-label">CODE BANQUE AGRÉÉE :</td>
                <td class="header-value" width="200">' . $this->escape($data['bank_code'] ?? '') . '</td>
                <td width="80"></td>
                <td class="header-label" width="220">BORDEREAU DE TRANSMISSION N° :</td>
                <td class="header-value" width="200">' . $this->escape($data['transmission_number'] ?? '') . '</td>
            </tr>
        </table>';
    }

    /**
     * Generate license type checkboxes
     */
    private function generateLicenseTypeRow(array $licenseTypes): string
    {
        $html = '<tr><td></td>';
        
        foreach (self::LICENSE_TYPES as $type) {
            $isChecked = in_array($type, $licenseTypes);
            $checkmark = $isChecked ? '✓' : '';
            $class = $isChecked ? ' license-box-checked' : '';
            
            $html .= '<td class="license-box' . $class . '">' . $checkmark . '</td>';
        }
        
        $html .= '<td></td></tr>';
        return $html;
    }

    /**
     * Generate common PDF content section
     */
    private function generatePdfContent(array $data): string
    {
        return '
        <table class="content-table" cellpadding="3" cellspacing="0">
            <tr>
                <td width="30" style="font-weight: bold;">1</td>
                <td class="field-label" width="150">Agent Economique :</td>
                <td class="field-value" width="200">' . $this->escape($data['agent_name'] ?? '') . '</td>
                <td width="50"></td>
                <td class="field-label" width="120">N° ID. NAT. :</td>
                <td class="field-value" width="150">' . $this->escape($data['id_nat_number'] ?? '') . '</td>
            </tr>
        </table>
        
        <table class="content-table" cellpadding="3" cellspacing="0">
            <tr>
                <td class="field-label" width="130">N° de la Licence :</td>
                <td class="field-value">' . $this->escape($data['license_number'] ?? '') . '</td>
            </tr>
        </table>';
    }

    /**
     * Generate ANNULATION PDF - mPDF Compatible
     */
    private function generateAnnulationPDF(array $data, array $licenseTypes): string
    {
        return $this->getPdfStyles() . 
               $this->generatePdfHeader($data) .
               '<div class="title">DECLARATION D\'ANNULATION</div>' .
               $this->generateModeleTable($licenseTypes) .
               '<div class="pieces-text">Pièces jointes : VOLETS SOUSCRIPTEUR & OFIDA + PROFORMA</div>' .
               '<hr class="hr">' .
               $this->generatePdfContent($data) .
               '<hr class="hr">' .
               '<div class="section-2"><strong>2</strong><br>Frais de dossier pour Annulation : ' . 
               number_format($data['processing_fee'] ?? 0, 2) . ' $</div>' .
               '<hr class="hr">' .
               $this->generateSignatureTable('3') .
               $this->generateFooterNotes();
    }

    /**
     * Generate MODIFICATION PDF - mPDF Compatible
     */
    private function generateModificationPDF(array $data, array $licenseTypes): string
    {
        $modificationRows = $this->parseModificationData(
            $data['before_modification'] ?? '', 
            $data['after_modification'] ?? ''
        );

        return $this->getPdfStyles() . 
               $this->generatePdfHeader($data) .
               '<div class="title">DÉCLARATION DE MODIFICATION</div>' .
               $this->generateModeleTable($licenseTypes) .
               '<div class="pieces-text">Pièces jointes : VOLETS SOUSCRIPTEUR & OFIDA + PROFORMA</div>' .
               '<hr class="hr">' .
               $this->generatePdfContent($data) .
               '<hr class="hr">' .
               $this->generateModificationTable($modificationRows) .
               '<hr class="hr">' .
               '<div class="section-2"><strong>3</strong><br>Frais de dossier pour modification : ' . 
               number_format($data['processing_fee'] ?? 0, 2) . ' $</div>' .
               '<hr class="hr">' .
               $this->generateSignatureTable('4') .
               $this->generateFooterNotes();
    }

    /**
     * Generate PROROGATION PDF - mPDF Compatible
     */
    private function generateProrogationPDF(array $data, array $licenseTypes): string
    {
        $initialDate = !empty($data['initial_expiry_date']) ? 
                       date('d/m/Y', strtotime($data['initial_expiry_date'])) : '';
        $newDate = !empty($data['new_expiry_date']) ? 
                   date('d/m/Y', strtotime($data['new_expiry_date'])) : '';

        return $this->getPdfStyles() . 
               $this->generatePdfHeader($data) .
               '<div class="title">DÉCLARATION DE PROROGATION</div>' .
               $this->generateModeleTable($licenseTypes) .
               '<div class="pieces-text">Pièces jointes : VOLETS SOUSCRIPTEUR & OFIDA + PROFORMA</div>' .
               '<hr class="hr">' .
               $this->generatePdfContent($data) .
               '<hr class="hr">' .
               $this->generateProrogationTable($initialDate, $newDate) .
               '<hr class="hr">' .
               '<div class="section-2"><strong>3</strong><br>Frais de dossier pour modification : ' . 
               number_format($data['processing_fee'] ?? 0, 2) . ' $</div>' .
               '<hr class="hr">' .
               $this->generateSignatureTable('4') .
               $this->generateFooterNotes();
    }

    /**
     * Get PDF common styles
     */
    private function getPdfStyles(): string
    {
        return '<style>
        body { font-family: Arial, sans-serif; font-size: 10.5pt; line-height: 1.35; color: #000; }
        .header-table { width: 100%; margin-bottom: 15px; }
        .header-label { font-weight: bold; font-size: 9.5pt; }
        .header-value { border-bottom: 1px solid #000; padding: 2px 4px; }
        .title { text-align: center; font-size: 15pt; font-weight: bold; margin: 20px 0; }
        .hr { border-top: 1.5px solid #000; margin: 15px 0; }
        .modele-table { width: 100%; margin: 15px 0; }
        .license-box { width: 70px; height: 28px; border: 2px solid #000; text-align: center; vertical-align: middle; font-weight: bold; font-size: 14pt; }
        .license-box-checked { background-color: #000; color: #fff; }
        .pieces-text { text-align: center; margin: 15px 0; font-size: 9.5pt; }
        .content-table { width: 100%; margin: 10px 0; }
        .field-label { font-weight: normal; font-size: 10.5pt; }
        .field-value { border-bottom: 1px solid #000; padding: 2px 4px; }
        .section-2 { margin: 15px 0; font-size: 10.5pt; }
        .modification-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .signature-table { width: 100%; margin-top: 25px; }
        .footer-notes { margin-top: 35px; border-top: 1px solid #000; padding-top: 8px; font-size: 8.5pt; }
        </style>';
    }

    /**
     * Generate MODÈLE table
     */
    private function generateModeleTable(array $licenseTypes): string
    {
        $typeLabels = '';
        foreach (self::LICENSE_TYPES as $type) {
            $typeLabels .= '<td width="70" align="center" style="font-size: 9pt; font-weight: bold;">" ' . $type . ' "</td>';
        }

        return '<table class="modele-table" cellpadding="5" cellspacing="0">
            <tr>
                <td width="100" style="font-size: 11pt; font-weight: bold;">MODÈLE</td>' .
                $typeLabels .
                '<td width="50" style="font-size: 8.5pt; text-align: right;">*(2)</td>
            </tr>' .
            $this->generateLicenseTypeRow($licenseTypes) .
            '</table>';
    }

    /**
     * Parse modification data into table rows
     * Keep vertical lines BETWEEN columns, remove left/right outer borders
     */
    private function parseModificationData(string $before, string $after): string
    {
        $beforeLines = explode("\n", $before);
        $afterLines = explode("\n", $after);
        $maxLines = max(count($beforeLines), count($afterLines), 1);
        
        $rows = '';
        for ($i = 0; $i < $maxLines; $i++) {
            $beforeValue = $beforeLines[$i] ?? '';
            $afterValue = $afterLines[$i] ?? '';
            
            $fieldName = '';
            if (!empty($beforeValue) && strpos($beforeValue, ':') !== false) {
                [$fieldName, $beforeValue] = array_map('trim', explode(':', $beforeValue, 2));
            }
            if (!empty($afterValue) && strpos($afterValue, ':') !== false) {
                [, $afterValue] = array_map('trim', explode(':', $afterValue, 2));
            }
            
            // First column: no left border, has right border
            // Middle column: has both left and right borders  
            // Last column: has left border, no right border
            $rows .= '<tr>
                <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 40px 6px; vertical-align: middle; text-align: center;">' . $this->escape($fieldName) . '</td>
                <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 40px 6px; vertical-align: middle; text-align: center;">' . $this->escape($beforeValue) . '</td>
                <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 40px 6px; vertical-align: middle; text-align: center;">' . $this->escape($afterValue) . '</td>
            </tr>';
        }
        
        return $rows;
    }

    /**
     * Generate modification table
     * Keep vertical lines BETWEEN columns, remove left/right outer borders
     */
    private function generateModificationTable(string $rows): string
    {
        return '<table class="modification-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; border-right: 1px solid #000; padding: 10px; font-weight: bold; font-size: 10.5pt; background: #fff; text-align: center;" width="33%"><strong>2</strong>&nbsp;&nbsp;&nbsp;Donnée(s) à modifier</th>
                    <th style="border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 10px; font-weight: bold; font-size: 10.5pt; background: #fff; text-align: center;" width="33%">Avant modification</th>
                    <th style="border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; border-left: 1px solid #000; padding: 10px; font-weight: bold; font-size: 10.5pt; background: #fff; text-align: center;" width="34%">Après modification</th>
                </tr>
            </thead>
            <tbody>' . $rows . '</tbody>
        </table>';
    }

    /**
     * Generate prorogation table
     * Keep vertical lines BETWEEN columns, remove left/right outer borders
     */
    private function generateProrogationTable(string $initialDate, string $newDate): string
    {
        return '<table class="modification-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; border-right: 1px solid #000; padding: 10px; font-weight: bold; font-size: 10.5pt; background: #fff; text-align: center;" width="33%"><strong>2</strong>&nbsp;&nbsp;&nbsp;Donnée(s) à modifier</th>
                    <th style="border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 10px; font-weight: bold; font-size: 10.5pt; background: #fff; text-align: center;" width="33%">Avant modification</th>
                    <th style="border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; border-left: 1px solid #000; padding: 10px; font-weight: bold; font-size: 10.5pt; background: #fff; text-align: center;" width="34%">Après modification</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 40px 6px; vertical-align: middle; text-align: center;">Date d\'expiration</td>
                    <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding: 40px 6px; vertical-align: middle; text-align: center;">' . $this->escape($initialDate) . '</td>
                    <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 40px 6px; vertical-align: middle; text-align: center;">' . $this->escape($newDate) . '</td>
                </tr>
            </tbody>
        </table>';
    }

    /**
     * Generate signature table
     */
    private function generateSignatureTable(string $sectionNumber): string
    {
        return '<table class="signature-table" cellpadding="5" cellspacing="0">
            <tr>
                <td width="50%" style="font-size: 9.5pt;"><strong>' . $sectionNumber . '</strong> Date et Signature du déclarant</td>
                <td width="50%" style="font-size: 9.5pt;">Date, Signature et Cachet de la Banque agréée</td>
            </tr>
        </table>';
    }

    /**
     * Generate footer notes
     */
    private function generateFooterNotes(): string
    {
        return '<div class="footer-notes">
            <p>* (1) Biffer la mention inutile</p>
            <p>* (2) Cocher la licence concernée</p>
        </div>';
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if request is POST
     */
    private function isPostRequest(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Get JSON input from request body
     */
    private function getJsonInput(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    /**
     * Check if type is valid
     */
    private function isValidType(string $type): bool
    {
        return isset(self::TABLE_MAP[$type]);
    }

    /**
     * Sanitize integer input
     */
    private function sanitizeInt($value): int
    {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Sanitize float input
     */
    private function sanitizeFloat($value): float
    {
        return (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Sanitize string input
     */
    private function sanitizeString($value): string
    {
        return htmlspecialchars(trim($value ?? ''), ENT_QUOTES, 'UTF-8');
    }

    /**
     * HTML escape for PDF output
     */
    private function escape($value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Get current user ID
     */
    private function getCurrentUserId(): int
    {
        return (int) ($_SESSION['user_id'] ?? 1);
    }

    /**
     * Get current timestamp
     */
    private function getCurrentTimestamp(): string
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Send JSON response
     */
    private function jsonResponse(bool $success, string $message, $data = null): void
    {
        $response = ['success' => $success, 'message' => $message];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        echo json_encode($response);
    }

    /**
     * Handle and log errors
     */
    private function handleError(string $context, Exception $e): void
    {
        error_log("{$context}: " . $e->getMessage());
    }

    /**
     * Require PhpSpreadsheet library
     */
    private function requirePhpSpreadsheet(): void
    {
        $vendorPath = __DIR__ . '/../../../vendor/autoload.php';
        if (!file_exists($vendorPath)) {
            throw new Exception('PhpSpreadsheet not found. Install via: composer require phpoffice/phpspreadsheet');
        }
        require_once $vendorPath;
    }

    /**
     * Require mPDF library
     */
    private function requireMpdf(): void
    {
        $vendorPath = __DIR__ . '/../../../vendor/autoload.php';
        if (!file_exists($vendorPath)) {
            throw new Exception('mPDF not found. Install via: composer require mpdf/mpdf');
        }
        require_once $vendorPath;
    }

    /**
     * Get record for export
     */
    private function getRecordForExport(int $id, array $config): array
    {
        $sql = "SELECT {$config['alias']}.*, l.license_number 
                FROM {$config['table']} {$config['alias']}
                LEFT JOIN licenses_t l ON {$config['alias']}.license_id = l.id
                WHERE {$config['alias']}.id = :id";
        
        $result = $this->db->customQuery($sql, [':id' => $id]);
        return $result[0] ?? [];
    }

    /**
     * Get all records for export
     */
    private function getAllRecordsForExport(array $config): array
    {
        $sql = "SELECT {$config['alias']}.*, l.license_number 
                FROM {$config['table']} {$config['alias']}
                LEFT JOIN licenses_t l ON {$config['alias']}.license_id = l.id
                ORDER BY {$config['alias']}.created_at DESC";
        
        return $this->db->customQuery($sql) ?? [];
    }

    /**
     * Get record for PDF generation
     */
    private function getRecordForPdf(int $id, array $config): array
    {
        $sql = "SELECT {$config['alias']}.* 
                FROM {$config['table']} {$config['alias']} 
                WHERE {$config['alias']}.id = :id";
        
        $result = $this->db->customQuery($sql, [':id' => $id]);
        return $result[0] ?? [];
    }

    /**
     * Create spreadsheet for single record
     */
    private function createSpreadsheetForRecord(array $data, array $config, string $type): \PhpOffice\PhpSpreadsheet\Spreadsheet
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($config['title'] . ' Details');

        $headers = $this->getExportHeaders($type);
        $values = $this->getExportValues($data, $type);

        $sheet->fromArray([$headers, $values], null, 'A1');
        $this->styleSpreadsheetHeader($sheet, count($headers));

        return $spreadsheet;
    }

    /**
     * Create spreadsheet for all records
     */
    private function createSpreadsheetForAllRecords(array $records, array $config, string $type): \PhpOffice\PhpSpreadsheet\Spreadsheet
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('All ' . $config['title']);

        $headers = $this->getExportHeaders($type);
        $sheet->fromArray([$headers], null, 'A1');

        $rowIndex = 2;
        foreach ($records as $record) {
            $values = $this->getExportValues($record, $type);
            $sheet->fromArray([$values], null, 'A' . $rowIndex);
            $rowIndex++;
        }

        $this->styleSpreadsheetHeader($sheet, count($headers), '#28a745');
        $sheet->setAutoFilter('A1:' . $this->getColumnLetter(count($headers)) . '1');

        return $spreadsheet;
    }

    /**
     * Get export headers based on type
     */
    private function getExportHeaders(string $type): array
    {
        $baseHeaders = [
            'License Number', 'Bank Name', 'Bank Code', 'Transmission Number', 
            'Processing Fee', 'Agent Name', 'ID NAT Number', 'Created At'
        ];
        
        if ($type === 'modification') {
            $baseHeaders[] = 'Before Modification';
            $baseHeaders[] = 'After Modification';
        } elseif ($type === 'prorogation') {
            $baseHeaders[] = 'Initial Expiry Date';
            $baseHeaders[] = 'New Expiry Date';
        }
        
        return $baseHeaders;
    }

    /**
     * Get export values for a record
     */
    private function getExportValues(array $data, string $type): array
    {
        $values = [
            $data['license_number'] ?? 'N/A',
            $data['bank_name'] ?? 'N/A',
            $data['bank_code'] ?? 'N/A',
            $data['transmission_number'] ?? 'N/A',
            '$' . number_format($data['processing_fee'] ?? 0, 2),
            $data['agent_name'] ?? 'N/A',
            $data['id_nat_number'] ?? 'N/A',
            $data['created_at'] ?? 'N/A'
        ];

        if ($type === 'modification') {
            $values[] = $data['before_modification'] ?? 'N/A';
            $values[] = $data['after_modification'] ?? 'N/A';
        } elseif ($type === 'prorogation') {
            $values[] = $data['initial_expiry_date'] ? date('Y-m-d', strtotime($data['initial_expiry_date'])) : 'N/A';
            $values[] = $data['new_expiry_date'] ? date('Y-m-d', strtotime($data['new_expiry_date'])) : 'N/A';
        }

        return $values;
    }

    /**
     * Style spreadsheet header
     */
    private function styleSpreadsheetHeader($sheet, int $columnCount, string $color = '667eea'): void
    {
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 
                'startColor' => ['rgb' => $color]
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
        
        $lastColumn = $this->getColumnLetter($columnCount);
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);

        for ($i = 1; $i <= $columnCount; $i++) {
            $column = $this->getColumnLetter($i);
            $sheet->getColumnDimension($column)->setWidth(20);
        }
    }

    /**
     * Get column letter from index
     */
    private function getColumnLetter(int $index): string
    {
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index);
    }

    /**
     * Output spreadsheet to browser
     */
    private function outputSpreadsheet($spreadsheet, string $title, string $identifier): void
    {
        $filename = $title . '_' . $identifier . '_' . date('Ymd_His') . '.xlsx';
        $filepath = __DIR__ . '/../../../uploads/' . $filename;

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filepath);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: max-age=0');

        readfile($filepath);
        
        @unlink($filepath);
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }
}