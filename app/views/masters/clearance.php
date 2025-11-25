<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- 🔹 Add New Clearance -->
                <div class="card">
                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Add Clearance</h4>
                    </div>
                    <div class="card-body">
                        <form id="clearanceInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Clearance Name</label>
                                    <input type="text" name="clearance_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Display</label>
                                    <select name="display" class="form-select">
                                        <option value="Y" selected>Yes</option>
                                        <option value="N">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Save Clearance</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 🔹 Clearance List -->
                <div class="card mt-3">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">Clearance List</h4>
                    </div>
                    <div class="card-body">
                        <table id="clearance-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Clearance Name</th>
                                    <th>Display</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($result)): $i=0; foreach ($result as $row): $i++; ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= htmlspecialchars($row['clearance_name']); ?></td>
                                        <td><?= htmlspecialchars($row['display']); ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['updated_at'])); ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary editClearanceBtn" data-id="<?= $row['id']; ?>"><i class="ti ti-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger deleteClearanceBtn" data-id="<?= $row['id']; ?>"><i class="ti ti-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>
</div>

<!-- 🔹 Edit Modal -->
<div class="modal fade" id="clearanceEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="clearanceUpdateForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Clearance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Clearance Name</label>
                        <input type="text" name="clearance_name" id="edit_clearance_name" class="form-control" required>
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

<!-- 🔹 Script Section -->
<script>
$(document).ready(function () {
    // Initialize DataTable
    $('#clearance-datatable').DataTable({
        "columnDefs": [{ "orderable": false, "targets": -1 }],
        "language": { "emptyTable": "No clearance found" }
    });

    // ✅ Insert (AJAX)
    $('#clearanceInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>clearance/crudData/insertion',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                Swal.fire({
                    icon: res.success ? 'success' : 'error',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                if (res.success) location.reload();
            }
        });
    });

    // ✅ Load edit modal
    $(document).on('click', '.editClearanceBtn', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        $.get('<?php echo APP_URL; ?>clearance/getClearanceById', { id }, function (res) {
            if (res.success) {
                $('#edit_clearance_name').val(res.data.clearance_name);
                $('#edit_display').val(res.data.display);
                $('#clearanceEditModal').data('id', id).modal('show');
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json');
    });

    // ✅ Update (AJAX)
    $('#clearanceUpdateForm').submit(function (e) {
        e.preventDefault();
        const id = $('#clearanceEditModal').data('id');
        $.ajax({
            url: '<?php echo APP_URL; ?>clearance/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    $('#categoryEditModal').modal('hide');
                    location.reload();
                } else {
                    alert('❌ ' + res.message);
                }
            }
        });
    });

    // ✅ Delete (AJAX)
   // ✅ Delete Clearance (simple confirm + alert)
    $(document).on('click', '.deleteClearanceBtn', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this clearance?')) return;

        $.ajax({
            url: '<?php echo APP_URL; ?>clearance/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
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
