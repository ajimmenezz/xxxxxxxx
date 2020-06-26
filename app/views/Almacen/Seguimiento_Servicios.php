<!-- Empezando #contenido -->
<div id="listaServicio" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Seguimiento Almacén</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel seguimiento Mesa de Ayuda-->
    <div id="panelSeguimientoAlmacen" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">                
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Seguimiento Almacén</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <div class="col-md-12">  
                    <div class="form-group">
                        <div class="col-md-6">
                            <h3 class="m-t-10">Servicios Pendientes</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                        <table id="data-table-almacen" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Ticket</th>
                                    <th class="desktop tablet-p">Servicio</th>
                                    <th class="desktop tablet-p">Fecha de Creación</th>
                                    <th class="all">Descripcion</th>
                                    <th class="all">Estatus</th>
                                    <th class="never">IdEstatus</th>
                                    <th class="all">Folio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($datos['Servicios'])) {
                                    foreach ($datos['Servicios'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Ticket'] . '</td>';
                                        echo '<td>' . $value['Servicio'] . '</td>';
                                        echo '<td>' . $value['FechaCreacion'] . '</td>';
                                        echo '<td>' . $value['Descripcion'] . '</td>';
                                        echo '<td>' . $value['NombreEstatus'] . '</td>';
                                        echo '<td>' . $value['IdEstatus'] . '</td>';
                                        ($value['Folio'] === '0') ? $folio = '' : $folio = $value['Folio'];
                                        echo '<td>' . $folio . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>                                        
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel seguimiento mesa de ayuda -->   
</div>
<!-- Finalizando #contenido -->

<!--Empezando seccion para el seguimiento de un servicio sin clasificar-->
<div id="seccionSeguimientoServicio" class="content hidden"></div>
<!-- Finalizando seccion para el seguimiento de un servicio sin clasificar -->