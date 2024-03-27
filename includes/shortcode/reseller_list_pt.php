<?php
function fr_reseller_list_pt_shortcode(){
    ob_start(); 
    ?>
    <section class="section mcb-section mfn-default-section mcb-section-c8wbnrlb default-width flex fr-data-icon" style="display: none;">
        <div class="mcb-background-overlay"></div>
        <div class="section_wrapper mfn-wrapper-for-wraps mcb-section-inner mcb-section-inner-c8wbnrlb">
            <div class="wrap mcb-wrap mcb-wrap-w6hbnmj5 one tablet-one laptop-one mobile-one clearfix" data-desktop-col="one" data-laptop-col="laptop-one" data-tablet-col="tablet-one" data-mobile-col="mobile-one" style="">
                <div class="mcb-wrap-inner mcb-wrap-inner-w6hbnmj5 mfn-module-wrapper mfn-wrapper-for-wraps">
                    <div class="mcb-wrap-background-overlay"></div>
                    <div class="column mcb-column mcb-item-q1stusf46 one laptop-one tablet-one mobile-one column_column" style="">
                        <div class="mcb-column-inner mfn-module-wrapper mcb-column-inner-q1stusf46 mcb-item-column-inner">
                            <div class="column_attr mfn-inline-editor clearfix" style="">
                                <p>
                                    <strong>
                                        Tratores<img class="alignnone wp-image-1263" src="<?=plugins_url('/', __FILE__).'img/trator.svg';?>" alt="" width="30" height="30" />&nbsp; &nbsp;|&nbsp; &nbsp; Implementos
                                        <img class="wp-image-1262 alignnone" src="<?=plugins_url('/', __FILE__).'img/implemento.svg';?>" alt="" width="30" height="30" />
                                    </strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="mcb-background-overlay"></div>
    <div class="fr-data section_wrapper mfn-wrapper-for-wraps mcb-section-inner mcb-section-inner-ednlxlf8r" style="margin: 0px !important; display: none;">
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'reseller_data';
        $resellers = $wpdb->get_results("SELECT DISTINCT * FROM $table_name ORDER BY title ASC LIMIT 4", ARRAY_A);
        function getNameState($sigla) {
            switch ($sigla) {
                case "AC":
                    return "Acre";
                    break;
                case "AL":
                    return "Alagoas";
                    break;
                case "AP":
                    return "Amapá";
                    break;
                case "AM":
                    return "Amazonas";
                    break;
                case "BA":
                    return "Bahia";
                    break;
                case "CE":
                    return "Ceará";
                    break;
                case "DF":
                    return "Distrito Federal";
                    break;
                case "ES":
                    return "Espírito Santo";
                    break;
                case "GO":
                    return "Goiás";
                    break;
                case "MA":
                    return "Maranhão";
                    break;
                case "MT":
                    return "Mato Grosso";
                    break;
                case "MS":
                    return "Mato Grosso do Sul";
                    break;
                case "MG":
                    return "Minas Gerais";
                    break;
                case "PA":
                    return "Pará";
                    break;
                case "PB":
                    return "Paraíba";
                    break;
                case "PR":
                    return "Paraná";
                    break;
                case "PE":
                    return "Pernambuco";
                    break;
                case "PI":
                    return "Piauí";
                    break;
                case "RJ":
                    return "Rio de Janeiro";
                    break;
                case "RN":
                    return "Rio Grande do Norte";
                    break;
                case "RS":
                    return "Rio Grande do Sul";
                    break;
                case "RO":
                    return "Rondônia";
                    break;
                case "RR":
                    return "Roraima";
                    break;
                case "SC":
                    return "Santa Catarina";
                    break;
                case "SE":
                    return "Sergipe";
                    break;
                case "SP":
                    return "São Paulo";
                    break;
                case "TO":
                    return "Tocantins";
                    break;
                default:
                    return null;
            }
        }
        foreach ($resellers as $reseller):
        $state = getNameState($reseller['state']);
        $numeroWpp = str_replace(['(', ')', '-', ' '], '', $reseller['whatsapp']);
        ?>
        <div class="wrap mcb-wrap mcb-wrap-fr one-second tablet-one-second laptop-one-second mobile-one clearfix" data-desktop-col="one-second" data-laptop-col="laptop-one-second" data-tablet-col="tablet-one-second" data-mobile-col="mobile-one">
            <div class="mcb-wrap-inner mcb-wrap-inner-fr mfn-module-wrapper mfn-wrapper-for-wraps">
                <div class="mcb-wrap-background-overlay"></div>
                <div class="column mcb-column mcb-item-2mmhkb26m one laptop-one tablet-one mobile-one column_column">
                    <div class="mcb-column-inner mfn-module-wrapper mcb-column-inner-2mmhkb26m mcb-item-column-inner">
                        <div class="column_attr mfn-inline-editor clearfix">
                            <h3>
                                <?php if ($reseller['tratores'] == 1): ?>
                                    <strong>
                                        <img loading="lazy" class="alignright wp-image-1262" src="<?=plugins_url('/', __FILE__).'img/implemento.svg';?>" alt="" width="30" height="30" />
                                    </strong>
                                <?php endif; ?>
                                <?php if ($reseller['implementos'] == 1): ?>
                                    <img loading="lazy" class="alignright wp-image-1263" src="<?=plugins_url('/', __FILE__).'img/trator.svg';?>" alt="" width="30" height="30" />
                                <?php endif; ?>
                                
                                <strong><?=$reseller['city']?><br/></strong>
                                <?= ($reseller['country'] == 'Brasil' || $reseller['country'] == 'Br') ? $state.' - '.$reseller['country'] : $reseller['country'] ?>
                            </h3>
                            <div>
                                <h4 class="fr-title"><?=$reseller['title']?></h4>
                                <div class="fr-info">
                                    <strong><span class="fr-data-address">Endereço: </span></strong><span class="fr-data-address-info"><?=$reseller['address']?><?=(isset($reseller['numero']) && !empty($reseller['numero'])) ? ', '.$reseller['numero'] : '' ;?></span><br />
                                    <strong><span class="fr-data-zipcode">CEP: </span></strong><span class="fr-data-zipcode-info"><?=$reseller['zipcode']?></span><br />
                                    <strong><span class="fr-data-phone">Tel: </span></strong><span class="fr-data-phone-info"><?=$reseller['phone']?></span><br />
                                    <?= (isset($reseller['phone2']) && !empty($reseller['phone2'])) ? '<strong><span class="fr-data-phone">Tel: </span></strong><span class="fr-data-phone-info">' . $reseller['phone2'] . '</span><br />' : '' ; ?>

                                    <?= (isset($reseller['whatsapp']) && !empty($reseller['whatsapp'])) ? '<span class="fr-data-whatsapp"><strong>WhatsApp:</strong> </span><a class="fr-data-whatsapp-info" href="https://wa.me/55' .$numeroWpp. '" target="_blank">' . $reseller['whatsapp'] . '</a><br />' : '' ; ?>

                                    <?= (isset($reseller['fax']) && !empty($reseller['fax'])) ? '<span class="fr-data-fax"><strong>Fax:</strong> </span><span class="fr-data-fax-info>' . $reseller['fax'] . '</span><br />' : '' ; ?>

                                    <strong><span class="fr-data-email">E-mail: </span></strong><a class="fr-data-email-info" href="<?= get_site_url().'/index.php/fale-conosco?data='.base64_encode($reseller['email'])?>"><?=$reseller['email']?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

