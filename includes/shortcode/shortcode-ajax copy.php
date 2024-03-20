<?php

// Inicia o buffer de saída
ob_start();

// Função para lidar com erros
function handle_error($message) {
    echo json_encode(array("error" => $message));
    exit;
}

// Verifica se a requisição foi feita via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    handle_error("Acesso não autorizado.");
}

// Verifica se há dados do revendedor na requisição
if (isset($_POST['reseller_data'])) {
    // Inclui o arquivo do WordPress
    require_once('../../../../../wp-load.php');
    global $wpdb;
    if (!isset($wpdb)) {
        handle_error("Erro: Não foi possível inicializar o objeto global \$wpdb. Certifique-se de estar executando este script dentro do contexto do WordPress.");
    }

    // Obtém os dados dos revendedores
    $table_name = $wpdb->prefix . 'reseller_data';
    $resellers_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY title ASC", ARRAY_A);

    // Verifica se houve erro ao acessar o banco de dados
    if ($wpdb->last_error) {
        handle_error("Ocorreu um erro ao tentar recuperar os dados: " . $wpdb->last_error);
    }

    // Retorna os dados dos revendedores em formato JSON
    echo json_encode(array("resellers_data" => $resellers_data));
}

// Verifica se há CEP na requisição
if (isset($_POST['zipcode'])) {
    $cep = $_POST['zipcode'];

    // Define a URL da API para obter os dados de latitude e longitude
    $url = 'https://brasilapi.com.br/api/cep/v1/' . $cep;

    // Configura a requisição cURL
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true
    ]);

    // Executa a requisição e obtém a resposta
    $response = curl_exec($ch);

    // Verifica se a requisição foi bem-sucedida
    if ($response === false) {
        handle_error("Erro ao acessar a API de CEP.");
    }

    // Decodifica a resposta JSON
    $data = json_decode($response, true);

    // Verifica se a resposta contém estado e cidade
    if (!isset($data['state']) || !isset($data['city'])) {
        handle_error("CEP inválido ou não encontrado.");
    }

    // Monta a URL para a segunda requisição
    $nominatim_url = 'https://nominatim.openstreetmap.org/search?format=json&country=Brasil&state=' . urlencode($data['state']) . '&city=' . urlencode($data['city']);

    // Configura a segunda requisição cURL
    $ch2 = curl_init($nominatim_url);
    curl_setopt_array($ch2, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => array(
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36'
        )
    ]);

    // Executa a segunda requisição e obtém a resposta
    $response2 = curl_exec($ch2);

    // Verifica se a requisição foi bem-sucedida
    if ($response2 === false) {
        handle_error("Erro ao acessar a API de geolocalização.");
    }

    // Decodifica a resposta JSON
    $data2 = json_decode($response2, true);

    // Verifica se há pelo menos um resultado retornado
    if (empty($data2)) {
        handle_error("Nenhum resultado encontrado para a localização.");
    }

    // Obtém o primeiro resultado
    $first_result = $data2[0];

    // Monta o array conforme especificado
    $result_array = array(
        "latitude" => $first_result['lat'],
        "longitude" => $first_result['lon'],
        "address" => $first_result['name'],
        "city" => $data['city'],
        "state" => $data['state']
    );

    // Exibe o array no formato JSON
    echo json_encode($result_array);

    // Fecha a conexão cURL
    curl_close($ch2);
}

// Limpa o buffer de saída e exibe seu conteúdo
echo ob_get_clean();

?>
