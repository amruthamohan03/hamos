<!-- include any head / css you already have -->
<link href="<?= BASE_URL ?>/assets/pages/css/license_styles.css" rel="stylesheet" type="text/css">

<style>
  /* ===== DATATABLE STYLING ===== */
  .dataTables_wrapper .dataTables_info {
    float: left;
  }
  .dataTables_wrapper .dataTables_paginate {
    float: right;
    text-align: right;
  }
  
  /* ===== BUTTON STYLING ===== */
  .btn-export-all {
    background: #28a745 !important;
    color: white !important;
    border: none !important;
    padding: 8px 20px !important;
    border-radius: 5px !important;
    font-weight: 500 !important;
    transition: all 0.3s !important;
    box-shadow: none !important;
  }
  
  .btn-export-all:hover {
    background: #218838 !important;
    color: white !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4) !important;
  }
  
  /* ===== VALIDATION STYLING ===== */
  .text-danger {
    color: #dc3545;
    font-weight: bold;
  }
  
  .is-invalid {
    border-color: #dc3545 !important;
  }
  
  .invalid-feedback {
    display: none;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
  }
  
  .is-invalid ~ .invalid-feedback {
    display: block;
  }
  
  /* ===== ACTION BUTTON STYLING ===== */
  .btn-view {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
  }
  .btn-view:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
  }
  
  .btn-export {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
  }
  .btn-export:hover {
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
  }
  
  /* ===== STATISTICS CARDS ===== */
  .stats-card {
    border: none;
    border-radius: 15px;
    transition: transform 0.3s, box-shadow 0.3s;
    overflow: hidden;
    cursor: pointer;
    min-height: 120px;
  }
  
  .stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  }
  
  .stats-card.active-filter {
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
    transform: scale(1.05);
  }
  
  .stats-card-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
  .stats-card-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
  .stats-card-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
  .stats-card-4 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; }
  .stats-card-5 { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; }
  .stats-card-6 { background: linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%); color: white; }
  .stats-card-7 { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); color: white; }
  
  .stats-card .card-body {
    padding: 20px 15px;
    position: relative;
  }
  
  .stats-icon {
    font-size: 2.5rem;
    opacity: 0.3;
    position: absolute;
    right: 15px;
    top: 15px;
  }
  
  .stats-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 5px;
  }
  
  .stats-label {
    font-size: 0.75rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  /* Responsive adjustments for 7 cards */
  @media (min-width: 1400px) {
    .stats-col {
      flex: 0 0 auto;
      width: 14.285714%; /* 100% / 7 cards */
    }
  }
  
  @media (max-width: 1399px) {
    .stats-card .card-body {
      padding: 15px 10px;
    }
    .stats-value {
      font-size: 1.75rem;
    }
    .stats-icon {
      font-size: 2rem;
    }
  }
  
  /* ===== ACCORDION STYLING ===== */
  .accordion-button {
    font-weight: 600;
    background-color: #f8f9fa;
  }
  
  .accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }
  
  .accordion-button:not(.collapsed)::after {
    filter: brightness(0) invert(1);
  }
  
  .accordion-item {
    border: none;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }
  
  .accordion-body {
    background: #ffffff;
  }
  
  /* ===== MODAL STYLING ===== */
  .modal-content {
    border: none;
    border-radius: 15px;
    overflow: hidden;
  }
  
  .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 20px 30px;
  }
  
  .modal-header .btn-close {
    filter: brightness(0) invert(1);
  }
  
  .detail-row {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s;
  }
  
  .detail-row:hover {
    background: #f8f9fa;
  }
  
  .detail-row:last-child {
    border-bottom: none;
  }
  
  .detail-label {
    font-weight: 600;
    color: #667eea;
    font-size: 0.9rem;
    margin-bottom: 5px;
  }
  
  .detail-value {
    color: #2d3748;
    font-size: 1rem;
    font-weight: 500;
  }
  
  .detail-icon {
    color: #667eea;
    margin-right: 8px;
  }

  /* ===== CARD SHADOWS ===== */
  .card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: none;
    border-radius: 15px;
  }

  /* ===== STATUS BADGE STYLING ===== */
  .badge {
    padding: 6px 12px;
    font-weight: 500;
    letter-spacing: 0.5px;
  }

  /* ===== FORM SECTION HEADERS ===== */
  .section-header h5 {
    color: #667eea;
    font-size: 1.1rem;
    font-weight: 600;
  }

  .section-header {
    position: relative;
    padding-bottom: 10px;
  }

  .section-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 2px;
  }

  .form-section {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 25px;
    margin-bottom: 25px;
  }

  .form-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
    margin-bottom: 0;
  }
  
  /* ===== FORM INPUT STYLING ===== */
  .form-control, .form-select {
    height: 38px;
  }
  
  input[type="file"].form-control {
    padding: 6px 12px;
  }
  
  /* ===== DESTINATION/ORIGIN WITH ADD BUTTON ===== */
  .input-with-button {
    display: flex;
    gap: 8px;
    align-items: center;
  }
  
  .input-with-button .form-select {
    flex: 1;
  }
  
  .btn-add-origin {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    white-space: nowrap;
    height: 38px;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  
  .btn-add-origin:hover {
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
  }
  
  .btn-add-origin i {
    font-size: 1.1rem;
  }
  
  /* ===== MODAL LIST ITEMS ===== */
  .expired-license-item,
  .expiring-license-item,
  .incomplete-license-item {
    padding: 15px;
    border-bottom: 1px solid #e9ecef;
    transition: background 0.2s;
  }
  
  .expired-license-item:hover,
  .expiring-license-item:hover,
  .incomplete-license-item:hover {
    background: #f8f9fa;
  }
  
  .expired-license-item:last-child,
  .expiring-license-item:last-child,
  .incomplete-license-item:last-child {
    border-bottom: none;
  }
  
  .days-expired-badge {
    font-size: 1.2rem;
    padding: 8px 15px;
    background: #dc3545;
    color: white;
  }
  
  .days-badge {
    font-size: 1.2rem;
    padding: 8px 15px;
  }
  
  .days-critical { background: #dc3545; color: white; }
  .days-warning { background: #ffc107; color: #000; }
  .days-notice { background: #17a2b8; color: white; }
  
  .missing-fields-list {
    margin-top: 10px;
  }
  
  .missing-field-badge {
    display: inline-block;
    background: #ffc107;
    color: #000;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 0.85rem;
    margin: 3px;
    font-weight: 500;
  }
  
  .badge-required { background: #dc3545; color: white; }
  .badge-optional { background: #6c757d; color: white; }
  
  /* ===== DATATABLE STYLING ===== */
  .dataTables_wrapper {
    padding: 20px 0;
  }
  
  .dataTables_wrapper .dataTables_length {
    float: left;
    margin-bottom: 15px;
  }
  
  .dataTables_wrapper .dataTables_filter {
    float: right;
    margin-bottom: 15px;
  }
  
  .dataTables_wrapper .dataTables_filter input {
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 5px 10px;
    margin-left: 5px;
  }
  
  .dataTables_wrapper .dataTables_length select {
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 5px 10px;
    margin: 0 5px;
  }
  
  table.dataTable thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    border: none;
    padding: 12px 8px;
  }
  
  table.dataTable tbody tr {
    transition: background 0.2s;
  }
  
  table.dataTable tbody tr:hover {
    background: #f8f9fa;
  }
  
  table.dataTable tbody td {
    padding: 10px 8px;
    vertical-align: middle;
  }
  
  .dataTables_wrapper .dataTables_paginate {
    padding-top: 15px;
  }
  
  .dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 5px 12px;
    margin: 0 2px;
    border-radius: 5px;
    border: 1px solid #ddd;
    background: white;
    transition: all 0.2s;
  }
  
  .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #667eea;
    color: white !important;
    border-color: #667eea;
  }
  
  .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #667eea;
    color: white !important;
    border-color: #667eea;
  }
</style>

<div class="page-content">
  <div class="page-container">
    <div class="row">
      <div class="col-12">
        
        <!-- Statistics Cards - 7 CARDS IN ONE ROW -->
        <div class="row mb-4">
          <!-- Card 1: Total Licenses -->
          <div class="col-xl col-lg-3 col-md-4 col-sm-6 mb-3 stats-col">
            <div class="card stats-card stats-card-1 shadow-sm filter-card" data-filter="all">
              <div class="card-body position-relative">
                <i class="ti ti-license stats-icon"></i>
                <div class="stats-value" id="totalLicenses">0</div>
                <div class="stats-label">Total</div>
              </div>
            </div>
          </div>
          
          <!-- Card 2: EXPIRED (WITH MODAL) -->
          <div class="col-xl col-lg-3 col-md-4 col-sm-6 mb-3 stats-col">
            <div class="card stats-card stats-card-2 shadow-sm filter-card" data-filter="expired" id="expiredCard">
              <div class="card-body position-relative">
                <i class="ti ti-calendar-x stats-icon"></i>
                <div class="stats-value" id="expiredLicenses">0</div>
                <div class="stats-label">Expired</div>
              </div>
            </div>
          </div>
          
          <!-- Card 3: Expiring Soon -->
          <div class="col-xl col-lg-3 col-md-4 col-sm-6 mb-3 stats-col">
            <div class="card stats-card stats-card-3 shadow-sm filter-card" data-filter="expiring" id="expiringCard">
              <div class="card-body position-relative">
                <i class="ti ti-clock-exclamation stats-icon"></i>
                <div class="stats-value" id="expiringLicenses">0</div>
                <div class="stats-label">Expiring</div>
              </div>
            </div>
          </div>
          
          <!-- Card 4: Incomplete -->
          <div class="col-xl col-lg-3 col-md-4 col-sm-6 mb-3 stats-col">
            <div class="card stats-card stats-card-4 shadow-sm filter-card" data-filter="incomplete" id="incompleteCard">
              <div class="card-body position-relative">
                <i class="ti ti-alert-triangle stats-icon"></i>
                <div class="stats-value" id="incompleteLicenses">0</div>
                <div class="stats-label">Incomplete</div>
              </div>
            </div>
          </div>
          
          <!-- Card 5: Annulated -->
          <div class="col-xl col-lg-3 col-md-4 col-sm-6 mb-3 stats-col">
            <div class="card stats-card stats-card-5 shadow-sm filter-card" data-filter="annulated">
              <div class="card-body position-relative">
                <i class="ti ti-ban stats-icon"></i>
                <div class="stats-value" id="annulatedLicenses">0</div>
                <div class="stats-label">Annulated</div>
              </div>
            </div>
          </div>
          
          <!-- Card 6: MODIFIED -->
          <div class="col-xl col-lg-3 col-md-4 col-sm-6 mb-3 stats-col">
            <div class="card stats-card stats-card-6 shadow-sm filter-card" data-filter="modified">
              <div class="card-body position-relative">
                <i class="ti ti-edit stats-icon"></i>
                <div class="stats-value" id="modifiedLicenses">0</div>
                <div class="stats-label">Modified</div>
              </div>
            </div>
          </div>
          
          <!-- Card 7: Prorogated -->
          <div class="col-xl col-lg-3 col-md-4 col-sm-6 mb-3 stats-col">
            <div class="card stats-card stats-card-7 shadow-sm filter-card" data-filter="prorogated">
              <div class="card-body position-relative">
                <i class="ti ti-clock stats-icon"></i>
                <div class="stats-value" id="prorogatedLicenses">0</div>
                <div class="stats-label">Prorogated</div>
              </div>
            </div>
          </div>
        </div>

        <!-- License Form Card -->
        <div class="card shadow-sm mb-4">
          <div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
            <h4 class="header-title mb-0"><i class="ti ti-license me-2"></i> <span id="formTitle">Add New License</span></h4>
            <div>
              <button type="button" class="btn btn-export-all me-2" id="exportAllBtn">
                <i class="ti ti-file-spreadsheet me-1"></i> Export All to Excel
              </button>
              <button type="button" class="btn btn-sm btn-secondary" id="resetFormBtn" style="display:none;">
                <i class="ti ti-plus"></i> Add New
              </button>
            </div>
          </div>

          <div class="card-body">
            <form id="licenseForm" method="post" enctype="multipart/form-data" novalidate>
              <input type="hidden" name="license_id" id="license_id" value="">
              <input type="hidden" name="action" id="formAction" value="insert">

              <div class="accordion" id="licenseAccordion">
                
                <!-- CREATE LICENSE ACCORDION -->
                <div class="accordion-item mb-3">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#createLicense">
                      <i class="ti ti-license me-2"></i> Create License
                    </button>
                  </h2>

                  <div id="createLicense" class="accordion-collapse collapse" data-bs-parent="#licenseAccordion">
                    <div class="accordion-body">

                      <!-- 1. BASIC INFORMATION SECTION -->
                      <div class="form-section mb-4" id="basicInfoSection">
                        <div class="section-header mb-3">
                          <h5 class="mb-0">
                            <i class="ti ti-info-circle me-2"></i>Basic Information
                          </h5>
                        </div>
                        <div class="row" id="basicInfoRow">
                          <div class="col-md-4 mb-3">
                            <label class="form-label">Kind <span class="text-danger">*</span></label>
                            <select name="kind_id" id="kind_id" class="form-select" required>
                              <option value="">-- Select Kind --</option>
                              <?php foreach ($kinds as $kind): ?>
                                <option value="<?= $kind['id'] ?>" data-kind-short="<?= htmlspecialchars($kind['kind_short_name']) ?>">
                                  <?= htmlspecialchars($kind['kind_name']) ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select a kind</div>
                          </div>

                          <div class="col-md-4 mb-3" id="bankField">
                            <label class="form-label">Bank <span class="text-danger">*</span></label>
                            <select name="bank_id" id="bank_id" class="form-select" required>
                              <option value="">-- Select Bank --</option>
                              <?php foreach ($banks as $bank): ?>
                                <option value="<?= $bank['id'] ?>"><?= htmlspecialchars($bank['bank_name']) ?></option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select a bank</div>
                          </div>

                          <div class="col-md-4 mb-3">
                            <label class="form-label">Client <span class="text-danger">*</span></label>
                            <select name="client_id" id="client_id" class="form-select" required>
                              <option value="">-- Select Client --</option>
                              <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['id'] ?>" data-client-short="<?= htmlspecialchars($client['short_name']) ?>">
                                  <?= htmlspecialchars($client['short_name']) ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select a client</div>
                          </div>

                          <div class="col-md-4 mb-3" id="licenseClearedByField">
                            <label class="form-label">License Cleared By <span class="text-danger">*</span></label>
                            <select name="license_cleared_by" id="license_cleared_by" class="form-select" required>
                              <option value="">-- Select Option --</option>
                              <?php foreach ($done_by_options as $opt): ?>
                                <option value="<?= $opt['id'] ?>"><?= htmlspecialchars($opt['done_by_name']) ?></option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select who cleared the license</div>
                          </div>

                          <div class="col-md-4 mb-3">
                            <label class="form-label">Type of Goods <span class="text-danger">*</span></label>
                            <select name="type_of_goods_id" id="type_of_goods_id" class="form-select" required>
                              <option value="">-- Select Type --</option>
                              <?php foreach ($type_of_goods as $type): ?>
                                <option value="<?= $type['id'] ?>" data-goods-short="<?= htmlspecialchars($type['goods_short_name']) ?>">
                                  <?= htmlspecialchars($type['goods_type']) ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select type of goods</div>
                          </div>

                          <div class="col-md-4 mb-3" id="weightField">
                            <label class="form-label">Weight <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="weight" id="weight" class="form-control" required>
                            <div class="invalid-feedback">Weight is required and must be positive</div>
                          </div>
                        </div>
                      </div>

                      <!-- 2. FINANCIAL INFORMATION SECTION -->
                      <div class="form-section mb-4" id="financialInfoSection">
                        <div class="section-header mb-3">
                          <h5 class="mb-0">
                            <i class="ti ti-currency-dollar me-2"></i>Financial Information
                          </h5>
                        </div>
                        <div class="row">
                          <div class="col-md-4 mb-3">
                            <label class="form-label">Unit of Measurement <span class="text-danger">*</span></label>
                            <select name="unit_of_measurement_id" id="unit_of_measurement_id" class="form-select" required>
                              <option value="">-- Select Unit --</option>
                              <?php foreach ($units as $unit): ?>
                                <option value="<?= $unit['id'] ?>"><?= htmlspecialchars($unit['unit_name']) ?></option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select unit of measurement</div>
                          </div>

                          <div class="col-md-4 mb-3">
                            <label class="form-label">Currency <span class="text-danger">*</span></label>
                            <select name="currency_id" id="currency_id" class="form-select" required>
                              <option value="">-- Select Currency --</option>
                              <?php foreach ($currencies as $currency): ?>
                                <option value="<?= $currency['id'] ?>">
                                  <?= htmlspecialchars($currency['currency_short_name']) ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select currency</div>
                          </div>

                          <div class="col-md-4 mb-3">
                            <label class="form-label">FOB Declared <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="fob_declared" id="fob_declared" class="form-control" required>
                            <div class="invalid-feedback">FOB Declared is required and must be positive</div>
                          </div>

                          <div class="col-md-4 mb-3">
                            <label class="form-label">Insurance</label>
                            <input type="number" step="0.01" min="0" name="insurance" id="insurance" class="form-control">
                            <div class="invalid-feedback">Insurance must be positive</div>
                          </div>

                          <div class="col-md-4 mb-3">
                            <label class="form-label">Freight</label>
                            <input type="number" step="0.01" min="0" name="freight" id="freight" class="form-control">
                            <div class="invalid-feedback">Freight must be positive</div>
                          </div>

                          <div class="col-md-4 mb-3">
                            <label class="form-label">Other Costs</label>
                            <input type="number" step="0.01" min="0" name="other_costs" id="other_costs" class="form-control">
                            <div class="invalid-feedback">Other costs must be positive</div>
                          </div>
                        </div>
                      </div>

                      <!-- 3. INVOICE & TRANSPORT INFORMATION SECTION -->
                      <div class="form-section mb-4" id="invoiceTransportSection">
                        <div class="section-header mb-3">
                          <h5 class="mb-0">
                            <i class="ti ti-file-invoice me-2"></i>Invoice & Transport Information
                          </h5>
                        </div>
                        <div class="row">
                          <div class="col-md-4 mb-3">
                            <label class="form-label">Transport Mode <span class="text-danger">*</span></label>
                            <select name="transport_mode_id" id="transport_mode_id" class="form-select" required>
                              <option value="">-- Select Transport Mode --</option>
                              <?php foreach ($transport_modes as $mode): ?>
                                <option value="<?= $mode['id'] ?>" data-transport-letter="<?= htmlspecialchars($mode['transport_letter']) ?>">
                                  <?= htmlspecialchars($mode['transport_mode_name']) ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select transport mode</div>
                          </div>

                          <div class="col-md-4 mb-3" id="invoiceNumberField">
                            <label class="form-label">Invoice Number <span class="text-danger">*</span></label>
                            <input type="text" name="invoice_number" id="invoice_number" class="form-control" required maxlength="50">
                            <div class="invalid-feedback">Invoice number is required</div>
                          </div>

                          <div class="col-md-4 mb-3" id="invoiceDateField">
                            <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control" required>
                            <div class="invalid-feedback">Invoice date is required and cannot be in the future</div>
                          </div>

                          <div class="col-md-4 mb-3" id="invoiceFileField">
                            <label class="form-label">Invoice File (PDF only)</label>
                            <input type="file" name="invoice_file" id="invoice_file" class="form-control" accept="application/pdf">
                            <small id="current_invoice_file" class="form-text text-muted"></small>
                            <div class="invalid-feedback">Only PDF files are allowed</div>
                          </div>

                          <div class="col-md-8 mb-3" id="supplierField">
                            <label class="form-label">Supplier/Buyer <span class="text-danger">*</span></label>
                            <input type="text" name="supplier" id="supplier" class="form-control" required maxlength="255">
                            <div class="invalid-feedback">Supplier/Buyer is required</div>
                          </div>
                        </div>
                      </div>

                      <!-- 4. LICENSE DETAILS SECTION -->
                      <div class="form-section mb-4" id="licenseDetailsSection">
                        <div class="section-header mb-3">
                          <h5 class="mb-0">
                            <i class="ti ti-calendar-event me-2"></i>License Details
                          </h5>
                        </div>
                        
                        <!-- ROW 1: License Applied Date, FSI/FSO, AUR -->
                        <div class="row">
                          <div class="col-md-4 mb-3" id="licenseAppliedDateField">
                            <label class="form-label">License Applied Date <span class="text-danger">*</span></label>
                            <input type="date" name="license_applied_date" id="license_applied_date" class="form-control" required>
                            <div class="invalid-feedback">License applied date is required</div>
                          </div>

                          <div class="col-md-4 mb-3" id="fsiField">
                            <label class="form-label">FSI/FSO</label>
                            <input type="text" name="fsi" id="fsi" class="form-control" maxlength="100">
                          </div>

                          <div class="col-md-4 mb-3" id="aurField">
                            <label class="form-label">AUR</label>
                            <input type="text" name="aur" id="aur" class="form-control" maxlength="100">
                          </div>
                        </div>

                        <!-- ROW 2: License Number, Entry Post, REF. COD -->
                        <div class="row">
                          <div class="col-md-4 mb-3">
                            <label class="form-label">License Number <span class="text-danger">*</span></label>
                            <input type="text" name="license_number" id="license_number" class="form-control" required maxlength="50">
                            <small class="form-text text-muted" id="licenseNumberHelp"></small>
                            <div class="invalid-feedback">License number is required and must be unique</div>
                          </div>

                          <div class="col-md-4 mb-3" id="entryPostField">
                            <label class="form-label">Entry Post <span class="text-danger">*</span></label>
                            <select name="entry_post_id" id="entry_post_id" class="form-select" required>
                              <option value="">-- Select Entry Post --</option>
                              <?php foreach ($entry_posts as $post): ?>
                                <option value="<?= $post['id'] ?>"><?= htmlspecialchars($post['transit_point_name']) ?></option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select an entry post</div>
                          </div>

                          <div class="col-md-4 mb-3" id="refCodField">
                            <label class="form-label">REF. COD</label>
                            <input type="text" name="ref_cod" id="ref_cod" class="form-control" maxlength="50">
                          </div>
                        </div>

                        <!-- ROW 3: License Validation Date, License Expiry Date, License File -->
                        <div class="row">
                          <div class="col-md-4 mb-3" id="licenseValidationDateField">
                            <label class="form-label">License Validation Date <span class="text-danger">*</span></label>
                            <input type="date" name="license_validation_date" id="license_validation_date" class="form-control" required>
                            <div class="invalid-feedback">Validation date must be ≥ applied date</div>
                          </div>

                          <div class="col-md-4 mb-3" id="licenseExpiryDateField">
                            <label class="form-label">License Expiry Date <span class="text-danger">*</span></label>
                            <input type="date" name="license_expiry_date" id="license_expiry_date" class="form-control" required>
                            <div class="invalid-feedback">Expiry date must be ≥ validation date</div>
                          </div>

                          <div class="col-md-4 mb-3" id="licenseFileField">
                            <label class="form-label">License File (PDF only)</label>
                            <input type="file" name="license_file" id="license_file" class="form-control" accept="application/pdf">
                            <small id="current_license_file" class="form-text text-muted"></small>
                            <div class="invalid-feedback">Only PDF files are allowed</div>
                          </div>
                        </div>
                      </div>

                      <!-- 5. PAYMENT INFORMATION SECTION WITH ADD ORIGIN BUTTON -->
                      <div class="form-section mb-4" id="paymentInfoSection">
                        <div class="section-header mb-3">
                          <h5 class="mb-0">
                            <i class="ti ti-credit-card me-2"></i>Payment Information
                          </h5>
                        </div>
                        <div class="row">
                          <div class="col-md-4 mb-3" id="paymentMethodField">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method_id" id="payment_method_id" class="form-select" required>
                              <option value="">-- Select Payment Method --</option>
                              <?php foreach ($payment_methods as $method): ?>
                                <option value="<?= $method['id'] ?>"><?= htmlspecialchars($method['payment_method_name']) ?></option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select a payment method</div>
                          </div>

                          <div class="col-md-4 mb-3" id="paymentSubtypeField">
                            <label class="form-label">Payment Subtype</label>
                            <select name="payment_subtype_id" id="payment_subtype_id" class="form-select">
                              <option value="">-- Select Payment Subtype --</option>
                              <?php foreach ($payment_subtypes as $subtype): ?>
                                <option value="<?= $subtype['id'] ?>"><?= htmlspecialchars($subtype['payment_subtype']) ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>

                          <div class="col-md-4 mb-3" id="destinationField">
                            <label class="form-label">Destination/Origin <span class="text-danger">*</span></label>
                            <div class="input-with-button">
                              <select name="destination_id" id="destination_id" class="form-select" required>
                                <option value="">-- Select Destination/Origin --</option>
                                <?php foreach ($origins as $origin): ?>
                                  <option value="<?= $origin['id'] ?>"><?= htmlspecialchars($origin['origin_name']) ?></option>
                                <?php endforeach; ?>
                              </select>
                              <button type="button" class="btn btn-add-origin" id="addOriginBtn">
                                <i class="ti ti-plus"></i> Add
                              </button>
                            </div>
                            <div class="invalid-feedback">Please select a destination/origin</div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>

              </div>

              <!-- Form Buttons -->
              <div class="row mt-4">
                <div class="col-12 text-end">
                  <button type="button" class="btn btn-secondary" id="cancelBtn">
                    <i class="ti ti-x me-1"></i> Cancel
                  </button>
                  <button type="submit" class="btn btn-primary ms-2" id="submitBtn">
                    <i class="ti ti-check me-1"></i> <span id="submitBtnText">Save License</span>
                  </button>
                </div>
              </div>

            </form>
          </div>
        </div>

        <!-- Licenses DataTable -->
        <div class="card shadow-sm">
          <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0"><i class="ti ti-list me-2"></i> License Records</h4>
            <button type="button" class="btn btn-sm btn-secondary" id="clearFilterBtn" style="display:none;">
              <i class="ti ti-filter-off me-1"></i> Clear Filter
            </button>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="licenseTable" class="table table-striped table-bordered dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    <th>License Number</th>
                    <th>Client</th>
                    <th>Bank</th>
                    <th>Invoice Number</th>
                    <th>Applied Date</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
<?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewLicenseModal" tabindex="-1" aria-labelledby="viewLicenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewLicenseModalLabel">
          <i class="ti ti-eye me-2"></i> License Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div id="modalDetailsContent">
          <!-- Details will be loaded here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Add Origin/Destination Modal -->
<div class="modal fade" id="addOriginModal" tabindex="-1" aria-labelledby="addOriginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addOriginModalLabel">
          <i class="ti ti-plus me-2"></i> Add New Destination/Origin
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addOriginForm">
          <div class="mb-3">
            <label for="new_origin_name" class="form-label">Destination/Origin Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="new_origin_name" name="origin_name" required maxlength="255" placeholder="Enter destination/origin name">
            <div class="invalid-feedback">Please enter a destination/origin name</div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Cancel
        </button>
        <button type="button" class="btn btn-primary" id="saveOriginBtn">
          <i class="ti ti-check me-1"></i> Save
        </button>
      </div>
    </div>
  </div>
</div>

<!-- EXPIRED Licenses Modal -->
<div class="modal fade" id="expiredLicensesModal" tabindex="-1" aria-labelledby="expiredLicensesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="expiredLicensesModalLabel">
          <i class="ti ti-calendar-x me-2"></i> Expired Licenses
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="expiredLicensesContent">
          <!-- Expired licenses will be loaded here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Expiring Licenses Modal -->
<div class="modal fade" id="expiringLicensesModal" tabindex="-1" aria-labelledby="expiringLicensesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="expiringLicensesModalLabel">
          <i class="ti ti-clock-exclamation me-2"></i> Licenses Expiring Within 30 Days
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="expiringLicensesContent">
          <!-- Expiring licenses will be loaded here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Incomplete Licenses Modal -->
<div class="modal fade" id="incompleteLicensesModal" tabindex="-1" aria-labelledby="incompleteLicensesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="incompleteLicensesModalLabel">
          <i class="ti ti-alert-triangle me-2"></i> Incomplete Licenses - Missing Fields
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="incompleteLicensesContent">
          <!-- Incomplete licenses will be loaded here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Include JavaScript from external file -->
<script src="<?= BASE_URL ?>/assets/pages/js/license_manager.js"></script>