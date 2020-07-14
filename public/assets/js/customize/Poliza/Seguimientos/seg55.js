class Seguimiento55 {

    constructor() {
        this.evento = new Base();
        this.table = new Tabla();
        this.serviceId = null;
    }

    init(serviceId) {
        this.serviceId = serviceId;
        this.panelChangeListening();
    }

    panelChangeListening() {
        let instance = this;
        $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#Seguimiento55":
                    instance.showSOUpdateForm();
                    break;
            }
        });
    }

    showSOUpdateForm() {
        let table = this.table;
        $("#Seguimiento55").empty();
        this.evento.enviarEvento(
            "/Poliza/Seguimiento/ShowSOUpdateForm",
            { serviceId: this.serviceId },
            "#Seguimiento55",
            function (respuesta) {
                var printHtml = '';
                if (respuesta.code == 200) {
                    printHtml = respuesta.html;
                } else {
                    printHtml = `
                    <div class="note note-warning">
					    <h4>Warning!</h4>
						<p>`+ respuesta.message + `</p>
					</div>
                    `;
                }
                $("#Seguimiento55").append(printHtml);
                table.generaTablaPersonal("#updateSOInfoTable", null, null, true, false, [], null, null, false, true);
            }
        );
    }

    /*
    1. Se carga o no se carga imagen
    2. Impedimentos
    2.1.  Disco duro menor a 64Gb
    2.2.  No hay imagen para el equipo
    2.3.  eLearning sin enlace
    3. Aplica para todas las computadoras del complejo, sin tocar plasmas.
    */



}