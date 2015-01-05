<?php
class gifts {
	function index() {
	
	$getgifts = mysql_query("select * from ".tb()."gifts");
		section_content('
		<style>
		ul.gifts {
	display: block;
	overflow: hidden;
	height: 100%;
	padding: 5px;
}
ul.gifts li {
	display: block;
	padding: 3px;
	margin: 3px;
	float: left;
	width: 125px;
	text-align:center;
	
	
}
ul.gifts li span {
	display: block;
	font-size: 14px;
	font-weight: bold;
}
</style>
<ul class="gifts">');
		while($gift = mysql_fetch_array($getgifts)){
		section_content('<li>
		<form method="post" action="index.php?p=gifts/sendto">
		<input type="hidden" name="giftid" value="'.$gift['id'].'">
		<input type="image" src="modules/gifts/gifts/'.$gift['gift_image'].'" height="64" width="64" border="0">
		</form><strong>'.$gift['gift_name'].'</strong>
		</li>');
		}
		section_content('</ul>');
		section_close('Please select a gift to send...');
	}
	
	function sendto(){
	global $client;
	if($_POST){
	$getgift = mysql_query('select * from '.tb().'gifts where id = "'.$_POST['giftid'].'"');
	$gift = mysql_fetch_array($getgift);
	section_content('<script type="text/javascript" src="modules/gifts/dropdowncontent.js"></script>
	<form action="index.php?p=gifts/sendgift" method="post"><table align="center">
	<tr><td align="center">
	<img src="modules/gifts/gifts/'.$gift['gift_image'].'" height="64" width="64"/>
	<a href="#" id="contentlink" rel="subcontent2">Add Message</a>
	<DIV id="subcontent2" style="position:absolute; visibility: hidden; width: 350px; height: 120px; padding: 4px;">
        <textarea id="giftmsg" name="giftmsg" style="width: 340px; height: 110px;"></textarea>
        </DIV>
        <script type="text/javascript">
        //Call dropdowncontent.init("anchorID", "positionString", glideduration, "revealBehavior") at the end of the page:

         dropdowncontent.init("contentlink", "right-bottom", 300, "click")

        </script>
	</td></tr>
	<tr><td>
	<p style="height: 100px; width:300px; overflow: auto; border: 5px solid #eee; background: #eee; color: #000; margin-bottom: 1.5em;">
	');
	
	$res = sql_query("select f.fid,u.username from ".tb()."friends as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$client['id']}' order by u.username asc");
	while ($friend = sql_fetch_array($res)) {
	section_content('<label><input type="checkbox" name="giftuser[]" value="'.$friend['fid'].'"> '.$friend['username'].'</label><br />');
	}
	
section_content('</p>
<input type="hidden" name="giftid" value="'.$_POST['giftid'].'" />
<input type="submit" name="submit" value="Send Gifts" />
</td></tr>
</table></form>');
section_close('Please select a friend to recieve the gift...');

	}else{
	redirect('gifts');
	}
	
	}
	
	function sendgift(){
	global $client;
	if($_POST){
	$giftid = $_POST['giftid'];
	$giftfrom = $client['id'];
	$giftmsg = mysql_real_escape_string($_POST['giftmsg']);
	
	foreach ( $_POST['giftuser'] as $giftto ) {
	$insert = mysql_query("INSERT INTO `".tb()."sent_gifts` (`giftid`, `giftto`, `giftfrom`, `giftmsg`) VALUES ('".$giftid."', '".$giftto."', '".$giftfrom."', '".$giftmsg."')");
	
	$msg = 'You have recieved a new <a href="index.php?p=gifts/mygifts">gift!</a>';
	send_note($giftto, $msg);
	$gettoinfo = mysql_query("select * from ".tb()."accounts where id = ".$giftto."");
	$toinfo = mysql_fetch_array($gettoinfo);
	
	$giftlist = section_content('<li><strong>'.$toinfo['username'].'</strong></li>');
	}
	    sys_notice("Your gifts have been sent to:
	    <ul>".$giftlist."</ul> <meta http-equiv='refresh' content='5;url=index.php?p=gifts'>");

	}else{
	redirect('gifts');
	}
	
	}
	
	function mygifts(){
	global $client;
	
	section_content('<table class="stories" cellspacing="1">');
	$getgifts = mysql_query('select * from '.tb().'sent_gifts where giftto = '.$client['id'].' order by recieved asc');
	if(!mysql_num_rows($getgifts)){
	sys_notice('You have no gifts!');
	}
	while($gifts = mysql_fetch_array($getgifts)){
	
		$getgiftinfo = mysql_query('select * from '.tb().'gifts where id = '.$gifts['giftid'].'');
                $gift = mysql_fetch_array($getgiftinfo);
                $getsender = mysql_query('select * from '.tb().'accounts where id = '.$gifts['giftfrom'].'');
                $sender = mysql_fetch_array($getsender);
                if($gifts['recieved'] == 0){
	section_content('<tr class="row1">
			<td><img src="modules/gifts/gifts/'.$gift['gift_image'].'" style="float:left;" height="64" width="64"/> 
			<span valign="top"  style="font-weight:bold;"><a href="index.php?p=u/'.$sender['username'].'"><strong>'.$sender['username'].'</strong></a> has sent you a '.$gift['gift_name'].'</span>
			<span style="float:right;"><strong><a href="index.php?p=gifts/acceptgift&id='.$gifts['id'].'">Accept</a> | <a href="index.php?p=gifts/ignoregift&id='.$gifts['id'].'">Ignore</a></strong></span>
			<div><font size="1">'.$gifts['giftmsg'].'</font></div>

			</td>
			</tr>');
			}else{
			section_content('<tr class="row1">
			<td><img src="modules/gifts/gifts/'.$gift['gift_image'].'" style="float:left;"/> 
			<span valign="top"><a href="index.php?p=u/'.$sender['username'].'"><strong>'.$sender['username'].'</strong></a> has sent you a '.$gift['gift_name'].'</span>
			<span style="float:right;" valign="middle"><a href="index.php?p=gifts/ignoregift&id='.$gifts['id'].'">x</a></span>
			<div><font size="1">'.$gifts['giftmsg'].'</font></div>
			</td>
			</tr>');
			}
	}
	section_content('</table>');
	
	}
	function acceptgift(){
	global $client;
	if($_GET['id']){
	$accept = mysql_query("UPDATE `".tb()."sent_gifts` SET `recieved` = '1' WHERE `id` = '".$_GET['id']."'");
        if($accept){
        sys_notice("Gift Accepted! <meta http-equiv='refresh' content='2;url=index.php?p=gifts/mygifts'>");
        }else{
        sys_notice("There was an error accepting your gift!! <meta http-equiv='refresh' content='2;url=index.php?p=gifts/mygifts'>");
        }
	}else{
	redirect('gifts/mygifts');
	}
	}
	
	function ignoregift(){
	global $client;
	if($_GET['id']){
	$ignore = mysql_query('DELETE FROM '.tb().'sent_gifts where id ='.$_GET['id'].'');
        if($ignore){
        sys_notice("Gift Ignored! <meta http-equiv='refresh' content='2;url=index.php?p=gifts/mygifts'>");
        }else{
        sys_notice("There was an error ignoring your gift!! <meta http-equiv='refresh' content='2;url=index.php?p=gifts/mygifts'>");
        }
	}else{
	redirect('gifts/mygifts');
	}
	}
	
	function admin(){
	section_content("
<form action=\"index.php?p=gifts/newgift\" method=\"post\"
enctype=\"multipart/form-data\">
<table><tr>
<td><label for=\"title\">Gift name:</label></td>
<td><input type=\"text\" name=\"title\"/></td>
</tr><tr>
<td><label for=\"file\">Gift image:</label></td>
<td><input type=\"file\" name=\"file\" id=\"file\" /> </td>
</tr><tr>
<td></td>
<td><input type=\"submit\" name=\"submit\" onClick=\"TestFileType(this.form.uploadfile.value, ['gif', 'jpg', 'png', 'jpeg']);\" value=\"Submit\" /></td>
</tr>
</table>
</form>");
section_close('Add New Gift');
$giftinfo = sql_query('SELECT * FROM '.tb().'gifts');

section_content("
<form action=\"index.php?p=gifts/removegift\" method=\"post\">
<table><tr>
<td><label for=\"remove_cat\">Remove Remove Gift:</label></td>
<td><select name=\"removegift\">");
while($info = mysql_fetch_array($giftinfo)){
section_content("<option value=\"".$info['id']."\">".$info['gift_name']."</option>");
}
section_content("</select>
<input type=\"submit\" name=\"submit\" value=\"Remove\" /></td>
</tr>
</table>
</form>");
section_close('Remove Gift');
	}
	
	function newgift(){
	global $client;
if(!$_POST['title']){
sys_notice("please fill all fields! <meta http-equiv='refresh' content='2;url=index.php?p=gifts/admin'>");

}
//SET FILE SIZE LIMIT
if ($_FILES["file"]["size"] < 500000)
  {
  if ($_FILES["file"]["error"] > 0)
    {
    sys_notice("Return Code: " . $_FILES["file"]["error"] . "<br />");
    }
  else
    {
    $insert = mysql_query("INSERT INTO `".tb()."gifts` (`gift_image`, `gift_name`) VALUES ('".$_FILES["file"]["name"]."', '".$_POST['title']."')");
    sys_notice("Added new gift Successfully! <meta http-equiv='refresh' content='2;url=index.php?p=gifts/admin'>");

    if (file_exists("modules/gifts/gifts/" . $_FILES["file"]["name"]))
      {
      sys_notice($_FILES["file"]["name"] . " already exists. <meta http-equiv='refresh' content='2;url=index.php?p=gifts/admin'>");
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "modules/gifts/gifts/" . $_FILES["file"]["name"]);
      
      }
    }
  }
else
  {
  sys_notice("Invalid file");
  }
	}
	
	function removegift(){
	global $client;

if($_POST){
$id = $_POST['removegift'];
$giftinfo = sql_query('SELECT * FROM '.tb().'gifts where id ='.$id.'');
$info = mysql_fetch_array($giftinfo);

$getfile = "modules/gifts/gifts/".$info['gift_image']."";
$remove = unlink($getfile);
$remove = mysql_query('DELETE FROM '.tb().'gifts where id ='.$id.'');

if($remove){
sys_notice("<b>".$info['gift_name']."</b> successfully removed! <meta http-equiv='refresh' content='2;url=index.php?p=gifts/admin'>");
}else{
sys_notice("There was an error when removing <b>".$info['gift_name']."</b> <meta http-equiv='refresh' content='2;url=index.php?p=gifts/admin'>");
}

}else{
sys_notice("You cant access this file directly! <meta http-equiv='refresh' content='2;url=index.php?p=gifts/admin'>");
}
	}

	
}
?>