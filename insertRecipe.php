<?php 
// connect to database

$host = "dbData3.db.3273326.hostedresource.com";
$database = "dbData3";
$user = "dbData3";
$password = "Ironman1726!";

$mysqliInsert = new mysqli($host, $user, $password, $database);
	if(!$mysqliInsert) {
    	die("Could not connect: " . mysqli_error($mysqli));
    };


//	Get the variables
//echo var_dump($_REQUEST);
$recipeName = $_REQUEST["nameNewRecipe"];
$noServings = $_REQUEST["noServingsNewRecipe"];
$servingSize = $_REQUEST["servingSizeNewRecipe"];
$servingUnit =$_REQUEST["unitServingNewRecipe"];
$prepInstruction = $_REQUEST["prepNewRecipe"];
$photoURL = $_REQUEST["urlNewRecipe"];
//  Explode $measureFactor
// $ingredientMeasuretemp = explode("|","$measureFactor");
// $ingredientMeasure = $ingredientMeasuretemp[0];
// $ingredientFactortemp = explode("|","$measureFactor");
// $ingredientFactor = $ingredientFactortemp[1];

//	Construct the query
$queryInsertRecipe = "INSERT INTO my_RECIPES (
		Recipe_Name, No_Servings, Serving_Size, Serving_Unit, Prep_Instruction, Photo_URL) 
		VALUES ("
		."\""
		.$recipeName
		."\","
		.$noServings
		.","
		.$servingSize
		.","
		."\""
		.$servingUnit
		."\","
		."\""
		.$prepInstruction
		."\","
		."\""
		.$photoURL
		."\""
		.")";

// Make the query:
		if(!$resultInsertRecipe = $mysqliInsert->query($queryInsertRecipe)) {
			echo "Couldn't Insert!  Check your work!!!".var_dump($queryInsertRecipe). "<br>";
		}	
		else {
			echo "Insert Complete!";//.var_dump($queryInsertIngredient)."<br>";
			}
// Free memory, close connection
mysqli_close($mysqliInsert);		
			
?>