<?php

use Controladores\Controller_Base as Base;

class Controller_Departamentos extends Base {

    private $catalogo;
    private $departementos;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->departementos = \Librerias\RH\Departamentos::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Nuevo_Departamento':
                $resultado = $this->catalogo->catDepartamentos('1', $this->input->post());
                break;
            case 'MostrarDepartamentoActualizar':
                $resultado = $this->catalogo->catDepartamentos('3', array($this->input->post('Departamento')));
                break;
            case 'Actualizar_Departamento':
                $resultado = $this->catalogo->catDepartamentos('2', $this->input->post());
                break;
            case 'MostrarFormularioDepartamentos':
                $resultado = $this->departementos->mostrarFormularioDepartamentos($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
