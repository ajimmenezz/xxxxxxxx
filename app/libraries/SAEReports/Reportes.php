<?php

namespace Librerias\SAEReports;

ini_set('max_execution_time', 3600);

use Controladores\Controller_Datos_Usuario as General;

class Reportes extends General {

    private $DBSAE;
    private $Excel;

    public function __construct() {
        parent::__construct();
        $this->DBSAE = \Modelos\Modelo_SAE7::factory();
        $this->Excel = new \Librerias\Generales\CExcel();
        parent::getCI()->load->helper(array('FileUpload', 'date'));
    }

    /* Encargado de regresar los almacenes virtuales de SAE */

    public function getAlamacenesSAE(array $datos = null) {
        $consulta = $this->DBSAE->getAlmacenesSAE();
        return $consulta;
    }

    /* Encargado de regresar los el inventario del almacen virtual de SAE */

    public function getInventarioAlamacenSAE(array $datos = null) {
        $consulta = $this->DBSAE->getInventarioAlamacenSAE($datos['almacen']);
        return $consulta;
    }

    public function exportaInventarioAlamacenSAE(array $datos = null) {
        $info = $datos['info'];

        /* Begin Hoja 1 */
        //Crea una hoja en la posición 0 y la nombra.
        $this->Excel->createSheet('Inventario', 0);
        //Selecciona la hoja creada y la marca como activa. Todas las modificaciones se harán en está hoja.
        $this->Excel->setActiveSheet(0);
        //Arreglo de los subtitulos de la tabla. LA posición es de izquierda a derecha.
        $arrayTitulos = [
            'Clave de Producto',
            'Producto',
            'Línea',
            'Unidad',
            'Existencias',
            'Costo'];
        //Envía el arreglo de los subtitulos a la hoja activa.
        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        //Arreglo con el ancho por columna. 
        $arrayWidth = [20, 35, 20, 15, 15, 20];
        //Envía y setea los anchos de las columnas definidos en el arreglo de los anchos por columna.
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        //Setea el titulo de la tabla. Envía la celda de inicio y la final para que se combinen.
        $this->Excel->setTableTitle("A1", "F1", "Inventario de " . $datos['almacen'], array('titulo'));
        //Arreglo de alineación por columna.
        $arrayAlign = ['', '', '', '', 'center'];
        //Envía:
        //La letra donde comienza la tabla
        //El número de fila donde comenzará la tabla -1
        //El contenido en forma de arreglo
        //Boleano que define si la tabla llevará autofiltros o no
        //Arreglo con la alineación de las columnas.
        $this->Excel->setTableContent('A', 2, $info, true, $arrayAlign);
        /* End Hoja 1 */

        $time = date("ymd_H_i_s");
        $nombreArchivo = 'Inventario_' . $datos['almacen'] . '_' . $time . '.xlsx';
        $nombreArchivo = trim($nombreArchivo);
        $ruta = 'storage/Archivos/SAEReports/' . $nombreArchivo;

        //Guarda la hoja envíandole la ruta y el nombre del archivo que se va a guardar.
        $this->Excel->saveFile($ruta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ruta];
    }

    public function exportaReporteComprasSAE(array $datos = null) {
        $compras = $datos['compras'];
        $existencias = $datos['existencias'];
        $movimientos = $datos['movimientos'];

        /* Begin Hoja 1 */
        //Crea una hoja en la posición 0 y la nombra.
        $this->Excel->createSheet('Compras', 0);
        //Selecciona la hoja creada y la marca como activa. Todas las modificaciones se harán en está hoja.
        $this->Excel->setActiveSheet(0);
        //Arreglo de los subtitulos de la tabla. La posición es de izquierda a derecha.
        $arrayTitulosCompras = [
            'Empresa',
            'Referencia',
            'Proyecto',
            'Observaciones',
            'Oc',
            'Fecha',
            'Clave Artículo',
            'Artículo',
            'Línea',
            'Cantidad',
            'Precio',
            'Total'];
        //Envía el arreglo de los subtitulos a la hoja activa.
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosCompras);
        //Arreglo con el ancho por columna. 
        $arrayWidthCompras = [12.86, 20.14, 16.14, 18.29, 14.86, 21.14, 17.43, 33.71, 10, 13.10, 11, 10];
        //Envía y setea los anchos de las columnas definidos en el arreglo de los anchos por columna.
        $this->Excel->setColumnsWidth('A', $arrayWidthCompras);
        //Arreglo de alineación por columna.
        $arrayAlignCompras = ['', '', '', '', '', 'center', '', '', '', 'center', 'center', 'center'];
        //Envía:
        //La letra donde comienza la tabla
        //El número de fila donde comenzará la tabla -1
        //El contenido en forma de arreglo
        //Boleano que define si la tabla llevará autofiltros o no
        //Arreglo con la alineación de las columnas.
        $this->Excel->setTableContent('A', 1, $compras, true, $arrayAlignCompras);
        /* End Hoja 1 */

