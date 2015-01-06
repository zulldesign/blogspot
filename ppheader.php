<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php ewpp_WriteTitle() ?></title>
	<?php ewpp_WriteMeta(); ?>
	<script type="text/javascript" src="spry161/includes_minified/SpryData.js"></script>
	<script type="text/javascript" src="spry161/includes_minified/SpryJSONDataSet.js"></script>
	<script type="text/javascript" src="spry161/includes_minified/SpryMenuBar.js"></script>
	<script type="text/javascript" src="yui281/build/utilities/utilities.js"></script>
	<script type="text/javascript" src="yui281/build/json/json-min.js"></script>
	<script type="text/javascript" src="yui281/build/cookie/cookie-min.js" ></script>
	<script type="text/javascript" src="yui281/build/storage/storage-min.js" ></script>
	<script type="text/javascript" src="swfobject.js" ></script>
	<script type="text/javascript" src="ewcartconfig.js"></script>
	<script type="text/javascript" src="ewcart4-min.js"></script>
	<script type="text/javascript">
	<?php ewpp_WriteScript(); ?>
	</script>
	<link rel="stylesheet" type="text/css" href="spry161/<?php echo EWPP_MENUBAR_STYLESHEET ?>">
<link href="demo.css" rel="stylesheet" type="text/css">
<meta name="generator" content="PayPal Shop Maker v4.0.0.0">
</head>
<body>
<div class="ewLayout">
	<!-- header (begin) --><!-- *** Note: Only licensed users are allowed to change the logo *** -->
  <div class="ewHeaderRow"><img src="images/paypalshopmkrhdr.png" alt="" border="0"></div>
	<!-- header (end) -->
<!-- content (begin) -->
 <table cellspacing="0" class="ewContentTable">
	<tr>
		<td class="ewMenuColumn">
		<!-- left column (begin) -->
			<div class="paypalshopmaker">
			<!-- main menu (begin) -->
			<?php ewpp_WriteMenu(); // Menu ?>
			<!-- main menu (end) -->
			</div>			
			<br>
			<div class="paypalshopmaker">&nbsp;<b><?php echo $PPLanguage->Phrase("Browse") ?></b></div>
			<div class="paypalshopmaker">
			<!-- categories (begin) -->
			<?php ewpp_WriteCategories(); // Categories ?>
			<!-- categories (end) -->
			</div>
			<!-- Enter your HTML below the category menu here -->
		<!-- left column (end) -->
		</td>
		<td class="ewContentColumn">
		<!-- content column (begin) -->		
