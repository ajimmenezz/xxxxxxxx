<?php

namespace Modelos;

use \Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_NodoRedes extends Base {

    public function __construct() {
        parent::__construct();
    }

    public function setNodo(string $idServicio, array $datos) {       
        $consulta = $this->insertar('insert into t_redes_nodos values(
                           null,
                           ' . $idServicio . ',
                           ' . $datos['area'] . ',
                           "' . $datos['nodo'] . '",
                           ' . $datos['switch'] . ',
                           ' . $datos['numSwitch'] . ',
                           "' . $datos['archivos'] . '"                          
                         )');
        return $consulta;
    }

    public function setMaterialNodo(string $idNodo, array $material) {
        foreach ($material as $value) {
            $this->insertar('insert into t_redes_material values(
                           "",
                           ' . $idNodo . ',
                           ' . $value['idMaterial'] . ',
                           ' . $value['cantidad'] . '                           
                         )');
            $consulta = $this->consulta('select Bloqueado from t_inventario where Id = ' . $value['idMaterial']);
            $totalMaterialUsado = $consulta[0]['Bloqueado'] + $value['cantidad'];
            $this->actualizar('update t_inventario 
                            set Bloqueado = ' . $totalMaterialUsado . '                            
                            where Id = ' . $value['idMaterial']);
        }
    }

    public function getNodosConMaterial(string $idServicio) {
        $consulta = $this->consulta('select 
                                        trn.Id as IdNodo,
                                        trn.IdArea,
                                        trn.Nombre,
                                        trn.IdSwitch,
                                        trn.NumeroSwitch,
                                        trn.Archivos,
                                        trm.Id as IdMaterial,
                                        trm.IdMaterialTecnico,
                                        trm.Cantidad
                                    from t_redes_nodos trn
                                    inner join t_redes_material trm
                                    on trn.Id = trm.IdNodo
                                    where trn.IdServicio = ' . $idServicio);
        return $consulta;
    }

    public function deleteNodo(string $idNodo) {
        $consulta = $this->consulta('select
                                        IdMaterialTecnico,
                                        Cantidad
                                     from t_redes_material
                                     where IdNodo = ' . $idNodo);
        foreach ($consulta as $value) {
            $bloqueado = $this->consulta('select Bloqueado from t_inventario where Id = ' . $value['IdMaterialTecnico']);
            $totalMaterialUsado = $bloqueado[0]['Bloqueado'] - $value['Cantidad'];
            $this->actualizar('update t_inventario 
                            set Bloqueado = ' . $totalMaterialUsado . '                            
                            where Id = ' . $value['IdMaterialTecnico']);
        }

        $this->borrar('delete from t_redes_material where IdNodo = ' . $idNodo);

        $this->borrar('delete from t_redes_nodos where Id = ' . $idNodo);
    }

    public function updateNodo(array $datos) {
        $archivos = null;

        $consulta = $this->consulta('select Archivos from t_redes_nodos where Id = ' . $datos['idNodo']);

        if (!empty($consulta)) {
            $archivos = $consulta[0]['Archivos'];
        }

        if (!empty($archivos)) {
            $archivos .= ',' . $datos['archivos'];
        } else {
            $archivos = $datos['archivos'];
        }

        $this->actualizar('update t_redes_nodos set
                                            IdArea = ' . $datos['area'] . ',
                                            Nombre = "' . $datos['nodo'] . '",
                                            IdSwitch = ' . $datos['switch'] . ',
                                            NumeroSwitch = ' . $datos['numSwitch'] . ',
                                            Archivos = "' . $archivos . '"
                                       where Id = ' . $datos['idNodo']);
    }

    public function getInformacionNodo(string $idNodo) {
        $consulta = $this->consulta('select                                        
                                        IdArea,
                                        Nombre,
                                        IdSwitch,
                                        NumeroSwitch,
                                        Archivos
                                    from t_redes_nodos                                    
                                    where Id = ' . $idNodo);
        return $consulta;
    }

    public function deleteMaterialNodo(string $idNodo) {
        $this->borrar('delete from t_redes_material where IdNodo = ' . $idNodo);
    }

    public function getTotalMaterial(string $idServicio) {
        return $this->ejecutaFuncion('call getTotalRedesServiceMaterial(' . $idServicio . ')');
    }

    public function deleteArchivo(string $idServicio, array $datos) {

        $temporal = null;
        $key = null;

        $consulta = $this->consulta('select Archivos from t_redes_nodos where Id = ' . $datos['idNodo']);

        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $temporal = explode(',', $value['Archivos']);
            }
        }

        if (in_array($datos['evidencia'], $temporal)) {
            $key = array_search($datos['evidencia'], $temporal);
            unset($temporal[$key]);
        }

        $archivos = implode(',', $temporal);

        $this->actualizar('update t_redes_nodos set
                            Archivos = "' . $archivos . '"
                            where Id = ' . $datos['idNodo']);
    }

}
