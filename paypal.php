<?php

// Termasuk fail globals.php
include("konfigurasi.php");
include("peta.php");
global $pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl, $admin_email;
$site_templates_path = $basepath . "templat";
$site_templates_url = $baseurl . "templat";

$link=dbconnect();
$ros_ic = $HTTP_GET_VARS['ros_ic'];
// Pilih e-mel utama PayPal ahli
$query1="SELECT paypal_email FROM store_info_tab WHERE ros_ic = $ros_ic";
if($res1=mysql_query($query1))
{
	$primary_email=mysql_fetch_array($res1);
	if($primary_email[0])
	{
		$paypal_primary_email=$primary_email[0];
	}
}

// memulakan konfigurasi pengguna
//========================================================================================

/* set notify_debug = 1; menerima e-mel setiap kali skrip ini dijalankan. */
$notify_debug = 1;

/* alamat e-mel yang digunakan dalam Untuk: dan Daripada: bidang mesej e-mel debug. */
$site_email = $admin_email;

// Akaun e-mel PayPal alamat e-mel utama
/*
Alamat e-mel utama penerima pembayaran (iaitu, peniaga).
Jika pembayaran itu dihantar ke alamat e-mel bukan-sekolah rendah dalam akaun PayPal anda, 
yang receiver_email masih akan e-mel utama anda
*/

$paypal_receiver_email = $paypal_primary_email;


// menerima wang dari PayPal pengguna yang mempunyai akaun PayPal disahkan
// ya atau tidak
$accept_unverified = 'yes';

// menerima wang dari PayPal pengguna yang mempunyai alamat akaun PayPal yang belum disahkan
// ya atau tidak
$accept_unconfirmed = 'yes';

// menghantar barangan digital untuk evenif pembeli status pembayaran yang dilaporkan oleh PayPal masih belum selesai. Ini berlaku jika penjual adalah penjual antarabangsa (bukan kita) atau pembeli adalah tidak disahkan atau banyak sebab itu. terdapat risiko kecil yang berkaitan dengan ini sejak beberapa bayaran yang belum selesai mungkin penipuan.
// ya atau tidak
$accept_pending = 'yes';

// alamat ip paypal 65.206.229.140
// paypal Alamat IP: 64.4.241.140 - baru pada 2033/10/24
// NetRange:   65.206.228.0 - 65.206.231.255



$date = date("D, j M Y H:i:s O"); 
$crlf = "\n";
$debug_headers = "From: $site_email" .$crlf;
$debug_headers .= "Reply-To: $site_email" .$crlf;
$debug_headers .= "Return-Path: $site_email" .$crlf;
$debug_headers .= "X-Mailer: Perl-Studio" .$crlf;
$debug_headers .= "Date: $date" .$crlf; 
$debug_headers .= "X-Sender-IP: $REMOTE_ADDR" .$crlf; 
//========================================================================================
// akhir konfigurasi pengguna

$error = 0;
$post_string = '';
$output = '';
$valid_post = ''; 

$workString = 'cmd=_notify-validate';
/* Dapatkan PayPal pembolehubah Pemberitahuan Pembayaran termasuk kod disulitkan */ 
reset($HTTP_POST_VARS);
while(list($key, $val) = each($HTTP_POST_VARS)) { 
$post_string .= $key.'='.$val.'&'; 
$val = stripslashes($val);
$val = urlencode($val); 
$workString .= '&' .$key .'=' .$val; 
} 

if($notify_debug){
  $str = $post_string;
  $str = str_replace('&', "\n", $str );
  $debugmess = "Satu cubaan pembayaran dicatatkan di kedai anda. Ini tidak bermakna bahawa bayaran itu berjaya atau tidak berjaya. Anda akan menerima satu e-mel yang lebih untuk memberitahu jika usaha ini berjaya. Data mentah diberikan oleh PayPal adalah seperti berikut:\n===============================\n$str\n\n";
//  $str = $workString;
//  $str = str_replace('&', "\n", $str );
//  $debugmess .= "workString dihantar semula ke PayPal\n==========================\n$str\n\n";
    
  /* mel kepada pemilik skrip ini*/
  if(send_mail_plain($paypal_receiver_email, $site_email,"PayPal payment attempt at your store", $debugmess))
	{
		print "hi";
	}
	else
	{
		print "problem at 1<br>";
	}

}  


