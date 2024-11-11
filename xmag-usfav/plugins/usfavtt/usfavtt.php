<?php
/*
Plugin Name: USFAV TT
Description: Fonctionnalité pour les besoins du site USFAV TT
Version: 1.0
Author: Vincent Rousseau
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

#region Plugin ACF requis

// Plugin ACF obligatoire
function usfavtt_activate_plugin() {
    if ( ! is_plugin_active( 'advanced-custom-fields/acf.php' ) && current_user_can( 'activate_plugins' ) ) {
        // Désactive le plugin
        deactivate_plugins( plugin_basename( __FILE__ ) );
        // Affiche un message d'erreur
        wp_die( 'Ce plugin nécessite que le plugin Advanced Custom Fields soit activé. <br><a href="' . admin_url( 'plugins.php' ) . '">Retour à la page des plugins</a>' );
    }
}
register_activation_hook( __FILE__, 'usfavtt_activate_plugin' );

// Message d'erreur dans l'administration si ACF est désactivé
function usfavtt_check_acf_dependency() {
    if ( ! is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
        add_action( 'admin_notices', 'usfavtt_acf_missing_notice' );
    }
}
add_action( 'admin_init', 'usfavtt_check_acf_dependency' );

#endregion

#region Menu d'administration

// Initialisation du menu d'administration
function usfavtt_plugin_menu() {
    add_menu_page(
        'USFAV TT',
        'USFAV TT',
        'manage_options',
        'usfavtt_settings',
        'usfavtt_settings_page'
    );
}
add_action('admin_menu', 'usfavtt_plugin_menu');

// Contenu de la page des paramètres
function usfavtt_settings_page() {
    // Vérifie si le formulaire a été soumis
    if (isset($_POST['usfavtt_save_api_settings'])) {
        // Vérifie le nonce pour la sécurité
        check_admin_referer('usfavtt_api_settings_nonce');

        // Enregistre les valeurs dans la base de données
        update_option('usfavtt_api_login', sanitize_text_field($_POST['api_login']));
        update_option('usfavtt_api_password', sanitize_text_field($_POST['api_password']));
        update_option('usfavtt_api_team_id', sanitize_text_field($_POST['api_team_id']));
        update_option('usfavtt_api_token_url', sanitize_text_field($_POST['api_token_url']));

        echo '<div class="updated"><p>Paramètres enregistrés avec succès.</p></div>';
    }

    // Récupère les valeurs actuelles
    $apiLogin = get_option('usfavtt_api_login');
    $apiPassword = get_option('usfavtt_api_password');
    $apiTeamId = get_option('usfavtt_api_team_id');
    $apiTokenUrl = get_option('usfavtt_api_token_url');

    // Formulaire HTML pour saisir les clés API
    ?>
    <div class="wrap">
        <h1>Paramètres du plugin USFAVTT</h1>
        <form method="post" action="">
            <?php wp_nonce_field('usfavtt_api_settings_nonce'); ?>

            <h2>Paramètres de l'API USFAVTT</h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="api_login">API Login</label></th>
                    <td><input type="text" name="api_login" id="api_login" value="<?php echo esc_attr($apiLogin); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="api_password">API Password</label></th>
                    <td><input type="password" name="api_password" id="api_password" value="<?php echo esc_attr($apiPassword); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="api_team_id">ID du club</label></th>
                    <td><input type="text" name="api_team_id" id="api_team_id" value="<?php echo esc_attr($apiTeamId); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="api_token_url">Token permettant l'accès à l'URL</label></th>
                    <td><input type="text" name="api_token_url" id="api_token_url" value="<?php echo esc_attr($apiTokenUrl); ?>" class="regular-text" /></td>
                </tr>
            </table>
            <input type="hidden" name="usfavtt_save_api_settings" value="1" />
            <?php submit_button('Enregistrer les paramètres'); ?>
        </form>
    </div>
    <?php
}

#endregion

#region Post-type Joueur

function usfavtt_register_post_type() {
    $labels = array(
        'name'                  => _x( 'Joueurs', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Joueur', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Joueurs', 'text_domain' ),
        'name_admin_bar'        => __( 'Joueur', 'text_domain' ),
    );

    $args = array(
        'label'                 => __( 'Joueur', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
        'public'                => true,
        'has_archive'           => true,
        'show_in_rest'          => true,
    );

    register_post_type( 'joueur', $args );
}
add_action( 'init', 'usfavtt_register_post_type' );

// Champs liés au joueur
add_action( 'acf/init', function() {
    if( function_exists('acf_add_local_field_group') ) {
        // Post-type Joueur
        acf_add_local_field_group(array(
            'key' => 'group_joueur',
            'title' => 'Informations joueur',
            'fields' => array(
                array(
                    'key' => 'nom',
                    'label' => 'Nom',
                    'name' => 'nom',
                    'type' => 'text',
                ),
                array(
                    'key' => 'prenom',
                    'label' => 'Prénom',
                    'name' => 'prenom',
                    'type' => 'text',
                ),
                array(
                    'key' => 'surnom',
                    'label' => 'Surnom',
                    'name' => 'surnom',
                    'type' => 'text',
                ),
                array(
                    'key' => 'sexe',
                    'label' => 'Sexe',
                    'name' => 'sexe',
                    'type' => 'select',
                    'choices' => array(
                        'homme' => 'Homme',
                        'femme' => 'Femme',
                    ),
                ),
                array(
                    'key' => 'points_fftt',
                    'label' => 'Points FFTT',
                    'name' => 'points_fftt',
                    'type' => 'number',
                    'default_value' => 500,
                ),
                array(
                    'key' => 'points_fftt_debut_saison',
                    'label' => 'Points FFTT début de saison',
                    'name' => 'points_fftt_debut_saison',
                    'type' => 'number',
                ),
                array(
                    'key' => 'points_fftt_mensuel',
                    'label' => 'Points FFTT mensuel',
                    'name' => 'points_fftt_mensuel',
                    'type' => 'number',
                ),
                array(
                    'key' => 'points_fftt_virtuel',
                    'label' => 'Points FFTT Virtuel',
                    'name' => 'points_fftt_virtuel',
                    'type' => 'number',
                ),
                array(
                    'key' => 'progression_points_fftt',
                    'label' => 'Progression Points FFTT',
                    'name' => 'progression_points_fftt',
                    'type' => 'number',
                ),
                array(
                    'key' => 'date_naissance',
                    'label' => 'Date de naissance',
                    'name' => 'date_naissance',
                    'type' => 'date_picker',
                    'return_format' => 'Y-m-d',
                ),
                array(
                    'key' => 'categorie',
                    'label' => 'Catégorie',
                    'name' => 'categorie',
                    'type' => 'text',
                ),
                array(
                    'key' => 'main',
                    'label' => 'Main',
                    'name' => 'main',
                    'type' => 'select',
                    'choices' => array(
                        'gauche' => 'Gaucher',
                        'droite' => 'Droitier',
                    ),
                ),
                array(
                    'key' => 'taille',
                    'label' => 'Taille',
                    'name' => 'taille',
                    'type' => 'number',
                    'append' => 'cm',
                ),
                array(
                    'key' => 'poids',
                    'label' => 'Poids',
                    'name' => 'poids',
                    'type' => 'number',
                    'append' => 'kg',
                ),
                array(
                    'key' => 'date_arrivee_club',
                    'label' => 'Date d\'arrivée au club',
                    'name' => 'date_arrivee_club',
                    'type' => 'date_picker',
                    'return_format' => 'Y-m-d',
                ),
                array(
                    'key' => 'numero_licence',
                    'label' => 'Numéro de Licence',
                    'name' => 'numero_licence',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_equipe',
                    'label' => 'Équipe',
                    'name' => 'equipe',
                    'type' => 'post_object',
                    'post_type' => array('equipe'),
                    'return_format' => 'object',
                    'ui' => 1,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'joueur',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
        ));

        // Post-type Calendrier
        acf_add_local_field_group(array(
            'key' => 'group_calendar',
            'title' => 'Informations calendrier',
            'fields' => array(
                array(
                    'key' => 'color',
                    'label' => 'Couleur',
                    'name' => 'couleur',
                    'type' => 'color_picker',
                    'default_value' => '#000000',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'calendar',
                    ),
                ),
            ),
        ));
    }
});

#endregion

#region Post-type Equipe

function usfavtt_register_post_type_equipe() {
    $labels = array(
        'name'                  => _x( 'Équipes', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Équipe', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Équipes', 'text_domain' ),
        'name_admin_bar'        => __( 'Équipe', 'text_domain' ),
    );

    $args = array(
        'label'                 => __( 'Équipe', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
        'public'                => true,
        'has_archive'           => false,
        'show_in_rest'          => true,
    );

    register_post_type( 'equipe', $args );
}
add_action( 'init', 'usfavtt_register_post_type_equipe' );

#endregion

#region ShortCode [ranking]

// Shortcode pour afficher la progression des joueurs
// [ranking]
function shortcode_show_ranking() {
    $args = [
        'post_type'  => 'joueur',
        // Exclusion des joueurs avec fftt_points = 500 & pas de progression
        'meta_query' => [
            'relation' => 'AND',
            [
                'key'     => 'points_fftt',
                'value'   => 500,
                'compare' => '!=',
            ],
            [
                'key'     => 'progression_points_fftt',
                'value'   => 0,
                'compare' => '!=',
            ]
        ],
        'orderby'  => 'meta_value_num',
        'meta_key' => 'progression_points_fftt',
        'order'    => 'DESC',
        'posts_per_page' => -1,
        'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'), // Tous les statuts
    ];

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        // Affichage Tableau HTML
        $output = '
            <table id="ranking">
                <thead>
                    <tr>
                        <th class="rank">#</th>
                        <th class="joueur">Joueur</th>
                        <th class="points">Points FFTT</th>
                        <th class="progression">Progression</th>
                    </tr>
                </thead>
                <tbody>';

        $rank = 1;

        while ($query->have_posts()) {
            $query->the_post();
            
            $points = get_post_meta(get_the_ID(), 'points_fftt', true);
            $points_virtuels = get_post_meta(get_the_ID(), 'points_fftt_virtuel', true);
            $progression = (int) get_post_meta(get_the_ID(), 'progression_points_fftt', true);

            $output .= '
                <tr>
                    <td class="rank">' . $rank++ . '</td>
                    <td class="joueur"><a href="' . get_the_permalink(get_the_ID()) . '">' . esc_html(get_the_title()) . '</a></td>
                    <td class="points">' . esc_html($points) . ' > ' . esc_html($points_virtuels) . '</td>
                    <td class="progression">' . ($progression > 0 ? '+' : '') . esc_html($progression) . ' <span class="emoji">' . get_progression_emoji($progression) . '</span></td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';

        wp_reset_postdata();

        return $output;
    }
}
add_shortcode('ranking', 'shortcode_show_ranking');

function get_progression_emoji($progression) {
    return match (true) {
        $progression <= -100 => '🤡',
        $progression <= -50 => '😭',
        $progression <= -40 => '🤐',
        $progression <= -30 => '😒',
        $progression <= -20 => '🌧️',
        $progression <= -10 => '🥺',
        $progression < 0 => '😥',
        $progression >= 200 => '👑',
        $progression >= 100 => '🚀',
        $progression >= 70 => '🏆',
        $progression >= 60 => '😎',
        $progression >= 50 => '🏅',
        $progression >= 40 => '🔥',
        $progression >= 30 => '💪',
        $progression >= 20 => '😇',
        $progression >= 10 => '😏',
        $progression >= 0 => '🏓',
        default => '🏓',
    };
}

#endregion

#region ShortCode [get_match]

// ShortCode pour afficher un match entre 2 équipes
// A finaliser
// [get_match link="ton-lien-rencontre"]
// function shortcode_show_match_details($atts) {
//     $atts = shortcode_atts(
//         [
//             'link' => '', // Identifiant ou lien de la rencontre (TODO: à voir comment récupérer ce truc)
//         ],
//         $atts,
//         'get_match',
//     );

//     $matckLink = $atts['link'];
//     if (empty($matckLink)) {
//         return 'Identifiant de la rencontre manquant.';
//     }

//     // C'est déjà en cache ?
//     $optionKey = 'match_details_' . md5($matckLink);
//     $matchDetails = get_option($optionKey);

//     // Si les données ne sont pas en cache on appelle l'API
//     if (false === $matchDetails) {
//         // Appel à l'API FFTT
//         // TODO: voir pour intégrer ça proprement
//         require __DIR__ . '/vendor/autoload.php';
//         $ffttApi = new \Alamirault\FFTTApi\Service\FFTTApi('SW970', 'zVfeQ23QN9');

//         try {
//             $matchDetails = $ffttApi->retrieveRencontreDetailsByLien($matckLink);

//             // Stockage en DB
//             update_option($optionKey, $matchDetails);
//         } catch (Exception $e) {
//             return 'Erreur lors de la récupération des détails de la rencontre : ' . $e->getMessage();
//         }
//     }

//     // Affichage Tableau HTML
//     // TODO: voir pour récupérer le score global + DATE (dateReelle)
//     $output = '<table>';
//     $output .= '<thead><tr><th>Joueur A</th><th>Joueur B</th><th>Score</th></tr></thead>';
//     $output .= '<tbody>';

//     foreach ($matchDetails->getParties() as $partie) {
//         $output .= '<tr>';
//         $output .= '<td>' . esc_html($partie->getNomJoueurA()) . '</td>';
//         $output .= '<td>' . esc_html($partie->getNomJoueurB()) . '</td>';
//         $output .= '<td>' . esc_html(implode(', ', $partie->getScores())) . '</td>';
//         $output .= '</tr>';
//     }

//     $output .= '</tbody></table>';

//     return $output;
// }
// add_shortcode('get_match', 'shortcode_show_match_details');

#endregion