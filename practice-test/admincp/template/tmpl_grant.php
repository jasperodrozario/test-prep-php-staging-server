   <form id="myform" method=post action="<?=$_SERVER['PHP_SELF'];?>"  onsubmit="return checkreg(this);">
   <input type='hidden' name='admin_id' value=<?=$_REQUEST['admin_id']?>>
 
    <table border=0 cellpadding=4 cellspacing=1 width=100% align="center" class='table table-bordered table-striped'>
    <?
	$k=0;
    foreach($adminmenu as $ddd):
		echo'<tr ><td colspan="2">&nbsp;<b>' .$ddd['name'] .'</b></td></tr>';
        $data2=getQuery("adminmenu","*",array("parent_id"=>$ddd['id'],"status"=>'Y'));
		foreach($data2 as $nnn):
		   	$chk="";
			if(($_REQUEST['type']=="E" && $nnn['name']=='New Task') ||($_REQUEST[type]=="E" && $nnn[name]=='Note') || ($_REQUEST[type]=="B" && $nnn[name]=='Comment List') || ($_REQUEST[type]=="B" && $nnn[name]=='Buyer Project') || in_array($nnn['id'],$array_id))
			{ 
			    $chk="checked"; 
			}
			?>
            <tr><td align="ledt" colspan="2"><input type='checkbox' name='progid<?=$k?>' value="<?=$nnn['id']?>,<?=$nnn[parent_id]?>" <?=$chk?> > <?=$nnn[name]?></td></tr>
            <?
		    $k++;
		endforeach;
    endforeach;
    ?>    
<tr ><td colspan=2 height=25 align=center ><input type='submit' name="submit" class='btn btn-success'  value='Submit'>
</td></tr>
</table>
<input type=hidden name='tot' value=<?=$k?>>
</form>