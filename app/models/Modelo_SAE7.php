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
        from ALMACENES01
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
        from MULT01 multi inner join INVE01 producto
        on multi.CVE_ART = producto.CVE_ART
        LEFT JOIN CLIN01 linea
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
                from PROV01
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
                  from ALMACENES01
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
                    from INVE01 producto
                    where producto.STATUS = 'A'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaListaOrdenesCompra() {
        $query = "SELECT 
                    COMP.CVE_DOC,
                    PROV.NOMBRE,
                    COMP.STATUS,
                    COMP.SU_REFER,
                    COMP.FECHA_DOC,
                    COMP.FECHA_REC,
                    COMP.SERIE,
                    COMP.FOLIO,
                    COMP.IMPORTE,
                    COMP.TOT_IND + COMP.IMPORTE AS TOTALDOCTO 
                FROM COMPO01 COMP                                  
                LEFT JOIN COMPO_CLIB01 COMPCLIB 
                ON (COMP.CVE_DOC = COMPCLIB.CLAVE_DOC) 
                LEFT JOIN PROV01 PROV 
                ON PROV.CLAVE = COMP.CVE_CLPV";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaUltimaClaveDocumentacion() {
        $query = "SELECT 
                    TOP 1
                    COMP.CVE_DOC
                FROM COMPO01 COMP
                ORDER BY CVE_DOC DESC";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function guardarOrdenCompra(array $datos, array $arraySubtotal) {
        $this->iniciaTransaccion();

//        $ultimaClaveTBLCONTROLTabla32 = $this->consultaUltimaClaveTBLCONTROL('32');
//        $nuevaClaveTabla32 = $ultimaClaveTBLCONTROLTabla32[0]['ULT_CVE'] + 1;
//        $this->actualizarUltimaClaveTBLCONTROL(array(
//            'nuevaClave' => $nuevaClaveTabla32,
//            'claveAnterior' => $ultimaClaveTBLCONTROLTabla32[0]['ULT_CVE'],
//            'numeroTabla' => '32'));
//        $ultimoDocumento = $this->consultaUltimoNumeroDocumento();
//        $this->actualizarUltimoDocumento($ultimoDocumento[0]['ULT_DOC'] + 1);
//        $ultimaClaveTBLCONTROLTabla57 = $this->consultaUltimaClaveTBLCONTROL('57');
//        $nuevaClaveTabla57 = $ultimaClaveTBLCONTROLTabla57[0]['ULT_CVE'] + 1;
//
//        $this->actualizarUltimaClaveTBLCONTROL(array(
//            'nuevaClave' => $nuevaClaveTabla57,
//            'claveAnterior' => $ultimaClaveTBLCONTROLTabla57[0]['ULT_CVE'],
//            'numeroTabla' => '57'));
//
//        $this->insertarObservacionesDocumento(array(
//            'claveObservaciones' => $nuevaClaveTabla57,
//            'observaciones' => $datos['observaciones']
//        ));
//
//        $mesCorriendo = $this->consultaACOMPMes();
//
//        $this->actualizarCantidadesACOMP(array(
//            'subtotal' => $arraySubtotal['subtotal'],
//            'iva' => $arraySubtotal['iva'],
//            'descuento' => $datos['descuento'],
//            'descuentoFinanciero' => $datos['descuentoFinanciero'],
//            'fechaMes' => $mesCorriendo[0]['PER_ACUM']));
//
//        $this->insertarCOMPO(array(
//            'claveDocumento' => $datos['claveNuevaDocumentacion'],
//            'claveProvedor' => $datos['proveedor'],
//            'fechaDoc' => $datos['fecha'],
//            'fechaRec' => $datos['fechaRec'],
//            'cantidadTotal' => $arraySubtotal['subtotal'],
//            'iva' => $arraySubtotal['iva'],
//            'descuentoTotal' => $datos['descuento'],
//            'descuentoFinanciero' => $datos['descuentoFinanciero'],
//            'observacionesCond' => $datos['entregaA'],
//            'claveObservaciones' => $nuevaClaveTabla57,
//            'folio' => $ultimoDocumento[0]['ULT_DOC'],
//            'importe' => $arraySubtotal['subtotal'] + $arraySubtotal['iva']
//        ));
//        
//        $this->insertCOMPO_CLIB($datos['claveNuevaDocumentacion']);

        foreach ($datos['datosTabla'] as $key => $value) {
            $this->actualizarINVE(array(
                'claveArticulo' => $value['producto'],
                'cantidad' => $value['cantidad']
            ));
        }
        var_dump('pumas');
//        return $ultimaClaveTBLCONTROL->result_array();
        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->terminaTransaccion();
            return TRUE;
        }
    }

    public function consultaUltimaClaveTBLCONTROL(string $numeroTabla) {
        $consulta = parent::connectDBSAE7()->query("SELECT ULT_CVE FROM TBLCONTROL01 WHERE ID_TABLA = '" . $numeroTabla . "'");
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
                FROM FOLIOSC01                  
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

    public function consultaACOMPMes() {
        $consulta = parent::connectDBSAE7()->query("SELECT PER_ACUM FROM ACOMP01 WHERE  PER_ACUM = DATEADD(MONTH, -1, DATEADD(DAY, 1, EOMONTH(GETDATE())))");
        return $consulta->result_array();
    }

    public function actualizarUltimaClaveTBLCONTROL(array $datos) {
        parent::connectDBSAE7()->query("update TBLCONTROL01
                set ULT_CVE = '" . $datos['nuevaClave'] . "'
                where ID_TABLA = '" . $datos['numeroTabla'] . "'
                AND ULT_CVE = '" . $datos['claveAnterior'] . "'");
    }

    public function actualizarUltimoDocumento(string $nuevoUltimoDocumento) {
        $query = "UPDATE FOLIOSC01 
                    SET ULT_DOC=(CASE WHEN ULT_DOC < '" . $nuevoUltimoDocumento . "' THEN '" . $nuevoUltimoDocumento . "' ELSE ULT_DOC END),                      
                    FECH_ULT_DOC= GETDATE() 
                WHERE TIP_DOC = N'o'  
                AND SERIE = N'STAND.'                    
                AND FOLIODESDE=1";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function actualizarCantidadesACOMP(array $datos) {
        $query = "UPDATE ACOMP01 
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
        $query = "UPDATE INVE01                      
            SET COMP_X_REC = 
            (CASE WHEN COMP_X_REC +  " . $datos['cantidad'] . "  < 0 THEN 0                                       
            WHEN COMP_X_REC +  " . $datos['cantidad'] . "  >= 0 THEN COMP_X_REC +  " . $datos['cantidad'] . "                                        
            ELSE 0 END)                      
            WHERE CVE_ART = N' " . $datos['claveArticulo'] . "'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function insertarObservacionesDocumento(array $datos) {
        $query = "INSERT INTO OBS_DOCC01 
                    (CVE_OBS,STR_OBS) 
                    VALUES('" . $datos['claveObservaciones'] . "', '" . $datos['observaciones'] . "')";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function insertarCOMPO(array $datos) {
        $query = "insert into COMPO01
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
                    '" . str_replace('&nbsp;', ' ', $datos['claveDocumento']) . "',
                    '" . $datos['claveProvedor'] . "',
                    'O',
                    '',
                    '" . $datos['fechaDoc'] . " 00:00:00.000',
                    '" . $datos['fechaRec'] . " 00:00:00.000',
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
                    1,
                    'S',
                    'N',
                    'O',
                    'O',
                    1,
                    1,
                    GETDATE(),
                    '',
                    " . $datos['folio'] . ",
                    0,
                    'N',
                    'N',
                    'N',
                    0,
                    0,
                    0,
                    '" . $datos['importe'] . "',
                    '',
                    '')";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function insertCOMPO_CLIB(string $claveDocumento) {
        $query = "insert into COMPO_CLIB01
                    (CLAVE_DOC)
                  values
                  ('" . str_replace('&nbsp;', ' ', $claveDocumento) . "')";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

    public function insertPARCOMPO(string $claveDocumento) {
        $query = "insert into PAR_COMPO01
  (CVE_DOC, NUM_PAR, CVE_ART, CANT, PXR, PREC, COST, IMPU1, IMPU2, IMPU3, IMPU4, IMP1APLA, IMP2APLA, IMP3APLA, IMP4APLA, TOTIMP1, TOTIMP2, TOTIMP3, TOTIMP4, DESCU, ACT_INV, NUM_ALM, TIP_CAM, UNI_VENTA, TIPO_PROD, TIPO_ELEM, CVE_OBS, REG_SERIE, E_LTPD, FACTCONV, MINDIRECTO, NUM_MOV, TOT_PARTIDA, MAN_IEPS, APL_MAN_IMP, CUOTA_IEPS, APL_MAN_IEPS, MTO_PORC, MTO_CUOTA, CVE_ESQ)
values
  (N'" . str_replace('&nbsp;', ' ', $claveDocumento) . "',
                1,
                '" . $datos['claveArticulo'] . "',
                " . $datos['cantidad'] . ",
                " . $datos['cantidad'] . ",
                0,
                88,
                0,
                0,0,16,6,6,6,0,0,0,0,211.19999999999999,0,'N',1,1,'PZA','P','N',0,0,0,1,0,0,1320,'N',1,0,'C',0,0,1)";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta;
    }

}
