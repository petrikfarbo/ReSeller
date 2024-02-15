<?php
// Determine o nome do host (domínio)
//$host = $_SERVER['HTTP_HOST'];

// Determine a raiz do URL do WordPress
//$wordpress_path = '/wordpress'; // Ajuste conforme necessário
// Inclui o arquivo wp-load.php para carregar o WordPress
require_once('../../../../../wp-load.php');

// Inicia o buffer de saída
ob_start(); 
// Obtém uma referência ao objeto global do WordPress
global $wpdb;

// Verifica se $wpdb foi inicializado corretamente
if ( !isset($wpdb) ) {
    die("Erro: Não foi possível inicializar o objeto global \$wpdb. Certifique-se de estar executando este script dentro do contexto do WordPress.");
}

// Define o nome da tabela usando o prefixo do WordPress
$table_name = $wpdb->prefix . 'reseller_data';

// Executa a consulta SQL
$resellers_data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

// Verifica se houve algum erro durante a consulta
if ($wpdb->last_error) {
    echo "Ocorreu um erro ao tentar recuperar os dados: " . $wpdb->last_error;
} else {
    // Imprime os dados recuperados
    print_r(json_encode($resellers_data));
}

// Limpa o buffer de saída e exibe seu conteúdo
echo ob_get_clean();
?>
