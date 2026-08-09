<?
echo "<br>";
switch ($action) {
    case "app-list":
?>
        <table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
            <thead>
                <tr>
                    <td align="center"><b>ID</b></td>
                    <td align="center"><b>Logo</b></td>
                    <td align="center"><b>Name</b></td>
                    <td align="center"><b>App Description</b></td>
                    <td align="center"><b>App Store URL</b></td>
                    <td align="center"><b>Google Play Store URL</b></td>
                    <td align="center"><b> Order </b></td>
                    <td width="20%" align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($db_list as $d) {
                    if (!$d['logo_img']) {
                        $d['logo_img'] = "uploads/no-user.png";
                    }

                    $image_path = str_replace("practice-test/", "", $configs['vpath']);

                    echo "
                    <tr>
                        <td align='center'>" . $d['app_id'] . "</td>
                        <td>
                            <img src='" . $configs['vpath'] . "image.php?src=" . $image_path . $d['logo_img'] . "&w=70'>
                        </td>
                        <td>" . ucwords($d['app_name']) . "</td>
                        <td>". $d['app_description'] ."</td>
                        <td>" . $d['app_store_url'] . "</td>
                        <td>" . $d['google_play_url'] . "</td>
                        <td>" . $d['ord'] . "</td>
                        <td align='center'> 
                            <a   href='asvab-mobile-apps?action=add-app&app_id=" . $d['app_id'] . "' class='btn btn-info btn-sm'>Edit</a>  
                            <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='asvab-mobile-apps?action=app-list&del_app_id=" . $d['app_id'] . "';}\" class='btn btn-danger btn-sm'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <?
        break;

    case "add-app":
        ?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>asvab-mobile-apps?action=add-app' enctype="multipart/form-data">
            <input type="hidden" name="app_id" value="<?php echo $_GET['app_id'] ?>">

            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                <tr>
                    <td align="left" width="400px">App Name</td>
                    <td><input type="text" name="app_name" class='form-control' value="<?php echo $db_value['app_name']; ?>" required></td>
                </tr>
                
                <tr>
                    <td align="left" width="400px">App Description</td>
                    <td><input type="text" name="app_description" class='form-control' value="<?php echo $db_value['app_description']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">App Store URL</td>
                    <td><input type="text" name="app_store_url" class='form-control' value="<?php echo $db_value['app_store_url']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Google Play Store URL</td>
                    <td><input type="text" name="google_play_url" class='form-control' value="<?php echo $db_value['google_play_url']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Logo Picture</td>
                    <td><input class="form-control" name="logo_img" type="file" accept=".png, .jpg, .jpeg"></td>
                </tr>

                <tr>
                    <td align="left">Order</td>
                    <td><input type="number" name="ord" class='form-control' value="<?php echo $db_value['ord']; ?>"></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' name='submit' value='Save' class='btn btn-success'>
                    </td>
                </tr>
            </table>
        </form>
    <?
        break;
}
?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-AMS_HTML' async></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<style>
    .link {
        font-size: 13px;
        /* Increase font size */
        font-weight: bold;
    }
</style>