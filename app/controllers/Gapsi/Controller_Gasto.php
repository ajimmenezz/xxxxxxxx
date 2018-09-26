<?php

use Controladores\Controller_Base as Base;

class Controller_Gasto extends Base {

    private $catalogo;

    public function __construct() {
        parent::__construct();
        $this->catalogo = new \Librerias\Gapsi\Catalogos();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'BeneficiarioByTipo':
                $resultado = $this->catalogo->beneficiarioByTipo($this->input->post());
                break;
            case 'CategoriasByTipoTrans':
                $resultado = $this->catalogo->categoriasByTipoTrans($this->input->post());
                break;
            case 'SubcategoriasByCategoria':
                $resultado = $this->catalogo->subcategoriasByCategoria($this->input->post());
                break;
            case 'ConceptosBySubcategoria':
                $resultado = $this->catalogo->conceptosBySubcategoria($this->input->post());
                break;
            case 'ProyectosByCliente':
                $resultado = $this->catalogo->proyectosByCliente($this->input->post());
                break;
            case 'SucursalesByProyecto':
                $resultado = $this->catalogo->sucursalesByProyecto($this->input->post());
                break;
            case 'SolicitarGasto':
                $resultado = $this->catalogo->solicitarGasto($this->input->post());
                break;
            case 'MisGastos':
                $resultado = $this->catalogo->misGastos();
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
