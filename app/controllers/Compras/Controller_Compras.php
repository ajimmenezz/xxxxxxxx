<?php

use Controladores\Controller_Base as Base;

/*
 * Clase encargada de llevar los procesos para el acceso al sistema y el 
 * arranque del mismo.
 */

class Controller_Compras extends Base {

    private $catalogo;
    private $compras;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->compras = \Librerias\Compras\Compras::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'MostrarDatosSucursalesBeneficiarios':
                $resultado = $this->compras->mostrarDatosProyectosBeneficiarios($this->input->post());
                break;
            case 'MostrarFormularioOrdenCompra':
                $resultado = $this->compras->mostrarFormularioOrdenCompra($this->input->post());
                break;
            case 'GuardarOrdenCompra':
                $resultado = $this->compras->guardarOrdenCompra($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
