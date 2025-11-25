<link href="<?= BASE_URL ?>/assets/pages/css/local_styles.css" rel="stylesheet" type="text/css">

<style>
  .dataTables_wrapper .dataTables_info {
    float: left;
  }
  .dataTables_wrapper .dataTables_paginate {
    float: right;
    text-align: right;
  }
  
  /* Export Button Styling - Green */
  .btn-export-all {
    background: #28a745 !important;
    color: white !important;
    border: none !important;
    padding: 8px 20px !important;
    border-radius: 5px !important;
    font-weight: 500 !important;
    transition: all 0.3s !important;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3) !important;
  }
  
  .btn-export-all:hover {
    background: #218838 !important;
    color: white !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4) !important;
  }
  
  /* Export Individual Button */
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
  
  /* Manage Seal Numbers Button */
  .btn-seal-numbers {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border: none;
  }
  .btn-seal-numbers:hover {
    background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(245, 87, 108, 0.4);
  }
  
  /* Required field indicator */
  .text-danger {
    color: #dc3545;
    font-weight: bold;
  }
  
  /* Validation Error Styling */
  .is-invalid {
    border-color: #dc3545 !important;
  }
  
  .invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
  }
  
  /* Colorful View Button */
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
  
  /* Stats Cards */
  .stats-card {
    border: none;
    border-radius: 15px;
    transition: transform 0.3s, box-shadow 0.3s;
    overflow: hidden;
    cursor: pointer;
  }
  
  .stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  }
  
  .stats-card-1 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }
  
  .stats-card-used {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    color: white;
  }
  
  .stats-card-damaged {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
  }
  
  .stats-card-location {
    color: white;
  }
  
  .stats-card.active {
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
    transform: translateY(-5px) scale(1.02);
  }
  
  .stats-card .card-body {
    padding: 25px;
  }
  
  .stats-icon {
    font-size: 3rem;
    opacity: 0.3;
    position: absolute;
    right: 20px;
    top: 20px;
  }
  
  .stats-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 5px;
  }
  
  .stats-label {
    font-size: 0.9rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  
  /* Added sub-label */
  .stats-added {
    font-size: 0.85rem;
    opacity: 0.8;
    margin-top: 5px;
  }
  
  /* Modal Styling */
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

  /* Badge styles */
  .badge {
    padding: 6px 12px;
    font-size: 0.85rem;
  }
  
  /* Auto-calculated field styling */
  .auto-calculated-field {
    background-color: #e9ecef;
    cursor: not-allowed;
    font-weight: 600;
    color: #495057;
  }
  
  /* Seal Numbers Card Styling */
  .seal-number-card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 15px;
    transition: all 0.3s;
    background: white;
  }
  
  .seal-number-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-color: #667eea;
  }
  
  .seal-number-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }
  
  .seal-number-text {
    font-weight: 700;
    color: #2d3748;
    font-size: 1.1rem;
  }
  
  .seal-number-body {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 10px;
  }
  
  .seal-info-item {
    display: flex;
    flex-direction: column;
  }
  
  .seal-info-label {
    font-size: 0.75rem;
    color: #667eea;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 3px;
  }
  
  .seal-info-value {
    font-size: 0.95rem;
    color: #2d3748;
    font-weight: 500;
  }
  
  .seal-status-badge {
    font-size: 0.85rem;
    padding: 4px 12px;
  }
  
  /* Progress bar for seal count */
  .seal-progress {
    margin-top: 10px;
  }
  
  .seal-progress-text {
    font-size: 0.9rem;
    color: #667eea;
    font-weight: 600;
    margin-bottom: 5px;
  }
  
  /* Add Seal Input Container */
  .add-seal-input-container {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
  }

  /* Status Badge Warning */
  .status-warning {
    background: #fff3cd;
    border: 1px solid #ffc107;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
    font-size: 0.85rem;
    color: #856404;
  }
</style>

