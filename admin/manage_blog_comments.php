<?php require_once('../Connections/BlogConnection.php'); ?>
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
  $updateSQL = sprintf("UPDATE blog_comments SET text_comment=%s, id_article=%s, datetime_comment=%s, email_comment=%s, name_comment=%s, title_comment=%s, validate_comment=%s WHERE id_comment=%s",
                       GetSQLValueString($_POST['text_comment'], "text"),
                       GetSQLValueString($_POST['id_article'], "int"),
                       GetSQLValueString($_POST['datetime_comment'], "date"),
                       GetSQLValueString($_POST['email_comment'], "text"),
                       GetSQLValueString($_POST['name_comment'], "text"),
                       GetSQLValueString($_POST['title_comment'], "text"),
                       GetSQLValueString($_POST['validate_comment'], "text"),
                       GetSQLValueString($_POST['id_comment'], "int"));

  mysql_select_db($database_BlogConnection, $BlogConnection);
  $Result1 = mysql_query($updateSQL, $BlogConnection) or die(mysql_error());

  $updateGoTo = "manage_blog_comments.php";
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

$colname_rsTopic_to_Update = "-1";
if (isset($_Get['id_topic'])) {
  $colname_rsTopic_to_Update = $_Get['id_topic'];
}
mysql_select_db($database_BlogConnection, $BlogConnection);
$query_rsTopic_to_Update = sprintf("SELECT * FROM blog_topics WHERE blog_topics.id_topic=%s ORDER BY title_topic DESC", GetSQLValueString($colname_rsTopic_to_Update, "int"));
$rsTopic_to_Update = mysql_query($query_rsTopic_to_Update, $BlogConnection) or die(mysql_error());
$row_rsTopic_to_Update = mysql_fetch_assoc($rsTopic_to_Update);
$totalRows_rsTopic_to_Update = mysql_num_rows($rsTopic_to_Update);

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
mysql_select_db($database_BlogConnection, $BlogConnection);
$query_rsArticles = sprintf("SELECT * FROM blog_articles INNER JOIN blog_topics ON id_topic_article=id_topic WHERE id_article=%s ORDER BY date_article DESC", GetSQLValueString($colname_rsArticles, "int"));
$query_limit_rsArticles = sprintf("%s LIMIT %d, %d", $query_rsArticles, $startRow_rsArticles, $maxRows_rsArticles);
$rsArticles = mysql_query($query_limit_rsArticles, $BlogConnection) or die(mysql_error());
$row_rsArticles = mysql_fetch_assoc($rsArticles);

if (isset($_GET['totalRows_rsArticles'])) {
  $totalRows_rsArticles = $_GET['totalRows_rsArticles'];
} else {
  $all_rsArticles = mysql_query($query_rsArticles);
  $totalRows_rsArticles = mysql_num_rows($all_rsArticles);
}
$totalPages_rsArticles = ceil($totalRows_rsArticles/$maxRows_rsArticles)-1;

$colname_rsArticle_to_Update = "-1";
if (isset($_GET['id_article'])) {
  $colname_rsArticle_to_Update = $_GET['id_article'];
}
mysql_select_db($database_BlogConnection, $BlogConnection);
$query_rsArticle_to_Update = sprintf("SELECT * FROM blog_articles INNER JOIN blog_topics ON id_topic_article=id_topic WHERE id_article = %s", GetSQLValueString($colname_rsArticle_to_Update, "int"));
$rsArticle_to_Update = mysql_query($query_rsArticle_to_Update, $BlogConnection) or die(mysql_error());
$row_rsArticle_to_Update = mysql_fetch_assoc($rsArticle_to_Update);
$totalRows_rsArticle_to_Update = mysql_num_rows($rsArticle_to_Update);

mysql_select_db($database_BlogConnection, $BlogConnection);
$query_rs_blog_comments = "SELECT * FROM blog_comments_2 ORDER BY blog_comments_2.id_article DESC, blog_comments_2.id_comment ASC ";
$rs_blog_comments = mysql_query($query_rs_blog_comments, $BlogConnection) or die(mysql_error());
$row_rs_blog_comments = mysql_fetch_assoc($rs_blog_comments);
$totalRows_rs_blog_comments = mysql_num_rows($rs_blog_comments);

