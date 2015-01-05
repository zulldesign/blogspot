<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>    
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
require_once(dirname(__FILE__)."/includes/rootdir/i_am_root.php");
require_once(dirname(__FILE__)."/includes/system.php");
?>    
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="windows-Target" content="_top">
<meta name="keywords" content="Game &amp; Shop Online With Just One Card! belajargitar - The Digital Card, your all-access pass for the online universe.">
<meta name="description" content="Game &amp; Shop Online With Just One Card! belajargitar - The Digital Card, your all-access pass for the online universe.">
<link rel="stylesheet" href="<?php echo $GLOBALS['base'];?>styles/style.css"/>
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<script language="javascript" src="<?php echo $GLOBALS['base'];?>js/skyline.js"></script>
<title>ARZEK E-COMMERCE-Home</title>
<!--HEADERTAG-->
</head>

<body>
<DIV class="main_container">         <!-- A kind of CONTAINER tag -->
    <div class="header">
            <div class="logo_container">
                <?php
                    $tag_logo = 'http://';
                    $tag_logo1 = 'https://';
                    $url_logo = 'LogoImage/logo_579068.jpg';
                    $echo_url_logo = '';
                    if (strchr($url_logo,$tag_logo)){
                        $echo_url_logo = $url_logo;
                    }
                    elseif (strchr($url_logo,$tag_logo1)){
                        $echo_url_logo = $url_logo;
                    }else{
                        $echo_url_logo = $GLOBALS['base'].$url_logo;
                    }
                ?>
                <img src="<?php echo $echo_url_logo;?>" border="0" />
            </div>
            <div class="search_container">
                <TABLE cellSpacing="0" cellPadding="0" border="0">
                    <TBODY><TR>
                        <TD width="20"><IMG height="34" src="<?php echo $GLOBALS['base'];?>images/bg_search_left.png" width="20" /></TD>
                        <TD class="search_box"><STRONG>search:</STRONG>&nbsp;</TD>
                        <TD class="search_box"><INPUT name="" value="" id="Tmp_Keywords" />&nbsp;</TD>
                        <TD class="search_box"><A href="#">
                            <IMG height="22" alt="GO!" id="btnSearch" onClick="javascript:search_result(0, document.getElementById('Tmp_Keywords').value)" src="<?php echo $GLOBALS['base'];?>images/button_go.png" width="36" border="0" /></A>
                        </TD>
                        <TD width="20"><IMG height="34" src="<?php echo $GLOBALS['base'];?>images/bg_search_right.png" width="20" /></TD>
                    </TR></TBODY>
                </TABLE>
	 	 	    <script type="text/javascript">
				    document.getElementById("Tmp_Keywords").onkeydown = function(e)
				    {
					    if (!e)
    						e = event;
    					if (e.keyCode == 13)
						    document.getElementById("btnSearch").onclick();
    				}
			    </script>			    
            </div>
            <div style="clear:both;height:0px"></div>  <!-- A trick to make sure that floating elements above should not affect TAGS below it -->                        
    </div>

    <div class="nav_menu" style="display:block;">   <!-- A kind of CONTAINER tag -->
	    <table border=0 cellpadding=0 cellspacing=0 width=950>    
		<tr>
		<td width=10><img src="<?php echo $GLOBALS['base'];?>images/bg_menu_left.png" width="10" height="30"></td>
		<td align=left class="nav_TD">
			<ul class="nav_item_container">
              <?php
		$tag_main = 'http://';
                $tag_main1 = 'https://';
		$url_main = 'index.php';
		$echo_url_main = '';
		if (strchr($url_main,$tag_main)){
			$echo_url_main = $url_main;
		}
                elseif (strchr($url_main,$tag_main1)){
			$echo_url_main = $url_main;
		}else{
			$echo_url_main = $GLOBALS['base'].$url_main;
		}
		
?>
<li><a href="<?php echo $echo_url_main;?>">Home</a></li><?php
		$tag_main = 'http://';
                $tag_main1 = 'https://';
		$url_main = 'e-commerce.php';
		$echo_url_main = '';
		if (strchr($url_main,$tag_main)){
			$echo_url_main = $url_main;
		}
                elseif (strchr($url_main,$tag_main1)){
			$echo_url_main = $url_main;
		}else{
			$echo_url_main = $GLOBALS['base'].$url_main;
		}
		
?>
<li><a href="<?php echo $echo_url_main;?>">e-Commerce</a></li><?php
		$tag_main = 'http://';
                $tag_main1 = 'https://';
		$url_main = 'contact_us.php';
		$echo_url_main = '';
		if (strchr($url_main,$tag_main)){
			$echo_url_main = $url_main;
		}
                elseif (strchr($url_main,$tag_main1)){
			$echo_url_main = $url_main;
		}else{
			$echo_url_main = $GLOBALS['base'].$url_main;
		}
		
