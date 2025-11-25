<?php

class ImportInvoiceController extends Controller
{
  private $db;
  private $logFile;
  private $logoPath;

  public function __construct()
  {
    $this->db = new Database();
    $this->logFile = __DIR__ . '/../../logs/import_invoice.log';
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

    // Get subscribers that have IMPORT licenses (kind_id IN 1,2,5,6)
    $sql = "SELECT DISTINCT c.id, c.short_name, c.company_name 
            FROM clients_t c
            INNER JOIN licenses_t l ON c.id = l.client_id
            WHERE c.display = 'Y' AND l.display = 'Y' AND l.kind_id IN (1, 2, 5, 6)
            ORDER BY c.short_name ASC";
    $subscribers = $this->db->customQuery($sql) ?: [];
    
    $currencies = $this->db->selectData('currency_master_t', 'id, currency_name, currency_short_name', ['display' => 'Y'], 'currency_short_name ASC') ?: [];

    $data = [
      'title' => 'Import Invoice Management',
      'subscribers' => $this->sanitizeArray($subscribers),
      'currencies' => $this->sanitizeArray($currencies),
      'csrf_token' => $_SESSION['csrf_token']
    ];

    $this->viewWithLayout('invoices/importinvoice', $data);
  }

  public function crudData($action = 'listing')
  {
    while (ob_get_level()) {
      ob_end_clean();
    }
    ob_start();

    header('Content-Type: application/json; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');

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
        case 'getMCAReferences':
          $this->getMCAReferences();
          break;
        case 'getMCADetails':
          $this->getMCADetails();
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

      $sql = "SELECT id, short_name, company_name FROM clients_t WHERE id = ? AND display = 'Y' LIMIT 1";
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

      $sql = "SELECT q.id, q.quotation_ref, q.quotation_date, 
                     q.sub_total, q.vat_amount, q.total_amount,
                     q.kind_id, q.transport_mode_id, q.goods_type_id
              FROM quotations_t q
              WHERE q.client_id = ? 
              AND q.display = 'Y'
              ORDER BY q.quotation_date DESC, q.id DESC";

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

      $quotationSql = "SELECT q.id, q.client_id, q.quotation_ref, q.sub_total, q.vat_amount, q.total_amount,
                              q.quotation_date, q.kind_id, q.transport_mode_id, q.goods_type_id
                       FROM quotations_t q
                       WHERE q.id = ? AND q.display = 'Y'
                       LIMIT 1";
      
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

      $itemsSql = "SELECT qi.id, qi.quotation_id, qi.category_id, qi.description_id,
                          qi.quantity, qi.unit_id, qi.unit_text,
                          qi.taux_usd, qi.cost_usd, qi.subtotal_usd,
                          qi.has_tva, qi.tva_usd, qi.total_usd,
                          qi.currency_id,
                          qd.description_name,
                          qc.category_name, qc.category_header, qc.display_order,
                          u.unit_name,
                          curr.currency_short_name
                   FROM quotation_items_t qi
                   LEFT JOIN quotation_descriptions_t qd ON qi.description_id = qd.id
                   LEFT JOIN quotation_categories_t qc ON qi.category_id = qc.id
                   LEFT JOIN units_master_t u ON qi.unit_id = u.id
                   LEFT JOIN currency_master_t curr ON qi.currency_id = curr.id
                   WHERE qi.quotation_id = ? 
                   AND qi.display = 'Y'
                   ORDER BY qc.display_order ASC, qi.id ASC";

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
      usort($categorizedItems, function($a, $b) {
        return ($a['display_order'] ?? 999) - ($b['display_order'] ?? 999);
      });

      echo json_encode([
        'success' => true, 
        'quotation' => $this->sanitizeArray($quotation),
        'items' => $items,
        'categorized_items' => $categorizedItems
      ]);

    } catch (Exception $e) {
      $this->logError("Error in getQuotationItems: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load quotation items', 'data' => []]);
    }
  }

  private function saveInvoiceItems($invoiceId, $itemsJson)
  {
    try {
      if (empty($itemsJson)) return true;

      $items = json_decode($itemsJson, true);
      if (json_last_error() !== JSON_ERROR_NONE || !is_array($items) || count($items) === 0) return true;

      $this->db->customQuery("DELETE FROM import_invoice_items_t WHERE invoice_id = ?", [$invoiceId]);

      $sql = "INSERT INTO import_invoice_items_t 
              (invoice_id, quotation_item_id, category_id, description_id, description_name,
               unit_id, unit_text, quantity, rate_usd, currency_id, has_tva, 
               tva_amount, subtotal_usd, total_usd, display, created_by, updated_by)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Y', ?, ?)";

      $userId = (int)($_SESSION['user_id'] ?? 1);

      foreach ($items as $item) {
        $params = [
          $invoiceId,
          isset($item['id']) ? (int)$item['id'] : null,
          isset($item['category_id']) ? (int)$item['category_id'] : null,
          isset($item['description_id']) ? (int)$item['description_id'] : null,
          $this->clean($item['description_name'] ?? 'N/A'),
          isset($item['unit_id']) ? (int)$item['unit_id'] : null,
          $this->clean($item['unit_text'] ?? $item['unit_name'] ?? 'Unit'),
          isset($item['quantity']) ? (float)$item['quantity'] : 1.00,
          isset($item['taux_usd']) ? (float)$item['taux_usd'] : (isset($item['cost_usd']) ? (float)$item['cost_usd'] : 0.00),
          isset($item['currency_id']) ? (int)$item['currency_id'] : null,
          isset($item['has_tva']) ? (int)$item['has_tva'] : 0,
          isset($item['tva_usd']) ? (float)$item['tva_usd'] : 0.00,
          isset($item['subtotal_usd']) ? (float)$item['subtotal_usd'] : 0.00,
          isset($item['total_usd']) ? (float)$item['total_usd'] : 0.00,
          $userId,
          $userId
        ];
        $this->db->customQuery($sql, $params);
      }

      return true;
    } catch (Exception $e) {
      $this->logError("Error in saveInvoiceItems: " . $e->getMessage());
      return false;
    }
  }

  private function getInvoiceItems($invoiceId)
  {
    try {
      $sql = "SELECT ii.*, qd.description_name as original_description,
                     qc.category_name, qc.category_header, qc.display_order,
                     u.unit_name, curr.currency_short_name
              FROM import_invoice_items_t ii
              LEFT JOIN quotation_descriptions_t qd ON ii.description_id = qd.id
              LEFT JOIN quotation_categories_t qc ON ii.category_id = qc.id
              LEFT JOIN units_master_t u ON ii.unit_id = u.id
              LEFT JOIN currency_master_t curr ON ii.currency_id = curr.id
              WHERE ii.invoice_id = ? AND ii.display = 'Y'
              ORDER BY qc.display_order ASC, ii.id ASC";

      return $this->sanitizeArray($this->db->customQuery($sql, [$invoiceId]) ?: []);
    } catch (Exception $e) {
      $this->logError("Error getting invoice items: " . $e->getMessage());
      return [];
    }
  }

