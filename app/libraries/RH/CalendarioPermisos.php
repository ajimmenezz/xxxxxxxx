<?php

namespace Librerias\RH;

use Modelos\Modelo_Calendar as Calendario;

class CalendarioPermisos extends Calendario {

    private $calendario;
    private $Correo;

    public function __construct() {
        parent::__construct();
        $this->calendario = new Calendario;
        $this->Correo = \Librerias\Generales\Correo::factory();
    }
    
    public function getMotivoCancelaciones() {
        $respuestaConsulta = $this->calendario->motivosCancelacion();
        return $respuestaConsulta;
    }

    public function PermisosUsuario(string $fecha) {
        $dia = $this->calcularDia($fecha);
        switch ($dia) {
            case "Domingo":
                $diasAntes = 0;
                $dia = 13;
                break;
            case "Lunes":
                $diasAntes = 1;
                $dia = 12;
                break;
            case "Martes":
                $diasAntes = 2;
                $dia = 11;
                break;
            case "Miercoles":
                $diasAntes = 3;
                $dia = 10;
                break;
            case "Jueves":
                $diasAntes = 4;
                $dia = 9;
                break;
            case "Viernes":
                $diasAntes = 5;
                $dia = 8;
                break;
            case "Sabado":
                $diasAntes = 6;
                $dia = 7;
                break;

            default;
                echo "No es un número de fecha válido";
        }

        $fechaMinima = date("Y-m-d", strtotime($fecha . "- " . $diasAntes . " days"));
        $fechaMaxima = date("Y-m-d", strtotime($fecha . "+ " . $dia . " days"));

        $respuestaConsulta = $this->calendario->consultaPermisos($fechaMinima, $fechaMaxima);

        return $respuestaConsulta;
    }

    private function calcularDia($inFecha) {
        $dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
        $fecha = $dias[date('N', strtotime($inFecha))];
        return($fecha);
    }

    public function peticionCancelarPermiso($peticionCancelar) {

        if ($peticionCancelar["idJefe"] === "") {
            $emailJefe = $this->calendario->getEmailJefeByIdUsusario($peticionCancelar["idUsuario"]);
        } else {
            $emailJefe = $this->calendario->getEmailJefe($peticionCancelar['idJefe']);
        }

        $correosRHContador = $this->calendario->getCorreosRHContador();

        $arregloCorreos = "";
        foreach ($correosRHContador as $value) {
            $arregloCorreos .= $value["EmailCorporativo"] . ",";
        }
        $arregloCorreos .= $emailJefe[0]['EmailCorporativo'];

        if ($peticionCancelar["motivoSelect"] !== "") {
            $motivo = $peticionCancelar["motivoSelect"];
        } else {
            $motivo = $peticionCancelar["motivoTextArea"];
        }

        $texto = "Se solicita la Cancelación para el PERMISO DE AUSENCIA de " . $peticionCancelar["nombreUsuario"]
                . "<br>EL cual estaba solicitado para " . $peticionCancelar["MotivoAusencia"] . " el día " . $peticionCancelar["fechaAusencia"] . "
                    <br>LA razon ".$motivo.". Por favor revisa el permiso en la sección de Autorizar Permisos en el ADIST";
        $mensaje = $this->Correo->mensajeCorreo('Cancelar Permiso de Ausencia ', $texto);
        $correoEnviado = $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($arregloCorreos), 'Cancelar Permiso de Ausencia', $mensaje);
        return $correoEnviado;
    }

}
