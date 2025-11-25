<?php

class QuotationController extends Controller
{
  private $db;

  // Category that uses special columns for IMPORT DEFINITIVE
  private $customsCategoryName = 'CUSTOMS CLEARANCE FEES';
  
  // Kind ID that triggers special columns (IMPORT DEFINITIVE)
  private $importDefinitiveKindId = 1;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function index()
  {
    // Fetch all master data
    $clients = $this->db->selectData('clients_t', 'id, company_name, short_name', ['display' => 'Y'], 'short_name ASC');
    $currencies = $this->db->selectData('currency_master_t', 'id, currency_short_name, currency_name', ['display' => 'Y'], 'currency_short_name ASC');
    $kinds = $this->db->selectData('kind_master_t', 'id, kind_name, kind_short_name', ['display' => 'Y'], 'kind_name ASC');
    $transport_modes = $this->db->selectData('transport_mode_master_t', 'id, transport_mode_name, transport_letter', ['display' => 'Y'], 'transport_mode_name ASC');
    $goods_types = $this->db->selectData('type_of_goods_master_t', 'id, goods_type, goods_short_name', ['display' => 'Y'], 'goods_type ASC');
    
    // Fetch ALL units from unit_master_t table
    $units = $this->db->selectData('unit_master_t', 'id, unit_name, unit_code', ['display' => 'Y'], 'unit_name ASC');
    
    // Fetch categories with their descriptions
    $categories = $this->db->selectData('quotation_categories_t', 'id, category_name, category_header', ['display' => 'Y'], 'display_order ASC');
    
    // Find the Customs Clearance category ID
    $customsCategoryId = null;
    foreach ($categories as $cat) {
      if (stripos($cat['category_name'], 'CUSTOMS') !== false || stripos($cat['category_name'], 'CLEARANCE') !== false) {
        $customsCategoryId = $cat['id'];
        break;
      }
    }
    
    // Fetch all descriptions grouped by category
    $descriptionsRaw = $this->db->customQuery(
      "SELECT qd.*, qc.category_name 
       FROM quotation_descriptions_t qd
       INNER JOIN quotation_categories_t qc ON qd.category_id = qc.id
       WHERE qd.display = 'Y' AND qc.display = 'Y'
       ORDER BY qc.display_order, qd.description_name ASC"
    );
    
    // Group descriptions by category
    $descriptions = [];
    foreach ($descriptionsRaw as $desc) {
      $categoryKey = strtolower(str_replace(' ', '_', $desc['category_name']));
      if (!isset($descriptions[$categoryKey])) {
        $descriptions[$categoryKey] = [];
      }
      $descriptions[$categoryKey][] = [
        'id' => $desc['id'],
        'description_name' => $desc['description_name'],
        'category_id' => $desc['category_id']
      ];
    }
    
    $data = [
      'title' => 'Quotations Management',
      'clients' => $clients ?: [],
      'currencies' => $currencies ?: [],
      'kinds' => $kinds ?: [],
      'transport_modes' => $transport_modes ?: [],
      'goods_types' => $goods_types ?: [],
      'units' => $units ?: [],
      'categories' => $categories ?: [],
      'descriptions' => $descriptions,
      'customsCategoryId' => $customsCategoryId,
      'importDefinitiveKindId' => $this->importDefinitiveKindId
    ];

    $this->viewWithLayout('quotations/quotations', $data);
  }

