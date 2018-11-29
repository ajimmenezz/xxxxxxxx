<?php

namespace Librerias\SAEReports;

ini_set('max_execution_time', 3600);

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Generales\PDF as PDF;

class Reportes extends General {

    private $DBSAE;
    private $Excel;
    private $pdf;

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
        if(!isset($datos['desde'])){
          $condicion = " where FECHAELAB BETWEEN DATEADD(week, -1, GETDATE()) and GETDATE() ";
        }else{
          $condicion = " where FECHAELAB BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' ";
        }
        $data['inventario'] = $this->DBSAE->getInventarioAlamacenSAE($datos['almacen']);
        $data['movimientos'] =$this->DBSAE->consultaBDSAE("select
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
                                                            on movimientos.CVE_ART = productos.CVE_ART ".$condicion."
                                                        and movimientos.ALMACEN = '".$datos['almacen']."'
                                                        order by Numero_Movimiento");
        return $data;
    }

    public function exportaInventarioAlamacenSAE(array $datos = null) {
        $info = $datos['info'];
        $movimientos = $datos['movimientos'];

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

        /* Begin Hoja 2 */
        $this->Excel->createSheet('Movimientos', 1);
        $this->Excel->setActiveSheet(1);
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
        /* End Hoja 2 */
        
        
        $time = date("ymd_H_i_s");
        $nombreArchivo = 'Inventario_' . $datos['almacen'] . '_' . $time . '.xlsx';
        $nombreArchivo = trim($nombreArchivo);
        $ruta = 'storage/Archivos/SAEReports/' . $nombreArchivo;

        //Guarda la hoja envíandole la ruta y el nombre del archivo que se va a guardar.
        $this->Excel->saveFile($ruta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ruta];
    }
    
    public function exportaReporteComprasSAE(array $datos = null) {
        $compras = isset($datos['compras']) ? $datos['compras'] : [];
        $existencias = isset($datos['existencias']) ? $datos['existencias'] : [];
        $movimientos = isset($datos['movimientos']) ? $datos['movimientos'] : [];                 

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

    public function exportaReporteComprasSAEProyecto(array $datos = null) {
        $compras = $datos['compras'];

        /* Begin Hoja 1 */
        //Crea una hoja en la posición 0 y la nombra.
        $this->Excel->createSheet('Compras', 0);
        //Selecciona la hoja creada y la marca como activa. Todas las modificaciones se harán en está hoja.
        $this->Excel->setActiveSheet(0);
        //Arreglo de los subtitulos de la tabla. La posición es de izquierda a derecha.
        $arrayTitulosCompras = [
            'OC',
            'Proveedor',
            'Referencia',
            'Fecha Documento',
            'Fecha Cancelación',
            'Fecha Elaboración',
            'Total Compra',
            'Impuesto',
            'Descuento',
            'Importe',
            'Proyecto',
            'Campo Libre',
            '# Partida',
            'Clave Artículo',
            'Artículo',
            'Cantidad',
            'Precio Unitario',
            'Moneda',
            'Tipo Cambio',
            'Total Partida'];
        //Envía el arreglo de los subtitulos a la hoja activa.
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosCompras);
        //Arreglo con el ancho por columna.
        $arrayWidthCompras = [20, 35, 25, 30, 30, 30, 15, 15, 15, 15, 25, 25, 10, 20, 35, 10, 15, 15, 15, 15];
        //Envía y setea los anchos de las columnas definidos en el arreglo de los anchos por columna.
        $this->Excel->setColumnsWidth('A', $arrayWidthCompras);
        //Arreglo de alineación por columna.
        $arrayAlignCompras = ['', '', '', '', '', '', 'center', 'center', 'center', 'center', '', '', 'center', 'center', '', 'center', 'center'];
        //Envía:
        //La letra donde comienza la tabla
        //El número de fila donde comenzará la tabla -1
        //El contenido en forma de arreglo
        //Boleano que define si la tabla llevará autofiltros o no
        //Arreglo con la alineación de las columnas.
        $this->Excel->setTableContent('A', 1, $compras, true, $arrayAlignCompras);
        /* End Hoja 1 */

        $time = date("ymd_H_i_s");
        $nombreArchivo = 'Reportes_Compras_Proyecto' . $time . '.xlsx';
        $nombreArchivo = trim($nombreArchivo);
        $ruta = 'storage/Archivos/SAEReports/' . $nombreArchivo;

        //Guarda la hoja envíandole la ruta y el nombre del archivo que se va a guardar.
        $this->Excel->saveFile($ruta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ruta];
    }

