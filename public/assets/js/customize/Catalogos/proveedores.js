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
    //Creando tabla de proveedores
    tabla.generaTablaPersonal('#data-table-proveedores', null, null, true, true);
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();

    //Evento que permite actualizar la proveedor
    $('#data-table-proveedores tbody').on('click', 'tr', function () {
        var datos = $('#data-table-proveedores').DataTable().row(this).data();
        var data = {proveedor: datos[0]};
        enviarEvento(data, datos);
    });

    $('#btnAgregarProveedor').on('click', function () {
        enviarEvento();
    });

    var enviarEvento = function () {

        var data = arguments[0] || '';
        var datos = arguments[1] || null;

        evento.enviarEvento('EventoCatalogoProveedores/MostrarFormularioProveedor', data, '#seccionProveedores', function (respuesta) {
            iniciarElementosFormulario(respuesta, datos);
            cargarEventosFormulario(respuesta, datos);
        });
    };

    var iniciarElementosFormulario = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        $('#listaProveedores').addClass('hidden');
        $('#formularioProveedor').removeClass('hidden').empty().append(respuesta.formulario);
        $('#btnRegresarProveedores').removeClass('hidden');

        select.crearSelect('#selectActualizarPaisProveedores');
        select.crearSelect('#selectActualizarEstadoProveedores');
        select.crearSelect('#selectActualizarMunicipioProveedores');
        select.crearSelect('#selectActualizarColoniaProveedores');
        select.crearSelect('select');

        //mascara para telefono 1
        $('#inputActualizarTelefono1Proveedores').mask("99-999-9999999");
        //mascara para telefono 2
        $('#inputActualizarTelefono2Proveedores').mask("99-999-9999999");

        //evento para mostrar eventos de los selectPais, selectEstado, selectMunucipio
        eventosSelectLocalidades(respuesta);

        if (respuesta.datos.ids !== null) {
            llenarCamposFormularioActualizar(respuesta, datos);
            $('#tituloProveedor').empty().html('Actualizar Proveedor');
        } else {
            $('#estatus').addClass('hidden');
            $('#tituloProveedor').empty().html('Nuevo Proveedor');
        }
    };

    var eventosSelectLocalidades = function () {

        var respuesta = arguments[0];

        if (respuesta.datos.ids !== null) {
            var estado = respuesta.datos.ids[0].IdEstado;
            var municipio = respuesta.datos.ids[0].IdMunicipio;
            var colonia = (respuesta.datos.ids[0].IdColonia);
        }

        //Trae datos para select Pais
        $('#selectActualizarPaisProveedores').on('change', function (event, data) {
            if ($('#selectActualizarPaisProveedores').val() != '') {
                select.setOpcionesSelectAjax('#selectActualizarPaisProveedores', ['EventoCatalogoProveedores/SelectEstados', null], ['#selectActualizarEstadoProveedores', 'IdPais'], function (respuesta) {
                    if (respuesta) {
                        $('#selectActualizarEstadoProveedores').removeAttr('disabled');
                        if (typeof data !== 'undefined') {
                            $('#selectActualizarEstadoProveedores').val(data.estado).trigger('change', {municipio: data.municipio, colonia: data.colonia});
                            $('#selectActualizarEstadoProveedores').attr('disabled', 'disabled');
                            $('#selectActualizarPaisProveedores').attr('disabled', 'disabled');
                        } else {
                            $('#selectActualizarEstadoProveedores').val(estado).trigger('change');
                        }
                    } else {
                        console.log('error en la consulta de estados');
                    }
                });
            } else {
                $('#selectActualizarEstadoProveedores').val('').trigger('change');
                $('#selectActualizarEstadoProveedores').attr('disabled', 'disabled');
            }
        });
        //Trae datos para select Estados
        $('#selectActualizarEstadoProveedores').on('change', function (event, data) {
            if ($('#selectActualizarEstadoProveedores').val() != '') {
                select.setOpcionesSelectAjax('#selectActualizarEstadoProveedores', ['EventoCatalogoProveedores/SelectMunicipios', null], ['#selectActualizarMunicipioProveedores', 'IdEstado'], function (respuesta) {
                    if (respuesta) {
                        $('#selectActualizarMunicipioProveedores').removeAttr('disabled');
                        if (typeof data !== 'undefined') {
                            $('#selectActualizarMunicipioProveedores').val(data.municipio).trigger('change', {colonia: data.colonia});
                            $('#selectActualizarMunicipioProveedores').attr('disabled', 'disabled');
                        } else {
                            $('#selectActualizarMunicipioProveedores').val(municipio).trigger('change');

                        }
                    } else {
                        console.log('error en la consulta de municipios');
                    }
                });
            } else {
                $('#selectActualizarMunicipioProveedores').val('').trigger('change');
                $('#selectActualizarMunicipioProveedores').attr('disabled', 'disabled');
            }
        });
        //Trae datos para select Municipios
        $('#selectActualizarMunicipioProveedores').on('change', function (event, data) {
            if ($('#selectActualizarMunicipioProveedores').val() != '') {
                select.setOpcionesSelectAjax('#selectActualizarMunicipioProveedores', ['EventoCatalogoProveedores/SelectColonias', null], ['#selectActualizarColoniaProveedores', 'IdMunicipio'], function (respuesta) {
                    if (respuesta) {
                        $('#selectActualizarColoniaProveedores').removeAttr('disabled');
                        if (typeof data !== 'undefined') {
                            $('#selectActualizarColoniaProveedores').val(data.colonia).trigger('change');
                            evento.finalizarCargando('#modal-dialogo');
                        } else {
                            $('#selectActualizarColoniaProveedores').val(colonia).trigger('change');
                        }
                    } else {
                        console.log('error en la consulta de colonias');
                    }
                });
            } else {
                $('#selectActualizarColoniaProveedores').val('').trigger('change');
                $('#selectActualizarColoniaProveedores').attr('disabled', 'disabled');
            }
        });
    };

    var llenarCamposFormularioActualizar = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var pais = respuesta.datos.ids[0].IdPais;

        $('#inputActualizarNombreProveedores').val(datos[1]);
        $('#inputActualizarRazonProveedores').val(datos[2]);
        $('#selectActualizarPaisProveedores').val(pais).trigger('change');
        $('#inputActualizarCPProveedores').val(datos[6]);
        $('#inputActualizarCalleProveedores').val(datos[7]);
        $('#inputActualizarExtProveedores').val(datos[8]);
        $('#inputActualizarIntProveedores').val(datos[9]);
        $('#inputActualizarTelefono1Proveedores').val(datos[10]);
        $('#inputActualizarTelefono2Proveedores').val(datos[11]);
    };

    var cargarEventosFormulario = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1'
        if (respuesta.datos.ids !== null) {
            id = datos[0];
            operacion = '2';
        }

        //Evento que busca el CP y llena los select estado, delegacion y colonia en el modal
        $('#btnActualizarBuscarCPProveedores').on('click', function () {
            var cp = $('#inputActualizarCPProveedores').val();
            if (cp) {
                var data = {cp: cp};
                evento.enviarEvento('EventoCatalogoProveedores/BuscarCP', data, null, function (respuesta) {
                    if (respuesta instanceof Array) {
                        evento.empezarCargando('#modal-dialogo');
                        var idPais = respuesta[0].IdPais;
                        var idEstado = respuesta[0].IdEstado;
                        var idMunicipio = respuesta[0].IdMunicipio;
                        var idColonia = respuesta[0].IdColonia;
                        $('#selectActualizarPaisProveedores').val(idPais).trigger('change', {estado: idEstado, municipio: idMunicipio, colonia: idColonia});
                    } else {
                        evento.mostrarMensaje('.errorActualizarProveedores', false, 'No existe ese CP', 3000);
                    }
                });
            } else {
                evento.mostrarMensaje('.errorActualizarProveedores', false, 'Introsdusca un CP', 3000);
            }
        });

        //Limpia los select pais, estado, delegacion, colonia y el input CP en modal 
        $('#btnActualizarLimpiarCPProveedores').on('click', function () {
            $('#selectActualizarPaisProveedores').val('').trigger('change');
            $('#selectActualizarEstadoProveedores').val('').trigger('change');
            $('#selectActualizarMunicipioProveedores').val('').trigger('change');
            $('#selectActualizarColoniaProveedores').val('').trigger('change');
            $('#selectActualizarPaisProveedores').removeAttr('disabled');
            $('#inputActualizarCPProveedores').val('');
        });

        $('#btnGuardarProveedor').on('click', function () {

            var nombre = $('#inputActualizarNombreProveedores').val();
            var razon = $('#inputActualizarRazonProveedores').val();
            var cliente = $('#selectActualizarClienteProveedores').val();
            var pais = $('#selectActualizarPaisProveedores').val();
            var estado = $('#selectActualizarEstadoProveedores').val();
            var municipio = $('#selectActualizarMunicipioProveedores').val();
            var colonia = $('#selectActualizarColoniaProveedores').val();
            var calle = $('#inputActualizarCalleProveedores').val();
            var ext = $('#inputActualizarExtProveedores').val();
            var int = $('#inputActualizarIntProveedores').val();
            var telefono1 = $('#inputActualizarTelefono1Proveedores').val();
            var telefono2 = $('#inputActualizarTelefono2Proveedores').val();
            var activacion;

            if (operacion === '2') {
                var estatus = $('#selectActualizarEstatusProveedores').val();
            } else {
                var estatus = '';
            }

            if (evento.validarFormulario('#formActualizarProveedores')) {
                var data = {id: id, nombre: nombre, razon: razon, cliente: cliente, pais: pais, estado: estado, municipio: municipio, colonia: colonia, calle: calle, ext: ext, int: int, telefono1: telefono1, telefono2: telefono2, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogoProveedores/Actualizar_Proveedor', data, '#seccionProveedores', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-proveedores');
                        $.each(respuesta, function (key, valor) {
                            if (valor.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-proveedores', [valor.Id, valor.Nombre, valor.RazonSocial, valor.Pais, valor.Estado, valor.Municipio, valor.CP, valor.Calle, valor.NoExt, valor.NoInt, valor.Telefono1, valor.Telefono2, activacion], true);
                        });
                        $('#listaProveedores').removeClass('hidden');
                        $('#formularioProveedor').addClass('hidden');
                        $('#btnRegresarProveedores').addClass('hidden');
                        evento.mostrarMensaje('.errorProveedores', true, 'Datos Actualizados correctamente', 3000);
                    } else {
                        evento.mostrarMensaje('.errorActualizarProveedores', false, 'Ya existe el Nombre de la Proveedor, por lo que ya no puedes repetirlo.', 3000);
                    }
                });
            }
        });

        $('#btnRegresarProveedores').on('click', function () {
            $('#listaProveedores').removeClass('hidden');
            $('#formularioProveedor').addClass('hidden');
            $('#btnRegresarProveedores').addClass('hidden');
        });
    };
});


