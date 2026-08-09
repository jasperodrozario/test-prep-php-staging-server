<?
include("../configs/license.php");
include("../configs/common.php");
$ext_array=array("pdf","mp4");

if(isset($_POST)):
    $type_array=array();
    if($_FILES['file']['name'])
    {
        $ext = @end(explode(".",$_FILES['file']['name']));
        if(strtolower($ext)=="pdf")
        {
            $file_type="pdf";
        }   
        else
        {
            $file_type="video";
        } 

        $type_array=array("type"=>$file_type);

        if(!in_array($ext,$ext_array))
        {
            echo "Wrong File!. Please upload only pdf and mp4 file";
        }    
        else
        {

            $_POSTDATA=array("sub_id"=>(int)$_POST['sub_id'],"course_id"=>(int)$_POST['course_id'],"date"=>date("Y-m-d"),"duration"=>(int)$_POST['duration'], "type"=>$file_type,"name"=>$_POST['name'],"name"=>$_POST['name'],"paid"=>(int)$_POST['paid'],"ord"=>(int)$_POST['ord']);
            
            
          
            if($_POST['id'])
            {
                $r=updateTable("course_subject_material",$_POSTDATA,array("file"=>$_FILES['file']),array("id"=>$_POST['id'])); 
                     
            }
            else
            {
                $r=insertTable("course_subject_material",$_POSTDATA,array("file"=>$_FILES['file'])); 

            }    

            if($r)
            {
           
        		echo"<h4>File has been successfully submitted</h4>";
        	}
            else
            {
        		echo"<h4>Error111</h4>";
        	}
        }      
    }
    else
    {
        echo"<h4>No file selected.</h4>";
    }    
endif;
?>