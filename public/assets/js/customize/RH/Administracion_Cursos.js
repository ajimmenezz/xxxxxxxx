$(function () {

    var evento = new Base();
    let nuevoCurso = new NuevoCurso();
    let editarCurso = new EditarCurso();

    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    App.init();

    let secciones = {};

    Helper.enviarPeticionServidor('panel-cursos', 'Administracion_Cursos/Secciones-Admintrador-Cursos/', {}, function (respond) {
        secciones.nuevoCurso = respond.NuevoCurso;
        secciones.editarCurso = respond.EditarCurso;
    });

    // <editor-fold desc="Seccion Cursos"> 
    let botonesFilaCursos = [
        {
            targets: 5,
            data: null,
            render: function (data, type, row, meta) {
                return `
                        <i class='fa fa-eye show-avances' style='cursor: pointer; margin: 5px; font-size: 17px;  color: #348fe2;' "></i>
                        <i class='fa fa-pencil edit-curso' style='cursor: pointer; margin: 5px; font-size: 17px; color: orange;' "></i>
                        <i class='fa fa-trash delete-curso' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;' "></i>`;
            }
        }
    ];

    let configTablaCursos = {columnas: botonesFilaCursos};
    let tablaCursos = new TablaRender('tabla-cursos', [], configTablaCursos);


    $('#btn-nuevo-curso').on('click', function (e) {
        Helper.ocultarElemento('seccion-cursos');
        Helper.agregarElemento('seccion', secciones.nuevoCurso);
        Helper.mostrarElemento('seccion');
        nuevoCurso.init(tablaCursos);
        nuevoCurso.events();
    });

    tablaCursos.addListenerOnclik('.show-avances', function (dataRow, fila) {
        console.log('mostrar row id:' + dataRow[0]);
    });

    tablaCursos.addListenerOnclik('.edit-curso', function (dataRow, fila) {
        console.log('Editar row id:' + dataRow[0]);
        Helper.ocultarElemento('seccion-cursos');
        Helper.agregarElemento('seccion', secciones.editarCurso);
        Helper.mostrarElemento('seccion');
        editarCurso.init(dataRow[0]);
        editarCurso.events();
    });

    tablaCursos.addListenerOnclik('.delete-curso', function (dataRow, fila) {
        console.log('Borar row id:' + dataRow[0]);
    });
    // </editor-fold> 

//    $('#btn-adminVerCurso').on('click', function (e) {
//
//
//    });
//
//    $('#btn-adminEditarCurso').on('click', function (e) {
//
//    });
//
//
//    $("#EliminarCursoAdminConfirm").on('click', function (e) {
//        console.debug('clic en boton elimnar curso confirmacion');
//        var curso = $("#idElementSeleccionAccion").val();
//        let datos = {idCurso: curso};
//        eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Eliminar-Curso', datos, function (respuesta) {
//            console.debug(respuesta);
//            evento.mostrarMensaje('.messageAcciones', true, 'Se ha eliminado el curso.', 5000);
//            location.reload();
//        });
//    });
//
//
//
//
//    $("#wizard").bwizard();
//
//
//
//    $("#wizard").bwizard({validating: function (e, ui) {
//            let datosTabla = tablaTemarios.datosTabla();
//            let datosTabla2 = tablaParticipantes.datosTabla();
//
//
//            if (ui.index == 0) {
//                // step-1 validation
//                if (false === $('form[name="form-wizard"]').parsley().validate('wizard-step-1')) {
//                    return false;
//                }
//            } else if (ui.index == 1) {
//                // step-2 validation
//                // if (false === $('form[name="form-wizard"]').parsley().validate('wizard-step-2')) {
//                //     return false;
//                // }
//                if (datosTabla.length <= 0) {
//                    $('#modalValidateTemario').modal('show')
//                    return false;
//                }
//            } else if (ui.index == 2) {
//                // step-3 validation
//                // if (false === $('form[name="form-wizard"]').parsley().validate('wizard-step-3')) {
//                //     return false;
//                // }
//                if (datosTabla2.length <= 0) {
//                    $('#modalValidateParticipantes').modal('show')
//                    return false;
//                }
//            }
//        }
//    });
//
//    $(".btn-cancel_wizard").on('click', function (e) {
//        //modalSubirTemarios
//        console.log("cancelar wizard")
//        $('#modalSubirTemarios').modal('hide')
//        $('#modalValidateTemario').modal('hide')
//        $("#modalValidateParticipantes").modal('hide');
//        $("#administracion-cursos-ver").css('display', 'none')
//        $("#administracion-cursos-verAvance").css('display', 'none')
//        $("#administracion-cursos-EDITAR").css('display', 'none')
//
//        $("#administracion-cursos").css('display', 'block')
//        $("#administracion-cursos_nuevoCurso").css('display', 'none')
//
//    });
//
//    $(".btn-cancel_wizardEdit").on('click', function (e) {
//        //modalSubirTemarios
//        console.log("cancelar wizardEdit")
//        $('#modalSubirTemarios').modal('hide')
//        $('#modalValidateTemario').modal('hide')
//        $("#modalValidateParticipantes").modal('hide');
//
//
//        $("#administracion-cursos_nuevoCurso").css('display', 'none')
//        $("#administracion-cursos-ver").css('display', 'none')
//        $("#administracion-cursos-verAvance").css('display', 'none')
//        $("#administracion-cursos-EDITAR").css('display', 'none')
//        $("#administracion-cursos").css('display', 'block')
//
//    });
//
//
//
//    let listTemario = []
//
//    $('#btn-agregar-nuevo-temario').on('click', function (e) {
//
//        console.log("btn-agregar-nuevo-temario")
//        $nombreTemario = $("#nombreTemario").val();
//        if ($nombreTemario !== "") {
//
//            let numItemsTemario = tablaTemarios.datosTabla();
//            $filas_num = numItemsTemario.length;
//            let datos = tablaTemarios.datosFila(this);
//
//
//            console.debug(datos, "DATOS TABLA TEMARIOS", numItemsTemario, $filas_num, datos);
//
//            $long = listTemario.length + 1;
//            $porcentaje = (100 / $long).toFixed(2);
//
//            console.debug($nombreTemario, $porcentaje, "DATOS tEMARIO1", listTemario)
//            listTemario.push({'nombre': $nombreTemario, 'porcentaje': $porcentaje});
//
//
//            tablaTemarios.limpiartabla();
//
//            listTemario.forEach(element => {
//                element.porcentaje = $porcentaje;
//                tablaTemarios.agregarDatosFila([
//                    element.nombre,
//                    element.porcentaje + '%',
//                    "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarTemario'></i></spand>"
//
//                ]);
//            });
//
//
//
//
//            console.debug($nombreTemario, $porcentaje, "DATOS tEMARIO2", listTemario)
//            $("#nombreTemario").val("");
//        }
//    });
//
//    tablaTemarios.evento(function () {
//        let numItemsTemario = tablaTemarios.datosTabla();
//        var elim = tablaTemarios.eliminarFila(this);
//        let datosTabla = tablaTemarios.datosTabla();
//
//        console.debug(numItemsTemario, "eliminar", elim, "resto", datosTabla, tablaTemarios.datosFila(this));
//
//        listTemario = []
//
//        if (datosTabla.length !== 0) {
//            $long = datosTabla.length;
//            $porcentaje = (100 / $long).toFixed(2);
//            let datos = tablaTemarios.datosFila(this);
//            var info = $('#tabla-cursosPrinc').DataTable().rows({search: 'applied'}).data();
//            // datos.forEach(element => {
//            //   listTemario.push({'nombre':element.nombre,'porcentaje':$porcentaje});
//            // });
//            console.debug(datosTabla, "ENTRE FOR", datosTabla.length, datos, info)
//
//            for (let index = 0; index < datosTabla.length; index++) {
//                const element = datosTabla[index];
//                console.debug("DATOS", element, element[0])
//
//                listTemario.push({'nombre': element[0], 'porcentaje': $porcentaje});
//
//            }
//        }
//
//        tablaTemarios.limpiartabla();
//
//        listTemario.forEach(element => {
//
//            tablaTemarios.agregarDatosFila([
//                element.nombre,
//                element.porcentaje + '%',
//                "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarTemario'></i></spand>"
//
//            ]);
//        });
//
//        console.debug("FINAL", listTemario)
//
//
//    });
//
//    let listTemarioEdit = []
//
//    $('#btn-agregar-nuevo-temarioEdit').on('click', function (e) {
//        let nombreTemario = $("#nombreTemarioEdit").val();
//        if (nombreTemario !== "") {
//            let datosTabla = tablaTemariosEdit.datosTabla();
//            let filas_num = datosTabla.length;
//            let datos = tablaTemariosEdit.datosFila(this);
//            var idCurso = $("#idElementSeleccionAccion").val();
//            let long = datosTabla.length + 1;
//            let porcentaje = (100 / long).toFixed(2);
//
//            listTemarioEdit = []
//            for (let index = 0; index < datosTabla.length; index++) {
//                const element = datosTabla[index];
//                listTemarioEdit.push({'nombre': element[0], 'porcentaje': porcentaje, 'id': element[2], 'idCurso': element[3]});
//            }
//
//            var json = {
//                tipoDato: 1,
//                idCurso: idCurso,
//                nombre: nombreTemario,
//                porcentaje: porcentaje,
//                descripcion: ''
//            }
//
//            eventoPagina.enviarPeticionServidor('administracion-cursos-EDITAR', 'Administracion_Cursos/Agregar-ElementoCurso', json, function (respuesta) {
//                if (!respuesta.success) {
//                    evento.mostrarMensaje('.eventAccionEditarCurso', false, 'No se ha registrado el tema.', 5000);
//                    return;
//                }
//
//                listTemarioEdit.push({'nombre': nombreTemario, 'porcentaje': porcentaje, 'id': respuesta.data.id, 'idCurso': idCurso});
//                tablaTemariosEdit.limpiartabla();
//                listTemarioEdit.forEach(element => {
//                    element.porcentaje = porcentaje;
//                    tablaTemariosEdit.agregarDatosFila([
//                        element.nombre,
//                        element.porcentaje + '%',
//                        element.id,
//                        element.idCurso,
//                        "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarTemario'></i></spand>"
//
//                    ]);
//                });
//            });
//
//            $("#nombreTemarioEdit").val("");
//        } else {
//            evento.mostrarMensaje('#eventAccionEditarCurso', false, 'No se ha registrado el modulo.', 5000);
//        }
//    });
//
//
//
//    tablaTemariosEdit.evento(function () {
//        let numItemsTemario = tablaTemariosEdit.datosTabla();
//        let datos = tablaTemariosEdit.datosFila(this);
//        let elim = tablaTemariosEdit.eliminarFila(this);
//        let datosTabla = tablaTemariosEdit.datosTabla();
//
//        if (numItemsTemario.length != 0) {
//
//            var json = {
//                tipoDato: 1,
//                idCurso: datos[3],
//                id: datos[2]
//            }
//
//            eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Eliminar-ElementoCurso', json, function (respuesta) {
//                if (!respuesta.success) {
//                    evento.mostrarMensaje('.eventAccionEditarCurso', false, 'No se ha eliminado el tema.', 5000);
//                    return;
//                }
//
//                listTemarioEdit = []
//
//                if (datosTabla.length >= 0) {
//                    let long = datosTabla.length;
//                    let porcentaje = (100 / long).toFixed(2);
//
//                    for (let index = 0; index < datosTabla.length; index++) {
//                        const element = datosTabla[index];
//                        listTemarioEdit.push({'nombre': element[0], 'porcentaje': porcentaje, 'id': element[2], 'idCurso': element[3]});
//                    }
//                }
//
//                tablaTemariosEdit.limpiartabla();
//
//                listTemarioEdit.forEach(element => {
//
//                    tablaTemariosEdit.agregarDatosFila([
//                        element.nombre,
//                        element.porcentaje + '%',
//                        element.id,
//                        element.idCurso,
//                        "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarTemario'></i></spand>"
//
//                    ]);
//                });
//            });
//        }
//    });
//
//
//    $("#file-upload-button").addClass("btn btn-success m-r-5 ");
//
//
//
//    let listPuesto = [];
//    let selectPartic = new SelectBasico('puesto');
//    
//    $("#btn-nuevo-puestoParticipante").on('click', function (e) {
//        //modalSubirTemarios
//        console.log("btn-nuevo-puestoParticipante")
//
//        $nombrePuesto = selectPartic.obtenerValor()
//        $nombrePuestoString = selectPartic.obtenerTexto()
//
//        console.debug("stirng", $nombrePuestoString)
//
//        if ($nombrePuesto !== "") {
//            let numItemsTemario = tablaParticipantes.datosTabla();
//            $filas_num = numItemsTemario.length;
//            let datos = tablaParticipantes.datosFila(this);
//
//
//
//
//            console.debug($nombrePuesto, "DATOS tEMARIO1", listPuesto)
//            listPuesto.push({'nombre': $nombrePuesto, 'nameString': $nombrePuestoString});
//
//            tablaParticipantes.limpiartabla();
//
//
//            listPuesto.forEach(element => {
//                tablaParticipantes.agregarDatosFila([
//                    element.nombre,
//                    element.nameString,
//                    "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarParticipant'></i></spand>"
//
//                ]);
//
//            });
//
//
//
//
//
//            console.debug($nombrePuesto, "DATOS tEMARIO2", listPuesto)
//            $("#puesto").val("");
//        }
//
//    });
//
//    tablaParticipantes.evento(function () {
//        let numItemsTemario = tablaParticipantes.datosTabla();
//
//        let datosElemento = tablaParticipantes.datosFila(this);
//        console.debug("DATO_ESPECIFICO", datosElemento);
//        var elim = tablaParticipantes.eliminarFila(this);
//        let datosTabla = tablaParticipantes.datosTabla();
//
//        console.debug(numItemsTemario, "eliminar", elim, "resto", datosTabla, tablaParticipantes.datosFila(this));
//
//        listPuesto = []
//
//        if (datosTabla.length !== 0) {
//
//            let datos = tablaParticipantes.datosFila(this);
//            var info = $('#tabla-cursos-temario').DataTable().rows({search: 'applied'}).data();
//
//            console.debug(datosTabla, "ENTRE FOR", datosTabla.length, datos, info)
//
//            for (let index = 0; index < datosTabla.length; index++) {
//                const element = datosTabla[index];
//                console.debug("DATOS", element, element[0])
//
//                listPuesto.push({'nombre': element[0], 'nameString': element[1]});
//
//            }
//        }
//
//        tablaParticipantes.limpiartabla();
//
//        listPuesto.forEach(element => {
//
//            tablaParticipantes.agregarDatosFila([
//                element.nombre,
//                element.nameString,
//                "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarParticipant'></i></spand>"
//
//            ]);
//        });
//
//        console.debug("FINAL", listPuesto)
//
//
//    });
//
//
//
//    tablaParticipantesEdit = new TablaBasica('tabla-cursos-participantesEdit');
//
//
//    let listPuestoEdit = [];
//
//    $('#btn-nuevo-puestoParticipanteEdit').off("click");
//    $("#btn-nuevo-puestoParticipanteEdit").on('click', function (e) {
//        let nombrePuesto = selectPart.obtenerValor()
//        let nombrePuestoString = selectPart.obtenerTexto()
//
//        if (nombrePuesto !== "") {
//            let datosTabla = tablaParticipantesEdit.datosTabla();
//            let filas_num = datosTabla.length;
//            let datos = tablaParticipantesEdit.datosFila(this);
//            let listPuestoEdit = [];
//            var idCurso = $("#idElementSeleccionAccion").val();
//            var perfiles = $('#perfiles').val()
//
//            for (let index = 0; index < datosTabla.length; index++) {
//                const element = datosTabla[index];
//                listPuestoEdit.push({'nombre': element[2], 'nameString': element[3], 'id': element[0], 'idCurso': element[1]});
//            }
//
//            var json = {
//                tipoDato: 0,
//                idCurso: idCurso,
//                idPerfil: nombrePuesto
//            }
//
//            eventoPagina.enviarPeticionServidor('administracion-cursos-EDITAR', 'Administracion_Cursos/Agregar-ElementoCurso', json, function (respuesta) {
//                if (!respuesta.success) {
//                    evento.mostrarMensaje('#eventAccionEditarCurso', false, 'No se ha registrado el participante.', 5000);
//                    return;
//                }
//
//                listPuestoEdit.push({'nombre': nombrePuesto, 'nameString': nombrePuestoString, 'id': respuesta.data.id, 'idCurso': idCurso});
//
//                tablaParticipantesEdit.limpiartabla();
//
//                listPuestoEdit.forEach(element => {
//                    tablaParticipantesEdit.agregarDatosFila([
//                        element.id,
//                        element.idCurso,
//                        element.nombre,
//                        element.nameString,
//                        "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarParticipant'></i></spand>"
//                    ]);
//                });
//            });
//            $("#puestoEdit").val("");
//        } else {
//            evento.mostrarMensaje('#eventAccionEditarCurso', false, 'Debe seleccionar un puesto.', 5000);
//        }
//    });
//
//
//
//
//    tablaParticipantesEdit.evento(function () {
//
//        let numItemsTemario = tablaParticipantesEdit.datosTabla();
//        let datos = tablaParticipantesEdit.datosFila(this);
//        let elim = tablaParticipantesEdit.eliminarFila(this);
//        let datosTabla = tablaParticipantesEdit.datosTabla();
//
//
//        console.debug("resto", datos, elim, "antes de ELIMANR", numItemsTemario, numItemsTemario.length, "eliminar_FIN", datosTabla, datosTabla.length, tablaParticipantesEdit.datosFila(this));
//
//        if (numItemsTemario.length != 0) {
//
//            var json = {
//                tipoDato: 0,
//                idCurso: datos[1],
//                id: datos[0]
//            }
//
//
//
//            eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Eliminar-ElementoCurso', json, function (respuesta) {
//                console.log("eliminarPART_EDIT", respuesta);
//                if (!respuesta.success) {
//                    evento.mostrarMensaje('.eventAccionEditarCurso', false, 'No se ha eliminado el participante.', 5000);
//                    return;
//                }
//
//
//                console.debug("FILA_ELIMINAR", elim);
//
//
//                listPuestoEdit = []
//
//
//                if (datosTabla.length >= 0) {
//
//                    console.debug(datosTabla, "ENTRE FOR", datosTabla.length, datos)
//
//
//                    for (let index = 0; index < datosTabla.length; index++) {
//                        const element = datosTabla[index];
//                        console.debug("DATOS", element, element[0])
//
//                        listPuestoEdit.push({'nombre': element[2], 'nameString': element[3], 'id': element[0], 'idCurso': element[1]});
//                    }
//
//                }
//
//                console.debug("ERREGLO", listPuestoEdit);
//
//
//                tablaParticipantesEdit.limpiartabla();
//
//                listPuestoEdit.forEach(element => {
//
//                    tablaParticipantesEdit.agregarDatosFila([
//                        element.id,
//                        element.idCurso,
//                        element.nombre,
//                        element.nameString,
//                        "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarParticipant'></i></spand>"
//
//                    ]);
//                });
//                console.debug("Areeglo_FIN", listPuestoEdit);
//
//            });
//        }
//
//
//
//
//
//    });
//    var tablaListCursosVer = new TablaBasica('tabla-cursosAsignados');
//
//    //ver curso
//    tablaListCursosVer.evento(function () {
//        let datosTabla = tablaListCursosVer.datosTabla();
//
//        if (datosTabla.length !== 0) {
//            let datosElemento = tablaListCursosVer.datosFila(this);
//
//            var json = {
//                idCurso: $("#idElementSeleccionAccion").val(),
//                idUsuario: datosElemento[3]
//            }
//
//            eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/TemasCursoUsuario', json, function (respuesta) {
//                if (!respuesta.success) {
//                    evento.mostrarMensaje('.eventAccionEditarCurso', false, 'No se ha eliminado el participante.', 5000);
//                    return;
//                }
//
////                let tablaListemasAvance = new TablaBasica('tabla-temarioAvances');
//                var temas = respuesta.data.infoUsuario.temas;
//                var datosUserInfo = respuesta.data.infoUsuario.infoUsuario[0];
//
//                tablaListemasAvance.limpiartabla();
//
//
//                for (var index in temas) {
//                    const element = temas[index];
//                    var idAvance = -1;
//                    var fecha = '-';
//
//                    if (element.idAvance) {
//                        idAvance = element.idAvance;
//                    }
//
//                    if (element.fechaModificacion) {
//                        fecha = element.fechaModificacion;
//                    }
//
//                    tablaListemasAvance.agregarDatosFila([
//                        element.id,
//                        element.nombre,
//                        element.porcentaje + '%',
//                        fecha,
//                        idAvance
//                    ]);
//                }
//
//                $("#cursoAvanceParticipante").text(datosUserInfo.Nombre);
//                $("#cursoAvancePuesto").html(datosUserInfo.Perfil);
//                $("#cursoAvanceCurso").text(respuesta.data.infoUsuario.infoCurso[0].nombre);
//                $("#administracion-cursos_nuevoCurso").css('display', 'none')
//                $("#administracion-cursos-ver").css('display', 'none')
//                $("#administracion-cursos-verAvance").css('display', 'block')
//                $("#administracion-cursos-EDITAR").css('display', 'none')
//                $("#administracion-cursos").css('display', 'none')
//                $("#evidenciasVerAvanceTema").css('display', 'none')
//            });
//        }
//    });
//
//
////    var tablePrinc = new TablaBasica('tabla-cursosPrinc');
//
//    tablaListemasAvance.evento(function () {
//        $('#modalSubirTemarios').modal('hide')
//        $('#modalValidateTemario').modal('hide')
//        $("#modalValidateParticipantes").modal('hide');
//
//        $("#administracion-cursos_nuevoCurso").css('display', 'none')
//        $("#administracion-cursos-ver").css('display', 'none')
//        $("#administracion-cursos-verAvance").css('display', 'block')
//        $("#administracion-cursos-EDITAR").css('display', 'none')
//        $("#administracion-cursos").css('display', 'none')
//        $("#evidenciasVerAvance").css('display', 'block')
//        $("#evidenciasVerAvanceTema").css('display', 'none')
//
//        let datosTabla = tablaListemasAvance.datosTabla();
//
//        if (datosTabla.length !== 0) {
//            let datosElemento = tablaListemasAvance.datosFila(this);
//
//            if (datosElemento[4] != -1) {
//                $('#modalSubirTemarios').modal('hide')
//                $('#modalValidateTemario').modal('hide')
//                $("#modalValidateParticipantes").modal('hide');
//
//                $("#administracion-cursos_nuevoCurso").css('display', 'none')
//                $("#administracion-cursos-ver").css('display', 'none')
//                $("#administracion-cursos-verAvance").css('display', 'block')
//                $("#administracion-cursos-EDITAR").css('display', 'none')
//                $("#administracion-cursos").css('display', 'none')
//                $("#evidenciasVerAvance").css('display', 'none')
//                $("#evidenciasVerAvanceTema").css('display', 'block')
//
//                var json = {
//                    idAvance: datosElemento[4]
//                }
//
//                eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Ver-Evidencias', json, function (respuesta) {
//                    if (!respuesta.success) {
//                        evento.mostrarMensaje('.alertMessageAvance', false, 'No se han obtenido las evidencias del tema.', 5000);
//                        return;
//                    }
//
//                    var datosG = respuesta.data.avance
//                    var datos = respuesta.data.avance[0]
//                    var texto = '';
//
//                    $("#comenarioEvidencias").text(datos.comentarios);
//
//                    datosG.forEach(datos => {
//                        texto += `<div class="image gallery-group-1 col-xs-12 col-md-4" style="width: 170px; height: 230px; text-align: center;">
//                                    <div class="image-inner">
//                                        <a href="${datos.url}" data-lightbox="gallery-group-1">
//                                            <img src="${datos.url}" alt="" style="width: 120px; height: 100px;"/>
//                                        </a>
//                                        
//                                    </div>
//                                    <div class="image-info">
//                                        <h5 class="title">${datos.fechaModificacion}</h5>
//                                        
//                                        <div class="desc">
//                                        <b>Comentarios</b><br>
//                                        ${datos.comentarios}
//                                        </div>
//                                    </div>
//                                </div>
//                        `;
//                    });
//
//                    $("#CONTENT_IMG_EVIDENCIAS").html(texto);
//                });
//            } else {
//                evento.mostrarMensaje('.alertMessageAvance', false, 'No hay evidencias del tema.', 4000);
//            }
//        }
//    });
//
//
//
//
//    $("#btn-save-curso").on('click', function (e) {
//        var temas = [];
//        var part = [];
//        var nombre = $("#nombreCurso").val();
//        var url = $("#urlCurso").val();
//        var descripcion = $("#textareaDescripcionCurso").val();
//        $("#nameCurso").text($("#nombreCurso").val());
//
//        if (nombre == '' || url == '' || descripcion == '') {
//            evento.mostrarMensaje('.messageAccionesWizard', false, 'Por favor acompleta los campos marcados con (*), que son obligatorios.', 3000);
//            return false;
//        }
//
//        let datosTabla = tablaTemarios.datosTabla();
//        let datosTabla2 = tablaParticipantes.datosTabla();
//
//        if (datosTabla.length <= 0) {
//            $('#modalValidateTemario').modal('show')
//            return false;
//        }
//
//        if (datosTabla2.length <= 0) {
//            $('#modalValidateParticipantes').modal('show')
//            return false;
//        }
//
//        var cursos = [
//            $('#evidencias').val(),
//            $("#nombreCurso").val(),
//            $("#urlCurso").val(),
//            $("#textareaDescripcionCurso").val(),
//            $("#certificadoCurso").val(),
//            $("#costoCurso").val()
//        ];
//
//        var json = {
//            cursos: cursos
//        };
//
//
//        for (let index = 0; index < datosTabla.length; index++) {
//            const element = datosTabla[index];
//            temas.push([element[0], '', parseFloat(element[1]), '/']);
//        }
//
//        json.temario = temas;
//
//        for (let index = 0; index < datosTabla2.length; index++) {
//            const element = datosTabla2[index];
//            part.push([element[0]]);
//        }
//
//        json.participantes = part;
//
//        if ($('#inputImgCurso').val() !== '') {
//            evidenciaCurso.enviarPeticionServidor('evidencias', json, function (respuesta) {
//                if (!respuesta.success) {
//                    evento.mostrarMensaje('.messageAccionesWizard', false, 'No se ha registrado el curso.', 5000);
//                    return;
//                }
//            });
//        } else {
//            eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Nuevo-Curso', json, function (respuesta) {
//                if (!respuesta.success) {
//                    evento.mostrarMensaje('.messageAccionesWizard', false, 'No se ha registrado el curso.', 5000);
//                    return;
//                }
//            });
//        }
//        evento.mostrarMensaje('.messageAccionesWizard', true, 'Se ha registrado el curso.', 5000);
//        $('#modalresponseSave').modal('show');
//        location.reload();
//    });
//
//
//
//
//
//
//    $("#btn-loadExcel-temario").on('click', function (e) {
//        //modalSubirTemarios
//        console.log("ABRIR_MODAL")
//        $('#modalSubirTemarios').modal('show')
//    });
//
//
//
//    //nuevo curso
//
//
//
//
//    $("#btn-editarDatosStatus").on('click', function (e) {
//        //modalSubirTemarios
//        console.log("editarDatosStatus")
//        $("#btn-editarDatosStatus").css('display', 'none');
//        $("#btn-cancelar-cambios").css('display', 'block');
//        $("#btn-editarDatosSave").css('display', 'block');
//
//        $('#inputImgCursoEdit').removeAttr('disabled');
//        $("#nombreCursoEdit").removeAttr('disabled');
//        $("#urlCursoEdit").removeAttr('disabled');
//        $("#textareaDescripcionCursoEdit").removeAttr('disabled');
//        $("#certificadoCursoEdit").removeAttr('disabled');
//        $("#costoCursoEdit").removeAttr('disabled');
//        $('#divImagenCurso').removeClass('hidden');
//        $('.profile-center').addClass('hidden');
//        $('#archivo').removeClass('hidden');
//        $('.profile-image').addClass('hidden');
//    });
//
//    $("#btn-cancelar-cambios").on('click', function (e) {
//        $("#btn-editarDatosStatus").css('display', 'block')
//        $("#btn-cancelar-cambios").css('display', 'none')
//        $("#btn-editarDatosSave").css('display', 'none')
//        $('#inputImgCursoEdit').attr('disabled', 'disabled')
//        $("#nombreCursoEdit").attr('disabled', 'disabled')
//        $("#urlCursoEdit").attr('disabled', 'disabled')
//        $("#textareaDescripcionCursoEdit").attr('disabled', 'disabled')
//        $("#certificadoCursoEdit").attr('disabled', 'disabled')
//        $("#costoCursoEdit").attr('disabled', 'disabled');
//        $('#divImagenCurso').addClass('hidden');
//        $('.profile-center').removeClass('hidden');
//        $('#archivo').addClass('hidden');
//        $('.profile-image').removeClass('hidden');
//    });
//
//
//
//
//    $('#btnSubirFotoCurso').off("click");
//    $('#btnSubirFotoCurso').on('click', function () {
//        evento.iniciarModal('#modal-box', 'Imagen de Curso', htmlFormularioSubirImagen());
//        file.crearUpload('#fotoCurso', '', ['jpg', 'jpeg', 'png'], false, [], '', null, false, 1);
//
//        $('#btnModalBoxConfirmar').off('click');
//        $('#btnModalBoxConfirmar').on('click', function () {
//            var foto = $('#btnModalConfirmar').val();
//            if (foto !== '') {
//                var datos = {};
//                file.enviarArchivos('#fotoUsuario', 'PerfilUsuario/ActualizarFotoUsuario', '#modal-box', datos, function (resultado) {
//                });
//            } else {
//                evento.mostrarMensaje("#errorFoto", false, "Favor de seleccionar una foto.", 4000);
//            }
//
//            cerrarModalCambios();
//        });
//    });
//
//
//    function htmlFormularioSubirImagen() {
//        let html = `<div class="row">
//                    <div class="col-md-12">                                    
//                        <div class="form-group">
//                            <label id="divArchivos">Foto *</label>
//                            <input id="fotoCurso"  name="fotoCurso[]" type="file" multiple/>
//                        </div>
//                    </div>
//                </div>
//                <div class="row m-t-10">
//                        <div class="col-md-12">
//                            <div id="errorFoto"></div>
//                        </div>
//                </div>`;
//
//        return html;
//    }
});


