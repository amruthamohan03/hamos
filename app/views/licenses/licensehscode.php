<style>
  .card {
    border: 1px solid #e5e7eb;
    margin-bottom: 24px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }
  
  .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 18px 24px;
    font-weight: 600;
    font-size: 1.1rem;
    border-radius: 12px 12px 0 0;
  }
  
  .card-body {
    padding: 24px;
  }
  
  .form-label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #374151;
    font-size: 0.95rem;
  }
  
  .text-danger {
    color: #dc3545;
  }
  
  /* Table Styling */
  .table-container {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
  }
  
  table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 14px 12px;
    text-align: center;
    font-weight: 600;
    font-size: 0.9rem;
    border: none;
  }
  
  table tbody td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
    font-size: 0.9rem;
  }
  
  table tbody tr:last-child td {
    border-bottom: none;
  }
  
  table tbody tr:hover {
    background: #f9fafb;
  }
  
  /* Buttons */
  .btn {
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
    font-size: 0.95rem;
  }
  
  .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }
  
  .btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
  }
  
  .btn-secondary {
    background: #6c757d;
    color: white;
  }
  
  .btn-secondary:hover:not(:disabled) {
    background: #5a6268;
    transform: translateY(-2px);
  }
  
  .btn-danger {
    background: #dc3545;
    color: white;
    padding: 6px 12px;
  }
  
  .btn-danger:hover:not(:disabled) {
    background: #c82333;
  }
  
  .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
  
  .btn-sm {
    padding: 6px 12px;
    font-size: 0.85rem;
  }
  
  .badge {
    background: #667eea;
    color: white;
    padding: 6px 14px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
  }
  
  .ddi-badge {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 6px 14px;
    border-radius: 6px;
    font-weight: 700;
    display: inline-block;
    font-size: 0.95rem;
  }
  
  .empty-message {
    text-align: center;
    padding: 40px;
    color: #9ca3af;
    font-size: 1rem;
  }
  
  /* Modal Styling */
  .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px 28px;
    border: none;
  }
  
  .modal-header .modal-title {
    font-weight: 600;
    font-size: 1.25rem;
  }
  
  .modal-header .btn-close {
    filter: brightness(0) invert(1);
    opacity: 1;
  }
  
  .modal-body {
    padding: 28px;
    max-height: 580px;
    overflow-y: auto;
    background: #f9fafb;
  }
  
  .modal-footer {
    padding: 18px 28px;
    border-top: 1px solid #e5e7eb;
    background: #fff;
  }
  
  /* Search Box */
  .search-wrapper {
    position: relative;
    margin-bottom: 20px;
  }
  
  .search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 1.1rem;
    pointer-events: none;
  }
  
  #hscodeSearch {
    padding: 14px 16px 14px 48px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 1rem;
    width: 100%;
    background: white;
    transition: all 0.2s;
  }
  
  #hscodeSearch:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
  }
  
  /* Count Badge */
  .count-badge {
    display: inline-block;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 1.05rem;
    margin-bottom: 20px;
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
  }
  
  .count-badge i {
    margin-right: 8px;
  }
  
  /* HS Code List */
  .hscode-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .hscode-item {
    background: white;
    padding: 20px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    margin-bottom: 14px;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 18px;
  }
  
  .hscode-item:hover:not(.already-assigned) {
    border-color: #667eea;
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.15);
    transform: translateY(-3px);
  }
  
  .hscode-item.selected {
    background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%);
    border-color: #667eea;
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.25);
    transform: translateY(-2px);
  }
  
  .hscode-item.already-assigned {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-color: #dc3545;
    cursor: not-allowed;
    opacity: 0.8;
  }
  
  .hscode-checkbox {
    width: 24px;
    height: 24px;
    cursor: pointer;
    flex-shrink: 0;
    accent-color: #667eea;
  }
  
  .hscode-checkbox:disabled {
    cursor: not-allowed;
    opacity: 0.4;
  }
  
  .hscode-content {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
  }
  
  .hscode-info {
    flex: 1;
  }
  
  .hscode-number {
    font-weight: 700;
    font-size: 1.2rem;
    color: #1f2937;
    margin: 0;
    letter-spacing: 0.5px;
  }
  
  .already-assigned-badge {
    display: inline-block;
    background: #dc3545;
    color: white;
    padding: 5px 14px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    margin-left: 14px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }
  
  /* DDI Display Section */
  .ddi-section {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(102, 126, 234, 0.08);
    padding: 12px 20px;
    border-radius: 10px;
    border: 2px solid transparent;
    min-width: 240px;
  }
  
  .hscode-item.selected .ddi-section {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.15);
  }
  
  .ddi-label {
    font-weight: 700;
    color: #667eea;
    font-size: 0.95rem;
    margin: 0;
    white-space: nowrap;
  }
  
  .ddi-display {
    padding: 10px 18px;
    border: 3px solid #667eea;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.2rem;
    text-align: center;
    background: white;
    color: #1f2937;
    min-width: 100px;
    letter-spacing: 0.5px;
  }
  
  .ddi-percent {
    font-weight: 700;
    color: #667eea;
    font-size: 1.2rem;
  }
  
  /* No Results */
  .no-results {
    background: white;
    text-align: center;
    padding: 50px 20px;
    border-radius: 12px;
    border: 2px dashed #e5e7eb;
    color: #9ca3af;
    font-size: 1.05rem;
  }
  
  .no-results i {
    font-size: 3.5rem;
    color: #e5e7eb;
    margin-bottom: 12px;
    display: block;
  }
  
  /* Loading Spinner */
  .spinner-border {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    vertical-align: text-bottom;
    border: 0.15em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border 0.75s linear infinite;
  }
  
  @keyframes spinner-border {
    to { transform: rotate(360deg); }
  }
  
  .spinner-border-sm {
    width: 0.875rem;
    height: 0.875rem;
    border-width: 0.125em;
  }
