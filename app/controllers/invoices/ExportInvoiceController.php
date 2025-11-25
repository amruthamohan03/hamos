<?php

class ExportInvoiceController extends Controller
{
  private $db;
  private $logFile;
  private $logoPath;

  public function __construct()
  {
    $this->db = new Database();
    $this->logFile = __DIR__ . '/../../logs/export_invoice.log';
    $this->logoPath = __DIR__ . '/../../../public/images/logo.jpg';
    
    $logDir = dirname($this->logFile);
    if (!is_dir($logDir)) {
      @mkdir($logDir, 0755, true);
    }
  }

  public function index()
  {
    if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time']) || (time() - $_SESSION['csrf_token_time']) > 3600) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      $_SESSION['csrf_token_time'] = time();
    }

    $sql = "SELECT DISTINCT c.id, c.short_name, c.company_name 
            FROM clients_t c
            INNER JOIN licenses_t l ON c.id = l.client_id
            WHERE c.display = 'Y' AND l.display = 'Y'
            ORDER BY c.short_name ASC";
    $subscribers = $this->db->customQuery($sql) ?: [];
    
    $currencies = $this->db->selectData('currency_master_t', 'id, currency_name, currency_short_name', ['display' => 'Y'], 'currency_short_name ASC') ?: [];

    $data = [
      'title' => 'Export Invoice Management',
      'subscribers' => $this->sanitizeArray($subscribers),
      'currencies' => $this->sanitizeArray($currencies),
      'csrf_token' => $_SESSION['csrf_token']
    ];

