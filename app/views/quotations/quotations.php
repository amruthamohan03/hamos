<?php
/**
 * Quotations Management View
 * Reference Format: Client-Kind-Transport-Type of Goods
 * Dynamic columns: IMPORT DEFINITIVE + Customs = CDF fields, Others = USD fields
 * UNIT field now populated from unit_master_t table
 */

// Extract data from controller
$clients = $data['clients'] ?? [];
$currencies = $data['currencies'] ?? [];
$kinds = $data['kinds'] ?? [];
$transport_modes = $data['transport_modes'] ?? [];
$goods_types = $data['goods_types'] ?? [];
$units = $data['units'] ?? [];
$categories = $data['categories'] ?? [];
$descriptions = $data['descriptions'] ?? [];
$customsCategoryId = $data['customsCategoryId'] ?? null;
$importDefinitiveKindId = $data['importDefinitiveKindId'] ?? 1;

// Find customs category key
$customsCategoryKey = null;
foreach ($categories as $cat) {
  if (stripos($cat['category_name'], 'CUSTOMS') !== false || stripos($cat['category_name'], 'CLEARANCE') !== false) {
    $customsCategoryKey = strtolower(str_replace(' ', '_', $cat['category_name']));
    break;
  }
}
?>

<link href="<?= BASE_URL ?>/assets/pages/css/client_styles.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
  .form-accordion {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }

  .accordion-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 18px 25px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.3s;
  }

  .accordion-header:hover {
    background: linear-gradient(135deg, #5568d3 0%, #653a8d 100%);
  }

  .accordion-header h4 {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 600;
    display: flex;
    align-items: center;
  }

  .accordion-header h4 i {
    font-size: 1.3rem;
    margin-right: 10px;
  }

  .accordion-toggle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
  }

  .accordion-toggle i {
    transition: transform 0.3s;
    font-size: 1.2rem;
  }

  .accordion-toggle.active i {
    transform: rotate(180deg);
  }

  .accordion-body {
    display: none;
    padding: 30px;
    background: #f8f9fa;
  }

  .accordion-body.show {
    display: block;
    animation: slideDown 0.3s ease-out;
  }

  @keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .form-section {
    background: white;
    padding: 25px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  }

  .section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
    display: flex;
    align-items: center;
  }

  .section-title i {
    margin-right: 8px;
    color: #667eea;
  }

  .category-section {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 14px 20px;
    border-radius: 8px;
    margin-top: 25px;
    margin-bottom: 15px;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  .category-section:first-of-type {
    margin-top: 0;
  }

  .items-container {
    background: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
  }

  .items-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 10px;
    font-weight: 600;
    font-size: 0.75rem;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .items-header .header-col {
    text-align: center;
  }

  .items-header .header-col-lg { flex: 0 0 170px; }
  .items-header .header-col-md { flex: 0 0 115px; }
  .items-header .header-col-sm { flex: 0 0 90px; }
  .items-header .header-spacer { width: 50px; }

  .item-row {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 6px;
    margin-bottom: 8px;
    border: 1px solid #e0e0e0;
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.2s;
  }

  .item-row:hover {
    border-color: #667eea;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.1);
  }

  .item-row .form-select,
  .item-row .form-control {
    font-size: 0.875rem;
    padding: 6px 10px;
    height: 36px;
    border: 1px solid #ced4da;
    transition: border-color 0.2s;
  }

  .item-row .form-select:focus,
  .item-row .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  .item-row .remove-item {
    width: 36px;
    height: 36px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #dc3545;
    border: none;
    border-radius: 4px;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
    flex-shrink: 0;
  }

  .item-row .remove-item:hover {
    background: #c82333;
    transform: scale(1.1);
    box-shadow: 0 2px 6px rgba(220, 53, 69, 0.4);
  }

  .add-item-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-left: 15px;
  }

  .add-item-btn {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 1.5rem;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 6px rgba(40, 167, 69, 0.3);
    flex-shrink: 0;
  }

  .add-item-btn:hover {
    background: linear-gradient(135deg, #218838 0%, #1ba87d 100%);
    transform: scale(1.1) rotate(90deg);
    box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4);
  }

  .summary-box {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 25px;
    border-radius: 10px;
    border: 2px solid #dee2e6;
    margin-top: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }

  .summary-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
  }

  .summary-title i {
    margin-right: 10px;
    color: #667eea;
    font-size: 1.3rem;
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #dee2e6;
  }

  .summary-row:last-child {
    border-bottom: 3px solid #667eea;
    padding-top: 15px;
    margin-top: 10px;
  }

  .summary-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.95rem;
  }

  .summary-value {
    font-weight: 700;
    color: #212529;
    font-size: 1.1rem;
    text-align: right;
    min-width: 150px;
  }

  .summary-row:last-child .summary-label {
    color: #667eea;
    font-size: 1.15rem;
  }

  .summary-row:last-child .summary-value {
    color: #667eea;
    font-size: 1.4rem;
  }

  .field-col { flex: 1; min-width: 0; }
  .field-col-sm { flex: 0 0 90px; }
  .field-col-md { flex: 0 0 115px; }
  .field-col-lg { flex: 0 0 170px; }

  .compact-header .form-label {
    font-size: 0.85rem;
    margin-bottom: 5px;
    font-weight: 600;
    color: #495057;
  }

  .compact-header .form-control,
  .compact-header .form-select {
    font-size: 0.9rem;
    height: 38px;
  }

  .compact-header .form-control:focus,
  .compact-header .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  .ref-display {
    background: #e7f3ff;
    border: 2px solid #667eea;
    color: #667eea;
    font-weight: 700;
    text-align: center;
    font-size: 0.88rem;
  }

  .ref-display:focus {
    background: #e7f3ff;
    border-color: #667eea;
  }

  .action-buttons {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #e9ecef;
  }

  .btn-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 12px 35px;
    font-weight: 600;
    transition: all 0.3s;
    box-shadow: 0 3px 6px rgba(102, 126, 234, 0.3);
  }

  .btn-save:hover {
    background: linear-gradient(135deg, #5568d3 0%, #653a8d 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(102, 126, 234, 0.4);
  }

  .btn-cancel {
    background: #6c757d;
    border: none;
    padding: 12px 35px;
    font-weight: 600;
    transition: all 0.3s;
  }

  .btn-cancel:hover {
    background: #5a6268;
    transform: translateY(-2px);
  }

  .items-section {
    display: none;
  }

  .items-section.active {
    display: block;
  }

  /* ============================================ */
  /* SHOW/HIDE COLUMNS FOR IMPORT DEFINITIVE     */
  /* ============================================ */
  
  /* Standard USD columns - visible by default */
  .std-col {
    display: flex;
  }
  
  /* CDF columns - hidden by default */
  .cdf-col {
    display: none;
  }
  
  /* Standard headers - visible by default (flex for horizontal layout) */
  .std-header {
    display: flex !important;
  }
  
  /* CDF headers - hidden by default */
  .cdf-header {
    display: none !important;
  }
  
  /* When IMPORT DEFINITIVE mode is active */
  .import-definitive-mode .customs-category .std-col {
    display: none !important;
  }
  
  .import-definitive-mode .customs-category .cdf-col {
    display: flex !important;
  }
  
  .import-definitive-mode .customs-category .std-header {
    display: none !important;
  }
  
  .import-definitive-mode .customs-category .cdf-header {
    display: flex !important;
  }

  /* Summary CDF section */
  .summary-cdf {
    display: none;
  }
  
  .import-definitive-mode .summary-cdf {
    display: block;
  }

  @media (max-width: 768px) {
    .compact-header .col-md-2,
    .compact-header .col-md-3,
    .compact-header .col-md-4 {
      flex: 0 0 100%;
      max-width: 100%;
    }

    .items-header {
      display: none;
    }

    .item-row {
      flex-wrap: wrap;
    }

    .summary-row {
      flex-direction: column;
      align-items: flex-start;
      gap: 5px;
    }

    .summary-value {
      text-align: left;
    }
  }
</style>

<div class="page-content">
  <div class="page-container">
    <div class="row">
      <div class="col-12">
        
        <!-- Quotation Form Accordion -->
        <div class="form-accordion">
          <div class="accordion-header" id="formAccordionHeader">
            <h4>
              <i class="ti ti-file-invoice"></i>
              <span id="formTitle">Add New Quotation</span>
            </h4>
            <div class="accordion-toggle" id="accordionToggle">
              <i class="ti ti-chevron-down"></i>
            </div>
          </div>

          <div class="accordion-body" id="formAccordionBody">
            <form id="quotationForm" method="post" novalidate>
              <input type="hidden" name="quotation_id" id="quotation_id" value="">
              <input type="hidden" name="action" id="formAction" value="insert">
              <input type="hidden" name="sub_total" id="sub_total" value="0.00">
              <input type="hidden" name="vat_amount" id="vat_amount" value="0.00">
              <input type="hidden" name="arsp_amount" id="arsp_amount" value="0.00">
              <input type="hidden" name="total_amount" id="total_amount" value="0.00">
              <input type="hidden" name="sub_total_cdf" id="sub_total_cdf" value="0.00">
              <input type="hidden" name="vat_amount_cdf" id="vat_amount_cdf" value="0.00">
              <input type="hidden" name="total_amount_cdf" id="total_amount_cdf" value="0.00">

              <!-- Header Section -->
              <div class="form-section">
                <div class="section-title">
                  <i class="ti ti-info-circle"></i>
                  Quotation Header Information
                </div>

                <div class="compact-header">
                  <!-- First Row -->
                  <div class="row g-3 mb-3">
                    <div class="col-md-3">
                      <label class="form-label">Client <span class="text-danger">*</span></label>
                      <select name="client_id" id="client_id" class="form-select form-select-sm" required>
                        <option value="">Select Client</option>
                        <?php if (!empty($clients)): ?>
                          <?php foreach ($clients as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['short_name']) ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                      <div class="invalid-feedback">Client is required</div>
                    </div>

                    <div class="col-md-4">
                      <label class="form-label">Quotation Ref <span class="text-danger">*</span></label>
                      <input type="text" name="quotation_ref" id="quotation_ref" class="form-control form-control-sm ref-display" required readonly placeholder="Auto-generated">
                      <div class="invalid-feedback">Reference is required</div>
                    </div>

                    <div class="col-md-2">
                      <label class="form-label">Date <span class="text-danger">*</span></label>
                      <input type="date" name="quotation_date" id="quotation_date" class="form-control form-control-sm" required>
                      <div class="invalid-feedback">Date is required</div>
                    </div>

                    <div class="col-md-3">
                      <label class="form-label">Kind <span class="text-danger">*</span></label>
                      <select name="kind_id" id="kind_id" class="form-select form-select-sm" required>
                        <option value="">Select Kind</option>
                        <?php if (!empty($kinds)): ?>
                          <?php foreach ($kinds as $k): ?>
                            <option value="<?= $k['id'] ?>" data-name="<?= htmlspecialchars($k['kind_name']) ?>">
                              <?= htmlspecialchars($k['kind_name']) ?>
                            </option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                      <div class="invalid-feedback">Kind is required</div>
                    </div>
                  </div>

                  <!-- Second Row -->
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">Transport <span class="text-danger">*</span></label>
                      <select name="transport_mode_id" id="transport_mode_id" class="form-select form-select-sm" required>
                        <option value="">Select Transport</option>
                        <?php if (!empty($transport_modes)): ?>
                          <?php foreach ($transport_modes as $tm): ?>
                            <option value="<?= $tm['id'] ?>"><?= htmlspecialchars($tm['transport_mode_name']) ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                      <div class="invalid-feedback">Transport is required</div>
                    </div>

                    <div class="col-md-4">
                      <label class="form-label">Type of Goods <span class="text-danger">*</span></label>
                      <select name="goods_type_id" id="goods_type_id" class="form-select form-select-sm" required>
                        <option value="">Select Type</option>
                        <?php if (!empty($goods_types)): ?>
                          <?php foreach ($goods_types as $gt): ?>
                            <option value="<?= $gt['id'] ?>"><?= htmlspecialchars($gt['goods_type']) ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                      <div class="invalid-feedback">Type of goods is required</div>
                    </div>

                    <div class="col-md-4">
                      <label class="form-label">ARSP <span class="text-danger">*</span></label>
                      <select name="arsp" id="arsp" class="form-select form-select-sm" required>
                        <option value="">Select ARSP Status</option>
                        <option value="Enabled">Enabled</option>
                        <option value="Disabled" selected>Disabled</option>
                      </select>
                      <div class="invalid-feedback">ARSP is required</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- ============================================ -->
              <!-- SECTION FOR ED (EXPORT DECLARATION) -->
              <!-- ============================================ -->
              <div id="edItemsSection" class="items-section form-section">
                <div class="section-title">
                  <i class="ti ti-list-details"></i>
                  Export Items (ED)
                </div>

                <?php if (!empty($categories)): ?>
                  <?php foreach ($categories as $category): ?>
                    <?php 
                      $categoryId = $category['id'];
                      $categoryName = $category['category_name'];
                      $categoryHeader = $category['category_header'];
                      $categoryKey = strtolower(str_replace(' ', '_', $categoryName));
                      $containerId = 'ed_' . $categoryKey . 'Container';
                    ?>
                    
                    <div class="category-section">
                      <?= htmlspecialchars($categoryHeader) ?>
                    </div>
                    
                    <div class="items-container">
                      <div class="items-header">
                        <div class="header-col header-col-lg">DESCRIPTION</div>
                        <div class="header-col header-col-sm">UNIT</div>
                        <div class="header-col header-col-md">COST/USD</div>
                        <div class="header-col header-col-md">SUBTOTAL USD</div>
                        <div class="header-col header-col-sm">CURRENCY</div>
                        <div class="header-col header-col-sm">TVA</div>
                        <div class="header-col header-col-md">TVA-16</div>
                        <div class="header-col header-col-md">TOTAL EN USD</div>
                        <div class="header-spacer"></div>
                      </div>

                      <div id="<?= $containerId ?>" data-category="<?= $categoryKey ?>"></div>
                      
                      <div class="add-item-row">
                        <button type="button" class="add-item-btn ed-add-btn" data-category="<?= $categoryKey ?>" title="Add Item">
                          <i class="ti ti-plus"></i>
                        </button>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>

              <!-- ============================================ -->
              <!-- SECTION FOR IMPORT ITEMS (Standard + IMPORT DEFINITIVE) -->
              <!-- ============================================ -->
              <div id="importItemsSection" class="items-section form-section">
                <div class="section-title">
                  <i class="ti ti-list-details"></i>
                  Quotation Items
                </div>

                <?php if (!empty($categories)): ?>
                  <?php foreach ($categories as $category): ?>
                    <?php 
                      $categoryId = $category['id'];
                      $categoryName = $category['category_name'];
                      $categoryHeader = $category['category_header'];
                      $categoryKey = strtolower(str_replace(' ', '_', $categoryName));
                      $containerId = 'import_' . $categoryKey . 'Container';
                      
                      // Check if this is the Customs category
                      $isCustomsCategory = (stripos($categoryName, 'CUSTOMS') !== false || stripos($categoryName, 'CLEARANCE') !== false);
                      $customsClass = $isCustomsCategory ? 'customs-category' : '';
                    ?>
                    
                    <div class="category-section">
                      <?= htmlspecialchars($categoryHeader) ?>
                    </div>
                    
                    <div class="items-container <?= $customsClass ?>" data-category-id="<?= $categoryId ?>" data-is-customs="<?= $isCustomsCategory ? '1' : '0' ?>">
                      
                      <!-- STANDARD HEADERS (for non-IMPORT DEFINITIVE or non-Customs) -->
                      <div class="items-header std-header">
                        <div class="header-col header-col-lg">DESCRIPTION</div>
                        <div class="header-col header-col-sm">UNIT</div>
                        <div class="header-col header-col-sm">QTY</div>
                        <div class="header-col header-col-md">TAUX/USD</div>
                        <div class="header-col header-col-sm">CURRENCY</div>
                        <div class="header-col header-col-sm">TVA</div>
                        <div class="header-col header-col-md">TVA/USD</div>
                        <div class="header-col header-col-md">TOTAL EN USD</div>
                        <div class="header-spacer"></div>
                      </div>
                      
                      <!-- CDF HEADERS (for IMPORT DEFINITIVE + Customs) -->
                      <?php if ($isCustomsCategory): ?>
                      <div class="items-header cdf-header">
                        <div class="header-col header-col-lg">DESCRIPTION</div>
                        <div class="header-col header-col-sm">UNIT</div>
                        <div class="header-col header-col-md">CIF/Split</div>
                        <div class="header-col header-col-sm">%</div>
                        <div class="header-col header-col-md">Rate/CDF</div>
                        <div class="header-col header-col-md">VAT/CDF</div>
                        <div class="header-col header-col-md">Total/CDF</div>
                        <div class="header-spacer"></div>
                      </div>
                      <?php endif; ?>

                      <div id="<?= $containerId ?>" data-category="<?= $categoryKey ?>" data-is-customs="<?= $isCustomsCategory ? '1' : '0' ?>"></div>
                      
                      <div class="add-item-row">
                        <button type="button" class="add-item-btn import-add-btn" data-category="<?= $categoryKey ?>" data-is-customs="<?= $isCustomsCategory ? '1' : '0' ?>" title="Add Item">
                          <i class="ti ti-plus"></i>
                        </button>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="alert alert-warning">
                    <i class="ti ti-alert-circle me-2"></i>
                    No categories found. Please add categories in the master data.
                  </div>
                <?php endif; ?>
              </div>

              <!-- Summary Section -->
              <div class="summary-box">
                <div class="summary-title">
                  <i class="ti ti-calculator"></i>
                  Summary
                </div>
                
                <!-- USD Summary -->
                <div class="summary-row">
                  <span class="summary-label">Sub-Total (USD):</span>
                  <span class="summary-value" id="display_sub_total">0.00</span>
                </div>
                <div class="summary-row">
                  <span class="summary-label">VAT (16%):</span>
                  <span class="summary-value" id="display_vat_amount">0.00</span>
                </div>
                <div class="summary-row">
                  <span class="summary-label">ARSP (1.2%):</span>
                  <span class="summary-value" id="display_arsp_amount">0.00</span>
                </div>
                <div class="summary-row">
                  <span class="summary-label">TOTAL EN USD:</span>
                  <span class="summary-value" id="display_total_amount">0.00</span>
                </div>
                
                <!-- CDF Summary (shown only for IMPORT DEFINITIVE) -->
                <div class="summary-cdf" style="margin-top: 20px; padding-top: 15px; border-top: 2px dashed #28a745;">
                  <div class="summary-row">
                    <span class="summary-label" style="color: #28a745;">Sub-Total (CDF):</span>
                    <span class="summary-value" id="display_sub_total_cdf" style="color: #28a745;">0.00</span>
                  </div>
                  <div class="summary-row">
                    <span class="summary-label" style="color: #28a745;">VAT (CDF):</span>
                    <span class="summary-value" id="display_vat_amount_cdf" style="color: #28a745;">0.00</span>
                  </div>
                  <div class="summary-row">
                    <span class="summary-label" style="color: #28a745;">TOTAL EN CDF:</span>
                    <span class="summary-value" id="display_total_amount_cdf" style="color: #28a745; font-size: 1.3rem;">0.00</span>
                  </div>
                </div>
              </div>

              <div class="action-buttons">
                <button type="submit" class="btn btn-primary btn-lg btn-save" id="submitBtn">
                  <i class="ti ti-device-floppy me-2"></i> 
                  <span id="submitBtnText">Save Quotation</span>
                </button>
                <button type="reset" class="btn btn-secondary btn-lg btn-cancel" id="cancelBtn">
                  <i class="ti ti-x me-2"></i> Cancel
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Quotations DataTable Card -->
        <div class="card shadow-sm">
          <div class="card-header border-bottom border-dashed" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <h4 class="header-title mb-0" style="color: #495057; font-weight: 600;">
              <i class="ti ti-list me-2"></i> Quotations List
            </h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="quotationsTable" class="table table-hover table-striped w-100">
                <thead class="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Ref</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Kind</th>
                    <th>Total USD</th>
                    <th>Total CDF</th>
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
</div>

<?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>

<script>
$(function () {
  'use strict';

  let quotationsTable;
  let edItemIndexes = {};
  let importItemIndexes = {};
  let currentKind = '';
  let isImportDefinitiveMode = false;
  
  // Configuration from PHP
  const IMPORT_DEFINITIVE_KIND_ID = <?= $importDefinitiveKindId ?>;
  const CUSTOMS_CATEGORY_KEY = '<?= $customsCategoryKey ?? '' ?>';
  
  const categories = <?= json_encode(array_map(function($cat) {
    return strtolower(str_replace(' ', '_', $cat['category_name']));
  }, $categories)) ?>;
  
  const categoryDescriptions = <?= json_encode($descriptions) ?>;
  
  // Units from database
  const units = <?= json_encode($units) ?>;
  
  categories.forEach(function(category) {
    edItemIndexes[category] = 0;
    importItemIndexes[category] = 0;
  });

  function toggleAccordion(show = null) {
    const body = $('#formAccordionBody');
    const toggle = $('#accordionToggle');
    
    if (show === null) {
      body.toggleClass('show');
    } else if (show) {
      body.addClass('show');
    } else {
      body.removeClass('show');
    }
    
    if (body.hasClass('show')) {
      toggle.addClass('active');
    } else {
      toggle.removeClass('active');
    }
  }

  $('#formAccordionHeader').on('click', function() {
    toggleAccordion();
  });

  function init() {
    initDataTable();
    setTodayDate();
    toggleAccordion(false);
  }

  function setTodayDate() {
    const today = new Date().toISOString().split('T')[0];
    $('#quotation_date').val(today);
  }

  function generateQuotationRef() {
    const clientText = $('#client_id option:selected').text().trim();
    const kindText = $('#kind_id option:selected').text().trim();
    const transportText = $('#transport_mode_id option:selected').text().trim();
    const goodsText = $('#goods_type_id option:selected').text().trim();

    if (clientText && clientText !== 'Select Client' && 
        kindText && kindText !== 'Select Kind' && 
        transportText && transportText !== 'Select Transport' && 
        goodsText && goodsText !== 'Select Type') {
      
      const ref = `${clientText}-${kindText}-${transportText}-${goodsText}`;
      $('#quotation_ref').val(ref);
      checkQuotationRefUniqueness(ref);
    } else {
      $('#quotation_ref').val('');
      $('#quotation_ref').removeClass('is-invalid');
    }
  }

  function checkQuotationRefUniqueness(ref) {
    const quotationId = $('#quotation_id').val();
    
    $.ajax({
      url: '<?= APP_URL ?>/quotation/crudData/checkRefUnique',
      method: 'POST',
      data: { quotation_ref: ref, quotation_id: quotationId },
      dataType: 'json',
      success: function(res) {
        if (!res.unique) {
          $('#quotation_ref').addClass('is-invalid');
          $('#quotation_ref').next('.invalid-feedback').remove();
          $('#quotation_ref').after('<div class="invalid-feedback" style="display: block;">' + res.message + '</div>');
        } else {
          $('#quotation_ref').removeClass('is-invalid');
          $('#quotation_ref').next('.invalid-feedback').remove();
        }
      }
    });
  }

  $('#client_id, #kind_id, #transport_mode_id, #goods_type_id').on('change', function() {
    generateQuotationRef();
  });

  // =====================================================
  // KIND CHANGE HANDLER - Toggle Import Definitive Mode
  // =====================================================
  $('#kind_id').on('change', function() {
    const kindId = parseInt($(this).val());
    const selectedKindText = $('#kind_id option:selected').text();
    currentKind = selectedKindText;
    
    const isED = selectedKindText.toUpperCase().includes('EXPORT');
    isImportDefinitiveMode = (kindId === IMPORT_DEFINITIVE_KIND_ID);
    
    // Toggle Import Definitive mode class on form
    if (isImportDefinitiveMode) {
      $('#quotationForm').addClass('import-definitive-mode');
    } else {
      $('#quotationForm').removeClass('import-definitive-mode');
    }
    
    if (isED) {
      $('#edItemsSection').addClass('active');
      $('#importItemsSection').removeClass('active');
      
      if ($('#edItemsSection .item-row').length === 0 && categories.length > 0) {
        addEDItem(categories[0]);
      }
    } else {
      $('#importItemsSection').addClass('active');
      $('#edItemsSection').removeClass('active');
      
      // Clear and rebuild items when switching modes
      rebuildImportItems();
    }
    
    calculateTotals();
  });

  function rebuildImportItems() {
    // If no items exist, add one to first category
    if ($('#importItemsSection .item-row').length === 0 && categories.length > 0) {
      addImportItem(categories[0]);
    }
  }

  function getDescriptionsForCategory(category) {
    return categoryDescriptions[category] || [];
  }

  // Check if a category is the Customs category
  function isCustomsCategory(category) {
    return category.toLowerCase().includes('customs') || category.toLowerCase().includes('clearance');
  }

  // Generate unit options HTML
  function getUnitOptionsHtml(selectedUnitId = null) {
    let html = '<option value="">Unit</option>';
    units.forEach(function(unit) {
      const selected = selectedUnitId && selectedUnitId == unit.id ? 'selected' : '';
      const displayText = unit.unit_code || unit.unit_name;
      html += `<option value="${unit.id}" ${selected}>${displayText}</option>`;
    });
    return html;
  }

  // =====================================================
  // ADD ED ITEM (Export)
  // =====================================================
  function addEDItem(category, data = null) {
    const containerId = 'ed_' + category + 'Container';
    const container = $('#' + containerId);
    
    if (container.length === 0) return;
    
    const index = edItemIndexes[category];
    const descriptions = getDescriptionsForCategory(category);
    
    let descriptionOptions = '<option value="">Select Description</option>';
    descriptions.forEach(function(desc) {
      const selected = data && data.description_id == desc.id ? 'selected' : '';
      descriptionOptions += `<option value="${desc.id}" ${selected}>${desc.description_name}</option>`;
    });

    const unitOptions = getUnitOptionsHtml(data ? data.unit_id : null);

    const itemHtml = `
      <div class="item-row ed-item" data-category="${category}" data-index="${index}">
        <div class="field-col-lg">
          <select name="${category}[${index}][description_id]" class="form-select form-select-sm item-description" required>
            ${descriptionOptions}
          </select>
        </div>

        <div class="field-col-sm">
          <select name="${category}[${index}][unit_id]" class="form-select form-select-sm item-unit">
            ${unitOptions}
          </select>
        </div>

        <div class="field-col-md">
          <input type="number" name="${category}[${index}][cost_usd]" class="form-control form-control-sm item-cost-usd text-end" 
                 step="0.01" min="0" value="${data && data.cost_usd ? data.cost_usd : '0.00'}" placeholder="0.00" required>
        </div>

        <div class="field-col-md">
          <input type="number" name="${category}[${index}][subtotal_usd]" class="form-control form-control-sm item-subtotal-usd text-end" 
                 step="0.01" value="${data && data.subtotal_usd ? data.subtotal_usd : '0.00'}" 
                 readonly style="background: #e9ecef;" placeholder="0.00">
        </div>

        <div class="field-col-sm">
          <select name="${category}[${index}][currency_id]" class="form-select form-select-sm item-currency">
            <option value="">CUR</option>
            <?php if (!empty($currencies)): ?>
              <?php foreach ($currencies as $cur): ?>
                <option value="<?= $cur['id'] ?>" ${data && data.currency_id == <?= $cur['id'] ?> ? 'selected' : '<?= $cur['currency_short_name'] == 'USD' ? 'selected' : '' ?>'}><?= htmlspecialchars($cur['currency_short_name']) ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <div class="field-col-sm">
          <select name="${category}[${index}][has_tva]" class="form-select form-select-sm item-has-tva">
            <option value="NO" ${data && data.has_tva == 0 ? 'selected' : ''}>NO</option>
            <option value="YES" ${data && data.has_tva == 1 ? 'selected' : ''}>YES</option>
          </select>
        </div>

        <div class="field-col-md">
          <input type="number" name="${category}[${index}][tva_usd]" class="form-control form-control-sm item-tva-usd text-end" 
                 step="0.01" value="${data && data.tva_usd ? data.tva_usd : '0.00'}" readonly style="background: #e9ecef;">
        </div>

        <div class="field-col-md">
          <input type="number" name="${category}[${index}][total_usd]" class="form-control form-control-sm item-total-usd text-end" 
                 step="0.01" value="${data && data.total_usd ? data.total_usd : '0.00'}" readonly style="background: #fff3cd; font-weight: 600;">
        </div>

        <input type="hidden" name="${category}[${index}][quantity]" value="1.00">

        <button type="button" class="remove-item" title="Remove Item"><i class="ti ti-x"></i></button>
      </div>
    `;

    container.append(itemHtml);
    edItemIndexes[category]++;
    
    if (data) {
      const row = container.find('.item-row').last();
      calculateItemTotal(row);
    }
    
    calculateTotals();
  }

  // =====================================================
  // ADD IMPORT ITEM (Standard + Import Definitive)
  // =====================================================
  function addImportItem(category, data = null) {
    const containerId = 'import_' + category + 'Container';
    const container = $('#' + containerId);
    
    if (container.length === 0) return;
    
    const index = importItemIndexes[category];
    const descriptions = getDescriptionsForCategory(category);
    const isCustCat = isCustomsCategory(category);
    
    let descriptionOptions = '<option value="">Select Description</option>';
    descriptions.forEach(function(desc) {
      const selected = data && data.description_id == desc.id ? 'selected' : '';
      descriptionOptions += `<option value="${desc.id}" ${selected}>${desc.description_name}</option>`;
    });

    const unitOptions = getUnitOptionsHtml(data ? data.unit_id : null);

    // Build the item row with BOTH standard and CDF columns
    // CSS will show/hide based on import-definitive-mode class
    
    let itemHtml = `
      <div class="item-row import-item ${isCustCat ? 'customs-item' : ''}" data-category="${category}" data-index="${index}" data-is-customs="${isCustCat ? '1' : '0'}">
        
        <!-- DESCRIPTION (Always visible) -->
        <div class="field-col-lg">
          <select name="${category}[${index}][description_id]" class="form-select form-select-sm item-description" required>
            ${descriptionOptions}
          </select>
        </div>

        <!-- UNIT SELECT (Always visible) -->
        <div class="field-col-sm">
          <select name="${category}[${index}][unit_id]" class="form-select form-select-sm item-unit">
            ${unitOptions}
          </select>
        </div>
    `;
    
    // ========== STANDARD COLUMNS (show when NOT Import Definitive OR not Customs) ==========
    if (isCustCat) {
      itemHtml += `
        <!-- QTY (Standard) -->
        <div class="field-col-sm std-col">
          <input type="number" name="${category}[${index}][quantity]" class="form-control form-control-sm item-quantity text-end" 
                 step="0.01" min="0.01" value="${data && data.quantity ? data.quantity : '1.00'}" placeholder="1.00">
        </div>

        <!-- TAUX/USD (Standard) -->
        <div class="field-col-md std-col">
          <input type="number" name="${category}[${index}][taux_usd]" class="form-control form-control-sm item-taux-usd text-end" 
                 step="0.01" min="0" value="${data && data.taux_usd ? data.taux_usd : '0.00'}" placeholder="0.00">
        </div>

        <!-- CURRENCY (Standard) -->
        <div class="field-col-sm std-col">
          <select name="${category}[${index}][currency_id]" class="form-select form-select-sm item-currency">
            <option value="">CUR</option>
            <?php if (!empty($currencies)): ?>
              <?php foreach ($currencies as $cur): ?>
                <option value="<?= $cur['id'] ?>" ${data && data.currency_id == <?= $cur['id'] ?> ? 'selected' : '<?= $cur['currency_short_name'] == 'USD' ? 'selected' : '' ?>'}><?= htmlspecialchars($cur['currency_short_name']) ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <!-- TVA (Standard) -->
        <div class="field-col-sm std-col">
          <select name="${category}[${index}][has_tva]" class="form-select form-select-sm item-has-tva">
            <option value="NO" ${data && data.has_tva == 0 ? 'selected' : ''}>NO</option>
            <option value="YES" ${data && data.has_tva == 1 ? 'selected' : ''}>YES</option>
          </select>
        </div>

        <!-- TVA/USD (Standard) -->
        <div class="field-col-md std-col">
          <input type="number" name="${category}[${index}][tva_usd]" class="form-control form-control-sm item-tva-usd text-end" 
                 step="0.01" value="${data && data.tva_usd ? data.tva_usd : '0.00'}" readonly style="background: #e9ecef;">
        </div>

        <!-- TOTAL EN USD (Standard) -->
        <div class="field-col-md std-col">
          <input type="number" name="${category}[${index}][total_usd]" class="form-control form-control-sm item-total-usd text-end" 
                 step="0.01" value="${data && data.total_usd ? data.total_usd : '0.00'}" readonly style="background: #fff3cd; font-weight: 600;">
        </div>
      `;
      
      // ========== CDF COLUMNS (show when Import Definitive AND Customs) ==========
      itemHtml += `
        <!-- CIF.Split (CDF) -->
        <div class="field-col-md cdf-col">
          <input type="number" name="${category}[${index}][cif_split]" class="form-control form-control-sm item-cif-split text-end" 
                 step="0.01" min="0" value="${data && data.cif_split ? data.cif_split : '0.00'}" placeholder="0.00">
        </div>

        <!-- % (CDF) -->
        <div class="field-col-sm cdf-col">
          <input type="number" name="${category}[${index}][percentage]" class="form-control form-control-sm item-percentage text-end" 
                 step="0.0001" min="0" value="${data && data.percentage ? data.percentage : '0.00'}" placeholder="0.00">
        </div>

        <!-- Rate/CDF (CDF) -->
        <div class="field-col-md cdf-col">
          <input type="number" name="${category}[${index}][rate_cdf]" class="form-control form-control-sm item-rate-cdf text-end" 
                 step="0.01" min="0" value="${data && data.rate_cdf ? data.rate_cdf : '0.00'}" placeholder="0.00">
        </div>

        <!-- VAT/CDF (CDF) -->
        <div class="field-col-md cdf-col">
          <input type="number" name="${category}[${index}][vat_cdf]" class="form-control form-control-sm item-vat-cdf text-end" 
                 step="0.01" value="${data && data.vat_cdf ? data.vat_cdf : '0.00'}" readonly style="background: #e9ecef;">
        </div>

        <!-- Total/CDF (CDF) -->
        <div class="field-col-md cdf-col">
          <input type="number" name="${category}[${index}][total_cdf]" class="form-control form-control-sm item-total-cdf text-end" 
                 step="0.01" value="${data && data.total_cdf ? data.total_cdf : '0.00'}" readonly style="background: #d4edda; font-weight: 600;">
        </div>
      `;
    } else {
      // Non-customs category - only standard columns
      itemHtml += `
        <div class="field-col-sm">
          <input type="number" name="${category}[${index}][quantity]" class="form-control form-control-sm item-quantity text-end" 
                 step="0.01" min="0.01" value="${data && data.quantity ? data.quantity : '1.00'}" placeholder="1.00">
        </div>

        <div class="field-col-md">
          <input type="number" name="${category}[${index}][taux_usd]" class="form-control form-control-sm item-taux-usd text-end" 
                 step="0.01" min="0" value="${data && data.taux_usd ? data.taux_usd : '0.00'}" placeholder="0.00">
        </div>

        <div class="field-col-sm">
          <select name="${category}[${index}][currency_id]" class="form-select form-select-sm item-currency">
            <option value="">CUR</option>
            <?php if (!empty($currencies)): ?>
              <?php foreach ($currencies as $cur): ?>
                <option value="<?= $cur['id'] ?>" ${data && data.currency_id == <?= $cur['id'] ?> ? 'selected' : '<?= $cur['currency_short_name'] == 'USD' ? 'selected' : '' ?>'}><?= htmlspecialchars($cur['currency_short_name']) ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <div class="field-col-sm">
          <select name="${category}[${index}][has_tva]" class="form-select form-select-sm item-has-tva">
            <option value="NO" ${data && data.has_tva == 0 ? 'selected' : ''}>NO</option>
            <option value="YES" ${data && data.has_tva == 1 ? 'selected' : ''}>YES</option>
          </select>
        </div>

        <div class="field-col-md">
          <input type="number" name="${category}[${index}][tva_usd]" class="form-control form-control-sm item-tva-usd text-end" 
                 step="0.01" value="${data && data.tva_usd ? data.tva_usd : '0.00'}" readonly style="background: #e9ecef;">
        </div>

        <div class="field-col-md">
          <input type="number" name="${category}[${index}][total_usd]" class="form-control form-control-sm item-total-usd text-end" 
                 step="0.01" value="${data && data.total_usd ? data.total_usd : '0.00'}" readonly style="background: #fff3cd; font-weight: 600;">
        </div>
      `;
    }

    itemHtml += `
        <button type="button" class="remove-item" title="Remove Item"><i class="ti ti-x"></i></button>
      </div>
    `;

    container.append(itemHtml);
    importItemIndexes[category]++;
    
    if (data) {
      const row = container.find('.item-row').last();
      calculateItemTotal(row);
    }
    
    calculateTotals();
  }

  $(document).on('click', '.ed-add-btn', function() {
    addEDItem($(this).data('category'));
  });

  $(document).on('click', '.import-add-btn', function() {
    addImportItem($(this).data('category'));
  });

  $(document).on('click', '.remove-item', function() {
    $(this).closest('.item-row').fadeOut(300, function() {
      $(this).remove();
      calculateTotals();
    });
  });

  // =====================================================
  // CALCULATION HANDLERS
  // =====================================================
  $(document).on('input change', '.item-quantity, .item-taux-usd, .item-cost-usd, .item-has-tva, .item-cif-split, .item-percentage, .item-rate-cdf', function() {
    const row = $(this).closest('.item-row');
    calculateItemTotal(row);
    calculateTotals();
  });

  function calculateItemTotal(row) {
    const isED = row.hasClass('ed-item');
    const isCustomsItem = row.data('is-customs') === '1' || row.data('is-customs') === 1;
    
    // Check if we're in Import Definitive mode AND this is a customs item
    if (isImportDefinitiveMode && isCustomsItem && !isED) {
      // CDF calculation
      const cifSplit = parseFloat(row.find('.item-cif-split').val()) || 0;
      const percentage = parseFloat(row.find('.item-percentage').val()) || 0;
      const rateCdf = parseFloat(row.find('.item-rate-cdf').val()) || 0;
      
      // VAT CDF = Rate * 16%
      const vatCdf = rateCdf * 0.16;
      const totalCdf = rateCdf + vatCdf;
      
      row.find('.item-vat-cdf').val(vatCdf.toFixed(2));
      row.find('.item-total-cdf').val(totalCdf.toFixed(2));
      
      // Clear USD fields
      row.find('.item-tva-usd').val('0.00');
      row.find('.item-total-usd').val('0.00');
    } else if (isED) {
      // ED calculation
      const costUsd = parseFloat(row.find('.item-cost-usd').val()) || 0;
      const hasTva = row.find('.item-has-tva').val();
      
      let subtotal = costUsd;
      let tvaAmount = hasTva === 'YES' ? subtotal * 0.16 : 0;
      let total = subtotal + tvaAmount;
      
      row.find('.item-subtotal-usd').val(subtotal.toFixed(2));
      row.find('.item-tva-usd').val(tvaAmount.toFixed(2));
      row.find('.item-total-usd').val(total.toFixed(2));
    } else {
      // Standard USD calculation
      const quantity = parseFloat(row.find('.item-quantity').val()) || 0;
      const tauxUsd = parseFloat(row.find('.item-taux-usd').val()) || 0;
      const hasTva = row.find('.item-has-tva').val();
      
      let subtotal = quantity * tauxUsd;
      let tvaAmount = hasTva === 'YES' ? subtotal * 0.16 : 0;
      let total = subtotal + tvaAmount;
      
      row.find('.item-tva-usd').val(tvaAmount.toFixed(2));
      row.find('.item-total-usd').val(total.toFixed(2));
    }
  }

  function calculateTotals() {
    let subTotalUsd = 0;
    let vatTotalUsd = 0;
    let subTotalCdf = 0;
    let vatTotalCdf = 0;

    $('.item-row:visible').each(function() {
      const isED = $(this).hasClass('ed-item');
      const isCustomsItem = $(this).data('is-customs') === '1' || $(this).data('is-customs') === 1;
      
      if (isImportDefinitiveMode && isCustomsItem && !isED) {
        // CDF items
        const rateCdf = parseFloat($(this).find('.item-rate-cdf').val()) || 0;
        const vatCdf = parseFloat($(this).find('.item-vat-cdf').val()) || 0;
        
        subTotalCdf += rateCdf;
        vatTotalCdf += vatCdf;
      } else if (isED) {
        // ED items
        const costUsd = parseFloat($(this).find('.item-cost-usd').val()) || 0;
        const tvaUsd = parseFloat($(this).find('.item-tva-usd').val()) || 0;
        
        subTotalUsd += costUsd;
        vatTotalUsd += tvaUsd;
      } else {
        // Standard items
        const quantity = parseFloat($(this).find('.item-quantity').val()) || 0;
        const tauxUsd = parseFloat($(this).find('.item-taux-usd').val()) || 0;
        const tvaUsd = parseFloat($(this).find('.item-tva-usd').val()) || 0;
        
        subTotalUsd += (quantity * tauxUsd);
        vatTotalUsd += tvaUsd;
      }
    });

    // ARSP calculation (only for USD items with TVA)
    let arspAmount = 0;
    const arspStatus = $('#arsp').val();
    
    if (arspStatus === 'Enabled') {
      let tvaItemsTotal = 0;
      
      $('.item-row:visible').each(function() {
        const isCustomsItem = $(this).data('is-customs') === '1' || $(this).data('is-customs') === 1;
        
        // Skip CDF items for ARSP
        if (isImportDefinitiveMode && isCustomsItem) return;
        
        const hasTva = $(this).find('.item-has-tva').val();
        if (hasTva === 'YES') {
          const isED = $(this).hasClass('ed-item');
          if (isED) {
            tvaItemsTotal += parseFloat($(this).find('.item-cost-usd').val()) || 0;
          } else {
            const quantity = parseFloat($(this).find('.item-quantity').val()) || 0;
            const tauxUsd = parseFloat($(this).find('.item-taux-usd').val()) || 0;
            tvaItemsTotal += (quantity * tauxUsd);
          }
        }
      });
      
      arspAmount = tvaItemsTotal * 0.012;
    }
    
    const totalUsd = subTotalUsd + vatTotalUsd + arspAmount;
    const totalCdf = subTotalCdf + vatTotalCdf;

    // Update hidden fields
    $('#sub_total').val(subTotalUsd.toFixed(2));
    $('#vat_amount').val(vatTotalUsd.toFixed(2));
    $('#arsp_amount').val(arspAmount.toFixed(2));
    $('#total_amount').val(totalUsd.toFixed(2));
    $('#sub_total_cdf').val(subTotalCdf.toFixed(2));
    $('#vat_amount_cdf').val(vatTotalCdf.toFixed(2));
    $('#total_amount_cdf').val(totalCdf.toFixed(2));

    // Update display
    $('#display_sub_total').text(subTotalUsd.toFixed(2));
    $('#display_vat_amount').text(vatTotalUsd.toFixed(2));
    $('#display_arsp_amount').text(arspAmount.toFixed(2));
    $('#display_total_amount').text(totalUsd.toFixed(2));
    $('#display_sub_total_cdf').text(subTotalCdf.toFixed(2));
    $('#display_vat_amount_cdf').text(vatTotalCdf.toFixed(2));
    $('#display_total_amount_cdf').text(totalCdf.toFixed(2));
  }

  $('#arsp').on('change', function() {
    calculateTotals();
  });

  // =====================================================
  // FORM SUBMIT
  // =====================================================
  $('#quotationForm').on('submit', function (e) {
    e.preventDefault();

    const form = this;

    if ($('#quotation_ref').hasClass('is-invalid')) {
      Swal.fire({ icon: 'error', title: 'Duplicate Reference', text: 'This quotation reference already exists.', timer: 3500 });
      return false;
    }

    if (!form.checkValidity()) {
      e.stopPropagation();
      form.classList.add('was-validated');
      
      const firstInvalid = form.querySelector(':invalid');
      if (firstInvalid) {
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstInvalid.focus();
      }
      
      Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please fill in all required fields correctly', timer: 2500 });
      return false;
    }

    if ($('.item-row:visible').length === 0) {
      Swal.fire({ icon: 'error', title: 'Validation Error', text: 'At least one item is required', timer: 2000 });
      return false;
    }

    const submitBtn = $('#submitBtn');
    const originalText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Processing...');

    const action = $('#formAction').val();
    const url = action === 'update' 
      ? '<?= APP_URL ?>/quotation/crudData/update' 
      : '<?= APP_URL ?>/quotation/crudData/insertion';

    $.ajax({
      url: url,
      method: 'POST',
      data: new FormData(form),
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (res) {
        submitBtn.prop('disabled', false).html(originalText);

        if (res.success) {
          Swal.fire({ icon: 'success', title: 'Success!', text: res.message, timer: 1500, showConfirmButton: false }).then(() => {
            resetForm();
            toggleAccordion(false);
            quotationsTable.ajax.reload(null, false);
          });
        } else {
          Swal.fire({ icon: 'error', title: 'Error!', html: res.message });
        }
      },
      error: function (xhr) {
        submitBtn.prop('disabled', false).html(originalText);
        Swal.fire({ icon: 'error', title: 'Server Error', html: xhr.responseText || 'An error occurred' });
      }
    });
  });

  function resetForm() {
    $('#quotationForm')[0].reset();
    $('#quotationForm').removeClass('was-validated import-definitive-mode');
    $('#quotation_id').val('');
    $('#formAction').val('insert');
    $('#formTitle').text('Add New Quotation');
    $('#submitBtnText').text('Save Quotation');
    $('#arsp').val('Disabled');
    $('#quotation_ref').removeClass('is-invalid');
    
    categories.forEach(function(category) {
      $('#ed_' + category + 'Container').empty();
      $('#import_' + category + 'Container').empty();
      edItemIndexes[category] = 0;
      importItemIndexes[category] = 0;
    });
    
    $('#edItemsSection, #importItemsSection').removeClass('active');
    
    isImportDefinitiveMode = false;
    currentKind = '';
    
    setTodayDate();
    calculateTotals();
  }

  $('#cancelBtn').on('click', function (e) {
    e.preventDefault();
    
    Swal.fire({
      title: 'Are you sure?',
      text: "All unsaved changes will be lost",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, reset it!'
    }).then((result) => {
      if (result.isConfirmed) {
        resetForm();
        toggleAccordion(false);
      }
    });
  });

  // =====================================================
  // DATATABLE
  // =====================================================
  function initDataTable() {
    quotationsTable = $('#quotationsTable').DataTable({
      ajax: {
        url: '<?= APP_URL ?>/quotation/crudData/listing',
        type: 'GET',
        dataSrc: 'data'
      },
      columns: [
        { data: 'id', width: '50px' },
        { 
          data: 'quotation_ref',
          render: data => `<span style="font-weight: 600; color: #667eea;">${data || 'N/A'}</span>`
        },
        { 
          data: 'client_code',
          render: data => `<span style="font-weight: 500;">${data || 'N/A'}</span>`
        },
        { 
          data: 'quotation_date',
          render: data => data ? new Date(data).toLocaleDateString('en-GB') : 'N/A'
        },
        {
          data: 'kind_name',
          render: data => `<span class="badge bg-info">${data || 'N/A'}</span>`
        },
        { 
          data: 'total_amount',
          render: data => `<strong style="color: #667eea;">${parseFloat(data || 0).toFixed(2)} USD</strong>`,
          className: 'text-end'
        },
        { 
          data: 'total_amount_cdf',
          render: data => {
            const val = parseFloat(data || 0);
            return val > 0 ? `<strong style="color: #28a745;">${val.toFixed(2)} CDF</strong>` : '-';
          },
          className: 'text-end'
        },
        {
          data: null,
          orderable: false,
          searchable: false,
          width: '150px',
          render: (data, type, row) => `
            <div class="btn-group btn-group-sm" role="group">
              <button class="btn btn-primary btn-sm editBtn" data-id="${row.id}" title="Edit"><i class="ti ti-edit"></i></button>
              <button class="btn btn-info btn-sm copyBtn" data-id="${row.id}" title="Copy"><i class="ti ti-copy"></i></button>
              <button class="btn btn-danger btn-sm deleteBtn" data-id="${row.id}" title="Delete"><i class="ti ti-trash"></i></button>
            </div>
          `
        }
      ],
      order: [[0, 'desc']],
      pageLength: 25,
      responsive: true,
      processing: true
    });
  }

  // =====================================================
  // EDIT HANDLER
  // =====================================================
  $(document).on('click', '.editBtn', function () {
    const id = $(this).data('id');
    toggleAccordion(true);
    
    Swal.fire({ title: 'Loading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    $.ajax({
      url: '<?= APP_URL ?>/quotation/crudData/getQuotation',
      method: 'GET',
      data: { id },
      dataType: 'json',
      success: function (res) {
        Swal.close();
        
        if (res.success && res.data) {
          const q = res.data.quotation;
          const items = res.data.items;

          $('#quotation_id').val(q.id);
          $('#formAction').val('update');
          $('#formTitle').text('Edit Quotation - ' + q.quotation_ref);
          $('#submitBtnText').text('Update Quotation');

          $('#quotation_ref').val(q.quotation_ref || '');
          $('#client_id').val(q.client_id || '');
          $('#quotation_date').val(q.quotation_date || '');
          $('#kind_id').val(q.kind_id || '');
          $('#transport_mode_id').val(q.transport_mode_id || '');
          $('#goods_type_id').val(q.goods_type_id || '');
          $('#arsp').val(q.arsp || 'Disabled');

          // Trigger kind change to set mode
          $('#kind_id').trigger('change');

          const kindText = $('#kind_id option:selected').text();
          const isED = kindText.toUpperCase().includes('EXPORT');

          // Clear containers
          categories.forEach(function(category) {
            $('#ed_' + category + 'Container').empty();
            $('#import_' + category + 'Container').empty();
            edItemIndexes[category] = 0;
            importItemIndexes[category] = 0;
          });

          // Add items
          categories.forEach(function(category) {
            if (items[category] && items[category].length > 0) {
              items[category].forEach(function(item) {
                if (isED) {
                  addEDItem(category, item);
                } else {
                  addImportItem(category, item);
                }
              });
            }
          });

          if ($('.item-row:visible').length === 0 && categories.length > 0) {
            if (isED) addEDItem(categories[0]);
            else addImportItem(categories[0]);
          }

          calculateTotals();
          $('html, body').animate({ scrollTop: $('#formAccordionHeader').offset().top - 20 }, 500);
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: res.message || 'Failed to load quotation' });
        }
      },
      error: function () {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load quotation data' });
      }
    });
  });

  // =====================================================
  // COPY HANDLER
  // =====================================================
  $(document).on('click', '.copyBtn', function () {
    const id = $(this).data('id');
    toggleAccordion(true);
    
    Swal.fire({ title: 'Copying...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    $.ajax({
      url: '<?= APP_URL ?>/quotation/crudData/copyQuotation',
      method: 'GET',
      data: { id },
      dataType: 'json',
      success: function (res) {
        Swal.close();
        
        if (res.success && res.data) {
          const q = res.data.quotation;
          const items = res.data.items;

          $('#quotation_id').val('');
          $('#formAction').val('insert');
          $('#formTitle').html('<i class="ti ti-copy me-2"></i>Copy Quotation');
          $('#submitBtnText').text('Save Quotation');

          $('#client_id').val(q.client_id || '');
          $('#kind_id').val(q.kind_id || '');
          $('#transport_mode_id').val(q.transport_mode_id || '');
          $('#goods_type_id').val(q.goods_type_id || '');
          $('#arsp').val(q.arsp || 'Disabled');
          $('#quotation_date').val(q.quotation_date || '');
          $('#quotation_ref').val('');

          $('#kind_id').trigger('change');

          const kindText = $('#kind_id option:selected').text();
          const isED = kindText.toUpperCase().includes('EXPORT');

          categories.forEach(function(category) {
            $('#ed_' + category + 'Container').empty();
            $('#import_' + category + 'Container').empty();
            edItemIndexes[category] = 0;
            importItemIndexes[category] = 0;
          });

          categories.forEach(function(category) {
            if (items[category] && items[category].length > 0) {
              items[category].forEach(function(item) {
                if (isED) addEDItem(category, item);
                else addImportItem(category, item);
              });
            }
          });

          calculateTotals();
          
          Swal.fire({ icon: 'info', title: 'Items Copied!', text: 'Change any dropdown to generate new reference.', confirmButtonText: 'Got it!' });
          $('html, body').animate({ scrollTop: $('#formAccordionHeader').offset().top - 20 }, 500);
        }
      },
      error: function () {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to copy quotation' });
      }
    });
  });

  // =====================================================
  // DELETE HANDLER
  // =====================================================
  $(document).on('click', '.deleteBtn', function () {
    const id = $(this).data('id');
    
    Swal.fire({
      title: 'Are you sure?',
      text: "This will delete the quotation!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '<?= APP_URL ?>/quotation/crudData/deletion',
          method: 'POST',
          data: { id },
          dataType: 'json',
          success: function (res) {
            if (res.success) {
              Swal.fire({ icon: 'success', title: 'Deleted!', text: res.message, timer: 1500, showConfirmButton: false }).then(() => {
                quotationsTable.ajax.reload(null, false);
              });
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: res.message });
            }
          }
        });
      }
    });
  });

  init();
  
  console.log('✅ Quotations initialized with UNIT from database');
  console.log('📊 Kind ID ' + IMPORT_DEFINITIVE_KIND_ID + ' = IMPORT DEFINITIVE (CDF columns for Customs)');
  console.log('📦 Units loaded:', units.length);
});
</script>