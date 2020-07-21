
<div class="content">

    <!-- begin page-header -->
    <div class="row">
        <div class="col-md-8">
            <h1 class="page-header hidden"> <b>Curso </b> Gestión de proyectos.</h1>
            <h1 class="page-header">Administración de Cursos</h1>
        </div>
        <div class="col-md-4 hidden">
            <ol class="breadcrumb pull-right">
                <li><a class="btn-cancel_wizardEdit" href="javascript:;">Cursos</a></li>
                <li class="active">Gestión de proyectos</li>
            </ol>

        </div>
    </div>
    <!-- end page-header -->

    <!-- Empezando #seccion-cursos -->
    <div id="seccion-cursos">

        <div class="panel panel-inverse panel-cursos" data-sortable-id="ui-widget-1" >
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
                        <table id="tabla-cursos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <td class="never">Id</td>
                                    <td class="all">Nombre</td>
                                    <td class="all">Descripción</td>
                                    <td class="all">#Participantes</td>
                                    <td class="all">Estatus</td>
                                    <td class="all">Acciones</td>
                                </tr>
                            </thead>
                            <tbody>                                        
                                <?php
                                foreach ($datos['cursos'] as $value) {
                                    echo "<tr>";
                                    echo "<td>" . $value["Id"] . "</td>";
                                    echo "<td>" . $value["Nombre"] . "</td>";
                                    echo "<td>" . $value["Descripcion"] . "</td>";

                                    echo "<td>" . $value["Participantes"] . "</td>";
                                    $estado = "Activo";
                                    if ($value["Estatus"] == 0) {
                                        $estado = "Inactivo";
                                    }
                                    echo "<td>" . $estado . "</td>";
                                    echo "<td></td></tr>";
                                }
                                ?>
                            </tbody>

                        </table>

                    </div>
                </div>
                <!-- end tabla cursos -->
            </div>
        </div>
    </div>
    <!-- Finalizando #seccion-cursos-->

    <!-- Empezando #seccion-nuevo-curso -->
    <div id="seccion" class="hidden"></div>
    <!-- Finalizando #contenido -->
</div>




<!-- Empezando #contenido MODALS

 subir temarios 
<div id="modalSubirTemarios" class="modal fade" >
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle" >Subir temarios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body ">


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


                            <div class="modal-footer">
                                <a href="javascript:;" class="btn btn-white m-r-5 " id="cerrar" data-dismiss="modal" aria-label="Close"> Cerrar</a>
                                <a href="javascript:;" class="btn btn-primary m-r-5 " id="desPlantilla">Descargar plantilla</a>
                                <input type="submit"  class="btn btn-success m-r-5 " id="save" value="Subir plantilla">
                            </div>
                        </form>


                    </div>
                </div>
            </div>

             fin subir temarios 

              temarios por lo menos 1 
            <div id="modalValidateTemario" class="modal fade ">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle" >Temarios</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body ">


                            <div class="row">

                                <div class="col-11">
                                    Debes registrar al menos un temario para poder continuar la creación del curso.
                                </div>

                            </div>


                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white m-r-5 " id="cerrar" data-dismiss="modal" aria-label="Close"> Cerrar</a>
                        </div>


                    </div>
                </div>
            </div>

             fin  temarios 1


              participantes por lo menos 1 
            <div id="modalValidateParticipantes" class="modal fade ">
                <div class="modal-dialog" >
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

             fin participantes 1

              guardar curso  
            <div id="modalresponseSave" class="modal fade ">
                <div class="modal-dialog" >
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle" >Nuevo curso</h5>
                            <button type="button" class="close btn-cancel_wizard" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body ">

                            <div class="container">
                                <div class="row">

                                    <div class="col-11">
                                        Se genero el curso <b id="nameCurso"></b> con éxito.
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

             fin guardar curso

             FIN #contenido MODALS-->

            <script>
