<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_BlogConnection = "/cloudsql/edgydad-168800:edgydad-sql-0";
$database_BlogConnection = "dbdata1";
$username_BlogConnection = "root";
$password_BlogConnection = "Ironman1726!";
$BlogConnection = mysql_pconnect($hostname_BlogConnection, $username_BlogConnection, $password_BlogConnection) or trigger_error(mysql_error(),E_USER_ERROR); 
?>