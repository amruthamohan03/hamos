<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Invoice bank Master</h4>
                    </div>

                    <div class="card-body">
                        <form id="invoiceInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="invoice_bank_account_name" class="form-label">Bank account name</label>
                                    <input type="text" class="form-control" id="invoice_bank_account_name" name="invoice_bank_account_name"
                                        placeholder="Enter bank account name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="invoice_bank_account_number" class="form-label">Bank account number</label>
                                    <input type="text" class="form-control" id="invoice_bank_account_number" name="invoice_bank_account_number"
                                        placeholder="Enter bank account number" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="invoice_bank_address" class="form-label">Bank address</label>
                                    <input type="text" class="form-control" id="invoice_bank_address" name="invoice_bank_address"
                                        placeholder="Enter bank account address" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="invoice_bank_name" class="form-label">Bank name</label>
                                    <input type="text" class="form-control" id="invoice_bank_name" name="invoice_bank_name"
                                        placeholder="Enter bank name" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="invoice_bank_swift" class="form-label">Bank swift</label>
                                    <input type="text" class="form-control" id="invoice_bank_swift" name="invoice_bank_swift"
                                        placeholder="Enter bank swift" required>
                                </div>
                                

                                <div class="col-md-6 mb-3">
                                    <label for="display" class="form-label">Display</label>
                                    <select class="form-select" id="display" name="display">
                                        <option value="Y" selected>Yes</option>
                                        <option value="N">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Save Invoice bank
                                </button>
                                <a href="" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <h4 class="header-title">Bank List</h4>
                        <table id="invoice-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Bank aaount name</th>
                                    <th>Bank account number</th>
                                     <th>Bank address</th>
                                    <th>Bank name</th>
                                    <th>Bank swift</th>
                                    <th>Display</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($result)): ?>
                                    <?php foreach ($result as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['id']); ?></td>
                                            <td><?= htmlspecialchars($row['invoice_bank_account_name']); ?></td>
                                            <td><?= htmlspecialchars($row['invoice_bank_account_number']); ?></td>
                                            <td><?= htmlspecialchars($row['invoice_bank_address']); ?></td>
                                            <td><?= htmlspecialchars($row['invoice_bank_name']); ?></td>
                                            <td><?= htmlspecialchars($row['invoice_bank_swift']); ?></td>
                                            <td><?= htmlspecialchars($row['display']); ?></td>
                                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                                            <td><?= htmlspecialchars($row['updated_at']); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editinvoiceBtn"
                                                    data-id="<?= $row['id']; ?>" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                    <a href="#" class="btn btn-sm btn-danger deleteinvoiceBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>

                            <?php if (empty($result)): ?>
                            <tfoot>
                                <tr><td colspan="10" class="text-center text-muted"></td></tr>
                            </tfoot>
                            <?php endif; ?>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="invoiceEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="invoiceUpdateForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit invoice bank </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Invoice bank account name</label>
                        <input type="text" name="invoice_bank_account_name" id="edit_invoice_bank_account_name" class="form-control" required>
                    </div>
                   <div class="mb-2">
                        <label>Invoice bank account number</label>
                        <input type="text" name="invoice_bank_account_number" id="edit_invoice_bank_account_number" class="form-control" required>
                    </div>
                     <div class="mb-2">
                        <label>Invoice bank address</label>
                        <input type="text" name="invoice_bank_address" id="edit_invoice_bank_address" class="form-control" required>
                    </div>
                   <div class="mb-2">
                        <label>Invoice bank name</label>
                        <input type="text" name="invoice_bank_name" id="edit_invoice_bank_name" class="form-control" required>
                    </div>
                   <div class="mb-2">
                        <label>Invoice </label>
                        <input type="text" name="invoice_bank_swift" id="edit_invoice_bank_swift" class="form-control" required>
                    </div>
                    
                    <div class="mb-2">
                        <label>Display</label>
                        <select name="display" id="edit_display" class="form-select">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#invoice-datatable').DataTable();

    $('#invoiceInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>invoice/crudData/insertion',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    $(document).on('click', '.deleteinvoiceBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this invoice bank details?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>invoice/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    // Edit modal load
    $(document).on('click', '.editinvoiceBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>invoice/getinvoiceById',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#edit_invoice_bank_account_name').val(res.data.invoice_bank_account_name);
                    $('#edit_invoice_bank_account_number').val(res.data.invoice_bank_account_number);
                    $('#edit_invoice_bank_address').val(res.data.invoice_bank_address);
                    $('#edit_invoice_bank_name').val(res.data.invoice_bank_name);
                    $('#edit_invoice_bank_swift').val(res.data.invoice_bank_swift);
                    $('#edit_display').val(res.data.display);
                    $('#invoiceEditModal').data('id', id).modal('show');
                } else {
                    alert('invoice bank details not found');
                }
            },
            error: function () {
                alert('Error fetching invoice bank details data.');
            }
        });
    });

    // AJAX update
    $('#invoiceUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#invoiceEditModal').data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>invoice/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    $('#invoiceEditModal').modal('hide');
                    location.reload();
                } else {
                    alert('❌ ' + res.message);
                }
            },
            error: function (xhr) {
                alert('AJAX Error: ' + xhr.responseText);
            }
        });
    });
});
</script>
