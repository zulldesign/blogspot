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
$EWPP_PAGE_ID = "finish"; // Clear Cart

// Unset all of the Session variables
$_SESSION = array();

// Delete the Session cookie and kill the Session
if (isset($_COOKIE[session_name()]))
	setcookie(session_name(), '', time()-42000, '/');

// Finally, destroy the Session
@session_destroy();
?>
<?php

// Open connection to the database
$conn = ewpp_Connect();
?>
<?php include "ppheader.php" ?>
<div class="paypalshopmaker ewTitle"><p><b><?php echo $PPLanguage->Phrase("ThankYou") ?></b></p></div>
<p><span class="paypalshopmaker">
<?php if (EWPP_USE_PAYPAL) { ?>
	<?php echo $PPLanguage->Phrase("ThankYouMessage") ?>
<?php } else { ?>
	<?php echo $PPLanguage->Phrase("OrderReceivedMessage") ?>
<?php } ?>
</span></p>
<?php

// Close connection
$conn->Close();
?>
<?php include "ppfooter.php" ?>
