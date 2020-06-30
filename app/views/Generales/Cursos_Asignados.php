<!-- Empezando #contenido -->
<div id="administracion-cursos" class="content">
    <!-- begin page-header -->
    <h1 class="page-header">Cursos asignados</h1>
    <!-- end page-header -->

    <div id="tablaAsigCursos" class="panel panel-inverse" data-sortable-id="ui-widget-1" style="display:block;">
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
                            <p>65.33%</p>	
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
                            <p>33.77%</p>	
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
                            <p>90</p>	
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
                        <table class="tabla-cursosAsignados table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <td class="all">Curso</td>
                                    <td class="all">Avance</td>
                                    <td class="all">Fecha de asignación</td>
                                    <td class="all">Estatus</td>
                                    <td class="all">Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($datos['filas'])) {
                                    foreach ($datos['filas'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Curso'] . '</td>';
                                        echo '<td>' . $value['Avance'] . '</td>';
                                        echo '<td>' . $value['FechaAsignacion'] . '</td>';
                                        echo '<td>' . $value['Estatus'] . '</td>';
                                        echo '<td class="text-center">' . $value['Acciones'] . '</td>';
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
                <div class="col-sm-12 col-md-6" style="float:left;">
                    <span style="font-size:15px;"><b style="font-size:18px;">Curso </b> Gestión de proyectos</span>
                    <p style="margin-top:25px;">En esta sección encuentras los temas que lleva el curso. Cada tema equivale a un porcentaje del total del <br>
                        curso por lo que la información del lado derechose estará actualizando. Ingresa a la siguiente liga para <br>
                        empezar el curso: <a href="#">https://url</a></p>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-green">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Faltante total</h4>
                                    <p>100%</p>	
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
                                    <p>0%</p>	
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
                <div class="col-md-5">
                    <h4>Temario</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                    <div class="table-responsive">
                        <table id="tabla-temario" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td>Modulo</td>
                                    <td>Avance</td>
                                    <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td><span class="temarioTablaCompletado" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #348fe2;"><i class="fa fa-edit"></i>Completado</span></td>
                                    <td><span class="temarioTablaTerminar" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #00acac; "><i class="fa fa-youtube-play" ></i>Terminar</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-7">
                    <h4>Avances</h4>
                    <div class="underline m-b-15 m-t-15"></div>
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
            </div>
        </div>
    </div>  

    <!-- inicio contenido continuar, continuar curso evidencias -->
    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" id="temarioComenzarCurso" style="display:none;">
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-sm-12 col-md-6" style="float:left;">
                    <span style="font-size:15px;"><b style="font-size:18px;">Curso </b> Gestión de proyectos</span>

                    <p style="margin-top:25px;">En esta sección encuentras los temas que lleva el curso. Cada tema equivale a un porcentaje del total del <br>
                        curso por lo que la información del lado derechose estará actualizando. Ingresa a la siguiente liga para <br>
                        empezar el curso: <a href="#">https://url</a></p>

                </div>

                <div class="col-sm-12 col-md-6">
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-green">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Faltante total</h4>
                                    <p>80%</p>	
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
                                    <p>20%</p>	
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
                <div class="col-md-5">
                    <h4>Temario</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                    <div class="table-responsive">
                        <table class="tabla-cursosAsignados table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td>Modulo</td>
                                    <td>Avance</td>
                                    <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><span class="temarioTablaCompletado"  style="cursor: pointer; margin: 5px; font-size: 13px;  color: #348fe2;"><i class="fa fa-edit"></i>Completado</span></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><span class="temarioTablaTerminar" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #00acac; "><i class="fa fa-youtube-play" ></i>Terminar</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-7">
                    <h4>Avances</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                    Comentarios <br>
                    <textarea class="form-control" placeholder="Comentarios" rows="5"></textarea>
                    <div id="gallery" class="gallery">
                        <div class="image gallery-group-1">
                            <div class="image-inner">
                                <a href="assets/img/gallery/gallery-1.jpg" data-lightbox="gallery-group-1">
                                    <img src="http://localhost/plantilla/admin/template_content_ajax/assets/img/gallery/gallery-1.jpg" alt="" />
                                </a>
                            </div>
                            <div class="image-info">
                                <h5>Fecha</h5>
                                <h5 class="title">Comentarios</h5>
                                <div class="desc">
                                    Nunc velit urna, aliquam at interdum sit amet, lacinia sit amet ligula. Quisque et erat eros. Aenean auctor metus in tortor placerat, non luctus justo blandit.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" id="btnCerrarCompletarAvanceCurso" class="btn btn-white text-right" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <!-- fin contenido continuar, continuar curso evidencias-->

    <!-- inicio contenido continuar, terminar curso evidencias -->
    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" id="temarioTerminarCurso" style="display:none;">
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-sm-12 col-md-6" style="float:left;">
                    <span style="font-size:15px;"><b style="font-size:18px;">Curso </b> Gestión de proyectos</span>

                    <p style="margin-top:25px;">En esta sección encuentras los temas que lleva el curso. Cada tema equivale a un porcentaje del total del <br>
                        curso por lo que la información del lado derechose estará actualizando. Ingresa a la siguiente liga para <br>
                        empezar el curso: <a href="#">https://url</a></p>

                </div>

                <div class="col-sm-12 col-md-6" style="float-right">
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-green">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Faltante total</h4>
                                    <p>100%</p>	
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
                                    <p>0%</p>	
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
                <div class="col-md-5">
                    <h4>Temario</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                    <div class="table-responsive">
                        <table class="tabla-cursosAsignados table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td>Modulo</td>
                                    <td>Avance</td>
                                    <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td><span id="temarioContentTablaCompletado" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #348fe2;"><i class="fa fa-edit"></i>Completado</span></td>
                                    <td><span id="temarioContentTablaComenzar" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #00acac; "><i class="fa fa-youtube-play" ></i>Terminar</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-7">
                    <h4>Avances</h4>
                    <div class="underline m-b-15 m-t-15"></div>

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
                                <button id="btn-cancel_terminarCurso" type="button" class="btn btn-success m-r-5 m-b-5 btn-sm">Agregar archivos</button>
                                <button id="btn-cancel_terminarCurso" type="button" class="btn btn-primary m-r-5 m-b-5 btn-sm">Subir evidencias</button>
                                <button id="btn-cancel_terminarCurso" type="button" class="btn btn-danger m-r-5 m-b-5 btn-sm">Borrar</button>
                                <div> <button id="btn-cancel_terminarCurso" type="button" class="btn btn-white m-r-5 m-b-5 " style="float: right;">Cancelar</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
<!-- fin contenido continuar, terminar curso evidencias-->


<!-- fin contenido continuar curso -->

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
                <button type="button" id="btnCancelar" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btnAceptar" class="btn btn-success">Comenzar</button>
            </div>
            <div id="errorModal"></div>
        </div>
    </div>
</div>
