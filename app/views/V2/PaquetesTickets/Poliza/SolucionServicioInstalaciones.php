<div class="panel-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <h5 class="f-w-700">Operación *</h5>
                <select id="selectOperacionInstalaciones" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">                                           
                <h5 class="f-w-700">Área de Atención *</h5>
                <input id="inputCantidadRefaccionSolicitud" type="number" class="form-control"  placeholder="Cantidad"/>
            </div>                               
        </div>
        <div class="col-md-2">
            <div class="form-group">                                           
                <h5 class="f-w-700">Punto *</h5>
                <input id="inputCantidadRefaccionSolicitud" type="number" class="form-control"  placeholder="Cantidad"/>
            </div>                               
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <h5 class="f-w-700">Modelo *</h5>
                <select id="selectModeloInstalaciones" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">                                           
                <h5 class="f-w-700">Serie *</h5>
                <input id="inputCantidadRefaccionSolicitud" type="number" class="form-control"  placeholder="Cantidad"/>
            </div>                               
        </div>
        <div class="col-md-2 m-t-30"> 
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="inputMultimedia" name="inputMultimedia" value="1" />
                        Ilegible
                    </label>
                </div>
            </div>                            
        </div>
    </div>
    <div class="row m-t-10">
        <!--Empezando error Impericia--> 
        <div class="col-md-12">
            <div class="errorEnviarReporteImpericia"></div>
        </div>
        <!--Finalizando Error Impericia-->

        <div class="row m-t-10">
            <div class="col-md-12">
                <div class="form-group text-center">
                    <a id="btnAgregarEquipoInstalacion" href="javascript:;" class="btn btn-success m-r-5 "><i class="fa fa-plus"></i> Agregar</a>                            
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="data-table-equipos-instalaciones" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
            <thead>
                <tr>
                    <th class="all">Modelo</th>
                    <th class="all">Serie</th>
                    <th class="all">Área</th>
                    <th class="all">Punto</th>
                    <th class="all">Operación</th>
                </tr>
            </thead>
            <tbody>                                      
            </tbody>
        </table>
    </div>
</div>