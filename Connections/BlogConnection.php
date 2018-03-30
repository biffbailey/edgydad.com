<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
echo "<div>Hello World!</div>";
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
echo "$BlogConnection = ";
var_dump($BlogConnection);
if(!$BlogConnection)
		{
			echo "<br/>";
			echo "Did not Connect, Returned = ";
			var_dump($BlogConnection);
			echo "<br/>Trying Apache<br/>";
				$hostname_BlogConnection = "127.0.0.1";
				$database_BlogConnection = "dbdata1";
				$username_BlogConnection = 'apache';
				$password_BlogConnection = "Ironman1726!";
				$port_BlogConnection = 3306;
				$socket_BlogConnection = null;
				$BlogConnection = mysqli_connect($hostname_BlogConnection, $username_BlogConnection, 
						$password_BlogConnection, $database_BlogConnection, $port_BlogConnection, $socket_BlogConnection);
			if(!$BlogConnection){
				echo "Did not connect to Apache either!";
				var_dump($BlogConnection);
			} else {
				echo "Connected to Apache!";
			}
		}

 //or trigger_error(mysql_error(),E_USER_ERROR); 
?>