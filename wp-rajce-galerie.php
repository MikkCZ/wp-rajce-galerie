<?php
/*
Plugin Name:	WP Rajče galerie
Plugin URI:		https://github.com/MikkCZ/wp-rajce-galerie/
Description:	Plugin pro jednoduché zobrazení seznamu fotogalerií uživatele Rajče.net.
Version:		1.0
Author:			Michal Stanke
Author URI:		http://www.mikk.cz/
License:		GPL2
*/

defined( 'ABSPATH' ) or die();

define( 'WP_RAJCE_GALERIE_PLUGIN_FILE', __FILE__ );
define( 'WP_RAJCE_GALERIE_PLUGIN_DIR', trailingslashit( plugin_dir_path( WP_RAJCE_GALERIE_PLUGIN_FILE ) ) );
define( 'WP_RAJCE_DATA_LOAD_TIMEOUT', 2 );
define( 'WP_RAJCE_GALERIE_CACHE_FILES_DIR', trailingslashit(WP_CONTENT_DIR).'wp-rajce-galerie-cache/' );

spl_autoload_register( 'wp_rajce_galerie_autoload' );

/**
 * Handles plugin classes autoloading (all should be prefixed by 'WP_Rajce_galerie_').
 * 
 * @param string $class_name
 * @return true if the class has been loaded successfully
 */
function wp_rajce_galerie_autoload( $class_name ) {
	if ( substr( $class_name, 0, strlen('WP_Rajce_galerie_') ) === 'WP_Rajce_galerie_' ) {
		$class_path = WP_RAJCE_GALERIE_PLUGIN_DIR . 'classes/' . str_replace( "\\", '/', $class_name ) . '.php';
		if ( file_exists( $class_path ) ) {
			require $class_path;
			return true;
		}
	}
	return false;
}

// Plugin installation and admin options
register_activation_hook( WP_RAJCE_GALERIE_PLUGIN_FILE, array('WP_Rajce_galerie_Options', 'install') );
if ( is_admin() ){
	add_action( 'admin_init', array('WP_Rajce_galerie_Options', 'register_settings') );
	add_action( 'admin_menu', array('WP_Rajce_galerie_Options', 'add_menu') );
}

// Mozilla Latest Version shortcodes
add_shortcode( 'rajce', array('WP_Rajce_galerie_Shortcode', 'show_rajce_profile') );
