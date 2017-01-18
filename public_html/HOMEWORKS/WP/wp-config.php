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

// define( 'WP_SITEURL',   'http://localhost/homeworks/public_html/HOMEWORKS' );
// define( 'WP_HOME',  'http://localhost/homeworks/public_html/HOMEWORKS' );
 
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'homewot5_wpsite');

define( 'WP_MEMORY_LIMIT', '512M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );

/** MySQL database username */
define('DB_USER', 'homewot5_admin');

/** MySQL database password */
define('DB_PASSWORD', '2012homeworksbooks');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'h]JlPicf}lY#^9_nyn7{h}]]AF,u{ONmPz@Y#yNorB&JIQ.tgw:jJE/?w]M[~L5:');
define('SECURE_AUTH_KEY',  'WQCzaUaDS6Y.|H+KklD|nQY@%(9aa{4DpV=V%%`x8w:O.Hr#f+qDrhz]PEY(p+G/');
define('LOGGED_IN_KEY',    '}D{8|Q+x=||a-xm7([JNJe%$InaTY;08<_|9F=~L^g4%Rn6KVA-<V+;+bBzdSG`m');
define('NONCE_KEY',        '=^LxK+e7*xv[G j^h.OYgMlO7-A0Y=V3i:.Sr?>)K3K{7+^`~jPS=jZBbH/dU~  ');
define('AUTH_SALT',        'N$:8 .%1u4tafD15zW5%} heSvWpYn{XcvR1il8d(PUI0{ac#w+$L{x!FZ81M3%l');
define('SECURE_AUTH_SALT', '0Ic@f_RAFgJu)5%$<D/E!+gO.,Z+9xSO9n?1 &([;kg}2rlA`P^P4~&^<0>|]8*W');
define('LOGGED_IN_SALT',   'GPaYB29buVYn`:`^Y{:A2P$*7tQHIYrmn;4o;@S1ez&xg4MLH=33if9R8$l9Nx~2');
define('NONCE_SALT',       'C3DGR!ZyG%vv#RwZ-eET*`^MtrYDzZ^rK}NIxKGQy#9mG#++v;vxZfJEK6+cTN}`');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'hwb_';

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