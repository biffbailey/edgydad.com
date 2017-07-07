<?php require_once('Connections/BlogConnection.php'); ?>
<?php require_once('Connections/floor_plan_db.php'); ?>
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

$colname_rsTopic_to_Update = "-1";
if (isset($_Get['id_topic'])) {
  $colname_rsTopic_to_Update = $_Get['id_topic'];
}
mysql_select_db($database_BlogConnection, $BlogConnection);
$query_rsTopic_to_Update = sprintf("SELECT * FROM blog_topics WHERE blog_topics.id_topic=%s ORDER BY title_topic DESC", GetSQLValueString($colname_rsTopic_to_Update, "int"));
$rsTopic_to_Update = mysql_query($query_rsTopic_to_Update, $BlogConnection) or die(mysql_error());
$row_rsTopic_to_Update = mysql_fetch_assoc($rsTopic_to_Update);
$totalRows_rsTopic_to_Update = mysql_num_rows($rsTopic_to_Update);

$colname_rsArticle_to_Update = "-1";
if (isset($_GET['id_article'])) {
  $colname_rsArticle_to_Update = $_GET['id_article'];
}
mysql_select_db($database_BlogConnection, $BlogConnection);
$query_rsArticle_to_Update = sprintf("SELECT * FROM blog_articles INNER JOIN blog_topics ON id_topic_article=id_topic WHERE id_article = %s", GetSQLValueString($colname_rsArticle_to_Update, "int"));
$rsArticle_to_Update = mysql_query($query_rsArticle_to_Update, $BlogConnection) or die(mysql_error());
$row_rsArticle_to_Update = mysql_fetch_assoc($rsArticle_to_Update);
$totalRows_rsArticle_to_Update = mysql_num_rows($rsArticle_to_Update);

mysql_select_db($database_floor_plan_db, $floor_plan_db);
$query_rsSumSalePrice = "SELECT count(contract_num) AS NumContracts,  format(sum( sale_price ),0) AS TotalSalePrice, format(sum( acc_price ),0) AS TotalAccessories, format(sum( clerical_fee ),0) AS TotalClericalFee, format(sum( t1_amt ),0) AS TotalTradeIn_1, format(sum( t2_amt ),0) AS TotalTradeIn_2, format(sum( t1_acv ),0) AS TotalTrade_1_ACV, format(sum( t2_acv ),0) AS TotalTrade_2_ACV,  format( sum( s_vh_cost ),0) AS TotalRVHCost, format(sum( expenses ),0) AS TotalExpenses, format(sum( pack_fee ),0) AS TotalPackFee, format(sum( warranty_prem ),0) AS TotalWarrantyPrem, format(sum( warranty_cost ),0) AS TotalWarrantyCost, format( sum( comm1+comm2+comm3+comm4 ),0) AS TotalComm,   format(sum( sale_price+acc_price-clerical_fee-t1_amt-t2_amt+t1_acv+t2_acv-s_vh_cost-expenses-pack_fee ),0) AS GrossProfit, format(sum(sale_price+acc_price-clerical_fee-t1_amt-t2_amt+t1_acv+t2_acv-s_vh_cost-expenses-pack_fee) / sum(sale_price+acc_price)*100, 1) AS GrossProfitPct, format(sum(sale_price+acc_price-clerical_fee-t1_amt-t2_amt+t1_acv+t2_acv-s_vh_cost-expenses-pack_fee-comm1-comm2-comm3-comm4),0)AS NetProfit, format(sum(sale_price+acc_price+warranty_prem-warranty_cost-clerical_fee-t1_amt-t2_amt+t1_acv+t2_acv-s_vh_cost-expenses-pack_fee-comm1-comm2-comm3-comm4) / sum(sale_price+acc_price)*100, 1) AS NetProfitPct, format(avg(datediff(S_vh_sale_date,  S_vh_stock_date)), 1)  AS AvgDays FROM Contracts ";
$rsSumSalePrice = mysql_query($query_rsSumSalePrice, $floor_plan_db) or die(mysql_error());
$row_rsSumSalePrice = mysql_fetch_assoc($rsSumSalePrice);
$totalRows_rsSumSalePrice = mysql_num_rows($rsSumSalePrice);

