<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Transport Master</h4>
                    </div>

                    <div class="card-body">
                        <form id="transportInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="transport_mode_name" class="form-label">Transport mode name</label>
                                    <input type="text" class="form-control" id="transport_mode_name" name="transport_mode_name"
                                        placeholder="Enter transport mode name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="transport_letter" class="form-label">Transport letter</label>
                                    <input type="text" class="form-control" id="transport_letter" name="transport_letter"
                                        placeholder="Enter transport letter" required>
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
                                    <i class="mdi mdi-content-save"></i> Save transport Mode
                                </button>
                                <a href="" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <h4 class="header-title">Transport  List</h4>
                        <table id="transport-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Transport mode name</th>
                                    <th>Transport letter</th>
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
                                            <td><?= htmlspecialchars($row['transport_mode_name']); ?></td>
                                            <td><?= htmlspecialchars($row['transport_letter']); ?></td>
                                            <td><?= htmlspecialchars($row['display']); ?></td>
                                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                                            <td><?= htmlspecialchars($row['updated_at']); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary edittransportBtn"
                                                    data-id="<?= $row['id']; ?>" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                    <a href="#" class="btn btn-sm btn-danger deletetransportBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>

                                            </td>
                                        </tr>
                                   <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>

                                <?php if (empty($result)): ?>
                                <tfoot>
                                    <tr><td colspan="7" class="text-center text-muted"></td></tr>
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
<div class="modal fade" id="transportEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="transportUpdateForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit transport</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Transport mode name</label>
                        <input type="text" name="transport_mode_name" id="edit_transport_mode_name" class="form-control" required>
                    </div>
                   <div class="mb-2">
                        <label>Transport letter</label>
                        <input type="text" name="transport_letter" id="edit_transport_letter" class="form-control" required>
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
    $('#transport-datatable').DataTable();

    $('#transportInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>transport/crudData/insertion',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    $(document).on('click', '.deletetransportBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this transport?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>transport/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    // Edit modal load
    $(document).on('click', '.edittransportBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>transport/gettransportById',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#edit_transport_mode_name').val(res.data.transport_mode_name);
                    $('#edit_transport_letter').val(res.data.transport_letter);

                    $('#edit_display').val(res.data.display);
                    $('#transportEditModal').data('id', id).modal('show');
                } else {
                    alert('transport not found');
                }
            },
            error: function () {
                alert('Error fetching transport data.');
            }
        });
    });

    // AJAX update
    $('#transportUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#transportEditModal').data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>transport/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    $('#transportEditModal').modal('hide');
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
