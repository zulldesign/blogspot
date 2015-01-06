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
<?php $EWPP_PAGE_ID = "shipping"; // Page ID ?>
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
<script language="JavaScript" type="text/javascript">

//
// Check Shipping Information
//
function CheckShipping(f) {

	var ppad = f.elements["ppad"];
	ppad = (ppad && ppad.checked);
	var fname = f.elements[ewpp_fldFirstName];
	var lname = f.elements[ewpp_fldLastName];
	var address1 = f.elements[ewpp_fldAddress1];
	var city = f.elements[ewpp_fldCity];
	var state = f.elements[ewpp_fldState];
	var zip = f.elements[ewpp_fldZip];
	var country = f.elements[ewpp_fldCountry];
	var email = f.elements[ewpp_fldEmail];
	var custom = f.elements[ewpp_fldCustom];
	var phone = f.elements[ewpp_fldPhone];	
	if (!ppad && fname && !fname.disabled) {
		if (fname.value == "") {
			alert(ewppLanguage.Phrase("RequiredMessage") + ' ' + ewppLanguage.Phrase("FirstName"));
			FocusOption(fname);
			return false;
		}
	}
	if (!ppad && lname && !lname.disabled) {
		if (lname.value == "") {
			alert(ewppLanguage.Phrase("RequiredMessage") + ' ' + ewppLanguage.Phrase("LastName"));
			FocusOption(lname);
			return false;
		}
	}
	if (!ppad && address1 && !address1.disabled) {
		if (address1.value == "") {
			alert(ewppLanguage.Phrase("RequiredMessage") + ' ' + ewppLanguage.Phrase("Address1"));
			FocusOption(address1);
			return false;
		}
	}
	if (!ppad && phone && !phone.disabled) { // 3.2
	 	if (ewpp_fldPhoneRequired && phone.value == "") {
			alert(ewppLanguage.Phrase("RequiredMessage") + ' ' + ewppLanguage.Phrase("Phone"));
			FocusOption(phone);
			return false;
		}		
		if (ewpp_fldPhoneCheck && !CheckPhone(phone.value)) {
			alert(ewppLanguage.Phrase("InvalidMessage") + ' ' + ewppLanguage.Phrase("Phone"));
			FocusOption(phone);			
			return false;
		}
	}	
	if (!ppad && city && !city.disabled) {
		if (city.value == "") {
			alert(ewppLanguage.Phrase("RequiredMessage") + ' ' + ewppLanguage.Phrase("City"));
			FocusOption(city);
			return false;
		}
	}
	if (!ppad && zip && !zip.disabled) {
		if (zip.value == "") {
			alert(ewppLanguage.Phrase("RequiredMessage") + ' ' + ewppLanguage.Phrase("Zip"));
			FocusOption(zip);
			return false;
		}
	}
	if (country && !country.disabled) {
		if ((country.type == "select-one" && country.selectedIndex <= 0) ||
			(country.type != "select-one" && country.value == "")) { // 3.1
			alert(ewppLanguage.Phrase("RequiredMessage") + ' ' + ewppLanguage.Phrase("Country"));
			FocusOption(country);
			return false;
		}		
	}
	if (state && !state.disabled) {
		if (ewpp_fldStateCheck) {
			if ((state.type == "select-one" && state.selectedIndex <= 0) ||
				(state.type != "select-one" && state.value == "")) { // 3.2
				alert(ewppLanguage.Phrase("RequiredMessage") + ' ' + ewppLanguage.Phrase("State"));
				FocusOption(state);
				return false;
			}
		}
	}
	if (email) {
		if (email.value == "") {
			alert(ewppLanguage.Phrase("RequiredMessage") + ' ' + ewppLanguage.Phrase("Email"));
			FocusOption(email);
			return false;
		} else if (!CheckEmail(email.value)) {
			alert(ewppLanguage.Phrase("InvalidMessage") + ' ' + ewppLanguage.Phrase("Email"));
			FocusOption(email);
			return false;
		}
	}
	if (custom) {
		if (custom.value.length > 255) {
			alert(ewppLanguage.Phrase("InvalidMessage") + ' ' + ewppLanguage.Phrase("CustomCaption"));
			FocusOption(custom);
			return false;
		}
	}
	return true;
}

