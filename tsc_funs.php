<?php
//Functions included in this file

//function to establish a connection to MySQL and selecting a database to work
function dbconnect()
{
	global $siteadds,$dbhost,$dbname,$dbuser,$dbpwd;
	if($link = mysql_connect($dbhost,$dbuser,$dbpwd))
	{
		$res=mysql_select_db($dbname) or die(mysql_error());
		if($res)
			return $link;
	}
	else
		print "There is some internal error in retrieving the records. Sorry for the inconvinence.";
}
//function  to close the opened link
function dbclose($link)
{
	global $link;
	if(mysql_close($link))
	{}
}


//Show main page
function showMain()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $baseurl;
	$pagekeywords = "create store, online store, build store, storefront";
  $pagedesc = "Create your own online store for free in minutes. Accept credit cards, web-based interface, PayPal shopping cart and more";
  $pagetitle = "Create your own online store for free in minutes.";

  $pagecontent .= <<<HTM
<table align="center" width="100%" cellpadding="3" cellspacing="0" border="0">
	<tr>
		<td align="center" valign="top">
			<p align="center"><b><font size="5">Your Own FREE Online Store in minutes!!</font></b></p>
			<p align="center"><b><font size="3" color="#008080">Why create a simple website when you can create a storefront?</font></b></font></p>
			<table align="center">
				<tr>
  				<td valign="top" width="450" bgcolor="#ffffff" align="center">
  					<p><b><font size="2">Create  online store!</font></b></p>
						<table summary="">
							<tr>
								<td width="50%" align="left" valign="top">
									<p><img src="images/arrow2.gif" alt=""><font size="2" color="#800000"><b>Accept Credit Cards!</b></font></p>
       						<p><img src="images/arrow2.gif" alt=""><font size="2" color="#800000"><b>Quick and easy setup!</b></font></p>
       						<p><img src="images/arrow2.gif" alt=""><font size="2" color="#800000"><b>Quality Visitors!</b></font></p>
       						<p><img src="images/arrow2.gif" alt=""><font size="2" color="#800000"><b>Web-based interface!</b></font></p>
								</td>
								<td align="left" valign="top">
									<p><img src="images/arrow2.gif" alt=""><font size="2" color="#800000"><b>PayPal shopping cart!</b></font></p>
       						<p><img src="images/arrow2.gif" alt=""><font size="2" color="#800000"><b>No monthly fees!</b></font></p>
       						<p><img src="images/arrow2.gif" alt=""><font size="2" color="#800000"><b>No setup fees!</b></font></p>
       						<p><img src="images/arrow2.gif" alt=""><font size="2" color="#800000"><b>No programming needed!</b></font></p>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<p align="center"><font size="3"><b>Everyone is making profits online, don't be left behind! Start selling in style...</b></font></p>
		</td>
	</tr>
</table>

HTM;



}


function register()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent;
	$pagekeywords = "";
	$pagedesc = "Register to create your own online store";
	$pagetitle = "Register for free to create your online store";
	$pagecontent .= <<<HTM
		<p align="center"><font face="arial"><font size="2"><b>Create Your Store Admin Account</b></font></font></p>
		<form action="index.php?a=register1" method="post">
			<p align="center"><font face="arial" size="2">Enter Contact Email: <input type="text" name="ce" size="50" maxlength="100"><br><font size="1">(This is the email you usually access. We will send confirmation link to this email and all future communications.)</p>
			<p align="center"><font face="arial" size="2">Enter PayPal Email: <input type="text" name="pe" size="50" maxlength="100"><br><font size="1">(You must have a <a href="https://www.paypal.com/in/mrb/pal=6LVNWMEXQ3SBN" target="_blank">valid PayPal account</a> in order to use this service.)</p>
			<p align="center"><font face="arial" size="2">Select Password: <input type="password" name="pa" size="20" maxlength="15"></p>
			<p><input type="checkbox" name="tosflag"/> - I accept the terms of service.</p>
			<p align="center"><input type="submit" value="Create my store admin account ..."></p>
		</font>
HTM;


}

