<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Solicitudes</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel nuevo proyecto-->
    <div id="panelNuevaSolicitud" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div id="botonesExtra" class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Nueva Solicitud</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando Formulario para nueva solicitud -->                
            <form id="formNuevaSolicitud" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
                <!--Inicia formulario para catálogo de sistema especial-->
                <fieldset>   

                    <!--Empezando select de personal--> 
                    <div class="row m-t-10">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="selectPersonal">Personal</label>
                                <select id="selectParsonalSolicitud" class="form-control" name="personalSolicitud" style="width: 100%">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($datos['CatalogoUsuarios'] as $item) {
                                        echo '<option value="' . $item['IdPerfil'] . '">' . $item['Nombre'] . ' ' . $item['ApPaterno'] . ' ' . $item['ApMaterno'] . '</option>';
                                    }
                                    ?>
                                </select>                            
                            </div>
                        </div>
                        <?php
                        if (isset($datos['Apoyo']) && $datos['Apoyo']) {
                            ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label >Asignar en SD a:</label>
                                    <select id="selectPersonalSD" class="form-control" name="selectPersonalSD" style="width: 100%">
                                        <option value="">Seleccionar</option>
                                        <?php
                                        foreach ($datos['UsuariosSD']->operation->details as $key => $value) {
                                            echo '<option value="' . $value->TECHNICIANID . '">' . $value->TECHNICIANNAME . '</option>';
                                        }
                                        ?>
                                    </select>                            
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <!--Finalizando-->

                    <!--Empezando fila 1 de los campos area, departamento y prioridad--> 
                    <div class="row m-t-10">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="selectArea">Área *</label>
                                <select id="selectAreasSolicitud" class="form-control" name="areaSolicitud" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <option value="sinArea">No conozco el área</option>
                                    <?php
                                    foreach ($datos['CatalogoAreas'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>                            
                            </div>
                        </div>                                                
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="selectDepartamento">Departamento *</label>
                                <select id="selectDepartamentoSolicitud" class="form-control" name="departamentoSolicitud" style="width: 100%" data-parsley-required="true" disabled>
                                    <option value="">Seleccionar</option>                                    
                                </select>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="prioridad">Prioridad *</label>
                                <select id="selectPrioridadSolicitud" class="form-control" name="prioridadSolicitud" style="width: 100%" data-parsley-required="true" >
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($datos['CatalogoPrioridades'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>                            
                            </div>
                        </div>
                    </div>
                    <!--Finalizando fila 1 de los campos area, departamento y prioridad-->

                    <div class="row m-t-10">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php
                                if (isset($datos['Folio'])) {
                                    $folio = $datos['Folio'];
                                    $disabled = 'disabled';
                                } else {
                                    $folio = '';
                                    $disabled = '';
                                }
                                ?>
                                <label for="inputFolioSolicitud">Folio</label>
                                <input type="text" class="form-control" id="inputFolioSolicitud" placeholder="Ingresa el Folio" value="<?php echo $folio ?>" style="width: 100%" maxlength="100" <?php echo $disabled; ?>/>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="selectClienteSolicitud">Cliente</label>
                                <select id="selectClienteSolicitud" class="form-control" name="clienteSolicitud" style="width: 100%">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($datos['CatalogoClientesActivos'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="selectSucursalSolicitud">Sucursal</label>
                                <select id="selectSucursalSolicitud" class="form-control" name="sucursalSolicitud" style="width: 100%" disabled>
                                    <option value="">Seleccionar</option>
                                </select>                            
                            </div>
                        </div>
                    </div>

                    <!--Empezando fila 2 asunto--> 
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="descripcionSolicitud">Asunto *</label>
                                <input type="text" class="form-control" id="inputAsuntoSolicitud" placeholder="Ingresa el Asunto de la Solicitud" style="width: 100%" data-parsley-required="true" maxlength="100"/>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label id="campoCorreo">Enviar copia a (Correo(s)) </label>
                                <ul id="tagValor" class="inverse"></ul>
                            </div>
                        </div>
                    </div>    

                    <!--Empezando fila 3 descripción--> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcionSolicitud">Descripción *</label>
                                <textarea id="textareaDescripcionSolicitud" class="form-control nuevoProyecto" name="descricpcionSolicitud" placeholder="Ingresa la descripción del problema... " rows="8" data-parsley-required="true" ></textarea>
                            </div>
                        </div>
                    </div>    
                    <!--Finalizando fila 3 descripción-->

                    <!--Empezando fila 4 input para evidencias -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="evidenciaSolicitud">Evidencia</label>
                                <input id="inputEvidenciasSolicitud" name="evidenciasSolicitud[]" type="file" multiple>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando fila 4 input para evidencias -->

                    <!--Empezando error--> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="errorSolicitudNueva"></div>
                        </div>
                    </div>
                    <!--Finalizando Error-->  

                    <!--Empezando fila 5 botones-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">                                                                
                                <div id="btnSeccionGenerarSolicitud" class="col-md-offset-2 col-md-8 text-center m-t-10 m-b-10">
                                    <button id="btnGenerarSolicitud" type="button" class="btn btn-sm btn-success m-r-5" ><i class="fa fa-check"></i> Generar</button>
                                    <button id="btnCancelarNuevaSolicitud" type="button" class="btn btn-sm btn-danger m-r-5" ><i class="fa fa-refresh"></i> Limpiar</button>                                
                                </div>
                            </div>
                        </div>
                    </div>    
                    <!--Finalizando fila 5 botonnes-->

                </fieldset>
            </form>
            <!-- Finaliza formulario para catálogo de sistema especial-->
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel nuevo proyecto -->
</div>
<!-- Finalizando #contenido -->