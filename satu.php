<?
include_once("konfigurasi.php");
include_once("peta.php");
$link = dbconnect();

$q="SELECT * FROM store_info_tab ORDER BY ros_ic" ;
if($r=mysql_query($q))
{
	$scdata = mysql_fetch_array($r);
	$sc = $scdata['ros_ic'];
	if($sc > 0)
	{
		header("Location: sms.php?ros_ic=$sc");
	}
	else
	{
		header("Location: index.php?a=signup");
	}
}
else
{
	echo mysql_error();
	exit;
}
dbclose($link);
?>