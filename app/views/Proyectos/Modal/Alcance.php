<div class="row">
    <div class="col-md-12">
        <!--Empieza formulario de alacance-->

        <fieldset>
            <!--Empezando selects de ubicación-->
            <div class="row">
                <form class="margin-bottom-0" id="formAlcanceProyecto" data-parsley-validate="true">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selectConcepto">Concepto</label>
                            <select id="selectConcepto" class="form-control" style="width: 100%" data-parsley-required="true" >
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($concepto as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selectArea">Área</label>
                            <select id="selectArea" class="form-control alcanceProyecto" style="width: 100%"  data-parsley-required="true" disabled>
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selectUbicación">Ubicación</label>
                            <select id="selectUbicacion" class="form-control alcanceProyecto" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>                
            <!--Finalizando selects de ubicacion-->

            <!--Empezando informacion de nodos-->
            <?php if ($tipoProyecto == 1) { ?>
                <div class="row">
                    <legend>Nodos</legend>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    Datos
                                    <input type="checkbox" value="" class="alcanceProyecto" disabled/>
                                </span>
                                <input type="number" class="form-control checkDatos" id="nodoDatos" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    Voz
                                    <input type="checkbox" value="" class="alcanceProyecto" disabled/>
                                </span>
                                <input type="number"  class="form-control checkVoz" id="nodoVoz" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    Video
                                    <input type="checkbox" value="" class="alcanceProyecto" disabled/>
                                </span>
                                <input type="number" class="form-control checkVideo" id="nodoVideo"  disabled/>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!--Finalizando información de nodos-->

            <!--Empezando formulario para agregar accesorios-->
            <div class="row">
                <legend>Accesorios</legend>           
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="accesorio">Accesorio</label>
                        <select id="selectAccesorio" class="form-control alcanceProyecto" style="width: 100%" disabled>
                            <option value="">Seleccionar</option>
                            <?php
                            foreach ($accesorios as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-8 ">
                    <div class="form-group">
                        <label for="cantidadAccesorio">Cantidad</label>
                        <div class="form-inline ">
                            <input type="number" class="form-control alcanceProyecto" id="inputCantidadAccesorio" placeholder="Cantidad de material" disabled/>
                            <a href="javascript:;" class="btn btn-success m-r-5 disabled alcanceProyecto" id="btnAgregarAccesorio" ><i class="fa fa-plus"></i> Agregar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="errorAgregarAccesorio"></div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <table id="data-table-accesorios" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>                                        
                                    <th class="all">Accesorio</th>
                                    <th class="all">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>               
            </div>
            <!--Finalizando formulario para agregar accesorios-->

            <div class="row">
                <div class="col-md-12 m-t-20">
                    <div class="alert alert-warning fade in m-b-15">                            
                        Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.                            
                    </div>                        
                </div>
            </div>   

            <!--Empezando boton para guardar accesorios-->
            <div class="row">
                <div class="col-md-12 ">
                    <div class="errorGuardarAccesorio"></div>
                </div>
                <div class="col-md-offset-4 col-md-4 text-center">
                    <button type="button" class="btn btn-sm btn-primary m-r-5 alcanceProyecto" id="btnGuardarAccesorio" disabled>Guardar</button>
                </div>
            </div>
            <!--Finalizando boton para guardar accesorios-->

            <!--Empezando tabla de resumen de accesorios-->
            <div class="row">
                <legend class="m-t-30">Resumen Accesorios</legend>
                <div class="col-md-12">
                    <div class="form-group">
                        <table id="data-table-resumen" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">                       
                            <thead>
                                <tr>
                                    <th class="all">Concepto</th>
                                    <th class="all">Area</th>                              
                                    <th class="all">Ubicacion</th>
                                    <?php
                                    if ($tipoProyecto === '1') {
                                        echo '<th class="all">Datos</th>';
                                        echo '<th class="all">Voz</th>';
                                        echo '<th class="all">Video</th>';
                                    }
                                    foreach ($accesorios as $item) {
                                        echo '<th class="none">' . $item['Nombre'] . '</th>';
                                    }
                                    ?>
                                    <th class="all">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>                                
                            </tbody>
                        </table>               
                    </div>
                </div>
            </div>
            <!--Finalizando tabla de resumen de accesorios-->
        </fieldset>
        <!--Finalizando formulario de alcance-->
    </div>
</div>