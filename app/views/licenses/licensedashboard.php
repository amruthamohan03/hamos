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

  .theme-btn:hover {
    background: var(--border-color);
  }

  /* Tabs Styling */
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

  .nav-tabs .nav-link:hover {
    background: var(--bg-primary);
  }

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

  .kpi-card h3 {
    font-size: 2.5rem;
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

  .card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    margin-bottom: 20px;
  }

  .card-body { padding: 20px; }

  .header-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: var(--text-primary);
  }

  .no-data-message {
    text-align: center;
    padding: 40px;
    color: var(--text-secondary);
  }

  /* Client Selection Card */
  .client-selection-card {
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
  }

  .client-selection-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 15px;
  }

  /* Client Details Container */
  #clientDetailsContainer {
    display: none;
  }

  #clientDetailsContainer.show {
    display: block;
  }

  /* Client Profile Header */
  .client-profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 40px;
    margin-bottom: 30px;
    color: white;
    position: relative;
    overflow: hidden;
  }

  .client-profile-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
  }

  .client-avatar-large {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
    border: 3px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
  }

  .client-profile-info h2 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 10px 0;
  }

  .client-profile-meta {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 20px;
  }

  .client-stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 30px;
  }

  .stat-card {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
  }

  .stat-card-label {
    font-size: 0.85rem;
    opacity: 0.9;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .stat-card-value {
    font-size: 2rem;
    font-weight: 700;
  }

  /* Info Cards Grid */
  .info-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }

  .info-card {
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 25px;
    transition: all 0.3s;
  }

  .info-card:hover {
    border-color: #667eea;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
    transform: translateY(-4px);
  }

  .info-card-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--border-color);
  }

  .info-card-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
  }

  .info-card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
  }

  .info-card-content {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background: var(--bg-primary);
    border-radius: 8px;
  }

  .info-item-label {
    font-size: 0.85rem;
    color: var(--text-secondary);
    font-weight: 500;
  }

  .info-item-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
  }

  .progress-item {
    margin-bottom: 15px;
  }

  .progress-item-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
  }

  .progress-item-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
  }

  .progress-item-value {
    font-size: 0.9rem;
    font-weight: 700;
    color: #667eea;
  }

  .progress {
    height: 10px;
    border-radius: 10px;
    background: var(--border-color);
  }

  .progress-bar {
    border-radius: 10px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
  }

  /* Action Buttons */
  .action-buttons-row {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 2px solid var(--border-color);
  }

  .btn-action {
    padding: 12px 30px;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* Modal Styles */
  .modal-content {
    background: var(--bg-secondary);
    color: var(--text-primary);
  }

  .modal-header {
    border-bottom: 1px solid var(--border-color);
  }

  .modal-footer {
    border-top: 1px solid var(--border-color);
  }

  #recentLicensesTable { 
    color: var(--text-primary);
    width: 100% !important;
  }

  #recentLicensesTable thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white !important;
    padding: 12px;
    border: none;
    font-weight: 600;
  }

  #recentLicensesTable tbody td {
    padding: 10px 12px;
    border-bottom: 1px solid var(--table-border);
    color: var(--text-primary);
  }

  #recentLicensesTable tbody tr:hover {
    background: var(--table-hover);
  }

  [data-bs-theme="dark"] .dataTables_wrapper .dataTables_length,
  [data-bs-theme="dark"] .dataTables_wrapper .dataTables_filter,
  [data-bs-theme="dark"] .dataTables_wrapper .dataTables_info,
  [data-bs-theme="dark"] .dataTables_wrapper .dataTables_paginate {
    color: var(--text-primary);
  }

  [data-bs-theme="dark"] .dataTables_wrapper input,
  [data-bs-theme="dark"] .dataTables_wrapper select {
    background: var(--bg-primary);
    color: var(--text-primary);
    border-color: var(--border-color);
  }

  [data-bs-theme="dark"] .page-link {
    background: var(--bg-primary);
    border-color: var(--border-color);
    color: var(--text-primary);
  }

  [data-bs-theme="dark"] .page-item.active .page-link {
    background: #667eea;
    border-color: #667eea;
  }

  .loading-spinner {
    text-align: center;
    padding: 60px;
  }

  .spinner-border {
    width: 3rem;
    height: 3rem;
    color: #667eea;
  }
</style>

