<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

class Busqueda extends General {

    private $DBB;
    private $DBS;
    private $servicio;
    private $notas;
    private $fechas;
    private $Excel;
    private $InformacionServicios;
    private $SeguimientoPoliza;

    public function __construct() {
        parent::__construct();
        $this->DBB = \Modelos\Modelo_Busqueda::factory();
        $this->DBS = \Modelos\Modelo_EditarSolicitud::factory();
        $this->servicio = \Librerias\Generales\Servicio::factory();
        $this->notas = \Librerias\Generales\Notas::factory();
        $this->fechas = \Librerias\Generales\Dashboard::factory();
        $this->Excel = new \Librerias\Generales\CExcel();
        $this->InformacionServicios = \Librerias\WebServices\InformacionServicios::factory();
        $this->SeguimientoPoliza = \Librerias\Poliza\Seguimientos::factory();
        parent::getCI()->load->helper('date');
    }

    public function busquedaReporte(array $parametros) {
        if ($parametros['columnas'] == '') {
            $query = 'select 
            ts.Id as IdSolicitud,
            tst.Id as IdServicio,
            tst.Ticket,
            ts.Folio,
            regionCliente(cs.IdRegionCliente) as Region,
            sucursal(tst.IdSucursal) as Sucursal,
            estatus(ts.IdEstatus) as EstatusSolicitud,
            departamento(ts.IdDepartamento) as DepartamentoSolicitud,
            prioridad(ts.IdPrioridad) as PriodidadSolicitud,
            ts.FechaCreacion as FechaSolicitud,
            ts.FechaRevision as FechaRevisionSolicitud,
            ts.FechaConclusion as FechaCierreSolicitud,
            nombreUsuario(ts.Solicita) as Solicita,
            tsi.Asunto as AsuntoSolicitud,
            tsi.Descripcion as DescripcionSolicitud,            
            tipoServicio(tst.IdTipoServicio) as TipoServicio,
            estatus(tst.IdEstatus) as EstatusServicio,
            nombreUsuario(tst.Solicita) as GeneraServicio,
            nombreUsuario(tst.Atiende) as AtiendeServicio,
            tst.FechaCreacion as FechaServicio,
            tst.FechaInicio as FechaInicioServicio,
            tst.FechaConclusion as FechaConclusionServicio,
            tst.Descripcion as Servicio 
            from 
            t_solicitudes ts 
            left join t_solicitudes_internas tsi on ts.Id = tsi.IdSolicitud
            LEFT JOIN t_servicios_ticket tst on ts.Id = tst.IdSolicitud  
            left join cat_v3_sucursales cs on tst.IdSucursal = cs.Id where 1 = 1 ';

            $htmlColumnas = ''
                    . '<th class="all">Solicitud</th>'
                    . '<th class="never">IdServicio</th>'
                    . '<th class="all">Ticket</th>'
                    . '<th class="all">Folio</th>'
                    . '<th class="all">Zona / Región</th>'
                    . '<th class="all">Sucursal</th>'
                    . '<th class="all">Estatus de Solicitud</th>'
                    . '<th class="all">Departamento de Solicitud</th>'
                    . '<th class="all">Prioridad de Solicitud</th>'
                    . '<th class="all">Fecha de Solicitud</th>'
                    . '<th class="all">Fecha de Revisión de Solicitud</th>'
                    . '<th class="all">Fecha de Cierre de Solicitud</th>'
                    . '<th class="all">Solicita</th>'
                    . '<th class="all">Asunto de Solicitud</th>'
                    . '<th class="all">Descripcion de Solicitud</th>'
                    . '<th class="all">Tipo de Servicio</th>'
                    . '<th class="all">Estatus del Servicio</th>'
                    . '<th class="all">Personal que Genera el Servicio</th>'
                    . '<th class="all">Atiende el Servicio</th>'
                    . '<th class="all">Fecha del Servicio</th>'
                    . '<th class="all">Fecha de Inicio del Servicio</th>'
                    . '<th class="all">Fecha de Cierre del Servicio</th>'
                    . '<th class="all">Descripción del Servicio</th>';
        } else {
            $htmlColumnas = '';
            $query = 'select ';
            $query .= 'ts.Id as IdSolicitud, ';
            $htmlColumnas .= '<th class="all">Solicitud</th>';
            $query .= 'tst.Id as IdServicio, ';
            $htmlColumnas .= '<th class="never">IdServicio</th>';
            $query .= (in_array("ts.Ticket", $parametros['columnas'])) ? 'ts.Ticket, ' : '';
            $htmlColumnas .= (in_array("ts.Ticket", $parametros['columnas'])) ? '<th class="all">Ticket</th>' : '';
            $query .= (in_array("ts.Folio", $parametros['columnas'])) ? 'ts.Folio, ' : '';
            $htmlColumnas .= (in_array("ts.Folio", $parametros['columnas'])) ? '<th class="all">Folio</th>' : '';
            $query .= (in_array("cs.IdRegionCliente", $parametros['columnas'])) ? 'regionCliente(cs.IdRegionCliente) as Region, ' : '';
            $htmlColumnas .= (in_array("cs.IdRegionCliente", $parametros['columnas'])) ? '<th class="all">Zona / Región</th>' : '';
            $query .= (in_array("tst.IdSucursal", $parametros['columnas'])) ? 'sucursal(tst.IdSucursal) as Sucursal, ' : '';
            $htmlColumnas .= (in_array("tst.IdSucursal", $parametros['columnas'])) ? '<th class="all">Sucursal</th>' : '';
            $query .= (in_array("ts.IdEstatus", $parametros['columnas'])) ? 'estatus(ts.IdEstatus) as EstatusSolicitud, ' : '';
            $htmlColumnas .= (in_array("ts.IdEstatus", $parametros['columnas'])) ? '<th class="all">Estatus de Solicitud</th>' : '';
            $query .= (in_array("ts.IdDepartamento", $parametros['columnas'])) ? 'departamento(ts.IdDepartamento) as DepartamentoSolicitud, ' : '';
            $htmlColumnas .= (in_array("ts.IdDepartamento", $parametros['columnas'])) ? '<th class="all">Departamento de Solicitud</th>' : '';
            $query .= (in_array("ts.IdPrioridad", $parametros['columnas'])) ? 'prioridad(ts.IdPrioridad) as PriodidadSolicitud, ' : '';
            $htmlColumnas .= (in_array("ts.IdPrioridad", $parametros['columnas'])) ? '<th class="all">Prioridad de Solicitud</th>' : '';
            $query .= (in_array("ts.FechaCreacion", $parametros['columnas'])) ? 'ts.FechaCreacion as FechaSolicitud, ' : '';
            $htmlColumnas .= (in_array("ts.FechaCreacion", $parametros['columnas'])) ? '<th class="all">Fecha de Solicitud</th>' : '';
            $query .= (in_array("ts.FechaRevision", $parametros['columnas'])) ? 'ts.FechaRevision as FechaRevisionSolicitud, ' : '';
            $htmlColumnas .= (in_array("ts.FechaRevision", $parametros['columnas'])) ? '<th class="all">Fecha de Revisión de Solicitud</th>' : '';
            $query .= (in_array("ts.FechaConclusion", $parametros['columnas'])) ? 'ts.FechaConclusion as FechaCierreSolicitud, ' : '';
            $htmlColumnas .= (in_array("ts.FechaConclusion", $parametros['columnas'])) ? '<th class="all">Fecha de Cierre de Solicitud</th>' : '';
            $query .= (in_array("ts.Solicita", $parametros['columnas'])) ? 'nombreUsuario(ts.Solicita) as Solicita, ' : '';
            $htmlColumnas .= (in_array("ts.Solicita", $parametros['columnas'])) ? '<th class="all">Solicita</th>' : '';
            $query .= (in_array("tsi.Asunto", $parametros['columnas'])) ? 'tsi.Asunto as AsuntoSolicitud, ' : '';
            $htmlColumnas .= (in_array("tsi.Asunto", $parametros['columnas'])) ? '<th class="all">Asunto de Solicitud</th>' : '';
            $query .= (in_array("tsi.Descripcion", $parametros['columnas'])) ? 'tsi.Descripcion as DescripcionSolicitud, ' : '';
            $htmlColumnas .= (in_array("tsi.Descripcion", $parametros['columnas'])) ? '<th class="all">Descripcion de Solicitud</th>' : '';
            $query .= (in_array("tst.IdTipoServicio", $parametros['columnas'])) ? 'tipoServicio(tst.IdTipoServicio) as TipoServicio, ' : '';
            $htmlColumnas .= (in_array("tst.IdTipoServicio", $parametros['columnas'])) ? '<th class="all">Tipo de Servicio</th>' : '';
            $query .= (in_array("tst.IdEstatus", $parametros['columnas'])) ? 'estatus(tst.IdEstatus) as EstatusServicio, ' : '';
            $htmlColumnas .= (in_array("tst.IdEstatus", $parametros['columnas'])) ? '<th class="all">Estatus del Servicio</th>' : '';
            $query .= (in_array("tst.Solicita", $parametros['columnas'])) ? 'nombreUsuario(tst.Solicita) as GeneraServicio, ' : '';
            $htmlColumnas .= (in_array("tst.Solicita", $parametros['columnas'])) ? '<th class="all">Personal que Genera el Servicio</th>' : '';
            $query .= (in_array("tst.Atiende", $parametros['columnas'])) ? 'nombreUsuario(tst.Atiende) as AtiendeServicio, ' : '';
            $htmlColumnas .= (in_array("tst.Atiende", $parametros['columnas'])) ? '<th class="all">Atiende el Servicio</th>' : '';
            $query .= (in_array("tst.FechaCreacion", $parametros['columnas'])) ? 'tst.FechaCreacion as FechaServicio, ' : '';
            $htmlColumnas .= (in_array("tst.FechaCreacion", $parametros['columnas'])) ? '<th class="all">Fecha del Servicio</th>' : '';
            $query .= (in_array("tst.FechaInicio", $parametros['columnas'])) ? 'tst.FechaInicio as FechaInicioServicio, ' : '';
            $htmlColumnas .= (in_array("tst.FechaInicio", $parametros['columnas'])) ? '<th class="all">Fecha de Inicio del Servicio</th>' : '';
            $query .= (in_array("tst.FechaConclusion", $parametros['columnas'])) ? 'tst.FechaConclusion as FechaConclusionServicio, ' : '';
            $htmlColumnas .= (in_array("tst.FechaConclusion", $parametros['columnas'])) ? '<th class="all">Fecha de Cierre del Servicio</th>' : '';
            $query .= (in_array("tst.Descripcion", $parametros['columnas'])) ? 'tst.Descripcion as Servicio, ' : '';
            $htmlColumnas .= (in_array("tst.Descripcion", $parametros['columnas'])) ? '<th class="all">Descripción del Servicio</th>' : '';

            $query = substr($query, 0, -2);
            $query .= ' from 
            t_solicitudes ts 
            left join t_solicitudes_internas tsi on ts.Id = tsi.IdSolicitud
            LEFT JOIN t_servicios_ticket tst on ts.Id = tst.IdSolicitud
            left join cat_v3_sucursales cs on tst.IdSucursal = cs.Id where 1 = 1 ';
        }

        if ($parametros['filtroFecha'] !== '') {
            if ($parametros['tipoFiltroFecha'] == 'rango') {
                if ($parametros['desde'] !== '' && $parametros['hasta'] !== '') {
                    $query .= " and " . $parametros['filtroFecha'] . " between '" . $parametros['desde'] . " 00:00:00' and '" . $parametros['hasta'] . " 23:59:59' ";
                }
            } else {
                $fechas = $this->fechas->getFiltrosFecha(['id' => $parametros['durante']])[0];
                $desde = substr($fechas['Inicio'], 6, 4) . '-' . substr($fechas['Inicio'], 3, 2) . '-' . substr($fechas['Inicio'], 0, 2);
                $hasta = substr($fechas['Fin'], 6, 4) . '-' . substr($fechas['Fin'], 3, 2) . '-' . substr($fechas['Fin'], 0, 2);
                $query .= " and " . $parametros['filtroFecha'] . " between '" . $desde . " 00:00:00' and '" . $hasta . " 23:59:59' ";
            }
        }

        if (isset($parametros['avanzados'])) {
            foreach ($parametros['avanzados'] as $key => $value) {
                $operador = "";
                switch ($value['criterio']) {
                    case 'es':
                        $operador = " in ";
                        break;
                    case 'noes':
                        $operador = " not in ";
                        break;
                    case 'contiene':
                        $operador = " like ";
                        break;
                }

                switch ($value['tipoCampo']) {
                    case 'tag':
                        $query .= " and " . $value['campo'] . $operador . "('" . implode("','", $value['valor']) . "') ";
                        break;
                    case 'cat':
                        $query .= " and " . $value['campo'] . $operador . "('" . implode("','", $value['valor']) . "') ";
                        break;
                    case 'text':
                        $query .= " and " . $value['campo'] . $operador . "'%" . $value['valor'] . "%' ";
                        break;
                }
            }
        }

        $resultado = $this->DBB->busquedaReporte($query);
        $htmlReturn = ''
                . '<table '
                . ' id="data-table-busqueda-reporte" '
                . ' class="table table-hover table-striped table-bordered no-wrap" '
                . ' style="cursor:pointer; width" '
                . ' >'
                . ' <thead>'
                . ' ' . $htmlColumnas . ''
                . ' </thead>'
                . ' <tbody>';

        foreach ($resultado as $key => $value) {
            $htmlReturn .= '<tr>';
            if ($parametros['columnas'] == '') {
                $htmlReturn .= '<td>' . $value['IdSolicitud'] . '</td>';
                $htmlReturn .= '<td>' . $value['IdServicio'] . '</td>';
                $htmlReturn .= '<td>' . $value['Ticket'] . '</td>';
                $htmlReturn .= '<td>' . $value['Folio'] . '</td>';
                $htmlReturn .= '<td>' . $value['Region'] . '</td>';
                $htmlReturn .= '<td>' . $value['Sucursal'] . '</td>';
                $htmlReturn .= '<td>' . $value['EstatusSolicitud'] . '</td>';
                $htmlReturn .= '<td>' . $value['DepartamentoSolicitud'] . '</td>';
                $htmlReturn .= '<td>' . $value['PriodidadSolicitud'] . '</td>';
                $htmlReturn .= '<td>' . $value['FechaSolicitud'] . '</td>';
                $htmlReturn .= '<td>' . $value['FechaRevisionSolicitud'] . '</td>';
                $htmlReturn .= '<td>' . $value['FechaCierreSolicitud'] . '</td>';
                $htmlReturn .= '<td>' . $value['Solicita'] . '</td>';
                $htmlReturn .= '<td>' . $value['AsuntoSolicitud'] . '</td>';
                $htmlReturn .= '<td>' . $value['DescripcionSolicitud'] . '</td>';
                $htmlReturn .= '<td>' . $value['TipoServicio'] . '</td>';
                $htmlReturn .= '<td>' . $value['EstatusServicio'] . '</td>';
                $htmlReturn .= '<td>' . $value['GeneraServicio'] . '</td>';
                $htmlReturn .= '<td>' . $value['AtiendeServicio'] . '</td>';
                $htmlReturn .= '<td>' . $value['FechaServicio'] . '</td>';
                $htmlReturn .= '<td>' . $value['FechaInicioServicio'] . '</td>';
                $htmlReturn .= '<td>' . $value['FechaConclusionServicio'] . '</td>';
                $htmlReturn .= '<td>' . $value['Servicio'] . '</td>';
            } else {
                $htmlReturn .= '<td>' . $value['IdSolicitud'] . '</td>';
                $htmlReturn .= '<td>' . $value['IdServicio'] . '</td>';
                $htmlReturn .= (in_array("ts.Ticket", $parametros['columnas'])) ? '<td>' . $value['Ticket'] . '</td>' : '';
                $htmlReturn .= (in_array("ts.Folio", $parametros['columnas'])) ? '<td>' . $value['Folio'] . '</td>' : '';
                $htmlReturn .= (in_array("cs.IdRegionCliente", $parametros['columnas'])) ? '<td>' . $value['Region'] . '</td>' : '';
                $htmlReturn .= (in_array("tst.IdSucursal", $parametros['columnas'])) ? '<td>' . $value['Sucursal'] . '</td>' : '';
                $htmlReturn .= (in_array("ts.IdEstatus", $parametros['columnas'])) ? '<td>' . $value['EstatusSolicitud'] . '</td>' : '';
                $htmlReturn .= (in_array("ts.IdDepartamento", $parametros['columnas'])) ? '<td>' . $value['DepartamentoSolicitud'] . '</td>' : '';
                $htmlReturn .= (in_array("ts.IdPrioridad", $parametros['columnas'])) ? '<td>' . $value['PriodidadSolicitud'] . '</td>' : '';
                $htmlReturn .= (in_array("ts.FechaCreacion", $parametros['columnas'])) ? '<td>' . $value['FechaSolicitud'] . '</td>' : '';
                $htmlReturn .= (in_array("ts.FechaRevision", $parametros['columnas'])) ? '<td>' . $value['FechaRevisionSolicitud'] . '</td>' : '';
                $htmlReturn .= (in_array("ts.FechaConclusion", $parametros['columnas'])) ? '<td>' . $value['FechaCierreSolicitud'] . '</td>' : '';
                $htmlReturn .= (in_array("ts.Solicita", $parametros['columnas'])) ? '<td>' . $value['Solicita'] . '</td>' : '';
                $htmlReturn .= (in_array("tsi.Asunto", $parametros['columnas'])) ? '<td>' . $value['AsuntoSolicitud'] . '</td>' : '';
                $htmlReturn .= (in_array("tsi.Descripcion", $parametros['columnas'])) ? '<td>' . $value['DescripcionSolicitud'] . '</td>' : '';
                $htmlReturn .= (in_array("tst.IdTipoServicio", $parametros['columnas'])) ? '<td>' . $value['TipoServicio'] . '</td>' : '';
                $htmlReturn .= (in_array("tst.IdEstatus", $parametros['columnas'])) ? '<td>' . $value['EstatusServicio'] . '</td>' : '';
                $htmlReturn .= (in_array("tst.Solicita", $parametros['columnas'])) ? '<td>' . $value['GeneraServicio'] . '</td>' : '';
                $htmlReturn .= (in_array("tst.Atiende", $parametros['columnas'])) ? '<td>' . $value['AtiendeServicio'] . '</td>' : '';
                $htmlReturn .= (in_array("tst.FechaCreacion", $parametros['columnas'])) ? '<td>' . $value['FechaServicio'] . '</td>' : '';
                $htmlReturn .= (in_array("tst.FechaInicio", $parametros['columnas'])) ? '<td>' . $value['FechaInicioServicio'] . '</td>' : '';
                $htmlReturn .= (in_array("tst.FechaConclusion", $parametros['columnas'])) ? '<td>' . $value['FechaConclusionServicio'] . '</td>' : '';
                $htmlReturn .= (in_array("tst.Descripcion", $parametros['columnas'])) ? '<td>' . $value['Servicio'] . '</td>' : '';
            }
            $htmlReturn .= '</tr>';
        }
        $htmlReturn .= '</tbody></table>';

        return ['tabla' => $htmlReturn];
    }

