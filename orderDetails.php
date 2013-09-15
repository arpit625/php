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
              <a class="btn btn-large pull-right" type="button"><i class="icon-off"> </i> Sign Out</a>
            </div>

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
            <tr>
              <td>1</td>
              <td>Pizza</td>
              <td>Toppings+Olive</td>
              <td>Extra Cheese</td>
              <td>10</td>
              <td>20</td>
              <td>30</td>
            </tr>

            <tr>
              <td>2</td>
              <td>Coke</td>
              <td>Diet Coke</td>
              <td>NA</td>
              <td>0</td>
              <td>20</td>
              <td>20</td>
            </tr>

            <tr>
              <td>3</td>
              <td>Cheese Sticks</td>
              <td>Italian Style</td>
              <td>Extra Cheese</td>
              <td>10</td>
              <td>20</td>
              <td>30</td>
            </tr>

          </table>

          <h4>
            Tax - $12.25 , 
            Coupon Discount - $10 , 
            Delivery Charges - $10 , 
            Total - $250 
          </h4>

          <br>
          <h2>Customer Details</h2>
          <div class="row-fluid">
            <div class="span4">
            <dl class="dl-horizontal">
              <dt>Name :</dt>
              <dd>Jacob Singh</dd>

              <dt>Street Address :</dt>
              <dd>234 Park Avenue</dd>

              <dt>Town :</dt>
              <dd>Virginia</dd>

              <dt>Delivery Type :</dt>
              <dd>Pick Up</dd>

              <dt>Order Time :</dt>
              <dd>03:50 pm</dd>                            

            </dl>
            </div>


            <div class="span4">
            <dl class="dl-horizontal">
              <dt>Contact Number :</dt>
              <dd>123-456-7890</dd>

              <dt>Apt Number :</dt>
              <dd>23</dd>

              <dt>Street :</dt>
              <dd>Winston Street</dd>

              <dt>Payment Type :</dt>
              <dd>Online</dd>                           

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
    <select id="orderStatur" name="orderStatur" class="input-xlarge">
      <option>New</option>
      <option>Pending</option>
      <option>Complete</option>
    </select>

    <a id="orderChange" name="orderChange" class="btn btn-success">Change</a>
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