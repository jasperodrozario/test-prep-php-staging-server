<?php
#1 Get Field Name By Id========================================
	# getFldValueById($table,$onefieldnamed="cat_id=" . $cat_id);
#2 Get Total Number of records as condition=================== 
	#getTotalRow($tabled="")
#3 Get multiple Fields Name By Id========================================
	#getFldsNameById($table,$fieldsd)
#4 Delete Record
	#deleteData($tabled); 
#5 Insert Table
	#insertTable($table,$post,$files=array(),$htmlentity=array()) 
#6 Update Tabele
	#updateTable($table,$postdition,$files=array(),$htmlentity=array())
#7 Listing table data
	#getQuery("course","course_id,thumb_pic,name,cost,cat_id,duration"d="",$order_by="",$limit="");
#8  get Data by Where
	#getDataByWhere($table,$fields="*"condition="")	
/*	
	Array
(
    [0] => Array
        (
            [id] => 1
            [firstname] => Firstname one
            [lastname] => Lastname one
        )

    [1] => Array
        (
            [id] => 2
            [firstname] => Firstname two
            [lastname] => Lastname two
        )

    [2] => Array
        (
            [id] => 3
            [firstname] => Firstname three
            [lastname] => Lastname three
        )
)	
*/	
$operator=array("like"," in ","[or]","<",">",">=","<=");
function chkFld($fld)
{
	global $operator;
	$flag=0;
	for($i=0;$i<count($operator);$i++)
	{
		if(substr_count($fld,$operator[$i]))
		{
			
			$flag++;
		}
	}
	 
	return $flag;	
	 
	
}	


function getQueryByJoin($table,$fields="*",$cond=array(),$relation=array(),$order_by="",$limit="",$print=0,$group_by="")
{
	global $configs;
	$cols_array=array();$data=array();
	$like="";
	$condition1=array();
	$condition2=array();

	$tables="";

	for($i=0;$i<count($table);$i++)
    {
       
		$tables.=$configs['prev'].$table[$i] .",";

    }
     

    $tables=substr($tables,0,-1);


	if(is_array($cond))
	{
		 
   	 	foreach($cond as $c=>$v)
   	 	{	
   	 		if(chkFld($c))
            {
					$condition1[]=str_replace("[or]","",$c).$v;

            }
            else
            {
                	$condition1[]=$c . "=\"".$v."\"";
            }	

        }
   	 	 
        
        $condition3=array();
        $k=0;
        foreach($relation as $rel=>$r)
        {
			$condition3[]=$rel."=".$r;
			$k++;
        }	
        $condition2=array_merge($condition1,$condition3); 
        $condition=" where  " .str_replace("[or]"," ",implode(" and ",$condition2)); 
    }
    elseif($cond!="")
    {
	 
		$condition=" where  " .$cond;
	}
	if($group_by)
	{
	 
	   	$group_by=" group by " .$group_by;
		 
	}
	if($order_by)
	{
	 
	   	$order_by=" order by " .$order_by;
		 
	}
	if($limit)
	{
	   	$limit=" limit " .$limit;
	}
	$rr=mysqli_query($configs['con'],"select " .$fields ." from " . $tables. " " .$condition . $group_by. $order_by . " ".$limit);
    
    $error=mysqli_errno($configs['con']);
    
    if($print || $error>0)
    {
    	echo "select " .$fields ." from " . $tables. " " .$condition . $group_by .$order_by . " ".$limit;
    }	
	/*
   	$total=mysqli_num_rows($rr);

    $cols_array=array();
    $data=array();
    
    
    $i=0;
    $ret = array();
 
    if($total)
    {
    	while ($row = mysqli_fetch_assoc($rr)) 
    	{
        	foreach ($row as $key => $value) 
        	{
            	$ret[$i][$key] = $value;
            }
        	$i++;
        }
     
    }	
	*/ 
	 return $rr;
}

function getDataByJoin($table,$fields="*",$cond=array(),$relation=array(),$print=0)
{
	global $configs;
	$condition="";


	$tables="";

	
 
	for($i=0;$i<count($table);$i++)
    {
       
		$tables.=$configs['prev'].$table[$i] .",";
		
		
    }
     

    $tables=substr($tables,0,-1);

	$condition1=array();
	if(is_array($cond))
	{
		$keys=array_keys($cond);
   	 	foreach($cond as $c=>$v)
   	 	{	
   	 		if(chkFld($c))
            {
					$condition1[]=str_replace("[or]","",$c).$v;

            }
            else
            {
                	$condition1[]=$c . "=\"".$v."\"";
            }	

        }
	    $condition3=array();
        $k=0;
        foreach($relation as $rel=>$r)
        {
			$condition3[]=$rel."=".$r;
			$k++;
        }	
        $condition2=array_merge($condition1,$condition3); 
	
	    $condition=" where  " .str_replace("[or]"," ",implode(" and ",$condition2));   
    }
    elseif($cond!="")
    {
		$condition=" where  " .$cond;
	}	
	$r2=mysqli_query($configs['con'],"select " . $fields ." from " . $tables . $condition);
    
    $error=mysqli_errno($configs['con']);
    
    if($print || $error>0){ echo "select " . $fields ." from " .$tables .$condition;}
	$d=@mysqli_fetch_array($r2);
	 
	return $d;
}



