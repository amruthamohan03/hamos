<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Main office Master</h4>
                    </div>

                    <div class="card-body">
                        <form id="mainofficeInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="main_location_name" class="form-label">Mainoffice Name</label>
                                    <input type="text" class="form-control" id="main_location_name" name="main_location_name"
                                        placeholder="Enter main office name" required>
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
                                    <i class="mdi mdi-content-save"></i> Save main office
                                </button>
                                <a href="" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form> 
                    </div>

                    <div class="card-body">
                        <h4 class="header-title">Main office List</h4>
                        <table id="mainoffice-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Main office Name</th>
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
                                            <td><?= htmlspecialchars($row['main_location_name']); ?></td>
                                            <td><?= htmlspecialchars($row['display']); ?></td>
                                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                                            <td><?= htmlspecialchars($row['updated_at']); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editmainofficeBtn"
                                                    data-id="<?= $row['id']; ?>" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                
                                 <a href="#" class="btn btn-sm btn-danger deletemainofficeBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>

                                    <?php if (empty($result)): ?>
                                    <tfoot>
                                        <tr><td colspan="6" class="text-center text-muted">No Feet Container found</td></tr>
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
<div class="modal fade" id="mainofficeEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="mainofficeUpdateForm" type="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit mainoffice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>mainoffice Name</label>
                        <input type="text" name="main_location_name" id="edit_main_location_name" class="form-control" required>
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
         $('#mainoffice-datatable').DataTable();

    $('#mainofficeInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>mainoffice/crudData/insertion',
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
    $(document).on('click', '.editmainofficeBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>mainoffice/getmainofficeById',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#edit_main_location_name').val(res.data.main_location_name);
                    $('#edit_display').val(res.data.display);
                    $('#mainofficeEditModal').data('id', id).modal('show');
                } else {
                    alert('mainoffice not found');
                }
            },
            error: function () {
                alert('Error fetching mainoffice data.');
            }
        });
    });
    
    $(document).on('click', '.deletemainofficeBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this mainoffice?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>mainoffice/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    // AJAX update
    $('#mainofficeUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#mainofficeEditModal').data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>mainoffice/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    $('#mainofficeEditModal').modal('hide');
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
