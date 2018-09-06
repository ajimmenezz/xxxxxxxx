<!--Empezando seccion para seguimiento de la solicitud-->

<div id="seccionSeguimiento" >    
    <div class="row">
        <div class="col-sm-7 col-md-8">          
            <div class="form-group">
                <label for="solicitaSolictud"> Solicita: <strong id="solicita"></strong></label>
            </div>    
        </div> 
        <div class="col-sm-5 col-md-4">          
            <div class="form-group">
                <label for="fechasolicitud"> Fecha: <strong id="fechaSolicitud"></strong></label>                        
            </div>    
        </div>
    </div>
    <div class="row">
        <?php if (!empty($datos['Ticket'])) { ?>
            <div class = "col-md-4">
                <div class = "form-group">
                    <label for = "ticket"> Ticket: </label>
                    <p><strong > <?php echo $datos['Ticket']; ?></strong></p>
                </div>
            </div>
        <?php } if (!empty($datos['detalles'][0]['NombreProyecto'])) { ?>
            <div class = "col-md-4">
                <div class = "form-group">
                    <label for = "autorizado"> Nombre del Proyecto: </label>
                    <p><strong> <?php echo $datos['detalles'][0]['NombreProyecto']; ?></strong></p>
                </div>    
            </div>        
        <?php } if (!empty($datos['Autoriza'])) { ?>
            <div class = "col-md-4">
                <div class = "form-group">
                    <label for = "autorizado"> Autorizado por: </label>
                    <p><strong> <?php echo $datos['Autoriza']; ?></strong></p>
                </div>    
            </div>
        <?php } ?>
    </div>

    <!--Empezando Separador-->
    <div class="row">
        <div class="col-md-12">
            <div class="underline m-t-15"></div>
        </div>
    </div>
    <!--Finalizando Separador-->

    <div class="row">
        <div class="col-md-12">          
            <div class="form-group">
                <label for="descripcion"> <h4>Material Solicitado</h4> </label>
                <table id="data-table-materiales" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%" data-editar="true">
                    <thead>
                        <tr>
                            <th class="none">Id</th>
                            <th class="all">Material</th>
                            <th class="all">Numero de parte</th>
                            <th class="all">Cantidad</th>                                          
                            <th class="all">Estatus</th>                                          
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($datos['detalles'])) {
                            foreach ($datos['detalles'] as $value) {
                                echo '<tr>';
                                echo '<td>' . $value['IdSolicitud'] . '</td>';
                                echo '<td>' . $value['Nombre'] . '</td>';
                                echo '<td>' . $value['NumeroParte'] . '</td>';
                                echo '<td>' . $value['Cantidad'] . '</td>';
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
        <div class="col-md-12">          
            <div class="form-group">
                <h4>Servicios</h4>
            </div>    
        </div>
    </div>
    <!--Empezando titulo de servicios-->
    <!--Empezando formulario para los servicios-->
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
    <form id="formAgregarSservicio" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
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
            <div id="content-selectClasificacion" class="col-md-6 hidden">
                <div class="form-group">
                    <label for="clasificacion"> Clasificacion * </label>
                    <select id="selectClasificacion" class="form-control" name="clasificacion" style="width: 100%"  >
                        <option value="">Seleccionar</option>
                        <?php
                        foreach ($clasificacion as $key => $value) {
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
                    <label for="servicioDepartamento"> Descripción *</label>
                    <input id="inputDescripcionServicio" type="text"  class="form-control" placeholder="Describicion breve .." data-parsley-required="true"/>
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
                            <th>Atiende</th>
                            <th>Clasificación</th>
                            <th>Descripción</th>
                            <th class="none">Idservicio</th>
                            <th class="none">IdAtiende</th>
                            <th class="none">IdClasificacion</th>
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
                <button id="btnConfirmarSeguimientoSolicitud" type="button" class="btn btn-sm btn-success m-r-5" >Generar Seguimiento</button>                
                <button id="btnCancelarSeguimiento" type="button" class="btn btn-sm btn-default m-r-5" >Cerrar Ventana</button>
            </div>    
        </div>
    </div>    

</div>
<!--Finalizando seccion para seguimiento de la solicitud-->

