<?php
function fr_reseller_form_en_shortcode() {
    ob_start(); 
    global $wpdb;
    $table_name = $wpdb->prefix . 'reseller_data';
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            // Função para buscar dados e salvá-los localmente
            function fetchDataAndSaveToSession() {
                $.ajax({
                    url: '<?=plugins_url('/', __FILE__).'shortcode-ajax.php';?>',
                    type: 'POST',
                    data: {
                        reseller_data: 'select'
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.error) {
                            alert(data.error);
                        } else {
                            sessionStorage.setItem('resellers_data', JSON.stringify(data.resellers_data));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
            fetchDataAndSaveToSession();
            var savedData = JSON.parse(sessionStorage.getItem('resellers_data'));

            // Verifica se savedData é um array
            if (!Array.isArray(savedData)) {
                fetchDataAndSaveToSession();
                savedData = JSON.parse(sessionStorage.getItem('resellers_data'));
            }



            $('#zipcode').inputmask('99999-999');
            // Função para calcular a distância entre duas coordenadas geográficas
            function calcularDistancia(lat1, lon1, lat2, lon2) {
                var R = 6371; // raio da Terra em km
                var dLat = (lat2 - lat1) * Math.PI / 180;
                var dLon = (lon2 - lon1) * Math.PI / 180;
                var a =
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                var d = R * c; // distância em km
                return d;
            }
            function getNameState(sigla) {
                switch (sigla) {
                    case "AC":
                        return "Acre";
                    case "AL":
                        return "Alagoas";
                    case "AP":
                        return "Amapá";
                    case "AM":
                        return "Amazonas";
                    case "BA":
                        return "Bahia";
                    case "CE":
                        return "Ceará";
                    case "DF":
                        return "Distrito Federal";
                    case "ES":
                        return "Espírito Santo";
                    case "GO":
                        return "Goiás";
                    case "MA":
                        return "Maranhão";
                    case "MT":
                        return "Mato Grosso";
                    case "MS":
                        return "Mato Grosso do Sul";
                    case "MG":
                        return "Minas Gerais";
                    case "PA":
                        return "Pará";
                    case "PB":
                        return "Paraíba";
                    case "PR":
                        return "Paraná";
                    case "PE":
                        return "Pernambuco";
                    case "PI":
                        return "Piauí";
                    case "RJ":
                        return "Rio de Janeiro";
                    case "RN":
                        return "Rio Grande do Norte";
                    case "RS":
                        return "Rio Grande do Sul";
                    case "RO":
                        return "Rondônia";
                    case "RR":
                        return "Roraima";
                    case "SC":
                        return "Santa Catarina";
                    case "SE":
                        return "Sergipe";
                    case "SP":
                        return "São Paulo";
                    case "TO":
                        return "Tocantins";
                    default:
                        return null;
                }
            }



            // Função para preencher os dados da interface com as empresas mais próximas
            function fillClosestCompanies(localidades) {
                $(".fr-data").empty();
                
                
                localidades.forEach(function(localidade) {
                    var nomeEstado = getNameState(localidade.state);
                    
                    var enderecoCompleto = localidade.address + (localidade.numero ? ', ' + localidade.numero : '');
                    var cep = localidade.zipcode || '';
                    var phone2 = localidade.phone2 ? '<strong><span class="fr-data-phone">Phone: </span></strong><span class="fr-data-phone-info">' + localidade.phone2 + '</span><br />' : '';
                    
                    const frWpp = localidade.whatsapp.replace(/[\(\)\-\s]/g, '');
                    var whatsapp = localidade.whatsapp ? '<span class="fr-data-whatsapp"><strong>WhatsApp:</strong> </span><a class="fr-data-whatsapp-info" href="https://wa.me/55'+frWpp+'" target="_blank">' + localidade.whatsapp + '</span><br />' : '';
                    
                    var fax = localidade.fax ? '<span class="fr-data-fax"><strong>Fax:</strong> </span><span class="fr-data-fax-info">' + localidade.fax + '</span><br />' : '';
                    var email2 = localidade.email2 ? '<strong><span class="fr-data-email">E-mail: </span></strong><a href="<?= get_site_url();?>/en/contact-us?data=' + btoa(localidade.email2) + '" class="fr-data-email-info">' + localidade.email2 + '</a><br />' : '';
                    var state = localidade.state ? '<span class="fr-data-country-state">'+nomeEstado+' - '+localidade.country+'</span>' : '<span class="fr-data-country">'+localidade.contry+'</span>';
                    
                    
                    
                    // Verifica se tratores é 1 e inclui o ícone
                    var tratoresIcon = localidade.tratores == 1 ? '<strong><img loading="lazy" class="alignright wp-image-1262" src="<?=plugins_url('/', __FILE__).'img/trator.svg';?>" alt="" width="30" height="30" /></strong>' : '';
                    // Verifica se implementos é 1 e inclui o ícone
                    var implementosIcon = localidade.implementos == 1 ? '<strong><img loading="lazy" class="alignright wp-image-1262" src="<?=plugins_url('/', __FILE__).'img/implemento.svg';?>" alt="" width="30" height="30" /></strong>' : '';
                    // Verifica se peças é 1 e inclui o ícone
                    var pecasIcon = localidade.pecas == 1 ? '<i class="fas fa-cogs"></i><span class="fr_cogs"> Parts</span>' : '';
                    
                    var html = '<div class="wrap mcb-wrap mcb-wrap-fr one-second tablet-one-second laptop-one-second mobile-one clearfix" data-desktop-col="one-second" data-laptop-col="laptop-one-second" data-tablet-col="tablet-one-second" data-mobile-col="mobile-one">' +
                        '<div class="mcb-wrap-inner mcb-wrap-inner-fr mfn-module-wrapper mfn-wrapper-for-wraps">' +
                        '<div class="mcb-wrap-background-overlay"></div>' +
                        '<div class="column mcb-column mcb-item-2mmhkb26m one laptop-one tablet-one mobile-one column_column">' +
                        '<div class="mcb-column-inner mfn-module-wrapper mcb-column-inner-2mmhkb26m mcb-item-column-inner">' +
                        '<div class="column_attr mfn-inline-editor clearfix">' +
                        '<h3>'+tratoresIcon +''+ implementosIcon+
                        '<strong>' + localidade.city + '<br/></strong>' +
                        state+
                        '</h3>' +
                        '<div>' +
                        '<h4 class="fr-title">' + localidade.title + '</h4>' +
                        '<div class="fr-info">' +
                        '<strong><span class="fr-data-address">Address: </span></strong><span class="fr-data-address-info">' + enderecoCompleto + '</span><br />' +
                        '<strong><span class="fr-data-zipcode">ZIP: </span></strong><span class="fr-data-zipcode-info">' + cep + '</span><br />' +
                        '<strong><span class="fr-data-phone">Phone: </span></strong><span class="fr-data-phone-info">' + localidade.phone + '</span><br />' +
                        phone2 +
                        whatsapp +
                        fax +
                        '<strong><span class="fr-data-email">E-mail: </span></strong><a class="fr-data-email-info" href="<?= get_site_url();?>/en/contact-us?data=' + btoa(localidade.email) + '">' + localidade.email + '</a>'+
                        '</div></div></div></div></div></div></div>';
                    
                    $(".fr-data").append(html);

                });
            }


            // Função para carregar as cidades de acordo com o estado selecionado
            function loadingCity(state) {
                // Limpa o conteúdo do select de cidades
                $("#city").empty();
            // Requisição para API do IBGE
            $.ajax({
                url: "https://servicodados.ibge.gov.br/api/v1/localidades/estados/" + state + "/municipios",
                dataType: "json",
                success: function(data) {
                // Loop para adicionar as cidades do estado selecionado
                for (var i = 0; i < data.length; i++) {
                    $("#city").append("<option value='" + data[i].nome + "'>" + data[i].nome + "</option>");
                }
                }
            });
            }

            // Evento ao clicar no botão de busca por CEP
            $('#zipcode-search').on('click', function(e) {
                e.preventDefault();
                var zipcode = $('#zipcode').val();
                if (typeof zipcode !== "undefined" && zipcode !== "") {
                    zipcode = zipcode.replace('-', '');

                    $.ajax({
                        url: '<?=plugins_url('/', __FILE__).'shortcode-ajax.php';?>',
                        type: 'POST',
                        data: {
                            zipcode: zipcode
                        },
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.error) {
                                alert(data.error);
                            } else {
                                fetchDataAndSaveToSession();
                                savedData = JSON.parse(sessionStorage.getItem('resellers_data'));
                                
                                var latitudeReferencia = data.latitude;
                                var longitudeReferencia = data.longitude;

                                var localidades = {"stores": savedData};

                                localidades.stores.forEach(function(localidade) {
                                    if (localidade.latitude && localidade.longitude) {
                                        var distancia = calcularDistancia(parseFloat(localidade.latitude), parseFloat(localidade.longitude), latitudeReferencia, longitudeReferencia);
                                        localidade.distancia = distancia;
                                    } else {
                                        localidade.distancia = Infinity; 
                                    }
                                });

                                localidades.stores.sort(function(a, b) {
                                    return a.distancia - b.distancia;
                                });

                                // Limitar a exibição das 26 empresas mais próximas
                                var empresasMaisProximas = localidades.stores.slice(0, 4);

                                fillClosestCompanies(empresasMaisProximas);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });

            // Evento ao alterar o país
            $('#country').on('change', function() {
                var country = $('#country').val();
                if (country.toUpperCase() === "BRASIL" || country.toUpperCase() === "BR") {
                    $("#state").empty();
                    $('#state').prop('disabled', false);
                    $("#state").append("<option>State</option>");

                    $("#city").empty();
                    $('#city').prop('disabled', true);
                    $("#city").append("<option>City</option>");
                    
                    var states = [
                        { value: "AC", text: "Acre" },
                        { value: "AL", text: "Alagoas" },
                        { value: "AM", text: "Amazonas" },
                        { value: "AP", text: "Amapá" },
                        { value: "BA", text: "Bahia" },
                        { value: "CE", text: "Ceará" },
                        { value: "DF", text: "Distrito Federal" },
                        { value: "ES", text: "Espírito Santo" },
                        { value: "GO", text: "Goiás" },
                        { value: "MA", text: "Maranhão" },
                        { value: "MG", text: "Minas Gerais" },
                        { value: "MS", text: "Mato Grosso do Sul" },
                        { value: "MT", text: "Mato Grosso" },
                        { value: "PA", text: "Pará" },
                        { value: "PB", text: "Paraíba" },
                        { value: "PE", text: "Pernambuco" },
                        { value: "PI", text: "Piauí" },
                        { value: "PR", text: "Paraná" },
                        { value: "RJ", text: "Rio de Janeiro" },
                        { value: "RN", text: "Rio Grande do Norte" },
                        { value: "RO", text: "Rondônia" },
                        { value: "RR", text: "Roraima" },
                        { value: "RS", text: "Rio Grande do Sul" },
                        { value: "SC", text: "Santa Catarina" },
                        { value: "SE", text: "Sergipe" },
                        { value: "SP", text: "São Paulo" },
                        { value: "TO", text: "Tocantins" }
                    ];                

                    states.forEach(function(state) {
                        $("#state").append("<option value='" + state.value + "'>" + state.text + "</option>");
                    });
                } else {
                    $("#state").empty();
                    $('#state').prop('disabled', true);
                    
                    $("#city").empty();
                    $('#city').prop('disabled', true);
                }

                    // Filtrar empresas pelo país selecionado
                    fetchDataAndSaveToSession();
                    savedData = JSON.parse(sessionStorage.getItem('resellers_data'));

                    var localidades = savedData.filter(function(localidade) {
                    return localidade.country.toUpperCase() === country.toUpperCase();
                });

                fillClosestCompanies(localidades);
            });

            // Evento ao alterar o estado
            $('#state').on('change', function() {
                var estadoSelecionado = $(this).val();
                $('#city').prop('disabled', false);

                loadingCity(estadoSelecionado);
                fetchDataAndSaveToSession();
                savedData = JSON.parse(sessionStorage.getItem('resellers_data'));

                var localidades = {"stores": savedData};
                var coordenadasEstados = {
                        "AC": [ -8.77, -70.55], 
                        "AL": [ -9.71, -35.73],
                        "AM": [ -3.07, -61.66],
                        "AP": [  1.41, -51.77],
                        "BA": [-12.96, -38.51],
                        "CE": [ -3.71, -38.54],
                        "DF": [-15.83, -47.86],
                        "ES": [-19.19, -40.34],
                        "GO": [-16.64, -49.31],
                        "MA": [ -2.55, -44.30],
                        "MT": [-12.64, -55.42],
                        "MS": [-20.51, -54.54],
                        "MG": [-18.10, -44.38],
                        "PA": [ -5.53, -52.29],
                        "PB": [ -7.06, -35.55],
                        "PR": [-24.89, -51.55],
                        "PE": [ -8.28, -35.07],
                        "PI": [ -8.28, -43.68],
                        "RJ": [-22.84, -43.15],
                        "RN": [ -5.22, -36.52],
                        "RO": [-11.22, -62.80],
                        "RS": [-30.01, -51.22],
                        "RR": [  1.89, -61.22],
                        "SC": [-27.33, -49.44],
                        "SE": [-10.90, -37.07],
                        "SP": [-23.55, -46.64],
                        "TO": [-10.25, -48.25],
                    }; // Defina as coordenadas dos estados conforme necessário

                var latitudeReferencia = coordenadasEstados[estadoSelecionado][0];
                var longitudeReferencia = coordenadasEstados[estadoSelecionado][1];

                localidades.stores.forEach(function(localidade) {
                    if (localidade.latitude && localidade.longitude) {
                        var distancia = calcularDistancia(parseFloat(localidade.latitude), parseFloat(localidade.longitude), latitudeReferencia, longitudeReferencia);
                        localidade.distancia = distancia;
                    } else {
                        localidade.distancia = Infinity; 
                    }
                });

                localidades.stores.sort(function(a, b) {
                    return a.distancia - b.distancia;
                });

                // Limitar a exibição das 26 empresas mais próximas
                var empresasMaisProximas = localidades.stores.slice(0, 4);

                fillClosestCompanies(empresasMaisProximas);
            });

            // Evento ao alterar a cidade
            $('#city').on('change', function() {
                var cidadeSelecionada = $(this).val();
                fetchDataAndSaveToSession();
                savedData = JSON.parse(sessionStorage.getItem('resellers_data'));

                var localidades = {"stores": savedData};
                $.ajax({
                    url: '<?=plugins_url('/', __FILE__).'cidades.json';?>', // Substitua 'cidades.json' pelo caminho do seu arquivo JSON
                    dataType: 'json',
                    success: function(data) {
                        var coordenadasCidades = data; // Supondo que o arquivo JSON já tenha um formato de objeto com as coordenadas das cidades

                        if (coordenadasCidades.hasOwnProperty(cidadeSelecionada)) {
                            var latitudeReferencia = coordenadasCidades[cidadeSelecionada][0];
                            var longitudeReferencia = coordenadasCidades[cidadeSelecionada][1];

                            localidades.stores.forEach(function(localidade) {
                                if (localidade.latitude && localidade.longitude) {
                                    var distancia = calcularDistancia(parseFloat(localidade.latitude), parseFloat(localidade.longitude), latitudeReferencia, longitudeReferencia);
                                    localidade.distancia = distancia;
                                } else {
                                    localidade.distancia = Infinity; 
                                }
                            });

                            localidades.stores.sort(function(a, b) {
                                return a.distancia - b.distancia;
                            });

                            // Limitar a exibição das 26 empresas mais próximas
                            var empresasMaisProximas = localidades.stores.slice(0, 4);

                            fillClosestCompanies(empresasMaisProximas);
                        } else {
                            console.error("Coordenadas não encontradas para a cidade selecionada");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro ao carregar o arquivo JSON:", error);
                    }
                });
            });
        });
    </script>

    <?php
    $resellers_data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    file_put_contents('wp-content/plugins/reseller/includes/shortcode/resellers.json', json_encode($resellers_data));

    $countrys = $wpdb->get_results("SELECT DISTINCT country FROM $table_name", ARRAY_A);
    ?>
    <style>
        .w-100{
                width: 100%;
            }
        .flex{
            display: flex;
            flex-wrap: wrap;
        }
        .flex-column{
            flex-direction: column;
        }
        .flex-1{
            flex: 1;
        }
        .flex-50{
            flex: 0 0 40%;
        }
        .flex-wrap{
            flex-wrap: wrap;
        }
        .flex-space-between {
            justify-content: space-between;
        }
        .flex-item-center{
            align-items: center;
        }
        .fr-form{
            padding: 5px;
        }
        .fr-zipcode{
            min-width: 220px;   
        }
        .search-btn-zipcode{
            height: 32px;
            padding: 5px;
        }
        .fr-data-single{
            padding: 10px;
        }
        .flex.fr-zipcode-input.flex-column input[type=text] {
            width: 100%;
        }
        button#zipcode-search {
            max-width: 25%;
        }
        .mcb-section .mcb-wrap-fr .mcb-wrap-inner-fr {
            background-color: rgba(0, 0, 0, 0.03);
            padding-top: 20px;
            padding-right: 20px;
            padding-bottom: 20px;
            padding-left: 20px;
            align-content: space-evenly;
            align-items: center;
            margin-right: 20px;
            margin-left: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
    </style>
    <div class="flex fr-form flex-item-center">
        <form class="w-100">
            <div class="flex fr-zipcode flex-column flex-column" style="display: none;">
                <h3 for="zipcode">type your ZIP: </h3>
                <div class="flex fr-zipcode-input flex-column">
                    <input type="text" name="zipcode" id="zipcode">
                    <button class="search-btn-zipcode" id="zipcode-search">Search</button>
                </div>                
            </div>
            <div class="flex fr-filter flex-column">
                <h3>locate by the state or country of your choice:</h3>
                <div class="flex fr-form-opt flex-column">
                    <h4>Country</h4>
                    <select class="fr-from-country flex-1 w-100" name="country" id="country" aria-placeholder="País" >
                        <?php foreach ($countrys as $country): ?>
                            <option value="<?= $country['country'] ?>"><?= $country['country'] ?></option>';
                        <?php endforeach; ?>
                    </select>
                    <h4>State</h4>
                    <select class="fr-from-state flex-1 w-100" name="state" id="state" aria-placeholder="Estado">
                        <option></option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AM">Amazonas</option>
                        <option value="AP">Amapá</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="PR">Paraná</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SE">Sergipe</option>
                        <option value="SP">São Paulo</option>
                        <option value="TO">Tocantins</option>
                    </select>
                    <h4>City</h4>
                    <select class="fr-from-city flex-1 w-100" name="city" id="city" aria-placeholder="Cidade" disabled>
                    </select>
                </div>
            </div>
            
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
    <?php
    $output = ob_get_clean();
    return $output;
}