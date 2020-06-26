$(function () {
//Objetos
    var evento = new Base();
    var websocket = new Socket();
    var select = new Select();
    var tabla = new Tabla();
    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());
    //Evento para cerra la session
    evento.cerrarSesion();
    //Creando tablas de Fallas Poliza
    tabla.generaTablaPersonal('#data-table-tipos-sistema', null, null, true, true);
    tabla.generaTablaPersonal('#data-table-tipos-sistema-equipos', null, null, true, true);
    tabla.generaTablaPersonal('#data-table-tipos-sistema-marcas', null, null, true, true);
    tabla.generaTablaPersonal('#data-table-tipos-sistema-modelos', null, null, true, true);
    tabla.generaTablaPersonal('#data-table-tipos-sistema-componentes', null, null, true, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();


    cargaJsonActividadesMantenimiento();





    //Tipos Sistema

    $('#data-table-tipos-sistema tbody').on('click', 'tr', function () {
        var datos = $('#data-table-tipos-sistema').DataTable().row(this).data();
        var data = {id: datos[0], nombre: datos[1], descripcion: datos[2]};

        mostrarFormularioTipoSistema(data);
    });

    $('#btnAgregarTipoSistema').on('click', function () {
        mostrarFormularioTipoSistema();
    });

    var mostrarFormularioTipoSistema = function () {
        var datos = arguments[0] || '';

        evento.enviarEvento('EventoCatalogos/MostrarFormularioTipoSistema', datos, '#seccion-catalogo-tipos-sistema-salas4xd', function (respuesta) {
            iniciarFormularioTipoSistema(respuesta, datos);
            cargarEventosFormularioTipoSistema(respuesta, datos);
        });
    };

    var iniciarFormularioTipoSistema = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#seccion-catalogo-tipos-sistema-salas4xd').addClass('hidden');
        $('#seccionFormulariosTiposSistema').removeClass('hidden').empty().append(respuesta.formulario);

        if (respuesta.datos.flag !== null) {
            llenarCamposFormularioTiposSistema(datos);
            $('#tituloTipoSistema').empty().html('Actualizar Tipo de Sistema');
            select.crearSelect('select');
        } else {
            $('#estatusTipoSistema').addClass('hidden');
            $('#tituloTipoSistema').empty().html('Nuevo Tipo de Sistema');
        }
    };

    var llenarCamposFormularioTiposSistema = function () {
        var datos = arguments[0] || null;

        $('#inputNombreTipoSistema').val(datos.nombre);
    };

    var cargarEventosFormularioTipoSistema = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1';
        var activacion;

        if (respuesta.datos.flag !== null) {
            id = datos.id;
            operacion = '2';
        }

        $('#btnGuardarTipoSistema').on('click', function () {
            var nombre = $('#inputNombreTipoSistema').val();

            if (operacion === '2') {
                var estatus = $('#selectEstatusTipoSistema').val();
            } else {
                var estatus = '';
            }

            if (evento.validarFormulario('#formTipoSistema')) {
                var data = {id: id, nombre: nombre, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogos/GuardarTipoSistema', data, '#panelTipoSistema', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-tipos-sistema');
                        $.each(respuesta, function (key, item) {
                            if (item.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-tipos-sistema', [item.Id, item.Nombre, activacion]);
                        });
                        $('#seccion-catalogo-tipos-sistema-salas4xd').removeClass('hidden');
                        $('#seccionFormulariosTiposSistema').addClass('hidden');
                        evento.mostrarMensaje('.errorTiposSistema', true, 'Datos guardados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorFormularioTipoSistema', false, 'Ya existe el Nombre del tipo del sistema, por lo que no puedes repetirlo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorFormularioTipoSistema', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }
        });

        eventoRegresar();
    };

    //Equipo

    $('#data-table-tipos-sistema-equipos tbody').on('click', 'tr', function () {
        var datos = $('#data-table-tipos-sistema-equipos').DataTable().row(this).data();
        var data = {id: datos[0], nombre: datos[1]};

        mostrarFormularioTiposSistemasEquipo(data);
    });

    $('#btnAgregarTiposSistemaEquipo').on('click', function () {
        mostrarFormularioTiposSistemasEquipo();
    });

    var mostrarFormularioTiposSistemasEquipo = function () {
        var datos = arguments[0] || '';

        evento.enviarEvento('EventoCatalogos/MostrarFormularioEquipo', datos, '#seccion-catalogo-tipos-sistema-salas4xd', function (respuesta) {
            iniciarFormularioTiposSistemaEquipo(respuesta, datos);
            cargarEventosFormularioTiposSistemasEquipo(respuesta, datos);
        });
    };

    var iniciarFormularioTiposSistemaEquipo = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#seccion-catalogo-tipos-sistema-salas4xd').addClass('hidden');
        $('#seccionFormulariosTiposSistema').removeClass('hidden').empty().append(respuesta.formulario);
        select.crearSelect('select');

        if (respuesta.datos.flag !== null) {
            llenarCamposFormularioTiposSistemaEquipo(datos);
            $('#tituloEquipo').empty().html('Actualizar Línea');
        } else {
            $('#estatusEquipo').addClass('hidden');
            $('#tituloEquipo').empty().html('Nueva Línea');
        }
    };

    var llenarCamposFormularioTiposSistemaEquipo = function () {
        var datos = arguments[0] || null;
        $('#inputNombreEquipo').val(datos.nombre);

    };

    var cargarEventosFormularioTiposSistemasEquipo = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1'
        var activacion;

        if (respuesta.datos.flag !== null) {
            id = datos.id;
            operacion = '2';
        }

        $('#btnGuardarEquipo').on('click', function () {
            var nombre = $('#inputNombreEquipo').val();

            if (operacion === '2') {
                var estatus = $('#selectEstatusEquipo').val();
            } else {
                var estatus = '';
            }
            if (evento.validarFormulario('#formEquipos')) {
                var data = {id: id, nombre: nombre, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogos/GuardarEquipo', data, '#panelEquipo', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-tipos-sistema-equipos');
                        $.each(respuesta, function (key, item) {
                            if (item.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-tipos-sistema-equipos', [item.Id, item.Nombre, activacion]);
                        });
                        $('#seccion-catalogo-tipos-sistema-salas4xd').removeClass('hidden');
                        $('#seccionFormulariosTiposSistema').addClass('hidden');
                        evento.mostrarMensaje('.errorTiposSistemaEquipos', true, 'Datos guardados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorEquipo', false, 'Ya existe la Línea en el catálogo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorEquipo', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }
        });

        eventoRegresar();
    };

    //Marcas


    $('#data-table-tipos-sistema-marcas').on('click', 'tr', function () {
        var datos = $('#data-table-tipos-sistema-marcas').DataTable().row(this).data();
        var data = {id: datos[0], marca: datos[1]};

        mostrarFormularioMarca(data);
    });

    $('#btnAgregarMarca').on('click', function () {
        mostrarFormularioMarca();
    });

    var mostrarFormularioMarca = function () {
        var datos = arguments[0] || '';

        evento.enviarEvento('EventoCatalogos/MostrarFormularioMarca', datos, '#seccion-catalogo-tipos-sistema-salas4xd', function (respuesta) {
            iniciarFormularioMarca(respuesta, datos);
            cargarEventosFormularioMarca(respuesta, datos);
        });
    };

    var iniciarFormularioMarca = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#seccion-catalogo-tipos-sistema-salas4xd').addClass('hidden');
        $('#seccionFormulariosTiposSistema').removeClass('hidden').empty().append(respuesta.formulario);

        select.crearSelect('select');

        console.log(respuesta);

        if (respuesta.datos.flag !== null) {
            llenarCamposFormularioMarca(datos, respuesta);
            $('#tituloMarca').empty().html('Actualizar Marca');
        } else {
            $('#estatusMarca').addClass('hidden');
            $('#tituloMarca').empty().html('Nuevo Marca');
        }
    };

    var llenarCamposFormularioMarca = function () {
        var datos = arguments[0] || null;
        $('#inputNombreMarca').val(datos.marca);
    };

    var cargarEventosFormularioMarca = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1'
        var activacion;

        if (respuesta.datos.flag !== null) {
            id = datos.id;
            operacion = '2';
        }

        $('#btnGuardarMarca').on('click', function () {
            var nombre = $('#inputNombreMarca').val();

            if (operacion === '2') {
                var estatus = $('#selectEstatusMarca').val();
            } else {
                var estatus = '';
            }
            if (evento.validarFormulario('#formMarca')) {
                var data = {id: id, nombre: nombre, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogos/GuardarMarca', data, '#panelMarca', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-tipos-sistema-marcas');
                        $.each(respuesta, function (key, item) {
                            if (item.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-tipos-sistema-marcas', [item.Id, item.Nombre, activacion]);
                        });
                        $('#seccion-catalogo-tipos-sistema-salas4xd').removeClass('hidden');
                        $('#seccionFormulariosTiposSistema').addClass('hidden');
                        evento.mostrarMensaje('.errorTiposSistemaMarcas', true, 'Datos guardados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorFormularioMarca', false, 'Ya existe el Nombre de la Marca en el catálogo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorFormularioMarca', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }
        });

        eventoRegresar();
    };
    //Modelos

    $('#data-table-tipos-sistema-modelos tbody').on('click', 'tr', function () {
        var datos = $('#data-table-tipos-sistema-modelos').DataTable().row(this).data();
        var data = {id: datos[0], modelo: datos[1]};

        mostrarFormularioModelo(data);
    });

    $('#btnAgregarModelo').on('click', function () {
        mostrarFormularioModelo();
    });

    var mostrarFormularioModelo = function () {
        var datos = arguments[0] || '';

        evento.enviarEvento('EventoCatalogos/MostrarFormularioModelo', datos, '#seccion-catalogo-tipos-sistema-salas4xd', function (respuesta) {
            iniciarFormularioModelo(respuesta, datos);
            cargarEventosFormularioModelo(respuesta, datos);
        });
    };

    var iniciarFormularioModelo = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#seccion-catalogo-tipos-sistema-salas4xd').addClass('hidden');
        $('#seccionFormulariosTiposSistema').removeClass('hidden').empty().append(respuesta.formulario);

        select.crearSelect('select');

        if (respuesta.datos.flag !== null) {
            llenarCamposFormularioModelo(datos, respuesta);
            $('#tituloModelo').empty().html('Actualizar elemento');
        } else {
            $('#estatusModelo').addClass('hidden');
            $('#tituloModelo').empty().html('Nuevo elemento');
        }
    };

    var llenarCamposFormularioModelo = function () {
        var datos = arguments[0] || null;
        var respuesta = arguments[1] || null;
        $('#inputNombreModelo').val(datos.modelo);
    };

    var cargarEventosFormularioModelo = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1';
        var activacion;

        if (respuesta.datos.flag !== null) {
            id = datos.id;
            operacion = '2';
        }

        $('#btnGuardarModelo').on('click', function () {
            var equipo = $('#selectEquipo').val();
            var marca = $('#selectMarca').val();
            var nombre = $('#inputNombreModelo').val();
            var cvesae = $("#inputClaveSAE").val();

            if (operacion === '2') {
                var estatus = $('#selectEstatusModelo').val();
            } else {
                var estatus = '';
            }
            if (evento.validarFormulario('#formModelo')) {
                var data = {id: id, equipo: equipo, marca: marca, cvesae: cvesae, nombre: nombre, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogos/GuardarModelo', data, '#panelModelo', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-tipos-sistema-modelos');
                        $.each(respuesta, function (key, item) {
                            if (item.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-tipos-sistema-modelos', [item.Id, item.Nombre, item.IdLinea, item.Linea, item.IdMarca, item.Marca, item.ClaveSAE, activacion]);
                        });
                        $('#seccion-catalogo-tipos-sistema-salas4xd').removeClass('hidden');
                        $('#seccionFormulariosTiposSistema').addClass('hidden');
                        evento.mostrarMensaje('.errorTiposSistemaModelos', true, 'Datos guardados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorFormularioModelo', false, 'Ya existe el elemento para esa Marca y Equipo en el catálogo', 5000);
                    } else {
                        evento.mostrarMensaje('.errorFormularioModelo', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }
        });

        eventoRegresar();
    };

    //Componentes



    $('#data-table-tipos-sistema-componentes tbody').on('click', 'tr', function () {
        var datos = $('#data-table-tipos-sistema-componentes').DataTable().row(this).data();
        var data = {id: datos[0], componente: datos[1]};

        mostrarFormularioComponente(data);
    });

    $('#btnAgregarComponente').on('click', function () {
        mostrarFormularioComponente();
    });

    var mostrarFormularioComponente = function () {
        var datos = arguments[0] || '';

        evento.enviarEvento('EventoCatalogos/MostrarFormularioComponente', datos, '#seccion-catalogo-tipos-sistema-salas4xd', function (respuesta) {
            iniciarFormularioComponente(respuesta, datos);
            cargarEventosFormularioComponente(respuesta, datos);
        });
    };

    var iniciarFormularioComponente = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#seccion-catalogo-tipos-sistema-salas4xd').addClass('hidden');
        $('#seccionFormulariosTiposSistema').removeClass('hidden').empty().append(respuesta.formulario);

        select.crearSelect('select');

        if (respuesta.datos.flag !== null) {
            llenarCamposFormularioComponente(datos, respuesta);
            $('#tituloComponente').empty().html('Actualizar sub-elemento');
        } else {
            $('#estatusComponente').addClass('hidden');
            $('#tituloComponente').empty().html('Nuevo sub-elemento');
        }
    };

    var llenarCamposFormularioComponente = function () {
        var datos = arguments[0] || null;
        var respuesta = arguments[1] || null;
        $('#inputNombreComponente').val(datos.componente);
    };

    var cargarEventosFormularioComponente = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1';
        var activacion;

        if (respuesta.datos.flag !== null) {
            id = datos.id;
            operacion = '2';
        }

        $('#btnGuardarComponente').on('click', function () {
            var modelo = $('#selectModelo').val();
            var marca = $('#selectMarca').val();
            var nombre = $('#inputNombreComponente').val();
            var cvesae = $("#inputClaveSAE").val();

            if (operacion === '2') {
                var estatus = $('#selectEstatusComponente').val();
            } else {
                var estatus = '';
            }
            if (evento.validarFormulario('#formComponente')) {
                var data = {id: id, marca: marca, modelo: modelo, cvesae: cvesae, nombre: nombre, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogos/GuardarComponente', data, '#panelComponente', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-tipos-sistema-componentes');
                        $.each(respuesta, function (key, item) {
                            if (item.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-tipos-sistema-componentes', [item.Id, item.Nombre, item.Marca, item.Elemento, item.ClaveSAE, activacion]);
                        });
                        $('#seccion-catalogo-tipos-sistema-salas4xd').removeClass('hidden');
                        $('#seccionFormulariosTiposSistema').addClass('hidden');
                        evento.mostrarMensaje('.errorTiposSistemaComponentes', true, 'Datos guardados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorFormularioComponente', false, 'Ya existe el subelemento para ese elemento en el catálogo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorFormularioComponente', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }
        });

        eventoRegresar();
    };

    //Todas las pestañas


    var eventosMultiselect = function () {
        var respuesta = arguments[0];
        var panel = arguments[1];
        var mensajeError = arguments[2];
        var seccion = arguments[3];

        if (respuesta.datos.ids !== null) {
            var equipo = respuesta.datos.ids[0].IdEquipo;
            var marca = respuesta.datos.ids[0].IdMarca;
            var modelo = respuesta.datos.ids[0].IdModelo;
        }

        //Trae datos para select Equipo
        $('#selectTiposSistemas').on('change', function (event, data) {
            if ($(this).val() != '') {
                select.setOpcionesSelectAjax('#selectTiposSistemas', ['EventoCatalogos/SelectEquipos', panel], ['#selectEquipo', 'IdSistema'], function (respuesta) {
                    if (respuesta) {
                        $('#selectEquipo').removeAttr('disabled');
                        select.cambiarOpcion('#selectEquipo', equipo);
                    } else {
                        $('#selectEquipo').attr('disabled', 'disabled');
                        select.cambiarOpcion('#selectEquipo', '');
                        evento.mostrarMensaje(mensajeError, false, 'No existen registros.', 3000);
                    }
                });
            } else {
                $('#selectEquipo').val('').trigger('change');
                $('#selectEquipo').attr('disabled', 'disabled');
            }
        });

        //Trae datos para select Marcas
        $('#selectEquipo').on('change', function (event, data) {
            if (seccion !== '3') {
                if ($(this).val() != '') {
                    select.setOpcionesSelectAjax('#selectEquipo', ['EventoCatalogos/SelectMarcas', panel], ['#selectMarca', 'IdEquipo'], function (respuesta) {
                        if (respuesta) {
                            $('#selectMarca').removeAttr('disabled');
                            select.cambiarOpcion('#selectMarca', marca);
                        } else {
                            $('#selectMarca').attr('disabled', 'disabled');
                            select.cambiarOpcion('#selectMarca', '');
                            evento.mostrarMensaje(mensajeError, false, 'No existen registros.', 3000);
                        }
                    });
                } else {
                    $('#selectMarca').val('').trigger('change');
                    $('#selectMarca').attr('disabled', 'disabled');
                }
            }
        });

        //Trae datos para select Modelos
        $('#selectMarca').on('change', function (event, data) {
            if (seccion !== '4') {
                if ($(this).val() != '') {
                    select.setOpcionesSelectAjax('#selectMarca', ['EventoCatalogos/SelectModelos', panel], ['#selectModelo', 'IdMarca'], function (respuesta) {
                        if (respuesta) {
                            $('#selectModelo').removeAttr('disabled');
                            select.cambiarOpcion('#selectModelo', modelo);
                        } else {
                            $('#selectModelo').attr('disabled', 'disabled');
                            select.cambiarOpcion('#selectModelo', '');
                            evento.mostrarMensaje(mensajeError, false, 'No existen registros.', 3000);
                        }
                    });
                } else {
                    $('#selectModelo').val('').trigger('change');
                    $('#selectModelo').attr('disabled', 'disabled');
                }
            }
        });
    };



    var eventoRegresar = function () {
        $('#btnModalConfirmar').off('click');
        $('#btnRegresarListaCatalogoTiposSistema').on('click', function () {
            $('#seccion-catalogo-tipos-sistema-salas4xd').removeClass('hidden');
            $('#seccionFormulariosTiposSistema').addClass('hidden');
        });
    };


    var InsertarActividadesMantenimientoX4D = function () {
        var node = arguments[0];
     
        if (node.parent.indexOf("sistema-") === -1) {
            var padre = node.parent;
        } else {
            var padre = false;
        }
        var sistema = $("#" + node.parent).attr("sistema");
        var datos = {
            "actividad": node.text,
            "sistema": sistema,
            "padre": padre || 0

        };
    console.log(node);
        evento.enviarEvento('EventoCatalogos/GuardarActividadMantenimiento', datos, '#seccion-catalogo-tipos-sistema-salas4xd', function (respuesta) {
            if (!respuesta) {
                evento.mostrarMensaje('#errorActividades', false, 'No se puede utilizar ese nombre de Actividad o ya existe. Verifique la información.', 4000);

                cargaJsonActividadesMantenimiento();
            } else {
                cargaJsonActividadesMantenimiento();
            }

        });
    };


//Elimina
    $('#jstree-default').on("delete_node.jstree", function (e, data) {
        borrarActividadesMantenimientoX4D(data.node.id);

    });

    var borrarActividadesMantenimientoX4D = function () {
        var nodeId = arguments[0] || '';
        var datos = {id: nodeId};
        console.log(nodeId);
        evento.enviarEvento('EventoCatalogos/BorrarActividadMantenimiento', datos, '#seccion-catalogo-tipos-sistema-salas4xd', function (respuesta) {
            cargaJsonActividadesMantenimiento();
        });
    };


    $('#jstree-default').jstree({
        "core": {
            "themes": {
                "responsive": false
            },
            "check_callback": function (op, node, par, pos, more) {

                if (op === "delete_node") {
                    if (node.parent === '#') {
                    evento.mostrarMensaje('#errorActividades', false, 'No se pueden eliminar los sistemas', 4000);
                        return false;
                    }
                }
                if (op === "rename_node") {
                    if (node.parent === '#') {
                       
                       evento.mostrarMensaje('#errorActividades', false, 'No se pueden renombrar los sistemas', 4000);

                        return false;
                    }
                }
                
                if(op === "create_node"){
                    
                    console.log(par.parents.length);
                        
                     if(par.parents.length >= 4){
                     evento.mostrarMensaje('#errorActividades', false, 'No se pueden crear mas actividades Verifique la información.', 4000);

                        return false;
                         
                     }
               
                    
                }
                
                
            }  
            
        },
        "types": {
            "default": {
                "icon": "fa fa-file text-info fa-lg"
            },
            "#": {
                "max_children": 1,
                "max_depth": 4, 
                "valid_children": ["root"]
            }

            ,
            "file": {
                "icon": "fa fa-file text-info fa-lg"
            }
        },
        "plugins": ['types','contextmenu','changed','massload','state'],
        
        
   

        "contextmenu": {
            "items": function ($node) {
                var tree = $("#jstree-default").jstree(true);
                return {
                    "Crear": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Crear",
                        "action": function (obj) {
                            $node = tree.create_node($node);
                            tree.edit($node);
                        },
                        "_disabled": false
                    },
                    "Renombrar": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Renombrar",
                        "action": function (obj) {
                            tree.edit($node);
                        }
                    },
                    "Eliminar": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Eliminar",
                        "action": function (obj) {
                            tree.delete_node($node);
                        }
                    }
                };
            }
        }



    });
    
    
//    $(function () {
//  $("#jstree-default").on("changed.jstree", function (e, data) {
//      var selec = data.node;
////      console.log(data.changed.selected,"seleccionado"); // newly selected
////      console.log(data.changed.deselected,"deseleccionado"); // newly deselected
//       var par = $('#jstree-default').jstree('is_parent',selec);
////       console.log(par);
//       $.each(data, function (key, value) {
////           console.log(value.parent);
//            
//       });
//       var jsonn = $("#jstree-default").jstree('jstree.defaults.massload');
////       console.log(jsonn);
//    });
//});
//$(function () {
//  $("#jstree-default").jstree({
//    "massload" : {
////      "url" : "/some/path",
//      "data" : function (nodes) {
//        return { "ids" : nodes.join(",") };
//      }
//    }
//  });
//});
    $('#jstree-default').on('rename_node.jstree', function (e, data) {
        if (data.node.id > 0) {
        } else {
            InsertarActividadesMantenimientoX4D(data.node);


        }
    });

    var ActualizaActividadesMantenimientoX4D = function () {
        var node = arguments[0];
        if (node.parent.indexOf("sistema-") === -1) {
            var padre = node.parent;
        } else {
            var padre = false;
        }
        var sistema = $("#" + node.parent).attr("sistema");
        var datos = {
            "actividad": node.text,
            "id": node.id,
            "sistema": sistema,
            "padre": padre || 0
        };

        evento.enviarEvento('EventoCatalogos/ActualizarActividadMantenimiento', datos, '#seccion-catalogo-tipos-sistema-salas4xd', function (respuesta) {
            if (!respuesta) {

              //  evento.mostrarMensaje('#errorActividades', false, 'No se puede utilizar ese nombre de Actividad o ya existe. Verifique la información.', 4000);

                cargaJsonActividadesMantenimiento();
            } else {


                cargaJsonActividadesMantenimiento();
            }

        });
    };



    function cargaJsonActividadesMantenimiento() {
        evento.enviarEvento('EventoCatalogos/ActividadesMantenimientoJson', [], '#seccion-catalogo-tipos-sistema-salas4xd', function (respuesta) {
            $('#jstree-default').jstree(true).settings.core.data = respuesta.json;
            $('#jstree-default').jstree(true).refresh();

        });
    }

    function  destruirJsonActividadesMantenimiento() {
        evento.enviarEvento('EventoCatalogos/ActividadesMantenimientoJson', [], '#seccion-catalogo-tipos-sistema-salas4xd', function (respuesta) {
            $('#jstree-default').jstree(true).settings.core.data = respuesta.json;
            $('#jstree-default').jstree(true).destroy();


        });

    }



});