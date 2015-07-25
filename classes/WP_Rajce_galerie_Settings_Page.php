<?php

/**
 * WP_Rajce_galerie_Settings_Page outputs the plugin settings page HTML code.
 * 
 * @author Michal Stanke <michal.stanke@mikk.cz>
 */
class WP_Rajce_galerie_Settings_Page {

	private $option_group;

	/**
	 * WP_Rajce_galerie_Settings_Page constructor.
	 * 
	 * @param string $option_group
	 */
	public function __construct( $option_group ) {
		$this->option_group = $option_group;
	}

	/**
	 * Outputs main plugin admin page HTML code.
	 */
	public function main() {
		print( '<div class="wrap">' );
		$wp_rajce_galerie_options = WP_Rajce_galerie_Options::getInstance();
		$plugin_data = get_plugin_data( WP_RAJCE_GALERIE_PLUGIN_FILE );
		printf( '<h2>%s</h2>', $plugin_data['Name'] );
		printf( '<p>%s</p>', $plugin_data['Description'] );
		print( '<h2>Nastavení</h2>' );
		print( '<form method="post" action="options.php">' );
        settings_fields( $this->option_group );
        do_settings_sections( $this->option_group );
        print( '<table class="form-table">' );
        printf( '<tr>
            <th><label for="%1$s">Expirace cache</label></th>
                <td>
                    <input type="number" name="%1$s" id="%1$s" min="0" value="%2$s" required>
                    <p class="description">Tato volba nastavuje expiraci cache. Vyšší hodnota může pomoci zrychlit načítání stránky, nižší urychlí aktualizaci změn provedených na Rajče.net (výchozí 7200 = 2 hodiny).</p>
                </td>
            </tr>
            ',
            $wp_rajce_galerie_options->cache_expire(),
            esc_attr( $wp_rajce_galerie_options->get_cache_expire() )
        );
        print( '</table>' );
        submit_button();
        print( '</form>' );
        
        print( '</div>' );
	}

}
