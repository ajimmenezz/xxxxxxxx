<!--
 * Description: Formulario para agregar Avences a los Servicios
 *
 * @author: Alberto Barcenas
 *
-->
<div id="seccion-avance-servicio" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">

    <div class="tab-content">

        <div class="panel-body">

            <!--Empezando formulario Avence servicio -->
            <form class="margin-bottom-0" id="formServicioSinClasificar" data-parsley-validate="true">

                <!--Empezando Decripcion-->
                <div class="row">
                    <div class="col-md-12">                                    
                        <div class="form-group">
                            <label>Descripción *</label>
                            <textarea id="inputDescripcionAvanceServicio" class="form-control " placeholder="Agregar Descripción" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando Archivos-->
                <div class="row">
                    <div class="col-md-12">                                    
                        <div class="form-group">
                            <label id="divArchivos">Archivos</label>
                            <input id="archivosAvanceServicio"  name="archivosAvanceServicio[]" type="file" multiple/>
                        </div>
                    </div>
                </div>
                <!--Finalizando-->

                <!-- Empezando Seleccion Material o Equipo Utilizado -->
                <div class="row ">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="tituloEquipoMaterial"></label>
                            <select id="selectUtilizado" class="form-control" style="width: 100%">
                                <option value="">Seleccionar</option>
                                <option value="1">Equipo TI</option>';
                                <option value="2">Material</option>';
                                <option value="3">Refacción</option>';
                                <option value="4">Salas X4D</option>';
                            </select>
                        </div>
                    </div>
                    <div id="divTipoFalla" class="col-md-6 hidden">
                        <div class="form-group">
                            <label>Tipo de Falla</label>
                            <select id="selectTipoFalla" class="form-control" style="width: 100%">
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($tiposDiagnostico as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Finalizando -->

                <!-- Empezando Seleccion Equipo -->
                <div id="seleccionEquipo" class="row hidden">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Equipo</label>
                            <select id="selectAvanceEquipo" class="form-control" style="width: 100%">
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($equipos as $item) {
                                    echo '<option value="' . $item['Id'] . '" data-serie="' . $item['Parte'] . ' ">' . $item['Equipo'] . '</option>';
                                }
                                ?>
                            </select>                                           
                        </div>
                    </div>
                    <div id='inputSerieEquipo' class="col-md-6">
                        <div class="form-group">
                            <label>Serie</label>
                            <input id="inputAvanceSerieEquipo" type="text" class="form-control"  placeholder="Serie"/>
                        </div>
                    </div>
                    <div id='inputCantidadEquipo' class="col-md-6 hidden">
                        <div class="form-group">
                            <label>Cantidad</label>
                            <input id="inputAvanceCantidadEquipo" type="number" class="form-control"  placeholder="Cantidad"/>
                        </div>
                    </div>   
                </div>
                <!-- Finalizando -->

                <!-- Empezando Seleccion Material -->
                <div id="seleccionMaterial" class="row hidden">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Material</label>
                            <select id="selectAvanceMaterial" class="form-control" style="width: 100%">
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($equiposSAE as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>                                           
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cantidad</label>
                            <input id="inputAvanceCantidadMaterial" type="number" class="form-control"  placeholder="Cantidad"/>
                        </div>
                    </div>                               
                </div>
                <!-- Finalizando -->

                <!-- Empezando Seleccion Refaccion-->
                <div id="seleccionRefaccion" class="row hidden">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Equipo *</label>
                            <select id="selectAvanceRefaccionEquipo" class="form-control" style="width: 100%">
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($equipos as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Equipo'] . '</option>';
                                }
                                ?>
                            </select>                                           
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Refacción *</label>
                            <select id="selectAvanceRefaccion" class="form-control" style="width: 100%" disabled>
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Cantidad</label>
                            <input id="inputAvanceCantidadRefaccion" type="number" class="form-control"  placeholder="Cantidad"/>
                        </div>
                    </div>                               
                </div>
                <!--Finalizando -->
                
                <!-- Empezando Seleccion Refaccion-->
                <div id="seleccionSalas4XD" class="row hidden">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Elemento *</label>
                            <select id="selectAvanceElemento" class="form-control" style="width: 100%">
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($elementos as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>                                           
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Sub-elemento</label>
                            <select id="selectAvanceSubelemento" class="form-control" style="width: 100%" disabled>
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Cantidad</label>
                            <input id="inputAvanceCantidadElementoSubelemento" type="number" class="form-control"  placeholder="Cantidad"/>
                        </div>
                    </div>                               
                </div>
                <!--Finalizando -->

                <!--Empezando boton de agregar y mensaje de error-->
                <div class="row m-t-10">
                    <!--Empezando error--> 
                    <div class="col-md-12">
                        <div class="errorAvenceServicio"></div>
                    </div>
                    <!--Finalizando Error-->

                    <!--Empezando Boton de Agregar--> 
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <br>
                            <a id="btnAgregarAvenceServicio" href="javascript:;" class="btn btn-success m-r-5 "><i class="fa fa-plus"></i> Agregar</a>                            
                        </div>
                    </div>
                    <!--Finalizando-->

                </div>
                <!--Finalizando-->

                <!--Empezando Tabla Avences-->
                <div class="row"> 
                    <div class="col-md-12">  
                        <div class="form-group">
                            <div class="col-md-12">
                                <h3 class="m-t-10">Lista de Equipos o Materiales</h3>
                            </div>
                            <div class="col-md-12">
                                <div class="underline m-b-15 m-t-15"></div>
                            </div>
                            <!--Finalizando Separador-->
                        </div>    
                    </div> 
                </div>

                <div class="table-responsive">
                    <table id="data-table-avances" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="all">Tipo Item</th>
                                <th class="all">Descripción</th>
                                <th class="all">Serie</th> 
                                <th class="all">Cantidad</th> 
                                <th class="never">Item</th> 
                                <th class="never">IdTipoItem</th> 
                                <th class="all">Tipo Falla</th> 
                                <th class="never">Id Tipo Falla</th> 
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!--Finalizando -->

            </form>
            <!--Finalizando formulario Avence servicio -->
        </div>

    </div>
</div>
