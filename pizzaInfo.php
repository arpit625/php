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

$colname_rsPizzaDetails = "-1";
if (isset($_GET['url_temp_order_id'])) {
  $colname_rsPizzaDetails = $_GET['url_temp_order_id'];
}
mysql_select_db($database_online_order, $online_order);
$query_rsPizzaDetails = sprintf("SELECT order_id, pizzaname, `size`, crusttype, `option`, toppingsideA, toppingsideB FROM cart_order_items WHERE temp_order_id = %s", GetSQLValueString($colname_rsPizzaDetails, "int"));
$rsPizzaDetails = mysql_query($query_rsPizzaDetails, $online_order) or die(mysql_error());
$row_rsPizzaDetails = mysql_fetch_assoc($rsPizzaDetails);
$totalRows_rsPizzaDetails = mysql_num_rows($rsPizzaDetails);
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
         <span class="badge badge-info">21</span> New / 
         <span class="badge badge-warning">2</span> Pending / 
         <span class="badge badge-success">28</span> Complete 
       </h5>
      </div>
      <div class="span6 pull-right">
        
        <a href="<?php echo $logoutAction ?>">
        <button class="btn btn-large pull-right" type="button"><i class="icon-off"> </i> Sign Out</button>
        </a>       </div>

</div>

            <br><br>

              <table class="table table-striped table-bordered">
              <tr>
                <th>Pizza Name</th>
                <th>Size</th>
                <th>Crust</th>
                <th>Other</th>
                <th>Toppings Side A</th>
                <th>Toppings Side B</th>
              </tr>

  <tr>
    <td><?php echo $row_rsPizzaDetails['pizzaname']; ?></td>
    <td><?php echo $row_rsPizzaDetails['size']; ?></td>
    <td><?php echo $row_rsPizzaDetails['crusttype']; ?></td>
    <td><?php echo $row_rsPizzaDetails['option']; ?></td>
    <td><?php echo $row_rsPizzaDetails['toppingsideA']; ?></td>
    <td><?php echo $row_rsPizzaDetails['toppingsideB']; ?></td>

  </tr>

              

              </table>
           
          </div>
        </div> 
      </div>

    </body>
    </html>
<?php
mysql_free_result($rsPizzaDetails);

?>
