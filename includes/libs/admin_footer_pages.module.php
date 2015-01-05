<?php
if (basename($_SERVER["SCRIPT_NAME"]) != 'index.php') die(basename($_SERVER["SCRIPT_NAME"]));
nav('Footer Pages');
if (!$step) {
	c('<ul>');
	$res = sql_query("select * from `".tb()."footer_pages` order by weight");
	while ($page = sql_fetch_array($res)) {
		c('<li class="cat">'.$page['link_name'].' <span class="sub">'.
		url('admin/footer_pages/delete/'.$page['id'],t('Delete')).' '.
		url('admin/footer_pages/modify/'.$page['id'],t('Modify')).' '.
		url('admin/footer_pages/moveup/'.$page['id'],t('MoveUp')).' '.
		url('admin/footer_pages/movedown/'.$page['id'],t('MoveDown')).'</span></li>');
	}
	c('</ul>');
	section_close('Footer Pages');

	c('
	<form action="'.url('admin/footer_pages/add').'" method="post">
	<p>'.label('Link Title (Required)').'
			<input type="text" name="link_name" size="60" />
			</p>
	<p>'.label('Page Title (Required)').'
			<input type="text" name="name" size="60" />
			</p>
			<p>
			'.label('Page Content'));
	if (file_exists('js/tiny_mce/jquery.tinymce.js')) {
			c(
				story::tinymce_form().'
	<textarea name="form_content" rows="18" style="width:580px" class="rich" ></textarea><br />
	If there is only a URL(starting by <strong>http://</strong> or <strong>https://</strong>) in the content, the page will be redirected to that URL automatically.');
		}
		else {
			c('
	<textarea name="form_content" rows="18" style="width:580px" class="rich" ></textarea><br />
	If there is only a URL(starting by <strong>http://</strong> or <strong>https://</strong>) in the content, the page will be redirected to that URL automatically.
	');
		}
		c('
			</p>
			<p>
			<input type="submit" class="button" value="'.t('Submit').'" />
			</p>
			</form>
	');
	section_close('Create a new page');
	
}
if ($step == 'add') {
	$res = sql_query("select max(weight) as weight from `".tb()."footer_pages`");
	$row = sql_fetch_array($res);
	$weight = $row['weight'] + 1;
	$q = "insert into `".tb()."footer_pages` (name,link_name,content,weight) values ('".$_POST['name']."','".$_POST['link_name']."','".$_POST['form_content']."',$weight)";
	$res = sql_query($q);
	redirect('admin/footer_pages',1);
}
if ($step == 'delete') {
	sql_query("delete from `".tb()."footer_pages` where id='$id'");
	redirect('admin/footer_pages',1);
}
if ($step == 'moveup') {
	$res = sql_query("select * from `".tb()."footer_pages` where id='$id'");
	$cat = sql_fetch_array($res);
	$res = sql_query("select * from `".tb()."footer_pages` where weight<{$cat['weight']} order by weight desc limit 1");
	$tcat = sql_fetch_array($res);
	if ($tcat['id']) {
		sql_query("update `".tb()."footer_pages` set weight={$tcat['weight']} where id='$id'");
		sql_query("update `".tb()."footer_pages` set weight={$cat['weight']} where id={$tcat['id']}");
	}
	redirect('admin/footer_pages',1);
}
if ($step == 'movedown') {
	$res = sql_query("select * from `".tb()."footer_pages` where id='$id'");
	$cat = sql_fetch_array($res);
	$res = sql_query("select * from `".tb()."footer_pages` where weight>{$cat['weight']} order by weight limit 1");
	$tcat = sql_fetch_array($res);
	if ($tcat['id']) {
		sql_query("update `".tb()."footer_pages` set weight={$tcat['weight']} where id='$id'");
		sql_query("update `".tb()."footer_pages` set weight={$cat['weight']} where id={$tcat['id']}");
	}
	redirect('admin/footer_pages',1);
}
if ($step == 'modify') {
	$res = sql_query("select * from `".tb()."footer_pages` where id='$id'");
	$page = sql_fetch_array($res);
	c('
	<form action="'.url('admin/footer_pages/modifypost').'" method="post">
	<p>
	'.label('Link title').'
	<input type="text" name="link_name" value="'.htmlspecialchars($page['link_name']).'" />
	</p>
	<p>
	'.label('Page title').'
	<input type="text" name="name" value="'.htmlspecialchars($page['name']).'" />
	</p>
	<p>
			'.label('Page Content'));
	if (file_exists('js/tiny_mce/jquery.tinymce.js')) {
			c(
				story::tinymce_form().'
	<textarea name="form_content" rows="18" style="width:580px" class="rich" >'.htmlspecialchars($page['content']).'</textarea>');
		}
		else {
			c('
	<textarea name="form_content" rows="18" style="width:580px" class="rich" >'.htmlspecialchars($page['content']).'</textarea>
	');
		}
		c('
			</p>
			<p>
					<input type="submit" class="button" value="'.t('Save changes').'" />
					<input type="hidden" name="id" value="'.$page['id'].'" />
					</p>
			</form>
			');
}
if ($step == 'modifypost') {
	sql_query("update `".tb()."footer_pages` set name='".$_POST['name']."',link_name='".$_POST['link_name']."',content='".$_POST['form_content']."' where id=".$_POST['id']);
	redirect('admin/footer_pages',1);
}