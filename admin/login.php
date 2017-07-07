<?php require_once('../Connections/BlogConnection.php'); ?>
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

  $insertGoTo = "../index.php";
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "level_user";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_BlogConnection, $BlogConnection);
  	
  $LoginRS__query=sprintf("SELECT username_user, password_user, level_user FROM blog_users WHERE username_user=%s AND password_user=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $BlogConnection) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'level_user');
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/edgydad_template.dwt.php" codeOutsideHTMLIsLocked="false" -->  
<head>
<!-- InstanceBeginEditable name="doctitle" -->

<title>EdgyDad's Blog Login</title>

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
<link href="../css/BigBrandsDefault.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
a:hover {
	color: #eeb702;
}
-->
</style>
<!-- InstanceBeginEditable name="head" -->

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

a:hover { color: #eeb702; }

</style>

<!-- TemplateBeginEditable name="head" -->

<!-- TemplateEndEditable -->

<script type="text/javascript">
<!--
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
//-->
</script>

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
	<!-- [END] .col1 -->
	<!--[START] .col2 -->
	<div class="col2">
      <table width="100%" >
        <tr>
          <td><h3>Blog Topics</h3></td>
        </tr>
        <?php do { ?>
          <tr>
            <td><a href="../topics.php?id_topic=<?php echo $row_rsTopics['id_topic']; ?>"><?php echo $row_rsTopics['title_topic']; ?></a></td>
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
          <td><a href="index.php">
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
        <h2 class="tagLine">Login Required</h2>
      </div>
      <p></p>
      
<form id="validate" name="admin_login" method="POST" action="<?php echo $loginFormAction; ?>">
	<table width="30%" border="0" cellspacing="0" cellpadding="0">
  		<caption>
    Administrator Only
  		</caption>
 
  <tr>
    <td>&nbsp;</td><td>  Username
      <input type="text" name="username" id="username" value="" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
      Password      
        <input type="password" name="password" id="password" value="" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="login" type="submit" id="login" onclick="MM_validateForm('username','','R','password','','R');return document.MM_returnValue" value="Login" /></td>
  </tr>
</table></form>

      <p>&nbsp;</p>

<!--[END] .col3 -->

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

mysql_free_result($rsTopicList);

mysql_free_result($rsArticles);
?>
