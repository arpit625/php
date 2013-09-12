<?php require_once('Connections/online_order.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "role";
  $MM_redirectLoginSuccess = "adminHome.php";
  $MM_redirectLoginFailed = "index.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_online_order, $online_order);
  	
  $LoginRS__query=sprintf("SELECT username, password, role FROM usr_mgmnt WHERE username=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $online_order) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'role');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
	if($loginUsername == "admin")	
   		header("Location: " . $MM_redirectLoginSuccess );
	else
		header("Location: viewOrder.php" );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
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
<div class="span8 offset2">

<div class="well">
       <form ACTION="<?php echo $loginFormAction; ?>" METHOD="POST" class="form-horizontal" name="login">
        <fieldset>

          <!-- Form Name -->
          <h1><legend>Login</legend></h1>

          <!-- Text input-->
          <div class="control-group">
            <label class="control-label">Username</label>
            <div class="controls">
              <input id="username" name="username" type="text" placeholder="Enter Username" class="input-xlarge" required>

            </div>
          </div>

          <!-- Password input-->
          <div class="control-group">
            <label class="control-label">Password</label>
            <div class="controls">
              <input id="password" name="password" type="password" placeholder="Enter Password" class="input-xlarge" required>

            </div>
          </div>

          <!-- Button -->
          <div class="control-group">
            <label class="control-label"></label>
            <div class="controls">
              <input id="submit" name="submit" class="btn" type="submit" value="Sign In">
            </div>
          </div>

        </fieldset>
      </form>


    </div>
</div>
  

</div> 
</div>

  </body>
  </html>