<?php 
include("includes/header.php");
$data=getDataByWhere("contents","*","seo_url='".$_GET['seo_url']."'");

?>

        <section id="page-content" >
            <!--Start Page Heading-->
            <div class="page-heading bg-cover" >
                <div class="overlay"></div>
                <div class="page-heading-content text-center">
                    <h2 class="font-700 text-uppercase color-white"><?php echo ucwords($data['cont_title']);?></h2>
                    <ol class="breadcrumb breadcrumb1" >
                        <li><a href="<?=$vpath?>" class="icofont icofont-home1"><i class="fa fa-home"></i></a></li>
                        <li class="active"><?php echo ucwords($data['cont_title']);?></li>
                    </ol>
                  
                </div>
            </div>
             
            <div class="inner-body">
             <div class="container">
               <div class="row">
                   <div class="midle-conarea">
                      <div class="col-md-7">
                        <h2><?php echo Ucwords($data['cont_title']);?></h2>
                        <p><?php 

    					echo html_entity_decode($data['contents']);
    					?></p>
                      </div>
                      
                      <div class="col-md-5">
                         <div class="bigimage">
                           <img class="img-responsive" src="<?=$vpath.$data['img']?>" alt="about-imge">
                         </div>	
                      </div>
                      
                   </div>
               </div>
             </div>
           </div>
            <section>

<?include("includes/footer.php");?>