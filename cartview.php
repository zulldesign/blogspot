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
$EWPP_PAGE_ID = "view";

// Set up current item
ewpp_GetItemId();
if (strval($ewpp_Item["ItemId"]) == "") {
	ob_end_clean();
	header("Location: " . $ewpp_CartUrl);
	exit();
}

// Build SQL
$sSql = EWPP_PRODUCT_SELECT_SQL;
$sWhere = str_replace("@@ItemId@@", $ewpp_Item["ItemId"], EWPP_PRODUCT_ITEM_FILTER);
if (EWPP_USE_ITEM_COUNT && EWPP_PRODUCT_ITEMCOUNT_FILTER <> "") {
	if ($sWhere <> "")
		$sWhere .= " AND ";
	$sWhere .= EWPP_PRODUCT_ITEMCOUNT_FILTER;
}
if ($sWhere <> "")
	$sSql .= " WHERE " . $sWhere;

// Open recordset
$rs = $conn->Execute($sSql);

// Load product details
ewpp_LoadProduct($rs);

// Close recordset
if ($rs)
	$rs->Close();
?>
<?php include "ppheader.php" ?>
<?php if (intval($ewpp_Item["ItemButtonTypeId"]) == 0) { // Note: Do NOT remove class="ewItemForm" ?>
<form name="pp<?php echo $ewpp_Item["ItemId"] ?>" class="ewItemForm ewForm" action="" onsubmit="return SubmitItemToCart(this);">
<?php } else { ?>
<form name="pp<?php echo $ewpp_Item["ItemId"] ?>" method="post" class="ewItemForm ewForm" action="<?php echo EWPP_PAYPAL_URL ?>" onsubmit="return SubmitItem(this);">
<?php } ?>
<!-- Common -->
<input type="hidden" name="id" value="<?php echo $ewpp_Item["ItemId"] ?>">
<input type="hidden" name="item_number" value="<?php echo ewpp_HtmlEncode($ewpp_Item["ItemNumber"]) ?>">
<input type="hidden" name="item_name" value="<?php echo ewpp_HtmlEncode($ewpp_Item["ItemName"]) ?>">
<input type="hidden" name="amount" value="<?php echo $ewpp_Item["ItemPrice"] ?>">
<input type="hidden" name="amount_base" value="<?php echo $ewpp_Item["ItemPrice"] ?>">
<?php if (EWPP_PROJECT_CHARSET <> "") { ?>
<input type="hidden" name="charset" value="<?php echo EWPP_PROJECT_CHARSET ?>">
<?php } ?>
<?php
for ($i=0; $i<7; $i++) {
	if ($ewpp_Item["ItemOption" . ($i+1) . "FieldName"] <> "") { ?>
<input type="hidden" name="on<?php echo $i ?>d" value="<?php echo ewpp_HtmlEncode($ewpp_Item["ItemOption" . ($i+1) . "FieldName"]) ?>">
<input type="hidden" name="or<?php echo $i ?>" value="<?php echo $ewpp_Item["ItemOption" . ($i+1) . "Required"] ?>">
<input type="hidden" name="on<?php echo $i ?>" value="">
<input type="hidden" name="os<?php echo $i ?>" value="">
<?php
	}
}
?>
<?php if (floatval($ewpp_Item["ItemHandling"]) > 0) { ?>
<input type="hidden" name="handling" value="<?php echo $ewpp_Item["ItemHandling"] ?>">
<?php } ?>
<?php if (floatval($ewpp_Item["ItemWeight"]) > 0) { ?>
<input type="hidden" name="weight" value="<?php echo $ewpp_Item["ItemWeight"] ?>">
<?php } ?>
<?php if (floatval($ewpp_Item["ItemTax"]) > 0) { ?>
<input type="hidden" name="tax" value="<?php echo ewpp_HtmlEncode($ewpp_Item["ItemTax"]) ?>">
<?php } ?>
<!-- Button Type Specific -->
<?php if (intval($ewpp_Item["ItemButtonTypeId"]) == 0) { // Add to Cart ?>
	<input type="hidden" name="discounttype" value="<?php echo $ewpp_Item["ItemDiscountTypeId"] ?>">
	<input type="hidden" name="taxtype" value="<?php echo $ewpp_Item["ItemTaxTypeId"] ?>">
	<input type="hidden" name="shiptype" value="<?php echo $ewpp_Item["ItemShippingTypeId"] ?>">
<?php } else { // Buy Now or Subscribe ?>
	<?php if (intval($ewpp_Item["ItemButtonTypeId"]) == 1) { // Buy Now ?>
		<input type="hidden" name="cmd" value="_xclick">
	<?php } elseif (intval($ewpp_Item["ItemButtonTypeId"]) == 2) { // Subscribe ?>
		<input type="hidden" name="cmd" value="_xclick-subscriptions">		
		<?php if (ewpp_ValidSubscribe($ewpp_Item, 3)) { // Mandatory, regular price ?>
			<?php if (ewpp_ValidSubscribe($ewpp_Item, 1)) { // Trial 1 ?>
		<input type="hidden" name="a1" value="<?php echo $ewpp_Item["ItemSubscribeA1"] ?>">
		<input type="hidden" name="p1" value="<?php echo $ewpp_Item["ItemSubscribeP1"] ?>">
		<input type="hidden" name="t1" value="<?php echo $ewpp_Item["ItemSubscribeT1"] ?>">			
				<?php if (ewpp_ValidSubscribe($ewpp_Item, 2)) { // Trial 2, requires Trial 1 ?>
		<input type="hidden" name="a2" value="<?php echo $ewpp_Item["ItemSubscribeA2"] ?>">
		<input type="hidden" name="p2" value="<?php echo $ewpp_Item["ItemSubscribeP2"] ?>">
		<input type="hidden" name="t2" value="<?php echo $ewpp_Item["ItemSubscribeT2"] ?>">
				<?php	} ?>							
			<?php	} ?>			
		<input type="hidden" name="a3" value="<?php echo $ewpp_Item["ItemSubscribeA3"] ?>">
		<input type="hidden" name="p3" value="<?php echo $ewpp_Item["ItemSubscribeP3"] ?>">
		<input type="hidden" name="t3" value="<?php echo $ewpp_Item["ItemSubscribeT3"] ?>">
		<?php } else { ?>
			<p><span class="ewWarning"><?php echo $PPLanguage->Phrase("InvalidSubscribeSettings") ?></span></p>		
		<?php	} ?>
		<?php if ($ewpp_Item["ItemSubscribeRecurring"] && intval($ewpp_Item["ItemSubscribeRecurringTimes"]) > 1) { ?>
		<input type="hidden" name="src" value="1">
		<input type="hidden" name="srt" value="<?php echo $ewpp_Item["ItemSubscribeRecurringTimes"] ?>">
		<input type="hidden" name="sra" value="<?php echo (($ewpp_Item["ItemSubscribeReattempt"]) ? 1 : 0) ?>">
		<?php } ?>
	<?php } ?>
	<input type="hidden" name="business" value="<?php echo EWPP_BUSINESS ?>">		
	<input type="hidden" name="currency_code" value="<?php echo EWPP_CURRENCY_CODE ?>">
	<input type="hidden" name="weight_unit" value="kgs">
	<input type="hidden" name="cs" value="0">
	<input type="hidden" name="no_note" value="0">
	<input type="hidden" name="no_shipping" value="0">
<?php } ?>
<!-- view page content begin -->
<p><span class="paypalshopmaker"><a href="<?php echo $ewpp_CartUrl ?>"><?php echo $PPLanguage->Phrase("BackToList") ?></a></span></p>
<table class="ewTable">
<tr><td colspan="2"><div class="paypalshopmaker ewTitle"><b><?php echo $ewpp_Item["ItemNumber"] ?></b></div></td></tr><!-- Item Number -->
<tr><td colspan="2"><div class="paypalshopmaker ewTitle"><b><?php echo $ewpp_Item["ItemName"] ?></b></div></td></tr>
<tr>
<td style="vertical-align: top;<?php if (EWPP_IMAGE_THUMBNAIL_WIDTH > 0) echo " width: " . EWPP_IMAGE_THUMBNAIL_WIDTH_VIEW . "px"; ?>">
<?php if (strval(@$ewpp_Item["ItemImage"]) <> "") { ?>
<a href="<?php echo ewpp_ImageHref($ewpp_Item["ItemImage"], EWPP_IMAGE_FULL_VIEW) ?>" target="_blank"><?php echo ewpp_ImageTag($ewpp_Item["ItemNumber"], $ewpp_Item["ItemImage"], EWPP_IMAGE_THUMBNAIL_VIEW, EWPP_IMAGE_THUMBNAIL_WIDTH_VIEW, EWPP_IMAGE_THUMBNAIL_HEIGHT_VIEW) ?></a>
<?php }?>
</td>
<td style="width: 99%; vertical-align: top;"><div class="paypalshopmaker">
<table class="ewItemTable" cellspacing="0">
<!-- Custom name value pairs -->
<?php
for ($i = 1; $i <= 6; $i++) {
	$si = ($i == 1) ? "" : strval($i);
	if (@$ewpp_Item["ItemCustomName" . $si] <> "" && @$ewpp_Item["ItemCustom" . $si] <> "") {
?>
<tr>
	<td style="vertical-align: top;"><?php echo $ewpp_Item["ItemCustomName" . $si] ?></td>
	<td><?php echo $ewpp_Item["ItemCustom" . $si] ?></td>
</tr>
<?php
	}
}
?>
<!-- Options -->
<?php
for ($i = 1; $i <= 7; $i++) {
	if (ewpp_ShowOption($ewpp_Item, $i)) {
?>
<tr><td style="vertical-align: top;"><?php echo $ewpp_Item["ItemOption" . $i . "FieldName"] ?></td><td><?php echo ewpp_FormatOption("os" . ($i-1) . "d", $ewpp_Item["ItemOption" . $i . "Type"], $ewpp_Item["ItemOption" . $i], @$ewpp_Item["ItemOption" . $i . "Default"]) ?></td></tr>
<?php
	}
}
?>
<tr><td><?php echo $PPLanguage->Phrase("Price") ?></td><td style="white-space: nowrap"><div id="div_amount_<?php echo $ewpp_Item["ItemId"] ?>" name="div_amount_<?php echo $ewpp_Item["ItemId"] ?>"><?php echo ewpp_FormatCurrency($ewpp_Item["ItemPrice"]) ?></div></td></tr>
</table>
<?php if (!EWPP_USE_ITEM_COUNT && $ewpp_Item["ItemCount"] <= 0 && EWPP_SHOW_SOLD_OUT) { ?>
<span class="ewWarning"><?php echo $PPLanguage->Phrase("SoldOut") ?></span>
<?php } else { ?>
	<?php if ($ewpp_Item["ItemButtonTypeId"] <> 2) { ?>
<?php echo $PPLanguage->Phrase("DescQuantity") ?>&nbsp;<input type="text" name="quantity" value="1" size="4">&nbsp;
	<?php } ?>
<input type="submit" name="btnSubmit" class="<?php echo ewpp_SubmitButtonClass($ewpp_Item) ?>" value="<?php echo ewpp_SubmitButtonText($ewpp_Item["ItemButtonTypeId"]) ?>">
<?php } ?>
</div>
</td>
</tr>
<tr>
<td colspan="2"><div class="paypalshopmaker"><?php echo ewpp_FormatDescription($ewpp_Item["ItemDescription"]) ?></div></td>
</tr>
<?php
for ($i = 2; $i <= 6; $i++) {
	if ($ewpp_Item["ItemImage" . $i] <> "") {
?>
<tr>
<td colspan="2"><a href="<?php echo ewpp_ImageHref($ewpp_Item["ItemImage" . $i], EWPP_IMAGE_FULL_VIEW) ?>" target="_blank"><?php echo ewpp_ImageTag($ewpp_Item["ItemNumber"] . "_" . $i, $ewpp_Item["ItemImage" . $i], EWPP_IMAGE_THUMBNAIL_VIEW, EWPP_IMAGE_THUMBNAIL_WIDTH_VIEW, EWPP_IMAGE_THUMBNAIL_HEIGHT_VIEW) ?></a></td>
</tr>
<?php
	}
}
?>
</table>
<!-- view page content end -->
</form>
<?php

// Close connection
$conn->Close();
?>
<?php include "ppfooter.php" ?>
