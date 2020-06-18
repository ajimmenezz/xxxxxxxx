$(function () {

    App.init();

    //restablece el modal cuando se cierra
    $('#modal-dialogo').on('hidden.bs.modal', function () {
        $('.modal-title').empty();
        $('.modal-body').empty();
        $('#btnModalConfirmar').empty().append('Aceptar').removeClass('hidden');
        $('#btnModalAbortar').empty().append('Cancelar').removeClass('hidden');
    });

    //Selects
    $('#selectComplejo').select2();
    $('#selectLideres').select2();
    $('#selectMaterial').select2();

    //fechas datapicker
    $('#fecha-inicial').datepicker({
        todayHighlight: true,
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true,
        clearBtn: true
    });
    $('#fecha-termino').datepicker({
        todayHighlight: true,
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true,
        clearBtn: true
    });

    //tablas
    $('#data-table-alcance').DataTable({
        responsive: {
            details: false
        },
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "scrollX": true,
        "paging": false,
        "searching": false,
        "info": false
    });
    $('#data-table-materiales').DataTable({
        responsive: {
            details: false
        },
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "paging": false,
        "searching": false,
        "info": false
    });
    $('#data-table-sinIniciar').DataTable({
        responsive: {
            details: false
        },
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
    $('#data-table-tareas').DataTable({
        responsive: {
            details: false
        },
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "paging": false,
        "searching": false,
        "info": false
    });



    //EVENTOS DE BOTONES

    //Nuevo proyecto
    $('#btnGenerarProyecto').on('click', function () {
        //Tabla alcance
        $('#seccionAlcanceProyecto').removeClass('hidden');
        //oculta botones
        $(this).parent().addClass('hidden').siblings('div.hidden').removeClass('hidden');
    });

    //modal alcance
    $('#btnAlcanceProyecto').on('click', function () {
        $('#modal-dialogo').modal({
            backdrop: 'static',
            keyboard: true
        });

        var html = '<form action="/" method="POST"><fieldset>';
        html += '<div class="row">';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="selectConcepto">Concepto</label>';
        html += '           <select id="selectConcepto" class="form-control" style="width: 100%" required>';
        html += '               <option value="">Seleccionar</option>';
        html += '           </select>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="selectArea">Área</label>';
        html += '           <select id="selectArea" class="form-control" style="width: 100%" required>';
        html += '               <option value="">Seleccionar</option>';
        html += '           </select>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="selectUbicación">Ubicación</label>';
        html += '           <select id="selectUbicacion" class="form-control" style="width: 100%" required>';
        html += '               <option value="">Seleccionar</option>';
        html += '           </select>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row">';
        html += '   <legend>Nodos</legend>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label class="control-label">Datos</label>';
        html += '           <div class="input-group">';
        html += '               <span class="input-group-addon">';
        html += '                   Datos';
        html += '                   <input type="checkbox" value="" />';
        html += '               </span>';
        html += '                   <input type="text" class="form-control" />';
        html += '           </div>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label class="control-label">Voz</label>';
        html += '           <div class="input-group">';
        html += '               <span class="input-group-addon">';
        html += '                   Datos';
        html += '                   <input type="checkbox" value="" />';
        html += '               </span>';
        html += '                   <input type="text" class="form-control" />';
        html += '           </div>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label class="control-label">Videos</label>';
        html += '           <div class="input-group">';
        html += '               <span class="input-group-addon">';
        html += '                   Datos';
        html += '                   <input type="checkbox" value="" />';
        html += '               </span>';
        html += '                   <input type="text" class="form-control" />';
        html += '           </div>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row">';
        html += '   <legend>Accesorios</legend>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="accesorio">Accesorio</label>';
        html += '           <select id="selectAccesorio" class="form-control" style="width: 100%" required>';
        html += '               <option value="">Seleccionar</option>';
        html += '           </select>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-8">';
        html += '       <div class="form-group">';
        html += '                <label for="cantidadAccesorio">Cantidad</label>';
        html += '                <div class="form-inline ">';
        html += '                   <input type="number" class="form-control " id="inputCantidadAccesorio" placeholder="Cantidad de material" />';
        html += '                   <a href="javascript:;" class="btn btn-success m-r-5 "><i class="fa fa-plus"></i> Agregar</a>';
        html += '                </div>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '           <table id="data-table-accesorios" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">';
        html += '               <thead>';
        html += '                   <tr>';
        html += '                       <th class="all">Accesorio</th>';
        html += '                       <th class="all">Cantidad</th>';
        html += '                       <th class="desktop">Acciones</th>';
        html += '                   </tr>';
        html += '               </thead>';
        html += '               <tbody>';
        html += '                   <tr>';
        html += '                       <th class="desktop">HDMI1 7mts</th>';
        html += '                       <th class="desktop">12</th>';
        html += '                       <td ><a href="javascript:;">Eliminar</a></td>';
        html += '                   </tr>';
        html += '               </tbody>';
        html += '           </table>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row">';
        html += '   <div class="col-md-offset-4 col-md-4 text-center">';
        html += '       <button type="button" class="btn btn-sm btn-primary m-r-5" id="btnAgregarAccesorio">Guardar</button>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row">';
        html += '   <legend class="m-t-30">Resumen</legend>';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '           <table id="data-table-resumen" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">';
        html += '               <thead>';
        html += '                   <tr>';
        html += '                       <th class="all">Concepto</th>';
        html += '                       <th class="all">Área</th>';
        html += '                       <th class="all">Ubicación</th>';
        html += '                       <th class="desktop">Datos</th>';
        html += '                       <th class="desktop">Voz</th>';
        html += '                       <th class="desktop">Video</th>';
        html += '                       <th class="desktop">Cable</th>';
        html += '                       <th class="desktop">Tapa contra agua</th>';
        html += '                       <th class="desktop">Jack</th>';
        html += '                       <th class="desktop">Patch Cord de 3"</th>';
        html += '                       <th class="desktop">Patch Cord de 7"</th>';
        html += '                       <th class="desktop">Tapa Sencilla</th>';
        html += '                       <th class="desktop">Tapa Doble</th>';
        html += '                       <th class="desktop">HDMI1 1.80</th>';
        html += '                       <th class="desktop">HDMI1 3mts</th>';
        html += '                       <th class="desktop">HDMI1 7mts</th>';
        html += '                       <th class="desktop">HDMI1 8mts</th>';
        html += '                       <th class="desktop">HDMI1 10mts</th>';
        html += '                       <th class="desktop">Acciones</th>';
        html += '                   </tr>';
        html += '               </thead>';
        html += '               <tbody>';
        html += '                   <tr>';
        html += '                       <td >Concepto</td>';
        html += '                       <td >Área</td>';
        html += '                       <td >Ubicación</td>';
        html += '                       <td >Datos</td>';
        html += '                       <td >Voz</td>';
        html += '                       <td >Video</td>';
        html += '                       <td >Cable</td>';
        html += '                       <td >Tapa contra agua</td>';
        html += '                       <td >Jack</td>';
        html += '                       <td >Patch Cord de 3"</td>';
        html += '                       <td >Patch Cord de 7"</td>';
        html += '                       <td >Tapa Sencilla</td>';
        html += '                       <td >Tapa Doble</td>';
        html += '                       <td >HDMI1 1.80</td>';
        html += '                       <td >HDMI1 3mts</td>';
        html += '                       <td >HDMI1 7mts</td>';
        html += '                       <td >HDMI1 8mts</td>';
        html += '                       <td >HDMI1 10mts</td>';
        html += '                       <td ><a href="javascript:;">Eliminar</a></td>';
        html += '                   </tr>';
        html += '               </tbody>';
        html += '           </table>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '</fieldset></form>';
        $('.modal-title').empty().append('Alcance del proyecto').addClass('text-center');
        $('.modal-body').empty().append(html);
        $('#selectConcepto').select2();
        $('#selectArea').select2();
        $('#selectUbicacion').select2();
        $('#selectAccesorio').select2();
        $('#data-table-accesorios').DataTable({
            responsive: {
                details: false
            },
            language: {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "paging": false,
            "searching": false,
            "info": false
        });
        $('#data-table-resumen').DataTable({
            responsive: true,
            language: {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "scrollX": true,
            "paging": false,
            "searching": false
        });

        $('#btnModalConfirmar').on('click', function () {
            $('#seccionMateriales').removeClass('hidden');
            $('#modal-dialogo').modal('hide');
        });
    });

    //Mensaje para generar tareas
    $('#btnActualizarProyecto').on('click', function () {
        if ($('#seccionTareas').hasClass('hidden')) {
            $('#modal-dialogo').modal({
                backdrop: 'static',
                keyboard: true
            });
            var html = '<div class="row">';
            html += '   <div class="col-md-offset-4 col-md-4 text-center">';
            html += '       ¿Deseas generar las tareas? ';
            html += '   </div>';
            html += '   <div class="col-md-12 text-center m-t-30">';
            html += '       <div class="form-group">';
            html += '           <button type="button" class="btn btn-sm btn-primary m-r-5" id="btnGenerarTareas">Si</button>';
            html += '           <button type="button" class="btn btn-sm btn-primary m-r-5" id="btnCancelarTareas">No</button>';
            html += '       </div>';
            html += '   </div>';
            html += '</div>';
            html += '';
            $('.modal-title').empty().append('Crear tareas').addClass('text-center');
            $('.modal-body').empty().append(html);
            $('#btnModalConfirmar').empty().addClass('hidden');
            $('#btnModalAbortar').empty().addClass('hidden');
            //mostrando seccion tareas
            $('#seccionTareas').removeClass('hidden');
            //Cierra el dialogo
            $('#btnCancelarTareas').on('click', function () {
                $('#modal-dialogo').modal('hide');
            });

            //Evento para agregar tareas
            $('#btnGenerarTareas').on('click', function () {
                html = '<form action="/" method="POST"><fieldset>';
                html += '<div class="row">';
                html += '   <div class="col-md-4">';
                html += '       <div class="form-group">';
                html += '           <label for="selectTipoTarea">Tipo</label>';
                html += '           <select id="selectTipoTarea" class="form-control" style="width: 100%" required>';
                html += '               <option value="">Seleccionar</option>';
                html += '           </select>';
                html += '       </div>';
                html += '   </div>';
                html += '   <div class="col-md-4">';
                html += '       <div class="form-group">';
                html += '           <label for="selectLiderTarea">Líder</label>';
                html += '           <select id="selectLiderTarea" class="form-control" style="width: 100%" required>';
                html += '               <option value="">Seleccionar</option>';
                html += '           </select>';
                html += '       </div>';
                html += '   </div>';
                html += '   <div class="col-md-4">';
                html += '       <div class="form-group">';
                html += '           <label for="selectAreaTarea">Área</label>';
                html += '           <select id="selectAreaTarea" class="form-control" style="width: 100%" required>';
                html += '               <option value="">Seleccionar</option>';
                html += '           </select>';
                html += '       </div>';
                html += '   </div>';
                html += '</div>';
                html += '<div class="row">';
                html += '   <div class="col-md-4">';
                html += '       <div class="form-group">';
                html += '           <label for="selectConceptoTarea">Concepto</label>';
                html += '           <select id="selectConceptoTarea" class="form-control" style="width: 100%" required>';
                html += '               <option value="">Seleccionar</option>';
                html += '           </select>';
                html += '       </div>';
                html += '   </div>';
                html += '   <div class="col-md-4">';
                html += '       <div class="form-group">';
                html += '           <label for="selectUbicación">Ubicación</label>';
                html += '           <select id="selectUbicacionTarea" class="form-control" style="width: 100%" required>';
                html += '               <option value="">Seleccionar</option>';
                html += '           </select>';
                html += '       </div>';
                html += '   </div>';
                html += '</div>';
                html += '<div class="row">';
                html += '   <legend>Asistentes</legend>';
                html += '   <div class="col-md-4">';
                html += '       <div class="form-group">';
                html += '               <select id="selectAsistenteTarea" class="form-control" style="width: 100%" required>';
                html += '                   <option value="">Seleccionar</option>';
                html += '               </select>';
                html += '       </div>';
                html += '   </div>';
                html += '   <div class="col-md-4">';
                html += '       <a href="javascript:;" class="btn btn-success m-r-5 "><i class="fa fa-plus"></i> Agregar</a>';
                html += '   </div>';
                html += '   <div class="col-md-12">';
                html += '       <div class="form-group">';
                html += '           <table id="data-table-asistentesTareas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">';
                html += '               <thead>';
                html += '                   <tr>';
                html += '                       <th class="all">Nombre</th>';
                html += '                       <th class="all">NSS</th>';
                html += '                       <th class="desktop">Acciones</th>';
                html += '                   </tr>';
                html += '               </thead>';
                html += '               <tbody>';
                html += '               </tbody>';
                html += '           </table>';
                html += '       </div>';
                html += '   </div>';
                html += '</div>';
                html += '<div class="row m-t-20">';
                html += '   <div class="col-md-offset-3 col-md-3 text-center">';
                html += '       <label for="control-label"> Fecha inicio </label>';
                html += '       <div class="input-group date" id="fecha-inicial-tarea">';
                html += '           <input type="text" class="form-control" placeholder="Fecha Inicio"/>';
                html += '           <span class="input-group-addon"><i class="fa fa-calendar"></i></span>';
                html += '       </div>';
                html += '   </div>';
                html += '   <div class="col-md-3 text-center">';
                html += '       <label for="control-label"> Fecha Termino </label>';
                html += '       <div class="input-group date" id="fecha-termino-tarea">';
                html += '           <input type="text" class="form-control" placeholder="Fecha Termino"/>';
                html += '           <span class="input-group-addon"><i class="fa fa-calendar"></i></span>';
                html += '       </div>';
                html += '   </div>';
                html += '</div>';
                html += '<div class="row">';
                html += '   <div class="col-md-12">';
                html += '       <div class="form-group">';
                html += '       </div>';
                html += '   </div>';
                html += '</div>';
                html += '</fieldset></form>';
                $('.modal-body').empty().append(html);
                $('#btnModalConfirmar').empty().removeClass('hidden').append('Guardar');
                $('#btnModalAbortar').empty().removeClass('hidden').append('Cancelar');

                $('#selectTipoTarea').select2();
                $('#selectLiderTarea').select2();
                $('#selectAreaTarea').select2();
                $('#selectConceptoTarea').select2();
                $('#selectUbicacionTarea').select2();
                $('#selectAsistenteTarea').select2();
                $('#data-table-asistentesTareas').DataTable({
                    responsive: {
                        details: false
                    },
                    language: {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    },
                    "paging": false,
                    "searching": false
                });
                $('#fecha-inicial-tarea').datepicker({
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    language: 'es',
                    autoclose: true,
                    clearBtn: true
                });
                $('#fecha-termino-tarea').datepicker({
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    language: 'es',
                    autoclose: true,
                    clearBtn: true
                });

                $('#btnModalConfirmar').on('click', function () {

                    if ($('#btnIniciarProyecto').hasClass('hidden')) {
                        $('#btnIniciarProyecto').removeClass('hidden');
                    }
                    $('#modal-dialogo').modal('hide');
                });
            });
        } else {
            $('#seccionAlcanceProyecto').addClass('hidden');
            $('#seccionMateriales').addClass('hidden');
            $('#seccionTareas').addClass('hidden');
            $('#btnGenerarProyecto').parent().removeClass('hidden');
            $('#btnActualizarProyecto').parent().addClass('hidden');
            if (!$('#btnIniciarProyecto').hasClass('hidden')) {
                $('#btnIniciarProyecto').addClass('hidden');
            }
        }
    });

    //Nueva Tarea
    $('#btnNuevaTarea').on('click', function () {
        $('#modal-dialogo').modal({
            backdrop: 'static',
            keyboard: true
        });

        var html = '<form action="/" method="POST"><fieldset>';
        html += '<div class="row">';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="selectTipoTarea">Tipo</label>';
        html += '           <select id="selectTipoTarea" class="form-control" style="width: 100%" required>';
        html += '               <option value="">Seleccionar</option>';
        html += '           </select>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="selectLiderTarea">Líder</label>';
        html += '           <select id="selectLiderTarea" class="form-control" style="width: 100%" required>';
        html += '               <option value="">Seleccionar</option>';
        html += '           </select>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="selectAreaTarea">Área</label>';
        html += '           <select id="selectAreaTarea" class="form-control" style="width: 100%" required>';
        html += '               <option value="">Seleccionar</option>';
        html += '           </select>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row">';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="selectConceptoTarea">Concepto</label>';
        html += '           <select id="selectConceptoTarea" class="form-control" style="width: 100%" required>';
        html += '               <option value="">Seleccionar</option>';
        html += '           </select>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="selectUbicación">Ubicación</label>';
        html += '           <select id="selectUbicacionTarea" class="form-control" style="width: 100%" required>';
        html += '               <option value="">Seleccionar</option>';
        html += '           </select>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row">';
        html += '   <legend>Asistentes</legend>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '               <select id="selectAsistenteTarea" class="form-control" style="width: 100%" required>';
        html += '                   <option value="">Seleccionar</option>';
        html += '               </select>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <a href="javascript:;" class="btn btn-success m-r-5 "><i class="fa fa-plus"></i> Agregar</a>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '           <table id="data-table-asistentesTareas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">';
        html += '               <thead>';
        html += '                   <tr>';
        html += '                       <th class="all">Nombre</th>';
        html += '                       <th class="all">NSS</th>';
        html += '                       <th class="desktop">Acciones</th>';
        html += '                   </tr>';
        html += '               </thead>';
        html += '               <tbody>';
        html += '               </tbody>';
        html += '           </table>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row m-t-20">';
        html += '   <div class="col-md-offset-3 col-md-3 text-center">';
        html += '       <label for="control-label"> Fecha inicio </label>';
        html += '       <div class="input-group date" id="fecha-inicial-tarea">';
        html += '           <input type="text" class="form-control" placeholder="Fecha Inicio"/>';
        html += '           <span class="input-group-addon"><i class="fa fa-calendar"></i></span>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-3 text-center">';
        html += '       <label for="control-label"> Fecha Termino </label>';
        html += '       <div class="input-group date" id="fecha-termino-tarea">';
        html += '           <input type="text" class="form-control" placeholder="Fecha Termino"/>';
        html += '           <span class="input-group-addon"><i class="fa fa-calendar"></i></span>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row">';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '</fieldset></form>';
        $('.modal-title').empty().append('Crear tareas').addClass('text-center');
        $('.modal-body').empty().append(html);
        $('#btnModalConfirmar').empty().removeClass('hidden').append('Guardar');
        $('#btnModalAbortar').empty().removeClass('hidden').append('Cancelar');

        $('#selectTipoTarea').select2();
        $('#selectLiderTarea').select2();
        $('#selectAreaTarea').select2();
        $('#selectConceptoTarea').select2();
        $('#selectUbicacionTarea').select2();
        $('#selectAsistenteTarea').select2();
        $('#data-table-asistentesTareas').DataTable({
            responsive: {
                details: false
            },
            language: {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "paging": false,
            "searching": false
        });
        $('#fecha-inicial-tarea').datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            language: 'es',
            autoclose: true,
            clearBtn: true
        });
        $('#fecha-termino-tarea').datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            language: 'es',
            autoclose: true,
            clearBtn: true
        });

        $('#btnModalConfirmar').on('click', function () {
            if ($('#btnIniciarProyecto').hasClass('hidden')) {
                $('#btnIniciarProyecto').removeClass('hidden');
            }
            $('#modal-dialogo').modal('hide');
        });
    });

    //Eliminar Proyecto
    $('#btnEliminarProyecto').on('click', function () {
        $('#modal-dialogo').modal({
            backdrop: 'static',
            keyboard: true
        });
        var html = '<div class="row">';
        html += '   <div class="col-md-12 text-center">';
        html += '       Al realizar esta acción se borrara completamente del sistema y no podra recuperarse ';
        html += '   </div>';
        html += '   <div class="col-md-12 text-center">';
        html += '       ¿Estas seguro de querer eliminar el proyecto? ';
        html += '   </div>';
        html += '   <div class="col-md-12 text-center m-t-30">';
        html += '       <div class="form-group">';
        html += '           <button type="button" class="btn btn-sm btn-primary m-r-5" id="btnAcpetarEliminarProyecto">Aceptar</button>';
        html += '           <button type="button" class="btn btn-sm btn-primary m-r-5" id="btnCancelarEliminarProyecto">Cancelar</button>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '';
        $('.modal-title').empty().append('Eliminar Proyecto').addClass('text-center');
        $('.modal-body').empty().append(html);
        $('#btnModalConfirmar').empty().addClass('hidden');
        $('#btnModalAbortar').empty().addClass('hidden');


        //Eliminar el Proyecto
        $('#btnAcpetarEliminarProyecto').on('click', function () {
            $('#modal-dialogo').modal('hide');
        });

        //Cierra el dialogo
        $('#btnCancelarEliminarProyecto').on('click', function () {
            $('#modal-dialogo').modal('hide');
        });
    });

    //Iniciar proyecto
    $('#btnIniciarProyecto').on('click', function () {
        $('#seccionAlcanceProyecto').addClass('hidden');
        $('#seccionMateriales').addClass('hidden');
        $('#seccionTareas').addClass('hidden');
        $('#btnGenerarProyecto').parent().removeClass('hidden');
        $('#btnActualizarProyecto').parent().addClass('hidden');
        if (!$('#btnIniciarProyecto').hasClass('hidden')) {
            $('#btnIniciarProyecto').addClass('hidden');
        }
    });

    //Evento que se genera para mostrar la notificacion en particular
//    $('li').on('click','.media', function(){
//        window.location.href = 'notificationCase';
//    }); 

});


