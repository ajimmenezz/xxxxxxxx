<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Resumen <small>de Archivos</small></h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel resumen personal -->
    <div id="seccionResumenArchivos" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Resumen de Archivos</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div id="tablaResumenArchivos" class="row">
                <form id="formSeleccionarArchivo" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-inline muestraCarga"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h3 class="m-t-10">Lista de Archivos</h3>
                        <!--Empezando Separador-->
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="resumenArchivo">Tipo de Archivo *</label>
                            <div class="input-group">
                                <select id="selectTiposArchivos" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($datos['SelectArchivos'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <span class="input-group-addon">
                                    <a href="javascript:;" class="btn btn-default btn-xs" id="btnBuscarTipoArchivo"><i class="fa fa-search"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorResumenArchivo"></div>
                    </div>
                </form>
                <!--Finalizando Error-->
                <div class="col-md-12">                        
                    <div class="form-group">
                        <table id="data-table-archivos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Nombre</th>
                                    <th class="all">Fecha</th>
                                    <th class="all">Archivo</th>
                                </tr>
                            </thead>
                        </table>
                    </div>    
                </div> 
            </div>
            <div id="seccionActualizarArchivo" class="row hidden">
            </div>
        </div>
    </div>
    <!-- Finalizando panel resumen archivos -->
</div>
<!-- Finalizando #contenido -->