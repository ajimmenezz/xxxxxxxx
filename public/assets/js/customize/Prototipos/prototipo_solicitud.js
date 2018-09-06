$(function () {

    App.init();

    //restablece el modal cuando se cierra
    $('#modal-dialogo').on('hidden.bs.modal', function () {
        $('.modal-title').empty();
        $('.modal-body').empty();
        $('#btnModalConfirmar').empty().append('Aceptar').removeClass('hidden');
        $('#btnModalAbortar').empty().append('Cancelar').removeClass('hidden');
    });

    //Define un campo de file con plugin para agregar la foto y el evento que recibe a respuesta del navegador
    $('#fileUploadSolicitudNueva').fileinput({
        language: "es",
        uploadUrl: "",
        allowedFileExtensions: ["jpg", "jpeg", "png"]
    });

    //Selects
//    $('#selectComplejo').select2();

    //tablas
    $('#data-table-generadas').DataTable({
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

    $('#data-table-asignadas').DataTable({
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

    //EVENTOS DE BOTONES

    //Nueva solicitud
    $('#btnGenerarSolicitud').on('click', function () {
        $('#modal-dialogo').modal({
            backdrop: 'static',
            keyboard: true
        });
        var html = '<div class="row">';
        html += '   <div class="col-md-12 text-center">';
        html += '           <h3>45681</h3>';
        html += '   </div>';
        html += '</div>';
        $('.modal-title').empty().append('Se genero la solicitud').addClass('text-center');
        $('.modal-body').empty().append(html);
        $('#btnModalConfirmar').empty().addClass('hidden');
        $('#btnModalAbortar').empty().append('Cerrar');
    });

    //Evento en renglo de la tabla generadas
    var table = $('#data-table-generadas').DataTable();
    $('#data-table-generadas tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        $('#modal-dialogo').modal({
            backdrop: 'static',
            keyboard: true
        });
        var html = '<form action="/" method="POST"><fieldset>';
        html += '<div id="informacionSolicitud">';
        html += '<div class="row m-t-10" id="informacionSolicitud">';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group">';
        html += '           <label for="inputActNombreMaterial">Fecha Creación: ' + data[1] + '</label>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4 text-center">';
        html += '       <div class="form-group">';
        html += '           <label for="inputActNumeroParteMaterial">Asignado: Victor Mojica</label>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-4">';
        html += '       <div class="form-group text-right">';
        html += '           <label for="inputActProvedorMaterial">Estatus: ' + data[2] + '</label>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row ">';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '           <label for="inputActPrecioMaterial">Descripción</label>';
        html += '           <textarea class="form-control" id="textareaDescripcionNuevaSolicitud" placeholder="Describe lo que se requiere aqui ...." rows="5" >' + data[3] + '</textarea>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row ">';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '           <label for="selectActEstatus">Evidencia</label>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row ">';
        html += '   <div class="panel panel-default" data-sortable-id="index-5">';
        html += '       <div class="panel-heading">';
        html += '           <h4 class="panel-title">Notas</h4>';
        html += '       </div>';
        html += '       <div class="panel-body">';
        html += '           <div class="height-sm" data-scrollbar="true" id="panelNotas">';
        html += '               <ul class="media-list media-list-with-divider media-messaging">';
        html += '                   <li class="media media-sm">';
        html += '                       <a href="javascript:;" class="pull-left">';
        html += '                           <img src="assets/img/user-5.jpg" alt="" class="media-object rounded-corner" />';
        html += '                       </a>';
        html += '                       <div class="media-body">';
        html += '                           <h5 class="media-heading">John Doe</h5>';
        html += '                           <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi id nunc non eros fermentum vestibulum ut id felis. Nunc molestie libero eget urna aliquet, vitae laoreet felis ultricies. Fusce sit amet massa malesuada, tincidunt augue vitae, gravida felis.</p>';
        html += '                       </div>';
        html += '                   </li>';
        html += '                   <li class="media media-sm">';
        html += '                           <a href="javascript:;" class="pull-left">';
        html += '                               <img src="assets/img/user-6.jpg" alt="" class="media-object rounded-corner" />';
        html += '                           </a>';
        html += '                           <div class="media-body">';
        html += '                               <h5 class="media-heading">Terry Ng</h5>';
        html += '                               <p>Sed in ante vel ipsum tristique euismod posuere eget nulla. Quisque ante sem, scelerisque iaculis interdum quis, eleifend id mi. Fusce congue leo nec mauris malesuada, id scelerisque sapien ultricies.</p>';
        html += '                           </div>';
        html += '                   </li>';
        html += '                   <li class="media media-sm">';
        html += '                           <a href="javascript:;" class="pull-left">';
        html += '                               <img src="assets/img/user-8.jpg" alt="" class="media-object rounded-corner" />';
        html += '                           </a>';
        html += '                           <div class="media-body">';
        html += '                               <h5 class="media-heading">Fiona Log</h5>';
        html += '                               <p>Pellentesque dictum in tortor ac blandit. Nulla rutrum eu leo vulputate ornare. Nulla a semper mi, ac lacinia sapien. Sed volutpat ornare eros, vel semper sem sagittis in. Quisque risus ipsum, iaculis quis cursus eu, tristique sed nulla.</p>';
        html += '                           </div>';
        html += '                   </li>';
        html += '                   <li class="media media-sm">';
        html += '                           <a href="javascript:;" class="pull-left">';
        html += '                               <img src="assets/img/user-7.jpg" alt="" class="media-object rounded-corner" />';
        html += '                           </a>';
        html += '                           <div class="media-body">';
        html += '                               <h5 class="media-heading">John Doe</h5>';
        html += '                               <p>Morbi molestie lorem quis accumsan elementum. Morbi condimentum nisl iaculis, laoreet risus sed, porta neque. Proin mi leo, dapibus at ligula a, aliquam consectetur metus.</p>';
        html += '                           </div>';
        html += '                   </li>';
        html += '                   <li class="media media-sm">';
        html += '                           <a href="javascript:;" class="pull-left">';
        html += '                               <img src="assets/img/user-7.jpg" alt="" class="media-object rounded-corner" />';
        html += '                           </a>';
        html += '                           <div class="media-body">';
        html += '                               <h5 class="media-heading">Felix Doe</h5>';
        html += '                               <p>Morbi molestie lorem quis accumsan elementum. Morbi condimentum nisl iaculis, laoreet risus sed, porta neque. Proin mi leo, dapibus at ligula a, aliquam consectetur metus.</p>';
        html += '                           </div>';
        html += '                   </li>';
        html += '                   <li class="media media-sm">';
        html += '                   </li>';
        html += '               </ul>';
        html += '           </div>';
        html += '       </div>';
        html += '       <div class="panel-footer">';
        html += '           <div class="input-group">';
        html += '               <input type="text" class="form-control bg-silver" placeholder="Enter message" />';
        html += '               <span class="input-group-btn">';
        html += '                   <button class="btn btn-primary" type="button"><i class="fa fa-pencil"></i> Agregar</button>';
        html += '               </span>';
        html += '           </div>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="row hidden" id="cancelacionSolicitud">';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '           <label for="inputActPrecioMaterial">Describir Causa</label>';
        html += '           <textarea class="form-control" id="textareaCancelaciónSolicitud" placeholder="Describe lo causa de la cancelación aqui ...." rows="5" >' + data[3] + '</textarea>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12 text-right">';
        html += '       <button type="button" class="btn btn-sm btn-danger m-r-5" id="btnCancelarSolicitud">Aceptar</button>';
        html += '       <button type="button" class="btn btn-sm btn-default m-r-5" id="btnRegresarCancelar">Cancelar</button>';
        html += '   </div>';
        html += '</div>';
        html += '</fieldset></form>';

        $('.modal-title').empty().append('Solicitud ' + data[0]).addClass('text-center');
        $('.modal-body').empty().append(html);
        if (data[2] === 'No autorizado') {
            $('#btnModalConfirmar').empty().append('Eliminar');
        } else {
            $('#btnModalConfirmar').empty().append('Cancelar Solicitud');
            $('#btnModalConfirmar').on('click', function () {
                $('#informacionSolicitud').addClass('hidden');
                $('#cancelacionSolicitud').removeClass('hidden');
                $('#btnModalAbortar').addClass('hidden');
                $('#btnModalConfirmar').addClass('hidden');
            });
        }

        //Botones de cancelacion de solicitud
        $('#btnCancelarSolicitud').on('click', function () {
            $('#modal-dialogo').modal('hide');
        });

        //Evento regresar seccion cancelar solicitud
        $('#btnRegresarCancelar').on('click', function () {
            $('#informacionSolicitud').removeClass('hidden');
            $('#cancelacionSolicitud').addClass('hidden');
            $('#btnModalAbortar').removeClass('hidden');
            $('#btnModalConfirmar').removeClass('hidden');
        });

        $('#btnModalAbortar').empty().append('Cerrar');
        $('#panelNotas').slimScroll();

    });

    //Evento en renglo de la tabla asignadas
    var table = $('#data-table-asignadas').DataTable();
    $('#data-table-asignadas tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        $('#modal-dialogo').modal({
            backdrop: 'static',
            keyboard: true
        });
        var html = '<form action="/" method="POST"><fieldset>';
        html += '<div id="informacionSolicitud">';
        html += '   <div class="row m-t-10" id="informacionSolicitud">';
        html += '       <div class="col-md-4">';
        html += '           <div class="form-group">';
        html += '               <label for="inputActNombreMaterial">Fecha Creación: ' + data[2] + '</label>';
        html += '           </div>';
        html += '       </div>';
        html += '       <div class="col-md-4 text-center">';
        html += '           <div class="form-group">';
        html += '               <label for="inputActNumeroParteMaterial">Asignado: Victor Mojica</label>';
        html += '           </div>';
        html += '       </div>';
        html += '       <div class="col-md-4">';
        html += '           <div class="form-group text-right">';
        html += '               <label for="inputActProvedorMaterial">Estatus: ' + data[3] + '</label>';
        html += '           </div>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="row ">';
        html += '       <div class="col-md-12">';
        html += '           <div class="form-group">';
        html += '               <label for="inputActPrecioMaterial">Descripción</label>';
        html += '               <textarea class="form-control" id="textareaDescripcionNuevaSolicitud" placeholder="Describe lo que se requiere aqui ...." rows="5" >' + data[4] + '</textarea>';
        html += '           </div>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="row ">';
        html += '       <div class="col-md-12">';
        html += '           <div class="form-group">';
        html += '               <label for="selectActEstatus">Evidencia</label>';
        html += '           </div>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="row ">';
        html += '       <div class="panel panel-default" data-sortable-id="index-5">';
        html += '           <div class="panel-heading">';
        html += '               <h4 class="panel-title">Notas</h4>';
        html += '           </div>';
        html += '           <div class="panel-body">';
        html += '               <div class="height-sm" data-scrollbar="true" id="panelNotas">';
        html += '                   <ul class="media-list media-list-with-divider media-messaging">';
        html += '                       <li class="media media-sm">';
        html += '                           <a href="javascript:;" class="pull-left">';
        html += '                               <img src="assets/img/user-5.jpg" alt="" class="media-object rounded-corner" />';
        html += '                           </a>';
        html += '                           <div class="media-body">';
        html += '                               <h5 class="media-heading">John Doe</h5>';
        html += '                               <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi id nunc non eros fermentum vestibulum ut id felis. Nunc molestie libero eget urna aliquet, vitae laoreet felis ultricies. Fusce sit amet massa malesuada, tincidunt augue vitae, gravida felis.</p>';
        html += '                           </div>';
        html += '                       </li>';
        html += '                       <li class="media media-sm">';
        html += '                           <a href="javascript:;" class="pull-left">';
        html += '                               <img src="assets/img/user-6.jpg" alt="" class="media-object rounded-corner" />';
        html += '                           </a>';
        html += '                           <div class="media-body">';
        html += '                               <h5 class="media-heading">Terry Ng</h5>';
        html += '                               <p>Sed in ante vel ipsum tristique euismod posuere eget nulla. Quisque ante sem, scelerisque iaculis interdum quis, eleifend id mi. Fusce congue leo nec mauris malesuada, id scelerisque sapien ultricies.</p>';
        html += '                           </div>';
        html += '                       </li>';
        html += '                       <li class="media media-sm">';
        html += '                           <a href="javascript:;" class="pull-left">';
        html += '                               <img src="assets/img/user-8.jpg" alt="" class="media-object rounded-corner" />';
        html += '                           </a>';
        html += '                           <div class="media-body">';
        html += '                               <h5 class="media-heading">Fiona Log</h5>';
        html += '                               <p>Pellentesque dictum in tortor ac blandit. Nulla rutrum eu leo vulputate ornare. Nulla a semper mi, ac lacinia sapien. Sed volutpat ornare eros, vel semper sem sagittis in. Quisque risus ipsum, iaculis quis cursus eu, tristique sed nulla.</p>';
        html += '                           </div>';
        html += '                       </li>';
        html += '                       <li class="media media-sm">';
        html += '                           <a href="javascript:;" class="pull-left">';
        html += '                               <img src="assets/img/user-7.jpg" alt="" class="media-object rounded-corner" />';
        html += '                           </a>';
        html += '                           <div class="media-body">';
        html += '                               <h5 class="media-heading">John Doe</h5>';
        html += '                               <p>Morbi molestie lorem quis accumsan elementum. Morbi condimentum nisl iaculis, laoreet risus sed, porta neque. Proin mi leo, dapibus at ligula a, aliquam consectetur metus.</p>';
        html += '                           </div>';
        html += '                       </li>';
        html += '                       <li class="media media-sm">';
        html += '                           <a href="javascript:;" class="pull-left">';
        html += '                               <img src="assets/img/user-7.jpg" alt="" class="media-object rounded-corner" />';
        html += '                           </a>';
        html += '                           <div class="media-body">';
        html += '                               <h5 class="media-heading">Felix Doe</h5>';
        html += '                               <p>Morbi molestie lorem quis accumsan elementum. Morbi condimentum nisl iaculis, laoreet risus sed, porta neque. Proin mi leo, dapibus at ligula a, aliquam consectetur metus.</p>';
        html += '                           </div>';
        html += '                       </li>';
        html += '                       <li class="media media-sm">';
        html += '                       </li>';
        html += '                   </ul>';
        html += '               </div>';
        html += '           </div>';
        html += '           <div class="panel-footer">';
        html += '               <div class="input-group">';
        html += '                   <input type="text" class="form-control bg-silver" placeholder="Enter message" />';
        html += '                   <span class="input-group-btn">';
        html += '                       <button class="btn btn-primary" type="button"><i class="fa fa-pencil"></i> Agregar</button>';
        html += '                   </span>';
        html += '               </div>';
        html += '           </div>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="row ">';
        html += '       <div class="col-md-12 text-right">';
        html += '           <button type="button" class="btn btn-sm btn-default m-r-5" id="btnAutorizarSolicitud">Autorizar</button>';
        html += '           <button type="button" class="btn btn-sm btn-default m-r-5" id="btnNoAutorizarSolicitud">No Autorizar</button>';
        html += '           <button type="button" class="btn btn-sm btn-default m-r-5" id="btnAsignarSolicitud">Asignar</button>';
        html += '           <button type="button" class="btn btn-sm btn-default m-r-5" id="btnCerrarModalSolicitud">Cerrar</button>';
        html += '       </div>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row hidden" id="seccionReasignar">';
        html += '   <div class="col-md-6">';
        html += '       <div class="form-group">';
        html += '           <label for="inputActPrecioMaterial">Gerente</label>';
        html += '               <select id="selectGerente" class="form-control" style="width: 100%" required>';
        html += '                   <option value="">Seleccionar</option>';
        html += '               </select>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '           <label for="inputActPrecioMaterial">Describir Causa</label>';
        html += '           <textarea class="form-control" id="textareaCancelaciónSolicitud" placeholder="Describe lo causa de la reasignacion aqui ...." rows="5" ></textarea>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12 text-right">';
        html += '       <button type="button" class="btn btn-sm btn-danger m-r-5" id="btnReasignarSolicitud">Aceptar</button>';
        html += '       <button type="button" class="btn btn-sm btn-default m-r-5" id="btnRegresarReasignar">Cancelar</button>';
        html += '   </div>';
        html += '</div>';
        html += '<div class="row hidden" id="seccionAutorizar">';
        html += '   <div class="col-md-12 text-center">';
        html += '           <label for="">¿A que categorio corresponde la solicitud?</label>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '       <div class="row">';
        html += '           <div class="col-md-6">';
        html += '               <div class="form-group">';
        html += '                   <label for="inputActPrecioMaterial">Categoria</label>';
        html += '                   <select id="selectCategoria" class="form-control" style="width: 100%" required>';
        html += '                       <option value="">Seleccionar</option>';
        html += '                   </select>';
        html += '               </div>';
        html += '           </div>';
        html += '           <div class="col-md-6">';
        html += '               <div class="form-group">';
        html += '                   <label for="inputActPrecioMaterial">Área</label>';
        html += '                   <select id="selectCategoria" class="form-control" style="width: 100%" required>';
        html += '                       <option value="">Seleccionar</option>';
        html += '                   </select>';
        html += '               </div>';
        html += '           </div>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12">';
        html += '       <div class="form-group">';
        html += '           <label for="inputActPrecioMaterial">Comentarios</label>';
        html += '           <textarea class="form-control" id="" placeholder="Describe lo causa de la cancelación aqui ...." rows="5" >' + data[3] + '</textarea>';
        html += '       </div>';
        html += '   </div>';
        html += '   <div class="col-md-12 text-right">';
        html += '       <button type="button" class="btn btn-sm btn-danger m-r-5" id="btnConfirmarAutorizarSolicitud">Aceptar</button>';
        html += '       <button type="button" class="btn btn-sm btn-default m-r-5" id="btnRegresarAutorizar">Cancelar</button>';
        html += '   </div>';
        html += '</div>';
        html += '</fieldset></form>';

        $('.modal-title').empty().append('Solicitud ' + data[0]).addClass('text-center');
        $('.modal-body').empty().append(html);
        $('#btnModalAbortar').addClass('hidden');
        $('#btnModalConfirmar').addClass('hidden');
        $('#panelNotas').slimScroll();

        //Evento Autorizar Solicitud
        $('#btnAutorizarSolicitud').on('click', function () {
            $('#informacionSolicitud').addClass('hidden');
            $('#seccionAutorizar').removeClass('hidden');
        });

        //Botón Confirmar Autorizar Solicitud  
        $('#btnConfirmarAutorizarSolicitud').on('click', function () {
            $('#modal-dialogo').modal('hide');
        });

        //Botón Cancelar Autorizar Solicitud  
        $('#btnRegresarAutorizar').on('click', function () {
            $('#informacionSolicitud').removeClass('hidden');
            $('#seccionAutorizar').addClass('hidden');
        });

        //Evento No Autorizar Solicitud
        $('#btnNoAutorizarSolicitud').on('click', function () {
            $('#modal-dialogo').modal('hide');
        });

        //Evento Reasignar Solicitud
        $('#btnAsignarSolicitud').on('click', function () {
            $('#informacionSolicitud').addClass('hidden');
            $('#seccionReasignar').removeClass('hidden');
        });

        //Evento Aceptar reasignar Solicitud
        $('#btnReasignarSolicitud').on('click', function () {
            $('#modal-dialogo').modal('hide');
        });
        
        //Evento regresar de reasignar Solicitud
        $('#btnRegresarReasignar').on('click', function () {
            $('#informacionSolicitud').removeClass('hidden');
            $('#seccionReasignar').addClass('hidden');
        });
        //Evento cerrar informacion solicitud
        $('#btnCerrarModalSolicitud').on('click', function () {
            $('#modal-dialogo').modal('hide');
        });
    });


});


