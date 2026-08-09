 <main>
    <div class="page-title-area bg-5 <?php echo empty($_GET['isapp']) ? 'mt-0' : ''; ?> text-center">
        <div class="container">
            <div class="page-title-content">
                <h2><?php echo $course_name;?> Tutoring Services</h2>
            </div>
        </div>
    </div>

    <!-- Single Tutoring Cards section -->

    <div class="card-section">
        <div class="container">
            <?php
            foreach ($tutoring_detail_list as $d) {
            ?>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <div class="section-title" style="padding-top: 5%;">
                            <h3 style="color: orange;"><b><?=$d['title']?></b></h3>
                        </div>
                        <?php echo html_entity_decode($d['description']);?>
                    </div>
                </div>
                
                <div class="row">
                    <?php
                    foreach ($tutoring_package_list[$d['det_id']] as $session) {
                    ?>
                        <div class="col-lg-3 col-md-6 text-center">
                            <div class="single-card">
                                <div class="content">
                                    <h3 class="orange-color"><strong> <?= htmlspecialchars($session['title']); ?> </strong></h3>
                                </div>
                                <div class="icon">
                                    <img src="<?= $configs['vpath'] . "template/template1/assets/img/get-tutor-icon.png"; ?>" alt="image">
                                </div>
                                <div class="content">
                                    <h4 class="orange-color"> Total $<?= htmlspecialchars($session['price']); ?></h4>
                                    <p class="bold" style="color: red;">
                                        <?= !empty($session['discount']) ? htmlspecialchars($session['discount']) : 'No Discount'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>    
            <?
            }
            ?>
        </div>
    </div>
    <? if(!isset($_GET['isapp'])){ ?>
    <div class="container">
            
        <div class="userprofile-body midle-conarea mr-t88">        
            <div class="section-title">
                <h3 style="color: orange;"><b>Request for 1-on-1 or Group Tutoring</b></h3>
            </div>
            
            <div class="col-lg-12 col-md-12">

                <div id='response'></div>

                <div class="form-notice">
                    <p>We will contact you within 24 hours after receiving your response.</p>
                </div>

                <div class="panel dashbord-panel with-nav-tabs panel-default">
                    <div class="panel-heading">&nbsp; Enroll in 1-on-1 or Group Tutoring</div>
                    <div class="panel-body">

                        <form method="post" id="form_tutor" enctype="multipart/form-data">
                            <input type='hidden' name='action' value='get-tutor'>
                            
                            <div class="form-group row">
                                <label for="first_name" class="col-md-3 col-form-label">First Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="First Name" id="first_name" required="" name="first_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="last_name" class="col-md-3 col-form-label">Last Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Last Name" id="last_name" name="last_name">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="email" class="col-md-3 col-form-label">Email</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" placeholder="Enter Valid Email Address" id="email" name="email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-3 col-form-label">Phone(US Only)</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="phone" placeholder="Enter Valid Phone Number" name="phone">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="course_id" class="col-md-3 col-form-label">Course</label>
                                <div class="col-md-9">
                                    <select name="course_id" id="course_id" class="form-control" size=1>
                                        <option value='0'>Please Select A Course</option>
                                        <?
                                        foreach($courses_list as $c)
                                        {
                                            if($_GET['course_id']==$c['course_id']){
                                                echo"<option value='".$c['course_id']."' selected>".$c['course_name']."</option>";
                                            }else{
                                                echo"<option value='".$c['course_id']."'>".$c['course_name']."</option>";
                                            }
                                        }
                                        ?>	
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="tutoring_type" class="col-md-3 col-form-label">Tutoring Type</label>
                                <div class="col-md-9">
                                    <select name="tutoring_type" id="tutoring_type" class="form-control" size=1>
                                        <option value='0'>Please Select A Type</option>
                                        <option value='1-on-1 Tutoring'>1-on-1 Tutoring</option>
                                        <option value='Group Tutoring'>Group Tutoring</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="state_id" class="col-md-3 col-form-label">State</label>
                                <div class="col-md-9">
                                    <select name="state_id" id="state_id" class="form-control">
                                        <option value='0'>Please Select A State</option>
                                        <?
                                        foreach($state_list as $s) {
                                            echo "<option value='".$s['id']."'>".$s['name']."</option>";
                                        }
                                        ?>	
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="message" class="col-md-3 col-form-label">Message</label>
                                <div class="col-md-9">
                                <textarea class="form-control" rows="5" placeholder="Write Your Message" name="message"></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class='col-md-3'></div>
                                <div class='col-md-9 '>
                                    <button type="submit" name="submit" class="btn btn-warning pull-left" style="margin-right:8px; width:200px;">Submit</button>
                                </div>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <? } ?>
</main>