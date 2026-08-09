<form method="post" action="<?=basename($_SERVER["SCRIPT_FILENAME"],'.php');?>">
			<table  width="100%" class="table table-borderd striped" border="0" align="center" cellspacing="1" cellpadding="4">
			<thead style='background: #f0eef0'>
			<tr><th valign=top align="left"><b>Admin Menu Name</b></th>
			<th valign=top align="left"><b>Sub Admin menu</b></th>
			<th valign=top align="center" align="left"><b>Order</b></th>
			<th valign=top style='text-align:center'><b>Status</b></th>
			<th valign=top style='text-align:center'><b>Action</b></th></tr>
			</thead>
			<tbody>
			<?
			$j=0;
			$tids=array();
			if($_REQUEST['tid']):
			  $tids=explode("|",$_REQUEST['tid']);
			endif;
			

			foreach($db_value as $d):
			   
				echo"<tr ><td align='left' class='vertical-middle'>";
							if(in_array($d['id'],$tids)):
								echo"<input type='button'  onclick=\"javascript:window.location.href='" . basename($_SERVER["SCRIPT_FILENAME"],'.php'). "?tid=". str_replace("|".$d["id"]."|","|",$tid) .  "'\" value='-'>";
							else:
								$q=getQuery("adminmenu","*",array("parent_id"=>$d["id"]));
								$n=@mysqli_num_rows($q);
								
								if(count($q)>0)
								{
									echo"<input type='button'  onclick=\"javascript:window.location.href='" . basename($_SERVER["SCRIPT_FILENAME"],'.php'). "?tid=". $tid . "|" . $d["id"] . "|'\" value='+'>"; 
								}
								else
								{
									echo"<input type='button'  onclick=\"javascript:window.location.href='" . basename($_SERVER["SCRIPT_FILENAME"],'.php'). "?tid=". str_replace("|".$d["id"]."|","|",$tid) .  "'\" value='-'>";						
								}
							endif;
							echo" <a  href='adminmenu.entryform?tid=" . $_REQUEST['tid'] . "&id=" . $d["id"] . "'><b>" . $d['name'] . "</b></a>
						</td>
						<td align=center>&nbsp;</td>
						<td align=center class='vertical-middle'>" . $d['ord'] . "</td><td align=center class='vertical-middle'>" . $d['status'] . "</td>
						<td align=center class='vertical-middle'>
							<a   href='adminmenu.entryform?tid=" . $_REQUEST['tid'] . "&id=" . $d["id"] . "'><u>Edit</u>
							</a> |  
							<a  href='adminmenu.entryform?tid=" . $_REQUEST['tid'] . "&parent_id=" . $d["id"] . "' ><u>Add Sub</u>
							</a> | 
							<a   href=\"javascript:if(confirm('You are deleting `" . $d['name'] . "`?')){window.location='" . basename($_SERVER["SCRIPT_FILENAME"],'.php'). "?tid=" . $_REQUEST['tid'] . "&del_id=" . $d["id"] . "';}\""; 
								//echo" href='#'";
								echo"><u>Delete</u>
							</a>
						</td>
					</tr>";
			    	if(in_array($d["id"],$tids)):
						$row=getQuery("adminmenu","*",array("parent_id"=>$d["id"]),"ord");
						$k=0;
						foreach($row as $dd):
							if(!($k%2)){$class="odd";}else{$class="even";} 
							echo"<tr bgcolor=#ffffff class=odd>
									<td class='vertical-middle'>&nbsp;|______________</td>
									<td align='left' class='vertical-middle'>
										<a  href='adminmenu.entryform?tid=" . $_REQUEST['tid'] . "&id=" . $dd["id"] . "&parent_id=" . $dd[parent_id] . "'"; 
											//echo "href='#'";
											echo">" . $dd['name'] . "
										</a>
									</td>
									<td align=center class='vertical-middle'>" . $dd['ord'] . "</td>
									<td align=center class='vertical-middle'>" . $dd['status'] . "</td>
									<td align=center class='vertical-middle'> 
										<a  href='adminmenu.entryform?tid=" . $_REQUEST['tid'] . "&id=" . $dd["id"]. "&parent_id=" . $dd['parent_id'] . "'"; 
										
											echo"><u>Edit</u>
										</a> | 
										
										<a href=\"javascript:if(confirm('You are deleting `" . $dd['name'] . "`?')){window.location='" . basename($_SERVER["SCRIPT_FILENAME"],'.php'). "?tid=" . $_REQUEST['tid'] . "&del_id=" . $dd["id"] . "';}\"";
									
											echo"><u>Delete</u>
										</a>
									</td>
								</tr>";
							$row3=getQuery("adminmenu","*",array("parent_id"=>$dd["id"]),"ord");
							$k=0;
							foreach($row3 as $d3):
								echo"<tr bgcolor=#ffffff class=odd>
										<td class='vertical-middle'>&nbsp;</td>
										<td class='vertical-middle'>&nbsp;|______________<td >
											<a  href='adminmenu.entryform?tid=" . $_REQUEST['tid'] . "&id=" . $d3["id"] . "&parent_id=" . $dd["id"] . "'"; 
												//echo" href='#'";
												echo">" . $d3['name'] . "
											</a>
										</td>
										<td align=center class='vertical-middle'> 
											<a  href='adminmenu.entryform?tid=" . $_REQUEST['tid'] . "&id=" . $d3["id"] . "'"; 
												//echo"href='#'";
												echo"><u>Edit</u>
											</a> | 
											<a href='add.entryform?tid=" . $_REQUEST['tid'] . "&id=" . $d3["id"] . "'"; 
												//echo" href='#'";
												echo"><u>Add Ads</u>
											</a> | 
											<a  href=\"javascript:if(confirm('You are deleting `" . $d3['name'] . "`?')){window.location='" . basename($_SERVER["SCRIPT_FILENAME"],'.php'). "?tid=" . $_REQUEST['tid'] . "&id=" . $d3["id"] . "&del=1';}\"";
												//echo"href='#'";
												echo"><u>Delete</u>
											</a>
										</td>
									</tr>";
							endforeach;		
							$k++;
						endforeach;
					endif;	
					$j++;
				endforeach;   
				?>
				</tbody>
			</table>