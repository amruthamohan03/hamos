<?php $types = ['entry','exit','loading','destination','warehouse','location']; ?>

<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- Add New Transit Point -->
                <div class="card">
                    <div class="card-header">
                        <h4>Add New Transit Point</h4>
                    </div>
                    <div class="card-body">
                        <form id="transitInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Transit Point Name</label>
                                    <input type="text" name="transit_point_name" class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Type</label><br>
                                    <?php foreach($types as $t): ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="type[]" value="<?= $t ?>" id="type_<?= $t ?>">
                                            <label class="form-check-label" for="type_<?= $t ?>"><?= ucfirst($t) ?></label>
                                        </div>
                                    <?php endforeach; ?>
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

                <!-- Transit Point List -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Transit Point List</h4>
                    </div>
                    <div class="card-body">
                        <table id="transit-datatable" class="table table-striped nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Type</th>
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
                                            <td><?= htmlspecialchars($row['transit_point_name']) ?></td>
                                            <td>
                                                <?php
                                                $flags = [
                                                    'Entry' => $row['entry_point'],
                                                    'Exit' => $row['exit_point'],
                                                    'Loading' => $row['loading'],
                                                    'Destination' => $row['destination'],
                                                    'Warehouse' => $row['warehouse'],
                                                    'Location' => $row['location'],
                                                ];
                                                foreach($flags as $label=>$val){
                                                    if($val==='Y') echo "<span class='badge bg-success me-1'>$label</span>";
                                                }
                                                ?>
                                            </td>
                                            <td><?= $row['display'] ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['updated_at'])) ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editTransitBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></a>
                                                <a href="#" class="btn btn-sm btn-danger deleteTransitBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>
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
<div class="modal fade" id="transitEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="transitUpdateForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transit Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Transit Point Name</label>
                        <input type="text" name="transit_point_name" id="edit_transit_name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Type</label><br>
                        <?php foreach($types as $t): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="type[]" value="<?= $t ?>" id="edit_type_<?= $t ?>">
                                <label class="form-check-label" for="edit_type_<?= $t ?>"><?= ucfirst($t) ?></label>
                            </div>
                        <?php endforeach; ?>
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
$(function(){
    $('#transit-datatable').DataTable({
        columnDefs:[{orderable:false,targets:-1}],
        language:{emptyTable:"No records found"}
    });

    // ➕ Insert
    $('#transitInsertForm').submit(function(e){
        e.preventDefault();
        if($('input[name="type[]"]:checked').length===0){alert('❌ Select at least one Type.');return;}
        $.post('<?php echo APP_URL; ?>transitpoint/crudData/insertion', $(this).serialize(), function(res){
            if(res.success){ alert(res.message); location.reload(); }
            else alert('❌ '+res.message);
        },'json');
    });

    // ✏️ Edit
    $(document).on('click','.editTransitBtn',function(){
        let id=$(this).data('id');
        $.get('<?php echo APP_URL; ?>transitpoint/getTransitPointById',{id:id},function(res){
            if(res.success){
                $('#edit_transit_name').val(res.data.transit_point_name);
                $('#edit_display').val(res.data.display);
                $('#transitEditModal input[type=checkbox]').prop('checked',false);
                res.data.type.forEach(t=>{
                    $('#edit_type_'+t).prop('checked',true);
                });
                $('#transitEditModal').data('id',id).modal('show');
            }else alert(res.message);
        },'json');
    });

    // ✅ Update
    $('#transitUpdateForm').submit(function(e){
        e.preventDefault();
        let id=$('#transitEditModal').data('id');
        if($('#transitUpdateForm input[name="type[]"]:checked').length===0){alert('❌ Select at least one Type.');return;}
        $.post('<?php echo APP_URL; ?>transitpoint/crudData/updation&id='+id,$(this).serialize(),function(res){
            if(res.success){ alert(res.message); $('#transitEditModal').modal('hide'); location.reload(); }
            else alert('❌ '+res.message);
        },'json');
    });

    // 🗑️ Delete
    $(document).on('click','.deleteTransitBtn',function(){
        let id=$(this).data('id');
        if(!confirm('Are you sure to delete?')) return;
        $.post('<?php echo APP_URL; ?>transitpoint/crudData/deletion&id='+id,function(res){
            if(res.success){ alert(res.message); $('#row_'+id).fadeOut(); }
            else alert('❌ '+res.message);
        },'json');
    });
});
</script>
