<?php

namespace Librerias\Proyectos;

use Librerias\Interfaces\Objeto_General as Objeto_General;
use Librerias\Componentes\Coleccion as Coleccion;
use \Librerias\Generales\Registro_Usuario as Usuario;
use \Librerias\Modelos\Modelo_Base as Modelo;

class Personal extends Objeto_General {

    private $personal;
    private $usuario;
    private $claveProyecto;

    public function __construct(Modelo $modelo, Usuario $usuario) {
        parent::__construct($modelo);
        $this->personal = new Coleccion('Personal de objeto Personal');
        $this->usuario = $usuario;
    }

    public function generarElementos() {

        $this->personal->limpiarColeccion();

        $listaPersonal = $this->db_adist3->consulta('
            select
                tpp.Id,
                tpp.IdUsuario,
                concat(trp.Nombres, " ",trp.ApPaterno, " ",trp.ApMaterno) as Nombre,
                tpp.DescripcionPerfil as Perfil,
                trp.NSS
            from t_personal_proyecto tpp
            inner join t_rh_personal trp
            on tpp.IdUsuario = trp.IdUsuario            
            where tpp.GrupoProyecto = "' . $this->claveProyecto . '" and tpp.Flag = 1');

        if (!empty($listaPersonal)) {
            foreach ($listaPersonal as $value) {
                $this->personal->agregar($value['Id'], array('Usuario' => $value['IdUsuario'], 'Nombre' => $value['Nombre'], 'Perfil' => $value['Perfil'], 'NSS' => $value['NSS']));
            }
        }
    }

    public function obtenerPersonalProyecto(string $claveProyecto) {
        $this->claveProyecto = $claveProyecto;
        $this->generarElementos();
        return $this->personal->elementos();
    }

    public function guardarLideres(array $datos, string $claveProyecto) {

        if (!empty($datos)) {
            $this->guardarPersonal($datos, $claveProyecto, 'Lider');
        }
    }

    private function guardarPersonal(array $datos, string $claveProyecto, string $perfil) {
        $usuario = $this->usuario->getDatosUsuario();
        foreach ($datos as $value) {
            $this->db_adist3->insertar('
                    insert t_personal_proyecto set                        
                        GrupoProyecto = "' . $claveProyecto . '",
                        IdUsuario = "' . $value . '",
                        DescripcionPerfil = "' . $perfil . '",
                        IdUsuarioModifica = "' . $usuario['Id'] . '",
                        Flag = "1"
                    ');
        }
    }

    public function actualizarLideres(array $datos = array()) {

        if (isset($datos['select-lideres'])) {
            $lista = ($datos['select-lideres'] === '') ? array() : $datos['select-lideres'];
            $personal = $this->personal->elementos();
            $lideres = array();

            foreach ($personal as $key => $value) {
                if ($value['Perfil'] === 'Lider') {
                    $lideres[$key] = $value;
                }
            }

            if (empty($lideres) && !empty($lista)) {
                $this->guardarLideres($lista, $this->claveProyecto);
            } else if (!empty($lideres) && !empty($lista)) {
                $this->actualizarPersonal($lista, $lideres);
            } else if (!empty($lideres) && empty($lista)) {
                $this->actualizarPersonal($lista, $lideres);
            }
        }
    }

    private function actualizarPersonal(array $listaNueva, array $personal) {

        foreach ($personal as $value) {
            if (in_array($value['Usuario'], $listaNueva)) {
                $index = array_search($value['Usuario'], $listaNueva);
                unset($listaNueva[$index]);
            } else {
                $this->db_adist3->actualizar('
                        update t_personal_proyecto set 
                            Flag = 0 
                        where GrupoProyecto = "' . $this->claveProyecto . '" and IdUsuario = ' . $value['Usuario']);
            }
        }
        $this->guardarPersonal($listaNueva, $this->claveProyecto, 'Lider');
        $this->personal->limpiarColeccion();
        $this->generarElementos();
    }

    public function guardarAsistente(array $datos, string $claveProyecto) {
        if (!empty($datos)) {
            $this->guardarPersonal(array($datos['select-asistente']), $claveProyecto, 'Asistente');
        }
    }

    public function actualizarAsistentes(array $datos) {
        $this->db_adist3->actualizar('
                        update t_personal_proyecto set 
                            Flag = 0 
                        where Id = ' . $datos[0]);
        $this->personal->limpiarColeccion();
        $this->generarElementos();
    }

}