function register1($uce, $upe, $upa, $utosflag)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $baseurl, $noreply_mail;
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = "thank you for your interest";
	$pagecontent = "";
	if($uce == "" or $upe == "" or $upa == "" or $utosflag == "")
	{
		$pagecontent .= "<p><b>ERROR!</b> All fields are mandatory. You must accept the terms of service.</p>";
		return;
	}
	//Is valid email entered as contact email?
	$emlresult=ereg("^[^@ ]+@[^@ ]+\.[^@ ]+$",$uce,$trashed);
	if(!$emlresult)
	{
		$pagecontent .= "<p><b>ERROR!</b> Contact email is not valid.</p>";
		return;
	}
	//Is valid email entered as PayPal email?
	$emlresult=ereg("^[^@ ]+@[^@ ]+\.[^@ ]+$",$upe,$trashed);
	if(!$emlresult)
	{
		$pagecontent .= "<p><b>ERROR!</b> PayPal email is not valid.</p>";
		return;
	}
	
	//Password cannot have space
	if(strstr($upa, " "))
	{
		$pagecontent .= "<p><b>ERROR!</b> Password cannot have spaces.</p>";
		return;
	}
	//Password length after removing spaces
	
	if(strlen($upa) < 6)
	{
		$pagecontent .= "<p><b>ERROR!</b> Password must be atleast 6 characters.</p>";
		return;
	}


	$cbcode=rand(0,9);
	$cbcode.=rand(0,9);
	$cbcode.=rand(0,9);
	$cbcode.=rand(0,9);
	$cbcode.=rand(0,9);
	$cbcode.=rand(0,9);
	$cbcode.=rand(0,9);
	$cbcode.=rand(0,9);
	$cbcode.=rand(0,9);
	$cbcode.=rand(0,9);

	$q="INSERT INTO store_info_tab SET password = '$upa', contact_email = '$uce', paypal_email = '$upe', conf_code = '$cbcode'";
	if($r=mysql_query($q))
	{
		//Now send the mail with activation code
		$msgsub = $sitename . " - confirmation link for - " . $uce;
		$confurl = $baseurl . "index.php?a=confirm&mid=".mysql_insert_id()."&cbcode=".$cbcode;
		$msg = <<<TXT
THIS IS NOT SPAM
----------------
This is your confirmation link from $sitename

$confurl

If you don't find the link clickable, just copy and paste it in the browser window.

Thanks for your interest and welcome aboard!

Admin
$sitename
$baseurl
-----------------

$serviceannouncements

TXT;

		if(send_mail_plain_new($uce,$noreply_mail,$noreply_mail,$msgsub,$msg))
		{
			$pagecontent .= "<p>Confirmation link is sent to <i>$uce</i>. Your account will be active as soon as you click the confirmation link in your email. Thank you for your interest and welcome aboard!</p>";
		}
		else
		{
			$pagecontent .= "<p>Error sending confirmation link. Try again.</p>";
		}
	}
	else
	{
		$pagecontent .= mysql_error();
	}

}

//Send mail
function send_mail_plain_new($mailid,$fromid,$replyto,$sub,$msg)
{
	global $def_eml_ad, $email_adcode;
	//print "$mailid,$fromid,$sub,$msg";
  $headers  = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
  /* additional headers */
  $headers .= "From: $fromid\r\n";
  $headers .= "Reply-To: $replyto\r\n";
	$msg="$msg".$def_eml_ad;
	//this will send mail to each person individually.
	$mailid=split(",",$mailid);
  //Add email ad
	$msg = $msg . $email_adcode;

	for($i=0;$i<count($mailid);$i++)
  {
  	//echo"Message : $msg";
    if(mail($mailid[$i], $sub, $msg,$headers))
  	{
  	}
  	else
  	{
  		print "<br>Error sending email to $mailid[$i]";
  		exit;
  	}
 	}
  return 1;
}

function confirm($midvalue, $codevalue)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $baseurl, $noreply_mail;
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = "confirm account - thank you for your interest";
	$pagecontent .= "";
	$q="SELECT * FROM store_info_tab WHERE mem_id = '$midvalue' LIMIT 1";
	if($r=mysql_query($q))
	{
		$cbuserstempdata = mysql_fetch_array($r);
		$codev = $cbuserstempdata['conf_code'];
		$usereml = $cbuserstempdata['contact_email'];
		$ustatus = $cbuserstempdata['mem_status'];
		$upass = $cbuserstempdata['password'];
		
		if($ustatus != "pending")
		{
			$pagecontent .= "<p><b>Error!</b> User account is already confirmed or is frozen by the site admin or has been suspended. Please contact site admin if you think there is an error.</p>";
			return;
		}
		
		if($codev == $codevalue)
		{
			$q1="UPDATE store_info_tab SET mem_status = 'regular' WHERE mem_id = $midvalue";
			if(mysql_query($q1))
			{
				$pagecontent .= "<p><b>Success!</b> User verified successfully. You can login to your account using the email and password you have used. Welcome to the team!</p>";
     		//Now send the mail with password
    		$msgsub = $sitename . " - site credentials for - " . $usereml;

     		$msg = <<<TXT
THIS IS NOT SPAM
----------------
This is your site credentials for $sitename

Your Username - $usereml
Your Password - $upass

Log in here - $baseurl

Thanks for your interest and welcome aboard!

Admin
$sitename
$baseurl

TXT;

     		if(send_mail_plain_new($usereml,$noreply_mail,$noreply_mail,$msgsub,$msg))
      	{
      		$pagecontent .= "<p>A confirmation mail with login details is also sent to - <i>$usereml</i>.</p>";
      	}
      	else
      	{
      		$pagecontent .= "<p>Error sending password to your email. Please notify site admin about this problem.</p>";
      	}
			}
			else
			{
				$pagecontent .= mysql_error();
			}
		}
	}
	else
	{
		$pagecontent .= mysql_error();
	}
}


