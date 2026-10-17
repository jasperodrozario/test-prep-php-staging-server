<?php
 	
function copyright($x)
{
	global $vpath,$template_dir;
	if($x == "admin_header")
	{
		echo '<a href="https://www.asvab-tutoring.com/" target="_blank" title="Scriptgiant.com"><img src="images/logo.png" alt="asvab-tutoring.com" border="0" height="100"/></a>';
	}
	elseif($x == "admin_footer")
	{
		echo "Powered By <a href='https://www.asvab-tutoring.com/' target='_blank' class='lnk' title='asvab-tutoring.com'>asvab-tutoring.com</a>";
	}
}

function seo_url($x)
{
	$x=str_replace(" ","-",$x);
	$x=preg_replace('/[^a-z0-9_-]/i', '', $x);
	$x=preg_replace('/--/i', '-', $x);
	 
	if(!$x)
	{
		 $x="more-info";
	} 	 
	return strtolower($x);
    
}

  
#=============================================
function GetCartId()
{
		$cartId = session_id();
		$_SESSION["cartId"] = $cartId;
		return session_id();
}

#Sub======================================================================================
function paging($total,$perpage = 100,$param = "") {
	$limit = $_REQUEST['limit'];
	if(!$limit)
        {
		$limit = 1;
        }

	$page = ceil($total / $perpage);

	$start = 1;
	$end = 10;
	$t = @($limit % 10);
	
	if($t)
		$start = $limit - $t + 1;
	elseif($limit > 10)
		$start = $limit - 9;
	
	$end = $start + 9;
	
	if($page < $end)
        {
		$end = $page;
	}
	if($param)
	{
		$param="&amp;".$param;
	}	
	$data = "";
	 
	$primary_text = basename($_SERVER['PHP_SELF'],".php").'?limit=';
		

	if($limit > 1 && $start != 1) {
		$data .= "<li><a href='".$primary_text.($start - 1).$param."'>&laquo; Prev</a></li> ";
	}
	
	for($i = $start; $i <= $end; $i++) {
		if($limit == $i) {
			$data .= "<li><a href='".$primary_text.$i.$param."'><b><u>".$i."</u></b></a></li> ";
		}
		else {
			$data .= "<li><a href='".$primary_text.$i.$param."'>".$i."</a></li> ";
		}
		
		 
	}
	
	if($end < $page) {
		$data .= "<li><a href='".$primary_text.$i.$param."'>Next &raquo;</a>" .$total ."</li> ";
	}
	
	if($total > $perpage) {
		return $data;
	}
}

function paging_page($url_link, $total, $perpage = 100,$param = "") {
	$limit = $_REQUEST['limit'];
	if(!$limit)
    {
		$limit = 1;
    }

	$page = ceil($total / 100);

	$start = 1;
	$end = 10;
	$t = @($limit % 10);
	
	if($t)
		$start = $limit - $t + 1;
	elseif($limit > 10)
		$start = $limit - 9;
	
	$end = $start + 9;
	
	if($page < $end)
        {
		$end = $page;
	}
	if($param)
	{
		$param="&amp;".$param;
	}	
	$data = "";
	 
//	$primary_text = basename($_SERVER['PHP_SELF'],".php").'?limit=';
	
    $primary_text = $url_link.'?limit=';
		
	if($limit > 1 && $start != 1) {
		$data .= "<li><a href='".$primary_text.($start - 1).$param."'>&laquo; Prev</a></li> ";
	}
	
	for($i = $start; $i <= $end; $i++) {
		if($limit == $i) {
			$data .= "<li><a href='".$primary_text.$i.$param."'><b><u>".$i."</u></b></a></li> ";
		}
		else {
			$data .= "<li><a href='".$primary_text.$i.$param."'>".$i."</a></li> ";
		}
		
		 
	}
	
	if($end < $page) {
		$data .= "<li><a href='".$primary_text.$i.$param."'>Next &raquo;</a>" .$total ."</li> ";
	}
	
	if($total > $perpage) {
		return $data;
	}
}

 function showdate($date)
 {
    if($date):
       $e=explode("-",$date);
      //return $e[2] . " " . $month_ary[$e[1]] . "'" . $e[0];
	  return date("d-M-Y",strtotime($date)); 
	  //return $e[1] . " " . $e[2] . "," . $e[0];
    endif;
 }
 function showdate2($date)
 {
    global $month_ary;
    if($date):
       $e=explode("-",$date);
      //return $e[2] . " " . $month_ary[$e[1]] . "'" . $e[0];
	  return  $e[2] . "th " . $month_ary[$e[1]] . " ' " . $e[0];
    endif;
 }
 function sqldate($date)
 {
    if($date):
       $e=explode("/",$date);
       return $e[2] . "-" . $e[1] . "-" . $e[0];
    endif;
 }


