<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Cp1252">
<title>edgyDAD NDB Search</title>
<style>
#popUpMask {
width:100%;
height:100%;
opacity: 1;
top:0;
left:0;
display:none;
position:fixed;
background-color:#006600;
overflow:auto
}
#x_form {
position:absolute;
right:-14px;
top:-14px;
cursor:pointer
}
#popupForm {
position:absolute;
left:50%;
top:17%;
opacity: 1;
margin-left:-202px;
border-style: groove;
border-width: 10 px;
border-color: #eeb000;
background-color: #ffffff;
padding: 20px
}
#form_2 {
max-width:300px;
min-width:250px;
padding:10px 50px;
border:30px;
border-color: #ffff00;
border-radius:10px;
background-color:#eeb000;
font-size: 0.8em;
font-color: #000000;
}
body {
	font-family: "Verdana", "Arial";
	background: #ffffff;
	font-size: 1em;
	}
h1 {				 
	font-size: 1.0em;
	color: #eeb702;
	}
	
select {
	font-size: 0.8em;
	color: #000000;	
	width: 90%
	}
input {
	font-size: 0.8em;
	color: #ff0000;
	width: 90%	
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
	width: 99%;
	float: left;
	background-color: #ffffff;
	border-style: none;
	border-color: #00aa00;
	}
#divChooseFood {
	width: 99%;
	float: left;
	background-color: #ffffff;
	border-style: none;
	border-color: #00aa00;
	}
#formChooseFood {
	width: 99%;
	float: left;
	background-color: #ffffff;
	border-style: none;
	border-color: #00aa00;
	}	
#divNutrientData {
	width: 99%;
	float: left;
	padding: 2em 0 0 0;
	background-color: #ffffff;
	border-style: none;
	border-color: #00aa00;
	}			
#Recipes {
	width: 99%;
	float: Left;
	background-color: #ffffff;
	border-style: none;
	border-color: #00aa00;
	}
#left_col {
	width: 49%;
	float: left;
	background-color: #ffffff;
	border-style: none;
	border-color: #00aa00;
	}
#divRightColumn {
	width: 49%;
	float: right;
	background-color: #ffffff;
	border-style: none;
	border-color: #00aa00;
	}	
#divInsertTest {
	width: 90%;
	float: right;
	background-color: #ffffff;
	border-style: solid;
	border-color: #00aa00;
	margin: 0;
	padding: 1em;
	}	
#selectFoodName {
	width: 99%;
	float: none;
	}			
</style>
<!-- jsShowHint to get suggestions from text in search box -->
<script>
function jsShowHint(str) {
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
<!-- jsAddRecipeWindow to insert recipe item into myIngredients -->
<script>
function jsAddRecipeWindow(){
	document.getElementById("divInsertTest").innerHTML="Hello, the Add Recipe function is not yet finished";
}
</script>
<!-- jsInsertIngredient to insert recipe item into myIngredients -->
<script>
function jsInsertIngredient() {
	document.getElementById("divInsertTest").innerHTML=("jsInsertIngredient says Hello!");
    selectedMeasureFactor = document.getElementById("selectMeasureFactor").value;
    inputRecipeNo = document.getElementById("inputRecipeNo").value;
    inputNDB_No = document.getElementById("inputNDB_No").value;
    inputIngredientAmount = document.getElementById("inputIngredientAmount").value;
    document.getElementById("divInsertTest").innerHTML=selectedMeasureFactor;
	confirm = window.confirm("Ingredient Amount: " + inputIngredientAmount + "\n"
			+ "Measure|Factor: " + selectedMeasureFactor + "\n"
			+ "Recipe No: " + inputRecipeNo + "\n"
			+ "NDB No: " + inputNDB_No + "\n")
	if (confirm == true)	{
		document.getElementById("divInsertTest").innerHTML="Posting Ingredient";
		 }
	else {document.getElementById("divInsertTest").innerHTML="Posting Cancelled";
	return;
	};
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("divInsertTest").innerHTML=xmlhttp.responseText;
    	}
  	}
	xmlhttp.open("GET","insertIngredient.php?selectedMeasureFactor="
			+selectedMeasureFactor
			+"&inputRecipeNo="
			+inputRecipeNo
			+"&inputNDB_No="
			+inputNDB_No
			+"&inputIngredientAmount="
			+inputIngredientAmount,
			true);
	xmlhttp.send();
	setTimeout(function(){location.reload()},10000);
}
</script>
<!--  This is the pop up JS code -->
<script type="text/javascript">
//Validating Empty Field
function check_empty() {
if (document.getElementById('name').value == "" || document.getElementById('email').value == "" || document.getElementById('msg').value == "") {
alert("Fill All Fields !");
} else {
document.getElementById('form').submit();
alert("Form Submitted Successfully...");
}
}
// Function to confirm data and insert New Recipe