//Login
function login()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $baseurl, $noreply_mail;
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = "login to member area";
	$pagecontent .= <<<HTM
		<p><b>Login to your Store Admin Account</b></p>
		<form action="index.php?a=login1" method="post">
			<p>Username / Email - <input type="text" name="uemail" size="50" maxlength="100"></p>
			<p>Password - <input type="password" name="upass" size="20" maxlength="15"></p>
			<p><input type="submit" value="Login"></p>
		</form>
		<p><a href="index.php?a=retpass">Forgot password? Retrieve it here.</a></p>
HTM;

}

function login1($pu, $pp)
{
	global $pagecontent;
	$q="SELECT * FROM store_info_tab WHERE contact_email = '$pu'";
	if($r=mysql_query($q))
	{
		$cbudata = mysql_fetch_array($r);
		$cbstatus = $cbudata['mem_status'];
		$cbpass = $cbudata['password'];
		$uid = $cbudata['mem_id'];
		
		if($cbstatus != "regular")
		{
			$pagecontent .= "<p>ERROR! User account is not active.";
			return;
		}
		if($pp == $cbpass)
		{
			$_SESSION['uname'] = $pu;
 			$_SESSION['userid'] = $uid;
			
			header("Location: index.php?a=mem");
		}
		else
		{
			$pagecontent .= "<p>ERROR! Username and/or password incorrect.";
		}
	}
	else
	{
		$pagecontent .= mysql_error();
	}
}

function mem()
{
	global $pagecontent;
	$pagecontent .= getMemMenu();
}

function check_login()
{
	$flag = false;
	if(isset($_SESSION['uname']))
	{
		$flag = true;
	}
	return $flag;
}

function getMemMenu()
{
	global $baseurl;

	$uname = $_SESSION['uname'];
	$usid = $_SESSION['userid'];
	//Read the domain name
	$q="SELECT storedomain FROM store_info_tab WHERE mem_id = $usid";
	if($r=mysql_query($q))
	{
		$ud = mysql_fetch_array($r);
	}
	if($ud[0] != "")
	{
		$storeurl = "http://www." . $ud[0] . "/";
	}
	else
	{
		$storeurl = $baseurl . "store.php?mem_id=$usid";
	}
	
	$codetoreturn .= <<<HTM
		<p align="center"><b>Hello $uname</b> (<a href="index.php?a=logoff">Logoff</a> - <a href="index.php?a=changepwd">Change Password</a>)</p>
		<table border="0" cellpadding="10" cellspacing="0" summary="" bgcolor="#ccff99" align="center">
			<tr>
				<td>
					<b>Your store URL - <a href="$storeurl" target="_blank">$storeurl</a></b>
				</td>
			</tr>
		</table>
		<p align="center">
		
		<table border="1" cellpadding="2" cellspacing="0" align="center">
			<tr>
				<td align="center">
					<b>Store setup menu</b>
				</td>
			</tr>			
			<tr>
				<td align="center">
					<a href="index.php?a=chooseTemplate">Choose store template</a>
				</td>
			</tr>
			<tr>

				<td align="center">
					<a href="index.php?a=showBasic">Maintain basic information</a>
				</td>
			</tr>
			<tr>
				<td align="center">
					<a href="index.php?a=showPay">Maintain payment processing information</a>
				</td>
			</tr>
			<tr>
				<td align="center">
					<a href="index.php?a=showInv">Maintain store inventory (add/remove items)</a>
				</td>
			</tr>
			<tr>
				<td align="center">
					<a href="index.php?a=addThankyou">Add thank you note</a>
				</td>
			</tr>
		</table>

HTM;

	return $codetoreturn;
}
//Functions included in this file
function retpass()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename;
	$pagekeywords = "retrieve password";
	$pagedesc = "Retrieve password";
	$pagetitle = "Retrieve password";
	$pagecontent .= <<<HTM
		<p><b>Retrieve Password</b></p>
		<form action="index.php?a=retpass1" method="post">
			<p>Enter email you have used to signup with us: <input type="text" name="ueml" size=50 maxlength="100"></p>
			<p><input type="submit" value="Retrieve password"></p>
		</form>
HTM;

}

function retpass1($puemail)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $baseurl, $noreply_mail;
	$pagekeywords = "retrieve password";
	$pagedesc = "Retrieve password";
	$pagetitle = "Retrieve password";
	if($puemail == "")
	{
		$pagecontent .= "<br> Email must be entered.";
	}
	else
	{
		$q="SELECT password, mem_status FROM store_info_tab WHERE contact_email = '$puemail'";
		if($r=mysql_query($q))
		{
			$passdata = mysql_fetch_array($r);
			if($passdata[1] == "regular")
			{
				$msg = <<<TXT
THIS IS NOT SPAM
----------------

Your password for $sitename is $passdata[0]
Your username is - $puemail

Login here - $baseurl

Thanks
Admin
$baseurl
TXT;

				if(send_mail_plain_new($puemail, $noreply_mail, $noreply_mail, $sitename . " - Password retrieval mail - " . $puemail, $msg))
				{
					$pagecontent .= "<br> Email sent with password to <i>$puemail</i>";
				}
				else
				{
					$pagecontent .= "<br> Error sending mail.";
				}
			}
			else
			{
				$pagecontent .= "<br> This account is not active. Password cannot be retrieved.";
			}
		}
		else
		{
			$pagecontent .= "<br> Error reading the password. ".mysql_error();
		}
	}

}


