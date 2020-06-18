<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Problemas y Faltantes Mantenimientos</h1>
    <!-- Finalizando titulo de la pagina -->

    <div id="seccion-reportes-problemas-faltantes" class="panel panel-inverse borde-sombra">

        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <?php
                foreach ($usuario ["Permisos"] as $value) {
                    if ($value == 324) {
                        echo '<div class="btn-group">
                                <a href="javascript:;" data-toggle="dropdown" class="btn btn-xs btn-warning dropdown-toggle">Acciones <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                        <li><a id="reporteAnual" href="javascript:;">Reporte Anual</a></li>
                                        <li><a id="reporteSemanal" href="javascript:;">Reporte Semanal</a></li>
                                        <li><a id="compararFolios" href="javascript:;">Comparativa Adist/SD</a></li>
                                        <li><a id="equiposRefacciones" href="javascript:;">Equipos y Refacciones</a></li>
                                </ul>
                        </div>';
                    }
                }
                ?>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Problemas y Faltantes Mantemientos</h4>
        </div>
        <!--Finalizando cabecera del panel-->

        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row m-t-20">
                <div class="col-md-12">                            
                    <fieldset>
                        <legend class="pull-left width-full f-s-17">Asistente de Búsqueda.</legend>
                    </fieldset>  
                </div>
            </div>

            <div class="row">                                          
                <div class="col-md-6 col-xs-6">
                    <div class="form-group">
                        <label for="esdeProblemasFaltantesMatenimiento">Desde *</label>
                        <div class='input-group date' id='desdeProblemasFaltantesMantenimiento'>
                            <input type='text' id="txtDesdeProblemasFaltantesMantenimiento" class="form-control" value="" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>                
                </div>                                                        
                <div class="col-md-6 col-xs-6">
                    <div class="form-group">
                        <label for="hastaProblemasFaltantesMantenimiento">Hasta *</label>
                        <div class='input-group date' id='hastaProblemasFaltantesMantenimiento'>
                            <input type='text' id="txtHastaProblemasFaltantesMantenimiento" class="form-control" value="" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>                
                </div>
            </div>

            <div class="row m-t-20">
                <div class="col-md-6">      
                    <div class="form-group">
                        <label for="selectFiltroZonaSucursalesReportesPoliza">Zona(s) *</label>
                        <select id="selectFiltroZonaSucursalesReportesPoliza" class="form-control" style="width: 100%" multiple="multiple">                                                                                                       
                            <option value="">Seleccionar</option>
                            <?php
                            foreach ($datos['ListaRegionesCliente'] as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">      
                    <div class="form-group">
                        <label for="selectFiltroSucursalReportePoliza">Sucursal(es)</label>
                        <select id="selectFiltroSucursalReportePoliza" class="form-control" style="width: 100%" multiple="multiple">                                                                                                       
                            <option value="">Seleccionar</option>
                            <?php
                            foreach ($datos['Sucursales'] as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div> 

            <!--Empezando error mostrar reporte--> 
            <div class="row m-t-10">
                <div class="col-md-12">
                    <div id="errorMostrarReporteProblemasFaltantesMantemientos"></div>
                </div>
            </div>
            <!--Finalizando-->

            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <a href="javascript:;" id="btnMostrarReporteProblemasFaltantesMantemientos" class="btn btn-success"><i class="fa fa-eye"></i> Mostrar Reporte</a>
                </div>
            </div>

        </div>
        <!--Finalizando cuerpo del panel-->
    </div>

    <!--Empezando seccion reporte problemas y falantes mantemientos -->
    <div id="seccionReporteProblemasFaltantesMantemientos" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1"></div>
    <!-- Finalizando seccion reporete problemas y faltantes mantemientos --> 
