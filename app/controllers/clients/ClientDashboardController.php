<?php

class ClientDashboardController extends Controller
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function index()
  {
    // Get dashboard data
    $data = [
      'title' => 'Client Dashboard',
      'kpi_data' => $this->getKPIData(),
      'client_type_distribution' => $this->getClientTypeDistribution(),
      'location_distribution' => $this->getLocationDistribution(),
      'industry_distribution' => $this->getIndustryDistribution(),
      'phase_distribution' => $this->getPhaseDistribution(),
      'payment_term_distribution' => $this->getPaymentTermDistribution(),
      'monthly_registration_trend' => $this->getMonthlyRegistrationTrend(),
      'group_company_distribution' => $this->getGroupCompanyDistribution(),
      'contract_status' => $this->getContractStatus(),
      'document_completion_status' => $this->getDocumentCompletionStatus(),
      'verification_status' => $this->getVerificationStatus(),
      'recent_clients' => $this->getRecentClients()
    ];

    $this->viewWithLayout('clients/clientdashboard', $data);
  }

  private function getKPIData()
  {
    $sql = "SELECT 
              COUNT(*) as total_clients,
              SUM(CASE WHEN display = 'Y' THEN 1 ELSE 0 END) as active_clients,
              SUM(CASE WHEN display = 'N' THEN 1 ELSE 0 END) as inactive_clients,
              SUM(CASE WHEN client_type LIKE '%I%' THEN 1 ELSE 0 END) as import_clients,
              SUM(CASE WHEN client_type LIKE '%E%' THEN 1 ELSE 0 END) as export_clients,
              SUM(CASE WHEN client_type LIKE '%L%' THEN 1 ELSE 0 END) as local_clients,
              SUM(CASE WHEN verified_by_id IS NOT NULL THEN 1 ELSE 0 END) as verified_clients,
              SUM(CASE WHEN approved_by_id IS NOT NULL THEN 1 ELSE 0 END) as approved_clients,
              SUM(CASE WHEN contract_validity >= CURDATE() THEN 1 ELSE 0 END) as valid_contracts,
              SUM(CASE WHEN contract_validity < CURDATE() THEN 1 ELSE 0 END) as expired_contracts,
              SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_registrations,
              SUM(CASE WHEN YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END) as this_week_registrations,
              SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END) as this_month_registrations
            FROM clients_t";
    
    $result = $this->db->customQuery($sql);
    return $result[0] ?? [];
  }

  private function getClientTypeDistribution()
  {
    $sql = "SELECT 
              SUM(CASE WHEN client_type = 'I' THEN 1 ELSE 0 END) as import_only,
              SUM(CASE WHEN client_type = 'E' THEN 1 ELSE 0 END) as export_only,
              SUM(CASE WHEN client_type = 'L' THEN 1 ELSE 0 END) as local_only,
              SUM(CASE WHEN client_type = 'IE' OR client_type = 'EI' THEN 1 ELSE 0 END) as import_export,
              SUM(CASE WHEN client_type = 'IL' OR client_type = 'LI' THEN 1 ELSE 0 END) as import_local,
              SUM(CASE WHEN client_type = 'EL' OR client_type = 'LE' THEN 1 ELSE 0 END) as export_local,
              SUM(CASE WHEN client_type IN ('IEL','ILE','EIL','ELI','LIE','LEI') THEN 1 ELSE 0 END) as all_three
            FROM clients_t
            WHERE display = 'Y'";
    
    $result = $this->db->customQuery($sql);
    return $result[0] ?? [];
  }

  private function getLocationDistribution()
  {
    $sql = "SELECT 
              COALESCE(t.transit_point_name, 'Not Specified') as location_name,
              COUNT(c.id) as client_count
            FROM clients_t c
            LEFT JOIN transit_point_master_t t ON c.office_location_id = t.id
            WHERE c.display = 'Y'
            GROUP BY c.office_location_id, t.transit_point_name
            ORDER BY client_count DESC";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getIndustryDistribution()
  {
    $sql = "SELECT 
              COALESCE(i.industry_name, 'Not Specified') as industry_name,
              COUNT(c.id) as client_count
            FROM clients_t c
            LEFT JOIN industry_master_t i ON c.industry_type_id = i.id
            WHERE c.display = 'Y'
            GROUP BY c.industry_type_id, i.industry_name
            ORDER BY client_count DESC
            LIMIT 10";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getPhaseDistribution()
  {
    $sql = "SELECT 
              COALESCE(CONCAT(p.phase_code, ' - ', p.phase_name), 'Not Specified') as phase_name,
              COUNT(c.id) as client_count
            FROM clients_t c
            LEFT JOIN phase_master_t p ON c.phase_id = p.id
            WHERE c.display = 'Y'
            GROUP BY c.phase_id, p.phase_code, p.phase_name
            ORDER BY client_count DESC";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getPaymentTermDistribution()
  {
    $sql = "SELECT 
              COALESCE(payment_term, 'Not Specified') as payment_term,
              COUNT(id) as client_count
            FROM clients_t
            WHERE display = 'Y'
            GROUP BY payment_term
            ORDER BY client_count DESC";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getMonthlyRegistrationTrend()
  {
    $sql = "SELECT 
              DATE_FORMAT(created_at, '%Y-%m') as month,
              DATE_FORMAT(created_at, '%b %Y') as month_name,
              COUNT(id) as client_count
            FROM clients_t
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b %Y')
            ORDER BY month ASC";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getGroupCompanyDistribution()
  {
    $sql = "SELECT 
              COALESCE(g.group_company_name, 'Independent') as group_name,
              COUNT(c.id) as client_count
            FROM clients_t c
            LEFT JOIN group_company_master_t g ON c.group_company_id = g.id
            WHERE c.display = 'Y'
            GROUP BY c.group_company_id, g.group_company_name
            ORDER BY client_count DESC
            LIMIT 10";
    
    return $this->db->customQuery($sql) ?: [];
  }

  private function getContractStatus()
  {
    $sql = "SELECT 
              SUM(CASE WHEN contract_start_date IS NULL THEN 1 ELSE 0 END) as no_contract,
              SUM(CASE WHEN contract_validity >= CURDATE() THEN 1 ELSE 0 END) as valid_contract,
              SUM(CASE WHEN contract_validity < CURDATE() AND contract_validity IS NOT NULL THEN 1 ELSE 0 END) as expired_contract,
              SUM(CASE WHEN contract_validity BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as expiring_soon
            FROM clients_t
            WHERE display = 'Y'";
    
    $result = $this->db->customQuery($sql);
    return $result[0] ?? [];
  }

  private function getDocumentCompletionStatus()
  {
    $sql = "SELECT 
              COUNT(*) as total_clients,
              SUM(CASE WHEN id_nat_file IS NOT NULL THEN 1 ELSE 0 END) as has_id_nat,
              SUM(CASE WHEN rccm_file IS NOT NULL THEN 1 ELSE 0 END) as has_rccm,
              SUM(CASE WHEN import_export_file IS NOT NULL THEN 1 ELSE 0 END) as has_import_export,
              SUM(CASE WHEN attestation_file IS NOT NULL THEN 1 ELSE 0 END) as has_attestation,
              SUM(CASE WHEN id_nat_file IS NOT NULL AND rccm_file IS NOT NULL AND import_export_file IS NOT NULL AND attestation_file IS NOT NULL THEN 1 ELSE 0 END) as complete_docs
            FROM clients_t
            WHERE display = 'Y'";
    
    $result = $this->db->customQuery($sql);
    return $result[0] ?? [];
  }

  private function getVerificationStatus()
  {
    $sql = "SELECT 
              COUNT(*) as total_clients,
              SUM(CASE WHEN verified_by_id IS NOT NULL AND approved_by_id IS NOT NULL THEN 1 ELSE 0 END) as verified_and_approved,
              SUM(CASE WHEN verified_by_id IS NOT NULL AND approved_by_id IS NULL THEN 1 ELSE 0 END) as verified_only,
              SUM(CASE WHEN verified_by_id IS NULL AND approved_by_id IS NULL THEN 1 ELSE 0 END) as not_verified
            FROM clients_t
            WHERE display = 'Y'";
    
    $result = $this->db->customQuery($sql);
    return $result[0] ?? [];
  }

  private function getRecentClients()
  {
    $sql = "SELECT 
              c.id,
              c.company_name,
              c.short_name,
              c.client_type,
              c.contact_person,
              c.email,
              c.phone,
              COALESCE(i.industry_name, 'N/A') as industry_name,
              COALESCE(t.transit_point_name, 'N/A') as location_name,
              c.created_at,
              c.display
            FROM clients_t c
            LEFT JOIN industry_master_t i ON c.industry_type_id = i.id
            LEFT JOIN transit_point_master_t t ON c.office_location_id = t.id
            WHERE c.display = 'Y'
            ORDER BY c.created_at DESC
            LIMIT 10";
    
    return $this->db->customQuery($sql) ?: [];
  }

  // Export dashboard data using PhpSpreadsheet
  public function exportDashboard()
  {
    try {
      // Load vendor autoload
      $vendorPath = __DIR__ . '/../../../vendor/autoload.php';
      
      if (!file_exists($vendorPath)) {
        throw new Exception('PhpSpreadsheet not found. Please run: composer require phpoffice/phpspreadsheet');
      }
      
      require_once $vendorPath;
      
      // Use fully qualified class names instead of use statements
      $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
      $spreadsheet->removeSheetByIndex(0);
      
      // ==================== SHEET 1: KPI Summary ====================
      $sheet1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'KPI Summary');
      $spreadsheet->addSheet($sheet1, 0);
      $kpiData = $this->getKPIData();
      
      // Header
      $sheet1->setCellValue('A1', 'CLIENT DASHBOARD - KPI SUMMARY');
      $sheet1->mergeCells('A1:D1');
      $sheet1->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]
      ]);
      $sheet1->getRowDimension(1)->setRowHeight(30);
      
      $sheet1->setCellValue('A2', 'Generated: ' . date('Y-m-d H:i:s'));
      $sheet1->mergeCells('A2:D2');
      $sheet1->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      
      // KPI Data
      $row = 4;
      $sheet1->setCellValue('A' . $row, 'Metric');
      $sheet1->setCellValue('B' . $row, 'Value');
      $sheet1->getStyle('A' . $row . ':B' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '5b69bc']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row++;
      $kpis = [
        ['Total Clients', $kpiData['total_clients'] ?? 0],
        ['Active Clients', $kpiData['active_clients'] ?? 0],
        ['Inactive Clients', $kpiData['inactive_clients'] ?? 0],
        ['Import Clients', $kpiData['import_clients'] ?? 0],
        ['Export Clients', $kpiData['export_clients'] ?? 0],
        ['Local Clients', $kpiData['local_clients'] ?? 0],
        ['Verified Clients', $kpiData['verified_clients'] ?? 0],
        ['Approved Clients', $kpiData['approved_clients'] ?? 0],
        ['Valid Contracts', $kpiData['valid_contracts'] ?? 0],
        ['Expired Contracts', $kpiData['expired_contracts'] ?? 0],
        ['Today Registrations', $kpiData['today_registrations'] ?? 0],
        ['This Week Registrations', $kpiData['this_week_registrations'] ?? 0],
        ['This Month Registrations', $kpiData['this_month_registrations'] ?? 0]
      ];
      
      foreach ($kpis as $kpi) {
        $sheet1->setCellValue('A' . $row, $kpi[0]);
        $sheet1->setCellValue('B' . $row, $kpi[1]);
        $row++;
      }
      
      // Styling
      $sheet1->getStyle('A4:B' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
        ]
      ]);
      
      $sheet1->getColumnDimension('A')->setWidth(30);
      $sheet1->getColumnDimension('B')->setWidth(20);
      
      // ==================== SHEET 2: Client Type Distribution ====================
      $sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Client Types');
      $spreadsheet->addSheet($sheet2, 1);
      $clientTypeData = $this->getClientTypeDistribution();
      
      $sheet2->setCellValue('A1', 'CLIENT TYPE DISTRIBUTION');
      $sheet2->mergeCells('A1:C1');
      $sheet2->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row = 3;
      $sheet2->setCellValue('A' . $row, 'Client Type');
      $sheet2->setCellValue('B' . $row, 'Count');
      $sheet2->setCellValue('C' . $row, 'Percentage');
      $sheet2->getStyle('A' . $row . ':C' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '5b69bc']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row++;
      $totalClients = ($kpiData['total_clients'] ?? 1) ?: 1;
      $types = [
        ['Import Only', $clientTypeData['import_only'] ?? 0],
        ['Export Only', $clientTypeData['export_only'] ?? 0],
        ['Local Only', $clientTypeData['local_only'] ?? 0],
        ['Import + Export', $clientTypeData['import_export'] ?? 0],
        ['Import + Local', $clientTypeData['import_local'] ?? 0],
        ['Export + Local', $clientTypeData['export_local'] ?? 0],
        ['All Three', $clientTypeData['all_three'] ?? 0]
      ];
      
      foreach ($types as $type) {
        $sheet2->setCellValue('A' . $row, $type[0]);
        $sheet2->setCellValue('B' . $row, $type[1]);
        $percentage = ($type[1] / $totalClients) * 100;
        $sheet2->setCellValue('C' . $row, number_format($percentage, 2) . '%');
        $row++;
      }
      
      $sheet2->getStyle('A3:C' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
        ]
      ]);
      
      $sheet2->getColumnDimension('A')->setWidth(25);
      $sheet2->getColumnDimension('B')->setWidth(15);
      $sheet2->getColumnDimension('C')->setWidth(15);
      
      // ==================== SHEET 3: Location Distribution ====================
      $sheet3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Locations');
      $spreadsheet->addSheet($sheet3, 2);
      $locationData = $this->getLocationDistribution();
      
      $sheet3->setCellValue('A1', 'LOCATION DISTRIBUTION');
      $sheet3->mergeCells('A1:C1');
      $sheet3->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row = 3;
      $sheet3->setCellValue('A' . $row, 'Location');
      $sheet3->setCellValue('B' . $row, 'Client Count');
      $sheet3->setCellValue('C' . $row, 'Percentage');
      $sheet3->getStyle('A' . $row . ':C' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '35b8e0']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row++;
      foreach ($locationData as $location) {
        $sheet3->setCellValue('A' . $row, $location['location_name']);
        $sheet3->setCellValue('B' . $row, $location['client_count']);
        $percentage = ($location['client_count'] / $totalClients) * 100;
        $sheet3->setCellValue('C' . $row, number_format($percentage, 2) . '%');
        $row++;
      }
      
      $sheet3->getStyle('A3:C' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
        ]
      ]);
      
      $sheet3->getColumnDimension('A')->setWidth(35);
      $sheet3->getColumnDimension('B')->setWidth(15);
      $sheet3->getColumnDimension('C')->setWidth(15);
      
      // ==================== SHEET 4: Industry Distribution ====================
      $sheet4 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Industries');
      $spreadsheet->addSheet($sheet4, 3);
      $industryData = $this->getIndustryDistribution();
      
      $sheet4->setCellValue('A1', 'INDUSTRY DISTRIBUTION (TOP 10)');
      $sheet4->mergeCells('A1:C1');
      $sheet4->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row = 3;
      $sheet4->setCellValue('A' . $row, 'Industry');
      $sheet4->setCellValue('B' . $row, 'Client Count');
      $sheet4->setCellValue('C' . $row, 'Percentage');
      $sheet4->getStyle('A' . $row . ':C' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '10c469']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row++;
      foreach ($industryData as $industry) {
        $sheet4->setCellValue('A' . $row, $industry['industry_name']);
        $sheet4->setCellValue('B' . $row, $industry['client_count']);
        $percentage = ($industry['client_count'] / $totalClients) * 100;
        $sheet4->setCellValue('C' . $row, number_format($percentage, 2) . '%');
        $row++;
      }
      
      $sheet4->getStyle('A3:C' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
        ]
      ]);
      
      $sheet4->getColumnDimension('A')->setWidth(40);
      $sheet4->getColumnDimension('B')->setWidth(15);
      $sheet4->getColumnDimension('C')->setWidth(15);
      
      // ==================== SHEET 5: Payment Terms ====================
      $sheet5 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Payment Terms');
      $spreadsheet->addSheet($sheet5, 4);
      $paymentData = $this->getPaymentTermDistribution();
      
      $sheet5->setCellValue('A1', 'PAYMENT TERMS DISTRIBUTION');
      $sheet5->mergeCells('A1:C1');
      $sheet5->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row = 3;
      $sheet5->setCellValue('A' . $row, 'Payment Term');
      $sheet5->setCellValue('B' . $row, 'Client Count');
      $sheet5->setCellValue('C' . $row, 'Percentage');
      $sheet5->getStyle('A' . $row . ':C' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'f9c851']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row++;
      foreach ($paymentData as $payment) {
        $sheet5->setCellValue('A' . $row, $payment['payment_term']);
        $sheet5->setCellValue('B' . $row, $payment['client_count']);
        $percentage = ($payment['client_count'] / $totalClients) * 100;
        $sheet5->setCellValue('C' . $row, number_format($percentage, 2) . '%');
        $row++;
      }
      
      $sheet5->getStyle('A3:C' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
        ]
      ]);
      
      $sheet5->getColumnDimension('A')->setWidth(30);
      $sheet5->getColumnDimension('B')->setWidth(15);
      $sheet5->getColumnDimension('C')->setWidth(15);
      
      // ==================== SHEET 6: Monthly Trend ====================
      $sheet6 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Monthly Trend');
      $spreadsheet->addSheet($sheet6, 5);
      $trendData = $this->getMonthlyRegistrationTrend();
      
      $sheet6->setCellValue('A1', 'MONTHLY REGISTRATION TREND (LAST 12 MONTHS)');
      $sheet6->mergeCells('A1:D1');
      $sheet6->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row = 3;
      $sheet6->setCellValue('A' . $row, 'Month');
      $sheet6->setCellValue('B' . $row, 'New Clients');
      $sheet6->setCellValue('C' . $row, 'Cumulative');
      $sheet6->setCellValue('D' . $row, 'Growth %');
      $sheet6->getStyle('A' . $row . ':D' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '5b69bc']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row++;
      $cumulative = 0;
      $prevCount = 0;
      foreach ($trendData as $trend) {
        $currentCount = $trend['client_count'];
        $cumulative += $currentCount;
        $growth = $prevCount > 0 ? (($currentCount - $prevCount) / $prevCount) * 100 : 0;
        
        $sheet6->setCellValue('A' . $row, $trend['month_name']);
        $sheet6->setCellValue('B' . $row, $currentCount);
        $sheet6->setCellValue('C' . $row, $cumulative);
        $sheet6->setCellValue('D' . $row, number_format($growth, 2) . '%');
        
        $prevCount = $currentCount;
        $row++;
      }
      
      $sheet6->getStyle('A3:D' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
        ]
      ]);
      
      $sheet6->getColumnDimension('A')->setWidth(20);
      $sheet6->getColumnDimension('B')->setWidth(15);
      $sheet6->getColumnDimension('C')->setWidth(15);
      $sheet6->getColumnDimension('D')->setWidth(15);
      
      // ==================== SHEET 7: Phase Distribution ====================
      $sheet7 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Phases');
      $spreadsheet->addSheet($sheet7, 6);
      $phaseData = $this->getPhaseDistribution();
      
      $sheet7->setCellValue('A1', 'PHASE DISTRIBUTION');
      $sheet7->mergeCells('A1:C1');
      $sheet7->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row = 3;
      $sheet7->setCellValue('A' . $row, 'Phase');
      $sheet7->setCellValue('B' . $row, 'Client Count');
      $sheet7->setCellValue('C' . $row, 'Percentage');
      $sheet7->getStyle('A' . $row . ':C' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fa5c7c']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row++;
      foreach ($phaseData as $phase) {
        $sheet7->setCellValue('A' . $row, $phase['phase_name']);
        $sheet7->setCellValue('B' . $row, $phase['client_count']);
        $percentage = ($phase['client_count'] / $totalClients) * 100;
        $sheet7->setCellValue('C' . $row, number_format($percentage, 2) . '%');
        $row++;
      }
      
      $sheet7->getStyle('A3:C' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
        ]
      ]);
      
      $sheet7->getColumnDimension('A')->setWidth(40);
      $sheet7->getColumnDimension('B')->setWidth(15);
      $sheet7->getColumnDimension('C')->setWidth(15);
      
      // ==================== SHEET 8: Group Companies ====================
      $sheet8 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Group Companies');
      $spreadsheet->addSheet($sheet8, 7);
      $groupData = $this->getGroupCompanyDistribution();
      
      $sheet8->setCellValue('A1', 'GROUP COMPANY DISTRIBUTION');
      $sheet8->mergeCells('A1:C1');
      $sheet8->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row = 3;
      $sheet8->setCellValue('A' . $row, 'Group Company');
      $sheet8->setCellValue('B' . $row, 'Client Count');
      $sheet8->setCellValue('C' . $row, 'Percentage');
      $sheet8->getStyle('A' . $row . ':C' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '8f75da']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row++;
      foreach ($groupData as $group) {
        $sheet8->setCellValue('A' . $row, $group['group_name']);
        $sheet8->setCellValue('B' . $row, $group['client_count']);
        $percentage = ($group['client_count'] / $totalClients) * 100;
        $sheet8->setCellValue('C' . $row, number_format($percentage, 2) . '%');
        $row++;
      }
      
      $sheet8->getStyle('A3:C' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
        ]
      ]);
      
      $sheet8->getColumnDimension('A')->setWidth(40);
      $sheet8->getColumnDimension('B')->setWidth(15);
      $sheet8->getColumnDimension('C')->setWidth(15);
      
      // ==================== SHEET 9: Contract Status ====================
      $sheet9 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Contract Status');
      $spreadsheet->addSheet($sheet9, 8);
      $contractData = $this->getContractStatus();
      
      $sheet9->setCellValue('A1', 'CONTRACT STATUS');
      $sheet9->mergeCells('A1:C1');
      $sheet9->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row = 3;
      $sheet9->setCellValue('A' . $row, 'Status');
      $sheet9->setCellValue('B' . $row, 'Count');
      $sheet9->setCellValue('C' . $row, 'Percentage');
      $sheet9->getStyle('A' . $row . ':C' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '10c469']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row++;
      $contracts = [
        ['No Contract', $contractData['no_contract'] ?? 0],
        ['Valid Contract', $contractData['valid_contract'] ?? 0],
        ['Expired Contract', $contractData['expired_contract'] ?? 0],
        ['Expiring Soon (30 days)', $contractData['expiring_soon'] ?? 0]
      ];
      
      foreach ($contracts as $contract) {
        $sheet9->setCellValue('A' . $row, $contract[0]);
        $sheet9->setCellValue('B' . $row, $contract[1]);
        $percentage = ($contract[1] / $totalClients) * 100;
        $sheet9->setCellValue('C' . $row, number_format($percentage, 2) . '%');
        $row++;
      }
      
      $sheet9->getStyle('A3:C' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
        ]
      ]);
      
      $sheet9->getColumnDimension('A')->setWidth(30);
      $sheet9->getColumnDimension('B')->setWidth(15);
      $sheet9->getColumnDimension('C')->setWidth(15);
      
      // ==================== SHEET 10: Document Completion ====================
      $sheet10 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Documents');
      $spreadsheet->addSheet($sheet10, 9);
      $docData = $this->getDocumentCompletionStatus();
      
      $sheet10->setCellValue('A1', 'DOCUMENT COMPLETION STATUS');
      $sheet10->mergeCells('A1:C1');
      $sheet10->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row = 3;
      $sheet10->setCellValue('A' . $row, 'Document Type');
      $sheet10->setCellValue('B' . $row, 'Count');
      $sheet10->setCellValue('C' . $row, 'Completion %');
      $sheet10->getStyle('A' . $row . ':C' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '35b8e0']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row++;
      $docTotal = $docData['total_clients'] ?: 1;
      $documents = [
        ['ID/NAT File', $docData['has_id_nat'] ?? 0],
        ['RCCM File', $docData['has_rccm'] ?? 0],
        ['Import/Export File', $docData['has_import_export'] ?? 0],
        ['Attestation File', $docData['has_attestation'] ?? 0],
        ['Complete Documentation', $docData['complete_docs'] ?? 0]
      ];
      
      foreach ($documents as $doc) {
        $sheet10->setCellValue('A' . $row, $doc[0]);
        $sheet10->setCellValue('B' . $row, $doc[1]);
        $percentage = ($doc[1] / $docTotal) * 100;
        $sheet10->setCellValue('C' . $row, number_format($percentage, 2) . '%');
        $row++;
      }
      
      $sheet10->getStyle('A3:C' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
        ]
      ]);
      
      $sheet10->getColumnDimension('A')->setWidth(30);
      $sheet10->getColumnDimension('B')->setWidth(15);
      $sheet10->getColumnDimension('C')->setWidth(15);
      
      // ==================== SHEET 11: Verification Status ====================
      $sheet11 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Verification');
      $spreadsheet->addSheet($sheet11, 10);
      $verificationData = $this->getVerificationStatus();
      
      $sheet11->setCellValue('A1', 'VERIFICATION & APPROVAL STATUS');
      $sheet11->mergeCells('A1:C1');
      $sheet11->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row = 3;
      $sheet11->setCellValue('A' . $row, 'Status');
      $sheet11->setCellValue('B' . $row, 'Count');
      $sheet11->setCellValue('C' . $row, 'Percentage');
      $sheet11->getStyle('A' . $row . ':C' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'f9c851']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row++;
      $verifyTotal = $verificationData['total_clients'] ?: 1;
      $statuses = [
        ['Verified & Approved', $verificationData['verified_and_approved'] ?? 0],
        ['Verified Only', $verificationData['verified_only'] ?? 0],
        ['Not Verified', $verificationData['not_verified'] ?? 0]
      ];
      
      foreach ($statuses as $status) {
        $sheet11->setCellValue('A' . $row, $status[0]);
        $sheet11->setCellValue('B' . $row, $status[1]);
        $percentage = ($status[1] / $verifyTotal) * 100;
        $sheet11->setCellValue('C' . $row, number_format($percentage, 2) . '%');
        $row++;
      }
      
      $sheet11->getStyle('A3:C' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
        ]
      ]);
      
      $sheet11->getColumnDimension('A')->setWidth(30);
      $sheet11->getColumnDimension('B')->setWidth(15);
      $sheet11->getColumnDimension('C')->setWidth(15);
      
      // ==================== SHEET 12: Recent Clients ====================
      $sheet12 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Recent Clients');
      $spreadsheet->addSheet($sheet12, 11);
      $recentClients = $this->getRecentClients();
      
      $sheet12->setCellValue('A1', 'RECENT CLIENTS (TOP 10)');
      $sheet12->mergeCells('A1:J1');
      $sheet12->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row = 3;
      $headers = ['ID', 'Company Name', 'Short Name', 'Type', 'Contact Person', 'Email', 'Phone', 'Industry', 'Location', 'Created Date'];
      $col = 'A';
      foreach ($headers as $header) {
        $sheet12->setCellValue($col . $row, $header);
        $col++;
      }
      $sheet12->getStyle('A' . $row . ':J' . $row)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fa5c7c']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
      ]);
      
      $row++;
      foreach ($recentClients as $client) {
        $sheet12->setCellValue('A' . $row, $client['id']);
        $sheet12->setCellValue('B' . $row, $client['company_name']);
        $sheet12->setCellValue('C' . $row, $client['short_name']);
        $sheet12->setCellValue('D' . $row, $client['client_type']);
        $sheet12->setCellValue('E' . $row, $client['contact_person']);
        $sheet12->setCellValue('F' . $row, $client['email']);
        $sheet12->setCellValue('G' . $row, $client['phone']);
        $sheet12->setCellValue('H' . $row, $client['industry_name']);
        $sheet12->setCellValue('I' . $row, $client['location_name']);
        $sheet12->setCellValue('J' . $row, date('Y-m-d', strtotime($client['created_at'])));
        $row++;
      }
      
      $sheet12->getStyle('A3:J' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
        ]
      ]);
      
      $sheet12->getColumnDimension('A')->setWidth(10);
      $sheet12->getColumnDimension('B')->setWidth(35);
      $sheet12->getColumnDimension('C')->setWidth(15);
      $sheet12->getColumnDimension('D')->setWidth(12);
      $sheet12->getColumnDimension('E')->setWidth(25);
      $sheet12->getColumnDimension('F')->setWidth(30);
      $sheet12->getColumnDimension('G')->setWidth(20);
      $sheet12->getColumnDimension('H')->setWidth(25);
      $sheet12->getColumnDimension('I')->setWidth(25);
      $sheet12->getColumnDimension('J')->setWidth(15);
      
      // Save file to uploads folder
      $filename = 'Client_Dashboard_' . date('Y-m-d_His') . '.xlsx';
      $filepath = __DIR__ . '/../../../uploads/' . $filename;
      
      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $writer->save($filepath);
      
      // Download file
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
      echo json_encode(['success' => false, 'message' => 'Export failed: ' . $e->getMessage()]);
      exit;
    }
  }
}