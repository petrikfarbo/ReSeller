<?php
function fr_reseller_form_submission() {
      
    global $wpdb;

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
    $zipcode = $_POST['zipcode'];
    $phone = $_POST['phone'];
    $whatsapp = $_POST['whatsapp'];
    $fax = $_POST['fax'];
    $email = $_POST['email'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $tractors = isset($_POST['tractors']) ? 1 : 0;
    $microtractors = isset($_POST['microtractors']) ? 1 : 0;

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
                'latitude' => $latitude,
                'longitude' => $longitude,
                'tractors' => $tractors,
                'microtractors' => $microtractors
            ),
            array('id' => $reseller_id),
            array(
                '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d'
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
                'zipcode' => $zipcode,
                'phone' => $phone,
                'whatsapp' => $whatsapp,
                'fax' => $fax,
                'email' => $email,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'tractors' => $tractors,
                'microtractors' => $microtractors,
            )
        );
    }
    // Redirecionar de volta para a página do plugin após o envio do formulário
    wp_redirect(admin_url('admin.php?page=fr_reseller'));
    exit;
}