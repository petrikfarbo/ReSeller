<?php
function fr_reseller_from_shortcode() {
    ob_start(); 
    ?>
    <script>
        jQuery(document).ready(function($) {
            $('#zipcode').on('change', function() {
                var zipcode = $(this).val();
                zipcode = zipcode.replace('-', '');
                $.ajax({
                    url: '<?=plugins_url('/', __FILE__).'admin-ajax.php';?>',
                    type: 'POST',
                    data: {
                        zipcode: zipcode
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.error) {
                            alert(data.error);
                        } else {
                            console.log(data);
                            $('#state').val(data.state);
                            $('#city').val(data.city);
                            $('#address').val(data.address);
                            $('#latitude').val(data.latitude);
                            $('#longitude').val(data.longitude);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'reseller_data';
    $countrys = $wpdb->get_results("SELECT DISTINCT country FROM $table_name", ARRAY_A);
    $states = $wpdb->get_results("SELECT DISTINCT state FROM $table_name", ARRAY_A);
    $citys = $wpdb->get_results("SELECT DISTINCT city FROM $table_name", ARRAY_A);
    ?>
    <div class="flex">
        <form>
            <div class="zipcode">
                <label for="zipcode">Procure pelo CEP</label>
                <input type="text" name="zipcode" id="zipcode">
            </div>
            <div class="filter flex">
                <label>Ou localize pelo estado ou pais de sua escolha:</label>
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
            
        </form>
    </div>
    <?php
    $output = ob_get_clean();
    return $output;
}