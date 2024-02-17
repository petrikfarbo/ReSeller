<?php
function fr_reseller_form_shortcode() {
    ob_start(); 
    global $wpdb;
    $table_name = $wpdb->prefix . 'reseller_data';
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            // Função para buscar dados e salvá-los localmente
            function fetchDataAndSaveToLocal() {
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
                            localStorage.setItem('resellers_data', JSON.stringify(data.resellers_data));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
            fetchDataAndSaveToLocal();
            var savedData = JSON.parse(localStorage.getItem('resellers_data'))

            // Verifica se savedData é um array
            if (!Array.isArray(savedData)) {
                fetchDataAndSaveToLocal();
                savedData = JSON.parse(localStorage.getItem('resellers_data'));
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

            // Função para preencher os dados da interface com as empresas mais próximas
            function fillClosestCompanies(localidades) {
                $(".fr-data").empty();
                localidades.forEach(function(localidade) {
                    var enderecoCompleto = localidade.address + (localidade.numero ? ', ' + localidade.numero : '');
                    var cep = localidade.zipcode || '';
                    var phone2 = localidade.phone2 ? '<span class="fr-data-phone2">Tel: </span><span class="fr-data-phone2-info">' + localidade.phone2 + '</span></br>' : '';
                    var whatsapp = localidade.whatsapp ? '<span class="fr-data-whatsapp">WhatsApp: </span><span class="fr-data-whatsapp-info">' + localidade.whatsapp + '</span></br>' : '';
                    var fax = localidade.fax ? '<span class="fr-data-fax">Fax: </span><span class="fr-data-fax-info">' + localidade.fax + '</span></br>' : '';
                    var email2 = localidade.email2 ? '<span class="fr-data-email2">E-mail: </span><a href="mailto:' + localidade.email2 + '" class="fr-data-email2-info">' + localidade.email2 + '</a></br>' : '';
                    var state = localidade.state ? '<span class="fr-data-country-state">'+localidade.city+' - '+localidade.state+'</span>' : '<span class="fr-data-country">'+localidade.city+'</span>';

                    // Verifica se tratores é 1 e inclui o ícone
                    var tratoresIcon = localidade.tratores == 1 ? '<i class="fas fa-tractor"></i><span class="fr_tractor"> Trator</span>' : '';
                    // Verifica se implementos é 1 e inclui o ícone
                    var implementosIcon = localidade.implementos == 1 ? '<i class="fa-solid fa-toolbox"></i><span class="fr_tools"> Implementos</span>' : '';
                    // Verifica se peças é 1 e inclui o ícone
                    var pecasIcon = localidade.pecas == 1 ? '<i class="fas fa-cogs"></i><span class="fr_cogs"> Peças</span>' : '';

                    var html = '<div class="flex flex-column flex-50 fr-data-single">' +
                        state +
                        '<div class="fr-title"><span>' + localidade.title + '</span></div>' +
                        '<div class="fr-info">' +
                            '<span class="fr-data-address">Endereço: </span><span class="fr-data-address-info">' + enderecoCompleto + '</span></br>' +
                            '<span class="fr-data-country">País: </span><span class="fr-data-country-info">' + localidade.country + '</span></br>' +
                            '<span class="fr-data-zipcode">CEP: </span><span class="fr-data-zipcode-info">' + cep + '</span></br>' +
                            '<span class="fr-data-phone">Tel: </span><span class="fr-data-phone-info">' + localidade.phone + '</span></br>' +
                            phone2 +
                            whatsapp +
                            fax +
                            '<span class="fr-data-email">E-mail: </span><a href="mailto:' + localidade.email + '" class="fr-data-email-info">' + localidade.email + '</a></br>' +
                            email2 +
                            // Inclui os ícones de trator, implemento e peças
                            '<div>' + tratoresIcon + ' ' + implementosIcon + ' ' + pecasIcon + '</div>' +
                        '</div></div>';

                    $(".fr-data").append(html);
                });
            }


            // Função para carregar as cidades de acordo com o estado selecionado
            function loadingCity(state) {
                // Limpa o conteúdo do select de cidades
                $("#city").empty();

                // Opção "Selecione"
                $("#city").append("<option value=''>Cidade</option>");

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
                                fetchDataAndSaveToLocal();
                                savedData = JSON.parse(localStorage.getItem('resellers_data'));
                                
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
                                var empresasMaisProximas = localidades.stores.slice(0, 26);

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
                    $('#state').prop('disabled', false);
                } else {
                    $('#state').prop('disabled', true);
                }

                    // Filtrar empresas pelo país selecionado
                    fetchDataAndSaveToLocal();
                    savedData = JSON.parse(localStorage.getItem('resellers_data'));

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
                fetchDataAndSaveToLocal();
                savedData = JSON.parse(localStorage.getItem('resellers_data'));

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
                var empresasMaisProximas = localidades.stores.slice(0, 26);

                fillClosestCompanies(empresasMaisProximas);
            });

            // Evento ao alterar a cidade
            $('#city').on('change', function() {
                var cidadeSelecionada = $(this).val();
                fetchDataAndSaveToLocal();
                savedData = JSON.parse(localStorage.getItem('resellers_data'));

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
                            var empresasMaisProximas = localidades.stores.slice(0, 26);

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
        .flex{
            display: flex;
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
            min-width: 380px;
            min-height: 140px;
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
        
    </style>
    <div class="flex fr-form flex-item-center">
        <form>
            <div class="flex fr-zipcode flex-column">
                <label for="zipcode">Procure pelo CEP: </label>
                <div class="flex fr-zipcode-input flex-item-center">
                    <input type="text" name="zipcode" id="zipcode">
                    <button class="search-btn-zipcode" id="zipcode-search">Buscar</button>
                </div>                
            </div>
            <div class="flex fr-filter flex-column">
                <label>Ou localize pelo estado ou pais de sua escolha:</label>
                <div class="flex fr-form-opt">
                    <select class="fr-from-country flex-1" name="country" id="country" aria-placeholder="País" >
                        <option value="">País</option>
                        <?php foreach ($countrys as $country){
                            echo '<option value="'.$country['country'].'">'.$country['country'].'</option>';
                        }
                        ?>
                    </select>
                    <select class="fr-from-state flex-1" name="state" id="state" aria-placeholder="Estado" disabled>
                        <option value="">Estado</option>
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
                    <select class="fr-from-city flex-1" name="city" id="city" aria-placeholder="Cidade" disabled>
                        <option value="">Cidade</option>
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