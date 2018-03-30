<?php 

require('Connections/BlogConnection.php'); 

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
?>
<?php
mysqli_select_db($BlogConnection, $database_BlogConnection); 
$query_rsTopics = "SELECT * FROM dbdata1.blog_topics ORDER BY title_topic DESC";
	echo " query_rsTopics :" ;
	var_dump($query_rsTopics);
	$rsTopics = mysqli_query($BlogConnection, $query_rsTopics)/* or die(mysql_error()) */;
	echo "rsTopics: ";
	var_dump($rsTopics);
$row_rsTopics = mysqli_fetch_assoc($rsTopics);
$totalRows_rsTopics = mysqli_num_rows($rsTopics);

mysqli_select_db($BlogConnection, $database_BlogConnection);
$query_rsTopicList = "SELECT * FROM blog_topics ORDER BY title_topic DESC";
$rsTopicList = mysqli_query($BlogConnection, $query_rsTopicList)/* or die(mysql_error()) */;
$row_rsTopicList = mysqli_fetch_assoc($rsTopicList);
$totalRows_rsTopicList = mysqli_num_rows($rsTopicList);

$colname_rsArticles = "-1";
if (isset($_GET['id_topic'])) {
  $colname_rsArticles = $_GET['id_topic'];
}
var_dump($colname_rsArticles);
mysqli_select_db($BlogConnection, $database_BlogConnection);
$query_rsArticles = sprintf(
		"SELECT * FROM blog_articles INNER JOIN blog_topics ON id_topic_article=id_topic WHERE id_topic_article = %s 
		OR id_topic_article_2=%s OR id_topic_article_3 = %s ORDER BY date_article DESC",
		GetSQLValueString($colname_rsArticles, "int",$BlogConnection),
		GetSQLValueString($colname_rsArticles, "int",$BlogConnection),
		GetSQLValueString($colname_rsArticles, "int",$BlogConnection)
		);
$rsArticles = mysqli_query($BlogConnection, $query_rsArticles)/* or die(mysql_error()) */;
$row_rsArticles = mysqli_fetch_assoc($rsArticles);
$totalRows_rsArticles = mysqli_num_rows($rsArticles);

$colname_rsTopicSelected = "-1";
if (isset($_GET['id_topic'])) {
  $colname_rsTopicSelected = $_GET['id_topic'];
}
mysqli_select_db($BlogConnection, $database_BlogConnection);
$query_rsTopicSelected = sprintf("select * from blog_topics where blog_topics.id_topic = %s", 
		GetSQLValueString($colname_rsTopicSelected, "int",$BlogConnection));
$rsTopicSelected = mysqli_query($BlogConnection, $query_rsTopicSelected)/* or die(mysql_error())*/;
$row_rsTopicSelected = mysqli_fetch_assoc($rsTopicSelected);
$totalRows_rsTopicSelected = mysqli_num_rows($rsTopicSelected);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  

<head>

<title>EdgyDad's Blog Topics</title>

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
<!--
a:hover {
	color: #eeb702;
}
-->
</style>

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
<!-- "col_1_slides" --> 
<a href="#" class="adSpotLarge"><img src="images/ad1_whittier_sign.jpg" alt="Lot for Sale Design Build 1718 Whittier East Dallas White Rock Lake Texas" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="/images/USCycling_Coach.jpg" alt="USA Cycling Certified Coach" width="100%" /></a>	
<a href="#" class="adSpotLarge active"><img src="images/IM_CDA_2007.jpg" alt="Ironman CDA 2007" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="images/IM_CDA_2010.jpg" alt="Ironman CDA 2010" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="images/IM_Wisconsin.JPG" alt="Ironman Ironman Wisconsin" width="100%" align="middle" /></a>		
<a href="#" class="adSpotLarge active"><img src="images/EDREA_Logo 1.jpg" alt="Biff's Real Estate Company Logo" width="100%" /></a>
<a href="#" class="adSpotLarge active"><img src="images/live_local_3.JPG" alt="Live Local East Dallas" width="100%" align="absmiddle"/></a>
<a href="#" class="adSpotLarge active"><img src="images/Biff_in_Barcelona.png" alt="Picture of Biff in Barcelona" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="images/Building_Creds.jpg" alt="ad2" width="100%" /></a>

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
          <td><h4>
          <a href="/real_estate_home.php">
          Biff Bailey's Real Estate Home Page
          </a></h4></td>
        </tr>
       
        <tr>
          <td><h4>
          <a href="contact_form.php">
            Contact EdgyDad
          </a></h4></td>
        </tr>
        <tr>
          <td><h4>
          <a href="bio.php">
            About EdgyDad
          </a></h4></td>
        </tr><tr>
          <td><h4>
          <a href="/resources.php">
          Resource Links Page
          </a></h4></td>
        </tr>
        <tr>
          <td><h4 class="light_rose_text" color=#000333>
          <a href="admin/index.php">
           Admin Pages
          </a></h4></td>
        </tr>
      </table>
<!-- Google Reader Shared Items script starts here--> 
      <script type="text/javascript" src="https://www.google.com/reader/ui/publisher-en.js"></script>
<script type="text/javascript" src="https://www.google.com/reader/public/javascript/user/07633767008004657036/state/com.google/broadcast?n=5&callback=GRC_p(%7Bc%3A%22blue%22%2Ct%3A%22Biff's%20shared%20items%22%2Cs%3A%22false%22%2Cn%3A%22true%22%2Cb%3A%22false%22%7D)%3Bnew%20GRC"></script>
<!--Googe Reader Shared Items end-->

</div>
		
	<!--[END] .col2 -->
	<!--[START] .col3 -->
    <div class="col3">
	<!-- InstanceBeginEditable name="edit_column_3_main_content" -->
        <h2 class="tagLine">Articles tagged &quot;<?php echo $row_rsTopicSelected['title_topic']; ?>&quot;</h2>
      <table width="98%" border="0" cellpadding="0" cellspacing="0">
        <?php do { ?>
        <tr>
          <td><a href="article.php?id_article=<?php echo $row_rsArticles['id_article']; ?>"><?php echo $row_rsArticles['title_article']; ?></a></td>
          <td colspan="2">Created: <?php echo $row_rsArticles['date_article']; ?></td>
        </tr>
        <tr>
          <td colspan="3"><?php echo $row_rsArticles['description_article']; ?>
            <hr />
          </td>
        </tr>
        
        <?php } while ($row_rsArticles = mysqli_fetch_assoc($rsArticles)); ?>
    </table>

      <!--[START] #footer -->
      <div id="footer">
        <div id="copyright"> &copy; Copyright 2010, 2011 Biff Bailey, EdgyDad.com , East Dallas Real Estate Advisors and Bbig Builders Incorporated. EdgyDad and EdgyDad's are trade names created by Bbig Builders Incorporated in January, 2008. Conforms to W3C Standard <a href="http://validator.w3.org/check?uri=referer" title="Validate XHTML">XHTML</a> &amp; <a title="Validate CSS" href="http://jigsaw.w3.org/css-validator/check/referer"> CSS</a></div>
        <!--[END] #copyright -->
      </div>
      <!--[END] #footer -->
</div>
<!--[END] .col3 -->
</div><!--[END] #wrapper -->

<!-- The code below makes the slideshow work -->

<script type="text/javascript" src="/js/rotator.js"></script>

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
