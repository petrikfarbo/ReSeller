<?php
function fr_reseller_admin_menus(){
    add_menu_page(
        'ReSeller - Plugin', //Titulo da Pagina
        'ReSeller', //Titulo do Menu
        'edit_theme_options', //capability
        'fr_reseller', //Slug
        'fr_reseller_admin_page', //pagina
        'dashicons-location'
    );
}