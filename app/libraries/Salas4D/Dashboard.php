<?php

namespace Librerias\Salas4D;

use Controladores\Controller_Datos_Usuario as General;

class Dashboard extends General {

    private $DBS;
    private $Excel;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Salas4D::factory();
        $this->Excel = new \Librerias\Generales\CExcel();
        parent::getCI()->load->helper('date');
    }

    public function infoInicial(array $datos = []) {
        $inicio = (isset($datos['Inicio'])) ? $datos['Inicio'] : '';
        $fin = (isset($datos['Fin'])) ? $datos['Fin'] : '';

        $returnArray = [
            'html' => "",
            'code' => 200,
            'estatus' => $this->DBS->getGroupEstatus($inicio, $fin),
            'prioridades' => $this->DBS->getGroupPrioridades($inicio, $fin),
            'tipos' => $this->DBS->getGroupTipos($inicio, $fin)
        ];

        return $returnArray;
    }

    public function cargaPanelByEstatus(array $datos = []) {
        $returnArray = [
            'html' => "",
            'code' => 400
        ];

        if (!empty($datos)) {
            $data = [
                'generalesPrioridad' => $this->DBS->getGroupPrioridades($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['id']),
                'generalesTipos' => $this->DBS->getGroupTipos($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['id']),
                'estatus' => ucwords($this->DBS->getEstatusName($datos['id'])),
                'lista' => $this->DBS->getListaSolicitudes($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['id'])
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/DashboardFiltroEstatus', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

    public function CargaPanelByEstatusPrioridad(array $datos = []) {
        $returnArray = [
            'html' => "",
            'code' => 400
        ];

        if (!empty($datos)) {
            $data = [
                'generalesTipos' => $this->DBS->getGroupTipos($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['ids']['estatus'], $datos['ids']['prioridad']),
                'estatus' => ucwords($this->DBS->getEstatusName($datos['ids']['estatus'])),
                'prioridad' => ucwords($this->DBS->getPrioridadName($datos['ids']['prioridad'])),
                'lista' => $this->DBS->getListaSolicitudes($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['ids']['estatus'], $datos['ids']['prioridad'])
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/DashboardFiltroEstatusPrioridad', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

    public function cargaPanelByPrioridad(array $datos = []) {
        $returnArray = [
            'html' => "",
            'code' => 400
        ];

        if (!empty($datos)) {
            $data = [
                'generalesEstatus' => $this->DBS->getGroupEstatus($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['id']),
                'generalesTipos' => $this->DBS->getGroupTipos($datos['fechas']['Inicio'], $datos['fechas']['Fin'], '', $datos['id']),
                'prioridad' => ucwords($this->DBS->getPrioridadName($datos['id'])),
                'lista' => $this->DBS->getListaSolicitudes($datos['fechas']['Inicio'], $datos['fechas']['Fin'], '', $datos['id'])
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/DashboardFiltroPrioridad', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

    public function CargaPanelByPrioridadEstatus(array $datos = []) {
        $returnArray = [
            'html' => "",
            'code' => 400
        ];

        if (!empty($datos)) {
            $data = [
                'generalesTipos' => $this->DBS->getGroupTipos($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['ids']['estatus'], $datos['ids']['prioridad']),
                'estatus' => ucwords($this->DBS->getEstatusName($datos['ids']['estatus'])),
                'prioridad' => ucwords($this->DBS->getPrioridadName($datos['ids']['prioridad'])),
                'lista' => $this->DBS->getListaSolicitudes($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['ids']['estatus'], $datos['ids']['prioridad'])
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/DashboardFiltroPrioridadEstatus', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

    public function cargaPanelByTipo(array $datos = []) {
        $returnArray = [
            'html' => "",
            'code' => 400
        ];

        if (!empty($datos)) {
            $estatusSolicitud = $prioridad = '';
            if (isset($datos['data']['estatusSolicitud'])) {
                $estatusSolicitud = $datos['data']['estatusSolicitud'];
            }
            if (isset($datos['data']['prioridad'])) {
                $prioridad = $datos['data']['prioridad'];
            }
            $data = [
                'estatusServicios' => $this->DBS->getGroupEstatusServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['id'], $estatusSolicitud, $prioridad),
                'generalesSucursales' => $this->DBS->getGroupSucursalesServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['id'], $estatusSolicitud, $prioridad),
                'generalesAtiende' => $this->DBS->getGroupAtiendeServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['id'], $estatusSolicitud, $prioridad),
                'tipo' => ucwords($this->DBS->getTipoName($datos['id'])),
                'estatusSolicitud' => ucwords($this->DBS->getEstatusName($estatusSolicitud)),
                'prioridad' => ucwords($this->DBS->getPrioridadName($prioridad)),
                'lista' => $this->DBS->getListaServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['id'], '', $estatusSolicitud, '', '', $prioridad)
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/DashboardFiltroTipoServicio', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

    public function cargaServiciosBySucursal(array $datos = []) {
        $returnArray = [
            'html' => "",
            'code' => 400
        ];

        if (!empty($datos)) {
            $estatusSolicitud = $prioridad = '';
            if (isset($datos['data']['estatusSolicitud'])) {
                $estatusSolicitud = $datos['data']['estatusSolicitud'];
            }
            if (isset($datos['data']['prioridad'])) {
                $prioridad = $datos['data']['prioridad'];
            }
            $data = [
                'estatusServicios' => $this->DBS->getGroupEstatusServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['data']['tipo'], $estatusSolicitud, $prioridad, $datos['data']['sucursal']),
                'generalesAtiende' => $this->DBS->getGroupAtiendeServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['data']['tipo'], $estatusSolicitud, $prioridad, $datos['data']['sucursal']),
                'tipo' => ucwords($this->DBS->getTipoName($datos['data']['tipo'])),
                'sucursal' => ucwords($this->DBS->getSucursalName($datos['data']['sucursal'])),
                'lista' => $this->DBS->getListaServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['data']['tipo'], '', $estatusSolicitud, $datos['data']['sucursal'], '', $prioridad)
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/DashboardFiltroSucursal', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

    public function cargaServiciosByAtiende(array $datos = []) {
        $returnArray = [
            'html' => "",
            'code' => 400
        ];

        if (!empty($datos)) {
            $estatusSolicitud = $prioridad = '';
            if (isset($datos['data']['estatusSolicitud'])) {
                $estatusSolicitud = $datos['data']['estatusSolicitud'];
            }
            if (isset($datos['data']['prioridad'])) {
                $prioridad = $datos['data']['prioridad'];
            }
            $data = [
                'estatusServicios' => $this->DBS->getGroupEstatusServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['data']['tipo'], $estatusSolicitud, $prioridad, '', $datos['data']['atiende']),
                'generalesSucursal' => $this->DBS->getGroupSucursalesServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['data']['tipo'], $estatusSolicitud, $prioridad, '', $datos['data']['atiende']),
                'tipo' => ucwords($this->DBS->getTipoName($datos['data']['tipo'])),
                'atiende' => ucwords($this->DBS->getUsuarioName($datos['data']['atiende'])),
                'lista' => $this->DBS->getListaServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['data']['tipo'], '', $estatusSolicitud, '', $datos['data']['atiende'], $prioridad)
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/DashboardFiltroAtiende', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

    public function cargaServiciosByEstatus(array $datos = []) {
        $returnArray = [
            'html' => "",
            'code' => 400
        ];

        if (!empty($datos)) {
            $estatusSolicitud = $prioridad = '';
            if (isset($datos['data']['estatusSolicitud'])) {
                $estatusSolicitud = $datos['data']['estatusSolicitud'];
            }
            if (isset($datos['data']['prioridad'])) {
                $prioridad = $datos['data']['prioridad'];
            }
            $data = [
                'generalesAtiende' => $this->DBS->getGroupAtiendeServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['data']['tipo'], $estatusSolicitud, $prioridad, '', '', $datos['data']['estatus']),
                'generalesSucursal' => $this->DBS->getGroupSucursalesServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['data']['tipo'], $estatusSolicitud, $prioridad, '', '', $datos['data']['estatus']),
                'tipo' => ucwords($this->DBS->getTipoName($datos['data']['tipo'])),
                'estatus' => ucwords($this->DBS->getEstatusName($datos['data']['estatus'])),
                'lista' => $this->DBS->getListaServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['data']['tipo'], $datos['data']['estatus'], $estatusSolicitud, '', '', $prioridad)
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/DashboardFiltroEstatusS', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

    public function cargaUltimoFiltro(array $datos = []) {
        $returnArray = [
            'html' => "",
            'code' => 400
        ];

        if (!empty($datos)) {            
            $data = [
                'tipo' => isset($datos['data']['tipo']) ? ucwords($this->DBS->getTipoName($datos['data']['tipo'])) : '',
                'sucursal' => isset($datos['data']['sucursal']) ? ucwords($this->DBS->getSucursalName($datos['data']['sucursal'])) : '',
                'estatus' => isset($datos['data']['estatus']) ? ucwords($this->DBS->getEstatusName($datos['data']['estatus'])) : '',
                'atiende' => isset($datos['data']['atiende']) ? ucwords($this->DBS->getUsuarioName($datos['data']['atiende'])) : '',
                'lista' => $this->DBS->getListaServicios(
                        $datos['fechas']['Inicio'], 
                        $datos['fechas']['Fin'], 
                        isset($datos['data']['tipo']) ? $datos['data']['tipo'] : '', 
                        isset($datos['data']['estatus']) ? $datos['data']['estatus'] : '', 
                        isset($datos['data']['estatusSolicitud']) ? $datos['data']['estatusSolicitud'] : '', 
                        isset($datos['data']['sucursal']) ? $datos['data']['sucursal'] : '', 
                        isset($datos['data']['atiende']) ? $datos['data']['atiende'] : '',
                        isset($datos['data']['prioridad']) ? $datos['data']['prioridad'] : '')
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/DashboardUltimoFiltro', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

    public function cargaPanelByEstatusTipo(array $datos = []) {
        $returnArray = [
            'html' => "",
            'code' => 400
        ];

        if (!empty($datos)) {
            $data = [
                'estatusServicios' => $this->DBS->getGroupEstatusServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['ids']['tipo'], $datos['ids']['estatus']),
                'generalesSucursales' => $this->DBS->getGroupSucursalesServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['ids']['tipo'], $datos['ids']['estatus']),
                'generalesAtiende' => $this->DBS->getGroupAtiendeServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['ids']['tipo'], $datos['ids']['estatus']),
                'estatus' => ucwords($this->DBS->getEstatusName($datos['ids']['estatus'])),
                'tipo' => ucwords($this->DBS->getTipoName($datos['ids']['tipo'])),
                'lista' => $this->DBS->getListaServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['ids']['tipo'], '', $datos['ids']['estatus'])
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/DashboardFiltroEstatusTipo', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

    public function cargaPanelByPrioridadTipo(array $datos = []) {
        $returnArray = [
            'html' => "",
            'code' => 400
        ];

        if (!empty($datos)) {
            $data = [
                'estatusServicios' => $this->DBS->getGroupEstatusServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['ids']['tipo'], '', $datos['ids']['prioridad']),
                'generalesSucursales' => $this->DBS->getGroupSucursalesServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['ids']['tipo'], '', $datos['ids']['prioridad']),
                'generalesAtiende' => $this->DBS->getGroupAtiendeServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['ids']['tipo'], '', $datos['ids']['prioridad']),
                'prioridad' => ucwords($this->DBS->getPrioridadName($datos['ids']['prioridad'])),
                'tipo' => ucwords($this->DBS->getTipoName($datos['ids']['tipo'])),
                'lista' => $this->DBS->getListaServicios($datos['fechas']['Inicio'], $datos['fechas']['Fin'], $datos['ids']['tipo'], $datos['ids']['prioridad'], '')
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/DashboardFiltroPrioridadTipo', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

    public function exportarServiciosLogistica(array $fechas = null) {
//        ini_set('memory_limit', '2048M');
//        set_time_limit('1200');        

        $info = $this->dashboardGeneral->getServiciosAreaLogisticaExcel($fechas);

        /* Begin Hoja 1 */
        //Crea una hoja en la posición 0 y la nombra.
        $this->Excel->createSheet('Solicitudes y servicios', 0);
        //Selecciona la hoja creada y la marca como activa. Todas las modificaciones se harán en está hoja.
        $this->Excel->setActiveSheet(0);
        //Arreglo de los subtitulos de la tabla. LA posición es de izquierda a derecha.
        $arrayTitulos = [
            'Id Solicitud',
            'Fecha Solicitud',
            'Solicita',
            'Asunto Solicitud',
            'Descripcion Solicitud',
            'Ticket',
            'Servicio',
            'Tipo de Servicio',
            'Estatus Servicio',
            'Atiende',
            'Creacion del Servicio',
            'Inicio del Servicio',
            'Concluisión del Servicio',
            'Descripción'];
        //Envía el arreglo de los subtitulos a la hoja activa.
        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        //Arreglo con el ancho por columna. 
        $arrayWidth = [15, 22, 25, 30, 65, 10, 10, 15, 20, 20, 22, 22, 22, 65];
        //Envía y setea los anchos de las columnas definidos en el arreglo de los anchos por columna.
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        //Setea el titulo de la tabla. Envía la celda de inicio y la final para que se combinen.
        $this->Excel->setTableTitle("A1", "D1", "Servicios del Área", array('titulo'));
        //Arreglo de alineación por columna.
        $arrayAlign = ['center', 'center', '', '', 'justify', 'center', 'center', '', '', '', 'center', 'center', 'center', 'justify'];
        //Envía:
        //La letra donde comienza la tabla
        //El número de fila donde comenzará la tabla -1
        //El contenido en forma de arreglo
        //Boleano que define si la tabla llevará autofiltros o no
        //Arreglo con la alineación de las columnas.
        $this->Excel->setTableContent('A', 2, $info, true, $arrayAlign);
        /* End Hoja 1 */


        $info = $this->dashboardGeneral->getServiciosTraficosAreaLogisticaExcel($fechas);

        /* Begin Hoja 2 */
        //Crea una hoja en la posición 0 y la nombra.
        $this->Excel->createSheet('Servicios Logistica', 1);
        //Selecciona la hoja creada y la marca como activa. Todas las modificaciones se harán en está hoja.
        $this->Excel->setActiveSheet(1);
        //Arreglo de los subtitulos de la tabla. LA posición es de izquierda a derecha.
        $arrayTitulos = [
            'Ticket',
            'Servicio',
            'Estatus',
            'Tipo de Tráfico',
            'Tipo de Origen',
            'Origen',
            'Tipo de Destino',
            'Destino',
            'Tipo de Envio',
            'Paqueteria',
            'Fecha de Envio',
            'Guia',
            'Comentarios de Envio',
            'Fecha de Entrega / Recolección',
            'Recibe / Entrega',
            'Comentarios Recibe / Entrega'];
        //Envía el arreglo de los subtitulos a la hoja activa.
        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        //Arreglo con el ancho por columna. 
        $arrayWidth = [15, 15, 20, 20, 20, 30, 20, 30, 20, 20, 25, 20, 65, 30, 20, 65];
        //Envía y setea los anchos de las columnas definidos en el arreglo de los anchos por columna.
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        //Setea el titulo de la tabla. Envía la celda de inicio y la final para que se combinen.
        $this->Excel->setTableTitle("A1", "G1", "Servicios Logística ", array('titulo'));
        //Arreglo de alineación por columna.
        $arrayAlign = ['center', 'center', '', '', '', '', '', '', '', 'center', '', 'justify', 'center', '', 'justify'];
        //Envía:
        //La letra donde comienza la tabla
        //El número de fila donde comenzará la tabla -1
        //El contenido en forma de arreglo
        //Boleano que define si la tabla llevará autofiltros o no
        //Arreglo con la alineación de las columnas.
        $this->Excel->setTableContent('A', 2, $info, true, $arrayAlign);
        /* End Hoja 2 */


        $info = $this->dashboardGeneral->getEquiposTraficosExcel($fechas);

        /* Begin Hoja 3 */
        //Crea una hoja en la posición 0 y la nombra.
        $this->Excel->createSheet('Productos logística', 2);
        //Selecciona la hoja creada y la marca como activa. Todas las modificaciones se harán en está hoja.
        $this->Excel->setActiveSheet(2);
        //Arreglo de los subtitulos de la tabla. LA posición es de izquierda a derecha.
        $arrayTitulos = [
            'Ticket',
            'Servicio',
            'Estatus',
            'Tipo de Producto',
            'Producto',
            'Serie',
            'Cantidad',
            'Tipo de Tráfico',
            'Tipo de Origen',
            'Origen',
            'Tipo de Destino',
            'Destino',
            'Tipo de Envio',
            'Paqueteria',
            'Fecha de Envio',
            'Guia',
            'Fecha de Entrega / Recolección',
            'Recibe / Entrega'];
        //Envía el arreglo de los subtitulos a la hoja activa.
        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        //Arreglo con el ancho por columna. 
        $arrayWidth = [15, 15, 20, 20, 30, 15, 10, 20, 20, 30, 20, 30, 20, 20, 25, 20, 30, 20];
        //Envía y setea los anchos de las columnas definidos en el arreglo de los anchos por columna.
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        //Setea el titulo de la tabla. Envía la celda de inicio y la final para que se combinen.
        $this->Excel->setTableTitle("A1", "G1", "Productos Logística ", array('titulo'));
        //Arreglo de alineación por columna.
        $arrayAlign = ['center', 'center', '', '', '', '', 'center', '', '', '', '', '', '', 'center', '', 'center', ''];
        //Envía:
        //La letra donde comienza la tabla
        //El número de fila donde comenzará la tabla -1
        //El contenido en forma de arreglo
        //Boleano que define si la tabla llevará autofiltros o no
        //Arreglo con la alineación de las columnas.
        $this->Excel->setTableContent('A', 2, $info, true, $arrayAlign);
        /* End Hoja 3 */


        $time = date("ymd_H_i_s");
        $nombreArchivo = 'Reporte_Area_Logística_' . $time . '.xlsx';
        $nombreArchivo = trim($nombreArchivo);
        $ruta = 'storage/Archivos/Dashboard/Logistica/Reportes/' . $nombreArchivo;

        //Guarda la hoja envíandole la ruta y el nombre del archivo que se va a guardar.
        $this->Excel->saveFile($ruta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ruta];
    }

}
