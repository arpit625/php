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
$query_rsUsers = "SELECT username, password, status, user_status FROM usr_mgmnt";
$rsUsers = mysql_query($query_rsUsers, $online_order) or die(mysql_error());
$row_rsUsers = mysql_fetch_assoc($rsUsers);
$totalRows_rsUsers = mysql_num_rows($rsUsers);
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
              <h1>User Management</h1>
  

<a href="adminHome.php" class="btn btn-large" type="button"><i class="icon-home"> </i> Home</a>
              <a href="adminAddUser.php">
              <button class="btn btn-large" type="button"><i class="icon-user"> </i>  Add User</button>
              </a>
      <a href="<?php echo $logoutAction ?>">
      <button class="btn btn-large pull-right" type="button"><i class="icon-off"> </i>  Sign Out</button>
      </a> <br><br>

              <table class="table table-striped table-bordered">
              <tr>
                <th>S.No.</th>
                <th>Username</th>
                <th>Password</th>
                <th>Status</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
              
              <?php $i = 1; do { ?>
  <tr>
    <td><?php echo $i; ?></td>
    <td><?php echo $row_rsUsers['username']; ?></td>
    <td><?php echo $row_rsUsers['password']; ?></td>
    <td><?php echo $row_rsUsers['status']; ?></td>
    <td>
      <a href="adminEnableDisable.php?url_user_status=enable&url_status=<?php echo $row_rsUsers['status']; ?>">
      <!-- <button class="btn btn-success" type="button">Enable</button> -->
      <button class="btn btn-success
      <?php if ($row_rsUsers['user_status']=='enable') {
        echo 'disabled';
      } ?>
      " type="button">Enable</button>
      </a>    </td>
    <td>
      <a href="adminEnableDisable.php?url_user_status=disable&url_status=<?php echo $row_rsUsers['status']; ?>">
      <button class="btn btn-inverse
      <?php if ($row_rsUsers['user_status']=='disable') {
        echo 'disabled';
      } ?>
      " type="button">Disable</button>
      </a>    </td>
    <td><a href="delete.php?statusID=<?php echo $row_rsUsers['status']; ?>">
      <button class="btn btn-danger" type="button">Delete</button>
    </a></td>
    <td><a href="adminEditUser.php?statusID=<?php echo $row_rsUsers['status']; ?>">
      <button class="btn btn-reset" type="button">Reset</button>
    </a></td>
  </tr>
  <?php $i++;} while ($row_rsUsers = mysql_fetch_assoc($rsUsers)); ?>
             

              </table>
           
          </div>
        </div> 
      </div>

    </body>
    </html>
<?php
mysql_free_result($rsUsers);
?>
