/*
	Developed by Robert Nyman, http://www.robertnyman.com
	Code/licensing: http://code.google.com/p/getelementsbyclassname/
*/	
var getElementsByClassName = function (className, tag, elm){
	if (document.getElementsByClassName) {
		getElementsByClassName = function (className, tag, elm) {
			elm = elm || document;
			var elements = elm.getElementsByClassName(className),
				nodeName = (tag)? new RegExp("\\b" + tag + "\\b", "i") : null,
				returnElements = [],
				current;
			for(var i=0, il=elements.length; i<il; i+=1){
				current = elements[i];
				if(!nodeName || nodeName.test(current.nodeName)) {
					returnElements.push(current);
				}
			}
			return returnElements;
		};
	}
	else if (document.evaluate) {
		getElementsByClassName = function (className, tag, elm) {
			tag = tag || "*";
			elm = elm || document;
			var classes = className.split(" "),
				classesToCheck = "",
				xhtmlNamespace = "http://www.w3.org/1999/xhtml",
				namespaceResolver = (document.documentElement.namespaceURI === xhtmlNamespace)? xhtmlNamespace : null,
				returnElements = [],
				elements,
				node;
			for(var j=0, jl=classes.length; j<jl; j+=1){
				classesToCheck += "[contains(concat(' ', @class, ' '), ' " + classes[j] + " ')]";
			}
			try	{
				elements = document.evaluate(".//" + tag + classesToCheck, elm, namespaceResolver, 0, null);
			}
			catch (e) {
				elements = document.evaluate(".//" + tag + classesToCheck, elm, null, 0, null);
			}
			while ((node = elements.iterateNext())) {
				returnElements.push(node);
			}
			return returnElements;
		};
	}
	else {
		getElementsByClassName = function (className, tag, elm) {
			tag = tag || "*";
			elm = elm || document;
			var classes = className.split(" "),
				classesToCheck = [],
				elements = (tag === "*" && elm.all)? elm.all : elm.getElementsByTagName(tag),
				current,
				returnElements = [],
				match;
			for(var k=0, kl=classes.length; k<kl; k+=1){
				classesToCheck.push(new RegExp("(^|\\s)" + classes[k] + "(\\s|$)"));
			}
			for(var l=0, ll=elements.length; l<ll; l+=1){
				current = elements[l];
				match = false;
				for(var m=0, ml=classesToCheck.length; m<ml; m+=1){
					match = classesToCheck[m].test(current.className);
					if (!match) {
						break;
					}
				}
				if (match) {
					returnElements.push(current);
				}
			}
			return returnElements;
		};
	}
	return getElementsByClassName(className, tag, elm);
};

