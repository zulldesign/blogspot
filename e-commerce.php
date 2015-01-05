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
<title>ARZEK E-COMMERCE-e-Commerce</title>
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
   $hs->title= "e-Commerce";
   $hs->body_bg= "#fafdeb";
   $hs->header_bg_path = "WidgetTitle/155e7c66ddcf4bffa159389dcc3e90a9.png";
   $hs->body_text= "#000000";
   $hs->header_text= "#000000";
   $hs->header_bg= "#0d90b2";   
   $hs->is_show_header = "block";
   $hs->html = "<img style=\"visibility:hidden;width:0px;height:0px;\" border=0 width=0 height=0 src=\"http://counters.gigya.com/wildfire/IMP/CXNID=2000002.0NXC/bT*xJmx*PTEyODI1NzY2NDQ*MDYmcHQ9MTI4MjU3NjY1MjY4NyZwPTcyMDY5MSZkPSZnPTEmbz1hNjIwZjNkYzZhNGU*OThlODgz/ZjhmNTNlYzEzMmQxMSZvZj*w.gif\" /><img style=\"visibility:hidden;width:0px;height:0px;\" border=0 width=0 height=0 src=\"http://counters.gigya.com/wildfire/IMP/CXNID=2000002.0NXC/bT*xJmx*PTEyODI1NDYyNzExNzEmcHQ9MTI4MjU*ODgzMzYyNSZwPTcyMDY5MSZkPSZnPTEmbz*2NDYyYTFhMTU3NTM*ZWJjOGU*/YmU5MGM3YjNjMjY4ZSZvZj*w.gif\" /><object width=\"540\" height=\"413\" type=\"application/x-shockwave-flash\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0\" allowScriptAccess=\"never\" allowNetworking=\"internal\"><param name=\"base\" value=\"http://static.wix.com\"></param><param name=\"movie\" value=\"http://static.wix.com/client/app.swf\" ></param><param name=\"quality\" value=\"high\" ></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"FlashVars\" value=\"pageId=_2OCAKYct5c-a&embedFormat=normal&embedID=oTj7v9gjBQ78n48gg4QPcCPHQH9x8N72cL;4icCJ3zaD651LZnyi3fviHuwk04F0a&partner_id=WMGs4POB1ko-a\" ></param><param name=\"scale\" value=\"noScale\" ></param><param name=\"salign\" value=\"tl\" ></param><embed wmode=\"opaque\" src=\"http://static.wix.com/client/app.swf\" quality=\"high\" allowFullScreen=\"true\" FlashVars=\"pageId=_2OCAKYct5c-a&embedFormat=normal&embedID=oTj7v9gjBQ78n48gg4QPcCPHQH9x8N72cL;4icCJ3zaD651LZnyi3fviHuwk04F0a&partner_id=WMGs4POB1ko-a\" type=\"application/x-shockwave-flash\" width=\"540\" height=\"413\" base=\"http://static.wix.com\" scale=\"noscale\" salign=\"tl\" ></embed></object><br/><object width=\"540\" height=\"413\" type=\"application/x-shockwave-flash\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0\" allowScriptAccess=\"never\" allowNetworking=\"internal\"><param name=\"base\" value=\"http://static.wix.com\"></param><param name=\"movie\" value=\"http://static.wix.com/client/app.swf\" ></param><param name=\"quality\" value=\"high\" ></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"FlashVars\" value=\"pageId=_2OCAKYct5c-a&embedFormat=normal&embedID=oTj7v9gjBQ78n48gg4QPcCPHQH9x8N72cL;4icCJ3zaD651LZnyi3fviHuwk04F0a&partner_id=WMGs4POB1ko-a\" ></param><param name=\"scale\" value=\"noScale\" ></param><param name=\"salign\" value=\"tl\" ></param><embed wmode=\"opaque\" src=\"http://static.wix.com/client/app.swf\" quality=\"high\" allowFullScreen=\"true\" FlashVars=\"pageId=_2OCAKYct5c-a&embedFormat=normal&embedID=oTj7v9gjBQ78n48gg4QPcCPHQH9x8N72cL;4icCJ3zaD651LZnyi3fviHuwk04F0a&partner_id=WMGs4POB1ko-a\" type=\"application/x-shockwave-flash\" width=\"540\" height=\"413\" base=\"http://static.wix.com\" scale=\"noscale\" salign=\"tl\" ></embed></object><br/>";
   $hs->to_html();