    public function exportarExcel(array $datos = null) {
        ini_set('memory_limit', '2048M');
        set_time_limit('1200');
        if ($datos['columnas'] == '') {
            $arrayTitulos = [
                'Solicitud',
                'Id Servicio',
                'Ticket',
                'Folio',
                'Region',
                'Sucursal',
                'Estatus de Solicitud',
                'Departamento de Solicitud',
                'Prioridad de Solicitud',
                'Fecha de Solicitud',
                'Fecha de Revisión de Solicitud',
                'Fecha de Cierre de Solicitud',
                'Solicita',
                'Asunto de Solicitud',
                'Descripcion de Solicitud',
                'Tipo de Servicio',
                'Estatus del Servicio',
                'Personal que Genera el Servicio',
                'Atiende el Servicio',
                'Fecha del Servicio',
                'Fecha de Inicio del Servicio',
                'Fecha de Cierre del Servicio',
                'Descripción del Servicio',];
            $arrayWidth = [15, 15, 15, 15, 35, 35, 20, 35, 15, 20, 20, 20, 35, 35, 65, 35, 20, 35, 35, 20, 20, 20, 65];
            $arrayAlign = ['center', 'center', 'center', 'center', '', '', '', '', '', 'center', 'center', 'center', '', '', 'justify', '', '', '', '', 'center', 'center', 'center', 'justify'];
        } else {
            $arrayTitulos = [];
            $arrayWidth = [];
            $arrayAlign = [];
            array_push($arrayTitulos, 'Solicitud');
            array_push($arrayWidth, 15);
            array_push($arrayAlign, 'center');

            array_push($arrayTitulos, 'Id Servicio');
            array_push($arrayWidth, 15);
            array_push($arrayAlign, 'center');

            if (in_array("ts.Ticket", $datos['columnas'])) {
                array_push($arrayTitulos, 'Ticket');
                array_push($arrayWidth, 15);
                array_push($arrayAlign, 'center');
            }
            if (in_array("ts.Folio", $datos['columnas'])) {
                array_push($arrayTitulos, 'Folio');
                array_push($arrayWidth, 15);
                array_push($arrayAlign, 'center');
            }
            if (in_array("cs.IdRegionCliente", $datos['columnas'])) {
                array_push($arrayTitulos, 'Region');
                array_push($arrayWidth, 35);
                array_push($arrayAlign, '');
            }
            if (in_array("tst.IdSucursal", $datos['columnas'])) {
                array_push($arrayTitulos, 'Sucursal');
                array_push($arrayWidth, 35);
                array_push($arrayAlign, '');
            }
            if (in_array("ts.IdEstatus", $datos['columnas'])) {
                array_push($arrayTitulos, 'Estatus de Solicitud');
                array_push($arrayWidth, 20);
                array_push($arrayAlign, '');
            }
            if (in_array("ts.IdDepartamento", $datos['columnas'])) {
                array_push($arrayTitulos, 'Departamento de Solicitud');
                array_push($arrayWidth, 35);
                array_push($arrayAlign, '');
            }
            if (in_array("ts.IdPrioridad", $datos['columnas'])) {
                array_push($arrayTitulos, 'Prioridad de Solicitud');
                array_push($arrayAlign, '');
            }
            if (in_array("ts.FechaCreacion", $datos['columnas'])) {
                array_push($arrayTitulos, 'Fecha de Solicitud');
                array_push($arrayWidth, 20);
                array_push($arrayAlign, 'center');
            }
            if (in_array("ts.FechaRevision", $datos['columnas'])) {
                array_push($arrayTitulos, 'Fecha de Revisión de Solicitud');
                array_push($arrayWidth, 20);
                array_push($arrayAlign, 'center');
            }
            if (in_array("ts.FechaConclusion", $datos['columnas'])) {
                array_push($arrayTitulos, 'Fecha de Cierre de Solicitud');
                array_push($arrayWidth, 20);
                array_push($arrayAlign, 'center');
            }
            if (in_array("ts.Solicita", $datos['columnas'])) {
                array_push($arrayTitulos, 'Solicita');
                array_push($arrayWidth, 35);
                array_push($arrayAlign, '');
            }
            if (in_array("tsi.Asunto", $datos['columnas'])) {
                array_push($arrayTitulos, 'Asunto de Solicitud');
                array_push($arrayWidth, 35);
                array_push($arrayAlign, '');
            }
            if (in_array("tsi.Descripcion", $datos['columnas'])) {
                array_push($arrayTitulos, 'Descripcion de Solicitud');
                array_push($arrayWidth, 65);
                array_push($arrayAlign, 'justify');
            }
            if (in_array("tst.IdTipoServicio", $datos['columnas'])) {
                array_push($arrayTitulos, 'Tipo de Servicio');
                array_push($arrayWidth, 35);
                array_push($arrayAlign, '');
            }
            if (in_array("tst.IdEstatus", $datos['columnas'])) {
                array_push($arrayTitulos, 'Estatus del Servicio');
                array_push($arrayWidth, 20);
                array_push($arrayAlign, '');
            }
            if (in_array("tst.Solicita", $datos['columnas'])) {
                array_push($arrayTitulos, 'Personal que Genera el Servicio');
                array_push($arrayWidth, 35);
                array_push($arrayAlign, '');
            }
            if (in_array("tst.Atiende", $datos['columnas'])) {
                array_push($arrayTitulos, 'Atiende el Servicio');
                array_push($arrayWidth, 35);
                array_push($arrayAlign, '');
            }
            if (in_array("tst.FechaCreacion", $datos['columnas'])) {
                array_push($arrayTitulos, 'Fecha del Servicio');
                array_push($arrayWidth, 20);
                array_push($arrayAlign, 'center');
            }
            if (in_array("tst.FechaInicio", $datos['columnas'])) {
                array_push($arrayTitulos, 'Fecha de Inicio del Servicio');
                array_push($arrayWidth, 20);
                array_push($arrayAlign, 'center');
            }
            if (in_array("tst.FechaConclusion", $datos['columnas'])) {
                array_push($arrayTitulos, 'Fecha de Cierre del Servicio');
                array_push($arrayWidth, 20);
                array_push($arrayAlign, 'center');
            }
            if (in_array("tst.Descripcion", $datos['columnas'])) {
                array_push($arrayTitulos, 'Descripción del Servicio');
                array_push($arrayWidth, 65);
                array_push($arrayAlign, 'justify');
            }
        }
        $info = $datos['info'];

        /* Begin Hoja 1 */
        //Crea una hoja en la posición 0 y la nombra.
        $this->Excel->createSheet('Inventario', 0);
        //Selecciona la hoja creada y la marca como activa. Todas las modificaciones se harán en está hoja.
        $this->Excel->setActiveSheet(0);
        //Arreglo de los subtitulos de la tabla. LA posición es de izquierda a derecha.
        //$arrayTitulos
        //Envía el arreglo de los subtitulos a la hoja activa.
        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        //Arreglo con el ancho por columna. 
//        $arrayWidth = [20, 35, 20, 15, 15];
        //Envía y setea los anchos de las columnas definidos en el arreglo de los anchos por columna.
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        //Setea el titulo de la tabla. Envía la celda de inicio y la final para que se combinen.
        $this->Excel->setTableTitle("A1", "F1", "Resultado de Búsqueda", array('titulo'));
        //Arreglo de alineación por columna.
        //$arrayAlign = ['', '', '', '', 'center'];
        //Envía:
        //La letra donde comienza la tabla
        //El número de fila donde comenzará la tabla -1
        //El contenido en forma de arreglo
        //Boleano que define si la tabla llevará autofiltros o no
        //Arreglo con la alineación de las columnas.
        $this->Excel->setTableContent('A', 2, $info, true, $arrayAlign);
        /* End Hoja 1 */

        $time = date("ymd_H_i_s");
        $nombreArchivo = 'Busqueda_' . $time . '.xlsx';
        $nombreArchivo = trim($nombreArchivo);
        $ruta = 'storage/Archivos/SAEReports/' . $nombreArchivo;

        //Guarda la hoja envíandole la ruta y el nombre del archivo que se va a guardar.
        $this->Excel->saveFile($ruta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ruta];
    }

