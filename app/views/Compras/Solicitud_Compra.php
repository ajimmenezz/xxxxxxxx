<!-- Empezando #contenido -->
<div id="divFormularioSolicitarCompra" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Solicitar Compra</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel seguimiento compras -->
    <div id="panelFormularioSolicitarCompra" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading"></div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorListaCompras"></div>
                </div>
                <!--Finalizando Error-->
                <div class="col-md-12">  
                    <div class="form-group">
                        <div class="col-md-6">
                            <h3 class="m-t-10">Formulario de solicitud de compra</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="alert alert-warning fade in p-5">
                                <strong>
                                    Todos los campos marcados con * son obligatorios.
                                </strong>
                            </div>                    
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                    </div>    
                </div> 
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">                   
                    <label class="f-w-600 f-s-13">Cliente:*</label>
                    <select id="listClientes" class="form-control" style="width: 100%" data-parsley-required="true">
                        <option value="">Selecciona . . .</option>
                        <?php
                        if (isset($datos['Clientes']) && count($datos['Clientes']) > 0) {
                            foreach ($datos['Clientes'] as $key => $value) {
                                echo '<option value="' . $value['ID'] . '">' . $value['Nombre'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">                   
                    <label class="f-s-14 f-w-600">Proyecto *</label>
                    <select class="form-control" style="width: 100%">
                        <option value="">Selecciona . . .</option>
                    </select>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">                   
                    <label class="f-s-14 f-w-600">Sucursal *</label>
                    <select class="form-control" style="width: 100%">
                        <option value="">Selecciona . . .</option>
                    </select>
                </div>
            </div>
            <!--            <div class="table-responsive">
                            <table id="data-table-compras" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Ticket</th>
                                        <th class="all">Servicio</th>
                                        <th class="all">Fecha de Creación</th>
                                        <th class="all">Descripcion</th>
                                        <th class="all">Estatus</th>
                                        <th class="never">IdEstatus</th>
                                        <th class="all">Folio</th>
                                    </tr>
                                </thead>
                                <tbody>                                          
                                </tbody>
                            </table>
                        </div>-->
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel seguimiento compras -->   
</div>
<!-- Finalizando #contenido -->

<!--Empezando seccion para el seguimiento de un servicio sin clasificar->-->
<div id="seccionSeguimientoServicio" class="content hidden"></div>
<!-- Finalizando seccion para el seguimiento de un servicio sin clasificar 