<?php
#limit for pagination ========================
 
if(strpos($_SERVER['REQUEST_URI'],"?"))
{
	$e=explode("?",$_SERVER['REQUEST_URI']);
     
    if(substr_count($e[1],"&"))
    {
    	$str=explode("&",$e[1]);
    	
    	for($i=0;$i<count($str);$i++)
    	{
    		$s=explode("=",$str[$i]);
	 
	        $_GET[$s[0]]=$s[1];
    	}
    }
    else
    {
    
		$pos = strpos($e[1],"=");
	    
		$str=substr_replace($e[1], "*", $pos, 1);
	   
		$s=explode("*",$str);
		 
		$_GET[$s[0]]=$s[1];
	}
}
 
 
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
					$condition1[]=$c . $v ;

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
	 
	 return $ret;
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
					$condition1[]=$c .  $v ;

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

function getFldValueByJoin($table,$fld,$cond=array(),$relation=array(),$print=0)
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
					$condition1[]=$c .  $v ;

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
	$r2=mysqli_query($configs['con'],"select " . $fld ." from " . $tables . $condition);
    
    $error=mysqli_errno($configs['con']);
    
    if($print || $error>0){ echo "select " . $fld ." from " .$tables .$condition;}
	$d=@mysqli_fetch_array($r2);
	 
	return $d[$fld];
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
					$condition1[]=$c .  $v ;

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
					$condition1[]=$c .  $v ;

            }
            else
            {
                	$condition1[]=$c . "=\"".$v."\"";
            }	

        }
     
        foreach($relation as $rel=>$r)
        {
			$condition3[]=$rel."=".$r;
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
					$condition1[]=$c .  $v ;

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
					$condition1[]=$c .  $v ;

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

function getDataByWhereOfficersAsvab($table,$fields="*",$cond=array(),$print=0)
{
	global $configs;
	
    $host_tmp="198.211.99.20"; //mysql host	
    $dbuser_tmp="qtadyxettw"; //mysql username	
		
    $dbpass_tmp="sN2gCBzJ4X"; // mysql password	
    $dbname_tmp="qtadyxettw"; //mysql database name
    
    $configs['con_tmp']=mysqli_connect ($host_tmp, $dbuser_tmp, $dbpass_tmp,$dbname_tmp) or die ('I cannot connect to the database because: ' . mysqli_connect_error());
    
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
	$r2=mysqli_query($configs['con_tmp'],"select " . $fields ." from " . $configs['prev'].$table .$condition);
    
    $error=mysqli_errno($configs['con_tmp']);
    
    if($print || $error>0){ echo "select " . $fields ." from " . $configs['prev'].$table .$condition;}
	$d=@mysqli_fetch_array($r2);
    
    $host="198.211.99.20"; //mysql host	
    $dbuser="ctbsrarevb"; //mysql username	
		
    $dbpass="sN2gCBzJ4X"; // mysql password	
    $dbname="ctbsrarevb"; //mysql database name
    
    $configs['con']=mysqli_connect ($host, $dbuser, $dbpass,$dbname) or die ('I cannot connect to the database because: ' . mysqli_connect_error());
    
	return $d;
}


function getDataByWhereFromAnotherDatabase($table,$fields="*",$cond=array(),$print=0)
{
	global $configs;
	
    $host_tmp="198.211.99.20"; //mysql host	
    $dbuser_tmp="qtadyxettw"; //mysql username	
		
    $dbpass_tmp="sN2gCBzJ4X"; // mysql password	
    $dbname_tmp="qtadyxettw"; //mysql database name
    
	//    $host_tmp="198.211.99.20"; 
	//    $dbuser_tmp=$database; 
	//		
	//    $dbpass_tmp=$password; 	
	//    $dbname_tmp=$database; 
    
    $configs['con_tmp']=mysqli_connect ($host_tmp, $dbuser_tmp, $dbpass_tmp,$dbname_tmp) or die ('I cannot connect to the database because: ' . mysqli_connect_error());
    
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
	$r2=mysqli_query($configs['con_tmp'],"select " . $fields ." from " . $configs['prev'].$table .$condition);
    
    $error=mysqli_errno($configs['con_tmp']);
    
    if($print || $error>0){ 
        echo "select " . $fields ." from " . $configs['prev'].$table .$condition;
    }
	
    $d=@mysqli_fetch_array($r2);
    
    $host="198.211.99.20"; //mysql host	
    $dbuser="ctbsrarevb"; //mysql username	
		
    $dbpass="sN2gCBzJ4X"; // mysql password	
    $dbname="ctbsrarevb"; //mysql database name

    $configs['con']=mysqli_connect ($host, $dbuser, $dbpass,$dbname) or die ('I cannot connect to the database because: ' . mysqli_connect_error());
    
	return $d;
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
		if(strlen($v)>200)
		{
			$v=htmlentities($v);
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
					$condition1[]=$c .  $v ;

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
	
	
	if(is_array($files) && count($files))
	{
		 
    	for($i=0;$i<count($files);$i++)
    	{		
    		if($_FILES[$files[$i]]['name'])
			{
	  			 
	  			$ext = pathinfo($_FILES[$files[$i]]['name'], PATHINFO_EXTENSION);
	  		 
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
    if($error>0) {return "Duplicate records!" ."update " . $configs['prev'].$table. " set " . $query . $where;}
    if($print){echo "update " . $configs['prev'].$table. " set " . $query . $where;}
    
    if(!$error)  
    
	return true;
	
}

#insert into table
function insertTable_raw_data($table,$post=array(),$files=array(),$print=0)
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
			if(strlen($v)>200)
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
				 
	  			$ext = pathinfo($_FILES[$files[$i]]['name'], PATHINFO_EXTENSION);
	  		 
 
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
    if($error>0) {return "Duplicate records! ". $error."insert into " . $configs['prev'].$table. " set " . $query;}
    if($print){echo  "insert into " . $configs['prev'].$table. " set " . $query;}
	if(!$error)
	{
		$id=mysqli_insert_id($configs['con']);

		return $id;
	}	
}

function getTotalRow($table,$cond=array(),$print=0)
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
					$condition1[]=$c .  $v ;

            }
            else
            {
                	$condition1[]=$c . "=\"".$v."\"";
            }	

        }
      	
	    $condition=" where  " .str_replace("and or "," or ",implode(" and ",$condition1));      
    }
    elseif(!is_array($cond) && $cond!="")
    {
		echo $cond;
		$condition=" where  " .$cond;
	}
	
	$rr=mysqli_query($configs['con'],"select * from " . $configs['prev'].$table. $condition);
    
    $error=mysqli_errno($configs['con']);
    if($print || $error>0){echo "select * from " . $configs['prev'].$table. $condition;}
    $total=@mysqli_num_rows($rr);
	 
	return $total;
}

