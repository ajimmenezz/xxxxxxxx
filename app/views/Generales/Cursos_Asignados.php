<!-- Empezando #contenido -->
<div id="administracion-cursos" class="content">
    <!-- begin page-header -->
    <div class="row">
        <div class="col-sm-8">
            <h1 class="page-header">Cursos asignados</h1>
        </div>
        <div id="divMigajaTemario" class="col-sm-4 hidden">
            <ol class="breadcrumb pull-right">
                <li><a id="btn-regresar-temario" href="javascript:;">Curso</a></li>
                <li class="active">Gestión de Proyectos</li>
            </ol>
        </div>
        <div id="divMigajaTemarioCompletado" class="col-sm-4 hidden">
            <ol class="breadcrumb pull-right">
                <li><a id="btn-regresar-temario-completado" href="javascript:;">Curso</a></li>
                <li class="active">Gestión de Proyectos</li>
            </ol>
        </div>
    </div>
    <!-- end page-header -->

    <div id="tablaAsigCursos" class="panel panel-inverse" data-sortable-id="ui-widget-1" style="display:block;">
        <input id="valorIdUsuario" class="hidden" value="<?php echo $datos['idUsuario']; ?>" />
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-2">
                    <div class="widget widget-stats bg-green">
                        <div class="stats-icon"></div>
                        <div class="stats-info">
                            <h4>Avance total</h4>
                            <p><?php echo $datos['cursos']['avance'] ?>%</p>	
                        </div>
                        <div class="stats-link">
                            <a href="javascript:;"></a>
                        </div>
                    </div>
                    <div class="stats-link">
                        <a href="javascript:;"></a>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="widget widget-stats bg-red">
                        <div class="stats-icon"></div>
                        <div class="stats-info">
                            <h4>Faltante total</h4>
                            <p><?php echo $datos['cursos']['feltante'] ?>%</p>	
                        </div>
                        <div class="stats-link">
                            <a href="javascript:;"></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="widget widget-stats bg-blue">
                        <div class="stats-icon"></div>
                        <div class="stats-info">
                            <h4>Total de cursos</h4>
                            <p><?php echo $datos['cursos']['totalCursos'] ?></p>	
                        </div>
                        <div class="stats-link">
                            <a href="javascript:;"></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    En esta sección encontraras una serie de cursos en línea que se te han asignado a tu perfil, los cuales tiene 
                    como finalidad crear un aporte profecional en tu formación. Por lo tanto esta 
                    herramienta web te permite ir registrando el progreso de cada uno de los cursos que vas tomando. <br><br>

                    <b>Nota Es importante mencionar que el área de Capacitación estará al pendiente de progreso que lleves.</b><br>
                </div>
            </div>

            <!-- begin tabla cursos -->
            <div class="row" style="margin-top:50px;">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabla-cursosAsignados" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <td class="all">Curso</td>
                                    <td class="all">Avance</td>
                                    <td class="all">Fecha de asignación</td>
                                    <td class="all">Estatus</td>
                                    <td class="never">Fecha Inicio</td>
                                    <td class="all">Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($datos['cursos']['cursos'])) {
                                    foreach ($datos['cursos']['cursos'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['id'] . '</td>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '<td>' . ceil($value['Porcentaje']) . '%</td>';
                                        echo '<td>' . $value['fechaAsignacion'] . '</td>';
                                        echo '<td>' . $value['EstatusNombre'] . '</td>';
                                        echo '<td>' . $value['FechaInicio'] . '</td>';
                                        echo '<td></td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end tabla cursos -->
        </div>
    </div>

    <!-- inicio contenido continuar curso -->
    <div id="asigCursoContinuar" class="panel panel-inverse" data-sortable-id="ui-widget-1" style="display:none;">
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12 col-md-5" style="float:left;">
                    <span style="font-size:15px;"><b style="font-size:18px;">Curso </b> Gestión de proyectos</span>
                    <p style="margin-top:25px;">En esta sección encuentras los temas que lleva el curso. Cada tema equivale a un porcentaje del total del
                        curso por lo que la información del lado derechose estará actualizando. Ingresa a la siguiente liga para
                        empezar el curso: <a href="#">https://url</a></p>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-green">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Faltante total</h4>
                                    <p class="divFaltante"></p>	
                                </div>
                                <div class="stats-link">
                                    <a href="javascript:;"></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-red">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Avance total</h4>
                                    <p class="divAvance"></p>	
                                </div>
                                <div class="stats-link">
                                    <a href="javascript:;"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 div-tabla-temario">
                    <h4>Temario</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                    <div class="table-responsive">
                        <table class="tabla-temario table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td class="never">IdCurso</td>
                                    <td class="never">IdAvance</td>
                                    <td class="all">Modulo</td>
                                    <td class="all">Avance</td>
                                    <td class="all">Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-7">
                    <h4>Avances</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                    <div id="divAvances">

                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>  
<!-- fin contenido continuar, terminar curso evidencias-->

<div id="divInstrucciones" class="hidden">
    <p class="text-justify">                                        
        Realiza las siguientes instrucciones para registar tu avance: <br><br>
        1.- En la tabla de temarios identifica el tema que deseas subir a tu avanze. <br>
        2.- Da un clic sobre el botón terminar de la columna acciones. <br>
        3.- El sistema muestra un formulario.<br>
        4.- Ingresa un comentario del tema que estas ingresando.<br>
        5.- Sube tus evidencias (solo formatos png y jpg).<br>
        6.- Guarda tu avance. <br>
    </p>
    <b style="font-size:12px;">Nota: Cuando guardes el avance ya no podrás modificarlo solo te permitirá consultarlo.</b>
</div>

<div id="formularioMostrarAvance" class="hidden">
    Comentarios <br>
    <textarea id="avanceComentario" class="form-control" placeholder="Comentarios" rows="5" disabled></textarea>
    <div id="gallery" class="gallery">
    </div>
    <div class="text-right">
        <button type="button" id="btnCerrarCompletarAvanceCurso" class="btn btn-white text-right" data-dismiss="modal">Cerrar</button>
    </div>
</div>

<div id="formularioAgregarAvance" class="hidden">
    <div class="row">
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-12">
                    <p>Comentarios</p>
                    <textarea id="cometariosAvanceCurso" class="form-control" placeholder="Comentarios" rows="5"></textarea>
                </div>
            </div>
            <div class="row"> 
                <div class="col-md-12 m-t-10">
                    <div id="errorCometariosAvanceCurso"></div>
                </div>
            </div>
            <div class="row m-t-15">
                <div class="col-md-12 text-center">
                    <button id="btn-cancel-avance" type="button" class="btn btn-white m-r-5 m-b-5 btn-sm">Cancelar avance</button>
                    <button id="btn-registrar-avance" type="button" class="btn btn-success m-r-5 m-b-5 btn-sm">Registrar avance</button>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="row">
                <input id="evidencias" name="evidencias[]" type="file" multiple >
            </div>
        </div>
    </div>
</div>