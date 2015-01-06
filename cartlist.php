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
$EWPP_PAGE_ID = "list";

// Set up current category
if (@$_GET[EWPP_COMMAND] == EWPP_COMMAND_RESETALL)
	$_SESSION[EWPP_SESSION_CATEGORY_ID] = "";
ewpp_LoadCatById();

// Build SQL
$sSql = EWPP_PRODUCT_SELECT_SQL;
$sWhere = "";
$sOrderBy  = EWPP_PRODUCT_ORDER_BY;

// Add search filter
if (@$_GET[EWPP_COMMAND] == EWPP_COMMAND_RESET || @$_GET[EWPP_COMMAND] == EWPP_COMMAND_RESETALL)
	$_SESSION[EWPP_SESSION_SEARCH_KEYWORD] = "";
if (@$_GET[EWPP_SEARCH_KEYWORD] <> "") {
	$sKeyword = trim(ewpp_StripSlashes($_GET[EWPP_SEARCH_KEYWORD]));
	$_SESSION[EWPP_SESSION_SEARCH_KEYWORD] = $sKeyword;
} else {
	$sKeyword = @$_SESSION[EWPP_SESSION_SEARCH_KEYWORD];
}

// Add search criteria and item count filter
ewpp_AddFilters($sWhere);

// Add category filter
if (!EWPP_SEARCH_ALL_CATEGORIES || $sKeyword == "") {
	$sCatFilter = ewpp_GetCategoryFilter($ewpp_CatId);
	if ($sCatFilter <> "") {
		if ($sWhere <> "") $sWhere .= " AND ";
		$sWhere .= $sCatFilter;
	}
}
if ($sWhere <> "")
	$sSql .= " WHERE " . $sWhere;
if ($sOrderBy <> "")
	$sSql .= " ORDER BY " . $sOrderBy;

// Open recordset
$rs = $conn->Execute($sSql);

// Number of records per row (multi column)
$ewpp_RecPerRow = 0;

// Set up pager
$ewpp_StartRec = 0; // Start record index
$ewpp_StopRec = 0; // Stop record index
$ewpp_TotalRecs = 0; // Total number of records
$ewpp_DisplayRecs = 20;
$ewpp_NoRecordShown = FALSE;
$ewpp_TotalRecs = $rs->RecordCount();
$ewpp_TotalPages = intval($ewpp_TotalRecs/$ewpp_DisplayRecs);
if ($ewpp_TotalRecs % $ewpp_DisplayRecs > 0) $ewpp_TotalPages++;
$ewpp_PageNumber = 1;
$ewpp_StartRec = 1;

// Set up pager
ewpp_LoadPagerPosition();

// Set up end record position
$ewpp_StopRec = $ewpp_StartRec + $ewpp_DisplayRecs - 1;
if ($ewpp_StopRec > $ewpp_TotalRecs) $ewpp_StopRec = $ewpp_TotalRecs;
?>
<?php include "ppheader.php" ?>
<div class="paypalshopmaker ewTitle">
<p><b>
<?php
if (EWPP_SEARCH_ALL_CATEGORIES && $sKeyword <> "") {
	echo $PPLanguage->Phrase("Products");
} else {
	if (@$ewpp_CatPath <> "") {
		echo $PPLanguage->Phrase("Category") . $ewpp_CatPath;
	} elseif (@$ewpp_CatName <> "") {
		echo $PPLanguage->Phrase("Category") . $ewpp_CatName;
	} else {
		echo $PPLanguage->Phrase("Products");
	}
}
?>
</b></p>
</div>
<!-- Search form -->
<form class="ewForm" action="">
<table class="ewSearchTable">
	<tr>
		<td><span class="paypalshopmaker">
			<input type="text" name="<?php echo EWPP_SEARCH_KEYWORD ?>" id="<?php echo EWPP_SEARCH_KEYWORD ?>" size="30" value="<?php echo ewpp_HtmlEncode($sKeyword) ?>">
			<input type="submit" name="btnSearch" id="btnSearch" value="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("Search")) ?>">
			<?php if ($sKeyword <> "") { ?>
			&nbsp;<a href="<?php echo $ewpp_CartUrl . "?" . EWPP_COMMAND . "=" . EWPP_COMMAND_RESET ?>"><?php echo $PPLanguage->Phrase("ClearSearch") ?></a>&nbsp;
			<?php } ?>
		</span></td>
	</tr>
</table>
</form>
<br>
<?php include "cartpager.php" ?>
<?php
if ($ewpp_TotalRecs > 0) {
?>
<!-- list page content begin -->
<table class="ewTable">
<?php

// Move to first record
$nRecCount = $ewpp_StartRec - 1;
$nRecActual = 0;
if (!$rs->EOF) {
	$rs->MoveFirst();
	$rs->Move($ewpp_StartRec - 1);
}
while (!$rs->EOF && $nRecCount < $ewpp_StopRec) {
	$nRecCount++;
	if ($nRecCount >= $ewpp_StartRec) {
		$nRecActual++;
		$sRowClass = ($nRecActual % 2 == 1) ? EWPP_ROW_CLASS_NAME : EWPP_ALT_ROW_CLASS_NAME;
		ewpp_LoadProduct($rs); // Load product details

		// Cart detail URL
		$ewpp_CartViewFullUrl = "cartview.php?id=" . $ewpp_Item["ItemId"];
		$ewpp_CartViewUrl = (strrpos($ewpp_CartViewFullUrl, "/") !== FALSE) ? substr($ewpp_CartViewFullUrl, strrpos($ewpp_CartViewFullUrl, "/")+1) : $ewpp_CartViewFullUrl;
?>
<tr class="<?php echo $sRowClass ?>">
<td style="vertical-align: top;<?php if (EWPP_IMAGE_THUMBNAIL_WIDTH > 0) echo " width: " . EWPP_IMAGE_THUMBNAIL_WIDTH . "px"; ?>">
<a href="<?php echo $ewpp_CartViewUrl ?>"><?php echo ewpp_ImageTag($ewpp_Item["ItemNumber"], $ewpp_Item["ItemImage"], EWPP_IMAGE_THUMBNAIL_LIST, EWPP_IMAGE_THUMBNAIL_WIDTH, EWPP_IMAGE_THUMBNAIL_HEIGHT) ?></a>
</td>
<td style="vertical-align: top; width: 99%;">
<table class="ewItemTable" cellspacing="0">
<tr><td colspan="2"><a href="<?php echo $ewpp_CartViewUrl ?>"><?php echo $ewpp_Item["ItemNumber"] ?></a><!-- Item Number --></td></tr>
<tr><td colspan="2"><a href="<?php echo $ewpp_CartViewUrl ?>"><?php echo $ewpp_Item["ItemName"] ?></a></td></tr>
<?php

// Custom name value pairs
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
</table>
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
<!-- Options -->
<table class="ewItemTable" cellspacing="0">
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
</form>
</td>
</tr>
<?php
	}
	$rs->MoveNext();
}
?>
</table>
<!-- list page content end -->
<?php
}
if ($rs)
	$rs->Close();
?>
<?php include "cartpager.php" ?>
<?php
$conn->Close();
?>
<?php include "ppfooter.php" ?>
