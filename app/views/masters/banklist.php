<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- Add New Bank -->
                <div class="card">
                    <div class="card-header">
                        <h4>Add New Bank</h4>
                    </div>
                    <div class="card-body">
                        <form id="bankInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Bank Code</label>
                                    <input type="text" name="bank_code" class="form-control" required>
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

                <!-- Bank List -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Bank List</h4>
                    </div>
                    <div class="card-body">
                        <table id="bank-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bank Name</th>
                                    <th>Bank Code</th>
                                    <th>Display</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($result)): $i=0; foreach($result as $row): $i++; ?>
                                    <tr id="bankRow_<?= $row['id'] ?>">
                                        <td><?= $i ?></td>
                                        <td><?= htmlspecialchars($row['bank_name']) ?></td>
                                        <td><?= htmlspecialchars($row['bank_code']) ?></td>
                                        <td><?= htmlspecialchars($row['display']) ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['updated_at'])) ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary editBankBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger deleteBankBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>
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
<div class="modal fade" id="bankEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="bankUpdateForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" id="edit_bank_name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Bank Code</label>
                        <input type="text" name="bank_code" id="edit_bank_code" class="form-control" required>
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
$(document).ready(function(){
    // Initialize DataTable
    $('#bank-datatable').DataTable({
        "columnDefs": [{ "orderable": false, "targets": -1 }],
        "language": { "emptyTable": "No banks found" }
    });

    // Insert
    $('#bankInsertForm').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: '<?php echo APP_URL; ?>banklist/crudData/insertion',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res){
                if(res.success){ alert('✅ ' + res.message); location.reload(); }
                else alert('❌ ' + res.message);
            }
        });
    });

    // Edit modal load
    $(document).on('click','.editBankBtn',function(){
        let id = $(this).data('id');
        $.ajax({
            url: '<?php echo APP_URL; ?>banklist/getBankById',
            type: 'GET',
            data: {id:id},
            dataType: 'json',
            success: function(res){
                if(res.success){
                    $('#edit_bank_name').val(res.data.bank_name);
                    $('#edit_bank_code').val(res.data.bank_code);
                    $('#edit_display').val(res.data.display);
                    $('#bankEditModal').data('id', id).modal('show');
                } else alert('❌ ' + res.message);
            }
        });
    });

    // Update
    $('#bankUpdateForm').submit(function(e){
        e.preventDefault();
        let id = $('#bankEditModal').data('id');
        $.ajax({
            url: '<?php echo APP_URL; ?>banklist/crudData/updation&id=' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res){
                if(res.success){
                    alert('✅ ' + res.message);
                    $('#bankEditModal').modal('hide');
                    location.reload();
                } else alert('❌ ' + res.message);
            }
        });
    });

    // Delete
    $(document).on('click','.deleteBankBtn',function(){
        let id = $(this).data('id');
        if(!confirm('Are you sure you want to delete this bank?')) return;
        $.ajax({
            url: '<?php echo APP_URL; ?>banklist/crudData/deletion&id=' + id,
            type: 'POST',
            dataType: 'json',
            success: function(res){
                if(res.success){ alert('✅ ' + res.message); location.reload(); }
                else alert('❌ ' + res.message);
            }
        });
    });
});
</script>
