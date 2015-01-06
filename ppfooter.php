		<p>&nbsp;</p>
		<?php echo ewpp_DebugMsg(); // Debug ?>			
		<!-- content column (end) -->
	</td>	
	<td class="ewRightColumn">
	<!-- right column (begin) -->
<?php if (in_array($EWPP_PAGE_ID, array("list", "view", "checkout", "menupage"))) { ?>			
	<div class="paypalshopmaker"><p><b><?php echo $PPLanguage->Phrase("ShopCart") ?></b></p></div>
	<!-- Note: DO NOT CHANGE THE IDs! -->
	<div id="ewCartView0" spry:region="dsShopCartItems dsShopCartSummary" class="SpryHiddenRegion">
		<div spry:if="{dsShopCartItems::ds_RowCount} > 0">
		<form name="simple" action="checkout.php">
			<table class="ewTable0">
				<tbody>
					<tr spry:repeat="dsShopCartItems">
						<td>{itemname}<br><span style="white-space: nowrap">{price}</span>&nbsp;&nbsp;<?php echo $PPLanguage->Phrase("DescQuantity") ?>&nbsp;{quantity1}&nbsp;{remove}
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td style="white-space: nowrap"><b><?php echo $PPLanguage->Phrase("DescTotal") ?></b>&nbsp;{dsShopCartSummary::total}</td>
					</tr>
				</tfoot>
			</table>
			<input type="submit" name="btnCheckout" id="btnCheckout" value="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("Checkout")) ?>">
		</form>
		</div>
		<div class="ewMessage" spry:if="{dsShopCartItems::ds_RowCount} == 0"><?php echo $PPLanguage->Phrase("CartEmptyMessage") ?></div>
	</div>
<?php } ?>&nbsp;	
		<!-- Enter your HTML below the tiny shopping cart here -->
	<!-- right column (end) -->
	</td>
</tr>
</table>
<!-- content (end) -->	
	<!-- footer (begin) --><!-- *** Note: Only licensed users are allowed to remove or change the following copyright statement. *** -->
	<div class="ewFooterRow">	
		<div class="ewFooterText"><?php echo EWPP_FOOTER_TEXT ?></div>
		<!-- Place other links, for example, disclaimer, here -->		
	</div>
	<!-- footer (end) -->	
</div>
</body>
</html>