  private function generateNextInvoiceRef($subscriberId)
  {
    try {
      $clientResult = $this->db->customQuery("SELECT short_name FROM clients_t WHERE id = ? LIMIT 1", [$subscriberId]);
      if (empty($clientResult)) throw new Exception("Client not found");
      
      $shortName = strtoupper($clientResult[0]['short_name']);
      $year = date('Y');
      
      $result = $this->db->customQuery(
        "SELECT invoice_ref FROM import_invoices_t WHERE subscriber_id = ? AND invoice_ref LIKE ? ORDER BY id DESC LIMIT 1",
        [$subscriberId, "$year-$shortName-%"]
      );
      
      $nextNumber = 1;
      if (!empty($result)) {
        preg_match('/(\d{4})$/i', $result[0]['invoice_ref'], $matches);
        $nextNumber = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
      }
      
      return sprintf('%s-%s-%04d', $year, $shortName, $nextNumber);
    } catch (Exception $e) {
      $this->logError("Error generating next invoice ref: " . $e->getMessage());
      return date('Y') . '-XXX-0001';
    }
  }

  private function getNextInvoiceRefForClient()
  {
    $subscriberId = (int)($_GET['subscriber_id'] ?? 0);
    if ($subscriberId <= 0) {
      echo json_encode(['success' => false, 'message' => 'Invalid subscriber ID']);
      return;
    }
    echo json_encode(['success' => true, 'invoice_ref' => $this->generateNextInvoiceRef($subscriberId)]);
  }

