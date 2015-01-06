
//
// Shopping Cart Configuration
//

var EWPP_CHECK_ITEM_COUNT = false; // do not check item count // 3.5
var ewpp_itemsPerCookie = 3; // cart items per cookie
var ewppUtil = YAHOO.util;
var ewpp_useCookie = !ewppUtil.StorageEngineHTML5.isAvailable();
var ewpp_maxCartItems = (ewpp_useCookie) ? 45 : 99; // max number of cart items

// HTML 5 storage engine
// LOCATION_LOCAL - data cleared on demand
// LOCATION_SESSION - data cleared at the end of a user's session

var ewpp_storageEngine = null;
if (ewppUtil.StorageEngineHTML5.isAvailable()) {
	ewpp_storageEngine = ewppUtil.StorageManager.get(
	  ewppUtil.StorageEngineHTML5.ENGINE_NAME,
    ewppUtil.StorageManager.LOCATION_LOCAL, {force: true}); 
}
var ewpp_cartCookie = "ewpp_cart_item_";
var ewpp_cntCookie = "ewpp_cart_count";
var ewpp_shipCookie = "ewpp_ship";
var ewpp_cartDelimiter = "|";
var ewpp_optionDelim = ", ";
var ewpp_optionSep = ": ";
var ewpp_cartExpire = null; // expires when browser is closed, for ewpp_useCookie

//e.g. var ewpp_cartExpire = 'CartExpire(0, 1, 0, 0)'; // expires in 0 day 1 hour 0 minute 0 second
var ewpp_cartPath = "/";
var ewpp_showCartAddMsg = false; // 4.0
var ewpp_showCartRemoveMsg = false; // 4.0

/* DO NOT CHANGE! (BEGIN) */
var ewpp_fldID = "id";
var ewpp_fldItemNumber = "item_number";
var ewpp_fldItemName = "item_name";
var ewpp_fldAmount = "amount";
var ewpp_fldQuantity = "quantity";

//var ewpp_fldShipping = "shipping";
//var ewpp_fldShipping2 = "shipping2";

var ewpp_fldHandling = "handling";
var ewpp_fldTax = "tax";
var ewpp_fldDiscountType = "discounttype"; // 2.0
var ewpp_fldShipType = "shiptype"; // 2.0
var ewpp_fldTaxType = "taxtype"; // 3.0
var ewpp_fldWeight = "weight"; // 3.0
var ewpp_fldWeightCart = "weight_cart"; // 3.0
var ewpp_fldWeightUnit = "weight_unit"; // 3.0
var ewpp_fldAmountBase = "amount_base";
var ewpp_fldShipMethod = "shipmethod"; // shipping method
var ewpp_fldFirstName = "first_name"; // first name
var ewpp_fldLastName = "last_name"; // last name
var ewpp_fldAddress1 = "address1"; // address1
var ewpp_fldAddress2 = "address2"; // address2
var ewpp_fldCity = "city"; // city
var ewpp_fldState = "state"; // state
var ewpp_fldZip = "zip"; // zip
var ewpp_fldCountry = "country"; // country
var ewpp_fldEmail = "email"; // email
var ewpp_fldCustom = "custom"; // custom // 3.0
var ewpp_fldPhone = "night_phone"; // phone // 3.2
var ewpp_fldDiscountCode= "discountcode"; // discount code // 4.0
var ewpp_fldShipCost = "shipcost"; // ship cost // 3.5
var ewpp_fldTaxCost = "taxcost"; // tax cost // 3.5
var ewpp_fldHandleCost = "handlecost"; // handle cost // 3.5
var ewpp_fldGrandTotal = "grandtotal"; // grand total // 3.5
var ewpp_fldNetPrice = "netprice"; // 4.0
var ewpp_fldHandlingCart = "handling_cart"; // handling_cart
var ewpp_fldTaxCart = "tax_cart"; // tax_cart
var ewpp_cartView0 = "ewCartView0"; // 4.0
var ewpp_cartView1 = "ewCartView1"; // 4.0
var ewpp_cartView2 = "ewCartView2"; // 4.0
var ewpp_shipView1 = "ewShipView1"; // 4.0
var ewpp_shipView2 = "ewShipView2"; // 4.0

/* DO NOT CHANGE! (END) */
var ewpp_fldQuantitySize = 2; // 3.1

// phone regular expression // 3.2
// Note: Change the regular expression as needed, requires separator (ewpp_fldPhoneSep)
// if 3 parts, split as night_phone_a, night_phone_b and night_phone_c
// if 2 parts, split as night_phone_a and night_phone_b
// if no separator, submit as night_phone_b

var ewpp_fldPhoneSep = "-"; // phone separator // 3.2
var ewpp_fldPhoneRegExp	= "^(\\d{3})" + ewpp_fldPhoneSep + "(\\d{3})" + ewpp_fldPhoneSep + "(\\d{4})$"; // US phone number (123-123-1234) 
var ewpp_fldPhoneRequired	= false; // phone required? // 3.2
var ewpp_fldPhoneCheck = false; // check phone? // 3.2
var ewpp_fldStateCheck = false; // check state? // 3.2
var ewpp_useAddressOverride = true; // 4.0
var ewpp_shippingTaxType = 0; // 3.0
var ewpp_weightCart = 0; // 3.0 
var ewpp_weightUnit = "kgs"; // 3.0
var ewpp_HandlingCart = 0;
var ewpp_fldRemove = "<img src='images/delete.gif' alt='Remove' border='0'>";
var ewpp_urlCheckout = "checkout.php"; // checkout page
var ewpp_urlShipping = "shipping.php"; // shipping page
var ewpp_urlConfirm = "confirm.php"; // confirm page
var ewpp_urlQuery = "ewquery.php"; // ewquery page
var ewpp_divAmountName = "div_amount_";

// Option types
var EWPP_OPTION_SELECT_ONE = 0;
var EWPP_OPTION_SELECT_MULTIPLE = 1;
var EWPP_OPTION_RADIO = 2;
var EWPP_OPTION_CHECKBOX = 3;
var EWPP_OPTION_TEXT = 4;

// Options settings
//###var EWPP_OPTION_REPEAT_COLUMN = 5;
//var EWPP_OPTION_SELECT_MULTIPLE_SIZE = 4;
//var EWPP_OPTION_TEXTBOX_SIZE = 25;
//?var EWPP_OPTION_TEXTBOX_MAXLEN = 200;
// Menu

var EWPP_MENUBAR_RIGHTHOVER_IMAGE = "spry161/SpryMenuBarRightHover.gif";
var EWPP_MENUBAR_DOWNHOVER_IMAGE = "spry161/SpryMenuBarDownHover.gif";
var ewpp_RootMenu = null;
var ewpp_RootCat = null;

//
//
// Shopping cart events // 4.0
//
//
//
// Cart_Submitting
// argument:
// f - form object
function Cart_Submitting(f) {
	// enter your code here, return false if you want to abort submission

	return true;
}

//
// Cart_Showing
// argument:
// item - cart item object
function CartItem_Showing(item) {
	//alert(ewppJSON.stringify(item));
	// enter your code here

}

//
// CartSummary_Showing
// argument:
// summary - summary object
function CartSummary_Showing(summary) {
	//alert(ewppJSON.stringify(summary));
	// enter your code here

}

//
// Shipping_Showing
// argument:
// obj - shipping info object
function Shipping_Showing(obj) {
	//alert(ewppJSON.stringify(obj));
	// enter your code here

}

//
// Confirm_Submitting
// argument:
// f - form object
function Confirm_Submitting(f) {
	// enter your code here, return false if you want to abort submission

	return true;
}
