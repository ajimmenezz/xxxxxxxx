<?php

namespace Librerias\Logistica;

use Controladores\Controller_Datos_Usuario as General;

class Dashboard extends General {

    private $DBS;
    private $solicitudes;
    private $dashboardGeneral;
    private $Excel;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Solicitud::factory();
        $this->solicitudes = \Librerias\Generales\Solicitud::factory();
        $this->dashboardGeneral = \Librerias\Generales\Dashboard::factory();
        $this->Excel = new \Librerias\Generales\CExcel();
        parent::getCI()->load->helper('date');
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