function jsInsertRecipe() {
	document.getElementById("divInsertTest").innerHTML=("jsInsertRECIPE says Hello!");
    nameAddRecipe = document.getElementById("nameAddRecipe").value;
    servingsAddRecipe = document.getElementById("servingsAddRecipe").value;
    sizeServingAddRecipe = document.getElementById("sizeServingAddRecipe").value;
    unitServingAddRecipe = document.getElementById("unitServingAddRecipe").value;
    prepAddRecipe = document.getElementById("prepAddRecipe").value;
    photoURLAddRecipe = document.getElementById("photoURLAddRecipe").value;
 //   document.getElementById("divInsertTest").innerHTML=selectedMeasureFactor;
	confirm = window.confirm("Recipe Name: " + nameAddRecipe + "\n"
			+ "Servings: " + servingsAddRecipe + "\n"
			+ "Serving Size: " + sizeServingAddRecipe + " " + unitServingAddRecipe + "\n"
			+ "Preparation: " + prepAddRecipe + "\n"
			+ "Photo URL: " + photoURLAddRecipe + "\n")
	if (confirm == true)	{
		document.getElementById("divInsertTest").innerHTML="Posting New Recipe";
		 }
	else {document.getElementById("divInsertTest").innerHTML="Posting Cancelled";
	return;
	};
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("divInsertTest").innerHTML=xmlhttp.responseText;
    	}
  	}
	xmlhttp.open("GET","insertRecipe.php?nameNewRecipe="
			+nameAddRecipe
			+"&noServingsNewRecipe="
			+servingsAddRecipe
			+"&servingSizeNewRecipe="
			+sizeServingAddRecipe
			+"&unitServingNewRecipe="
			+unitServingAddRecipe
			+"&prepNewRecipe="
			+prepAddRecipe
			+"&urlNewRecipe="
			+photoURLAddRecipe,		
			true);
	xmlhttp.send();
//	setTimeout(function(){location.reload()},10000);
	div_hide();
}
//Function To Display Popup
function div_show() {
document.getElementById('popUpMask').style.display = "block";
}
//Function to Hide Popup
function div_hide(){
document.getElementById('popUpMask').style.display = "none";
}
</script>

<?php 
//	set variables, get $q from URL
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
		$recipeNo="10";
	}
?>
<?php 
 // connect to database
	require_once('Connections/FoodConnection.php');
	//mysqli = new mysqli($host, $user, $password, $database);
	//if(!$mysqli) {
    //	die("Could not connect: " . mysqli_error($mysqli));
    //};
?>		
</head>

<body>
<!-- Page Heading----------------------------------------------------------->
<div id="heading">
<h1>edgyDAD's USDA Nutrition Database Search Page
<img src="images/usda.jpg" width="20em" height="20em"></h1>
<h6> &copy; 2014 2017 Builders Incorporated</h6>
</div>
<div id="left_col">
<!-- ----------------------------------------------------------------------->
	<div id="divChooseFood">
