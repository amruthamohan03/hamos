<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Model Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Model Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <!-- Add New Item -->
                <div class="col-12">
                    <div class="callout callout-info">Add New Model</div>
                </div>

                <div class="col-md-12">
                    <div class="card card-info card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Add Model Entry</div>
                        </div>

                        <form class="needs-validation" novalidate method="POST" action="index.php?url=model/crudData/insertion">
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

                                    <!-- Model Name -->
                                    <div class="col-md-6">
                                        <label for="model_name" class="form-label">Model Name</label>
                                        <input type="text" class="form-control" id="model_name" name="model_name" required>
                                        <div class="invalid-feedback">Please enter model name.</div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button class="btn btn-info" type="submit"><i class="mdi mdi-content-save"></i> Save Item</button>
                                <a href="#" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>

                        <script>
                            (() => {
                                'use strict';
                                const forms = document.querySelectorAll('.needs-validation');
                                Array.from(forms).forEach((form) => {
                                    form.addEventListener('submit', (event) => {
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
                            <div class="card-title">Model List</div>
                        </div>
                        <div class="card-body">
                            <table id="items-datatable" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Make</th>
                                        <th>Model Name</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($models)): ?>
                                        <?php foreach ($models as $row): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['id']); ?></td>
                                                <td><?= htmlspecialchars($row['make_name']); ?></td>
                                                <td><?= htmlspecialchars($row['model_name']); ?></td>
                                                <td><?= htmlspecialchars($row['description']); ?></td>
                                                <td>
                                                    <a href="model/crudData/updation?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                    <a href="model/crudData/deletion?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?');">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center text-muted">No items found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end::Col-->

            </div>
        </div>
    </div>
    <!--end::App Content-->
</main>

<script>
$(document).ready(function() {
    $('#items-datatable').DataTable({
        responsive: true,
        autoWidth: false
    });
});
</script>
