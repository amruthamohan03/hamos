<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Item Master</h4>
                    </div>

                    <div class="card-body">
                        <form id="itemInsertForm">
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Item Short Name</label>
                                    <input type="text" class="form-control" name="short_name" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Item Full Name</label>
                                    <input type="text" class="form-control" name="item_name" required>
                                </div>

                                <!-- NEW 4 CHECKBOXES -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label><br>

                                    <label class="me-3">
                                        <input type="checkbox" name="customes_clearance"> Customs Clearance
                                    </label>

                                    <label class="me-3">
                                        <input type="checkbox" name="other_charge"> Other Charge
                                    </label>

                                    <label class="me-3">
                                        <input type="checkbox" name="operational_cost"> Operational Cost
                                    </label>

                                    <label class="me-3">
                                        <input type="checkbox" name="service_fee"> Service Fee
                                    </label>
                                </div>

                                <!-- DISPLAY -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Display</label>
                                    <select class="form-select" name="display">
                                        <option value="Y">Yes</option>
                                        <option value="N">No</option>
                                    </select>
                                </div>

                            </div>

                            <div class="text-end">
                                <button class="btn btn-primary">Save Item</button>
                                <a href="" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>

                    <!-- ITEM LIST -->
                    <div class="card-body">
                        <h4 class="header-title">Item List</h4>

                        <table id="item-datatable" class="table table-striped nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Short Name</th>
                                    <th>Full Name</th>
                                    <th>Category</th>
                                    <th>Display</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($result)): $i=1; ?>
                                    <?php foreach ($result as $row): ?>

                                        <tr id="row_<?= $row['id'] ?>">
                                            <td><?= $i++ ?></td>

                                            <td><?= htmlspecialchars($row['short_name']) ?></td>
                                            <td><?= htmlspecialchars($row['item_name']) ?></td>

                                            <!-- CATEGORY BADGES -->
                                            <td>
                                                <?php
                                                $categories = [
                                                    "Customs Clearance" => $row['customes_clearance'],
                                                    "Other Charge"      => $row['other_charge'],
                                                    "Operational Cost"  => $row['operational_cost'],
                                                    "Service Fee"       => $row['service_fee']
                                                ];

                                                foreach($categories as $label => $val){
                                                    if($val === 1){
                                                        echo "<span class='badge bg-success me-1'>$label</span>";
                                                    }
                                                }
                                                ?>
                                            </td>

                                            <td><?= $row['display'] ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['updated_at'])) ?></td>

                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary editItemBtn" data-id="<?= $row['id'] ?>">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                <a href="#" class="btn btn-sm btn-danger deleteItemBtn" data-id="<?= $row['id'] ?>">
                                                    <i class="ti ti-trash"></i>
                                                </a>
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

<!-- EDIT MODAL -->
<div class="modal fade" id="itemEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="itemUpdateForm">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label>Item Short Name</label>
                    <input type="text" class="form-control mb-2" name="short_name" id="edit_short_name" required>

                    <label>Item Full Name</label>
                    <input type="text" class="form-control mb-2" name="item_name" id="edit_item_name" required>

                    <!-- CHECKBOXES -->
                    <label class="form-label">Category</label><br>

                    <label class="me-3">
                        <input type="checkbox" name="customes_clearance" id="edit_customes_clearance"> Customs Clearance
                    </label>

                    <label class="me-3">
                        <input type="checkbox" name="other_charge" id="edit_other_charge"> Other Charge
                    </label>

                    <label class="me-3">
                        <input type="checkbox" name="operational_cost" id="edit_operational_cost"> Operational Cost
                    </label>

                    <label class="me-3">
                        <input type="checkbox" name="service_fee" id="edit_service_fee"> Service Fee
                    </label>

                    <label class="d-block mt-3">Display</label>
                    <select name="display" class="form-select" id="edit_display">
                        <option value="Y">Yes</option>
                        <option value="N">No</option>
                    </select>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Update</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){

    $('#item-datatable').DataTable();

    /* INSERT */
    $('#itemInsertForm').submit(function(e){
        e.preventDefault();

        $.post("<?= APP_URL ?>item/crudData/insertion", $(this).serialize(), function(res){
            alert(res.message);
            if(res.success) location.reload();
        }, 'json');
    });

    /* DELETE */
    $(document).on('click','.deleteitemBtn',function(){
        if(!confirm("Delete this item?")) return;
        let id = $(this).data('id');

        $.post("<?= APP_URL ?>item/crudData/deletion?id="+id, function(res){
            alert(res.message);
            if(res.success) location.reload();
        }, 'json');
    });

    /* OPEN EDIT MODAL */
    $(document).on('click','.editItemBtn',function(e){
        e.preventDefault();
        let id = $(this).data('id');

        $.get("<?= APP_URL ?>item/getItemById",{id:id}, function(res){
            if(res.success){
                let d = res.data;

                $('#edit_short_name').val(d.short_name);
                $('#edit_item_name').val(d.item_name);

                $('#edit_customes_clearance').prop('checked', d.customes_clearance == 1);
                $('#edit_other_charge').prop('checked', d.other_charge == 1);
                $('#edit_operational_cost').prop('checked', d.operational_cost == 1);
                $('#edit_service_fee').prop('checked', d.service_fee == 1);

                $('#edit_display').val(d.display);

                $('#itemEditModal').data('id', id).modal('show');
            }
        }, 'json');
    });

    /* UPDATE */
    $('#itemUpdateForm').submit(function(e){
        e.preventDefault();

        let id = $('#itemEditModal').data('id');

        $.post("<?= APP_URL ?>item/crudData/updation?id="+id, $(this).serialize(), function(res){
            alert(res.message);
            if(res.success){
                $('#itemEditModal').modal('hide');
                location.reload();
            }
        }, 'json');
    });

});
</script>
