<br>

<table width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
  <thead>
    <tr>
      <td align="center"><b>ID</b></td>
      <td align="center"><b>Name</b></td>
      <td align="center"><b>Order</b></td>
      <td align="center" with=25%><b>Action</b></td>
    </tr>
  </thead>
  <tbody>
    <?php
    $j = 0;
    foreach ($course_group_list as $c) {
    ?>
      <tr>
        <td><?php echo $c['group_id'] ?></td>
        <td><?php echo $c['group_name']; ?></td>
        <td><?php echo $c['ord'] ?></td>

        <td align="center">
          <?php
          // echo "<a  href='course_entryform?course_id=" . $d['course_id'] . "'><u>Edit</u></a> | ";

          echo "<a href='#'
                            class='edit-group'
                            data-id='" . $c['group_id'] . "'
                            data-name=\"" . htmlspecialchars($c['group_name'], ENT_QUOTES) . "\"
                            data-order='" . $c['ord'] . "'
                            data-toggle='modal'
                            data-target='#setPredSetModal'>
                            <u>Edit</u>
                        </a> | ";

          echo "<a  href=\"javascript:if (confirm('Are you sure you want to delete `" . $c['group_name'] . "`?')){window.location.href='course-group?del_id=" . $c['group_id'] . "'}\"><u>Delete</u></a>";
          ?>
        </td>
      </tr>
    <?php
    }
    ?>
  </tbody>
</table>

<div class="modal fade" id="setPredSetModal" tabindex="-1" role="dialog" aria-labelledby="setPredSetModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="setPredSetModalLabel">Create Course Group</h5>
      </div>
      <div class="modal-body">
        <div>
          <input type="hidden" id="groupId">
          <label for="nameInput">Enter Group Name:</label>
          <input type="text" id="nameInput" class="form-control" />
          <label for="orderInput">Group Order:</label>
          <input type="number" id="orderInput" class="form-control" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="saveOrderBtn">Create Group</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  $(document).ready(function() {

    $('.set-pred-set-btn').click(function() {

      $('#groupId').val('');
      $('#nameInput').val('');
      $('#orderInput').val('');

      $('#setPredSetModalLabel').text('Create Course Group');
      $('#saveOrderBtn').text('Create Group');
    });

    $('#saveOrderBtn').click(function() {

      var id = $('#groupId').val();
      var name = $('#nameInput').val();
      var order = $('#orderInput').val();

      var action = (id == '') ? 'createGroup' : 'updateGroup';

      $.ajax({
        type: 'POST',
        url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
        data: {
          action: action,
          group_id: id,
          group_name: name,
          order: order
        },
        success: function() {

          $('#setPredSetModal').modal('hide');

          setTimeout(function() {
            location.reload();
          }, 300);
        }
      });

    });

    $(document).on('click', '.edit-group', function() {

      $('#groupId').val($(this).data('id'));
      $('#nameInput').val($(this).data('name'));
      $('#orderInput').val($(this).data('order'));

      $('#setPredSetModalLabel').text('Edit Course Group');
      $('#saveOrderBtn').text('Update Group');
    });
  });
</script>