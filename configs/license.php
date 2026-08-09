<?php
 
function isMobileDevice() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
 
$month_ary["01"]="Jan";
$month_ary["02"]="Feb";
$month_ary["03"]="Mar";
$month_ary["04"]="Apr";
$month_ary["05"]="May";
$month_ary["06"]="Jun";
$month_ary["07"]="Jul";
$month_ary["08"]="Aug";
$month_ary["09"]="Sep";
$month_ary["10"]="Oct";
$month_ary["11"]="Nov";
$month_ary["12"]="Dec";
 
function seo_url($x)
{
	 $x=str_replace(" ","-",$x);
	 $x=preg_replace('/[^a-z0-9_-]/i', '', $x);
	 
	 if(!$x)
	 {
		 $x="more-info";
	 } 	 
	 return strtolower($x);
	 
    
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

function number($digits)
{
    $temp = "";

    for ($i = 0; $i < $digits; $i++) {
       $temp .= rand(0, 9);
    }
 
    return $temp;
}
  
#=============================================
function GetOrderId()
{
		 
		if(!$_SESSION["order_id"])
		{
			$_SESSION["order_id"] = number(3)."-".number(3);
		   
		}
		return $_SESSION["order_id"];
}

#Sub======================================================================================
function paging($total, $param = "", $url = "") 
{
	$perpage=20;
	$limit = $_GET['limit'];
	if(!$limit)
		$limit = 1;
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
		$end = $page;
	
	$data = "";
	if(!$url)
	{
		$url=basename($_SERVER['PHP_SELF'],".php"); 
	}
	if(substr_count($_SERVER['PHP_SELF'],"admin")):
		$primary_text = $url.'?limit=';
	else:
		$primary_text = $_SERVER['REQUEST_URI'] ."/page/";
		
	endif;
	
	if($limit > 1 && $start != 1) 
	{
		$data .= "<li class='paginate_button'><a href='".$primary_text.($start - 1).";'>&laquo; Prev</a> </li>";
	}
	
	for($i = $start; $i <= $end; $i++) 
	{
		if($limit == $i) 
		{
			$data .= "<li class='paginate_button current'><a href='".$primary_text.$i.$param."'><b>".$i."</b></a></li>";
		}
		else 
		{
			$data .= "<li class='paginate_button'><a href='".$primary_text.$i.$param."'>".$i."</a></li>";
		}

	}
	
	if($end < $page) {
		$data .= "<li class='paginate_button'><a href='".$primary_text.$i."/".$param."'>Next &raquo;</a></li>";
	}
	
	if($total > $perpage) {
		return  $data;
	}
}
function paging_home($total,$url = "") {
	$perpage=30;
	$limit = $_GET['limit'];
	if(!$limit)
		$limit = 1;
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
		$end = $page;
	
	$data = "";
	if(!$url)
	{
		$url=$_SERVER['PHP_SELF'];
	}
	if(substr_count($_SERVER['PHP_SELF'],"admincp")):
		$primary_text = $url.'?limit=';
	else:
		$primary_text = $url;
		
	endif;
	if($limit > 1 && $start != 1) {
		$data .= "<li><a href='".$primary_text.($start - 1).";'>&laquo; Prev</a></li>";
	}
	
	for($i = $start; $i <= $end; $i++) {
		if($limit == $i) {
			$data .= "<li class='active'><a href='".$primary_text.$i."'>".$i."</a></li>";
		}
		else {
			$data .= "<li><a href='".$primary_text.$i."'>".$i."</a></li>";
		}
		
		
	}
	
	if($end < $page) {
		$data .= "<li><a href='".$primary_text.$i."'>Next &raquo;</a></li>";
	}
	
	if($total > $perpage) {
		return  $data;
	}
}
#date====================================================================================
 function mysqldate($date)
 {
   global $month_ary2;
   if($date):
     $e=explode(",",$date);
     $d=explode(" ",$e[0]);
     $month=$month_ary2[$d[0]];
     $day=$d[1];
     $f=explode(":",$date);
     $year=substr($e[1],0,4);
     $hour=substr($f[0],-2,2);
     $minute=substr($f[1],0,2);
     $am=substr($f[1],-2,2);
     if($am=="PM"){$hour=$hour+12;}
	 if($year && $month && $day):
        return $year . "-" . $month . "-" . $day . " " . $hour . ":" . $minute . ":00";
     endif;
  endif;
 }
 
 
 function showdate($date)
 {
    global $month_ary;
    if($date):
       $e=explode("-",$date);
      //return $e[2] . " " . $month_ary[$e[1]] . "'" . $e[0];
	  return $month_ary[$e[1]] . " " . $e[2] . "," . $e[0];
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


/**
 * Custom Date Format Modifier Function
 */
function dateModifier($db_date, $format = 'm/d/Y') {
	$datetime = new DateTime( $db_date );
	
	if( !is_object( $datetime ) || $db_date == '0000-00-00' || $db_date == '0000-00-00 00:00:00' )
		return "Unknown Date";
	
	$explode_db_date = explode(' ', $db_date);
	if( count( $explode_db_date ) == 1 ) {
		$explode_format = explode( ' [', $format );
		$format = $explode_format[0];
	}
	return $datetime->format( $format );
}
/**
 * Email Validation Function
 */

#=========================================================================================================================

#Number Format====================================================================

#=========================================================================
function morePaging($total,$perpage=20,$param="",$class="lnk")
{
    $limit=$_GET['limit'];
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


function breadcrum($links)
{
	
	global $vpath;
	
	$linktext="<a href='index.php'>Home</a> > ";
	foreach($links as $l=>$v)
	{

     	if($v):
     		$linktext.="<a href='".  $v . "'>" . ucwords(strtolower($l)) . "</a> >";
     	else:
     		$linktext.=ucwords(strtolower($l)) .">";
     	endif;
     
	}
	
    return substr($linktext,0,-1);
}
 

function tz_list() {
  $zones_array = array();
  $timestamp = time();
  foreach(timezone_identifiers_list() as $key => $zone) {
    date_default_timezone_set($zone);
    $zones_array[$key]['zone'] = $zone;
    $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
  }
  return $zones_array;
}



function tempid()
{
	for ($randomNumber = mt_rand(1, 9), $i = 1; $i < 10; $i++) 
	{
    	$randomNumber .= mt_rand(0, 9);
    	
    }
    return $randomNumber;
}
function getStatus($x)
{
	global $configs;
	if($x<1)
	{
		return "<img src='".$configs['vpath']."img/off.png'>";

	}	
	elseif($x==1)
	{
		return "<img src='" .$configs['vpath'] ."img/on.png'>";
	}
	else
	{
		return $x;
	}	
}

function getActive($info)
{
	if($_GET['info']==$info)
	{
		echo "class='active'";
	}
}
function get_tiny_url($url)  {  
	$ch = curl_init();  
	$timeout = 5;  
	curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);  
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
	$data = curl_exec($ch);  
	curl_close($ch);  
	return $data;  
}

function getMonth($date)
{
	global $month_ary;
	
	$e=explode("-",$date);
	
 	$month=$month_ary[$e[1]];
	
	$v=$month ." ". $e[2];
	return $v;
}


?>