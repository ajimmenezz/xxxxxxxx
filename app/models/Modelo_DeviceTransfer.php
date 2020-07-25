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

        $branchWarehouseId = $this->getBranchWarehouseId($generalsService['Sucursal']);
        $technicianWarehouseId = $this->getTechnicianWareahouseId($this->user['Id']);
        $censoInfo = $this->getCensoIdFromService($data['serviceId']);

        if ($data['backupDevice'] !== "") {
            $this->updateWarehousesBackupUse($data, $censoInfo, $branchWarehouseId, $technicianWarehouseId);
        }

        $inventoryId = $this->updateWarehousesForTransfer($data, $censoInfo, $branchWarehouseId, $technicianWarehouseId);

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
            'IdInventarioRespaldo' => $data['backupDevice'],
            'IdInventarioRetiro' => $inventoryId
        ]);

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

    public function updateWarehousesForTransfer($deviceTransferData, $censoInfo, $branchWarehouseId, $technicianWarehouseId)
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
            $newInventoryId = $inventoryId[0]['Id'];
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
            $newInventoryId = $this->ultimoId();
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

        return $newInventoryId;
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

    public  function getTechnicianWareahouseId($userId)
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

    public function requestQuote($data)
    {
        $this->iniciaTransaccion();

        $serviceInfo = $this->getServiceInfo($data['serviceId']);

        $quoteRequestId = null;
        if (in_array($data['assignTo'], [15306])) {
            //if (in_array($data['assignTo'], [15306, 8706])) {
            $quoteRequestId = $this->insertQuoteRequest($serviceInfo, $data['annotations'], $data['files']);
        }

        $laboratoryCheckId = $this->getLaboratoryCheckInfo($data['movementId']);

        $this->insertar("t_equipos_allab_revision_laboratorio_historial", [
            'IdRevision' => $laboratoryCheckId,
            'IdUsuario' => $this->user['Id'],
            'Fecha' => date('Y-m-d H:i:s'),
            'Comentarios' => $data['annotations'],
            'Archivos' => $data['files'],
            'IdSolicitudCotizacion' => $quoteRequestId,
            'IdUsuarioSD' => $data['assignTo']
        ]);

        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400, 'error' => $this->tipoError()];
        } else {
            $this->commitTransaccion();
            return ['code' => 200, 'serviceInfo' => $serviceInfo];
        }
    }

    private function insertQuoteRequest($serviceInfo, $annotations, $files)
    {
        $this->insertar("t_solicitudes", [
            'IdTipoSolicitud' => 3,
            'IdEstatus' => 1,
            'IdDepartamento' => 21,
            'IdPrioridad' => 2,
            'Ticket' => $serviceInfo['Ticket'],
            'Folio' => $serviceInfo['Folio'],
            'Fechacreacion' => date('Y-m-d H:i:s'),
            'Solicita' => $this->user['Id'],
            'IdServicioOrigen' => $serviceInfo['IdServicio']
        ]);

        $requestId = $this->ultimoId();
        $this->insertar("t_solicitudes_internas", [
            'IdSolicitud' => $requestId,
            'Asunto' => 'Solicitud de cotización',
            'Descripcion' => $annotations,
            'Evidencias' => $files
        ]);

        return $requestId;
    }

    private function getLaboratoryCheckInfo($movementId)
    {
        $result = $this->consulta("
        select 
        * 
        from t_equipos_allab_revision_laboratorio 
        where IdRegistro = '" . $movementId . "'");
        if (!empty($result)) {
            return $result[0]['Id'];
        } else {
            $this->insertar("t_equipos_allab_revision_laboratorio", [
                'IdRegistro' => $movementId,
                'IdEstatus' => 29,
                'IdUsuario' => $this->user['Id'],
                'Fecha' => date('Y-m-d H:i:s')
            ]);
            return $this->ultimoId();
        }
    }

    public function getServiceInfo($serviceId)
    {
        return $this->consulta("
        select 
        tst.Id as IdServicio,
        ts.*,
        tst.* 
        from t_solicitudes ts 
        inner join t_servicios_ticket tst on ts.Id = tst.IdSolicitud 
        where tst.Id = '" . $serviceId . "'")[0];
    }

    public function getQuoteRequestInfo($serviceId)
    {
        return $this->consulta("
        select 
        tearl.IdRegistro,
        tearlh.Id,
        tearlh.Comentarios,
        tearlh.Archivos,
        tearlh.IdUsuarioSD,
        ts.Id as IdSolicitud,
        ts.IdEstatus as EstatusSolicitud
        from t_equipos_allab_revision_laboratorio tearl
        inner join t_equipos_allab_revision_laboratorio_historial tearlh on tearl.Id = tearlh.IdRevision
        left join t_solicitudes ts on ts.Id = tearlh.IdSolicitudCotizacion
        where tearl.IdRegistro = (select Id from t_equipos_allab where IdServicio = '" . $serviceId . "' and Idestatus <> 6 limit 1)
        and (
            (tearlh.IdSolicitudCotizacion is not null and tearlh.IdSolicitudCotizacion > 0) 
            or 
            (tearlh.IdUsuarioSD is not null and tearlh.IdUsuarioSD > 0)             
        )");
    }

    public function cancelQuoteRequest($commentId)
    {
        $this->iniciaTransaccion();

        $commentInfo = $this->consulta("
        select 
        * 
        from t_equipos_allab_revision_laboratorio_historial 
        where Id = '" . $commentId . "'")[0];

        if ($commentInfo['IdSolicitudCotizacion'] > 0) {
            $this->actualizar("t_solicitudes", ['IdEstatus' => 6], ['Id' => $commentInfo['IdSolicitudCotizacion']]);
        }

        $this->eliminar("t_equipos_allab_revision_laboratorio_historial", ['Id' => $commentId]);

        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400, 'error' => $this->tipoError()];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function getMovementGeneralsForPdf($serviceId)
    {
        return $this->consulta("
        select
        folioByServicio(tst.Id) as Folio,
        tst.Ticket,
        cliente(cs.IdCliente) as Cliente,
        cs.Nombre as Sucursal,
        tipoServicio(tst.IdTipoServicio) as TipoServicio,
        tst.IdEstatus,
        estatus(tst.IdEstatus) as Estatus,
        nombreUsuario(tst.Atiende) as Atiende,
        (select Nombre from cat_perfiles where Id = (select IdPerfil from cat_v3_usuarios where Id = tst.Atiende)) as Perfil,
        (select Nombre from cat_v3_equipos_allab_tipo_movimiento where Id = tea.IdTipoMovimiento) as TipoMovimiento,
        concat(modelo(tcg.IdModelo),' NS:',tcg.Serie) as EquipoRetirado,
        if(
            tea.IdInventarioRespaldo > 0,
            concat(CONVERT(modelo(ti.IdProducto) USING utf8),' NS:',CONVERT(ti.Serie USING utf8)),
            'NO SE DEJA EQUIPO DE RESPALDO'
        ) as EquipoRespaldo,
        tea.Id as IdMovimiento,
        concat(
            if(tcd.IdTipoDiagnostico = 4, 
                    (select Nombre from cat_v3_fallas_refaccion where Id = tcd.IdFalla), 
                    (select Nombre from cat_v3_fallas_equipo where Id = tcd.IdFalla)
            ),' (',
            if(IdTipoDiagnostico = 4, 
                    (select Nombre from cat_v3_tipos_falla where Id = (select IdTipoFalla from cat_v3_fallas_refaccion where Id = tcd.IdFalla)), 
                    (select Nombre from cat_v3_tipos_falla where Id = (select IdTipoFalla from cat_v3_fallas_equipo where Id = tcd.IdFalla))
            ),')'
        ) as Falla,
        tcd.Evidencias as EvidenciasDiagnostico,
        tcd.Observaciones as ObservacionesDiagnostico
        from t_servicios_ticket tst
        inner join cat_v3_sucursales cs on tst.Idsucursal = cs.Id
        inner join t_correctivos_generales tcg on tcg.IdServicio = tst.Id
        inner join t_correctivos_diagnostico tcd on tcd.Id = (select MAX(Id) from t_correctivos_diagnostico where IdServicio = tst.Id)
        inner join t_equipos_allab tea on tea.IdServicio = tst.Id and tea.IdEstatus <> 6
        left join t_inventario ti on tea.IdInventarioRespaldo = ti.Id and tea.IdInventarioRespaldo > 0
        where tst.Id = '" . $serviceId . "'")[0];
    }

    public function getReceiptHistory($serviceId)
    {
        return $this->consulta("
        select 
        estatus(tear.IdEstatus) as Estatus,
        nombreUsuario(tear.IdUsuario) as Usuario,
        tear.Fecha
        from t_equipos_allab_recepciones tear 
        where tear.IdRegistro = (select Id from t_equipos_allab where IdServicio = '" . $serviceId . "' and IdEstatus <> 6)
        order by Fecha desc");
    }

    public function getLaboratoryCommentsHistory($serviceId)
    {
        return $this->consulta("
        select 
        nombreUsuario(tearlh.IdUsuario) as Usuario,
        (select Nombre from cat_perfiles where Id = (select IdPerfil from cat_v3_usuarios where Id = tearlh.IdUsuario)) as Perfil,
        tearlh.Fecha,
        tearlh.Comentarios as Descripcion,
        tearlh.Archivos as Evidencias
        from t_equipos_allab_revision_laboratorio tearl
        inner join t_equipos_allab_revision_laboratorio_historial tearlh on tearl.Id = tearlh.IdRevision
        where tearl.IdRegistro = (select Id from t_equipos_allab where IdServicio = '" . $serviceId . "' and IdEstatus <> 6)");
    }

    public function getLaboratoryRevisionHistory($serviceId)
    {
        return $this->consulta("SELECT
                                tearlh.*
                                FROM t_equipos_allab_revision_laboratorio_historial tearlh
                                        INNER JOIN t_equipos_allab_revision_laboratorio tearl
                                        ON tearlh.IdRevision = tearl.Id
                                where tearl.IdRegistro = (select Id from t_equipos_allab where IdServicio = '" . $serviceId . "' and IdEstatus <> 6)");
    }
}
