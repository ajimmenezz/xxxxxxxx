<div class="row">
    <div class="col-md-"
</div>

<?php
foreach ($subelementos as $key => $value) {
    ?>
    <div class="panel panel-info overflow-hidden">
        <div class="panel-heading">
            <h3 class="panel-title f-w-600">
                <input type="number" value="0" min="0" max="99" placeholder="0" data-subelement="<?php echo $value['Id']; ?>" class="pull-right text-primary inputNumberOfSubelements">
                <?php
                echo $value['Nombre'];
                ?>                
            </h3>
        </div>        
        <div class="panel-body">
            <div id="panelSeriesSubelementos-<?php echo $value['Id']; ?>" class="panelSeriesSubelementos">
                <div class="note note-warning">
                    <h4 class="f-w-500">Sin sub-elementos!</h4>
                    <p class="f-w-500">
                        La cantidad de sub-elementos debe ser mayor a 0 si deseas agregar las series.
                    </p>
                </div>
            </div>
        </div>        
    </div>
    <?php
}
?>