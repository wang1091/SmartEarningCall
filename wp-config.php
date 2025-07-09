<?php
/**
 * WordPress Configuration File for MVP
 */

// ** MySQL settings ** //
define( 'DB_NAME', 'wordpress_mvp' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

// ** Authentication Unique Keys and Salts ** //
define( 'AUTH_KEY',         'mvp-auth-key-12345' );
define( 'SECURE_AUTH_KEY',  'mvp-secure-auth-key-12345' );
define( 'LOGGED_IN_KEY',    'mvp-logged-in-key-12345' );
define( 'NONCE_KEY',        'mvp-nonce-key-12345' );
define( 'AUTH_SALT',        'mvp-auth-salt-12345' );
define( 'SECURE_AUTH_SALT', 'mvp-secure-auth-salt-12345' );
define( 'LOGGED_IN_SALT',   'mvp-logged-in-salt-12345' );
define( 'NONCE_SALT',       'mvp-nonce-salt-12345' );

// ** WordPress Database Table prefix ** //
$table_prefix = 'wp_';

// ** For developers: WordPress debugging mode ** //
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );

// ** Absolute path to the WordPress directory ** //
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

// ** Sets up WordPress vars and included files ** //
require_once ABSPATH . 'wp-settings.php';