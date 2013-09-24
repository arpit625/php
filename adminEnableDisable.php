<?php require_once('Connections/online_order.php'); ?>
<?php
ob_start();
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

$colname_rsUpdateEnable = "-1";
$varUserStatus = "disable";
if (isset($_GET['url_status'])) {
  $colname_rsUpdateEnable = $_GET['url_status'];
  echo "Status Done";
}
if (isset($_GET['url_user_status'])) {
  $varUserStatus = $_GET['url_user_status'];
  echo "User Status Done";
}
mysql_select_db($database_online_order, $online_order);
$query_rsUpdateEnable = sprintf("SELECT * FROM usr_mgmnt WHERE user_id = %s", GetSQLValueString($colname_rsUpdateEnable, "text"));
$rsUpdateEnable = mysql_query($query_rsUpdateEnable, $online_order) or die(mysql_error());
$row_rsUpdateEnable = mysql_fetch_assoc($rsUpdateEnable);
$totalRows_rsUpdateEnable = mysql_num_rows($rsUpdateEnable);


$updateSQL = "UPDATE usr_mgmnt SET user_status= '$varUserStatus' WHERE user_id='$colname_rsUpdateEnable'";

$Result1 = mysql_query($updateSQL, $online_order) or die(mysql_error());
 echo "sql successful";
header("Location: adminUserManage.php");
exit;
echo "after";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
<?php
mysql_free_result($rsUpdateEnable);
?>
