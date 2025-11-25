<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- Add New Category -->
                <div class="card">
                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Add Category</h4>
                    </div>
                    <div class="card-body">
                        <form id="categoryInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" name="category_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Display</label>
                                    <select name="display" class="form-select">
                                        <option value="Y" selected>Yes</option>
                                        <option value="N">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Save Category</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Category List -->
                <div class="card mt-3">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">Category List</h4>
                    </div>
                    <div class="card-body">
                        <table id="category-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
                                    <th>Display</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($result)): $i=0; foreach ($result as $row): $i++; ?>
                                <tr id="categoryRow_<?= $row['id'] ?>">
                                    <td><?= $i ?></td>
                                    <td><?= htmlspecialchars($row['category_name']) ?></td>
                                    <td><?= htmlspecialchars($row['display']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['updated_at'])) ?></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary editCategoryBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger deleteCategoryBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>
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
<div class="modal fade" id="categoryEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="categoryUpdateForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Category Name</label>
                        <input type="text" name="category_name" id="edit_category_name" class="form-control" required>
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
    // ✅ Initialize DataTable
    $('#category-datatable').DataTable({
        "columnDefs": [{ "orderable": false, "targets": -1 }],
        "language": { "emptyTable": "No categories found" }
    });

    // ✅ Insert
    $('#categoryInsertForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>category/crudData/insertion',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    location.reload();
                } else {
                    alert('❌ ' + res.message);
                }
            }
        });
    });

    // ✅ Load Edit Modal
    $(document).on('click', '.editCategoryBtn', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        $.ajax({
            url: '<?php echo APP_URL; ?>category/getCategoryById',
            type: 'GET',
            data: { id },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#edit_category_name').val(res.data.category_name);
                    $('#edit_display').val(res.data.display);
                    $('#categoryEditModal').data('id', id).modal('show');
                } else {
                    alert('❌ ' + res.message);
                }
            }
        });
    });

    // ✅ Update
    $('#categoryUpdateForm').submit(function (e) {
        e.preventDefault();
        const id = $('#categoryEditModal').data('id');
        $.ajax({
            url: '<?php echo APP_URL; ?>category/crudData/updation?id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    $('#categoryEditModal').modal('hide');
                    location.reload();
                } else {
                    alert('❌ ' + res.message);
                }
            }
        });
    });

    // ✅ Delete
    $(document).on('click', '.deleteCategoryBtn', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this category?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>category/crudData/deletion?id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('✅ ' + res.message);
                    location.reload();
                } else {
                    alert('❌ ' + res.message);
                }
            }
        });
    });
});
</script>
