<?
include("../common/access.php");
include("../common/autoload.php");
 
$data['page_title']="Lesson Entry";
$data['breadcrumb']=array("Category List"=>"category_list?platfom=".$_REQUEST['platform']."&course_id=".$_REQUEST['course_id'], "Add | Edit Lesson"=>"");
 
if(!$data['menu_open_id'])
{
	$data['menu_open_id']=getFldValueById("adminmenu","parent_id",array("url"=>"category_list"));
}

$data['category']= getQuery("category","*",array("course_id"=>$_REQUEST['course_id']),"category_name");
$data['course']= getQuery("course","*","","course_name");
$data['study_categories'] = array();
$data['study_chapters'] = array();
$data['study_topics'] = array();
$data['filtered_studies'] = array();
$data['study_filter_category'] = '';
$data['study_filter_chapter'] = '';
$data['study_filter_topic'] = '';

if($_REQUEST['page'])
{
	switch($_REQUEST['page'])
	{
		case "homework-list":
		    if($_REQUEST['del_id'])
		    {
				$r=deleteData("questions",array("id"=>$_REQUEST['del_id']));
			    $r=deleteData("answers",array("quesion_id"=>$_REQUEST['del_id']));
                $data['msg']=$configs['delete_msg'];
			}	
		 
	        $data['db_list']= getQuery("questions","*",array("cat_id"=>$_REQUEST['cat_id'],"lesson_id"=>$_REQUEST['lesson_id']),'','',1);
	 
	    break;
	    case "homework":
	        if($_REQUEST['id'])
	        {
				$data['id']=$_REQUEST['id'];
		    	$data['db_value']= getDataByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.id"=>$_REQUEST['id']),array("qa.question_id"=>"q.id"));

		    	$data['answers']=getQuery("answers","*",array("question_id"=>$_REQUEST['id']),"id");
		        $data['total_answers']=getTotalRow("answers",array("question_id"=>$_REQUEST['id']));
		    }
		 
		break;
       	
		case "lesson":
		     $data['db_value']=getDataByWhere("lesson","*",array("lesson_id"=>$_REQUEST['lesson_id'],"cat_id"=>$_REQUEST['cat_id'],"course_id"=>$_REQUEST['course_id']));
            
            $data['added_study']=getQuery("lesson_study","*",array("lesson_id"=>$_REQUEST['lesson_id'],"cat_id"=>$_REQUEST['cat_id'],"course_id"=>$_REQUEST['course_id']));

            $categoryRows = getQuery("study", "DISTINCT subject", "subject IS NOT NULL AND subject != ''", "subject");
            if ($categoryRows) {
                while ($row = mysqli_fetch_assoc($categoryRows)) {
                    if ($row['subject'] !== '') {
                        $data['study_categories'][] = $row['subject'];
                    }
                }
            }

            $lessonCategoryName = (string) getFldValueById("category", "category_name", array("cat_id" => $_REQUEST['cat_id']));
            $lessonCourseName = (string) getFldValueById("course", "course_name", array("course_id" => $_REQUEST['course_id']));
            if (in_array($lessonCategoryName, $data['study_categories'], true)) {
                $data['study_filter_category'] = $lessonCategoryName;
            } elseif (in_array($lessonCourseName, $data['study_categories'], true)) {
                $data['study_filter_category'] = $lessonCourseName;
            }

            if ($data['study_filter_category'] !== '') {
                $categorySql = mysqli_real_escape_string($configs['con'], $data['study_filter_category']);
                $chapterRows = getQuery("study", "DISTINCT chapter", "subject=\"" . $categorySql . "\" AND chapter IS NOT NULL AND chapter != ''", "chapter");
                if ($chapterRows) {
                    while ($row = mysqli_fetch_assoc($chapterRows)) {
                        if ($row['chapter'] !== '') {
                            $data['study_chapters'][] = $row['chapter'];
                        }
                    }
                }

                $lessonTitle = isset($data['db_value']['Lesson_title']) ? trim($data['db_value']['Lesson_title']) : '';
                if ($lessonTitle !== '' && in_array($lessonTitle, $data['study_chapters'], true)) {
                    $data['study_filter_chapter'] = $lessonTitle;
                }

                $studyCond = array("subject" => $data['study_filter_category']);
                if ($data['study_filter_chapter'] !== '') {
                    $chapterSql = mysqli_real_escape_string($configs['con'], $data['study_filter_chapter']);
                    $topicRows = getQuery("study", "DISTINCT Topic", "subject=\"" . $categorySql . "\" AND chapter=\"" . $chapterSql . "\" AND Topic IS NOT NULL AND Topic != ''", "Topic");
                    if ($topicRows) {
                        while ($row = mysqli_fetch_assoc($topicRows)) {
                            if ($row['Topic'] !== '') {
                                $data['study_topics'][] = $row['Topic'];
                            }
                        }
                    }
                    $studyCond["chapter"] = $data['study_filter_chapter'];
                }

                $studyRows = getQuery("study", "study_id, study_title", $studyCond, "study_title");
                if ($studyRows) {
                    while ($row = mysqli_fetch_assoc($studyRows)) {
                        $data['filtered_studies'][] = $row;
                    }
                }
            }
            
		break;
            
       	case "video":	
       	     if($_REQUEST['del_id'])
		     {
			 	deleteData("file",array("id"=>$_REQUEST['del_id']));
			 } 
			 if($_REQUEST['id'])
		     {
			 	deleteData("file",array("id"=>$_REQUEST['del_id']));
			 }    
			 $data['db_value']=getDataByWhere("file","*",array("id"=>$_REQUEST['id']));
	
			 $data['video_list']=getQuery("file","*",array("file_type"=>"v","lesson_id"=>$_REQUEST['lesson_id']),'pos');
			 
		break;
    }
}
  	
$data['platform']=$_REQUEST['platform'];


if(!$_REQUEST['page'])
{
	$_REQUEST['page']="lesson";
}
echo template($data);
?>

 
