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
    //Creando tabla de perfiles
    tabla.generaTablaPersonal('#data-table-clientes', null, null, true, true);
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();
    
    //Evento que permite actualizar el cliente
    $('#data-table-clientes tbody').on('click', 'tr', function () {
        var datos = $('#data-table-clientes').DataTable().row(this).data();
        var data = {cliente: datos[0]};
        enviarEvento(data, datos);
    });

    $('#btnAgregarCliente').on('click', function () {
        enviarEvento();
    });

    var enviarEvento = function () {

        var data = arguments[0] || '';
        var datos = arguments[1] || null;

        evento.enviarEvento('EventoCatalogoSucursales/MostrarFormularioClientes', data, '#seccionClientes', function (respuesta) {
            iniciarElementosFormulario(respuesta, datos);
            cargarEventosFormulario(respuesta, datos);
        });
    };

    var iniciarElementosFormulario = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#listaClientes').addClass('hidden');
        $('#formularioClientes').removeClass('hidden').empty().append(respuesta.formulario);
        $('#btnRegresarClientes').removeClass('hidden');

        select.crearSelect('#selectActualizarPaisCliente');
        select.crearSelect('#selectActualizarEstadoCliente');
        select.crearSelect('#selectActualizarMunicipioCliente');
        select.crearSelect('#selectActualizarColoniaCliente');

        //mascara para telefono 1
        $('#inputActualizarTelefono1Cliente').mask("99-999-99999999");
        //mascara para telefono 2
        $('#inputActualizarTelefono2Cliente').mask("99-999-99999999");

        //evento para mostrar eventos de los selectPais, selectEstado, selectMunucipio
        eventosSelectLocalidades(respuesta);

        if (respuesta.datos.ids !== null) {
            llenarCamposFormularioActualizar(respuesta, datos);
            $('#tituloCliente').empty().html('Actualizar Cliente');
        } else {
            $('#tituloCliente').empty().html('Nuevo Cliente');
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
        $('#selectActualizarPaisCliente').on('change', function (event, data) {
            if ($('#selectActualizarPaisCliente').val() != '') {
                select.setOpcionesSelectAjax('#selectActualizarPaisCliente', ['EventoCatalogoCliente/SelectEstados', null], ['#selectActualizarEstadoCliente', 'IdPais'], function (respuesta) {
                    if (respuesta) {
                        $('#selectActualizarEstadoCliente').removeAttr('disabled');
                        if (typeof data !== 'undefined') {
                            $('#selectActualizarEstadoCliente').val(data.estado).trigger('change', {municipio: data.municipio, colonia: data.colonia});
                            $('#selectActualizarEstadoCliente').attr('disabled', 'disabled');
                            $('#selectActualizarPaisCliente').attr('disabled', 'disabled');
                        } else {
                            $('#selectActualizarEstadoCliente').val(estado).trigger('change');
                        }
                    } else {
                        console.log('error en la consulta de estados');
                    }
                });
            } else {
                $('#selectActualizarEstadoCliente').val('').trigger('change');
                $('#selectActualizarEstadoCliente').attr('disabled', 'disabled');
            }
        });
        //Trae datos para select Estados
        $('#selectActualizarEstadoCliente').on('change', function (event, data) {
            if ($('#selectActualizarEstadoCliente').val() != '') {
                select.setOpcionesSelectAjax('#selectActualizarEstadoCliente', ['EventoCatalogoCliente/SelectMunicipios', null], ['#selectActualizarMunicipioCliente', 'IdEstado'], function (respuesta) {
                    if (respuesta) {
                        $('#selectActualizarMunicipioCliente').removeAttr('disabled');
                        if (typeof data !== 'undefined') {
                            $('#selectActualizarMunicipioCliente').val(data.municipio).trigger('change', {colonia: data.colonia});
                            $('#selectActualizarMunicipioCliente').attr('disabled', 'disabled');
                        } else {
                            $('#selectActualizarMunicipioCliente').val(municipio).trigger('change');

                        }
                    } else {
                        console.log('error en la consulta de municipios');
                    }
                });
            } else {
                $('#selectActualizarMunicipioCliente').val('').trigger('change');
                $('#selectActualizarMunicipioCliente').attr('disabled', 'disabled');
            }
        });
        //Trae datos para select Municipios
        $('#selectActualizarMunicipioCliente').on('change', function (event, data) {
            if ($('#selectActualizarMunicipioCliente').val() != '') {
                select.setOpcionesSelectAjax('#selectActualizarMunicipioCliente', ['EventoCatalogoCliente/SelectColonias', null], ['#selectActualizarColoniaCliente', 'IdMunicipio'], function (respuesta) {
                    if (respuesta) {
                        $('#selectActualizarColoniaCliente').removeAttr('disabled');
                        if (typeof data !== 'undefined') {
                            $('#selectActualizarColoniaCliente').val(data.colonia).trigger('change');
                            evento.finalizarCargando('#modal-dialogo');
                        } else {
                            $('#selectActualizarColoniaCliente').val(colonia).trigger('change');
                        }
                    } else {
                        console.log('error en la consulta de colonias');
                    }
                });
            } else {
                $('#selectActualizarColoniaCliente').val('').trigger('change');
                $('#selectActualizarColoniaCliente').attr('disabled', 'disabled');
            }
        });
    };

    var llenarCamposFormularioActualizar = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var pais = respuesta.datos.ids[0].IdPais;

        $('#inputActualizarNombreCliente').val(datos[1]);
        $('#inputActualizarRazonSocialCliente').val(datos[2]);
        $('#inputActualizarRepresentanteCliente').val(datos[7]);
        $('#selectActualizarPaisCliente').val(pais).trigger('change');
        $('#inputActualizarCPCliente').val(datos[6]);
        $('#inputActualizarCalleCliente').val(datos[8]);
        $('#inputActualizarExtCliente').val(datos[10]);
        $('#inputActualizarIntCliente').val(datos[9]);
        $('#inputActualizarTelefono1Cliente').val(datos[11]);
        $('#inputActualizarTelefono2Cliente').val(datos[12]);
        $('#inputActualizarPaginaCliente').val(datos[14]);
        $('#inputActualizarEmailCliente').val(datos[13]);
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
        $('#btnActualizarBuscarCP').on('click', function () {
            var cp = $('#inputActualizarCPCliente').val();
            if (cp) {
                var data = {cp: cp};
                evento.enviarEvento('EventoCatalogoCliente/BuscarCP', data, null, function (respuesta) {
                    if (respuesta instanceof Array) {
                        evento.empezarCargando('#modal-dialogo');
                        var idPais = respuesta[0].IdPais;
                        var idEstado = respuesta[0].IdEstado;
                        var idMunicipio = respuesta[0].IdMunicipio;
                        var idColonia = respuesta[0].IdColonia;
                        $('#selectActualizarPaisCliente').val(idPais).trigger('change', {estado: idEstado, municipio: idMunicipio, colonia: idColonia});
                    } else {
                        evento.mostrarMensaje('.errorActualizarCliente', false, 'No existe ese CP', 3000);
                    }
                });
            } else {
                evento.mostrarMensaje('.errorActualizarCliente', false, 'Introsdusca un CP', 3000);
            }
        });

        //Limpia los select pais, estado, delegacion, colonia y el input CP en modal 
        $('#btnActualizarLimpiarCP').on('click', function () {
            $('#selectActualizarPaisCliente').val('').trigger('change');
            $('#selectActualizarEstadoCliente').val('').trigger('change');
            $('#selectActualizarMunicipioCliente').val('').trigger('change');
            $('#selectActualizarColoniaCliente').val('').trigger('change');
            $('#selectActualizarPaisCliente').removeAttr('disabled');
            $('#inputActualizarCPCliente').val('');
        });

        $('#btnGuardarCliente').on('click', function () {

            var nombre = $('#inputActualizarNombreCliente').val();
            var razonSocial = $('#inputActualizarRazonSocialCliente').val();
            var representante = $('#inputActualizarRepresentanteCliente').val();
            var pais = $('#selectActualizarPaisCliente').val();
            var estado = $('#selectActualizarEstadoCliente').val();
            var municipio = $('#selectActualizarMunicipioCliente').val();
            var colonia = $('#selectActualizarColoniaCliente').val();
            var calle = $('#inputActualizarCalleCliente').val();
            var ext = $('#inputActualizarExtCliente').val();
            var int = $('#inputActualizarIntCliente').val();
            var telefono1 = $('#inputActualizarTelefono1Cliente').val();
            var telefono2 = $('#inputActualizarTelefono2Cliente').val();
            var pagina = $('#inputActualizarPaginaCliente').val();
            var email = $('#inputActualizarEmailCliente').val();

            if (evento.validarFormulario('#formActualizarCliente')) {
                var data = {id: id, nombre: nombre, razonSocial: razonSocial, representante: representante, pais: pais, estado: estado, municipio: municipio, colonia: colonia, calle: calle, ext: ext, int: int, telefono1: telefono1, telefono2: telefono2, pagina: pagina, email: email, operacion: operacion};
                evento.enviarEvento('EventoCatalogoCliente/Actualizar_Cliente', data, '#seccionClientes', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-clientes');
                        $.each(respuesta, function (key, valor) {
                            tabla.agregarFila('#data-table-clientes', [valor.Id, valor.Nombre, valor.RazonSocial, valor.Pais, valor.Estado, valor.Municipio, valor.CP, valor.Representante, valor.Calle, valor.NoInt, valor.NoExt, valor.Telefono1, valor.Telefono2, valor.Email, valor.Web], true);
                        });
                        $('#listaClientes').removeClass('hidden');
                        $('#formularioClientes').addClass('hidden');
                        $('#btnRegresarClientes').addClass('hidden');
                        evento.mostrarMensaje('.errorClientes', true, 'Datos Actualizados correctamente', 3000);
                    } else {
                        evento.mostrarMensaje('.errorActualizarCliente', false, 'Ya existe el Nombre del Cliente, por lo que ya no puedes repetirlo.', 3000);
                    }
                });
            }
        });

        $('#btnRegresarClientes').on('click', function () {
            $('#listaClientes').removeClass('hidden');
            $('#formularioClientes').addClass('hidden');
            $('#btnRegresarClientes').addClass('hidden');
        });
    };
});


