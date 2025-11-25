<?php
/* View: tracking/exports.php - Complete Export Management View */
?>
<link href="<?= BASE_URL ?>/assets/pages/css/local_styles.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<style>
  .dataTables_wrapper .dataTables_info { float: left; }
  .dataTables_wrapper .dataTables_paginate { float: right; text-align: right; }
  
  .dt-buttons { float: left; margin-bottom: 10px; }
  .buttons-excel {
    background: #28a745 !important; color: white !important; border: none !important;
    padding: 8px 20px !important; border-radius: 5px !important; font-weight: 500 !important;
    transition: all 0.3s !important; box-shadow: none !important;
  }
  .buttons-excel:hover {
    background: #218838 !important; color: white !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4) !important;
  }
  
  .btn-export {
    background: #28a745; color: white; border: none;
  }
  .btn-export:hover {
    background: #218838; color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
  }
  
  .btn-bulk-update {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    color: white; border: none; font-weight: 500;
  }
  .btn-bulk-update:hover {
    background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
    color: white; transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(243, 156, 18, 0.4);
  }
  .btn-bulk-update:disabled {
    background: #95a5a6 !important;
    cursor: not-allowed; opacity: 0.6;
  }
  
  .text-danger { color: #dc3545; font-weight: bold; }
  .is-invalid { border-color: #dc3545 !important; }
  .invalid-feedback { display: block; color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem; }
  
  .btn-view {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white; border: none;
  }
  .btn-view:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white; transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
  }
  
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
  .icon-amber { background: linear-gradient(135deg, #FFC107 0%, #FF9800 100%); }
  .icon-lime { background: linear-gradient(135deg, #8BC34A 0%, #689F38 100%); }
  
  .stats-value {
    font-size: 1.4rem; font-weight: 700; color: #2C3E50;
    margin-bottom: 2px; line-height: 1.2;
  }
  .stats-label {
    font-size: 0.75rem; color: #7F8C8D;
    font-weight: 500; line-height: 1.2;
  }
  
  .stats-card .card-body::after {
    content: ""; display: table; clear: both;
  }
  
  .modal-content { border: none; border-radius: 15px; overflow: hidden; }
  .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white; border: none; padding: 20px 30px;
  }
  .modal-header .btn-close { filter: brightness(0) invert(1); }
  
  .auto-generated-field { background-color: #f8f9fa; cursor: not-allowed; }
  .readonly-field { background-color: #e9ecef; cursor: not-allowed; }
  
  .accordion-button:not(.collapsed) { background-color: #667eea; color: white; }
  
  .accordion-body {
    padding: 0.75rem;
  }
  
  /* 5 COLUMNS PER ROW - EQUAL WIDTH */
  .col-5-per-row {
    flex: 0 0 auto;
    width: 20%;
    padding-right: 4px;
    padding-left: 4px;
  }
  
  @media (max-width: 1400px) {
    .col-5-per-row { width: 25%; }
  }
  
  @media (max-width: 992px) {
    .col-5-per-row { width: 33.333%; }
  }
  
  @media (max-width: 768px) {
    .col-5-per-row { width: 50%; }
  }
  
  @media (max-width: 576px) {
    .col-5-per-row { width: 100%; }
  }
  
  .form-control, .form-select {
    height: 38px;
    font-size: 0.875rem;
  }
  
  .form-label {
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.15rem;
    color: #495057;
  }
  
  .mb-3 {
    margin-bottom: 0.5rem !important;
  }

  .row {
    margin-left: -4px;
    margin-right: -4px;
    margin-bottom: 0.25rem;
    display: flex;
    flex-wrap: wrap;
  }
  
  .row:last-child {
    margin-bottom: 0;
  }

  .filter-indicator {
    position: absolute; top: 8px; right: 8px;
    background: #007bff; color: white; border-radius: 50%;
    width: 20px; height: 20px; display: none;
    align-items: center; justify-content: center;
    font-size: 10px; font-weight: bold;
  }
  .stats-card.active .filter-indicator { display: flex; }
  
  .dataTables_wrapper .dataTables_scroll {
    overflow-x: auto;
  }
  
  .dataTables_wrapper .dataTables_scrollBody {
    overflow-x: auto;
  }

  #exportsTable {
    width: 100% !important;
  }

  #exportsTable th, #exportsTable td {
    white-space: nowrap;
    padding: 8px 12px;
  }
  
  .bulk-create-info {
    background: #d1ecf1;
    border: 1px solid #17a2b8;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    text-align: center;
  }
  
  .bulk-create-info p {
    margin-bottom: 0;
    font-size: 0.95rem;
  }
  
  .bulk-create-info .text-warning {
    margin-top: 8px;
  }
  
  #numEntriesInput {
    width: 100%;
    font-weight: bold;
    font-size: 1rem;
  }
  
  .bulk-create-summary {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
  }
  
  .bulk-create-summary h6 {
    color: #856404;
    margin-bottom: 10px;
    font-weight: 600;
  }

  .section-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5px 15px;
    border-radius: 8px;
    margin-top: 10px;
    margin-bottom: 6px;
    font-weight: 600;
    font-size: 0.95rem;
  }
  
  .section-header:first-child {
    margin-top: 0;
  }

  .edit-only-section {
    display: none;
  }
  
  .edit-only-section.show {
    display: block;
  }

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
  
  .loading-date-text {
    color: #6c757d;
    font-size: 0.75rem;
    display: block;
    margin-top: 2px;
  }
  
  .bulk-table-container {
    max-height: 500px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
  }

  #bulkInsertModal .modal-dialog {
    max-width: 98%;
    width: 98%;
    margin: 1rem auto;
  }

  #bulkInsertModal .modal-body {
    padding: 20px;
    max-height: calc(100vh - 250px);
    overflow-y: auto;
  }

  .bulk-insert-scrollable {
    overflow-x: auto;
    overflow-y: visible;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    position: relative;
  }

  .bulk-insert-table {
    width: max-content;
    min-width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
    margin: 0;
  }

  .bulk-insert-table thead th {
    background: #667eea;
    color: white;
    padding: 12px 10px;
    font-weight: 600;
    border: 1px solid #5568d3;
    position: sticky;
    top: 0;
    z-index: 10;
    font-size: 0.8rem;
    white-space: nowrap;
    min-width: 140px;
  }

  .bulk-insert-table tbody td {
    padding: 8px 10px;
    border: 1px solid #dee2e6;
    vertical-align: middle;
    background: white;
  }

  .bulk-insert-table .form-control,
  .bulk-insert-table .form-select {
    font-size: 0.8rem;
    padding: 6px 10px;
    height: 36px;
    min-width: 130px;
    width: 100%;
  }

  .bulk-insert-table input[type="date"] {
    font-size: 0.75rem;
    min-width: 150px;
  }

  .bulk-insert-table input[type="number"] {
    text-align: right;
    min-width: 110px;
  }

  .bulk-insert-table input[type="text"] {
    min-width: 130px;
  }

  .row-number {
    background: #f8f9fa;
    font-weight: 600;
    text-align: center;
    min-width: 50px !important;
    position: sticky;
    left: 0;
    z-index: 5;
    border-right: 2px solid #667eea !important;
  }

  .mca-ref-cell {
    position: sticky;
    left: 50px;
    z-index: 5;
    background: white;
    border-right: 2px solid #667eea !important;
    min-width: 180px !important;
  }

  .bulk-insert-table thead th:first-child,
  .bulk-insert-table thead th:nth-child(2) {
    position: sticky;
    z-index: 15;
    background: #667eea;
  }

  .bulk-insert-table thead th:first-child {
    left: 0;
  }

  .bulk-insert-table thead th:nth-child(2) {
    left: 50px;
  }

  .auto-propagate-field {
    border: 2px solid #28a745 !important;
    background-color: #f0fff4;
  }
  
  .date-validation-error {
    border: 2px solid #dc3545 !important;
    background-color: #ffe6e6 !important;
  }

  /* ✅ FIXED: Seal Selection - Using Bootstrap Input Group Pattern */
  .seal-input-group {
    display: flex;
    width: 100%;
  }

  .seal-input-group input.form-control {
    flex: 1;
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
    height: 38px;
  }

  .seal-input-group .btn-select-seals {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: 1px solid #28a745;
    border-left: 1px solid #28a745;
    padding: 0;
    margin-left: -1px;
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
    border-top-right-radius: 0.375rem !important;
    border-bottom-right-radius: 0.375rem !important;
    font-weight: 700;
    transition: all 0.3s;
    font-size: 1.3rem;
    line-height: 1;
    min-width: 40px;
    width: 40px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
  }
  
  .seal-input-group .btn-select-seals:hover {
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
    color: white;
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.4);
  }

  /* ✅ UPDATED: Transport Mode Conditional Fields */
  .transport-conditional-field {
    display: none !important;
  }

  .transport-conditional-field.show {
    display: flex !important;
  }

  .select2-container {
    width: 100% !important;
  }

  /* ✅ FIXED: Seal Selection Modal - High z-index */
  #sealSelectionModal {
    z-index: 9999 !important;
  }

  #sealSelectionModal .modal-backdrop {
    z-index: 9998 !important;
  }

  .seal-checkbox-item {
    padding: 10px;
    border-bottom: 1px solid #e9ecef;
    cursor: pointer;
    transition: background 0.2s;
  }

  .seal-checkbox-item:hover {
    background: #f8f9fa;
  }

  .seal-checkbox-item input[type="checkbox"] {
    margin-right: 10px;
    width: 18px;
    height: 18px;
    cursor: pointer;
  }

  .seal-checkbox-item label {
    margin: 0;
    cursor: pointer;
    font-size: 0.9rem;
    user-select: none;
  }

  #sealSelectionModal .modal-body {
    max-height: 400px;
    overflow-y: auto;
  }
</style>