$details_string= "<table border=0 cellpadding=0 cellspacing=5 width=60%>\n";
$details_string.= '<tr><td>payment date</td><td>' .$_POST['payment_date'] ."</td></tr>\n"; 
$details_string.= '<tr><td>invoice no.</td><td>' .$_POST['invoice'] ."</td></tr>\n"; 
$details_string.= '<tr><td>payment id</td><td>' .$_POST['txn_id'] ."</td></tr>\n"; 
$details_string.= '<tr><td>item name</td><td>' .$_POST['item_name'] ."</td></tr>\n"; 
$details_string.= '<tr><td>quantity</td><td>' .$_POST['quantity'] ."</td></tr>\n"; 
$details_string.= '<tr><td>payment status</td><td>' .$_POST['payment_status'] ."</td></tr>\n"; 
$details_string.= '<tr><td>pending reason</td><td>' .$_POST['pending_reason'] ."</td></tr>\n"; 
$details_string.= '<tr><td>payment gross</td><td>' .$_POST['payment_gross'] ."</td></tr>\n"; 
$details_string.= '<tr><td>payment method</td><td>' .$_POST['payment_method'] ."</td></tr>\n"; 
$details_string.= '<tr><td>firstname</td><td>' .$_POST['first_name'] ."</td></tr>\n"; 
$details_string.= '<tr><td>lastname</td><td>' .$_POST['last_name'] ."</td></tr>\n"; 
$details_string.= '<tr><td>street address</td><td>' .$_POST['address_street'] ."</td></tr>\n"; 
$details_string.= '<tr><td>city</td><td>' .$_POST['address_city'] ."</td></tr>\n"; 
$details_string.= '<tr><td>state</td><td>' .$_POST['address_state'] ."</td></tr>\n"; 
$details_string.= '<tr><td>zipcode</td><td>' .$_POST['address_zip'] ."</td></tr>\n"; 
$details_string.= '<tr><td>country</td><td>' .$_POST['address_country'] ."</td></tr>\n"; 
$details_string.= '<tr><td>payer email</td><td>' .$_POST['payer_email'] ."</td></tr>\n"; 
$details_string.= '<tr><td>address status</td><td>' .$_POST['address_status'] ."</td></tr>\n"; 
$details_string.= '<tr><td>payer status</td><td>' .$_POST['payer_status'] ."</td></tr>\n"; 
$details_string.= "</table>\n";


/* memberikan posted pembolehubah pembolehubah tempatan
1) ambil perhatian: beberapa pembolehubah posted akan kosong
2) perhatian: berikut bukan senarai lengkap pembolehubah mencatatkan
*/

