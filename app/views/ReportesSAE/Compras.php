<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Compras</h1>
    <!-- Finalizando titulo de la pagina -->

    <div id="seccion-compras-SAE" class="panel panel-inverse borde-sombra">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Productos SAE</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row m-t-20">
                <div class="col-md-12">                            
                    <fieldset>
                        <legend class="pull-left width-full f-s-17">Asistente de Búsqueda.</legend>
                    </fieldset>  
                </div>
            </div>

            <form id="formFiltrosFechas">
                <fieldset>
                    <div class="row">                                          
                        <div class="col-md-6 col-xs-6">
                            <div class="form-group">
                                <label for="inputBuscarProductoSAE">Desde *</label>
                                <div class='input-group date' id='desdeComprasSAE'>
                                    <input type='text' id="txtDesdeComptrasSAE" class="form-control" value="" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>                
                        </div>                                                        
                        <div class="col-md-6 col-xs-6">
                            <div class="form-group">
                                <label for="inputBuscarProductoSAE">Hasta *</label>
                                <div class='input-group date' id='hastaComprasSAE'>
                                    <input type='text' id="txtHastaComprasSAE" class="form-control" value="" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>                
                        </div>
                    </div>

                    <!--Empezando error de busqueda de producto--> 
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div class="errorProductoBuscar"></div>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="inputBuscarProductoSAE">Producto SAE *</label>
                                <input type="text" class="form-control" id="inputBuscarProductoSAE" placeholder="Ingresa el producto que desa buscar" style="width: 100%" data-parsley-required="true"/>                            
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6 text-center">
                            <a href="javascript:;" id="btnBuscarComprasSAE" class="btn btn-warning m-t-20"><i class="fa fa-search"></i> Buscar</a>
                        </div>
                        <div class="col-md-2 col-xs-6 text-center">
                            <a href="javascript:;" id="btnLimpiarComprasSAE" class="btn btn-info m-t-20"><i class="fa fa-eraser"></i> Limpiar</a>
                        </div>
                    </div>
                    <div class="row m-t-20">
                        <div class="col-md-12 col-xs-12">      
                            <div class="form-group">
                                <label for="selectFiltroProductosSAE">Productos Encontrados *</label>
                                <select id="selectFiltroProductosSAE" class="form-control" style="width: 100%" multiple="multiple" disabled="">                                                                                                       
                                </select>
                            </div>
                        </div>
                    </div> 

                    <!--Empezando error mostrar reporte--> 
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div class="errorMostrarReporteComprasSAE"></div>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-center">
                            <a href="javascript:;" id="btnMostrarReporteComprasSAE" class="btn btn-success"><i class="fa fa-eye"></i> Mostrar Reporte</a>
                        </div>
                    </div>
                </fieldset>
            </form>
            <!--Finalizando cuerpo del panel-->
        </div>
    </div>

    <!--Empezando seccion reporte de compras -->
    <div id="seccionReporteComprasSAE" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1"></div>
    <!-- Finalizando seccion reporete de compras --> 