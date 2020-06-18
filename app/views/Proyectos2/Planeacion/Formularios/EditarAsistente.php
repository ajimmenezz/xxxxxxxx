<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">        
        <div class="form-group">
            <input type="hidden" value="<?php echo $asistente['Id']; ?>" id="idTecnico" />
            <label class="f-w-600 f-s-15">Nombre:</label><br />
            <label class="f-w-500 f-s-15"><?php echo $asistente['Nombre']; ?></label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">        
        <div class="form-group">
            <label class="f-w-600 f-s-15">Perfil:</label><br />
            <label class="f-w-500 f-s-15"><?php echo $asistente['Perfil']; ?></label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">        
        <div class="form-group">
            <label class="f-w-600 f-s-15">NSS:</label><br />
            <label class="f-w-500 f-s-15"><?php echo $asistente['NSS']; ?></label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <a href="javascript:;" id="btnEliminarAsistente" class="btn btn-danger btn-block">Eliminar Técnico</a>
    </div>
</div>