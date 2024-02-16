<?php
// Inicia o buffer de saída
ob_start(); 

if (isset($_POST['reseller_data'])) {

    require_once('../../../../../wp-load.php');
    global $wpdb;
    if ( !isset($wpdb) ) {
        die("Erro: Não foi possível inicializar o objeto global \$wpdb. Certifique-se de estar executando este script dentro do contexto do WordPress.");
    }

    $table_name = $wpdb->prefix . 'reseller_data';
    $resellers_data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    if ($wpdb->last_error) {
        echo "Ocorreu um erro ao tentar recuperar os dados: " . $wpdb->last_error;
    }

    echo json_encode(array("resellers_data" => $resellers_data));
}

// Dados da requisição
if (isset($_POST['zipcode'])) {
    $cep = $_POST['zipcode'];

    $url = 'https://www.cepaberto.com/api/v3/cep?cep=' . $cep;

    // Configurações da requisição
    $options = [
        'http' => [
            'header' => "Authorization: Token token=d03ab2505d4de0665adb0ff7ae2914ba\r\n"
        ]
    ];

    // Criando contexto da requisição
    $context = stream_context_create($options);

    // Fazendo a requisição
    $response = file_get_contents($url, false, $context);

    // Se a resposta não estiver vazia
    if ($response !== false) {
        // Decodificando o JSON
        $data = json_decode($response, true);
        
        // Verificando se os dados foram obtidos corretamente
        if ($data !== null) {
            
            // Exibindo os dados
            echo json_encode(array(
                    "latitude" => $data['latitude'],
                    "longitude" => $data['longitude'],
                    "address" => $data['logradouro'],
                    "city" => $data['cidade']['nome'],
                    "state" => $data['estado']['sigla']
                    )
            );

        } else {
            echo "Erro ao decodificar a resposta JSON.\n";
        }
    } else {
        echo "Erro ao obter resposta da API.\n";
    }
}
// Limpa o buffer de saída e exibe seu conteúdo
echo ob_get_clean();
?>
