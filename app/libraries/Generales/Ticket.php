<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Ticket
 *
 * @author Freddy
 */
class Ticket extends General {

    private $DBT;

    public function __construct() {
        parent::__construct();
        $this->DBT = \Modelos\Modelo_Ticket::factory();
    }

    /*
     * Encargado de crear un ticket nuevo
     * 
     */

    public function setTicket(array $datos, array $informacion) {
        if(empty($informacion['cliente'])){
           $informacion['cliente'] = '4'; 
        }
        
        if ($datos['Folio'] !== '') {
            $ticket = $this->DBT->setTicketAdist2($informacion['cliente'], $informacion['descripcion'], $datos['Folio']);
        } else {
            $ticket = $this->DBT->setTicketAdist2($informacion['cliente'], $informacion['descripcion']);
        }
        
        return $ticket;
    }

}
