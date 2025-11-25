<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- Add Phase Form -->
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">Add New Phase</h4>
                    </div>
                    <div class="card-body">
                        <form id="phaseInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phase_name" class="form-label">Phase Name</label>
                                    <input type="text" class="form-control" id="phase_name" name="phase_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phase_code" class="form-label">Phase Code</label>
                                    <input type="text" class="form-control" id="phase_code" name="phase_code" required>
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

                <!-- Phase List -->
                <div class="card mt-3">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">Phase List</h4>
                    </div>
                    <div class="card-body">
                        <table id="phase-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Phase Name</th>
                                    <th>Phase Code</th>
                                    <th>Display</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($result)):
                                    $i = 0;
                                    foreach ($result as $row):
                                        $i++; ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= htmlspecialchars($row['phase_name']) ?></td>
                                            <td><?= htmlspecialchars($row['phase_code']) ?></td>
                                            <td><?= htmlspecialchars($row['display']) ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editPhaseBtn"
                                                    data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></a>
                                                <a href="#" class="btn btn-sm btn-danger deletePhaseBtn"
                                                    data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>
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

<!-- Edit Phase Modal -->
<div class="modal fade" id="phaseEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="phaseUpdateForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Phase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Phase Name</label>
                        <input type="text" name="phase_name" id="edit_phase_name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Phase Code</label>
                        <input type="text" name="phase_code" id="edit_phase_code" class="form-control" required>
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

<!-- ✅ JS CRUD -->
<script>
    $(document).ready(function () {
        $('#phase-datatable').DataTable({
            columnDefs: [{ orderable: false, targets: -1 }],
            language: { emptyTable: "No records found" }
        });

        // ➕ Insert
        $('#phaseInsertForm').submit(function (e) {
            e.preventDefault();
            $.post('<?php echo APP_URL; ?>phase/crudData/insertion', $(this).serialize(), function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }, 'json');
        });

        // ✏️ Edit
        $(document).on('click', '.editPhaseBtn', function () {
            const id = $(this).data('id');
            $.get('<?php echo APP_URL; ?>phase/getPhaseById', { id: id }, function (res) {
                if (res.success) {
                    $('#edit_phase_name').val(res.data.phase_name);
                    $('#edit_phase_code').val(res.data.phase_code);
                    $('#edit_display').val(res.data.display);
                    $('#phaseEditModal').data('id', id).modal('show');
                } else alert(res.message);
            }, 'json');
        });

        // ✅ Update
        $('#phaseUpdateForm').submit(function (e) {
            e.preventDefault();
            const id = $('#phaseEditModal').data('id');
            $.post('<?php echo APP_URL; ?>phase/crudData/updation?id=' + id, $(this).serialize(), function (res) {
                alert(res.message);
                if (res.success) { $('#phaseEditModal').modal('hide'); location.reload(); }
            }, 'json');
        });

        // ❌ Delete
        $(document).on('click', '.deletePhaseBtn', function () {
            const id = $(this).data('id');
            if (!confirm('Are you sure you want to delete this phase?')) return;
            $.post('<?php echo APP_URL; ?>phase/crudData/deletion?id=' + id, function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }, 'json');
        });
    });
</script>