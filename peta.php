<?php
//Fungsi dimasukkan ke dalam fail ini

//berfungsi untuk mewujudkan sambungan ke MySQL dan memilih pangkalan data untuk bekerja
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
		print "Terdapat beberapa kesilapan dalaman dalam mendapatkan semula rekod. Maaf untuk inconvinence yang.";
}
//berfungsi untuk menutup link dibuka
function dbclose($link)
{
	global $link;
	if(mysql_close($link))
	{}
}


//Menunjukkan halaman utama
function showMain()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $baseurl;
	$pagekeywords = "mewujudkan kedai, kedai online, membina kedai, etalase";
  $pagedesc = "Buat kedai online anda sendiri secara percuma dalam beberapa minit. Menerima kad kredit, antara muka berasaskan web, membeli-belah PayPal kart dan lebih";
  $pagetitle = "Buat kedai online anda sendiri secara percuma dalam beberapa minit.";

  $pagecontent .= <<<HTM
<table align="center" width="100%" cellpadding="3" cellspacing="0" border="0">
	<tr>
		<td align="center" valign="top">
			<p align="center"><b><font size="5">Store Anda Sendiri PERCUMA Online dalam beberapa minit!</font></b></p>
			<p align="center"><b><font size="3" color="#008080">Mengapa mewujudkan satu laman web yang mudah apabila anda boleh membuat storefront?</font></b></font></p>
			<table align="center">
				<tr>
  				<td valign="top" width="450" bgcolor="#ffffff" align="center">
  					<p><b><font size="2">Buat kedai online!</font></b></p>
						<table summary="">
							<tr>
								<td width="50%" align="left" valign="top">
									<p><img src="photo/arrow2.gif" alt=""><font size="2" color="#800000"><b>Terima Kad Kredit!</b></font></p>
       						<p><img src="photo/arrow2.gif" alt=""><font size="2" color="#800000"><b>Persediaan cepat dan mudah!</b></font></p>
       						<p><img src="photo/arrow2.gif" alt=""><font size="2" color="#800000"><b>Kualiti Pelawat!</b></font></p>
       						<p><img src="photo/arrow2.gif" alt=""><font size="2" color="#800000"><b>Antara muka berasaskan web!</b></font></p>
								</td>
								<td align="left" valign="top">
									<p><img src="photo/arrow2.gif" alt=""><font size="2" color="#800000"><b>Membeli-belah PayPal kart!</b></font></p>
       						<p><img src="photo/arrow2.gif" alt=""><font size="2" color="#800000"><b>Tiada yuran bulanan!</b></font></p>
       						<p><img src="photo/arrow2.gif" alt=""><font size="2" color="#800000"><b>Tiada yuran persediaan!</b></font></p>
       						<p><img src="photo/arrow2.gif" alt=""><font size="2" color="#800000"><b>Tiada pengaturcaraan diperlukan!</b></font></p>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<p align="center"><font size="3"><b>Semua orang membuat keuntungan dalam talian, jangan ketinggalan! Mula menjual dalam gaya ...</b></font></p>
		</td>
	</tr>
</table>

HTM;



}


function register()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent;
	$pagekeywords = "";
	$pagedesc = "Daftar untuk mewujudkan kedai online anda sendiri";
	$pagetitle = "Daftar secara percuma untuk mewujudkan kedai online anda";
	$pagecontent .= <<<HTM
		<p align="center"><font face="arial"><font size="2"><b>Buat Akaun Admin Kedai Anda</b></font></font></p>
		<form action="index.php?a=register1" method="post">
			<p align="center"><font face="arial" size="2">Masukkan Hubungi Email: <input type="text" name="ce" size="50" maxlength="100"><br><font size="1">(Ini adalah e-mel yang anda biasanya mengakses. Kami akan menghantar link pengesahan e-mel ini dan semua komunikasi masa depan.)</p>
			<p align="center"><font face="arial" size="2">Masukkan PayPal Email: <input type="text" name="pe" size="50" maxlength="100"><br><font size="1">(Anda mesti mempunyai <a href="https://www.paypal.com/in/mrb/pal=6LVNWMEXQ3SBN" target="_blank">akaun PayPal sah</a> untuk menggunakan perkhidmatan ini.)</p>
			<p align="center"><font face="arial" size="2">Pilih Kata Laluan: <input type="password" name="pa" size="20" maxlength="15"></p>
			<p><input type="checkbox" name="tosflag"/> - Saya menerima syarat perkhidmatan.</p>
			<p align="center"><input type="submit" value="Buat akaun admin kedai saya ..."></p>
		</font>
