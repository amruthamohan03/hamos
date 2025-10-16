<main class="app-main">
    <!-- App Content Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Item Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Items</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- App Content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">

                <!-- Form Column -->
                <div class="col-12">
                    <div class="callout callout-info">
                        Add New Item
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card card-info card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Add New Entry</div>
                        </div>

                        <form class="needs-validation" novalidate method="POST" action="index.php?url=item/crudData/insertion">
                            <div class="card-body">
                                <div class="row g-3">

                                    <!-- Make Dropdown -->
                                    <div class="col-md-6">
                                        <label for="make_id" class="form-label">Make</label>
                                        <select class="form-select" id="make_id" name="make_id" required>
                                            <option selected disabled value="">Select Make</option>
                                            <?php if (!empty($makes)): ?>
                                                <?php foreach ($makes as $make): ?>
                                                    <option value="<?= htmlspecialchars($make['id']) ?>">
                                                        <?= htmlspecialchars($make['make_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="invalid-feedback">Please select a make.</div>
                                    </div>

                                    <!-- Model Dropdown -->
                                    <div class="col-md-6">
                                        <label for="model_id" class="form-label">Model</label>
                                        <select class="form-select" id="model_id" name="model_id" required>
                                            <option selected disabled value="">Select Model</option>
                                            <?php if (!empty($models)): ?>
                                                <?php foreach ($models as $model ): ?>
                                                    <option value="<?= htmlspecialchars($model['id']) ?>">
                                                        <?= htmlspecialchars($model['model_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="invalid-feedback">Please select a model.</div>
                                    </div>

                                    <!-- Item Name -->
                                    <div class="col-md-6">
                                        <label for="item_name" class="form-label">Item Name</label>
                                        <input type="text" class="form-control" id="item_name" name="item_name" required>
                                        <div class="invalid-feedback">Please enter item name.</div>
                                    </div>

                                    <!-- Item Code -->
                                    <div class="col-md-6">
                                        <label for="item_code" class="form-label">Item Code</label>
                                        <input type="text" class="form-control" id="item_code" name="item_code" required>
                                        <div class="invalid-feedback">Please enter item code.</div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>

                                    <!-- UOM -->
                                    <div class="col-md-6">
                                        <label for="uom" class="form-label">Unit of Measurement (UOM)</label>
                                        <input type="text" class="form-control" id="uom" name="uom" required>
                                        <div class="invalid-feedback">Please enter UOM.</div>
                                    </div>

                                    <!-- Display -->
                                    <div class="col-md-6">
                                        <label for="display" class="form-label">Display</label>
                                        <select class="form-select" id="display" name="display" required>
                                            <option value="Y" selected>Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer mt-3 text-end">
                                <button class="btn btn-info" type="submit">Save Item</button>
                            </div>
                        </form>

                        <script>
                            (() => {
                                'use strict';
                                const forms = document.querySelectorAll('.needs-validation');
                                Array.from(forms).forEach(form => {
                                    form.addEventListener('submit', event => {
                                        if (!form.checkValidity()) {
                                            event.preventDefault();
                                            event.stopPropagation();
                                        }
                                        form.classList.add('was-validated');
                                    }, false);
                                });
                            })();
                        </script>
                    </div>
                </div>

                <!-- Items List Table -->
                <div class="col-md-12">
                    <div class="card card-info card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Items List</div>
                        </div>
                        <div class="card-body">
                            <table id="items-datatable" class="table table-striped table-bordered dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Item Name</th>
                                        <th>Item Code</th>
                                        <th>Description</th>
                                        <th>UOM</th>
                                        <th>Display</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($items)): ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($item['id']); ?></td>
                                                <td><?= htmlspecialchars($item['make_name']); ?></td>
                                                <td><?= htmlspecialchars($item['model_name']); ?></td>
                                                <td><?= htmlspecialchars($item['item_name']); ?></td>
                                                <td><?= htmlspecialchars($item['item_code']); ?></td>
                                                <td><?= htmlspecialchars($item['description']); ?></td>
                                                <td><?= htmlspecialchars($item['uom']); ?></td>
                                                <td><?= htmlspecialchars($item['display']); ?></td>
                                                <td><?= htmlspecialchars($item['created_at']); ?></td>
                                                <td><?= htmlspecialchars($item['updated_at']); ?></td>
                                                <td>
                                                    <a href="edit_item.php?id=<?= $item['id']; ?>" class="btn btn-sm btn-primary">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                    <a href="delete_item.php?id=<?= $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="11" class="text-center text-muted">No items found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $('#items-datatable').DataTable({
            responsive: true,
            autoWidth: false
        });
    });
</script>
