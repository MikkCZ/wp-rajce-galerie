<?php

defined('ABSPATH') or die();

register_activation_hook(WP_RAJCE_GALERIE_PLUGIN_FILE, 'wp_rajce_plugin_install');
if (is_admin()){
	add_action('admin_init', 'register_wp_rajce_plugin_settings');
	add_action('admin_menu', 'add_wp_rajce_plugin_menu');
}

function wp_rajce_plugin_install() {
	add_option('wp-rajce-plugin-cache-expiration', 7200);
}

function register_wp_rajce_plugin_settings() {
	register_setting('wp-rajce-plugin-option-group', 'wp-rajce-plugin-cache-expiration');
}

function add_wp_rajce_plugin_menu() {
	$plugin_data = get_wp_rajce_plugin_data();
	add_options_page(
		$plugin_data['Name'],
		$plugin_data['Name'],
		'manage_options',
		'wp_rajce_plugin',
		'wp_rajce_plugin_settings_page'
	);
}

function wp_rajce_plugin_settings_page() {
	$plugin_data = get_wp_rajce_plugin_data();
	print('<div class="wrap">');
	printf('<h2>%s</h2>', $plugin_data['Name']);
	printf('<p>%s</p>', $plugin_data['Description']);
	print('<h2>Nastavení</h2>');
	print('<form method="post" action="options.php">');
	settings_fields('wp-rajce-plugin-option-group');
	do_settings_sections('wp-rajce-plugin-option-group');
	print('<table class="form-table">');
	printf('
			<tr>
				<th><label for="wp-rajce-plugin-cache-expiration">Expirace cache</label></th>
				<td>
					<input type="number" name="wp-rajce-plugin-cache-expiration" id="wp-rajce-plugin-cache-expiration" min="0" value="%s" required>
					<p class="description">Tato volba nastavuje expiraci cache. Vyšší hodnota může pomoci zrychlit načítání stránky, nižší urychlí aktualizaci změn provedených na Rajče.net (výchozí 7200 = 2 hodiny).</p>
				</td>
			</tr>
			',
			get_option('wp-rajce-plugin-cache-expiration')
		);
	print('</table>');
	submit_button();
	print('</form>');
	print('</div>');
}

