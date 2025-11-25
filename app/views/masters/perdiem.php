<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Paymentsubtype Master</h4>
                    </div>

                    <div class="card-body">
                        <form id="paymentsubtypeInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="payment_subtype" class="form-label">Paymentsubtype Name</label>
                                    <input type="text" class="form-control" id="payment_subtype" name="payment_subtype"
                                        placeholder="Enter paymentsubtype name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                        <label class="form-label">Payment type</label>
                        <select name="payment_type_name" id="payment_type_name" class="form-select">
                          <option value="">-- Select Payment type --</option>
                          <?php foreach ($paymenttype as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['payment_type_name']) ?></option>
                          <?php endforeach; ?>
                        </select>
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
                                    <i class="mdi mdi-content-save"></i> Save paymentsubtype
                                </button>
                                <a href="" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <h4 class="header-title">paymentsubtype List</h4>
                        <table id="perdiem-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>                                    
                                    <th>Paymentsubtype Name</th>
                                    <th>Payment type </th>
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
                                            <td><?= htmlspecialchars($row['payment_subtype']); ?></td>
                                            <td><?= htmlspecialchars($row['payment_type_name']); ?></td>
                                            <td><?= htmlspecialchars($row['display']); ?></td>
                                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                                            <td><?= htmlspecialchars($row['updated_at']); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editpaymentsubtypeBtn"
                                                    data-id="<?= $row['id']; ?>" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                
                                 <a href="#" class="btn btn-sm btn-danger deletepaymentsubtypeBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center text-muted">No paymentsubtype types found</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="paymentsubtypeEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="paymentsubtypeUpdateForm" type="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit paymentsubtype</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Paymentsubtype Name</label>
                        <input type="text" name="payment_subtype" id="edit_payment_subtype" class="form-control" required>
                    </div>
                     <div class="col-md-6 mb-3">
                        <label class="form-label">Payment type</label>
                        <select name="payment_type_name" id="edit_payment_type_name" class="form-select">
                          <option value="">-- Select Payment type --</option>
                          <?php foreach ($paymenttype as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['payment_type_name']) ?></option>
                          <?php endforeach; ?>
                        </select>
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
        $('#perdiem-datatable').DataTable();
 
    $('#paymentsubtypeInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>paymentsubtype/crudData/insertion',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    // Edit modal load
    $(document).on('click', '.editpaymentsubtypeBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>paymentsubtype/getpaymentsubtypeById',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#edit_payment_subtype').val(res.data.payment_subtype);
                    $('#edit_payment_type_name').val(res.data.payment_type_id);

                    $('#edit_display').val(res.data.display);
                    $('#paymentsubtypeEditModal').data('id', id).modal('show');
                } else {
                    alert('paymentsubtype not found');
                }
            },
            error: function () {
                alert('Error fetching paymentsubtype data.');
            }
        });
    });
    
    $(document).on('click', '.deletepaymentsubtypeBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this paymentsubtype?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>paymentsubtype/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    // AJAX update
    $('#paymentsubtypeUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#paymentsubtypeEditModal').data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>paymentsubtype/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    $('#paymentsubtypeEditModal').modal('hide');
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
