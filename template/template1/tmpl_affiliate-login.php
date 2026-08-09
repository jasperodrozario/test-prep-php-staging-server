<main>
    <div class="page-title-area bg-2 ">
        <div class="container">
            <div class="page-title-content">
                <h2>Become an Affiliate Member</h2>
                <p class="earn-money">Get <b>20% for each referral</b></p>

            </div>
        </div>
    </div>
    <section class="enroll-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">


                </div>

                <div class="col-lg-6">
                    <div class="enroll-wrap affiliate-member">
                        <div id='loaderIcon'><img src='<?= $configs['vpath'] . "img/Spinner-2.gif"; ?>' style="display: none;text-align:center"></div>

                        <div id='response'></div>

                        <form class="courses-form" method='post' id='form'>

                            <h3>Affiliate Login</h3>

                            <div class="affiliate-member-exit">
                                <input type='hidden' name='action' value='affiliate-login'>

                                <div class="form-group">
                                    <input type="email" class="form-control" id='email' placeholder="Email" name="email" required>
                                </div>

                                <div class="form-group"><input type="password" class="form-control" id='password' name="password" placeholder="Password" required>
                                </div>
                                <div class="row form-group">
                                    <div class='col-md-6'>
                                        <button type="submit" class="default-btn   pull-left">Login</button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="become-an-affiliate" style="color: white; text-decoration:underline; font-size:14px;">
                                            New user? Click here to register
                                        </a>
                                    </div>
                                </div>

                        </form>
                    </div>
                </div>

            </div>
            <div class="col-lg-3">


            </div>
        </div>

    </section>
</main>