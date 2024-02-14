<?php
function fr_reseller_list_shortcode(){
    ob_start(); 
    ?>
    
    <?php
    echo file_get_contents('reseller_list_template.php', true);
    $output = ob_get_clean();
    return $output;
}