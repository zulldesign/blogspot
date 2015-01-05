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

class product_search_element {
	public $affiliate_id;
	public $page_size;
	public $category_id;
	public $param;
	public $vendor_id;
	public $key_word;
	public $sort_type;
	public $current_page = 1;
	
	public $total_pages;
	public $total_records;
	
	
	public function to_json()
	{
		$json = "{";
		$json .= sprintf("%s:'%s',","affiliate_id" ,$this->affiliate_id );
		$json .= sprintf("%s:'%s',","page_size" ,$this->page_size );
		$json .= sprintf("%s:'%s',","category_id" ,$this->category_id );
		$json .= sprintf("%s:'%s',","param" ,$this->param );
		$json .= sprintf("%s:'%s',","vendor_id" ,$this->vendor_id );
		$json .= sprintf("%s:'%s',","key_word" ,$this->key_word );
		$json .= sprintf("%s:'%s',","sort_type" ,$this->sort_type );
		$json .= sprintf("%s:'%s',","current_page" ,$this->current_page );
		$json .= sprintf("%s:'%s',","total_pages" ,$this->total_pages );
		$json .= sprintf("%s:'%s'","total_records" ,$this->total_records );
		return $json."}";
		
	}
}

?>