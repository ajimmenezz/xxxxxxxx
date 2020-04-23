<?php

namespace Librerias\Poliza;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Componentes\Error as Error;
use Librerias\Generales\SimpleXLSX as SimpleXLSX;

class Seguimientos extends General {

    private $DBS;
    private $Notificacion;
    private $DBST;
    private $DBP;
    private $DBB;
    private $Catalogo;
    private $Correo;
    private $ServiceDesk;
    private $InformacionServicios;
    private $MSP;
    private $usuario;
    private $MSicsa;
    private $DBCensos;
    private $Excel;
    private $SimpleXLSX;
    private $db;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Loguistica_Seguimiento::factory();
        $this->DBB = \Modelos\Modelo_Busqueda::factory();
        $this->Notificacion = \Librerias\Generales\Notificacion::factory();
        $this->DBST = \Modelos\Modelo_ServicioTicket::factory();
        $this->DBP = \Modelos\Modelo_Poliza::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->ServiceDesk = \Librerias\WebServices\ServiceDesk::factory();
        $this->InformacionServicios = \Librerias\WebServices\InformacionServicios::factory();
        $this->MSP = \Modelos\Modelo_SegundoPlano::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
        $this->MSicsa = \Modelos\Modelo_Sicsa::factory();
        $this->DBCensos = \Modelos\Modelo_Censos::factory();
        $this->Excel = new \Librerias\Generales\CExcel();
        $this->DBIC = \Modelos\Modelo_InventarioConsignacion::factory();
        $this->db = \Modelos\Modelo_DeviceTransfer::factory();

