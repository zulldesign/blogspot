<?php
/*
  Software License Agreement (BSD License)

  Copyright (c) 2008, Digital River, Inc.
  All rights reserved.

  Redistribution and use of this software in source and binary forms, with or without
  modification, are permitted provided that the following conditions are met:

  * Redistributions of source code must retain the above copyright notice, 
    this list of conditions and the following disclaimer.

  * Redistributions in binary form must reproduce the above copyright notice,
    this list of conditions and the following disclaimer in the documentation
    and/or other materials provided with the distribution.

  * Neither the name of Digital River, Inc. nor the names of its contributors 
    may be used to endorse or promote products derived from this software 
    without specific prior written permission.

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
  IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, 
  INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
  BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
  DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY 
  OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING 
  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, 
  EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/**
 * $this->id : widget id
 * $this->title: widget title
 * $this->$this->header_bg_path: widget header background image's path
 * $this->header_text: widget header text color
 * $this->body_bg: widget body background color
 * $this->body_text: widget body text color
 * $this->is_show_header: none/block, switch of show widget header
 * 
 * $this->search_element: product search info, refer to dao\product_search_element.php
 * $products: product array
 * 
 * url_rewriter: seo friendly function static class.
 * paging: paging function static class.
 * 
 * urlRewriter: javascript class, it is the client seo friendly function static class
 * <?php echo $this->id ?>.searchElement: javascript obj, refer to $this->search_element
 **/
