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
<?php $EWPP_PAGE_ID = "checkout"; // Page ID ?>
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
<?php include "ppheader.php" ?>
<div class="paypalshopmaker ewTitle"><p><b><?php echo $PPLanguage->Phrase("Checkout") ?></b></p></div>
<p><span class="paypalshopmaker"><?php echo $PPLanguage->Phrase("ItemsInCart") ?></span></p>
<form name="checkout" action="">
<div class="paypalshopmaker">
<!-- Note: DO NOT CHANGE THE IDs! -->
<div id="ewCartView1" spry:region="dsShopCartItems dsShopCartSummary" class="SpryHiddenRegion">
	<div spry:if="{dsShopCartItems::ds_RowCount} > 0">
	<table class="ewTable1">
		<thead>
			<tr>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescItemNumber") ?></td><!-- Item Number -->
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescItemName") ?></td>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescOption") ?></td>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescPrice") ?></td>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescDiscount") ?></td>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescQuantity") ?></td>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescAmount") ?></td>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescRemove") ?></td>
			</tr>
		</thead>
		<tbody>
			<tr spry:repeat="dsShopCartItems">
				<td>{itemnumber}</td>
				<td>{itemname}</td>
				<td>{option}</td>
				<td style="white-space: nowrap">{price}</td>
				<td>{discount}</td>
				<td>{quantity1}</td>
				<td style="white-space: nowrap">{subtotal}{hidden}</td>
				<td>{remove}</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6"><b><?php echo $PPLanguage->Phrase("DescTotal2") ?></b></td>
				<td style="white-space: nowrap"><b>{dsShopCartSummary::total}</b>{dsShopCartSummary::hidden}</td>
				<td></td>
			</tr>
		</tfoot>
	</table>
	<br>
	<input type="button" name="btnUpdateCart" id="btnUpdateCart" value="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("UpdateCart")) ?>" onclick="UpdateCartQuantity(this.form);">&nbsp;
	<input type="button" name="btnContinueCheckout" id="btnContinueCheckout" value="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("ContinueToCheckOut")) ?>" onclick="if (Cart_Submitting(this.form)) RedirectTo(ewpp_urlShipping);">
	</div>
	<div class="ewMessage" spry:if="{dsShopCartItems::ds_RowCount} == 0"><?php echo $PPLanguage->Phrase("CartEmptyMessage") ?></div>
</div>
<br>
</div>
</form>
<?php include "ppfooter.php" ?>
