<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Payment Method Master</h4>
                    </div>

                    <div class="card-body">
                        <form id="paymentmethodInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="payment_method_name" class="form-label">Payment method Name</label>
                                    <input type="text" class="form-control" id="payment_method_name" name="payment_method_name"
                                        placeholder="Enter payment method name" required>
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
                                    <i class="mdi mdi-content-save"></i> Save Payment method
                                </button>
                                <a href="" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <h4 class="header-title">Payment method List</h4>
                        <table id="paymentmethod-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Payment Method Name</th>
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
                                            <td><?= htmlspecialchars($row['payment_method_name']); ?></td>
                                            <td><?= htmlspecialchars($row['display']); ?></td>
                                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                                            <td><?= htmlspecialchars($row['updated_at']); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editPaymentmethodBtn"
                                                    data-id="<?= $row['id']; ?>" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                            <a href="#" class="btn btn-sm btn-danger deletepaymentmethodBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>

                                            </td>
                                        </tr>
                                   <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>

                            <?php if (empty($result)): ?>
                            <tfoot>
                                <tr><td colspan="6" class="text-center text-muted"></td></tr>
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
<div class="modal fade" id="paymentmethodEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="paymentmethodUpdateForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Payment method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Payment method Name</label>
                        <input type="text" name="payment_method_name" id="edit_payment_method_name" class="form-control" required>
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
    $('#paymentmethod-datatable').DataTable();

    $('#paymentmethodInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>paymentmethod/crudData/insertion',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });


    $(document).on('click', '.deletepaymentmethodBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this payment method?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>paymentmethod/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    // Edit modal load
    $(document).on('click', '.editPaymentmethodBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>paymentmethod/getPaymentmethodById',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#edit_payment_method_name').val(res.data.payment_method_name);
                    $('#edit_display').val(res.data.display);
                    $('#paymentmethodEditModal').data('id', id).modal('show');
                } else {
                    alert('Payment methods not found');
                }
            },
            error: function () {
                alert('Error fetching payment method data.');
            }
        });
    });

    // AJAX update
    $('#paymentmethodUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#paymentmethodEditModal').data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>paymentmethod/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    $('#paymentmethodEditModal').modal('hide');
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
