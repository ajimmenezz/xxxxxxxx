<!-- Empezando #contenido -->
<div id="administracion-cursos" class="content">
    <!-- begin page-header -->
    <h1 class="page-header">Administración de Cursos</h1>
    <!-- end page-header -->

    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" >
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-9">
                   Este modulo tiene el objetivo de administrar los cursos en linea que tomará el personal de la empresa.<br>
                   Aquí se cuenta con las funcionalidades para la creación, edición y eliminación de un curso, de igual forma, 
                   se puede ver el avance general de los cursos y el de cada uno. También se peude dar seguimiento al avance de 
                   cada uno de los participantes que se encuentran asignados al curso.
                </div>
                <div class="col-md-3">
                    <button id="btn-nuevo-curso" type="button" class="btn btn-primary m-r-5 m-b-5" style="float: right;">Nuevo Curso</button>
                    <button id="btn-subir-cursos" type="button" class="btn btn-info m-r-5 m-b-5" style="float: right;">Subir Cursos</button>
                   
                </div>
              
            </div>

            <!-- begin tabla cursos -->
            <div class="row" style="margin-top:50px;">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabla-cursos" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                  <td>Nombre</td>
                                  <td>Descripción</td>
                                  <td>#Participantes</td>
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
</div>
<!-- Finalizando #contenido -->
