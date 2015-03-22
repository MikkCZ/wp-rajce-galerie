<?php
/*
Plugin Name:	WP Rajče galerie
Plugin URI:		https://github.com/MikkCZ/wp-rajce-galerie/
Description:	Plugin pro jednoduché zobrazení seznamu fotogalerií uživatele Rajče.net.
Version:		0.8
Author:			Michal Stanke
Author URI:		http://www.mikk.cz/
License:		GPL2
*/

defined('ABSPATH') or die();

define(WP_RAJCE_GALERIE_PLUGIN_FILE, __FILE__);

require_once 'wp-rajce-galerie-options.php';

add_shortcode('rajce', 'show_rajce_profile');

function show_rajce_profile($atts, $content = NULL) {
	$plugin_data = get_wp_rajce_plugin_data();
	shortcode_atts(
		array('uzivatel' => NULL,
			  'limit' => NULL,
			  'popisky' => false,
		),
		$atts,
		'rajce'
	);
	if ($atts['uzivatel'] == NULL) {
		return sprintf('<!-- Nebyl zadán žádný uživatel (%s). -->', $plugin_data['Name']);
	}

	$username = strtolower($atts['uzivatel']);
	if(!ctype_alnum($username)) {
		return sprintf('<!-- Zadaný uživatel je neplatný (%s). -->', $plugin_data['Name']);
	}

	$rss_url = sprintf('http://%s.rajce.idnes.cz/?rss=news', $username);

	$in_cache = trailingslashit(WP_CONTENT_DIR).sprintf('wp-rajce-galerie-cache/%s.rss', $username);
	if( !is_file($in_cache) || !(time()-filemtime($in_cache) < get_option('wp-rajce-plugin-cache-expiration')) ) {
		$rss_file_content = file_get_contents($rss_url);
		if(substring_in_array(' 404 Not Found', $http_response_header)) {
			return sprintf('<!-- Zadaný uživatel neexistuje (%s). -->', $plugin_data['Name']);
		}
		if(substring_in_array(' 200 OK', $http_response_header)) {
			if(!file_exists(dirname($in_cache))) {
				mkdir(dirname($in_cache));
			}
			file_put_contents($in_cache, $rss_file_content);
		}
	}
	$rss_file = simplexml_load_file($in_cache);

	$albums = array();
	$i = 0;
	$limit = $atts['limit'];
	foreach ($rss_file->channel->item as $album) {
		if($limit != NULL && $i == $limit) {
			break;
		}
		$albums[$i]['title'] = str_replace($username . ' | ', '', $album->title);
		$albums[$i]['album_url'] = $album->link;
		$albums[$i]['thumbnail_url'] = $album->image->url;
		$i++;
	}

	$output = format_output($content, $albums, $atts['popisky']);

	wp_enqueue_style('wp-rajce-galerie', plugins_url('css/style.css', __FILE__));
	return $output;
}

function substring_in_array($substring, $array) {
	foreach ($array as $value) {
		if (strpos($value, $substring) !== false) {
			return true;
		}
	}
	return false;
}

function format_output($headline, $albums_from_rss, $show_titles) {
	$output = '<div class="wp-rajce-galerie">' . PHP_EOL;
	if ($headline != NULL) {
		$output .= sprintf('<h3>%s</h3>', $headline) . PHP_EOL;
	}
	foreach ($albums_from_rss as $album) {
		$output .= '<div class="album">';
		$output .= sprintf('<a href="%s">', $album['album_url']);
		$output .= sprintf('<img src="%1$s" alt="%2$s" class="thumbnail">', $album['thumbnail_url'], $album['title']);
		if ($show_titles) {
			$output .= $album['title'];
		}
		$output .= '</a>';
		$output .= '</div>' . PHP_EOL;
	}
	$output .= '</div>';
	return $output;
}

function get_wp_rajce_plugin_data() {
	return get_plugin_data(WP_RAJCE_GALERIE_PLUGIN_FILE);
}