?>
<li><a href="<?php echo $echo_url_main;?>">Contact Us</a></li>
        	</ul>		
		</td>
		<td width=10><img src="<?php echo $GLOBALS['base'];?>images/bg_menu_right.png" width="10" height="30"></td>
		</tr>
		</table>    
    </div>   

	<!--  the below section marks the position for occurrence of WIDGETS, which later will be rendered dynamically by SiteBuilder, at these positions -->
    <div class="content_top"></div>
    <div class="content">    <!-- A kind of CONTAINER tag -->
                
        <div class="left_container">         
            <div id="dom0" class="left_child_1">
                
            <?php
   $hs = new html_snippet();
   $hs->id= "w04";
   $hs->title= "Arzek:10-Chat";
   $hs->body_bg= "#fafdeb";
   $hs->header_bg_path = "WidgetTitle/90140224ffaf4b71ba200b534fcbd116.png";
   $hs->body_text= "#000000";
   $hs->header_text= "#000000";
   $hs->header_bg= "#0d90b2";   
   $hs->is_show_header = "block";
   $hs->html = "<object width=\"500\" height=\"360\" id=\"obj_1282582342625\"><param name=\"movie\" value=\"http://rzmessenger.chatango.com/group\"/><param name=\"AllowScriptAccess\" VALUE=\"always\"/><param name=\"AllowNetworking\" VALUE=\"all\"/><param name=\"AllowFullScreen\" VALUE=\"true\"/><param name=\"flashvars\" value=\"cid=1282582342625&a=FDE614&b=100&c=333333&d=333333&f=64&g=333333&j=333333&k=666666&l=FDE614&m=FFFFFF&n=333333&o=88&s=1&aa=1\"/><embed id=\"emb_1282582342625\" src=\"http://rzmessenger.chatango.com/group\" width=\"500\" height=\"360\" allowScriptAccess=\"always\" allowNetworking=\"all\" type=\"application/x-shockwave-flash\" allowFullScreen=\"true\" flashvars=\"cid=1282582342625&a=FDE614&b=100&c=333333&d=333333&f=64&g=333333&j=333333&k=666666&l=FDE614&m=FFFFFF&n=333333&o=88&s=1&aa=1\"></embed></object>";
   $hs->to_html();
?><?php
   $pp = new product_selector();
   $pp->aff_id = "579068";
   $pp->is_product_str = true;
   $pp->products_str = "17193-1,17247-1,17469-1,17469-2";
   $pp->selector_type = 1;
   $pp->id = "w05";
   $pp->title = "Recommended";
   $pp->body_bg = "#fafdeb";
   $pp->header_bg_path = "WidgetTitle/6e80e31f10af4854aeab55bc48926dee.png";
   $pp->body_text = "#000000";
   $pp->header_text = "#000000";
   $pp->header_bg= "#0d90b2";
   $pp->is_show_header = "block";
   $pp->display_num = "5";
   $pp->IsImageShow = "block";
   $pp->image_width = "70px";
   $pp->image_height = "82px";
   $pp->is_show_buy_btn = "none";
   $pp->is_show_download_btn = "none";
   $pp->is_show_detail_btn = "block";
   $pp->is_show_desc = "block";
   $pp->to_html();
?>	
                
            </div>
        </div>
            
        <div class="right_container">
         	<div class="right_child_1">
			<div id="dom1">
	             
			<?php
   $pc = new product_category();
   $pc->cate_str = "3_Audio/14_Business & Finance/26_Desktop Enhancements/40_Games/53_Home & Education";
   $pc->id= "w14";
   $pc->title= "Product Categories";
   $pc->body_bg= "#fafdeb";
   $pc->header_bg_path = "WidgetTitle/7d05aca377e84466859ac1d1fcae6350.png";
   $pc->body_text= "#000000";
   $pc->header_text= "#000000";
   $pc->header_bg= "#0d90b2";   
   $pc->is_show_header = "block";
   $pc->to_html();
?>	


	             
	        </div> 
			</div>
			
	        <div class="right_child_2">
			<div id="dom2">
	             
				             
	        </div> 
			</div>
	        
	        <div class="right_child_3">
			<div id="dom3">
	             
				             
	        </div> 
			</div>
			
	        <div class="right_child_4">
			<div id="dom4">
	             
			<?php 
   $ba = new banner_ads();
   $ba->id= "w44";
   $ba->title= "CIMB BANK";
   $ba->body_bg= "#fafdeb";
   $ba->body_text= "#000000";
   $ba->header_text= "#000000";
   $ba->header_bg= "#0d90b2";   
   $ba->is_show_header = "block";
   $ba->image_path = "BannerImages/9d8f210e0204419b914c808513490601.jpg";
   $ba->link_url = "http://rzmessenger.chatango.com/";
   $ba->desc = "";
   $ba->width = "100%";
   $ba->height = "180px";
   $ba->header_bg_path = "WidgetTitle/9d8f210e0204419b914c808513490601.png";
   $ba->to_html();
