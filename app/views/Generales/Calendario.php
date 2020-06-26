<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Calendario de Servicios</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel nuevo proyecto-->
    <div id="seccion-notificaciones" class="panel panel-inverse borde-sombra">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Calendario de Servicios</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row m-t-20">
                <div class="col-md-12"> 
                    <?php
                    if (count($datos['Areas']) > 0) {
                        ?>
                        <div class="row m-b-30">
                            <div class="col-md-11 col-sm-11 col-xs-11">
                                <fieldset>
                                    <legend class="pull-left width-full f-s-17">Filtrar por Área.</legend>
                                </fieldset> 
                                <select class="form-control" id="listAreasServicios">
                                    <option value="mis" selected="selected">Mis Servicios</option>
                                    <?php
                                    foreach ($datos['Areas'] as $key => $value) {
                                        echo '<option value="' . $value['Id'] . '">' . $value['Area'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div id='calendar'></div>                    
                </div>
            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel nuevo proyecto -->   

    </div>
    <!-- Finalizando #contenido -->