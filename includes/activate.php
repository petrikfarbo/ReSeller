<?php
function fr_activate_plugin() {
    if (version_compare(get_bloginfo('version'), '4.5', '<')){
        wp_die(__('Você precisa atualizar o seu WordPress para usar este plugin', 'reseller'));
    }
}