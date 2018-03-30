<?php require_once('Connections/BlogConnection.php'); ?>
<?php

if (!function_exists("GetSQLValueString")) 
	{
	
	function GetSQLValueString($theValue, $theType, $BlogConnection, $theDefinedValue = "", $theNotDefinedValue = "")
		
		{
			
		if (PHP_VERSION < 6) 
  			{
    		$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  			}

  		$theValue = mysqli_real_escape_string($BlogConnection, $theValue);

		switch ($theType) 
			{
		    case "text":
		      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		      break;    
		    case "long":
		    case "int":
		      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
		      break;
		    case "double":
		      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
		      break;
		    case "date":
		      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		      break;
		    case "defined":
		      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
		      break;
  			}
  		return $theValue;
		}
	}
//Post the comment form values to blog_comments table:
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO blog_comments_new (text_comment, id_article, datetime_comment, email_comment, name_comment, title_comment, validate_comment) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['text_comment'], "text",$BlogConnection),
                       GetSQLValueString($_POST['id_article'], "int",$BlogConnection),
                       GetSQLValueString($_POST['datetime_comment'], "date",$BlogConnection),
                       GetSQLValueString($_POST['email_comment'], "text",$BlogConnection),
                       GetSQLValueString($_POST['name_comment'], "text",$BlogConnection),
                       GetSQLValueString($_POST['title_comment'], "text",$BlogConnection),
                       GetSQLValueString($_POST['text_validation'], "text",$BlogConnection));

  mysqli_select_db($BlogConnection, $database_BlogConnection);
  $Result1 = mysqli_query($BlogConnection, $insertSQL);// or die(mysql_error());

  $insertGoTo = "article.php?id_article=" . $row_rsArticles['id_article'] . "";

 // BELOW IS SOME VERY SKETCHY CODE >> results in ...?id_article=&id_article=NNNNNN which works but...   
    if (isset($_SERVER['QUERY_STRING'])) {
       $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
       $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
 header(sprintf("Location: %s", $insertGoTo));
}
// Begin common queries

mysqli_select_db($BlogConnection, $database_BlogConnection);
$query_rsTopics = "SELECT * FROM blog_topics ORDER BY title_topic DESC";
$rsTopics = mysqli_query($BlogConnection, $query_rsTopics); //or die(mysql_error());
$row_rsTopics = mysqli_fetch_assoc($rsTopics);
$totalRows_rsTopics = mysqli_num_rows($rsTopics);

mysqli_select_db($BlogConnection, $database_BlogConnection);
$query_rsTopicList = "SELECT * FROM blog_topics ORDER BY title_topic DESC";
$rsTopicList = mysqli_query($BlogConnection, $query_rsTopicList); // or die(mysql_error());
$row_rsTopicList = mysqli_fetch_assoc($rsTopicList);
$totalRows_rsTopicList = mysqli_num_rows($rsTopicList);

$maxRows_rsArticles = 10;
$pageNum_rsArticles = 0;
if (isset($_GET['pageNum_rsArticles'])) {
  $pageNum_rsArticles = $_GET['pageNum_rsArticles'];
}
$startRow_rsArticles = $pageNum_rsArticles * $maxRows_rsArticles;

$colname_rsArticles = "-1";
if (isset($_GET['id_article'])) {
  $colname_rsArticles = $_GET['id_article'];
}

mysqli_select_db($BlogConnection, $database_BlogConnection);
$query_rsArticles = "SELECT * FROM blog_articles INNER JOIN blog_topics ON id_topic_article=id_topic WHERE id_article= ".$colname_rsArticles." ORDER BY date_article DESC";
$query_limit_rsArticles = sprintf("%s LIMIT %d, %d", $query_rsArticles, $startRow_rsArticles, $maxRows_rsArticles);
$rsArticles = mysqli_query($BlogConnection, $query_limit_rsArticles); //or die(mysql_error());
$row_rsArticles = mysqli_fetch_assoc($rsArticles);