    public function detalles(array $datos = null) {
        $datosSolicitud = $this->DBB->getGeneralesSolicitud($datos['datos'][0]);
        if ($datos['datos'][1] !== '') {
            $datosConversacion = $this->servicio->getNotasByServicio($datos['datos'][1]);
            $datosHistorial = $this->servicio->getHistorialServicio($datos['datos'][1]);
        } else {
            $datosConversacion = "";
            $datosHistorial = "";
        }

        $data = [
            'detalles' => $this->DBS->getDetalleSolicitud($datosSolicitud[0]['Id'])[0],
            'datos' => $datosSolicitud[0]
        ];

        $arrayReturn = [
            'solicitud' => parent::getCI()->load->view("Generales/Modal/detallesSolicitud", $data, TRUE),
            'servicio' => $this->detallesServicio($datos['datos'][1]),
            'conversacion' => parent::getCI()->load->view("Generales/Modal/conversacionServicio", ['datos' => $datosConversacion], TRUE),
            'historial' => parent::getCI()->load->view("Generales/Modal/historialServicio", ['datos' => $datosHistorial], TRUE)
        ];
        return $arrayReturn;
    }

    public function getHistorialServicio(string $servicio = '') {
        $datosHistorial = $this->servicio->getHistorialServicio($servicio);
        return parent::getCI()->load->view("Generales/Modal/historialServicio", ['datos' => $datosHistorial], TRUE);
    }

