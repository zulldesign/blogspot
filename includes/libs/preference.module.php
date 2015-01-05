<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class preference{
	function preference() {
		set_title('Preference');
	}
	function index() {
		global $client,$timezone;
		$timezones = array(
			array('value'=>-12,'flag'=>'(GMT -12:00) Eniwetok, Kwajalein'),
			array('value'=>-11,'flag'=>'(GMT -11:00) Midway Island, Samoa'),
			array('value'=>-10,'flag'=>'(GMT -10:00) Hawaii'),
			array('value'=>-9,'flag'=>'(GMT -9:00) Alaska'),
			array('value'=>-8,'flag'=>'(GMT -8:00) Pacific Time (US &amp; Canada)'),
			array('value'=>-7,'flag'=>'(GMT -7:00) Mountain Time (US &amp; Canada)'),
			array('value'=>-6,'flag'=>'(GMT -6:00) Central Time (US &amp; Canada), Mexico City'),
			array('value'=>-5,'flag'=>'(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima'),
			array('value'=>-4,'flag'=>'(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz'),
			array('value'=>-3.5,'flag'=>'(GMT -3:30) Newfoundland'),
			array('value'=>-3,'flag'=>'(GMT -3:00) Brazil, Buenos Aires, Georgetown'),
			array('value'=>-2,'flag'=>'(GMT -2:00) Mid-Atlantic'),
			array('value'=>-1,'flag'=>'(GMT -1:00 hour) Azores, Cape Verde Islands'),
			array('value'=>0,'flag'=>'(GMT) Western Europe Time, London, Lisbon, Casablanca'),
			array('value'=>1,'flag'=>'(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris'),
			array('value'=>2,'flag'=>'(GMT +2:00) Kaliningrad, South Africa'),
			array('value'=>3,'flag'=>'(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg'),
			array('value'=>3.5,'flag'=>'(GMT +3:30) Tehran'),
			array('value'=>4,'flag'=>'(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi'),
			array('value'=>4.5,'flag'=>'(GMT +4:30) Kabul'),
			array('value'=>5,'flag'=>'(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent'),
			array('value'=>5.5,'flag'=>'(GMT +5:30) Bombay, Calcutta, Madras, New Delhi'),
			array('value'=>5.75,'flag'=>'(GMT +5:45) Kathmandu'),
			array('value'=>6,'flag'=>'(GMT +6:00) Almaty, Dhaka, Colombo'),
			array('value'=>7,'flag'=>'(GMT +7:00) Bangkok, Hanoi, Jakarta'),
			array('value'=>8,'flag'=>'(GMT +8:00) Beijing, Perth, Singapore, Hong Kong'),
			array('value'=>9,'flag'=>'(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk'),
			array('value'=>9.5,'flag'=>'(GMT +9:30) Adelaide, Darwin'),
			array('value'=>10,'flag'=>'(GMT +10:00) Eastern Australia, Guam, Vladivostok'),
			array('value'=>11,'flag'=>'(GMT +11:00) Magadan, Solomon Islands, New Caledonia'),
			array('value'=>12,'flag'=>'(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka')
			);


		c('
		<form action="'.url('preference/post').'" method="post">
		'.label(t('Timezone')).'
		<select name="new_timezone">');
		foreach($timezones as $arr) {
			if ($client['timezone'] == $arr['value']) {
				$arr['selected'] = 'selected';
			}
			c('<option value="'.$arr['value'].'" '.$arr['selected'].'>'.$arr['flag'].'</option>');
		}
		c('</select>
		<p><input type="submit" value="'.t('Save changes').'" /></p>
		</form>');
	}

	function post() {
		global $client, $timezone;
		setcookie('timezone', $_POST['new_timezone'], time()+3600*24*365,'/');
		redirect('home',1);
	}

}