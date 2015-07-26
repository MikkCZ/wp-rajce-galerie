=== WP Rajče galerie ===
Contributors: mikk_cz
Donate link: http://www.mikk.cz/
Tags: fotogalerie, Rajče, Rajče.net, shortcode
Requires at least: 4.0
Tested up to: 4.2.3
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin pro jednoduché zobrazení seznamu fotogalerií uživatele Rajče.net.

== Description ==

Plugin pro jednoduché zobrazení seznamu fotogalerií uživatele Rajče.net. Inspirován pluginem [Rajče WP Plugin](http://wordpress-rajce.ic.cz).

= Jak použít shortcode? =
`[rajce uzivatel="uživatel" limit="25" popisky="false"]`
* `uživatel` je uživatelské jméno na Rajčeti.
* `limit` je volitelný atribut počtu zobrazených galerií. Ve výchozím stavu se zobrazí všechny v RSS kanálu (tedy maximum 25).
* `popisky` je volitelný atribut určující zobrazení názvu galerie s výchozí hodnotou `false` (nezobrazuje se). Chcete-li, aby se název zobrazoval, nastavte na `true`.

== Installation ==

Jak nainstalovat:

1. Ve administraci WordPressu přejděte do Pluginy -> Instalace pluginů.
2. Vyhledejte "WP Rajče galerie".
3. Klepněte na tlačítko Instalovat.
4. Po instalaci v Pluginy -> Přehled pluginů můžete WP Rajče galerie Aktivovat.
5. Nyní stačí vložit shortcode kam potřebujete (viz část Description nebo FAQ).

== Frequently Asked Questions ==

= Jak použít shortcode? =
`[rajce uzivatel="uživatel" limit="25" popisky="false"]`
* `uživatel` je uživatelské jméno na Rajčeti.
* `limit` je volitelný atribut počtu zobrazených galerií. Ve výchozím stavu se zobrazí všechny v RSS kanálu (tedy maximum 25).
* `popisky` je volitelný atribut určující zobrazení názvu galerie s výchozí hodnotou `false` (nezobrazuje se). Chcete-li, aby se název zobrazoval, nastavte na `true`.

== Screenshots ==

1. Ukázka vložené galerie
2. Ukázka shortcode v editoru stránky

== Changelog ==

= 1.0 =
* Úprava CSS
* Přepsáno do OOP
* Nová implementace cache
* Možnost volby typu cache

= 0.9 =
* Lepší kontrola vstupních dat ze shortcode

= 0.8.2 =
* Zrušení používání funkce get_plugin_data().

= 0.8.1 =
* Oprava Undefined index notice.

= 0.8 =
* First public release.

== Upgrade Notice ==

= 1.0 =
* Úprava CSS
* Přepsáno do OOP
* Nová implementace cache
* Možnost volby typu cache

= 0.9 =
* Lepší kontrola vstupních dat ze shortcode

= 0.8.2 =
* Zrušení používání funkce get_plugin_data().

= 0.8.1 =
* Oprava Undefined index notice.

= 0.8 =
* First public release.

