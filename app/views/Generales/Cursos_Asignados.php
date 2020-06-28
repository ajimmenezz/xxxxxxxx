<!-- Empezando #contenido -->
<div id="administracion-cursos" class="content">
    <!-- begin page-header -->
    <h1 class="page-header">Cursos asignados</h1>
    <!-- end page-header -->

    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" id="tablaAsigCursos">
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="row">
                        <div class="col-sm-4">
                                <div class="widget widget-stats bg-green">
                                    <div class="stats-icon"></div>
                                    <div class="stats-info">
                                        <h4>Avance total</h4>
                                        <p>3,291</p>	
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
                                        <h4>Faltante total</h4>
                                        <p>3,291</p>	
                                    </div>
                                    <div class="stats-link">
                                        <a href="javascript:;"></a>
                                    </div>
                                </div>
                        </div>
                        <div class="col-sm-4">
                                <div class="widget widget-stats bg-blue">
                                    <div class="stats-icon"></div>
                                    <div class="stats-info">
                                        <h4>Total de cursos</h4>
                                        <p>3,278</p>	
                                    </div>
                                    <div class="stats-link">
                                        <a href="javascript:;"></a>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
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
                        <table id="tabla-cursosAsignados" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                  <td>Curso</td>
                                  <td>Avance</td>
                                  <td>Fecha de asignación</td>
                                  <td>Estatus</td>
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
            <!-- end tabla cursos -->
        </div>
    </div>

    <!-- inicio contenido continuar curso -->
    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" id="asigCursoContinuar" style="display:none;">
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">
               
                <div class="col-sm-12 col-md-6" style="float:left;">
                <p><h5>Curso </h5> Gestión de proyectos</p>
                
                <p>En esta sección encuentras los temas que lleva el curso. Cada tema equivale a un porcentaje del total del <br>
                curso por lo que la información del lado derechose estará actualizando. Ingresa a la siguiente liga para <br>
                empezar el curso: <a href="#">https://url</a></p>

                </div>

                <div class="col-sm-12 col-md-6" style="float:right;">
                    <div class="row">
                        <div class="col-sm-4">
                                <div class="widget widget-stats bg-green">
                                    <div class="stats-icon"></div>
                                    <div class="stats-info">
                                        <h4>Faltante total</h4>
                                        <p>3,291</p>	
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
                                        <p>3,291</p>	
                                    </div>
                                    <div class="stats-link">
                                        <a href="javascript:;"></a>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            
            </div>

            <!-- begin tabla cursos -->
            <div class="row" style="margin-top:50px;">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabla-cursosAsignados" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                <td>Curso</td>
                                <td>Avance</td>
                                <td>Fecha de asignación</td>
                                <td>Estatus</td>
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
            <!-- end tabla cursos -->
        </div>
    </div>  
    <!-- fin contenido continuar curso -->

</div>
<!-- Finalizando #contenido -->