  private function getStatistics()
  {
    try {
      $sql = "SELECT COUNT(*) as total_invoices,
                SUM(CASE WHEN status = 'PENDING' THEN 1 ELSE 0 END) as pending_invoices,
                SUM(CASE WHEN status = 'COMPLETED' THEN 1 ELSE 0 END) as completed_invoices,
                SUM(CASE WHEN status = 'DRAFT' THEN 1 ELSE 0 END) as draft_invoices,
                SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) THEN 1 ELSE 0 END) as this_month_count
              FROM import_invoices_t";
      $stats = $this->db->customQuery($sql);
      echo json_encode(['success' => true, 'data' => [
        'total_invoices' => (int)($stats[0]['total_invoices'] ?? 0),
        'pending_invoices' => (int)($stats[0]['pending_invoices'] ?? 0),
        'completed_invoices' => (int)($stats[0]['completed_invoices'] ?? 0),
        'draft_invoices' => (int)($stats[0]['draft_invoices'] ?? 0),
        'this_month_count' => (int)($stats[0]['this_month_count'] ?? 0)
      ]]);
    } catch (Exception $e) {
      $this->logError("Error getting statistics: " . $e->getMessage());
      echo json_encode(['success' => true, 'data' => ['total_invoices' => 0, 'pending_invoices' => 0, 'completed_invoices' => 0, 'draft_invoices' => 0, 'this_month_count' => 0]]);
    }
  }

  /**
   * ⭐ UPDATED: Get Licenses - ONLY kind_id IN (1, 2, 5, 6)
   * 1 = IMPORT DEFINITIVE
   * 2 = IMPORT TEMPORARY
   * 5 = UNDER VALUE
   * 6 = HAND CARRY
   */
  private function getLicenses()
  {
    try {
      $clientId = (int)($_GET['subscriber_id'] ?? 0);
      if ($clientId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid subscriber ID', 'data' => []]);
        return;
      }
      
      // ⭐ UPDATED: Filter by kind_id IN (1, 2, 5, 6) for IMPORT types only
      $sql = "SELECT l.id, l.license_number, l.kind_id, k.kind_name, k.kind_short_name
              FROM licenses_t l
              LEFT JOIN kind_master_t k ON l.kind_id = k.id
              WHERE l.client_id = ? 
              AND l.display = 'Y' 
              AND l.status = 'ACTIVE'
              AND l.kind_id IN (1, 2, 5, 6)
              ORDER BY l.license_number ASC";
      
      $licenses = $this->db->customQuery($sql, [$clientId]) ?: [];
      
      echo json_encode(['success' => true, 'data' => $this->sanitizeArray($licenses)]);
    } catch (Exception $e) {
      $this->logError("Error getting licenses: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load licenses', 'data' => []]);
    }
  }

  private function getMCAReferences()
  {
    try {
      $subscriberId = (int)($_GET['subscriber_id'] ?? 0);
      $licenseId = (int)($_GET['license_id'] ?? 0);
      if ($subscriberId <= 0 || $licenseId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid IDs', 'data' => []]);
        return;
      }
      $sql = "SELECT i.id, i.mca_ref FROM imports_t i WHERE i.license_id = ? AND i.subscriber_id = ? AND i.display = 'Y' ORDER BY i.id DESC LIMIT 100";
      echo json_encode(['success' => true, 'data' => $this->sanitizeArray($this->db->customQuery($sql, [$licenseId, $subscriberId]) ?: [])]);
    } catch (Exception $e) {
      $this->logError("Error getting MCA references: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load MCA references', 'data' => []]);
    }
  }

  private function getMCADetails()
  {
    try {
      $mcaId = (int)($_GET['mca_id'] ?? 0);
      if ($mcaId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid MCA ID', 'data' => null]);
        return;
      }
      $sql = "SELECT i.id, i.mca_ref, i.fob, i.fret, i.weight, i.commodity, i.supplier,
                i.currency as currency_id, i.kind as kind_id, i.type_of_goods as type_of_goods_id, i.transport_mode as transport_mode_id,
                i.horse, i.trailer_1, i.trailer_2, i.container, i.wagon, i.airway_bill, i.airway_bill_weight,
                i.invoice as facture_pfi_no, i.po_ref, i.inspection_reports as bivac_inspection,
                i.declaration_reference as declaration_no, i.liquidation_reference as liquidation_no, i.liquidation_date,
                i.quittance_reference as quittance_no, i.quittance_date, i.dgda_out_date as dispatch_deliver_date,
                i.customs_manifest_date as declaration_date, k.kind_name, tg.goods_type as type_of_goods_name,
                tm.transport_mode_name, curr.currency_short_name
              FROM imports_t i
              LEFT JOIN kind_master_t k ON i.kind = k.id
              LEFT JOIN type_of_goods_master_t tg ON i.type_of_goods = tg.id
              LEFT JOIN transport_mode_master_t tm ON i.transport_mode = tm.id
              LEFT JOIN currency_master_t curr ON i.currency = curr.id
              WHERE i.id = ? AND i.display = 'Y' LIMIT 1";
      $mcaDetails = $this->db->customQuery($sql, [$mcaId]);
      if (empty($mcaDetails)) {
        echo json_encode(['success' => false, 'message' => 'MCA not found', 'data' => null]);
        return;
      }
      echo json_encode(['success' => true, 'data' => $this->sanitizeArray($mcaDetails)[0]]);
    } catch (Exception $e) {
      $this->logError("Error getting MCA details: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load MCA details', 'data' => null]);
    }
  }

  /**
   * ⭐ Get Banks for a Subscriber via client_bank_mapping_t
   */
  private function getBanks()
  {
    try {
      $clientId = (int)($_GET['subscriber_id'] ?? 0);
      if ($clientId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid subscriber ID', 'data' => []]);
        return;
      }
      
      $sql = "SELECT ibm.id, ibm.invoice_bank_name as bank_name, 
                     ibm.invoice_bank_account_name as account_name,
                     ibm.invoice_bank_account_number as account_number, 
                     ibm.invoice_bank_swift as swift_code
              FROM client_bank_mapping_t cbm
              INNER JOIN invoice_bank_master_t ibm ON cbm.bank_id = ibm.id
              WHERE cbm.client_id = ? AND ibm.display = 'Y' 
              ORDER BY cbm.id ASC";
      
      $banks = $this->db->customQuery($sql, [$clientId]) ?: [];
      
      echo json_encode(['success' => true, 'data' => $this->sanitizeArray($banks)]);
    } catch (Exception $e) {
      $this->logError("Error getting banks: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load banks', 'data' => []]);
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

      $columns = ['inv.id', 'inv.invoice_ref', 'c.short_name', 'i.mca_ref', 'inv.cif_usd', 'inv.total_duty_cdf', 'inv.status'];
      $orderColumn = $columns[$orderColumnIndex] ?? 'inv.id';

      $baseQuery = "FROM import_invoices_t inv LEFT JOIN clients_t c ON inv.subscriber_id = c.id LEFT JOIN imports_t i ON inv.mca_id = i.id WHERE 1=1";
      
      $filterCondition = "";
      if ($filter === 'completed') $filterCondition = " AND inv.status = 'COMPLETED'";
      elseif ($filter === 'pending') $filterCondition = " AND inv.status = 'PENDING'";
      elseif ($filter === 'draft') $filterCondition = " AND inv.status = 'DRAFT'";

      $searchCondition = "";
      $params = [];
      if (!empty($searchValue)) {
        $searchCondition = " AND (inv.invoice_ref LIKE ? OR i.mca_ref LIKE ? OR c.short_name LIKE ?)";
        $searchParam = "%{$searchValue}%";
        $params = [$searchParam, $searchParam, $searchParam];
      }

      $totalResult = $this->db->customQuery("SELECT COUNT(*) as total FROM import_invoices_t");
      $totalRecords = (int)($totalResult[0]['total'] ?? 0);
      
      $filteredResult = $this->db->customQuery("SELECT COUNT(*) as total {$baseQuery} {$filterCondition} {$searchCondition}", $params);
      $filteredRecords = (int)($filteredResult[0]['total'] ?? 0);

      $dataSql = "SELECT inv.id, inv.invoice_ref, inv.cif_usd, inv.total_duty_cdf, inv.status, c.short_name as subscriber_name, i.mca_ref, inv.created_at {$baseQuery} {$filterCondition} {$searchCondition} ORDER BY {$orderColumn} {$orderDirection} LIMIT {$length} OFFSET {$start}";
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
      $this->logError("INSERT Invoice - POST data received");

      $validation = $this->validateInvoiceData($_POST);
      if (!$validation['success']) {
        echo json_encode($validation);
        return;
      }

      $sql = "INSERT INTO import_invoices_t (
                subscriber_id, license_id, mca_id, kind_id, type_of_goods_id, transport_mode_id,
                invoice_ref, tax_duty_part, fob_currency_id, fob_usd, fret_currency_id, fret_usd,
                assurance_currency_id, assurance_usd, autres_charges_currency_id, autres_charges_usd,
                rate_cdf_inv, rate_cdf_usd_bcc, cif_usd, cif_cdf, total_duty_cdf, poids_kg,
                tariff_code_client, horse, trailer_1, trailer_2, container, wagon,
                airway_bill, airway_bill_weight, facture_pfi_no, po_ref, bivac_inspection,
                produit, exoneration_code, declaration_no, declaration_date, liquidation_no,
                liquidation_date, quittance_no, quittance_date, dispatch_deliver_date,
                bank_id, invoice_template, arsp, status, created_by, updated_by
              ) VALUES (
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?
              )";

      $params = [
        $this->toInt($_POST['subscriber_id']),
        $this->toInt($_POST['license_id']),
        $this->toInt($_POST['mca_id']),
        $this->toInt($_POST['kind_id'] ?? null),
        $this->toInt($_POST['type_of_goods_id'] ?? null),
        $this->toInt($_POST['transport_mode_id'] ?? null),
        $this->clean($_POST['invoice_ref']),
        $this->clean($_POST['tax_duty_part'] ?? 'Include'),
        $this->toInt($_POST['fob_currency_id'] ?? null),
        $this->toDecimal($_POST['fob_usd'] ?? 0),
        $this->toInt($_POST['fret_currency_id'] ?? null),
        $this->toDecimal($_POST['fret_usd'] ?? 0),
        $this->toInt($_POST['assurance_currency_id'] ?? null),
        $this->toDecimal($_POST['assurance_usd'] ?? 0),
        $this->toInt($_POST['autres_charges_currency_id'] ?? null),
        $this->toDecimal($_POST['autres_charges_usd'] ?? 0),
        $this->toDecimal($_POST['rate_cdf_inv'] ?? 2500),
        $this->toDecimal($_POST['rate_cdf_usd_bcc'] ?? 2500),
        $this->toDecimal($_POST['cif_usd'] ?? 0),
        $this->toDecimal($_POST['cif_cdf'] ?? 0),
        $this->toDecimal($_POST['total_duty_cdf'] ?? 0),
        $this->toDecimal($_POST['poids_kg'] ?? 0),
        $this->clean($_POST['tariff_code_client'] ?? null),
        $this->clean($_POST['horse'] ?? null),
        $this->clean($_POST['trailer_1'] ?? null),
        $this->clean($_POST['trailer_2'] ?? null),
        $this->clean($_POST['container'] ?? null),
        $this->clean($_POST['wagon'] ?? null),
        $this->clean($_POST['airway_bill'] ?? null),
        $this->toDecimal($_POST['airway_bill_weight'] ?? null),
        $this->clean($_POST['facture_pfi_no'] ?? null),
        $this->clean($_POST['po_ref'] ?? null),
        $this->clean($_POST['bivac_inspection'] ?? null),
        $this->clean($_POST['produit'] ?? 'Default Commodity'),
        $this->clean($_POST['exoneration_code'] ?? null),
        $this->clean($_POST['declaration_no'] ?? null),
        $this->toDate($_POST['declaration_date'] ?? null),
        $this->clean($_POST['liquidation_no'] ?? null),
        $this->toDate($_POST['liquidation_date'] ?? null),
        $this->clean($_POST['quittance_no'] ?? null),
        $this->toDate($_POST['quittance_date'] ?? null),
        $this->toDate($_POST['dispatch_deliver_date'] ?? null),
        $this->toInt($_POST['bank_id'] ?? null),
        $this->clean($_POST['invoice_template'] ?? null),
        $this->clean($_POST['arsp'] ?? null),
        'PENDING',
        (int)($_SESSION['user_id'] ?? 1),
        (int)($_SESSION['user_id'] ?? 1)
      ];

      $this->logError("Executing INSERT with " . count($params) . " parameters");

      $result = $this->db->customQuery($sql, $params);

      $lastIdResult = $this->db->customQuery("SELECT LAST_INSERT_ID() as id");
      $insertId = (int)($lastIdResult[0]['id'] ?? 0);

      $this->logError("Insert completed. ID: " . $insertId);

      if ($insertId > 0) {
        $itemsJson = $_POST['quotation_items'] ?? '';
        if (!empty($itemsJson)) {
          $this->saveInvoiceItems($insertId, $itemsJson);
        }

        echo json_encode(['success' => true, 'message' => 'Invoice created successfully!', 'id' => $insertId]);
      } else {
        $checkResult = $this->db->customQuery("SELECT id FROM import_invoices_t WHERE invoice_ref = ? ORDER BY id DESC LIMIT 1", [$this->clean($_POST['invoice_ref'])]);
        if (!empty($checkResult)) {
          $insertId = (int)$checkResult[0]['id'];
          $itemsJson = $_POST['quotation_items'] ?? '';
          if (!empty($itemsJson)) {
            $this->saveInvoiceItems($insertId, $itemsJson);
          }
          echo json_encode(['success' => true, 'message' => 'Invoice created successfully!', 'id' => $insertId]);
        } else {
          echo json_encode(['success' => false, 'message' => 'Failed to create invoice']);
        }
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

      $existing = $this->db->customQuery("SELECT id FROM import_invoices_t WHERE id = ?", [$invoiceId]);
      if (empty($existing)) {
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
        return;
      }

      $validation = $this->validateInvoiceData($_POST, $invoiceId);
      if (!$validation['success']) {
        echo json_encode($validation);
        return;
      }

      $sql = "UPDATE import_invoices_t SET
                subscriber_id = ?, license_id = ?, mca_id = ?, kind_id = ?, type_of_goods_id = ?, transport_mode_id = ?,
                invoice_ref = ?, tax_duty_part = ?, fob_currency_id = ?, fob_usd = ?, fret_currency_id = ?, fret_usd = ?,
                assurance_currency_id = ?, assurance_usd = ?, autres_charges_currency_id = ?, autres_charges_usd = ?,
                rate_cdf_inv = ?, rate_cdf_usd_bcc = ?, cif_usd = ?, cif_cdf = ?, total_duty_cdf = ?, poids_kg = ?,
                tariff_code_client = ?, horse = ?, trailer_1 = ?, trailer_2 = ?, container = ?, wagon = ?,
                airway_bill = ?, airway_bill_weight = ?, facture_pfi_no = ?, po_ref = ?, bivac_inspection = ?,
                produit = ?, exoneration_code = ?, declaration_no = ?, declaration_date = ?, liquidation_no = ?,
                liquidation_date = ?, quittance_no = ?, quittance_date = ?, dispatch_deliver_date = ?,
                bank_id = ?, invoice_template = ?, arsp = ?, updated_by = ?, updated_at = NOW()
              WHERE id = ?";

      $params = [
        $this->toInt($_POST['subscriber_id']),
        $this->toInt($_POST['license_id']),
        $this->toInt($_POST['mca_id']),
        $this->toInt($_POST['kind_id'] ?? null),
        $this->toInt($_POST['type_of_goods_id'] ?? null),
        $this->toInt($_POST['transport_mode_id'] ?? null),
        $this->clean($_POST['invoice_ref']),
        $this->clean($_POST['tax_duty_part'] ?? 'Include'),
        $this->toInt($_POST['fob_currency_id'] ?? null),
        $this->toDecimal($_POST['fob_usd'] ?? 0),
        $this->toInt($_POST['fret_currency_id'] ?? null),
        $this->toDecimal($_POST['fret_usd'] ?? 0),
        $this->toInt($_POST['assurance_currency_id'] ?? null),
        $this->toDecimal($_POST['assurance_usd'] ?? 0),
        $this->toInt($_POST['autres_charges_currency_id'] ?? null),
        $this->toDecimal($_POST['autres_charges_usd'] ?? 0),
        $this->toDecimal($_POST['rate_cdf_inv'] ?? 2500),
        $this->toDecimal($_POST['rate_cdf_usd_bcc'] ?? 2500),
        $this->toDecimal($_POST['cif_usd'] ?? 0),
        $this->toDecimal($_POST['cif_cdf'] ?? 0),
        $this->toDecimal($_POST['total_duty_cdf'] ?? 0),
        $this->toDecimal($_POST['poids_kg'] ?? 0),
        $this->clean($_POST['tariff_code_client'] ?? null),
        $this->clean($_POST['horse'] ?? null),
        $this->clean($_POST['trailer_1'] ?? null),
        $this->clean($_POST['trailer_2'] ?? null),
        $this->clean($_POST['container'] ?? null),
        $this->clean($_POST['wagon'] ?? null),
        $this->clean($_POST['airway_bill'] ?? null),
        $this->toDecimal($_POST['airway_bill_weight'] ?? null),
        $this->clean($_POST['facture_pfi_no'] ?? null),
        $this->clean($_POST['po_ref'] ?? null),
        $this->clean($_POST['bivac_inspection'] ?? null),
        $this->clean($_POST['produit'] ?? 'Default Commodity'),
        $this->clean($_POST['exoneration_code'] ?? null),
        $this->clean($_POST['declaration_no'] ?? null),
        $this->toDate($_POST['declaration_date'] ?? null),
        $this->clean($_POST['liquidation_no'] ?? null),
        $this->toDate($_POST['liquidation_date'] ?? null),
        $this->clean($_POST['quittance_no'] ?? null),
        $this->toDate($_POST['quittance_date'] ?? null),
        $this->toDate($_POST['dispatch_deliver_date'] ?? null),
        $this->toInt($_POST['bank_id'] ?? null),
        $this->clean($_POST['invoice_template'] ?? null),
        $this->clean($_POST['arsp'] ?? null),
        (int)($_SESSION['user_id'] ?? 1),
        $invoiceId
      ];

      $this->db->customQuery($sql, $params);

      $itemsJson = $_POST['quotation_items'] ?? '';
      if (!empty($itemsJson)) {
        $this->saveInvoiceItems($invoiceId, $itemsJson);
      }

      echo json_encode(['success' => true, 'message' => 'Invoice updated successfully!']);

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

      $existing = $this->db->customQuery("SELECT id FROM import_invoices_t WHERE id = ?", [$invoiceId]);
      if (empty($existing)) {
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
        return;
      }

      $this->db->customQuery("DELETE FROM import_invoice_items_t WHERE invoice_id = ?", [$invoiceId]);
      $this->db->customQuery("DELETE FROM import_invoices_t WHERE id = ?", [$invoiceId]);
      
      echo json_encode(['success' => true, 'message' => 'Invoice deleted successfully!']);

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

      $sql = "SELECT inv.*, c.short_name as subscriber_name, c.company_name, l.license_number, i.mca_ref
              FROM import_invoices_t inv
              LEFT JOIN clients_t c ON inv.subscriber_id = c.id
              LEFT JOIN licenses_t l ON inv.license_id = l.id
              LEFT JOIN imports_t i ON inv.mca_id = i.id
              WHERE inv.id = ?";
      $invoice = $this->db->customQuery($sql, [$invoiceId]);
      
      if (!empty($invoice)) {
        echo json_encode(['success' => true, 'data' => $this->sanitizeArray($invoice)[0], 'items' => $this->getInvoiceItems($invoiceId)]);
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

      $result = $this->db->customQuery("SELECT inv.*, c.short_name as client_name, l.license_number FROM import_invoices_t inv LEFT JOIN clients_t c ON inv.subscriber_id = c.id LEFT JOIN licenses_t l ON inv.license_id = l.id WHERE inv.id = ?", [$invoiceId]);
      if (empty($result)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
        return;
      }

      $data = $result[0];
      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('Invoice');
      $sheet->setCellValue('A1', 'MALABAR RDC SARL - INVOICE');
      $sheet->mergeCells('A1:D1');

      $row = 3;
      foreach ([['Invoice Ref:', $data['invoice_ref'] ?? ''], ['Client:', $data['client_name'] ?? ''], ['License:', $data['license_number'] ?? ''], ['CIF (USD):', '$' . number_format((float)($data['cif_usd'] ?? 0), 2)]] as $header) {
        $sheet->setCellValue('A' . $row, $header[0]);
        $sheet->setCellValue('B' . $row, $header[1]);
        $row++;
      }
      foreach (range('A', 'D') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

      $filename = 'Invoice_' . preg_replace('/[^a-zA-Z0-9]/', '_', $data['invoice_ref'] ?? 'INV') . '.xlsx';
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

      $invoices = $this->db->customQuery("SELECT inv.invoice_ref, c.short_name as client_name, l.license_number, inv.cif_usd, inv.cif_cdf, inv.status, inv.created_at FROM import_invoices_t inv LEFT JOIN clients_t c ON inv.subscriber_id = c.id LEFT JOIN licenses_t l ON inv.license_id = l.id ORDER BY inv.id DESC");
      
      if (empty($invoices)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'No invoices found']);
        return;
      }

      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('All Invoices');
      $sheet->setCellValue('A1', 'MALABAR RDC SARL - ALL INVOICES');
      $sheet->mergeCells('A1:G1');
      $sheet->fromArray([['Invoice Ref', 'Client', 'License', 'CIF USD', 'CIF CDF', 'Status', 'Created']], null, 'A3');

      $rowIndex = 4;
      foreach ($invoices as $inv) {
        $sheet->fromArray([[
          $inv['invoice_ref'] ?? '', $inv['client_name'] ?? '', $inv['license_number'] ?? '',
          number_format((float)($inv['cif_usd'] ?? 0), 2), number_format((float)($inv['cif_cdf'] ?? 0), 2),
          $inv['status'] ?? '', $inv['created_at'] ? date('d-m-Y', strtotime($inv['created_at'])) : ''
        ]], null, 'A' . $rowIndex);
        $rowIndex++;
      }
      foreach (range('A', 'G') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

      $filename = 'All_Invoices_' . date('Ymd_His') . '.xlsx';
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

      $invoice = $this->db->customQuery("SELECT * FROM import_invoices_t WHERE id = ? LIMIT 1", [$invoiceId]);
      if (empty($invoice)) die("Invoice not found");
      
      $invoice = $invoice[0];
      $subscriberId = $invoice['subscriber_id'] ?? 0;

      $data = [
        'invoice_ref' => $invoice['invoice_ref'] ?? '',
        'fob_usd' => $invoice['fob_usd'] ?? 0,
        'fret_usd' => $invoice['fret_usd'] ?? 0,
        'assurance_usd' => $invoice['assurance_usd'] ?? 0,
        'autres_charges_usd' => $invoice['autres_charges_usd'] ?? 0,
        'rate_cdf_inv' => $invoice['rate_cdf_inv'] ?? 2500,
        'rate_cdf_usd_bcc' => $invoice['rate_cdf_usd_bcc'] ?? 2500,
        'cif_usd' => $invoice['cif_usd'] ?? 0,
        'cif_cdf' => $invoice['cif_cdf'] ?? 0,
        'poids_kg' => $invoice['poids_kg'] ?? 0,
        'horse' => $invoice['horse'] ?? '',
        'trailer_1' => $invoice['trailer_1'] ?? '',
        'container' => $invoice['container'] ?? '',
        'wagon' => $invoice['wagon'] ?? '',
        'airway_bill' => $invoice['airway_bill'] ?? '',
        'airway_bill_weight' => $invoice['airway_bill_weight'] ?? 0,
        'facture_pfi_no' => $invoice['facture_pfi_no'] ?? '',
        'po_ref' => $invoice['po_ref'] ?? '',
        'bivac_inspection' => $invoice['bivac_inspection'] ?? '',
        'produit' => $invoice['produit'] ?? '',
        'tariff_code_client' => $invoice['tariff_code_client'] ?? '',
        'exoneration_code' => $invoice['exoneration_code'] ?? '',
        'declaration_no' => $invoice['declaration_no'] ?? '',
        'declaration_date' => $invoice['declaration_date'] ?? '',
        'liquidation_no' => $invoice['liquidation_no'] ?? '',
        'liquidation_date' => $invoice['liquidation_date'] ?? '',
        'quittance_no' => $invoice['quittance_no'] ?? '',
        'quittance_date' => $invoice['quittance_date'] ?? '',
        'dispatch_deliver_date' => $invoice['dispatch_deliver_date'] ?? '',
        'arsp' => $invoice['arsp'] ?? 'Disabled'
      ];

      if (!empty($subscriberId)) {
        $client = $this->db->customQuery("SELECT short_name, company_name, address, rccm_number, nif_number, id_nat_number, import_export_number FROM clients_t WHERE id = ? LIMIT 1", [$subscriberId]);
        if (!empty($client)) {
          $data['client_name'] = $client[0]['short_name'] ?? '';
          $data['client_company'] = $client[0]['company_name'] ?? '';
          $data['client_address'] = $client[0]['address'] ?? '';
          $data['client_rccm'] = $client[0]['rccm_number'] ?? '';
          $data['client_nif'] = $client[0]['nif_number'] ?? '';
          $data['client_id_nat'] = $client[0]['id_nat_number'] ?? '';
          $data['client_import_export'] = $client[0]['import_export_number'] ?? '';
        }
      }

      $data['client_name'] = $data['client_name'] ?? 'N/A';
      $data['client_company'] = $data['client_company'] ?? '';
      $data['client_address'] = $data['client_address'] ?? '';
      $data['client_rccm'] = $data['client_rccm'] ?? '';
      $data['client_nif'] = $data['client_nif'] ?? '';
      $data['client_id_nat'] = $data['client_id_nat'] ?? '';
      $data['client_import_export'] = $data['client_import_export'] ?? '';
      $data['client_tva'] = '';

      if (!empty($invoice['mca_id'])) {
        $mca = $this->db->customQuery("SELECT supplier, commodity FROM imports_t WHERE id = ? LIMIT 1", [$invoice['mca_id']]);
        if (!empty($mca)) {
          $data['supplier'] = $mca[0]['supplier'] ?? '';
          if (empty($data['produit'])) $data['produit'] = $mca[0]['commodity'] ?? '';
        }
      }
      $data['supplier'] = $data['supplier'] ?? '';
      if (empty($data['produit'])) $data['produit'] = 'N/A';

      if (!empty($invoice['license_id'])) {
        $license = $this->db->customQuery("SELECT license_number FROM licenses_t WHERE id = ? LIMIT 1", [$invoice['license_id']]);
        if (!empty($license)) $data['license_number'] = $license[0]['license_number'] ?? '';
      }
      $data['license_number'] = $data['license_number'] ?? '';

      if (!empty($invoice['transport_mode_id'])) {
        $transport = $this->db->customQuery("SELECT transport_mode_name FROM transport_mode_master_t WHERE id = ? LIMIT 1", [$invoice['transport_mode_id']]);
        if (!empty($transport)) $data['transport_mode_name'] = $transport[0]['transport_mode_name'] ?? 'ROAD';
      }
      $data['transport_mode_name'] = $data['transport_mode_name'] ?? 'ROAD';

      $banks = $this->db->customQuery("SELECT ibm.invoice_bank_name, ibm.invoice_bank_account_name, ibm.invoice_bank_account_number, ibm.invoice_bank_swift FROM client_bank_mapping_t cbm INNER JOIN invoice_bank_master_t ibm ON cbm.bank_id = ibm.id WHERE cbm.client_id = ? AND ibm.display = 'Y' ORDER BY cbm.id ASC", [$subscriberId]);
      $data['banks'] = $banks ?: [];
      $data['items'] = $this->getInvoiceItems($invoiceId);

      $html = $this->generateInvoicePDF($data);

      $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_top' => 5, 'margin_bottom' => 5, 'margin_left' => 5, 'margin_right' => 5]);
      $mpdf->WriteHTML($html);
      $mpdf->Output('Invoice_' . preg_replace('/[^a-zA-Z0-9]/', '_', $data['invoice_ref']) . '.pdf', 'I');

    } catch (Exception $e) {
      $this->logError("PDF Exception: " . $e->getMessage());
      die("PDF generation failed: " . $e->getMessage());
    }
  }

  private function generateInvoicePDF($data)
  {
    $invoiceRef = htmlspecialchars($data['invoice_ref'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
    $clientName = htmlspecialchars($data['client_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
    $clientAddress = htmlspecialchars($data['client_address'] ?? '', ENT_QUOTES, 'UTF-8');
    $clientRCCM = htmlspecialchars($data['client_rccm'] ?? '', ENT_QUOTES, 'UTF-8');
    $clientNIF = htmlspecialchars($data['client_nif'] ?? '', ENT_QUOTES, 'UTF-8');
    $clientIDNat = htmlspecialchars($data['client_id_nat'] ?? '', ENT_QUOTES, 'UTF-8');
    $clientImportExport = htmlspecialchars($data['client_import_export'] ?? '', ENT_QUOTES, 'UTF-8');
    $clientTVA = htmlspecialchars($data['client_tva'] ?? '-', ENT_QUOTES, 'UTF-8');
    $supplier = htmlspecialchars($data['supplier'] ?? '', ENT_QUOTES, 'UTF-8');
    $commodity = htmlspecialchars($data['produit'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
    $facturePFI = htmlspecialchars($data['facture_pfi_no'] ?? '', ENT_QUOTES, 'UTF-8');
    $poFour = htmlspecialchars($data['po_ref'] ?? '', ENT_QUOTES, 'UTF-8');
    $bivacInspection = htmlspecialchars($data['bivac_inspection'] ?? '', ENT_QUOTES, 'UTF-8');
    $transportMode = strtoupper(htmlspecialchars($data['transport_mode_name'] ?? 'ROAD', ENT_QUOTES, 'UTF-8'));
    $horse = htmlspecialchars($data['horse'] ?? '', ENT_QUOTES, 'UTF-8');
    $trailer1 = htmlspecialchars($data['trailer_1'] ?? '', ENT_QUOTES, 'UTF-8');
    $container = htmlspecialchars($data['container'] ?? '', ENT_QUOTES, 'UTF-8');
    $truckTrailerContainer = trim($horse . '/' . $trailer1 . '/' . $container, '/');
    $poidsKg = number_format((float)($data['poids_kg'] ?? 0), 2);
    $fobUSD = number_format((float)($data['fob_usd'] ?? 0), 2);
    $fretUSD = number_format((float)($data['fret_usd'] ?? 0), 2);
    $assuranceUSD = number_format((float)($data['assurance_usd'] ?? 0), 2);
    $autresChargesUSD = number_format((float)($data['autres_charges_usd'] ?? 0), 2);
    $cifUSD = number_format((float)($data['cif_usd'] ?? 0), 2);
    $cifCDF = number_format((float)($data['cif_cdf'] ?? 0), 2);
    $rateCDFInv = number_format((float)($data['rate_cdf_inv'] ?? 2500), 2);
    $rateCDFBCC = number_format((float)($data['rate_cdf_usd_bcc'] ?? 2500), 2);
    $tariffCode = htmlspecialchars($data['tariff_code_client'] ?? '', ENT_QUOTES, 'UTF-8');
    $exonerationCode = htmlspecialchars($data['exoneration_code'] ?? '', ENT_QUOTES, 'UTF-8');
    $licenseNumber = htmlspecialchars($data['license_number'] ?? '', ENT_QUOTES, 'UTF-8');
    $declarationNo = htmlspecialchars($data['declaration_no'] ?? '', ENT_QUOTES, 'UTF-8');
    $declarationDate = !empty($data['declaration_date']) ? date('d/m/Y', strtotime($data['declaration_date'])) : '';
    $liquidationNo = htmlspecialchars($data['liquidation_no'] ?? '', ENT_QUOTES, 'UTF-8');
    $liquidationDate = !empty($data['liquidation_date']) ? date('d/m/Y', strtotime($data['liquidation_date'])) : '';
    $quittanceNo = htmlspecialchars($data['quittance_no'] ?? '', ENT_QUOTES, 'UTF-8');
    $quittanceDate = !empty($data['quittance_date']) ? date('d/m/Y', strtotime($data['quittance_date'])) : '';
    $dispatchDate = !empty($data['dispatch_deliver_date']) ? date('d/m/Y', strtotime($data['dispatch_deliver_date'])) : '';
    $invoiceDate = date('d M y');

    $logoPath = $this->logoPath;
    $logoHtml = file_exists($logoPath) ? '<img src="' . $logoPath . '" style="max-width:160px;max-height:35px;">' : '<b>MALABAR RDC SARL</b>';

    $items = $data['items'] ?? [];
    $totalExclTVA = 0;
    $totalTVA = 0;
    foreach ($items as $item) {
      $totalExclTVA += (float)($item['subtotal_usd'] ?? 0);
      $totalTVA += (float)($item['tva_amount'] ?? 0);
    }
    
    if ($totalExclTVA == 0) $totalExclTVA = 1520.00;
    if ($totalTVA == 0) $totalTVA = 56.00;
    
    $grandTotal = $totalExclTVA + $totalTVA;

    $arspEnabled = strtolower($data['arsp'] ?? 'disabled') === 'enabled';
    $arspTax = 0;
    $netPayable = $grandTotal;
    if ($arspEnabled) {
      $arspTax = $grandTotal * 0.012;
      $netPayable = $grandTotal + $arspTax;
    }
    $equivalentCDF = $netPayable * (float)($data['rate_cdf_inv'] ?? 2500);

    $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>
body{font-family:Arial,sans-serif;font-size:6pt;margin:0;padding:3mm;line-height:1.2;}
table{border-collapse:collapse;width:100%;}
td,th{padding:1px 3px;vertical-align:top;}
.b td,.b th{border:1px solid #000;}
.r{text-align:right;}.c{text-align:center;}.bo{font-weight:bold;}.g{background:#e0e0e0;}
.bk{background:#000;color:#fff;padding:2px 4px;font-weight:bold;font-size:6pt;}
.box{border:1px solid #000;}.box td{padding:2px 4px;border:none;}
.client-header{background:#e0e0e0;text-align:center;font-weight:bold;border-bottom:1px solid #000;}
</style></head><body>

<table><tr><td style="width:50%;">' . $logoHtml . '</td>
<td style="text-align:right;font-size:5pt;line-height:1.3;">No. 1068, Avenue Ruwe, Quartier Makutano,<br>Lubumbashi, DRC<br>RCCM: 13-B-1122, ID NAT. 6-9-N91867E<br>NIF: A 1309334 L<br>VAT Ref # 145/DGI/DGE/INF/BN/TVA/2020<br>Capital Social: 45.000.000 FC</td></tr></table>

<div style="border:1px solid #000;padding:3px 10px;font-weight:bold;font-size:9pt;width:300px;text-align:center;margin:2mm 0;">FACTURE</div>

<table><tr><td style="width:46%;vertical-align:top;padding-right:4%;">
<table class="box" style="margin-bottom:2mm;"><tr><td class="client-header">CLIENT</td></tr>
<tr><td style="font-size:5pt;line-height:1.3;">CLIENT:<br>' . $clientName . '<br>' . $clientAddress . '</td></tr>
<tr><td>No RCCM: ' . $clientRCCM . '</td></tr><tr><td>No NIF.: ' . $clientNIF . '</td></tr>
<tr><td>No IDN.: ' . $clientIDNat . '</td></tr><tr><td>No IMPORT/EXPORT: ' . $clientImportExport . '</td></tr>
<tr><td>No TVA: ' . $clientTVA . '</td></tr></table>

<table class="b"><tr><td style="width:55%;">Poids (Kg):</td><td class="r">' . $poidsKg . '</td></tr>
<tr><td>FOB/USD:</td><td class="r">' . $fobUSD . '</td></tr><tr><td>Fret/USD:</td><td class="r">' . $fretUSD . '</td></tr>
<tr><td>Autres Charges/USD:</td><td class="r">' . $autresChargesUSD . '</td></tr><tr><td>Assurance/USD:</td><td class="r">' . $assuranceUSD . '</td></tr>
<tr><td class="bo">CIF/USD:</td><td class="r bo">' . $cifUSD . '</td></tr><tr><td class="bo">CIF/CDF:</td><td class="r bo">' . $cifCDF . '</td></tr></table>
</td><td style="width:50%;vertical-align:top;">

<table class="b"><tr class="g"><td style="width:24%;">FACTURE N°</td><td colspan="3" class="r bo">' . $invoiceRef . '</td></tr>
<tr><td>Date</td><td colspan="3">' . $invoiceDate . '</td></tr><tr><td>Mode Transport</td><td colspan="3">' . $transportMode . '</td></tr>
<tr><td>Truck/Trailer/Container</td><td colspan="3">' . $truckTrailerContainer . '</td></tr><tr><td>Fournisseur</td><td colspan="3">' . $supplier . '</td></tr>
<tr><td>Facture/PFI:</td><td style="width:24%;" class="bo">' . $facturePFI . '</td><td style="width:20%;">PO Four:</td><td>' . $poFour . '</td></tr>
<tr><td>Produit:</td><td colspan="3">' . $commodity . '</td></tr>
<tr><td>BIVAC Insp.:</td><td>' . $bivacInspection . '</td><td>License:</td><td class="bo">' . $licenseNumber . '</td></tr>
<tr><td>Tariff Code:</td><td colspan="3" class="bo">' . $tariffCode . '</td></tr><tr><td>Dispatch/Deliver Date:</td><td colspan="3">' . $dispatchDate . '</td></tr>
<tr><td>Exoneration/Code:</td><td colspan="3">' . $exonerationCode . '</td></tr>
<tr><td>Rate(CDF/USD) BCC:</td><td>' . $rateCDFBCC . '</td><td>Rate(CDF/USD) Inv.:</td><td>' . $rateCDFInv . '</td></tr>
<tr><td>FOB (USD):</td><td colspan="3">' . $fobUSD . '</td></tr>
<tr><td>Declaration:</td><td class="bo">' . $declarationNo . '</td><td colspan="2" class="bo">' . $declarationDate . '</td></tr>
<tr><td>Liquidation:</td><td class="bo">' . $liquidationNo . '</td><td colspan="2" class="bo">' . $liquidationDate . '</td></tr>
<tr><td>Quittance:</td><td class="bo">' . $quittanceNo . '</td><td colspan="2" class="bo">' . $quittanceDate . '</td></tr></table>
</td></tr></table>

<div class="bk" style="margin-top:2mm;">OTHER CHARGES / AUTRES FRAIS</div>
<table class="b"><tr class="g"><th style="width:32%;">Description</th><th style="width:14%;">Unit</th><th style="width:8%;">Qty</th><th style="width:14%;">Taux/USD</th><th style="width:14%;">TVA/USD</th><th style="width:18%;">TOTAL EN USD</th></tr>
<tr><td>Enlevement d\'urgence</td><td class="c">Per Declaration</td><td class="c">1.00</td><td class="r">150.00</td><td class="r">0.00</td><td class="r">150.00</td></tr>
<tr><td>Frais de transite (TR8/TR1-Declarations)</td><td class="c">Per Declaration</td><td class="c">1.00</td><td class="r">100.00</td><td class="r">0.00</td><td class="r">100.00</td></tr>
<tr><td>Frais Seguce</td><td class="c">Per Declaration</td><td class="c">1.00</td><td class="r">120.00</td><td class="r">0.00</td><td class="r">120.00</td></tr>
<tr><td>Scelles Electroniques</td><td class="c">Per Declaration</td><td class="c">1.00</td><td class="r">35.00</td><td class="r">0.00</td><td class="r">35.00</td></tr>
<tr class="g"><td colspan="3" class="bo">Sub-total</td><td class="r bo">405.00</td><td class="r bo">0.00</td><td class="r bo">405.00</td></tr></table>

<div class="bk" style="margin-top:1mm;">OPERATIONAL COSTS / COUT OPERATIONEL</div>
<table class="b"><tr class="g"><th style="width:32%;">Description</th><th style="width:14%;">Unit</th><th style="width:8%;">Qty</th><th style="width:14%;">Taux/USD</th><th style="width:14%;">TVA/USD</th><th style="width:18%;">TOTAL EN USD</th></tr>
<tr><td>Operation Cost</td><td class="c">Per Declaration</td><td class="c">1.00</td><td class="r">280.00</td><td class="r">0.00</td><td class="r">280.00</td></tr>
<tr class="g"><td colspan="3" class="bo">Sub-total</td><td class="r bo">280.00</td><td class="r bo">0.00</td><td class="r bo">280.00</td></tr></table>

<div class="bk" style="margin-top:1mm;">SERVICE FEE / SERVICES</div>
<table class="b"><tr class="g"><th style="width:32%;">Description</th><th style="width:14%;">Unit</th><th style="width:8%;">Qty</th><th style="width:14%;">Taux/USD</th><th style="width:14%;">TVA/USD</th><th style="width:18%;">TOTAL EN USD</th></tr>
<tr><td>CONTRACTOR AGENCY FEE / FRAIS D\'AGENCE</td><td class="c">Per Declaration</td><td class="c">1.00</td><td class="r">175.00</td><td class="r">28.00</td><td class="r">203.00</td></tr>
<tr class="g"><td colspan="3" class="bo">Sub-total</td><td class="r bo">175.00</td><td class="r bo">28.00</td><td class="r bo">203.00</td></tr></table>';

    $totalRows = $arspEnabled ? 6 : 4;
    $html .= '<table style="margin-top:2mm;"><tr><td style="width:32%;" rowspan="' . $totalRows . '">' . $logoHtml . '</td><td style="width:14%;"></td><td style="width:8%;"></td>
<td colspan="2" style="width:28%;border:1px solid #000;border-right:none;padding:1px 3px;" class="r">Total excl. TVA</td>
<td style="width:18%;border:1px solid #000;padding:1px 3px;" class="r">$ ' . number_format($totalExclTVA, 2) . '</td></tr>
<tr><td></td><td></td><td colspan="2" style="border:1px solid #000;border-right:none;border-top:none;padding:1px 3px;" class="r">TVA 16%</td>
<td style="border:1px solid #000;border-top:none;padding:1px 3px;" class="r">$ ' . number_format($totalTVA, 2) . '</td></tr>
<tr><td></td><td></td><td colspan="2" style="border:1px solid #000;border-right:none;border-top:none;padding:1px 3px;" class="r bo">Grand Total</td>
<td style="border:1px solid #000;border-top:none;padding:1px 3px;" class="r bo">$ ' . number_format($grandTotal, 2) . '</td></tr>';

    if ($arspEnabled) {
      $html .= '<tr><td></td><td></td><td colspan="2" style="border:1px solid #000;border-right:none;border-top:none;padding:1px 3px;" class="r">ARSP Tax (1.2%)</td>
<td style="border:1px solid #000;border-top:none;padding:1px 3px;" class="r">$ ' . number_format($arspTax, 2) . '</td></tr>
<tr><td></td><td></td><td colspan="2" style="border:1px solid #000;border-right:none;border-top:none;padding:1px 3px;" class="r bo">Net Payable</td>
<td style="border:1px solid #000;border-top:none;padding:1px 3px;" class="r bo">$ ' . number_format($netPayable, 2) . '</td></tr>';
    }

    $html .= '<tr><td></td><td></td><td colspan="2" style="border:1px solid #000;border-right:none;border-top:none;padding:1px 3px;" class="r">Equivalent en CDF</td>
<td style="border:1px solid #000;border-top:none;padding:1px 3px;" class="r">' . number_format($equivalentCDF, 2) . '</td></tr></table>';

    $banks = $data['banks'] ?? [];
    if (!empty($banks)) {
      $html .= '<div style="text-align:center;font-size:5pt;margin:3mm 0 2mm 0;text-transform:uppercase;">VEUILLEZ TROUVER CI-DESSOUS LES DETAILS DE NOTRE COMPTE BANCAIRE</div>';
      $html .= '<table style="width:100%;"><tr>';

      foreach ($banks as $index => $bank) {
        $bankName = htmlspecialchars($bank['invoice_bank_name'] ?? '', ENT_QUOTES, 'UTF-8');
        $accountName = htmlspecialchars($bank['invoice_bank_account_name'] ?? '', ENT_QUOTES, 'UTF-8');
        $accountNumber = htmlspecialchars($bank['invoice_bank_account_number'] ?? '', ENT_QUOTES, 'UTF-8');
        $swift = htmlspecialchars($bank['invoice_bank_swift'] ?? '', ENT_QUOTES, 'UTF-8');

        $tdStyle = 'width:49%;vertical-align:top;' . ($index > 0 ? 'padding-left:2%;' : '');

        $html .= '<td style="' . $tdStyle . '">';
        $html .= '<table style="width:100%;border:1px solid #000;">';
        $html .= '<tr><td style="width:25%;padding:2px 4px;">INTITULE</td><td style="padding:2px 4px;">' . $accountName . '</td></tr>';
        $html .= '<tr><td style="padding:2px 4px;">N.COMPTE</td><td style="padding:2px 4px;">' . $accountNumber . '</td></tr>';
        $html .= '<tr><td style="padding:2px 4px;">SWIFT</td><td style="padding:2px 4px;">' . $swift . '</td></tr>';
        $html .= '<tr><td style="padding:2px 4px;">BANQUE</td><td style="padding:2px 4px;">' . $bankName . '</td></tr>';
        $html .= '</table></td>';
      }

      if (count($banks) == 1) {
        $html .= '<td style="width:49%;"></td>';
      }

      $html .= '</tr></table>';
    }

    $html .= '<div style="border:1px solid #000;text-align:center;padding:3px;font-size:6pt;margin-top:2mm;">Thank you for your business!</div></body></html>';

    return $html;
  }

  private function validateInvoiceData($post, $invoiceId = null)
  {
    $errors = [];
    
    if (empty($post['subscriber_id'])) $errors[] = 'Subscriber is required';
    if (empty($post['license_id'])) $errors[] = 'License Number is required';
    if (empty($post['mca_id'])) $errors[] = 'MCA Reference is required';
    if (empty($post['invoice_ref'])) $errors[] = 'Invoice Reference is required';

    if (!empty($post['invoice_ref'])) {
      $invoiceRef = $this->clean($post['invoice_ref']);
      $sql = "SELECT id FROM import_invoices_t WHERE invoice_ref = ?";
      $params = [$invoiceRef];
      
      if ($invoiceId) {
        $sql .= " AND id != ?";
        $params[] = $invoiceId;
      }
      
      $existing = $this->db->customQuery($sql, $params);
      if (!empty($existing)) {
        $errors[] = 'Invoice Reference already exists';
      }
    }

    if (!empty($errors)) {
      return ['success' => false, 'message' => implode(', ', $errors)];
    }

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
      if (is_array($item)) {
        return array_map(function($v) {
          return is_string($v) ? htmlspecialchars($v, ENT_QUOTES | ENT_HTML5, 'UTF-8') : $v;
        }, $item);
      }
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