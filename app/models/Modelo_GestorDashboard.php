<?php

namespace Modelos;

class Modelo_GestorDashboard {
 
    public function getVistasDashboards(array $claves) {
        return array('V2/PaquetesDashboard/Graficas_Tendencia','V2/PaquetesDashboard/Graficas_Comparacion');
    }
}
