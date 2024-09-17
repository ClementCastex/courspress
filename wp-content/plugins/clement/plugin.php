<?php
/*
 * @package Akismet
 */
/*
Plugin Name: Frendly Censure
Plugin URI: https://4each.fr/
Description: A plugin who censure all not nice words, you can custom them ass you like. Its can be usefull as an automatic commentary moderation 
Version: 5.3.3
Requires at least: 5.8
Requires PHP: 5.6.20
Author: Automattic - Anti-spam Team
Author URI: https://automattic.com/wordpress-plugins/
License: GPLv2 or later
Text Domain: akismet
*/
function filtrerInsultes($texte) {
    // Liste des insultes à filtrer
    $insultes = array('insulte1', 'insulte2', 'insulte3'); // Remplace par les insultes que tu veux filtrer

    // Parcourir la liste des insultes
    foreach ($insultes as $insulte) {
        // Remplacer l'insulte par des astérisques (la longueur de l'insulte)
        $texte = str_ireplace($insulte, str_repeat('*', strlen($insulte)), $texte);
    }

    return $texte;
}

// Exemple d'utilisation
$phrase = "Ceci est un exemple avec insulte1 et insulte2.";
$phraseFiltrée = filtrerInsultes($phrase);

echo $phraseFiltrée; // Ceci est un exemple avec ******* et *******.
?>