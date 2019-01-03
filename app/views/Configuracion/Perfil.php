<!-- Empezando #contenido -->
<div id="content" class="content">
    <div class="row">
        <div class="col-md-6 col-xs-6">
            <!-- Empezando titulo de la pagina -->
            <h1 class="page-header">Configuración Perfil Usuario</h1>
            <!-- Finalizando titulo de la pagina -->
        </div>
        <div class="col-md-6 col-xs-6 text-right">
            <label id="btnActualizarContraseñaUsuario" class="btn btn-success">
                <i class="fa fa-pencil"></i> Actualizar Contraseña
            </label>  
        </div>
    </div>
    <!-- Empezando perfil contenedor -->
    <div class="profile-container">
        <div id="cargando" class="text-center hidden">
            <img
                width="200"
                src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif" />
        </div>
        <!-- Empezando perfil-seccion 1 -->
        <div id="configuracionPerfilUsuario" class="profile-section">

            <!-- Empezando perfil-left -->
            <div class="profile-left">
                <!-- Empezando perfil-image -->
                <div class="profile-image">
                    <?php $datosUsuario = $datos['datosUsuario']['datosUsuario']; ?>
                    <?php (empty($datosUsuario['UrlFoto'])) ? $foto = '/assets/img/user-13.jpg' : $foto = $datosUsuario['UrlFoto']; ?>
                    <img src="<?php echo $foto; ?>" alt="" />
                    <input type="hidden" value="<?php echo $usuario['Usuario']; ?>" id="usuario"/>
                    <i class="fa fa-user hide"></i>
                </div>
                <!-- Finalizando perfil-image -->
                <div class="m-b-10">
                    <a id="btnSubirFotoUsuario" href="javascript:;" class="btn btn-warning btn-block btn-sm">Cambiar Foto</a>
                </div>
            </div>

            <!-- Empezando perfil-right -->
            <div class="profile-right">
                <!-- Empezando perfil-info -->
                <div class="profile-info">
                    <!-- Empezando table -->
                    <div class="table-responsive ">
                        <table class="table table-profile">
                            <thead>
                                <tr>
                                    <th class="field"></th>
                                    <th><h4><?php echo $usuario['Nombre'] ?><small><?php echo $usuario['Perfil']; ?></small></h4></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="highlight">
                                    <td class="field">Nombre</td>
                                    <td>
                                        <label class="mobile visible-xs">Nombre</label> 
                                        <div class="col-xs-12"><?php echo $usuario['Nombre'] ?></div>
                                    </td>
                                </tr>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td class="field">CURP</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <?php echo $datosUsuario['CURP'] ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field">NSS</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <?php echo $datosUsuario['NSS'] ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field">RFC</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <?php echo $datosUsuario['RFC'] ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field">Fecha de Nacimiento</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <i class="fa fa-calendar fa-lg m-r-5"></i><?php echo date("d/m/Y", strtotime($datosUsuario['FechaNacimiento'])); ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td class="field">Teléfono Móvil</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <i class="fa fa-mobile fa-lg m-r-5"></i><?php echo $datosUsuario['Tel1']; ?>
                                            <a class="editarPerfil m-l-5" data-campo="Tel1" data-input="<?php echo $datosUsuario['Tel1']; ?>" data-nombreInput="Teléfono Móvil" data-tabla="personal" href="javascript:;">Editar</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field">Teléfono Fijo</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <i class="fa fa-phone fa-lg m-r-5"></i><?php echo $datosUsuario['Tel2']; ?>
                                            <a class="editarPerfil m-l-5" data-campo="Tel2" data-input="<?php echo $datosUsuario['Tel2']; ?>" data-nombreInput="Teléfono Fijo" data-tabla="personal" href="javascript:;">Editar</a>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="field">Email Personal</td>
                                    <td>
                                        <label class="mobile visible-xs ">Email</label>
                                        <div class="col-xs-12">
                                            <i class="fa fa-envelope fa-lg m-r-5"></i><?php echo $datosUsuario['Email']; ?>
                                            <a class="editarPerfil m-l-5" data-campo="Email" data-input="<?php echo $datosUsuario['Email']; ?>" data-nombreInput="Email Personal" data-tabla="usuario" href="javascript:;">Editar</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td class="field">Domicilio</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <i class="fa fa-map-marker fa-lg m-r-5"></i><?php echo $datosUsuario['Domicilio']; ?>
                                            <a class="editarPerfil m-l-5" data-campo="Domicilio" data-input="<?php echo $datosUsuario['Domicilio']; ?>" data-nombreInput="Domicilio" data-tabla="personal" href="javascript:;">Editar</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field">Codigo Postal</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <?php echo $datosUsuario['CP'] ?>
                                            <a class="editarPerfil m-l-5" data-campo="CP" data-input="<?php echo $datosUsuario['CP']; ?>" data-nombreInput="Codigo Postal" data-tabla="personal" href="javascript:;">Editar</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field">Genero</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <?php echo $datosUsuario['Genero'] ?>
                                            <a class="editarPerfil m-l-5" data-campo="IdSexo" data-input="<?php echo $datosUsuario['Genero']; ?>" data-nombreInput="Genero" data-tabla="personal" href="javascript:;">Editar</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field">Token</td>
                                    <td>
                                        <div class="col-xs-12">
                                            <?php echo $datosUsuario['Token'] ?>
                                            <a id="inputToken" class="m-l-5" href="javascript:;">Editar</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Finalizando table -->
                </div>
                <!-- Finalizando perfil-info -->
            </div>
            <!-- Finalizando perfil-right -->
        </div>
        <!-- Finalizando perfil-seccion 1-->
    </div>
    <!-- Fin de perfil contenedor --> 

    <div class="row">
        <div class="col-md-6 col-xs-6">
            <h1 class="page-header">Información del Usuario</h1>
        </div>
    </div>
    <!-- Finalizando titulo de la pagina -->
    <div id="seccion-informacion-usuario" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
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
                    <li class="active"><a href="#personales" data-toggle="tab">Personales</a></li>
                    <li class=""><a href="#academicos" data-toggle="tab">Academicos</a></li>
                    <li class=""><a href="#idiomas" data-toggle="tab">Idiomas</a></li>
                    <li class=""><a href="#computacionales" data-toggle="tab">Computacionales</a></li>
                    <li class=""><a href="#sistemasEspeciales" data-toggle="tab">Sistemas Especiales</a></li>
                    <li class=""><a href="#automovil" data-toggle="tab">Automovil</a></li>
                    <li class=""><a href="#dependientesEconomicos" data-toggle="tab">Dependientes Economicos</a></li>
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
        <!--Finalizando Pestañas para definir la seccion-->

        <!--Empezando contenido de la informacion del servicio-->
        <div class="tab-content">

            <!--Empezando con datos personales-->
            <div class="tab-pane fade active in" id="personales">
                <h3 class="m-t-10 text-center">Datos Personales</h3>

                <div class="separatorBorder"></div>
                <div class="panel-body">
                    <form class="form-horizontal">

                        <div class="row">
                            <div class="col-md-4">
                                <label for="inputFechaNacimientoUsuario">Fecha de nacimiento *</label>
                                <div id="inputFechaNacimientoUsuario" class="input-group date calendario" >
                                    <input id="inputFechaNacimiento" type="text" class="form-control"/>
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="row m-t-10">
                            <div class="col-md-3">
                                <h4 class="m-t-25">Lugar de nacimiento</h4>
                            </div>
                        </div>

                        <div class="row m-t-10">
                            <div class="col-md-4">
                                <label for="selectActualizarPaisUsuario">País</label>
                                <select id="selectActualizarPaisUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    foreach ($datos['catalogos']['paises'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="selectActualizarEstadoUsuario">Estado</label>
                                <select id="selectActualizarEstadoUsuario" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="selectActualizarMunicipioUsuario">Delegación o Municipio</label>
                                <select id="selectActualizarMunicipioUsuario" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                        </div>

                        <div class="separatorBorder"></div>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="selectActualizarEstadoCivilUsuario">Estado civil</label>
                                <select id="selectActualizarEstadoCivilUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    foreach ($datos['catalogos']['estadoCivil'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="inputActualizarNacionalidadUsuario">Nacionalidad</label>
                                <input type="text" class="form-control" id="inputActualizarNacionalidadUsuario" style="width: 100%"/>                            
                            </div>
                            <div class="col-md-4">
                                <label for="selectActualizarSexoUsuario">Sexo</label>
                                <select id="selectActualizarSexoUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    foreach ($datos['catalogos']['sexo'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row m-t-10">
                            <div class="col-md-4">
                                <label for="inputActualizarEstaturaUsuario">Estatura</label>
                                <input type="text" class="form-control" id="inputActualizarEstaturaUsuario" style="width: 100%"/>
                            </div>
                            <div class="col-md-4">
                                <label for="inputActualizarPesoUsuario">Peso</label>
                                <input type="text" class="form-control" id="inputActualizarPesoUsuario" style="width: 100%"/>
                            </div>
                            <div class="col-md-4">
                                <label for="cinputActualizarTipoSangreUsuario">Tipo de sangre</label>
                                <input type="text" class="form-control" id="inputActualizarTipoSangreUsuario" style="width: 100%"/>
                            </div>
                        </div>

                        <div class="row m-t-10">
                            <div class="col-md-4">
                                <label for="inputActualizarTallaPantalonUsuario">Talla de pantalon</label>
                                <input type="text" class="form-control" id="inputActualizarTallaPantalonUsuario" style="width: 100%"/>
                            </div>
                            <div class="col-md-4">
                                <label for="inputActualizarTallaCamisaPantalonUsuario">Talla de camisa</label>
                                <input type="text" class="form-control" id="inputActualizarTallaCamisaUsuario" style="width: 100%"/>
                            </div>
                            <div class="col-md-4">
                                <label for="inputActualizarTallaZapatosUsuario">Tallas zapatos</label>
                                <input type="text" class="form-control" id="inputActualizarTallaZapatosUsuario" style="width: 100%"/>
                            </div>
                        </div>

                        <div class="row m-t-10">
                            <div class="col-md-4">
                                <label for="inputActualizarCurpUsuario">CURP</label>
                                <input type="text" class="form-control" id="inputActualizarCurpUsuario" style="width: 100%"/>
                            </div>
                            <div class="col-md-4">
                                <label for="inputActualizarRfcUsuario">RFC</label>
                                <input type="text" class="form-control" id="inputActualizarRfcUsuario" style="width: 100%"/>
                            </div>
                            <div class="col-md-4">
                                <label for="inputActualizarNssUsuario">NSS</label>
                                <input type="text" class="form-control" id="inputActualizarNssUsuario" style="width: 100%"/>
                            </div>
                        </div>

                        <div class="row m-t-10">
                            <div class="col-md-4">
                                <label for="inputActualizarInstitutoAforeUsuario">Instituto AFORE</label>
                                <input type="text" class="form-control" id="inputActualizarInstitutoAforeUsuario" style="width: 100%"/>
                            </div>
                            <div class="col-md-4">
                                <label for="inputActualizarNumeroAforeUsuario">Numero de AFORE</label>
                                <input type="text" class="form-control" id="inputActualizarNumeroAforeUsuario" style="width: 100%"/>
                            </div>
                        </div>

                        <div class="row m-t-10">
                            <!--Empezando error--> 
                            <div class="col-md-12">
                                <div class="errorGuardarPersonalesUsuario"></div>
                            </div>
                            <!--Finalizando Error-->
                        </div>   
                        <div class="row m-t-10">
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <br>
                                    <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarPersonalesUsuario"><i class="fa fa-save"></i> Guardar</a>
                                </div>
                            </div>
                        </div>   
                    </form>
                </div>
            </div>
            <!--Finalizando datos personales-->

            <!--Empezando con datos academicos-->
            <div class="tab-pane fade" id="academicos">
                <h3 class="m-t-10 text-center">Datos Academicos</h3>

                <div class="separatorBorder"></div>

                <form class="form-horizontal">

                    <div class="row">
                        <div class="col-md-4">
                            <label for="selectActualizarNivelEstudioUsuario">Nivel de estudio *</label>
                            <select id="selectActualizarNivelEstudioUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($datos['catalogos']['nivelEstudio'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="selectActualizarNombreInstitutoUsuario">Nombre de la institución *</label>
                            <input type="tel" class="form-control" id="selectActualizarNombreInstitutoUsuario" style="width: 100%"/>
                        </div>
                        <div class="col-md-4">
                            <label for="selectActualizarDocumentoRecibidoUsuario">Documento recibido *</label>
                            <select id="selectActualizarDocumentoRecibidoUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($datos['catalogos']['documentosEstudio'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-6">
                            <label for="inputActualizarDesdeUsuario">Desde *</label>
                            <div id="inputFecha" class="input-group date calendario" >
                                <input id="inputActualizarDesdeUsuario" type="text" class="form-control"/>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputActualizarHastaUsuario">Hasta *</label>
                            <div id="inputFecha" class="input-group date calendario" >
                                <input id="inputActualizarHastaUsuario" type="text" class="form-control"/>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div id="errorGuardarAcademicosUsuario"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div>   
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarAcademicosUsuario"><i class="fa fa-save"></i> Guardar</a>
                            </div>
                        </div>
                    </div>  

                    <div class="separatorBorder"></div>

                    <div class="table-responsive">
                        <table id="data-table-datos-academicos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Nivel de estudio</th>
                                    <th class="all">Nombre de la institución</th>
                                    <th class="all">Desde</th>
                                    <th class="all">Hasta</th>
                                    <th class="all">Documento</th>
                                </tr>
                            </thead>
                            <tbody>                                     
                            </tbody>
                        </table>
                    </div>

                </form>
            </div>
            <!--Finalizando datos academicos-->

            <!--Empezando con datos de idiomas-->
            <div class="tab-pane fade" id="idiomas">
                <h3 class="m-t-10 text-center">Datos de idiomas</h3>

                <div class="separatorBorder"></div>

                <form class="form-horizontal">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="selectActualizarIdiomaUsuario">Idioma *</label>
                            <select id="selectActualizarIdiomaUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($datos['catalogos']['habilidadesIdioma'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="selectActualizarComprensionUsuario">Comprensión *</label>
                            <select id="selectActualizarComprensionUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($datos['catalogos']['nivelHabilidades'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>                        
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-6">
                            <label for="selectActualizarLecturaUsuario">Lectura *</label>
                            <select id="selectActualizarLecturaUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($datos['catalogos']['nivelHabilidades'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="selectActualizarEscrituraUsuario">Escritura *</label>
                            <select id="selectActualizarEscrituraUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($datos['catalogos']['nivelHabilidades'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>                        
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-12">                                    
                            <label for="inputActualizarComantariosIdiomasUsuario">Comentarios</label>
                            <textarea id="inputActualizarComantariosIdiomasUsuario" class="form-control entregaGarantia" placeholder="Ingrese los comentarios" rows="3" ></textarea>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div id="errorGuardarIdiomasUsuario"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div>   
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarIdiomasUsuario"><i class="fa fa-save"></i> Guardar</a>
                            </div>
                        </div>
                    </div>

                    <div class="separatorBorder"></div>

                    <div class="table-responsive">
                        <table id="data-table-datos-idiomas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Idioma</th>
                                    <th class="all">Comprensión</th>
                                    <th class="all">Lectura</th>
                                    <th class="all">Escritura</th>
                                    <th class="all">Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>                                     
                            </tbody>
                        </table>
                    </div>

                </form>
            </div>
            <!--Finalizando con datos de idiomas-->

            <!--Empezando con datos computacionales-->
            <div class="tab-pane fade" id="computacionales">
                <h3 class="m-t-10 text-center">Datos computacionales</h3>

                <div class="separatorBorder"></div>

                <form class="form-horizontal">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="selectActualizarSoftwareUsuario">Software *</label>
                            <select id="selectActualizarSoftwareUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($datos['catalogos']['habilidadesSoftware'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="selectActualizarNivelComputacionalesUsuario">Nivel *</label>
                            <select id="selectActualizarNivelComputacionalesUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($datos['catalogos']['nivelHabilidades'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                            </select>                        
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-12">                                    
                            <label for="inputActualizarComentariosComputacionalesUsuario">Comentarios</label>
                            <textarea id="inputActualizarComentariosComputacionalesUsuario" class="form-control entregaGarantia" placeholder="Ingrese los comentarios" rows="3" ></textarea>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div id="errorGuardarComputacionalesUsuario"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div>   
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarComputacionalesUsuario"><i class="fa fa-save"></i> Guardar</a>
                            </div>
                        </div>
                    </div>  

                    <div class="separatorBorder"></div>

                    <div class="table-responsive">
                        <table id="data-table-datos-computacionales" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Software</th>
                                    <th class="all">Nivel</th>
                                    <th class="all">Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>                                     
                            </tbody>
                        </table>
                    </div>

                </form>
            </div>
            <!--Finalizando con datos computacionales-->

            <!--Empezando con datos de sistemas especiales-->
            <div class="tab-pane fade" id="sistemasEspeciales">
                <h3 class="m-t-10 text-center">Datos de sistemas especiales</h3>

                <div class="separatorBorder"></div>

                <form class="form-horizontal">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="selectActualizarSistemasUsuario">Sistemas *</label>
                            <select id="selectActualizarSistemasUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($datos['catalogos']['habilidadesSistema'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="selectActualizarNivelSistemasUsuario">Nivel *</label>
                            <select id="selectActualizarNivelSistemasUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($datos['catalogos']['nivelHabilidades'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>                        
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-12">                                    
                            <label for="inputActualizarComnetariosSistemasUsuario">Comentarios</label>
                            <textarea id="inputActualizarComnetariosSistemasUsuario" class="form-control entregaGarantia" placeholder="Ingrese los comentarios" rows="3" ></textarea>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div id="errorGuardarEspecialesUsuario"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div>   
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarEspecialesUsuario"><i class="fa fa-save"></i> Guardar</a>
                            </div>
                        </div>
                    </div>

                    <div class="separatorBorder"></div>

                    <div class="table-responsive">
                        <table id="data-table-datos-sistemas-especiales" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Sistemas</th>
                                    <th class="all">Nivel</th>
                                    <th class="all">Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>                                     
                            </tbody>
                        </table>
                    </div>

                </form>
            </div>
            <!--Finalizando con datos de sistemas especiales-->

            <!--Empezando con datos de automovil-->
            <div class="tab-pane fade" id="automovil">
                <h3 class="m-t-10 text-center">Datos de automovil</h3>

                <div class="separatorBorder"></div>

                <form class="form-horizontal">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="selectActualizarDominaUsuario">¿Sabe conducir?</label>
                            <select id="selectActualizarDominaUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar</option>
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="selectActualizarAntiguedadUsuario">Antigüedad</label>
                            <div id="inputFechaAntiguedadUsuario" class="input-group date calendario" >
                                <input id="selectActualizarAntiguedadUsuario" type="text" class="form-control"/>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>                        
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-6">
                            <label for="inputActualizarTipoLicenciaUsuario">Tipo de licencia</label>
                            <input type="tel" class="form-control" id="inputActualizarTipoLicenciaUsuario" style="width: 100%"/>
                        </div>
                        <div class="col-md-6">
                            <label for="selectActualizarTipoVigenciaUsuario">Vigencia</label>
                            <div id="inputFechaVigenciaUsuario" class="input-group date calendario" >
                                <input id="selectActualizarTipoVigenciaUsuario" type="text" class="form-control"/>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>                        
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-6">
                            <label for="inputActualizarNumeroLicenciaUsuario">Numero de licencia</label>
                            <input type="tel" class="form-control" id="inputActualizarNumeroLicenciaUsuario" style="width: 100%"/>
                        </div>
                        <div class="col-md-6">
                            <label for="selectActualizarNumeroVigenciaUsuario">Vigencia</label>
                            <div id="inputFechaNacimientoUsuario" class="input-group date calendario" >
                                <input id="selectActualizarNumeroVigenciaUsuario" type="text" class="form-control"/>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>                        
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div id="errorGuardarAutomovilUsuario"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div>   
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarAutomovilUsuario"><i class="fa fa-save"></i> Guardar</a>
                            </div>
                        </div>
                    </div>

                </form>    
            </div>
            <!--Finalizando con datos de automovil-->

            <!--Empezando con datos de dependientes Economicos-->
            <div class="tab-pane fade" id="dependientesEconomicos">
                <h3 class="m-t-10 text-center">Datos de dependientes economicos</h3>

                <div class="separatorBorder"></div>

                <form class="form-horizontal">

                    <div class="row">
                        <div class="col-md-4">
                            <label for="inputActualizarNombreDependienteUsuario">Nombre *</label>
                            <input type="tel" class="form-control" id="inputActualizarNombreDependienteUsuario" placeholder="01-555-5555555" style="width: 100%"/>
                        </div>
                        <div class="col-md-4">
                            <label for="inputActualizarParentescoUsuario">Parentesco *</label>
                            <input type="tel" class="form-control" id="inputActualizarParentescoUsuario" placeholder="01-555-5555555" style="width: 100%"/>
                        </div>
                        <div class="col-md-4">
                            <label for="inputActualizarParentescoVigenciaUsuario">Vigencia *</label>
                            <input type="tel" class="form-control" id="inputActualizarParentescoVigenciaUsuario" placeholder="01-555-5555555" style="width: 100%"/>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div class="errorGuardarDependientesUsuario"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div>   
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarDependientesUsuario"><i class="fa fa-save"></i> Guardar</a>
                            </div>
                        </div>
                    </div>

                    <div class="separatorBorder"></div>

                    <div class="table-responsive">
                        <table id="data-table-datos-dependientes-economicos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Nombre</th>
                                    <th class="all">Parentesco</th>
                                    <th class="all">Vigencia</th>
                                </tr>
                            </thead>
                            <tbody>                                     
                            </tbody>
                        </table>
                    </div>

                </form>
            </div>
            <!--Empezando con datos de dependientes Economicos-->
        </div> 





    </div>
