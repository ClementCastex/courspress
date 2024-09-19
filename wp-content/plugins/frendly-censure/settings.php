<?php
add_action('admin_menu', 'frendly_censure_ajouter_page_options');

function frendly_censure_ajouter_page_options() {
    add_options_page(
        'Paramètres de Frendly Censure', 
        'Frendly Censure',               
        'manage_options',              
        'frendly-censure',               
        'frendly_censure_afficher_page_options' 
    );
}


function frendly_censure_afficher_page_options() {
    $plugin_enabled = get_option('frendly_censure_enabled', 'yes'); 

    ?>
    <div class="wrap">
        <h1>Paramètres de Frendly Censure</h1>
        <?php
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') {
                echo '<div class="updated"><p>Les modifications ont été enregistrées avec succès.</p></div>';
            } elseif ($_GET['status'] == 'error') {
                echo '<div class="error"><p>Veuillez remplir tous les champs avant d\'enregistrer les mots.</p></div>';
            }
        }
        ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php
            wp_nonce_field('frendly_censure_save_options_verify');
            ?>
            <input type="hidden" name="action" value="frendly_censure_save_options">

            <!-- Section pour activer/désactiver le filtrage -->
            <h2>Activation du filtrage</h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Activer le filtrage des mots</th>
                    <td>
                        <input type="checkbox" name="frendly_censure_enabled" value="yes" <?php checked('yes', $plugin_enabled); ?> />
                    </td>
                </tr>
            </table>
            <?php submit_button('Enregistrer les paramètres', 'primary', 'save_settings'); ?>

            <!-- Section pour ajouter des mots personnalisés -->
            <h2>Personnalisation de la Censure</h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Ajouter vos mots à censurer personnalisés</th>
                    <td>
                        <textarea name="frendly_censure_insultes" rows="5" cols="50"></textarea>
                        <p class="description">Entrez un mot ou une liste de mots séparés par des virgules.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Ajouter vos mots Frendly personnalisés</th>
                    <td>
                        <textarea name="frendly_censure_gentil" rows="5" cols="50"></textarea>
                        <p class="description">Entrez un mot ou une liste de mots séparés par des virgules, correspondant aux insultes ci-dessus.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Enregistrer les mots', 'secondary', 'save_words'); ?>
        </form>
    </div>
    <?php
}

add_action('admin_post_frendly_censure_save_options', 'frendly_censure_handle_form_submission');

function frendly_censure_handle_form_submission() {
    if (!current_user_can('manage_options')) {
        wp_die('Vous n\'avez pas les permissions nécessaires pour effectuer cette action.');
    }

    check_admin_referer('frendly_censure_save_options_verify');

    if (isset($_POST['save_settings'])) {

        $plugin_enabled = isset($_POST['frendly_censure_enabled']) ? 'yes' : 'no';

        update_option('frendly_censure_enabled', $plugin_enabled);

        wp_redirect(admin_url('options-general.php?page=frendly-censure&status=success'));
        exit;

    } elseif (isset($_POST['save_words'])) {

        $insultes = isset($_POST['frendly_censure_insultes']) ? sanitize_textarea_field($_POST['frendly_censure_insultes']) : '';
        $gentil = isset($_POST['frendly_censure_gentil']) ? sanitize_textarea_field($_POST['frendly_censure_gentil']) : '';

        if (!empty($insultes) && !empty($gentil)) {
            $insultes_array = array_map('trim', explode(',', $insultes));
            $gentil_array = array_map('trim', explode(',', $gentil));

            if (count($insultes_array) == count($gentil_array)) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'filter';

                for ($i = 0; $i < count($insultes_array); $i++) {
                    $data = array(
                        'insultes' => $insultes_array[$i],
                        'gentil'   => $gentil_array[$i],
                    );

                    $format = array('%s', '%s');

                    $wpdb->insert($table_name, $data, $format);
                }

                wp_redirect(admin_url('options-general.php?page=frendly-censure&status=success'));
                exit;
            } else {
                wp_redirect(admin_url('options-general.php?page=frendly-censure&status=error'));
                exit;
            }
        } else {
            wp_redirect(admin_url('options-general.php?page=frendly-censure&status=error'));
            exit;
        }
    }
}
?>