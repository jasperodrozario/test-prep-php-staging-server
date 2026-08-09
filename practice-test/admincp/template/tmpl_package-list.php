<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>" enctype="multipart/form-data">
    <table  width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
        <thead>
            <tr>
              <td><b>ID</b></td>
              <td><b>Course Name/ Duration</b></td>
              <td><b>Price</b></td>
              <td align="center" with=25%><b>Action</b></td>
            </tr>
        </thead>
        
        <tbody>
            <?php
            $j=0;
            foreach($db_list as $d)
            {
                if($total_package[$d['course_id']])
                {
                ?>
                <tr style="background-color: #1ABC9C;">
                    <td><?php echo $d['course_id']?></td>
                    <td ><?php echo $d['course_name'];?></td>
                    <td>-</td>
                    <td align="center">
                    <?php
                        echo "<a href='package-entryform?course_id=".$d['course_id']."'><u>Add New Package</u></a>";
                    ?>
                    </td>
                </tr>
                <?php
                
                    foreach($package_list[$d['course_id']] as $dd)
                    {
                ?>
                      <tr>
                          <td><?php echo $dd['package_id']?></td>
                          <td ><?php echo $dd['duration']?></td>
                          <td><?php echo $dd['price']?></td>
                          <td align="center">
                            <?php
                                echo"<a  href='package-entryform?package_id=" . $dd['package_id'] . "'><u>Edit</u></a> | ";

                                echo"<a  href=\"javascript:if (confirm('Are you sure you want to delete this package?')){window.location.href='package-list?del_id=" . $dd['package_id'] . "'}\"><u>Delete</u></a>";
                            ?>
                           </td>
                       </tr>   
                 <?php 
                    }
                }
            }	
            ?>
        </tbody>
    </table>
</form>

<style>
    .link {
        color: inherit; /* Set the default text color */
        text-decoration: none; /* Remove underline */
    }

    .link:hover {
        color: white; /* Change text color on hover */
    }
</style>

<script>
//$(document).ready(function() {
//    $('.set-pred-set-btn').click(function() {
//        var testId = $(this).data('test-id');
//        $('#saveOrderBtn').data('test-id', testId);
//
//        // Make an AJAX request to get categories for the selected test ID
//        $.ajax({
//            type: 'POST',
//            url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
//            data: "action=getPredCategories&testId=" + testId
//        }).done(function(data) {
//            $("#catInput").html(data.list);
//        });
//    });
//    
//    $('#saveOrderBtn').click(function() {
//        var predId = $(this).data('test-id');
//        var order = $('#orderInput').val();
//        var cat_id = $('#catInput').val();
//
//        // Perform an AJAX request to update the order in the database
//        $.ajax({
//            type: 'POST',
//            url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax", // Replace with the actual URL of your server-side script
//            data: "action=addPredCat&predId=" + predId + "&qnum=" + order + "&cat_id=" + cat_id
//        }).done(function(data) {
//            // Display the response message
//            $("#response").html(data.msg);
//            
//            // Close the modal/pop-up
//            $('#setPredSetModal').modal('hide');
//            
//            // Refresh the page after a short delay (adjust the delay as needed)
//            setTimeout(function() {
//                location.reload();
//            });
//        });
//    });
//});
</script>










