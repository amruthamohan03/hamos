<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Incoterm Master</h4>
                    </div>

                    <div class="card-body">
                        <form id="incotermInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="incoterm_short_name" class="form-label">Incoterm short name</label>
                                    <input type="text" class="form-control" id="incoterm_short_name" name="incoterm_short_name"
                                        placeholder="Enter incoterm short name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="incoterm_full_name" class="form-label">Incoterm full name</label>
                                    <input type="text" class="form-control" id="incoterm_full_name" name="incoterm_full_name"
                                        placeholder="Enter incoterm full name" required>
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
                                    <i class="mdi mdi-content-save"></i> Save Incoterm
                                </button>
                                <a href="" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <h4 class="header-title">Incoterm  List</h4>
                        <table id="incoterm-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Incoterm short name</th>
                                    <th>Incoterm full name</th>
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
                                            <td><?= htmlspecialchars($row['incoterm_short_name']); ?></td>
                                            <td><?= htmlspecialchars($row['incoterm_full_name']); ?></td>
                                            <td><?= htmlspecialchars($row['display']); ?></td>
                                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                                            <td><?= htmlspecialchars($row['updated_at']); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editIncotermBtn"
                                                    data-id="<?= $row['id']; ?>" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                    <a href="#" class="btn btn-sm btn-danger deleteincotermBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center text-muted">No Incoterm found</td></tr>
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
<div class="modal fade" id="incotermEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="incotermUpdateForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Incoterm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Incoterm short name</label>
                        <input type="text" name="incoterm_short_name" id="edit_incoterm_short_name" class="form-control" required>
                    </div>
                   <div class="mb-2">
                        <label>Incoterm full name</label>
                        <input type="text" name="incoterm_full_name" id="edit_incoterm_full_name" class="form-control" required>
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

    $('#incotermInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>incoterm/crudData/insertion',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    $(document).on('click', '.deleteincotermBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this incoterm?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>incoterm/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    // Edit modal load
    $(document).on('click', '.editIncotermBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>incoterm/getIncotermById',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#edit_incoterm_short_name').val(res.data.incoterm_short_name);
                    $('#edit_incoterm_full_name').val(res.data.incoterm_full_name);

                    $('#edit_display').val(res.data.display);
                    $('#incotermEditModal').data('id', id).modal('show');
                } else {
                    alert('Incoterm not found');
                }
            },
            error: function () {
                alert('Error fetching incoterm data.');
            }
        });
    });

    // AJAX update
    $('#incotermUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#incotermEditModal').data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>incoterm/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    $('#incotermEditModal').modal('hide');
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
