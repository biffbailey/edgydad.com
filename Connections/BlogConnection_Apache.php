<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

error_reporting(0);

//Try Cloud SQL Connection
$hostname_BlogConnection = null;
$database_BlogConnection = "dbdata1";
$username_BlogConnection = "root";
$password_BlogConnection = "Ironman1726!";
$port_BlogConnection = '3306';
$socket_BlogConnection = "/cloudsql/edgydad-168800:edgydad-sql-0";

if(!$BlogConnection = mysqli_connect($hostname_BlogConnection, $username_BlogConnection, $password_BlogConnection, $database_BlogConnection,
		$port_BlogConnection, $socket_BlogConnection)) {

	$hostname_BlogConnection = "127.0.0.1";
	$database_BlogConnection = "dbdata1";
	$username_BlogConnection = 'apache';
	$password_BlogConnection = "Ironman1726!";
	$port_BlogConnection = 3306;
	$socket_BlogConnection = null;
	$BlogConnection = mysqli_connect($hostname_BlogConnection, $username_BlogConnection, $password_BlogConnection, $database_BlogConnection,
			$port_BlogConnection, $socket_BlogConnection);
}
/*Report what we got!

if(!$BlogConnection)
		{
			echo "<br/>";
			echo "Did not connect, returned = ";
			var_dump($BlogConnection);
			echo "<br/>";
		}
	else
		{	
			echo "<br/>";
			echo "Connected, returned = ";
			var_dump($BlogConnection);
			echo "<br/>";
		}
*/
?>