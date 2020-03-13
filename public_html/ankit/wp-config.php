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
define( 'DB_NAME', 'u280902347_wp' );

/** MySQL database username */
define( 'DB_USER', 'u280902347_wp' );

/** MySQL database password */
define( 'DB_PASSWORD', 'wp_u280902347' );

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
define( 'AUTH_KEY',         'Y1Q)z-fWy -+<k[(UbcdLorG ]/+:B9$|WN{t32hZ,1BX:hKb~Pl<|Z5e;<,/1w$' );
define( 'SECURE_AUTH_KEY',  'AXPqJ#oH$6V]vXq6EN*V6+9e^_*U|cmH<MB__IC]VL8MB^5Oc!mmyJi7Lw(,r:TT' );
define( 'LOGGED_IN_KEY',    'p%&Djto]XDvXH;]$V,|R*e1LO)5D8AA{5_RyZvNe|2_50v6{,-tFz$;2N0 j.d]M' );
define( 'NONCE_KEY',        'hdbTG<F)C`la]6?n.2u~>E w5`V`V,!*A/0YFy:s/fe>(|^t$st.3d3i22$4eIyM' );
define( 'AUTH_SALT',        'SS`?R eGVowl|OXkht&rK_#>Th>Oq0J,FCTjC?]cR9?aSG4AZt,PSlLFm}yDy{I<' );
define( 'SECURE_AUTH_SALT', 'Nn@-#+?we$yp&`x|$#)__#BND]a,hN,ZAr7L:r4>:%%p]|CBY4&+NOkT?CyUVIO(' );
define( 'LOGGED_IN_SALT',   'C|t`a@dFit!9<!U{^gEK1jdZFs^Yzb4(NZk=fIN5{6jMy221^t2Own)mpY>I,k?K' );
define( 'NONCE_SALT',       'g?&LG-OZP<pAVOU{VbH~cI@C<T!wn$[Ebm*>!aCAb?m 09<?8Cw=S/ufxB7KO?b,' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'ankit_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
