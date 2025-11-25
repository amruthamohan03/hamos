<?php $types = ['I'=>'IMPORT','E'=>'EXPORT']; ?>

<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- Add New Regime -->
                <div class="card">
                    <div class="card-header">
                        <h4>Add New Regime</h4>
                    </div>
                    <div class="card-body">
                        <form id="regimeInsertForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Regime Name</label>
                                    <input type="text" name="regime_name" class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Type</label>
                                    <div>
                                        <?php foreach($types as $key => $label): ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="type[]" value="<?= $key ?>" id="type_<?= $key ?>">
                                            <label class="form-check-label" for="type_<?= $key ?>"><?= $label ?></label>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
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

                <!-- Regime List -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Regime List</h4>
                    </div>
                    <div class="card-body">
                        <table id="regime-datatable" class="table table-striped dt-responsive nowrap w-100">
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
                                <?php if(!empty($result)): $i=0; ?>
                                    <?php foreach($result as $row): $i++; ?>
                                        <tr id="regimeRow_<?= $row['id'] ?>">
                                            <td><?= $i ?></td>
                                            <td><?= htmlspecialchars($row['regime_name']) ?></td>
                                            <td>
                                                <?php
                                                $typeDisplay = ['I'=>'IMPORT','E'=>'EXPORT','IE'=>'IMPORT AND EXPORT'];
                                                $rowTypes = explode(',', $row['type']);
                                                foreach($rowTypes as $t){
                                                    echo "<span class='badge bg-success me-1'>{$typeDisplay[$t]}</span>";
                                                }
                                                ?>
                                            </td>
                                            <td><?= $row['display'] ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['updated_at'])) ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editRegimeBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></a>
                                                <a href="#" class="btn btn-sm btn-danger deleteRegimeBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>
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
<div class="modal fade" id="regimeEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="regimeUpdateForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Regime</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Regime Name</label>
                        <input type="text" name="regime_name" id="edit_regime_name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Type</label>
                        <div id="edit_types">
                            <?php foreach($types as $key => $label): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="type[]" value="<?= $key ?>" id="edit_type_<?= $key ?>">
                                <label class="form-check-label" for="edit_type_<?= $key ?>"><?= $label ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
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

    $('#regime-datatable').DataTable({"columnDefs":[{"orderable":false,"targets":-1}],"language":{"emptyTable":"No records found"}});

    // Insert
    $('#regimeInsertForm').submit(function(e){
        e.preventDefault();
        if($('#regimeInsertForm input[name="type[]"]:checked').length === 0){
            alert('❌ Please select at least one Type'); return;
        }
        $.ajax({
            url:'<?php echo APP_URL; ?>regime/crudData/insertion',
            type:'POST',
            data:$(this).serialize(),
            dataType:'json',
            success:function(res){
                if(res.success){ alert('✅ Regime inserted!'); location.reload(); }
                else alert('❌ '+res.message);
            }
        });
    });

    // Edit
    $(document).on('click','.editRegimeBtn',function(){
        let id = $(this).data('id');
        $.ajax({
            url:'<?php echo APP_URL; ?>regime/getRegimeById',
            type:'GET',
            data:{id:id},
            dataType:'json',
            success:function(res){
                if(res.success){
                    $('#edit_regime_name').val(res.data.regime_name);
                    $('#edit_display').val(res.data.display);
                    $('#edit_types input[type=checkbox]').prop('checked', false);
                    res.data.type.forEach(function(val){
                        $('#edit_types input[type=checkbox][value="'+val+'"]').prop('checked', true);
                    });
                    $('#regimeEditModal').data('id',id).modal('show');
                } else alert(res.message);
            }
        });
    });

    // Update
    $('#regimeUpdateForm').submit(function(e){
        e.preventDefault();
        let id = $('#regimeEditModal').data('id');
        if($('#regimeUpdateForm input[name="type[]"]:checked').length === 0){
            alert('❌ Please select at least one Type'); return;
        }
        $.ajax({
            url:'<?php echo APP_URL; ?>regime/crudData/updation&id='+id,
            type:'POST',
            data:$(this).serialize(),
            dataType:'json',
            success:function(res){
                if(res.success){ alert('✅ Updated successfully!'); $('#regimeEditModal').modal('hide'); location.reload(); }
                else alert('❌ '+res.message);
            }
        });
    });

    // Delete
    $(document).on('click','.deleteRegimeBtn',function(){
        let id = $(this).data('id');
        if(!confirm('Are you sure?')) return;
        $.ajax({
            url:'<?php echo APP_URL; ?>regime/crudData/deletion&id='+id,
            type:'POST',
            dataType:'json',
            success:function(res){
                if(res.success){ alert('✅ Deleted!'); location.reload(); }
                else alert('❌ '+res.message);
            }
        });
    });

});
</script>
