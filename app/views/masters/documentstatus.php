<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Document status Master</h4>
                    </div>

                    <div class="card-body">
                        <form id="documentstatusInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="document_status" class="form-label">Document status</label>
                                    <input type="text" class="form-control" id="document_status" name="document_status"
                                        placeholder="Enter documentstatus" required>
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
                                    <i class="mdi mdi-content-save"></i> Save document status 
                                </button>
                                <a href="" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <h4 class="header-title">Documentstatus  List</h4>
                        <table id="documentstatus-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Documentstatus</th>                                    
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
                                            <td><?= htmlspecialchars($row['document_status']); ?></td>
                                            <td><?= htmlspecialchars($row['display']); ?></td>
                                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                                            <td><?= htmlspecialchars($row['updated_at']); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editdocumentstatusBtn"
                                                    data-id="<?= $row['id']; ?>" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                    <a href="#" class="btn btn-sm btn-danger deletedocumentstatusBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>

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
<div class="modal fade" id="documentstatusEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="documentstatusUpdateForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit documentstatus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>documentstatus Name</label>
                        <input type="text" name="document_status" id="edit_document_status" class="form-control" required>
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
    $('#documentstatus-datatable').DataTable();

    $('#documentstatusInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>documentstatus/crudData/insertion',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    $(document).on('click', '.deletedocumentstatusBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this documentstatus?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>documentstatus/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    // Edit modal load
    $(document).on('click', '.editdocumentstatusBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>documentstatus/getdocumentstatusById',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#edit_document_status').val(res.data.document_status);

                    $('#edit_display').val(res.data.display);
                    $('#documentstatusEditModal').data('id', id).modal('show');
                } else {
                    alert('documentstatus details not found');
                }
            },
            error: function () {
                alert('Error fetching documentstatus data.');
            }
        });
    });

    // AJAX update
    $('#documentstatusUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#documentstatusEditModal').data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>documentstatus/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    $('#documentstatusEditModal').modal('hide');
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
