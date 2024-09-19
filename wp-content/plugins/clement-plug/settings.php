<?php
// Ajouter la page d'options dans le menu
add_action('admin_menu', 'frendly_censure_ajouter_page_options');

function frendly_censure_ajouter_page_options() {
    add_options_page(
        'Paramètres de Frendly Censure', // Titre de la page
        'Frendly Censure',               // Titre du menu
        'manage_options',                // Capacité requise
        'frendly-censure',               // Slug de la page
        'frendly_censure_afficher_page_options' // Fonction de callback
    );
}

// Afficher la page d'options
function frendly_censure_afficher_page_options() {
    ?>
    <div class="wrap">
        <h1>Paramètres de Frendly Censure</h1>
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success') : ?>
            <div class="updated"><p>Les mots ont été enregistrés avec succès.</p></div>
        <?php endif; ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php
            // Ajout du champ nonce pour la sécurité
            wp_nonce_field('frendly_censure_save_options_verify');
            ?>
            <input type="hidden" name="action" value="frendly_censure_save_options">
            <h2>Personnalisation de la Censure</h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Ajouter vos mots à censurer personnalisés</th>
                    <td>
                        <textarea name="frendly_censure_insultes" rows="5" cols="50"></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Ajouter vos mots Frendly personnalisés</th>
                    <td>
                        <textarea name="frendly_censure_gentil" rows="5" cols="50"></textarea>
                    </td>
                </tr>
            </table>
            <?php submit_button('Enregistrer les mots'); ?>
        </form>
    </div>
    <?php
}

// Gérer la soumission du formulaire
add_action('admin_post_frendly_censure_save_options', 'frendly_censure_handle_form_submission');

function frendly_censure_handle_form_submission() {
    // Vérifier les permissions de l'utilisateur
    if (!current_user_can('manage_options')) {
        wp_die('Vous n\'avez pas les permissions nécessaires pour effectuer cette action.');
    }

    // Vérifier le nonce pour la sécurité
    check_admin_referer('frendly_censure_save_options_verify');

    // Récupérer et sanitiser les données du formulaire
    $insultes = isset($_POST['frendly_censure_insultes']) ? sanitize_textarea_field($_POST['frendly_censure_insultes']) : '';
    $gentil = isset($_POST['frendly_censure_gentil']) ? sanitize_textarea_field($_POST['frendly_censure_gentil']) : '';

    // Insérer les données dans la table personnalisée
    global $wpdb;
    $table_name = $wpdb->prefix . 'filter';

    $data = array(
        'insultes' => $insultes,
        'gentil' => $gentil,
    );

    $format = array(
        '%s',
        '%s',
    );

    $wpdb->insert($table_name, $data, $format);

    // Rediriger vers la page des paramètres avec un message de succès
    wp_redirect(admin_url('options-general.php?page=frendly-censure&status=success'));
    exit;
}