$item_name = trim(stripslashes($_POST['item_name']));
$item_number = trim(stripslashes($_POST['item_number']));
$payment_status = trim(stripslashes($_POST['payment_status']));
$payment_gross = trim(stripslashes($_POST['payment_gross']));
$txn_id = trim(stripslashes($_POST['txn_id']));
$receiver_email = trim(stripslashes($_POST['receiver_email']));
$payer_email = trim(stripslashes($_POST['payer_email']));
$payment_date = trim(stripslashes($_POST['payment_date']));
$invoice =  trim(stripslashes($_POST['invoice']));
$quantity = trim(stripslashes($_POST['quantity']));
$pending_reason = trim(stripslashes($_POST['pending_reason']));
$payment_method = trim(stripslashes($_POST['payment_method']));
$first_name = trim(stripslashes($_POST['first_name']));
$last_name = trim(stripslashes($_POST['last_name']));
$address_street = trim(stripslashes($_POST['address_street']));
$address_city = trim(stripslashes($_POST['address_city']));
$address_state = trim(stripslashes($_POST['address_state']));
$address_zipcode = trim(stripslashes($_POST['address_zip']));
$address_country = trim(stripslashes($_POST['address_country']));
$payer_email = trim(stripslashes($_POST['payer_email']));
$address_status = trim(stripslashes($_POST['address_status']));
$payer_status = trim(stripslashes($_POST['payer_status']));
$notify_version = trim(stripslashes($_POST['notify_version'])); 
$verify_sign = trim(stripslashes($_POST['verify_sign'])); 
$business = trim(stripslashes($_POST['business'])); 
$custom = trim(stripslashes($_POST['custom'])); 
$txn_type = trim(stripslashes($_POST['txn_type'])); 

$settle_amount = trim(stripslashes($_POST['settle_amount'])); 
$settle_currency = trim(stripslashes($_POST['settle_currency'])); 
$exchange_rate = trim(stripslashes($_POST['exchange_rate'])); 
$payment_fee = trim(stripslashes($_POST['payment_fee'])); 
$mc_gross = trim(stripslashes($_POST['mc_gross'])); 
$mc_fee = trim(stripslashes($_POST['mc_fee'])); 
$mc_currency = trim(stripslashes($_POST['mc_currency'])); 
$tax = trim(stripslashes($_POST['tax'])); 
$for_auction = trim(stripslashes($_POST['for_auction'])); 
$memo = trim(stripslashes($_POST['memo'])); 
$option_name1 = trim(stripslashes($_POST['option_name1'])); 
$option_selection1 = trim(stripslashes($_POST['option_selection1'])); 
$option_name2 = trim(stripslashes($_POST['option_name2'])); 
$option_selection2 = trim(stripslashes($_POST['option_selection2'])); 
$num_cart_items = trim(stripslashes($_POST['num_cart_items'])); 


// pembolehubah langganan 
$username = trim(stripslashes($_POST['username'])); 
$password = trim(stripslashes($_POST['password'])); 
$subscr_id = trim(stripslashes($_POST['subscr_id']));
$subscr_date = trim(stripslashes($_POST['subscr_date'])); 
$subscr_effective = trim(stripslashes($_POST['subscr_effective'])); 
$period1 = trim(stripslashes($_POST['period1'])); 
$period2 = trim(stripslashes($_POST['period2'])); 
$period3 = trim(stripslashes($_POST['period3'])); 
$amount1 = trim(stripslashes($_POST['amount1'])); 
$amount2 = trim(stripslashes($_POST['amount2'])); 
$amount3 = trim(stripslashes($_POST['amount3'])); 
$mc_amount1 = trim(stripslashes($_POST['mc_amount1'])); 
$mc_amount2 = trim(stripslashes($_POST['mc_amount2'])); 
$mc_amount3 = trim(stripslashes($_POST['mc_amount3'])); 
$recurring = trim(stripslashes($_POST['recurring'])); 
$recur_times = trim(stripslashes($_POST['recur_times'])); 
$subscr_eot = trim(stripslashes($_POST['subscr_eot']));
$subscr_cancel = trim(stripslashes($_POST['subscr_cancel']));



if($paypal_receiver_email != $receiver_email){
    $error_message .= "Error code 501. Possible fraud. Error with receiver_email. receiver_email = $receiver_email\n";
    $error++;
}  

$remote_ip_error = true;
if ((preg_match("/^65.206/", $REMOTE_ADDR)) || (preg_match("/^64.4/", $REMOTE_ADDR))){
    $remote_ip_error = false;
} 
if ($remote_ip_error == true){
       $error_message = "Error code 506. Possible fraud. Error with REMOTE IP ADDRESS = $REMOTE_ADDR . The remote address of the script posting to this notify script does not match a valid PayPal ip address\n";
       $error++;
} 

