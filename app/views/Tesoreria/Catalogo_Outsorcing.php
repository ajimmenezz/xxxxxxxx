<!--
 * Description: Listas de los catalogos para Outsorcing
 *
 * @author: Alberto Barcenas
 *
-->
<div id="seccionCatalogoOutsorcing" class="content">

    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Catálogo</h1>
    <!-- Finalizando titulo de la pagina -->

    <div id="seccion-catalogo-outsorcing" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">

        <!--Empezando Pestañas para definir la seccion-->
        <div class="panel-heading p-0">
            <div class="btn-group pull-right" data-toggle="buttons">

            </div>
            <div class="panel-heading-btn m-r-10 m-t-10">                                 
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse">
                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                    <li class="active"><a href="#Montos" data-toggle="tab">Montos</a></li>
                    <li class=""><a href="#Viaticos" data-toggle="tab">Viáticos</a></li>
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
        <!--Finalizando Pestañas para definir la seccion-->

        <!--Empezando contenido de catalogo outsorcing-->
        <div class="tab-content">

            <!--Empezando la seccion Montos-->
            <div class="tab-pane fade active in" id="Montos">
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">                        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <h3 class="m-t-10">Montos de Técnicos</h3>
                                    </div>
                                </div>

                                <!--Empezando Separador-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>
                                </div>
                                <!--Finalizando Separador-->

                            </div>    
                        </div> 
                    </div>
                    <form class="margin-bottom-0" id="formMontosOutsorcing" data-parsley-validate="true">
                        <div class="row m-t-10"> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputPrimerVueltaMonto">Monto Primer Vuelta (sin IVA) *</label>
                                    <?php (empty($datos['MontosOutsourcing'][0]['Monto'])) ? $primerMonto = '' : $primerMonto = $datos['MontosOutsourcing'][0]['Monto']; ?>
                                    <input type="number" step="0.01" class="form-control" id="inputPrimerVueltaMonto" placeholder="Ingresa la cantidad de la primer vuelta"  value="<?php echo $primerMonto ?>" style="width: 100%" />                            
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputAdicionalesMonto">Monto Vueltas Adicionales (sin IVA) *</label>
                                    <?php (empty($datos['MontosOutsourcing'][1]['Monto'])) ? $adicionalMonto = '' : $adicionalMonto = $datos['MontosOutsourcing'][1]['Monto']; ?>
                                    <input type="number" step="0.01" class="form-control" id="inputAdicionalesMonto" placeholder="Ingresa la cantidad de las vueltas adicionales" value="<?php echo $adicionalMonto ?>" style="width: 100%" />                            
                                </div>
                            </div>
                        </div>

                        <!--Empezando error--> 
                        <div class="row m-t-10">                       
                            <div class="col-md-12">
                                <div id="errorMontos"></div>
                            </div>
                        </div>   
                        <!--Finalizando Error-->

                        <div class="row m-t-10">
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <br>
                                    <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarMontos"><i class="fa fa-save"></i> Guardar</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Empezando la seccion Montos -->

            <!-- Empezando la seccion Viaticos -->
            <div class="tab-pane fade" id="Viaticos">
                <div class="panel-body">

                    <!--Empezando error--> 
                    <div class="row m-t-10">                       
                        <div class="col-md-12">
                            <div class="errorViaticos"></div>
                        </div>
                    </div>   
                    <!--Finalizando Error-->

                    <!--Empezando tabla Viaticos -->
                    <div id='listaViaticos'>

                        <!-- Empezando titulo de la tabla Viaticos -->
                        <div class="row">
                            <div class="col-md-12">                        
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <h3 class="m-t-10">Lista de Viáticos</h3>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group text-right">
                                                <a href="javascript:;" class="btn btn-success btn-lg " id="btnAgregarViatico"><i class="fa fa-plus"></i> Agregar / Actualizar</a>
                                            </div>
                                        </div>
                                    </div>

                                    <!--Empezando Separador-->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="underline m-b-15 m-t-15"></div>
                                        </div>
                                    </div>
                                    <!--Finalizando Separador-->

                                </div>    
                            </div> 
                        </div>
                        <!--Finalizando titulo de la tabla Viaticos-->

                        <!--Empezando datos de la tabla Viaticos -->
                        <div class="table-responsive">
                            <table id="data-table-viaticos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Técnico</th>
                                        <th class="all">Sucursal</th>
                                        <th class="all">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($datos['ListaViaticosOutsourcing'])) {
                                        foreach ($datos['ListaViaticosOutsourcing'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Outsourcing'] . '</td>';
                                            echo '<td>' . $value['Sucursal'] . '</td>';
                                            echo '<td>' . $value['Monto'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>
                        <!-- Finalizando datos de la tabla Viaticos -->

                    </div>
                    <!-- Finalizando tabla Viaticos -->

                </div>
            </div>
            <!-- Empezando la seccion viaticos -->        

        </div>
        <!-- Finalizando contenido de catalogo outsorcing -->

    </div>

</div>

<!-- Empezando seccion para mostrar los fomularios de catalogos outsorcing -->
<div id="seccionFormulariosOutsorcing" class="content"></div>
<!-- Finalizando seccion para mostrar los fomularios de catalogos outsorcing --> 
