<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>dragNdropLetters</title>
<style>

div {
float: left;
}

p {
float: left;
}

.dropArea {
width: 80%; 
margin-top: 2%; 
height: 40%; 
padding: 1em; 
border:2px solid #aaaaaa; 
float: left;
clear: both;
}

#bank {

}
#word {

}

.letter {
	font-size: xx-large;
	text-align: center;
	float: left;
	width: 1em;
	draggable: true; 
	border: 2px solid #aaaaaa;
	background-color: #00aa00; 
	font-color: #0000aa; 
	margin: 2%;
	padding:1%;
	ondragstart: "drag(event)";
}


</style>

<script>
var gData=" ";
var data;

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
    ev.preventDefault();
    data = ev.dataTransfer.getData("text");
    gData = gData + data;
    ev.target.appendChild(document.getElementById(data));
    document.getElementById("jsOut").innerHTML = gData;
}
</script>

<script>
function getStatus(){
document.getElementById("jsOut2").innerHTML = "getStatus called"	;
// var xhr = new XMLHttpRequest();
// xhr.open("GET", "http://www.dictionaryapi.com/v1/references/sd2/xml/school?key="a8c54874-f520-4019-afc0-a7a75d80520a", false);
// Add your code below!
// xhr.send();
// document.getElementById("jsOut2").innerHTML = xhr[responseText]+"End of Response";
//console.log(xhr.status);
//console.log(xhr.statusText);
}
</script>

</head>

<body>


<div class="dropArea" ondrop="drop(event)" ondragover="allowDrop(event)" >

	<div class="letter" id="A" draggable= "true" ondragstart = "drag(event)">
	A
	</div>
	<div class="letter" id="E" draggable= "true" ondragstart = "drag(event)">
	E
	</div>
	<div class="letter" id="I" draggable= "true" ondragstart = "drag(event)">
	I
	</div>
	<div class="letter" id="A" draggable= "true" ondragstart = "drag(event)">
	A
	</div>
	<div class="letter" id="M" draggable= "true" ondragstart = "drag(event)">
	M
	</div>
	<div class="letter" id="R" draggable= "true" ondragstart = "drag(event)">
	R
	</div>
	<div class="letter" id="C" draggable= "true" ondragstart = "drag(event)">
	C
	</div>
	<div class="letter" id="N" draggable= "true" ondragstart = "drag(event)">
	N
	</div>
</div>
</br>
<div>
WORD SPACE:
</div>
<div class="dropArea" ondrop="drop(event)" ondragover="allowDrop(event)" >
</div>
<div>
<button type="button" onclick= "getStatus()" color="green">
Get Dictionary Status</button>
<button type="button" color="red" onclick= "http://www.edgydad.com/dragNdrop.php" >
Reset / Reload</button>
</div>


<div class=" dropArea"id="jsOut">
No Output
</div>

<div class="dropArea" id="jsOut2">
No Output
</div>

</body>
</html>