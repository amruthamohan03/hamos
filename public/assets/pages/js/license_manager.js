/**
 * License Management System - JavaScript Module
 * 
 * Handles all client-side operations for license management including
 * form validation, CRUD operations, MCA type handling, and modals.
 * 
 * @version 2.0.0
 */

// ===== CONSTANTS =====
const MCA_KIND_IDS = [5, 6]; // MCA Import and Export Kind IDs
const EXPIRING_DAYS_CRITICAL = 7;
const EXPIRING_DAYS_WARNING = 15;
const MCA_LICENSE_FORMAT = 'CLIENT-KIND-GOODS-TRANSPORT';

// ===== GLOBAL VARIABLES =====
let licensesTable;
let currentFilter = 'all';
let currentSearch = '';
let isMCAType = false;
const today = new Date().toISOString().split('T')[0];

// ===== INITIALIZATION =====
$(document).ready(function () {
  initializeDateConstraints();
  initializeEventHandlers();
  initDataTable();
  updateStatistics();
  setActiveFilter('all');
});

/**
 * Initialize date input constraints
 */
function initializeDateConstraints() {
  $('#invoice_date').attr('max', today);
}

/**
 * Initialize all event handlers
 */
function initializeEventHandlers() {
  // Kind change event
  $('#kind_id').on('change', handleKindChange);
  
  // Client change event
  $('#client_id').on('change', handleClientChange);
  
  // Type of goods change event (for MCA reference)
  $('#type_of_goods_id').on('change', function() {
    if (isMCAType) {
      generateMCAReference();
    }
  });
  
  // Date validation events
  $('#license_applied_date').on('change', handleAppliedDateChange);
  $('#license_validation_date').on('change', handleValidationDateChange);
  
  // Numeric field validation
  $('input[type="number"]').on('input', validateNumericInput);
  
  // File input validation
  $('input[type="file"]').on('change', validateFileInput);
  
  // Form submission
  $('#licenseForm').on('submit', handleFormSubmit);
  
  // Filter cards
  $('.filter-card').on('click', handleFilterCardClick);
  
  // Clear filter button
  $('#clearFilterBtn').on('click', clearFilter);
  
  // Export buttons
  $('#exportAllBtn').on('click', exportAllLicenses);
  $(document).on('click', '.exportBtn', exportSingleLicense);
  
  // CRUD buttons
  $(document).on('click', '.viewBtn', viewLicenseDetails);
  $(document).on('click', '.editBtn', editLicense);
  $(document).on('click', '.deleteBtn', deleteLicense);
  
  // Origin management
  $('#addOriginBtn').on('click', openAddOriginModal);
  $('#saveOriginBtn').on('click', saveNewOrigin);
  
  // Modal card handlers
  $('#expiredCard').on('click', showExpiredLicensesModal);
  $('#expiringCard').on('click', showExpiringLicensesModal);
  $('#incompleteCard').on('click', showIncompleteLicensesModal);
  
  // Form reset buttons
  $('#cancelBtn, #resetFormBtn').on('click', resetForm);
}

// ===== MCA TYPE HANDLING =====

/**
 * Check if kind ID is MCA type
 * @param {number} kindId - Kind ID to check
 * @returns {boolean} True if MCA type
 */
function isMCAKind(kindId) {
  return MCA_KIND_IDS.includes(parseInt(kindId));
}

/**
 * Handle kind selection change
 */
function handleKindChange() {
  const kindId = $(this).val();
  isMCAType = isMCAKind(kindId);
  toggleFieldsForMCAType(isMCAType);
}

/**
 * Toggle form fields visibility based on MCA type
 * @param {boolean} isMCA - Is MCA type license
 */
function toggleFieldsForMCAType(isMCA) {
  if (isMCA) {
    showMCAFields();
  } else {
    showStandardFields();
  }
}

/**
 * Show fields for MCA type licenses
 */
function showMCAFields() {
  // Hide standard sections
  $('#financialInfoSection, #invoiceTransportSection, #licenseDetailsSection, #paymentInfoSection').hide();
  $('#bankField, #licenseClearedByField, #weightField').hide();
  
  // Add MCA-specific fields if not already present
  if ($('#mcaTransportField').length === 0) {
    appendMCAFields();
  }
  
  // Remove required attributes from hidden fields
  removeRequiredFromHiddenFields();
  
  // Make original fields not required
  $('#transport_mode_id, #currency_id').removeAttr('required');
  
  // Setup license number auto-generation
  setupMCALicenseNumber();
  
  // Generate MCA reference
  generateMCAReference();
}

/**
 * Append MCA-specific form fields
 */
function appendMCAFields() {
  $('#basicInfoRow').append(getMCAFieldsHTML());
  
  // Sync values if switching from standard to MCA
  $('#transport_mode_id_mca').val($('#transport_mode_id').val());
  $('#currency_id_mca').val($('#currency_id').val());
  
  // Add change events
  $('#transport_mode_id_mca, #currency_id_mca').on('change', generateMCAReference);
}

/**
 * Get HTML for MCA-specific fields
 * @returns {string} HTML string
 */
