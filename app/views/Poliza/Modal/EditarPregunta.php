<form class="margin-bottom-0" id="formAgregarPregunta" data-parsley-validate="true">
    <div class="row">
        <div class="col-md-12 m-t-20">
            <div class="alert alert-warning fade in m-b-15">                            
                Recuerda que la pregunta o concepto debe ser en sentido positivo y para el área en general
            </div>                        
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="f-w-600">Pregunta Categoria (Concepto) *:</label>
                <input class="form-control" type="text" id="txtPreguntaCategoria" placeholder="¿Todos los puntos de red cuentan con tapa?" data-parsley-required="true"/>
            </div>
            <div class="form-group">
                <label class="f-w-600">Etiqueta para reporte *:</label>
                <input class="form-control" type="text" id="txtEtiqueta" placeholder="Los puntos de red que no cuentan con tapa son" data-parsley-required="true" />
            </div>
            <div class="form-group">
                <label class="f-w-600">Categoria *:</label>
                <select id="categoria" class="form-control" style="width: 100% !important;" data-parsley-required="true">
                </select>        
            </div>
            <div class="form-group">
                <label class="f-w-600">Área de atención *:</label>
                <select id="areaAtencion" class="form-control" style="width: 100% !important;" multiple="multiple" data-parsley-required="true">
                </select>        
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <?php 
                if($data['Flag'] == 1){
                    $classbtn = 'btn-danger';
                    $flag = 'Inhabilitar';
                }else{
                    $classbtn = 'btn-primary';
                    $flag = 'Activar';
                }
            ?>
            <button type="button" id="editarEstatus" class="btn btn-block <?php echo $classbtn ?>" value="<?php echo $data['Flag'];?>"><?php echo $flag;?></button>
        </div>
    </div>
</form>