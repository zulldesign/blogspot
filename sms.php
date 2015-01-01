<?php
include("konfigurasi.php");
include("peta.php");
global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl, $b480X60, $b120X600;
$site_templates_path = $basepath . "templat";
$site_templates_url = $baseurl . "templat";
$link = dbconnect();
$ros_ic = $HTTP_GET_VARS['ros_ic'];
$action = "";
$action = $HTTP_GET_VARS['action'];

if($ros_ic == "")
{
	echo "<p>Error!</b> ID Ahli yang hilang.</p>";
	exit;
}

//Baca data ahli sekali
$query1="SELECT * FROM store_info_tab WHERE ros_ic = $ros_ic";
if($res1=mysql_query($query1))
{
	$storeInfoData=mysql_fetch_array($res1);
	$ce = $storeInfoData['contact_email'];
	$pe = $storeInfoData['paypal_email'];
	$ms = $storeInfoData['mem_status'];
	$st = $storeInfoData['store_title'];
	$sd = $storeInfoData['short_desc'];
	$co = $storeInfoData['contact'];
	$ab = $storeInfoData['about'];
	$we = $storeInfoData['welcome_note'];
	$wi = $storeInfoData['welcome_img'];
	$sc = $storeInfoData['store_currency'];
	$sth = $storeInfoData['store_thankyou'];
	$store_template = $storeInfoData['template'];
}
//Adakah ini adalah ahli yang sah?
if($ce == "")
{
	echo "<p><Error!</b> ID ahli tidak sah atau akaun ahli tidak aktif.</p>";
	exit;
}

//Adalah persediaan template?
if($store_template == "")
{
	echo "<p><Error!</b> Persediaan Store adalah tidak lengkap. Sila simpan template anda mahu gunakan untuk kedai anda dari kawasan ahli. <a href='index.php?a=login'>Klik di sini untuk log masuk ke kawasan admin kedai ..</a></p>";
	exit;
}

//Membaca fail template
$store_page=file_get_contents($site_templates_path . "/" . $store_template. ".html");
//menggantikan data asas
$store_page=str_replace("[title]",$st,$store_page);
$store_page=str_replace("[shortdesc]",$sd,$store_page);
$store_page=str_replace("[site_name]",$sitename,$store_page);
$store_page=str_replace("[site_url]",$baseurl,$store_page);
$store_page=str_replace("[viewcart]",getViewCart($ros_ic, $pe),$store_page);
$store_page=str_replace("[store_menu]",getmenu($ros_ic, $action),$store_page);
$store_page=str_replace("[store_curr]",$sc,$store_page);

switch($action)
{
	case "";
		$store_page=showStoreMain($store_page,$ros_ic,$action, $st, $sd, $we);
		break;
	case "showCat";
		$catName = $HTTP_GET_VARS['catName'];
		$store_page=showStoreCat($store_page,$ros_ic,$action,$catName);
		break;
	case "showItem";
		$catName = $HTTP_GET_VARS['catName'];
		$itemID = $HTTP_GET_VARS['itemID'];
		$store_page=showStoreItem($store_page,$ros_ic,$action,$catName,$itemID);
		break;
	case "showContact";
		$store_page=showStoreContact($store_page,$co);
		break;
	case "showAbout";
		$store_page=showStoreAbout($store_page,$ab);
		break;
	case "thankYou";
		$store_page=showStoreThankyou($store_page,$ros_ic,$item_id);
		break;
}

//Jaga sepanduk
$store_page=str_replace("[banner_480X60]",$b480X60,$store_page);
$store_page=str_replace("[banner_120XANY]",$b120X600,$store_page);

//Buang baki tag sebelum menunjukkan halaman
$store_page=str_replace("[metatitle]","",$store_page);
$store_page=str_replace("[metadesc]","",$store_page);
$store_page=str_replace("[metakey]","",$store_page);
$store_page=str_replace("[site_url]","",$store_page);
$store_page=str_replace("[title]","",$store_page);
$store_page=str_replace("[banner1]","",$store_page);
$store_page=str_replace("[shortdesc]","",$store_page);
$store_page=str_replace("[store_menu]","",$store_page);
$store_page=str_replace("[viewcart]","",$store_page);
$store_page=str_replace("[welcome]","",$store_page);
$store_page=str_replace("[banner2]","",$store_page);
$store_page=str_replace("[site_name]","",$store_page);
$store_page=str_replace("[store_curr]","",$store_page);

echo $store_page;


dbclose($link);