HTM;


}

function register1($uce, $upe, $upa, $utosflag)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $baseurl, $noreply_mail;
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = "terima kasih kerana minat anda";
	$pagecontent = "";
	if($uce == "" or $upe == "" or $upa == "" or $utosflag == "")
	{
		$pagecontent .= "<p><b>ERROR!</b> Semua medan adalah mandatori. Anda harus menerima syarat perkhidmatan.</p>";
		return;
	}
	//Adakah e-mel yang sah dimasukkan sebagai e-mel kenalan?
	$emlresult=ereg("^[^@ ]+@[^@ ]+\.[^@ ]+$",$uce,$trashed);
	if(!$emlresult)
	{
		$pagecontent .= "<p><b>ERROR!</b> E-mel kenalan tidak sah.</p>";
		return;
	}
	//Adakah e-mel yang sah dimasukkan sebagai email PayPal?
	$emlresult=ereg("^[^@ ]+@[^@ ]+\.[^@ ]+$",$upe,$trashed);
	if(!$emlresult)
	{
		$pagecontent .= "<p><b>ERROR!</b> Email PayPal adalah tidak sah.</p>";
		return;
	}
	
	//Kata laluan tidak boleh mempunyai ruang
	if(strstr($upa, " "))
	{
		$pagecontent .= "<p><b>ERROR!</b> Kata laluan tidak boleh mempunyai ruang.</p>";
		return;
	}
	//Panjang kata laluan selepas membuang ruang
	
	if(strlen($upa) < 6)
	{
		$pagecontent .= "<p><b>ERROR!</b> Kata laluan mestilah atleast 6 aksara.</p>";
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
		//Sekarang menghantar mel dengan kod pengaktifan
		$msgsub = $sitename . " - confirmation link for - " . $uce;
		$confurl = $baseurl . "index.php?a=confirm&mid=".mysql_insert_id()."&cbcode=".$cbcode;
		$msg = <<<TXT
INI BUKAN SPAM
----------------
Ini adalah link pengesahan anda dari $sitename

$confurl

Jika anda tidak mencari pautan diklik, hanya copy dan paste dalam tetingkap pelayar.

Terima kasih kerana minat anda dan mengalu-alukan di atas kapal!

Admin
$sitename
$baseurl
-----------------

$serviceannouncements

TXT;

		if(send_mail_plain_new($uce,$noreply_mail,$noreply_mail,$msgsub,$msg))
		{
			$pagecontent .= "<p>Link pengesahan akan dihantar kepada <i>$uce</i>. Akaun anda akan menjadi aktif sebaik sahaja anda klik link pengesahan dalam e-mel anda. Terima kasih atas minat anda dan selamat datang datang!</p>";
		}
		else
		{
			$pagecontent .= "<p>Error menghantar link pengesahan. cuba lagi.</p>";
		}
	}
	else
	{
		$pagecontent .= mysql_error();
	}

}

