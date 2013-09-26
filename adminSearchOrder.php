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
      $_SESSION['MM_Userid'] = NULL ;
  $_SESSION['MM_Status'] = NULL ;
   unset($_SESSION['MM_Userid']);
  unset($_SESSION['MM_Status']);
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

$orderNo = (isset($_POST['orderNo']) ? $_POST['orderNo']: null);
$startDate = (isset($_POST['startDate']) ? $_POST['startDate']: null);
$endDate = (isset($_POST['endDate']) ? $_POST['endDate']: null);

mysql_select_db($database_online_order, $online_order);
if(!empty($orderNo)&& isset($orderNo)) 
  $query_rsViewAll = sprintf("SELECT * FROM orders o1, order_updt_status o2 WHERE o1.mainorder_id = %s  AND o1.mainorder_id = o2.mainorder_id ORDER BY order_time DESC", $_POST['orderNo']);
elseif(!empty($startDate) && !empty($endDate) && isset($startDate) && isset($endDate)) 
  $query_rsViewAll = sprintf("SELECT * FROM orders o1, order_updt_status o2 WHERE order_date between CAST('%s' AS DATE) and CAST('%s' AS DATE) AND o1.mainorder_id = o2.mainorder_id ORDER BY order_time DESC", $_POST['startDate'], $_POST['endDate']);
else 
  $query_rsViewAll = "SELECT * FROM orders o1, order_updt_status o2 where o1.mainorder_id = o2.mainorder_id ORDER BY order_time DESC";


if (isset($_GET['url_daily_search'])) {
  $query_rsViewAll = "SELECT * FROM orders WHERE order_date = DATE(CURDATE())";
}
// echo $query_rsViewAll;

$rsViewAll = mysql_query($query_rsViewAll, $online_order) or die(mysql_error());
$row_rsViewAll = mysql_fetch_assoc($rsViewAll);
$totalRows_rsViewAll = mysql_num_rows($rsViewAll);




// New , Pending , Complete Order Count Details
$query_newOrder = sprintf("SELECT * FROM order_updt_status WHERE update_status = 'New'");
$query_pendingOrder = sprintf("SELECT * FROM order_updt_status WHERE update_status = 'Pending'");
$query_completeOrder = sprintf("SELECT * FROM order_updt_status WHERE update_status = 'Complete'");
$count_newOrder = mysql_query($query_newOrder, $online_order) or die(mysql_error());
$count_pendingOrder = mysql_query($query_pendingOrder, $online_order) or die(mysql_error());
$count_completeOrder = mysql_query($query_completeOrder, $online_order) or die(mysql_error());
$totalRows_newOrder = mysql_num_rows($count_newOrder);
$totalRows_pendingOrder = mysql_num_rows($count_pendingOrder);
$totalRows_completeOrder = mysql_num_rows($count_completeOrder);

$query_lastTime = "SELECT * FROM usr_mgmnt WHERE username='admin'";
$lastTime = mysql_query($query_lastTime, $online_order) or die(mysql_error());
$row_lastTime = mysql_fetch_assoc($lastTime);
$totalRows_lastTime = mysql_num_rows($lastTime);
$timeToCompare = strtotime($row_lastTime['last_time']);

// Update Timestamp
$query_UpdateLastTime = "update  usr_mgmnt set last_time=now() WHERE username='admin'";
$UpdateLastTime = mysql_query($query_UpdateLastTime, $online_order) or die(mysql_error());

$play = false;
?>
<!DOCTYPE html>
<html>
<head>
  <title>Online Order Updates</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="refresh" content="180" />
  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>

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
<a href="adminHome.php" class="btn btn-large" type="button"><i class="icon-home"> </i> Home</a>
<a href="adminSearchOrder.php?url_all_orders=1" class="btn btn-large " type="button"><i class="icon-align-justify"> </i> All Orders</a>
<a href="adminSearchOrder.php?url_daily_search=1" class="btn btn-large" type="button"><i class="icon-book"> </i> Daily Orders</a>
<a href="adminHome.php" class="btn btn-large" type="button"><i class="icon-backward"> </i> Back</a>

      
         <span class="badge badge-info"><?php echo $totalRows_newOrder; ?></span> New / 
         <span class="badge badge-warning"><?php echo $totalRows_pendingOrder; ?></span> Pending / 
         <span class="badge badge-success"><?php echo $totalRows_completeOrder; ?></span> Complete 
     

        <a href="<?php echo $logoutAction ?>">
        <button class="btn btn-large pull-right" type="button"><i class="icon-off"> </i> Sign Out</button>
        </a>    

