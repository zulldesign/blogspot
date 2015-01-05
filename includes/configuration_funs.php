<?php
function get_web_contents($url)
{
	$arr = parse_ini_file(dirname(__FILE__)."/../includes/configuration.ini");
	switch($arr["remote_access"])
	{
		case "fopen":
			$file_handle = fopen ( $url, "r" );
			if (! $file_handle) 
			{
				return "";
			}
			$file_contents = "";
			while ( ! feof ( $file_handle ) ) {
				$file_contents .= fread ( $file_handle, 8192 );
			}
			fclose ( $file_handle );
			return $file_contents;
			break;
		case "curl":
			$ch = curl_init();
			$timeout = 5; // set to zero for no timeout
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file_contents = curl_exec($ch);
			curl_close($ch);
			return $file_contents;
			break;
		default:
			die("Invalid configuration value for remote access: '".$arr["remote_access"]."'");
			break;
	}
}
?>