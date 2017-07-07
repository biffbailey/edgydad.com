<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_BlogConnection = "dbdata1.db.3273326.hostedresource.com";
$database_BlogConnection = "dbdata1";
$username_BlogConnection = "dbdata1";
$password_BlogConnection = "Ironman1726";
$BlogConnection = mysql_pconnect($hostname_BlogConnection, $username_BlogConnection, $password_BlogConnection) or trigger_error(mysql_error(),E_USER_ERROR); 
?>