    public function getConversacionServicio(string $servicio = '') {
        $datosConversacion = $this->servicio->getNotasByServicio($servicio);
        return parent::getCI()->load->view("Generales/Modal/conversacionServicio", ['datos' => $datosConversacion], TRUE);
    }

    public function detallesServicio(string $servicio = null) {
        $tipoServicio = ($servicio === null) ? ['Tipo' => 0, 'Seguimiento' => 0] : $this->getTipoServicio($servicio);
        if (in_array($tipoServicio['Seguimiento'], [0, '0'])) {
            $datosServicio = $this->DBB->getGeneralesServicioGeneral($servicio);
            if (count($datosServicio) > 0) {
                return parent::getCI()->load->view("Generales/Modal/detallesServicio", ['datos' => $datosServicio[0]], TRUE);
            } else {
                return '<div class="row"><div class="col-md-12 col-sm-12 col-xs-12"><pre>Al parecer esta solicitud no tiene servicios registrados.</pre></div></div>';
            }
        } else {
            switch ($tipoServicio['Tipo']) {
                /* Mantenimientos Preventivos de Póliza */
                case '12': case 12:
                    $data = [
                        /* Datos generales del mantenimiento */
                        'datos' => $this->DBB->getGeneralesServicio12($servicio)[0],
                        /* Antes y después del mantenimiento */
                        'ad' => $this->DBB->getAntesDespues12($servicio),
                        /* Problemas por equipo del mantenimiento */
                        'pe' => $this->DBB->getProblemasEquipo12($servicio),
                        /* Equipo Faltante por mantenimiento */
                        'ef' => $this->DBB->getEquipoFaltante12($servicio),
                        /* Problemas adicionales del mantenimiento */
                        'pa' => $this->DBB->getProblemasAdicionales12($servicio)
                    ];
                    return parent::getCI()->load->view("Generales/Modal/detallesServicio_12", $data, TRUE);
                    break;
                case '11': case 11:
                    $data = [
                        /* Datos generales del censo */
                        'datos' => $this->DBB->getGeneralesServicio11($servicio)[0],
                        /* Detalles del censo */
                        'detalles' => $this->DBB->getDetalllesServicio11($servicio),
                        'diferencias' => $this->SeguimientoPoliza->cargaDiferenciasCenso(['servicio' => $servicio,'mostrarCenso' => true])
                    ];
                    return parent::getCI()->load->view("Generales/Modal/detallesServicio_11", $data, TRUE);
                    break;
                case '27': case 27:
                    $datosServicio = $this->DBB->getServicioDiagnostico($servicio);
                    if (count($datosServicio) > 0 || empty($datosServicio)) {
                        $data = [
                            'datos' => $this->DBB->getGeneralesServicioGeneralCompleto($servicio)[0],
                            'datosCorrectivo' => $this->DBB->getGeneralesServicio20($servicio)[0],
                            'diagnosticoEquipo' => $this->DBB->getDiagnosticoEquipo20($servicio),
                            'tipoProblema' => $this->DBB->getTipoProblema20($servicio)[0]['IdTipoProblema'],
                            'problemasServicio' => $this->DBB->getProblemaServicio20($servicio),
                            'verificarEnvioEntrega' => $this->DBB->getVerificarEnvioEntrega20($servicio),
                            'envioEntrega' => $this->DBB->consultaEntregaEnvio($servicio),
                            'correctivoSoluciones' => $this->DBB->getCorrectivosSoluciones($servicio),
                        ];
                        if (!empty($data['diagnosticoEquipo'])) {
                            $bitacoraObservaciones = $this->InformacionServicios->getHistorialReporteEnFalso($servicio);
                            $data['diagnosticoEquipo'][0]['BitacoraObservaciones'] = $bitacoraObservaciones;
                        }
                        return parent::getCI()->load->view("Generales/Modal/detallesServicio_20", $data, TRUE);
                    } else {
                        return parent::getCI()->load->view("Generales/Modal/detallesServicio", ['datos' => $datosServicio[0]], TRUE);
                    }
                    break;
                case '20': case 20:
                    $data = [
                        /* Datos generales del servicio */
                        'datos' => $this->DBB->getGeneralesServicioGeneralCompleto($servicio)[0],
                        /* Datos generales del correctivo */
                        'datosCorrectivo' => $this->DBB->getGeneralesServicio20($servicio)[0],
                        /* Diagnostico del Equipo */
                        'diagnosticoEquipo' => $this->DBB->getDiagnosticoEquipo20($servicio),
                        /* Tipos de Problema del correctivo */
                        'tipoProblema' => $this->DBB->getTipoProblema20($servicio)[0]['IdTipoProblema'],
                        /* Problemas del servicio del correctivo */
                        'problemasServicio' => $this->DBB->getProblemaServicio20($servicio),
                        /* Dato para saber si entrega o equipo */
                        'verificarEnvioEntrega' => $this->DBB->getVerificarEnvioEntrega20($servicio),
                        /* Datos entrega o equipo */
                        'envioEntrega' => $this->DBB->consultaEntregaEnvio($servicio),
                        /* Datos solciones del servicio correctivo */
                        'correctivoSoluciones' => $this->DBB->getCorrectivosSoluciones($servicio),
                    ];
                    
                    if ($data['diagnosticoEquipo'][0]['IdTipoDiagnostico']) {
                        $bitacoraObservaciones = $this->InformacionServicios->getHistorialReporteEnFalso($servicio);
                        $data['diagnosticoEquipo'][0]['BitacoraObservaciones'] = $bitacoraObservaciones;
                    }

                    return parent::getCI()->load->view("Generales/Modal/detallesServicio_20", $data, TRUE);
                    break;
                case '5': case 5:
                    $datosTrafico = $this->DBB->getGeneralesServicio5($servicio);
                    $data = [
                        /* Datos generales del tráfico */
                        'datos' => $datosTrafico[0],
                        /* Detalles de items del tráfico */
                        'items' => $this->DBB->getItemsServicio5($servicio),
                        /* Detalles del envío. En caso de no ser envío, este parámetro no retorna nada */
                        'envio' => $this->DBB->getEnvioServicio5($servicio),
                        'htmlDocumentacion' => $this->getEvidenciasTrafico($datosTrafico[0]['IdTipoTrafico'], $servicio)
                    ];
                    return parent::getCI()->load->view("Generales/Modal/detallesServicio_5", $data, TRUE);
                    break;
            }
        }
    }

