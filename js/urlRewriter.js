
function urlRewriter(){}

urlRewriter.get_new_search_element = function()
{
	var se = {current_page:1};
	return se;
}

urlRewriter.get_seach_element = function(json_str)
{
	eval("var obj = "+ json_str);
	return obj;
}

urlRewriter.get_product_list_url = function(access_url, search_element)
{
	var se = search_element;
	var url = access_url;
	if (se.page_size)
		url += "/ps/" + se.page_size;
	if (se.category_id)
		url += "/cid/" + se.category_id;
	if (se.current_page)
		url += "/cp/" + se.current_page;
	if (se.key_word)
		url += "/kw/" + se.key_word;
	if (se.sort_type)
		url += "/st/" + se.sort_type;
	if (se.param)
		url += "/pa/" + se.param;
	return url;
}