        parent::getCI()->load->helper('dividestringconviertearray');
    }

    public function consultaTodosCensoServicio(string $servicio) {
        $areasPuntos = $this->DBS->consulta("select 
                                        tcp.Id,
                                        tcp.IdArea,
                                        areaAtencion(tcp.IdArea) as Area,
                                        tcp.Puntos
                                        from
                                        t_censos_puntos tcp
                                        where tcp.IdServicio = '" . $servicio . "'
                                        order by Area");

        $censo = $this->DBS->consulta('SELECT 
                                        tc.*,                
                                        cvaa.Nombre as Sucursal,
                                        cvme.Nombre as Modelo,
                                        cvmae.Nombre as Marca,
                                        cvle.Nombre as Linea 
                                        FROM t_censos tc inner join cat_v3_areas_atencion cvaa
                                        on tc.IdArea = cvaa.Id
                                        inner join cat_v3_modelos_equipo cvme
                                        on tc.IdModelo = cvme.Id 
                                        inner join cat_v3_marcas_equipo cvmae
                                        on cvme.Marca = cvmae.Id 
                                        inner join cat_v3_sublineas_equipo cvse
                                        on cvmae.Sublinea = cvse.Id 
                                        inner join cat_v3_lineas_equipo cvle
                                        on cvse.Linea = cvle.Id
                                      WHERE IdServicio = "' . $servicio . '"
                                      ORDER BY Sucursal, Punto, Linea ASC');

        return [
            'areaspuntos' => $areasPuntos,
            'censo' => $censo
        ];
    }

    public function consultaAreaPuntoXSucursal(string $sucursal, string $agruparX) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                      tc.IdArea,
                                                      tc.Punto,
                                                      cvaa.Nombre as Area
                                                      FROM t_censos tc inner join cat_v3_areas_atencion cvaa
                                                      on tc.IdArea = cvaa.Id
                                                    WHERE IdServicio = (select MAX(tcg.IdServicio) 
                                                    from t_censos_generales tcg 
                                                    inner join t_servicios_ticket tst
                                                    on tcg.IdServicio = tst.Id
                                                    WHERE tcg.IdSucursal = "' . $sucursal . '"
                                                    and tst.IdEstatus = 4)
                                                    group by ' . $agruparX);
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaEquiposFaltantes(string $servicio, string $area, string $punto) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                tmef.*, 
                                                            CASE tmef.TipoItem
                                                                WHEN 1 THEN "Equipo"
                                                                WHEN 2 then "Material"
                                                                WHEN 3 THEN "Refacción"
                                                            END as NombreItem, 
                                                            CASE tmef.TipoItem
                                                                WHEN 1 THEN (SELECT Equipo FROM v_equipos WHERE Id = tmef.Idmodelo)
                                                                WHEN 2 THEN (SELECT Nombre FROM cat_v3_equipos_sae WHERE Id = tmef.Idmodelo)
                                                                WHEN 3 THEN (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tmef.Idmodelo)
                                                            END as Equipo
                                                            FROM t_mantenimientos_equipo_faltante tmef
                                                            WHERE IdServicio = "' . $servicio . '"
                                                            AND IdArea = "' . $area . '"
                                                            AND Punto = "' . $punto . '"
                                                            ORDER BY NombreItem, Equipo ASC');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaProblemasAdicionales(string $servicio) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                tmpa.*, 
                                                                cvaa.Nombre AS Sucursal,
                                                                areaAtencion(tmpa.IdArea) AS Area
                                                                FROM t_mantenimientos_problemas_adicionales tmpa
                                                                INNER JOIN cat_v3_areas_atencion cvaa
                                                                ON tmpa.IdArea = cvaa.Id
                                                                WHERE IdServicio = "' . $servicio . '"
                                                                ORDER BY Area, Punto ASC');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaAntesYDespues(string $servicio, string $area, string $punto) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                                * 
                                                                FROM t_mantenimientos_antes_despues 
                                                                WHERE IdServicio = "' . $servicio . '" 
                                                                AND IdArea = "' . $area . '" 
                                                                AND Punto = "' . $punto . '"');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaPuntosCensadosMantenimiento(string $sucursal, string $servicio) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                                tc.IdServicio,
                                                                tc.IdArea,
                                                                (select Nombre from cat_v3_areas_atencion where Id = tc.IdArea) as Area,
                                                                tc.Punto,
                                                                tc.IdModelo,
                                                                tc.Serie,
                                                                if(
                                                                tmad.IdArea is null or tmad.IdArea = 0
                                                                or tmad.Punto is null or tmad.Punto = 0
                                                                or tmad.ObservacionesAntes is null or tmad.ObservacionesAntes = ""
                                                                or tmad.ObservacionesDespues is null or tmad.ObservacionesDespues = ""
                                                                or tmad.EvidenciasAntes is null or tmad.EvidenciasAntes = ""
                                                                or tmad.EvidenciasDespues is null or tmad.EvidenciasDespues = "", "Sin documentación","Documentado") as Estatus
                                                                from t_censos tc left join t_mantenimientos_antes_despues tmad
                                                                on tmad.IdServicio = "' . $servicio . '" and tmad.IdArea = tc.IdArea and tmad.Punto = tc.Punto
                                                                where tc.IdServicio = (
                                                                                select tcg.IdServicio
                                                                                from t_censos_generales tcg inner join t_servicios_ticket tst 
                                                                                on tcg.IdServicio = tst.Id
                                                                                where tcg.IdSucursal = "' . $sucursal . '"
                                                                                and tst.IdEstatus = 4
                                                                                order by FechaConclusion DESC LIMIT 1
                                                                ) group by Area, Punto;
                                                                ');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaDocumentacionMantenimientoAntesDespues(array $datos) {
        $consulta = $this->consultaPuntosCensadosMantenimiento($datos['sucursal'], $datos['servicio']);
        foreach ($consulta as $value) {
            if ($value['Estatus'] !== 'Documentado') {
                return 'faltaDocumentacion';
            }
        }
        return TRUE;
    }

    public function consultaProblemasEquipos(array $datos) {
        $consulta = $this->DBS->consultaGeneralSeguimiento(
                'select 
                                                            ve.Equipo,
                                                            tc.IdModelo as Modelo,
                                                            tc.Serie,
                                                            tmpe.Observaciones,
                                                            tmpe.Evidencias,
                                                            areaAtencion(tmpe.IdArea) AS Area,
                                                            tmpe.Punto,
                                                            tmpe.IdArea,
                                                            tmpe.IdModelo
                                                            from t_censos tc inner join v_equipos ve
                                                            on tc.IdModelo = ve.Id
                                                            left join t_mantenimientos_problemas_equipo tmpe
                                                            on tmpe.IdServicio = "' . $datos['servicio'] . '" and tmpe.IdArea = tc.IdArea and tmpe.Punto = tc.Punto and tmpe.IdModelo = tc.IdModelo and tmpe.Serie = tc.Serie
                                                            where tc.IdServicio = "' . $datos['servicioCenso'] . '" 
                                                            and tc.IdArea = "' . $datos['area'] . '" 
                                                            and tc.Punto = "' . $datos['punto'] . '"'
        );
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaProblemasEquiposServicio(array $datos) {
        $consulta = $this->DBS->consultaGeneralSeguimiento(
                'SELECT 
                                                                *,
                                                                areaAtencion(IdArea) AS Area,
                                                                (SELECT Equipo FROM v_equipos WHERE Id = IdModelo) AS Equipo
                                                            FROM t_mantenimientos_problemas_equipo 
                                                            WHERE IdServicio = "' . $datos['servicio'] . '" 
                                                            AND IdArea = "' . $datos['area'] . '" 
                                                            AND Punto = "' . $datos['punto'] . '"'
        );
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaExistenteProblemasEquipos(array $datos, string $extra = NULL) {
        $camposMostrar = (is_null($extra)) ? 'Evidencias ' : 'Observaciones ';
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT ' . $camposMostrar . '
                                                                FROM t_mantenimientos_problemas_equipo
                                                                WHERE IdServicio = "' . $datos['servicio'] . '"
                                                                AND IdArea = "' . $datos['area'] . '"
                                                                AND Punto = "' . $datos['punto'] . '"
                                                                AND IdModelo = "' . $datos['modelo'] . '"
                                                                AND Serie = "' . $datos['serie'] . '"');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaEquipoXAreaPuntoUltimoCenso(array $datos) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                      tc.IdModelo,
                                                      tc.Serie,
                                                      tc.Extra,
                                                      (SELECT Equipo FROM v_equipos WHERE Id = tc.IdModelo) AS Equipo
                                                      FROM t_censos tc inner join cat_v3_areas_atencion cvaa
                                                      on tc.IdArea = cvaa.Id
                                                    WHERE tc.IdServicio = (select MAX(tcg.IdServicio) 
                                                    from t_censos_generales tcg 
                                                    inner join t_servicios_ticket tst
                                                    on tcg.IdServicio = tst.Id
                                                    WHERE tcg.IdSucursal = "' . $datos['sucursal'] . '"
                                                    AND tc.IdArea = "' . $datos['area'] . '"
                                                    AND tc.Punto = "' . $datos['punto'] . '"
                                                    and tst.IdEstatus = 4)
                                                    ORDER BY Equipo ASC');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaTiposFallasEquipos(array $datos) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                cvtf.Id,
                                                                (SELECT CONCAT ((SELECT Nombre FROM cat_v3_clasificaciones_falla WHERE Id = cvtf.IdClasificacion), " - ", cvtf.Nombre)) AS Nombre
                                                            FROM 
                                                                cat_v3_tipos_falla cvtf
                                                            INNER JOIN cat_v3_fallas_equipo cvfe
                                                                ON cvfe.IdTipoFalla = cvtf.Id
                                                            WHERE cvfe.IdModeloEquipo = "' . $datos['equipo'] . '"
                                                            AND cvtf.Flag = 1
                                                            AND cvtf.Id not in(7,67,102)
                                                            GROUP BY cvtf.Id
                                                            ORDER BY Nombre');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaTiposFallasEquiposImpericia(array $datos) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                cvtf.Id,
                                                                (SELECT CONCAT ((SELECT Nombre FROM cat_v3_clasificaciones_falla WHERE Id = cvtf.IdClasificacion), " - ", cvtf.Nombre)) AS Nombre
                                                            FROM 
                                                                cat_v3_tipos_falla cvtf
                                                            INNER JOIN cat_v3_fallas_equipo cvfe
                                                                ON cvfe.IdTipoFalla = cvtf.Id
                                                            WHERE cvfe.IdModeloEquipo = "' . $datos['equipo'] . '"
                                                            AND cvtf.Flag = 1
                                                            AND cvtf.Id in(7,67,102)
                                                            GROUP BY cvtf.Id
                                                            ORDER BY Nombre');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaFallasEquiposXTipoFallaYEquipo(array $datos) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                Id,
                                                                Nombre 
                                                            FROM 
                                                                cat_v3_fallas_equipo
                                                            WHERE IdTipoFalla = "' . $datos['tipoFalla'] . '"
                                                            AND IdModeloEquipo = "' . $datos['equipo'] . '"
                                                            AND Flag = 1');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaTipoFallaXRefaccion(array $datos) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                cvfr.IdTipoFalla,
                                                                (SELECT Nombre FROM cat_v3_tipos_falla WHERE Id = cvfr.IdTipoFalla) AS NombreTipo
                                                            FROM 
                                                                cat_v3_fallas_refaccion cvfr
                                                            WHERE cvfr.IdRefaccion = "' . $datos['componente'] . '"
                                                            AND cvfr.Flag = 1
                                                            GROUP BY cvfr.IdTipoFalla');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaFallasRefacionXTipoFalla(array $datos) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                Id,
                                                                Nombre
                                                            FROM 
                                                                cat_v3_fallas_refaccion
                                                            WHERE IdTipoFalla = "' . $datos['tipoFalla'] . '"
                                                            AND IdRefaccion = "' . $datos['componente'] . '"
                                                            AND Flag = 1');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaFallasRefacionXTipoFallaChecklist(array $datos) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                Id,
                                                                Nombre
                                                            FROM 
                                                                cat_v3_fallas_refaccion
                                                            WHERE IdTipoFalla = "' . $datos['tipoFalla'] . '"
                                                            AND IdRefaccion = "' . $datos['componente'] . '"
                                                            AND Flag = 1');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaRefacionXEquipo(array $datos) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                Id,
                                                                Nombre
                                                            FROM 
                                                                cat_v3_componentes_equipo
                                                            WHERE IdModelo = "' . $datos['equipo'] . '"
                                                            AND Flag = 1');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaCorreoSupervisorXSucursal(string $sucursal) {
        $correoSupervisor = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                        (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = cvrc.IdResponsableInterno) AS CorreoSupervisor,
                                                                        usuario(cvrc.IdResponsableInterno) NombreSupervisor
                                                                    FROM 
                                                                        cat_v3_sucursales cvs
                                                                    INNER JOIN cat_v3_regiones_cliente cvrc
                                                                        ON cvrc.Id = cvs.IdRegionCliente
                                                                    WHERE cvs.Id = "' . $sucursal . '"');
        if (!empty($correoSupervisor)) {
            return $correoSupervisor;
        } else {
            return FALSE;
        }
    }

    public function consultaEquiposXLinea(array $datos) {
        $idLinea = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                cvse.Id,
                                                                cvse.Linea
                                                            FROM cat_v3_modelos_equipo cvme
                                                            INNER JOIN cat_v3_marcas_equipo cvmeq
                                                                ON cvmeq.Id = Marca
                                                            INNER JOIN cat_v3_sublineas_equipo cvse
                                                                ON cvse.Id = Sublinea
                                                            WHERE cvme.Id = "' . $datos['equipo'] . '"
                                                            AND cvme.Flag = "1"');
        if (!empty($idLinea)) {
            if ($idLinea[0]['Linea'] === '1') {
                $lineas = '1,10';
            } else {
                $lineas = $idLinea[0]['Linea'];
            }
        } else {
            $lineas = '""';
        }

        $consulta = $this->DBS->consultaGeneralSeguimiento('select 
                                                                cvle.Id as IdLinea,                
                                                                cvmoe.Id as IdMod,
                                                                cvle.Nombre as Linea,
                                                                cvme.Nombre as Marca,
                                                                cvmoe.Nombre as Modelo							
                                                            from cat_v3_lineas_equipo cvle inner join cat_v3_sublineas_equipo cvse
                                                                on cvle.Id = cvse.Linea
                                                            inner join cat_v3_marcas_equipo cvme
                                                                on cvse.Id = cvme.Sublinea
                                                            inner join cat_v3_modelos_equipo cvmoe
                                                                on cvme.Id = cvmoe.Marca 
                                                            WHERE cvmoe.Flag = 1
                                                            AND cvle.Id in(' . $lineas . ')
                                                            order by Linea, Marca, Modelo');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaCatalogoSolucionesEquipoXEquipo(array $datos) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                Id,
                                                                Nombre
                                                            FROM 
                                                            cat_v3_soluciones_equipo
                                                            WHERE IdModelo = "' . $datos['equipo'] . '"');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaCorrectivosSolucionesServicio(array $datos) {
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                             Evidencias 
                                                            FROM t_correctivos_soluciones 
                                                            WHERE IdServicio = "' . $datos['servicio'] . '" 
                                                            AND IdTipoSolucion = "' . $datos['idTipoSolucion'] . '" 
                                                            ORDER BY Fecha DESC LIMIT 1');
        if (!empty($consulta)) {
            if ($consulta[0]['Evidencias'] !== null || $consulta[0] !== '') {
                if ($consulta[0]['Evidencias'] !== '') {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function consultaCorrectivosServiciosTicket(string $ticket, string $servicio) {
        $sentencia = 'SELECT 
                            *
                        FROM t_servicios_ticket
                        WHERE ticket = "' . $ticket . '"
                        AND Id <> "' . $servicio . '"
                        AND IdTipoServicio = 20
                        AND IdEstatus IN(10,3,2,1)';
        return $this->DBS->consultaGeneralSeguimiento($sentencia);
    }

    public function consultaCorrectivosSolicitudEquipo(string $servicio) {
        $sentencia = 'select 
                            tcse.IdServicio as Servicio,
                            nombreUsuario(tst.Solicita) as Solicitante,
                            tst.FechaCreacion,
                            estatus(tst.IdEstatus) as Estatus,
                            group_concat(ve.Equipo," _ ",tcse.Cantidad) as EquipoCantidad,
                            group_concat(tcse.Id) as Id
                        from t_correctivos_solicitudes_equipo tcse inner join t_servicios_ticket tst
                            on tcse.IdServicio = tst.Id
                        inner join v_equipos ve
                            on tcse.IdModelo = ve.Id
                        where tcse.IdServicioOrigen = "' . $servicio . '" 
                        group by Servicio';

        return $this->DBS->consultaGeneralSeguimiento($sentencia);
    }

    public function consultaCorrectivosSolicitudRefaccion(string $servicio) {
        $sentencia = 'select
                            tcsr.IdServicio as Servicio,
                            nombreUsuario(tst.Solicita) as Solicitante,
                            tst.FechaCreacion,
                            estatus(tst.IdEstatus) as Estatus,
                            group_concat(cvce.Nombre," - ",tcsr.Cantidad) as RefaccionCantidad,
                            group_concat(tcsr.Id) as Id
                        from t_correctivos_solicitudes_refaccion tcsr inner join t_servicios_ticket tst
                            on tcsr.IdServicio = tst.Id
                        inner join cat_v3_componentes_equipo cvce
                            on tcsr.IdRefaccion = cvce.Id
                        where tcsr.IdServicioOrigen = "' . $servicio . '" 
                        group by Servicio';

        return $this->DBS->consultaGeneralSeguimiento($sentencia);
    }

    public function consultaCorrectivoTI() {
        $usuario = $this->Usuario->getDatosUsuario();
        $key = $this->InformacionServicios->getApiKeyByUser($usuario['Id']);
        $listaTI = $this->ServiceDesk->consultarValidadoresTI($key);

        return $listaTI;
    }

    public function guardarDatosGeneralesCenso(array $datos) {
        $datosRecoleccion = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_censos_generales WHERE IdServicio = ' . $datos['servicio']);

        if (empty($datosRecoleccion)) {
            $this->sobreEscribirServicioCenso($datos['servicio'], $datos['sucursal']);
            $consulta = $this->DBS->insertarSeguimiento(
                    't_censos_generales', array(
                'IdServicio' => $datos['servicio'],
                'IdSucursal' => $datos['sucursal'],
                'Descripcion' => $datos['descripcion'],
                    )
            );
            if (!empty($consulta)) {
                $this->DBS->actualizarSeguimiento(
                        't_servicios_ticket', array(
                    'IdSucursal' => $datos['sucursal'],
                        ), array('Id' => $datos['servicio'])
                );
                return $this->consultaTodosCensoServicio($datos['servicio']);
            } else {
                return FALSE;
            }
        } else {
            $this->sobreEscribirServicioCenso($datos['servicio'], $datos['sucursal']);
            $consulta = $this->DBS->actualizarSeguimiento(
                    't_censos_generales', array(
                'IdServicio' => $datos['servicio'],
                'IdSucursal' => $datos['sucursal'],
                'Descripcion' => $datos['descripcion'],
                    ), array('IdServicio' => $datos['servicio'])
            );
            if (!empty($consulta)) {
                $this->DBS->actualizarSeguimiento(
                        't_servicios_ticket', array(
                    'IdSucursal' => $datos['sucursal'],
                        ), array('Id' => $datos['servicio'])
                );
                return $this->consultaTodosCensoServicio($datos['servicio']);
            } else {
                return FALSE;
            }
        }
    }

    public function guardarDatosCenso(array $datos) {
        $censosAgregados = TRUE;

        foreach ($datos['censos'] as $value) {
            $verificarExistenteCensos = $this->DBS->consultaGeneralSeguimiento('SELECT IdServicio FROM t_censos WHERE IdServicio = "' . $datos['servicio'] . '" AND IdArea = "' . $value[5] . '" AND IdModelo = "' . $value[6] . '" AND Serie = "' . $value[3] . '" AND Extra = "' . $value[4] . '"');
            if (!empty($verificarExistenteCensos)) {
                $this->DBS->eliminarDatos('t_censos', array('IdServicio' => $datos['servicio'], 'IdArea' => $value[5], 'IdModelo' => $value[6], 'Punto' => $value[1], 'Serie' => $value[3], 'Extra' => $value[4]));
            }
            $consulta = $this->DBS->insertarSeguimiento('t_censos', array(
                'IdServicio' => $datos['servicio'],
                'IdArea' => $value[5],
                'IdModelo' => $value[6],
                'Punto' => $value[1],
                'Serie' => $value[3],
                'Extra' => $value[4]
            ));
        }

        if ($censosAgregados) {
            $censosAgregados = $this->consultaTodosCensoServicio($datos['servicio']);
        } else {
            $censosAgregados = FALSE;
        }
        return $censosAgregados;
    }

    public function guardarDatosMantenimiento(array $datos) {
        $validarExistenteCensoEnSucusal = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                                        tcg.Id 
                                                                                    FROM t_censos_generales tcg
                                                                                    INNER JOIN t_servicios_ticket tst
                                                                                        ON tst.Id = tcg.IdServicio
                                                                                    WHERE tcg.IdSucursal = "' . $datos['sucursal'] . '" 
                                                                                    AND tst.IdEstatus = 4');
        if (!empty($validarExistenteCensoEnSucusal)) {
            $consulta = $this->DBS->insertarSeguimiento(
                    't_mantenimientos_generales', array(
                'IdServicio' => $datos['servicio'],
                'IdSucursal' => $datos['sucursal'],
                    )
            );
            if (!empty($consulta)) {
                $this->DBS->actualizarSeguimiento(
                        't_servicios_ticket', array(
                    'IdSucursal' => $datos['sucursal'],
                        ), array('Id' => $datos['servicio'])
                );
                return $this->consultaPuntosCensadosMantenimiento($datos['sucursal'], $datos['servicio']);
            } else {
                return FALSE;
            }
        } else {
            return 'noExisteCensoSucursal';
        }
    }

    public function guardarEquiposFaltantes(array $datos) {
        $equiposFaltantesAgregados = true;

        foreach ($datos['equipoFaltante'] as $value) {
            $verificarExistenteCensos = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_mantenimientos_equipo_faltante WHERE IdServicio = "' . $datos['servicio'] . '" AND IdArea = "' . $datos['area'] . '" AND Punto = "' . $datos['punto'] . '" AND IdModelo = "' . $value[3] . '" AND TipoItem = "' . $value[4] . '"');
            if (!empty($verificarExistenteCensos)) {
                $this->DBS->eliminarDatos('t_mantenimientos_equipo_faltante', array('Id' => $verificarExistenteCensos[0]['Id']));
            }
            $this->DBS->insertarSeguimiento('t_mantenimientos_equipo_faltante', array(
                'IdServicio' => $datos['servicio'],
                'IdArea' => $datos['area'],
                'Punto' => $datos['punto'],
                'IdModelo' => $value[3],
                'TipoItem' => $value[4],
                'Cantidad' => $value[2]
            ));
        }

        if ($equiposFaltantesAgregados) {
            $equiposFaltantesAgregados = $this->consultaEquiposFaltantes($datos['servicio'], $datos['area'], $datos['punto']);
        }
        return $equiposFaltantesAgregados;
    }

    public function guardarProblemasAdicionales(array $datos) {
        $archivos = null;
        $CI = parent::getCI();

        $numeroProblemaAdicional = $this->DBS->insertarSeguimiento('t_mantenimientos_problemas_adicionales', array(
            'IdServicio' => $datos['servicio'],
            'IdArea' => $datos['area'],
            'Punto' => $datos['punto'],
            'Descripcion' => $datos['descripcion']
        ));
        $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/EvidenciaProblemasAdicionales/';
        $archivos = setMultiplesArchivos($CI, 'evidenciasProblemasAdicionales', $carpeta);

        if ($archivos) {
            $archivos = implode(',', $archivos);
            $this->DBS->actualizarSeguimiento(
                    't_mantenimientos_problemas_adicionales', array(
                'Evidencias' => $archivos
                    ), array('Id' => $numeroProblemaAdicional)
            );
            return $this->consultaProblemasAdicionales($datos['servicio']);
        } else {
            return FALSE;
        }
    }

    public function guardarAntesYDespues(array $datos) {
        $verificarPuntoCenso = $this->consultaAntesYDespues($datos['servicio'], $datos['area'], $datos['punto']);

        if (!empty($verificarPuntoCenso)) {
            $this->DBS->actualizarSeguimiento('t_mantenimientos_antes_despues', array(
                'Observaciones' . $datos['operacion'] => $datos['descripcion']
                    ), array('IdServicio' => $datos['servicio'], 'IdArea' => $datos['area'], 'Punto' => $datos['punto']));
            return $this->consultaPuntosCensadosMantenimiento($datos['sucursal'], $datos['servicio']);
        } else {
            return 'faltaEvidencia';
        }
    }

    public function guardarEvidenciasAntesYDespues(array $datos) {
        $archivos = null;
        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia' . $datos['operacion'] . '/';
        $archivos = setMultiplesArchivos($CI, 'evidencias' . $datos['operacion'], $carpeta);

        if ($archivos) {
            $archivos = implode(',', $archivos);
            $verificarPuntoCenso = $this->consultaAntesYDespues($datos['servicio'], $datos['area'], $datos['punto']);
            if (empty($verificarPuntoCenso)) {
                $this->DBS->insertarSeguimiento('t_mantenimientos_antes_despues', array(
                    'IdServicio' => $datos['servicio'],
                    'IdArea' => $datos['area'],
                    'Punto' => $datos['punto'],
                    'Evidencias' . $datos['operacion'] => $archivos
                ));
            } else {
                $evidenciasAnteriores = $verificarPuntoCenso[0]['Evidencias' . $datos['operacion']];
                if ($evidenciasAnteriores !== NULL) {
                    if ($evidenciasAnteriores !== '') {
                        $evidenciasAnteriores = $evidenciasAnteriores . ',';
                    }
                } else {
                    $evidenciasAnteriores = '';
                }
                $this->DBS->actualizarSeguimiento(
                        't_mantenimientos_antes_despues', array(
                    'Evidencias' . $datos['operacion'] => $evidenciasAnteriores . $archivos
                        ), array('IdServicio' => $datos['servicio'], 'IdArea' => $datos['area'], 'Punto' => $datos['punto'])
                );
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function guardarProblemasEquipo(array $datos) {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $atiende = $this->DBST->getDatosAtiende($usuario['Id']);
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $arrayDatos = array(
            'servicio' => $datos['servicio'],
            'area' => $datos['area'],
            'punto' => $datos['punto'],
            'modelo' => $datos['modelo'],
            'serie' => $datos['serie']
        );
        $verificarProblemasEquiposEvidencias = $this->consultaExistenteProblemasEquipos($arrayDatos);
        $verificarTodosProblemasEquiposObservaciones = $this->consultaExistenteProblemasEquipos($arrayDatos, TRUE);

        if ($verificarTodosProblemasEquiposObservaciones[0]['Observaciones'] === '' || empty($verificarTodosProblemasEquiposObservaciones)) {
            if (!empty($verificarProblemasEquiposEvidencias)) {
                $datosSucursal = $this->DBS->consultaGeneralSeguimiento('SELECT IdSucursal,
                                                                            sucursal(IdSucursal) AS NombreSucursal
                                                                        FROM t_mantenimientos_generales 
                                                                        WHERE IdServicio = "' . $datos['servicio'] . '"');
                $idResponsable = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                            cvrc.IdResponsableInterno
                                                                        FROM cat_v3_sucursales cvs
                                                                        INNER JOIN cat_v3_regiones_cliente cvrc
                                                                        ON cvs.IdRegionCliente = cvrc.Id
                                                                        WHERE cvs.Id = "' . $datosSucursal[0]['IdSucursal'] . '"');
                $dataNuevoServicio = array(
                    'Ticket' => $datos['ticket'],
                    'IdSolicitud' => $datos['idSolicitud'],
                    'IdTipoServicio' => '20',
                    'IdSucursal' => $datosSucursal[0]['IdSucursal'],
                    'IdEstatus' => '1',
                    'Solicita' => $usuario['Id'],
                    'Atiende' => $idResponsable[0]['IdResponsableInterno'],
                    'FechaCreacion' => $fecha,
                    'Descripcion' => $datos['descripcion']
                );
                $numeroServicio = $this->DBST->setNuevoServicio($dataNuevoServicio);

                $this->DBS->actualizarSeguimiento('t_mantenimientos_problemas_equipo', array(
                    'Observaciones' => $datos['descripcion'], 'IdNuevoServicio' => $numeroServicio
                        ), array('IdServicio' => $datos['servicio'], 'IdArea' => $datos['area'], 'Punto' => $datos['punto'], 'IdModelo' => $datos['modelo'], 'serie' => $datos['serie']));

                $this->DBS->insertarSeguimiento('t_servicios_relaciones', array(
                    'IdServicioOrigen' => $datos['servicio'],
                    'IdServicioNuevo' => $numeroServicio
                ));

                $data['departamento'] = $atiende['IdDepartamento'];
                $data['remitente'] = $usuario['Id'];
                $data['tipo'] = '7';
                $data['descripcion'] = 'La genero el servicio <b class="f-s-16">' . $numeroServicio . '</b> del ticket ' . $datos['ticket'];

                $this->Notificacion->setNuevaNotificacion(
                        $data, 'Nuevo servicio', 'El usuario <b>' . $usuario['Nombre'] . '</b> a generado el servicio "<strong>' . $numeroServicio . '</strong>" del ticket ' . $datos['ticket'] . ' en la Sucursal ' . $datosSucursal[0]['NombreSucursal'] . '.<br><br>
                        La fecha de creacion fue el ' . $fecha . '. <br><br> Por lo que se solicita que se atienda lo mas pronto posible el servicio.', $atiende
                );

                return $this->consultaProblemasEquiposServicio($arrayDatos);
            } else {
                return 'faltaEvidencia';
            }
        } else {
            return 'existeRegistro';
        }
    }

    public function guardarEvidenciasProblemasEquipo(array $datos) {
        $archivos = null;
        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/EvidenciaProblemasEquipo/';
        $archivos = setMultiplesArchivos($CI, 'evidenciasFallasEquipo', $carpeta);
        $arrayDatos = array(
            'servicio' => $datos['servicio'],
            'area' => $datos['area'],
            'punto' => $datos['punto'],
            'modelo' => $datos['modelo'],
            'serie' => $datos['serie']
        );

        if ($archivos) {
            $archivos = implode(',', $archivos);
            $verificarProblemasEquipos = $this->consultaExistenteProblemasEquipos($arrayDatos);
            if (empty($verificarProblemasEquipos)) {
                $this->DBS->insertarSeguimiento('t_mantenimientos_problemas_equipo', array(
                    'IdServicio' => $datos['servicio'],
                    'IdArea' => $datos['area'],
                    'Punto' => $datos['punto'],
                    'IdModelo' => $datos['modelo'],
                    'Serie' => $datos['serie'],
                    'Evidencias' => $archivos
                ));
                return TRUE;
            } else {
                return 'existeRegistro';
            }
        } else {
            return 'sinArchivo';
        }
    }

    public function guardarDatosGeneralesCorrectivo(array $datos) {
        $datosRecoleccion = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_correctivos_generales WHERE IdServicio = ' . $datos['servicio']);

        if (isset($datos['multimedia'])) {
            $multimedia = $datos['multimedia'];
        } else {
            $multimedia = '0';
        }

        $arrayCorrectivo = array(
            'IdServicio' => $datos['servicio'],
            'IdArea' => $datos['area'],
            'Punto' => $datos['punto'],
            'IdModelo' => $datos['equipo'],
            'Serie' => $datos['serie'],
            'Terminal' => $datos['numTerminal'],
            'Multimedia' => $multimedia
        );

        if (empty($datosRecoleccion)) {
            $consulta = $this->DBS->insertarSeguimiento('t_correctivos_generales', $arrayCorrectivo);
            if (!empty($consulta)) {
                $this->actualizarServicioSucursal($datos['sucursal'], $datos['servicio']);
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $consulta = $this->DBS->actualizarSeguimiento('t_correctivos_generales', $arrayCorrectivo, array('IdServicio' => $datos['servicio']));
            if (!empty($consulta)) {
                $this->actualizarServicioSucursal($datos['sucursal'], $datos['servicio']);
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function guardarDiagnosticoEquipo(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = null;
        $CI = parent::getCI();
        $evidenciasAnteriores = '';
        $folio = $this->DBST->consultaFolio($datos['servicio']);
        $key = $this->ServiceDesk->validarAPIKey($usuario['SDKey']);
        $verificarCorrectivosGenerales = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_correctivos_generales WHERE IdServicio = "' . $datos['servicio'] . '"');

        if (!empty($verificarCorrectivosGenerales)) {
            if ($datos['evidencias'] !== NULL) {
                if ($datos['evidencias'] !== '') {
                    if ($datos['tipoDiagnosticoAnterior'] === $datos['tipoDiagnostico']) {
                        $evidenciasAnteriores = $datos['evidencias'] . ',';
                    }
                }
            }

            $this->DBS->actualizarSeguimiento(
                    't_correctivos_generales', array(
                'FallaReportada' => $datos['fallaReportada']
                    ), array('IdServicio' => $datos['servicio'])
            );

            switch ($datos['tipoDiagnostico']) {
                case '1':
                    $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia_Correctivo_ReporteEnFalso/';
                    $archivos = setMultiplesArchivos($CI, 'evidenciasReporteFalsoCorrectivo', $carpeta);

                    $idCorrectivoDiagnostico = $this->DBS->insertarSeguimiento('t_correctivos_diagnostico', array(
                        'IdServicio' => $datos['servicio'],
                        'IdTipoDiagnostico' => $datos['tipoDiagnostico'],
                        'IdUsuario' => $usuario['Id'],
                        'FechaCaptura' => $fecha
                    ));

                    if (!empty($idCorrectivoDiagnostico)) {
                        if ($archivos) {
                            $archivos = implode(',', $archivos);
                            $evidencias = $evidenciasAnteriores . $archivos;
                        } else {
                            $evidencias = $datos['evidencias'];
                        }

                        $this->DBS->actualizarSeguimiento(
                                't_correctivos_diagnostico', array(
                            'Evidencias' => $evidencias
                                ), array('Id' => $idCorrectivoDiagnostico)
                        );

                        $this->ServiceDesk->cambiarReporteFalsoServiceDesk($key, $folio, 'SI');

                        return $idCorrectivoDiagnostico;
                    } else {
                        return FALSE;
                    }
                    break;
                case '2':
                    $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia_Correctivo_Impericia/';
                    $archivos = setMultiplesArchivos($CI, 'evidenciasImpericiaCorrectivo', $carpeta);

                    $idCorrectivoDiagnostico = $this->DBS->insertarSeguimiento('t_correctivos_diagnostico', array(
                        'IdServicio' => $datos['servicio'],
                        'IdTipoDiagnostico' => $datos['tipoDiagnostico'],
                        'IdUsuario' => $usuario['Id'],
                        'IdTipoFalla' => $datos['tipoFalla'],
                        'IdFalla' => $datos['falla'],
                        'FechaCaptura' => $fecha,
                        'Observaciones' => $datos['observaciones']
                    ));
                    if (!empty($idCorrectivoDiagnostico)) {
                        if ($archivos) {
                            $archivos = implode(',', $archivos);
                            $this->DBS->actualizarSeguimiento(
                                    't_correctivos_diagnostico', array(
                                'Evidencias' => $evidenciasAnteriores . $archivos
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                        } else {
                            $this->DBS->actualizarSeguimiento(
                                    't_correctivos_diagnostico', array(
                                'Evidencias' => $datos['evidencias']
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                        }
                        $this->DBS->actualizarSeguimiento('t_servicios_ticket', array(
                            'IdEstatus' => '3'
                                ), array('Id' => $datos['servicio']));
                        //                        $this->cambiarEstatusServiceDesk($datos['servicio'], 'Problema');
                        $this->ServiceDesk->cambiarReporteFalsoServiceDesk($key, $folio, 'NO');

                        return $idCorrectivoDiagnostico;
                    } else {
                        return FALSE;
                    }
                    break;
                case '3':
                    $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia_Correctivo_Falla_Equipo/';
                    $archivos = setMultiplesArchivos($CI, 'evidenciasFallaEquipoCorrectivo', $carpeta);
                    $idCorrectivoDiagnostico = $this->DBS->insertarSeguimiento('t_correctivos_diagnostico', array(
                        'IdServicio' => $datos['servicio'],
                        'IdTipoDiagnostico' => $datos['tipoDiagnostico'],
                        'IdUsuario' => $usuario['Id'],
                        'IdTipoFalla' => $datos['tipoFalla'],
                        'IdFalla' => $datos['falla'],
                        'FechaCaptura' => $fecha,
                        'Observaciones' => $datos['observaciones']
                    ));
                    if (!empty($idCorrectivoDiagnostico)) {
                        $this->ServiceDesk->cambiarReporteFalsoServiceDesk($key, $folio, 'NO');
                        $this->DBS->actualizarSeguimiento('t_servicios_ticket', array(
                            'IdEstatus' => '2'
                                ), array('Id' => $datos['servicio']));
                        $this->cambiarEstatusServiceDesk($datos['servicio'], 'En Atención');
                        if ($archivos) {
                            $archivos = implode(',', $archivos);
                            $this->DBS->actualizarSeguimiento(
                                    't_correctivos_diagnostico', array(
                                'Evidencias' => $evidenciasAnteriores . $archivos
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                            return $idCorrectivoDiagnostico;
                        } else {
                            $this->DBS->actualizarSeguimiento(
                                    't_correctivos_diagnostico', array(
                                'Evidencias' => $datos['evidencias']
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                        }
                    } else {
                        return FALSE;
                    }
                    break;
                case '4':
                    $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia_Correctivo_Falla_Componente/';
                    $archivos = setMultiplesArchivos($CI, 'evidenciasFallaComponenteCorrectivo', $carpeta);
                    $idCorrectivoDiagnostico = $this->DBS->insertarSeguimiento('t_correctivos_diagnostico', array(
                        'IdServicio' => $datos['servicio'],
                        'IdTipoDiagnostico' => $datos['tipoDiagnostico'],
                        'IdUsuario' => $usuario['Id'],
                        'IdComponente' => $datos['componente'],
                        'IdTipoFalla' => $datos['tipoFalla'],
                        'IdFalla' => $datos['falla'],
                        'FechaCaptura' => $fecha,
                        'Observaciones' => $datos['observaciones']
                    ));

                    if (!empty($idCorrectivoDiagnostico)) {
                        $this->DBS->actualizarSeguimiento('t_servicios_ticket', array(
                            'IdEstatus' => '2'
                                ), array('Id' => $datos['servicio']));
//                        $this->cambiarEstatusServiceDesk($datos['servicio'], 'En Atención');
//                        $this->ServiceDesk->cambiarReporteFalsoServiceDesk($key, $folio, 'NO');
//                        $this->cambiarEstatusServiceDesk($datos['servicio'], 'En Atención');
//                        $this->ServiceDesk->cambiarReporteFalsoServiceDesk($key, $folio, 'NO');
