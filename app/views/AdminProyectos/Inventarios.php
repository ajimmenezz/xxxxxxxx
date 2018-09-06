<!-- Empezando #contenido -->
<div id="listaAlmacenes" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Inventarios por Almacén</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel de almacenes-->
    <div id="panelListaAlmacenes" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">                
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Almacenes Virtuales</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <div class="col-md-12">  
                    <div class="form-group">
                        <div class="col-md-6">
                            <h3 class="m-t-10">Almacenes</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                        <table id="data-table-almacenes" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Clave</th>
                                    <th class="all">Almacén</th>
                                    <th class="desktop tablet-p">Encargado</th>
                                    <th class="all">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($datos['Almacenes'])) {
                                    foreach ($datos['Almacenes'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Almacen'] . '</td>';
                                        echo '<td>' . $value['Encargado'] . '</td>';
                                        echo '<td>' . $value['Estatus'] . '</td>';
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
    <!-- Finalizando panel de almacenes -->   

    <!-- Empezando panel de inventarios-->
    <div id="panelInventarioAlmacen" class="panel panel-inverse hidden">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">      
                <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Acciones <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li id="btnExportarExcel"><a href="#"><i class="fa fa-file-excel-o"></i> Exportar Excel</a></li>
                    </ul>
                </div>
                <label id="btnRegresarSeguimiento" class="btn btn-success btn-xs">
                    <i class="fa fa fa-reply"></i> Regresar
                </label>
            </div>
            <h4 class="panel-title">Almacenes Virtuales</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <div class="col-md-12">  
                    <div class="form-group">
                        <div class="col-md-6">
                            <h3 class="m-t-10">Inventario del Almacén <span id="nombreAlmacen"></span></h3>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                        <table id="data-table-inventario" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Clave</th>
                                    <th class="all">Producto</th>
                                    <th class="desktop tablet-p">Línea</th>
                                    <th class="all">Unidad</th>
                                    <th class="all">Existencias</th>
                                    <th class="all">Costo</th>
                                </tr>
                            </thead>
                            <tbody>                                                                 
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel de inventario -->   
</div>
<!-- Finalizando #contenido -->