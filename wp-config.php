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
define( 'DB_NAME', 'wp_vaamengineering_d' );

/** Database username */
define( 'DB_USER', 'wp_vaamengineering_u' );

/** Database password */
define( 'DB_PASSWORD', 'b3%.ndrD}q' );

/** Database hostname */
define( 'DB_HOST', 'ec2-43-205-168-50.ap-south-1.compute.amazonaws.com' );

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
define( 'AUTH_KEY',         'yE`rxo0ePn.B/?;OA_d8%(:qgfLV(JIdqRLo/1lb8g_(&Ng/V!ceqN{<Pkj9;4D{' );
define( 'SECURE_AUTH_KEY',  '6S|G:h1*imgk?|%j+)h?W>|_r[c3K|Vj0S$cxfu%GvvYEFaSjx<7)E@7_></EyWm' );
define( 'LOGGED_IN_KEY',    'm$dXqhlb=]5}swCu ~Q8_F_dRv)8!KiY@l:1:#-NRWrSGl3ywqX|k JE<_CjTn&|' );
define( 'NONCE_KEY',        'oT;|vmy1,R>Ox[}t*O-d J+o>4&,sX8Etup- vcJ&gi^qmRV2Wt,PPu3;S7O-`$(' );
define( 'AUTH_SALT',        'k)rIw;b(E%Xk%P]^H}:OWY]m(hN<w=~B!*X;5>`AM}$qdaI6leT7)1m}q:dfN|4:' );
define( 'SECURE_AUTH_SALT', 'tTz+gg! /vAxY^6(a#0ubddo~Y3HkhyJnw]1oN`?2U[v(_#bfKl{`v[E3KW|t|vN' );
define( 'LOGGED_IN_SALT',   'q)<pbrq`jQ#cHJdilfx ^40X3x{|8(og1I@YX0w`GXjDtgF*|ERI{ncD:SRv0Fl[' );
define( 'NONCE_SALT',       ';bqWHyZ5]}S}90JV&um(=L}|q(0~.)7[!7$becXWL9tiB|[_Dm}p[B[JWtjdCJ:p' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'vme_';

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



define( 'DISALLOW_FILE_EDIT', true );
define( 'DISABLE_WP_CRON' , true );
define( 'DISALLOW_FILE_MODS', true );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
