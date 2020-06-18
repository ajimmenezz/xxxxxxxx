<?php

namespace Librerias\Proyectos;

use CI_Controller;
use Librerias\Proyectos\Complejo as Complejo;
use Librerias\Proyectos\Personal as Personal;
use Librerias\Componentes\Coleccion as Coleccion;
use Librerias\Componentes\Error as Error;
use Librerias\Proyectos\Reporte as Reporte;
use Modelos\Modelo_DB_Adist3 as ModelAdis3;

class Proyecto2 {

    private $CI;
    private $db_adist3;
    private $complejos;
    private $datosGenerales;
    private $claveProyecto;
    private $usuario;
    private $personal;
    private $paginaError;
    private $inputsPostCliente;
    private $controlador;
    private $reporte;

    public function __construct(string $controlador = '') {

        if (empty(self::$CI)) {
            $this->CI = & get_instance();
        }

        $this->db_adist3 = new ModelAdis3();
        $this->complejos = new Coleccion('Complejos de objeto Proyecto');
        $this->datosGenerales = new Coleccion('DatosGenerales de objeto Proyecto');
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
        $this->personal = new Personal($this->db_adist3, $this->usuario);
        $this->paginaError = new Error();
        $this->controlador = $controlador;
        $this->reporte = new Reporte();

        $this->inputsPostCliente = array(
            'input-nombre-proyecto' => 'Nombre',
            'select-sistemas' => 'Sistema',
            'select-tipo-proyecto' => 'Tipo',
            'textArea-observaciones' => 'Observaciones',
            'fecha-inicio-proyecto' => 'FechaInicio',
            'fecha-final-proyecto' => 'FechaFin'
        );
    }

    public function nuevoProyecto(array $datos) {

        try {
            $this->db_adist3->empezarTransaccion();
            $this->generarClaveProyecto($datos);
            $this->generarDatosGenerales($datos);
            $complejosCargados = ($datos['select-complejo'] === '') ? array() : $datos['select-complejo'];
            $this->generarComplejos($complejosCargados);
            $this->guardarProyecto($datos);
            $this->db_adist3->finalizarTransaccion();
            return array(
                'datosProyectoNuevo' => $this->obtenerDatosProyectoNuevo(),
                'listaProyectos' => $this->obtenerListaProyectosSinIniciar(),
                'idPrimerProyecto' => $this->idPrimerProyecto($complejosCargados));
        } catch (\Exception $ex) {
            return $this->paginaError->mostrarError($ex);
        }
    }

    private function generarDatosGenerales(array $datos) {

        $this->datosGenerales->limpiarColeccion();

        $this->datosGenerales->agregar('clave', $this->claveProyecto);
        foreach ($datos as $key => $valor) {
            (array_key_exists($key, $this->inputsPostCliente)) ? $this->datosGenerales->agregar($this->inputsPostCliente[$key], $valor) : $this->datosGenerales->agregar($key, $valor);
        }
    }

    private function generarClaveProyecto(array $datos) {

        $nombreProyecto = $datos['input-nombre-proyecto'];
        $tipoProyecto = $datos['select-tipo-proyecto'];
        $sistemaSucursales = $datos['select-sistemas'];
        $numeroAletorio = rand(1, 1000);
        $this->claveProyecto = strtoupper($sistemaSucursales . substr(trim($nombreProyecto), 0, 3) . substr(trim($tipoProyecto), 0, 3) . $numeroAletorio);
    }

    private function generarComplejos(array $datos) {

        if (!empty($datos)) {
            $this->complejos->limpiarColeccion();
            foreach ($datos as $idComplejo) {
                $complejo = new Complejo($this->claveProyecto, $idComplejo, $this->db_adist3);
                $complejo->generarTicket();
                $this->complejos->agregar($idComplejo, $complejo);
            }
        }
    }

