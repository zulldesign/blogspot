function wpl_remember_inputs( selector ){	//alert('hi');    jQuery(selector).each(		        function(){			//alert ('here');            //if this item has been cookied, restore it            var name = jQuery(this).attr('name');			var type_input = jQuery(this).attr("type");                       if( jQuery.cookie( name ) && type_input != 'submit' &&  type_input != 'hidden' ){                jQuery(this).val( jQuery.cookie(name) );            }            //assign a change function to the item to cookie it            jQuery(this).change(                function(){                    jQuery.cookie(name, jQuery(this).val(), { path: '/', expires: 365 });                }            );        }    );}function wpl_remember_form( selector ){    jQuery(selector).each(        function(){            var form_name = jQuery(this).attr('name');            var form_id = jQuery(this).attr('id');            var form_class = jQuery(this).attr('class');             if(typeof(form_name) != "undefined" && form_name !== null) {                  jQuery.cookie("wpl_form_uid", form_name, { path: '/', expires: 365 });             }              else if(typeof(form_id) != "undefined" && form_id !== null) {               jQuery.cookie("wpl_form_uid", form_id, { path: '/', expires: 365 });             }             else if (typeof(form_class) != "undefined" && form_class !== null) {                jQuery.cookie("wpl_form_uid", form_class, { path: '/', expires: 365 });             } else {                jQuery.cookie("wpl_form_uid", 'form_generic', { path: '/', expires: 365 });             }        }    );}jQuery(document).ready(function($) {		// Query String Stuff	var p  = jQuery("pre"),		result = "",		urlParams = {};	(function () {		var e,			d = function (s) { return decodeURIComponent(s).replace(/\+/g, " "); },			q = window.location.search.substring(1),			r = /([^&=]+)=?([^&]*)/g;		while (e = r.exec(q)) {			if (e[1].indexOf("[") == "-1")				urlParams[d(e[1])] = d(e[2]);			else {				var b1 = e[1].indexOf("["),					aN = e[1].slice(b1+1, e[1].indexOf("]", b1)),					pN = d(e[1].slice(0, b1));			  				if (typeof urlParams[pN] != "object")					urlParams[d(pN)] = {},					urlParams[d(pN)].length = 0;								if (aN)					urlParams[d(pN)][d(aN)] = d(e[2]);				else					Array.prototype.push.call(urlParams[d(pN)], d(e[2]));			}		}	})();	if (JSON) {		result = JSON.stringify(urlParams, null, 4);		  for (var k in urlParams) {				if (typeof urlParams[k] == "object") {				  for (var k2 in urlParams[k])					jQuery.cookie(k2, urlParams[k][k2], { expires: 365 });				} 				else {					jQuery.cookie(k, urlParams[k], { expires: 365 }); }			  }	}	//alert('hi');	// Fill Form Inputs from Cookies	wpl_remember_inputs( 'input' );	wpl_remember_inputs( 'textarea' );	wpl_remember_form( 'form' );});