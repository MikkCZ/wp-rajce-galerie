<?php

/**
 * WP_Rajce_galerie_Options is singleton handling the plugin options and its installation.
 * 
 * @author Michal Stanke <michal.stanke@mikk.cz>
 */
class WP_Rajce_galerie_Options {

	private static $instance = NULL;
	private $option_group = 'wp-rajce-galerie-option-group';
	private $cache_type = 'wp_rajce_galerie_cache_type';
	private $cache_type_transients_api = 'transients_api';
	private $cache_type_files = 'files';
	private $cache_expire = 'wp_rajce_galerie_cache_expire';

	/**
	 * Handles the plugin installation and its options registration (including default values).
	 */
	public function install() {
		$wp_rajce_galerie_options = self::getInstance();
		add_option( $wp_rajce_galerie_options->cache_type, $wp_rajce_galerie_options->cache_type_transients_api );
		add_option( $wp_rajce_galerie_options->cache_expire, 7200 );
	}

	/**
	 * Registers the plugin settings.
	 */
	public function register_settings() {
		$wp_rajce_galerie_options = self::getInstance();
		register_setting( $wp_rajce_galerie_options->option_group, $wp_rajce_galerie_options->cache_type );
		register_setting( $wp_rajce_galerie_options->option_group, $wp_rajce_galerie_options->cache_expire );
	}

	/**
	 * Registers the plugin page in the admin menu.
	 */
	public function add_menu() {
		add_options_page(
			'WP Rajče galerie',
			'WP Rajče galerie',
			'manage_options',
			'WP_Rajce_galerie_Page.php',
			array( new WP_Rajce_galerie_Settings_Page( self::getInstance()->option_group ), 'main' )
		);
	}

	/**
	 * Returns cache type option identification.
	 */
	public function cache_type() {
		return $this->cache_type;
	}

	/**
	 * Returns cache type setting value.
	 */
	public function get_cache_type() {
		return get_option( $this->cache_type() );
	}

	/**
	 * Returns TRansients API cache type option identification.
	 */
	public function cache_type_transients_api() {
		return $this->cache_type_transients_api;
	}

	/**
	 * Returns Files cache type option identification.
	 */
	public function cache_type_files() {
		return $this->cache_type_files;
	}

	/**
	 * Returns cache expiration option identification.
	 */
	public function cache_expire() {
		return $this->cache_expire;
	}

	/**
	 * Returns cache expiration value.
	 */
	public function get_cache_expire() {
		return get_option( $this->cache_expire() );
	}

	/**
	 * Returns the WP_Rajce_galerie_Options singleton instance.
	 */
	public static function getInstance() {
		if ( self::$instance == NULL ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {}

}
