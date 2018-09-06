
<!-- Empezando #contenido -->
<div id="divListaCatalogos" class="content">
    <!-- Empezando titulo de la pagina -->    
    <!-- Finalizando titulo de la pagina -->
    <div class="row">
        <div class="col-md-6 col-xs-6">
            <h1 class="page-header">Catálogo <small>de Almacenes Virtuales</small></h1>            
        </div>
        <div class="col-md-6 col-xs-6 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acciones <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li id="btnTraspasarAlmacenes"><a href="#"><i class="fa fa-exchange"></i> Traspasar productos.</a></li>                    
                    <li id="btnVerTraspasos"><a href="#"><i class="fa fa-eye"></i> Ver Traspasos.</a></li>                    
                    <li id="btnVerAltasIniciales"><a href="#"><i class="fa fa-eye"></i> Ver Altas Iniciales.</a></li>                    
                    <li id="btnVerKitsEquipo"><a href="#"><i class="fa fa-laptop"></i> Kits de Equipos.</a></li>                    
                    <li id="btnDeshuesarEquipo"><a href="#"><i class="fa fa-wrench"></i> Deshuesar Equipo.</a></li>                    
                </ul>
            </div>             
        </div>
    </div>
    <!-- Empezando panel catálogo de almacenes virtuales -->
    <div id="seccionAlmacenes" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo de Almacenes Virtuales</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando fila 1 -->
            <div id="formularioAlmacen" class="row m-t-10" >
            </div>
            <!--Finalizando fila 1-->
            <!--Empezando tabla fila 2 -->
            <div id='listaAlmacenes' class="row"> 
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorListaAlmacenes"></div>
                </div>
                <!--Finalizando Error-->
                <div class="col-md-12">                        
                    <div class="form-group">
                        <div class="col-md-6">
                            <h3 class="m-t-10">Lista de Almacenes Virtuales</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-right">
                                <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarAlmacen"><i class="fa fa-plus"></i> Agregar</a>
                            </div>
                        </div>
                        <!--Empezando Separador-->
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <table id="data-table-almacenes" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Nombre</th>
                                    <th class="all">Tipo de Almacén</th>
                                    <th class="all">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($datos['ListaAlmacenes']) {
                                    foreach ($datos['ListaAlmacenes'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '<td>' . $value['Tipo'] . '</td>';
                                        if ($value['Flag'] === '1') {
                                            echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                        } else {
                                            echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                        }
                                        echo '</tr>';
                                    }
                                }
                                ?>                                        
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
            <!--Finalizando tabla fila 2-->
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel catálogo de almacenes -->
</div>
<!-- Finalizando #contenido -->

<!--Empezando sección para mostrar el inventario del almacen-->
<div id="divInventarioAlmacen" class="content" style="display:none"></div>
<!--Finalizando sección para mostrar el inventario del almacen-->

<!--Empezando sección para mostrar el formulario para agregar nuevos productos-->
<div id="divAgregarProducto" class="content" style="display:none"></div>
<!--Finalizando sección para mostrar el formulario para agregar nuevos productos-->

<!--Empezando seccion para el trapaso de productos entre almacenes-->
<div id="divTraspasarProducto" class="content" style="display:none"></div>
<!--Finalizando seccion para el trapaso de productos entre almacenes-->

<!--Empezando seccion para el trapaso de productos entre almacenes-->
<div id="divVerTraspasos" class="content" style="display:none"></div>
<!--Finalizando seccion para el trapaso de productos entre almacenes-->

<!--Empezando seccion para el trapaso de productos entre almacenes-->
<div id="divVerAltasIniciales" class="content" style="display:none"></div>
<!--Finalizando seccion para el trapaso de productos entre almacenes-->

<!--Empezando seccion para el trapaso de productos entre almacenes-->
<div id="divVerKitsEquipos" class="content" style="display:none"></div>
<!--Finalizando seccion para el trapaso de productos entre almacenes-->

<!--Empezando seccion para el trapaso de productos entre almacenes-->
<div id="divDeshuesarEquipo" class="content" style="display:none"></div>
<!--Finalizando seccion para el trapaso de productos entre almacenes-->
