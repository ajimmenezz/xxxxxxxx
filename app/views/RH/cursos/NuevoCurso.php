<!-- Empezando #contenido -->
<div id="administracion-cursos" class="content">
    <!-- begin page-header -->
    <h1 class="page-header">Administración de Cursos</h1>
    <!-- end page-header -->

    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" >
        <div class="panel-heading">
            <h4 class="panel-title">Nuevo curso</h4>
        </div>
        <div class="panel-body">

            <div class="row">

                <form action="/" method="POST" data-parsley-validate="true" name="form-wizard">
                    <div id="wizard">
                        <ol>
                            <li>
                                Datos del curso
                                <small>Establece la Información del curso.</small>
                            </li>
                            <li>
                                Temario
                                <small>Establece los temas que se estarán evaluando en el curso.</small>
                            </li>
                            <li>
                                Participantes
                                <small>Indican los puestos que tendrán que tomar el curso.</small>
                            </li>

                        </ol>
                        <!-- begin wizard step-1 -->
                        <div>
                            <fieldset>

                                <div class="row">
                                    <div class=" col-xs-12 col-md-8">
                                        <h4 class="pull-left width-full">Datos curso</h4>
                                    </div>
                                    <div class=" col-xs-12 col-md-4">
                                        <button id="btn-cancel_nuevo-curso" type="button" class="btn btn-danger m-r-5 m-b-5" style="float: right;">Cancelar</button>
                                    </div>
                                    <div class=" col-xs-12 col-md-12"><hr style="width:100%;"></div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <input id="inputImgCurso" name="inputImgCurso[]" type="file">
                                    </div>
                                    <div class="col-xs-8">

                                        <!-- begin row -->
                                        <div class="row">
                                            <!-- begin col-4 -->
                                            <div class=" col-xs-12 col-md-6">
                                                <div class="form-group">
                                                    <label>Nombre del curso *</label>
                                                    <input type="text" name="Nombre" placeholder="Nombre" class="form-control" />
                                                </div>
                                            </div>
                                            <!-- end col-4 -->
                                            <!-- begin col-4 -->
                                            <div class=" col-xs-12 col-md-6">
                                                <div class="form-group">
                                                    <label>Url *</label>
                                                    <input type="text" name="url" placeholder="http://" class="form-control" />
                                                </div>
                                            </div>
                                            <!-- end col-4 -->
                                            <!-- begin col-4 -->
                                            <div class=" col-xs-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="nuevoArchivo">Descripción *</label>
                                                    <textarea id="textareaDescripcionArchivo" class="form-control" name="descripcionArchivo" placeholder="Ingresa una descripción del curso" rows="6" data-parsley-required="true"/></textarea>
                                                </div>
                                            </div>
                                            <!-- end col-4 -->
                                            <!-- begin col-4 -->
                                            <div class=" col-xs-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="nuevoArchivo">Certificado </label>
                                                    <select id="selectTiposArchivos" class="form-control" style="width: 100%" data-parsley-required="true">
                                                        <option value="1">Sin certificado</option>
                                                        <option value="1">Con certificado</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- end col-4 -->
                                            <!-- begin col-4 -->
                                            <div class=" col-xs-12 col-md-6">
                                                <div class="form-group">
                                                    <label>Costo </label>
                                                    <input type="text" name="costo" placeholder="$00.00" class="form-control" />
                                                </div>
                                            </div>
                                            <!-- end col-4 -->
                                        </div>
                                        <!-- end row -->
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- end wizard step-1 -->
                        <!-- begin wizard step-2 -->
                        <div>
                            <fieldset>
                                <div class="row">
                                    <div class=" col-xs-12 col-md-8">
                                        <h4 class="pull-left width-full">Temario</h4>
                                    </div>
                                    <div class=" col-xs-12 col-md-4">
                                        <button id="btn-cancel_nuevo-curso" type="button" class="btn btn-danger m-r-5 m-b-5" style="float: right;">Cancelar</button>
                                    </div>
                                    <div class=" col-xs-12 col-md-12"><hr style="width:100%;"></div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label>Nombre del curso *</label>
                                                    <input type="text" name="Nombre" placeholder="Nombre" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button id="btn-cancel_nuevo-curso" type="button" class="btn btn-success m-r-5 m-b-5" style="float: right;"><i class="fa fa-plus"></i> Agregar</button>
                                            </div>

                                            <div class="col-xs-12">
                                                Aqui defines el temario que lleva el curso que tomara el personal de la empresa.<br>
                                                Cada temario que se ingrese tendrá un valor porcentual del 100%, esto quiere 
                                                decir, que si yo ingreso 10 temas cada uno tendrá un valor del 10%, por lo que es 
                                                importante que tome esto en consideración al definirlo.
                                                <br><br>
                                                <b>Nota: Es importante que se deba definir al menos un temario al curso ya que 
                                                    no se podrá crear.
                                                </b>
                                            </div>
                                            <div class="col-xs-12" style="text-align: center; margin-top:30px;" >
                                                <button id="btn-cancel_nuevo-curso" type="button" class="btn btn-success m-r-5 m-b-5" ><i class="fa fa-file"></i> Subir Excel</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="tabla-cursos" class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <td>Temario</td>
                                                                <td>Porcentaje</td>
                                                                <td>Nombre</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($datos['filas'] as $value) {
                                                                echo '<tr>';
                                                                foreach ($value as $dato) {
                                                                    echo '<td>' . $dato . '</td>';
                                                                }
                                                                echo '</tr>';
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- end wizard step-2 -->
                        <!-- begin wizard step-3 -->
                        <div>
                            <fieldset>
                                <div class="row">
                                    <div class=" col-xs-12 col-md-8">
                                        <h4 class="pull-left width-full">Participantes</h4>
                                    </div>
                                    <div class=" col-xs-12 col-md-4">
                                        <button id="btn-cancel_nuevo-curso" type="button" class="btn btn-danger m-r-5 m-b-5" style="float: right;">Cancelar</button>
                                    </div>
                                    <div class=" col-xs-12 col-md-12"><hr style="width:100%;"></div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="puesto">Puesto </label>
                                                    <select id="puesto" class="form-control" style="width: 100%" data-parsley-required="true">
                                                        <option value="">Seleccionar</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button id="btn-cancel_nuevo-curso" type="button" class="btn btn-success m-r-5 m-b-5" style="float: right;"><i class="fa fa-plus"></i> Agregar</button>
                                            </div>

                                            <div class="col-xs-12">
                                                Indica los puestos que deben tomar el curso.<br>
                                                Cuando generes el curso el sistema notificará por correo al personal que cubra el 
                                                puesto que esta asignado en el curso y también le aparecerá en la sección de CURSOS ASIGNADOS.

                                                <br><br>
                                                <b>Nota: Es importante que se deba definir al menos un puesto. </b>
                                            </div>
                                            <div class="col-xs-12" style="text-align: center; margin-top:30px;">
                                                <button id="btn-save-curso" type="button" class="btn btn-success m-r-5 m-b-5" ><i class="fa fa-save"></i> Guardar curso</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="tabla-cursos" class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <td>Puesto</td>
                                                                <td>Acciones</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($datos['filas'] as $value) {
                                                                echo '<tr>';
                                                                foreach ($value as $dato) {
                                                                    echo '<td>' . $dato . '</td>';
                                                                }
                                                                echo '</tr>';
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- end wizard step-3 -->

                    </div>
                </form>


            </div>


        </div>
    </div>
</div>
<!-- Finalizando #contenido -->
