<!--
 * Description: Formulario para agregar Avences a la actividad
 *
 * @author: Alberto Barcenas
 *
-->

<!-- Empezando titulo de la pagina -->
<div class="row">
    <div class="col-md-6 col-xs-6">
        <h1 class="page-header">Seguimiento Actividad</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li id="btnConcluirActividadSeguimientoActividad"><a href="#" class="concluir-actividad"><i class="fa fa-unlock-alt"></i> Concluir Actividad</a></li>                             
            </ul>
        </div>
        <label id="btnRegresarSeguimientoActivadSalas4XD" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>
<div id="panelSeguimientoActividad" class="panel panel-inverse">

    <!--Empezando Pestañas para definir la seccion-->
    <div class="panel-heading p-0">
        <div class="panel-heading-btn m-r-10 m-t-10">
            <!-- Single button -->                                  
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <!-- begin nav-tabs -->
        <div class="tab-overflow">
            <ul class="nav nav-tabs nav-tabs-inverse">
                <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                <li class="active"><a href="#HistorialAvances" data-toggle="tab">Historial Avances</a></li>
                <li class=""><a href="#Avances" data-toggle="tab">Avances</a></li>
                <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
            </ul>
        </div>
    </div>
    <!--Finalizando Pestañas para definir la seccion-->

    <!--Empezando contenido-->
    <div class="tab-content">

        <!--Empezando la seccion Asignacion de Actividades-->
        <div class="tab-pane fade active in" id="HistorialAvances">            
            <div class="panel-body">
                <div id="divHistorialAvances">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 m-b-20">
                            <h3>Historial Avances</h3>
                            <div class="underline"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="errorConcluir"></div>
                        </div>                                    
                    </div>
                    <div class="row m-t-20">
                        <div class="col-md-12 col-xs-12">
                            <div id="divNotasServicio">
                                <!--<div class="height-sm">-->
                                    <ul id="ulListaNotas" class="media-list media-list-with-divider media-messaging">
                                        <?php
                                        echo $htmlHistorialAvances;
                                        ?>
                                    </ul>
                                <!--</div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col-6 -->

        <!--Empezando la seccion servicio Correctivo-->
        <div class="tab-pane fade" id="Avances">
            <!-- Empezando cuerpo del panel -->
            <div class="panel-body">
                <div class="row"> 
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorListaSalasX4D"></div>
                    </div>
                    <!--Finalizando Error-->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="errorConcluirAvance"></div>
                    </div>                                    
                </div>
                <div class="row"> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <h3 id="tituloSeguimientoActividad" class="m-t-10"></h3>
                        </div>
                    </div>
                </div>

                <div class="row"> 
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                    <!--Finalizando Separador-->
                </div>   

                <!--Empezando formulario Avence servicio -->
                <form class="margin-bottom-0" id="formSeguimientoActividad" data-parsley-validate="true">

                    <div class="row">
                        <div class="col-md-6 col-sm-6 text-center">
                            <label>
                                <input id="radioMantemientoGeneral" type="radio" name="radioMantenimiento" value="general"/> Mantemimineto General
                            </label>
                        </div>
                        <div class="col-md-6 col-sm-6 text-center">
                            <label>
                                <input id="radioMantemimientoElemento" type="radio" name="radioMantenimiento" value="elemento" /> Mantenimiento a Elemento / Subelemento
                            </label>
                        </div>

                    </div>

                    <!--Empezando Decripcion-->
                    <div class="row">
                        <div class="col-md-12">                                    
                            <div class="form-group">
                                <label>Observaciones *</label>
                                <textarea id="inputObservacionesSeguimientoActividad" class="form-control " placeholder="Agregar Observaciones" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <!--Empezando Archivos-->
                    <div class="row">
                        <div class="col-md-12">                                    
                            <div class="form-group">
                                <label >Archivos *</label>
                                <input id="archivosSeguimientoActividad"  name="archivosSeguimientoActividad[]" type="file" multiple/>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Ubicación *</label>
                                <select id="selectUbicacionSeguimientoActividad" class="form-control" style="width: 100%">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($ubicaciones as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div id="divMantimientoElemento" class="hidden">
                        <!-- Empezando Seleccion de Producto -->
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Elemento *</label>
                                    <select id="selectElementoSeguimientoActividad" class="form-control" style="width: 100%" disabled>
                                        <option value="">Seleccionar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Subelemento</label>
                                    <select id="selectSubelementoSeguimientoActividad" class="form-control" style="width: 100%" disabled>
                                        <option value="">Seleccionar</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Empezando boton de agregar y mensaje de error-->
                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div id="errorProductoSeguimientoActividad"></div>
                        </div>
                        <!--Finalizando Error-->

                    </div>
                    <!--Finalizando-->

                    <!--Empezando Tabla Avences-->
                    <div class="row"> 
                        <div class="col-md-12">  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Lista de Productos</h3>
                                </div>
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Finalizando Separador-->
                            </div>    
                        </div> 
                    </div>

                    <!-- Empezando Seleccion de Producto -->
                    <div class="row ">
                        <div class="col-md-5 col-sm-5 col-xs-5">
                            <div class="form-group">
                                <label>Tipo de Producto</label>
                                <select id="selectTipoProducto" class="form-control" style="width: 100%">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($tipoProductos as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-5 col-xs-5">
                            <div class="form-group" >
                                <label>Producto</label>
                                <select id="selectProducto" class="form-control" style="width: 100%" disabled>
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-2  col-xs-2 m-t-20 text-center">
                            <div class="form-group">
                                <a id="btnAgregarProductoSeguimientoActividad" href="javascript:;" class="btn btn-success m-r-5 "><i class="fa fa-plus"></i> Agregar</a>                            
                            </div>
                        </div>

                    </div>
                    <!-- Finalizando -->

                    <div id="divCantidadSeguimientoActividad" class="row hidden">
                        <div class="col-md-5">
                            <div class="form-group">                                           
                                <label for="inputCantidadSeguimientoActividad">Cantidad *</label>
                                <input id="inputCantidadSeguimientoActividad" type="number" class="form-control"  placeholder="Cantidad" min="0" max="' . $value['Cantidad'] . '"/>
                            </div>                               
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="data-table-productos-seguimiento-actividad" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">@</th> 
                                    <th class="never">IdTipoProducto</th>
                                    <th class="all">Tipo Producto</th>
                                    <th class="all">Producto</th>
                                    <th class="all">Cantidad</th> 
                                    <th class="never">IdProducto</th> 
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!--Finalizando -->

                    <div class="row">
                        <div class="col-md-12 m-t-20">
                            <div class="alert alert-warning fade in m-b-15">                            
                                Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.                            
                            </div>                        
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div id="errorMantenimientoGeneralSeguimientoActividad"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a id="btnGuardarMantenimientoGeneralSeguimientoActividad" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Información</a>                            
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Finalizando cuerpo del panel -->
            </div>
        </div>
    </div>
