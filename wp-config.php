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
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'bun+J84fvqrbHbPKkoea3lJPeWQI/4DFbGwcbmH2YEwYSKELX+jkcSfhm6cY57ZsTDc7v4JwIpX3fFyTdmDGnw==');
define('SECURE_AUTH_KEY',  '/NYlsyaSrngsQmgMz4mtiXPnwU/AkBVBpB6JmjUSk7KamKUok+/q0wGaI7r/7wgmvYkN2geJPelhIc+Q6kVdgA==');
define('LOGGED_IN_KEY',    'Xcl4Xx6JPG9IgZ8/w7xsfp3unqDNmwP6VO37YYfEefc6Dd8VIqzIqp6CvvQuglkdhkTTZl6wsNICOwAA+FIA7Q==');
define('NONCE_KEY',        'UpOidHPCwPRWoAAOtJ6Gydb3FMY8zLbewA6+BiOFDKaPX+FLj1XaRlyxRj17q4m/I7rjtei4l25xbhdyZEQbQQ==');
define('AUTH_SALT',        'p6/qJN/ZoRDpty49zLzaQ4HyF8j0oq1nSnrr6V2SbK7Fum8SQ5bm22eViWThjD8vJx/K1JsBM6NMDuUa938d+A==');
define('SECURE_AUTH_SALT', '31YBmJoqfySc5G9YzktebEzrEl4FZLOsafPgjwtTQTt3LbX1UjjXHU4oazDsvvGUhGf1o+FzrXBjhesQwbKmZA==');
define('LOGGED_IN_SALT',   'xsBKadXYBsB8s9AtXkhQbtMolEtvf9Na+ip5EvspBDiev7/BwMI8LOTezfgyv25wEB91sFaXO/COgtW2IKlMmw==');
define('NONCE_SALT',       'Bd9Mj4oBcQIC/IrXdKziDoUkfpdBpXbOdsL66AqCwGZJrbJVfBYy7at0p9t/GmeBAiEVBf8/6dAjQ3YpHbKa4g==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