    public function exportaReporteRemisiones(array $datos = null) {
        $compras = $datos['compras'];

        /* Begin Hoja 1 */
        //Crea una hoja en la posición 0 y la nombra.
        $this->Excel->createSheet('Compras', 0);
        //Selecciona la hoja creada y la marca como activa. Todas las modificaciones se harán en está hoja.
        $this->Excel->setActiveSheet(0);
        //Arreglo de los subtitulos de la tabla. La posición es de izquierda a derecha.
        $arrayTitulosCompras = [
            'Remision',
            'Fecha Elaboración',
            'Tipo Documento Anterior',
            'Documento Anterior',
            'Producto',
            'Clave',
            'Serie',
            'Observacione Partida',
            'Observaciones Remisión',
            'Pedido',
            'Req',
            'Fecha Documento',
            'Fecha Entrada',
            'Fecha Venta',
            'Fecha Cancelación'];
        //Envía el arreglo de los subtitulos a la hoja activa.
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosCompras);
        //Arreglo con el ancho por columna.
        $arrayWidthCompras = [20, 25, 20, 20, 35, 20, 20, 30, 30, 15, 20, 25, 25, 25, 25];
        //Envía y setea los anchos de las columnas definidos en el arreglo de los anchos por columna.
        $this->Excel->setColumnsWidth('A', $arrayWidthCompras);
        //Arreglo de alineación por columna.
        $arrayAlignCompras = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        //Envía:
        //La letra donde comienza la tabla
        //El número de fila donde comenzará la tabla -1
        //El contenido en forma de arreglo
        //Boleano que define si la tabla llevará autofiltros o no
        //Arreglo con la alineación de las columnas.
        $this->Excel->setTableContent('A', 1, $compras, true, $arrayAlignCompras);
        /* End Hoja 1 */

        $time = date("ymd_H_i_s");
        $nombreArchivo = 'Reporte Remisiones_' . $time . '.xlsx';
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

    public function mostrarReporteComprasSAEProyecto(array $datos) {
        $claves = explode(",", $datos['claves']);

        $condicion = " where compras.FECHAELAB between '".$datos['desde']." 00:00:00' and '".$datos['hasta']." 00:00:00' and (1 <> 1";

        foreach ($claves as $key => $value) {
          $condicion .= " or compras.SU_REFER like '%".$value."%'
          or libres.CAMPLIB1 like '%".$value."%'
          or compras.SU_REFER like '%".strtoupper($value)."%'
          or libres.CAMPLIB1 like '%".strtoupper($value)."%'";
        }

        $condicion .= ")";

        $query = "select
                  compras.CVE_DOC as OC,
                  proveedores.NOMBRE as Proveedor,
                  compras.SU_REFER as Referencia,
                  compras.FECHA_DOC as FechaDocumento,
                  compras.FECHA_CANCELA as FechaCancelacion,
                  compras.FECHAELAB as FechaElaboracion,
                  cast(compras.CAN_TOT as float) as TotalCompra,
                  CAST(compras.IMP_TOT4 as float) as Impuesto,
                  CAST(compras.DES_TOT as float) as Descuento,
                  CAST(compras.IMPORTE as float) as Importe,
                  libres.CAMPLIB1 as Proyecto,
                  libres.CAMPLIB2,
                  partidas.NUM_PAR as NumeroPartida,
                  partidas.CVE_ART as ClaveArticulo,
                  productos.DESCR as Articulo,
                  partidas.CANT as Cantidad,
                  CAST((partidas.TOT_PARTIDA / partidas.CANT)  as float) as PrecioUnitario,
                  moneda.DESCR as Moneda,
                  compras.TIPCAMB as TipoCambio,
                  CAST(partidas.TOT_PARTIDA as float) as TotalPartida,
                  partidas.TOT_PARTIDA * compras.TIPCAMB  as TotalPesos
                  from COMPO03 compras
                  inner join COMPO_CLIB03 libres on compras.CVE_DOC = libres.CLAVE_DOC
                  inner join PAR_COMPO03 partidas on compras.CVE_DOC = partidas.CVE_DOC
                  inner join INVE03 productos on partidas.CVE_ART = productos.CVE_ART
                  inner join PROV03 proveedores on compras.CVE_CLPV = proveedores.CLAVE
                  inner join MONED03 moneda on compras.NUM_MONED = moneda.NUM_MONED ".$condicion;

        $data['compras'] = $this->DBSAE->consultaBDSAE($query);

        return array('formulario' => parent::getCI()->load->view('ReportesSAE/Modal/ReporteComprasSAEProyecto', $data, TRUE), 'datos' => $data);
    }