$colname_rs_comment_to_modify = "0";
if (isset($_GET["id_comment"])) {
  $colname_rs_comment_to_modify = $_GET["id_comment"];
}
mysql_select_db($database_BlogConnection, $BlogConnection);
$query_rs_comment_to_modify = sprintf("SELECT * FROM blog_comments_2 WHERE blog_comments_2.id_comment=%s", GetSQLValueString($colname_rs_comment_to_modify, "int"));
$rs_comment_to_modify = mysql_query($query_rs_comment_to_modify, $BlogConnection) or die(mysql_error());
$row_rs_comment_to_modify = mysql_fetch_assoc($rs_comment_to_modify);
$totalRows_rs_comment_to_modify = mysql_num_rows($rs_comment_to_modify);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/edgydad_admin_template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<!-- InstanceBeginEditable name="doctitle" -->

<title>EdgyDad's Blog Manage Questions Commentse</title>

<!-- InstanceEndEditable -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--
	<link rel="stylesheet" href="../BbigBbrands/css/reset.css" />
	<link rel="stylesheet" href="../BbigBbrands/css/default.css" />
-->    
    <link href="../css/BigBrandsDefault.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="../test/js/jquery-1.3.1.min.js"></script>
	<script type="text/javascript" src="../test/js/functions.js"></script>

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
		<a href="../index.php"><img src="../images/EdgyDadLogo.png" width="100%"  alt="EdgyDad's Logo" /></a>
		<!--[START] image rotator in sidebar -->
		<div id="slideshow">
<!-- InstanceBeginEditable name="col_1_slides" --> 
<a href="#" class="adSpotLarge"><img src="../images/ad1_whittier_sign.jpg" alt="Lot for Sale Design Build 1718 Whittier East Dallas White Rock Lake Texas" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="../images/USCycling_Coach.jpg" alt="USA Cycling Certified Coach" width="100%" /></a>	
<a href="#" class="adSpotLarge active"><img src="/images/IM_CDA_2007.jpg" alt="Ironman CDA 2007" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="/images/IM_CDA_2010.jpg" alt="Ironman CDA 2010" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="/images/IM_Wisconsin.JPG" alt="Ironman Ironman Wisconsin" width="100%" /></a>		
<a href="#" class="adSpotLarge active"><img src="../images/EDREA_Logo 1.jpg" alt="Biff's Real Estate Company Logo" width="100%" /></a>
<a href="#" class="adSpotLarge active"><img src="/images/live_local_3.JPG" alt="Live Local East Dallas" width="100%" valign="center"/></a>
<a href="#" class="adSpotLarge active"><img src="../images/Biff_in_Barcelona.png" alt="Picture of Biff in Barcelona" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="/images/Building_Creds.jpg" alt="ad2" width="100%" /></a>
	
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
            <td><a href="../topics.php?id_topic=<?php echo $row_rsTopics['id_topic']; ?>"><?php echo $row_rsTopics['title_topic']; ?></a></td>
          </tr>
          <?php } while ($row_rsTopics = mysql_fetch_assoc($rsTopics)); ?>
      </table>
      <table width="100" border="0">
        <tr>
          <td><h4>Questions, Comments?</h4></td>
        </tr>
        <tr>
          <td><a href="../contact_form.php">
            <h4>Contact EdgyDad</h4>
          </a></td>
        </tr>
        <tr>
          <td><a href="index.php">
            <h4 class="light_rose_text" color=#000333>Admin Pages</h4>
          </a></td>
        </tr>
      </table> 

