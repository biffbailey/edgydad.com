<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_BlogConnection = null;
$database_BlogConnection = "dbdata1";
$username_BlogConnection = "root";
$password_BlogConnection = "Ironman1726!";
$port_BlogConnection = null;
$socket_BlogConnection = "/cloudsql/edgydad-168800:edgydad-sql-0";
/*
$BlogConnection = new pdo('mysql:unix_socket=/cloudsql/edgydad-168800:edgydad-sql-0;dbname=dbdata1',
  'root',  // username
  'Ironman1726!'       // password
  );
*/
$BlogConnection = mysqli_connect($hostname_BlogConnection, $username_BlogConnection, $password_BlogConnection, $database_BlogConnection,
	$port_BlogConnection, $socket_BlogConnection);
if(!$BlogConnection)
		{
			echo "<br/>";
			echo "Did not Connect, Returned = ";
			var_dump($BlogConnection);
			echo "<br/>Trying Apache<br/>";
			$hostname_BlogConnection = "localhost:1234";
			$database_BlogConnection = "dbdata1";
			$username_BlogConnection = "root";
			$password_BlogConnection = "Ironman1726!";
			$port_BlogConnection = null;
			$socket_BlogConnection = null;
			$BlogConnection = mysqi_connect($hostname_BlogConnection, $username_BlogConnection, $password_BlogConnection, $database_BlogConnection,
	$port_BlogConnection, $socket_BlogConnection);
			if(!BlogConnection){
				echo "Did not connect to Apache either!";
				var_dump($BlogConnection);
			} else {
				echo "Connected to Apache!";
			}
		}

 //or trigger_error(mysql_error(),E_USER_ERROR); 
?>