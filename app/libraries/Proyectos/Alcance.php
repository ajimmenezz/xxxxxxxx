<?php

namespace Librerias\Proyectos;

use Librerias\Interfaces\Objeto_General as Objeto_General;
use Librerias\Componentes\Coleccion as Coleccion;
use \Librerias\Modelos\Modelo_Base as Modelo;

class Alcance extends Objeto_General {

    private $nodos;
    private $listas;
    private $sistemaProyecto;
    private $idProyecto;

    public function __construct(Modelo $modelo) {
        parent::__construct($modelo);
        $this->nodos = new Coleccion('Nodos de objeto Alcance');
        $this->listas = new Coleccion('Listas de objeto Alcance');
    }

    public function generarElementos() {

        $this->nodos->limpiarColeccion();

        $nodo = $this->db_adist3->consulta('
                select 
                    tap.Id,                    
                    tap.IdConcepto,
                    (select Nombre from cat_v3_conceptos_proyecto where Id = tap.IdConcepto) as Concepto,
                    tap.IdArea,
                    (select Nombre from cat_v3_areas_proyectos where Id = tap.IdArea) as Area,
                    tap.IdUbicacion,
                    (select Nombre from cat_v3_ubicaciones_proyectos where Id = tap.IdUbicacion) as Ubicacion
                from t_alcance_proyecto tap 
                where IdProyecto = ' . $this->idProyecto . ' and Flag = 1');

        if (!empty($nodo)) {
            foreach ($nodo as $value) {
                $this->nodos->agregar($value['Id'], array(                    
                    'IdConcepto' => $value['IdConcepto'],
                    'Concepto' => $value['Concepto'],
                    'IdArea' => $value['IdArea'],
                    'Area' => $value['Area'],
                    'IdUbicacion' => $value['IdUbicacion'],
                    'Ubicacion' => $value['Ubicacion'],
                    'Puntos' => $this->obtenerPuntos($value['Id'])
                ));
            }
        }
    }

    private function obtenerPuntos(string $alcance) {

        $puntos = array();

        $accesorios = $this->db_adist3->consulta('
                    select                         
                        IdTipoNodo,
                        Nombre,
                        IdAccesorio,
                        (select Nombre from cat_v3_accesorios_proyecto where Id = IdAccesorio) as Accesorio,
                        IdMaterial,
                        (select Nombre from cat_v3_equipos_sae where Id = IdMaterial) as Material,
                        Cantidad                        
                    from t_nodos_alcance_proyecto where IdAlcance = ' . $alcance);
        if (!empty($accesorios)) {
            foreach ($accesorios as $item) {
                array_push($puntos, array(
                    'Tipo' => $item['IdTipoNodo'],
                    'Nombre' => $item['Nombre'],
                    'IdAccesorio' => $item['IdAccesorio'],
                    'Accesorio' => $item['Accesorio'],
                    'IdMaterial' => $item['IdMaterial'],
                    'Material' => $item['Material'],
                    'Cantidad' => $item['Cantidad']                    
                ));
            }
        }

        return $puntos;
    }

    public function obtenerNodosAlcance(string $idProyecto) {
        $this->idProyecto = $idProyecto;
        $this->generarElementos();
        return $this->nodos->elementos();
    }

    public function datosAlcance(string $sistema) {
        $this->sistemaProyecto = $sistema;
        $this->listas->agregar('Conceptos', $this->obtenerListaConcepto());
        $this->listas->agregar('Areas', $this->obtenerListaAreas());
        $this->listas->agregar('Ubicaciones', $this->obtenerListaUbicacion());
        return $this->listas->elementos();
    }

    private function obtenerListaConcepto() {

        $lista = array();
        $conceptos = $this->db_adist3->consulta('select * from cat_v3_conceptos_proyecto where Flag = 1  and IdSistema = ' . $this->sistemaProyecto);

        if (!empty($conceptos)) {
            foreach ($conceptos as $value) {
                array_push($lista, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
            }
        }
        return $lista;
    }

    private function obtenerListaAreas() {

        $lista = array();
        $areas = $this->db_adist3->consulta('select *, (select Nombre from cat_v3_conceptos_proyecto where Id = IdConcepto) as Concepto from cat_v3_areas_proyectos where Flag = 1');

        if (!empty($areas)) {
            foreach ($areas as $value) {
                array_push($lista, array('Id' => $value['Id'], 'Nombre' => $value['Nombre'], 'Concepto' => $value['IdConcepto'], 'NombreConcepto' => $value['Concepto']));
            }
        }
        return $lista;
    }

    private function obtenerListaUbicacion() {

        $lista = array();
        $areas = $this->db_adist3->consulta('select * from cat_v3_ubicaciones_proyectos where Flag = 1');

        if (!empty($areas)) {
            foreach ($areas as $value) {
                array_push($lista, array('Id' => $value['Id'], 'Nombre' => $value['Nombre'], 'Area' => $value['IdArea']));
            }
        }
        return $lista;
    }

    public function agregarNodo(array $datos) {

        if (!empty($datos['idNodo'])) {
            $idAlcance = $datos['idNodo'];
        } else {
            $idAlcance = $this->db_adist3->insertar('
                insert t_alcance_proyecto set 
                    IdProyecto = ' . $datos['idProyecto'] . ',
                    IdConcepto = ' . $datos['ubicacion']['select-concepto'] . ',
                    IdArea = ' . $datos['ubicacion']['select-area'] . ',
                    IdUbicacion = ' . $datos['ubicacion']['select-ubicacion'] . ',                    
                    Flag = 1');
        }

        $this->db_adist3->borrar('delete from t_nodos_alcance_proyecto where IdAlcance = ' . $idAlcance);
        foreach ($datos['nodos'] as $value) {
            if (!empty($idAlcance)) {
                $this->db_adist3->insertar('
                    insert t_nodos_alcance_proyecto set 
                        IdAlcance = ' . $idAlcance . ',                    
                        IdTipoNodo = ' . $value[5] . ',
                        Nombre = "' . $value[1] . '",
                        IdAccesorio = ' . $value[6] . ',
                        IdMaterial = ' . $value[7] . ',
                        Cantidad = ' . $value[4]);
            }
        }
    }

    public function eliminarNodo(array $datos) {

        $this->db_adist3->actualizar('
                        update t_alcance_proyecto set                         
                           Flag = 0                       
                        where Id = ' . $datos['Nodo']);
        $this->db_adist3->borrar('delete from t_nodos_alcance_proyecto where IdAlcance = ' . $datos['Nodo']);
    }

}
