<?php

class LocalDashboardController extends Controller
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function index()
  {
    $data = [
      'title' => 'Local Tracking Dashboard',
      'kpi_data' => $this->getKPIData(),
      'top_locations' => $this->getTopLocations(),
      'location_distribution' => $this->getLocationDistribution(),
      'client_type_distribution' => $this->getClientTypeDistribution(),
      'monthly_trend' => $this->getMonthlyTrend(),
      'horse_performance' => $this->getHorsePerformance(),
      'trailer_performance' => $this->getTrailerPerformance(),
      'top_clients' => $this->getTopClients(),
      'recent_trackings' => $this->getRecentTrackings()
    ];

    $this->viewWithLayout('tracking/localdashboard', $data);
  }

  private function getKPIData()
  {
    $sql = "SELECT 
              COUNT(*) as total_files,
              SUM(CASE WHEN DATE(l.created_at) = CURDATE() THEN 1 ELSE 0 END) as today_files,
              SUM(CASE WHEN YEARWEEK(l.created_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END) as week_files,
              SUM(CASE WHEN YEAR(l.created_at) = YEAR(CURDATE()) AND MONTH(l.created_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END) as month_files,
              SUM(CASE WHEN YEAR(l.created_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as year_files,
              AVG(CASE WHEN l.cee_in IS NOT NULL AND l.cee_out IS NOT NULL 
                  THEN DATEDIFF(l.cee_out, l.cee_in) END) as avg_cee_days,
              COALESCE(SUM(l.capacity_t), 0) as total_capacity,
              COALESCE(SUM(l.bags), 0) as total_bags
            FROM locals_t l
            LEFT JOIN main_office_master_t m ON l.location = m.id
            WHERE l.display = 'Y' AND m.id IN (1, 2, 4)";
    
    $result = $this->db->customQuery($sql);
    return $result[0] ?? [];
  }

  private function getTopLocations()
  {
    // FIXED: JOIN with main_office_master_t to get location name
    $sql = "SELECT 
              COALESCE(m.main_location_name, 'Not Specified') as location_name,
              COUNT(l.id) as file_count
            FROM locals_t l
            LEFT JOIN main_office_master_t m ON l.location = m.id
            WHERE l.display = 'Y' AND m.id IN (1, 2, 4)
            GROUP BY m.main_location_name
            ORDER BY file_count DESC
            LIMIT 3";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getLocationDistribution()
  {
    // FIXED: JOIN with main_office_master_t to get location name
    $sql = "SELECT 
              COALESCE(m.main_location_name, 'Not Specified') as location_name,
              COUNT(l.id) as tracking_count,
              SUM(l.capacity_t) as total_capacity,
              SUM(l.bags) as total_bags
            FROM locals_t l
            LEFT JOIN main_office_master_t m ON l.location = m.id
            WHERE l.display = 'Y' AND m.id IN (1, 2, 4)
            GROUP BY m.main_location_name
            ORDER BY tracking_count DESC
            LIMIT 10";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getClientTypeDistribution()
  {
    $sql = "SELECT 
              CASE 
                WHEN c.client_type LIKE '%I%' AND c.client_type LIKE '%E%' AND c.client_type LIKE '%L%' THEN 'Import+Export+Local'
                WHEN c.client_type LIKE '%I%' AND c.client_type LIKE '%E%' THEN 'Import+Export'
                WHEN c.client_type LIKE '%I%' AND c.client_type LIKE '%L%' THEN 'Import+Local'
                WHEN c.client_type LIKE '%E%' AND c.client_type LIKE '%L%' THEN 'Export+Local'
                WHEN c.client_type = 'I' THEN 'Import Only'
                WHEN c.client_type = 'E' THEN 'Export Only'
                WHEN c.client_type = 'L' THEN 'Local Only'
                ELSE 'Other'
              END as client_category,
              COUNT(l.id) as tracking_count
            FROM locals_t l
            INNER JOIN clients_t c ON l.client_id = c.id
            LEFT JOIN main_office_master_t m ON l.location = m.id
            WHERE l.display = 'Y' AND c.display = 'Y' AND m.id IN (1, 2, 4)
            GROUP BY client_category
            ORDER BY tracking_count DESC";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getMonthlyTrend()
  {
    $sql = "SELECT 
              DATE_FORMAT(l.created_at, '%Y-%m') as month,
              DATE_FORMAT(l.created_at, '%b %Y') as month_name,
              COUNT(l.id) as tracking_count,
              SUM(l.capacity_t) as total_capacity
            FROM locals_t l
            LEFT JOIN main_office_master_t m ON l.location = m.id
            WHERE l.created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) 
              AND l.display = 'Y' AND m.id IN (1, 2, 4)
            GROUP BY month, month_name
            ORDER BY month ASC";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getHorsePerformance()
  {
    $sql = "SELECT 
              COALESCE(l.horse, 'Not Specified') as horse_name,
              COUNT(l.id) as trip_count,
              SUM(l.capacity_t) as total_capacity
            FROM locals_t l
            LEFT JOIN main_office_master_t m ON l.location = m.id
            WHERE l.display = 'Y' AND l.horse IS NOT NULL AND l.horse != '' 
              AND m.id IN (1, 2, 4)
            GROUP BY l.horse
            ORDER BY trip_count DESC
            LIMIT 10";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getTrailerPerformance()
  {
    $sql = "SELECT 
              COALESCE(l.trailer, 'Not Specified') as trailer_name,
              COUNT(l.id) as trip_count,
              SUM(l.capacity_t) as total_capacity
            FROM locals_t l
            LEFT JOIN main_office_master_t m ON l.location = m.id
            WHERE l.display = 'Y' AND l.trailer IS NOT NULL AND l.trailer != '' 
              AND m.id IN (1, 2, 4)
            GROUP BY l.trailer
            ORDER BY trip_count DESC
            LIMIT 10";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getTopClients()
  {
    $sql = "SELECT 
              c.company_name,
              c.short_name,
              COUNT(l.id) as tracking_count,
              SUM(l.capacity_t) as total_capacity
            FROM clients_t c
            INNER JOIN locals_t l ON c.id = l.client_id
            LEFT JOIN main_office_master_t m ON l.location = m.id
            WHERE l.display = 'Y' AND c.display = 'Y' AND m.id IN (1, 2, 4)
            GROUP BY c.id, c.company_name, c.short_name
            ORDER BY tracking_count DESC
            LIMIT 10";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getRecentTrackings()
  {
    // FIXED: JOIN with main_office_master_t to get location name
    $sql = "SELECT 
              l.id,
              l.lt_reference,
              l.location as location_id,
              m.main_location_name as location_name,
              l.pv_transfer_number,
              l.horse,
              l.trailer,
              l.capacity_t,
              l.bags,
              l.cee_in,
              l.cee_out,
              CASE 
                WHEN l.cee_in IS NOT NULL AND l.cee_out IS NOT NULL 
                THEN DATEDIFF(l.cee_out, l.cee_in)
                ELSE NULL 
              END as cee_duration_days,
              l.created_at,
              c.company_name,
              c.short_name
            FROM locals_t l
            LEFT JOIN clients_t c ON l.client_id = c.id
            LEFT JOIN main_office_master_t m ON l.location = m.id
            WHERE l.display = 'Y' AND m.id IN (1, 2, 4)
            ORDER BY l.created_at DESC
            LIMIT 20";
    
    return $this->db->customQuery($sql) ?: [];
  }

  public function exportDashboard()
  {
    try {
      $vendorPath = __DIR__ . '/../../../vendor/autoload.php';
      
      if (!file_exists($vendorPath)) {
        throw new Exception('PhpSpreadsheet not found.');
      }
      
      require_once $vendorPath;
      
      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $spreadsheet->removeSheetByIndex(0);
      
      // Sheet 1: Summary
      $sheet1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Summary');
      $spreadsheet->addSheet($sheet1, 0);
      $kpiData = $this->getKPIData();
      
      $sheet1->setCellValue('A1', 'LOCAL TRACKING DASHBOARD');
      $sheet1->mergeCells('A1:B1');
      $sheet1->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row = 3;
      $sheet1->setCellValue('A' . $row, 'Metric');
      $sheet1->setCellValue('B' . $row, 'Value');
      $sheet1->getStyle('A' . $row . ':B' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '5b69bc']]
      ]);
      
      $row++;
      $metrics = [
        ['Total Files', $kpiData['total_files'] ?? 0],
        ['Today Files', $kpiData['today_files'] ?? 0],
        ['This Week', $kpiData['week_files'] ?? 0],
        ['This Month', $kpiData['month_files'] ?? 0],
        ['This Year', $kpiData['year_files'] ?? 0],
        ['Avg CEE Days', number_format(floatval($kpiData['avg_cee_days'] ?? 0), 1)],
        ['Total Capacity (T)', number_format(floatval($kpiData['total_capacity'] ?? 0), 2)],
        ['Total Bags', number_format(floatval($kpiData['total_bags'] ?? 0))]
      ];
      
      foreach ($metrics as $metric) {
        $sheet1->setCellValue('A' . $row, $metric[0]);
        $sheet1->setCellValue('B' . $row, $metric[1]);
        $row++;
      }
      
      $sheet1->getColumnDimension('A')->setWidth(30);
      $sheet1->getColumnDimension('B')->setWidth(20);
      
      // Sheet 2: Locations
      $sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Locations');
      $spreadsheet->addSheet($sheet2, 1);
      $locationData = $this->getLocationDistribution();
      
      $sheet2->setCellValue('A1', 'LOCATION ANALYSIS');
      $sheet2->mergeCells('A1:D1');
      $sheet2->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']]
      ]);
      
      $row = 3;
      $sheet2->setCellValue('A' . $row, 'Location');
      $sheet2->setCellValue('B' . $row, 'Count');
      $sheet2->setCellValue('C' . $row, 'Capacity (T)');
      $sheet2->setCellValue('D' . $row, 'Bags');
      $sheet2->getStyle('A' . $row . ':D' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '5b69bc']]
      ]);
      
      $row++;
      foreach ($locationData as $loc) {
        $sheet2->setCellValue('A' . $row, $loc['location_name']);
        $sheet2->setCellValue('B' . $row, $loc['tracking_count']);
        $sheet2->setCellValue('C' . $row, number_format(floatval($loc['total_capacity'] ?? 0), 2));
        $sheet2->setCellValue('D' . $row, $loc['total_bags'] ?? 0);
        $row++;
      }
      
      $sheet2->getColumnDimension('A')->setWidth(30);
      $sheet2->getColumnDimension('B')->setWidth(15);
      $sheet2->getColumnDimension('C')->setWidth(20);
      $sheet2->getColumnDimension('D')->setWidth(15);
      
      // Sheet 3: Recent Trackings
      $sheet3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Recent');
      $spreadsheet->addSheet($sheet3, 2);
      $recentData = $this->getRecentTrackings();
      
      $sheet3->setCellValue('A1', 'RECENT TRACKINGS');
      $sheet3->mergeCells('A1:J1');
      $sheet3->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']]
      ]);
      
      $row = 3;
      $headers = ['ID', 'Reference', 'Client', 'Location', 'Horse', 'Trailer', 'Capacity', 'Bags', 'CEE Days', 'Created'];
      $col = 'A';
      foreach ($headers as $header) {
        $sheet3->setCellValue($col . $row, $header);
        $col++;
      }
      $sheet3->getStyle('A' . $row . ':J' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fa5c7c']]
      ]);
      
      $row++;
      foreach ($recentData as $tracking) {
        $sheet3->setCellValue('A' . $row, $tracking['id']);
        $sheet3->setCellValue('B' . $row, $tracking['lt_reference'] ?: 'N/A');
        $sheet3->setCellValue('C' . $row, $tracking['short_name'] ?: 'N/A');
        $sheet3->setCellValue('D' . $row, $tracking['location_name'] ?: 'N/A'); // FIXED: Now uses location_name
        $sheet3->setCellValue('E' . $row, $tracking['horse'] ?: 'N/A');
        $sheet3->setCellValue('F' . $row, $tracking['trailer'] ?: 'N/A');
        $sheet3->setCellValue('G' . $row, number_format(floatval($tracking['capacity_t'] ?? 0), 2));
        $sheet3->setCellValue('H' . $row, $tracking['bags'] ?? 0);
        $sheet3->setCellValue('I' . $row, $tracking['cee_duration_days'] ?: 'N/A');
        $sheet3->setCellValue('J' . $row, date('Y-m-d H:i', strtotime($tracking['created_at'])));
        $row++;
      }
      
      foreach (range('A', 'J') as $col) {
        $sheet3->getColumnDimension($col)->setWidth(15);
      }
      
      $filename = 'Local_Dashboard_' . date('Ymd_His') . '.xlsx';
      $filepath = __DIR__ . '/../../../uploads/' . $filename;
      
      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $writer->save($filepath);
      
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="' . $filename . '"');
      header('Content-Length: ' . filesize($filepath));
      header('Cache-Control: max-age=0');
      
      readfile($filepath);
      unlink($filepath);
      exit;
      
    } catch (Exception $e) {
      error_log('Export error: ' . $e->getMessage());
      header('HTTP/1.1 500 Internal Server Error');
      echo json_encode(['success' => false, 'message' => 'Export failed']);
      exit;
    }
  }
}