function changepwd()
{
	global $pagecontent;
	$pagecontent .= "";
	$uname = $_SESSION['uname'];
	$pagecontent .= getMemMenu();
	$pagecontent .= <<<HTM
		<p><font size="3"><b>Change Password</b></font></p>
		<form action="index.php?a=changepwd1" method="post">
			<p>Old password: <input type="password" name="opwd" size="15"></p>
			<p>New password: <input type="password" name="npwd" size="15"></p>
			<p>New password confirm: <input type="password" name="npwd1" size="15"></p>
			<p><input type="submit" value="Change password"></p>
		</form>

HTM;

}

function changepwd1($pupwdold, $pupwdnew, $pupwdnew1)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename;
	$pagekeywords = "members area, changing password";
	$pagedesc = "changing password";
	$pagetitle = "changing password";
	$uname = $_SESSION['uname'];
	$pagecontent .= getMemMenu();

	if($pupwdold == "" or $pupwdnew == "" or $pupwdnew1 == "")
	{
		$pagecontent .= "<br> Some or all the entries missing. Try again.";
	}
	else
	{
		if($pupwdnew != $pupwdnew1)
		{
			$pagecontent .= "<br> New passwords are not matching. Try again.";
		}
		else
		{
			//Select the current password of the user
			$q="SELECT password FROM store_info_tab WHERE contact_email = '$uname'";
			if($r=mysql_query($q))
			{
				$userdata = mysql_fetch_array($r);
				if($userdata[0] != $pupwdold)
				{
					$pagecontent .= "<br>Old password is not matching. Try again.";
				}
				else
				{
					$q1="UPDATE store_info_tab SET password = '$pupwdnew' WHERE contact_email = '$uname'";
					if($r1=mysql_query($q1))
					{
						$pagecontent .= "<br> Password chage successful!";
					}
					else
					{
						$pagecontent .= "<br> Error updating user password. ".mysql_error();
					}
				}
			}
			else
			{
				$pagecontent .= "<br> Error in reading the user data. ".mysql_error();
			}
		}
	}

}

function chooseTemplate()
{
  global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " choose store template";
	$uname = $_SESSION['uname'];
	$pagecontent .= getMemMenu();	
	
	$dir = opendir("$site_templates_path");
	while($entry=readdir($dir)) 
	{
    if($entry == "." || $entry == "..") 
		{ 
        continue; 
		}
		$templates_list = $templates_list . "<a href=$site_templates_url/$entry target=_blank>$entry</a><br>";
		$entrynumber = str_replace(".html", "", $entry);
		$templates_dropdown .= <<<HTM
			<option value="$entrynumber">Template # $entrynumber</option>
HTM;

  }


	$pagecontent .= <<<HTM
		<p align="center"><b>Choose the template</b></p>		
		<p align="center">(Following list shows available templates. Please select the one that you want to use and enter the number in the box below.)</p>
		<p align="center">$templates_list</p>
		<form action="index.php?a=saveTemplate" method="post">
			<p align="center">Select the template from the dropdown: <select name="template">$templates_dropdown</select></p>
			<p align="center"><input type="submit" value="Save" /></p>
		</form>
HTM;

}

function saveTemplate($template)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " choose store template";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	if($template == "")
	{
		$pagecontent .= "<p><b>Error!</b> Template number cannot be blank. Try again. ";
		return;
	}
	$tempFile=$site_templates_path . "/" . $template . ".html"; 
	$query1="SELECT * FROM store_info_tab WHERE mem_id = $uid";
	if($res1=mysql_query($query1))
	{
		if(file_exists($tempFile))
		{
		
		}
		else
		{
			$pagecontent .= "<p><b>Error!</b> Invalid tempalte number. Try again.";
			return;
		}
		$storeInfoData=mysql_fetch_array($res1);
		$query2="UPDATE store_info_tab SET template='$template' WHERE mem_id = $uid"; 
		if(mysql_query($query2))
		{
			$pagecontent .= "<p><b>Success!</b> Template set successfully to <i>$template</i> for <i>$uname</i>.";
		}
		else
		{
			$pagecontent .= "<p><b>Error!</b>  " . mysql_error();
		}
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b>  Could not read the store data. " . mysql_error();
	}
}