function getMCAFieldsHTML() {
  return `
    <div class="col-md-4 mb-3" id="mcaTransportField">
      <label class="form-label">Transport Mode <span class="text-danger">*</span></label>
      <select name="transport_mode_id_mca" id="transport_mode_id_mca" class="form-select" required>
        <option value="">-- Select Transport Mode --</option>
        ${getTransportModeOptions()}
      </select>
      <div class="invalid-feedback">Please select transport mode</div>
    </div>
    
    <div class="col-md-4 mb-3" id="mcaCurrencyField">
      <label class="form-label">Currency <span class="text-danger">*</span></label>
      <select name="currency_id_mca" id="currency_id_mca" class="form-select" required>
        <option value="">-- Select Currency --</option>
        ${getCurrencyOptions()}
      </select>
      <div class="invalid-feedback">Please select currency</div>
    </div>
    
    <div class="col-md-12 mb-3" id="mcaLicenseNumberField">
      <label class="form-label">License Number <span class="text-danger">*</span> (Auto-generated)</label>
      <input type="text" name="license_number_mca" id="license_number_mca" class="form-control" required readonly>
      <small class="text-muted">Format: ${MCA_LICENSE_FORMAT}</small>
      <div class="invalid-feedback">License number is required</div>
    </div>
  `;
}

/**
 * Get transport mode options HTML
 * @returns {string} Options HTML
 */
function getTransportModeOptions() {
  let options = '';
  $('#transport_mode_id option').each(function() {
    if ($(this).val()) {
      options += `<option value="${$(this).val()}" data-transport-letter="${$(this).data('transport-letter')}">${$(this).text()}</option>`;
    }
  });
  return options;
}

/**
 * Get currency options HTML
 * @returns {string} Options HTML
 */
function getCurrencyOptions() {
  let options = '';
  $('#currency_id option').each(function() {
    if ($(this).val()) {
      options += `<option value="${$(this).val()}">${$(this).text()}</option>`;
    }
  });
  return options;
}

/**
 * Setup MCA license number field
 */
function setupMCALicenseNumber() {
  $('#license_number').attr('readonly', true).val('');
  $('#licenseNumberHelp').text('Auto-generated: ' + MCA_LICENSE_FORMAT);
}

/**
 * Show fields for standard (non-MCA) licenses
 */
function showStandardFields() {
  // Show all sections
  $('#financialInfoSection, #invoiceTransportSection, #licenseDetailsSection, #paymentInfoSection').show();
  $('#bankField, #licenseClearedByField, #weightField').show();
  
  // Remove MCA-specific fields
  $('#mcaTransportField, #mcaCurrencyField, #mcaLicenseNumberField').remove();
  
  // Restore required attributes
  addRequiredToStandardFields();
  
  // Restore original field requirements
  $('#transport_mode_id, #currency_id').attr('required', true);
  
  // Make license number editable
  $('#license_number').attr('readonly', false);
  $('#licenseNumberHelp').text('');
}

/**
 * Remove required attribute from hidden fields
 */
function removeRequiredFromHiddenFields() {
  const hiddenFields = [
    '#bank_id', '#license_cleared_by', '#weight', '#unit_of_measurement_id',
    '#fob_declared', '#invoice_number', '#invoice_date', '#supplier',
    '#license_applied_date', '#license_validation_date', '#license_expiry_date',
    '#entry_post_id', '#payment_method_id', '#destination_id'
  ];
  
  hiddenFields.forEach(field => $(field).removeAttr('required'));
}

/**
 * Add required attribute to standard fields
 */
function addRequiredToStandardFields() {
  const requiredFields = [
    '#bank_id', '#license_cleared_by', '#weight', '#unit_of_measurement_id',
    '#currency_id', '#fob_declared', '#transport_mode_id', '#invoice_number',
    '#invoice_date', '#supplier', '#license_applied_date', '#license_validation_date',
    '#license_expiry_date', '#entry_post_id', '#payment_method_id', '#destination_id'
  ];
  
  requiredFields.forEach(field => $(field).attr('required', true));
}

/**
 * Generate MCA reference number (CLIENT-KIND-GOODS-TRANSPORT)
 */
function generateMCAReference() {
  const clientId = $('#client_id').val();
  const kindId = $('#kind_id').val();
  const goodsId = $('#type_of_goods_id').val();
  const transportId = $('#transport_mode_id_mca').val() || $('#transport_mode_id').val();
  
  if (!clientId || !kindId || !goodsId || !transportId || !isMCAType) {
    return;
  }
  
  const clientShort = $('#client_id option:selected').data('client-short');
  const kindShort = $('#kind_id option:selected').data('kind-short');
  const goodsShort = $('#type_of_goods_id option:selected').data('goods-short');
  const transportLetter = $('#transport_mode_id_mca option:selected').data('transport-letter') || 
                         $('#transport_mode_id option:selected').data('transport-letter');
  
  if (clientShort && kindShort && goodsShort && transportLetter) {
    const mcaRef = `${clientShort}-${kindShort}-${goodsShort}-${transportLetter}`;
    $('#license_number').val(mcaRef);
    $('#license_number_mca').val(mcaRef);
  }
}

// ===== CLIENT HANDLING =====

/**
 * Handle client selection change
 */
function handleClientChange() {
  if (isMCAType) {
    generateMCAReference();
    return;
  }
  
  loadClientLicenseSetting($(this).val());
}

/**
 * Load client license setting (cleared by)
 * @param {number} clientId - Client ID
 */
