<div id='<?php echo $_GET['info'] ?>-response'></div>
<form id="<?php echo $_GET['info'] ?>-form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>">
    <input type="hidden" name="action" value="<?php echo $_GET['info'] ?>">

    <?

    switch ($_GET['info']) {
        case "login":
    ?>


            <div class="row form-group">
                <div class="col-lg-3 control-label" for="digits">Email </div>
                <div class="col-lg-9">
                    <input class="form-control" value="<?php if (isset($db_value['email'])) {
                                                            echo $db_value['email'];
                                                        } ?>" type="text" placeholder="Email" />
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-3 control-label" for="digits">Password </div>
                <div class="col-lg-9">
                    <input class="form-control" name="password" type="text" placeholder="Keep it empty if don't want to change the password" />

                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-3 control-label" for="digits">Grant Access</div>
                <div class="col-lg-9">
                    <input class="form-check-input" type="checkbox" id="fp" name="fp" value="1" <?php if (!empty($db_value['fp'])) echo 'checked'; ?>>
                    <label class="form-check-label" for="fp">Final Practice</label>
                    &nbsp;&nbsp;&nbsp;
                    <input class="form-check-input" type="checkbox" id="pt" name="pt" value="1" <?php if (!empty($db_value['pt'])) echo 'checked'; ?>>
                    <label class="form-check-label" for="pt">Prediction Test</label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-3 control-label" for="digits">Status </div>
                <div class="col-lg-9">
                    <select name="status" id="status" class="form-control" size="1">
                        <option value="Y" <?php if (isset($db_value['activation_status']) && $db_value['activation_status'] == 'Y') {
                                                echo 'selected';
                                            } ?>>Active</option>
                        <option value="N" <?php if (isset($db_value['activation_status']) && $db_value['activation_status'] == 'N') {
                                                echo 'selected';
                                            } ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <button type="submit" name="general" class="btn btn-primary"><i class="i-checkmark-circle-2" aria-hidden="true"></i> Save changes</button>
                    <button type="button" onclick="history.back();" class="btn btn-warning"><i class="i-close-3" aria-hidden="true"></i> Cancel</button>

                </div>

            </div><!-- End .form-group  -->
        <?
            break;
        case "info":
        ?>

            <div class="row form-group">
                <div class="col-lg-3 control-label" for="digits">First Name </div>
                <div class="col-lg-9">
                    <input class="form-control" value="<?= $db_value['first_name']; ?>" name="first_name" type="text" placeholder="First Name" />
                </div>
            </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-3 control-label" for="digits">Last Name </div>
                <div class="col-lg-9">
                    <input class="form-control" value="<?= $db_value['last_name']; ?>" name="last_name" type="text" placeholder="Last Name" />
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-3 control-label" for="digits">Mobile</div>
                <div class="col-lg-9">
                    <input class="form-control" value="<?= $db_value['mobile']; ?>" name="mobile" type="text" id="mobile" placeholder="Mobile No" />
                </div>
            </div>
            <!-- 
        <div class="row form-group">
                    <div class='col-md-3'><label>State</label></div>
                    <div class='col-md-9'>
                    <select name='state_id' id='state_id' class="form-control" size=1 required>
                    <option value=''>Select State</option>
                    <?
                    foreach ($state_list as $s) {
                        if ($db_value['state_id'] == $s['id']) {
                            echo "<option value='" . $s['id'] . "' selected>" . $s['name'] . "</option>";
                        } else {
                            echo "<option value='" . $s['id'] . "'>" . $s['name'] . "</option>";
                        }
                    }
                    ?>	
                    </select>
                    </div>
                </div>
                 <div class="row form-group">
                    <div class='col-md-3'><label>City</label></div>
                    <div class='col-md-9'>
                     <select name='city_id' id='city_id' class="form-control" size=1 required>
                    <option value=''>Select City</option>
                    <?
                    foreach ($city_list as $s) {
                        if ($db_value['city_id'] == $s['id']) {
                            echo "<option value='" . $s['id'] . "' selected>" . $s['name'] . "</option>";
                        } else {
                            echo "<option value='" . $s['id'] . "'>" . $s['name'] . "</option>";
                        }
                    }
                    ?>	
                    </select>	
                    	
                    	
                    </div>
                </div>
                 <div class="row form-group">
                    <div class='col-md-3'><label>ZIp Code</label></div>
                    <div class='col-md-9'><input type="text" value="<?= $db_value['zip_code']; ?>" class="form-control" id="zip_code" placeholder="Zip code" name="zip_code" required></div>
                </div>
                -->
            <div class="row form-group">
                <div class='col-md-3'>Select a Course</div>
                <div class='col-md-9'><select name='test_id' class='form-control' required>
                        <option value=''>Select Course Name</option>
                        <?
                        foreach ($test_names as $t) {

                            // Build flag label
                            $flags = [];
                            if ($t['subscribe']) $flags[] = 'Subscribe';
                            if ($t['app'])       $flags[] = 'App';
                            if ($t['tutoring'])  $flags[] = 'Tutoring';

                            $flagLabel = count($flags) ? ' - (' . implode(', ', $flags) . ')' : '';

                            // Course label: Course Name [Count] - (Flags)
                            $label = $t['course_name'] . $flagLabel;

                            // Selected course
                            $selected = ($t['course_id'] == $db_value['course_id']) ? "selected" : "";

                            echo "<option value='" . $t['course_id'] . "' $selected>$label</option>";

                            // if ($t['course_id'] == $db_value['course_id']) {
                            //     echo "<option  value='" . $t['course_id'] . "' required selected>" . $t['course_name']  . "</option>";
                            // } else {
                            //     echo "<option  value='" . $t['course_id'] . "'>" . $t['course_name']  . "</option>";
                            // }
                        }
                        ?>
                    </select></div>
            </div>
            <div class="row form-group">
                <div class='col-md-3'>Subscription Type</div>
                <div class='col-md-9'><select name='paid' class='form-control' required>
                        <option value=''>Select Type</option>
                        <option value='Y' <? if ($db_value['activation_status'] == "Y") {
                                                echo "selected";
                                            } ?>>Paid</option>";
                        <option value='N' <? if ($db_value['activation_status'] == "N") {
                                                echo "selected";
                                            } ?>>Free</option>";
                    </select></div>
            </div>
            <div class="row form-group">
                <div class='col-md-3'>Expire Date</div>
                <div class='col-md-9'><input type="date" value="<?= $db_value['expire_date']; ?>" class="form-control" id="date3" placeholder="Expire Date" name="exp_date" autocomplete="off"></div>
            </div>

            <div class="row form-group">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <button type="submit" name="submit" class="btn btn-primary"><i class="i-checkmark-circle-2" aria-hidden="true"></i> Save changes</button>
                    <button type="button" onclick="history.back();" class="btn btn-warning"><i class="i-close-3" aria-hidden="true"></i> Cancel</button>

                </div>

            </div>
        <?
            break;
        case "reviews":
        case "enquires":
            $db_list;
            break;
        case "applied":

            echo '<br><table class="table table-bordered table-striped">';
            echo "<thead><th>Date</th><th>Subject</th><th>Lesson</th><th>Score</th></thead>";
            echo "<tbody>";
            foreach ($results as $d) {


                echo "<tr><td>" . showdate($d['date']) . "</td><td>" . $d['cat_name'] . "</td><td>" . $d['lesson_id'] . "</td><td>" . $d['score'] . "</td></tr>";
            }
            echo "</tbody></table>";

            break;




        case "notes":
        ?>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#noteshome">Notes</a></li>
                <li><a data-toggle="tab" href="#notesmenu1" class='total'>Notes List (<?= count($notes); ?>)</a></li>

            </ul>
            <div class="tab-content">
                <div id="noteshome" class="tab-pane fade in active">
                    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                        <tr>
                            <td valign="top">Notes</td>
                            <td><textarea cols="50" rows="2" name="notes" class='form-control' Placeholder="Add Notes" required></textarea></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td height="25"><input type="submit" class="btn btn-success" name='submit' value='Submit'></td>
                        </tr>
                    </table>
                </div>
                <div id="notesmenu1" class="tab-pane fade list">
                    <?
                    if (count($notes)) {
                        echo "<table class='table table-bordered table-striped'>";
                        foreach ($notes as $d) {
                            echo "<tr><td width=20%>" . showdate($d['date']) . "</td><td>" . html_entity_decode($d['notes']) . "</td></tr>";
                        }
                        echo "</table>";
                    }

                    ?>
                </div>
            </div>

        <?
            break;
        case "mail":

        ?>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#mailhome">Send Mail To Student </a></li>
                <li><a data-toggle="tab" href="#mailmenu1" class='total'>Outbox (<?= count($mails); ?>)</a></li>

            </ul>

            <div class="tab-content">
                <div id="mailhome" class="tab-pane fade in active">
                    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                        <tr>
                            <td valign="top">Subject</td>
                            <td><input type='text' name="subject" class='form-control' Placeholder="Add Subject" required></td>
                        </tr>

                        <tr>
                            <td valign="top">Message</td>
                            <td><textarea cols="50" rows="6" name="message" class='form-control' Placeholder="Add Message" required></textarea></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td height="25"><input type="submit" class="btn btn-success" name='submit' value='Send'></td>
                        </tr>
                    </table>

                </div>
                <div id="mailmenu1" class="tab-pane fade list">
                    <?
                    if (count($mails)) {
                        echo "<table class='table table-bordered table-striped'>";
                        foreach ($mails as $d) {
                            echo "<tr><td width=20%>" . showdate($d['date']) . "</td><td width=20%>" . $d['subject'] . "</td><td>" . html_entity_decode($d['message']) . "</td></tr>";
                        }
                        echo "</table>";
                    }

                    ?>
                </div>
            </div>
    <?
            break;
    }
    ?>
