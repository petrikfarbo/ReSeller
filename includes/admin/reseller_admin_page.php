<?php
function fr_reseller_admin_page(){
    
    ?>
    
    <script>
        jQuery(document).ready(function($) {
            $('#zipcode').on('change', function() {
                var zipcode = $(this).val();
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

    <div class="wrap">
        <h1>ReSeller - Plugin</h1>
    </div>


    <div class="wrap">
        <form action="admin-post.php" method="post">
            <input type="hidden" name="action" value="fr_reseller_form_submission">
            <?php wp_nonce_field( 'fr_reseller_form_verify' );?>


            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><label for="codigo">Código:</label></th>
                    <td><input type="number" name="codigo" id="codigo" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="title">Nome da Empresa:</label></th>
                    <td><input type="text" name="title" id="title" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="country">País:</label></th>
                    <td><input type="text" name="country" id="country" class="regular-text" maxlength="2" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="zipcode">CEP:</label></th>
                    <td><input type="text" name="zipcode" id="zipcode" class="regular-text" maxlength="10" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="phone">Telefone:</label></th>
                    <td><input type="text" name="phone" id="phone" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="whatsapp">WhatsApp:</label></th>
                    <td><input type="text" name="whatsapp" id="whatsapp" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="fax">Fax:</label></th>
                    <td><input type="text" name="fax" id="fax" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="email">E-mail:</label></th>
                    <td><input type="email" name="email" id="email" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="state">Estado:</label></th>
                    <td><input type="text" name="state" id="state" class="regular-text" maxlength="2" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="city">Cidade:</label></th>
                    <td><input type="text" name="city" id="city" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="address">Endereço:</label></th>
                    <td><input type="text" name="address" id="address" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="latitude">Latitude:</label></th>
                    <td><input type="text" step="0.000001" name="latitude" id="latitude" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="longitude">Longitude:</label></th>
                    <td><input type="text" step="0.000001" name="longitude" id="longitude" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="tractors">Tratores:</label></th>
                    <td><input type="checkbox" name="tractors" id="tractors" class="regular-text"></td>
                </tr>
                <tr>    
                    <th scope="row"><label for="microtractors">Microtratores:</label></th>
                    <td><input type="checkbox" name="microtractors" id="microtractors" class="regular-text"></td>
                </tr>
                </tbody>
            </table>

            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Cadastrar Empresa">
            </p>
        </form>

    </div>
    <?php
}
