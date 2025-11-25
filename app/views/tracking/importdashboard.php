<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
  :root {
    --bg-primary: #f5f7fa;
    --bg-secondary: #ffffff;
    --text-primary: #2c3e50;
    --text-secondary: #7c8a96;
    --border-color: rgba(0, 0, 0, 0.08);
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
    --table-hover: #f8f9fa;
    --table-border: #e9ecef;
  }

  [data-bs-theme="dark"] {
    --bg-primary: #1a1d29;
    --bg-secondary: #252836;
    --text-primary: #e4e6eb;
    --text-secondary: #b8bbc5;
    --border-color: rgba(255, 255, 255, 0.08);
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.3);
    --table-hover: #2d3142;
    --table-border: #3a3d4f;
  }

  body { background: var(--bg-primary); }

  .dashboard-header {
    background: var(--bg-secondary);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
  }

  .dashboard-header h2 {
    margin: 0;
    color: var(--text-primary);
    font-weight: 700;
  }

  .theme-btn {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
  }

  .theme-btn:hover { background: var(--border-color); }

  .nav-tabs {
    background: var(--bg-secondary);
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid var(--border-color);
  }

  .nav-tabs .nav-link {
    color: var(--text-primary);
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s;
  }

  .nav-tabs .nav-link:hover { background: var(--bg-primary); }

  .nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }

  .kpi-card {
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    color: white;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
    cursor: pointer;
  }

  .kpi-card:hover { 
    transform: translateY(-8px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  }

  .kpi-card.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
  .kpi-card.green { background: linear-gradient(135deg, #10c469 0%, #0e9f5a 100%); }
  .kpi-card.orange { background: linear-gradient(135deg, #f9c851 0%, #f7a842 100%); }
  .kpi-card.red { background: linear-gradient(135deg, #fa5c7c 0%, #f83e5e 100%); }
  .kpi-card.purple { background: linear-gradient(135deg, #5b69bc 0%, #3f4d96 100%); }
  .kpi-card.cyan { background: linear-gradient(135deg, #35b8e0 0%, #2a9dc7 100%); }
  .kpi-card.teal { background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%); }
  .kpi-card.indigo { background: linear-gradient(135deg, #6610f2 0%, #5a3baa 100%); }
  .kpi-card.pink { background: linear-gradient(135deg, #e83e8c 0%, #d63384 100%); }
  .kpi-card.dark { background: linear-gradient(135deg, #343a40 0%, #23272b 100%); }
  .kpi-card.warning { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); }
  .kpi-card.info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); }
  .kpi-card.lime { background: linear-gradient(135deg, #84cc16 0%, #65a30d 100%); }
  .kpi-card.amber { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
  .kpi-card.emerald { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
  .kpi-card.sky { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
  .kpi-card.violet { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
  .kpi-card.fuchsia { background: linear-gradient(135deg, #d946ef 0%, #c026d3 100%); }
  .kpi-card.rose { background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); }
  .kpi-card.slate { background: linear-gradient(135deg, #64748b 0%, #475569 100%); }

  .kpi-card h3 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
  }

  .kpi-card h4 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
  }

  .kpi-card p {
    margin: 5px 0 0;
    font-size: 0.9rem;
    opacity: 0.9;
  }

  .kpi-card .icon {
    font-size: 3rem;
    opacity: 0.3;
    position: absolute;
    right: 20px;
    top: 20px;
  }

  .phase-card {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    border-radius: 16px;
    padding: 32px;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
    margin-bottom: 20px;
  }

  .phase-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.16);
    transform: translateY(-2px);
  }

  .phase-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
  }

  .phase-1::before { background: linear-gradient(90deg, #10b981, #059669); }
  .phase-2::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
  .phase-3::before { background: linear-gradient(90deg, #ef4444, #dc2626); }
  .phase-total::before { background: linear-gradient(90deg, #3b82f6, #1d4ed8); }

  .phase-stat-number {
    font-size: 3rem;
    font-weight: 900;
    margin-bottom: 12px;
  }

  .phase-stat-label {
    font-size: 1.1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
  }

  .phase-stat-desc {
    font-size: 0.875rem;
    opacity: 0.8;
    font-weight: 600;
  }

  .phase-1 .phase-stat-number { color: #10b981; }
  .phase-2 .phase-stat-number { color: #f59e0b; }
  .phase-3 .phase-stat-number { color: #ef4444; }
  .phase-total .phase-stat-number { color: #3b82f6; }

  .phase-1 .phase-stat-label { color: #047857; }
  .phase-2 .phase-stat-label { color: #b45309; }
  .phase-3 .phase-stat-label { color: #b91c1c; }
  .phase-total .phase-stat-label { color: #1e40af; }

  .phase-1 .phase-stat-desc { color: #059669; }
  .phase-2 .phase-stat-desc { color: #d97706; }
  .phase-3 .phase-stat-desc { color: #dc2626; }
  .phase-total .phase-stat-desc { color: #2563eb; }

  .phase-card .phase-icon {
    font-size: 3.5rem;
    margin-bottom: 16px;
  }

  .phase-1 .phase-icon { color: #10b981; }
  .phase-2 .phase-icon { color: #f59e0b; }
  .phase-3 .phase-icon { color: #ef4444; }
  .phase-total .phase-icon { color: #3b82f6; }

  [data-bs-theme="dark"] .phase-card {
    background: linear-gradient(135deg, #252836, #1a1d29);
    border-color: #3a3d4f;
  }

  .tracking-stage-card {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    border-radius: 16px;
    padding: 32px;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
    margin-bottom: 20px;
  }

  .tracking-stage-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.16);
    transform: translateY(-2px);
  }

  .tracking-stage-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
  }

  .tracking-stage-card.stage-blue::before { background: linear-gradient(90deg, #667eea, #764ba2); }
  .tracking-stage-card.stage-orange::before { background: linear-gradient(90deg, #f9c851, #f7a842); }
  .tracking-stage-card.stage-amber::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
  .tracking-stage-card.stage-lime::before { background: linear-gradient(90deg, #84cc16, #65a30d); }
  .tracking-stage-card.stage-emerald::before { background: linear-gradient(90deg, #10b981, #059669); }
  .tracking-stage-card.stage-sky::before { background: linear-gradient(90deg, #0ea5e9, #0284c7); }
  .tracking-stage-card.stage-violet::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
  .tracking-stage-card.stage-fuchsia::before { background: linear-gradient(90deg, #d946ef, #c026d3); }
  .tracking-stage-card.stage-rose::before { background: linear-gradient(90deg, #f43f5e, #e11d48); }
  .tracking-stage-card.stage-green::before { background: linear-gradient(90deg, #10c469, #0e9f5a); }

  .tracking-stage-icon-large { font-size: 3.5rem; margin-bottom: 16px; }
  .tracking-stage-card.stage-blue .tracking-stage-icon-large { color: #667eea; }
  .tracking-stage-card.stage-orange .tracking-stage-icon-large { color: #f9c851; }
  .tracking-stage-card.stage-amber .tracking-stage-icon-large { color: #f59e0b; }
  .tracking-stage-card.stage-lime .tracking-stage-icon-large { color: #84cc16; }
  .tracking-stage-card.stage-emerald .tracking-stage-icon-large { color: #10b981; }
  .tracking-stage-card.stage-sky .tracking-stage-icon-large { color: #0ea5e9; }
  .tracking-stage-card.stage-violet .tracking-stage-icon-large { color: #8b5cf6; }
  .tracking-stage-card.stage-fuchsia .tracking-stage-icon-large { color: #d946ef; }
  .tracking-stage-card.stage-rose .tracking-stage-icon-large { color: #f43f5e; }
  .tracking-stage-card.stage-green .tracking-stage-icon-large { color: #10c469; }

  .tracking-stat-number {
    font-size: 3rem;
    font-weight: 900;
    margin-bottom: 12px;
  }

  .tracking-stage-card.stage-blue .tracking-stat-number { color: #667eea; }
  .tracking-stage-card.stage-orange .tracking-stat-number { color: #f9c851; }
  .tracking-stage-card.stage-amber .tracking-stat-number { color: #f59e0b; }
  .tracking-stage-card.stage-lime .tracking-stat-number { color: #84cc16; }
  .tracking-stage-card.stage-emerald .tracking-stat-number { color: #10b981; }
  .tracking-stage-card.stage-sky .tracking-stat-number { color: #0ea5e9; }
  .tracking-stage-card.stage-violet .tracking-stat-number { color: #8b5cf6; }
  .tracking-stage-card.stage-fuchsia .tracking-stat-number { color: #d946ef; }
  .tracking-stage-card.stage-rose .tracking-stat-number { color: #f43f5e; }
  .tracking-stage-card.stage-green .tracking-stat-number { color: #10c469; }

  .tracking-stat-label {
    font-size: 1.1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
    color: #374151;
  }

  .tracking-stat-desc {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 600;
  }

  .tracking-stage-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #f3f4f6;
    color: #374151;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  [data-bs-theme="dark"] .tracking-stage-card,
  [data-bs-theme="dark"] .phase-card {
    background: linear-gradient(135deg, #252836, #1a1d29);
    border-color: #3a3d4f;
  }

  [data-bs-theme="dark"] .tracking-stat-label,
  [data-bs-theme="dark"] .tracking-stat-desc {
    color: #e4e6eb;
  }

  [data-bs-theme="dark"] .tracking-stage-badge {
    background: rgba(255, 255, 255, 0.1);
    color: #e4e6eb;
  }

  .section-header {
    background: var(--bg-secondary);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    border: 2px solid var(--border-color);
  }

  .section-header h3 {
    margin: 0;
    color: var(--text-primary);
    font-weight: 700;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .section-header p {
    margin: 5px 0 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
  }

  .card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    margin-bottom: 20px;
    border-radius: 8px;
  }

  .card-body { padding: 20px; }

  .header-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: var(--text-primary);
  }

  .apex-charts {
    min-height: 350px;
  }

  .chart-type-selector {
    display: flex;
    background: var(--bg-primary);
    border-radius: 8px;
    padding: 4px;
    margin-bottom: 15px;
    gap: 4px;
    border: 1px solid var(--border-color);
    justify-content: center;
  }

  .chart-type-btn {
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    color: var(--text-primary);
    background: transparent;
    border: none;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
  }

  .chart-type-btn.active {
    background: linear-gradient(135deg, #5b69bc, #3f4d96);
    color: white;
    box-shadow: 0 2px 8px rgba(91, 105, 188, 0.3);
  }

  .chart-type-btn:hover:not(.active) {
    background: var(--bg-secondary);
  }

  .table {
    color: var(--text-primary);
    width: 100%;
    border-collapse: collapse;
  }

  .table thead th {
    background: #f8fafc;
    font-weight: 700;
    color: #374151;
    padding: 12px;
    text-align: center;
    border: 1px solid var(--border-color);
  }

  .table tbody td {
    padding: 10px 12px;
    text-align: center;
    border: 1px solid var(--border-color);
  }

  .table tbody tr:hover {
    background: var(--table-hover);
  }

  .table .grand-total-row {
    background: #f0f9ff !important;
    font-weight: 700;
    border-top: 2px solid #3b82f6;
  }

  .table .grand-total-row td {
    color: #1e40af;
    font-weight: 800;
  }

  [data-bs-theme="dark"] .table thead th {
    background: var(--bg-primary);
    color: var(--text-primary);
  }

  [data-bs-theme="dark"] .table .grand-total-row {
    background: rgba(59, 130, 246, 0.1) !important;
  }
</style>

<div class="page-content">
  <div class="page-container">

    <!-- Header -->
    <div class="dashboard-header d-flex justify-content-between align-items-center">
      <div>
        <h2><i class="ti ti-file-import me-2"></i> Import Dashboard</h2>
        <p class="text-muted mb-0">Comprehensive import analytics and insights</p>
      </div>
      <div class="d-flex gap-2">
        <button class="theme-btn" id="themeToggle">
          <i class="ti ti-sun" id="themeIcon"></i>
        </button>
        <button class="btn btn-primary" id="exportDashboardBtn">
          <i class="ti ti-download me-1"></i> Export
        </button>
        <a href="<?= APP_URL ?>/import" class="btn btn-secondary">
          <i class="ti ti-file-import me-1"></i> Imports
        </a>
      </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
          <i class="ti ti-chart-line me-2"></i>Overview
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="logistics-tab" data-bs-toggle="tab" data-bs-target="#logistics" type="button" role="tab">
          <i class="ti ti-truck me-2"></i>Logistics
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="delay-tab" data-bs-toggle="tab" data-bs-target="#delay" type="button" role="tab">
          <i class="ti ti-clock-exclamation me-2"></i>Delay KPI
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="triphase-tab" data-bs-toggle="tab" data-bs-target="#triphase" type="button" role="tab">
          <i class="ti ti-timeline me-2"></i>Tri Phase
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button" role="tab">
          <i class="ti ti-map-pin me-2"></i>Declaration Office
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="client-based-tab" data-bs-toggle="tab" data-bs-target="#client-based" type="button" role="tab">
          <i class="ti ti-users me-2"></i>Client Analysis
        </button>
      </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="dashboardTabsContent">
      
      <!-- ==================== OVERVIEW TAB (NO CHARTS - DATABASE DRIVEN) ==================== -->
      <div class="tab-pane fade show active" id="overview" role="tabpanel">
        
        <!-- Primary KPI Row -->
        <div class="row">
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="kpi-card blue">
              <i class="ti ti-file-import icon"></i>
              <h3><?= number_format($kpi_data['total_imports'] ?? 0) ?></h3>
              <p>Total Imports</p>
            </div>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="kpi-card cyan">
              <i class="ti ti-truck-delivery icon"></i>
              <h3><?= number_format($clearing_status_summary['in_transit'] ?? 0) ?></h3>
              <p>In Transit</p>
            </div>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="kpi-card orange">
              <i class="ti ti-progress icon"></i>
              <h3><?= number_format($kpi_data['in_progress_imports'] ?? 0) ?></h3>
              <p>In Progress</p>
            </div>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="kpi-card green">
              <i class="ti ti-circle-check icon"></i>
              <h3><?= number_format($kpi_data['clearing_completed'] ?? 0) ?></h3>
              <p>Clearing Completed</p>
            </div>
          </div>
        </div>

        <!-- Time Period KPI Row -->
        <div class="row">
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card red">
              <i class="ti ti-calendar-event icon"></i>
              <h3><?= number_format($kpi_data['today_imports'] ?? 0) ?></h3>
              <p>Today</p>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card purple">
              <i class="ti ti-calendar-week icon"></i>
              <h3><?= number_format($kpi_data['this_week_imports'] ?? 0) ?></h3>
              <p>This Week</p>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card teal">
              <i class="ti ti-calendar-month icon"></i>
              <h3><?= number_format($kpi_data['this_month_imports'] ?? 0) ?></h3>
              <p>This Month</p>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card indigo">
              <i class="ti ti-calendar icon"></i>
              <h3><?= number_format($kpi_data['this_year_imports'] ?? 0) ?></h3>
              <p>This Year</p>
            </div>
          </div>
        </div>

        <!-- Transport Mode Section -->
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-truck me-2"></i>Transport Mode Statistics</h3>
              <p>Import distribution by transport method</p>
            </div>
          </div>
        </div>

        <div class="row">
          <?php if (!empty($transport_mode_stats) && is_array($transport_mode_stats)): ?>
            <?php 
            $transport_colors = ['ROAD' => 'blue', 'AIR' => 'sky', 'WAGON' => 'emerald'];
            $transport_icons = ['ROAD' => 'car', 'AIR' => 'plane', 'WAGON' => 'train'];
            foreach ($transport_mode_stats as $transport): 
              $color = $transport_colors[$transport['transport_name']] ?? 'slate';
              $icon = $transport_icons[$transport['transport_name']] ?? 'truck';
            ?>
              <div class="col-xl-4 col-md-6">
                <div class="kpi-card <?= $color ?>">
                  <i class="ti ti-<?= $icon ?> icon"></i>
                  <h3><?= number_format($transport['import_count'] ?? 0) ?></h3>
                  <p><?= htmlspecialchars($transport['transport_name']) ?></p>
                  <div style="display: flex; gap: 15px; margin-top: 10px; font-size: 0.85rem; flex-wrap: wrap;">
                    <span><i class="ti ti-truck-delivery"></i> <?= number_format($transport['in_transit_count'] ?? 0) ?> In Transit</span>
                    <span><i class="ti ti-progress"></i> <?= number_format($transport['in_progress_count'] ?? 0) ?> In Progress</span>
                    <span><i class="ti ti-circle-check"></i> <?= number_format($transport['cleared_count'] ?? 0) ?> Cleared</span>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12"><div class="alert alert-info">No transport mode data available</div></div>
          <?php endif; ?>
        </div>

        <!-- Clearing Status Summary -->
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-list-check me-2"></i>Detailed Clearing Status Breakdown</h3>
              <p>Complete overview of all clearing statuses</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-2 col-md-4">
            <div class="card">
              <div class="card-body text-center">
                <i class="ti ti-truck-delivery" style="font-size: 2.5rem; color: #667eea;"></i>
                <h3 class="mt-2 mb-0"><?= number_format($clearing_status_summary['in_transit'] ?? 0) ?></h3>
                <p class="text-muted mb-0">In Transit</p>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-4">
            <div class="card">
              <div class="card-body text-center">
                <i class="ti ti-progress" style="font-size: 2.5rem; color: #f9c851;"></i>
                <h3 class="mt-2 mb-0"><?= number_format($clearing_status_summary['in_progress'] ?? 0) ?></h3>
                <p class="text-muted mb-0">In Progress</p>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-4">
            <div class="card">
              <div class="card-body text-center">
                <i class="ti ti-circle-check" style="font-size: 2.5rem; color: #10c469;"></i>
                <h3 class="mt-2 mb-0"><?= number_format($clearing_status_summary['clearing_completed'] ?? 0) ?></h3>
                <p class="text-muted mb-0">Clearing Completed</p>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-4">
            <div class="card">
              <div class="card-body text-center">
                <i class="ti ti-x" style="font-size: 2.5rem; color: #fa5c7c;"></i>
                <h3 class="mt-2 mb-0"><?= number_format($clearing_status_summary['cancelled'] ?? 0) ?></h3>
                <p class="text-muted mb-0">Cancelled</p>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-4">
            <div class="card">
              <div class="card-body text-center">
                <i class="ti ti-alert-circle" style="font-size: 2.5rem; color: #5b69bc;"></i>
                <h3 class="mt-2 mb-0"><?= number_format($clearing_status_summary['cleared_with_ir'] ?? 0) ?></h3>
                <p class="text-muted mb-0">Cleared With IR</p>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-4">
            <div class="card">
              <div class="card-body text-center">
                <i class="ti ti-shield-check" style="font-size: 2.5rem; color: #35b8e0;"></i>
                <h3 class="mt-2 mb-0"><?= number_format($clearing_status_summary['cleared_with_ara'] ?? 0) ?></h3>
                <p class="text-muted mb-0">Cleared With ARA</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Kind Distribution -->
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-category me-2"></i>Import Kind Distribution</h3>
              <p>Breakdown by import type</p>
            </div>
          </div>
        </div>

        <div class="row">
          <?php if (!empty($kind_distribution) && is_array($kind_distribution)): ?>
            <?php 
            $kind_colors = ['violet', 'pink', 'amber', 'lime', 'sky', 'rose', 'emerald', 'fuchsia'];
            $kind_icons = ['tag', 'tags', 'bookmark', 'bookmarks', 'box', 'package', 'packages', 'archive'];
            foreach ($kind_distribution as $index => $kind): 
              $color = $kind_colors[$index % count($kind_colors)];
              $icon = $kind_icons[$index % count($kind_icons)];
            ?>
              <div class="col-xl-3 col-md-6">
                <div class="kpi-card <?= $color ?>">
                  <i class="ti ti-<?= $icon ?> icon"></i>
                  <h4><?= number_format($kind['import_count'] ?? 0) ?></h4>
                  <p><?= htmlspecialchars($kind['kind_name']) ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12"><div class="alert alert-info">No kind distribution data available</div></div>
          <?php endif; ?>
        </div>

        <!-- Type of Goods -->
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-package me-2"></i>Type of Goods Distribution</h3>
              <p>Breakdown by goods category</p>
            </div>
          </div>
        </div>

        <div class="row">
          <?php if (!empty($goods_distribution) && is_array($goods_distribution)): ?>
            <?php 
            $goods_colors = ['blue', 'green', 'orange', 'purple', 'cyan', 'teal', 'indigo', 'pink', 'amber', 'lime'];
            $goods_icons = ['package', 'packages', 'box', 'boxes', 'gift', 'shopping-bag', 'briefcase', 'toolbox', 'device-desktop', 'palette'];
            foreach ($goods_distribution as $index => $goods): 
              $color = $goods_colors[$index % count($goods_colors)];
              $icon = $goods_icons[$index % count($goods_icons)];
            ?>
              <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card">
                  <div class="card-body text-center">
                    <i class="ti ti-<?= $icon ?>" style="font-size: 2.5rem; color: #667eea;"></i>
                    <h4 class="mt-2 mb-0"><?= number_format($goods['import_count'] ?? 0) ?></h4>
                    <p class="text-muted mb-0"><?= htmlspecialchars($goods['goods_name']) ?></p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12"><div class="alert alert-info">No goods distribution data available</div></div>
          <?php endif; ?>
        </div>

        <!-- Clearance Type -->
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-checklist me-2"></i>Clearance Type Distribution</h3>
              <p>Breakdown by clearance method</p>
            </div>
          </div>
        </div>

        <div class="row">
          <?php if (!empty($clearance_type_distribution) && is_array($clearance_type_distribution)): ?>
            <?php 
            $clearance_colors = ['green', 'blue', 'orange', 'purple', 'cyan'];
            foreach ($clearance_type_distribution as $index => $clearance): 
              $color = $clearance_colors[$index % count($clearance_colors)];
            ?>
              <div class="col-xl-3 col-md-6">
                <div class="kpi-card <?= $color ?>">
                  <i class="ti ti-file-check icon"></i>
                  <h4><?= number_format($clearance['import_count'] ?? 0) ?></h4>
                  <p><?= htmlspecialchars($clearance['clearance_name']) ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12"><div class="alert alert-info">No clearance type data available</div></div>
          <?php endif; ?>
        </div>

        <!-- Currency Distribution -->
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-currency-dollar me-2"></i>Currency Distribution</h3>
              <p>Breakdown by currency used</p>
            </div>
          </div>
        </div>

        <div class="row">
          <?php if (!empty($currency_distribution) && is_array($currency_distribution)): ?>
            <?php 
            $currency_colors = ['teal', 'indigo', 'pink', 'amber', 'lime'];
            foreach ($currency_distribution as $index => $currency): 
              $color = $currency_colors[$index % count($currency_colors)];
            ?>
              <div class="col-xl-3 col-md-6">
                <div class="kpi-card <?= $color ?>">
                  <i class="ti ti-cash icon"></i>
                  <h4><?= number_format($currency['import_count'] ?? 0) ?></h4>
                  <p><?= htmlspecialchars($currency['currency_name']) ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12"><div class="alert alert-info">No currency distribution data available</div></div>
          <?php endif; ?>
        </div>

        <!-- Entry Point -->
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-map-pin me-2"></i>Entry Point Distribution</h3>
              <p>Breakdown by entry points</p>
            </div>
          </div>
        </div>

        <div class="row">
          <?php if (!empty($entry_point_distribution) && is_array($entry_point_distribution)): ?>
            <?php 
            $entry_colors = ['sky', 'violet', 'rose', 'emerald', 'amber', 'fuchsia', 'cyan', 'orange', 'purple', 'teal'];
            foreach ($entry_point_distribution as $index => $entry): 
              $color = $entry_colors[$index % count($entry_colors)];
            ?>
              <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card">
                  <div class="card-body text-center">
                    <i class="ti ti-flag" style="font-size: 2.5rem; color: #10c469;"></i>
                    <h4 class="mt-2 mb-0"><?= number_format($entry['import_count'] ?? 0) ?></h4>
                    <p class="text-muted mb-0"><?= htmlspecialchars($entry['entry_point_name']) ?></p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12"><div class="alert alert-info">No entry point data available</div></div>
          <?php endif; ?>
        </div>

        <!-- Regime Distribution -->
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-building-bank me-2"></i>Regime Distribution</h3>
              <p>Breakdown by customs regime</p>
            </div>
          </div>
        </div>

        <div class="row">
          <?php if (!empty($regime_distribution) && is_array($regime_distribution)): ?>
            <?php 
            $regime_colors = ['purple', 'cyan', 'orange', 'teal', 'pink'];
            foreach ($regime_distribution as $index => $regime): 
              $color = $regime_colors[$index % count($regime_colors)];
            ?>
              <div class="col-xl-3 col-md-6">
                <div class="kpi-card <?= $color ?>">
                  <i class="ti ti-shield icon"></i>
                  <h4><?= number_format($regime['import_count'] ?? 0) ?></h4>
                  <p><?= htmlspecialchars($regime['regime_name']) ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12"><div class="alert alert-info">No regime distribution data available</div></div>
          <?php endif; ?>
        </div>

      </div>
      <!-- END OVERVIEW TAB -->

      <!-- ==================== LOGISTICS TAB ==================== -->
      <div class="tab-pane fade" id="logistics" role="tabpanel">
        
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-route me-2"></i>Road Shipment Tracking Journey</h3>
              <p>Track your shipments through every stage from origin to final destination</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-3 col-md-6">
            <div class="tracking-stage-card stage-blue">
              <span class="tracking-stage-badge">OVERVIEW</span>
              <div class="tracking-stage-icon-large">
                <i class="ti ti-truck"></i>
              </div>
              <div class="tracking-stat-number"><?= number_format($tracking_stages['total_road_shipments'] ?? 0) ?></div>
              <div class="tracking-stat-label">Total Road Shipments</div>
              <div class="tracking-stat-desc">All active road transport entries</div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="tracking-stage-card stage-orange">
              <span class="tracking-stage-badge">STAGE 1</span>
              <div class="tracking-stage-icon-large">
                <i class="ti ti-clock-pause"></i>
              </div>
              <div class="tracking-stat-number"><?= number_format($tracking_stages['waiting_arrival_zambia'] ?? 0) ?></div>
              <div class="tracking-stat-label">Waiting Arrival Zambia</div>
              <div class="tracking-stat-desc">Not yet arrived in Zambia</div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="tracking-stage-card stage-amber">
              <span class="tracking-stage-badge">STAGE 2</span>
              <div class="tracking-stage-icon-large">
                <i class="ti ti-clock-hour-4"></i>
              </div>
              <div class="tracking-stat-number"><?= number_format($tracking_stages['waiting_dispatch_zambia'] ?? 0) ?></div>
              <div class="tracking-stat-label">Waiting Dispatch Zambia</div>
              <div class="tracking-stat-desc">Arrived but not dispatched</div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="tracking-stage-card stage-lime">
              <span class="tracking-stage-badge">STAGE 3</span>
              <div class="tracking-stage-icon-large">
                <i class="ti ti-flag"></i>
              </div>
              <div class="tracking-stat-number"><?= number_format($tracking_stages['waiting_drc_entry'] ?? 0) ?></div>
              <div class="tracking-stat-label">Waiting DRC Entry</div>
              <div class="tracking-stat-desc">Dispatched but not entered DRC</div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-3 col-md-6">
            <div class="tracking-stage-card stage-emerald">
              <span class="tracking-stage-badge">STAGE 4</span>
              <div class="tracking-stage-icon-large">
                <i class="ti ti-building-warehouse"></i>
              </div>
              <div class="tracking-stat-number"><?= number_format($tracking_stages['waiting_border_warehouse'] ?? 0) ?></div>
              <div class="tracking-stat-label">Waiting Border Warehouse</div>
              <div class="tracking-stat-desc">In DRC but not at border warehouse</div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="tracking-stage-card stage-sky">
              <span class="tracking-stage-badge">STAGE 5</span>
              <div class="tracking-stage-icon-large">
                <i class="ti ti-send"></i>
              </div>
              <div class="tracking-stat-number"><?= number_format($tracking_stages['waiting_border_dispatch'] ?? 0) ?></div>
              <div class="tracking-stat-label">Waiting Border Dispatch</div>
              <div class="tracking-stat-desc">At border but not dispatched</div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="tracking-stage-card stage-violet">
              <span class="tracking-stage-badge">STAGE 6</span>
              <div class="tracking-stage-icon-large">
                <i class="ti ti-map-pin"></i>
              </div>
              <div class="tracking-stat-number"><?= number_format($tracking_stages['waiting_kanyaka_arrival'] ?? 0) ?></div>
              <div class="tracking-stat-label">Waiting Kanyaka Arrival</div>
              <div class="tracking-stat-desc">Dispatched but not at Kanyaka</div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="tracking-stage-card stage-fuchsia">
              <span class="tracking-stage-badge">STAGE 7</span>
              <div class="tracking-stage-icon-large">
                <i class="ti ti-arrows-right"></i>
              </div>
              <div class="tracking-stat-number"><?= number_format($tracking_stages['waiting_kanyaka_dispatch'] ?? 0) ?></div>
              <div class="tracking-stat-label">Waiting Kanyaka Dispatch</div>
              <div class="tracking-stat-desc">At Kanyaka but not dispatched</div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-3 col-md-6">
            <div class="tracking-stage-card stage-rose">
              <span class="tracking-stage-badge">STAGE 8</span>
              <div class="tracking-stage-icon-large">
                <i class="ti ti-building-store"></i>
              </div>
              <div class="tracking-stat-number"><?= number_format($tracking_stages['waiting_final_warehouse'] ?? 0) ?></div>
              <div class="tracking-stat-label">Waiting Final Warehouse</div>
              <div class="tracking-stat-desc">Dispatched, no warehouse assigned</div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="tracking-stage-card stage-green">
              <span class="tracking-stage-badge">COMPLETED</span>
              <div class="tracking-stage-icon-large">
                <i class="ti ti-circle-check"></i>
              </div>
              <div class="tracking-stat-number"><?= number_format($tracking_stages['completed_road_journey'] ?? 0) ?></div>
              <div class="tracking-stat-label">Completed Road Journey</div>
              <div class="tracking-stat-desc">All stages complete</div>
            </div>
          </div>

          <div class="col-xl-6 col-md-12">
            <div class="card">
              <div class="card-body text-center">
                <i class="ti ti-chart-line" style="font-size: 3rem; color: #667eea; opacity: 0.5;"></i>
                <h4 class="mt-3 mb-2">Journey Completion Rate</h4>
                <h2 class="text-success mb-0">
                  <?php 
                    $total = ($tracking_stages['total_road_shipments'] ?? 0);
                    $completed = ($tracking_stages['completed_road_journey'] ?? 0);
                    $rate = $total > 0 ? round(($completed / $total) * 100, 1) : 0;
                    echo $rate . '%';
                  ?>
                </h2>
                <p class="text-muted mb-0 mt-2">of shipments have completed the full journey</p>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- END LOGISTICS TAB -->

      <!-- ==================== DELAY KPI TAB ==================== -->
      <div class="tab-pane fade" id="delay" role="tabpanel">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title">Delay KPI Analysis - Coming Soon</h4>
                <p>This section will contain delay metrics and analysis.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ==================== TRI PHASE TAB ==================== -->
      <div class="tab-pane fade" id="triphase" role="tabpanel">
        
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-timeline me-2"></i>Tri-Phase Monthly Division</h3>
              <p>Imports divided into 3 phases per month: Days 1-10, 11-20, 21-End based on creation date</p>
            </div>
          </div>
        </div>

        <!-- Current Month Overview -->
        <div class="row">
          <div class="col-12">
            <div class="alert alert-info">
              <h5><i class="ti ti-calendar me-2"></i>Current Month: <?= date('F Y') ?></h5>
              <p class="mb-0">Showing import distribution across three phases of the month</p>
            </div>
          </div>
        </div>

        <!-- Phase Cards -->
        <div class="row">
          <div class="col-xl-3 col-md-6">
            <div class="phase-card phase-1">
              <div class="phase-icon">
                <i class="ti ti-calendar-event"></i>
              </div>
              <div class="phase-stat-number"><?= number_format($triphase_overview['phase1_count'] ?? 0) ?></div>
              <div class="phase-stat-label">01 To 10 Days</div>
              <div class="phase-stat-desc">Early month activities</div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="phase-card phase-2">
              <div class="phase-icon">
                <i class="ti ti-calendar-week"></i>
              </div>
              <div class="phase-stat-number"><?= number_format($triphase_overview['phase2_count'] ?? 0) ?></div>
              <div class="phase-stat-label">11 To 20 Days</div>
              <div class="phase-stat-desc">Mid month activities</div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="phase-card phase-3">
              <div class="phase-icon">
                <i class="ti ti-calendar-exclamation"></i>
              </div>
              <div class="phase-stat-number"><?= number_format($triphase_overview['phase3_count'] ?? 0) ?></div>
              <div class="phase-stat-label">21 To EOM</div>
              <div class="phase-stat-desc">Late month activities</div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="phase-card phase-total">
              <div class="phase-icon">
                <i class="ti ti-folder-open"></i>
              </div>
              <div class="phase-stat-number"><?= number_format(($triphase_overview['phase1_count'] ?? 0) + ($triphase_overview['phase2_count'] ?? 0) + ($triphase_overview['phase3_count'] ?? 0)) ?></div>
              <div class="phase-stat-label">Grand Total</div>
              <div class="phase-stat-desc">All files in period</div>
            </div>
          </div>
        </div>

        <!-- Tri-Phase Chart -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title mb-3">
                  <i class="ti ti-chart-pie me-2"></i>Tri-Phase Distribution
                </h4>
                
                <div class="chart-type-selector">
                  <button class="chart-type-btn active" onclick="changeTriPhaseChartType('bar')" id="triphase-chart-bar">
                    <i class="ti ti-chart-bar me-1"></i>Bar
                  </button>
                  <button class="chart-type-btn" onclick="changeTriPhaseChartType('pie')" id="triphase-chart-pie">
                    <i class="ti ti-chart-pie me-1"></i>Pie
                  </button>
                  <button class="chart-type-btn" onclick="changeTriPhaseChartType('donut')" id="triphase-chart-donut">
                    <i class="ti ti-chart-donut me-1"></i>Donut
                  </button>
                </div>
                
                <div dir="ltr">
                  <div id="triphaseChart" class="apex-charts" data-colors="#10b981,#f59e0b,#ef4444"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Monthly Breakdown Table -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title mb-3">
                  <i class="ti ti-table me-2"></i>Monthly Breakdown (Based on Day of Month)
                </h4>
                
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Month</th>
                        <th>01-10 Days</th>
                        <th>11-20 Days</th>
                        <th>21+ Days</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($triphase_current_month) && is_array($triphase_current_month)): ?>
                        <?php 
                        $grandTotal1 = 0; $grandTotal2 = 0; $grandTotal3 = 0; $grandTotalAll = 0;
                        $monthlyData = [];
                        foreach ($triphase_current_month as $item) {
                          $monthKey = date('Y-m', strtotime($item['created_at']));
                          $monthName = date('M Y', strtotime($item['created_at']));
                          if (!isset($monthlyData[$monthKey])) {
                            $monthlyData[$monthKey] = ['month_name' => $monthName, 'phase1' => 0, 'phase2' => 0, 'phase3' => 0, 'total' => 0];
                          }
                          $dayOfMonth = (int)$item['day_of_month'];
                          if ($dayOfMonth >= 1 && $dayOfMonth <= 10) $monthlyData[$monthKey]['phase1']++;
                          elseif ($dayOfMonth >= 11 && $dayOfMonth <= 20) $monthlyData[$monthKey]['phase2']++;
                          elseif ($dayOfMonth >= 21) $monthlyData[$monthKey]['phase3']++;
                          $monthlyData[$monthKey]['total']++;
                        }
                        foreach ($monthlyData as $month): 
                          $grandTotal1 += $month['phase1']; $grandTotal2 += $month['phase2']; $grandTotal3 += $month['phase3']; $grandTotalAll += $month['total'];
                        ?>
                        <tr>
                          <td class="font-semibold"><?= $month['month_name'] ?></td>
                          <td class="text-success font-weight-bold"><?= number_format($month['phase1']) ?></td>
                          <td class="text-warning font-weight-bold"><?= number_format($month['phase2']) ?></td>
                          <td class="text-danger font-weight-bold"><?= number_format($month['phase3']) ?></td>
                          <td class="text-primary font-weight-bold"><?= number_format($month['total']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="grand-total-row">
                          <td>GRAND TOTAL</td>
                          <td><?= number_format($grandTotal1) ?></td>
                          <td><?= number_format($grandTotal2) ?></td>
                          <td><?= number_format($grandTotal3) ?></td>
                          <td><?= number_format($grandTotalAll) ?></td>
                        </tr>
                      <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted">No imports this month</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Client Performance by Phase -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title mb-3">Client Performance by Phase</h4>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Client</th>
                        <th>Total</th>
                        <th>Phase 1 (1-10)</th>
                        <th>Phase 2 (11-20)</th>
                        <th>Phase 3 (21-End)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($triphase_by_client) && is_array($triphase_by_client)): ?>
                        <?php foreach ($triphase_by_client as $client): ?>
                          <tr>
                            <td><strong><?= htmlspecialchars($client['client_name'] ?? 'N/A') ?></strong></td>
                            <td><?= number_format($client['total_imports'] ?? 0) ?></td>
                            <td><span class="badge bg-success"><?= number_format($client['phase1_count'] ?? 0) ?></span></td>
                            <td><span class="badge bg-warning"><?= number_format($client['phase2_count'] ?? 0) ?></span></td>
                            <td><span class="badge bg-danger"><?= number_format($client['phase3_count'] ?? 0) ?></span></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted">No data</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- END TRI PHASE TAB -->

<!-- ==================== DECLARATION OFFICE TAB ==================== -->
      <div class="tab-pane fade" id="location" role="tabpanel">

        <!-- Period Cards Row -->
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-calendar me-2"></i>Import Activity by Period</h3>
              <p>Declaration office imports based on creation date</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card red">
              <i class="ti ti-calendar-event icon"></i>
              <h3><?= number_format($location_overview['today_imports'] ?? 0) ?></h3>
              <p>Today's Imports</p>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="kpi-card purple">
              <i class="ti ti-calendar-week icon"></i>
              <h3><?= number_format($location_overview['this_week_imports'] ?? 0) ?></h3>
              <p>This Week's Imports</p>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="kpi-card teal">
              <i class="ti ti-calendar-month icon"></i>
              <h3><?= number_format($location_overview['this_month_imports'] ?? 0) ?></h3>
              <p>This Month's Imports</p>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="kpi-card indigo">
              <i class="ti ti-calendar icon"></i>
              <h3><?= number_format($location_overview['this_year_imports'] ?? 0) ?></h3>
              <p>This Year's Imports</p>
            </div>
          </div>
        </div>

        <!-- Overview KPIs -->
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-building-bank me-2"></i>Declaration Office Overview</h3>
              <p>Comprehensive statistics for all declaration offices</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card blue">
              <i class="ti ti-building icon"></i>
              <h3><?= number_format($location_overview['unique_offices'] ?? 0) ?></h3>
              <p>Main Offices</p>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="kpi-card cyan">
              <i class="ti ti-file-import icon"></i>
              <h3><?= number_format($location_overview['total_imports'] ?? 0) ?></h3>
              <p>Total Imports</p>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="kpi-card green">
              <i class="ti ti-circle-check icon"></i>
              <h3><?= number_format($location_overview['cleared_imports'] ?? 0) ?></h3>
              <p>Cleared Imports</p>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="kpi-card orange">
              <i class="ti ti-clock icon"></i>
              <h3><?= number_format($location_overview['avg_clearance_days'] ?? 0, 1) ?></h3>
              <p>Avg Clearance Days</p>
            </div>
          </div>
        </div>

        <!-- Main Office + Sub Office Structure -->
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-building-community me-2"></i>Main Office & Sub Office Structure</h3>
              <p>Complete office hierarchy with import distribution</p>
            </div>
          </div>
        </div>

        <div class="row">
          <?php if (!empty($main_office_list) && is_array($main_office_list)): ?>
            <?php foreach ($main_office_list as $main_office): ?>
              <div class="col-xl-6 col-lg-12">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h5 class="mb-0">
                        <i class="ti ti-building-bank text-primary me-2"></i>
                        <strong><?= htmlspecialchars($main_office['main_location_name'] ?? 'N/A') ?></strong>
                      </h5>
                      <span class="badge bg-primary"><?= number_format($main_office['total_imports'] ?? 0) ?> Imports</span>
                    </div>

                    <div class="row mb-3">
                      <div class="col-4">
                        <div class="text-center">
                          <small class="text-muted">Sub Offices</small>
                          <h4 class="mb-0 text-info"><?= number_format($main_office['sub_office_count'] ?? 0) ?></h4>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="text-center">
                          <small class="text-muted">Total Imports</small>
                          <h4 class="mb-0 text-primary"><?= number_format($main_office['total_imports'] ?? 0) ?></h4>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="text-center">
                          <small class="text-muted">Cleared</small>
                          <h4 class="mb-0 text-success"><?= number_format($main_office['cleared_count'] ?? 0) ?></h4>
                        </div>
                      </div>
                    </div>

                    <!-- Sub Offices -->
                    <?php 
                    $sub_offices = array_filter($sub_office_breakdown ?? [], function($sub) use ($main_office) {
                      return $sub['main_office'] === $main_office['main_location_name'];
                    });
                    ?>

                    <?php if (!empty($sub_offices)): ?>
                      <div class="sub-offices-list">
                        <h6 class="text-muted mb-2"><i class="ti ti-building me-1"></i>Sub Offices:</h6>
                        <div class="table-responsive">
                          <table class="table table-sm table-hover mb-0">
                            <thead>
                              <tr>
                                <th>Sub Office</th>
                                <th>Total</th>
                                <th>Cleared</th>
                                <th>In Progress</th>
                                <th>Today</th>
                                <th>Week</th>
                                <th>Month</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($sub_offices as $sub): ?>
                                <tr>
                                  <td><strong><?= htmlspecialchars($sub['sub_office_name'] ?? 'N/A') ?></strong></td>
                                  <td><?= number_format($sub['import_count'] ?? 0) ?></td>
                                  <td><span class="badge bg-success"><?= number_format($sub['cleared_count'] ?? 0) ?></span></td>
                                  <td><span class="badge bg-warning"><?= number_format($sub['in_progress_count'] ?? 0) ?></span></td>
                                  <td><?= number_format($sub['today_count'] ?? 0) ?></td>
                                  <td><?= number_format($sub['week_count'] ?? 0) ?></td>
                                  <td><?= number_format($sub['month_count'] ?? 0) ?></td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    <?php else: ?>
                      <div class="alert alert-info mb-0">
                        <i class="ti ti-info-circle me-2"></i>No sub offices configured for this main office
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-info">
                <i class="ti ti-info-circle me-2"></i>No main office data available
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Office-wise Breakdown Cards -->
        <div class="row">
          <div class="col-12">
            <div class="section-header">
              <h3><i class="ti ti-chart-bar me-2"></i>Office-wise Import Distribution</h3>
              <p>Detailed breakdown by declaration office</p>
            </div>
          </div>
        </div>

        <div class="row">
          <?php if (!empty($declaration_office_analysis) && is_array($declaration_office_analysis)): ?>
            <?php 
            $office_colors = ['blue', 'green', 'orange', 'purple', 'cyan', 'teal', 'indigo', 'pink', 'amber', 'lime', 'sky', 'violet', 'rose', 'emerald', 'fuchsia'];
            foreach ($declaration_office_analysis as $index => $office): 
              $color = $office_colors[$index % count($office_colors)];
            ?>
              <div class="col-xl-4 col-md-6">
                <div class="kpi-card <?= $color ?>">
                  <i class="ti ti-building-bank icon"></i>
                  <h3><?= number_format($office['import_count'] ?? 0) ?></h3>
                  <p><?= htmlspecialchars($office['office_name']) ?></p>
                  <div style="display: flex; gap: 15px; margin-top: 10px; font-size: 0.85rem; flex-wrap: wrap;">
                    <span><i class="ti ti-circle-check"></i> <?= number_format($office['cleared_count'] ?? 0) ?> Cleared</span>
                    <span><i class="ti ti-progress"></i> <?= number_format($office['in_progress_count'] ?? 0) ?> In Progress</span>
                    <span><i class="ti ti-truck"></i> <?= number_format($office['in_transit_count'] ?? 0) ?> In Transit</span>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-info">
                <i class="ti ti-info-circle me-2"></i>No declaration office data available
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Office Distribution Chart (PIE/DONUT) -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title mb-3">
                  <i class="ti ti-chart-pie me-2"></i>Office-wise Import Distribution Chart
                </h4>
                
                <div class="chart-type-selector" id="officeDistributionSelector">
                  <button class="chart-type-btn active" onclick="changeOfficeDistributionChartType('pie')" id="office-dist-chart-pie">
                    <i class="ti ti-chart-pie me-1"></i>Pie
                  </button>
                  <button class="chart-type-btn" onclick="changeOfficeDistributionChartType('donut')" id="office-dist-chart-donut">
                    <i class="ti ti-chart-donut me-1"></i>Donut
                  </button>
                </div>
                
                <div dir="ltr">
                  <div id="officeDistributionChart" class="apex-charts" data-colors="#5b69bc,#35b8e0,#10c469,#fa5c7c,#e3eaef"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Office Performance Metrics -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title mb-3">
                  <i class="ti ti-trophy me-2"></i>Office Performance Metrics
                </h4>
                
                <div class="table-responsive">
                  <table class="table table-hover" id="officePerformanceTable">
                    <thead>
                      <tr>
                        <th>Office Name</th>
                        <th>Total Imports</th>
                        <th>Cleared</th>
                        <th>In Progress</th>
                        <th>Fast Clearance</th>
                        <th>Delayed</th>
                        <th>Clearance Rate</th>
                        <th>Avg Days</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($office_performance) && is_array($office_performance)): ?>
                        <?php foreach ($office_performance as $office): ?>
                          <tr>
                            <td><strong><?= htmlspecialchars($office['office_name'] ?? 'N/A') ?></strong></td>
                            <td><?= number_format($office['total_imports'] ?? 0) ?></td>
                            <td><span class="badge bg-success"><?= number_format($office['cleared_imports'] ?? 0) ?></span></td>
                            <td><span class="badge bg-warning"><?= number_format($office['in_progress_imports'] ?? 0) ?></span></td>
                            <td><span class="badge bg-info"><?= number_format($office['fast_clearance'] ?? 0) ?></span></td>
                            <td><span class="badge bg-danger"><?= number_format($office['delayed_clearance'] ?? 0) ?></span></td>
                            <td>
                              <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: <?= $office['clearance_rate'] ?? 0 ?>%">
                                  <?= number_format($office['clearance_rate'] ?? 0, 1) ?>%
                                </div>
                              </div>
                            </td>
                            <td><?= number_format($office['avg_clearance_days'] ?? 0, 1) ?> days</td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="8" class="text-center text-muted">No performance data available</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Clearance Time Analysis -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title mb-3">
                  <i class="ti ti-clock-hour-4 me-2"></i>Clearance Time Analysis by Office
                </h4>
                
                <div class="table-responsive">
                  <table class="table table-hover" id="officeClearanceTable">
                    <thead>
                      <tr>
                        <th>Office Name</th>
                        <th>Total Processed</th>
                        <th>Avg Days</th>
                        <th>Min Days</th>
                        <th>Max Days</th>
                        <th>≤ 3 Days</th>
                        <th>4-7 Days</th>
                        <th>> 7 Days</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($office_clearance_times) && is_array($office_clearance_times)): ?>
                        <?php foreach ($office_clearance_times as $office): ?>
                          <tr>
                            <td><strong><?= htmlspecialchars($office['office_name'] ?? 'N/A') ?></strong></td>
                            <td><?= number_format($office['total_imports'] ?? 0) ?></td>
                            <td><span class="badge bg-primary"><?= number_format($office['avg_days'] ?? 0, 1) ?></span></td>
                            <td><span class="badge bg-success"><?= number_format($office['min_days'] ?? 0) ?></span></td>
                            <td><span class="badge bg-danger"><?= number_format($office['max_days'] ?? 0) ?></span></td>
                            <td><?= number_format($office['within_3_days'] ?? 0) ?></td>
                            <td><?= number_format($office['within_7_days'] ?? 0) ?></td>
                            <td><?= number_format($office['over_7_days'] ?? 0) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="8" class="text-center text-muted">No clearance data available</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Office Time Period Breakdown -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title mb-3">
                  <i class="ti ti-calendar-stats me-2"></i>Office-wise Time Period Breakdown
                </h4>
                
                <div class="table-responsive">
                  <table class="table table-hover" id="officePeriodTable">
                    <thead>
                      <tr>
                        <th>Office Name</th>
                        <th>Today</th>
                        <th>This Week</th>
                        <th>This Month</th>
                        <th>This Year</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($declaration_office_analysis) && is_array($declaration_office_analysis)): ?>
                        <?php foreach ($declaration_office_analysis as $office): ?>
                          <tr>
                            <td><strong><?= htmlspecialchars($office['office_name'] ?? 'N/A') ?></strong></td>
                            <td><span class="badge bg-danger"><?= number_format($office['today_count'] ?? 0) ?></span></td>
                            <td><span class="badge" style="background: #5b69bc;"><?= number_format($office['week_count'] ?? 0) ?></span></td>
                            <td><span class="badge bg-info"><?= number_format($office['month_count'] ?? 0) ?></span></td>
                            <td><span class="badge bg-primary"><?= number_format($office['year_count'] ?? 0) ?></span></td>
                            <td><strong><?= number_format($office['import_count'] ?? 0) ?></strong></td>
                          </tr>
                        <?php endforeach; ?>
                        <tr class="grand-total-row">
                          <td><strong>GRAND TOTAL</strong></td>
                          <td><strong><?= number_format(array_sum(array_column($declaration_office_analysis, 'today_count'))) ?></strong></td>
                          <td><strong><?= number_format(array_sum(array_column($declaration_office_analysis, 'week_count'))) ?></strong></td>
                          <td><strong><?= number_format(array_sum(array_column($declaration_office_analysis, 'month_count'))) ?></strong></td>
                          <td><strong><?= number_format(array_sum(array_column($declaration_office_analysis, 'year_count'))) ?></strong></td>
                          <td><strong><?= number_format(array_sum(array_column($declaration_office_analysis, 'import_count'))) ?></strong></td>
                        </tr>
                      <?php else: ?>
                        <tr>
                          <td colspan="6" class="text-center text-muted">No data available</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- END DECLARATION OFFICE TAB -->

      <!-- ==================== CLIENT ANALYSIS TAB ==================== -->
      <div class="tab-pane fade" id="client-based" role="tabpanel">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title">Client Analysis - Coming Soon</h4>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>

<script src="<?= BASE_URL ?>/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
  'use strict';

  // Theme Toggle
  const html = document.documentElement;
  const themeBtn = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');

  function setTheme(theme) {
    html.setAttribute('data-bs-theme', theme);
    localStorage.setItem('theme', theme);
    themeIcon.className = theme === 'dark' ? 'ti ti-moon' : 'ti ti-sun';
  }

  const savedTheme = localStorage.getItem('theme') || 'light';
  setTheme(savedTheme);
  
  if (themeBtn) {
    themeBtn.addEventListener('click', () => {
      const currentTheme = html.getAttribute('data-bs-theme');
      setTheme(currentTheme === 'dark' ? 'light' : 'dark');
    });
  }

  // ==================== TRI PHASE TAB CHARTS ====================

  let triphaseChart = null;
  let triphaseChartType = 'bar';

  window.changeTriPhaseChartType = function(type) {
    triphaseChartType = type;
    document.querySelectorAll('#triphase .chart-type-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('triphase-chart-' + type).classList.add('active');
    createTriPhaseChart();
  };

  function createTriPhaseChart() {
    const ctx = document.querySelector('#triphaseChart');
    if (!ctx) return;

    if (triphaseChart) {
      triphaseChart.destroy();
    }

    const triphaseData = <?= json_encode([
      'phase1' => $triphase_overview['phase1_count'] ?? 0,
      'phase2' => $triphase_overview['phase2_count'] ?? 0,
      'phase3' => $triphase_overview['phase3_count'] ?? 0
    ]) ?>;

    let options = {};

    if (triphaseChartType === 'bar') {
      options = {
        series: [{
          name: 'Phase 1 (1-10)',
          data: [triphaseData.phase1]
        }, {
          name: 'Phase 2 (11-20)',
          data: [triphaseData.phase2]
        }, {
          name: 'Phase 3 (21+)',
          data: [triphaseData.phase3]
        }],
        chart: {
          type: 'bar',
          height: 350,
          stacked: false
        },
        colors: ['#10b981', '#f59e0b', '#ef4444'],
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: true
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Current Period'],
        },
        fill: {
          opacity: 1
        },
        legend: {
          position: 'bottom'
        }
      };
    } else {
      // Pie or Donut
      options = {
        series: [triphaseData.phase1, triphaseData.phase2, triphaseData.phase3],
        chart: {
          type: triphaseChartType === 'donut' ? 'donut' : 'pie',
          height: 350
        },
        labels: ['Phase 1 (1-10)', 'Phase 2 (11-20)', 'Phase 3 (21+)'],
        colors: ['#10b981', '#f59e0b', '#ef4444'],
        legend: {
          position: 'bottom',
          horizontalAlign: 'center'
        },
        dataLabels: {
          enabled: true,
          formatter: function(val, opts) {
            return opts.w.config.series[opts.seriesIndex];
          }
        },
        plotOptions: {
          pie: {
            donut: triphaseChartType === 'donut' ? {
              size: '65%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total',
                  fontSize: '22px',
                  fontWeight: 600,
                  formatter: function(w) {
                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                  }
                }
              }
            } : undefined
          }
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
      };
    }

    triphaseChart = new ApexCharts(ctx, options);
    triphaseChart.render();
  }

  $('button[data-bs-target="#triphase"]').on('shown.bs.tab', function() {
    setTimeout(() => {
      createTriPhaseChart();
    }, 100);
  });

  // ==================== DECLARATION OFFICE TAB CHARTS ====================

  let officeDistributionChart = null;
  let officeDistributionChartType = 'pie';

  window.changeOfficeDistributionChartType = function(type) {
    officeDistributionChartType = type;
    document.querySelectorAll('#officeDistributionSelector .chart-type-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('office-dist-chart-' + type).classList.add('active');
    createOfficeDistributionChart();
  };

  function createOfficeDistributionChart() {
    const ctx = document.querySelector('#officeDistributionChart');
    if (!ctx) return;

    if (officeDistributionChart) {
      officeDistributionChart.destroy();
    }

    const officeData = <?= json_encode($declaration_office_analysis ?? []) ?>;
    
    if (!officeData || officeData.length === 0) {
      ctx.innerHTML = '<div class="alert alert-info">No office data available</div>';
      return;
    }

    const labels = officeData.map(item => item.office_name);
    const series = officeData.map(item => parseInt(item.import_count || 0));

    const options = {
      series: series,
      chart: {
        type: officeDistributionChartType === 'donut' ? 'donut' : 'pie',
        height: 350
      },
      labels: labels,
      colors: ['#5b69bc', '#35b8e0', '#10c469', '#fa5c7c', '#e3eaef', '#f9c851', '#313a46', '#6c757d'],
      legend: {
        position: 'bottom',
        horizontalAlign: 'center'
      },
      dataLabels: {
        enabled: true,
        formatter: function(val, opts) {
          return opts.w.config.series[opts.seriesIndex];
        }
      },
      plotOptions: {
        pie: {
          donut: officeDistributionChartType === 'donut' ? {
            size: '65%',
            labels: {
              show: true,
              total: {
                show: true,
                label: 'Total',
                fontSize: '22px',
                fontWeight: 600,
                formatter: function(w) {
                  return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                }
              }
            }
          } : undefined
        }
      },
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: 200
          },
          legend: {
            position: 'bottom'
          }
        }
      }]
    };

    officeDistributionChart = new ApexCharts(ctx, options);
    officeDistributionChart.render();
  }

  // Initialize charts when tabs are shown
  $('button[data-bs-target="#location"]').on('shown.bs.tab', function() {
    setTimeout(() => {
      createOfficeDistributionChart();
      initializeDataTables();
    }, 100);
  });

  // ==================== DATATABLES INITIALIZATION ====================

  function initializeDataTables() {
    if (!$.fn.DataTable) return;

    // Office Performance Table
    if ($('#officePerformanceTable').length) {
      if ($.fn.DataTable.isDataTable('#officePerformanceTable')) {
        $('#officePerformanceTable').DataTable().destroy();
      }
      $('#officePerformanceTable').DataTable({
        pageLength: 10,
        order: [[1, 'desc']],
        responsive: true,
        language: {
          emptyTable: "No data available"
        }
      });
    }
    
    // Office Clearance Table
    if ($('#officeClearanceTable').length) {
      if ($.fn.DataTable.isDataTable('#officeClearanceTable')) {
        $('#officeClearanceTable').DataTable().destroy();
      }
      $('#officeClearanceTable').DataTable({
        pageLength: 10,
        order: [[2, 'asc']],
        responsive: true,
        language: {
          emptyTable: "No data available"
        }
      });
    }
    
    // Office Period Table
    if ($('#officePeriodTable').length) {
      if ($.fn.DataTable.isDataTable('#officePeriodTable')) {
        $('#officePeriodTable').DataTable().destroy();
      }
      $('#officePeriodTable').DataTable({
        pageLength: 10,
        order: [[5, 'desc']],
        responsive: true,
        language: {
          emptyTable: "No data available"
        }
      });
    }
  }

  // Export Dashboard
  $('#exportDashboardBtn').on('click', function() {
    window.location.href = '<?= APP_URL ?>/importdashboard/exportDashboard';
  });
});
</script>