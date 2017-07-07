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
		 
table, thead, tfoot, th, td {
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
	}
#Measures {
	width: 49%;
	float: right;
	background-color: #ffffff;
	border-style: none;
	border-color: #00aa00;
	}
#Food_Chooser {
	width: 49%;
	float: left;
	background-color: #ffffff;
	border-style: none;
	border-color: #00aa00;
	}		
#Recipes {
	width: 49%;
	float: Right;
	background-color: #ffffff;
	border-style: none;
	border-color: #00aa00;
	}
</style>
<script>
function showHint(str) {
  if (str.length==0) { 
    document.getElementById("txtHint").innerHTML="";
    return;
  }
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
  xmlhttp.open("GET","get_hint.php?q="+str,true);
  xmlhttp.send();
}
</script>
</head>
<body>
<div id="Food_Chooser">
<?php 
//	set variables, get $q from URL

	$host = "dbData3.db.3273326.hostedresource.com";
	$database = "dbData3";
	$user = "dbData3";
	$password = "Ironman1726!";
//	$q =$_REQUEST["q"];
//  var_dump($_GET)
	if(isset($_GET["foodGroup"])) {
		$foodGroup=$_GET["foodGroup"];
	}
	if(!isset($_GET["foodGroup"])) {
		$foodGroup="2000";
	}
	if(isset($_GET["NDB_No"])) {
		$ndbNo=$_GET["NDB_No"];
	}
	if(!isset($_GET["NDB_No"])) {
		$ndbNo="20137";
	}
	if(isset($_GET["recipeNo"])) {
		$recipeNo=$_GET["recipeNo"];
	}
	if(!isset($_GET["recipeNo"])) {
		$recipeNo="5";
	}
?>
<?php 
 // connect to database
	
	$mysqli = new mysqli($host, $user, $password, $database);
	if(!$mysqli) {
    	die("Could not connect: " . mysqli_error($mysqli));
    };
?>

<!  Page Heading----------------------------------------------------------->
<h1>edgyDAD's USDA Nutrition Database Search Page
<img src="www.edgydad.com/images/usda.jpg" width="20em" height="20em"></h1>
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
<form id="chooseFood" action="food_data_2.php" method="get">
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
<?php 
echo "<input type=\"hidden\" name=\"recipeNo\" value=".$recipeNo.">";
?>
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
<?php 
echo "<input type=\"hidden\" name=\"recipeNo\" value=".$recipeNo.">";
?>
<input type="submit" value="Refresh Nutriant List with Values for Selected Food">
</p>
</form>	
<!  ----------------------------------------------------------------------->	

<p>
	<form id="hint"> 
	For Suggestions, start typing a name in the input field below:</br>
	<span style = color:#000000>
	Search string: <input type="text" onkeyup="showHint(this.value)">
	</span>
	</form>
	<span id="txtHint"></span></p>

</div>
<!  ----------------------------------------------------------------------->
<div id="Measures">

<?php

//	get the measures and grams per measure from WEIGHt_sr25 and draw table_Measures 
//  and draw the select_Measures list box

$query = "SELECT Amount, Msre_Desc, Gm_Wgt, Gm_Wgt/100 as Factor, Seq FROM WEIGHT_sr25 WHERE NDB_No =".$ndbNo." ORDER BY Seq ASC";
$measures_result = mysqli_query($mysqli,$query);

$cur_fld_offset = $measures_result->current_field;
 
// fetch the field names, open a table and put the names in the first row of the table
  
  $fieldInfo = $measures_result->fetch_fields();
  
  echo "<table style= \"width: 100%; \">";
  echo "<caption>Measures and Gram Weight from USDA Nutrition Database SR25<br>for ".$ndbNo."</caption>";
  echo "<thead><tr>";
  
  	foreach ($fieldInfo as $val) {
  		echo "<th>";
  		printf("%s", $val->name);
  		echo "</th>";
  		};
  	
  echo "</tr></thead>";	

