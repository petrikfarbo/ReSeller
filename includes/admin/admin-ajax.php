<?php

// Dados da requisição

// Dados da requisição
if (isset($_POST['zipcode'])) {
    $cep = $_POST['zipcode'];

    $headers = [
      'Accept: */*',
      'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36',
      'Accept-Language: pt-BR,pt;q=0.9',
      'Connection: keep-alive',
      'Authorization: Token token=d03ab2505d4de0665adb0ff7ae2914ba',
    ];
    
    $url = 'https://www.cepaberto.com/api/v3/cep?cep=' . $cep;
    
    // Configurações da requisição
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ];
    
    // Inicializa o cURL
    $curl = curl_init();
    curl_setopt_array($curl, $options);
    
    // Fazendo a requisição
    $response = curl_exec($curl);

    // Fecha a sessão cURL
    curl_close($curl);

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

?>