function getRowsByJoin($table,$fields="*",$cond=array(),$relation=array(),$order_by="",$limit="",$print=0,$group_by="")
{
	global $configs;
	$cols_array=array();$data=array();
	$like="";
	$condition1=array();
	$condition2=array();

	$tables="";


 
	for($i=0;$i<count($table);$i++)
    {
       
		$tables.=$configs['prev'].$table[$i] .",";
		
		
    }
     

    $tables=substr($tables,0,-1);


	if(is_array($cond))
	{
		$keys=array_keys($cond);
   	 	foreach($cond as $c=>$v)
   	 	{	
   	 		if(chkFld($c))
            {
					$condition1[]=str_replace("[or]","",$c).$v;

            }
            else
            {
                	$condition1[]=$c . "=\"".$v."\"";
            }	

        }
        
        $condition3=array();
        $k=0;
        foreach($relation as $rel=>$r)
        {
			$condition3[]=$rel."=".$r;
			$k++;
        }	
        $condition2=array_merge($condition1,$condition3); 

	    $condition=" where  " .str_replace("[or]"," ",implode(" and ",$condition2));      
    }
    elseif($cond!="")
    {
	 
		$condition=" where  " .$cond;
	}
	if($group_by)
	{
	 
	   	$group_by=" group by " .$group_by;
		 
	}
	if($order_by)
	{
	 
	   	$order_by=" order by " .$order_by;
		 
	}
	if($limit)
	{
	   	$limit=" limit " .$limit;
	}
	$rr=mysqli_query($configs['con'],"select " .$fields ." from " . $tables. " " .$condition . $group_by. $order_by . " ".$limit);
    
    $error=mysqli_errno($configs['con']);
    
    if($print || $error>0)
    {
    	echo "select " .$fields ." from " . $tables. " " .$condition . $group_by. $order_by . " ".$limit;
    }	
	
   	$total=@mysqli_num_rows($rr);;

    $cols_array=array();
    $data=array();
    
    
    $i=0;
    $ret = array();
 
    if($total)
    {
    	while ($row = mysqli_fetch_assoc($rr)) 
    	{
        	foreach ($row as $key => $value) 
        	{
            	$ret[$i][$key] = $value;
            }
        	$i++;
        }
     
    }	
	 
	 return $ret;
}

 function getTotalRowsByJoin($table,$cond=array(),$relation=array(),$print=0,$group_by="")
{
	global $configs;
	$cols_array=array();$data=array();
	$like="";
	$condition1=array();
	$condition2=array();
    $condition3=array();
	$tables="";
 
	for($i=0;$i<count($table);$i++)
    {
       
		$tables.=$configs['prev'].$table[$i] .",";
		
		
    }
     

    $tables=substr($tables,0,-1);
 
	if(is_array($cond))
	{
		$keys=array_keys($cond);
   	 	foreach($cond as $c=>$v)
   	 	{	
   	 		if(chkFld($c))
            {
					$condition1[]=str_replace("[or]","",$c).$v;

            }
            else
            {
                	$condition1[]=$c . "=\"".$v."\"";
            }	

        }
     
        foreach($relation as $rel=>$r)
        {
			$condition3[]=$rel."=".$r;
			$k++;
        }	
        
        $condition2=array_merge($condition1,$condition3);

	    $condition=" where  " .str_replace("[or]"," ",implode(" and ",$condition2));  
	   

    }
    elseif($cond!="")
    {
	 
		$condition=" where  " .$cond;
	}
	
	if($group_by)
	{
	 
	   	$group_by=" group by " .$group_by;
		 
	}
	 
	$rr=mysqli_query($configs['con'],"select * from " . $tables. " " .$condition." ".$group_by);
    
    $error=mysqli_errno($configs['con']);
    
    if($print || $error>0)
    {
    	echo "select * from " . $tables. " " .$condition ." ".$group_by;
    }	
	
   	$total=mysqli_num_rows($rr);
    
    	 
	return $total;
}

function getDataByWhere($table,$fields="*",$cond=array(),$print=0)
{
	global $configs;
	$condition="";
	 
	$condition1=array();
	if(is_array($cond))
	{
		 
   	 	foreach($cond as $c=>$v)
   	 	{	
   	 		if(chkFld($c))
            {
					$condition1[]=str_replace("[or]","",$c).$v;

            }
            else
            {
                	$condition1[]=$c . "=\"".$v."\"";
            }	

        }
	    
	    $condition=" where  " .str_replace("[or]"," ",implode(" and ",$condition1));  
    }
    elseif($cond!="")
    {
		$condition=" where  " .$cond;
	}	
	$r2=mysqli_query($configs['con'],"select " . $fields ." from " . $configs['prev'].$table .$condition);
    
    $error=mysqli_errno($configs['con']);
    
    if($print || $error>0){ echo "select " . $fields ." from " . $configs['prev'].$table .$condition;}
	$d=@mysqli_fetch_array($r2);
	 
	return $d;
}

#deleteData($tabled); 
function deleteData($table,$condition=array(),$print=0)
{
	global $configs;
	
	$condition1=array();$where="";
	if(is_array($condition))
	{
		foreach($condition as $c=>$v)
   	 	{	
   	 		if(chkFld($c))
            {
					$condition1[]=str_replace("[or]","",$c).$v;

            }
            else
            {
                	$condition1[]=$c . "=\"".$v."\"";
            }	

        }
		$where=" where  " .str_replace("and or "," or ",implode(" and ",$condition1));   
	}
	elseif($condition!="")
	{
		$where=" where  " .$condition;
	}
	
	$rr=mysqli_query($configs['con'],"delete from " . $configs['prev'].$table. $where);
    if($print)
    {
		echo"delete from " . $configs['prev'].$table. $where;
	}
	if($rr)
	{
		return true;
	}	
}

