<?php
function fr_activate_plugin() {
    global $wpdb;

    $table_prefix = $wpdb->prefix . 'reseller_';
    
    // Criar a consulta SQL para criar a tabela
    $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}data (
    id INT NOT NULL AUTO_INCREMENT,
    Codigo INT(6) NOT NULL,
    title VARCHAR(255) NOT NULL,
    country VARCHAR(255) NOT NULL,
    state VARCHAR(2) NOT NULL,
    city VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    zipcode VARCHAR(10) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    whatsapp VARCHAR(20) NULL,
    fax VARCHAR(20) NULL,
    email VARCHAR(255) NOT NULL,
    latitude DECIMAL(10,8) NOT NULL,
    longitude DECIMAL(11,8) NOT NULL,
    tratores INT NOT NULL,
    implementos INT NOT NULL,
    PRIMARY KEY (id)
    );";

  // Executar a consulta SQL
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);


    
}
function fr_deactivate_plugin() {

}