mysql_select_db($database_floor_plan_db, $floor_plan_db);
$query_rsContract_Data = "SELECT contract_num,  s_vh_stock, t1_vh_stock, t2_vh_stock, s_vh_stock_date,s_vh_sale_date, format( sale_price ,0) AS SalePrice, format( acc_price,0) AS Accessories, format( clerical_fee,0) AS ClericalFee, format( t1_amt ,0) AS TradeIn_1, format( t2_amt ,0) AS TradeIn_2, format( t1_acv ,0) AS Trade_1_ACV, format( t2_acv ,0) AS Trade_2_ACV,  format(  s_vh_cost ,0) AS RVHCost, format( expenses ,0) AS Expenses, format( pack_fee ,0) AS PackFee, format( warranty_prem ,0) AS Warranty, format( warranty_cost ,0) AS WarrantyCost, format(  comm1+comm2+comm3+comm4 ,0) AS Comm,   format( sale_price+acc_price-clerical_fee-t1_amt-t2_amt+t1_acv+t2_acv-s_vh_cost-expenses-pack_fee ,0) AS GrossProfit, format((sale_price+acc_price-clerical_fee-t1_amt-t2_amt+t1_acv+t2_acv-s_vh_cost-expenses-pack_fee) / (sale_price+acc_price)*100, 1) AS GrossProfitPct, format((sale_price+acc_price-clerical_fee-t1_amt-t2_amt+t1_acv+t2_acv-s_vh_cost-expenses-pack_fee-comm1-comm2-comm3-comm4),0)AS NetProfit, format((sale_price+acc_price+warranty_prem-warranty_cost-clerical_fee-t1_amt-t2_amt+t1_acv+t2_acv-s_vh_cost-expenses-pack_fee-comm1-comm2-comm3-comm4) / (sale_price+acc_price)*100, 1) AS NetProfitPct, format(datediff(S_vh_sale_date,  S_vh_stock_date), 1)  AS NumDays FROM Contracts  ORDER BY Contracts.s_vh_sale_date";
$rsContract_Data = mysql_query($query_rsContract_Data, $floor_plan_db) or die(mysql_error());
$row_rsContract_Data = mysql_fetch_assoc($rsContract_Data);
$totalRows_rsContract_Data = mysql_num_rows($rsContract_Data);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/edgydad_admin_template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<!-- InstanceBeginEditable name="doctitle" -->

<title>EdgyDad RV Contracts Data</title>

<!-- InstanceEndEditable -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--
	<link rel="stylesheet" href="../BbigBbrands/css/reset.css" />
	<link rel="stylesheet" href="../BbigBbrands/css/default.css" />
-->    
<link href="css/BigBrandsDefault.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="test/js/jquery-1.3.1.min.js"></script>
<script type="text/javascript" src="test/js/functions.js"></script>

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
		<a href="index.php"><img src="images/EdgyDadLogo.png" width="100%"  alt="EdgyDad's Logo" /></a>
		<!--[START] image rotator in sidebar -->
		<div id="slideshow">
<!-- InstanceBeginEditable name="col_1_slides" --> 
<a href="#" class="adSpotLarge"><img src="/images/ad1_whittier_sign.jpg" alt="Lot for Sale Design Build 1718 Whittier East Dallas White Rock Lake Texas" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="/images/USCycling_Coach.jpg" alt="USA Cycling Certified Coach" width="100%" /></a>	
<a href="#" class="adSpotLarge active"><img src="/images/IM_CDA_2007.jpg" alt="Ironman CDA 2007" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="/images/IM_CDA_2010.jpg" alt="Ironman CDA 2010" width="100%" /></a>
<a href="#" class="adSpotLarge"><img src="/images/IM_Wisconsin.JPG" alt="Ironman Ironman Wisconsin" width="100%" /></a>		
<a href="#" class="adSpotLarge active"><img src="/images/EDREA_Logo 1.jpg" alt="Biff's Real Estate Company Logo" width="100%" /></a>
<a href="#" class="adSpotLarge active"><img src="/images/live_local_3.JPG" alt="Live Local East Dallas" width="100%" valign="center"/></a>
<a href="#" class="adSpotLarge active"><img src="/images/Biff_in_Barcelona.png" alt="Picture of Biff in Barcelona" width="100%" /></a>
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
            <td><a href="topics.php?id_topic=<?php echo $row_rsTopics['id_topic']; ?>"><?php echo $row_rsTopics['title_topic']; ?></a></td>
          </tr>
          <?php } while ($row_rsTopics = mysql_fetch_assoc($rsTopics)); ?>
    </table>
      <table width="100" border="0">
        <tr>
          <td><h4>Questions, Comments?</h4></td>
        </tr>
        <tr>
          <td><a href="contact_form.php">
            <h4>Contact EdgyDad</h4>
          </a></td>
        </tr>
        <tr>
          <td><a href="admin/index.php">
            <h4 class="light_rose_text" color=#000333>Admin Pages</h4>
          </a></td>
        </tr>
      </table> 

