<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Agenda extends Modelo_Base
{

    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getMyPendingServices(int $serviceId = null)
    {
        $condition = '';
        if (!is_null($serviceId)) {
            $condition = " and tst.Id = '" . $serviceId . "'";
        }

        $employes = $this->getEmpleadosByIdJefe($this->usuario['Id']);

        return $this->consulta("
        select
        tst.Id,
        tst.Ticket,
        ts.Folio,
        tipoServicio(tst.IdTipoServicio) as TipoServicio,
        sucursal(tst.IdSucursal) as Sucursal,
        regionBySucursal(tst.IdSucursal) as Zona,
        estatus(tst.IdEstatus) as Estatus,
        nombreUsuario(tst.Atiende) as Atiende,
        tsi.Asunto,
        tsi.Descripcion,
        ts.FechaCreacion as FechaSolicitud,
        tst.FechaInicio,
        tst.FechaTentativa,
        tst.CalendarId,
        tst.CalendarLink
        from t_servicios_ticket tst
        inner join t_solicitudes ts on tst.IdSolicitud = ts.Id
        inner join t_solicitudes_internas tsi on ts.Id = tsi.IdSolicitud
        where tst.IdEstatus in (1,2,3,10)
         " . $condition . " 
        and tst.Atiende in (" . implode(",", $employes) . ")");
    }

    public function saveCalendarEvent(array $updateData)
    {
        $this->iniciaTransaccion();

        $this->actualizar("t_servicios_ticket", [
            'CalendarId' => $updateData['googleEvent']['id'],
            'CalendarLink' => $updateData['googleEvent']['link'],
            'FechaTentativa' => $updateData['tentative'],
        ], ['Id' => $updateData['serviceId']]);

        $attendees = [];
        foreach ($updateData['dataEvent']['users'] as $k => $v) {
            array_push($attendees, $v['email']);
        }

        $this->insertar("t_servicios_programaciones_google", [
            'IdServicio' => $updateData['serviceId'],
            'IdUsuario' => $this->usuario['Id'],
            'Fecha' => date('Y-m-d H:i:s'),
            'CalendarId' => $updateData['googleEvent']['id'],
            'Titulo' => $updateData['dataEvent']['title'],
            'Descripcion' => $updateData['dataEvent']['description'],
            'FechaInicio' => $updateData['dataEvent']['startDate'],
            'FechaTermino' => $updateData['dataEvent']['endDate'],
            'Invitados' => implode($attendees)
        ]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function getTechnicianEmailByService($serviceId)
    {
        return $this->consulta(
            "select 
            emailCorporativo(tst.Atiende) as Email 
            from t_servicios_ticket tst 
            where tst.Id = '" . $serviceId . "'"
        )[0]['Email'];
    }

    public function getEventHistory($serviceId)
    {
        return $this->consulta("
        select 
        nombreUsuario(tspg.IdUsuario) as Usuario, 
        tspg.* 
        from t_servicios_programaciones_google tspg
        where tspg.IdServicio = '" . $serviceId . "' 
        order by Fecha desc");
    }

    private function getEmpleadosByIdJefe(int $id)
    {
        $arrayUsuarios = [$id];
        $arrayUsuariosTemp = $arrayUsuarios;

        while (!empty($arrayUsuariosTemp)) {
            $ids = implode(",", $arrayUsuariosTemp);
            $consulta = $this->consulta("select Id from cat_v3_usuarios where IdJefe in (" . $ids . ")");
            $arrayUsuariosTemp = [];
            if (!empty($consulta)) {
                foreach ($consulta as $key => $value) {
                    if (!in_array($value['Id'], $arrayUsuarios)) {
                        array_push($arrayUsuarios, $value['Id']);
                        array_push($arrayUsuariosTemp, $value['Id']);
                    }
                }
            }
        }

        return $arrayUsuarios;
    }
}