<table width="100" border="0" cellspacing="0" cellpadding="0" summary="Links to site administrative pages">
  <caption>
    Adminisitrative Menu
    </caption>
  <tr>
    <td><a href="list_topics.php">Manage Topics</a></td>
    </tr>
  <tr>
    <td><a href="list_articles.php">Manage Articles</a></td>
    </tr>
   <tr>
    <td height="49"><a href="admin_manage_comments.php">Manage Question, Comments</a></td>
    </tr>  
   <tr>
     <td><a href="manage_blog_comments.php">Manage Blog Comments</a></td>
   </tr>
   <tr>
     <td><a href="add_topic.php">Add Topic</a></td>
   </tr>
   <tr>
    <td><a href="index.php">Add Article</a></td>
    </tr>
    <tr>
    <td><a href="post_contract.php">Add Contract</a></td>
    </tr>
  <tr>
    <td><a href="post_coffee.php">Add Coffee</a></td>
  </tr>
  <tr>
    <td><a href="post_roast.php">Add Roast</a></td>
  </tr>
  <tr>
    <td><a href="coffee_log.php">Manage Coffee Log</a></td>
  </tr>
  <tr>
    <td><a href="roast_log.php">Manage Roast Log</a></td>
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
<h2 class="tagLIne">Article Comments:</h2>
<table width="90%" border="0" cellpadding="1" cellspacing="1">
  <?php do { ?>
      <tr>
        <td width="40%"><?php echo $row_rs_blog_comments['title_comment']; ?></td>
        <td width="30%" valign="middle" align="center"><a href="admin_manage_comments?id_question_comment=<?php echo $row_rs_questions_comments['id_question_comment']; ?>"><img src="../images/update.gif" width="23" height="23" hspace="5" vspace="0"/></a><a href="delete_blog_comment.php?id_comment=<?php echo $row_rs_blog_comments['id_comment']; ?>"><img src="../images/delete.gif" alt="Delete_Comment" width="23" height="23" hspace="10" vspace="0" /></a></td>
        <td width="10%"><?php echo $row_rs_blog_comments['id_comment']; ?></td>
        <td width="10%"><?php echo $row_rs_blog_comments['id_article']; ?></td>
        </tr>
      <tr>
        <td width="40%"><?php echo $row_rs_blog_comments['name_comment']; ?></td>
        <td><?php echo $row_rs_blog_comments['email_comment']; ?></td>
        <td width="20%" colspan="2"><?php echo $row_rs_blog_comments['datetime_comment']; ?></td>
        </tr>
      <tr>
        <td colspan="3"><?php echo $row_rs_blog_comments['text_comment']; ?></td>
        <td><?php echo $row_rs_blog_comments['validate_comment']; ?></td>
      </tr>
      <tr>
        <td colspan="4"><hr /></td>
      </tr>
<?php } while ($row_rs_blog_comments = mysql_fetch_assoc($rs_blog_comments)); ?>
</table>

<h2 id=tagLine>Update  Comment</h2>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Id_comment:</td>
      <td><?php echo $row_rs_comment_to_modify['id_comment']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Text_comment:</td>
      <td><textarea name="text_comment" cols="32"><?php echo htmlentities($row_rs_comment_to_modify['text_comment'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Id_article:</td>
      <td><input type="text" name="id_article" value="<?php echo htmlentities($row_rs_comment_to_modify['id_article'], ENT_COMPAT, 'utf-8'); ?>" size="69" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Datetime_comment:</td>
      <td><input type="text" name="datetime_comment" value="<?php echo htmlentities($row_rs_comment_to_modify['datetime_comment'], ENT_COMPAT, 'utf-8'); ?>" size="69" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Email_comment:</td>
      <td><input type="text" name="email_comment" value="<?php echo htmlentities($row_rs_comment_to_modify['email_comment'], ENT_COMPAT, 'utf-8'); ?>" size="69" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Name_comment:</td>
      <td><input type="text" name="name_comment" value="<?php echo htmlentities($row_rs_comment_to_modify['name_comment'], ENT_COMPAT, 'utf-8'); ?>" size="69" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Title_comment:</td>
      <td><input type="text" name="title_comment" value="<?php echo htmlentities($row_rs_comment_to_modify['title_comment'], ENT_COMPAT, 'utf-8'); ?>" size="69" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Validate_comment:</td>
      <td><input type="text" name="validate_comment" value="<?php echo htmlentities($row_rs_comment_to_modify['validate_comment'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id_comment" value="<?php echo $row_rs_comment_to_modify['id_comment']; ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
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

mysql_free_result($rsTopic_to_Update);

mysql_free_result($rsArticles);

mysql_free_result($rsArticle_to_Update);

mysql_free_result($rs_blog_comments);

mysql_free_result($rs_comment_to_modify);
?>
