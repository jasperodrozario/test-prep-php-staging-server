<div><? $msg ?>

    <?php
    /*
 echo '<pre>';
if($original_url){
    echo "original_url:";
    print_r($original_url);
    echo "<br>:";
    //echo "test_id:";
    //print_r($test_id);
    

    

} else{
    echo "not set";
}
 
//echo $_SERVER['REQUEST_URI'];
echo '</pre>';
*/
    ?>


</div>
<div>
    <form id="categoryForm" method="post" action="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>" enctype="multipart/form-data">
        <input type="hidden" name="test_id" value="<?php echo $_GET['test_id'] ?>">
        <input type="hidden" name="cat_id" value="<?php echo $_GET['cat_id'] ?>">
        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
        <div>
            <table class="table align-middle">
                <tr>

                    <td>
                        <label for="catInput" class="form-label"><b>Category Name:</b></label>
                        <?php
                        $selectedCatId = isset($_GET['cat_id']) ? $_GET['cat_id'] : '';
                        ?>
                        <select id="catInput" name="cat_id_drop" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['cat_id'] ?>" <?= $c['cat_id'] == $selectedCatId ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <td>
                        <label for="quesInput" class="form-label"><b>Question Source:</b></label>
                        <?php
                        $existing_pred_id = isset($cat_data['pred_qb_id']) ? $cat_data['pred_qb_id'] : '';
                        ?>
                        <select id="quesInput" name="pred_qb_id" class="form-control">
                            <option value="">Select Question Source</option>
                            <?php foreach ($ques_sources as $q): ?>
                                <option value="<?= $q['pred_id'] ?>" <?= $q['pred_id'] == $existing_pred_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($q['cat_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <td>
                        <label for="num_ques" class="form-label"><b>Num of Ques:</b></label>
                        <?php
                        $existingNumQues = isset($cat_data['number_of_questions']) ? $cat_data['number_of_questions'] : '';
                        $existingDuration = isset($cat_data['duration']) ? $cat_data['duration'] : '';
                        $existing_order = isset($cat_data['order_no']) ? $cat_data['order_no'] : '';
                        ?>
                        <input type="number" name="num_ques" id="num_ques" class="form-control" value="<?= $existingNumQues ?>">
                    </td>

                    <td>
                        <label for="duration" class="form-label"><b>Duration:</b></label>
                        <input type="number" name="duration" id="duration" class="form-control" value="<?= $existingDuration ?>">
                    </td>

                    <td>
                        <label for="order" class="form-label"><b>Order:</b></label>
                        <input type="number" name="order" id="order" class="form-control" value="<?= $existing_order ?>">
                    </td>

                    <td class="align-bottom">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100" name="add_btn" id="saveOrderBtn"><b>Save</b></button>
                    </td>
                </tr>
            </table>

        </div>
    </form>
</div>

<!-- Displaying the list of categories -->
<div>
    <form method="post" action="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>" enctype="multipart/form-data">

        <table width="100%" class="table table-bordered table-striped" cellspacing="1" cellpadding="4">
            <thead>
                <tr>
                    <td><b>ID/Ord</b></td>
                    <td><b>Prediction Test Name</b></td>
                    <td><b>Question Source</b></td>
                    <td><b>Number of Questions</b></td>
                    <td><b>Duration</b></td>
                    <td width="25%"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($pred_test_cat as $c) {
                    $cat_name = getFldValueById("category", "category_name", array("cat_id" => $c['cat_id']));
                    $ques_source_name = getFldValueById("pred_test_category", "cat_name", array("pred_id" => $c['pred_qb_id']));
                ?>
                    <tr>
                        <td id="cat_id"><?php echo $c['id'] ?>/<?php echo $c['order_no'] ?></td>
                        <td id="cat_name"><?php echo $cat_name ?></td>
                        <td ><?php echo $ques_source_name ?></td>
                        <td><?php echo $c['number_of_questions'] ?></td>
                        <td><?php echo $c['duration'] ?></td>
                        <td>
                            <?php
                            echo "<a href='pred-create-category?test_id=" . $c['pred_test_id'] . "&cat_id=" . $c['cat_id'] . "&id=" . $c['id'] . "'><u>Edit</u></a> | ";
                            echo "<a href='#' class='delete-category' 
                        data-id='{$c['id']}' 
                        data-pred_test_id='{$c['pred_test_id']}' 
                        data-cat_id='{$c['cat_id']}'>
                        <u>Delete</u></a>";

                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>

</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '.delete-category', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var predTestId = $(this).data('pred_test_id');
            var catId = $(this).data('cat_id');

            if (confirm('Are you sure you want to delete this category?')) {
                $.ajax({
                    type: 'POST',
                    url: '<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax', // change to your delete handler
                    data: {
                        action: 'deletePredCat',
                        id: id,
                        pred_test_id: predTestId,
                        cat_id: catId
                    },
                    success: function(response) {
                        try {
                            var result = JSON.parse(response);
                            if (result.status === 'success') {
                                //alert('Category deleted successfully.');
                                // remove the row from table
                                $('a.delete-category[data-id="' + id + '"]').closest('tr').remove();
                            } else {
                                alert(result.msg || 'Deletion failed.');
                            }
                        } catch (e) {
                            alert('Unexpected server response.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            }
        });
    });
</script>