function showBasic()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " choose store template";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	if ($res=mysql_query("SELECT * FROM store_info_tab WHERE mem_id = $uid"))
	{
		$data=mysql_fetch_array($res);		
	}
	$st = $data['store_title'];
	$sd = $data['short_desc'];
	$sc = $data['contact'];
	$sa = $data['about'];
	$sw = $data['welcome_note'];
	
	$pagecontent .= <<<HTM
		<p align="center">
			<b>Enter basic information about your store</b>
		</p>
		<form action="index.php?a=saveBasic" method="post">
			<p>Title: <input type="text" name="storetitle" value="$st" size="40"><br>(This is the title shown in bold letters at the top of your store. Use 2-3 words.)</p>
			<p>Short description: <input type="text" name="shortdesc" value="$sd" cols="50" rows="5"><br>(Describe your store in 1-2 sentences.)</p>
			<p>Contact information:<br>(This will be shown in your store under contact link. Include as much contact information as you want such as 1-800 number, email, address, etc. Basic HTML Tags &lt;p&gt; , &lt;br&gt; and &lt;b&gt; are allowed.)<br> <textarea rows="5" cols="50" name="contact">$sc</textarea></p>
			<p>About your business:<br>(Explain your business/store to your customers. This will be shown under about link in your store. Basic HTML Tags &lt;p&gt; , &lt;br&gt; and &lt;b&gt; are allowed.)<br> <textarea rows="3" cols="50" name="about">$sa</textarea></p>
			<p>Welcome message:<br>(This will be shown on the main page of your store. Simple 2-3 paragraphs with attractive message are expected. Basic HTML Tags &lt;p&gt; , &lt;br&gt; and &lt;b&gt; are allowed.)<br> <textarea rows="3" cols="50" name="welcome">$sw</textarea></p>
			
			<p align="center"><input type="submit" name="b1" value="Save"></p>
		</form>
HTM;
}

function saveBasic($st, $sd, $co, $ab, $we)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	
	$st = strip_tags($st);
	$sd = strip_tags($sd);
	$co = strip_tags($co, '<br><p><b>');
	$ab = strip_tags($ab, '<br><p><b>');
	$we = strip_tags($we, '<br><p><b>');
	
	$st = str_replace("'", "", $st);
	$sd = str_replace("'", "", $sd);
	$co = str_replace("'", "", $co);
	$ab = str_replace("'", "", $ab);
	$we = str_replace("'", "", $we);
	
	$query2= <<<SQL
			INSERT INTO store_info_tab SET mem_id = $uid, store_title='$st',short_desc='$sd',contact='$co', about='$ab', welcome_note='$we' ON DUPLICATE KEY UPDATE store_title='$st', short_desc='$sd', contact='$co',  about='$ab', welcome_note='$we'
SQL;
		
	if(mysql_query ($query2))
	{
		$pagecontent .= "<p><b>Success!</b> Information updates successfully";
	}
	else
	{
	 	$pagecontent .= "<p><b>Error!</b> " . mysql_error();
	}
}

function showPay()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	
	if ($res=mysql_query("SELECT * FROM store_info_tab WHERE mem_id = $uid"))
	{
		$data=mysql_fetch_array($res);		
	}
	$sc = $data['store_currency'];
	$pa = $data['paypal_email'];
	
	$pagecontent .= <<<HTM
		<p align="center"><b>Maintain payment processing information for your store</b></p>
		<form action="index.php?a=savePay" method="post">
			<p>Store currency: <input type="text" name="storecurr" value="$sc" size="10"><br><font color="#ff0000">(Copy currency symbol exactly from PayPal site otherwise shopping cart will not work properly! Ex. - USD for US dollars, EURO for Euroes, etc.)</font></p>
			<p>Paypal email: <input type="text" name="paypalEmail" value="$pa" size="40"></p>
			<p align="center"><input type="submit" name="b2" value="Save"></p>
		</form>		
HTM;

}

function savePay($sc, $pa)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	$query2= "INSERT INTO store_info_tab (mem_id, store_currency, paypal_email)	VALUES ($uid, '$sc', '$pa')	ON DUPLICATE KEY UPDATE store_currency = '$sc', paypal_email = '$pa'";
	
	if(mysql_query ($query2))
	{
		$pagecontent .= "<p><b>Success!</b> Information updates successfully";
	}
	else
	{
	 	$pagecontent .= "<p><b>Error!</b> " . mysql_error();
	}
}

