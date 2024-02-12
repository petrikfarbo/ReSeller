<?php
function fr_reseller_cadastrar_page(){
    if(isset($_GET['reseller_id']) && !empty($_GET['reseller_id'])){
        global $wpdb;
        $table_name = $wpdb->prefix . 'reseller_data';

        if(isset($_GET['reseller_id'])){
            $reseller_id = $_GET['reseller_id'];
            $reseller = $wpdb->get_row("SELECT * FROM $table_name WHERE id = $reseller_id", ARRAY_A);
            if($reseller){
                ?>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>

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
                    <script>
                        jQuery(document).ready(function($) {
                            $('#phone').inputmask('(99) 9 9999-9999');

                            $('#whatsapp').inputmask('(99) 9 9999-9999');

                            $('#fax').inputmask('(99) 9999-9999');

                            $('#zipcode').inputmask('99999-999');
                        });
                    </script>

                    <div class="wrap">
                        <h1>ReSeller - Plugin</h1>
                    </div>


                    <div class="wrap">
                        <form action="admin-post.php" method="post">
                            <input type="hidden" name="action" value="fr_reseller_form_submission">
                            <input type="hidden" name="reseller_id" value="<?php echo $_GET['reseller_id']?>">
                            <?php wp_nonce_field( 'fr_reseller_form_verify' );?>


                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <th scope="row"><label for="codigo">Código:</label></th>
                                    <td><input type="number" name="codigo" id="codigo" class="regular-text" value="<?=$reseller['Codigo']?>" required></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="title">Nome da Empresa:</label></th>
                                    <td><input type="text" name="title" id="title" class="regular-text" value="<?=$reseller['title']?>"required></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="country">País:</label></th>
                                    <td><input type="text" name="country" id="country" class="regular-text" maxlength="20" value="<?=$reseller['country']?>"required></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="zipcode">CEP:</label></th>
                                    <td><input type="text" name="zipcode" id="zipcode" class="regular-text" maxlength="10" value="<?=$reseller['zipcode']?>"required></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="phone">Telefone:</label></th>
                                    <td><input type="text" name="phone" id="phone" class="regular-text" value="<?=$reseller['phone']?>"required></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="whatsapp">WhatsApp:</label></th>
                                    <td><input type="text" name="whatsapp" id="whatsapp" class="regular-text" value="<?=$reseller['whatsapp']?>"></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="fax">Fax:</label></th>
                                    <td><input type="text" name="fax" id="fax" class="regular-text" value="<?=$reseller['fax']?>"></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="email">E-mail:</label></th>
                                    <td><input type="email" name="email" id="email" class="regular-text" value="<?=$reseller['email']?>" required></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="state">Estado:</label></th>
                                    <td><input type="text" name="state" id="state" class="regular-text" maxlength="2" value="<?=$reseller['state']?>" required></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="city">Cidade:</label></th>
                                    <td><input type="text" name="city" id="city" class="regular-text" value="<?=$reseller['city']?>" required></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="address">Endereço:</label></th>
                                    <td><input type="text" name="address" id="address" class="regular-text" value="<?=$reseller['address']?>" required></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="latitude">Latitude:</label></th>
                                    <td><input type="text" step="0.000001" name="latitude" id="latitude" class="regular-text" value="<?=$reseller['latitude']?>" required></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="longitude">Longitude:</label></th>
                                    <td><input type="text" step="0.000001" name="longitude" id="longitude" class="regular-text" value="<?=$reseller['longitude']?>" required></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="tractors">Tratores:</label></th>
                                    <td><input type="checkbox" name="tractors" id="tractors" class="regular-text" <?php echo $reseller['tractors'] == 1 ? "checked" : ""; ?>></td>
                                </tr>
                                <tr>    
                                    <th scope="row"><label for="microtractors">Implementos:</label></th>
                                    <td><input type="checkbox" name="microtractors" id="microtractors" class="regular-text" <?php echo $reseller['microtractors'] == 1 ? "checked" : ""; ?>></td>
                                </tr>
                                </tbody>
                            </table>

                            <p class="submit">
                                <input type="submit" name="submit" id="submit" class="button button-primary" value="Cadastrar Empresa">
                            </p>
                        </form>

                    </div>
                <?php
            } else {
                echo "<div class='error'><p>Revendedora não encontrada.</p></div>";
            }
        } else {
            echo "<div class='error'><p>ID da revendedora não especificado.</p></div>";
        }
    }else{
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>

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
    <script>
        jQuery(document).ready(function($) {
            $('#phone').inputmask('(99) 9 9999-9999');

            $('#whatsapp').inputmask('(99) 9 9999-9999');

            $('#fax').inputmask('(99) 9999-9999');

            $('#zipcode').inputmask('99999-999');
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
                    <td><input type="text" name="country" id="country" class="regular-text" maxlength="20" required></td>
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
                    <th scope="row"><label for="microtractors">Implementos:</label></th>
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
}