    public function getEvidenciasTrafico(string $tipoTrafico, string $servicio) {
        $htmlDocumentacion = '';
        switch ($tipoTrafico) {
            case 1:
                $documentacion = $this->servicio->getDocumentacionEnvio($servicio);
                $htmlArchivos = '';
                $fechaEnvio = $fechaEntrega = $recibe = $comentariosEntrega = 'Sin Información';
                if (array_key_exists(0, $documentacion)) {
                    $fechaEnvio = ($documentacion[0]['FechaEnvio'] !== '') ? strftime('%A %e de %B, %G ', strtotime($documentacion[0]['FechaEnvio'])) . date("h:ma", strtotime($documentacion[0]['FechaEnvio'])) : 'Sin información';
                    $fechaEntrega = ($documentacion[0]['FechaEntrega'] !== '') ? strftime('%A %e de %B, %G ', strtotime($documentacion[0]['FechaEntrega'])) . date("h:ma", strtotime($documentacion[0]['FechaEntrega'])) : 'Sin información';
                    $recibe = ($documentacion[0]['Recibe'] !== '') ? $documentacion[0]['Recibe'] : 'Sin Información';
                    $comentariosEntrega = ($documentacion[0]['ComentariosEntrega'] !== '') ? $documentacion[0]['ComentariosEntrega'] : 'Sin Información';

                    if ($documentacion[0]['EvidenciaEntrega'] !== '' && $documentacion[0]['EvidenciaEntrega'] !== NULL) {
                        $htmlArchivos .= '';
                        $archivos = explode(",", $documentacion[0]['EvidenciaEntrega']);
                        foreach ($archivos as $k => $v) {
                            $pathInfo = pathinfo($v);
                            $src = $this->servicio->getSrcByPath($pathInfo, $v);
                            $htmlArchivos .= ''
                                    . '<div class="evidencia">'
                                    . ' <a class="m-l-5 m-r-5" href="' . $v . '" data-lightbox="image-entrega-' . $servicio . '" data-title="' . $pathInfo['basename'] . '">'
                                    . '     <img src="' . $src . '" style="max-height:115px !important;" alt="' . $pathInfo['basename'] . '"  />'
                                    . '     <p class="m-t-0">' . $pathInfo['basename'] . '</p>'
                                    . ' </a>'
                                    . '</div>';
                        }
                    }

                    $htmlDocumentacion .= '
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <h5 class="f-w-700">Fecha y Hora de Envío</h5>
                                    <pre>' . $fechaEnvio . '</pre>
                                </div>
                            </div>';

                    if (in_array($documentacion[0]['IdTipoEnvio'], [2, 3, '2', '3'])) {
                        $paqueteria = ($documentacion[0]['Paqueteria'] !== '') ? $documentacion[0]['Paqueteria'] : 'Sin Información';
                        $guia = ($documentacion[0]['Guia'] !== '') ? $documentacion[0]['Guia'] : 'Sin Información';
                        $comentariosEnvio = ($documentacion[0]['ComentariosEnvio'] !== '') ? $documentacion[0]['ComentariosEnvio'] : 'Sin Información';
                        $htmlArchivosE = '';
                        if ($documentacion[0]['EvidenciaEnvio'] !== '' && $documentacion[0]['EvidenciaEnvio'] !== NULL) {
                            $htmlArchivos .= '';
                            $archivos = explode(",", $documentacion[0]['EvidenciaEnvio']);
                            foreach ($archivos as $k => $v) {
                                $pathInfo = pathinfo($v);
                                $src = $this->getSrcByPath($pathInfo, $v);
                                $htmlArchivosE .= ''
                                        . '<div class="evidencia">'
                                        . ' <a class="m-l-5 m-r-5" href="' . $v . '" data-lightbox="image-envio-' . $servicio . '" data-title="' . $pathInfo['basename'] . '">'
                                        . '     <img src="' . $src . '" style="max-height:115px !important;" alt="' . $pathInfo['basename'] . '"  />'
                                        . '     <p class="m-t-0">' . $pathInfo['basename'] . '</p>'
                                        . ' </a>'
                                        . '</div>';
                            }
                        } else {
                            $htmlArchivosE .= ''
                                    . '<h5>Sin Información</h5>';
                        }
                        $htmlDocumentacion .= ''
                                . '<div class="row m-t-20">'
                                . '     <div class="col-md-12 col-xs-12">'
                                . '         <fieldset>'
                                . '             <legend class="pull-left width-full f-s-17">Información de Paqueteria y Consolidado.</legend>'
                                . '         </fieldset>'
                                . '     </div>'
                                . '</div>'
                                . '</div>'
                                . '<div class="row">'
                                . ' <div class="col-md-6 col-xs-12">'
                                . '     <h5 class="f-w-700">Paquetería</h5>'
                                . '     <h5>' . $paqueteria . '</h5>'
                                . ' </div>'
                                . ' <div class="col-md-6 col-xs-12">'
                                . '     <h5 class="f-w-700">Guía o Referencia</h5>'
                                . '     <pre>' . $guia . '</pre>'
                                . ' </div>'
                                . '</div>'
                                . '<div class="row">'
                                . ' <div class="col-md-6 col-xs-12">'
                                . '     <h5 class="f-w-700">Comentarios de Envío</h5>'
                                . '     <pre>' . $comentariosEnvio . '</pre>'
                                . ' </div>'
                                . '</div>'
                                . '<div class="row m-t-20">'
                                . ' <div class="col-md-6 col-xs-12">'
                                . '     <fieldset>'
                                . '         <legend class="pull-left width-full f-s-17">Evidencia de Envío.</legend>'
                                . '     </fieldset>'
                                . '     ' . $htmlArchivosE
                                . ' </div>'
                                . '</div>';
                    }
                }
                $htmlDocumentacion .= ''
                        . '<div class="row m-t-20">'
                        . '     <div class="col-md-12 col-xs-12">'
                        . '         <fieldset>'
                        . '             <legend class="pull-left width-full f-s-17">Información de Entrega.</legend>'
                        . '         </fieldset>'
                        . '     </div>'
                        . '</div>'
                        . '<div class="row">'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h5 class="f-w-700">Fecha y Hora de Entrega</h5>'
                        . '     <pre>' . $fechaEntrega . '</pre>'
                        . ' </div>'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h5 class="f-w-700">¿Quién Recibe?</h5>'
                        . '     <pre>' . $recibe . '</pre>'
                        . ' </div>'
                        . '</div>'
                        . '<div class="row">'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h5 class="f-w-700">Comentarios de Entrega</h5>'
                        . '     <pre>' . $comentariosEntrega . '</pre>'
                        . ' </div>'
                        . '</div>'
                        . '<div class="row m-t-20">'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <fieldset>'
                        . '         <legend class="pull-left width-full f-s-17">Evidencia de Entrega.</legend>'
                        . '     </fieldset>'
                        . '     ' . $htmlArchivos
                        . ' </div>'
                        . '</div>';
                break;
            case 2:
                $documentacion = $this->getDocumentacionRecoleccionTrafico($servicio);
                $htmlArchivos = '';
                $fecha = $entrega = $comentariosRecoleccion = 'Sin Información';
                if (array_key_exists(0, $documentacion)) {
                    $fecha = ($documentacion[0]['Fecha'] !== '') ? strftime('%A %e de %B, %G ', strtotime($documentacion[0]['Fecha'])) . date("h:ma", strtotime($documentacion[0]['Fecha'])) : 'Sin información';
                    $entrega = ($documentacion[0]['Entrega'] !== '') ? $documentacion[0]['Entrega'] : 'Sin Información';
                    $comentariosRecoleccion = ($documentacion[0]['ComentariosRecoleccion'] !== '') ? $documentacion[0]['ComentariosRecoleccion'] : 'Sin Información';

                    if ($documentacion[0]['Recoleccion'] !== '' && $documentacion[0]['Recoleccion'] !== NULL) {
                        $htmlArchivos .= '';
                        $archivos = explode(",", $documentacion[0]['Recoleccion']);
                        foreach ($archivos as $k => $v) {
                            $pathInfo = pathinfo($v);
                            $src = $this->getSrcByPath($pathInfo, $v);
                            $htmlArchivos .= ''
                                    . '<div class="evidencia">'
                                    . ' <a class="m-l-5 m-r-5" href="' . $v . '" data-lightbox="image-envio-' . $servicio . '" data-title="' . $pathInfo['basename'] . '">'
                                    . '     <img src="' . $src . '" style="max-height:115px !important;" alt="' . $pathInfo['basename'] . '"  />'
                                    . '     <p class="m-t-0">' . $pathInfo['basename'] . '</p>'
                                    . ' </a>'
                                    . '</div>';
                        }
                    } else {
                        $htmlArchivos .= ''
                                . '<h5>Sin Información</h5>';
                    }
                }
                $htmlDocumentacion .= ''
                        . '<div class="row">'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h5 class="f-w-700">Fecha y Hora de Entrega</h5>'
                        . '     <pre>' . $fecha . '</pre>'
                        . ' </div>'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h5 class="f-w-700">¿Quién Entrega?</h5>'
                        . '     <pre>' . $entrega . '</pre>'
                        . ' </div>'
                        . '</div>'
                        . '<div class="row">'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h5 class="f-w-700">Comentarios de Entrega</h5>'
                        . '     <pre>' . $comentariosRecoleccion . '</pre>'
                        . ' </div>'
                        . '</div>'
                        . '<div class="row m-t-20">'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <fieldset>'
                        . '         <legend class="pull-left width-full f-s-17">Evidencia de Entrega.</legend>'
                        . '     </fieldset>'
                        . '     ' . $htmlArchivos
                        . ' </div>'
                        . '</div>';
                break;
        }
        return $htmlDocumentacion;
    }