if (isset($_GET['totalRows_rsArticles'])) {
  $totalRows_rsArticles = $_GET['totalRows_rsArticles'];
} else {
  $all_rsArticles = mysqli_query($BlogConnection, $query_rsArticles);
  $totalRows_rsArticles = mysqli_num_rows($all_rsArticles);
}
$totalPages_rsArticles = ceil($totalRows_rsArticles/$maxRows_rsArticles)-1;

$maxRows_rsComments = 10;
$pageNum_rsComments = 0;
if (isset($_GET['pageNum_rsComments'])) {
  $pageNum_rsComments = $_GET['pageNum_rsComments'];
}
$startRow_rsComments = $pageNum_rsComments * $maxRows_rsComments;

$colname_rsComments = "-1";
if (isset($_GET['id_article'])) {
  $colname_rsComments = $_GET['id_article'];
}
mysqli_select_db($BlogConnection, $database_BlogConnection);
$query_rsComments = sprintf("SELECT * FROM blog_comments_new WHERE id_article = %s", GetSQLValueString($colname_rsComments, "int",$BlogConnection));
$query_limit_rsComments = sprintf("%s LIMIT %d, %d", $query_rsComments, $startRow_rsComments, $maxRows_rsComments);
$rsComments = mysqli_query($BlogConnection, $query_limit_rsComments); // or die(mysql_error());
$row_rsComments = mysqli_fetch_assoc($rsComments);

if (isset($_GET['totalRows_rsComments'])) {
  $totalRows_rsComments = $_GET['totalRows_rsComments'];
} else {
  $all_rsComments = mysqli_query($BlogConnection, $query_rsComments);
  $totalRows_rsComments = mysqli_num_rows($all_rsComments);
}
$totalPages_rsComments = ceil($totalRows_rsComments/$maxRows_rsComments)-1;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  

<html xmlns="http://www.w3.org/1999/xhtml">  

<head>
<title>
EdgyDad's Blog
<?php echo $_GET['id_article']?>
</title>

<script type="text/javascript" src="js/rotator.js"></script>

<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

<script type="text/javascript">stLight.options({publisher:'8125a367-12b7-4772-91c6-384daedfd848'});</script>


<!-- RSS autodiscovery links START -->

<link rel="alternate" type="application/rss+xml" title="All EdgyDad"
      href="http://www.edgydad.com/edgydad_rss.php">
      
<link rel="alternate" type="application/rss+xml" title="EdgyDad Real Estate and Building"
      href="http://www.edgydad.com/edgydad_realestate_building_rss.php">
            
<!-- RSS autodiscovery links END -->  

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--
	<link rel="stylesheet" href="../BbigBbrands/css/reset.css" />
	<link rel="stylesheet" href="../BbigBbrands/css/default.css" />
-->    
    
<script type="text/javascript" src="js/jquery-1.3.1.min.js"></script>

<script type="text/javascript" src="js/functions.js"></script>

<!--[if IE 6]>
	<link rel="stylesheet" type="text/css" href="css/ie6.css" />
	<script type="text/javascript" src="js/DD_belatedPNG_0.0.7a.js"></script>
	<script type="text/javascript">
		//DD_belatedPNG.fix(); //pass a selector to fix
	</script>
<![endif]--> 

<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="css/ie7.css" />	
<![endif]--> 

<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="css/ie.css" />	
<![endif]--> 
<link href="css/BigBrandsDefault.css" rel="stylesheet" type="text/css" />
<style type="text/css">

	a:hover {
		color: #eeb702;
			}

</style>

<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-21738701-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

</head>

<body id="page">

<div id="wrapper"> 	
	<!--[START] .col1 -->
  <div class="col1"> 
		<a href="index.php"><img src="images/EdgyDadLogo.png" width="100%"  alt="EdgyDad's Logo" /></a>
		<!--[START] image rotator in sidebar -->
		<div id="slideshow">

