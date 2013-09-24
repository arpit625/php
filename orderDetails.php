<?php require_once('Connections/online_order.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_rsUserInfo = "-1";
if (isset($_GET['url_user_id'])) {
  $colname_rsUserInfo = $_GET['url_user_id'];
}

$colname_rsOrderDetails = "-1";
if (isset($_GET['url_mainorder_id'])) {
  $colname_rsOrderDetails = $_GET['url_mainorder_id'];
}
if (isset($_GET['url_status_id'])) {
  $colname_Status = $_GET['url_status_id'];
}
mysql_select_db($database_online_order, $online_order);
// $query_rsUserInfo = sprintf("SELECT order_time,mainorder_id, status_deliver, status_pickup, status_dineup, first_name, last_name, phone, add1, apt_no, city, zip, order_total, coupon_discount, tax, delivery_charge, order_status, payment_mode FROM orders WHERE user_id = %s AND mainorder_id = %s" , GetSQLValueString($colname_rsUserInfo, "int"),GetSQLValueString($colname_rsOrderDetails, "int"));
$query_rsUserInfo = sprintf("SELECT * FROM orders WHERE user_id = %s AND status ='%s' AND mainorder_id = %s" , GetSQLValueString($colname_rsUserInfo, "int"),$colname_Status,GetSQLValueString($colname_rsOrderDetails, "int"));
$rsUserInfo = mysql_query($query_rsUserInfo, $online_order) or die(mysql_error());
$row_rsUserInfo = mysql_fetch_assoc($rsUserInfo);
$totalRows_rsUserInfo = mysql_num_rows($rsUserInfo);



mysql_select_db($database_online_order, $online_order);
// $query_rsOrderDetails = sprintf("SELECT temp_order_id, extra_price, price, item_name, extra_items_name,selected_options_name FROM cart_order_items WHERE order_id = %s", GetSQLValueString($colname_rsOrderDetails, "int"));
$query_rsOrderDetails = sprintf("SELECT * FROM cart_order_items WHERE order_id = %s", GetSQLValueString($colname_rsOrderDetails, "int"));
$rsOrderDetails = mysql_query($query_rsOrderDetails, $online_order) or die(mysql_error());
$row_rsOrderDetails = mysql_fetch_assoc($rsOrderDetails);
$totalRows_rsOrderDetails = mysql_num_rows($rsOrderDetails);

// Select Status
// $query_update_status = sprintf("SELECT temp_order_id, extra_price, price, item_name, extra_items_name,selected_options_name FROM cart_order_items WHERE order_id = %s", GetSQLValueString($colname_rsOrderDetails, "int"));
// $rsOrderDetails = mysql_query($query_update_status, $online_order) or die(mysql_error());
// $row_rsOrderDetails = mysql_fetch_assoc($rsOrderDetails);


if (isset($_GET['orderStatus'])) {
  $orderStatus = $_GET['orderStatus'];
// $updateSQL = "UPDATE order_updt_status SET update_status= '$orderStatus' WHERE status = '$colname_rsUserInfo' AND mainorder_id = '$colname_rsOrderDetails'";
$updateSQL = sprintf("UPDATE order_updt_status SET update_status= %s WHERE status = %s AND mainorder_id = %s",$orderStatus,$colname_rsUserInfo,$colname_rsOrderDetails);

$Result1 = mysql_query($updateSQL, $online_order) or die(mysql_error());

}

// Order Update Status ( new/ pending/complete )
$query_rsOrderStatus = sprintf("SELECT * FROM order_updt_status WHERE status = %s AND mainorder_id = %s",$colname_rsUserInfo,$colname_rsOrderDetails);
$rsOrderStatus = mysql_query($query_rsOrderStatus, $online_order) or die(mysql_error());
$row_orderStatus = mysql_fetch_assoc($rsOrderStatus);
// $totalRows_rsOrderStatus = mysql_num_rows($rsOrderStatus);
$update_status = $row_orderStatus['update_status'];


// New , Pending , Complete Order Count Details
$query_newOrder = sprintf("SELECT * FROM order_updt_status WHERE update_status = 1 AND status='$colname_rsUserInfo'");
$query_pendingOrder = sprintf("SELECT * FROM order_updt_status WHERE update_status = 2 AND status='$colname_rsUserInfo'");
$query_completeOrder = sprintf("SELECT * FROM order_updt_status WHERE update_status = 3 AND status='$colname_rsUserInfo'");
$count_newOrder = mysql_query($query_newOrder, $online_order) or die(mysql_error());
$count_pendingOrder = mysql_query($query_pendingOrder, $online_order) or die(mysql_error());
$count_completeOrder = mysql_query($query_completeOrder, $online_order) or die(mysql_error());
$totalRows_newOrder = mysql_num_rows($count_newOrder);
$totalRows_pendingOrder = mysql_num_rows($count_pendingOrder);
$totalRows_completeOrder = mysql_num_rows($count_completeOrder);


// Pizza Info

$query_rsPizzaDetails = sprintf("SELECT * FROM cart_order_items WHERE order_id = %s AND item_name='pizza'", $colname_rsOrderDetails);
$rsPizzaDetails = mysql_query($query_rsPizzaDetails, $online_order) or die(mysql_error());
$row_rsPizzaDetails = mysql_fetch_assoc($rsPizzaDetails);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Online Order Updates</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
      <![endif]-->
    </head>
    <body>
      <div class="container-fluid">
        <div class="row-fluid">
          <div class="well">


            <div class="row-fluid">
              <a href="viewOrder.php" class="btn btn-large" type="button"><i class="icon-home"> </i> Home</a>
       <a href="viewOrder.php" class="btn btn-large" type="button"><i class="icon-backward"> </i> Back</a>
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <span class="badge badge-info"><?php echo $totalRows_newOrder; ?></span> New / 
         <span class="badge badge-warning"><?php echo $totalRows_pendingOrder; ?></span> Pending / 
         <span class="badge badge-success"><?php echo $totalRows_completeOrder; ?></span> Complete 
        <a href="<?php echo $logoutAction ?>">
               <button class="btn btn-large pull-right" type="button"><i class="icon-off"> </i> Sign Out</button>
              </a> 
             </div>



          <br>

                    <div class="row-fluid">
            <div class="span4">
             <strong> Order No. : </strong>
              <?php echo $row_rsUserInfo['mainorder_id']; ?> 
              <br>
             <strong> Order Time :</strong>
               <?php echo $row_rsUserInfo['order_time']; ?>
            </div>

            <div class="span4">
             <strong> Delivery Type :</strong>

                                    <?php 
          if($row_rsUserInfo['status_deliver'] == "yes")
          echo "Home Delivery";
        if($row_rsUserInfo['status_pickup'] == "yes")
          echo "Pick Up";
        if($row_rsUserInfo['status_dineup'] == "yes")
          echo "Dine Up";
         ?>
             
              <br>
             <strong> Payment Mode :</strong>
               <?php echo $row_rsUserInfo['payment_mode']; ?>
            </div>
          </div>

          <br>

          <table class="table table-striped table-bordered">
            <tr>
              <th>S. No.</th>
              <th>Item Name</th>
              <th>Item Desc.</th>
              <th>Extras</th>
              <th>Extras Total</th>
              <th>Item Price</th>
              <th>Total</th>
            </tr>
            <?php $i = 1; do { ?>
  <tr>
    <td><?php echo $i++; ?></td>
    <td>
<?php echo $row_rsOrderDetails['item_name']; ?>
    </td>
    <td><?php echo $row_rsOrderDetails['selected_options_name']; ?></td>
    <td><?php echo $row_rsOrderDetails['extra_items_name']; ?></td>
    <td><?php echo $row_rsOrderDetails['extra_price']; ?></td>
    <td><?php echo $row_rsOrderDetails['price']; ?></td>
    <td><?php echo $row_rsOrderDetails['price']+$row_rsOrderDetails['extra_price']; ?></td>
  </tr>
  <?php } while ($row_rsOrderDetails = mysql_fetch_assoc($rsOrderDetails)); ?>


          </table>

                    <!-- Pizza Details -->

                      <table class="table table-striped table-bordered">
              <tr>
              <th>S. No.</th>
                <th>Pizza Name</th>
                <th>Pizza Type</th>
                <th>Option</th>
                <th>Size</th>
                <th>Side A</th>
                <th>Side B</th>
                <th>Total</th>
              </tr>
              <?php $i = 1; do { ?>
              <tr>
              <td><?php echo $i++; ?></td>
                <td><?php echo $row_rsPizzaDetails['pizzaname']; ?></td>
                <td><?php echo $row_rsPizzaDetails['pizzatype']; ?></td>
                <td><?php echo $row_rsPizzaDetails['option']; ?></td>
                <td><?php echo $row_rsPizzaDetails['size']; ?></td>
                <td><?php echo $row_rsPizzaDetails['toppingsideA']; ?></td>
                <td><?php echo $row_rsPizzaDetails['toppingsideB']; ?></td>
                <td><?php echo $row_rsPizzaDetails['price']; ?></td>
             

              </tr>  
               <?php } while ($row_rsPizzaDetails = mysql_fetch_assoc($rsPizzaDetails)); ?>         
            </table>

<div class="row-fluid">
<div class="span8"
          <br>
          <h2>Customer Details</h2>
          <div class="row-fluid">
            <div class="span6">
            <dl class="dl-horizontal">
              <dt>Name :</dt>
              <dd><?php echo $row_rsUserInfo['first_name'] . " " . $row_rsUserInfo['last_name']; ?></dd>

              <dt>Street Address :</dt>
              <dd><?php echo $row_rsUserInfo['add1']; ?></dd>

              <dt>Apt No. :</dt>
              <dd><?php echo $row_rsUserInfo['apt_no']; ?></dd>

              <dt>City :</dt>
              <dd><?php echo $row_rsUserInfo['city']; ?></dd>

              <dt>Zip :</dt>
              <dd><?php echo $row_rsUserInfo['zip']; ?></dd>

              <dt>Contact Number :</dt>
              <dd><?php echo $row_rsUserInfo['phone']; ?></dd>

            </dl>
            </div>
            <!-- column 2  ends here -->
          </div>
          <!-- customer details row ends here -->


<form class="form-inline" method="adminOrderDetails.php">
<fieldset>
  <label class="control-label"><strong>Order Status : </strong></label>
    <select id="orderStatus" name="orderStatus" class="input-xlarge">
      <option value="1" <?php if($update_status == 1) echo "selected"; ?>>New</option>
      <option value="2" <?php if($update_status == 2) echo "selected"; ?>>Pending</option>
      <option value="3" <?php if($update_status == 3) echo "selected"; ?>>Complete</option>
    </select>
<input type="hidden" name="url_mainorder_id" value="<?php echo $colname_rsOrderDetails; ?>">
<input type="hidden" name="url_user_id" value="<?php echo $colname_rsUserInfo; ?>">
<input id="orderChange" name="orderChange" class="btn btn-success" type="submit" value="Change">

</fieldset>
</form>
</div>

<div class="span3 pull-right">
  <table class="table table-bordered table-striped table-hover">
  <tr>
    <td>Sub Total</td>
    <td><?php echo $row_rsUserInfo['subtotal']; ?></td>
  </tr>
  <tr>
    <td>Tax</td>
    <td><?php echo $row_rsUserInfo['tax']; ?></td>
  </tr>
  <tr>
    <td>Coupoun Discount</td>
    <td><?php echo $row_rsUserInfo['coupon_discount']; ?></td>
  </tr>
  <tr>
    <td>Combo Discount</td>
    <td><?php echo $row_rsUserInfo['combo_dis']; ?></td>
  </tr>
  <tr>
    <td>Delivery Charges</td>
    <td><?php echo $row_rsUserInfo['delivery_charge']; ?></td>
  </tr>
  <tr>
    <td><strong>Total</strong></td>
    <td><strong><?php echo $row_rsUserInfo['order_total']; ?></strong></td>
  </tr>

  </table>
</div>
</div>


        </div>
        <!-- Class well ends here -->
      </div> 
      <!-- class row-fluid ends here -->
    </div>
    <!-- Class container-fluid ends here -->

  </body>
  </html>
<?php
mysql_free_result($rsUserInfo);

mysql_free_result($rsOrderDetails);
?>