function loadClientLicenseSetting(clientId) {
  if (!clientId) {
    $('#license_cleared_by').val('');
    return;
  }

  $.ajax({
    url: BASE_URL + '/license/getClientLicenseSetting',
    method: 'GET',
    data: { client_id: clientId },
    dataType: 'json',
    success: function (res) {
      if (res.success) {
        $('#license_cleared_by').val(res.license_cleared_by);
      } else {
        $('#license_cleared_by').val('');
      }
    },
    error: function() {
      console.error('Failed to load client license setting');
    }
  });
}

// ===== STATISTICS =====

/**
 * Update statistics dashboard
 */
function updateStatistics() {
  $.ajax({
    url: BASE_URL + '/license/crudData/statistics',
    method: 'GET',
    dataType: 'json',
    success: function(res) {
      if (res.success) {
        updateStatisticsUI(res.data);
      }
    },
    error: function() {
      console.error('Failed to load statistics');
    }
  });
}

/**
 * Update statistics UI with data
 * @param {Object} data - Statistics data
 */
function updateStatisticsUI(data) {
  $('#totalLicenses').text(data.total_licenses || 0);
  $('#expiredLicenses').text(data.expired_licenses || 0);
  $('#expiringLicenses').text(data.expiring_licenses || 0);
  $('#incompleteLicenses').text(data.incomplete_licenses || 0);
  $('#annulatedLicenses').text(data.annulated_licenses || 0);
  $('#modifiedLicenses').text(data.modified_licenses || 0);
  $('#prorogatedLicenses').text(data.prorogated_licenses || 0);
}

// ===== FILTER HANDLING =====

/**
 * Handle filter card click
 */
function handleFilterCardClick() {
  const cardId = $(this).attr('id');
  
  // Special handling for modal cards
  if (['expiredCard', 'expiringCard', 'incompleteCard'].includes(cardId)) {
    return;
  }
  
  const filter = $(this).data('filter');
  setActiveFilter(filter);
  
  if (filter === 'all') {
    $('#clearFilterBtn').hide();
  } else {
    $('#clearFilterBtn').show();
  }
  
  if (licensesTable) {
    licensesTable.ajax.reload();
  }
}

/**
 * Set active filter
 * @param {string} filter - Filter type
 */
function setActiveFilter(filter) {
  $('.filter-card').removeClass('active-filter');
  $(`.filter-card[data-filter="${filter}"]`).addClass('active-filter');
  currentFilter = filter;
}

/**
 * Clear active filter
 */
function clearFilter() {
  setActiveFilter('all');
  $('#clearFilterBtn').hide();
  if (licensesTable) {
    licensesTable.ajax.reload();
  }
}

// ===== ORIGIN MANAGEMENT =====

/**
 * Open add origin modal
 */
function openAddOriginModal(e) {
  e.preventDefault();
  $('#addOriginForm')[0].reset();
  $('#new_origin_name').removeClass('is-invalid');
  $('#addOriginModal').modal('show');
}

/**
 * Save new origin
 */
function saveNewOrigin() {
  const originName = $('#new_origin_name').val().trim();
  
  if (!originName) {
    $('#new_origin_name').addClass('is-invalid');
    return;
  }
  
  $('#new_origin_name').removeClass('is-invalid');
  
  const btn = $(this);
  const originalText = btn.html();
  btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');
  
  $.ajax({
    url: BASE_URL + '/license/crudData/addOrigin',
    method: 'POST',
    data: { origin_name: originName },
    dataType: 'json',
    success: function(res) {
      btn.prop('disabled', false).html(originalText);
      
      if (res.success) {
        showSuccessMessage(res.message);
        refreshOriginDropdown(res.data);
        $('#addOriginModal').modal('hide');
      } else {
        showErrorMessage(res.message);
      }
    },
    error: function() {
      btn.prop('disabled', false).html(originalText);
      showErrorMessage('Failed to add destination/origin. Please try again.');
    }
  });
}

/**
 * Refresh origin dropdown with new data
 * @param {Object} newOrigin - New origin data
 */
function refreshOriginDropdown(newOrigin) {
  $.ajax({
    url: BASE_URL + '/license/crudData/getOrigins',
    method: 'GET',
    dataType: 'json',
    success: function(res) {
      if (res.success && res.data) {
        const $dropdown = $('#destination_id');
        const currentValue = $dropdown.val();
        
        $dropdown.find('option:not(:first)').remove();
        
        res.data.forEach(function(origin) {
          $dropdown.append(new Option(origin.origin_name, origin.id));
        });
        
        if (newOrigin && newOrigin.id) {
          $dropdown.val(newOrigin.id);
        } else if (currentValue) {
          $dropdown.val(currentValue);
        }
      }
    },
    error: function() {
      console.error('Failed to refresh origin dropdown');
    }
  });
}

// ===== MODAL HANDLERS =====

/**
 * Show expired licenses modal
 */
function showExpiredLicensesModal(e) {
  e.preventDefault();
  e.stopPropagation();
  
  $.ajax({
    url: BASE_URL + '/license/crudData/expiredLicenses',
    method: 'GET',
    dataType: 'json',
    success: function(res) {
      if (res.success && res.data && res.data.length > 0) {
        $('#expiredLicensesContent').html(buildExpiredLicensesHTML(res.data));
      } else {
        $('#expiredLicensesContent').html(getNoDataAlertHTML('No expired licenses found.'));
      }
      $('#expiredLicensesModal').modal('show');
    },
    error: function() {
      showErrorMessage('Failed to load expired licenses');
    }
  });
  
  setActiveFilter('expired');
  $('#clearFilterBtn').show();
  if (licensesTable) {
    licensesTable.ajax.reload();
  }
}

