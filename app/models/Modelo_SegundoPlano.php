<?php

namespace Modelos;

use Librerias\Modelos\Base as Base;

/**
 * Description of Modelo_SegundoPlano
 *
 * @author Freddy
 */
class Modelo_SegundoPlano extends Base
{

    public function obtenerMaterialSae()
    {
        $data = array();
        $consulta = parent::connectDBSAE7()->query('select * from SAE7EMPRESA3.dbo.INVE03');
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $value) {
                array_push($data, array('Clave' => $value['CVE_ART'], 'Nombre' => $value['DESCR'], 'Linea' => $value['LIN_PROD'], 'Unidad' => $value['UNI_MED']));
            }
        }
        return $data;
    }

    public function truncar(string $consulta)
    {
        parent::connectDBPrueba()->query($consulta);
    }

    public function agregarMaterialFaltanteEquiposSae()
    {

        parent::connectDBPrueba()->query('call actualizaCatalogoProductosSAE()');
        if (mysqli_more_results(parent::connectDBPrueba()->conn_id)) {
            mysqli_next_result(parent::connectDBPrueba()->conn_id);
        }
    }

    public function getApiKeyByUser($idUser = '')
    {

        $consulta = $this->consulta('select SDKey from cat_v3_usuarios where Id = "' . $idUser . '";');
        if (!empty($consulta) && isset($consulta[0]['SDkey'])) {
            return $consulta[0]['SDKey'];
        } else {
            return '';
        }


        // $consulta = parent::connectDBPrueba()->query('select SDKey from cat_v3_usuarios where Id = "' . $idUser . '";');
        // if (!empty($consulta && isset($consulta[0]['SDKey']))) {
        //     $value = $consulta->result_array();
        //     return $value[0]['SDKey'];
        // } else {
        //     return false;
        // }
    }

    public function getDatabaseInfoSD(string $folio = '')
    {
        $consulta = parent::connectDBPrueba()->query('select * from hist_incidentes_sd where Folio = "' . $folio . '" order by FechaComprobacion desc limit 1;');
        if (!empty($consulta)) {
            return $consulta->result_array();
        } else {
            return false;
        }
    }

    public function buscarMailsBySDName(string $name = 'SIN INFO')
    {
        $consulta = parent::connectDBPrueba()->query("select  
                                                        Email as Tecnico,
                                                        if(cu.IdJefe is not null and cu.IdJefe > 0,
                                                        (select Email from cat_v3_usuarios where Id = cu.IdJefe),
                                                        '') as Jefe
                                                        from cat_v3_usuarios cu where SDName = '" . $name . "';");
        if (!empty($consulta)) {
            return $consulta->result_array();
        } else {
            return false;
        }
    }

    public function insertInfoCambiosSD(array $datos)
    {
        $fecha = $this->consulta("select now() as Fecha;");
        $this->insertar('hist_incidentes_sd', [
            'Folio' => $datos['folio'],
            'Creador' => $datos['creador'],
            'Solicitante' => $datos['solicita'],
            'Tecnico' => $datos['tecnico'],
            'Estatus' => $datos['estatus'],
            'Prioridad' => $datos['prioridad'],
            'Asunto' => $datos['asunto'],
            'Resolucion' => $datos['resolucion'],
            'Solucionador' => $datos['resolver'],
            'FechaResolucion' => ($datos['fechaResolucion'] != '0000-00-00 00:00:00') ? $datos['fechaResolucion'] : null,
            'FechaLectura' => (!isset($fecha['fechaLectura'])) ? $fecha[0]['Fecha'] : $fecha['fechaLectura'],
            'FechaComprobacion' => $fecha[0]['Fecha']
        ]);
    }

    public function getInfoUserByIMEI($imei)
    {
        $consulta = $this->consulta("select "
            . "nombreUsuario(cu.Id) as Usuario, "
            . "cu.EmailCorporativo as Email "
            . "from cat_v3_usuarios cu "
            . "where IMEI = '" . $imei . "'");
        return $consulta;
    }

    public function insertaAsignacionesSD($array)
    {
        $consulta = $this->consulta("select FechaLectura as Fecha from hist_incidentes_sd where Folio = '" . $array['Folio'] . "' order by Id limit 1;");
        if (!empty($consulta)) {
            $fecha = $consulta[0]['Fecha'];
        } else {
            $consulta = $this->consulta("select now() as Fecha;");
            $fecha = $consulta[0]['Fecha'];
        }
        $insert = array_merge($array, ['Fecha' => $fecha]);
        $this->insertar("t_asignaciones_sd", $insert);
    }

    public function getFoliosExistentesAsignacionesSD(string $folios = ",0")
    {
        $consulta = $this->consulta("select Folio from t_asignaciones_sd where Folio in (''" . $folios . ")");
        return $consulta;
    }

    public function getFoliosExistentesEnSolicitudes(string $folios = ",0")
    {
        $consulta = $this->consulta("select Folio from t_solicitudes where Folio in (''" . $folios . ")");
        return $consulta;
    }

    public function getFoliosExistentesEnV2(string $folios = ",0")
    {
        $consulta = parent::connectDBAdist2()->query("select  
                                                        Folio_Cliente as Folio
                                                        from t_servicios
                                                        where Folio_Cliente in (''" . $folios . ")");
        return $consulta->result_array();
    }

    public function insertaSolicitudesAdISTV3($array, $arrayAsunto)
    {
        $this->iniciaTransaccion();
        $this->insertar("t_solicitudes", $array);
        $ultimo = $this->ultimoId();
        $this->insertar('t_solicitudes_internas', array_merge(['IdSolicitud' => $ultimo], $arrayAsunto));

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return false;
        } else {
            $this->commitTransaccion();
            return true;
        }
    }
}
