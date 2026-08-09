<div class="link-rate">
    <table class="table table-bordered">     
      <tr>
        <td class="bg-color <?php if($_GET['action']=="add-product"){echo "active";} ?>"><a href="<?php if($_GET['action']=="add-product"){echo "#";}else{echo $configs['admin_vpath']."product/add-product/".$_GET['product_id'];} ?>">Edit Product</a></td>
        <td class="bg-color <?php if($_GET['action']=="autoform-list" || $_GET['action']=="add-autoform"){echo "active";} ?>"><a href="<?php if($_GET['action']=="autoform-list"){echo "#";}elseif($_GET['action']=="add-autoform"){echo $configs['admin_vpath']."product/autoform-list/".$_GET['product_id'];}else{echo $configs['admin_vpath']."product/autoform-list/".$_GET['product_id'];} ?>">Extra Fields</a></td>
        <td class="bg-color <?php if($_GET['action']=="template-list" || $_GET['action']=="add-template"){echo "active";} ?>"><a href="<?php if($_GET['action']=="template-list"){echo "#";}elseif($_GET['action']=="add-template"){echo $configs['admin_vpath']."product/template-list?product_id=".$_GET['product_id'];}else{echo $configs['admin_vpath']."product/template-list?product_id=".$_GET['product_id'];} ?>">Template List</a></td>
        <td class="bg-color <?php if($_GET['action']=="add-rate"){echo "active";} ?>"><a href="<?=$configs['admin_vpath']."product/add-rate/".$_GET['product_id'] ?>">Rates</a></td>
        <td class="bg-color <?php if($_GET['action']=="delete"){echo "active";} ?>"><?php echo "<a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."product/product-list?delall_id=" . $_GET['product_id']."';}\">Delete</a>" ?></td>
      </tr>

      

      
    </table>
  </div>