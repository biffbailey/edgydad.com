<?php
    header("Content-Type: application/rss+xml; charset=ISO-8859-1");
?>
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
$query_rs_rss_articles_topics = "SELECT * FROM blog_articles INNER JOIN blog_topics ON id_topic_article=id_topic ORDER BY date_article DESC";
$rs_rss_articles_topics = mysql_query($query_rs_rss_articles_topics, $BlogConnection) or die(mysql_error());
$row_rs_rss_articles_topics = mysql_fetch_assoc($rs_rss_articles_topics);
$totalRows_rs_rss_articles_topics = mysql_num_rows($rs_rss_articles_topics);
?>




<rss version="2.0">
<!-- <!DOCTYPE rss PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> -->



<channel>
      <title>
      EdgyDad's Blog
      </title>
      <link>
      http://www.edgydad.com/
      </link>
      <description>EdgyDad blogs about endurance sport, ironman triathlon, training and nutrition, deep ellum, expo park and east dallas real estate, housing, home design and home building, ethics, politics and big business -- whatever may be making him Edgy.....</description>
      <language>en-us</language>
      <pubDate><?php echo date("D, d M Y H:i:s T")?></pubDate>
      <lastBuildDate><?php echo date("D, d M Y H:i:s T")?></lastBuildDate>
      <docs>http://www.rssboard.org/rss-specification</docs>
      <managingEditor>admin@biffbailey.com(EdgyDad)</managingEditor>
      <webMaster>admin@biffbailey.com(EdgyDad)</webMaster>
      <copyright>Copyright 2010, 2011 Biff Bailey and Bbig Builders Incorporated</copyright>
   
     
     <?php do { ?>
<item><title><?php echo $row_rs_rss_articles_topics['title_article']; ?></title><link>http://www.edgydad.com/article.php?id_article=<?php echo $row_rs_rss_articles_topics['id_article'];?></link><pubDate><?php $date=$row_rs_rss_articles_topics['date_article'];echo date("D, d M Y H:i:s T",strtotime($date));?></pubDate><description><?php echo $row_rs_rss_articles_topics['description_article']; ?></description></item>
     <?php } while ($row_rs_rss_articles_topics = mysql_fetch_assoc($rs_rss_articles_topics)); ?>
     
</channel>   
</rss>
    <?php mysql_free_result($rs_rss_articles_topics);?>
      