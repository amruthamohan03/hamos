<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- Add New User -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Add New User</h4>
                    </div>
                    <div class="card-body">
                        <form id="userInsertForm">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label>Full Name</label>
                                    <input type="text" name="full_name" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label>Role</label>
                                    <select name="role_id" class="form-select">
                                        <option value="">-- Select Role --</option>
                                        <?php foreach ($roles as $r): ?>
                                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['role_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Department</label>
                                    <select name="dept_id" class="form-select">
                                        <option value="">-- Select Department --</option>
                                        <?php foreach ($departments as $d): ?>
                                            <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['department_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- ✅ Location Dropdown -->
                                <div class="col-md-3">
                                    <label>Location</label>
                                    <select name="location_id" class="form-select">
                                        <option value="">-- Select Location --</option>
                                        <?php foreach ($locations as $loc): ?>
                                            <option value="<?= $loc['id'] ?>">
                                                <?= htmlspecialchars($loc['main_location_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>Display</label>
                                    <select name="display" class="form-select">
                                        <option value="Y">Yes</option>
                                        <option value="N">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- User List -->
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>User List</h4>
                    </div>
                    <div class="card-body">
                        <table id="user-datatable" class="table table-striped nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th>Location</th>
                                    <th>Display</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($result as $row): ?>
                                    <tr id="row_<?= $row['id'] ?>">
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= $row['role_name'] ?></td>
                                        <td><?= $row['dept_name'] ?></td>
                                        <td><?= ($row['main_location_name'])?htmlspecialchars($row['main_location_name']):'' ?></td>
                                        <td><?= $row['display'] ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary editUserBtn"
                                                data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></button>
                                            <button class="btn btn-sm btn-danger deleteUserBtn"
                                                data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></button>
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

<!-- ✅ Edit Modal -->
<div class="modal fade" id="userEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="userUpdateForm" class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-semibold"><i class="ti ti-pencil me-1 text-primary"></i>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Username</label>
                        <input type="text" id="edit_username" name="username" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label>Email</label>
                        <input type="email" id="edit_email" name="email" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label>Full Name</label>
                        <input type="text" id="edit_full_name" name="full_name" class="form-control form-control-sm"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label>Role</label>
                        <select id="edit_role_id" name="role_id" class="form-select form-select-sm">
                            <option value="">-- Select Role --</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['role_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Department</label>
                        <select id="edit_dept_id" name="dept_id" class="form-select form-select-sm">
                            <option value="">-- Select Department --</option>
                            <?php foreach ($departments as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['department_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Location</label>
                        <select id="edit_location_id" name="location_id" class="form-select form-select-sm">
                            <option value="">-- Select Location --</option>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['main_location_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>New Password</label>
                        <input type="password" id="edit_password" name="password" class="form-control form-control-sm"
                            placeholder="Leave blank to keep">
                    </div>
                    <div class="col-md-2">
                        <label>Display</label>
                        <select id="edit_display" name="display" class="form-select form-select-sm">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="ti ti-x"></i>
                    Close</button>
                <button type="submit" class="btn btn-success btn-sm"><i class="ti ti-device-floppy"></i> Update</button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ AJAX CRUD JS -->
<script>
    $(function () {
        const table = $('#user-datatable').DataTable();

        // ➕ Insert User
        $('#userInsertForm').on('submit', function (e) {
            e.preventDefault();
            $.post('<?php echo APP_URL; ?>user/crudData/insertion', $(this).serialize(), function (res) {
                if (res.success) {
                    Swal.fire('✅ Success', res.message, 'success');
                    reloadTable();
                    $('#userInsertForm')[0].reset();
                } else Swal.fire('❌ Error', res.message, 'error');
            }, 'json');
        });

        // ✏️ Open Edit Modal
        $(document).on('click', '.editUserBtn', function () {
            let id = $(this).data('id');
            $.get('<?php echo APP_URL; ?>user/getUserById', { id: id }, function (res) {
                if (res.success) {
                    let u = res.data;
                    $('#edit_username').val(u.username);
                    $('#edit_email').val(u.email);
                    $('#edit_full_name').val(u.full_name);
                    $('#edit_role_id').val(u.role_id);
                    $('#edit_dept_id').val(u.dept_id);
                    $('#edit_location_id').val(u.location_id);
                    $('#edit_display').val(u.display);
                    $('#userEditModal').data('id', id).modal('show');
                } else Swal.fire('Error', res.message, 'error');
            }, 'json');
        });

        // ✅ Update User
        $('#userUpdateForm').submit(function (e) {
            e.preventDefault();
            let id = $('#userEditModal').data('id');
            $.post('<?php echo APP_URL; ?>user/crudData/updation&id=' + id, $(this).serialize(), function (res) {
                if (res.success) {
                    Swal.fire('✅ Updated', res.message, 'success');
                    $('#userEditModal').modal('hide');
                    reloadTable();
                    location.reload();
                } else Swal.fire('❌ Failed', res.message, 'error');
            }, 'json');
        });

        // 🗑️ Delete User
        $(document).on('click', '.deleteUserBtn', function () {
            let id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "This will permanently delete the user!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('<?php echo APP_URL; ?>user/crudData/deletion&id=' + id, function (res) {
                        if (res.success) {
                            Swal.fire('Deleted!', res.message, 'success');
                            $('#row_' + id).fadeOut();
                        } else Swal.fire('Error', res.message, 'error');
                    }, 'json');
                }
            });
        });

        // 🔄 Reload Table via AJAX (no full page refresh)
        function reloadTable() {
            $.get('<?php echo APP_URL; ?>user', function (html) {
                const rows = $(html).find('#user-datatable tbody').html();
                $('#user-datatable tbody').html(rows);
                table.draw();
            });
        }
    });
</script>