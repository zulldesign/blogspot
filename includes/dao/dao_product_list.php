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

require_once dirname(__FILE__).'/lite.php';
require_once dirname(__FILE__).'/product_search_element.php';
require_once dirname(__FILE__).'/product.php';

define("regnow_new_releases", 1);
define("regnow_most_successful", 2);
define("regnow_top_downloads", 3);

class product_list {
	
	const search_product_url = "http://dc1datafeed.regnow.com/Srv/ts.aspx";
	const product_detail_url = "http://dc1datafeed.regnow.com/srv/xs.aspx?req=2&cid=";
	const new_releases_url = 'http://dc1datafeed.regnow.com/feeds/new-releases.xml';
	const most_successful_url = "http://dc1datafeed.regnow.com/feeds/most-successful.xml";
	const top_downloads_url = "http://dc1datafeed.regnow.com/feeds/top-trial-downloads.xml";
	
	private static $cache;
	public static function set_cache($cache){
		product_list::$cache = $cache;
	}
	
	private static function get_data($url) {
		return get_web_contents($url);
	}
	
    private static function get_arr() {
		$arr = parse_ini_file(dirname(__FILE__)."/../../includes/configuration.ini");
		return $arr["remote_access"];		
	}
	
	private static function get_fopen() {
		$fp = fsockopen("dc1datafeed.regnow.com", 80, $errno, $errstr, 5);
        if (!$fp) 
        {
            return false;
        } 
        else 
        {
            $out = "GET / HTTP/1.1\r\n";
            $out .= "Host: dc1datafeed.regnow.com\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            $data = fgets($fp, 128);
            fclose($fp);
            return strpos($data, "200 OK");           
        }		
	}
	