/**
 * Build HTML for expired licenses
 * @param {Array} licenses - License data array
 * @returns {string} HTML string
 */
function buildExpiredLicensesHTML(licenses) {
  let html = '<div class="list-group">';
  
  licenses.forEach(function(license) {
    const daysExpired = parseInt(license.days_expired);
    const expiryDate = new Date(license.license_expiry_date).toLocaleDateString('en-US');
    const appliedDate = license.license_applied_date ? new Date(license.license_applied_date).toLocaleDateString('en-US') : 'N/A';
    
    html += `
      <div class="expired-license-item">
        <div class="row align-items-center">
          <div class="col-md-2">
            <span class="badge days-expired-badge">${daysExpired} days ago</span>
          </div>
          <div class="col-md-10">
            <h6 class="mb-1"><strong>License:</strong> ${license.license_number || 'N/A'}</h6>
            <div class="row">
              <div class="col-md-4">
                <small><strong>Client:</strong> ${license.client_name || 'N/A'}</small>
              </div>
              <div class="col-md-4">
                <small><strong>Bank:</strong> ${license.bank_name || 'N/A'}</small>
              </div>
              <div class="col-md-4">
                <small><strong>Applied:</strong> ${appliedDate}</small>
              </div>
            </div>
            <div class="mt-1">
              <small class="text-danger"><strong>Expired:</strong> ${expiryDate}</small>
            </div>
          </div>
        </div>
      </div>
    `;
  });
  
  html += '</div>';
  return html;
}

/**
 * Show expiring licenses modal
 */
function showExpiringLicensesModal(e) {
  e.preventDefault();
  e.stopPropagation();
  
  $.ajax({
    url: BASE_URL + '/license/crudData/expiringLicenses',
    method: 'GET',
    dataType: 'json',
    success: function(res) {
      if (res.success && res.data && res.data.length > 0) {
        $('#expiringLicensesContent').html(buildExpiringLicensesHTML(res.data));
      } else {
        $('#expiringLicensesContent').html(getNoDataAlertHTML('No licenses expiring within 30 days.'));
      }
      $('#expiringLicensesModal').modal('show');
    },
    error: function() {
      showErrorMessage('Failed to load expiring licenses');
    }
  });
  
  setActiveFilter('expiring');
  $('#clearFilterBtn').show();
  if (licensesTable) {
    licensesTable.ajax.reload();
  }
}

/**
 * Build HTML for expiring licenses
 * @param {Array} licenses - License data array
 * @returns {string} HTML string
 */
function buildExpiringLicensesHTML(licenses) {
  let html = '<div class="list-group">';
  
  licenses.forEach(function(license) {
    const daysRemaining = parseInt(license.days_remaining);
    let badgeClass = 'days-notice';
    if (daysRemaining <= EXPIRING_DAYS_CRITICAL) {
      badgeClass = 'days-critical';
    } else if (daysRemaining <= EXPIRING_DAYS_WARNING) {
      badgeClass = 'days-warning';
    }
    
    const expiryDate = new Date(license.license_expiry_date).toLocaleDateString('en-US');
    const appliedDate = license.license_applied_date ? new Date(license.license_applied_date).toLocaleDateString('en-US') : 'N/A';
    
    html += `
      <div class="expiring-license-item">
        <div class="row align-items-center">
          <div class="col-md-2">
            <span class="badge days-badge ${badgeClass}">${daysRemaining} days</span>
          </div>
          <div class="col-md-10">
            <h6 class="mb-1"><strong>License:</strong> ${license.license_number || 'N/A'}</h6>
            <div class="row">
              <div class="col-md-4">
                <small><strong>Client:</strong> ${license.client_name || 'N/A'}</small>
              </div>
              <div class="col-md-4">
                <small><strong>Bank:</strong> ${license.bank_name || 'N/A'}</small>
              </div>
              <div class="col-md-4">
                <small><strong>Applied:</strong> ${appliedDate}</small>
              </div>
            </div>
            <div class="mt-1">
              <small class="text-danger"><strong>Expires:</strong> ${expiryDate}</small>
            </div>
          </div>
        </div>
      </div>
    `;
  });
  
  html += '</div>';
  return html;
}

/**
 * Show incomplete licenses modal
 */
function showIncompleteLicensesModal(e) {
  e.preventDefault();
  e.stopPropagation();
  
  $('#incompleteLicensesContent').html(getLoadingHTML('Loading incomplete licenses...'));
  $('#incompleteLicensesModal').modal('show');
  
  $.ajax({
    url: BASE_URL + '/license/crudData/incompleteLicenses',
    method: 'GET',
    dataType: 'json',
    success: function(res) {
      if (res.success && res.data && res.data.length > 0) {
        $('#incompleteLicensesContent').html(buildIncompleteLicensesHTML(res.data));
      } else {
        $('#incompleteLicensesContent').html(getNoDataAlertHTML('No incomplete licenses found.'));
      }
    },
    error: function() {
      $('#incompleteLicensesContent').html(getErrorAlertHTML('Failed to load incomplete licenses.'));
    }
  });
  
  setActiveFilter('incomplete');
  $('#clearFilterBtn').show();
  
  if (licensesTable) {
    licensesTable.ajax.reload();
  }
}

