<div class="link-customer">
    <table class="table table-bordered">     
      <tr>
        <td class="bg-color <?php if($_GET['action']=="add-customer"){echo "active";} ?>"><a href="<?php if($_GET['action']=="add-customer"){echo "#";}else{echo $configs['admin_vpath']."customer/add-customer/".$_GET['user_id'];} ?>">Edit Customer</a></td>
        <td class="bg-color <?php if($_GET['action']=="order-list" || $_GET['action']=="view-order"){echo "active";} ?>"><a href="<?php if($_GET['action']=="order-list"){echo "#";}elseif($_GET['action']=="view-order"){echo $configs['admin_vpath']."customer/order-list/".$_GET['user_id'];}else{echo $configs['admin_vpath']."customer/order-list/".$_GET['user_id'];} ?>">Orders</a></td>
        
        
        <td class="bg-color <?php if($_GET['action']=="delete"){echo "active";} ?>"><?php echo "<a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."customer/customer-list?delall_id=" . $_GET['user_id']."';}\">Delete</a>" ?></td>
      </tr>

      

      
    </table>
  </div>