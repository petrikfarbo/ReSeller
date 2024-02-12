<?php
function fr_reseller_admin_menus(){
    add_menu_page(
        'ReSeller - Plugin', // Título da Página
        'ReSeller', // Título do Menu
        'edit_theme_options', // capability
        'fr_reseller', // Slug
        'fr_reseller_admin_page', // Página
        'dashicons-location'
    );

    // Adiciona o submenu "Listar e Cadastrar"
    add_submenu_page(
        'fr_reseller', // Slug do menu pai
        'Cadastrar', // Título da página
        'ReSeller - Cadastrar', // Título do submenu
        'edit_theme_options', // capability
        'fr_reseller_cadastrar', // Slug da página
        'fr_reseller_cadastrar_page' // Callback da página
    );
}

// Callback para a página "Listar e Cadastrar"
function fr_reseller_list_cadastrar_page() {
    // Coloque aqui o código para a página "Listar e Cadastrar"
}