<table width="100" border="0" cellspacing="0" cellpadding="0" summary="Links to site administrative pages">
  <caption>
    Adminisitrative Menu
    </caption>
  <tr>
    <td><a href="admin/list_topics.php">Manage Topics</a></td>
    </tr>
  <tr>
    <td><a href="admin/list_articles.php">Manage Articles</a></td>
    </tr>
   <tr>
    <td height="49"><a href="admin/admin_manage_comments.php">Manage Question, Comments</a></td>
    </tr>  
   <tr>
     <td><a href="admin/manage_blog_comments.php">Manage Blog Comments</a></td>
   </tr>
   <tr>
     <td><a href="admin/add_topic.php">Add Topic</a></td>
   </tr>
   <tr>
    <td><a href="admin/index.php">Add Article</a></td>
    </tr>
    <tr>
    <td><a href="admin/post_contract.php">Add Contract</a></td>
    </tr>
  <tr>
    <td><a href="admin/post_coffee.php">Add Coffee</a></td>
  </tr>
  <tr>
    <td><a href="admin/post_roast.php">Add Roast</a></td>
  </tr>
  <tr>
    <td><a href="admin/coffee_log.php">Manage Coffee Log</a></td>
  </tr>
  <tr>
    <td><a href="admin/roast_log.php">Manage Roast Log</a></td>
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
<div>
  <table align="left" class="clear">
<tr align="right">
<td><h4>Summary Stats so Far</h4></td>
</tr>
<tr>
<td align="right"><?php echo $row_rsSumSalePrice['TotalSalePrice']; ?></td>
<td>Sales Price</td>
</tr>
<tr>
  <td align="right"><?php echo $row_rsSumSalePrice['TotalAccessories']; ?></td>
  <td>Accessories</td>
</tr>
<tr>
  <td align="right">(<?php echo $row_rsSumSalePrice['TotalClericalFee']; ?>)</td>
  <td>Clerical Fee</td>
</tr>
<tr>
  <td align="right">(<?php echo $row_rsSumSalePrice['TotalTradeIn_1']; ?>)</td>
  <td>Credit Trade 1</td>
</tr>
<tr>
  <td align="right">(<?php echo $row_rsSumSalePrice['TotalTradeIn_2']; ?>)</td>
  <td>Credit Trade 2</td>
</tr>
<tr>
  <td align="right"><?php echo $row_rsSumSalePrice['TotalTrade_1_ACV']; ?></td>
  <td>ACV Trade 1</td>
</tr>
<tr>
  <td align="right"><?php echo $row_rsSumSalePrice['TotalTrade_2_ACV']; ?></td>
  <td>ACV Trade 2</td>
</tr>
<tr>
<td align="right">(<?php echo $row_rsSumSalePrice['TotalRVHCost']; ?>)</td>
<td>RV Cost</td>
</tr>
<tr>
  <td align="right">(<?php echo $row_rsSumSalePrice['TotalExpenses']; ?>)</td>
  <td>Expenses</td>
</tr>
<tr >
  <td align="right">(<?php echo $row_rsSumSalePrice['TotalPackFee']; ?>)</td>
  <td>Pack Fee</td>
</tr>
<tr>
  <td align="right">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
<td align="right"><?php echo $row_rsSumSalePrice['GrossProfit']; ?></td>
<td>Gross Profit</td>
</tr>
<tr>
  <td align="right"><?php echo $row_rsSumSalePrice['GrossProfitPct']; ?></td>
  <td>Gross Profit Pct</td>
</tr>
<tr>
  <td align="right">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td align="right"><?php echo $row_rsSumSalePrice['TotalWarrantyPrem']; ?></td>
  <td>Warranty Premium</td>
</tr>
<tr>
  <td align="right">(<?php echo $row_rsSumSalePrice['TotalWarrantyCost']; ?>)</td>
  <td>Warranty Cost</td>
</tr>
<tr>
  <td align="right">(<?php echo $row_rsSumSalePrice['TotalComm']; ?>)</td>
  <td>Commissions</td>