function showInv()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	$query1="SELECT * FROM store_cats_tab WHERE mem_id = $uid";
	if($res = mysql_query ($query1))
	{
		while($res1 = mysql_fetch_array($res))
		{
			$dropdown= $dropdown . "<option value='$res1[1]'>$res1[1]</option>";
		}
	}
	
	$query2="SELECT * FROM store_items_tab WHERE mem_id = $uid";
	if($res2 = mysql_query($query2))
	{
		while($res3=mysql_fetch_array($res2))
		{
			$dropdown2=$dropdown2 . "<option value='$res3[3]'>$res3[4]</option>";
		}
	}
	$pagecontent .= <<<HTM
		<p>&nbsp;</p>
		<table border="1" cellpadding="2" cellspacing="0" align="center">
			<tr>
				<td align="center" colspan="2">
					<b>Inventory management menu</b>
				</td>
			</tr>			
			<tr>
				<td align="center" valign="top">
					<p><b>Categories management</b></p>
					<a href="index.php?a=addCat">Add new category</a>
					<form action="index.php?a=delCat" method="post">
						Select category: 
						<select name="storecat2" size="1">$dropdown</select>
						<input type="submit" value="Delete Category">
					</form>
				</td>
				<td align="center" valign="top">
					<p><b>Items management</b></p>
					<form action="index.php?a=addItem" method="post">
						Select category: 
						<select name="storecat2" size="1">$dropdown</select>
						<input type="submit" value="Add new item">
					</form>
					<form action="index.php?a=editItem" method="post">
						Select item: 
						<select name="storeitem1" size="1">$dropdown2</select>
						<input type="submit" value="Edit Item">
					</form>
					<form action="index.php?a=delItem" method="post">
						Select item: 
						<select name="itemID1" size="1">$dropdown2</select>
						<input type="submit" value="Delete item">
					</form>
				</td>
			</tr>
		</table>
HTM;

}

function addCat()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	
	$pagecontent .= getMemMenu();	
	$pagecontent .= <<<HTM
		<p align="center"><b>Add new category</b></p>
		<form action="index.php?a=saveCat" method="post">
			<p>Category name: <input type="text" name="catname" value="" size="20"/><br>(Enter any name upto 50 characters.)</p>
			<p align="center"><input type="submit" name="b3" value="Save"></p>
		</form>
HTM;


}

function saveCat($ca)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	if($ca == "")
	{
  	$pagecontent .= "<p><b>Error!</b> Category name cannot be blank.";
		return;
	}
	//Change spaces to SPACE
	$ca = str_replace(" ", "SPACE", $ca);
	//Check if the category has any non-alphanumeric characters
	if(ctype_alnum($ca))
	{
		//Ok to proceed
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b> Category name cannot contain any characters other than alphabets and numbers.";
		return;
	}
	//Change SPACE back to space
	$ca = str_replace("SPACE", " ", $ca);
	// Check if the member information already exists
	$query1 = "SELECT * FROM store_cats_tab WHERE mem_id = $uid AND category_name = '$ca'";
	if($res = mysql_query ($query1))
  {
		$res1 = mysql_fetch_array($res);
		if ($res1[1] == $ca)
		{
			$cat_exists = true;
		}
		else
		{
			$cat_exists = false;
		}
	}
	if ($cat_exists)
	{
		$pagecontent .= "<p><b>Error!</b> Category exists.";
		return;
	}
	else
	{
		$query2= <<<SQL
			insert into store_cats_tab (mem_id,category_name)
			values ($uid,'$ca')
SQL;
	}
	if(mysql_query ($query2))
	{
		$pagecontent .= "<p><b>Success!</b> Category added.";
	}
	else
	{
	 	 $pagecontent .= "<p><b>Error!</b> " . mysql_error();
	}
}

function delCat($ca)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	$pagecontent .= <<<HTM
		<p align="center"><b>Confirm action</b></p>
		<p>Are you sure you want to delete category <u>$ca</u> and all its items? <b>ALL ITEMS FROM THIS CATEGORY WILL BE DELETED!!</b></p>
		<form action="index.php?a=delCat1" method="post">
			<input type="hidden" name="storecat3" value="$ca">
			<input type="submit" value="Yes - delete '$ca'">
		</form>
HTM;

}

function delCat1($ca)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	$query1="DELETE FROM store_cats_tab WHERE mem_id = $uid AND category_name = '$ca' LIMIT 1";
	$query2="DELETE FROM store_items_tab WHERE mem_id = $uid AND category_name = '$ca'";
	if (mysql_query($query2))
	{
		if (mysql_query($query1))
		{
			$pagecontent .= "<p><b>Success!</b> Category deleted successfully</p>";
		}
		else
		{
			$pagecontent .= "<p><b>Error!</b> " . mysql_error();
		}
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b>  " . mysql_error();
	}	
}



function checkInvLimit($ppuname)
{
	global $pagecontent;
	$query1="SELECT inv_limit FROM store_info_tab WHERE mem_id = $ppuname";
	if($res1=mysql_query($query1))
	{
		$inv_limit=mysql_fetch_array($res1);		
	}
	$query2="SELECT count(*) FROM store_items_tab WHERE mem_id = $ppuname";
	if($res2=mysql_query($query2))
	{
		$total_items=mysql_fetch_array($res2);		
	}
	if($inv_limit[0]<=$total_items[0])
	{
		$pagecontent .= "<p align='center'><b>Inventory Limit Reached</b></p><p>Your current settings allow you to add upto $inv_limit[0] item(s) in your store at this stage. This restriction is applied because of the web-space limitations and to avoid abuse of our service. If you want to add more items please become Premier Seller. Premier Sellers can add upto 999 items.";
		return false;
	}
	else
	{
		return true;
	}
}

