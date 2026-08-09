</div> 
                  <!-- /.box-body -->
                  </div>
                  <!-- /.box -->
                </div>
                    
            </div>
          
    </section>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
     
 
	    
	<div id="myModal" class="modal fade">
        <div class="modal-dialog" >
            <div class="modal-content" style='width:850px!important' >
                <!-- Content will be loaded here from "remote.php" file -->
            </div>
        </div>
    </div>
     <script>
     $(function () {
        $('#modalClose').on('click', function () {
            $('#myModal').hide();
            
        })
    })
    </script> 

<div id="myModalEdit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Content will be loaded here from "remote.php" file -->
        </div>
    </div>
</div>
<footer class="main-footer">
    <!--<div class="pull-right d-none d-sm-inline-block">
      <b>Version</b> Beta
    </div>-->
    
    Copyright &copy; <?=date("Y")?> <a target="_blank" href="<?=$configs['vpath']?>"><?=$configs['dotcom'];?></a>. All Rights Reserved.
  </footer>


</div>
<!-- ./wrapper -->
	<!-- jQuery 3 -->
	<script src="<?=$configs['admin_template_path'];?>assets/vendor_components/jquery/dist/jquery.js"></script>
	
	<!-- popper -->
	<script src="<?=$configs['admin_template_path'];?>assets/vendor_components/popper/dist/popper.min.js"></script>

	<!-- Bootstrap v4.0.0-beta -->
	<script src="<?=$configs['admin_template_path'];?>assets/vendor_components/bootstrap/dist/js/bootstrap.js"></script>

	<!-- FastClick -->
	<script src="<?=$configs['admin_template_path'];?>assets/vendor_components/fastclick/lib/fastclick.js"></script>

	<!-- Sparkline -->
	<script src="<?=$configs['admin_template_path'];?>assets/vendor_components/jquery-sparkline/dist/jquery.sparkline.js"></script>

	<!-- jvectormap  -->
	<script src="<?=$configs['admin_template_path'];?>assets/vendor_plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="<?=$configs['admin_template_path'];?>assets/vendor_plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

	<!-- SlimScroll -->
	<script src="<?=$configs['admin_template_path'];?>assets/vendor_components/jquery-slimscroll/jquery.slimscroll.js"></script>

	<!-- ChartJS -->
	<script src="<?=$configs['admin_template_path'];?>assets/vendor_components/chart-js/chart.js"></script>

	<!-- Maximum_admin App -->
	<script src="<?=$configs['admin_template_path'];?>js/template.js"></script>

	<!-- Maximum_admin dashboard demo (This is only for demo purposes) -->
	<script src="<?=$configs['admin_template_path'];?>js/pages/dashboard2.js"></script>

	<!-- Maximum_admin for demo purposes -->
	<script src="<?=$configs['admin_template_path'];?>js/demo.js"></script>
	<script type="text/JavaScript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

    <script src="<?=$configs['admin_vpath']?>template/js.js"></script>
    
     <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <link rel="stylesheet" href="<?=$configs['admin_vpath'];?>summernote/summernote-lite.css">  
	<script type="text/javascript" src="<?=$configs['admin_vpath'];?>summernote/summernote-lite.js"></script>

	<script>
	  $('.description').summernote({height:275,   
	    
	    toolbar: [
	    // [groupName, [list of button]]
	    ['style', ['bold', 'italic', 'underline']],
	    ['color', ['color']],
	    ['fontsize', ['fontsize']],
	    
	  ['para', ['ul', 'ol', 'paragraph']],

	  ['table', ['table']],
	     ['insert', ['link', 'picture','math', 'hr','codeview']],
	  ],
	 
	  });
	</script> 
	
	<script type="text/javascript">
   

   $(document).ready(function()
{
    $("#cat_id").change(function()
    {
         
        var id=$(this).val();
        //var dataString = 'parent_id='+ id;
        $("#sub_cat_id").find('option').remove();
         
        $.ajax
        ({
        type: "POST",
        url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax-admin",
        data: "cat_id="+id+"&get_subcatgory_option=1"
    
        })
        .done(function(html)
        {
             
            $("#sub_cat_id").html(html);
        }) 
    }); 

});
   
 </script>
 <script type="text/javascript">
		$(document).ready(function()
	{
	    $("#city").change(function()
	    {
	         
	       var id=$(this).val();
	        //var dataString = 'parent_id='+ id;
	        $("#area").find('option').remove();
	         
	        $.ajax
	        ({
	        type: "POST",
	        url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax-admin",
	        data: "city_id="+id+"&get_area_option=1"
	    
	        })
	        .done(function(html)
	        {
	             
	            $("#area_id").html(html);
	        }) 
	    });
	    
	    

	});

	</script>
	
	<script>
  $(document).ready(function() {
    $("input[name$='product_type']").click(function() {
        var test = $(this).val();

        $("div.desc").hide();
        $("#product_type" + test).show();
    });
});
</script>

</body>
</html>
