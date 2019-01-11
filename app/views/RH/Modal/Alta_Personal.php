<!-- Empezando titulo de la pagina -->
<h1 class="page-header">Alta <small>de Personal</small></h1>
<!-- Finalizando titulo de la pagina -->
<!-- Empezando panel alta de personal -->
<div id="seccion-datos-personal" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
    <div class="panel-heading p-0">
        <div class="panel-heading-btn m-r-10 m-t-10">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <!-- begin nav-tabs -->
        <div class="tab-overflow">
            <ul class="nav nav-tabs nav-tabs-inverse">
                <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                <li class="active"><a href="#nav-tab-informormacion-personal" data-toggle="tab">Información Personal</a></li>
                <li class="hidden"><a href="#nav-tab-datos-personales" data-toggle="tab">Datos Personales</a></li>
                <li class="hidden"><a href="#nav-tab-academicos" data-toggle="tab">Academicos</a></li>
                <li class="hidden"><a href="#nav-tab-idiomas" data-toggle="tab">Idiomas</a></li>
                <li class="hidden"><a href="#nav-tab-computacionales" data-toggle="tab">Computacionales</a></li>
                <li class="hidden"><a href="#nav-tab-sistemas-especiales" data-toggle="tab">Sistemas Especiales</a></li>
                <li class="hidden"><a href="#nav-tab-automovil" data-toggle="tab">Automovil</a></li>
                <li class="hidden"><a href="#nav-tab-dependientes-economicos" data-toggle="tab">Dependientes Economicos</a></li>
                <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane fade active in" id="nav-tab-informormacion-personal">
                <h3 class="m-t-10">Información Personal</h3>
                <!--Empezando Separador-->
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <!--Finalizando Separador-->
                <div class="row m-t-10">  
                    <form class="margin-bottom-0" id="formNuevoPersonal" data-parsley-validate="true" >
                        <br>
                        <br>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">Área *</label>
                                <select id="selectArea"  style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($areas as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">Departamento *</label>
                                <select id="selectDepartamento" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">Puesto *</label>
                                <select id="selectPerfil" style="width: 100%" data-parsley-required="true" data-parsley-required="true" disabled>
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">Apellido Paterno *</label>
                                <input type="text" class="form-control" id="inputAPPersonal" placeholder="Ingresa el apellido paterno" style="width: 100%" data-parsley-required="true"/>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">Apellido Materno</label>
                                <input type="text" class="form-control" id="inputAMPersonal" placeholder="Ingresa el apellido materno" style="width: 100%"/>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">Nombre(s) *</label>
                                <input type="text" class="form-control" id="inputNombrePersonal" placeholder="Ingresa nombre" style="width: 100%" data-parsley-required="true"/>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">Teléfono Móvil *</label>
                                <input type="text" class="form-control" id="inputTMPersonal" placeholder="044-5555555555" style="width: 100%" data-parsley-required="true"/>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">Teléfono Fijo</label>
                                <input type="text" class="form-control" id="inputTFPersonal" placeholder="01-555-5555555" style="width: 100%"/>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">eMail *</label>
                                <input type="text" class="form-control" id="inputEmailPersonal" placeholder="Ingresa email" style="width: 100%" data-parsley-type="email" data-parsley-required="true"/>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal"> Fecha de Nacimiento *</label>
                                <div id="inputFechaPersonal" class="input-group date calendario" >
                                    <input id="inputFechaNacimiento" type="text" class="form-control nuevoProyecto" placeholder="Fecha de Naciemiento" />
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">CURP *</label>
                                <input type="text" class="form-control" id="inputCurpPersonal" placeholder="Ingresa el curp" style="width: 100%" data-parsley-required="true"/>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">RFC *</label>
                                <input type="text" class="form-control" id="inputRFCPersonal" placeholder="Ingresa RFC" style="width: 100%" data-parsley-required="true"/>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal"> Fecha de Ingreso *</label>
                                <div id="inputFecha" class="input-group date calendario" >
                                    <input id="inputFechaIngreso" type="text" class="form-control" placeholder="Fecha de Ingreso" />
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">No. Seguro Social *</label>
                                <input type="text" class="form-control" id="inputNoSeguroSocial" placeholder="99999999999" style="width: 100%" maxlength="11" data-parsley-required="true"/>                            
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="personal">Jefe *</label>
                                <select id="selectJefe"  style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($infocatV3Usuarios as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div id="nuevaFoto" class="col-md-12">
                            <div class="form-group">
                                <label for="personal">Foto</label>
                                <input id="inputFoto" name="fotoPersonal[]" type="file" multiple />
                            </div>
                        </div>
                        <div id="actualizarFoto" class="col-md-12"></div>
                        <div id="nuevo" class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevoPersonal"><i class="fa fa-plus"></i> Agregar</a>
                                <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnCancelarNuevoPersonal"><i class="fa fa-times"></i> Cancelar</a>
                            </div>
                        </div>
                        <div id="actualizarPersonal" class="col-md-12 hidden">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-success m-r-5 " id="btnactualizarPersonal"><i class="fa fa-plus"></i> Actualizar</a>
                                <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnCancelarActualizarPersonal"><i class="fa fa-times"></i> Cancelar</a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-inline muestraCarga"></div>
                            </div>
                        </div>
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div class="errorAltaPersonal"></div>
                        </div>
                        <!--Finalizando Error-->
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-tab-datos-personales">
                <h3 class="m-t-10">Datos Personales</h3>
                <!--Empezando Separador-->
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <!--Finalizando Separador-->
                <form class="form-horizontal">
                    <div class="row m-t-10">
                        <div class="col-md-4">
                            <label for="selectActualizarPaisUsuario">País</label>
                            <select id="selectActualizarPaisUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($paises as $item) {
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

                    <div class="row m-t-10">
                        <div class="col-md-4">
                            <label for="selectActualizarEstadoCivilUsuario">Estado civil</label>
                            <select id="selectActualizarEstadoCivilUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($estadoCivil as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="inputActualizarNacionalidadUsuario">Nacionalidad</label>
                            <?php (isset($datosPersonal)) ? $nacionalidad = '' : $nacionalidad = $datosPersonal[0]['Nacionalidad']; ?>
                            <input type="text" class="form-control" id="inputActualizarNacionalidadUsuario" style="width: 100%" value="<?php echo $nacionalidad; ?>"/>                            
                        </div>
                        <div class="col-md-4">
                            <label for="selectActualizarSexoUsuario">Sexo</label>
                            <select id="selectActualizarSexoUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($sexo as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-4">
                            <label for="inputActualizarEstaturaUsuario">Estatura</label>                            
                            <?php (isset($datosPersonal)) ? $estatura = '' : $estatura = $datosPersonal[0]['Estatura']; ?>
                            <input type="text" class="form-control" id="inputActualizarEstaturaUsuario" style="width: 100%"  value="<?php echo $estatura; ?>"/>
                        </div>
                        <div class="col-md-4">
                            <label for="inputActualizarPesoUsuario">Peso</label>
                            <?php (isset($datosPersonal)) ? $peso = '' : $peso = $datosPersonal[0]['Peso']; ?>
                            <input type="text" class="form-control" id="inputActualizarPesoUsuario" style="width: 100%" value="<?php echo $peso; ?>"/>
                        </div>
                        <div class="col-md-4">
                            <label for="cinputActualizarTipoSangreUsuario">Tipo de sangre</label>
                            <?php (isset($datosPersonal)) ? $sangre = '' : $sangre = $datosPersonal[0]['Sangre']; ?>
                            <input type="text" class="form-control" id="inputActualizarTipoSangreUsuario" style="width: 100%" value="<?php echo $sangre; ?>"/>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-4">
                            <label for="inputActualizarTallaPantalonUsuario">Talla de pantalon</label>
                            <?php (isset($datosPersonal)) ? $tallaPantalon = '' : $tallaPantalon = $datosPersonal[0]['TallaPantalon']; ?>
                            <input type="text" class="form-control" id="inputActualizarTallaPantalonUsuario" style="width: 100%" value="<?php echo $tallaPantalon; ?>"/>
                        </div>
                        <div class="col-md-4">
                            <label for="inputActualizarTallaCamisaPantalonUsuario">Talla de camisa</label>
                            <?php (isset($datosPersonal)) ? $tallaCamisa = '' : $tallaCamisa = $datosPersonal[0]['Tallacamisa']; ?>
                            <input type="text" class="form-control" id="inputActualizarTallaCamisaUsuario" style="width: 100%" value="<?php echo $tallaCamisa; ?>"/>
                        </div>
                        <div class="col-md-4">
                            <label for="inputActualizarTallaZapatosUsuario">Tallas zapatos</label>
                            <?php (isset($datosPersonal)) ? $tallaZapatos = '' : $tallaZapatos = $datosPersonal[0]['TallaZapatos']; ?>
                            <input type="text" class="form-control" id="inputActualizarTallaZapatosUsuario" style="width: 100%" value="<?php echo $tallaZapatos; ?>"/>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-4">
                            <label for="inputActualizarInstitutoAforeUsuario">Instituto AFORE</label>
                            <?php (isset($datosPersonal)) ? $instAfore = '' : $instAfore = $datosPersonal[0]['InstAfore']; ?>
                            <input type="text" class="form-control" id="inputActualizarInstitutoAforeUsuario" style="width: 100%" value="<?php echo $instAfore; ?>"/>
                        </div>
                        <div class="col-md-4">
                            <label for="inputActualizarNumeroAforeUsuario">Numero de AFORE</label>
                            <?php (isset($datosPersonal)) ? $afore = '' : $afore = $datosPersonal[0]['Afore']; ?>
                            <input type="text" class="form-control" id="inputActualizarNumeroAforeUsuario" style="width: 100%" value="<?php echo $afore; ?>"/>
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
                                <a href="javascript:;" class="btn btn-success m-r-5 " id="btnGuardarPersonalesUsuario"><i class="fa fa-plus"></i> Actualizar</a>
                            </div>
                        </div>
                    </div>  
                </form>
            </div>
            <div class="tab-pane fade" id="nav-tab-academicos">
                <h3 class="m-t-10">Datos Academicos</h3>
                <!--Empezando Separador-->
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <!--Finalizando Separador-->
                <form class="form-horizontal">

                    <div class="row">
                        <div class="col-md-4">
                            <label for="selectActualizarNivelEstudioUsuario">Nivel de estudio *</label>
                            <select id="selectActualizarNivelEstudioUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($nivelEstudio as $item) {
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
                                foreach ($documentosEstudio as $item) {
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
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarAcademicosUsuario"><i class="fa fa-save"></i> Guardar</a>
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
                </form>
            </div>
            <div class="tab-pane fade" id="nav-tab-idiomas">
                <h3 class="m-t-10">Datos Idiomas</h3>
                <!--Empezando Separador-->
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <!--Finalizando Separador-->

                <form class="form-horizontal">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="selectActualizarIdiomaUsuario">Idioma *</label>
                            <select id="selectActualizarIdiomaUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($habilidadesIdioma as $item) {
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
                                foreach ($nivelHabilidades as $item) {
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
                                foreach ($nivelHabilidades as $item) {
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
                                foreach ($nivelHabilidades as $item) {
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
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarIdiomasUsuario"><i class="fa fa-save"></i> Guardar</a>
                            </div>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div id="errorGuardarIdiomasUsuario"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div>  
                </form>
            </div>
            <div class="tab-pane fade" id="nav-tab-computacionales">
                <h3 class="m-t-10">Datos Computacionales</h3>
                <!--Empezando Separador-->
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <!--Finalizando Separador-->
                <form class="form-horizontal">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="selectActualizarSoftwareUsuario">Software *</label>
                            <select id="selectActualizarSoftwareUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($habilidadesSoftware as $item) {
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
                                foreach ($nivelHabilidades as $item) {
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
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarComputacionalesUsuario"><i class="fa fa-save"></i> Guardar</a>
                            </div>
                        </div>
                    </div>  

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div id="errorGuardarComputacionalesUsuario"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div> 
                </form>
            </div>
            <div class="tab-pane fade" id="nav-tab-sistemas-especiales">
                <h3 class="m-t-10">Datos Sistemas Especiales</h3>
                <!--Empezando Separador-->
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <!--Finalizando Separador-->

                <form class="form-horizontal">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="selectActualizarSistemasUsuario">Sistemas *</label>
                            <select id="selectActualizarSistemasUsuario" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar...</option>
                                <?php
                                foreach ($habilidadesSistema as $item) {
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
                                foreach ($nivelHabilidades as $item) {
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
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarEspecialesUsuario"><i class="fa fa-save"></i> Guardar</a>
                            </div>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div id="errorGuardarEspecialesUsuario"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div> 
                </form>
            </div>
            <div class="tab-pane fade" id="nav-tab-automovil">
                <h3 class="m-t-10">Datos Automovil</h3>
                <!--Empezando Separador-->
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <!--Finalizando Separador-->

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
                                <?php (empty($datosConduccion)) ? $antiguedad = '' : $antiguedad = $datosConduccion['Antiguedad']; ?>
                                <input id="selectActualizarAntiguedadUsuario" type="text" class="form-control" value="<?php echo $antiguedad; ?>" />
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>                        
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-6">
                            <label for="inputActualizarTipoLicenciaUsuario">Tipo de licencia</label>
                            <?php (empty($datosConduccion)) ? $tipoLicencia = '' : $tipoLicencia = $datosConduccion['TipoLicencia']; ?>
                            <input type="tel" class="form-control" id="inputActualizarTipoLicenciaUsuario" style="width: 100%" value="<?php echo $tipoLicencia; ?>" />
                        </div>
                        <div class="col-md-6">
                            <label for="selectActualizarTipoVigenciaUsuario">Vigencia</label>
                            <div id="inputFechaVigenciaUsuario" class="input-group date calendario" >
                                <?php (empty($datosConduccion)) ? $expedicion = '' : $expedicion = $datosConduccion['Expedicion']; ?>
                                <input id="selectActualizarTipoVigenciaUsuario" type="text" class="form-control" value="<?php echo $expedicion; ?>" />
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>                        
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-6">
                            <?php (empty($datosConduccion)) ? $noLicencia = '' : $noLicencia = $datosConduccion['NoLicencia']; ?>
                            <label for="inputActualizarNumeroLicenciaUsuario">Numero de licencia</label>
                            <input type="tel" class="form-control" id="inputActualizarNumeroLicenciaUsuario" style="width: 100%" value="<?php echo $noLicencia; ?>" />
                        </div>
                        <div class="col-md-6">
                            <label for="selectActualizarNumeroVigenciaUsuario">Expedición</label>
                            <div id="inputFechaNacimientoUsuario" class="input-group date calendario" >
                                <?php (empty($datosConduccion)) ? $vigencia = '' : $vigencia = $datosConduccion['Vigencia']; ?>
                                <input id="selectActualizarNumeroVigenciaUsuario" type="text" class="form-control" value="<?php echo $vigencia; ?>"/>
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
            <div class="tab-pane fade" id="nav-tab-dependientes-economicos">
                <h3 class="m-t-10">Datos Dependientes Economicos</h3>
                <!--Empezando Separador-->
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <!--Finalizando Separador-->

                <form class="form-horizontal">

                    <div class="row">
                        <div class="col-md-4">
                            <label for="inputActualizarNombreDependienteUsuario">Nombre *</label>
                            <input type="tel" class="form-control" id="inputActualizarNombreDependienteUsuario" style="width: 100%"/>
                        </div>
                        <div class="col-md-4">
                            <label for="inputActualizarParentescoUsuario">Parentesco *</label>
                            <input type="tel" class="form-control" id="inputActualizarParentescoUsuario" style="width: 100%"/>
                        </div>
                        <div class="col-md-4">
                            <label for="inputActualizarParentescoVigenciaUsuario">Fecha de Nacimiento *</label>
                            <div id="inputFechaParentescoVigencia" class="input-group date calendario" >
                                <input id="inputActualizarParentescoVigenciaUsuario" type="text" class="form-control"/>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>   
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarDependientesUsuario"><i class="fa fa-save"></i> Guardar</a>
                            </div>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div id="errorGuardarDependientesUsuario"></div>
                        </div>
                        <!--Finalizando Error-->
                    </div>   
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Finalizando panel alta de personal -->