if($error>0)
{
	$email_message=$error_message.$post_string;
  $email_message = str_replace('&', "\n", $email_message );
	if(mail($paypal_receiver_email,"Erroneous PayPal payment attempt",$email_message,$debug_headers))
	{
		print "hi";
	}
	
	exit;
}

// pos kembali kepada sistem PayPal untuk mengesahkan
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Host: paypal.com\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen ($workString) . "\r\n\r\n";
$fp = fsockopen ("www.paypal.com", 80, $errno, $errstr, 30);


if (!$fp) {
// HTTP ERROR
echo "$errstr ($errno)";
} else {
   fputs ($fp, $header . $workString);
   while (!feof($fp)) {
     $output .= fgets ($fp, 1024);
   }
   fclose ($fp);
}


// mengeluarkan tajuk pos, jika ada.
$output = preg_replace("'Content-type: text/plain'si","",$output);

$error_lines = split("\n", $error_message);
$i=0;
while($i <= sizeof($error_lines)) {            
  $error_message_html .= "<p>" .$error_lines[$i];
  $i++;
}


// logik untuk mengendalikan tindak balas SAH atau DISAHKAN.
/* sambutan sah dari PayPal, update jadual paypal dengan balasan*/ 

if (ereg('VERIFIED',$output)) { 
    $valid_post = 'VERIFIED POST'; 
    if (eregi('failed',$payment_status)){ 
    /* invalid - mengemaskini jadual paypal dengan 'sah' balasan*/  
      $debug_status = 1;//"dikemaskini jadual paypal dengan sambutan DISAHKAN-gagal";
//      update_paypal_ipn_table(1);
    
    } 
    else if (eregi('denied',$payment_status)){ 
    /* invalid - mengemaskini jadual paypal dengan 'sah' balasan*/  
      $debug_status = 2;//"dikemaskini jadual paypal dengan DISAHKAN-menafikan";
//      update_paypal_ipn_table(2);
     
    } 
    
     else if (eregi('pending',$payment_status)){ 
    /* invalid - mengemaskini jadual paypal dengan 'sah' balasan*/  
      $debug_status = 3;//"dikemaskini jadual paypal dengan DISAHKAN sementara menunggu";
//       update_paypal_ipn_table(3);
    
    }     
    
    else if ((eregi('Completed',$payment_status)) && ($error == 0)){ 
      
      if (eregi('unverified',$payer_status)){ 
      /* mengemaskini jadual paypal dengan 'DISAHKAN-tidak disahkan' balasan*/ 
        if($accept_unverified == 'yes'){          
            // set paid = 'yes'
             $debug_status = 4;//"dikemaskini jadual paypal dengan sambutan DISAHKAN dilengkapkan dengan status pembayar disahkan";
//              update_paypal_ipn_table(4);
           }
        else{
            $debug_status = 5;//"dikemaskini jadual paypal dengan sambutan DISAHKAN-tidak disahkan";
//             update_paypal_ipn_table(5);
        }  
     } 
     else if (eregi('unconfirmed',$address_status)) {  
      /* sah - mengemaskini jadual paypal dengan 'belum disahkan' balasan*/ 
      if($accept_unconfirmed == 'yes'){
             // set paid = 'yes'
            $debug_status = 6;//"dikemaskini jadual paypal dengan sambutan DISAHKAN dilengkapkan dengan status alamat yang belum disahkan";
//            update_paypal_ipn_table(6);
          }
       else{
            $debug_status = 7;//"dikemaskini jadual paypal dengan sambutan DISAHKAN-belum disahkan";
//             update_paypal_ipn_table(7);

        }  
      }
      else{
                
          /* sah-disahkan, update jadual paypal dengan sambutan disahkan */ 
          $debug_status = 8;//"dikemaskini jadual paypal dengan sambutan DISAHKAN-siap, Perintah lengkap";
//          update_paypal_ipn_table(8);

        }
    }   // status pembayaran akhir melengkapkan
}   // berakhir sambutan DISAHKAN dari paypal

