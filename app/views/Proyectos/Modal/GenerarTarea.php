<div class="row">
    <div class="col-md-12">
        <!--Empieza formulario de tarea--> 
        <form class="margin-bottom-0" id="formNuevaTarea" data-parsley-validate="true">
            <fieldset>         
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selectConceptoTarea">Concepto</label>
                            <select id="selectConceptoTarea" class="form-control" style="width: 100%" data-parsley-required="true">
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
                            <label for="selectAreaTarea">Área</label>
                            <select id="selectAreaTarea" class="form-control tareasProyecto" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selectLiderTarea">Líder</label>
                            <select id="selectLiderTarea" class="form-control tareasProyecto" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($lideres as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="selectTipoTarea">Tarea</label>
                            <select id="selectTipoTarea" class="form-control tareasProyecto" style="width: 100%" data-parsley-required="true" disabled>
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($tareas as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <legend>Asistentes</legend>
  
                    <div class="col-md-4">
                        <div class="form-group">
                            <select id="selectAsistenteTarea" class="form-control tareasProyecto" style="width: 100%" disabled>
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($asistentes['oficiales'][0] as $item) {
                                    echo '<option value="' . $item['Id'] . '" data-NSS="' . $item['NSS'] . '">' . $item['Nombre'] . '</option>';
                                }
                                foreach ($asistentes['asistentes'][0] as $item) {
                                    echo '<option value="' . $item['Id'] . '" data-NSS="' . $item['NSS'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <a id="btnAgregarAsistenteTarea" href="javascript:;" class="btn btn-success m-r-5 tareasProyecto" disabled><i class="fa fa-plus"></i> Agregar</a>
                    </div>

                    <!--Empezando mensaje de eror de fechas-->
                    <div class="col-md-12">
                        <div class="errorAgregarAsistente"></div>
                    </div>
                    <!--Finalizando mensaje de eror de fechas-->

                    <div class="col-md-12">
                        <div class="form-group">
                            <table id="data-table-asistentesTareas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%" data-editar="true">
                                <thead>
                                    <tr>
                                        <th class="none">Id</th>
                                        <th class="all">Nombre</th>
                                        <th class="all">NSS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-t-20">
                        <div class="alert alert-warning fade in m-b-15">                            
                            Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.                            
                        </div>                        
                    </div>
                </div>                
                <div class="row m-t-20">
                    <div class="col-md-offset-3 col-md-3 text-center">
                        <label for="control-label"> Fecha inicio </label>
                        <div id="fecha-inicial-tarea" class="input-group date calendarioTarea" >
                            <input type="text" class="form-control tareasProyecto" placeholder="Fecha Inicio" readonly disabled/>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <label for="control-label"> Fecha Termino </label>
                        <div id="fecha-termino-tarea" class="input-group date calendarioTarea" >
                            <input type="text" class="form-control tareasProyecto" placeholder="Fecha Termino" readonly disabled/>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <!--Empezando mensaje de eror de fechas-->
                    <div class="col-md-12 m-t-20">
                        <div class="errorGuardarTarea"></div>
                    </div>
                    <!--Finalizando mensaje de eror de fechas-->
                </div>
            </fieldset>
        </form>
        <!--Finaliza formulario de tarea--> 
    </div>        
</div>        