<a href="#" class="active"><img src="images/ad1_whittier_sign.jpg" alt="Lot for Sale Design Build 1718 Whittier East Dallas White Rock Lake Texas" width="100%" /></a>
<a href="#" class="active"><img src="images/USCycling_Coach.jpg" alt="USA Cycling Certified Coach" width="100%" /></a>	
<a href="#" class="active"><img src="images/IM_CDA_2007.jpg" alt="Ironman CDA 2007" width="100%" /></a>
<a href="#" class="active"><img src="images/IM_CDA_2010.jpg" alt="Ironman CDA 2010" width="100%" /></a>
<a href="#" class="active"><img src="images/IM_Wisconsin.jpg" alt="Ironman Ironman Wisconsin" width="100%" align="middle" /></a>		
<a href="#" class="active"><img src="images/EDREA_Logo 1.jpg" alt="Biff's Real Estate Company Logo" width="100%" /></a>
<a href="#" class="active"><img src="images/live_local_3.JPG" alt="Live Local East Dallas" width="100%" align="absmiddle"/></a>
<a href="#" class="active"><img src="images/Biff_in_Barcelona.png" alt="Picture of Biff in Barcelona" width="100%" /></a>
<a href="#" class="active"><img src="images/Building_Creds.jpg" alt="ad2" width="100%" /></a>

		</div>  
        <!--[END] image rotator in sidebar -->
        <br />
        <br />
        
<p class="subtext">Pictures are worth a thousand words!...</p>
	<h3>Who is EdgyDad?</h3>
	<p>Husband, father, inline skater, cycling and triathlon athlete and sometimes coach, graduate civil engineer, commercial and residential and commercial Broker Realtor&reg; working in Ellum, Expo Park, Munger, Peak Suburban, PD 98, PD 269, Swiss Avenue, Baylor PD, all of in-town east Dallas, former home building land acquisitions executive, home builder, home designer (chief architect X3 design solutions), LEED Green Associate (GA), NAHB Green Professional (CGP), NAHB Graduate Builder (CGB), Universal Design and Accessability student and Certified Aging in Place Specialist (CAPS), Advanced Historic Home Specialist certified by Preservation Dallas..........EdgyDad is Biff Bailey of Dallas, Texas</p>
</div>
	<!-- [END] .col1 -->
	<!--[START] .col2 -->
	<div class="col2">
      <table width="100%" >
        <tr>
          <td><h3>Blog Topics</h3></td>
        </tr>
        <?php do { ?>
          <tr>
            <td><a href="topics.php?id_topic=<?php echo $row_rsTopics['id_topic']; ?>"><?php echo $row_rsTopics['title_topic']; ?></a></td>
          </tr>
          <?php } while ($row_rsTopics = mysqli_fetch_assoc($rsTopics)); ?>
      </table>
     
      <table width="100%" >
        <tr>
          <td><h4><a href="/real_estate_home.php">Biff Bailey's Real Estate Home Page</a></h4></td>
        </tr>
       
        <tr>
          <td><a href="contact_form.php">
            <h4>Contact EdgyDad</h4>
          </a></td>
        </tr>
        <tr>
          <td><a href="bio.php">
            <h4>About EdgyDad</h4>
          </a></td>
        </tr><tr>
          <td><h4><a href="/resources.php">Resource Links Page</a></h4></td>
        </tr>
        <tr>
          <td><a href="admin/index.php">
            <h4 class="light_rose_text" color=#000333>Admin Pages</h4>
          </a></td>
        </tr>
      </table>
<!-- Google Reader Shared Items script starts here--> 
      <script type="text/javascript" src="https://www.google.com/reader/ui/publisher-en.js"></script>
<script type="text/javascript" src="https://www.google.com/reader/public/javascript/user/07633767008004657036/state/com.google/broadcast?n=5&callback=GRC_p(%7Bc%3A%22blue%22%2Ct%3A%22Biff's%20shared%20items%22%2Cs%3A%22false%22%2Cn%3A%22true%22%2Cb%3A%22false%22%7D)%3Bnew%20GRC"></script>
<!--Googe Reader Shared Items end-->
<!-- InstanceBeginEditable name="edit_area_column_2" -->
 
 <!-- InstanceEndEditable -->