//                var eventoPagina = new Pagina();
//                var tablaListCursosVer = [];
//                tablaListCursosVer = new TablaBasica('tabla-cursosAsignados');
//                var tablaTemariosEdit = new TablaBasica('tabla-cursos-temarioEdit');
//                var tablaParticipantesEdit = new TablaBasica('tabla-cursos-participantesEdit');
//
//                function btnAdminEliminarCurso(id) {
//                    alert("ELLIMNAR", id);
//                    console.debug("ELLIMNAR", id);
//                    $("#idElementSeleccionAccion").val(id)
//                    $("#modalDeletoCursoAdmin").modal('show')
//                }
//
//                function btnAdminEditarCurso(id) {
//                    tablaTemariosEdit.iniciarTabla();
//                    tablaParticipantesEdit.iniciarTabla();
//                    tablaTemariosEdit.limpiartabla();
//                    tablaParticipantesEdit.limpiartabla();
//
//                    let evidenciaCursoEditar = new FileUpload_Boton('evidenciasEditarCurso', {
//                        url: 'Administracion_Cursos/Editar-Curso',
//                        extensiones: ['jpg', 'jpeg', 'png'],
//                        tituloAceptar: 'Agregar Archivos',
//                        colorBotonAceptar: 'btn btn-success'});
//                    evidenciaCursoEditar.iniciarFileUpload();
//
//                    $("#idElementSeleccionAccion").val(id)
//
//                    $('#modalSubirTemarios').modal('hide')
//                    $('#modalValidateTemario').modal('hide')
//                    $("#modalValidateParticipantes").modal('hide');
//
//                    $("#administracion-cursos").css('display', 'none')
//                    $("#administracion-cursos_nuevoCurso").css('display', 'none')
//                    $("#administracion-cursos-ver").css('display', 'none')
//
//                    $("#administracion-cursos-EDITAR").css('display', 'block')
//
//                    var datos = {
//                        idCurso: id
//                    };
//
//                    eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Obtener-Curso', datos, function (respuesta) {
//                        if (!respuesta.success) {
//                            evento.mostrarMensaje('.eventAccionEditarCurso', false, 'No se ha obtenido información del curso.', 5000);
//                            return;
//                        }
//
//                        let selectPuestoEditar = new SelectBasico('puestoEdit');
//                        selectPuestoEditar.cargaDatosEnSelect(respuesta.data.infoCurso.selectPuesto);
//
//                        var cursos = respuesta.data.infoCurso.curso;
//                        var perfiles = respuesta.data.infoCurso.perfiles;
//                        var temas = respuesta.data.infoCurso.temas;
//
//                        $('#inputImgCursoEdit').val(),
//                                $("#nombreCursoEdit").val(cursos.nombre),
//                                $("#urlCursoEdit").val(cursos.url),
//                                $("#textareaDescripcionCursoEdit").val(cursos.descripcion),
//                                $("#certificadoCursoEdit").val(cursos.idTipoCertificado),
//                                $("#costoCursoEdit").val(cursos.costo)
//
//                        if (respuesta.data.infoCurso.curso.imagen !== null) {
//                            let imagenCurso = respuesta.data.infoCurso.curso.imagen;
//                            $('#divEditarImagenCurso').attr('src', imagenCurso);
//                        }
//
//
//                        temas.forEach(element => {
//                            if (element.estatus != 0 && element.estatus != '0') {
//                                tablaTemariosEdit.agregarDatosFila([
//                                    element.nombre,
//                                    element.porcentaje + '%',
//                                    element.id,
//                                    element.idCurso,
//                                    "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn-AdminEliminarTemario'></i></spand>"
//                                ]);
//                            }
//                        });
//
//
//                        perfiles.forEach(element => {
//                            tablaParticipantesEdit.agregarDatosFila([
//                                element.id,
//                                element.idCurso,
//                                element.idPerfil,
//                                element.Nombre,
//                                "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarParticipant'></i></spand>"
//
//                            ]);
//                        });
//
//                        $("#btn-editarDatosSave").off("click");
//                        $("#btn-editarDatosSave").on('click', function (e) {
//                            var id = $("#idElementSeleccionAccion").val()
//                            var nombre = $("#nombreCursoEdit").val();
//                            var url = $("#urlCursoEdit").val();
//                            var descripcion = $("#textareaDescripcionCursoEdit").val();
//
//                            if (nombre == '' || url == '' || descripcion == '') {
//                                eventoPagina.mostrarMensaje('#eventAccionEditarCurso', false, 'Por favor acompleta los campos marcados con (*), que son obligatorios.', 3000);
//                                return false;
//                            }
//
//                            var json = {
//                                id: id,
//                                curso: [
//                                    $('#videnciasEditarCurso').val(),
//                                    $("#nombreCursoEdit").val(),
//                                    $("#urlCursoEdit").val(),
//                                    $("#textareaDescripcionCursoEdit").val(),
//                                    $("#certificadoCursoEdit").val(),
//                                    $("#costoCursoEdit").val()
//                                ]
//                            }
//
//                            $("#nameCurso").text($("#nombreCursoEdit").val());
//
//                            if ($('#evidenciasEditarCurso').val() !== '') {
//                                evidenciaCursoEditar.enviarPeticionServidor('evidenciasEditarCurso', json, function (respuesta) {
//                                    if (!respuesta.success) {
//                                        eventoPagina.mostrarMensaje('#eventAccionEditarCurso', false, 'No se ha editado el curso.', 5000);
//                                        return;
//                                    }
//                                });
//                            } else {
//                                eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Editar-Curso', json, function (respuesta) {
//                                    if (!respuesta.success) {
//                                        evento.mostrarMensaje('#eventAccionEditarCurso', false, 'No se ha editado el curso.', 5000);
//                                        return;
//                                    }
//                                });
//                            }
//
//                            $('#modalresponseSaveEdit').modal('show');
//                            location.reload();
//                        });
//                    });
//                }
//
//                function btnAdminVerCurso(id) {
//                    tablaListCursosVer.iniciarTabla();
//                    tablaListCursosVer.limpiartabla();
//
//                    if (id != 0) {
//                        $("#idElementSeleccionAccion").val(id)
//                    } else {
//                        id = $("#idElementSeleccionAccion").val()
//                    }
//
//                    $('#modalSubirTemarios').modal('hide')
//                    $('#modalValidateTemario').modal('hide')
//                    $("#modalValidateParticipantes").modal('hide');
//                    $("#modalDeletoCursoAdmin").modal('hide')
//
//                    $("#administracion-cursos").css('display', 'none')
//                    $("#administracion-cursos-verAvance").css('display', 'none')
//
//                    $("#administracion-cursos_nuevoCurso").css('display', 'none')
//
//                    $("#administracion-cursos-ver").css('display', 'block')
//                    $("#evidenciasVerAvance").css('display', 'block')
//                    $("#evidenciasVerAvanceTema").css('display', 'none')
//
//                    var json = {
//                        idCurso: id
//                    }
//
//                    eventoPagina.enviarPeticionServidor('tablaAsigCursos', 'Administracion_Cursos/Ver-Curso', json, function (respuesta) {
//                        if (!respuesta.success) {
//                            evento.mostrarMensaje('.eventAccionEditarCurso', false, 'No se ha obtenido el curso.', 5000);
//                            return;
//                        }
//
//                        var perfiles = respuesta.data.infoCurso.perticipantes;
//                        var total = respuesta.data.infoCurso.total;
//                        var avance = respuesta.data.infoCurso.avance;
//
//                        $("#avanceVerCurso").text(avance);
//                        $("#totalVerCurso").text(total);
//
//                        var tablaListCursosVer = []
//
//                        tablaListCursosVer = new TablaBasica('tabla-cursosAsignados');
//                        tablaListCursosVer.limpiartabla();
//
//
//                        perfiles.forEach(element => {
//                            var porcentaje = '0';
//                            if (element.Porcentaje != null && element.Porcentaje != '' && element.Porcentaje != 'null') {
//                                porcentaje = element.Porcentaje;
//                            }
//                            tablaListCursosVer.agregarDatosFila([
//                                element.nombreUsuario,
//                                element.Nombre,
//                                porcentaje + '%',
//                                element.Id,
//                                "<span><i class='fa fa-eye' style='cursor: pointer; margin: 5px; font-size: 17px;  color: #348fe2;'  id='btn-AdminVerCursoVerAvance'></i>Ver avances</spand>"
//
//                            ]);
//                        });
//                    });
//                }


            </script>

            <!-- ver cursos -->
