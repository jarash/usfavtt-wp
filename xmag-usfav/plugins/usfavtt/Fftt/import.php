<?php
echo '<pre>';
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Jarash\FfttApi\Service\FFTTApi;

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once '../../../../wp-load.php';
require_once __DIR__ . '/Fftt.php';

$tokenUrl = get_option('usfavtt_api_token_url');

if (!isset($_GET['token']) || !$tokenUrl || $_GET['token'] !== $tokenUrl) {
    die;
}

$apiLogin = get_option('usfavtt_api_login');
$apiPassword = get_option('usfavtt_api_password');
$apiTeamId = get_option('usfavtt_api_team_id');

$ffttService = new FfttService($apiLogin, $apiPassword, $apiTeamId);
$players = $ffttService->ffttApi->listJoueursByClub($ffttService->usfavId);

foreach ($players as $player) {
    $licenceId = $player->getLicence();

    // Appels API spécifiques
    $playerDetail = $ffttService->ffttApi->retrieveJoueurDetails($licenceId);
    $virtualPoints = $ffttService->ffttApi->retrieveVirtualPoints($licenceId);

    // Récupération du joueur dans le WP :
    $args = [
        'post_type'  => 'joueur',
        'meta_query' => [
            [
                'key'   => 'numero_licence',
                'value' => $licenceId,
                'compare' => '=',
            ],
        ],
        'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'), // Tous les statuts
    ];

    $query = new WP_Query($args);
    $postId = null;

    // Si le joueur existe, on récupère son post ID
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $postId = get_the_ID();
        }
    } else {
        // Si le joueur n'existe pas, on le crée
        $post_data = [
            'post_title'    => $playerDetail->getPrenom() . ' ' . $playerDetail->getNom(),
            'post_content'  => '',  // Tu peux ajouter plus de détails si nécessaire
            'post_status'   => 'draft',
            'post_type'     => 'joueur',
        ];

        // Création du post
        $postId = wp_insert_post($post_data);
        update_post_meta($postId, 'numero_licence', $licenceId);
    }

    // Mise à jour des informations du joueur
    if ($postId) {
        // Récupération des détails du joueur
        update_post_meta($postId, 'nom', $playerDetail->getNom());
        update_post_meta($postId, 'prenom', $playerDetail->getPrenom());
        update_post_meta($postId, 'sexe', $playerDetail->isHomme() ? 'homme' : 'femme');
        update_post_meta($postId, 'categorie', $playerDetail->getCategorie());

        // Points
        update_post_meta($postId, 'points_fftt', $playerDetail->getPointsLicence());
        update_post_meta($postId, 'points_fftt_debut_saison', $playerDetail->getPointDebutSaison());
        update_post_meta($postId, 'points_fftt_mensuel', $playerDetail->getPointsMensuel());
        
        // Récupération et mise à jour des points virtuels
        update_post_meta($postId, 'points_fftt_virtuel', $virtualPoints->getVirtualPoints());
        update_post_meta($postId, 'progression_points_fftt', $virtualPoints->getSeasonlyPointsWon());
    }
}

    
die("FIN");
