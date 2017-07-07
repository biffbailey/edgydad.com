<?php require_once('../../Connections/BlogConnection.php'); ?>
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

$maxRows_rsArticles = 10;
$pageNum_rsArticles = 0;
if (isset($_GET['pageNum_rsArticles'])) {
  $pageNum_rsArticles = $_GET['pageNum_rsArticles'];
}
$startRow_rsArticles = $pageNum_rsArticles * $maxRows_rsArticles;

mysql_select_db($database_BlogConnection, $BlogConnection);
$query_rsArticles = "SELECT * FROM blog_articles INNER JOIN blog_topics ON id_topic_article=id_topic ORDER BY date_article DESC";
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>
<!-- TemplateBeginEditable name="doctitle" -->
<title>EdgyDad's Blog</title>
<!-- TemplateEndEditable -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--
	<link rel="stylesheet" href="../BbigBbrands/css/reset.css" />
	<link rel="stylesheet" href="../BbigBbrands/css/default.css" />
-->    
    
	<script type="text/javascript" src="file:///C|/xampp/htdocs/BiffBaileyDotCom/BbigBbrands/js/jquery-1.3.1.min.js"></script>
	<script type="text/javascript" src="file:///C|/xampp/htdocs/BiffBaileyDotCom/BbigBbrands/js/functions.js"></script>

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
<link href="../css/BigBrandsDefault.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
a:hover {
	color: #eeb702;
}
-->
</style>
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>
<body id="page">
<div id="wrapper"> 	
	<!--[START] .col1 -->
  <div class="col1"> 
		<a href="../index.php" class="logo"><span>EdgyDad's Blog</span><img src="../../images/EdgyDadLogo.png" width="100%" height="132" alt="EdgyDad's Logo" /></a>
		<div class="dividerDark tall"></div>
		<!--[START] image rotator in sidebar -->
		<div id="slideshow">				
			<a href="#" class="adSpotLarge"><img src="../../images/ad1_whittier_sign.jpg" alt="ad 1 Whittier Sign" width="100%" /></a>
             <a href="#" class="adSpotLarge"><img src="../../images/Blank.png" alt="ad2" width="100%" /></a>			
			<a href="#" class="adSpotLarge"><img src="../../images/USCycling_Coach.jpg" alt="ad2" width="100%" /></a>	
            <a href="#" class="adSpotLarge"><img src="../../images/Blank.png" alt="ad2" width="100%" /></a>		
			<a href="#" class="adSpotLarge active"><img src="../../images/IM_CDA_banner.jpg" alt="ad3" width="100%" /></a>
             <a href="#" class="adSpotLarge"><img src="../../images/Blank.png" alt="ad2" width="100%" /></a>	
            <a href="#" class="adSpotLarge active"><img src="../../images/EDREA_Logo 1.jpg" alt="ad4" width="100%" /></a>
             <a href="#" class="adSpotLarge"><img src="../../images/Blank.png" alt="ad2" width="100%" /></a>	
            <a href="#" class="adSpotLarge active"><img src="../../images/Biff_in_Barcelona.jpg" alt="ad5" width="100%" /></a>
             <a href="#" class="adSpotLarge"><img src="../../images/Blank.png" alt="ad2" width="100%" /></a>	
            <a href="#" class="adSpotLarge active"><img src="../../images/LEED_GA_Color.jpg" alt="ad6" /></a>
             <a href="#" class="adSpotLarge"><img src="../../images/Blank.png" alt="ad2" width="100%" /></a>	
            <a href="#" class="adSpotLarge active"><img src="../../images/CGP_Better.jpg" alt="ad7" /></a>
             <a href="#" class="adSpotLarge"><img src="../../images/Blank.png" alt="ad2" width="100%" /></a>	
			<a href="#" class="adSpotLarge"><img src="../../images/CGB_white_bkgrnd.png" alt="ad8" /></a>
             <a href="#" class="adSpotLarge"><img src="../../images/Blank.png" alt="ad2" width="100%" /></a>	
		</div>  
        <!--[END] image rotator in sidebar -->
		<p class="subtext">Some photos...</p>
		<div class="dividerDark tall"></div>	
		<div class="dividerDark"></div>
		<h3>Who is EdgyDad?</h3>		
		<p>Husband, father, amateur triathlete and inline skater, USAC level 3 Cycling coach, Graduate Civil Engineer, commercial and residential Realtor, homebuilder, LEED Green Associate, NAHB Green Professional, NAHB Graduate Builder, Universal Design and Accessability student, Advanced Historic Home Specialist..........</p>
        

		
  </div>

	
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
    </div>
		
	<!--[END] .col2 -->
	<!--[START] .col3 -->
    <div class="col3">
      <div class="content">
        <h2 class="tagLine">Home.</h2>
      </div>
      <p>Welcome to EdgyDad's Blog, hosted at biffbailey.com. Here is a list of recent articles. Choose a topic from the list in the column to the left to navigate to a list of articles related to that topic.</p>
      <table width="650" border="0" cellpadding="0" cellspacing="0">
        <?php do { ?>
        <tr>
          <td width="382"><a href="../topics.php?id_topic=<?php echo $row_rsArticles['id_topic']; ?>"><?php echo $row_rsArticles['title_topic']; ?></a> &gt; <a href="../article.php?id_article=<?php echo $row_rsArticles['id_article']; ?>"><?php echo $row_rsArticles['title_article']; ?></a></td>
          <td width="268" colspan="2">Created: <?php echo $row_rsArticles['date_article']; ?></td>
        </tr>
        <tr>
          <td colspan="3"><p><?php echo $row_rsArticles['description_article']; ?></p>
            <hr /></td>
        </tr>
        <?php } while ($row_rsArticles = mysql_fetch_assoc($rsArticles)); ?>
      </table>
      <p class="padBottom"></p>
      <!--[START] #footer -->
      <div id="footer">
        <div id="copyright"> &copy; Copyright 2010, 2011 Biff Bailey, EdgyDad.com and Bbig Builders Incorporated. EdgyDad and EdgyDad's are trade names created by Bbig Builders Incorporated in January, 2008. Conforms to W3C Standard <a href="http://validator.w3.org/check?uri=referer" title="Validate XHTML">XHTML</a> &amp; <a title="Validate CSS" href="http://jigsaw.w3.org/css-validator/check/referer"> CSS</a></div>
        <!--[END] #copyright -->
      </div>
      <!--[END] #footer -->
</div>
<!--[END] .col3 -->
</div><!--[END] #wrapper -->

<!-- The code below makes the slideshow work -->

<script type="text/javascript" src="file:///C|/xampp/htdocs/BiffBaileyDotCom/BbigBbrands/js/rotator.js"></script>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6006465-2");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>
<?php
mysql_free_result($rsTopics);

mysql_free_result($rsTopicList);

mysql_free_result($rsArticles);
?>
