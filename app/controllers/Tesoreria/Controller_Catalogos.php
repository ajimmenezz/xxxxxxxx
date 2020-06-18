<?php

use Controladores\Controller_Base as Base;

/*
 * Clase encargada de llevar los procesos para el acceso al sistema y el 
 * arranque del mismo.
 */

class Controller_Catalogos extends Base {

    private $catalogosTesoreria;

    public function __construct() {
        parent::__construct();
        $this->catalogosTesoreria = \Librerias\Tesoreria\Catalogos::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'MostrarFormularioViaticos':
                $resultado = $this->catalogosTesoreria->mostrarFormularioViaticos($this->input->post());
                break;
            case 'MostrarTablaSucursalesAsociado':
                $resultado = $this->catalogosTesoreria->mostrarTablaSucursalesAsociado($this->input->post());
                break;
            case 'GuardarViaticosOutsourcing':
                $resultado = $this->catalogosTesoreria->guardarViaticosOutsourcing($this->input->post());
                break;
            case 'GuardarMontosOutsourcing':
                $resultado = $this->catalogosTesoreria->guardarMontosOutsourcing($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
