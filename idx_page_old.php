<?php require_once('Connections/BlogConnection.php'); ?>
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
	
  $logoutGoTo = "test/index.php";
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

$MM_restrictGoTo = "admin/login.php";
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO blog_articles (id_topic_article, title_article, description_article, text_article, date_article) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_topic_article'], "int"),
                       GetSQLValueString($_POST['title_article'], "text"),
                       GetSQLValueString($_POST['description_article'], "text"),
                       GetSQLValueString($_POST['text_article'], "text"),
                       GetSQLValueString($_POST['date_article'], "date"));

  mysql_select_db($database_BlogConnection, $BlogConnection);
  $Result1 = mysql_query($insertSQL, $BlogConnection) or die(mysql_error());

  $insertGoTo = "test/index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>EdgyDad's Blog Admin Template</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--
	<link rel="stylesheet" href="../BbigBbrands/css/reset.css" />
	<link rel="stylesheet" href="../BbigBbrands/css/default.css" />
-->    
    <link href="/test/css/BigBrandsDefault.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="/test/js/jquery-1.3.1.min.js"></script>
	<script type="text/javascript" src="/test/js/functions.js"></script>

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

<style type="text/css">

</style>

</head>

<body id="page">


<div id="wrapper"> 	

<!--[START] .col1 -->
<div class="col1"> 

	<a href="/index.php" class="logo"><span>EdgyDad's Blog</span><img src="/images/EdgyDadLogo.png" width="100%" height="132" alt="EdgyDad's Logo" /></a>

<div class="dividerDark tall"></div>

<!--[START] image rotator in sidebar -->
<div id="slideshow"> 


<a href="#" class="adSpotLarge"><img src="../images/ad1_whittier_sign.jpg" alt="Lot for Sale Design Build 1718 Whittier East Dallas White Rock Lake Texas" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="../images/USCycling_Coach.jpg" alt="USA Cycling Certified Coach" width="100%" /></a>	
<a href="#" class="adSpotLarge active"><img src="/images/IM_CDA_2007.jpg" alt="Ironman CDA 2007" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="/images/IM_CDA_2010.jpg" alt="Ironman CDA 2010" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="/images/IM_Wisconsin.JPG" alt="Ironman Ironman Wisconsin" width="100%" align="middle" /></a>		
<a href="#" class="adSpotLarge active"><img src="../images/EDREA_Logo 1.jpg" alt="Biff's Real Estate Company Logo" width="100%" /></a>
<a href="#" class="adSpotLarge active"><img src="/images/live_local_3.JPG" alt="Live Local East Dallas" width="100%" align="absmiddle"/></a>
<a href="#" class="adSpotLarge active"><img src="../images/Biff_in_Barcelona.png" alt="Picture of Biff in Barcelona" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="/images/Building_Creds.jpg" alt="ad2" width="100%" /></a>
	
</div>  
<!--[END] image rotator in sidebar -->
<p> 
<!-- class="subtext" -->
Some photos...</p>
<!--		<div class="dividerDark tall"></div>	-->
		<div class="dividerDark"></div>
		<h3>Who is EdgyDad?</h3>		
		<p>Husband, father, amateur triathlete and inline skater, USAC level 3 Cycling coach, Graduate Civil Engineer, commercial and residential Realtor, homebuilder, LEED Green Associate, NAHB Green Professional, NAHB Graduate Builder, Universal Design and Accessability student, Advanced Historic Home Specialist..........</p>
		
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
            <td><a href="/topics.php?id_topic=<?php echo $row_rsTopics['id_topic']; ?>"><?php echo $row_rsTopics['title_topic']; ?></a></td>
          </tr>
          <?php } while ($row_rsTopics = mysql_fetch_assoc($rsTopics)); ?>
      </table>
      <table width="100" border="0">
        <tr>
          <td><h4>Questions, Comments?</h4></td>
        </tr>
        <tr>
          <td><a href="/contact_form.php">
            <h4>Contact EdgyDad</h4>
          </a></td>
        </tr>
        <tr>
          <td><a href="/admin/index.php">
            <h4 class="light_rose_text" color=#000333>Admin Pages</h4>
          </a></td>
        </tr>
      </table> 

<table width="100" border="0" cellspacing="0" cellpadding="0" summary="Links to site administrative pages">
  <caption>
    Adminisitrative Menu
    </caption>
  <tr>
    <td><a href="/admin/list_topics.php">Manage Topics</a></td>
    </tr>
  <tr>
    <td><a href="/admin/list_articles.php">Manage Articles</a></td>
    </tr>
   <tr>
    <td height="49"><a href="/admin/admin_manage_comments.php">Manage Question, Comments</a></td>
    </tr>  
   <tr>
     <td><a href="/admin/manage_blog_comments.php">Manage Blog Comments</a></td>
   </tr>
   <tr>
     <td><a href="/admin/add_topic.php">Add Topic</a></td>
   </tr>
   <tr>
    <td><a href="/admin/index.php">Add Article</a></td>
    </tr>
  <tr>
    <td><a href="/admin/post_coffee.php">Add Coffee</a></td>
  </tr>
  <tr>
    <td><a href="/admin/post_roast.php">Add Roast</a></td>
  </tr>
  <tr>
    <td><a href="/admin/coffee_log.php">Manage Coffee Log</a></td>
  </tr>
  <tr>
    <td><a href="/admin/roast_log.php">Manage Roast Log</a></td>
  </tr>
  <tr>
    <td><a href="<?php echo $logoutAction ?>">Logout</a></td>
    </tr>
</table>
<!-- INSERT SPECIAL MENU ITEMS HERE IN 100 PX TABLE BORDER 0-->
</div>
<!--[END] .col2 -->
	
<!--[START] .col3 -->
<div class="col3">


<h3> Search public MLS with my complements! <a href="http://www.edgydad.com/contact_form.php">Contact me</a> to see any property, sell your property, get expert advice or any other question. I can help you with any real estate or home building need.</h3>
<p><iframe src="http://www.ntreisinnovia.net/cgi-ntr/BR_login?0582669" width=200% height="600" scrolling="auto">
  
  
</iframe>&nbsp;</p>
    
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
</html>

<?php
mysql_free_result($rsTopics);

mysql_free_result($rsTopic_to_Update);

mysql_free_result($rsArticles);

mysql_free_result($rsArticle_to_Update);
?>
