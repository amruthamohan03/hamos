<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <!-- Add Kind Form -->
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">Add New Kind</h4>
                    </div>
                    <div class="card-body">
                        <form id="kindInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="kind_name" class="form-label">Kind Name</label>
                                    <input type="text" class="form-control" id="kind_name" name="kind_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="kind_short_name" class="form-label">Short Name</label>
                                    <input type="text" class="form-control" id="kind_short_name" name="kind_short_name">
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
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kind List -->
                <div class="card mt-3">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">Kind List</h4>
                    </div>
                    <div class="card-body">
                        <table id="kind-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kind Name</th>
                                    <th>Short Name</th>
                                    <th>Display</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($result)): $i=0; foreach ($result as $row): $i++; ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= htmlspecialchars($row['kind_name']) ?></td>
                                    <td><?= htmlspecialchars($row['kind_short_name']) ?></td>
                                    <td><?= htmlspecialchars($row['display']) ?></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary editKindBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger deleteKindBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>
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
<div class="modal fade" id="kindEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="kindUpdateForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kind</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Kind Name</label>
                        <input type="text" name="kind_name" id="edit_kind_name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Short Name</label>
                        <input type="text" name="kind_short_name" id="edit_kind_short_name" class="form-control">
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
    $('#kind-datatable').DataTable({
        columnDefs: [{ orderable: false, targets: -1 }],
        language: { emptyTable: "No records found" }
    });

    // ✅ Insert Kind
    $('#kindInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>kind/crudData/insertion',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    // ✅ Edit Kind
    $(document).on('click', '.editKindBtn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '<?php echo APP_URL; ?>kind/getKindById',
            type: 'GET',
            data: { id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#edit_kind_name').val(res.data.kind_name);
                    $('#edit_kind_short_name').val(res.data.kind_short_name);
                    $('#edit_display').val(res.data.display);
                    $('#kindEditModal').data('id', id).modal('show');
                } else {
                    alert(res.message);
                }
            }
        });
    });

    // ✅ Update Kind
    $('#kindUpdateForm').submit(function (e) {
        e.preventDefault();
        const id = $('#kindEditModal').data('id');
        $.ajax({
            url: '<?php echo APP_URL; ?>kind/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) {
                    $('#kindEditModal').modal('hide');
                    location.reload();
                }
            }
        });
    });

    // ✅ Delete Kind
    $(document).on('click', '.deleteKindBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this kind?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>kind/crudData/deletion?id=' + id,
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
