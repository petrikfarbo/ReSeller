<?php
function fr_reseller_form_shortcode() {
    ob_start(); 
    global $wpdb;
    $table_name = $wpdb->prefix . 'reseller_data';
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $('#zipcode').inputmask('99999-999');

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
                                //console.log(data);
                                var latitudeReferencia =  data.latitude;
                                var longitudeReferencia = data.longitude;

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
                                
                                var localidades = {"stores": data.reselles_data};

                                //console.log(localidades);
                                $(".fr-data").empty();

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

                                localidades.stores.forEach(function(localidade) {
                                    console.log(localidade.title + ": " + localidade.distancia + " km");
                                    var enderecoCompleto = localidade.address + (localidade.numero ? ', ' + localidade.numero : '');
                                    var cep = localidade.zipcode || '';
                                    var telefone2 = localidade.zipcode ? '' : '<span class="fr-data-phone2">Tel: </span><span class="fr-data-phone2-info">' + localidade.phone2 + '</span></br>';

                                    var html = '<div class="flex flex-column flex-50 fr-data-single">' +
                                        '<div class="fr-title"><span>' + localidade.title + '</span></div>' +
                                        '<div class="fr-info">' +
                                        '<span class="fr-data-address">Endereço: </span><span class="fr-data-address-info">' + enderecoCompleto + '</span></br>' +
                                        '<span class="fr-data-country">País: </span><span class="fr-data-country-info">' + localidade.country + '</span></br>' +
                                        '<span class="fr-data-zipcode">CEP: </span><span class="fr-data-zipcode-info">' + cep + '</span></br>' +
                                        '<span class="fr-data-phone">Tel: </span><span class="fr-data-phone-info">' + localidade.phone + '</span></br>' +
                                        telefone2 +
                                        '</div></div>';

                                    $(".fr-data").append(html);
                                            

                                }); 
                                

                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
                
                
                
                
            });
        });
    </script>
    <?php
    $resellers_data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    file_put_contents('wp-content/plugins/reseller/includes/shortcode/resellers.json', json_encode($resellers_data));

    $countrys = $wpdb->get_results("SELECT DISTINCT country FROM $table_name", ARRAY_A);
    $states = $wpdb->get_results("SELECT DISTINCT state FROM $table_name", ARRAY_A);
    $citys = $wpdb->get_results("SELECT DISTINCT city FROM $table_name", ARRAY_A);
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
                <label for="zipcode">Procure pelo CEP</label>
                <div class="flex fr-zipcode-input flex-item-center">
                    <input type="text" name="zipcode" id="zipcode">
                    <button class="search-btn-zipcode" id="zipcode-search">Buscar</button>
                </div>                
            </div>
            <div class="flex fr-filter flex-column">
                <label>Ou localize pelo estado ou pais de sua escolha:</label>
                <div class="flex fr-form-opt">
                    <select name="country" id="country" aria-placeholder="País">
                        <option value="">País</option>
                        <?php foreach ($countrys as $country){
                            echo '<option value="'.$country['country'].'">'.$country['country'].'</option>';
                        }
                        ?>
                    </select>
                    <select name="state" id="state" aria-placeholder="Estado">
                        <option value="">Estado</option>
                        <?php foreach ($states as $state){
                                echo '<option value="'.$state['state'].'">'.$state['state'].'</option>';
                            }
                        ?>
                    </select>
                    <select name="city" id="city" aria-placeholder="Cidade">
                        <option value="">Cidade</option>
                        <?php foreach ($citys as $city){
                                echo '<option value="'.$city['city'].'">'.$city['city'].'</option>';
                            }
                        ?>
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