<!--Empezando seccion para seguimiento de la solicitud-->
<div id="seccionSeguimiento" >       
    <input type="hidden" id="creator-sd" value="<?php echo (isset($datosSD->CREATEDBY)) ? $datosSD->CREATEDBY : ''; ?>" />
    <input type="hidden" id="requester-sd" value="<?php echo (isset($datosSD->REQUESTER)) ? $datosSD->REQUESTER : ''; ?>" />
    <input type="hidden" id="id-sucursal-interna" value="<?php echo $datos['IdSucursal']; ?>" />
    <div class="row">
        <div class="col-sm-7 col-md-8">          
            <div class="form-group">
                <label for="solicitaSolictud"> Solicita: <strong id="solicita"><?php echo $datos['NombreSolicita'] . ' [' . $datos['DepartamentoSolicitante'] . ']'; ?></strong></label>
            </div>    
        </div> 
        <div class="col-sm-5 col-md-4">          
            <div class="form-group">
                <label for="fechasolicitud"> Fecha: <strong id="fechaSolicitud"><?php echo $datos['Fecha']; ?></strong></label>                        
            </div>    
        </div>
    </div>
    <div class="row">
        <?php if (!empty($datos['Ticket'])) { ?>
            <div class = "col-md-6">
                <div class = "form-group">
                    <label for = "ticket"> Ticket: <strong > <?php echo $datos['Ticket']; ?></strong></label>
                </div>
            </div>
        <?php } if (!empty($datos['Autoriza'])) { ?>
            <div class = "col-md-6">
                <div class = "form-group">
                    <label for = "autorizado"> Autorizado por: <strong> <?php echo $datos['Autoriza']; ?></strong></label>                        
                </div>    
            </div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-md-12">          
            <div class="form-group">
                <label for="asunto"> Asunto: </label>
                <p><strong><?php echo $datos['detalles'][0]['Asunto']; ?></strong></p>
            </div>    
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">          
            <div class="form-group">
                <label for="descripcion"> Descripción: </label>
                <p><strong><?php echo $datos['detalles'][0]['Descripcion']; ?></strong></p>
            </div>    
        </div>
    </div>

    <?php if (!empty($datos['detalles'][0]['Evidencias'])) { ?>
        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="evidencia"> Evidencia: </label>
                    <div class="evidenciasSolicitud">
                        <?php
                        $archivos = array('pdf', 'doc', 'docx', 'xls', 'xlsx');
                        $evidencias = explode(',', $datos['detalles'][0]['Evidencias']);
                        foreach ($evidencias as $key => $value) {
                            $posicionNombre = strrpos($value, '/');
                            $nombre = str_replace('_', ' ', substr($value, $posicionNombre + 1));
                            $posicionExtencion = strrpos($value, '.');
                            $extencion = substr($value, $posicionExtencion + 1);
                            if (in_array($extencion, $archivos)) {
                                if ($extencion === 'pdf') {
                                    echo '<div class="evidencia"><a href="' . $value . '" target="_blank"><img src ="/assets/img/Iconos/pdf_icon.png" /></a><p>' . $nombre . '</p></div>';
                                } else if ($extencion === 'doc' || $extencion === 'docx') {
                                    echo '<div class="evidencia"><a href="' . $value . '" target="_blank"><img src ="/assets/img/Iconos/word_icon.png" /></a><p>' . $nombre . '</p></div>';
                                } else if ($extencion === 'xls' || $extencion === 'xlsx') {
                                    echo '<div class="evidencia"><a href="' . $value . '" target="_blank"><img src ="/assets/img/Iconos/excel_icon.png" /></a><p>' . $nombre . '</p></div>';
                                }
                            } else {
                                echo '<div class="evidencia"><a href="' . $value . '" data-lightbox="evidencias"><img src ="' . $value . '" /></a><p>' . $nombre . '</p></div>';
                            }
                        }
                        ?>
                    </div>
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
        <div class="col-md-6 col-xs-6">
            <h4>Servicios</h4>
        </div>
        <div class="col-md-6 col-xs-6">
            <div class="form-group text-right">
                <a href="javascript:;" class="btn btn-warning btn-sm m-t-10 hidden" id="btnAtenderSolicitud"><i class="fa fa-check-square-o"></i> Atender Solicitud</a>
            </div>
        </div>
    </div>
    <!--Finalizando titulo de servicios-->

    <!--Empezando formulario para los servicios-->
    <form id="formAgregarSservicio" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">          
                <div class="form-group">
                    <label for="servicioCliente"> Cliente * </label>
                    <select id="selectCliente" class="form-control" name="clienteServicio" style="width: 100%" >
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
                            $selected = ($datos['IdSucursal'] == $value['Id']) ? 'selected' : '';
                            echo '<option data-id="' . $datos['IdSucursal'] . '" value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
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
                            echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                        }
                        ?>
                    </select>                            
                </div>    
            </div>
        </div>
        <div class="row">
            <div id="content-selectAtiende" class="col-md-12">          
                <div class="form-group">
                    <label for="atiendeServicio"> Atiende * </label>
                    <select id="selectAtiendeServicio" class="form-control" name="atiendeServicio" style="width: 100%" data-parsley-required="true" >
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($atiende as $key => $value) {
                            echo '<option value="' . $value['IdUsuario'] . '">' . $value['Nombre'] . '</option>';
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
                    <input value="<?php echo $datos['detalles'][0]['Descripcion']; ?>" id="inputDescripcionServicio" type="text"  class="form-control" placeholder="Describicion breve .." data-parsley-required="true"/>
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
    <!--Empezando tabla de servicio-->
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
                <?php if ($datos['TipoSolicitud'] !== '4') { ?>
                    <button id="btnReasignarSolicitud" type="button" class="btn btn-sm btn-primary m-r-5" >Reasignar</button>
                <?php } ?>
                <button id="btnConfirmarSeguimientoSolicitud" type="button" class="btn btn-sm btn-success m-r-5" >Generar Ticket</button>
                <?php if ($datos['TipoSolicitud'] !== '4') { ?>
                    <button id="btnRechazarSolicitud" type="button" class="btn btn-sm btn-danger m-r-5" >Rechazar Solicitud</button>
                <?php } ?>
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

        <!--Empezando select de personal--> 
        <div class="row m-t-10">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="reasignar">Personal</label>
                    <select id="selectReasignarParsonal" class="form-control" name="reasignarPersonal" style="width: 100%">
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($usuarios as $item) {
                            echo '<option value="' . $item['IdPerfil'] . '">' . $item['Nombre'] . ' ' . $item['ApPaterno'] . ' ' . $item['ApMaterno'] . '</option>';
                        }
                        ?>
                    </select>                            
                </div>
            </div>                                                
        </div>
        <!--Finalizando-->

        <div class="row">
            <div class="col-md-6">          
                <div class="form-group">
                    <label for="reasignar"> Area *</label>
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
                    <label for="reasignar"> Departamento *</label>
                    <select id="selectReasignarDepartamento" class="form-control" name="reasignarDepartamento" style="width: 100%" data-parsley-required="true" disabled>
                        <option value="">Seleccionar</option>
                    </select>                            
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <label for="reasignar"> Indique la causa por la que se reasigna la solictud *</label>
                <textarea id="textareaDescripcionReasignacion" class="form-control" placeholder="Ingresa tus observaciones aqui ...." rows="3" data-parsley-required="true"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center m-t-15">          
                <div class="form-group">
                    <button id="btnConfirmarReasignar" type="button" class="btn btn-sm btn-success m-r-5" ><i class="fa fa-check"></i> Aceptar</button>
                    <button id="btnCancelarReasignar" type="button" class="btn btn-sm btn-danger m-r-5" ><i class="fa fa-times"></i> Cancelar</button>
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
                    <label for="reasignar"> Indique la causa por la que rechaza la solictud: </label>
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