        /* Begin Hoja 2 */
        $this->Excel->createSheet('Existencias', 1);
        $this->Excel->setActiveSheet(1);
        $arrayTitulosExistencias = [
            'Clave Artículo',
            'Artículo',
            'Almacén Virtual',
            'Existencias'];
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosExistencias);
        $arrayWidthExistencias = [18, 33.71, 34.14, 15];
        $this->Excel->setColumnsWidth('A', $arrayWidthExistencias);
        $arrayAlignExistencias = ['center', '', '', 'center'];
        $this->Excel->setTableContent('A', 1, $existencias, true, $arrayAlignExistencias);
        /* End Hoja 2 */
        
        /* Begin Hoja 3 */
        $this->Excel->createSheet('Movimientos', 2);
        $this->Excel->setActiveSheet(2);
        $arrayTitulosMovimientos = [
            'Número Movimiento',
            'Folio',
            'Clave Artículo',
            'Artículo',
            'Almacén',
            'Concepto',
            'Movimiento',
            'Referencia',
            'Cantidad',
            'Costo',
            'Costo Promo Inicial',
            'Costo Promo Final',
            'Unidad Venta',
            'Existencia',
            'Fecha',
            'Movimiento Enlazado'];
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosMovimientos);
        $arrayWidthMovimientos = [24.71, 9.46, 17.57, 33.71, 15.29, 16.71, 16.43, 17.14, 13.43, 12.14, 22.43, 21, 17.15, 14.14, 16.29, 24.29];
        $this->Excel->setColumnsWidth('A', $arrayWidthMovimientos);
        $arrayAlignMovimientos = ['center', 'center', 'center', '', '', '', '', '', 'center', 'center', 'center', 'center', '', 'center', 'center', 'center'];
        $this->Excel->setTableContent('A', 1, $movimientos, true, $arrayAlignMovimientos);
        /* End Hoja 3 */

        $time = date("ymd_H_i_s");
        $nombreArchivo = 'Reportes_Compras_' . $time . '.xlsx';
        $nombreArchivo = trim($nombreArchivo);
        $ruta = 'storage/Archivos/SAEReports/' . $nombreArchivo;

        //Guarda la hoja envíandole la ruta y el nombre del archivo que se va a guardar.
        $this->Excel->saveFile($ruta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ruta];
    }

    public function getBuscarProductosCompras(array $datos = null) {
        $consulta = $this->DBSAE->consultaBDSAE("SELECT CVE_ART as Clave, DESCR as Nombre 
                                                FROM SAE7EMPRESA3.dbo.INVE03 as Productos 
                                                WHERE Productos.CVE_ART LIKE '%" . strtoupper($datos['producto']) . "%' 
                                                OR Productos.DESCR LIKE '%" . strtoupper($datos['producto']) . "%'
                                                OR Productos.CVE_ART LIKE '%" . $datos['producto'] . "%' 
                                                OR Productos.DESCR LIKE '%" . $datos['producto'] . "%'");
        return $consulta;
    }

    public function mostrarReporteComprasSAE(array $datos) {
        $data = array();
        $nuevoListaProductos = array();

        foreach ($datos['listaProductos'] as $key => $value) {
            array_push($nuevoListaProductos, "'" . $value . "'");
        }
        $stringListaProductos = implode(",", $nuevoListaProductos);
        $data['compras'] = $this->DBSAE->consultaBDSAE("select 
                                                            'Empresa 3' as Empresa,
                                                            ordenes.SU_REFER as Referencia,
                                                            adicionales.CAMPLIB1 as Proyecto,
                                                            ordenes.OBS_COND as Observaciones,
                                                            partidas.CVE_DOC as OC,
                                                            ordenes.FECHAELAB as Fecha,
                                                            inventario.CVE_ART as Clave,  
                                                            inventario.DESCR as Articulo,  
                                                            inventario.LIN_PROD as Linea, 
                                                            partidas.CANT as Cantidad,
                                                            ROUND(partidas.COST,2) as Precio,
                                                            ROUND(partidas.TOT_PARTIDA,2) as Total
                                                        from 
                                                        SAE7EMPRESA3.dbo.COMPO03 ordenes inner join SAE7EMPRESA3.dbo.PAR_COMPO03 partidas
                                                            ON ordenes.CVE_DOC = partidas.CVE_DOC
                                                        INNER JOIN SAE7EMPRESA3.dbo.INVE03 inventario
                                                            ON partidas.CVE_ART = inventario.CVE_ART
                                                        INNER JOIN SAE7EMPRESA3.dbo.COMPO_CLIB03 adicionales
                                                            ON ordenes.CVE_DOC = adicionales.CLAVE_DOC
                                                        where ordenes.FECHAELAB BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' 
                                                        and ordenes.FECHA_CANCELA is null and inventario.CVE_ART IN (" . $stringListaProductos . ")");
        $data['existencias'] = $this->DBSAE->consultaBDSAE("SELECT 
                                                            inventario.CVE_ART as Clave,
                                                            productos.DESCR as Articulo,
                                                            almacenes.DESCR as Almacen,
                                                            inventario.EXIST as Existencias
                                                        FROM SAE7EMPRESA3.dbo.MULT03 as inventario 
                                                        inner join SAE7EMPRESA3.dbo.INVE03 as productos 
                                                            on inventario.CVE_ART = productos.CVE_ART
                                                        inner join SAE7EMPRESA3.dbo.ALMACENES03 as almacenes 
                                                            on almacenes.CVE_ALM = inventario.CVE_ALM
                                                        WHERE inventario.CVE_ART in (" . $stringListaProductos . ") 
                                                        and inventario.EXIST > 0");
        $data['movimientos'] = $this->DBSAE->consultaBDSAE("select 
                                                            NUM_MOV as Numero_Movimiento,
                                                            movimientos.CVE_FOLIO as Folio,
                                                            productos.CVE_ART as Clave_Producto,
                                                            productos.DESCR as Articulo,
                                                            (select DESCR from ALMACENES03 almacenes where almacenes.CVE_ALM = movimientos.ALMACEN) as Almacen,
                                                            (select DESCR from CONM03 conceptos where conceptos.CVE_CPTO = movimientos.CVE_CPTO) as Concepto,
                                                            case movimientos.TIPO_DOC when 'M' then 'Traspaso' when 'r' then 'Compra/Remisión' end as Movimiento,
                                                            movimientos.REFER as Referencia,
                                                            movimientos.CANT as Cantidad,
                                                            CAST(movimientos.COSTO as CHAR) as Costo,
                                                            CAST(movimientos.COSTO_PROM_INI as CHAR) as Costo_Promo_Inicial,
                                                            CAST(movimientos.COSTO_PROM_FIN as CHAR) as Costo_Promo_Final,
                                                            movimientos.UNI_VENTA as Unidad_Venta,
                                                            movimientos.EXISTENCIA as Existencia,
                                                            movimientos.FECHAELAB as Fecha,
                                                            movimientos.MOV_ENLAZADO
                                                        from MINVE03 movimientos 
                                                        inner join INVE03 productos 
                                                            on movimientos.CVE_ART = productos.CVE_ART
                                                        where FECHAELAB BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' 
                                                        and movimientos.CVE_ART in (" . $stringListaProductos . ")
                                                        order by Numero_Movimiento");
        return array('formulario' => parent::getCI()->load->view('ReportesSAE/Modal/ReporteComprasSAE', $data, TRUE), 'datos' => $data);
    }

}
