<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- Add New Role -->
                <div class="card">
                    <div class="card-header">
                        <h4>Add New Role</h4>
                    </div>
                    <div class="card-body">
                        <form id="roleInsertForm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Role Name</label>
                                    <input type="text" name="role_name" class="form-control" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>Department</label>
                                    <select name="department_id" class="form-select">
                                        <option value="">-- Select Department --</option>
                                        <?php foreach ($departments as $d): ?>
                                            <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['department_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>Office Location</label>
                                    <select name="office_location_id" class="form-select">
                                        <option value="">-- Select Office --</option>
                                        <?php foreach ($offices as $o): ?>
                                            <option value="<?= $o['id'] ?>">
                                                <?= htmlspecialchars($o['main_location_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Parent Role</label>
                                    <select name="parent_role_id" class="form-select">
                                        <option value="0">-- None --</option>
                                        <?php foreach ($result as $r): ?>
                                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['role_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Approval Level</label>
                                    <input type="number" name="approval_level" class="form-control" value="0" min="0">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>Access Permissions</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="department" id="department" value="1">
                                        <label class="form-check-label" for="department">Department</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="management" id="management" value="1">
                                        <label class="form-check-label" for="management">Management</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="finance" id="finance" value="1">
                                        <label class="form-check-label" for="finance">Finance</label>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label>Display</label>
                                    <select name="display" class="form-select">
                                        <option value="Y">Yes</option>
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

                <!-- Role List -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Role List</h4>
                    </div>
                    <div class="card-body">
                        <table id="role-datatable" class="table table-striped nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Role Name</th>
                                    <th>Department</th>
                                    <th>Office</th>
                                    <th>Parent Role</th>
                                    <th>Approval Level</th>
                                    <th>Permissions</th>
                                    <th>Display</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($result as $row): ?>
                                    <tr id="row_<?= $row['id'] ?>">
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($row['role_name']) ?></td>
                                        <td><?= htmlspecialchars($row['department_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['office_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['parent_role_name'] ?? '-') ?></td>
                                        <td><?= $row['approval_level'] ?></td>
                                        <td>
                                            <?php
                                            $permissions = [];
                                            if ($row['department'] == 1) $permissions[] = "<span class='badge bg-primary me-1'>Department</span>";
                                            if ($row['management'] == 1) $permissions[] = "<span class='badge bg-success me-1'>Management</span>";
                                            if ($row['finance'] == 1) $permissions[] = "<span class='badge bg-info me-1'>Finance</span>";
                                            echo !empty($permissions) ? implode('', $permissions) : '-';
                                            ?>
                                        </td>
                                        <td><span class="badge bg-<?= $row['display'] == 'Y' ? 'success' : 'secondary' ?>"><?= $row['display'] ?></span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary editRoleBtn"
                                                data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger deleteRoleBtn"
                                                data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="roleEditModal" tabindex="-1" aria-labelledby="roleEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="roleUpdateForm" class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-semibold" id="roleEditModalLabel">
                    <i class="ti ti-pencil me-1 text-primary"></i> Edit Role
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <!-- Role Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-muted">Role Name</label>
                        <input type="text" name="role_name" id="edit_role_name"
                            class="form-control form-control-sm shadow-sm" required>
                    </div>

                    <!-- Department -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-muted">Department</label>
                        <select name="department_id" id="edit_department_id"
                            class="form-select form-select-sm shadow-sm">
                            <option value="">-- Select Department --</option>
                            <?php foreach ($departments as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['department_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Office -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-muted">Office Location</label>
                        <select name="office_location_id" id="edit_office_location_id"
                            class="form-select form-select-sm shadow-sm">
                            <option value="">-- Select Office --</option>
                            <?php foreach ($offices as $o): ?>
                                <option value="<?= $o['id'] ?>"><?= htmlspecialchars($o['main_location_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Parent Role -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-muted">Parent Role</label>
                        <select name="parent_role_id" id="edit_parent_role_id"
                            class="form-select form-select-sm shadow-sm">
                            <option value="0">-- None --</option>
                            <?php foreach ($result as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['role_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Approval Level -->
                    <div class="col-md-4">
                        <label class="form-label fw-medium text-muted">Approval Level</label>
                        <input type="number" name="approval_level" id="edit_approval_level" 
                            class="form-control form-control-sm shadow-sm" value="0" min="0">
                    </div>

                    <!-- Permissions -->
                    <div class="col-md-8">
                        <label class="form-label fw-medium text-muted">Access Permissions</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="department" id="edit_department" value="1">
                            <label class="form-check-label" for="edit_department">Department</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="management" id="edit_management" value="1">
                            <label class="form-check-label" for="edit_management">Management</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="finance" id="edit_finance" value="1">
                            <label class="form-check-label" for="edit_finance">Finance</label>
                        </div>
                    </div>

                    <!-- Display -->
                    <div class="col-md-3">
                        <label class="form-label fw-medium text-muted">Display</label>
                        <select name="display" id="edit_display" class="form-select form-select-sm shadow-sm">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 bg-light py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i> Close
                </button>
                <button type="submit" class="btn btn-sm btn-success">
                    <i class="ti ti-device-floppy me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JS CRUD Logic -->
<script>
    $(function () {
        $('#role-datatable').DataTable();

        // ➕ Insert
        $('#roleInsertForm').submit(function (e) {
            e.preventDefault();
            $.post('<?php echo APP_URL; ?>role/crudData/insertion', $(this).serialize(), function (res) {
                if (res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert(res.message);
                }
            }, 'json');
        });

        // ✏️ Edit - Open Modal
        $(document).on('click', '.editRoleBtn', function () {
            let id = $(this).data('id');
            $.get('<?php echo APP_URL; ?>role/getRoleById', { id: id }, function (res) {
                if (res.success) {
                    let r = res.data;
                    $('#edit_role_name').val(r.role_name);
                    $('#edit_department_id').val(r.department_id || '');
                    $('#edit_office_location_id').val(r.office_location_id || '');
                    $('#edit_parent_role_id').val(r.parent_role_id || '0');
                    $('#edit_approval_level').val(r.approval_level || 0);
                    $('#edit_display').val(r.display);

                    // Reset and set checkboxes
                    $('#edit_department').prop('checked', r.department == 1);
                    $('#edit_management').prop('checked', r.management == 1);
                    $('#edit_finance').prop('checked', r.finance == 1);

                    $('#roleEditModal').data('id', id).modal('show');
                } else {
                    alert(res.message);
                }
            }, 'json');
        });

        // ✅ Update (from Modal)
        $('#roleUpdateForm').submit(function (e) {
            e.preventDefault();
            let id = $('#roleEditModal').data('id');
            $.post('<?php echo APP_URL; ?>role/crudData/updation&id=' + id, $(this).serialize(), function (res) {
                if (res.success) {
                    alert(res.message);
                    $('#roleEditModal').modal('hide');
                    location.reload();
                } else {
                    alert(res.message);
                }
            }, 'json');
        });

        // 🗑️ Delete
        $(document).on('click', '.deleteRoleBtn', function () {
            if (!confirm('Are you sure to delete this role?')) return;
            let id = $(this).data('id');
            $.post('<?php echo APP_URL; ?>role/crudData/deletion&id=' + id, function (res) {
                if (res.success) {
                    alert(res.message);
                    $('#row_' + id).fadeOut();
                } else {
                    alert(res.message);
                }
            }, 'json');
        });
    });
</script>