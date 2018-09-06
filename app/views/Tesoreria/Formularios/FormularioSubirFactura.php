<div class="row">
    <div class="col-md-12">
        <h3 class="m-t-10">Documentación Archivos</h3>
    </div>
    <!--Empezando Separador-->
    <div class="col-md-12">
        <div class="underline m-b-15 m-t-15"></div>
    </div>
</div>
<div class="table-responsive">
    <table id="data-table-subir-facturas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
        <thead>
            <tr>
                <th class="all"></th>
                <th class="never">Id</th>
                <th class="all">Servicio</th>
                <th class="all">Ticket </th>
                <th class="all">Folio</th>
                <th class="all">Número de Vuelta</th>
                <th class="all">Sucursal</th>
                <th class="all">Tecnico</th>
                <th class="all">Fecha</th>
                <th class="all">Monto</th>
                <th class="all">Viatico</th>
                <th class="all">Estatus</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($tablaFacturacionOutsourcingAutorizado)) {
                foreach ($tablaFacturacionOutsourcingAutorizado as $key => $value) {
                    echo '<tr>';
                    echo '<td><div class="custom-control custom-checkbox">
                                <input id="checkbox-' . $value['Id'] . '" type="checkbox" class="custom-control-input" data-checkbox="' . $value['Ticket'] . '">
                            </div></td>';
                    echo '<td>' . $value['Id'] . '</td>';
                    echo '<td>' . $value['IdServicio'] . '</td>';
                    echo '<td>' . $value['Ticket'] . '</td>';
                    echo '<td>' . $value['Folio'] . '</td>';
                    echo '<td>' . $value['Vuelta'] . '</td>';
                    echo '<td>' . $value['Sucursal'] . '</td>';
                    echo '<td>' . $value['NombreAtiende'] . '</td>';
                    echo '<td>' . $value['Fecha'] . '</td>';
                    echo '<td>' . $value['Monto'] . '</td>';
                    echo '<td>' . $value['Viatico'] . '</td>';
                    echo '<td>' . $value['Estatus'] . '</td>';
                    echo '</tr>';
                }
            }
            ?>                                       
        </tbody>
    </table>
</div>

<div class="row m-t-15">
    <div class="col-md-12">
        <div class="form-group">
            <label>Outsourcing Factura</label>
            <input id="inputTicketsFacturaTesoreria" type="text" class="form-control" disabled/>
        </div>
    </div>                               
</div>

<!-- Seccion de Evidencias -->
<div class="row">
    <div class="col-md-12">                                    
        <div class="form-group">
            <label for="evidenciasFacturaTesoreria">Archivo PDF y XML *</label>
            <input id="evidenciasFacturaTesoreria"  name="evidenciasFacturaTesoreria[]" type="file" multiple/>
        </div>
    </div>
</div>
<!-- Finalizando -->

<div class="row m-t-10">
    <!--Empezando error--> 
    <div class="col-md-12">
        <div id="errorFormularioSubirFacturas"></div>
    </div>
</div>

<div class="row m-t-10">
    <div class="col-md-12">
        <div class="form-group text-center">
            <a id="btnGuardarSubirArchivos" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-cloud-upload"></i> Subir Archivos</a>                            
        </div>
    </div>
</div>

