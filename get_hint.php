<?php 
// get and display the suggestions for the request parameter q
// lookup all hints from array if $q is different from "" 

// connect to database

$host = "dbData3.db.3273326.hostedresource.com";
$database = "dbData3";
$user = "dbData3";
$password = "Ironman1726!";

$mysqli = new mysqli($host, $user, $password, $database);
//	if(!$mysqli) {
//    	die("Could not connect: " . mysqli_error($mysqli));
//    };
//	echo $mysqli->host_info. "<br><br>";

$hint = "";
$q = $_REQUEST["q"];

	if ($q !== "") {
  		$q=strtolower($q); $len=strlen($q);
  		
// Construct the query:  		
  		$hintquery = "select 
		FOOD_DESC.Long_Desc, 
		FD_GROUP.FdGrp_Desc,
		NDB_No
		from
		FOOD_DESC
		join FD_GROUP on FD_GROUP.FdGrp_Cd = FOOD_DESC.FdGrp_Cd
		where
		Long_Desc
		like
		\"%".$q."%\"";
//		echo $hintquery;
		
// Make the query:
		if(!$hintresult = $mysqli->query($hintquery)) {
			echo "var_dump($mysqli)";
		}
// Assign the query result:
	$hint = $hintresult->fetch_array();
		
// Output "no suggestion" if no hint was found
// or output the correct values 
echo $hint==="" ? "no suggestion =>> ".$q : "";

// fetch the field names, open a table and put the names in the first row of the table

// $hintFieldInfo = $hintresult->fetch_fields();

echo "<table style= \"width: 80%; \">";
echo "<caption>Suggested Food Names from</br>USDA Nutrition Database SR27</caption>";
echo "<thead><tr>";
foreach ($hintresult->fetch_fields() as $val) {
	echo "<th>";
	printf("%s", $val->name);
	echo "</th>";
};
echo "</tr></thead>";

// fetch the data and put them in the next rows of the table then close the table

while ($suggestions = $hintresult->fetch_assoc()) {
	echo "<tr>";
	 
	foreach ($suggestions as $val) {
		echo "<td>";
		echo "$val";
		echo "</td>";
	}

	echo "</tr>";
};

echo "<tfoot>";
echo "<tr>";
echo "<td	colspan=\"3\">";
echo "Source US Department of Agriculture www.UDSA.gov";
echo "</td>";
echo 	"</tr>";
echo "</tfoot>";
echo "</table>";

	}
?>