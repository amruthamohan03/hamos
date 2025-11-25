<!-- include any head / css you already have -->
<link href="<?= BASE_URL ?>/assets/pages/css/client_styles.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
  .section-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    margin-top: 30px;
    margin-bottom: 20px;
    font-weight: 600;
    font-size: 1.1rem;
  }
  
  .section-header:first-child {
    margin-top: 0;
  }

  .accordion-button:not(.collapsed) {
    background-color: #667eea;
    color: white;
  }

  .dataTables_wrapper .dataTables_filter {
    float: right;
    text-align: right;
  }

  .dataTables_wrapper .dt-buttons {
    float: none;
    display: inline-block;
    margin-left: 10px;
  }

  .buttons-excel {
    background: #28a745 !important;
    color: white !important;
    border: none !important;
    padding: 6px 12px !important;
    border-radius: 4px !important;
    font-weight: 500 !important;
    transition: all 0.3s !important;
    box-shadow: none !important;
    font-size: 0.875rem !important;
  }

  .buttons-excel:hover {
    background: #218838 !important;
    color: white !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4) !important;
  }

  .btn-export {
    background: #28a745;
    color: white;
    border: none;
  }

  .btn-export:hover {
    background: #218838;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
  }

  /* 5 COLUMNS PER ROW */
  .col-5-per-row {
    flex: 0 0 auto;
    width: 20%;
    padding-right: 12px;
    padding-left: 12px;
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
</style>

<div class="page-content">
  <div class="page-container">
    <div class="row">
      <div class="col-12">
        <!-- Client Form Card -->
        <div class="card shadow-sm mb-4">
          <div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
            <h4 class="header-title mb-0"><i class="ti ti-users me-2"></i> <span id="formTitle">Add New Client</span></h4>
            <button type="button" class="btn btn-sm btn-secondary" id="resetFormBtn" style="display:none;">
              <i class="ti ti-plus"></i> Add New
            </button>
          </div>

          <div class="card-body">
            <form id="clientForm" method="post" enctype="multipart/form-data" novalidate>
              <input type="hidden" name="client_id" id="client_id" value="">
              <input type="hidden" name="action" id="formAction" value="insert">

              <div class="accordion" id="clientAccordion">
                
                <!-- ACCORDION - DEFAULT CLOSED -->
                <div class="accordion-item mb-3">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#clientDetailsSection">
                      <i class="ti ti-users me-2"></i> Client Details
                    </button>
                  </h2>

                  <div id="clientDetailsSection" class="accordion-collapse collapse" data-bs-parent="#clientAccordion">
                    <div class="accordion-body">

                      <!-- ============= SUB-GROUP 1: BASIC INFO ============= -->
                      <div class="section-header">
                        <i class="ti ti-info-circle me-2"></i> Basic Information
                      </div>

                      <div class="row">
                        <div class="col-5-per-row mb-3">
                          <label>Company Name <span class="text-danger">*</span></label>
                          <input type="text" name="company_name" id="company_name" class="form-control" required minlength="2" maxlength="200">
                          <div class="invalid-feedback">Company name is required (2-200 characters)</div>
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Group Company Name</label>
                          <select name="group_company_id" id="group_company_id" class="form-select">
                            <option value="">-- Select Group Company --</option>
                            <?php foreach ($group_company as $i): ?>
                              <option value="<?= $i['id'] ?>"><?= htmlspecialchars($i['group_company_name']) ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Client Code <span class="text-danger">*</span></label>
                          <input type="text" name="short_name" id="short_name" class="form-control" maxlength="3" minlength="3" style="text-transform: uppercase;" required>
                          <div class="invalid-feedback">3 characters required</div>
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label>Client Type <span class="text-danger">*</span></label><br>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="client_type[]" id="client_import" value="I">
                            <label class="form-check-label" for="client_import">Import</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="client_type[]" id="client_export" value="E">
                            <label class="form-check-label" for="client_export">Export</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="client_type[]" id="client_local" value="L">
                            <label class="form-check-label" for="client_local">Local</label>
                          </div>
                          <div class="text-danger" id="client_type_error" style="display:none; font-size: 0.875rem; margin-top: 0.25rem;">
                            Please select at least one client type.
                          </div>
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label>Industry Type</label>
                          <select name="industry_type_id" id="industry_type_id" class="form-select">
                            <option value="">-- Select Industry --</option>
                            <?php foreach ($industries as $i): ?>
                              <option value="<?= $i['id'] ?>"><?= htmlspecialchars($i['industry_name']) ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>

                      <!-- ROW 2 -->
                      <div class="row">
                        <div class="col-5-per-row mb-3">
                          <label>Referred By</label>
                          <select name="referred_by_id" id="referred_by_id" class="form-select">
                            <option value="">-- Select --</option>
                            <?php foreach ($refferers as $c): ?>
                              <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['refferer_name']) ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label>Phase</label>
                          <select name="phase_id" id="phase_id" class="form-select">
                            <option value="">-- Select Phase --</option>
                            <?php foreach ($phases as $ph): ?>
                              <option value="<?= $ph['id'] ?>"><?= htmlspecialchars($ph['phase_code']) . '-' . htmlspecialchars($ph['phase_name']) ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label>Phase Start Date</label>
                          <input type="date" name="phase_start_date" id="phase_start_date" class="form-control">
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Phase End Date</label>
                          <input type="date" name="phase_end_date" id="phase_end_date" class="form-control">
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Location</label>
                          <select name="office_location_id" id="office_location_id" class="form-select">
                            <option value="">-- Select Location --</option>
                            <?php foreach ($locations as $loc): ?>
                              <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['location_name']) ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>

                      <!-- ROW 3 -->
                      <div class="row">
                        <div class="col-12 mb-3">
                          <label>Address <span class="text-danger">*</span></label>
                          <textarea name="address" id="address" class="form-control" rows="2" maxlength="500" required></textarea>
                          <small class="text-muted">Maximum 500 characters</small>
                          <div class="invalid-feedback">Address is required (2-500 characters)</div>
                        </div>
                      </div>

                      <!-- ============= SUB-GROUP 2: CONTACT INFO ============= -->
                      <div class="section-header">
                        <i class="ti ti-phone me-2"></i> Contact Information
                      </div>

                      <div class="row">
                        <div class="col-5-per-row mb-3">
                          <label>Contact Person</label>
                          <input type="text" name="contact_person" id="contact_person" class="form-control" maxlength="100">
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Email</label>
                          <input type="email" name="email" id="email" class="form-control" maxlength="100">
                          <div class="invalid-feedback">Please enter a valid email address</div>
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Secondary Email</label>
                          <input type="email" name="email_secondary" id="email_secondary" class="form-control" maxlength="100">
                          <div class="invalid-feedback">Please enter a valid email address</div>
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Phone</label>
                          <input type="tel" name="phone" id="phone" class="form-control" pattern="[0-9+\s()-]{7,20}" maxlength="20">
                          <div class="invalid-feedback">Please enter a valid phone number</div>
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Phone (Secondary)</label>
                          <input type="tel" name="phone_secondary" id="phone_secondary" class="form-control" pattern="[0-9+\s()-]{7,20}" maxlength="20">
                          <div class="invalid-feedback">Please enter a valid phone number</div>
                        </div>
                      </div>

                      <!-- ============= SUB-GROUP 3: LEGAL & FILES ============= -->
                      <div class="section-header">
                        <i class="ti ti-file-certificate me-2"></i> Legal & License Info
                      </div>

                      <div class="row">
                        <div class="col-5-per-row mb-3">
                          <label>ID/NAT Number</label>
                          <input name="id_nat_number" id="id_nat_number" class="form-control" maxlength="50">
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>ID/NAT File</label>
                          <input type="file" name="id_nat_file" id="id_nat_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                          <small class="text-muted">Max 5MB</small>
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>RCCM Number</label>
                          <input name="rccm_number" id="rccm_number" class="form-control" maxlength="50">
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>RCCM File</label>
                          <input type="file" name="rccm_file" id="rccm_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                          <small class="text-muted">Max 5MB</small>
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Import/Export Number</label>
                          <input name="import_export_number" id="import_export_number" class="form-control" maxlength="50">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-5-per-row mb-3">
                          <label>Import/Export Validity</label>
                          <input type="date" name="import_export_validity" id="import_export_validity" class="form-control">
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Import/Export File</label>
                          <input type="file" name="import_export_file" id="import_export_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                          <small class="text-muted">Max 5MB</small>
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Attestation Number</label>
                          <input name="attestation_number" id="attestation_number" class="form-control" maxlength="50">
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Attestation Validity</label>
                          <input type="date" name="attestation_validity" id="attestation_validity" class="form-control">
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Attestation File</label>
                          <input type="file" name="attestation_file" id="attestation_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                          <small class="text-muted">Max 5MB</small>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-5-per-row mb-3">
                          <label>NIF Number</label>
                          <input name="nif_number" id="nif_number" class="form-control" maxlength="50">
                        </div>
                      </div>

                      <!-- ============= SUB-GROUP 4: FINANCIAL INFO ============= -->
                      <div class="section-header">
                        <i class="ti ti-currency-dollar me-2"></i> Financial & Payment Info
                      </div>

                      <div class="row">
                        <div class="col-5-per-row mb-3">
                          <label>Payment Term <span class="text-danger">*</span></label>
                          <select name="payment_term" id="payment_term" required class="form-select">
                            <option value="">Select Payment Term</option>
                            <option value="ADVANCE">Advance</option>
                            <option value="15days">15 Days</option>
                            <option value="30days">30 Days</option>
                            <option value="45days">45 Days</option>
                            <option value="60days">60 Days</option>
                          </select>
                          <div class="invalid-feedback">Please select a valid payment term.</div>
                        </div>

                        <div class="col-5-per-row mb-3">
                          <label>Credit Term (days)</label>
                          <input type="number" name="credit_term" id="credit_term" class="form-control" min="0" max="365">
                          <div class="invalid-feedback">Credit term must be between 0 and 365 days</div>
                        </div>
                        
                        <!-- UPDATED: Using done_by_t table -->
                        <div class="col-5-per-row mb-3">
                          <label>Liquidation Paid By <span class="text-danger">*</span></label>
                          <select name="liquidation_paid_by" id="liquidation_paid_by" class="form-select" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($done_by_options as $opt): ?>
                              <option value="<?= $opt['id'] ?>"><?= htmlspecialchars($opt['done_by_name']) ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback">Liquidation Paid By is required</div>
                        </div>
                        
                        <!-- UPDATED: Using done_by_t table -->
                        <div class="col-5-per-row mb-3">
                          <label>License Cleared By</label>
                          <select name="license_cleared_by" id="license_cleared_by" class="form-select">
                            <option value="">-- Select --</option>
                            <?php foreach ($done_by_options as $opt): ?>
                              <option value="<?= $opt['id'] ?>"><?= htmlspecialchars($opt['done_by_name']) ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        
                        <!-- UPDATED: Using done_by_t table -->
                        <div class="col-5-per-row mb-3">
                          <label>License Submitted To Bank <span class="text-danger">*</span></label>
                          <select name="license_submit_to_bank" id="license_submit_to_bank" class="form-select" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($done_by_options as $opt): ?>
                              <option value="<?= $opt['id'] ?>"><?= htmlspecialchars($opt['done_by_name']) ?></option>
                            <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback">Please select who submitted the license to the bank.</div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-5-per-row mb-3">
                          <label>Contract Start Date</label>
                          <input type="date" name="contract_start_date" id="contract_start_date" class="form-control">
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Contract Validity</label>
                          <input type="date" name="contract_validity" id="contract_validity" class="form-control">
                        </div>
                        
                        <div class="col-5-per-row mb-3">
                          <label>Payment Contact Email</label>
                          <input type="email" name="payment_contact_email" id="payment_contact_email" class="form-control" maxlength="100">
                          <div class="invalid-feedback">Please enter a valid email address</div>
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Payment Contact Phone</label>
                          <input type="tel" name="payment_contact_phone" id="payment_contact_phone" class="form-control" pattern="[0-9+\s()-]{7,20}" maxlength="20">
                          <div class="invalid-feedback">Please enter a valid phone number</div>
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Approval Code</label>
                          <input name="approval_code" id="approval_code" class="form-control" maxlength="50">
                        </div>
                      </div>

                      <!-- ============= SUB-GROUP 5: VERIFICATION & APPROVAL ============= -->
                      <div class="section-header">
                        <i class="ti ti-file-text me-2"></i> Verification & Approval
                      </div>

                      <div class="row">
                        <div class="col-5-per-row mb-3">
                          <label>Verified By</label>
                          <select name="verified_by_id" id="verified_by_id" class="form-select">
                            <option value="">-- Select User --</option>
                            <?php foreach ($users as $u): ?>
                              <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Verification Date</label>
                          <input type="date" name="verified_by_date" id="verified_by_date" class="form-control">
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Approved By</label>
                          <select name="approved_by_id" id="approved_by_id" class="form-select">
                            <option value="">-- Select User --</option>
                            <?php foreach ($users as $u): ?>
                              <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-5-per-row mb-3">
                          <label>Approved Date</label>
                          <input type="date" name="approved_by_date" id="approved_by_date" class="form-control">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-12 mb-3">
                          <label>Remarks</label>
                          <textarea name="remarks" id="remarks" class="form-control" rows="3" maxlength="1000"></textarea>
                          <small class="text-muted">Maximum 1000 characters</small>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>

              </div> <!-- accordion -->

              <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                  <i class="ti ti-device-floppy"></i> <span id="submitBtnText">Save Client</span>
                </button>
                <button type="reset" class="btn btn-secondary" id="cancelBtn">
                  <i class="ti ti-x"></i> Cancel
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Clients DataTable Card -->
        <div class="card shadow-sm">
          <div class="card-header border-bottom border-dashed">
            <h4 class="header-title"><i class="ti ti-list me-2"></i> Clients List</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="clientsTable" class="table table-hover table-striped w-100">
                <thead class="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone</th>
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
</div>