    private function guardarProyecto(array $datos) {

        if ($this->complejos->longitud() > 0) {
            foreach ($this->complejos->elementos() as $complejo) {
                $this->insertandoProyectoDB($complejo->obtenerTicket(), $complejo->obtenerId());
            }
        } else {
            $this->insertandoProyectoDB();
        }

        $this->personal->guardarLideres(($datos['select-lideres'] === '') ? array() : $datos['select-lideres'], $this->claveProyecto);
    }

    private function insertandoProyectoDB(string $ticket = null, string $idComplejo = null) {

        $usuario = $this->usuario->getDatosUsuario();

        $this->db_adist3->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db_adist3->insertar('
                    insert t_proyectos set
                        Ticket = "' . $ticket . '",
                        Nombre = "' . $this->datosGenerales->obtenerElemento('Nombre') . '",
                        IdSistema = "' . $this->datosGenerales->obtenerElemento('Sistema') . '",
                        IdTipo = "' . $this->datosGenerales->obtenerElemento('Tipo') . '",
                        IdUsuario = "' . $usuario['Id'] . '",
                        IdSucursal = "' . $idComplejo . '",
                        IdEstatus = "1",
                        Grupo = "' . $this->claveProyecto . '",
                        Observaciones = "' . $this->datosGenerales->obtenerElemento('Observaciones') . '",
                        FechaInicio = "' . $this->datosGenerales->obtenerElemento('FechaInicio') . '",
                        FechaTermino = "' . $this->datosGenerales->obtenerElemento('FechaFin') . '",
                        IdUsuarioModifica = "' . $usuario['Id'] . '"
                    ');

        $this->db_adist3->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    private function obtenerDatosProyectoNuevo() {

        $proyectoNuevo = new \stdClass();
        $proyectoNuevo->clave = $this->claveProyecto;

        foreach ($this->complejos->elementos() as $complejo) {
            $nombre = $complejo->obtenerNombre();
            $proyectoNuevo->$nombre = array('ticket' => $complejo->obtenerTicket(), 'Id' => $complejo->obtenerId());
        }

        return $proyectoNuevo;
    }

    private function obtenerListaProyectosSinIniciar() {
        $proyectos = array();
        $consulta = $this->db_adist3->consulta('
                select         
                    p.Id,
                    p.Ticket,                    
                    p.Nombre,    
                    e.Nombre Estado,
                    s.Nombre Complejo,
                    p.FechaInicio,
                    p.FechaTermino 
                from t_proyectos p left join cat_v3_sucursales s 
                on p.IdSucursal = s.Id 
                left join cat_v3_estados e 
                on s.IdEstado = e.Id 
                where p.IdEstatus = 1');

        foreach ($consulta as $value) {
            array_push($proyectos, array(
                'Id' => $value['Id'],
                'Ticket' => $value['Ticket'],
                'Nombre' => $value['Nombre'],
                'Complejo' => $value['Complejo'],
                'Estado' => $value['Estado'],
                'FechaInicio' => $value['FechaInicio'],
                'FechaTermino' => $value['FechaTermino']
            ));
        }
        return $proyectos;
    }

    public function idPrimerProyecto(array $datos) {
        if (!empty($datos)) {
            $idProyecto = $this->db_adist3->consulta('select Id from t_proyectos where Grupo = "' . $this->claveProyecto . '" and IdSucursal = ' . $datos[0]);
            return $idProyecto[0]['Id'];
        } else {
            $idProyecto = $this->db_adist3->consulta('select Id from t_proyectos where Grupo = "' . $this->claveProyecto . '"');
            return $idProyecto[0]['Id'];
        }
    }

    public function obtenerDatosProyecto(array $datos) {

        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $ex) {
            return $this->paginaError->mostrarError($ex);
        }
    }

    private function obtenerFormularios(Complejo $complejo) {

        if ($this->controlador === 'proyectos') {
            return $this->obtenerFormulariosProyectos($complejo);
        } else if ($this->controlador === 'seguimiento') {
            return $this->obtenerFormulariosSeguimiento($complejo);
        } else if ($this->controlador === 'tareasTecnico') {
            return $this->obtenerFormulariosTareasTecnico($complejo);
        }
    }

    private function obtenerFormulariosProyectos(Complejo $complejo) {
        $datos = array();
        $datos['listasSelects'] = $complejo->obtenerDatosAlcance();
        $datos['listasSelectsMaterial'] = $complejo->obtenerDatosMaterial();
        $datos['listaNodos'] = $complejo->obtenerPuntosAlcance();
        $datos['listaComplejos'] = $this->obtenerComplejosNoAsosciadosAProyecto();
        $datos['formularioNuevoNodo'] = $this->CI->load->view('Proyectos/Formularios/NodoNuevo', $datos, TRUE);
        $datos['formularioSolicitudPersonal'] = $this->CI->load->view('Proyectos/Formularios/SolicitudPersonal', $datos, TRUE);
        $datos['formularioNuevaTarea'] = $this->CI->load->view('Proyectos/Formularios/NuevaTarea', $datos, TRUE);
        $datos['formularioListaNodosCapturados'] = $this->CI->load->view('Proyectos/Formularios/ListaNodosCapturados', $datos, TRUE);
        $datos['formularioNuevoComplejo'] = $this->CI->load->view('Proyectos/Formularios/AgregarComplejos', $datos, TRUE);
        $datos['formularioEliminarComplejoProyecto'] = $this->CI->load->view('Proyectos/Formularios/EliminarComplejoProyecto', $datos, TRUE);
        return $datos;
    }

    private function obtenerFormulariosSeguimiento(Complejo $complejo) {
        $datos = array();
        $datos['listasSelects'] = $complejo->obtenerDatosAlcance();
        $datos['listasSelectsMaterial'] = $complejo->obtenerDatosMaterial();
        $datos['formularioNuevaTarea'] = $this->CI->load->view('Proyectos/Formularios/NuevaTarea', $datos, TRUE);
        $datos['formularioNuevaActividad'] = $this->CI->load->view('Proyectos/Formularios/NuevaActividad', $datos, TRUE);
        $datos['formularioNuevoMaterialActividad'] = $this->CI->load->view('Proyectos/Formularios/NuevoMaterialActividad', $datos, TRUE);
        return $datos;
    }

    private function obtenerFormulariosTareasTecnico(Complejo $complejo) {
        $datos = array();
        $datos['listasSelectsMaterial'] = $complejo->obtenerDatosMaterial();
        $datos['formularioMaterialUtilizado'] = $this->CI->load->view('Proyectos/Formularios/MaterialUtilizadoActividad', $datos, TRUE);
        return $datos;
    }

    private function obtenerListaComplejos() {
        $complejos = array();
        $lista = $this->db_adist3->consulta('select * from cat_v3_sucursales where Flag = 1');
        if (!empty($lista)) {
            foreach ($lista as $value) {
                array_push($complejos, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
            }
        }
        return $complejos;
    }

    private function obtenerComplejosNoAsosciadosAProyecto() {

        $listaComplejos = array();
        $temporal = array();
        $complejos = $this->obtenerListaComplejos();
        $lista = $this->db_adist3->consulta('select IdSucursal from t_proyectos where Grupo = "' . $this->claveProyecto . '" and IdEstatus = 1');
        if (!empty($lista)) {
            foreach ($lista as $value) {
                array_push($listaComplejos, $value['IdSucursal']);
            }

            foreach ($complejos as $value) {
                if (!in_array($value['Id'], $listaComplejos)) {
                    array_push($temporal, $value);
                }
            }
        }
        return $temporal;
    }

    private function obtenerDatosGeneralesProyecto(string $IdProyecto) {

        $datos = array();
        $consulta = $this->db_adist3->consulta('
                select 
                    *,
                    (select Nombre from cat_v3_sistemas_proyecto where Id = IdSistema) as NombreSistema,
                    (select Nombre from cat_v3_tipo_proyecto where Id = IdTipo) as NombreTipo
                from t_proyectos where Id = ' . $IdProyecto);
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $this->claveProyecto = $value['Grupo'];
                $datos['IdProyecto'] = $IdProyecto;
                $datos['Ticket'] = $value['Ticket'];
                $datos['Nombre'] = $value['Nombre'];
                $datos['Sistema'] = $value['IdSistema'];
                $datos['NombreSistema'] = $value['NombreSistema'];
                $datos['Tipo'] = $value['IdTipo'];
                $datos['NombreTipo'] = $value['NombreTipo'];
                $datos['Complejo'] = $value['IdSucursal'];
                $datos['Observaciones'] = $value['Observaciones'];
                $datos['FechaInicio'] = $value['FechaInicio'];
                $datos['FechaFin'] = $value['FechaTermino'];
                $datos['Avance'] = $value['Avance'];
            }
            $this->generarDatosGenerales($datos);
            $this->datosGenerales->agregar('personal', $this->personal->obtenerPersonalProyecto($this->claveProyecto));

            if (!empty($this->datosGenerales->obtenerElemento('Complejo'))) {
                $this->generarComplejos(array($this->datosGenerales->obtenerElemento('Complejo')));                
                $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));                
                $this->datosGenerales->agregar('NombreComplejo', $complejo->obtenerNombre());
                $this->datosGenerales->agregar('datosProyecto', $complejo->obtenerDatosComplejo());                
                $this->datosGenerales->agregar('Formularios', $this->obtenerFormularios($complejo));
            }
        } else {
            throw new \Exception('No se encuentra el proyecto');
        }
    }

