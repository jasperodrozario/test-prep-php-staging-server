<?
include("includes/access.php");
include("includes/header.php");
$page_title="Add | Edit Subject";

$breadcrum=array(
    array("Course List", "course.list.php"), 
    array("Add | Edit Subject", "")
    
);
$img=array("png","jpg");

if(isset($_REQUEST['delete'])):
	 deleteData("course_subject",array("sub_id"=>$_REQUEST['delete']); 
endif;

if(isset($_POST["submit"])):

	if($_POST['sub_id']):
        
        $r=updateTable("course_subject",array("course_id"=>(int)$_POST['course_id'],"subject"=>$_POST['subject'],"description"=>htmlentities($_POST['descrition']),"duration"=>(int)$_POST['duration'],"display_order"=>(int)($_POST['display_order'])),array("sub_id"=>$_POST['sub_id']));
 
	else:
		$r=insertTable("course_subject",array("course_id"=>(int)$_POST['course_id'],"subject"=>$_POST['subject'],"description"=>htmlentities($_POST['descrition']),"duration"=>(int)$_POST['duration'],"display_order"=>(int)($_POST['display_order']));
		 
	endif;
	if($r):
	 
	   $msg="<font color=green><img src='images/ok.png' height=20 absmiddle> Subject has been added/updated successfully.</font>";
   
    else:
 
	    $msg="<font color=red><img src='images/error.png'  height=20 absmiddle> Some error occurred, please try again.</font>";
	endif;
endif;

 
include_once("includes/header_common.php");

$data=array();
if($_REQUEST['sub_id'])
{
	$data=getDataByWhere("course_subject","*","sub_id=" . $_REQUEST['sub_id']);
}


$fields=Array
(
     Array
        (
            'name' => 'sub_id',
            'type' => 'hidden',
            'option' => '',
            'value' => $_REQUEST['sub_id'],
            'required' => ''
        ),

    Array
        (
            'name' => 'course_id',
            'type' => 'hidden',
            'option' => '',
            'value' => $_REQUEST['course_id'],
            'required' => ''
        ),

    Array
        (
            'name' => 'subject',
            'type' => 'text',
            'option' => '',
            'value' => $data['subject'],
            'required' => 'required'
        ),
    Array
        (
            'name' => 'description',
            'type' => 'textarea',
            'option' => '',
            'value' => $data['description'],
            'required' => 'required'
        ),
     Array
        (
            'name' => 'duration',
            'type' => 'text',
            'option' => '',
            'value' => $data['duration'],
            'required' => 'required',
            'label'=>'(in Hrs.)'
        ),
    Array
        (
            'name' => 'display_order',
            'type' => 'text',
            'option' => '',
            'value' => $data['display_order'],
            'required' => 'required'
        ) 
  
);	


echo makeForm("course_subject",$fields);

?>

 
<br><br>
<h3>Subject List</h3>

<hr>
<?
$list_array=array("sub_id","subject","duration");

$action="<a href='add.subject.php?sub_id={id}&course_id=" .$_REQUEST['course_id'] ."'>Edit</a> | <a  href=\"javascript:if(confirm('Are you sure you want to delete it?')){window.location='" . $_SERVER['PHP_SELF'] . "?delete_sub_id={id}';}\">Delete</a>";
#print grid list
echo getList("course_subject",$list_array,$action,array("course_id"=> $_REQUEST['course_id']),1);
 
include("includes/footer.php");
?>