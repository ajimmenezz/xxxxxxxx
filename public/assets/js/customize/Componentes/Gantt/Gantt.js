class Gantt {

    constructor(nombreGantt) {
        this.gantt = nombreGantt;
        this.datos = {};
        this.objetoGantt = $(`#${this.gantt}`);
//        this.iniciarGantt();
    }

    iniciarGantt(datos) {

//        gantt.config.lightbox.sections = [
//            {name: "description", height: 70, map_to: "text", type: "textarea", focus: true}
//        ];
//        gantt.config.buttons_right = [];
//        gantt.config.buttons_left = ["dhx_cancel_btn"];
        gantt.config.columns = [
            {name: "text", label: "Tarea", width: "*", tree: true},
            {name: "start_date", label: "Inicio", align: "center"},
            {name: "duration", label: "Duración", align: "center"}            
        ];
        gantt.config.readonly = true;
        gantt.init(this.gantt);
        gantt.clearAll();
        gantt.parse(datos);
    }

    agregarTarea(datos) {
        gantt.addTask(datos);
    }
}