function chkExist($table,$cond)
{
	return getTotalRow($table,$cond=array());
} 

function chklogin($referer="",$activation_code="")
{
    global $configs;
	
    
    if(!isset($_SESSION['user_id'])):
         if($referer){$txt="?referer=" . $referer;}
		 header("Location: ".$configs['vpath']."login".$txt);
		 exit();        
    endif;
    
    
}


 
 function getQuery($table,$fields="*",$cond=array(),$order_by="",$limit="",$print=0,$group_by="") {
	global $configs;
	$cols_array=array();$data=array();
	$like="";
	$condition1=array();
	$condition2=array();
	if(is_array($cond)) {
   	 	if(count($cond)) {
   	 		foreach($cond as $c=>$v) {	
	   	 		if(chkFld($c)) {
						$condition1[]=$c .  $v ;
	            }
	            else {
	                	$condition1[]=$c . "=\"".$v."\"";
	            }	
        	}
		    $condition=" where  " .str_replace("[or]"," ",implode(" and ",$condition1));  
		}       
    }
    elseif($cond!="") {
		$condition=" where " .$cond;
	}
	if($order_by) {
	   	$order_by=" order by " .$order_by;
	}
	if($group_by) {
	   	$group_by=" group by " .$group_by; 
	}
	if($limit) {
	   	$limit=" limit " .$limit;
	}
	$rr=mysqli_query($configs['con'],"select " .$fields ." from " . $configs['prev'].$table. " " .$condition .  $group_by. $order_by . " ".$limit);
    $error=mysqli_errno($configs['con']);
    if($print ||  $error>0) {
    	echo "select " .$fields ." from " . $configs['prev'].$table. " " .$condition . $order_by . " ".$limit;
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
 
#design=======================

function getList($table,$fields_arrray=array(),$action="",$cond=array(),$order_by="",$limit="")
{
	global $configs;
	$fields="";
	$label=array();
	for($i=0;$i<count($fields_arrray);$i++)
	{
		if(is_array($fields_arrray[$i]))
		{
			$e=$fields_arrray[$i];
			//$fields.=$e[2] .",";
			$label_array=explode(",",$e[1]);
			for($k=0;$k<count($label_array);$k++)
			{
				$label[]=ucwords(str_replace("_"," ",$label_array[$k]));
				$fields.=$label_array[$k] .",";
			}
			
		}
		else
		{
			$fields.=$fields_arrray[$i] .",";
			$label[]=ucwords(str_replace("_"," ",$fields_arrray[$i]));
		}
	}
	$fields=substr($fields,0,-1);
	
	$cols_array=array();$data=array();
	
	$condition="";
	$param="";

	$total=getTotalRow($table,$cond);
	
	$param=implode("&",$cond);
	
	$list='<table  width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="0" cellpadding="0"  style="margin-top:20px">
	<thead>
	<tr >';
	for($l=0;$l<count($label);$l++)
	{
		$list.='<td   align="left"><b>'.$label[$l].'</b></td>'; 
	}
	$list.='<td width="13%" align="center"><b>Action</b></td> 
	</tr>
	</thead>
	<tbody>';
	if(!$total)
	{
		$list.='<tr><td colspan=' . (count($label)+1) .'>No Record Found</td></tr>';
	}
	else
	{
		$rows=getQuery($table,"*",$cond,$order_by,$configs['limit']);
    	$k=0;
    	foreach($rows as $d)
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
		            if(substr_count($e[2],"="))
		            {
						$s=explode("=",$e[2]);
						
						$array=array($s[0]=>$d[$s[1]]);
					} 
					else
					{
						$array=array($e[2]=>$d[$e[2]]);
					}
					$name=getFldValueById($e[0],$e[1],$array);
				 
					$list.="<td >".$name . "</td>";
				}
				elseif(substr_count($fields_arrray[$i],"pic") || substr_count($fields_arrray[$i],"logo") ||substr_count($fields_arrray[$i],"file") || substr_count($fields_arrray[$i],"icon") || substr_count($fields_arrray[$i],"_img"))
                {
                	$list.="<td ><img src='" . $configs['vpath']."image.php?src=" . $configs['vpath'].$d[$fields_arrray[$i]] . "&w=100&h=100'></td>";
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
	if($total>20)
	{
		$list.="<div class='col-md-12 text-align-center'><ul class='pagination'>".paging($total,$limit,$param)."</ul></div>";
	}
	
	return $list;
}
function getSelect($data,$table_flds,$form_fld_id,$id=0)
{
	$html="<select name='" . $form_fld_id ."' id='" . $form_fld_id ."' class='form-control' size=1>
	<option value=''>Select " . ucwords($table_flds[1]) ."</option>";
	foreach($data as $c)
	{
		if($c[$table_flds[0]]==$id)
		{

			$html.="<option value='" . $c[$table_flds[0]] . "' selected>" . $c[$table_flds[0]] .") ". $c[$table_flds[1]] . "</option>";
		}
		else
		{
			$html.="<option value='" . $c[$table_flds[0]]. "' >" . $c[$table_flds[0]] .") ". $c[$table_flds[1]]. "</option>";
		}
	}
 
	$html.="</select>";
	return $html;

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
				$form.="<div class='col-md-6'>";
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
            $form.="<input type='file'  id='" . $fld['name'] ."' name='".$fld['name'] ."' ". $fld['required'] .">";
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
    		$form.="<input type='".$fld['type']."' class='btn btn-success' id='".$fld['name']."' name='".$fld['name']."' value='".$fld['value']."'>";
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
 
 
 
 
# Get Field Name By Id========================================
function getFldValueById($table,$field,$cond=array(),$print=0)
{
	global $configs;
	$condition="";$condition1=array();
	if(is_array($cond) && count($cond))
	{
		$keys=array_keys($cond);
   	 	foreach($cond as $c=>$v)
   	 	{	
	   	 		if(chkFld($c))
	            {
						$condition1[]=$c .  $v ;

	            }
	            else
	            {
	                	$condition1[]=$c . "=\"".$v."\"";
	            }	

        }
	    $condition=" where " .str_replace("and or "," or ",implode(" and ",$condition1));      
    }
  
	 
	$d=@mysqli_fetch_array(mysqli_query($configs['con'],"select " .$field ." from ".$configs['prev']. $table . $condition));;
    
    $error=mysqli_errno($configs['con']);
    
    if($print || $error>0)
    	echo "select " .$field ." from ".$configs['prev']. $table . $condition;
  	
  	if(substr_count($field," as "))
  	{
		$e=explode(" as ",$field);
		$field=$e[1];
	}
  	
  	return $d[$field];	
}

# Get Field Name By Id========================================
function getFldNameById($table,$field,$cond=array(),$print=0)
{
	global $configs;
	$condition="";$condition1=array();
	if(is_array($cond))
	{
		$keys=array_keys($cond);
   	 	foreach($cond as $c=>$v)
   	 	{	
	   	 		if(chkFld($c))
	            {
						$condition1[]=$c .  $v ;

	            }
	            else
	            {
	                	$condition1[]=$c . "=\"".$v."\"";
	            }	

        }
	    $condition=str_replace("and or "," or ",implode(" and ",$condition1));      
    }
    elseif($cond!="")
    {
	 
		$condition=$cond;
	}
	 
	$d=@mysqli_fetch_array(mysqli_query($configs['con'],"select " .$field ." from ".$configs['prev']. $table." where " . $condition));;
    
    $error=mysqli_errno($configs['con']);
    
    if($print || $error>0)
    	echo "select " .$field ." from ".$configs['prev']. $table." where " . $condition;
  	return $d[$field];	
}

/*
 function getMenu($location)
{
	global $configs;
	$menu=getQuery("contents","*",array($location=>1,"parent_id"=>0,"status"=>'Y'),"ord");
	$text="";
	if($location=="location_header")
	{
       
		for($i=0;$i<count($menu);$i++)
        {   $class="";
			 
			$submenu_tot=0;
			$submenu_tot=getTotalRow("contents",array("parent_id"=>$menu[$i]['id']));
            
            if(!$submenu_tot)
            {
			 
				$parent="";
				if(!file_exists($configs['apath'].$menu[$i]['seo_url'] .".php") && $menu[$i]['source_url']=='') 
				{
					$parent="info/";
				}	
				$text.="<li " . $class . "><a href='" .$configs['vpath'] . $parent .$menu[$i]['seo_url'] ."'>" .$menu[$i]['title'] ."</a></li>";
            	
			}
			else
            {
				 
			   	
			    
				$parent="";
               
                	if(!file_exists($configs['apath'].$menu[$i]['seo_url'] .".php") && $menu[$i]['source_url']=='')
					{
						$parent="info/";
					}	
					$text.="<li  ".$class."><a class='dropdown-toggle' data-toggle='dropdown' href='".$configs['vpath'].$parent.$menu[$i]['seo_url']."'>" .$menu[$i]['title'] ."<b class='caret'></b></a>";
	      			$text.="<ul class='dropdown-menu'>";
					$submenu=getQuery("contents","title,seo_url",array("parent_id"=>$menu[$i]['id']),"ord");
	      			for($s=0;$s<count($submenu);$s++)
	      			{
						$text.="<li ><a href='" .$configs['vpath'] .seo_url($menu[$i]['title'])."/".$submenu[$s]['seo_url'] ."'>" .$submenu[$s]['title'] ."</a></li>";
	            	
					}
	                $text.="</ul></li>";
      
                
				
			}
                            
		}


	}
	else
	{
		$i=0;
		$text.="<ul >";
		$n=count($menu);
		foreach($menu as $d)
    	{   
    		$parent="";
			if($d['parent_id'])
			{	
				//$parent=getFldValueById("contents","seo_url",array("id"=>$d['parent_id']));
		    }
			 
			if($d['source']=="content")
			{
				$parent="info/";
                				
			}	
    		
			$text.="<li><a href='" . $configs['vpath'].$parent.$d['seo_url'] . "'>" .ucwords($d['cont_title']) . "</a></li>";

    		$i++;
    		/*if(round($n/2)==$i)
    		{
    			$text.="</ul><ul class='one-half'>";
    		}*//*	
    	}
    	$text.="</ul>";
	}	
	

    return $text;
}

*/
function getArray($cond,$quote="")
{
	 $new_array=array();
	 foreach($cond as $c=>$val)
	 {
	 	$new_array[]=$c . "=".$quote.$val.$quote;
     }	
	 
	 return $new_array;
}	 
 


function template($args=array())
{
	
  global $configs;
   
  $names=array();
  if( is_array( $args['files'] ) ) { 
     $names = array_values( $args['files'] ); 
  }

  if(is_array($configs['includes']) && count($configs['includes'])) {
  	 $names = array_merge($configs['includes']['header'], $names, $configs['includes']['footer']); 
  }

  $template_found=array();
  if($args['script_dir']) {
  	$args['script_dir']=$args['script_dir'] ."/";
  }
 
  foreach ( $names as $name ) {
    	 
    	$file = $configs['apath']."template/".$configs['template'] . "/". $name . '.php';
   
    	if($args['admin']) {
    		if(substr_count($name,"includes")) 	{
			     $file = $configs['apath'].$configs['admin_dir']."/template/".$configs['admin_template'] . "/".$name . '.php';
			}
	    	else {
			     $file = $configs['apath'].$configs['admin_dir']."/template/".$configs['admin_template'] . "/". $args['script_dir'].$name . '.php';
			}
    	}	
    
    	if (file_exists($file)) {
      	   if($args['admin']) {
		   		if(strpos($_SERVER['PHP_SELF'],"index.php")) {
					$template_found[] =$configs['apath'].$configs['admin_dir']."/template/".$configs['admin_template']."/".$args['script_dir'].$name . '.php';
				}
				elseif(substr_count($name,"includes"))  {
					$template_found[] =$configs['apath'].$configs['admin_dir']."/template/".$configs['admin_template']."/".$name . '.php';
				}
				else {
					$template_found[] =$configs['apath'].$configs['admin_dir']."/template/".$configs['admin_template']."/".$args['script_dir'].$name . '.php';
				}
		   		
		   } else {
		   		if(strpos($_SERVER['PHP_SELF'],"index.php")) {
					$template_found[] ="template/".$configs['template']."/".$name . '.php';

				} else {
					$template_found[] ="../template/".$configs['template']."/".$name . '.php';
					//$template_found[] ="template/".$configs['template']."/".$name . '.php';
				}
		   }
    	} else {  
    	    return $file. ' not Found';// stop after the first template is found
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

function getStateId($country_id,$state_name)
 {
 	global $configs;
    $n=getTotalRow("state",array("country_id"=>$country_id,"name"=>$state_name));
    if(!$n)
    {
    	$state_id=insertTable("state",array("country_id"=>$country_id,"name"=>$state_name)); 
    }	
 	else
 	{
 		$state_id=getFldValueById("state","id",array("country_id"=>$country_id,"name"=>$state_name)); 
 	}	
    return $state_id;
 }

function getCityId($country_id,$state_id,$city_name)
 {
 	global $configs;
    $n=getTotalRow("city",array("country_id"=>$country_id,"state_id"=>$state_id, "name"=>$city_name));
    if(!$n)
    {
    	$city_id=insertTable("city",array("country_id"=>$country_id,"state_id"=>(int)$state_id,"name"=>$city_name)); 
       
    }	
 	else
 	{
 		$city_id=getFldValueById("city","id",array("country_id"=>$country_id,"name"=>$city_name)); 
 	}	
    return $city_id;
 }
 
function getCatId($cat_name)
{
	global $configs;
    $n=getTotalRow("category",array("cat_name"=>$cat_name));
    if(!$n)
    {
    	$cat_id=insertTable("category",array("cat_name"=>$cat_name,"parent_id"=>0,"seo_url"=>seo_url($cat_name),"meta_title"=>$cat_name,"status"=>"Y")); 
       
    }	
 	else
 	{
 		$cat_id=getFldValueById("category","cat_id",array("cat_name"=>$cat_name)); 
 	}	
    return $cat_id;
	
}

function getExtraVal($user_id,$cat_id)
{
	$extra="";
	$row=getQuery("form","*",array("cat_id"=>$cat_id),"fld_ord");
	if(count($row))
	{
		
		foreach($row as $d)
		{
			$value=getFldValueById("service_data","value",array("user_id"=>$user_id,"id"=>$d['id']));
            if($value)
			{
				$extra.="<div class='col-md-4'><h4>" . ucwords(str_replace("_"," ",$d['fld_additional'])) ." </h4>" .$value ."</div>";
			}	 
			 
		}
		
    }
	return $extra;
}

function getFldType($table,$fld)
{
	global $configs;
	$data=mysqli_fetch_array(mysqli_query($configs['con'],"SELECT COLUMN_TYPE
	FROM information_schema.COLUMNS
	WHERE TABLE_NAME = '" . $configs['prev'].$table."'
	AND COLUMN_NAME = '" . $fld."' limit 1"));
 
	return substr($data['COLUMN_TYPE'],0,strpos($data['COLUMN_TYPE'],"("));
	
}

function postFields($table,$POST)
{
	global $configs;
	$array=array();
	foreach($POST as $d=>$v)
	{
		if(getFldType($table,$d)=="int")
		{
			$array=array_merge($array,array($d=>(int)$v));
		}
		elseif(getFldType($table,$d)=="text")
		{
			$array=array_merge($array,array($d=>htmlentities($v)));
		}
		else
		{
			$array=array_merge($array,array($d=>$v));
		}
	}
	
	return $array;
}


#Banner==============================================================
function banner($size)
{
    global $configs;
    $d=getQuery("adsense","*",array("size"=>$size,"status"=>'Y'),"rand()","1");
 
	$e=explode("x",$d['size'][0]);
    $width=$e[0];$height=$e[1];
	if($d[0]['type']==1)
	{
	 
    	$dx= html_value_output($d['adsense'][0]);
		$dx=str_replace("000000","E7DEBD",$dx);
		if($height!=240)
		{
			$dx=str_replace("text_image","image",$dx);
		}
	 
		$dx=str_replace("F0F0F0","E7DEBD",$dx);
		return $dx;
	}
	else
	{
	 
		return"<a href='". $d['link'][0]."' target='_blank' title='". $d['link'][0]."'><img src='". $configs['vpath'] . "image.php?image=" . $configs['vpath'] . $d['banner'][0]."&width=" .$width . "&height=" . $height . "' width='".$width."' height='". $height."' border='0' alt='". $d['link'][0]."'></a>";
    }
}
function adPic($adid)
{
	global $configs;
	$d=getQuery("ads_img","*",array("adid"=>$adid),"",1);
 
    
    if(count($d))
    {
		 
      	$id=$dd["id"][0];
	  	$img=$dd["img"][0];
	}
    else
    {
		$img="uploads/picture/noimage.jpg";
	}
   
    if(!file_exists($apath .$img))
    {
		$img="uploads/picture/noimage.jpg";
	}
   
    return $img;

}
function getName($user_id) 
{
	
  $user_type=getFldValueById("login","user_type",array("user_id"=>$user_id));
    
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
	
}
function getPicAll($user_id)
{
	global $configs;
	$img=array();
	$dd=getQuery("picture","*",array("user_id"=>$user_id,"file_type"=>"p"),"pos");
    foreach($dd as $d)
    {
		 $img[]=$d["file"];
	}
      
  
    return $img;

}


function getCityList($id)
{
	global $configs;
	$menu="";
	$k=0; 
	$data=getQuery("city","*",array("country_id"=>$id),"name","60");
    $txt="<ul style='text-align:left'>";
    foreach($data as $d)
    {
	    $total=$d['total'];
        if($total)
        {
		   $txt.="<li ><a href='" . $configs['vpath'] . "escorts/" . $_GET['country_name'] ."/" .seo_url($d['city_name']) . "'>" . $d['city_name'] ."</a></li> ";  
        	
		}
  	    $k++;

  	}
  	$txt.="</ul>";
  	return $txt;	
}
function getCity($city_id)
{
	global $configs;
	
	 
	$d=getDataByWhere("city","*",array("id"=>$city_id));
 
  	return $d['name'];	
}
function getCityContents($city_id)
{
	global $configs;
	$d=getDataByWhere("city","*",array("city_id"=>$city_id));
 
  	return $d['contents'];	
}

function getTotalCity($id)
{
	global $configs;
	
	$n=getTotalRow("city", array("country_id"=>$id));
 
  	return $n;
	
}
function getCountry($id)
{
	global $configs;
	
	 
	$name=getFldValueById("country","name",array("id"=>$id));
 
  	return $name;	
}

 
function getFlag($id)
{
	global $configs;
	
	$d=getFldValueById("country","pic",array("id"=>$id));
 
  	return $d['pic'];	
}

 
function  getCategory($cat_id)
{
	global $configs;
	$d=getFldValueById("category","cat_name",array("cat_id"=>$cat_id));
	
	$name=$d['cat_name'];
	return $name;
	
	 
}
function  getUserData($user_id,$table)
{

	global $configs;
	$d=getDataByJoin(array($table." as t","login as l"),"t.*,l.*",array("l.user_id"=>$user_id),array("t.user_id"=>"l.user_id"));

   
}


if($_GET['country_name'])
{
	global $configs;
	$name=ucwords(str_replace("-"," ",$_GET['country_name']));
	 
	$d=getDataByWhere("country","*",array("name"=>$name));
    
  	$_GET['country_id']=$d['id'];
  	$country_id=$d['id'];	
}
if($_GET['city_name'])
{
	
	global $configs;
	
	$name=ucwords(str_replace("-"," ",$_GET['city_name'])); 
	
	$d=getDataByWhere("city","*",array("country_id"=>$_GET['country_id'],"city_name"=>$name));
  
  	$_GET['city_id']=$d['city_id'];	
  	$city_id=$d['city_id'];		
}
if($_GET['user_id'])
{
	$d=getDataByWhere("login","*",array("user_id"=>$_GET['user_id']));
    $_GET['country_id']=$d['country_id'];
  	$country_id=$d['country_id'];
  	$_GET['city_id']=$d['city_id'];	
  	$city_id=$d['city_id'];		
}

 function get_times( $escort_id=0,$day='',$timer='start_time',$default = '19:00', $interval = '+15 minutes') {
    global $con,$prev;
    $days=array();
    
    $output = '';

    $current = strtotime( '00:00' );
    $end = strtotime( '23:59' );

    while( $current <= $end ) {
        $time = date( 'H:i', $current );
        $sel = ( $time == $default ) ? ' selected' : '';
        
        $selected="";
        if($escort_id):
    		 $n=getTotalRow("escort_working_days",array("user_id"=>$escort_id, "day"=>$day,$timer=>$time));
             if($n)$selected=" selected";
        endif;	
       
        $output .= "<option value='".$time . "'" . $selected.">" . date( 'h.i A', $current ) ."</option>";
 
        $current = strtotime( $interval, $current );
    }

    return $output;
}

if(!$_SESSION['country_code'])
{	
	// set IP address and API access key 
	$ip = $_SERVER['REMOTE_ADDR'];
	$access_key = '61142150451ef0a0ddf28c5a0de27bcf';

	// Initialize CURL:
	$ch = curl_init('http://api.ipstack.com/'.$ip.'?access_key='.$access_key.'');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Store the data:
	$json = curl_exec($ch);
	curl_close($ch);

	// Decode JSON response:
	$api_result = json_decode($json, true);

	// Output the "capital" object inside "location"
	$_SESSION['country_code']=$api_result['country_code'];
	
	

	if($_SESSION['country_code'])
	{	
		$d=getDataByWhere("country","*",array("code"=>$api_result['country_code']));
		if(count($d))
		{
			$_GET['country_id']=$d['id'];
			$country_id=$d['id'];
			$_GET['country_name']=seo_url($d['name']);
		}
	}
}


function feedbackBy($user_id) 
{
	
  global $configs;	
  
  $user_type=getFldValueById("login","user_type",array("user_id"=>$user_id));
   
  $d=getDataByWhere($user_type,"*",array("user_id"=>$user_id));
  $city=getCity($d['city_id']);
  if($d['logo'])
  {
  	$logo=$d['logo'];
  }
  else
  {
  	$logo="img/noimage.jpg";
  }
  
  $name=$r['fname'] ." " . $r['lname'];
  return array("name"=>$name,"city"=>$city,"logo"=>$logo);
	
}

function  getFeedback($user_id)
{
	global $configs;
	$row=getQuery("feedback","*",array("status"=>'Y',"escort_id"=>$user_id));
	$txt="";
	if(count($row))
	{
	 
		foreach($row as $d)
		{
			$txt.="<div class='col-md-12'>" . $d['feedback'] . "<br>~~<b><i>" . ucwords($d['feedback_by']) . "</i></b> said on ". showdate($d['added_date']). "</div>";	
		}
    }
	return $txt;
}

function mainmenu()
{
	
	global $configs;
	$menu="";
	$row=getQuery("contents","*",array("location_header"=>1,"menu"=>'Y',"parent_id"=>0),"ord");

    foreach($row as $d)
    {
		
	
  	    $row2=getQuery("contents","*",array("menu"=>'Y',"parent_id"=>$d['id']),"ord");
  		$mtotal=count($row2);
  		 
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
  			$menu.="<li class=\"dropdown\"><a href='". $configs['vpath'] . "info/" . $d['seo_url'] ."'>" . $d['cont_title'] . "</a>";
      		$menu.="<ul class=\"dropdown-menu dropdown-menu-left\">";
      		foreach($row2 as $d2)
      		{
					 
      		    $link=$configs['vpath'] . $d['seo_url'] . "/" .$d2['seo_url'];
      		     
    			$menu.="<li><a href='". $link. "'>" . $d2['cont_title'] . "</a></li>";
			}
           
      		$menu.="</ul></li>";
      	elseif($d['id']==1):
      		$menu.="<li class=\"dropdown\"><a href='". $configs['vpath'] . "info/" . $d['seo_url'] ."'>" . $d['cont_title'] . "</a>";
      		$menu.="<ul class=\"dropdown-menu dropdown-menu-left\">";
      		
      		$row3=getQuery("city","*",array("country_id"=>$country_id));
  			foreach($row3 as $d3)
  			{
				$menu.="<li><a href='" . $configs['vpath']."search/" . $d3['city_id'] . "'>" .$d3['city_name'] ."</li>";  
			}
  	     
      	    $menu.="</ul></li>";
      	else:
      	    if((isset($_GET['key']) && substr_count($d["source_url"],$_GET['key']))|| substr_count($_SERVER['REQUEST_URI'],$d["source_url"])):
      	 		$menu.="<li class='active'><a href='". $link ."'>" . $d['cont_title']."</a></li>";
      	 	else:
      	 		$menu.="<li ><a href='". $link ."'>" . $d['cont_title']."</a></li>";
      	 	endif;	
      	endif;	
  	}
  	return $menu;	
}

function submenu($seo_url)
{
	
	global $configs;
	
	$d=getDataBYWhere("contents","*",array("menu"=>'Y',"seo_url"=>$seo_url),"ord");
    if(count($d))
    {
	
        $row=getQuery("contents","*",array("menu"=>'Y',"parent_id"=>$d['id']),"ord");
        $menu='<ul class="left_pannel">';
        foreach($row as $d2)
        {
			$link=$configs['vpath'] . $d['seo_url'] . "/" .$d2['seo_url'];
	      	$menu.="<li><i class=\"fa fa-angle-double-right\" aria-hidden=\"true\"></i> <a href='". $link. "'>" . $d2['cont_title'] . "</a></li>";        
	 
		}
 
     	$menu.='</ul>';
  	}
  	
  	return $menu;	
}

function footer_simple_menu()
{
	
	global $configs;
	 
    $menu='<div style="padding:10px"><ul>';
	$row=getQuery("contents","*",array("menu"=>'Y',"location_footer"=>'1'),"ord");
    foreach($row as $d)
    {
	    $link=$configs['vpath'] . $d['seo_url'];
	   	$menu.="<li><a href='". $link. "' class='active'>" . $d['cont_title'] . "</a></li>";        
		
	}
 
	$menu=substr($menu,0,-2);	
    $menu.='</ul></div>';
  
  	return $menu;	
}
 
 

function getField($id,$fld_type,$fld_text,$price_change=0,$label=0,$product_id=0,$order=0,$attr="")
{
	global $configs;
	$txt="";$typevalue=array();
	  
	if($price_change):
		$star="<font color=red  size=1></font>";
		$required="onChange=\"getRate('" . $attr . "','" . $product_id ."');\" required";
	else:
		$required="";
		$star="";
	endif;	
	if($label>0):
	   	$txt.="<div class='row form-group xrow'><div class='col-md-12'><label>"  .$fld_text . " " . $star . "</label></div><div class='col-md-8'>";
	else:
	    $txt.="<div class='row form-group xrow'><div class='col-md-12'><label>"  .$fld_text . " " . $star . "</label>";
	endif;    
	if($fld_type<=3):
		$j=0;
		if($fld_type==3):
			$idx="";
		    if($fld_text=="Art Work")
			{
				$idx="id='artwork'";
			}
            $txt.="<div class='custom-select-wrapper mb-0'><select ".$idx ." name='" . str_replace(" ","_",$fld_text) . "' id='" . str_replace(" ","_",$fld_text) . "'  class='form-control custom-select' " .$required ." ><option value=''>Select " .$fld_text."</option>";
	    
	    elseif($fld_type==1 || $fld_type==2):
		    if(!$label):
			    //$txt.="<p></p>";
            endif;  
		endif;
		
		$options=getQuery("form_option","*",array("id"=>$id),"lid");
        $opt=0;
        foreach($options as $dd)
        {
        	 
			if($fld_type==1):
				if($j==0):
					if($value==Ucwords($dd['name'])):
						$txt.="<div class='cas-radio'><input type='radio' name='" . str_replace(" ","_",$fld_text) . "' id='" . str_replace(" ","_",$fld_text) . "' checked  value=\"". $dd['name'] . "\"  checked  " .$required ." > "  .Ucwords($dd['name']) ."</div>";
					else:
						$txt.="<br><div class='cas-radio'><input type='radio' name='fld" . $id . "' checked  value=\"". $dd['name'] . "\"  " .$required ." > "  .Ucwords($dd['name']) ." </div>";
					endif;	
				else:
				    
 					if($value==$dd['name']):
						$txt.="<div class='cas-radio'><input type='radio' name='fld" . $id . "'   value=\"". $dd['name'] . "\"  checked   " .$required ."  > "  .Ucwords($dd['name']) ."</div>";
					else:
						$txt.="<div class='cas-radio'><input type='radio' name='fld" . $id . "'   value=\"". $dd['name'] . "\"  " .$required ." > "  .Ucwords($dd['name']) ."</div>";
					endif;	
				endif;
			elseif($fld_type==2):
			
                if(in_array($dd['name'],$value_array)):
					$txt.="<br><input type='checkbox' name='fld" . $id . "[]'  value=\"". $dd['name'] . "\"  checked>  " .$dd['name'] . " <br> ";
				else:
					$txt.="<input type='checkbox' name='fld" . $id . "[]'  value=\"". $dd['name'] . "\"  >  " .Ucwords($dd['name']) . " <br>";
				endif;	
			elseif($fld_type==3):  
                
			    if($fld_text=="Quantity")
			    {
			    	if($value==$dd['name'] || !$opt):
				       $txt.="<option value='" . $dd['name'] . "' selected>"   .  $dd['name'] . "</option>";
				    else:
					   $txt.="<option value='" . $dd['name'] . "' >"   .$dd['name'] . "</option>";
                    endif;
			    }
			    else
			    {
			    	if($value==$dd['lid'] || !$opt):
				       $txt.="<option value='" . $dd['lid'] . "' selected>"   .  $dd['name'] . "</option>";
				    else:
					   $txt.="<option value='" . $dd['lid'] . "' >"   .$dd['name'] . "</option>";
                    endif;
			    }				    	
			   
				$opt++; 				  
	  		endif;
			$j++;
		
		}
		if($fld_type==3):
	        $txt.="</select></div>";
		    if($idx):
		    	
		       $txt.="<div class='row form-group xrow' style='visibility:hidden'><div class='col-md-12'><label>Upload Artwork *</label></div><div class='col-md-8'><input type='file' name='artwork_file' id='artwork_file'   class='form-control'  Placeholder='Enter Artwork file '></div></div>";
            
            endif;  
	    endif;
		 	
	elseif($fld_type==4):
        
		$txt.="<div class='custom-input'><input type='text' name='fld" . $id . "'  value='" . $value . "' class='form-control'  Placeholder='Enter " . $fld_text . "' " .  " .$required ."  . "></div>";
	elseif($fld_type==5):
		$txt.="<textarea name='fld" . $id . "' class='form-control'  Placeholder='Enter " . $fld_text . "' "  .$required .">" . $value . "</textarea>";
	elseif($fld_type==6):
		$txt.="<div class='custom-input'><input type='text' id='fld" . $id . "' name='fld" . $id . "'  value='" . $value . "' class='form-control'  Placeholder='Enter " . $fld_text . " (yyyy-mm-dd)' " .  " .$required ."  . "></div>";
	
	elseif($fld_type==7):
		$txt.="<input type='file' name='fld" . $id . "'  class='form-control' " .  " .$required ."  . ">" . $value;
	endif;
		
	$txt.="</div></div>";
	
    
    return $txt;
}




 
// Save The Keys In Your Configuration File
define('FIRSTKEY','Lk5Uz3slx3BrAghS1aaW5AYgWZRV0tIX5eI0yPchFz4=');
define('SECONDKEY','EZ44mFi3TlAey1b2w4Y7lVDuqO+SRxGXsa7nctnr/JmMrA2vN6EJhrvdVZbxaQs5jpSe34X3ejFK/o9+Y5c83w==');

 
 
function encrypt($input)
{
	/*$first_key = base64_decode(FIRSTKEY);
	$second_key = base64_decode(SECONDKEY);   
	   
	$method = "aes-256-cbc";   
	$iv_length = openssl_cipher_iv_length($method);
	$iv = openssl_random_pseudo_bytes($iv_length);
	       
	$first_encrypted = openssl_encrypt($data,$method,$first_key, OPENSSL_RAW_DATA ,$iv);   
	$second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);
	           
	$output = base64_encode($iv.$second_encrypted.$first_encrypted);   
	return $output; 
	*/
	//return base64_encode($input);   
	return $input;       
}
 
function decrypt($input)
{
	/*$first_key = base64_decode(FIRSTKEY);
	$second_key = base64_decode(SECONDKEY);           
	$mix = base64_decode($input);
	       
	$method = "aes-256-cbc";   
	$iv_length = openssl_cipher_iv_length($method);
	           
	$iv = substr($mix,0,$iv_length);
	$second_encrypted = substr($mix,$iv_length,64);
	$first_encrypted = substr($mix,$iv_length+64);
	           
	$data = openssl_decrypt($first_encrypted,$method,$first_key,OPENSSL_RAW_DATA,$iv);
	$second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);
	   
	if(hash_equals($second_encrypted,$second_encrypted_new))
	{
		return $data;
	}
	else
	{
		return false;
	}   */
	//return base64_decode($input); 
	return $input; 
}

 function nameField($val)
{
	if(preg_match("/([%\$#\*\'\"\()]+)/", $val)) //Not allowed some of the special characters
		{
		return false;
		}
	else
	{
		return true;
	}
}

function numberField($val)
{
	if(preg_match("/^[0-9]*$/", $val))
	{
	return true;
	}
	else
	{
	return false;
	}
}
function getJson($values,$array)
{
	$val=[];
	for($i=0;$i<count($array);$i++)
	{
		$val[]="\"" .$array[$i] . "\":\"".$values[$array[$i]]."\"";
		
	}
	return '{'.implode(",",str_replace('"','\"',$val)).'}';
}

function validateReferral($code)
{
   
    $ch = curl_init("https://staging-officers.asvab-tutoring.com/validate-referral.php?code=" . $code);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 	fclose($lfile);
    curl_close($ch);
    return curl_exec($ch);
}

function getAllDataByWhere($table, $fields = "*", $whereArray = [])
{
    global $conn;

    $sql = "SELECT {$fields} FROM {$table}";

    if (!empty($whereArray) && is_array($whereArray)) {
        $conditions = [];

        foreach ($whereArray as $key => $value) {
            $value = mysqli_real_escape_string($conn, $value);
            $conditions[] = "{$key} = '{$value}'";
        }

        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $result = mysqli_query($conn, $sql);

    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data; // Always returns array (empty if none)
}

function getAllRowsByWhereWithPrefix($table, $fields="*", $cond=[], $order_by="", $limit="", $print=0)
{
    global $configs;

    $condition = "";
    $condition1 = [];

    if (is_array($cond) && count($cond)) {
        foreach ($cond as $c => $v) {
            if (chkFld($c)) {
                $condition1[] = $c . $v;
            } else {
                $condition1[] = $c . "=\"" . $v . "\"";
            }
        }
        $condition = " where " . str_replace("[or]", " ", implode(" and ", $condition1));
    } elseif ($cond !== "") {
        $condition = " where " . $cond;
    }

    $order = $order_by ? " order by " . $order_by : "";
    $lim   = $limit ? " limit " . $limit : "";

    $sql = "select " . $fields . " from " . $configs['prev'] . $table . $condition . $order . $lim;

    $rr = mysqli_query($configs['con'], $sql);
    $error = mysqli_errno($configs['con']);
    if ($print || $error > 0) { echo $sql; }

    $rows = [];
    if ($rr) {
        while ($d = mysqli_fetch_assoc($rr)) {
            $rows[] = $d;
        }
    }
    return $rows;
}

function tmpl_subscribe_h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

?>