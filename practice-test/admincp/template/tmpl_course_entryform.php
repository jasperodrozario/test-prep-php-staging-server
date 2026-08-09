<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>" enctype="multipart/form-data">
  <input type="hidden" name="course_id" value="<?php echo $_GET['course_id'] ?>">
  <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

    <tr>
      <td align="left" width=20%>Name</td>
      <td><input type="text" class='form-control' name='course_name' value="<?php echo $db_value['course_name'] ?>" required></td>
    </tr>

    <tr>
      <td>Description </td>
      <td><textarea class='form-control description' name='description'><?= html_entity_decode($db_value['description']); ?></textarea></td>
    </tr>

    <tr>
      <td align="left">Display Order</td>
      <td><input type="number" class='form-control' name='ord' value="<?php echo $db_value['ord'] ?>"></td>
    </tr>

    <tr>
      <td width="20%">Course Group</td>
      <td>
        <select name='group_id' size=1 class='form-control' required>
          <option value='0'>Select Course Group</option>
          <?
          $course_group = getQuery("course_group", "*", "", "group_name");
          foreach ($course_group as $dd) {
            if ($db_value['group_id'] == $dd['group_id']):
              echo "<option value=" . $dd['group_id'] . " selected>" . $dd['group_name'] . "</option>";
            else:
              echo "<option value=" . $dd['group_id'] . ">" . $dd['group_name'] . "</option>";
            endif;
          }
          ?>
        </select>
      </td>
    </tr>

    <table width="100%">
      <tr>
        <td width="25%" align="center">
          <input type="checkbox" name="is_online" <?php echo ($db_value['is_online'] == 'Y') ? 'checked' : ''; ?>> Is Online
        </td>
        <td width="25%" align="center">
          <input type="checkbox" name="app" value="1" <?php echo ($db_value['app'] == 1) ? 'checked' : ''; ?>> App
        </td>
        <td width="25%" align="center">
          <input type="checkbox" name="tutoring" value="1" <?php echo ($db_value['tutoring'] == 1) ? 'checked' : ''; ?>> Tutoring
        </td>
        <td width="25%" align="center">
          <input type="checkbox" name="subscribe" value="1" <?php echo ($db_value['subscribe'] == 1) ? 'checked' : ''; ?>> Subscribe
        </td>
      </tr>

      <!-- Spacer row -->
      <tr>
        <td colspan="4" style="height: 20px;"></td>
      </tr>

      <tr>
        <td colspan="4" align="center">
          <input type="submit" name="SBMT_REG" value="Update" class="btn btn-success">
        </td>
      </tr>
    </table>

  </table>
</form>

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

    $('#saveOrderBtn').click(function() {
      var order = $('#orderInput').val();
      var name = $('#nameInput').val();

      // Perform an AJAX request to update the order in the database
      $.ajax({
        type: 'POST',
        url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax", // Replace with the actual URL of your server-side script
        data: "action=createGroup&group_name=" + name + "&order=" + order
      }).done(function(data) {
        // Display the response message
        $("#response").html(data.msg);

        // Close the modal/pop-up
        $('#setPredSetModal').modal('hide');

        // Refresh the page after a short delay (adjust the delay as needed)
        setTimeout(function() {
          location.reload();
        });
      });
    });
  });
</script>