  public function crudData($action = 'insertion')
  {
    header('Content-Type: application/json');

    try {
      switch ($action) {
        case 'insertion':
          $this->insertQuotation();
          break;
        case 'update':
          $this->updateQuotation();
          break;
        case 'deletion':
          $this->deleteQuotation();
          break;
        case 'getQuotation':
          $this->getQuotation();
          break;
        case 'copyQuotation':
          $this->copyQuotation();
          break;
        case 'listing':
          $this->listQuotations();
          break;
        case 'checkRefUnique':
          $this->checkRefUnique();
          break;
        default:
          echo json_encode(['success' => false, 'message' => 'Invalid action']);
      }
    } catch (Exception $e) {
      error_log("Controller Error: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
    exit;
  }

  private function checkRefUnique()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
      return;
    }

    $quotationRef = $this->clean($_POST['quotation_ref'] ?? '');
    $quotationId = (int) ($_POST['quotation_id'] ?? 0);

    if (empty($quotationRef)) {
      echo json_encode(['unique' => true]);
      return;
    }

    if ($quotationId > 0) {
      $sql = "SELECT id FROM quotations_t WHERE quotation_ref = :ref AND display = 'Y' AND id != :id";
      $result = $this->db->customQuery($sql, [':ref' => $quotationRef, ':id' => $quotationId]);
    } else {
      $result = $this->db->selectData('quotations_t', 'id', ['quotation_ref' => $quotationRef, 'display' => 'Y']);
    }

    if (empty($result)) {
      echo json_encode(['unique' => true]);
    } else {
      echo json_encode(['unique' => false, 'message' => 'This reference already exists. Please change any dropdown to create a different combination.']);
    }
  }

