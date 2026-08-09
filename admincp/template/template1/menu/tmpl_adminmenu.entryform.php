<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>ADMIN</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?=$configs['admin_vpath'];?>"><i class="zmdi zmdi-home"></i> NFIN</a></li>
                   
                        <li class="breadcrumb-item active"><?=ucwords(str_replace("-"," ",$_GET['action']));?></li>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                    <a href="profile-edit" class="btn btn-info btn-icon float-right"><i class="zmdi zmdi-edit"></i></a>
                </div>
            </div>
        </div> 
        <div class="container-fluid">
            <div class="row clearfix">
                
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="body">
                         	<div class="table-responsive" style='overflow-x: hidden'>
                         	<form method="post" action="<?=basename($_SERVER['PHP_SELF'],".php");?>" role='form' enctype="multipart/form-data">
							<input type="hidden" name="parent_id" value='<?=$_GET['parent_id']?>'>
							<input type="text" name="id" value="<?=$_GET['id']?>">
							 
					 
							<table width="100%" border="0" align="center" cellpadding=3 cellspacing=1 class="table table-bordered">

							<tr class="vertical-middle" ><td valign=top align="left">Name</td><td align="left"><input type="text"  name="name" value="<?=$db_value['name']?>" class="form-control"></td></tr>
							<tr class="vertical-middle" ><td valign=top align="left">Url</td><td align="left"><input type="text"  name="url" value="<?=$db_value['url']?>" class="form-control"></td></tr>
							<tr class="vertical-middle" ><td valign=top align="left">Icon</td><td align="left"><input type="text"  name="icon" value="<?=$db_value['icon'];?>" class="form-control"></td></tr>
							<tr class="vertical-middle" ><td valign=top align="left">Menu Order</td><td align="left"><input type="text" size=5 name="ord" value="<?=$db_value[ord]?>" class="form-control"> [Example. 2]</td></tr>
							<tr  class="vertical-middle"><td align="left">Status</td><td align="left"><input type=radio name=status value="Y" <?if($db_value["status"]=="Y"){echo" checked";}?> >Online <input type=radio name=status value="N" <?if($db_value["status"]=="N"){echo" checked";}?>> Offline </td></tr>
							<tr><td></td><td><input type="submit" name="submit" value="Update" class='btn bg-green-500 color-white btn-dark padding-left-40 padding-right-40'>&nbsp;&nbsp;<?if($_GET['id']){?><input type="submit" name="DELT" value="Delete" class='btn danger'><?}?></td></tr>
							</table><br>
							</form>
							 </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>