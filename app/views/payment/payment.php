<?php 
// Include header if your framework uses it
if (file_exists(VIEW_PATH . 'layouts/partials/header.php')) {
  include(VIEW_PATH . 'layouts/partials/header.php'); 
}
?>

<!-- Include any head / css you already have -->
<link href="<?= BASE_URL ?>/assets/pages/css/payment_request_styles.css" rel="stylesheet" type="text/css">

<style>
    .dataTables_wrapper .dataTables_info {
        float: left;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: right;
        text-align: right;
    }

    /* Compact Design */
    .page-content {
        padding: 15px 0;
    }

    .card {
        margin-bottom: 15px;
    }

    .card-header {
        padding: 12px 20px;
    }

    .card-header h4 {
        font-size: 1.1rem;
        margin: 0;
    }

    .accordion-button {
        padding: 12px 20px;
        font-size: 1rem;
    }

    .accordion-button.collapsed {
        background-color: #fff;
    }

    .accordion-body {
        padding: 20px;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 6px;
        font-size: 0.875rem;
        display: block;
        color: #666;
    }

    .form-control,
    .form-select {
        height: 38px;
        padding: 8px 12px;
        font-size: 0.875rem;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    textarea.form-control {
        height: auto;
        padding: 10px 12px;
        resize: none;
    }

    .input-group-text {
        padding: 8px 12px;
        font-size: 0.875rem;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    .section-header {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-header i {
        font-size: 1.1rem;
        color: #6c757d;
    }

    /* Grid Layout */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        margin-bottom: 15px;
    }

    .form-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 15px;
    }

    .form-grid-motif-docs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
        margin-top: 20px;
    }

    .motif-column {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .documents-column {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        align-content: start;
    }

    @media (max-width: 1400px) {
        .form-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 1200px) {
        .form-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .form-grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }

        .form-grid-motif-docs {
            grid-template-columns: 1fr;
        }

        .documents-column {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .form-grid,
        .form-grid-4 {
            grid-template-columns: 1fr;
        }

        .documents-column {
            grid-template-columns: 1fr;
        }
    }

    /* Compact MCA Section */
    .mca-wrapper {
        max-width: 100%;
    }

    .mca-toolbar {
        background: #f8f9fa;
        padding: 10px 12px;
        border-radius: 6px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .mca-toolbar-left {
        display: flex;
        gap: 6px;
        align-items: center;
        flex-wrap: wrap;
    }

    .mca-toolbar input[type="number"] {
        width: 70px;
        height: 32px;
        font-size: 0.85rem;
        padding: 6px 8px;
    }

    .mca-toolbar .btn {
        height: 32px;
        padding: 6px 12px;
        font-size: 0.8rem;
    }

    .mca-toolbar .btn i {
        font-size: 0.9rem;
        margin-right: 3px;
    }

    .mca-total {
        font-size: 0.9rem;
        font-weight: 600;
        color: #495057;
    }

    .mca-total span {
        color: #0d6efd;
        font-size: 1.1rem;
    }

    /* Small Compact Table */
    .table-mca-small {
        font-size: 0.85rem;
        margin-bottom: 0;
    }

    .table-mca-small thead th {
        padding: 8px 10px;
        background: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        font-size: 0.85rem;
    }

    .table-mca-small tbody td {
        padding: 6px 10px;
        vertical-align: middle;
    }

    .table-mca-small input {
        height: 32px;
        padding: 6px 10px;
        font-size: 0.85rem;
    }

    .table-mca-small .btn-sm {
        padding: 4px 8px;
        font-size: 0.8rem;
    }

    /* Buttons */
    .btn {
        padding: 8px 16px;
        font-size: 0.875rem;
        border-radius: 4px;
    }

    .btn i {
        margin-right: 5px;
    }

    .action-footer {
        background: #f8f9fa;
        padding: 12px 20px;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .progress {
        height: 3px;
        border-radius: 0;
    }

    .text-danger {
        color: #dc3545;
    }

    small.text-muted {
        font-size: 0.75rem;
        color: #6c757d;
        display: block;
        margin-top: 4px;
    }

    .invalid-feedback {
        font-size: 0.8rem;
    }

    .form-file-label {
        display: block;
        font-size: 0.875rem;
        margin-bottom: 6px;
        font-weight: 500;
        color: #666;
    }
</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Payment Request Form Card -->
                <div class="card shadow-sm">
                    <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                        <h4>
                            <i class="ti ti-file-invoice-dollar me-2"></i>
                            <span id="formTitle">New Payment Request</span>
                        </h4>
                        <button type="button" class="btn btn-sm btn-secondary" id="resetFormBtn" style="display:none;">
                            <i class="ti ti-plus"></i> Add New
                        </button>
                    </div>

                    <!-- Progress Indicator -->
                    <div class="progress">
                        <div class="progress-bar bg-primary" id="formProgress" role="progressbar" style="width: 50%">
                        </div>
                    </div>

                    <form id="paymentRequestForm" method="post" enctype="multipart/form-data" novalidate>
                        <input type="hidden" name="action" id="formAction" value="insert">
                        <input type="hidden" name="id" id="recordId" value="">

                        <!-- Single Accordion Container -->
                        <div class="accordion" id="paymentAccordion">
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#paymentFormContent">
                                        <i class="ti ti-file-invoice me-2"></i>
                                        Payment Request Details
                                    </button>
                                </h2>

                                <div id="paymentFormContent" class="accordion-collapse collapse"
                                    data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body">

                                        <!-- BASIC INFORMATION SECTION -->
                                        <div class="section-header">
                                            <i class="ti ti-info-circle"></i>
                                            <span>Basic Information</span>
                                        </div>

                                        <!-- First Row: 5 Fields -->
                                        <div class="form-grid">
                                            <!-- Department -->
                                            <div>
                                                <label class="form-label">Department <span
                                                        class="text-danger">*</span></label>
                                                <select name="department" id="department" class="form-select" required>
                                                    <option value="">Select</option>
                                                    <?php foreach ($dept as $p): ?>
                                                    <option value="<?= $p['id'] ?>">
                                                        <?= htmlspecialchars($p['department_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">Required</div>
                                            </div>

                                            <!-- Location -->
                                            <div>
                                                <label class="form-label">Location <span
                                                        class="text-danger">*</span></label>
                                                <select name="location" id="location" class="form-select" required>
                                                    <option value="">Select</option>
                                                    <?php foreach ($loc as $p): ?>
                                                    <option value="<?= $p['id'] ?>">
                                                        <?= htmlspecialchars($p['main_location_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">Required</div>
                                            </div>

                                            <!-- Beneficiary -->
                                            <div>
                                                <label class="form-label">Beneficiary <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="beneficiary" id="beneficiary"
                                                    class="form-control" required minlength="2" maxlength="200"
                                                    placeholder="Enter name">
                                                <div class="invalid-feedback">Required</div>
                                            </div>

                                            

                                            <!-- Client -->
                                            <div>
                                                <label class="form-label">Client <span
                                                        class="text-danger">*</span></label>
                                                <select name="client_id" id="client_id" class="form-select" required>
                                                    <option value="">Select</option>
                                                    <?php foreach ($client as $p): ?>
                                                    <option value="<?= $p['id'] ?>">
                                                        <?= htmlspecialchars($p['short_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">Required</div>
                                            </div>

                                            <!-- Payment for -->
                                            <div>
                                                <label class="form-label">Payment For <span
                                                        class="text-danger">*</span></label>
                                                <select name="pay_for" id="pay_for" class="form-select" required>
                                                    <option value="">Select</option>
                                                    <option value="0">Import Tracking</option>
                                                    <option value="1">Export Tracking</option>
                                                    <option value="2">Local Tracking</option>
                                                    <option value="3">Other</option>
                                                    <option value="4">Advance</option>

                                                </select>
                                                <div class="invalid-feedback">Required</div>
                                            </div>
                                        </div>

                                        <!-- Second Row: 5 Fields -->
                                        <div class="form-grid">
                                            <!-- Payment Type -->
                                            <div>
                                                <label class="form-label">Payment Type <span
                                                        class="text-danger">*</span></label>
                                                <select name="payment_type" id="payment_type" class="form-select"
                                                    required>
                                                    <option value="">Select</option>
                                                    <option value="Bank">Bank</option>
                                                    <option value="Cash">Cash</option>
                                                </select>
                                                <div class="invalid-feedback">Required</div>
                                            </div>

                                            <!-- Currency -->
                                            <div>
                                                <label class="form-label">Currency <span
                                                        class="text-danger">*</span></label>
                                                <select name="currency" id="currency" class="form-select" required>
                                                    <option value="">Select</option>
                                                    <?php foreach ($currency as $p): ?>
                                                    <option value="<?= $p['id'] ?>">
                                                        <?= htmlspecialchars($p['currency_short_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">Required</div>
                                            </div>

                                            <!-- Amount -->
                                            <div>
                                                <label class="form-label">Amount <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" name="amount" id="amount" class="form-control"
                                                        step="0.01" min="0" required placeholder="0.00">
                                                </div>
                                                <div class="invalid-feedback">Required</div>
                                            </div>

                                            <!-- Expense Type -->
                                            <div>
                                                <label class="form-label">Expense Type <span
                                                        class="text-danger">*</span></label>
                                                <select name="expense_type" id="expense_type" class="form-select"
                                                    required>
                                                    <option value="">Select</option>
                                                    <?php foreach ($expense as $p): ?>
                                                    <option value="<?= $p['id'] ?>">
                                                        <?= htmlspecialchars($p['expense_type_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">Required</div>
                                            </div>

                                            <!-- Cash Collector -->
                                            <div id="cash_collector_group">
                                                <label class="form-label">Cash Collector</label>
                                                <input type="text" name="cash_collector" id="cash_collector"
                                                    class="form-control" maxlength="100" placeholder="Enter name">
                                            </div>
                                        </div>
                                    <div class="form-grid">
<!-- Request -->
                                            <div>
                                                <label class="form-label">Requestee <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="requestee" id="requestee"
                                                    class="form-control" required minlength="2" maxlength="200"
                                                    placeholder="Enter name">
                                                <div class="invalid-feedback">Required</div>
                                            </div>

                                                    </div>


                                        <!-- MCA REFERENCES SECTION -->
                                        <div style="margin-top: 30px;">
                                            <div class="section-header">
                                                <i class="ti ti-file-text"></i>
                                                <span>MCA References</span>
                                            </div>

                                            <div class="mca-wrapper">
                                                <div class="mca-toolbar">
                                                    <div class="mca-toolbar-left">
                                                        <input type="number" id="num_mca_refs" class="form-control"
                                                            min="0" max="50" value="0" placeholder="0-50">
                                                        <button type="button" class="btn btn-primary"
                                                            id="add_mca_refs_btn">
                                                            <i class="ti ti-plus"></i> Add
                                                        </button>
                                                        <button type="button" class="btn btn-success"
                                                            id="selectMcaRefsBtn">
                                                            <i class="ti ti-list-check"></i> Select
                                                        </button>
                                                        <label for="excel_import" class="btn btn-info mb-0"
                                                            style="cursor: pointer;">
                                                            <i class="ti ti-file-excel"></i> Import
                                                            <input type="file" id="excel_import" accept=".xlsx,.xls"
                                                                class="d-none">
                                                        </label>
                                                    </div>
                                                    <div class="mca-total">
                                                        Total: <span id="mca_total_amount">0.00</span>
                                                    </div>
                                                </div>

                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover table-mca-small"
                                                        id="mcaFilesTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 40px;">#</th>
                                                                <th id="refHeading" style="width: 250px;">MCA Reference
                                                                </th>
                                                                <th style="width: 120px;">Amount</th>
                                                                <th style="width: 60px;" class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="mcaFilesList">
                                                            <tr>
                                                                <td colspan="4" class="text-center text-muted"
                                                                    style="padding: 15px;">
                                                                    <i class="ti ti-info-circle me-1"></i>No MCA
                                                                    references added yet
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Motif + Documents Section (Under MCA References) -->
                                                <div class="form-grid-motif-docs">
                                                    <!-- Left Column: Motif -->
                                                    <div class="motif-column">
                                                        <div>
                                                            <label class="form-label">Motif / Reason <span
                                                                    class="text-danger">*</span></label>
                                                            <textarea name="motif" id="motif" class="form-control" rows="3" required
                                                                minlength="10" maxlength="500"
                                                                placeholder="Enter reason for payment"></textarea>
                                                            <small class="text-muted">Min 10 chars, Max 500 chars</small>
                                                            <div class="invalid-feedback">Required (10-500 characters)</div>
                                                        </div>
                                                    </div>

                                                    <!-- Right Column: Documents -->
                                                    <div class="documents-column">
                                                        <!-- Document 1 -->
                                                        <div>
                                                            <label class="form-file-label">Document 1</label>
                                                            <input type="file" name="file1" id="file1" class="form-control"
                                                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                                            <small class="text-muted">Max 5MB</small>
                                                        </div>

                                                        <!-- Document 2 -->
                                                        <div>
                                                            <label class="form-file-label">Document 2</label>
                                                            <input type="file" name="file2" id="file2" class="form-control"
                                                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                                            <small class="text-muted">Max 5MB</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-footer">
                            <button type="button" class="btn btn-outline-secondary" id="reviewBtn">
                                <i class="ti ti-eye"></i> Review
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="ti ti-check"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Payment Requests List Table -->
                <div class="card shadow-sm">
                    <div
                        class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
                        <h4 class="header-title mb-0"><i class="ti ti-list me-2"></i> List of Payment Requests</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="paymentRequestsTable"
                                class="table table-striped table-bordered dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Department</th>
                                        <th>Beneficiary</th>
                                        <th>Client</th>
                                        <th>Payment For</th>
                                        <th>Amount</th>
                                        <th>Currency</th>
                                        <th>Status</th>
                                        <th>Date</th>
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

        <!-- REVIEW MODAL -->
        <div class="modal fade" id="reviewModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="ti ti-eye"></i> Review Payment Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="reviewModalBody"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Edit</button>
                        <button type="button" class="btn btn-success" id="confirmFinalSubmit">
                            <i class="ti ti-check"></i> Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- VIEW MODAL -->
        <div class="modal fade" id="viewModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="ti ti-eye"></i> Payment Request Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="viewModalBody"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?= BASE_URL ?>/js/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
         let placeholderText = 'Enter MCA reference';

        // When "Payment For" dropdown changes
        $('#pay_for').on('change', function () {
                let val = $(this).val(); //alert(val);
    
    $.ajax({
        url: "<?php echo APP_URL; ?>payment/getExpenseTypesByCategory",
        
        type: "POST",
        data: { pay_for: val },
        dataType: "json",
        success: function(res){ console.log(res);
                        let html = '<option value="">Select</option>';

            res.forEach(row => {
                html += `<option value="${row.id}">${row.expense_type_name}</option>`;
            });

            $('#expense_type').html(html);
        }
    });

            let selected = $(this).val();
            let sectionTitle = 'MCA References';
            let tableHeading = 'MCA Reference';

            switch (selected) {
                case '0':
                    sectionTitle = 'Import Tracking References';
                    tableHeading = 'Import Tracking Reference';
                     placeholderText = 'Enter Import tracking reference';
                    break;
                case '1':
                    sectionTitle = 'Export Tracking References';
                    tableHeading = 'Export Tracking Reference';
                     placeholderText = 'Enter Export tracking reference';
                    break;
                case '2':
                    sectionTitle = 'Local Tracking References';
                    tableHeading = 'Local Tracking Reference';
                     placeholderText = 'Enter Local tracking reference';
                    break;
                case '3':
                    sectionTitle = 'Other References';
                    tableHeading = 'Other Reference';
                    placeholderText = 'Enter Other reference';

                    break;
                 case '4':
                    sectionTitle = 'Advance References';
                    tableHeading = 'Advance Reference';
                    placeholderText = 'Enter Advance reference';

                    break;
                   
                default:
                    sectionTitle = 'MCA References';
                    tableHeading = 'MCA Reference';
                    placeholderText = 'Enter Mca reference';
            }

            $('.section-header span').text(sectionTitle);
            $('#refHeading').text(tableHeading);
                $('.mca-reference-input').attr('placeholder', placeholderText);
    $('#mcaFilesList').html(`
        <tr>
            <td colspan="4">
                <i class="ti ti-info-circle me-1"></i>No references added yet
            </td>
        </tr>
    `);
                $('#mca_total_amount').text('0.00');
            $('#num_mca_refs').val(0);
        });

        // Validate and calculate total
        function validateMcaAmounts() {
            let mainAmount = parseFloat($('#amount').val()) || 0;
            let total = 0;
            let hasError = false;

            $('.mca-amount-input').each(function () {
                let rowVal = parseFloat($(this).val()) || 0;

                if (mainAmount > 0 && rowVal > mainAmount) {
                    alert('❌ Individual row amount (' + rowVal + ') cannot exceed the main amount (' + mainAmount + ').');
                    $(this).val('');
                    hasError = true;
                    rowVal = 0;
                }

                total += rowVal;
            });

            $('#mca_total_amount').text(total.toFixed(2));

            if (mainAmount > 0 && total > mainAmount) {
                alert('❌ Total of all references (' + total.toFixed(2) + ') exceeds main amount (' + mainAmount + ').');
                $('#mca_total_amount').css('color', 'red');
                hasError = true;
            } else {
                $('#mca_total_amount').css('color', '');
            }

            $('#submitBtn').prop('disabled', hasError);
        }

        $(document).on('input', '.mca-amount-input', function () {
            validateMcaAmounts();
        });

        $('#amount').on('input', function () {
            validateMcaAmounts();
        });

        // Initialize DataTable
        var paymentTable = $('#paymentRequestsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo APP_URL; ?>payment/get_list",
                type: "POST",
                error: function (xhr, error, thrown) {
                    console.error('DataTable Error:', error);
                }
            },
            columns: [
                { data: 'id', width: '50px' },
                { data: 'department_name' },
                { data: 'beneficiary' },
                { data: 'client_name' },
                {
                    data: 'pay_for',
                    render: function (data) {
                        const payForMap = {
                            '0': 'Import Tracking',
                            '1': 'Export Tracking',
                            '2': 'Local Tracking',
                            '3': 'Other',
                            '4': 'Advance'
                        };
                        return payForMap[data] || 'N/A';
                    }
                },
                {
                    data: 'amount',
                    render: function (data) {
                        return parseFloat(data).toFixed(2);
                    }
                },
                { data: 'currency_short_name' },
                {
                    data: 'status',
                    render: function (data) {
                        const badges = {
                            'pending': '<span class="badge bg-warning">Pending</span>',
                            'approved': '<span class="badge bg-success">Approved</span>',
                            'rejected': '<span class="badge bg-danger">Rejected</span>'
                        };
                        return badges[data] || '<span class="badge bg-secondary">Unknown</span>';
                    }
                },
                {
                    data: 'created_at',
                    render: function (data) {
                        return data ? new Date(data).toLocaleDateString() : 'N/A';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    width: '120px',
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-sm btn-info view-payment" data-id="${row.id}" title="View">
                                <i class="ti ti-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-primary edit-payment" data-id="${row.id}" title="Edit">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-payment" data-id="${row.id}" title="Delete">
                                <i class="ti ti-trash"></i>
                            </button>
                        `;
                    }
                }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>',
                emptyTable: "No payment requests found",
                zeroRecords: "No matching records found"
            }
        });

        // Function to close accordion
        function closeAccordion() {
            $('#paymentFormContent').removeClass('show');
            $('.accordion-button').addClass('collapsed').attr('aria-expanded', 'false');
        }

        // Function to open accordion
        function openAccordion() {
            $('#paymentFormContent').addClass('show');
            $('.accordion-button').removeClass('collapsed').attr('aria-expanded', 'true');
        }

        // Reset form function
        function resetForm() {
            $('#paymentRequestForm')[0].reset();
            $('#formAction').val('insert');
            $('#recordId').val('');
            $('#formTitle').text('New Payment Request');
            $('#resetFormBtn').hide();
            
            // Clear MCA table
            $('#mcaFilesList').html(`
                <tr>
                    <td colspan="4" class="text-center text-muted" style="padding: 15px;">
                        <i class="ti ti-info-circle me-1"></i>No MCA references added yet
                    </td>
                </tr>
            `);
            
            $('#mca_total_amount').text('0.00');
            $('#paymentRequestForm').removeClass('was-validated');
        }

        // Reset button click
        $('#resetFormBtn').on('click', function() {
            resetForm();
            closeAccordion();
        });

        $('#pay_for').change(autoFillOtherReferences);
        $('#location').change(autoFillOtherReferences);

        // Add MCA Reference Rows
        $('#add_mca_refs_btn').on('click', function () {
            const numRefs = parseInt($('#num_mca_refs').val()) || 0;

            if (numRefs <= 0) {
                alert('Please enter a valid number of references');
                return;
            }

            if (numRefs > 50) {
                alert('Maximum 50 references allowed');
                return;
            }

            const tbody = $('#mcaFilesList');
            tbody.find('td[colspan="4"]').closest('tr').remove();
            const currentRows = tbody.find('tr').length;

            for (let i = 0; i < numRefs; i++) {
                const rowNum = currentRows + i + 1;
                let autoRef = "";

                const payFor = $('#pay_for').val();
                if (payFor == "3") {
                    const locText = $('#location option:selected').text().trim();
                    const firstTwo = locText.substring(0, 2).toUpperCase();
                    autoRef = `OTH-${firstTwo}-${rowNum}`;
                }

                const newRow = `
                    <tr>
                        <td class="text-center">${rowNum}</td>
                        <td>
                            <input type="text" class="form-control mca-reference-input" 
                                   name="mca_reference[]" value="${autoRef}"
                        placeholder="${placeholderText}"  required>
                        </td>
                        <td>
                            <input type="number" class="form-control mca-amount-input" 
                                   name="mca_amount[]" step="0.01" min="0" placeholder="0.00" required>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger delete-mca-row">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.append(newRow);
            }

            $('#num_mca_refs').val(0);
            calculateMcaTotal();
            autoFillOtherReferences();
        });

        function autoFillOtherReferences() {
            const payFor = $('#pay_for').val();
            if (payFor != "3") return;

            const locText = $('#location option:selected').text().trim();
            if (!locText) return;

            const firstTwo = locText.substring(0, 2).toUpperCase();

            $('.mca-reference-input').each(function (index) {
                const rowNum = index + 1;
                $(this).val(`OTH-${firstTwo}-${rowNum}`);
            });
        }

        // Delete MCA Row
        $('#mcaFilesList').on('click', '.delete-mca-row', function () {
            $(this).closest('tr').remove();
            renumberMcaRows();
            calculateMcaTotal();

            if ($('#mcaFilesList tr').length === 0) {
                $('#mcaFilesList').html(`
                    <tr>
                        <td colspan="4" class="text-center text-muted" style="padding: 15px;">
                            <i class="ti ti-info-circle me-1"></i>No MCA references added yet
                        </td>
                    </tr>
                `);
            }
        });

        // Renumber rows
        function renumberMcaRows() {
            $('#mcaFilesList tr').each(function (index) {
                const firstCell = $(this).find('td:first-child');
                if (!firstCell.attr('colspan')) {
                    firstCell.text(index + 1);
                }
            });
        }

        // Calculate total amount
        function calculateMcaTotal() {
            let total = 0;
            $('.mca-amount-input').each(function () {
                const value = parseFloat($(this).val()) || 0;
                total += value;
            });
            $('#mca_total_amount').text(total.toFixed(2));
        }

        $('#mcaFilesList').on('input', '.mca-amount-input', calculateMcaTotal);

        // Select References Button
        $('#selectMcaRefsBtn').on('click', function () {
            alert('Select References functionality - to be implemented');
        });

        // Excel Import
        $('#excel_import').on('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                alert('Excel import functionality - to be implemented');
                $(this).val('');
            }
        });

        // REVIEW BUTTON
        $('#reviewBtn').on('click', function () {
            const form = document.getElementById('paymentRequestForm');

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                alert('Please fill all required fields');
                return;
            }

            const mainAmount = parseFloat($('#amount').val()) || 0;
            const mcaTotal = parseFloat($('#mca_total_amount').text()) || 0;

            if (mcaTotal !== mainAmount) {
                alert("Total MCA Amount must match Basic Information Amount");
                return;
            }

            const department = $('#department option:selected').text().trim();
            const location = $('#location option:selected').text().trim();
            const beneficiary = $('#beneficiary').val();
            const requestee = $('#requestee').val();

            const client = $('#client_id option:selected').text().trim();
            const payFor = $('#pay_for option:selected').text().trim();
            const paymentType = $('#payment_type option:selected').text().trim();
            const currency = $('#currency option:selected').text().trim();
            const amount = $('#amount').val();
            const expenseType = $('#expense_type option:selected').text().trim();
            const motif = $('#motif').val();

            let mcaHTML = '';
            const mcaRows = $('#mcaFilesList tr');

            if (mcaRows.length && !mcaRows.first().find('td[colspan]').length) {
                mcaHTML = `
                    <table class="table table-bordered table-sm mt-3">
                        <thead><tr><th>#</th><th>Reference</th><th>Amount</th></tr></thead>
                        <tbody>
                `;

                mcaRows.each(function () {
                    const ref = $(this).find('.mca-reference-input').val() || '';
                    const amt = $(this).find('.mca-amount-input').val() || '';
                    const num = $(this).find('td:first-child').text();
                    mcaHTML += `<tr><td>${num}</td><td>${ref}</td><td>${amt}</td></tr>`;
                });

                mcaHTML += `</tbody></table>`;
            } else {
                mcaHTML = `<p class="text-muted">No References</p>`;
            }

            const reviewHTML = `
                <h6 class="mb-2">Basic Information</h6>
                <table class="table table-sm table-bordered">
                    <tr><td><strong>Department:</strong></td><td>${department}</td></tr>
                    <tr><td><strong>Location:</strong></td><td>${location}</td></tr>
                    <tr><td><strong>Beneficiary:</strong></td><td>${beneficiary}</td></tr>
                                        <tr><td><strong>Beneficiary:</strong></td><td>${requestee}</td></tr>

                    <tr><td><strong>Client:</strong></td><td>${client}</td></tr>
                    <tr><td><strong>Payment For:</strong></td><td>${payFor}</td></tr>
                    <tr><td><strong>Payment Type:</strong></td><td>${paymentType}</td></tr>
                    <tr><td><strong>Currency:</strong></td><td>${currency}</td></tr>
                    <tr><td><strong>Amount:</strong></td><td>${amount}</td></tr>
                    <tr><td><strong>Expense Type:</strong></td><td>${expenseType}</td></tr>
                </table>
                <h6 class="mb-2 mt-3">Motif</h6>
                <div class="alert alert-secondary">${motif}</div>
                <h6 class="mt-3">References</h6>
                ${mcaHTML}
                <h5 class="text-end mt-3">Total: <span class="text-primary">${$('#mca_total_amount').text()}</span></h5>
            `;

            $('#reviewModalBody').html(reviewHTML);
            new bootstrap.Modal($('#reviewModal')).show();
        });

        // Final Submit
        $('#confirmFinalSubmit').on('click', function () {
            $('#submitBtn').click();
            bootstrap.Modal.getInstance($('#reviewModal')[0]).hide();
        });

        // Form submission
        $('#paymentRequestForm').on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const action = $('#formAction').val();
            const url = action === 'update' ? 
                "<?php echo APP_URL; ?>payment/update" : 
                "<?php echo APP_URL; ?>payment/store";

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        alert('✅ ' + res.message);
                        paymentTable.ajax.reload(null, false);
                        closeAccordion();
                        resetForm();
                    } else {
                        alert('❌ ' + res.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('❌ An error occurred. Please try again.');
                }
            });
        });

        // View Payment Details
        $('#paymentRequestsTable').on('click', '.view-payment', function () {
            const id = $(this).data('id');
            
            $.ajax({
                url: "<?php echo APP_URL; ?>payment/get/" + id,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        const data = response.payment;
                        
                        let mcaHTML = '';
                        if (response.mca_references && response.mca_references.length > 0) {
                            mcaHTML = `
                                <table class="table table-bordered table-sm mt-3">
                                    <thead><tr><th>#</th><th>Reference</th><th>Amount</th></tr></thead>
                                    <tbody>
                            `;
                            
                            response.mca_references.forEach((mca, index) => {
                                mcaHTML += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${mca.reference}</td>
                                        <td>${parseFloat(mca.amount).toFixed(2)}</td>
                                    </tr>
                                `;
                            });
                            
                            mcaHTML += `</tbody></table>`;
                        } else {
                            mcaHTML = '<p class="text-muted">No references found</p>';
                        }

                        const payForMap = {
                            '0': 'Import Tracking',
                            '1': 'Export Tracking',
                            '2': 'Local Tracking',
                            '3': 'Other'
                        };

                        const viewHTML = `
                            <h6 class="mb-2">Basic Information</h6>
                            <table class="table table-sm table-bordered">
                                <tr><td><strong>ID:</strong></td><td>${data.id}</td></tr>
                                <tr><td><strong>Department:</strong></td><td>${data.department_name || 'N/A'}</td></tr>
                                <tr><td><strong>Location:</strong></td><td>${data.location_name || 'N/A'}</td></tr>
                                <tr><td><strong>Beneficiary:</strong></td><td>${data.beneficiary}</td></tr>
                                <tr><td><strong>Requestee:</strong></td><td>${data.requestee}</td></tr>

                                <tr><td><strong>Client:</strong></td><td>${data.client_name || 'N/A'}</td></tr>
                                <tr><td><strong>Payment For:</strong></td><td>${payForMap[data.pay_for] || 'N/A'}</td></tr>
                                <tr><td><strong>Payment Type:</strong></td><td>${data.payment_type}</td></tr>
                                <tr><td><strong>Currency:</strong></td><td>${data.currency_name || 'N/A'}</td></tr>
                                <tr><td><strong>Amount:</strong></td><td>${parseFloat(data.amount).toFixed(2)}</td></tr>
                                <tr><td><strong>Expense Type:</strong></td><td>${data.expense_type_name || 'N/A'}</td></tr>
                                <tr><td><strong>Cash Collector:</strong></td><td>${data.cash_collector || 'N/A'}</td></tr>
                                <tr><td><strong>Status:</strong></td><td>${data.status}</td></tr>
                                <tr><td><strong>Created:</strong></td><td>${data.created_at}</td></tr>
                            </table>
                            <h6 class="mb-2 mt-3">Motif</h6>
                            <div class="alert alert-secondary">${data.motif}</div>
                            <h6 class="mt-3">References</h6>
                            ${mcaHTML}
                        `;

                        $('#viewModalBody').html(viewHTML);
                        new bootstrap.Modal($('#viewModal')).show();
                    } else {
                        alert('❌ ' + response.message);
                    }
                },
                error: function () {
                    alert('❌ Failed to load payment details');
                }
            });
        });

        // Edit Payment
        $('#paymentRequestsTable').on('click', '.edit-payment', function () {
            const id = $(this).data('id');
            
            $.ajax({
                url: "<?php echo APP_URL; ?>payment/get/" + id,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        const data = response.payment;
                        
                        // Set form to update mode
                        $('#formAction').val('update');
                        $('#recordId').val(data.id);
                        $('#formTitle').text('Edit Payment Request #' + data.id);
                        $('#resetFormBtn').show();

                        // Fill basic fields
                        $('#department').val(data.department);
                        $('#location').val(data.location);
                        $('#beneficiary').val(data.beneficiary);
                        $('#requestee').val(data.requestee);
                        $('#client_id').val(data.client_id);
                        $('#pay_for').val(data.pay_for);
                        $('#payment_type').val(data.payment_type);
                        $('#currency').val(data.currency);
                        $('#amount').val(data.amount);
                        $('#expense_type').val(data.expense_type);
                        $('#cash_collector').val(data.cash_collector);
                        $('#motif').val(data.motif);

                        // Clear and populate MCA references
                        $('#mcaFilesList').empty();
                        
                        if (response.mca_references && response.mca_references.length > 0) {
                            response.mca_references.forEach((mca, index) => {
                                const newRow = `
                                    <tr>
                                        <td class="text-center">${index + 1}</td>
                                        <td>
                                            <input type="text" class="form-control mca-reference-input" 
                                                   name="mca_reference[]" value="${mca.reference}"
                                                   placeholder="Enter MCA Reference" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control mca-amount-input" 
                                                   name="mca_amount[]" step="0.01" min="0" 
                                                   value="${mca.amount}" placeholder="0.00" required>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger delete-mca-row">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                $('#mcaFilesList').append(newRow);
                            });
                        } else {
                            $('#mcaFilesList').html(`
                                <tr>
                                    <td colspan="4" class="text-center text-muted" style="padding: 15px;">
                                        <i class="ti ti-info-circle me-1"></i>No MCA references added yet
                                    </td>
                                </tr>
                            `);
                        }

                        calculateMcaTotal();

                        // Show accordion
                        openAccordion();

                        // Scroll to form
                        $('html, body').animate({
                            scrollTop: $('#paymentRequestForm').offset().top - 100
                        }, 500);
                    } else {
                        alert('❌ ' + response.message);
                    }
                },
                error: function () {
                    alert('❌ Failed to load payment data');
                }
            });
        });

        // Delete Payment
        $('#paymentRequestsTable').on('click', '.delete-payment', function () {
            const id = $(this).data('id');

            if (confirm('Are you sure you want to delete this payment request?')) {
                $.ajax({
                    url: "<?php echo APP_URL; ?>payment/delete/" + id,
                    type: "POST",
                    dataType: "json",
                    success: function (res) {
                        if (res.success) {
                            alert('✅ ' + res.message);
                            paymentTable.ajax.reload(null, false);
                        } else {
                            alert('❌ ' + res.message);
                        }
                    },
                    error: function () {
                        alert('❌ An error occurred. Please try again.');
                    }
                });
            }
        });
    });
</script>

<?php 
if (file_exists(VIEW_PATH . 'layouts/partials/footer.php')) {
  include(VIEW_PATH . 'layouts/partials/footer.php'); 
}
?>