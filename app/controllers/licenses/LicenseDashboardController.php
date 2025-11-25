<?php

class LicenseDashboardController extends Controller
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  // Load PhpSpreadsheet when needed
  private function loadPhpSpreadsheet()
  {
    // Try multiple possible paths
    $possiblePaths = [
      __DIR__ . '/../../../vendor/autoload.php',  // Root vendor
      __DIR__ . '/../../vendor/autoload.php',      // App vendor
      $_SERVER['DOCUMENT_ROOT'] . '/malabar/vendor/autoload.php', // Absolute path
      dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php', // Alternative root
    ];

    foreach ($possiblePaths as $path) {
      if (file_exists($path)) {
        require_once $path;
        return true;
      }
    }

    return false;
  }

  public function index()
  {
    $data = [
      'title' => 'License Dashboard',
      
      // Overview Tab Data
      'kpi_data' => $this->getKPIData(),
      'status_distribution' => $this->getStatusDistribution(),
      'bank_distribution' => $this->getBankDistribution(),
      'expiry_status' => $this->getExpiryStatus(),
      'monthly_trend' => $this->getMonthlyTrend(),
      'goods_distribution' => $this->getGoodsDistribution(),
      'transport_distribution' => $this->getTransportDistribution(),
      'currency_distribution' => $this->getCurrencyDistribution(),
      'weight_distribution' => $this->getWeightDistribution(),
      'value_weight_scatter' => $this->getValueWeightScatter(),
      'entry_post_distribution' => $this->getEntryPostDistribution(),
      'recent_licenses' => $this->getRecentLicenses(),
      
      // Client Based Tab Data
      'client_stats' => $this->getClientStats(),
      'client_details' => $this->getClientDetails()
    ];

    $this->viewWithLayout('licenses/licensedashboard', $data);
  }

  // ==================== OVERVIEW TAB METHODS ====================

  private function getKPIData()
  {
    try {
      $sql = "SELECT 
                COUNT(*) as total_licenses,
                SUM(CASE WHEN status = 'ACTIVE' THEN 1 ELSE 0 END) as active_licenses,
                SUM(CASE WHEN status = 'INACTIVE' THEN 1 ELSE 0 END) as inactive_licenses,
                SUM(CASE WHEN status = 'ANNULATED' THEN 1 ELSE 0 END) as annulated_licenses,
                SUM(CASE WHEN status = 'MODIFIED' THEN 1 ELSE 0 END) as modified_licenses,
                SUM(CASE WHEN license_expiry_date >= CURDATE() THEN 1 ELSE 0 END) as valid_licenses,
                SUM(CASE WHEN license_expiry_date < CURDATE() THEN 1 ELSE 0 END) as expired_licenses,
                SUM(CASE WHEN license_expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 15 DAY) THEN 1 ELSE 0 END) as expiring_soon_15,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_licenses,
                SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END) as this_month_licenses,
                COALESCE(SUM(fob_declared), 0) as total_fob_value
              FROM licenses_t
              WHERE display = 'Y'";
      
      $result = $this->db->customQuery($sql);
      return $result[0] ?? [];
    } catch (Exception $e) {
      error_log("KPI Data Error: " . $e->getMessage());
      return [];
    }
  }

  private function getStatusDistribution()
  {
    try {
      $sql = "SELECT 
                SUM(CASE WHEN status = 'ACTIVE' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'INACTIVE' THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN status = 'ANNULATED' THEN 1 ELSE 0 END) as annulated,
                SUM(CASE WHEN status = 'MODIFIED' THEN 1 ELSE 0 END) as modified
              FROM licenses_t
              WHERE display = 'Y'";
      
      $result = $this->db->customQuery($sql);
      return $result[0] ?? ['active' => 0, 'inactive' => 0, 'annulated' => 0, 'modified' => 0];
    } catch (Exception $e) {
      error_log("Status Distribution Error: " . $e->getMessage());
      return ['active' => 0, 'inactive' => 0, 'annulated' => 0, 'modified' => 0];
    }
  }

  private function getBankDistribution()
  {
    try {
      $sql = "SELECT 
                COALESCE(b.bank_name, 'Not Specified') as bank_name,
                COUNT(l.id) as license_count
              FROM licenses_t l
              LEFT JOIN banklist_master_t b ON l.bank_id = b.id AND b.display = 'Y'
              WHERE l.display = 'Y'
              GROUP BY l.bank_id, b.bank_name
              HAVING COUNT(l.id) > 0
              ORDER BY license_count DESC
              LIMIT 10";
      
      return $this->db->customQuery($sql) ?: [];
    } catch (Exception $e) {
      error_log("Bank Distribution Error: " . $e->getMessage());
      return [];
    }
  }

  private function getExpiryStatus()
  {
    try {
      $sql = "SELECT 
                COUNT(*) as total_licenses,
                SUM(CASE WHEN license_expiry_date >= CURDATE() THEN 1 ELSE 0 END) as valid,
                SUM(CASE WHEN license_expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as expiring_soon,
                SUM(CASE WHEN license_expiry_date < CURDATE() THEN 1 ELSE 0 END) as expired
              FROM licenses_t
              WHERE display = 'Y'";
      
      $result = $this->db->customQuery($sql);
      return $result[0] ?? ['total_licenses' => 0, 'valid' => 0, 'expiring_soon' => 0, 'expired' => 0];
    } catch (Exception $e) {
      error_log("Expiry Status Error: " . $e->getMessage());
      return ['total_licenses' => 0, 'valid' => 0, 'expiring_soon' => 0, 'expired' => 0];
    }
  }

  private function getMonthlyTrend()
  {
    try {
      $sql = "SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                DATE_FORMAT(created_at, '%b %Y') as month_name,
                COUNT(id) as license_count,
                COALESCE(SUM(fob_declared), 0) as total_fob_value
              FROM licenses_t
              WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                AND display = 'Y'
              GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b %Y')
              ORDER BY month ASC";
      
      return $this->db->customQuery($sql) ?: [];
    } catch (Exception $e) {
      error_log("Monthly Trend Error: " . $e->getMessage());
      return [];
    }
  }

  private function getGoodsDistribution()
  {
    try {
      $sql = "SELECT 
                COALESCE(tg.goods_type, 'Not Specified') as goods_name,
                COUNT(l.id) as license_count
              FROM licenses_t l
              LEFT JOIN type_of_goods_master_t tg ON l.type_of_goods_id = tg.id AND tg.display = 'Y'
              WHERE l.display = 'Y'
              GROUP BY l.type_of_goods_id, tg.goods_type
              HAVING COUNT(l.id) > 0
              ORDER BY license_count DESC
              LIMIT 10";
      
      return $this->db->customQuery($sql) ?: [];
    } catch (Exception $e) {
      error_log("Goods Distribution Error: " . $e->getMessage());
      return [];
    }
  }

  private function getTransportDistribution()
  {
    try {
      $sql = "SELECT 
                COALESCE(tm.transport_mode_name, 'Not Specified') as transport_name,
                COUNT(l.id) as license_count
              FROM licenses_t l
              LEFT JOIN transport_mode_master_t tm ON l.transport_mode_id = tm.id AND tm.display = 'Y'
              WHERE l.display = 'Y'
              GROUP BY l.transport_mode_id, tm.transport_mode_name
              HAVING COUNT(l.id) > 0
              ORDER BY license_count DESC";
      
      return $this->db->customQuery($sql) ?: [];
    } catch (Exception $e) {
      error_log("Transport Distribution Error: " . $e->getMessage());
      return [];
    }
  }

  private function getCurrencyDistribution()
  {
    try {
      $sql = "SELECT 
                COALESCE(cur.currency_name, 'USD') as currency_name,
                COUNT(l.id) as license_count
              FROM licenses_t l
              LEFT JOIN currency_master_t cur ON l.currency_id = cur.id AND cur.display = 'Y'
              WHERE l.display = 'Y'
              GROUP BY l.currency_id, cur.currency_name
              HAVING COUNT(l.id) > 0
              ORDER BY license_count DESC";
      
      return $this->db->customQuery($sql) ?: [];
    } catch (Exception $e) {
      error_log("Currency Distribution Error: " . $e->getMessage());
      return [];
    }
  }

  private function getWeightDistribution()
  {
    try {
      $sql = "SELECT 
                COALESCE(tg.goods_type, 'Not Specified') as goods_name,
                COALESCE(SUM(l.weight), 0) as total_weight
              FROM licenses_t l
              LEFT JOIN type_of_goods_master_t tg ON l.type_of_goods_id = tg.id AND tg.display = 'Y'
              WHERE l.display = 'Y' AND l.weight > 0
              GROUP BY l.type_of_goods_id, tg.goods_type
              HAVING total_weight > 0
              ORDER BY total_weight DESC
              LIMIT 10";
      
      return $this->db->customQuery($sql) ?: [];
    } catch (Exception $e) {
      error_log("Weight Distribution Error: " . $e->getMessage());
      return [];
    }
  }

  private function getValueWeightScatter()
  {
    try {
      $sql = "SELECT 
                weight,
                fob_declared
              FROM licenses_t
              WHERE display = 'Y'
                AND weight > 0
                AND fob_declared > 0
              ORDER BY created_at DESC
              LIMIT 100";
      
      return $this->db->customQuery($sql) ?: [];
    } catch (Exception $e) {
      error_log("Value Weight Scatter Error: " . $e->getMessage());
      return [];
    }
  }

  private function getEntryPostDistribution()
  {
    try {
      $sql = "SELECT 
                COALESCE(tp.transit_point_name, 'Not Specified') as entry_post_name,
                COUNT(l.id) as license_count
              FROM licenses_t l
              LEFT JOIN transit_point_master_t tp ON l.entry_post_id = tp.id 
                AND tp.display = 'Y' 
                AND tp.entry_point = 'Y'
              WHERE l.display = 'Y'
              GROUP BY l.entry_post_id, tp.transit_point_name
              HAVING COUNT(l.id) > 0
              ORDER BY license_count DESC
              LIMIT 10";
      
      return $this->db->customQuery($sql) ?: [];
    } catch (Exception $e) {
      error_log("Entry Post Distribution Error: " . $e->getMessage());
      return [];
    }
  }

  private function getRecentLicenses()
  {
    try {
      $sql = "SELECT 
                l.id,
                l.license_number,
                l.invoice_number,
                l.fob_declared,
                l.weight,
                l.license_applied_date,
                l.license_expiry_date,
                l.status,
                COALESCE(c.company_name, 'N/A') as client_name,
                COALESCE(b.bank_name, 'N/A') as bank_name,
                COALESCE(u.unit_name, 'KG') as unit_name
              FROM licenses_t l
              LEFT JOIN clients_t c ON l.client_id = c.id AND c.display = 'Y'
              LEFT JOIN banklist_master_t b ON l.bank_id = b.id AND b.display = 'Y'
              LEFT JOIN unit_master_t u ON l.unit_of_measurement_id = u.id AND u.display = 'Y'
              WHERE l.display = 'Y'
              ORDER BY l.created_at DESC
              LIMIT 20";
      
      return $this->db->customQuery($sql) ?: [];
    } catch (Exception $e) {
      error_log("Recent Licenses Error: " . $e->getMessage());
      return [];
    }
  }

  // ==================== CLIENT BASED TAB METHODS ====================

  private function getClientStats()
  {
    try {
      $sql = "SELECT 
                COUNT(*) as total_clients,
                SUM(CASE WHEN display = 'Y' THEN 1 ELSE 0 END) as active_clients,
                SUM(CASE WHEN verified_by_id IS NOT NULL THEN 1 ELSE 0 END) as verified_clients,
                (SELECT COUNT(DISTINCT client_id) FROM licenses_t WHERE display = 'Y') as clients_with_licenses
              FROM clients_t";
      
      $result = $this->db->customQuery($sql);
      return $result[0] ?? ['total_clients' => 0, 'active_clients' => 0, 'verified_clients' => 0, 'clients_with_licenses' => 0];
    } catch (Exception $e) {
      error_log("Client Stats Error: " . $e->getMessage());
      return ['total_clients' => 0, 'active_clients' => 0, 'verified_clients' => 0, 'clients_with_licenses' => 0];
    }
  }

  private function getClientDetails()
  {
    try {
      $sql = "SELECT 
                c.id,
                c.short_name,
                c.company_name,
                c.client_type,
                c.display,
                c.contact_person,
                c.email,
                c.payment_term,
                COUNT(l.id) as total_licenses,
                SUM(CASE WHEN l.status = 'ACTIVE' THEN 1 ELSE 0 END) as active_licenses,
                COALESCE(SUM(l.fob_declared), 0) as total_fob_value,
                COALESCE(SUM(l.weight), 0) as total_weight,
                MAX(l.license_applied_date) as last_license_date,
                CASE 
                  WHEN COUNT(l.id) > 0 THEN ROUND((SUM(CASE WHEN l.status = 'ACTIVE' THEN 1 ELSE 0 END) / COUNT(l.id)) * 100, 0)
                  ELSE 0 
                END as success_rate
              FROM clients_t c
              LEFT JOIN licenses_t l ON c.id = l.client_id AND l.display = 'Y'
              WHERE c.display = 'Y'
              GROUP BY c.id, c.short_name, c.company_name, c.client_type, c.display, c.contact_person, c.email, c.payment_term
              HAVING total_licenses > 0
              ORDER BY total_licenses DESC, total_fob_value DESC";
      
      $clients = $this->db->customQuery($sql) ?: [];

      foreach ($clients as &$client) {
        $client_id = $client['id'];
        $client['transport_breakdown'] = $this->getClientTransportBreakdown($client_id);
        $client['goods_breakdown'] = $this->getClientGoodsBreakdown($client_id);
        $client['bank_breakdown'] = $this->getClientBankBreakdown($client_id);
        $client['payment_breakdown'] = $this->getClientPaymentBreakdown($client_id);
      }

      return $clients;
    } catch (Exception $e) {
      error_log("Client Details Error: " . $e->getMessage());
      return [];
    }
  }

  private function getClientTransportBreakdown($client_id)
  {
    try {
      $sql = "SELECT 
                COALESCE(tm.transport_mode_name, 'Not Specified') as transport_name,
                COUNT(l.id) as license_count
              FROM licenses_t l
              LEFT JOIN transport_mode_master_t tm ON l.transport_mode_id = tm.id AND tm.display = 'Y'
              WHERE l.client_id = ? AND l.display = 'Y'
              GROUP BY l.transport_mode_id, tm.transport_mode_name
              HAVING COUNT(l.id) > 0
              ORDER BY license_count DESC";
      
      return $this->db->customQuery($sql, [$client_id]) ?: [];
    } catch (Exception $e) {
      error_log("Client Transport Breakdown Error: " . $e->getMessage());
      return [];
    }
  }

  private function getClientGoodsBreakdown($client_id)
  {
    try {
      $sql = "SELECT 
                COALESCE(tg.goods_type, 'Not Specified') as goods_name,
                COUNT(l.id) as license_count
              FROM licenses_t l
              LEFT JOIN type_of_goods_master_t tg ON l.type_of_goods_id = tg.id AND tg.display = 'Y'
              WHERE l.client_id = ? AND l.display = 'Y'
              GROUP BY l.type_of_goods_id, tg.goods_type
              HAVING COUNT(l.id) > 0
              ORDER BY license_count DESC";
      
      return $this->db->customQuery($sql, [$client_id]) ?: [];
    } catch (Exception $e) {
      error_log("Client Goods Breakdown Error: " . $e->getMessage());
      return [];
    }
  }

  private function getClientBankBreakdown($client_id)
  {
    try {
      $sql = "SELECT 
                COALESCE(b.bank_name, 'Not Specified') as bank_name,
                COUNT(l.id) as license_count
              FROM licenses_t l
              LEFT JOIN banklist_master_t b ON l.bank_id = b.id AND b.display = 'Y'
              WHERE l.client_id = ? AND l.display = 'Y'
              GROUP BY l.bank_id, b.bank_name
              HAVING COUNT(l.id) > 0
              ORDER BY license_count DESC";
      
      return $this->db->customQuery($sql, [$client_id]) ?: [];
    } catch (Exception $e) {
      error_log("Client Bank Breakdown Error: " . $e->getMessage());
      return [];
    }
  }

  private function getClientPaymentBreakdown($client_id)
  {
    try {
      $sql = "SELECT 
                COALESCE(pm.method_name, 'Not Specified') as payment_method,
                COUNT(l.id) as license_count
              FROM licenses_t l
              LEFT JOIN payment_method_master_t pm ON l.payment_method_id = pm.id AND pm.display = 'Y'
              WHERE l.client_id = ? AND l.display = 'Y'
              GROUP BY l.payment_method_id, pm.method_name
              HAVING COUNT(l.id) > 0
              ORDER BY license_count DESC";
      
      return $this->db->customQuery($sql, [$client_id]) ?: [];
    } catch (Exception $e) {
      error_log("Client Payment Breakdown Error: " . $e->getMessage());
      return [];
    }
  }

  // ==================== MODAL DATA METHOD ====================

  public function getModalData()
  {
    header('Content-Type: application/json');
    
    $type = $_POST['type'] ?? '';
    
    try {
      $sql = "";
      
      switch ($type) {
        case 'allLicenses':
          $sql = "SELECT l.license_number, c.company_name as client_name, b.bank_name, l.fob_declared, 
                         DATE_FORMAT(l.license_expiry_date, '%Y-%m-%d') as license_expiry_date, l.status
                  FROM licenses_t l
                  LEFT JOIN clients_t c ON l.client_id = c.id
                  LEFT JOIN banklist_master_t b ON l.bank_id = b.id
                  WHERE l.display = 'Y'
                  ORDER BY l.created_at DESC
                  LIMIT 50";
          break;
          
        case 'activeLicenses':
          $sql = "SELECT l.license_number, c.company_name as client_name, b.bank_name, l.fob_declared, 
                         DATE_FORMAT(l.license_expiry_date, '%Y-%m-%d') as license_expiry_date, l.status
                  FROM licenses_t l
                  LEFT JOIN clients_t c ON l.client_id = c.id
                  LEFT JOIN banklist_master_t b ON l.bank_id = b.id
                  WHERE l.display = 'Y' AND l.status = 'ACTIVE'
                  ORDER BY l.created_at DESC
                  LIMIT 50";
          break;
          
        case 'monthLicenses':
          $sql = "SELECT l.license_number, c.company_name as client_name, b.bank_name, l.fob_declared, 
                         DATE_FORMAT(l.license_expiry_date, '%Y-%m-%d') as license_expiry_date, l.status
                  FROM licenses_t l
                  LEFT JOIN clients_t c ON l.client_id = c.id
                  LEFT JOIN banklist_master_t b ON l.bank_id = b.id
                  WHERE l.display = 'Y' 
                    AND YEAR(l.created_at) = YEAR(CURDATE()) 
                    AND MONTH(l.created_at) = MONTH(CURDATE())
                  ORDER BY l.created_at DESC
                  LIMIT 50";
          break;
          
        case 'todayLicenses':
          $sql = "SELECT l.license_number, c.company_name as client_name, b.bank_name, l.fob_declared, 
                         DATE_FORMAT(l.license_expiry_date, '%Y-%m-%d') as license_expiry_date, l.status
                  FROM licenses_t l
                  LEFT JOIN clients_t c ON l.client_id = c.id
                  LEFT JOIN banklist_master_t b ON l.bank_id = b.id
                  WHERE l.display = 'Y' AND DATE(l.created_at) = CURDATE()
                  ORDER BY l.created_at DESC
                  LIMIT 50";
          break;
          
        case 'validLicenses':
          $sql = "SELECT l.license_number, c.company_name as client_name, b.bank_name, l.fob_declared, 
                         DATE_FORMAT(l.license_expiry_date, '%Y-%m-%d') as license_expiry_date, l.status
                  FROM licenses_t l
                  LEFT JOIN clients_t c ON l.client_id = c.id
                  LEFT JOIN banklist_master_t b ON l.bank_id = b.id
                  WHERE l.display = 'Y' AND l.license_expiry_date >= CURDATE()
                  ORDER BY l.license_expiry_date ASC
                  LIMIT 50";
          break;
          
        case 'expiredLicenses':
          $sql = "SELECT l.license_number, c.company_name as client_name, b.bank_name, l.fob_declared, 
                         DATE_FORMAT(l.license_expiry_date, '%Y-%m-%d') as license_expiry_date, l.status
                  FROM licenses_t l
                  LEFT JOIN clients_t c ON l.client_id = c.id
                  LEFT JOIN banklist_master_t b ON l.bank_id = b.id
                  WHERE l.display = 'Y' AND l.license_expiry_date < CURDATE()
                  ORDER BY l.license_expiry_date DESC
                  LIMIT 50";
          break;
          
        case 'expiringSoon':
          $sql = "SELECT l.license_number, c.company_name as client_name, b.bank_name, l.fob_declared, 
                         DATE_FORMAT(l.license_expiry_date, '%Y-%m-%d') as license_expiry_date, l.status
                  FROM licenses_t l
                  LEFT JOIN clients_t c ON l.client_id = c.id
                  LEFT JOIN banklist_master_t b ON l.bank_id = b.id
                  WHERE l.display = 'Y' 
                    AND l.license_expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 15 DAY)
                  ORDER BY l.license_expiry_date ASC
                  LIMIT 50";
          break;
          
        case 'fobValue':
          $sql = "SELECT l.license_number, c.company_name as client_name, b.bank_name, l.fob_declared, 
                         DATE_FORMAT(l.license_expiry_date, '%Y-%m-%d') as license_expiry_date, l.status
                  FROM licenses_t l
                  LEFT JOIN clients_t c ON l.client_id = c.id
                  LEFT JOIN banklist_master_t b ON l.bank_id = b.id
                  WHERE l.display = 'Y' AND l.fob_declared > 0
                  ORDER BY l.fob_declared DESC
                  LIMIT 50";
          break;
          
        default:
          echo json_encode(['success' => false, 'error' => 'Invalid type']);
          exit;
      }
      
      $data = $this->db->customQuery($sql);
      echo json_encode(['success' => true, 'data' => $data ?: []]);
      
    } catch (Exception $e) {
      error_log("Modal Data Error: " . $e->getMessage());
      echo json_encode(['success' => false, 'error' => 'Data retrieval failed']);
    }
    exit;
  }

  // ==================== EXPORT METHODS WITH PHPSPREADSHEET ====================

  public function exportDashboard()
  {
    try {
      // Load PhpSpreadsheet
      if (!$this->loadPhpSpreadsheet()) {
        die('PhpSpreadsheet library not found. Please install it using: composer require phpoffice/phpspreadsheet');
      }

      if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        die('PhpSpreadsheet class not available after autoload');
      }

      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      
      // Set document properties
      $spreadsheet->getProperties()
        ->setCreator("Malabar Group")
        ->setTitle("License Dashboard Report")
        ->setSubject("Dashboard Export")
        ->setDescription("Comprehensive license dashboard data");
      
      // Title
      $sheet->setCellValue('A1', 'LICENSE DASHBOARD REPORT');
      $sheet->mergeCells('A1:B1');
      $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
      $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      
      // Export date
      $sheet->setCellValue('A2', 'Export Date:');
      $sheet->setCellValue('B2', date('Y-m-d H:i:s'));
      
      // Get KPI Data
      $kpiData = $this->getKPIData();
      
      // Headers
      $row = 4;
      $sheet->setCellValue('A' . $row, 'KPI Metric');
      $sheet->setCellValue('B' . $row, 'Value');
      
      // Style headers
      $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ];
      $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray($headerStyle);
      
      // Data
      $row++;
      $sheet->setCellValue('A' . $row, 'Total Licenses');
      $sheet->setCellValue('B' . $row, $kpiData['total_licenses'] ?? 0);
      
      $row++;
      $sheet->setCellValue('A' . $row, 'Active Licenses');
      $sheet->setCellValue('B' . $row, $kpiData['active_licenses'] ?? 0);
      
      $row++;
      $sheet->setCellValue('A' . $row, 'Valid Licenses');
      $sheet->setCellValue('B' . $row, $kpiData['valid_licenses'] ?? 0);
      
      $row++;
      $sheet->setCellValue('A' . $row, 'Expired Licenses');
      $sheet->setCellValue('B' . $row, $kpiData['expired_licenses'] ?? 0);
      
      $row++;
      $sheet->setCellValue('A' . $row, 'Expiring in 15 Days');
      $sheet->setCellValue('B' . $row, $kpiData['expiring_soon_15'] ?? 0);
      
      $row++;
      $sheet->setCellValue('A' . $row, 'Total FOB Value');
      $sheet->setCellValue('B' . $row, '$' . number_format($kpiData['total_fob_value'] ?? 0, 2));
      
      // Auto-size columns
      $sheet->getColumnDimension('A')->setWidth(30);
      $sheet->getColumnDimension('B')->setWidth(20);
      
      // Generate file
      $filename = 'License_Dashboard_' . date('Y-m-d') . '.xlsx';
      
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '"');
      header('Cache-Control: max-age=0');
      
      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $writer->save('php://output');
      exit;
      
    } catch (Exception $e) {
      error_log("Export Dashboard Error: " . $e->getMessage());
      die('Export failed: ' . $e->getMessage());
    }
  }

  public function exportClientData()
  {
    try {
      // Load PhpSpreadsheet
      if (!$this->loadPhpSpreadsheet()) {
        die('PhpSpreadsheet library not found. Please install it using: composer require phpoffice/phpspreadsheet');
      }

      if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        die('PhpSpreadsheet class not available after autoload');
      }

      $client_id = $_GET['client_id'] ?? 0;
      
      if (!$client_id) {
        die('Client ID required');
      }
      
      // Get client details
      $sql = "SELECT 
                c.id,
                c.short_name,
                c.company_name,
                c.client_type,
                c.display,
                c.contact_person,
                c.email,
                c.payment_term,
                COUNT(l.id) as total_licenses,
                SUM(CASE WHEN l.status = 'ACTIVE' THEN 1 ELSE 0 END) as active_licenses,
                COALESCE(SUM(l.fob_declared), 0) as total_fob_value,
                COALESCE(SUM(l.weight), 0) as total_weight,
                CASE 
                  WHEN COUNT(l.id) > 0 THEN ROUND((SUM(CASE WHEN l.status = 'ACTIVE' THEN 1 ELSE 0 END) / COUNT(l.id)) * 100, 0)
                  ELSE 0 
                END as success_rate
              FROM clients_t c
              LEFT JOIN licenses_t l ON c.id = l.client_id AND l.display = 'Y'
              WHERE c.id = ?
              GROUP BY c.id, c.short_name, c.company_name, c.client_type, c.display, c.contact_person, c.email, c.payment_term";
      
      $result = $this->db->customQuery($sql, [$client_id]);
      
      if (empty($result)) {
        die('Client not found');
      }
      
      $client = $result[0];
      
      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      
      // Title
      $sheet->setCellValue('A1', 'CLIENT REPORT');
      $sheet->mergeCells('A1:B1');
      $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
      $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      
      // Date
      $sheet->setCellValue('A2', 'Generated:');
      $sheet->setCellValue('B2', date('Y-m-d H:i:s'));
      
      // Client Info
      $row = 4;
      $sheet->setCellValue('A' . $row, 'Company Name:');
      $sheet->setCellValue('B' . $row, $client['company_name']);
      
      $row++;
      $sheet->setCellValue('A' . $row, 'Client Code:');
      $sheet->setCellValue('B' . $row, $client['short_name']);
      
      $row++;
      $sheet->setCellValue('A' . $row, 'Total Licenses:');
      $sheet->setCellValue('B' . $row, $client['total_licenses']);
      
      $row++;
      $sheet->setCellValue('A' . $row, 'Active Licenses:');
      $sheet->setCellValue('B' . $row, $client['active_licenses']);
      
      $row++;
      $sheet->setCellValue('A' . $row, 'Total FOB Value:');
      $sheet->setCellValue('B' . $row, '$' . number_format($client['total_fob_value'], 2));
      
      $row++;
      $sheet->setCellValue('A' . $row, 'Total Weight:');
      $sheet->setCellValue('B' . $row, number_format($client['total_weight'], 2) . ' KG');
      
      $row++;
      $sheet->setCellValue('A' . $row, 'Success Rate:');
      $sheet->setCellValue('B' . $row, $client['success_rate'] . '%');
      
      // Auto-size
      $sheet->getColumnDimension('A')->setWidth(25);
      $sheet->getColumnDimension('B')->setWidth(30);
      
      // Generate file
      $filename = 'Client_' . $client['short_name'] . '_Report_' . date('Y-m-d') . '.xlsx';
      
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '"');
      header('Cache-Control: max-age=0');
      
      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $writer->save('php://output');
      exit;
      
    } catch (Exception $e) {
      error_log("Export Client Data Error: " . $e->getMessage());
      die('Export failed: ' . $e->getMessage());
    }
  }
}