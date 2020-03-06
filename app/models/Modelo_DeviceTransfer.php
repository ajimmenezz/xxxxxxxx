<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_DeviceTransfer extends Modelo_Base
{
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getTransferOrRequestDeviceValidators()
    {
        return $this->consulta("
        select
        cu.Id,
        nombreUsuario(cu.Id) as Nombre        
        from cat_v3_usuarios cu
        where cu.IdPerfil in (46,39,38)
        and cu.Flag = 1
        order by cu.Nombre");
    }

    public function getAvailableDeviceForServiceSolution($serviceId)
    {
        return $this->consulta("
        select 
        ti.IdProducto,
        modelo(ti.IdProducto) as Producto,
        ti.Cantidad,
        ti.Id as IdInventario,
        ti.Serie
        from t_inventario ti where ti.IdAlmacen in (
	        (select Id 
	        from cat_v3_almacenes_virtuales
	        where (IdTipoAlmacen = 1 and IdReferenciaAlmacen = '" . $this->user['Id'] . "') or (IdTipoAlmacen = 4 and IdResponsable = '" . $this->user['Id'] . "'))
        ) and IdTipoProducto = 1 
        and ti.IdEstatus = 17 
        and ti.Bloqueado = 0
        and lineaByModelo(ti.IdProducto) = (select lineaByModelo(IdModelo) from t_correctivos_generales where IdServicio = '" . $serviceId . "')");
    }

    public function getGeneralsService($serviceId)
    {
        return $this->consulta("
        select 
        (select IdSucursal from t_servicios_ticket where Id = tcg.IdServicio) as Sucursal,
        tcg.*
        from t_correctivos_generales tcg 
        where tcg.IdServicio = '" . $serviceId . "'");
    }

    public function getDiagnosticService($serviceId)
    {
        return $this->consulta("select * from t_correctivos_diagnostico where IdServicio = '" . $serviceId . "' order by Id desc limit 1");
    }

    public function cancelMovementDeviceTransfer($data)
    {
        $this->iniciaTransaccion();
        $generalsService = $this->getGeneralsService($data['serviceId'])[0];
        $movementInfo = $this->getDeviceMovementData(null, $data['movementId'])[0];

        $branchWarehouseId = $this->getBranchWarehouseId($generalsService['Sucursal']);
        $technicianWarehouseId = $this->getTechnicianWareahouseId($this->user['Id']);

        if (!is_null($movementInfo['IdInventarioRespaldo']) && $movementInfo['IdInventarioRespaldo'] > 0) {
            $backupDeviceInfo = $this->getDeviceInventoryInfo($movementInfo['IdInventarioRespaldo']);
            $this->rollbackWarehousesBackupUse($backupDeviceInfo, $data['serviceId'], $branchWarehouseId, $technicianWarehouseId);
        }

        $this->rollbackWarehouses($data['serviceId'], $branchWarehouseId, $technicianWarehouseId);

        $this->actualizar("t_equipos_allab", ['IdEstatus' => 6], ['Id' => $data['movementId']]);

        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400, 'error' => $this->tipoError()];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    private function rollbackWarehouses($serviceId, $branchWarehouseId, $technicianWarehouseId)
    {
        $this->returnDeviceToCenso($serviceId);
        $censoInfo = $this->getCensoIdFromService($serviceId, 1);
        $deviceInfo = $this->getDeviceInventoryInfoFromPreviusCenso($serviceId, $censoInfo);
        $this->transferDeviceToTechnicianWareahouse($serviceId, $deviceInfo, $technicianWarehouseId, $branchWarehouseId);
    }

    private function getDeviceInventoryInfoFromPreviusCenso($serviceId, $censoInfo)
    {
        $sqlText = "
        select 
        ti.*
        from t_inventario ti
        where ti.IdTipoProducto = 1
        and IdProducto = '" . $censoInfo['IdModelo'] . "' 
        and IdEstatus = 22
        and Serie = '" . $censoInfo['Serie'] . "'
        and IdAlmacen = (
                        select 
                        Id 
                        from cat_v3_almacenes_virtuales 
                        where IdTipoAlmacen = 1 
                        and IdReferenciaAlmacen = '" . $this->user['Id'] . "')";
        $query = $this->consulta($sqlText);
        if (empty($query)) {
            var_dump($sqlText);
        } else {
            return $query[0];
        }
    }

    private function returnDeviceToCenso($serviceId)
    {
        $generalsService = $this->getGeneralsService($serviceId)[0];
        $this->queryBolean("
        update 
        t_censos tc
        set Existe = 1,
        IdEstatus = 17,
        Danado = 0
        where tc.IdServicio = (
            select 
            MAX(Id) 
            from t_servicios_ticket 
            where IdSucursal = (select IdSucursal from t_servicios_ticket where Id = '" . $serviceId . "') 
            and IdTipoServicio = 11 
            and IdEstatus = 4
        ) 
        and tc.IdArea = '" . $generalsService['IdArea'] . "'
        and tc.Punto = '" . $generalsService['Punto'] . "'
        and tc.IdModelo = '" . $generalsService['IdModelo'] . "'
        and Serie = '" . $generalsService['Serie'] . "'
        and Existe = 0
        and IdEstatus = 22
        and Danado = 1");
    }

    private function rollbackWarehousesBackupUse($deviceInfo, $serviceId, $branchWarehouseId, $technicianWarehouseId)
    {
        $this->removeBackupFromCenso($serviceId, $deviceInfo);
        $this->transferBackupToTechnicianWareahouse($serviceId, $deviceInfo, $technicianWarehouseId, $branchWarehouseId);
    }

    private function removeBackupFromCenso($serviceId, $deviceInfo)
    {
        $generalsService = $this->getGeneralsService($serviceId)[0];
        $this->queryBolean("
        delete        
        from t_censos
        where IdServicio = (
            select 
            MAX(Id) 
            from t_servicios_ticket 
            where IdSucursal = (select IdSucursal from t_servicios_ticket where Id = '" . $serviceId . "') 
            and IdTipoServicio = 11 
            and IdEstatus = 4
        ) 
        and IdArea = '" . $generalsService['IdArea'] . "' 
        and Punto = '" . $generalsService['Punto'] . "' 
        and IdModelo = '" . $deviceInfo['IdProducto'] . "' 
        and Serie = '" . $deviceInfo['Serie'] . "'");
    }

    private function transferBackupToTechnicianWareahouse($serviceId, $deviceInfo, $technicianWarehouseId, $branchWarehouseId)
    {
        $this->actualizar('t_inventario', [
            'IdAlmacen' => $technicianWarehouseId,
            'Bloqueado' => 0,
            'IdEstatus' => 17
        ], ['Id' => $deviceInfo['Id']]);

        //Inserta el movimiento de salida del equipo de respaldo de la sucursal al almacén del técnico
        $this->insertar("t_movimientos_inventario", [
            'IdTipoMovimiento' => 4,
            'IdServicio' => $serviceId,
            'IdAlmacen' => $branchWarehouseId,
            'IdTipoProducto' => 1,
            'IdProducto' => $deviceInfo['IdProducto'],
            'IdEstatus' => 17,
            'IdUsuario' => $this->user['Id'],
            'Cantidad' => 1,
            'Serie' => $deviceInfo['Serie'],
            'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
        ]);

        //Inserta el movimiento de entrada del equipo de respaldo al almacén de la sucursal
        $this->insertar("t_movimientos_inventario", [
            'IdMovimientoEnlazado' => $this->ultimoId(),
            'IdTipoMovimiento' => 5,
            'IdServicio' => $serviceId,
            'IdAlmacen' => $technicianWarehouseId,
            'IdTipoProducto' => 1,
            'IdProducto' => $deviceInfo['IdProducto'],
            'IdEstatus' => 17,
            'IdUsuario' => $this->user['Id'],
            'Cantidad' => 1,
            'Serie' => $deviceInfo['Serie'],
            'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
        ]);
    }

    private function transferDeviceToTechnicianWareahouse($serviceId, $deviceInfo, $technicianWarehouseId, $branchWarehouseId)
    {
        $this->actualizar('t_inventario', [
            'IdAlmacen' => $branchWarehouseId,
            'Bloqueado' => 0,
            'IdEstatus' => 17
        ], ['Id' => $deviceInfo['Id']]);

        //Inserta el movimiento de salida del equipo de respaldo de la sucursal al almacén del técnico
        $this->insertar("t_movimientos_inventario", [
            'IdTipoMovimiento' => 4,
            'IdServicio' => $serviceId,
            'IdAlmacen' => $technicianWarehouseId,
            'IdTipoProducto' => 1,
            'IdProducto' => $deviceInfo['IdProducto'],
            'IdEstatus' => 17,
            'IdUsuario' => $this->user['Id'],
            'Cantidad' => 1,
            'Serie' => $deviceInfo['Serie'],
            'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
        ]);

        //Inserta el movimiento de entrada del equipo de respaldo al almacén de la sucursal
        $this->insertar("t_movimientos_inventario", [
            'IdMovimientoEnlazado' => $this->ultimoId(),
            'IdTipoMovimiento' => 5,
            'IdServicio' => $serviceId,
            'IdAlmacen' => $branchWarehouseId,
            'IdTipoProducto' => 1,
            'IdProducto' => $deviceInfo['IdProducto'],
            'IdEstatus' => 17,
            'IdUsuario' => $this->user['Id'],
            'Cantidad' => 1,
            'Serie' => $deviceInfo['Serie'],
            'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
        ]);
    }

    public function saveDeviceTransfer($data)
    {
        $this->iniciaTransaccion();
        $generalsService = $this->getGeneralsService($data['serviceId'])[0];

        $this->insertar("t_equipos_allab", [
            'IdServicio' => $data['serviceId'],
            'IdPersonalValida' => $data['validator'],
            'FechaValidacion' => date('Y-m-d H:i:s'),
            'IdTipoMovimiento' => $data['movement'],
            'IdModelo' => $generalsService['IdModelo'],
            'Serie' => $generalsService['Serie'],
            'IdUsuario' => $this->user['Id'],
            'idEstatus' => 2,
            'FechaEstatus' => date('Y-m-d H:i:s'),
            'IdTecnicoSolicita' => $this->user['Id'],
            'Flag' => 0,
            'IdInventarioRespaldo' => $data['backupDevice']
        ]);

        $branchWarehouseId = $this->getBranchWarehouseId($generalsService['Sucursal']);
        $technicianWarehouseId = $this->getTechnicianWareahouseId($this->user['Id']);
        $censoInfo = $this->getCensoIdFromService($data['serviceId']);

        if ($data['backupDevice'] !== "") {
            $this->updateWarehousesBackupUse($data, $censoInfo, $branchWarehouseId, $technicianWarehouseId);
        }
        $this->updateWarehousesForTransfer($data, $censoInfo, $branchWarehouseId, $technicianWarehouseId);

        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400, 'error' => $this->tipoError()];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    private function updateWarehousesBackupUse($deviceTransferData, $censoInfo, $branchWarehouseId, $technicianWarehouseId)
    {
        $deviceInfo = $this->getDeviceInventoryInfo($deviceTransferData['backupDevice']);
        $this->insertar("t_censos", [
            'IdServicio' => $censoInfo['IdServicio'],
            'IdArea' => $censoInfo['IdArea'],
            'IdModelo' => $deviceInfo['IdProducto'],
            'Punto' => $censoInfo['Punto'],
            'Serie' => $deviceInfo['Serie'],
            'Extra' => '',
            'Existe' => 1
        ]);

        //Cambia al equipo de respaldo de almacén (del técnico a la sucursal)
        $this->actualizar("t_inventario", [
            'IdAlmacen' => $branchWarehouseId,
            'Bloqueado' => 0
        ], ['Id' => $deviceTransferData['backupDevice']]);

        //Inserta el movimiento de salida del equipo de respaldo del almacén del técnico
        $this->insertar("t_movimientos_inventario", [
            'IdTipoMovimiento' => 4,
            'IdServicio' => $deviceTransferData['serviceId'],
            'IdAlmacen' => $technicianWarehouseId,
            'IdTipoProducto' => 1,
            'IdProducto' => $deviceInfo['IdProducto'],
            'IdEstatus' => 17,
            'IdUsuario' => $this->user['Id'],
            'Cantidad' => 1,
            'Serie' => $deviceInfo['Serie'],
            'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
        ]);

        //Inserta el movimiento de entrada del equipo de respaldo al almacén de la sucursal
        $this->insertar("t_movimientos_inventario", [
            'IdMovimientoEnlazado' => $this->ultimoId(),
            'IdTipoMovimiento' => 5,
            'IdServicio' => $deviceTransferData['serviceId'],
            'IdAlmacen' => $branchWarehouseId,
            'IdTipoProducto' => 1,
            'IdProducto' => $deviceInfo['IdProducto'],
            'IdEstatus' => 17,
            'IdUsuario' => $this->user['Id'],
            'Cantidad' => 1,
            'Serie' => $deviceInfo['Serie'],
            'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
        ]);
    }

    private function updateWarehousesForTransfer($deviceTransferData, $censoInfo, $branchWarehouseId, $technicianWarehouseId)
    {
        $this->actualizar(
            "t_censos",
            [
                'Danado' => 1,
                'Existe' => 0,
                'IdEstatus' => 22
            ],
            ['Id' => $censoInfo['Id']]
        );

        //Agrega el equipo dañado al almacen virtual del técnico
        $inventoryId = $this->consulta("
              select Id 
              from t_inventario 
              where IdTipoProducto = 1 
              and IdProducto = '" . $censoInfo['IdModelo'] . "' 
              and Serie = '" . $censoInfo['Serie'] . "' 
              and Serie <> 'ILEGIBLE'");
        if (!empty($inventoryId) && isset($inventoryId[0]['Id']) && $inventoryId[0]['Id'] > 0) {
            $this->actualizar("t_inventario", [
                'IdAlmacen' => $technicianWarehouseId,
                'IdEstatus' => 22,
                'Bloqueado' => 0
            ], ['Id' => $inventoryId[0]['Id']]);
        } else {
            $this->insertar("t_inventario", [
                'IdAlmacen' => $technicianWarehouseId,
                'IdTipoProducto' => 1,
                'IdProducto' => $censoInfo['IdModelo'],
                'IdEstatus' => 22,
                'Cantidad' => 1,
                'Serie' => $censoInfo['Serie'],
                'Bloqueado' => 0
            ]);
        }

        //Inserta el movimiento de salida del equipo dañado del almacén de la sucursal
        $this->insertar("t_movimientos_inventario", [
            'IdTipoMovimiento' => 4,
            'IdServicio' => $deviceTransferData['serviceId'],
            'IdAlmacen' => $branchWarehouseId,
            'IdTipoProducto' => 1,
            'IdProducto' => $censoInfo['IdModelo'],
            'IdEstatus' => 22,
            'IdUsuario' => $this->user['Id'],
            'Cantidad' => 1,
            'Serie' => $censoInfo['Serie'],
            'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
        ]);

        //Inserta el movimiento de entrada del equipo de respaldo al almacén de la sucursal
        $this->insertar("t_movimientos_inventario", [
            'IdMovimientoEnlazado' => $this->ultimoId(),
            'IdTipoMovimiento' => 5,
            'IdServicio' => $deviceTransferData['serviceId'],
            'IdAlmacen' => $technicianWarehouseId,
            'IdTipoProducto' => 1,
            'IdProducto' => $censoInfo['IdModelo'],
            'IdEstatus' => 22,
            'IdUsuario' => $this->user['Id'],
            'Cantidad' => 1,
            'Serie' => $censoInfo['Serie'],
            'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
        ]);
    }

    private function getBranchWarehouseId($branchid)
    {
        $query = $this->consulta("
        select 
        Id 
        from cat_v3_almacenes_virtuales 
        where IdTipoAlmacen = 2 
        and IdReferenciaAlmacen = '" . $branchid . "'");
        if (!empty($query)) {
            return $query[0]['Id'];
        } else {
            $this->queryBolean("
            insert 
            into cat_v3_almacenes_virtuales
            set IdTipoAlmacen = 2, 
            IdReferenciaAlmacen = '" . $branchId . "', 
            Nombre = concat('Inventario de ', (select Nombre from cat_v3_sucursales where Id = '" . $branchId . "')),
            Flag = 1 ");
            return $this->ultimoId();
        }
    }

    private function getTechnicianWareahouseId($userId)
    {
        $query = $this->consulta("
        select 
        Id 
        from cat_v3_almacenes_virtuales 
        where IdTipoAlmacen = 1 
        and IdReferenciaAlmacen = '" . $userId . "'");
        if (!empty($query)) {
            return $query[0]['Id'];
        } else {
            $this->queryBolean("
            insert 
            into cat_v3_almacenes_virtuales
            set IdTipoAlmacen = 1, 
            IdReferenciaAlmacen = '" . $userId . "', 
            Nombre = concat('Inventario de ', nombreUsuario($userId)),
            Flag = 1 ");
            return $this->ultimoId();
        }
    }

    private function getCensoIdFromService($serviceId, $exists = 1)
    {
        $generalsService = $this->getGeneralsService($serviceId)[0];
        return $this->consulta("
        select 
        * 
        from t_censos tc
        where tc.IdServicio = (
            select 
            MAX(Id) 
            from t_servicios_ticket 
            where IdSucursal = (select IdSucursal from t_servicios_ticket where Id = '" . $serviceId . "') 
            and IdTipoServicio = 11 
            and IdEstatus = 4
        ) 
        and tc.IdArea = '" . $generalsService['IdArea'] . "'
        and tc.Punto = '" . $generalsService['Punto'] . "'
        and tc.IdModelo = '" . $generalsService['IdModelo'] . "'
        and Serie = '" . $generalsService['Serie'] . "' 
        and Existe = " . $exists . "
        limit 1")[0];
    }

    private function getDeviceInventoryInfo($inventoryId)
    {
        return $this->consulta("select * from t_inventario where Id = '" . $inventoryId . "'")[0];
    }

    public function getDeviceMovementData($serviceId = null, $movementId = null)
    {
        $condition = '';
        if (!is_null($serviceId)) {
            $condition .= " and tea.IdServicio = '" . $serviceId . "' and tea.IdEstatus <> 6";
        }

        if (!is_null($movementId)) {
            $condition .= " and tea.Id = '" . $movementId . "'";
        }

        return $this->consulta("
        select        
        nombreUsuario(tea.IdPersonalValida) as Valida,
        (select Nombre from cat_v3_equipos_allab_tipo_movimiento where Id = tea.IdTipoMovimiento) as Movimiento,    
        modelo(tea.IdModelo) as Modelo,
        modelo(ti.IdProducto) as ModeloRespaldo,
        ti.Serie as SerieRespaldo,
        tst.Ticket,
        folioByServicio(tst.Id) as Folio,
        nombreUsuario('" . $this->user['Id'] . "') as UsuarioActual,
        sucursal(tst.IdSucursal) as Sucursal,
        tea.* 
        from t_equipos_allab tea 
        inner join t_servicios_ticket tst on tea.IdServicio = tst.Id
        left join t_inventario ti on tea.IdInventarioRespaldo = ti.Id
        where 1=1 " . $condition);
    }

    public function getLogisticCompanies()
    {
        return $this->consulta("select * from cat_v3_paqueterias where Flag = 1");
    }

    public function requestLogisticGuide($dataFormRequest, $bodyText)
    {
        $this->iniciaTransaccion();
        $this->insertar("t_equipos_allab_envio_tecnico", [
            'IdRegistro' => $dataFormRequest['movementId'],
            'IdUsuario' => $this->user['Id'],
            'IdEstatusEnvio' => 26,
            'Solicitud' => 1,
            'InformacionSolicitudGuia' => $bodyText
        ]);

        $this->actualizar("t_equipos_allab", [
            'IdEstatus' => 26,
            'FechaEstatus' => date('Y-m-d H:i:s'),
            'IdTecnicoSolicita' => $this->user['Id'],
            'Flag' => 1
        ]);

        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400, 'error' => $this->tipoError()];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function cancelRequestLogisticGuide($logisticGuideRequestId)
    {
        $this->iniciaTransaccion();
        $requestLogisticGuideInfo = $this->consulta("
        select 
        * 
        from t_equipos_allab_envio_tecnico 
        where Id = '" . $logisticGuideRequestId . "'")[0];

        $this->eliminar("t_equipos_allab_envio_tecnico", ['Id' => $logisticGuideRequestId]);

        $this->actualizar("t_equipos_allab", [
            'IdUsuario' => $this->user['Id'],
            'IdEstatus' => 2,
            'FechaEstatus' => date('Y-m-d H:i:s'),
            'Flag' => 0
        ], ['Id' => $requestLogisticGuideInfo['IdRegistro']]);

        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400, 'error' => $this->tipoError()];
        } else {
            $this->commitTransaccion();
            return ['code' => 200, 'bodyText' => $requestLogisticGuideInfo['InformacionSolicitudGuia']];
        }
    }

    public function saveShipingInfo($formData)
    {
        $this->iniciaTransaccion();
        if ($formData['shipingId'] > 0) {
            $this->actualizar("t_equipos_allab_envio_tecnico", [
                'IdEstatusEnvio' => 12,
                'IdPaqueteria' => $formData['logisticCompanie'],
                'Guia' => $formData['logisticTrackNumber'],
                'Fecha' => date('Y-m-d H:i:s')
            ], ['Id' => $formData['shipingId']]);
        } else {
            $this->insertar("t_equipos_allab_envio_tecnico", [
                'IdRegistro' => $formData['movementId'],
                'IdUsuario' => $this->user['Id'],
                'IdEstatusEnvio' => 12,
                'IdPaqueteria' => $formData['logisticCompanie'],
                'Guia' => $formData['logisticTrackNumber'],
                'Fecha' => date('Y-m-d H:i:s'),
                'Solicitud' => 0
            ]);
        }

        $this->actualizar("t_equipos_allab", [
            'IdUsuario' => $this->user['Id'],
            'IdEstatus' => 12,
            'FechaEstatus' => date('Y-m-d H:i:s'),
            'Flag' => 1
        ], ['Id' => $formData['movementId']]);

        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400, 'error' => $this->tipoError()];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function getTechnicianLogicticInfo($movementId)
    {
        return $this->consulta("
        select
        (select Nombre from cat_v3_paqueterias where Id = teaet.IdPaqueteria) as Paqueteria, 
        teaet.* 
        from t_equipos_allab_envio_tecnico teaet
        where teaet.IdRegistro = '" . $movementId . "' 
        and teaet.IdEstatusEnvio <> 6");
    }
}