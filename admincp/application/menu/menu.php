<?
include("../../common/access.php");

include("../../common/autoload.php");
if(!$_GET['action'])
{
	$_GET['action']='list';
}

 
switch($_GET['action'])
{

	case "menu-allot":
	
	
		$data['page_title']="Admin Menu Management";
		$data['page_subtitle']="Menu Allot to Tutor";

		$data['breadcrumb']=array("Menu"=>"");
	 
	    $data['db_list']= getQuery('adminmenu','*',array("status"=>"Y","parent_id"=>0),"name");
	    
	    $submenu=[];
	    foreach($data['db_list'] as $d)
	    {
	    	$submenu[$d['id']]=getQuery('adminmenu','*',array("status"=>"Y","parent_id"=>$d['id']),"name");
	    
	    }	
	    
        $data['submenu']=$submenu;
        
        $prog_id=[];
        $proghead_id=[];
        
        if($_GET['user_id'])
        {
        	$tutor_name=getFldNameById('tutor','concat(first_name," ",last_name)',array("user_id"=>$_GET['user_id']));
        	
        	$data['page_subtitle']="Menu Allot to Tutor : ".$tutor_name;
        	
        	$data['grant_list']= getQuery('admin_grant','*',array("user_id"=>$_GET['user_id']));
        	 
        	foreach($data['grant_list'] as $d)
        	{
        	 	
        	 	$prog_id[]=$d['prog_id'];
        	 	
        	 	$proghead_id[]=$d['proghead_id'];
        	}
        	
        }	
        
        $data['prog_id']=$prog_id;
        $data['proghead_id']=$proghead_id;
         
	    if($_GET['del_id'])
		{

			$r=deleteData("admin_grant",array("id"=>$_GET['del_id']));
		
			$data['msg']=$configs['delete_msg'];
		}
	
	break; 
	case "list":
 
		$data['page_title']="Admin Menu Management";
		$data['page_subtitle']="Menu List";
		$data['breadcrumb']=array("Admin Menu Management"=>$configs['admin_vpath'] ."menu/list",
     	"Add/Modify adminmenu"=>"");  

	 
		
		$data['btn']="<a href='".$configs['admin_vpath']."menu/add' class='btn btn-info  float-right'><i class='zmdi zmdi-plus'></i> Create Menu</a>";
        $data['db_list']=getQuery("adminmenu","*",array("parent_id"=>0),"ord");
        
        $sub_menu=[];$sub_menu_total=[];
        foreach($data['db_list'] as $d)
        {
        	$sub_menu_total[$d['id']]=getTotalRow("adminmenu",array("parent_id"=>$d['id']));
        	$sub_menu[$d['id']]=getQuery("adminmenu","*",array("parent_id"=>$d['id']));
        }
        $data['sub_menu']=$sub_menu;
		$data['sub_menu_total']=$sub_menu_total;
	break;
	case"add":
	case"edit":
	case"add-sub":
	 	$data['page_title']="Admin Menu Management";
        $data['page_subtitle']="ADD | Edit Menu";
		$data['breadcrumb']=array("Admin Menu Management"=>$configs['admin_vpath']."menu/list"," Admin Menu List"=>"");
 
        $data['btn']='<a href="' .$configs['admin_vpath'] .'menu/add" class="btn btn-info  float-right"><i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i> Create Menu</a>';

	    if($_GET['id']):
		    $data['db_value']=getDataByWhere("adminmenu","*",array("id"=>$_GET['id']));
		    $data['id']=$_GET['id'];
		    $_GET['parent_id']=$data['db_value']['parent_id'];
		endif;
		
		if($_POST)
		{
			
		    $_POST['ord']=(int)$_POST['ord'];
		    $_POST['parent_id']=(int)$_POST['parent_id'];
		    
		    if(!$_POST['id'])
		    {
			
		        unset($_POST['id']);
		        $r=insertTable("adminmenu",$_POST);
		         $data["id"]=$r;
		    }   
		    else
		    {
			
		        $r=updateTable("adminmenu",$_POST,array("id"=>$_POST['id']));
		        $data["id"]=$_POST['id'];
		    }
		    if($r)
		    {
			
		        $data['msg']=str_replace("{table}","Menu ",$configs['success_msg']);
		    }
		    else
		    {
				$data['msg']=$configs['error_msg'];
		  
			}
		   
	    }  
	    
	    $data['form_fields']=Array
		(
		     
		    Array('name' => 'table','type' => 'hidden','value' => "adminmenu"),
		    Array('name' => 'auto_id','type' => 'hidden','value' => "id"),
		    Array('name' => 'file','type' => 'hidden','value' => "icon"),
		   
		    Array('name' => 'id','type' => 'hidden','value' => (int)$_GET['id']),
		    Array('name' => 'parent_id','type' => 'hidden','value' => (int)$_GET['parent_id']),
		   
		    Array('name' => 'name','label' => 'Menu Name','type' => 'text','value' => $data['db_value']['name'],'required' => 'required'),
		    Array('name' => 'url','label' => 'Menu Url','type' => 'text','value' => $data['db_value']['url'],'required' => 'required'),
		    Array('name' => 'ord','label'=>'Display Order','type' => 'number','value'=>$data['db_value']['ord'],'required' => 'required'), 
		    Array('name' => 'icon','label' => 'Icon','type' => 'text','value' => $data['db_value']['icon'],'required' => ''),
		    Array('name' => 'status','label' => 'Status','type' => 'radio','value' => $data['db_value']['status'],'required' => '',"option"=>array("Y"=>"Online","N"=>"Offline")),
		    
		    Array('name' => 'submit','type' => 'submit','value' => "Submit")  
		);
	    
	break;
	
	case"delete":	 
	    
	    deleteData("adminmenu",array("parent_id"=>$_GET["del_id"]));
	    
	    deleteData("adminmenu",array("id"=>$_GET["del_id"]));
	    
	    $data['msg']=str_replace("{table}","Menu ",$configs['delete_msg']);
	  	  
	   
        header("location:" . $configs['admin_vpath'] . "menu/list");
	break;	
		
	
} 

$data['action']=$_GET['action'];

echo template($data);
?>
