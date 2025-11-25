<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <h4>Role–Menu Mapping</h4>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label>Select Role</label>
                            <select id="role_id" class="form-select">
                                <option value="">-- Select Role --</option>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['role_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <form id="mappingForm">
                            <table class="table table-bordered align-middle dt-responsive nowrap w-100"
                                id="menuMappingTable">
                                <thead>
                                    <tr>
                                        <th>Menu Name</th>
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                        <th>Approve</th>
                                    </tr>
                                </thead>
                                <tbody id="menuTableBody"></tbody>
                            </table>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary mt-2">Save Mapping</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>
</div>

<script>
    $(document).ready(function () {
        let menuTable = $('#menuMappingTable').DataTable({
            responsive: true,
            destroy: true,
            paging: false,
            searching: true,
            ordering: true,
            info: true,
            columnDefs: [
                { orderable: false, targets: [1, 2, 3, 4, 5] },
                { className: 'text-center', targets: [1, 2, 3, 4, 5] }
            ],
            language: { emptyTable: "Select a role to load menus" }
        });

        // ✅ Load menus for role
        function loadMenus(role_id) {
            if (!role_id) {
                menuTable.clear().draw();
                menuTable.row.add([
                    "<span class='text-muted'>Select a role to load menus</span>",
                    "", "", "", "", ""
                ]).draw();
                return;
            }

            $.getJSON('<?php echo APP_URL; ?>rolemenumapping/getMapping', { role_id: role_id }, function (res) {
                menuTable.clear();
                if (res.success && res.data.length > 0) {
                    res.data.forEach(row => {
                        menuTable.row.add([
                            row.menu_name,
                            `<input type="checkbox" name="permissions[${row.menu_id}][view]" ${row.can_view == 1 ? 'checked' : ''}>`,
                            `<input type="checkbox" name="permissions[${row.menu_id}][add]" ${row.can_add == 1 ? 'checked' : ''}>`,
                            `<input type="checkbox" name="permissions[${row.menu_id}][edit]" ${row.can_edit == 1 ? 'checked' : ''}>`,
                            `<input type="checkbox" name="permissions[${row.menu_id}][delete]" ${row.can_delete == 1 ? 'checked' : ''}>`,
                            `<input type="checkbox" name="permissions[${row.menu_id}][approve]" ${row.can_approve == 1 ? 'checked' : ''}>`
                        ]);
                    });
                } else {
                    menuTable.row.add([
                        "<span class='text-muted'>No menus found for this role</span>",
                        "", "", "", "", ""
                    ]);
                }
                menuTable.draw();
            });
        }

        // ✅ Load menus when role changes
        $('#role_id').change(function () {
            const role_id = $(this).val();
            loadMenus(role_id);
        });

        // ✅ Save Mapping
        $('#mappingForm').submit(function (e) {
            e.preventDefault();
            const role_id = $('#role_id').val();
            if (!role_id) { alert('Select a role first!'); return; }

            $.ajax({
                url: '<?php echo APP_URL; ?>rolemenumapping/saveMapping',
                method: 'POST',
                data: $(this).serialize() + '&role_id=' + role_id,
                dataType: 'json',
                success: function (res) {
                    alert(res.message);
                    // 🔄 Re-fetch permissions instead of reloading the page
                    loadMenus(role_id);
                },
                error: function () {
                    alert('Error saving mapping. Please try again.');
                }
            });
        });
    });

</script>