    private static function get_curl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$url");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		$dtr=curl_exec($ch);
		curl_close($ch);
        return strpos($dtr, "200 OK");
	}
	
	public static function search_product($search_element) {
		$search_element->key_word = str_replace(" ", "%20", $search_element->key_word);
		$url = product_list::search_product_url;
		$url .= "?afid=" . $search_element->affiliate_id;
		$url .= "&req=1";
		$url .= "&pi=" . $search_element->current_page;
		$url .= "&ps=" . $search_element->page_size;
		$url .= "&cid=" . $search_element->category_id;
		$url .= "&vid=" . $search_element->vendor_id;
		$url .= "&k=" . $search_element->key_word;
		$url .= "&s=" . $search_element->sort_type;
		$url .= "&dk=" . $search_element->key_word;
		
		if (product_list::get_arr() == "curl")
		{
			$http_code = product_list::get_curl($url);
		}
		else 
		{
			$http_code = product_list::get_fopen();
		}
		    	
	    if (!$http_code)
		{
		    return "error";
		}
		$data = product_list::get_data ( $url );
		if ($data == "")
			return NULL;
		$search_element->total_records = substr ( $data, 0, strpos ( $data, "~`" ) );
		if (is_null($search_element->page_size))
			$search_element->page_size = 10;
		if ($search_element->page_size != 0)
			$search_element->total_pages = ceil ( $search_element->total_records / $search_element->page_size );
		else 
			$search_element->total_pages = 0;
		$data = substr ( $data, strpos ( $data, "~`" ) + 2 );
		$products_str = explode ( "~`", $data );
		$products = array ();
		foreach ( $products_str as $product_str ) {
			if ($product_str == "")
				continue;
			$p = explode ( "`|", $product_str );
			$add_pro = new product ( );
			$add_pro->product_id = $p [0];
			$add_pro->category_id = $p [1];
			$add_pro->vendor_id = $p [2];
			$add_pro->product_name = $p [3];
			$add_pro->short_desc = strlen($p [4]) > 200 ? substr($p[4] , 0 , 200).'...' : $p[4];
			$add_pro->long_desc = $p[4];
			$add_pro->usd_price = $p [5];
			$add_pro->download_url = $p[8];
			if ($add_pro->download_url)
			{
			   $add_pro->download_url = $p[8]."&linkid=asbdr-dld";
			}
			$add_pro->direct_purchase_url = $p[6]."&linkid=asbdr";
			$add_pro->boxshot = $p [7];
			array_push ( $products, $add_pro );
		}
		return $products;
	}
	
	public static function get_by_ids($afid, $ids) 
	{
		if (!$afid) $afid="123456";
		$pro_ids = explode ( ",", $ids );
		$url = product_list::product_detail_url."&afid=".$afid;
		$tmp_product = NULL;
		$products = array();
	    if (product_list::get_arr() == "curl")
		{
			$http_code = product_list::get_curl($url);
		}
		else 
		{
			$http_code = product_list::get_fopen();
		}	
        if (!$http_code)
		    return false;
		foreach ( $pro_ids as $id ) 
		{
			$cache_data = self::$cache->get($id);
		    if (!$cache_data)
			{
				$data_url = $url."&pid=".$id;
				$cache_data = self::get_data($data_url);
				self::$cache->save($cache_data, $id);
			}
			if ($cache_data == "")
				continue;
			$dom = new DOMDocument();
			$dom->loadXML($cache_data);
			$product_str = $dom->getElementsByTagName("Product")->item(0);
			if (is_null($product_str))
				continue;
			$tmp_product = new product();
			$tmp_product->product_id = $product_str->getAttribute("ProductID");
			$tmp_product->product_name = $product_str->getAttribute("ProductName");
			$tmp_product->vendor_id = $product_str->getAttribute("VendorID");
			$tmp_product->vendor_name = $product_str->getAttribute("VendorName");
			$tmp_product->vendor_home_page_url = $product_str->getAttribute("VendorHomepageURL");
			$tmp_product->usd_price = $product_str->getAttribute("USDPrice");
			$tmp_product->category_id = $product_str->getAttribute("CategoryID");
			$tmp_product->category_name = $product_str->getAttribute("CategoryName");
			$tmp_product->short_desc = $product_str->getAttribute("ShortDesc");
			$tmp_product->long_desc = $product_str->getAttribute("LongDesc");
			$tmp_product->download_url = $product_str->getAttribute("TrialURL");
			if ($tmp_product->download_url)
			{
				$tmp_product->download_url = $product_str->getAttribute("TrialURL")."&linkid=asbdr-dld";
			}
			$tmp_product->direct_purchase_url = $product_str->getAttribute("DirectPurchaseURL")."&linkid=asbdr";
			$tmp_product->banner_125 = $product_str->getAttribute("Banner125x125");
			$tmp_product->banner_90 = $product_str->getAttribute("Banner120x90");
			$tmp_product->encoding_charset = $product_str->getAttribute("EncodingCharSet");
			$tmp_product->boxshot = $product_str->getAttribute("Boxshot");
			array_push($products, $tmp_product);
		}
		return $products;
	}
	
	public static function get_by_id($afid, $id)
	{
		$pros = self::get_by_ids($afid, $id);
		return $pros[0];	
	}
	
	public static function get_regnow_extra_data($type_id = regnow_new_releases, $afid = "")
	{
		switch ($type_id)
		{
			case regnow_new_releases:
				$url = self::new_releases_url;
				$cache_name = "new_releases";
				break;
			case regnow_most_successful:
				$url = self::most_successful_url;
				$cache_name = "most_successful";
				break;
			case regnow_top_downloads:
				$url = self::top_downloads_url;
				$cache_name = "top_downloads";
				break;
			default:return NULL;
		}
		
	    if (product_list::get_arr() == "curl")
		{
			$http_code = product_list::get_curl($url);
		}
		else 
		{
			$http_code = product_list::get_fopen();
		}
		if (!$http_code)
		    return false;
	    $data = self::$cache->get($cache_name);
		if (!$data)
		{
			$data = self::get_data($url);
			self::$cache->save($data, $cache_name);
		}
		if (!$data)
			return NULL;
		$dom = new DOMDocument();
		$dom->loadXML($data);
		$items = $dom->getElementsByTagName("item");
		$products = array();
		foreach ($items as $i)
		{
			$tmp_product = new product();
			$tmp_product->product_id = $i->getElementsByTagName("productID")->item(0)->nodeValue;
			$tmp_product->product_name = $i->getElementsByTagName("title")->item(0)->nodeValue;
			$tmp_product->usd_price = $i->getElementsByTagName("USDPrice")->item(0)->nodeValue;
			$tmp_product->category_name = $i->getElementsByTagName("Category")->item(0)->nodeValue;
			$tmp_product->short_desc = $i->getElementsByTagName("description")->item(0)->nodeValue;
			$tmp_product->long_desc = $i->getElementsByTagName("description")->item(0)->nodeValue;
			$tmp_product->download_url = $i->getElementsByTagName("downloadURL")->item(0)->nodeValue;
			if ($tmp_product->download_url)
			{
				$tmp_product->download_url = $i->getElementsByTagName("downloadURL")->item(0)->nodeValue."&affiliate=".$afid."&linkid=asbdr-dld";
			}
			$tmp_product->direct_purchase_url = $i->getElementsByTagName("buyURL")->item(0)->nodeValue."&affiliate=".$afid."&linkid=asbdr";
			$tmp_product->banner_125 = $i->getElementsByTagName("boxShotURL")->item(0)->nodeValue;
			$tmp_product->banner_90 = $i->getElementsByTagName("boxShotURL")->item(0)->nodeValue;
			$tmp_product->boxshot = $i->getElementsByTagName("boxShotURL")->item(0)->nodeValue;
			array_push($products, $tmp_product);
		}
		return $products;
	}
}
$cache_options = array ('cacheDir' => dirname(__FILE__).'/cache/', 'lifeTime' => 86400, 'pearErrorMode' => CACHE_LITE_ERROR_DIE );
product_list::set_cache(new Cache_Lite ( $cache_options ));
?>