?><?php 
   $ba = new banner_ads();
   $ba->id= "w45";
   $ba->title= "";
   $ba->body_bg= "#fafdeb";
   $ba->body_text= "#000000";
   $ba->header_text= "#000000";
   $ba->header_bg= "#0d90b2";   
   $ba->is_show_header = "block";
   $ba->image_path = "BannerImages/04c79d9467df4b65a4a2d215bb2df883.jpg";
   $ba->link_url = "http://www.facebook.com/profile.php?id=1813968065";
   $ba->desc = "";
   $ba->width = "100%";
   $ba->height = "230px";
   $ba->header_bg_path = "WidgetTitle/04c79d9467df4b65a4a2d215bb2df883.png";
   $ba->to_html();
?><?php 
   $ba = new banner_ads();
   $ba->id= "w46";
   $ba->title= "PACKAGE#1";
   $ba->body_bg= "#fafdeb";
   $ba->body_text= "#000000";
   $ba->header_text= "#000000";
   $ba->header_bg= "#0d90b2";   
   $ba->is_show_header = "block";
   $ba->image_path = "BannerImages/14667722cc944d8bb2bd38cfdc455220.jpg";
   $ba->link_url = "http://arzek.co.cc/579068";
   $ba->desc = "";
   $ba->width = "100%";
   $ba->height = "150px";
   $ba->header_bg_path = "WidgetTitle/14667722cc944d8bb2bd38cfdc455220.png";
   $ba->to_html();
?><?php 
   $ba = new banner_ads();
   $ba->id= "w47";
   $ba->title= "PACKAGE#2";
   $ba->body_bg= "#fafdeb";
   $ba->body_text= "#000000";
   $ba->header_text= "#000000";
   $ba->header_bg= "#0d90b2";   
   $ba->is_show_header = "block";
   $ba->image_path = "BannerImages/6084eb89557c454690ed7338b776076c.jpg";
   $ba->link_url = "http://rizalzulfahmy.co.cc/578636";
   $ba->desc = "";
   $ba->width = "100%";
   $ba->height = "150px";
   $ba->header_bg_path = "WidgetTitle/6084eb89557c454690ed7338b776076c.png";
   $ba->to_html();
?><?php 
   $ba = new banner_ads();
   $ba->id= "w48";
   $ba->title= "PACKAGE#3";
   $ba->body_bg= "#fafdeb";
   $ba->body_text= "#000000";
   $ba->header_text= "#000000";
   $ba->header_bg= "#0d90b2";   
   $ba->is_show_header = "block";
   $ba->image_path = "BannerImages/d8928443230b43b7a901bb170789654e.jpg";
   $ba->link_url = "http://astore.amazon.com/clicknconnect07-20";
   $ba->desc = "";
   $ba->width = "100%";
   $ba->height = "150px";
   $ba->header_bg_path = "WidgetTitle/d8928443230b43b7a901bb170789654e.png";
   $ba->to_html();
?>
	        </div>
	        </div>
        </div>
        
        <div style="clear:both"></div>
    </div>
    <div class="content_bottom"></div>
	<div class="footer"  style="display:block;"> 
			<ul class="footer_nav">
   			<?php
		$tag_footer = 'http://';
                $tag_footer1 = 'https://';
		$url_footer = 'https://www.paypal.com/my/mrb/pal=Z5BU82XPS8KDC';
		$echo_url_footer = '';
		if (strchr($url_footer,$tag_footer)){
			$echo_url_footer = $url_footer;
		}
                elseif (strchr($url_footer,$tag_footer1)){
			$echo_url_footer = $url_footer;
		}
                else{
			$echo_url_footer = $GLOBALS['base'].$url_footer;
		}
		
?>
<li><a href="<?php echo $echo_url_footer;?>">arzek@2010</a></li>
			</ul>
	</div>

</DIV>         <!-End of MAIN CONTAINER tag -->
<script language="javascript">
func_loaded=false;
while(func_loaded==false){
	if(window.do_customized_ui_changes){
		setInterval("do_customized_ui_changes()",1000);
		func_loaded = true;
		break;
	}
}
</script>

<!--FOOTERTAG-->        <!-- this tag must be present  just before BODY ends or just before tag where MAIN CONTAINER ends -->
</body>
</html>