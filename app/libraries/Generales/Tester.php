<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Generales\Catalogo as Catalogo;

class Tester extends General {

    private $ServiceDesk;
    private $Catalogo;

    public function __construct() {
        parent::__construct();
        $this->ServiceDesk = \Librerias\WebServices\ServiceDesk::factory();
        $this->Catalogo = new Catalogo();
    }

    public function actualizarValidadoresSD() {
        $usuariosSD = $this->ServiceDesk->consultarValidadoresTI('A8D6001B-EB63-4996-A158-1B968E19AB84');

        $arrayValidadoresSD = [];
        $i = 0;
        foreach ($usuariosSD as $value) {
            if (!strpos($value["userName"], ' - Siccob')) {
                $arrayValidadoresSD[$i]['id'] = $value['userId'];
                $arrayValidadoresSD[$i]['nombre'] = $value['userName'];
                $arrayValidadoresSD[$i]['correo'] = $value['userEmail'];
                $i++;
            }
        }

        $j = 0;
        foreach ($arrayValidadoresSD as $insertData) {
            if ($insertData["correo"] !== '') {
                $respuesta = $this->Catalogo->traerinfobd(1, $insertData);
                if ($respuesta !== 'Repetido')
                    $j++;
            }
        }
        
        return $j . " Validadores de Cinemex agregados";
    }

}
