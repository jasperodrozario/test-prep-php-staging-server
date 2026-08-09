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
                    <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>
        
                    <div class="info-box-content">
                      <span class="info-box-number"><a href="<?=$stats_url[$d] ?>" class="dash-link"><?php echo (int)$v;?></a></span>
                      <span class="info-box-text"><a href="<?=$stats_url[$d] ?>" class="dash-link"><?=$d?></a></span>
                    </div>
                    
                  </div>
                 
              
            </div>
       
        
       
          <?
        }       
        ?>
    </div>
      
   <div class='col-md-12'><p style='height:400px'>Area For Chart</p></div>
 
    </section>
 

  