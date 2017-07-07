<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Cp1252">
<title>edgyDAD NDB Search</title>
<style>
body {
	font-family: "Verdana", "Arial";
	background: #ffffff;
	font-size: 1em;
	}
	
h1 {				 
	font-size: 1.0em;
	color: #eeb702;
	}
	
form {
	font-size: 0.8em;
	color: #ff0000;
	}	
	
select {
	font-size: 0.8em;
	color: #000000;	
	}
	
input {
	font-size: 0.8em;
	color: #ff0000;	
	}
		 
table, thead, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    padding: 2px;
    text-align: center;
    float: center;
    background: #ffffff;
    font-size:0.8em
	}

caption {
	font-size: 1em; 
	color: #008f00;
	font-style: bold;
	}
th {
	font-size: 0.9em; 
	color: #0000ff;
	}
tfoot {
	font-size: 0.5em;
	color: #ff0000;
	text-align: left;
	}
</style>
</head>
<body>
<div>
<?php 
//	set variables, get $q from URL

	$host = "dbData3.db.3273326.hostedresource.com";
	$database = "dbData3";
	$user = "dbData3";
	$password = "Ironman1726!";
//	$q =$_REQUEST["q"];
	if(isset($_GET["foodGroup"])) {
		$foodGroup=$_GET["foodGroup"];
	}
	if(!isset($_GET["foodGroup"])) {
		$foodGroup="2000";
	}
	if(isset($_GET["NDB_No"])) {
		$ndbNo=$_GET["NDB_No"];
	}
	if(!isset($ndbNo)) {
		$ndbNo="20137";
	}
//	echo "<p>".$ndbNo."</p>";
//	echo "<p>".$foodGroup."</p>";
?>
<?php 
 // connect to database
	
	$mysqli = new mysqli($host, $user, $password, $database);
//	if(!$mysqli) {
//    	die("Could not connect: " . mysqli_error($mysqli));
//    };
//	echo $mysqli->host_info. "<br><br>";
?>

<!  Page Heading----------------------------------------------------------->
<h1>edgyDAD's USDA Nutrition Database Search Page
<img src="images/usda.jpg" width="20em" height="20em"></h1>
<h6> &copy 2014 Bbig Builders Incorporated</h6>
<!  ----------------------------------------------------------------------->
<?php 
//	get the food groups from FD_DESC and draw the select list box
	
	$query = "SELECT * FROM FD_GROUP ORDER BY FD_GROUP.FdGrp_Desc ASC";
	$mysqli_result = mysqli_query($mysqli,$query);
//	echo "<p>".var_dump($mysqli_result)."</p>";
	$cur_fld_offset = $mysqli_result->current_field;
//	echo "<p>".var_dump($cur_fld_offset)."<p>";
	$resultarray = mysqli_fetch_assoc($mysqli_result);
//	echo "<p>".var_dump($resultarray)."<p>";
?>	
<form action="food_data.php" method="get">
<p>Choose Food Group:<br>
<select name=foodGroup id=$foodGroup >
<?php 	
	while ($row = $mysqli_result->fetch_assoc()) {
		if($row["FdGrp_Cd"]==$foodGroup){
			echo "<option value="."\"".$row["FdGrp_Cd"]."\""." selected>".$row["FdGrp_Desc"]."</option>";
		}
		echo "<option value="."\"".$row["FdGrp_Cd"]."\">".$row["FdGrp_Desc"]."</option>";
	}	
?>	
</select><br>
<input type="submit" value="Refresh Food Name List with Food Group Names">
</p>	
<!  ----------------------------------------------------------------------->	
<?php 
//	get the long descriptions from FD_DESC and draw the select list box

	$query = "SELECT Long_Desc, NDB_No FROM FOOD_DESC WHERE FdGrp_Cd=".$foodGroup." ORDER BY Long_Desc ASC";
 	$mysqli_result = mysqli_query($mysqli,$query);
//	echo "<p>".var_dump($mysqli_result)."</p>";
	$cur_fld_offset = $mysqli_result->current_field;
//	echo "<p>".var_dump($cur_fld_offset)."<p>";
	$resultarray = mysqli_fetch_assoc($mysqli_result);
//	echo "<p>".var_dump($resultarray)."<p>";
?>	
<p>Choose Food Name:<br>
<select name=NDB_No id=select>
<?php 
	while ($row = $mysqli_result->fetch_assoc()) {
		if($row["NDB_No"]==$ndbNo) {
			echo "<p>"."<option value="."\"".$row["NDB_No"]."\" selected>".$row["Long_Desc"]."</option>";
			}
		echo "<p>"."<option value="."\"".$row["NDB_No"]."\">".$row["Long_Desc"]."</option>";
		}
?>	
</select><br>
<input type="submit" value="Refresh Nutriant List with Values for Selected Food">
</p>
</form>	
<?php 
//	echo $_GET;

//run the stored proc
    $query = "CALL "."query_basic_data_measures_on_NDB_No(\"".$ndbNo."\")";
//	$query = "SELECT * FROM FD_GROUP ORDER BY FD_GROUP.FdGRP_Cd ASC";
//    echo $query."<br>";
//	Check the $result is boolean 0 and dump the $mysqli array if so dump the variable
    if(!$result = $mysqli->query($query)) {
    	var_dump($mysqli);
    }
    	;
//    var_dump($result);
//    echo "<br>";
  
// fetch the field names, open a table and put the names in the first row of the table
  
  $fieldInfo = $result->fetch_fields();
  echo "<table style= \"width: 100%; height: 400px;\">";
  echo "<caption>USDA Nutrition Database SR27<br>Basic Nutrition Info for ".$ndbNo."</caption>";
  echo "<thead><tr>";
  foreach ($fieldInfo as $val) {
  	echo "<th>";
  	printf("%s", $val->name);
  	echo "</th>";
  	};
  echo "</tr></thead>";	

// fetch the data and put them in the next rows of the table then close the table 
// find the number of fields and inspect $result_array
	$num_fields = $result->field_count;
//	var_dump($num_fields);
//	echo "<br>";
	$num_rows = $result->num_rows;
//	var_dump($num_rows);
//	echo "<br>";

// fetch_all method table construction follows but not available with GoDaddy PHP version	
// 	$result_array = $result->fetch_all();
// 	var_dump ($result_array);
//    foreach ($result_array as $val) {
//  		for ($i=0; $i<$mysqli->field_count; $i++) {
//  		echo "</td><td>";
//		printf($val[$i]);
//  		}
//	echo "</td></tr>";		
//  };

// fetch_row method table construction follows
	while ($row = $result->fetch_row()) {
//		var_dump($row);
//		echo "<br><br>";
		echo "<tr>";
		foreach ($row as $data) {
			echo "<td>".$data."</td>";
		}
		echo "</tr>";
	}
	
	
  echo "<tfoot>";
  echo "<tr>";
  echo "<td	colspan=\"".$num_fields."\">"; 
  echo "Source US Department of Agriculture www.UDSA.gov";
  echo "</td>";
  echo 	"</tr>";	
  echo "</tfoot>";	
  echo "</table>";
 
?>    

</div>
</body>
</html>