#update into table
function updateTable($table,$post=array(),$condition=array(),$files=array(),$print=0)
{
	global $configs;

    $keys=array_keys($post);
    $query="";
     
    $cols_array=array();
    $sql = "SHOW COLUMNS FROM ".$configs['prev'].$table;
    $result = mysqli_query($configs['con'],$sql);
	while($row = mysqli_fetch_array($result))
	{
		$cols_array[]=$row['Field'];
	}
    
	foreach($post as $p=>$v)
	{
		if(strlen($v)>60 && $p !='explanation')
		{
			$v=htmlentities($v);
		}
		if($p=='explanation')
        {
            $v=str_replace('"',"'",$v);
        }    
		if(in_array($p,$cols_array)) 
		{
			 
		
			$query.=$p . "=\"".$v . "\",";
			
		} 
	}

    
	$condition1=array();$where="";
	if(is_array($condition))
	{
		 
		foreach($condition as $c=>$v)
   	 	{	
   	 		if(chkFld($c))
            {
					$condition1[]=str_replace("[or]","",$c).$v;

            }
            else
            {
                	$condition1[]=$c . "=\"".$v."\"";
            }	

        }
		$where=" where  " .str_replace("and or "," or ",implode(" and ",$condition1));   
	}
	elseif($condition!="")
	{
		$where=" where  " .$condition;
	}
	
	
	if(count($files))
	{
		 
    	for($i=0;$i<count($files);$i++)
    	{		
    		if($_FILES[$files[$i]]['name'])
			{
	  			 
	  			$ext = end(explode(".",$_FILES[$files[$i]]['name']));
 
	  			if (!file_exists($configs['apath']."/uploads/" . $table)) 
	  			{
					mkdir($configs['apath']."/uploads/" . $table, 0777, true);
				} 
					
				$image="uploads/".$table."/".time() . "_" .$i .".". $ext;
	    		
	    		move_uploaded_file($_FILES[$files[$i]]['tmp_name'],$configs['apath'] .$image);

	    		$query.=$files[$i]."=\"" . $image . "\",";	
    	 	
    		}
	    }
	}
	$query=substr($query,0,-1);
    $rr=mysqli_query($configs['con'],"update " . $configs['prev'].$table. " set " . $query . $where);
    
    $error=mysqli_errno($configs['con']);
    
    if($print || $error>0)  
    echo "update " . $configs['prev'].$table. " set " . $query . $where;

	return true;
	
}
#insert into table
function insertTable($table,$post=array(),$files=array(),$print=0)
{
	global $configs;
    $query="";
    $cols_array=array();
    $sql = "SHOW COLUMNS FROM ".$configs['prev'].$table;
    $result = mysqli_query($configs['con'],$sql);
	while($row = mysqli_fetch_array($result))
	{
		$cols_array[]=$row['Field'];
	}
    
	foreach($post as $p=>$v)
	{
		if(in_array($p,$cols_array)) 
		{
			if(strlen($v)>60)
			{
				$v=htmlentities($v);
			}
			$query.=$p . "=\"".$v . "\",";
			
			
		}
		
		 
	}
 
    #uupload files
 	if(count($files))
    {
 
    	for($i=0;$i<count($files);$i++)
    	{		
    		if($_FILES[$files[$i]]['name'])
			{
	  			$ext = end(explode(".",$_FILES[$files[$i]]['name']));
 
	  			if (!file_exists($configs['apath']."/uploads/" . $table)) 
	  			{
					mkdir($configs['apath']."/uploads/" . $table, 0777, true);
				} 
					
				$image="uploads/".$table."/".time() . "_" . $i.".". $ext;
	    		
	    		move_uploaded_file($_FILES[$files[$i]]['tmp_name'],$configs['apath'] .$image);

	    		$query.=$files[$i]."=\"" . $image . "\",";	
    	 	
    		}
	    }
	
    }

    $query=substr($query,0,-1);
	$rr=mysqli_query($configs['con'],"insert into " . $configs['prev'].$table. " set " . $query);
    
     
    $error=mysqli_errno($configs['con']);
    
    if($print || $error>0)echo  "insert into " . $configs['prev'].$table. " set " . $query;
	if($rr)
	{
		$id=mysqli_insert_id($configs['con']);

		return $id;
	}	
}

function getTotalRow($table,$cond="",$print=0,$group_by="")
{
	global $configs;
	$like="";
	$condition1=array();
	$condition2=array();
    
	if(is_array($cond) && count($cond))
	{
		
   	 	foreach($cond as $c=>$v)
   	 	{	
   	 		if(chkFld($c))
            {
					$condition1[]=str_replace("[or]","",$c).$v;

            }
            else
            {
                	$condition1[]=$c . "=\"".$v."\"";
            }	

        }
      	
	    $condition=" where  " .str_replace("and or  "," or ",implode(" and ",$condition1));      
    }
    elseif(!is_array($cond) && $cond!="")
    {
		echo $cond;
		$condition=" where  " .$cond;
	}
	
	if($group_by)
	{
		$group_by="group by ".$group_by;
	}
	
	$rr=mysqli_query($configs['con'],"select * from " . $configs['prev'].$table. $condition .$group_by);
    
    $error=mysqli_errno($configs['con']);
    if($print || $error>0){echo "select * from " . $configs['prev'].$table. $condition. $group_by;}
    $total=@mysqli_num_rows($rr);
	 
	return $total;
}
 
