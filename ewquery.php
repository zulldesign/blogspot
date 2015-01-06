<?php
session_start(); // Initialize session data
ob_start(); // Turn on output buffering

// No cache
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0

// Include ADODB
include_once 'adodb5/adodb.inc.php';
?>
<?php include "ppcfg.php" ?>
<?php include "ppfn.php" ?>
<?php
if (@$_GET["sid"] == "" || @$_GET["sid"] <> ewpp_TeaEncrypt(session_id(), EWPP_RANDOM_KEY))
	exit();

// Open connection to the database
$conn = ewpp_Connect();

// Execute query
if (@$_GET["item"] <> "") {
	$itemno = $_GET["item"];
	$itemcnt = ewpp_GetItemCount($itemno);	

	//ewpp_WriteLog("QUERY", "item number", $itemno);
	//ewpp_WriteLog("QUERY", "item count", $itemcnt);

	echo $itemcnt;
} elseif (@$_GET["code"] <> "") {
	$code = @$_GET["code"];
	$disc = ewpp_GetDiscount($code); // "Percent(0-100)|Amount"

	//ewpp_WriteLog("QUERY", "discount code", $code);
	//ewpp_WriteLog("QUERY", "discount", $disc);

	echo $disc;
} elseif (@$_GET["order"] <> "") {
	ewpp_WriteLog("ORDER", "submitted variables", ewpp_PostVars());
}

// Close connection
$conn->Close();
exit();
?>
