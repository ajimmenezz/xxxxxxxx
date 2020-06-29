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
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-9">
                   Este modulo tiene el objetivo de administrar los cursos en linea que tomará el personal de la empresa.<br>
                   Aquí se cuenta con las funcionalidades para la creación, edición y eliminación de un curso, de igual forma, 
                   se puede ver el avance general de los cursos y el de cada uno. También se peude dar seguimiento al avance de 
                   cada uno de los participantes que se encuentran asignados al curso.
                </div>
                <div class="col-md-3">
                    <button id="btn-nuevo-curso" type="button" class="btn btn-primary m-r-5 m-b-5" style="float: right;">Nuevo Curso</button>
                    <button id="btn-loadExcel-temario" type="button" class="btn btn-info m-r-5 m-b-5" style="float: right;">Subir Cursos</button>
                   
                </div>
              
            </div>

            <div class="col-12 messageAcciones"></div>

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
                            var_dump($datos['cursos']);

                            
                                
                                foreach ($datos['cursos'] as $value) {
                                    echo "<tr>";
                                        echo "<td>".$value["Nombre"]."</td>";
                                        echo "<td>".$value["Descripcion"]."</td>";
                                        
                                        echo "<td>".$value["Participantes"]."</td>";
                                        $estado="Activo";
                                        if($value["Estatus"]==0){
                                            $estado="Inactivo";
                                        }
                                        echo "<td>".$estado."</td>";
                                        echo "<td> 
                                                <div style='text-align: center;'>
                                                <input type='hidden' id='idElementSeleccionAccion'>
                                                <i class='fa fa-eye' style='cursor: pointer; margin: 5px; font-size: 17px;  color: #348fe2;' id='btn-adminVerCurso'></i>
                                                <i class='fa fa-pencil' style='cursor: pointer; margin: 5px; font-size: 17px; color: orange;'' id='btn-adminEditarCurso' ></i>
                                                <i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;' onclick='btnAdminEliminarCurso(".$value["Id"].")' id='btn-adminEliminarCurso'></i>
                                                </div>
                                            </td>";
                                    echo "</tr>";
                                }

                            

                                // foreach ($datos['cursos'] as $value) {
                                //     echo '<tr>';
                                //     foreach ($value as $dato) {
                                //         echo '<td>' . $dato . '</td>';
                                //     }
                                //     echo '</tr>';
                                // }
                                ?>
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
            <!-- end tabla cursos -->
        </div>
    </div>

    <!--SandBox SmartResponse--->
    <div class="panel panel-inverse" data-sortable-id="ui-widget-1">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                    <button id="btn-demo-smartresponse" type="button" class="btn btn-info m-r-5 m-b-5">Demo SmartResponse</button>
                    <button id="btn-demo-smartresponse-error" type="button" class="btn btn-info m-r-5 m-b-5">Demo SmartResponse Error</button>
                    </div>
                </div>

                <div class="row">
                <div id="sandbox-result" class="col-md-12"></div>
                </div>
            </div>
    </div>
</div>
<!-- Finalizando #contenido  PRINCIPAL-->


