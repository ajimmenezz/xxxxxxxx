<!--
 * Description: Formulario Viaticos para outsorcing
 *
 * @author: Alberto Barcenas
 *
-->

<!-- Empezando titulo de la pagina -->
<div class="row">
    <div class="col-md-6 col-xs-6">
        <h1 class="page-header">Viáticos Técnico</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <label id="btnRegresarViaticosOutsourcing" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>
<div id="panelViaticosOutsorcing" class="panel panel-inverse">

    <!--Empezando cabecera del panel-->
    <div class="panel-heading">
        <div class="panel-heading-btn">  
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title">Viáticos</h4>
    </div>
    <!--Finalizando cabecera del panel-->

    <!--Empezando cuerpo del panel-->
    <div class="panel-body">

        <form class="margin-bottom-0" id="formViaticosOutsorcing" data-parsley-validate="true">
            <div class="row m-t-15"> 
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <h4 class="m-t-10">Viáticos por Técnico</h4>
                    </div>
                </div>                               
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
            </div>

            <!-- Empezando Tecnicos outsorcing -->
            <div class="row m-t-15">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="selectTecnicosOutsorcing">Técnico *</label>
                        <select id="selectTecnicosOutsorcing" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar</option>
                            <?php
                            foreach ($tecnicosAsociados as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . ' (' . $item['Puesto'] . ')</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Finalizando -->

            <div id="div-table-viaticos-outsorcing"></div> 
            
        </form>
    </div>
</div>