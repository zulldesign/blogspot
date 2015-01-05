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

require_once(dirname(__FILE__)."/category_parent.php");

class category_list
{

	public static function get_categories($cate_str)
	{
		
		//get categories
		$cate_strs = explode("/", $cate_str);
		
		$parent_categories = array();// category_parent array;
		
		foreach ($cate_strs as $cstr)
		{
			$cid = "";
			$cname = "";
			$csub_name = "";
			// get one category id and names
			if (!$cstr) continue;
			$cstr_info = explode("_", $cstr);
			if (!$cstr_info[1]) continue;
			$cstr_info_names = explode("::", $cstr_info[1]);
			$cid = $cstr_info[0];
			$cname = $cstr_info_names[0];
			if (count($cstr_info_names) > 1)
			{
				//this is a child category, $cid is childs's id,  $cname is it's parent's name, $csub_name is it's name
				$csub_name = $cstr_info_names[1];
				if (!array_key_exists($cname, $parent_categories))
				{
					//it's parent had not been handled
					$parent_categories[$cname] = new category_parent("", $cname);
				}
				$parent_categories[$cname]->add_child(new category($cid, $csub_name));
			}
			else 
			{
				//this is a parent category, $cname is it's name
				if (!array_key_exists($cname, $parent_categories))
				{
					//it had not been handled
					$parent_categories[$cname] = new category_parent($cid, $cname);
				}
				else if (!$parent_categories[$cname]->id) 
				{
					$parent_categories[$cname]->id = $cid;
				}
			}
		}
		
		return $parent_categories;
	}
}
?>