function getQuery($table,$fields="*",$cond=array(),$order_by="",$limit="",$print=0,$group_by="")
{
	global $configs;
	$cols_array=array();$data=array();
	$like="";
	$condition1=array();
	$condition2=array();
	if(is_array($cond))
	{
		
   	 	if(count($cond))
   	 	{
   	 		foreach($cond as $c=>$v)
   	 		{	
	   	 		if(chkFld($c))
	            {
						$condition1[]=str_replace("[or]","",$c).$v;

	            }
	            else
	            {
	                	$condition1[]=$c . "=\"".$v."\"";
	            }	

        	}

	        	
		    $condition=" where  " .str_replace("and or  "," or ",implode(" and ",$condition1));  
		}       
    }
    elseif($cond!="")
    {
	 
		$condition=" where " .$cond;
	}
	
	if($order_by)
	{
	 
	   	$order_by=" order by " .$order_by;
		 
	}
	if($group_by)
	{
	 
	   	$group_by=" group by " .$group_by;
		 
	}
	if($limit)
	{
	   	$limit=" limit " .$limit;
	}
	$rr=mysqli_query($configs['con'],"select " .$fields ." from " . $configs['prev'].$table. " " .$condition .  $group_by. $order_by . " ".$limit);
    $error=mysqli_errno($configs['con']);
    if($print ||  $error>0)
    {
    	echo "select " .$fields ." from " . $configs['prev'].$table. " " .$condition . $group_by. $order_by . " ".$limit;
    }	
	
   	$total=mysqli_num_rows($rr);
    /*
    $cols_array=array();
    $data=array();
    if(!$fields){$fields="*";}
    if($fields=="*")
    {
    	$sql = "SHOW COLUMNS FROM ".$configs['prev'].$table;
    	$result = mysqli_query($configs['con'],$sql);
		while($row = mysqli_fetch_array($result))
		{
		    $cols_array[]=$row['Field'];
		}
    }
    else
    {
       $cols_array=explode(",",$fields);

    }	
   
    if($total)
    {
    	$k=0;
    	while($d=mysqli_fetch_array($rr))
    	{
            for($i=0;$i<count($cols_array);$i++)
            {
                 
                	$data[$k][$cols_array[$i]]=$d[$cols_array[$i]];
         
            } 
         $k++;  
    	}	

    }	
	*/ 
	return $rr;
}
 
 
function getList($table,$fields_arrray=array(),$action="",$cond=array(),$order_by="",$limit="",$print=0)
{
	global $configs;
	$fields="";$condition="";
	$label=array();
	for($i=0;$i<count($fields_arrray);$i++)
	{
		if(is_array($fields_arrray[$i]))
		{
			$e=$fields_arrray[$i];
			$fields.=$e[2] .",";
			$label[$i]=ucwords(str_replace("_"," ",$e[1]));
		}
		else
		{
			$fields.=$fields_arrray[$i] .",";
			$label[$i]=ucwords(str_replace("_"," ",$fields_arrray[$i]));
		}
	}
	$fields=substr($fields,0,-1);
	
	$cols_array=array();$data=array();
	
	$condition="";
	if(!$limit)
	{
		 $limit=20;
	}
	if(!$_REQUEST['limit'])
	{
		$_REQUEST['limit']=1;
	}
	if($order_by)
	{
		$order_by=" order by ".$order_by;
	}
	$limit_st=" limit ".(($_REQUEST['limit']-1)*20)."," . $limit;
	$param="";
	if(is_array($cond)):

		foreach($cond as $c=>$v)
   	 	{	
   	 		if(chkFld($c))
            {
				$condition1[]=str_replace("[or]","",$c).$v;
            }
            else
            {
                $condition1[]=$c . "=\"".$v."\"";
            }	

        }
        if(count($condition1))
        {
        	$condition2[]="(".implode(" or ",$condition1) .")";
        }	
	    $condition=" where  " .implode(" and ",$condition2);      
	  
	    $total=getTotalRow($table,$cond);
	    $param=implode("&",$condition2);
	else:
	    $total=getTotalRow($table);
	endif;
 
	$list='<table  width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4"  >
	<thead><tr >';
	for($l=0;$l<count($label);$l++)
	{
		$txt="";
		if($label[$l]=="Duration"){$txt="(Hrs.)";}
		$list.='<td   align="left"><b>'.$label[$l]. $txt.'</b></td>'; 
	}
	$list.='<td width="30%" align="center"><b>Action</b></td></tr></thead><tbody>';
	
	if(!$total)
	{
		$list.='<tr><td colspan=' . (count($fields_arrray)+1) .'>No Record Found</td></tr>';
	}
	else
	{
		$rr=mysqli_query($configs['con'],"select " .$fields ." from " . $configs['prev'].$table. $condition . $order_by . $limit_st);
        if($print){echo "select " .$fields ." from " . $configs['prev'].$table. $condition . $order_by . $limit_st;}
    	$k=0;
    	while($d=mysqli_fetch_array($rr))
    	{
     
            $list.="<tr>";
            for($i=0;$i<count($fields_arrray);$i++)
            {
                if(!$i)
                {
				   $id=$d[$fields_arrray[$i]];
				} 
               # $data[$fields_arrray[$i]][$k]=$d[$fields_arrray[$i]];
               		
                if(is_array($fields_arrray[$i]))
                {
					$e=$fields_arrray[$i];
		
					$name=getFldValueById($e[0],$e[1],$e[2]."=" . $d[$e[2]]);
				 
					$list.="<td >!".$name . "</td>";
				}
				elseif(substr_count($fields_arrray[$i],"_pic")||substr_count($fields_arrray[$i],"_img"))
                {
                	$list.="<td ><img src='image.php?image=" . $configs['vpath'].$d[$fields_arrray[$i]] . "&width=100&cropratio=1:1'></td>";
                }
                else
                {
					$list.="<td >".$d[$fields_arrray[$i]] . "</td>";
				}
				
            } 
            if($action)
            {
				$list.="<td align='center'>" . str_replace("{id}",$id,$action) ."</td>";

			}
            $list.="</tr>";   
         
         $k++;  
    	}	
 	
	} 
	$list.="</table>";
	$list.="<div class='clearfix'></div>";
	if($total>$limit)
	{
		$list.="<div class='pull-right'>".paging($total,$limit,$param)."</div>";
	}
	
	return $list;
}