<?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>

<script>
  $(function () {
    'use strict';

    let clientsTable;

    function escapeHtml(text) {
      if (text === null || text === undefined) return '';
      const map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
      return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    function typeLabelFromChar(c) {
      if (c === 'I') return 'Import';
      if (c === 'E') return 'Export';
      if (c === 'L') return 'Local';
      return c;
    }

    // Export single client to Excel using PhpSpreadsheet (server-side)
    function exportClientToExcel(clientId) {
      // Show loading indicator
      Swal.fire({
        title: 'Generating Excel...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      // Trigger file download directly
      window.location.href = '<?= APP_URL ?>/client/crudData/exportClient?id=' + clientId;
      
      // Close loading after a short delay
      setTimeout(function() {
        Swal.close();
      }, 1000);
    }

    // Initialize DataTable
    function initDataTable() {
      clientsTable = $('#clientsTable').DataTable({
        ajax: {
          url: '<?= APP_URL ?>/client/crudData/listing',
          type: 'GET',
          dataSrc: 'data',
          error: function (xhr, error, thrown) {
            console.error('DataTable error:', error, thrown);
            Swal.fire('Error', 'Failed to load clients data', 'error');
          }
        },
        columns: [
          { data: 'id' },
          { data: 'company_name', render: data => escapeHtml(data) },
          { data: 'short_name', render: data => escapeHtml(data) },
          {
            data: 'client_type',
            render: function (data) {
              if (!data) return '<em class="text-muted">N/A</em>';
              const parts = data.split('');
              return parts.map(c => `<span class="badge bg-info me-1">${typeLabelFromChar(c)}</span>`).join('');
            }
          },
          { data: 'contact_person', defaultContent: '<em class="text-muted">N/A</em>', render: data => escapeHtml(data) },
          { data: 'email', defaultContent: '<em class="text-muted">N/A</em>', render: data => escapeHtml(data) },
          { data: 'phone', defaultContent: '<em class="text-muted">N/A</em>', render: data => escapeHtml(data) },
          {
            data: 'display',
            render: function (data) {
              return data === 'Y' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
            }
          },
          {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              return `
                <div class="btn-group btn-group-sm">
                  <button class="btn btn-primary btn-sm editBtn" data-id="${parseInt(row.id)}" title="Edit">
                    <i class="ti ti-edit"></i>
                  </button>
                  <button class="btn btn-export btn-sm exportBtn" data-id="${parseInt(row.id)}" title="Export to Excel">
                    <i class="ti ti-file-spreadsheet"></i>
                  </button>
                  <button class="btn btn-danger btn-sm deleteBtn" data-id="${parseInt(row.id)}" title="Delete">
                    <i class="ti ti-trash"></i>
                  </button>
                </div>
              `;
            }
          }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        processing: true,
        language: { processing: '<i class="fa fa-spinner fa-spin"></i> Loading...' },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"<"d-flex justify-content-end"fB>>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        buttons: [
          {
            extend: 'excel',
            text: '<i class="ti ti-file-spreadsheet me-1"></i> Export All',
            className: 'buttons-excel',
            exportOptions: {
              columns: [0, 1, 2, 3, 4, 5, 6, 7]
            }
          }
        ]
      });
    }

    // Export button click
    $(document).on('click', '.exportBtn', function() {
      const clientId = parseInt($(this).data('id'));
      if (clientId > 0) {
        exportClientToExcel(clientId);
      } else {
        Swal.fire('Error', 'Invalid client ID', 'error');
      }
    });

    // Validate file size
    function validateFileSize(fileInput) {
      if (fileInput.files.length > 0) {
        const fileSize = fileInput.files[0].size / 1024 / 1024;
        if (fileSize > 5) {
          fileInput.setCustomValidity('File size must be less than 5MB');
          return false;
        } else {
          fileInput.setCustomValidity('');
          return true;
        }
      }
      return true;
    }

    $(document).on('change', 'input[type="file"]', function () {
      validateFileSize(this);
    });

    $(document).on('blur', 'input[type="email"]', function () {
      if (this.value && !this.checkValidity()) {
        this.classList.add('is-invalid');
      } else {
        this.classList.remove('is-invalid');
      }
    });

    // FIX: Hide client type error immediately when checkbox is selected
    $(document).on('change', 'input[name="client_type[]"]', function () {
      if ($('input[name="client_type[]"]:checked').length > 0) {
        $('#client_type_error').hide();
      }
    });

    // Short name validation
    $('#short_name').on('input keyup change blur', function () {
      let shortName = $(this).val().toUpperCase().replace(/[^A-Z]/g, '');
      $(this).val(shortName);
      const field = $(this);
      
      if (shortName.length === 0) {
        field.removeClass('is-invalid is-valid');
        field.next('.invalid-feedback').text('3 characters required');
        return;
      }
      
      if (shortName.length !== 3) {
        field.addClass('is-invalid').removeClass('is-valid');
        field.next('.invalid-feedback').text('Exactly 3 characters required');
        return;
      }

      const clientId = $('#client_id').val();
      $.ajax({
        url: '<?= APP_URL ?>/client/crudData/checkShortName',
        method: 'GET',
        data: { short_name: shortName, client_id: clientId },
        dataType: 'json',
        success: function (res) {
          if (res.success) {
            if (res.exists) {
              field.addClass('is-invalid').removeClass('is-valid');
              field.next('.invalid-feedback').text('Short name already exists!');
            } else {
              field.addClass('is-valid').removeClass('is-invalid');
              field.next('.invalid-feedback').text('Looks good!');
            }
          }
        }
      });
    });

    // ENHANCED Date validation - Phase, Contract, and Verification/Approval
    function validateDates() {
      let isValid = true;
      
      // Phase Date Validation
      const phaseStart = $('#phase_start_date').val();
      const phaseEnd = $('#phase_end_date').val();
      if (phaseStart && phaseEnd) {
        if (new Date(phaseStart) > new Date(phaseEnd)) {
          Swal.fire({ 
            icon: 'warning', 
            title: 'Invalid Date Range', 
            text: 'Phase End Date cannot be earlier than Phase Start Date!' 
          });
          $('#phase_end_date').val('');
          isValid = false;
        }
      }
      
      // Contract Date Validation
      const contractStart = $('#contract_start_date').val();
      const contractValidity = $('#contract_validity').val();
      if (contractStart && contractValidity) {
        if (new Date(contractStart) > new Date(contractValidity)) {
          Swal.fire({ 
            icon: 'warning', 
            title: 'Invalid Contract Date', 
            text: 'Contract Validity cannot be earlier than Contract Start Date!' 
          });
          $('#contract_validity').val('');
          isValid = false;
        }
      }
      
      // Verification/Approval Date Validation
      const verifiedDate = $('#verified_by_date').val();
      const approvedDate = $('#approved_by_date').val();
      if (verifiedDate && approvedDate) {
        if (new Date(verifiedDate) > new Date(approvedDate)) {
          Swal.fire({ 
            icon: 'warning', 
            title: 'Invalid Approval Date', 
            text: 'Approved Date cannot be earlier than Verification Date!' 
          });
          $('#approved_by_date').val('');
          isValid = false;
        }
      }
      
      return isValid;
    }

    // Attach validation to all date fields
    $(document).on('change', '#phase_start_date, #phase_end_date, #contract_start_date, #contract_validity, #verified_by_date, #approved_by_date', validateDates);

    // Form submission
    $('#clientForm').on('submit', function (e) {
      e.preventDefault();

      if (!validateDates()) return false;

      const form = this;

      if (!form.checkValidity()) {
        e.stopPropagation();
        form.classList.add('was-validated');
        const firstInvalid = form.querySelector('.is-invalid, :invalid');
        if (firstInvalid) {
          firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
          firstInvalid.focus();
        }
        Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please fill in all required fields correctly', timer: 2500 });
        return false;
      }

      let fileValid = true;
      $('input[type="file"]').each(function () {
        if (!validateFileSize(this)) fileValid = false;
      });
      if (!fileValid) {
        Swal.fire('Error', 'One or more files exceed the 5MB size limit', 'error');
        return false;
      }

      const selected = $('input[name="client_type[]"]:checked').map(function () { return this.value; }).get();

      if (selected.length === 0) {
        $('#client_type_error').show();
        $('#client_type_error')[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please select at least one client type', timer: 2500 });
        return false;
      } else {
        $('#client_type_error').hide();
      }

      const clientType = selected.sort().join('');
      const formData = new FormData(form);
      formData.set('client_type', clientType);

      const submitBtn = $('#submitBtn');
      const originalText = submitBtn.html();
      submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

      const action = $('#formAction').val();
      const url = action === 'update' ? '<?= APP_URL ?>/client/crudData/update' : '<?= APP_URL ?>/client/crudData/insertion';

      $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (res) {
          submitBtn.prop('disabled', false).html(originalText);

          if (res.success) {
            Swal.fire({ icon: 'success', title: 'Success!', text: res.message || 'Saved successfully', timer: 1500, showConfirmButton: false });
            resetForm();
            if (typeof clientsTable !== 'undefined') clientsTable.ajax.reload(null, false);
          } else {
            Swal.fire({ icon: 'error', title: 'Error!', html: res.message || 'Unable to save' });
          }
        },
        error: function (xhr) {
          submitBtn.prop('disabled', false).html(originalText);
          let errorMsg = 'An error occurred while processing your request';
          try {
            const response = JSON.parse(xhr.responseText);
            errorMsg = response.message || errorMsg;
          } catch (e) {
            errorMsg = xhr.responseText || errorMsg;
          }
          Swal.fire({ icon: 'error', title: 'Server Error', html: errorMsg });
        }
      });
    });

    function resetForm() {
      $('#clientForm')[0].reset();
      $('#clientForm').removeClass('was-validated');
      $('#client_id').val('');
      $('#formAction').val('insert');
      $('#formTitle').text('Add New Client');
      $('#submitBtnText').text('Save Client');
      $('#resetFormBtn').hide();
      $('#client_type_error').hide();
      $('input[name="client_type[]"]').prop('checked', false);
      $('.form-control, .form-select').removeClass('is-valid is-invalid');
      
      $('#clientDetailsSection').collapse('hide');
      
      $('html, body').animate({ scrollTop: $('#clientForm').offset().top - 100 }, 200);
    }

    $('#cancelBtn, #resetFormBtn').on('click', function (e) {
      e.preventDefault();
      resetForm();
    });

    // Edit client
    $(document).on('click', '.editBtn', function () {
      const id = parseInt($(this).data('id'));
      $.ajax({
        url: '<?= APP_URL ?>/client/crudData/getClient',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
          if (res.success && res.data) {
            const client = res.data;

            $('#client_id').val(client.id);
            $('#formAction').val('update');
            $('#formTitle').text('Edit Client');
            $('#submitBtnText').text('Update Client');
            $('#resetFormBtn').show();

            const fillableFields = ['company_name','short_name','industry_type_id','referred_by_id',
              'office_location_id','phase_id','phase_start_date','phase_end_date','address',
              'contact_person','email','email_secondary','phone','phone_secondary','id_nat_number','rccm_number',
              'import_export_number','import_export_validity','attestation_number','attestation_validity',
              'nif_number','payment_contact_email','payment_contact_phone','payment_term','credit_term',
              'liquidation_paid_by','license_cleared_by','license_submit_to_bank','contract_start_date',
              'contract_validity','approval_code','remarks','verified_by_id','verified_by_date','approved_by_id','approved_by_date','group_company_id'];

            fillableFields.forEach(function (key) {
              const val = client[key];
              $('#' + key).val((val !== null && val !== undefined) ? val : '');
            });

            $('input[name="client_type[]"]').prop('checked', false);
            if (client.client_type) {
              const chars = client.client_type.split('');
              chars.forEach(function (c) {
                if (c === 'I') $('#client_import').prop('checked', true);
                if (c === 'E') $('#client_export').prop('checked', true);
                if (c === 'L') $('#client_local').prop('checked', true);
              });
            }

            $('#clientDetailsSection').collapse('show');
            $('html, body').animate({ scrollTop: $('#clientForm').offset().top - 100 }, 500);
          } else {
            Swal.fire('Error', res.message || 'Failed to load client data', 'error');
          }
        },
        error: function () {
          Swal.fire('Error', 'Failed to load client data', 'error');
        }
      });
    });

    // Delete client
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
            url: '<?= APP_URL ?>/client/crudData/deletion',
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (res) {
              if (res.success) {
                Swal.fire({ icon: 'success', title: 'Deleted!', text: res.message, timer: 1500, showConfirmButton: false });
                clientsTable.ajax.reload(null, false);
              } else {
                Swal.fire('Error', res.message || 'Delete failed', 'error');
              }
            },
            error: function () {
              Swal.fire('Error', 'Failed to delete client', 'error');
            }
          });
        }
      });
    });

    initDataTable();
  });
</script>

<script src="<?= BASE_URL ?>/assets/pages/js/client-form-validator.js"></script>