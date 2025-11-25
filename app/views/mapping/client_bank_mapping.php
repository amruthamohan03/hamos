<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <h4>Client-Bank Mapping</h4>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label>Select Client</label>
                            <select id="client_id" class="form-select">
                                <option value="">-- Select Client --</option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= $client['id'] ?>">
                                        <?= htmlspecialchars($client['short_name']) ?> 
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <form id="mappingForm">
                            <table class="table table-bordered align-middle dt-responsive nowrap w-100" id="bankMappingTable">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Bank Name</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="bankTableBody"></tbody>
                            </table>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary mt-2" style="display:none;" id="saveBtn">
                                    <i class="fas fa-save"></i> Save Mapping
                                </button>
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
$(document).ready(function() {
    let bankTable = $('#bankMappingTable').DataTable({
        responsive: true,
        destroy: true,
        paging: false,
        searching: true,
        ordering: true,
        info: true,
        columnDefs: [
            { orderable: false, targets: [0] },
            { className: 'text-center', targets: [0, 2] }
        ],
        language: { emptyTable: "Select a client to load banks" }
    });

    // Load banks for client
    function loadBanks(client_id) {
        if (!client_id) {
            bankTable.clear().draw();
            $('#saveBtn').hide();
            bankTable.row.add([
                "",
                "<span class='text-muted'>Select a client to load banks</span>",
                ""
            ]).draw();
            return;
        }

        $('#saveBtn').show();
        
        $.getJSON('<?php echo APP_URL; ?>clientbankmapping/getMapping', { client_id: client_id }, function(res) {
            bankTable.clear();
            if (res.success && res.data.length > 0) {
                res.data.forEach(row => {
                    let checkbox = `<input type="checkbox" name="banks[]" value="${row.bank_id}" 
                                    class="form-check-input bank-checkbox" ${row.is_mapped == 1 ? 'checked' : ''}>`;
                    let status = row.is_mapped == 1 
                        ? '<span class="badge bg-success">Mapped</span>' 
                        : '<span class="badge bg-secondary">Not Mapped</span>';
                    
                    bankTable.row.add([
                        checkbox,
                        row.invoice_bank_name,
                        status
                    ]);
                });
            } else {
                bankTable.row.add([
                    "",
                    "<span class='text-muted'>No banks found</span>",
                    ""
                ]);
            }
            bankTable.draw();
        });
    }

    // Client change event
    $('#client_id').change(function() {
        const client_id = $(this).val();
        loadBanks(client_id);
    });

    // Select/Deselect all checkboxes
    $('#selectAll').on('change', function() {
        $('.bank-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Update select all checkbox
    $(document).on('change', '.bank-checkbox', function() {
        let totalCheckboxes = $('.bank-checkbox').length;
        let checkedCheckboxes = $('.bank-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // Save Mapping
    $('#mappingForm').submit(function(e) {
        e.preventDefault();
        const client_id = $('#client_id').val();
        
        if (!client_id) {
            alert('Please select a client first!');
            return;
        }

        $.ajax({
            url: '<?php echo APP_URL; ?>clientbankmapping/saveMapping',
            method: 'POST',
            data: $(this).serialize() + '&client_id=' + client_id,
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert(res.message);  // Changed from toastr.success to alert
                    // Reload to refresh status badges
                    loadBanks(client_id);
                } else {
                    alert(res.message);  // Changed from toastr.error to alert
                }
            },
            error: function() {
                alert('Error saving mapping. Please try again.');  // Changed from toastr.error to alert
            }
        });
    });
});
</script>