<div class="row">
    <div class="col-md-12">
        <h4>Puntos Censados</h4>
        <div class="underline"></div>
    </div>
</div>
<div class="row m-t-20">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php
        $arrayRevisados = [];
        if (isset($puntosRevisados) && count($puntosRevisados) > 0) {
            foreach ($puntosRevisados as $key => $value) {
                array_push($arrayRevisados, $value['IdArea'] . '_' . $value['Punto']);
            }
        }


        if (isset($areasPuntos) && count($areasPuntos) > 0) {
            foreach ($areasPuntos as $key => $value) {
                for ($i = 1; $i <= $value['Puntos']; $i++) {
                    $classButton = (in_array($value['IdArea'] . "_" . $i, $arrayRevisados)) ? 'btn-success' : 'btn-white';                    
                    ?>
                    <div class="col-md-2 col-sm-4 col-xs-6 m-t-5 m-b-10">
                        <a data-area="<?php echo $value['IdArea']; ?>" data-punto="<?php echo $i; ?>" class="btn <?php echo $classButton; ?> btn-block f-w-600 f-s-13 btnPuntoArea">
                            <?php echo $value['Area'] . '<br />' . $i; ?>                            
                        </a>
                    </div>
                    <?php
                }
            }
        }
        ?>
    </div>
</div>