<?php require_once('../../Connections/BlogConnection.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "admin";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE blog_articles SET id_topic_article=%s, title_article=%s, description_article=%s, text_article=%s, date_article=%s WHERE id_article=%s",
                       GetSQLValueString($_POST['id_topic_article'], "int"),
                       GetSQLValueString($_POST['title_article'], "text"),
                       GetSQLValueString($_POST['description_article'], "text"),
                       GetSQLValueString($_POST['text_article'], "text"),
                       GetSQLValueString($_POST['date_article'], "date"),
                       GetSQLValueString($_POST['id_article'], "int"));

  mysql_select_db($database_BlogConnection, $BlogConnection);
  $Result1 = mysql_query($updateSQL, $BlogConnection) or die(mysql_error());

  $updateGoTo = "../article.php?id_article=" . $row_rsArticle_to_Update['id_article'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_BlogConnection, $BlogConnection);
$query_rsTopics = "SELECT * FROM blog_topics ORDER BY title_topic DESC";
$rsTopics = mysql_query($query_rsTopics, $BlogConnection) or die(mysql_error());
$row_rsTopics = mysql_fetch_assoc($rsTopics);
$totalRows_rsTopics = mysql_num_rows($rsTopics);

mysql_select_db($database_BlogConnection, $BlogConnection);
$query_rsTopicList = "SELECT * FROM blog_topics ORDER BY title_topic DESC";
$rsTopicList = mysql_query($query_rsTopicList, $BlogConnection) or die(mysql_error());
$row_rsTopicList = mysql_fetch_assoc($rsTopicList);
$totalRows_rsTopicList = mysql_num_rows($rsTopicList);

$maxRows_rsArticle_to_Update = 10;
$pageNum_rsArticle_to_Update = 0;
if (isset($_GET['pageNum_rsArticle_to_Update'])) {
  $pageNum_rsArticle_to_Update = $_GET['pageNum_rsArticle_to_Update'];
}
$startRow_rsArticle_to_Update = $pageNum_rsArticle_to_Update * $maxRows_rsArticle_to_Update;

$colname_rsArticle_to_Update = "-1";
if (isset($_GET['id_article'])) {
  $colname_rsArticle_to_Update = $_GET['id_article'];
}
mysql_select_db($database_BlogConnection, $BlogConnection);
$query_rsArticle_to_Update = sprintf("SELECT * FROM blog_articles INNER JOIN blog_topics ON id_topic_article=id_topic WHERE id_article=%s ORDER BY date_article DESC", GetSQLValueString($colname_rsArticle_to_Update, "int"));
$query_limit_rsArticle_to_Update = sprintf("%s LIMIT %d, %d", $query_rsArticle_to_Update, $startRow_rsArticle_to_Update, $maxRows_rsArticle_to_Update);
$rsArticle_to_Update = mysql_query($query_limit_rsArticle_to_Update, $BlogConnection) or die(mysql_error());
$row_rsArticle_to_Update = mysql_fetch_assoc($rsArticle_to_Update);

if (isset($_GET['totalRows_rsArticle_to_Update'])) {
  $totalRows_rsArticle_to_Update = $_GET['totalRows_rsArticle_to_Update'];
} else {
  $all_rsArticle_to_Update = mysql_query($query_rsArticle_to_Update);
  $totalRows_rsArticle_to_Update = mysql_num_rows($all_rsArticle_to_Update);
}
$totalPages_rsArticle_to_Update = ceil($totalRows_rsArticle_to_Update/$maxRows_rsArticle_to_Update)-1;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/edgydad_admin_template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<!-- InstanceBeginEditable name="doctitle" -->

<title>EdgyDad's Blog Update_Article</title>

<!-- InstanceEndEditable -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--
	<link rel="stylesheet" href="../BbigBbrands/css/reset.css" />
	<link rel="stylesheet" href="../BbigBbrands/css/default.css" />
-->    
    <link href="../../css/BigBrandsDefault.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="../js/jquery-1.3.1.min.js"></script>
	<script type="text/javascript" src="../js/functions.js"></script>

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

<!-- InstanceBeginEditable name="head" -->

<style type="text/css">

