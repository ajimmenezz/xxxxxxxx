$(function () {

    App.init();

    //restablece el modal cuando se cierra
    $('#modal-dialogo').on('hidden.bs.modal', function () {
        $('.modal-title').empty();
        $('.modal-body').empty();
        $('#btnConfirm').empty().append('Aceptar').removeClass('hidden');
        $('#btnAbort').empty().append('Cancelar').removeClass('hidden');
    });

    //Selects
    $('#selectSistemaEspecial').select2();

    //tablas
    $('#data-table-sistemasEspeciales').DataTable({
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
    $('#data-table-tareaSistemaEspecial').DataTable({
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

    //modal de sistemas especiales
    var table = $('#data-table-sistemasEspeciales').DataTable();
    $('#data-table-sistemasEspeciales tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        $('#modal-dialogo').modal({
            backdrop: 'static',
            keyboard: true
        });
        var html = '<div class="row">';
        html += '   <div class="col-md-6">';
        html += '       <label for="nombreActualizarSistema">Nombre</label> ';
        html += '       <input type="text" class="form-control" id="inputActualizarSistemaEsp" placeholder="Ingresa el nuevo sistema"/> ';
        html += '   </div>';
        html += '   <div class="col-md-6">';
        html += '       <div class="form-group">';
        html += '           <div class="form-group">';
        html += '               <label for="selectEstatusSistemaEspecial">Estatus</label>';
        html += '               <select id="selectEstatusSistemaEspecial" class="form-control" style="width: 100%" required>';
        html += '                   <option value="">Activo</option>';
        html += '                   <option value="">Desactivo</option>';
        html += '               </select>';
        html += '           </div>';
        html += '       </div> ';
        html += '   </div>';
        html += '</div>';
        html += '';
        $('.modal-title').empty().append('Sistema Especial').addClass('text-center');
        $('.modal-body').empty().append(html);
        $('#btnModalConfirmar').empty().append('Guardar');
    });

    //modal de tareas de sistemas especiales
    var table = $('#data-table-tareaSistemaEspecial').DataTable();
    $('#data-table-tareaSistemaEspecial tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        $('#modal-dialogo').modal({
            backdrop: 'static',
            keyboard: true
        });
        var html = '<form action="/" method="POST"><fieldset>';
        html += '<div class="row">';
        html += '   <div class="col-md-6">';
        html += '       <div class="form-group">';
        html += '           <label for="selectSistemaEspecial">Sistema Especial</label> ';
        html += '           <select id="selectSistema" class="form-control" style="width: 100%" required>';
        html += '               <option value="">Seleccionar</option>';
        html += '           </select>';
        html += '       </div> ';
        html += '   </div>';
        html += '   <div class="col-md-6">';
        html += '       <div class="form-group">';
        html += '           <label for="nombreTareaSistema">Nombre</label> ';
        html += '           <input type="text" class="form-control" id="inputNombreTareaSistema" placeholder="Ingresa la nueva tarea"/> ';
        html += '       </div> ';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row">';
        html += '   <div class="col-md-6">';
        html += '       <div class="form-group">';
        html += '               <label for="selectActEstatusTareaSistema">Estatus</label>';
        html += '               <select id="selectActEstatusTareaSistema" class="form-control" style="width: 100%" required>';
        html += '                   <option value="">Activo</option>';
        html += '                   <option value="">Desactivo</option>';
        html += '               </select>';
        html += '       </div> ';
        html += '   </div>';
        html += '</div>';
        html += '</fieldset></form>';
        $('.modal-title').empty().append('Sistema Especial').addClass('text-center');
        $('.modal-body').empty().append(html);
        $('#btnModalConfirmar').empty().append('Guardar');

        $('#selectSistema').select2();
    });
});


