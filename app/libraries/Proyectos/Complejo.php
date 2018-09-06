<?php

namespace Librerias\Proyectos;

use Librerias\Componentes\Coleccion as Coleccion;
use Librerias\Proyectos\Alcance as Alcance;
use Librerias\Proyectos\Material as Material;
use Librerias\Proyectos\Tarea as Tarea;
use Modelos\Modelo_DB_Adist2 as ModelAdis2;
use \Librerias\Modelos\Modelo_Base as Modelo;

class Complejo {

    private $ticket;
    private $nombre;
    private $claveProyecto;
    private $idComplejo;
    private $db_adist2;
    private $db_adist3;
    private $alcance;
    private $materiales;
    private $tareas;
    private $direccion = array();

    public function __construct(string $claveProyecto, string $idComplejo, Modelo $modelo) {

        $this->db_adist2 = new ModelAdis2();
        $this->alcance = new Alcance($modelo);
        $this->materiales = new Material($modelo);
        $this->tareas = new Tarea($modelo);
        $this->db_adist3 = $modelo;
        $this->claveProyecto = $claveProyecto;

        $this->definirNombreComplejo($idComplejo);
        $this->definirDireccion();
    }

    private function definirNombreComplejo(string $idComplejo) {
        $this->idComplejo = $idComplejo;
        $consulta = $this->db_adist3->consulta('select sucursal(' . $idComplejo . ') as Sucursal');
        foreach ($consulta as $value) {
            $this->nombre = $value['Sucursal'];
        }
    }
    
    private function definirDireccion() {
        $consulta = $this->db_adist3->consulta('
                select
                    catp.Nombre as Pais,
                    cate.Nombre as Estado,
                    catm.Nombre as Municipio,
                    catc.Nombre as Colonia,
                    cats.Calle,
                    cats.NoExt,
                    cats.Telefono1
                from cat_v3_sucursales cats
                inner join cat_v3_paises catp
                on cats.IdPais = catp.Id
                inner join cat_v3_estados cate
                on cats.IdEstado = cate.Id
                inner join cat_v3_municipios catm
                on cats.IdMunicipio = catm.Id
                inner join cat_v3_colonias catc
                on cats.IdColonia = catc.Id
                where cats.Id = '. $this->idComplejo);
        
        foreach ($consulta as $key => $value) {            
            $this->direccion['Pais'] = $value['Pais'];
            $this->direccion['Estado'] = $value['Estado'];
            $this->direccion['Municipio'] = $value['Municipio'];
            $this->direccion['Colonia'] = $value['Colonia'];
            $this->direccion['Calle'] = $value['Calle'].' NoExt: '. $value['NoExt'];
            $this->direccion['Telefono'] = $value['Telefono1'];
        }                
    }

    public function obtenerTicket() {
        return $this->ticket;
    }

    public function obtenerNombre() {
        return $this->nombre;
    }

    public function obtenerId() {
        return $this->idComplejo;
    }
    
    public function obtenerDireccion(){
        return $this->direccion;
    }

    public function generarTicket() {

        $ticket = $this->db_adist3->consulta('select Ticket from t_proyectos where IdSucursal = ' . $this->idComplejo . ' and Grupo = "' . $this->claveProyecto . '"');

        if (empty($ticket)) {
            $obtenerCliente = $this->db_adist3->consulta('select IdCliente from cat_v3_sucursales where Id = ' . $this->idComplejo);
            foreach ($obtenerCliente as $value) {
                $cliente = $value['IdCliente'];
            }

            $this->ticket = $this->db_adist2->insertar(
                    'insert t_servicios set 
                    F_Start = curdate() + 0,
                    H_Start = curtime(),
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
                    Observaciones = "CREACION DE PROYECTO ADIST V3",
                    Tipo = 16,
                    Gerente = 0,
                    Enlace = 0,
                    PersonalTI = 0,
                    Prioridad = 0');
        } else {
            foreach ($ticket as $value) {
                $this->ticket = $value['Ticket'];
            }
        }
    }

    public function obtenerDatosComplejo() {

        $datos = array();

        $consulta = $this->db_adist3->consulta('select Id from t_proyectos where Ticket = ' . $this->ticket);
        $idProyecto = $consulta[0]['Id'];

        $datos['alcance'] = $this->alcance->obtenerNodosAlcance($idProyecto);
        $datos['materiales'] = $this->materiales->obtenerMaterialTotal($idProyecto);
        $datos['tareas'] = $this->tareas->obtenerTareas($idProyecto);
        return $datos;
    }

    public function obtenerDatosAlcance() {
        $idSistema = $this->db_adist3->consulta('select IdSistema from t_proyectos where Grupo = "' . $this->claveProyecto . '"');
        return $this->alcance->datosAlcance($idSistema[0]['IdSistema']);
    }

    public function obtenerDatosMaterial() {
        $idSistema = $this->db_adist3->consulta('select IdSistema from t_proyectos where Grupo = "' . $this->claveProyecto . '"');
        return $this->materiales->datosMaterial($idSistema[0]['IdSistema']);
    }

    public function nuevoNodoAlcance(array $datos) {
        $this->alcance->agregarNodo($datos);
        $this->materiales->actualizarMaterial($datos['idProyecto']);
    }

    public function eliminarNodoAlcance(array $datos) {
        $this->alcance->eliminarNodo($datos);
        $this->materiales->actualizarMaterial($datos['idProyecto']);
    }

    public function generarTarea(array $datos, string $claveProyecto) {
        $this->tareas->nuevaTarea($datos, $claveProyecto);
    }

    public function actualizarTarea(array $datos) {
        $this->tareas->actualizarInformacion($datos);
    }

    public function eliminarTarea(array $datos) {
        $this->tareas->bajaTarea($datos);
    }

    public function obtenerPuntosAlcance() {
        $consulta = $this->db_adist3->consulta('select Id from t_proyectos where Ticket = ' . $this->ticket);
        $idProyecto = $consulta[0]['Id'];
        return $this->alcance->obtenerNodosAlcance($idProyecto);
    }

    public function definirSolicitudMaterial($idSolicitud) {
        $consulta = $this->db_adist3->consulta('select Id from t_proyectos where Ticket = ' . $this->ticket);
        $idProyecto = $consulta[0]['Id'];
        $this->materiales->ingresarSolicitudGenerada($idProyecto, $idSolicitud);
    }

    public function generarNuevaActividad(array $datos) {
        return $this->tareas->nuevaActividad($datos);
    }
    
    public function agregarNodoActividad(array $datos) {
        $this->tareas->agregarNodoActividad($datos);
    }
    
    public function eliminarNodoDeActividad(array $datos) {
        $this->tareas->eliminarNodoDeActividad($datos);
    }
    
    public function actualizarActividad(array $datos) {
        $this->tareas->actualizarActividad($datos);
    }
    
    public function eliminarActividad(array $datos) {
        $this->tareas->eliminarActividad($datos);
    }
    
    public function obtenerTareasAsignadas(array $datos){
        return $this->tareas->obtenerTareasTecnico($datos);                
    }

}