/**
 * Build HTML for incomplete licenses
 * @param {Array} licenses - License data array
 * @returns {string} HTML string
 */
function buildIncompleteLicensesHTML(licenses) {
  let html = '<div class="list-group">';
  
  licenses.forEach(function(license) {
    const createdDate = license.created_at ? new Date(license.created_at).toLocaleDateString('en-US') : 'N/A';
    const licenseNum = license.license_number || '<span class="text-danger">Not Set</span>';
    const clientName = license.client_name || '<span class="text-danger">Not Set</span>';
    const bankName = license.bank_name || '<span class="text-danger">Not Set</span>';
    
    html += `
      <div class="incomplete-license-item">
        <div class="row">
          <div class="col-md-12">
            <h6 class="mb-2">
              <strong>License:</strong> ${licenseNum}
              <span class="badge bg-warning text-dark ms-2">${license.missing_fields.length} Missing Field${license.missing_fields.length > 1 ? 's' : ''}</span>
            </h6>
            <div class="row mb-2">
              <div class="col-md-4">
                <small><strong>Client:</strong> ${clientName}</small>
              </div>
              <div class="col-md-4">
                <small><strong>Bank:</strong> ${bankName}</small>
              </div>
              <div class="col-md-4">
                <small><strong>Created:</strong> ${createdDate}</small>
              </div>
            </div>
            <div class="missing-fields-list">
              <small><strong class="text-danger">Missing Fields:</strong></small><br>
    `;
    
    if (license.missing_fields && license.missing_fields.length > 0) {
      license.missing_fields.forEach(function(field) {
        const badgeClass = field.includes('(Required)') ? 'badge-required' : 'badge-optional';
        html += `<span class="missing-field-badge ${badgeClass}">${field}</span>`;
      });
    } else {
      html += `<span class="text-muted">No missing fields identified</span>`;
    }
    
    html += `
            </div>
          </div>
        </div>
      </div>
    `;
  });
  
  html += '</div>';
  return html;
}

/**
 * Get no data alert HTML
 * @param {string} message - Message to display
 * @returns {string} HTML string
 */
function getNoDataAlertHTML(message) {
  return `<div class="alert alert-info mb-0"><i class="ti ti-info-circle me-2"></i>${message}</div>`;
}

/**
 * Get error alert HTML
 * @param {string} message - Message to display
 * @returns {string} HTML string
 */
function getErrorAlertHTML(message) {
  return `<div class="alert alert-danger mb-0"><i class="ti ti-alert-circle me-2"></i>${message}</div>`;
}

/**
 * Get loading HTML
 * @param {string} message - Loading message
 * @returns {string} HTML string
 */
function getLoadingHTML(message) {
  return `
    <div class="text-center p-4">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-2">${message}</p>
    </div>
  `;
}

// ===== DATATABLE INITIALIZATION =====

/**
 * Initialize DataTable
 */
function initDataTable() {
  if ($.fn.DataTable.isDataTable('#licenseTable')) {
    $('#licenseTable').DataTable().destroy();
  }

  licensesTable = $('#licenseTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: BASE_URL + '/license/crudData/listing',
      type: 'GET',
      data: function(d) {
        d.filter = currentFilter;
      },
      error: function (xhr, error, thrown) {
        console.error('DataTable Error:', error, thrown);
        showErrorMessage('Failed to load data');
      }
    },
    columns: [
      { data: 'license_number' },
      { data: 'client_name' },
      { data: 'bank_name' },
      { data: 'invoice_number' },
      {
        data: 'license_applied_date',
        render: function (data) {
          return data ? new Date(data).toLocaleDateString('en-US') : '-';
        }
      },
      {
        data: 'license_expiry_date',
        render: function (data) {
          return data ? new Date(data).toLocaleDateString('en-US') : '-';
        }
      },
      {
        data: 'status',
        render: function (data) {
          const badges = {
            'ACTIVE': 'success',
            'INACTIVE': 'secondary',
            'ANNULATED': 'danger',
            'MODIFIED': 'warning',
            'PROROGATED': 'info'
          };
          const badge = badges[data] || 'secondary';
          return `<span class="badge bg-${badge}">${data}</span>`;
        }
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return getActionButtonsHTML(row);
        }
      }
    ],
    order: [[0, 'desc']],
    pageLength: 25,
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    responsive: true,
    drawCallback: function() {
      updateStatistics();
      currentSearch = licensesTable.search();
    }
  });
}

/**
 * Get action buttons HTML
 * @param {Object} row - Row data
 * @returns {string} HTML string
 */
function getActionButtonsHTML(row) {
  return `
    <button class="btn btn-sm btn-view viewBtn" data-id="${row.id}" title="View Details">
      <i class="ti ti-eye"></i>
    </button>
    <button class="btn btn-sm btn-primary editBtn" data-id="${row.id}" title="Edit">
      <i class="ti ti-edit"></i>
    </button>
    <button class="btn btn-sm btn-export exportBtn" data-id="${row.id}" data-license="${row.license_number}" title="Export to Excel">
      <i class="ti ti-file-spreadsheet"></i>
    </button>
    <button class="btn btn-sm btn-danger deleteBtn" data-id="${row.id}" title="Delete">
      <i class="ti ti-trash"></i>
    </button>
  `;
}

