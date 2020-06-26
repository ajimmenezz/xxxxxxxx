<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

/**
 * Description of Modelo_Ticket
 *
 * @author Freddy
 */
class Modelo_Ticket extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de insertar un nuevo registro en la tabla registro del adist2
     * 
     * @param string $sucursal recive el id de la sucursal
     * @return string regresa el Id_Orden de la insercion al adist2
     */

    public function setTicketAdist2(string $cliente, string $observaciones, string $folio = null) {
        $query = 'insert t_servicios set 
                    F_Start = curdate() + 0,
                    H_Start = curtime(),
                    Folio_Cliente = "' . $folio . '",
                    Cliente = ' . $cliente . ',
                    Sucursal = 0,
                    Reporta = 0,
                    N_Asignador = 0,
                    Estatus = "EN PROCESO DE ATENCION",
                    Flag = 0,
                    F_Cierre = 00000000,
                    Ingeniero = 0,
                    MedioContacto = "INTERNET",
                    F_Asignacion = "",
                    H_Asignacion = "",
                    Observaciones = "' . parent::connectDBAdist2()->escape($observaciones) . '",
                    Tipo = 0,
                    Gerente = 0,
                    Enlace = 0,
                    PersonalTI = 0,
                    Prioridad = 0';
        
        $host = $_SERVER['SERVER_NAME'];
        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $consulta = parent::connectDBAdist2()->query($query);
            return parent::connectDBAdist2()->insert_id();
        } else {
            $consulta = parent::connectDBAdist2P()->query($query);
            return parent::connectDBAdist2P()->insert_id();
        }
    }

}
