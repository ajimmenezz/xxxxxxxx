<?php

namespace Librerias\MesaDeAyuda;

ini_set('max_execution_time', 3600);

use Controladores\Controller_Datos_Usuario as General;

class Seguimiento extends General {

    private $DBMAS;
    private $servicios;
    private $catalogo;
    private $libroExcel;

    public function __construct() {
        parent::__construct();
        $this->DBMAS = \Modelos\Modelo_MesaDeAyuda_Seguimiento::factory();
        $this->servicios = \Librerias\Generales\ServiciosTicket::factory();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        parent::getCI()->load->helper(array('FileUpload', 'date'));
    }

    /* Encargado de guardar los generales del servicio Uber */

    public function guardaGeneralesUber(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();        
        $this->DBMAS->iniciaTransaccion();
        $arrayDatos = [
            $usuario['Id'],
            $datos['servicio'],
            $datos['ticket'],
            $datos['personas'],
            $datos['fecha'],
            $datos['origen'],
            $datos['destino'],
            $datos['proyecto']
        ];
        $sentencia = ""
                . "insert into "
                . "t_uber_generales set "
                . "IdUsuario = ?, "
                . "IdServicio = ?, "
                . "Ticket = ?, "
                . "Personas = ?, "
                . "Fecha = ?, "
                . "Origen = ?, "
                . "Destino = ?, "
                . "Proyecto = ? ";

        $guardaDatos = $this->DBMAS->bindingQuery($sentencia, $arrayDatos);
        if ($guardaDatos) {
            $consulta = $this->servicios->actualizarServicio(['servicio' => $datos['servicio'], 'operacion' => '3']);
            if ($this->DBMAS->estatusTransaccion() === FALSE) {
                $this->DBMAS->roolbackTransaccion();
            } else {
                $this->DBMAS->commitTransaccion();
            }
            return $consulta;
        } else {
            return FALSE;
        }
    }

}
