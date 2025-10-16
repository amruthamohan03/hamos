<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Menu Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Menu Management</li>
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
                <!--begin::Col-->
                <div class="col-12">
                    <div class="callout callout-info">Add New Menu</div>
                </div>

                <div class="col-md-12">
                    <div class="card card-info card-outline mb-4">
                        <!--begin::Header-->
                        <div class="card-header">
                            <div class="card-title">Add Menu Entry</div>
                        </div>
                        <!--end::Header-->

                        <!--begin::Form-->
                        <form class="needs-validation" novalidate method="POST" action="index.php?url=menu/crudData/insertion">
                            <div class="card-body">
                                <div class="row g-3">

                                    <!-- Parent Menu -->
                                    <div class="col-md-6">
                                        <label for="menu_id" class="form-label">Parent Menu</label>
                                        <select class="form-select select2" id="menu_id" name="menu_id" data-placeholder="Select parent menu">
                                            <option value="">-- No Parent (Top Level) --</option>
                                            <?php if (!empty($menus)): ?>
                                                <?php foreach ($menus as $menu): ?>
                                                    <option value="<?= htmlspecialchars($menu['id']) ?>">
                                                        <?= htmlspecialchars($menu['menu_name']) ?> (ID: <?= htmlspecialchars($menu['id']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="invalid-feedback">Please select parent menu (optional).</div>
                                    </div>

                                    <!-- Menu Level -->
                                    <div class="col-md-6">
                                        <label for="menu_level" class="form-label">Menu Level</label>
                                        <select class="form-select" id="menu_level" name="menu_level" required>
                                            <option value="">-- Select Menu Level --</option>
                                            <option value="0">Top Level</option>
                                            <option value="1">First Level</option>
                                            <option value="2">Second Level</option>
                                        </select>
                                        <div class="invalid-feedback">Please select menu level.</div>
                                    </div>

                                    <!-- Menu Name -->
                                    <div class="col-md-6">
                                        <label for="menu_name" class="form-label">Menu Name</label>
                                        <input type="text" class="form-control" id="menu_name" name="menu_name" placeholder="Enter menu name" required>
                                        <div class="invalid-feedback">Please enter menu name.</div>
                                    </div>

                                    <!-- URL -->
                                    <div class="col-md-6">
                                        <label for="url" class="form-label">URL</label>
                                        <input type="text" class="form-control" id="url" name="url" placeholder="Enter menu URL (e.g., user/dashboard)">
                                    </div>

                                    <!-- Text -->
                                    <div class="col-md-6">
                                        <label for="text" class="form-label">Text</label>
                                        <input type="text" class="form-control" id="text" name="text" placeholder="Display text for the menu">
                                    </div>

                                    <!-- Icon -->
                                    <div class="col-md-6">
                                        <label for="icon" class="form-label">Icon Class</label>
                                        <input type="text" class="form-control" id="icon" name="icon" placeholder="Enter icon class (e.g., bi bi-house)">
                                    </div>

                                    <!-- Badge -->
                                    <div class="col-md-6">
                                        <label for="badge" class="form-label">Badge</label>
                                        <input type="text" class="form-control" id="badge" name="badge" placeholder="Enter badge text (optional)">
                                    </div>

                                    <!-- Display -->
                                    <div class="col-md-6">
                                        <label for="display" class="form-label">Display</label>
                                        <select class="form-select" id="display" name="display">
                                            <option value="Y" selected>Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button class="btn btn-info" type="submit"><i class="mdi mdi-content-save"></i> Save Menu</button>
                                <a href="#" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                        <!--end::Form-->

                        <!--begin::JavaScript-->
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
                        <!--end::JavaScript-->
                    </div>
                </div>

                <!-- Menu List Table -->
                <div class="col-md-12">
                    <div class="card card-info card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Menu List</div>
                        </div>
                        <div class="card-body">
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
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($result)): ?>
                                        <?php 
                                        $menu_level = [
                                            '0' => 'Top Level',
                                            '1' => 'First Level',
                                            '2' => 'Second Level'
                                        ];
                                        foreach ($result as $row): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['id']); ?></td>
                                                <td><?= htmlspecialchars($row['menu_name']); ?></td>
                                                <td><?= htmlspecialchars($row['text']); ?></td>
                                                <td><?= htmlspecialchars($row['url']); ?></td>
                                                <td><?= ($row['icon'] != NULL)?htmlspecialchars($row['icon']):''; ?></td>
                                                <td><?= ($row['badge'] != NULL)?htmlspecialchars($row['badge']):''; ?></td>
                                                <td><?= htmlspecialchars($menu_level[$row['menu_level']] ?? '-'); ?></td>
                                                <td><?= htmlspecialchars($row['created_at']); ?></td>
                                                <td><?= htmlspecialchars($row['updated_at']); ?></td>
                                                <td>
                                                    <a href="menu/crudData/updation?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                    <a href="menu/crudData/deletion?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this menu?');">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="10" class="text-center text-muted">No menu items found</td></tr>
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
