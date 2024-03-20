<?php
function fr_reseller_contact_es_shortcode() {
    ob_start(); 
    global $wpdb;
    $table_name = $wpdb->prefix . 'reseller_data';

    $reseller_email = isset($_GET['data']) ? base64_decode($_GET['data']) : 'vendas@agritech.ind.br'; //alterar email default para envio caso ñ tenha representante na região

    $email = $wpdb->get_results("SELECT DISTINCT email, email2 FROM $table_name WHERE email = '$reseller_email' OR email2 = '$reseller_email'", ARRAY_A);

    if (count($email) > 0) {
        echo '<input type="email" style="display:none" class="reseller-email-contact" value="'.$reseller_email.'">';
    } else {
        echo '<input type="email" style="display:none" class="reseller-email-contact" value="vendas@agritech.ind.br">';
    }

    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $('.wpcf7-fr-return').hide();
            var reseller_email = $('.reseller-email-contact').val();
            if(reseller_email != 'vendas@agritech.ind.br'){
                $('.reseller-mail input').val(reseller_email);
            }

            var url = window.location.href;
            if (url.includes('data=')) {
                $('.fr-contact-form').hide();
            }else{
                $('.fr-contact-form').show();
            }
    
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
                $("#city").append("<option>City</option>");
                    for (var i = 0; i < data.length; i++) {
                        $("#city").append("<option value='" + data[i].nome + "'>" + data[i].nome + "</option>");
                    }
                }
            });
            }


            // Evento ao alterar o país
            $('#country').on('change', function() {
            var country = $('#country').val();

            if (country.toUpperCase() === "BRASIL" || country.toUpperCase() === "BR") {
                $("#state").empty();
                $('#state').prop('disabled', false);
                $("#state").append("<option>Estado</option>");

                $("#city").empty();
                $('#city').prop('disabled', true);
                $("#city").append("<option>Ciudad</option>");
                
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
                $("#state").append("<option>Estado</option>");

                $("#city").empty();
                $('#city').prop('disabled', false);
                $("#city").append("<option>Ciudad</option>");

                fetchDataAndSaveToSession();
                savedData = JSON.parse(sessionStorage.getItem('resellers_data'));
                var localidades = { "stores": savedData };

                // Create an empty set to store unique cities
                var uniqueCities = new Set();

                // Iterate through store locations and add unique cities to the set
                localidades.stores.forEach(function(localidade) {
                if (localidade.country == country && !uniqueCities.has(localidade.city)) {
                    // Add city to the set only if it's not already there
                    uniqueCities.add(localidade.city);
                }
                });

                // Populate the city dropdown with unique cities from the set
                uniqueCities.forEach(function(city) {
                    $("#city").append("<option value='" + city + "'>" + city + "</option>");
                });
            }
            });


            // Evento ao alterar o estado
            $('#state').on('change', function() {
                var estadoSelecionado = $(this).val();
                $('#city').prop('disabled', false);

                loadingCity(estadoSelecionado);
            });

            // Evento ao alterar a cidade
            $('#city').on('change', function() {
                var cidadeSelecionada = $(this).val();
                fetchDataAndSaveToSession();
                savedData = JSON.parse(sessionStorage.getItem('resellers_data'));
                var localidades = {"stores": savedData};

                $('.reseller-mail input').val('vendas@agritech.ind.br');
                $('.mail p').show();
                $('.phone-2').show();
                $('.address_wrapper').html('Av. dos Trabalhadores, 145 Vila Castelo Branco 13338-050 Indaiatuba, SP');
                $('.phone-1 p').html('<a href="#">(19) 3801-9000</a>');
                $('.phone-2 p').html('<a href="#">(19) 3801-9049</a>');
                $('.mail p').html('<a href="#">vendas@agritech.ind.br</a>');
                localidades.stores.forEach(function(localidade) {
                    if (cidadeSelecionada == localidade.city) {
                        var enderecoCompleto = localidade.address + (localidade.numero ? ', ' + localidade.numero : '');
                        $('.address_wrapper').html(enderecoCompleto);
                        $('.phone-1 p').html('<a href="#">'+localidade.phone+'</a>');
                        if(localidade.email2){
                            $('.phone-2').show();
                        }else{
                            $('.phone-2').hide();
                            $('.phone-2 p').html('<a href="#">'+localidade.phone2+'</a>');
                        }

                        if(localidade.email){
                            $('.mail p').show();
                            $('.mail p').html('<a href="#">'+localidade.email+'</a>');

                            $('.reseller-mail input').val(localidade.email);
                        }else{
                            $('.mail p').html('<a href="#">vendas@agritech.ind.br</a>');
                        }

                    }
                });

            });

            $('.wpcf7-form').submit((event) => {
                event.preventDefault();

                // Obtenha os valores dos campos do formulário.
                //const name = $('#fr-your-name').val();
                //const email = $('#fr-your-email').val();
                const fr_reseller_email = $('#fr-reseller-email').val();
                //const message = $('#fr-your-message').val();

                //fetchDataAndSaveToSession();
                savedData = JSON.parse(sessionStorage.getItem('resellers_data'));
                var localidades = {"stores": savedData};

                localidades.stores.forEach(function(localidade) {
                    if (fr_reseller_email == localidade.email || fr_reseller_email == localidade.email2 || fr_reseller_email == "vendas@agritech.ind.br") {
                        $('.wpcf7-fr-return').show();
                        $('.wpcf7-form').submit();
                    }else {
                        //alert('Email de revendedor inválido.');
                    }
                });
                
            });
        });
    </script>
    <?php
    $countrys = $wpdb->get_results("SELECT DISTINCT country FROM $table_name", ARRAY_A);
    ?>
    <form action="/agritech/index.php/fale-conosco/#wpcf7-f1648-p775-o1" method="post" class="wpcf7-form init fr-contact-form" aria-label="Contact form" novalidate="novalidate" data-status="init">
        <div class="column one-second">
            <p>
                <span class="wpcf7-form-control-wrap" data-name="Pais">
                    <select class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required pais" id="country" aria-required="true" aria-invalid="false" name="Pais">
                        <option>País</option>
                        <?php foreach ($countrys as $country): ?>
                            <option value="<?= $country['country'] ?>"><?= $country['country'] ?></option>';
                        <?php endforeach; ?>
                    </select>
                </span>
            </p>
        </div>
        <div class="column one-second">
            <p>
                <span class="wpcf7-form-control-wrap" data-name="Estado">
                    <select class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required estado" id="state" aria-required="true" aria-invalid="false" name="Estado" disabled>
                        <option>Estado</option>
                    </select>
                </span>
            </p>
        </div>
        <div class="column one">
            <p>
                <span class="wpcf7-form-control-wrap" data-name="Cidade">
                    <select class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required cidade" id="city" aria-required="true" aria-invalid="false" name="Cidade" disabled>
                        <option>Ciudad</option>
                    </select>
                </span>
            </p>
        </div>
    </form>
    <?php
    $output = ob_get_clean();
    return $output;
}