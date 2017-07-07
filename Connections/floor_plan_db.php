<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_floor_plan_db = "dbdata2.db.3273326.hostedresource.com";
$database_floor_plan_db = "dbdata2";
$username_floor_plan_db = "dbdata2";
$password_floor_plan_db = "Ironman1895";
$floor_plan_db = mysql_pconnect($hostname_floor_plan_db, $username_floor_plan_db, $password_floor_plan_db) or trigger_error(mysql_error(),E_USER_ERROR); 
?>