function makeForm($fields=array(),$formtag=0)
{
	global $configs;
	$form="<div class='clearfix'><br></div>";
	if($formtag) 
	{	
	    $form.="<form class='form form-horizontal' method='post' enctype='multipart/form-data'>";
    }
	$j=0;
 
	foreach($fields as $fld)
	{
        $star=" ";
	    if(!$fld['label']) 
	    {
			$fld['label']=ucwords(str_replace("_"," ",$fld['name']));
		}
	    if($fld['required'])
	    {
	    	$fld['required']=" Required";
	    	$star=" *";
	    }

	 	if($fld['type']=="hidden")
        {
			
			$form.="<input type='hidden' id='" . $fld['name'] ."' name='" . $fld['name'] ."'  value='" .$fld['value'] ."'>"; 
		}
		elseif($fld['type']=="text")
		{
		    $form.="<div class='row form-group'><div class='col-md-3'>" . $fld['label']."</div>";
			$form.="<div class='col-md-9'><input type='text' class='form-control' id='" . $fld['name'] ."' name='" .$fld['name'] ."' value='" . $fld['value'] . "' placeholder='Enter ".Ucwords($fld['label']). $star."' " . $fld['required'] ."></div></div>";
		}
		elseif($fld['type']=="number")
		{
		    $form.="<div class='row form-group'><div class='col-md-3'>" . $fld['label']."</div>";
			$form.="<div class='col-md-9'><input type='number' class='form-control' id='" . $fld['name'] ."' name='" .$fld['name'] ."' value='" . $fld['value'] . "' placeholder='Enter ".Ucwords($fld['label']). $star."' " . $fld['required'] ."></div></div>";
		}
		elseif($fld['type']=="textarea")
		{
		    $form.="<div class='row form-group'><div class='col-md-3'>" .$fld['label']."</div>";
			$form.="<div class='col-md-9'><textarea class='form-control description' style='width:100%;height:100px' id='" . $fld['name'] ."' name='" . $fld['name'] . "' placeholder='Enter ".Ucwords($fld['label']). $star."' " . $fld['required'] .">" . html_entity_decode($fld['value']) ."</textarea></div></div>";
		}
	    elseif($fld['type']=="select")
		{
		    $form.="<div class='row form-group'><div class='col-md-3'> ".$fld['label']."</div>";
			$form.="<div class='col-md-9'><select class='form-control' size=1 id='" . $fld['name'] ."' name='" . $fld['name'] . "' " . $fld['required'] .">";
			$form.="<option value=''> Select ".str_replace("_"," ",$fld['name']).$star."</option>";
			$options=$fld['option'];
			 
			$i=0;
			foreach($options as $option=>$opt)
			{
				if($fld['value']==$option)
				{
					$form.="<option value='" .$option . "' selected >" .$opt ."</option>";
		
				}
				else
				{
					$form.="<option value='" .$option . "' >" .$opt ."</option>";
		
				}
				$i++;
			}

			$form.="</select></div></div>";
		}
		elseif($fld['type']=="checkbox")
		{
		   
		    $form.="<div class='row form-group'><div class='col-md-3'> " . Ucwords($fld['label']).$star."</div>";
			$form.="<div class='col-md-9'><div class=row>";
 			$options=$fld['option'];
			 

			$i=0;
			if(is_array($fld['value']))
			{
				$array=$fld['value'];
			}
			else
			{
				$array=explode(",",$fld['value']);
			}
			
			foreach($options as $option=>$opt)
			{
				$form.="<div class='col-md-4'>";
				if(in_array($option,$array))
				{
					$form.="<input type='checkbox' name='" .$fld['name'] ."' value='" .$option . "' checked > " .$opt ."<br>";
				}
				else
				{
					$form.="<input type='checkbox' name='" .$fld['name'] ."' value='" .$option . "' > " .$opt ."<br>";
				}
				$form.="</div>";
				$i++;
			}
			$form.="</div></div></div>";
		}
		elseif($fld['type']=="radio")
		{
		    $form.="<div class='row form-group'><div class='col-md-3'>" . $fld['label'].$star."</div>";
			$form.="<div class='col-md-9'>";
 
			$options=$fld['option'];
			
			$i=0;
			foreach($options as $option=>$opt)
			{
				if($fld['value']==$option)
				{
					$form.="<input type='radio'   name='".$fld['name'] ."' value='" .$option . "' ". $fld['required'] ." checked > " .$opt ."&nbsp;";
		
				}
				else
				{
					$form.="<input type='radio'   name='".$fld['name'] ."' value='" .$option . "' ". $fld['required'] ." > " .$opt ."&nbsp;";
		
				}
				$i++;
			}
			$form.="</div></div>";
		}
		elseif($fld['type']=="file")
		{
		    $form.="<div class='row form-group'><div class='col-md-3'>" . Ucwords($fld['name'])  .$star."</div>";
			$form.="<div class='col-md-9'>";
            $form.="<input type='file' accept='" . $fld['accept'] ."'  id='" . $fld['name'] ."' name='".$fld['name'] ."' ". $fld['required'] .">";
			if($fld['value'])
			{   
				if(substr_count($fld['value'],".pdf"))
				{
					$form.="<br><a href='"  .$configs['vpath'] .$fld['value'] ."'>".$fld['value']."</a>";
				}
				else
				{
					$form.="<br><img src='" . $configs['vpath']."image.php?src=" .$configs['vpath'] .$fld['value'] ."&w=100'>";
				}
				
			} 
			$form.="</div></div>";
		}
		elseif($fld['type']=="date")
		{
		    $form.="<div class='row form-group'><div class='col-md-3'>" . Ucwords(str_replace("_"," ",$fld['name']))  .$star."</div>";
			$form.="<div class='col-md-9'>";
            $form.="<input type='text'  id='date' value='".$fld['value']."' name='".$fld['name'] ."' ". $fld['required'] .">";
			 
			$form.="</div></div>";
		}
		elseif($fld['type']=="submit" || $fld['type']=="button")
		{
			$form.="<div class='row form-group'><div class='col-md-3'></div>";
			$form.="<div class='col-md-9'>";
    		$form.="<input type='".$fld['type']."' class='btn btn-success' id='".$fld['name']."' name='".$fld['name']."' value='".$fld['value']."'> <input type='reset' class='btn btn-danger'  value='Reset'>";
			//if(substr_count($_SERVER['PHP_SELF'],"admin")) 
			//{
			//	$form.="&nbsp;<input type='button' class='btn btn-danger'  value='Cancel' onclick='javascript:history.back();'>";
			//}
			$form.="</div></div>";
		}
		$j++;
	}
	
    if($formtag)
    {
		$form.="</form>";
	}
		
	return $form;
}
 
