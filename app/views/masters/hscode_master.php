<?php $types = ['I'=>'IMPORT', 'E'=>'EXPORT']; ?>

<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- Add New HS Code -->
                <div class="card">
                    <div class="card-header">
                        <h4>Add New HS Code</h4>
                    </div>
                    <div class="card-body">
                        <form id="hscodeInsertForm">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label>HS Code Number</label>
                                    <input type="text" id="hscode_number" name="hscode_number" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label>DDI</label>
                                    <input type="number" step="0.01" id="hscode_ddi"name="hscode_ddi" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label>ICA</label>
                                    <input type="number" step="0.01"id="hscode_ica" name="hscode_ica" class="form-control" value="0">
                                </div>
                                <div class="col-md-2">
                                    <label>DCI</label>
                                    <input type="number" step="0.01" id="hscode_dci" name="hscode_dci" class="form-control" value="0">
                                </div>
                                <div class="col-md-2">
                                    <label>DCL</label>
                                    <input type="number" step="0.01" id="hscode_dcl"name="hscode_dcl" class="form-control" value="0">
                                </div>
                                <div class="col-md-2">
                                    <label>TPI</label>
                                    <input type="number" step="0.01" id="hscode_tpi"name="hscode_tpi" class="form-control" value="0">
                                </div>
                                <div class="col-md-2">
                                    <label>Display</label>
                                    <select name="display" class="form-select">
                                        <option value="Y" selected>Yes</option>
                                        <option value="N">No</option>
                                    </select>
                                </div>

                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- HS Code List -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>HS Code List</h4>
                    </div>
                    <div class="card-body">
                        <table id="hscode-datatable" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>HS Code</th>
                                    <th>DDI</th>
                                    <th>ICA</th>
                                    <th>DCI</th>
                                    <th>DCL</th>
                                    <th>TPI</th>
                                    <th>Display</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($result)): $i=1; foreach($result as $row): ?>
                                    <tr id="hscodeRow_<?= $row['id'] ?>">
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($row['hscode_number']) ?></td>
                                        <td><?= $row['hscode_ddi'] ?></td>
                                        <td><?= $row['hscode_ica'] ?></td>
                                        <td><?= $row['hscode_dci'] ?></td>
                                        <td><?= $row['hscode_dcl'] ?></td>
                                        <td><?= $row['hscode_tpi'] ?></td>
                                        <td><?= $row['display'] ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary editHscodeBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></button>
                                            <button class="btn btn-sm btn-danger deleteHscodeBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></button>
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
<div class="modal fade" id="editHscodeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="hscodeUpdateForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit HS Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label>HS Code Number</label>
                            <input type="text" name="hscode_number" id="edit_hscode_number" class="form-control">
                        </div>
                        <div class="col-md-2"><label>DDI</label><input type="number" step="0.01" name="hscode_ddi" id="edit_hscode_ddi" class="form-control"></div>
                        <div class="col-md-2"><label>ICA</label><input type="number" step="0.01" name="hscode_ica" id="edit_hscode_ica" class="form-control"></div>
                        <div class="col-md-2"><label>DCI</label><input type="number" step="0.01" name="hscode_dci" id="edit_hscode_dci" class="form-control"></div>
                        <div class="col-md-2"><label>DCL</label><input type="number" step="0.01" name="hscode_dcl" id="edit_hscode_dcl" class="form-control"></div>
                        <div class="col-md-2"><label>TPI</label><input type="number" step="0.01" name="hscode_tpi" id="edit_hscode_tpi" class="form-control"></div>
                        <div class="col-md-2">
                            <label>Display</label>
                            <select name="display" id="edit_display" class="form-select">
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(function(){
    $('#hscode-datatable').DataTable();

    // INSERT
    $('#hscodeInsertForm').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url:'<?php echo APP_URL; ?>hscode/crudData/insertion',
            type:'POST',
            data:$(this).serialize(),
            dataType:'json',
            success:function(res){
                if(res.success){ alert('✅ Inserted Successfully'); location.reload(); }
                else {
                    alert('❌ '+res.message);
                    $('#hscode_number').val('');
                    $('#hscode_ddi').val('');
                    $('#hscode_ica').val('0');
                    $('#hscode_dci').val('0');
                    $('#hscode_dcl').val('0');
                    $('#hscode_tpi').val('0');

                    // ✅ Focus on first field
                    $('#hscode_number').focus();
                }
            }
        });
    });

    // EDIT FETCH
    $(document).on('click','.editHscodeBtn',function(){
        let id = $(this).data('id');
        $.get('<?php echo APP_URL; ?>hscode/getHscodeById',{id:id},function(res){
            if(res.success){
                $('#edit_hscode_number').val(res.data.hscode_number);
                $('#edit_hscode_ddi').val(res.data.hscode_ddi);
                $('#edit_hscode_ica').val(res.data.hscode_ica);
                $('#edit_hscode_dci').val(res.data.hscode_dci);
                $('#edit_hscode_dcl').val(res.data.hscode_dcl);
                $('#edit_hscode_tpi').val(res.data.hscode_tpi);
                $('#edit_display').val(res.data.display);
                $('#editHscodeModal').data('id',id).modal('show');
            } else alert(res.message);
        },'json');
    });

    // UPDATE
    $('#hscodeUpdateForm').on('submit',function(e){
        e.preventDefault();
        let id = $('#editHscodeModal').data('id');
        $.ajax({
            url:'<?php echo APP_URL; ?>hscode/crudData/updation&id='+id,
            type:'POST',
            data:$(this).serialize(),
            dataType:'json',
            success:function(res){
                if(res.success){ alert('✅Data Updated Successfully..'); $('#editHscodeModal').modal('hide'); location.reload(); }
                else alert('❌ '+res.message);
            }
        });
    });

    // DELETE
    $(document).on('click','.deleteHscodeBtn',function(){
        if(!confirm('Delete this HS Code?')) return;
        let id = $(this).data('id');
        $.post('<?php echo APP_URL; ?>hscode/crudData/deletion&id='+id,function(res){
            if(res.success){ alert('✅Data Deleted Successfully.. '); location.reload(); }
            else alert('❌ '+res.message);
        },'json');
    });
});
</script>
