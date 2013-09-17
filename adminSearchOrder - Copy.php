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

mysql_select_db($database_online_order, $online_order);
$query_rsViewAll = "SELECT * FROM orders";
$rsViewAll = mysql_query($query_rsViewAll, $online_order) or die(mysql_error());
$row_rsViewAll = mysql_fetch_assoc($rsViewAll);
$totalRows_rsViewAll = mysql_num_rows($rsViewAll);


// New , Pending , Complete Order Count Details
$query_newOrder = sprintf("SELECT * FROM order_updt_status WHERE update_status = 1");
$query_pendingOrder = sprintf("SELECT * FROM order_updt_status WHERE update_status = 2");
$query_completeOrder = sprintf("SELECT * FROM order_updt_status WHERE update_status = 3");
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

<div class="row-fluid">
<div class="span3"><a class="btn btn-large pull-right" type="button"><i class="icon-book"> </i> Daily Orders</a></div>
<div class="span2"><a class="btn btn-large pull-right" type="button"><i class="icon-align-justify"> </i> All Orders</a></div>
      <div class="span5">
       <h5>
         <span class="badge badge-info"><?php echo $totalRows_newOrder; ?></span> New / 
         <span class="badge badge-warning"><?php echo $totalRows_pendingOrder; ?></span> Pending / 
         <span class="badge badge-success"><?php echo $totalRows_completeOrder; ?></span> Complete 
       </h5>
      </div>
      <div class="span2 ">
        <a href="<?php echo $logoutAction ?>">
        <button class="btn btn-large pull-right" type="button"><i class="icon-off"> </i> Sign Out</button>
        </a>      </div>

</div>

<br><br>

<div class="row-fluid">
  <form class="form-inline">
 

  <div class="input-append">
 <input type="text" class="input-medium" placeholder="Enter Order No.">
  <span class="add-on"><i class=" icon-list-alt"> </i> </span>
</div>

<div class="input-append">
    <input type="text" class="input-medium" placeholder="Date Range From">
  <span class="add-on"><i class="icon-calendar"> </i> </span>
</div>

<div class="input-append">
    <input type="text" class="input-medium" placeholder="Date Range To">
  <span class="add-on"><i class="icon-calendar"> </i> </span>
</div>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <button type="submit" class="btn"><i class="icon-search"> </i> Search</button>
</form>

</div>

            <br>

              <table class="table table-striped table-bordered">
              <tr>
                <th>Order No.</th>
                <th>Date/Time</th>
                <th>Customer Detail</th>
                <th>Order Value</th>
                <th>Order Status</th>
                <th>Delivery Type</th>
                <th>Payment Type</th>
                <th>Select</th>
              </tr>

               <?php do { ?>
              <tr>
                <td><?php echo $row_rsViewAll['mainorder_id']; ?></td>
                <td><?php echo $row_rsViewAll['order_time']; ?></td>
                <td><?php echo $row_rsViewAll['apt_no']; ?>, <?php echo $row_rsViewAll['add1']; ?>, <?php echo $row_rsViewAll['city']; ?> - <?php echo $row_rsViewAll['zip']; ?> , <?php echo $row_rsViewAll['phone']; ?></td>
                <td><?php echo $row_rsViewAll['order_total']; ?></td>
                <td><?php echo $row_rsViewAll['order_status']; ?></td>
                <td>
      <?php 
			   	if($row_rsViewAll['status_deliver'] == "yes")
					echo "Home Delivery";
				if($row_rsViewAll['status_pickup'] == "yes")
					echo "Pick Up";
				if($row_rsViewAll['status_dineup'] == "yes")
					echo "Dine Up";
			   ?>
    </td>
                <td><?php echo $row_rsViewAll['payment_mode']; ?></td>
                <td>
                  <a href="adminOrderDetails.php?url_mainorder_id=<?php echo $row_rsViewAll['mainorder_id']; ?>&url_user_id=<?php echo $row_rsViewAll['user_id']; ?>">
                  <button class="btn btn-reset" type="button">View Items</button>
                </a>                </td>
              </tr>
                <?php } while ($row_rsViewAll = mysql_fetch_assoc($rsViewAll)); ?>



              </table>
           
          </div>
        </div> 
      </div>

    </body>
    </html>
<?php
mysql_free_result($rsViewAll);
?>
