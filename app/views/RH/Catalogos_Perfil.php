<div id="seccionCatalogosPerfil" class="content">

    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogos <small> Perfil</small></h1>
    <!-- Finalizando titulo de la pagina -->

    <div id="panel-catalogos-perfil" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">

        <!--Empezando Pestañas para definir la seccion-->
        <div class="panel-heading p-0">
            <div class="btn-group pull-right" data-toggle="buttons">

            </div>
            <div class="panel-heading-btn m-r-10 m-t-10">                                                 
            </div>
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse">
                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                    <li class="active"><a href="#DocumentosRecibidos" data-toggle="tab">Documentos Recibidos</a></li>
                    <li class=""><a href="#EstadoCivil" data-toggle="tab">Estado Civil</a></li>
                    <li class=""><a href="#Idiomas" data-toggle="tab">Idiomas</a></li>
                    <li class=""><a href="#NivelesEstudio" data-toggle="tab">Niveles de Estudio</a></li>
                    <li class=""><a href="#NivelesHabilidad" data-toggle="tab">Niveles de Habilidad</a></li>
                    <li class=""><a href="#Sexos" data-toggle="tab">Sexos</a></li>
                    <li class=""><a href="#Sistemas" data-toggle="tab">Sistemas</a></li>
                    <li class=""><a href="#Software" data-toggle="tab">Software</a></li>
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
        <!--Finalizando Pestañas para definir la seccion-->

        <!--Empezando error--> 
        <div class="row m-t-10">                       
            <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-1 col-xs-10">
                <div id="errorMessage"></div>
            </div>
        </div>
        <!--Finalizando Error-->

        <!--Empezando contenido de los catalogos-->
        <div class="tab-content">

            <!--Empezando la seccion Documentos Recibidos-->
            <div class="tab-pane fade active in" id="DocumentosRecibidos">
                <div class="panel-body">                                        
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6 col-sm-offset-6 col-sm-6 col-xs-offset-0 col-xs-12">
                            <div class="input-group">
                                <input type="text" id="txtNuevoDocumentoRecibido" class="form-control" placeholder="Nuevo Documento Recibido">
                                <span role="button" id="btnGuardarDocumentoRecibido" class="input-group-addon bg-aqua"><i class="fa fa-plus text-white"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h4>Lista de Documentos Recibidos</h4>
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-documentos-recibido" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="none">Id</th>
                                            <th class="none">Flag</th>
                                            <th class="all">Documento Recibido</th>
                                            <th class="all" style="width: 25%">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($datos['DocumentosRecibidos']) && !empty($datos['DocumentosRecibidos'])) {
                                            foreach ($datos['DocumentosRecibidos'] as $key => $value) {
                                                echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Flag'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td>" . $value['Estatus'] . "</td>"
                                                . "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Empezando la seccion Documentos Recibidos -->

            <!-- Empezando la seccion Estado Civil -->
            <div class="tab-pane fade" id="EstadoCivil">
                <div class="panel-body">                                        
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6 col-sm-offset-6 col-sm-6 col-xs-offset-0 col-xs-12">
                            <div class="input-group">
                                <input type="text" id="txtNuevoEstadoCivil" class="form-control" placeholder="Nuevo Estado Civil">
                                <span role="button" id="btnGuardarEstadoCivil" class="input-group-addon bg-aqua"><i class="fa fa-plus text-white"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h4>Lista de Estado Civil</h4>
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-estado-civil" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="none">Id</th>
                                            <th class="none">Flag</th>
                                            <th class="all">Estado Civil</th>
                                            <th class="all" style="width: 25%">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($datos['EstadoCivil']) && !empty($datos['EstadoCivil'])) {
                                            foreach ($datos['EstadoCivil'] as $key => $value) {
                                                echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Flag'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td>" . $value['Estatus'] . "</td>"
                                                . "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Empezando la seccion Estado Civil -->

            <!-- Empezando la seccion Idiomas -->
            <div class="tab-pane fade" id="Idiomas">
                <div class="panel-body">                                        
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6 col-sm-offset-6 col-sm-6 col-xs-offset-0 col-xs-12">
                            <div class="input-group">
                                <input type="text" id="txtNuevoIdioma" class="form-control" placeholder="Nuevo Idioma">
                                <span role="button" id="btnGuardarIdioma" class="input-group-addon bg-aqua"><i class="fa fa-plus text-white"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h4>Lista de Idiomas</h4>
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-idiomas" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="none">Id</th>
                                            <th class="none">Flag</th>
                                            <th class="all">Idioma</th>
                                            <th class="all" style="width: 25%">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($datos['Idiomas']) && !empty($datos['Idiomas'])) {
                                            foreach ($datos['Idiomas'] as $key => $value) {
                                                echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Flag'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td>" . $value['Estatus'] . "</td>"
                                                . "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Empezando la seccion Idiomas -->        

            <!-- Empezando la seccion Niveles de Estudio -->
            <div class="tab-pane fade" id="NivelesEstudio">
                <div class="panel-body">                                        
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6 col-sm-offset-6 col-sm-6 col-xs-offset-0 col-xs-12">
                            <div class="input-group">
                                <input type="text" id="txtNuevoNivelEstudio" class="form-control" placeholder="Nuevo Nivel de estudio">
                                <span role="button" id="btnGuardarNivelEstudio" class="input-group-addon bg-aqua"><i class="fa fa-plus text-white"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h4>Lista de Niveles de Estudio</h4>
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-niveles-estudio" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="none">Id</th>
                                            <th class="none">Flag</th>
                                            <th class="all">Nivel de Estudio</th>
                                            <th class="all" style="width: 25%">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($datos['NivelesEstudio']) && !empty($datos['NivelesEstudio'])) {
                                            foreach ($datos['NivelesEstudio'] as $key => $value) {
                                                echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Flag'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td>" . $value['Estatus'] . "</td>"
                                                . "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Empezando la seccion Niveles de Estudio -->

            <!-- Empezando la seccion Niveles de Habilidad -->
            <div class="tab-pane fade" id="NivelesHabilidad">
                <div class="panel-body">                                        
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6 col-sm-offset-6 col-sm-6 col-xs-offset-0 col-xs-12">
                            <div class="input-group">
                                <input type="text" id="txtNuevoNivelHabilidad" class="form-control" placeholder="Nuevo Nivel de Habilidad">
                                <span role="button" id="btnGuardarNivelHabilidad" class="input-group-addon bg-aqua"><i class="fa fa-plus text-white"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h4>Lista de Niveles de Habilidad</h4>
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-niveles-habilidad" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="none">Id</th>
                                            <th class="none">Flag</th>
                                            <th class="all">Nivel de Habilidad</th>
                                            <th class="all" style="width: 25%">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($datos['NivelesHabilidad']) && !empty($datos['NivelesHabilidad'])) {
                                            foreach ($datos['NivelesHabilidad'] as $key => $value) {
                                                echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Flag'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td>" . $value['Estatus'] . "</td>"
                                                . "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Empezando la seccion Niveles de Habilidad -->

            <!-- Empezando la seccion Sexos -->
            <div class="tab-pane fade" id="Sexos">
                <div class="panel-body">                                        
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6 col-sm-offset-6 col-sm-6 col-xs-offset-0 col-xs-12">
                            <div class="input-group">
                                <input type="text" id="txtNuevoSexo" class="form-control" placeholder="Nuevo Sexo">
                                <span role="button" id="btnGuardarSexo" class="input-group-addon bg-aqua"><i class="fa fa-plus text-white"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h4>Lista de Sexos</h4>
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-sexos" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="none">Id</th>
                                            <th class="none">Flag</th>
                                            <th class="all">Sexo</th>
                                            <th class="all" style="width: 25%">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($datos['Sexos']) && !empty($datos['Sexos'])) {
                                            foreach ($datos['Sexos'] as $key => $value) {
                                                echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Flag'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td>" . $value['Estatus'] . "</td>"
                                                . "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Empezando la seccion Sexos -->

            <!-- Empezando la seccion de Sistemas -->
            <div class="tab-pane fade" id="Sistemas">
                <div class="panel-body">                                        
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6 col-sm-offset-6 col-sm-6 col-xs-offset-0 col-xs-12">
                            <div class="input-group">
                                <input type="text" id="txtNuevoSistema" class="form-control" placeholder="Nuevo Sistema">
                                <span role="button" id="btnGuardarSistema" class="input-group-addon bg-aqua"><i class="fa fa-plus text-white"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h4>Lista de Sistemas</h4>
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-sistemas" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="none">Id</th>
                                            <th class="none">Flag</th>
                                            <th class="all">Sistema</th>
                                            <th class="all" style="width: 25%">Estatus</th>                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($datos['Sistemas']) && !empty($datos['Sistemas'])) {
                                            foreach ($datos['Sistemas'] as $key => $value) {
                                                echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Flag'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td>" . $value['Estatus'] . "</td>"
                                                . "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Empezando la seccion de Sistemas -->

            <!-- Empezando la seccion de Software -->
            <div class="tab-pane fade" id="Software">
                <div class="panel-body">                                        
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6 col-sm-offset-6 col-sm-6 col-xs-offset-0 col-xs-12">
                            <div class="input-group">
                                <input type="text" id="txtNuevoSoftware" class="form-control" placeholder="Nuevo Software">
                                <span role="button" id="btnGuardarSoftware" class="input-group-addon bg-aqua"><i class="fa fa-plus text-white"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h4>Lista de Software</h4>
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-software" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="none">Id</th>                                            
                                            <th class="none">Flag</th>                                            
                                            <th class="all">Software</th>
                                            <th class="all" style="width: 25%">Estatus</th>                                          
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($datos['Software']) && !empty($datos['Software'])) {
                                            foreach ($datos['Software'] as $key => $value) {
                                                echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Flag'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td>" . $value['Estatus'] . "</td>"
                                                . "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
            <!--Empezando la seccion de Software-->
        </div>
        <!--Finalizando contenido de catalogos de perfil-->  

    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
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
                <button type="button" id="btnGuardarCambios" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>