</style>

<div class="page-content">
  <div class="page-container">
    <div class="row">
      <div class="col-12">

        <!-- License Selection Card -->
        <div class="card">
          <div class="card-header">
            <i class="ti ti-license me-2"></i> Assign HS Codes to License
          </div>
          <div class="card-body">
            
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">License Number <span class="text-danger">*</span></label>
                <select id="license_select" class="form-select">
                  <option value="">-- Select License --</option>
                  <?php if (!empty($licenses)): ?>
                    <?php foreach ($licenses as $license): ?>
                      <option value="<?= $license['id'] ?>" data-license-number="<?= htmlspecialchars($license['license_number']) ?>">
                        <?= htmlspecialchars($license['license_number']) ?>
                        <?php if ($license['assigned_count'] > 0): ?>
                          (<?= $license['assigned_count'] ?> HS Codes)
                        <?php endif; ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
              <div class="col-md-6 text-end align-self-end">
                <button type="button" class="btn btn-secondary" id="clearBtn" style="display: none;">
                  <i class="ti ti-x me-2"></i>Clear
                </button>
                <button type="button" class="btn btn-primary" id="openModalBtn" style="display: none;">
                  <i class="ti ti-plus me-2"></i>Select HS Codes
                </button>
              </div>
            </div>

          </div>
        </div>

        <!-- Assigned HS Codes Table -->
        <div class="card" id="listCard" style="display: none;">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="ti ti-list-check me-2"></i> Assigned HS Codes</span>
            <span class="badge" id="hsCodeCount">0</span>
          </div>
          <div class="card-body">
            <div class="table-container">
              <table id="hscodesTable">
                <thead>
                  <tr>
                    <th width="8%">#</th>
                    <th width="30%">License Number</th>
                    <th width="30%">HS Code Number</th>
                    <th width="15%">DDI (%)</th>
                    <th width="17%">Action</th>
                  </tr>
                </thead>
                <tbody id="hscodesTableBody">
                  <tr>
                    <td colspan="5" class="empty-message">
                      <i class="ti ti-inbox" style="font-size: 3rem; display: block; margin-bottom: 10px; color: #e5e7eb;"></i>
                      No HS Codes assigned yet
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>
</div>