function getViewCart($pros_ic, $ppe)
{
	$viewcartcode=<<< HTM
  	<hr size="1">
		Shopping Cart
		<br>
		<a href="#" onclick="window.open('https://www.paypal.com/cart/display=1&business=$ppe','cartwin','width=600,height=400,scrollbars,location,resizable,status');"><img src="https://www.paypal.com/images/view_cart_02.gif" border="0"></a>
		<hr size="1">
HTM;

	return $viewcartcode;
}

function getmenu($ppros_ic,$ppaction)
{
	$storemenu="<center><b>Menu</b><br>";
	$storemenu=$storemenu."<a href=sms.php?ros_ic=$ppros_ic>Home</a><br>";
	$storemenu=$storemenu."<a href=sms.php?ros_ic=$ppros_ic&action=showContact>Contact</a><br>";
	$storemenu=$storemenu."<a href=sms.php?ros_ic=$ppros_ic&action=showAbout>About</a></center><hr>";
	$storemenu=$storemenu."<center><b>Categories</b><br>";
	$query1="SELECT * FROM store_cats_tab WHERE ros_ic = $ppros_ic";
	if ($res1=mysql_query($query1))
	{
		while($storeCats1=mysql_fetch_array($res1))
		{
			$storemenu=$storemenu."<a href='sms.php?ros_ic=$ppros_ic&action=showCat&catName=$storeCats1[1]'>$storeCats1[1]</a><br>";
		}
	}
	$storemenu=$storemenu."<hr>";
	return $storemenu;
}

function showStoreMain($pstore_page,$pros_ic,$paction,$pst, $psd, $pwe)
{
	$pstore_page=str_replace("[metatitle]",$pst,$pstore_page);
	$pstore_page=str_replace("[metadesc]",$psd,$pstore_page);
	$pstore_page=str_replace("[metakey]",str_replace(" ", ",", $psd),$pstore_page);
	$pstore_page=str_replace("[welcome]",$pwe,$pstore_page);

	return $pstore_page;
}

function showStoreCat($pstore_page,$pros_ic,$paction,$pcatName)
{
	$pstore_page=str_replace("[metatitle]",$pcatName,$pstore_page);
	$pstore_page=str_replace("[metadesc]",$pcatName,$pstore_page);
	$pstore_page=str_replace("[metakey]",str_replace(" ", ",",$pcatName),$pstore_page);
	$pstore_page=getItems($pstore_page,$pros_ic,$pcatName);
	return $pstore_page;
}

function getItems($ppstore_page,$ppros_ic,$ppcatName)
{
	$cat_page= <<<HDR
		<p align="center"><b><u>Category: $ppcatName</u></b></p>
		<table border="0" cellpadding="5" cellspacing="0" align="left">
HDR;
	$query1="SELECT * FROM store_items_tab WHERE ros_ic = $ppros_ic AND category_name = '$ppcatName'";
	if ($res1=mysql_query($query1))
	{
		while($storeItemsData=mysql_fetch_array($res1))
		{
			if ($storeItemsData[8])
			{
				$image_data="<img src='$storeItemsData[8]' width=100>";
			}
			else
			{
				$image_data="<b>Image not available</b>";
			}
			$item_data= <<<HTM
					<tr>
						<td align="left" valign="top">
							$image_data
						</td>
						<td align="left" valign="top">
							<p>Item ID: $storeItemsData[3]<br>
							Item Title: $storeItemsData[4]<br>
							Item Qty: $storeItemsData[5]<br>
							Item Price: $storeItemsData[6]<br>
							<a href="sms.php?ros_ic=$ppros_ic&action=showItem&catName=$ppcatName&itemID=$storeItemsData[3]">Klik di sini untuk maklumat lanjut</a></p>
						</td>
					</tr>
HTM;
			$total_items = $total_items . $item_data;			
		}
		$total_items = $total_items . "</table>";
		$cat_page = $cat_page . $total_items;
		$ppstore_page=str_replace("[welcome]",$cat_page,$ppstore_page);
	}
	return $ppstore_page;
}

function showStoreItem($pstore_page,$pros_ic,$paction,$pcatName,$pitemID)
{
	$query1="SELECT * FROM store_items_tab WHERE ros_ic = $pros_ic AND item_id = '$pitemID'";
	if ($res1=mysql_query($query1))
	{
		$storeItemsData=mysql_fetch_array($res1);
		$it = $storeItemsData['item_title'];
		$pstore_page=str_replace("[metatitle]",$it,$pstore_page);		
	}
	$pstore_page=getItem($pstore_page,$pros_ic,$pcatName,$pitemID);
	return $pstore_page;
}

