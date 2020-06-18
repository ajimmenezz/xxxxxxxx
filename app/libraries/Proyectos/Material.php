<?php

namespace Librerias\Proyectos;

use Librerias\Interfaces\Objeto_General as Objeto_General;
use Librerias\Componentes\Coleccion as Coleccion;
use \Librerias\Modelos\Modelo_Base as Modelo;

class Material extends Objeto_General {

    private $material;
    private $sistemaProyecto;
    private $listas;
    private $idProyecto;

    public function __construct(Modelo $modelo) {
        parent::__construct($modelo);
        $this->material = new Coleccion('Material de objeto Material');
        $this->listas = new Coleccion('Listas de objeto Material');
    }

    public function generarElementos() {

        $this->material->limpiarColeccion();

        $material = $this->db_adist3->consulta('
                select
                    tmp.IdSolicitud,
                    tmp.IdMaterial,
                    ces.Nombre, 
                    ces.Clave,
                    tmp.Cantidad,
                    ces.Unidad
                from t_material_proyecto tmp 
                inner join cat_v3_equipos_sae ces
                on ces.Id = tmp.IdMaterial
                where tmp.IdProyecto = ' . $this->idProyecto);

        if (!empty($material)) {
            foreach ($material as $value) {
                $this->material->agregar($value['IdMaterial'], array('solicitud' => $value['IdSolicitud'], 'nombre' => $value['Nombre'], 'numParte' => $value['Clave'], 'total' => $value['Cantidad'], 'unidad' => $value['Unidad']));
            }
        }
    }

    public function obtenerMaterialTotal(string $idProyecto) {
        $this->idProyecto = $idProyecto;
        $this->generarElementos();
        return $this->material->elementos();
    }

    public function datosMaterial(string $sistema, string $idProyecto = '') {
        $this->sistemaProyecto = $sistema;
        $this->idProyecto = $idProyecto;
        $this->listas->agregar('Accesorios', $this->obtenerAccesorios());
        $this->listas->agregar('Material', $this->obtenerMaterial());
        return $this->listas->elementos();
    }

    private function obtenerAccesorios() {

        $lista = array();
//        $accesorios = $this->db_adist3->consulta('select * from cat_v3_accesorios_proyecto where Flag = 1  and IdSistema = ' . $this->sistemaProyecto);
        $accesorios = $this->db_adist3->consulta("select 
                                                accesorios.Id as IdAccesorio,
                                                material.Id as IdMaterial,
                                                accesorios.Nombre as Accesorio,
                                                productos.Nombre as Material,
                                                concat(accesorios.Nombre,' - ',productos.Nombre) as Nombre
                                                from cat_v3_accesorios_proyecto accesorios 
                                                inner join cat_v3_material_proyectos material on accesorios.Id = material.IdAccesorio
                                                inner join cat_v3_equipos_sae productos on material.Id = productos.Id
                                                where accesorios.Flag = 1                                                
                                                and accesorios.IdSistema = '" . $this->sistemaProyecto . "' 
                                                order by Nombre");

        if (!empty($accesorios)) {
            foreach ($accesorios as $value) {
                array_push($lista, [
                    'Id' => $value['IdMaterial'],
                    'Nombre' => $value['Nombre'],
                    'IdAccesorio' => $value['IdAccesorio'],
                    'IdMaterial' => $value['IdMaterial'],
                    'Accesorio' => $value['Accesorio'],
                    'Material' => $value['Material']
                ]);
            }
        }
        return $lista;
    }

    private function obtenerMaterial() {

        $lista = array();
        $material = $this->db_adist3->consulta('
            select 
                cvmp.*,
                cves.Nombre,
                cves.Clave
            from cat_v3_material_proyectos cvmp
            inner join cat_v3_equipos_sae cves
            on cvmp.Id = cves.Id
            where cves.Id in (select Id from cat_v3_material_proyectos) and cves.Flag = 1');

        if (!empty($material)) {
            foreach ($material as $value) {
                array_push($lista, array('Id' => $value['Id'], 'Nombre' => $value['Nombre'], 'NumParte' => $value['Clave'], 'Accesorio' => $value['IdAccesorio']));
            }
        }
        return $lista;
    }

    public function actualizarMaterial(string $idProyecto) {

        $this->idProyecto = $idProyecto;
        $material = array();

        $listaAlcance = $this->db_adist3->consulta('select Id from t_alcance_proyecto where IdProyecto = ' . $idProyecto . ' and Flag = 1');

        if (!empty($listaAlcance)) {

            $material = $this->obtenerMaterialNodo($listaAlcance);
            $this->db_adist3->query('SET FOREIGN_KEY_CHECKS = 0');
            $this->db_adist3->borrar('delete from t_material_proyecto where IdProyecto = ' . $idProyecto);
            foreach ($material as $value) {
                $this->db_adist3->insertar('
                    insert into t_material_proyecto set
                        IdProyecto = ' . $idProyecto . ',
                        IdMaterial = ' . $value['IdMaterial'] . ',
                        Cantidad = ' . $value['Cantidad']);
            }
            $this->db_adist3->query('SET FOREIGN_KEY_CHECKS = 1');

            $this->generarElementos();
            return $this->material->elementos();
        } else {
            $this->db_adist3->borrar('delete from t_material_proyecto where IdProyecto = ' . $idProyecto);
        }
    }

    private function obtenerMaterialNodo(array $listaNodos) {

        $listaIdAlcance = '';

        foreach ($listaNodos as $key => $nodo) {
            if ($key === 0) {
                $listaIdAlcance .= $nodo['Id'];
            } else {
                $listaIdAlcance .= ',' . $nodo['Id'];
            }
        }

        return $this->db_adist3->consulta('select IdAlcance, IdMaterial, sum(Cantidad) as Cantidad from t_nodos_alcance_proyecto where IdAlcance in (' . $listaIdAlcance . ') group by IdMaterial');
    }

    public function ingresarSolicitudGenerada(string $idProyecto, string $IdSolicitud) {
        $this->db_adist3->actualizar('
                update t_material_proyecto set
                    IdSolicitud = ' . $IdSolicitud . '                    
                where IdProyecto = "' . $idProyecto . '"');
    }

}