</div>

<br><br>

<?php 
    if (isset($_GET['url_all_orders'])) {
?>

<div class="row-fluid">
  <form class="form-inline" method="POST" action="adminSearchOrder.php">
 

  <div class="input-append">
 <input type="text" class="input-medium" placeholder="Enter Order No." name="orderNo">
  <span class="add-on"><i class=" icon-list-alt"> </i> </span>
</div>

<div class="input-append">
    <input type="text" class="input-medium" placeholder="Date: 2013-09-10" name="startDate">
  <span class="add-on"><i class="icon-calendar"> </i> </span>
</div>

<div class="input-append">
    <input type="text" class="input-medium" placeholder="Date: 2013-09-11" name="endDate">
  <span class="add-on"><i class="icon-calendar"> </i> </span>
</div>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <button type="submit" class="btn"><i class="icon-search"> </i> Search</button>
</form>

</div>
<?php      } ?>

            <br>

              <table class="table table-striped table-bordered">
              <tr >
                <th>Order No.</th>
                <th>Date/Time</th>
                <th>Customer Detail</th>
                <th>Order Value</th>
                <th>Order Status</th>
                <th>Delivery Type</th>
                <th>Payment Type</th>
                <!-- <th>Select</th> -->
              </tr>

               <?php 
if ($totalRows_rsViewAll == 0) {
 echo "<tr> <th>No Result Found</th></tr>";
}
else
{


               do { ?>

               <?php
              $new = false;
                if (strtotime($row_rsViewAll['order_time']) > $timeToCompare) {
                  $new = true;
                  $play = true;
                }
                ?>
              <tr <?php if($new) echo "class=\"alert alert-success\"" ?> >


                

               <td>
                  <a href="adminOrderDetails.php?url_mainorder_id=<?php echo $row_rsViewAll['mainorder_id']; ?>&url_user_id=<?php echo $row_rsViewAll['userid']; ?>&url_status_id=<?php echo $row_rsViewAll['status']; ?>">
                  <button class="btn btn-reset" type="button"><?php echo $row_rsViewAll['mainorder_id']; ?></button>
                </a>   

                <br><br>
                <?php
                 if($new){
                  echo "<button class=\"btn btn-success\" type=\"button\">New Order</button>"; 
                }
                ?>          
               </td>
                
                <td>
                <?php echo $row_rsViewAll['order_date'] . "<br>"; ?>
               <strong> <?php echo substr($row_rsViewAll['order_time'],11,5); ?></strong>
                </td>
                  
                <!-- Customer Details -->
                <td>
                
                <?php 
                echo $row_rsViewAll['first_name'] . " " . $row_rsViewAll['last_name']; 
                echo "<br>";
                echo $row_rsViewAll['add1'];
                echo "<br>";
                echo $row_rsViewAll['apt_no'];
                echo "<br>";
                echo $row_rsViewAll['city'];
                echo "<br>";
                echo $row_rsViewAll['zip'];
                echo "<br>";
                echo $row_rsViewAll['phone'];

                ?>
                </td>
                               

                <td><?php echo $row_rsViewAll['order_total']; ?></td>
                <td><?php echo $row_rsViewAll['update_status']; ?></td>
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

              </tr>
                <?php } while ($row_rsViewAll = mysql_fetch_assoc($rsViewAll)); 
}
                ?>



              </table>
              <?php if($play) { ?>
           <audio autoplay>
  <source src="notification.mp3" type="audio/mpeg">
</audio>
<?php } ?>
              <?php if($play) { ?>
           <audio autoplay>
  <source src="notification.mp3" type="audio/mpeg">
</audio>
<?php } ?>

<!-- <button type="button" data-toggle="modal" data-target="#myModal">Launch modal</button> -->
<div class="modal hide fade" id="myModal" data-target="#foo">
  <div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Notification</h3>
  </div>
  <div class="modal-body">
    <p>You have New Orders.</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

          </div>
        </div> 
      </div>
<script>
 <?php if($play) { 
echo "$('#myModal').modal('show')";
 } ?>
</script>
    </body>
    </html>
<?php
mysql_free_result($rsViewAll);
?>
