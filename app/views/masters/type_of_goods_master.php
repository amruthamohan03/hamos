<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- Add Goods Form -->
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">Add New Goods Type</h4>
                    </div>
                    <div class="card-body">
                        <form id="goodsInsertForm" method="post">
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Goods Name</label>
                                    <input type="text" class="form-control" id="goods_type" name="goods_type" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Goods Short Name</label>
                                    <input type="text" class="form-control" id="goods_short_name" name="goods_short_name">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Display</label>
                                    <select class="form-select" id="display" name="display">
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

                <!-- Goods List -->
                <div class="card mt-3">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">Goods Type List</h4>
                    </div>
                    <div class="card-body">
                        <table id="goods-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Goods Name</th>
                                    <th>Short Name</th>
                                    <th>Display</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($result)): $i=0; foreach ($result as $row): $i++; ?>
                                <tr id="goodsRow_<?= $row['id'] ?>">
                                    <td><?= $i ?></td>
                                    <td><?= htmlspecialchars($row['goods_type']) ?></td>
                                    <td><?= htmlspecialchars($row['goods_short_name']) ?></td>
                                    <td>
                                        <?= $row['display']=="Y"
                                            ? "<span class='badge bg-success'>Yes</span>"
                                            : "<span class='badge bg-danger'>No</span>"
                                        ?>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary editGoodsBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-danger deleteGoodsBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>
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
<div class="modal fade" id="goodsEditModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="goodsUpdateForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Goods Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <label>Goods Name</label>
                        <input type="text" name="goods_type" id="edit_goods_type" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Goods Short Name</label>
                        <input type="text" name="goods_short_name" id="edit_goods_short_name" class="form-control">
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
$(document).ready(function () {

    $('#goods-datatable').DataTable({
        columnDefs: [{ orderable: false, targets: -1 }],
        language:{ emptyTable:"No records found" }
    });

    // INSERT
    $('#goodsInsertForm').submit(function(e){
        e.preventDefault();
        $.post('<?php echo APP_URL; ?>typeofgoods/crudData/insertion', $(this).serialize(), function(res){
            alert(res.message);
            if(res.success) location.reload();
        }, 'json');
    });

    // EDIT FETCH & OPEN MODAL
    $(document).on('click','.editGoodsBtn', function(){
        let id = $(this).data('id');
        $.get('<?php echo APP_URL; ?>typeofgoods/getGoodsById', {id}, function(res){
            if(res.success){
                $('#edit_goods_type').val(res.data.goods_type);
                $('#edit_goods_short_name').val(res.data.goods_short_name);
                $('#edit_display').val(res.data.display);
                $('#goodsEditModal').data('id', id).modal('show');
            } else alert(res.message);
        }, 'json');
    });

    // UPDATE
    $('#goodsUpdateForm').submit(function(e){
        e.preventDefault();
        let id = $('#goodsEditModal').data('id');

        $.post(`<?php echo APP_URL; ?>typeofgoods/crudData/updation?id=${id}`, $(this).serialize(), function(res){
            alert(res.message);
            if(res.success){
                $('#goodsEditModal').modal('hide');
                location.reload();
            }
        }, 'json');
    });

    // DELETE
    $(document).on('click','.deleteGoodsBtn', function(){
        let id = $(this).data('id');

        if(!confirm("Delete this record?")) return;

        $.post(`<?php echo APP_URL; ?>typeofgoods/crudData/deletion?id=${id}`, {}, function(res){
            alert(res.message);
            if(res.success){
                $('#goodsRow_'+id).fadeOut(400,function(){ $(this).remove() });
            }
        }, 'json');
    });

});
</script>