</div>
<!-- Finalizando #contenido -->

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div id="error-in-modal"></div>
                <button type="button" id="btnCerrarCambios" class="btn btn-secondary">Cerrar</button>
                <button type="button" id="btnGuardarCambios" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- ================== EMPEZANDO ARCHIVOS CSS DE LA PAGINA================== -->
<link href="/assets/plugins/jquery-fileUpload/css/fileinput.min.css" rel="stylesheet" />
<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
<link href="/assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<link href="/assets/plugins/DataTables/css/data-table.css" rel="stylesheet" />

<!-- ================== EMPEZANDO ARCHIVOS JS DE LA PAGINA================== -->
<script src="/assets/plugins/jquery-fileUpload/js/fileinput.js"></script>
<script src="/assets/plugins/jquery-fileUpload/js/es.js"></script>
<script src="/assets/js/customize/Base/fileUpload.js"></script>
<script src="/assets/js/customize/Base/Select.js"></script>
<script src="/assets/js/customize/Base/Fecha.js"></script>
<script src="/assets/js/customize/Base/Tabla.js"></script>
<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="/assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js"></script>
<script src="/assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="/assets/plugins/DataTables/js/jquery.dataTables.js"></script>
<script src="/assets/plugins/DataTables/js/dataTables.responsive.js"></script>