function isValidURL($url) 
{ 
 return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url); 
} 

function addItem($storecat2, $itmid)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();
	
	//If item id is present, read the item details
	if($itmid != "")
	{
		$query1000="SELECT * FROM store_items_tab WHERE mem_id = $uid AND item_id = '$itmid'";
  	if($res1000=mysql_query($query1000))
  	{
  		$itemData1000=mysql_fetch_array($res1000);
			$storecat2 = $itemData1000['category_name'];
			$itemIDDisabled = " disabled = 'disabled' ";
			$itemIDHidden = " <input type='hidden' name='itemID' value='$itmid'>";
			$itemID1000 = $itemData1000['item_id'];
			$itemTitle1000 = $itemData1000['item_title'];
			$itemQty1000 = $itemData1000['item_qty'];
			$itemPrice1000 = $itemData1000['item_price'];
			$itemShip1000 = $itemData1000['item_shipping'];
			$itemImage1000 = $itemData1000['item_image'];
			$itemDet1000 = $itemData1000['item_details'];
			$itemDWLD1000 = $itemData1000['item_dwld_url'];
			$itemType1000 = $itemData1000['item_category'];
			
  	}		
		
  	if($itemType1000 == "Physical goods")
  	{
  		$physSelected=" selected = 'selected'";
			$digSelected="";
  	}
  	else
  	{
			$physSelected="";
  		$digSelected=" selected = 'selected'";
  	}
	}
	else
	{
  	if(checkInvLimit($uid))
  	{
  	}
  	else
  	{
  		return;
  	}	
	}
	if($storecat2)
	{
  				
  	$pagecontent .= <<<HTM
  		<p align="center"><b>Add/Edit Item in <u>$storecat2</u></b><br>(* indicates mandatory fields.)</p>
  		<form action="index.php?a=saveItem" method="post">
    		<input type="hidden" name="itemCat" value="$storecat2">
  			$itemIDHidden
				<p>Item ID*: <input type="text" name="itemID" size="10" maxlength="20" $itemIDDisabled value="$itemID1000">(Give any alphanumerical ID.)</p>

    		<p>Item title*: <input type="text" name="itemTitle" size="40" value="$itemTitle1000"><br>(Enter title of the item.)</p>
    		<p>Image URL: <input type="text" name="imageURL" size="40" value="$itemImage1000" maxlength="200"><br>(Enter a complete URL of the image for this item starting with http://)</p>
    		<p>Quantity on sale*: <input type="text" name="itemQty" size="15" value="$itemQty1000" maxlength="11"><br>(Must be an integer value or shopping cart will not work correctly.)</p>
    		<p>Price in store currency*: <input type="text" name="itemPrice" size="15" value="$itemPrice1000" maxlength="11"><br>(Must be a numerical value with appropriate decimal points or the shopping cart will not work correctly.)</p>
  			<p>Shipping details: <input type="text" name="itemShip" size="50" value="$itemShip1000" maxlength="100"><br>(Use this field to list shipping policy. Actual shipping price should be setup in PayPal.)</p>
				<p>Item Type*: 
				<select name="item_category">
					<option value="Physical goods" $physSelected>Physical goods (Items that are shipped)</option>
					<option value="Digital goods" $digSelected>Digital goods (Items that are delivered online)</option>
				</select><br />
				(<font color="#ff0000">Warning!</font> - If you mark your product as Digital goods, you must enter purchase delivery email message below and it will be sent immediately to your buyers once PayPal confirms their payment as successful. This is the automated delivery of digital goods developed on PayPal IPN technology.)
				</p>
				<p>Purchase delivery email message (for digital goods only): (This is the message that will be sent to the buyer once his/her purchase is confirmed. Make sure you enter all the details needed for delivering your digital item in email such as download URL, content URL, etc. These details will be sent to the buyer immediately after the payment is processed but only after payment is confirmed by PayPal.)<br><textarea rows="3" cols="50" name="item_dwld_url">$itemDWLD1000</textarea></p>
    		<p>Item details*: <br>(Use this space to describe your item in details)<br><textarea rows="3" cols="50" name="itemDet">$itemDet1000</textarea></p>
    		<p align="center"><input type="submit" name="b4" value="Save"></p>
    </form>
HTM;

	}
	else
	{
		$pagecontent .= "<p><b>Error!</b> Category missing. You must select a category to add item to.</p>";
	}
}

