<?php
function fr_reseller_admin_page(){
    ?>
    <div class="wrap">
        <h1>ReSeller - Plugin</h1>

    </div>
    <div class="wrap">
        <table class="wp-list-table widefat fixed striped table-view-list posts">
		    <caption class="screen-reader-text">Tabela ordenada por data. Descendente.</caption>	
            <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <input id="cb-select-all-1" type="checkbox">
                        <label for="cb-select-all-1"><span class="screen-reader-text">Selecionar todos</span></label>
                    </td>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc" abbr="Título">
                        <a href="#">
                            <span>Título</span>
                            <span class="sorting-indicators">
                                <span class="sorting-indicator asc" aria-hidden="true"></span>
                                <span class="sorting-indicator desc" aria-hidden="true"></span>
                            </span>
                        </a>
                    </th>
                    <th scope="col" id="author" class="manage-column">Endereço</th>
                    <th scope="col" id="categories" class="manage-column">Cidade</th>
                    <th scope="col" id="tags" class="manage-column">CEP</th>
                </tr>
            </thead>


        </table>
    </div>
    <?php
}