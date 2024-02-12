<?php
/**
Plugin Name: ReSeller
Description: Plugin de gerenciamento de revendedores para WordPress
Version: 1.0.0
Author: Fartech TI
Author URI: https://farbosolutions.com/
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

//HOOKs
register_activation_hook(RESELLER_PLUGIN_URL, 'fr_activate_plugin');
register_deactivation_hook(RESELLER_PLUGIN_URL, 'fr_deactivate_plugin');
add_action('admin_init', 'fr_reseller_admin_init');
add_action('admin_menu', 'fr_reseller_admin_menus');

//SHORTCODE
?>