?>
<?php
    if (!$products)
    {
       echo "<div id=\"$this->id\" class=\"widget_container productlist\">
    <h1 class=\"plist_t\" style=\"background:url(" . $GLOBALS['base'].$this->header_bg_path .");filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=" . $GLOBALS['base'].$this->header_bg_path ." ); color:". $this->header_text . "; display:" . $this->is_show_header .";\"><span>$this->title &nbsp;</span></h1>
	<div style=\"background-color:$this->body_bg; padding-top:25px; height:50px; font-size:14px; text-align:center; font-weight:bold; color:$this->body_text;\">";
       echo "No matched results"."</div></div>";
    }
    elseif ($products == "error")
    {
       echo "<div id=\"$this->id\" class=\"widget_container productlist\">
    <h1 class=\"plist_t\" style=\"background:url(" . $GLOBALS['base'].$this->header_bg_path .");filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=" . $GLOBALS['base'].$this->header_bg_path ." ); color:". $this->header_text . "; display:" . $this->is_show_header .";\"><span>$this->title &nbsp;</span></h1>
	<div style=\"background-color:$this->body_bg; padding-top:25px; height:100px; font-size:14px; text-align:center; font-weight:bold; color:$this->body_text;\">";
       echo "The product data feed server is temporarily unavailable.<br><br>
            We apologize for any inconvenience this causes you.<br><br>
            Please visit us again soon."."</div></div>";
    }
    else {
?>

<?php
	$stitle = "";
	if ($this->search_element->category_id)
		$stitle .= "-".$this->search_element->param;
	if($stitle) 
	{
		$stitle = substr($stitle, 1);
		$stitle = urldecode($stitle);
	}
?>

<script type="text/javascript">
	document.getElementById("Tmp_Keywords").value = "<?php echo urldecode($this->search_element->key_word)?>";
</script>

<div id="<?php echo $this->id?>" class="widget_container productlist">
	<h1 class="plist_t" style="background:url(<?php echo $GLOBALS['base'].$this->header_bg_path ?>);filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=<?php echo $GLOBALS['base'].$this->header_bg_path ?> ); color:<?php echo $this->header_text ?> ; display:<?php echo $this->is_show_header ?>;">
	<span><?php echo $this->title?>&nbsp;</span></h1>
	<div class="plist_c" style="background-color:<?php echo $this->body_bg?>; color:<?php echo $this->body_text?>;
	border-bottom-color:<?php echo $this->header_text ?>; 
	border-top-color:<?php echo $this->header_text ?>;">	
	<div class="plist_topbar" style="color:<?php echo $this->body_text?>; border-color:<?php echo $this->body_text?>;">
		<div class="plist_form">
			<select 
				onChange="productlist_sort(<?php echo $this->id ?>.searchElement, this.value)">
				<option value="0" <?php echo $this->search_element->sort_type==0 ? "selected" : ""?> >Product A-Z</option>
				<option value="1" <?php echo $this->search_element->sort_type==1 ? "selected" : ""?> >&gt;&gt;Sorted By Product Z-A</option>
				<option value="2" <?php echo $this->search_element->sort_type==2 ? "selected" : ""?> >Low Price</option>
				<option value="3" <?php echo $this->search_element->sort_type==3 ? "selected" : ""?> >High Price</option>
				      </select>
	   	</div>
	   	<div class="plist_category">
	   	<span><?php echo $stitle ?></span>
		</div>
		<div style="clear:both;"></div>	
	</div>
	<div id="Content_<?php echo $this->id?>">
	<?php foreach($products as $p){ ?>
		<div class="plist_product" style="color:<?php echo $this->body_text?>;">
		<div class="plist_pic">
		<?php
			if ($p->boxshot == ''){
				$imge_path = $GLOBALS['base'].'images/images03.jpg';
			}else {
				$imge_path = $p->boxshot;
			}
		?>
		<img src="<?php echo $imge_path?>" width="60px" height="80px">
		</div>
		<div class="plist_info">
			<div class="product_name">
			<p><a style="color:<?php echo $this->body_text?>;" href="<?php echo url_rewriter::get_product_detail_url($p->product_id,$p->product_name)?>"><?php echo $p->product_name?></a><p>
			</div>
			<div class="product_price">
			<p>Price: <a href="<?php echo $p->direct_purchase_url?>"><?php echo $p->usd_price?></a></p>
			</div>
			<div class="product_desc">
			<?php echo $p->short_desc?>
			</div>
			</div>
			<div class="btn_container">
				<div class="btn">
				<a href="<?php echo url_rewriter::get_product_detail_url($p->product_id,$p->product_name)?>">
				<img src="<?php echo $GLOBALS['base']?>images/button_more_info.gif"/>
				</a>
				</div>			
				<div class="btn">
				<a href="<?php echo $p->direct_purchase_url?>">
				<img src="<?php echo $GLOBALS['base']?>images/button_buy.gif"/>
				</a>
				</div>
		        <?php
		           if (!$p->download_url)
		              $show_dnld_url = "none";
				   else
		              $show_dnld_url = "block";
		        ?>				
				<div class="btn" style="display:<?php echo $show_dnld_url ?>">
				<a href="<?php echo $p->download_url?>">
				<img src="<?php echo $GLOBALS['base']?>images/button_download.gif"/>
				</a>
				</div>				
			</div>
		<div style="clear:both;"></div></div>
	<?php } ?>
	</div>
	<div class="plist_topbar" style="color:<?php echo $this->body_text?>; border-color:<?php echo $this->body_text?>;">
	<div class="plist_pagination">
		
		<?php 
			$item_module = sprintf("<a style='color:<?php echo $this->body_text?>;' href=\"%s\">@num</a>|", url_rewriter::get_product_list_unknow_current_page_url($this->search_element, "@num"));
			$sel_item = "@num|";
			$num_list = paging::get_num_list($this->search_element, $item_module, $sel_item, "@num");
			$next_page_url = paging::get_next_page(url_rewriter::get_product_list_unknow_current_page_url($this->search_element, "@num"), "@num", $this->search_element);
			$prev_page_url = paging::get_prev_page(url_rewriter::get_product_list_unknow_current_page_url($this->search_element, "@num"), "@num", $this->search_element);
		?>	
		<?php
		     $startNum = ($this->search_element->current_page - 1) * $this->search_element->page_size + 1;
		     $endNum = ($startNum + $this->search_element->page_size - 1) < $this->search_element->total_records ? ($startNum + $this->search_element->page_size - 1) : $this->search_element->total_records;
		?>
		<?php echo $startNum ?>-<?php echo $endNum ?>&nbsp;of&nbsp;<?php echo $this->search_element->total_records?>
		
		<?php if ($this->search_element->current_page > 1){?>
			 <a style="color:<?php echo $this->body_text?>;" href="<?php echo $prev_page_url?>">&lt;&nbsp;Prev</a>
		<?php }?>
		
		&nbsp;&nbsp;Page:&nbsp;<?php echo $num_list?>&nbsp;&nbsp;
		
		<?php if ($this->search_element->current_page < $this->search_element->total_pages){?>
			 <a style="color:<?php echo $this->body_text?>;" href="<?php echo $next_page_url?>">Next&nbsp;&gt;</a>
		<?php }?>
	</div>
	<div class="plist_pagesize"><?php echo $this->search_element->page_size?>&nbsp;per&nbsp;page</div>
	</div>
 </div>
</div>
<?php } ?>