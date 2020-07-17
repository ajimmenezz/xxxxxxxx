<!-- Empezando #contenido -->
<div id="administracion-cursos" class="content">
    <!-- begin page-header -->
    <h1 class="page-header">Cursos asignados</h1>
    <!-- end page-header -->

    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" >
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="row">
                        <div class="col-sm-4">
                                <div class="widget widget-stats bg-green">
                                    <div class="stats-icon"><i class="fa fa-desktop"></i></div>
                                    <div class="stats-info">
                                        <h4>Avence total</h4>
                                        <p>3,291,922</p>	
                                    </div>
                                    <div class="stats-link">
                                        <!-- <a href="javascript:;">View Detail <i class="fa fa-arrow-circle-o-right"></i></a> -->
                                    </div>
                                </div>
                        </div>
                        <div class="col-sm-4">
                                <div class="widget widget-stats bg-red">
                                    <div class="stats-icon"><i class="fa fa-desktop"></i></div>
                                    <div class="stats-info">
                                        <h4>Faltante total</h4>
                                        <p>3,291,922</p>	
                                    </div>
                                    <div class="stats-link">
                                        <!-- <a href="javascript:;">View Detail <i class="fa fa-arrow-circle-o-right"></i></a> -->
                                    </div>
                                </div>
                        </div>
                        <div class="col-sm-4">
                                <div class="widget widget-stats bg-blue">
                                    <div class="stats-icon"><i class="fa fa-desktop"></i></div>
                                    <div class="stats-info">
                                        <h4>Total de cursos</h4>
                                        <p>3,291,922</p>	
                                    </div>
                                    <div class="stats-link">
                                        <!-- <a href="javascript:;">View Detail <i class="fa fa-arrow-circle-o-right"></i></a> -->
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
</div>
<!-- Finalizando #contenido -->




<!-- Empezando #contenido MODALS-->

<!-- subir cursos -->
<div id="modalSubircursos" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered " role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle" >Subir cursos</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body ">

                <div class="container">
                  <div class="row">
                   
                    <div class="col-11">
                         Para poder subir los cursos a través de un archivo de Excel es necesario seguir los siguientes pasos: <br><br>
                                1.- Debes descargar la plantilla de Excel en el botón descargar plantilla.<br>
                                2.- LLenar la plantilla con los datos solicitados.<br>
                                3.- Subir la platilla con el botón archivo (solo formato Excel).<br>
                                4.- Una vez cargado el archivo solo dar clic en subir archivo.<br><br>
                    </div>
                   
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white m-r-5 " id="cerrar" data-dismiss="modal" aria-label="Close"> Cerrar</a>
                    <a href="javascript:;" class="btn btn-primary m-r-5 " id="desPlantilla">Descargar plantilla</a>
                    <a href="javascript:;" class="btn btn-success m-r-5 " id="save"> Subir plantilla</a>
              </div>
              <div id="alertasGeocercas"></div>

          </div>
      </div>
    </div>

<!-- fin subir cursos -->


<!-- fin #contenido MODALS-->

