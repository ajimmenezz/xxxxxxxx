$(function () {
    //Objetos
    var evento = new Base();
    var tabla = new Tabla();
    var select = new Select();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    //Crea select multiple permiso
    select.crearSelectMultiple('#areaAtencion', 'Ducelceria,Taquilla,Gerencia,Cocina');

    tabla.generaTablaPersonal('#tabla-categorias', null, null, true, true, [[1, 'asc']]);
    tabla.generaTablaPersonal('#tabla-preguntas', null, null, true, true);

    $('#btnAgregarCategoria').off('click');
    $('#btnAgregarCategoria').on('click', function () {
        var txtNuevaCategoria = $.trim($("#txtNuevaCategoria").val());

        if (txtNuevaCategoria !== '') {
            var datos = {'nuevaCategoria': txtNuevaCategoria};
            evento.enviarEvento('EventoCatalogoRevisionFisica/AgregarCategoria', datos, '#panel-catalogo-checklist', function (respuesta) {
                if (respuesta.code === 200) {
                    tabla.agregarFila('#tabla-categorias', [respuesta.id, respuesta.categoria, 'Activo']);
                    tabla.reordenarTabla('#tabla-categorias', [1, 'asc']);
                    evento.mostrarMensaje("#errorMessage", true, respuesta.succes, 4000);
                    $("#txtNuevaCategoria").val('');
                }
                if (respuesta.code === 500) {
                    evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                }
            });
        } else {
            evento.mostrarMensaje("#errorMessage", false, "Ingresa una categoria", 4000);
        }
    });

    $('#tabla-categorias tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#tabla-categorias').DataTable().row(_this).data();
        var dato = {'idCategoria': datosTabla[0]};

        evento.enviarEvento('EventoCatalogoRevisionFisica/ActulizarCategoria', dato, '#panel-catalogo-checklist', function (respuesta) {
            evento.iniciarModal('#modalEdit', 'Editar Categoria', respuesta.modal);

            $('#btnGuardarCambios').off('click');
            $('#btnGuardarCambios').on('click', function () {
                var txtCategoriaModal = $.trim($('#txtCategoriaModal').val());
                if (txtCategoriaModal !== '') {
                    var datos = {
                        'Id': $('#idCategoria').val(),
                        'Nombre': txtCategoriaModal,
                        'Flag': $('#estatusCategoria').val()
                    };

                    evento.enviarEvento('EventoCatalogoRevisionFisica/EditarCategoria', datos, '#modalEdit', function (resultado) {
                        if (resultado) {
                            evento.terminarModal('#modalEdit');
                            $('#tabla-categorias').DataTable().row(_this).data([resultado.Id, resultado.Nombre, resultado.Estatus]);
                            tabla.reordenarTabla('#tabla-categorias', [1, 'asc']);
                            $('#tabla-categorias').DataTable().page.jumpToData(respuesta.Id, 0);
                        } else {
                            evento.mostrarMensaje("#errorMessage", false, "No es posible editar la categoria", 4000);
                        }
                    });
                }
            });
        });
    });

    $('#agregarPregunta').off('click');
    $('#agregarPregunta').on('click', function () {

        evento.enviarEvento('EventoCatalogoRevisionFisica/ModalPregunta', {}, '', function (respuesta) {
            select.crearSelect('#categoria');
            select.crearSelect('#areaAtencion');

            evento.iniciarModal('#modalEdit', 'Agregar Pregunta (Concepto)', respuesta.modal);
            select.cargaDatos('#categoria', respuesta.categoria);
            select.cargaDatos('#areaAtencion', respuesta.areaAtencion);
        });
    });

    $('#btnGuardarCambios').off('click');
    $('#btnGuardarCambios').on('click', function () {

        var concepto = $('#txtPreguntaCategoria').val();
        var etiqueta = $('#txtEtiqueta').val();
        var categoria = $('#categoria').val();
        var areaAtencion = $('#areaAtencion').val();
        var ListaAreaAtencion = '';
        
        $("#areaAtencion option:selected").each(function () {
            ListaAreaAtencion += '<br/>' + $(this).text();
        });
        
        if(ListaAreaAtencion !== ''){
            ListaAreaAtencion = ListaAreaAtencion.substr(5);
        }
        
        if (evento.validarFormulario('#formAgregarPregunta')) {
            var data = {'concepto': concepto, 'etiqueta': etiqueta, 'categoria': categoria, 'areaAtencion': areaAtencion};
            
            evento.enviarEvento('EventoCatalogoRevisionFisica/GuardarPregunta', data, '', function (datosPregunta) {
                if (datosPregunta) {
                    evento.terminarModal('#modalEdit');
                    evento.mostrarMensaje("#errorMessagePregunta", true, datosPregunta.succes, 4000);
                    
                    var filaPreguntas = '<tr><td>' + datosPregunta.Id + '</td>' +
                            '<td>' + datosPregunta.Concepto + '</td>' +
                            '<td>' + datosPregunta.Etiqueta + '</td>' +
                            '<td>' + datosPregunta.NombreCategoria + '</td>' +
                            '<td>' + ListaAreaAtencion + '</td>' +
                            '<td>' + datosPregunta.Estatus + '</td></tr>';
                    tabla.agregarFilaHtml('#tabla-preguntas', filaPreguntas);
                }
            });
        }
    });
    
    $('#tabla-preguntas tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#tabla-preguntas').DataTable().row(_this).data();
        var dato = {'idPregunta': datosTabla[0]};
        var IdPregunta = datosTabla[0];
        var estatusPregunta;
        
        evento.enviarEvento('EventoCatalogoRevisionFisica/MostrarPregunta', dato, '#panel-catalogo-checklist', function (respuesta) {
            evento.iniciarModal('#modalEdit', 'Editar Pregunta (Concepto)', respuesta.modal);
            
            select.crearSelect('#categoria');
            select.crearSelect('#areaAtencion');
            select.cargaDatos('#categoria', respuesta.categoria);
            select.cargaDatos('#areaAtencion', respuesta.areaAtencion);
            
            var pregunta = (respuesta.consultaPregunta[0]['Concepto']);
            var etiqueta = (respuesta.consultaPregunta[0]['Etiqueta']);
            var categoria = (respuesta.consultaPregunta[0]['IdCategoria']);
            var area = (respuesta.consultaPregunta[0]['AreasAtencion']);
            var arrayArea = JSON.parse("[" + area + "]");
            
            $('#txtPreguntaCategoria').val(pregunta);
            $('#txtEtiqueta').val(etiqueta);
            $('#categoria').val(categoria).trigger('change');
            $('#areaAtencion').val(arrayArea).trigger('change');  
            
            
            $('#editarEstatus').off('click');
            $('#editarEstatus').on('click', function(){
                estatusPregunta = $('#editarEstatus').val();
                
                if(estatusPregunta == 1){
                    estatusPregunta = 0;
                    $('#editarEstatus').removeClass('btn-primary');
                    $('#editarEstatus').addClass('btn-danger');
                    $('#editarEstatus').text('Inhabiliatado');
                    guardarPregunta(IdPregunta,estatusPregunta,_this);
                }else{
                    estatusPregunta = 1;
                    $('#editarEstatus').removeClass('btn-danger');
                    $('#editarEstatus').addClass('btn-primary');
                    $('#editarEstatus').text('Activado');
                    guardarPregunta(IdPregunta,estatusPregunta,_this);
                }

            });
        });
        
        $('#btnGuardarCambios').off('click');
        $('#btnGuardarCambios').on('click', function () {
            estatusPregunta = $('#editarEstatus').val();
            guardarPregunta(IdPregunta,estatusPregunta,_this);
        });
    });
    
    var guardarPregunta = function(IdPregunta,estatusPregunta,_this){
      
        var concepto = $('#txtPreguntaCategoria').val();
        var etiqueta = $('#txtEtiqueta').val();
        var categoria = $('#categoria').val();
        var areaAtencion = $('#areaAtencion').val();

        if (evento.validarFormulario('#formAgregarPregunta')) {
            var data = {'Id': IdPregunta, 'concepto': concepto, 'etiqueta': etiqueta, 'categoria': categoria, 'areaAtencion': areaAtencion, 'estatus' : estatusPregunta};
            evento.enviarEvento('EventoCatalogoRevisionFisica/EditarPregunta', data, '', function (datosPregunta) {

                if (datosPregunta) {
                    evento.terminarModal('#modalEdit');
                    evento.mostrarMensaje("#errorMessagePregunta", true, "Datos modificados", 4000);

                    $('#tabla-preguntas').DataTable().row(_this).data([datosPregunta.pregunta.Id,
                                                                        datosPregunta.pregunta.Concepto,
                                                                        datosPregunta.pregunta.Etiqueta,
                                                                        datosPregunta.pregunta.NombreCategoria,
                                                                        datosPregunta.pregunta.Areas,
                                                                        datosPregunta.pregunta.Estatus]);
                    tabla.reordenarTabla('#tabla-categorias', [1, 'asc']);
                    $('#tabla-preguntas').DataTable().page.jumpToData(datosPregunta.pregunta.Id, 0);
                }
            });
        }
    };
});