function getItem($ppstore_page,$ppros_ic,$ppcatName,$ppitemID)
{
	$query1="SELECT * FROM store_items_tab WHERE ros_ic = $ppros_ic AND item_id = '$ppitemID'";
	if ($res1=mysql_query($query1))
	{
		$storeItemsData=mysql_fetch_array($res1);
		$iimage = $storeItemsData['item_image'];
		$ititle = $storeItemsData['item_title'];
		$iqty = $storeItemsData['item_qty'];
		$iprice = $storeItemsData['item_price'];
		$itype = $storeItemsData['item_category'];
		$iship = $storeItemsData['item_shipping'];
		$idet = $storeItemsData['item_details'];
		if($iimage != "")
		{
			$image_data="<img src='$iimage' width=200>";
		}
		else
		{
			$image_data="<b>Image not available</b>";
		}
		$item_page= <<<HTM
			<p>$image_data</p>
			<p>Item ID: $ppitemID<br>
			Item Title: $ititle<br>
			Item Qty: $iqty<br>
			Item Price: $iprice<br>
			</p>
HTM;
		$item_page=$item_page . getBuyButton($ppros_ic,$ppitemID,$ititle,$iqty,$iprice,$itype);
		if($itype=="Physical goods")
		{
  		$item_page=$item_page . getCartButton($ppros_ic,$ppitemID,$ititle,$iqty,$iprice);
		}
		$item_page=$item_page."<p>Shipping details: $iship</p><p>Item details:<br>$idet";
	}
	$ppstore_page=str_replace("[welcome]",$item_page,$ppstore_page);
	return $ppstore_page;
}

function getBuyButton($pppros_ic,$pppitemID,$pitemTitle,$pitemQty,$pitemPrice,$pitemType)
{
	global $baseurl;
	$query1="SELECT * FROM store_info_tab WHERE ros_ic = $pppros_ic";
	if($pitemType=="Digital goods")
	{
		$notify_url=$baseurl."/paypal.php?ros_ic=".$pppros_ic;
	}
	if($res1=mysql_query($query1))
	{
		$storeInfoData=mysql_fetch_array($res1);
		$peml = $storeInfoData['paypal_email'];
		$stcur = $storeInfoData['store_currency'];
		$buy_button= <<<HTM
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
      <input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="rm" value="1">
			<input type="hidden" name="notify_url" value="$notify_url">
      <input type="hidden" name="business" value="$peml">
      <input type="hidden" name="item_name" value="$pitemTitle">
      <input type="hidden" name="item_number" value="$pppitemID">
      <input type="hidden" name="amount" value="$pitemPrice">
      <input type="hidden" name="return" value="$baseurl/sms.php?action=thankYou&ros_ic=$pppros_ic&item_id=$pppitemID">
      <input type="hidden" name="no_note" value="1">
      <input type="hidden" name="currency_code" value="$stcur">
      <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but23.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
      </form>
HTM;
	}
	return $buy_button;
}

function  getCartButton($pppros_ic,$pppitemID,$pitemTitle,$pitemQty,$pitemPrice)
{
	$query1="SELECT * FROM store_info_tab WHERE ros_ic = $pppros_ic";
	if($res1=mysql_query($query1))
	{
		$storeInfoData=mysql_fetch_array($res1);
		$peml = $storeInfoData['paypal_email'];
		$stcur = $storeInfoData['store_currency'];
		$cart_button= <<<HTM
			<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
      <input type="hidden" name="cmd" value="_cart">
      <input type="hidden" name="business" value="$peml">
      <input type="hidden" name="item_name" value="$pitemTitle">
      <input type="hidden" name="item_number" value="$pppitemID">
      <input type="hidden" name="amount" value="$pitemPrice">
      <input type="hidden" name="no_note" value="1">
      <input type="hidden" name="currency_code" value="$stcur">
      <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but22.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
      <input type="hidden" name="add" value="1">
      </form>
HTM;
	}
	return $cart_button;
}

function showStoreContact($pstore_page,$pco)
{
	$pstore_page=str_replace("[welcome]",$pco,$pstore_page);
	return $pstore_page;
}

function showStoreAbout($pstore_page,$pab)
{
	$pstore_page=str_replace("[welcome]",$pab,$pstore_page);
	return $pstore_page;
}

?>