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
 * $this->header_bg: widget header background color
 * $this->header_text: widget header text color
 * $this->body_bg: widget body background color
 * $this->body_text: widget body text color
 * $this->is_show_header: none/block, switch of show widget header
 * 
 * $this->aff_id: affiliate id
 * $product: product to show, refer to dao/product.php
*/
?>

<div id="<?php echo $this->id ?>" class="widget_container product_detail">
  <h1 class="detail_t" style="background:url(<?php echo $GLOBALS['base'].$this->header_bg_path?>);filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=<?php echo $GLOBALS['base'].$this->header_bg_path?>); color:<?php echo $this->header_text ?>; display:<?php echo $this->is_show_header ?>;">	
    <span><?php echo $this->title ?>&nbsp;</span></h1>
    <div class="detail_c" style="color:<?php echo $this->body_text?>; background-color:<?php echo $this->body_bg?>;
								border-bottom-color:<?php echo $this->header_text ?>;
								border-top-color:<?php echo $this->header_text ?>;">
		<?php if(!$product){ ?>
			The product data feed server is temporarily unavailable.<br><br>
            We apologize for any inconvenience this causes you.<br><br>
            Please visit us again soon.		
	    <?php }else{ ?>
        <?php
	       if ($product->boxshot == '')
		       $product->boxshot = $GLOBALS['base'].'images/images03.jpg';
        ?>
		<div class="detail_pic"><img src="<?php echo $product->boxshot ?>"/>
		        <div class="btn_container">
		        <?php
		           if (!$product->download_url)
		              $this->detail_down = "none";
		        ?>
		        <div class="btn" style="display:<?php echo $this->detail_down ?>;"><a href="<?php echo $product->download_url ?>"><img src="<?php echo $GLOBALS['base']."images/button_download_large.gif" ?>"></a></div>
		        <div class="btn"><a href="<?php echo $product->direct_purchase_url ?>"><img src="<?php echo $GLOBALS['base']."images/button_buy_large.gif" ?>"/></a></div>
		        </div>
		</div>
		<div class="detail_info">
            <div class="product_name"><p><a  style="color:<?php echo $this->body_text?>;" href="<?php echo $product->direct_purchase_url ?>"><?php echo $product->product_name ?></a></p></div>
            <div class="product_category"><p><?php echo $product->category_name ?></p></div>
			<div class="product_price"><p>Price free to try,<a href="<?php echo $product->direct_purchase_url ?>">$<?php echo $product->usd_price ?></a> to buy </p></div>            
            <div class="product_desc"><p><?php echo $product->long_desc ?></p></div>            
        </div>
        <div style="clear:both;"></div>
        <?php } ?>
        </div>
        
</div>