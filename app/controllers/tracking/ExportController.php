<?php

class ExportController extends Controller
{
  private $db;
  private $logFile;
  private $allowedFilters = ['completed', 'in_progress', 'in_transit', 'ceec_pending', 'min_div_pending', 'gov_docs_pending', 'audited_pending', 'archived_pending', 'dgda_in_pending', 'liquidation_pending', 'quittance_pending'];

  public function __construct()
  {
    $this->db = new Database();
    $this->logFile = __DIR__ . '/../../logs/export_operations.log';
    
    $logDir = dirname($this->logFile);
    if (!is_dir($logDir)) {
      mkdir($logDir, 0755, true);
    }
  }

  /**
   * Index page - Display export form and list
   */
  public function index()
  {
    if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time']) || (time() - $_SESSION['csrf_token_time']) > 3600) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      $_SESSION['csrf_token_time'] = time();
    }

    $sql = "SELECT DISTINCT c.id, c.short_name, c.liquidation_paid_by 
            FROM clients_t c
            INNER JOIN licenses_t l ON c.id = l.client_id
            WHERE c.display = 'Y' 
              AND c.client_type LIKE '%E%'
              AND l.kind_id IN (3, 4)
              AND l.display = 'Y'
              AND l.status = 'ACTIVE'
            ORDER BY c.short_name ASC";
    $subscribers = $this->db->customQuery($sql) ?: [];

    $regimes = $this->db->selectData('regime_master_t', 'id, regime_name', ['display' => 'Y', 'type' => 'E'], 'regime_name ASC') ?: [];
    $currencies = $this->db->selectData('currency_master_t', 'id, currency_name, currency_short_name', ['display' => 'Y'], 'currency_short_name ASC') ?: [];
    $exit_points = $this->db->selectData('transit_point_master_t', 'id, transit_point_name', ['display' => 'Y', 'exit_point' => 'Y'], 'transit_point_name ASC') ?: [];
    $loading_sites = $this->db->selectData('transit_point_master_t', 'id, transit_point_name', ['display' => 'Y', 'warehouse' => 'Y'], 'transit_point_name ASC') ?: [];
    $clearance_types = $this->db->selectData('clearance_master_t', 'id, clearance_name', ['display' => 'Y'], 'clearance_name ASC') ?: [];
    $clearing_statuses = $this->db->selectData('clearing_status_master_t', 'id, clearing_status', ['display' => 'Y'], 'clearing_status ASC') ?: [];
    $document_statuses = $this->db->selectData('document_status_master_t', 'id, document_status', ['display' => 'Y', 'type' => 'E'], 'document_status ASC') ?: [];
    $truck_statuses = $this->db->selectData('truck_status_master_t', 'id, truck_status', ['display' => 'Y'], 'truck_status ASC') ?: [];
    $feet_containers = $this->db->selectData('feet_container_master_t', 'id, feet_container_size', ['display' => 'Y'], 'feet_container_size ASC') ?: [];
    
    $transport_modes = $this->db->selectData('transport_mode_master_t', 'id, transport_mode_name', ['display' => 'Y'], 'transport_mode_name ASC') ?: [];

    $data = [
      'title' => 'Export Management',
      'subscribers' => $this->sanitizeArray($subscribers),
      'regimes' => $this->sanitizeArray($regimes),
      'currencies' => $this->sanitizeArray($currencies),
      'exit_points' => $this->sanitizeArray($exit_points),
      'loading_sites' => $this->sanitizeArray($loading_sites),
      'clearance_types' => $this->sanitizeArray($clearance_types),
      'clearing_statuses' => $this->sanitizeArray($clearing_statuses),
      'document_statuses' => $this->sanitizeArray($document_statuses),
      'truck_statuses' => $this->sanitizeArray($truck_statuses),
      'feet_containers' => $this->sanitizeArray($feet_containers),
      'transport_modes' => $this->sanitizeArray($transport_modes),
      'csrf_token' => $_SESSION['csrf_token']
    ];

