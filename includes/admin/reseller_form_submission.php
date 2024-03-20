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
    $table_atuacao = $wpdb->prefix . 'reseller_atuacao';

    // Coletar e sanitizar dados do formulário
    $codigo = sanitize_text_field($_POST['codigo']);
    $title = strtoupper(sanitize_text_field($_POST['title']));
    $country = ucwords(sanitize_text_field($_POST['country']));
    $state = ucwords(sanitize_text_field($_POST['state']));
    $city = ucwords(sanitize_text_field($_POST['city']));
    $address = ucwords(sanitize_text_field($_POST['address']));
    $numero = sanitize_text_field($_POST['numero']);
    $zipcode = sanitize_text_field($_POST['zipcode']);
    $phone = sanitize_text_field($_POST['phone']);
    $phone2 = sanitize_text_field($_POST['phone2']);
    $whatsapp = sanitize_text_field($_POST['whatsapp']);
    $fax = sanitize_text_field($_POST['fax']);
    $email = sanitize_email($_POST['email']);
    $email2 = sanitize_email($_POST['email2']);
    $latitude = sanitize_text_field($_POST['latitude']);
    $longitude = sanitize_text_field($_POST['longitude']);
    $tratores = isset($_POST['tratores']) ? 1 : 0;
    $implementos = isset($_POST['implementos']) ? 1 : 0;

    $selectedCities = $_POST['city-list'];

   

    // Inserir dados no banco de dados
    // Verificar se é uma atualização ou um novo cadastro
    if (isset($_POST['reseller_id'])) {
        $reseller_id = $_POST['reseller_id'];

        // Atualizar os dados no banco de dados
        $wpdb->update(
            $table_name,
            array(
                'Codigo' => $codigo,
                'title' => $title,
                'country' => $country,
                'zipcode' => $zipcode,
                'phone' => $phone,
                'phone2' => $phone2,
                'whatsapp' => $whatsapp,
                'fax' => $fax,
                'email' => $email,
                'email2' => $email2,
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
                '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d'
            )

        );

        $wpdb->delete(
            $table_atuacao,
            array(
                'id_revenda' => $reseller_id
            )
        );

        foreach ($selectedCities as $city) {
            $wpdb->insert(
                $table_atuacao,
                array(
                    'id_revenda' => $reseller_id,
                    'state' => $state,
                    'country' => $country,
                    'city' => $city
                )
            );
        }

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
                'phone2' => $phone2,
                'whatsapp' => $whatsapp,
                'fax' => $fax,
                'email' => $email,
                'email2' => $email2,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'tratores' => $tratores,
                'implementos' => $implementos
            )
        );

        // Pega o ID da última inserção
        $empresa_id = $wpdb->insert_id;

        // Insere na tabela de atuação para cada cidade
        foreach ($selectedCities as $city) {
            $wpdb->insert(
                $table_atuacao,
                array(
                    'id_revenda' => $empresa_id,
                    'state' => $state,
                    'country' => $country,
                    'city' => $city
                )
            );
        }
    }
    // Redirecionar de volta para a página do plugin após o envio do formulário
    wp_redirect(admin_url('admin.php?page=fr_reseller'));
    exit;
}