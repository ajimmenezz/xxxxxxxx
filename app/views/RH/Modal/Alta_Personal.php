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
                <li class="active"><a href="#nav-tab-1" data-toggle="tab">Información Personal</a></li>
                <!--                    <li class=""><a href="#nav-tab-2" data-toggle="tab">Información Postal</a></li>
                                    <li class=""><a href="#nav-tab-3" data-toggle="tab">Información Laboral</a></li>
                                    <li class=""><a href="#nav-tab-4" data-toggle="tab">Información de cuentas y créditos</a></li>-->
                <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane fade active in" id="nav-tab-1">
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
            <div class="tab-pane fade" id="nav-tab-2">
                <h3 class="m-t-10">Información Postal</h3>
            </div>
            <div class="tab-pane fade" id="nav-tab-3">
                <h3 class="m-t-10">Información Laboral</h3>
            </div>
            <div class="tab-pane fade" id="nav-tab-4">
                <h3 class="m-t-10">Información de cuentas y créditos</h3>
            </div>
        </div>
    </div>
</div>
<!-- Finalizando panel alta de personal -->