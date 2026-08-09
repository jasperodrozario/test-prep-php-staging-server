<?php 
include 'includes/header.php';
chklogin();
$data=getDataByWhere("login","*",array("user_id"=>$_SESSION['user_id']));
?>   

<!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>MY DASHBOARD</h1></div>
          </div>
</div>  

<!--courses section-->
  <div class="userprofile_wrapper">
      <div class="container">
        
            <div class="userprofile-body">
            <div class="col-lg-3 col-md-12">
            
                 
                <?
                include("includes/left_dashboard.php");
                ?>
               
            </div>
            
            <div class="col-lg-9 col-md-12">
           
        

              <div class="panel dashbord-panel with-nav-tabs panel-default">
                  <div class="panel-heading">Edit Profile Picture</div>
                  <div class="panel-body">
                  <div id='response' style='text-align:center'></div>

                <form   method="post" id="profile" enctype="multipart/form-data" >
                <div class="form-group">
                    
                    <div class='col-md-4'>
                     <?
                     if($data['pic']):
                        echo"<img src='image.php?image=" . $vpath.$data['pic'] . "&width=100&height=100'>";

                     else:
                         $data['pic']="img/avatar.png"; 
                         echo"<img src='image.php?image=" . $vpath.$data['pic'] . "&width=200&height=250'>";
                     endif; 
                     ?>
                    </div>
                    <div class='col-md-8'> 
                       <div class="form-group">
                          <br />
                        </div>

                        <div class="form-group">
                              <input type="file" class="form-control"  placeholder="Upload Pic"   id="pic" required="" name="pic"> 
                        </div>
                    
                          <div class="form-group">
                          <span class="error" style="color:#0E6938"></span><br />
                        </div>
    				
                          <div class="form-group text-center">
                          
                          
                              <button type="submit" name="submit" class="btn btn-default">Update</button>
                               
                          </div>
                      </div>
                 
            </form>
                       
                  </div>
            
             
               </div>
          </div>
            </div>
            </div>
            
            </div>
            
      </div>         
    </div>    
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script> 
<script>

$(document).ready(function(){
    $('#profile').submit(function(e){
  
     e.preventDefault();
        // show that something is loading
        $('#response').html("<b>Loading response...</b>");

        //var file_data = $("#file").prop("files")[0];   // Getting the properties of file from file field
    var form_data = new FormData(this);                  // Creating object of FormData class
    //form_data.append("file", file_data)              // Appending parameter named file with properties of file_field to form_data
    //form_data.append("user_id", 123)   
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         */
        $.ajax({
            type: 'POST',
            url: 'post_photo.php', 
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false, 
            //data: $(this).serialize()
            data:form_data
        })
        .done(function(data){
            // alert(data);
            // show the response
            $('#response').html(data);
            $('#profile').hide();
          //$("#message").html(data);
       
             
        })
        .fail(function() {
         
            // just in case posting your form failed
            alert( "Posting failed." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
    
    
});
</script>
<!--footer section-->
<?php 
include 'includes/footer.php';
?> 