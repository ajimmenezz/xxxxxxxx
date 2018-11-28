<?php

use Controladores\Controller_Base as Base;

class Controller_SAEReports extends Base {

    private $SAEReports;

    public function __construct() {
        parent::__construct();
        $this->SAEReports = \Librerias\SAEReports\Reportes::factory();
    }

    /*
     * Se encarga se recibir eventos ajax de la vista
     *
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Carga_Almacenes':
                $resultado = $this->SAEReports->getAlamacenesSAE();
                break;
            case 'getInventarioAlmacen':
                $resultado = $this->SAEReports->getInventarioAlamacenSAE($this->input->post());
                break;
            case 'exportaInventarioAlmacen':
                $resultado = $this->SAEReports->exportaInventarioAlamacenSAE($this->input->post());
                break;
            case 'exportaReporteComprasSAE':
                $resultado = $this->SAEReports->exportaReporteComprasSAE($this->input->post());
                break;
            case 'buscarProductosCompras':
                $resultado = $this->SAEReports->getBuscarProductosCompras($this->input->post());
                break;
            case 'mostrarReporteComprasSAE':
                $resultado = $this->SAEReports->mostrarReporteComprasSAE($this->input->post());
                break;
            case 'mostrarReporteComprasSAEProyecto':
                $resultado = $this->SAEReports->mostrarReporteComprasSAEProyecto($this->input->post());
                break;
            case 'exportaReporteComprasSAEProyecto':
                $resultado = $this->SAEReports->exportaReporteComprasSAEProyecto($this->input->post());
                break;
            case 'GeneraOC':
                $resultado = $this->SAEReports->generaOC($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
