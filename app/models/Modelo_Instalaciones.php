<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;
use Ratchet\Wamp\Exception;

class Modelo_Instalaciones extends Modelo_Base
{

    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }


    public function getInstalacionesPendientes(int $idUsuario = null)
    {
        $condicion = " and IdEstatus in (1,2,3,4,10) ";
        if (!is_null($idUsuario)) {
            $condicion = " and IdEstatus in (1,2,3,10) and tst.Atiende = '" . $idUsuario . "'";
        }

        $consulta = $this->consulta("select
        tst.Id,
        tst.Ticket,
        folioByServicio(tst.Id) as Folio,
        tipoServicio(tst.IdTipoServicio) as TipoServicio,
        nombreUsuario(tst.Atiende) as Atiende,
        sucursal(tst.IdSucursal) as Sucursal,
        tst.FechaCreacion,
        estatus(tst.IdEstatus) as Estatus
        
        from t_servicios_ticket tst
        where IdTipoServicio in (select Id from cat_v3_servicios_departamento where Instalacion = 1 and Flag = 1)
        " . $condicion);
        return $consulta;
    }

    public function getGeneralesServicio(int $servicio)
    {
        $consulta = $this->consulta("select 
        tst.Id,        
        folioByServicio(tst.Id) as SD,
        tst.Ticket,
        nombreUsuario(tst.Atiende) as Atiende,
        (select FechaCreacion from t_solicitudes where Id = tst.IdSolicitud) as FechaSolicitud,
        tst.FechaCreacion,
        tst.FechaInicio,
        tst.Descripcion,
        tst.IdSolicitud,
        nombreUsuario(ts.Solicita) as Solicita,
        ts.FechaCreacion,
        tsi.Asunto,
        tsi.Descripcion as Solicitud,
        tst.IdSucursal,
        tst.IdEstatus,
        tst.IdTipoServicio,
        sucursal(tst.IdSucursal) as Sucursal, 
        (select IdCliente from cat_v3_sucursales where Id = tst.IdSucursal) as IdCliente,
        cliente((select IdCliente from cat_v3_sucursales where Id = tst.IdSucursal)) as Cliente       
        from t_servicios_ticket tst
        inner join t_solicitudes ts on tst.IdSolicitud = ts.Id
        inner join t_solicitudes_internas tsi on tsi.IdSolicitud = ts.Id
        where tst.Id = '" . $servicio . "'");

        return $consulta;
    }

    public function iniciarInstalacion(int $servicio)
    {
        $this->iniciaTransaccion();

        $this->actualizar("t_servicios_ticket", [
            'IdEstatus' => 2,
            'FechaInicio' => $this->getFecha()
        ], ['Id' => $servicio]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function guardarSucursalServicio(int $servicio, int $sucursal)
    {
        $this->iniciaTransaccion();

        $this->actualizar("t_servicios_ticket", [
            'IdSucursal' => $sucursal,
        ], ['Id' => $servicio]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Cambios guardados"
            ];
        }
    }

    public function guardarInstaladosLexmark(array $datos)
    {
        $this->iniciaTransaccion();

        $registroImpresora = $this->consulta("
        select 
        * 
        from t_instalaciones_equipos 
        where IdServicio = '" . $datos['servicio'] . "' 
        and IdModelo = '655'");
        if (!empty($registroImpresora) && isset($registroImpresora[0]) && isset($registroImpresora[0]['Id'])) {
            $this->actualizar("t_instalaciones_equipos", [
                'IdArea' => $datos['instalados']['impresora']['area'],
                'Punto' => $datos['instalados']['impresora']['punto'],
                'Serie' => $datos['instalados']['impresora']['serie']
            ], ['Id' => $registroImpresora[0]['Id']]);

            $registroAdicionalesImp = $this->consulta("
            select * 
            from t_instalaciones_adicionales_45 
            where IdInstalacion = '" . $registroImpresora[0]['Id'] . "'");
            if (!empty($registroAdicionalesImp) && isset($registroAdicionalesImp[0]) && isset($registroAdicionalesImp[0]['Id'])) {
                $this->actualizar("t_instalaciones_adicionales_45", [
                    'IP' => $datos['instalados']['impresora']['ip'],
                    'MAC' => $datos['instalados']['impresora']['mac'],
                    'Firmware' => $datos['instalados']['impresora']['firmware'],
                    'Contador' => $datos['instalados']['impresora']['contador']
                ], ['IdInstalacion' => $registroImpresora[0]['Id']]);
            } else {
                $this->insertar("t_instalaciones_adicionales_45", [
                    'IdInstalacion' => $registroImpresora[0]['Id'],
                    'IP' => $datos['instalados']['impresora']['ip'],
                    'MAC' => $datos['instalados']['impresora']['mac'],
                    'Firmware' => $datos['instalados']['impresora']['firmware'],
                    'Contador' => $datos['instalados']['impresora']['contador']
                ]);
            }
        } else {
            $this->insertar("t_instalaciones_equipos", [
                'IdServicio' => $datos['servicio'],
                'IdModelo' => 655,
                'IdArea' => $datos['instalados']['impresora']['area'],
                'Punto' => $datos['instalados']['impresora']['punto'],
                'Serie' => $datos['instalados']['impresora']['serie']
            ]);
            $id = $this->ultimoId();
            $this->insertar("t_instalaciones_adicionales_45", [
                'IdInstalacion' => $id,
                'IP' => $datos['instalados']['impresora']['ip'],
                'MAC' => $datos['instalados']['impresora']['mac'],
                'Firmware' => $datos['instalados']['impresora']['firmware'],
                'Contador' => $datos['instalados']['impresora']['contador']
            ]);
        }


        $registroSupresor = $this->consulta("
        select 
        * 
        from t_instalaciones_equipos 
        where IdServicio = '" . $datos['servicio'] . "' 
        and IdModelo = '654'");
        if (!empty($registroSupresor) && isset($registroSupresor[0]) && isset($registroSupresor[0]['Id'])) {
            if ($datos['instalados']['supresor']['serie'] == '' || $datos['instalados']['supresor']['area'] == '') {
                $this->eliminar("t_instalaciones_equipos", ['Id' => $registroSupresor[0]['Id']]);
            } else {
                $this->actualizar("t_instalaciones_equipos", [
                    'IdArea' => $datos['instalados']['supresor']['area'],
                    'Punto' => $datos['instalados']['supresor']['punto'],
                    'Serie' => $datos['instalados']['supresor']['serie']
                ], ['Id' => $registroSupresor[0]['Id']]);
            }
        } else {
            if ($datos['instalados']['supresor']['serie'] != '' || $datos['instalados']['supresor']['area'] != '') {
                $this->insertar("t_instalaciones_equipos", [
                    'IdServicio' => $datos['servicio'],
                    'IdModelo' => 654,
                    'IdArea' => $datos['instalados']['supresor']['area'],
                    'Punto' => $datos['instalados']['supresor']['punto'],
                    'Serie' => $datos['instalados']['supresor']['serie']
                ]);
            }
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Cambios guardados"
            ];
        }
    }

    public function getEquiposInstaladosLexmark(int $servicio)
    {
        $this->iniciaTransaccion();

        $consulta = $this->consulta("select 
        tie.IdArea,        
        tie.Punto,
        tie.Serie,
        tia.IP,
        tia.MAC,
        tia.Firmware,
        tia.Contador,
        areaAtencion(tie.IdArea) as Area,
        modelo(tie.IdModelo) as Modelo
        from t_instalaciones_equipos tie
        left join t_instalaciones_adicionales_45 tia on tie.Id = tia.IdInstalacion
        where tie.IdServicio = '" . $servicio . "' and IdModelo= 655");

        $impresora = [
            'IdArea' => '',
            'Area' => '',
            'Modelo' => '',
            'Punto' => '',
            'Serie' => '',
            'IP' => '',
            'MAC' => '',
            'Firmware' => '',
            'Contador' => ''
        ];
        if (!empty($consulta) && isset($consulta[0])) {
            $impresora = $consulta[0];
        }

        $consulta = $this->consulta("select 
        tie.IdArea,
        tie.Punto,
        tie.Serie,
        areaAtencion(tie.IdArea) as Area, 
        modelo(tie.IdModelo) as Modelo 
        from t_instalaciones_equipos tie        
        where tie.IdServicio = '" . $servicio . "' and tie.IdModelo= 654");

        $supresor = [
            'IdArea' => '',
            'Area' => '',
            'Modelo' => '',
            'Punto' => '',
            'Serie' => ''
        ];

        if (!empty($consulta) && isset($consulta[0])) {
            $supresor = $consulta[0];
        }

        $result = [
            'impresora' => $impresora,
            'supresor' => $supresor
        ];

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Información Correcta",
                'result' => $result
            ];
        }
    }

    public function getModelosKyocera()
    {
        $consulta = $this->consulta("select
        cme.Id,
        linea(lineaByModelo(cme.Id)) as Linea,
        sublinea(sublineaByModelo(cme.Id)) as Sublinea,
        marca(cme.Marca) as Marca,
        cme.Nombre
        from cat_v3_modelos_equipo cme
        where cme.Marca in (51,92,134)");
        return $consulta;
    }

    public function getKyocerasCensadas(int $sucursal)
    {
        $consulta = $this->consulta("select 
        Id,
        IdArea,
        Punto,
        IdModelo,
        Serie,
        modelo(IdModelo) as Modelo
        from t_censos tc
        where IdServicio = (
            select MAX(Id) 
            from t_servicios_ticket 
            where IdSucursal = 27
            and IdEstatus = 4 
            and IdTipoServicio = 11
        ) and tc.IdModelo in (
            select 	Id from cat_v3_modelos_equipo where Marca in (51,92)
        )");
        return $consulta;
    }

    public function getEstatusRetiro()
    {
        $consulta = $this->consulta("select Id, Nombre from cat_v3_estatus where Id in (42,43,44,45)");
        return $consulta;
    }

    public function guardarRetiradosLexmark(array $datos)
    {
        $this->iniciaTransaccion();

        $registroImpresora = $this->consulta("
        select 
        * 
        from t_retiros_equipos 
        where IdServicio = '" . $datos['servicio'] . "'");
        if (!empty($registroImpresora) && isset($registroImpresora[0]) && isset($registroImpresora[0]['Id'])) {
            $this->actualizar("t_retiros_equipos", [
                'IdModelo' => $datos['retirados']['impresora']['modelo'],
                'IdEstatus' => $datos['retirados']['impresora']['estatus'],
                'Serie' => $datos['retirados']['impresora']['serie']
            ], ['Id' => $registroImpresora[0]['Id']]);
        } else {
            $this->insertar("t_retiros_equipos", [
                'IdServicio' => $datos['servicio'],
                'IdModelo' => $datos['retirados']['impresora']['modelo'],
                'IdEstatus' => $datos['retirados']['impresora']['estatus'],
                'Serie' => $datos['retirados']['impresora']['serie']
            ]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Cambios guardados"
            ];
        }
    }

    public function getImpresoraRetirada(int $servicio)
    {
        $this->iniciaTransaccion();

        $consulta = $this->consulta("select 
        tre.IdModelo,    
        tre.IdEstatus,
        tre.Serie,
        modelo(tre.IdModelo) as Modelo,
        estatus(tre.IdEstatus) as Estatus
        from t_retiros_equipos tre        
        where tre.IdServicio = '" . $servicio . "'");

        $impresora = [
            'IdModelo' => '',
            'IdEstatus' => '',
            'Serie' => '',
            'Modelo' => '',
            'Estatus' => ''
        ];
        if (!empty($consulta) && isset($consulta[0])) {
            $impresora = $consulta[0];
        }

        $result = [
            'impresora' => $impresora
        ];

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Información Correcta",
                'result' => $result
            ];
        }
    }

    public function getTiposEvidencia(int $tipoServicio, int $servicio = null)
    {
        $condicion = " and IdTipoServicio = '" . $tipoServicio . "' ";
        if (!is_null($servicio)) {
            $condicion .= " and Id not in ((select IdEvidencia from t_instalaciones_evidencias where IdServicio = '" . $servicio . "'))";
        }
        $consulta = $this->consulta("
        select 
        Id, 
        Nombre 
        from cat_v3_instalaciones_evidencias 
        where Flag = 1 and XEquipo = 0 " . $condicion . " order by Nombre");
        return $consulta;
    }

    public function getTiposEvidenciaRetiro(int $tipoServicio, int $servicio = null)
    {
        $condicion = '';
        if (!is_null($servicio)) {
            $condicion = " and Id not in ((select IdEvidencia from t_retiros_evidencias where IdServicio = '" . $servicio . "'))";
        }
        $consulta = $this->consulta("
        select 
        Id, 
        Nombre 
        from cat_v3_retiros_evidencias 
        where Flag = 1 " . $condicion . " order by Nombre");
        return $consulta;
    }

    public function registrarArchivosInstalacion(array $datos)
    {
        $this->iniciaTransaccion();

        $registroEvidencia = $this->consulta("
        select * 
        from t_instalaciones_evidencias 
        where IdServicio = '" . $datos['id'] . "' 
        and IdEvidencia = '" . $datos['evidencia'] . "'");

        if (!empty($registroEvidencia) && isset($registroEvidencia[0]) && isset($registroEvidencia[0]['Id'])) {
            $this->actualizar("t_instalaciones_evidencias", [
                'Archivo' => $datos['archivos']
            ], ['Id' => $registroEvidencia[0]['Id']]);
        } else {
            $this->insertar("t_instalaciones_evidencias", [
                'IdServicio' => $datos['id'],
                'IdEvidencia' => $datos['evidencia'],
                'Archivo' => $datos['archivos']
            ]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Se ha agregado una nueva evidencia a la instalación"
            ];
        }
    }

    public function registrarArchivosRetiro(array $datos)
    {
        $this->iniciaTransaccion();

        $registroEvidencia = $this->consulta("
        select * 
        from t_retiros_evidencias 
        where IdServicio = '" . $datos['id'] . "' 
        and IdEvidencia = '" . $datos['evidencia'] . "'");

        if (!empty($registroEvidencia) && isset($registroEvidencia[0]) && isset($registroEvidencia[0]['Id'])) {
            $this->actualizar("t_retiros_evidencias", [
                'Archivo' => $datos['archivos']
            ], ['Id' => $registroEvidencia[0]['Id']]);
        } else {
            $this->insertar("t_retiros_evidencias", [
                'IdServicio' => $datos['id'],
                'IdEvidencia' => $datos['evidencia'],
                'Archivo' => $datos['archivos']
            ]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Se ha agregado una nueva evidencia a la instalación"
            ];
        }
    }

    public function getEvidenciasInstalacion(int $servicio)
    {
        $consulta = $this->consulta("
        select 
        Id, 
        IdEvidencia,
        Archivo,
        (select Nombre from cat_v3_instalaciones_evidencias where Id = IdEvidencia) as Evidencia
        from t_instalaciones_evidencias where IdServicio = '" . $servicio . "'");
        return $consulta;
    }

    public function getEvidenciasRetiro(int $servicio)
    {
        $consulta = $this->consulta("
        select 
        Id, 
        IdEvidencia,
        Archivo,
        (select Nombre from cat_v3_retiros_evidencias where Id = IdEvidencia) as Evidencia
        from t_retiros_evidencias where IdServicio = '" . $servicio . "'");
        return $consulta;
    }

    public function eliminarEvidenciaInstalacion(int $id)
    {
        $this->iniciaTransaccion();

        $archivo = $this->consulta("select Archivo from t_instalaciones_evidencias where Id = '" . $id . "'");

        $this->eliminar("t_instalaciones_evidencias", ['Id' => $id]);

        if (unlink('.' . $archivo[0]['Archivo'])) { } else {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => 'No se ha podido eliminar el archivo'
            ];
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Se ha eliminado la evidencia de la instalación"
            ];
        }
    }

    public function eliminarEvidenciaRetiro(int $id)
    {
        $this->iniciaTransaccion();

        $archivo = $this->consulta("select Archivo from t_retiros_evidencias where Id = '" . $id . "'");

        $this->eliminar("t_retiros_evidencias", ['Id' => $id]);

        if (unlink('.' . $archivo[0]['Archivo'])) { } else {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => 'No se ha podido eliminar el archivo'
            ];
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Se ha eliminado la evidencia del retiro"
            ];
        }
    }

    public function guardarMaterial(array $datos)
    {
        $this->iniciaTransaccion();

        if (!isset($datos['id'])) {
            $this->insertar("t_instalaciones_material", [
                "IdServicio" => $datos['servicio'],
                "Clave" => $datos['clave'],
                "Producto" => $datos['producto'],
                "Cantidad" => $datos['cantidad']
            ]);
            $id = $this->ultimoId();
        } else {
            $this->actualizar("t_instalaciones_material", [
                "Cantidad" => $datos['cantidad']
            ], ['Id' => $datos['id']]);
            $id = $datos['id'];
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Material agregado al servicio.",
                'id' => $id
            ];
        }
    }

    public function eliminarMaterial(array $datos)
    {
        $this->iniciaTransaccion();

        $this->eliminar("t_instalaciones_material", [
            'Id' => $datos['id']
        ]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Material eliminado"
            ];
        }
    }

    public function materialesUtilizados(int $servicio)
    {
        $consultaLista = $this->consulta("
        select
        Id,
        Clave,
        Producto,
        Cantidad
        from t_instalaciones_material
        where IdServicio = '" . $servicio . "'");

        $consultaClaves = $this->consulta("
        select 
        group_concat(Clave) as Claves
        from t_instalaciones_material
        where IdServicio = '" . $servicio . "'");

        return [
            'lista' => $consultaLista,
            'claves' => explode(",", $consultaClaves[0]['Claves'])
        ];
    }

    public function guardarFirma(array $datos, string $url)
    {
        $this->iniciaTransaccion();

        if ($datos['tipo'] == "gerente") {
            $this->actualizar("t_servicios_ticket", [
                'Firma' => $url,
                'NombreFirma' => $datos['nombre'],
                'FechaFirma' => $this->getFecha()
            ], ['Id' => $datos['servicio']]);
        } else if ($datos['tipo'] == "tecnico") {
            $this->actualizar("t_servicios_ticket", [
                'FirmaTecnico' => $url,
                'IdTecnicoFirma' => $this->usuario['Id']
            ], ['Id' => $datos['servicio']]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Firma agregada"
            ];
        }
    }

    public function getFirmasServicio(int $servicio)
    {
        $consulta = $this->consulta("
        select 
        Firma,
        NombreFirma as Gerente,
        FechaFirma,
        nombreUsuario(tst.IdTecnicoFirma) as Tecnico,
        FirmaTecnico
        from t_servicios_ticket tst where Id = '" . $servicio . "'");
        return $consulta;
    }

    public function concluirServicio(int $servicio)
    {
        $this->iniciaTransaccion();

        $this->actualizar("t_servicios_ticket", [
            'IdEstatus' => 4,
            'FechaConclusion' => $this->getFecha()
        ], ['Id' => $servicio]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200
            ];
        }
    }

    public function getModelosAntenas()
    {
        $consulta = $this->consulta("
        select
        cme.Id,
        marca(cme.Marca) as Marca,
        cme.Nombre
        from cat_v3_modelos_equipo cme
        where sublineaByModelo(cme.Id) in (17)
        and Flag = 1
        order by Marca, Nombre");
        return $consulta;
    }

    public function getModelosSwitch()
    {
        $consulta = $this->consulta("
        select
        cme.Id,
        marca(cme.Marca) as Marca,
        cme.Nombre
        from cat_v3_modelos_equipo cme
        where sublineaByModelo(cme.Id) in (28)
        and Flag = 1
        order by Marca, Nombre");
        return $consulta;
    }

    public function guardarAntena(array $datos)
    {
        $this->iniciaTransaccion();

        $consulta = $this->consulta("
        select 
        MIN(tie.Id) as A1,
        MAX(tie.Id) as A2
        from t_instalaciones_equipos tie
        where tie.IdServicio = '" . $datos['servicio'] . "'");

        $registroAntena1 = $consulta[0]['A1'];
        $registroAntena2 = $consulta[0]['A2'];

        if ($registroAntena1 == $registroAntena2) {
            $registroAntena2 = null;
        }

        $accion = '';
        $registroActualizar = 0;

        if ($registroAntena1 == '' || is_null($registroAntena1)) {
            $accion = 'insertar';
        } else if ($datos['posicion'] == 1 && $registroAntena1 > 0) {
            $accion = 'actualizar';
            $registroActualizar = $registroAntena1;
        } else if ($datos['posicion'] == 2 && $registroAntena1 > 0 && ($registroAntena2 == '' || is_null($registroAntena2))) {
            $accion = 'insertar';
        } else if ($datos['posicion'] == 2 && $registroAntena1 > 0 && $registroAntena2 > 0) {
            $accion = 'actualizar';
            $registroActualizar = $registroAntena2;
        }

        if ($accion == 'insertar') {
            $this->insertar("t_instalaciones_equipos", [
                'IdServicio' => $datos['servicio'],
                'IdModelo' => $datos['antena']['modelo'],
                'IdArea' => $datos['antena']['ubicacion'],
                'Punto' => 0,
                'Serie' => $datos['antena']['serie'],
            ]);

            $id = $this->ultimoId();

            $this->insertar("t_instalaciones_adicionales_48", [
                'IdInstalacion' => $id,
                'MAC' => $datos['antena']['mac'],
                'POE' => $datos['antena']['poe'],
                'SeriePOE' => ($datos['antena']['poe'] == 1) ? $datos['antena']['seriePoe'] : '',
                'IdModeloSwitch' => $datos['antena']['modeloSwitch'],
                'NumeroSwitch' => $datos['antena']['numeroSwitch'],
                'PuertoSwitch' => $datos['antena']['puertoSwitch']
            ]);
        } else if ($accion == "actualizar") {
            $this->actualizar("t_instalaciones_equipos", [
                'IdModelo' => $datos['antena']['modelo'],
                'IdArea' => $datos['antena']['ubicacion'],
                'Serie' => $datos['antena']['serie'],
            ], [
                'Id' => $registroActualizar
            ]);

            $this->actualizar("t_instalaciones_adicionales_48", [
                'MAC' => $datos['antena']['mac'],
                'POE' => $datos['antena']['poe'],
                'SeriePOE' => ($datos['antena']['poe'] == 1) ? $datos['antena']['seriePoe'] : '',
                'IdModeloSwitch' => $datos['antena']['modeloSwitch'],
                'NumeroSwitch' => $datos['antena']['numeroSwitch'],
                'PuertoSwitch' => $datos['antena']['puertoSwitch']
            ], ['IdInstalacion' => $registroActualizar]);
        }



        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Cambios guardados"
            ];
        }
    }

    public function eliminarAntena(array $datos)
    {
        $this->iniciaTransaccion();

        $archivosBorrar = [];
        $evidencias = $this->consulta("select
        Archivo
        from t_instalaciones_evidencias_equipo 
        where IdInstalacion = '" . $datos['id'] . "'");
        foreach ($evidencias as $key => $value) {
            array_push($archivosBorrar, $value['Archivo']);
        }

        $this->eliminar("t_instalaciones_evidencias_equipo", ['IdInstalacion' => $datos['id']]);
        $this->eliminar("t_instalaciones_adicionales_48", ['IdInstalacion' => $datos['id']]);
        $this->eliminar("t_instalaciones_equipos", ['Id' => $datos['id']]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            foreach ($archivosBorrar as $key => $value) {
                unlink('.' . $value);
            }

            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Antena eliminada"
            ];
        }
    }

    public function getAntenasInstaladas(int $servicio)
    {
        $this->iniciaTransaccion();

        $consulta = $this->consulta("select 
        tie.Id,
        tie.IdModelo,
        tie.IdArea,
        tie.Serie,
        tia.MAC,
        tia.POE,
        tia.SeriePOE,
        tia.IdModeloSwitch,
        tia.NumeroSwitch,
        tia.PuertoSwitch,
        modelo(tie.IdModelo) as Modelo,
        areaAtencion(tie.IdArea) as Ubicacion,
        modelo(tia.IdModeloSwitch) as ModeloSwitch
        from t_instalaciones_equipos tie
        left join t_instalaciones_adicionales_48 tia on tie.Id = tia.IdInstalacion
        where IdServicio = '" . $servicio . "'
        order by tie.Id");

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Información Correcta",
                'result' => $consulta
            ];
        }
    }

    public function getEquiposInstalados(int $servicio)
    {
        $consulta = $this->consulta("
        select
        tie.*,
        modelo(tie.IdModelo) as Equipo,
        marca(marcaByModelo(tie.IdModelo)) as Marca,
        (select Nombre from cat_v3_modelos_equipo where Id = tie.IdModelo) as Modelo,
        areaAtencion(tie.IdArea) as Area
        from 
        t_instalaciones_equipos tie
        where IdServicio = '" . $servicio . "'
        order by Id");
        return $consulta;
    }

    public function getEvidenciasRequeridasXEquipo(int $tipoServicio, int $idInstalacion)
    {
        $consulta = $this->consulta("
        select 
        cie.Id,
        cie.Nombre
        from cat_v3_instalaciones_evidencias cie
        where cie.IdTipoServicio = '" . $tipoServicio . "'
        and cie.XEquipo = 1
        and cie.Flag = 1
        and cie.Id not in (
                        select 
                        IdEvidencia 
                        from t_instalaciones_evidencias_equipo 
                        where IdInstalacion = '" . $idInstalacion . "')");
        return $consulta;
    }

    public function getEvidenciasXEquipoInstalado(int $instalacion)
    {
        $consulta = $this->consulta("
        select
        tiee.Id,
        tiee.Archivo,
        cie.Nombre as Evidencia
        from t_instalaciones_equipos tie 
        inner join t_instalaciones_evidencias_equipo tiee on tie.Id = tiee.IdInstalacion
        inner join cat_v3_instalaciones_evidencias cie on tiee.IdEvidencia = cie.Id    
        where tie.Id = '" . $instalacion . "'");
        return $consulta;
    }

    public function registrarArchivosInstalacionEquipo(array $datos)
    {
        $this->iniciaTransaccion();

        $registroEvidencia = $this->consulta("
        select * 
        from t_instalaciones_evidencias_equipo 
        where IdInstalacion = '" . $datos['instalacion'] . "' 
        and IdEvidencia = '" . $datos['evidencia'] . "'");

        if (!empty($registroEvidencia) && isset($registroEvidencia[0]) && isset($registroEvidencia[0]['Id'])) {
            $this->actualizar("t_instalaciones_evidencias_equipo", [
                'Archivo' => $datos['archivos']
            ], ['Id' => $registroEvidencia[0]['Id']]);
        } else {
            $this->insertar("t_instalaciones_evidencias_equipo", [
                'IdInstalacion' => $datos['instalacion'],
                'IdEvidencia' => $datos['evidencia'],
                'Archivo' => $datos['archivos']
            ]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Se ha agregado una nueva evidencia a la instalación"
            ];
        }
    }

    public function eliminarEvidenciaInstalacionEquipo(int $id)
    {
        $this->iniciaTransaccion();

        $archivo = $this->consulta("select Archivo from t_instalaciones_evidencias_equipo where Id = '" . $id . "'");

        $this->eliminar("t_instalaciones_evidencias_equipo", ['Id' => $id]);

        if (unlink('.' . $archivo[0]['Archivo'])) { } else {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => 'No se ha podido eliminar el archivo'
            ];
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => "Se ha eliminado la evidencia de la instalación del equipo"
            ];
        }
    }
}
