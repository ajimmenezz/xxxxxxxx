<?php

namespace Librerias\Logistica;

use Controladores\Controller_Datos_Usuario as General;

class Rutas extends General {

    private $DBR;

    public function __construct() {
        parent::__construct();
        $this->DBR = \Modelos\Modelo_Rutas::factory();
        $this->notificacion = \Librerias\Generales\Notificacion::factory();
        parent::getCI()->load->helper('date');
    }

    /*
     * Metodo para solo para mostrar el formulario de Rutas 
     * 
     * @param array $datos recibe los datos para mostra en el formulario
     * @return array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function mostrarFormularioRutas(array $datos = null) {
        $data = array();
        $data['choferes'] = $this->DBR->getSentenciaRuta('SELECT cu.Id, cu.Nombre, trp.ApPaterno, trp.ApMaterno FROM cat_v3_usuarios cu INNER JOIN t_rh_personal trp ON trp.IdUsuario = cu.Id WHERE cu.IdPerfil = 59');
        if (!empty($datos['Ruta'])) {
            $data['idChofer'] = $this->DBR->getSentenciaRuta('SELECT IdUsuarioAsignado FROM t_rutas_logistica WHERE Id = ' . $datos['Ruta']);
        }
        return array('formulario' => parent::getCI()->load->view('Logistica/Modal/formularioRutas', $data, TRUE), 'datos' => $data);
    }

    /*
     * Metodo para solo para mostrar las rutas con estatus 1,2,12 
     * 
     * @param array $datos recibe los datos para mostra si trae datos la variable datos
     * @return array devuelve una array con los valores de la consulta.
     */

    public function listaRutas(array $datos = null) {
        if (empty($datos)) {
            $consulta = $this->DBR->getSentenciaRuta('SELECT trl.*, cu.Nombre, trh.ApPaterno, estatus(IdEstatus) AS Estatus FROM t_rutas_logistica trl INNER JOIN cat_v3_usuarios cu ON trl.IdUsuarioAsignado = cu.Id INNER JOIN t_rh_personal trh ON cu.Id = trh.IdUsuario WHERE trl.IdEstatus IN(1,2,12) AND trl.FechaRuta >= "CURRENT_DATE"');
        } else {
            $consulta = $this->DBR->getSentenciaRuta("SELECT trl.*, cu.Nombre, trh.ApPaterno, estatus(IdEstatus) AS Estatus FROM t_rutas_logistica trl INNER JOIN cat_v3_usuarios cu ON trl.IdUsuarioAsignado = cu.Id INNER JOIN t_rh_personal trh ON cu.Id = trh.IdUsuario WHERE trl.IdEstatus IN(1,2,12) AND trl.FechaRuta BETWEEN '" . $datos['desde'] . "' AND '" . $datos['hasta'] . "'");
        }
        return $consulta;
    }

    /*
     * Metodo para solo para insertar
     * 
     * @param array $datos recibe los datos para insertar
     * @return array devuelve una la consulta de las rutas en caso de error un false.
     */

    public function nuevaRuta(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $consulta = $this->DBR->insertarRutaNueva('insert into t_rutas_logistica
                                                        set 
                                                        IdUsuarioAsignado = ' . $datos['chofer'] . ',
                                                        IdCreador = ' . $usuario['Id'] . ',
                                                        IdEstatus = 2,
                                                        FechaCreacion = now(),
                                                        FechaRuta = "' . $datos['fecha'] . '",
                                                        Codigo = concat("' . $datos['fecha'] . '-R",
                                                        (select Total from (select count(*)+1 as Total from t_rutas_logistica where FechaRuta = "' . $datos['fecha'] . '") as tf));'
        );
        if ($consulta === TRUE) {
            return $this->listaRutas();
        } else {
            return FALSE;
        }
    }

