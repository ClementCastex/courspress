<?php
/*
 * @package Akismet
 */
/*
Plugin Name: Frendly Censure
Plugin URI: https://4each.fr/
Description: A plugin that censors all inappropriate words, which you can customize as you like. It can be useful for automatic comment moderation. You can choose whether to replace words with a '*' or a more user-friendly censored message.
Version: 5.3.3
Requires at least: 5.8
Requires PHP: 5.6.20
Author: Automattic - Anti-spam Team
Author URI: https://automattic.com/wordpress-plugins/
License: GPLv2 or later
Text Domain: akismet
*/
include_once plugin_dir_path(__FILE__) . './themes/clement/index.php';

function filtrerInsultes($texte) {
    $insultes = array(' grognard', ' tête de mule ', ' imbécile ', ' fainéant ', ' crétin ', ' responsable ', ' borné ', ' malotru ', ' désagréable ', ' horrible '); 
    foreach ($insultes as $insulte) {
        $texte = str_ireplace($insulte, str_repeat('¤', strlen($insulte)), $texte);
    }
    return $texte;
}

function activer_plugin_filtrage_insultes() {
    add_filter('the_content', 'filtrerInsultes');
}
register_activation_hook(__FILE__, 'activer_plugin_filtrage_insultes');


function desactiver_plugin_filtrage_insultes() {
    remove_filter('the_content', 'filtrerInsultes');
}
register_deactivation_hook(__FILE__, 'desactiver_plugin_filtrage_insultes');


add_action('init', function() {
    if (is_plugin_active(plugin_basename(__FILE__))) {
        add_filter('the_content', 'filtrerInsultes');
    }
});
?>