</style>

<!-- InstanceEndEditable -->

</head>

<body id="page">


<div id="wrapper"> 	

<!--[START] .col1 -->
<div class="col1"> 
		<a href="../../index.php"><img src="../../images/EdgyDadLogo.png" width="100%"  alt="EdgyDad's Logo" /></a>
		<!--[START] image rotator in sidebar -->
		<div id="slideshow">
<!-- InstanceBeginEditable name="col_1_slides" --> 

	<a href="#" class="adSpotLarge"><img src="../../images/ad1_whittier_sign.jpg" alt="Lot for Sale Design Build 1718 Whittier East Dallas White Rock Lake Texas" width="100%" /></a> 
    <a href="#" class="adSpotLarge"><img src="../../images/USCycling_Coach.jpg" alt="USA Cycling Certified Coach" width="100%" /></a> 
    <a href="#" class="adSpotLarge active"><img src="../../images/IM_CDA_2007.png" alt="Ironman CDA 2007" width="350" /></a> 
    <a href="#" class="adSpotLarge"><img src="../../images/IM_CDA_2010.png" alt="Ironman CDA 2010" width="350" /></a> 
    <a href="#" class="adSpotLarge"><img src="../../images/IM_CDA_Improved.png" alt="Ironman Coeur d'Alene Improvement" width="350" /></a> 
    <a href="#" class="adSpotLarge active"><img src="../../images/EDREA_Logo 1.jpg" alt="Biff's Real Estate Company Logo" width="100%" /></a> 
    <a href="#" class="adSpotLarge active"><img src="../../images/Biff_in_Barcelona.png" alt="Picture of Biff in Barcelona" width="100%" /></a> 
    <a href="#" class="adSpotLarge"><img src="../../images/Building_Creds.png" alt="ad2" width="350" /></a> 
	
<!-- InstanceEndEditable -->
</div>  
        <!--[END] image rotator in sidebar -->
        <br />
        <br />
        
<p class="subtext">Pictures are worth a thousand words!...</p>
	<h3>Who is EdgyDad?</h3>
	<p>Husband, father, inline skater, cycling and triathlon athlete and sometimes coach, graduate civil engineer, commercial and residential and commercial Broker Realtor&reg; working in Ellum, Expo Park, Munger, Peak Suburban, PD 98, PD 269, Swiss Avenue, Baylor PD, all of in-town east Dallas, former home building land acquisitions executive, home builder, home designer (chief architect X3 design solutions), LEED Green Associate (GA), NAHB Green Professional (CGP), NAHB Graduate Builder (CGB), Universal Design and Accessability student and Certified Aging in Place Specialist (CAPS), Advanced Historic Home Specialist certified by Preservation Dallas..........EdgyDad is Biff Bailey of Dallas, Texas</p>
</div>
<!--[END] .col1 -->
	
<!--[START] .col2 -->
<div class="col2">
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><h3>Topics</h3></td>
        </tr>
        <?php do { ?>
          <tr>
            <td><a href="../../topics.php?id_topic=<?php echo $row_rsTopics['id_topic']; ?>"><?php echo $row_rsTopics['title_topic']; ?></a></td>
          </tr>
          <?php } while ($row_rsTopics = mysql_fetch_assoc($rsTopics)); ?>
      </table>
      <table width="100" border="0">
        <tr>
          <td><h4>Questions, Comments?</h4></td>
        </tr>
        <tr>
          <td><a href="../../contact_form.php">
            <h4>Contact EdgyDad</h4>
          </a></td>
        </tr>
        <tr>
          <td><a href="../../admin/index.php">
            <h4 class="light_rose_text" color=#000333>Admin Pages</h4>
          </a></td>
        </tr>
      </table> 

