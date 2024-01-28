<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'best_choice' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'D{|)nibS^_9!-z?>HtC},j[U:;`kVwoPW[C)X#M+u)+o[cNigC_6+S<1v!Ijx?NT' );
define( 'SECURE_AUTH_KEY',  ']V=MH+Ki+4a-~ffvJXi>}Xn=z/(zA##)jC+u&SEz7&8n_0()ZA gnyr(s_*=Y<:g' );
define( 'LOGGED_IN_KEY',    't>-$K+e*Z*S%Opfs$dsu%G:vw%uR(Wo>t_B<:E!Evgmkl:iviJ~UsV]yJ}$ccZv^' );
define( 'NONCE_KEY',        'i3rdWwjE~%ilXcXE8VU~sTk](0qRKj3UwhL)4PuqZT{,q0-ZS5tl#3^up -3vZK,' );
define( 'AUTH_SALT',        ',^y[#4@{f.OSLGIg.AIL6qQWu$U~@`n*%R^$ !?UM:aaDw{bj9}Ab>KV>0<L&|VC' );
define( 'SECURE_AUTH_SALT', 'rHWAOQarh7e9N_`!U}Y ;)4U:W~eEG2VeukOP}~bX}jS*T-s+AvwOU<ORY/Qt`Y,' );
define( 'LOGGED_IN_SALT',   'o31Rh&`iH9{BfHI>rl=3^!XWN >tP#KuYvg4T1^.0ZO&4)40zv#^AP1Is3d>W>da' );
define( 'NONCE_SALT',       'H5]TRm@U z?g*vacTQOz)Y6fBsvBCf(AVnw`Wx>FtIq@tseD`UC:|ewg0cGzShP^' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