<!-- Empezando #contenido NUEVO CURSO-->
<div id="administracion-cursos_nuevoCurso" class="content" style="display:none;">
    <!-- begin page-header -->
    <h1 class="page-header">Administración de Cursos</h1>
    <!-- end page-header -->

    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" >
        <div class="panel-heading">
            <h4 class="panel-title">Nuevo curso</h4>
        </div>
        <div class="panel-body">
            
            <div class="row">

                
                    <div id="wizard">
                        <ol>
                            <li  id="showContent_1">
                                Datos del curso
                                <small>Establece la Información del curso.</small>
                            </li>
                            <li  id="showContent_2">
                                Temario
                                <small>Establece los temas que se estarán evaluando en el curso.</small>
                            </li>
                            <li  id="showContent_3">
                                Participantes
                                <small>Indican los puestos que tendrán que tomar el curso.</small>
                            </li>
                           
                        </ol>
                        <!-- begin wizard step-1 -->
                        <div>
                        
                            <fieldset>
                                <form id="formDatosNewCurso" data-parsley-validate="true" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class=" col-xs-12 col-md-8">
                                            <h4 class="pull-left width-full">Datos curso</h4>
                                        </div>
                                        <div class=" col-xs-12 col-md-4">
                                            <button id="btn-cancel_nuevo-curso" type="button" class="btn btn-danger m-r-5 m-b-5 btn-cancel_wizard" style="float: right;">Cancelar</button>
                                        </div>
                                        <div class=" col-xs-12 col-md-12"><hr style="width:100%;"></div>
                                    </div>
                            
                                    <div class="row">
                                        <div class="col-xs-4">
                                             <div class="col-xs-12">
                                                <img class="img-fluid" style="width:90%; margin-left:12px;"   src="/assets/img/user-12.jpg" alt="img-curso">
                                             </div>
                                             <div class="col-xs-12" style="text-align: center;  margin-top: 10px;">
                                                


                                                <label for="file-upload" class="subir btn" style="width:100%">
                                                    <i class="fas fa-cloud-upload-alt"></i> Subir archivo
                                                </label>
                                                <input id="file-upload" onchange='cambiar()' type="file" style='display: none;'/>
                                                <div id="info"></div>
                                            </div>
                                        </div>
                                        <div class="col-xs-8">
                                        
                                            <!-- begin row -->
                                            <div class="row">
                                                <!-- begin col-4 -->
                                                <div class=" col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label>Nombre del curso *</label>
                                                        <input type="text" id="nombreCurso" name="Nombre" placeholder="Nombre" class="form-control" data-parsley-required="true" />
                                                    </div>
                                                </div>
                                                <!-- end col-4 -->
                                                <!-- begin col-4 -->
                                                <div class=" col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label>Url *</label>
                                                        <input type="text" id="urlCurso" name="url" placeholder="http://" class="form-control" data-parsley-required="true"/>
                                                    </div>
                                                </div>
                                                <!-- end col-4 -->
                                                <!-- begin col-4 -->
                                                <div class=" col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="nuevoArchivo">Descripción *</label>
                                                        <textarea id="textareaDescripcionCurso" class="form-control" name="textareaDescripcionCurso" placeholder="Ingresa una descripción del curso" rows="6" data-parsley-required="true"/></textarea>
                                                    </div>
                                                </div>
                                                <!-- end col-4 -->
                                                <!-- begin col-4 -->
                                                <div class=" col-xs-12 col-md-6">
                                                <?php

                                                // var_dump($datos['certificados']);
                                                // var_dump($datos['tipoCursos']);
                                                

                                                ?>
                                                    <div class="form-group">
                                                        <label for="nuevoArchivo">Certificado </label>
                                                        <select id="certificadoCurso" class="form-control" style="width: 100%" data-parsley-required="true">
                                                            
                                                            <?php
                                                            var_dump($datos['certificados']);
                                                            foreach ($datos['certificados'] as $value) {
                                                               
                                                                    echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                                                                
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- end col-4 -->
                                                <!-- begin col-4 -->
                                                <div class=" col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label>Costo </label>
                                                        <input type="text" id="costoCurso" name="costo" placeholder="$00.00" class="form-control" />
                                                    </div>
                                                </div>
                                                <!-- end col-4 -->
                                            </div>
                                            <!-- end row -->
                                        </div>
                                    </div>
                                </form>
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
                                        <button id="btn-cancel_temario" type="button" class="btn btn-danger m-r-5 m-b-5 btn-cancel_wizard" style="float: right;">Cancelar</button>
                                    </div>
                                    <div class=" col-xs-12 col-md-12"><hr style="width:100%;"></div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-xs-9">
                                                <div class="form-group">
                                                    <label>Nombre del curso *</label>
                                                    <input type="text" id="nombreTemario" name="Nombre" placeholder="Nombre" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <button style="margin-top: 21px;" id="btn-agregar-nuevo-temario" type="button" class="btn btn-success m-r-5 m-b-5" style="float: right;"><i class="fa fa-plus"></i> Agregar</button>
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
                                            <button id="btn-loadExcel-temario" type="button" class="btn btn-success m-r-5 m-b-5" ><i class="fa fa-file"></i> Subir Excel</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="tabla-cursos-temario" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                            <td>Temario</td>
                                                            <td>Porcentaje</td>
                                                            <td>Acciones</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <div id="arrayTemario" style="display:none;"></div>
                                                            <?php
                                                            //  echo "welcome ".$_COOKIE['temarios'];
                                                            //  print_r($_COOKIE['temarios']);
                                                            
                                                            // foreach ($datos['temario'] as $value) {
                                                            //     echo '<tr>';
                                                            //     foreach ($value as $dato) {
                                                            //         echo '<td>' . $dato . '</td>';
                                                            //     }
                                                            //     echo '</tr>';
                                                            // }
                                                            ?>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
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
                                        <button id="btn-cancel_participantes" type="button" class="btn btn-danger m-r-5 m-b-5 btn-cancel_wizard" style="float: right;">Cancelar</button>
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
                                                        <?php
                                                            var_dump($datos['perfiles']);
                                                            foreach ($datos['perfiles'] as $value) {
                                                               
                                                                    echo '<option value="'.$value['Id'].'">'.$value['Nombre'].'</option>';
                                                                
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button style="margin-top: 21px;"  id="btn-nuevo-puestoParticipante" type="button" class="btn btn-success m-r-5 m-b-5" style="float: right;"><i class="fa fa-plus"></i> Agregar</button>
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
                                                    <table id="tabla-cursos-participantes" class="table  table-bordered">
                                                        <thead>
                                                            <tr>
                                                            <td>Puesto</td>
                                                            <td>Acciones</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            // foreach ($datos['filas'] as $value) {
                                                            //     echo '<tr>';
                                                            //     foreach ($value as $dato) {
                                                            //         echo '<td>' . $dato . '</td>';
                                                            //     }
                                                            //     echo '</tr>';
                                                            // }
                                                            ?>
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
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
               


            </div>


        </div>
    </div>
</div>
<!-- Finalizando #contenido -->


<!-- Empezando #contenido MODALS-->

<!-- subir temarios -->
<div id="modalSubirTemarios" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered " role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle" >Subir temarios</h5>
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

                    <div class="col-12">
                        <form action="#" method="post" enctype="multipart/form-data">

                            <input type="file" name="archivossubidos[]" >

                    </div>
                   
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white m-r-5 " id="cerrar" data-dismiss="modal" aria-label="Close"> Cerrar</a>
                    <a href="javascript:;" class="btn btn-primary m-r-5 " id="desPlantilla">Descargar plantilla</a>
                    <input type="submit"  class="btn btn-success m-r-5 " id="save" value="Subir plantilla">
              </div>
              </form>
              <div id="alertasGeocercas"></div>

          </div>
      </div>
    </div>

<!-- fin subir temarios -->

<!--  temarios por lo menos 1 -->
<div id="modalValidateTemario" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered " role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle" >Temarios</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body ">

                <div class="container">
                  <div class="row">
                   
                    <div class="col-11">
                        Debes registrar al menos un temario para poder continuar la creación del curso.
                    </div>
                   
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white m-r-5 " id="cerrar" data-dismiss="modal" aria-label="Close"> Cerrar</a>
              </div>
            

          </div>
      </div>
    </div>

<!-- fin  temarios 1-->


<!--  participantes por lo menos 1 -->
<div id="modalValidateParticipantes" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog-sm modal-dialog-centered " role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle" >Participantes</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body ">

                <div class="container">
                  <div class="row">
                   
                    <div class="col-11">
                        Debes registrar al menos un participante para poder continuar la creación del curso.
                    </div>
                   
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white m-r-5 " id="cerrar" data-dismiss="modal" aria-label="Close"> Cerrar</a>
              </div>
            

          </div>
      </div>
    </div>

<!-- fin participantes 1-->

<!--  guardar curso  -->
<div id="modalresponseSave" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog-sm modal-dialog-centered " role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle" >Nuevo curso</h5>
                  <button type="button" class="close btn-cancel_wizard" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body ">

                <div class="container">
                  <div class="row">
                   
                    <div class="col-11">
                        Se genero el curso {title} con éxito.
                    </div>
                   
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white m-r-5 btn-cancel_wizard" id="cerrar" data-dismiss="modal" aria-label="Close"> Cerrar</a>
              </div>
              

          </div>
      </div>
    </div>

<!-- fin guardar curso-->

<!-- FIN #contenido MODALS-->

<script>
function cambiar(){
    var pdrs = document.getElementById('file-upload').files[0].name;
    document.getElementById('info').innerHTML = pdrs;
    alert(pdrs)
}

function btnAdminEliminarCurso(id) {
      alert("ELLIMNAR",id);
      console.debug("ELLIMNAR",id);
      $("#idElementSeleccionAccion").val(id)
      $("#modalDeletoCursoAdmin").modal('show')
    }
</script>

<style>
.subir{
    padding: 5px 10px;
    background: #ffa500;
    color:#fff;
    border:0px solid #fff;
}
 
.subir:hover{
    color:#fff;
    background: #d8900c;
}

</style>


<!-- ver cursos -->

<div id="administracion-cursos-ver" class="content" style="display:none;">
    <!-- begin page-header -->
    <h1 class="page-header">Administración de Cursos</h1>
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
</div>
<!--  fin ver cursos -->


<!-- mdals eliminar cursos -->


<div id="modalDeletoCursoAdmin" class="modal fade " >
      <div class="modal-dialog " >
          <div class="modal-content">
              <div class="modal-header">
                 <div class="row">
                    <div class="col-sm-11" style="text-align: center;">
                        <span style="font-size: 16px;" class="modal-title" id="exampleModalCenterTitle" >
                        <i class='fa fa-exclamation-circle' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;' ></i> Eliminar curso</span>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="close btn-cancel_wizard" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                 </div>
              </div>
              <div class="modal-body ">

               
                  <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10" style="text-align: center;">
                        Al eliminar curso se borrara toda la información  de los participantes y los datos  del mismo, por lo que no se podrá recuperar.<br>
                        <b>¿Esta seguro de eliminar el curso?</b>
                    </div>
                    <div class="col-sm-1"></div>

                  
                   
                  </div>
                            

              </div>
              <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white m-r-5 btn-cancel_wizard " id="cerrar" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <a href="javascript:;" class="btn btn-danger m-r-5" id="EliminarCursoAdminConfirm" data-dismiss="modal" aria-label="Close">Eliminar</a>
              </div>
              

          </div>
      </div>
</div>

<!-- modals fin modal cursos -->

