<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo <small>de Regiones Logística</small></h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel catálogo de regiones logistica -->
    <div id="seccionRegionesLogistica" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo de Regiones Logística</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="panel-body">
                <div id="seccionDatosProyecto" class="panel panel-default borde-sombra" data-sortable-id="ui-widget-10">
                    <div class="panel-heading bg-cabecera-subpanel">
                        <div class="panel-heading-btn">                                                
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                        
                        </div>
                        <h3 class="panel-title">Agregar Región Logística</h3>
                    </div>
                    <div class="panel-body ">     
                        <!--Inicia formulario para catálogo de regiones logistica -->
                        <form class="margin-bottom-0" id="formRegionesLogistica" data-parsley-validate="true">
                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="catalogoRegionesLogistica">Nombre de la Región *</label>
                                        <input type="text" class="form-control" id="inputNombreRegion" placeholder="Ingresa nombre de la Región" style="width: 100%" data-parsley-required="true"/>                            
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="catalogoRegionLogistica">Descripción *</label>
                                        <input class="form-control" id="inputDescripcionRegion" placeholder="Descripción breve de que trata la Región" style="width: 100%" data-parsley-required="true"/> </textarea>                               
                                    </div>
                                </div>
                            </div>
                            <div class="row m-t-10">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="catalogoRegionLogistica">Sucursales *</label>
                                        <select id="selectSucursalesRegion" class="form-control" style="width: 100%" multiple="multiple" data-parsley-required="true">
                                            <?php
                                            foreach ($datos['Sucursales'] as $item) {
                                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <br>
                                        <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevaRegion"><i class="fa fa-plus"></i> Agregar</a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-inline muestraCarga"></div>
                                    </div>
                                </div>
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorRegion"></div>
                                </div>
                                <!--Finalizando Error-->
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Finaliza formulario para catalogo de regiones logistica-->                       
            </div>
            <!--Finalizando cuerpo del panel-->
            <!--Empezando tabla fila 2 -->
            <div class="row"> 
                <div class="col-md-12">                        
                    <div class="form-group">
                        <h3 class="m-t-10">Lista de Regiones Logística</h3>
                        <!--Empezando Separador-->
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                        <table id="data-table-regiones" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Región</th>
                                    <th class="desktop">Descripción</th>
                                    <th class="desktop tablet-l">Sucursales</th>
                                    <th class="all">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['ListaRegiones'] as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Id'] . '</td>';
                                    echo '<td>' . $value['Nombre'] . '</td>';
                                    echo '<td>' . $value['Descripcion'] . '</td>';
                                    echo '<td>' . $value['Sucursales'] . '</td>';
                                    if ($value['Flag'] === '1') {
                                        echo '<td data-flag="' . $value['Flag'] . '">Activo</td>';
                                    } else {
                                        echo '<td data-flag="' . $value['Flag'] . '">Inactivo</td>';
                                    }
                                    echo '</tr>';
                                }
                                ?>                                        
                            </tbody>
                        </table>
                    </div>    
                </div> 
            </div>
            <!--Finalizando tabla fila 2-->
            <!-- Finaliza formulario para catálogo de regiones logistica -->
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel catálogo de regiones logistica -->
</div>
<!-- Finalizando #contenido -->