<form method="post" action="<?php echo BASENAME($_SERVER['SCRIPT_NAME'],".php");?>">
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover">                                                                     
<thead>
<tr ><th width="7%"><b>ID</b></th>
<th><b>Title</b></th><th><b>Img</b></th>

<th style='text-align:center'><b>Display Order</b></th>
<th style='text-align:center'><b>Status</b></th>
<th align="center" style='text-align:center'><b>Action</b></th></tr>
</thead><tbody>
<?

if(!$total):
	echo"<tr><td class='lnkred' colspan='7' align='center'>No Record Found</td></tr>";
endif;
 
foreach($contents as $d)
{
    echo"<tr ><td >" . $d['id'] . "</td><td ><a href='page.editor?id=" . $d['id'] . "&type=" . $d['type']."'>" . $d['title'] . "</a></td>
    <td >";

    if($d['img']){echo"<img src='" .$configs['vpath'].$d['img'] . "'  width='60'>";}

    echo"</td>";
    echo"<td align=center>" . $d['ord'] . "</td><td align='center'>" . $d['status'] . "</td><td align='center'> <i class='fa fa-pencil-square-o'></i> <a  href='page.editor?id=" . $d['id'] . "&parent_id=" . $d['parent_id'] ."'>Edit</a>  ";
    echo" <i class='fa fa-pencil-square-o'></i> <a  href='page.editor?type=" . $d['type']."&parent_id=" . $d['id'] ."&menu=Y' >Add Sub</a>  ";
       
    echo"<br><i class='fa fa-times'></i> <a   href=\"javascript:if(confirm('Are you sure whish to delete?')){window.location.href='" . BASENAME($_SERVER['SCRIPT_NAME'],".php") . "?del_id=" . $d['id'] . "';}\" >Delete</a></td></tr>\n";

	
	foreach($contents_sub[$d['id']] as $dd)
	{
	    echo"<tr><td >" . $dd['id'] . ".</td><td ><a   href='page.editor?id=" . $dd['id'] . "&parent_id=" . $dd['parent_id']."'>|___" . $dd['title'] . "</a></td><td>";
        if($dd['img']){echo"<img src='" . $configs['vpath'].$dd['img'] . "' width='60'>";}
	     echo"</td><td align=center>" . $dd['ord'] . "</td><td align='center'>" . $status . "</td><td align='center'><i class='fa fa-pencil-square-o'></i> <a href='page.editor?id=" . $dd['id'] . "&parent_id=" . $dd['parent_id'] ."'>Edit</a>   <i class='fa fa-times'></i> <a   href=\"javascript:if(confirm('Are you sure whish to delete?')){window.location.href='" . BASENAME($_SERVER['SCRIPT_NAME'],".php") . "?del_id=" . $dd['id'] . "';}\">Delete</a></td></tr>\n";
	}
	$j++;
}
?>
</tbody>
</table>
<?
if($total>20):
?>
  <?php echo paging($total);?>
<?
endif;
?>
</form>