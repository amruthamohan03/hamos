<div class="page-content">
  <div class="page-container">
    <div class="row">
      <div class="col-12">

        <!-- Add Group Company -->
        <div class="card">
          <div class="card-header border-bottom border-dashed">
            <h4 class="header-title">Add New Group Company</h4>
          </div>
          <div class="card-body">
            <form id="groupCompanyInsertForm">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="group_company_name" class="form-label">Group Company Name</label>
                  <input type="text" class="form-control" id="group_company_name" name="group_company_name" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="display" class="form-label">Display</label>
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

        <!-- List -->
        <div class="card mt-3">
          <div class="card-header border-bottom border-dashed">
            <h4 class="header-title">Group Company List</h4>
          </div>
          <div class="card-body">
            <table id="groupCompanyTable" class="table table-striped dt-responsive nowrap w-100">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Group Company Name</th>
                  <th>Display</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($result)): $i = 0; foreach ($result as $row): $i++; ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= htmlspecialchars($row['group_company_name']) ?></td>
                  <td><?= htmlspecialchars($row['display']) ?></td>
                  <td>
                    <a href="#" class="btn btn-sm btn-primary editBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-danger deleteBtn" data-id="<?= $row['id'] ?>"><i class="ti ti-trash"></i></a>
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
<div class="modal fade" id="groupCompanyEditModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="groupCompanyUpdateForm" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Group Company</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Group Company Name</label>
            <input type="text" name="group_company_name" id="edit_group_company_name" class="form-control" required>
          </div>
          <div class="mb-3">
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
$(document).ready(function() {
  $('#groupCompanyTable').DataTable({
    columnDefs: [{ orderable: false, targets: -1 }],
    language: { emptyTable: "No records found" }
  });

  // ✅ Insert
  $('#groupCompanyInsertForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: '<?= APP_URL ?>groupcompany/crudData/insertion',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(res) {
        alert(res.message);
        if (res.success) location.reload();
      }
    });
  });

  // ✅ Edit
  $(document).on('click', '.editBtn', function() {
    const id = $(this).data('id');
    $.ajax({
      url: '<?= APP_URL ?>groupcompany/getGroupCompanyById',
      type: 'GET',
      data: { id },
      dataType: 'json',
      success: function(res) {
        if (res.success) {
          $('#edit_group_company_name').val(res.data.group_company_name);
          $('#edit_display').val(res.data.display);
          $('#groupCompanyEditModal').data('id', id).modal('show');
        } else alert(res.message);
      }
    });
  });

  // ✅ Update
  $('#groupCompanyUpdateForm').on('submit', function(e) {
    e.preventDefault();
    const id = $('#groupCompanyEditModal').data('id');
    $.ajax({
      url: '<?= APP_URL ?>groupcompany/crudData/updation?id=' + id,
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(res) {
        alert(res.message);
        if (res.success) {
          $('#groupCompanyEditModal').modal('hide');
          location.reload();
        }
      }
    });
  });

  // ✅ Delete
  $(document).on('click', '.deleteBtn', function() {
    const id = $(this).data('id');
    if (!confirm('Are you sure you want to delete this Group Company?')) return;
    $.ajax({
      url: '<?= APP_URL ?>groupcompany/crudData/deletion?id=' + id,
      type: 'POST',
      dataType: 'json',
      success: function(res) {
        alert(res.message);
        if (res.success) location.reload();
      }
    });
  });
});
</script>
