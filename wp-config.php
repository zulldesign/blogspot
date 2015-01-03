<?php
use System\Configuration;
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

parse_str(str_replace(';', '&', Configuration\ConfigurationManager::$AppSettings->get_Item("MYSQL_CONNECTION_STRING")));

define('DB_NAME', $database);

/** MySQL database username */
define('DB_USER', $uid);

/** MySQL database password */
define('DB_PASSWORD', $pwd);

/** MySQL hostname */
define('DB_HOST', $server);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '-A|_INpP^&]6UeK0q>nPsd@I?37+1&h3JhSkGwfl^#}GF|2$Hu`:j+E|qNVQ6/lD');
define('SECURE_AUTH_KEY',  'S5OfNV10%|SzU(9=XyPZ]prMOD?g3lFC,<c^s3iauVrQwls]q!7XW<1G?Lx}*{wh');
define('LOGGED_IN_KEY',    ';(!o$<9X&8>,n-+s^+0CY#}0W5X9~^MV|9acO?2<O(ww8o*6||y44Y)P(Xw(*^zV');
define('NONCE_KEY',        '[2>-f!$}=I4S!i]TaQ[oB+-ul.),@E))aUB$ QNqS4Jn<LchGBI*q@Bj+,*!-yPs');
define('AUTH_SALT',        '1l3+U0Ryd4s+ [Ytk8n>@%5 [y$DfJH5q?+;R-P!UIlw0+EP1^4$o:|`H=7|te~^');
define('SECURE_AUTH_SALT', 'SV]WV-AE^+Q51o0!(N?mO1k ;P^dN!~; +4r(0F+IK1at;`,-hb%G,m98HC69&#m');
define('LOGGED_IN_SALT',   'hDXr+/KdHenb4CXAu3i.HamvN8#t5-z+EIzlk!&cZI>b,iqKQ:S9min-nU.}*2pW');
define('NONCE_SALT',       '4IwN83+{oGu<Y/T|&|]Hnur4U`KQoU/T74)F}!BbSjm%mLPO`q115q|;e-)PH{`/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST']);
define('WP_HOME',    'http://' . $_SERVER['HTTP_HOST']);
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
