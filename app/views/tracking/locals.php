<!-- include any head / css you already have -->
<link href="<?= BASE_URL ?>/assets/pages/css/local_styles.css" rel="stylesheet" type="text/css">

<style>
  .dataTables_wrapper .dataTables_info {
    float: left;
  }
  .dataTables_wrapper .dataTables_paginate {
    float: right;
    text-align: right;
  }
  
  /* ✅ UPDATED: Export Button Styling - Green */
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
  
  .stats-card-location {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

  /* Auto-generated field styling */
  .auto-generated-field {
    background-color: #f8f9fa;
    cursor: not-allowed;
  }

  /* Filter section */
  .filter-section {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
  }
</style>

<div class="page-content">
  <div class="page-container">
    <div class="row">
      <div class="col-12">
        
        <!-- Statistics Cards -->
        <div class="row mb-4" id="statsCardsContainer">
          <!-- Total Tracking Card -->
          <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card stats-card-1 shadow-sm" data-location="0">
              <div class="card-body position-relative">
                <i class="ti ti-truck-delivery stats-icon"></i>
                <div class="stats-value" id="totalTracking">0</div>
                <div class="stats-label">Total Tracking</div>
              </div>
            </div>
          </div>
          
          <!-- Location Cards will be dynamically added here -->
          
        </div>

        <!-- Local Form Card -->
        <div class="card shadow-sm mb-4">
          <!-- ✅ UPDATED: Card Header with Export Button -->
          <div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
            <h4 class="header-title mb-0">
              <i class="ti ti-truck-delivery me-2"></i> 
              <span id="formTitle">Add New Local</span>
            </h4>
            <div class="d-flex gap-2">
              <!-- ✅ NEW: Export All Button Positioned Here -->
              <button type="button" class="btn btn-export-all" id="exportAllBtn">
                <i class="ti ti-file-spreadsheet me-1"></i> Export All to Excel
              </button>
              <button type="button" class="btn btn-sm btn-secondary" id="resetFormBtn" style="display:none;">
                <i class="ti ti-plus"></i> Add New
              </button>
            </div>
          </div>

          <div class="card-body">
            <form id="localForm" method="post" novalidate>
              <!-- CSRF Token -->
              <input type="hidden" name="csrf_token" id="csrf_token" value="<?= $csrf_token ?>">
              <input type="hidden" name="local_id" id="local_id" value="">
              <input type="hidden" name="action" id="formAction" value="insert">

              <div class="accordion" id="localAccordion">
                
                <!-- LOCAL TRACKING -->
                <div class="accordion-item mb-3">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#localTracking">
                      <i class="ti ti-truck-delivery me-2"></i> Local Tracking
                    </button>
                  </h2>

                  <div id="localTracking" class="accordion-collapse collapse" data-bs-parent="#localAccordion">
                    <div class="accordion-body row">

                      <div class="col-md-3 mb-3">
                        <label>Client <span class="text-danger">*</span></label>
                        <select name="client_id" id="client_id" class="form-select" required>
                          <option value="">-- Select Client --</option>
                          <?php foreach ($clients as $client): ?>
                            <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['short_name']) ?></option>
                          <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback" id="client_id_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>Location <span class="text-danger">*</span></label>
                        <select name="location" id="location" class="form-select" required>
                          <option value="">-- Select Location --</option>
                          <?php foreach ($locations as $loc): ?>
                            <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['main_location_name']) ?></option>
                          <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback" id="location_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>LT Reference <small class="text-muted">(Auto-generated)</small></label>
                        <input type="text" name="lt_reference" id="lt_reference" class="form-control auto-generated-field" readonly placeholder="Auto-generated">
                        <div class="invalid-feedback" id="lt_reference_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>N° PV Transfer</label>
                        <input type="text" name="pv_transfer_number" id="pv_transfer_number" class="form-control" maxlength="100">
                        <div class="invalid-feedback" id="pv_transfer_number_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>N° Attestation</label>
                        <input type="text" name="attestation_number" id="attestation_number" class="form-control" maxlength="100">
                        <div class="invalid-feedback" id="attestation_number_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>Date PV</label>
                        <input type="date" name="date_pv" id="date_pv" class="form-control">
                        <div class="invalid-feedback" id="date_pv_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>LOT No</label>
                        <input type="text" name="lot_number" id="lot_number" class="form-control" maxlength="100">
                        <div class="invalid-feedback" id="lot_number_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>Horse</label>
                        <input type="text" name="horse" id="horse" class="form-control" maxlength="100">
                        <div class="invalid-feedback" id="horse_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>Trailer</label>
                        <input type="text" name="trailer" id="trailer" class="form-control" maxlength="100">
                        <div class="invalid-feedback" id="trailer_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>Trailer 2</label>
                        <input type="text" name="trailer_2" id="trailer_2" class="form-control" maxlength="100">
                        <div class="invalid-feedback" id="trailer_2_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>Capacity–T</label>
                        <input type="number" step="0.01" name="capacity_t" id="capacity_t" class="form-control" min="0" max="999999.99">
                        <div class="invalid-feedback" id="capacity_t_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>Bags</label>
                        <input type="number" name="bags" id="bags" class="form-control" min="0" max="999999">
                        <div class="invalid-feedback" id="bags_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>CEE In</label>
                        <input type="date" name="cee_in" id="cee_in" class="form-control">
                        <div class="invalid-feedback" id="cee_in_error"></div>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label>CEE Out</label>
                        <input type="date" name="cee_out" id="cee_out" class="form-control">
                        <div class="invalid-feedback" id="cee_out_error"></div>
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
                    <i class="ti ti-check me-1"></i> <span id="submitBtnText">Save Local</span>
                  </button>
                </div>
              </div>

            </form>
          </div>
        </div>

        <!-- Locals DataTable -->
        <div class="card shadow-sm">
          <div class="card-header border-bottom border-dashed">
            <h4 class="header-title mb-0"><i class="ti ti-list me-2"></i> Locals List</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="localsTable" class="table table-striped table-bordered dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Location</th>
                    <th>LT Reference</th>
                    <th>PV Transfer No</th>
                    <th>Attestation No</th>
                    <th>Date PV</th>
                    <th>LOT No</th>
                    <th>Horse</th>
                    <th>Capacity–T</th>
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
<div class="modal fade" id="viewLocalModal" tabindex="-1" aria-labelledby="viewLocalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewLocalModalLabel">
          <i class="ti ti-eye me-2"></i> Local Details
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

