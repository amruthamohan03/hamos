<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Suboffice Master</h4>
                    </div>

                    <div class="card-body">
                        <form id="subofficeInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sub_office_name" class="form-label">suboffice Name</label>
                                    <input type="text" class="form-control" id="sub_office_name" name="sub_office_name"
                                        placeholder="Enter suboffice name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                        <label class="form-label">Main Office</label>
                        <select name="main_office_id" id="main_office_id" class="form-select">
                          <option value="">-- Select Main Office --</option>
                          <?php foreach ($mainoffice as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['main_location_name']) ?></option>
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
                                    <i class="mdi mdi-content-save"></i> Save suboffice
                                </button>
                                <a href="" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <h4 class="header-title">suboffice List</h4>
                        <table id="suboffice-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>                                    
                                    <th>Suboffice Name</th>
                                    <th>Main Office </th>
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
                                            <td><?= htmlspecialchars($row['sub_office_name']); ?></td>
                                            <td><?= htmlspecialchars($row['main_location_name']); ?></td>
                                            <td><?= htmlspecialchars($row['display']); ?></td>
                                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                                            <td><?= htmlspecialchars($row['updated_at']); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editsubofficeBtn"
                                                    data-id="<?= $row['id']; ?>" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                
                                 <a href="#" class="btn btn-sm btn-danger deletesubofficeBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>

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
<div class="modal fade" id="subofficeEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="subofficeUpdateForm" type="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit suboffice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>suboffice Name</label>
                        <input type="text" name="sub_office_name" id="edit_sub_office_name" class="form-control" required>
                    </div>
                     <div class="col-md-6 mb-3">
                        <label class="form-label">Main office</label>
                        <select name="main_office_id" id="edit_main_office_id" class="form-select">
                          <option value="">-- Select main office --</option>
                          <?php foreach ($mainoffice as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['main_location_name']) ?></option>
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
         $('#suboffice-datatable').DataTable();

    $('#subofficeInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>suboffice/crudData/insertion',
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
    $(document).on('click', '.editsubofficeBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>suboffice/getsubofficeById',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#edit_sub_office_name').val(res.data.sub_office_name);
                    $('#edit_main_office_id').val(res.data.main_office_id);

                    $('#edit_display').val(res.data.display);
                    $('#subofficeEditModal').data('id', id).modal('show');
                } else {
                    alert('suboffice not found');
                }
            },
            error: function () {
                alert('Error fetching suboffice data.');
            }
        });
    });
    
    $(document).on('click', '.deletesubofficeBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this suboffice?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>suboffice/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    // AJAX update
    $('#subofficeUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#subofficeEditModal').data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>suboffice/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    $('#subofficeEditModal').modal('hide');
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
