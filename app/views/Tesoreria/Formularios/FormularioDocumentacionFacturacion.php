<div id="formularioFacturacionTesoreria" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
    <!--Empezando Pestañas para definir la seccion-->
    <div class="panel-heading p-0">
        <div class="btn-group pull-right" data-toggle="buttons">

        </div>
        <div class="panel-heading-btn m-r-10 m-t-10">
            <!-- Single button -->
            <label id="btnValidarFacturacionTesoreria" class="btn btn-warning btn-xs hidden">
                <i class="fa fa-gavel"></i> Validar
            </label>  
            <label id="btnRegresarFacturacionTesoreria" class="btn btn-success btn-xs">
                <i class="fa fa-reply"></i> Regresar
            </label>                                    
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <!-- begin nav-tabs -->
        <div class="tab-overflow">
            <ul class="nav nav-tabs nav-tabs-inverse">
                <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                <li class="active"><a href="#Documentacion" data-toggle="tab">Documentación</a></li>
                <li class=""><a href="#Validacion" data-toggle="tab">Validación</a></li>
                <li class=""><a href="#Referencia" data-toggle="tab">Referencia de Pago</a></li>
                <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
            </ul>
        </div>
    </div>
    <!--Finalizando Pestañas para definir la seccion-->

    <!--Empezando contenido de la informacion del servicio-->
    <div class="tab-content">

        <!--Empezando la seccion Documentacion-->
        <div class="tab-pane fade active in" id="Documentacion">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="m-t-10">Documentación Archivos</h3>
                    </div>
                </div>

                <!-- Inicia Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
                <!-- Finalizando Separador-->


                <!--Empezando inputs de PDF y XML-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Archivo PDF</label>
                            <input id="inputPDFFacturacion" name="PDFFacturacion[]" type="file" multiple>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Archivo XML</label>
                            <input id="inputXMLFacturacion" name="XMLFacturacion[]" type="file" multiple>
                        </div>
                    </div>
                </div>
                <!--Finalizando-->
            </div>
        </div>
        <!--Finalizandor-->

        <!--Empezando la seccion Validacion-->
        <div class="tab-pane fade " id="Validacion">            
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="m-t-10">Validación Supervisor y Coordinador</h3>
                    </div>
                </div>


                <!-- Inicia Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
                <!-- Finalizando Separador-->

                <!-- Inicia Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if (!empty($datosFacturacionAsociados)) {
                            if ($datosFacturacionAsociados['FechaValidacionSup'] !== NULL) {
                                $tituloValidacionSupervisor = 'Validación Correcta';
                                $mostrarNotaSupervisor = '';
                                $iconoSupervisor = 'fa-check-square text-success';
                                $nombreSupervisor = $NombreSupervisor[0]['NombreUsuario'];
                            } else {
                                $tituloValidacionSupervisor = 'Falta Validación Supervisor';
                                $mostrarNotaSupervisor = 'hidden';
                                $iconoSupervisor = 'fa-minus-square text-warning';
                            }
                        } else {
                            $tituloValidacionSupervisor = 'Falta Validación Supervisor';
                            $mostrarNotaSupervisor = 'hidden';
                            $iconoSupervisor = 'fa-minus-square text-warning';
                        }
                        ?>
                        <h3>
                            <i class="fa <?php echo $iconoSupervisor; ?> fa-2x"></i>
                            <?php echo $tituloValidacionSupervisor; ?>
                        </h3>
                        <div class="note note-success <?php echo $mostrarNotaSupervisor; ?>">
                            <h4><?php echo $nombreSupervisor; ?> </h4>
                        </div>
                    </div>
                </div>
                <!-- Finalizando Separador-->

                <!-- Inicia Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if (!empty($datosFacturacionAsociados)) {
                            if ($datosFacturacionAsociados['FechaValidacionCoord'] !== NULL) {
                                $tituloValidacionCoordinador = 'Validación Correcta Coordinador';
                                $mostrarNotaCoordinador = '';
                                $iconoCoordinador = 'fa-check-square text-success';
                                $nombreCoordinador = $NombreCoordinador[0]['NombreUsuario'];
                            } else {
                                $tituloValidacionCoordinador = 'Falta Validación Coordinador';
                                $mostrarNotaCoordinador = 'hidden';
                                $iconoCoordinador = 'fa-minus-square text-warning';
                            }
                        } else {
                            $tituloValidacionCoordinador = 'Falta Validación Coordinador';
                            $mostrarNotaCoordinador = 'hidden';
                            $iconoCoordinador = 'fa-minus-square text-warning';
                        }
                        ?>
                        <h3>
                            <i class="fa <?php echo $iconoCoordinador; ?>  fa-2x"></i>
                            <?php echo $tituloValidacionCoordinador; ?>
                        </h3>
                        <div class="note note-success <?php echo $mostrarNotaCoordinador; ?>">
                            <h4><?php echo $nombreCoordinador; ?> </h4>
                        </div>
                    </div>
                </div>
                <!-- Finalizando Separador-->

                <!-- Inicia Separador-->
                <?php
                if (!empty($datosFacturacionAsociados)) {
                    if ($datosFacturacionAsociados['Rechazo'] !== NULL) {
                        ?>

                        <div class="row">
                            <div class="col-md-12">
                                <h3>
                                    <i class="fa fa-exclamation-triangle text-danger fa-2x"></i>
                                    Factura Rechazada
                                </h3>
                                <div class="note note-danger">
                                    <h4><?php echo $NombreRechaza[0]['NombreUsuario'];; ?> </h4>
                                    <p><?php echo $datosFacturacionAsociados['Rechazo'] ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                <!-- Finalizando Separador-->

            </div>
        </div>
        <!--Finalizando-->

        <!--Empezando la seccion Referencia-->
        <div class="tab-pane fade " id="Referencia">            
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="m-t-10">Referencia de Pago</h3>
                    </div>
                </div>

                <!-- Inicia Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
                <!-- Finalizando Separador-->

                <!-- Inicia Referencia de Pago-->
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if (!empty($datosFacturacionAsociados)) {
                            if ($datosFacturacionAsociados['FechaPago'] !== NULL) {
                                $tituloValidacionTesoreria = 'Pago Correcto';
                                $mostrarNotaTesoreria = '';
                                $iconoTesoreria = 'fa-check-square text-success';
                                $nombreTesoreria = $NombreTesoreria[0]['NombreUsuario'];
                            } else {
                                $tituloValidacionTesoreria = 'Falta Referencia de Pago';
                                $mostrarNotaTesoreria = 'hidden';
                                $iconoTesoreria = 'fa-minus-square text-warning';
                            }
                        } else {
                            $tituloValidacionTesoreria = 'Falta Referencia de Pago';
                            $mostrarNotaTesoreria = 'hidden';
                            $iconoTesoreria = 'fa-minus-square text-warning';
                        }
                        ?>
                        <h3>
                            <i class="fa <?php echo $iconoTesoreria; ?> fa-2x"></i>
                            <?php echo $tituloValidacionTesoreria; ?>
                        </h3>
                        <div class="note note-success <?php echo $mostrarNotaTesoreria; ?>">
                            <h4><?php echo $nombreTesoreria; ?> </h4>
                            <div id="minutaOriginal" class="row m-t-10"> 
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h5>Evidencia(s)</h5>
                                        <div class="evidenciasMinuta">
                                            <?php
                                            $arrayDocumentosAsociados = explode(',', $datosFacturacionAsociados['EvidenciaPago']);
                                            foreach ($arrayDocumentosAsociados as $item) {
                                                echo '<div class = "evidencia">';
                                                echo '<a href = "' . $item . '" target="_blank">';
                                                echo '<img src = "\assets\img\Iconos\jpg_icon.png" alt = "Lights" style = "width:100%">';
                                                echo '</a>';
                                                $posicion = strrpos($item, '/');
                                                echo '<br>';
                                                echo '<h2 class="nombreArchivo">' . substr($item, $posicion + 1) . '</h2>';
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Finalizando-->

            </div>
        </div>
        <!--Finalizando-->

        <!--Finalizando contenido de la informacion del servicio-->
    </div>
</div>