<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- Add New language -->
                <div class="card">
                    <div class="card-header">
                        <h4>Add New language</h4>
                    </div>
                    <div class="card-body">
                        <form id="languageInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>label Name</label>
                                    <input type="text" name="label" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>English Name</label>
                                    <input type="text" name="english" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>French Name</label>
                                    <input type="text" name="french" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Module Name</label><br>
                                    <select name="menu_name" class="form-select">
                                        <option value="">-- Select Module --</option>
                                        <?php foreach ($modules as $o): ?>
                                            <option value="<?= $o['id'] ?>">
                                                <?= htmlspecialchars($o['menu_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Display</label>
                                    <select name="display" class="form-select">
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

                <!-- language List -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>language List</h4>
                    </div>
                    <div class="card-body">
                        <table id="language-datatable" class="table table-striped nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Label Name</th>
                                    <th>English Name</th>
                                    <th>French Name</th>
                                    <th>Module Name</th>                                   
                                    <th>Display</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($result)): $i=1; ?>
                                    <?php foreach($result as $row): ?>
                                        <tr id="row_<?= $row['id'] ?>">
                                            <td><?= $i++ ?></td>
                                            <td><?= htmlspecialchars($row['label']) ?></td>
                                            <td><?= htmlspecialchars($row['english']) ?></td>
                                            <td><?= htmlspecialchars($row['french']) ?></td>
                                            <td><?= htmlspecialchars($row['menu_name']) ?></td>
                                            <td><?= $row['display'] ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['updated_at'])) ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editlanguageBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></a>
                                                <a href="#" class="btn btn-sm btn-danger deletelanguageBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
<div class="modal fade" id="languageEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="languageUpdateForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit language</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>label Name</label>
                        <input type="text" name="label" id="edit_label" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>English Name</label>
                        <input type="text" name="english" id="edit_english" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>French Name</label>
                        <input type="text" name="french" id="edit_french" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Type</label><br>
                        <select name="module_id" id="edit_module_id" class="form-select">
                          <option value="">-- Select Module --</option>
                          <?php foreach ($modules as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['menu_name']) ?></option>
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
         $('#language-datatable').DataTable();

    $('#languageInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>language/crudData/insertion',
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
    $(document).on('click', '.editlanguageBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>language/getlanguageById',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (res) { console.log(res);
                if (res.success) {
                    $('#edit_label').val(res.data.label);
                    $('#edit_english').val(res.data.english);
                    $('#edit_french').val(res.data.french);
$('#edit_module_id').val(res.data.module_id ? res.data.module_id.toString() : '').trigger('change');                    $('#edit_display').val(res.data.display);
                    $('#languageEditModal').data('id', id).modal('show');
                } else {
                    alert('language not found');
                }
            },
            error: function () {
                alert('Error fetching language data.');
            }
        });
    });
    
    $(document).on('click', '.deletelanguageBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this language?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>language/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if (res.success) location.reload();
            }
        });
    });

    // AJAX update
    $('#languageUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#languageEditModal').data('id');

        $.ajax({
            url: '<?php echo APP_URL; ?>language/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    $('#languageEditModal').modal('hide');
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
