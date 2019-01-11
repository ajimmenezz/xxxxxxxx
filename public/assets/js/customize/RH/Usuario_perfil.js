//Constructor del la clase Tabla
function Usuario_perfil() {
//    this.file = new Upload();
//    this.tabla = new Tabla();
    this.select = new Select();
//    this.nota = new Nota();
}
//Herencia del objeto Base
Usuario_perfil.prototype = new Base();
Usuario_perfil.prototype.constructor = Usuario_perfil;

//Evento para crear un nuevo servicio interno desde del mismo servicio
Usuario_perfil.prototype.SelectNacimiento = function () {
    var _this = this;
    var evento = new Base();
    var select = new Select();
    
    $("#selectActualizarPaisUsuario").on("change", function () {
        $("#selectActualizarEstadoUsuario").empty().append('<option value="">Seleccionar...</option>');
        var pais = $(this).val();
        if (pais !== '') {
            var data = {IdPais: pais};
            evento.enviarEvento('/Configuracion/PerfilUsuario/MostrarDatosEstados', data, '#seccion-informacion-usuario', function (respuesta) {
                $.each(respuesta, function (k, v) {
                    $("#selectActualizarEstadoUsuario").append('<option value="' + v.Id + '">' + v.Nombre + '</option>')
                });
                $("#selectActualizarEstadoUsuario").removeAttr("disabled");
//                var variablesGlobales = viewGlobals();
//                select.cambiarOpcion('#selectActualizarEstadoUsuario', variablesGlobales[0]);
            });
        } else {
            $("#selectActualizarEstadoUsuario").attr("disabled", "disabled");
        }
    });
    
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
//                var variablesGlobales = viewGlobals();
//                select.cambiarOpcion('#selectActualizarMunicipioUsuario', variablesGlobales[1]);
            });
        } else {
            $("#selectActualizarMunicipioUsuario").attr("disabled", "disabled");
        }
    });
};