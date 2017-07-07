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
        <h2 class="tagLine">EdgyDad is Biff Bailey.</h2>
      </div>
      <div>
        <p><a href="/clients_customers.php">Click here to see a list of my clients and customers</a>.<br /><br />
        <span>Biff Bailey grew up in Lyndhurst, Ohio, a middle class suburb of Cleveland (think </span>real wrestling, rock-n-rock and football).  The Cuyahoga River was occasionally flammable, the Flats was where people disappeared, Goulardi was on TV and the economy sucked.  Biff played baseball, ran some track, studied music, mowed lawns, caddied, worked as a garbage man and did other usual kid stuff.  After high school, Biff got a scolarship and moved to Dallas, Texas (think Texas football, sunbelt, cowgirls and oil boom) where he earned BS Civil Engineering and MBA at Southern Methodist University.  He made good grades, joined Phi Delta Gamma (FIJI), walked onto the football team, drank beer and did other usual college stuff. <br />
        <br />
        Following Dad’s advice, Biff went to work for a big corporation, beginning his career building subdivisions for Exxon’s Friendswood Development Company in Houston, Texas.  Mr. Bailey built several hundred lots, and a few major thoroughfares and drainage projects..  He moved into the finance area as an analyst underwriting land and commercial projects for several years.  Biff moved on to Sales where he sold and closed dozens of commercial tracts to major aerospace firms, developers, and small businesses.  In his last assignment,  Biff managed, cleaned up and disposed of multiple surplus properties across the U.S..  <br />
        <br />
        Done with corporate life, Biff split for Los Angeles.  Initially landing in Venice, Biff worked commercial deals while becoming a regular at Gold’s Gym and trying surfing (resulting in his first near-death experience.) Eventually Biff joined <a href="http://www.wellmanproperties.com/">Wes Wellman</a> and Paul DeSantis as their financial manager and partnership liaison in liberating Santa Monica apartments from rent control using the Tenant Ownership Rights Charter Amendment and state subdivision law.  </p>
        <p>Shortly before returning to Dallas, Biff rode out the Northridge earthquake in one of the partnership properties in north Santa Monica. Totally twilight zone. The building felt like a little row boat in big ocean with 10 foot swells. Unfortuneately, still tied to the dock -- when that building hit the &quot;end&quot; of it's shear strap and the shear wall &quot;exploded&quot; -- chaos!  Acting quickly the same day, Mr. Bailey designed and supervised temporary reparis to the the building's kneewall and corner colums, saving the building from condemnation and the attendant difficulties.<br />
          <br />
          Returning to Dallas, Biff joined <a href="http://www.davidweekleyhomes.com/">David Weekley Homes</a>.  Over the next 13 years, he worked his way up the ladder from a builder through purchasing to Land Acquisitions Manager, a role he pioneered for the company.  He introduced the company to wood design and application of engineered wood and became an expert on the technical, structural and dirt sides of the business as well as an excellent deal underwriter, matching right products, price points and locations.  Biff was instrumental in securing land positions enabling the company to grow from 600 to over 1200 annual closings.<br />
          <br />
          A licensed real estate broker with extensive transactional experience, Biff has waded through complex real estate transactions with a high success rate.  He has continually expanded his residential product knowledge and building expertise and is certified through NAHB as Graduate Builder CGB, Green Professional CGP and Aging-in-Place Specialist CAPS.  He is also GBCI certified LEED Green Assocate and has Advanced Historic Housing Specialist certification through Preservation Dallas.  Biff has honed his site planning and housing design skill through years of experience and hundreds of product walks.  In addition, Biff is understands the design process and CAD systems and has produced original site plans and site responsive product designs.<br />
          <br />
          Biff is married to Tanisha, RN, 3x Ironman, marathoner and ultra-runner and lives in the east Dallas community of Forest Hills with their two young children, a greyhound and a yellow labrador retriever.  An avid amateur endurance athlete, Bailey has competed in multiple inline skate races in the US and abroad before switching to triathlon, learning to swim and becoming 2x Ironman himself.  <br />
          <br />
          Biff loves the old edgy east Dallas’ neighborhoods, White Rock Lake and the Santa Fe Trail and is committed to apply his transactional and operational experience to  promote progressive, sustainable real estate investment locally -- where he lives and raises his family.<br />
        <a href="/clients_customers.php">Click here to see a list of my clients and customers</a>.</p>
      </div>

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
