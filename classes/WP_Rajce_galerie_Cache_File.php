<?php

/**
 * WP_Rajce_galerie_Cache_File is one of the WP_Rajce_galerie_Cache_Interface implematations, which stores values in standalone files in directory defined by WP_RAJCE_GALERIE_CACHE_FILES_DIR.
 * 
 * @author Michal Stanke <michal.stanke@mikk.cz>
 */
class WP_Rajce_galerie_Cache_File implements WP_Rajce_galerie_Cache_Interface {

	private $cache_dir = WP_RAJCE_GALERIE_CACHE_FILES_DIR;

	public function valid( $key ) {
		return $this->exists( $key ) && ! ( $this->expired( $key ) );
	}

	public function get( $key ) {
		if ( $this->exists( $key ) ) {
			return file_get_contents( $this->get_file_path( $key ) );
		} else {
			return false;
		}
	}

	public function store( $key, $value ) {
		wp_mkdir_p( $this->cache_dir );
		if ( ! file_exists( $this->cache_dir.".htaccess" ) ) {
			file_put_contents( $this->cache_dir.".htaccess", "deny from all", LOCK_EX );
		}
		return file_put_contents( $this->get_file_path( $key ), $value, LOCK_EX );
	}

	/**
	 * Checks the value for $key stored in cache exists.
	 * 
	 * @param string $key
	 * @return boolean true if exists
	 */
	private function exists( $key ) {
		return is_file( $this->get_file_path( $key ) );
	}

	/**
	 * Checks the value for $key stored in cache is expired.
	 * 
	 * @param string $key
	 * @return boolean true if expired
	 */
	private function expired( $key ) {
		return time() - filemtime( $this->get_file_path( $key ) ) > WP_Rajce_galerie_Options::getInstance()->get_cache_expire();
	}

	/**
	 * Get file name (including path) for the key.
	 * 
	 * @param string $key
	 * @return string file path
	 */
	private function get_file_path( $key ) {
		return $this->cache_dir . sha1($key) . '.cache';
	}

}
