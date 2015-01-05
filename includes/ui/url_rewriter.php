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

class url_rewriter
{
	private static function _match($url, $item)
	{
		$str_arr = explode("/", $url);
		$pos = array_search($item, $str_arr);
		if (!$pos)
			return "";
		return $str_arr[$pos + 1];
	}
	
	public static function get_product_list_url($search_element)
	{	
		if ($search_element->category_id && !$search_element->key_word)
			$access_url = "product_list.php";
		else if (!$search_element->category_id && $search_element->key_word)
			$access_url = "search_result.php";
		$url = $GLOBALS['base'].$access_url;
		if ($search_element->page_size)
			$url.= "/ps/".$search_element->page_size;
		if ($search_element->category_id)
		    $url.= "/cid/".$search_element->category_id;
		if ($search_element->current_page)
		    $url.= "/cp/".$search_element->current_page;
		if ($search_element->key_word)
		    $url.= "/kw/".$search_element->key_word;
		if ($search_element->sort_type)
		    $url.= "/st/".$search_element->sort_type;
		if ($search_element->param)
		{
		    $url.= "/pa/".self::format($search_element->param);  
		} 
		return $url;
	}
	
	public static function get_product_list_unknow_current_page_url($search_element, $current_page_param_name)
	{
		if ($search_element->category_id && !$search_element->key_word)
			$access_url = "product_list.php";
		else if (!$search_element->category_id && $search_element->key_word)
			$access_url = "search_result.php";
		$url = $GLOBALS['base'].$access_url;
		if ($search_element->page_size)
			$url.= "/ps/".$search_element->page_size;
		if ($search_element->category_id)
		    $url.= "/cid/".$search_element->category_id;
		if ($search_element->current_page)
		    $url.= "/cp/".$current_page_param_name;
		if ($search_element->key_word)
		    $url.= "/kw/".$search_element->key_word;
		if ($search_element->sort_type)
		    $url.= "/st/".$search_element->sort_type;
		if ($search_element->param)
		{
		    $url.= "/pa/".self::format($search_element->param);  
		}
		return $url;
	}
	
	public static function get_product_list_url_data()
	{
		$url = $_SERVER['REQUEST_URI'];
		$search_element = new product_search_element();
		
		$search_element->page_size = self::_match($url, "ps");
		$search_element->category_id = self::_match($url, "cid");
		$search_element->current_page = self::_match($url,"cp");
		$search_element->key_word = self::_match($url,"kw");
		$search_element->sort_type = self::_match($url,"st");
		$search_element->product_id = self::_match($url,"pid");
		$search_element->param = self::_match($url, "pa");
        return $search_element;
	}
	
	public static function get_product_detail_url($pid, $product_name)
	{
		$url = $GLOBALS['base']."product_detail.php"."/pid/".$pid."/pa/".self::format($product_name);
		return $url;
	}
	
	
	public static  function get_product_detail_url_data()
	{
		$url = $_SERVER['REQUEST_URI'];
		$pid = self::_match($url, "pid");
        return $pid;
	}
	
	public static  function get_product_detail_url_data_product_name()
	{
		$url = $_SERVER['REQUEST_URI'];
		$pa = self::_match($url, "pa");
        return $pa;
	}
	
	public static  function format($param)
	{
		$result = "";
		for($i = 0; $i < strlen($param); $i++)
		{
			$c = substr($param, $i, 1);
			$ascNum = ord($c);
			if (48<=$ascNum && $ascNum<=57)
			{
				$result.= $c;
			}
			elseif (65<=$ascNum && $ascNum<=90)
			{
				$result.= $c;
			}
			elseif (97<=$ascNum && $ascNum<=122)
			{
				$result.= $c;
			}
			elseif ($ascNum == 45)
			{
				$result.= $c;
			}
			else 
			{
			$result.= "-";
			}
		}
		return $result;		
	}
}
?>