<div class="page-content">
  <div class="page-container">
    <div class="row">
      <div class="col-12">
        
        <!-- Statistics Cards with Icons - 12 Cards -->
        <div class="row mb-4">
          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="all">
              <div class="card-body">
                <div class="stats-card-icon icon-blue">
                  <i class="ti ti-truck-delivery"></i>
                </div>
                <div class="stats-value" id="totalTrackings">0</div>
                <div class="stats-label">Total Exports</div>
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
            <div class="card stats-card shadow-sm" data-filter="ceec_pending">
              <div class="card-body">
                <div class="stats-card-icon icon-purple">
                  <i class="ti ti-file-certificate"></i>
                </div>
                <div class="stats-value" id="totalCEECPending">0</div>
                <div class="stats-label">CEEC Pending</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="min_div_pending">
              <div class="card-body">
                <div class="stats-card-icon icon-cyan">
                  <i class="ti ti-file-alert"></i>
                </div>
                <div class="stats-value" id="totalMinDivPending">0</div>
                <div class="stats-label">Min Div Pending</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="gov_docs_pending">
              <div class="card-body">
                <div class="stats-card-icon icon-pink">
                  <i class="ti ti-file-text"></i>
                </div>
                <div class="stats-value" id="totalGovDocsPending">0</div>
                <div class="stats-label">Gov Docs Pending</div>
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
                <div class="stats-card-icon icon-amber">
                  <i class="ti ti-calendar-event"></i>
                </div>
                <div class="stats-value" id="totalDGDAInPending">0</div>
                <div class="stats-label">DGDA In Pending</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card stats-card shadow-sm" data-filter="liquidation_pending">
              <div class="card-body">
                <div class="stats-card-icon icon-lime">
                  <i class="ti ti-receipt"></i>
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
                  <i class="ti ti-file-invoice"></i>
                </div>
                <div class="stats-value" id="totalQuittancePending">0</div>
                <div class="stats-label">Quittance Pending</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Export Form Card -->
        <div class="card shadow-sm mb-4">
          <div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
            <h4 class="header-title mb-0"><i class="ti ti-file-export me-2"></i> <span id="formTitle">Create New Exports</span></h4>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-sm btn-export" id="exportAllBtn">
                <i class="ti ti-file-spreadsheet me-1"></i> Export ALL to Excel
              </button>
              <button type="button" class="btn btn-sm btn-secondary" id="resetFormBtn" style="display:none;">
                <i class="ti ti-plus"></i> Add New
              </button>
            </div>
          </div>

          <div class="card-body">
            <form id="exportForm" method="post" novalidate data-csrf-token="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
              <input type="hidden" name="export_id" id="export_id" value="">
              <input type="hidden" name="action" id="formAction" value="insert">
              <input type="hidden" name="dgda_seal_ids" id="dgda_seal_ids" value="">

              <div class="accordion" id="exportAccordion">
                
                <div class="accordion-item mb-3">
                  <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#exportDetailsSection">
                      <i class="ti ti-file-export me-2"></i> Export Details
                    </button>
                  </h2>

                  <div id="exportDetailsSection" class="accordion-collapse collapse show" data-bs-parent="#exportAccordion">
                    <div class="accordion-body">

                      <!-- SECTION 1: DOCUMENTATION -->
                      <div class="section-header">
                        <i class="ti ti-file-text me-2"></i> Documentation
                      </div>

                      <!-- ROW 1 -->
                      <div class="row">
                        <div class="col-5-per-row mb-3">
                          <label class="form-label">Client <span class="text-danger">*</span></label>
                          <select name="subscriber_id" id="subscriber_id" class="form-select common-field" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($subscribers as $sub): ?>
                              <option value="<?= $sub['id'] ?>" data-liquidation="<?= $sub['liquidation_paid_by'] ?? '' ?>"><?= $sub['short_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label class="form-label">License Number <span class="text-danger">*</span></label>
                          <select name="license_id" id="license_id" class="form-select common-field" required>
                            <option value="">-- Select --</option>
                          </select>
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label class="form-label">Kind</label>
                          <input type="hidden" name="kind" id="kind_hidden" class="common-field">
                          <input type="text" id="kind_display" class="form-control readonly-field" readonly placeholder="From License">
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label class="form-label">Type of Goods</label>
                          <input type="hidden" name="type_of_goods" id="type_of_goods_hidden" class="common-field">
                          <input type="text" id="type_of_goods_display" class="form-control readonly-field" readonly placeholder="From License">
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label class="form-label">Transport Mode</label>
                          <input type="hidden" name="transport_mode" id="transport_mode_hidden" class="common-field">
                          <input type="text" id="transport_mode_display" class="form-control readonly-field" readonly placeholder="From License">
                        </div>
                      </div>

                      <!-- ROW 2 -->
                      <div class="row">
                        <div class="col-5-per-row mb-3">
                          <label class="form-label">MCA Ref <span class="text-danger">*</span></label>
                          <input type="text" name="mca_ref" id="mca_ref" class="form-control auto-generated-field common-field" required readonly placeholder="Auto-generated">
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label class="form-label">Currency</label>
                          <input type="hidden" name="currency" id="currency_hidden" class="common-field">
                          <input type="text" id="currency_display" class="form-control readonly-field" readonly placeholder="From License">
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label class="form-label">Supplier</label>
                          <input type="text" name="supplier" id="supplier" class="form-control readonly-field common-field" readonly placeholder="From License">
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label class="form-label">Regime <span class="text-danger">*</span></label>
                          <select name="regime" id="regime" class="form-select common-field" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($regimes as $regime): ?>
                              <option value="<?= $regime['id'] ?>"><?= $regime['regime_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label class="form-label">Types of Clearance <span class="text-danger">*</span></label>
                          <select name="types_of_clearance" id="types_of_clearance" class="form-select common-field" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($clearance_types as $type): ?>
                              <option value="<?= $type['id'] ?>" <?= ($type['id'] == 1) ? 'selected' : '' ?>><?= $type['clearance_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>

                      <!-- ROW 3 -->
                      <div class="row">
                        <div class="col-5-per-row mb-3">
                          <label class="form-label">Available Weight (MT)</label>
                          <input type="text" id="available_weight_display" class="form-control readonly-field" readonly placeholder="From License">
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label class="form-label">Available FOB</label>
                          <input type="text" id="available_fob_display" class="form-control readonly-field" readonly placeholder="From License">
                        </div>

                        <div class="col-5-per-row mb-3" id="numEntriesContainer">
                          <label class="form-label">Number of Entries <span class="text-danger">*</span></label>
                          <input type="number" id="numEntriesInput" class="form-control" min="1" max="100" value="1">
                        </div>
                        
                        <div class="col-5-per-row mb-3">
                          <label class="form-label">&nbsp;</label>
                          <button type="button" class="btn btn-primary w-100" id="proceedBulkBtn">
                            <i class="ti ti-arrow-right me-1"></i> Proceed to Create
                          </button>
                        </div>
                      </div>

                      <!-- EDIT MODE ONLY FIELDS -->
                      <div class="edit-only-section" id="documentationRestFields">
                        
                        <!-- ROW 4 -->
                        <div class="row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Invoice</label>
                            <input type="text" name="invoice" id="invoice" class="form-control" maxlength="255">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">PO Ref</label>
                            <input type="text" name="po_ref" id="po_ref" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Weight (MT)</label>
                            <input type="number" step="0.01" name="weight" id="weight" class="form-control" min="0">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">FOB</label>
                            <input type="number" step="0.01" name="fob" id="fob" class="form-control" min="0">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Transporter</label>
                            <input type="text" name="transporter" id="transporter" class="form-control" maxlength="255">
                          </div>
                        </div>

                        <!-- ROW 5 - TRANSPORT MODE: TRUCK FIELDS -->
                        <div class="row transport-conditional-field" id="truck_fields_row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Horse</label>
                            <input type="text" name="horse" id="horse" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Trailer 1</label>
                            <input type="text" name="trailer_1" id="trailer_1" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Trailer 2</label>
                            <input type="text" name="trailer_2" id="trailer_2" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Site of Loading</label>
                            <select name="site_of_loading_id" id="site_of_loading_id" class="form-select">
                              <option value="">-- Select --</option>
                              <?php foreach ($loading_sites as $site): ?>
                                <option value="<?= $site['id'] ?>"><?= $site['transit_point_name'] ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Destination</label>
                            <input type="text" name="destination" id="destination" class="form-control" maxlength="255">
                          </div>
                        </div>

                        <!-- ROW 5 - TRANSPORT MODE: SEA/RAIL FIELDS -->
                        <div class="row transport-conditional-field" id="sea_fields_row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Wagon Reference</label>
                            <input type="text" name="wagon_ref" id="wagon_ref" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Container</label>
                            <input type="text" name="container" id="container" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Feet Container</label>
                            <select name="feet_container" id="feet_container" class="form-select">
                              <option value="">-- Select --</option>
                              <?php foreach ($feet_containers as $fc): ?>
                                <option value="<?= htmlspecialchars($fc['feet_container_size'], ENT_QUOTES, 'UTF-8') ?>">
                                  <?= htmlspecialchars($fc['feet_container_size'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Site of Loading</label>
                            <select class="form-select site-of-loading-duplicate">
                              <option value="">-- Select --</option>
                              <?php foreach ($loading_sites as $site): ?>
                                <option value="<?= $site['id'] ?>"><?= $site['transit_point_name'] ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Destination</label>
                            <input type="text" class="form-control destination-duplicate" maxlength="255">
                          </div>
                        </div>

                        <!-- ROW 6 -->
                        <div class="row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Loading Date</label>
                            <input type="date" name="loading_date" id="loading_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">PV Date</label>
                            <input type="date" name="pv_date" id="pv_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">BP Date</label>
                            <input type="date" name="bp_date" id="bp_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Demande d'Attestation</label>
                            <input type="date" name="demande_attestation_date" id="demande_attestation_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Assay Date</label>
                            <input type="date" name="assay_date" id="assay_date" class="form-control">
                          </div>
                        </div>

                        <!-- ROW 7 -->
                        <div class="row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Lot Number</label>
                            <input type="text" name="lot_number" id="lot_number" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Seal DGDA</label>
                            <div class="seal-input-group">
                              <input type="text" name="dgda_seal_no" id="dgda_seal_no" class="form-control" readonly placeholder="No seals selected">
                              <button type="button" class="btn-select-seals" id="editModeSealBtn" title="Select Seals">+</button>
                            </div>
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">No. of Seals</label>
                            <input type="number" name="number_of_seals" id="number_of_seals" class="form-control readonly-field" readonly placeholder="0">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Number of Bags</label>
                            <input type="number" name="number_of_bags" id="number_of_bags" class="form-control" min="0">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Archive Reference</label>
                            <input type="text" name="archive_reference" id="archive_reference" class="form-control" maxlength="255">
                          </div>
                        </div>


                      </div>

                      <!-- SECTION 2: DECLARATION (EDIT MODE ONLY) -->
                      <div class="edit-only-section" id="declarationSection">
                        <div class="section-header">
                          <i class="ti ti-file-certificate me-2"></i> Declaration
                        </div>

                        <!-- ROW 1 -->
                        <div class="row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">CEEC In</label>
                            <input type="date" name="ceec_in_date" id="ceec_in_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">CEEC Out</label>
                            <input type="date" name="ceec_out_date" id="ceec_out_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Min Div In</label>
                            <input type="date" name="min_div_in_date" id="min_div_in_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Min Div Out</label>
                            <input type="date" name="min_div_out_date" id="min_div_out_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">CGEA Doc Ref</label>
                            <input type="text" name="cgea_doc_ref" id="cgea_doc_ref" class="form-control" maxlength="100">
                          </div>
                        </div>

                        <!-- ROW 2 -->
                        <div class="row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Segues RCV Ref</label>
                            <input type="text" name="segues_rcv_ref" id="segues_rcv_ref" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Segues Date of Payment</label>
                            <input type="date" name="segues_payment_date" id="segues_payment_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Document Status</label>
                            <select name="document_status" id="document_status" class="form-select">
                              <option value="">-- Select --</option>
                              <?php foreach ($document_statuses as $status): ?>
                                <option value="<?= $status['id'] ?>"><?= $status['document_status'] ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Customs Clearing Code</label>
                            <input type="text" name="customs_clearing_code" id="customs_clearing_code" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">DGDA In Date</label>
                            <input type="date" name="dgda_in_date" id="dgda_in_date" class="form-control">
                          </div>
                        </div>

                        <!-- ROW 3 -->
                        <div class="row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Declaration Reference</label>
                            <input type="text" name="declaration_reference" id="declaration_reference" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Liquidation Reference</label>
                            <input type="text" name="liquidation_reference" id="liquidation_reference" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Date Liquidation</label>
                            <input type="date" name="liquidation_date" id="liquidation_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Liquidation Paid By</label>
                            <input type="text" name="liquidation_paid_by" id="liquidation_paid_by" class="form-control readonly-field" readonly placeholder="From Client">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Liquidation Amount</label>
                            <input type="number" step="0.01" name="liquidation_amount" id="liquidation_amount" class="form-control" min="0">
                          </div>
                        </div>

                        <!-- ROW 4 -->
                        <div class="row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Quittance Reference</label>
                            <input type="text" name="quittance_reference" id="quittance_reference" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Date Quittance</label>
                            <input type="date" name="quittance_date" id="quittance_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">DGDA Out Date</label>
                            <input type="date" name="dgda_out_date" id="dgda_out_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Gov Docs In</label>
                            <input type="date" name="gov_docs_in_date" id="gov_docs_in_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Gov Docs Out</label>
                            <input type="date" name="gov_docs_out_date" id="gov_docs_out_date" class="form-control">
                          </div>
                        </div>

                        <!-- ROW 5 -->
                        <div class="row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Declaration Status</label>
                            <select name="clearing_status" id="clearing_status" class="form-select">
                              <option value="">-- Select --</option>
                              <?php foreach ($clearing_statuses as $status): ?>
                                <option value="<?= $status['id'] ?>"><?= $status['clearing_status'] ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>

                      </div>

                      <!-- SECTION 3: LOGISTICS (EDIT MODE ONLY) -->
                      <div class="edit-only-section" id="logisticsSection">
                        <div class="section-header">
                          <i class="ti ti-truck me-2"></i> Logistics
                        </div>

                        <!-- ROW 1 -->
                        <div class="row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Dispatch/Deliver Date</label>
                            <input type="date" name="dispatch_deliver_date" id="dispatch_deliver_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Kanyaka Arrival Date</label>
                            <input type="date" name="kanyaka_arrival_date" id="kanyaka_arrival_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Kanyaka Departure Date</label>
                            <input type="date" name="kanyaka_departure_date" id="kanyaka_departure_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Border Arrival</label>
                            <input type="date" name="border_arrival_date" id="border_arrival_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Exit DRC Date</label>
                            <input type="date" name="exit_drc_date" id="exit_drc_date" class="form-control">
                          </div>
                        </div>

                        <!-- ROW 2 -->
                        <div class="row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Exit Point</label>
                            <select name="exit_point_id" id="exit_point_id" class="form-select">
                              <option value="">-- Select --</option>
                              <?php foreach ($exit_points as $point): ?>
                                <option value="<?= $point['id'] ?>"><?= $point['transit_point_name'] ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">End of Formalities Date</label>
                            <input type="date" name="end_of_formalities_date" id="end_of_formalities_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Truck Status</label>
                            <select name="truck_status" id="truck_status" class="form-select">
                              <option value="">-- Select --</option>
                              <?php foreach ($truck_statuses as $status): ?>
                                <option value="<?= $status['id'] ?>"><?= $status['truck_status'] ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">LMC ID</label>
                            <input type="text" name="lmc_id" id="lmc_id" class="form-control" maxlength="100">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">OGEFREM Inv.Ref.</label>
                            <input type="text" name="ogefrem_inv_ref" id="ogefrem_inv_ref" class="form-control" maxlength="100">
                          </div>
                        </div>

                        <!-- ROW 3 -->
                        <div class="row">
                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Loading to Dispatch Date</label>
                            <input type="date" name="loading_to_dispatch_date" id="loading_to_dispatch_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Audited Date</label>
                            <input type="date" name="audited_date" id="audited_date" class="form-control">
                          </div>

                          <div class="col-5-per-row mb-3">
                            <label class="form-label">Archived Date</label>
                            <input type="date" name="archived_date" id="archived_date" class="form-control">
                          </div>
                        </div>

                      </div>

                    </div>
                  </div>
                </div>

              </div>

              <!-- Form Buttons - EDIT MODE ONLY -->
              <div class="row mt-4" id="singleFormButtons" style="display:none;">
                <div class="col-12 text-end">
                  <button type="button" class="btn btn-secondary" id="cancelBtn">
                    <i class="ti ti-x me-1"></i> Cancel
                  </button>
                  <button type="submit" class="btn btn-primary ms-2" id="submitBtn">
                    <i class="ti ti-check me-1"></i> <span id="submitBtnText">Update Export</span>
                  </button>
                </div>
              </div>

            </form>
          </div>
        </div>

        <!-- Exports DataTable -->
        <div class="card shadow-sm">
          <div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
            <h4 class="header-title mb-0"><i class="ti ti-list me-2"></i> Exports List</h4>
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
              <table id="exportsTable" class="table table-striped table-bordered dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>MCA Ref</th>
                    <th>Client</th>
                    <th>License</th>
                    <th>Invoice</th>
                    <th>Loading Date</th>
                    <th>Weight (MT)</th>
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

<!-- ✅ Seal Selection Modal -->
<div class="modal fade" id="sealSelectionModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti ti-shield-check me-2"></i> Select DGDA Seals
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <input type="text" class="form-control" id="sealSearchInput" placeholder="Search seals...">
        </div>
        <div id="sealCheckboxList"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Cancel
        </button>
        <button type="button" class="btn btn-primary" id="confirmSealSelection">
          <i class="ti ti-check me-1"></i> Confirm Selection
        </button>
      </div>
    </div>
  </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewExportModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti ti-eye me-2"></i> Export Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div id="modalDetailsContent"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti ti-edit me-2"></i> Bulk Update Exports
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="bulk-create-summary">
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

<!-- Bulk Insert Modal -->
<div class="modal fade" id="bulkInsertModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti ti-file-plus me-2"></i> New Exports - <span id="bulkEntriesCount">0</span> Entries
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="bulk-create-info">
          <p><strong>Available Weight:</strong> <span id="bulk_available_weight">0.00</span> MT | <strong>Available FOB:</strong> <span id="bulk_available_fob">0.00</span></p>
          <p><strong>Used Weight:</strong> <span id="bulk_used_weight">0.00</span> MT | <strong>Used FOB:</strong> <span id="bulk_used_fob">0.00</span></p>
          <p><strong>Remaining:</strong> <span id="bulk_remaining_weight" class="text-success">0.00</span> MT | <span id="bulk_remaining_fob" class="text-success">0.00</span> FOB</p>
          <p class="text-warning mb-0"><i class="ti ti-alert-triangle me-1"></i> At least one entry must have weight > 0 to create exports.</p>
        </div>

        <div class="bulk-insert-scrollable">
          <table class="bulk-insert-table" id="bulkInsertTable">
            <thead>
              <tr>
                <th style="min-width: 50px;">#</th>
                <th style="min-width: 180px;">MCA File Ref</th>
                <th style="min-width: 150px;">License Number</th>
                <th style="min-width: 160px;">Loading Date</th>
                <th style="min-width: 160px;">BP Receive Date</th>
                <th style="min-width: 160px;">Site of Loading</th>
                <th style="min-width: 140px;">Destination</th>
                <th style="min-width: 120px;" class="transport-modal-field truck-field">Horse</th>
                <th style="min-width: 120px;" class="transport-modal-field truck-field">Trailer 1</th>
                <th style="min-width: 120px;" class="transport-modal-field truck-field">Trailer 2</th>
                <th style="min-width: 130px;" class="transport-modal-field sea-field">Wagon Reference</th>
                <th style="min-width: 130px;" class="transport-modal-field sea-field">Container</th>
                <th style="min-width: 130px;" class="transport-modal-field sea-field">Feet Container</th>
                <th style="min-width: 150px;">Transporter</th>
                <th style="min-width: 150px;">Exit Point</th>
                <th style="min-width: 130px;">Weight (MT) *</th>
                <th style="min-width: 130px;">FOB *</th>
                <th style="min-width: 120px;">No. of Bags</th>
                <th style="min-width: 130px;">Lot Number</th>
                <th style="min-width: 200px;">Seal DGDA</th>
                <th style="min-width: 120px;">No. of Seals</th>
              </tr>
            </thead>
            <tbody id="bulkInsertTableBody">
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Cancel
        </button>
        <button type="button" class="btn btn-primary" id="saveBulkInsertBtn">
          <i class="ti ti-check me-1"></i> Create All Exports
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function () {

    const csrfToken = $('#exportForm').data('csrf-token');
    const transportModes = <?= json_encode($transport_modes) ?>;
    
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
    
    function sanitizeNumber(value) {
      const num = parseFloat(value);
      return isNaN(num) || num < 0 ? 0 : num;
    }

    let activeFilters = [];
    let bulkUpdateData = [];
    let isEditMode = false;
    let loadingSiteOptions = <?= json_encode($loading_sites) ?>;
    let exitPointOptions = <?= json_encode($exit_points) ?>;
    let feetContainerOptions = <?= json_encode($feet_containers) ?>;
    let availableSeals = [];
    let selectedSealIds = [];
    let currentModalContext = null;

    // ===== LOAD AVAILABLE SEALS =====
    function loadAvailableSeals() {
      $.ajax({
        url: '<?= APP_URL ?>/export/crudData/getAvailableSeals',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
          if (res.success) {
            availableSeals = res.data || [];
          }
        },
        error: function() {
          console.error('Failed to load available seals');
        }
      });
    }

    // ===== TRANSPORT MODE CONDITIONAL FIELDS =====
    function updateTransportConditionalFields() {
      const transportModeId = parseInt($('#transport_mode_hidden').val());
      
      // Hide all conditional rows first
      $('#truck_fields_row, #sea_fields_row').removeClass('show');
      
      // Clear all conditional field values
      $('#horse, #trailer_1, #trailer_2, #wagon_ref, #container, #feet_container').val('');
      
      if (transportModeId === 3) {
        // Sea/Rail transport - show sea fields
        $('#sea_fields_row').addClass('show');
        
        // Sync values from main inputs to duplicates
        const siteVal = $('#site_of_loading_id').val();
        const destVal = $('#destination').val();
        $('.site-of-loading-duplicate').val(siteVal);
        $('.destination-duplicate').val(destVal);
        
        $('.sea-field').show();
        $('.truck-field').hide();
      } else {
        // Truck transport - show truck fields  
        $('#truck_fields_row').addClass('show');
        
        // Sync values from duplicates back to main inputs (if they were filled)
        const siteVal = $('.site-of-loading-duplicate').val();
        const destVal = $('.destination-duplicate').val();
        if (siteVal) $('#site_of_loading_id').val(siteVal);
        if (destVal) $('#destination').val(destVal);
        
        $('.truck-field').show();
        $('.sea-field').hide();
      }
    }
    
    // Sync site of loading changes between truck and sea rows
    $(document).on('change', '#site_of_loading_id', function() {
      $('.site-of-loading-duplicate').val($(this).val());
    });
    
    $(document).on('change', '.site-of-loading-duplicate', function() {
      $('#site_of_loading_id').val($(this).val());
    });
    
    // Sync destination changes between truck and sea rows
    $(document).on('input', '#destination', function() {
      $('.destination-duplicate').val($(this).val());
    });
    
    $(document).on('input', '.destination-duplicate', function() {
      $('#destination').val($(this).val());
    });

    // ✅ DATE VALIDATION FUNCTION
    function validateDatePairs() {
      let isValid = true;
      let errorMsg = '';

      const ceecIn = $('#ceec_in_date').val();
      const ceecOut = $('#ceec_out_date').val();
      if (ceecIn && ceecOut && new Date(ceecOut) < new Date(ceecIn)) {
        $('#ceec_out_date').addClass('date-validation-error');
        errorMsg += 'CEEC Out date cannot be before CEEC In date.<br>';
        isValid = false;
      } else {
        $('#ceec_out_date').removeClass('date-validation-error');
      }

      const minDivIn = $('#min_div_in_date').val();
      const minDivOut = $('#min_div_out_date').val();
      if (minDivIn && minDivOut && new Date(minDivOut) < new Date(minDivIn)) {
        $('#min_div_out_date').addClass('date-validation-error');
        errorMsg += 'Min Div Out date cannot be before Min Div In date.<br>';
        isValid = false;
      } else {
        $('#min_div_out_date').removeClass('date-validation-error');
      }

      const govDocsIn = $('#gov_docs_in_date').val();
      const govDocsOut = $('#gov_docs_out_date').val();
      if (govDocsIn && govDocsOut && new Date(govDocsOut) < new Date(govDocsIn)) {
        $('#gov_docs_out_date').addClass('date-validation-error');
        errorMsg += 'Gov Docs Out date cannot be before Gov Docs In date.<br>';
        isValid = false;
      } else {
        $('#gov_docs_out_date').removeClass('date-validation-error');
      }

      if (!isValid) {
        Swal.fire({
          icon: 'error',
          title: 'Date Validation Error',
          html: errorMsg
        });
      }

      return isValid;
    }

    $('#ceec_out_date, #min_div_out_date, #gov_docs_out_date').on('change', function() {
      validateDatePairs();
    });

    // ===== EDIT MODE - SEAL SELECTION =====
    $('#editModeSealBtn').on('click', function() {
      currentModalContext = 'edit';
      const currentSealIds = $('#dgda_seal_ids').val().split(',').filter(id => id);
      
      renderSealSelection(currentSealIds);
      $('#sealSelectionModal').modal('show');
    });

    function renderSealSelection(currentSealIds) {
      const $list = $('#sealCheckboxList');
      $list.empty();

      if (availableSeals.length === 0) {
        $list.html('<p class="text-muted text-center">No seals available</p>');
        return;
      }

      availableSeals.forEach(seal => {
        const isChecked = currentSealIds.includes(seal.id.toString());
        const $item = $(`
          <div class="seal-checkbox-item">
            <input type="checkbox" id="seal_${seal.id}" value="${seal.id}" ${isChecked ? 'checked' : ''}>
            <label for="seal_${seal.id}">${escapeHtml(seal.seal_number)}</label>
          </div>
        `);
        $list.append($item);
      });
    }

    // ===== SEAL SEARCH =====
    $('#sealSearchInput').on('input', function() {
      const searchTerm = $(this).val().toLowerCase();
      
      if (currentModalContext === 'edit') {
        const currentSealIds = $('#dgda_seal_ids').val().split(',').filter(id => id);
        const filteredSeals = searchTerm 
          ? availableSeals.filter(seal => seal.seal_number.toLowerCase().includes(searchTerm))
          : availableSeals;

        const $list = $('#sealCheckboxList');
        $list.empty();

        if (filteredSeals.length === 0) {
          $list.html('<p class="text-muted text-center">No seals found</p>');
          return;
        }

        filteredSeals.forEach(seal => {
          const isChecked = currentSealIds.includes(seal.id.toString());
          const $item = $(`
            <div class="seal-checkbox-item">
              <input type="checkbox" id="seal_${seal.id}" value="${seal.id}" ${isChecked ? 'checked' : ''}>
              <label for="seal_${seal.id}">${escapeHtml(seal.seal_number)}</label>
            </div>
          `);
          $list.append($item);
        });
      } else if (currentModalContext === 'bulk') {
        const currentSealIds = $(`.seal-ids-hidden[data-row="${currentBulkRow}"]`).val().split(',').filter(id => id);
        const filteredSeals = searchTerm 
          ? availableSeals.filter(seal => seal.seal_number.toLowerCase().includes(searchTerm))
          : availableSeals;

        const $list = $('#sealCheckboxList');
        $list.empty();

        if (filteredSeals.length === 0) {
          $list.html('<p class="text-muted text-center">No seals found</p>');
          return;
        }

        filteredSeals.forEach(seal => {
          const isChecked = currentSealIds.includes(seal.id.toString());
          const $item = $(`
            <div class="seal-checkbox-item">
              <input type="checkbox" id="bulk_seal_${seal.id}" value="${seal.id}" ${isChecked ? 'checked' : ''}>
              <label for="bulk_seal_${seal.id}">${escapeHtml(seal.seal_number)}</label>
            </div>
          `);
          $list.append($item);
        });
      }
    });

    // ===== CONFIRM SEAL SELECTION =====
    $('#confirmSealSelection').on('click', function() {
      const selectedSeals = [];
      const selectedIds = [];

      $('#sealCheckboxList input[type="checkbox"]:checked').each(function() {
        const sealId = $(this).val();
        selectedIds.push(sealId);
        
        const seal = availableSeals.find(s => s.id.toString() === sealId);
        if (seal) {
          selectedSeals.push(seal);
        }
      });

      if (currentModalContext === 'edit') {
        updateEditModeSealDisplay(selectedSeals, selectedIds);
      } else if (currentModalContext === 'bulk') {
        updateBulkSealDisplay(currentBulkRow, selectedSeals, selectedIds);
      }

      $('#sealSelectionModal').modal('hide');
    });

    function updateEditModeSealDisplay(seals, sealIds) {
      if (seals.length === 0) {
        $('#dgda_seal_no').val('');
        $('#number_of_seals').val('');
        $('#dgda_seal_ids').val('');
      } else {
        const sealNumbers = seals.map(s => s.seal_number).join(', ');
        $('#dgda_seal_no').val(sealNumbers);
        $('#number_of_seals').val(seals.length);
        $('#dgda_seal_ids').val(sealIds.join(','));
      }
    }

    // ===== EXPORT ALL BUTTON =====
    $('#exportAllBtn').on('click', function() {
      window.location.href = '<?= APP_URL ?>/export/crudData/exportAll';
      
      Swal.fire({
        icon: 'success',
        title: 'Exporting ALL Exports...',
        text: 'Your export will download shortly',
        timer: 2000,
        showConfirmButton: false
      });
    });

    // ===== PROCEED TO BULK CREATE =====
    $('#proceedBulkBtn').on('click', function() {
      const numEntries = parseInt($('#numEntriesInput').val());
      
      if (numEntries < 1 || numEntries > 100) {
        Swal.fire('Error', 'Number of entries must be between 1 and 100', 'error');
        return;
      }

      const validation = validateCommonFields();
      if (!validation.success) {
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          html: '<ul style="text-align:left;"><li>' + validation.errors.map(err => escapeHtml(err)).join('</li><li>') + '</li></ul>'
        });
        return;
      }

      generateBulkInsertModal(numEntries);
    });

    function validateCommonFields() {
      let errors = [];
      
      const requiredFields = [
        { id: 'subscriber_id', label: 'Client' },
        { id: 'license_id', label: 'License Number' },
        { id: 'regime', label: 'Regime' },
        { id: 'types_of_clearance', label: 'Types of Clearance' }
      ];

      requiredFields.forEach(field => {
        const value = $(`#${field.id}`).val();
        if (!value || value === '') {
          errors.push(`${field.label} is required`);
        }
      });

      const mcaRef = $('#mca_ref').val();
      if (!mcaRef) {
        errors.push('MCA Reference is required');
      }

      return { success: errors.length === 0, errors };
    }

    function generateBulkInsertModal(numEntries) {
      const baseMCARef = $('#mca_ref').val();
      const licenseNumber = $('#license_id option:selected').text();
      const availableWeight = parseFloat($('#available_weight_display').val()) || 0;
      const availableFOB = parseFloat($('#available_fob_display').val()) || 0;
      const transportModeId = parseInt($('#transport_mode_hidden').val());

      const mcaParts = baseMCARef.match(/^(.+-)(\d{4})$/);
      if (!mcaParts) {
        Swal.fire('Error', 'Invalid MCA Reference format. Expected format: XXX-XXXXX-0001', 'error');
        return;
      }

      const mcaPrefix = mcaParts[1];
      const startSequence = parseInt(mcaParts[2]);

      $('#bulkEntriesCount').text(numEntries);
      $('#bulk_available_weight').text(availableWeight.toFixed(2));
      $('#bulk_available_fob').text(availableFOB.toFixed(2));
      $('#bulk_used_weight').text('0.00');
      $('#bulk_used_fob').text('0.00');
      $('#bulk_remaining_weight').text(availableWeight.toFixed(2));
      $('#bulk_remaining_fob').text(availableFOB.toFixed(2));

      if (transportModeId === 3) {
        $('.sea-field').show();
        $('.truck-field').hide();
      } else {
        $('.truck-field').show();
        $('.sea-field').hide();
      }

      let tableHTML = '';
      for (let i = 0; i < numEntries; i++) {
        const rowNum = i + 1;
        const sequence = startSequence + i;
        const mcaRef = mcaPrefix + String(sequence).padStart(4, '0');

        let horseColumn = '', trailer1Column = '', trailer2Column = '', wagonRefColumn = '', containerColumn = '', feetContainerColumn = '';
        
        if (transportModeId === 3) {
          wagonRefColumn = `<td class="transport-modal-field sea-field"><input type="text" class="form-control wagon-ref-input" data-row="${rowNum}" maxlength="100"></td>`;
          containerColumn = `<td class="transport-modal-field sea-field"><input type="text" class="form-control container-input" data-row="${rowNum}" maxlength="100"></td>`;
          feetContainerColumn = `<td class="transport-modal-field sea-field">
            <select class="form-select feet-container-input" data-row="${rowNum}">
              <option value="">-- Select --</option>
              ${feetContainerOptions.map(fc => `<option value="${escapeHtml(fc.feet_container_size)}">${escapeHtml(fc.feet_container_size)}</option>`).join('')}
            </select>
          </td>`;
        } else {
          horseColumn = `<td class="transport-modal-field truck-field"><input type="text" class="form-control horse-input" data-row="${rowNum}" maxlength="100"></td>`;
          trailer1Column = `<td class="transport-modal-field truck-field"><input type="text" class="form-control trailer1-input" data-row="${rowNum}" maxlength="100"></td>`;
          trailer2Column = `<td class="transport-modal-field truck-field"><input type="text" class="form-control trailer2-input" data-row="${rowNum}" maxlength="100"></td>`;
        }

        tableHTML += `
          <tr data-row="${rowNum}">
            <td class="row-number">${rowNum}</td>
            <td class="mca-ref-cell"><input type="text" class="form-control mca-ref-input" readonly value="${escapeHtml(mcaRef)}"></td>
            <td><input type="text" class="form-control" readonly value="${escapeHtml(licenseNumber)}"></td>
            <td><input type="date" class="form-control loading-date-input" data-row="${rowNum}"></td>
            <td><input type="date" class="form-control bp-date-input" data-row="${rowNum}"></td>
            <td>
              <select class="form-select site-loading-input" data-row="${rowNum}">
                <option value="">-- Select --</option>
                ${loadingSiteOptions.map(site => `<option value="${site.id}">${escapeHtml(site.transit_point_name)}</option>`).join('')}
              </select>
            </td>
            <td><input type="text" class="form-control destination-input" data-row="${rowNum}" maxlength="255"></td>
            ${horseColumn}
            ${trailer1Column}
            ${trailer2Column}
            ${wagonRefColumn}
            ${containerColumn}
            ${feetContainerColumn}
            <td><input type="text" class="form-control transporter-input" data-row="${rowNum}" maxlength="255"></td>
            <td>
              <select class="form-select exit-point-input" data-row="${rowNum}">
                <option value="">-- Select --</option>
                ${exitPointOptions.map(point => `<option value="${point.id}">${escapeHtml(point.transit_point_name)}</option>`).join('')}
              </select>
            </td>
            <td><input type="number" step="0.01" class="form-control weight-input" data-row="${rowNum}" min="0" placeholder="0.00"></td>
            <td><input type="number" step="0.01" class="form-control fob-input" data-row="${rowNum}" min="0" placeholder="0.00"></td>
            <td><input type="number" class="form-control bags-input" data-row="${rowNum}" min="0"></td>
            <td><input type="text" class="form-control lot-input" data-row="${rowNum}" maxlength="100"></td>
            <td>
              <input type="hidden" class="seal-ids-hidden" data-row="${rowNum}">
              <div class="seal-input-group">
                <input type="text" class="form-control seal-display-input" data-row="${rowNum}" readonly placeholder="No seals">
                <button type="button" class="btn-select-seals bulk-seal-btn" data-row="${rowNum}" title="Select Seals">+</button>
              </div>
            </td>
            <td><input type="number" class="form-control seals-count-input" data-row="${rowNum}" readonly></td>
          </tr>
        `;
      }

      $('#bulkInsertTableBody').html(tableHTML);

      $(document).off('click', '.bulk-seal-btn');
      $(document).on('click', '.bulk-seal-btn', function() {
        currentModalContext = 'bulk';
        const rowNum = $(this).data('row');
        currentBulkRow = rowNum;
        const currentSealIds = $(`.seal-ids-hidden[data-row="${rowNum}"]`).val().split(',').filter(id => id);
        
        renderSealSelection(currentSealIds);
        $('#sealSelectionModal').modal('show');
      });

      $(document).off('change', '.loading-date-input');
      $(document).on('change', '.loading-date-input', function() {
        const selectedValue = $(this).val();
        if (selectedValue) {
          $('.loading-date-input').not(this).val(selectedValue).addClass('auto-propagate-field');
          setTimeout(() => {
            $('.loading-date-input').removeClass('auto-propagate-field');
          }, 1000);
        }
      });

      $(document).off('change', '.bp-date-input');
      $(document).on('change', '.bp-date-input', function() {
        const selectedValue = $(this).val();
        if (selectedValue) {
          $('.bp-date-input').not(this).val(selectedValue).addClass('auto-propagate-field');
          setTimeout(() => {
            $('.bp-date-input').removeClass('auto-propagate-field');
          }, 1000);
        }
      });

      $(document).off('change', '.site-loading-input');
      $(document).on('change', '.site-loading-input', function() {
        const selectedValue = $(this).val();
        if (selectedValue) {
          $('.site-loading-input').not(this).val(selectedValue).addClass('auto-propagate-field');
          setTimeout(() => {
            $('.site-loading-input').removeClass('auto-propagate-field');
          }, 1000);
        }
      });

      $(document).off('input', '.weight-input, .fob-input');
      $(document).on('input', '.weight-input, .fob-input', function() {
        let totalWeight = 0;
        let totalFOB = 0;

        $('.weight-input').each(function() {
          totalWeight += parseFloat($(this).val()) || 0;
        });

        $('.fob-input').each(function() {
          totalFOB += parseFloat($(this).val()) || 0;
        });

        const remainingWeight = availableWeight - totalWeight;
        const remainingFOB = availableFOB - totalFOB;

        $('#bulk_used_weight').text(totalWeight.toFixed(2));
        $('#bulk_used_fob').text(totalFOB.toFixed(2));
        $('#bulk_remaining_weight').text(remainingWeight.toFixed(2));
        $('#bulk_remaining_fob').text(remainingFOB.toFixed(2));

        if (remainingWeight < 0) {
          $('#bulk_remaining_weight').removeClass('text-success').addClass('text-danger');
        } else {
          $('#bulk_remaining_weight').removeClass('text-danger').addClass('text-success');
        }

        if (remainingFOB < 0) {
          $('#bulk_remaining_fob').removeClass('text-success').addClass('text-danger');
        } else {
          $('#bulk_remaining_fob').removeClass('text-danger').addClass('text-success');
        }
      });

      $('#bulkInsertModal').modal('show');
    }

    let currentBulkRow = null;

    function updateBulkSealDisplay(rowNum, seals, sealIds) {
      if (seals.length === 0) {
        $(`.seal-display-input[data-row="${rowNum}"]`).val('');
        $(`.seals-count-input[data-row="${rowNum}"]`).val('');
        $(`.seal-ids-hidden[data-row="${rowNum}"]`).val('');
      } else {
        const sealNumbers = seals.map(s => s.seal_number).join(', ');
        $(`.seal-display-input[data-row="${rowNum}"]`).val(sealNumbers);
        $(`.seals-count-input[data-row="${rowNum}"]`).val(seals.length);
        $(`.seal-ids-hidden[data-row="${rowNum}"]`).val(sealIds.join(','));
      }
    }

    // ===== SAVE BULK INSERT =====
    $('#saveBulkInsertBtn').on('click', function() {
      const rows = [];
      let hasWeight = false;

      $('#bulkInsertTableBody tr').each(function() {
        const weight = parseFloat($(this).find('.weight-input').val()) || 0;
        const fob = parseFloat($(this).find('.fob-input').val()) || 0;
        
        if (weight > 0) hasWeight = true;

        const rowNum = $(this).data('row');
        const sealIds = ($(`.seal-ids-hidden[data-row="${rowNum}"]`).val() || '').split(',').filter(id => id);
        const sealNumbers = $(`.seal-display-input[data-row="${rowNum}"]`).val();

        const rowData = {
          mca_ref: $(this).find('.mca-ref-input').val(),
          loading_date: $(this).find('.loading-date-input').val() || null,
          bp_date: $(this).find('.bp-date-input').val() || null,
          site_of_loading_id: $(this).find('.site-loading-input').val() || null,
          destination: $(this).find('.destination-input').val() || null,
          horse: $(this).find('.horse-input').val() || null,
          trailer_1: $(this).find('.trailer1-input').val() || null,
          trailer_2: $(this).find('.trailer2-input').val() || null,
          wagon_ref: $(this).find('.wagon-ref-input').val() || null,
          container: $(this).find('.container-input').val() || null,
          feet_container: $(this).find('.feet-container-input').val() || null,
          transporter: $(this).find('.transporter-input').val() || null,
          exit_point_id: $(this).find('.exit-point-input').val() || null,
          weight: weight,
          fob: fob,
          number_of_bags: $(this).find('.bags-input').val() || null,
          lot_number: $(this).find('.lot-input').val() || null,
          dgda_seal_no: sealNumbers || null,
          number_of_seals: sealIds.length || null,
          seal_ids: sealIds
        };

        rows.push(rowData);
      });

      if (!hasWeight) {
        Swal.fire({
          icon: 'warning',
          title: 'Invalid Data',
          text: 'At least one entry must have weight > 0',
        });
        return;
      }

      const remainingWeight = parseFloat($('#bulk_remaining_weight').text());
      const remainingFOB = parseFloat($('#bulk_remaining_fob').text());

      if (remainingWeight < 0) {
        Swal.fire({
          icon: 'error',
          title: 'Exceeded Available Weight',
          text: 'Total weight exceeds available license weight',
        });
        return;
      }

      if (remainingFOB < 0) {
        Swal.fire({
          icon: 'error',
          title: 'Exceeded Available FOB',
          text: 'Total FOB exceeds available license FOB',
        });
        return;
      }

      const commonData = {
        subscriber_id: $('#subscriber_id').val(),
        license_id: $('#license_id').val(),
        kind: $('#kind_hidden').val() || null,
        type_of_goods: $('#type_of_goods_hidden').val() || null,
        transport_mode: $('#transport_mode_hidden').val() || null,
        currency: $('#currency_hidden').val() || null,
        supplier: $('#supplier').val() || null,
        regime: $('#regime').val(),
        types_of_clearance: $('#types_of_clearance').val(),
        liquidation_paid_by: $('#liquidation_paid_by').val() || null,
        available_weight: $('#available_weight_display').val() || 0,
        available_fob: $('#available_fob_display').val() || 0
      };

      const saveBtn = $('#saveBulkInsertBtn');
      const originalText = saveBtn.html();
      saveBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Creating...');

      $.ajax({
        url: '<?= APP_URL ?>/export/crudData/bulkInsertFromModal',
        method: 'POST',
        data: {
          csrf_token: csrfToken,
          common_data: JSON.stringify(commonData),
          rows_data: JSON.stringify(rows)
        },
        dataType: 'json',
        success: function(res) {
          saveBtn.prop('disabled', false).html(originalText);
          
          if (res.success) {
            let messageHTML = '<p>' + escapeHtml(res.message) + '</p>';
            
            if (res.errors && res.errors.length > 0) {
              messageHTML += '<hr><p style="text-align:left;"><strong>Error Details:</strong></p>';
              messageHTML += '<ul style="text-align:left; color:#dc3545;">';
              res.errors.forEach(function(error) {
                messageHTML += '<li>' + escapeHtml(error) + '</li>';
              });
              messageHTML += '</ul>';
            }
            
            const icon = (res.error_count > 0) ? 'warning' : 'success';
            
            Swal.fire({
              icon: icon,
              title: (res.error_count > 0) ? 'Partial Success' : 'Success!',
              html: messageHTML,
              confirmButtonText: 'OK',
              width: '600px'
            }).then(() => {
              if (res.success_count > 0) {
                $('#bulkInsertModal').modal('hide');
                resetForm();
                if (typeof exportsTable !== 'undefined') {
                  exportsTable.ajax.reload(null, false);
                }
                updateStatistics();
                loadAvailableSeals();
              }
            });
          } else {
            Swal.fire('Error', res.message || 'Bulk insert failed', 'error');
          }
        },
        error: function(xhr, status, error) {
          saveBtn.prop('disabled', false).html(originalText);
          
          let errorMsg = 'An error occurred during bulk insert';
          
          if (xhr.status === 403) {
            errorMsg = 'Security token expired. Please refresh the page and try again.';
          } else if (xhr.responseText) {
            try {
              const errorResponse = JSON.parse(xhr.responseText);
              errorMsg = errorResponse.message || errorMsg;
            } catch (e) {
              errorMsg = 'Server error: ' + xhr.status;
            }
          }
          
          Swal.fire('Error', errorMsg, 'error');
        }
      });
    });

    // ===== STATS CARD FILTERS =====
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
      if (typeof exportsTable !== 'undefined') {
        exportsTable.ajax.reload();
      }
    }

    function updateBulkUpdateButton() {
      if (activeFilters.length > 0) {
        $('#bulkUpdateBtn').prop('disabled', false);
      } else {
        $('#bulkUpdateBtn').prop('disabled', true);
      }
    }

    // ===== BULK UPDATE BUTTON =====
    $('#bulkUpdateBtn').on('click', function() {
      if (activeFilters.length === 0) {
        Swal.fire('Error', 'Please select a filter first', 'error');
        return;
      }

      $.ajax({
        url: '<?= APP_URL ?>/export/crudData/getBulkUpdateData',
        method: 'GET',
        data: { filters: activeFilters },
        dataType: 'json',
        success: function(res) {
          if (res.success) {
            bulkUpdateData = res.data || [];
            const relevantFields = res.relevant_fields || [];
            
            if (bulkUpdateData.length === 0) {
              Swal.fire('Info', 'No exports match the selected filters', 'info');
              return;
            }

            const filterNames = {
              'ceec_pending': 'CEEC Pending',
              'min_div_pending': 'Min Div Pending',
              'gov_docs_pending': 'Gov Docs Pending',
              'dgda_in_pending': 'DGDA In Pending',
              'liquidation_pending': 'Liquidation Pending',
              'quittance_pending': 'Quittance Pending',
              'audited_pending': 'Audited Pending',
              'archived_pending': 'Archived Pending'
            };

            let filterSummary = activeFilters.map(f => filterNames[f] || f).join(', ');
            $('#bulkFilterSummary').text(`Active Filters: ${filterSummary} (${bulkUpdateData.length} exports)`);

            let tableHTML = '<div class="bulk-table-container"><table class="bulk-update-table"><thead><tr>';
            tableHTML += '<th style="width: 50px;">MCA Ref</th>';
            tableHTML += '<th style="width: 100px;">Client</th>';
            tableHTML += '<th style="width: 100px;">Loading Date</th>';

            const fieldLabels = {
              'ceec_in_date': 'CEEC In',
              'ceec_out_date': 'CEEC Out',
              'min_div_in_date': 'Min Div In',
              'min_div_out_date': 'Min Div Out',
              'gov_docs_in_date': 'Gov Docs In',
              'gov_docs_out_date': 'Gov Docs Out',
              'dgda_in_date': 'DGDA In',
              'liquidation_date': 'Liquidation',
              'quittance_date': 'Quittance',
              'audited_date': 'Audited',
              'archived_date': 'Archived'
            };

            relevantFields.forEach(field => {
              tableHTML += `<th>${fieldLabels[field] || field}</th>`;
            });

            tableHTML += '</tr></thead><tbody>';

            bulkUpdateData.forEach(exp => {
              tableHTML += '<tr>';
              tableHTML += `<td><span class="mca-ref-badge">${escapeHtml(exp.mca_ref)}</span></td>`;
              tableHTML += `<td>${escapeHtml(exp.subscriber_name || '')}</td>`;
              tableHTML += `<td><span class="loading-date-text">${exp.loading_date ? formatDate(exp.loading_date) : 'N/A'}</span></td>`;

              relevantFields.forEach(field => {
                const value = exp[field] || '';
                tableHTML += `<td><input type="date" class="form-control bulk-field" data-export-id="${exp.id}" data-field="${field}" value="${value}"></td>`;
              });

              tableHTML += '</tr>';
            });

            tableHTML += '</tbody></table></div>';

            $('#bulkUpdateContent').html(tableHTML);
            $('#bulkUpdateModal').modal('show');
          } else {
            Swal.fire('Error', res.message || 'Failed to load bulk update data', 'error');
          }
        },
        error: function() {
          Swal.fire('Error', 'Failed to load bulk update data', 'error');
        }
      });
    });

    // ===== SAVE BULK UPDATE =====
    $('#saveBulkUpdateBtn').on('click', function() {
      const updateData = [];

      $('.bulk-field').each(function() {
        const exportId = $(this).data('export-id');
        const field = $(this).data('field');
        const value = $(this).val();

        let existingUpdate = updateData.find(u => u.export_id === exportId);
        if (!existingUpdate) {
          existingUpdate = { export_id: exportId };
          updateData.push(existingUpdate);
        }

        existingUpdate[field] = value;
      });

      if (updateData.length === 0) {
        Swal.fire('Error', 'No data to update', 'error');
        return;
      }

      const saveBtn = $('#saveBulkUpdateBtn');
      const originalText = saveBtn.html();
      saveBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Updating...');

      $.ajax({
        url: '<?= APP_URL ?>/export/crudData/bulkUpdate',
        method: 'POST',
        data: {
          csrf_token: csrfToken,
          update_data: JSON.stringify(updateData)
        },
        dataType: 'json',
        success: function(res) {
          saveBtn.prop('disabled', false).html(originalText);

          if (res.success) {
            let messageHTML = '<p>' + escapeHtml(res.message) + '</p>';

            if (res.errors && res.errors.length > 0) {
              messageHTML += '<hr><p style="text-align:left;"><strong>Error Details:</strong></p>';
              messageHTML += '<ul style="text-align:left; color:#dc3545;">';
              res.errors.forEach(function(error) {
                messageHTML += '<li>' + escapeHtml(error) + '</li>';
              });
              messageHTML += '</ul>';
            }

            const icon = (res.error_count > 0) ? 'warning' : 'success';

            Swal.fire({
              icon: icon,
              title: (res.error_count > 0) ? 'Partial Success' : 'Success!',
              html: messageHTML,
              confirmButtonText: 'OK',
              width: '600px'
            }).then(() => {
              if (res.success_count > 0) {
                $('#bulkUpdateModal').modal('hide');
                if (typeof exportsTable !== 'undefined') {
                  exportsTable.ajax.reload(null, false);
                }
                updateStatistics();
              }
            });
          } else {
            Swal.fire('Error', res.message || 'Bulk update failed', 'error');
          }
        },
        error: function(xhr) {
          saveBtn.prop('disabled', false).html(originalText);

          let errorMsg = 'An error occurred during bulk update';

          if (xhr.status === 403) {
            errorMsg = 'Security token expired. Please refresh the page and try again.';
          }

          Swal.fire('Error', errorMsg, 'error');
        }
      });
    });

    // ===== CLIENT & LICENSE AUTO-POPULATION =====
    $('#subscriber_id').on('change', function() {
      const subscriberId = $(this).val();
      const selectedOption = $(this).find('option:selected');
      const liquidationPaidBy = selectedOption.data('liquidation');

      setLiquidationPaidBy(liquidationPaidBy);

      $('#license_id').html('<option value="">-- Select --</option>');
      
      if (!subscriberId) {
        clearLicenseFields();
        return;
      }

      $.ajax({
        url: '<?= APP_URL ?>/export/crudData/getLicenses',
        method: 'GET',
        data: { subscriber_id: subscriberId },
        dataType: 'json',
        success: function(res) {
          if (res.success && res.data.length > 0) {
            res.data.forEach(function(license) {
              $('#license_id').append(`<option value="${license.id}" data-available-weight="${license.available_weight || 0}" data-available-fob="${license.available_fob || 0}">${escapeHtml(license.license_number)}</option>`);
            });
          } else {
            Swal.fire({
              icon: 'info',
              title: 'No Export Licenses',
              text: 'No active export licenses found for this client.',
              timer: 3000
            });
          }
        },
        error: function() {
          Swal.fire('Error', 'Failed to load licenses', 'error');
        }
      });
    });

    function setLiquidationPaidBy(value) {
      if (value == 1) {
        $('#liquidation_paid_by').val('Client');
      } else if (value == 2) {
        $('#liquidation_paid_by').val('Malabar');
      } else {
        $('#liquidation_paid_by').val('');
      }
    }

    $('#license_id').on('change', function() {
      const licenseId = $(this).val();
      
      if (!licenseId) {
        clearLicenseFields();
        return;
      }

      $('#kind_display, #type_of_goods_display, #transport_mode_display, #currency_display, #supplier').val('Loading...');

      $.ajax({
        url: '<?= APP_URL ?>/export/crudData/getLicenseDetails',
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
            $('#available_weight_display').val(license.available_weight ? parseFloat(license.available_weight).toFixed(2) : '0.00');
            $('#available_fob_display').val(license.available_fob ? parseFloat(license.available_fob).toFixed(2) : '0.00');
            
            updateTransportConditionalFields();
            
            if (!isEditMode) {
              generateMCAReference();
            }
          } else {
            clearLicenseFields();
            Swal.fire('Error', res.message || 'Failed to load license details', 'error');
          }
        },
        error: function() {
          clearLicenseFields();
          Swal.fire('Error', 'Failed to load license details', 'error');
        }
      });
    });

    function clearLicenseFields() {
      $('#kind_hidden, #type_of_goods_hidden, #transport_mode_hidden, #currency_hidden').val('');
      $('#kind_display, #type_of_goods_display, #transport_mode_display, #currency_display').val('');
      $('#supplier, #available_weight_display, #available_fob_display, #liquidation_paid_by').val('');
      $('#mca_ref').val('');
      $('.transport-conditional-field').removeClass('show');
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
        url: '<?= APP_URL ?>/export/crudData/getNextMCASequence',
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

    function resetForm() {
      $('#exportForm')[0].reset();
      $('#export_id, #mca_ref').val('');
      $('#formAction').val('insert');
      $('#formTitle').text('Create New Exports');
      $('#submitBtnText').text('Update Export');
      $('#resetFormBtn').hide();
      $('#numEntriesInput').val(1);
      $('#numEntriesContainer').show();
      $('#proceedBulkBtn').show();
      $('#singleFormButtons').hide();
      clearLicenseFields();
      
      $('.edit-only-section').removeClass('show');
      
      $('#types_of_clearance').val('1');
      
      $('#exportDetailsSection').collapse('show');
      isEditMode = false;
      
      $('#subscriber_id, #license_id').prop('disabled', false);
      
      selectedSealIds = [];
      $('#dgda_seal_no').val('');
      $('#number_of_seals').val('');
      $('#dgda_seal_ids').val('');
      
      $('.date-validation-error').removeClass('date-validation-error');
    }

    $('#cancelBtn, #resetFormBtn').on('click', (e) => { 
      e.preventDefault(); 
      resetForm(); 
    });

    // ===== EDIT FUNCTIONALITY =====
    $(document).on('click', '.editBtn', function () {
      const id = parseInt($(this).data('id'));
      loadExportForEdit(id);
    });

    function loadExportForEdit(exportId) {
      $.ajax({
        url: '<?= APP_URL ?>/export/crudData/getExport',
        method: 'GET',
        data: { id: exportId },
        dataType: 'json',
        success: function(res) {
          if (res.success && res.data) {
            const exp = res.data;
            
            isEditMode = true;
            $('#formAction').val('update');
            $('#export_id').val(exp.id);
            $('#formTitle').text('Edit Export');
            $('#submitBtnText').text('Update Export');
            $('#resetFormBtn').show();
            
            $('#numEntriesContainer').hide();
            $('#proceedBulkBtn').hide();
            $('#singleFormButtons').show();
            
            $('.edit-only-section').addClass('show');
            
            $('#subscriber_id').val(exp.subscriber_id).prop('disabled', true);
            
            const selectedSubscriber = $('#subscriber_id option:selected');
            const liquidationValue = selectedSubscriber.data('liquidation');
            setLiquidationPaidBy(liquidationValue);
            
            $.ajax({
              url: '<?= APP_URL ?>/export/crudData/getLicenses',
              method: 'GET',
              data: { subscriber_id: exp.subscriber_id },
              dataType: 'json',
              success: function(licRes) {
                if (licRes.success && licRes.data.length > 0) {
                  $('#license_id').html('<option value="">-- Select License --</option>');
                  licRes.data.forEach(function(license) {
                    $('#license_id').append(`<option value="${license.id}">${escapeHtml(license.license_number)}</option>`);
                  });
                  $('#license_id').val(exp.license_id).prop('disabled', true);
                }
              }
            });
            
            $('#mca_ref').val(exp.mca_ref);
            $('#kind_hidden').val(exp.kind);
            $('#type_of_goods_hidden').val(exp.type_of_goods);
            $('#transport_mode_hidden').val(exp.transport_mode);
            $('#currency_hidden').val(exp.currency);
            
            $('#kind_display').val(exp.kind_name || '');
            $('#type_of_goods_display').val(exp.type_of_goods_name || '');
            $('#transport_mode_display').val(exp.transport_mode_name || '');
            $('#currency_display').val(exp.currency_name || '');
            
            $('#regime').val(exp.regime);
            $('#types_of_clearance').val(exp.types_of_clearance);
            $('#supplier').val(exp.supplier);
            
            $('#available_weight_display').val(exp.license_available_weight ? parseFloat(exp.license_available_weight).toFixed(2) : '0.00');
            $('#available_fob_display').val(exp.license_available_fob ? parseFloat(exp.license_available_fob).toFixed(2) : '0.00');
            
            updateTransportConditionalFields();
            
            $('#invoice').val(exp.invoice);
            $('#po_ref').val(exp.po_ref);
            $('#weight').val(exp.weight);
            $('#fob').val(exp.fob);
            $('#horse').val(exp.horse);
            $('#trailer_1').val(exp.trailer_1);
            $('#trailer_2').val(exp.trailer_2);
            $('#wagon_ref').val(exp.wagon_ref);
            $('#container').val(exp.container);
            $('#feet_container').val(exp.feet_container);
            $('#transporter').val(exp.transporter);
            $('#site_of_loading_id').val(exp.site_of_loading_id);
            $('.site-of-loading-duplicate').val(exp.site_of_loading_id);
            $('#destination').val(exp.destination);
            $('.destination-duplicate').val(exp.destination);
            $('#loading_date').val(exp.loading_date);
            $('#pv_date').val(exp.pv_date);
            $('#bp_date').val(exp.bp_date);
            $('#demande_attestation_date').val(exp.demande_attestation_date);
            $('#assay_date').val(exp.assay_date);
            $('#lot_number').val(exp.lot_number);
            $('#number_of_bags').val(exp.number_of_bags);
            $('#archive_reference').val(exp.archive_reference);
            
            $('#dgda_seal_no').val(exp.dgda_seal_no || '');
            $('#number_of_seals').val(exp.number_of_seals || '');
            
            if (exp.dgda_seal_no) {
              const sealNumbers = exp.dgda_seal_no.split(',').map(s => s.trim());
              selectedSealIds = [];
              sealNumbers.forEach(sealNum => {
                const seal = availableSeals.find(s => s.seal_number === sealNum);
                if (seal) {
                  selectedSealIds.push(seal.id.toString());
                }
              });
              $('#dgda_seal_ids').val(selectedSealIds.join(','));
            }
            
            $('#ceec_in_date').val(exp.ceec_in_date);
            $('#ceec_out_date').val(exp.ceec_out_date);
            $('#min_div_in_date').val(exp.min_div_in_date);
            $('#min_div_out_date').val(exp.min_div_out_date);
            $('#cgea_doc_ref').val(exp.cgea_doc_ref);
            $('#segues_rcv_ref').val(exp.segues_rcv_ref);
            $('#segues_payment_date').val(exp.segues_payment_date);
            $('#document_status').val(exp.document_status);
            $('#customs_clearing_code').val(exp.customs_clearing_code);
            $('#dgda_in_date').val(exp.dgda_in_date);
            $('#declaration_reference').val(exp.declaration_reference);
            $('#liquidation_reference').val(exp.liquidation_reference);
            $('#liquidation_date').val(exp.liquidation_date);
            
            if (exp.liquidation_paid_by) {
              $('#liquidation_paid_by').val(exp.liquidation_paid_by);
            }
            
            $('#liquidation_amount').val(exp.liquidation_amount);
            $('#quittance_reference').val(exp.quittance_reference);
            $('#quittance_date').val(exp.quittance_date);
            $('#dgda_out_date').val(exp.dgda_out_date);
            $('#gov_docs_in_date').val(exp.gov_docs_in_date);
            $('#gov_docs_out_date').val(exp.gov_docs_out_date);
            $('#clearing_status').val(exp.clearing_status);
            
            $('#dispatch_deliver_date').val(exp.dispatch_deliver_date);
            $('#kanyaka_arrival_date').val(exp.kanyaka_arrival_date);
            $('#kanyaka_departure_date').val(exp.kanyaka_departure_date);
            $('#border_arrival_date').val(exp.border_arrival_date);
            $('#exit_drc_date').val(exp.exit_drc_date);
            $('#exit_point_id').val(exp.exit_point_id);
            $('#end_of_formalities_date').val(exp.end_of_formalities_date);
            $('#truck_status').val(exp.truck_status);
            $('#lmc_id').val(exp.lmc_id);
            $('#ogefrem_inv_ref').val(exp.ogefrem_inv_ref);
            $('#loading_to_dispatch_date').val(exp.loading_to_dispatch_date);
            $('#audited_date').val(exp.audited_date);
            $('#archived_date').val(exp.archived_date);
            
            $('#exportDetailsSection').collapse('show');
            
            $('html, body').animate({
              scrollTop: $('#exportForm').offset().top - 100
            }, 500);
            
          } else {
            Swal.fire('Error', res.message || 'Failed to load export data', 'error');
          }
        },
        error: function() {
          Swal.fire('Error', 'Failed to load export data', 'error');
        }
      });
    }

    // ===== FORM SUBMIT (EDIT MODE ONLY) =====
    $('#exportForm').on('submit', function (e) {
      e.preventDefault();
      
      if (!isEditMode) {
        Swal.fire('Error', 'Please use the "Proceed to Create" button to create exports', 'error');
        return;
      }

      if (!validateDatePairs()) {
        return;
      }

      $('#subscriber_id, #license_id').prop('disabled', false);

      const submitBtn = $('#submitBtn');
      const originalText = submitBtn.html();
      submitBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Saving...');

      const formData = $(this).serialize() + '&csrf_token=' + encodeURIComponent(csrfToken);

      $.ajax({
        url: '<?= APP_URL ?>/export/crudData/update',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(res) {
          submitBtn.prop('disabled', false).html(originalText);
          
          if (res.success) {
            Swal.fire({ 
              icon: 'success', 
              title: 'Success!', 
              html: res.message, 
              timer: 2000, 
              showConfirmButton: true 
            });
            
            resetForm();
            
            if (typeof exportsTable !== 'undefined') {
              exportsTable.ajax.reload(null, false);
            }
            updateStatistics();
            loadAvailableSeals();
          } else {
            Swal.fire({ 
              icon: 'error', 
              title: 'Error!', 
              html: res.message 
            });
            
            $('#subscriber_id, #license_id').prop('disabled', true);
          }
        },
        error: function(xhr) {
          submitBtn.prop('disabled', false).html(originalText);
          
          $('#subscriber_id, #license_id').prop('disabled', true);
          
          let errorMsg = 'An error occurred while processing your request';
          
          if (xhr.status === 403) {
            errorMsg = 'Security token expired. Please refresh the page and try again.';
          }
          
          Swal.fire({ 
            icon: 'error', 
            title: 'Server Error', 
            html: errorMsg 
          });
        }
      });
    });

    // ===== DELETE =====
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
            url: '<?= APP_URL ?>/export/crudData/deletion',
            method:'POST',
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
                exportsTable.ajax.reload(null, false);
                updateStatistics();
                loadAvailableSeals();
              } else {
                Swal.fire('Error', res.message || 'Delete failed', 'error');
              }
            },
            error: function (xhr) {
              let errorMsg = 'Failed to delete export';
              
              if (xhr.status === 403) {
                errorMsg = 'Security token expired. Please refresh the page and try again.';
              }
              
              Swal.fire('Error', errorMsg, 'error');
            }
          });
        }
      });
    });

    // ===== DATATABLE =====
    var exportsTable;
    function initDataTable() {
      if ($.fn.DataTable.isDataTable('#exportsTable')) {
        $('#exportsTable').DataTable().destroy();
      }

      exportsTable = $('#exportsTable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: { 
          url: '<?= APP_URL ?>/export/crudData/listing', 
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
            data: 'loading_date',
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
              <button class="btn btn-sm btn-view viewBtn" data-id="${parseInt(row.id)}" title="View">
                <i class="ti ti-eye"></i>
              </button>
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
        responsive: false
      });
    }

    function updateStatistics() {
      $.ajax({
        url: '<?= APP_URL ?>/export/crudData/statistics',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
          if (res.success) {
            $('#totalTrackings').text(res.data.total_exports || 0);
            $('#totalCompleted').text(res.data.total_completed || 0);
            $('#totalInProgress').text(res.data.in_progress || 0);
            $('#totalInTransit').text(res.data.in_transit || 0);
            $('#totalCEECPending').text(res.data.ceec_pending || 0);
            $('#totalMinDivPending').text(res.data.min_div_pending || 0);
            $('#totalGovDocsPending').text(res.data.gov_docs_pending || 0);
            $('#totalAuditedPending').text(res.data.audited_pending || 0);
            $('#totalArchivedPending').text(res.data.archived_pending || 0);
            $('#totalDGDAInPending').text(res.data.dgda_in_pending || 0);
            $('#totalLiquidationPending').text(res.data.liquidation_pending || 0);
            $('#totalQuittancePending').text(res.data.quittance_pending || 0);
          }
        },
        error: function() {
          console.error('Failed to load statistics');
        }
      });
    }

    function formatDate(dateStr) {
      if (!dateStr) return '';
      const date = new Date(dateStr);
      return date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    function exportToExcel(exportId) {
      window.location.href = '<?= APP_URL ?>/export/crudData/exportExport?id=' + exportId;
      
      Swal.fire({
        icon: 'success',
        title: 'Exporting...',
        text: 'Your export will download shortly',
        timer: 2000,
        showConfirmButton: false
      });
    }

    $(document).on('click', '.exportBtn', function () {
      const id = parseInt($(this).data('id'));
      exportToExcel(id);
    });

    // ===== INITIALIZE =====
    loadAvailableSeals();
    initDataTable();
    updateStatistics();
  });
</script>