// ===== EXPORT FUNCTIONS =====

/**
 * Export all licenses to Excel
 */
function exportAllLicenses() {
  showLoadingMessage('Generating Excel...', 'Please wait while we export all licenses');

  let url = BASE_URL + '/license/crudData/exportAll';
  url += '?filter=' + encodeURIComponent(currentFilter);
  url += '&search=' + encodeURIComponent(currentSearch);

  window.location.href = url;
  
  setTimeout(function() {
    Swal.close();
  }, 1500);
}

/**
 * Export single license to Excel
 */
function exportSingleLicense() {
  const id = $(this).data('id');
  
  showLoadingMessage('Generating Excel...', 'Please wait');

  window.location.href = BASE_URL + '/license/crudData/exportLicense?id=' + id;
  
  setTimeout(function() {
    Swal.close();
  }, 1000);
}

// ===== CRUD OPERATIONS =====

/**
 * View license details
 */
function viewLicenseDetails() {
  const id = $(this).data('id');
  
  $.ajax({
    url: BASE_URL + '/license/crudData/getLicense',
    method: 'GET',
    data: { id: id },
    dataType: 'json',
    success: function (res) {
      if (res.success && res.data) {
        $('#modalDetailsContent').html(buildLicenseDetailsHTML(res.data));
        $('#viewLicenseModal').modal('show');
      } else {
        showErrorMessage(res.message || 'Failed to load license data');
      }
    },
    error: function () {
      showErrorMessage('Failed to load license data');
    }
  });
}

/**
 * Build license details HTML
 * @param {Object} license - License data
 * @returns {string} HTML string
 */
function buildLicenseDetailsHTML(license) {
  return `
    <div class="detail-row">
      <div class="row">
        <div class="col-md-6">
          <div class="detail-label">
            <i class="ti ti-file-text detail-icon"></i>License Number
          </div>
          <div class="detail-value">${license.license_number || 'N/A'}</div>
        </div>
        <div class="col-md-6">
          <div class="detail-label">
            <i class="ti ti-building detail-icon"></i>Client
          </div>
          <div class="detail-value">${license.client_name || 'N/A'}</div>
        </div>
      </div>
    </div>
    
    <div class="detail-row">
      <div class="row">
        <div class="col-md-6">
          <div class="detail-label">
            <i class="ti ti-building-bank detail-icon"></i>Bank
          </div>
          <div class="detail-value">${license.bank_name || 'N/A'}</div>
        </div>
        <div class="col-md-6">
          <div class="detail-label">
            <i class="ti ti-category detail-icon"></i>Kind
          </div>
          <div class="detail-value">${license.kind_name || 'N/A'}</div>
        </div>
      </div>
    </div>
    
    <div class="detail-row">
      <div class="row">
        <div class="col-md-6">
          <div class="detail-label">
            <i class="ti ti-file-invoice detail-icon"></i>Invoice Number
          </div>
          <div class="detail-value">${license.invoice_number || 'N/A'}</div>
        </div>
        <div class="col-md-6">
          <div class="detail-label">
            <i class="ti ti-calendar detail-icon"></i>Invoice Date
          </div>
          <div class="detail-value">${license.invoice_date ? new Date(license.invoice_date).toLocaleDateString('en-US') : 'N/A'}</div>
        </div>
      </div>
    </div>
    
    <div class="detail-row">
      <div class="row">
        <div class="col-md-4">
          <div class="detail-label">
            <i class="ti ti-calendar-event detail-icon"></i>Applied Date
          </div>
          <div class="detail-value">${license.license_applied_date ? new Date(license.license_applied_date).toLocaleDateString('en-US') : 'N/A'}</div>
        </div>
        <div class="col-md-4">
          <div class="detail-label">
            <i class="ti ti-calendar-check detail-icon"></i>Validation Date
          </div>
          <div class="detail-value">${license.license_validation_date ? new Date(license.license_validation_date).toLocaleDateString('en-US') : 'N/A'}</div>
        </div>
        <div class="col-md-4">
          <div class="detail-label">
            <i class="ti ti-calendar-x detail-icon"></i>Expiry Date
          </div>
          <div class="detail-value">${license.license_expiry_date ? new Date(license.license_expiry_date).toLocaleDateString('en-US') : 'N/A'}</div>
        </div>
      </div>
    </div>
    
    <div class="detail-row">
      <div class="row">
        <div class="col-md-6">
          <div class="detail-label">
            <i class="ti ti-weight detail-icon"></i>Weight
          </div>
          <div class="detail-value">${license.weight || 'N/A'}</div>
        </div>
        <div class="col-md-6">
          <div class="detail-label">
            <i class="ti ti-currency-dollar detail-icon"></i>FOB Declared
          </div>
          <div class="detail-value">${license.fob_declared || 'N/A'}</div>
        </div>
      </div>
    </div>
    
    <div class="detail-row">
      <div class="row">
        <div class="col-md-6">
          <div class="detail-label">
            <i class="ti ti-truck-delivery detail-icon"></i>Transport Mode
          </div>
          <div class="detail-value">${license.transport_mode_name || 'N/A'}</div>
        </div>
        <div class="col-md-6">
          <div class="detail-label">
            <i class="ti ti-user detail-icon"></i>Supplier
          </div>
          <div class="detail-value">${license.supplier || 'N/A'}</div>
        </div>
      </div>
    </div>
    
    <div class="detail-row">
      <div class="row">
        <div class="col-md-6">
          <div class="detail-label">
            <i class="ti ti-map-pin detail-icon"></i>Destination/Origin
          </div>
          <div class="detail-value">${license.destination_name || 'N/A'}</div>
        </div>
        <div class="col-md-6">
          <div class="detail-label">
            <i class="ti ti-flag detail-icon"></i>Status
          </div>
          <div class="detail-value">
            <span class="badge bg-${license.status === 'ACTIVE' ? 'success' : 'secondary'}">${license.status || 'N/A'}</span>
          </div>
        </div>
      </div>
    </div>
  `;
}

