<div class="content-wrapper">
    <?
 
    if($breadcrumb)  
    {?>  
        <section class="content-header">
          <h1>
            <?=$page_title;?>
          </h1>
          <ol class="breadcrumb"> 
            <li class="breadcrumb-item"><a href="<?=$configs['admin_vpath']."account/dashboard";?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
            <?
            foreach($breadcrumb as $name=>$url)
            {
                $class='';
                if(!$url)
                {
                    echo'<li class="breadcrumb-item active"> ' . $name .'</li>';
                }
                else
                {
                    echo'<li class="breadcrumb-item"><a href="' .$url .'">' . $name .'</a></li>';
                }           
            }
            ?> 
          </ol>
        </section>
    <?}?> 

    <section class="content">
        <div class="row">
            <div class="col-12">
         
                <div class="box">
                        <?
                        if($page_subtitle || $btn)
                        {
                            ?>
                             <div class="box-header with-border">
                                <div class='row'><div class='col-md-6 pull-left'><h3 class="box-title"><?=$page_subtitle;?></h3></div> 
                                <div class='col-md-6'><span class='pull-right'><?=$btn;?></span> </div></div>
                            </div>
                            <?
                        } 
                        ?>   
                       

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id='response'><?if($msg){echo $msg;}?></div>