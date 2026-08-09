<?
include("includes/access.php");
include("includes/header.php");

if($_GET[del]==1):
	$r=mysqli_query($con,"delete from " . $prev . "faq_category where cat_id=". txt_value($_GET['cat_id']));
	//echo "delete from " . $prev . "faq_category where cat_id=". txt_value($_GET['cat_id']);
endif;
if(!$_REQUEST['limit']){$_REQUEST['limit']=1;}


$page_title="Question Category";
$breadcrum=array(
    array("Question Category", "")
   
);
$btn="<input name='button' type='button' class='btn btn-success' onclick=\"javascript:window.location.href='question_category.entryform.php'\" value='Add Category' style='float:right;margin-right:36px' />";

require_once("includes/header_common.php");

?>


<?
if($msg)
{
   echo $msg;	
}
?>	 
 

		
		
				
				
						
			
			<table width="100%" border="0" align="center" cellspacing="1"   cellpadding="4" align=center class="table table-bordered table-striped">
			<thead> 
			<tr ><td><b>Id</b></td><td><b>Category</b></td><td align=center><b>Status</b></td><td align=center><b>Option</b></td></tr>
			</thead>
			<?
			$h=0;
			$r=mysqli_query($con,"select * from " . $prev. "faq_category where parent_id=0");

			//echo "select * from " . $prev. "faq_category where parent_id=0 order by type";
 
			while($d1=mysqli_fetch_array($r)):
			    if($d1['status']=='Y')
			    {
				   //$d1['status']="<img src='assets/images/on.png'>";
				   $d1['status']="Y";		
				}
				else
				{
					//$d1['status']="<img src='assets/images/off.png'>";
					$d1['status']="N";	
				}
				if($d1['type']=='S')
			    {
				   $d1['type']="Servicemen";	
				}
				else
				{
					$d1['type']="Buyer";	
				}
				echo "<tr><td>" . $d1['cat_id'] ."</td>";
				echo"<td><a href=\"javascript://\" onClick=\"javascritp:window.open('faqcategory.entryform.php?cat_id=" . $d1['cat_id'] . "&cat_name=" . urlencode($d1['cat_name']) . "','t','width=350,height=170,top=50,left=400, Scrollbars=yes resizable=yes');\"><b>" .$d1['cat_name'] . "</b></a></td>


				<td align=center>" .$d1['status'] ."</td>

				<td align=center>

				<a  href='question_category.entryform.php?cat_id=" . $d1['cat_id'] . " '><img src='images/edit.png' alt='Delete' width='22' height='24' border='0'></a> | 

				<a href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='" . $_SERVER['PHP_SELF'] . "?cat_id=" . $d1['cat_id'] . "&del=1&limit=".$_REQUEST['limit']."';}\" ><img src='images/delete.png' alt='Delete' width='22' height='24' border='0'></a></td></tr>";
			
			endwhile;
			?>
			 
			</table>




 
<?
include("includes/footer.php");
?>