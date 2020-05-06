<?php

use Controladores\Controller_Base as Base;

class Controller_UnidadesNegocio extends Base {

    private $catalogo;
    private $catologosPoliza;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
//        $this->catologosGenericos = \Librerias\Catalogos\CatalogosGenericos::factory();
        $this->catologosPoliza = \Librerias\Poliza\Catalogos::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Nueva_Unidad_Negocio':
                $resultado = $this->catalogo->catUnidadesNegocio('1', array($this->input->post('nombre'), $this->input->post('cliente')));
                break;
            case 'Actualizar_Unidad_Negocio':
                $resultado = $this->catalogo->catUnidadesNegocio('2', array($this->input->post('id'), $this->input->post('nombre'), $this->input->post('cliente'), $this->input->post('estatus')));
                break;
            case 'MostrarDatosActualizar':
                $resultado = $this->catologosPoliza->mostrarDatosActualizarUnidadNegocio();
                break;
            case 'MostrarFormularioUnidadNegocio':
                $resultado = $this->catologosPoliza->mostrarFormularioUnidadNegocio($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
