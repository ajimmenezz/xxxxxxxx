<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo <small>de Perfiles</small></h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel catálogo de perfiles -->
    <div id="seccionPerfiles" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <label id="btnRegresarPerfiles" class="btn btn-success btn-xs hidden">
                    <i class="fa fa fa-reply"></i> Regresar
                </label> 
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Catálogo de Perfiles</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="panel-body">
                <!--                <div id="seccionDatosProyecto" class="panel panel-default borde-sombra" data-sortable-id="ui-widget-10">
                                    <div class="panel-heading bg-cabecera-subpanel">
                                        <div class="panel-heading-btn">                                                
                                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                        
                                        </div>
                                        <h3 class="panel-title">Nuevo Perfil</h3>
                                    </div>
                                    <div class="panel-body ">     
                                        Inicia formulario para catálogo de perfiles 
                                        <form class="margin-bottom-0" id="formPerfiles" data-parsley-validate="true">
                                            <div class="row m-t-10">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="nombrePerfil">Área *</label>
                                                        <select id="selectArea" class="form-control" style="width: 100%" data-parsley-required="true">
                                                            <option value="">Seleccionar</option>
                <?php
                foreach ($datos['SelectAreas'] as $item) {
                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                }
                ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="nombrePerfil">Departamento *</label>
                                                        <select id="selectDepartamento" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                                            <option value="">Seleccionar</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="catalogoPerfil">Nombre *</label>
                                                        <input type="text" class="form-control" id="inputNombrePerfil" placeholder="Ingresa nombre de la perfil" style="width: 100%" data-parsley-required="true"/>                            
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-t-10"> 
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="nivelPerfil">Nivel *</label>
                                                        <select id="selectNivel" class="form-control" style="width: 100%" data-parsley-required="true">
                                                            <option value="">Seleccionar</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
                                                            <option value="7">7</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="catalogoPerfil">Clave *</label>
                                                        <input type="text" class="form-control" id="inputClave" placeholder="Ingresa clave del perfil" style="width: 100%" data-parsley-required="true"/>                            
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="catalogoPerfil">Cantidad *</label>
                                                        <input type="number" class="form-control" id="inputCantidad" placeholder="999" style="width: 100%" data-parsley-required="true"/>                            
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-t-10">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="catalogoPerfil">Descripción *</label>
                                                        <textarea class="form-control" id="inputDescripcionPerfil" rows="4" placeholder="Descripción breve de que trata el perfil" style="width: 100%" data-parsley-required="true"/> </textarea>                               
                                                    </div>
                                                </div>
                <?php if ($datos['Autorizacion']) { ?>
                                                                                            <div class="col-md-12">
                                                                                                <div class="form-group">
                                                                                                    <label for="selectPermisos">Permisos *</label>
                                                                                                    <select id="selectPermisos" class="form-control" style="width: 100%" multiple="multiple">
                    <?php
                    foreach ($datos['Permisos'] as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                                                                                                    </select>
                                                                                                    <input type="checkbox" id="checkboxPerfiles" > Todos los permisos
                                                                                                </div>
                                                                                            </div>
                <?php } ?>
                                                <div class="col-md-12">
                                                    <div class="form-group text-center">
                                                        <br>
                                                        <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevoPerfil"><i class="fa fa-plus"></i> Agregar</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="form-inline muestraCarga"></div>
                                                    </div>
                                                </div>
                                                Empezando error 
                                                <div class="col-md-12">
                                                    <div class="errorPerfil"></div>
                                                </div>
                                                Finalizando Error
                                            </div>
                                        </form>
                                    </div>
                                </div>-->
                <!-- Finaliza formulario para catalogo de perfiles-->                       
                <!--Finalizando cuerpo del panel-->
                <!--Empezando el formulario Sucursal -->
                <div id="formularioPerfil">
                </div>
                <!--Finalizando formulario Sucursal-->
                <div class="row m-t-10">
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorPerfiles"></div>
                    </div>
                    <!--Finalizando Error-->
                </div>   
                <div id='listaPerfiles'> 
                    <div class="row">
                        <div class="col-md-12">                        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <h3 class="m-t-10">Lista de Perfiles</h3>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group text-right">
                                            <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarPerfil"><i class="fa fa-plus"></i> Agregar</a>
                                        </div>
                                    </div>
                                </div>

                                <!--Empezando Separador-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>
                                </div>
                                <!--Finalizando Separador-->

                            </div>    
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-perfiles" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Nombre</th>
                                    <th class="all">Área</th>
                                    <th class="all">Departamento</th>
                                    <th class="never">Permisos</th>
                                    <th class="all">Descripción</th>
                                    <th class="all">Nivel</th>
                                    <th class="all">Clave</th>
                                    <th class="all">Cantidad</th>
                                    <th class="all">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($datos['ListaPerfiles'])) {
                                    foreach ($datos['ListaPerfiles'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '<td>' . $value['Area'] . '</td>';
                                        echo '<td>' . $value['Departamento'] . '</td>';
                                        echo '<td>' . $value['Permisos'] . '</td>';
                                        echo '<td>' . $value['Descripcion'] . '</td>';
                                        echo '<td>' . $value['Nivel'] . '</td>';
                                        echo '<td>' . $value['Clave'] . '</td>';
                                        echo '<td>' . $value['Cantidad'] . '</td>';
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
                <!-- Finaliza formulario para catálogo de perfiles -->
            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel catálogo de perfiles -->
    </div>
    <!-- Finalizando #contenido -->