<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Add New Menu</h4>
                    </div>

                    <div class="card-body">
                        <form id="menuInsertForm" method="post" >
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="menu_id" class="form-label">Menu ID (Parent)</label>
                                    <select class="form-select select2" id="menu_id" name="menu_id"
                                        data-placeholder="Select parent menu">
                                        <option value="">-- No Parent (Top Level) --</option>
                                        <?php if (!empty($menus)): ?>
                                            <?php foreach ($menus as $menu): ?>
                                                <option value="<?= htmlspecialchars($menu['id']) ?>">
                                                    <?= htmlspecialchars($menu['menu_name']) ?> (ID:
                                                    <?= htmlspecialchars($menu['id']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="menu_level" class="form-label">Menu Level</label>
                                    <select class="form-select" id="menu_level" name="menu_level" required>
                                        <option value="">-- Select Menu Level --</option>
                                        <option value="0">First Level</option>
                                        <option value="1">Second Level</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="menu_order" class="form-label">Menu Order</label>
                                    <input type="number" class="form-control" id="menu_order" name="menu_order"
                                        placeholder="Enter menu order (e.g., 1, 2, 3)" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="menu_name" class="form-label">Menu Name</label>
                                    <input type="text" class="form-control" id="menu_names" name="menu_name"
                                        placeholder="Enter menu name" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="url" class="form-label">URL</label>
                                    <input type="text" class="form-control" id="urls" name="url"
                                        placeholder="Enter menu URL (e.g., user/dashboard)">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="text" class="form-label">Text</label>
                                    <input type="text" class="form-control" id="texts" name="text"
                                        placeholder="Display text for the menu">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="icon" class="form-label">Icon Class</label>
                                    <input type="text" class="form-control" id="icons" name="icon"
                                        placeholder="Enter icon class (e.g., fa fa-home)">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="badge" class="form-label">Badge</label>
                                    <input type="text" class="form-control" id="badges" name="badge"
                                        placeholder="Enter badge text (optional)">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="display" class="form-label">Display</label>
                                    <select class="form-select" id="displays" name="display">
                                        <option value="Y" selected>Yes</option>
                                        <option value="N">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Save Menu
                                </button>
                                <a href="" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div> <!-- end card-body -->
                    <div class="card-body">
                        <h4 class="header-title">Menus List</h4>

                        <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Menu Name</th>
                                    <th>Text</th>
                                    <th>URL</th>
                                    <th>Icon</th>
                                    <th>Badge</th>
                                    <th>Menu Level</th>
                                    <th>Order</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if (!empty($result)): ?>
                                    <?php
                                    $menu_level = ['2' => 'Top Level', '0' => 'First Level', '1' => 'Second Level'];
                                    foreach ($result as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['menu_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['text']); ?></td>
                                            <td><?php echo htmlspecialchars($row['url']); ?></td>
                                            <td><?php echo htmlspecialchars($row['icon']); ?></td>
                                            <td><?php echo htmlspecialchars($row['badge']); ?></td>
                                            <td><?php echo htmlspecialchars($menu_level[$row['menu_level']]); ?></td>
                                            <td><?php echo htmlspecialchars($row['menu_order']); ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['updated_at'])); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editMenuBtn"
                                                    data-id="<?= $row['id']; ?>" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <a href="#" 
                                                    class="btn btn-sm btn-danger deleteMenuBtn" 
                                                    data-id="<?= $row['id']; ?>" 
                                                    title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No menu items found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>


                    </div> <!-- end card body-->


                </div> <!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div> <!-- end container -->

    <?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>
</div>
<!-- Edit Menu Modal -->
<div class="modal fade" id="menuEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="menuUpdateForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Parent Menu -->
                    <div class="mb-2">
                        <label class="form-label">Menu ID (Parent)</label>
                        <select class="form-select select2" id="menu_id_edit" name="menu_id">
                            <option value="">-- No Parent (Top Level) --</option>
                            <?php foreach ($menus as $menu): ?>
                                <option value="<?= $menu['id'] ?>">
                                    <?= htmlspecialchars($menu['menu_name']) ?> (ID: <?= $menu['id'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Menu Level -->
                    <div class="mb-2">
                        <label class="form-label">Menu Level</label>
                        <select class="form-select" id="menu_level_edit" name="menu_level" required>
                            <option value="">-- Select Menu Level --</option>
                            <option value="0">First Level</option>
                            <option value="1">Second Level</option>
                        </select>
                    </div>

                    <!-- Menu Order -->
                    <div class="mb-2">
                        <label class="form-label">Menu Order</label>
                        <input type="number" class="form-control" name="menu_order" id="menu_order_edit" required>
                    </div>

                    <!-- Menu Name -->
                    <div class="mb-2">
                        <label class="form-label">Menu Name</label>
                        <input type="text" class="form-control" name="menu_name" id="menu_name_edit" required>
                    </div>

                    <!-- URL -->
                    <div class="mb-2">
                        <label class="form-label">URL</label>
                        <input type="text" class="form-control" name="url" id="url_edit">
                    </div>

                    <!-- Text -->
                    <div class="mb-2">
                        <label class="form-label">Text</label>
                        <input type="text" class="form-control" name="text" id="text_edit">
                    </div>

                    <!-- Display -->
                    <div class="mb-2">
                        <label class="form-label">Display</label>
                        <select class="form-select" name="display" id="display_edit">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update Menu</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <script>
    $(document).ready(function () {
        $('#menuInsertForm').on('submit', function(e) {
            e.preventDefault(); // prevent default form submission

            $.ajax({
                url: '<?php echo APP_URL; ?>/menu/crudData/insertion', // your insertion URL
                type: 'POST',
                data: $(this).serialize(), // serialize form data
                dataType: 'json',
                success: function(res) {
                    if(res.success) {
                        alert('✅ Menu inserted successfully!');
                        $('#menuInsertForm')[0].reset(); // reset the form
                        // Optionally, reload table or append new row
                        location.reload(); 
                    } else {
                        alert('❌ ' + res.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('❌ Error: ' + xhr.responseText);
                }
            });
        });
        // Open Edit Modal and load data
        $(document).on('click', '.editMenuBtn', function (e) {
            e.preventDefault();
            let id = $(this).data('id');

            $.ajax({
                url: 'getMenuById',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(res) {
                    if(res.success) {
                        const data = res.data;

                        $('#menu_id_edit').val(data.menu_id).trigger('change');  // select parent
                        $('#menu_level_edit').val(data.menu_level);             // menu level
                        $('#menu_order_edit').val(data.menu_order);             // menu order
                        $('#menu_name_edit').val(data.menu_name);
                        $('#url_edit').val(data.url);
                        $('#text_edit').val(data.text);
                        $('#display_edit').val(data.display);

                        $('#menuEditModal').data('id', id).modal('show');
                    } else {
                        alert(res.message);
                    }
                },
                error: function() {
                    alert('Error fetching menu data.');
                }
            });
        });

        // Submit AJAX update
        $('#menuUpdateForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#menuEditModal').data('id');

            $.ajax({
                url: 'crudData/updation?id=' + id,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if(res.success) {
                        alert('✅ Menu updated successfully!');
                        $('#menuEditModal').modal('hide');
                        location.reload();
                    } else {
                        alert('❌ ' + res.message);
                    }
                },
                error: function() {
                    alert('Error updating menu.');
                }
            });
        });

        $(document).on('click', '.deleteMenuBtn', function(e) {
            e.preventDefault();

            // Get the menu id from data attribute
            let id = $(this).data('id');

            // Confirm deletion
            if (!confirm('Are you sure you want to delete this menu?')) {
                return;
            }

            $.ajax({
                url: 'crudData/deletion?id=' + id, // Your delete URL
                type: 'POST', // Can be GET if your endpoint supports
                dataType: 'json',
                success: function(res) {
                    if(res.success) {
                        alert('✅ Menu deleted successfully!');
                        // Remove the row from the table without reload
                        $('#menuRow_' + id).fadeOut(500, function() {
                            $(this).remove();
                        });
                        location.reload();
                    } else {
                        alert('❌ ' + res.message);
                    }
                },
                error: function() {
                    alert('Error deleting menu.');
                }
            });
        });

    });
</script>