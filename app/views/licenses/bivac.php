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
  
  /* Auto-calculated/Read-only field styling */
  .readonly-field {
    background-color: #e9ecef !important;
    cursor: not-allowed !important;
    font-weight: 600;
    color: #495057 !important;
  }
  
  /* Value boxes for detail view */
  .value-box {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    margin-bottom: 15px;
  }
  .value-box .value-label {
    font-size: 0.85rem;
    color: #7F8C8D;
    margin-bottom: 5px;
    font-weight: 600;
  }
  .value-box .value-number {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2C3E50;
  }
  .value-box-license { border-left: 4px solid #3498DB; }
  .value-box-used { border-left: 4px solid #F39C12; }
  .value-box-available { border-left: 4px solid #9B59B6; }

  /* License Info Box */
  .license-info-box {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
  }
  .license-info-box .info-label {
    font-weight: 600;
    color: #667eea;
    margin-bottom: 5px;
    font-size: 0.85rem;
  }
  .license-info-box .info-value {
    color: #2C3E50;
    font-size: 1rem;
    font-weight: 500;
  }

  /* Help Text */
  .help-text {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 15px;
    font-size: 0.9rem;
    color: #856404;
  }

  /* Section Headers */
  .section-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    margin-top: 20px;
  }
</style>

<div class="page-content">
  <div class="page-container">
    <div class="row">
      <div class="col-12">

        <!-- PARTIELLE Form Card -->
        <div class="card shadow-sm mb-4">
          <div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
            <h4 class="header-title mb-0">
              <i class="ti ti-file-invoice me-2"></i> 
              <span id="formTitle">Add New PARTIELLE</span>
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
            <form id="partielleForm" method="post" novalidate>
              <!-- CSRF Token -->
              <input type="hidden" name="csrf_token" id="csrf_token" value="<?= $csrf_token ?>">
              <input type="hidden" name="partielle_id" id="partielle_id" value="">
              <input type="hidden" name="action" id="formAction" value="insert">

              <div class="accordion" id="partielleAccordion">
                
                <!-- PARTIELLE INFORMATION -->
                <div class="accordion-item mb-3">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#partielleInfo">
                      <i class="ti ti-file-invoice me-2"></i> PARTIELLE Information
                    </button>
                  </h2>

                  <div id="partielleInfo" class="accordion-collapse collapse" data-bs-parent="#partielleAccordion">
                    <div class="accordion-body">
                      
                      <!-- License Selection -->
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label>License <span class="text-danger">*</span></label>
                          <select name="license_id" id="license_id" class="form-select" required>
                            <option value="">-- Select License --</option>
                            <?php foreach ($licenses as $lic): ?>
                              <option value="<?= $lic['id'] ?>" 
                                      data-number="<?= htmlspecialchars($lic['license_number'], ENT_QUOTES, 'UTF-8') ?>"
                                      data-crf="<?= htmlspecialchars($lic['ref_cod'], ENT_QUOTES, 'UTF-8') ?>"
                                      data-supplier="<?= htmlspecialchars($lic['supplier'], ENT_QUOTES, 'UTF-8') ?>"
                                      data-weight="<?= $lic['weight'] ?>"
                                      data-fob="<?= $lic['fob_declared'] ?>">
                                <?= htmlspecialchars($lic['license_number'], ENT_QUOTES, 'UTF-8') ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback" id="license_id_error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                          <label>PARTIELLE Name <span class="text-danger">*</span></label>
                          <div class="input-group">
                            <span class="input-group-text" id="crf_prefix" style="background-color: #667eea; color: white; font-weight: 600;"></span>
                            <input type="text" name="partial_name_input" id="partial_name_input" class="form-control" placeholder="e.g., PART-001" maxlength="200">
                          </div>
                          <input type="hidden" name="partial_name" id="partial_name">
                          <div class="invalid-feedback" id="partial_name_error"></div>
                          <small class="text-muted">Format: CRF Reference/Number</small>
                        </div>
                      </div>

                      <!-- License Info Display -->
                      <div id="licenseInfoBox" class="license-info-box" style="display:none;">
                        <div class="row">
                          <div class="col-md-3">
                            <div class="info-label">CRF Reference</div>
                            <div class="info-value" id="display_crf">-</div>
                          </div>
                          <div class="col-md-3">
                            <div class="info-label">Supplier</div>
                            <div class="info-value" id="display_supplier">-</div>
                          </div>
                          <div class="col-md-3">
                            <div class="info-label">License Weight</div>
                            <div class="info-value" id="display_weight">0.00 KG</div>
                          </div>
                          <div class="col-md-3">
                            <div class="info-label">License FOB</div>
                            <div class="info-value" id="display_fob">$0.00</div>
                          </div>
                        </div>
                      </div>

                      <!-- AV Fields Section (Only shown in Edit Mode) - ALL VALUES VISIBLE -->
                      <div id="availableFieldsSection" style="display:none;">
                        
                        <!-- LICENSE ORIGINAL VALUES (READ-ONLY) -->
                        <div class="section-header" style="background: linear-gradient(135deg, #3498DB 0%, #2980B9 100%);">
                          <i class="ti ti-file-text me-2"></i>License Original Values (Read-Only)
                        </div>

                        <div class="row mb-4">
                          <div class="col mb-3">
                            <label>License Weight (KG)</label>
                            <input type="number" step="0.01" name="license_weight_display" id="license_weight_display" class="form-control readonly-field" readonly>
                          </div>

                          <div class="col mb-3">
                            <label>License FOB ($)</label>
                            <input type="number" step="0.01" name="license_fob_display" id="license_fob_display" class="form-control readonly-field" readonly>
                          </div>

                          <div class="col mb-3">
                            <label>License Insurance ($)</label>
                            <input type="number" step="0.01" name="license_insurance_display" id="license_insurance_display" class="form-control readonly-field" readonly>
                          </div>

                          <div class="col mb-3">
                            <label>License Freight ($)</label>
                            <input type="number" step="0.01" name="license_freight_display" id="license_freight_display" class="form-control readonly-field" readonly>
                          </div>

                          <div class="col mb-3">
                            <label>License Other Costs ($)</label>
                            <input type="number" step="0.01" name="license_other_costs_display" id="license_other_costs_display" class="form-control readonly-field" readonly>
                          </div>
                        </div>

                        <!-- CUMULATIVE USED VALUES (READ-ONLY) -->
                        <div class="section-header" style="background: linear-gradient(135deg, #F39C12 0%, #E67E22 100%);">
                          <i class="ti ti-chart-bar me-2"></i>Cumulative Used Values (Auto-Updated, Read-Only)
                        </div>

                        <div class="row mb-4">
                          <div class="col mb-3">
                            <label>Used Weight (KG)</label>
                            <input type="number" step="0.01" name="partial_weight_display" id="partial_weight_display" class="form-control readonly-field" readonly>
                          </div>

                          <div class="col mb-3">
                            <label>Used FOB ($)</label>
                            <input type="number" step="0.01" name="partial_fob_display" id="partial_fob_display" class="form-control readonly-field" readonly>
                          </div>

                          <div class="col mb-3">
                            <label>Used Insurance ($)</label>
                            <input type="number" step="0.01" name="partial_insurance_display" id="partial_insurance_display" class="form-control readonly-field" readonly>
                          </div>

                          <div class="col mb-3">
                            <label>Used Freight ($)</label>
                            <input type="number" step="0.01" name="partial_freight_display" id="partial_freight_display" class="form-control readonly-field" readonly>
                          </div>

                          <div class="col mb-3">
                            <label>Used Other Costs ($)</label>
                            <input type="number" step="0.01" name="partial_other_costs_display" id="partial_other_costs_display" class="form-control readonly-field" readonly>
                          </div>
                        </div>

                        <!-- AV BALANCE (EDITABLE) -->
                        <div class="section-header" style="background: linear-gradient(135deg, #9B59B6 0%, #8E44AD 100%);">
                          <i class="ti ti-edit me-2"></i>AV Balance (Editable)
                        </div>
                        <p class="help-text">
                          <i class="ti ti-info-circle me-2"></i>
                          <strong>Note:</strong> Only "AV" fields can be edited. License and Used values are system-managed and read-only.
                        </p>

                        <div class="row">
                          <div class="col mb-3">
                            <label>AV Weight (KG)</label>
                            <input type="number" step="0.01" name="av_weight" id="av_weight" class="form-control" min="0" placeholder="0.00">
                            <div class="invalid-feedback" id="av_weight_error"></div>
                          </div>

                          <div class="col mb-3">
                            <label>AV FOB ($)</label>
                            <input type="number" step="0.01" name="av_fob" id="av_fob" class="form-control" min="0" placeholder="0.00">
                            <div class="invalid-feedback" id="av_fob_error"></div>
                          </div>

                          <div class="col mb-3">
                            <label>AV Insurance ($)</label>
                            <input type="number" step="0.01" name="av_insurance" id="av_insurance" class="form-control" min="0" placeholder="0.00">
                            <div class="invalid-feedback" id="av_insurance_error"></div>
                          </div>

                          <div class="col mb-3">
                            <label>AV Freight ($)</label>
                            <input type="number" step="0.01" name="av_freight" id="av_freight" class="form-control" min="0" placeholder="0.00">
                            <div class="invalid-feedback" id="av_freight_error"></div>
                          </div>

                          <div class="col mb-3">
                            <label>AV Other Costs ($)</label>
                            <input type="number" step="0.01" name="av_other_costs" id="av_other_costs" class="form-control" min="0" placeholder="0.00">
                            <div class="invalid-feedback" id="av_other_costs_error"></div>
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
                    <i class="ti ti-check me-1"></i> <span id="submitBtnText">Save PARTIELLE</span>
                  </button>
                </div>
              </div>

            </form>
          </div>
        </div>

        <!-- PARTIELLE DataTable -->
        <div class="card shadow-sm">
          <div class="card-header border-bottom border-dashed">
            <h4 class="header-title mb-0"><i class="ti ti-list me-2"></i> PARTIELLE List</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="partielleTable" class="table table-striped table-bordered dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>PARTIELLE Name</th>
                    <th>License</th>
                    <th>CRF Ref</th>
                    <th>Supplier</th>
                    <th>Used Weight</th>
                    <th>Used FOB</th>
                    <th>Imports</th>
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
<div class="modal fade" id="viewPartielleModal" tabindex="-1" aria-labelledby="viewPartielleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewPartielleModalLabel">
          <i class="ti ti-eye me-2"></i> PARTIELLE Details
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function () {

    // CSRF Token
    const csrfToken = $('#csrf_token').val();

    // Date formatting helper
    function formatDateToDDMMYYYY(dateStr) {
      if (!dateStr) return '';
      const date = new Date(dateStr);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      return `${day}-${month}-${year}`;
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

      const licenseId = $('#license_id').val();
      const partialName = $('#partial_name').val();

      if (!licenseId || licenseId === '') {
        showFieldError('license_id', 'Please select License (Required)');
        errors.push('License is required');
        hasError = true;
      }

      if (!partialName || partialName === '') {
        showFieldError('partial_name_input', 'Please enter PARTIELLE name (Required)');
        errors.push('PARTIELLE name is required');
        hasError = true;
      }

      return {
        isValid: !hasError,
        errors: errors
      };
    }

    // ===== REAL-TIME VALIDATION =====
    
    $('#license_id').on('change', function() {
      const value = $(this).val();
      if (!value || value === '') {
        $(this).addClass('is-invalid');
        $('#license_id_error').text('Please select License (Required)').show();
      } else {
        $(this).removeClass('is-invalid');
        $('#license_id_error').text('').hide();
      }
    });

    $('#partial_name_input').on('input', function() {
      const value = $(this).val().trim();
      if (!value || value === '') {
        $(this).addClass('is-invalid');
        $('#partial_name_error').text('Please enter PARTIELLE name (Required)').show();
      } else {
        $(this).removeClass('is-invalid');
        $('#partial_name_error').text('').hide();
      }
    });

    // ===== LICENSE CHANGE - Update Prefix & Display Info =====

    $('#license_id').on('change', function() {
      const selectedOption = $(this).find('option:selected');
      const licenseId = $(this).val();
      const crfRef = selectedOption.data('crf') || '';
      const supplier = selectedOption.data('supplier') || '';
      const weight = selectedOption.data('weight') || 0;
      const fob = selectedOption.data('fob') || 0;

      if (!licenseId) {
        $('#crf_prefix').text('');
        $('#licenseInfoBox').hide();
        $('#partial_name').val('');
        return;
      }

      // Update prefix
      $('#crf_prefix').text(crfRef + '/');

      // Display license info
      $('#display_crf').text(crfRef);
      $('#display_supplier').text(supplier);
      $('#display_weight').text(parseFloat(weight).toFixed(2) + ' KG');
      $('#display_fob').text('$' + parseFloat(fob).toFixed(2));
      $('#licenseInfoBox').show();

      // Update hidden partial_name field
      const input = $('#partial_name_input').val().trim();
      if (input) {
        $('#partial_name').val(crfRef + '/' + input);
      }
    });

    // ===== PARTIELLE NAME INPUT - Auto-combine with CRF =====

    $('#partial_name_input').on('input', function() {
      const crfRef = $('#license_id option:selected').data('crf') || '';
      const input = $(this).val().trim();
      
      if (crfRef && input) {
        $('#partial_name').val(crfRef + '/' + input);
      } else {
        $('#partial_name').val('');
      }
    });

    // Initialize DataTable
    var partielleTable;
    function initDataTable() {
      if ($.fn.DataTable.isDataTable('#partielleTable')) {
        $('#partielleTable').DataTable().destroy();
      }

      partielleTable = $('#partielleTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '<?= APP_URL ?>/bivac/crudData/listing',
          type: 'GET',
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
          { data: 'partial_name' },
          { data: 'license_number' },
          { data: 'ref_cod' },
          { data: 'supplier' },
          { 
            data: 'partial_weight',
            render: function(data, type, row) {
              return '<span class="badge bg-warning">' + parseFloat(data || 0).toFixed(2) + ' KG</span>';
            }
          },
          { 
            data: 'partial_fob',
            render: function(data, type, row) {
              return '<span class="badge bg-success">$' + parseFloat(data || 0).toFixed(2) + '</span>';
            }
          },
          { 
            data: 'import_count',
            render: function(data, type, row) {
              const count = data || 0;
              let badgeClass = count > 0 ? 'bg-info' : 'bg-secondary';
              return `<span class="badge ${badgeClass}">${count}</span>`;
            }
          },
          {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              return `
                <button class="btn btn-sm btn-view viewBtn" data-id="${row.id}" title="View Details">
                  <i class="ti ti-eye"></i>
                </button>
                <button class="btn btn-sm btn-primary editBtn" data-id="${row.id}" title="Edit">
                  <i class="ti ti-edit"></i>
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
          emptyTable: 'No PARTIELLE found',
          zeroRecords: 'No matching PARTIELLE found'
        }
      });
    }

    // Export All Button Handler
    $('#exportAllBtn').on('click', function() {
      Swal.fire({
        title: 'Exporting...',
        text: 'Please wait while we prepare your Excel export',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      const exportUrl = '<?= APP_URL ?>/bivac/crudData/exportAll';
      window.location.href = exportUrl;
      
      setTimeout(function() {
        Swal.close();
      }, 2000);
    });

    // View PARTIELLE details in modal
    $(document).on('click', '.viewBtn', function () {
      const id = $(this).data('id');
      
      Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
      
      $.ajax({
        url: '<?= APP_URL ?>/bivac/crudData/getPartielle',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
          Swal.close();
          
          if (res.success && res.data) {
            const data = res.data;
            let detailsHtml = `
              <div class="row p-3">
                <div class="col-12 mb-3">
                  <h5 class="text-center mb-4" style="color: #667eea;">${data.partial_name}</h5>
                </div>
              </div>

              <!-- License Information -->
              <div class="p-3">
                <h6 class="mb-3"><i class="ti ti-info-circle me-2"></i>License Information</h6>
                <div class="row mb-4">
                  <div class="col-md-4 mb-2">
                    <div class="value-box value-box-license">
                      <div class="value-label">License Number</div>
                      <div class="value-number">${data.license_number || 'N/A'}</div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-2">
                    <div class="value-box value-box-license">
                      <div class="value-label">CRF Reference</div>
                      <div class="value-number">${data.ref_cod || 'N/A'}</div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-2">
                    <div class="value-box value-box-license">
                      <div class="value-label">Supplier</div>
                      <div class="value-number" style="font-size: 1rem;">${data.supplier || 'N/A'}</div>
                    </div>
                  </div>
                </div>

                <!-- License Original Values -->
                <h6 class="mb-3"><i class="ti ti-file-text me-2"></i>License Original Values (Read-Only)</h6>
                <div class="row mb-4">
                  <div class="col mb-2">
                    <div class="value-box value-box-license">
                      <div class="value-label">Weight</div>
                      <div class="value-number">${parseFloat(data.license_weight || 0).toFixed(2)} KG</div>
                    </div>
                  </div>
                  <div class="col mb-2">
                    <div class="value-box value-box-license">
                      <div class="value-label">FOB</div>
                      <div class="value-number">$${parseFloat(data.license_fob || 0).toFixed(2)}</div>
                    </div>
                  </div>
                  <div class="col mb-2">
                    <div class="value-box value-box-license">
                      <div class="value-label">Insurance</div>
                      <div class="value-number">$${parseFloat(data.license_insurance || 0).toFixed(2)}</div>
                    </div>
                  </div>
                  <div class="col mb-2">
                    <div class="value-box value-box-license">
                      <div class="value-label">Freight</div>
                      <div class="value-number">$${parseFloat(data.license_freight || 0).toFixed(2)}</div>
                    </div>
                  </div>
                  <div class="col mb-2">
                    <div class="value-box value-box-license">
                      <div class="value-label">Other Costs</div>
                      <div class="value-number">$${parseFloat(data.license_other_costs || 0).toFixed(2)}</div>
                    </div>
                  </div>
                </div>

                <!-- Cumulative Used Values -->
                <h6 class="mb-3"><i class="ti ti-chart-bar me-2"></i>Cumulative Used Values (Auto-Updated)</h6>
                <div class="row mb-4">
                  <div class="col mb-2">
                    <div class="value-box value-box-used">
                      <div class="value-label">Used Weight</div>
                      <div class="value-number">${parseFloat(data.partial_weight || 0).toFixed(2)} KG</div>
                    </div>
                  </div>
                  <div class="col mb-2">
                    <div class="value-box value-box-used">
                      <div class="value-label">Used FOB</div>
                      <div class="value-number">$${parseFloat(data.partial_fob || 0).toFixed(2)}</div>
                    </div>
                  </div>
                  <div class="col mb-2">
                    <div class="value-box value-box-used">
                      <div class="value-label">Used Insurance</div>
                      <div class="value-number">$${parseFloat(data.partial_insurance || 0).toFixed(2)}</div>
                    </div>
                  </div>
                  <div class="col mb-2">
                    <div class="value-box value-box-used">
                      <div class="value-label">Used Freight</div>
                      <div class="value-number">$${parseFloat(data.partial_freight || 0).toFixed(2)}</div>
                    </div>
                  </div>
                  <div class="col mb-2">
                    <div class="value-box value-box-used">
                      <div class="value-label">Used Other Costs</div>
                      <div class="value-number">$${parseFloat(data.partial_other_costs || 0).toFixed(2)}</div>
                    </div>
                  </div>
                </div>

                <!-- AV Balance -->
                <h6 class="mb-3"><i class="ti ti-wallet me-2"></i>AV Balance (Manual Entry)</h6>
                <div class="row mb-4">
                  <div class="col mb-2">
                    <div class="value-box value-box-available">
                      <div class="value-label">AV Weight</div>
                      <div class="value-number">${parseFloat(data.av_weight || 0).toFixed(2)} KG</div>
                    </div>
                  </div>
                  <div class="col mb-2">
                    <div class="value-box value-box-available">
                      <div class="value-label">AV FOB</div>
                      <div class="value-number">$${parseFloat(data.av_fob || 0).toFixed(2)}</div>
                    </div>
                  </div>
                  <div class="col mb-2">
                    <div class="value-box value-box-available">
                      <div class="value-label">AV Insurance</div>
                      <div class="value-number">$${parseFloat(data.av_insurance || 0).toFixed(2)}</div>
                    </div>
                  </div>
                  <div class="col mb-2">
                    <div class="value-box value-box-available">
                      <div class="value-label">AV Freight</div>
                      <div class="value-number">$${parseFloat(data.av_freight || 0).toFixed(2)}</div>
                    </div>
                  </div>
                  <div class="col mb-2">
                    <div class="value-box value-box-available">
                      <div class="value-label">AV Other Costs</div>
                      <div class="value-number">$${parseFloat(data.av_other_costs || 0).toFixed(2)}</div>
                    </div>
                  </div>
                </div>

                <!-- Timestamps -->
                <div class="row">
                  <div class="col-md-6">
                    <small class="text-muted"><i class="ti ti-calendar me-1"></i>Created: ${formatDateToDDMMYYYY(data.created_at)}</small>
                  </div>
                  <div class="col-md-6 text-end">
                    <small class="text-muted"><i class="ti ti-calendar me-1"></i>Updated: ${data.updated_at ? formatDateToDDMMYYYY(data.updated_at) : 'N/A'}</small>
                  </div>
                </div>
              </div>
            `;
            
            $('#modalDetailsContent').html(detailsHtml);
            $('#viewPartielleModal').modal('show');
          } else {
            Swal.fire('Error', res.message || 'Failed to load PARTIELLE data', 'error');
          }
        },
        error: function () {
          Swal.close();
          Swal.fire('Error', 'Failed to load PARTIELLE data. Please try again.', 'error');
        }
      });
    });

    // Form submission
    $('#partielleForm').on('submit', function (e) {
      e.preventDefault();

      const validation = validateForm();
      
      if (!validation.isValid) {
        $('#partielleInfo').collapse('show');
        
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
        url: '<?= APP_URL ?>/bivac/crudData/' + action,
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
            if (typeof partielleTable !== 'undefined') {
              partielleTable.ajax.reload(null, false);
            }
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
      $('#partielleForm')[0].reset();
      clearValidationErrors();
      $('#partielle_id').val('');
      $('#license_id').val('').prop('disabled', false);
      $('#partial_name_input').val('').prop('disabled', false);
      $('#partial_name').val('');
      $('#formAction').val('insert');
      $('#formTitle').text('Add New PARTIELLE');
      $('#submitBtnText').text('Save PARTIELLE');
      $('#resetFormBtn').hide();
      $('#crf_prefix').text('');
      $('#licenseInfoBox').hide();
      $('#availableFieldsSection').hide();
      
      // Clear all display fields
      $('#license_weight_display').val('');
      $('#license_fob_display').val('');
      $('#license_insurance_display').val('');
      $('#license_freight_display').val('');
      $('#license_other_costs_display').val('');
      $('#partial_weight_display').val('');
      $('#partial_fob_display').val('');
      $('#partial_insurance_display').val('');
      $('#partial_freight_display').val('');
      $('#partial_other_costs_display').val('');
      
      $('#partielleInfo').collapse('hide');

      $('html, body').animate({ scrollTop: $('#partielleForm').offset().top - 100 }, 200);
    }

    $('#cancelBtn, #resetFormBtn').on('click', function (e) {
      e.preventDefault();
      resetForm();
    });

    // Edit PARTIELLE - POPULATE ALL 15 FIELDS
    $(document).on('click', '.editBtn', function () {
      const id = $(this).data('id');
      
      Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
      
      $.ajax({
        url: '<?= APP_URL ?>/bivac/crudData/getPartielle',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
          Swal.close();
          
          if (res.success && res.data) {
            const data = res.data;

            clearValidationErrors();

            $('#partielle_id').val(data.id);
            $('#formAction').val('update');
            $('#formTitle').text('Edit PARTIELLE');
            $('#submitBtnText').text('Update PARTIELLE');
            $('#resetFormBtn').show();

            // Set license (disabled for edit)
            $('#license_id').val(data.license_id).prop('disabled', true);
            
            // Display license info
            $('#display_crf').text(data.ref_cod || '');
            $('#display_supplier').text(data.supplier || '');
            $('#display_weight').text(parseFloat(data.license_weight || 0).toFixed(2) + ' KG');
            $('#display_fob').text('$' + parseFloat(data.license_fob || 0).toFixed(2));
            $('#licenseInfoBox').show();

            // PARTIELLE name (disabled for edit)
            $('#crf_prefix').text((data.ref_cod || '') + '/');
            const nameParts = data.partial_name.split('/');
            $('#partial_name_input').val(nameParts[nameParts.length - 1]).prop('disabled', true);
            $('#partial_name').val(data.partial_name);

            // Populate LICENSE ORIGINAL VALUES (read-only)
            $('#license_weight_display').val(parseFloat(data.license_weight || 0).toFixed(2));
            $('#license_fob_display').val(parseFloat(data.license_fob || 0).toFixed(2));
            $('#license_insurance_display').val(parseFloat(data.license_insurance || 0).toFixed(2));
            $('#license_freight_display').val(parseFloat(data.license_freight || 0).toFixed(2));
            $('#license_other_costs_display').val(parseFloat(data.license_other_costs || 0).toFixed(2));

            // Populate CUMULATIVE USED VALUES (read-only)
            $('#partial_weight_display').val(parseFloat(data.partial_weight || 0).toFixed(2));
            $('#partial_fob_display').val(parseFloat(data.partial_fob || 0).toFixed(2));
            $('#partial_insurance_display').val(parseFloat(data.partial_insurance || 0).toFixed(2));
            $('#partial_freight_display').val(parseFloat(data.partial_freight || 0).toFixed(2));
            $('#partial_other_costs_display').val(parseFloat(data.partial_other_costs || 0).toFixed(2));

            // Populate AV BALANCE (editable)
            $('#av_weight').val(parseFloat(data.av_weight || 0).toFixed(2));
            $('#av_fob').val(parseFloat(data.av_fob || 0).toFixed(2));
            $('#av_insurance').val(parseFloat(data.av_insurance || 0).toFixed(2));
            $('#av_freight').val(parseFloat(data.av_freight || 0).toFixed(2));
            $('#av_other_costs').val(parseFloat(data.av_other_costs || 0).toFixed(2));
            
            $('#availableFieldsSection').show();

            $('#partielleInfo').collapse('show');

            $('html, body').animate({ scrollTop: $('#partielleForm').offset().top - 100 }, 500);
          } else {
            Swal.fire('Error', res.message || 'Failed to load PARTIELLE data', 'error');
          }
        },
        error: function () {
          Swal.close();
          Swal.fire('Error', 'Failed to load PARTIELLE data. Please try again.', 'error');
        }
      });
    });

    // Delete PARTIELLE
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
            url: '<?= APP_URL ?>/bivac/crudData/deletion',
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
                partielleTable.ajax.reload(null, false);
              } else {
                Swal.fire('Error', res.message || 'Delete failed', 'error');
              }
            },
            error: function (xhr) {
              let errorMsg = 'Failed to delete PARTIELLE';
              
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
  });
</script>