function breadcrum($links)
{

	global $configs;
 
	$linktext="<a href='index.php'>Home</a> > ";
	 
	foreach($links as $name=>$link)
    {
     	if($link):
     		$linktext.="<a href='".  $link . "'>" . $name . "</a> >";
     	else:
     		$linktext.=ucwords($name);
     	endif;
      
	}

return $linktext;
}
 

 
# Get Field Name By Id========================================
function getFldValueById($table,$field,$cond=array(),$print=0)
{
	global $configs;
	$condition="";$condition1=array();
	if(is_array($cond))
	{
		foreach($cond as $c=>$v)
   	 	{	
   	 		if(chkFld($c))
            {
				$condition1[]=$c . $v ;
            }
            else
            {
                $condition1[]=$c . "=\"".$v."\"";
            }	

        }
	    $condition=implode(" and ",$condition1);      
    }
    elseif($cond!="")
    {
	 
		$condition=$cond;
	}
	 
	 
	$d=@mysqli_fetch_array(mysqli_query($configs['con'],"select " .$field ." from ".$configs['prev']. $table." where " . $condition));;
    if($print)
    	echo "select " .$field ." from ".$configs['prev']. $table." where " . $condition;
  	return $d[$field];	
}

 function getMenu($location)
{
	global $configs;
	$row=getQuery("contents","*",array($location=>1,"parent_id"=>0,"status"=>"Y"),"ord");
	
	if(strpos($_SERVER['SCRIPT_NAME'],"index.php"))
	{  
	   $text='<li class="active"><a href="'.$configs['vpath'].'">Home</a></li>';
    }
    else
    {
		$text='<li ><a href="'.$configs['vpath'].'">Home</a></li>';
    
	}                      
	if($location=="location_header")
	{
       
		foreach($row as $menu) 
        {   $class="";
			 
			$submenu_tot=0;
			$submenu_tot=getTotalRow("contents",array("parent_id"=>$menu['id']));
            
            if(!$submenu_tot)
            {
			 
				$parent="";
				if($menu['id']==17)
                {

					$text.="<li ><a class='dropdown-toggle' data-toggle='dropdown' href='".$configs['vpath'].$parent.$menu['seo_url']."'>" .$menu['title'] ."<b class='caret'></b></a>";
					$text.=getTestDropDown();


                }
                else
                {
					if(!file_exists($configs['apath']."application/".$menu['seo_url'] .".php"))
					{
						$parent="info/";
					}
						
					if($menu['seo_url']==$_GET['seo_url'] || strpos($_SERVER['SCRIPT_NAME'],$menu['seo_url']))
					{
						$text.="<li class='active'><a href='" .$configs['vpath'] . $parent .$menu['seo_url'] ."'>" .$menu['title'] ."</a></li>";
	            	
					}
					else
					{
						$text.="<li ><a href='" .$configs['vpath'] . $parent .$menu['seo_url'] ."'>" .$menu['title'] ."</a></li>";
	            	
					}
					
				}  
			}
			else
            {
				 
			   	$class="class='second-level-menu'";		
			    
				$parent="";
                
                
            	if(!file_exists($configs['apath'].$menu['seo_url'] .".php"))
				{
					$parent=seo_url($menu['seo_url'])."/";
				}	
				$text.="<li ".$class."><a class='dropdown-toggle' data-toggle='dropdown' href='".$configs['vpath'].$parent.$menu['seo_url']."'>" .$menu['title'] ."<b class='caret'></b></a>";
      			$text.="<ul class='dropdown-menu'>";
				$submenu=getQuery("contents","name,seo_url",array("parent_id"=>$menu['id']),"ord");
      			for($s=0;$s<count($submenu);$s++)
      			{
					$text.="<li ><a href='" .$configs['vpath'] .seo_url($menu['title'])."/".$submenu[$s]['seo_url'] ."'>" .$submenu[$s]['title'] ."</a></li>";
            	
				}
                $text.="</ul></li>";
      
                 
				
			}
                            
		}


	}
	else
	{
		$i=0;
		$text.="<ul class='one-half'>";
		$n=count($row);
		foreach($row as $d)
    	{   
    		
    		$parent="";
			if($d['parent_id'])
			{	
				$parent=getFldValueById("contents","seo_url",array("id"=>$d['parent_id']));
		    }
			
			if(!file_exists($configs['apath'].$d['seo_url'] .".php"))
			{
				if(!$d['parent_id'])
                {
        			$parent=seo_url($d['title']) . "/";
				}
				else
				{
					$parent=$parent . "/";
				}	
                				
			}	
    		
			$text.="<li><a href='" . $configs['vpath'].$parent.$d['seo_url'] . "'>" .ucwords($d['title']) . "</a></li>";

    		$i++;
    		if(round($n/2)==$i)
    		{
    			$text.="</ul><ul class='one-half'>";
    		}	
    	}
    	$text.="</ul>";
	}	
	

    return $text;
}
function getTestDropDown()
{
	global $configs;
    $html='<ul class="second-level-menu">';
  
    $j=0;
    if($_SESSION['test_id'])
    {
    	 $row=getQuery("test","*",array("status"=>"Y","id"=>$_SESSION['test_id']),"ord");
    }
    else
    {
    	 $row=getQuery("test","*",array("status"=>"Y"),"ord");
    }		
   

    foreach($row as $d)
    {
         
         $html.='<li><a href="' .$configs['vpath'] .'exam/' .$d['id'] .'-' .seo_url($d['name']) .'">'. $d['name'].'</a></li>';
  	
     }
         
    $html.='</ul>';
    return $html;
}

function template($args=array()){
	
  global $configs;
   
  $names=array();
  if( is_array( $args['files'] ) ) 
  { 
     $names = array_values( $args['files'] ); 
  }
   

  if(count($configs['includes']))
  {
  	 $names = array_merge($configs['includes']['header'], $names, $configs['includes']['footer']); 
  }

  
  $template_found=array();
 
  foreach ( $names as $name ) 
  {
    	 
    	$file = $configs['apath']."template/".$configs['template'] . "/". $name . '.php';
   
    	if($args['admin'])
    	{
            $file = $configs['apath'].$configs['admin_dir']."/".$configs['template'] . "/". $name . '.php';
    
    	}	
    
    	if (file_exists($file)) 
    	{
      	   if($args['admin'])
      	   {
		   		if(strpos($_SERVER['PHP_SELF'],"index.php"))
		   		{
					$template_found[] =$configs['template']."/".$name . '.php';
				}
				else
				{
					$template_found[] ="../".$configs['template']."/".$name . '.php';
				}
		   		
		   }
		   else
		   {
		   		if(strpos($_SERVER['PHP_SELF'],"index.php"))
		   		{
					$template_found[] ="template/".$configs['template']."/".$name . '.php';

				}
				else
				{
					$template_found[] ="../template/".$configs['template']."/".$name . '.php';
					//$template_found[] ="template/".$configs['template']."/".$name . '.php';
				}
		   			
		   		
		   }
      	   
    	}
    	else
    	{  
    	    return $file. "|".$args['admin'].' not Found';// stop after the first template is found
      		break;
			
		}
  }
 
  // Make values in the associative array easier to access by extracting them
  if ( is_array( $args ) ){
    extract( $args );
  }
  // buffer the output (including the file is "output")
  ob_start();
  extract($configs); 
  foreach ( $template_found as $file_name ) {
   
	include $file_name;

  }  
  return ob_get_clean();
}


function getSelect($table,$fields,$value,$fld) 
{

	$html="<select name='" . $fld ."' class='form-control' size=1>\n<option value=''>Select</option>";
	$list=getQuery($table,$fields);

	$e=explode(",",$fields);
	$id=$e[0];$name=$e[1];
	
	foreach($list as $l)
	{
       if($value==$l[$id])
       {
       	  $html.="<option value='" .$l[$id] ."' selected>" .$l[$name] . "</option>\n";
       }
       else
       {

			$html.="<option value='" .$l[$id] ."'>" .$l[$name] . "</option>\n";
       }

	}
	$html.="</select>";
	return $html;
}
 
$setting=getDataBywhere("setting","*",array("id"=>1));


