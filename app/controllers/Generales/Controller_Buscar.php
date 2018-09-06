<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Buscar
 *
 * @author Alonso
 */
class Controller_Buscar extends Base{        

    private $catalogo;
    private $busqueda;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->busqueda = \Librerias\Generales\Busqueda::factory();
    }
    
    
    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Catalogos':
                $resultado = [
                    'status' => $this->catalogo->catStatus('5'),
                    'sucursales' => $this->catalogo->catSucursales('3'),
                    'departamentos' => $this->catalogo->catDepartamentos('5'),
                    'prioridades' => $this->catalogo->catPrioridades('3'),
                    'personal' => $this->catalogo->catUsuarios('5'),
                    'tiposServicio' => $this->catalogo->catTiposServicio('3')
                ]; 
                break;   
            case 'Reporte':
                $resultado = $this->busqueda->busquedaReporte($this->input->post());
                break;
            case 'Excel':
                $resultado = $this->busqueda->exportarExcel($this->input->post());
                break;
            case 'Detalles':
                $resultado = $this->busqueda->detalles($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
