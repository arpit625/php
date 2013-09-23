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
$colname_rsUserDetail = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUserDetail = $_SESSION['MM_Username'];
}
mysql_select_db($database_online_order, $online_order);
$query_rsUserDetail = sprintf("SELECT username, status FROM usr_mgmnt WHERE username = %s", GetSQLValueString($colname_rsUserDetail, "text"));
$rsUserDetail = mysql_query($query_rsUserDetail, $online_order) or die(mysql_error());
$row_rsUserDetail = mysql_fetch_assoc($rsUserDetail);
$totalRows_rsUserDetail = mysql_num_rows($rsUserDetail);

$user_status_id = $row_rsUserDetail['status'];
$colname_rsViewOrder = "-1";

  $colname_rsViewOrder = $user_status_id;

mysql_select_db($database_online_order, $online_order);
// $query_rsViewOrder = sprintf("SELECT mainorder_id, order_date, order_time, status_deliver, status_pickup, status_dineup, phone, add1, apt_no, city, zip, user_id, order_total, delivery_charge, order_status, payment_mode FROM orders WHERE user_id = %s", $colname_rsViewOrder);
$query_rsViewOrder = sprintf("SELECT * FROM orders WHERE user_id = %s", $colname_rsViewOrder);
$rsViewOrder = mysql_query($query_rsViewOrder, $online_order) or die(mysql_error());
$row_rsViewOrder = mysql_fetch_assoc($rsViewOrder);
$totalRows_rsViewOrder = mysql_num_rows($rsViewOrder);

// New , Pending , Complete Order Count Details
$query_newOrder = sprintf("SELECT * FROM order_updt_status WHERE status = %s AND update_status = 1", $colname_rsViewOrder);
$query_pendingOrder = sprintf("SELECT * FROM order_updt_status WHERE username = %s AND update_status = 2", GetSQLValueString($colname_rsUserDetail, "text"));
$query_completeOrder = sprintf("SELECT * FROM order_updt_status WHERE username = %s AND update_status = 3", GetSQLValueString($colname_rsUserDetail, "text"));
$count_newOrder = mysql_query($query_newOrder, $online_order) or die(mysql_error());
$count_pendingOrder = mysql_query($query_pendingOrder, $online_order) or die(mysql_error());
$count_completeOrder = mysql_query($query_completeOrder, $online_order) or die(mysql_error());
$totalRows_newOrder = mysql_num_rows($count_newOrder);
$totalRows_pendingOrder = mysql_num_rows($count_pendingOrder);
$totalRows_completeOrder = mysql_num_rows($count_completeOrder);



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
       <h5>
         <span class="badge badge-info"><?php echo $totalRows_newOrder; ?></span> New / 
         <span class="badge badge-warning"><?php echo $totalRows_pendingOrder; ?></span> Pending / 
         <span class="badge badge-success"><?php echo $totalRows_completeOrder; ?></span> Complete 
       </h5>
      </div>
      <div class="span6 pull-right">
        <a href="<?php echo $logoutAction ?>">
        <button class="btn btn-large pull-right" type="button"><i class="icon-off"> </i> Sign Out</button>
        </a>      </div>

</div>

            <br><br>

              <table class="table table-striped table-bordered">
              <tr>
                <th>Order No.</th>
                <th>Date/Time</th>
                <th>Customer Detail</th>
                <th>Order Value</th>
                <th>Order Status</th>
                <th>Delivery Type</th>
                <th>Payment Type</th>

              </tr>
              <?php do { ?>
  <tr>
      <td>
      <a href="orderDetails.php?url_mainorder_id=<?php echo $row_rsViewOrder['mainorder_id']; ?>&url_user_id=<?php echo $row_rsViewOrder['user_id']; ?>">
        <button class="btn btn-reset" type="button"><?php echo $row_rsViewOrder['mainorder_id']; ?></button>
      </a> 
    </td>

    <td><?php echo $row_rsViewOrder['order_time']; ?></td>

                    <!-- Customer Details -->
                <td>
                
                <?php 
                echo $row_rsViewOrder['first_name'] . " " . $row_rsViewOrder['last_name']; 
                echo "<br>";
                echo $row_rsViewOrder['add1'];
                echo "<br>";
                echo $row_rsViewOrder['apt_no'];
                echo "<br>";
                echo $row_rsViewOrder['city'];
                echo "<br>";
                echo $row_rsViewOrder['zip'];
                echo "<br>";
                echo $row_rsViewOrder['phone'];

                ?>
                </td>
    <td><?php echo $row_rsViewOrder['order_total']; ?></td>
    <td><?php echo $row_rsViewOrder['order_status']; ?></td>
    <td>
      <?php 
			   	if($row_rsViewOrder['status_deliver'] == "yes")
					echo "Home Delivery";
				if($row_rsViewOrder['status_pickup'] == "yes")
					echo "Pick Up";
				if($row_rsViewOrder['status_dineup'] == "yes")
					echo "Dine Up";
			   ?>
    </td>
    <td><?php echo $row_rsViewOrder['payment_mode']; ?></td>

  </tr>
  <?php } while ($row_rsViewOrder = mysql_fetch_assoc($rsViewOrder)); ?>
              

              </table>
           
          </div>
        </div> 
      </div>

    </body>
    </html>
<?php
mysql_free_result($rsViewOrder);

mysql_free_result($rsUserDetail);
?>