// Check email format
function CheckEmail(elementValue) {

	var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
	return emailPattern.test(elementValue);
}

// Check phone number
function CheckPhone(elementValue) {

	var p = new RegExp(ewpp_fldPhoneRegExp);
	return p.test(elementValue);
}
</script>
<div class="paypalshopmaker ewTitle"><p><b><?php echo $PPLanguage->Phrase("ShippingDetails") ?></b></p></div>
<!--Shopping Cart Checkout Begin-->
<!-- DO NOT CHANGE THE IDs! -->
<div id="ewShipView1" spry:region="dsShipView" class="SpryHiddenRegion">
	<div class="paypalshopmaker" spry:if="shipdetails.toString() != ''">
	<form name="shipping" class="ewForm" spry:repeat="dsShipView">		
<?php if (EWPP_USE_PAYPAL) {?>{usePayPalStoredShippingAddress}<?php } ?>
		<table class="ewTable2">
			<tbody>
				<tr class="ewShipAddress">
					<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("FirstName") ?></td>
					<td><input type="text" name="first_name" value="{firstname}" size="20" maxlength="32"></td>
				</tr>
				<tr class="ewShipAddress">
					<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("LastName") ?></td>
					<td><input type="text" name="last_name" value="{lastname}" size="20" maxlength="64"></td>
				</tr>
				<tr class="ewShipAddress">
					<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("Address1") ?></td>
					<td><input type="text" name="address1" value="{address1}" size="30" maxlength="100"></td>
				</tr>
				<tr class="ewShipAddress">
					<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("Address2") ?></td>
					<td><input type="text" name="address2" value="{address2}" size="30" maxlength="100"></td>
				</tr>
				<tr class="ewShipAddress">
					<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("Phone") ?></td>
					<td><input type="text" name="night_phone" value="{phone}" size="30" maxlength="100"></td>
				</tr>
				<tr class="ewShipAddress">
					<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("City") ?></td>
					<td><input type="text" name="city" value="{city}" size="30" maxlength="40"></td>
				</tr>
				<tr class="ewShipAddress">
					<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("Zip") ?></td>
					<td><input type="text" name="zip" value="{zip}" size="30" maxlength="32"></td>
				</tr>
				<tr>
					<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("Email") ?></td>
					<td><input type="text" name="email" value="{email}" size="30" maxlength="127"></td>
				</tr>
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
				<?php if (EWPP_CUSTOM_AS_TEXTAREA) { ?>
				<tr>
					<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("CustomCaption") ?></td>
					<td>
						<textarea cols="40" rows="4" name="custom" onkeydown="TextCounter(this,this.form.cntcustom,255);" onnkeyup="TextCounter(this,this.form.cntcustom,255);";>{custom}</textarea><br>
						<input readonly="readonly" type="text" name="cntcustom" size="3" maxlength="3" value="{cntcustom}">&nbsp;<?php echo $PPLanguage->Phrase("TextAreaCntMessage") ?>
					</td>
				</tr>
				<?php } ?>				
				<?php if ($EWPP_DISCOUNT_CODE) { ?>
				<tr>
					<td class="ewTable2Header"><?php echo $PPLanguage->Phrase("DiscountCode") ?></td>
					<td><input type="text" name="discountcode" value="{discountcode}" size="30" maxlength="255"></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<br>
		<input type="button" name="btnContinue" id="btnContinue" value="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("Continue")) ?>" class="paypalshopmaker" onclick="if (SubmitShipping(this.form)) RedirectTo(ewpp_urlConfirm);">
	</form>
	</div>
	<div class="ewMessage" spry:if="shipdetails.toString() == ''"><?php echo $PPLanguage->Phrase("CartEmptyMessage") ?></div>
</div>
<?php

// Close connection
$conn->Close();
?>
<?php include "ppfooter.php" ?>
