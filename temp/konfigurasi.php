<?php
$dbhost = "localhost";   //Pelbagai pangkalan data, selalunya localhost
$dbname = "affiliate_sms";   //DB Nama
$dbuser = "affiliate_admin1";   //Nama pengguna DB
$dbpwd = "6021023";    //Kata laluan pengguna DB

$baseurl = "http://www.flash.mx/sms/"; //URL laman web kami - http://www.namadomain.com/sms/  
$basepath = "/home/affiliate/public_html/flash.mx/sms/"; //Jalan asas untuk folder akar laman web kami - example - /home/somename/public_html/

$sitename= "flash.mx"; //SITENAME kami di sini

$pagekeywords = ""; //Biarkan ini kosong
$pagedesc = ""; //Biarkan ini kosong
$pagetitle = ""; //Biarkan ini kosong
$pagecontent = ""; //Biarkan ini kosong

//Tiada e-mel jawapan
$noreply_mail = "gooclebook@gmail.com"; //Ini adalah tag e-mel yang apppears sebagai 'Daripada' alamat e-mel - tidak mempunyai e-mel yang sah jika kita tidak membalas mel yang dihantar pulang. Alamat ini akan paling dieksploitasi, jadi menggunakan satu yang SPAM tahan.

//Perkhidmatan pengumuman - string teks yang akan dihantar dalam semua komunikasi e-mel
$serviceannouncements = <<<TXT

TXT;

//e-mel admin
$admin_email = "tsamud1@yahoo.com"; //Mesti email yang sah di sini, untuk mendapatkan pemberitahuan admin 

//Iklan e-mel lalai - dihantar dalam semua komunikasi e-mel
$email_adcode = <<<TXT

TXT;

//Sepanduk yang ditunjukkan di atas kedai-kedai. Pertama WIDE adalah 480 x 60, kedua TINGGI adalah 120 x 600. - Baik untuk menyelamatkan beberapa kod seperti Google adsense atau yang serupa, untuk menunjukkan pada setiap halaman kedai - Kita boleh secara langsung (dengan teliti) edit fail template untuk menunjukkan sepanduk.
$b480X60 = <<<HTM
<a href="http://www.flash.mx" target="_blank"><img src="images/banner.gif"></a>
HTM;

$b120X600 = <<<HTM

HTM;

?>