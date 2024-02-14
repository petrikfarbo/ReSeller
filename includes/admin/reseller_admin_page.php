<?php

// Callback para a página "Listar e Cadastrar"
function fr_reseller_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'reseller_data';

    $search_name = isset($_GET['search_name']) ? $_GET['search_name'] : '';
    $search_code = isset($_GET['search_code']) ? $_GET['search_code'] : '';

    $where = '';
    if (!empty($search_name)) {
        $where .= " AND title LIKE '%$search_name%'";
    }
    if (!empty($search_code)) {
        $where .= " AND Codigo = $search_code";
    }

    $resellers = $wpdb->get_results("SELECT * FROM $table_name WHERE 1 $where", ARRAY_A);
    ?>
    <style>
        p.search-box{
            float: left;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
    <div class="wrap">
        <h1 class="wp-heading-inline">ReSeller - Lista</h1><br/><br/>
        <a href="<?php echo admin_url('admin.php?page=fr_reseller_cadastrar'); ?>" class="page-title-action">Adicionar Novo</a>
        <form method="get" action="">
            <input type="hidden" name="page" value="fr_reseller">
            <p class="search-box">
                <label class="screen-reader-text" for="search_name">Pesquisar por Nome:</label>
                <input type="text" id="search_name" name="search_name" value="<?php echo esc_attr($search_name); ?>" placeholder="Pesquisar por Nome">
                <label class="screen-reader-text" for="search_code">Pesquisar por Código:</label>
                <input type="text" id="search_code" name="search_code" value="<?php echo esc_attr($search_code); ?>" placeholder="Pesquisar por Código">
                <input type="submit" id="search-submit" class="button" value="Pesquisar Revendedoras">
            </p>
        </form>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Título</th>
                    <th>País</th>
                    <th>Estado</th>
                    <th>Cidade</th>
                    <th>Endereço</th>
                    <th>CEP</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Tratores</th>
                    <th>Implementos</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resellers) {
                    foreach ($resellers as $reseller) {
                        echo "<tr>";
                        echo "<td>{$reseller['id']}</td>";
                        echo "<td>{$reseller['Codigo']}</td>";
                        echo "<td>{$reseller['title']}</td>";
                        echo "<td>{$reseller['country']}</td>";
                        echo "<td>{$reseller['state']}</td>";
                        echo "<td>{$reseller['city']}</td>";
                        echo "<td>{$reseller['address']}, {$reseller['numero']}</td>";
                        echo "<td>{$reseller['zipcode']}</td>";
                        echo "<td>{$reseller['phone']}</td>";
                        echo "<td>{$reseller['email']}</td>";
                        echo "<td>" . ($reseller['tratores'] == 1 ? 'Sim' : 'Não') . "</td>";
                        echo "<td>" . ($reseller['implementos'] == 1 ? 'Sim' : 'Não') . "</td>";
                        echo "<td><a href='" . admin_url('admin.php?page=fr_reseller_cadastrar&reseller_id=' . $reseller['id']) . "'>Editar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='17'>Nenhuma revendedora encontrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
