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
  state VARCHAR(2) NULL,
  city VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
  numero VARCHAR(50) NULL,
  zipcode VARCHAR(15) NULL,
  phone VARCHAR(50) NOT NULL,
  phone2 VARCHAR(50) NULL,
  whatsapp VARCHAR(50) NULL,
  fax VARCHAR(50) NULL,
  email VARCHAR(255) NULL,
  email2 VARCHAR(255) NULL,
  latitude DECIMAL(10,8) NULL,
  longitude DECIMAL(11,8) NULL,
  tratores INT NOT NULL Default 0,
  implementos INT NOT NULL Default 0,
  PRIMARY KEY (id)
  );";

  // Executar a consulta SQL
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
    
  // Criar a consulta SQL para criar a segunda tabela
  $sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}atuacao (
  id INT NOT NULL AUTO_INCREMENT,
  id_revenda INT NOT NULL,
  country VARCHAR(255) NOT NULL,
  state VARCHAR(2) NOT NULL,
  city VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_revenda) REFERENCES {$table_prefix}data(id)
  );";

  // Executar a consulta SQL
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}
function fr_deactivate_plugin() {

}