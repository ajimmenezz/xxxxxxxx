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
    //Creando tabla de sucursales
    tabla.generaTablaPersonal('#data-table-sucursales', null, null, true, true);
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();

    var permisoEditar = null;
    //Evento que permite actualizar la sucursal
    $('#data-table-sucursales tbody').on('click', 'tr', function () {
        var datos = $('#data-table-sucursales').DataTable().row(this).data();
        var data = {sucursal: datos[0]};

        enviarEvento(data, datos);
    });

    $('#btnAgregarSucursal').on('click', function () {
        enviarEvento();
    });

    var enviarEvento = function () {
        var data = arguments[0] || '';
        var datos = arguments[1] || null;

        evento.enviarEvento('EventoCatalogoSucursales/MostrarFormularioSucursales', data, '#seccionSucursales', function (respuesta) {
            permisoEditar = respuesta.datos.permisoEditar;            
            iniciarElementosFormulario(respuesta, datos);
            cargarEventosFormulario(respuesta, datos);
        });
    };

    var iniciarElementosFormulario = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#listaSucursales').addClass('hidden');
        $('#formularioSucursal').removeClass('hidden').empty().append(respuesta.formulario);
        $('#btnRegresarSucursales').removeClass('hidden');

        select.crearSelect('#selectActualizarPaisSucursales');
        select.crearSelect('#selectActualizarEstadoSucursales');
        select.crearSelect('#selectActualizarMunicipioSucursales');
        select.crearSelect('#selectActualizarColoniaSucursales');
        select.crearSelect('select');

        //mascara para telefono 1
        $('#inputActualizarTelefono1Sucursales').mask("99-999-99999999");
        //mascara para telefono 2
        $('#inputActualizarTelefono2Sucursales').mask("99-999-99999999");

        //evento para mostrar eventos de los selectPais, selectEstado, selectMunucipio
        eventosSelectLocalidades(respuesta);

        if (respuesta.datos.ids !== null) {
            llenarCamposFormularioActualizar(respuesta, datos);
            $('#tituloSucursal').empty().html('Actualizar Sucursal');
        } else {
            $('#estatus').addClass('hidden');
            $('#tituloSucursal').empty().html('Nueva Sucursal');
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
        $('#selectActualizarPaisSucursales').on('change', function (event, data) {
            if ($('#selectActualizarPaisSucursales').val() != '') {
                select.setOpcionesSelectAjax('#selectActualizarPaisSucursales', ['EventoCatalogoSucursales/SelectEstados', null], ['#selectActualizarEstadoSucursales', 'IdPais'], function (respuesta) {
                    if (respuesta) {
                        if (permisoEditar) {
                            $('#selectActualizarEstadoSucursales').removeAttr('disabled');
                        }
                        if (typeof data !== 'undefined') {
                            $('#selectActualizarEstadoSucursales').val(data.estado).trigger('change', {municipio: data.municipio, colonia: data.colonia});
                            $('#selectActualizarEstadoSucursales').attr('disabled', 'disabled');
                            $('#selectActualizarPaisSucursales').attr('disabled', 'disabled');
                        } else {
                            $('#selectActualizarEstadoSucursales').val(estado).trigger('change');
                        }
                    } else {
                        console.log('error en la consulta de estados');
                    }
                });
            } else {
                $('#selectActualizarEstadoSucursales').val('').trigger('change');
                $('#selectActualizarEstadoSucursales').attr('disabled', 'disabled');
            }
        });

        //Trae datos para select Estados
        $('#selectActualizarEstadoSucursales').on('change', function (event, data) {
            if ($('#selectActualizarEstadoSucursales').val() != '') {
                select.setOpcionesSelectAjax('#selectActualizarEstadoSucursales', ['EventoCatalogoSucursales/SelectMunicipios', null], ['#selectActualizarMunicipioSucursales', 'IdEstado'], function (respuesta) {
                    if (respuesta) {
                        if (permisoEditar) {
                            $('#selectActualizarMunicipioSucursales').removeAttr('disabled');
                        }
                        if (typeof data !== 'undefined') {
                            $('#selectActualizarMunicipioSucursales').val(data.municipio).trigger('change', {colonia: data.colonia});
                            $('#selectActualizarMunicipioSucursales').attr('disabled', 'disabled');
                        } else {
                            $('#selectActualizarMunicipioSucursales').val(municipio).trigger('change');

                        }
                    } else {
                        console.log('error en la consulta de municipios');
                    }
                });
            } else {
                $('#selectActualizarMunicipioSucursales').val('').trigger('change');
                $('#selectActualizarMunicipioSucursales').attr('disabled', 'disabled');
            }
        });

        //Trae datos para select Municipios
        $('#selectActualizarMunicipioSucursales').on('change', function (event, data) {
            if ($('#selectActualizarMunicipioSucursales').val() != '') {
                select.setOpcionesSelectAjax('#selectActualizarMunicipioSucursales', ['EventoCatalogoSucursales/SelectColonias', null], ['#selectActualizarColoniaSucursales', 'IdMunicipio'], function (respuesta) {
                    if (respuesta) {
                        if (permisoEditar) {
                            $('#selectActualizarColoniaSucursales').removeAttr('disabled');
                        }
                        if (typeof data !== 'undefined') {
                            $('#selectActualizarColoniaSucursales').val(data.colonia).trigger('change');
                            evento.finalizarCargando('#modal-dialogo');
                        } else {
                            $('#selectActualizarColoniaSucursales').val(colonia).trigger('change');
                        }
                    } else {
                        console.log('error en la consulta de colonias');
                    }
                });
            } else {
                $('#selectActualizarColoniaSucursales').val('').trigger('change');
                $('#selectActualizarColoniaSucursales').attr('disabled', 'disabled');
            }
        });
    };

    var llenarCamposFormularioActualizar = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var responsable = respuesta.datos.ids[0].IdUsuario;
        var cliente = respuesta.datos.ids[0].IdCliente;
        var region = respuesta.datos.ids[0].IdRegionCliente;
        var pais = respuesta.datos.ids[0].IdPais;
        var unidadNegocio = respuesta.datos.ids[0].IdUnidadNegocio;

        $('#inputActualizarNombreSucursales').val(datos[1]);
        $('#inputActualizarCinemexSucursales').val(datos[16]);
        $('#selectActualizarResponsableSucursales').val(responsable).trigger('change');
        $('#selectActualizarClienteSucursales').val(cliente).trigger('change');
        $('#selectActualizarRegionSucursales').val(region).trigger('change');
        $('#selectActualizarUnidadNegocioSucursales').val(unidadNegocio).trigger('change');
        $('#selectActualizarPaisSucursales').val(pais).trigger('change');
        $('#inputActualizarCPSucursales').val(datos[9]);
        $('#inputActualizarCalleSucursales').val(datos[10]);
        $('#inputActualizarExtSucursales').val(datos[12]);
        $('#inputActualizarIntSucursales').val(datos[11]);
        $('#inputActualizarTelefono1Sucursales').val(datos[13]);
        $('#inputActualizarTelefono2Sucursales').val(datos[14]);
        $('#inputAlias').val(datos[17]);
        $('#inputCentroCostos').val(datos[18]);
        $('#inputActualizarDominio').val(datos[19]);
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
        $('#btnActualizarBuscarCPSucursales').on('click', function () {
            var cp = $('#inputActualizarCPSucursales').val();
            if (cp) {
                var data = {cp: cp};
                evento.enviarEvento('EventoCatalogoSucursales/BuscarCP', data, null, function (respuesta) {
                    if (respuesta instanceof Array) {
                        evento.empezarCargando('#modal-dialogo');
                        var idPais = respuesta[0].IdPais;
                        var idEstado = respuesta[0].IdEstado;
                        var idMunicipio = respuesta[0].IdMunicipio;
                        var idColonia = respuesta[0].IdColonia;
                        $('#selectActualizarPaisSucursales').val(idPais).trigger('change', {estado: idEstado, municipio: idMunicipio, colonia: idColonia});
                    } else {
                        evento.mostrarMensaje('.errorActualizarSucursales', false, 'No existe ese CP', 3000);
                    }
                });
            } else {
                evento.mostrarMensaje('.errorActualizarSucursales', false, 'Introsdusca un CP', 3000);
            }
        });

        //Limpia los select pais, estado, delegacion, colonia y el input CP en modal 
        $('#btnActualizarLimpiarCPSucursales').on('click', function () {
            $('#selectActualizarPaisSucursales').val('').trigger('change');
            $('#selectActualizarEstadoSucursales').val('').trigger('change');
            $('#selectActualizarMunicipioSucursales').val('').trigger('change');
            $('#selectActualizarColoniaSucursales').val('').trigger('change');
            if (permisoEditar) {
                $('#selectActualizarPaisSucursales').removeAttr('disabled');
            }
            $('#inputActualizarCPSucursales').val('');
        });

        $('#btnGuardarSucursales').on('click', function () {

            var nombre = $('#inputActualizarNombreSucursales').val();
            var cinemex = $('#inputActualizarCinemexSucursales').val();
            var responsable = $('#selectActualizarResponsableSucursales').val();
            var cliente = $('#selectActualizarClienteSucursales').val();
            var region = $('#selectActualizarRegionSucursales').val();
            var unidadNegocio = $('#selectActualizarUnidadNegocioSucursales').val();
            var pais = $('#selectActualizarPaisSucursales').val();
            var estado = $('#selectActualizarEstadoSucursales').val();
            var municipio = $('#selectActualizarMunicipioSucursales').val();
            var colonia = $('#selectActualizarColoniaSucursales').val();
            var calle = $('#inputActualizarCalleSucursales').val();
            var ext = $('#inputActualizarExtSucursales').val();
            var int = $('#inputActualizarIntSucursales').val();
            var telefono1 = $('#inputActualizarTelefono1Sucursales').val();
            var telefono2 = $('#inputActualizarTelefono2Sucursales').val();
            var alias = $('#inputAlias').val();
            var centroCostos = $('#inputCentroCostos').val();
            var dominio = $('#inputActualizarDominio').val();
            var localForaneo = $('#selectActualizarLocalForaneoSucursales').val();
            var activacion;

            if (operacion === '2') {
                var estatus = $('#selectActualizarEstatusSucursales').val();
            } else {
                var estatus = '';
            }
            if (evento.validarFormulario('#formActualizarSucursales')) {
                var data = {
                    id: id, 
                    nombre: nombre, 
                    cinemex: cinemex, 
                    responsable: responsable, 
                    cliente: cliente, 
                    region: region, 
                    unidadNegocio: unidadNegocio, 
                    pais: pais, 
                    estado: estado, 
                    municipio: municipio, 
                    colonia: colonia, 
                    calle: calle, 
                    ext: ext, 
                    int: int, 
                    telefono1: telefono1, 
                    telefono2: telefono2, 
                    estatus: estatus, 
                    operacion: operacion, 
                    alias: alias, 
                    centroCostos: centroCostos, 
                    dominio: dominio,
                    localForaneo: localForaneo};
                evento.enviarEvento('EventoCatalogoSucursales/Actualizar_Sucursal', data, '#seccionSucursales', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-sucursales');
                        $.each(respuesta, function (key, valor) {
                            if (valor.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-sucursales', [valor.Id, valor.Nombre, valor.Cliente, valor.Region, valor.Responsable, valor.UnidadNegocio, valor.Pais, valor.Estado, valor.Municipio, valor.CP, valor.Calle, valor.NoInt, valor.NoExt, valor.Telefono1, valor.Telefono2, activacion, valor.NombreCinemex, valor.Alias, valor.CentroCostos, valor.Dominio], true);
                        });
                        $('#listaSucursales').removeClass('hidden');
                        $('#formularioSucursal').addClass('hidden');
                        $('#btnRegresarSucursales').addClass('hidden');
                        evento.mostrarMensaje('.errorSucursales', true, 'Datos Actualizados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorActualizarSucursales', false, 'Ya existe el Nombre de la Sucursal con ese Cliente, por lo que ya no puedes repetirlo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorActualizarSucursales', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }
        });

        $('#btnRegresarSucursales').on('click', function () {
            $('#listaSucursales').removeClass('hidden');
            $('#formularioSucursal').addClass('hidden');
            $('#btnRegresarSucursales').addClass('hidden');
        });
    };
});


