<?include($configs['apath'] .$configs['admin_dir']."/template/".$configs['admin_template']."/includes/tmpl_breadcrumb.php"); ?>


    <!-- Main content -->
    
     <section class="content">
      
        <div class="row">
        <?
        $j=0;
        foreach($stats as $d=>$v)
        {
          $j++;
           ?>
           
               <div class="col-12 col-md-6 col-lg-3">
                
                      
                  <div class="info-box">
                    <span class="info-box-icon bg-aqua"><img src='<?=$configs['admin_vpath']?>img/<?=$v['icon']?>'></span>
        
                    <div class="info-box-content">
                      <span class="info-box-number"><a href="<?=$v['url'] ?>" class="dash-link"><?php echo $v['total'];?></a></span>
                      <span class="info-box-text"><a href="<?=$v['url'] ?>" class="dash-link"><?=$d?></a></span>
                    </div>
                    
                  </div>
                 
              
            </div>
       
        
       
          <?
        }       
        ?>
    </div>
    </section>
    <section class="content">
     
 
      
   <div class='col-md-12'><p style='height:400px'></p></div>
 
    </section>
 

  