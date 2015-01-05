<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Network Builder.
@copyright	Copyright (C) 2009 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */


$module_title = 'Jcow ';
$module_name = 'admin';
$writable_arr = array('./my/config.php');
require("./install/db_ctrl.php");
$default_lang = 'en';
require("./install/lang/$default_lang/install.php");
$dbtype = 'MySQL';
$homeurl = "http://".$_SERVER['HTTP_HOST'].preg_replace("/\/install\.php/i","",$_SERVER['REQUEST_URI']);
if (preg_match('/localhost/i',$homeurl) || preg_match("/^192\.168/i",$homeurl) || preg_match("/^10\.2\./i",$homeurl) || preg_match("/^127\.0\.0/i",$homeurl)) {
	$testing = 1;
}
// header ----------------------------------------------------------------------------------------------------------------------
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html
	xmlns="http://www.w3.org/1999/xhtml"> 
	
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="install/default.css" rel="stylesheet" type="text/css" media="all" />
<title><?php echo $module_title.' '.$msg['installtion']; ?></title>
</head>
<body>

<div id="logo">
<img src="files/logo_clean.png" />
   <span style="font-size:20px;font-weight:bold">
   Jcow Network Installation</span>
</div>
<div id="content">
<div id="main">
<?php
if (!$_GET['step'] && !$_POST['step']) {
?>

<table width="80%">
<tr><td colspan="2">
<i><?php echo $msg['about']; ?></i>
</td></tr>
<form action="install.php" method="post">
<tr><td colspan="2" class="row1">
Database
</td></tr>
<tr>
<td align="right" class="row2">
<?php echo $msg['dbhost']; ?></td><td class="row2"><input type="text" name="dbhost" value="localhost" /></td>
</tr>
<tr>
<td align="right" class="row2"><?php echo $msg['dbname']; ?></td><td class="row2"><input type="text" name="dbname" /><br />(The database should be existing)</td>
</tr>
<tr>
<td align="right" class="row2"><?php echo $msg['dbuser']; ?></td><td class="row2"><input type="text" name="dbuser" /></td>

</tr>
<tr>
<td align="right" class="row2"><?php echo $msg['dbpass']; ?></td><td class="row2"><input type="password" name="dbpass" /></td>
</tr>



<tr><td colspan="2" class="row1">
Jcow Network Info
</td></tr>
<tr>
<td align="right" class="row2" valign="top">Website URL</td><td class="row2">
	<input type="text" name="uhome" size="40" value="<?php echo $homeurl;?>" /></td>
</tr>
<tr><td colspan="2" class="row1">
Create an account to manage this site
</td></tr>
<tr>
<td align="right" class="row2" valign="top">Email</td><td class="row2">
	<input type="text" name="email" size="40"  /></td>
</tr>

<tr>
<td align="right" class="row2" valign="top">Choose a Password</td><td class="row2">
	<input type="password" name="password" size="20"  /></td>
</tr>

<tr>
<td colspan="2" class="row1">
License
</td>
</tr>

<tr>
<td colspan="2" align="center">
See the <strong>License.txt</strong> in the Jcow package.
</td>
</tr>

<tr>
<td colspan="2" align="center">
<?php
echo '<input type="submit" name="submit" value="'.$msg['begin_install'].'" />';

	?>
<input type="hidden" name="step" value="2" />
<input type="hidden" name="charset" value="<?php echo $_POST['charset']; ?>" />
</td>
</tr>

</form>
</table>

<?php
}
elseif ($_POST['step'] == 2) {
	if (preg_match("/\/$/i",$_POST['uhome'])) $_POST['uhome'] = substr($_POST['uhome'],0,strlen($_POST['uhome'])-1);
	if (!is_writable('./my/config.php')) {
		$error = 1;
		$error_msg[] = 'You need to make the "config.php" writable';
	}
	// check db
	if (!$conn=sql_connect($_POST["dbhost"], $_POST["dbuser"], $_POST["dbpass"], $_POST["dbname"])) {
		$error = 1;
		$error_msg[] = $msg['check_db_info'];
	}

	if (!strlen($_POST['password'])) {
		$error = 1;
		$error_msg[] = 'Please set an Admin password';
	}
	
	$res = sql_query("select * from jcow_accounts limit 1", $conn);
	if ($row = @sql_fetch_array($res)) {
		if ($row['id']) {
			$error = 1;
			$error_msg[] = 'You have installed a Jcow! in the selected database, please choose another database';
		}
	}

	if ($error) {
		echo '<h3>'.$msg['got_errors'].'</h3>';
		echo '<ol>';
		foreach ($error_msg as $val) {
			echo '<li>'.$val.'</li>';
		}
		echo '</ol>';
		?>
		<input type="button" onclick="history.go(-1)" value=" &lt; <?php echo $msg['return']; ?>" />
		<?php
	}

	else {
	// install
		$root_path = 'http://'.$_SERVER['HTTP_HOST'].str_replace('/install.php','',$_SERVER['REQUEST_URI']);
		$ss = get_rand(5);

		// settings.php
		$con_from = array('{db_host}','{db_user}','{db_pass}','{db_name}','{uhome}','{mod}');
		$con_to = array($_POST["dbhost"],$_POST["dbuser"],$_POST["dbpass"],$_POST["dbname"],$_POST['uhome'],$_POST['test_mod']);
		write_config($con_from,$con_to,'./install/config.php', './my/config.php');

		
		// config.php
		/*
		$con_from = array('{libs}','{module_name}');
		$con_to = array($_POST["libs"],$module_name);
		write_config($con_from,$con_to,'./install/config.php','./config.php');
		*/

		// import db
		print('Importing database ..<br />');
		$db_source = './install/data.sql';
		import_sql($db_source);
		// insert administrator account
		$password = md5($_POST['password'].'jcow');
		$timeline = time();
		sql_query("insert into `jcow_accounts` (roles,gender,birthyear,hide_age,password,email,username,fullname,created,lastlogin) values(3,1,1990,1,'$password','".$_POST['email']."','admin','admin',$timeline,$timeline)", $conn);
		$uid = mysql_insert_id();
		sql_query("insert into `jcow_pages` (uid,uri,type) values($uid,'admin','u')",$conn);

	?>
<table width="80%">
<tr><td >
<i><?php echo $msg['about']; ?></i>
</td></tr>
<tr>
<td>
Congratulations! You have installed Jcow Network successfully! <br />
<span style="color:red">Delete the file: <strong>install.php</strong></span><br />
<br />
You can now login to your Network with the admin account:
<div style="font-size:18px">
<strong>Username:</strong> admin<br />
<strong>Password:</strong> <i>[the password you've just submitted]</i><br />
<a href="<?php echo $_POST['uhome'];?>/index.php">Go to your Network</a>
</div>
</td>
</tr>
</table>

	<?php
		} // end of install
} // end of step2
// footer ------------------------------------------------------------------------------------------------------------
?>
</div>
</div>
<div id="footer"> &copy; Jcow.net<br />
</body>
</html>

<?php



// ######################### FUNCTIONS ###########


function check_writable($path) {
     if (!($f = @fopen($path, 'w+')))
        return false;
	 else {
	     fclose($f);
		 return true;
	 }
}
function write_config($from, $to, $source, $target) {
	$config_data = fread(fopen($source, 'r'), filesize($source));
	$config_data = str_replace($from, $to, $config_data);
	$fp = fopen($target,"w");
	fwrite($fp,$config_data);
	fclose($fp);
}

function import_sql($sql_file) {
	GLOBAL $conn,$root_path;
	if (!$_POST["dbpass"])
		$_POST["dbpass"] = "";
	$sql_query = fread(fopen($sql_file, 'r'), filesize($sql_file));
	$sql_query = remove_remarks($sql_query);
	$pieces = split_sql_file($sql_query, ";");

	$sql_count = count($pieces);
	for($i = 0; $i < $sql_count; $i++) {
		$sql = trim($pieces[$i]);

		if(!empty($sql) and $sql[0] != "#") {
			$res = sql_query($sql,$conn);
		}
	}
	
}

function remove_remarks($sql)
{
	$lines = explode("\n", $sql);
	
	// try to keep mem. use down
	$sql = "";
	
	$linecount = count($lines);
	$output = "";

	for ($i = 0; $i < $linecount; $i++)
	{
		if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0))
		{
			if ($lines[$i][0] != "#")
			{
				$output .= $lines[$i] . "\n";
			}
			else
			{
				$output .= "\n";
			}
			// Trading a bit of speed for lower mem. use here.
			$lines[$i] = "";
		}
	}
	
	return $output;
	
}

