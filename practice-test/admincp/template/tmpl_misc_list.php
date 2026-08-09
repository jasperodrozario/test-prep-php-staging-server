<form method='post' action='misc_list'>
    <div class='row'>
        <div class='col-md-4'>
            <input type='number' name='id' placeholder='Enter Story Id' class='form-control'>
        </div>
        <div class='col-md-5'>
            <input type='text' name='title'  id='ctitle' class='form-control' value="<?=$db_value['story_title'];?>" placeholder='Enter Title' autocomplete="off" >
            <div id='suggesstion-box'>
            </div>
        </div>
        <div class='col-md-3'>
            <input type='submit'  name='submit' value='Search' class='btn btn-success'>
        </div>
    </div>
</form>

<div class='clearfix'><br></div>
<form method="post" action="misc.editor">
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover">                                                                     
<thead>
<tr ><th width="4%"><b>ID</b></th>
<th><b>Title</b></th>
<th width="25%"><b>Author</b></th>
<th width="15%"><b>Number of questions</b></th>
<th align="center" width="15%" style='text-align:center'><b>Action</b></th></tr>
</thead><tbody>
<?

if(!$total):
	echo"<tr><td class='lnkred' colspan='6' align='center'>No Record Found</td></tr>";
endif;
 
foreach($contents as $d)
{
    $n_questions = getTotalRow("question", array("story_id"=>$d['story_id']));
    
    echo"<tr ><td >" . $d['story_id'] . "</td><td ><a href='view-story?story_id=" . $d['story_id'] . "'>" . $d['story_title'] . "</a></td><td>".$d['author']."</td><td>".$n_questions."</td><td align='center'> <i class='fa fa-pencil-square-o'></i> <a  href='misc.editor?story_id=" . $d['story_id'] . "' >Edit</a> | ";

    echo" <i class='fa fa-times'></i> <a   href=\"javascript:if(confirm('Are you sure whish to delete?')){window.location.href='misc_list?del_id=" . $d['story_id'] . "';}\" >Delete</a></td></tr>\n";
	
	 
}
?>
</tbody>
</table>
<ul class='pagination'>
<?
if($total>20):
?>
  <?php echo paging($total);?>
<?
endif;
?>
</ul>
</form>