</tr>
<tr>
  <td align="right">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td align="right"><?php echo $row_rsSumSalePrice['NetProfit']; ?></td>
  <td>Net Profit</td>
</tr>
<tr>
  <td align="right"><?php echo $row_rsSumSalePrice['NetProfitPct']; ?></td>
  <td>Net Profit Pct</td>
</tr>
<tr>
  <td align="right">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td align="right"><?php echo $row_rsSumSalePrice['AvgDays']; ?></td>
  <td>Avg Inventory Days</td>
</tr>
<tr>
  <td align="right"><?php echo $row_rsSumSalePrice['NumContracts']; ?></td>
  <td># Contracts</td>
</tr>
</table>
<br />
<h4 class="clear">Contract Details:</h4>
    <?php do { ?>
      <table border="0" cellpadding="1" cellspacing="1" class="clear">
    <tr class="tlabel">
      <td>contract_num</td>
      <td>s_vh_stock</td>
      <td>t1_vh_stock</td>
      <td>t2_vh_stock</td>
      <td>Expenses</td>
      <td>ClericalFee</td>
    </tr>
    <tr>
        <td><?php echo $row_rsContract_Data['contract_num']; ?></td>
        <td><?php echo $row_rsContract_Data['s_vh_stock']; ?></td>
        <td><?php echo $row_rsContract_Data['t1_vh_stock']; ?></td>
        <td><?php echo $row_rsContract_Data['t2_vh_stock']; ?></td>
        <td><?php echo $row_rsContract_Data['Expenses']; ?></td>
        <td><?php echo $row_rsContract_Data['ClericalFee']; ?></td>
      </tr>
      <tr class="tlabel">
        <td>s_vh_stock_date</td>
        <td>SalePrice</td>
        <td>TradeIn_1</td>
        <td>TradeIn_2</td>
        <td>PackFee</td>
        <td>Accessories</td>
      </tr>
      <tr>
        <td><?php echo $row_rsContract_Data['s_vh_stock_date']; ?></td>
        <td><?php echo $row_rsContract_Data['SalePrice']; ?></td>
        <td><?php echo $row_rsContract_Data['TradeIn_1']; ?></td>
        <td><?php echo $row_rsContract_Data['TradeIn_2']; ?></td>
        <td><?php echo $row_rsContract_Data['PackFee']; ?></td>
        <td><?php echo $row_rsContract_Data['Accessories']; ?></td>
      </tr>
      <tr class="tlabel">
        <td>s_vh_sale_date</td>
        <td>RVHCost</td>
        <td>Trade_1_ACV</td>
        <td>Trade_2_ACV</td>
        <td>Comm</td>
        <td>Warranty</td>
      </tr>
      <tr>
        <td><?php echo $row_rsContract_Data['s_vh_sale_date']; ?></td>
        <td><?php echo $row_rsContract_Data['RVHCost']; ?></td>
        <td><?php echo $row_rsContract_Data['Trade_1_ACV']; ?></td>
        <td><?php echo $row_rsContract_Data['Trade_2_ACV']; ?></td>
        <td><?php echo $row_rsContract_Data['Comm']; ?></td>
        <td><?php echo $row_rsContract_Data['Warranty']; ?></td>
      </tr>
      <tr class="tlabel">
        <td>NumDays</td>
        <td>GrossProfit</td>
        <td>GrossProfitPct</td>
        <td>NetProfit</td>
        <td>NetProfitPct</td>
        <td>WarrantyCost</td>
      </tr>
      <tr>
        <td><?php echo $row_rsContract_Data['NumDays']; ?></td>
        <td><?php echo $row_rsContract_Data['GrossProfit']; ?></td>
        <td><?php echo $row_rsContract_Data['GrossProfitPct']; ?></td>
        <td><?php echo $row_rsContract_Data['NetProfit']; ?></td>
        <td><?php echo $row_rsContract_Data['NetProfitPct']; ?></td>
        <td><?php echo $row_rsContract_Data['WarrantyCost']; ?></td>
      </tr>
      <tr>
        <td colspan="6"><hr /></td>
      <?php } while ($row_rsContract_Data = mysql_fetch_assoc($rsContract_Data)); ?>
  </table>
</div>
    
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

mysql_free_result($rsArticle_to_Update);

mysql_free_result($rsSumSalePrice);

mysql_free_result($rsContract_Data);
?>
