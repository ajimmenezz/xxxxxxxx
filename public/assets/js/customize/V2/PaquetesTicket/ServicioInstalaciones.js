class ServicioInstalaciones extends IServicio {
    constructor() {
        super();

    }

    setDatos(datos) {
        console.log(datos);
//        setInformacion(datos);
    }

    setInformacion(datos) {
        $("#solicitudInformacionGeneral").text(datos.servicio.idSolicitud);
        $("#ticketInformacionGeneral").text(datos.servicio.Ticket);
        $("#solicitaInformacionGeneral").text(datos.servicio.Solicita);
        $("#atiendeInformacionGeneral").text(datos.servicio.Atiende);
        $("#fechaSolicitudInformacionGeneral").text(datos.servicio.FechaSolicitud);
        $("#folioInformacionGeneral").text(datos.servicio.Folio);
        $("#servicioInformacionGeneral").text(datos.servicio.idServicio);
        $("#fechaCreacionServicioInformacionGeneral").text(datos.servicio.FechaCreacion);
        $("#fechaInicioServicioInformacionGeneral").text(datos.servicio.FechaInicio);

    }
}


