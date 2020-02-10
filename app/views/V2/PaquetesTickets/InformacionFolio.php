<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h5 class="f-w-700">Creado por:</h5>
        <input type="text" class="form-control" value="<?php echo $folio->CREATEDBY; ?>" disabled/>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h5 class="f-w-700">Solicita:</h5>
        <input type="text" class="form-control" value="<?php echo $folio->CREATEDBY; ?>" disabled/>                         
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h5 class="f-w-700">Fecha Cración:</h5>
        <input type="text" class="form-control" value="<?php echo date('Y-m-d H:i:s', $folio->CREATEDTIME / 1000); ?>" disabled/>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h5 class="f-w-700">Prioridad:</h5>
        <input type="text" class="form-control" value="<?php echo $folio->PRIORITY; ?>" disabled/>                         
    </div>
</div>
<ul class="nav nav-pills m-t-25">
    <li class="active"><a href="#descripcionFolio" data-toggle="tab">Descripción</a></li>
    <li><a href="#notasFolio" data-toggle="tab">Notas</a></li>
    <li><a href="#resolucionFolio" data-toggle="tab">Resolución</a></li>
</ul>
<div class="row">
    <div class="col-md-12">
        <div class="underline m-b-10 m-t-10"></div>
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane fade active in" id="descripcionFolio">
        <p class="m-t-10"><?php echo $folio->DESCRIPTION; ?></p>
    </div>
    <div id="notasFolio" class="tab-pane fade">
        <!--Empieza contenedor y scroll del acordean-->
        <div class="height-xs" data-scrollbar="true" data-height="100%" style="padding: 10px;">
            <div id="accordion" class="panel-group">
                <?php
                if ($notasFolio) {
                    foreach ($notasFolio as $key => $value) {
                        echo '<div class="panel panel-inverse overflow-hidden">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$key.'">
                                    <i class="fa fa-plus-circle pull-right"></i> 
                                    ' . $value->USERNAME . ' - ' . date('Y-m-d H:i:s', $value->NOTESDATE / 1000) . '
                                </a>
                            </h3>
                        </div>
                        <div id="collapse'.$key.'" class="panel-collapse collapse">
                            <div class="panel-body">
                            <p>' . $value->NOTESTEXT . '</p>
                            </div>
                        </div>
                    </div>';
                    }
                }
                ?>
            </div>
        </div>
        <!--Finaliza contenedor y scroll del acordean-->


    </div>
    <div class="tab-pane fade" id="resolucionFolio">
        <!--<div class="height-xs" data-scrollbar="true" data-height="20%" style="padding: 10px;">-->
        <p>
            <?php echo $resolucionFolio->RESOLUTION; ?>
        </p>
        <!--</div>-->
    </div>
</div>
