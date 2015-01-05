<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */


class browse{
	
	function browse() {
		global $sub_menu, $ss, $client, $menuon, $current_sub_menu, $tab_menu;
		$menuon = 'browse';
		do_auth( explode('|',get_gvar('permission_browse')) );
		nav(url('browse',t('Browse')));
		nav('Members');
		set_title('Browse');
	}
	
	function index($filter = '') {
		$locations = explode("\r\n",get_text('locations'));
		$current_sub_menu['href'] = 'browse/index';
		if (!preg_match("/^[0-9a-z_]+$/i",$filter)) {
			$filter = '';
		}
		global $num_per_page,$offset,$page,$client, $current_sub_menu;
		$page = $_POST['page'];
		if (!$page) $page = 1;
		if (is_numeric($_POST['age_from']))
			$age_from = $_POST['age_from'];
		else
			$age_from = 18;
		if (is_numeric($_POST['age_to']))
			$age_to = $_POST['age_to'];
		else
			$age_to = 60;
		if ($_POST['gender'] == 'male')
			$gender_male = 'checked';
		elseif ($_POST['gender'] == 'female')
			$gender_female = 'checked';
		else
			$gender_both = 'checked';
		if ($_POST['orderby'] == 0)
			$orderby_0 = 'checked';
		elseif ($_POST['orderby'] == 1)
			$orderby_1 = 'checked';
		else
			$orderby_2 = 'checked';
		section_content('
					<div id="user_filter" style="background:#F7F7F7;border:#E3E3E3 1px solid;overflow:hidden;padding:5px;">

					<form action="'.url('browse/index').'" method="post">
					<div style="width:150px;padding-top:20px;float:right;">
					<input type="submit" class="fbutton" value="'.t('Update').'" />
					</div>
					'.t('Gender').': 
					<input type="radio" name="gender" value="male" '.$gender_male.' />'.t('Male').'
					<input type="radio" name="gender" value="female" '.$gender_female.' />'.t('Female').'
					<input type="radio" name="gender" value="both" '.$gender_both.' />'.t('Both').'<br />
					
					'.t('Age').':
					<input type="text" name="age_from" size="2" value="'.$age_from.'" /> ~ <input type="text" name="age_to" size="2" value="'.$age_to.'" /> , 
					
					'.t('Location').':
					<select name="location">
					<option value="0">'.t('All').'</option>');
					foreach($locations as $key=>$location) {
						$selected = '';
						$pkey = $_POST['location'] - 1;
						if ($pkey == $key)
							$selected = 'selected';
						section_content('<option value="'.($key+1).'" '.$selected.' >'.$location.'</option>');
					}
					section_content('</select>');

					// custom fields
					for($i=1;$i<=7;$i++) {
						$col = 'var'.$i;
						$key = 'cf_var'.$i;
						$key2 = 'cf_var_value'.$i;
						$key3 = 'cf_var_des'.$i;
						$key4 = 'cf_var_label'.$i;
						$type = get_gvar($key);
						$value = get_gvar($key2);
						$des = get_gvar($key3);
						$label = get_gvar($key4);
						if ($type != 'disabled') {
							if ($type == 'select_box') {
								$tarr = explode("\r\n",$value);
								section_content('<br />
								'.$label.' 
								<select name="'.$col.'">
								<option value="">'.t('All').'</option>
								');
								foreach ($tarr as $val) {
									$selected = '';
									if (stripslashes($_POST[$col]) == $val)
										$selected = 'selected';
									section_content('<option value="'.$val.'" '.$selected.'>'.$val.'</option>');
								}
								section_content('</select>');
							}
						}
					}
					
					section_content('<br /><input type="hidden" value="1" name="update" />
					'.t('Order by').' 
					<input type="radio" name="orderby"  value="0" '.$orderby_0.' />'.t('Last Login').' 
					<input type="radio" name="orderby" value="1" '.$orderby_1.' />'.t('Registration').' 
					<input type="radio" name="orderby" value="2" '.$orderby_2.' />'.t('Top Followed').'
					</form>
					</div>
			');
		section_close();
		
		section_content('<ul class="small_avatars">');

		if (is_numeric($_POST['age_from']) || is_numeric($_POST['age_to'])) {
			$year_from = date("Y",time()) - $_POST['age_to'];
			$year_to = date("Y",time()) - $_POST['age_from'];
			$where .= ' AND birthyear>'.$year_from.' AND birthyear<'.$year_to;

		}
		if ($_POST['location']) {
			$key = $_POST['location'] - 1;
			if (strlen($locations[$key])) {
				$where .= " AND location='{$locations[$key]}' ";
			}
		}
		if ($_POST['gender'] == 'male') {
			$where .= " AND gender=1 ";
		}
		elseif ($_POST['gender'] == 'female') {
			$where .= " AND gender=0 ";
		}
		if ($_POST['orderby'] == 1) {
			$orderby = 'created';
		}
		elseif ($_POST['orderby'] == 2) {
			$orderby = 'followers';
		}
		else {
			$orderby = 'lastlogin';
		}

		for($i=1;$i<=7;$i++) {
			$col = 'var'.$i;
			$key = 'cf_var'.$i;
			$type = get_gvar($key);
			if ($type == 'select_box') {
				if (strlen($_POST[$col])) {
					$where .= " AND {$col}='{$_POST[$col]}' ";
				}
			}
		}

		$where = ' WHERE 1 '.$where;
		if (!$_POST['page']) $_POST['page'] = 1;
		$offset = ($_POST['page']-1)*$num_per_page;
		$num_per_page++;
		$i = 0;
		$res = sql_query("select * from `".tb()."accounts` $where and !hide_me order by $orderby DESC limit $offset, $num_per_page");
		while ($member = sql_fetch_array($res)) {
			$i++;
			if ($i < $num_per_page) {
				$age = $gender = $local = '';
				if ($member['gender'] != 2) {
					$gender = gender($member['gender']);
				}
				if (!$member['hide_age']) {
					$age = ', '.get_age($member['birthyear']);
				}
				if (!$member['avatar']) {
				$member['avatar'] = 'undefined.jpg';
			}
				section_content('<li><span><a href="'.url('u/'.$member['username']).'" rel="nofollow">'.$member['username'].'</a></span><br />
				<a href="'.url('u/'.$member['username']).'" rel="nofollow">
	<img src="'.uhome().'/'.uploads.'/avatars/s_'.$member['avatar'].'" class="avatar" /></a><br />'
				.$gender
				.$age.'</li>');
			}
		}
		if (!$i) section_content('<li>Sorry, no results</li>');
		section_content('</ul>');
		if ($i == $num_per_page) {
			$page++;
			section_content('<form action="'.url('browse/index').'" method="post">
			<input type="hidden" name="age_from" value="'.h(stripslashes($_POST['age_from'])).'" />
			<input type="hidden" name="age_to" value="'.h(stripslashes($_POST['age_to'])).'" />
			<input type="hidden" name="location" value="'.h(stripslashes($_POST['location'])).'" />
			<input type="hidden" name="gender" value="'.h(stripslashes($_POST['gender'])).'" />

			<input type="hidden" name="var1" value="'.h(stripslashes($_POST['var1'])).'" />
			<input type="hidden" name="var2" value="'.h(stripslashes($_POST['var2'])).'" />
			<input type="hidden" name="var3" value="'.h(stripslashes($_POST['var3'])).'" />
			<input type="hidden" name="var4" value="'.h(stripslashes($_POST['var4'])).'" />
			<input type="hidden" name="var5" value="'.h(stripslashes($_POST['var5'])).'" />
			<input type="hidden" name="var6" value="'.h(stripslashes($_POST['var6'])).'" />
			<input type="hidden" name="var7" value="'.h(stripslashes($_POST['var7'])).'" />

			<input type="hidden" name="page" value="'.$page.'" />
			<div class="hr"></div>
			<input type="submit" value=" '.t('More..').' " />
			</form>');
		}
		section_close();
		

	}
	

}