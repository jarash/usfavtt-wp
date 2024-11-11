<?php
/**
 * Theme functions and definitions
 * 
 * Le thème est livré avec un plugin Custom pour la gestion des joueurs, etc
 */

#region Copie du Plugin USFAV TT

// Nom du plugin
define('PLUGIN_NAME', 'usfavtt');
define('PLUGIN_PATH', get_stylesheet_directory() . '/plugins/usfavtt');

// Vérifie si le plugin est activé
function usfav_check_and_activate_plugin() {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');

    // Chemin vers le plugin dans WordPress
    $plugin_main_file = PLUGIN_NAME . '/' . PLUGIN_NAME . '.php';

    
    // Si le plugin n'est pas activé
    if (!is_plugin_active($plugin_main_file)) {
        // Et que le plugin n'est pas dans le dossier des plugins WordPress
        if (!file_exists(WP_PLUGIN_DIR . '/' . PLUGIN_NAME)) {
            // Copie du plugin
            copy_directory(PLUGIN_PATH, WP_PLUGIN_DIR . '/' . PLUGIN_NAME);
        }

        // Activation du plugin
        activate_plugin(WP_PLUGIN_DIR . '/' . $plugin_main_file);
    }
}
add_action('after_setup_theme', 'usfav_check_and_activate_plugin');

// Fonction utilitaire pour copier un dossier
function copy_directory($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copy_directory($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

#endregion

#region CSS pour le Calendrier

function usfav_enqueue_custom_calendar_css() {
    wp_enqueue_style('usfav-calendar', get_stylesheet_directory_uri(__FILE__) . '/usfav-calendar.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'usfav_enqueue_custom_calendar_css', 100);

function css_calendar_list() {
    $args = array(
        'post_type' => 'calendar',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );

    $calendar_query = new WP_Query($args);

    $output = '<style type="text/css">';
    if ($calendar_query->have_posts()) {
        while ($calendar_query->have_posts()) {
            $calendar_query->the_post();
            $output .= '.simcal-default-calendar-grid .simcal-events > .simcal-events-calendar-' . get_the_ID() . '{background: ' . get_field('couleur', get_the_ID()) . ' !important;} ';
            $output .= 'td:has(.simcal-events-calendar-' . get_the_ID() . ') .simcal-day-number {background: ' . get_field('couleur', get_the_ID()) . ' !important;} ';
        }
    }

    wp_reset_postdata();

    $output .= '</style>';

    return $output;
}
function register_css_calendar_list_shortcode() {
    add_shortcode('css_calendar_list', 'css_calendar_list');
}
add_action('init', 'register_css_calendar_list_shortcode');

#endregion
