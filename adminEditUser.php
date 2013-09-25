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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ResetUser")) {
  $updateSQL = sprintf("UPDATE usr_mgmnt SET password=%s WHERE userid=%s AND status=%s",GetSQLValueString($_POST['password'], "text"),GetSQLValueString($_POST['useridInput'], "int"),GetSQLValueString($_POST['statusInput'], "text"));

  mysql_select_db($database_online_order, $online_order);
  $Result1 = mysql_query($updateSQL, $online_order) or die(mysql_error());

  $updateGoTo = "adminUserManage.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsEditUser = "-1";
if (isset($_GET['statusID']) && isset($_GET['userID'])) {
  $colname_rsEditUser = $_GET['statusID'];
  $colname_rsUserID = $_GET['userID'];
}
mysql_select_db($database_online_order, $online_order);
$query_rsEditUser = sprintf("SELECT * FROM usr_mgmnt WHERE userid = %s AND status='%s'", GetSQLValueString($colname_rsUserID, "int"),$colname_rsEditUser);
$rsEditUser = mysql_query($query_rsEditUser, $online_order) or die(mysql_error());
$row_rsEditUser = mysql_fetch_assoc($rsEditUser);
$totalRows_rsEditUser = mysql_num_rows($rsEditUser);
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


            <a href="<?php echo $logoutAction ?>">
            <button class="btn btn-large pull-right" type="button"><i class="icon-off"> </i> Sign Out</button>
            </a> <br><br>

            <form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal" name="ResetUser">
        <fieldset>

          <!-- Form Name -->
          <h1>
            <legend>Edit  User</legend></h1>

          <!-- Text input-->
          <div class="control-group">
            <label class="control-label">Username</label>
            <div class="controls">
              <label><?php echo $row_rsEditUser['username']; ?></label>

            </div>
          </div>

          <!-- Password input-->
          <div class="control-group">
            <label class="control-label">Password</label>
            <div class="controls">
              <input id="password" name="password" type="text" placeholder="Enter Password" class="input-xlarge" value="<?php echo $row_rsEditUser['password']; ?>" >

            </div>
          </div>

          <!-- Button -->
          <div class="control-group">
            <label class="control-label"></label>
            <div class="controls">
              <input id="submit" name="submit" class="btn" valur="Add User" type="submit">
            </div>
          </div>
<input name="useridInput" type="hidden" id="useridInput" value="<?php echo $row_rsEditUser['userid']; ?>">
<input name="statusInput" type="hidden" id="statusInput" value="<?php echo $row_rsEditUser['status']; ?>">
        </fieldset>
        <input type="hidden" name="MM_update" value="ResetUser">
            </form>
           
            
          </div>
        </div> 
      </div>

</body>
</html>
<?php
mysql_free_result($rsEditUser);
?>