function saveItem($itemCat, $itemID, $itemTitle, $imageURL, $itemQty, $itemPrice, $itemShip, $item_category, $item_dwld_url, $itemDet)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();
	
	//Mandatory fields
	if($itemCat == "" or $itemID == "" or $itemTitle == "" or $itemQty == "" or $itemPrice == "" or $itemDet == "")
	{
		$pagecontent .= "<p><b>Error!</b> Mandatory fields are missing. Please try again.</p>$itemCat, $itemID, $itemTitle, $imageURL, $itemQty, $itemPrice, $itemShip, $item_category, $item_dwld_url, $itemDet";
		return;
	}
	
	//Item ID check - alphanumeric
	if(ctype_alnum($itemID))
	{
		//OK
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b> Item ID must be alphanumeric field (numbers and letters only).</p>";
		return;
	}

	//Item Titel check - remove HTML tags
	$itemTitle = strip_tags($itemTitle);

	//Check if numeric fields are numeric
	if(is_numeric($itemQty))
	{
		//OK
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b> Quantity must be a numeric value. Try again.</p>";
		return;
	}
	
	//Check if numeric fields are numeric
	if(is_numeric($itemPrice))
	{
		//OK
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b> Price must be a numeric value. Try again.</p>";
		return;
	}
	
	//Remove HTML from item shipping details
	$itemShip = strip_tags($itemShip);
	
	//Image URL must start with http
	if(isValidURL($imageURL)) 
	{
		//OK
  }
  else
	{
		$pagecontent .= "<p><b>Error!</b> Image URL is incorrect. It must start with 'http://' or 'https://'. Try again.</p>";
		return;
  }

	//Remove html from download message
	$item_dwld_url = strip_tags($item_dwld_url);
	
	//Don't allow script, table, div, span tags in the item description
	if(strstr($itemDet, "</table") or strstr($itemDet, "</TABLE") or strstr($itemDet, "</div") or strstr($itemDet, "</DIV") or strstr($itemDet, "</span") or strstr($itemDet, "</SPAN") or strstr($itemDet, "<table") or strstr($itemDet, "<TABLE") or strstr($itemDet, "<div") or strstr($itemDet, "<DIV") or strstr($itemDet, "<span") or strstr($itemDet, "<SPAN"))
	{
  	$pagecontent .= "<p><b>Error!</b> Item description cannot have table or div or span HTML tags. Try again.</p>";
		return;
	}  
	
	$query2= "INSERT INTO store_items_tab (mem_id, category_name, item_id, item_title, item_qty, item_price, item_shipping, item_image, item_details, item_category, item_dwld_url) VALUES ($uid, '$itemCat', '$itemID', '$itemTitle',  $itemQty, $itemPrice,  '$itemShip',  '$imageURL', '$itemDet', '$item_category', '$item_dwld_url') ON DUPLICATE KEY UPDATE item_title = '$itemTitle', item_qty = $itemQty, item_price = $itemPrice, item_shipping = '$itemShip', item_image = '$imageURL', item_details = '$itemDet', item_category = '$item_category', item_dwld_url = '$item_dwld_url'";
	
	
	if(mysql_query ($query2))
	{
		$pagecontent .= "<p><b>Success! </b> Item added/updated.</p>";
	}
	else
	{
		 $pagecontent .= "<p><b>Error!</b> " .  mysql_error();
	}
}
function delItem($itemID1)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();

	$pagecontent .= <<<HTM
		<p align="center"><b>Confirm action</b></p>
		<p>Are you sure you want to delete item <u>$itemID1</u>?</p>
		<form action="index.php?a=delItem1" method="post">
			<input type="hidden" name="itemID2" value="$itemID1">
			<input type="submit" value="Yes - delete '$itemID1'">
		</form>
HTM;
}

function delItem1($itemID2)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();

	$query1="DELETE FROM store_items_tab WHERE mem_id = $uid AND item_id = '$itemID2' LIMIT 1";	
	if (mysql_query($query1))
	{
		$pagecontent .= "<p><b>Success!</b> Item <i>$itemID2</i> deleted.";
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b> " . mysql_error();
	}	
}

function addThankyou()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();
	$query1="SELECT store_thankyou FROM store_info_tab WHERE mem_id = $uid";
	if($res1=mysql_query($query1))
	{
		$store_thankyou=mysql_fetch_array($res1);
	}
	$pagecontent .= <<<HTM
		<p align="center"><b>Add thank you note</b></p>
		<p>Use the following box to add a thank you note to your store. This note will be shown to your buyers after they complete their purchase and are sent back to your store from PayPal's website.</p>
		<p><u>Important note:</u> If you are using your online store to sell digital goods and using PayPal IPN system implemented by us then please make sure you tell your buyers that they will get the product in email.</p>
		<p>Enter thank you note in the box below: (If you leave this blank a default thank you note will be displayed.)</p>
		<form action="index.php?a=saveThankyou" method="post">
			<textarea rows="4" cols="50" name="thankyouNote">$store_thankyou[0]</textarea>
			<input type="submit" value="Save thank you note" />
		</form>
HTM;
}

function saveThankyou($thankyouNote)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templates";
	$site_templates_url = $baseurl . "templates";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();
	
	$query2="UPDATE store_info_tab SET store_thankyou = '$thankyouNote' WHERE mem_id = $uid";
	if($res2=mysql_query($query2))
	{
		$pagecontent .= "<p><b>Success!</b> Thank you note saved successfully.</p>";
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b> " . mysql_error();
	} 
}


?>