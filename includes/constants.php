<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

define( 'V_PLUGIN_NAME', 'topvpn' );
define( 'V_FILE', __FILE__ );
define( 'V_PLUGIN_BASENAME', plugin_basename( V_FILE ) );
if ( !defined( 'V_PLUGIN_DIR' ) ) {
    define( 'V_PLUGIN_DIR', WP_PLUGIN_DIR . '/topvpn' );
}
if ( !defined( 'V_CORE_LIB' ) ) {
    define( 'V_CORE_LIB', V_PLUGIN_DIR . '/system/' );
}
if ( !defined( 'V_ADMIN' ) ) {
    define( 'V_ADMIN', V_PLUGIN_DIR . '/admin' );
}
if ( !defined( 'V_CORE_URL' ) ) {
    define( 'V_CORE_URL', WP_PLUGIN_URL . '/topvpn' );
}

define( 'V_PLUGIN_URL', plugin_dir_url( V_FILE ) );