function mainmenu()
{
	
	global $configs;
	$menu="";
	$row=getQuery("contents","*",array("location_header"=>1,"menu"=>'Y',"parent_id"=>0),"ord");

    foreach($row as $d)
    {
		
	    $mtotal=getTotalRow("contents",array("menu"=>'Y',"parent_id"=>$d['id']));
  		if($d['source']=='url'):
  		    $link=$configs['vpath'] . $d['source_url'];
  		else:
  		    if(strip_tags($d['contents'])!=""):
  		   		$link=$configs['vpath'] . "info/" . $d['seo_url'];
  		   	else:
  		   		$link="";
  		   	endif;	
  		endif; 
  		 
  		if($mtotal):
  			$menu.="<li class=\"dropdown\"><a href='". $configs['vpath'] . "info/" . $d['seo_url'] ."'>" . $d['title'] . "</a>";
      		$menu.="<ul class=\"dropdown-menu dropdown-menu-left\">";
      		$mrow=getTotalRow("contents","*",array("menu"=>'Y',"parent_id"=>$d['id']));
  		
      		foreach($mrow as $d2) 
      		{
			 
      		    $link=$configs['vpath'] . $d['seo_url'] . "/" .$d2['seo_url'];
      		     
    			$menu.="<li><a href='". $link. "'>" . $d2['title'] . "</a></li>";        
      		}
      		$menu.="</ul></li>";
      	elseif($d['id']==1):
      		$menu.="<li class=\"dropdown\"><a href='". $configs['vpath'] . "info/" . $d['seo_url'] ."'>" . $d['title'] . "</a>";
      		$menu.="<ul class=\"dropdown-menu dropdown-menu-left\">";
      		
      		$row2=getQuery("city","*",array("country_id"=>$country_id));
  			foreach($row2 as $d2)
  			{
				$menu.="<li><a href='" . $configs['vpath']."search/" . $d2['city_id'] . "'>" .$d2['city_name'] ."</li>";  
       		
			}
  	     
      	    $menu.="</ul></li>";
      	else:
      	    if((isset($_GET['key']) && substr_count($d["source_url"],$_GET['key']))|| substr_count($_SERVER['REQUEST_URI'],$d["source_url"])):
      	 		$menu.="<li class='active'><a href='". $link ."'>" . $d['title']."</a></li>";
      	 	else:
      	 		$menu.="<li ><a href='". $link ."'>" . $d['title']."</a></li>";
      	 	endif;	
      	endif;	
  	}
  	
  	return $menu;	
}
function submenu($seo_url)
{
	
	global $configs;
	
	$d=getDataByWhere("contents","*",array("menu"=>'Y',"seo_url"=>$seo_url));
    if(count($d)):
    	 
        $row=getQuery("contents","*",array("menu"=>'Y',"parent_id"=>$d['id']));
        $menu='<ul class="left_pannel">';
        foreach($row as $d2)
        {
			$link=$configs['vpath'] . $d['seo_url'] . "/" .$d2['seo_url'];
	      	$menu.="<li><i class=\"fa fa-angle-double-right\" aria-hidden=\"true\"></i> <a href='". $link. "'>" . $d2['title'] . "</a></li>";        
	 
		}
    	
     	$menu.='</ul>';
  	endif;
  	
  	return $menu;	
}
function getName($user_id,$user_type="student") 
{
	
 if(!$user_type){$user_type=getFldValueById("login","user_type",array("user_id"=>$user_id));}
    
  if($user_type=="tutor")
  {
  		$name=getFldValueById("tutor","name",array("user_id"=>$user_id));
    	return $name;
  }
  elseif($user_type=="student")
  {
  		$name=getFldValueById("student","name",array("user_id"=>$user_id));
  	 
    	return $name;
  }
  else
  {
	    $name=getFldValueById("guest","name",array("user_id"=>$user_id));
  	 
    	return $name;
  }
	
}
function getMobile($user_id,$user_type="student") 
{
	
 // $user_type=getFldValueById("login","user_type",array("user_id"=>$user_id));
    
  if($user_type=="tutor")
  {
  		$mobile=getFldValueById("tutor","mobile",array("user_id"=>$user_id));
    	return $mobile;
  }
  elseif($user_type=="student")
  {
  		$name=getFldValueById("student","mobile",array("user_id"=>$user_id));
  	 
    	return $mobile;
  }
  else
  {
	    $name=getFldValueById("guest","mobile",array("user_id"=>$user_id));
  	 
    	return $mobile;
  }
	
}
function footer_simple_menu()
{
	
	global $configs;
	 
    $menu='<div style="padding:10px"><ul>';
	$row=getQuery("contents","*",array("menu"=>'Y',"location_footer"=>'1'), "ord");
    foreach($row as $d)
    {
		$link=$configs['vpath'] . $d['seo_url'];
	   	$menu.="<li><a href='". $link. "' class='active'>" . $d['title'] . "</a></li>";        
	
	}
   
	$menu=substr($menu,0,-2);	
    $menu.='</ul></div>';
  
  	return $menu;	
}

function footer_submenu($seo_url)
{
	
	global $configs;
	if($seo_url=="other"):
	    $menu='<ul style="text-align:left">';
		$row=getQuery("contents",array("menu"=>'Y',"location_footer"=>1),"ord");
        
        foreach($row as $d)
        {
		
	        $link=$configs['vpath'] . $d['seo_url'];
		   	$menu.="<li><a href='". $link. "' >" . $d['title'] . "</a> </li>";        
		}  
		 
	    
	
	else:
	    $d=getDataByWhere("contents",array("menu"=>'Y',"seo_url"=>$seo_url),"ord");
        
		if(count($d)):
	    
	        $row=getQuery("contents","*",array("menu"=>'Y',"parent_id"=>$d['id']),"ord");
	        $menu='';
	        foreach($row as $d2)
	        {
				
			 $link=$configs['vpath'] . $d['seo_url'] . "/" .$d2['seo_url'];
		      	$menu.="<li><a href='". $link. "' class='active'>" . $d2['title'] . "</a> </li>";        
		 
		    }
	     
	  	endif;
	  	$menu.="</ul>";
  	endif;
  	
  	return $menu;	
}

