<?php

/**
 * WP_Rajce_galerie_Cache_Interface is an interface which has to be implemented by all cache classes.
 * 
 * @author Michal Stanke <michal.stanke@mikk.cz>
 */
interface WP_Rajce_galerie_Cache_Interface {

	/**
	 * Checks the value for $key stored in cache is valid (exists and not expired).
	 * 
	 * @param string $key
	 * @return boolean true if exists and not expired
	 */
	public function valid( $key );

	/**
	 * Returns the value stored in cache for $key.
	 * 
	 * @param string $key
	 * @return string value stored in cache or boolean false if no value stored or expired
	 */
	public function get( $key );

	/**
	 * Stores $value for $key in the cache.
	 * 
	 * @param string $key
	 * @param string $value
	 * @return boolean true if stored successfully
	 */
	public function store( $key, $value );

}
