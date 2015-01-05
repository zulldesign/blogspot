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

class paging
{
	public static function get_num_list($search_element,
	$item_module,
	$select_item_module,
	$num)
	{	
/*		$search_element = new product_search_element();*/
		$total_products = $search_element->total_records;
		$total_pages = $search_element->total_pages;
		$num_para_name = $search_element->current_page;
		$page_num_list = '';
		if ($total_pages >1){
			if ($num_para_name == 1){
				$page_num_list = '1|';
				$page_num_start = 2;
				$page_num_end = 5;
			}else {
				$page_num_list .= str_replace($num, '1', $item_module);
				if ($num_para_name > 4 && $total_pages > 5) {
					if ($num_para_name == $total_pages) {
						$page_num_start = $num_para_name - 4;
						$page_num_end = $total_pages;
					}elseif ($num_para_name == $total_pages-1){
						$page_num_start = $num_para_name - 3;
						$page_num_end = $total_pages;
					}else {
						$page_num_start = $num_para_name - 2;
						$page_num_end = $num_para_name + 2;
					}
					if ($page_num_start == 1) $page_num_start = 2;
					$page_num_list .= '...|';
				}elseif ($num_para_name > 2){
					$page_num_start = 2;
					$page_num_end = 5;
				}else {
					$page_num_start = $num_para_name;
					$page_num_end = $num_para_name + 3;
				}
			}
			for ($i=$page_num_start;$i<$page_num_end;$i++){
				if($i >= $total_pages) break;
				if ($i == $num_para_name){
					$page_num_list .= str_replace($num,$i,$select_item_module);
				}else {
					$page_num_list .= str_replace($num,$i,$item_module);
				}
			}
			if ($i < $total_pages) $page_num_list .= "...|";
			if ($num_para_name == $total_pages && $num_para_name > 1) {
				$page_num_list .= $total_pages;
			}else {
				$page_num_list .= str_replace($num,$total_pages,$item_module);
			}
		}else {
			$page_num_list = "1";
		}
		return $page_num_list;
	}

	public static function get_next_page($url, $url_num_param, $search_element)
	{
		$total_pages = $search_element->total_pages;
		if ($search_element->current_page < $total_pages)
		{
			$cp = $search_element->current_page + 1;
			return str_replace($url_num_param, $cp, $url);
		}
		else 
			return str_replace($url_num_param, $search_element->total_pages, $url);
	}

	public static function get_prev_page($url, $url_num_param, $search_element)
	{
		if ($search_element->current_page > 1)
		{
			$cp = $search_element->current_page - 1;
			return str_replace($url_num_param, $cp, $url);
		}
		else 
			return str_replace($url_num_param, 1, $url);
	}

}
?>


