<?php

use Controladores\Controller_Base as Base;
use Librerias\Generales\Reportes as Reportes;

/**
 * Description of Controller_Solicitud
 *
 * @author Freddy
 */
class Controller_Reportes extends Base {

    private $Reportes;

    public function __construct() {
        parent::__construct();
    }

    public function manejarEvento(string $evento = null) {
        $this->Reportes = new Reportes();
        try {
            switch ($evento) {
                case 'solicitudSemanal':
                    $resultado = $this->Reportes->getFoliosSemanal();
                    break;
                case 'solicitudAnual':
                    $resultado = $this->Reportes->getFoliosAnual();
                    break;
                case 'EquiposRefaccionesCorrectivo':
                    $resultado = $this->Reportes->getEquiposRefaccionesCorrectivo();
                    break;
                default:
                    $resultado = FALSE;
                    break;
            }
            echo json_encode($resultado);
        } catch (\Exception $ex) {
            $resultado = $ex->getMessage();
        }
    }

}