<script>
  $(document).ready(function () {

    // CSRF Token
    const csrfToken = $('#csrf_token').val();
    
    // Current location filter
    let currentLocationFilter = 0;

    // ===== AUTO-GENERATE LT REFERENCE =====
    $('#client_id, #location').on('change', function() {
      generateLTReference();
    });

    function generateLTReference() {
      const formAction = $('#formAction').val();
      
      if (formAction === 'update') {
        return;
      }

      const clientId = $('#client_id').val();
      const locationId = $('#location').val();
      
      if (!clientId || !locationId) {
        $('#lt_reference').val('');
        return;
      }

      const clientText = $('#client_id option:selected').text();
      const clientAbbrev = clientText.substring(0, 3).toUpperCase().replace(/[^A-Z]/g, '');
      
      const locationText = $('#location option:selected').text();
      const locationPrefix = locationText.substring(0, 2).toUpperCase().replace(/[^A-Z]/g, '');
      
      const year = new Date().getFullYear().toString().slice(-2);
      
      $.ajax({
        url: '<?= APP_URL ?>/local/crudData/getNextLTSequence',
        method: 'POST',
        data: {
          csrf_token: csrfToken,
          client_id: clientId,
          location_id: locationId,
          year: year
        },
        dataType: 'json',
        success: function(res) {
          if (res.success) {
            const sequence = String(res.sequence).padStart(4, '0');
            const ltReference = `${clientAbbrev}-LT${locationPrefix}${year}-${sequence}`;
            $('#lt_reference').val(ltReference);
          }
        },
        error: function() {
          console.error('Failed to generate LT Reference');
        }
      });
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

      const clientId = $('#client_id').val();
      const locationId = $('#location').val();

      if (!clientId || clientId === '') {
        showFieldError('client_id', 'Please select a client (Required)');
        errors.push('Client selection is required');
        hasError = true;
      }

      if (!locationId || locationId === '') {
        showFieldError('location', 'Please select a location (Required)');
        errors.push('Location selection is required');
        hasError = true;
      }

      return {
        isValid: !hasError,
        errors: errors
      };
    }

    // ===== REAL-TIME VALIDATION =====
    
    $('#client_id').on('change', function() {
      const value = $(this).val();
      if (!value || value === '') {
        $(this).addClass('is-invalid');
        $('#client_id_error').text('Please select a client (Required)').show();
      } else {
        $(this).removeClass('is-invalid');
        $('#client_id_error').text('').hide();
      }
    });

    $('#location').on('change', function() {
      const value = $(this).val();
      if (!value || value === '') {
        $(this).addClass('is-invalid');
        $('#location_error').text('Please select a location (Required)').show();
      } else {
        $(this).removeClass('is-invalid');
        $('#location_error').text('').hide();
      }
    });

    // Date formatting helper function
    function formatDateToDDMMYYYY(dateStr) {
      if (!dateStr) return '';
      
      if (dateStr.match(/^\d{2}-\d{2}-\d{4}$/)) {
        return dateStr;
      }
      
      const parts = dateStr.split('-');
      if (parts.length === 3) {
        return `${parts[2]}-${parts[1]}-${parts[0]}`;
      }
      return dateStr;
    }

    // Initialize DataTable
    var localsTable;
    function initDataTable() {
      if ($.fn.DataTable.isDataTable('#localsTable')) {
        $('#localsTable').DataTable().destroy();
      }

      localsTable = $('#localsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '<?= APP_URL ?>/local/crudData/listing',
          type: 'GET',
          data: function(d) {
            d.location_filter = currentLocationFilter;
          },
          error: function(xhr, error, code) {
            console.error('DataTable error:', error, code);
            Swal.fire('Error', 'Failed to load data', 'error');
          }
        },
        columns: [
          { data: 'id' },
          { data: 'client_name' },
          { data: 'location_name' },
          { data: 'lt_reference' },
          { data: 'pv_transfer_number' },
          { data: 'attestation_number' },
          { 
            data: 'date_pv',
            render: function(data, type, row) {
              if (type === 'display' || type === 'filter') {
                return formatDateToDDMMYYYY(data);
              }
              return data;
            }
          },
          { data: 'lot_number' },
          { data: 'horse' },
          { 
            data: 'capacity_t',
            render: function(data, type, row) {
              return data ? parseFloat(data).toFixed(2) : '0.00';
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
                <button class="btn btn-sm btn-export exportBtn" data-id="${row.id}" data-lt="${row.lt_reference}" title="Export to Excel">
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
        drawCallback: function() {
          updateStatistics();
        }
      });
    }

    // ✅ NEW: Export All Button Handler
    $('#exportAllBtn').on('click', function() {
      Swal.fire({
        title: 'Exporting...',
        text: 'Please wait while we prepare your export',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      const exportUrl = '<?= APP_URL ?>/local/crudData/exportAll';
      window.location.href = exportUrl;
      
      setTimeout(function() {
        Swal.close();
      }, 2000);
    });

    // Export individual local
    $(document).on('click', '.exportBtn', function () {
      const id = $(this).data('id');
      const exportUrl = '<?= APP_URL ?>/local/crudData/exportLocal?id=' + id;
      window.location.href = exportUrl;
    });

    // Update Statistics Cards
    function updateStatistics() {
      $.ajax({
        url: '<?= APP_URL ?>/local/crudData/statistics',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
          if (res.success) {
            $('#totalTracking').text(res.data.total_tracking || 0);
            
            let locationCardsHtml = '';
            if (res.data.location_counts && res.data.location_counts.length > 0) {
              res.data.location_counts.forEach((loc, index) => {
                const gradients = [
                  'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                  'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                  'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                  'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'
                ];
                const gradient = gradients[index % gradients.length];
                
                locationCardsHtml += `
                  <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card stats-card stats-card-location shadow-sm" data-location="${loc.id}" style="background: ${gradient};">
                      <div class="card-body position-relative">
                        <i class="ti ti-map-pin stats-icon"></i>
                        <div class="stats-value">${loc.file_count || 0}</div>
                        <div class="stats-label">${loc.main_location_name}</div>
                      </div>
                    </div>
                  </div>
                `;
              });
            }
            
            $('.stats-card-1').parent().nextAll().remove();
            $('.stats-card-1').parent().after(locationCardsHtml);
          }
        },
        error: function() {
          console.error('Failed to load statistics');
        }
      });
    }

    // Handle stats card click for filtering
    $(document).on('click', '.stats-card', function() {
      const locationId = $(this).data('location');
      
      $('.stats-card').removeClass('active');
      $(this).addClass('active');
      
      currentLocationFilter = locationId;
      
      if (typeof localsTable !== 'undefined') {
        localsTable.ajax.reload();
      }
    });

    // View local details in modal
    $(document).on('click', '.viewBtn', function () {
      const id = $(this).data('id');
      $.ajax({
        url: '<?= APP_URL ?>/local/crudData/getLocal',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
          if (res.success && res.data) {
            const local = res.data;
            let detailsHtml = `
              <div class="detail-row">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-building detail-icon"></i>Client
                    </div>
                    <div class="detail-value">${local.client_name || 'N/A'}</div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-map-pin detail-icon"></i>Location
                    </div>
                    <div class="detail-value">${local.location_name || 'N/A'}</div>
                  </div>
                </div>
              </div>
              
              <div class="detail-row">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-file-text detail-icon"></i>LT Reference
                    </div>
                    <div class="detail-value">${local.lt_reference || 'N/A'}</div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-file-invoice detail-icon"></i>PV Transfer Number
                    </div>
                    <div class="detail-value">${local.pv_transfer_number || 'N/A'}</div>
                  </div>
                </div>
              </div>
              
              <div class="detail-row">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-certificate detail-icon"></i>Attestation Number
                    </div>
                    <div class="detail-value">${local.attestation_number || 'N/A'}</div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-calendar detail-icon"></i>Date PV
                    </div>
                    <div class="detail-value">${formatDateToDDMMYYYY(local.date_pv) || 'N/A'}</div>
                  </div>
                </div>
              </div>
              
              <div class="detail-row">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-package detail-icon"></i>LOT Number
                    </div>
                    <div class="detail-value">${local.lot_number || 'N/A'}</div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-horse detail-icon"></i>Horse
                    </div>
                    <div class="detail-value">${local.horse || 'N/A'}</div>
                  </div>
                </div>
              </div>
              
              <div class="detail-row">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-truck detail-icon"></i>Trailer
                    </div>
                    <div class="detail-value">${local.trailer || 'N/A'}</div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-truck detail-icon"></i>Trailer 2
                    </div>
                    <div class="detail-value">${local.trailer_2 || 'N/A'}</div>
                  </div>
                </div>
              </div>
              
              <div class="detail-row">
                <div class="row">
                  <div class="col-md-4">
                    <div class="detail-label">
                      <i class="ti ti-weight detail-icon"></i>Capacity (T)
                    </div>
                    <div class="detail-value">${local.capacity_t ? parseFloat(local.capacity_t).toFixed(2) : 'N/A'}</div>
                  </div>
                  <div class="col-md-4">
                    <div class="detail-label">
                      <i class="ti ti-shopping-bag detail-icon"></i>Bags
                    </div>
                    <div class="detail-value">${local.bags || 'N/A'}</div>
                  </div>
                  <div class="col-md-4">
                    <div class="detail-label">
                      <i class="ti ti-id detail-icon"></i>ID
                    </div>
                    <div class="detail-value">${local.id || 'N/A'}</div>
                  </div>
                </div>
              </div>
              
              <div class="detail-row">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-login detail-icon"></i>CEE In
                    </div>
                    <div class="detail-value">${formatDateToDDMMYYYY(local.cee_in) || 'N/A'}</div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-logout detail-icon"></i>CEE Out
                    </div>
                    <div class="detail-value">${formatDateToDDMMYYYY(local.cee_out) || 'N/A'}</div>
                  </div>
                </div>
              </div>
            `;
            
            $('#modalDetailsContent').html(detailsHtml);
            $('#viewLocalModal').modal('show');
          } else {
            Swal.fire('Error', res.message || 'Failed to load local data', 'error');
          }
        },
        error: function () {
          Swal.fire('Error', 'Failed to load local data', 'error');
        }
      });
    });

    // Form submission
    $('#localForm').on('submit', function (e) {
      e.preventDefault();

      const validation = validateForm();
      
      if (!validation.isValid) {
        $('#localTracking').collapse('show');
        
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
        url: '<?= APP_URL ?>/local/crudData/' + action,
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
            if (typeof localsTable !== 'undefined') {
              localsTable.ajax.reload(null, false);
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
      $('#localForm')[0].reset();
      clearValidationErrors();
      $('#local_id').val('');
      $('#formAction').val('insert');
      $('#formTitle').text('Add New Local');
      $('#submitBtnText').text('Save Local');
      $('#resetFormBtn').hide();
      $('#lt_reference').val('').prop('readonly', true).addClass('auto-generated-field');

      $('#localTracking').collapse('hide');
      
      $('html, body').animate({ scrollTop: $('#localForm').offset().top - 100 }, 200);
    }

    $('#cancelBtn, #resetFormBtn').on('click', function (e) {
      e.preventDefault();
      resetForm();
    });

    // Edit local
    $(document).on('click', '.editBtn', function () {
      const id = $(this).data('id');
      $.ajax({
        url: '<?= APP_URL ?>/local/crudData/getLocal',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
          if (res.success && res.data) {
            const local = res.data;

            clearValidationErrors();

            $('#local_id').val(local.id);
            $('#formAction').val('update');
            $('#formTitle').text('Edit Local');
            $('#submitBtnText').text('Update Local');
            $('#resetFormBtn').show();

            $('#client_id').val(local.client_id || '');
            $('#location').val(local.location_id || '');
            $('#lt_reference').val(local.lt_reference || '').prop('readonly', true);
            $('#pv_transfer_number').val(local.pv_transfer_number || '');
            $('#attestation_number').val(local.attestation_number || '');
            $('#lot_number').val(local.lot_number || '');
            $('#horse').val(local.horse || '');
            $('#trailer').val(local.trailer || '');
            $('#trailer_2').val(local.trailer_2 || '');
            $('#capacity_t').val(local.capacity_t || '');
            $('#bags').val(local.bags || '');
            
            $('#date_pv').val(local.date_pv || '');
            $('#cee_in').val(local.cee_in || '');
            $('#cee_out').val(local.cee_out || '');

            $('#localTracking').collapse('show');
            $('html, body').animate({ scrollTop: $('#localForm').offset().top - 100 }, 500);
          } else {
            Swal.fire('Error', res.message || 'Failed to load local data', 'error');
          }
        },
        error: function () {
          Swal.fire('Error', 'Failed to load local data', 'error');
        }
      });
    });

    // Delete local
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
            url: '<?= APP_URL ?>/local/crudData/deletion',
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
                localsTable.ajax.reload(null, false);
                updateStatistics();
              } else {
                Swal.fire('Error', res.message || 'Delete failed', 'error');
              }
            },
            error: function (xhr) {
              let errorMsg = 'Failed to delete local';
              
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