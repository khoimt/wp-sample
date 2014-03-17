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
define('DB_NAME', 'wp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'abc123');

/** MySQL hostname */
define('DB_HOST', 'wp.local.com');

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
define('AUTH_KEY',         '-K_ve`g;qfL,qb]M_*md[22Gr6N[uz5|1`x5|Mhn#y;u1{9 0{hh8FH_8}6_X>Kn');
define('SECURE_AUTH_KEY',  ')zn#QwDb.6|NA=xqwSZ% Tv*2tE8H^TM2Z`J`Kn,.(p&xR[f&4v%aO}DtQF<qcQ<');
define('LOGGED_IN_KEY',    '>(k9+cd+b654Z#|?~;`O+bEl5oA+$+qbzh_@_e>|{p9iSr+&f:RAG8RiVT}8Qg@&');
define('NONCE_KEY',        '4kKwn-.g+:o_W->*7*+gT-gumH&d;x_GOXHU/A]|9+? :n?]Z*H*U eZX*9~:CdJ');
define('AUTH_SALT',        '.l.m-i%6.]~+pfuuD,4ftKm2fVt}JT-QF}-KoZpPmAf-9*dN^HV{ZUD6EE9S(l`&');
define('SECURE_AUTH_SALT', ']hhiYs)5gK+WlH$!h*$-{1XpzfOb,S5$-8MTv&;i;EH+@hk9~{g5Cp5w6Jg11+mU');
define('LOGGED_IN_SALT',   'BUr$la`+q4~JTp0[rLF?l4#v Do>kbLuHcTFA77+fx;sDoI%}i]gw.sl8YXKIa!x');
define('NONCE_SALT',       'NFi4b/=|Wy<++J![Dx;8r=*m$=1c^Tkq!@l_kw8|bzOThm&vXDMNuv}T9s+%P|6Q');

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