/**
 * Edit license
 */
function editLicense() {
  const id = $(this).data('id');
  
  $.ajax({
    url: BASE_URL + '/license/crudData/getLicense',
    method: 'GET',
    data: { id: id },
    dataType: 'json',
    success: function (res) {
      if (res.success && res.data) {
        populateFormForEdit(res.data);
      } else {
        showErrorMessage(res.message || 'Failed to load license data');
      }
    },
    error: function () {
      showErrorMessage('Failed to load license data');
    }
  });
}

/**
 * Populate form with license data for editing
 * @param {Object} license - License data
 */
function populateFormForEdit(license) {
  $('.form-control, .form-select').removeClass('is-invalid');
  
  // Set form mode to update
  $('#license_id').val(license.id);
  $('#formAction').val('update');
  $('#formTitle').text('Edit License');
  $('#submitBtnText').text('Update License');
  $('#resetFormBtn').show();

  // Populate all fields
  const fillableFields = [
    'kind_id', 'bank_id', 'client_id', 'license_cleared_by', 'type_of_goods_id', 'weight',
    'unit_of_measurement_id', 'currency_id', 'fob_declared', 'insurance', 'freight', 'other_costs',
    'transport_mode_id', 'invoice_number', 'invoice_date', 'supplier',
    'license_applied_date', 'license_validation_date', 'license_expiry_date',
    'fsi', 'aur', 'license_number', 'entry_post_id', 'ref_cod',
    'payment_method_id', 'payment_subtype_id', 'destination_id'
  ];

  fillableFields.forEach(function (key) {
    if (typeof license[key] !== 'undefined' && license[key] !== null) {
      $('#' + key).val(license[key]);
    } else {
      $('#' + key).val('');
    }
  });

  // Toggle fields based on kind
  isMCAType = isMCAKind(license.kind_id);
  toggleFieldsForMCAType(isMCAType);

  // If MCA type, set MCA-specific fields
  if (isMCAType) {
    $('#transport_mode_id').val(license.transport_mode_id);
    $('#currency_id').val(license.currency_id);
    $('#transport_mode_id_mca').val(license.transport_mode_id);
    $('#currency_id_mca').val(license.currency_id);
    $('#license_number').val(license.license_number);
    $('#license_number_mca').val(license.license_number);
  }

  // Load client setting for non-MCA types
  if (!isMCAType && license.client_id) {
    $('#client_id').trigger('change');
    setTimeout(function () {
      $('#license_cleared_by').val(license.license_cleared_by);
    }, 500);
  }

  // Show current files
  if (license.invoice_file) {
    $('#current_invoice_file').text('Current: ' + license.invoice_file);
  }
  if (license.license_file) {
    $('#current_license_file').text('Current: ' + license.license_file);
  }

  // Expand form and scroll
  $('#createLicense').collapse('show');
  $('html, body').animate({ scrollTop: $('#licenseForm').offset().top - 100 }, 500);
}

/**
 * Delete license
 */
function deleteLicense() {
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
      performDelete(id);
    }
  });
}

/**
 * Perform delete operation
 * @param {number} id - License ID
 */
function performDelete(id) {
  $.ajax({
    url: BASE_URL + '/license/crudData/deletion',
    method: 'POST',
    data: { id: id },
    dataType: 'json',
    success: function (res) {
      if (res.success) {
        showSuccessMessage(res.message, 1500);
        licensesTable.ajax.reload(null, false);
        updateStatistics();
      } else {
        showErrorMessage(res.message || 'Delete failed');
      }
    },
    error: function () {
      showErrorMessage('Failed to delete license');
    }
  });
}

// ===== FORM VALIDATION =====

/**
 * Handle applied date change
 */
function handleAppliedDateChange() {
  const appliedDate = $(this).val();
  $('#license_validation_date').attr('min', appliedDate);
  $('#license_expiry_date').attr('min', appliedDate);
}

/**
 * Handle validation date change
 */
function handleValidationDateChange() {
  const validationDate = $(this).val();
  $('#license_expiry_date').attr('min', validationDate);
}

/**
 * Validate numeric input
 */
function validateNumericInput() {
  const value = parseFloat($(this).val());
  if (value < 0) {
    $(this).val(0);
  }
}

/**
 * Validate file input
 */
