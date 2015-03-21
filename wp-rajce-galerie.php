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

add_shortcode('rajce', 'show_rajce_profile');

function show_rajce_profile($atts, $content = NULL) {
	$plugin_data = get_plugin_data(__FILE__);
	shortcode_atts(
		array('uzivatel' => NULL,
			  'popisky' => false,
		),
		$atts
	);
	if ($atts['uzivatel'] == NULL) {
		return '<!-- Nebyl zadán žádný uživatel ('.$plugin_data['Name'].'). -->';
	}
	$username = strtolower($atts['uzivatel']);

	if(!ctype_alnum($username)) {
		return '<!-- Zadaný uživatel je neplatný ('.$plugin_data['Name'].'). -->';
	}

	$rss_url = 'http://' . $username . '.rajce.idnes.cz/?rss=news';
	$headers = get_headers($rss_url);
	if (substring_in_array(' 404 Not Found', $headers)) {
		return '<!-- Zadaný uživatel neexistuje ('.$plugin_data['Name'].'). -->';
	}

	$in_cache = trailingslashit(WP_CONTENT_DIR).'wp-rajce-galerie-cache/'.$username.'.rss';
	if( !is_file($in_cache) || !(time()-filemtime($in_cache) < 7200) ) {
		$rss_file_content = file_get_contents($rss_url);
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
	foreach ($rss_file->channel->item as $album) {
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
		$output .= '<h3>' . $headline . '</h3>' . PHP_EOL;
	}
	foreach ($albums_from_rss as $album) {
		$output .= '<div class="rajce-album">';
		$output .= '<a href="' . $album['album_url'] . '">';
		$output .= '<img src="' . $album['thumbnail_url'] . '" alt="' . $album['title'] . '" class="rajce-thumbnail">';
		if ($show_titles) {
			$output .= '<br>';
			$output .= $album['title'];
		}
		$output .= '</a>';
		$output .= '</div>' . PHP_EOL;
	}
	$output .= '</div>';
	return $output;
}