</div>
		
	<!--[END] .col2 -->
	<!--[START] .col3 -->
    <div class="col3">
	<!-- InstanceBeginEditable name="edit_column_3_main_content" -->
       <div class="content">
        <h2 class="tagLine"><?php echo $row_rsArticles['title_article']; ?></h2>
        <span class="st_sharethis" displayText="ShareThis" ></span>
      </div>
      <p><?php echo $row_rsArticles['description_article']; ?></p>
      <p>Last Update: <?php echo $row_rsArticles['date_article']; ?></p>
      <p><?php echo $row_rsArticles['text_article']; ?></p>
      <p class="padBottom"></p>
      <h3>Reader Comments</h3>
       <table border="0">
      <?php do { ?>
        <tr>
            <td colspan="7"><?php echo $row_rsComments['title_comment']; ?></td>
        </tr>
        <tr>
            <td>by: <?php echo $row_rsComments['name_comment']; ?></td>
            <td colspan="6">on: <?php echo $row_rsComments['datetime_comment']; ?></td>
        </tr>
        <tr>
          <td colspan="7"><?php echo $row_rsComments['text_comment']; ?></td>
        </tr>
        <tr>
          <td colspan="7"><hr/></td>
        </tr>
        <?php } while ($row_rsComments = mysqli_fetch_assoc($rsComments)); ?>
    </table>
    <h3>Add a Comment</h3>
   </form>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Your Name:</td>
          <td><input type="text" name="name_comment" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Your Email:<br/><a onclick="PrivacyPolicy()">Privacy Policy</a></td>
          <td><span id="sprytextfield1">
          <input type="text" name="email_comment" value="" size="32" />
          <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span><br/>Comments without valid email address may be deleted.</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Title Your<br/>Comment:</td>
          <td><input type="text" name="title_comment" value="" size="69" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Write Your<br/>Comment Here:</td>
          <td><textarea name="text_comment" cols="32"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right"><p>Type A74-Bxp/9<br/>
          to validate yourself</p></td>
          <td><span id="sprytextfield2">
            <input type="text" name="text_validation" id="text_validation" />
          <span class="textfieldRequiredMsg">A value is required.</span>
          <span class="textfieldInvalidFormatMsg">Invalid format.</span>
          </span></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input name="Post Your Comment" type="submit" id="Post Your Comment" value="Post Your Comment" /></td>
        </tr>
      </table>
      <input type="hidden" name="id_article" value="<?php echo $row_rsArticles['id_article']; ?>" />
      <input type="hidden" name="datetime_comment" value="" />
      <input type="hidden" name="MM_insert" value="form2" />
    </form>
    <p>&nbsp;</p>
<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-6006465-2");
	pageTracker._trackPageview();
	} catch(err) {}
	var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email", {validateOn:["blur", "change"]});
	var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "custom", {pattern:"A00-Aaa/0"});
</script>
   
    
<!--[START] #footer -->
<div id="footer">
	<div id="copyright"> &copy; Copyright 2010 - 2017 Biff Bailey, EdgyDad.com , Bbig LLC and Bbig Builders Incorporated. EdgyDad and EdgyDad's are trade names created by Bbig Builders Incorporated in January, 2008. Conforms to W3C Standard <a href="http://validator.w3.org/check?uri=referer" title="Validate XHTML">XHTML</a> &amp; <a title="Validate CSS" href="http://jigsaw.w3.org/css-validator/check/referer"> CSS</a></div>
	</div>
</div>
<!--[END] .col3 -->
</div>
<!--[END] #wrapper -->

<!-- The code below makes the slideshow work -->

<script type="text/javascript" src="js/rotator.js"></script>
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript">
	try {
		var pageTracker = _gat._getTracker("UA-6006465-2");
		pageTracker._trackPageview();
		} catch(err) {}
</script>
</body>
</html>
<?php
mysqli_free_result($rsTopics);
mysqli_free_result($rsTopicList);
mysqli_free_result($rsArticles);
mysqli_free_result($rsComments);
?>
