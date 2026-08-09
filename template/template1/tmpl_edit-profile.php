<main>
    <div class="userprofile_wrapper">
        <div class="container">

            <div class="userprofile-body midle-conarea mr-t88">

                <div class="col-lg-12 col-md-12">

                    <div id='response'></div>

                    <div class="panel dashbord-panel with-nav-tabs panel-default">
                        <div class="panel-heading">&nbsp; Edit Profile</div>
                        <div class="panel-body">

                            <form method="post" id="form" enctype="multipart/form-data">
                                <input type='hidden' name='action' value='edit-profile'>

                                <div class="form-group row">
                                    <label for="exp_date" class="col-md-3 col-form-label">Expire Date</label>
                                    <div class="col-md-9" style="display: flex; align-items: center;">
                                        <span><b><?= $db_value['expire_date']; ?></b></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="first_name" class="col-md-3 col-form-label">First Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="First Name" value="<?= $db_value['first_name']; ?>" id="first_name" required="" name="first_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="last_name" class="col-md-3 col-form-label">Last Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Last Name" value="<?= $db_value['last_name']; ?>" id="last_name" name="last_name">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-3 col-form-label">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" value="<?= $db_value['email']; ?>" class="form-control" placeholder="Email" id="email" name="email">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="mobile" class="col-md-3 col-form-label">Mobile</label>
                                    <div class="col-md-9">
                                        <input type="text" value="<?= $db_value['mobile']; ?>" class="form-control" id="mobile" placeholder="Mobile" name="mobile">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="state_id" class="col-md-3 col-form-label">State</label>
                                    <div class="col-md-9">
                                        <select name="state_id" id="state_id" class="form-control" size=1>
                                            <option value='0'>Select State</option>
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

                                <div class="form-group row">
                                    <label for="city_id" class="col-md-3 col-form-label">City</label>
                                    <div class="col-md-9">
                                        <select name='city_id' id='city_id' class='form-control city_id'>
                                            <option value=''>Select City</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="zip_code" class="col-md-3 col-form-label">Zip Code</label>
                                    <div class="col-md-9">
                                        <input type="text" value="<?= $db_value['zip_code']; ?>" class="form-control" id="zip_code" name="zip_code" placeholder="Enter Zip Code">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class='col-md-3'></div>
                                    <div class='col-md-9 '>
                                        <button type="submit" name="submit" class="btn btn-warning pull-left" style="margin-right:8px; width:200px;"> Update</button>

                                        <a class="btn btn-warning pull-left" href="<?= $configs['vpath'] ?>change-password" style="width:200px; margin-top:10px; margin-right:8px;"> Change Password</a>

                                        <a class="btn btn-warning pull-left" href='javascript://' style="width:200px; margin-top:10px; margin-right:8px;" onClick="javascript:if(confirm('Do you want to deactivate the account?')){window.location='edit-profile?deactivate_id=<?= $_SESSION['user_id']; ?>'};"> Deactivate Account</a>

                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

</main>


<style>

    .userprofile_wrapper {
        margin: 20px auto;
    }

    .userprofile-body {
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    select {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
    }

    button.btn {
        margin-top: 10px;
    }

    .error {
        display: block;
        margin-top: 5px;
        color: #ff0000;
    }

    .container {
        width: 100%;
        padding: 0 15px;
        margin: 0 auto;
    }

    .midle-conarea {
        margin: 0 auto;
    }

    .mr-t88 {
        margin-top: 88px;
    }

    .dashbord-panel {
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-top: 20px;
    }

    .dashbord-panel .panel-heading {
        background-color: #f5f5f5;
        padding: 10px;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .dashbord-panel .panel-body {
        padding: 20px;
    }

    .text-center {
        text-align: center;
    }

    .pull-left {
        float: left;
    }

    /* Add more styles as needed */
</style>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>