    /*
     * Metodo para solo para actualizar datos 
     * 
     * @param array $datos recibe los datos para actualiazar
     * @return array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function actualizarRuta(string $operacion, array $datos) {
        switch ($operacion) {
            case '1':
                $usuario = $this->Usuario->getDatosUsuario();
                $consulta = $this->DBR->actualizarRuta('t_rutas_logistica', array(
                    'IdUsuarioAsignado' => $datos['chofer']
                        ), array('Id' => $datos['id'])
                );
                if (!empty($consulta)) {;
                    return $this->listaRutas();
                } else {
                    return FALSE;
                }
                break;
            //Empieza la ruta y los pone en estatus 12
            case '2':
                $servicios = $this->DBR->getSentenciaRuta('SELECT * FROM t_servicios_x_ruta WHERE IdRuta = ' . $datos['Ruta']);
                if (!empty($servicios)) {
                    foreach ($servicios as $value) {
                        $this->DBR->actualizarRuta('t_servicios_ticket', array(
                            'IdEstatus' => '12'
                                ), array('Id' => $value['IdServicio'])
                        );
                    }
                    $consulta = $this->DBR->actualizarRuta('t_rutas_logistica', array(
                        'IdEstatus' => '12'
                            ), array('Id' => $datos['Ruta'])
                    );
                    if (!empty($consulta)) {
                        return $this->listaRutas();
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'faltaServicio';
                }
                break;
            //Cancela la ruta
            case '3':
                $validacion = TRUE;
                $ruta = $this->DBR->getSentenciaRuta('SELECT tsxr.IdRuta, tsxr.IdServicio, trl.IdEstatus FROM t_rutas_logistica trl INNER JOIN t_servicios_x_ruta tsxr ON tsxr.IdRuta = trl.Id WHERE trl.Id = ' . $datos['id']);
                if (!empty($ruta)) {
                    foreach ($ruta as $value) {
                        if ($value['IdEstatus'] === '2') {
                            $consulta = $this->DBR->actualizarRuta('t_servicios_x_ruta', array(
                                'Flag' => '0'
                                    ), array('IdServicio' => $value['IdServicio'])
                            );
                        } elseif ($value['IdEstatus'] === '12') {
                            $idServicio = $this->DBR->getSentenciaRuta('SELECT IdEstatus FROM t_servicios_ticket WHERE Id = ' . $value['IdServicio']);
                            if ($idServicio[0]['IdEstatus'] === '6' || $idServicio[0]['IdEstatus'] === '4') {
                                $this->DBR->actualizarRuta('t_servicios_ticket', array(
                                    'IdEstatus' => '2'
                                        ), array('Id' => $value['IdServicio'])
                                );
                                $this->DBR->actualizarRuta('t_servicios_x_ruta', array(
                                    'Flag' => '0'
                                        ), array('IdServicio' => $value['IdServicio'])
                                );
                            } else {
                                $validacion = FALSE;
                            }
                        } else {
                            $validacion = FALSE;
                        }
                    }
                }
                if ($validacion) {
                    $consulta = $this->DBR->actualizarRuta('t_rutas_logistica', array(
                        'IdEstatus' => $datos['cancelacion']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->listaRutas();
                    } else {
                        return FALSE;
                    }
                }
                break;
            //Concluye la Ruta
            case '4':
                $idStatus = TRUE;
                $servicios = $this->DBR->getSentenciaRuta('SELECT tst.IdEstatus FROM t_servicios_x_ruta tsxr INNER JOIN t_servicios_ticket tst ON tst.Id = tsxr.IdServicio WHERE tsxr.IdRuta = ' . $datos['Ruta']);
                if (!empty($servicios)) {
                    foreach ($servicios as $value) {
                        if ($value['IdEstatus'] != '6') {
                            if ($value['IdEstatus'] != '4') {
                                $idStatus = FALSE;
                            }
                        }
                    }
                }
                if ($idStatus) {
                    $ruta = $this->DBR->getSentenciaRuta('SELECT IdEstatus FROM t_rutas_logistica WHERE Id = ' . $datos['Ruta']);
                    if ($ruta[0]['IdEstatus'] === '12') {
                        $consulta = $this->DBR->actualizarRuta('t_rutas_logistica', array(
                            'IdEstatus' => '4'
                                ), array('Id' => $datos['Ruta'])
                        );
                        if (!empty($consulta)) {
                            return $this->listaRutas();
                        } else {
                            return FALSE;
                        }
                    } else {
                        return FALSE;
                    }
                }
                break;
        }
    }

}