<!-- HS Code Selection Modal -->
<div class="modal fade" id="hscodeModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti ti-checkup-list me-2"></i> Select HS Codes (DDI from Master)
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        
        <!-- Search Box -->
        <div class="search-wrapper">
          <i class="ti ti-search search-icon"></i>
          <input type="text" id="hscodeSearch" class="form-control" placeholder="Search HS Code Number..." autocomplete="off">
        </div>

        <!-- Selected Count -->
        <div class="count-badge">
          <i class="ti ti-circle-check"></i>
          <span id="selectedCount">0</span> Selected
        </div>

        <!-- HS Code List -->
        <ul class="hscode-list" id="hscodeList">
          <?php if (!empty($hscodes)): ?>
            <?php foreach ($hscodes as $hscode): ?>
              <li class="hscode-item" data-id="<?= $hscode['id'] ?>" data-number="<?= strtolower($hscode['hscode_number']) ?>" data-ddi="<?= $hscode['hscode_ddi'] ?>">
                <input type="checkbox" class="hscode-checkbox" value="<?= $hscode['id'] ?>" data-ddi="<?= $hscode['hscode_ddi'] ?>">
                <div class="hscode-content">
                  <div class="hscode-info">
                    <div class="hscode-number">
                      <?= htmlspecialchars($hscode['hscode_number']) ?>
                      <span class="already-assigned-badge" style="display: none;">Already Assigned</span>
                    </div>
                  </div>
                  <div class="ddi-section">
                    <label class="ddi-label">DDI Value:</label>
                    <div class="ddi-display"><?= number_format($hscode['hscode_ddi'], 2) ?></div>
                    <span class="ddi-percent">%</span>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="no-results">
              <i class="ti ti-database-off"></i>
              No HS Codes available
            </li>
          <?php endif; ?>
        </ul>

        <!-- No Results Message -->
        <div id="noResultsMessage" class="no-results" style="display: none;">
          <i class="ti ti-search-off"></i>
          No matching HS Codes found
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-2"></i> Cancel
        </button>
        <button type="button" class="btn btn-primary" id="assignSelectedBtn">
          <i class="ti ti-check me-2"></i> Assign Selected HS Codes
        </button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Cache frequently used selectors
  const $licenseSelect = $('#license_select');
  const $clearBtn = $('#clearBtn');
  const $openModalBtn = $('#openModalBtn');
  const $listCard = $('#listCard');
  const $hsCodesTableBody = $('#hscodesTableBody');
  const $hsCodeCount = $('#hsCodeCount');
  const $hscodeModal = $('#hscodeModal');
  const $hscodeSearch = $('#hscodeSearch');
  const $selectedCount = $('#selectedCount');
  const $assignSelectedBtn = $('#assignSelectedBtn');
  const $hscodeList = $('#hscodeList');
  const $noResultsMessage = $('#noResultsMessage');
  
  // State management
  let selectedLicenseId = null;
  let selectedLicenseNumber = '';
  let assignedHsCodes = [];
  let isLoading = false;
  let ajaxRequest = null;

  // Initialize Select2 with optimized settings
  $licenseSelect.select2({ 
    placeholder: '-- Select License --', 
    allowClear: true, 
    width: '100%',
    minimumResultsForSearch: 10
  });

  // License Selection Change
  $licenseSelect.on('change', function() {
    const $selected = $(this).find(':selected');
    selectedLicenseId = $(this).val();
    selectedLicenseNumber = $selected.data('license-number') || '';
    
    if (selectedLicenseId) {
      $clearBtn.show();
      $openModalBtn.show();
      $listCard.show();
      loadHsCodes(selectedLicenseId);
    } else {
      resetView();
    }
  });

  // Clear Button
  $clearBtn.on('click', resetView);

  // Reset View Function
  function resetView() {
    $licenseSelect.val(null).trigger('change');
    $clearBtn.hide();
    $openModalBtn.hide();
    $listCard.hide();
    selectedLicenseId = null;
    selectedLicenseNumber = '';
    assignedHsCodes = [];
    $hsCodesTableBody.html(getEmptyMessage());
  }

  // Get Empty Message HTML
  function getEmptyMessage() {
    return '<tr><td colspan="5" class="empty-message"><i class="ti ti-inbox" style="font-size: 3rem; display: block; margin-bottom: 10px; color: #e5e7eb;"></i>No HS Codes assigned yet</td></tr>';
  }

  // Open Modal with optimized setup
  $openModalBtn.on('click', function() {
    if (!selectedLicenseId) {
      alert('⚠️ Please select a license first');
      return;
    }
    
    // Reset modal state efficiently
    $('.hscode-checkbox').prop('checked', false).prop('disabled', false);
    $('.hscode-item').removeClass('selected already-assigned');
    $('.already-assigned-badge').hide();
    
    // Mark already assigned HS codes using Set for O(1) lookups
    const assignedSet = new Set(assignedHsCodes);
    $('.hscode-item').each(function() {
      const hscodeId = parseInt($(this).data('id'));
      if (assignedSet.has(hscodeId)) {
        $(this).addClass('already-assigned')
               .find('.hscode-checkbox').prop('disabled', true);
        $(this).find('.already-assigned-badge').show();
      }
    });
    
    updateCount();
    $hscodeModal.modal('show');
  });

  // Debounced Search Function (300ms delay)
  let searchTimeout;
  $hscodeSearch.on('input', function() {
    clearTimeout(searchTimeout);
    const term = $(this).val().toLowerCase();
    
    searchTimeout = setTimeout(function() {
      performSearch(term);
    }, 300);
  });

  // Optimized Search Function
  function performSearch(term) {
    if (!term) {
      $('.hscode-item').show();
      $noResultsMessage.hide();
      $hscodeList.show();
      return;
    }
    
    let visibleCount = 0;
    const $items = $('.hscode-item');
    
    // Use requestAnimationFrame for smoother performance
    requestAnimationFrame(function() {
      $items.each(function() {
        const itemNumber = $(this).data('number');
        if (itemNumber.includes(term)) {
          $(this).show();
          visibleCount++;
        } else {
          $(this).hide();
        }
      });
      
      if (visibleCount === 0) {
        $noResultsMessage.show();
        $hscodeList.hide();
      } else {
        $noResultsMessage.hide();
        $hscodeList.show();
      }
    });
  }

  // Checkbox Change using event delegation
  $(document).on('change', '.hscode-checkbox', function() {
    $(this).closest('.hscode-item').toggleClass('selected', this.checked);
    updateCount();
  });

  // Click on Item using event delegation
  $(document).on('click', '.hscode-item', function(e) {
    if ($(this).hasClass('already-assigned')) return;
    
    if (!$(e.target).is('.hscode-checkbox')) {
      const $checkbox = $(this).find('.hscode-checkbox');
      if (!$checkbox.prop('disabled')) {
        $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
      }
    }
  });

  // Update Selected Count
  function updateCount() {
    $selectedCount.text($('.hscode-checkbox:checked').length);
  }

  // Assign Selected HS Codes with optimized data gathering
  $assignSelectedBtn.on('click', function() {
    if (isLoading) return;
    
    const data = [];
    $('.hscode-checkbox:checked').each(function() {
      const ddi = parseFloat($(this).data('ddi')) || 0;
      data.push({
        id: parseInt(this.value),
        ddi: ddi
      });
    });

    if (data.length === 0) {
      alert('⚠️ Please select at least one HS Code');
      return;
    }

    assignHsCodes(data);
  });

  // Assign HS Codes Function
  function assignHsCodes(data) {
    isLoading = true;
    const originalText = $assignSelectedBtn.html();
    $assignSelectedBtn.prop('disabled', true)
                      .html('<span class="spinner-border spinner-border-sm me-2"></span>Assigning...');

    $.ajax({
      url: '<?= APP_URL ?>licensehscode/crudData/insertion',
      method: 'POST',
      data: { 
        license_id: selectedLicenseId, 
        hscode_data: data 
      },
      dataType: 'json',
      timeout: 30000,
      success: function(res) {
        if (res.success) {
          alert('✅ ' + res.message);
          $hscodeModal.modal('hide');
          loadHsCodes(selectedLicenseId);
        } else {
          alert('❌ ' + res.message);
        }
      },
      error: function(xhr, status, error) {
        console.error('Assignment error:', error);
        alert('❌ Failed to assign HS Codes. Please try again.');
      },
      complete: function() {
        isLoading = false;
        $assignSelectedBtn.prop('disabled', false).html(originalText);
      }
    });
  }

  // Load Assigned HS Codes with caching
  function loadHsCodes(id) {
    // Abort previous request if still running
    if (ajaxRequest) {
      ajaxRequest.abort();
    }
    
    ajaxRequest = $.ajax({
      url: '<?= APP_URL ?>licensehscode/getHsCodesForLicense',
      method: 'GET',
      data: { license_id: id },
      dataType: 'json',
      timeout: 15000,
      success: function(res) {
        if (res.success) {
          assignedHsCodes = res.data.map(item => parseInt(item.hscode_id));
          displayHsCodes(res.data);
        } else {
          console.error('Failed to load HS codes:', res.message);
          displayHsCodes([]);
        }
      },
      error: function(xhr, status, error) {
        if (status !== 'abort') {
          console.error('Load error:', error);
          displayHsCodes([]);
        }
      },
      complete: function() {
        ajaxRequest = null;
      }
    });
  }

  // Display HS Codes in Table with optimized DOM manipulation
  function displayHsCodes(data) {
    $hsCodeCount.text(data.length);
    
    if (data.length === 0) {
      $hsCodesTableBody.html(getEmptyMessage());
      return;
    }
    
    // Use DocumentFragment for better performance
    const fragment = document.createDocumentFragment();
    const tbody = document.createElement('tbody');
    
    data.forEach((item, i) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><strong>${i + 1}</strong></td>
        <td><strong>${escapeHtml(selectedLicenseNumber)}</strong></td>
        <td><strong>${escapeHtml(item.hscode_number)}</strong></td>
        <td><span class="ddi-badge">${item.license_ddi}%</span></td>
        <td>
          <button class="btn btn-sm btn-danger delete-btn" data-id="${item.id}">
            <i class="ti ti-trash me-1"></i>Delete
          </button>
        </td>
      `;
      tbody.appendChild(tr);
    });
    
    fragment.appendChild(tbody);
    $hsCodesTableBody.empty().append(fragment.firstChild.childNodes);
  }

  // HTML Escape Function
  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  // Delete HS Code using event delegation
  $(document).on('click', '.delete-btn', function() {
    if (!confirm('⚠️ Are you sure you want to remove this HS Code from the license?')) {
      return;
    }
    
    const id = $(this).data('id');
    const $btn = $(this);
    const originalText = $btn.html();
    
    $btn.prop('disabled', true)
        .html('<span class="spinner-border spinner-border-sm"></span>');
    
    $.ajax({
      url: '<?= APP_URL ?>licensehscode/crudData/deletion',
      method: 'POST',
      data: { id: id },
      dataType: 'json',
      timeout: 10000,
      success: function(res) {
        if (res.success) {
          alert('✅ ' + res.message);
          loadHsCodes(selectedLicenseId);
        } else {
          alert('❌ ' + res.message);
          $btn.prop('disabled', false).html(originalText);
        }
      },
      error: function() {
        alert('❌ Failed to remove HS Code');
        $btn.prop('disabled', false).html(originalText);
      }
    });
  });

  // Reset Modal on Close
  $hscodeModal.on('hidden.bs.modal', function() {
    $hscodeSearch.val('');
    clearTimeout(searchTimeout);
    $('.hscode-item').show();
    $noResultsMessage.hide();
    $hscodeList.show();
  });

  // Prevent memory leaks
  $(window).on('beforeunload', function() {
    if (ajaxRequest) {
      ajaxRequest.abort();
    }
  });
});
</script>