<div id="response"></div>

<form method='post' action="<?= basename($_SERVER['PHP_SELF'], ".php"); ?>">
    <div class='row'>
        <div class='col-md-8'><input type='text' class='form-control' name='key' placeholder='Search By ID / Email Id / Phone' autocomplete="off"></div>
        <div class='col-md-2'><input type='submit' style='width:100%' class='btn btn-success pull-right' value='Search'></div>
    </div>
</form>
<br>
<form method="post" action="<?= basename($_SERVER['PHP_SELF'], ".php"); ?>">

    <table id="table-1" width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-stripe table-bordered'>
        <thead>
            <tr>
                <th width=3% height=22><b>ID</b></th>
                <th width=20%><b>Sent By</b></th>
                <th><b>Content</b></th>
                <th><b>State</b></th>
                <th><b>Date</b></th>
                <th><b>Status</b></th>
                <th><b>Action</b></th>
            </tr>
        </thead>
        <tbody>
            <?
            if ($total) {

                foreach ($db_list as $d) {
                    $state_name = getFldValueById("state", "name", array("id" => (int)$d['state_id']));

                    if ($d['status'] == "Y") {
                        $change = "N";
                        $status = "Viewed";
                        $bold = "";
                        $back_color = "";
                    } else {
                        $change = "Y";
                        $status = "Pending";
                        $bold = "style='font-weight:bold;'";
                        $back_color = "style='background-color: #e6ecfa;'";
                    }
                    $dot = "";
                    echo "
                        <tr $back_color>
                            <td $bold>" . $d['id'] . "</td>
                            <td $bold>" . $d['name'] . "<br>" . $d['email'] . "<br>" . $d['phone'] . "</td>
                            <td $bold>" . substr(strip_tags(html_entity_decode($d['message'])), 0, 60) . "</td>
                            <td $bold>" . $state_name . "</td>
                            <td align=center $bold>" . $d['created_at'] . "</td>
                            <td $bold>" . $status . "</td>
                            <td align=center $bold>
                                <a   href='" . basename($_SERVER['PHP_SELF'], ".php") . "?id=" . $d['id'] . "&status=" . $change . "'>Change Status</a> | 
                                <a href=\"javascript:if(confirm('Are you sure you wish to delete?')){window.location='" . basename($_SERVER['PHP_SELF'], ".php") . "?del_id=" . $d['id'] . "';}\">Delete</a>
                            </td>
                        </tr>";

                    $j++;
                }
            } else {
                echo "<font color=red>No Record found.</font>";
            }
            ?>
        </tbody>
    </table>
</form>

<? if ($total > 100) {
    echo "<ul class='pagination'>" . paging($total, "", "id=" . $_GET['id'] . "&status=" . $_GET['status']) . "</ul>";
} ?>

<!-- BEGIN FOOTER -->
<? include("includes/footer.php"); ?>