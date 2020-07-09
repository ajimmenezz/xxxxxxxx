$(function () {

    var evento = new Base();
    var eventoPagina = new Pagina();
    var file = new Upload();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    file.crearUpload('#inputImgCursoEdit', 'Administracion_Cursos/Editar-Curso', ['jpg', 'jpeg', 'png'], false, [], '', null, false, 1);
//    file.crearUploadBoton('#inputImgCurso', 'Administracion_Cursos/Nuevo-Curso', 'Subir Imagen');
    let evidenciaCurso = new FileUpload_Boton('evidencias', {
        url: 'Administracion_Cursos/Nuevo-Curso',
        extensiones: ['jpg', 'jpeg', 'png'],
        tituloAceptar: 'Agregar Archivos',
        colorBotonAceptar: 'btn btn-success'});
    evidenciaCurso.iniciarFileUpload();

    //Inicializa funciones de la plantilla
    App.init( );

    let tablaCursos = new TablaBasica('tabla-cursos');
    let tablaTemarios = new TablaBasica('tabla-cursos-temario');
    let tablaTemariosEdit = new TablaBasica('tabla-cursos-temarioEdit');

    let tablaListCursosVer = new TablaBasica('tabla-cursosAsignados');
    let tablaListemasAvance = new TablaBasica('tabla-temarioAvances');
    let tablaParticipantes = new TablaBasica('tabla-cursos-participantes');



    $('#btn-nuevo-curso').on('click', function (e) {
        $("#administracion-cursos").css('display', 'none')
        $("#administracion-cursos_nuevoCurso").css('display', 'block')
        console.log('clic en boton');

    });


    $('#btn-adminVerCurso').on('click', function (e) {


    });

    $('#btn-adminEditarCurso').on('click', function (e) {

    });


    $("#EliminarCursoAdminConfirm").on('click', function (e) {
        console.debug('clic en boton elimnar curso confirmacion');
        var curso = $("#idElementSeleccionAccion").val();
        let datos = {idCurso: curso};
        eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Eliminar-Curso', datos, function (respuesta) {
            console.debug(respuesta);
            evento.mostrarMensaje('.messageAcciones', true, 'Se ha eliminado el curso.', 5000);
            location.reload();
        });
    });




    $("#wizard").bwizard();



    $("#wizard").bwizard({validating: function (e, ui) {
            let datosTabla = tablaTemarios.datosTabla();
            let datosTabla2 = tablaParticipantes.datosTabla();


            if (ui.index == 0) {
                // step-1 validation
                if (false === $('form[name="form-wizard"]').parsley().validate('wizard-step-1')) {
                    return false;
                }
            } else if (ui.index == 1) {
                // step-2 validation
                // if (false === $('form[name="form-wizard"]').parsley().validate('wizard-step-2')) {
                //     return false;
                // }
                if (datosTabla.length <= 0) {
                    $('#modalValidateTemario').modal('show')
                    return false;
                }
            } else if (ui.index == 2) {
                // step-3 validation
                // if (false === $('form[name="form-wizard"]').parsley().validate('wizard-step-3')) {
                //     return false;
                // }
                if (datosTabla2.length <= 0) {
                    $('#modalValidateParticipantes').modal('show')
                    return false;
                }
            }
        }
    });




    $(".btn-cancel_wizard").on('click', function (e) {
        //modalSubirTemarios
        console.log("cancelar wizard")
        $('#modalSubirTemarios').modal('hide')
        $('#modalValidateTemario').modal('hide')
        $("#modalValidateParticipantes").modal('hide');
        $("#administracion-cursos-ver").css('display', 'none')
        $("#administracion-cursos-verAvance").css('display', 'none')
        $("#administracion-cursos-EDITAR").css('display', 'none')

        $("#administracion-cursos").css('display', 'block')
        $("#administracion-cursos_nuevoCurso").css('display', 'none')

    });

    $(".btn-cancel_wizardEdit").on('click', function (e) {
        //modalSubirTemarios
        console.log("cancelar wizardEdit")
        $('#modalSubirTemarios').modal('hide')
        $('#modalValidateTemario').modal('hide')
        $("#modalValidateParticipantes").modal('hide');


        $("#administracion-cursos_nuevoCurso").css('display', 'none')
        $("#administracion-cursos-ver").css('display', 'none')
        $("#administracion-cursos-verAvance").css('display', 'none')
        $("#administracion-cursos-EDITAR").css('display', 'none')
        $("#administracion-cursos").css('display', 'block')

    });



    let listTemario = []

    $('#btn-agregar-nuevo-temario').on('click', function (e) {
        //modalSubirTemarios
        console.log("btn-agregar-nuevo-temario")
        $nombreTemario = $("#nombreTemario").val();
        if ($nombreTemario !== "") {

            let numItemsTemario = tablaTemarios.datosTabla();
            $filas_num = numItemsTemario.length;
            let datos = tablaTemarios.datosFila(this);


            console.debug(datos, "DATOS TABLA TEMARIOS", numItemsTemario, $filas_num, datos);

            $long = listTemario.length + 1;
            $porcentaje = (100 / $long).toFixed(2);

            console.debug($nombreTemario, $porcentaje, "DATOS tEMARIO1", listTemario)
            listTemario.push({'nombre': $nombreTemario, 'porcentaje': $porcentaje});


            tablaTemarios.limpiartabla();

            listTemario.forEach(element => {
                element.porcentaje = $porcentaje;
                tablaTemarios.agregarDatosFila([
                    element.nombre,
                    element.porcentaje + '%',
                    "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarTemario'></i></spand>"

                ]);
            });




            console.debug($nombreTemario, $porcentaje, "DATOS tEMARIO2", listTemario)
            $("#nombreTemario").val("");
        }
    });

    tablaTemarios.evento(function () {
        let numItemsTemario = tablaTemarios.datosTabla();
        var elim = tablaTemarios.eliminarFila(this);
        let datosTabla = tablaTemarios.datosTabla();

        console.debug(numItemsTemario, "eliminar", elim, "resto", datosTabla, tablaTemarios.datosFila(this));

        listTemario = []

        if (datosTabla.length !== 0) {
            $long = datosTabla.length;
            $porcentaje = (100 / $long).toFixed(2);
            let datos = tablaTemarios.datosFila(this);
            var info = $('#tabla-cursosPrinc').DataTable().rows({search: 'applied'}).data();
            // datos.forEach(element => {
            //   listTemario.push({'nombre':element.nombre,'porcentaje':$porcentaje});
            // });
            console.debug(datosTabla, "ENTRE FOR", datosTabla.length, datos, info)

            for (let index = 0; index < datosTabla.length; index++) {
                const element = datosTabla[index];
                console.debug("DATOS", element, element[0])

                listTemario.push({'nombre': element[0], 'porcentaje': $porcentaje});

            }
        }

        tablaTemarios.limpiartabla();

        listTemario.forEach(element => {

            tablaTemarios.agregarDatosFila([
                element.nombre,
                element.porcentaje + '%',
                "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarTemario'></i></spand>"

            ]);
        });

        console.debug("FINAL", listTemario)


    });

    let listTemarioEdit = []

    $('#btn-agregar-nuevo-temarioEdit').on('click', function (e) {
        //modalSubirTemarios
        console.log("btn-agregar-nuevo-temario_EDIT")
        $nombreTemario = $("#nombreTemarioEdit").val();
        if ($nombreTemario !== "") {

            let datosTabla = tablaTemariosEdit.datosTabla();
            $filas_num = datosTabla.length;
            let datos = tablaTemariosEdit.datosFila(this);

            var idCurso=$("#idElementSeleccionAccion").val();


            console.debug(datos, "DATOS TABLA TEMARIOS", datosTabla, $filas_num, datos);

            $long = datosTabla.length + 1;
            $porcentaje = (100 / $long).toFixed(2);

            listTemarioEdit = []
            for (let index = 0; index < datosTabla.length; index++) {
                const element = datosTabla[index];
                console.debug("DATOS", element, element[0])

                listTemarioEdit.push({'nombre': element[0], 'porcentaje': $porcentaje, 'id': element[2],'idCurso':element[3]});

            }



            console.debug($nombreTemario, $porcentaje, "DATOS tEMARIO1", listTemarioEdit)


            var json = {
                tipoDato: 1,
                idCurso: idCurso,
                nombre:$nombreTemario,
                porcentaje:$porcentaje,
                descripcion:''
            }

           

            console.debug("DATOS_SEND", json)


            eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Agregar-ElementoCurso', json, function (respuesta) {
                console.log("nuevoTema_EDIT", respuesta);
                if (!respuesta.success) {
                    evento.mostrarMensaje('.eventAccionEditarCurso', false, 'No se ha registrado el tema.', 5000);
                    return;
                }

                listTemarioEdit.push({'nombre': $nombreTemario, 'porcentaje': $porcentaje, 'id': respuesta.data.id,'idCurso':idCurso});


                tablaTemariosEdit.limpiartabla();

                listTemarioEdit.forEach(element => {
                    element.porcentaje = $porcentaje;
                    tablaTemariosEdit.agregarDatosFila([
                        element.nombre,
                        element.porcentaje + '%',
                        element.id,
                        element.idCurso,
                        "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarTemario'></i></spand>"

                    ]);
                });


            });




            console.debug($nombreTemario, $porcentaje, "DATOS tEMARIO2", listTemarioEdit)
            $("#nombreTemarioEdit").val("");
        }
    });



    tablaTemariosEdit.evento(function () {
        let numItemsTemario = tablaTemariosEdit.datosTabla();
        let datos = tablaTemariosEdit.datosFila(this);
        let elim  = tablaTemariosEdit.eliminarFila(this);  
        let datosTabla = tablaTemariosEdit.datosTabla();


        console.debug("resto",datos,elim,"antes de ELIMANR",numItemsTemario, numItemsTemario.length,"eliminar_FIN",  datosTabla, datosTabla.length, tablaTemariosEdit.datosFila(this));

        if (numItemsTemario.length !=0) {

            var json = {
                tipoDato: 1,
                idCurso: datos[3],
                id: datos[2]
            }



            eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Eliminar-ElementoCurso', json, function (respuesta) {
                console.log("eliminarTema_EDIT", respuesta);
                if (!respuesta.success) {
                    evento.mostrarMensaje('.eventAccionEditarCurso', false, 'No se ha eliminado el tema.', 5000);
                    return;
                }

                
                console.debug("FILA_ELIMINAR",elim);


                listTemarioEdit = []
        

                if (datosTabla.length >=0) {
                    $long = datosTabla.length;
                    $porcentaje = (100 / $long).toFixed(2);
                    
                    
                    console.debug(datosTabla, "ENTRE FOR", datosTabla.length, datos)
        
                    for (let index = 0; index < datosTabla.length; index++) {
                        const element = datosTabla[index];
                        console.debug("DATOS", element, element[0])
        
                        listTemarioEdit.push({'nombre': element[0], 'porcentaje': $porcentaje, 'id': element[2],'idCurso':element[3]});
        
                    }
                }

                console.debug("ERREGLO",listTemarioEdit);



                tablaTemariosEdit.limpiartabla();

                listTemarioEdit.forEach(element => {

                    tablaTemariosEdit.agregarDatosFila([
                        element.nombre,
                        element.porcentaje + '%',
                        element.id,
                        element.idCurso,
                        "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarTemario'></i></spand>"

                    ]);
                });
                console.debug("Areeglo_FIN",listTemarioEdit);

            });
        }

      


    });


    $("#file-upload-button").addClass("btn btn-success m-r-5 ");



    let listPuesto = [];
    let selectPartic = new SelectBasico('puesto')
    $("#btn-nuevo-puestoParticipante").on('click', function (e) {
        //modalSubirTemarios
        console.log("btn-nuevo-puestoParticipante")

        $nombrePuesto = selectPartic.obtenerValor()
        $nombrePuestoString = selectPartic.obtenerTexto()

        console.debug("stirng", $nombrePuestoString)

        if ($nombrePuesto !== "") {
            let numItemsTemario = tablaParticipantes.datosTabla();
            $filas_num = numItemsTemario.length;
            let datos = tablaParticipantes.datosFila(this);




            console.debug($nombrePuesto, "DATOS tEMARIO1", listPuesto)
            listPuesto.push({'nombre': $nombrePuesto, 'nameString': $nombrePuestoString});

            tablaParticipantes.limpiartabla();


            listPuesto.forEach(element => {
                tablaParticipantes.agregarDatosFila([
                    element.nombre,
                    element.nameString,
                    "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarParticipant'></i></spand>"

                ]);

            });





            console.debug($nombrePuesto, "DATOS tEMARIO2", listPuesto)
            $("#puesto").val("");
        }

    });

    tablaParticipantes.evento(function () {
        let numItemsTemario = tablaParticipantes.datosTabla();

        let datosElemento = tablaParticipantes.datosFila(this);
        console.debug("DATO_ESPECIFICO", datosElemento);
        var elim = tablaParticipantes.eliminarFila(this);
        let datosTabla = tablaParticipantes.datosTabla();

        console.debug(numItemsTemario, "eliminar", elim, "resto", datosTabla, tablaParticipantes.datosFila(this));

        listPuesto = []

        if (datosTabla.length !== 0) {

            let datos = tablaParticipantes.datosFila(this);
            var info = $('#tabla-cursos-temario').DataTable().rows({search: 'applied'}).data();

            console.debug(datosTabla, "ENTRE FOR", datosTabla.length, datos, info)

            for (let index = 0; index < datosTabla.length; index++) {
                const element = datosTabla[index];
                console.debug("DATOS", element, element[0])

                listPuesto.push({'nombre': element[0], 'nameString': element[1]});

            }
        }

        tablaParticipantes.limpiartabla();

        listPuesto.forEach(element => {

            tablaParticipantes.agregarDatosFila([
                element.nombre,
                element.nameString,
                "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarParticipant'></i></spand>"

            ]);
        });

        console.debug("FINAL", listPuesto)


    });



    tablaParticipantesEdit = new TablaBasica('tabla-cursos-participantesEdit');


    let listPuestoEdit = [];
    let selectPart = new SelectBasico('puestoEdit')
    $("#btn-nuevo-puestoParticipanteEdit").on('click', function (e) {
        //modalSubirTemarios
        console.log("btn-nuevo-puestoParticipante_EDIT")

        $nombrePuesto = selectPart.obtenerValor()
        $nombrePuestoString = selectPart.obtenerTexto()
        alert("string", $nombrePuestoString)
        console.debug("stirng", $nombrePuestoString)



        if ($nombrePuesto !== "") {
            let datosTabla = tablaParticipantesEdit.datosTabla();
            $filas_num = datosTabla.length;
            let datos = tablaParticipantesEdit.datosFila(this);

            let listPuestoEdit = [];
            for (let index = 0; index < datosTabla.length; index++) {
                const element = datosTabla[index];
                console.debug("DATOS", element, element[0])

                listPuestoEdit.push({'nombre': element[2], 'nameString': element[3],'id':element[0], 'idCurso':element[1]});

            }

            var idCurso=$("#idElementSeleccionAccion").val();


            var perfiles = $('#perfiles').val()
            console.debug('per', perfiles, 'files', datos, "DATOS TABLA TEMARIOS", datosTabla, $filas_num, datos);

            console.debug($nombrePuesto, "DATOS tEMARIO1", listPuestoEdit)

            var json = {
                tipoDato: 0,
                idCurso: idCurso,
                idPerfil: $nombrePuesto


            }

            console.debug("DATOS_SEND", json);

            eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Agregar-ElementoCurso', json, function (respuesta) {
                console.log("nuevoParticipante_EDIT", respuesta);
                if (!respuesta.success) {
                    evento.mostrarMensaje('.eventAccionEditarCurso', false, 'No se ha registrado el participante.', 5000);
                    return;
                }

                //listPuestoEdit.push({'nombre': $nombrePuesto});
                listPuestoEdit.push({'nombre': $nombrePuesto, 'nameString': $nombrePuestoString,'id':respuesta.data.id,'idCurso':idCurso});


                tablaParticipantesEdit.limpiartabla();

                listPuestoEdit.forEach(element => {
                    tablaParticipantesEdit.agregarDatosFila([
                        element.id,
                        element.idCurso,
                        element.nombre,
                        element.nameString,
                        "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarParticipant'></i></spand>"

                    ]);
                });


            });




            console.debug($nombrePuesto, "DATOS part2", listPuestoEdit)
            $("#puestoEdit").val("");
        }


    });




    tablaParticipantesEdit.evento(function () {

        let numItemsTemario = tablaParticipantesEdit.datosTabla();
        let datos = tablaParticipantesEdit.datosFila(this);
        let elim  = tablaParticipantesEdit.eliminarFila(this);  
        let datosTabla = tablaParticipantesEdit.datosTabla();
        
        
        console.debug("resto",datos,elim,"antes de ELIMANR",numItemsTemario, numItemsTemario.length,"eliminar_FIN",  datosTabla, datosTabla.length, tablaParticipantesEdit.datosFila(this));
        
        if (numItemsTemario.length !=0) {
        
            var json = {
                tipoDato: 0,
                idCurso: datos[1],
                id: datos[0]
            }
        
        
        
            eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Eliminar-ElementoCurso', json, function (respuesta) {
                console.log("eliminarPART_EDIT", respuesta);
                if (!respuesta.success) {
                    evento.mostrarMensaje('.eventAccionEditarCurso', false, 'No se ha eliminado el participante.', 5000);
                    return;
                }
        
                
                console.debug("FILA_ELIMINAR",elim);
        
        
                listPuestoEdit = []
        
        
                if (datosTabla.length >=0) {
                    
                    console.debug(datosTabla, "ENTRE FOR", datosTabla.length, datos)
        
            
                    for (let index = 0; index < datosTabla.length; index++) {
                        const element = datosTabla[index];
                        console.debug("DATOS", element, element[0])
                       
                        listPuestoEdit.push({'nombre': element[2], 'nameString': element[3],'id':element[0], 'idCurso':element[1]});
                    }
        
                }
        
                console.debug("ERREGLO",listPuestoEdit);
        
        
                tablaParticipantesEdit.limpiartabla();
        
                listPuestoEdit.forEach(element => {
        
                    tablaParticipantesEdit.agregarDatosFila([
                        element.id,
                        element.idCurso,
                        element.nombre,
                        element.nameString,
                        "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarParticipant'></i></spand>"

                    ]);
                });
                console.debug("Areeglo_FIN",listPuestoEdit);
        
            });
        }
        
        
        


    });


    //ver curso



    var tablePrinc = new TablaBasica('tabla-cursosPrinc');





    tablaListCursosVer.evento(function () {

        let datosTabla = tablaListCursosVer.datosTabla();

        console.debug("eliminar", "resto", datosTabla, tablaListCursosVer.datosFila(this));



        if (datosTabla.length !== 0) {

            let datosElemento = tablaListCursosVer.datosFila(this);
            console.debug("DATO_ESPECIFICO", datosElemento);

            var json = {
                idCurso: $("#idElementSeleccionAccion").val(),
                idUsuario: datosElemento[3]
            }

            console.debug("PARAMS", json);
            eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/TemasCursoUsuario', json, function (respuesta) {
                console.log("TemasCursoUsuario_AVANCES", respuesta);
                if (!respuesta.success) {
                    evento.mostrarMensaje('.eventAccionEditarCurso', false, 'No se ha eliminado el participante.', 5000);
                    return;
                }

                var datosUser = respuesta.data.infoUsuario
                var temas = respuesta.data.infoUsuario.temas



                console.debug("DATOS_TEMAS", temas, respuesta.data.infoUsuario.temas)

                tablaListemasAvance.limpiartabla();



                // temas.forEach(element => {
                //     tablaListemasAvance.agregarDatosFila([
                //         element.id,
                //         element.nombre,
                //         element.porcentaje+'%',
                //        element.fechaModificacion,
                //        element.idAvance
                //     ]);
                // });

                for (var index in temas) {
                    console.debug("FOR");
                    // if(index>0){
                    //     index=index+1;
                    // }
                    const element = temas[index];

                    var idAvance = -1;
                    var fecha = '-';
                    if (element.idAvance) {
                        idAvance = element.idAvance;
                    }
                    if (element.fechaModificacion) {
                        fecha = element.fechaModificacion;
                    }


                    tablaListemasAvance.agregarDatosFila([
                        element.id,
                        element.nombre,
                        element.porcentaje + '%',
                        fecha,
                        idAvance
                    ]);

                    console.debug("ELEMENTOS", element, idAvance, index)

                }

                var datosUserInfo = respuesta.data.infoUsuario.infoUsuario[0];

                console.debug("DATOS_INFO", datosUserInfo, tablaListemasAvance)

                $("#cursoAvanceParticipante").text(datosUserInfo.NOmbre);
                $("#cursoAvanceCurso").text(datosUserInfo.Perfil);
                $("#cursoAvancePuesto").html(datosUserInfo.Curso);




                $("#administracion-cursos_nuevoCurso").css('display', 'none')
                $("#administracion-cursos-ver").css('display', 'none')
                $("#administracion-cursos-verAvance").css('display', 'block')
                $("#administracion-cursos-EDITAR").css('display', 'none')
                $("#administracion-cursos").css('display', 'none')
                $("#evidenciasVerAvanceTema").css('display', 'none')



            });
        }
    });


    tablaListemasAvance.evento(function () {
        $('#modalSubirTemarios').modal('hide')
        $('#modalValidateTemario').modal('hide')
        $("#modalValidateParticipantes").modal('hide');


        $("#administracion-cursos_nuevoCurso").css('display', 'none')
        $("#administracion-cursos-ver").css('display', 'none')
        $("#administracion-cursos-verAvance").css('display', 'block')
        $("#administracion-cursos-EDITAR").css('display', 'none')
        $("#administracion-cursos").css('display', 'none')
        $("#evidenciasVerAvance").css('display', 'block')
        $("#evidenciasVerAvanceTema").css('display', 'none')




        let datosTabla = tablaListemasAvance.datosTabla();

        console.debug(datosTabla, "evidencia", "resto", tablaListemasAvance.datosFila(this));



        if (datosTabla.length !== 0) {

            let datosElemento = tablaListemasAvance.datosFila(this);
            console.debug("DATO_ESPECIFICO", datosElemento);

            if (datosElemento[4] != -1) {
                $('#modalSubirTemarios').modal('hide')
                $('#modalValidateTemario').modal('hide')
                $("#modalValidateParticipantes").modal('hide');


                $("#administracion-cursos_nuevoCurso").css('display', 'none')
                $("#administracion-cursos-ver").css('display', 'none')
                $("#administracion-cursos-verAvance").css('display', 'block')
                $("#administracion-cursos-EDITAR").css('display', 'none')
                $("#administracion-cursos").css('display', 'none')
                $("#evidenciasVerAvance").css('display', 'none')
                $("#evidenciasVerAvanceTema").css('display', 'block')

                // verEvidencia(datosElemento[4])

                var json = {
                    idAvance: datosElemento[4]
                }

                console.debug("PARAMS", json)
                eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Ver-Evidencias', json, function (respuesta) {
                    console.log("Ver-Evidencias_AVANCES", respuesta);
                    if (!respuesta.success) {
                        evento.mostrarMensaje('.alertMessageAvance', false, 'No se han obtenido las evidencias del tema.', 5000);
                        return;
                    }

                    var datosG = respuesta.data.avance

                    var datos = respuesta.data.avance[0]

                    $("#comenarioEvidencias").text(datos.comentarios);
                    var texto = '';

                    datosG.forEach(datos => {
                        texto += `<div class="image gallery-group-1 col-xs-12 col-md-4" style="width: 170px; height: 230px; text-align: center;">
                                    <div class="image-inner">
                                        <a href="${datos.url}" data-lightbox="gallery-group-1">
                                            <img src="${datos.url}" alt="" style="width: 120px; height: 100px;"/>
                                        </a>
                                        
                                    </div>
                                    <div class="image-info">
                                        <h5 class="title">${datos.fechaModificacion}</h5>
                                        
                                        <div class="desc">
                                        <b>Comentarios</b><br>
                                        ${datos.comentarios}
                                        </div>
                                    </div>
                                </div>
                        `;
                    });


                    $("#CONTENT_IMG_EVIDENCIAS").html(texto);



                });


            } else {
                evento.mostrarMensaje('.alertMessageAvance', false, 'No hay evidencias del tema.', 4000);
            }


        }

    });




    $("#btn-save-curso").on('click', function (e) {
        var temas = [];
        var part = [];
        var nombre = $("#nombreCurso").val();
        var url = $("#urlCurso").val();
        var descripcion = $("#textareaDescripcionCurso").val();
        $("#nameCurso").text($("#nombreCurso").val());

        if (nombre == '' || url == '' || descripcion == '') {
            evento.mostrarMensaje('.messageAccionesWizard', false, 'Por favor acompleta los campos marcados con (*), que son obligatorios.', 3000);
            return false;
        }

        let datosTabla = tablaTemarios.datosTabla();
        let datosTabla2 = tablaParticipantes.datosTabla();

        if (datosTabla.length <= 0) {
            $('#modalValidateTemario').modal('show')
            return false;
        }

        if (datosTabla2.length <= 0) {
            $('#modalValidateParticipantes').modal('show')
            return false;
        }

        var cursos = [
            $('#evidencias').val(),
            $("#nombreCurso").val(),
            $("#urlCurso").val(),
            $("#textareaDescripcionCurso").val(),
            $("#certificadoCurso").val(),
            $("#costoCurso").val()
        ];

        var json = {
            cursos: cursos
        };


        for (let index = 0; index < datosTabla.length; index++) {
            const element = datosTabla[index];
            temas.push([element[0], '', parseFloat(element[1]), '/']);
        }

        json.temario = temas;

        for (let index = 0; index < datosTabla2.length; index++) {
            const element = datosTabla2[index];
            part.push([element[0]]);
        }

        json.participantes = part;

        if ($('#inputImgCurso').val() !== '') {
            evidenciaCurso.enviarPeticionServidor('evidencias', json, function (respuesta) {
                if (!respuesta.success) {
                    evento.mostrarMensaje('.messageAccionesWizard', false, 'No se ha registrado el curso.', 5000);
                    return;
                }
            });
        } else {
            eventoPagina.enviarPeticionServidor('administracion-cursos', 'Administracion_Cursos/Nuevo-Curso', json, function (respuesta) {
                if (!respuesta.success) {
                    evento.mostrarMensaje('.messageAccionesWizard', false, 'No se ha registrado el curso.', 5000);
                    return;
                }
            });
        }
        evento.mostrarMensaje('.messageAccionesWizard', true, 'Se ha registrado el curso.', 5000);
        $('#modalresponseSave').modal('show');
        location.reload();
    });






    $("#btn-loadExcel-temario").on('click', function (e) {
        //modalSubirTemarios
        console.log("ABRIR_MODAL")
        $('#modalSubirTemarios').modal('show')
    });



    //nuevo curso




    $("#btn-editarDatosStatus").on('click', function (e) {
        //modalSubirTemarios
        console.log("editarDatosStatus")
        $("#btn-editarDatosStatus").css('display', 'none');
        $("#btn-cancelar-cambios").css('display', 'block');
        $("#btn-editarDatosSave").css('display', 'block');

        $('#inputImgCursoEdit').removeAttr('disabled');
        $("#nombreCursoEdit").removeAttr('disabled');
        $("#urlCursoEdit").removeAttr('disabled');
        $("#textareaDescripcionCursoEdit").removeAttr('disabled');
        $("#certificadoCursoEdit").removeAttr('disabled');
        $("#costoCursoEdit").removeAttr('disabled');
        $('#divImagenCurso').removeClass('hidden');
        $('.profile-center').addClass('hidden');
    });

    $("#btn-cancelar-cambios").on('click', function (e) {
        $("#btn-editarDatosStatus").css('display', 'block')
        $("#btn-cancelar-cambios").css('display', 'none')
        $("#btn-editarDatosSave").css('display', 'none')
        $('#inputImgCursoEdit').attr('disabled', 'disabled')
        $("#nombreCursoEdit").attr('disabled', 'disabled')
        $("#urlCursoEdit").attr('disabled', 'disabled')
        $("#textareaDescripcionCursoEdit").attr('disabled', 'disabled')
        $("#certificadoCursoEdit").attr('disabled', 'disabled')
        $("#costoCursoEdit").attr('disabled', 'disabled');
        $('#divImagenCurso').addClass('hidden');
        $('.profile-center').removeClass('hidden');
    });




    $('#btnSubirFotoCurso').off("click");
    $('#btnSubirFotoCurso').on('click', function () {
        evento.iniciarModal('#modal-box', 'Imagen de Curso', htmlFormularioSubirImagen());
        file.crearUpload('#fotoCurso', '', ['jpg', 'jpeg', 'png'], false, [], '', null, false, 1);

        $('#btnModalBoxConfirmar').off('click');
        $('#btnModalBoxConfirmar').on('click', function () {
            var foto = $('#btnModalConfirmar').val();
            if (foto !== '') {
                var datos = {};
                file.enviarArchivos('#fotoUsuario', 'PerfilUsuario/ActualizarFotoUsuario', '#modal-box', datos, function (resultado) {
                });
            } else {
                evento.mostrarMensaje("#errorFoto", false, "Favor de seleccionar una foto.", 4000);
            }

            cerrarModalCambios();
        });
    });

    function htmlFormularioSubirImagen() {
        let html = `<div class="row">
                    <div class="col-md-12">                                    
                        <div class="form-group">
                            <label id="divArchivos">Foto *</label>
                            <input id="fotoCurso"  name="fotoCurso[]" type="file" multiple/>
                        </div>
                    </div>
                </div>
                <div class="row m-t-10">
                        <div class="col-md-12">
                            <div id="errorFoto"></div>
                        </div>
                </div>`;

        return html;
    }


});
