<div class="row">                           
    <div class="col-md-12">
        <table id="data-table-nodos-capturados" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
            <thead>
                <tr>
                    <th class="never">Id</th>
                    <th class="not-mobile-l">Concepto</th>
                    <th class="all">Área</th>
                    <th class="all">Ubicación</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($listaNodos as $key => $value) {
                    echo '<tr>';
                    echo '<td>' . $key . '</td>';
                    echo '<td>' . $value['Concepto'] . '</td>';
                    echo '<td>' . $value['Area'] . '</td>';
                    echo '<td>' . $value['Ubicacion'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>

        </table>
    </div> 
</div>
<!--Finalizando tabla-->

<!--Empezando indicaciones--> 
<div class="row">
    <div class="col-md-12 m-t-15">                                
        <div class="alert alert-info fade in ">
            <strong>Informacion : </strong>
            Para editar una ubicación solo tienes que dar click sobre fila.
        </div>
    </div>
</div>
<!--Finalizando indicaciones--> 

<!--Empezando error-->
<div class="row">
    <div id="errorNodos" class="col-md-12">
    </div>
</div>
<!--Finalizando error-->
<!--Finalizando Lista de Nodos-->