</form>



<script type="text/javascript">
    function setActiveTab(tab, id, table) {
        $("#home").html("");
        $('a[href="#' + tab + '"]').trigger('click');
        $.ajax({
                type: "POST",
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                data: "id=" + id + "&form=" + table

            })
            .done(function(html) {

                $("#home").html(html);
            });


    }



    $(document).ready(function() {
        $("#country_id").change(function() {

            var id = $(this).val();
            //var dataString = 'parent_id='+ id;
            $("#state_id").find('option').remove();

            $.ajax({
                    type: "POST",
                    url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                    data: "country_id=" + id + "&get_state_option=1"

                })
                .done(function(html) {

                    $("#state_id").html(html);
                })
        });

    });




    $(document).ready(function() {
        $("#state_id").change(function() {

            var id = $(this).val();
            //var dataString = 'parent_id='+ id;
            $("#city_id").find('option').remove();

            $.ajax({
                    type: "POST",
                    url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                    data: "state_id=" + id + "&get_city_option=1"

                })
                .done(function(html) {

                    $("#city_id").html(html);
                })
        });

    });
</script>


<script>
    // AJAX call for autocomplete 
    $(document).ready(function() {

        $("#<?php echo $_GET['info'] ?>-form").submit(function(event) {

            event.preventDefault();

            //var formData = new FormData($('#<?= $info ?>-form')[0]);
            $('#<?php echo $_GET['info'] ?>-response').html("<img src='<?= $configs['vpath']; ?>img/loaderIcon.gif'>");
            $.ajax({

                    type: "POST",
                    dataType: "json",

                    url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                    data: new FormData(this),

                    contentType: false, // The content type used when sending data to the server.
                    cache: false, // To unable request pages to be cached
                    processData: false
                })
                .done(function(data) {



                    $("#<?php echo $_GET['info'] ?>-response").html(data.msg);
                    if (data.list) {
                        $(".list").html(data.list);
                    }
                    if (data.total) {
                        $(".total").html(data.total);
                    }
                    $('html, body').animate({
                        scrollTop: 0
                    }, 1000);

                })
                .fail(function(jqXHR, textStatus, errorThrown) {


                    // just in case posting your form failed
                    alert(textStatus);

                });

            // to prevent refreshing the whole page page
            return false;

        });
        $("#submitok").click(function(event) {

            event.preventDefault();
            var formData = new FormData($('#<?= $info ?>-form')[0]);
            $('#<?= $info ?>-response').html("<img src='<?= $configs['vpath']; ?>img/loaderIcon.gif'>");
            $.ajax({

                    type: "POST",
                    dataType: "json",

                    url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                    data: formData,

                    contentType: false, // The content type used when sending data to the server.
                    cache: false, // To unable request pages to be cached
                    processData: false
                })
                .done(function(data) {

                    // alert(data);
                    $("#<?= $info ?>-response").html(data.msg);
                    if (data.list) {
                        $(".list").html(data.list);
                    }
                    if (data.total) {
                        $(".total").html(data.total);
                    }
                    $('html, body').animate({
                        scrollTop: 0
                    }, 1000);

                })
                .fail(function(jqXHR, textStatus, errorThrown) {


                    // just in case posting your form failed
                    alert(textStatus);

                });

            // to prevent refreshing the whole page page
            return false;

        });
    });


    function deleteImage(x) {

        $('#<?= $info ?>-response').html("<img src='<?= $configs['vpath']; ?>img/loaderIcon.gif'>");
        $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                data: "del_id=" + x + "&user_id=" + <?= (int)$_GET['user_id'] ?> + "&delete_picture=1"

            })
            .done(function(data) {

                $("#<?= $info ?>-response").html(data.msg);
                if (data.list) {
                    $(".list").html(data.list);
                }
                if (data.total) {
                    $(".total").html(data.total);
                }
            })
            .fail(function() {
                // just in case posting your form failed
                alert("Posting failed.");
            });

    }

    function deleteVideo(x) {

        $('#<?= $info ?>-response').html("<img src='<?= $configs['vpath']; ?>img/loaderIcon.gif'>");
        $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                data: "del_id=" + x + "&user_id=" + <?= (int)$_GET['user_id'] ?> + "&delete_video=1"

            })
            .done(function(data) {

                $("#<?= $info ?>-response").html(data.msg);
                if (data.list) {
                    $(".list").html(data.list);
                }
                if (data.total) {
                    $(".total").html(data.total);
                }
            })
            .fail(function() {
                // just in case posting your form failed
                alert("Posting failed.");
            });

    }

    function getDelete(table, auto_id, id) {
        $('#<?= $info ?>-response').html("<img src='<?= $configs['vpath']; ?>img/loaderIcon.gif'>");
        $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                data: "college_id=<?= $_GET['college_id']; ?>&table=" + table + "&auto_id=" + auto_id + "&del_id=" + id

            })
            .done(function(data) {

                $("#<?= $info ?>-response").html(data.msg);
                if (data.list) {
                    $(".list").html(data.list);
                }
                if (data.total) {
                    $(".total").html(data.total);
                }
            })
            .fail(function() {
                // just in case posting your form failed
                alert("Posting failed.");
            });

    }
</script>

<script>
    $(document).ready(function() {
        $(function() {
            $("#dateofbirth").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $("#date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
    });
</script>