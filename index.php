<?
include_once("konfigurasi.php");
include_once("peta.php");
$link = dbconnect();

$act=$HTTP_GET_VARS['a'];

switch($act)
{
	case "showMain";
		if(file_exists("satu.php"))
		{
			include("satu.php");
		}
		else
		{
			showMain();
		}
		break;
	case "register";
		register();
		break;
		
	case "register1";
		$uce = $HTTP_POST_VARS['ce'];
		$upe = $HTTP_POST_VARS['pe'];
		$upa = $HTTP_POST_VARS['pa'];
		$utosflag = $HTTP_POST_VARS['tosflag'];
		register1($uce, $upe, $upa, $utosflag);
		break;
	case "confirm";
		$midvalue = $HTTP_GET_VARS['mid'];
		$codevalue = $HTTP_GET_VARS['cbcode'];
		confirm($midvalue, $codevalue);
		break;
	case "login";
		login();
		break;
	case "login1";
		session_start();
		$cbuname = $HTTP_POST_VARS['uemail'];
		$cbupass = $HTTP_POST_VARS['upass'];
		login1($cbuname, $cbupass);
		break;
	case "mem";
		session_start();
		if(check_login())
		{
			mem();
		}
		else
		{
			login();
		}
		break;
	case "logoff";
		session_start();
		session_destroy();
		header("Location: index.php");
		break;
	case "retpass";
		retpass();
		break;
	case "retpass1";
		$uemail = $HTTP_POST_VARS['ueml'];
		retpass1($uemail);
		break;
	case "changepwd";
		session_start();
		if(check_login())
		{
			changepwd();
		}
		else
		{
			login();
		}
		break;
	case "changepwd1";
		session_start();
		if(check_login())
		{
			$upwdold = $HTTP_POST_VARS['opwd'];
			$upwdnew = $HTTP_POST_VARS['npwd'];
			$upwdnew1 = $HTTP_POST_VARS['npwd1'];

			changepwd1($upwdold, $upwdnew, $upwdnew1);
		}
		break;
	case "thankyou";
		$cbuname = $HTTP_GET_VARS['cbusername'];
		$pid = $HTTP_GET_VARS['p'];
		thankyou($cbuname, $pid);
		break;
	case "chooseTemplate";
		session_start();
		if(check_login())
		{
			chooseTemplate();
		}
		else
		{
			login();
		}
		break;
	case "saveTemplate";
		session_start();
		if(check_login())
		{
			$template = $HTTP_POST_VARS['template'];
			saveTemplate($template);
		}
		else
		{
			login();
		}
		break;
	case "showBasic";
		session_start();
		if(check_login())
		{
			showBasic();
		}
		else
		{
			login();
		}
		break;
	case "saveBasic";
		session_start();
		if(check_login())
		{
			$st = $HTTP_POST_VARS['storetitle'];
			$sd = $HTTP_POST_VARS['shortdesc'];
			$co = $HTTP_POST_VARS['contact'];
			$ab = $HTTP_POST_VARS['about'];
			$we = $HTTP_POST_VARS['welcome'];
			saveBasic($st, $sd, $co, $ab, $we);
		}
		else
		{
			login();
		}
		break;
	case "showPay";
		session_start();
		if(check_login())
		{
			showPay();
		}
		else
		{
			login();
		}
		break;
	case "savePay";
		session_start();
		if(check_login())
		{
			$sc = $HTTP_POST_VARS['storecurr'];
			$pa = $HTTP_POST_VARS['paypalEmail'];
			savePay($sc, $pa);
		}
		else
		{
			login();
		}
		break;
	case "showInv";
		session_start();
		if(check_login())
		{
			showInv();
		}
		else
		{
			login();
		}
		break;
	case "addCat";
		session_start();
		if(check_login())
		{
			addCat();
		}
		else
		{
			login();
		}
		break;
	case "saveCat";
		session_start();
		if(check_login())
		{
			$ca = $HTTP_POST_VARS['catname'];
			saveCat($ca);
		}
		else
		{
			login();
		}
		break;
	case "delCat";
		session_start();
		if(check_login())
		{
			$ca = $HTTP_POST_VARS['storecat2'];
			delCat($ca);
		}
		else
		{
			login();
		}
		break;
	case "delCat1";
		session_start();
		if(check_login())
		{
			$ca = $HTTP_POST_VARS['storecat3'];
			delCat1($ca);
		}
		else
		{
			login();
		}
		break;
	case "addItem";
		session_start();
		if(check_login())
		{
			$ca = $HTTP_POST_VARS['storecat2'];
			addItem($ca, "");
		}
		else
		{
			login();
		}
		break;
	case "saveItem";
		session_start();
		if(check_login())
		{
			$ica = $HTTP_POST_VARS['itemCat'];
			$iia = $HTTP_POST_VARS['itemID'];
			$ita = $HTTP_POST_VARS['itemTitle'];
			$iua = $HTTP_POST_VARS['imageURL'];
			$iqa = $HTTP_POST_VARS['itemQty'];
			$ipa = $HTTP_POST_VARS['itemPrice'];
			$isa = $HTTP_POST_VARS['itemShip'];
			$icaa = $HTTP_POST_VARS['item_category'];
			$idua = $HTTP_POST_VARS['item_dwld_url'];
			$idea = $HTTP_POST_VARS['itemDet'];
			saveItem($ica, $iia, $ita, $iua, $iqa, $ipa, $isa, $icaa, $idua, $idea);
		}
		else
		{
			login();
		}
		break;
	case "editItem";
		session_start();
		if(check_login())
		{
			$itemI = $HTTP_POST_VARS['storeitem1'];
			addItem("", $itemI);
		}
		else
		{
			login();
		}
		break;

	case "delItem";
		session_start();
		if(check_login())
		{
			$itemI = $HTTP_POST_VARS['itemID1'];
			delItem($itemI);
		}
		else
		{
			login();
		}
		break;
	case "delItem1";
		session_start();
		if(check_login())
		{
			$itemI = $HTTP_POST_VARS['itemID2'];
			delItem1($itemI);
		}
		else
		{
			login();
		}
		break;
	case "addThankyou";
		session_start();
		if(check_login())
		{
			addThankyou();
		}
		else
		{
			login();
		}
		break;
	case "saveThankyou";
		session_start();
		if(check_login())
		{
			$tn = $HTTP_POST_VARS['thankyouNote'];
			saveThankyou($tn);
		}
		else
		{
			login();
		}
		break;
	case "promotestore";
		promotestore();
		break;
	case "addBanners";
		session_start();
		if(check_login())
		{
			addBanners();
		}
		else
		{
			login();
		}
		break;
		
}