//

//
function split_sql_file($sql, $delimiter)
{
	// Split up our string into "possible" SQL statements.
	$tokens = explode($delimiter, $sql);

	// try to save mem.
	$sql = "";
	$output = array();
	
	// we don't actually care about the matches preg gives us.
	$matches = array();
	
	// this is faster than calling count($oktens) every time thru the loop.
	$token_count = count($tokens);
	for ($i = 0; $i < $token_count; $i++)
	{
		// Don't wanna add an empty string as the last thing in the array.
		if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0)))
		{
			// This is the total number of single quotes in the token.
			$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
			// Counts single quotes that are preceded by an odd number of backslashes, 
			// which means they're escaped quotes.
			$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
			
			$unescaped_quotes = $total_quotes - $escaped_quotes;
			
			// If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
			if (($unescaped_quotes % 2) == 0)
			{
				// It's a complete sql statement.
				$output[] = $tokens[$i];
				// save memory.
				$tokens[$i] = "";
			}
			else
			{
				// incomplete sql statement. keep adding tokens until we have a complete one.
				// $temp will hold what we have so far.
				$temp = $tokens[$i] . $delimiter;
				// save memory..
				$tokens[$i] = "";
				
				// Do we have a complete statement yet? 
				$complete_stmt = false;
				
				for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++)
				{
					// This is the total number of single quotes in the token.
					$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
					// Counts single quotes that are preceded by an odd number of backslashes, 
					// which means they're escaped quotes.
					$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
			
					$unescaped_quotes = $total_quotes - $escaped_quotes;
					
					if (($unescaped_quotes % 2) == 1)
					{
						// odd number of unescaped quotes. In combination with the previous incomplete
						// statement(s), we now have a complete statement. (2 odds always make an even)
						$output[] = $temp . $tokens[$j];

						// save memory.
						$tokens[$j] = "";
						$temp = "";
						
						// exit the loop.
						$complete_stmt = true;
						// make sure the outer loop continues at the right point.
						$i = $j;
					}
					else
					{
						// even number of unescaped quotes. We still don't have a complete statement. 
						// (1 odd and 1 even always make an odd)
						$temp .= $tokens[$j] . $delimiter;
						// save memory.
						$tokens[$j] = "";
					}
					
				} // for..
			} // else
		}
	}

	return $output;
}

function get_rand($length) { 
	srand((double)microtime()*1000000);
    $possible = "0123456789". 
    "abcdefghijklmnopqrstuvwxyz". 
    "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
    $str = ""; 
    while(strlen($str) < $length) { 
        $str .= substr($possible, rand(0,50), 1); 
        } 
    return($str); 
} 

?>