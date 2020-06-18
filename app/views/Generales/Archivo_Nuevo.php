<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Archivos</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel nueva minuta-->
    <div id="panelNuevoArchivo" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Nuevo Archivo</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando Formulario para nuevo archivo -->                
            <div class="row m-t-10">
                <form id="formNuevoArchivo" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <h3 class="m-t-10">Nuevo Archivo</h3>
                        <!--Empezando Separador-->
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nuevoArchivo">Tipo de Archivo *</label>
                            <select id="selectTiposArchivos" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($datos['SelectArchivos'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nuevoArchivo">Nombre *</label>
                            <input type="text" class="form-control" id="inputNombreArchivo" placeholder="Ingresa nombre del Archivo" style="width: 100%" data-parsley-required="true"/>                            
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nuevoArchivo">Descripción *</label>
                            <textarea id="textareaDescripcionArchivo" class="form-control" name="descripcionArchivo" placeholder="Ingresa una breve descripción del archivo" rows="3" data-parsley-required="true"/></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nuevoArchivo">Formato o Archivo *</label>
                            <input id="inputEvidenciasArchivo" name="evidenciaArchivo[]" type="file" multiple />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <br>
                            <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevoArchivo"><i class="fa fa-plus"></i> Agregar</a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="errorNuevoArchivo"></div>
                    </div>
                </form>
            </div>
            <!--Finalizando Formulario para nuevo archivo-->                
            </fieldset>
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel nuevo archivo -->
</div>
<!-- Finalizando #contenido -->