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
$measureFactor= $_REQUEST["selectedMeasureFactor"];
$recipeNo=$_REQUEST["inputRecipeNo"];
$NDB_No=str_pad($_REQUEST["inputNDB_No"],5,"0",STR_PAD_LEFT);
$ingredientAmount=$_REQUEST["inputIngredientAmount"];
//  Explode $measureFactor
$ingredientMeasuretemp = explode("|","$measureFactor");
$ingredientMeasure = $ingredientMeasuretemp[0];
$ingredientFactortemp = explode("|","$measureFactor");
$ingredientFactor = $ingredientFactortemp[1];

//	Construct the query
$queryInsertIngredient = "INSERT INTO my_INGREDIENTS (
		Recipe_No, NDB_No, Ingredient_Amt, Ingredient_Units, Ingredient_Factor) 
		VALUES ("
		.$recipeNo.","
		."\"".$NDB_No."\","
		.$ingredientAmount.","
		."\"".$ingredientMeasure."\"".","
		.$ingredientFactor.")";

// Make the query:
		if(!$resultInsertIngredient = $mysqliInsert->query($queryInsertIngredient)) {
			echo "Couldn't Insert!  Did you choose amount?";//.var_dump($queryInsertIngredient)."<br>";
		}	
		else {
			echo "Insert Complete!";//.var_dump($queryInsertIngredient)."<br>";
			}
// Free memory, close connection
mysqli_close($mysqliInsert);		
			
?>