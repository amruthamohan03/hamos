<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Refferer Master</h4>
                    </div>

                    <div class="card-body">
                        <form id="reffererInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="refferer_name" class="form-label">Refferer Name</label>
                                    <input type="text" class="form-control" id="refferer_name" name="refferer_name"
                                        placeholder="Enter refferer name" required>
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
                                    <i class="mdi mdi-content-save"></i> Save refferer
                                </button>
                                <a href="" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <h4 class="header-title">refferer List</h4>
                        <table id="refferer-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Refferer Name</th>
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
                                            <td><?= htmlspecialchars($row['refferer_name']); ?></td>
                                            <td><?= htmlspecialchars($row['display']); ?></td>
                                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                                            <td><?= htmlspecialchars($row['updated_at']); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editreffererBtn"
                                                    data-id="<?= $row['id']; ?>" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                    <a href="#" class="btn btn-sm btn-danger deletereffererBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center text-muted">No refferer types found</td></tr>
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
<div class="modal fade" id="reffererEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="reffererUpdateForm" type="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit refferer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>refferer Name</label>
                        <input type="text" name="refferer_name" id="edit_refferer_name" class="form-control" required>
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
    $('#refferer-datatable').DataTable();

    $('#reffererInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>refferer/crudData/insertion',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });


    $(document).on('click', '.deletereffererBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this refferer?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>refferer/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });


    // Edit modal load
    $(document).on('click', '.editreffererBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>refferer/getreffererById',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#edit_refferer_name').val(res.data.refferer_name);
                    $('#edit_display').val(res.data.display);
                    $('#reffererEditModal').data('id', id).modal('show');
                } else {
                    alert('refferer not found');
                }
            },
            error: function () {
                alert('Error fetching refferer data.');
            }
        });
    });

    // AJAX update
    $('#reffererUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#reffererEditModal').data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>refferer/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    $('#reffererEditModal').modal('hide');
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
