<div class="row m-l-5">
    <input type="hidden" id="idRevision" value="<?php echo $data['Id']; ?>" />
    <div class="col-md-5 col-sm-4 col-xs-12">
        <h6 class="f-w-600">Área y Punto:</h6>
        <h5><?php echo $data['AreaPunto']; ?></h5>
    </div>
    <div class="col-md-6 col-sm-7 col-xs-12">
        <h6 class="f-w-600">Equipo:</h6>
        <h5><?php echo $data['Equipo']; ?></h5>
    </div>
</div>
<div class="row m-l-5 m-t-10">
    <input type="hidden" id="idRevision" value="<?php echo $data['Id']; ?>" />
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h6 class="f-w-600">Serie:</h6>
        <h5><?php echo $data['Serie']; ?></h5>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h6 class="f-w-600">Componente:</h6>
        <h5><?php echo $data['Componente']; ?></h5>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h6 class="f-w-600">Tipo Diágnostico:</h6>
        <h5><?php echo $data['TipoDiagnostico']; ?></h5>
    </div>
</div>
<div class="row m-l-5 m-t-10">
    <input type="hidden" id="idRevision" value="<?php echo $data['Id']; ?>" />
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h6 class="f-w-600">Falla:</h6>
        <h5><?php echo $data['Falla']; ?></h5>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h6 class="f-w-600">Fecha:</h6>
        <h5><?php echo $data['Fecha']; ?></h5>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php 
            if($data['Flag'] == 1){
                $classbtn = 'btn-danger';
                $flag = 'Inhabilitar';
            }
        ?>
        <button type="button" id="editarEstatus" class="btn btn-block <?php echo $classbtn ?>" value="<?php echo $data['Flag'];?>"><?php echo $flag;?></button>
    </div>
</div>