<?php

class FicheController extends Controller
{
  private $db;
  private $logFile;
  private $allowedFilters = ['completed', 'in_progress', 'pending'];

  public function __construct()
  {
    $this->db = new Database();
    $this->logFile = __DIR__ . '/../../logs/fiche_operations.log';
    
    $logDir = dirname($this->logFile);
    if (!is_dir($logDir)) {
      mkdir($logDir, 0755, true);
    }
  }

  /**
   * Index page - Display fiche form and list
   */
  public function index()
  {
    // Generate new CSRF token for each page load
    if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time']) || (time() - $_SESSION['csrf_token_time']) > 3600) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      $_SESSION['csrf_token_time'] = time();
    }

    // Load all master data
    $subscribers = $this->db->selectData('clients_t', 'id, short_name', ['display' => 'Y'], 'short_name ASC') ?: [];
    $regimes = $this->db->selectData('regime_master_t', 'id, regime_name', ['display' => 'Y', 'type' => 'I'], 'regime_name ASC') ?: [];
    $currencies = $this->db->selectData('currency_master_t', 'id, currency_name, currency_short_name', ['display' => 'Y'], 'currency_short_name ASC') ?: [];
    $incoterms = $this->db->selectData('incoterm_master_t', 'id, incoterm_short_name, incoterm_full_name', ['display' => 'Y'], 'incoterm_short_name ASC') ?: [];

    $data = [
      'title' => 'Fiche Management',
      'subscribers' => $this->sanitizeArray($subscribers),
      'regimes' => $this->sanitizeArray($regimes),
      'currencies' => $this->sanitizeArray($currencies),
      'incoterms' => $this->sanitizeArray($incoterms),
      'csrf_token' => $_SESSION['csrf_token']
    ];

