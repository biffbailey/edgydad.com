<?php require_once('Connections/BlogConnection.php'); ?>
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
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/edgydad_template.dwt.php" codeOutsideHTMLIsLocked="false" -->  
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>EdgyDad's Blog</title>
<!-- InstanceEndEditable -->

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
<!-- InstanceBeginEditable name="head" -->
<!-- Insert Head Section Customization Here -->
<!-- InstanceEndEditable -->
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
<!-- InstanceBeginEditable name="col_1_slides" --> 
<a href="#" class="adSpotLarge"><img src="../images/ad1_whittier_sign.jpg" alt="Lot for Sale Design Build 1718 Whittier East Dallas White Rock Lake Texas" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="../images/USCycling_Coach.jpg" alt="USA Cycling Certified Coach" width="100%" /></a>	
<a href="#" class="adSpotLarge active"><img src="/images/IM_CDA_2007.jpg" alt="Ironman CDA 2007" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="/images/IM_CDA_2010.jpg" alt="Ironman CDA 2010" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="/images/IM_Wisconsin.JPG" alt="Ironman Ironman Wisconsin" width="100%" align="middle" /></a>		
<a href="#" class="adSpotLarge active"><img src="../images/EDREA_Logo 1.jpg" alt="Biff's Real Estate Company Logo" width="100%" /></a>
<a href="#" class="adSpotLarge active"><img src="/images/live_local_3.JPG" alt="Live Local East Dallas" width="100%" align="absmiddle"/></a>
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
          <?php } while ($row_rsTopics = mysql_fetch_assoc($rsTopics)); ?>
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
<!-- INSERT SPECIAL MENU ITEMS HERE IN 100 PX TABLE BORDER 0-->

<!-- InstanceEndEditable -->
</div>
		
	<!--[END] .col2 -->
	<!--[START] .col3 -->
    <div class="col3">
	<!-- InstanceBeginEditable name="edit_column_3_main_content" -->
    <div class="content">
        <h2 class="tagLine">Resources.</h2>
      </div>
      <table border="0" width="100%">
        <tbody>
          <tr>
            <td width="170"><a href="http://r20.rs6.net/tn.jsp?llr=xfvygggab&amp;et=1106559066802&amp;s=537&amp;e=001yx8CmC1N6UiluduELczDcI59M3X-B0Ny_Lb0vhw9OOHZXNigrsyvtVWUpwwfqebegazDUTGzvG9p3O6g8tdjtz4WCkoGXZVrPGHkI4IBP7zq7o1Owl8TPw=="><img src="/images/austincapitoldome.jpg" alt="Capital Rotunda" width="300" height="200" border="0" /></a></td>
            <td><p align="justify"><strong>Did you know the TREC website has a tool for consumers that allows the public to search for an inspector by City?<br />
              </strong>Every single Texas Real Estate Inspector is listed on the TREC website. Members of the public can search for inspectors by name or by city with this convenient tool. Go to the <a href="http://r20.rs6.net/tn.jsp?llr=xfvygggab&amp;et=1106559066802&amp;s=537&amp;e=001yx8CmC1N6UiluduELczDcI59M3X-B0Ny_Lb0vhw9OOHZXNigrsyvtVWUpwwfqebegazDUTGzvG9p3O6g8tdjtz4WCkoGXZVrPGHkI4IBP7zq7o1Owl8TPw==" target="_blank">TREC website</a> to test it out yourself.<br />
              <br />
              The TREC website has many great tools for inspectors and members of the public alike. Check out the website regularly for all the latest goings on at the Texas Real Estate Commission.</p></td>
          </tr>
          <tr>
            <td><a href="http://www.usgbc.org"><img src="/images/USGBC Logo.jpg" width="82" height="110" alt="usgbc logo" /></a></td>
            <td><strong>The U.S. Green Building Council (USGBC)</strong> is a Washington, D.C.-based 501(c)(3) nonprofit organization committed to a prosperous and sustainable future for our nation through cost-efficient and energy-saving green buildings. USGBC works toward its mission of market transformation through its LEED green building certification program, robust educational offerings, a nationwide network of chapters and affiliates, the annual Greenbuild International Conference &amp; Expo, and advocacy in support of public policy that encourages and enables green buildings and communities. </td>
          </tr>
          <tr>
            <td><a href="http://www.gbci.org"><img src="/images/LEED AP Logo.jpg" width="82" height="110" alt="leed ap logo" /><img src="/images/LEED_GA_Color.jpg" alt="leed ga logo" width="83" height="92" align="top" /></a></td>
            <td><span class="Apple-style-span" style="border-collapse: separate; color: rgb(0, 0, 0); font-family: 'Times New Roman'; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; -webkit-text-decorations-in-effect: none; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; font-size: medium; "><span class="Apple-style-span" style="color: rgb(51, 51, 51); font-family: 'Lucida Grande', Geneva, Arial, Verdana, sans-serif; font-size: 11px; line-height: 14px; text-align: left; "><strong>LEED Professional Credentials (LEED AP and Green Associate)</strong><span class="Apple-converted-space"> </span>recognize professionals who have demonstrated a thorough understanding of green building techniques, the LEED green building rating systems, and the certification process. The LEED Professional Credentials program is administerbed by the Green Building Certification Institute (GBCI), which was established with the support of USGBC to allow for objective, balanced management of the credential program.</span></span></td>
          </tr>
          <tr>
            <td><a href="http://www.leedforhomes.org/OST/homepage.aspx"><img src="/images/LEED for Homes Scoring Tool.jpg" width="219" height="216" alt="leed for homes scoring tool" /></a></td>
            <td><a href="http://www.centerforgreenschools.org/"><img src="/images/USGBC's Green Home Guide.gif" width="219" height="216" alt="leed's grean home guide" /></a></td>
          </tr>
          <tr>
            <td><img src="/images/imgres.jpg" width="180" height="176" alt="video camera image" /></td>
            <td><h3>How to shoot a full-motion home tour video:</h3></br>4. Step-by-step instruction:
Need additional help in shooting your own full motion home tour videos? Go to<br />
            www.realtor.com/freetraining to watch our FREE, “How To” video.</td>
          </tr>
          <tr>
            <td><a href="/documents/home_sellers_guide.pdf"><img src="/images/sellers_guide.JPG" width="300" /></a></td>
            <td><a href="/documents/home_buyers_guide.pdf"><img src="/images/buyers_guide.JPG" width="300" /></a></td>
          </tr>
          <tr>
            <td><img src="/images/Listing_Optimization_Plan.PNG" width="205" height="186" /></td>
            <td><h4>National Association of Realtors PPT getting the most out of lisitings and open houses online</h4></td>
          </tr>
          <tr>
            <td><a href="/documents/TARCreditReporting.ppsx"><img src="/images/Reject_Face.png" width="150" height="150" /></a></td>
            <td><h4>Texas Association of Realtors PPT on tenant application and credit reporting. TAR provides great forms, policies and procedures to it's professional members allowing us to offer a great value to you. <a href="/documents/TARCreditReporting.ppsx">See the PPT</a>.</h4></td>
          </tr>
        </tbody>
      </table>
<p>&nbsp;</p><p class="padBottom"></p>
    <!-- InstanceEndEditable -->
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
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsTopics);

mysql_free_result($rsArticles);
?>
