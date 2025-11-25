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

  /* Header */
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
  }

  /* KPI Cards */
  .kpi-card {
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    color: white;
    box-shadow: var(--shadow-sm);
    transition: transform 0.3s;
    position: relative;
    overflow: hidden;
  }

  .kpi-card:hover {
    transform: translateY(-5px);
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

  /* Cards */
  .card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    margin-bottom: 20px;
  }

  .card-body {
    padding: 20px;
  }

  .header-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: var(--text-primary);
  }

  /* Table */
  #recentClientsTable { color: var(--text-primary); }

  #recentClientsTable thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white !important;
    padding: 12px;
    border: none;
  }

  #recentClientsTable tbody td {
    padding: 10px 12px;
    border-bottom: 1px solid var(--table-border);
    color: var(--text-primary);
  }

  #recentClientsTable tbody tr:hover {
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
</style>

<div class="page-content">
  <div class="page-container">

    <!-- Header -->
    <div class="dashboard-header d-flex justify-content-between align-items-center">
      <div>
        <h2><i class="ti ti-chart-bar me-2"></i> Client Dashboard</h2>
        <p class="text-muted mb-0">Real-time insights and analytics</p>
      </div>
      <div class="d-flex gap-2">
        <button class="theme-btn" id="themeToggle">
          <i class="ti ti-sun" id="themeIcon"></i>
        </button>
        <button class="btn btn-primary" id="exportDashboardBtn">
          <i class="ti ti-download me-1"></i> Export Excel
        </button>
        <a href="<?= APP_URL ?>/client" class="btn btn-secondary">
          <i class="ti ti-users me-1"></i> Clients
        </a>
      </div>
    </div>

    <!-- KPI Cards -->
    <div class="row">
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card blue">
          <i class="ti ti-users icon"></i>
          <h3><?= number_format($kpi_data['total_clients'] ?? 0) ?></h3>
          <p>Total Clients</p>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card green">
          <i class="ti ti-check icon"></i>
          <h3><?= number_format($kpi_data['active_clients'] ?? 0) ?></h3>
          <p>Active Clients</p>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card orange">
          <i class="ti ti-calendar-check icon"></i>
          <h3><?= number_format($kpi_data['this_month_registrations'] ?? 0) ?></h3>
          <p>This Month</p>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card red">
          <i class="ti ti-calendar-time icon"></i>
          <h3><?= number_format($kpi_data['today_registrations'] ?? 0) ?></h3>
          <p>Today</p>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card purple">
          <i class="ti ti-file-check icon"></i>
          <h3><?= number_format($kpi_data['verified_clients'] ?? 0) ?></h3>
          <p>Verified</p>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card cyan">
          <i class="ti ti-shield-check icon"></i>
          <h3><?= number_format($kpi_data['approved_clients'] ?? 0) ?></h3>
          <p>Approved</p>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card green">
          <i class="ti ti-file-text icon"></i>
          <h3><?= number_format($kpi_data['valid_contracts'] ?? 0) ?></h3>
          <p>Valid Contracts</p>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card red">
          <i class="ti ti-alert-circle icon"></i>
          <h3><?= number_format($kpi_data['expired_contracts'] ?? 0) ?></h3>
          <p>Expired</p>
        </div>
      </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row">
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title">Client Type Distribution</h4>
            <div dir="ltr">
              <div id="simple-donut" class="apex-charts" data-colors="#5b69bc,#35b8e0,#10c469,#f9c851,#fa5c7c"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title">Location Distribution</h4>
            <div dir="ltr">
              <div id="location-bar" class="apex-charts" data-colors="#5b69bc,#35b8e0,#10c469,#f9c851,#fa5c7c"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title">Payment Terms</h4>
            <div dir="ltr">
              <div id="simple-pie" class="apex-charts" data-colors="#5b69bc,#35b8e0,#10c469,#f9c851,#fa5c7c"></div>
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
            <h4 class="header-title">Monthly Registration Trend</h4>
            <div dir="ltr">
              <div id="line-column-mixed" class="apex-charts" data-colors="#5b69bc,#10c469"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title">Verification Status</h4>
            <div dir="ltr">
              <div id="multiple-radialbar" class="apex-charts" data-colors="#10c469,#f9c851,#fa5c7c"></div>
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
            <h4 class="header-title">Top Industries</h4>
            <div dir="ltr">
              <div id="industry-bar" class="apex-charts" data-colors="#5b69bc,#35b8e0,#10c469,#f9c851,#fa5c7c,#8f75da,#39afd1,#ff679b"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-6">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title">Phase Distribution</h4>
            <div dir="ltr">
              <div id="basic-polar-area" class="apex-charts" data-colors="#5b69bc,#35b8e0,#10c469,#fa5c7c,#f9c851,#39afd1"></div>
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
            <h4 class="header-title">Group Companies</h4>
            <div dir="ltr">
              <div id="gradient-donut" class="apex-charts" data-colors="#5b69bc,#35b8e0,#10c469,#f9c851,#fa5c7c"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-6">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title">Document Completion</h4>
            <div dir="ltr">
              <div id="basic-radialbar" class="apex-charts" data-colors="#5b69bc,#35b8e0,#10c469,#f9c851"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title">Recent Clients</h4>
            <div class="table-responsive">
              <table id="recentClientsTable" class="table table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Company</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Industry</th>
                    <th>Location</th>
                    <th>Contact</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($recent_clients as $client): ?>
                    <tr>
                      <td>#<?= $client['id'] ?></td>
                      <td><strong><?= htmlspecialchars($client['company_name']) ?></strong></td>
                      <td><span class="badge bg-primary"><?= htmlspecialchars($client['short_name']) ?></span></td>
                      <td>
                        <?php
                        $types = str_split($client['client_type']);
                        foreach ($types as $type) {
                          $label = $type == 'I' ? 'Import' : ($type == 'E' ? 'Export' : 'Local');
                          echo '<span class="badge bg-info me-1">' . $label . '</span>';
                        }
                        ?>
                      </td>
                      <td><?= htmlspecialchars($client['industry_name']) ?></td>
                      <td><?= htmlspecialchars($client['location_name']) ?></td>
                      <td><?= htmlspecialchars($client['contact_person'] ?: 'N/A') ?></td>
                      <td><?= htmlspecialchars($client['phone'] ?: 'N/A') ?></td>
                      <td><?= date('M d, Y', strtotime($client['created_at'])) ?></td>
                      <td>
                        <?php if ($client['display'] == 'Y'): ?>
                          <span class="badge bg-success">Active</span>
                        <?php else: ?>
                          <span class="badge bg-secondary">Inactive</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
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
$(function() {
  'use strict';

  // Theme
  const html = document.documentElement;
  const themeBtn = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');

  function setTheme(theme) {
    html.setAttribute('data-bs-theme', theme);
    localStorage.setItem('theme', theme);
    themeIcon.className = theme === 'dark' ? 'ti ti-moon' : 'ti ti-sun';
  }

  setTheme(localStorage.getItem('theme') || 'light');
  themeBtn.addEventListener('click', () => {
    setTheme(html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
  });

  // Colors
  const colors = ["#5b69bc", "#35b8e0", "#10c469", "#f9c851", "#fa5c7c", "#8f75da", "#39afd1", "#ff679b"];
  const isDark = () => html.getAttribute('data-bs-theme') === 'dark';
  const textColor = () => isDark() ? '#b8bbc5' : '#313a46';
  const gridColor = () => isDark() ? '#3a3d4f' : '#e9ecef';

  // Data
  const clientTypeData = <?= json_encode($client_type_distribution) ?>;
  const locationData = <?= json_encode($location_distribution) ?>;
  const paymentData = <?= json_encode($payment_term_distribution) ?>;
  const trendData = <?= json_encode($monthly_registration_trend) ?>;
  const verificationData = <?= json_encode($verification_status) ?>;
  const industryData = <?= json_encode($industry_distribution) ?>;
  const phaseData = <?= json_encode($phase_distribution) ?>;
  const groupData = <?= json_encode($group_company_distribution) ?>;
  const docData = <?= json_encode($document_completion_status) ?>;
  const totalClients = parseInt(verificationData.total_clients) || 1;
  const docTotal = parseInt(docData.total_clients) || 1;

  // DataTable
  $('#recentClientsTable').DataTable({
    order: [[0, 'desc']],
    pageLength: 10,
    responsive: true
  });

  // 1. Client Type - Donut
  new ApexCharts(document.querySelector("#simple-donut"), {
    series: [
      parseInt(clientTypeData.import_only) || 0,
      parseInt(clientTypeData.export_only) || 0,
      parseInt(clientTypeData.local_only) || 0,
      parseInt(clientTypeData.import_export) || 0,
      parseInt(clientTypeData.all_three) || 0
    ],
    chart: { height: 280, type: 'donut' },
    labels: ['Import', 'Export', 'Local', 'Import+Export', 'All'],
    colors: colors,
    legend: { position: 'bottom' },
    plotOptions: { pie: { donut: { size: '65%' } } },
    dataLabels: { enabled: true }
  }).render();

  // 2. Location - Bar
  new ApexCharts(document.querySelector("#location-bar"), {
    series: [{ name: 'Clients', data: locationData.map(i => parseInt(i.client_count)) }],
    chart: { height: 280, type: 'bar' },
    plotOptions: { bar: { horizontal: true, distributed: true } },
    dataLabels: { enabled: true },
    xaxis: { categories: locationData.map(i => i.location_name) },
    colors: colors
  }).render();

  // 3. Payment - Pie  
  new ApexCharts(document.querySelector("#simple-pie"), {
    series: paymentData.map(i => parseInt(i.client_count)),
    chart: { height: 280, type: 'pie' },
    labels: paymentData.map(i => i.payment_term),
    colors: colors,
    legend: { position: 'bottom' }
  }).render();

  // 4. Trend - Mixed (Line + Column)
  new ApexCharts(document.querySelector("#line-column-mixed"), {
    series: [
      { name: 'New Registrations', type: 'column', data: trendData.map(i => parseInt(i.client_count)) },
      { name: 'Cumulative', type: 'line', data: trendData.map((i, idx) => trendData.slice(0, idx + 1).reduce((sum, item) => sum + parseInt(item.client_count), 0)) }
    ],
    chart: { height: 350, type: 'line', stacked: false },
    stroke: { width: [0, 4], curve: 'smooth' },
    plotOptions: { bar: { columnWidth: '50%' } },
    colors: ['#5b69bc', '#10c469'],
    xaxis: { categories: trendData.map(i => i.month_name) },
    yaxis: [{ title: { text: 'New Clients' } }, { opposite: true, title: { text: 'Total' } }],
    legend: { position: 'top' }
  }).render();

  // 5. Verification - Radial Bar
  new ApexCharts(document.querySelector("#multiple-radialbar"), {
    series: [
      ((parseInt(verificationData.verified_and_approved) / totalClients) * 100).toFixed(0),
      ((parseInt(verificationData.verified_only) / totalClients) * 100).toFixed(0),
      ((parseInt(verificationData.not_verified) / totalClients) * 100).toFixed(0)
    ],
    chart: { height: 300, type: 'radialBar' },
    plotOptions: {
      radialBar: {
        dataLabels: {
          name: { fontSize: '12px' },
          value: { fontSize: '14px' },
          total: { show: true, label: 'Total', formatter: () => totalClients }
        }
      }
    },
    labels: ['Approved', 'Verified', 'Pending'],
    colors: ['#10c469', '#f9c851', '#fa5c7c']
  }).render();

  // 6. Industry - Bar
  new ApexCharts(document.querySelector("#industry-bar"), {
    series: [{ name: 'Clients', data: industryData.map(i => parseInt(i.client_count)) }],
    chart: { height: 350, type: 'bar' },
    plotOptions: { bar: { distributed: true, columnWidth: '50%' } },
    dataLabels: { enabled: false },
    xaxis: {
      categories: industryData.map(i => i.industry_name),
      labels: { rotate: -45, rotateAlways: true }
    },
    colors: colors
  }).render();

  // 7. Phase - Polar Area
  new ApexCharts(document.querySelector("#basic-polar-area"), {
    series: phaseData.map(i => parseInt(i.client_count)),
    chart: { height: 350, type: 'polarArea' },
    labels: phaseData.map(i => i.phase_name.substring(0, 15)),
    colors: colors,
    stroke: { width: 1, colors: ['#fff'] },
    fill: { opacity: 0.8 },
    legend: { position: 'bottom' }
  }).render();

  // 8. Group - Donut
  new ApexCharts(document.querySelector("#gradient-donut"), {
    series: groupData.map(i => parseInt(i.client_count)),
    chart: { height: 350, type: 'donut' },
    labels: groupData.map(i => i.group_name),
    colors: colors,
    legend: { position: 'bottom' },
    plotOptions: { pie: { donut: { size: '65%' } } },
    fill: {
      type: 'gradient',
      gradient: { shade: 'dark', type: 'vertical', gradientToColors: ['#667eea', '#764ba2', '#8f75da'] }
    }
  }).render();

  // 9. Document - Radial Bar
  new ApexCharts(document.querySelector("#basic-radialbar"), {
    series: [
      ((parseInt(docData.has_id_nat) / docTotal) * 100).toFixed(0),
      ((parseInt(docData.has_rccm) / docTotal) * 100).toFixed(0),
      ((parseInt(docData.has_import_export) / docTotal) * 100).toFixed(0),
      ((parseInt(docData.has_attestation) / docTotal) * 100).toFixed(0)
    ],
    chart: { height: 350, type: 'radialBar' },
    plotOptions: {
      radialBar: {
        dataLabels: {
          name: { fontSize: '12px' },
          value: { fontSize: '14px' },
          total: {
            show: true,
            label: 'Average',
            formatter: function(w) {
              return (w.globals.seriesTotals.reduce((a, b) => a + b, 0) / w.globals.seriesTotals.length).toFixed(0) + '%';
            }
          }
        }
      }
    },
    labels: ['ID/NAT', 'RCCM', 'Import/Export', 'Attestation'],
    colors: ['#5b69bc', '#35b8e0', '#10c469', '#f9c851']
  }).render();

  // Export to Excel using PhpSpreadsheet
  $('#exportDashboardBtn').on('click', function() {
    const btn = $(this);
    btn.prop('disabled', true).html('<i class="ti ti-loader"></i> Generating...');
    
    // Direct download - navigate to export URL
    window.location.href = '<?= APP_URL ?>/clientdashboard/exportDashboard';
    
    // Re-enable button after 2 seconds
    setTimeout(function() {
      btn.prop('disabled', false).html('<i class="ti ti-download me-1"></i> Export Excel');
    }, 2000);
  });
});
</script>