<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-600">No. Incidentente *</label>
            <input class="form-control" type="text" id="inputNoIncidente" value="<?php echo $orderNumber; ?>" disabled/>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-600">Persona que solicita *</label>
            <input class="form-control" type="text" id="inputNombreTecnico" value="<?php echo $technicalName; ?>" disabled/>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-600">Origen *</label>
            <select id="selectOrigen" class="form-control" style="width: 100%">
                <option value="">Selecciona . . .</option>
                <?php
                foreach ($sucursales as $key => $value) {
                    echo '<option value="' . $value['Nombre'] . '">' . $value['Nombre'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-600">Destino *</label>
            <select id="selectDestino" class="form-control" style="width: 100%">
                <option value="">Selecciona . . .</option>
                <?php
                foreach ($sucursales as $key => $value) {
                    echo '<option value="' . $value['Nombre'] . '">' . $value['Nombre'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-600">Personal de TI que autoriza *</label>
            <select id="lista-TI" class="form-control" style="width: 100%">
                <option value="">Selecciona . . .</option>
                <?php
                foreach ($TIList as $key => $value) {
                    echo '<option value="' . $value['userName'] . '">' . $value['userName'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-600">No. Cajas *</label>
            <input class="form-control" id="inputNumeroCajas" type="number" value="1" min="1"  />
        </div>
    </div>
</div>

<form id="formInformationBoxes" data-parsley-validate="true">
    <div class="classForm">
        <div class="row m-t-5">
            <div class="col-md-2 col-sm-2 col-xs-12">
                <div class="form-grup">
                    <label class="f-w-600">Caja</label>
                    <input type="text" value="#1" disabled="disabled" class="form-control f-s-16 text-center" />
                </div>
            </div>
        </div>
        <div class="row m-t-5">
            <div class="col-md-3 col-sm-3 col-xs-3">
                <label class="f-w-600">Peso *</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="info-peso-1" data-parsley-required="true"/>
                    <span class="input-group-addon">kg</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <label class="f-w-600">Largo *</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="info-largo-1" data-parsley-required="true"/>
                    <span class="input-group-addon">cm</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <label class="f-w-600">Ancho *</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="info-ancho-1" data-parsley-required="true"/>
                    <span class="input-group-addon">cm</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <label class="f-w-600">Alto *</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="info-alto-1" data-parsley-required="true"/>
                    <span class="input-group-addon">cm</span>
                </div>
            </div>
        </div>
    </div>

    <!--Empezando error--> 
    <div class="row m-t-10">
        <div class="col-md-12">
            <div id="errorFormularioInformacionGeneracionGuia"></div>
        </div>
    </div>
    <!--Finalizando Error-->
</form>

