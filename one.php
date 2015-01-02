<?
include_once("tsc_config.php");
include_once("tsc_funs.php");
$link = dbconnect();

$q="SELECT * FROM store_info_tab ORDER BY mem_id" ;
if($r=mysql_query($q))
{
	$scdata = mysql_fetch_array($r);
	$sc = $scdata['mem_id'];
	if($sc > 0)
	{
		header("Location: store.php?mem_id=$sc");
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