<?php
require_once './my/config.php';
if (!is_array($db_info)) {
	header("location:install.php");
	exit;
}
include './includes/libs/common.inc.php';
require_once './includes/libs/pages.inc.php';
require_once './includes/libs/story.inc.php';
require_once './includes/libs/db.inc.php';
require_once './includes/captcha/recaptchalib.php';
require_once './includes/libs/misc.inc.php';
require_once './includes/libs/apps.inc.php';