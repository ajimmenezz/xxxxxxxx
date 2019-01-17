<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_SAE7 extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /* Encargado de hacer la consulta de los almacenes virtuales de SAE */

    public function getAlmacenesSAE() {
        $query = "select
        CVE_ALM as Id,
        DESCR as Almacen,
        ENCARGADO as Encargado,
        CASE
            WHEN STATUS = 'A' THEN 'Activo'
            WHEN STATUS <> 'A' THEN 'Inactivo'
        END as Estatus
        from ALMACENES03
        order by Almacen;";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    /* Encargado de hacer la consulta de los almacenes virtuales de SAE */

    public function getInventarioAlamacenSAE($almacen) {
        $query = "select
        producto.CVE_ART as Clave,
        producto.DESCR as Producto,
        linea.DESC_LIN as Linea,
        producto.UNI_MED as Unidad,
        multi.EXIST as Existencia,
        producto.COMP_X_REC,
        producto.ULT_COSTO as Costo
        from MULT03 multi inner join INVE03 producto
        on multi.CVE_ART = producto.CVE_ART
        LEFT JOIN CLIN03 linea
        on producto.LIN_PROD = linea.CVE_LIN
        where multi.CVE_ALM = '" . $almacen . "' and multi.EXIST > 0;";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    /*
     * Encargado de consultar la tabla dependiendo de la sentencia
     * @param string $sentencia recibe la sentencia para hacer la consulta
     * @return array regresa todos los datos de una o varias tablas
     */

    public function insertarSeguimiento(string $tabla, array $datos) {
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 0');
        $consulta = $this->insertar($tabla, $datos);

        if (!empty($consulta)) {
            $Id = parent::connectDBPrueba()->insert_id();
            parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 1');
            return $Id;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar
     *  @param string $tabla = tabla en la BD
     *  @param string $datos = datos para actualizar
     *  @param string $where = id que necesitamos para saber que campos se modificaran
     *  @return boolean TRUE si fue correcto de lo contrario el tipo de error
     */

    public function actualizarSeguimiento(string $tabla, array $datos, array $where = null) {
        $consulta = $this->actualizar($tabla, $datos, $where);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return parent::tipoError();
        }
    }

    /*
     * Encargado de unir tablas para mostrar los datos
     * 
     * @return array regresa todos los datos de una o varias tablas
     */

    public function consultaGeneralSeguimiento(string $sentencia) {
        $consulta = $this->consulta($sentencia);
        return $consulta;
    }

    /*
     * Encargado de unir tablas para mostrar los datos
     * 
     * @return array regresa todos los datos de una o varias tablas
     */

    public function consultaQuery(string $sentencia) {
        $consulta = parent::connectDBPrueba()->query($sentencia);
        return $consulta;
    }

    /*
     * Encargado de eliminar informacion de la tabla
     */

    public function eliminarDatos(string $tabla, array $where) {
        $consulta = $this->eliminar($tabla, $where);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /* Encargado de hacer la consulta a la BD de SAE */

    public function consultaBDSAE($query) {
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaProveedoresSAE() {
        $query = "select 
                    CLAVE,
                    NOMBRE,
                    concat(CALLE, ' ', NUMEXT, ' ', COLONIA, ' CP ', CODIGO) AS DIRECCION
                from PROV03
                where STATUS = 'A'
                order by NOMBRE asc";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaAlmacenesSAE() {
        $query = "select 
                    CVE_ALM,
                    DESCR,
                    DIRECCION
                  from ALMACENES03
                  where STATUS = 'A'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaProductosSAE() {
        $query = "select
                    producto.CVE_ART,
                    producto.DESCR,
                    producto.LIN_PROD,
                    producto.UNI_MED,
                    producto.UNI_ALT,
                    producto.ULT_COSTO,
                    producto.FAC_CONV,
                    (producto.ULT_COSTO*producto.FAC_CONV) as COSTO_UNIDAD
                    from INVE03 producto
                    where producto.STATUS = 'A'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaListaOrdenesCompra(string $whereFecha) {
        parent::connectDBSAE7()->query("SET language us_english");

        $query = "SELECT 
                    COMP.CVE_DOC,
                    PROV.NOMBRE,
                    COMP.SU_REFER,
                    COMP.FECHA_DOC,
                    COMP.FECHA_REC,
                    COMP.SERIE,
                    COMP.FOLIO,
                    COMP.IMPORTE,
                    COMP.TOT_IND + COMP.IMPORTE AS TOTALDOCTO,
                    CASE COMP.STATUS
                            WHEN 'E' THEN 'Emitida'
                            WHEN 'O' THEN 'Original'
                            WHEN 'C' THEN 'Comprada'
                        END as STATUS
                FROM COMPO03 COMP                                  
                LEFT JOIN COMPO_CLIB03 COMPCLIB 
                ON (COMP.CVE_DOC = COMPCLIB.CLAVE_DOC) 
                LEFT JOIN PROV03 PROV 
                ON PROV.CLAVE = COMP.CVE_CLPV "
                . $whereFecha;

        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaUltimaClaveDocumentacion() {
        $query = "select 
                    ULT_DOC + 1 as ULT_DOC,
                    CONCAT(CONVERT(date, GETDATE()),' 00:00:00.000') as FECH_ULT_DOC,
                    CONCAT(SERIE, REPLICATE('0',10-LEN(ULT_DOC)), ULT_DOC + 1) as CVE_DOC,
                    CONCAT(SERIE, ULT_DOC + 1) as CVE_GAPSI
                from FOLIOSC03 
                where TIP_DOC = 'o' 
                and SERIE = 'OC'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaTipoMoneda() {
        $query = "SELECT TOP (1000) 
                    NUM_MONED,
                    DESCR,
                    TCAMBIO
                FROM MONED03
                WHERE NUM_MONED IN(1,2)";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaRequisiciones() {
        $query = "SELECT 
                    CVE_DOC,CVE_CLPV,FECHA_DOC,CAN_TOT,IMPORTE 
                    FROM COMPQ03  
                    WHERE (TIP_DOC= 'q'  AND BLOQ <>  'S'  AND STATUS <>  'C'  AND ENLAZADO <>  'T' )";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaRequisicionesOrdenCompra(string $ordenCompra) {
        $query = "SELECT 
                    CVE_DOC,CVE_CLPV,FECHA_DOC,CAN_TOT,IMPORTE 
                    FROM COMPQ03  
                    WHERE (TIP_DOC= 'q'  AND BLOQ <>  'S'  AND STATUS <>  'C'  AND ENLAZADO <>  'T' )
                    OR DOC_SIG = '" . $ordenCompra . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaPartidasOrdenCompraAnteriores(string $ordenCompra) {
        $query = "SELECT 
                    partida.CANT,
                    partida.CVE_ART,
                    partida.UNI_VENTA,
                    partida.DESCU,
                    partida.IMPU1,
                    partida.IMPU2,
                    partida.IMPU3,
                    partida.IMPU4 as IVA,
                    producto.ULT_COSTO,
                    partida.CANT * producto.ULT_COSTO as Subtotal,
                    partida.NUM_PAR
                  FROM DOCTOSIGC03 doctos
                  INNER JOIN PAR_COMPQ03 partida
                  ON doctos.CVE_DOC_E = partida.CVE_DOC AND doctos.PARTIDA = partida.NUM_PAR
                  left join INVE03 producto 
                  on partida.CVE_ART = producto.CVE_ART
                  WHERE doctos.CVE_DOC = '" . $ordenCompra . "'
                  order by partida.NUM_PAR asc";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaListaRequisiciones(string $claveDocumento) {
        $query = "select
                    partida.CANT,
                    partida.CVE_ART,
                    partida.UNI_VENTA,
                    partida.DESCU,
                    partida.IMPU1,
                    partida.IMPU2,
                    partida.IMPU3,
                    partida.IMPU4 as IVA,
                    producto.ULT_COSTO,
                    partida.CANT * producto.ULT_COSTO as Subtotal,
                    partida.NUM_PAR
                from PAR_COMPQ03 partida
                left join INVE03 producto on partida.CVE_ART = producto.CVE_ART
                where partida.CVE_DOC = '" . $claveDocumento . "'
                and partida.PXR > 0
                order by partida.NUM_PAR asc";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function guardarOrdenCompra(array $datos, array $arraySubtotal) {
        $this->iniciaTransaccionSAE();

        $ultimaClaveTBLCONTROLTabla32 = $this->consultaUltimaClaveTBLCONTROL('32');
        $nuevaClaveTabla32 = $ultimaClaveTBLCONTROLTabla32[0]['ULT_CVE'] + 1;

        $this->actualizarUltimaClaveTBLCONTROL(array(
            'nuevaClave' => $nuevaClaveTabla32,
            'claveAnterior' => $ultimaClaveTBLCONTROLTabla32[0]['ULT_CVE'],
            'numeroTabla' => '32'));

        $this->actualizarUltimoDocumento($datos['folio']);

        $ultimaClaveTBLCONTROLTabla57 = $this->consultaUltimaClaveTBLCONTROL('57');
        $nuevaClaveTabla57 = $ultimaClaveTBLCONTROLTabla57[0]['ULT_CVE'] + 1;

        $this->actualizarUltimaClaveTBLCONTROL(array(
            'nuevaClave' => $nuevaClaveTabla57,
            'claveAnterior' => $ultimaClaveTBLCONTROLTabla57[0]['ULT_CVE'],
            'numeroTabla' => '57'));

        $this->insertarObservacionesDocumento(array(
            'claveObservaciones' => $nuevaClaveTabla57,
            'observaciones' => $datos['observaciones']
        ));

        $diaPrimeroMes = $this->consultaPrimerDiaMes();

        $this->actualizarCantidadesACOMP(array(
            'subtotal' => $arraySubtotal['subtotal'],
            'iva' => $arraySubtotal['iva'],
            'descuento' => $arraySubtotal['descuento'],
            'descuentoFinanciero' => $datos['descuentoFinanciero'],
            'fechaMes' => $diaPrimeroMes[0]['PrimerDiaMes']));

        if ($datos['orden'] === 'Requisicion') {
            $orden = 'q';
            $TIP_DOC_ANT = 'q';
            $DOC_ANT = $datos['requisicion'];
        } else {
            $orden = 'O';
            $TIP_DOC_ANT = '';
            $DOC_ANT = '';
        }

        $this->insertarCOMPO(array(
            'claveDocumento' => $datos['claveNuevaDocumentacion'],
            'claveProvedor' => $datos['proveedor'],
            'referencia' => $datos['referencia'],
            'fechaDoc' => $datos['fecha'],
            'fechaRec' => $datos['fechaRec'],
            'cantidadTotal' => $arraySubtotal['subtotal'],
            'iva' => $arraySubtotal['iva'],
            'descuentoTotal' => $arraySubtotal['descuento'],
            'descuentoFinanciero' => $datos['descuentoFinanciero'],
            'observacionesCond' => $datos['entregaA'],
            'claveObservaciones' => $nuevaClaveTabla57,
            'folio' => $datos['folio'],
            'descuentoTotalParc' => $datos['descuento'],
            'importe' => $arraySubtotal['total'],
            'almacen' => $datos['almacen'],
            'moneda' => $datos['moneda'],
            'tipoCambio' => $datos['tipoCambio'],
            'orden' => $orden,
            'TIP_DOC_ANT' => $TIP_DOC_ANT,
            'DOC_ANT' => $DOC_ANT
        ));

        $consultaCampoClib = $this->consultaCOMPO_CLIB($datos['claveNuevaDocumentacion']);

        $arrayCampoClib = array(
            'camplib1' => substr($datos['textoProyectoGapsi'], 0, 15),
            'camplib2' => $datos['direccionEntrega']
        );

        if (empty($consultaCampoClib)) {
            $this->insertCOMPO_CLIB($datos['claveNuevaDocumentacion'], $arrayCampoClib);
        } else {
            $this->actualizarCAMPO_CLIB($datos['claveNuevaDocumentacion'], $arrayCampoClib);
        }

        if ($datos['orden'] === 'Requisicion') {
            $this->actualizarCOMPQ(array(
                'claveDocumento' => $datos['claveNuevaDocumentacion'],
                'requisicion' => $datos['requisicion']
            ));
        }

        foreach ($datos['datosTabla'] as $key => $value) {
            $facConv = $this->consultaFAC_CONV_INVE($value['producto']);
            $this->actualizarINVE(array(
                'claveArticulo' => $value['producto'],
                'cantidad' => $value['cantidad']
            ));

            if (isset($value['observacionesPartida'])) {
                $ultimaClaveTBLCONTROLTabla57Partida = $this->consultaUltimaClaveTBLCONTROL('57');
                $nuevaClaveTabla57Partida = $ultimaClaveTBLCONTROLTabla57Partida[0]['ULT_CVE'] + 1;

                $this->actualizarUltimaClaveTBLCONTROL(array(
                    'nuevaClave' => $nuevaClaveTabla57Partida,
                    'claveAnterior' => $ultimaClaveTBLCONTROLTabla57Partida[0]['ULT_CVE'],
                    'numeroTabla' => '57'));

                $this->insertarObservacionesDocumento(array(
                    'claveObservaciones' => $nuevaClaveTabla57Partida,
                    'observaciones' => $value['observacionesPartida']
                ));
            } else {
                $nuevaClaveTabla57Partida = 0;
            }

            $this->insertPARCOMPO(array(
                'claveDocumento' => $datos['claveNuevaDocumentacion'],
                'numeroPartida' => $key + 1,
                'claveArticulo' => $value['producto'],
                'facConv' => $facConv[0]['FAC_CONV'],
                'cantidad' => $value['cantidad'],
                'precio' => $value['costoUnidad'],
                'esquema' => $datos['esquema'],
                'iva' => $iva = number_format($value['subtotalPartida'] * (int) $datos['esquema'] / 100, 2, ".", ""),
                'unidad' => $value['unidad'],
                'importe' => $value['subtotalPartida'],
                'almacen' => $datos['almacen'],
                'tipoCambio' => $datos['tipoCambio'],
                'claveObservaciones' => $nuevaClaveTabla57Partida,
                'descuento' => $value['descuento']
            ));

            $this->insertPAR_COMPO_CLIB(array(
                'claveDocumento' => $datos['claveNuevaDocumentacion'],
                'numeroPartida' => $key + 1
            ));

            if ($datos['orden'] === 'Requisicion') {
                $this->actualizarPAR_COMPQ(array(
                    'cantidad' => $value['cantidad'],
                    'claveArticulo' => $value['producto'],
                    'claveDocumento' => $datos['requisicion'],
                    'partidaRequisicion' => $value['partidaRequisicion']
                ));

                $this->actualizarMult(array(
                    'cantidad' => $value['cantidad'],
                    'claveArticulo' => $value['producto'],
                    'almacen' => $datos['almacen']
                ));

                $this->actualizarCOMPQPartida(array(
                    'requisicion' => $datos['requisicion']
                ));

                $arrayDOCTOSIGC = array(
                    'requisicion' => $datos['requisicion'],
                    'claveDocumento' => $datos['claveNuevaDocumentacion'],
                    'numeroPartida' => $key + 1,
                    'partidaRequisicion' => $value['partidaRequisicion'],
                    'cantidad' => $value['cantidad']
                );

                $this->insertDOCTOSIGC1($arrayDOCTOSIGC);
                $this->insertDOCTOSIGC2($arrayDOCTOSIGC);
            }
        }

        $this->terminaTransaccionSAE();

        if ($this->estatusTransaccionSAE() === FALSE) {
            $this->roolbackTransaccionSAE();
        } else {
            return TRUE;
        }
    }

    public function actualizarOrdenCompra(array $datos, array $arraySubtotal) {
        $this->iniciaTransaccionSAE();

        if ($datos['orden'] === 'Requisicion') {
            $orden = 'q';
            $TIP_DOC_ANT = 'q';
            $DOC_ANT = $datos['requisicion'];
            $datosOrdenCompra = $this->consultaCOMPO($datos['claveNuevaDocumentacion']);

            $this->actualizarCOMPQPartidaEnlazado(array(
                'requisicion' => $datosOrdenCompra[0]['DOC_ANT']
            ));


            $datosDOCTO = $this->consultaDOCTOSIG_CVE_ART($datos['claveNuevaDocumentacion']);

            foreach ($datosDOCTO as $key => $value) {
                $this->actualizarPAR_COMPQPXR(array(
                    'cantidad' => $value['CANT_E'],
                    'claveArticulo' => $value['CVE_ART'],
                    'claveDocumento' => $value['CVE_DOC'],
                    'partidaRequisicion' => $value['PARTIDA']
                ));
            }
        } else {
            $orden = 'O';
            $TIP_DOC_ANT = '';
            $DOC_ANT = '';
        }

        $ultimaClaveTBLCONTROLTabla32 = $this->consultaUltimaClaveTBLCONTROL('32');
        $nuevaClaveTabla32 = $ultimaClaveTBLCONTROLTabla32[0]['ULT_CVE'] + 1;

        $this->actualizarUltimaClaveTBLCONTROL(array(
            'nuevaClave' => $nuevaClaveTabla32,
            'claveAnterior' => $ultimaClaveTBLCONTROLTabla32[0]['ULT_CVE'],
            'numeroTabla' => '32'));

        $this->actualizarUltimoDocumento($datos['folio'], $datos['fecha']);

        $ultimaClaveTBLCONTROLTabla57 = $this->consultaUltimaClaveTBLCONTROL('57');
        $nuevaClaveTabla57 = $ultimaClaveTBLCONTROLTabla57[0]['ULT_CVE'] + 1;

        $this->actualizarUltimaClaveTBLCONTROL(array(
            'nuevaClave' => $nuevaClaveTabla57,
            'claveAnterior' => $ultimaClaveTBLCONTROLTabla57[0]['ULT_CVE'],
            'numeroTabla' => '57'));

        $this->insertarObservacionesDocumento(array(
            'claveObservaciones' => $nuevaClaveTabla57,
            'observaciones' => $datos['observaciones']
        ));

        $diaPrimeroMes = $this->consultaPrimerDiaMes();

        $this->actualizarCantidadesACOMP(array(
            'subtotal' => $arraySubtotal['subtotal'],
            'iva' => $arraySubtotal['iva'],
            'descuento' => $arraySubtotal['descuento'],
            'descuentoFinanciero' => $datos['descuentoFinanciero'],
            'fechaMes' => $diaPrimeroMes[0]['PrimerDiaMes']));

        $this->actualizarCOMPO(array(
            'claveDocumento' => $datos['claveNuevaDocumentacion'],
            'claveProvedor' => $datos['proveedor'],
            'referencia' => $datos['referencia'],
            'fechaDoc' => $datos['fecha'],
            'fechaRec' => $datos['fechaRec'],
            'cantidadTotal' => $arraySubtotal['subtotal'],
            'iva' => $arraySubtotal['iva'],
            'descuentoTotal' => $arraySubtotal['descuento'],
            'descuentoFinanciero' => $datos['descuentoFinanciero'],
            'observacionesCond' => $datos['entregaA'],
            'claveObservaciones' => $nuevaClaveTabla57,
            'folio' => $datos['folio'],
            'descuentoTotalParc' => $datos['descuento'],
            'importe' => $arraySubtotal['total'],
            'almacen' => $datos['almacen'],
            'moneda' => $datos['moneda'],
            'tipoCambio' => $datos['tipoCambio'],
            'orden' => $orden,
            'TIP_DOC_ANT' => $TIP_DOC_ANT,
            'DOC_ANT' => $DOC_ANT
        ));

        $arrayCampoClib = array(
            'camplib1' => substr($datos['textoProyectoGapsi'], 0, 15),
            'camplib2' => $datos['direccionEntrega']
        );

        $this->actualizarCAMPO_CLIB($datos['claveNuevaDocumentacion'], $arrayCampoClib);

        if ($datos['orden'] === 'Requisicion') {
            $this->actualizarCOMPQ(array(
                'claveDocumento' => $datos['claveNuevaDocumentacion'],
                'requisicion' => $datos['requisicion']
            ));
        }

        $this->eliminarPAR_COMPO_CLIB(array(
            'claveDocumento' => $datos['claveNuevaDocumentacion']));

        $this->eliminarPARCOMPO(array(
            'claveDocumento' => $datos['claveNuevaDocumentacion']));

        foreach ($datos['datosTabla'] as $key => $value) {
            $facConv = $this->consultaFAC_CONV_INVE($value['producto']);
            $this->actualizarINVE(array(
                'claveArticulo' => $value['producto'],
                'cantidad' => $value['cantidad']
            ));

            if (isset($value['observacionesPartida'])) {
                $ultimaClaveTBLCONTROLTabla57Partida = $this->consultaUltimaClaveTBLCONTROL('57');
                $nuevaClaveTabla57Partida = $ultimaClaveTBLCONTROLTabla57Partida[0]['ULT_CVE'] + 1;

                $this->actualizarUltimaClaveTBLCONTROL(array(
                    'nuevaClave' => $nuevaClaveTabla57Partida,
                    'claveAnterior' => $ultimaClaveTBLCONTROLTabla57Partida[0]['ULT_CVE'],
                    'numeroTabla' => '57'));

                $this->insertarObservacionesDocumento(array(
                    'claveObservaciones' => $nuevaClaveTabla57Partida,
                    'observaciones' => $value['observacionesPartida']
                ));
            } else {
                $nuevaClaveTabla57Partida = 0;
            }

            $this->insertPARCOMPO(array(
                'claveDocumento' => $datos['claveNuevaDocumentacion'],
                'numeroPartida' => $key + 1,
                'claveArticulo' => $value['producto'],
                'facConv' => $facConv[0]['FAC_CONV'],
                'cantidad' => $value['cantidad'],
                'precio' => $value['costoUnidad'],
                'esquema' => $datos['esquema'],
                'iva' => $iva = number_format($value['subtotalPartida'] * (int) $datos['esquema'] / 100, 2, ".", ""),
                'unidad' => $value['unidad'],
                'importe' => $value['subtotalPartida'],
                'almacen' => $datos['almacen'],
                'tipoCambio' => $datos['tipoCambio'],
                'claveObservaciones' => $nuevaClaveTabla57Partida,
                'descuento' => $value['descuento']
            ));

            $this->insertPAR_COMPO_CLIB(array(
                'claveDocumento' => $datos['claveNuevaDocumentacion'],
                'numeroPartida' => $key + 1
            ));

            if ($datos['orden'] === 'Requisicion') {
                $this->actualizarPAR_COMPQ(array(
                    'cantidad' => $value['cantidad'],
                    'claveArticulo' => $value['producto'],
                    'claveDocumento' => $datos['requisicion'],
                    'partidaRequisicion' => $value['partidaRequisicion']
                ));

                $this->actualizarMult(array(
                    'cantidad' => $value['cantidad'],
                    'claveArticulo' => $value['producto'],
                    'almacen' => $datos['almacen']
                ));

                $this->actualizarCOMPQPartida(array(
                    'requisicion' => $datos['requisicion']
                ));

                $arrayDOCTOSIGC = array(
                    'requisicion' => $datos['requisicion'],
                    'claveDocumento' => $datos['claveNuevaDocumentacion'],
                    'numeroPartida' => $key + 1,
                    'partidaRequisicion' => $value['partidaRequisicion'],
                    'cantidad' => $value['cantidad']
                );

                $this->eliminarDOCTOSIGC1(array(
                    'claveDocumento' => $datos['claveNuevaDocumentacion']));

                $this->eliminarDOCTOSIGC2(array(
                    'claveDocumento' => $datos['claveNuevaDocumentacion']));

                $this->insertDOCTOSIGC1($arrayDOCTOSIGC);
                $this->insertDOCTOSIGC2($arrayDOCTOSIGC);
            }
        }

        $this->terminaTransaccionSAE();

        if ($this->estatusTransaccionSAE() === FALSE) {
            $this->roolbackTransaccionSAE();
        } else {
            return TRUE;
        }
    }

    public function consultaUltimaClaveTBLCONTROL(string $numeroTabla) {
        $consulta = parent::connectDBSAE7()->query("SELECT ULT_CVE FROM TBLCONTROL03 WHERE ID_TABLA = '" . $numeroTabla . "'");
        return $consulta->result_array();
    }

    public function consultaUltimoNumeroDocumento() {
        $query = "SELECT 
                    SERIE,
                    TIP_DOC,
                    FOLIODESDE, 
                    FOLIOHASTA,
                    ULT_DOC, 
                    FECH_ULT_DOC                  
                FROM FOLIOSC03                  
                WHERE TIP_DOC = 'o'                   
                AND SERIE = 'STAND.'								 
                GROUP BY 
                TIP_DOC,SERIE, 
                FOLIODESDE, 
                FOLIOHASTA , 
                ULT_DOC, 
                FECH_ULT_DOC";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaPrimerDiaMes() {
        $consulta = parent::connectDBSAE7()->query("SELECT DATEADD(MM, DATEDIFF(MM,0,GETDATE()), 0) AS 'PrimerDiaMes';");
        return $consulta->result_array();
    }

    public function consultaFAC_CONV_INVE(string $producto) {
        $consulta = parent::connectDBSAE7()->query("SELECT INVE.FAC_CONV
                                                    FROM INVE03 INVE
                                                    WHERE INVE.CVE_ART = '" . $producto . "'");
        return $consulta->result_array();
    }

    public function consultaCOMPO(string $claveDocumento) {
        $consulta = parent::connectDBSAE7()->query("select * from COMPO03 where CVE_DOC = '" . $claveDocumento . "'");
        return $consulta->result_array();
    }

    public function consultaPAR_COMPO(string $claveDocumento) {
        $consulta = parent::connectDBSAE7()->query("select 
                                                        partidas.*,
                                                        TOT_PARTIDA as Subtotal 
                                                    from PAR_COMPO03 partidas
                                                    where CVE_DOC = '" . $claveDocumento . "'");
        return $consulta->result_array();
    }

    public function consultaDOCTOSIG_CVE_ART(string $claveDocumento) {
        $consulta = parent::connectDBSAE7()->query("select 
                                                        (select CVE_ART from PAR_COMPO03 where CVE_DOC = '" . $claveDocumento . "' and NUM_PAR = docto.PART_E) CVE_ART,
                                                        docto.CANT_E,
                                                        docto.CVE_DOC_E,
                                                        docto.PARTIDA,
                                                        docto.CVE_DOC
                                                    from DOCTOSIGC03 docto
                                                    where CVE_DOC = '" . $claveDocumento . "'");
        return $consulta->result_array();
    }

    public function consultaCOMPO_CLIB(string $claveDocumento) {
        $consulta = parent::connectDBSAE7()->query("select * from COMPO_CLIB03 where CLAVE_DOC = '" . $claveDocumento . "'");
        return $consulta->result_array();
    }

    public function consultaOBS_DOCC(string $claveObservaciones) {
        $consulta = parent::connectDBSAE7()->query("select * from OBS_DOCC03 where CVE_OBS = '" . $claveObservaciones . "'");
        return $consulta->result_array();
    }

    public function consultaPartidasEditar(string $claveDocumentacion) {
        $consulta = parent::connectDBSAE7()->query("SELECT 
                                                    	partidas.CANT,
                                                        partidas.CVE_ART,
                                                        partidas.UNI_VENTA,
                                                        partidas.DESCU,
                                                        partidas.IMPU1,
                                                        partidas.IMPU2,
                                                        partidas.IMPU3,
                                                        partidas.IMPU4 as IVA,
                                                        partidas.TOT_PARTIDA as Subtotal,
                                                        partidas.NUM_PAR
                                                FROM COMPQ03 requis
                                                inner join PAR_COMPQ03 partidas on requis.CVE_DOC = partidas.CVE_DOC
                                                inner join DOCTOSIGC03 doctos on doctos.CVE_DOC_E = partidas.CVE_DOC and doctos.PARTIDA = partidas.NUM_PAR
                                                where requis.DOC_SIG  = '" . $claveDocumentacion . "' and doctos.CVE_DOC =  '" . $claveDocumentacion . "'");
        return $consulta->result_array();
    }

    public function actualizarUltimaClaveTBLCONTROL(array $datos) {
        parent::connectDBSAE7()->query("update TBLCONTROL03
                set ULT_CVE = '" . $datos['nuevaClave'] . "'
                where ID_TABLA = '" . $datos['numeroTabla'] . "'
                AND ULT_CVE = '" . $datos['claveAnterior'] . "'");
    }

    public function actualizarUltimoDocumento(string $nuevoUltimoDocumento) {
        $fechaDocumento = mdate('%Y-%d-%m %H:%i:%s', now('America/Mexico_City'));

        $query = "UPDATE FOLIOSC03 
                    SET ULT_DOC=(CASE WHEN ULT_DOC < '" . $nuevoUltimoDocumento . "' THEN '" . $nuevoUltimoDocumento . "' ELSE ULT_DOC END),                      
                    FECH_ULT_DOC= '" . $fechaDocumento . "'
                WHERE TIP_DOC = N'o'  
                AND SERIE = N'OC'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function actualizarCantidadesACOMP(array $datos) {
        $query = "UPDATE ACOMP03 
                    SET 
                    OVTA_COM =OVTA_COM +  " . $datos['subtotal'] . ",
                    ODESCTO =ODESCTO +  " . (float) $datos['descuento'] . ",
                    ODES_FIN =ODES_FIN +  " . (float) $datos['descuentoFinanciero'] . ",
                    OIMP =OIMP + " . (float) $datos['iva'] . "   
                    WHERE PER_ACUM =  '" . $datos['fechaMes'] . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function actualizarINVE(array $datos) {
        $query = "UPDATE INVE03                      
            SET COMP_X_REC = 
            (CASE WHEN COMP_X_REC + " . $datos['cantidad'] . "  < 0 THEN 0                                       
            WHEN COMP_X_REC + " . $datos['cantidad'] . "  >= 0 THEN COMP_X_REC + " . $datos['cantidad'] . "                                        
            ELSE 0 END)                      
            WHERE CVE_ART = N' " . $datos['claveArticulo'] . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function actualizarCAMPO_CLIB(string $claveDocumento, array $arrayCampos) {
        $query = "UPDATE COMPO_CLIB03                      
                SET 
                    CAMPLIB1 = '" . $arrayCampos['camplib1'] . "',
                    CAMPLIB2 = '" . $arrayCampos['camplib2'] . "'             
                WHERE CLAVE_DOC = '" . $claveDocumento . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function actualizarPAR_COMPQ(array $datos) {
        $query = "UPDATE PAR_COMPQ03  
                    SET PXR = (CASE 
                        WHEN PXR < " . $datos['cantidad'] . " /*Cantidad de partida*/ 
                                THEN 0                 
                        ELSE PXR - " . $datos['cantidad'] . " /*Cantidad de partida*/ 
                        END) 
                WHERE CVE_DOC = '" . $datos['claveDocumento'] . "'   
                AND NUM_PAR = " . $datos['partidaRequisicion'] . "
                AND CVE_ART = '" . $datos['claveArticulo'] . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function actualizarPAR_COMPQPXR(array $datos) {
        $query = "UPDATE PAR_COMPQ03  
                    SET PXR = " . $datos['cantidad'] . "
                WHERE CVE_DOC = '" . $datos['claveDocumento'] . "'   
                AND NUM_PAR = " . $datos['partidaRequisicion'] . "
                AND CVE_ART = '" . $datos['claveArticulo'] . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function actualizarMult(array $datos) {
        $query = "UPDATE MULT03                  
                    SET COMP_X_REC = COMP_X_REC + " . $datos['cantidad'] . " /*Cantidad den partida*/                     
                    WHERE CVE_ART = '" . $datos['claveArticulo'] . "'   
                    AND CVE_ALM = " . $datos['almacen'] . " /*Almacén seleccionado en formulario*/";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function actualizarCOMPQPartida(array $datos) {
        $query = "UPDATE 
                COMPQ03                 
                SET TIP_DOC_E =  'o', 
                ENLAZADO = (
                    CASE 
                            WHEN (
                                    SELECT 
                                    SUM(P.PXR) 
                                    FROM PAR_COMPQ03 P 
                                    WHERE P.CVE_DOC= '" . $datos['requisicion'] . "' /*Requisición*/ 
                                    AND COMPQ03.CVE_DOC = P.CVE_DOC)=0 
                            THEN 'T'                     
                            WHEN (
                                    SELECT 
                                    SUM(P.PXR) 
                                    FROM PAR_COMPQ03 P 
                                    WHERE P.CVE_DOC= '" . $datos['requisicion'] . "' /*Requisición*/
                    AND COMPQ03.CVE_DOC = P.CVE_DOC)>0 
                                    THEN 'P'                     
                                    ELSE ENLAZADO 
                            END)                     
                WHERE COMPQ03.CVE_DOC =  '" . $datos['requisicion'] . "' /*Requisición*/";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function actualizarCOMPQPartidaEnlazado(array $datos) {
        $query = "UPDATE 
                COMPQ03                 
                SET ENLAZADO = 'P'                          
                WHERE CVE_DOC =  '" . $datos['requisicion'] . "' /*Requisición*/";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function actualizarCOMPQ(array $datos) {
        $query = "UPDATE COMPQ03                    
                    SET DOC_SIG =  '" . $datos['claveDocumento'] . "', 
                    TIP_DOC_SIG =  'o'                       
                    WHERE CVE_DOC =  '" . $datos['requisicion'] . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function actualizarCOMPO(array $datos) {
        parent::connectDBSAE7()->query("SET language us_english");
        $query = "update COMPO03 set
                    CVE_CLPV = '" . $datos['claveProvedor'] . "',
                    SU_REFER = '" . $datos['referencia'] . "', 
                    FECHA_DOC = GETDATE(), 
                    FECHA_REC = '" . $datos['fechaRec'] . "',
                    FECHA_PAG = EOMONTH(GETDATE()),
                    CAN_TOT = " . $datos['cantidadTotal'] . ", 
                    IMP_TOT4 = " . $datos['iva'] . ", 
                    DES_TOT = " . $datos['descuentoTotal'] . ", 
                    DES_FIN = " . $datos['descuentoFinanciero'] . ", 
                    OBS_COND = '" . $datos['observacionesCond'] . "', 
                    CVE_OBS = " . $datos['claveObservaciones'] . ", 
                    NUM_ALMA = " . $datos['almacen'] . ", 
                    TIP_DOC_E = '" . $datos['orden'] . "', 
                    NUM_MONED = " . $datos['moneda'] . ", 
                    TIPCAMB = " . $datos['tipoCambio'] . ", 
                    FECHAELAB = GETDATE(), 
                    FOLIO = " . $datos['folio'] . ", 
                    DES_FIN_PORC = " . $datos['descuentoFinanciero'] . ", 
                    DES_TOT_PORC = " . $datos['descuentoTotalParc'] . ", 
                    IMPORTE = " . $datos['importe'] . ", 
                    DOC_ANT = '" . $datos['DOC_ANT'] . "', 
                    TIP_DOC_ANT = '" . $datos['TIP_DOC_ANT'] . "'
                 where CVE_DOC =  '" . $datos['claveDocumento'] . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function insertarObservacionesDocumento(array $datos) {
        $query = "INSERT INTO OBS_DOCC03 
                    (CVE_OBS,STR_OBS) 
                    VALUES('" . $datos['claveObservaciones'] . "', '" . $datos['observaciones'] . "')";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function insertarCOMPO(array $datos) {
        parent::connectDBSAE7()->query("SET language us_english");
        $query = "insert into COMPO03
                    (TIP_DOC,
                     CVE_DOC, 
                     CVE_CLPV, 
                     STATUS, 
                     SU_REFER, 
                     FECHA_DOC, 
                     FECHA_REC, 
                     FECHA_PAG, 
                     CAN_TOT, 
                     IMP_TOT1, 
                     IMP_TOT2, 
                     IMP_TOT3, 
                     IMP_TOT4, 
                     DES_TOT, 
                     DES_FIN, 
                     OBS_COND, 
                     CVE_OBS, 
                     NUM_ALMA, 
                     ACT_CXP, 
                     ACT_COI, 
                     ENLAZADO, 
                     TIP_DOC_E, 
                     NUM_MONED, 
                     TIPCAMB, 
                     FECHAELAB, 
                     SERIE, 
                     FOLIO, 
                     CTLPOL, 
                     ESCFD, 
                     CONTADO, 
                     BLOQ, 
                     TOT_IND, 
                     DES_FIN_PORC, 
                     DES_TOT_PORC, 
                     IMPORTE, 
                     DOC_ANT, 
                     TIP_DOC_ANT)
                  values
                    ('o',
                    '" . $datos['claveDocumento'] . "',
                    '" . $datos['claveProvedor'] . "',
                    'O',
                    '" . $datos['referencia'] . "',
                    GETDATE(),
                    '" . $datos['fechaRec'] . "',
                    EOMONTH(GETDATE()),
                    " . $datos['cantidadTotal'] . ",
                    0,
                    0,
                    0,
                    " . $datos['iva'] . ",
                    " . $datos['descuentoTotal'] . ",
                    " . $datos['descuentoFinanciero'] . ",
                    '" . $datos['observacionesCond'] . "',
                    " . $datos['claveObservaciones'] . ",
                    " . $datos['almacen'] . ",
                    'S',
                    'N',
                    'O',
                    '" . $datos['orden'] . "',
                    " . $datos['moneda'] . ",
                    " . $datos['tipoCambio'] . ",
                    GETDATE(),
                    'OC',
                    " . $datos['folio'] . ",
                    0,
                    'N',
                    'N',
                    'N',
                    0,
                    " . $datos['descuentoFinanciero'] . ",
                    " . $datos['descuentoTotalParc'] . ",
                    " . $datos['importe'] . ",
                    '" . $datos['DOC_ANT'] . "',
                    '" . $datos['TIP_DOC_ANT'] . "')";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function insertCOMPO_CLIB(string $claveDocumento, array $arrayCampos) {
        $query = "insert into COMPO_CLIB03
                    (CLAVE_DOC,CAMPLIB1,CAMPLIB2)
                  values
                  ('" . $claveDocumento . "',
                   '" . $arrayCampos['camplib1'] . "',
                   '" . $arrayCampos['camplib2'] . "')";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function insertPARCOMPO(array $datos) {
        $query = "insert into PAR_COMPO03
                (CVE_DOC, NUM_PAR, CVE_ART, CANT, PXR, PREC, COST, IMPU1, IMPU2, IMPU3, IMPU4, IMP1APLA, IMP2APLA, IMP3APLA, IMP4APLA, TOTIMP1, TOTIMP2, TOTIMP3, TOTIMP4, DESCU, ACT_INV, NUM_ALM, TIP_CAM, UNI_VENTA, TIPO_PROD, TIPO_ELEM, CVE_OBS, REG_SERIE, E_LTPD, FACTCONV, MINDIRECTO, NUM_MOV, TOT_PARTIDA, MAN_IEPS, APL_MAN_IMP, CUOTA_IEPS, APL_MAN_IEPS, MTO_PORC, MTO_CUOTA, CVE_ESQ)
              values
                (N'" . $datos['claveDocumento'] . "',
                " . $datos['numeroPartida'] . ",
                '" . $datos['claveArticulo'] . "',
                " . $datos['cantidad'] . ",
                " . $datos['facConv'] . ",
                0,
                " . $datos['precio'] . ",
                0,
                0,
                0,
                " . $datos['esquema'] . ",
                6,
                6,
                6,
                0,
                0,
                0,
                0,
                " . $datos['iva'] . ",
                " . $datos['descuento'] . ",
                'N',
                " . $datos['almacen'] . ",
                " . $datos['tipoCambio'] . ",
                '" . $datos['unidad'] . "',
                'P',
                'N',
                " . $datos['claveObservaciones'] . ",
                0,
                0,
                " . $datos['facConv'] . ",
                0,
                0,
                '" . $datos['importe'] . "',
                'N',
                1,
                0,
                'C',
                0,
                0,
                1)";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function insertPAR_COMPO_CLIB(array $datos) {
        $query = "insert into PAR_COMPO_CLIB03
                    (CLAVE_DOC, NUM_PART)
                  values
                    ('" . $datos['claveDocumento'] . "'," . $datos['numeroPartida'] . ")";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function insertDOCTOSIGC1(array $datos) {
        $query = "INSERT INTO DOCTOSIGC03
                (TIP_DOC,CVE_DOC,ANT_SIG,TIP_DOC_E,CVE_DOC_E, PARTIDA, PART_E, CANT_E)                     
                VALUES(
                'q' , 
                '" . $datos['requisicion'] . "' /*Requisición*/ , 
                'S' , 
                'o' ,
                '" . $datos['claveDocumento'] . "' /*OC*/ , 
                " . $datos['numeroPartida'] . " /*Numero de Partida de la OC*/ , 
                " . $datos['partidaRequisicion'] . " /*Numero de partida de la Requisición*/ , 
                " . $datos['cantidad'] . " /*Cantidad de la partida*/  )";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function insertDOCTOSIGC2(array $datos) {
        $query = "INSERT INTO DOCTOSIGC03 
                    (TIP_DOC,CVE_DOC,ANT_SIG,TIP_DOC_E,CVE_DOC_E, PARTIDA, PART_E, CANT_E)                     
                    VALUES(
                    'o' , 
                    '" . $datos['claveDocumento'] . "' /*OC*/ ,  
                    'A' , 
                    'q' , 
                    '" . $datos['requisicion'] . "' /*Requisicióon*/ , 
                    " . $datos['partidaRequisicion'] . " /*Numero de partida de la Requisición*/ , 
                    " . $datos['numeroPartida'] . " /*Numero de Partida de la OC*/ , 
                    " . $datos['cantidad'] . " /*Cantidad de la partida*/)";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function eliminarPARCOMPO(array $datos) {
        $query = "DELETE FROM PAR_COMPO03 WHERE CVE_DOC = '" . $datos['claveDocumento'] . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function eliminarPAR_COMPO_CLIB(array $datos) {
        $query = "DELETE FROM PAR_COMPO_CLIB03 WHERE CLAVE_DOC = '" . $datos['claveDocumento'] . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function eliminarDOCTOSIGC1(array $datos) {
        $query = "DELETE FROM DOCTOSIGC03 WHERE CVE_DOC = '" . $datos['claveDocumento'] . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function eliminarDOCTOSIGC2(array $datos) {
        $query = "DELETE FROM DOCTOSIGC03 WHERE CVE_DOC_E = '" . $datos['claveDocumento'] . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

}
