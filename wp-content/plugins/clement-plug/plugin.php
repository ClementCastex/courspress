<?php
/*
 * @package Akismet
 */
/*
Plugin Name: Frendly Censure  
Plugin URI: https://4each.fr/
Description: A plugin that censors all inappropriate words,( for exemple " crétin " )which you can customize as you like. It can be useful for automatic comment moderation. You can choose whether to replace words with a '*' or a more user-friendly censored message.
Version: 5.3.3
Requires at least: 5.8
Requires PHP: 5.6.20
Author: Automattic - Anti-spam Team
Author URI: https://automattic.com/wordpress-plugins/
License: GPLv2 or later
Text Domain: akismet
*/
require_once plugin_dir_path(__FILE__) . 'settings.php';

/* Fonctionnalité de Filtrage et de changement de mot insultant par un mot gentil*/
function insererInsultesAvecMotsGentils() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'filter'; 

    $insultes = array(
        'grognard', 'tête de mule', 'imbécile', 'fainéant', 'crétin',
        'responsable', 'borné', 'malotru', 'désagréable', 'horrible',
        'détestable', 'tête de nœud', 'imbécile', 'navrant', 'fainéant', 'bras cassé', 'gamin', 'agaçant', 
        'malotru', 'exécrable', 'fainéantise', 'méprisante', 'pestiféré', 'imbéciles', 'paresseux', 'peste', 
        'crétin'
    );

    $mots_gentils = array(
        'Amour', 'Étoile', 'Câlin', 'Chéri(e)', 'Soleil', 'Joie', 'Douceur', 'Bonheur', 'Harmonie', 
        'Sourire', 'Paix', 'Lumière', 'Amical', 'Fleur', 'Trésor', 'Ange', 'Beauté', 'Bienveillance',
        'Gentillesse', 'Compassion', 'Grâce', 'Générosité', 'Respect', 'Sérénité', 'Sympathie', 
        'Espoir', 'Tendresse', 'Confiance', 'Amabilité', 'Rêveur', 'Plaisir', 'Splendeur', 'Brillant(e)', 
        'Magie', 'Lumière', 'Sympathique', 'Radieux', 'Charmant(e)', 'Perle', 'Merveilleux', 'Calme', 
        'Sagesse', 'Fantastique', 'Courageux', 'Héroïque', 'Inspirant', 'Talentueux', 'Fort(e)', 
        'Délicatesse', 'Clarté', 'Fidèle', 'Vérité', 'Passionné', 'Divin(e)', 'Affectueux', 'Protecteur',
        'Joyeux', 'Lumineux', 'Rafraîchissant', 'Pétillant', 'Optimiste', 'Positif', 'Créatif', 'Fabuleux',
        'Enchanteur', 'Gracieux', 'Magnifique', 'Paisible', 'Doux', 'Sensible', 'Espiègle', 'Rêve', 
        'Bienfaisant', 'Bienheureux', 'Ensoleillé', 'Éblouissant', 'Inoubliable', 'Passion', 'Émerveillé',
        'Génie', 'Courage', 'Chaleureux', 'Attentionné', 'Loyal', 'Charmant', 'Adorable', 'Sérénité',
        'Confiant', 'Motivé', 'Inspiré', 'Valeureux', 'Aimant', 'Passionné', 'Enthousiaste', 'Digne', 
        'Loyal', 'Gentil', 'Doux', 'Souriant', 'Rayonnant', 'Brillant', 'Solidaire', 'Altruiste', 
        'Respectueux', 'Optimiste'
    );

    $wpdb->query("TRUNCATE TABLE $table_name");

    foreach ($insultes as $insulte) {
        $mot_gentil = $mots_gentils[array_rand($mots_gentils)];
        $wpdb->insert(
            $table_name,
            array(
                'insultes' => $insulte,
                'gentil' => $mot_gentil
            )
        );
    }
}
function mon_plugin_activation() {
    insererInsultesAvecMotsGentils();
}
register_activation_hook(__FILE__, 'mon_plugin_activation');

function filtrerInsultes($texte) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'filter';
    $insultes = $wpdb->get_results( "SELECT insultes, gentil FROM $table_name", ARRAY_A );

    foreach ($insultes as $insulte) {
        $texte = str_ireplace($insulte['insultes'], $insulte['gentil'], $texte);
    }

    return $texte;
}
add_filter('the_content', 'filtrerInsultes', 20);
add_filter('the_title', 'filtrerInsultes', 20);
add_filter('widget_text', 'filtrerInsultes', 20);
add_filter('gettext', 'filtrerInsultes', 20);
add_filter('ngettext', 'filtrerInsultes', 20);
add_filter('the_excerpt', 'filtrerInsultes', 20);

?>