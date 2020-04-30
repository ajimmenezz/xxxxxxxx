<form id="formInstalaciones" data-parsley-validate="true" enctype="multipart/form-data">
    <div class="panel-body">
        <div id="seccion-formulario">
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
                        <select id="selectAreaAtencionInstalaciones" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                            <option value="">Seleccionar</option>
                        </select>
                    </div>                               
                </div>
                <div class="col-md-2">
                    <div class="form-group">                                           
                        <h5 class="f-w-700">Punto *</h5>
                        <select id="selectPuntoInstalaciones" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                            <option value="">Seleccionar</option>
                        </select>
                    </div>                               
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <h5 class="f-w-700">Modelo *</h5>
                        <select id="selectModeloInstalaciones" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                            <option value="">Seleccionar</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">                                           
                        <h5 class="f-w-700">Serie *</h5>
                        <input id="inputSerieInstalaciones" class="form-control" placeholder="Serie" data-parsley-required="true" disabled/>
                    </div>                               
                </div>
<!--                <div id="divIlegible" class="col-md-2 m-t-30 hidden"> 
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="inputIlegibleInstalciones" name="inputIlegibleInstalciones" value="1"/>
                                Ilegible
                            </label>
                        </div>
                    </div>                            
                </div>-->
            </div>
            <div id="divAdjuntos" class="row hidden">
                <div class="col-md-12">
                    <h5 class="f-w-700">Adjuntos *</h5>
                    <div id="archivoEquipo" class="form-group">
                        <input id="agregarEvidenciaEquipo" name="agregarEvidenciaEquipo[]" type="file" multiple>
                    </div>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-12">
                    <div class="errorInstalaciones"></div>
                </div>

                <div class="row m-t-10">
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <a id="btnAgregarEquipoInstalacion" href="javascript:;" class="btn btn-success m-r-5 "><i class="fa fa-plus"></i> Agregar</a>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="data-table-equipos-instalaciones" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                <thead>
                    <tr>
                        <th class="never">Id</th>
                        <th class="never">IdModelo</th>
                        <th class="all">Modelo</th>
                        <th class="all">Serie</th>
                        <th class="never">IdArea</th>
                        <th class="all">Área</th>
                        <th class="all">Punto</th>
                        <th class="never">IdOperación</th>
                        <th class="all">Operación</th>
                        <th class="all divAcciones" data-acciones="true">Acciones</th>
                    </tr>
                </thead>
                <tbody>                                      
                </tbody>
            </table>
        </div>
    </div>
</form>