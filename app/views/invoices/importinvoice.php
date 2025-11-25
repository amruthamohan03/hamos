<?php
// app/views/invoices/importinvoice.php
?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
  /**
   * IMPORT INVOICE MANAGEMENT STYLES
   */
  
  /* ========== COMPACT FORM STYLING ========== */
  .form-label { 
    font-size: 0.8rem; 
    font-weight: 600; 
    margin-bottom: 0.25rem;
    color: #2c3e50;
  }
  
  .form-control, .form-select { 
    font-size: 0.85rem; 
    padding: 0.4rem 0.6rem;
    height: auto;
  }
  
  .mb-2 { margin-bottom: 0.5rem !important; }
  
  /* ========== ENHANCED STATS CARDS - 4 PER ROW ========== */
  .stats-card {
    border: none;
    border-radius: 12px;
    transition: all 0.3s ease;
    overflow: hidden;
    cursor: pointer;
    background: white;
    border: 2px solid transparent;
    position: relative;
  }
  
  .stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
  }
  
  .stats-card.active-filter {
    border-color: #667eea;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    transform: scale(1.02);
  }
  
  .stats-card .card-body {
    padding: 18px;
    position: relative;
  }
  
  .stats-card-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
  }
  
  .stats-card-icon i {
    font-size: 22px;
    color: white;
  }
  
  .icon-purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
  .icon-green { background: linear-gradient(135deg, #2ECC71 0%, #27AE60 100%); }
  .icon-orange { background: linear-gradient(135deg, #F39C12 0%, #E67E22 100%); }
  .icon-blue { background: linear-gradient(135deg, #3498DB 0%, #2980B9 100%); }
  
  .stats-value {
    font-size: 2rem;
    font-weight: 700;
    color: #2C3E50;
    margin-bottom: 5px;
    line-height: 1;
  }
  
  .stats-label {
    font-size: 0.8rem;
    color: #7F8C8D;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .filter-indicator {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #667eea;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
  }
  
  .stats-card.active-filter .filter-indicator {
    display: flex;
  }
  
  /* ========== ACCORDION STYLING ========== */
  .accordion-button {
    font-weight: 600;
    background-color: #f8f9fa !important;
    color: #333 !important;
    padding: 1rem 1.25rem;
    border: none;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
  }
  
  .accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    box-shadow: none !important;
  }
  
  .accordion-button::after {
    margin-left: 0 !important;
  }
  
  .accordion-button:not(.collapsed)::after {
    filter: brightness(0) invert(1);
  }
  
  .accordion-button:focus {
    box-shadow: none !important;
    border-color: rgba(0,0,0,.125) !important;
  }
  
  .accordion-button:hover {
    background-color: #e9ecef !important;
  }
  
  .accordion-button:not(.collapsed):hover {
    background: linear-gradient(135deg, #5568d3 0%, #6a4893 100%) !important;
  }
  
  .accordion-title-section {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
  }
  
  .accordion-item {
    border: none;
    border-radius: 12px !important;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
  }
  
  .accordion-body {
    background: #ffffff;
    padding: 1.5rem;
  }
  
  /* ========== DATATABLE HEADER WITH SEARCH AND EXPORT ========== */
  .datatable-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding: 15px 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
  }
  
  .datatable-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .datatable-actions {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .custom-search-box {
    position: relative;
    width: 250px;
  }
  
  .custom-search-box input {
    width: 100%;
    padding: 0.5rem 2.5rem 0.5rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    transition: all 0.3s;
  }
  
  .custom-search-box input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }
  
  .custom-search-box i {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #7f8c8d;
    pointer-events: none;
  }
  
  .btn-export-all {
    background: #28a745 !important;
    border: none !important;
    color: white !important;
    font-weight: 600 !important;
    padding: 0.5rem 1.25rem !important;
    font-size: 0.875rem !important;
    border-radius: 0.375rem !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    transition: all 0.3s !important;
  }
  
  .btn-export-all:hover {
    background: #218838 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
  }
  
  /* ========== REQUIRED FIELDS ========== */
  .text-danger { 
    color: #dc3545; 
    font-weight: bold; 
  }
  
  .is-invalid { 
    border-color: #dc3545 !important; 
  }
  
  .invalid-feedback { 
    display: block; 
    color: #dc3545; 
    font-size: 0.75rem; 
    margin-top: 0.15rem; 
  }
  
  /* ========== AUTO-GENERATED FIELDS ========== */
  .readonly-field { 
    background-color: #e9ecef; 
    cursor: not-allowed; 
  }
  
  .calculated-field { 
    background-color: #d1ecf1; 
    cursor: not-allowed; 
    font-weight: 600; 
  }
  
  .auto-generated-field {
    background-color: #f8f9fa;
    cursor: not-allowed;
  }
  
  .hidden-field { 
    display: none !important; 
  }
  
  /* ========== SIDE-BY-SIDE LAYOUT ========== */
  .invoice-layout {
    display: flex;
    gap: 20px;
    margin-top: 15px;
  }
  
  .invoice-left-panel {
    flex: 0 0 420px;
    max-width: 420px;
  }
  
  .invoice-right-panel {
    flex: 1;
  }
  
  /* ========== PANEL HEADERS ========== */
  .panel-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 10px 15px;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 8px 8px 0 0;
  }
  
  .panel-body {
    background: white;
    border: 1px solid #e9ecef;
    border-top: none;
    border-radius: 0 0 8px 8px;
    padding: 15px;
  }
  
  /* ========== SECTION HEADERS ========== */
  .section-header {
    background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
    color: white;
    padding: 8px 12px;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  
  /* ========== FINANCIAL TABLE ========== */
  .financial-table {
    width: 100%;
    margin-bottom: 12px;
  }
  
  .financial-table td {
    padding: 6px 10px;
    border-bottom: 1px solid #ecf0f1;
  }
  
  .financial-table td:first-child {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    width: 160px;
    border-radius: 4px;
  }
  
  .financial-table td:last-child {
    padding-left: 12px;
  }
  
  .financial-table input,
  .financial-table select {
    width: 100%;
    padding: 5px 10px;
    border: 1px solid #bdc3c7;
    border-radius: 4px;
    font-size: 0.85rem;
  }
  
  .financial-table .input-group {
    display: flex;
    gap: 6px;
  }
  
  .financial-table .input-group select {
    width: 80px;
    flex-shrink: 0;
  }
  
  .financial-table .input-group input {
    flex: 1;
  }
  
  /* ========== QUOTATION SELECTOR ========== */
  #quotationSelector {
    width: 100%;
    padding: 8px 12px;
    border: 2px solid #e9ecef;
    border-radius: 6px;
    font-size: 0.85rem;
    transition: all 0.3s;
    background: white;
  }
  
  #quotationSelector:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }
  
  #quotationSelector option {
    padding: 8px;
  }
  
  /* ========== SUMMARY TOTALS BOX ========== */
  .summary-totals {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 18px 20px;
    margin-top: 20px;
    border: 1px solid #dee2e6;
  }
  
  .summary-totals table {
    width: 100%;
  }
  
  .summary-totals td {
    padding: 8px 0;
    font-size: 0.95rem;
  }
  
  .summary-totals td:first-child {
    color: #495057;
    font-weight: 500;
  }
  
  .summary-totals td:last-child {
    text-align: right;
    font-weight: 600;
    color: #212529;
    font-size: 1rem;
  }
  
  .summary-totals .grand-total td {
    font-size: 1.1rem;
    padding-top: 12px;
    border-top: 2px solid #adb5bd;
    font-weight: 700;
  }
  
  .summary-totals .grand-total td:last-child {
    color: #28a745;
  }
  
  /* ========== STATUS BADGE ========== */
  .status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
  }
  
  .status-pending {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
    color: #856404;
  }
  
  .status-completed {
    background: linear-gradient(135deg, #d1e7dd 0%, #a3cfbb 100%);
    color: #0f5132;
  }
  
  .status-draft {
    background: linear-gradient(135deg, #e2e3e5 0%, #c6c8ca 100%);
    color: #383d41;
  }
  
  /* ========== BANK SELECTION STYLES ========== */
  .bank-select-container {
    margin-top: 8px;
  }
  
  .bank-select-container select {
    width: 100%;
    padding: 8px 12px;
    border: 2px solid #e9ecef;
    border-radius: 6px;
    font-size: 0.85rem;
    transition: all 0.3s;
    background: white;
  }
  
  .bank-select-container select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }
  
  .bank-alert {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 12px;
    text-align: center;
    color: #6c757d;
    font-size: 0.85rem;
  }
  
  /* ========== ACTION BUTTONS ========== */
  .btn-sm {
    padding: 0.35rem 0.65rem;
    font-size: 0.875rem;
  }
  
  .btn-pdf {
    background: #dc3545 !important;
    color: white !important;
    border: none !important;
  }
  
  .btn-pdf:hover {
    background: #c82333 !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
  }
  
  .btn-export {
    background: #28a745 !important;
    color: white !important;
    border: none !important;
  }
  
  .btn-export:hover {
    background: #218838 !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
  }
  
  /* ========== CARD SHADOWS ========== */
  .card {
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border: none;
    border-radius: 12px;
  }
  
  /* ========== QUOTATION PANEL ========== */
  .quotation-content {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
    text-align: center;
    color: #7f8c8d;
    font-size: 0.9rem;
  }
  
  .quotation-panel {
    background: white;
    border: 1px solid #e9ecef;
    border-top: none;
    border-radius: 0 0 8px 8px;
    padding: 12px;
  }
  
  #quotationItemsContainer {
    margin-bottom: 15px;
  }
  
  /* ========== QUOTATION AUTO-MATCHED INFO BOX ========== */
  .quotation-info-box {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border: 2px solid #4caf50;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .quotation-info-box i {
    font-size: 24px;
    color: #2e7d32;
  }
  
  .quotation-info-text {
    flex: 1;
  }
  
  .quotation-info-text h6 {
    margin: 0 0 4px 0;
    color: #1b5e20;
    font-weight: 600;
    font-size: 0.9rem;
  }
  
  .quotation-info-text p {
    margin: 0;
    color: #2e7d32;
    font-size: 0.75rem;
  }
  
  /* ========== QUOTATION ITEMS DISPLAY ========== */
  .quotation-category-header {
    background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
    color: white;
    padding: 12px 15px;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-top: 15px;
    margin-bottom: 0;
    border-radius: 6px 6px 0 0;
  }
  
  .quotation-category-header:first-child {
    margin-top: 0;
  }
  
  .category-items-display {
    background: white;
    border: 1px solid #e9ecef;
    border-top: none;
    border-radius: 0 0 6px 6px;
    padding: 0;
  }
  
  .quotation-items-row {
    display: grid;
    grid-template-columns: 2fr 1fr 0.8fr 1fr 1fr 0.8fr 1fr 1.2fr 0.8fr;
    gap: 8px;
    padding: 10px 12px;
    border-bottom: 1px solid #ecf0f1;
    align-items: center;
  }
  
  .quotation-items-row:last-child {
    border-bottom: none;
  }
  
  .quotation-items-header {
    background: #ecf0f1;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 0.3px;
    color: #2c3e50;
    border-bottom: 2px solid #bdc3c7;
  }
  
  .editable-row:hover {
    background: #f8f9fa;
  }
  
  .qitem-col {
    font-size: 0.75rem;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
  .qitem-description {
    font-weight: 500;
    color: #2c3e50;
  }
  
  .qitem-qty,
  .qitem-tva,
  .qitem-actions {
    text-align: center;
  }
  
  .qitem-rate,
  .qitem-tva-amount,
  .qitem-total {
    text-align: right;
    font-family: 'Courier New', monospace;
  }
  
  .qitem-total {
    font-weight: 600;
    color: #2c3e50;
  }
  
  .qitem-unit,
  .qitem-currency {
    text-transform: uppercase;
    font-size: 0.7rem;
    color: #7f8c8d;
  }
  
  /* ========== EDITABLE INPUTS ========== */
  .item-input {
    width: 100%;
    padding: 5px 8px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    font-size: 0.75rem;
    font-family: 'Courier New', monospace;
    transition: border-color 0.2s;
  }
  
  .item-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
  }
  
  .item-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
    margin: 0 auto;
    display: block;
  }
  
  .item-total {
    font-weight: 600;
    color: #2c3e50;
    font-family: 'Courier New', monospace;
  }
  
  /* ========== DELETE BUTTON ========== */
  .delete-item-btn {
    padding: 5px 10px;
    background: #ef4444;
    border: none;
    color: white;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
  }
  
  .delete-item-btn:hover {
    background: #dc2626;
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
  }
  
  .text-right {
    text-align: right;
  }
  
  .text-center {
    text-align: center;
  }
  
  /* ========== RESPONSIVE ADJUSTMENTS ========== */
  @media (max-width: 1400px) {
    .stats-card .card-body {
      padding: 15px;
    }
    
    .stats-value {
      font-size: 1.8rem;
    }
  }
  
  @media (max-width: 992px) {
    .invoice-layout {
      flex-direction: column;
    }
    
    .invoice-left-panel {
      flex: 1;
      max-width: 100%;
    }
    
    .datatable-header {
      flex-direction: column;
      gap: 10px;
      align-items: flex-start;
    }
    
    .datatable-actions {
      width: 100%;
      flex-direction: column;
    }
    
    .custom-search-box {
      width: 100%;
    }
    
    .btn-export-all {
      width: 100%;
      justify-content: center !important;
    }
    
    .quotation-items-row {
      grid-template-columns: 1fr;
      gap: 4px;
    }
    
    .quotation-items-header {
      display: none;
    }
    
    .qitem-col::before {
      content: attr(data-label);
      font-weight: 600;
      display: inline-block;
      margin-right: 8px;
      color: #2c3e50;
    }
  }
  
  @media (max-width: 768px) {
    .stats-card .card-body {
      padding: 12px;
    }
    
    .stats-value {
      font-size: 1.5rem;
    }
    
    .stats-label {
      font-size: 0.7rem;
    }
  }
</style>

<div class="page-content">
  <div class="page-container">
    <div class="row">
      <div class="col-12">
        
        <!-- ========== ENHANCED STATISTICS CARDS - 4 CARDS ========== -->
        <div class="row mb-4">
          <!-- Total Invoices -->
          <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stats-card filter-card" data-filter="all">
              <div class="card-body">
                <div class="stats-card-icon icon-purple">
                  <i class="ti ti-file-invoice"></i>
                </div>
                <div class="stats-value" id="totalInvoices">0</div>
                <div class="stats-label">Total Invoices</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
          
          <!-- Completed -->
          <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stats-card filter-card" data-filter="completed">
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
          
          <!-- Pending -->
          <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stats-card filter-card" data-filter="pending">
              <div class="card-body">
                <div class="stats-card-icon icon-orange">
                  <i class="ti ti-clock"></i>
                </div>
                <div class="stats-value" id="totalPending">0</div>
                <div class="stats-label">Pending</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
          
          <!-- Draft -->
          <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stats-card filter-card" data-filter="draft">
              <div class="card-body">
                <div class="stats-card-icon icon-blue">
                  <i class="ti ti-file-text"></i>
                </div>
                <div class="stats-value" id="totalDraft">0</div>
                <div class="stats-label">Draft</div>
                <div class="filter-indicator">✓</div>
              </div>
            </div>
          </div>
        </div>

        <!-- ========== ACCORDION WITH FORM ========== -->
        <div class="accordion mb-4" id="invoiceAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingInvoice">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInvoice" aria-expanded="false" aria-controls="collapseInvoice">
                <div class="accordion-title-section">
                  <i class="ti ti-file-invoice"></i>
                  <span id="formTitle">Add New Import Invoice</span>
                </div>
              </button>
            </h2>
            <div id="collapseInvoice" class="accordion-collapse collapse" aria-labelledby="headingInvoice" data-bs-parent="#invoiceAccordion">
              <div class="accordion-body">
                
                <form id="invoiceForm" method="post" novalidate data-csrf-token="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                  <input type="hidden" name="invoice_id" id="invoice_id" value="">
                  <input type="hidden" name="action" id="formAction" value="insert">
                  <input type="hidden" name="kind_id" id="kind_id" value="">
                  <input type="hidden" name="type_of_goods_id" id="type_of_goods_id" value="">
                  <input type="hidden" name="transport_mode_id" id="transport_mode_id" value="">
                  <input type="hidden" name="quotation_items" id="quotation_items" value="">

                  <!-- Basic Info -->
                  <div class="row mb-2">
                    <div class="col-md-2 mb-2">
                      <label class="form-label">Subscriber <span class="text-danger">*</span></label>
                      <select name="subscriber_id" id="subscriber_id" class="form-select" required>
                        <option value="">-- Select --</option>
                        <?php foreach ($subscribers as $sub): ?>
                          <option value="<?= $sub['id'] ?>"><?= htmlspecialchars($sub['short_name']) ?></option>
                        <?php endforeach; ?>
                      </select>
                      <div class="invalid-feedback" id="subscriber_id_error"></div>
                    </div>

                    <div class="col-md-2 mb-2">
                      <label class="form-label">License Number <span class="text-danger">*</span></label>
                      <select name="license_id" id="license_id" class="form-select" required>
                        <option value="">-- Select --</option>
                      </select>
                      <div class="invalid-feedback" id="license_id_error"></div>
                    </div>

                    <div class="col-md-3 mb-2">
                      <label class="form-label">MCA Reference <span class="text-danger">*</span></label>
                      <select name="mca_id" id="mca_id" class="form-select" required>
                        <option value="">Select MCA Reference</option>
                      </select>
                      <div class="invalid-feedback" id="mca_id_error"></div>
                    </div>

                    <div class="col-md-3 mb-2">
                      <label class="form-label">Invoice Ref <span class="text-danger">*</span></label>
                      <input type="text" name="invoice_ref" id="invoice_ref" class="form-control auto-generated-field" required maxlength="100" readonly placeholder="Select Subscriber First">
                      <div class="invalid-feedback" id="invoice_ref_error"></div>
                    </div>

                    <div class="col-md-2 mb-2">
                      <label class="form-label">Tax & Duty Part:</label>
                      <select name="tax_duty_part" id="tax_duty_part" class="form-select">
                        <option value="Include">Include</option>
                        <option value="Exclude">Exclude</option>
                      </select>
                    </div>
                  </div>

                  <!-- Row 2 -->
                  <div class="row mb-2">
                    <div class="col-md-2 mb-2">
                      <label class="form-label">Kind <span class="text-danger">*</span></label>
                      <input type="text" name="kind" id="kind" class="form-control readonly-field" readonly placeholder="From MCA">
                    </div>

                    <div class="col-md-3 mb-2">
                      <label class="form-label">Type of Goods <span class="text-danger">*</span></label>
                      <input type="text" name="type_of_goods" id="type_of_goods" class="form-control readonly-field" readonly placeholder="From MCA">
                    </div>

                    <div class="col-md-2 mb-2">
                      <label class="form-label">Transport Mode <span class="text-danger">*</span></label>
                      <input type="text" name="transport_mode" id="transport_mode" class="form-control readonly-field" readonly placeholder="From MCA">
                    </div>

                    <div class="col-md-3 mb-2">
                      <label class="form-label">Invoice Template:</label>
                      <select name="invoice_template" id="invoice_template" class="form-select">
                        <option value="">Select</option>
                      </select>
                    </div>

                    <div class="col-md-2 mb-2">
                      <label class="form-label">ARSP:</label>
                      <input type="text" name="arsp" id="arsp" class="form-control" value="Disabled" placeholder="Disabled">
                    </div>
                  </div>

                  <!-- SIDE-BY-SIDE LAYOUT -->
                  <div class="invoice-layout">
                    
                    <!-- LEFT PANEL -->
                    <div class="invoice-left-panel">
                      <div class="panel-header">
                        <i class="ti ti-receipt"></i> INVOICE DETAILS
                      </div>
                      <div class="panel-body">
                        
                        <div class="section-header">
                          FINANCIAL INFO
                        </div>
                        
                        <table class="financial-table">
                          <tr>
                            <td>FOB</td>
                            <td>
                              <div class="input-group">
                                <select name="fob_currency_id" id="fob_currency_id">
                                  <option value="">USD</option>
                                  <?php foreach ($currencies as $curr): ?>
                                    <option value="<?= $curr['id'] ?>"><?= htmlspecialchars($curr['currency_short_name']) ?></option>
                                  <?php endforeach; ?>
                                </select>
                                <input type="number" step="0.01" name="fob_usd" id="fob_usd" class="financial-calc-trigger" placeholder="0.00" min="0">
                              </div>
                            </td>
                          </tr>
                          
                          <tr>
                            <td>FRET</td>
                            <td>
                              <div class="input-group">
                                <select name="fret_currency_id" id="fret_currency_id">
                                  <option value="">USD</option>
                                  <?php foreach ($currencies as $curr): ?>
                                    <option value="<?= $curr['id'] ?>"><?= htmlspecialchars($curr['currency_short_name']) ?></option>
                                  <?php endforeach; ?>
                                </select>
                                <input type="number" step="0.01" name="fret_usd" id="fret_usd" class="financial-calc-trigger" placeholder="0.00" min="0">
                              </div>
                            </td>
                          </tr>
                          
                          <tr>
                            <td>ASSURANCE</td>
                            <td>
                              <div class="input-group">
                                <select name="assurance_currency_id" id="assurance_currency_id">
                                  <option value="">USD</option>
                                  <?php foreach ($currencies as $curr): ?>
                                    <option value="<?= $curr['id'] ?>"><?= htmlspecialchars($curr['currency_short_name']) ?></option>
                                  <?php endforeach; ?>
                                </select>
                                <input type="number" step="0.01" name="assurance_usd" id="assurance_usd" class="financial-calc-trigger" placeholder="0.00" min="0">
                              </div>
                            </td>
                          </tr>
                          
                          <tr>
                            <td>AUTRES CHARGES</td>
                            <td>
                              <div class="input-group">
                                <select name="autres_charges_currency_id" id="autres_charges_currency_id">
                                  <option value="">USD</option>
                                  <?php foreach ($currencies as $curr): ?>
                                    <option value="<?= $curr['id'] ?>"><?= htmlspecialchars($curr['currency_short_name']) ?></option>
                                  <?php endforeach; ?>
                                </select>
                                <input type="number" step="0.01" name="autres_charges_usd" id="autres_charges_usd" class="financial-calc-trigger" placeholder="0.00" min="0">
                              </div>
                            </td>
                          </tr>
                          
                          <tr>
                            <td>RATE CDF/INV</td>
                            <td>
                              <input type="number" step="0.01" name="rate_cdf_inv" id="rate_cdf_inv" class="financial-calc-trigger" placeholder="2500.00" value="2500.00" min="0">
                            </td>
                          </tr>
                          
                          <tr>
                            <td>RATE CDF/USD BCC</td>
                            <td>
                              <input type="number" step="0.01" name="rate_cdf_usd_bcc" id="rate_cdf_usd_bcc" class="financial-calc-trigger" placeholder="2500.00" value="2500.00" min="0">
                            </td>
                          </tr>
                          
                          <tr>
                            <td>CIF USD</td>
                            <td>
                              <input type="number" step="0.01" name="cif_usd" id="cif_usd" class="calculated-field" readonly placeholder="0.00">
                            </td>
                          </tr>
                          
                          <tr>
                            <td>CIF CDF</td>
                            <td>
                              <input type="number" step="0.01" name="cif_cdf" id="cif_cdf" class="calculated-field" readonly placeholder="0.00">
                            </td>
                          </tr>
                          
                          <tr>
                            <td>TOTAL DUTY CDF</td>
                            <td>
                              <input type="number" step="0.01" name="total_duty_cdf" id="total_duty_cdf" class="calculated-field" readonly placeholder="0">
                            </td>
                          </tr>
                          
                          <tr>
                            <td>POIDS (KG)</td>
                            <td>
                              <input type="number" step="0.01" name="poids_kg" id="poids_kg" placeholder="0.00" min="0">
                            </td>
                          </tr>
                          
                          <tr>
                            <td>TARIFF CODE</td>
                            <td>
                              <input type="text" name="tariff_code_client" id="tariff_code_client" maxlength="100">
                            </td>
                          </tr>
                        </table>
                        
                        <!-- ROAD TRANSPORT DETAILS -->
                        <div id="roadTransportSection" class="hidden-field">
                          <div class="section-header" style="margin-top: 10px;">
                            ROAD TRANSPORT
                          </div>
                          
                          <table class="financial-table">
                            <tr>
                              <td>HORSE</td>
                              <td>
                                <input type="text" name="horse" id="horse" maxlength="100">
                              </td>
                            </tr>
                            <tr>
                              <td>TRAILER 1</td>
                              <td>
                                <input type="text" name="trailer_1" id="trailer_1" maxlength="100">
                              </td>
                            </tr>
                            <tr>
                              <td>TRAILER 2</td>
                              <td>
                                <input type="text" name="trailer_2" id="trailer_2" maxlength="100">
                              </td>
                            </tr>
                            <tr>
                              <td>CONTAINER</td>
                              <td>
                                <input type="text" name="container" id="container" maxlength="100">
                              </td>
                            </tr>
                          </table>
                        </div>
                        
                        <!-- RAIL TRANSPORT DETAILS -->
                        <div id="railTransportSection" class="hidden-field">
                          <div class="section-header" style="margin-top: 10px;">
                            WAGON TRANSPORT
                          </div>
                          
                          <table class="financial-table">
                            <tr>
                              <td>WAGON</td>
                              <td>
                                <input type="text" name="wagon" id="wagon" maxlength="100">
                              </td>
                            </tr>
                            <tr>
                              <td>HORSE</td>
                              <td>
                                <input type="text" name="horse_rail" id="horse_rail" maxlength="100">
                              </td>
                            </tr>
                            <tr>
                              <td>TRAILER 1</td>
                              <td>
                                <input type="text" name="trailer_1_rail" id="trailer_1_rail" maxlength="100">
                              </td>
                            </tr>
                            <tr>
                              <td>TRAILER 2</td>
                              <td>
                                <input type="text" name="trailer_2_rail" id="trailer_2_rail" maxlength="100">
                              </td>
                            </tr>
                            <tr>
                              <td>CONTAINER</td>
                              <td>
                                <input type="text" name="container_rail" id="container_rail" maxlength="100">
                              </td>
                            </tr>
                          </table>
                        </div>
                        
                        <!-- AIR TRANSPORT DETAILS -->
                        <div id="airTransportSection" class="hidden-field">
                          <div class="section-header" style="margin-top: 10px;">
                            AIR TRANSPORT
                          </div>
                          
                          <table class="financial-table">
                            <tr>
                              <td>AIRWAY BILL</td>
                              <td>
                                <input type="text" name="airway_bill" id="airway_bill" maxlength="100">
                              </td>
                            </tr>
                            <tr>
                              <td>AIRWAY BILL WEIGHT</td>
                              <td>
                                <input type="number" step="0.01" name="airway_bill_weight" id="airway_bill_weight" placeholder="0.00" min="0">
                              </td>
                            </tr>
                            <tr>
                              <td>CONTAINER</td>
                              <td>
                                <input type="text" name="container_air" id="container_air" maxlength="100">
                              </td>
                            </tr>
                          </table>
                        </div>
                        
                        <!-- DOCUMENT DETAILS -->
                        <div class="section-header" style="margin-top: 10px;">
                          DOCUMENT DETAILS
                        </div>
                        
                        <table class="financial-table">
                          <tr>
                            <td>FACTURE/PFI NO</td>
                            <td>
                              <input type="text" name="facture_pfi_no" id="facture_pfi_no" maxlength="100">
                            </td>
                          </tr>
                          <tr>
                            <td>PO REF</td>
                            <td>
                              <input type="text" name="po_ref" id="po_ref" maxlength="100">
                            </td>
                          </tr>
                          <tr>
                            <td>BIVAC INSPECT</td>
                            <td>
                              <input type="text" name="bivac_inspection" id="bivac_inspection" maxlength="100">
                            </td>
                          </tr>
                          <tr>
                            <td>PRODUIT</td>
                            <td>
                              <input type="text" name="produit" id="produit" value="Default Commodity" maxlength="255">
                            </td>
                          </tr>
                          <tr>
                            <td>EXONERAT/CODE</td>
                            <td>
                              <input type="text" name="exoneration_code" id="exoneration_code" maxlength="100">
                            </td>
                          </tr>
                          <tr>
                            <td>DECLARATION NO</td>
                            <td>
                              <input type="text" name="declaration_no" id="declaration_no" maxlength="100">
                            </td>
                          </tr>
                          <tr>
                            <td>DECLAR DATE</td>
                            <td>
                              <input type="date" name="declaration_date" id="declaration_date">
                            </td>
                          </tr>
                          <tr>
                            <td>LIQUIDATION NO</td>
                            <td>
                              <input type="text" name="liquidation_no" id="liquidation_no" maxlength="100">
                            </td>
                          </tr>
                          <tr>
                            <td>LIQUID DATE</td>
                            <td>
                              <input type="date" name="liquidation_date" id="liquidation_date">
                            </td>
                          </tr>
                          <tr>
                            <td>QUITTANCE NO</td>
                            <td>
                              <input type="text" name="quittance_no" id="quittance_no" maxlength="100">
                            </td>
                          </tr>
                          <tr>
                            <td>QUIT DATE</td>
                            <td>
                              <input type="date" name="quittance_date" id="quittance_date">
                            </td>
                          </tr>
                          <tr>
                            <td>DISPATCH DATE</td>
                            <td>
                              <input type="date" name="dispatch_deliver_date" id="dispatch_deliver_date">
                            </td>
                          </tr>
                        </table>
                        
                        <!-- BANK SELECTION -->
                        <div class="section-header" style="margin-top: 10px;">
                          BANK SELECTION
                        </div>
                        
                        <div id="bankContainer" class="bank-select-container">
                          <div class="bank-alert">
                            <i class="ti ti-info-circle me-1"></i>
                            Select a subscriber to load banks
                          </div>
                        </div>
                        
                      </div>
                    </div>
                    
                    <!-- RIGHT PANEL - QUOTATION SELECTION WITH EDITABLE ITEMS -->
                    <div class="invoice-right-panel">
                      <div class="section-header">
                        <span>QUOTATION SELECTION</span>
                      </div>
                      <div class="quotation-panel">
                        
                        <!-- QUOTATION DROPDOWN SELECTOR -->
                        <div style="margin-bottom: 15px;">
                          <label class="form-label" style="font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; display: block;">
                            Select Quotation <span class="text-danger">*</span>
                          </label>
                          <select id="quotationSelector" class="form-select" style="padding: 8px 12px; font-size: 0.85rem;">
                            <option value="">-- Select Subscriber First --</option>
                          </select>
                          <div style="margin-top: 5px; font-size: 0.75rem; color: #7f8c8d;">
                            <i class="ti ti-info-circle"></i> Quotations are automatically loaded when you select a Subscriber
                          </div>
                        </div>
                        
                        <!-- Quotation Info Box (Hidden by default) -->
                        <div id="quotationInfoBox" style="display: none;"></div>
                        
                        <!-- Quotation Items Container -->
                        <div id="quotationItemsContainer" style="min-height: 50px;">
                          <div class="quotation-content">
                            <p style="margin: 0;"><i class="ti ti-info-circle me-1"></i> Select a quotation from the dropdown above</p>
                          </div>
                        </div>
                        
                        <!-- Summary Totals -->
                        <div class="summary-totals">
                          <table>
                            <tr>
                              <td>Total excl. TVA</td>
                              <td id="totalExclTVA">$0.00</td>
                            </tr>
                            <tr>
                              <td>TVA (16%)</td>
                              <td id="tvaAmount">$0.00</td>
                            </tr>
                            <tr class="grand-total">
                              <td>Grand Total</td>
                              <td id="grandTotal">$0.00</td>
                            </tr>
                            <tr>
                              <td>Equivalent CDF</td>
                              <td id="equivalentCDF">0.00 CDF</td>
                            </tr>
                          </table>
                        </div>
                      </div>
                    </div>
                    
                  </div>

                  <!-- Form Buttons -->
                  <div class="row mt-4">
                    <div class="col-12 text-end">
                      <button type="button" class="btn btn-secondary btn-sm" id="cancelBtn">
                        <i class="ti ti-x me-1"></i> Cancel
                      </button>
                      <button type="submit" class="btn btn-primary btn-sm ms-2" id="submitBtn">
                        <i class="ti ti-check me-1"></i> <span id="submitBtnText">Save Invoice</span>
                      </button>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Invoices DataTable Card -->
        <div class="card shadow-sm">
          <div class="datatable-header">
            <div class="datatable-title">
              <i class="ti ti-list"></i>
              <span>Import Invoices List</span>
            </div>
            <div class="datatable-actions">
              <button type="button" class="btn btn-sm btn-secondary" id="clearFilterBtn" style="display:none;">
                <i class="ti ti-filter-off me-1"></i> Clear Filter
              </button>
              <div class="custom-search-box">
                <input type="text" id="customSearchBox" placeholder="Search invoices..." autocomplete="off">
                <i class="ti ti-search"></i>
              </div>
              <button type="button" class="btn btn-export-all" onclick="exportAllInvoices();">
                <i class="ti ti-file-spreadsheet"></i> Export All to Excel
              </button>
            </div>
          </div>
          
          <div class="card-body">
            <div class="table-responsive">
              <table id="invoicesTable" class="table table-striped table-bordered dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Invoice Ref</th>
                    <th>Subscriber</th>
                    <th>MCA Ref</th>
                    <th>CIF USD</th>
                    <th>Total Duty</th>
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

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<script>
/**
 * IMPORT INVOICE MANAGEMENT SYSTEM - COMPLETE JAVASCRIPT
 * UPDATED: License filter for kind_id IN (1,2,5,6) and Bank Selection
 */

$(document).ready(function () {
  let invoicesTable;
  let currentFilter = 'all';
  let quotationItemsData = [];
  let quotationData = null;

  let baseUrl = '<?= rtrim(APP_URL, "/") ?>';
  const CONTROLLER_URL = baseUrl + '/importinvoice';
  const csrfToken = $('#invoiceForm').data('csrf-token');
  
  console.log('🚀 Import Invoice System Initialized');
  console.log('📍 Controller URL:', CONTROLLER_URL);
  
  function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    const map = {'&': '&amp;','<': '&lt;','>': '&gt;','"': '&quot;',"'": '&#039;'};
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
  }
  
  function sanitizeNumber(value) {
    const num = parseFloat(value);
    return isNaN(num) || num < 0 ? 0 : num;
  }

  // ========== FILTER CARDS CLICK HANDLER ==========
  $('.filter-card').on('click', function() {
    $('.filter-card').removeClass('active-filter');
    $(this).addClass('active-filter');
    currentFilter = $(this).data('filter');
    $('#clearFilterBtn').toggle(currentFilter !== 'all');
    if (invoicesTable) invoicesTable.ajax.reload();
  });

  $('#clearFilterBtn').on('click', function() {
    $('.filter-card').removeClass('active-filter');
    $('.filter-card[data-filter="all"]').addClass('active-filter');
    currentFilter = 'all';
    $(this).hide();
    if (invoicesTable) invoicesTable.ajax.reload();
  });

  // ========== EXPORT ALL FUNCTION ==========
  window.exportAllInvoices = function() {
    console.log('📊 Exporting all invoices...');
    window.location.href = CONTROLLER_URL + '/crudData/exportAll';
    Swal.fire({
      icon: 'success', 
      title: 'Exporting...', 
      text: 'Your Excel file will download shortly', 
      timer: 1500, 
      showConfirmButton: false
    });
  };

  // ========== SUBSCRIBER CHANGE ==========
  $('#subscriber_id').on('change', function() {
    const subscriberId = $(this).val();
    
    console.log('👤 Subscriber changed:', subscriberId);
    
    // Reset fields
    $('#license_id').html('<option value="">-- Select --</option>');
    $('#mca_id').html('<option value="">Select MCA Reference</option>');
    $('#quotationSelector').html('<option value="">-- Select Subscriber First --</option>');
    $('#invoice_ref').val('').attr('placeholder', 'Select Subscriber First');
    clearMCAFields();
    clearQuotationItems();
    loadBanks(subscriberId);
    
    if (!subscriberId) return;

    // Generate invoice ref
    $.ajax({
      url: CONTROLLER_URL + '/crudData/getNextInvoiceRefForClient',
      method: 'GET',
      data: { subscriber_id: subscriberId },
      dataType: 'json',
      success: function(res) {
        console.log('📝 Invoice ref generated:', res);
        if (res.success) {
          $('#invoice_ref').val(res.invoice_ref).attr('placeholder', res.invoice_ref);
        }
      },
      error: function(xhr, status, error) {
        console.error('❌ Error generating invoice ref:', error);
      }
    });

    // Load licenses - FILTERED BY kind_id IN (1, 2, 5, 6)
    $.ajax({
      url: CONTROLLER_URL + '/crudData/getLicenses',
      method: 'GET',
      data: { subscriber_id: subscriberId },
      dataType: 'json',
      success: function(res) {
        console.log('📜 Licenses loaded (IMPORT types only):', res);
        if (res.success && res.data && res.data.length > 0) {
          res.data.forEach(function(license) {
            const kindInfo = license.kind_short_name ? ` (${escapeHtml(license.kind_short_name)})` : '';
            $('#license_id').append(`<option value="${license.id}">${escapeHtml(license.license_number)}${kindInfo}</option>`);
          });
        } else {
          console.log('ℹ️ No import licenses found for this subscriber');
        }
      },
      error: function(xhr, status, error) {
        console.error('❌ Error loading licenses:', error);
      }
    });

    // Load quotations
    $.ajax({
      url: CONTROLLER_URL + '/crudData/getAllQuotationsForSubscriber',
      method: 'GET',
      data: { subscriber_id: subscriberId },
      dataType: 'json',
      success: function(res) {
        console.log('📋 All quotations loaded:', res);
        
        if (res.success && res.data && res.data.length > 0) {
          let options = '<option value="">-- Select Quotation --</option>';
          res.data.forEach(function(quot) {
            options += `<option value="${quot.id}">${escapeHtml(quot.quotation_ref)}</option>`;
          });
          $('#quotationSelector').html(options);
          console.log('✅ Quotation dropdown populated with ' + res.data.length + ' quotations');
        } else {
          $('#quotationSelector').html('<option value="">-- No Quotations Found --</option>');
          console.log('ℹ️ No quotations found for subscriber');
        }
      },
      error: function(xhr, status, error) {
        console.error('❌ Error loading quotations:', error);
        $('#quotationSelector').html('<option value="">-- Error Loading Quotations --</option>');
      }
    });
  });

  // ========== LOAD BANKS FOR SUBSCRIBER ==========
  function loadBanks(subscriberId) {
    if (!subscriberId) {
      $('#bankContainer').html('<div class="bank-alert"><i class="ti ti-info-circle me-1"></i>Select a subscriber to load banks</div>');
      return;
    }

    console.log('🏦 Loading banks for subscriber:', subscriberId);

    $.ajax({
      url: CONTROLLER_URL + '/crudData/getBanks',
      method: 'GET',
      data: { subscriber_id: subscriberId },
      dataType: 'json',
      success: function(res) {
        console.log('🏦 Banks response:', res);
        
        if (res.success && res.data && res.data.length > 0) {
          let html = '<select name="bank_id" id="bank_id" class="form-select">';
          html += '<option value="">-- Select Bank --</option>';
          
          res.data.forEach(function(bank) {
            const bankName = escapeHtml(bank.bank_name || '');
            const accountName = escapeHtml(bank.account_name || '');
            const accountNumber = escapeHtml(bank.account_number || '');
            
            let displayText = bankName;
            if (accountName) displayText += ' - ' + accountName;
            if (accountNumber) displayText += ' (' + accountNumber + ')';
            
            html += `<option value="${bank.id}">${displayText}</option>`;
          });
          
          html += '</select>';
          $('#bankContainer').html(html);
          console.log('✅ Banks loaded:', res.data.length);
        } else {
          $('#bankContainer').html('<div class="bank-alert"><i class="ti ti-alert-circle me-1"></i>No banks configured for this subscriber</div>');
          console.log('ℹ️ No banks found for subscriber');
        }
      },
      error: function(xhr, status, error) {
        console.error('❌ Error loading banks:', error);
        $('#bankContainer').html('<div class="bank-alert"><i class="ti ti-x-circle me-1"></i>Error loading banks</div>');
      }
    });
  }

  // ========== QUOTATION SELECTOR CHANGE ==========
  $('#quotationSelector').on('change', function() {
    const quotationId = $(this).val();
    const subscriberId = $('#subscriber_id').val();
    
    if (!quotationId || !subscriberId) {
      clearQuotationItems();
      return;
    }

    console.log('🎯 Quotation selected:', quotationId);

    $.ajax({
      url: CONTROLLER_URL + '/crudData/getQuotationItems',
      method: 'GET',
      data: { quotation_id: quotationId, client_id: subscriberId },
      dataType: 'json',
      success: function(itemsRes) {
        console.log('📦 Quotation Items Response:', itemsRes);
        
        if (itemsRes.success) {
          quotationData = itemsRes.quotation;
          
          if (itemsRes.categorized_items && itemsRes.categorized_items.length > 0) {
            const quotDate = quotationData.quotation_date ? new Date(quotationData.quotation_date).toLocaleDateString() : '';
            
            const infoBoxHtml = `
              <div class="quotation-info-box">
                <i class="ti ti-circle-check"></i>
                <div class="quotation-info-text">
                  <h6>Quotation Selected: ${escapeHtml(quotationData.quotation_ref)}</h6>
                  <p>Date: ${quotDate} | Total: $${parseFloat(quotationData.total_amount || 0).toFixed(2)}</p>
                </div>
              </div>
            `;
            $('#quotationInfoBox').html(infoBoxHtml).show();
            
            displayQuotationItemsByCategory(itemsRes.categorized_items, quotationData);
            
            const subTotal = parseFloat(quotationData.sub_total || 0);
            const vatAmount = parseFloat(quotationData.vat_amount || 0);
            const totalAmount = parseFloat(quotationData.total_amount || 0);
            updateSummaryTotals(subTotal, vatAmount, totalAmount);
          } else {
            $('#quotationInfoBox').hide();
            $('#quotationItemsContainer').html('<div class="quotation-content"><p style="margin: 0; color: #e67e22;"><i class="ti ti-alert-triangle me-1"></i> No items found in quotation</p></div>');
          }
        } else {
          $('#quotationInfoBox').hide();
          $('#quotationItemsContainer').html('<div class="quotation-content"><p style="margin: 0; color: #e74c3c;"><i class="ti ti-x-circle me-1"></i> ' + (itemsRes.message || 'Error loading quotation items') + '</p></div>');
        }
      },
      error: function(xhr, status, error) {
        console.error('❌ AJAX Error fetching quotation items:', error);
        $('#quotationInfoBox').hide();
        $('#quotationItemsContainer').html('<div class="quotation-content"><p style="margin: 0; color: #e74c3c;"><i class="ti ti-x-circle me-1"></i> Error loading quotation items</p></div>');
      }
    });
  });

  // ========== LICENSE CHANGE ==========
  $('#license_id').on('change', function() {
    const subscriberId = $('#subscriber_id').val();
    const licenseId = $(this).val();
    
    console.log('🔑 License changed:', licenseId);
    
    $('#mca_id').html('<option value="">Select MCA Reference</option>');
    clearMCAFields();
    
    if (!subscriberId || !licenseId) return;

    $.ajax({
      url: CONTROLLER_URL + '/crudData/getMCAReferences',
      method: 'GET',
      data: { subscriber_id: subscriberId, license_id: licenseId },
      dataType: 'json',
      success: function(res) {
        console.log('📋 MCA references loaded:', res);
        if (res.success && res.data && res.data.length > 0) {
          res.data.forEach(function(mca) {
            $('#mca_id').append(`<option value="${mca.id}">${escapeHtml(mca.mca_ref)}</option>`);
          });
        }
      },
      error: function(xhr, status, error) {
        console.error('❌ Error loading MCA references:', error);
      }
    });
  });

  // ========== MCA CHANGE ==========
  $('#mca_id').on('change', function() {
    const mcaId = $(this).val();
    if (!mcaId) { 
      clearMCAFields();
      return; 
    }

    console.log('🎯 MCA selected, loading details for ID:', mcaId);

    $.ajax({
      url: CONTROLLER_URL + '/crudData/getMCADetails',
      method: 'GET',
      data: { mca_id: mcaId },
      dataType: 'json',
      success: function(res) {
        console.log('📦 MCA Details Response:', res);
        
        if (res.success && res.data) {
          const mca = res.data;
          
          // Set hidden IDs
          $('#kind_id').val(mca.kind_id || '');
          $('#type_of_goods_id').val(mca.goods_type_id || '');
          $('#transport_mode_id').val(mca.transport_mode_id || '');
          
          // Set display names
          $('#kind').val(escapeHtml(mca.kind_name || ''));
          $('#type_of_goods').val(escapeHtml(mca.type_of_goods_name || ''));
          $('#transport_mode').val(escapeHtml(mca.transport_mode_name || ''));
          
          // Set financial data
          $('#fob_usd').val(mca.fob || '');
          $('#fret_usd').val(mca.fret || '');
          $('#poids_kg').val(mca.weight || '');
          $('#produit').val(escapeHtml(mca.commodity || 'Default Commodity'));
          
          // Set document data
          $('#facture_pfi_no').val(escapeHtml(mca.facture_pfi_no || ''));
          $('#po_ref').val(escapeHtml(mca.po_ref || ''));
          $('#bivac_inspection').val(escapeHtml(mca.bivac_inspection || ''));
          $('#declaration_no').val(escapeHtml(mca.declaration_no || ''));
          $('#declaration_date').val(mca.declaration_date || '');
          $('#liquidation_no').val(escapeHtml(mca.liquidation_no || ''));
          $('#liquidation_date').val(mca.liquidation_date || '');
          $('#quittance_no').val(escapeHtml(mca.quittance_no || ''));
          $('#quittance_date').val(mca.quittance_date || '');
          $('#dispatch_deliver_date').val(mca.dispatch_deliver_date || '');
          
          // Transport mode logic
          const transportModeId = parseInt(mca.transport_mode_id);
          $('#roadTransportSection, #railTransportSection, #airTransportSection').addClass('hidden-field');
          
          if (transportModeId === 1) {
            $('#roadTransportSection').removeClass('hidden-field');
            $('#horse').val(escapeHtml(mca.horse || ''));
            $('#trailer_1').val(escapeHtml(mca.trailer_1 || ''));
            $('#trailer_2').val(escapeHtml(mca.trailer_2 || ''));
            $('#container').val(escapeHtml(mca.container || ''));
          } 
          else if (transportModeId === 2) {
            $('#airTransportSection').removeClass('hidden-field');
            $('#airway_bill').val(escapeHtml(mca.airway_bill || ''));
            $('#airway_bill_weight').val(mca.airway_bill_weight || '');
            $('#container_air').val(escapeHtml(mca.container || ''));
          }
          else if (transportModeId === 3) {
            $('#railTransportSection').removeClass('hidden-field');
            $('#wagon').val(escapeHtml(mca.wagon || ''));
            $('#horse_rail').val(escapeHtml(mca.horse || ''));
            $('#trailer_1_rail').val(escapeHtml(mca.trailer_1 || ''));
            $('#trailer_2_rail').val(escapeHtml(mca.trailer_2 || ''));
            $('#container_rail').val(escapeHtml(mca.container || ''));
          }
          
          calculateFinancials();
        }
      },
      error: function(xhr, status, error) {
        console.error('❌ Error loading MCA details:', error);
      }
    });
  });

  // ========== DISPLAY QUOTATION ITEMS BY CATEGORY ==========
  function displayQuotationItemsByCategory(categorizedItems, quotation) {
    let html = '';
    quotationItemsData = [];
    
    if (!categorizedItems || categorizedItems.length === 0) {
      html = '<p style="margin: 20px 0; color: #e67e22; text-align: center;"><i class="ti ti-alert-triangle me-1"></i> No items found in quotation.</p>';
      $('#quotationItemsContainer').html(html);
      return;
    }
    
    categorizedItems.forEach(function(category, categoryIndex) {
      if (!category.items || category.items.length === 0) return;
      
      // Category Header
      html += '<div class="quotation-category-header">';
      html += escapeHtml(category.category_header || category.category_name || 'UNCATEGORIZED');
      html += '</div>';
      
      // Category Items Container
      html += '<div class="category-items-display">';
      
      // Table Header
      html += '<div class="quotation-items-row quotation-items-header">';
      html += '<div class="qitem-col qitem-description">DESCRIPTION</div>';
      html += '<div class="qitem-col qitem-unit">UNIT</div>';
      html += '<div class="qitem-col qitem-qty">QTY</div>';
      html += '<div class="qitem-col qitem-rate">TAUX/USD</div>';
      html += '<div class="qitem-col qitem-currency">CURRENCY</div>';
      html += '<div class="qitem-col qitem-tva">TVA</div>';
      html += '<div class="qitem-col qitem-tva-amount">TVA/USD</div>';
      html += '<div class="qitem-col qitem-total">TOTAL USD</div>';
      html += '<div class="qitem-col qitem-actions">ACTION</div>';
      html += '</div>';
      
      // Items Rows - EDITABLE
      category.items.forEach(function(item, itemIndex) {
        const globalItemIndex = quotationItemsData.length;
        quotationItemsData.push(item);
        
        const hasTVA = parseInt(item.has_tva || 0) === 1;
        const currency = escapeHtml(item.currency_short_name || 'USD');
        const quantity = parseFloat(item.quantity || 1);
        const rate = parseFloat(item.taux_usd || item.cost_usd || 0);
        const tvaAmount = parseFloat(item.tva_usd || 0);
        const totalAmount = parseFloat(item.total_usd || 0);
        
        html += '<div class="quotation-items-row editable-row" data-item-index="' + globalItemIndex + '">';
        html += '<div class="qitem-col qitem-description" data-label="Description">' + escapeHtml(item.description_name || 'N/A') + '</div>';
        html += '<div class="qitem-col qitem-unit" data-label="Unit">' + escapeHtml(item.unit_text || item.unit_name || 'Unit') + '</div>';
        html += '<div class="qitem-col qitem-qty" data-label="Quantity"><input type="number" class="item-input qty-input" data-field="quantity" value="' + quantity.toFixed(2) + '" step="0.01" min="0" style="width: 70px;"></div>';
        html += '<div class="qitem-col qitem-rate" data-label="Rate"><input type="number" class="item-input rate-input" data-field="rate" value="' + rate.toFixed(2) + '" step="0.01" min="0" style="width: 80px;"></div>';
        html += '<div class="qitem-col qitem-currency" data-label="Currency">' + currency + '</div>';
        html += '<div class="qitem-col qitem-tva" data-label="TVA"><input type="checkbox" class="item-checkbox tva-checkbox" data-field="has_tva" ' + (hasTVA ? 'checked' : '') + '></div>';
        html += '<div class="qitem-col qitem-tva-amount" data-label="TVA Amount"><input type="number" class="item-input tva-input" data-field="tva_amount" value="' + tvaAmount.toFixed(2) + '" step="0.01" min="0" style="width: 80px;"></div>';
        html += '<div class="qitem-col qitem-total" data-label="Total"><span class="item-total">' + totalAmount.toFixed(2) + '</span></div>';
        html += '<div class="qitem-col qitem-actions" data-label="Action"><button type="button" class="delete-item-btn" data-item-index="' + globalItemIndex + '" title="Delete Item"><i class="ti ti-trash"></i></button></div>';
        html += '</div>';
      });
      
      html += '</div>';
      html += '<div style="margin-bottom: 20px;"></div>';
    });
    
    if (html === '') {
      html = '<p style="margin: 20px 0; color: #e67e22; text-align: center;"><i class="ti ti-alert-triangle me-1"></i> No categories with items found.</p>';
    }
    
    $('#quotationItemsContainer').html(html);
    attachItemEventListeners();
    console.log('✅ All categories displayed. Total items:', quotationItemsData.length);
  }

  // ========== ATTACH EVENT LISTENERS TO EDITABLE ITEMS ==========
  function attachItemEventListeners() {
    $(document).off('input change', '.item-input, .item-checkbox');
    $(document).on('input change', '.item-input, .item-checkbox', function() {
      const $row = $(this).closest('.editable-row');
      const itemIndex = parseInt($row.data('item-index'));
      const field = $(this).data('field');
      const value = $(this).is(':checkbox') ? ($(this).is(':checked') ? 1 : 0) : parseFloat($(this).val()) || 0;
      
      if (quotationItemsData[itemIndex]) {
        if (field === 'quantity') {
          quotationItemsData[itemIndex].quantity = value;
        } else if (field === 'rate') {
          quotationItemsData[itemIndex].taux_usd = value;
          quotationItemsData[itemIndex].cost_usd = value;
        } else if (field === 'has_tva') {
          quotationItemsData[itemIndex].has_tva = value;
        } else if (field === 'tva_amount') {
          quotationItemsData[itemIndex].tva_usd = value;
        }
        recalculateRowTotal($row, itemIndex);
      }
    });
    
    $(document).off('click', '.delete-item-btn');
    $(document).on('click', '.delete-item-btn', function() {
      const itemIndex = parseInt($(this).data('item-index'));
      const $row = $(this).closest('.editable-row');
      
      Swal.fire({
        title: 'Delete Item?',
        text: "Are you sure you want to remove this item?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $row.fadeOut(300, function() {
            $(this).remove();
            quotationItemsData.splice(itemIndex, 1);
            $('.editable-row').each(function(idx) {
              $(this).attr('data-item-index', idx);
              $(this).find('.delete-item-btn').attr('data-item-index', idx);
            });
            recalculateAllTotals();
            Swal.fire({ icon: 'success', title: 'Deleted!', timer: 1500, showConfirmButton: false });
          });
        }
      });
    });
  }

  // ========== RECALCULATE ROW TOTAL ==========
  function recalculateRowTotal($row, itemIndex) {
    const item = quotationItemsData[itemIndex];
    if (!item) return;
    
    const quantity = parseFloat(item.quantity || 1);
    const rate = parseFloat(item.taux_usd || item.cost_usd || 0);
    const tvaAmount = parseFloat(item.tva_usd || 0);
    
    const subtotal = quantity * rate;
    const total = subtotal + tvaAmount;
    
    quotationItemsData[itemIndex].subtotal_usd = subtotal;
    quotationItemsData[itemIndex].total_usd = total;
    
    $row.find('.item-total').text(total.toFixed(2));
    recalculateAllTotals();
  }

  // ========== RECALCULATE ALL TOTALS ==========
  function recalculateAllTotals() {
    let totalExclTVA = 0;
    let totalTVA = 0;
    
    quotationItemsData.forEach(item => {
      const quantity = parseFloat(item.quantity || 1);
      const rate = parseFloat(item.taux_usd || item.cost_usd || 0);
      const subtotal = quantity * rate;
      const tva = parseFloat(item.tva_usd || 0);
      
      totalExclTVA += subtotal;
      totalTVA += tva;
    });
    
    const grandTotal = totalExclTVA + totalTVA;
    updateSummaryTotals(totalExclTVA, totalTVA, grandTotal);
  }

  function clearQuotationItems() {
    quotationItemsData = [];
    quotationData = null;
    $('#quotationInfoBox').hide();
    $('#quotationItemsContainer').html('<div class="quotation-content"><p style="margin: 0;"><i class="ti ti-info-circle me-1"></i> Select a quotation from the dropdown above</p></div>');
    updateSummaryTotals(0, 0, 0);
  }

  function clearMCAFields() {
    $('#kind_id, #type_of_goods_id, #transport_mode_id').val('');
    $('#kind, #type_of_goods, #transport_mode').val('');
    $('#fob_usd, #fret_usd, #assurance_usd, #autres_charges_usd, #poids_kg').val('');
    $('#horse, #trailer_1, #trailer_2, #container').val('');
    $('#wagon, #horse_rail, #trailer_1_rail, #trailer_2_rail, #container_rail').val('');
    $('#airway_bill, #airway_bill_weight, #container_air').val('');
    $('#facture_pfi_no, #po_ref, #bivac_inspection, #tariff_code_client').val('');
    $('#declaration_no, #declaration_date').val('');
    $('#liquidation_no, #liquidation_date').val('');
    $('#quittance_no, #quittance_date').val('');
    $('#dispatch_deliver_date').val('');
    $('#produit').val('Default Commodity');
    $('#roadTransportSection, #railTransportSection, #airTransportSection').addClass('hidden-field');
    calculateFinancials();
  }

  // ========== FINANCIAL CALCULATIONS ==========
  $(document).on('input change', '.financial-calc-trigger', calculateFinancials);

  function calculateFinancials() {
    const fob = sanitizeNumber($('#fob_usd').val());
    const fret = sanitizeNumber($('#fret_usd').val());
    const assurance = sanitizeNumber($('#assurance_usd').val());
    const autresCharges = sanitizeNumber($('#autres_charges_usd').val());
    const rateCDF = sanitizeNumber($('#rate_cdf_inv').val());
    
    const cifUSD = fob + fret + assurance + autresCharges;
    $('#cif_usd').val(cifUSD.toFixed(2));
    
    const cifCDF = cifUSD * rateCDF;
    $('#cif_cdf').val(cifCDF.toFixed(2));
    
    const totalDuty = cifCDF * 0.10;
    $('#total_duty_cdf').val(totalDuty.toFixed(0));
    
    recalculateAllTotals();
  }
  
  function updateSummaryTotals(subTotal, vatAmount, totalAmount) {
    const rateCDF = sanitizeNumber($('#rate_cdf_inv').val()) || 2500;
    const equivalentCDF = totalAmount * rateCDF;
    
    $('#totalExclTVA').text('$' + subTotal.toFixed(2));
    $('#tvaAmount').text('$' + vatAmount.toFixed(2));
    $('#grandTotal').text('$' + totalAmount.toFixed(2));
    $('#equivalentCDF').text(equivalentCDF.toFixed(2) + ' CDF');
  }

  // ========== FORM VALIDATION ==========
  function validateForm() {
    $('.form-control, .form-select').removeClass('is-invalid');
    $('.invalid-feedback').text('').hide();
    
    let errors = [];
    const required = [
      { id: 'subscriber_id', label: 'Subscriber' },
      { id: 'license_id', label: 'License Number' },
      { id: 'mca_id', label: 'MCA Reference' },
      { id: 'invoice_ref', label: 'Invoice Reference' }
    ];

    required.forEach(field => {
      if (!$(`#${field.id}`).val()) {
        $(`#${field.id}`).addClass('is-invalid');
        $(`#${field.id}_error`).text(`${field.label} is required`).show();
        errors.push(`${field.label} is required`);
      }
    });

    return { isValid: errors.length === 0, errors };
  }

  // ========== FORM RESET ==========
  function resetForm() {
    $('#invoiceForm')[0].reset();
    $('.form-control, .form-select').removeClass('is-invalid');
    $('.invalid-feedback').text('').hide();
    $('#invoice_id').val('');
    $('#formAction').val('insert');
    $('#formTitle').text('Add New Import Invoice');
    $('#submitBtnText').text('Save Invoice');
    $('#quotationSelector').html('<option value="">-- Select Subscriber First --</option>');
    clearMCAFields();
    clearQuotationItems();
    $('#rate_cdf_inv, #rate_cdf_usd_bcc').val('2500.00');
    $('#produit').val('Default Commodity');
    $('#invoice_ref').val('').attr('placeholder', 'Select Subscriber First');
    $('#bankContainer').html('<div class="bank-alert"><i class="ti ti-info-circle me-1"></i>Select a subscriber to load banks</div>');
    updateSummaryTotals(0, 0, 0);
    $('#collapseInvoice').collapse('hide');
  }

  // ========== FORM SUBMIT ==========
  $('#invoiceForm').on('submit', function (e) {
    e.preventDefault();
    
    const validation = validateForm();
    if (!validation.isValid) {
      Swal.fire({
        icon: 'error', 
        title: 'Validation Error', 
        html: '<ul style="text-align:left;"><li>' + validation.errors.join('</li><li>') + '</li></ul>'
      });
      $('#collapseInvoice').collapse('show');
      return;
    }

    // Sync transport fields
    const transportModeId = parseInt($('#transport_mode_id').val());
    if (transportModeId === 3) {
      $('input[name="horse"]').val($('#horse_rail').val());
      $('input[name="trailer_1"]').val($('#trailer_1_rail').val());
      $('input[name="trailer_2"]').val($('#trailer_2_rail').val());
      $('input[name="container"]').val($('#container_rail').val());
    } else if (transportModeId === 2) {
      $('input[name="container"]').val($('#container_air').val());
    }

    // Serialize quotation items
    $('#quotation_items').val(JSON.stringify(quotationItemsData));

    const submitBtn = $('#submitBtn');
    const originalText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Saving...');

    const formData = new FormData(this);
    formData.set('csrf_token', csrfToken);

    $.ajax({
      url: CONTROLLER_URL + '/crudData/' + $('#formAction').val(),
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (res) {
        submitBtn.prop('disabled', false).html(originalText);
        
        if (res.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: res.message, timer: 1500, showConfirmButton: false });
          resetForm();
          if (invoicesTable) invoicesTable.ajax.reload(null, false);
          updateStatistics();
        } else {
          Swal.fire({ icon: 'error', title: 'Error!', html: res.message });
        }
      },
      error: function (xhr, status, error) {
        submitBtn.prop('disabled', false).html(originalText);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to save invoice' });
      }
    });
  });

  $('#cancelBtn').on('click', (e) => { e.preventDefault(); resetForm(); });

  // ========== DATATABLE INITIALIZATION ==========
  function initDataTable() {
    if ($.fn.DataTable.isDataTable('#invoicesTable')) {
      $('#invoicesTable').DataTable().destroy();
    }

    invoicesTable = $('#invoicesTable').DataTable({
      processing: true,
      serverSide: true,
      scrollX: true,
      searching: false,
      ajax: { 
        url: CONTROLLER_URL + '/crudData/listing',
        type: 'GET',
        data: function(d) { 
          d.filter = currentFilter;
          d.search = { value: $('#customSearchBox').val() };
        },
        dataSrc: function(json) {
          return json.data || [];
        }
      },
      columns: [
        { data: 'id' },
        { data: 'invoice_ref', render: data => escapeHtml(data || '') },
        { data: 'subscriber_name', render: data => escapeHtml(data || '') },
        { data: 'mca_ref', render: data => escapeHtml(data || '') },
        { data: 'cif_usd', render: data => data ? '$' + parseFloat(data).toFixed(2) : '$0.00' },
        { data: 'total_duty_cdf', render: data => data ? parseFloat(data).toFixed(0) + ' CDF' : '0 CDF' },
        { 
          data: 'status', 
          render: data => {
            const status = (data || 'PENDING').toLowerCase();
            return `<span class="status-badge status-${status}">${escapeHtml(data || 'PENDING')}</span>`;
          }
        },
        { 
          data: null, 
          orderable: false, 
          searchable: false, 
          render: (data, type, row) => `
            <button class="btn btn-sm btn-pdf pdfBtn" data-id="${row.id}" title="View PDF">
              <i class="ti ti-file-type-pdf"></i>
            </button>
            <button class="btn btn-sm btn-primary editBtn" data-id="${row.id}" title="Edit">
              <i class="ti ti-edit"></i>
            </button>
            <button class="btn btn-sm btn-export exportBtn" data-id="${row.id}" title="Export to Excel">
              <i class="ti ti-file-spreadsheet"></i>
            </button>
            <button class="btn btn-sm btn-danger deleteBtn" data-id="${row.id}" title="Delete">
              <i class="ti ti-trash"></i>
            </button>
          `
        }
      ],
      order: [[0, 'desc']],
      pageLength: 25,
      dom: 'rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      drawCallback: function() { updateStatistics(); }
    });

    $('#customSearchBox').on('keyup', function() {
      invoicesTable.ajax.reload();
    });
  }

  // ========== STATISTICS UPDATE ==========
  function updateStatistics() {
    $.ajax({
      url: CONTROLLER_URL + '/crudData/statistics',
      method: 'GET',
      dataType: 'json',
      success: function(res) {
        if (res.success && res.data) {
          $('#totalInvoices').text(res.data.total_invoices || 0);
          $('#totalCompleted').text(res.data.completed_invoices || 0);
          $('#totalPending').text(res.data.pending_invoices || 0);
          $('#totalDraft').text(res.data.draft_invoices || 0);
        }
      }
    });
  }

  // ========== ACTION BUTTONS ==========
  $(document).on('click', '.pdfBtn', function() {
    const id = $(this).data('id');
    if (!id) return;
    window.open(CONTROLLER_URL + '/crudData/viewPDF?id=' + id, '_blank');
  });
  
  $(document).on('click', '.exportBtn', function() {
    const id = $(this).data('id');
    if (!id) return;
    window.location.href = CONTROLLER_URL + '/crudData/exportInvoice?id=' + id;
    Swal.fire({icon: 'success', title: 'Exporting...', timer: 1500, showConfirmButton: false});
  });

  $(document).on('click', '.editBtn', function() {
    const id = $(this).data('id');
    $.ajax({
      url: CONTROLLER_URL + '/crudData/getInvoice',
      method: 'GET',
      data: { id: id },
      dataType: 'json',
      success: function(res) {
        if (res.success && res.data) {
          const inv = res.data;
          $('#invoice_id').val(inv.id);
          $('#formAction').val('update');
          $('#formTitle').text('Edit Import Invoice');
          $('#submitBtnText').text('Update Invoice');
          $('#subscriber_id').val(inv.subscriber_id).trigger('change');
          setTimeout(() => {
            $('#license_id').val(inv.license_id).trigger('change');
            setTimeout(() => {
              $('#mca_id').val(inv.mca_id).trigger('change');
              Object.keys(inv).forEach(key => {
                const $field = $(`#${key}`);
                if ($field.length && inv[key] !== null) $field.val(inv[key]);
              });
              
              if (res.items && res.items.length > 0) {
                quotationItemsData = res.items;
              }
              
              calculateFinancials();
              $('#collapseInvoice').collapse('show');
              $('html, body').animate({ scrollTop: $('#invoiceForm').offset().top - 100 }, 500);
            }, 800);
          }, 500);
        }
      }
    });
  });

  $(document).on('click', '.deleteBtn', function() {
    const id = $(this).data('id');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: CONTROLLER_URL + '/crudData/deletion',
          method: 'POST',
          data: { id: id, csrf_token: csrfToken },
          dataType: 'json',
          success: function(res) {
            if (res.success) {
              Swal.fire({icon: 'success', title: 'Deleted!', text: res.message, timer: 1500, showConfirmButton: false});
              invoicesTable.ajax.reload(null, false);
              updateStatistics();
            } else {
              Swal.fire('Error', res.message, 'error');
            }
          }
        });
      }
    });
  });

  // ========== INITIALIZE ==========
  initDataTable();
  updateStatistics();
  $('#rate_cdf_inv, #rate_cdf_usd_bcc').val('2500.00');
  $('#produit').val('Default Commodity');
  updateSummaryTotals(0, 0, 0);
  $('.filter-card[data-filter="all"]').addClass('active-filter');
  
  console.log('✅ Import Invoice System Ready!');
});
</script>