    $this->viewWithLayout('fiches/fiches', $data);
  }

  /**
   * Sanitize array of data for output
   */
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

  /**
   * CRUD Data Router
   */
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
          $this->insertFiche();
          break;
        case 'update':
          $this->updateFiche();
          break;
        case 'deletion':
          $this->deleteFiche();
          break;
        case 'getFiche':
          $this->getFiche();
          break;
        case 'listing':
          $this->listFiches();
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
        case 'exportFiche':
          $this->exportFiche();
          break;
        case 'getLicenseHsCodes':
          $this->getLicenseHsCodes();
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

  /**
   * Get HS Codes assigned to license with DDI values
   */
  private function getLicenseHsCodes()
  {
    try {
      $licenseId = (int)($_GET['license_id'] ?? 0);

      if ($licenseId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid license ID']);
        return;
      }

      $sql = "SELECT 
                lh.id,
                lh.hscode_id,
                lh.ddi as license_ddi,
                h.hscode_number,
                h.hscode_ddi as master_ddi
              FROM license_hscode_t lh
              LEFT JOIN hscode_master_t h ON lh.hscode_id = h.id
              WHERE lh.license_id = :license_id 
              AND lh.display = 'Y'
              ORDER BY h.hscode_number ASC";

      $hscodes = $this->db->customQuery($sql, [':license_id' => $licenseId]);
      $hscodes = $this->sanitizeArray($hscodes);

      echo json_encode([
        'success' => true,
        'data' => $hscodes ?: []
      ]);

    } catch (Exception $e) {
      $this->logError('Failed to get license HS codes', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to load HS codes']);
    }
  }

  /**
   * Convert amount to USD
   */
  private function convertToUSD($amount, $currencyId, $exchangeRate)
  {
    if (!$amount || !$currencyId) return 0;
    
    // Check if currency is already USD
    $currency = $this->db->selectData('currency_master_t', 'currency_short_name', ['id' => $currencyId]);
    if (!empty($currency) && strtoupper($currency[0]['currency_short_name']) === 'USD') {
      return (float)$amount;
    }
    
    // Convert using exchange rate
    return (float)$amount * (float)$exchangeRate;
  }

  /**
   * Export single fiche to Excel
   */
  private function exportFiche()
  {
    try {
      $ficheId = (int)($_GET['id'] ?? 0);

      if ($ficheId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid fiche ID']);
        return;
      }

      $sql = "SELECT 
                f.*,
                c.short_name as client_name,
                l.license_number,
                i.mca_ref,
                curr.currency_short_name as currency_name,
                rm.regime_name,
                fob_curr.currency_short_name as fob_currency_name,
                fret_curr.currency_short_name as fret_currency_name,
                other_curr.currency_short_name as other_charges_currency_name,
                ins_curr.currency_short_name as insurance_currency_name,
                inc.incoterm_short_name, inc.incoterm_full_name
              FROM fiches_t f
              LEFT JOIN clients_t c ON f.client_id = c.id
              LEFT JOIN licenses_t l ON f.license_id = l.id
              LEFT JOIN imports_t i ON f.mca_reference_id = i.id
              LEFT JOIN currency_master_t curr ON f.currency_id = curr.id
              LEFT JOIN regime_master_t rm ON f.regime_id = rm.id
              LEFT JOIN currency_master_t fob_curr ON f.fob_currency = fob_curr.id
              LEFT JOIN currency_master_t fret_curr ON f.fret_currency = fret_curr.id
              LEFT JOIN currency_master_t other_curr ON f.other_charges_currency = other_curr.id
              LEFT JOIN currency_master_t ins_curr ON f.insurance_currency = ins_curr.id
              LEFT JOIN incoterm_master_t inc ON f.incoterm_id = inc.id
              WHERE f.id = :id AND f.display = 'Y'";

      $fiche = $this->db->customQuery($sql, [':id' => $ficheId]);

      if (empty($fiche)) {
        echo json_encode(['success' => false, 'message' => 'Fiche not found']);
        return;
      }

      $data = $fiche[0];

      // Create filename
      $ficheRef = preg_replace('/[^A-Za-z0-9_-]/', '_', $data['fiche_reference']);
      $filename = htmlspecialchars("{$ficheRef}_Export", ENT_QUOTES, 'UTF-8');

      // Sanitize data
      $data = $this->sanitizeArray([$data])[0];

      // Format helper
      $formatValue = function($value, $type = 'text') {
        if ($value === null || $value === '') return '';
        
        switch ($type) {
          case 'number':
            return number_format((float)$value, 2, '.', '');
          case 'date':
            return date('d/m/Y', strtotime($value));
          default:
            return $value;
        }
      };

      // Check if currency is USD
      $isUSD = strtoupper($data['currency_name']) === 'USD';
      $rateLabel = $isUSD ? 'USD to USD Rate' : 'USD to ' . $data['currency_name'] . ' Rate';
      $cifLabel = $isUSD ? 'CIF (USD)' : 'CIF (' . $data['currency_name'] . ')';

      // HORIZONTAL FORMAT
      $excelData = [];
      
      $headers = [
        'Client', 'License Number', 'MCA Reference', 'Regime', 'Fiche Reference', 'Fiche Date',
        'Currency', 'Transport Mode', 'Weight', 'FOB', 'FOB Currency'
      ];

      // Add conversion fields if NOT USD
      if (!$isUSD) {
        $headers[] = 'FOB (USD)';
      }

      $headers[] = 'Insurance Amount';
      $headers[] = 'Insurance Currency';

      // Add conversion field if NOT USD
      if (!$isUSD) {
        $headers[] = 'Insurance (USD)';
      }

      $headers[] = 'Exchange Rate';
      $headers[] = 'Fret';
      $headers[] = 'Fret Currency';

      // Add conversion field if NOT USD
      if (!$isUSD) {
        $headers[] = 'Fret (USD)';
      }

      $headers[] = 'Other Charges';
      $headers[] = 'Other Charges Currency';

      // Add conversion field if NOT USD
      if (!$isUSD) {
        $headers[] = 'Other Charges (USD)';
      }

      $headers[] = 'Insurance Amount (USD)';
      $headers[] = $rateLabel;
      $headers[] = 'Provence (Origin)';
      $headers[] = $cifLabel;
      $headers[] = 'Coefficient';
      $headers[] = 'INCOTERM Short';
      $headers[] = 'INCOTERM Full';
      $headers[] = 'Status';
      
      $excelData[] = $headers;

      $values = [
        $data['client_name'] ?? '',
        $data['license_number'] ?? '',
        $data['mca_ref'] ?? '',
        $data['regime_name'] ?? '',
        $data['fiche_reference'] ?? '',
        $formatValue($data['fiche_date'] ?? '', 'date'),
        $data['currency_name'] ?? '',
        $data['transport_mode'] ?? '',
        $formatValue($data['poids'] ?? '', 'number'),
        $formatValue($data['fob'] ?? '', 'number'),
        $data['fob_currency_name'] ?? ''
      ];

      // Add FOB converted if NOT USD
      if (!$isUSD) {
        $fobConverted = (float)($data['fob'] ?? 0) * (float)($data['exchange_rate'] ?? 1);
        $values[] = $formatValue($fobConverted, 'number');
      }

      $values[] = $formatValue($data['insurance_amount'] ?? '', 'number');
      $values[] = $data['insurance_currency_name'] ?? '';

      // Add Insurance converted if NOT USD
      if (!$isUSD) {
        $insConverted = (float)($data['insurance_amount'] ?? 0) * (float)($data['exchange_rate'] ?? 1);
        $values[] = $formatValue($insConverted, 'number');
      }

      $values[] = $formatValue($data['exchange_rate'] ?? '', 'number');
      $values[] = $formatValue($data['fret'] ?? '', 'number');
      $values[] = $data['fret_currency_name'] ?? '';

      // Add Fret converted if NOT USD
      if (!$isUSD) {
        $fretConverted = (float)($data['fret'] ?? 0) * (float)($data['exchange_rate'] ?? 1);
        $values[] = $formatValue($fretConverted, 'number');
      }

      $values[] = $formatValue($data['other_charges'] ?? '', 'number');
      $values[] = $data['other_charges_currency_name'] ?? '';

      // Add Other Charges converted if NOT USD
      if (!$isUSD) {
        $otherConverted = (float)($data['other_charges'] ?? 0) * (float)($data['exchange_rate'] ?? 1);
        $values[] = $formatValue($otherConverted, 'number');
      }

      $values[] = $formatValue($data['insurance_amount_usd'] ?? '', 'number');
      $values[] = '1.00';
      $values[] = $data['provence_origin'] ?? '';
      $values[] = $formatValue($data['cif_usd'] ?? '', 'number');
      $values[] = $formatValue($data['coefficient'] ?? '', 'number');
      $values[] = $data['incoterm_short_name'] ?? '';
      $values[] = $data['incoterm_full_name'] ?? '';
      $values[] = $data['status'] ?? '';
      
      $excelData[] = $values;

      echo json_encode([
        'success' => true,
        'filename' => $filename,
        'data' => $excelData
      ]);

    } catch (Exception $e) {
      $this->logError('Failed to export fiche', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to export data']);
    }
  }

  /**
   * Get licenses for subscriber
   */
  private function getLicenses()
  {
    try {
      $subscriberId = (int)($_GET['subscriber_id'] ?? 0);

      if ($subscriberId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid subscriber ID']);
        return;
      }

      $sql = "SELECT l.id, l.license_number 
              FROM licenses_t l
              WHERE l.client_id = :subscriber_id 
              AND l.display = 'Y' 
              AND l.status = 'ACTIVE'
              AND l.kind_id IN (1, 2)
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

  /**
   * Get MCA references for license
   */
  private function getMCAReferences()
  {
    try {
      $licenseId = (int)($_GET['license_id'] ?? 0);

      if ($licenseId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid license ID']);
        return;
      }

      $sql = "SELECT i.id, i.mca_ref, i.regime
              FROM imports_t i
              WHERE i.license_id = :license_id 
              AND i.display = 'Y'
              ORDER BY i.mca_ref ASC";

      $mcaRefs = $this->db->customQuery($sql, [':license_id' => $licenseId]);
      $mcaRefs = $this->sanitizeArray($mcaRefs);

      echo json_encode([
        'success' => true,
        'data' => $mcaRefs ?: []
      ]);

    } catch (Exception $e) {
      $this->logError('Failed to get MCA references', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to load MCA references']);
    }
  }

  /**
   * Get MCA details and populate all fields from imports_t
   */
  private function getMCADetails()
  {
    try {
      $mcaId = (int)($_GET['mca_id'] ?? 0);

      if ($mcaId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid MCA ID']);
        return;
      }

      // Fetch comprehensive MCA details including all financial fields and invoice
      $sql = "SELECT 
                i.id, 
                i.mca_ref, 
                i.regime, 
                i.currency, 
                i.transport_mode,
                i.weight,
                i.fob,
                i.fob_currency,
                i.fret,
                i.fret_currency,
                i.other_charges,
                i.other_charges_currency,
                i.insurance_amount,
                i.insurance_amount_currency,
                i.invoice,
                rm.regime_name,
                curr.currency_short_name,
                curr.id as currency_id,
                tm.transport_mode_name,
                fob_curr.currency_short_name as fob_currency_name,
                fob_curr.id as fob_currency_id,
                fret_curr.currency_short_name as fret_currency_name,
                fret_curr.id as fret_currency_id,
                other_curr.currency_short_name as other_charges_currency_name,
                other_curr.id as other_charges_currency_id,
                ins_curr.currency_short_name as insurance_currency_name,
                ins_curr.id as insurance_currency_id
              FROM imports_t i
              LEFT JOIN regime_master_t rm ON i.regime = rm.id
              LEFT JOIN currency_master_t curr ON i.currency = curr.id
              LEFT JOIN transport_mode_master_t tm ON i.transport_mode = tm.id
              LEFT JOIN currency_master_t fob_curr ON i.fob_currency = fob_curr.id
              LEFT JOIN currency_master_t fret_curr ON i.fret_currency = fret_curr.id
              LEFT JOIN currency_master_t other_curr ON i.other_charges_currency = other_curr.id
              LEFT JOIN currency_master_t ins_curr ON i.insurance_amount_currency = ins_curr.id
              WHERE i.id = :mca_id AND i.display = 'Y'";

      $mca = $this->db->customQuery($sql, [':mca_id' => $mcaId]);

      if (empty($mca)) {
        echo json_encode(['success' => false, 'message' => 'MCA not found']);
        return;
      }

      $mcaData = $this->sanitizeArray([$mca[0]])[0];
      
      // Generate Fiche Reference based on MCA Reference
      $ficheReference = $this->generateFicheReferenceFromMCA($mcaData['mca_ref']);
      $mcaData['fiche_reference'] = $ficheReference;

      // Check if currency is USD
      $mcaData['is_usd'] = strtoupper($mcaData['currency_short_name']) === 'USD';

      // Calculate coefficient (CIF / FOB) from MCA data - 2 decimal places
      $fob = (float)($mcaData['fob'] ?? 0);
      $fret = (float)($mcaData['fret'] ?? 0);
      $insurance = (float)($mcaData['insurance_amount'] ?? 0);
      $otherCharges = (float)($mcaData['other_charges'] ?? 0);
      
      if ($fob > 0) {
        $cif = $fob + $fret + $insurance + $otherCharges;
        $coefficient = $cif / $fob;
        $mcaData['coefficient'] = round($coefficient, 2);
      } else {
        $mcaData['coefficient'] = 1.00;
      }

      echo json_encode([
        'success' => true,
        'data' => $mcaData
      ]);

    } catch (Exception $e) {
      $this->logError('Failed to get MCA details', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to load MCA details']);
    }
  }

  /**
   * Generate Fiche Reference from MCA Reference
   * Format: FICHE-{MCA_REF}
   */
  private function generateFicheReferenceFromMCA($mcaRef)
  {
    if (empty($mcaRef)) {
      return '';
    }
    
    // Clean the MCA reference
    $cleanMcaRef = strtoupper(trim($mcaRef));
    
    // Generate base reference
    $baseRef = "FICHE-{$cleanMcaRef}";
    
    // Check if this reference already exists
    $sql = "SELECT fiche_reference 
            FROM fiches_t 
            WHERE fiche_reference LIKE :prefix 
            AND display = 'Y'
            ORDER BY fiche_reference DESC";
    
    $result = $this->db->customQuery($sql, [':prefix' => $baseRef . '%']);
    
    // If no duplicates, return base reference
    if (empty($result)) {
      return $baseRef;
    }
    
    // If duplicates exist, add a sequence number
    $sequence = 1;
    foreach ($result as $row) {
      if (preg_match('/-(\d+)$/', $row['fiche_reference'], $matches)) {
        $lastSequence = (int)$matches[1];
        if ($lastSequence >= $sequence) {
          $sequence = $lastSequence + 1;
        }
      }
    }
    
    return "{$baseRef}-{$sequence}";
  }

  /**
   * Validate CSRF Token
   */
  private function validateCsrfToken()
  {
    $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
    
    if (empty($token) || empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time'])) {
      $this->logError('CSRF token missing or expired', [
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
      ]);
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
      $this->logError('CSRF token validation failed', [
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
      ]);
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'Invalid security token. Please refresh the page.']);
      exit;
    }
  }

  /**
   * Log errors
   */
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

  /**
   * Log info
   */
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

  /**
   * Get statistics
   */
  private function getStatistics()
  {
    try {
      $sql = "SELECT 
                COUNT(*) as total_fiches,
                COALESCE(SUM(poids), 0) as total_weight,
                COALESCE(SUM(cif_usd), 0) as total_cif
              FROM fiches_t
              WHERE display = 'Y'";

      $stats = $this->db->customQuery($sql);

      $statusSql = "SELECT 
                      f.status, 
                      COUNT(f.id) as count
                    FROM fiches_t f
                    WHERE f.display = 'Y'
                    GROUP BY f.status";

      $statusCounts = $this->db->customQuery($statusSql);

      $statusData = [];
      foreach ($statusCounts as $status) {
        $statusKey = strtolower(str_replace(' ', '_', $status['status'] ?? 'unknown'));
        $statusData[$statusKey] = (int)$status['count'];
      }

      if (!empty($stats)) {
        echo json_encode([
          'success' => true,
          'data' => [
            'total_fiches' => (int)$stats[0]['total_fiches'],
            'total_weight' => number_format((float)$stats[0]['total_weight'], 2, '.', ''),
            'total_cif' => number_format((float)$stats[0]['total_cif'], 2, '.', ''),
            'completed' => $statusData['completed'] ?? 0,
            'in_progress' => $statusData['in_progress'] ?? 0,
            'pending' => $statusData['pending'] ?? 0
          ]
        ]);
      }
    } catch (Exception $e) {
      $this->logError('Failed to get statistics', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to load statistics']);
    }
  }

  /**
   * Insert new fiche
   */
  private function insertFiche()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
      return;
    }

    $this->validateCsrfToken();

    try {
      $validation = $this->validateFicheData($_POST);
      if (!$validation['success']) {
        echo json_encode($validation);
        return;
      }

      $data = $this->prepareFicheData($_POST);
      $data['created_by'] = (int)($_SESSION['user_id'] ?? 1);
      $data['updated_by'] = (int)($_SESSION['user_id'] ?? 1);
      $data['display'] = 'Y';

      $insertId = $this->db->insertData('fiches_t', $data);

      if ($insertId) {
        $this->logInfo('Fiche created successfully', ['fiche_id' => $insertId]);
        echo json_encode(['success' => true, 'message' => 'Fiche created successfully!', 'id' => $insertId]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save fiche.']);
      }
    } catch (Exception $e) {
      $this->logError('Exception during fiche insert', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'An error occurred while saving.']);
    }
  }

  /**
   * Update existing fiche
   */
  private function updateFiche()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
      return;
    }

    $this->validateCsrfToken();

    try {
      $ficheId = (int)($_POST['fiche_id'] ?? 0);
      if ($ficheId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid fiche ID']);
        return;
      }

      $existing = $this->db->selectData('fiches_t', '*', ['id' => $ficheId, 'display' => 'Y']);
      if (empty($existing)) {
        echo json_encode(['success' => false, 'message' => 'Fiche not found']);
        return;
      }

      $validation = $this->validateFicheData($_POST, $ficheId);
      if (!$validation['success']) {
        echo json_encode($validation);
        return;
      }

      $data = $this->prepareFicheData($_POST);
      $data['updated_by'] = (int)($_SESSION['user_id'] ?? 1);
      $data['updated_at'] = date('Y-m-d H:i:s');

      $success = $this->db->updateData('fiches_t', $data, ['id' => $ficheId]);

      if ($success) {
        $this->logInfo('Fiche updated successfully', ['fiche_id' => $ficheId]);
        echo json_encode(['success' => true, 'message' => 'Fiche updated successfully!']);
      } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update fiche.']);
      }
    } catch (Exception $e) {
      $this->logError('Exception during fiche update', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'An error occurred while updating.']);
    }
  }

  /**
   * Delete fiche (soft delete)
   */
  private function deleteFiche()
  {
    $this->validateCsrfToken();

    try {
      $ficheId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

      if ($ficheId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid fiche ID']);
        return;
      }

      $fiche = $this->db->selectData('fiches_t', '*', ['id' => $ficheId, 'display' => 'Y']);
      if (empty($fiche)) {
        echo json_encode(['success' => false, 'message' => 'Fiche not found']);
        return;
      }

      $success = $this->db->updateData('fiches_t', [
        'display' => 'N',
        'updated_by' => (int)($_SESSION['user_id'] ?? 1),
        'updated_at' => date('Y-m-d H:i:s')
      ], ['id' => $ficheId]);

      if ($success) {
        $this->logInfo('Fiche deleted successfully', ['fiche_id' => $ficheId]);
        echo json_encode(['success' => true, 'message' => 'Fiche deleted successfully!']);
      } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete fiche']);
      }
    } catch (Exception $e) {
      $this->logError('Exception during fiche delete', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'An error occurred while deleting.']);
    }
  }

  /**
   * Get single fiche for editing
   */
  private function getFiche()
  {
    try {
      $ficheId = (int)($_GET['id'] ?? 0);

      if ($ficheId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid fiche ID']);
        return;
      }

      $sql = "SELECT f.*, 
                c.short_name as client_name, 
                l.license_number,
                i.mca_ref,
                rm.regime_name,
                curr.currency_short_name
              FROM fiches_t f
              LEFT JOIN clients_t c ON f.client_id = c.id
              LEFT JOIN licenses_t l ON f.license_id = l.id
              LEFT JOIN imports_t i ON f.mca_reference_id = i.id
              LEFT JOIN regime_master_t rm ON f.regime_id = rm.id
              LEFT JOIN currency_master_t curr ON f.currency_id = curr.id
              WHERE f.id = :id AND f.display = 'Y'";

      $fiche = $this->db->customQuery($sql, [':id' => $ficheId]);

      if (!empty($fiche)) {
        $fiche = $this->sanitizeArray($fiche);
        
        // Check if currency is USD
        $fiche[0]['is_usd'] = strtoupper($fiche[0]['currency_short_name']) === 'USD';
        
        echo json_encode(['success' => true, 'data' => $fiche[0]]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Fiche not found']);
      }
    } catch (Exception $e) {
      $this->logError('Exception while fetching fiche', ['error' => $e->getMessage()]);
      echo json_encode(['success' => false, 'message' => 'Failed to load fiche data']);
    }
  }

  /**
   * List all fiches for DataTable
   */
  private function listFiches()
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
      
      $columns = ['f.id', 'f.fiche_reference', 'c.short_name', 'i.mca_ref', 'f.fiche_date', 'f.poids', 'f.cif_usd', 'f.status'];
      $orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'f.id';

      $baseQuery = "FROM fiches_t f
                    LEFT JOIN clients_t c ON f.client_id = c.id
                    LEFT JOIN licenses_t l ON f.license_id = l.id
                    LEFT JOIN imports_t i ON f.mca_reference_id = i.id
                    LEFT JOIN regime_master_t rm ON f.regime_id = rm.id
                    LEFT JOIN currency_master_t curr ON f.currency_id = curr.id
                    WHERE f.display = 'Y'";

      $searchCondition = "";
      $filterCondition = "";
      $params = [];
      
      if (!empty($searchValue)) {
        $searchCondition = " AND (
          f.fiche_reference LIKE :search OR
          c.short_name LIKE :search OR
          i.mca_ref LIKE :search OR
          f.status LIKE :search
        )";
        $params[':search'] = "%{$searchValue}%";
      }

      if (!empty($filters)) {
        $filterClauses = [];
        
        foreach ($filters as $filter) {
          switch ($filter) {
            case 'completed':
              $filterClauses[] = "f.status = :status_completed";
              $params[':status_completed'] = 'COMPLETED';
              break;
            case 'in_progress':
              $filterClauses[] = "f.status = :status_in_progress";
              $params[':status_in_progress'] = 'IN PROGRESS';
              break;
            case 'pending':
              $filterClauses[] = "f.status = :status_pending";
              $params[':status_pending'] = 'PENDING';
              break;
          }
        }
        
        if (!empty($filterClauses)) {
          $filterCondition = " AND (" . implode(' OR ', $filterClauses) . ")";
        }
      }

      $totalSql = "SELECT COUNT(*) as total FROM fiches_t WHERE display = 'Y'";
      $totalResult = $this->db->customQuery($totalSql);
      $totalRecords = (int)($totalResult[0]['total'] ?? 0);

      $filteredSql = "SELECT COUNT(*) as total {$baseQuery} {$searchCondition} {$filterCondition}";
      $filteredResult = $this->db->customQuery($filteredSql, $params);
      $filteredRecords = (int)($filteredResult[0]['total'] ?? 0);

      $dataSql = "SELECT 
                    f.id, f.fiche_reference, f.fiche_date, f.poids, f.cif_usd, f.status,
                    c.short_name as client_name,
                    l.license_number,
                    i.mca_ref,
                    rm.regime_name,
                    curr.currency_short_name as currency_name
                  {$baseQuery}
                  {$searchCondition}
                  {$filterCondition}
                  ORDER BY {$orderColumn} {$orderDirection}
                  LIMIT :limit OFFSET :offset";

      $params[':limit'] = $length;
      $params[':offset'] = $start;

      $fiches = $this->db->customQuery($dataSql, $params);
      $fiches = $this->sanitizeArray($fiches);

      echo json_encode([
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $fiches ?: []
      ]);

    } catch (Exception $e) {
      $this->logError('Exception in listFiches', ['error' => $e->getMessage()]);
      echo json_encode([
        'draw' => $_GET['draw'] ?? 1,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => []
      ]);
    }
  }

  /**
   * Validate fiche data
   */
  private function validateFicheData($post, $ficheId = null)
  {
    $errors = [];

    $requiredFields = [
      'client_id' => 'Client',
      'license_id' => 'License Number',
      'mca_reference_id' => 'MCA Reference',
      'regime_id' => 'Regime',
      'fiche_date' => 'Fiche Date',
      'poids' => 'Weight',
      'fob' => 'FOB',
      'exchange_rate' => 'Exchange Rate',
      'status' => 'Status'
    ];

    foreach ($requiredFields as $field => $label) {
      if (empty($post[$field])) {
        $errors[] = htmlspecialchars("{$label} is required", ENT_QUOTES, 'UTF-8');
      }
    }

    if (empty($post['fiche_reference'])) {
      $errors[] = 'Fiche Reference is required';
    } else {
      $ficheRef = $this->sanitizeInput(trim($post['fiche_reference']));
      
      $sql = "SELECT id FROM fiches_t WHERE fiche_reference = :fiche_reference AND display = 'Y'";
      $params = [':fiche_reference' => $ficheRef];
      
      if ($ficheId) {
        $sql .= " AND id != :fiche_id";
        $params[':fiche_id'] = $ficheId;
      }
      
      $exists = $this->db->customQuery($sql, $params);
      if ($exists) {
        $errors[] = 'Fiche Reference already exists';
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

  /**
   * Prepare fiche data for database
   */
  private function prepareFicheData($post)
  {
    $data = [
      'client_id' => !empty($post['client_id']) ? $this->toInt($post['client_id']) : null,
      'license_id' => !empty($post['license_id']) ? $this->toInt($post['license_id']) : null,
      'mca_reference_id' => !empty($post['mca_reference_id']) ? $this->toInt($post['mca_reference_id']) : null,
      'regime_id' => !empty($post['regime_id']) ? $this->toInt($post['regime_id']) : null,
      'fiche_reference' => !empty($post['fiche_reference']) ? $this->clean($post['fiche_reference']) : null,
      'fiche_date' => !empty($post['fiche_date']) && $this->isValidDate($post['fiche_date']) ? $post['fiche_date'] : null,
      'currency_id' => !empty($post['currency_id']) ? $this->toInt($post['currency_id']) : null,
      'transport_mode' => !empty($post['transport_mode']) ? $this->clean($post['transport_mode']) : null,
      'poids' => !empty($post['poids']) && is_numeric($post['poids']) ? round((float)$post['poids'], 2) : null,
      'fob' => !empty($post['fob']) && is_numeric($post['fob']) ? round((float)$post['fob'], 2) : null,
      'fob_currency' => !empty($post['fob_currency']) ? $this->toInt($post['fob_currency']) : null,
      'insurance_amount' => !empty($post['insurance_amount']) && is_numeric($post['insurance_amount']) ? round((float)$post['insurance_amount'], 2) : null,
      'insurance_currency' => !empty($post['insurance_currency']) ? $this->toInt($post['insurance_currency']) : null,
      'exchange_rate' => !empty($post['exchange_rate']) && is_numeric($post['exchange_rate']) ? round((float)$post['exchange_rate'], 6) : null,
      'fret' => !empty($post['fret']) && is_numeric($post['fret']) ? round((float)$post['fret'], 2) : null,
      'fret_currency' => !empty($post['fret_currency']) ? $this->toInt($post['fret_currency']) : null,
      'other_charges' => !empty($post['other_charges']) && is_numeric($post['other_charges']) ? round((float)$post['other_charges'], 2) : 0,
      'other_charges_currency' => !empty($post['other_charges_currency']) ? $this->toInt($post['other_charges_currency']) : null,
      'insurance_amount_usd' => !empty($post['insurance_amount_usd']) && is_numeric($post['insurance_amount_usd']) ? round((float)$post['insurance_amount_usd'], 2) : null,
      'provence_origin' => !empty($post['provence_origin']) ? $this->clean($post['provence_origin']) : null,
      'cif_usd' => !empty($post['cif_usd']) && is_numeric($post['cif_usd']) ? round((float)$post['cif_usd'], 2) : null,
      'coefficient' => !empty($post['coefficient']) && is_numeric($post['coefficient']) ? round((float)$post['coefficient'], 2) : 1.00,
      'incoterm_id' => !empty($post['incoterm_id']) ? $this->toInt($post['incoterm_id']) : null,
      'status' => !empty($post['status']) ? $this->clean($post['status']) : 'PENDING'
    ];

    return $data;
  }

  /**
   * Sanitize input
   */
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

  /**
   * Clean function
   */
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
}