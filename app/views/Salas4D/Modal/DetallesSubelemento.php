<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Información del sub-elemento</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="panelInfosubelemento" class="panel panel-inverse">        
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title">Infromación del sub-elemento</h4>        
    </div>
    <div class="panel-body">
        <div id="infoReadOnly">
            <div class="row"> 
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-grup">
                        <h4 class="m-t-10">Detalles del sub-elemento</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="btn-group pull-right">                        
                        <button id="btnDeleteSubelemento" data-id="<?php echo $detalles['Id']; ?>" data-toggle="tooltip" data-placement="top" title="Eliminar el sub-elemento" class="btn btn-danger"><i class="fa fa-minus-square text-white" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>                                               
                </div>
                <div class="col-md-12">
                    <div id="errorInfoSubelemento"></div>
                </div>
            </div>     
            <div class="row">            
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700 f-s-13">Sub-elemento:</label>
                        <input type="text" value="<?php echo $detalles['Subelemento']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700 f-s-13">Elemento:</label>
                        <input type="hidden" value="<?php echo $detalles['Id']; ?>" id="id-elemento-h" />
                        <input type="text" value="<?php echo $detalles['Elemento']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700 f-s-13">Serie:</label>
                        <input type="text" value="<?php echo $detalles['Serie']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700 f-s-13">Clave Cinemex (Inventario):</label>                    
                        <input type="text" value="<?php echo $detalles['ClaveCinemex']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                    </div>
                </div>
            </div>
            <div class="row">            
                <div class="col-md-6 col-sm-6 col-xs-12">                
                    <div class="form-group">
                        <label class="f-w-700 f-s-13">Ubicación:</label>
                        <input type="text" value="<?php echo $detalles['Ubicacion']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700 f-s-13">Sistema:</label>                    
                        <input type="text" value="<?php echo $detalles['Sistema']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-11 col-sm-11 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-700 f-s-13">Fotografía(s):</label>                                            
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 m-t-20">                                                          
                                <?php
                                if (array_key_exists("Imagen", $detalles) && $detalles['Imagen'] != '') {
                                    $evidencias = explode(",", $detalles['Imagen']);
                                    foreach ($evidencias as $key => $value) {
                                        echo '<div class="thumbnail-pic m-5 p-5">';
                                        $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                                        switch ($ext) {
                                            case 'png': case 'jpeg': case 'jpg': case 'gif':
                                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="' . $value . '" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                break;
                                            case 'xls': case 'xlsx':
                                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/excel_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                break;
                                            case 'doc': case 'docx':
                                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/word_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                break;
                                            case 'pdf':
                                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/pdf_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                break;
                                            default :
                                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/no-thumbnail.jpg" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                break;
                                        }
                                        echo '</div>';
                                    }
                                }
                                ?>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>                                           
        </div>        
    </div>        
</div>