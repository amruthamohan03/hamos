<!-- include any head / css you already have -->
<link href="<?= BASE_URL ?>/assets/pages/css/local_styles.css" rel="stylesheet" type="text/css">

<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
  .dataTables_wrapper .dataTables_info { float: left; }
  .dataTables_wrapper .dataTables_paginate { float: right; text-align: right; }
  
  /* Export Button Styling - Green */
  .dt-buttons { float: left; margin-bottom: 10px; }
  .buttons-excel, .btn-export-all {
    background: #28a745 !important; color: white !important; border: none !important;
    padding: 8px 20px !important; border-radius: 5px !important; font-weight: 500 !important;
    transition: all 0.3s !important; box-shadow: none !important;
  }
  .buttons-excel:hover, .btn-export-all:hover {
    background: #218838 !important; color: white !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4) !important;
  }
  
  /* Individual Export Button */
  .btn-export {
    background: #28a745; color: white; border: none;
  }
  .btn-export:hover {
    background: #218838; color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
  }
  
  /* Bulk Update Button - Orange */
  .btn-bulk-update {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    color: white; border: none; font-weight: 500;
  }
  .btn-bulk-update:hover {
    background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(243, 156, 18, 0.4);
  }
  .btn-bulk-update:disabled {
    background: #95a5a6 !important;
    cursor: not-allowed;
    opacity: 0.6;
  }
  
  /* Required field indicator */
  .text-danger { color: #dc3545; font-weight: bold; }
  
  /* Validation Error Styling */
  .is-invalid { border-color: #dc3545 !important; }
  .invalid-feedback { display: block; color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem; }
  
  /* Stats Cards - Smaller and Clickable */
  .stats-card {
    border: none; border-radius: 10px;
    transition: all 0.3s ease; overflow: hidden;
    background: white; border: 1px solid #e9ecef;
    cursor: pointer; position: relative;
  }
  .stats-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    border-color: #007bff;
  }
  .stats-card.active {
    border-color: #007bff; background: #f8f9ff;
    box-shadow: 0 4px 12px rgba(0,123,255,0.2);
  }
  .stats-card .card-body {
    padding: 15px; position: relative;
  }
  
  /* Stats Card Icons - Smaller */
  .stats-card-icon {
    width: 35px; height: 35px;
    border-radius: 8px; display: flex;
    align-items: center; justify-content: center;
    margin-bottom: 8px; float: left; margin-right: 10px;
  }
  .stats-card-icon i { font-size: 18px; color: white; }
  
  .icon-blue { background: linear-gradient(135deg, #4A90E2 0%, #357ABD 100%); }
  .icon-green { background: linear-gradient(135deg, #2ECC71 0%, #27AE60 100%); }
  .icon-orange { background: linear-gradient(135deg, #F39C12 0%, #E67E22 100%); }
  .icon-gray { background: linear-gradient(135deg, #95A5A6 0%, #7F8C8D 100%); }
  .icon-red { background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%); }
  .icon-purple { background: linear-gradient(135deg, #9B59B6 0%, #8E44AD 100%); }
  .icon-cyan { background: linear-gradient(135deg, #3498DB 0%, #2980B9 100%); }
  .icon-pink { background: linear-gradient(135deg, #E91E63 0%, #C2185B 100%); }
  .icon-teal { background: linear-gradient(135deg, #1ABC9C 0%, #16A085 100%); }
  .icon-indigo { background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%); }
  .icon-yellow { background: linear-gradient(135deg, #FFC107 0%, #FF9800 100%); }
  .icon-brown { background: linear-gradient(135deg, #795548 0%, #5D4037 100%); }
  
  .stats-value {
    font-size: 1.4rem; font-weight: 700; color: #2C3E50;
    margin-bottom: 2px; line-height: 1.2;
  }
  .stats-label {
    font-size: 0.75rem; color: #7F8C8D;
    font-weight: 500; line-height: 1.2;
  }
  
  /* Clear float */
  .stats-card .card-body::after {
    content: ""; display: table; clear: both;
  }
  
  /* Modal Styling */
  .modal-content { border: none; border-radius: 15px; overflow: hidden; }
  .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white; border: none; padding: 20px 30px;
  }
  .modal-header .btn-close { filter: brightness(0) invert(1); }
  .detail-row {
    padding: 15px; border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s;
  }
  .detail-row:hover { background: #f8f9fa; }
  .detail-row:last-child { border-bottom: none; }
  .detail-label {
    font-weight: 600; color: #667eea;
    font-size: 0.9rem; margin-bottom: 5px;
  }
  .detail-value { color: #2d3748; font-size: 1rem; font-weight: 500; }
  .detail-icon { color: #667eea; margin-right: 8px; }

  /* Auto-generated field styling */
  .auto-generated-field { background-color: #f8f9fa; cursor: not-allowed; }
  .readonly-field { background-color: #e9ecef; cursor: not-allowed; }
  
  /* Clearing Status Manual Override Indicator */
  .clearing-status-manual-mode {
    border-left: 4px solid #f39c12 !important;
  }
  .clearing-status-auto-mode {
    border-left: 4px solid #28a745 !important;
  }
  .status-mode-badge {
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 3px;
    margin-left: 8px;
    font-weight: 600;
  }
  .status-mode-badge.manual {
    background: #fff3cd;
    color: #856404;
  }
  .status-mode-badge.auto {
    background: #d4edda;
    color: #155724;
  }
  
  /* Remarks Section */
  .remarks-entry {
    border: 1px solid #dee2e6; border-radius: 8px;
    padding: 15px; margin-bottom: 15px; background: #f8f9fa;
    position: relative;
  }
  .remarks-entry .btn-remove { position: absolute; top: 10px; right: 10px; }
  
  .accordion-button:not(.collapsed) { background-color: #667eea; color: white; }
  
  /* Group Headers */
  .group-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white; padding: 10px 15px; border-radius: 8px;
    margin-bottom: 20px; margin-top: 20px;
  }
  .group-header i { margin-right: 10px; }
  
  /* Date Validation Error */
  .date-sequence-error {
    border-color: #dc3545 !important;
    animation: shake 0.5s;
  }
  
  @keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
  }
  
  /* Custom 5 columns per row */
  @media (min-width: 768px) {
    .col-md-2-4 {
      flex: 0 0 auto;
      width: 20%; /* 100% / 5 = 20% */
    }
  }

  /* Filter indicator */
  .filter-indicator {
    position: absolute; top: 8px; right: 8px;
    background: #007bff; color: white; border-radius: 50%;
    width: 20px; height: 20px; display: none;
    align-items: center; justify-content: center;
    font-size: 10px; font-weight: bold;
  }
  .stats-card.active .filter-indicator { display: flex; }
  
  /* Horizontal scroll for datatable */
  .dataTables_wrapper .dataTables_scroll {
    overflow-x: auto;
  }
  
  .dataTables_wrapper .dataTables_scrollBody {
    overflow-x: auto;
  }
  
  /* Bulk Update Modal - TABLE LAYOUT */
  #bulkUpdateModal .modal-header {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
  }
  
  .bulk-update-table {
    width: 100%;
    margin-bottom: 1rem;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.9rem;
  }
  
  .bulk-update-table thead th {
    background: #667eea;
    color: white;
    padding: 12px 8px;
    font-weight: 600;
    font-size: 0.85rem;
    border: none;
    position: sticky;
    top: 0;
    z-index: 10;
    text-align: left;
  }
  
  .bulk-update-table tbody tr {
    border-bottom: 1px solid #dee2e6;
    transition: background 0.2s;
  }
  
  .bulk-update-table tbody tr:hover {
    background: #f8f9fa;
  }
  
  .bulk-update-table tbody tr.selected {
    background: #e7f3ff;
  }
  
  .bulk-update-table td {
    padding: 10px 8px;
    vertical-align: middle;
  }
  
  .bulk-update-table .form-control,
  .bulk-update-table .form-select {
    font-size: 0.85rem;
    padding: 6px 10px;
    height: auto;
    width: 100%;
  }
  
  .bulk-update-table .form-check-input {
    width: 20px;
    height: 20px;
    cursor: pointer;
  }
  
  .mca-ref-badge {
    background: #667eea;
    color: white;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
    white-space: nowrap;
  }
  
  .pre-alert-date-text {
    color: #6c757d;
    font-size: 0.75rem;
    display: block;
    margin-top: 2px;
  }
  
  .bulk-update-summary {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
  }
  
  .bulk-update-summary h6 {
    color: #856404;
    margin-bottom: 10px;
    font-weight: 600;
  }
  
  .bulk-table-container {
    max-height: 500px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
  }
  
  /* Auto-suggestion visual feedback */
  .border-success {
    border-color: #28a745 !important;
    transition: border-color 0.3s ease;
  }

  /* Commodity Button and Modal Styling */
  #addCommodityBtn {
    padding: 0.375rem 0.75rem;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    background: #28a745;
    border: 1px solid #28a745;
  }

  #addCommodityBtn:hover {
    background: #218838;
    border-color: #1e7e34;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
  }

  #commodityModal .modal-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  }

  /* PARTIELLE Button and Modal Styling */
  #addPartielleBtn {
    padding: 0.375rem 0.75rem;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    background: #28a745;
    border: 1px solid #28a745;
  }

  #addPartielleBtn:hover {
    background: #218838;
    border-color: #1e7e34;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
  }

  #partielleModal .modal-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  }

  #partielle_preview {
    font-size: 0.95rem;
    border-left: 4px solid #28a745;
    background: #d4edda;
    border-color: #c3e6cb;
  }

  #partielle_number {
    border-left: none;
  }

  .input-group-text {
    min-width: 60px;
    justify-content: center;
  }

  #partielle_prefix {
    background-color: #28a745;
    color: white;
    font-weight: 600;
    border-color: #28a745;
  }
  
  /* SweetAlert2 custom styling for better visibility */
  .swal2-popup {
    font-size: 1rem !important;
  }
  
  .swal2-title {
    font-size: 1.5rem !important;
  }
  
  .swal2-html-container {
    font-size: 0.95rem !important;
  }
</style>

<div class="page-content">
  <div class="page-container">
    <div class="row">
      <div class="col-12">
        
        <!-- Statistics Cards with Icons -->
        <div class="row mb-4">
          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="all">
              <div class="card-body">
                <div class="stats-card-icon icon-blue">
                  <i class="ti ti-truck-delivery"></i>
                </div>
                <div class="stats-value" id="totalTrackings">0</div>
                <div class="stats-label">Total Imports</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="completed">
              <div class="card-body">
                <div class="stats-card-icon icon-green">
                  <i class="ti ti-circle-check"></i>
                </div>
                <div class="stats-value" id="totalCompleted">0</div>
                <div class="stats-label">Completed</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="in_progress">
              <div class="card-body">
                <div class="stats-card-icon icon-orange">
                  <i class="ti ti-loader"></i>
                </div>
                <div class="stats-value" id="totalInProgress">0</div>
                <div class="stats-label">In Progress</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="in_transit">
              <div class="card-body">
                <div class="stats-card-icon icon-gray">
                  <i class="ti ti-package"></i>
                </div>
                <div class="stats-value" id="totalInTransit">0</div>
                <div class="stats-label">In Transit</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="crf_missing">
              <div class="card-body">
                <div class="stats-card-icon icon-purple">
                  <i class="ti ti-file-text"></i>
                </div>
                <div class="stats-value" id="totalCRFMissing">0</div>
                <div class="stats-label">CRF Missing</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="ad_missing">
              <div class="card-body">
                <div class="stats-card-icon icon-cyan">
                  <i class="ti ti-file-alert"></i>
                </div>
                <div class="stats-value" id="totalADMissing">0</div>
                <div class="stats-label">AD Missing</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="insurance_missing">
              <div class="card-body">
                <div class="stats-card-icon icon-pink">
                  <i class="ti ti-shield-off"></i>
                </div>
                <div class="stats-value" id="totalInsuranceMissing">0</div>
                <div class="stats-label">Insurance Missing</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="audited_pending">
              <div class="card-body">
                <div class="stats-card-icon icon-teal">
                  <i class="ti ti-calendar-check"></i>
                </div>
                <div class="stats-value" id="totalAuditedPending">0</div>
                <div class="stats-label">Audited Pending</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="archived_pending">
              <div class="card-body">
                <div class="stats-card-icon icon-indigo">
                  <i class="ti ti-archive"></i>
                </div>
                <div class="stats-value" id="totalArchivedPending">0</div>
                <div class="stats-label">Archived Pending</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="dgda_in_pending">
              <div class="card-body">
                <div class="stats-card-icon icon-yellow">
                  <i class="ti ti-building"></i>
                </div>
                <div class="stats-value" id="totalDgdaInPending">0</div>
                <div class="stats-label">DGDA In Pending</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="liquidation_pending">
              <div class="card-body">
                <div class="stats-card-icon icon-brown">
                  <i class="ti ti-cash"></i>
                </div>
                <div class="stats-value" id="totalLiquidationPending">0</div>
                <div class="stats-label">Liquidation Pending</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="quittance_pending">
              <div class="card-body">
                <div class="stats-card-icon icon-red">
                  <i class="ti ti-receipt"></i>
                </div>
                <div class="stats-value" id="totalQuittancePending">0</div>
                <div class="stats-label">Quittance Pending</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Import Form Card -->
        <div class="card shadow-sm mb-4">
          <div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
            <h4 class="header-title mb-0"><i class="ti ti-file-import me-2"></i> <span id="formTitle">Add New Import</span></h4>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-sm btn-export-all" id="exportAllBtn">
                <i class="ti ti-file-spreadsheet me-1"></i> Export All to Excel
              </button>
              <button type="button" class="btn btn-sm btn-secondary" id="resetFormBtn" style="display:none;">
                <i class="ti ti-plus"></i> Add New
              </button>
            </div>
          </div>

          <div class="card-body">
            <form id="importForm" method="post" novalidate data-csrf-token="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
              <input type="hidden" name="import_id" id="import_id" value="">
              <input type="hidden" name="action" id="formAction" value="insert">

              <div class="accordion" id="importAccordion">
                
                <!-- IMPORT TRACKING ACCORDION -->
                <div class="accordion-item mb-3">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#importTracking">
                      <i class="ti ti-file-import me-2"></i> Import Tracking
                    </button>
                  </h2>

                  <div id="importTracking" class="accordion-collapse collapse" data-bs-parent="#importAccordion">
                    <div class="accordion-body">

                      <!-- ========== GROUP 1: DOCUMENTATION ========== -->
                      <div class="group-header">
                        <i class="ti ti-file-text"></i>Documentation
                      </div>

                      <!-- Row 1: Client & License Info -->
                      <div class="row">
                        <div class="col-md-2-4 mb-3">
                          <label>Client <span class="text-danger">*</span></label>
                          <select name="subscriber_id" id="subscriber_id" class="form-select" required>
                            <option value="">-- Select Client --</option>
                            <?php foreach ($subscribers as $sub): ?>
                              <option value="<?= $sub['id'] ?>" data-liquidation="<?= $sub['liquidation_paid_by'] ?? '' ?>"><?= $sub['short_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="subscriber_id_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>License Number <span class="text-danger">*</span></label>
                          <select name="license_id" id="license_id" class="form-select" required>
                            <option value="">-- Select License --</option>
                          </select>
                          <div class="invalid-feedback" id="license_id_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Kind <span class="text-danger">*</span></label>
                          <input type="hidden" name="kind" id="kind_hidden">
                          <input type="text" id="kind_display" class="form-control readonly-field" readonly placeholder="From License">
                          <div class="invalid-feedback" id="kind_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Type of Goods <span class="text-danger">*</span></label>
                          <input type="hidden" name="type_of_goods" id="type_of_goods_hidden">
                          <input type="text" id="type_of_goods_display" class="form-control readonly-field" readonly placeholder="From License">
                          <div class="invalid-feedback" id="type_of_goods_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Transport Mode <span class="text-danger">*</span></label>
                          <input type="hidden" name="transport_mode" id="transport_mode_hidden">
                          <input type="text" id="transport_mode_display" class="form-control readonly-field" readonly placeholder="From License">
                          <div class="invalid-feedback" id="transport_mode_error"></div>
                        </div>
                      </div>

                      <!-- Row 2: MCA, Currency, Supplier, Regime, Clearance -->
                      <div class="row">
                        <div class="col-md-2-4 mb-3">
                          <label>MCA Reference <span class="text-danger">*</span> <small class="text-muted">(Auto)</small></label>
                          <input type="text" name="mca_ref" id="mca_ref" class="form-control auto-generated-field" required readonly placeholder="Auto-generated">
                          <div class="invalid-feedback" id="mca_ref_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Currency <span class="text-danger">*</span></label>
                          <input type="hidden" name="currency" id="currency_hidden">
                          <input type="text" id="currency_display" class="form-control readonly-field" readonly placeholder="From License">
                          <div class="invalid-feedback" id="currency_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Supplier <span class="text-danger">*</span></label>
                          <input type="text" name="supplier" id="supplier" class="form-control readonly-field" readonly placeholder="From License">
                          <div class="invalid-feedback" id="supplier_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Regime <span class="text-danger">*</span></label>
                          <select name="regime" id="regime" class="form-select" required>
                            <option value="">-- Select Regime --</option>
                            <?php foreach ($regimes as $regime): ?>
                              <option value="<?= $regime['id'] ?>"><?= $regime['regime_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="regime_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Types of Clearance <span class="text-danger">*</span></label>
                          <select name="types_of_clearance" id="types_of_clearance" class="form-select" required>
                            <option value="">-- Select Clearance --</option>
                            <?php foreach ($clearance_types as $type): ?>
                              <option value="<?= $type['id'] ?>" <?= ($type['id'] == 1) ? 'selected' : '' ?>><?= $type['clearance_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="types_of_clearance_error"></div>
                        </div>
                      </div>

                      <!-- Row 3: Declaration Office, Pre-Alert, Invoice, Commodity with + button, PO Ref -->
                      <div class="row">
                        <div class="col-md-2-4 mb-3">
                          <label>Declaration Office <span class="text-danger">*</span></label>
                          <select name="declaration_office_id" id="declaration_office_id" class="form-select" required>
                            <option value="">-- Select Office --</option>
                            <?php foreach ($sub_offices as $office): ?>
                              <option value="<?= $office['id'] ?>"><?= $office['sub_office_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="declaration_office_id_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Pre-Alert Date <span class="text-danger">*</span></label>
                          <input type="date" name="pre_alert_date" id="pre_alert_date" class="form-control" required>
                          <div class="invalid-feedback" id="pre_alert_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Invoice <span class="text-danger">*</span></label>
                          <input type="text" name="invoice" id="invoice" class="form-control" required maxlength="100">
                          <div class="invalid-feedback" id="invoice_error"></div>
                        </div>

                        <!-- COMMODITY FIELD WITH + BUTTON -->
                        <div class="col-md-2-4 mb-3">
                          <label>Commodity <span class="text-danger">*</span></label>
                          <div class="input-group">
                            <select name="commodity" id="commodity" class="form-select" required>
                              <option value="">-- Select Commodity --</option>
                              <?php foreach ($commodities as $commodity): ?>
                                <option value="<?= $commodity['id'] ?>"><?= $commodity['commodity_name'] ?></option>
                              <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-success" id="addCommodityBtn" title="Add Commodity">
                              <i class="ti ti-plus"></i>
                            </button>
                          </div>
                          <div class="invalid-feedback" id="commodity_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>PO Reference</label>
                          <input type="text" name="po_ref" id="po_ref" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="po_ref_error"></div>
                        </div>
                      </div>

                      <!-- Row 4: Fret, Fret Currency, Other Charges, Other Charges Currency, CRF Reference -->
                      <div class="row">
                        <div class="col-md-2-4 mb-3">
                          <label>Fret</label>
                          <input type="number" step="0.01" name="fret" id="fret" class="form-control" min="0">
                          <div class="invalid-feedback" id="fret_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Fret Currency</label>
                          <select name="fret_currency" id="fret_currency" class="form-select">
                            <option value="">-- Select --</option>
                            <?php foreach ($currencies as $curr): ?>
                              <option value="<?= $curr['id'] ?>"><?= $curr['currency_short_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="fret_currency_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Other Charges</label>
                          <input type="number" step="0.01" name="other_charges" id="other_charges" class="form-control" min="0">
                          <div class="invalid-feedback" id="other_charges_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Other Charges Currency</label>
                          <select name="other_charges_currency" id="other_charges_currency" class="form-select">
                            <option value="">-- Select --</option>
                            <?php foreach ($currencies as $curr): ?>
                              <option value="<?= $curr['id'] ?>"><?= $curr['currency_short_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="other_charges_currency_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>CRF Reference <small class="text-muted">(From License)</small></label>
                          <input type="text" name="crf_reference" id="crf_reference" class="form-control readonly-field" readonly maxlength="100" placeholder="From License">
                          <div class="invalid-feedback" id="crf_reference_error"></div>
                        </div>
                      </div>

                      <!-- Row 5: CRF Received Date, Clearing Based On, AD Date, Insurance Date, Insurance Amount -->
                      <div class="row">
                        <div class="col-md-2-4 mb-3">
                          <label>CRF Received Date <small class="text-muted">(>= Pre-Alert)</small></label>
                          <input type="date" name="crf_received_date" id="crf_received_date" class="form-control document-status-trigger date-after-prealert">
                          <div class="invalid-feedback" id="crf_received_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Clearing Based On</label>
                          <select name="clearing_based_on" id="clearing_based_on" class="form-select">
                            <option value="">-- Select --</option>
                            <?php foreach ($clearing_based_on_options as $option): ?>
                              <option value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="clearing_based_on_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>AD Date <small class="text-muted">(>= Pre-Alert)</small></label>
                          <input type="date" name="ad_date" id="ad_date" class="form-control document-status-trigger date-after-prealert">
                          <div class="invalid-feedback" id="ad_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Insurance Date <small class="text-muted">(>= Pre-Alert)</small></label>
                          <input type="date" name="insurance_date" id="insurance_date" class="form-control document-status-trigger date-after-prealert">
                          <div class="invalid-feedback" id="insurance_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Insurance Amount</label>
                          <input type="number" step="0.01" name="insurance_amount" id="insurance_amount" class="form-control" min="0">
                          <div class="invalid-feedback" id="insurance_amount_error"></div>
                        </div>
                      </div>

                      <!-- Row 6: Insurance Currency, Insurance Reference, Inspection Reports (PARTIELLE), Weight, FOB -->
                      <div class="row">
                        <div class="col-md-2-4 mb-3">
                          <label>Insurance Currency</label>
                          <select name="insurance_amount_currency" id="insurance_amount_currency" class="form-select">
                            <option value="">-- Select --</option>
                            <?php foreach ($currencies as $curr): ?>
                              <option value="<?= $curr['id'] ?>"><?= $curr['currency_short_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="insurance_amount_currency_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Insurance Reference</label>
                          <input type="text" name="insurance_reference" id="insurance_reference" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="insurance_reference_error"></div>
                        </div>

                        <!-- PARTIELLE FIELD WITH + BUTTON -->
                        <div class="col-md-2-4 mb-3">
                          <label>Inspection Reports (PARTIELLE)</label>
                          <div class="input-group">
                            <select name="inspection_reports" id="inspection_reports" class="form-select">
                              <option value="">-- Select PARTIELLE --</option>
                            </select>
                            <button type="button" class="btn btn-success" id="addPartielleBtn" title="Add PARTIELLE">
                              <i class="ti ti-plus"></i>
                            </button>
                          </div>
                          <div class="invalid-feedback" id="inspection_reports_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Weight <span class="text-danger">*</span></label>
                          <input type="number" step="0.01" name="weight" id="weight" class="form-control" required min="0">
                          <div class="invalid-feedback" id="weight_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>FOB <span class="text-danger">*</span></label>
                          <input type="number" step="0.01" name="fob" id="fob" class="form-control" required min="0">
                          <div class="invalid-feedback" id="fob_error"></div>
                        </div>
                      </div>

                      <!-- Row 7: FOB Currency, Archive Reference, Audited Date, Archived Date, Road Manifest -->
                      <div class="row">
                        <div class="col-md-2-4 mb-3">
                          <label>FOB Currency</label>
                          <select name="fob_currency" id="fob_currency" class="form-select">
                            <option value="">-- Select --</option>
                            <?php foreach ($currencies as $curr): ?>
                              <option value="<?= $curr['id'] ?>"><?= $curr['currency_short_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="fob_currency_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Archive Reference</label>
                          <input type="text" name="archive_reference" id="archive_reference" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="archive_reference_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Audited Date <small class="text-muted">(>= Pre-Alert)</small></label>
                          <input type="date" name="audited_date" id="audited_date" class="form-control date-after-prealert">
                          <div class="invalid-feedback" id="audited_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Archived Date <small class="text-muted">(>= Pre-Alert)</small></label>
                          <input type="date" name="archived_date" id="archived_date" class="form-control date-after-prealert">
                          <div class="invalid-feedback" id="archived_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3" id="road_manifest_field" style="display:none;">
                          <label>Road Manifest <small class="text-muted">(Road/Rail)</small></label>
                          <input type="text" name="road_manif" id="road_manif" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="road_manif_error"></div>
                        </div>
                      </div>

                      <!-- WAGON ROW (RAIL ONLY) -->
                      <div class="row" id="wagon_field_row" style="display:none;">
                        <div class="col-md-2-4 mb-3">
                          <label>Wagon <small class="text-muted">(Rail)</small></label>
                          <input type="text" name="wagon" id="wagon" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="wagon_error"></div>
                        </div>
                      </div>

                      <!-- HORSE/TRAILERS/CONTAINER/ENTRY POINT ROW (ROAD/RAIL) -->
                      <div class="row" id="road_fields" style="display:none;">
                        <div class="col-md-2-4 mb-3">
                          <label>Horse <small class="text-muted">(Road/Rail)</small></label>
                          <input type="text" name="horse" id="horse" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="horse_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Trailer 1 <small class="text-muted">(Road/Rail)</small></label>
                          <input type="text" name="trailer_1" id="trailer_1" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="trailer_1_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Trailer 2 <small class="text-muted">(Road/Rail)</small></label>
                          <input type="text" name="trailer_2" id="trailer_2" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="trailer_2_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Container <small class="text-muted">(Road/Rail)</small></label>
                          <input type="text" name="container" id="container" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="container_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Entry Point <span class="text-danger">*</span></label>
                          <select name="entry_point_id" id="entry_point_id" class="form-select" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($entry_points as $point): ?>
                              <option value="<?= $point['id'] ?>"><?= $point['transit_point_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="entry_point_id_error"></div>
                        </div>
                      </div>

                      <!-- AIR: ENTRY POINT ALONE -->
                      <div class="row" id="air_entry_point_row" style="display:none;">
                        <div class="col-md-2-4 mb-3">
                          <label>Entry Point <span class="text-danger">*</span></label>
                          <select name="entry_point_id_air" id="entry_point_id_air" class="form-select" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($entry_points as $point): ?>
                              <option value="<?= $point['id'] ?>"><?= $point['transit_point_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="entry_point_id_air_error"></div>
                        </div>
                      </div>

                      <!-- ========== GROUP 2: DECLARATION ========== -->
                      <div class="group-header">
                        <i class="ti ti-file-certificate"></i>Declaration
                      </div>

                      <!-- Row 1: DGDA In Date, Declaration Reference, SEGUES RCV Reference, SEGUES Payment Date, Customs Manifest Number -->
                      <div class="row">
                        <div class="col-md-2-4 mb-3">
                          <label>DGDA In Date</label>
                          <input type="date" name="dgda_in_date" id="dgda_in_date" class="form-control">
                          <div class="invalid-feedback" id="dgda_in_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Declaration Reference</label>
                          <input type="text" name="declaration_reference" id="declaration_reference" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="declaration_reference_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>SEGUES RCV Reference</label>
                          <input type="text" name="segues_rcv_ref" id="segues_rcv_ref" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="segues_rcv_ref_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>SEGUES Payment Date</label>
                          <input type="date" name="segues_payment_date" id="segues_payment_date" class="form-control">
                          <div class="invalid-feedback" id="segues_payment_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Customs Manifest Number</label>
                          <input type="text" name="customs_manifest_number" id="customs_manifest_number" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="customs_manifest_number_error"></div>
                        </div>
                      </div>

                      <!-- Row 2: Customs Manifest Date, Liquidation Reference, Liquidation Date, Liquidation Paid By, Liquidation Amount -->
                      <div class="row">
                        <div class="col-md-2-4 mb-3">
                          <label>Customs Manifest Date</label>
                          <input type="date" name="customs_manifest_date" id="customs_manifest_date" class="form-control">
                          <div class="invalid-feedback" id="customs_manifest_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Liquidation Reference</label>
                          <input type="text" name="liquidation_reference" id="liquidation_reference" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="liquidation_reference_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Liquidation Date</label>
                          <input type="date" name="liquidation_date" id="liquidation_date" class="form-control">
                          <div class="invalid-feedback" id="liquidation_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Liquidation Paid By <small class="text-muted">(From Client)</small></label>
                          <input type="text" name="liquidation_paid_by" id="liquidation_paid_by" class="form-control readonly-field" readonly placeholder="From Client">
                          <div class="invalid-feedback" id="liquidation_paid_by_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Liquidation Amount</label>
                          <input type="number" step="0.01" name="liquidation_amount" id="liquidation_amount" class="form-control" min="0">
                          <div class="invalid-feedback" id="liquidation_amount_error"></div>
                        </div>
                      </div>

                      <!-- Row 3: Quittance Reference, Quittance Date, DGDA Out Date, Document Status, Customs Clearance Code -->
                      <div class="row">
                        <div class="col-md-2-4 mb-3">
                          <label>Quittance Reference</label>
                          <input type="text" name="quittance_reference" id="quittance_reference" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="quittance_reference_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Quittance Date <small class="text-muted">(Triggers Status)</small></label>
                          <input type="date" name="quittance_date" id="quittance_date" class="form-control clearing-status-trigger">
                          <div class="invalid-feedback" id="quittance_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>DGDA Out Date</label>
                          <input type="date" name="dgda_out_date" id="dgda_out_date" class="form-control">
                          <div class="invalid-feedback" id="dgda_out_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Document Status <small class="text-muted">(Auto)</small></label>
                          <select name="document_status" id="document_status" class="form-select auto-generated-field" readonly disabled>
                            <option value="">-- Select --</option>
                            <?php foreach ($document_statuses as $status): ?>
                              <option value="<?= $status['id'] ?>" <?= ($status['id'] == 1) ? 'selected' : '' ?>><?= $status['document_status'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="document_status_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Customs Clearance Code</label>
                          <input type="text" name="customs_clearance_code" id="customs_clearance_code" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="customs_clearance_code_error"></div>
                        </div>
                      </div>

                      <!-- ========== GROUP 3: LOGISTICS & TRANSPORT ========== -->
                      <div class="group-header">
                        <i class="ti ti-truck-delivery"></i>Logistics & Transport
                      </div>

                      <!-- AIR ONLY FIELDS -->
                      <div class="row" id="air_fields" style="display:none;">
                        <div class="col-md-2-4 mb-3">
                          <label>Airway Bill <small class="text-muted">(Air)</small></label>
                          <input type="text" name="airway_bill" id="airway_bill" class="form-control" maxlength="100">
                          <div class="invalid-feedback" id="airway_bill_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Airway Bill Weight <small class="text-muted">(Air)</small></label>
                          <input type="number" step="0.01" name="airway_bill_weight" id="airway_bill_weight" class="form-control" min="0">
                          <div class="invalid-feedback" id="airway_bill_weight_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Airport Arrival Date <small class="text-muted">(Air)</small></label>
                          <input type="date" name="airport_arrival_date" id="airport_arrival_date" class="form-control">
                          <div class="invalid-feedback" id="airport_arrival_date_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Dispatch from Airport <small class="text-muted">(Air, >= Arrival)</small></label>
                          <input type="date" name="dispatch_from_airport" id="dispatch_from_airport" class="form-control airport-date-validate">
                          <div class="invalid-feedback" id="dispatch_from_airport_error"></div>
                        </div>

                        <div class="col-md-2-4 mb-3">
                          <label>Declaration Validity <small class="text-muted">(Air/Temp)</small></label>
                          <select name="declaration_validity" id="declaration_validity" class="form-select">
                            <option value="">-- Select --</option>
                            <?php foreach ($declaration_validity_options as $option): ?>
                              <option value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="declaration_validity_error"></div>
                        </div>
                      </div>

                      <!-- ALL LOGISTICS FIELDS (ROAD & RAIL ONLY, NOT AIR) -->
                      <div id="logistics_fields" style="display:none;">
                        <!-- Row 1: Always has 5 fields -->
                        <div class="row" id="logistics_row1">
                          <!-- T1 fields (when clearance = 3) -->
                          <div class="col-md-2-4 mb-3" id="t1_number_col" style="display:none;">
                            <label>T1 Number <small class="text-muted">(Transfer)</small></label>
                            <input type="text" name="t1_number" id="t1_number" class="form-control" maxlength="100">
                            <div class="invalid-feedback" id="t1_number_error"></div>
                          </div>

                          <div class="col-md-2-4 mb-3" id="t1_date_col" style="display:none;">
                            <label>T1 Date <small class="text-muted">(Transfer)</small></label>
                            <input type="date" name="t1_date" id="t1_date" class="form-control">
                            <div class="invalid-feedback" id="t1_date_error"></div>
                          </div>

                          <!-- Always visible -->
                          <div class="col-md-2-4 mb-3">
                            <label>Arrival Date Zambia</label>
                            <input type="date" name="arrival_date_zambia" id="arrival_date_zambia" class="form-control date-sequence-field" data-seq="1">
                            <div class="invalid-feedback" id="arrival_date_zambia_error"></div>
                          </div>

                          <div class="col-md-2-4 mb-3">
                            <label>Dispatch from Zambia</label>
                            <input type="date" name="dispatch_from_zambia" id="dispatch_from_zambia" class="form-control date-sequence-field" data-seq="2">
                            <div class="invalid-feedback" id="dispatch_from_zambia_error"></div>
                          </div>

                          <div class="col-md-2-4 mb-3">
                            <label>DRC Entry Date</label>
                            <input type="date" name="drc_entry_date" id="drc_entry_date" class="form-control date-sequence-field" data-seq="3">
                            <div class="invalid-feedback" id="drc_entry_date_error"></div>
                          </div>

                          <!-- Show when T1 hidden -->
                          <div class="col-md-2-4 mb-3" id="border_arrival_col_row1" style="display:none;">
                            <label>Border Warehouse Arrival <small class="text-muted">(Triggers Status)</small></label>
                            <input type="date" name="border_warehouse_arrival_date" id="border_warehouse_arrival_date" class="form-control date-sequence-field clearing-status-trigger" data-seq="4">
                            <div class="invalid-feedback" id="border_warehouse_arrival_date_error"></div>
                          </div>

                          <div class="col-md-2-4 mb-3" id="border_dispatch_col_row1" style="display:none;">
                            <label>Dispatch from Border</label>
                            <input type="date" name="dispatch_from_border" id="dispatch_from_border" class="form-control date-sequence-field" data-seq="5">
                            <div class="invalid-feedback" id="dispatch_from_border_error"></div>
                          </div>
                        </div>

                        <!-- Row 2: IBS Coupon onwards -->
                        <div class="row">
                          <div class="col-md-2-4 mb-3">
                            <label>IBS Coupon Reference</label>
                            <input type="text" name="ibs_coupon_reference" id="ibs_coupon_reference" class="form-control" maxlength="100">
                            <div class="invalid-feedback" id="ibs_coupon_reference_error"></div>
                          </div>

                          <div class="col-md-2-4 mb-3">
                            <label>Border Warehouse</label>
                            <select name="border_warehouse_id" id="border_warehouse_id" class="form-select">
                              <option value="">-- Select --</option>
                              <?php foreach ($border_warehouses as $wh): ?>
                                <option value="<?= $wh['id'] ?>"><?= $wh['transit_point_name'] ?></option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback" id="border_warehouse_id_error"></div>
                          </div>

                          <div class="col-md-2-4 mb-3">
                            <label>Entry Coupon</label>
                            <input type="text" name="entry_coupon" id="entry_coupon" class="form-control" maxlength="100">
                            <div class="invalid-feedback" id="entry_coupon_error"></div>
                          </div>

                          <div class="col-md-2-4 mb-3">
                            <label>Bonded Warehouse</label>
                            <select name="bonded_warehouse_id" id="bonded_warehouse_id" class="form-select">
                              <option value="">-- Select --</option>
                              <?php foreach ($bonded_warehouses as $wh): ?>
                                <option value="<?= $wh['id'] ?>"><?= $wh['transit_point_name'] ?></option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback" id="bonded_warehouse_id_error"></div>
                          </div>

                          <div class="col-md-2-4 mb-3">
                            <label>Truck Status</label>
                            <select name="truck_status" id="truck_status" class="form-select">
                              <option value="">-- Select --</option>
                              <?php foreach ($truck_statuses as $status): ?>
                                <option value="<?= htmlspecialchars($status['truck_status'], ENT_QUOTES, 'UTF-8') ?>"><?= $status['truck_status'] ?></option>
                              <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback" id="truck_status_error"></div>
                          </div>
                        </div>

                        <!-- Row 3: Kanyaka dates -->
                        <div class="row">
                          <div class="col-md-2-4 mb-3">
                            <label>Kanyaka Arrival Date</label>
                            <input type="date" name="kanyaka_arrival_date" id="kanyaka_arrival_date" class="form-control">
                            <div class="invalid-feedback" id="kanyaka_arrival_date_error"></div>
                          </div>

                          <div class="col-md-2-4 mb-3">
                            <label>Kanyaka Dispatch Date</label>
                            <input type="date" name="kanyaka_dispatch_date" id="kanyaka_dispatch_date" class="form-control">
                            <div class="invalid-feedback" id="kanyaka_dispatch_date_error"></div>
                          </div>

                          <div class="col-md-2-4 mb-3">
                            <label>Warehouse Arrival Date</label>
                            <input type="date" name="warehouse_arrival_date" id="warehouse_arrival_date" class="form-control">
                            <div class="invalid-feedback" id="warehouse_arrival_date_error"></div>
                          </div>

                          <div class="col-md-2-4 mb-3">
                            <label>Warehouse Departure Date</label>
                            <input type="date" name="warehouse_departure_date" id="warehouse_departure_date" class="form-control">
                            <div class="invalid-feedback" id="warehouse_departure_date_error"></div>
                          </div>

                          <div class="col-md-2-4 mb-3">
                            <label>Dispatch/Deliver Date</label>
                            <input type="date" name="dispatch_deliver_date" id="dispatch_deliver_date" class="form-control">
                            <div class="invalid-feedback" id="dispatch_deliver_date_error"></div>
                          </div>
                        </div>
                      </div>

                      <!-- Clearing Status with Auto/Manual Indicator -->
                      <div class="row">
                        <div class="col-md-4 mb-3">
                          <label>Clearing Status <span class="text-danger">*</span> 
                            <span class="status-mode-badge auto" id="statusModeBadge">Auto</span>
                          </label>
                          <div class="d-flex align-items-center">
                            <select name="clearing_status" id="clearing_status" class="form-select clearing-status-auto-mode" required style="flex: 1;">
                              <option value="">-- Select --</option>
                              <?php foreach ($clearing_statuses as $status): ?>
                                <option value="<?= $status['id'] ?>"><?= $status['clearing_status'] ?></option>
                              <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2" id="resetClearingStatusBtn" title="Reset to auto-suggestion">
                              <i class="ti ti-refresh"></i>
                            </button>
                          </div>
                          <small class="text-muted">Auto-suggested based on dates. You can override manually.</small>
                          <div class="invalid-feedback" id="clearing_status_error"></div>
                        </div>
                      </div>

                      <!-- ========== REMARKS ========== -->
                      <div class="mt-4 mb-3">
                        <h6><i class="ti ti-message-circle me-2"></i>Remarks</h6>
                        <input type="hidden" name="remarks" id="remarks_hidden" value="">
                        
                        <div id="remarksContainer">
                          <!-- Remarks will be dynamically added here -->
                        </div>

                        <button type="button" class="btn btn-sm btn-success" id="addRemarkBtn">
                          <i class="ti ti-plus me-1"></i> Add Remark
                        </button>
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
                    <i class="ti ti-check me-1"></i> <span id="submitBtnText">Save Import</span>
                  </button>
                </div>
              </div>

            </form>
          </div>
        </div>

        <!-- Imports DataTable -->
        <div class="card shadow-sm">
          <div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
            <h4 class="header-title mb-0"><i class="ti ti-list me-2"></i> Imports List</h4>
            <div class="d-flex align-items-center">
              <button type="button" class="btn btn-sm btn-bulk-update me-2" id="bulkUpdateBtn" disabled>
                <i class="ti ti-edit me-1"></i> Bulk Update
              </button>
              <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="clearFilters">
                <i class="ti ti-filter-off me-1"></i> Clear Filters
              </button>
              <span class="badge bg-primary" id="activeFiltersBadge" style="display: none;">0 Filters Active</span>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="importsTable" class="table table-striped table-bordered dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>MCA Ref</th>
                    <th>Client</th>
                    <th>License</th>
                    <th>Invoice</th>
                    <th>Pre-Alert Date</th>
                    <th>Weight</th>
                    <th>FOB</th>
                    <th>Clearing Status</th>
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

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti ti-edit me-2"></i> Bulk Update Imports
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="bulk-update-summary">
          <h6><i class="ti ti-info-circle me-2"></i>Filter Summary</h6>
          <p class="mb-0" id="bulkFilterSummary">No filter active</p>
        </div>

        <div id="bulkUpdateContent">
          <p class="text-center text-muted">Loading...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Cancel
        </button>
        <button type="button" class="btn btn-primary" id="saveBulkUpdateBtn">
          <i class="ti ti-check me-1"></i> Save All Changes
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Commodity Modal -->
<div class="modal fade" id="commodityModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
        <h5 class="modal-title">
          <i class="ti ti-plus-circle me-2"></i> Create New Commodity
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: brightness(0) invert(1);"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Commodity Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="commodity_name_input" placeholder="Enter commodity name" maxlength="255">
          <small class="text-muted">Enter the name of the commodity</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Cancel
        </button>
        <button type="button" class="btn btn-success" id="saveCommodityBtn">
          <i class="ti ti-check me-1"></i> Create Commodity
        </button>
      </div>
    </div>
  </div>
</div>

<!-- PARTIELLE Modal -->
<div class="modal fade" id="partielleModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
        <h5 class="modal-title">
          <i class="ti ti-plus-circle me-2"></i> Create New PARTIELLE
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: brightness(0) invert(1);"></button>
      </div>
      <div class="modal-body">
        <!-- CRITICAL: Hidden license_id field -->
        <input type="hidden" id="partielle_license_id" value="">
        
        <div class="mb-3">
          <label class="form-label">Selected License</label>
          <input type="text" class="form-control" id="partielle_license_display" readonly style="background-color: #f8f9fa;">
          <small class="text-muted">License selected for this PARTIELLE</small>
        </div>
        
        <div class="mb-3">
          <label class="form-label">CRF Reference</label>
          <input type="text" class="form-control" id="partielle_crf_reference" readonly style="background-color: #f8f9fa;">
          <small class="text-muted">Auto-filled from License CRF Reference</small>
        </div>
        
        <div class="mb-3">
          <label class="form-label">PARTIELLE Number <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text" id="partielle_prefix" style="background-color: #28a745; color: white; font-weight: 600;"></span>
            <input type="text" class="form-control" id="partielle_number" placeholder="Enter number (e.g., PART-001, 001)" maxlength="50">
          </div>
          <small class="text-muted">Format: CRF Reference/Number</small>
        </div>
        
        <div id="partielle_preview" class="alert alert-info" style="display: none;">
          <strong>Full PARTIELLE Name:</strong> <span id="partielle_preview_text"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Cancel
        </button>
        <button type="button" class="btn btn-success" id="savePartielleBtn">
          <i class="ti ti-check me-1"></i> Create PARTIELLE
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function () {

    const csrfToken = $('#importForm').data('csrf-token');
    
    let clearingStatusIds = {
      in_transit_id: null,
      in_progress_id: null,
      completed_id: null
    };
    
    function loadClearingStatusIds() {
      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/getClearingStatusIds',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
          if (res.success) {
            clearingStatusIds = res.data;
            console.log('✅ Clearing Status IDs loaded:', clearingStatusIds);
            
            if (clearingStatusIds.in_transit_id) {
              $('#clearing_status').val(clearingStatusIds.in_transit_id);
              $('#clearing_status').data('auto-mode', true);
              updateStatusModeBadge(true);
            }
          } else {
            console.error('❌ Failed to load clearing status IDs');
          }
        },
        error: function() {
          console.error('❌ Error loading clearing status IDs');
        }
      });
    }
    
    loadClearingStatusIds();

    // ========================================
    // COMMODITY FUNCTIONALITY
    // ========================================

    $('#addCommodityBtn').on('click', function() {
      $('#commodity_name_input').val('');
      $('#commodityModal').modal('show');
    });

    $('#saveCommodityBtn').on('click', function() {
      const commodityName = $('#commodity_name_input').val().trim();
      
      if (!commodityName) {
        Swal.fire({
          icon: 'warning',
          title: 'Name Required',
          text: 'Please enter a commodity name.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#f39c12'
        });
        return;
      }
      
      const $saveBtn = $('#saveCommodityBtn');
      const originalText = $saveBtn.html();
      $saveBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Creating...');
      
      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/createCommodity',
        method: 'POST',
        data: {
          commodity_name: commodityName,
          csrf_token: csrfToken
        },
        dataType: 'json',
        success: function(res) {
          $saveBtn.prop('disabled', false).html(originalText);
          
          if (res.success) {
            $('#commodity').append(new Option(commodityName, res.id));
            $('#commodity').val(res.id);
            $('#commodityModal').modal('hide');
            
            Swal.fire({
              icon: 'success',
              title: 'Created!',
              text: 'Commodity created successfully!',
              timer: 1500,
              showConfirmButton: false
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Failed',
              html: res.message || 'Failed to create commodity',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function(xhr) {
          $saveBtn.prop('disabled', false).html(originalText);
          
          let errorMsg = 'An error occurred while creating commodity';
          
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMsg = xhr.responseJSON.message;
          } else if (xhr.status === 403) {
            errorMsg = 'Security token expired. Please refresh the page and try again.';
          }
          
          Swal.fire({
            icon: 'error',
            title: 'Error',
            html: errorMsg,
            confirmButtonText: 'OK'
          });
        }
      });
    });

    function loadCommoditiesForLicense() {
      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/getCommodities',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
          if (res.success && res.data && res.data.length > 0) {
            $('#commodity').html('<option value="">-- Select Commodity --</option>');
            res.data.forEach(function(commodity) {
              $('#commodity').append(
                new Option(commodity.commodity_name, commodity.id)
              );
            });
          }
        },
        error: function() {
          console.error('Failed to load commodities');
        }
      });
    }

    // ========================================
    // ✅ FIXED PARTIELLE FUNCTIONALITY - Uses license_id
    // ========================================

    $('#addPartielleBtn').on('click', function() {
      // ✅ GET license_id, NOT license_number
      const licenseId = $('#license_id').val();
      const licenseNumber = $('#license_id option:selected').text();
      const crfReference = $('#crf_reference').val();
      
      console.log('🔍 Opening PARTIELLE modal:', {
        license_id: licenseId,
        license_number: licenseNumber,
        crf_reference: crfReference
      });
      
      if (!licenseId || licenseId === '') {
        Swal.fire({
          icon: 'warning',
          title: 'License Required',
          text: 'Please select a License first.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#f39c12'
        });
        return;
      }
      
      if (!crfReference || crfReference.trim() === '') {
        Swal.fire({
          icon: 'warning',
          title: 'CRF Reference Required',
          text: 'The selected license must have a CRF Reference.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#f39c12'
        });
        return;
      }
      
      // ✅ Store license_id in hidden field
      $('#partielle_license_id').val(licenseId);
      $('#partielle_license_display').val(licenseNumber);
      $('#partielle_crf_reference').val(crfReference);
      $('#partielle_prefix').text(crfReference + '/');
      $('#partielle_number').val('');
      $('#partielle_preview').hide();
      
      $('#partielleModal').modal('show');
    });

    $('#partielle_number').on('input', function() {
      const crfRef = $('#partielle_crf_reference').val();
      const number = $(this).val().trim();
      
      if (number) {
        const fullReference = crfRef + '/' + number;
        $('#partielle_preview_text').text(fullReference);
        $('#partielle_preview').show();
      } else {
        $('#partielle_preview').hide();
      }
    });

    $('#savePartielleBtn').on('click', function() {
      const crfRef = $('#partielle_crf_reference').val();
      const number = $('#partielle_number').val().trim();
      // ✅ Get license_id from hidden field
      const licenseId = $('#partielle_license_id').val();
      const licenseNumber = $('#partielle_license_display').val();
      
      console.log('💾 Saving PARTIELLE:', {
        license_id: licenseId,
        license_number: licenseNumber,
        partial_name: crfRef + '/' + number
      });
      
      if (!number) {
        Swal.fire({
          icon: 'warning',
          title: 'Number Required',
          text: 'Please enter a number for the PARTIELLE reference.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#f39c12'
        });
        return;
      }
      
      if (!licenseId || licenseId === '') {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'License ID is missing. Please close and reopen the modal.',
          confirmButtonText: 'OK'
        });
        return;
      }
      
      const fullReference = crfRef + '/' + number;
      
      const $saveBtn = $('#savePartielleBtn');
      const originalText = $saveBtn.html();
      $saveBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Creating...');
      
      // ✅ CRITICAL: Send license_id, NOT license_number
      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/createPartielle',
        method: 'POST',
        data: {
          partial_name: fullReference,
          license_id: licenseId,  // ✅ Send license_id
          csrf_token: csrfToken
        },
        dataType: 'json',
        success: function(res) {
          $saveBtn.prop('disabled', false).html(originalText);
          
          console.log('📥 PARTIELLE creation response:', res);
          
          if (res.success) {
            $('#inspection_reports').append(new Option(fullReference, fullReference));
            $('#inspection_reports').val(fullReference);
            
            $('#partielleModal').modal('hide');
            
            Swal.fire({
              icon: 'success',
              title: 'Created!',
              text: 'PARTIELLE created successfully!',
              timer: 1500,
              showConfirmButton: false
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Failed',
              html: res.message || 'Failed to create PARTIELLE',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function(xhr) {
          $saveBtn.prop('disabled', false).html(originalText);
          
          console.error('❌ PARTIELLE creation error:', xhr);
          
          let errorMsg = 'An error occurred while creating PARTIELLE';
          
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMsg = xhr.responseJSON.message;
          } else if (xhr.status === 403) {
            errorMsg = 'Security token expired. Please refresh the page and try again.';
          }
          
          Swal.fire({
            icon: 'error',
            title: 'Error',
            html: errorMsg,
            confirmButtonText: 'OK'
          });
        }
      });
    });

    // ✅ Load PARTIELLE options when license changes - Uses license_id
    $('#license_id').on('change', function() {
      const licenseId = $(this).val();
      const licenseNumber = $(this).find('option:selected').text();
      
      console.log('🔄 License changed:', {
        license_id: licenseId,
        license_number: licenseNumber
      });
      
      if (!licenseId) {
        $('#inspection_reports').html('<option value="">-- Select PARTIELLE --</option>');
        clearLicenseFields();
        return;
      }
      
      $('#kind_display, #type_of_goods_display, #transport_mode_display, #currency_display, #supplier, #crf_reference').val('Loading...');

      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/getLicenseDetails',
        method: 'GET',
        data: { license_id: licenseId },
        dataType: 'json',
        success: function(res) {
          if (res.success && res.data) {
            const license = res.data;
            
            $('#kind_hidden').val(license.kind_id || '');
            $('#type_of_goods_hidden').val(license.type_of_goods_id || '');
            $('#transport_mode_hidden').val(license.transport_mode_id || '');
            $('#currency_hidden').val(license.currency_id || '');
            
            $('#kind_display').val(escapeHtml(license.kind_name || ''));
            $('#type_of_goods_display').val(escapeHtml(license.type_of_goods_name || ''));
            $('#transport_mode_display').val(escapeHtml(license.transport_mode_name || ''));
            $('#currency_display').val(escapeHtml(license.currency_name || ''));
            $('#supplier').val(escapeHtml(license.supplier || ''));
            $('#crf_reference').val(escapeHtml(license.ref_cod || ''));
            
            syncCurrencyFields(license.currency_id);
            generateMCAReference();
            handleTransportModeFields(license.transport_mode_id, license.transport_mode_name);
          } else {
            clearLicenseFields();
            Swal.fire({
              icon: 'error',
              title: 'Error',
              html: res.message || 'Failed to load license details',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function(xhr) {
          clearLicenseFields();
          
          let errorMsg = 'Failed to load license details';
          
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMsg = xhr.responseJSON.message;
          }
          
          Swal.fire({
            icon: 'error',
            title: 'Error',
            html: errorMsg,
            confirmButtonText: 'OK'
          });
        }
      });
      
      // ✅ Load PARTIELLE options using license_id
      loadPartielleOptions(licenseId);
    });

    // ✅ NEW FUNCTION: Load PARTIELLE options by license_id
    function loadPartielleOptions(licenseId) {
      console.log('📋 Loading PARTIELLE options for license_id:', licenseId);
      
      if (!licenseId) {
        $('#inspection_reports').html('<option value="">-- Select PARTIELLE --</option>');
        return;
      }
      
      $('#inspection_reports').html('<option value="">Loading...</option>').prop('disabled', true);
      
      // ✅ CRITICAL: Pass license_id, NOT license_number
      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/getPartielleOptions',
        method: 'GET',
        data: { license_id: licenseId },  // ✅ Use license_id
        dataType: 'json',
        success: function(res) {
          console.log('📥 PARTIELLE options response:', res);
          
          $('#inspection_reports').html('<option value="">-- Select PARTIELLE --</option>').prop('disabled', false);
          
          if (res.success && res.data && res.data.length > 0) {
            res.data.forEach(function(partial) {
              $('#inspection_reports').append(
                new Option(partial.partial_name, partial.partial_name)
              );
            });
            console.log('✅ Loaded', res.data.length, 'PARTIELLE options');
          } else {
            console.log('ℹ️ No PARTIELLE options found for this license');
          }
        },
        error: function(xhr) {
          console.error('❌ Failed to load PARTIELLE options:', xhr);
          $('#inspection_reports').html('<option value="">-- Error Loading --</option>').prop('disabled', false);
        }
      });
    }

    // ✅ Load PARTIELLE when editing import - Uses license_id
    function loadPartielleForEdit(licenseId, selectedPartielle) {
      console.log('✏️ Loading PARTIELLE for edit:', {
        license_id: licenseId,
        selected: selectedPartielle
      });
      
      if (!licenseId) {
        return;
      }
      
      // ✅ Use license_id
      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/getPartielleOptions',
        method: 'GET',
        data: { license_id: licenseId },  // ✅ Use license_id
        dataType: 'json',
        success: function(res) {
          $('#inspection_reports').html('<option value="">-- Select PARTIELLE --</option>');
          
          if (res.success && res.data && res.data.length > 0) {
            res.data.forEach(function(partial) {
              $('#inspection_reports').append(
                new Option(partial.partial_name, partial.partial_name)
              );
            });
            
            if (selectedPartielle) {
              $('#inspection_reports').val(selectedPartielle);
              console.log('✅ Selected PARTIELLE:', selectedPartielle);
            }
          }
        },
        error: function(xhr) {
          console.error('❌ Failed to load PARTIELLE for edit:', xhr);
        }
      });
    }

    // ========================================
    // HELPER FUNCTIONS
    // ========================================

    function updateStatusModeBadge(isAuto) {
      const badge = $('#statusModeBadge');
      const field = $('#clearing_status');
      
      if (isAuto) {
        badge.text('Auto').removeClass('manual').addClass('auto');
        field.removeClass('clearing-status-manual-mode').addClass('clearing-status-auto-mode');
      } else {
        badge.text('Manual').removeClass('auto').addClass('manual');
        field.removeClass('clearing-status-auto-mode').addClass('clearing-status-manual-mode');
      }
    }
    
    function escapeHtml(text) {
      if (text === null || text === undefined) return '';
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    let remarksArray = [];
    let remarkCounter = 0;
    let activeFilters = [];
    let bulkUpdateData = [];

    function updateDocumentStatus() {
      const crfDate = $('#crf_received_date').val();
      const adDate = $('#ad_date').val();
      const insuranceDate = $('#insurance_date').val();
      
      let statusId = 1;
      
      if (crfDate && adDate && insuranceDate) {
        statusId = 7;
      } else if (crfDate && insuranceDate) {
        statusId = 6;
      } else if (adDate && insuranceDate) {
        statusId = 4;
      } else if (crfDate && adDate) {
        statusId = 3;
      } else if (crfDate) {
        statusId = 2;
      }
      
      $('#document_status').val(statusId);
    }
    
    $('.document-status-trigger').on('change', function() {
      updateDocumentStatus();
    });

    function suggestClearingStatus() {
      const borderWarehouseArrival = $('#border_warehouse_arrival_date').val();
      const quittanceDate = $('#quittance_date').val();
      
      let suggestedStatus = null;
      let suggestedStatusName = '';
      
      if (quittanceDate && clearingStatusIds.completed_id) {
        suggestedStatus = clearingStatusIds.completed_id;
        suggestedStatusName = 'COMPLETED';
      } else if (borderWarehouseArrival && clearingStatusIds.in_progress_id) {
        suggestedStatus = clearingStatusIds.in_progress_id;
        suggestedStatusName = 'IN PROGRESS';
      } else if (clearingStatusIds.in_transit_id) {
        suggestedStatus = clearingStatusIds.in_transit_id;
        suggestedStatusName = 'IN TRANSIT';
      }
      
      const isAutoMode = $('#clearing_status').data('auto-mode');
      
      if (isAutoMode !== false && suggestedStatus) {
        $('#clearing_status').val(suggestedStatus);
        $('#clearing_status').addClass('border-success');
        setTimeout(() => {
          $('#clearing_status').removeClass('border-success');
        }, 1000);
        
        console.log(`🤖 Auto-suggested: ${suggestedStatusName}`);
      } else if (isAutoMode === false) {
        console.log('🔧 Manual mode active - not auto-updating');
      }
    }
    
    $('.clearing-status-trigger').on('change', function() {
      suggestClearingStatus();
    });

    $('#clearing_status').on('change', function() {
      $(this).data('auto-mode', false);
      updateStatusModeBadge(false);
      console.log('🔧 User manually changed Clearing Status to:', $(this).find('option:selected').text());
    });

    $('#resetClearingStatusBtn').on('click', function() {
      $('#clearing_status').data('auto-mode', true);
      updateStatusModeBadge(true);
      suggestClearingStatus();
      Swal.fire({
        icon: 'success',
        title: 'Reset to Auto-Suggestion',
        text: 'Clearing Status will now auto-update based on dates',
        timer: 1500,
        showConfirmButton: false
      });
      console.log('🔄 Reset to auto-suggestion mode');
    });

    function adjustLogisticsLayout() {
      const clearanceId = parseInt($('#types_of_clearance').val());
      const transportMode = $('#transport_mode_display').val().toUpperCase();
      
      if (clearanceId === 3 && !transportMode.includes('AIR')) {
        $('#t1_number_col, #t1_date_col').show();
        $('#border_arrival_col_row1, #border_dispatch_col_row1').hide();
        console.log('✅ T1 shown - 5 fields: T1 Number, T1 Date, Arrival, Dispatch, DRC Entry');
      } else {
        $('#t1_number_col, #t1_date_col').hide();
        $('#t1_number, #t1_date').val('');
        $('#border_arrival_col_row1, #border_dispatch_col_row1').show();
        console.log('✅ T1 hidden - 5 fields: Arrival, Dispatch, DRC Entry, Border Arrival, Border Dispatch');
      }
    }

    $('#types_of_clearance').on('change', function() {
      adjustLogisticsLayout();
    });

    $('.date-after-prealert').on('change', function() {
      validateDateAgainstPreAlert($(this));
    });

    $('#pre_alert_date').on('change', function() {
      $('.date-after-prealert').each(function() {
        if ($(this).val()) {
          validateDateAgainstPreAlert($(this));
        }
      });
    });

    function validateDateAgainstPreAlert($field) {
      const preAlertDate = $('#pre_alert_date').val();
      const fieldValue = $field.val();
      const fieldId = $field.attr('id');
      const fieldLabel = $field.closest('.mb-3').find('label').first().text().replace('*', '').trim();

      if (!fieldValue || !preAlertDate) {
        $field.removeClass('is-invalid date-sequence-error');
        $(`#${fieldId}_error`).text('').hide();
        return true;
      }

      if (fieldValue < preAlertDate) {
        $field.addClass('is-invalid date-sequence-error');
        $(`#${fieldId}_error`).text(`${fieldLabel} cannot be before Pre-Alert Date (${formatDate(preAlertDate)})`).show();

        Swal.fire({
          icon: 'warning',
          title: 'Date Validation Error',
          html: `<strong>${escapeHtml(fieldLabel)}</strong> cannot be before <strong>Pre-Alert Date</strong>.<br><br>Pre-Alert Date: <strong>${escapeHtml(formatDate(preAlertDate))}</strong><br>Please select a date on or after the Pre-Alert Date.`,
          confirmButtonText: 'OK',
          confirmButtonColor: '#f39c12'
        });

        $field.val('');

        setTimeout(() => {
          $field.removeClass('date-sequence-error is-invalid');
          $(`#${fieldId}_error`).text('').hide();
        }, 3000);

        return false;
      }

      $field.removeClass('is-invalid date-sequence-error');
      $(`#${fieldId}_error`).text('').hide();
      return true;
    }

    $('#dispatch_from_airport').on('change', function() {
      validateAirportDateSequence();
    });

    $('#airport_arrival_date').on('change', function() {
      if ($('#dispatch_from_airport').val()) {
        validateAirportDateSequence();
      }
    });

    function validateAirportDateSequence() {
      const arrivalDate = $('#airport_arrival_date').val();
      const dispatchDate = $('#dispatch_from_airport').val();

      if (!dispatchDate || !arrivalDate) {
        $('#dispatch_from_airport').removeClass('is-invalid date-sequence-error');
        $('#dispatch_from_airport_error').text('').hide();
        return true;
      }

      if (dispatchDate < arrivalDate) {
        $('#dispatch_from_airport').addClass('is-invalid date-sequence-error');
        $('#dispatch_from_airport_error').text(`Dispatch from Airport cannot be before Airport Arrival Date (${formatDate(arrivalDate)})`).show();

        Swal.fire({
          icon: 'warning',
          title: 'Airport Date Validation Error',
          html: `<strong>Dispatch from Airport</strong> cannot be before <strong>Airport Arrival Date</strong>.<br><br>Airport Arrival Date: <strong>${escapeHtml(formatDate(arrivalDate))}</strong><br>Please select a valid dispatch date.`,
          confirmButtonText: 'OK',
          confirmButtonColor: '#f39c12'
        });

        $('#dispatch_from_airport').val('');

        setTimeout(() => {
          $('#dispatch_from_airport').removeClass('date-sequence-error is-invalid');
          $('#dispatch_from_airport_error').text('').hide();
        }, 3000);

        return false;
      }

      $('#dispatch_from_airport').removeClass('is-invalid date-sequence-error');
      $('#dispatch_from_airport_error').text('').hide();
      return true;
    }

    function handleTransportModeFields(transportModeId, transportModeName) {
      $('#air_fields, #air_entry_point_row, #wagon_field_row, #road_fields, #road_manifest_field, #logistics_fields').hide();
      $('#t1_number_col, #t1_date_col, #border_arrival_col_row1, #border_dispatch_col_row1').hide();
      
      const modeId = parseInt(transportModeId);
      const modeName = (transportModeName || '').toUpperCase();
      
      console.log('🚚 Transport Mode ID:', modeId, 'Name:', modeName);
      
      $('#entry_point_id, #entry_point_id_air').on('change', function() {
        const val = $(this).val();
        if (val) {
          $('#entry_point_id').val(val);
          $('#entry_point_id_air').val(val);
        }
      });
      
      if (modeId === 2 || modeName.includes('AIR')) {
        $('#air_fields').show();
        $('#air_entry_point_row').show();
        console.log('✈️ AIR mode: Air fields + Entry Point in Documentation');
      }
      else if (modeId === 3 || modeName.includes('RAIL')) {
        $('#road_manifest_field').show();
        $('#wagon_field_row').show();
        $('#road_fields').show();
        $('#logistics_fields').show();
        adjustLogisticsLayout();
        console.log('🚂 RAIL mode: Road Manifest + Wagon + Horse/Trailers/Container/Entry Point + all logistics');
      }
      else if (modeName.includes('ROAD')) {
        $('#road_manifest_field').show();
        $('#road_fields').show();
        $('#logistics_fields').show();
        adjustLogisticsLayout();
        console.log('🚛 ROAD mode: Road Manifest + Horse/Trailers/Container/Entry Point + all logistics');
      }
      else {
        $('#logistics_fields').show();
        adjustLogisticsLayout();
        console.log('📦 DEFAULT mode: Showing all logistics');
      }
    }

    const dateSequenceFields = [
      'arrival_date_zambia',
      'dispatch_from_zambia',
      'drc_entry_date',
      'border_warehouse_arrival_date',
      'dispatch_from_border'
    ];

    $('.date-sequence-field').on('change', function() {
      validateDateSequence($(this));
    });

    function validateDateSequence($field) {
      const fieldId = $field.attr('id');
      const sequence = parseInt($field.data('seq'));
      const currentValue = $field.val();

      if (!currentValue) {
        $field.removeClass('date-sequence-error is-invalid');
        $(`#${fieldId}_error`).text('').hide();
        return true;
      }

      const currentDate = new Date(currentValue);

      if (sequence > 1) {
        const prevFieldId = dateSequenceFields[sequence - 2];
        const prevValue = $(`#${prevFieldId}`).val();

        if (prevValue) {
          const prevDate = new Date(prevValue);

          if (currentDate < prevDate) {
            $field.val(prevValue);
            $field.addClass('date-sequence-error is-invalid');
            $(`#${fieldId}_error`).text(`Date cannot be before ${getFieldLabel(prevFieldId)} (${formatDate(prevValue)}). Auto-adjusted.`).show();

            $('html, body').animate({
              scrollTop: $field.offset().top - 100
            }, 500);

            Swal.fire({
              icon: 'warning',
              title: 'Date Sequence Error',
              html: `<strong>${escapeHtml(getFieldLabel(fieldId))}</strong> cannot be before <strong>${escapeHtml(getFieldLabel(prevFieldId))}</strong>.<br><br>Date auto-adjusted to: <strong>${escapeHtml(formatDate(prevValue))}</strong>`,
              confirmButtonText: 'OK',
              confirmButtonColor: '#f39c12',
              timer: 5000
            });

            setTimeout(() => {
              $field.removeClass('date-sequence-error is-invalid');
              $(`#${fieldId}_error`).text('').hide();
            }, 3000);

            return false;
          }
        }
      }

      $field.removeClass('date-sequence-error is-invalid');
      $(`#${fieldId}_error`).text('').hide();
      return true;
    }

    function getFieldLabel(fieldId) {
      const labels = {
        'arrival_date_zambia': 'Arrival Date Zambia',
        'dispatch_from_zambia': 'Dispatch from Zambia',
        'drc_entry_date': 'DRC Entry Date',
        'border_warehouse_arrival_date': 'Border Warehouse Arrival',
        'dispatch_from_border': 'Dispatch from Border'
      };
      return labels[fieldId] || fieldId;
    }

    function formatDate(dateStr) {
      if (!dateStr) return '';
      const date = new Date(dateStr);
      return date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    $('#subscriber_id').on('change', function() {
      const subscriberId = $(this).val();
      const selectedOption = $(this).find('option:selected');
      const liquidationPaidBy = selectedOption.data('liquidation');

      $('#license_id').html('<option value="">-- Select License --</option>');
      
      if (!subscriberId) {
        clearLicenseFields();
        $('#liquidation_paid_by').val('');
        return;
      }

      if (liquidationPaidBy == 1) {
        $('#liquidation_paid_by').val('Client');
      } else if (liquidationPaidBy == 2) {
        $('#liquidation_paid_by').val('Malabar');
      } else {
        $('#liquidation_paid_by').val('');
      }

      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/getLicenses',
        method: 'GET',
        data: { subscriber_id: subscriberId },
        dataType: 'json',
        success: function(res) {
          if (res.success && res.data.length > 0) {
            res.data.forEach(function(license) {
              $('#license_id').append(`<option value="${license.id}">${escapeHtml(license.license_number)}</option>`);
            });
          } else {
            Swal.fire({
              icon: 'info',
              title: 'No Import Licenses',
              text: 'No active import licenses found for this client.',
              timer: 3000,
              showConfirmButton: false
            });
          }
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load licenses',
            confirmButtonText: 'OK'
          });
        }
      });
    });

    function syncCurrencyFields(currencyId) {
      if (currencyId) {
        $('#fret_currency').val(currencyId);
        $('#other_charges_currency').val(currencyId);
        $('#fob_currency').val(currencyId);
        $('#insurance_amount_currency').val(currencyId);
      }
    }

    function clearLicenseFields() {
      $('#kind_hidden, #type_of_goods_hidden, #transport_mode_hidden, #currency_hidden').val('');
      $('#kind_display, #type_of_goods_display, #transport_mode_display, #currency_display').val('');
      $('#supplier, #crf_reference').val('');
      $('#commodity').html('<option value="">-- Select Commodity --</option>');
      $('#mca_ref').val('');
      $('#fret_currency, #other_charges_currency, #fob_currency, #insurance_amount_currency').val('');
      $('#liquidation_paid_by').val('');
      $('#air_fields, #air_entry_point_row, #wagon_field_row, #road_fields, #road_manifest_field, #logistics_fields').hide();
      $('#t1_number_col, #t1_date_col, #border_arrival_col_row1, #border_dispatch_col_row1').hide();
      $('#inspection_reports').html('<option value="">-- Select PARTIELLE --</option>');
    }

    function generateMCAReference() {
      const formAction = $('#formAction').val();
      if (formAction === 'update') return;

      const subscriberId = $('#subscriber_id').val();
      const licenseId = $('#license_id').val();
      
      if (!subscriberId || !licenseId) {
        $('#mca_ref').val('');
        return;
      }

      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/getNextMCASequence',
        method: 'POST',
        data: { csrf_token: csrfToken, subscriber_id: subscriberId, license_id: licenseId },
        dataType: 'json',
        success: function(res) {
          if (res.success) {
            $('#mca_ref').val(res.mca_ref);
          }
        },
        error: function() {
          console.error('Failed to generate MCA reference');
        }
      });
    }

    function exportToExcel(importId) {
      window.location.href = '<?= APP_URL ?>/import/crudData/exportImport?id=' + importId;
      
      Swal.fire({
        icon: 'success',
        title: 'Exporting...',
        text: 'Your export will download shortly',
        timer: 2000,
        showConfirmButton: false
      });
    }

    $('#exportAllBtn').on('click', function() {
      window.location.href = '<?= APP_URL ?>/import/crudData/exportAll';
      
      Swal.fire({
        icon: 'success',
        title: 'Exporting All Imports...',
        text: 'Your export will download shortly',
        timer: 2000,
        showConfirmButton: false
      });
    });

    $('#addRemarkBtn').on('click', () => addRemarkEntry());

    function addRemarkEntry(date = '', text = '') {
      remarkCounter++;
      const remarkId = `remark_${remarkCounter}`;
      const remarkHtml = `
        <div class="remarks-entry" id="${remarkId}">
          <button type="button" class="btn btn-sm btn-danger btn-remove" onclick="$('#${remarkId}').remove(); updateRemarksHidden();">
            <i class="ti ti-x"></i>
          </button>
          <div class="row">
            <div class="col-md-3 mb-2">
              <label>Date</label>
              <input type="date" class="form-control remark-date" value="${escapeHtml(date)}">
            </div>
            <div class="col-md-9 mb-2">
              <label>Remark Text</label>
              <textarea class="form-control remark-text" rows="2">${escapeHtml(text)}</textarea>
            </div>
          </div>
        </div>
      `;
      $('#remarksContainer').append(remarkHtml);
      updateRemarksHidden();
    }

    function updateRemarksHidden() {
      const remarks = [];
      $('.remarks-entry').each(function() {
        const date = $(this).find('.remark-date').val();
        const text = $(this).find('.remark-text').val();
        if (date || text) remarks.push({ date, text });
      });
      $('#remarks_hidden').val(JSON.stringify(remarks));
    }

    $(document).on('change input', '.remark-date, .remark-text', updateRemarksHidden);

    function validateForm() {
      clearValidationErrors();
      let errors = [];
      
      const requiredFields = [
        { id: 'subscriber_id', label: 'Client' },
        { id: 'license_id', label: 'License Number' },
        { id: 'regime', label: 'Regime' },
        { id: 'types_of_clearance', label: 'Types of Clearance' },
        { id: 'declaration_office_id', label: 'Declaration Office' },
        { id: 'pre_alert_date', label: 'Pre-Alert Date' },
        { id: 'invoice', label: 'Invoice' },
        { id: 'commodity', label: 'Commodity' },
        { id: 'weight', label: 'Weight' },
        { id: 'fob', label: 'FOB' },
        { id: 'clearing_status', label: 'Clearing Status' }
      ];
      
      const transportMode = $('#transport_mode_display').val().toUpperCase();
      if (transportMode.includes('AIR')) {
        requiredFields.push({ id: 'entry_point_id_air', label: 'Entry Point' });
      } else {
        requiredFields.push({ id: 'entry_point_id', label: 'Entry Point' });
      }

      requiredFields.forEach(field => {
        const value = $(`#${field.id}`).val();
        if (!value || value === '') {
          showFieldError(field.id, `${field.label} is required`);
          errors.push(`${field.label} is required`);
        }
      });

      const weight = parseFloat($('#weight').val());
      const fob = parseFloat($('#fob').val());
      
      if (isNaN(weight) || weight < 0) {
        showFieldError('weight', 'Weight must be a positive number');
        errors.push('Invalid weight');
      }
      
      if (isNaN(fob) || fob < 0) {
        showFieldError('fob', 'FOB must be a positive number');
        errors.push('Invalid FOB');
      }

      const preAlertDate = $('#pre_alert_date').val();
      if (preAlertDate) {
        $('.date-after-prealert').each(function() {
          const fieldVal = $(this).val();
          if (fieldVal && fieldVal < preAlertDate) {
            const fieldId = $(this).attr('id');
            const label = $(this).closest('.mb-3').find('label').first().text().replace('*', '').trim();
            showFieldError(fieldId, `${label} cannot be before Pre-Alert Date`);
            errors.push(`${label} cannot be before Pre-Alert Date`);
          }
        });
      }

      $('.date-sequence-field').each(function() {
        validateDateSequence($(this));
      });

      validateAirportDateSequence();

      return { isValid: errors.length === 0, errors };
    }

    function clearValidationErrors() {
      $('.form-control, .form-select').removeClass('is-invalid');
      $('.invalid-feedback').text('').hide();
    }

    function showFieldError(fieldId, errorMessage) {
      $('#' + fieldId).addClass('is-invalid');
      $('#' + fieldId + '_error').text(errorMessage).show();
    }

    $('#importForm').on('submit', function (e) {
      e.preventDefault();
      
      updateDocumentStatus();
      
      if ($('#air_entry_point_row').is(':visible')) {
        $('#entry_point_id').val($('#entry_point_id_air').val());
      }
      
      const validation = validateForm();
      
      if (!validation.isValid) {
        $('#importTracking').collapse('show');
        
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          html: '<ul style="text-align:left;"><li>' + validation.errors.map(err => escapeHtml(err)).join('</li><li>') + '</li></ul>',
          confirmButtonText: 'OK'
        });
        
        const firstError = $('.is-invalid').first();
        if (firstError.length) {
          $('html, body').animate({
            scrollTop: firstError.offset().top - 100
          }, 300);
        }
        
        return false;
      }

      const submitBtn = $('#submitBtn');
      const originalText = submitBtn.html();
      submitBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Saving...');

      const formData = new FormData(this);
      formData.set('csrf_token', csrfToken);
      
      $('#document_status').prop('disabled', false);

      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/' + $('#formAction').val(),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (res) {
          submitBtn.prop('disabled', false).html(originalText);
          $('#document_status').prop('disabled', true);
          
          if (res.success) {
            Swal.fire({ 
              icon: 'success', 
              title: 'Success!', 
              text: res.message, 
              timer: 1500, 
              showConfirmButton: false 
            });
            
            resetForm();
            $('#importTracking').collapse('hide');
            
            if (typeof importsTable !== 'undefined') {
              importsTable.ajax.reload(null, false);
            }
            updateStatistics();
          } else {
            Swal.fire({ 
              icon: 'error', 
              title: 'Error!', 
              html: res.message,
              confirmButtonText: 'OK'
            });
          }
        },
        error: function (xhr) {
          submitBtn.prop('disabled', false).html(originalText);
          $('#document_status').prop('disabled', true);
          
          let errorMsg = 'An error occurred while processing your request';
          
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMsg = xhr.responseJSON.message;
          } else if (xhr.status === 403) {
            errorMsg = 'Security token expired. Please refresh the page and try again.';
          }
          
          Swal.fire({ 
            icon: 'error', 
            title: 'Server Error', 
            html: errorMsg,
            confirmButtonText: 'OK'
          });
        }
      });
    });

    function resetForm() {
      $('#importForm')[0].reset();
      clearValidationErrors();
      $('#import_id, #mca_ref, #crf_reference').val('');
      $('#formAction').val('insert');
      $('#formTitle').text('Add New Import');
      $('#submitBtnText').text('Save Import');
      $('#resetFormBtn').hide();
      $('#remarksContainer').empty();
      clearLicenseFields();
      
      $('#types_of_clearance').val('1');
      $('#document_status').val('1');
      
      if (clearingStatusIds.in_transit_id) {
        $('#clearing_status').val(clearingStatusIds.in_transit_id);
      }
      $('#clearing_status').data('auto-mode', true);
      updateStatusModeBadge(true);
      
      $('#liquidation_paid_by').val('');
      
      $('#importTracking').collapse('hide');
      
      loadCommoditiesForLicense();
    }

    $('#cancelBtn, #resetFormBtn').on('click', (e) => { 
      e.preventDefault(); 
      resetForm(); 
    });

    $('.stats-card').on('click', function() {
      const filter = $(this).data('filter');
      
      if (filter === 'all') {
        $('.stats-card').removeClass('active');
        $(this).addClass('active');
        activeFilters = [];
      } else {
        $('.stats-card[data-filter="all"]').removeClass('active');
        
        if ($(this).hasClass('active')) {
          $(this).removeClass('active');
          activeFilters = activeFilters.filter(f => f !== filter);
        } else {
          $(this).addClass('active');
          if (!activeFilters.includes(filter)) {
            activeFilters.push(filter);
          }
        }
      }
      
      updateActiveFiltersDisplay();
      applyFiltersToTable();
      updateBulkUpdateButton();
    });

    $('#clearFilters').on('click', function() {
      $('.stats-card').removeClass('active');
      activeFilters = [];
      updateActiveFiltersDisplay();
      applyFiltersToTable();
      updateBulkUpdateButton();
    });

    function updateActiveFiltersDisplay() {
      if (activeFilters.length > 0) {
        $('#activeFiltersBadge').show().text(activeFilters.length + ' Filter' + (activeFilters.length > 1 ? 's' : '') + ' Active');
      } else {
        $('#activeFiltersBadge').hide();
      }
    }

    function applyFiltersToTable() {
      if (typeof importsTable !== 'undefined') {
        importsTable.ajax.reload();
      }
    }

    function updateBulkUpdateButton() {
      if (activeFilters.length > 0) {
        $('#bulkUpdateBtn').prop('disabled', false);
      } else {
        $('#bulkUpdateBtn').prop('disabled', true);
      }
    }

    $('#bulkUpdateBtn').on('click', function() {
      if (activeFilters.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'No Filter Selected',
          text: 'Please select at least one filter from the statistics cards before bulk updating.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#f39c12'
        });
        return;
      }

      $('#bulkUpdateModal').modal('show');
      
      $('#bulkUpdateContent').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3">Loading import records...</p></div>');

      const filterNames = {
        'completed': 'Completed',
        'in_progress': 'In Progress',
        'in_transit': 'In Transit',
        'crf_missing': 'CRF Missing',
        'ad_missing': 'AD Missing',
        'insurance_missing': 'Insurance Missing',
        'audited_pending': 'Audited Pending',
        'archived_pending': 'Archived Pending',
        'dgda_in_pending': 'DGDA In Pending',
        'liquidation_pending': 'Liquidation Pending',
        'quittance_pending': 'Quittance Pending'
      };
      
      const activeFilterNames = activeFilters.map(f => filterNames[f] || f).join(', ');
      $('#bulkFilterSummary').html(`<strong>Active Filters:</strong> ${escapeHtml(activeFilterNames)}`);

      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/getBulkUpdateData',
        method: 'GET',
        data: { filters: activeFilters },
        dataType: 'json',
        success: function(res) {
          if (res.success && res.data) {
            bulkUpdateData = res.data;
            renderBulkUpdateTable(res.data, res.relevant_fields || []);
          } else {
            $('#bulkUpdateContent').html(`
              <div class="alert alert-info">
                <i class="ti ti-info-circle me-2"></i>
                ${escapeHtml(res.message || 'No records found matching the selected filters.')}
              </div>
            `);
            $('#saveBulkUpdateBtn').prop('disabled', true);
          }
        },
        error: function(xhr) {
          let errorMsg = 'Failed to load bulk update data. Please try again.';
          
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMsg = xhr.responseJSON.message;
          }
          
          $('#bulkUpdateContent').html(`
            <div class="alert alert-danger">
              <i class="ti ti-alert-circle me-2"></i>
              ${escapeHtml(errorMsg)}
            </div>
          `);
          $('#saveBulkUpdateBtn').prop('disabled', true);
        }
      });
    });

    function renderBulkUpdateTable(data, relevantFields) {
      if (!data || data.length === 0) {
        $('#bulkUpdateContent').html(`
          <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            No records found for bulk update.
          </div>
        `);
        $('#saveBulkUpdateBtn').prop('disabled', true);
        return;
      }

      let tableHtml = `
        <div class="bulk-table-container">
          <table class="bulk-update-table">
            <thead>
              <tr>
                <th style="width: 40px;">
                  <input type="checkbox" class="form-check-input" id="selectAllBulk">
                </th>
                <th style="width: 120px;">MCA Ref</th>
                <th style="width: 100px;">Client</th>
                <th style="width: 100px;">Pre-Alert</th>
      `;

      const fieldConfig = {
        'crf_reference': { label: 'CRF Reference', type: 'text' },
        'crf_received_date': { label: 'CRF Date', type: 'date' },
        'ad_date': { label: 'AD Date', type: 'date' },
        'insurance_date': { label: 'Insurance Date', type: 'date' },
        'insurance_amount': { label: 'Insurance Amount', type: 'number' },
        'audited_date': { label: 'Audited Date', type: 'date' },
        'archived_date': { label: 'Archived Date', type: 'date' },
        'dgda_in_date': { label: 'DGDA In Date', type: 'date' },
        'liquidation_date': { label: 'Liquidation Date', type: 'date' },
        'quittance_date': { label: 'Quittance Date', type: 'date' }
      };

      relevantFields.forEach(field => {
        const config = fieldConfig[field];
        if (config) {
          tableHtml += `<th style="width: 140px;">${escapeHtml(config.label)}</th>`;
        }
      });

      tableHtml += `</tr></thead><tbody>`;

      data.forEach((row, index) => {
        tableHtml += `
          <tr data-import-id="${parseInt(row.id)}">
            <td>
              <input type="checkbox" class="form-check-input bulk-row-checkbox" data-index="${index}">
            </td>
            <td>
              <span class="mca-ref-badge">${escapeHtml(row.mca_ref || '')}</span>
            </td>
            <td><small>${escapeHtml(row.subscriber_name || '')}</small></td>
            <td>
              <small class="pre-alert-date-text">${escapeHtml(formatDate(row.pre_alert_date) || '')}</small>
            </td>
        `;

        relevantFields.forEach(field => {
          const config = fieldConfig[field];
          if (config) {
            const currentValue = row[field] || '';
            
            if (config.type === 'text') {
              tableHtml += `
                <td>
                  <input type="text" 
                         class="form-control bulk-field" 
                         data-field="${field}" 
                         data-import-id="${parseInt(row.id)}"
                         value="${escapeHtml(currentValue)}" 
                         maxlength="100">
                </td>
              `;
            } else if (config.type === 'date') {
              tableHtml += `
                <td>
                  <input type="date" 
                         class="form-control bulk-field" 
                         data-field="${field}" 
                         data-import-id="${parseInt(row.id)}"
                         value="${escapeHtml(currentValue)}">
                </td>
              `;
            } else if (config.type === 'number') {
              tableHtml += `
                <td>
                  <input type="number" 
                         step="0.01" 
                         class="form-control bulk-field" 
                         data-field="${field}" 
                         data-import-id="${parseInt(row.id)}"
                         value="${escapeHtml(currentValue)}" 
                         min="0">
                </td>
              `;
            }
          }
        });

        tableHtml += `</tr>`;
      });

      tableHtml += `</tbody></table></div>`;

      $('#bulkUpdateContent').html(tableHtml);
      $('#saveBulkUpdateBtn').prop('disabled', false);

      attachBulkUpdateHandlers();
    }

    function attachBulkUpdateHandlers() {
      $('#selectAllBulk').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.bulk-row-checkbox').prop('checked', isChecked);
        
        if (isChecked) {
          $('.bulk-update-table tbody tr').addClass('selected');
        } else {
          $('.bulk-update-table tbody tr').removeClass('selected');
        }
      });

      $('.bulk-row-checkbox').on('change', function() {
        const $row = $(this).closest('tr');
        
        if ($(this).prop('checked')) {
          $row.addClass('selected');
        } else {
          $row.removeClass('selected');
        }

        const totalRows = $('.bulk-row-checkbox').length;
        const checkedRows = $('.bulk-row-checkbox:checked').length;
        $('#selectAllBulk').prop('checked', totalRows === checkedRows);
      });

      $('.bulk-field').on('change', function() {
        const $row = $(this).closest('tr');
        $row.find('.bulk-row-checkbox').prop('checked', true).trigger('change');
        $(this).addClass('border-warning');
      });
    }

    $('#saveBulkUpdateBtn').on('click', function() {
      const selectedRows = $('.bulk-row-checkbox:checked');
      
      if (selectedRows.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'No Records Selected',
          text: 'Please select at least one record to update by checking the checkbox.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#f39c12'
        });
        return;
      }

      const updateData = [];
      
      selectedRows.each(function() {
        const $row = $(this).closest('tr');
        const importId = parseInt($row.data('import-id'));
        const rowData = { import_id: importId };
        
        $row.find('.bulk-field').each(function() {
          const field = $(this).data('field');
          const value = $(this).val();
          
          if (value !== null && value !== undefined && value !== '') {
            rowData[field] = value;
          } else {
            rowData[field] = null;
          }
        });
        
        if (Object.keys(rowData).length > 1) {
          updateData.push(rowData);
        }
      });

      if (updateData.length === 0) {
        Swal.fire({
          icon: 'info',
          title: 'No Changes Detected',
          text: 'No field changes detected in the selected records.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#3085d6'
        });
        return;
      }

      Swal.fire({
        title: 'Confirm Bulk Update',
        html: `You are about to update <strong>${updateData.length}</strong> record(s).<br><br>Are you sure you want to proceed?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Update',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#28a745'
      }).then((result) => {
        if (result.isConfirmed) {
          performBulkUpdate(updateData);
        }
      });
    });

    function performBulkUpdate(updateData) {
      const $saveBtn = $('#saveBulkUpdateBtn');
      const originalText = $saveBtn.html();
      $saveBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Updating...');

      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/bulkUpdate',
        method: 'POST',
        data: {
          csrf_token: csrfToken,
          update_data: JSON.stringify(updateData)
        },
        dataType: 'json',
        success: function(res) {
          $saveBtn.prop('disabled', false).html(originalText);
          
          if (res.success) {
            let message = res.message || 'Bulk update completed successfully!';
            
            if (res.error_count > 0) {
              message += `<br><br><strong>Errors:</strong><br>`;
              message += '<ul style="text-align:left; max-height:200px; overflow-y:auto;">';
              (res.errors || []).forEach(error => {
                message += `<li>${escapeHtml(error)}</li>`;
              });
              message += '</ul>';
            }

            Swal.fire({
              icon: res.error_count > 0 ? 'warning' : 'success',
              title: 'Bulk Update Complete',
              html: message,
              confirmButtonText: 'OK'
            }).then(() => {
              $('#bulkUpdateModal').modal('hide');
              
              if (typeof importsTable !== 'undefined') {
                importsTable.ajax.reload(null, false);
              }
              updateStatistics();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Update Failed',
              html: res.message || 'Bulk update failed. Please try again.',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function(xhr) {
          $saveBtn.prop('disabled', false).html(originalText);
          
          let errorMsg = 'Failed to perform bulk update. Please try again.';
          
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMsg = xhr.responseJSON.message;
          } else if (xhr.status === 403) {
            errorMsg = 'Security token expired. Please refresh the page and try again.';
          }
          
          Swal.fire({
            icon: 'error',
            title: 'Server Error',
            html: errorMsg,
            confirmButtonText: 'OK'
          });
        }
      });
    }

    var importsTable;
    function initDataTable() {
      if ($.fn.DataTable.isDataTable('#importsTable')) {
        $('#importsTable').DataTable().destroy();
      }

      importsTable = $('#importsTable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: { 
          url: '<?= APP_URL ?>/import/crudData/listing', 
          type: 'GET',
          data: function(d) {
            d.filters = activeFilters;
          },
          error: function(xhr, error, code) {
            console.error('DataTable error:', error, code);
          }
        },
        columns: [
          { data: 'id' },
          { data: 'mca_ref', render: function(data) { return escapeHtml(data); } },
          { data: 'subscriber_name', render: function(data) { return escapeHtml(data); } },
          { data: 'license_number', render: function(data) { return escapeHtml(data); } },
          { data: 'invoice', render: function(data) { return escapeHtml(data); } },
          { 
            data: 'pre_alert_date',
            render: function(data) {
              return data ? escapeHtml(formatDate(data)) : '';
            }
          },
          { 
            data: 'weight', 
            render: (data) => data ? parseFloat(data).toFixed(2) : '0.00' 
          },
          { 
            data: 'fob', 
            render: (data) => data ? parseFloat(data).toFixed(2) : '0.00' 
          },
          { data: 'clearing_status', render: function(data) { return escapeHtml(data); } },
          {
            data: null, 
            orderable: false, 
            searchable: false,
            render: (data, type, row) => `
              <button class="btn btn-sm btn-primary editBtn" data-id="${parseInt(row.id)}" title="Edit">
                <i class="ti ti-edit"></i>
              </button>
              <button class="btn btn-sm btn-export exportBtn" data-id="${parseInt(row.id)}" title="Export to Excel">
                <i class="ti ti-file-spreadsheet"></i>
              </button>
              <button class="btn btn-sm btn-danger deleteBtn" data-id="${parseInt(row.id)}" title="Delete">
                <i class="ti ti-trash"></i>
              </button>
            `
          }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        responsive: false,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
      });
    }

    function updateStatistics() {
      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/statistics',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
          if (res.success) {
            $('#totalTrackings').text(res.data.total_imports || 0);
            $('#totalCompleted').text(res.data.total_completed || 0);
            $('#totalInProgress').text(res.data.in_progress || 0);
            $('#totalInTransit').text(res.data.in_transit || 0);
            $('#totalCRFMissing').text(res.data.crf_missing || 0);
            $('#totalADMissing').text(res.data.ad_missing || 0);
            $('#totalInsuranceMissing').text(res.data.insurance_missing || 0);
            $('#totalAuditedPending').text(res.data.audited_pending || 0);
            $('#totalArchivedPending').text(res.data.archived_pending || 0);
            $('#totalDgdaInPending').text(res.data.dgda_in_pending || 0);
            $('#totalLiquidationPending').text(res.data.liquidation_pending || 0);
            $('#totalQuittancePending').text(res.data.quittance_pending || 0);
          }
        },
        error: function() {
          console.error('Failed to load statistics');
        }
      });
    }

    $(document).on('click', '.exportBtn', function () {
      const id = parseInt($(this).data('id'));
      exportToExcel(id);
    });

    $(document).on('click', '.editBtn', function () {
      const id = parseInt($(this).data('id'));
      
      $.ajax({
        url: '<?= APP_URL ?>/import/crudData/getImport',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
          if (res.success && res.data) {
            const data = res.data;
            
            clearValidationErrors();
            
            $('#import_id').val(data.id);
            $('#formAction').val('update');
            $('#formTitle').text('Edit Import');
            $('#submitBtnText').text('Update Import');
            $('#resetFormBtn').show();
            
            $('#subscriber_id').val(data.subscriber_id).trigger('change');
            
            setTimeout(() => {
              $('#license_id').val(data.license_id).trigger('change');
              
              setTimeout(() => {
                Object.keys(data).forEach(key => {
                  const $field = $(`#${key}`);
                  if ($field.length && data[key] !== null) {
                    $field.val(data[key]);
                  }
                });
                
                if (data.entry_point_id) {
                  $('#entry_point_id').val(data.entry_point_id);
                  $('#entry_point_id_air').val(data.entry_point_id);
                }
                
                if (data.client_liquidation_paid_by == 1) {
                  $('#liquidation_paid_by').val('Client');
                } else if (data.client_liquidation_paid_by == 2) {
                  $('#liquidation_paid_by').val('Malabar');
                }
                
                if (data.remarks) {
                  try {
                    const remarks = JSON.parse(data.remarks);
                    $('#remarksContainer').empty();
                    remarks.forEach(r => addRemarkEntry(r.date, r.text));
                  } catch (e) {
                    console.error('Failed to parse remarks:', e);
                  }
                }
                
                const licenseId = data.license_id;
                const selectedPartielle = data.inspection_reports;
                loadPartielleForEdit(licenseId, selectedPartielle);
                
                updateDocumentStatus();
                adjustLogisticsLayout();
                
                $('#clearing_status').data('auto-mode', false); 
                updateStatusModeBadge(false);
                
                $('#importTracking').collapse('show');
                $('html, body').animate({ 
                  scrollTop: $('#importForm').offset().top - 100 
                }, 500);
              }, 800);
            }, 500);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              html: res.message || 'Failed to load import data',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function (xhr) {
          let errorMsg = 'Failed to load import data';
          
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMsg = xhr.responseJSON.message;
          }
          
          Swal.fire({
            icon: 'error',
            title: 'Error',
            html: errorMsg,
            confirmButtonText: 'OK'
          });
        }
      });
    });

    $(document).on('click', '.deleteBtn', function () {
      const id = parseInt($(this).data('id'));
      
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '<?= APP_URL ?>/import/crudData/deletion',
            method: 'POST',
            data: { id: id, csrf_token: csrfToken },
            dataType: 'json',
            success: function (res) {
              if (res.success) {
                Swal.fire({ 
                  icon: 'success', 
                  title: 'Deleted!', 
                  text: res.message,
                  timer: 1500, 
                  showConfirmButton: false 
                });
                importsTable.ajax.reload(null, false);
                updateStatistics();
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  html: res.message || 'Delete failed',
                  confirmButtonText: 'OK'
                });
              }
            },
            error: function (xhr) {
              let errorMsg = 'Failed to delete import';
              
              if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
              } else if (xhr.status === 403) {
                errorMsg = 'Security token expired. Please refresh the page and try again.';
              }
              
              Swal.fire({
                icon: 'error',
                title: 'Error',
                html: errorMsg,
                confirmButtonText: 'OK'
              });
            }
          });
        }
      });
    });

    initDataTable();
    updateStatistics();
    updateDocumentStatus();
    loadCommoditiesForLicense();
  });
</script>