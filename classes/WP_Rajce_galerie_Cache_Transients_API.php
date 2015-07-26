<?php

/**
 * WP_Rajce_galerie_Cache_Transients_API is one of the WP_Rajce_galerie_Cache_Interface implematations, which stores values using WordPress Transients API.
 * 
 * @link https://codex.wordpress.org/Transients_API Transients API « WordPress Codex
 * @author Michal Stanke <michal.stanke@mikk.cz>
 */
class WP_Rajce_galerie_Cache_Transients_API implements WP_Rajce_galerie_Cache_Interface {

	private $created = '_created';

	public function valid( $key ) {
		return $this->exists( $key ) && ! ( $this->expired( $key ) );
	}

	public function get( $key ) {
		return get_transient( $this->get_transient_name( $key ) );
	}

	public function store( $key, $value ) {
		set_transient( $this->get_transient_name( $key ), $value );
		return set_transient( $this->get_transient_name( $key ).$this->created, time() );
	}

	/**
	 * Checks the value for $key stored in cache exists.
	 * 
	 * @param string $key
	 * @return boolean true if exists
	 */
	private function exists( $key ) {
		return (bool)( get_transient( $this->get_transient_name( $key ) ) );
	}

	/**
	 * Checks the value for $key stored in cache is expired.
	 * 
	 * @param string $key
	 * @return boolean true if expired
	 */
	private function expired( $key ) {
		$created = get_transient( $this->get_transient_name( $key ).$this->created );
		return $created && time() - $created > WP_Rajce_galerie_Options::getInstance()->get_cache_expire();
	}

	/**
	 * Get transient name for the key.
	 * 
	 * @param string $key
	 * @return string transient name
	 */
	private function get_transient_name( $key ) {
		return sha1($key);
	}

}