    public function mostrarReporteRemisiones(array $datos) {
        $condicion = " where remision.FECHAELAB between '".$datos['desde']." 00:00:00' and '".$datos['hasta']." 23:59:59' ";

        $query = "select
                remision.CVE_DOC as Remision,
                remision.FECHAELAB as FechaElaboracion,
                case remision.TIP_DOC_ANT
                	when 'P' then 'PEDIDO'
                	when 'C' then 'COTIZACION'
                	else 'OTRO'
                end as TipoDocumentoAnterior,
                remision.DOC_ANT as DocumentoAnterior,
                productos.DESCR as Producto,
                productos.CVE_ART as Modelo,
                hist_series.NUM_SER as Serie,
                (select STR_OBS from OBS_DOCF03 obs1 where obs1.CVE_OBS = partidas.CVE_OBS) as Observaciones_Partida,
                (select STR_OBS from OBS_DOCF03 obs1 where obs1.CVE_OBS = remision.CVE_OBS) as Observaciones_Remision,
                libres.CAMPLIB1 as Pedido, libres.CAMPLIB4 as Req,remision.FECHA_DOC, remision.FECHA_ENT,
                remision.FECHA_VEN,
                remision.FECHA_CANCELA
                from
                FACTR03 remision
                inner join PAR_FACTR03 partidas on remision.CVE_DOC = partidas.CVE_DOC
                inner join INVE03 productos on partidas.CVE_ART = productos.CVE_ART
                left join HNUMSER03 hist_series on partidas.REG_SERIE = hist_series.REG_SERIE
                left join FACTR_CLIB03 libres on remision.CVE_DOC = libres.CLAVE_DOC ".$condicion;

        $data['compras'] = $this->DBSAE->consultaBDSAE($query);

        return array('formulario' => parent::getCI()->load->view('ReportesSAE/Modal/ReporteRemisiones', $data, TRUE), 'datos' => $data);
    }

