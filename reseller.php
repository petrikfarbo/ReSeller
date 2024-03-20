<?php
/**
Plugin Name: ReSeller
Description: Plugin de gerenciamento de revendedores para WordPress
Version: 2.1.0
Author: Fartech TI
Author URI: https://www.farbosolutions.com.br/
Text Domain: reseller
*/
if (!function_exists('add_action')) {
    //echo __('Erro: Plugin não pode ser chamado diretamente', 'reseller');
    exit;
}
//SETUP
define('RESELLER_PLUGIN_URL', __FILE__);

//INCLUDES
include 'includes/activation.php';
include 'includes/admin/admin_init.php';
include 'includes/admin/menus.php';
include 'includes/admin/reseller_admin_page.php';
include 'includes/admin/reseller_cadastrar_page.php';
include 'includes/shortcode/reseller_form_pt.php';
include 'includes/shortcode/reseller_list_pt.php';
include 'includes/shortcode/reseller_contact_pt.php';

include 'includes/shortcode/reseller_form_en.php';
include 'includes/shortcode/reseller_list_en.php';
include 'includes/shortcode/reseller_contact_en.php';

include 'includes/shortcode/reseller_form_es.php';
include 'includes/shortcode/reseller_list_es.php';
include 'includes/shortcode/reseller_contact_es.php';

//HOOKs
register_activation_hook(RESELLER_PLUGIN_URL, 'fr_activate_plugin');
register_deactivation_hook(RESELLER_PLUGIN_URL, 'fr_deactivate_plugin');

add_action('admin_init', 'fr_reseller_admin_init');
add_action('admin_menu', 'fr_reseller_admin_menus');

//SHORTCODE
add_shortcode('reseller_form_pt', 'fr_reseller_form_pt_shortcode');
add_shortcode('reseller_list_pt', 'fr_reseller_list_pt_shortcode');
add_shortcode('reseller_contact_pt', 'fr_reseller_contact_pt_shortcode');

add_shortcode('reseller_form_en', 'fr_reseller_form_en_shortcode');
add_shortcode('reseller_list_en', 'fr_reseller_list_en_shortcode');
add_shortcode('reseller_contact_en', 'fr_reseller_contact_en_shortcode');

add_shortcode('reseller_form_es', 'fr_reseller_form_es_shortcode');
add_shortcode('reseller_list_es', 'fr_reseller_list_es_shortcode');
add_shortcode('reseller_contact_es', 'fr_reseller_contact_es_shortcode');
?>