//Hantar mel
function send_mail_plain_new($mailid,$fromid,$replyto,$sub,$msg)
{
	global $def_eml_ad, $email_adcode;
	//print "$mailid,$fromid,$sub,$msg";
  $headers  = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
  /* tajuk tambahan */
  $headers .= "From: $fromid\r\n";
  $headers .= "Reply-To: $replyto\r\n";
	$msg="$msg".$def_eml_ad;
	//ini akan menghantar mel kepada setiap orang secara individu.
	$mailid=split(",",$mailid);
  //Tambah iklan e-mel
	$msg = $msg . $email_adcode;

	for($i=0;$i<count($mailid);$i++)
  {
  	//echo"Message : $msg";
    if(mail($mailid[$i], $sub, $msg,$headers))
  	{
  	}
  	else
  	{
  		print "<br>Error menghantar e-mel kepada $mailid[$i]";
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
	$pagetitle = "mengesahkan akaun - terima kasih kerana minat anda";
	$pagecontent .= "";
	$q="SELECT * FROM store_info_tab WHERE ros_ic = '$midvalue' LIMIT 1";
	if($r=mysql_query($q))
	{
		$cbuserstempdata = mysql_fetch_array($r);
		$codev = $cbuserstempdata['conf_code'];
		$usereml = $cbuserstempdata['contact_email'];
		$ustatus = $cbuserstempdata['mem_status'];
		$upass = $cbuserstempdata['password'];
		
		if($ustatus != "pending")
		{
			$pagecontent .= "<p><b>Error!</b> Akaun pengguna sudah disahkan atau dibekukan oleh admin laman web atau telah digantung. Sila hubungi admin laman web jika anda berfikir terdapat error.</p>";
			return;
		}
		
		if($codev == $codevalue)
		{
			$q1="UPDATE store_info_tab SET mem_status = 'regular' WHERE ros_ic = $midvalue";
			if(mysql_query($q1))
			{
				$pagecontent .= "<p><b>Success!</b> Pengguna berjaya disahkan. Anda boleh log masuk ke akaun anda menggunakan e-mel dan kata laluan yang anda telah digunakan. Selamat datang ke pasukan!</p>";
     		//Sekarang menghantar mel dengan kata laluan
    		$msgsub = $sitename . " - site credentials for - " . $usereml;

     		$msg = <<<TXT
INI BUKAN SPAM
----------------
Ini adalah kelayakan laman web anda untuk $sitename

Nama Pengguna - $usereml
Kata Laluan Anda - $upass

Layari di sini - $baseurl

Terima kasih kerana minat anda dan mengalu-alukan di atas kapal!

Admin
$sitename
$baseurl

TXT;

     		if(send_mail_plain_new($usereml,$noreply_mail,$noreply_mail,$msgsub,$msg))
      	{
      		$pagecontent .= "<p>A mel pengesahan dengan butiran login juga dihantar ke - <i>$usereml</i>.</p>";
      	}
      	else
      	{
      		$pagecontent .= "<p>Ralat menghantar kata laluan ke email anda. Sila maklumkan kepada admin laman web tentang masalah ini.</p>";
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


//Masuk
function login()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $baseurl, $noreply_mail;
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = "login to member area";
	$pagecontent .= <<<HTM
		<p><b>Masuk ke Akaun Admin Store anda</b></p>
		<form action="index.php?a=login1" method="post">
			<p>Nama Pengguna / E-mel - <input type="text" name="uemail" size="50" maxlength="100"></p>
			<p>Kata laluan - <input type="password" name="upass" size="20" maxlength="15"></p>
			<p><input type="submit" value="Login"></p>
		</form>
		<p><a href="index.php?a=retpass">Lupa kata laluan? Ambil di sini.</a></p>
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
		$uid = $cbudata['ros_ic'];
		
		if($cbstatus != "regular")
		{
			$pagecontent .= "<p>ERROR! Akaun pengguna tidak aktif.";
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
			$pagecontent .= "<p>ERROR! Nama pengguna dan / atau kata laluan salah.";
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
	//Baca nama domain
	$q="SELECT storedomain FROM store_info_tab WHERE ros_ic = $usid";
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
		$storeurl = $baseurl . "sms.php?ros_ic=$usid";
	}
	
	$codetoreturn .= <<<HTM
		<p align="center"><b>Hello $uname</b> (<a href="index.php?a=logoff">Logoff</a> - <a href="index.php?a=changepwd">Tukar Kata Laluan</a>)</p>
		<table border="0" cellpadding="10" cellspacing="0" summary="" bgcolor="#ccff99" align="center">
			<tr>
				<td>
					<b>URL kedai anda - <a href="$storeurl" target="_blank">$storeurl</a></b>
				</td>
			</tr>
		</table>
		<p align="center">
		
		<table border="1" cellpadding="2" cellspacing="0" align="center">
			<tr>
				<td align="center">
					<b>Menu persediaan kedai</b>
				</td>
			</tr>			
			<tr>
				<td align="center">
					<a href="index.php?a=chooseTemplate">Pilih template kedai</a>
				</td>
			</tr>
			<tr>

				<td align="center">
					<a href="index.php?a=showBasic">Mengekalkan maklumat asas</a>
				</td>
			</tr>
			<tr>
				<td align="center">
					<a href="index.php?a=showPay">Mengekalkan maklumat pemprosesan pembayaran</a>
				</td>
			</tr>
			<tr>
				<td align="center">
					<a href="index.php?a=showInv">Mengekalkan inventori kedai (menambah / membuang item)</a>
				</td>
			</tr>
			<tr>
				<td align="center">
					<a href="index.php?a=addThankyou">Tambah terima kasih ambil perhatian</a>
				</td>
			</tr>
		</table>

HTM;

	return $codetoreturn;
}
//Fungsi dimasukkan ke dalam fail ini
function retpass()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename;
	$pagekeywords = "mendapatkan kata laluan";
	$pagedesc = "mendapatkan kata laluan";
	$pagetitle = "mendapatkan kata laluan";
	$pagecontent .= <<<HTM
		<p><b>mendapatkan kata laluan</b></p>
		<form action="index.php?a=retpass1" method="post">
			<p>Masukkan e-mel yang anda gunakan untuk mendaftar dengan kami: <input type="text" name="ueml" size=50 maxlength="100"></p>
			<p><input type="submit" value="Retrieve password"></p>
		</form>
HTM;

}

function retpass1($puemail)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $baseurl, $noreply_mail;
	$pagekeywords = "mendapatkan kata laluan";
	$pagedesc = "mendapatkan kata laluan";
	$pagetitle = "mendapatkan kata laluan";
	if($puemail == "")
	{
		$pagecontent .= "<br> E-mel mesti dimasukkan.";
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
INI BUKAN SPAM
----------------

Kata laluan anda untuk $sitename is $passdata[0]
Nama pengguna anda ialah - $puemail

login di sini - $baseurl

terima kasih
Admin
$baseurl
TXT;

				if(send_mail_plain_new($puemail, $noreply_mail, $noreply_mail, $sitename . " - Password retrieval mail - " . $puemail, $msg))
				{
					$pagecontent .= "<br> E-mel dihantar dengan kata laluan untuk <i>$puemail</i>";
				}
				else
				{
					$pagecontent .= "<br> Error menghantar mel.";
				}
			}
			else
			{
				$pagecontent .= "<br> Akaun ini tidak aktif. Kata laluan tidak boleh didapatkan semula.";
			}
		}
		else
		{
			$pagecontent .= "<br> Ralat membaca kata laluan. ".mysql_error();
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
		<p><font size="3"><b>Tukar Kata Laluan</b></font></p>
		<form action="index.php?a=changepwd1" method="post">
			<p>Kata laluan lama: <input type="password" name="opwd" size="15"></p>
			<p>Kata laluan baru: <input type="password" name="npwd" size="15"></p>
			<p>Kata laluan baru mengesahkan: <input type="password" name="npwd1" size="15"></p>
			<p><input type="submit" value="Change password"></p>
		</form>

HTM;

}

function changepwd1($pupwdold, $pupwdnew, $pupwdnew1)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename;
	$pagekeywords = "ahli kawasan, password berubah";
	$pagedesc = "menukar kata laluan";
	$pagetitle = "menukar kata laluan";
	$uname = $_SESSION['uname'];
	$pagecontent .= getMemMenu();

	if($pupwdold == "" or $pupwdnew == "" or $pupwdnew1 == "")
	{
		$pagecontent .= "<br> Sesetengah atau semua penyertaan yang hilang. cuba lagi.";
	}
	else
	{
		if($pupwdnew != $pupwdnew1)
		{
			$pagecontent .= "<br> Kata laluan baru tidak sepadan. cuba lagi.";
		}
		else
		{
			//Pilih kata laluan semasa pengguna
			$q="SELECT password FROM store_info_tab WHERE contact_email = '$uname'";
			if($r=mysql_query($q))
			{
				$userdata = mysql_fetch_array($r);
				if($userdata[0] != $pupwdold)
				{
					$pagecontent .= "<br>Kata laluan lama tidak sepadan. cuba lagi.";
				}
				else
				{
					$q1="UPDATE store_info_tab SET password = '$pupwdnew' WHERE contact_email = '$uname'";
					if($r1=mysql_query($q1))
					{
						$pagecontent .= "<br> Kata chage berjaya!";
					}
					else
					{
						$pagecontent .= "<br> Ralat mengemas kini kata laluan pengguna. ".mysql_error();
					}
				}
			}
			else
			{
				$pagecontent .= "<br> Error dalam membaca data pengguna. ".mysql_error();
			}
		}
	}

}

function chooseTemplate()
{
  global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " memilih template kedai";
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
		<p align="center"><b>Pilih template</b></p>		
		<p align="center">(Berikut menunjukkan senarai template yang sedia ada. Sila pilih satu yang anda hendak gunakan dan masukkan nombor dalam kotak di bawah.)</p>
		<p align="center">$templates_list</p>
		<form action="index.php?a=saveTemplate" method="post">
			<p align="center">Pilih template dari jatuh: <select name="template">$templates_dropdown</select></p>
			<p align="center"><input type="submit" value="Save" /></p>
		</form>
HTM;

}

function saveTemplate($template)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " memilih template kedai";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	if($template == "")
	{
		$pagecontent .= "<p><b>Error!</b> Bernombor tidak boleh kosong. cuba lagi. ";
		return;
	}
	$tempFile=$site_templates_path . "/" . $template . ".html"; 
	$query1="SELECT * FROM store_info_tab WHERE ros_ic = $uid";
	if($res1=mysql_query($query1))
	{
		if(file_exists($tempFile))
		{
		
		}
		else
		{
			$pagecontent .= "<p><b>Error!</b> Bilangan tempalte tidak sah. cuba lagi.";
			return;
		}
		$storeInfoData=mysql_fetch_array($res1);
		$query2="UPDATE store_info_tab SET template='$template' WHERE ros_ic = $uid"; 
		if(mysql_query($query2))
		{
			$pagecontent .= "<p><b>Success!</b> Templat berjaya ditetapkan untuk <i>$template</i> for <i>$uname</i>.";
		}
		else
		{
			$pagecontent .= "<p><b>Error!</b>  " . mysql_error();
		}
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b>  Tidak dapat membaca data kedai. " . mysql_error();
	}
}

function showBasic()
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " memilih template kedai";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	if ($res=mysql_query("SELECT * FROM store_info_tab WHERE ros_ic = $uid"))
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
			<b>Masukkan maklumat asas mengenai kedai anda</b>
		</p>
		<form action="index.php?a=saveBasic" method="post">
			<p>Title: <input type="text" name="storetitle" value="$st" size="40"><br>(Ini adalah tajuk yang ditunjukkan dalam huruf tebal di bahagian atas kedai anda. Gunakan 2-3 perkataan.)</p>
			<p>Penerangan ringkas: <input type="text" name="shortdesc" value="$sd" cols="50" rows="5"><br>(Jelaskan kedai anda dalam 1-2 ayat.)</p>
			<p>Maklumat kenalan:<br>(Ini akan ditunjukkan di kedai anda di bawah link kenalan. Termasuk maklumat hubungan seberapa banyak yang anda mahu seperti 1-800 nombor, e-mel, alamat, dan lain-lain Basic HTML Tags &lt;p&gt; , &lt;br&gt; and &lt;b&gt; are allowed.)<br> <textarea rows="5" cols="50" name="contact">$sc</textarea></p>
			<p>Tentang perniagaan anda:<br>(Jelaskan perniagaan / kedai anda kepada pelanggan anda. Ini akan ditunjukkan di bawah tentang hubungan di kedai anda. Basic HTML Tags &lt;p&gt; , &lt;br&gt; and &lt;b&gt; are allowed.)<br> <textarea rows="3" cols="50" name="about">$sa</textarea></p>
			<p>Selamat datang mesej:<br>(Ini akan ditunjukkan pada halaman utama kedai anda. Mudah 2-3 perenggan dengan mesej menarik dijangka. Basic HTML Tags &lt;p&gt; , &lt;br&gt; and &lt;b&gt; are allowed.)<br> <textarea rows="3" cols="50" name="welcome">$sw</textarea></p>
			
			<p align="center"><input type="submit" name="b1" value="Save"></p>
		</form>
HTM;
}

function saveBasic($st, $sd, $co, $ab, $we)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
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
			INSERT INTO store_info_tab SET ros_ic = $uid, store_title='$st',short_desc='$sd',contact='$co', about='$ab', welcome_note='$we' ON DUPLICATE KEY UPDATE store_title='$st', short_desc='$sd', contact='$co',  about='$ab', welcome_note='$we'
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
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	
	if ($res=mysql_query("SELECT * FROM store_info_tab WHERE ros_ic = $uid"))
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
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	$query2= "INSERT INTO store_info_tab (ros_ic, store_currency, paypal_email)	VALUES ($uid, '$sc', '$pa')	ON DUPLICATE KEY UPDATE store_currency = '$sc', paypal_email = '$pa'";
	
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
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	$query1="SELECT * FROM store_cats_tab WHERE ros_ic = $uid";
	if($res = mysql_query ($query1))
	{
		while($res1 = mysql_fetch_array($res))
		{
			$dropdown= $dropdown . "<option value='$res1[1]'>$res1[1]</option>";
		}
	}
	
	$query2="SELECT * FROM store_items_tab WHERE ros_ic = $uid";
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
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
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
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
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
	$query1 = "SELECT * FROM store_cats_tab WHERE ros_ic = $uid AND category_name = '$ca'";
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
			insert into store_cats_tab (ros_ic,category_name)
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
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
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
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();	
	$query1="DELETE FROM store_cats_tab WHERE ros_ic = $uid AND category_name = '$ca' LIMIT 1";
	$query2="DELETE FROM store_items_tab WHERE ros_ic = $uid AND category_name = '$ca'";
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
	$query1="SELECT inv_limit FROM store_info_tab WHERE ros_ic = $ppuname";
	if($res1=mysql_query($query1))
	{
		$inv_limit=mysql_fetch_array($res1);		
	}
	$query2="SELECT count(*) FROM store_items_tab WHERE ros_ic = $ppuname";
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
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " save basic information";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();
	
	//If item id is present, read the item details
	if($itmid != "")
	{
		$query1000="SELECT * FROM store_items_tab WHERE ros_ic = $uid AND item_id = '$itmid'";
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
    		<p>Price in store currency*: <input type="text" name="itemPrice" size="15" value="$itemPrice1000" maxlength="11"><br>(Mesti menjadi nilai berangka dengan titik perpuluhan yang sesuai atau kereta membeli-belah tidak akan berfungsi dengan betul.)</p>
  			<p>Shipping details: <input type="text" name="itemShip" size="50" value="$itemShip1000" maxlength="100"><br>(Gunakan medan ini untuk menyenaraikan dasar penghantaran. Harga penghantaran sebenar perlu persediaan di PayPal.)</p>
				<p>Item Type*: 
				<select name="item_category">
					<option value="Physical goods" $physSelected>Barangan fizikal (Item yang dihantar)</option>
					<option value="Digital goods" $digSelected>Barangan digital (Barang-barang yang dihantar online)</option>
				</select><br />
				(<font color="#ff0000">Warning!</font> - Jika anda menandakan produk anda sebagai barangan Digital, anda mesti memasukkan pembelian penghantaran mesej e-mel di bawah dan ia akan dihantar dengan segera kepada pembeli anda sekali PayPal mengesahkan pembayaran sebagai berjaya. Ini adalah penghantaran automatik barangan digital dibangunkan PayPal IPN teknologi.)
				</p>
				<p>Pembelian penghantaran mesej e-mel (untuk barangan digital sahaja): (Ini adalah mesej yang akan dihantar kepada pembeli sebaik sahaja pembelian / beliau disahkan. Pastikan anda memasukkan semua butiran yang diperlukan untuk menyampaikan item digital anda dalam e-mel seperti turun URL, URL kandungan, dan lain-lain butir-butir akan dihantar kepada pembeli dengan segera selepas pembayaran diproses tetapi hanya selepas pembayaran disahkan oleh PayPal.)<br><textarea rows="3" cols="50" name="item_dwld_url">$itemDWLD1000</textarea></p>
    		<p>Item details*: <br>(Gunakan ruangan ini untuk menerangkan barang anda dalam butiran)<br><textarea rows="3" cols="50" name="itemDet">$itemDet1000</textarea></p>
    		<p align="center"><input type="submit" name="b4" value="Save"></p>
    </form>
HTM;

	}
	else
	{
		$pagecontent .= "<p><b>Error!</b> Kategori hilang. Anda mesti memilih kategori untuk menambah item ke.</p>";
	}
}

