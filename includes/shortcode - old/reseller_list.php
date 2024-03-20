<?php
function fr_reseller_list_shortcode(){
    ob_start(); 
    ?>
    
    <div class="flex fr-data flex-wrap alignfull flex-space-between" style="margin: 0px !important;">
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'reseller_data';
        $resellers = $wpdb->get_results("SELECT DISTINCT * FROM $table_name ORDER BY title ASC LIMIT 26", ARRAY_A);
        foreach ($resellers as $reseller):
        ?>
        <div class="flex flex-column flex-50 fr-data-single">
            <span><?= ($reseller['country'] == 'Brasil' || $reseller['country'] == 'Br') ? $reseller['city'].' - '.$reseller['state'] : $reseller['city'] ?></span>
            <div class="fr-title"><span><?=$reseller['title']?></span></div>
            <div class="fr-info">
                <span class="fr-data-address">Endereço: </span><span class="fr-data-address-info"><?=$reseller['address']?><?=(isset($reseller['numero']) && !empty($reseller['numero'])) ? ', '.$reseller['numero'] : '' ;?></span><br/>
                <span class="fr-data-country">País: </span><span class="fr-data-country-info"><?=$reseller['country']?></span></br>
                <span class="fr-data-zipcode">CEP: </span><span class="fr-data-zipcode-info"><?=$reseller['zipcode']?></span></br>
                <span class="fr-data-phone">Tel: </span><span class="fr-data-phone-info"><?=$reseller['phone']?></span></br>
                <?=(isset($reseller['phone2']) && !empty($reseller['phone2'])) ? 
                '<span class="fr-data-phone2">Tel: </span><span class="fr-data-phone2-info">'.$reseller['phone2'].'</span></br>' : 
                '' ;?>
                <?=(isset($reseller['whatsapp']) && !empty($reseller['whatsapp'])) ? 
                '<span class="fr-data-whatsapp">WhatsApp: </span><span class="fr-data-whatsapp-info">'.$reseller['whatsapp'].'</span></br>' : 
                '' ;?>
                <?=(isset($reseller['fax']) && !empty($reseller['fax'])) ? 
                '<span class="fr-data-fax">Fax: </span><span class="fr-data-fax-info">'.$reseller['fax'].'</span></br>' : 
                '' ;?>
                <span class="fr-data-email">E-mail: </span><a href="mailto:<?=$reseller['email']?>" class="fr-data-email-info"><?=$reseller['email']?></a></br>
                <?=(isset($reseller['email2']) && !empty($reseller['email2'])) ? 
                '<span class="fr-data-email2">E-mail: </span><a href="mailto:'.$reseller['email2'].'" class="fr-data-email2-info">'.$reseller['email2'].'</a></br>' : 
                '' ;?>
                <?php // Adicionando ícones de trator, implemento e peças ?>
                <?php if ($reseller['tratores'] == 1): ?>
                    <span class="fr-data-icon"><i class="fa-solid fa-tractor"></i> Trator</span>
                <?php endif; ?>
                <?php if ($reseller['implementos'] == 1): ?>
                    <span class="fr-data-icon"><i class="fa-solid fa-toolbox"></i> Implemento</span>
                <?php endif; ?>
                <?php if ($reseller['pecas'] == 1): ?>
                    <span class="fr-data-icon"><i class="fas fa-cogs"></i> Peças</span>
                <?php endif; ?>
            </div>
        </div>
    <?php
    endforeach;
    ?>
    </div>
    <?php
    $output = ob_get_clean();
    return $output;
}