#=========================================================================================================================
function chklogin($referer="")
{
    global $configs;
    
    if(!isset($_SESSION['user_id'])):
       
         if($referer)
         {
         	$referer=str_replace("","",$referer); 
            $txt="?referer=" . $referer;
         }
		 header("location:".str_replace("practice-test/","",$configs['vpath'])."login" . $txt);
		 exit();        
     endif;
}

#Number Format====================================================================
function number($n)
{
     $txt="";
	 $nom = explode(",",substr(number_format($n, 3),0,-4));
	 for($i=0;$i<count($nom);$i++):
	   if($i==0):
	      $txt.=$nom[0] . ",";
	   else:
	      $txt.=$nom[$i] . ",";
	   endif;
	 endfor;
	 return substr($txt,0,-1);
}
#=========================================================================
function ref_code($n)
{
    $temp = "";

    for ($i = 0; $i < $temp; $i++) {
       $temp .= rand(0, 9);
    }
 
    return $temp;
}


function morePaging($total,$perpage=20,$param="",$class="lnk")
{
    $limit=$_REQUEST['limit'];
	$page=ceil($total/$perpage);
	$t=$limit+1;
	$data="<a href='" . $_SERVER['PHP_SELF'] . "?limit=" . $t . $param . "' class='" . $class . "'> More >> </a>";
	if($t<=$page)
	{
	  echo $data;
	}    
}
function encrypter($string, $key)
{
	$result = '';
	for($i=0; $i<strlen($string); $i++) {
	$char = substr($string, $i, 1);
	$keychar = substr($key, ($i % strlen($key))-1, 1);
	$char = chr(ord($char)+ord($keychar));
	$result.=$char;
}
return base64_encode($result);
}
function decrypter($string, $key) {
$result = '';
$string = base64_decode($string);
for($i=0; $i<strlen($string); $i++) {
$char = substr($string, $i, 1);
$keychar = substr($key, ($i % strlen($key))-1, 1);
$char = chr(ord($char)-ord($keychar));
$result.=$char;
}
return $result;
}
 function isInt($foo)
 {
    if (preg_match("/^[0-9]+$/i", $foo))
    {
      return true;
    }
    else
    return false;
}


function tz_list() 
{
  $zones_array = array();
  $timestamp = time();
  foreach(timezone_identifiers_list() as $key => $zone) {
    date_default_timezone_set($zone);
    $zones_array[$key]['zone'] = $zone;
    $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
  }
  return $zones_array;
}
function getPassword($length=8){
     $token = "";
     $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
     $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
     $codeAlphabet.= "0123456789";
     $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[rand(0, $max-1)];
    }

    return $token;
}

function autonumber()
{
	for ($randomNumber = mt_rand(1, 9), $i = 1; $i < 10; $i++) 
	{
    	$randomNumber .= mt_rand(0, 9);
    	
    }
    return $randomNumber;
}
function getToken($length=32){
     $token = "";
     $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
     $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
     $codeAlphabet.= "0123456789";
     $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return $token;
}
function homework_paging($total, $limit,$cat_id,$lesson_id,$lesson_name) {
	global $configs;
	$paging=[];
	 
	if(!$limit)
	{
		$start=1;
		$end=2;
	}
	else
	{
		$start = $limit - 1;
		$end = $limit+1;
	}	
 	$start_disabled="";
 	$next_disabled="";
 	
	if($limit==1)
	{
		$start_disabled="disabled";
	}	
	if($total==$limit)
	{
		$next_disabled="disabled";
	}
	
	$primary_text = $configs['vpath'] . "lesson/" . $cat_id . "-" . $lesson_id . "-" . $lesson_name."/homework/";
	
	if($start_disabled) 
	{
	    $paging['prev']= "<a id='previous' class='btn pull-left' href='javascript:void(0);return false;' " .$start_disabled ."><i class='fa fa-angle-left fa-2x'></i></a>";
	}	
	else
	{
	    $paging['prev']= "<a id='previous' class='btn pull-left' href='".$primary_text.$start."'><i class='fa fa-angle-left fa-2x'></i></a>";
	}	
	
	if($next_disabled)
	{
		$paging['next']= "<a id='next' class='btn pull-right'  href='javascript:void(0);return false;' ".$next_disabled ."><i class='fa fa-angle-right fa-2x'></i></a>";
	}
	else
	{
		$paging['next']= "<a id='next' class='btn pull-right'  href='".$primary_text.$end."'><i class='fa fa-angle-right fa-2x'></i></a>";
	}		
	
	return $paging; 
}
?>