    public function getTipoServicio(string $servicio) {
        $tipo = $this->DBB->getTipoServicio($servicio);
        if (count($tipo) > 0) {
            return $tipo[0];
        } else {
            return ['Tipo' => 0, 'Seguimiento' => 0];
        }
    }

    public function listaServicios(string $solicitud) {
        $listaServicios = $this->DBB->busquedaReporte('select                
                                                            tst.Id,								
                                                            tst.Ticket,
                                                            estatus(tst.IdEstatus)as NombreEstatus,
                                                            tipoServicio(tst.IdTipoServicio) as TipoServicio,
                                                            sucursal(tst.IdSucursal) Sucursal,
                                                            tst.FechaCreacion,
                                                            nombreUsuario(tst.Atiende) Atiende,
                                                            tst.Descripcion,
                                                            tst.IdSolicitud
                                                        from t_servicios_ticket tst
                                                        where tst.IdEstatus in (1,2,3,10,12)
                                                        AND tst.IdSolicitud = "' . $solicitud . '"
                                                        GROUP BY tst.Id ASC');
        $data = [
            'listaServicios' => $listaServicios
        ];
        return parent::getCI()->load->view("Generales/Modal/tablaServicios", $data, TRUE);
    }
    
    public function exportarCenso(array $datos = null){
        $listaCenso = $this->DBB->getinfoEqiposCenso($datos['servicio']);
        $detallesDiferenciaCenso = $this->SeguimientoPoliza->getDataForCensoCompare($datos['servicio']);
        $host = $_SERVER['SERVER_NAME'];
        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $liga = 'http://siccob.solutions';
        } else {
            $liga = 'http://' . $host;
        }

        if($listaCenso){
            
            $this->contenidoCenso($listaCenso);
            $this->EncabezadoDiferenciasConteos($detallesDiferenciaCenso['generales'], count($detallesDiferenciaCenso['ultimo']), count($detallesDiferenciaCenso['actual']), $detallesDiferenciaCenso['diferenciaAreas']);
            $this->diferenciasConteos($detallesDiferenciaCenso, $detallesDiferenciaCenso['diferenciaAreas']);
            $this->faltantes($detallesDiferenciaCenso['diferenciasKit']['faltantes']);
            $this->sobrantes($detallesDiferenciaCenso['diferenciasKit']['sobrantes']);
//            $this->diferenciasSeries($detallesDiferenciaCenso['diferenciasActual'], $detallesDiferenciaCenso['diferenciasUltimo'], $detallesDiferenciaCenso['generales']);
//            $this->cambiosSeries($detallesDiferenciaCenso['cambiosSerie']);
                        
            $nombreArchivo = 'Censo-'.$listaCenso[0]['Sucursal'] .'_'. $datos['servicio'] . '.xlsx';
            $nombreArchivo = trim($nombreArchivo);
            $ruta = '../public/storage/Archivos/Reportes/' . $nombreArchivo;

            $path = "../public/storage/Archivos/Reportes/";
            if (!is_dir($path)) {
                mkdir($path, 775, true);
            }
            $this->Excel->saveFile($ruta);

            return ['ruta' => $liga . '/storage/Archivos/Reportes/' . $nombreArchivo];
        } else {
            return ['ruta' => 500, 'mensaje' => 'No hay Registros en el censo'];
        }
    }
    
    public function contenidoCenso($listaCenso) {
        $this->Excel->createSheet('Censo', 0);
        $this->Excel->setActiveSheet(0);
        $arrayTitulos = [
                'Sucursal',
                'Área de Atención',
                'Punto',
                'Línea de Equipo',
                'Sublínea de Equipo',
                'Marca',
                'Modelo',
                'Serie',
                'Terminal'];
            $this->Excel->setTableSubtitles('A', 1, $arrayTitulos);
            $arrayWidth = [30, 15, 8, 20, 20, 20, 30, 30, 20];
            $this->Excel->setColumnsWidth('A', $arrayWidth);
            $arrayAlign = ['justify', 'center', 'center', 'justify', 'justify', 'justify', 'justify', 'center', 'center'];

            $this->Excel->setTableContent('A', 1, $listaCenso, true, $arrayAlign);
    }
    
