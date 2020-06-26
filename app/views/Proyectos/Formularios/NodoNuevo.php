<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Agregar <small>ubicación/nodos</small></h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>
<div id="panel-nueva-ubicacion" class="panel panel-inverse">            
    <div class="panel-heading">
        <div class="panel-heading-btn">                
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title">Agregar nueva ubicación y nodos</h4>
    </div>            
    <div class="panel-body">   
        <!--Empezando alerta-->
        <div class="row">
            <div id="errorFormularioUbicacion" class="col-md-12">
            </div>
        </div>
        <!--Finalizando alerta-->
        <!--Empezando formulario-->
        <form id="form-nueva-ubicacion" data-parsley-validate="true">    
            <!--Empezando fila 1-->
            <div class="row">    
                <div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <label >Concepto</label>
                        <select id="select-concepto" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar</option> 
                            <?php
                            foreach ($listasSelects['Conceptos'] as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <label >Área</label>
                        <select id="select-area" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar</option>                   
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <label >Ubicación</label>
                        <select id="select-ubicacion" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar</option>
                        </select>
                    </div>
                </div>
            </div>
            <!--Finalizando fila 1-->
        </form>
        <!--Finalizando formulario-->       

        <!--Empezando Captura de material del nodo-->
        <div id="contenedor-formulario-nodo-material">

            <!--Empezando formulario-->
            <form id="form-define-material-nodo" data-parsley-validate="true">
                <!--Empezando titulo-->
                <div class="row">
                    <div class="col-md-12">
                        <h5>Definiendo Nodos para la Ubicación</h5>
                    </div>
                </div>
                <!--Finalizando titulo-->                                        

                <!--Empezando Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15"></div>
                    </div>
                </div>
                <!--Finalizando Separador--> 

                <!--Empezando fila 1-->
                <div class="row">    
                    <div class="col-xs-12 col-md-4">
                        <label >Tipo Nodo</label>
                        <div class="form-group">
                            <select id="select-tipo-nodo" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar</option>     
                                <option value="1">Datos</option>     
                                <option value="2">Voz</option>     
                                <option value="3">Video</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label >Nombre</label>
                        <div class="form-group">
                            <input id="input-nombre-nodo" type="text" class="form-control" placeholder="Nombre nodo" data-parsley-required="true"/>
                        </div>
                    </div>        
                </div>
                <!--Finalizando fila 1-->

                <!--Empezando fila 2-->
                <div class="row">    
                    <div class="col-xs-12 col-md-4">
                        <label >Acccesorio</label>
                        <div class="form-group">
                            <select id="select-accesorio" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($listasSelectsMaterial['Accesorios'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label >Material</label>
                        <div class="form-group">
                            <select id="select-material-nodo" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Seleccionar</option>
                                <?php
//                        foreach ($listasSelectsMaterial['Material'] as $item) {
//                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
//                        }
                                ?>
                            </select>
                        </div>
                    </div>            
                    <div class="col-xs-9 col-sm-10 col-md-3">
                        <label >Cantidad</label>
                        <div class="form-group">
                            <input id="input-cantidad-material" type="number" class="form-control"  placeholder="Cantidad" data-parsley-required="true" />
                        </div>
                    </div>        
                    <div class="col-xs-3 col-sm-2 col-md-1 text-left">
                        <label >&nbsp;</label>
                        <div class="form-group">
                            <a id="btn-agregar-material" href="javascript:;" class="btn btn-success m-r-5"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>        
                <!--Finalizando fila 2 -->
            </form>
            <!--Finalizando formulario-->

            <!--Empezando alerta-->
            <div class="row">
                <div id="mensajeError" class="col-md-12">
                </div>
            </div>
            <!--Finalizando alerta-->

            <!--Empezando Separador-->
            <div class="row">
                <div class="col-md-12">
                    <div class="underline m-b-15"></div>
                </div>
            </div>
            <!--Finalizando Separador--> 

            <!--Empezando tabla-->
            <div class="row">
                <div class="col-md-12">
                    <table id="data-table-material-nodo" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="all">Tipo</th>
                                <th class="all">Nodo</th>
                                <th class="not-mobile-l">Accesorio</th>                        
                                <th class="not-mobile-l">Material</th>
                                <th class="not-mobile-l">Cantidad</th>
                                <th class="never">IdTipo</th>
                                <th class="never">IdAccesorio</th>
                                <th class="never">IdMaterial</th>
                            </tr>
                        </thead>
                        <tbody>                
                        </tbody>
                    </table>
                </div> 
            </div>
            <!--Finalizando tabla-->

            <!--Empezando indicaciones--> 
            <div class="row">
                <div class="col-md-12 m-t-15">                                
                    <div class="alert alert-info fade in ">
                        <strong>Informacion : </strong>
                        Para eliminar el registro de la tabla puntos solo tiene que dar click sobre fila.
                    </div>
                </div>
            </div>
            <!--Finalizando indicaciones--> 

            <!--Empezando alerta-->
            <div class="row">
                <div id="errorGuardarFormulario" class="col-md-12">
                </div>
            </div>
            <!--Finalizando alerta-->    
        </div>
        <!--Finalizando Captura de material del nodo-->
    </div>
</div>