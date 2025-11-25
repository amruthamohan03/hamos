<?php
class BivacController extends Controller
{
    /*
     * INDEX PAGE
     */
    public function index()
    {
        $db = new Database();

        // Ensure partial_t table exists
        $this->ensurePartialTableExists($db);

        // Get all active licenses for dropdown
        $sql = "
            SELECT 
                id,
                license_number,
                ref_cod,
                supplier,
                weight,
                fob_declared,
                insurance,
                freight,
                other_costs
            FROM licenses_t
            WHERE display = 'Y'
            ORDER BY license_number ASC
        ";
        $licenses = $db->customQuery($sql);
        
        // Handle false return
        if ($licenses === false) {
            $licenses = [];
        }

        // Generate CSRF Token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $data = [
            'title' => 'PARTIELLE Management',
            'licenses' => $licenses,
            'csrf_token' => $_SESSION['csrf_token']
        ];

        $this->viewWithLayout('licenses/bivac', $data);
    }

    /*
     * CRUD - INSERT, UPDATE, DELETE, LISTING
     */
    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'partial_t';

        function s($v) {
            return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
        }

        /*
         * ENSURE PARTIAL_T TABLE EXISTS
         */
        $this->ensurePartialTableExists($db);

        /*
         * DATATABLE LISTING
         */
        if ($action === 'listing' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Content-Type: application/json');

            try {
                $draw = intval($_GET['draw'] ?? 1);
                $start = intval($_GET['start'] ?? 0);
                $length = intval($_GET['length'] ?? 25);
                $searchValue = trim($_GET['search']['value'] ?? '');
                $orderColumnIndex = intval($_GET['order'][0]['column'] ?? 0);
                $orderDir = strtolower($_GET['order'][0]['dir'] ?? 'desc');
                
                $orderDir = in_array($orderDir, ['asc', 'desc']) ? $orderDir : 'desc';

                $columns = ['p.id', 'p.partial_name', 'l.license_number', 'p.partial_weight', 'p.partial_fob', 'p.created_at'];
                $orderColumn = $columns[$orderColumnIndex] ?? 'p.id';

                // Base query
                $baseQuery = "
                    FROM partial_t p
                    LEFT JOIN licenses_t l ON p.license_id = l.id
                ";

                $whereClause = " WHERE p.display = 'Y' ";
                $params = [];

                // Search filter
                if (!empty($searchValue)) {
                    $whereClause .= " AND (
                        p.partial_name LIKE ? OR
                        l.license_number LIKE ? OR
                        l.ref_cod LIKE ? OR
                        l.supplier LIKE ?
                    )";
                    $searchParam = "%{$searchValue}%";
                    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
                }

                // License filter
                if (!empty($_GET['license_filter']) && $_GET['license_filter'] != '0') {
                    $licenseFilter = intval($_GET['license_filter']);
                    $whereClause .= " AND p.license_id = ? ";
                    $params[] = $licenseFilter;
                }

                // Total records
                $totalRecordsQuery = "SELECT COUNT(*) as total " . $baseQuery . $whereClause;
                $totalRecordsResult = $db->customQuery($totalRecordsQuery, $params);
                $totalRecords = (!empty($totalRecordsResult) && isset($totalRecordsResult[0]['total'])) ? $totalRecordsResult[0]['total'] : 0;

                // Fetch data
                $dataQuery = "
                    SELECT 
                        p.id,
                        p.partial_name,
                        p.license_id,
                        l.license_number,
                        l.ref_cod,
                        l.supplier,
                        p.license_weight,
                        p.license_fob,
                        p.license_insurance,
                        p.license_freight,
                        p.license_other_costs,
                        p.partial_weight,
                        p.partial_fob,
                        p.partial_insurance,
                        p.partial_freight,
                        p.partial_other_costs,
                        p.av_weight,
                        p.av_fob,
                        p.av_insurance,
                        p.av_freight,
                        p.av_other_costs,
                        p.display,
                        p.created_at,
                        p.updated_at
                    " . $baseQuery . $whereClause . "
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?
                ";
                
                $params[] = $start;
                $params[] = $length;

                $records = $db->customQuery($dataQuery, $params);
                
                // Handle false return from customQuery
                if ($records === false || !is_array($records)) {
                    $records = [];
                }

                $data = [];
                foreach ($records as $row) {
                    // Get import count separately
                    $importCountQuery = "SELECT COUNT(*) as cnt FROM import_tracking_t WHERE inspection_reports = ? AND display = 'Y'";
                    $importCountResult = $db->customQuery($importCountQuery, [$row['partial_name']]);
                    $importCount = (!empty($importCountResult) && isset($importCountResult[0]['cnt'])) ? $importCountResult[0]['cnt'] : 0;
                    
                    $data[] = [
                        'id' => $row['id'],
                        'partial_name' => $row['partial_name'] ?? '',
                        'license_id' => $row['license_id'],
                        'license_number' => $row['license_number'] ?? 'N/A',
                        'ref_cod' => $row['ref_cod'] ?? '',
                        'supplier' => $row['supplier'] ?? '',
                        'license_weight' => $row['license_weight'] ?? 0,
                        'license_fob' => $row['license_fob'] ?? 0,
                        'license_insurance' => $row['license_insurance'] ?? 0,
                        'license_freight' => $row['license_freight'] ?? 0,
                        'license_other_costs' => $row['license_other_costs'] ?? 0,
                        'partial_weight' => $row['partial_weight'] ?? 0,
                        'partial_fob' => $row['partial_fob'] ?? 0,
                        'partial_insurance' => $row['partial_insurance'] ?? 0,
                        'partial_freight' => $row['partial_freight'] ?? 0,
                        'partial_other_costs' => $row['partial_other_costs'] ?? 0,
                        'av_weight' => $row['av_weight'] ?? 0,
                        'av_fob' => $row['av_fob'] ?? 0,
                        'av_insurance' => $row['av_insurance'] ?? 0,
                        'av_freight' => $row['av_freight'] ?? 0,
                        'av_other_costs' => $row['av_other_costs'] ?? 0,
                        'import_count' => $importCount,
                        'display' => $row['display'] ?? 'Y',
                        'created_at' => $row['created_at'],
                        'updated_at' => $row['updated_at']
                    ];
                }

                echo json_encode([
                    'draw' => $draw,
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $totalRecords,
                    'data' => $data
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'draw' => intval($_GET['draw'] ?? 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage()
                ]);
            }
            exit;
        }

        /*
         * GET STATISTICS
         */
        if ($action === 'statistics' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Content-Type: application/json');

            try {
                // Total PARTIELLE count
                $totalQuery = "SELECT COUNT(*) as total FROM partial_t WHERE display = 'Y'";
                $totalResult = $db->customQuery($totalQuery);
                $totalPartielle = (!empty($totalResult) && isset($totalResult[0]['total'])) ? $totalResult[0]['total'] : 0;

                // Active PARTIELLE (with usage)
                $activeQuery = "SELECT COUNT(*) as active FROM partial_t WHERE display = 'Y' AND partial_weight > 0";
                $activeResult = $db->customQuery($activeQuery);
                $activePartielle = (!empty($activeResult) && isset($activeResult[0]['active'])) ? $activeResult[0]['active'] : 0;

                // Unused PARTIELLE
                $unusedQuery = "SELECT COUNT(*) as unused FROM partial_t WHERE display = 'Y' AND partial_weight = 0";
                $unusedResult = $db->customQuery($unusedQuery);
                $unusedPartielle = (!empty($unusedResult) && isset($unusedResult[0]['unused'])) ? $unusedResult[0]['unused'] : 0;

                // Total weight used
                $weightQuery = "SELECT SUM(partial_weight) as total_weight FROM partial_t WHERE display = 'Y'";
                $weightResult = $db->customQuery($weightQuery);
                $totalWeight = (!empty($weightResult) && isset($weightResult[0]['total_weight'])) ? $weightResult[0]['total_weight'] : 0;

                // Total FOB used
                $fobQuery = "SELECT SUM(partial_fob) as total_fob FROM partial_t WHERE display = 'Y'";
                $fobResult = $db->customQuery($fobQuery);
                $totalFob = (!empty($fobResult) && isset($fobResult[0]['total_fob'])) ? $fobResult[0]['total_fob'] : 0;

                // License-wise breakdown
                $licenseQuery = "
                    SELECT 
                        l.id,
                        l.license_number,
                        COUNT(p.id) as partielle_count,
                        COALESCE(SUM(p.partial_weight), 0) as total_weight,
                        COALESCE(SUM(p.partial_fob), 0) as total_fob
                    FROM licenses_t l
                    LEFT JOIN partial_t p ON l.id = p.license_id AND p.display = 'Y'
                    WHERE l.display = 'Y'
                    GROUP BY l.id, l.license_number
                    HAVING partielle_count > 0
                    ORDER BY l.license_number ASC
                ";
                $licenseCounts = $db->customQuery($licenseQuery);
                
                if ($licenseCounts === false || !is_array($licenseCounts)) {
                    $licenseCounts = [];
                }

                echo json_encode([
                    'success' => true,
                    'data' => [
                        'total_partielle' => $totalPartielle,
                        'active_partielle' => $activePartielle,
                        'unused_partielle' => $unusedPartielle,
                        'total_weight' => round($totalWeight, 2),
                        'total_fob' => round($totalFob, 2),
                        'license_counts' => $licenseCounts
                    ]
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to load statistics: ' . $e->getMessage()
                ]);
            }
            exit;
        }

        /*
         * GET SINGLE PARTIELLE
         */
        if ($action === 'getPartielle' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Content-Type: application/json');

            $id = intval($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                exit;
            }

            $query = "
                SELECT 
                    p.*,
                    l.license_number,
                    l.ref_cod,
                    l.supplier
                FROM partial_t p
                LEFT JOIN licenses_t l ON p.license_id = l.id
                WHERE p.id = ? AND p.display = 'Y'
            ";
            $result = $db->customQuery($query, [$id]);

            if (!empty($result) && is_array($result)) {
                echo json_encode(['success' => true, 'data' => $result[0]]);
            } else {
                echo json_encode(['success' => false, 'message' => 'PARTIELLE not found']);
            }
            exit;
        }

        /*
         * GET LICENSE DETAILS
         */
        if ($action === 'getLicenseDetails' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Content-Type: application/json');

            $licenseId = intval($_GET['license_id'] ?? 0);
            if ($licenseId <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid License ID']);
                exit;
            }

            $query = "
                SELECT 
                    id,
                    license_number,
                    ref_cod,
                    supplier,
                    weight,
                    fob_declared,
                    insurance,
                    freight,
                    other_costs
                FROM licenses_t
                WHERE id = ? AND display = 'Y'
            ";
            $result = $db->customQuery($query, [$licenseId]);

            if (!empty($result) && is_array($result)) {
                echo json_encode(['success' => true, 'data' => $result[0]]);
            } else {
                echo json_encode(['success' => false, 'message' => 'License not found']);
            }
            exit;
        }

        /*
         * INSERT
         */
        if ($action === 'insert' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // CSRF validation
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo json_encode(['success' => false, 'message' => 'Invalid security token']);
                exit;
            }

            $license_id = (int)($_POST['license_id'] ?? 0);
            $partial_name = s($_POST['partial_name'] ?? '');

            // Validation
            if ($license_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Please select License']);
                exit;
            }

            if (empty($partial_name)) {
                echo json_encode(['success' => false, 'message' => 'PARTIELLE name is required']);
                exit;
            }

            // Check for duplicate PARTIELLE name
            $checkQuery = "SELECT id FROM partial_t WHERE partial_name = ? AND display = 'Y'";
            $existing = $db->customQuery($checkQuery, [$partial_name]);
            if (!empty($existing) && is_array($existing)) {
                echo json_encode(['success' => false, 'message' => 'PARTIELLE name already exists']);
                exit;
            }

            // Get license details
            $licenseQuery = "SELECT weight, fob_declared, insurance, freight, other_costs FROM licenses_t WHERE id = ? AND display = 'Y'";
            $licenseData = $db->customQuery($licenseQuery, [$license_id]);

            if (empty($licenseData) || !is_array($licenseData)) {
                echo json_encode(['success' => false, 'message' => 'License not found']);
                exit;
            }

            $license = $licenseData[0];

            $data = [
                'partial_name' => $partial_name,
                'license_id' => $license_id,
                'license_weight' => round((float)($license['weight'] ?? 0), 2),
                'license_fob' => round((float)($license['fob_declared'] ?? 0), 2),
                'license_insurance' => round((float)($license['insurance'] ?? 0), 2),
                'license_freight' => round((float)($license['freight'] ?? 0), 2),
                'license_other_costs' => round((float)($license['other_costs'] ?? 0), 2),
                'partial_weight' => 0.00,
                'partial_fob' => 0.00,
                'partial_insurance' => 0.00,
                'partial_freight' => 0.00,
                'partial_other_costs' => 0.00,
                'av_weight' => 0.00,
                'av_fob' => 0.00,
                'av_insurance' => 0.00,
                'av_freight' => 0.00,
                'av_other_costs' => 0.00,
                'display' => 'Y',
                'created_by' => $_SESSION['user_id'] ?? 1,
                'updated_by' => $_SESSION['user_id'] ?? 1,
            ];

            $insertId = $db->insertData($table, $data);

            echo json_encode($insertId
                ? ['success' => true, 'message' => 'PARTIELLE created successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => 'Insert failed.']
            );
            exit;
        }

        /*
         * UPDATE - ONLY av_* fields can be updated
         */
        if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // CSRF validation
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo json_encode(['success' => false, 'message' => 'Invalid security token']);
                exit;
            }

            $id = (int)($_POST['partielle_id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                exit;
            }

            // Check if PARTIELLE exists
            $oldRow = $db->selectData('partial_t', 'id', ['id' => $id, 'display' => 'Y']);
            if (empty($oldRow)) {
                echo json_encode(['success' => false, 'message' => 'PARTIELLE not found']);
                exit;
            }

            // Only av_* fields can be updated
            $data = [
                'av_weight' => round((float)($_POST['av_weight'] ?? 0), 2),
                'av_fob' => round((float)($_POST['av_fob'] ?? 0), 2),
                'av_insurance' => round((float)($_POST['av_insurance'] ?? 0), 2),
                'av_freight' => round((float)($_POST['av_freight'] ?? 0), 2),
                'av_other_costs' => round((float)($_POST['av_other_costs'] ?? 0), 2),
                'updated_by' => $_SESSION['user_id'] ?? 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);

            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'PARTIELLE updated successfully!' : 'Update failed.'
            ]);
            exit;
        }

        /*
         * DELETE (Soft delete)
         */
        if ($action === 'deletion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // CSRF validation
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo json_encode(['success' => false, 'message' => 'Invalid security token']);
                exit;
            }

            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid delete ID']);
                exit;
            }

            // Check if PARTIELLE is being used in imports
            $partialNameQuery = "SELECT partial_name FROM partial_t WHERE id = ?";
            $partialData = $db->customQuery($partialNameQuery, [$id]);
            
            if (!empty($partialData) && is_array($partialData)) {
                $partialName = $partialData[0]['partial_name'];
                $usageQuery = "SELECT COUNT(*) as cnt FROM import_tracking_t WHERE inspection_reports = ? AND display = 'Y'";
                $usageResult = $db->customQuery($usageQuery, [$partialName]);
                
                if (!empty($usageResult) && is_array($usageResult)) {
                    $usageCount = $usageResult[0]['cnt'] ?? 0;

                    if ($usageCount > 0) {
                        echo json_encode([
                            'success' => false,
                            'message' => "Cannot delete PARTIELLE. It is being used in $usageCount import(s)."
                        ]);
                        exit;
                    }
                }
            }

            // Soft delete
            $data = [
                'display' => 'N',
                'updated_by' => $_SESSION['user_id'] ?? 1,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $delete = $db->updateData($table, $data, ['id' => $id]);

            echo json_encode([
                'success' => $delete ? true : false,
                'message' => $delete ? 'PARTIELLE deleted successfully!' : 'Delete failed.'
            ]);
            exit;
        }

        /*
         * EXPORT ALL TO EXCEL
         */
        if ($action === 'exportAll' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $query = "
                SELECT 
                    p.*,
                    l.license_number,
                    l.ref_cod,
                    l.supplier
                FROM partial_t p
                LEFT JOIN licenses_t l ON p.license_id = l.id
                WHERE p.display = 'Y'
                ORDER BY p.id DESC
            ";
            $partielle = $db->customQuery($query);
            
            if ($partielle === false || !is_array($partielle)) {
                $partielle = [];
            }

            $vendorPath = dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';
            
            if (!file_exists($vendorPath)) {
                die('PhpSpreadsheet library not found. Please run: composer require phpoffice/phpspreadsheet');
            }
            
            require_once $vendorPath;
            
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('PARTIELLE List');

            // Header styling
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '28a745']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ];

            // Headers
            $headers = [
                'ID', 'PARTIELLE Name', 'License Number', 'CRF Reference', 'Supplier',
                'License Weight', 'License FOB', 'License Insurance', 'License Freight', 'License Other Costs',
                'Used Weight', 'Used FOB', 'Used Insurance', 'Used Freight', 'Used Other Costs',
                'Available Weight', 'Available FOB', 'Available Insurance', 'Available Freight', 'Available Other Costs',
                'Created At', 'Updated At'
            ];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $sheet->getStyle($col . '1')->applyFromArray($headerStyle);
                $col++;
            }

            // Data
            $row = 2;
            foreach ($partielle as $p) {
                $sheet->setCellValue('A' . $row, $p['id']);
                $sheet->setCellValue('B' . $row, $p['partial_name']);
                $sheet->setCellValue('C' . $row, $p['license_number'] ?? 'N/A');
                $sheet->setCellValue('D' . $row, $p['ref_cod'] ?? '');
                $sheet->setCellValue('E' . $row, $p['supplier'] ?? '');
                
                $sheet->setCellValue('F' . $row, $p['license_weight']);
                $sheet->setCellValue('G' . $row, number_format($p['license_fob'], 2));
                $sheet->setCellValue('H' . $row, number_format($p['license_insurance'], 2));
                $sheet->setCellValue('I' . $row, number_format($p['license_freight'], 2));
                $sheet->setCellValue('J' . $row, number_format($p['license_other_costs'], 2));
                
                $sheet->setCellValue('K' . $row, $p['partial_weight']);
                $sheet->setCellValue('L' . $row, number_format($p['partial_fob'], 2));
                $sheet->setCellValue('M' . $row, number_format($p['partial_insurance'], 2));
                $sheet->setCellValue('N' . $row, number_format($p['partial_freight'], 2));
                $sheet->setCellValue('O' . $row, number_format($p['partial_other_costs'], 2));
                
                $sheet->setCellValue('P' . $row, $p['av_weight']);
                $sheet->setCellValue('Q' . $row, number_format($p['av_fob'], 2));
                $sheet->setCellValue('R' . $row, number_format($p['av_insurance'], 2));
                $sheet->setCellValue('S' . $row, number_format($p['av_freight'], 2));
                $sheet->setCellValue('T' . $row, number_format($p['av_other_costs'], 2));
                
                $sheet->setCellValue('U' . $row, date('d-m-Y H:i', strtotime($p['created_at'])));
                $sheet->setCellValue('V' . $row, $p['updated_at'] ? date('d-m-Y H:i', strtotime($p['updated_at'])) : 'N/A');
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'V') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Download
            $filename = 'PARTIELLE_List_' . date('Y-m-d_His') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }

    /*
     * ENSURE PARTIAL_T TABLE EXISTS
     */
    private function ensurePartialTableExists($db)
    {
        try {
            $checkTableSql = "SHOW TABLES LIKE 'partial_t'";
            $exists = $db->customQuery($checkTableSql);

            if (empty($exists) || !is_array($exists)) {
                $createTableSql = "
                    CREATE TABLE `partial_t` (
                      `id` INT(11) NOT NULL AUTO_INCREMENT,
                      `partial_name` VARCHAR(255) NOT NULL COMMENT 'e.g., CRF123/PART-001',
                      `license_id` INT(11) NOT NULL COMMENT 'Foreign key to licenses_t',
                      
                      `license_weight` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Original license weight',
                      `license_fob` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Original license FOB declared',
                      `license_insurance` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Original license insurance',
                      `license_freight` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Original license freight',
                      `license_other_costs` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Original license other costs',
                      
                      `partial_weight` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Cumulative weight used',
                      `partial_fob` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Cumulative FOB used',
                      `partial_insurance` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Cumulative insurance used',
                      `partial_freight` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Cumulative freight used',
                      `partial_other_costs` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Cumulative other costs used',
                      
                      `av_weight` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Available weight (not auto-calculated)',
                      `av_fob` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Available FOB (not auto-calculated)',
                      `av_insurance` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Available insurance (not auto-calculated)',
                      `av_freight` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Available freight (not auto-calculated)',
                      `av_other_costs` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Available other costs (not auto-calculated)',
                      
                      `created_by` INT(11) NOT NULL,
                      `updated_by` INT(11) DEFAULT NULL,
                      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                      `display` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
                      
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `unique_partial_name` (`partial_name`),
                      KEY `idx_license_id` (`license_id`),
                      KEY `idx_display` (`display`),
                      KEY `idx_license_display` (`license_id`, `display`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
                      COMMENT='Tracks partial shipments (PARTIELLE) using license_id for referential integrity';
                ";

                $db->customQuery($createTableSql);
                
                // Add foreign key constraint separately to avoid creation issues
                try {
                    $addFkSql = "
                        ALTER TABLE `partial_t` 
                        ADD CONSTRAINT `fk_partial_license` 
                        FOREIGN KEY (`license_id`) 
                        REFERENCES `licenses_t` (`id`) 
                        ON DELETE RESTRICT 
                        ON UPDATE CASCADE;
                    ";
                    $db->customQuery($addFkSql);
                } catch (Exception $e) {
                    // Foreign key may already exist, continue
                }
            }
        } catch (Exception $e) {
            // Log error but continue
            error_log("Error ensuring partial_t table: " . $e->getMessage());
        }
    }
}