function saveItem($itemCat, $itemID, $itemTitle, $imageURL, $itemQty, $itemPrice, $itemShip, $item_category, $item_dwld_url, $itemDet)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " menyimpan maklumat asas";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();
	
	//medan mandatori
	if($itemCat == "" or $itemID == "" or $itemTitle == "" or $itemQty == "" or $itemPrice == "" or $itemDet == "")
	{
		$pagecontent .= "<p><b>Error!</b> Bidang mandatori hilang. Sila cuba sekali lagi.</p>$itemCat, $itemID, $itemTitle, $imageURL, $itemQty, $itemPrice, $itemShip, $item_category, $item_dwld_url, $itemDet";
		return;
	}
	
	//Item ID cek - abjad
	if(ctype_alnum($itemID))
	{
		//OK
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b> ID Item mesti bidang abjad angka (nombor dan huruf sahaja).</p>";
		return;
	}

	//Perkara Titel memeriksa - membuang tag HTML
	$itemTitle = strip_tags($itemTitle);

	//Check if numeric fields are numeric
	if(is_numeric($itemQty))
	{
		//OK
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b> Bahan mesti bernilai angka. Cuba lagi.</p>";
		return;
	}
	
	//Check if numeric fields are numeric
	if(is_numeric($itemPrice))
	{
		//OK
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b> Harga mesti bernilai angka. Cuba lagi.</p>";
		return;
	}
	
	//Buang HTML dari butiran penghantaran item
	$itemShip = strip_tags($itemShip);
	
	//URL imej mesti bermula dengan http
	if(isValidURL($imageURL)) 
	{
		//OK
  }
  else
	{
		$pagecontent .= "<p><b>Error!</b> URL imej adalah tidak betul. Ia mesti bermula dengan 'http://' or 'https://'. cuba lagi.</p>";
		return;
  }

	//Buang html dari mesej turun
	$item_dwld_url = strip_tags($item_dwld_url);
	
	//Tidak membenarkan skrip, meja, div, tag span dalam huraian item
	if(strstr($itemDet, "</table") or strstr($itemDet, "</TABLE") or strstr($itemDet, "</div") or strstr($itemDet, "</DIV") or strstr($itemDet, "</span") or strstr($itemDet, "</SPAN") or strstr($itemDet, "<table") or strstr($itemDet, "<TABLE") or strstr($itemDet, "<div") or strstr($itemDet, "<DIV") or strstr($itemDet, "<span") or strstr($itemDet, "<SPAN"))
	{
  	$pagecontent .= "<p><b>Error!</b> Penerangan Item tidak boleh mempunyai jadual atau span atau span HTML tag. cuba lagi.</p>";
		return;
	}  
	
	$query2= "INSERT INTO store_items_tab (ros_ic, category_name, item_id, item_title, item_qty, item_price, item_shipping, item_image, item_details, item_category, item_dwld_url) VALUES ($uid, '$itemCat', '$itemID', '$itemTitle',  $itemQty, $itemPrice,  '$itemShip',  '$imageURL', '$itemDet', '$item_category', '$item_dwld_url') ON DUPLICATE KEY UPDATE item_title = '$itemTitle', item_qty = $itemQty, item_price = $itemPrice, item_shipping = '$itemShip', item_image = '$imageURL', item_details = '$itemDet', item_category = '$item_category', item_dwld_url = '$item_dwld_url'";
	
	
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
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " menyimpan maklumat asas";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();

	$pagecontent .= <<<HTM
		<p align="center"><b>Sahkan tindakan</b></p>
		<p>Adakah anda pasti anda mahu memadam item <u>$itemID1</u>?</p>
		<form action="index.php?a=delItem1" method="post">
			<input type="hidden" name="itemID2" value="$itemID1">
			<input type="submit" value="Yes - delete '$itemID1'">
		</form>
HTM;
}

function delItem1($itemID2)
{
	global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl;
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " menyimpan maklumat asas";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();

	$query1="DELETE FROM store_items_tab WHERE ros_ic = $uid AND item_id = '$itemID2' LIMIT 1";	
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
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " menyimpan maklumat asas";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();
	$query1="SELECT store_thankyou FROM store_info_tab WHERE ros_ic = $uid";
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
	$site_templates_path = $basepath . "templat";
	$site_templates_url = $baseurl . "templat";
	
	$pagekeywords = "";
	$pagedesc = "";
	$pagetitle = " menyimpan maklumat asas";
	$uname = $_SESSION['uname'];
	$uid = $_SESSION['userid'];
	
	$pagecontent .= getMemMenu();
	
	$query2="UPDATE store_info_tab SET store_thankyou = '$thankyouNote' WHERE ros_ic = $uid";
	if($res2=mysql_query($query2))
	{
		$pagecontent .= "<p><b>Success!</b> Terima kasih nota berjaya disimpan.</p>";
	}
	else
	{
		$pagecontent .= "<p><b>Error!</b> " . mysql_error();
	} 
}

?>