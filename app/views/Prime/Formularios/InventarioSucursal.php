<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Inventario</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li id="btnExportarInventarioSucursal"><a href="#"><i class="fa fa-file-excel-o"></i> Exportar</a></li>                
        </div>
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div id="panelInventario" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
            <div class="panel-heading p-0">
                <div class="tab-overflow">
                    <ul class="nav nav-tabs nav-tabs-inverse">
                        <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                        <li class="active"><a href="#Generales" data-toggle="tab" class="f-w-600 f-s-14">Generales</a></li>
                        <li class=""><a href="#Equipo" data-toggle="tab" class="f-w-600 f-s-14">Equipos</a></li>
                        <!-- <li class=""><a href="#Conceptos" data-toggle="tab">Conceptos</a></li>
                        <li class=""><a href="#Montos" data-toggle="tab">Montos Por Usuario</a></li> -->
                        <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-1 col-xs-10">
                    <div id="errorMessageInventario"></div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade active in" id="Generales">
                    <div class="panel-body">
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <h5>Info General</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="underline"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Ticket: </strong><?php echo $generales['Ticket']; ?></p>
                                <p><strong>Servicio: </strong><?php echo $generales['Id']; ?></p>
                                <p><strong>Sucursal: </strong><?php echo $generales['Sucursal']; ?></p>
                                <p><strong>Atiende: </strong><?php echo ucwords($generales['Atiende']); ?></p>
                                <p><strong>Gerente: </strong><?php echo ucwords($generales['NombreFirma']); ?></p>
                                <p><strong>Fecha Creación: </strong><?php echo $generales['FechaCreacion']; ?></p>
                                <p><strong>Fecha Inicio: </strong><?php echo $generales['FechaInicio']; ?></p>
                                <p><strong>Fecha Conclusión: </strong><?php echo $generales['FechaConclusion']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="Equipo">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table id="table-inventario" class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th class="none">Id</th>
                                                <th class="all">Línea</th>
                                                <th class="all">Sublínea</th>
                                                <th class="all">Marca</th>
                                                <th class="all">Modelo</th>
                                                <th class="all">Serie</th>
                                                <th class="all">Estatus</th>
                                                <th class="all">Etiqueta</th>
                                                <th class="all">S.O.</th>
                                                <th class="all">Nombre Red</th>
                                                <th class="all">MAC</th>
                                                <th class="all">Software RQ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($inventario) && !empty($inventario)) {
                                                foreach ($inventario as $key => $value) {
                                                    echo ""
                                                        . "<tr>"
                                                        . " <td>" . $value['Id'] . "</td>"
                                                        . " <td>" . $value['Linea'] . "</td>"
                                                        . " <td>" . $value['Sublinea'] . "</td>"
                                                        . " <td>" . $value['Marca'] . "</td>"
                                                        . " <td>" . $value['Modelo'] . "</td>"
                                                        . " <td>" . $value['Serie'] . "</td>"
                                                        . " <td>" . $value['EstadoEquipo'] . "</td>"
                                                        . " <td>" . $value['Etiqueta'] . "</td>"
                                                        . " <td>" . $value['SO'] . "</td>"
                                                        . " <td>" . $value['NombreRed'] . "</td>"
                                                        . " <td>" . $value['SoftwareRQ'] . "</td>"
                                                        . " <td>" . $value['MAC'] . "</td>"
                                                        . "</tr>";
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>