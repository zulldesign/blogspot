function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}


	function createXMLHttpRequest() {
		if(window.XMLHttpRequest) {
			xmlHttp = new XMLHttpRequest();
			if (xmlHttp.overrideMimeType) {
				xmlHttp.overrideMimeType('text/xml');
			}
		}
		else if (window.ActiveXObject) {
			try {
				xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
		}
		if (!xmlHttp) {
			window.alert("geting URL error!");
			return false;
		}
	}

	function output(val,getData,div_id,msg){
		phpfile = val;
		divid=div_id;
		data_array = getData.split("&");
		for(i=0;i<data_array.length;i++){
			var kdv = data_array[i].split("=")
			if( "mod" == kdv[0] ){
				mod = kdv[1];
			}
			if( "act" == kdv[0] ){
				act = kdv[1];
			}
		}

		createXMLHttpRequest();
		var random= new Date().getTime();
		document.getElementById(div_id).innerHTML = "<img src=\"files/loading.gif\" /> Loading... <p></p><p></p><p></p> <hr /><a href=\"avascript:void(0);\" onclick=\"cpstyle('1','cpbox','hidden','0');return false;\">Close</a>";
		xmlHttp.open("GET",phpfile+".php?"+getData,true);
		xmlHttp.onreadystatechange = callback;
		xmlHttp.send(null);
	}


	function callback(){
		if (xmlHttp.readyState == 4) { 
				if (xmlHttp.status == 200) {
					if(phpfile == "xml"){
						eval(mod+"()");
					}else{
						document.getElementById(divid).innerHTML = xmlHttp.responseText;
					}
				} else {
					document.getElementById(divid).innerHTML = MessageText("error", "page not found", "Warning");
				}
		}
	}
	
function cpstyle( bid,targetId,action,page,type ){
  if (document.getElementById){
  		target = document.getElementById( targetId );
  			if (action == "show"){
		output('style_list','bid='+bid+'&pageid='+page+'&type='+type,'cpbox');
  			} else {
  				target.style.display = "none";
  			}
  	}
}

function cpframe( bid,targetId,action){
  if (document.getElementById){
  		target = document.getElementById( targetId );
  			if (action == "show"){
  				target.style.display = "";
				output('frame_list','bid='+bid,'cpbox');
  			} else {
  				target.style.display = "none";
  			}
  	}
}

function cpmanage( bid,targetId,action){
  if (document.getElementById){
  		target = document.getElementById( targetId );
  			if (action == "show"){
  				target.style.display = "";
				output('manage_cp','bid='+bid,'cpbox');
  			} else {
  				target.style.display = "none";
  			}
  	}
}

function replyform(divid,action,bid) {
	if (action == "show"){
		next_b = document.getElementById('js_cancel').innerHTML;
  		document.getElementById(divid).innerHTML = "<form action=\"reply_comment.php\" method=\"post\"><textarea name=\"reply\" cols=\"45\" rows=\"3\"></textarea><input type=\"hidden\" name=\"bid\" value=\""+bid+"\" /><input type=\"hidden\" name=\"action\" value=\"reply_comment\" /><input type=\"hidden\" name=\"c_id\" value=\""+divid+"\" /><br /><input type=\"submit\" value=\"Submit\" /> <a href=\"#\"  onclick=\"replyform("+divid+",'hide',1);return false;\">"+next_b+"</a> </form>";
  	} else {
		next_b = document.getElementById('js_reply').innerHTML;
  		document.getElementById(divid).innerHTML = "";
  	}
}

function preview_css(css_id,css_p) {
	var css_set = readCookie('css_ids');
	id_arr = css_set.split("|");
	if (css_p == "css_wallpaper")
	{
		document.getElementById("css_wallpaper").href = "styles/wallpaper/" + css_id + "/css.css";
		id_arr[0] = css_id;
	}
	if (css_p =="css_general") {
		document.getElementById("css_general").href = "styles/general/" + css_id + "/css.css";
		id_arr[1] = css_id;
	}
	if (css_p =="css_bheader") {
		document.getElementById("css_bheader").href = "styles/bheader/" + css_id + "/css.css";
		id_arr[2] = css_id;
	}
	if (css_p =="css_menubar") {
		document.getElementById("css_menubar").href = "styles/menubar/" + css_id + "/css.css";
		id_arr[4] = css_id;
	}
	if (css_p =="css_header") {
		document.getElementById("css_header").href = "styles/header/" + css_id + "/css.css";
		id_arr[3] = css_id;
	}
	document.getElementById("save_button").disabled = false;
	css_ids = id_arr.join("|");
	createCookie('css_ids',css_ids,1);
}

function setup_default_css(css_ids) {
	createCookie('css_ids',css_ids,1);
}

function css_save(space_home) {
	var css_set = readCookie('css_ids');
	window.location = space_home+"/index.php?p=editprofile/themepost/" + css_set;
}

function css_website_save(space_home) {
	var css_set = readCookie('css_ids');
	window.location = space_home+"/index.php?p=admintheme/themepost/" + css_set;
}

function css_cancel(bid) {
	window.location = "space.php?bid="+bid+"&action=change_style";
	
}
createCookie('css_ids','',-1);



function Marquee(){
  this.ID=document.getElementById(arguments[0]);
  this.Direction=arguments[1];
  this.Step=arguments[2];
  this.Width=arguments[3];
  this.Height=arguments[4];
  this.Timer=arguments[5];
  this.WaitTime=arguments[6];
  this.StopTime=arguments[7];
  if(arguments[8]){this.ScrollStep=arguments[8];}else{this.ScrollStep=this.Direction>1?this.Width:this.Height;}
  this.CTL=this.StartID=this.Stop=this.MouseOver=0;
  this.ID.style.overflowX=this.ID.style.overflowY="hidden";
  this.ID.noWrap=true;
  this.ID.style.width=this.Width;
  this.ID.style.height=this.Height;
  this.ClientScroll=this.Direction>1?this.ID.scrollWidth:this.ID.scrollHeight;
  this.ID.innerHTML+=this.ID.innerHTML;
  this.Start(this,this.Timer,this.WaitTime,this.StopTime);
  }
Marquee.prototype.Start=function(msobj,timer,waittime,stoptime){
  msobj.StartID=function(){msobj.Scroll();}
  msobj.Continue=function(){
    if(msobj.MouseOver==1){setTimeout(msobj.Continue,waittime);}
    else{clearInterval(msobj.TimerID); msobj.CTL=msobj.Stop=0; msobj.TimerID=setInterval(msobj.StartID,timer);}
    }
  msobj.Pause=function(){msobj.Stop=1; clearInterval(msobj.TimerID); setTimeout(msobj.Continue,waittime);}
  msobj.Begin=function(){
    msobj.TimerID=setInterval(msobj.StartID,timer);
    msobj.ID.onmouseover=function(){msobj.MouseOver=1; clearInterval(msobj.TimerID);}
    msobj.ID.onmouseout=function(){msobj.MouseOver=0; if(msobj.Stop==0){clearInterval(msobj.TimerID); msobj.TimerID=setInterval(msobj.StartID,timer);}}
    }
  setTimeout(msobj.Begin,stoptime);
  }
Marquee.prototype.Scroll=function(){
  switch(this.Direction){
    case 0:
      this.CTL+=this.Step;
      if(this.CTL>=this.ScrollStep&&this.WaitTime>0){this.ID.scrollTop+=this.ScrollStep+this.Step-this.CTL; this.Pause(); return;}
      else{if(this.ID.scrollTop>=this.ClientScroll) this.ID.scrollTop-=this.ClientScroll; this.ID.scrollTop+=this.Step;}
      break;
    case 1:
      this.CTL+=this.Step;
      if(this.CTL>=this.ScrollStep&&this.WaitTime>0){this.ID.scrollTop-=this.ScrollStep+this.Step-this.CTL; this.Pause(); return;}
      else{if(this.ID.scrollTop<=0) this.ID.scrollTop+=this.ClientScroll; this.ID.scrollTop-=this.Step;}
      break;
    case 2:
      this.CTL+=this.Step;
      if(this.CTL>=this.ScrollStep&&this.WaitTime>0){this.ID.scrollLeft+=this.ScrollStep+this.Step-this.CTL; this.Pause(); return;}
      else{if(this.ID.scrollLeft>=this.ClientScroll) this.ID.scrollLeft-=this.ClientScroll; this.ID.scrollLeft+=this.Step;}
      break;
    case 3:
      this.CTL+=this.Step;
      if(this.CTL>=this.ScrollStep&&this.WaitTime>0){this.ID.scrollLeft-=this.ScrollStep+this.Step-this.CTL; this.Pause(); return;}
      else{if(this.ID.scrollLeft<=0) this.ID.scrollLeft+=this.ClientScroll; this.ID.scrollLeft-=this.Step;}
      break;
    }
  }