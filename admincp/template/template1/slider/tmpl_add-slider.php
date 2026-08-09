<ul class="nav nav-tabs">
	<?
	$i=0;
	foreach($langs as $l)
	{
		?>
		
		<li <?if($l['lang_id']==$_GET['lang_id']){echo"class='active'";}?>><a  href="add-slider?id=<?=$_GET['id']."&lang_id=".$l['lang_id'];?>"><?=$l['name']?></a></li>
		<?
		$i++;
	}
	?>	
		
	</ul>

  
	<div class="tab-content">
	<div id="home" class="tab-pane fade in active">
    <!--  <div id='response'></div> -->

 <form method='post' id='form' enctype="multipart/form-data" style='padding-top:20px'>
<input type="hidden"   name='action'  value="slider"> 


<input type='hidden' name="lang_id" value="<?=$_GET['lang_id'];?>">
<input type='hidden' name="id" value="<?=$_GET['id'];?>">


<div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Title</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input class='form-control' type=text name="title" value="<?php echo $db_value['title']?>" required>
      </div>
                                      
     
    </div>

    <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Description</label>
        </div>
        <div class="col-md-9 col-lg-9">
         <textarea name='description' id='description' class="form-control" " style="width:100%;height:100px;"><?=html_entity_decode($db_value['description']);?></textarea>
      </div>
                                      
     
    </div>


    <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Image</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input type="file" class='form-control' name="background" value="" >
        <?if($db_value['background'])
        {
        ?>
          <br>
          <img src="<?php echo $configs['vpath'].$db_value['background']; ?>" width="100">
        <?}?>
      </div>
                                      
     
    </div>

    <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Link</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input class='form-control' type=text name="link" value="<?php echo $db_value['link']?>" required>
      </div>
                                      
     
    </div>
    <?php
    if($_GET['lang_id']==1){?>
    <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Set Display Order</label>
        </div>
        <div class="col-md-9 col-lg-9">
         <input class='form-control' type=text name="ord" value="<?php echo $db_value['ord']?>" required>
      </div> 
    </div> 
     <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Status</label>
        </div>
        <div class="col-md-9 col-lg-9">
           <select name='status' class='form-control'>
               <option value=''>Select </option>
               <option value="Y" <?php   if($db_value['status']=="Y"){echo"selected";}?>>Active</option>
                <option value="N" <?php   if($db_value['status']=="N"){echo"selected";}?>>Inactive</option>
               
              
          </select>
      </div>
                                      
     
    </div>
  <?}?>
 <div class="row row-input-button">      

        <div class="col-md-3 col-lg-3">
        <label for="">&nbsp;</label>
        </div>
        <div class="col-md-9 col-lg-9">
                <input type='submit' class='btn btn-success' name='submit' value='Save'  > <input type='button' class='btn btn-danger'  value='Cancel' onclick="javascript:history.back();">
      </div>
                                      
     
    </div>





   
 </form>
        </div></div>
 