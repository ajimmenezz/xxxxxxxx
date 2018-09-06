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
    $('#data-table-materiales').DataTable({
        responsive: {
            details: true
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
    var table = $('#data-table-materiales').DataTable();
    $('#data-table-materiales tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        $('#modal-dialogo').modal({
            backdrop: 'static',
            keyboard: true
        });
        var html = '<form action="/" method="POST"><fieldset>';
        html += '<div class="row m-t-10">';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="inputActNombreMaterial">Material</label>';
        html += '           <input type="text" class="form-control" id="inputActNombreMaterial" placeholder="Ingresa el nombre del material"/>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="inputActNumeroParteMaterial">Numero de Parte</label>';
        html += '           <input type="text" class="form-control" id="inputActNumeroParteMaterial" placeholder="Ingresa el numero de parte"/>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="inputActProvedorMaterial">Provedor</label>';
        html += '           <input type="text" class="form-control" id="inputActProvedorMaterial" placeholder="Ingresa el provedor"/>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';               
        html += '<div class="row ">';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="inputActPrecioMaterial">Precio</label>';
        html += '           <input type="text" class="form-control" id="inputActPrecioMaterial" placeholder="Ingresa el nombre del material"/>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="selectActTipoMoneda">Moneda</label>';
        html += '           <select id="selectActTipoMoneda" class="form-control" style="width: 100%" required>';
        html += '               <option value="">Pesos</option>';
        html += '               <option value="">Dolares</option>';
        html += '           </select>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="textareaActDescripcionMaterial">Descripción</label>';
        html += '           <textarea class="form-control" id="textareaActDescripcionMaterial" placeholder="Descripción del material" rows="1"></textarea>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row ">';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="selectActEstatus">Estatus</label>';
        html += '           <select id="selectActEstatus" class="form-control" style="width: 100%" required>';
        html += '               <option value="">Activo</option>';
        html += '               <option value="">Desactivo</option>';
        html += '           </select>';
        html += '       </div>';
        html += '   </div>';        
        html += '</div>';
        html += '</fieldset></form>';
        
        $('.modal-title').empty().append('Actualizar Material').addClass('text-center');
        $('.modal-body').empty().append(html);
        $('#btnModalConfirmar').empty().append('Guardar');
    });
   
});


