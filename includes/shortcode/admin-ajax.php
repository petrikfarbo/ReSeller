<?php

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
}else{
    echo "Fail"; 
}

?>
