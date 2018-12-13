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

        <!-- Empezando perfil-seccion 2 -->
        <!--        <div class="profile-section">
                     Empezando titulo de seccion 
                    <h1 class="page-header">Información <small> del usuario</small> </h1>
                     Finalizando titulo de seccion 
        
                    <div class="row">
                        <div class="col-md-offset-1 col-md-10">
                             begin panel 
                            <div class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
                                <div class="panel-heading p-0">
                                     Empezando nav-tabs 
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
                                     Finalizando nav-tabs 
                                </div>
                                Empezando contenido del nav-tabs
                                <div class="tab-content">
                                    Empezando con datos personales
                                    <div class="tab-pane fade active in" id="personales">
                                        <h3 class="m-t-10 text-center">Datos Personales</h3>
        
                                        <div class="separatorBorder"></div>
        
                                        <form class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Fecha de nacimiento</label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" id="datepicker-default" placeholder="Select Date" value="04/1/2014" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-12 col-md-2 control-label">Lugar de nacimiento</label>
                                                <label class="col-md-1 control-label">Pais</label>
                                                <div class="col-md-2">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                                <label class="col-md-1 control-label">Estado</label>
                                                <div class="col-md-2">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                                <label class="col-md-2 control-label">Municipio</label>
                                                <div class="col-md-2">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                            </div>
        
                                            <div class="separatorBorder"></div>
        
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Estado civil</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                                <label class="col-md-2 control-label">Nacionalidad</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                                <label class="col-md-2 control-label">Telefóno particular</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Estatura</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                                <label class="col-md-2 control-label">Sexo</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                                <label class="col-md-2 control-label">Peso</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Tipo de sangre</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                                <label class="col-md-2 control-label">Talla de pantalon</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                                <label class="col-md-2 control-label">Talla de camisa</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Tallas zapatos</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                                <label class="col-md-2 control-label">CURP</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                                <label class="col-md-2 control-label">RFC</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Instituto AFORE</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                                <label class="col-md-2 control-label">Numero de AFORE</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                                <label class="col-md-2 control-label">NSS</label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" placeholder="Default input" />
                                                </div>
                                            </div>
        
                                            <div class="form-group">
                                                <div class=" col-xs-offset-0 col-xs-12 col-md-offset-11 col-md-1 text-center">
                                                    <button type="button" class="btn btn-sm btn-success">Guardar</button>
                                                </div>    
                                            </div>    
                                        </form>
                                    </div>
                                    Finalizando datos personales
        
                                    Empezando con datos academicos
                                    <div class="tab-pane fade" id="academicos">
                                        <h3 class="m-t-10 text-center">Datos Academicos</h3>
        
                                        <div class="separatorBorder"></div>
        
                                        <form class="form-horizontal">
                                            <div class="form-group">                                                    
                                                <label class="col-md-2 control-label">Nivel de estudio</label>
                                                <div class="col-md-2">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                                <label class="col-md-2 control-label">Nombre de la institución</label>
                                                <div class="col-md-2">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                                <label class="col-md-2 control-label">Documento recibido</label>
                                                <div class="col-md-2">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                            </div>
        
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Desde</label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" id="datepicker-default" placeholder="Select Date" value="04/1/2014" />
                                                </div>
                                                <label class="col-md-2 control-label">Hasta</label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" id="datepicker-default" placeholder="Select Date" value="04/1/2014" />
                                                </div>
                                            </div>
        
                                            <div class="separatorBorder"></div>
        
                                            <div class="form-group">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Nivel de estudio</th>
                                                                <th>Nombre de la institución</th>
                                                                <th>Desde</th>
                                                                <th>Hasta</th>
                                                                <th>Documento</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Primaria</td>
                                                                <td>Escuela primaria</td>
                                                                <td>1980</td>
                                                                <td>1986</td>
                                                                <td>certificado</td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>Secundaria</td>
                                                                <td>Escuela Secundaria</td>
                                                                <td>1986</td>
                                                                <td>1989</td>
                                                                <td>certificado</td>
                                                            </tr>
                                                            <tr>
                                                                <td>3</td>
                                                                <td>Preparatoria</td>
                                                                <td>Escuela preparatorio</td>
                                                                <td>1989</td>
                                                                <td>1992</td>
                                                                <td>certificado</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
        
                                            </div>
        
                                            <div class="form-group">
                                                <div class=" col-xs-offset-0 col-xs-12 col-md-offset-11 col-md-1 text-center">
                                                    <button type="button" class="btn btn-sm btn-success">Guardar</button>
                                                </div>    
                                            </div>    
                                        </form>
                                    </div>
                                    Finalizando datos academicos
        
                                    Empezando con datos de idiomas
                                    <div class="tab-pane fade" id="idiomas">
                                        <h3 class="m-t-10 text-center">Datos de idiomas</h3>
        
                                        <div class="separatorBorder"></div>
        
                                        <form class="form-horizontal">
                                            <div class="form-group">                                                    
                                                <label class="col-md-2 control-label">Idioma</label>
                                                <div class="col-md-3">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                                <label class="col-md-2 control-label">Comprension</label>
                                                <div class="col-md-3">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                            </div>
        
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Lectura</label>
                                                <div class="col-md-3">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                                <label class="col-md-2 control-label">Escritura</label>
                                                <div class="col-md-3">
                                                    <select class="default-select2 form-control"></select>
                                                </div>                                                    
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Comentarios</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" placeholder="Textarea" rows="5"></textarea>
                                                </div>                                                    
                                            </div>
        
                                            <div class="separatorBorder"></div>
        
                                            <div class="form-group">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Idioma</th>
                                                                <th>Comprension</th>
                                                                <th>Lectura</th>
                                                                <th>Escritura</th>
                                                                <th>Comentarios</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Ingles</td>
                                                                <td>100%</td>
                                                                <td>100%</td>
                                                                <td>100%</td>
                                                                <td>Sin comentarios</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
        
                                            </div>
        
                                            <div class="form-group">
                                                <div class=" col-xs-offset-0 col-xs-12 col-md-offset-11 col-md-1 text-center">
                                                    <button type="button" class="btn btn-sm btn-success">Guardar</button>
                                                </div>    
                                            </div>    
                                        </form>
                                    </div>
                                    Finalizando con datos de idiomas
        
                                    Empezando con datos computacionales
                                    <div class="tab-pane fade" id="computacionales">
                                        <h3 class="m-t-10 text-center">Datos computacionales</h3>
        
                                        <div class="separatorBorder"></div>
        
                                        <form class="form-horizontal">
                                            <div class="form-group">                                                    
                                                <label class="col-md-2 control-label">Software</label>
                                                <div class="col-md-3">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                                <label class="col-md-2 control-label">Nivel</label>
                                                <div class="col-md-3">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Comentarios</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" placeholder="Textarea" rows="5"></textarea>
                                                </div>                                                    
                                            </div>
        
                                            <div class="separatorBorder"></div>
        
                                            <div class="form-group">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Software</th>
                                                                <th>Nivel</th>
                                                                <th>Comentarios</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Windows</td>
                                                                <td>100%</td>
                                                                <td>Sin comentarios</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
        
                                            </div>
        
                                            <div class="form-group">
                                                <div class=" col-xs-offset-0 col-xs-12 col-md-offset-11 col-md-1 text-center">
                                                    <button type="button" class="btn btn-sm btn-success">Guardar</button>
                                                </div>    
                                            </div>    
                                        </form>
                                    </div>
                                    Finalizando con datos computacionales
        
                                    Empezando con datos de sistemas especiales
                                    <div class="tab-pane fade" id="sistemasEspeciales">
                                        <h3 class="m-t-10 text-center">Datos de sistemas especiales</h3>
        
                                        <div class="separatorBorder"></div>
        
                                        <form class="form-horizontal">
                                            <div class="form-group">                                                    
                                                <label class="col-md-2 control-label">Sistemas</label>
                                                <div class="col-md-3">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                                <label class="col-md-2 control-label">Nivel</label>
                                                <div class="col-md-3">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Comentarios</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" placeholder="Textarea" rows="5"></textarea>
                                                </div>                                                    
                                            </div>
        
                                            <div class="separatorBorder"></div>
        
                                            <div class="form-group">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Sistemas</th>
                                                                <th>Nivel</th>
                                                                <th>Comentarios</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>CCTV</td>
                                                                <td>Basico</td>
                                                                <td>Sin comentarios</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
        
                                            </div>
        
                                            <div class="form-group">
                                                <div class=" col-xs-offset-0 col-xs-12 col-md-offset-11 col-md-1 text-center">
                                                    <button type="button" class="btn btn-sm btn-success">Guardar</button>
                                                </div>    
                                            </div>    
                                        </form>
                                    </div>
                                    Finalizando con datos de sistemas especiales
        
                                    Empezando con datos de automovil
                                    <div class="tab-pane fade" id="automovil">
                                        <h3 class="m-t-10 text-center">Datos de automovil</h3>
        
                                        <div class="separatorBorder"></div>
        
                                        <form class="form-horizontal">
                                            <div class="form-group">                                                    
                                                <label class="col-md-2 control-label">Domina</label>
                                                <div class="col-md-3">
                                                    <select class="default-select2 form-control"></select>
                                                </div>
                                                <label class="col-md-2 control-label">Antigüedad</label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" id="datepicker-default" placeholder="Select Date" value="04/1/2014" />
                                                </div>                                                    
                                            </div>
        
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Tipo de licencia</label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" placeholder="Licencia" />
                                                </div>
                                                <label class="col-md-2 control-label">Expedición</label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" id="datepicker-default" placeholder="Select Date" value="04/1/2014" />
                                                </div>
                                            </div>
        
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Numero de licencia</label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" placeholder="Licencia" />
                                                </div>
                                                <label class="col-md-2 control-label">Vigencia</label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" id="datepicker-default" placeholder="Select Date" value="04/1/2014" />
                                                </div>
                                            </div>
        
                                            <div class="form-group">
                                                <div class=" col-xs-offset-0 col-xs-12 col-md-offset-11 col-md-1 text-center">
                                                    <button type="button" class="btn btn-sm btn-success">Guardar</button>
                                                </div>    
                                            </div>   
                                        </form>    
                                    </div>
                                    Finalizando con datos de automovil
        
                                    Empezando con datos de dependientes Economicos
                                    <div class="tab-pane fade" id="dependientesEconomicos">
                                        <h3 class="m-t-10 text-center">Datos de dependientes economicos</h3>
        
                                        <div class="separatorBorder"></div>
        
                                        <form class="form-horizontal">
                                            <div class="form-group">                                                    
                                                <label class="col-md-1 control-label">Nombre</label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" placeholder="Nombre"/>
                                                </div>
                                                <label class="col-md-1 control-label">Parentesco</label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" placeholder="Parentesco"/>
                                                </div>
                                                <label class="col-md-1 control-label">Vigencia</label>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" id="datepicker-default" placeholder="Select Date" value="04/1/2014" />
                                                </div>
                                            </div>
        
                                            <div class="separatorBorder"></div>
        
                                            <div class="form-group">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Nombre</th>
                                                                <th>Parentesco</th>
                                                                <th>Vigencia</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Carlos</td>
                                                                <td>Hermano</td>
                                                                <td>15/12/2015</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
        
                                            </div>
        
                                            <div class="form-group">
                                                <div class=" col-xs-offset-0 col-xs-12 col-md-offset-11 col-md-1 text-center">
                                                    <button type="button" class="btn btn-sm btn-success">Guardar</button>
                                                </div>    
                                            </div>    
                                        </form>
                                    </div>
                                    Empezando con datos de dependientes Economicos
                                </div> 
                                Finalizando contenido del nav-tabs
                            </div>
                             end panel 
                        </div>
                    </div>
                </div>-->
        <!-- Finalizando perfil-seccion 2 -->
    </div>
    <!-- Fin de perfil contenedor --> 

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

<!-- ================== EMPEZANDO ARCHIVOS JS DE LA PAGINA================== -->
<script src="/assets/plugins/jquery-fileUpload/js/fileinput.js"></script>
<script src="/assets/plugins/jquery-fileUpload/js/es.js"></script>
<script src="/assets/js/customize/Base/fileUpload.js"></script>