    public function actualizarDatosGenerales(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $inputs = $this->establecerValoresInputsDelCliente($datos);
            $this->actualizarGenerales($inputs);
            $this->personal->actualizarLideres($datos);
            if (empty($this->datosGenerales->obtenerElemento('Ticket'))) {
                $this->actualizandoProyectoSinComplejos($datos);
            } else {
                $this->actualizandoProyectoDB($this->datosGenerales->obtenerElemento('Ticket'), $datos['idProyecto'], $this->datosGenerales->obtenerElemento('Complejo'));
            }
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();

            if (!empty($this->controlador) && $this->controlador === 'proyectos') {
                return array('datosProyecto' => $this->datosGenerales->elementos(), 'datosProyectoActualizado' => $this->obtenerDatosProyectoNuevo(), 'listaProyectos' => $this->obtenerListaProyectosSinIniciar());
            } else if (!empty($this->controlador) && $this->controlador === 'seguimiento') {
                return array('datosProyecto' => $this->datosGenerales->elementos(), 'listaProyectos' => $this->obtenerListaProyectosIniciados());
            }
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function obtenerListaProyectosIniciados() {
        $proyectos = array();
        $consulta = $this->db_adist3->consulta('
                select
                    p.Id,
                    p.Ticket,                    
                    p.Nombre,    
                    e.Nombre Estado,
                    s.Nombre Complejo,
                    p.FechaInicio,
                    p.FechaTermino 
                from t_proyectos p left join cat_v3_sucursales s 
                on p.IdSucursal = s.Id 
                left join cat_v3_estados e 
                on s.IdEstado = e.Id 
                where p.IdEstatus = 2');

        foreach ($consulta as $value) {
            array_push($proyectos, array(
                'Id' => $value['Id'],
                'Ticket' => $value['Ticket'],
                'Nombre' => $value['Nombre'],
                'Complejo' => $value['Complejo'],
                'Estado' => $value['Estado'],
                'FechaInicio' => $value['FechaInicio'],
                'FechaTermino' => $value['FechaTermino']
            ));
        }
        return $proyectos;
    }

    private function establecerValoresInputsDelCliente(array $datos) {

        $nuevos = array();

        foreach ($datos as $key => $valor) {
            if (array_key_exists($key, $this->inputsPostCliente)) {
                $nuevos[$this->inputsPostCliente[$key]] = $valor;
            }
        }
        return $nuevos;
    }

    private function actualizarGenerales(array $datos) {
        foreach ($datos as $key => $value) {
            $this->datosGenerales->actualizarElemento($key, $value);
        }
    }

    public function actualizandoProyectoSinComplejos(array $datos) {
        $contador = 1;

        $this->generarComplejos(($datos['select-complejo'] === '') ? array() : $datos['select-complejo']);

        if ($this->complejos->longitud() > 0) {
            foreach ($this->complejos->elementos() as $complejo) {
                if ($contador === 1) {
                    $this->actualizandoProyectoDB($complejo->obtenerTicket(), $datos['idProyecto'], $complejo->obtenerId());
                    $contador++;
                } else {
                    $this->insertandoProyectoDB($complejo->obtenerTicket(), $complejo->obtenerId());
                }
            }
        } else {
            $this->actualizandoProyectoDB(null, $datos['idProyecto']);
        }
    }

    private function actualizandoProyectoDB(string $ticket = null, string $idProyecto = null, string $idComplejo = null) {

        $usuario = $this->usuario->getDatosUsuario();

        $this->db_adist3->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db_adist3->actualizar('
                    update t_proyectos set
                        Ticket = "' . $ticket . '",
                        Nombre = "' . $this->datosGenerales->obtenerElemento('Nombre') . '",
                        IdSistema = "' . $this->datosGenerales->obtenerElemento('Sistema') . '",
                        IdTipo = "' . $this->datosGenerales->obtenerElemento('Tipo') . '",
                        IdUsuario = "' . $usuario['Id'] . '",
                        IdSucursal = "' . $idComplejo . '",                                                
                        Observaciones = "' . $this->datosGenerales->obtenerElemento('Observaciones') . '",
                        FechaInicio = "' . $this->datosGenerales->obtenerElemento('FechaInicio') . '",
                        FechaTermino = "' . $this->datosGenerales->obtenerElemento('FechaFin') . '",
                        IdUsuarioModifica = "' . $usuario['Id'] . '"
                    where Id = ' . $idProyecto);
        $this->db_adist3->actualizar('
                    update t_proyectos set                        
                        FechaInicio = "' . $this->datosGenerales->obtenerElemento('FechaInicio') . '",
                        FechaTermino = "' . $this->datosGenerales->obtenerElemento('FechaFin') . '"                        
                    where Grupo = "' . $this->claveProyecto . '"');
        $this->db_adist3->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function guardarAsistente(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->personal->guardarAsistente($datos, $this->claveProyecto);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $ex) {
            return $this->paginaError->mostrarError($ex);
        }
    }

    public function eliminarAsistente(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->personal->actualizarAsistentes($datos);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function generarSolicitudPersonal(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $numeroSolicitud = $this->generarSolicitud('1', '3', $datos['textarea-perfil-personal']);
            $this->db_adist3->finalizarTransaccion();
            return array('datos' => $this->datosGenerales->elementos(), 'solicitud' => $numeroSolicitud);
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    private function generarSolicitud(string $tipo, string $departamento, string $descripcion) {

        $usuario = $this->usuario->getDatosUsuario();

        $numeroSolicitud = $this->db_adist3->insertar('
                    insert t_solicitudes set
                        IdTipoSolicitud = ' . $tipo . ',
                        IdEstatus = 1,
                        IdDepartamento = ' . $departamento . ',
                        IdPrioridad = 1,
                        Ticket = "' . $this->datosGenerales->obtenerElemento('Ticket') . '",
                        FechaCreacion = now(),
                        Solicita = ' . $usuario['Id']);

        if (!empty($numeroSolicitud)) {
            $descripcion = str_replace('"', '', $descripcion);
            $this->db_adist3->insertar('
                        insert t_solicitudes_internas set
                            IdSolicitud = ' . $numeroSolicitud . ',
                            Asunto = "Se levanta solicitud de proyectos",
                            Descripcion = "' . $descripcion . '"');
        }

        return $numeroSolicitud;
    }

    public function guardarNodoAlcance(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $complejo->nuevoNodoAlcance($datos);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function eliminarNodoAlcance(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $complejo->eliminarNodoAlcance($datos);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function generarNuevaTarea(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $complejo->generarTarea($datos, $this->claveProyecto);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function actualizarTarea(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $complejo->actualizarTarea($datos);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function eliminarTarea(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $complejo->eliminarTarea($datos);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function agregarComplejo(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $lideres = array('select-lideres' => '');
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->generarComplejos((($datos['select-agregar-complejo'] === '') ? array() : $datos['select-agregar-complejo']));
            $this->guardarProyecto($lideres);
            $this->datosGenerales->agregar('NuevosComplejos', $this->obtenerDatosProyectoNuevo());
            $this->datosGenerales->agregar('listaProyectos', $this->obtenerListaProyectosSinIniciar());
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function eliminarComplejoProyecto(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $datosProyecto = array();
            $usuario = $this->usuario->getDatosUsuario();
            $this->db_adist3->actualizar('
                update t_proyectos set
                    IdEstatus = 6,
                    IdUsuarioCancela = ' . $usuario['Id'] . ',
                    DescripcionCancelacion = "' . $datos['textarea-eliminar-proyecto'] . '",
                    FechaCancelacion = NOW()
                where Id = ' . $datos['idProyecto']);
            $datosProyecto['listaProyectos'] = $this->obtenerListaProyectosSinIniciar();
            $this->db_adist3->finalizarTransaccion();
            return $datosProyecto;
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function eliminarProyecto(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $usuario = $this->usuario->getDatosUsuario();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->actualizar('
                update t_proyectos set
                    IdEstatus = 6,
                    IdUsuarioCancela = ' . $usuario['Id'] . ',
                    DescripcionCancelacion = "' . $datos['textarea-eliminar-proyecto'] . '",
                    FechaCancelacion = NOW()
                where Grupo = "' . $this->claveProyecto . '" and IdEstatus = 1');
            $datosProyecto['listaProyectos'] = $this->obtenerListaProyectosSinIniciar();
            $this->db_adist3->finalizarTransaccion();
            return $datosProyecto;
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function generarSolicitudMaterial(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $datosComplejo = $complejo->obtenerDatosComplejo();
            $listaMaterial = $this->generarListaMaterialParaSolicitud($datosComplejo['materiales']);
            $numeroSolicitud = $this->generarSolicitud('2', '16', $listaMaterial);
            $complejo->definirSolicitudMaterial($numeroSolicitud);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return array('datos' => $this->datosGenerales->elementos(), 'solicitud' => $numeroSolicitud);
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    private function generarListaMaterialParaSolicitud(array $materiales) {
        $listaMaterial = 'Esta es la lista de material requerido: ';
        $contador = 0;
        foreach ($materiales as $value) {
            $unidad = ($value['unidad'] === 'BOBINA') ? 'mts' : strtolower($value['unidad']);
            if ($contador === 0) {
                $listaMaterial .= $value['nombre'] . ', numero de parte : ' . $value['numParte'] . ', cantidad : ' . $value['total'] . $unidad;
            } else {
                $listaMaterial .= ' || ' . $value['nombre'] . ', numero de parte : ' . $value['numParte'] . ', cantidad : ' . $value['total'] . $unidad;
            }
            $contador++;
        }
        return $listaMaterial;
    }

    public function iniciarProyecto(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->actualizar('
                update t_proyectos set
                    Inicio = NOW(),
                    IdEstatus = 2
                where Id = ' . $datos['idProyecto']);
            $datosProyecto['listaProyectos'] = $this->obtenerListaProyectosSinIniciar();
            $this->db_adist3->finalizarTransaccion();
            return $datosProyecto;
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function generarActividad(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $usuario = $this->usuario->getDatosUsuario();
            $datos['usuario'] = $usuario['Id'];
            $datos['CI'] = $this->CI;
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $actividadNueva = $complejo->generarNuevaActividad($datos);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return array('datosProyecto' => $this->datosGenerales->elementos(), 'actividadNueva' => $actividadNueva);
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function agregarNodoEnActividad(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $datos['CI'] = $this->CI;
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $complejo->agregarNodoActividad($datos);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function eliminarNodoDeActividad(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $datos['CI'] = $this->CI;
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $complejo->eliminarNodoDeActividad($datos);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function actualizarActividad(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $complejo->actualizarActividad($datos);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function eliminarActividad(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $datos['CI'] = $this->CI;
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $complejo->eliminarActividad($datos);
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $this->db_adist3->finalizarTransaccion();
            return $this->datosGenerales->elementos();
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function obtenerTareasAsignadasTecnico(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $usuario = $this->usuario->getDatosUsuario();
            $datos['usuario'] = $usuario['Id'];
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $listaTareas = $complejo->obtenerTareasAsignadas($datos);
            $this->db_adist3->finalizarTransaccion();
            return $listaTareas;
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function generarPDFInicioProyecto(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $datosReporte = $this->definirDatosReporte($complejo, $datos['idProyecto']);
            $archivo = $this->reporte->reporteInicioProyecto($datosReporte);
            $this->db_adist3->finalizarTransaccion();
            return $archivo;
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    public function generarPDFMaterial(array $datos) {
        try {
            $this->db_adist3->empezarTransaccion();
            $this->obtenerDatosGeneralesProyecto($datos['idProyecto']);
            $complejo = $this->complejos->obtenerElemento($this->datosGenerales->obtenerElemento('Complejo'));
            $datosReporte = $this->definirDatosReporte($complejo, $datos['idProyecto']);
            $archivo = $this->reporte->reporteMaterialProyecto($datosReporte);
            $this->db_adist3->finalizarTransaccion();
            return $archivo;
        } catch (\Exception $exc) {
            return $this->paginaError->mostrarError($exc);
        }
    }

    private function definirDatosReporte(Complejo $complejo, string $idProyecto) {
        $datos = array();
        $datos['carpeta'] = 'Proyectos/Proyecto_' . $idProyecto;
        $datos['Proyecto'] = $this->datosGenerales->obtenerElemento('Nombre');
        $datos['Inicio'] = $this->datosGenerales->obtenerElemento('FechaInicio');
        $datos['Complejo'] = $complejo->obtenerNombre();
        $datos['Direccion'] = $complejo->obtenerDireccion();
        $datos['Material'] = array();
        $datos['Lideres'] = array();
        $datos['Tecnicos'] = array();
        $datos['TotalPersonal'] = count($this->datosGenerales->obtenerElemento('personal'));

        foreach ($complejo->obtenerDatosComplejo() as $key => $value) {
            if ($key === 'materiales') {
                foreach ($value as $item) {
                    if ($item['unidad'] === 'BOBINA') {
                        $item['unidad'] = 'MTS';
                    }
                    array_push($datos['Material'], array($item['nombre'], $item['numParte'], $item['total'] . ' ' . $item['unidad']));
                }
            }
        }

        foreach ($this->datosGenerales->obtenerElemento('personal') as $key => $value) {
            if ($value['Perfil'] === 'Lider') {
                array_push($datos['Lideres'], $value['Nombre']);
            } else if ($value['Perfil'] === 'Asistente') {
                array_push($datos['Tecnicos'], $value['Nombre']);
            }
        }

        return $datos;
    }

    public function getAreasByConcepto(array $datos) {
        $consulta = $this->db_adist3->consulta("select "
                . "* "
                . "from cat_v3_areas_proyectos "
                . "where IdConcepto = '" . $datos['concepto'] . "' "
                . "and Flag = 1 order by Nombre");
        return ['areas' => $consulta];
    }
    
    public function getUbicacionesByArea(array $datos) {
        $consulta = $this->db_adist3->consulta("select "
                . "* "
                . "from cat_v3_ubicaciones_proyectos "
                . "where IdArea = '" . $datos['area'] . "' "
                . "and Flag = 1 order by Nombre");
        return ['ubicaciones' => $consulta];
    }

}
