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

// Open connection to the database
$conn = ewpp_Connect();
?>
<?php

// Check application status
if (!ewpp_ApplicationEnabled()) {
	echo $PPLanguage->Phrase("ApplicationStopped");
	exit();
}
?>
<?php

// Page ID
$EWPP_PAGE_ID = "menupage";

// Init page content
$sMenuPageContent = "";

// Set up current menu item
ewpp_LoadMenuId();

// Build SQL
$sSql = EWPP_MENU_SELECT_SQL;
if ($ewpp_MenuId <> "")
	$sWhere = str_replace("@@MenuId@@", $ewpp_MenuId, EWPP_MENU_MENUID_FILTER);
if (@$sWhere <> "")
	$sSql .= " WHERE " . $sWhere;

// Open recordset
$rs = $conn->Execute($sSql); 
if ($rs && !$rs->EOF) {
	ewpp_LoadMenu($rs); // load menu details
	$sMenuPageContent = $ewpp_MenuPageContent;
	$rs->Close();
}
?>
<?php include "ppheader.php" ?>
<div class="paypalshopmaker">
<?php echo $sMenuPageContent ?>
</div>
<?php
$conn->Close();
?>
<?php include "ppfooter.php" ?>