  private function insertQuotation()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
      return;
    }

    $validation = $this->validateQuotationData($_POST);
    if (!$validation['success']) {
      echo json_encode($validation);
      return;
    }

    // Check if at least one category has items
    $categories = $this->db->selectData('quotation_categories_t', 'category_name', ['display' => 'Y']);
    $hasItems = false;
    
    foreach ($categories as $cat) {
      $categoryKey = strtolower(str_replace(' ', '_', $cat['category_name']));
      if (!empty($_POST[$categoryKey]) && is_array($_POST[$categoryKey])) {
        foreach ($_POST[$categoryKey] as $item) {
          if (!empty($item['description_id'])) {
            $hasItems = true;
            break 2;
          }
        }
      }
    }

    if (!$hasItems) {
      echo json_encode(['success' => false, 'message' => 'At least one item is required in any category']);
      return;
    }

    // Check uniqueness of quotation reference
    $existing = $this->db->selectData('quotations_t', 'id', ['quotation_ref' => $_POST['quotation_ref'], 'display' => 'Y']);
    if (!empty($existing)) {
      echo json_encode(['success' => false, 'message' => 'This quotation reference already exists. Please change any dropdown to create a different combination.']);
      return;
    }

    $this->db->beginTransaction();

    try {
      $quotationData = $this->prepareQuotationData($_POST);
      $quotationData['display'] = 'Y';
      $quotationData['created_by'] = $_SESSION['user_id'] ?? 1;
      $quotationData['updated_by'] = $_SESSION['user_id'] ?? 1;

      $quotationId = $this->db->insertData('quotations_t', $quotationData);

      if (!$quotationId) {
        throw new Exception('Failed to insert quotation');
      }

      // Determine if this is ED (EXPORT)
      $kindId = (int) $_POST['kind_id'];
      $kindData = $this->db->selectData('kind_master_t', 'kind_name', ['id' => $kindId]);
      $isED = !empty($kindData) && stripos($kindData[0]['kind_name'], 'EXPORT') !== false;
      
      // Check if IMPORT DEFINITIVE
      $isImportDefinitive = ($kindId === $this->importDefinitiveKindId);

      // Get all categories from database with their IDs
      $dbCategories = $this->db->selectData('quotation_categories_t', '*', ['display' => 'Y'], 'display_order ASC');
      
      // Process items from all categories
      foreach ($dbCategories as $cat) {
        $categoryId = $cat['id'];
        $categoryName = $cat['category_name'];
        $categoryKey = strtolower(str_replace(' ', '_', $categoryName));
        
        // Check if this is the Customs category
        $isCustomsCategory = (stripos($categoryName, 'CUSTOMS') !== false || stripos($categoryName, 'CLEARANCE') !== false);
        
        if (!empty($_POST[$categoryKey]) && is_array($_POST[$categoryKey])) {
          foreach ($_POST[$categoryKey] as $item) {
            if (empty($item['description_id'])) continue;

            $itemData = $this->prepareItemData($item, $quotationId, $categoryId, $isED, $isImportDefinitive, $isCustomsCategory);
            $itemData['created_by'] = $_SESSION['user_id'] ?? 1;
            $itemData['updated_by'] = $_SESSION['user_id'] ?? 1;
            $itemData['display'] = 'Y';

            $itemId = $this->db->insertData('quotation_items_t', $itemData);
            if (!$itemId) {
              throw new Exception('Failed to insert quotation item');
            }
          }
        }
      }

      $this->db->commit();
      echo json_encode(['success' => true, 'message' => 'Quotation created successfully!', 'id' => $quotationId]);

    } catch (Exception $e) {
      $this->db->rollback();
      error_log("Insert Error: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to save quotation: ' . $e->getMessage()]);
    }
  }

  private function updateQuotation()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
      return;
    }

    $quotationId = (int) ($_POST['quotation_id'] ?? 0);
    if ($quotationId <= 0) {
      echo json_encode(['success' => false, 'message' => 'Invalid quotation ID']);
      return;
    }

    $existing = $this->db->selectData('quotations_t', '*', ['id' => $quotationId]);
    if (empty($existing)) {
      echo json_encode(['success' => false, 'message' => 'Quotation not found']);
      return;
    }

    $validation = $this->validateQuotationData($_POST, $quotationId);
    if (!$validation['success']) {
      echo json_encode($validation);
      return;
    }

    // Check if at least one category has items
    $categories = $this->db->selectData('quotation_categories_t', 'category_name', ['display' => 'Y']);
    $hasItems = false;
    
    foreach ($categories as $cat) {
      $categoryKey = strtolower(str_replace(' ', '_', $cat['category_name']));
      if (!empty($_POST[$categoryKey]) && is_array($_POST[$categoryKey])) {
        foreach ($_POST[$categoryKey] as $item) {
          if (!empty($item['description_id'])) {
            $hasItems = true;
            break 2;
          }
        }
      }
    }

    if (!$hasItems) {
      echo json_encode(['success' => false, 'message' => 'At least one item is required in any category']);
      return;
    }

    // Check uniqueness of quotation reference (excluding current record)
    $sql = "SELECT id FROM quotations_t WHERE quotation_ref = :ref AND display = 'Y' AND id != :id";
    $refCheck = $this->db->customQuery($sql, [':ref' => $_POST['quotation_ref'], ':id' => $quotationId]);
    if (!empty($refCheck)) {
      echo json_encode(['success' => false, 'message' => 'This quotation reference already exists. Please change any dropdown to create a different combination.']);
      return;
    }

    $this->db->beginTransaction();

    try {
      $quotationData = $this->prepareQuotationData($_POST);
      $quotationData['updated_by'] = $_SESSION['user_id'] ?? 1;
      $quotationData['updated_at'] = date('Y-m-d H:i:s');

      $success = $this->db->updateData('quotations_t', $quotationData, ['id' => $quotationId]);
      if (!$success) {
        throw new Exception('Failed to update quotation');
      }

      // Delete old items by setting display to 'N'
      $this->db->updateData('quotation_items_t', ['display' => 'N'], ['quotation_id' => $quotationId]);

      // Determine if this is ED
      $kindId = (int) $_POST['kind_id'];
      $kindData = $this->db->selectData('kind_master_t', 'kind_name', ['id' => $kindId]);
      $isED = !empty($kindData) && stripos($kindData[0]['kind_name'], 'EXPORT') !== false;
      
      // Check if IMPORT DEFINITIVE
      $isImportDefinitive = ($kindId === $this->importDefinitiveKindId);

      // Get all categories from database
      $dbCategories = $this->db->selectData('quotation_categories_t', '*', ['display' => 'Y'], 'display_order ASC');
      
      // Insert new items
      foreach ($dbCategories as $cat) {
        $categoryId = $cat['id'];
        $categoryName = $cat['category_name'];
        $categoryKey = strtolower(str_replace(' ', '_', $categoryName));
        
        // Check if this is the Customs category
        $isCustomsCategory = (stripos($categoryName, 'CUSTOMS') !== false || stripos($categoryName, 'CLEARANCE') !== false);
        
        if (!empty($_POST[$categoryKey]) && is_array($_POST[$categoryKey])) {
          foreach ($_POST[$categoryKey] as $item) {
            if (empty($item['description_id'])) continue;

            $itemData = $this->prepareItemData($item, $quotationId, $categoryId, $isED, $isImportDefinitive, $isCustomsCategory);
            $itemData['display'] = 'Y';
            $itemData['created_by'] = $_SESSION['user_id'] ?? 1;
            $itemData['updated_by'] = $_SESSION['user_id'] ?? 1;

            $itemId = $this->db->insertData('quotation_items_t', $itemData);
            if (!$itemId) {
              throw new Exception('Failed to insert quotation item');
            }
          }
        }
      }

      $this->db->commit();
      echo json_encode(['success' => true, 'message' => 'Quotation updated successfully!']);

    } catch (Exception $e) {
      $this->db->rollback();
      error_log("Update Error: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to update quotation: ' . $e->getMessage()]);
    }
  }

  private function deleteQuotation()
  {
    $quotationId = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

    if ($quotationId <= 0) {
      echo json_encode(['success' => false, 'message' => 'Invalid quotation ID']);
      return;
    }

    $quotation = $this->db->selectData('quotations_t', '*', ['id' => $quotationId]);
    if (empty($quotation)) {
      echo json_encode(['success' => false, 'message' => 'Quotation not found']);
      return;
    }

    $this->db->beginTransaction();
    try {
      $this->db->updateData('quotations_t', [
        'display' => 'N', 
        'updated_by' => $_SESSION['user_id'] ?? 1, 
        'updated_at' => date('Y-m-d H:i:s')
      ], ['id' => $quotationId]);
      
      $this->db->updateData('quotation_items_t', ['display' => 'N'], ['quotation_id' => $quotationId]);
      
      $this->db->commit();
      
      echo json_encode(['success' => true, 'message' => 'Quotation deleted successfully']);
    } catch (Exception $e) {
      $this->db->rollback();
      error_log("Delete Error: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to delete quotation']);
    }
  }

  private function getQuotation()
  {
    $quotationId = (int) ($_GET['id'] ?? 0);

    if ($quotationId <= 0) {
      echo json_encode(['success' => false, 'message' => 'Invalid quotation ID']);
      return;
    }

    $quotation = $this->db->selectData('quotations_t', '*', ['id' => $quotationId]);

    if (empty($quotation)) {
      echo json_encode(['success' => false, 'message' => 'Quotation not found']);
      return;
    }

    // Fetch items with category and unit information
    $sql = "SELECT qi.*, qc.category_name, qc.category_header, um.unit_name, um.unit_code
            FROM quotation_items_t qi
            LEFT JOIN quotation_categories_t qc ON qi.category_id = qc.id
            LEFT JOIN unit_master_t um ON qi.unit_id = um.id
            WHERE qi.quotation_id = :quotation_id AND qi.display = 'Y'
            ORDER BY qc.display_order, qi.id ASC";
    
    $items = $this->db->customQuery($sql, [':quotation_id' => $quotationId]);

    // Get all categories from database
    $dbCategories = $this->db->selectData('quotation_categories_t', '*', ['display' => 'Y'], 'display_order ASC');
    
    // Group items by category
    $groupedItems = [];
    foreach ($dbCategories as $cat) {
      $categoryKey = strtolower(str_replace(' ', '_', $cat['category_name']));
      $groupedItems[$categoryKey] = [];
    }

    if (!empty($items)) {
      foreach ($items as $item) {
        $categoryName = $item['category_name'] ?? '';
        $categoryKey = strtolower(str_replace(' ', '_', $categoryName));
        
        if (isset($groupedItems[$categoryKey])) {
          $groupedItems[$categoryKey][] = $item;
        }
      }
    }

    echo json_encode([
      'success' => true,
      'data' => [
        'quotation' => $quotation[0],
        'items' => $groupedItems
      ]
    ]);
  }

  private function copyQuotation()
  {
    $quotationId = (int) ($_GET['id'] ?? 0);

    if ($quotationId <= 0) {
      echo json_encode(['success' => false, 'message' => 'Invalid quotation ID']);
      return;
    }

    $quotation = $this->db->selectData('quotations_t', '*', ['id' => $quotationId]);

    if (empty($quotation)) {
      echo json_encode(['success' => false, 'message' => 'Quotation not found']);
      return;
    }

    // Fetch items with category and unit information
    $sql = "SELECT qi.*, qc.category_name, qc.category_header, um.unit_name, um.unit_code
            FROM quotation_items_t qi
            LEFT JOIN quotation_categories_t qc ON qi.category_id = qc.id
            LEFT JOIN unit_master_t um ON qi.unit_id = um.id
            WHERE qi.quotation_id = :quotation_id AND qi.display = 'Y'
            ORDER BY qc.display_order, qi.id ASC";
    
    $items = $this->db->customQuery($sql, [':quotation_id' => $quotationId]);

    // Get all categories from database
    $dbCategories = $this->db->selectData('quotation_categories_t', '*', ['display' => 'Y'], 'display_order ASC');
    
    // Group items by category
    $groupedItems = [];
    foreach ($dbCategories as $cat) {
      $categoryKey = strtolower(str_replace(' ', '_', $cat['category_name']));
      $groupedItems[$categoryKey] = [];
    }

    if (!empty($items)) {
      foreach ($items as $item) {
        $categoryName = $item['category_name'] ?? '';
        $categoryKey = strtolower(str_replace(' ', '_', $categoryName));
        
        if (isset($groupedItems[$categoryKey])) {
          $groupedItems[$categoryKey][] = $item;
        }
      }
    }

    // Set date to today
    $quotation[0]['quotation_date'] = date('Y-m-d');

    echo json_encode([
      'success' => true,
      'data' => [
        'quotation' => $quotation[0],
        'items' => $groupedItems
      ]
    ]);
  }

  private function listQuotations()
  {
    $sql = "SELECT 
              q.*,
              c.short_name as client_code,
              k.kind_name,
              k.kind_short_name,
              tm.transport_mode_name,
              tm.transport_letter,
              gt.goods_type as goods_type_name,
              gt.goods_short_name
            FROM quotations_t q
            LEFT JOIN clients_t c ON q.client_id = c.id
            LEFT JOIN kind_master_t k ON q.kind_id = k.id
            LEFT JOIN transport_mode_master_t tm ON q.transport_mode_id = tm.id
            LEFT JOIN type_of_goods_master_t gt ON q.goods_type_id = gt.id
            WHERE q.display = 'Y'
            ORDER BY q.id DESC";

    try {
      $quotations = $this->db->customQuery($sql);
      echo json_encode(['success' => true, 'data' => $quotations ?: []]);
    } catch (Exception $e) {
      error_log("List Error: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to load quotations: ' . $e->getMessage()]);
    }
  }

  private function validateQuotationData($data, $quotationId = null)
  {
    $errors = [];

    if (empty(trim($data['quotation_ref'] ?? ''))) {
      $errors[] = 'Quotation reference is required';
    }

    if (empty($data['client_id'])) {
      $errors[] = 'Client is required';
    }

    if (empty($data['quotation_date'])) {
      $errors[] = 'Quotation date is required';
    }

    if (empty($data['kind_id'])) {
      $errors[] = 'Kind is required';
    }

    if (empty($data['transport_mode_id'])) {
      $errors[] = 'Transport mode is required';
    }

    if (empty($data['goods_type_id'])) {
      $errors[] = 'Type of goods is required';
    }

    if (empty($data['arsp'])) {
      $errors[] = 'ARSP status is required';
    }

    if (!empty($errors)) {
      return ['success' => false, 'message' => '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>'];
    }

    return ['success' => true];
  }

  private function prepareQuotationData($post)
  {
    // Calculate totals from items
    $subTotal = 0;
    $vatAmount = 0;
    $subTotalCdf = 0;
    $vatTotalCdf = 0;
    
    // Get all categories
    $categories = $this->db->selectData('quotation_categories_t', '*', ['display' => 'Y']);
    
    // Determine kind type
    $kindId = (int) $post['kind_id'];
    $kindData = $this->db->selectData('kind_master_t', 'kind_name', ['id' => $kindId]);
    $isED = !empty($kindData) && stripos($kindData[0]['kind_name'], 'EXPORT') !== false;
    $isImportDefinitive = ($kindId === $this->importDefinitiveKindId);
    
    foreach ($categories as $cat) {
      $categoryKey = strtolower(str_replace(' ', '_', $cat['category_name']));
      $isCustomsCategory = (stripos($cat['category_name'], 'CUSTOMS') !== false || stripos($cat['category_name'], 'CLEARANCE') !== false);
      
      if (!empty($post[$categoryKey]) && is_array($post[$categoryKey])) {
        foreach ($post[$categoryKey] as $item) {
          if (empty($item['description_id'])) continue;
          
          // For IMPORT DEFINITIVE + Customs category, use CDF fields
          if ($isImportDefinitive && $isCustomsCategory) {
            $rateCdf = floatval($item['rate_cdf'] ?? 0);
            $vatCdf = floatval($item['vat_cdf'] ?? 0);
            $subTotalCdf += $rateCdf;
            $vatTotalCdf += $vatCdf;
          } else {
            // Standard calculation
            $quantity = floatval($item['quantity'] ?? 1);
            
            if ($isED) {
              $rateValue = floatval($item['cost_usd'] ?? 0);
            } else {
              $rateValue = floatval($item['taux_usd'] ?? 0);
            }
            
            $hasTva = isset($item['has_tva']) && $item['has_tva'] === 'YES' ? 1 : 0;
            
            $itemTotal = $rateValue * $quantity;
            $subTotal += $itemTotal;
            if ($hasTva) {
              $vatAmount += $itemTotal * 0.16;
            }
          }
        }
      }
    }
    
    // Calculate ARSP amount (only for USD items)
    $arspAmount = 0;
    $arspStatus = $post['arsp'] ?? 'Disabled';
    if ($arspStatus === 'Enabled') {
      $totalTvaItems = 0;
      foreach ($categories as $cat) {
        $categoryKey = strtolower(str_replace(' ', '_', $cat['category_name']));
        $isCustomsCategory = (stripos($cat['category_name'], 'CUSTOMS') !== false || stripos($cat['category_name'], 'CLEARANCE') !== false);
        
        // Skip CDF items for ARSP calculation
        if ($isImportDefinitive && $isCustomsCategory) continue;
        
        if (!empty($post[$categoryKey]) && is_array($post[$categoryKey])) {
          foreach ($post[$categoryKey] as $item) {
            if (empty($item['description_id'])) continue;
            
            $quantity = floatval($item['quantity'] ?? 1);
            
            if ($isED) {
              $rateValue = floatval($item['cost_usd'] ?? 0);
            } else {
              $rateValue = floatval($item['taux_usd'] ?? 0);
            }
            
            $hasTva = isset($item['has_tva']) && $item['has_tva'] === 'YES' ? 1 : 0;
            
            if ($hasTva) {
              $totalTvaItems += $rateValue * $quantity;
            }
          }
        }
      }
      $arspAmount = $totalTvaItems * 0.012;
    }
    
    $totalAmount = $subTotal + $vatAmount + $arspAmount;
    $totalAmountCdf = $subTotalCdf + $vatTotalCdf;
    
    return [
      'client_id' => $this->toInt($post['client_id'] ?? null),
      'quotation_ref' => $this->clean($post['quotation_ref'] ?? ''),
      'quotation_date' => !empty($post['quotation_date']) ? date('Y-m-d', strtotime($post['quotation_date'])) : null,
      'sub_total' => $this->toDecimal($subTotal),
      'vat_amount' => $this->toDecimal($vatAmount),
      'arsp_amount' => $this->toDecimal($arspAmount),
      'total_amount' => $this->toDecimal($totalAmount),
      'sub_total_cdf' => $this->toDecimal($subTotalCdf),
      'vat_amount_cdf' => $this->toDecimal($vatTotalCdf),
      'total_amount_cdf' => $this->toDecimal($totalAmountCdf),
      'arsp' => $this->clean($post['arsp'] ?? null),
      'kind_id' => $this->toInt($post['kind_id'] ?? null),
      'transport_mode_id' => $this->toInt($post['transport_mode_id'] ?? null),
      'goods_type_id' => $this->toInt($post['goods_type_id'] ?? null)
    ];
  }

  private function prepareItemData($item, $quotationId, $categoryId, $isED = false, $isImportDefinitive = false, $isCustomsCategory = false)
  {
    $descriptionId = $this->toInt($item['description_id'] ?? null);
    $unitId = $this->toInt($item['unit_id'] ?? null);
    
    $data = [
      'quotation_id' => $quotationId,
      'category_id' => $categoryId,
      'description_id' => $descriptionId,
      'unit_id' => $unitId,
      'currency_id' => $this->toInt($item['currency_id'] ?? null),
      'has_tva' => isset($item['has_tva']) && $item['has_tva'] === 'YES' ? 1 : 0,
    ];
    
    // For IMPORT DEFINITIVE + Customs category, use CDF fields
    if ($isImportDefinitive && $isCustomsCategory) {
      $data['quantity'] = $this->toDecimal(1);
      $data['taux_usd'] = '0.00';
      $data['tva_usd'] = '0.00';
      $data['total_usd'] = '0.00';
      $data['cost_usd'] = '0.00';
      $data['subtotal_usd'] = '0.00';
      $data['cif_split'] = $this->toDecimal($item['cif_split'] ?? 0.00);
      $data['percentage'] = $this->toDecimal($item['percentage'] ?? 0.00, 4);
      $data['rate_cdf'] = $this->toDecimal($item['rate_cdf'] ?? 0.00);
      $data['vat_cdf'] = $this->toDecimal($item['vat_cdf'] ?? 0.00);
      $data['total_cdf'] = $this->toDecimal($item['total_cdf'] ?? 0.00);
    } else if ($isED) {
      // EXPORT mode
      $data['quantity'] = $this->toDecimal(1);
      $data['cost_usd'] = $this->toDecimal($item['cost_usd'] ?? 0.00);
      $data['subtotal_usd'] = $this->toDecimal($item['subtotal_usd'] ?? 0.00);
      $data['taux_usd'] = '0.00';
      $data['tva_usd'] = $this->toDecimal($item['tva_usd'] ?? 0.00);
      $data['total_usd'] = $this->toDecimal($item['total_usd'] ?? 0.00);
      $data['cif_split'] = '0.00';
      $data['percentage'] = '0.0000';
      $data['rate_cdf'] = '0.00';
      $data['vat_cdf'] = '0.00';
      $data['total_cdf'] = '0.00';
    } else {
      // Standard IMPORT mode
      $data['quantity'] = $this->toDecimal($item['quantity'] ?? 1.00);
      $data['taux_usd'] = $this->toDecimal($item['taux_usd'] ?? 0.00);
      $data['tva_usd'] = $this->toDecimal($item['tva_usd'] ?? 0.00);
      $data['total_usd'] = $this->toDecimal($item['total_usd'] ?? 0.00);
      $data['cost_usd'] = '0.00';
      $data['subtotal_usd'] = '0.00';
      $data['cif_split'] = '0.00';
      $data['percentage'] = '0.0000';
      $data['rate_cdf'] = '0.00';
      $data['vat_cdf'] = '0.00';
      $data['total_cdf'] = '0.00';
    }
    
    return $data;
  }

  private function clean($value)
  {
    if ($value === null) return null;
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
  }

  private function toInt($value)
  {
    if ($value === '' || $value === null) return null;
    $intValue = (int) $value;
    if ($intValue === 0 && $value !== '0' && $value !== 0) return null;
    return $intValue;
  }

  private function toDecimal($value, $decimals = 2)
  {
    if ($value === '' || $value === null) return '0.' . str_repeat('0', $decimals);
    return number_format((float) $value, $decimals, '.', '');
  }
}