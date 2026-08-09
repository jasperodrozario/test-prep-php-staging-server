<!-- BEGIN JAVASCRIPTS -->
	
	<!-- BEGIN CORE PLUGINS -->


<script src="<?=$configs['tpath'];?>assets/plugins/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/bootstrap/js/holder.js"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/pace/pace.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/js/core.js" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	
	<!-- flot chart -->
	<script src="<?=$configs['tpath'];?>assets/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/flot/jquery.flot.grow.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
	
	<!-- bootstrap slider -->
	<script src="<?=$configs['tpath'];?>assets/plugins/bootstrap-slider/js/bootstrap-slider.js" type="text/javascript"></script>
	
	<!-- datepicker -->
	<script src="<?=$configs['tpath'];?>assets/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
	
	<!-- vectormap -->
	<script src="<?=$configs['tpath'];?>assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/jquery-jvectormap/jquery-jvectormap-europe-merc-en.js" type="text/javascript"></script>
	
	<!-- counter -->
	<script src="<?=$configs['tpath'];?>assets/plugins/jquery-countTo/jquery.countTo.js" type="text/javascript"></script>
	
	<!-- rickshaw -->
	<script src="<?=$configs['tpath'];?>assets/plugins/rickshaw/vendor/d3.v3.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/rickshaw/rickshaw.min.js" type="text/javascript"></script>
	
	<!-- maniac -->
	<script src="<?=$configs['tpath'];?>assets/js/maniac.js" type="text/javascript"></script>
    <script src="<?=$configs['tpath'];?>assets/js/jquery.nicescroll.min.js" type="text/javascript"></script>
    <script src="<?=$configs['tpath'];?>assets/js/jquery.imgareaselect.js"></script> 
   <script src="<?=$configs['tpath'];?>assets/js/jquery.awesome-cropper.js"></script> 
	<!-- dashboard -->
	<script type="text/javascript">
		maniac.loadchart();
		maniac.loadvectormap();
		maniac.loadbsslider();
		maniac.loadrickshaw();
		maniac.loadcounter();
		maniac.loadprogress();
		maniac.loaddaterangepicker();
	</script> 
    <script>
  $(document).ready(function() {
	//var nice = $("html").niceScroll(); 
    $("#boxscroll").niceScroll({cursorborder:"",cursorcolor:"#00F",boxzoom:true}); // First scrollable DIV
  });
</script>

<!---- Leade Catgory ---->
<!--<script src="<?=$configs['tpath'];?>https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<script>
 $(document).ready(function() {
    $(document).on('submit', '.form1', function() {
      //alert('Update successful');
      return false;
	 
     });
});
</script>

<style>
#boxscroll {
	height: 220px;
	width: 100%;
	overflow: auto;
}
.event-children {
    margin-left: 20px;
    list-style: none;
    display: none;
}
.nicescroll-cursors {
    background: #424242 !important;
}
</style>

 <script>
 $(document).ready(function() {
 $('.events-category').change( function(){

    console.log('showing sub categories');
    var c = this.checked;
 
    if( c ){
       // alert("hii");
        $(this).next('.event-children').css('display', 'block');
    }else{
		//alert("hii2");
        $(this).next('.event-children').css('display', 'none');
    }
});

$('.events-child-category-all').change( function(){
	 //alert("hii5");
    $(this).parent().siblings().find(':checkbox').attr('checked', this.checked);
});
});
</script>
 <!--- End  ---->

 <!-- for modal8 -->