<!-- make a form to choose the food group and food with a suggestion box to help
	locate a food with submit buttons to refresh the page -->
		<form id="formChooseFood" action="food_data_2.php" method="get">
			<?php 
//	get the food groups from FD_DESC and draw the select list box
				$queryFoodGroupList = "SELECT * FROM FD_GROUP ORDER BY FD_GROUP.FdGrp_Desc ASC";
				$resultFoodGroupList = mysqli_query($mysqli,$queryFoodGroupList);
				echo "Choose Food Group:<br>";
				echo "<select name=foodGroup id=".$foodGroup.">";
				while ($row = $resultFoodGroupList->fetch_assoc()) {
					if($row["FdGrp_Cd"]==$foodGroup) {
						echo "<option value="."\"".$row["FdGrp_Cd"]."\""." selected>".$row["FdGrp_Desc"]."</option>";
						} 	else {
								echo "<option value="."\"".$row["FdGrp_Cd"]."\">".$row["FdGrp_Desc"]."</option>";
							}
				}				
				mysqli_free_result($resultFoodGroupList);
					
				echo "</select><br/>";
			?>
				<input type="submit" value="Refresh Food Name List with Food Group Names"><br>
				Choose Food Name:<br>
				<select name="NDB_No" id="selectFoodName">
					<?php 
//	get the long descriptions from FD_DESC and draw the select list box
						$queryFoodDescriptions = "SELECT Long_Desc,
							NDB_No
							FROM FOOD_DESC
							WHERE FdGrp_Cd=".$foodGroup."
							ORDER BY Long_Desc ASC";
						$resultFoodDescriptions = mysqli_query($mysqli,$queryFoodDescriptions);
						while ($row = $resultFoodDescriptions->fetch_assoc()) {
							if($row["NDB_No"]==$ndbNo) {
								echo "<p>"."<option value="."\"".$row["NDB_No"]."\" selected>".$row["Long_Desc"]."</option>";
							} 	else {
									echo "<p>"."<option value="."\"".$row["NDB_No"]."\">".$row["Long_Desc"]."</option>";
								}
						}
					?>	
				</select><br>
				<?php
// set hidden input for recipeNo in this form
					echo "<input type=\"hidden\" name=\"recipeNo\" value=".$recipeNo.">";
				?>
				<input type="submit" value="Refresh Nutriant List with Values for Selected Food">
		</form>	
<!-- create a form for the hint AJAX lookup jsShowHint -->
				<form id="hint">
					<p>For Suggestions, start typing a name in the input field below:<br>
					<span style = color:#000000>
					Search string: <input type="text" onkeyup="jsShowHint(this.value)">
					</span>
				</form>
				<span id="txtHint"></span></p>
<!-- Close div foodChooser -->
	</div>
	<div id="Measures">
<!-- Display the measures associated with the selected food -->
		<?php
