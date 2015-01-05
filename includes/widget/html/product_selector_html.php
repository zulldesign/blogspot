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
 * $products: product list, product class refer to dao/product.php
 * $this->display_num: show regnow extra feed data number
 * $this->image_width: product images's width
 * $this->image_height: product images's height
 * $this->is_show_buy_btn: none/block, is show buy button
 * $this->is_show_download_btn: none/block, is show download button
 * $this->is_show_detail_btn: none/block, is show detail/more button
 * $this->is_show_desc: none/block, is show product's description
 * 
 *url_rewriter: seo friendly function static class.
 *
 * urlRewriter: javascript class, it is the client seo friendly function static class
 * searchElement: javascript obj, refer to $this->search_element
*/
?>

<div id="<?php echo $this->id ?>" class="widget_container productselector">
	<h1 class="productselector_t" style="background:url(<?php echo $GLOBALS['base'].$this->header_bg_path?>);filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=<?php echo $GLOBALS['base'].$this->header_bg_path?>); color:<?php echo $this->header_text ?>; display:<?php echo $this->is_show_header ?>;">	
    <span><?php echo $this->title ?>&nbsp;</span></h1>
	<div class="productselector_c" style="color:<?php echo $this->body_text ?>; background-color:<?php echo $this->body_bg ?>;
	border-bottom-color:<?php echo $this->header_text ?>; 
	border-top-color:<?php echo $this->header_text ?>;">
	<?php if (!$products) {?>
			The product data feed server is temporarily unavailable.<br><br>
            We apologize for any inconvenience this causes you.<br><br>
            Please visit us again soon.
	<?php }else{?>
	    <?php foreach($products as $p){ ?>
	    <?php
	    	if (!$p->boxshot)
	    	{
	    		$p->boxshot = $GLOBALS["base"]."images/images03.jpg";
	    	}
	    ?>
        <div class="product">
              <div class="product_pic"><img id="<?php echo $this->id ?>" width="<?php echo $this->image_width ?>" height="<?php echo $this->image_height ?>" src="<?php echo $p->boxshot ?>"/></div>
              <div class="product_info">
                 <div class="product_name"><a style="color:<?php echo $this->body_text ?>;" href="<?php echo url_rewriter::get_product_detail_url($p->product_id, $p->product_name) ?>"><?php echo $p->product_name ?></a></div>
                 <div class="product_desc" style="display:<?php echo $this->is_show_desc ?>;"><?php echo substr($p->short_desc,0,200) ?></div>
                 <div class="btn_container">
                     <div class="btn" style="display:<?php echo $this->is_show_detail_btn ?>;"><a href="<?php echo url_rewriter::get_product_detail_url($p->product_id, $p->product_name) ?>"><img style="display:<?php echo $this->is_show_detail_btn ?>;" src="<?php echo $GLOBALS['base']."images/button_more_info.gif" ?>"/></a></div>	 
                     <div class="btn" style="display:<?php echo $this->is_show_buy_btn ?>;"><a href="<?php echo $p->direct_purchase_url ?>"><img src="<?php echo $GLOBALS['base']."images/button_buy.gif" ?>"/></a></div>
                     <div class="btn" style="display:<?php echo (empty($p->download_url) ? "none" : $this->is_show_download_btn) ?>;"><a href="<?php echo $p->download_url ?>"><img src="<?php echo $GLOBALS['base']."images/button_download.gif" ?>"/></a></div>
                     <div style="clear:both;"></div>
                 </div>
              </div>
           	  <div style="clear:both;"></div>
        </div>
        <?php } ?>
    <?php } ?>    
	</div>
</div>
