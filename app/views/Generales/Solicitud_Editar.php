<div id="firstPage" class="content">    
    <h1 class="page-header">Editar solicitudes y servicios <?php echo $_SERVER['SERVER_NAME']; ?></h1>    

    <div class="panel panel-inverse panel-with-tabs" id="PanelGeneral" data-sortable-id="ui-unlimited-tabs-1">        
        <div class="panel-heading p-0">
            <div class="btn-group pull-right" data-toggle="buttons">

            </div>
            <div class="panel-heading-btn m-r-10 m-t-10">                                 
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse">
                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                    <li class="active"><a href="#PanelSolicitudes" data-toggle="tab">Editar Solicitudes</a></li>
                    <li class=""><a href="#PanelServicios" data-toggle="tab">Editar Servicios</a></li>                    
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>        
        <div class="tab-content">            
            <div class="tab-pane fade active in" id="PanelSolicitudes">
                <div class="panel-body">

                    <!--Empezando error--> 
                    <div class="row">                       
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="errorSolicitudes1"></div>
                        </div>
                    </div>   
                    <!--Finalizando Error-->

                    <div class="row">
                        <div class="col-md-12">                        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h3 class="m-t-5">Buscar solicitudes</h3>
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>
                                </div>

                            </div>    
                        </div> 
                    </div>      
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="radio">
                                    <label class="f-w-700 f-s-12">
                                        <input type="radio" name="radioSolicitud" value="dpto" id="radioSolicitudDepartamento">
                                        Buscar por departamento:
                                    </label>
                                </div>                               
                                <select class="form-group" id="listDepartamentosSolicitud" disabled="disabled" style="width: 100%" multiple="multiple">
                                    <?php
                                    if (!empty($datos['departamentos'])) {
                                        foreach ($datos['departamentos'] as $key => $value) {
                                            echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="radio">
                                    <label class="f-w-700 f-s-12">
                                        <input type="radio" name="radioSolicitud" value="id" id="radioSolicitudId">
                                        Buscar por Id (# de Solicitud):
                                    </label>
                                </div>                               
                                <ul id="tagsIdsSolicitudes"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-20">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <a class="btn btn-success" id="btnBuscarSolicitudes">Buscar Solicitudes</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tableSolicitudes" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Id</th>
                                    <th class="all">Ticket</th>
                                    <th class="all">Solicita</th>
                                    <th class="all">Departamento</th>
                                    <th class="all">Prioridad</th>
                                    <th class="all">Atiende</th>
                                    <th class="all">Estatus</th>
                                    <th class="all">Fecha</th>
                                    <th class="all">Asunto</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>                        

                </div>
            </div>            

            <div class="tab-pane fade" id="PanelServicios">
                <div class="panel-body">                    
                    <div class="row m-t-10">                       
                        <div class="col-md-12">
                            <div class="errorPanelServicios"></div>
                        </div>
                    </div>   

                    <div class="row">
                        <div class="col-md-12">                        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h3 class="m-t-5">En desarrollo . . .</h3>
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>
                                </div>

                            </div>    
                        </div> 
                    </div> 

                </div>
            </div>            
        </div>        
    </div>
</div>
<div id="secondPage" class="content" style="display:none"></div>