else if (ereg('INVALID',$output)) { 
  $valid_post = 'INVALID POST'; 
    /* invalid - mengemaskini jadual paypal dengan 'sah' balasan*/ 
  $debug_status = 9;//"dikemaskini jadual paypal dengan sambutan SAH. PayPal kembali sambutan yang SAH";
//  update_paypal_ipn_table(9);

} 
$post_string = str_replace('&', "\n", $post_string );
// Memutuskan tindakan berdasarkan status debug
switch($debug_status)
{
	case 1;
		//Hantar e-mel kepada penjual
		//Transaksi = sah
		//Pembayaran = gagal
		//Pembeli = na
		//Alamat = na
		$seller_subject="Failed payment notice from $sitename";
		$seller_message="A transaction was recorded for your digital goods at $sitename.\n\nTransaction status reported by PayPal: Payment failed. Buyer did NOT get the product.\n\nAction required from you: None\n\nTransaction details:\n\n$post_string\n\n";
		$buyer_email_flag=0;
		break;
	case 2;
		//Hantar e-mel kepada penjual
		//Transaksi = sah
		//Pembayaran = menafikan
		//Pembeli = na
		//Alamat = na
		$seller_subject="Denied payment notice from $sitename";
		$seller_message="A transaction was recorded for your digital goods at $sitename.\n\nTransaction status reported by PayPal: Payment denied. Buyer did NOT get the product.\n\nAction required from you: None\n\nTransaction details:\n\n$post_string\n\n";
		$buyer_email_flag=0;
		break;
	case 3;
		//Hantar e-mel kepada penjual
		//Transaksi = sah
		//Pembayaran = menafikan
		//Pembeli = na
		//Alamat = na
		$seller_subject="Pending payment notice from $sitename";
		$seller_message="A transaction was recorded for your digital goods at $sitename.\n\nTransaction status reported by PayPal: Payment pending. Buyer did MAY NOT get the product.\n\nAction required from you: Contact the buyer and send the product once payment is cleared by PayPal\n\nTransaction details:\n\n$post_string\n\n";
		if($accept_pending=='yes')
		{
			$buyer_email_flag=2;
		}
		else
		{
			$buyer_email_flag=1;
		}
		break;
	case 4;
		//Hantar e-mel kepada penjual
		//Transaksi = sah
		//Pembayaran = lengkap
		//Pembeli = tidak disahkan
		//Address=na
		$seller_subject="Completed payment notice from $sitename";
		$seller_message="A transaction was recorded for your digital goods at $sitename.\n\nTransaction status reported by PayPal: Payment complete. Buyer is sent the product.\n\nAction required: None but thank you note is always appreciated to make sure product is received.\n\nTransaction details:\n\n$post_string\n\n";
		$buyer_email_flag=2;
		break;
	case 5;
		//Hantar e-mel kepada penjual
		//Transaction=valid
		//Pembayaran = lengkap
		//Pembeli = tidak disahkan
		//Alamat = tidak disahkan
		$seller_subject="Completed payment notice from $sitename";
		$seller_message="A transaction was recorded for your digital goods at $sitename.\n\nTransaction status reported by PayPal: Payment complete. Buyer is sent the product.\n\nAction required: None but thank you note is always appreciated to make sure product is received.\n\nTransaction details:\n\n$post_string\n\n";
		$buyer_email_flag=2;
		break;
	case 6;
		//Hantar e-mel kepada penjual 
		//Transaksi = sah
		//Pembayaran = lengkap
		//Buyer=na
		//Address=unconfirmed
		$seller_subject="Completed payment notice from $sitename";
		$seller_message="A transaction was recorded for your digital goods at $sitename.\n\nTransaction status reported by PayPal: Payment complete. Buyer is sent the product.\n\nAction required: None but thank you note is always appreciated to make sure product is received.\n\nTransaction details:\n\n$post_string\n\n";
		$buyer_email_flag=2;
		break;
	case 7;
		//Hantar e-mel kepada penjual
		//Transaksi = sah
		//Pembayaran = lengkap
		//Buyer=na
		//Address=unconfirmed
		$seller_subject="Completed payment notice from $sitename";
		$seller_message="A transaction was recorded for your digital goods at $sitename.\n\nTransaction status reported by PayPal: Payment complete. Buyer is sent the product.\n\nAction required: None but thank you note is always appreciated to make sure product is received.\n\nTransaction details:\n\n$post_string\n\n";
		$buyer_email_flag=2;
		break;
	case 8;
		//Hantar e-mel kepada penjual
		//Transaksi = sah
		//Pembayaran = lengkap
		//Buyer=na
		//Address=confirmed
		$seller_subject="Completed payment notice from $sitename";
		$seller_message="A transaction was recorded for your digital goods at $sitename.\n\nTransaction status reported by PayPal: Payment complete. Buyer is sent the product.\n\nAction required: None but thank you note is always appreciated to make sure product is received.\n\nTransaction details:\n\n$post_string\n\n";
		$buyer_email_flag=2;
		break;
	case 9;
		//Hantar e-mel kepada penjual
		//Transaction=invalid
		//Payment=na
		//Buyer=na
		//Address=na
		$seller_subject="Invalid transaction notice from $sitename";
		$seller_message="A transaction was recorded for your digital goods at $sitename.\n\nTransaction status reported by PayPal: Invalid transaction. Buyer did NOT get the product.\n\nAction required: None\n\nTransaction details:\n\n$post_string\n\n";
		$buyer_email_flag=0;
		break;
}
if(mail($paypal_receiver_email,$seller_subject,$seller_message,$debug_headers))
{
	if($buyer_email_flag>0)
	{
		if($seller_item_number)
		{
			$query2="SELECT item_dwld_url,item_title FROM store_items_tab WHERE ros_ic = $ros_ic AND item_id = $seller_item_number AND item_category = 'Digital goods'";
		}
		else
		{
			$query2="SELECT item_dwld_url,item_title FROM store_items_tab WHERE ros_ic = $ros_ic AND item_id = $item_number AND item_category = 'Digital goods'";
		}
		 
		if($res2=mysql_query($query2))
		{
			$items_data=mysql_fetch_array($res2);
			$buyer_subject=$items_data[1];
			if($buyer_email_flag==2)
			{
				$buyer_message=$items_data[0];
			}
			else
			{
				$buyer_message="Thank you for your payment. We are unable to give you instant access to the product at this stage because PayPal's Instant Payment Notification (IPN) system has replied to your payment with status 'PENDING'. This does NOT mean your payment has failed. This only means that you will receive the product after payment is cleared by PayPal. If you have any questions please contact the seller at the seller email provided to you by PayPal.";
			}
			$date = date("D, j M Y H:i:s O"); 
      $crlf = "\n";
      $buyer_headers = "From: $paypal_receiver_email" .$crlf;
      $buyer_headers .= "Reply-To: $paypal_receiver_email" .$crlf;
      $buyer_headers .= "Return-Path: $paypal_receiver_email" .$crlf;
      $buyer_headers .= "X-Mailer: Perl-Studio" .$crlf;
      $buyer_headers .= "Date: $date" .$crlf; 
      $buyer_headers .= "X-Sender-IP: $REMOTE_ADDR" .$crlf; 
			mail($payer_email,$buyer_subject,$buyer_message,$buyer_headers);
		}
	}
}

dbclose($link);
exit;

/* ======================================================================== */
/*      menambah IPN pangkalan data kod update anda di sini */
function  update_paypal_ipn_table($response_type){
  
}
?>

