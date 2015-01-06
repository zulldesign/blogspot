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
<?php $EWPP_PAGE_ID = "confirm"; // Page ID ?>
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
<div class="paypalshopmaker ewTitle"><p><b><?php echo $PPLanguage->Phrase("ConfirmOrder") ?></b></p></div>
<br>
<!--Shopping Cart Checkout Begin-->
<form method="post" name="confirm" class="ewForm" action="<?php echo EWPP_PAYPAL_URL ?>" onSubmit="return CheckCart(this);">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="upload" value="1">
<input type="hidden" name="business" value="<?php echo EWPP_BUSINESS ?>">
<p><b><?php echo $PPLanguage->Phrase("OrderDetails") ?></b></p>
<!-- DO NOT CHANGE THE IDs! -->
<div id="ewCartView2" spry:region="dsShopCartItems dsShopCartSummary" class="SpryHiddenRegion">
	<div spry:if="{dsShopCartItems::ds_RowCount} > 0">
	<table class="ewTable1">
		<thead>
			<tr>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescItemNumber") ?></td><!-- Item Number -->
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescItemName") ?></td>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescOption") ?></td>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescPrice") ?></td>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescQuantity") ?></td>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescDiscount") ?></td>
				<td class="ewTable1Header"><?php echo $PPLanguage->Phrase("DescAmount") ?></td>				
			</tr>
		</thead>
		<tbody>
			<tr spry:repeat="dsShopCartItems">
				<td>{itemnumber}</td>
				<td>{itemname}</td>
				<td>{option}</td>
				<td style="white-space: nowrap">{price}</td>
				<td>{quantity}</td>
				<td>-{discount}</td>
				<td style="white-space: nowrap">{subtotal}{hidden}</td>				
			</tr>
		</tbody>
		<tfoot>
			<tr class="ewTable1Summary">
				<td colspan="6"><?php echo $PPLanguage->Phrase("DescSubtotal") ?></td>
				<td style="white-space: nowrap">{dsShopCartSummary::total}</td>
			</tr>
			<tr spry:if="{dsShopCartSummary::nDiscount} > 0">
				<td colspan="6"><?php echo $PPLanguage->Phrase("Discount") ?> (<?php echo $PPLanguage->Phrase("DiscountCode") ?>)</td>
				<td style="white-space: nowrap">-{dsShopCartSummary::discount}</td>				
			</tr>
			<tr spry:if="{dsShopCartSummary::nShipping} > 0">
				<td colspan="6"><?php echo $PPLanguage->Phrase("DescShipping") ?></td>
				<td style="white-space: nowrap">{dsShopCartSummary::shipping}</td>				
			</tr>
			<tr spry:if="{dsShopCartSummary::nHandling} > 0">
				<td colspan="6"><?php echo $PPLanguage->Phrase("DescHandling") ?></td>
				<td style="white-space: nowrap">{dsShopCartSummary::handling}</td>
			</tr>
			<tr spry:if="{dsShopCartSummary::nTax} > 0">
				<td colspan="6"><?php echo $PPLanguage->Phrase("DescTax") ?></td>
				<td style="white-space: nowrap">{dsShopCartSummary::tax}</td>				
			</tr>
			<tr class="ewTable1Summary">
				<td colspan="6"><b><?php echo $PPLanguage->Phrase("DescTotal") ?></b></td>				
				<td style="white-space: nowrap"><b>{dsShopCartSummary::net}</b></td>
			</tr>
		</tfoot>
	</table>
	{dsShopCartSummary::hidden}
	</div>
	<div class="ewMessage" spry:if="{dsShopCartItems::ds_RowCount} == 0"><?php echo $PPLanguage->Phrase("CartEmptyMessage") ?></div>
</div>
<br>
<!-- shipping details -->
<!-- DO NOT CHANGE THE IDs! -->
<div id="ewShipView2" spry:region="dsShipView" class="SpryHiddenRegion">
	<div spry:if="nItems > 0">
<p><b><?php echo $PPLanguage->Phrase("ShippingDetails") ?></b></p>
<?php if (EWPP_USE_PAYPAL) {?>{usePayPalStoredShippingAddress}<?php } ?>
<table class="ewTable2">
	<tbody>
		<tr class="ewShipAddress" spry:if="'{firstname}' != ''">
			<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("FirstName") ?></td>
			<td>{firstname}</td>
		</tr>
		<tr class="ewShipAddress" spry:if="'{lastname}' != ''">
			<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("LastName") ?></td>
			<td>{lastname}</td>
		</tr>
		<tr class="ewShipAddress" spry:if="'{address1}' != ''">
			<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("Address1") ?></td>
			<td>{address1}</td>
		</tr>
		<tr class="ewShipAddress" spry:if="'{address2}' != ''">
			<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("Address2") ?></td>
			<td>{address2}</td>
		</tr>
		<tr class="ewShipAddress" spry:if="'{phone}' != ''">
			<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("Phone") ?></td>
			<td>{phone}</td>
		</tr>
		<tr class="ewShipAddress" spry:if="'{city}' != ''">
			<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("City") ?></td>
			<td>{city}</td>
		</tr>
		<tr class="ewShipAddress" spry:if="'{zip}' != ''">
			<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("Zip") ?></td>
			<td>{zip}</td>
		</tr>
		<tr spry:if="'{email}' != ''">
			<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("Email") ?></td>
			<td>{email}</td>
		</tr>
<!--
Note: Country and state are required by PayPal. If you remove the country (or state) row, make sure you add a hidden element named "country" (or "state") as default values. See Appendix C in PayPal's Website Payments Standard Integration Guide for allowable country codes. State (for U.S. only) must be two-character official U.S. abbreviation. e.g.
<input type="hidden" name="country" value="GB">
<input type="hidden" name="state" value="">
-->
		<tr spry:if="'{country}' != ''">
			<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("Country") ?></td>
			<td>{country}</td>
		</tr>		
		<tr spry:if="'{state}' != ''">
			<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("State") ?></td>
			<td>{state}</td>
		</tr>
		<tr spry:if="'{shipmethod}' != ''">
			<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("ShippingMethod") ?></td>
			<td>{shipmethod}</td>
		</tr>				
		<tr spry:if="'{custom}' != ''">
			<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("CustomCaption") ?></td>
			<td>{custom}</td>
		</tr>		
	</tbody>
</table>
{hidden}
	</div>
</div>
<br><br><br>
<?php If (EWPP_PROJECT_CHARSET <> "") { ?>
<input type="hidden" name="charset" value="<?php echo EWPP_PROJECT_CHARSET ?>">
<?php } ?>
<input type="hidden" name="currency_code" value="<?php echo EWPP_CURRENCY_CODE ?>">
<input type="hidden" name="cs" value="0">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="no_shipping" value="0">
<input type="submit" name="btnClickToBuy" id="btnClickToBuy" value="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("ClickToBuy")) ?>">
</form>
<!--Shopping Cart Checkout End  -->
<?php

// Close connection
$conn->Close();
?>
<?php include "ppfooter.php" ?>