    public function totalesExcel($diferenciaAreas) {
        $totales = [
            'area' => 'TOTALES',
            'puntos' => 0,
            'debenExistir' => 0,
            'censados' => 0,
            'faltantes' => 0,
            'sobrantes' => 0
        ];
        if (isset($diferenciaAreas) && count($diferenciaAreas) > 0) {
            foreach ($diferenciaAreas as $k => $v) {
                $totales['puntos'] += $v['Puntos'];
                $totales['debenExistir'] += ($v['Puntos'] * $v['EquiposxPunto']);
                $totales['censados'] += $v['TotalCensado'];
                $totales['faltantes'] += $v['Faltantes'];
                $totales['sobrantes'] += $v['Sobrantes'];
            }
        }
        
        return $totales;
    }
    
    public function EncabezadoDiferenciasConteos($informacionGeneralCenso, $ultimo, $actual, $diferenciaAreas) {
        $totales = $this->totalesExcel($diferenciaAreas);
        $this->Excel->createSheet('Diferencias(Conteos)', 1);
        $this->Excel->setActiveSheet(1);
        $this->Excel->setTableTitle('A1', 'J1', $informacionGeneralCenso["Sucursal"], ['center']);
        $this->Excel->setTableTitle('A2', 'B2', 'Total de Equipos censados');
        $this->Excel->setTableTitle('A3', 'B3', $totales['censados'], ['center']);
        $this->Excel->setTableTitle('C2', 'D2', 'Total de equipos que deben existir (basado en el estandar)');
        $this->Excel->setTableTitle('C3', 'D3', $totales['debenExistir'], ['center']);
        $this->Excel->setTableTitle('E2', 'G2', 'Total Faltantes', ['center']);
        $this->Excel->setTableTitle('E3', 'G3', $totales['faltantes'], ['center']);
        $this->Excel->setTableTitle('H2', 'J2', 'Total Sobrantes', ['center']);
        $this->Excel->setTableTitle('H3', 'J3', $totales['sobrantes'], ['center']);
    }
    
    public function diferenciasConteos($detallesGeneralesCenso, $diferenciaAreas) {
        $totales = $this->totalesExcel($diferenciaAreas);
        $this->Excel->setActiveSheet(1);
        $this->Excel->setTableTitle('A5', 'C5', 'Diferencia de Sublíneas');
//        $this->Excel->setTableTitle('D5', 'E5', 'Diferencia de Puntos por Área');
//        $this->Excel->setTableTitle('G5', 'H5', 'Diferencia de Líneas');
//        $this->Excel->setTableTitle('J5', 'K5', 'Diferencia de Modelos');
        $this->Excel->setTableTitle('E5', 'J5', 'Diferencia de Equipos en Áreas');
        
        $titulosSublineas = [
                'SubLíneas',
                'Faltantes',
                'Sobrantes'];
        $this->Excel->setTableSubtitles('A', 6, $titulosSublineas);
//        $titulosArea = [
//                'Área',
//                'Total de Puntos'];
//        $this->Excel->setTableSubtitles('D', 6, $titulosArea);
//        $titulosLinea = [
//                'Línea',
//                'Total de Equipos'];
//        $this->Excel->setTableSubtitles('G', 6, $titulosLinea);
//        $titulosModelos = [
//                'Modelos',
//                'Total de Equipos'];
//        $this->Excel->setTableSubtitles('J', 6, $titulosModelos);
        $titulosEquiposArea = [
                'Área',
                'Número de Puntos',
                'Equipos que deben existir (según el estandar)',
                'Equipos censados',
                'Faltantes (según el estandar)',
                'Sobrantes (según el estandar)'
            ];
        $this->Excel->setTableSubtitles('E', 6, $titulosEquiposArea);
        $arrayWidth = [20, 20, 20, 20, 20, 20];
        $this->Excel->setColumnsWidth('A', $arrayWidth);
//        $this->Excel->setColumnsWidth('D', $arrayWidth);
//        $this->Excel->setColumnsWidth('G', $arrayWidth);
//        $this->Excel->setColumnsWidth('J', $arrayWidth);
        $this->Excel->setColumnsWidth('E', $arrayWidth);
        $arrayAlign = ['center', 'center', 'center', 'center', 'center', 'center'];
        
        $listaSubLineas = array();
//        $listaAreas = array();
//        $listaLineas = array();
//        $listaModelos = array();
        $listaEquiposArea = array();
        
        foreach ($detallesGeneralesCenso['diferenciaSublineas'] as $k => $v) {
            if($v != 0){
                $listaSubLineas[$k]['Sublinea'] = $k;
                $listaSubLineas[$k]['faltantes'] = $v["faltantes"];
                $listaSubLineas[$k]['sobrantes'] = $v["sobrantes"];
            }
        }
        unset($listaSubLineas["conteo"]);
        $listaSubLineas['Totales'] = array($totales['area'], $totales['faltantes'], $totales['sobrantes']);
        
//        foreach ($detallesGeneralesCenso['diferenciaAreas'] as $k => $v) {
//            if($v != 0){
//                $listaAreas[$k]['Area'] = $k;
//                $listaAreas[$k]['Valor'] = $v;
//            }
//        }
        
//        foreach ($detallesGeneralesCenso['diferenciaLineas'] as $k => $v) {
//            if($v != 0){
//                $listaLineas[$k]['Area'] = $k;
//                $listaLineas[$k]['Valor'] = $v;
//            }
//        }
        
//        foreach ($detallesGeneralesCenso['diferenciaModelos'] as $k => $v) {
//            if($v != 0){
//                $listaModelos[$k]['Area'] = $k;
//                $listaModelos[$k]['Valor'] = $v;
//            }
//        }
        foreach ($detallesGeneralesCenso['diferenciaAreas'] as $k => $v) {
            if($v != 0){
                $listaEquiposArea[$k]['Area'] = $k;
                $listaEquiposArea[$k]['EquiposxPunto'] = $v["Puntos"];
                $listaEquiposArea[$k]['TextoKit'] = $v['Puntos'] * $v['EquiposxPunto'];
                $listaEquiposArea[$k]['TotalCensado'] = $v["TotalCensado"];
                $listaEquiposArea[$k]['Faltantes'] = $v["Faltantes"];
                $listaEquiposArea[$k]['Sobrantes'] = $v["Sobrantes"];
            }
        }
        array_push($listaEquiposArea, $totales);
        
        if(count($listaSubLineas) > 0){
            $this->Excel->setTableContent('A', 6, $listaSubLineas, true, $arrayAlign);
        } else {
            $this->Excel->setTableTitle('A7', 'B7', 'No existen registros', ['center']);
        }
//        if(count($listaAreas) > 0){
//            $this->Excel->setTableContent('D', 6, $listaAreas, true, $arrayAlign);
//        } else {
//            $this->Excel->setTableTitle('D7', 'E7', 'No existen registros', ['center']);
//        }
//        if(count($listaLineas) > 0){
//            $this->Excel->setTableContent('G', 6, $listaLineas, true, $arrayAlign);
//        } else {
//            $this->Excel->setTableTitle('G7', 'H7', 'No existen registros', ['center']);
//        }
//        if(count($listaModelos) > 0){
//            $this->Excel->setTableContent('J', 6, $listaModelos, true, $arrayAlign);
//        } else {
//            $this->Excel->setTableTitle('J7', 'K7', 'No existen registros', ['center']);
//        }
        if(count($listaEquiposArea) > 0){
            $this->Excel->setTableContent('E', 6, $listaEquiposArea, true, $arrayAlign);
        } else {
            $this->Excel->setTableTitle('E6', 'J6', 'No existen registros', ['center']);
        }
    }
    
