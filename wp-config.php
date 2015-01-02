<?php
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
define('DB_NAME', 'db02904178ec5b421c9b5ba413016683fd');

/** MySQL database username */
define('DB_USER', 'zumrrboatotompbh');

/** MySQL database password */
define('DB_PASSWORD', 'rB2GEWYMksRVSwdQk43R62fuAgb5uwfwcqrnZGzh6YDvuUrwDdxGSuvywFfyV4rV');

/** MySQL hostname */
define('DB_HOST', '02904178-ec5b-421c-9b5b-a413016683fd.mysql.sequelizer.com');

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
define('AUTH_KEY',         '|-Q(Z_-~?jFiR&|01#7sV?-8T<=<TAk}~TYCcx&iOL)9h^H 5/p`ej63(s>*=WQT');
define('SECURE_AUTH_KEY',  'MU0U6# cN>0a.XhKO=)u1ao]Tvfj?`YKXCQ9d~jz@W8Hu1n-t3]){/uu+1>|r&?x');
define('LOGGED_IN_KEY',    'tzS|sY%^xPoaXvbUd3=e?n=U|x%Sd[|KR3lm>vD-j6|]A*1tUmH/e=D8$> };D>8');
define('NONCE_KEY',        'Ea7=A$6h61OF48uuiCJX-ImDM{&_AD3b1ZeIWVh$1BvG=t^*3;$KXlh&]fH#Il_S');
define('AUTH_SALT',        'Q}FCb?$|aEP$P|UJ*M+)-g1-O;$p-s2x%=BMAgB%eVVqU`V=A@Rr[*91=-FiZv,|');
define('SECURE_AUTH_SALT', '`%YRZA_fLzL_#j?fyHNNK@}-X )C7UG|nL=S;rVFdmT4.y+W}CB`RL6C|c!D?TTp');
define('LOGGED_IN_SALT',   '_~6)4|cI/b;s<6s@#X^nA{&8s_hC)r(&.^G]^{73+Admde+ER%2k5e=,cO>,<~pc');
define('NONCE_SALT',       'vqWQVM{%RS=m+vSj9ChbMWuk$D/Uok,=n*E.PE&W7BS%q0-Wi]bppoObTATJU|sO');

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
