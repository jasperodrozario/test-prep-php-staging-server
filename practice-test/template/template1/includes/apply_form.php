<div class='clearfix'></div>
<div class="free-classform">
                   <h3 class="title"><span>Enroll now for free live demo class</span></h3>
                   <div class="rgform">
                   <div id='contact_response'></div>
                  <form method="post" id="contactForm"> 
     
                         <div class="form-field">
                            <span><input type="text" id="name" placeholder="Your Name *" required></span>
                         </div>
                         <div class="form-field">
                            <span><input type="email" id="email" name="email" placeholder="Email *" required></span>
                         </div>
                         <div class="form-field">
                            <span><input type="text" name="phone" id="phone" placeholder="Mobile *" required></span>
                         </div>
                         <div class="form-field">
                        <span>
                        <select class="form-control" id="course_id" name="course_id">
                            <option>Select program</option>
                            <?
       
                            $data=getQuery("course","course_id,name","featured='Y'","course_id desc","limit 10");
 							for($i=0;$i<count($data);$i++)
          					{
          						echo"<option value='" .$data[$i]['course_id'] ."'>".$data[$i]['name']."</option>";
          					}
          					?>
                             
                        </select>
                        </span>
                         </div>
                         <!--<div class="input-group registration-date-time">
		                <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
		                <input class="form-control" name="registration_date" id="registration-date" type="date">
		             
		              	</div>-->
		                <div class="form-field">
		                    <button class="get-itnow" type='submit' name='submit' >Get It Now</button> 
		                </div>
		                      </form>
		       </div>
		                </div>
 <div class='clearfix'></div>