    $this->viewWithLayout('tracking/exports', $data);
  }

  private function sanitizeArray($data)
  {
    if (!is_array($data)) return [];
    
    return array_map(function($item) {
      if (is_array($item)) {
        return array_map(function($value) {
          return is_string($value) ? htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8') : $value;
        }, $item);
      }
      return $item;
    }, $data);
  }

  public function crudData($action = 'insertion')
  {
    header('Content-Type: application/json');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');

    try {
      switch ($action) {
        case 'insert':
        case 'insertion':
          $this->insertExport();
          break;
        case 'bulkInsertFromModal':
          $this->bulkInsertFromModal();
          break;
        case 'update':
          $this->updateExport();
          break;
        case 'deletion':
          $this->deleteExport();
          break;
        case 'getExport':
          $this->getExport();
          break;
        case 'listing':
          $this->listExports();
          break;
        case 'statistics':
          $this->getStatistics();
          break;
        case 'getLicenses':
          $this->getLicenses();
          break;
        case 'getLicenseDetails':
          $this->getLicenseDetails();
          break;
        case 'getNextMCASequence':
          $this->getNextMCASequence();
          break;
        case 'getClearingStatusIds':
          $this->getClearingStatusIds();
          break;
        case 'exportExport':
          $this->exportExport();
          break;
        case 'exportAll':
          $this->exportAllExports();
          break;
        case 'getBulkUpdateData':
          $this->getBulkUpdateData();
          break;
        case 'bulkUpdate':
          $this->bulkUpdate();
          break;
        case 'getAvailableSeals':
          $this->getAvailableSeals();
          break;
        default:
          $this->logError('Invalid action attempted', ['action' => $action]);
          echo json_encode(['success' => false, 'message' => 'Invalid action']);
      }
    } catch (Exception $e) {
      $this->logError('Server error in crudData', [
        'action' => $action,
        'error' => $e->getMessage()
      ]);
      echo json_encode(['success' => false, 'message' => 'Server error occurred. Please try again.']);
    }
    exit;
  }

  private function getAvailableSeals()
  {
    try {
      $sql = "SELECT id, seal_number 
              FROM seal_individual_numbers_t 
              WHERE status = 'Available' 
                AND display = 'Y'
              ORDER BY seal_number ASC";
      
      $seals = $this->db->customQuery($sql);
      $seals = $this->sanitizeArray($seals);
      
      echo json_encode([
        'success' => true,
        'data' => $seals ?: []
      ]);
    } catch (Exception $e) {
      $this->logError('Failed to get available seals', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to load available seals']);
    }
  }

  private function insertExport()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
      return;
    }

    $this->validateCsrfToken();

    try {
      $validation = $this->validateExportData($_POST);
      if (!$validation['success']) {
        echo json_encode($validation);
        return;
      }

      $data = $this->prepareExportData($_POST);
      
      if (empty($data['weight']) || $data['weight'] === null) {
        $data['weight'] = 0.00;
      }
      
      if (empty($data['fob']) || $data['fob'] === null) {
        $data['fob'] = 0.00;
      }
      
      if (empty($data['clearing_status'])) {
        $defaultClearingStatus = 5;
        $statusResult = $this->db->customQuery("SELECT id FROM clearing_status_master_t WHERE clearing_status LIKE '%IN TRANSIT%' AND display = 'Y' LIMIT 1");
        if (!empty($statusResult)) {
          $defaultClearingStatus = (int)$statusResult[0]['id'];
        }
        $data['clearing_status'] = $defaultClearingStatus;
      }
      
      $data['created_by'] = (int)($_SESSION['user_id'] ?? 1);
      $data['updated_by'] = (int)($_SESSION['user_id'] ?? 1);
      $data['display'] = 'Y';

      $sealIds = !empty($_POST['dgda_seal_ids']) ? json_decode($_POST['dgda_seal_ids'], true) : [];

      $insertId = $this->db->insertData('exports_t', $data);

      if ($insertId) {
        if (!empty($sealIds) && is_array($sealIds)) {
          foreach ($sealIds as $sealId) {
            $this->db->updateData('seal_individual_numbers_t', [
              'status' => 'Used',
              'notes' => 'Used in Export MCA: ' . $data['mca_ref'],
              'updated_by' => (int)($_SESSION['user_id'] ?? 1),
              'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => (int)$sealId]);
          }
        }

        $this->logInfo('Export created successfully', ['export_id' => $insertId]);
        echo json_encode(['success' => true, 'message' => 'Export created successfully!', 'id' => $insertId]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save export. Please check all required fields.']);
      }
    } catch (Exception $e) {
      $this->logError('Exception during export insert', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);
      echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
  }

  private function bulkInsertFromModal()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
      return;
    }

    $this->validateCsrfToken();

    try {
      $commonData = json_decode($_POST['common_data'] ?? '{}', true);
      $rowsData = json_decode($_POST['rows_data'] ?? '[]', true);
      
      if (empty($commonData) || empty($rowsData)) {
        echo json_encode(['success' => false, 'message' => 'No update data provided']);
        return;
      }

      if (count($rowsData) > 100) {
        echo json_encode(['success' => false, 'message' => 'Maximum 100 exports can be created at once']);
        return;
      }

      $requiredFields = ['subscriber_id', 'license_id', 'regime', 'types_of_clearance'];
      foreach ($requiredFields as $field) {
        if (empty($commonData[$field])) {
          echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
          return;
        }
      }

      $hasWeight = false;
      foreach ($rowsData as $row) {
        if (isset($row['weight']) && floatval($row['weight']) > 0) {
          $hasWeight = true;
          break;
        }
      }

      if (!$hasWeight) {
        echo json_encode(['success' => false, 'message' => 'At least one entry must have weight > 0']);
        return;
      }

      $defaultClearingStatus = 5;
      $statusResult = $this->db->customQuery("SELECT id FROM clearing_status_master_t WHERE clearing_status LIKE '%IN TRANSIT%' AND display = 'Y' LIMIT 1");
      if (!empty($statusResult)) {
        $defaultClearingStatus = (int)$statusResult[0]['id'];
      }

      $successCount = 0;
      $errorCount = 0;
      $errors = [];
      $createdIds = [];

      foreach ($rowsData as $index => $row) {
        try {
          $entryNum = $index + 1;
          $mcaRef = $this->clean($row['mca_ref'] ?? '');
          
          if (empty($mcaRef)) {
            $errors[] = "Entry #{$entryNum}: MCA Reference is missing";
            $errorCount++;
            continue;
          }
          
          $existingMCA = $this->db->selectData('exports_t', 'id', ['mca_ref' => $mcaRef, 'display' => 'Y']);
          if (!empty($existingMCA)) {
            $errors[] = "Entry #{$entryNum}: MCA Reference {$mcaRef} already exists";
            $errorCount++;
            continue;
          }
          
          $exportData = [
            'subscriber_id' => (int)$commonData['subscriber_id'],
            'license_id' => (int)$commonData['license_id'],
            'kind' => !empty($commonData['kind']) ? (int)$commonData['kind'] : null,
            'type_of_goods' => !empty($commonData['type_of_goods']) ? (int)$commonData['type_of_goods'] : null,
            'transport_mode' => !empty($commonData['transport_mode']) ? (int)$commonData['transport_mode'] : null,
            'mca_ref' => $mcaRef,
            'currency' => !empty($commonData['currency']) ? (int)$commonData['currency'] : null,
            'supplier' => !empty($commonData['supplier']) ? $this->clean($commonData['supplier']) : null,
            'regime' => (int)$commonData['regime'],
            'types_of_clearance' => (int)$commonData['types_of_clearance'],
            
            'loading_date' => !empty($row['loading_date']) && $this->isValidDate($row['loading_date']) ? $row['loading_date'] : null,
            'bp_date' => !empty($row['bp_date']) && $this->isValidDate($row['bp_date']) ? $row['bp_date'] : null,
            'site_of_loading_id' => !empty($row['site_of_loading_id']) ? (int)$row['site_of_loading_id'] : null,
            'destination' => !empty($row['destination']) ? $this->clean($row['destination']) : null,
            'horse' => !empty($row['horse']) ? $this->clean($row['horse']) : null,
            'trailer_1' => !empty($row['trailer_1']) ? $this->clean($row['trailer_1']) : null,
            'trailer_2' => !empty($row['trailer_2']) ? $this->clean($row['trailer_2']) : null,
            'wagon_ref' => !empty($row['wagon_ref']) ? $this->clean($row['wagon_ref']) : null,
            'container' => !empty($row['container']) ? $this->clean($row['container']) : null,
            'feet_container' => !empty($row['feet_container']) ? $this->clean($row['feet_container']) : null,
            'transporter' => !empty($row['transporter']) ? $this->clean($row['transporter']) : null,
            'exit_point_id' => !empty($row['exit_point_id']) ? (int)$row['exit_point_id'] : null,
            'weight' => !empty($row['weight']) && is_numeric($row['weight']) ? round((float)$row['weight'], 2) : 0.00,
            'fob' => !empty($row['fob']) && is_numeric($row['fob']) ? round((float)$row['fob'], 2) : 0.00,
            'number_of_bags' => !empty($row['number_of_bags']) ? (int)$row['number_of_bags'] : null,
            'lot_number' => !empty($row['lot_number']) ? $this->clean($row['lot_number']) : null,
            'dgda_seal_no' => !empty($row['dgda_seal_no']) ? $this->clean($row['dgda_seal_no']) : null,
            'number_of_seals' => !empty($row['number_of_seals']) ? (int)$row['number_of_seals'] : null,
            
            'invoice' => null,
            'clearing_status' => $defaultClearingStatus,
            'created_by' => (int)($_SESSION['user_id'] ?? 1),
            'updated_by' => (int)($_SESSION['user_id'] ?? 1),
            'display' => 'Y'
          ];

          $insertId = $this->db->insertData('exports_t', $exportData);

          if ($insertId) {
            $sealIds = !empty($row['seal_ids']) ? $row['seal_ids'] : [];
            if (!empty($sealIds) && is_array($sealIds)) {
              foreach ($sealIds as $sealId) {
                $this->db->updateData('seal_individual_numbers_t', [
                  'status' => 'Used',
                  'notes' => 'Used in Export MCA: ' . $mcaRef,
                  'updated_by' => (int)($_SESSION['user_id'] ?? 1),
                  'updated_at' => date('Y-m-d H:i:s')
                ], ['id' => (int)$sealId]);
              }
            }

            $successCount++;
            $createdIds[] = $insertId;
          } else {
            $errors[] = "Entry #{$entryNum}: Failed to save";
            $errorCount++;
          }
        } catch (Exception $e) {
          $errors[] = "Entry #{$entryNum}: " . $e->getMessage();
          $errorCount++;
        }
      }

      $message = "Bulk insert completed: {$successCount} exports created successfully";
      if (!empty($errors)) {
        $message .= ". " . count($errors) . " failed.";
      }

      $this->logInfo('Bulk export insert from modal completed', [
        'success_count' => $successCount,
        'error_count' => count($errors),
        'created_ids' => $createdIds
      ]);

      echo json_encode([
        'success' => true,
        'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
        'success_count' => $successCount,
        'error_count' => count($errors),
        'errors' => array_map(function($error) {
          return htmlspecialchars($error, ENT_QUOTES, 'UTF-8');
        }, $errors),
        'created_ids' => $createdIds
      ]);

    } catch (Exception $e) {
      $this->logError('Exception during bulk insert from modal', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'An error occurred during bulk insert.']);
    }
  }

  private function getClearingStatusIds()
  {
    try {
      $sql = "SELECT id, clearing_status 
              FROM clearing_status_master_t 
              WHERE display = 'Y'
              ORDER BY id ASC";
      
      $statuses = $this->db->customQuery($sql);
      
      $statusMap = [
        'in_transit_id' => null,
        'in_progress_id' => null,
        'completed_id' => null
      ];
      
      foreach ($statuses as $status) {
        $statusText = strtoupper(trim($status['clearing_status']));
        
        if (strpos($statusText, 'IN TRANSIT') !== false || strpos($statusText, 'TRANSIT') !== false) {
          $statusMap['in_transit_id'] = (int)$status['id'];
        } elseif (strpos($statusText, 'IN PROGRESS') !== false || strpos($statusText, 'PROGRESS') !== false) {
          $statusMap['in_progress_id'] = (int)$status['id'];
        } elseif (strpos($statusText, 'CLEARING COMPLETED') !== false || strpos($statusText, 'COMPLETED') !== false) {
          $statusMap['completed_id'] = (int)$status['id'];
        }
      }
      
      echo json_encode([
        'success' => true,
        'data' => $statusMap
      ]);
      
    } catch (Exception $e) {
      $this->logError('Failed to get clearing status IDs', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to load clearing status IDs']);
    }
  }

  private function calculateDocumentStatus($ceecIn, $ceecOut, $minDivIn, $minDivOut)
  {
    if ($ceecIn && $ceecOut && $minDivIn && $minDivOut) {
      return 5;
    } elseif ($ceecIn && $ceecOut) {
      return 3;
    } elseif ($minDivIn && $minDivOut) {
      return 4;
    } elseif ($ceecIn || $minDivIn) {
      return 2;
    }
    return 1;
  }

  private function exportExport()
  {
    $exportId = (int) ($_GET['id'] ?? 0);

    if ($exportId <= 0) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Invalid export ID']);
      return;
    }

    try {
      $vendorPath = __DIR__ . '/../../../vendor/autoload.php';
      
      if (!file_exists($vendorPath)) {
        throw new Exception('PhpSpreadsheet not found. Please run: composer require phpoffice/phpspreadsheet');
      }
      
      require_once $vendorPath;

      $sql = "SELECT 
                e.mca_ref,
                c.short_name as client_name,
                c.company_name as client_full_name,
                l.license_number,
                k.kind_name,
                tg.goods_type as type_of_goods,
                tm.transport_mode_name,
                curr.currency_short_name as currency,
                e.supplier,
                rm.regime_name,
                ct.clearance_name,
                e.invoice,
                e.po_ref,
                e.available_weight,
                e.weight,
                e.fob,
                e.horse,
                e.trailer_1,
                e.trailer_2,
                e.wagon_ref,
                e.container,
                e.feet_container,
                e.transporter,
                ls.transit_point_name as loading_site,
                e.destination,
                e.loading_date,
                e.pv_date,
                e.bp_date,
                e.demande_attestation_date,
                e.assay_date,
                e.lot_number,
                e.number_of_seals,
                e.dgda_seal_no,
                e.number_of_bags,
                e.archive_reference,
                e.ceec_in_date,
                e.ceec_out_date,
                e.min_div_in_date,
                e.min_div_out_date,
                e.cgea_doc_ref,
                e.segues_rcv_ref,
                e.segues_payment_date,
                ds.document_status,
                e.customs_clearing_code,
                e.dgda_in_date,
                e.declaration_reference,
                e.liquidation_reference,
                e.liquidation_date,
                e.liquidation_paid_by,
                e.liquidation_amount,
                e.quittance_reference,
                e.quittance_date,
                e.dgda_out_date,
                e.gov_docs_in_date,
                e.gov_docs_out_date,
                e.dispatch_deliver_date,
                e.kanyaka_arrival_date,
                e.kanyaka_departure_date,
                e.border_arrival_date,
                e.exit_drc_date,
                ep.transit_point_name as exit_point,
                e.end_of_formalities_date,
                ts.truck_status,
                cs.clearing_status,
                e.lmc_id,
                e.ogefrem_inv_ref,
                e.loading_to_dispatch_date,
                e.audited_date,
                e.archived_date,
                e.remarks
              FROM exports_t e
              LEFT JOIN clients_t c ON e.subscriber_id = c.id
              LEFT JOIN licenses_t l ON e.license_id = l.id
              LEFT JOIN kind_master_t k ON e.kind = k.id
              LEFT JOIN type_of_goods_master_t tg ON e.type_of_goods = tg.id
              LEFT JOIN transport_mode_master_t tm ON e.transport_mode = tm.id
              LEFT JOIN currency_master_t curr ON e.currency = curr.id
              LEFT JOIN regime_master_t rm ON e.regime = rm.id
              LEFT JOIN clearance_master_t ct ON e.types_of_clearance = ct.id
              LEFT JOIN transit_point_master_t ls ON e.site_of_loading_id = ls.id
              LEFT JOIN transit_point_master_t ep ON e.exit_point_id = ep.id
              LEFT JOIN clearing_status_master_t cs ON e.clearing_status = cs.id
              LEFT JOIN document_status_master_t ds ON e.document_status = ds.id
              LEFT JOIN truck_status_master_t ts ON e.truck_status = ts.id
              WHERE e.id = :id AND e.display = 'Y'";

      $result = $this->db->customQuery($sql, [':id' => $exportId]);
      
      if (empty($result)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Export not found']);
        return;
      }

      $data = $result[0];
      
      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('Export Details');

      // ✅ HORIZONTAL FORMAT - Headers in Row 1, Values in Row 2
      $headers = [
        'MCA Reference', 'Client Code', 'Client Full Name', 'License Number', 'Kind', 'Type of Goods',
        'Transport Mode', 'Currency', 'Supplier', 'Regime', 'Clearance Type', 'Invoice', 'PO Reference',
        'Available Weight (MT)', 'Weight (MT)', 'FOB', 'Horse', 'Trailer 1', 'Trailer 2', 'Wagon Reference',
        'Container', 'Feet Container', 'Transporter', 'Site of Loading', 'Destination', 'Loading Date',
        'PV Date', 'BP Date', 'Demande d\'Attestation', 'Assay Date', 'Lot Number', 'Number of Seals',
        'DGDA Seal No', 'Number of Bags', 'Archive Reference', 'CEEC In Date', 'CEEC Out Date',
        'Min Div In Date', 'Min Div Out Date', 'CGEA Doc Ref', 'SEGUES RCV Ref', 'SEGUES Payment Date',
        'Document Status', 'Customs Clearing Code', 'DGDA In Date', 'Declaration Reference',
        'Liquidation Reference', 'Liquidation Date', 'Liquidation Paid By', 'Liquidation Amount',
        'Quittance Reference', 'Quittance Date', 'DGDA Out Date', 'Gov Docs In Date', 'Gov Docs Out Date',
        'Dispatch/Deliver Date', 'Kanyaka Arrival Date', 'Kanyaka Departure Date', 'Border Arrival Date',
        'Exit DRC Date', 'Exit Point', 'End of Formalities Date', 'Truck Status', 'Clearing Status',
        'LMC ID', 'OGEFREM Inv.Ref.', 'Loading to Dispatch Date', 'Audited Date', 'Archived Date', 'Remarks'
      ];

      $values = [
        $data['mca_ref'] ?? '',
        $data['client_name'] ?? '',
        $data['client_full_name'] ?? '',
        $data['license_number'] ?? '',
        $data['kind_name'] ?? '',
        $data['type_of_goods'] ?? '',
        $data['transport_mode_name'] ?? '',
        $data['currency'] ?? '',
        $data['supplier'] ?? '',
        $data['regime_name'] ?? '',
        $data['clearance_name'] ?? '',
        $data['invoice'] ?? '',
        $data['po_ref'] ?? '',
        $data['available_weight'] ? number_format((float)$data['available_weight'], 2) : '',
        $data['weight'] ? number_format((float)$data['weight'], 2) : '',
        $data['fob'] ? number_format((float)$data['fob'], 2) : '',
        $data['horse'] ?? '',
        $data['trailer_1'] ?? '',
        $data['trailer_2'] ?? '',
        $data['wagon_ref'] ?? '',
        $data['container'] ?? '',
        $data['feet_container'] ?? '',
        $data['transporter'] ?? '',
        $data['loading_site'] ?? '',
        $data['destination'] ?? '',
        $data['loading_date'] ? date('d-m-Y', strtotime($data['loading_date'])) : '',
        $data['pv_date'] ? date('d-m-Y', strtotime($data['pv_date'])) : '',
        $data['bp_date'] ? date('d-m-Y', strtotime($data['bp_date'])) : '',
        $data['demande_attestation_date'] ? date('d-m-Y', strtotime($data['demande_attestation_date'])) : '',
        $data['assay_date'] ? date('d-m-Y', strtotime($data['assay_date'])) : '',
        $data['lot_number'] ?? '',
        $data['number_of_seals'] ?? '',
        $data['dgda_seal_no'] ?? '',
        $data['number_of_bags'] ?? '',
        $data['archive_reference'] ?? '',
        $data['ceec_in_date'] ? date('d-m-Y', strtotime($data['ceec_in_date'])) : '',
        $data['ceec_out_date'] ? date('d-m-Y', strtotime($data['ceec_out_date'])) : '',
        $data['min_div_in_date'] ? date('d-m-Y', strtotime($data['min_div_in_date'])) : '',
        $data['min_div_out_date'] ? date('d-m-Y', strtotime($data['min_div_out_date'])) : '',
        $data['cgea_doc_ref'] ?? '',
        $data['segues_rcv_ref'] ?? '',
        $data['segues_payment_date'] ? date('d-m-Y', strtotime($data['segues_payment_date'])) : '',
        $data['document_status'] ?? '',
        $data['customs_clearing_code'] ?? '',
        $data['dgda_in_date'] ? date('d-m-Y', strtotime($data['dgda_in_date'])) : '',
        $data['declaration_reference'] ?? '',
        $data['liquidation_reference'] ?? '',
        $data['liquidation_date'] ? date('d-m-Y', strtotime($data['liquidation_date'])) : '',
        $data['liquidation_paid_by'] ?? '',
        $data['liquidation_amount'] ? number_format((float)$data['liquidation_amount'], 2) : '',
        $data['quittance_reference'] ?? '',
        $data['quittance_date'] ? date('d-m-Y', strtotime($data['quittance_date'])) : '',
        $data['dgda_out_date'] ? date('d-m-Y', strtotime($data['dgda_out_date'])) : '',
        $data['gov_docs_in_date'] ? date('d-m-Y', strtotime($data['gov_docs_in_date'])) : '',
        $data['gov_docs_out_date'] ? date('d-m-Y', strtotime($data['gov_docs_out_date'])) : '',
        $data['dispatch_deliver_date'] ? date('d-m-Y', strtotime($data['dispatch_deliver_date'])) : '',
        $data['kanyaka_arrival_date'] ? date('d-m-Y', strtotime($data['kanyaka_arrival_date'])) : '',
        $data['kanyaka_departure_date'] ? date('d-m-Y', strtotime($data['kanyaka_departure_date'])) : '',
        $data['border_arrival_date'] ? date('d-m-Y', strtotime($data['border_arrival_date'])) : '',
        $data['exit_drc_date'] ? date('d-m-Y', strtotime($data['exit_drc_date'])) : '',
        $data['exit_point'] ?? '',
        $data['end_of_formalities_date'] ? date('d-m-Y', strtotime($data['end_of_formalities_date'])) : '',
        $data['truck_status'] ?? '',
        $data['clearing_status'] ?? '',
        $data['lmc_id'] ?? '',
        $data['ogefrem_inv_ref'] ?? '',
        $data['loading_to_dispatch_date'] ? date('d-m-Y', strtotime($data['loading_to_dispatch_date'])) : '',
        $data['audited_date'] ? date('d-m-Y', strtotime($data['audited_date'])) : '',
        $data['archived_date'] ? date('d-m-Y', strtotime($data['archived_date'])) : '',
        ''
      ];

      // Handle remarks
      if (!empty($data['remarks'])) {
        try {
          $remarksArray = json_decode($data['remarks'], true);
          if (is_array($remarksArray)) {
            $remarksLines = [];
            foreach ($remarksArray as $remark) {
              $date = isset($remark['date']) ? date('d-m-Y', strtotime($remark['date'])) : '';
              $text = isset($remark['text']) ? $remark['text'] : '';
              if ($date || $text) {
                $remarksLines[] = ($date ? "[$date] " : '') . $text;
              }
            }
            $values[count($values) - 1] = implode("\n", $remarksLines);
          }
        } catch (Exception $e) {
          $values[count($values) - 1] = $data['remarks'];
        }
      }

      // Write headers and values
      $sheet->fromArray([$headers], null, 'A1');
      $sheet->fromArray([$values], null, 'A2');

      // Style headers
      $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'wrapText' => true],
        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
      ];
      
      $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
      $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);

      // Style values
      $valueStyle = [
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'wrapText' => true],
        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'BDC3C7']]]
      ];
      $sheet->getStyle('A2:' . $lastColumn . '2')->applyFromArray($valueStyle);

      // Set column widths
      foreach (range(1, count($headers)) as $colIndex) {
        $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
        $sheet->getColumnDimension($column)->setWidth(18);
      }

      // Set row heights
      $sheet->getRowDimension(1)->setRowHeight(30);
      $sheet->getRowDimension(2)->setRowHeight(25);

      $today = date('d-m-Y');
      $mcaRef = $data['mca_ref'] ?? 'Export';
      $filename = 'Export_' . str_replace(['/', '\\', '-'], '_', $mcaRef) . '_' . $today . '.xlsx';
      
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '"');
      header('Cache-Control: max-age=0');
      header('Pragma: public');

      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $writer->save('php://output');

      $spreadsheet->disconnectWorksheets();
      unset($spreadsheet);
      exit;
      
    } catch (Exception $e) {
      $this->logError('Export Export Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Export failed: ' . $e->getMessage()]);
      exit;
    }
  }

  private function exportAllExports()
  {
    try {
      $vendorPath = __DIR__ . '/../../../vendor/autoload.php';
      
      if (!file_exists($vendorPath)) {
        throw new Exception('PhpSpreadsheet not found');
      }
      
      require_once $vendorPath;

      $sql = "SELECT 
                e.mca_ref,
                c.short_name as client_name,
                c.company_name as client_full_name,
                l.license_number,
                k.kind_name,
                tg.goods_type as type_of_goods,
                tm.transport_mode_name,
                curr.currency_short_name as currency,
                e.supplier,
                rm.regime_name,
                ct.clearance_name,
                e.invoice,
                e.po_ref,
                e.available_weight,
                e.weight,
                e.fob,
                e.horse,
                e.trailer_1,
                e.trailer_2,
                e.wagon_ref,
                e.container,
                e.feet_container,
                e.transporter,
                ls.transit_point_name as loading_site,
                e.destination,
                e.loading_date,
                e.pv_date,
                e.bp_date,
                e.demande_attestation_date,
                e.assay_date,
                e.lot_number,
                e.number_of_seals,
                e.dgda_seal_no,
                e.number_of_bags,
                e.archive_reference,
                e.ceec_in_date,
                e.ceec_out_date,
                e.min_div_in_date,
                e.min_div_out_date,
                e.cgea_doc_ref,
                e.segues_rcv_ref,
                e.segues_payment_date,
                ds.document_status,
                e.customs_clearing_code,
                e.dgda_in_date,
                e.declaration_reference,
                e.liquidation_reference,
                e.liquidation_date,
                e.liquidation_paid_by,
                e.liquidation_amount,
                e.quittance_reference,
                e.quittance_date,
                e.dgda_out_date,
                e.gov_docs_in_date,
                e.gov_docs_out_date,
                e.dispatch_deliver_date,
                e.kanyaka_arrival_date,
                e.kanyaka_departure_date,
                e.border_arrival_date,
                e.exit_drc_date,
                ep.transit_point_name as exit_point,
                e.end_of_formalities_date,
                ts.truck_status,
                cs.clearing_status,
                e.lmc_id,
                e.ogefrem_inv_ref,
                e.loading_to_dispatch_date,
                e.audited_date,
                e.archived_date,
                e.remarks
              FROM exports_t e
              LEFT JOIN clients_t c ON e.subscriber_id = c.id
              LEFT JOIN licenses_t l ON e.license_id = l.id
              LEFT JOIN kind_master_t k ON e.kind = k.id
              LEFT JOIN type_of_goods_master_t tg ON e.type_of_goods = tg.id
              LEFT JOIN transport_mode_master_t tm ON e.transport_mode = tm.id
              LEFT JOIN currency_master_t curr ON e.currency = curr.id
              LEFT JOIN regime_master_t rm ON e.regime = rm.id
              LEFT JOIN clearance_master_t ct ON e.types_of_clearance = ct.id
              LEFT JOIN transit_point_master_t ls ON e.site_of_loading_id = ls.id
              LEFT JOIN transit_point_master_t ep ON e.exit_point_id = ep.id
              LEFT JOIN clearing_status_master_t cs ON e.clearing_status = cs.id
              LEFT JOIN document_status_master_t ds ON e.document_status = ds.id
              LEFT JOIN truck_status_master_t ts ON e.truck_status = ts.id
              WHERE e.display = 'Y'
              ORDER BY e.id DESC";

      $exports = $this->db->customQuery($sql);

      if (empty($exports)) {
        header('Content-Type: application/json');
        echo json_encode([
          'success' => false, 
          'message' => 'No exports found. Please create at least one export record first.'
        ]);
        return;
      }

      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('All Exports');

      $headers = [
        'MCA Reference', 'Client Code', 'Client Full Name', 'License Number', 'Kind', 'Type of Goods',
        'Transport Mode', 'Currency', 'Supplier', 'Regime', 'Clearance Type', 'Invoice',
        'PO Reference', 'Available Weight', 'Weight', 'FOB',
        'Horse', 'Trailer 1', 'Trailer 2', 'Wagon Reference', 'Container', 'Feet Container', 'Transporter',
        'Site of Loading', 'Destination', 'Loading Date', 'PV Date', 'BP Date',
        'Demande d\'Attestation', 'Assay Date', 'Lot Number', 'Number of Seals', 'DGDA Seal No',
        'Number of Bags', 'Archive Reference', 'CEEC In', 'CEEC Out', 'Min Div In', 'Min Div Out',
        'CGEA Doc Ref', 'SEGUES RCV Ref', 'SEGUES Payment Date', 'Document Status',
        'Customs Clearing Code', 'DGDA In Date', 'Declaration Reference', 'Liquidation Reference',
        'Liquidation Date', 'Liquidation Paid By', 'Liquidation Amount', 'Quittance Reference',
        'Quittance Date', 'DGDA Out Date', 'Gov Docs In', 'Gov Docs Out',
        'Dispatch/Deliver Date', 'Kanyaka Arrival Date', 'Kanyaka Departure Date',
        'Border Arrival', 'Exit DRC Date', 'Exit Point', 'End of Formalities Date',
        'Truck Status', 'Clearing Status', 'LMC ID', 'OGEFREM Inv.Ref.', 'Loading to Dispatch Date',
        'Audited Date', 'Archived Date', 'Remarks'
      ];

      $sheet->fromArray([$headers], null, 'A1');

      $rowIndex = 2;
      foreach ($exports as $export) {
        $remarksText = '';
        if (!empty($export['remarks'])) {
          try {
            $remarksArray = json_decode($export['remarks'], true);
            if (is_array($remarksArray)) {
              $remarksLines = [];
              foreach ($remarksArray as $remark) {
                $date = isset($remark['date']) ? date('d-m-Y', strtotime($remark['date'])) : '';
                $text = isset($remark['text']) ? $remark['text'] : '';
                if ($date || $text) {
                  $remarksLines[] = ($date ? "[$date] " : '') . $text;
                }
              }
              $remarksText = implode("\n", $remarksLines);
            }
          } catch (Exception $e) {
            $remarksText = $export['remarks'];
          }
        }

        $rowData = [
          $export['mca_ref'] ?? '',
          $export['client_name'] ?? '',
          $export['client_full_name'] ?? '',
          $export['license_number'] ?? '',
          $export['kind_name'] ?? '',
          $export['type_of_goods'] ?? '',
          $export['transport_mode_name'] ?? '',
          $export['currency'] ?? '',
          $export['supplier'] ?? '',
          $export['regime_name'] ?? '',
          $export['clearance_name'] ?? '',
          $export['invoice'] ?? '',
          $export['po_ref'] ?? '',
          $export['available_weight'] ? number_format((float)$export['available_weight'], 2) : '',
          $export['weight'] ? number_format((float)$export['weight'], 2) : '',
          $export['fob'] ? number_format((float)$export['fob'], 2) : '',
          $export['horse'] ?? '',
          $export['trailer_1'] ?? '',
          $export['trailer_2'] ?? '',
          $export['wagon_ref'] ?? '',
          $export['container'] ?? '',
          $export['feet_container'] ?? '',
          $export['transporter'] ?? '',
          $export['loading_site'] ?? '',
          $export['destination'] ?? '',
          $export['loading_date'] ? date('d-m-Y', strtotime($export['loading_date'])) : '',
          $export['pv_date'] ? date('d-m-Y', strtotime($export['pv_date'])) : '',
          $export['bp_date'] ? date('d-m-Y', strtotime($export['bp_date'])) : '',
          $export['demande_attestation_date'] ? date('d-m-Y', strtotime($export['demande_attestation_date'])) : '',
          $export['assay_date'] ? date('d-m-Y', strtotime($export['assay_date'])) : '',
          $export['lot_number'] ?? '',
          $export['number_of_seals'] ?? '',
          $export['dgda_seal_no'] ?? '',
          $export['number_of_bags'] ?? '',
          $export['archive_reference'] ?? '',
          $export['ceec_in_date'] ? date('d-m-Y', strtotime($export['ceec_in_date'])) : '',
          $export['ceec_out_date'] ? date('d-m-Y', strtotime($export['ceec_out_date'])) : '',
          $export['min_div_in_date'] ? date('d-m-Y', strtotime($export['min_div_in_date'])) : '',
          $export['min_div_out_date'] ? date('d-m-Y', strtotime($export['min_div_out_date'])) : '',
          $export['cgea_doc_ref'] ?? '',
          $export['segues_rcv_ref'] ?? '',
          $export['segues_payment_date'] ? date('d-m-Y', strtotime($export['segues_payment_date'])) : '',
          $export['document_status'] ?? '',
          $export['customs_clearing_code'] ?? '',
          $export['dgda_in_date'] ? date('d-m-Y', strtotime($export['dgda_in_date'])) : '',
          $export['declaration_reference'] ?? '',
          $export['liquidation_reference'] ?? '',
          $export['liquidation_date'] ? date('d-m-Y', strtotime($export['liquidation_date'])) : '',
          $export['liquidation_paid_by'] ?? '',
          $export['liquidation_amount'] ? number_format((float)$export['liquidation_amount'], 2) : '',
          $export['quittance_reference'] ?? '',
          $export['quittance_date'] ? date('d-m-Y', strtotime($export['quittance_date'])) : '',
          $export['dgda_out_date'] ? date('d-m-Y', strtotime($export['dgda_out_date'])) : '',
          $export['gov_docs_in_date'] ? date('d-m-Y', strtotime($export['gov_docs_in_date'])) : '',
          $export['gov_docs_out_date'] ? date('d-m-Y', strtotime($export['gov_docs_out_date'])) : '',
          $export['dispatch_deliver_date'] ? date('d-m-Y', strtotime($export['dispatch_deliver_date'])) : '',
          $export['kanyaka_arrival_date'] ? date('d-m-Y', strtotime($export['kanyaka_arrival_date'])) : '',
          $export['kanyaka_departure_date'] ? date('d-m-Y', strtotime($export['kanyaka_departure_date'])) : '',
          $export['border_arrival_date'] ? date('d-m-Y', strtotime($export['border_arrival_date'])) : '',
          $export['exit_drc_date'] ? date('d-m-Y', strtotime($export['exit_drc_date'])) : '',
          $export['exit_point'] ?? '',
          $export['end_of_formalities_date'] ? date('d-m-Y', strtotime($export['end_of_formalities_date'])) : '',
          $export['truck_status'] ?? '',
          $export['clearing_status'] ?? '',
          $export['lmc_id'] ?? '',
          $export['ogefrem_inv_ref'] ?? '',
          $export['loading_to_dispatch_date'] ? date('d-m-Y', strtotime($export['loading_to_dispatch_date'])) : '',
          $export['audited_date'] ? date('d-m-Y', strtotime($export['audited_date'])) : '',
          $export['archived_date'] ? date('d-m-Y', strtotime($export['archived_date'])) : '',
          $remarksText
        ];
        
        $sheet->fromArray([$rowData], null, 'A' . $rowIndex);
        $rowIndex++;
      }

      $headerStyle = [
        'font' => [
          'bold' => true,
          'color' => ['rgb' => 'FFFFFF'],
          'size' => 11
        ],
        'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => ['rgb' => '28a745']
        ],
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ],
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
          ]
        ]
      ];
      
      $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
      $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);

      $dataStyle = [
        'borders' => [
          'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => 'CCCCCC']
          ]
        ],
        'alignment' => [
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'wrapText' => true
        ]
      ];
      $sheet->getStyle('A2:' . $lastColumn . ($rowIndex - 1))->applyFromArray($dataStyle);

      foreach (range(1, count($headers)) as $colIndex) {
        $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
        $sheet->getColumnDimension($column)->setWidth(18);
      }

      $sheet->getRowDimension(1)->setRowHeight(25);
      $sheet->setAutoFilter('A1:' . $lastColumn . '1');

      $today = date('d-m-Y');
      $filename = 'All_Exports_' . $today . '.xlsx';
      
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '"');
      header('Cache-Control: max-age=0');
      header('Pragma: public');

      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $writer->save('php://output');

      $spreadsheet->disconnectWorksheets();
      unset($spreadsheet);
      exit;
      
    } catch (Exception $e) {
      $this->logError('Export All Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Export failed: ' . $e->getMessage()]);
      exit;
    }
  }

  private function getBulkUpdateData()
  {
    try {
      $filters = isset($_GET['filters']) ? $_GET['filters'] : [];

      if (!is_array($filters)) {
        echo json_encode(['success' => false, 'message' => 'Invalid filters']);
        return;
      }

      $filters = array_filter($filters, function($filter) {
        return in_array($filter, $this->allowedFilters);
      });

      if (empty($filters)) {
        echo json_encode(['success' => false, 'message' => 'Please select a valid filter first']);
        return;
      }

      $baseQuery = "FROM exports_t e
                    LEFT JOIN clients_t c ON e.subscriber_id = c.id
                    LEFT JOIN licenses_t l ON e.license_id = l.id
                    LEFT JOIN clearing_status_master_t cs ON e.clearing_status = cs.id
                    WHERE e.display = 'Y'";

      $filterClauses = [];
      $params = [];
      
      foreach ($filters as $filter) {
        switch ($filter) {
          case 'completed':
            $filterClauses[] = "cs.clearing_status = :status_completed";
            $params[':status_completed'] = 'CLEARING COMPLETED';
            break;
          case 'in_progress':
            $filterClauses[] = "cs.clearing_status = :status_in_progress";
            $params[':status_in_progress'] = 'IN PROGRESS';
            break;
          case 'in_transit':
            $filterClauses[] = "cs.clearing_status = :status_in_transit";
            $params[':status_in_transit'] = 'IN TRANSIT';
            break;
          case 'ceec_pending':
            $filterClauses[] = "(e.ceec_in_date IS NULL OR e.ceec_out_date IS NULL)";
            break;
          case 'min_div_pending':
            $filterClauses[] = "(e.min_div_in_date IS NULL OR e.min_div_out_date IS NULL)";
            break;
          case 'gov_docs_pending':
            $filterClauses[] = "(e.gov_docs_in_date IS NULL OR e.gov_docs_out_date IS NULL)";
            break;
          case 'audited_pending':
            $filterClauses[] = "e.audited_date IS NULL";
            break;
          case 'archived_pending':
            $filterClauses[] = "e.archived_date IS NULL";
            break;
          case 'dgda_in_pending':
            $filterClauses[] = "e.dgda_in_date IS NULL";
            break;
          case 'liquidation_pending':
            $filterClauses[] = "e.liquidation_date IS NULL";
            break;
          case 'quittance_pending':
            $filterClauses[] = "e.quittance_date IS NULL";
            break;
        }
      }
      
      $filterCondition = "";
      if (!empty($filterClauses)) {
        $filterCondition = " AND (" . implode(' OR ', $filterClauses) . ")";
      }

      $sql = "SELECT 
                e.id,
                e.mca_ref,
                e.loading_date,
                e.ceec_in_date,
                e.ceec_out_date,
                e.min_div_in_date,
                e.min_div_out_date,
                e.gov_docs_in_date,
                e.gov_docs_out_date,
                e.dgda_in_date,
                e.liquidation_date,
                e.quittance_date,
                e.audited_date,
                e.archived_date,
                c.short_name as subscriber_name
              {$baseQuery}
              {$filterCondition}
              ORDER BY e.id ASC";

      $exports = $this->db->customQuery($sql, $params);

      $relevantFields = [];
      
      $fieldMap = [
        'ceec_pending' => ['ceec_in_date', 'ceec_out_date'],
        'min_div_pending' => ['min_div_in_date', 'min_div_out_date'],
        'gov_docs_pending' => ['gov_docs_in_date', 'gov_docs_out_date'],
        'dgda_in_pending' => ['dgda_in_date'],
        'liquidation_pending' => ['liquidation_date'],
        'quittance_pending' => ['quittance_date'],
        'audited_pending' => ['audited_date'],
        'archived_pending' => ['archived_date']
      ];
      
      foreach ($filters as $filter) {
        if (isset($fieldMap[$filter])) {
          $relevantFields = array_merge($relevantFields, $fieldMap[$filter]);
        }
      }
      
      $relevantFields = array_unique($relevantFields);

      $exports = $this->sanitizeArray($exports);

      echo json_encode([
        'success' => true,
        'data' => $exports ?: [],
        'relevant_fields' => $relevantFields,
        'active_filters' => $filters,
        'count' => count($exports)
      ]);

    } catch (Exception $e) {
      $this->logError('Failed to get bulk update data', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to load bulk update data']);
    }
  }

  private function bulkUpdate()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
      return;
    }

    $this->validateCsrfToken();

    try {
      $updateData = isset($_POST['update_data']) ? json_decode($_POST['update_data'], true) : null;
      
      if (empty($updateData) || !is_array($updateData)) {
        echo json_encode(['success' => false, 'message' => 'No update data provided']);
        return;
      }

      if (count($updateData) > 100) {
        echo json_encode(['success' => false, 'message' => 'Maximum 100 records can be updated at once']);
        return;
      }

      $successCount = 0;
      $errorCount = 0;
      $errors = [];

      foreach ($updateData as $update) {
        $exportId = (int)($update['export_id'] ?? 0);
        
        if ($exportId <= 0) {
          $errorCount++;
          continue;
        }

        $export = $this->db->selectData('exports_t', 'loading_date, ceec_in_date, ceec_out_date, min_div_in_date, min_div_out_date, gov_docs_in_date, gov_docs_out_date', ['id' => $exportId, 'display' => 'Y']);
        
        if (empty($export)) {
          $errorCount++;
          $errors[] = "Export ID {$exportId}: Not found";
          continue;
        }

        $loadingDate = $export[0]['loading_date'];
        $currentCeecIn = $export[0]['ceec_in_date'];
        $currentCeecOut = $export[0]['ceec_out_date'];
        $currentMinDivIn = $export[0]['min_div_in_date'];
        $currentMinDivOut = $export[0]['min_div_out_date'];
        $currentGovDocsIn = $export[0]['gov_docs_in_date'];
        $currentGovDocsOut = $export[0]['gov_docs_out_date'];

        $data = [];
        $allowedFields = ['ceec_in_date', 'ceec_out_date', 'min_div_in_date', 'min_div_out_date', 
                         'gov_docs_in_date', 'gov_docs_out_date', 'dgda_in_date', 'liquidation_date', 
                         'quittance_date', 'audited_date', 'archived_date'];
        
        foreach ($update as $field => $value) {
          if ($field === 'export_id') continue;
          
          if (!in_array($field, $allowedFields)) {
            continue;
          }
          
          if (empty($value)) {
            $data[$field] = null;
          } else {
            $value = $this->sanitizeInput($value);
            
            if (!$this->isValidDate($value)) {
              $errorCount++;
              $errors[] = "Export ID {$exportId}: Invalid {$field} format";
              continue 2;
            }
            
            $data[$field] = $value;
          }
        }

        $ceecIn = $data['ceec_in_date'] ?? $currentCeecIn;
        $ceecOut = $data['ceec_out_date'] ?? $currentCeecOut;
        $minDivIn = $data['min_div_in_date'] ?? $currentMinDivIn;
        $minDivOut = $data['min_div_out_date'] ?? $currentMinDivOut;
        $govDocsIn = $data['gov_docs_in_date'] ?? $currentGovDocsIn;
        $govDocsOut = $data['gov_docs_out_date'] ?? $currentGovDocsOut;

        if ($ceecIn && $ceecOut && strtotime($ceecOut) < strtotime($ceecIn)) {
          $errorCount++;
          $errors[] = "Export ID {$exportId}: CEEC Out date cannot be before CEEC In date";
          continue;
        }

        if ($minDivIn && $minDivOut && strtotime($minDivOut) < strtotime($minDivIn)) {
          $errorCount++;
          $errors[] = "Export ID {$exportId}: Min Div Out date cannot be before Min Div In date";
          continue;
        }

        if ($govDocsIn && $govDocsOut && strtotime($govDocsOut) < strtotime($govDocsIn)) {
          $errorCount++;
          $errors[] = "Export ID {$exportId}: Gov Docs Out date cannot be before Gov Docs In date";
          continue;
        }

        if (empty($data)) {
          continue;
        }

        $data['updated_by'] = $_SESSION['user_id'] ?? 1;
        $data['updated_at'] = date('Y-m-d H:i:s');

        if (isset($data['ceec_in_date']) || isset($data['ceec_out_date']) || isset($data['min_div_in_date']) || isset($data['min_div_out_date'])) {
          $data['document_status'] = $this->calculateDocumentStatus($ceecIn, $ceecOut, $minDivIn, $minDivOut);
        }

        $success = $this->db->updateData('exports_t', $data, ['id' => $exportId]);

        if ($success) {
          $successCount++;
        } else {
          $errorCount++;
          $errors[] = "Export ID {$exportId}: Update failed";
        }
      }

      $message = "Bulk update completed: {$successCount} successful";
      if ($errorCount > 0) {
        $message .= ", {$errorCount} failed";
      }

      echo json_encode([
        'success' => true,
        'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
        'success_count' => $successCount,
        'error_count' => $errorCount,
        'errors' => array_map(function($error) {
          return htmlspecialchars($error, ENT_QUOTES, 'UTF-8');
        }, $errors)
      ]);

    } catch (Exception $e) {
      $this->logError('Exception during bulk update', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'An error occurred during bulk update.']);
    }
  }

  private function getLicenses()
  {
    try {
      $subscriberId = (int)($_GET['subscriber_id'] ?? 0);

      if ($subscriberId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid subscriber ID']);
        return;
      }

      $sql = "SELECT l.id, l.license_number, l.weight as available_weight 
              FROM licenses_t l
              WHERE l.client_id = :subscriber_id 
              AND l.display = 'Y' 
              AND l.status = 'ACTIVE'
              AND l.kind_id IN (3, 4)
              ORDER BY l.license_number ASC";

      $licenses = $this->db->customQuery($sql, [':subscriber_id' => $subscriberId]);
      $licenses = $this->sanitizeArray($licenses);

      echo json_encode([
        'success' => true,
        'data' => $licenses ?: []
      ]);

    } catch (Exception $e) {
      $this->logError('Failed to get licenses', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to load licenses']);
    }
  }

  private function getLicenseDetails()
  {
    try {
      $licenseId = (int)($_GET['license_id'] ?? 0);

      if ($licenseId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid license ID']);
        return;
      }

      $sql = "SELECT 
                l.id, l.license_number, l.client_id as subscriber_id,
                l.kind_id, l.type_of_goods_id, l.transport_mode_id,
                l.currency_id, l.supplier, l.weight as available_weight,
                k.kind_name, k.kind_short_name,
                tg.goods_type as type_of_goods_name, tg.goods_short_name,
                tm.transport_mode_name, tm.transport_letter,
                c.currency_short_name as currency_name
              FROM licenses_t l
              LEFT JOIN kind_master_t k ON l.kind_id = k.id
              LEFT JOIN type_of_goods_master_t tg ON l.type_of_goods_id = tg.id
              LEFT JOIN transport_mode_master_t tm ON l.transport_mode_id = tm.id
              LEFT JOIN currency_master_t c ON l.currency_id = c.id
              WHERE l.id = :license_id AND l.display = 'Y'";

      $license = $this->db->customQuery($sql, [':license_id' => $licenseId]);

      if (empty($license)) {
        echo json_encode(['success' => false, 'message' => 'License not found']);
        return;
      }

      $licenseData = $this->sanitizeArray([$license[0]])[0];

      echo json_encode([
        'success' => true,
        'data' => $licenseData
      ]);

    } catch (Exception $e) {
      $this->logError('Failed to get license details', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to load license details']);
    }
  }

  private function getNextMCASequence()
  {
    $this->validateCsrfToken();

    try {
      $subscriberId = (int)($_POST['subscriber_id'] ?? 0);
      $licenseId = (int)($_POST['license_id'] ?? 0);

      if ($subscriberId <= 0 || $licenseId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
        return;
      }

      $subscriber = $this->db->selectData('clients_t', 'short_name', ['id' => $subscriberId]);
      if (empty($subscriber)) {
        echo json_encode(['success' => false, 'message' => 'Subscriber not found']);
        return;
      }
      $clientShortName = strtoupper(trim($this->sanitizeInput($subscriber[0]['short_name'])));

      $sql = "SELECT k.kind_short_name, tg.goods_short_name, tm.transport_letter
              FROM licenses_t l
              LEFT JOIN kind_master_t k ON l.kind_id = k.id
              LEFT JOIN type_of_goods_master_t tg ON l.type_of_goods_id = tg.id
              LEFT JOIN transport_mode_master_t tm ON l.transport_mode_id = tm.id
              WHERE l.id = :license_id AND l.display = 'Y'";

      $license = $this->db->customQuery($sql, [':license_id' => $licenseId]);

      if (empty($license)) {
        echo json_encode(['success' => false, 'message' => 'License not found']);
        return;
      }

      $kindShortName = strtoupper(trim($this->sanitizeInput($license[0]['kind_short_name'] ?? '')));
      $goodsShortName = strtoupper(trim($this->sanitizeInput($license[0]['goods_short_name'] ?? '')));
      $transportLetter = strtoupper(trim($this->sanitizeInput($license[0]['transport_letter'] ?? '')));

      $year = substr(date('Y'), -2);
      $combinedCode = "{$kindShortName}{$goodsShortName}{$transportLetter}{$year}";
      $prefix = "{$clientShortName}-{$combinedCode}-";

      $sql = "SELECT mca_ref 
              FROM exports_t 
              WHERE mca_ref LIKE :prefix 
              AND display = 'Y'
              ORDER BY mca_ref DESC 
              LIMIT 1";

      $result = $this->db->customQuery($sql, [':prefix' => $prefix . '%']);

      $nextSequence = 1;

      if (!empty($result)) {
        $lastRef = $result[0]['mca_ref'];
        if (preg_match('/-(\d{4})$/', $lastRef, $matches)) {
          $lastSequence = (int)$matches[1];
          $nextSequence = $lastSequence + 1;
        }
      }

      $sequence = str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
      $mcaRef = "{$prefix}{$sequence}";

      echo json_encode([
        'success' => true,
        'mca_ref' => htmlspecialchars($mcaRef, ENT_QUOTES, 'UTF-8'),
        'sequence' => $nextSequence
      ]);

    } catch (Exception $e) {
      $this->logError('Failed to generate MCA sequence', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to generate MCA reference']);
    }
  }

  private function updateExport()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
      return;
    }

    $this->validateCsrfToken();

    try {
      $exportId = (int)($_POST['export_id'] ?? 0);
      if ($exportId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid export ID']);
        return;
      }

      $existing = $this->db->selectData('exports_t', '*', ['id' => $exportId, 'display' => 'Y']);
      if (empty($existing)) {
        echo json_encode(['success' => false, 'message' => 'Export not found']);
        return;
      }

      $validation = $this->validateExportData($_POST, $exportId);
      if (!$validation['success']) {
        echo json_encode($validation);
        return;
      }

      $ceecIn = !empty($_POST['ceec_in_date']) ? $_POST['ceec_in_date'] : null;
      $ceecOut = !empty($_POST['ceec_out_date']) ? $_POST['ceec_out_date'] : null;
      $minDivIn = !empty($_POST['min_div_in_date']) ? $_POST['min_div_in_date'] : null;
      $minDivOut = !empty($_POST['min_div_out_date']) ? $_POST['min_div_out_date'] : null;
      $govDocsIn = !empty($_POST['gov_docs_in_date']) ? $_POST['gov_docs_in_date'] : null;
      $govDocsOut = !empty($_POST['gov_docs_out_date']) ? $_POST['gov_docs_out_date'] : null;

      if ($ceecIn && $ceecOut && strtotime($ceecOut) < strtotime($ceecIn)) {
        echo json_encode(['success' => false, 'message' => 'CEEC Out date cannot be before CEEC In date']);
        return;
      }

      if ($minDivIn && $minDivOut && strtotime($minDivOut) < strtotime($minDivIn)) {
        echo json_encode(['success' => false, 'message' => 'Min Div Out date cannot be before Min Div In date']);
        return;
      }

      if ($govDocsIn && $govDocsOut && strtotime($govDocsOut) < strtotime($govDocsIn)) {
        echo json_encode(['success' => false, 'message' => 'Gov Docs Out date cannot be before Gov Docs In date']);
        return;
      }

      $data = $this->prepareExportData($_POST);
      $data['updated_by'] = (int)($_SESSION['user_id'] ?? 1);
      $data['updated_at'] = date('Y-m-d H:i:s');

      $oldDgdaSealNo = $existing[0]['dgda_seal_no'] ?? '';
      $newDgdaSealNo = $data['dgda_seal_no'] ?? '';
      
      if ($oldDgdaSealNo !== $newDgdaSealNo) {
        if (!empty($oldDgdaSealNo)) {
          $oldSeals = explode(',', $oldDgdaSealNo);
          foreach ($oldSeals as $sealNumber) {
            $sealNumber = trim($sealNumber);
            if (!empty($sealNumber)) {
              $this->db->customQuery(
                "UPDATE seal_individual_numbers_t 
                 SET status = 'Available', notes = NULL, updated_by = :user_id, updated_at = NOW() 
                 WHERE seal_number = :seal_number",
                [':user_id' => (int)($_SESSION['user_id'] ?? 1), ':seal_number' => $sealNumber]
              );
            }
          }
        }
        
        if (!empty($newDgdaSealNo)) {
          $newSeals = explode(',', $newDgdaSealNo);
          foreach ($newSeals as $sealNumber) {
            $sealNumber = trim($sealNumber);
            if (!empty($sealNumber)) {
              $this->db->customQuery(
                "UPDATE seal_individual_numbers_t 
                 SET status = 'Used', notes = :notes, updated_by = :user_id, updated_at = NOW() 
                 WHERE seal_number = :seal_number",
                [
                  ':notes' => 'Used in Export MCA: ' . ($data['mca_ref'] ?? ''),
                  ':user_id' => (int)($_SESSION['user_id'] ?? 1),
                  ':seal_number' => $sealNumber
                ]
              );
            }
          }
        }
      }

      $success = $this->db->updateData('exports_t', $data, ['id' => $exportId]);

      if ($success) {
        $this->logInfo('Export updated successfully', ['export_id' => $exportId]);
        echo json_encode(['success' => true, 'message' => 'Export updated successfully!']);
      } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update export.']);
      }
    } catch (Exception $e) {
      $this->logError('Exception during export update', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'An error occurred while updating.']);
    }
  }

  private function deleteExport()
  {
    $this->validateCsrfToken();

    try {
      $exportId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

      if ($exportId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid export ID']);
        return;
      }

      $export = $this->db->selectData('exports_t', '*', ['id' => $exportId, 'display' => 'Y']);
      if (empty($export)) {
        echo json_encode(['success' => false, 'message' => 'Export not found']);
        return;
      }

      $dgdaSealNo = $export[0]['dgda_seal_no'] ?? '';
      if (!empty($dgdaSealNo)) {
        $seals = explode(',', $dgdaSealNo);
        foreach ($seals as $sealNumber) {
          $sealNumber = trim($sealNumber);
          if (!empty($sealNumber)) {
            $this->db->customQuery(
              "UPDATE seal_individual_numbers_t 
               SET status = 'Available', notes = NULL, updated_by = :user_id, updated_at = NOW() 
               WHERE seal_number = :seal_number",
              [':user_id' => (int)($_SESSION['user_id'] ?? 1), ':seal_number' => $sealNumber]
            );
          }
        }
      }

      $success = $this->db->updateData('exports_t', [
        'display' => 'N',
        'updated_by' => (int)($_SESSION['user_id'] ?? 1),
        'updated_at' => date('Y-m-d H:i:s')
      ], ['id' => $exportId]);

      if ($success) {
        $this->logInfo('Export deleted successfully', ['export_id' => $exportId]);
        echo json_encode(['success' => true, 'message' => 'Export deleted successfully!']);
      } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete export']);
      }
    } catch (Exception $e) {
      $this->logError('Exception during export delete', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'An error occurred while deleting.']);
    }
  }

  private function getExport()
  {
    try {
      $exportId = (int)($_GET['id'] ?? 0);

      if ($exportId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid export ID']);
        return;
      }

      $sql = "SELECT e.*, 
                c.short_name as subscriber_name, 
                c.liquidation_paid_by as client_liquidation_paid_by, 
                l.license_number, 
                l.weight as license_available_weight,
                l.transport_mode_id as license_transport_mode_id,
                k.kind_name,
                tg.goods_type as type_of_goods_name,
                tm.transport_mode_name,
                curr.currency_short_name as currency_name
              FROM exports_t e
              LEFT JOIN clients_t c ON e.subscriber_id = c.id
              LEFT JOIN licenses_t l ON e.license_id = l.id
              LEFT JOIN kind_master_t k ON e.kind = k.id
              LEFT JOIN type_of_goods_master_t tg ON e.type_of_goods = tg.id
              LEFT JOIN transport_mode_master_t tm ON e.transport_mode = tm.id
              LEFT JOIN currency_master_t curr ON e.currency = curr.id
              WHERE e.id = :id AND e.display = 'Y'";

      $export = $this->db->customQuery($sql, [':id' => $exportId]);

      if (!empty($export)) {
        $export = $this->sanitizeArray($export);
        echo json_encode(['success' => true, 'data' => $export[0]]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Export not found']);
      }
    } catch (Exception $e) {
      $this->logError('Exception while fetching export', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to load export data']);
    }
  }

  private function listExports()
  {
    try {
      $draw = isset($_GET['draw']) ? (int)$_GET['draw'] : 1;
      $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
      $length = isset($_GET['length']) ? (int)$_GET['length'] : 25;
      $searchValue = isset($_GET['search']['value']) ? $this->sanitizeInput(trim($_GET['search']['value'])) : '';
      
      $filters = isset($_GET['filters']) ? $_GET['filters'] : [];
      if (!is_array($filters)) {
        $filters = [];
      }
      $filters = array_filter($filters, function($filter) {
        return in_array($filter, $this->allowedFilters);
      });
      
      $orderColumnIndex = isset($_GET['order'][0]['column']) ? (int)$_GET['order'][0]['column'] : 0;
      $orderDirection = isset($_GET['order'][0]['dir']) && strtolower($_GET['order'][0]['dir']) === 'asc' ? 'ASC' : 'DESC';
      
      $columns = ['e.id', 'e.mca_ref', 'c.short_name', 'l.license_number', 'e.invoice', 
                  'e.loading_date', 'e.weight', 'e.fob', 'cs.clearing_status'];
      $orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'e.id';

      $baseQuery = "FROM exports_t e
                    LEFT JOIN clients_t c ON e.subscriber_id = c.id
                    LEFT JOIN licenses_t l ON e.license_id = l.id
                    LEFT JOIN clearing_status_master_t cs ON e.clearing_status = cs.id
                    WHERE e.display = 'Y'";

      $searchCondition = "";
      $filterCondition = "";
      $params = [];
      
      if (!empty($searchValue)) {
        $searchCondition = " AND (
          e.mca_ref LIKE :search OR
          e.invoice LIKE :search OR
          c.short_name LIKE :search OR
          l.license_number LIKE :search OR
          cs.clearing_status LIKE :search
        )";
        $params[':search'] = "%{$searchValue}%";
      }

      if (!empty($filters)) {
        $filterClauses = [];
        
        foreach ($filters as $filter) {
          switch ($filter) {
            case 'completed':
              $filterClauses[] = "cs.clearing_status = :status_completed";
              $params[':status_completed'] = 'CLEARING COMPLETED';
              break;
            case 'in_progress':
              $filterClauses[] = "cs.clearing_status = :status_in_progress";
              $params[':status_in_progress'] = 'IN PROGRESS';
              break;
            case 'in_transit':
              $filterClauses[] = "cs.clearing_status = :status_in_transit";
              $params[':status_in_transit'] = 'IN TRANSIT';
              break;
            case 'ceec_pending':
              $filterClauses[] = "(e.ceec_in_date IS NULL OR e.ceec_out_date IS NULL)";
              break;
            case 'min_div_pending':
              $filterClauses[] = "(e.min_div_in_date IS NULL OR e.min_div_out_date IS NULL)";
              break;
            case 'gov_docs_pending':
              $filterClauses[] = "(e.gov_docs_in_date IS NULL OR e.gov_docs_out_date IS NULL)";
              break;
            case 'audited_pending':
              $filterClauses[] = "e.audited_date IS NULL";
              break;
            case 'archived_pending':
              $filterClauses[] = "e.archived_date IS NULL";
              break;
            case 'dgda_in_pending':
              $filterClauses[] = "e.dgda_in_date IS NULL";
              break;
            case 'liquidation_pending':
              $filterClauses[] = "e.liquidation_date IS NULL";
              break;
            case 'quittance_pending':
              $filterClauses[] = "e.quittance_date IS NULL";
              break;
          }
        }
        
        if (!empty($filterClauses)) {
          $filterCondition = " AND (" . implode(' OR ', $filterClauses) . ")";
        }
      }

      $totalSql = "SELECT COUNT(*) as total FROM exports_t WHERE display = 'Y'";
      $totalResult = $this->db->customQuery($totalSql);
      $totalRecords = (int)($totalResult[0]['total'] ?? 0);

      $filteredSql = "SELECT COUNT(*) as total {$baseQuery} {$searchCondition} {$filterCondition}";
      $filteredResult = $this->db->customQuery($filteredSql, $params);
      $filteredRecords = (int)($filteredResult[0]['total'] ?? 0);

      $dataSql = "SELECT 
                    e.id, e.mca_ref, e.invoice, e.loading_date,
                    e.weight, e.fob,
                    c.short_name as subscriber_name,
                    l.license_number,
                    cs.clearing_status
                  {$baseQuery}
                  {$searchCondition}
                  {$filterCondition}
                  ORDER BY {$orderColumn} {$orderDirection}
                  LIMIT :limit OFFSET :offset";

      $params[':limit'] = $length;
      $params[':offset'] = $start;

      $exports = $this->db->customQuery($dataSql, $params);
      $exports = $this->sanitizeArray($exports);

      echo json_encode([
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $exports ?: []
      ]);

    } catch (Exception $e) {
      $this->logError('Exception in listExports', ['error' => $e->getMessage()]);
      echo json_encode([
        'draw' => $_GET['draw'] ?? 1,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => []
      ]);
    }
  }

  private function getStatistics()
  {
    try {
      $sql = "SELECT 
                COUNT(*) as total_exports,
                COALESCE(SUM(weight), 0) as total_weight,
                COALESCE(SUM(fob), 0) as total_fob
              FROM exports_t
              WHERE display = 'Y'";

      $stats = $this->db->customQuery($sql);

      $statusSql = "SELECT cs.clearing_status, COUNT(e.id) as count
                    FROM exports_t e
                    LEFT JOIN clearing_status_master_t cs ON e.clearing_status = cs.id
                    WHERE e.display = 'Y'
                    GROUP BY cs.clearing_status";

      $statusCounts = $this->db->customQuery($statusSql);

      $ceecPending = $this->db->customQuery("SELECT COUNT(*) as count FROM exports_t WHERE display = 'Y' AND (ceec_in_date IS NULL OR ceec_out_date IS NULL)");
      $minDivPending = $this->db->customQuery("SELECT COUNT(*) as count FROM exports_t WHERE display = 'Y' AND (min_div_in_date IS NULL OR min_div_out_date IS NULL)");
      $govDocsPending = $this->db->customQuery("SELECT COUNT(*) as count FROM exports_t WHERE display = 'Y' AND (gov_docs_in_date IS NULL OR gov_docs_out_date IS NULL)");
      $auditedPending = $this->db->customQuery("SELECT COUNT(*) as count FROM exports_t WHERE display = 'Y' AND audited_date IS NULL");
      $archivedPending = $this->db->customQuery("SELECT COUNT(*) as count FROM exports_t WHERE display = 'Y' AND archived_date IS NULL");
      $dgdaInPending = $this->db->customQuery("SELECT COUNT(*) as count FROM exports_t WHERE display = 'Y' AND dgda_in_date IS NULL");
      $liquidationPending = $this->db->customQuery("SELECT COUNT(*) as count FROM exports_t WHERE display = 'Y' AND liquidation_date IS NULL");
      $quittancePending = $this->db->customQuery("SELECT COUNT(*) as count FROM exports_t WHERE display = 'Y' AND quittance_date IS NULL");

      $statusData = [];
      foreach ($statusCounts as $status) {
        $statusKey = strtolower(str_replace(' ', '_', $status['clearing_status'] ?? 'unknown'));
        $statusData[$statusKey] = (int)$status['count'];
      }

      if (!empty($stats)) {
        echo json_encode([
          'success' => true,
          'data' => [
            'total_exports' => (int)$stats[0]['total_exports'],
            'total_weight' => number_format((float)$stats[0]['total_weight'], 2, '.', ''),
            'total_fob' => number_format((float)$stats[0]['total_fob'], 2, '.', ''),
            'total_completed' => $statusData['clearing_completed'] ?? 0,
            'in_progress' => $statusData['in_progress'] ?? 0,
            'in_transit' => $statusData['in_transit'] ?? 0,
            'ceec_pending' => (int)($ceecPending[0]['count'] ?? 0),
            'min_div_pending' => (int)($minDivPending[0]['count'] ?? 0),
            'gov_docs_pending' => (int)($govDocsPending[0]['count'] ?? 0),
            'audited_pending' => (int)($auditedPending[0]['count'] ?? 0),
            'archived_pending' => (int)($archivedPending[0]['count'] ?? 0),
            'dgda_in_pending' => (int)($dgdaInPending[0]['count'] ?? 0),
            'liquidation_pending' => (int)($liquidationPending[0]['count'] ?? 0),
            'quittance_pending' => (int)($quittancePending[0]['count'] ?? 0)
          ]
        ]);
      }
    } catch (Exception $e) {
      $this->logError('Failed to get statistics', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to load statistics']);
    }
  }

  private function validateExportData($post, $exportId = null)
  {
    $errors = [];

    $requiredFields = [
      'subscriber_id' => 'Subscriber',
      'license_id' => 'License Number',
      'regime' => 'Regime',
      'types_of_clearance' => 'Types of Clearance'
    ];

    foreach ($requiredFields as $field => $label) {
      if (empty($post[$field])) {
        $errors[] = htmlspecialchars("{$label} is required", ENT_QUOTES, 'UTF-8');
      }
    }

    if (empty($post['mca_ref'])) {
      $errors[] = 'MCA Reference is required';
    } else {
      $mcaRef = $this->sanitizeInput(trim($post['mca_ref']));
      
      if (!preg_match('/^[A-Z0-9]+-[A-Z0-9]+\d{2}-\d{4}$/', $mcaRef)) {
        $errors[] = 'MCA Reference has invalid format';
      }
      
      $sql = "SELECT id FROM exports_t WHERE mca_ref = :mca_ref AND display = 'Y'";
      $params = [':mca_ref' => $mcaRef];
      
      if ($exportId) {
        $sql .= " AND id != :export_id";
        $params[':export_id'] = $exportId;
      }
      
      $exists = $this->db->customQuery($sql, $params);
      if ($exists) {
        $errors[] = 'MCA Reference already exists';
      }
    }

    if (!empty($errors)) {
      return [
        'success' => false,
        'message' => '<ul style="text-align:left;"><li>' . implode('</li><li>', $errors) . '</li></ul>'
      ];
    }

    return ['success' => true];
  }

  private function prepareExportData($post)
  {
    return [
      'subscriber_id' => !empty($post['subscriber_id']) ? $this->toInt($post['subscriber_id']) : null,
      'license_id' => !empty($post['license_id']) ? $this->toInt($post['license_id']) : null,
      'kind' => !empty($post['kind']) ? $this->toInt($post['kind']) : null,
      'type_of_goods' => !empty($post['type_of_goods']) ? $this->toInt($post['type_of_goods']) : null,
      'transport_mode' => !empty($post['transport_mode']) ? $this->toInt($post['transport_mode']) : null,
      'mca_ref' => !empty($post['mca_ref']) ? $this->clean($post['mca_ref']) : null,
      'currency' => !empty($post['currency']) ? $this->toInt($post['currency']) : null,
      'supplier' => !empty($post['supplier']) ? $this->clean($post['supplier']) : null,
      'regime' => !empty($post['regime']) ? $this->toInt($post['regime']) : null,
      'types_of_clearance' => !empty($post['types_of_clearance']) ? $this->toInt($post['types_of_clearance']) : null,
      'invoice' => !empty($post['invoice']) ? $this->clean($post['invoice']) : null,
      'po_ref' => !empty($post['po_ref']) ? $this->clean($post['po_ref']) : null,
      'available_weight' => !empty($post['available_weight']) && is_numeric($post['available_weight']) ? round((float)$post['available_weight'], 2) : null,
      'weight' => !empty($post['weight']) && is_numeric($post['weight']) ? round((float)$post['weight'], 2) : null,
      'fob' => !empty($post['fob']) && is_numeric($post['fob']) ? round((float)$post['fob'], 2) : null,
      'horse' => !empty($post['horse']) ? $this->clean($post['horse']) : null,
      'trailer_1' => !empty($post['trailer_1']) ? $this->clean($post['trailer_1']) : null,
      'trailer_2' => !empty($post['trailer_2']) ? $this->clean($post['trailer_2']) : null,
      'wagon_ref' => !empty($post['wagon_ref']) ? $this->clean($post['wagon_ref']) : null,
      'container' => !empty($post['container']) ? $this->clean($post['container']) : null,
      'feet_container' => !empty($post['feet_container']) ? $this->clean($post['feet_container']) : null,
      'transporter' => !empty($post['transporter']) ? $this->clean($post['transporter']) : null,
      'site_of_loading_id' => !empty($post['site_of_loading_id']) ? $this->toInt($post['site_of_loading_id']) : null,
      'destination' => !empty($post['destination']) ? $this->clean($post['destination']) : null,
      'loading_date' => !empty($post['loading_date']) && $this->isValidDate($post['loading_date']) ? $post['loading_date'] : null,
      'pv_date' => !empty($post['pv_date']) && $this->isValidDate($post['pv_date']) ? $post['pv_date'] : null,
      'bp_date' => !empty($post['bp_date']) && $this->isValidDate($post['bp_date']) ? $post['bp_date'] : null,
      'demande_attestation_date' => !empty($post['demande_attestation_date']) && $this->isValidDate($post['demande_attestation_date']) ? $post['demande_attestation_date'] : null,
      'assay_date' => !empty($post['assay_date']) && $this->isValidDate($post['assay_date']) ? $post['assay_date'] : null,
      'lot_number' => !empty($post['lot_number']) ? $this->clean($post['lot_number']) : null,
      'number_of_seals' => !empty($post['number_of_seals']) ? $this->toInt($post['number_of_seals']) : null,
      'dgda_seal_no' => !empty($post['dgda_seal_no']) ? $this->clean($post['dgda_seal_no']) : null,
      'number_of_bags' => !empty($post['number_of_bags']) ? $this->toInt($post['number_of_bags']) : null,
      'archive_reference' => !empty($post['archive_reference']) ? $this->clean($post['archive_reference']) : null,
      'ceec_in_date' => !empty($post['ceec_in_date']) && $this->isValidDate($post['ceec_in_date']) ? $post['ceec_in_date'] : null,
      'ceec_out_date' => !empty($post['ceec_out_date']) && $this->isValidDate($post['ceec_out_date']) ? $post['ceec_out_date'] : null,
      'min_div_in_date' => !empty($post['min_div_in_date']) && $this->isValidDate($post['min_div_in_date']) ? $post['min_div_in_date'] : null,
      'min_div_out_date' => !empty($post['min_div_out_date']) && $this->isValidDate($post['min_div_out_date']) ? $post['min_div_out_date'] : null,
      'cgea_doc_ref' => !empty($post['cgea_doc_ref']) ? $this->clean($post['cgea_doc_ref']) : null,
      'segues_rcv_ref' => !empty($post['segues_rcv_ref']) ? $this->clean($post['segues_rcv_ref']) : null,
      'segues_payment_date' => !empty($post['segues_payment_date']) && $this->isValidDate($post['segues_payment_date']) ? $post['segues_payment_date'] : null,
      'document_status' => !empty($post['document_status']) ? $this->toInt($post['document_status']) : null,
      'customs_clearing_code' => !empty($post['customs_clearing_code']) ? $this->clean($post['customs_clearing_code']) : null,
      'dgda_in_date' => !empty($post['dgda_in_date']) && $this->isValidDate($post['dgda_in_date']) ? $post['dgda_in_date'] : null,
      'declaration_reference' => !empty($post['declaration_reference']) ? $this->clean($post['declaration_reference']) : null,
      'liquidation_reference' => !empty($post['liquidation_reference']) ? $this->clean($post['liquidation_reference']) : null,
      'liquidation_date' => !empty($post['liquidation_date']) && $this->isValidDate($post['liquidation_date']) ? $post['liquidation_date'] : null,
      'liquidation_paid_by' => !empty($post['liquidation_paid_by']) ? $this->clean($post['liquidation_paid_by']) : null,
      'liquidation_amount' => !empty($post['liquidation_amount']) && is_numeric($post['liquidation_amount']) ? round((float)$post['liquidation_amount'], 2) : null,
      'quittance_reference' => !empty($post['quittance_reference']) ? $this->clean($post['quittance_reference']) : null,
      'quittance_date' => !empty($post['quittance_date']) && $this->isValidDate($post['quittance_date']) ? $post['quittance_date'] : null,
      'dgda_out_date' => !empty($post['dgda_out_date']) && $this->isValidDate($post['dgda_out_date']) ? $post['dgda_out_date'] : null,
      'gov_docs_in_date' => !empty($post['gov_docs_in_date']) && $this->isValidDate($post['gov_docs_in_date']) ? $post['gov_docs_in_date'] : null,
      'gov_docs_out_date' => !empty($post['gov_docs_out_date']) && $this->isValidDate($post['gov_docs_out_date']) ? $post['gov_docs_out_date'] : null,
      'dispatch_deliver_date' => !empty($post['dispatch_deliver_date']) && $this->isValidDate($post['dispatch_deliver_date']) ? $post['dispatch_deliver_date'] : null,
      'kanyaka_arrival_date' => !empty($post['kanyaka_arrival_date']) && $this->isValidDate($post['kanyaka_arrival_date']) ? $post['kanyaka_arrival_date'] : null,
      'kanyaka_departure_date' => !empty($post['kanyaka_departure_date']) && $this->isValidDate($post['kanyaka_departure_date']) ? $post['kanyaka_departure_date'] : null,
      'border_arrival_date' => !empty($post['border_arrival_date']) && $this->isValidDate($post['border_arrival_date']) ? $post['border_arrival_date'] : null,
      'exit_drc_date' => !empty($post['exit_drc_date']) && $this->isValidDate($post['exit_drc_date']) ? $post['exit_drc_date'] : null,
      'exit_point_id' => !empty($post['exit_point_id']) ? $this->toInt($post['exit_point_id']) : null,
      'end_of_formalities_date' => !empty($post['end_of_formalities_date']) && $this->isValidDate($post['end_of_formalities_date']) ? $post['end_of_formalities_date'] : null,
      'truck_status' => !empty($post['truck_status']) ? $this->toInt($post['truck_status']) : null,
      'lmc_id' => !empty($post['lmc_id']) ? $this->clean($post['lmc_id']) : null,
      'ogefrem_inv_ref' => !empty($post['ogefrem_inv_ref']) ? $this->clean($post['ogefrem_inv_ref']) : null,
      'loading_to_dispatch_date' => !empty($post['loading_to_dispatch_date']) && $this->isValidDate($post['loading_to_dispatch_date']) ? $post['loading_to_dispatch_date'] : null,
      'audited_date' => !empty($post['audited_date']) && $this->isValidDate($post['audited_date']) ? $post['audited_date'] : null,
      'archived_date' => !empty($post['archived_date']) && $this->isValidDate($post['archived_date']) ? $post['archived_date'] : null,
      'remarks' => !empty($post['remarks']) ? $this->sanitizeJson($post['remarks']) : null,
      'clearing_status' => !empty($post['clearing_status']) ? $this->toInt($post['clearing_status']) : null,
    ];
  }

  private function sanitizeInput($value)
  {
    if (is_array($value)) {
      return array_map([$this, 'sanitizeInput'], $value);
    }
    
    if (!is_string($value)) {
      return $value;
    }
    
    $value = str_replace(chr(0), '', $value);
    $value = trim($value);
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);
    
    return $value;
  }

  private function sanitizeJson($jsonString)
  {
    if (empty($jsonString)) return null;
    
    $decoded = json_decode($jsonString, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
      return null;
    }
    
    if (is_array($decoded)) {
      $decoded = $this->sanitizeInput($decoded);
      return json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    
    return null;
  }

  private function clean($value)
  {
    if (empty($value)) return null;
    
    $value = $this->sanitizeInput($value);
    $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);
    $value = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $value);
    $value = preg_replace('/on\w+\s*=\s*["\'][^"\']*["\']/i', '', $value);
    
    if (strlen($value) > 255) {
      $value = substr($value, 0, 255);
    }
    
    return $value;
  }

  private function toInt($value)
  {
    if (!is_numeric($value)) {
      return null;
    }
    
    $int = (int)$value;
    return $int > 0 ? $int : null;
  }

  private function isValidDate($date)
  {
    if (empty($date)) {
      return false;
    }
    
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
  }

  private function validateCsrfToken()
  {
    $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
    
    if (empty($token) || empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time'])) {
      $this->logError('CSRF token missing or expired', ['ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'Security token expired. Please refresh the page.']);
      exit;
    }

    if ((time() - $_SESSION['csrf_token_time']) > 3600) {
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'Security token expired. Please refresh the page.']);
      exit;
    }

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
      $this->logError('CSRF token validation failed', ['ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'Invalid security token. Please refresh the page.']);
      exit;
    }
  }

  private function logError($message, $context = [])
  {
    $logEntry = [
      'timestamp' => date('Y-m-d H:i:s'),
      'level' => 'ERROR',
      'message' => $message,
      'user_id' => $_SESSION['user_id'] ?? 'guest',
      'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
      'context' => $context
    ];
    
    $logLine = json_encode($logEntry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    @file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);
  }

  private function logInfo($message, $context = [])
  {
    $logEntry = [
      'timestamp' => date('Y-m-d H:i:s'),
      'level' => 'INFO',
      'message' => $message,
      'user_id' => $_SESSION['user_id'] ?? 'guest',
      'context' => $context
    ];
    
    $logLine = json_encode($logEntry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    @file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);
  }
}