<!--
            <div id="administracion-cursos-ver" class="content" style="display:none; margin-top:15px;">
                 begin page-header 
                <div class="row">
                    <div class="col-sm-8">
                        <h1 class="page-header"> <b>Curso </b> Gestión de proyectos.</h1>
                    </div>
                    <div class="col-sm-4">
                        <ol class="breadcrumb pull-right">
                            <li><a class="btn-cancel_wizardEdit" href="javascript:;">Cursos</a></li>
                            <li class="active">Avance del curso</li>
                        </ol>

                    </div>
                </div>
                 end page-header 
                <div class="panel panel-inverse" data-sortable-id="ui-widget-1" id="tablaAsigCursos">
                    <div class="panel-heading">
                        <h4 class="panel-title">Cursos</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="widget widget-stats bg-green">
                                            <div class="stats-icon"></div>
                                            <div class="stats-info">
                                                <h4>Avance general</h4>
                                                <p id="avanceVerCurso"></p>	
                                            </div>
                                            <div class="stats-link">
                                                <a href="javascript:;"></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xs-12">
                                        <div class="widget widget-stats bg-blue">
                                            <div class="stats-icon"></div>
                                            <div class="stats-info">
                                                <h4>Total de part.</h4>
                                                <p id="totalVerCurso"></p>	
                                            </div>
                                            <div class="stats-link">
                                                <a href="javascript:;"></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6" style="text-align: right;">

                                <b>En esta sección puedes ver el avance de cada uno de los participantes. <br>
                                    Para poder realizarlo solo tienes que dar clic sobre el icono ver avances.</b><br>
                            </div>

                        </div>

                    </div>

                     begin tabla cursos 
                    <div class="row" style="margin-top:50px;">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tabla-cursosAsignados" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <td class="all">Empleado</td>
                                            <td class="all">Puesto</td>
                                            <td class="all">Avance</td>
                                            <td class="never">id</td>
                                            <td class="all">Acciones</td>

                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                         end tabla cursos 
                    </div>
                </div>
            </div>


             ver avance 
            <div id="administracion-cursos-verAvance" class="content" style="display:none; margin-top:15px;">
                 begin page-header 
                <div class="row">
                    <div class="col-sm-8">
                        <h1 class="page-header"> <b>Curso </b> Gestión de proyectos.</h1>
                    </div>
                    <div class="col-sm-4">
                        <ol class="breadcrumb pull-right">
                            <li><a class="btn-cancel_wizardEdit" href="javascript:;">Cursos</a></li>
                            <li class="active" onclick='btnAdminVerCurso(0)' style="cursor: pointer;">Avance</li>
                            <li class="active">Detalles avance</li>
                        </ol>

                    </div>
                </div>
                 end page-header 
                <div  class="panel panel-inverse" data-sortable-id="ui-widget-1" >
                    <div class="panel-heading">
                        <h4 class="panel-title">Avance de participante</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="alertMessageAvance"></div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="widget widget-stats bg-green">
                                            <div class="stats-icon"></div>
                                            <div class="stats-info">
                                                <h4>Avance total</h4>
                                                <p id="avanceVerDCurso"></p>	
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
                                                <p id="faltanteVerDCurso"></p>	
                                            </div>
                                            <div class="stats-link">
                                                <a href="javascript:;"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4"></div>

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6" style="float:right;">

                                <b style="font-size:15px;">Participante </b><span style="font-size:13px;" id="cursoAvanceParticipante"> </span><br>
                                <b style="font-size:15px;">Curso </b><span style="font-size:13px;" id="cursoAvanceCurso"> </span><br>
                                <b style="font-size:15px;">Puesto </b><span style="font-size:13px;" id="cursoAvancePuesto"> </span>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <h4><b>Temario</b></h4>
                                <div class="underline m-b-15 m-t-15"></div>
                                <div class="table-responsive">
                                    <table id="tabla-temarioAvances" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="never">id</th>
                                                <th class="all">Modulo</th>
                                                <th class="all">Avance</th>
                                                <th class="all">Fecha</th>
                                                <th class="never">idAvance</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-7" id="evidenciasVerAvance" >
                                <h4><b>Evidencias</b></h4>
                                <div class="underline m-b-15 m-t-15"></div>
                                <p class="text-justify">                                        
                                    Realiza las siguientes instrucciones: <br><br>
                                    1.- En la tabla de temarios identifica el tema que deseas revisar. <br>
                                    2.- Da un clic sobre el renglón del tema para poder ver sus evidencias. <br>
                                    3.- Da clic sobre la evidencia para poder ampliarla.<br>

                                </p>

                            </div>

                            <div class="col-md-7" id="evidenciasVerAvanceTema" >
                                <h4><b>Evidencias</b></h4>
                                <div class="underline m-b-15 m-t-15"></div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="nuevoArchivo">Comentarios</label>
                                            <textarea id="comenarioEvidencias" class="form-control" name="comenarioEvidencias"  rows="6"  data-parsley-group="wizard-step-1" required /></textarea>
                                        </div>
                                    </div>

                                </div>

                                <div  id="CONTENT_IMG_EVIDENCIAS" <div id="gallery" class="row gallery">

                                    </div>




                                </div>



                            </div>

                            <div class="row" style="margin-top:20px; text-align: right;">
                                <div class="col-sm-12" >
                                    <button id="btn-regresarCurso" type="button" class="btn btn-white btn-sm m-r-5 m-b-5 btn-cancel_wizardEdit" style=" margin-top:5px;">Regresar a cursos</button>

                                    <button id="btn-regresarAvance" type="button" class="btn btn-primary btn-sm m-r-5 m-b-5 " onclick='btnAdminVerCurso(0)' style=" margin-top:5px;" >Regresar a avance</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
             ver avance 
              fin ver cursos 


              EDITAR curso 


            <div id="administracion-cursos-EDITAR" class="content" style="display:none; margin-top:15px;">
                 begin page-header 
                <div class="row">
                    <div class="col-sm-8">
                        <h1 class="page-header"> <b>Curso </b> Gestión de proyectos.</h1>
                    </div>
                    <div class="col-sm-4">
                        <ol class="breadcrumb pull-right">
                            <li><a class="btn-cancel_wizardEdit" href="javascript:;">Cursos</a></li>
                            <li class="active">Gestión de proyectos</li>
                        </ol>

                    </div>
                     end page-header 


                    <div class="col-sm-12" style="margin-top:15px;">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#default-tab-1" data-toggle="tab">Datos del curso</a></li>
                            <li class=""><a href="#default-tab-2" data-toggle="tab">Temario</a></li>
                            <li class=""><a href="#default-tab-3" data-toggle="tab">Participantes</a></li>
                            <div class=" col-xs-12 col-md-4 " style="float:right;">
                                <button id="btn-cancel_nuevo-curso" type="button" class="btn btn-primary btn-sm m-r-5 m-b-5 btn-cancel_wizardEdit" style="float: right; margin-top:5px;">Regresar a cursos</button>
                            </div>
                        </ul>
                        <div class="tab-content">
                            <div id="eventAccionEditarCurso"></div>
                            <div class="tab-pane fade active in" id="default-tab-1" >
                                <form id="formDatosNewCursoEdit" data-parsley-validate="true" enctype="multipart/form-data">
                                    <div class="row"  style="margin:40px 45px;">
                                        <div class="col-xs-4">
                                             <div class="col-xs-12">
                                            <img class="img-fluid" style="width:90%; margin-left:12px;"   src="/assets/img/user-12.jpg" alt="img-curso">
                                            </div> 
                                            <div class="col-xs-12" style="text-align: center;  margin-top: 10px;">

                                                <div class="profile-image text-center">
                                                    <img id="divEditarImagenCurso" src="" alt="" />
                                                </div>

                                                <div id="archivo" class="form-group hidden">
                                                    <input id="evidenciasEditarCurso" name="evidenciasEditarCurso[]" type="file" multiple >
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-xs-8">

                                             begin row 
                                            <div class="row">
                                                 begin col-4 
                                                <div class=" col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label>Nombre del curso *</label>
                                                        <input disabled type="text" id="nombreCursoEdit" name="Nombre" placeholder="Nombre" class="form-control" data-parsley-required="true" />
                                                    </div>
                                                </div>
                                                 end col-4 
                                                 begin col-4 
                                                <div class=" col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label>Url *</label>
                                                        <input disabled type="text" id="urlCursoEdit" name="url" placeholder="http://" class="form-control" data-parsley-required="true"/>
                                                    </div>
                                                </div>
                                                 end col-4 
                                                 begin col-4 
                                                <div class=" col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="nuevoArchivo">Descripción *</label>
                                                        <textarea disabled id="textareaDescripcionCursoEdit" class="form-control" name="textareaDescripcionCurso" placeholder="Ingresa una descripción del curso" rows="6" data-parsley-required="true"/></textarea>
                                                    </div>
                                                </div>
                                                 end col-4 
                                                 begin col-4 
                                                <div class=" col-xs-12 col-md-6">
                                                    <?php
                                                    // var_dump($datos['certificados']);
                                                    // var_dump($datos['tipoCursos']);
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="nuevoArchivo">Certificado </label>
                                                        <select disabled id="certificadoCursoEdit" class="form-control" style="width: 100%" data-parsley-required="true">

                                                            <?php
                                                            var_dump($datos['certificados']);
                                                            foreach ($datos['certificados'] as $value) {

                                                                echo '<option value="' . $value['id'] . '">' . $value['nombre'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                 end col-4 
                                                 begin col-4 
                                                <div class=" col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label>Costo </label>
                                                        <input disabled type="text" id="costoCursoEdit" name="costo" placeholder="$00.00" class="form-control" />
                                                    </div>
                                                </div>
                                                 end col-4 
                                            </div>
                                             end row 
                                        </div>

                                        <div class="col-sm-12">
                                            <button style="margin-top: 21px; float: right;"  id="btn-editarDatosStatus" type="button" class="btn btn-success m-r-5 m-b-5" >
                                                Editar datos</button>
                                            <button style="margin-top: 21px; float: right; display:none;"  id="btn-cancelar-cambios" type="button" class="btn btn-white m-r-5 m-b-5" >
                                                Cancelar cambios</button>
                                            <button style="margin-top: 21px; float: right; display:none;"  id="btn-editarDatosSave" type="button" class="btn btn-success m-r-5 m-b-5" >
                                                <i class="fa fa-save"></i> Editar datos</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="default-tab-2">
                                <div class="row" style="margin:40px;">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-xs-9">
                                                <div class="form-group">
                                                    <label>Nombre del modulo </label>
                                                    <input type="text" id="nombreTemarioEdit" name="Nombre" placeholder="Nombre" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <button style="margin-top: 21px;" id="btn-agregar-nuevo-temarioEdit" type="button" class="btn btn-success m-r-5 m-b-5" style="float: right;"><i class="fa fa-plus"></i> Agregar</button>
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
                                                 <button id="btn-loadExcel-temarioEDit" type="button" class="btn btn-success m-r-5 m-b-5" ><i class="fa fa-file"></i> Subir Excel</button> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="tabla-cursos-temarioEdit" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th class="all">Temario</th>
                                                                <th class="all">Porcentaje</th>
                                                                <th class="never">id</th>
                                                                <th class="never">idCurso</th>
                                                                <th class="all">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

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

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="default-tab-3">
                                <div class="row"  style="margin:40px 45px;">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="puestoEdit">Puesto </label>
                                                    <select id="puestoEdit" class="form-control" style="width: 100%" data-parsley-required="true">
                                                        <option value="">Seleccionar</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button style="margin-top: 21px;"  id="btn-nuevo-puestoParticipanteEdit" type="button" class="btn btn-success m-r-5 m-b-5" style="float: right;"><i class="fa fa-plus"></i> Agregar</button>
                                            </div>

                                            <div class="col-xs-12">
                                                Indica los puestos que deben tomar el curso.<br>
                                                Cuando generes el curso el sistema notificará por correo al personal que cubra el 
                                                puesto que esta asignado en el curso y también le aparecerá en la sección de CURSOS ASIGNADOS.

                                                <br><br>
                                                <b>Nota: Es importante que se deba definir al menos un puesto. </b>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="tabla-cursos-participantesEdit"  class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th class="never">id</th>
                                                                <th class="never">idCurso</th>
                                                                <th class="never">idP</th>
                                                                <th class="all">Puesto</th>
                                                                <th class="all">Acciones</th>
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

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

              modal editar curso  
            <div id="modalresponseSaveEdit" class="modal fade ">
                <div class="modal-dialog" >
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle" >Datos del curso</h5>
                            <button type="button" class="close btn-cancel_wizard" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body ">

                            <div class="container">
                                <div class="row">

                                    <div class="col-11">
                                        Se cambiaron los datos del curso con éxito.<br>
                                        <i class="fa fa-check-circle" style="color:#00acac;"></i>
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

             fin modal edit curso

             fin EDITAR curso 


             mdals eliminar cursos 


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

             modals fin modal cursos -->

