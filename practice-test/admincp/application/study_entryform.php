<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Add Lesson Study";
$data['breadcrumb']=array("Study List"=>"study-list", "Add Lesson Study"=>"");

 
if($_POST['SBMT_REG']):

	if (!isset($_POST['chapter']) || trim($_POST['chapter']) === '') {
		$_POST['chapter'] = 'Not set';
	}
	if (!isset($_POST['Topic']) || trim($_POST['Topic']) === '') {
		$_POST['Topic'] = 'Not set';
	}

   	if($_POST['study_id']):
   	    $r=updateTable("study",$_POST,array("study_id"=>$_POST['study_id']));
	    $study_id=$_POST['study_id'];

	else:
	    unset($_POST['study_id']);
	    $study_id=insertTable("study",$_POST);
	endif;
	
    if(!mysqli_errno($configs['con'])):
	 	$data['msg']=$configs['success_msg'];
        header("location:study-list");
	endif;

endif;

if($_REQUEST['study_id'])
{
	$data['db_value']=getDataByWhere("study","*",array("study_id"=>$_REQUEST['study_id']));	
}

$data['subject_list'] = array();
$data['chapter_list'] = array();
$data['topic_list'] = array();
$subjectRows = getQuery("question", "DISTINCT subject", "subject IS NOT NULL AND subject != ''", "subject");
if ($subjectRows) {
	while ($row = mysqli_fetch_assoc($subjectRows)) {
		if ($row['subject'] !== '') {
			$data['subject_list'][] = $row['subject'];
		}
	}
}

$currentSubject = isset($data['db_value']['subject']) ? $data['db_value']['subject'] : '';
$currentChapter = isset($data['db_value']['chapter']) ? $data['db_value']['chapter'] : '';
if ($currentSubject !== '') {
	$subjectSql = mysqli_real_escape_string($configs['con'], $currentSubject);
	$chapterRows = getQuery("question", "DISTINCT chapter", "subject=\"" . $subjectSql . "\" AND chapter IS NOT NULL AND chapter != ''", "chapter");
	if ($chapterRows) {
		while ($row = mysqli_fetch_assoc($chapterRows)) {
			if ($row['chapter'] !== '') {
				$data['chapter_list'][] = $row['chapter'];
			}
		}
	}
	if ($currentChapter !== '') {
		$chapterSql = mysqli_real_escape_string($configs['con'], $currentChapter);
		$topicRows = getQuery("question", "DISTINCT topic", "subject=\"" . $subjectSql . "\" AND chapter=\"" . $chapterSql . "\" AND topic IS NOT NULL AND topic != ''", "topic");
		if ($topicRows) {
			while ($row = mysqli_fetch_assoc($topicRows)) {
				if ($row['topic'] !== '') {
					$data['topic_list'][] = $row['topic'];
				}
			}
		}
	}
} 

echo template($data);
?>

 