    public function generaOC(array $datos) {
        $_SESSION['datosOC'] = $datos;

        $proveedor = $this->DBSAE->consultaBDSAE("select
                                                NOMBRE,
                                                CALLE,
                                                NUMEXT,
                                                NUMINT,
                                                COLONIA,
                                                CODIGO,
                                                MUNICIPIO,
                                                ESTADO,
                                                RFC
                                                from PROV01 where CLAVE = (select CVE_CLPV from COMPO01 where CVE_DOC = '" . $datos['documento'] . "')")[0];

        $generales = $this->DBSAE->consultaBDSAE("select
                                                CONVERT(varchar(10),orden.FECHA_DOC,103) as Fecha,
                                                (select DESCR from ALMACENES01 where CVE_ALM = orden.NUM_ALMA) as Almacen,
                                                orden.OBS_COND as EntregarA,
                                                campos.CAMPLIB1 as Proyecto,
                                                campos.CAMPLIB2 as LugarEntrega,
                                                (select STR_OBS from OBS_DOCC01 where CVE_OBS = orden.CVE_OBS) as Observaciones,
                                                orden.CAN_TOT as Subtotal,
                                                orden.DES_TOT as Descuento,
                                                orden.DES_FIN as DescFin,
                                                orden.IMP_TOT1 as IEPS1,
                                                orden.IMP_TOT2 as IEPS2,
                                                orden.IMP_TOT3 as IEPS3,
                                                orden.IMP_TOT4 as IVA,
                                                orden.IMPORTE as Total
                                                from COMPO01 orden
                                                inner join COMPO_CLIB01 campos on orden.CVE_DOC = campos.CLAVE_DOC
                                                where CVE_DOC = '" . $datos['documento'] . "'")[0];

        $partidas = $this->DBSAE->consultaBDSAE("select
                                                partida.CANT as Cantidad,
                                                partida.CVE_ART as Producto,
                                                producto.DESCR as Descripcion,
                                                partida.DESCU as Descuento,
                                                partida.COST as Costo,
                                                partida.TOT_PARTIDA as Importe,
                                                (select STR_OBS from OBS_DOCC01 where CVE_OBS = partida.CVE_OBS) as Observaciones
                                                from PAR_COMPO01 partida
                                                inner join INVE01 producto on partida.CVE_ART = producto.CVE_ART
                                                where CVE_DOC = '" . $datos['documento'] . "'");

        $this->pdf = new PDFOC();
        $this->pdf->addPage();

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Text(10, 40, 'Proveedor:');

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Text(10, 43.5, $proveedor['NOMBRE']);
        $this->pdf->Text(10, 47, $proveedor['CALLE'] . ' NO. ' . $proveedor['NUMEXT'] . ', COL. ' . $proveedor['COLONIA'] . ', CP ' . $proveedor['CODIGO'] . '');
        $this->pdf->Text(10, 50.5, $proveedor['MUNICIPIO'] . ', ' . $proveedor['ESTADO'] . '.');
        $this->pdf->Text(10, 54, 'RFC: ' . $proveedor['RFC']);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Text(10, 60, 'Lugar de entrega:');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Text(10, 63.5, $generales['LugarEntrega']);


        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Text(130, 40, 'ORDEN No. ' . $datos['documento']);
        $this->pdf->Text(130, 43.5, 'Fecha: ');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Text(140, 43.5, $generales['Fecha']);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Text(130, 47, utf8_decode('Almacén: '));
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Text(144, 47, utf8_decode($generales['Almacen']));

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Text(130, 50.5, 'Entregar a: ');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Text(146, 50.5, $generales['EntregarA']);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Text(130, 54, 'Proyecto: ');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Text(144, 54, $generales['Proyecto']);

        $this->pdf->SetXY(10, 70);
        $this->pdf->SetFillColor(100, 100, 100);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->Cell(20, 6, "Cantidad", 1, 0, 'C', true);
        $this->pdf->Cell(30, 6, "Producto", 1, 0, 'C', true);
        $this->pdf->Cell(70, 6, utf8_decode("Descripción"), 1, 0, 'C', true);
        $this->pdf->Cell(15, 6, "% Desc", 1, 0, 'C', true);
        $this->pdf->Cell(25, 6, "Costo Unitario", 1, 0, 'C', true);
        $this->pdf->Cell(25, 6, "Importe", 1, 0, 'C', true);

        foreach ($partidas as $key => $value) {
            $this->pdf->Ln();
            $break = $this->pdf->CheckPageBreak(9);
            if ($break == 1) {
                $this->pdf->SetFillColor(100, 100, 100);
                $this->pdf->SetFont('Arial', 'B', 8);
                $this->pdf->SetTextColor(255, 255, 255);
                $this->pdf->Cell(20, 6, "Cantidad", 1, 0, 'C', true);
                $this->pdf->Cell(30, 6, "Producto", 1, 0, 'C', true);
                $this->pdf->Cell(70, 6, utf8_decode("Descripción"), 1, 0, 'C', true);
                $this->pdf->Cell(15, 6, "% Desc", 1, 0, 'C', true);
                $this->pdf->Cell(25, 6, "Costo Unitario", 1, 0, 'C', true);
                $this->pdf->Cell(25, 6, "Importe", 1, 0, 'C', true);
                $this->pdf->Ln();
            }
            $this->pdf->SetFillColor(200, 200, 200);
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pdf->Cell(20, 6, number_format($value['Cantidad'], 2, '.', ','), 1, 0, 'C', true);
            $this->pdf->Cell(30, 6, $value['Producto'], 1, 0, 'C', true);
            $this->pdf->Cell(70, 6, utf8_decode($value['Descripcion']), 1, 0, 'J', true);
            $this->pdf->Cell(15, 6, number_format($value['Descuento'], 2, '.', ','), 1, 0, 'C', true);
            $this->pdf->Cell(25, 6, number_format($value['Costo'], 3, '.', ','), 1, 0, 'C', true);
            $this->pdf->Cell(25, 6, number_format($value['Importe'], 3, '.', ','), 1, 0, 'C', true);
            $heigh = ($value['Observaciones'] != '') ? 6 : 2;
            $this->pdf->Ln();
            $this->pdf->SetFillColor(235, 235, 235);
            $this->pdf->Cell(185, $heigh, $value['Observaciones'], 1, 0, 'J', true);
        }

        $break = $this->pdf->CheckPageBreak(50);

        $this->pdf->SetFillColor(255, 255, 255);
        $this->pdf->SetXY(130, 190);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->Cell(20, 6, 'Subtotal', 0, 0, 'L', false);
        $this->pdf->Cell(50, 6, number_format($generales['Subtotal'], 2, '.', ','), 0, 0, 'R', false);
        $this->pdf->Ln();
        $this->pdf->SetX(130);
        $this->pdf->Cell(20, 6, 'Descuento', 0, 0, 'L', false);
        $this->pdf->Cell(50, 6, number_format($generales['Descuento'], 2, '.', ','), 0, 0, 'R', false);
        $this->pdf->Ln();
        $this->pdf->SetX(130);
        $this->pdf->Cell(20, 6, 'Desc. Fin.', 0, 0, 'L', false);
        $this->pdf->Cell(50, 6, number_format($generales['DescFin'], 2, '.', ','), 0, 0, 'R', false);
        $this->pdf->Ln();
        $this->pdf->SetX(130);
        $this->pdf->Cell(20, 6, 'I.E.P.S.', 0, 0, 'L', false);
        $this->pdf->Cell(50, 6, number_format($generales['IEPS1'], 2, '.', ','), 0, 0, 'R', false);
        $this->pdf->Ln();
        $this->pdf->SetX(150);
        $this->pdf->Cell(50, 6, number_format($generales['IEPS2'], 2, '.', ','), 0, 0, 'R', false);
        $this->pdf->Ln();
        $this->pdf->SetX(150);
        $this->pdf->Cell(50, 6, number_format($generales['IEPS3'], 2, '.', ','), 0, 0, 'R', false);
        $this->pdf->Ln();
        $this->pdf->SetX(130);
        $this->pdf->Cell(20, 6, 'I.V.A.', 0, 0, 'L', false);
        $this->pdf->Cell(50, 6, number_format($generales['IVA'], 2, '.', ','), 0, 0, 'R', false);
        $this->pdf->Ln();
        $this->pdf->Ln();
        $this->pdf->SetX(130);
        $this->pdf->Cell(20, 6, 'Total', 0, 0, 'L', false);
        $this->pdf->Cell(50, 6, number_format($generales['Total'], 2, '.', ','), 0, 0, 'R', false);


        $this->pdf->Ln();
        $this->pdf->Ln();
        $totales = explode(".", number_format($generales['Total'], 2, '.', ''));
        $letra = NumeroALetras::convertir($totales[0], 'PESOS');
        $this->pdf->Cell(200, 6, $letra . ' ' . $totales[1] . '/100 M.N.', 0, 0, 'L', false);

        $this->pdf->SetXY(10, 215);
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->MultiCell(115, 6, utf8_decode($generales['Observaciones']), 0, 'J', false);

        $carpeta = './storage/Gastos/' . $datos['idGapsi'] . '/PRE/' . $datos['documento'] . '.pdf';

        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);
        return $carpeta;
    }

}

class PDFOC extends PDF {

    private $contenidoHeader;

    public function __construct($contenido = '', $orientation = 'P', $unit = 'mm', $size = 'Letter') {
        parent::__construct($orientation, $unit, $size);
        $this->contenidoHeader = $contenido;
    }

    public function Header($datos = []) {
        $this->SetFont('Helvetica', '', 8.4);
        $this->Image('./assets/img/siccob-logo.png', 10, 10, 21, 21, 'PNG');
        $this->SetXY(25, 12);
        $this->MultiCell(0, 5, $this->contenidoHeader, 0, 'R');
        $this->SetFont('Arial', '', 15);
        $this->Text(73.5, 15, "SICCOB SOLUTIONS, S.A. DE C.V.");

        $this->SetFont('Arial', 'B', 8);
        $this->Text(33, 19.5, 'Domicilio Fiscal');

        $this->SetFont('Arial', '', 8);
        $this->Text(33, 23.5, 'Calle: INSURGENTES SUR No. 1647 Int: 215, Col. SAN JOSE INSURGENTES, CP: 03900, BENITO JUAREZ, CIUDAD');
        $this->Text(33, 27, 'DE MEXICO. RFC: SSO0101179Z7');


        switch ($_SESSION['datosOC']['id']) {
            case 1:
                $this->Text(33, 31, 'Sucursal:  INSURGENTES  SUR  1647 Int 215, Col. SAN  JOSE  INSURGENTES, CP 03900, BENITO  JUAREZ, CDMX');
                break;
        }

        $this->SetXY(10, 40);
    }

    public function subTitulo(string $titulo) {
        $this->Ln();
        $this->SetFont("helvetica", "", 9);
        $this->Cell(0, 10, utf8_decode($titulo));
        $this->Ln();
        $this->Line($this->GetX(), $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
    }

    public function Footer() {
        $fecha = date('d/m/Y');
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Helvetica', 'I', 10);
        // Print centered page number
        $this->Cell(120, 10, utf8_decode('Fecha de Generación: ') . $fecha, 0, 0, 'L');
        $this->Cell(68, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'R');
    }

    public function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            return 1;
        }
        return 0;
    }

    // Tabla simple
    public function BasicTable($header, $data) {
        $this->Ln(3);
        $ancho = ($this->GetPageWidth() - 20) / count($header);
        // Cabecera
        foreach ($header as $col) {
            $this->SetFont("Helvetica", "B", 9);
            $this->Cell($ancho, 7, utf8_decode($col), 0);
        }
        $this->Ln();
        // Datos
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->SetFont("Helvetica", "", 10);
                $this->Cell($ancho, 6, utf8_decode($col), 0);
            }
            $this->Ln();
        }
    }

    public function multiceldaConTitulo($titulo, $txt) {
        $this->Ln();
        $this->SetFont("Helvetica", "B", 9);
        $this->Cell(0, 7, utf8_decode($titulo));
        $this->Ln(4);
        $this->SetFont("Helvetica", "", 10);
        $this->MultiCell(0, 7, utf8_decode($txt));
    }

    public function imagenConTiuloYSubtitulo($url, $titulo, $subtitulo, $y) {
        $this->Ln();
        $this->SetFont("Helvetica", "B", 9);
        $this->Cell(0, 7, $titulo, 0, 0, 'C');
        $this->Ln(4);
        $x = ($this->GetPageWidth() - 54) / 2;
        $this->Image("." . $url, $x, $y, 60, 0, 'PNG');
        $y = $this->GetY() + 40;
        $this->SetY($y);
        $this->SetFont("Helvetica", "", 10);
        $this->Cell(0, 7, $subtitulo, 0, 0, 'C');
    }

    public function tablaImagenes(array $imagenes) {
        $this->Ln(7);
        $countFilas = ((count($imagenes) / 4) < 0.5) ? round(count($imagenes) / 4, 0, PHP_ROUND_HALF_UP) + 1 : ceil(count($imagenes) / 4);
        $columna = 0;
        $listaImagenes = array();
        $tempImagenes = array();

        for ($j = 0; $j < $countFilas; $j++) {

            foreach ($imagenes as $key => $imagen) {
                if ($columna < 4) {
                    array_push($tempImagenes, $imagen);
                    $columna += 1;
                    unset($imagenes[$key]);
                }
            }
            array_push($listaImagenes, $tempImagenes);
            $tempImagenes = array();
            $columna = 0;
        }

        //insertar imagenes
        $ancho = $this->GetPageWidth() - 20;
        $y = $this->GetY();
        $x = 10;
        foreach ($listaImagenes as $imagenes) {
            foreach ($imagenes as $imagen) {
                if ($x < $ancho) {
                    $this->Image('.' . $imagen, $x, $y, 40, 35, 'JPG');
                    $x += 50;
                }
            }
            $x = 10;
            $y += 40;
            $altura = $y + 35;
            if ($altura > ($this->GetPageHeight() - 40)) {
                $this->AddPage();
                $y = 25;
            }
        }
        $this->SetY($y);
    }

}

class NumeroALetras {

    private static $UNIDADES = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];
    private static $DECENAS = [
        'VENTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];
    private static $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];

    public static function convertir($number, $moneda = '', $centimos = '', $forzarCentimos = false) {
        $converted = '';
        $decimales = '';
        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }
        $div_decimales = explode('.', $number);
        if (count($div_decimales) > 1) {
            $number = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if (strlen($decNumberStr) == 2) {
                $decNumberStrFill = str_pad($decNumberStr, 9, '0', STR_PAD_LEFT);
                $decCientos = substr($decNumberStrFill, 6);
                $decimales = self::convertGroup($decCientos);
            }
        } else if (count($div_decimales) == 1 && $forzarCentimos) {
            $decimales = 'CERO ';
        }
        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);
        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }
        }
        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }
        }
        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }
        if (empty($decimales)) {
            $valor_convertido = $converted . strtoupper($moneda);
        } else {
            $valor_convertido = $converted . strtoupper($moneda) . ' CON ' . $decimales . ' ' . strtoupper($centimos);
        }
        return $valor_convertido;
    }

    private static function convertGroup($n) {
        $output = '';
        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];
        }
        $k = intval(substr($n, 1));
        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if (($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }
        return $output;
    }

}
