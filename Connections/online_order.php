<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_online_order = "localhost";
$database_online_order = "online_order";
$username_online_order = "root";
$password_online_order = "";
$online_order = mysql_pconnect($hostname_online_order, $username_online_order, $password_online_order) or trigger_error(mysql_error(),E_USER_ERROR); 
?>