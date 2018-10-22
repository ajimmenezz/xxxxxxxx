<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h4>Selección de equipo</h4>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 underline m-b-10"></div>
</div>
<div id="errorRevisionTecnica"></div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12 form-group">

        <label> Área y Punto *</label>
        <select id="selectAreaPunto" class="form-control" style="width: 100%" data-parsley-required="true">
            <option value="">Seleccionar</option>
            <?php
            if (isset($areaPunto) && count($areaPunto) > 0) {
                foreach ($areaPunto as $key => $value) {
                    echo '<option value="1" data-area ="' . $value['IdArea'] . '" data-punto="' . $value['Punto'] . '">' . $value['Area'] . ' ' . $value['Punto'] . '</option>';
                }
            }
            ?>
        </select>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label> Equipo *</label>
        <select id="selectEquipo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
            <option value="">Seleccionar</option>
        </select>
    </div>
</div>
<div id="divDiagnosticoEquipo" style="display: none">
    <div class="row m-t-10">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h4>Diagnóstico de Equipo</h4>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 underline m-b-10"></div>
    </div>

    <ul id="clasificacionesFalla" class="nav nav-pills">
        <!--<li class="active"><a href="#reporte-falso" data-toggle="tab">Reporte en Falso</a></li>-->
        <li><a href="#impericia" data-toggle="tab">Impericia (Mal uso)</a></li>
        <li><a href="#falla-equipo" data-toggle="tab">Falla de Equipo</a></li>
        <li><a href="#falla-componente" data-toggle="tab">Falla de Componente</a></li>
        <li><a href="#reporte-multimedia" data-toggle="tab">Reporte Multimedia</a></li>
    </ul>
    <div id="contentClasificacionesFalla" class="tab-content">
        <div class="tab-pane fade" id="impericia">
            <div class="well">

                <div class="mensajeImpericia row hidden">
                    <div class="col-md-12 m-t-20">
                        <div class="alert alert-warning fade in m-b-15">                            
                            No existen registros de Impericia para el equipo en el Catálogo de Tipo de Falla.                             
                        </div>                        
                    </div>
                </div>

                <!--Empezando Tipo de Falla y Falla-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selectImpericiaTipoFallaEquipoCorrectivo">Tipo de Falla *</label>
                            <select id="selectImpericiaTipoFallaEquipoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar</option>
                                <?php
                                if ($informacion['tiposFallasEquiposImpericia'] !== FALSE) {
                                    if ($informacion['tiposFallasEquiposImpericia'] !== NULL) {
                                        foreach ($informacion['tiposFallasEquiposImpericia'] as $item) {
                                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selectImpericiaFallaDiagnosticoCorrectivo">Falla *</label>
                            <select id="selectImpericiaFallaDiagnosticoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando Evidencias Impericia-->
                <div class="row">
                    <div class="col-md-12">                                    
                        <div class="form-group">
                            <label for="evidenciasImpericiaCorrectivo">Evidencias de la Impericia *</label>
                            <input id="evidenciasImpericiaCorrectivo"  name="evidenciasImpericiaCorrectivo[]" type="file" multiple/>
                        </div>
                    </div>
                </div>
                <!--Finalizando-->

                <div class="row m-t-10">
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorFormularioImpericiaCorrectivo"></div>
                    </div>
                    <!--Finalizando Error-->

                    <div class="col-md-12">
                        <div class="form-group text-center"><br>
                            <a id="btnGuardarImpericiaChecklist" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Diagnóstico</a>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="falla-equipo">
            <div class="well">

                <div class="mensajeTipoFalla row hidden">
                    <div class="col-md-12 m-t-20">
                        <div class="alert alert-warning fade in m-b-15">                            
                            No existe registros en Catálogo Tipos de Fallas.                             
                        </div>                        
                    </div>
                </div>

                <!--Empezando Tipo de Falla y Falla-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selectTipoFallaEquipoCorrectivo">Tipo de Falla *</label>
                            <select id="selectTipoFallaEquipoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar</option>
                                <?php
                                if ($informacion['tiposFallasEquipos'] !== FALSE) {
                                    if ($informacion['tiposFallasEquipos'] !== NULL) {
                                        foreach ($informacion['tiposFallasEquipos'] as $item) {
                                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selectFallaDiagnosticoCorrectivo">Falla *</label>
                            <select id="selectFallaDiagnosticoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando Evidencias Fallas de Equipo-->
                <div class="row">
                    <div class="col-md-12">                                    
                        <div class="form-group">
                            <label for="evidenciasFallaEquipoCorrectivo">Evidencias de la Falla *</label>
                            <input id="evidenciasFallaEquipoCorrectivo"  name="evidenciasFallaEquipoCorrectivo[]" type="file" multiple/>
                        </div>
                    </div>
                </div>
                <!--Finalizando-->

                <div class="row m-t-10">
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorFormularioFallaEquipoCorrectivo"></div>
                    </div>
                    <!--Finalizando Error-->
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <br>
                            <a id="btnGuardarFallaEquipoChecklist" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Diagnóstico</a>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="falla-componente">
            <div class="well">

                <div class="mensajeRefaccion row hidden">
                    <div class="col-md-12 m-t-20">
                        <div class="alert alert-warning fade in m-b-15">                            
                            No existe registros en Catálogo Refacciones.                             
                        </div>                        
                    </div>
                </div>

                <!--Empezando Tipo de Falla y Falla-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selectComponenteDiagnosticoCorrectivo">Componente *</label>
                            <select id="selectComponenteDiagnosticoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar</option>
                                <?php
                                if ($informacion['catalogoComponentesEquipos'] !== FALSE) {
                                    if ($informacion['catalogoComponentesEquipos'] !== NULL) {
                                        foreach ($informacion['catalogoComponentesEquipos'] as $item) {
                                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selectTipoFallaComponenteCorrectivo">Tipo de Falla *</label>
                            <select id="selectTipoFallaComponenteCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selectFallaComponenteDiagnosticoCorrectivo">Falla *</label>
                            <select id="selectFallaComponenteDiagnosticoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--Finalizando-->

                <!-- Empezando Evidencias Falla de Componente -->
                <div class="row">
                    <div class="col-md-12">                                    
                        <div class="form-group">
                            <label for="evidenciasFallaComponenteCorrectivo">Evidencias de la Falla *</label>
                            <input id="evidenciasFallaComponenteCorrectivo"  name="evidenciasFallaComponenteCorrectivo[]" type="file" multiple/>
                        </div>
                    </div>
                </div>
                <!-- Finalizando -->

                <div class="row m-t-10">
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorFormularioFallaComponenteCorrectivo"></div>
                    </div>
                    <!--Finalizando Error-->
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <br>
                            <a id="btnGuardarFallaComponenteChecklist" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Diagnóstico</a>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade in" id="reporte-multimedia">
            <div class="well">

                <!-- Empezando Evidencias Reporte Multimedia -->
                <div class="row">
                    <div class="col-md-12">                                    
                        <div class="form-group">
                            <label for="evidenciasReporteMultimediaCorrectivo">Evidencias *</label>
                            <input id="evidenciasReporteMultimediaCorrectivo"  name="evidenciasReporteMultimediaCorrectivo[]" type="file" multiple/>
                        </div>
                    </div>
                </div>
                <!--Finalizando-->

                <div class="row m-t-10">
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorFormularioReporteMultimediaCorrectivo"></div>
                    </div>
                    <!--Finalizando Error-->
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <br>
                            <a id="btnGuardarReporteMultimediaChecklist" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar y Concluir Servicio</a>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h4>Fallas Ténicas Detectadas</h4>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 underline m-b-10"></div>
</div>
<div class="row m-t-15">
<div class="row" id="mensajeRevisionTecnica"></div>
    <div class="table-responsive">
        <table id="tablaFallasTecnicas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
            <thead>
                <tr>
                    <th class="never">Id</th> 
                    <th class="all">Area y Punto</th> 
                    <th class="all">Equipo</th>
                    <th class="all">Serie</th>
                    <th class="all">Componente</th>
                    <th class="all">Tipo Diágnostico</th>
                    <th class="all">Falla</th>
                    <th class="all">Fecha</th>
                </tr>
            </thead>
            <tbody>                                    
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modalEditRevisionChecklist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div id="error-in-modal"></div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardarCambios" class="btn btn-primary" style="display: none">Guardar</button>
            </div>
        </div>
    </div>
</div>