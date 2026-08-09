<!--banner section-->
<div class="banner_wrapper">
    <!--carousel section-->
    <div class="inner-banner">
        <image class="img-responsive" src="images/slider-03.jpg" alt="">
            <div class="banner-text">
                <h1>Register</h1>
                <font color=#fff>(Online practice test is ONLY for the paid students)</font>
            </div>
    </div>
</div>

<!--about section-->
<div class="about_wrappers">

    <div class="inner-body">

        <div class="container">
            <div class="register-body" style='min-height: 300px'>
                <?php if ($error) {
                    echo  $error;
                }

                if ($msg) {
                    echo "<h3 class='contact-update new'>" . $msg . "</h3>";
                } else {
                ?>

                    <form method="post" action="<?php echo $configs['vpath']; ?>register">

                        <div class="form-group">
                            <span class="form-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" id="first_name" placeholder="First Name" required="" value="<?= $db_value['first_name']; ?>" name="first_name" required="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <span class="form-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" id="last_name" placeholder="Last Name" name="last_name" value="<?= $db_value['last_name']; ?>" required="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <span class="form-icon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?= $db_value['email']; ?>" required="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <span class="form-icon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" id="mobile" placeholder="Mobile" name="mobile" value="<?= $db_value['mobile']; ?>" required="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <span class="form-icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="pass" id="pass" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <span class="form-icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="confpass" id="confpass" required placeholder="Repeat Password">
                        </div>
                        <div class="form-group">

                            <select name='test_id' class='form-control' required>
                                <option value=''>Select Test Name</option>
                                <?
                                foreach ($test_names as $t) {
                                    echo "<option value='" . $t['id'] . "' required>" . $t['name']  . "</option>";
                                }
                                ?>
                            </select>
                        </div>



                        <div class="form-group text-center">
                            <button type="submit" name="submit" class="btn btn-default">Sign up</button>
                        </div>

                        <div class="form-footer">
                            <div class="form-footer-con">
                                <div class="line-heading">Already a member? </div>
                                <a class="button" id="myImg2">Sign In</a>
                                <!-- <a id="myImg" href="#">Sign In</a> -->



                            </div>
                            <!--
                  <div class="form-group fb-login">
                          <div class="fb-loginbtn">
                            <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a>
                          </div>
                   </div>
                   -->
                        </div>
                    </form>
                <?
                }
                ?>

            </div>
        </div>
    </div>

</div>

<? if (!$_SESSION['user_id']) { ?>
    <!-- The Modal -->

    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content" id="img01">
            <div class="form-bg">

                <div class="col-md-12 padding0">
                    <div class="tab" role="tabpanel">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a id="log" href="#Section1" aria-controls="home" role="tab" data-toggle="tab">sign in</a></li>

                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content tabs">
                            <div role="tabpanel" class="tab-pane fade in active" id="Section1">
                                <div id='response' style='color:red'></div>
                                <form class="form-horizontal" id='loginForm''>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">username</label>
                                    <input type="email" class="form-control" name=' email' id="email" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" name='pass' id="pass" required>
                            </div>
                            <div class="form-group mr20">
                                <div class="main-checkbox">
                                    <input value="None" id="checkbox1" name="check" type="checkbox">
                                    <label for="checkbox1"></label>
                                </div>
                                <span class="text">Keep me Signed in</span>
                                <div class="forgot-pass">
                                    <a id="forgot" href="#Section2" aria-controls="home" role="tab" data-toggle="tab">forgot password</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-default">Sign in</button>
                            </div>


                            <div class="form-group fb-login">
                                <div class="fb-loginbtn">
                                    <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a>
                                </div>
                            </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="Section2">
                            <div id="response_forgot"></div>
                            <form class="form-horizontal" id="forgotForm">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">E-mail</label>
                                    <input class="form-control" id="email1" name='email1' type="email" required>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-default" id='login'>Submit</button>
                                </div>

                            </form>
                        </div>

                    </div>
                <? } ?>
                </div>
            </div>
        </div>

    </div>
    </div>