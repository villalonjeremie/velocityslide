<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'velocity_slide');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '590590Ab');

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
define('AUTH_KEY',         '@Kyrm/>bN)%!9|5>Vr]pO<S&dr2VRf<EzOoFq!.- J5cX|d5|,+/>VBgVm?+&I!j');
define('SECURE_AUTH_KEY',  'E#-* A;62|JH7w76O&6j^IY!yj y)@?|LdwOwI|2nbO>BN9%xr/,C .+IC+;+5>#');
define('LOGGED_IN_KEY',    'V5#gZ=*ykcDoEa{;`d->$(udDFz~AL~j&;h6T2a4PeIZJ&NGMbHt7-]@<m{n:$T$');
define('NONCE_KEY',        '{3m|+|xJZ6d<{ZKs@%?s57lH}A}T9NJ65k0C9dP&FXMihcf|buD-AS4W!~-srpn?');
define('AUTH_SALT',        'Iu7)4+WPM<]yyaP#g6xi/68OjLs#C}<<o{-RUsxo-z+CT+[^Z7t]K!lw{rEjaj T');
define('SECURE_AUTH_SALT', '!&scMJYsW%y++HkM@r[?LpRi<@zKqwtIa!:k,T-5VIExl4PbOY}cm;Wm<l|Y&n3A');
define('LOGGED_IN_SALT',   'Gt{U#h{rk+wVs3ewZRx;XYYo:/I.%XRU.S:{+>Pee> tQS[^)9-z-Ep(SEA0VWoL');
define('NONCE_SALT',       'KB:W/%jy.D&55^KEhj>%[hB]}$|5nM[L`zP)F2s5qZWy$?2Q|0s_P3|-ZSg=LC?R');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY',false);
define('WP_DEBUG_LOG',true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
