<?
include($configs['admin_template_include']."includes/tmpl_breadcrumb.php"); 
         
	switch($action)
	{
		case "menu-allot": 
	  ?>

	  <form class="form-horizontal" id="form" accept-charset="UTF-8" method="post">
        <input name="table" type="hidden" value="grant">
        <input name="user_id" type="hidden" value="<?=$_GET['user_id'];?>">
        
		<div class="row form-group">
		    <div class="col-md-6">
			<table class='table table-bordered'>
			<thead><th>Parent Menu</th><th>Sub Menu</th></thead></tr>
			<?
			foreach($db_list as $d)
			{
			   
			   if(in_array($d['id'],$prog_id))
			   {
			   	   echo"<tr><td><input type='checkbox' name='prog_id[]' value='" . $d['id'] ."' checked> " . $d['name'] . "</td><td>-</td></tr>";
			   }	
			   else
			   {
			   	  echo"<tr><td><input type='checkbox' name='prog_id[]' value='" . $d['id'] ."'> " . $d['name'] . "</td><td>-</td></tr>";
			   }	
			   
			   
			    foreach($submenu[$d['id']] as $s)
			    {
			       
			        if(in_array($s['id'],$proghead_id))
			        {
			       		echo"<tr><td>|____________</td><td><input type='checkbox' name='proghead_id[]' value='" . $s['id'] ."' checked> " . $s['name'] . "</td></tr>";
				    }
				    else
				    {
				        echo"<tr><td>|____________</td><td><input type='checkbox' name='proghead_id[]' value='" . $s['id'] ."'> " . $s['name'] . "</td></tr>";
				
				    }	
			    }	
			   
			}		
			?>	
			</table>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6"><input class="btn btn-success update-schedule" type="submit" value="Save" ></div>
        </div>
	    
							 
		</form>

	  <?
	   
	break;

	case"list":
	case"delete":
	?> 
 
 	    <table  class="table table-bordered striped">
		<thead >
		<tr><th valign=top align="left"><b>Admin Menu Name</b></th>
		<th valign=top align="left"><b>Sub Admin menu</b></th>
		<th valign=top align="left"><b>Icon</b></th>
		<th valign=top align="center" align="left"><b>Order</b></th>
		<th valign=top style='text-align:center'><b>Status</b></th>
		<th valign=top style='text-align:center'><b>Action</b></th></tr>
		</thead>
		<tbody>
		<?
		 
		$j=0;
		
		foreach($db_list as $d):
		   
			echo"<tr ><td align='left' class='vertical-middle'>";
						if($d['id']==$_GET['id']):
							echo"<input type='button'  onclick=\"javascript:window.location.href='" . $configs['admin_vpath'] ."menu/list'\" value='-'>";
						else:
							$q=getQuery("adminmenu","*",array("parent_id"=>$d["id"]));
							
							if(count($q)>0)
							{
								echo"<input type='button'  onclick=\"javascript:window.location.href='" . $configs['admin_vpath'] ."menu/list/". $d["id"] . "'\" value='+'>"; 
							}
							else
							{
								echo"<input type='button'  onclick=\"javascript:window.location.href='" . $configs['admin_vpath'] ."menu/list'\" value='-'>";						
							}
						endif;
						echo" <a  href='add/" . $d["id"] . "'>" . $d['name'] . "</a>
					</td>
					<td align=center>&nbsp;</td>
					<td align=center><i class='fa ".$d['icon'] ." fa-lg' aria-hidden='true' ></i></td>
					<td align=center class='vertical-middle'>" . $d['ord'] . "</td><td align=center class='vertical-middle'>" . $d['status'] . "</td>
					<td align=center class='vertical-middle'>
						<a   href='" . $configs['admin_vpath'] ."menu/edit/" . $d["id"] . "'>Edit
						</a> |  
						<a  href='" . $configs['admin_vpath'] ."menu/add-sub/" . $d["id"] . "' >Add Sub
						</a> | 
						<a   href=\"javascript:if(confirm('You are deleting `" . $d['name'] . "`?')){window.location='" . $configs['admin_vpath'] ."menu/delete/" . $d["id"] . "';}\""; 
							//echo" href='#'";
							echo">Delete
						</a>
					</td>
				</tr>";
		    	if($d["id"]==$_GET['parent_id']):
					$row=getQuery("adminmenu","*",array("parent_id"=>$d["id"]),"ord");
					$k=0;
					foreach($row as $dd):
						if(!($k%2)){$class="odd";}else{$class="even";} 
						echo"<tr bgcolor=#ffffff class=odd>
								<td class='vertical-middle'>&nbsp;|______________</td>
								<td align='left' class='vertical-middle'>
									<a  href='" . $configs['admin_vpath'] ."menu/edit/" . $dd["id"] . "'>" . $dd['name'] . "</a>
								</td>
								<td align=center class='vertical-middle'>-</td>
								<td align=center class='vertical-middle'>" . $dd['ord'] . "</td>
								<td align=center class='vertical-middle'>" . $dd['status'] . "</td>
								<td align=center class='vertical-middle'><a  href='" . $configs['admin_vpath'] ."menu/edit/" . $dd["id"] . "'>Edit </a> | 
								<a href=\"javascript:if(confirm('You are deleting " . $dd['name'] . "?')){window.location='" . $configs['admin_vpath'] ."menu/delete/" . $dd["id"] . "';}\">Delete</a></td>
							</tr>";
						
						    $k++;
					endforeach;
				endif;	
				$j++;
			endforeach;   
			?>
			</tbody>
		</table>
    <?
	break;
	case "add":
	case "edit": 
	case "add-sub": 
	   ?>
	   <div class="table-responsive" style='overflow-x: hidden'>
     	<form method="post"  id='form' enctype="multipart/form-data">
		<input type="hidden" name="parent_id" value='<?=$_GET['parent_id']?>'>
		<input type="hidden" name="id" value="<?=$_GET['id']?>">
		<input type="hidden" name="action" value="menu">
 
		<table width="100%" border="0" align="center" cellpadding=3 cellspacing=1 class="table table-bordered">

		<tr class="vertical-middle" ><td valign=top align="left" width=25%>Name</td><td align="left"><input type="text"  name="name" value="<?=$db_value['name']?>" class="form-control"></td></tr>
		<tr class="vertical-middle" ><td valign=top align="left">Url</td><td align="left"><input type="text"  name="url" value="<?=$db_value['url']?>" class="form-control"></td></tr>
		<tr class="vertical-middle" ><td valign=top align="left">Icon</td><td align="left"><input type="text"  name="icon" value="<?=$db_value['icon'];?>" class="form-control"></td></tr>
		<tr class="vertical-middle" ><td valign=top align="left">Menu Order</td><td align="left"><input type="text" size=5 name="ord" value="<?=$db_value[ord]?>" class="form-control"> [Example. 2]</td></tr>
		<tr  class="vertical-middle"><td align="left">Status</td><td align="left"><input type=radio name=status value="Y" <?if($db_value["status"]=="Y"){echo" checked";}?> >Online <input type=radio name=status value="N" <?if($db_value["status"]=="N"){echo" checked";}?>> Offline </td></tr>
		<tr><td></td><td><input type="submit" name="submit" value="Update" class='btn bg-green-500 color-white btn-dark padding-left-40 padding-right-40'>&nbsp;&nbsp;<?if($_GET['id']){?><input type="submit" name="DELT" value="Delete" class='btn danger'><?}?></td></tr>
		</table><br>
		</form>
	   <?
	   
	break;
}	
?>
							
			  