//  get the resultMeasures and draw the select_Measures list box
// Get the measures to choose from when adding a food to a recipe
		$queryMeasures = "SELECT Amount,
		Msre_Desc, Gm_Wgt,
		Gm_Wgt/100 as Factor,
		Seq
		FROM WEIGHT_sr25
		WHERE NDB_No =".$ndbNo."
		ORDER BY Seq ASC";
		if(!$resultMeasures = mysqli_query($mysqli,$queryMeasures))	{
			echo "queryMeasures Failed!  ".$mysqli->errno." ".$mysqli->error."<br><br>";
		}
		else {
// fetch the field names, open a table and put the names in the first row of the table
		  	$fieldInfo = $resultMeasures->fetch_fields();
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
		  while ($measures = $resultMeasures->fetch_assoc()) {
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
		}
		mysqli_free_result($resultMeasures);	
		  ?>
<!-- Close div measures -->		
	</div>
	<div id="divNutrientData">
<!-- Get and display the selected food nutrient data -->
		<?php 
		
//get selected item nutrient data by running the stored proc
			$queryNutrientData = "SELECT
					NUTR_DEF.NutrDesc,
					WEIGHT.Msre_Desc as \"Measure Units\",
					WEIGHT.Gm_Wgt as \"Measure Weight\",
					NUTR_DEF.Units,
					NUT_DATA.Nutr_Val / 100 * WEIGHT.Gm_Wgt as \"Nutrient Value / Measure\"
					FROM NUT_DATA
						JOIN FOOD_DESC on FOOD_DESC.NDB_No = NUT_DATA.NDB_No
						JOIN WEIGHT on WEIGHT.NDB_No = NUT_DATA.NDB_No
						JOIN NUTR_DEF on NUTR_DEF.Nutr_No = NUT_DATA.Nutr_No
					WHERE NUT_DATA.NDB_No = ".$ndbNo."
					ORDER BY NUT_DATA.Nutr_No ASC";
			$resultNutrientData = mysqli_query($mysqli,$queryNutrientData);
//run the stored proc
//    		$query = "CALL "."query_basic_data_measures_on_NDB_No(\"".$ndbNo."\")";
//	Check the $result is boolean 0 and dump the $mysqli array if so dump the variable
    		if(!$resultNutrientData) {
    			echo "resultNutrientData:<br>";
    			var_dump($resultNutrientData)."<br>";
    		};
  
// fetch the field names, open a table and put the names in the first row of the table
  
  			$fieldInfo = $resultNutrientData->fetch_fields();
			echo "<table style= \"width: 100%; height: 400px;\">";
  			echo "<caption>USDA Nutrition Database SR27<br>Basic Nutrition Info"; // for ".$ndbNo.", ".$foodName."</caption>";
  			echo "<thead><tr>";
  			foreach ($fieldInfo as $val) {
  				echo "<th>";
  				printf("%s", $val->name);
  				echo "</th>";
  			};
			echo "</tr></thead>";	
  
// fetch the data and put them in the next rows of the table then close the table 

    		while ($nutrient_info = $resultNutrientData->fetch_assoc()) {
    			echo "<tr>";
    	
  				foreach ($nutrient_info as $val) {
  					echo "<td>";
					echo "$val";
					echo "</td>";
  				};
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
 // release the result set
 			mysqli_free_result($resultNutrientData); 			
 		?>
 <!-- Close div food nutrient data -->		 
	</div>
<!-- Close div left column -->
</div>

<div id="divRightColumn">
	<div id="Recipes">
<!--  open the form and select box -->
		<form id="formSelectRecipe" action="food_data_2.php" method="get">  
			Choose Recipe:<br>
			<select name="recipeNo">
				<?php 
// get the recipes list
				$queryRecipeList = "SELECT Recipe_Name,
					Recipe_No 
					FROM dbData3.my_RECIPES 
					ORDER BY Recipe_Name ASC";
				if(!$resultRecipeList = mysqli_query($mysqli,$queryRecipeList))	{
					print_r("Recipe List QUERY FAILED! ".$mysqli->errno." ".$mysqli->error."<br><br");
				}
				else {
					$fieldInfoRecipeList = $resultRecipeList->fetch_fields();
					$recipeList = $resultRecipeList->fetch_assoc();
// 	write select box options from recipe list
					while ($row = $resultRecipeList->fetch_assoc()) {
						if($row["Recipe_No"]==$recipeNo) {
						echo "<option value="."\"".$row["Recipe_No"]."\" selected>".$row["Recipe_Name"]."</option>";
						}
					echo "<option value="."\"".$row["Recipe_No"]."\">".$row["Recipe_Name"]."</option>";
					}
					mysqli_free_result($resultRecipeList);
				};
						?>		
<!-- close the select box -->		
			</select><br>
<!--   add the food group and ndb number variables -->
			<?php 
				echo "<input type=\"hidden\" name=\"foodGroup\" value=".$foodGroup.">";
				echo "<input type=\"hidden\" name=\"NDB_No\" value=".$ndbNo.">";
			?>
			<input type="submit" value="Show Recipe Ingredients List">
<!--  close Select Recipe form   -->
		</form>			
			
			
<!-- Add a New Recipe Popup and Post-->
<div id="popUpMask">
	<!-- Popup Starts Here -->
	<div id="popupForm">
		<!-- Add Recipe Form -->
		<form action="#" id="form_2" method="post" name="formAddRecipe">
			<img id="x_form" src="/x_form.png" onclick ="div_hide()">
			<p>Add a Recipe with this Information:</p>
			<br>
			<input id="nameAddRecipe" name="nameNewRecipe" placeholder="New Recipe Name" type="text"><br/>
			<br/>
			<input id="servingsAddRecipe" name="servingsAddRecipe" placeholder="Number of Servings" type="number"><br/>
			<br/>
			<input id="sizeServingAddRecipe" name="sizeServingAddRecipe" placeholder="Serving Size" type="number"><br/>
			<br/>
			<p>Select Serving Size:</p>
				<select id="unitServingAddRecipe" name="unitServingAddRecipe" placeholder="Serving Size Units" type="number">
					<option value="Piece">Piece</option>
					<option value="Slice">Slice</option>
					<option value="Cup">Cup</option>
					<option value="Tbsp">Tbsp</option>
					<option value="Ounce">Ounce</option>
				</select>	
			<br/>
			<textarea id="prepAddRecipe" name="prepAddRecipe" placeholder="Preparation Instructions"></textarea><br/>
			<br/>
			<input id="photoURLAddRecipe" name="photoURLAddRecipe" placeholder="Photo URL" type="url"><br/>
			<br/>
			<button type="button" onclick="jsInsertRecipe()" id="submit">Add the Recipe!</button>
		</form>
<!-- popupForm div Ends Here -->			
	</div>
<!-- popUpMask Div Ends Here -->
</div>
<!-- Display Add a New Recipe Popup Button -->
<button id="popup" onclick="div_show()">Add a New Recipe</button>			

		<?php 
// Now build the ingredients table
// Get the recipe name for table caption
		$queryRecipeName = "SELECT * FROM dbData3.my_RECIPES WHERE my_RECIPES.Recipe_No = ".$recipeNo;
		$resultRecipeName = mysqli_query($mysqli,$queryRecipeName);
		$recipeNametemp = $resultRecipeName->fetch_assoc();
		$recipeName = $recipeNametemp["Recipe_Name"];
// Open the table and set the caption
		echo "<table style= \"width: 100%; \">";
		echo "<caption>Ingredients for Recipe No: ".$recipeNo.", ".$recipeName."</caption>";
		echo "<thead><tr>";
		mysqli_free_result($resultRecipeName);
// Get the ingredients for selected recipeNo
		$queryIngredients = "SELECT
				FOOD_DESC.Long_Desc,
				my_INGREDIENTS.Ingredient_Amt as \"Amount\",
				my_INGREDIENTS.Ingredient_Units as \"Unit\",
				my_INGREDIENTS.Ingredient_Factor * 100 as \"Grams\"
				FROM dbData3.my_INGREDIENTS
				JOIN FOOD_DESC on my_INGREDIENTS.NDB_No = FOOD_DESC.NDB_No
				WHERE
				my_INGREDIENTS.Recipe_No = ".$recipeNo;
		$resultIngredients = mysqli_query($mysqli,$queryIngredients);
// fetch the field names, put the names in the first row of the table
		foreach ($resultIngredients->fetch_fields() as $val) {
			echo "<th>";
			printf("%s", $val->name);
			echo "</th>";
		};
		echo "</tr></thead>";
// fetch the data and put them in the next rows of the table then close the table
		echo "<tbody>";
		while ($ingredients = $resultIngredients->fetch_assoc()) {
			echo "<tr>";
			foreach($ingredients as $val) {
				echo "<td>";
				echo $val;
				echo "</td>";
			};
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
<!-- Close div recipes -->	
	</div>
<!--  Make a form to enter amount, choose measure and add current $ndbNo to my_ingredients table for current $recipeNo  -->
	<div id="divAddRecipeItem">
		<form  action = "" onsubmit="" method="get" >
			<fieldset>
				<legend>Add the current ingredient to the recipe:</legend>
					Choose Measure:<br>
					<select name=selectMeasureFactor id="selectMeasureFactor">
						<?php 
// Get the measures to choose from when adding a food to a recipe
						$queryMeasures2 = "SELECT Amount,
							Msre_Desc, Gm_Wgt,
							Gm_Wgt/100 as Factor,
							Seq
							FROM WEIGHT_sr25
							WHERE NDB_No =".$ndbNo."
							ORDER BY Seq ASC";
						if(!$resultMeasures2 = mysqli_query($mysqli,$queryMeasures))	{
							echo "queryMeasures2 Failed!  ".$mysqli->errno." ".$mysqli->error."<br><br>";
						}	else {
								while ($row = $resultMeasures2->fetch_assoc()) {
									echo "<p>"."<option value="."\"".$row["Msre_Desc"]."|".$row["Factor"]."\">".$row["Msre_Desc"]." ".$row["Factor"]."</option>";
								}
						}
						?>	
					</select>
					Choose Amount:
					<input type = "number" name = inputAddIngredientAmount id="inputIngredientAmount" size="4">
					<?php 
						echo "<input type=\"hidden\" id= \"inputRecipeNo\" name=\"recipeNo\" value=".$recipeNo.">";
						echo "<input type=\"hidden\" id = \"inputNDB_No\" name=\"NDB_No\" value=".$ndbNo.">"
					?>
					<button type="button"  onclick="jsInsertIngredient()" >Add the Item to the Recipe</button>
				
					
			</fieldset>
		</form>
<!-- Close divAddRecipeItem -->
	</div>
<!-- Make a div to take the test output from jsInsertIngredient -->
	<div id = "divInsertTest">
	jsInsertIngredient Results: 
<!-- Close divInsertTest -->	
	</div>
<!-- Get and display the recipe summary for selected recipe -->
	<div id = "divRecipeSummary">
	<?php 
	//run the stored proc to get the Recipe Summary for the selected recipe
	$queryRecipeSummary = "CALL "."queryRecipeSummary(\"".$recipeNo."\")";
	$resultRecipeSummary = mysqli_query($mysqli,$queryRecipeSummary);
	
	
//	Check the $result is boolean 0 and dump the $mysqli array if so dump the variable
    		if(!$resultRecipeSummary) {
    			var_dump($mysqli);
    		};
  
// fetch the field names, open a table and put the names in the first row of the table
  
  			$fieldInfo = $resultRecipeSummary->fetch_fields();
			echo "<table style= \"width: 100%; height: 400px;\">";
  			echo "<caption>USDA Nutrition Database SR27<br>Basic Nutrition Info for Recipe No: ".$recipeNo.", ".$recipeName."</caption>";
  			echo "<thead><tr>";
  			foreach ($fieldInfo as $val) {
  				echo "<th>";
  				printf("%s", $val->name);
  				echo "</th>";
  			};
			echo "</tr></thead>";	
  
// fetch the data and put them in the next rows of the table then close the table 

    		while ($nutrient_info = $resultRecipeSummary->fetch_assoc()) {
    			echo "<tr>";
    			foreach ($nutrient_info as $val) {
  				echo "<td>";
				echo "$val";
						echo "</td>";
  				};
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
<!-- Close divRecipeSummary -->	
</div>	
</body>
</html>