function getDropDown($list,$id,$name,$value="",$required="")
{
	$txt="<select class='form-control' name='".$id."' id='".$id."' ".$required.">
	<option value=''>Select --</option>";
	foreach($list as $c)
	{
		if($c[$id]==$value)
	 	{
			$txt.="<option value='".$c[$id] ."' selected>" .$c[$name] ."</option>";
		}
		else
		{
		 	 $txt.="<option value='".$c[$id] ."'>" .$c[$name] ."</option>";
		} 
		
	}
	$txt.="</select>";
	return $txt;
}
/*
function getPic($user_id)
{
	global $con,$prev,$configs['vpath'],$apath;
	$rr=mysqli_query($con,"select * from " . $prev . "picture where user_id=".$user_id ." limit 1");
    //echo "select * from " . $prev . "picture where user_id=".$user_id ." limit 1";
    
    if(@mysqli_num_rows($rr)):
      	$dd=mysqli_fetch_array($rr);
      	$id=$dd["id"];
	  	$img=$dd["file"];
    else:
      $img="uploads/picture/noimage.jpg";
    endif;
    if(!file_exists($apath .$img)):
    	$img="uploads/picture/noimage.jpg";
    endif;
    return $img;

}
function getPicAll($user_id)
{
	global $con,$prev,$configs['vpath'],$apath;
	$img=array();
	$rr=mysqli_query($con,"select * from " . $prev . "picture where user_id=".$user_id);
   // echo "select * from " . $prev . "picture where user_id=".$user_id;
    while($d=mysqli_fetch_array($rr)):
       $img[]=$d["file"];
    endwhile;
    return $img;

}
function getDropdown($table,$condition)
{
	global $configs;
	$menu="";
	if($condition)
	{
		$condition=" where " . $condition;
	}
	$r=mysqli_query($con,"select * from ".$table. $condition);
    $txt="";
    while($d=mysqli_fetch_array($r)):
  	    $txt.="<option value='" . $d['city_id'] . "'>" . $d['city_name'] ."</option>";  
       
  	endwhile;
  	return $txt;	
}
function getCityList()
{
	global $con,$prev,$configs['vpath'],$country_id;
	$menu="";
	$k=0; 
	$r=mysqli_query($con,"select * from ".$prev. "city where country_id=".$country_id . " limit 8");
    $txt="<ul style='text-align:left'>";
    while($d=mysqli_fetch_array($r)):
    
  	    $txt.="<li ><a href='" . $configs['vpath'] . "/search/" . $d['city_id'] . "'>" . $d['city_name'] ."</a></li> ";  
        $k++;

  	endwhile;
  	$txt.="</ul>";
  	return $txt;	
}
function getCity($city_id)
{
	global $configs;
	
	 
	$d=@mysqli_fetch_array(mysqli_query($con,"select * from ".$prev. "city where city_id=".$city_id));
 
  	return $d['city_name'];	
}
function getTotalRowCity($id)
{
	global $configs;
	
	 
	$n=@mysqli_num_rows(mysqli_query($con,"select * from ".$prev. "city where country_id=".$id));
 
  	return $n;
	
}

function getCountryList($id=0)
{
	global $configs;
	$list="";
	$rr=@mysqli_query($con,"select * from  " . $prev . "country  where status='Y' order by name");
	while($d=mysqli_fetch_array($rr)):
		if($d['id']==$id):
			$list.="<option value='" . $d['id'] . "' selected>" . $d['name'] . "</option>\n";
		else:
			$list.="<option value='" . $d['id'] . "'>" . $d['name'] . "</option>\n";
		endif;		
	endwhile;
	return $list;
}

				
function getCountry($id)
{
	global $configs;
	
	 
	$d=@mysqli_fetch_array(mysqli_query($con,"select name from ".$prev. "country where id=".$id));
    //echo "select name from ".$prev. "country where id=".$id;
  	return $d['name'];	
}
 
function getFlag($id)
{
	global $configs;
	
	 
	$d=@mysqli_fetch_array(mysqli_query($con,"select pic from ".$prev. "country where id=".$id));
 
  	return $d['pic'];	
}
function getCategoryList($cat_id=0)
{
	global $configs;
	$list="";
	$rr=@mysqli_query($con,"select * from  " . $prev . "category where parent_id=0 and status='Y' order by cat_name");
	while($d=mysqli_fetch_array($rr)):
		if($d['cat_id']==$cat_id):
			$list.="<option value='" . $d['cat_id'] . "' selected>" . $d['cat_name'] . "</option>\n";
		else:
			$list.="<option value='" . $d['cat_id'] . "'>" . $d['cat_name'] . "</option>\n";
		endif;		
	endwhile;
	return $list;
}
 


function  getUserData($user_id,$table)
{

	global $configs;
	$d=mysqli_fetch_array(mysqli_query($con,"select ".$table.".*,".$prev."login.*  from ".$table.",".$prev."login where ".$table.".user_id=".$prev."login.user_id and ".$table.".user_id=" .$user_id));
    
   	return $d;
   
}
function  getUserName($user_id,$table)
{
	global $configs;
	$d=@mysqli_fetch_array(mysqli_query($con,"select ".$table.".*,".$prev."login.*  from ".$table.",".$prev."login where ".$table.".user_id=".$prev."login.user_id and ".$table.".user_id=" .$user_id));
   // echo "select ".$table."*,".$prev."login.*  from ".$table.",".$prev."login where ".$table.".user_id=".$prev."login.user_id and ".$table.".user_id=" .$user_id;

	return $d['fname'] . " " . $d['lname'];
}

function  getValue($user_id,$table,$field)
{
	global $configs;
	$d=@mysqli_fetch_array(mysqli_query($con,"select ".$table.".*,".$prev."login.*  from ".$table.",".$prev."login where ".$table.".user_id=".$prev."login.user_id and ".$table.".user_id=" .$user_id));
   // echo "select ".$table."*,".$prev."login.*  from ".$table.",".$prev."login where ".$table.".user_id=".$prev."login.user_id and ".$table.".user_id=" .$user_id;

	return $d[$field];
}
function  getFeedback($user_id)
{
	global $configs;
	$r=mysqli_query($con,"select * from ".$prev."feedback where status='Y' and escort_id=" . $user_id);
	
	$txt="";
	if(@mysqli_num_rows($r)):
		while($d=mysqli_fetch_array($r)):
		   $txt.="<div class='col-md-12'>" . $d['feedback'] . "<br>~~<b><i>" . ucwords($d['feedback_by']) . "</i></b> said on ". showdate($d['added_date']). "</div>";	
		endwhile;
	endif;	
	return $txt;
}
*/
?>