<div class="page-content">
  <div class="page-container">
    <div class="row">
      <div class="col-12">
        
        <!-- Statistics Cards -->
        <div class="row mb-4" id="statsCardsContainer">
          <!-- Total Seals Card -->
          <div class="col-xl-2 col-md-4 mb-3">
            <div class="card stats-card stats-card-1 shadow-sm active" data-location="0" data-filter-type="all">
              <div class="card-body position-relative">
                <i class="ti ti-shield-lock stats-icon"></i>
                <div class="stats-value" id="totalSeals">0</div>
                <div class="stats-label">Total Seals</div>
                <div class="stats-added">Added: <span id="totalAdded">0</span></div>
              </div>
            </div>
          </div>
          
          <!-- Used Seals Card -->
          <div class="col-xl-2 col-md-4 mb-3">
            <div class="card stats-card stats-card-used shadow-sm" data-filter-type="used">
              <div class="card-body position-relative">
                <i class="ti ti-check-circle stats-icon"></i>
                <div class="stats-value" id="usedSeals">0</div>
                <div class="stats-label">Used Seals</div>
                <div class="stats-added">Status: Used</div>
              </div>
            </div>
          </div>
          
          <!-- Damaged Seals Card -->
          <div class="col-xl-2 col-md-4 mb-3">
            <div class="card stats-card stats-card-damaged shadow-sm" data-filter-type="damaged">
              <div class="card-body position-relative">
                <i class="ti ti-alert-triangle stats-icon"></i>
                <div class="stats-value" id="damagedSeals">0</div>
                <div class="stats-label">Damaged Seals</div>
                <div class="stats-added">Status: Damaged</div>
              </div>
            </div>
          </div>
          
          <!-- Office Location Cards will be dynamically added here -->
          <?php 
          $gradients = [
            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
            'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
            'linear-gradient(135deg, #30cfd0 0%, #330867 100%)',
            'linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%)'
          ];
          foreach ($officeLocations as $index => $loc): 
            $gradient = $gradients[$index % count($gradients)];
          ?>
          <div class="col-xl-2 col-md-4 mb-3">
            <div class="card stats-card stats-card-location shadow-sm" data-location="<?= $loc['id'] ?>" data-filter-type="location" style="background: <?= $gradient ?>;">
              <div class="card-body position-relative">
                <i class="ti ti-map-pin stats-icon"></i>
                <div class="stats-value location-seal-count" data-location-id="<?= $loc['id'] ?>">0</div>
                <div class="stats-label"><?= htmlspecialchars($loc['main_location_name']) ?></div>
                <div class="stats-added">Added: <span class="location-added-count" data-location-id="<?= $loc['id'] ?>">0</span></div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          
        </div>

        <!-- Seal Form Card -->
        <div class="card shadow-sm mb-4">
          <div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
            <h4 class="header-title mb-0">
              <i class="ti ti-shield-lock me-2"></i> 
              <span id="formTitle">Add New Seal</span>
            </h4>
            <div class="d-flex gap-2">
              <!-- Export All Button -->
              <button type="button" class="btn btn-export-all" id="exportAllBtn">
                <i class="ti ti-file-spreadsheet me-1"></i> Export All to Excel
              </button>
              <button type="button" class="btn btn-sm btn-secondary" id="resetFormBtn" style="display:none;">
                <i class="ti ti-plus"></i> Add New
              </button>
            </div>
          </div>

          <div class="card-body">
            <form id="sealForm" method="post" novalidate>
              <!-- CSRF Token -->
              <input type="hidden" name="csrf_token" id="csrf_token" value="<?= $csrf_token ?>">
              <input type="hidden" name="seal_id" id="seal_id" value="">
              <input type="hidden" name="action" id="formAction" value="insert">

              <div class="accordion" id="sealAccordion">
                
                <!-- SEAL INFORMATION -->
                <div class="accordion-item mb-3">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sealInfo">
                      <i class="ti ti-shield-lock me-2"></i> Seal Information
                    </button>
                  </h2>

                  <div id="sealInfo" class="accordion-collapse collapse" data-bs-parent="#sealAccordion">
                    <div class="accordion-body">
                      
                      <div class="row">
                        <div class="col-md-3 mb-3">
                          <label>Office Location <span class="text-danger">*</span></label>
                          <select name="office_location_id" id="office_location_id" class="form-select" required>
                            <option value="">-- Select Office Location --</option>
                            <?php foreach ($officeLocations as $loc): ?>
                              <option value="<?= $loc['id'] ?>">
                                <?= htmlspecialchars($loc['main_location_name']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="office_location_id_error"></div>
                        </div>

                        <div class="col-md-3 mb-3">
                          <label>Purchase Date <span class="text-danger">*</span></label>
                          <input type="date" name="purchase_date" id="purchase_date" class="form-control" required>
                          <div class="invalid-feedback" id="purchase_date_error"></div>
                        </div>

                        <div class="col-md-3 mb-3">
                          <label>Total Amount ($) <span class="text-danger">*</span></label>
                          <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" required min="0" max="999999.99" placeholder="Enter total amount">
                          <div class="invalid-feedback" id="total_amount_error"></div>
                        </div>

                        <div class="col-md-3 mb-3">
                          <label>Per Seal Amount</label>
                          <input type="text" value="$10.00" class="form-control auto-calculated-field" readonly>
                          <small class="text-muted">Fixed price per seal</small>
                        </div>

                        <div class="col-md-3 mb-3">
                          <label>Total Seal <small class="text-muted">(Auto-calculated)</small></label>
                          <input type="number" name="total_seal" id="total_seal" class="form-control auto-calculated-field" readonly>
                          <small class="text-muted">= Total Amount ÷ $10</small>
                        </div>

                        <div class="col-md-3 mb-3">
                          <label>Display <span class="text-danger">*</span></label>
                          <select name="display" id="display" class="form-select" required>
                            <option value="Y" selected>Yes</option>
                            <option value="N">No</option>
                          </select>
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
                    <i class="ti ti-check me-1"></i> <span id="submitBtnText">Save Seal</span>
                  </button>
                </div>
              </div>

            </form>
          </div>
        </div>

        <!-- Seals DataTable -->
        <div class="card shadow-sm">
          <div class="card-header border-bottom border-dashed">
            <h4 class="header-title mb-0"><i class="ti ti-list me-2"></i> Seals List</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="sealsTable" class="table table-striped table-bordered dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Office Location</th>
                    <th>Purchase Date</th>
                    <th>Total Amount</th>
                    <th>Total Seal</th>
                    <th>Added</th>
                    <th>Display</th>
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
<div class="modal fade" id="viewSealModal" tabindex="-1" aria-labelledby="viewSealModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewSealModalLabel">
          <i class="ti ti-eye me-2"></i> Seal Details
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

<!-- Manage Seal Numbers Modal -->
<div class="modal fade" id="manageSealNumbersModal" tabindex="-1" aria-labelledby="manageSealNumbersModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="manageSealNumbersModalLabel">
          <i class="ti ti-list-numbers me-2"></i> Manage Seal Numbers - <span id="modalLocationName"></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- Left Side: Add Seal Numbers -->
          <div class="col-md-5">
            <div class="card border-0 bg-light">
              <div class="card-body">
                <h6 class="mb-3"><i class="ti ti-plus-circle me-2"></i>Add Seal Number</h6>
                
                <!-- Progress Bar -->
                <div class="seal-progress mb-3">
                  <div class="seal-progress-text">
                    Added: <span id="addedSealCount">0</span> / <span id="totalSealLimit">0</span>
                  </div>
                  <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" id="sealProgressBar" style="width: 0%"></div>
                  </div>
                </div>
                
                <form id="addSealNumberForm">
                  <input type="hidden" id="seal_master_id" name="seal_master_id">
                  
                  <div class="add-seal-input-container">
                    <label class="form-label">Seal Number <span class="text-danger">*</span></label>
                    <input 
                      type="text" 
                      name="seal_number_single" 
                      id="seal_number_single" 
                      class="form-control" 
                      placeholder="Enter seal number (e.g., SEAL-001)"
                      required
                    >
                    <small class="text-muted">Enter one seal number at a time</small>
                  </div>
                  
                  <button type="submit" class="btn btn-primary w-100" id="addSealNumberBtn">
                    <i class="ti ti-plus me-1"></i> Add Seal Number
                  </button>
                </form>
              </div>
            </div>
          </div>
          
          <!-- Right Side: Existing Seal Numbers -->
          <div class="col-md-7">
            <h6 class="mb-3"><i class="ti ti-list me-2"></i>Existing Seal Numbers</h6>
            <div id="sealNumbersList" style="max-height: 500px; overflow-y: auto;">
              <!-- Seal numbers will be loaded here -->
            </div>
          </div>
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

<!-- Edit Seal Number Modal -->
<div class="modal fade" id="editSealNumberModal" tabindex="-1" aria-labelledby="editSealNumberModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editSealNumberModalLabel">
          <i class="ti ti-edit me-2"></i> Edit Seal Number
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editSealNumberForm">
          <input type="hidden" name="seal_number_id" id="edit_seal_number_id">
          <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
          <input type="hidden" id="edit_current_status">
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Location</label>
              <input type="text" id="edit_location" class="form-control auto-calculated-field" readonly>
              <small class="text-muted">Purchase location</small>
            </div>
            
            <div class="col-md-6 mb-3">
              <label class="form-label">Seal Number <span class="text-danger">*</span></label>
              <input type="text" name="seal_number" id="edit_seal_number" class="form-control" required>
            </div>
            
            <div class="col-md-12 mb-3">
              <label class="form-label">Status <span class="text-danger">*</span></label>
              <select name="status" id="edit_status" class="form-select" required>
                <option value="Available">Available</option>
                <option value="Used">Used</option>
                <option value="Damaged">Damaged</option>
              </select>
              <div class="status-warning" id="statusWarning" style="display:none;">
                <i class="ti ti-alert-triangle me-1"></i>
                <strong>Warning:</strong> Once a seal is marked as "Used", it cannot be changed to "Damaged".
              </div>
            </div>
            
            <div class="col-md-12 mb-3">
              <label class="form-label">Notes</label>
              <textarea name="notes" id="edit_notes" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Cancel
        </button>
        <button type="button" class="btn btn-primary" id="updateSealNumberBtn">
          <i class="ti ti-check me-1"></i> Update
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function () {

    // CSRF Token
    const csrfToken = $('#csrf_token').val();
    
    // Current location filter
    let currentLocationFilter = 0;
    let currentStatusFilter = '';
    
    // Current seal master for managing seal numbers
    let currentSealMasterId = 0;
    let currentTotalSealLimit = 0;
    let currentLocationName = '';

    // ===== AUTO-CALCULATE TOTAL SEAL =====
    $('#total_amount').on('input', function() {
      calculateTotalSeal();
    });

    function calculateTotalSeal() {
      const totalAmount = parseFloat($('#total_amount').val()) || 0;
      const sealPrice = 10; // Fixed $10 per seal
      const totalSeal = Math.floor(totalAmount / sealPrice);
      $('#total_seal').val(totalSeal);
    }

    // ===== CLIENT-SIDE VALIDATION FUNCTIONS =====
    
    function clearValidationErrors() {
      $('.form-control, .form-select').removeClass('is-invalid');
      $('.invalid-feedback').text('').hide();
    }

    function showFieldError(fieldId, errorMessage) {
      $('#' + fieldId).addClass('is-invalid');
      $('#' + fieldId + '_error').text(errorMessage).show();
    }

    function validateForm() {
      clearValidationErrors();
      
      let errors = [];
      let hasError = false;

      const officeLocationId = $('#office_location_id').val();
      const purchaseDate = $('#purchase_date').val();
      const totalAmount = parseFloat($('#total_amount').val()) || 0;

      if (!officeLocationId || officeLocationId === '') {
        showFieldError('office_location_id', 'Please select Office Location (Required)');
        errors.push('Office Location is required');
        hasError = true;
      }

      if (!purchaseDate || purchaseDate === '') {
        showFieldError('purchase_date', 'Please select Purchase Date (Required)');
        errors.push('Purchase Date is required');
        hasError = true;
      }

      if (totalAmount <= 0) {
        showFieldError('total_amount', 'Total Amount must be greater than 0 (Required)');
        errors.push('Total Amount must be greater than 0');
        hasError = true;
      }

      return {
        isValid: !hasError,
        errors: errors
      };
    }

    // ===== REAL-TIME VALIDATION =====
    
    $('#office_location_id').on('change', function() {
      const value = $(this).val();
      if (!value || value === '') {
        $(this).addClass('is-invalid');
        $('#office_location_id_error').text('Please select Office Location (Required)').show();
      } else {
        $(this).removeClass('is-invalid');
        $('#office_location_id_error').text('').hide();
      }
    });

    $('#purchase_date').on('change', function() {
      const value = $(this).val();
      if (!value || value === '') {
        $(this).addClass('is-invalid');
        $('#purchase_date_error').text('Please select Purchase Date (Required)').show();
      } else {
        $(this).removeClass('is-invalid');
        $('#purchase_date_error').text('').hide();
      }
    });

    $('#total_amount').on('input', function() {
      const value = parseFloat($(this).val()) || 0;
      if (value <= 0) {
        $(this).addClass('is-invalid');
        $('#total_amount_error').text('Total Amount must be greater than 0 (Required)').show();
      } else {
        $(this).removeClass('is-invalid');
        $('#total_amount_error').text('').hide();
      }
    });

    // Date formatting helper function
    function formatDateToDDMMYYYY(dateStr) {
      if (!dateStr) return '';
      
      const date = new Date(dateStr);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      
      return `${day}-${month}-${year}`;
    }

    // Initialize DataTable
    var sealsTable;
    function initDataTable() {
      if ($.fn.DataTable.isDataTable('#sealsTable')) {
        $('#sealsTable').DataTable().destroy();
      }

      sealsTable = $('#sealsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '<?= APP_URL ?>/seal/crudData/listing',
          type: 'GET',
          data: function(d) {
            d.location_filter = currentLocationFilter;
            d.status_filter = currentStatusFilter;
          },
          error: function(xhr, error, code) {
            console.error('DataTable error:', error, code, xhr.responseText);
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to load data. Please try again.',
              confirmButtonText: 'OK'
            });
          }
        },
        columns: [
          { data: 'id' },
          { data: 'location_name' },
          { 
            data: 'purchase_date',
            render: function(data, type, row) {
              return formatDateToDDMMYYYY(data);
            }
          },
          { 
            data: 'total_amount',
            render: function(data, type, row) {
              return '$' + parseFloat(data).toFixed(2);
            }
          },
          { data: 'total_seal' },
          { 
            data: 'added_seals',
            render: function(data, type, row) {
              const added = data || 0;
              const total = row.total_seal || 0;
              const percentage = total > 0 ? Math.round((added / total) * 100) : 0;
              
              let badgeClass = 'bg-secondary';
              if (percentage > 0 && percentage < 50) badgeClass = 'bg-warning';
              else if (percentage >= 50 && percentage < 100) badgeClass = 'bg-info';
              else if (percentage >= 100) badgeClass = 'bg-success';
              
              return `<span class="badge ${badgeClass}">${added}</span>`;
            }
          },
          { 
            data: 'display',
            render: function(data, type, row) {
              if (data === 'Y') {
                return '<span class="badge bg-success">Yes</span>';
              } else {
                return '<span class="badge bg-danger">No</span>';
              }
            }
          },
          {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              return `
                <button class="btn btn-sm btn-seal-numbers manageSealNumbersBtn" data-id="${row.id}" data-total="${row.total_seal}" data-location="${row.location_name}" title="Manage Seal Numbers">
                  <i class="ti ti-list-numbers"></i>
                </button>
                <button class="btn btn-sm btn-view viewBtn" data-id="${row.id}" title="View Details">
                  <i class="ti ti-eye"></i>
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
              `;
            }
          }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        responsive: true,
        language: {
          processing: '<i class="spinner-border spinner-border-sm"></i> Loading...',
          emptyTable: 'No seals found',
          zeroRecords: 'No matching seals found'
        },
        drawCallback: function() {
          updateStatistics();
        }
      });
    }

    // ===== MANAGE SEAL NUMBERS =====
    
    // Open Manage Seal Numbers Modal
    $(document).on('click', '.manageSealNumbersBtn', function() {
      const sealMasterId = $(this).data('id');
      const totalSeal = $(this).data('total');
      const locationName = $(this).data('location');
      
      currentSealMasterId = sealMasterId;
      currentTotalSealLimit = totalSeal;
      currentLocationName = locationName;
      
      $('#seal_master_id').val(sealMasterId);
      $('#totalSealLimit').text(totalSeal);
      $('#modalLocationName').text(locationName);
      $('#seal_number_single').val('');
      
      loadSealNumbers(sealMasterId);
      
      $('#manageSealNumbersModal').modal('show');
    });
    
    // Load existing seal numbers
    function loadSealNumbers(sealMasterId) {
      $.ajax({
        url: '<?= APP_URL ?>/seal/crudData/getSealNumbers',
        method: 'GET',
        data: { seal_master_id: sealMasterId },
        dataType: 'json',
        success: function(res) {
          if (res.success) {
            const sealNumbers = res.data;
            let html = '';
            
            if (sealNumbers.length === 0) {
              html = '<div class="alert alert-info"><i class="ti ti-info-circle me-2"></i>No seal numbers added yet</div>';
            } else {
              sealNumbers.forEach(function(seal) {
                let statusClass = 'bg-success';
                if (seal.status === 'Used') statusClass = 'bg-warning';
                if (seal.status === 'Damaged') statusClass = 'bg-danger';
                
                html += `
                  <div class="seal-number-card">
                    <div class="seal-number-header">
                      <div>
                        <span class="seal-number-text">${seal.seal_number}</span>
                        <span class="badge seal-status-badge ${statusClass} ms-2">${seal.status}</span>
                      </div>
                      <button class="btn btn-sm btn-primary editSealNumberBtn" data-id="${seal.id}">
                        <i class="ti ti-edit"></i> Edit
                      </button>
                    </div>
                    <div class="seal-number-body">
                      <div class="seal-info-item">
                        <span class="seal-info-label">Location</span>
                        <span class="seal-info-value">${seal.location || 'N/A'}</span>
                      </div>
                      ${seal.notes ? `
                      <div class="seal-info-item">
                        <span class="seal-info-label">Notes</span>
                        <span class="seal-info-value">${seal.notes}</span>
                      </div>
                      ` : ''}
                    </div>
                  </div>
                `;
              });
            }
            
            $('#sealNumbersList').html(html);
            
            // Update progress
            const addedCount = sealNumbers.length;
            
            $('#addedSealCount').text(addedCount);
            
            const percentage = currentTotalSealLimit > 0 ? Math.round((addedCount / currentTotalSealLimit) * 100) : 0;
            $('#sealProgressBar').css('width', percentage + '%').attr('aria-valuenow', percentage);
          }
        },
        error: function() {
          $('#sealNumbersList').html('<div class="alert alert-danger">Failed to load seal numbers</div>');
        }
      });
    }
    
    // Add single seal number
    $('#addSealNumberForm').on('submit', function(e) {
      e.preventDefault();
      
      const sealNumber = $('#seal_number_single').val().trim();
      
      if (!sealNumber) {
        Swal.fire('Error', 'Please enter a seal number', 'error');
        return;
      }
      
      const addBtn = $('#addSealNumberBtn');
      const originalText = addBtn.html();
      addBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Adding...');
      
      const formData = {
        seal_master_id: $('#seal_master_id').val(),
        seal_numbers: sealNumber,
        csrf_token: csrfToken
      };
      
      $.ajax({
        url: '<?= APP_URL ?>/seal/crudData/addSealNumbers',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(res) {
          addBtn.prop('disabled', false).html(originalText);
          
          if (res.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: res.message,
              timer: 1500,
              showConfirmButton: false
            });
            
            $('#seal_number_single').val('');
            loadSealNumbers(currentSealMasterId);
            sealsTable.ajax.reload(null, false);
            updateStatistics();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: res.message
            });
          }
        },
        error: function() {
          addBtn.prop('disabled', false).html(originalText);
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Failed to add seal number'
          });
        }
      });
    });
    
    // Open Edit Seal Number Modal - WITH STATUS VALIDATION
    $(document).on('click', '.editSealNumberBtn', function() {
      const id = $(this).data('id');
      
      $.ajax({
        url: '<?= APP_URL ?>/seal/crudData/getSingleSealNumber',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(res) {
          if (res.success && res.data) {
            const seal = res.data;
            
            $('#edit_seal_number_id').val(seal.id);
            $('#edit_location').val(seal.location || '');
            $('#edit_seal_number').val(seal.seal_number || '');
            $('#edit_status').val(seal.status || 'Available');
            $('#edit_notes').val(seal.notes || '');
            $('#edit_current_status').val(seal.status || 'Available');
            
            // Show warning if status is "Used"
            if (seal.status === 'Used') {
              $('#statusWarning').show();
              // Disable "Damaged" option
              $('#edit_status option[value="Damaged"]').prop('disabled', true);
            } else {
              $('#statusWarning').hide();
              $('#edit_status option[value="Damaged"]').prop('disabled', false);
            }
            
            $('#editSealNumberModal').modal('show');
          } else {
            Swal.fire('Error', res.message || 'Failed to load seal number data', 'error');
          }
        },
        error: function() {
          Swal.fire('Error', 'Failed to load seal number data', 'error');
        }
      });
    });
    
    // Status change validation in edit modal
    $('#edit_status').on('change', function() {
      const currentStatus = $('#edit_current_status').val();
      const newStatus = $(this).val();
      
      if (currentStatus === 'Used' && newStatus === 'Damaged') {
        Swal.fire({
          icon: 'warning',
          title: 'Invalid Status Change',
          text: 'Cannot change status from "Used" to "Damaged"',
          confirmButtonText: 'OK'
        });
        $(this).val(currentStatus);
      }
    });
    
    // Update Seal Number
    $('#updateSealNumberBtn').on('click', function() {
      const updateBtn = $(this);
      const originalText = updateBtn.html();
      updateBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Updating...');
      
      const formData = $('#editSealNumberForm').serialize();
      
      $.ajax({
        url: '<?= APP_URL ?>/seal/crudData/updateSealNumber',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(res) {
          updateBtn.prop('disabled', false).html(originalText);
          
          if (res.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: res.message,
              timer: 1500,
              showConfirmButton: false
            });
            
            $('#editSealNumberModal').modal('hide');
            loadSealNumbers(currentSealMasterId);
            sealsTable.ajax.reload(null, false);
            updateStatistics();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: res.message
            });
          }
        },
        error: function() {
          updateBtn.prop('disabled', false).html(originalText);
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Failed to update seal number'
          });
        }
      });
    });

    // Export All Button Handler
    $('#exportAllBtn').on('click', function() {
      Swal.fire({
        title: 'Exporting...',
        text: 'Please wait while we prepare your comprehensive Excel export with charts',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      const exportUrl = '<?= APP_URL ?>/seal/crudData/exportAll';
      window.location.href = exportUrl;
      
      setTimeout(function() {
        Swal.close();
      }, 2000);
    });

    // Export individual seal
    $(document).on('click', '.exportBtn', function () {
      const id = $(this).data('id');
      const exportUrl = '<?= APP_URL ?>/seal/crudData/exportSeal?id=' + id;
      
      Swal.fire({
        title: 'Exporting...',
        text: 'Preparing seal data for export',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
      
      window.location.href = exportUrl;
      
      setTimeout(function() {
        Swal.close();
      }, 2000);
    });

    // Update Statistics Cards
    function updateStatistics() {
      $.ajax({
        url: '<?= APP_URL ?>/seal/crudData/statistics',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
          if (res.success) {
            $('#totalSeals').text(res.data.total_seals || 0);
            $('#totalAdded').text(res.data.added_seals || 0);
            $('#usedSeals').text(res.data.used_seals || 0);
            $('#damagedSeals').text(res.data.damaged_seals || 0);
            
            // Update location-specific counts
            if (res.data.location_counts && res.data.location_counts.length > 0) {
              res.data.location_counts.forEach((loc) => {
                $('.location-seal-count[data-location-id="' + loc.id + '"]').text(loc.seal_count || 0);
                $('.location-added-count[data-location-id="' + loc.id + '"]').text(loc.added_count || 0);
              });
            }
          }
        },
        error: function() {
          console.error('Failed to load statistics');
        }
      });
    }

    // Handle stats card click for filtering
    $(document).on('click', '.stats-card', function() {
      const filterType = $(this).data('filter-type');
      const locationId = $(this).data('location');
      
      $('.stats-card').removeClass('active');
      $(this).addClass('active');
      
      if (filterType === 'all') {
        currentLocationFilter = 0;
        currentStatusFilter = '';
      } else if (filterType === 'used') {
        currentLocationFilter = 0;
        currentStatusFilter = 'Used';
      } else if (filterType === 'damaged') {
        currentLocationFilter = 0;
        currentStatusFilter = 'Damaged';
      } else if (filterType === 'location') {
        currentLocationFilter = locationId;
        currentStatusFilter = '';
      }
      
      if (typeof sealsTable !== 'undefined') {
        sealsTable.ajax.reload();
      }
    });

    // View seal details in modal
    $(document).on('click', '.viewBtn', function () {
      const id = $(this).data('id');
      
      // Show loading
      Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
      
      $.ajax({
        url: '<?= APP_URL ?>/seal/crudData/getSeal',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
          Swal.close();
          
          if (res.success && res.data) {
            const seal = res.data;
            let detailsHtml = `
              <div class="detail-row">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-id detail-icon"></i>ID
                    </div>
                    <div class="detail-value">${seal.id || 'N/A'}</div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-map-pin detail-icon"></i>Office Location
                    </div>
                    <div class="detail-value">${seal.main_location_name || 'N/A'}</div>
                  </div>
                </div>
              </div>
              
              <div class="detail-row">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-calendar detail-icon"></i>Purchase Date
                    </div>
                    <div class="detail-value">${formatDateToDDMMYYYY(seal.purchase_date) || 'N/A'}</div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-coin detail-icon"></i>Total Amount
                    </div>
                    <div class="detail-value">$${parseFloat(seal.total_amount || 0).toFixed(2)}</div>
                  </div>
                </div>
              </div>
              
              <div class="detail-row">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-package detail-icon"></i>Total Seal
                    </div>
                    <div class="detail-value">${seal.total_seal || 0} seals</div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-coin detail-icon"></i>Per Seal Amount
                    </div>
                    <div class="detail-value">$10.00</div>
                  </div>
                </div>
              </div>
              
              <div class="detail-row">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-eye detail-icon"></i>Display
                    </div>
                    <div class="detail-value">
                      ${seal.display === 'Y' ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>'}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-calendar-plus detail-icon"></i>Created At
                    </div>
                    <div class="detail-value">${formatDateToDDMMYYYY(seal.created_at) || 'N/A'}</div>
                  </div>
                </div>
              </div>
            `;
            
            $('#modalDetailsContent').html(detailsHtml);
            $('#viewSealModal').modal('show');
          } else {
            Swal.fire('Error', res.message || 'Failed to load seal data', 'error');
          }
        },
        error: function () {
          Swal.close();
          Swal.fire('Error', 'Failed to load seal data. Please try again.', 'error');
        }
      });
    });

    // Form submission
    $('#sealForm').on('submit', function (e) {
      e.preventDefault();

      const validation = validateForm();
      
      if (!validation.isValid) {
        // Open accordion if validation fails
        $('#sealInfo').collapse('show');
        
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          html: '<ul style="text-align:left;"><li>' + validation.errors.join('</li><li>') + '</li></ul>',
          confirmButtonText: 'OK'
        });
        
        const firstError = $('.is-invalid').first();
        if (firstError.length) {
          $('html, body').animate({
            scrollTop: firstError.offset().top - 100
          }, 300);
          firstError.focus();
        }
        
        return false;
      }

      const submitBtn = $('#submitBtn');
      const originalText = submitBtn.html();
      submitBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Saving...');

      const formData = new FormData(this);
      formData.set('csrf_token', csrfToken);
      
      const action = $('#formAction').val();

      $.ajax({
        url: '<?= APP_URL ?>/seal/crudData/' + action,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (res) {
          submitBtn.prop('disabled', false).html(originalText);

          if (res.success) {
            Swal.fire({ 
              icon: 'success', 
              title: 'Success!', 
              text: res.message || 'Saved successfully', 
              timer: 1500, 
              showConfirmButton: false 
            });
            resetForm();
            if (typeof sealsTable !== 'undefined') {
              sealsTable.ajax.reload(null, false);
            }
            updateStatistics();
          } else {
            Swal.fire({ 
              icon: 'error', 
              title: 'Error!', 
              html: res.message || 'Unable to save' 
            });
          }
        },
        error: function (xhr) {
          submitBtn.prop('disabled', false).html(originalText);
          
          let errorMsg = 'An error occurred while processing your request';
          
          if (xhr.status === 403) {
            errorMsg = 'Security token expired. Please refresh the page and try again.';
          } else {
            try {
              const response = JSON.parse(xhr.responseText);
              errorMsg = response.message || errorMsg;
            } catch (e) {
              errorMsg = xhr.responseText || errorMsg;
            }
          }
          
          Swal.fire({ 
            icon: 'error', 
            title: 'Server Error', 
            html: errorMsg 
          });
        }
      });
    });

    // Reset form function
    function resetForm() {
      $('#sealForm')[0].reset();
      clearValidationErrors();
      $('#seal_id').val('');
      $('#office_location_id').val('');
      $('#formAction').val('insert');
      $('#formTitle').text('Add New Seal');
      $('#submitBtnText').text('Save Seal');
      $('#resetFormBtn').hide();
      $('#total_seal').val('');
      $('#display').val('Y');
      
      // Collapse accordion
      $('#sealInfo').collapse('hide');

      $('html, body').animate({ scrollTop: $('#sealForm').offset().top - 100 }, 200);
    }

    $('#cancelBtn, #resetFormBtn').on('click', function (e) {
      e.preventDefault();
      resetForm();
    });

    // Edit seal
    $(document).on('click', '.editBtn', function () {
      const id = $(this).data('id');
      
      // Show loading
      Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
      
      $.ajax({
        url: '<?= APP_URL ?>/seal/crudData/getSeal',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
          Swal.close();
          
          if (res.success && res.data) {
            const seal = res.data;

            clearValidationErrors();

            $('#seal_id').val(seal.id);
            $('#formAction').val('update');
            $('#formTitle').text('Edit Seal');
            $('#submitBtnText').text('Update Seal');
            $('#resetFormBtn').show();

            $('#office_location_id').val(seal.office_location_id || '');
            $('#purchase_date').val(seal.purchase_date || '');
            $('#total_amount').val(seal.total_amount || '');
            $('#total_seal').val(seal.total_seal || '');
            $('#display').val(seal.display || 'Y');

            // Open accordion when editing
            $('#sealInfo').collapse('show');

            $('html, body').animate({ scrollTop: $('#sealForm').offset().top - 100 }, 500);
          } else {
            Swal.fire('Error', res.message || 'Failed to load seal data', 'error');
          }
        },
        error: function () {
          Swal.close();
          Swal.fire('Error', 'Failed to load seal data. Please try again.', 'error');
        }
      });
    });

    // Delete seal
    $(document).on('click', '.deleteBtn', function () {
      const id = $(this).data('id');
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
            url: '<?= APP_URL ?>/seal/crudData/deletion',
            method: 'POST',
            data: { 
              id: id,
              csrf_token: csrfToken
            },
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
                sealsTable.ajax.reload(null, false);
                updateStatistics();
              } else {
                Swal.fire('Error', res.message || 'Delete failed', 'error');
              }
            },
            error: function (xhr) {
              let errorMsg = 'Failed to delete seal';
              
              if (xhr.status === 403) {
                errorMsg = 'Security token expired. Please refresh the page and try again.';
              }
              
              Swal.fire('Error', errorMsg, 'error');
            }
          });
        }
      });
    });

    // Initialize DataTable on page load
    initDataTable();
    
    // Initial statistics load
    updateStatistics();
  });
</script>