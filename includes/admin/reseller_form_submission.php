<?php
function fr_reseller_form_submission() {
      
    global $wpdb;

    if(!current_user_can('edit_theme_options')){
        wp_die('Erro de segurança - Usuario sem permissão.');
    }

    if (!isset($_POST['action']) || $_POST['action'] !== 'fr_reseller_form_submission') {
        wp_die('Erro de segurança.');
    }

    // Verificar nonce
    check_admin_referer('fr_reseller_form_verify');

    $table_name = $wpdb->prefix . 'reseller_data';

    // Coletar dados do formulário
    $codigo = $_POST['codigo'];
    $title = $_POST['title'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $numero = $_POST['numero'];
    $zipcode = $_POST['zipcode'];
    $phone = $_POST['phone'];
    $whatsapp = $_POST['whatsapp'];
    $fax = $_POST['fax'];
    $email = $_POST['email'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $tratores = isset($_POST['tratores']) ? 1 : 0;
    $implementos = isset($_POST['implementos']) ? 1 : 0;

    // Inserir dados no banco de dados
    // Verificar se é uma atualização ou um novo cadastro
    if (isset($_POST['reseller_id'])) {
        $reseller_id = $_POST['reseller_id'];

        // Atualizar os dados no banco de dados
        $wpdb->update(
            $wpdb->prefix . 'reseller_data',
            array(
                'Codigo' => $codigo,
                'title' => $title,
                'country' => $country,
                'zipcode' => $zipcode,
                'phone' => $phone,
                'whatsapp' => $whatsapp,
                'fax' => $fax,
                'email' => $email,
                'state' => $state,
                'city' => $city,
                'address' => $address,
                'numero' => $numero,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'tratores' => $tratores,
                'implementos' => $implementos
            ),
            array('id' => $reseller_id),
            array(
                '%d', '%s', '%s', '%s', '%s', '%s',  '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d'
            ),
            array('%d')
        );
    }else{
        $wpdb->insert(
            $table_name,
            array(
                'Codigo' => $codigo,
                'title' => $title,
                'country' => $country,
                'state' => $state,
                'city' => $city,
                'address' => $address,
                'numero' => $numero,
                'zipcode' => $zipcode,
                'phone' => $phone,
                'whatsapp' => $whatsapp,
                'fax' => $fax,
                'email' => $email,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'tratores' => $tratores,
                'implementos' => $implementos,
            )
        );
    }
    // Redirecionar de volta para a página do plugin após o envio do formulário
    wp_redirect(admin_url('admin.php?page=fr_reseller'));
    exit;
}