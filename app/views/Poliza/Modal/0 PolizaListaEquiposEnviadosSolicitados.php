<div id="divListaEquiposEnviados" class="content">
    <input type="hidden" value="<?php // echo $datos['vistaUsuario']['IdPerfil']   ?>" id="IdPerfil" />
    <h1 class="page-header">Seguimiento Equipos Almacén o Solicitados</h1>
    <div id="panelTablaEquiposEnviados" class="panel panel-inverse">
        <div class="panel-heading">    
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <h4 class="panel-title">Seguimiento Equipos Almacén o Solicitados</h4>
                </div>
                <div class="col-md-6 col-xs-6 text-right">
                    <label class="btnRegresarTabla btn btn-success hidden">
                        <i class="fa fa fa-reply"></i> Regresar
                    </label> 
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <h4>Lista de Equipos Enviados o Solicitados</h4>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group text-right hidden" id="botonNuevoValidacion">
                        <a href="javascript:;" class="btn btn-success" id="agregarEquipo">Nuevo</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="underline m-b-10"></div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="errorFormulario"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="lista-equipos-enviados-solicitados" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Ticket</th>
                                    <th class="all">Sucursal</th>
                                    <th class="all">Equipo o Refacción</th>
                                    <th class="all">Fecha</th>
                                    <th class="all">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>8215</td>
                                    <td>Aguascalientes</td>
                                    <td>Tablet Elo Touch</td>
                                    <td>12/11/2018</td>
                                    <td>Revision en laboratorio</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <pre>
                    <?php
//                        foreach ($datos['vistaUsuario'] as $key => $value) {
                    print_r($datos['vistaUsuario']['IdPerfil']);
//                        }
                    ?>
                </pre>
            </div>
        </div>
    </div>
    <div id="seccionFormulariosValidacion" class="hidden"></div>
</div>