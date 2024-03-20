<?php
require_once('../../../wp-load.php');
// Redireciona para o site principal do cliente
header("Location: " . home_url());
exit; // Certifica-se de que nenhum código adicional seja executado após o redirecionamento
