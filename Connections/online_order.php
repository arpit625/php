<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_online_order = "orderviewer17.db.11292088.hostedresource.com";
$database_online_order = "orderviewer17";
$username_online_order = "orderviewer17";
$password_online_order = "OrderViewer@10";
$online_order = mysql_pconnect($hostname_online_order, $username_online_order, $password_online_order) or trigger_error(mysql_error(),E_USER_ERROR); 
?>