<table width="100" border="0" cellspacing="0" cellpadding="0" summary="Links to site administrative pages">
  <caption>
    Adminisitrative Menu
    </caption>
  <tr>
    <td><a href="../../admin/list_topics.php">Manage Topics</a></td>
    </tr>
  <tr>
    <td><a href="../../admin/list_articles.php">Manage Articles</a></td>
    </tr>
   <tr>
    <td height="49"><a href="../../admin/admin_manage_comments.php">Manage Question, Comments</a></td>
    </tr>  
   <tr>
     <td><a href="../../admin/manage_blog_comments.php">Manage Blog Comments</a></td>
   </tr>
   <tr>
     <td><a href="../../admin/add_topic.php">Add Topic</a></td>
   </tr>
   <tr>
    <td><a href="../../admin/index.php">Add Article</a></td>
    </tr>
    <tr>
    <td><a href="../../admin/post_contract.php">Add Contract</a></td>
    </tr>
  <tr>
    <td><a href="../../admin/post_coffee.php">Add Coffee</a></td>
  </tr>
  <tr>
    <td><a href="../../admin/post_roast.php">Add Roast</a></td>
  </tr>
  <tr>
    <td><a href="../../admin/coffee_log.php">Manage Coffee Log</a></td>
  </tr>
  <tr>
    <td><a href="../../admin/roast_log.php">Manage Roast Log</a></td>
  </tr>
  <tr>
    <td><a href="<?php echo $logoutAction ?>">Logout</a></td>
    </tr>
</table>
<!-- InstanceBeginEditable name="edit_area_column_2" -->
<!-- INSERT SPECIAL MENU ITEMS HERE IN 100 PX TABLE BORDER 0-->
<!-- InstanceEndEditable -->
</div>
<!--[END] .col2 -->
	
<!--[START] .col3 -->
<div class="col3">
    
	<!-- InstanceBeginEditable name="edit_column_3_main_content" -->
    
   <div class="content">
  <h2 class="tagLine">Update Article Form:</h2>
</div>
   <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
     <table align="center">
       <tr valign="baseline">
         <td nowrap="nowrap" align="right">Title_article:</td>
         <td><input type="text" name="title_article" value="<?php echo htmlentities($row_rsArticle_to_Update['title_article'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
       </tr>
       <tr valign="baseline">
         <td nowrap="nowrap" align="right">Description_article:</td>
         <td><textarea name="description_article" cols="32"><?php echo htmlentities($row_rsArticle_to_Update['description_article'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
       </tr>
       <tr valign="baseline">
         <td nowrap="nowrap" align="right">Text_article:</td>
         <td><textarea name="text_article" cols="32"><?php echo htmlentities($row_rsArticle_to_Update['text_article'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
       </tr>
       <tr valign="baseline">
         <td nowrap="nowrap" align="right">&nbsp;</td>
         <td><input type="submit" value="Update record" /></td>
       </tr>
     </table>
     <input type="hidden" name="id_article" value="<?php echo $row_rsArticle_to_Update['id_article']; ?>" />
     <input type="hidden" name="id_topic_article" value="<?php echo htmlentities($row_rsArticle_to_Update['id_topic_article'], ENT_COMPAT, 'utf-8'); ?>" />
  <!--   <input type="hidden" name="date_article" value="<?php echo date("Y-m-d H:i:s"), ENT_COMPAT, 'utf-8'; ?>" />
  -->
     <input type="hidden" name="MM_update" value="form1" />
        </form>
  
    
    <!-- InstanceEndEditable -->
      
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

<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6006465-2");
pageTracker._trackPageview();
} catch(err) {}
</script>

<div id="footer">
    <div id="copyright"> &copy; Copyright 2010, 2011 Biff Bailey, EdgyDad.com and Bbig Builders Incorporated. EdgyDad and EdgyDad's are trade names created by Bbig Builders Incorporated in January, 2008. Conforms to W3C Standard <a href="http://validator.w3.org/check?uri=referer" title="Validate XHTML">XHTML</a> &amp; <a title="Validate CSS" href="http://jigsaw.w3.org/css-validator/check/referer"> CSS</a></div>
        <!--[END] #copyright -->
</div>
<!--[END] #footer -->
</div>
<!--[END] .col3 -->
</div>
<!--[END] #wrapper -->

<!-- The code below makes the slideshow work -->
<script type="text/javascript" src="/js/rotator.js"></script>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>

</body>

<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsTopics);

mysql_free_result($rsTopicList);

mysql_free_result($rsArticle_to_Update);
?>
