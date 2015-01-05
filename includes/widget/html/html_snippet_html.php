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
 * $this->html: the html snippet's inner html
*/
?>
<div id="<?php echo $this->id ?>" class="widget_container html_snippet">
	<h1 class="html_t" style="background:url(<?php echo $GLOBALS['base'].$this->header_bg_path?>);filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src=<?php echo $GLOBALS['base'].$this->header_bg_path?>); color:<?php echo $this->header_text ?>; display:<?php echo $this->is_show_header ?>;">	
    <span><?php echo $this->title ?>&nbsp;</span></h1>
	<div class="html_c" style="color:<?php echo $this->body_text ?>; background-color:<?php echo $this->body_bg ?>;
								border-bottom-color:<?php echo $this->header_text ?>; 
								border-top-color:<?php echo $this->header_text ?>;">	
 		<?php echo $this->html ?>
	</div>
</div>