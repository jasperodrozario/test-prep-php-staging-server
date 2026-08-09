<?
include_once("configs/license.php");
include_once("configs/common.php");

$content=$_REQUEST['content'];
$data=getDataByWhere("course","*",array("course_id"=>$_REQUEST['course_id']));


switch ($content) 
{
    case "download":
      
        echo"<table class='table table-bordered table-striped'>
            <tr><td>Sr. No</td><td>Name</td><td>Type</td><td>Download</td></tr>";


            $subjects=getQuery("course_subject","*",array("course_id"=> $_REQUEST['course_id']));
            $k=0;
            foreach($subjects as $subject)
            {
                $total=getTotalRow("course_subject_material",array("sub_id"=> $subject['sub_id']));
                $subject_name=getFldValueById("course_subject","subject",array("sub_id"=> $subject['sub_id']));

                echo"<tr bgcolor=#eee><td colspan=4><h5><a name='" .$subject['sub_id']."'></a>Study Mat of <b>" .ucwords($subject_name)."</b></h5></td></tr>";
                if($total)
                {   $k=0;
                    $files=getQuery("course_subject_material","*",array("sub_id"=>$subject['sub_id']));
                    foreach($files as $file)
                    {
                        if($file['paid']){$paid="Yes";}else{$paid="Free";}
                        echo"<tr><td>" . ($k+1)."</td><td>" . $file['name']."</td><td>" . $file['type']."</td><td>";

                        if($file['type']=='video')
                        {
                            echo"<a title='View File' href='play.php?file=" .$file['file'] . "' data-toggle=\"modal\"  data-target=\"#modalFile\" ><img src='img/download.png' width=20></a>";
                        }
                        else
                        {
                            echo"<a title='View File' href='" .$vpath.$file['file'] . "' target=_new><img src='img/download.png' width=20></a>";
                        }    

                        
                        echo"</td></tr>";
                        $k++;
                    }    

                }  
                $k++;  
                 
            }  
            echo"</table>";


        break;
    case "faq":
        $faqs=getQuery("faq","*",array("course_id"=>$_REQUEST['course_id']),"ord");
        echo"<ul>";
        foreach($faqs as $faq)
        {
           echo"<li><b>" . $faq['question'] . "</b><p>" . html_entity_decode($faq['answers'])."</li>";
        }    
        echo"</ul>";
        break;
    case "subjects":
        $subjects=getQuery("course_subject","*",array("course_id"=>$_REQUEST['course_id']),"display_order");
        echo"<table class='table'><tr><td>Sr. No</td><td>Subject</td><td>Duration[Hrs.]</td></tr>";
        foreach($subjects as $subject)
        {
           echo"<tr><td>" . $subject['display_order']."</td><td><b>" . ucwords($subject['subject']) . "</b><p>". html_entity_decode($d['description']) ."</p></td><td>" . $subject['duration']." Hrs.</td></tr>";
        }    
        echo"</table>";
 
        break;
    default:
        echo html_entity_decode($data['details']);
}

?>
 
  <div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:790px;">
        <div class="modal-content" >
           <!--modal body-->
  
        </div>
    </div>
</div>   

<script src="js/jquery-1.12.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
  <link href="<?php echo $vpath;?>css/bootstrap.min.css" rel="stylesheet">


