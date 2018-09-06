<?php
    if (empty($solucionServicio)){
         $mostrarElemento = '<div id="divElementoUtilizado" style="display:none">'; 
         $mostrarSubelemento = '<div id="divSubelementoUtilizado" style="display:none">';
    }else if ($solucionServicio[0]['IdTipoSolucion'] === "2"){
        $mostrarElemento = '<div id="divElementoUtilizado" style="display:block">';
        $mostrarSubelemento = '<div id="divSubelementoUtilizado" style="display:none">'; 
    }else if($solucionServicio[0]['IdTipoSolucion'] === "3"){
        $mostrarElemento = '<div id="divElementoUtilizado" style="display:none">';       
        $mostrarSubelemento = '<div id="divSubelementoUtilizado" style="display:block">';
    }else if($solucionServicio[0]['IdTipoSolucion'] === "1"){
        $mostrarElemento = '<div id="divElementoUtilizado" style="display:none">'; 
        $mostrarSubelemento = '<div id="divSubelementoUtilizado" style="display:none">';
    }
?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Información de la Solución</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <label for="tipoSolucion">Tipo de Solución *:</label>
                        <select id="tipoSolucion" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                            <option value="">Seleccionar</option>
                            <?php
                                foreach ($tipoSolucion as $item) {
                                    if(!empty($solucionServicio) || !empty($informeGeneral)){
                                      $select = ($solucionServicio[0]['IdTipoSolucion'] == $item['id']) ? 'selected' : '';  
                                      $hidden = ($informeGeneral['tipoFalla'] == "2" && $item['id'] == "2") ? 'style="display:none "': '';
                                      echo '<option value="' . $item['id'] . '" '.$select.' '.$hidden.'>' . $item['text'] . '</option>';
                                    }else{
                                        echo '<option value="' . $item['id'] . '">' . $item['text'] . '</option>';
                                    }
                                    
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <?php echo $mostrarElemento; ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Elemento utilizado</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label for="elementoUtilizado">Elemento *:</label>
                                <select id="elementoUtilizado" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                        foreach ($productosAlmacen as $item) {
                                            if($item['IdtipoProducto'] === "3"){
                                                $selected = ($item['id'] == $elemento) ? 'selected' : '';
                                                echo '<option value="' . $item['id'] . '" '.$selected.'>' . $item['text'] . '</option>';
                                            }
                                        }
                                    ?>
                                </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 m-t-20">
                            <div class="alert alert-warning fade in m-b-15">                            
                                Al terminar la documentación del servicio, le sera descontado el elemento seleccionado de su inventario y se agregara el elemento que falla.                           
                            </div>                        
                        </div>
                    </div>
                </div>
                <?php echo $mostrarSubelemento; ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Subelemento(s) utilizado(s)</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label for="subelementoDanado">Subelemento dañado *:</label>
                                <select id="subelementoDanado" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                </select>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="subelementoUtilizado">Subelemento utilizado *:</label>
                                <select id="subelementoUtilizado" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                </select>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div id="errorSubelementoSolucio"></div>
                        </div>
                    </div>
                    <div class="row m-t-15">
                        <div class="col-md-6 col-xs-6 text-right">
                            <label id="btnAgregarSubelementoSolucion" class="btn btn-success">
                                Agregar
                            </label> 
                        </div>
                    </div>
                    <div class="row m-t-15">
                        <div class="table-responsive">
                            <table id="tablaSubelementosCorrectivo" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">IdDañado</th> 
                                        <th class="all">Subelemento dañado</th>
                                        <th class="never">IdUtilizado</th>
                                        <th class="all">Subelemento utilizado</th>
                                    </tr>
                                </thead>
                                <tbody>                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 m-t-20">
                            <div class="alert alert-warning fade in m-b-15">                            
                                Para eliminar el producto da doble click a la fila                           
                            </div>                        
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Adicionales</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="control-label">Notas adicionales *:</label>
                        <textarea id="notasSolucion" class="form-control" placeholder="" rows="5"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                            <?php
                                foreach ($solucionServicio as $valorEvidencia) {
                                   $evidenciasExplode = explode(',', $valorEvidencia['Archivos']);
                                }
                                if(!empty($evidenciasExplode) && $evidenciasExplode[0] !== ""){
                                    echo '<div class="evidenciaMantoCorrectivo m-t-15 p-5">';
                                    foreach ($evidenciasExplode as $key => $valorUrl) {
                                        $posicion = strrpos($valorUrl, '/');
                                        $nameSubstr = substr($valorUrl, $posicion + 1);
                                        echo '<div id = "evidenciaCorrectivo">';
                                        echo '<img src = "'.$valorUrl.'" alt = "" style = "width:100%"/>';
                                        echo '<p class="nombreArchivo">' . $nameSubstr . '</p>';
                                        echo '<button type="button" class="btn btn-sm btn-danger btn-xs btnEliminarEvidencia" data-numeroKey ="'.$key.'" data-urlArchivo="' . $valorUrl . '"><i class="fa fa-trash"></i></button>';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                }
                            ?>
                    </div>
                </div>
                <div class="row">
                    <div id="archivosCorrectivos" class="col-md-12 col-xs-12 m-t-15">
                        <div class="form-group">
                            <label>Adjuntar archivos</label>
                            <input id="inputArchivoCorrectivo" name="inputArchivoCorrectivo[]" type="file" multiple />
                        </div>
                    </div>
                </div>
                <div class="row m-t-15">
                    <div class="col-md-12">
                        <div id="errorSolucion"></div>
                    </div>                                    
                </div>
                <div class="row m-t-15">
                    <div class="col-md-6 col-xs-6 text-right">
                        <label id="btnAgregarSolucion" class="btn btn-success">
                            Guardar información
                        </label>
                    </div>
                </div>
