<?php if ($ewpp_TotalRecs > 0) { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>
<?php if ($ewpp_TotalPages > 1) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="paypalshopmaker"><?php echo $PPLanguage->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
<?php if ($ewpp_PageNumber == 1) { ?>
	<td><img src="images/firstdisab.gif" alt="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("First")) ?>" width="16" height="16" border="0"></td>
<?php } else { ?>
	<td><a href="<?php echo $ewpp_CartUrl . "?" . EWPP_START_REC ?>=1"><img src="images/first.gif" alt="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("First")) ?>" width="16" height="16" border="0"></a></td>
<?php } ?>
<!--previous page button-->
<?php if ($ewpp_PageNumber == 1) { ?>
	<td><img src="images/prevdisab.gif" alt="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("Prev")) ?>" width="16" height="16" border="0"></td>
<?php } else { ?>
	<td><a href="<?php echo $ewpp_CartUrl . "?" . EWPP_START_REC . "=" . (($ewpp_PageNumber-2)*$ewpp_DisplayRecs+1) ?>"><img src="images/prev.gif" alt="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("Prev")) ?>" width="16" height="16" border="0"></a></td>
<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" value="<?php echo $ewpp_PageNumber ?>" size="4"></td>
<!--next page button-->
<?php if ($ewpp_PageNumber == $ewpp_TotalPages) { ?>
	<td><img src="images/nextdisab.gif" alt="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("Next")) ?>" width="16" height="16" border="0"></td>
<?php } else { ?>
	<td><a href="<?php echo $ewpp_CartUrl . "?" . EWPP_START_REC . "=" . ($ewpp_PageNumber*$ewpp_DisplayRecs+1) ?>"><img src="images/next.gif" alt="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("Next")) ?>" width="16" height="16" border="0"></a></td>
<?php } ?>
<!--last page button-->
<?php if ($ewpp_PageNumber == $ewpp_TotalPages) { ?>
	<td><img src="images/lastdisab.gif" alt="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("Last")) ?>" width="16" height="16" border="0"></td>
<?php } else { ?>
	<td><a href="<?php echo $ewpp_CartUrl . "?" . EWPP_START_REC . "=" . (($ewpp_TotalPages-1)*$ewpp_DisplayRecs+1) ?>"><img src="images/last.gif" alt="<?php echo ewpp_HtmlEncode($PPLanguage->Phrase("Last")) ?>" width="16" height="16" border="0"></a></td>
<?php } ?>
	<td><span class="paypalshopmaker">&nbsp;<?php echo $PPLanguage->Phrase("of") ?>&nbsp;<?php echo $ewpp_TotalPages ?></span></td>	
	</tr></table>
<?php } ?>
</td>
<td align="right"><div class="paypalshopmaker"><?php echo $PPLanguage->Phrase("Record") ?>&nbsp;<?php echo $ewpp_StartRec ?>&nbsp;<?php echo $PPLanguage->Phrase("To") ?>&nbsp;<?php echo $ewpp_StopRec ?>&nbsp;<?php echo $PPLanguage->Phrase("Of") ?>&nbsp;<?php echo $ewpp_TotalRecs ?></div></td>
</tr></table>
<?php } else { ?>
<?php if (!$ewpp_NoRecordShown) { ?>
<span class="paypalshopmaker"><?php echo $PPLanguage->Phrase("NoRecord") ?></span>
<?php $ewpp_NoRecordShown = TRUE; ?>
<?php } ?>
<?php } ?>
