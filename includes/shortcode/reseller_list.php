<?php
function fr_reseller_list_shortcode(){
    ob_start(); 
    ?>
    <div class="flex fr-data flex-wrap alignfull" style="margin: 0px !important;">
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'reseller_data';
        $resellers = $wpdb->get_results("SELECT DISTINCT * FROM $table_name", ARRAY_A);
        foreach ($resellers as $reseller):
        ?>
        <div class="flex flex-column flex-1">
            <div class="fr-title"><h1><?=$reseller['title']?></h1></div>
            <div class="fr-info">
                <span class="fr-data-address">Endereço: </span><?=$reseller['address']?><?=(isset($reseller['numero']) && !empty($reseller['numero'])) ? ', '.$reseller['numero'] : '' ;?><br/>
                <span class="fr-data-country">País: </span><?=$reseller['country']?></br>
                <span class="fr-data-zipcode">CEP: </span><?=$reseller['zipcode']?></br>
                <span class="fr-data-phone">Tel: </span><?=$reseller['phone']?></br>
                <?=(isset($reseller['whatsapp']) && !empty($reseller['whatsapp'])) ? 
                '<span class="fr-data-whatsapp">WhatsApp: </span>'.$reseller['whatsapp'].'</br>' : 
                '' ;?>
                <?=(isset($reseller['fax']) && !empty($reseller['fax'])) ? 
                '<span class="fr-data-fax">Fax: </span>'.$reseller['fax'].'</br>' : 
                '' ;?>
                <span class="fr-data-email">E-mail: </span><a href="mailto:<?=$reseller['email']?>"><?=$reseller['email']?></a>
            </div>
        </div>
    <?php
    endforeach;
    ?>
    </div>
    <?php
    echo file_get_contents('reseller_list_template.php', true);
    $output = ob_get_clean();
    return $output;
}

