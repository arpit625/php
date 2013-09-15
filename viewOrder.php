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
                <th>Select</th>
              </tr>
              <tr>
                <td>abcd12</td>
                <td>Sept 8,2013</td>
                <td>Customer123</td>
                <td>200</td>
                <td>New</td>
                <td>Home Delivery</td>
                <td>Credit Card</td>
                <td><a class="btn btn-reset" type="button">View Items</a></td>
              </tr>

              <tr>
                <td>abcd12</td>
                <td>Sept 8,2013</td>
                <td>Customer123</td>
                <td>200</td>
                <td>New</td>
                <td>Home Delivery</td>
                <td>Credit Card</td>
                <td><a class="btn btn-reset" type="button">View Items</a></td>
              </tr>

              <tr>
                <td>abcd12</td>
                <td>Sept 8,2013</td>
                <td>Customer123</td>
                <td>200</td>
                <td>New</td>
                <td>Home Delivery</td>
                <td>Credit Card</td>
                <td><a class="btn btn-reset" type="button">View Items</a></td>
              </tr>

              </table>
           
          </div>
        </div> 
      </div>

    </body>
    </html>