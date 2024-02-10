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
    echo __('Erro: Plugin não pode ser chamado diretamente', 'reseller');
    exit;
}
//SETUP

//INCLUDESs
include ('includes/activate.php');

//HOOKs
register_activation_hook(__FILE__, 'fr_activate_plugin');

//SHORTCODE
?>