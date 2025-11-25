<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- Add Clearing Status -->
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">Add New Clearing Status</h4>
                    </div>
                    <div class="card-body">
                        <form id="statusInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Clearing Status</label>
                                    <input type="text" name="clearing_status" class="form-control" required>
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
                                <button type="submit" class="btn btn-primary">Save Status</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Clearing Status List -->
                <div class="card mt-3">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">Clearing Status List</h4>
                    </div>
                    <div class="card-body">
                        <table id="status-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Clearing Status</th>
                                    <th>Display</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($result)): $i=0; foreach ($result as $row): $i++; ?>
                                <tr id="statusRow_<?= $row['id'] ?>">
                                    <td><?= $i ?></td>
                                    <td><?= htmlspecialchars($row['clearing_status']) ?></td>
                                    <td><?= htmlspecialchars($row['display']) ?></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary editStatusBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger deleteStatusBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>
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

<!-- Edit Modal -->
<div class="modal fade" id="statusEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="statusUpdateForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Clearing Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Clearing Status</label>
                        <input type="text" name="clearing_status" id="edit_clearing_status" class="form-control" required>
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
    // ✅ DataTable init
    $('#status-datatable').DataTable({
        columnDefs: [{ orderable: false, targets: -1 }],
        language: { emptyTable: "No clearing statuses found" }
    });

    // ✅ Insert
    $('#statusInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>clearingstatus/crudData/insertion',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    // ✅ Load Edit Modal
    $(document).on('click', '.editStatusBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '<?php echo APP_URL; ?>clearingstatus/getStatusById',
            type: 'GET',
            data: { id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#edit_clearing_status').val(res.data.clearing_status);
                    $('#edit_display').val(res.data.display);
                    $('#statusEditModal').data('id', id).modal('show');
                } else {
                    alert(res.message);
                }
            }
        });
    });

    // ✅ Update
    $('#statusUpdateForm').submit(function (e) {
        e.preventDefault();
        const id = $('#statusEditModal').data('id');
        $.ajax({
            url: '<?php echo APP_URL; ?>clearingstatus/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) {
                    $('#statusEditModal').modal('hide');
                    location.reload();
                }
            }
        });
    });

    // ✅ Delete
    $(document).on('click', '.deleteStatusBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this status?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>clearingstatus/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });
});
</script>
