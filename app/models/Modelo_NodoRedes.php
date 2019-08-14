<?php

namespace Modelos;

use \Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_NodoRedes extends Base {

    public function __construct() {
        parent::__construct();
    }

    public function setNodo(string $idServicio, array $datos) {

//        $consulta = $this->insertar('insert into t_redes_nodos values(
//                           "",
//                           '.$idServicio.',
//                           '.$datos['area'].',
//                           "'.$datos['nodo'].'",
//                           '.$datos['switch'].',
//                           '.$datos['numSwitch'].',
//                           "'.$datos['archivos'].'"                          
//                         )');
//        
//        return $consulta;
        return '1';
    }

    public function setMaterialNodo(string $idNodo, array $material) {
        foreach ($material as $value) {            
            $this->insertar('insert into t_redes_material values(
                           "",
                           ' . $idNodo . ',
                           ' . $value['idMaterial'] . ',
                           ' . $value['cantidad'] . '                           
                         )');
            $consulta = $this->consulta('select Bloqueado from t_inventario where Id = '.$value['idMaterial']);
            $totalMaterialUsado = $consulta[0]['Bloqueado'] + $value['cantidad'];
            $this->actualizar('update t_inventario 
                            set Bloqueado = ' . $totalMaterialUsado . '                            
                            where Id = ' . $value['idMaterial']);
        }
    }

}
