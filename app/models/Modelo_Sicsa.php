<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Sicsa extends Modelo_Base {

    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function insertaCotizacion(array $datos) {
        parent::connectDBSicsa()->trans_begin();
        $query = "insert into "
                . "db_Cotizacion "
                . "(Cliente, Fecha, SubtotalUSD, IVAUSD, TotalUSD, SubtotalMXN, IVAMXN, TotalMXN, NombreCliente, Observaciones, Complejo, ServiceDesk, Status) "
                . "VALUES "
                . "('         1', getdate(), '0', '0', '0', '0', '0', '0', 'OPERADORA DE CINEMAS, S.A. DE C.V.', '" . $datos['Observaciones'] . "', '" . $datos['Complejo'] . "', '" . $datos['SD'] . "', 'Capturada')";

        parent::connectDBSicsa()->query($query);
        $ultimo = parent::connectDBSicsa()->insert_id();

        $query = "insert into "
                . "db_DetCotizacion "
                . "(Cotizacion, CVE_ART, Cantidad, Descripcion, Complejo, ServiceDesk, Observaciones) "
                . "VALUES "
                . "('" . $ultimo . "', '" . $datos['CVE'] . "', 1, '" . $datos['Articulo'] . "', '" . $datos['Complejo'] . "', '" . $datos['SD'] . "', '" . $datos['Observaciones'] . "')";

        parent::connectDBSicsa()->query($query);


        if (parent::connectDBSicsa()->trans_status() === FALSE) {
            parent::connectDBSicsa()->trans_rollback();
            return ['code' => 400];
        } else {
            parent::connectDBSicsa()->trans_commit();
            return ['code' => 200];
        }
    }

}