// fetch the data and put them in the next rows of the table then close the table 

  echo "<tbody>";

  while ($measures = $measures_result->fetch_assoc()) {

        echo "<tr>";

		foreach($measures as $val) {
 			echo "<td>";
 			echo $val;	
			echo "</td>";
	  		}
	
	  		echo "</tr>";		
   		};

  echo "</tbody>";
  echo "<tfoot>";
  echo "<tr>";
  echo "<td	colspan=\"5\">"; 
  echo "Source US Department of Agriculture www.UDSA.gov";
  echo "</td>";
  echo 	"</tr>";	
  echo "</tfoot>";	
  echo "</table>";
?>
</div>
<! ------------------------------------------------------------------------------>

<!  ----------------------------------------------------------------------->
<div id="Recipes">

<?php

//	get the recipes list and draw select box 
//  

$query = "SELECT Recipe_Name, Recipe_No FROM my_RECIPES ORDER BY Recipe_Name ASC";
$recipe_list_result = mysqli_query($mysqli,$query);

$cur_fld_offset = $measures_result->current_field;
 
// fetch the field names, open a table and put the names in the first row of the table
  
$fieldInfo = $measures_result->fetch_fields();
?>
<form id="recipe" action="food_data_2.php" method="get">  
 <p>Choose Recipe:<br>
<select name=recipeNo id=select>
<?php 
	while ($row = $recipe_list_result->fetch_assoc()) {
		if($row["Recipe_No"]==$recipeNo) {
			echo "<p>"."<option value="."\"".$row["Recipe_No"]."\" selected>".$row["Recipe_Name"]."</option>";
			}
		echo "<p>"."<option value="."\"".$row["Recipe_No"]."\">".$row["Recipe_Name"]."</option>";
		}
?>	
</select><br>
<?php 
echo "<input type=\"hidden\" name=\"foodGroup\" value=".$foodGroup.">";
echo "<input type=\"hidden\" name=\"NDB_No\" value=".$ndbNo.">";
?>
<input type="submit" value="Show Recipe Ingredients List">
<?php 
// Now build the recipe query and table
$query = "SELECT 
FOOD_DESC.Long_Desc,
my_INGREDIENTS.Ingredient_Amt as \"Amount\",
my_INGREDIENTS.Ingredient_Units as \"Unit\",
my_INGREDIENTS.Ingredient_Factor * 100 as \"Grams\"
FROM dbData3.my_INGREDIENTS
JOIN FOOD_DESC on my_INGREDIENTS.NDB_No = FOOD_DESC.NDB_No
WHERE
my_INGREDIENTS.Recipe_No = ".$recipeNo;

$ingredients_result = mysqli_query($mysqli,$query);

// fetch the field names, open a table and put the names in the first row of the table

echo "<table style= \"width: 100%; \">";
echo "<caption>Ingredients for Recipe No: ".$recipeNo."</caption>";
echo "<thead><tr>";

foreach ($ingredients_result->fetch_fields() as $val) {
	echo "<th>";
	printf("%s", $val->name);
	echo "</th>";
};
 
echo "</tr></thead>";

// fetch the data and put them in the next rows of the table then close the table

echo "<tbody>";

while ($ingredients = $ingredients_result->fetch_assoc()) {

	echo "<tr>";

	foreach($ingredients as $val) {
		echo "<td>";
		echo $val;
		echo "</td>";
	}

	echo "</tr>";
};

echo "</tbody>";
echo "<tfoot>";
echo "<tr>";
echo "<td	colspan=\"5\">";
echo "Source US Department of Agriculture www.UDSA.gov";
echo "</td>";
echo 	"</tr>";
echo "</tfoot>";
echo "</table>";	
?>
</p>
</form>
</div>
<! ------------------------------------------------------------------------------>

<div>
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
//    var_dump($result)."<br>";
  
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

    while ($nutrient_info = $result->fetch_assoc()) {
    	echo "<tr>";
    	
  		foreach ($nutrient_info as $val) {
  		echo "<td>";
		echo "$val";
		echo "</td>";
  		}
	
  	echo "</tr>";		
  	};
  
  echo "<tfoot>";
  echo "<tr>";
  echo "<td	colspan=\"2\">"; 
  echo "Source US Department of Agriculture www.UDSA.gov";
  echo "</td>";
  echo 	"</tr>";	
  echo "</tfoot>";	
  echo "</table>";
 
?>    
</div>
</body>
</html>