<!-- for modal8 -->



	<!-- END JAVASCRIPTS -->

	<!-- for member select start -->

	<script type="text/javascript"> 

		$(".memberSelect").click(function() {
  var open = $(this).data("isopen");
  if(open) {
    window.location.href = $(this).val()
  }
  //set isopen to opposite so next time when use clicked select box
  //it wont trigger this event
  $(this).data("isopen", !open);
});

	</script>
    

    
    
    
	<!-- for member select end -->

	<!-- for add row dynamik location start  -->

	<script type="text/javascript">
        $(function () {
            //Build an array containing Customer records.
            var customers = new Array();
            
           
 
            //Add the data rows.
            for (var i = 0; i < customers.length; i++) {
                AddRow(customers[i][0], customers[i][1]);
            }
        });
 
        function Add() {
            AddRow($("#txtName").val(), $("#txtCountry").val());
            $("#txtName").val("");
            $("#txtCountry").val("edit");
        };
 
        function AddRow(name, country) {
            //Get the reference of the Table's TBODY element.
            var tBody = $("#tblCustomers > TBODY")[0];
 
            //Add Row.
            row = tBody.insertRow(-1);
 
            //Add Name cell.
            var cell = $(row.insertCell(-1));
            cell.html(name);
 
            //Add Country cell.
            cell = $(row.insertCell(-1));
            cell.html(country);
 
            //Add Button cell.
            cell = $(row.insertCell(-1));
            var btnRemove = $("<input />");
            btnRemove.attr("type", "button");
            btnRemove.attr("onclick", "Remove(this);");
            btnRemove.val("Remove");
            cell.append(btnRemove);
        };
 
        function Remove(button) {
            //Determine the reference of the Row using the Button.
            var row = $(button).closest("TR");
            var name = $("TD", row).eq(0).html();
            if (confirm("Do you want to delete: " + name)) {
 
                //Get the reference of the Table.
                var table = $("#tblCustomers")[0];
 
                //Delete the Table row using it's Index.
                table.deleteRow(row[0].rowIndex);
            }
        };
    </script>

	<!-- for add row end -->
    <!-- for add row dynamik Mini Site start  -->

    <SCRIPT language="javascript">
        function addRow(tableID) {

            var table = document.getElementById(tableID);

            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);

            var colCount = table.rows[0].cells.length;

            for(var i=0; i<colCount; i++) {

                var newcell = row.insertCell(i);

                newcell.innerHTML = table.rows[0].cells[i].innerHTML;
                //alert(newcell.childNodes);
                switch(newcell.childNodes[0].type) {
                    case "text":
                            newcell.childNodes[0].value = "";
                            break;
                    case "checkbox":
                            newcell.childNodes[0].checked = false;
                            break;
                    case "select-one":
                            newcell.childNodes[0].selectedIndex = 0;
                            break;
                }
            }
        }

        function deleteRow(tableID) {
            try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;

            for(var i=0; i<rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if(null != chkbox && true == chkbox.checked) {
                    if(rowCount <= 1) {
                        alert("Cannot delete all the rows.");
                        break;
                    }
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }


            }
            }catch(e) {
                alert(e);
            }
        }

    </SCRIPT>

    <!-- for add row end -->

    <!-- for member information display -->
    <script type="text/javascript">
         function toggleTable()
    {
         var elem=document.getElementById("loginTable");
         var hide = elem.style.display =="none";
         if (hide) {
             elem.style.display="table";
        } 
        else {
           elem.style.display="none";
        }
    }
    </script>
   <script>
    $(document).ready(function() {
     $('.tab-more-btn a').click(function() {
    $('#tab-more-subbtn').slideToggle(400);
     });
	 });
	 $(document).ready(function(){
    $(".more-btn-click").click(function(){
        $(".tab-more-subbtn").slideToggle();
    });
});
   </script>
   <script>
    $(document).ready(function() {
     $('#googlecode').click(function() {
    $('#googlecode-con').show();
	$('#bannerimage-con').hide();
     });
	 });
	 $(document).ready(function(){
   $('#bannerimage').click(function() {
    $('#bannerimage-con').show();
	$('#googlecode-con').hide();
     });
});
   </script>

   <!-- for gobach button -->
   <script>
function goBack() {
    window.history.back();
}
</script>
<script>
$(document).ready(function () {
	$('#sample_input').awesomeCropper(
	{ width: 150, height: 150, debug: true }
	);
});
</script> 
<!-- for go back button -->

</body>
<!-- END BODY -->
</html>