function validateFileInput() {
  const file = this.files[0];
  if (file) {
    const fileType = file.type;
    if (fileType !== 'application/pdf') {
      $(this).val('');
      $('#createLicense').collapse('show');
      showErrorMessage('Only PDF files are allowed');
    }
  }
}

// ===== FORM SUBMISSION =====

/**
 * Handle form submission
 */
function handleFormSubmit(e) {
  e.preventDefault();

  $('.form-control, .form-select').removeClass('is-invalid');
  
  // Sync MCA fields to original fields
  if (isMCAType) {
    $('#transport_mode_id').val($('#transport_mode_id_mca').val());
    $('#currency_id').val($('#currency_id_mca').val());
  }
  
  // Check HTML5 validation
  if (!this.checkValidity()) {
    e.stopPropagation();
    $(this).find(':invalid').addClass('is-invalid');
    $('#createLicense').collapse('show');
    
    scrollToFirstError();
    return;
  }

  // Custom date validations for non-MCA types
  if (!isMCAType && !validateDates()) {
    return;
  }

  submitForm();
}

/**
 * Validate dates
 * @returns {boolean} True if valid
 */
function validateDates() {
  const appliedDate = new Date($('#license_applied_date').val());
  const validationDate = new Date($('#license_validation_date').val());
  const expiryDate = new Date($('#license_expiry_date').val());

  if (appliedDate > validationDate) {
    $('#license_validation_date').addClass('is-invalid');
    $('#createLicense').collapse('show');
    showErrorMessage('Validation date must be greater than or equal to applied date');
    return false;
  }

  if (validationDate > expiryDate) {
    $('#license_expiry_date').addClass('is-invalid');
    $('#createLicense').collapse('show');
    showErrorMessage('Expiry date must be greater than or equal to validation date');
    return false;
  }

  const invoiceDate = new Date($('#invoice_date').val());
  const todayDate = new Date();
  todayDate.setHours(0, 0, 0, 0);

  if (invoiceDate > todayDate) {
    $('#invoice_date').addClass('is-invalid');
    $('#createLicense').collapse('show');
    showErrorMessage('Invoice date cannot be in the future');
    return false;
  }
  
  return true;
}

/**
 * Submit form data
 */
function submitForm() {
  const formData = new FormData($('#licenseForm')[0]);
  const action = $('#formAction').val();
  const url = action === 'update'
    ? BASE_URL + '/license/crudData/update'
    : BASE_URL + '/license/crudData/insertion';

  const submitBtn = $('#submitBtn');
  const originalText = submitBtn.html();
  submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

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
        showSuccessMessage(res.message || 'Saved successfully', 1500);
        resetForm();
        if (licensesTable) {
          licensesTable.ajax.reload(null, false);
        }
        updateStatistics();
      } else {
        if (res.message) {
          showErrorMessage(res.message);
          $('#createLicense').collapse('show');
        }
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
      showErrorMessage(errorMsg);
    }
  });
}

/**
 * Scroll to first error field
 */
function scrollToFirstError() {
  const firstError = $('.is-invalid').first();
  if (firstError.length) {
    $('html, body').animate({
      scrollTop: firstError.offset().top - 100
    }, 300);
  }
}

// ===== FORM RESET =====

/**
 * Reset form to initial state
 */
function resetForm(e) {
  if (e) {
    e.preventDefault();
  }
  
  $('#licenseForm')[0].reset();
  $('.form-control, .form-select').removeClass('is-invalid');
  $('#license_id').val('');
  $('#formAction').val('insert');
  $('#formTitle').text('Add New License');
  $('#submitBtnText').text('Save License');
  $('#resetFormBtn').hide();
  $('#current_invoice_file, #current_license_file').text('');
  $('#createLicense').collapse('hide');
  $('#license_validation_date').removeAttr('min');
  $('#license_expiry_date').removeAttr('min');
  $('#invoice_date').attr('max', today);
  $('#license_number').attr('readonly', false).val('');
  $('#licenseNumberHelp').text('');
  
  // Reset MCA state
  isMCAType = false;
  
  // Show all sections
  $('#financialInfoSection, #invoiceTransportSection, #licenseDetailsSection, #paymentInfoSection').show();
  $('#bankField, #licenseClearedByField, #weightField').show();
  
  // Remove MCA fields
  $('#mcaTransportField, #mcaCurrencyField, #mcaLicenseNumberField').remove();
  
  // Restore required attributes
  addRequiredToStandardFields();
  
  $('html, body').animate({ scrollTop: $('#licenseForm').offset().top - 100 }, 200);
}

// ===== UI HELPER FUNCTIONS =====

/**
 * Show success message
 * @param {string} message - Message to display
 * @param {number} timer - Auto close timer (optional)
 */
function showSuccessMessage(message, timer = null) {
  const config = {
    icon: 'success',
    title: 'Success!',
    text: message
  };
  
  if (timer) {
    config.timer = timer;
    config.showConfirmButton = false;
  }
  
  Swal.fire(config);
}

/**
 * Show error message
 * @param {string} message - Message to display
 */
function showErrorMessage(message) {
  Swal.fire({
    icon: 'error',
    title: 'Error',
    html: message
  });
}

/**
 * Show loading message
 * @param {string} title - Title text
 * @param {string} text - Body text
 */
function showLoadingMessage(title, text) {
  Swal.fire({
    title: title,
    text: text,
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });
}