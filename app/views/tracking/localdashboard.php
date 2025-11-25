<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
  :root {
    --bg-primary: #f5f7fa;
    --bg-secondary: #ffffff;
    --text-primary: #2c3e50;
    --border-color: rgba(0, 0, 0, 0.08);
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  [data-bs-theme="dark"] {
    --bg-primary: #1a1d29;
    --bg-secondary: #252836;
    --text-primary: #e4e6eb;
    --border-color: rgba(255, 255, 255, 0.08);
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.3);
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

  .kpi-card {
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 20px;
    color: white;
    box-shadow: var(--shadow-sm);
    transition: transform 0.3s;
    position: relative;
  }

  .kpi-card:hover { transform: translateY(-5px); }
  .kpi-card.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
  .kpi-card.green { background: linear-gradient(135deg, #10c469 0%, #0e9f5a 100%); }
  .kpi-card.orange { background: linear-gradient(135deg, #f9c851 0%, #f7a842 100%); }
  .kpi-card.red { background: linear-gradient(135deg, #fa5c7c 0%, #f83e5e 100%); }
  .kpi-card.purple { background: linear-gradient(135deg, #5b69bc 0%, #3f4d96 100%); }
  .kpi-card.cyan { background: linear-gradient(135deg, #35b8e0 0%, #2a9dc7 100%); }
  .kpi-card.teal { background: linear-gradient(135deg, #02a8b5 0%, #1d8996 100%); }

  .kpi-card h3 {
    font-size: 2.8rem;
    font-weight: 700;
    margin: 0 0 10px 0;
  }

  .kpi-card p {
    margin: 0;
    font-size: 1rem;
    opacity: 0.95;
    font-weight: 500;
  }

  .kpi-card .icon {
    font-size: 3.5rem;
    opacity: 0.25;
    position: absolute;
    right: 20px;
    top: 20px;
  }

  .location-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    margin: 5px;
    background: rgba(255,255,255,0.2);
  }

  .card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    margin-bottom: 20px;
  }

  .header-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: var(--text-primary);
  }

  #recentTrackingsTable thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white !important;
    padding: 12px;
    border: none;
  }

  #recentTrackingsTable tbody td {
    padding: 10px 12px;
    color: var(--text-primary);
  }
</style>

<div class="page-content">
  <div class="page-container">

    <!-- Header -->
    <div class="dashboard-header d-flex justify-content-between align-items-center">
      <div>
        <h2><i class="ti ti-truck me-2"></i> Local Tracking Dashboard</h2>
        <p class="text-muted mb-0">Quick overview of local tracking activities</p>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-secondary" id="themeToggle">
          <i class="ti ti-sun" id="themeIcon"></i>
        </button>
        <button class="btn btn-success" id="exportDashboardBtn">
          <i class="ti ti-download me-1"></i> Export
        </button>
        <a href="<?= APP_URL ?>/local" class="btn btn-primary">
          <i class="ti ti-list me-1"></i> All Trackings
        </a>
      </div>
    </div>

    <!-- KPI Cards -->
    <div class="row">
      <!-- Total Files -->
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card blue">
          <i class="ti ti-files icon"></i>
          <h3><?= number_format($kpi_data['total_files'] ?? 0) ?></h3>
          <p>Total Files</p>
        </div>
      </div>

      <!-- Today -->
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card green">
          <i class="ti ti-calendar-time icon"></i>
          <h3><?= number_format($kpi_data['today_files'] ?? 0) ?></h3>
          <p>Today</p>
        </div>
      </div>

      <!-- This Week -->
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card orange">
          <i class="ti ti-calendar-week icon"></i>
          <h3><?= number_format($kpi_data['week_files'] ?? 0) ?></h3>
          <p>This Week</p>
        </div>
      </div>

      <!-- This Month -->
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card red">
          <i class="ti ti-calendar-check icon"></i>
          <h3><?= number_format($kpi_data['month_files'] ?? 0) ?></h3>
          <p>This Month</p>
        </div>
      </div>
    </div>

    <!-- Second Row KPIs -->
    <div class="row">
      <!-- This Year -->
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card purple">
          <i class="ti ti-calendar icon"></i>
          <h3><?= number_format($kpi_data['year_files'] ?? 0) ?></h3>
          <p>This Year</p>
        </div>
      </div>

      <!-- Avg CEE Days -->
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card cyan">
          <i class="ti ti-clock icon"></i>
          <h3><?= number_format(floatval($kpi_data['avg_cee_days'] ?? 0), 1) ?></h3>
          <p>Avg CEE Days</p>
        </div>
      </div>

      <!-- Total Capacity -->
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card teal">
          <i class="ti ti-weight icon"></i>
          <h3><?= number_format(floatval($kpi_data['total_capacity'] ?? 0), 1) ?></h3>
          <p>Total Capacity (T)</p>
        </div>
      </div>

      <!-- Total Bags -->
      <div class="col-xl-3 col-md-6">
        <div class="kpi-card orange">
          <i class="ti ti-package icon"></i>
          <h3><?= number_format($kpi_data['total_bags'] ?? 0) ?></h3>
          <p>Total Bags</p>
        </div>
      </div>
    </div>

    <!-- Top 3 Locations -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title"><i class="ti ti-map-pin me-2"></i>Top 3 Locations</h4>
            <div class="text-center py-3">
              <?php foreach ($top_locations as $index => $loc): ?>
                <div class="location-badge" style="background: <?= ['#667eea', '#10c469', '#f9c851'][$index] ?>; color: white;">
                  <strong><?= htmlspecialchars($loc['location_name']) ?></strong>: <?= number_format($loc['file_count']) ?> files
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row">
      <div class="col-xl-6">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title"><i class="ti ti-map-2 me-2"></i>Location Distribution</h4>
            <div id="location-bar" class="apex-charts"></div>
          </div>
        </div>
      </div>
      <div class="col-xl-6">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title"><i class="ti ti-users-group me-2"></i>Client Types</h4>
            <div id="client-pie" class="apex-charts"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title"><i class="ti ti-chart-line me-2"></i>Monthly Trend (Last 12 Months)</h4>
            <div id="monthly-chart" class="apex-charts"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row 3 -->
    <div class="row">
      <div class="col-xl-6">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title"><i class="ti ti-horse me-2"></i>Top 10 Horses</h4>
            <div id="horse-bar" class="apex-charts"></div>
          </div>
        </div>
      </div>
      <div class="col-xl-6">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title"><i class="ti ti-trailer me-2"></i>Top 10 Trailers</h4>
            <div id="trailer-bar" class="apex-charts"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Clients -->
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title"><i class="ti ti-trophy me-2"></i>Top 10 Clients</h4>
            <div id="client-bar" class="apex-charts"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Trackings Table -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title"><i class="ti ti-clock-hour-4 me-2"></i>Recent Trackings</h4>
            <div class="table-responsive">
              <table id="recentTrackingsTable" class="table table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Reference</th>
                    <th>Client</th>
                    <th>Location</th>
                    <th>Horse</th>
                    <th>Trailer</th>
                    <th>Capacity</th>
                    <th>Bags</th>
                    <th>CEE Status</th>
                    <th>Created</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($recent_trackings as $tracking): ?>
                    <tr>
                      <td><strong>#<?= $tracking['id'] ?></strong></td>
                      <td><?= htmlspecialchars($tracking['lt_reference'] ?: 'N/A') ?></td>
                      <td><?= htmlspecialchars($tracking['short_name'] ?: 'N/A') ?></td>
                      <td><?= htmlspecialchars($tracking['location_name'] ?: 'N/A') ?></td>
                      <td><?= htmlspecialchars($tracking['horse'] ?: 'N/A') ?></td>
                      <td><?= htmlspecialchars($tracking['trailer'] ?: 'N/A') ?></td>
                      <td><?= number_format(floatval($tracking['capacity_t'] ?? 0), 2) ?> T</td>
                      <td><?= number_format($tracking['bags'] ?? 0) ?></td>
                      <td>
                        <?php if ($tracking['cee_out']): ?>
                          <span class="badge bg-success">Done (<?= $tracking['cee_duration_days'] ?> days)</span>
                        <?php elseif ($tracking['cee_in']): ?>
                          <span class="badge bg-warning">In Progress</span>
                        <?php else: ?>
                          <span class="badge bg-secondary">Pending</span>
                        <?php endif; ?>
                      </td>
                      <td><?= date('M d, Y', strtotime($tracking['created_at'])) ?></td>
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

  // Theme Toggle
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

  // Data
  const locationData = <?= json_encode($location_distribution) ?>;
  const clientTypeData = <?= json_encode($client_type_distribution) ?>;
  const monthlyData = <?= json_encode($monthly_trend) ?>;
  const horseData = <?= json_encode($horse_performance) ?>;
  const trailerData = <?= json_encode($trailer_performance) ?>;
  const topClients = <?= json_encode($top_clients) ?>;

  // DataTable
  $('#recentTrackingsTable').DataTable({
    order: [[0, 'desc']],
    pageLength: 10,
    responsive: true
  });

  // 1. Location Bar
  new ApexCharts(document.querySelector("#location-bar"), {
    series: [{ name: 'Files', data: locationData.map(i => parseInt(i.tracking_count)) }],
    chart: { height: 350, type: 'bar' },
    plotOptions: { bar: { horizontal: true, distributed: true } },
    dataLabels: { enabled: true },
    xaxis: { categories: locationData.map(i => i.location_name) },
    colors: colors
  }).render();

  // 2. Client Type Pie
  new ApexCharts(document.querySelector("#client-pie"), {
    series: clientTypeData.map(i => parseInt(i.tracking_count)),
    chart: { height: 350, type: 'pie' },
    labels: clientTypeData.map(i => i.client_category),
    colors: colors,
    legend: { position: 'bottom' }
  }).render();

  // 3. Monthly Trend
  new ApexCharts(document.querySelector("#monthly-chart"), {
    series: [
      { name: 'Files', type: 'column', data: monthlyData.map(i => parseInt(i.tracking_count)) },
      { name: 'Capacity (T)', type: 'line', data: monthlyData.map(i => parseFloat(i.total_capacity || 0)) }
    ],
    chart: { height: 350, type: 'line' },
    stroke: { width: [0, 3], curve: 'smooth' },
    colors: ['#5b69bc', '#10c469'],
    xaxis: { categories: monthlyData.map(i => i.month_name) },
    yaxis: [
      { title: { text: 'Files' } },
      { opposite: true, title: { text: 'Capacity (T)' } }
    ]
  }).render();

  // 4. Horse Bar
  new ApexCharts(document.querySelector("#horse-bar"), {
    series: [{ name: 'Trips', data: horseData.map(i => parseInt(i.trip_count)) }],
    chart: { height: 350, type: 'bar' },
    plotOptions: { bar: { distributed: true } },
    xaxis: { categories: horseData.map(i => i.horse_name), labels: { rotate: -45 } },
    colors: colors
  }).render();

  // 5. Trailer Bar
  new ApexCharts(document.querySelector("#trailer-bar"), {
    series: [{ name: 'Trips', data: trailerData.map(i => parseInt(i.trip_count)) }],
    chart: { height: 350, type: 'bar' },
    plotOptions: { bar: { distributed: true } },
    xaxis: { categories: trailerData.map(i => i.trailer_name), labels: { rotate: -45 } },
    colors: colors
  }).render();

  // 6. Top Clients
  new ApexCharts(document.querySelector("#client-bar"), {
    series: [{ name: 'Files', data: topClients.map(i => parseInt(i.tracking_count)) }],
    chart: { height: 350, type: 'bar' },
    plotOptions: { bar: { distributed: true } },
    xaxis: { categories: topClients.map(i => i.short_name), labels: { rotate: -45 } },
    colors: colors
  }).render();

  // Export
  $('#exportDashboardBtn').on('click', function() {
    window.location.href = '<?= APP_URL ?>/localdashboard/exportDashboard';
  });
});
</script>