function add_round_corners(){
	widgets = getElementsByClassName("widget_container", "div");
	if(widgets)
	for(w_i=0;w_i<widgets.length;w_i++){
		ele = widgets[w_i];

		is_banner_ad = (ele.className.indexOf('banner_ad')>=0);

		widget_header_visible = (ele.getElementsByTagName('h1')[0].style.display=='none')?false:true;
		if(!widget_header_visible){
			w_top_h = getElementsByClassName('curved_top','p',ele);
			if(w_top_h.length) w_top_h[0].style.display = "block";
		}else{
			w_top_h = getElementsByClassName('curved_top','p',ele);			
			if(w_top_h.length) w_top_h[0].style.display = "";
		}

		bottom_corners = getElementsByClassName('curved_bottom','p',ele);
		any_widget_container_present = getElementsByClassName('widget_container','div',ele);
		if(!bottom_corners.length && !any_widget_container_present.length && !is_banner_ad){
			w_top = document.createElement("p");
			w_bottom = document.createElement("p");

			ele.appendChild(w_top);
			ele.appendChild(w_bottom);

			w_top.className = "curved_top";
			w_bottom.className = "curved_bottom";
			
			if(w_top.style.display!='none'){
				a_ = document.createElement("div");
				a_.className = "a";
				a_right = document.createElement("div");
				a_right.className = "a_right transparent_50";
				a_left = document.createElement("div");
				a_left.className = "a_left transparent_50";
							
				b_ = document.createElement("div");
				b_.className = "b";
				b_right = document.createElement("div");
				b_right.className = "b_right transparent_50";
				b_left = document.createElement("div");
				b_left.className = "b_left transparent_50";
				
				
				c_ = document.createElement("div");
				c_.className = "c";
				d_ = document.createElement("div");
				d_.className = "d";
				
				e_ = document.createElement("div");
				e_.className = "e";
				e_right = document.createElement("div");
				e_right.className = "e_right transparent_50";
				e_left = document.createElement("div");
				e_left.className = "e_left transparent_50";
				
				
				w_top.appendChild(a_);
				a_.appendChild(a_left);
				a_.appendChild(a_right);
				
				w_top.appendChild(b_);
				b_.appendChild(b_left);
				b_.appendChild(b_right);
				
				
				w_top.appendChild(c_);
				w_top.appendChild(d_);
				
				w_top.appendChild(e_);
				e_.appendChild(e_left);
				e_.appendChild(e_right);				
			}
			
			if(w_bottom.style.display!='none'){			
				f_ = document.createElement("div");
				f_.className = "f";
				f_right = document.createElement("div");
				f_right.className = "f_right transparent_50";
				f_left = document.createElement("div");
				f_left.className = "f_left transparent_50";
							
				g_ = document.createElement("div");
				g_.className = "g";
				h_ = document.createElement("div");
				h_.className = "h";
				
				i_ = document.createElement("div");
				i_.className = "i";
				i_right = document.createElement("div");
				i_right.className = "i_right transparent_50";
				i_left = document.createElement("div");
				i_left.className = "i_left transparent_50";			
				
				j_ = document.createElement("div");
				j_.className = "j";
				j_right = document.createElement("div");
				j_right.className = "j_right transparent_50";
				j_left = document.createElement("div");
				j_left.className = "j_left transparent_50";			
			
				w_bottom.appendChild(f_);
				f_.appendChild(f_left);
				f_.appendChild(f_right);
	
				w_bottom.appendChild(g_);
				w_bottom.appendChild(h_);
	
				w_bottom.appendChild(i_);
				i_.appendChild(i_left);
				i_.appendChild(i_right);
				
				w_bottom.appendChild(j_);
				j_.appendChild(j_left);
				j_.appendChild(j_right);

			}
		}
	}
}

function remove_title_bgcolor(){
	h1Coll = document.getElementsByTagName('h1');
	for(i_h1=0; i_h1<h1Coll.length; i_h1++){
		eachH1 = h1Coll[i_h1];
		if(eachH1.style.background.toLowerCase().indexOf("png")>0 || eachH1.style.background.toLowerCase().indexOf("jpg")>0 || eachH1.style.background.toLowerCase().indexOf("gif")>0 ){
			eachH1.style.backgroundColor = "none"; // Not works in Chrome.. TODO
			eachH1.style.background = "none " + eachH1.style.background.match((/url\(.*\)/))[0];
		}
	}
}

function add_default_title_bg(){
	h1Coll = document.getElementsByTagName('h1');
	for(i_h1=0; i_h1<h1Coll.length; i_h1++){
		eachH1 = h1Coll[i_h1];
		if(eachH1.style.background.toLowerCase().indexOf("png")>0 || eachH1.style.background.toLowerCase().indexOf("jpg")>0 || eachH1.style.background.toLowerCase().indexOf("gif")>0){
			// do nothing
		}else{
				url = self.location+"";
				if(url.indexOf(".php")<0){
					url = url.substring(0,url.indexOf(".aspx"));
				}
				else{
					url = url.substring(0,url.indexOf(".php"));
				}
				url = url.substring(0,url.lastIndexOf("/"));
				eachH1.style.background = "url(" + url + "/images/default_title_bg.png)";
				url = url + "/images/default_title_bg.png";
				if(eachH1.style.filter){
					filterStr = eachH1.style.filter+"";
					filterStr = filterStr.replace(filterStr.match(/src=.*\.(png|jpg|gif)/gi),"src="+url);
					eachH1.style.filter = filterStr;
				}
		}
	}
}

self.run_once_add_default_title_bg = true;

function do_customized_ui_changes(){
	add_round_corners();
	if(run_once_add_default_title_bg){
		add_default_title_bg();
		self.run_once_add_default_title_bg = false;
	}	
	//remove_title_bgcolor();
}
