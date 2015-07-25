<?php

/**
 * WP_Rajce_galerie_Shortcode contains all functions neccessary to handle the shortcodes.
 * 
 * @author Michal Stanke <michal.stanke@mikk.cz>
 */
class WP_Rajce_galerie_Shortcode {

	private static $instance = NULL;

	public function show_rajce_profile( $atts, $content = NULL ) {
	    $atts = shortcode_atts(
		    array(
			    'uzivatel' => NULL,
			    'limit'    => NULL,
			    'popisky'  => false,
		    ),
		    $atts,
		    'rajce'
	    );
	    if ( $atts['uzivatel'] == NULL ) {
		    return sprint( '<!-- Nebyl zadán žádný uživatel (WP Rajče galerie). -->' );
	    }
	    $username = strtolower( $atts['uzivatel'] );
	    if ( ! ctype_alnum($username) ) {
		    return sprint( '<!-- Zadaný uživatel je neplatný (WP Rajče galerie). -->' );
	    }

	    $limit = $atts['limit'];
	    if ( $limit == NULL ) {
		    $limit = PHP_INT_MAX;
	    } else if ( is_numeric($limit) && intval($limit) > 0 ) {
		    $limit = intval( $limit );
	    } else {
		    return sprint( '<!-- Zadaný limit není kladné celé číslo (WP Rajče galerie). -->' );
	    }

	    $show_titles = filter_var($atts['popisky'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

	    $rss_url = sprintf( 'http://%s.rajce.idnes.cz/?rss=news', $username );

	    $in_cache = WP_RAJCE_GALERIE_CACHE_FILES_DIR.sprintf( '%s.rss', $username );
	    $wp_rajce_galerie_options = WP_Rajce_galerie_Options::getInstance();
	    $cache_refresh = ! is_file( $in_cache ) || ( time() - filemtime($in_cache) ) > $wp_rajce_galerie_options->get_cache_expire();
	    if ( $cache_refresh ) {
		    $rss_file_content = file_get_contents( $rss_url );
		    if ( self::substring_in_array( ' 404 Not Found', $http_response_header ) ) {
			    return sprint( '<!-- Zadaný uživatel neexistuje (WP Rajče galerie). -->' );
		    }
		    if ( self::substring_in_array( ' 200 OK', $http_response_header ) ) {
			    if ( ! file_exists( dirname( $in_cache ) ) ) {
				    wp_mkdir_p( dirname( $in_cache ) );
			    }
			    if ( ! file_exists( dirname( $in_cache )."/.htaccess" ) ) {
				    file_put_contents( dirname( $in_cache )."/.htaccess", "deny from all", LOCK_EX );
			    }
			    file_put_contents( $in_cache, $rss_file_content, LOCK_EX );
		    }
	    }
	    $rss_file = simplexml_load_file( $in_cache );

	    $albums = array();
	    $i = 0;
	    foreach ( $rss_file->channel->item as $album ) {
		    if ( $limit != NULL && $i >= $limit ) {
			    break;
		    }
		    $albums[ $i ]['title'] = str_replace( $username . ' | ', '', $album->title );
		    $albums[ $i ]['album_url'] = $album->link;
		    $albums[ $i ]['thumbnail_url'] = $album->image->url;
		    $i++;
	    }

	    $output = self::format_output( $content, $albums, $show_titles );

	    wp_enqueue_style( 'wp-rajce-galerie', plugins_url( 'css/style.css', WP_RAJCE_GALERIE_PLUGIN_FILE ) );
	    return $output;
    }

    private function substring_in_array( $substring, $array ) {
	    foreach ( $array as $value ) {
		    if ( strpos( $value, $substring ) !== false ) {
			    return true;
		    }
	    }
	    return false;
    }

    private function format_output( $headline, $albums_from_rss, $show_titles ) {
	    $output = '<div class="wp-rajce-galerie">' . PHP_EOL;
	    if ( $headline != NULL ) {
		    $output .= sprintf( '<h3>%s</h3>', $headline ) . PHP_EOL;
	    }
	    foreach ( $albums_from_rss as $album ) {
		    $output .= '<div class="album">';
		    $output .= sprintf( '<a href="%s">', $album['album_url'] );
		    $output .= sprintf( '<img src="%1$s" alt="%2$s" class="thumbnail">', $album['thumbnail_url'], $album['title'] );
		    if ( $show_titles ) {
			    $output .= $album['title'];
		    }
		    $output .= '</a>';
		    $output .= '</div>' . PHP_EOL;
	    }
	    $output .= '</div>';
	    return $output;
    }

	/**
	 * Returns the WP_Rajce_galerie_Shortcode singleton instance.
	 */
	private static function getInstance() {
		if ( self::$instance == NULL ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {}

}