    public function diferenciasSeries($diferenciasActual, $diferenciasUltimo, $fechas) {
        $listaDiferenciasActual = array();
        $listaDiferenciasUltimo = array();
        $this->Excel->createSheet('Diferencias(Series)', 4);
        $this->Excel->setActiveSheet(4);
        $this->Excel->setTableTitle('A1', 'G1', 'Equipos que no existen en el censo de ' . $fechas["Fecha"]);
        $this->Excel->setTableTitle('I1', 'O1', 'Equipos que no existen en el censo de ' . $fechas["FechaUltimo"] . ' (Actual)');
        $arrayTitulos = [
                'Área',
                'Punto',
                'Línea',
                'Sublínea',
                'Marca',
                'Modelo',
                'Serie'];
        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        $this->Excel->setTableSubtitles('I', 2, $arrayTitulos);
        $arrayWidth = [15, 8, 20, 20, 20, 30, 30];
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        $this->Excel->setColumnsWidth('I', $arrayWidth);
        $arrayAlign = ['center', 'center', 'justify', 'justify', 'justify', 'justify', 'center'];
        
        foreach ($diferenciasActual as $k => $v) {
            $listaDiferenciasActual[$k]['Area'] = $v['Area'];
            $listaDiferenciasActual[$k]['Punto'] = $v['Punto'];
            $listaDiferenciasActual[$k]['Linea'] = $v['Linea'];
            $listaDiferenciasActual[$k]['Sublinea'] = $v['Sublinea'];
            $listaDiferenciasActual[$k]['Marca'] = $v['Marca'];
            $listaDiferenciasActual[$k]['Modelo'] = $v['Modelo'];
            $listaDiferenciasActual[$k]['SerieAnt'] = $v['Serie'];
        }
        foreach ($diferenciasUltimo as $k => $v) {
            $listaDiferenciasUltimo[$k]['Area'] = $v['Area'];
            $listaDiferenciasUltimo[$k]['Punto'] = $v['Punto'];
            $listaDiferenciasUltimo[$k]['Linea'] = $v['Linea'];
            $listaDiferenciasUltimo[$k]['Sublinea'] = $v['Sublinea'];
            $listaDiferenciasUltimo[$k]['Marca'] = $v['Marca'];
            $listaDiferenciasUltimo[$k]['Modelo'] = $v['Modelo'];
            $listaDiferenciasUltimo[$k]['SerieAnt'] = $v['Serie'];
        }
        if(count($listaDiferenciasActual) > 0){
            $this->Excel->setTableContent('A', 2, $listaDiferenciasActual, true, $arrayAlign);
        } else {
            $this->Excel->setTableTitle('A3', 'G3', 'No existen registros', ['center']);
        }
        if(count($listaDiferenciasUltimo) > 0){
            $this->Excel->setTableContent('I', 2, $listaDiferenciasUltimo, true, $arrayAlign);
        } else {
            $this->Excel->setTableTitle('I3', 'O3', 'No existen registros', ['center']);
        }
    }
    
    public function cambiosSeries($cambiosSerie) {
        $listaCambiosSerie = array();
        $this->Excel->createSheet('Cambios Serie', 5);
        $this->Excel->setActiveSheet(5);
        $this->Excel->setTableTitle('A1', 'H1', 'Equipos que posiblemente cambiaron de tener Serie a ser ILEGIBLE');
        $arrayTitulos = [
                'Área',
                'Punto',
                'Línea',
                'Sublínea',
                'Marca',
                'Modelo',
                'Serie Anterior',
                'Serie Actual'];
        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        $arrayWidth = [15, 8, 20, 20, 20, 30, 30, 30];
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        $arrayAlign = ['center', 'center', 'justify', 'justify', 'justify', 'justify', 'center', 'center'];

        foreach ($cambiosSerie as $k => $v) {
            $listaCambiosSerie[$k]['Area'] = $v['Area'];
            $listaCambiosSerie[$k]['Punto'] = $v['Punto'];
            $listaCambiosSerie[$k]['Linea'] = $v['Linea'];
            $listaCambiosSerie[$k]['Sublinea'] = $v['Sublinea'];
            $listaCambiosSerie[$k]['Marca'] = $v['Marca'];
            $listaCambiosSerie[$k]['Modelo'] = $v['Modelo'];
            $listaCambiosSerie[$k]['SerieAnt'] = $v['Serie'];
            $listaCambiosSerie[$k]['SerieAct'] = 'ILEGIBLE';
        }

        if(count($listaCambiosSerie) > 0){
            $this->Excel->setTableContent('A', 2, $listaCambiosSerie, true, $arrayAlign);
        } else {
            $this->Excel->setTableTitle('A3', 'H3', 'No existen registros', ['center']);
        }
    }
    
    public function faltantes($diferenciasKitFaltantes) {
        $listaFaltantes = array();
        $this->Excel->createSheet('Faltantes', 2);
        $this->Excel->setActiveSheet(2);
        $this->Excel->setTableTitle('A1', 'E1', 'Equipos que faltan basado en el Kit Estandar de Área');
        $arrayTitulos = [
                'Área',
                'Punto',
                'Línea',
                'Sublínea',
                'Cantidad'];
        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        $arrayWidth = [30, 8, 20, 20, 20];
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        $arrayAlign = ['justify', 'center', 'justify', 'justify', 'justify'];
        
        $i = 0;
        foreach ($diferenciasKitFaltantes as $kArea => $vArea) {
            foreach ($vArea as $kPunto => $vPunto) {
                foreach ($vPunto as $k => $v) {
                    $listaFaltantes[$i]["Area"] = $diferenciasKitFaltantes[$kArea][$kPunto][$k]['Area'];
                    $listaFaltantes[$i]["Punto"] = str_replace("P", "", $kPunto);
                    $listaFaltantes[$i]["Linea"] = $diferenciasKitFaltantes[$kArea][$kPunto][$k]['Linea'];
                    $listaFaltantes[$i]["Sublinea"] = $diferenciasKitFaltantes[$kArea][$kPunto][$k]['Sublinea'];
                    $listaFaltantes[$i]["Cantidad"] = $diferenciasKitFaltantes[$kArea][$kPunto][$k]['Cantidad'];
                    $i++;
                }
            }
        }
        
        if(count($listaFaltantes) > 0){
            $this->Excel->setTableContent('A', 2, $listaFaltantes, true, $arrayAlign);
        } else {
            $this->Excel->setTableTitle('A3', 'E3', 'No existen registros', ['center']);
        }        
    }
    
    public function sobrantes($diferenciasKitSobrantes) {
        $listaSobrantes = array();
        $lista = array();
        $this->Excel->createSheet('Sobrantes', 3);
        $this->Excel->setActiveSheet(3);
        $this->Excel->setTableTitle('A1', 'G1', 'Equipos que sobran basado en el Kit Estandar de Área');
        $arrayTitulos = [
                'Área',
                'Punto',
                'Línea',
                'Sublínea',
                'Marca',
                'Modelo',
                'Serie'];
        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        $arrayWidth = [30, 8, 20, 20, 20, 30, 30];
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        $arrayAlign = ['justify', 'center', 'justify', 'justify', 'justify', 'justify', 'center'];
        
        $i = 0;
        foreach ($diferenciasKitSobrantes as $kArea => $vArea) {
            foreach ($vArea as $kPunto => $vPunto) {
                foreach ($vPunto as $k => $v) {
                    $listaSobrantes[$kPunto][$k]["Area"] = $v['Area'];
                    $listaSobrantes[$kPunto][$k]["Punto"] = $v['Punto'];
                    $listaSobrantes[$kPunto][$k]["Linea"] = $v['Linea'];
                    $listaSobrantes[$kPunto][$k]["Sublinea"] = $v['Sublinea'];
                    $listaSobrantes[$kPunto][$k]["Marca"] = $v['Marca'];
                    $listaSobrantes[$kPunto][$k]["Modelo"] = $v['Modelo'];
                    $listaSobrantes[$kPunto][$k]["Serie"] = $v['Serie'];
                }
            }
        }
        foreach ($listaSobrantes as $k => $v) {
            foreach ($v as $key => $value) {
                $lista[$i] = $value;
                $i++;
            }
        }
        
        if(count($lista) > 0){
            $this->Excel->setTableContent('A', 2, $lista, true, $arrayAlign);
        } else {
            $this->Excel->setTableTitle('A3', 'G3', 'No existen registros', ['center']);
        }
    }
}