?>
                
            </div>
        </div>
            
        <div class="right_container">
         	<div class="right_child_1">
			<div id="dom1">
	             
				             
	        </div> 
			</div>
			
	        <div class="right_child_2">
			<div id="dom2">
	             
				             
	        </div> 
			</div>
	        
	        <div class="right_child_3">
			<div id="dom3">
	             
			<?php
   $hs = new html_snippet();
   $hs->id= "w34";
   $hs->title= "IrfanKhairi";
   $hs->body_bg= "#fafdeb";
   $hs->header_bg_path = "WidgetTitle/83479b3c87624143be88eba0646898d2.png";
   $hs->body_text= "#000000";
   $hs->header_text= "#000000";
   $hs->header_bg= "#0d90b2";   
   $hs->is_show_header = "block";
   $hs->html = "<a href='http://www.irfankhairi.com/mentor?a_aid=f93d9cc3&a_bid=4e69d055'><img src='http://www.irfankhairi.com/affiliate/scripts/sb.php?a_aid=f93d9cc3&a_bid=4e69d055' alt=\"\" title=\"\"></a>";
   $hs->to_html();
?>	             
	        </div> 
			</div>
			
	        <div class="right_child_4">
			<div id="dom4">
	             
			<?php 
   $ba = new banner_ads();
   $ba->id= "w44";
   $ba->title= "Amazon";
   $ba->body_bg= "#fafdeb";
   $ba->body_text= "#000000";
   $ba->header_text= "#000000";
   $ba->header_bg= "#0d90b2";   
   $ba->is_show_header = "block";
   $ba->image_path = "BannerImages/fccfde482b784e1bacbeed2eb1a6e637.jpg";
   $ba->link_url = "http://astore.amazon.com/clicknconnect07-20";
   $ba->desc = "";
   $ba->width = "100%";
   $ba->height = "100px";
   $ba->header_bg_path = "WidgetTitle/fccfde482b784e1bacbeed2eb1a6e637.png";
   $ba->to_html();
?><?php 
   $ba = new banner_ads();
   $ba->id= "w45";
   $ba->title= "34";
   $ba->body_bg= "#fafdeb";
   $ba->body_text= "#000000";
   $ba->header_text= "#000000";
   $ba->header_bg= "#0d90b2";   
   $ba->is_show_header = "block";
   $ba->image_path = "BannerImages/eac3723ade094d40a4f4f82920f710f8.jpg";
   $ba->link_url = "http://www.maxhosting.com.my/affiliate/idevaffiliate.php?id=318";
   $ba->desc = "";
   $ba->width = "100%";
   $ba->height = "100px";
   $ba->header_bg_path = "WidgetTitle/eac3723ade094d40a4f4f82920f710f8.png";
   $ba->to_html();
?><?php 
   $ba = new banner_ads();
   $ba->id= "w46";
   $ba->title= "3docean";
   $ba->body_bg= "#fafdeb";
   $ba->body_text= "#000000";
   $ba->header_text= "#000000";
   $ba->header_bg= "#0d90b2";   
   $ba->is_show_header = "block";
   $ba->image_path = "BannerImages/bf88d4ab5a5744eb828644e443d75571.jpg";
   $ba->link_url = "http://3docean.net/?ref=scopio9";
   $ba->desc = "";
   $ba->width = "100%";
   $ba->height = "100px";
   $ba->header_bg_path = "WidgetTitle/bf88d4ab5a5744eb828644e443d75571.png";
   $ba->to_html();
?><?php 
   $ba = new banner_ads();
   $ba->id= "w47";
   $ba->title= "Activeden";
   $ba->body_bg= "#fafdeb";
   $ba->body_text= "#000000";
   $ba->header_text= "#000000";
   $ba->header_bg= "#0d90b2";   
   $ba->is_show_header = "block";
   $ba->image_path = "BannerImages/abd7a64cf9ca46658f0f2acba5e6d08e.jpg";
   $ba->link_url = "http://activeden.net/?ref=scopio9";
   $ba->desc = "";
   $ba->width = "100%";
   $ba->height = "100px";
   $ba->header_bg_path = "WidgetTitle/abd7a64cf9ca46658f0f2acba5e6d08e.png";
   $ba->to_html();
?><?php 
   $ba = new banner_ads();
   $ba->id= "w48";
   $ba->title= "Graphicriver";
   $ba->body_bg= "#fafdeb";
   $ba->body_text= "#000000";
   $ba->header_text= "#000000";
   $ba->header_bg= "#0d90b2";   
   $ba->is_show_header = "block";
   $ba->image_path = "BannerImages/2f961bb3f66c4391bca9a5de66d711d9.jpg";
   $ba->link_url = "http://graphicriver.net/?ref=scopio9";
   $ba->desc = "";
   $ba->width = "100%";
   $ba->height = "100px";
   $ba->header_bg_path = "WidgetTitle/2f961bb3f66c4391bca9a5de66d711d9.png";
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