    $this->viewWithLayout('invoices/exportinvoice', $data);
  }

  public function crudData($action = 'listing')
  {
    while (ob_get_level()) ob_end_clean();
    ob_start();

    header('Content-Type: application/json; charset=utf-8');
    header('X-Content-Type-Options: nosniff');

    try {
      switch ($action) {
        case 'insert':
        case 'insertion':
          $this->insertInvoice();
          break;
        case 'update':
          $this->updateInvoice();
          break;
        case 'deletion':
          $this->deleteInvoice();
          break;
        case 'getInvoice':
          $this->getInvoice();
          break;
        case 'listing':
          $this->listInvoices();
          break;
        case 'statistics':
          $this->getStatistics();
          break;
        case 'getLicenses':
          $this->getLicenses();
          break;
        case 'getExportMCAReferences':
          $this->getExportMCAReferences();
          break;
        case 'getExportMCADetails':
          $this->getExportMCADetails();
          break;
        case 'getBanks':
          $this->getBanks();
          break;
        case 'getNextInvoiceRefForClient':
          $this->getNextInvoiceRefForClient();
          break;
        case 'getSubscriberDetails':
          $this->getSubscriberDetails();
          break;
        case 'getAllQuotationsForSubscriber':
          $this->getAllQuotationsForSubscriber();
          break;
        case 'getQuotationItems':
          $this->getQuotationItems();
          break;
        case 'exportInvoice':
          $this->exportInvoice();
          break;
        case 'exportAll':
          $this->exportAllInvoices();
          break;
        case 'viewPDF':
          $this->viewPDF();
          break;
        default:
          echo json_encode(['success' => false, 'message' => 'Invalid action']);
      }
    } catch (Exception $e) {
      $this->logError("FATAL Exception in crudData: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }

    ob_end_flush();
    exit;
  }

  private function getSubscriberDetails()
  {
    try {
      $subscriberId = (int)($_GET['subscriber_id'] ?? 0);
      if ($subscriberId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid subscriber ID', 'data' => null]);
        return;
      }
      $sql = "SELECT id, short_name, company_name, address, rccm_number, nif_number, id_nat_number, import_export_number 
              FROM clients_t WHERE id = ? AND display = 'Y' LIMIT 1";
      $result = $this->db->customQuery($sql, [$subscriberId]);
      if (!empty($result)) {
        echo json_encode(['success' => true, 'data' => $this->sanitizeArray($result)[0]]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Subscriber not found', 'data' => null]);
      }
    } catch (Exception $e) {
      $this->logError("Error getting subscriber details: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load subscriber details', 'data' => null]);
    }
  }

  private function getAllQuotationsForSubscriber()
  {
    try {
      $subscriberId = (int)($_GET['subscriber_id'] ?? 0);
      if ($subscriberId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid subscriber ID', 'data' => []]);
        return;
      }
      $sql = "SELECT q.id, q.quotation_ref, q.quotation_date, q.sub_total, q.vat_amount, q.total_amount
              FROM quotations_t q WHERE q.client_id = ? AND q.display = 'Y' ORDER BY q.quotation_date DESC, q.id DESC";
      $quotations = $this->db->customQuery($sql, [$subscriberId]);
      echo json_encode(['success' => true, 'data' => $this->sanitizeArray($quotations ?: [])]);
    } catch (Exception $e) {
      $this->logError("Error in getAllQuotationsForSubscriber: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load quotations', 'data' => []]);
    }
  }

  private function getQuotationItems()
  {
    try {
      $quotationId = (int)($_GET['quotation_id'] ?? 0);
      $clientId = (int)($_GET['client_id'] ?? 0);
      if ($quotationId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid quotation ID', 'data' => []]);
        return;
      }
      $quotationSql = "SELECT q.id, q.client_id, q.quotation_ref, q.sub_total, q.vat_amount, q.total_amount, q.quotation_date
                       FROM quotations_t q WHERE q.id = ? AND q.display = 'Y' LIMIT 1";
      $quotationResult = $this->db->customQuery($quotationSql, [$quotationId]);
      if (empty($quotationResult)) {
        echo json_encode(['success' => false, 'message' => 'Quotation not found', 'data' => []]);
        return;
      }
      $quotation = $quotationResult[0];
      if ($clientId > 0 && (int)$quotation['client_id'] !== $clientId) {
        echo json_encode(['success' => false, 'message' => 'Quotation does not belong to selected client', 'data' => []]);
        return;
      }
      $itemsSql = "SELECT qi.id, qi.quotation_id, qi.category_id, qi.description_id, qi.quantity, qi.unit_id, qi.unit_text,
                          qi.taux_usd, qi.cost_usd, qi.subtotal_usd, qi.has_tva, qi.tva_usd, qi.total_usd, qi.currency_id,
                          qd.description_name, qc.category_name, qc.category_header, qc.display_order, u.unit_name, curr.currency_short_name
                   FROM quotation_items_t qi
                   LEFT JOIN quotation_descriptions_t qd ON qi.description_id = qd.id
                   LEFT JOIN quotation_categories_t qc ON qi.category_id = qc.id
                   LEFT JOIN units_master_t u ON qi.unit_id = u.id
                   LEFT JOIN currency_master_t curr ON qi.currency_id = curr.id
                   WHERE qi.quotation_id = ? AND qi.display = 'Y' ORDER BY qc.display_order ASC, qi.id ASC";
      $items = $this->db->customQuery($itemsSql, [$quotationId]);
      $items = $this->sanitizeArray($items ?: []);
      $groupedItems = [];
      foreach ($items as $item) {
        $categoryId = $item['category_id'] ?? 0;
        $categoryName = $item['category_name'] ?? 'Uncategorized';
        $categoryHeader = $item['category_header'] ?? $categoryName;
        if (!isset($groupedItems[$categoryId])) {
          $groupedItems[$categoryId] = [
            'category_id' => $categoryId,
            'category_name' => $categoryName,
            'category_header' => $categoryHeader,
            'display_order' => $item['display_order'] ?? 999,
            'category_total' => 0,
            'category_tva' => 0,
            'items' => []
          ];
        }
        $groupedItems[$categoryId]['category_total'] += (float)($item['subtotal_usd'] ?? 0);
        $groupedItems[$categoryId]['category_tva'] += (float)($item['tva_usd'] ?? 0);
        $groupedItems[$categoryId]['items'][] = $item;
      }
      $categorizedItems = array_values($groupedItems);
      usort($categorizedItems, function($a, $b) { return ($a['display_order'] ?? 999) - ($b['display_order'] ?? 999); });
      echo json_encode(['success' => true, 'quotation' => $this->sanitizeArray($quotation), 'items' => $items, 'categorized_items' => $categorizedItems]);
    } catch (Exception $e) {
      $this->logError("Error in getQuotationItems: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load quotation items', 'data' => []]);
    }
  }

  private function getLicenses()
  {
    try {
      $clientId = (int)($_GET['subscriber_id'] ?? 0);
      if ($clientId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid subscriber ID', 'data' => []]);
        return;
      }
      $sql = "SELECT l.id, l.license_number FROM licenses_t l WHERE l.client_id = ? AND l.display = 'Y' AND l.status = 'ACTIVE' ORDER BY l.license_number ASC";
      echo json_encode(['success' => true, 'data' => $this->sanitizeArray($this->db->customQuery($sql, [$clientId]) ?: [])]);
    } catch (Exception $e) {
      $this->logError("Error getting licenses: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load licenses', 'data' => []]);
    }
  }

  private function getExportMCAReferences()
  {
    try {
      $subscriberId = (int)($_GET['subscriber_id'] ?? 0);
      $licenseId = (int)($_GET['license_id'] ?? 0);
      if ($subscriberId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid subscriber ID', 'data' => []]);
        return;
      }
      $sql = "SELECT e.id, e.mca_ref, e.commodity, e.quantity_mt, e.destination, e.transporter, e.horse, e.trailer_1, e.trailer_2, 
                     e.lot_number, e.loading_date, e.clearing_completed_date, e.clearing_status, e.declaration_ref, e.declaration_date, 
                     e.bcc_rate, e.liquidation_ref, e.liquidation_date, e.liquidation_amount_cdf, e.quittance_ref, e.quittance_date, 
                     e.liquidation_amount_usd
              FROM exports_t e WHERE e.subscriber_id = ? AND e.display = 'Y' ORDER BY e.id DESC LIMIT 200";
      $params = [$subscriberId];
      if ($licenseId > 0) {
        $sql = "SELECT e.id, e.mca_ref, e.commodity, e.quantity_mt, e.destination, e.transporter, e.horse, e.trailer_1, e.trailer_2, 
                       e.lot_number, e.loading_date, e.clearing_completed_date, e.clearing_status, e.declaration_ref, e.declaration_date, 
                       e.bcc_rate, e.liquidation_ref, e.liquidation_date, e.liquidation_amount_cdf, e.quittance_ref, e.quittance_date, 
                       e.liquidation_amount_usd
                FROM exports_t e WHERE e.license_id = ? AND e.subscriber_id = ? AND e.display = 'Y' ORDER BY e.id DESC LIMIT 200";
        $params = [$licenseId, $subscriberId];
      }
      echo json_encode(['success' => true, 'data' => $this->sanitizeArray($this->db->customQuery($sql, $params) ?: [])]);
    } catch (Exception $e) {
      $this->logError("Error getting export MCA references: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load export MCA references', 'data' => []]);
    }
  }

  private function getExportMCADetails()
  {
    try {
      $mcaId = (int)($_GET['mca_id'] ?? 0);
      if ($mcaId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid MCA ID', 'data' => null]);
        return;
      }
      $sql = "SELECT e.* FROM exports_t e WHERE e.id = ? AND e.display = 'Y' LIMIT 1";
      $mcaDetails = $this->db->customQuery($sql, [$mcaId]);
      if (empty($mcaDetails)) {
        echo json_encode(['success' => false, 'message' => 'Export MCA not found', 'data' => null]);
        return;
      }
      echo json_encode(['success' => true, 'data' => $this->sanitizeArray($mcaDetails)[0]]);
    } catch (Exception $e) {
      $this->logError("Error getting export MCA details: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load export MCA details', 'data' => null]);
    }
  }

  private function getBanks()
  {
    try {
      $clientId = (int)($_GET['subscriber_id'] ?? 0);
      if ($clientId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid subscriber ID', 'data' => []]);
        return;
      }
      $sql = "SELECT ibm.id, ibm.invoice_bank_name as bank_name, ibm.invoice_bank_account_name as account_name,
                     ibm.invoice_bank_account_number as account_number, ibm.invoice_bank_swift as swift_code
              FROM client_bank_mapping_t cbm
              INNER JOIN invoice_bank_master_t ibm ON cbm.bank_id = ibm.id
              WHERE cbm.client_id = ? AND ibm.display = 'Y' ORDER BY cbm.id ASC";
      echo json_encode(['success' => true, 'data' => $this->sanitizeArray($this->db->customQuery($sql, [$clientId]) ?: [])]);
    } catch (Exception $e) {
      $this->logError("Error getting banks: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load banks', 'data' => []]);
    }
  }

  private function generateNextInvoiceRef($subscriberId, $commodityType = 'EXP')
  {
    try {
      $clientResult = $this->db->customQuery("SELECT short_name FROM clients_t WHERE id = ? LIMIT 1", [$subscriberId]);
      if (empty($clientResult)) throw new Exception("Client not found");
      $shortName = strtoupper($clientResult[0]['short_name']);
      $year = date('Y');
      $commodityCode = strtoupper(substr($commodityType, 0, 2));
      $prefix = "$year-$shortName-EXP-$commodityCode";
      $result = $this->db->customQuery("SELECT invoice_ref FROM export_invoices_t WHERE subscriber_id = ? AND invoice_ref LIKE ? ORDER BY id DESC LIMIT 1", [$subscriberId, "$prefix%"]);
      $nextNumber = 1;
      if (!empty($result)) {
        preg_match('/(\d+)$/i', $result[0]['invoice_ref'], $matches);
        $nextNumber = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
      }
      return sprintf('%s%d', $prefix, $nextNumber);
    } catch (Exception $e) {
      $this->logError("Error generating next invoice ref: " . $e->getMessage());
      return date('Y') . '-XXX-EXP-001';
    }
  }

  private function getNextInvoiceRefForClient()
  {
    $subscriberId = (int)($_GET['subscriber_id'] ?? 0);
    $commodityType = $this->clean($_GET['commodity_type'] ?? 'CU');
    if ($subscriberId <= 0) {
      echo json_encode(['success' => false, 'message' => 'Invalid subscriber ID']);
      return;
    }
    echo json_encode(['success' => true, 'invoice_ref' => $this->generateNextInvoiceRef($subscriberId, $commodityType)]);
  }

  private function getStatistics()
  {
    try {
      $sql = "SELECT COUNT(*) as total_invoices,
                SUM(CASE WHEN status = 'PENDING' THEN 1 ELSE 0 END) as pending_invoices,
                SUM(CASE WHEN status = 'COMPLETED' THEN 1 ELSE 0 END) as completed_invoices,
                SUM(CASE WHEN status = 'DRAFT' THEN 1 ELSE 0 END) as draft_invoices
              FROM export_invoices_t";
      $stats = $this->db->customQuery($sql);
      echo json_encode(['success' => true, 'data' => [
        'total_invoices' => (int)($stats[0]['total_invoices'] ?? 0),
        'pending_invoices' => (int)($stats[0]['pending_invoices'] ?? 0),
        'completed_invoices' => (int)($stats[0]['completed_invoices'] ?? 0),
        'draft_invoices' => (int)($stats[0]['draft_invoices'] ?? 0)
      ]]);
    } catch (Exception $e) {
      $this->logError("Error getting statistics: " . $e->getMessage());
      echo json_encode(['success' => true, 'data' => ['total_invoices' => 0, 'pending_invoices' => 0, 'completed_invoices' => 0, 'draft_invoices' => 0]]);
    }
  }

  private function saveInvoiceItems($invoiceId, $itemsJson)
  {
    try {
      if (empty($itemsJson)) return true;
      $items = json_decode($itemsJson, true);
      if (json_last_error() !== JSON_ERROR_NONE || !is_array($items) || count($items) === 0) return true;
      $this->db->customQuery("DELETE FROM export_invoice_items_t WHERE invoice_id = ?", [$invoiceId]);
      $sql = "INSERT INTO export_invoice_items_t (invoice_id, quotation_item_id, category_id, description_id, description_name, unit_id, unit_text, quantity, rate_usd, currency_id, has_tva, tva_amount, subtotal_usd, total_usd, display, created_by, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Y', ?, ?)";
      $userId = (int)($_SESSION['user_id'] ?? 1);
      foreach ($items as $item) {
        $params = [$invoiceId, isset($item['id']) ? (int)$item['id'] : null, isset($item['category_id']) ? (int)$item['category_id'] : null, isset($item['description_id']) ? (int)$item['description_id'] : null, $this->clean($item['description_name'] ?? 'N/A'), isset($item['unit_id']) ? (int)$item['unit_id'] : null, $this->clean($item['unit_text'] ?? $item['unit_name'] ?? 'Unit'), isset($item['quantity']) ? (float)$item['quantity'] : 1.00, isset($item['taux_usd']) ? (float)$item['taux_usd'] : (isset($item['cost_usd']) ? (float)$item['cost_usd'] : 0.00), isset($item['currency_id']) ? (int)$item['currency_id'] : null, isset($item['has_tva']) ? (int)$item['has_tva'] : 0, isset($item['tva_usd']) ? (float)$item['tva_usd'] : 0.00, isset($item['subtotal_usd']) ? (float)$item['subtotal_usd'] : 0.00, isset($item['total_usd']) ? (float)$item['total_usd'] : 0.00, $userId, $userId];
        $this->db->customQuery($sql, $params);
      }
      return true;
    } catch (Exception $e) {
      $this->logError("Error in saveInvoiceItems: " . $e->getMessage());
      return false;
    }
  }

  private function saveInvoiceDossiers($invoiceId, $dossiersJson)
  {
    try {
      if (empty($dossiersJson)) return true;
      $dossiers = json_decode($dossiersJson, true);
      if (json_last_error() !== JSON_ERROR_NONE || !is_array($dossiers) || count($dossiers) === 0) return true;
      $this->db->customQuery("DELETE FROM export_invoice_dossiers_t WHERE invoice_id = ?", [$invoiceId]);
      $sql = "INSERT INTO export_invoice_dossiers_t (invoice_id, export_mca_id, mca_ref, destination, transporter, horse, trailer_1, trailer_2, lot_number, quantity_mt, loading_date, clearing_completed_date, clearing_status, declaration_ref, declaration_date, bcc_rate, liquidation_ref, liquidation_date, liquidation_amount_cdf, quittance_ref, quittance_date, liquidation_amount_usd, display, created_by, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Y', ?, ?)";
      $userId = (int)($_SESSION['user_id'] ?? 1);
      foreach ($dossiers as $dossier) {
        $params = [$invoiceId, isset($dossier['id']) ? (int)$dossier['id'] : null, $this->clean($dossier['mca_ref'] ?? ''), $this->clean($dossier['destination'] ?? $dossier['destination_name'] ?? ''), $this->clean($dossier['transporter'] ?? $dossier['transporter_name'] ?? ''), $this->clean($dossier['horse'] ?? ''), $this->clean($dossier['trailer_1'] ?? ''), $this->clean($dossier['trailer_2'] ?? ''), $this->clean($dossier['lot_number'] ?? ''), isset($dossier['quantity_mt']) ? (float)$dossier['quantity_mt'] : 0.00, $this->toDate($dossier['loading_date'] ?? null), $this->toDate($dossier['clearing_completed_date'] ?? null), $this->clean($dossier['clearing_status'] ?? 'PENDING'), $this->clean($dossier['declaration_ref'] ?? ''), $this->toDate($dossier['declaration_date'] ?? null), isset($dossier['bcc_rate']) ? (float)$dossier['bcc_rate'] : 0.00, $this->clean($dossier['liquidation_ref'] ?? ''), $this->toDate($dossier['liquidation_date'] ?? null), isset($dossier['liquidation_amount_cdf']) ? (float)$dossier['liquidation_amount_cdf'] : 0.00, $this->clean($dossier['quittance_ref'] ?? ''), $this->toDate($dossier['quittance_date'] ?? null), isset($dossier['liquidation_amount_usd']) ? (float)$dossier['liquidation_amount_usd'] : 0.00, $userId, $userId];
        $this->db->customQuery($sql, $params);
      }
      return true;
    } catch (Exception $e) {
      $this->logError("Error in saveInvoiceDossiers: " . $e->getMessage());
      return false;
    }
  }

  private function getInvoiceItems($invoiceId)
  {
    try {
      $sql = "SELECT ei.*, qd.description_name as original_description, qc.category_name, qc.category_header, qc.display_order, u.unit_name, curr.currency_short_name
              FROM export_invoice_items_t ei
              LEFT JOIN quotation_descriptions_t qd ON ei.description_id = qd.id
              LEFT JOIN quotation_categories_t qc ON ei.category_id = qc.id
              LEFT JOIN units_master_t u ON ei.unit_id = u.id
              LEFT JOIN currency_master_t curr ON ei.currency_id = curr.id
              WHERE ei.invoice_id = ? AND ei.display = 'Y' ORDER BY qc.display_order ASC, ei.id ASC";
      return $this->sanitizeArray($this->db->customQuery($sql, [$invoiceId]) ?: []);
    } catch (Exception $e) {
      $this->logError("Error getting invoice items: " . $e->getMessage());
      return [];
    }
  }

  private function getInvoiceDossiersData($invoiceId)
  {
    try {
      $sql = "SELECT * FROM export_invoice_dossiers_t WHERE invoice_id = ? AND display = 'Y' ORDER BY id ASC";
      return $this->sanitizeArray($this->db->customQuery($sql, [$invoiceId]) ?: []);
    } catch (Exception $e) {
      $this->logError("Error getting invoice dossiers: " . $e->getMessage());
      return [];
    }
  }

  private function listInvoices()
  {
    try {
      $draw = (int)($_GET['draw'] ?? 1);
      $start = (int)($_GET['start'] ?? 0);
      $length = (int)($_GET['length'] ?? 25);
      $searchValue = $this->sanitizeInput(trim($_GET['search']['value'] ?? ''));
      $filter = $this->sanitizeInput($_GET['filter'] ?? 'all');
      $orderColumnIndex = (int)($_GET['order'][0]['column'] ?? 0);
      $orderDirection = (strtolower($_GET['order'][0]['dir'] ?? 'desc') === 'asc') ? 'ASC' : 'DESC';
      $columns = ['inv.id', 'inv.invoice_ref', 'c.short_name', 'inv.commodity_type', 'inv.total_quantity_mt', 'inv.grand_total_usd', 'inv.status'];
      $orderColumn = $columns[$orderColumnIndex] ?? 'inv.id';
      $baseQuery = "FROM export_invoices_t inv LEFT JOIN clients_t c ON inv.subscriber_id = c.id WHERE 1=1";
      $filterCondition = "";
      if ($filter === 'completed') $filterCondition = " AND inv.status = 'COMPLETED'";
      elseif ($filter === 'pending') $filterCondition = " AND inv.status = 'PENDING'";
      elseif ($filter === 'draft') $filterCondition = " AND inv.status = 'DRAFT'";
      $searchCondition = "";
      $params = [];
      if (!empty($searchValue)) {
        $searchCondition = " AND (inv.invoice_ref LIKE ? OR c.short_name LIKE ? OR inv.commodity_type LIKE ?)";
        $searchParam = "%{$searchValue}%";
        $params = [$searchParam, $searchParam, $searchParam];
      }
      $totalResult = $this->db->customQuery("SELECT COUNT(*) as total FROM export_invoices_t");
      $totalRecords = (int)($totalResult[0]['total'] ?? 0);
      $filteredResult = $this->db->customQuery("SELECT COUNT(*) as total {$baseQuery} {$filterCondition} {$searchCondition}", $params);
      $filteredRecords = (int)($filteredResult[0]['total'] ?? 0);
      $dataSql = "SELECT inv.id, inv.invoice_ref, inv.commodity_type, inv.total_quantity_mt, inv.grand_total_usd, inv.net_payable_usd, inv.equivalent_cdf, inv.dossier_count, inv.status, c.short_name as subscriber_name, inv.created_at {$baseQuery} {$filterCondition} {$searchCondition} ORDER BY {$orderColumn} {$orderDirection} LIMIT {$length} OFFSET {$start}";
      $invoices = $this->db->customQuery($dataSql, $params);
      echo json_encode(['draw' => $draw, 'recordsTotal' => $totalRecords, 'recordsFiltered' => $filteredRecords, 'data' => $this->sanitizeArray($invoices ?: [])]);
    } catch (Exception $e) {
      $this->logError("Error listing invoices: " . $e->getMessage());
      echo json_encode(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
    }
  }

  private function insertInvoice()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
      return;
    }
    $this->validateCsrfToken();
    try {
      $validation = $this->validateInvoiceData($_POST);
      if (!$validation['success']) {
        echo json_encode($validation);
        return;
      }
      $sql = "INSERT INTO export_invoices_t (subscriber_id, license_id, invoice_ref, invoice_date, commodity_type, dossier_count, total_quantity_mt, rate_cdf_usd, total_clearing_cost_usd, total_tva_usd, grand_total_usd, arsp_enabled, arsp_base_amount, arsp_rate, arsp_amount, net_payable_usd, equivalent_cdf, bank_id, status, notes, created_by, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $arspEnabled = isset($_POST['arsp_enabled']) && $_POST['arsp_enabled'] == '1' ? 1 : 0;
      $arspBaseAmount = $this->toDecimal($_POST['arsp_base_amount'] ?? 0);
      $arspRate = $this->toDecimal($_POST['arsp_rate'] ?? 1.2);
      $arspAmount = $arspEnabled ? ($arspBaseAmount * $arspRate / 100) : 0;
      $grandTotal = $this->toDecimal($_POST['grand_total_usd'] ?? 0);
      $netPayable = $grandTotal + $arspAmount;
      $rateCDF = $this->toDecimal($_POST['rate_cdf_usd'] ?? 2856);
      $equivalentCDF = $netPayable * $rateCDF;
      $params = [$this->toInt($_POST['subscriber_id']), $this->toInt($_POST['license_id'] ?? null), $this->clean($_POST['invoice_ref']), $this->toDate($_POST['invoice_date'] ?? date('Y-m-d')), $this->clean($_POST['commodity_type'] ?? 'CUIVRE'), $this->toInt($_POST['dossier_count'] ?? 1), $this->toDecimal($_POST['total_quantity_mt'] ?? 0), $rateCDF, $this->toDecimal($_POST['total_clearing_cost_usd'] ?? 0), $this->toDecimal($_POST['total_tva_usd'] ?? 0), $grandTotal, $arspEnabled, $arspBaseAmount, $arspRate, $arspAmount, $netPayable, $equivalentCDF, $this->toInt($_POST['bank_id'] ?? null), 'PENDING', $this->clean($_POST['notes'] ?? null), (int)($_SESSION['user_id'] ?? 1), (int)($_SESSION['user_id'] ?? 1)];
      $this->db->customQuery($sql, $params);
      $lastIdResult = $this->db->customQuery("SELECT LAST_INSERT_ID() as id");
      $insertId = (int)($lastIdResult[0]['id'] ?? 0);
      if ($insertId > 0) {
        $itemsJson = $_POST['quotation_items'] ?? '';
        if (!empty($itemsJson)) $this->saveInvoiceItems($insertId, $itemsJson);
        $dossiersJson = $_POST['dossiers'] ?? '';
        if (!empty($dossiersJson)) $this->saveInvoiceDossiers($insertId, $dossiersJson);
        echo json_encode(['success' => true, 'message' => 'Export Invoice created successfully!', 'id' => $insertId]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create export invoice']);
      }
    } catch (Exception $e) {
      $this->logError("Insert Exception: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
  }

  private function updateInvoice()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
      return;
    }
    $this->validateCsrfToken();
    try {
      $invoiceId = (int)($_POST['invoice_id'] ?? 0);
      if ($invoiceId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid invoice ID']);
        return;
      }
      $existing = $this->db->customQuery("SELECT id FROM export_invoices_t WHERE id = ?", [$invoiceId]);
      if (empty($existing)) {
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
        return;
      }
      $validation = $this->validateInvoiceData($_POST, $invoiceId);
      if (!$validation['success']) {
        echo json_encode($validation);
        return;
      }
      $arspEnabled = isset($_POST['arsp_enabled']) && $_POST['arsp_enabled'] == '1' ? 1 : 0;
      $arspBaseAmount = $this->toDecimal($_POST['arsp_base_amount'] ?? 0);
      $arspRate = $this->toDecimal($_POST['arsp_rate'] ?? 1.2);
      $arspAmount = $arspEnabled ? ($arspBaseAmount * $arspRate / 100) : 0;
      $grandTotal = $this->toDecimal($_POST['grand_total_usd'] ?? 0);
      $netPayable = $grandTotal + $arspAmount;
      $rateCDF = $this->toDecimal($_POST['rate_cdf_usd'] ?? 2856);
      $equivalentCDF = $netPayable * $rateCDF;
      $sql = "UPDATE export_invoices_t SET subscriber_id = ?, license_id = ?, invoice_ref = ?, invoice_date = ?, commodity_type = ?, dossier_count = ?, total_quantity_mt = ?, rate_cdf_usd = ?, total_clearing_cost_usd = ?, total_tva_usd = ?, grand_total_usd = ?, arsp_enabled = ?, arsp_base_amount = ?, arsp_rate = ?, arsp_amount = ?, net_payable_usd = ?, equivalent_cdf = ?, bank_id = ?, notes = ?, updated_by = ?, updated_at = NOW() WHERE id = ?";
      $params = [$this->toInt($_POST['subscriber_id']), $this->toInt($_POST['license_id'] ?? null), $this->clean($_POST['invoice_ref']), $this->toDate($_POST['invoice_date'] ?? date('Y-m-d')), $this->clean($_POST['commodity_type'] ?? 'CUIVRE'), $this->toInt($_POST['dossier_count'] ?? 1), $this->toDecimal($_POST['total_quantity_mt'] ?? 0), $rateCDF, $this->toDecimal($_POST['total_clearing_cost_usd'] ?? 0), $this->toDecimal($_POST['total_tva_usd'] ?? 0), $grandTotal, $arspEnabled, $arspBaseAmount, $arspRate, $arspAmount, $netPayable, $equivalentCDF, $this->toInt($_POST['bank_id'] ?? null), $this->clean($_POST['notes'] ?? null), (int)($_SESSION['user_id'] ?? 1), $invoiceId];
      $this->db->customQuery($sql, $params);
      $itemsJson = $_POST['quotation_items'] ?? '';
      if (!empty($itemsJson)) $this->saveInvoiceItems($invoiceId, $itemsJson);
      $dossiersJson = $_POST['dossiers'] ?? '';
      if (!empty($dossiersJson)) $this->saveInvoiceDossiers($invoiceId, $dossiersJson);
      echo json_encode(['success' => true, 'message' => 'Export Invoice updated successfully!']);
    } catch (Exception $e) {
      $this->logError("Update Exception: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
  }

  private function deleteInvoice()
  {
    $this->validateCsrfToken();
    try {
      $invoiceId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
      if ($invoiceId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid invoice ID']);
        return;
      }
      $existing = $this->db->customQuery("SELECT id FROM export_invoices_t WHERE id = ?", [$invoiceId]);
      if (empty($existing)) {
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
        return;
      }
      $this->db->customQuery("DELETE FROM export_invoice_items_t WHERE invoice_id = ?", [$invoiceId]);
      $this->db->customQuery("DELETE FROM export_invoice_dossiers_t WHERE invoice_id = ?", [$invoiceId]);
      $this->db->customQuery("DELETE FROM export_invoices_t WHERE id = ?", [$invoiceId]);
      echo json_encode(['success' => true, 'message' => 'Export Invoice deleted successfully!']);
    } catch (Exception $e) {
      $this->logError("Delete Exception: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
  }

  private function getInvoice()
  {
    try {
      $invoiceId = (int)($_GET['id'] ?? 0);
      if ($invoiceId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid invoice ID']);
        return;
      }
      $sql = "SELECT inv.*, c.short_name as subscriber_name, c.company_name, c.address, c.rccm_number, c.nif_number, c.id_nat_number, c.import_export_number, l.license_number
              FROM export_invoices_t inv LEFT JOIN clients_t c ON inv.subscriber_id = c.id LEFT JOIN licenses_t l ON inv.license_id = l.id WHERE inv.id = ?";
      $invoice = $this->db->customQuery($sql, [$invoiceId]);
      if (!empty($invoice)) {
        echo json_encode(['success' => true, 'data' => $this->sanitizeArray($invoice)[0], 'items' => $this->getInvoiceItems($invoiceId), 'dossiers' => $this->getInvoiceDossiersData($invoiceId)]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
      }
    } catch (Exception $e) {
      $this->logError("Error getting invoice: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load invoice data']);
    }
  }

  private function exportInvoice()
  {
    $invoiceId = (int)($_GET['id'] ?? 0);
    if ($invoiceId <= 0) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Invalid invoice ID']);
      return;
    }
    try {
      $vendorPath = __DIR__ . '/../../../vendor/autoload.php';
      if (!file_exists($vendorPath)) throw new Exception('PhpSpreadsheet not found');
      require_once $vendorPath;
      $result = $this->db->customQuery("SELECT inv.*, c.short_name as client_name, l.license_number FROM export_invoices_t inv LEFT JOIN clients_t c ON inv.subscriber_id = c.id LEFT JOIN licenses_t l ON inv.license_id = l.id WHERE inv.id = ?", [$invoiceId]);
      if (empty($result)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
        return;
      }
      $data = $result[0];
      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('Export Invoice');
      $sheet->setCellValue('A1', 'MALABAR RDC SARL - EXPORT INVOICE');
      $sheet->mergeCells('A1:D1');
      $row = 3;
      foreach ([['Invoice Ref:', $data['invoice_ref'] ?? ''], ['Client:', $data['client_name'] ?? ''], ['Commodity:', $data['commodity_type'] ?? ''], ['Total Qty (MT):', number_format((float)($data['total_quantity_mt'] ?? 0), 3)], ['Grand Total (USD):', '$' . number_format((float)($data['grand_total_usd'] ?? 0), 2)], ['Net Payable (USD):', '$' . number_format((float)($data['net_payable_usd'] ?? 0), 2)]] as $header) {
        $sheet->setCellValue('A' . $row, $header[0]);
        $sheet->setCellValue('B' . $row, $header[1]);
        $row++;
      }
      foreach (range('A', 'D') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
      $filename = 'Export_Invoice_' . preg_replace('/[^a-zA-Z0-9]/', '_', $data['invoice_ref'] ?? 'INV') . '.xlsx';
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '"');
      header('Cache-Control: max-age=0');
      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $writer->save('php://output');
      $spreadsheet->disconnectWorksheets();
      exit;
    } catch (Exception $e) {
      $this->logError("Export error: " . $e->getMessage());
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Export failed']);
      exit;
    }
  }

  private function exportAllInvoices()
  {
    try {
      $vendorPath = __DIR__ . '/../../../vendor/autoload.php';
      if (!file_exists($vendorPath)) throw new Exception('PhpSpreadsheet not found');
      require_once $vendorPath;
      $invoices = $this->db->customQuery("SELECT inv.invoice_ref, c.short_name as client_name, inv.commodity_type, inv.total_quantity_mt, inv.grand_total_usd, inv.net_payable_usd, inv.status, inv.created_at FROM export_invoices_t inv LEFT JOIN clients_t c ON inv.subscriber_id = c.id ORDER BY inv.id DESC");
      if (empty($invoices)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'No invoices found']);
        return;
      }
      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('All Export Invoices');
      $sheet->setCellValue('A1', 'MALABAR RDC SARL - ALL EXPORT INVOICES');
      $sheet->mergeCells('A1:H1');
      $sheet->fromArray([['Invoice Ref', 'Client', 'Commodity', 'Qty (MT)', 'Grand Total', 'Net Payable', 'Status', 'Created']], null, 'A3');
      $rowIndex = 4;
      foreach ($invoices as $inv) {
        $sheet->fromArray([[$inv['invoice_ref'] ?? '', $inv['client_name'] ?? '', $inv['commodity_type'] ?? '', number_format((float)($inv['total_quantity_mt'] ?? 0), 3), number_format((float)($inv['grand_total_usd'] ?? 0), 2), number_format((float)($inv['net_payable_usd'] ?? 0), 2), $inv['status'] ?? '', $inv['created_at'] ? date('d-m-Y', strtotime($inv['created_at'])) : '']], null, 'A' . $rowIndex);
        $rowIndex++;
      }
      foreach (range('A', 'H') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
      $filename = 'All_Export_Invoices_' . date('Ymd_His') . '.xlsx';
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '"');
      header('Cache-Control: max-age=0');
      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $writer->save('php://output');
      $spreadsheet->disconnectWorksheets();
      exit;
    } catch (Exception $e) {
      $this->logError("Export all error: " . $e->getMessage());
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Export failed']);
      exit;
    }
  }

  private function viewPDF()
  {
    $invoiceId = (int)($_GET['id'] ?? 0);
    if ($invoiceId <= 0) die("Invalid invoice ID");
    try {
      $vendorPath = __DIR__ . '/../../../vendor/autoload.php';
      if (!file_exists($vendorPath)) die("mPDF library not found");
      require_once $vendorPath;
      $invoice = $this->db->customQuery("SELECT * FROM export_invoices_t WHERE id = ? LIMIT 1", [$invoiceId]);
      if (empty($invoice)) die("Invoice not found");
      $invoice = $invoice[0];
      $subscriberId = $invoice['subscriber_id'] ?? 0;
      $data = ['invoice_ref' => $invoice['invoice_ref'] ?? '', 'invoice_date' => $invoice['invoice_date'] ?? date('Y-m-d'), 'commodity_type' => $invoice['commodity_type'] ?? 'CUIVRE', 'dossier_count' => $invoice['dossier_count'] ?? 1, 'total_quantity_mt' => $invoice['total_quantity_mt'] ?? 0, 'rate_cdf_usd' => $invoice['rate_cdf_usd'] ?? 2856, 'total_clearing_cost_usd' => $invoice['total_clearing_cost_usd'] ?? 0, 'total_tva_usd' => $invoice['total_tva_usd'] ?? 0, 'grand_total_usd' => $invoice['grand_total_usd'] ?? 0, 'arsp_enabled' => $invoice['arsp_enabled'] ?? 0, 'arsp_base_amount' => $invoice['arsp_base_amount'] ?? 0, 'arsp_rate' => $invoice['arsp_rate'] ?? 1.2, 'arsp_amount' => $invoice['arsp_amount'] ?? 0, 'net_payable_usd' => $invoice['net_payable_usd'] ?? 0, 'equivalent_cdf' => $invoice['equivalent_cdf'] ?? 0];
      if (!empty($subscriberId)) {
        $client = $this->db->customQuery("SELECT short_name, company_name, address, rccm_number, nif_number, id_nat_number, import_export_number FROM clients_t WHERE id = ? LIMIT 1", [$subscriberId]);
        if (!empty($client)) {
          $data['client_name'] = $client[0]['company_name'] ?? $client[0]['short_name'] ?? '';
          $data['client_short_name'] = $client[0]['short_name'] ?? '';
          $data['client_address'] = $client[0]['address'] ?? '';
          $data['client_rccm'] = $client[0]['rccm_number'] ?? '';
          $data['client_nif'] = $client[0]['nif_number'] ?? '';
          $data['client_id_nat'] = $client[0]['id_nat_number'] ?? '';
          $data['client_import_export'] = $client[0]['import_export_number'] ?? '';
        }
      }
      $data['client_name'] = $data['client_name'] ?? 'N/A';
      $data['client_short_name'] = $data['client_short_name'] ?? '';
      $data['client_address'] = $data['client_address'] ?? '';
      $data['client_rccm'] = $data['client_rccm'] ?? '';
      $data['client_nif'] = $data['client_nif'] ?? '';
      $data['client_id_nat'] = $data['client_id_nat'] ?? '';
      $data['client_import_export'] = $data['client_import_export'] ?? '';
      $data['client_tva'] = '';
      $banks = $this->db->customQuery("SELECT ibm.invoice_bank_name, ibm.invoice_bank_account_name, ibm.invoice_bank_account_number, ibm.invoice_bank_swift FROM client_bank_mapping_t cbm INNER JOIN invoice_bank_master_t ibm ON cbm.bank_id = ibm.id WHERE cbm.client_id = ? AND ibm.display = 'Y' ORDER BY cbm.id ASC LIMIT 1", [$subscriberId]);
      $data['banks'] = $banks ?: [];
      $data['items'] = $this->getInvoiceItems($invoiceId);
      $data['dossiers'] = $this->getInvoiceDossiersData($invoiceId);
      $html = $this->generateExportInvoicePDF($data);
      $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_top' => 5, 'margin_bottom' => 5, 'margin_left' => 5, 'margin_right' => 5]);
      $mpdf->WriteHTML($html);
      $mpdf->Output('Export_Invoice_' . preg_replace('/[^a-zA-Z0-9]/', '_', $data['invoice_ref']) . '.pdf', 'I');
    } catch (Exception $e) {
      $this->logError("PDF Exception: " . $e->getMessage());
      die("PDF generation failed: " . $e->getMessage());
    }
  }

  private function generateExportInvoicePDF($data)
  {
    $logoPath = $this->logoPath;
    $logoHtml = file_exists($logoPath) ? '<img src="' . $logoPath . '" style="max-width:200px;max-height:50px;">' : '<b style="font-size:18pt;color:#8B0000;">malabar rdc sarl</b>';
    $invoiceRef = htmlspecialchars($data['invoice_ref'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
    $invoiceDate = !empty($data['invoice_date']) ? date('d-M-y', strtotime($data['invoice_date'])) : date('d-M-y');
    $clientName = htmlspecialchars($data['client_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
    $clientAddress = htmlspecialchars($data['client_address'] ?? '', ENT_QUOTES, 'UTF-8');
    $clientRCCM = htmlspecialchars($data['client_rccm'] ?? '', ENT_QUOTES, 'UTF-8');
    $clientNIF = htmlspecialchars($data['client_nif'] ?? '', ENT_QUOTES, 'UTF-8');
    $clientIDNat = htmlspecialchars($data['client_id_nat'] ?? '', ENT_QUOTES, 'UTF-8');
    $clientImportExport = htmlspecialchars($data['client_import_export'] ?? '', ENT_QUOTES, 'UTF-8');
    $clientTVA = htmlspecialchars($data['client_tva'] ?? '', ENT_QUOTES, 'UTF-8');
    $dossierCount = (int)($data['dossier_count'] ?? 1);
    $dossiers = $data['dossiers'] ?? [];
    $dossierRefs = [];
    foreach ($dossiers as $d) $dossierRefs[] = htmlspecialchars($d['mca_ref'] ?? '', ENT_QUOTES, 'UTF-8');
    $dossierRefsStr = !empty($dossierRefs) ? implode(', ', $dossierRefs) : 'N/A';
    $items = $data['items'] ?? [];
    $groupedItems = [];
    foreach ($items as $item) {
      $catHeader = $item['category_header'] ?? $item['category_name'] ?? 'OTHER';
      if (!isset($groupedItems[$catHeader])) $groupedItems[$catHeader] = ['items' => [], 'subtotal' => 0, 'tva' => 0, 'total' => 0];
      $groupedItems[$catHeader]['items'][] = $item;
      $groupedItems[$catHeader]['subtotal'] += (float)($item['subtotal_usd'] ?? 0);
      $groupedItems[$catHeader]['tva'] += (float)($item['tva_amount'] ?? 0);
      $groupedItems[$catHeader]['total'] += (float)($item['total_usd'] ?? 0);
    }
    $totalClearingCost = (float)($data['total_clearing_cost_usd'] ?? 0);
    $totalTVA = (float)($data['total_tva_usd'] ?? 0);
    $grandTotal = (float)($data['grand_total_usd'] ?? 0);
    $arspEnabled = (int)($data['arsp_enabled'] ?? 0);
    $arspBaseAmount = (float)($data['arsp_base_amount'] ?? 0);
    $arspRate = (float)($data['arsp_rate'] ?? 1.2);
    $arspAmount = (float)($data['arsp_amount'] ?? 0);
    $netPayable = (float)($data['net_payable_usd'] ?? 0);
    $equivalentCDF = (float)($data['equivalent_cdf'] ?? 0);

    $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>
body{font-family:Arial,sans-serif;font-size:7pt;margin:0;padding:5mm;line-height:1.3;}
table{border-collapse:collapse;width:100%;}
td,th{padding:2px 4px;vertical-align:top;}
.b td,.b th{border:1px solid #000;}
.r{text-align:right;}.c{text-align:center;}.bo{font-weight:bold;}.g{background:#e0e0e0;}
.header-dark{background:#333;color:#fff;font-weight:bold;padding:3px 5px;}
.section-header{background:#333;color:#fff;font-weight:bold;padding:4px 8px;font-size:7pt;}
.client-box{border:1px solid #000;}.client-box td{padding:2px 5px;border:none;}
.client-header{background:#e0e0e0;text-align:center;font-weight:bold;border-bottom:1px solid #000;}
.totals-box{border:1px solid #000;}.totals-box td{padding:2px 5px;}
.bank-box{border:1px solid #000;margin-top:3mm;}.bank-box td{padding:2px 5px;}
.page-break{page-break-before:always;}
</style></head><body>';

    $html .= '<table style="margin-bottom:3mm;"><tr><td style="width:60%;">' . $logoHtml . '</td><td style="text-align:right;font-size:6pt;line-height:1.4;">No. 1068, Avenue Ruwe, Quartier Makutano,<br>Lubumbashi, DRC<br>RCCM: 13-B-1122, ID NAT. 6-9-N91867E<br>NIF : A 1309334 L<br>VAT Ref # 145/DGI/DGE/INF/BN/TVA/2020<br>Capital Social : 45.000.000 FC</td></tr></table>';
    $html .= '<div style="border:2px solid #000;padding:5px 20px;font-weight:bold;font-size:12pt;width:120px;text-align:center;margin-bottom:3mm;">FACTURE</div>';
    $html .= '<table style="margin-bottom:3mm;"><tr><td style="width:48%;vertical-align:top;"><table class="client-box" style="width:100%;"><tr><td class="client-header" colspan="2">CLIENT</td></tr><tr><td colspan="2" style="font-weight:bold;">' . $clientName . '</td></tr><tr><td colspan="2" style="font-size:6pt;">' . $clientAddress . '</td></tr><tr><td style="width:40%;">No.RCCM:</td><td>' . $clientRCCM . '</td></tr><tr><td>No.NIF.:</td><td>' . $clientNIF . '</td></tr><tr><td>No.IDN.:</td><td>' . $clientIDNat . '</td></tr><tr><td>No.IMPORT/EXPORT:</td><td>' . $clientImportExport . '</td></tr><tr><td>No.TVA:</td><td>' . $clientTVA . '</td></tr></table></td><td style="width:4%;"></td><td style="width:48%;vertical-align:top;"><table class="b" style="width:100%;"><tr class="g"><td style="width:40%;">N.FACTURE</td><td class="bo r">' . $invoiceRef . '</td></tr><tr><td>Date</td><td class="r">' . $invoiceDate . '</td></tr><tr><td>Dossier(s):</td><td class="bo">' . $dossierRefsStr . '</td></tr><tr><td>Nombre de Dossier(s):</td><td class="bo r">' . $dossierCount . '</td></tr></table></td></tr></table>';

    if (!empty($groupedItems)) {
      foreach ($groupedItems as $catHeader => $catData) {
        $html .= '<div class="section-header">' . htmlspecialchars($catHeader, ENT_QUOTES, 'UTF-8') . '</div>';
        $html .= '<table class="b"><tr class="g"><th style="width:40%;">Description</th><th style="width:10%;">Unit</th><th style="width:12%;">COST /USD</th><th style="width:14%;">SUBTOTAL USD</th><th style="width:12%;">TVA- 16%</th><th style="width:12%;">TOTAL EN USD</th></tr>';
        foreach ($catData['items'] as $item) {
          $desc = htmlspecialchars($item['description_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
          $unit = htmlspecialchars($item['unit_text'] ?? $item['unit_name'] ?? '1', ENT_QUOTES, 'UTF-8');
          $costUSD = number_format((float)($item['rate_usd'] ?? $item['taux_usd'] ?? 0), 2);
          $subtotalUSD = number_format((float)($item['subtotal_usd'] ?? 0), 2);
          $tvaUSD = number_format((float)($item['tva_amount'] ?? 0), 2);
          $totalUSD = number_format((float)($item['total_usd'] ?? 0), 2);
          $html .= '<tr><td>' . $desc . '</td><td class="c">' . $unit . '</td><td class="r">' . $costUSD . '</td><td class="r">' . $subtotalUSD . '</td><td class="r">' . $tvaUSD . '</td><td class="r">' . $totalUSD . '</td></tr>';
        }
        $html .= '<tr class="g bo"><td colspan="2">SUB-TOTAL / SOUS-TOTAL</td><td class="r">' . number_format($catData['subtotal'], 2) . '</td><td class="r">' . number_format($catData['subtotal'], 2) . '</td><td class="r">' . number_format($catData['tva'], 2) . '</td><td class="r">' . number_format($catData['total'], 2) . '</td></tr></table>';
        $html .= '<div style="margin-bottom:2mm;"></div>';
      }
    } else {
      $html .= '<div class="section-header">CUSTOMS CLEARANCE FEES / FRAIS DEDOUANEMENT</div><table class="b"><tr class="g"><th style="width:40%;">Description</th><th style="width:10%;">Unit</th><th style="width:12%;">COST /USD</th><th style="width:14%;">SUBTOTAL USD</th><th style="width:12%;">TVA- 16%</th><th style="width:12%;">TOTAL EN USD</th></tr><tr><td>No items found</td><td class="c">-</td><td class="r">0.00</td><td class="r">0.00</td><td class="r">0.00</td><td class="r">0.00</td></tr><tr class="g bo"><td colspan="2">SUB-TOTAL / SOUS-TOTAL</td><td class="r">0.00</td><td class="r">0.00</td><td class="r">0.00</td><td class="r">0.00</td></tr></table>';
    }

    $html .= '<table style="margin-top:3mm;"><tr><td style="width:50%;"></td><td style="width:50%;"><table class="totals-box" style="width:100%;"><tr class="g"><td style="width:60%;">TOTAL CLEARING COST IN USD / COUT TOTAL EN USD</td><td class="r bo">' . number_format($grandTotal, 2) . '</td></tr>';
    if ($arspEnabled) $html .= '<tr><td>ARSP Tax (' . number_format($arspRate, 1) . '% on the Agency Fees without TVA)</td><td class="r">' . number_format($arspAmount, 2) . '</td></tr>';
    $html .= '<tr class="g bo"><td>NET PAYABLE AMOUNT EN USD</td><td class="r">' . number_format($netPayable, 2) . '</td></tr><tr class="bo"><td>CDF</td><td class="r">' . number_format($equivalentCDF, 2) . '</td></tr></table></td></tr></table>';

    $banks = $data['banks'] ?? [];
    if (!empty($banks)) {
      $bank = $banks[0];
      $html .= '<div style="text-align:center;font-size:6pt;margin:3mm 0 2mm 0;text-transform:uppercase;">VEUILLEZ TROUVER CI-DESSOUS LES DETAILS DE NOTRE COMPTE BANCAIRE</div><table class="bank-box" style="width:50%;"><tr><td style="width:25%;">INTITULE</td><td>' . htmlspecialchars($bank['invoice_bank_account_name'] ?? '', ENT_QUOTES, 'UTF-8') . '</td></tr><tr><td>N.COMPTE</td><td>' . htmlspecialchars($bank['invoice_bank_account_number'] ?? '', ENT_QUOTES, 'UTF-8') . '</td></tr><tr><td>SWIFT</td><td>' . htmlspecialchars($bank['invoice_bank_swift'] ?? '', ENT_QUOTES, 'UTF-8') . '</td></tr><tr><td>BANQUE</td><td>' . htmlspecialchars($bank['invoice_bank_name'] ?? '', ENT_QUOTES, 'UTF-8') . '<br>LUBUMBASHI<br>R.D. CONGO</td></tr></table><div style="font-size:6pt;margin-top:2mm;">LE PAIEMENT DOIT S\'EFFECTUER ENDEANS 7 JOURS</div>';
    }
    $html .= '<div style="border:1px solid #000;text-align:center;padding:3px;margin-top:3mm;font-size:6pt;">Thank you for your business!</div>';

    if (!empty($dossiers)) {
      $html .= '<div class="page-break"></div><table style="margin-bottom:3mm;"><tr><td>' . $logoHtml . '</td></tr></table><div style="font-weight:bold;margin-bottom:3mm;">DETAILS - EXPORT CLEARING ' . htmlspecialchars(strtoupper($data['commodity_type'] ?? 'CUIVRE'), ENT_QUOTES, 'UTF-8') . ' LOADS</div>';
      $html .= '<table class="b" style="font-size:6pt;"><tr class="g"><th style="width:3%;">#</th><th style="width:12%;">MCA File No</th><th style="width:10%;">Destination</th><th style="width:12%;">Transporter</th><th style="width:10%;">Horse/Wagon</th><th style="width:10%;">Trailer 1</th><th style="width:8%;">Trailer 2</th><th style="width:12%;">Lot. No.</th><th style="width:7%;">Qty(Mt)</th><th style="width:8%;">Loading Date</th><th style="width:8%;">Clearing Completed Date</th></tr>';
      $totalQty = 0;
      $rowNum = 1;
      foreach ($dossiers as $d) {
        $qty = (float)($d['quantity_mt'] ?? 0);
        $totalQty += $qty;
        $loadingDate = !empty($d['loading_date']) ? date('d/m/Y', strtotime($d['loading_date'])) : '';
        $completedDate = !empty($d['clearing_completed_date']) ? date('d/m/Y', strtotime($d['clearing_completed_date'])) : '';
        $html .= '<tr><td class="c">' . $rowNum . '</td><td>' . htmlspecialchars($d['mca_ref'] ?? '', ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($d['destination'] ?? '', ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($d['transporter'] ?? '', ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($d['horse'] ?? '', ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($d['trailer_1'] ?? '', ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($d['trailer_2'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($d['lot_number'] ?? '', ENT_QUOTES, 'UTF-8') . '</td><td class="r">' . number_format($qty, 3) . '</td><td class="c">' . $loadingDate . '</td><td class="c">' . $completedDate . '</td></tr>';
        $rowNum++;
      }
      $html .= '<tr class="g bo"><td colspan="8" class="r">Total:</td><td class="r">' . number_format($totalQty, 3) . '</td><td colspan="2"></td></tr></table><div style="text-align:right;font-size:6pt;margin-top:3mm;">Details INV No. ' . $invoiceRef . ' du ' . $invoiceDate . '</div>';

      $html .= '<div class="page-break"></div><table style="margin-bottom:3mm;"><tr><td>' . $logoHtml . '</td></tr></table><div style="font-weight:bold;margin-bottom:3mm;">DETAILS - EXPORT CLEARING ' . htmlspecialchars(strtoupper($data['commodity_type'] ?? 'CUIVRE'), ENT_QUOTES, 'UTF-8') . ' LOADS</div>';
      $html .= '<table class="b" style="font-size:6pt;"><tr class="g"><th style="width:3%;">#</th><th style="width:12%;">MCA File No</th><th style="width:7%;">Qty(Mt)</th><th style="width:8%;">Loading Date</th><th style="width:9%;">Declaration Ref.</th><th style="width:8%;">Declaration Date</th><th style="width:9%;">BCC Rate</th><th style="width:9%;">Liquidation Ref.</th><th style="width:8%;">Liquidation Date</th><th style="width:10%;">Liq. Amt. CDF</th><th style="width:8%;">Quittance Ref.</th><th style="width:8%;">Quittance Date</th><th style="width:9%;">Liq. Amt. USD</th></tr>';
      $rowNum = 1;
      foreach ($dossiers as $d) {
        $loadingDate = !empty($d['loading_date']) ? date('d/m/Y', strtotime($d['loading_date'])) : '';
        $declDate = !empty($d['declaration_date']) ? date('d/m/Y', strtotime($d['declaration_date'])) : '';
        $liqDate = !empty($d['liquidation_date']) ? date('d/m/Y', strtotime($d['liquidation_date'])) : '';
        $quitDate = !empty($d['quittance_date']) ? date('d/m/Y', strtotime($d['quittance_date'])) : '';
        $html .= '<tr><td class="c">' . $rowNum . '</td><td>' . htmlspecialchars($d['mca_ref'] ?? '', ENT_QUOTES, 'UTF-8') . '</td><td class="r">' . number_format((float)($d['quantity_mt'] ?? 0), 3) . '</td><td class="c">' . $loadingDate . '</td><td>' . htmlspecialchars($d['declaration_ref'] ?? '', ENT_QUOTES, 'UTF-8') . '</td><td class="c">' . $declDate . '</td><td class="r">' . number_format((float)($d['bcc_rate'] ?? 0), 4) . '</td><td>' . htmlspecialchars($d['liquidation_ref'] ?? '', ENT_QUOTES, 'UTF-8') . '</td><td class="c">' . $liqDate . '</td><td class="r">' . number_format((float)($d['liquidation_amount_cdf'] ?? 0), 0) . '</td><td>' . htmlspecialchars($d['quittance_ref'] ?? '', ENT_QUOTES, 'UTF-8') . '</td><td class="c">' . $quitDate . '</td><td class="r">' . number_format((float)($d['liquidation_amount_usd'] ?? 0), 2) . '</td></tr>';
        $rowNum++;
      }
      $html .= '</table><div style="text-align:right;font-size:6pt;margin-top:3mm;">Details INV No. ' . $invoiceRef . ' du ' . $invoiceDate . '</div>';
    }
    $html .= '</body></html>';
    return $html;
  }

  private function validateInvoiceData($post, $invoiceId = null)
  {
    $errors = [];
    if (empty($post['subscriber_id'])) $errors[] = 'Subscriber is required';
    if (empty($post['invoice_ref'])) $errors[] = 'Invoice Reference is required';
    if (!empty($post['invoice_ref'])) {
      $invoiceRef = $this->clean($post['invoice_ref']);
      $sql = "SELECT id FROM export_invoices_t WHERE invoice_ref = ?";
      $params = [$invoiceRef];
      if ($invoiceId) {
        $sql .= " AND id != ?";
        $params[] = $invoiceId;
      }
      $existing = $this->db->customQuery($sql, $params);
      if (!empty($existing)) $errors[] = 'Invoice Reference already exists';
    }
    if (!empty($errors)) return ['success' => false, 'message' => implode(', ', $errors)];
    return ['success' => true];
  }

  private function validateCsrfToken()
  {
    $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
    if (empty($token) || empty($_SESSION['csrf_token'])) {
      $this->logError("CSRF validation failed - empty token");
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'Security token expired. Please refresh the page.']);
      exit;
    }
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
      $this->logError("CSRF validation failed - token mismatch");
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'Invalid security token. Please refresh the page.']);
      exit;
    }
  }

  private function sanitizeArray($data)
  {
    if (!is_array($data)) return [];
    return array_map(function($item) {
      if (is_array($item)) return array_map(function($v) { return is_string($v) ? htmlspecialchars($v, ENT_QUOTES | ENT_HTML5, 'UTF-8') : $v; }, $item);
      return $item;
    }, $data);
  }

  private function sanitizeInput($value)
  {
    if (is_array($value)) return array_map([$this, 'sanitizeInput'], $value);
    if (!is_string($value)) return $value;
    return trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', str_replace(chr(0), '', $value)));
  }

  private function clean($value)
  {
    if ($value === null || $value === '') return null;
    $value = $this->sanitizeInput($value);
    $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return strlen($value) > 255 ? substr($value, 0, 255) : $value;
  }

  private function toInt($value)
  {
    if ($value === null || $value === '' || !is_numeric($value)) return null;
    $int = (int)$value;
    return $int > 0 ? $int : null;
  }

  private function toDecimal($value)
  {
    if ($value === null || $value === '') return null;
    if (!is_numeric($value)) return 0.00;
    return round((float)$value, 2);
  }

  private function toDate($value)
  {
    if (empty($value)) return null;
    $d = DateTime::createFromFormat('Y-m-d', $value);
    return ($d && $d->format('Y-m-d') === $value) ? $value : null;
  }

  private function logError($message)
  {
    @file_put_contents($this->logFile, "[" . date('Y-m-d H:i:s') . "] {$message}\n", FILE_APPEND);
  }
}