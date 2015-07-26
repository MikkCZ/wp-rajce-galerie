<?php

/**
 * WP_Rajce_galerie_Cache_Factory is a static class to get the cache implementation according to the plugin settings.
 *
 * @author Michal Stanke <michal.stanke@mikk.cz>
 */
class WP_Rajce_galerie_Cache_Factory {

	private static $cache = NULL;

	/**
	 * Returns the cache implementation according to the plugin settings.
	 * 
	 * @return WP_Rajce_galerie_Cache_Interface cache implementation
	 */
	public static function get_cache() {
		if ( self::$cache == NULL ) {
			$wp_rajce_galerie_options = WP_Rajce_galerie_Options::getInstance();
			switch ( $wp_rajce_galerie_options->get_cache_type() ) {
				case $wp_rajce_galerie_options->cache_type_transients_api():
					self::$cache = new WP_Rajce_galerie_Cache_Transients_API();
					break;
				case $wp_rajce_galerie_options->cache_type_files():
					self::$cache = new WP_Rajce_galerie_Cache_File();
					break;
				default:
					self::$cache = new WP_Rajce_galerie_Cache_Transients_API();
					break;
			}
		}
		return self::$cache;
	}

}
