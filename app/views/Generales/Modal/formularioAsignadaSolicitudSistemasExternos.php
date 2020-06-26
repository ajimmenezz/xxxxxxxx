<!--Empezando seccion para seguimiento de la solicitud-->
<div id="seccionSeguimiento" >      
    <?php if (!empty($datos['Folio'])) { ?>
        <?php if ($datos['TipoSolicitud'] === '6') { ?>
            <div class="row">
                <div class="col-sm-7 col-md-5">          
                    <div class="form-group">
                        <label for="solicitaSolictud"> Solicita: <strong>Sistema Externo Adist 2</strong></label>
                    </div>    
                </div> 
                <div class = "col-md-3">
                    <div class = "form-group">
                        <label for = "ticket"> Ticket: <strong > <?php echo $datos['Ticket']; ?></strong></label>
                    </div>
                </div>            
                <div class="col-sm-5 col-md-4">          
                    <div class="form-group">
                        <label for="fechasolicitud"> Fecha: <strong><?php echo $datos['Fecha']; ?></strong></label>                        
                    </div>    
                </div>
            </div>            
            <div class="row">
                <div class="col-md-12">          
                    <div class="form-group">
                        <label for="asunto">Descripción : </label>
                        <p><strong><?php echo $datos['detalles'][0]['Descripcion']; ?></strong></p>
                    </div>    
                </div>
            </div>
            <!--Empezando Separador-->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label ><h5>Información Service Desk</h5></label>
                        <div class="underline m-b-15"></div>
                    </div>
                </div>
            </div>
            <!--Finalizando Separador-->
        <?php } ?>
        <div class="row">
            <div class="col-md-4">          
                <div class="form-group">
                    <label for="folio">Folio Service Desk: </label>
                    <?php
                    $folio = 'Solicitud no entra bajo su ámbito permitido. Por lo tanto usted no está autorizado para actualizar la misma';
                    if (isset($datosSD->WORKORDERID)) {
                        $folio = $datosSD->WORKORDERID;
                    }
                    ?>
                    <p><strong><?php echo $folio; ?></strong></p>
                </div>    
            </div> 
            <div class="col-md-4">          
                <div class="form-group">
                    <label for="estatus">Estatus Service Desk: </label>
                    <?php
                    $estatus = 'Solicitud no entra bajo su ámbito permitido. Por lo tanto usted no está autorizado para actualizar la misma';
                    if (isset($datosSD->STATUS)) {
                        $estatus = $datosSD->STATUS;
                    }
                    ?>
                    <p><strong><?php echo $estatus; ?></strong></p>
                </div>    
            </div>
            <div class="col-md-4">          
                <div class="form-group">
                    <label for="prioridad">Prioridad: </label>
                    <?php
                    $prioridad = 'Solicitud no entra bajo su ámbito permitido. Por lo tanto usted no está autorizado para actualizar la misma';
                    if (isset($datosSD->PRIORITY)) {
                        $prioridad = $datosSD->PRIORITY;
                    }
                    ?>
                    <p><strong><?php echo $prioridad; ?></strong></p>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">          
                <div class="form-group">
                    <label for="creado">Creado Por : </label>
                    <?php
                    $creadoPor = 'Solicitud no entra bajo su ámbito permitido. Por lo tanto usted no está autorizado para actualizar la misma';
                    if (isset($datosSD->CREATEDBY)) {
                        $creadoPor = $datosSD->CREATEDBY;
                        ?>
                        <input type = "hidden" id = "creator-sd" value = "<?php echo $datosSD->CREATEDBY; ?>" />
                        <input type = "hidden" id = "requester-sd" value = "<?php echo $datosSD->REQUESTER; ?>" />
                        <?php
                    }
                    ?>
                    <p><strong><?php echo $creadoPor; ?></strong></p>
                </div>    
            </div> 
            <div class="col-md-4">          
                <div class="form-group">
                    <label for="estatus">Técnico Asignado: </label>
                    <?php
                    $tecnico = 'Solicitud no entra bajo su ámbito permitido. Por lo tanto usted no está autorizado para actualizar la misma';
                    if (isset($datosSD->TECHNICIAN)) {
                        $tecnico = $datosSD->TECHNICIAN;
                    }
                    ?>
                    <p><strong><?php echo $tecnico; ?></strong></p>
                </div>    
            </div>
            <div class="col-md-4">          
                <div class="form-group">
                    <label for="prioridad">Fecha Creación: </label>
                    <?php
                    $fechaCracion = 'Solicitud no entra bajo su ámbito permitido. Por lo tanto usted no está autorizado para actualizar la misma';
                    if (isset($datosSD->CREATEDTIME)) {
                        $fechaCreacion = date('Y-m-d H:i:s', $datosSD->CREATEDTIME / 1000);
                    }
                    ?>
                    <p>
                        <strong><?php echo $fechaCracion; ?></strong>
                    </p>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">          
                <div class="form-group ">
                    <label for="asunto"><a  id="ocultarMostrarSD" href="javascript:;"><span >+ Mostrar detalles</span> de Service Desk</a></label>
                </div>    
            </div>
        </div>
        <div id="contenidoSD" class="hidden">
            <div class="row">
                <div class="col-md-12">          
                    <div class="form-group">
                        <label for="asunto">Asunto: </label>
                        <?php
                        $asunto = 'Solicitud no entra bajo su ámbito permitido. Por lo tanto usted no está autorizado para actualizar la misma';
                        if (isset($datosSD->SUBJECT)) {
                            $asunto = $datosSD->SUBJECT;
                        }
                        ?>
                        <p><strong><?php echo $asunto; ?></strong></p>
                    </div>    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">          
                    <div class="form-group">
                        <label for="Solicitud">Solicitud: </label>
                        <?php
                        $solicitud = 'Solicitud no entra bajo su ámbito permitido. Por lo tanto usted no está autorizado para actualizar la misma';
                        if (isset($datosSD->SHORTDESCRIPTION)) {
                            $solicitud = $datosSD->SHORTDESCRIPTION;
                        }
                        ?>
                        <p><strong><?php echo $solicitud; ?></strong></p>
                    </div>    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">          
                    <div class="form-group">
                        <label for="resolucion">Resolución: </label>
                        <p>
                            <strong>
                                <?php
                                if (isset($datosResolucionSD->operation->Details)) {
                                    echo $datosResolucionSD->operation->Details->RESOLUTION;
                                } else {
                                    echo "No hay resolución para este Folio.";
                                }
                                ?>
                            </strong>
                        </p>
                    </div>    
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="row">
            <div class="col-sm-7 col-md-8">          
                <div class="form-group">
                    <label for="solicitaSolictud"> Solicita: <strong>Sistema Externo Adist 2</strong></label>
                </div>    
            </div> 
            <div class="col-sm-5 col-md-4">          
                <div class="form-group">
                    <label for="fechasolicitud"> Fecha: <strong><?php echo $datos['Fecha']; ?></strong></label>                        
                </div>    
            </div>
        </div>
        <div class="row">            
            <div class = "col-md-12">
                <div class = "form-group">
                    <label for = "ticket"> Ticket: <strong > <?php echo $datos['Ticket']; ?></strong></label>
                </div>
            </div>            
        </div>        
        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="asunto">Descripción : </label>
                    <p><strong><?php echo $datos['detalles'][0]['Descripcion']; ?></strong></p>
                </div>    
            </div>
        </div>
    <?php } ?>


    <!--Empezando Separador-->
    <div class="row">
        <div class="col-md-12">
            <div class="underline m-t-15"></div>
        </div>
    </div>
    <!--Finalizando Separador-->

    <!--Empezando secccion de servicios-->
    <!--Empezando titulo de servicios-->
    <div class="row">
        <div class="col-md-12">          
            <div class="form-group">
                <h4>Servicios</h4>
            </div>    
        </div>
    </div>
    <!--Empezando titulo de servicios-->
    <!--Empezando formulario para los servicios-->
    <form id="formAgregarSservicio" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="servicioCliente"> Cliente * </label>
                    <select id="selectCliente" class="form-control" name="clienteServicio" style="width: 100%" data-parsley-required="true" >
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($cliente as $key => $value) {
                            echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                        }
                        ?>
                    </select>                            
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="servicioCliente"> Sucursal * </label>
                    <select id="selectSucursal" class="form-control" name="sucursalServicio" style="width: 100%" data-parsley-required="true" >
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($sucursales as $key => $value) {
                            $selected = ($value['NombreCinemex'] == $datosSD->REQUESTER || $value['NombreCinemex'] == $datosSD->CREATEDBY) ? 'selected' : '';
                            echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                        }
                        ?>
                    </select>                            
                </div>    
            </div>
        </div>   
        <div class="row">          
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="atiendeServicio"> Atiende * </label>
                    <select id="selectAtiendeServicio" class="form-control" name="atiendeServicio" style="width: 100%" data-parsley-required="true" >
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($atiende as $key => $value) {
                            $selected = ($value['SDName'] == $datosSD->TECHNICIAN) ? 'selected' : '';
                            echo '<option value="' . $value['IdUsuario'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                        }
                        ?>
                    </select>                            
                </div>   
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="servicioDepartamento"> Tipo de servicio * </label>
                    <select id="selectServicioDepartamento" class="form-control" name="servicioDepartemento" style="width: 100%" data-parsley-required="true" >
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($servicios as $key => $value) {
                            echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . ' (' . $value['Departamento'] . ')</option>';
                        }
                        ?>
                    </select>                            
                </div>    
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="servicioDepartamento"> Descripción *</label>
                    <?php
                    $descripcion = '';
                    if (isset($datosSD->WORKORDERID)) {
                        $descripcion = $datosSD->WORKORDERID . "  --  " . $datosSD->SUBJECT;
                    }
                    ?>
                    <input value="<?php echo $descripcion; ?>" id="inputDescripcionServicio" type="text"  class="form-control" placeholder="Describicion breve .." data-parsley-required="true"/>
                </div>    
            </div>
        </div>
    </form>
    <!--Finalizando formulario para los servicios-->

    <!--Empezando boton para agregar servicio-->
    <div class="row">
        <div class="col-md-12 text-center">          
            <div class="form-group">
                <button id="btnAgregarServicio" type="button" class="btn btn-sm btn-primary m-r-5" >Agregar Servicio</button>
            </div>    
        </div>
    </div>
    <!--Finalizando boton para agregar servicio-->

    <!--Empezando tabla de servicio-->
    <div class="row">
        <div class="col-md-12 text-center">          
            <div class="form-group">
                <table id="data-table-servicio" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                    <thead>
                        <tr>                            
                            <th>Servicio</th>
                            <th>Sucursal</th>
                            <th>Atiende</th>                            
                            <th>Descripción</th>
                            <th class="none">Idservicio</th>
                            <th class="none">IdSucursal</th>
                            <th class="none">IdAtiende</th>                        
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>                
            </div>    
        </div>
    </div>    
    <div class="row">
        <div class="col-md-12 m-t-20">
            <div class="alert alert-warning fade in m-">                            
                <strong>Warning!</strong> Para eliminar el registro de la tabla solo tiene que dar click sobre fila.
            </div>                        
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 m-t-20">
            <div id="errorGenerarTicket"></div>
        </div>
    </div>   
    <!--Finalizando tabla de servicio-->
    <!--Empezando secccion de servicios--> 

    <!--Empezando Separador-->
    <div class="row">
        <div class="col-md-12">
            <div class="underline m-t-15"></div>
        </div>  
    </div>
    <!--Finalizando Separador-->
    <div class="row">
        <div class="col-md-12 text-center m-t-15">   
            <div class="form-group">                
                <button id="btnConfirmarSeguimientoSolicitud" type="button" class="btn btn-sm btn-success m-r-5" >Generar Ticket</button>
                <?php
                if (!empty($datos['Folio'])) {
                    if (!empty($usuarioApiKey)) {
                        ?>
                        <button id="btnRechazarSolicitud" type="button" class="btn btn-sm btn-warning m-r-5" >Reasignar Folio SD</button>
                        <button id="btnRechazarSolicitudSD" type="button" class="btn btn-sm btn-danger m-r-5" >Rechazar Folio SD</button>
                        <?php
                    }
                }
                ?>
                <button id="btnCancelarSeguimiento" type="button" class="btn btn-sm btn-default m-r-5" >Cerrar Ventana</button>
            </div>    
        </div>
    </div>    

</div>
<!--Finalizando seccion para seguimiento de la solicitud-->

<!--Empezando la seccion de para reasignar de departamento-->
<div id="seccionReasignar" class="hidden">
    <form id="formReasignarSolicitud" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12 text-center">          
                <div class="form-group">
                    <p>Si requiere reasignar la solicitud es necesario que indique a que área y departamento se asignara.</p>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="reasignar"> Area: </label>
                    <select id="selectReasignarArea" class="form-control" name="reasignarArea" style="width: 100%" data-parsley-required="true" >
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($areas as $value) {
                            echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                        }
                        ?>
                    </select>                            
                </div>    
            </div>                 
            <div class="col-md-6">          
                <div class="form-group">
                    <label for="reasignar"> Departamento: </label>
                    <select id="selectReasignarDepartamento" class="form-control" name="reasignarDepartamento" style="width: 100%" data-parsley-required="true" >
                        <option value="">Seleccionar</option>
                    </select>                            
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <label for="reasignar"> Indique la causa por la que se reasigna la solictud: </label>
                <textarea id="textareaDescripcionReasignacion" class="form-control" placeholder="Ingresa tus observaciones aqui ...." rows="3" data-parsley-required="true"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center m-t-15">          
                <div class="form-group">
                    <button id="btnConfirmarReasignar" type="button" class="btn btn-sm btn-success m-r-5" >Aceptar</button>
                    <button id="btnCancelarReasignar" type="button" class="btn btn-sm btn-danger m-r-5" >Cancelar</button>
                </div>    
            </div>
        </div>
    </form>
</div>
<!--Finalizando la seccion de para reasignar de departamento-->

<!--Empezando la seccion de para rechazar solicitud-->
<div id="seccionRechazar" class="hidden">
    <form id="formRechazarSolicitud" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">        
        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="tecnico SD"> Tecnico Service Desk * </label>
                    <select id="selectTecnicosSD" class="form-control" name="tecnicoSD" style="width: 100%" data-parsley-required="true">
                        <option value="">Seleccionar</option>
                    </select>                            
                </div>        
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="reasignar"> Indique la causa por la que rechaza el folio de Service Desk: </label>
                    <textarea id="textareaDescripcionRechazada" class="form-control" placeholder="Ingresa tus observaciones aqui ...." rows="3" data-parsley-required="true"></textarea>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">          
                <div class="form-group">
                    <button id="btnConfirmarRechazo" type="button" class="btn btn-sm btn-success m-r-5" >Aceptar</button>
                    <button id="btnCancelarRechazo" type="button" class="btn btn-sm btn-danger m-r-5" >Cancelar</button>
                </div>    
            </div>
        </div>
    </form>
</div>
<!--Finalizando la seccion de para rechazar solicitud-->