<div class="page-content">
  <div class="page-container">

    <!-- Header -->
    <div class="dashboard-header d-flex justify-content-between align-items-center">
      <div>
        <h2><i class="ti ti-license me-2"></i> License Dashboard</h2>
        <p class="text-muted mb-0">Comprehensive license analytics and insights</p>
      </div>
      <div class="d-flex gap-2">
        <button class="theme-btn" id="themeToggle">
          <i class="ti ti-sun" id="themeIcon"></i>
        </button>
        <button class="btn btn-primary" id="exportDashboardBtn">
          <i class="ti ti-download me-1"></i> Export
        </button>
        <a href="<?= APP_URL ?>/license" class="btn btn-secondary">
          <i class="ti ti-file-certificate me-1"></i> Licenses
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
        <button class="nav-link" id="client-based-tab" data-bs-toggle="tab" data-bs-target="#client-based" type="button" role="tab">
          <i class="ti ti-users me-2"></i>Client Analysis
        </button>
      </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="dashboardTabsContent">
      
      <!-- OVERVIEW TAB -->
      <div class="tab-pane fade show active" id="overview" role="tabpanel">
        
        <!-- KPI Cards Row 1 -->
        <div class="row">
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card blue" data-bs-toggle="modal" data-bs-target="#totalLicensesModal">
              <i class="ti ti-file-certificate icon"></i>
              <h3><?= number_format($kpi_data['total_licenses'] ?? 0) ?></h3>
              <p>Total Licenses</p>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card green" data-bs-toggle="modal" data-bs-target="#activeLicensesModal">
              <i class="ti ti-circle-check icon"></i>
              <h3><?= number_format($kpi_data['active_licenses'] ?? 0) ?></h3>
              <p>Active Licenses</p>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card orange" data-bs-toggle="modal" data-bs-target="#monthLicensesModal">
              <i class="ti ti-calendar-month icon"></i>
              <h3><?= number_format($kpi_data['this_month_licenses'] ?? 0) ?></h3>
              <p>This Month</p>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card red" data-bs-toggle="modal" data-bs-target="#todayLicensesModal">
              <i class="ti ti-calendar-event icon"></i>
              <h3><?= number_format($kpi_data['today_licenses'] ?? 0) ?></h3>
              <p>Today</p>
            </div>
          </div>
        </div>

        <!-- KPI Cards Row 2 -->
        <div class="row">
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card purple" data-bs-toggle="modal" data-bs-target="#validLicensesModal">
              <i class="ti ti-clock-check icon"></i>
              <h3><?= number_format($kpi_data['valid_licenses'] ?? 0) ?></h3>
              <p>Valid Licenses</p>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card red" data-bs-toggle="modal" data-bs-target="#expiredLicensesModal">
              <i class="ti ti-clock-x icon"></i>
              <h3><?= number_format($kpi_data['expired_licenses'] ?? 0) ?></h3>
              <p>Expired</p>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card orange" data-bs-toggle="modal" data-bs-target="#expiringSoonModal">
              <i class="ti ti-alert-triangle icon"></i>
              <h3><?= number_format($kpi_data['expiring_soon_15'] ?? 0) ?></h3>
              <p>Expiring in 15 Days</p>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card cyan" data-bs-toggle="modal" data-bs-target="#fobValueModal">
              <i class="ti ti-currency-dollar icon"></i>
              <h3>$<?= number_format($kpi_data['total_fob_value'] ?? 0, 0) ?></h3>
              <p>Total FOB Value</p>
            </div>
          </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="row">
          <div class="col-xl-6">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title">Status Distribution</h4>
                <div dir="ltr">
                  <div id="status-donut" class="apex-charts"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-6">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title">Expiry Status</h4>
                <div dir="ltr">
                  <div id="expiry-radialbar" class="apex-charts"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row">
          <div class="col-xl-8">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title">Monthly License Trend & FOB Value</h4>
                <div dir="ltr">
                  <div id="license-trend" class="apex-charts"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-4">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title">Top 5 Banks</h4>
                <div dir="ltr">
                  <div id="bank-funnel" class="apex-charts"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts Row 3 -->
        <div class="row">
          <div class="col-xl-6">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title">Type of Goods Distribution</h4>
                <div dir="ltr">
                  <div id="goods-column" class="apex-charts"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-6">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title">Transport Mode Analysis</h4>
                <div dir="ltr">
                  <div id="transport-polar" class="apex-charts"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts Row 4 -->
        <div class="row">
          <div class="col-xl-6">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title">Weight Distribution by Goods Type</h4>
                <div dir="ltr">
                  <div id="weight-treemap" class="apex-charts"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-6">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title">FOB Value vs Weight Analysis</h4>
                <div dir="ltr">
                  <div id="value-weight-scatter" class="apex-charts"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts Row 5 -->
        <div class="row">
          <div class="col-xl-6">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title">Currency Distribution</h4>
                <div dir="ltr">
                  <div id="currency-donut" class="apex-charts"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-6">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title">Entry Point Distribution</h4>
                <div dir="ltr">
                  <div id="entry-post-column" class="apex-charts"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Licenses Table -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4 class="header-title mb-3">Recent Licenses</h4>
                <div class="table-responsive">
                  <table id="recentLicensesTable" class="table table-hover table-striped w-100">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>License #</th>
                        <th>Client</th>
                        <th>Bank</th>
                        <th>Invoice #</th>
                        <th>FOB Value</th>
                        <th>Weight</th>
                        <th>Applied</th>
                        <th>Expiry</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($recent_licenses) && is_array($recent_licenses)): ?>
                        <?php foreach ($recent_licenses as $license): ?>
                          <tr>
                            <td><strong>#<?= htmlspecialchars($license['id'] ?? '') ?></strong></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($license['license_number'] ?? 'N/A') ?></span></td>
                            <td><?= htmlspecialchars($license['client_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($license['bank_name'] ?? 'N/A') ?></td>
                            <td><span class="badge bg-info"><?= htmlspecialchars($license['invoice_number'] ?? 'N/A') ?></span></td>
                            <td><strong>$<?= number_format($license['fob_declared'] ?? 0, 2) ?></strong></td>
                            <td><?= number_format($license['weight'] ?? 0, 2) ?> <?= htmlspecialchars($license['unit_name'] ?? 'KG') ?></td>
                            <td><?= !empty($license['license_applied_date']) ? date('M d, Y', strtotime($license['license_applied_date'])) : 'N/A' ?></td>
                            <td><?= !empty($license['license_expiry_date']) ? date('M d, Y', strtotime($license['license_expiry_date'])) : 'N/A' ?></td>
                            <td>
                              <?php
                              $status_class = [
                                'ACTIVE' => 'success',
                                'INACTIVE' => 'secondary',
                                'ANNULATED' => 'danger',
                                'MODIFIED' => 'warning'
                              ];
                              $status = $license['status'] ?? 'INACTIVE';
                              $class = $status_class[$status] ?? 'info';
                              ?>
                              <span class="badge bg-<?= $class ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="10" class="text-center text-muted">No recent licenses found</td>
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
      <!-- END OVERVIEW TAB -->

      <!-- CLIENT ANALYSIS TAB -->
      <div class="tab-pane fade" id="client-based" role="tabpanel">
        
        <!-- Client KPIs -->
        <div class="row">
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card blue">
              <i class="ti ti-users icon"></i>
              <h3><?= number_format($client_stats['total_clients'] ?? 0) ?></h3>
              <p>Total Clients</p>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card green">
              <i class="ti ti-user-check icon"></i>
              <h3><?= number_format($client_stats['active_clients'] ?? 0) ?></h3>
              <p>Active Clients</p>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card purple">
              <i class="ti ti-building icon"></i>
              <h3><?= number_format($client_stats['verified_clients'] ?? 0) ?></h3>
              <p>Verified Clients</p>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="kpi-card orange">
              <i class="ti ti-license icon"></i>
              <h3><?= number_format($client_stats['clients_with_licenses'] ?? 0) ?></h3>
              <p>Clients with Licenses</p>
            </div>
          </div>
        </div>

        <!-- Client Selection Card -->
        <div class="client-selection-card">
          <label for="clientDropdown" class="client-selection-title">Select Client</label>
          <select id="clientDropdown" class="form-select form-select-lg">
            <option value="">-- Choose a Client --</option>
            <?php if (!empty($client_details) && is_array($client_details)): ?>
              <?php foreach ($client_details as $client): ?>
                <option value="<?= $client['id'] ?>" 
                        data-client='<?= json_encode($client) ?>'>
                  <?= htmlspecialchars($client['company_name']) ?> (<?= htmlspecialchars($client['short_name']) ?>)
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <!-- Client Details Container -->
        <div id="clientDetailsContainer">
          <div id="clientDetailsContent"></div>
        </div>

      </div>
      <!-- END CLIENT ANALYSIS TAB -->

    </div>

  </div>
</div>

<!-- Modals -->
<div class="modal fade" id="totalLicensesModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ti ti-file-certificate me-2"></i>All Licenses</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover" id="allLicensesTable"></table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="activeLicensesModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ti ti-circle-check me-2"></i>Active Licenses</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover" id="activeLicensesTable"></table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="monthLicensesModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ti ti-calendar-month me-2"></i>This Month's Licenses</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover" id="monthLicensesTable"></table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="todayLicensesModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ti ti-calendar-event me-2"></i>Today's Licenses</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover" id="todayLicensesTable"></table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="validLicensesModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ti ti-clock-check me-2"></i>Valid Licenses</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover" id="validLicensesTable"></table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="expiredLicensesModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ti ti-clock-x me-2"></i>Expired Licenses</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover" id="expiredLicensesTable"></table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="expiringSoonModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ti ti-alert-triangle me-2"></i>Licenses Expiring in 15 Days</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover" id="expiringSoonTable"></table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="fobValueModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ti ti-currency-dollar me-2"></i>FOB Value Breakdown</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover" id="fobValueTable"></table>
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

  // Client Selection Handler (Simple Dropdown - No Animation)
  $('#clientDropdown').on('change', function() {
    const selectedOption = $(this).find('option:selected');
    const clientData = selectedOption.data('client');
    
    if (clientData) {
      displayClientDetails(clientData);
    } else {
      $('#clientDetailsContainer').removeClass('show').hide();
    }
  });

  // Display Client Details Function (No Animation)
  function displayClientDetails(client) {
    const clientTypes = client.client_type.split('');
    let typeLabels = clientTypes.map(type => {
      if (type === 'I') return '<span class="badge bg-primary me-1">Import</span>';
      if (type === 'E') return '<span class="badge bg-success me-1">Export</span>';
      if (type === 'L') return '<span class="badge bg-warning me-1">Local</span>';
      return '';
    }).join('');

    const statusBadge = client.display === 'Y' 
      ? '<span class="badge bg-success">Active</span>' 
      : '<span class="badge bg-danger">Inactive</span>';

    const avgLicenseValue = client.total_licenses > 0 
      ? (client.total_fob_value / client.total_licenses).toFixed(2) 
      : '0.00';

    let html = `
      <!-- Client Profile Header -->
      <div class="client-profile-header">
        <div class="d-flex align-items-start gap-4">
          <div class="client-avatar-large">
            ${client.short_name.substring(0, 2).toUpperCase()}
          </div>
          <div class="client-profile-info flex-grow-1">
            <h2>${client.company_name}</h2>
            <div class="client-profile-meta">
              <span class="badge bg-dark">${client.short_name}</span>
              ${typeLabels}
              ${statusBadge}
            </div>
            
            <div class="client-stats-row">
              <div class="stat-card">
                <div class="stat-card-label">Total Licenses</div>
                <div class="stat-card-value">${Number(client.total_licenses).toLocaleString()}</div>
              </div>
              <div class="stat-card">
                <div class="stat-card-label">Active Licenses</div>
                <div class="stat-card-value">${Number(client.active_licenses).toLocaleString()}</div>
              </div>
              <div class="stat-card">
                <div class="stat-card-label">Success Rate</div>
                <div class="stat-card-value">${client.success_rate}%</div>
              </div>
              <div class="stat-card">
                <div class="stat-card-label">Total FOB Value</div>
                <div class="stat-card-value">$${Number(client.total_fob_value).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
              </div>
              <div class="stat-card">
                <div class="stat-card-label">Total Weight</div>
                <div class="stat-card-value">${Number(client.total_weight).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} <small>KG</small></div>
              </div>
              <div class="stat-card">
                <div class="stat-card-label">Avg License Value</div>
                <div class="stat-card-value">$${Number(avgLicenseValue).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Info Cards Grid -->
      <div class="info-cards-grid">
        
        <!-- Performance Metrics Card -->
        <div class="info-card">
          <div class="info-card-header">
            <div class="info-card-icon">
              <i class="ti ti-chart-bar"></i>
            </div>
            <h4 class="info-card-title">Performance Metrics</h4>
          </div>
          <div class="info-card-content">
            <div class="progress-item">
              <div class="progress-item-header">
                <span class="progress-item-label">Success Rate</span>
                <span class="progress-item-value">${client.success_rate}%</span>
              </div>
              <div class="progress">
                <div class="progress-bar" style="width: ${client.success_rate}%"></div>
              </div>
            </div>
            <div class="info-item">
              <span class="info-item-label">Total Licenses</span>
              <span class="info-item-value">${Number(client.total_licenses).toLocaleString()}</span>
            </div>
            <div class="info-item">
              <span class="info-item-label">Active Licenses</span>
              <span class="info-item-value" style="color: #10c469;">${Number(client.active_licenses).toLocaleString()}</span>
            </div>
            <div class="info-item">
              <span class="info-item-label">Inactive Licenses</span>
              <span class="info-item-value" style="color: #fa5c7c;">${Number(client.total_licenses - client.active_licenses).toLocaleString()}</span>
            </div>
          </div>
        </div>

        <!-- Financial Overview Card -->
        <div class="info-card">
          <div class="info-card-header">
            <div class="info-card-icon">
              <i class="ti ti-currency-dollar"></i>
            </div>
            <h4 class="info-card-title">Financial Overview</h4>
          </div>
          <div class="info-card-content">
            <div class="info-item">
              <span class="info-item-label">Total FOB Value</span>
              <span class="info-item-value" style="color: #667eea;">$${Number(client.total_fob_value).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
            </div>
            <div class="info-item">
              <span class="info-item-label">Average per License</span>
              <span class="info-item-value">$${Number(avgLicenseValue).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
            </div>
            <div class="info-item">
              <span class="info-item-label">Payment Terms</span>
              <span class="info-item-value">${client.payment_term || 'Not Specified'}</span>
            </div>
            <div class="info-item">
              <span class="info-item-label">Total Weight</span>
              <span class="info-item-value">${Number(client.total_weight).toLocaleString(undefined, {minimumFractionDigits: 2})} KG</span>
            </div>
          </div>
        </div>

        <!-- Contact Information Card -->
        <div class="info-card">
          <div class="info-card-header">
            <div class="info-card-icon">
              <i class="ti ti-address-book"></i>
            </div>
            <h4 class="info-card-title">Contact Information</h4>
          </div>
          <div class="info-card-content">
            <div class="info-item">
              <span class="info-item-label">Contact Person</span>
              <span class="info-item-value">${client.contact_person || 'Not Available'}</span>
            </div>
            <div class="info-item">
              <span class="info-item-label">Email</span>
              <span class="info-item-value" style="font-size: 0.85rem;">${client.email || 'Not Available'}</span>
            </div>
            <div class="info-item">
              <span class="info-item-label">Last License Date</span>
              <span class="info-item-value">${client.last_license_date ? new Date(client.last_license_date).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'}) : 'No licenses yet'}</span>
            </div>
            <div class="info-item">
              <span class="info-item-label">Client Status</span>
              <span class="info-item-value">${client.display === 'Y' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'}</span>
            </div>
          </div>
        </div>
    `;

    // Transport Mode Breakdown
    if (client.transport_breakdown && client.transport_breakdown.length > 0) {
      html += `
        <div class="info-card">
          <div class="info-card-header">
            <div class="info-card-icon">
              <i class="ti ti-truck"></i>
            </div>
            <h4 class="info-card-title">Transport Modes</h4>
          </div>
          <div class="info-card-content">
      `;
      client.transport_breakdown.forEach(transport => {
        html += `
            <div class="info-item">
              <span class="info-item-label">${transport.transport_name}</span>
              <span class="info-item-value">${transport.license_count} <small>licenses</small></span>
            </div>
        `;
      });
      html += `
          </div>
        </div>
      `;
    }

    // Goods Type Breakdown
    if (client.goods_breakdown && client.goods_breakdown.length > 0) {
      html += `
        <div class="info-card">
          <div class="info-card-header">
            <div class="info-card-icon">
              <i class="ti ti-package"></i>
            </div>
            <h4 class="info-card-title">Type of Goods</h4>
          </div>
          <div class="info-card-content">
      `;
      client.goods_breakdown.forEach(goods => {
        html += `
            <div class="info-item">
              <span class="info-item-label">${goods.goods_name}</span>
              <span class="info-item-value">${goods.license_count} <small>licenses</small></span>
            </div>
        `;
      });
      html += `
          </div>
        </div>
      `;
    }

    // Bank Breakdown
    if (client.bank_breakdown && client.bank_breakdown.length > 0) {
      html += `
        <div class="info-card">
          <div class="info-card-header">
            <div class="info-card-icon">
              <i class="ti ti-building-bank"></i>
            </div>
            <h4 class="info-card-title">Banking Partners</h4>
          </div>
          <div class="info-card-content">
      `;
      client.bank_breakdown.forEach(bank => {
        html += `
            <div class="info-item">
              <span class="info-item-label">${bank.bank_name}</span>
              <span class="info-item-value">${bank.license_count} <small>licenses</small></span>
            </div>
        `;
      });
      html += `
          </div>
        </div>
      `;
    }

    // Payment Method Breakdown
    if (client.payment_breakdown && client.payment_breakdown.length > 0) {
      html += `
        <div class="info-card">
          <div class="info-card-header">
            <div class="info-card-icon">
              <i class="ti ti-credit-card"></i>
            </div>
            <h4 class="info-card-title">Payment Methods</h4>
          </div>
          <div class="info-card-content">
      `;
      client.payment_breakdown.forEach(payment => {
        html += `
            <div class="info-item">
              <span class="info-item-label">${payment.payment_method}</span>
              <span class="info-item-value">${payment.license_count} <small>licenses</small></span>
            </div>
        `;
      });
      html += `
          </div>
        </div>
      `;
    }

    html += `
      </div>

      <!-- Action Buttons (NO EDIT BUTTON) -->
      <div class="action-buttons-row">
        <a href="<?= APP_URL ?>/license?client_id=${client.id}" class="btn btn-primary btn-action">
          <i class="ti ti-eye"></i>
          View All Licenses
        </a>
        <button class="btn btn-info btn-action" onclick="exportClientData(${client.id})">
          <i class="ti ti-download"></i>
          Export Data
        </button>
      </div>
    `;

    $('#clientDetailsContent').html(html);
    $('#clientDetailsContainer').addClass('show').show();
  }

  // Chart Colors
  const statusColors = ['#10c469', '#5b69bc', '#fa5c7c', '#f9c851'];
  const expiryColors = ['#10c469', '#f9c851', '#fa5c7c'];
  const trendColors = ['#5b69bc', '#10c469', '#fa5c7c'];
  const bankColors = ['#ff6b6b', '#ee5a6f', '#f06595', '#cc5de8', '#845ef7'];
  const goodsColors = ['#667eea', '#35b8e0', '#10c469', '#f9c851', '#fa5c7c', '#8f75da', '#39afd1', '#ff679b'];
  const transportColors = ['#20c997', '#17a2b8', '#007bff', '#6610f2', '#6f42c1'];
  const currencyColors = ['#2ecc71', '#27ae60', '#16a085', '#f39c12', '#f1c40f'];
  const weightColors = ['#1abc9c', '#16a085', '#27ae60', '#2ecc71', '#3498db'];
  const scatterColor = '#3498db';
  const entryColors = ['#e74c3c', '#e67e22', '#f39c12', '#f1c40f', '#2ecc71', '#1abc9c', '#3498db', '#9b59b6'];

  // Parse Data
  function parseData(data, defaultValue = []) {
    try {
      return Array.isArray(data) ? data : defaultValue;
    } catch (e) {
      console.error('Data parsing error:', e);
      return defaultValue;
    }
  }

  // Overview Tab Data
  const statusData = <?= json_encode($status_distribution ?? ['active' => 0, 'inactive' => 0, 'annulated' => 0, 'modified' => 0]) ?>;
  const bankData = parseData(<?= json_encode($bank_distribution ?? []) ?>);
  const expiryData = <?= json_encode($expiry_status ?? ['total_licenses' => 0, 'valid' => 0, 'expiring_soon' => 0, 'expired' => 0]) ?>;
  const trendData = parseData(<?= json_encode($monthly_trend ?? []) ?>);
  const goodsData = parseData(<?= json_encode($goods_distribution ?? []) ?>);
  const transportData = parseData(<?= json_encode($transport_distribution ?? []) ?>);
  const currencyData = parseData(<?= json_encode($currency_distribution ?? []) ?>);
  const weightData = parseData(<?= json_encode($weight_distribution ?? []) ?>);
  const scatterData = parseData(<?= json_encode($value_weight_scatter ?? []) ?>);
  const entryPostData = parseData(<?= json_encode($entry_post_distribution ?? []) ?>);
  const totalLicenses = parseInt(expiryData.total_licenses) || 1;

  // Helper function to render chart or show no data message
  function renderChartOrNoData(selector, chartConfig, data, minDataPoints = 0) {
    const element = document.querySelector(selector);
    if (!element) return;

    const hasData = Array.isArray(data) ? data.length > minDataPoints : Object.keys(data).length > 0;
    
    if (hasData) {
      try {
        new ApexCharts(element, chartConfig).render();
      } catch (e) {
        console.error('Chart render error for ' + selector, e);
        element.innerHTML = '<div class="no-data-message">Error loading chart</div>';
      }
    } else {
      element.innerHTML = '<div class="no-data-message"><i class="ti ti-chart-pie" style="font-size: 3rem; opacity: 0.3;"></i><p class="mt-2">No data available</p></div>';
    }
  }

  // Initialize DataTables
  try {
    $('#recentLicensesTable').DataTable({
      order: [[0, 'desc']],
      pageLength: 10,
      responsive: true,
      language: {
        emptyTable: "No licenses available in the system",
        zeroRecords: "No matching licenses found"
      },
      columnDefs: [
        { targets: [5, 6], type: 'num' }
      ]
    });
  } catch (e) {
    console.error('DataTable initialization error:', e);
  }

  // Modal Data Loading
  function loadModalData(type) {
    $.ajax({
      url: '<?= APP_URL ?>/licensedashboard/getModalData',
      method: 'POST',
      data: { type: type },
      dataType: 'json',
      success: function(response) {
        if (response.success && response.data) {
          const tableId = type + 'Table';
          const $table = $('#' + tableId);
          
          if ($.fn.DataTable.isDataTable('#' + tableId)) {
            $('#' + tableId).DataTable().destroy();
          }
          
          let html = '<thead><tr>';
          html += '<th>License #</th><th>Client</th><th>Bank</th><th>FOB Value</th><th>Expiry Date</th><th>Status</th>';
          html += '</tr></thead><tbody>';
          
          response.data.forEach(function(item) {
            const statusClass = item.status === 'ACTIVE' ? 'success' : (item.status === 'EXPIRED' ? 'danger' : 'warning');
            html += '<tr>';
            html += '<td><span class="badge bg-primary">' + item.license_number + '</span></td>';
            html += '<td>' + item.client_name + '</td>';
            html += '<td>' + item.bank_name + '</td>';
            html += '<td>$' + parseFloat(item.fob_declared).toLocaleString('en-US', {minimumFractionDigits: 2}) + '</td>';
            html += '<td>' + item.license_expiry_date + '</td>';
            html += '<td><span class="badge bg-' + statusClass + '">' + item.status + '</span></td>';
            html += '</tr>';
          });
          
          html += '</tbody>';
          $table.html(html);
          
          $table.DataTable({
            pageLength: 10,
            responsive: true,
            order: [[0, 'desc']]
          });
        }
      },
      error: function() {
        const tableId = type + 'Table';
        $('#' + tableId).html('<tr><td colspan="6" class="text-center">Error loading data</td></tr>');
      }
    });
  }

  // Attach modal loading events
  $('#totalLicensesModal').on('show.bs.modal', function() { loadModalData('allLicenses'); });
  $('#activeLicensesModal').on('show.bs.modal', function() { loadModalData('activeLicenses'); });
  $('#monthLicensesModal').on('show.bs.modal', function() { loadModalData('monthLicenses'); });
  $('#todayLicensesModal').on('show.bs.modal', function() { loadModalData('todayLicenses'); });
  $('#validLicensesModal').on('show.bs.modal', function() { loadModalData('validLicenses'); });
  $('#expiredLicensesModal').on('show.bs.modal', function() { loadModalData('expiredLicenses'); });
  $('#expiringSoonModal').on('show.bs.modal', function() { loadModalData('expiringSoon'); });
  $('#fobValueModal').on('show.bs.modal', function() { loadModalData('fobValue'); });

  // OVERVIEW TAB CHARTS
  // 1. Status - Donut
  renderChartOrNoData("#status-donut", {
    series: [
      parseInt(statusData.active) || 0,
      parseInt(statusData.inactive) || 0,
      parseInt(statusData.annulated) || 0,
      parseInt(statusData.modified) || 0
    ],
    chart: { height: 320, type: 'donut' },
    labels: ['Active', 'Inactive', 'Annulated', 'Modified'],
    colors: statusColors,
    legend: { position: 'bottom', fontSize: '13px' },
    plotOptions: { 
      pie: { 
        donut: { 
          size: '65%',
          labels: {
            show: true,
            total: {
              show: true,
              label: 'Total',
              fontSize: '16px',
              fontWeight: 600,
              formatter: () => {
                const total = parseInt(statusData.active) + parseInt(statusData.inactive) + 
                              parseInt(statusData.annulated) + parseInt(statusData.modified);
                return total;
              }
            }
          }
        } 
      } 
    },
    dataLabels: { enabled: true, style: { fontSize: '12px', fontWeight: 'bold' } }
  }, statusData);

  // 2. Expiry - Radial Bar
  renderChartOrNoData("#expiry-radialbar", {
    series: [
      Math.round((parseInt(expiryData.valid) / totalLicenses) * 100),
      Math.round((parseInt(expiryData.expiring_soon) / totalLicenses) * 100),
      Math.round((parseInt(expiryData.expired) / totalLicenses) * 100)
    ],
    chart: { height: 320, type: 'radialBar' },
    plotOptions: {
      radialBar: {
        hollow: { size: '55%' },
        dataLabels: {
          name: { fontSize: '14px', fontWeight: 600 },
          value: { fontSize: '16px', fontWeight: 700, formatter: (val) => val + '%' },
          total: { 
            show: true, 
            label: 'Total',
            fontSize: '14px',
            fontWeight: 600,
            formatter: () => totalLicenses 
          }
        }
      }
    },
    labels: ['Valid', 'Expiring Soon', 'Expired'],
    colors: expiryColors
  }, expiryData);

  // 3. Monthly Trend - Mixed
  if (trendData.length > 0) {
    renderChartOrNoData("#license-trend", {
      series: [
        { 
          name: 'License Count', 
          type: 'column', 
          data: trendData.map(i => parseInt(i.license_count)) 
        },
        { 
          name: 'FOB Value ($)', 
          type: 'area', 
          data: trendData.map(i => parseFloat(i.total_fob_value || 0)) 
        },
        { 
          name: 'Cumulative', 
          type: 'line', 
          data: trendData.map((i, idx) => trendData.slice(0, idx + 1).reduce((sum, item) => sum + parseInt(item.license_count), 0)) 
        }
      ],
      chart: { height: 350, type: 'line', stacked: false, toolbar: { show: true } },
      stroke: { width: [0, 3, 4], curve: 'smooth' },
      plotOptions: { bar: { columnWidth: '50%' } },
      fill: {
        opacity: [0.85, 0.35, 1],
        gradient: {
          inverseColors: false,
          shade: 'light',
          type: "vertical",
          opacityFrom: 0.85,
          opacityTo: 0.55,
          stops: [0, 100, 100, 100]
        }
      },
      colors: trendColors,
      xaxis: { 
        categories: trendData.map(i => i.month_name),
        labels: { rotate: -45 }
      },
      yaxis: [
        { title: { text: 'License Count' }, seriesName: 'License Count' },
        { opposite: true, title: { text: 'FOB Value ($)' }, seriesName: 'FOB Value ($)' }
      ],
      legend: { position: 'top', horizontalAlign: 'left' },
      tooltip: {
        shared: true,
        intersect: false,
        y: {
          formatter: function (y, { seriesIndex }) {
            if (seriesIndex === 1) return "$" + y.toFixed(2);
            return y;
          }
        }
      }
    }, trendData);
  } else {
    document.querySelector("#license-trend").innerHTML = '<div class="no-data-message"><i class="ti ti-chart-line" style="font-size: 3rem; opacity: 0.3;"></i><p class="mt-2">No trend data available</p></div>';
  }

  // 4. Bank - Funnel
  if (bankData.length > 0) {
    const topBanks = bankData.slice(0, 5);
    renderChartOrNoData("#bank-funnel", {
      series: [{ 
        name: 'Licenses', 
        data: topBanks.map(i => parseInt(i.license_count)) 
      }],
      chart: { type: 'bar', height: 320 },
      plotOptions: {
        bar: {
          borderRadius: 8,
          horizontal: true,
          barHeight: '80%',
          isFunnel: true,
          distributed: true
        }
      },
      dataLabels: { 
        enabled: true, 
        formatter: (val, opt) => opt.w.globals.labels[opt.dataPointIndex] + ': ' + val,
        style: { fontSize: '11px', fontWeight: 'bold', colors: ['#fff'] }
      },
      xaxis: { categories: topBanks.map(i => i.bank_name) },
      colors: bankColors,
      legend: { show: false }
    }, bankData);
  } else {
    document.querySelector("#bank-funnel").innerHTML = '<div class="no-data-message"><i class="ti ti-building-bank" style="font-size: 3rem; opacity: 0.3;"></i><p class="mt-2">No bank data available</p></div>';
  }

  // 5. Goods - Column
  if (goodsData.length > 0) {
    renderChartOrNoData("#goods-column", {
      series: [{ name: 'Licenses', data: goodsData.map(i => parseInt(i.license_count)) }],
      chart: { height: 350, type: 'bar', toolbar: { show: false } },
      plotOptions: { bar: { borderRadius: 8, distributed: true, columnWidth: '60%' } },
      dataLabels: { enabled: false },
      xaxis: {
        categories: goodsData.map(i => i.goods_name),
        labels: { 
          rotate: -45, 
          rotateAlways: true,
          style: { fontSize: '10px' }
        }
      },
      colors: goodsColors,
      legend: { show: false }
    }, goodsData);
  } else {
    document.querySelector("#goods-column").innerHTML = '<div class="no-data-message"><i class="ti ti-package" style="font-size: 3rem; opacity: 0.3;"></i><p class="mt-2">No goods data available</p></div>';
  }

  // 6. Transport - Polar
  if (transportData.length > 0) {
    renderChartOrNoData("#transport-polar", {
      series: transportData.map(i => parseInt(i.license_count)),
      chart: { height: 350, type: 'polarArea' },
      labels: transportData.map(i => i.transport_name),
      colors: transportColors,
      stroke: { width: 2, colors: ['#fff'] },
      fill: { opacity: 0.85 },
      legend: { position: 'bottom', fontSize: '12px' },
      yaxis: { show: false }
    }, transportData);
  } else {
    document.querySelector("#transport-polar").innerHTML = '<div class="no-data-message"><i class="ti ti-truck" style="font-size: 3rem; opacity: 0.3;"></i><p class="mt-2">No transport data available</p></div>';
  }

  // 7. Currency - Donut
  if (currencyData.length > 0) {
    renderChartOrNoData("#currency-donut", {
      series: currencyData.map(i => parseInt(i.license_count)),
      chart: { height: 350, type: 'donut' },
      labels: currencyData.map(i => i.currency_name),
      colors: currencyColors,
      legend: { position: 'bottom', fontSize: '13px' },
      plotOptions: { pie: { donut: { size: '65%' } } },
      dataLabels: { enabled: true, style: { fontSize: '12px', fontWeight: 'bold' } }
    }, currencyData);
  } else {
    document.querySelector("#currency-donut").innerHTML = '<div class="no-data-message"><i class="ti ti-currency-dollar" style="font-size: 3rem; opacity: 0.3;"></i><p class="mt-2">No currency data available</p></div>';
  }

  // 8. Weight - Treemap
  if (weightData.length > 0) {
    renderChartOrNoData("#weight-treemap", {
      series: [{ 
        data: weightData.map(i => ({ 
          x: i.goods_name, 
          y: parseFloat(i.total_weight) 
        })) 
      }],
      chart: { height: 350, type: 'treemap', toolbar: { show: false } },
      colors: weightColors,
      plotOptions: {
        treemap: {
          distributed: true,
          enableShades: false
        }
      },
      dataLabels: { 
        enabled: true,
        style: { fontSize: '11px' },
        formatter: (text, op) => [text, op.value.toFixed(2) + ' KG']
      }
    }, weightData);
  } else {
    document.querySelector("#weight-treemap").innerHTML = '<div class="no-data-message"><i class="ti ti-weight" style="font-size: 3rem; opacity: 0.3;"></i><p class="mt-2">No weight data available</p></div>';
  }

  // 9. Scatter
  if (scatterData.length > 0) {
    renderChartOrNoData("#value-weight-scatter", {
      series: [{ 
        name: 'Licenses', 
        data: scatterData.map(i => [parseFloat(i.weight), parseFloat(i.fob_declared)]) 
      }],
      chart: { 
        height: 350, 
        type: 'scatter', 
        zoom: { enabled: true, type: 'xy' },
        toolbar: { show: true }
      },
      xaxis: { 
        title: { text: 'Weight (KG)' },
        tickAmount: 10,
        labels: { formatter: (val) => val.toFixed(0) }
      },
      yaxis: { 
        title: { text: 'FOB Value ($)' },
        labels: { formatter: (val) => '$' + val.toFixed(0) }
      },
      colors: [scatterColor],
      markers: { size: 6 },
      tooltip: {
        y: { formatter: (val) => '$' + val.toFixed(2) }
      }
    }, scatterData);
  } else {
    document.querySelector("#value-weight-scatter").innerHTML = '<div class="no-data-message"><i class="ti ti-chart-dots" style="font-size: 3rem; opacity: 0.3;"></i><p class="mt-2">No scatter data available</p></div>';
  }

  // 10. Entry Post - Column
  if (entryPostData.length > 0) {
    renderChartOrNoData("#entry-post-column", {
      series: [{ name: 'Licenses', data: entryPostData.map(i => parseInt(i.license_count)) }],
      chart: { height: 350, type: 'bar', toolbar: { show: false } },
      plotOptions: { bar: { borderRadius: 8, columnWidth: '60%', distributed: true } },
      dataLabels: { enabled: true, style: { fontSize: '11px' } },
      xaxis: { 
        categories: entryPostData.map(i => i.entry_post_name),
        labels: { rotate: -45, style: { fontSize: '11px' } }
      },
      colors: entryColors,
      legend: { show: false }
    }, entryPostData);
  } else {
    document.querySelector("#entry-post-column").innerHTML = '<div class="no-data-message"><i class="ti ti-map-pin" style="font-size: 3rem; opacity: 0.3;"></i><p class="mt-2">No entry post data available</p></div>';
  }

  // Export Dashboard (Using PhpSpreadsheet on backend)
  $('#exportDashboardBtn').on('click', function() {
    window.location.href = '<?= APP_URL ?>/licensedashboard/exportDashboard';
  });
});

// Export Client Data Function (Using PhpSpreadsheet on backend)
function exportClientData(clientId) {
  window.location.href = '<?= APP_URL ?>/licensedashboard/exportClientData?client_id=' + clientId;
}
</script>