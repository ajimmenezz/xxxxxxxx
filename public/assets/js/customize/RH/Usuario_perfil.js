function Usuario_perfil() {
//    this.file = new Upload();
//    this.tabla = new Tabla();
    this.select = new Select();
//    this.nota = new Nota();
}

//Herencia del objeto Base
Usuario_perfil.prototype = new Base();
Usuario_perfil.prototype.constructor = Usuario_perfil;

Usuario_perfil.prototype.SelectNacimiento = function (datosPersonal) {
    var _this = this;
    var evento = new Base();
    var select = new Select();
    console.log(datosPersonal)


    $("#selectActualizarEstadoUsuario").on("change", function () {
        $("#selectActualizarMunicipioUsuario").empty().append('<option value="">Seleccionar...</option>');
        select.cambiarOpcion("#selectActualizarMunicipioUsuario", '');
        var pais = $(this).val();
        if (pais !== '') {
            var data = {IdEstado: pais};
            evento.enviarEvento('/Configuracion/PerfilUsuario/MostrarDatosMunicipio', data, '#seccion-informacion-usuario', function (respuesta) {
                $.each(respuesta, function (k, v) {
                    $("#selectActualizarMunicipioUsuario").append('<option value="' + v.Id + '">' + v.Nombre + '</option>')
                });
                $("#selectActualizarMunicipioUsuario").removeAttr("disabled");
                select.cambiarOpcion('#selectActualizarMunicipioUsuario', datosPersonal.MunicipioNac);
            });
        } else {
            $("#selectActualizarMunicipioUsuario").attr("disabled", "disabled");
        }
    });
};

Usuario_perfil.prototype.BotonesActualizar = function (idUsuario) {
    var _this = this;
    var evento = new Base();

    $('#btnGuardarPersonalesUsuario').off("click");
    $('#btnGuardarPersonalesUsuario').on('click', function () {
        var fechaNacimiento = $('#inputFechaNacimiento').val();
        var pais = $('#selectActualizarPaisUsuario').val();
        var estado = $('#selectActualizarEstadoUsuario').val();
        var municipio = $('#selectActualizarMunicipioUsuario').val();
        var estadoCivil = $('#selectActualizarEstadoCivilUsuario').val();
        var nacionalidad = $('#inputActualizarNacionalidadUsuario').val();
        var estatura = $('#inputActualizarEstaturaUsuario').val();
        var sexo = $('#selectActualizarSexoUsuario').val();
        var peso = $('#inputActualizarPesoUsuario').val();
        var tipoSangre = $('#inputActualizarTipoSangreUsuario').val();
        var tallaPantalon = $('#inputActualizarTallaPantalonUsuario').val();
        var tallaCamisa = $('#inputActualizarTallaCamisaUsuario').val();
        var tallaZapatos = $('#inputActualizarTallaZapatosUsuario').val();
        var institutoAfore = $('#inputActualizarInstitutoAforeUsuario').val();
        var numeroAfore = $('#inputActualizarNumeroAforeUsuario').val();

//        mostrarCargaPaginaInformacionUsuario('#personales');

        var data = {
            fechaNacimiento: fechaNacimiento,
            pais: pais,
            estado: estado,
            municipio: municipio,
            estadoCivil: estadoCivil,
            nacionalidad: nacionalidad,
            estatura: estatura,
            sexo: sexo,
            peso: peso,
            tipoSangre: tipoSangre,
            tallaPantalon: tallaPantalon,
            tallaCamisa: tallaCamisa,
            tallaZapatos: tallaZapatos,
            institutoAfore: institutoAfore,
            numeroAfore: numeroAfore,
            id: idUsuario
        };

        evento.enviarEvento('EventoAltaPersonal/ActualizarDatosPersonal', data, '', function (respuesta) {
            if (respuesta) {
                mensajeModal('Se guardo correctamente.', 'Correcto', '#personales');
            } else {
                mensajeModal('No hay ningún campo modificado.', 'Advertencia', '#personales');
            }
        });
    });
};