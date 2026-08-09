<?php
include("includes/access.php");
include("includes/header.php");
$adminque = mysqli_query($con,"select * from food_setting");
$row_ad = mysqli_fetch_object($adminque);
//print_r($row_ad);
?>
<div class="wrapper">
    <!-- BEGIN LEFTSIDE -->
    <? include("includes/left.php"); ?>
    <!-- BEGIN RIGHTSIDE -->
    <div class="rightside bg-grey-100">
        <!-- BEGIN PAGE HEADING -->
        <div class="page-head bg-grey-100">
            <h1 class="page-title"><a href='manage_order.php'>Manage Order</a></h1>
        </div>
        <!-- END PAGE HEADING -->
        <div class="container-fluid">
            <div class="panel-body no-padding-top bg-white">
                <h4 class="color-grey-700">View Order</h4>
                <div class="table-responsive">
                    <?
                    $order = 0;
                    if ($_REQUEST['OrderID']) {
                        $OrderID = $_REQUEST['OrderID'];
                        $sql = mysqli_query($con,"SELECT * FROM " . $prev . "restaurant_payment_mode where pay_mode_id=" . $OrderID . "");
                        $order = mysqli_fetch_object($sql);
//        echo "<pre>";
//        print_r($order);
//        echo "</pre>";

                        $tax = 'No';
                        /*                         * ******************************Restaurant Tax/delivery/min order*************************************************** */
//        $sql_count=mysqli_query($con,"SELECT count(`rest_info_id`) cnt FROM ".$prev."resturant_info where rest_id=".$order->rest_id);
//	$row_count=mysqli_fetch_object($sql_count);
//	if($row_count->cnt){
//            $sql_tax=mysqli_query($con,"SELECT rest_tax FROM ".$prev."resturant_info where rest_id=".$order->rest_id);	
//            $row_tax=mysqli_fetch_object($sql_tax);	
//            $tax=$row_tax->rest_tax;
//	}
                    }
                    if ($order) {
                        ?>
                        <table id="tableListContent" width="100%" class="table table-striped table-bordered table-hover" border="0" align="center" cellspacing="1" cellpadding="4">
                            <thead><tr>
                                    <th width="30%">Item</th>
                                    <th width="20%">Qty</th>
                                    <th width="20%">Price</th>
                                    <th width="50%">Total Price</th>
                                </tr></thead><tbody>
                                <?php
                                $items = json_decode($order->all_order_details);
                                /*
                                  echo "<pre>";
                                  print_r($items);
                                  echo "</pre>";
                                 */

                                if (!$items) {
                                    $items = array();
                                }

                                $userDetails = json_decode($order->all_userdetails);
                                if (!$userDetails) {
                                    $userDetails = array();
                                }
//                                echo "<pre>";
//                                print_r($userDetails);
//                                echo "</pre>";

                                foreach ($items as $val) {
                                    $productAddons = isset($val->addonsProduct) ? $val->addonsProduct : array();
                                    //echo "<pre>";
                                    //print_r($productAddons);
                                    //diue();
                                    ?>

                                    <tr>
                                        <td><?php echo $val->product_name ?></td>
                                        <td><?php echo  $val->product_qty ?></td>
                                        <td><?php echo  $val->product_price ?></td>
                                        <td><?php echo  $val->product_price * $val->product_qty ?></td>
                                    </tr>
                                    <?php foreach ($productAddons as $addons) { ?>
                                        <tr>
                                            <td colspan='3' style='text-align: right;'><?php echo  $addons->addons_name ?></td>
                                            <td><?php echo  $addons->addons_price ?></td>
                                        </tr>

                                    <?php } ?>
                                <?php } ?>
                                <!--
        <tr >
                <td colspan="3">Total</td>
                <td><?php echo  $amount = $order->total_amt ?></td>
        </tr>
                                -->
                                <tr >
                                    <td colspan="3">Tax (<?php echo  $tax ?>%)</td>
                                    <td>  $<?php echo $row_ad->maintenance_desc; ?></td>
                                </tr>
                                <tr >
                                    <td colspan="3">Delivery Charges</td>
                                    <td>$<?php echo $row_ad->maintenance_msg; ?> <?php //$shipping_charges=$order->shipping_charges ?></td>
                                </tr>
                                <tr >
                                    <td colspan="3">Final Total</td>
                                    <td>$<?php echo  $amount + $vat + $shipping_charges; ?></td>
                                </tr>
                            </tbody></table>

                        <h4>Order Details</h4>
                        <form>
                            <table id="tableListContent" width="100%" class="table table-striped table-bordered table-hover" border="0" align="center" cellspacing="1" cellpadding="4">

                                <tr><td>
                                        <span class="addPageRightFont">Order Number</span>
                                    </td><td>				
                                        <span class="value"><?php echo  'ORD' . $order->txn_id ?></span>
                                    </td></tr>

                                <tr><td>
                                        <span class="addPageRightFont">Delivery Type</span>
                                    </td><td>				
                                        <span class="value"><?php echo  $order->delivery_type ?></span>
                                    </td></tr>
                                <!--
                                <tr><td>Delivery Time</td><td>				
                                        <span class="value"><?php echo  $order->Ord_time . $order->delivery_time . $order->delivery_status ?></span>
                                </td></tr>
                                -->
                                <tr><td>
                                        <span class="addPageRightFont">Delivery Status</span>
                                    </td><td>				
                                        <span class="value"><?php echo  $order->delivery_status ?></span>
                                    </td></tr>

                                <tr><td>
                                        <span class="addPageRightFont">Order Date</span>
                                    </td><td>				
                                        <span class="value"><?php //date("Y",strtotime($order->payment_date)) ?>
                                            <?php echo  $order->payment_date ?>
                                        </span>
                                    </td></tr>

                                <!--CUSTOMER DETAILS-->
                                <?php
                                //$query = "SELECT * FROM food_user WHERE user_id=$order->userid";
                                //$resource = mysqli_query($con,$query);
                                //$result = mysqli_fetch_object($resource);
                                ?>

                                <tr><td colspan=2><h4><b>Customer Details</b></h4></td></tr> 
                                <tr><td><span class="addPageRightFont">Customer Name</span></td><td>				
                                        <span class="value"><?php echo  $userDetails->user_fname . " " . $userDetails->user_lname ?></span>
                                    </td></tr>

                                <tr>
                                    <td><span class="addPageRightFont">Customer Address</span></td><td>				
                                        <span class="value"><?php echo  $userDetails->address ?></span><br>
                                        <span class="value"><?php echo  $userDetails->land_mark ?></span><br>
                                    </td>
                                </tr>
                                
                                
                                <tr>
                                    <td><span class="addPageRightFont">Customer Comment</span></td><td>				
                                        <span class="value"><?php echo  $userDetails->comment ?></span>
                                    </td>
                                </tr>

                                <tr><td><span class="addPageRightFont">Customer Phone Number</span></td><td>				
                                        <span class="value"><?php echo  $userDetails->user_phone ?></span>
                                        </div>
                                        <!--PAYMENT DETAILS-->
                                <tr><td colspan=2><h4><b>Payment Details</b></h4></td></tr>
                                <tr>
                                    <td><span class="addPageRightFont">Payment Method</span></td>
                                    <td><span class="value"><?php echo  $order->pay_type ?></span></td>
                                </tr>
                                <!-- <tr>
                                    <td><span class="addPageRightFont">Payment Status</span></td>
                                    <td><span class="value"><?php echo  $order->payment_status ?></span></td>
                                </tr> -->
                                <tr><td colspan=2 align=center><input type='button' onlclick="javascript:window.location.href='manage_order.php'" class="btn btn-danger" value='Cancel'>
                                    </td></tr></table></form>
                    <?php } else { ?>
                        No Result Found.
                    <?php } ?>
                </div>
            </div>
            <!-- BEGIN FOOTER -->
            <? include("includes/footer-top.php"); ?>
            <!-- END FOOTER -->
        </div><!-- /.container-fluid -->
    </div><!-- /.rightside -->
</div><!-- /.wrapper -->
<!-- END CONTENT -->
<? include("includes/index_footer.php"); ?>
	

