<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'knowledgeconvey');

/** MySQL database username */
define('DB_USER', 'knowledgeuser');

/** MySQL database password */
define('DB_PASSWORD', 'knowledgeconvey_bd#');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'ttT2`pN[<#=%}=R9ckx&$iLd8fvPASq8m6)KSd<2/xY49R&&+;N|(hqFX~3;=+v9');
define('SECURE_AUTH_KEY',  '=$9*qpVQL$O3IH?^4nidZ[ti/l)<q!!4uXDTzh!+YLL~*tNqh}/4ai}sUMoYDPgv');
define('LOGGED_IN_KEY',    'Q+{2 *(!-+2iW,(J7W8z[_=<NXy5mm*H@3lZT7Cv/p@Ez{!d_R@PWcY;-X+f_2!}');
define('NONCE_KEY',        'Cj_E4]Oav{Tzn<G/3n<kCw?XzdmZ2RHTRkbk4OHP:})U}/#)osJ9Y+W).iO4p-YA');
define('AUTH_SALT',        'RG{Tj)&x$E{Z|B%Wgt+Pt. (v3HKHCt7.EMrj|PH]I0ihNw)XJ>xuVB?IVW#p3 -');
define('SECURE_AUTH_SALT', '2j#-38,{(jW&YD>^7 0TNtm33GvUQb _$+7ZJggC6.7HWc~7s<S?rOV`izdX~9?G');
define('LOGGED_IN_SALT',   'j-Y/o;o+gbMKS;W~ZWlOjq+SzX[}V;}~h<m%ZQ1::*Lz7hlw_F^l;;U.B#R({zu^');
define('NONCE_SALT',       '08f$V83,hgG^.`8I~KsRP|zBl5.OAn^~v7B+KJf_,WBe#qb-EQ!$Qt3=|zng7_+ ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
