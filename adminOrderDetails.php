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
$MM_authorizedUsers = "1";
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
mysql_select_db($database_online_order, $online_order);
$query_rsUserInfo = sprintf("SELECT order_time, status_deliver, status_pickup, status_dineup, first_name, last_name, phone, add1, apt_no, city, zip, order_total, coupon_discount, tax, delivery_charge, order_status, payment_mode FROM orders WHERE user_id = %s AND mainorder_id = %s" , GetSQLValueString($colname_rsUserInfo, "int"),GetSQLValueString($colname_rsOrderDetails, "int"));
$rsUserInfo = mysql_query($query_rsUserInfo, $online_order) or die(mysql_error());
$row_rsUserInfo = mysql_fetch_assoc($rsUserInfo);
$totalRows_rsUserInfo = mysql_num_rows($rsUserInfo);



mysql_select_db($database_online_order, $online_order);
$query_rsOrderDetails = sprintf("SELECT temp_order_id, extra_price, price, item_name, extra_items_name,selected_options_name FROM cart_order_items WHERE order_id = %s", GetSQLValueString($colname_rsOrderDetails, "int"));
$rsOrderDetails = mysql_query($query_rsOrderDetails, $online_order) or die(mysql_error());
$row_rsOrderDetails = mysql_fetch_assoc($rsOrderDetails);
$totalRows_rsOrderDetails = mysql_num_rows($rsOrderDetails);

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
            <h1>Daily Orders</h1>
            <br>
            <div class="row-fluid">
              <div class="span5 offset1">

             </div>
             <div class="span6 pull-right">
               <a href="<?php echo $logoutAction ?>">
               <button class="btn btn-large pull-right" type="button"><i class="icon-off"> </i> Sign Out</button>
              </a>             </div>

          </div>

          <br><br>

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
    <a href="pizzaInfo.php?url_temp_order_id=<?php echo $row_rsOrderDetails['temp_order_id']; ?>"><?php echo $row_rsOrderDetails['item_name']; ?></a>
    </td>
    <td><?php echo $row_rsOrderDetails['selected_options_name']; ?></td>
    <td><?php echo $row_rsOrderDetails['extra_items_name']; ?></td>
    <td><?php echo $row_rsOrderDetails['extra_price']; ?></td>
    <td><?php echo $row_rsOrderDetails['price']; ?></td>
    <td><?php echo $row_rsOrderDetails['price']+$row_rsOrderDetails['extra_price']; ?></td>
  </tr>
  <?php } while ($row_rsOrderDetails = mysql_fetch_assoc($rsOrderDetails)); ?>


          </table>

          <h4>
            Tax - <?php echo $row_rsUserInfo['tax']; ?> , 
            Coupon Discount - <?php echo $row_rsUserInfo['coupon_discount']; ?> , 
            Delivery Charges - <?php echo $row_rsUserInfo['delivery_charge']; ?> , 
            Total - <?php echo $row_rsUserInfo['order_total']; ?>
          </h4>

          <br>
          <h2>Customer Details</h2>
          <div class="row-fluid">
            <div class="span4">
            <dl class="dl-horizontal">
              <dt>Name :</dt>
              <dd><?php echo $row_rsUserInfo['first_name'] . " " . $row_rsUserInfo['last_name']; ?></dd>

              <dt>Street Address :</dt>
              <dd>
              <?php echo $row_rsUserInfo['apt_no']; ?> , <?php echo $row_rsUserInfo['add1']; ?>
              <br>
              <?php echo $row_rsUserInfo['city']; ?> - <?php echo $row_rsUserInfo['zip']; ?>
              </dd>

              <dt>Delivery Type :</dt>
              <dd>

                    <?php 
          if($row_rsUserInfo['status_deliver'] == "yes")
          echo "Home Delivery";
        if($row_rsUserInfo['status_pickup'] == "yes")
          echo "Pick Up";
        if($row_rsUserInfo['status_dineup'] == "yes")
          echo "Dine Up";
         ?>

              </dd>

              <dt>Order Time :</dt>
              <dd><?php echo $row_rsUserInfo['order_time']; ?></dd>                            

            </dl>
            </div>


            <div class="span4">
            <dl class="dl-horizontal">
              <dt>Contact Number :</dt>
              <dd><?php echo $row_rsUserInfo['phone']; ?></dd>

              <dt>Payment Type :</dt>
              <dd><?php echo $row_rsUserInfo['payment_mode']; ?></dd>                           

            </dl>
            </div>
            <!-- column 2  ends here -->


          </div>
          <!-- customer details row ends here -->


<form class="form-horizontal">
<fieldset>


<!-- Select Basic -->
<div class="control-group">
  <label class="control-label">Order Status</label>
  <div class="controls">
    <select id="orderStatus" name="orderStatus" class="input-xlarge">
      <option value="1">New</option>
      <option value="2">Pending</option>
      <option value="3">Complete</option>
    </select>

<a href="updateOrderStatus.php?url_mainorder_id=<?php echo $colname_rsOrderDetails; ?>&url_user_id=<?php echo $colname_rsUserInfo; ?>&url_update_status=<?php echo $_POST['orderStatus']; ?>">
    <button id="orderChange" name="orderChange" class="btn btn-success">Change</button>
     </a> 
  </div>
</div>

</fieldset>
</form>


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
