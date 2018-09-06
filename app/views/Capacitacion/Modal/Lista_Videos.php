<?php
    echo '<div class="row">';    
    $cont = 0;
    $contAux = 0;
    foreach ($respuesta as $key => $value) {
        $cont++;
        $contAux++;
        echo '<div class="col-md-4 col-xs-12">
            <video width="320" height="180" controls>
                <source src="'.$value['Url'].'" type="video/mp4" />                
            </video>
            <br />
            '.$value['Nombre'].'
        </div>';
        if($cont == 3){
            echo '</div><div class="row">';
            $cont = 0;
        }
        if($contAux == count($respuesta)){
            echo '</div>';
        }
    }
?>