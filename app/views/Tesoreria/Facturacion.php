<div id="listaPoliza" class="content">
    <!-- Empezando panel facturacin tesoreria-->
    <!-- Empezando titulo de la pagina -->
    <div class="row">
        <div class="col-md-6 col-xs-6">
            <h1 class="page-header">Facturación</h1>
        </div>
        <div class="col-md-6 col-xs-6 text-right">
            <label id="btnRegresarFacturacionTesoreria" class="btn btn-success hidden">
                <i class="fa fa fa-reply"></i> Regresar
            </label>  
        </div>
    </div>
    <div id="panelFacturacionTesoreria" class="panel panel-inverse">

        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Facturación</h4>
        </div>
        <!--Finalizando cabecera del panel-->

        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            
            <!-- Empezando #contenido -->
            <div id="listaFacturas">
                <?php echo $datos['TablaFacturacion']['datos']['titulo'] ?>
                <?php echo $datos['TablaFacturacion']['datos']['tablaVueltas'] ?>
                <?php echo $datos['TablaFacturacion']['datos']['tablaTesoreria'] ?>

            </div>
            <!-- Empezando seccion para la Documentacion -->
            <div id="seccionProcesoFacturacion" class="hidden"></div>
            <!--Finalizando cuerpo del panel-->

        </div>
        <!-- Finalizando panel facturacion tesoreria -->

    </div>
    <!-- Finalizando #contenido -->
</div>