?>
<html>
<head>
<title><?php echo $sitename." - ".$pagetitle;?></title>
<meta name="keywords" content="<? echo $pagekeywords; ?>">
<meta name="description" content="<? echo $pagedesc; ?>">
<STYLE>
body {
	background-color:#ffffff;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10px;
	margin-top:0;
	margin-bottom:0;
	margin-left:0;
	margin-right:0;
	color:#333;
}
td {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	border-bottom-width: medium;
	border-bottom-style: none;
	color:#333;
}

</style>
</head>
<body text="#000000" style="font-face:arial">
<!--Header-->
<? if(file_exists("header.php")) { include("header.php"); } ?>
<!--Menu-->
<? if(file_exists("menu.php")) { include("menu.php"); } ?>
<!--Main-->
<table align="center" width="100%" cellpadding=0 cellspacing=0 border=0 bgcolor="#999999">
	<tr>
		<td valign=top align=center width="160">
<!--Left panel-->
<? if(file_exists("kiri.php")) { include("kiri.php"); } ?>
		</td>
		<td valign=top align=center bgcolor="#ffffff">
<? if(file_exists("banneratas.php")) { include("banneratas.php"); } ?>
<?
echo $pagecontent;
?>
<? if(file_exists("bannerbawah.php")) { include("bannerbawah.php"); } ?>
		</td>
		<td valign=top align=center  width="160">
<? if(file_exists("kanan.php")) { include("kanan.php"); } ?>
		</td>
	</tr>
</table>
<? if(file_exists("footer.php")) { include("footer.php"); } ?>

</body>
</html>