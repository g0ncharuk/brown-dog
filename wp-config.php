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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'browndog' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'nV;EUd}WkyGK<RFug6Y(!54jZS9]$(xCQ?#E;>qEQRY% fQ0_+pOy.&6gEtlD]rz' );
define( 'SECURE_AUTH_KEY',  'L=4lbqr#yV^A]))kx&rR/Vl(sC;2~.pBdTEzyAQd X$);*@egfQqo(/vvK2V1=mb' );
define( 'LOGGED_IN_KEY',    'UuzA;2&6f*kVK7qo,VVmdh|.x~96ySLt;n<TKllL:#_mIH^eR`Afx,vv2=_?Wity' );
define( 'NONCE_KEY',        '[n)R$r6:[Q}VW++8lmj ^=qx!ABUXx[^IH8p1_[.VPrhC`c4(+@$qgr.wVtgJ[>H' );
define( 'AUTH_SALT',        '^AbpLy4rAa>ey({y;{`Pv}Uw7yoO;*C<R~.dCw%KK7>B^}r.~:FCPib J?yYtX.-' );
define( 'SECURE_AUTH_SALT', 'kPjz[|*at]g[w4>b]EThIL<@K<1[LJdg*FC[_xuW/Q$%Zr6V(.Zx4BR6Zp2jwU{w' );
define( 'LOGGED_IN_SALT',   '6P8CGP=H[{iRd.{XGIH0CZI o3-omPTO#WsQbPG#tKgq]bAW(~WjK1>Xha`T:KQ ' );
define( 'NONCE_SALT',       'JMx(vX#d/y1^lJY:4xtrv;m}S:O|5o#Q[Qoh_f?e5[~|.zu=e9{uQ9ND~$MnRZT!' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
