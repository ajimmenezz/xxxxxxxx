<?php

namespace Librerias\Poliza;

use Controladores\Controller_Datos_Usuario as General;

class Seguimientos extends General {

    private $DBS;
    private $Notificacion;
    private $DBST;
    private $DBP;
    private $DBB;
    private $Catalogo;
    private $Correo;
    private $Phantom;
    private $ServiceDesk;
    private $InformacionServicios;
    private $MSP;
    private $usuario;
    private $MSicsa;
    private $DBCensos;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Loguistica_Seguimiento::factory();
        $this->DBB = \Modelos\Modelo_Busqueda::factory();
        $this->Notificacion = \Librerias\Generales\Notificacion::factory();
        $this->DBST = \Modelos\Modelo_ServicioTicket::factory();
        $this->DBP = \Modelos\Modelo_Poliza::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->Phantom = \Librerias\Generales\Phantom::factory();
        $this->ServiceDesk = \Librerias\WebServices\ServiceDesk::factory();
        $this->InformacionServicios = \Librerias\WebServices\InformacionServicios::factory();
        $this->MSP = \Modelos\Modelo_SegundoPlano::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
        $this->MSicsa = \Modelos\Modelo_Sicsa::factory();
        $this->DBCensos = \Modelos\Modelo_Censos::factory();

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
        $consulta = $this->DBS->consultaGeneralSeguimiento('select 
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
        $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT 
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
                                                            WHERE cvme.Id = "' . $datos['equipo'] . '"');
        if ($idLinea[0]['Linea'] === '1') {
            $lineas = '1,10';
        } else {
            $lineas = $idLinea[0]['Linea'];
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
                        AND IdEstatus IN(10,5,2,1)';

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
        $key = $this->MSP->getApiKeyByUser($usuario['Id']);
        $listaTI = $this->ServiceDesk->consultarDepartamentoTI($key);

        return $listaTI;
    }

    public function guardarDatosGeneralesCenso(array $datos) {
        $datosRecoleccion = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_censos_generales WHERE IdServicio = ' . $datos['servicio']);

        if (empty($datosRecoleccion)) {
            $this->sobreEscribirServicioCenso($datos['servicio'], $datos['sucursal']);
            $consulta = $this->DBS->insertarSeguimiento('t_censos_generales', array(
                'IdServicio' => $datos['servicio'],
                'IdSucursal' => $datos['sucursal'],
                'Descripcion' => $datos['descripcion'],
                    )
            );
            if (!empty($consulta)) {
                $this->DBS->actualizarSeguimiento('t_servicios_ticket', array(
                    'IdSucursal' => $datos['sucursal'],
                        ), array('Id' => $datos['servicio'])
                );
                return $this->consultaTodosCensoServicio($datos['servicio']);
            } else {
                return FALSE;
            }
        } else {
            $this->sobreEscribirServicioCenso($datos['servicio'], $datos['sucursal']);
            $consulta = $this->DBS->actualizarSeguimiento('t_censos_generales', array(
                'IdServicio' => $datos['servicio'],
                'IdSucursal' => $datos['sucursal'],
                'Descripcion' => $datos['descripcion'],
                    ), array('IdServicio' => $datos['servicio'])
            );
            if (!empty($consulta)) {
                $this->DBS->actualizarSeguimiento('t_servicios_ticket', array(
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
            $consulta = $this->DBS->insertarSeguimiento('t_mantenimientos_generales', array(
                'IdServicio' => $datos['servicio'],
                'IdSucursal' => $datos['sucursal'],
                    )
            );
            if (!empty($consulta)) {
                $this->DBS->actualizarSeguimiento('t_servicios_ticket', array(
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
            $this->DBS->actualizarSeguimiento('t_mantenimientos_problemas_adicionales', array(
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
                $this->DBS->actualizarSeguimiento('t_mantenimientos_antes_despues', array(
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
        $arrayDatos = array('servicio' => $datos['servicio'],
            'area' => $datos['area'],
            'punto' => $datos['punto'],
            'modelo' => $datos['modelo'],
            'serie' => $datos['serie']);
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

                $this->DBS->insertarSeguimiento('t_servicios_relaciones', array('IdServicioOrigen' => $datos['servicio'],
                    'IdServicioNuevo' => $numeroServicio));

                $data['departamento'] = $atiende['IdDepartamento'];
                $data['remitente'] = $usuario['Id'];
                $data['tipo'] = '7';
                $data['descripcion'] = 'La genero el servicio <b class="f-s-16">' . $numeroServicio . '</b> del ticket ' . $datos['ticket'];

                $this->Notificacion->setNuevaNotificacion(
                        $data, 'Nuevo servicio', 'El usuario <b>' . $usuario['Nombre'] . '</b> a generado el servicio "<strong>' . $numeroServicio . '</strong>" del ticket ' . $datos['ticket'] . ' en la Sucursal ' . $datosSucursal[0]['NombreSucursal'] . '.<br><br>
                        La fecha de creacion fue el ' . $fecha . '. <br><br> Por lo que se solicita que se atienda lo mas pronto posible el servicio.', $atiende);

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
        $arrayDatos = array('servicio' => $datos['servicio'],
            'area' => $datos['area'],
            'punto' => $datos['punto'],
            'modelo' => $datos['modelo'],
            'serie' => $datos['serie']);

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

        $verificarCorrectivosGenerales = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_correctivos_generales WHERE IdServicio = "' . $datos['servicio'] . '"');

        if (!empty($verificarCorrectivosGenerales)) {
            if ($datos['evidencias'] !== NULL) {
                if ($datos['evidencias'] !== '') {
                    if ($datos['tipoDiagnosticoAnterior'] === $datos['tipoDiagnostico']) {
                        $evidenciasAnteriores = $datos['evidencias'] . ',';
                    }
                }
            }
            switch ($datos['tipoDiagnostico']) {
                case '1':
                    $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia_Correctivo_ReporteEnFalso/';
                    $archivos = setMultiplesArchivos($CI, 'evidenciasReporteFalsoCorrectivo', $carpeta);

                    $idCorrectivoDiagnostico = $this->DBS->insertarSeguimiento('t_correctivos_diagnostico', array(
                        'IdServicio' => $datos['servicio'],
                        'IdTipoDiagnostico' => $datos['tipoDiagnostico'],
                        'IdUsuario' => $usuario['Id'],
                        'FechaCaptura' => $fecha,
                        'Observaciones' => $datos['observaciones']
                    ));
                    if (!empty($idCorrectivoDiagnostico)) {
                        if ($archivos) {
                            $archivos = implode(',', $archivos);
                            $this->DBS->actualizarSeguimiento('t_correctivos_diagnostico', array(
                                'Evidencias' => $evidenciasAnteriores . $archivos
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                            return $idCorrectivoDiagnostico;
                        } else {
                            $this->DBS->actualizarSeguimiento('t_correctivos_diagnostico', array(
                                'Evidencias' => $datos['evidencias']
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                        }
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
                            $this->DBS->actualizarSeguimiento('t_correctivos_diagnostico', array(
                                'Evidencias' => $evidenciasAnteriores . $archivos
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                        } else {
                            $this->DBS->actualizarSeguimiento('t_correctivos_diagnostico', array(
                                'Evidencias' => $datos['evidencias']
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                        }
                        $this->DBS->actualizarSeguimiento('t_servicios_ticket', array(
                            'IdEstatus' => '3'
                                ), array('Id' => $datos['servicio']));
                        $this->cambiarEstatusServiceDesk($datos['servicio'], 'Problema');




                        //Incluir aqui la inserción a SICSA 

                        $cotizacionAnterior = $this->DBS->consulta("select "
                                . "count(*) as Total "
                                . "from t_servicios_ticket tst "
                                . "where tst.IdServicioOrigen = '" . $datos['servicio'] . "' "
                                . "and tst.IdTipoServicio = 41");

                        if ($cotizacionAnterior[0]['Total'] <= 0) {

                            $detallesServicio = $this->DBS->consulta("SELECT
                                                                ClaveSAE,
                                                                (select Nombre from cat_v3_equipos_sae where Clave = cme.ClaveSAE) as Articulo,
                                                                (select Equipo from v_equipos where Id = (select 
                                                                            IdModelo 
                                                                            from t_correctivos_generales 
                                                                            where IdServicio = '" . $datos['servicio'] . "')) as Equipo
                                                                from cat_v3_modelos_equipo cme
                                                                where Id = (select 
                                                                            IdModelo 
                                                                            from t_correctivos_generales 
                                                                            where IdServicio = '" . $datos['servicio'] . "')");

                            $otherData = $this->DBS->consulta("SELECT                                                         
                                                        tst.Ticket,
                                                        tst.IdSolicitud,
                                                        tst.IdSucursal,
                                                        sucursalByServicio('" . $datos['servicio'] . "') as Sucursal,
                                                        folioByServicio('" . $datos['servicio'] . "') as Folio,
                                                        (select concat((select Nombre from cat_v3_clasificaciones_falla where Id = IdClasificacion),' - ', Nombre) from cat_v3_tipos_falla where Id = tcd.IdTipoFalla) as TipoFalla,
                                                        (select Nombre from cat_v3_fallas_equipo where Id = tcd.IdFalla) as Falla,
                                                        tcd.Observaciones
                                                        from t_correctivos_diagnostico tcd 
                                                        inner join t_servicios_ticket tst on tcd.IdServicio = tst.Id
                                                        where IdServicio = '" . $datos['servicio'] . "'                                                        
                                                        order by tcd.Id desc limit 1");

                            $cve_art = ($detallesServicio[0]['ClaveSAE'] != '') ? $detallesServicio[0]['ClaveSAE'] : 'PIECE';
                            $articulo = ($detallesServicio[0]['ClaveSAE'] != '') ? $detallesServicio[0]['Articulo'] : $detallesServicio[0]['Equipo'];


                            $arrayDatosCotizacion = [
                                'SD' => $otherData[0]['Folio'],
                                'Complejo' => $otherData[0]['Sucursal'],
                                'Observaciones' => '',
                                'CVE' => $cve_art,
                                'Articulo' => $articulo,
                                'Categoria' => $otherData[0]['TipoFalla'],
                                'Falla' => $otherData[0]['Falla'],
                                'Link' => 'http://siccob.solutions/Detalles/Servicio/' . $datos['servicio']
                            ];

                            $insertSicsa = $this->MSicsa->insertaCotizacion($arrayDatosCotizacion);

                            if ($insertSicsa['code'] == 200) {
                                $arrayInsertCotizacion = [
                                    'Ticket' => $otherData[0]['Ticket'],
                                    'IdSolicitud' => $otherData[0]['IdSolicitud'],
                                    'IdTipoServicio' => 41,
                                    'IdSucursal' => $otherData[0]['IdSucursal'],
                                    'IdEstatus' => 2,
                                    'Solicita' => $this->usuario['Id'],
                                    'Atiende' => 47,
                                    'FechaCreacion' => $fecha,
                                    'FechaInicio' => $fecha,
                                    'Descripcion' => 'Cotización de ' . $arrayDatosCotizacion['Observaciones'],
                                    'IdServicioOrigen' => $datos['servicio']
                                ];

                                $this->DBS->insertar('t_servicios_ticket', $arrayInsertCotizacion);
                            }
                        }

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
                        $this->DBS->actualizarSeguimiento('t_servicios_ticket', array(
                            'IdEstatus' => '2'
                                ), array('Id' => $datos['servicio']));
                        $this->cambiarEstatusServiceDesk($datos['servicio'], 'En Atención');
                        if ($archivos) {
                            $archivos = implode(',', $archivos);
                            $this->DBS->actualizarSeguimiento('t_correctivos_diagnostico', array(
                                'Evidencias' => $evidenciasAnteriores . $archivos
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                            return $idCorrectivoDiagnostico;
                        } else {
                            $this->DBS->actualizarSeguimiento('t_correctivos_diagnostico', array(
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
                        $this->cambiarEstatusServiceDesk($datos['servicio'], 'En Atención');
                        if ($archivos) {
                            $archivos = implode(',', $archivos);
                            $this->DBS->actualizarSeguimiento('t_correctivos_diagnostico', array(
                                'Evidencias' => $evidenciasAnteriores . $archivos
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                            return $idCorrectivoDiagnostico;
                        } else {
                            $this->DBS->actualizarSeguimiento('t_correctivos_diagnostico', array(
                                'Evidencias' => $datos['evidencias']
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                        }
                    } else {
                        return FALSE;
                    }
                    break;
                case '5':
                    $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia_Correctivo_ReporteMultimedia/';
                    $archivos = setMultiplesArchivos($CI, 'evidenciasReporteMultimediaCorrectivo', $carpeta);

                    $idCorrectivoDiagnostico = $this->DBS->insertarSeguimiento('t_correctivos_diagnostico', array(
                        'IdServicio' => $datos['servicio'],
                        'IdTipoDiagnostico' => $datos['tipoDiagnostico'],
                        'IdUsuario' => $usuario['Id'],
                        'FechaCaptura' => $fecha,
                        'Observaciones' => $datos['observaciones']
                    ));
                    if (!empty($idCorrectivoDiagnostico)) {
                        if ($archivos) {
                            $archivos = implode(',', $archivos);
                            $this->DBS->actualizarSeguimiento('t_correctivos_diagnostico', array(
                                'Evidencias' => $evidenciasAnteriores . $archivos
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                            return $idCorrectivoDiagnostico;
                        } else {
                            $this->DBS->actualizarSeguimiento('t_correctivos_diagnostico', array(
                                'Evidencias' => $datos['evidencias']
                                    ), array('Id' => $idCorrectivoDiagnostico)
                            );
                        }
                    } else {
                        return FALSE;
                    }
                    break;
            }
        } else {
            return 'faltaDatosGenerales';
        }
    }

    public function guardarRefaccionesSolicitud(array $datos) {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $dataExtra = array(
            'Servicio' => $datos['servicio'],
            'Usuario' => $usuario['Id'],
            'Tabla' => 't_correctivos_solicitudes_refaccion',
            'TipoProblema' => '1',
            'FechaCreacion' => $fecha,
            'TipoSolicitud' => $datos['tipoSolicitud']
        );

        $this->cambiarEstatus(array('servicio' => $datos['servicio'], 'estatus' => '3'));

        $linkPDF = $this->cargarPDF($datos);

        if ($datos['tipoSolicitud'] === 'almacen') {
            $dataNuevoServicio = array(
                'Ticket' => $datos['ticket'],
                'IdSolicitud' => $datos['solicitud'],
                'IdTipoServicio' => '21',
                'IdSucursal' => $datos['sucursal'],
                'IdEstatus' => '1',
                'Solicita' => $usuario['Id'],
                'Atiende' => $datos['atiende'],
                'FechaCreacion' => $fecha,
                'Descripcion' => 'Solicitud de Equipos o Refacciones del ticket ' . $datos['ticket']
            );
            $numeroServicio = $this->DBP->insertarServiciosTicketCorrectivosSolicitudes($dataNuevoServicio, $datos['refaccionesSolicitudes'], $dataExtra);

            if (is_int($numeroServicio)) {
                $data['departamento'] = '16';
                $data['remitente'] = $usuario['Id'];
                $data['tipo'] = '7';
                $data['descripcion'] = 'La genero el servicio <b class="f-s-16">' . $numeroServicio . '</b> del ticket ' . $datos['ticket'];

                $this->Notificacion->setNuevaNotificacion(
                        $data, 'Nuevo servicio', 'El usuario <b>' . $usuario['Nombre'] . '</b> a generado el servicio "<strong>' . $numeroServicio . '</strong>" del ticket ' . $datos['ticket'] . ' en la Sucursal ' . $datos['nombreSucursal'] . '.<br><br>
                        La fecha de creacion fue el ' . $fecha . '. <br><br> Por lo que se solicita que se atienda lo mas pronto posible el servicio.');

                $textoTecnico = '<p>Estimado(a) <strong>' . $usuario['Nombre'] . ',</strong> se le ha mandado el documento de la solicitud de refacción a Almacén que realizo.</p><br><a href="' . $linkPDF . '">Documento PDF</a>';
                $this->enviarCorreoConcluido(array($usuario['EmailCorporativo']), 'Solicitud de Refacción', $textoTecnico);

                return $this->consultaCorrectivosSolicitudRefaccion($datos['servicio']);
            } else {
                return FALSE;
            }
        } else {
            $numeroServicio = $this->DBP->insertarCorrectivosSolicitudes($datos['refaccionesSolicitudes'], $dataExtra);
            $verificarFolio = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                        (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio
                                                                    FROM t_servicios_ticket
                                                                    WHERE Id =  "' . $datos['servicio'] . '"');
            if (!empty($verificarFolio)) {
                if ($verificarFolio[0]['Folio'] !== NULL) {
                    if ($verificarFolio[0]['Folio'] !== '') {
                        if ($verificarFolio[0]['Folio'] !== '0') {

                            $arrayTI = $this->consultaCorrectivoTI();

                            foreach ($arrayTI as $key => $value) {
                                if (array_search($datos['atiende'], $value)) {
                                    $correoTI = $value['userEmail'];
                                }
                            }

                            $key = $this->MSP->getApiKeyByUser($usuario['Id']);
                            $this->ServiceDesk->reasignarFolioSD($verificarFolio[0]['Folio'], $datos['atiende'], $key);
                            $this->ServiceDesk->cambiarEstatusServiceDesk($key, 'Problema', $verificarFolio[0]['Folio']);
                            $textoTI = '<p>El técnico <strong>' . $usuario['Nombre'] . ' </strong> le ha reasignado la solicitud para solicitar una Refacción.<br>Número de Solicitud: <strong>' . $verificarFolio[0]['Folio'] . '</strong>.</p><br><a href="' . $linkPDF . '">Documento PDF</a><br><p>Favor de verificar en Service Desk</p>';
                            $this->enviarCorreoConcluido(array($correoTI), 'Reasignación de Solicitud', $textoTI);

                            return $this->consultaCorrectivosSolicitudRefaccion($datos['servicio']);
                        } else {
                            return 'faltaFolio';
                        }
                    } else {
                        return 'faltaFolio';
                    }
                } else {
                    return 'faltaFolio';
                }
            } else {
                return 'faltaFolio';
            }
        }
    }

    public function guardarEquiposSolicitud(array $datos) {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $dataExtra = array(
            'Servicio' => $datos['servicio'],
            'Usuario' => $usuario['Id'],
            'Tabla' => 't_correctivos_solicitudes_equipo',
            'TipoProblema' => '2',
            'FechaCreacion' => $fecha,
            'TipoSolicitud' => $datos['tipoSolicitud']
        );

        $this->cambiarEstatus(array('servicio' => $datos['servicio'], 'estatus' => '3'));

        $linkPDF = $this->cargarPDF($datos);

        if ($datos['tipoSolicitud'] === 'almacen') {
            $dataNuevoServicio = array(
                'Ticket' => $datos['ticket'],
                'IdSolicitud' => $datos['solicitud'],
                'IdTipoServicio' => '21',
                'IdSucursal' => $datos['sucursal'],
                'IdEstatus' => '1',
                'Solicita' => $usuario['Id'],
                'Atiende' => $datos['atiende'],
                'FechaCreacion' => $fecha,
                'Descripcion' => 'Solicitud de Equipos o Refacciones del ticket ' . $datos['ticket']
            );
            $numeroServicio = $this->DBP->insertarServiciosTicketCorrectivosSolicitudes($dataNuevoServicio, $datos['equiposSolicitudes'], $dataExtra);
            if (is_int($numeroServicio)) {
                $data['departamento'] = '16';
                $data['remitente'] = $usuario['Id'];
                $data['tipo'] = '7';
                $data['descripcion'] = 'La genero el servicio <b class="f-s-16">' . $numeroServicio . '</b> del ticket ' . $datos['ticket'];

                $this->Notificacion->setNuevaNotificacion(
                        $data, 'Nuevo servicio', 'El usuario <b>' . $usuario['Nombre'] . '</b> a generado el servicio "<strong>' . $numeroServicio . '</strong>" del ticket ' . $datos['ticket'] . ' en la Sucursal ' . $datos['nombreSucursal'] . '.<br><br>
                        La fecha de creacion fue el ' . $fecha . '. <br><br> Por lo que se solicita que se atienda lo mas pronto posible el servicio.');

                $textoTecnico = '<p>Estimado(a) <strong>' . $usuario['Nombre'] . ',</strong> se le ha mandado el documento de la solicitud de equipo a Almacén que realizo.</p><br><a href="' . $linkPDF . '">Documento PDF</a>';
                $this->enviarCorreoConcluido(array($usuario['EmailCorporativo']), 'Solicitud de Equipo', $textoTecnico);

                return $this->consultaCorrectivosSolicitudEquipo($datos['servicio']);
            } else {
                return FALSE;
            }
        } else {
            $numeroServicio = $this->DBP->insertarCorrectivosSolicitudes($datos['equiposSolicitudes'], $dataExtra);
            $verificarFolio = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                        (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio
                                                                    FROM t_servicios_ticket
                                                                    WHERE Id =  "' . $datos['servicio'] . '"');

            if (!empty($verificarFolio)) {
                if ($verificarFolio[0]['Folio'] !== NULL) {
                    if ($verificarFolio[0]['Folio'] !== '') {
                        if ($verificarFolio[0]['Folio'] !== '0') {
                            $arrayTI = $this->consultaCorrectivoTI();

                            foreach ($arrayTI as $key => $value) {
                                if (array_search($datos['atiende'], $value)) {
                                    $correoTI = $value['userEmail'];
                                }
                            }

                            $key = $this->MSP->getApiKeyByUser($usuario['Id']);
                            $this->ServiceDesk->reasignarFolioSD($verificarFolio[0]['Folio'], $datos['atiende'], $key);
                            $this->ServiceDesk->cambiarEstatusServiceDesk($key, 'Problema', $verificarFolio[0]['Folio']);
                            $textoTI = '<p>El técnico <strong>' . $usuario['Nombre'] . '</strong> le ha reasignado la solicitud para solicitar un Equipo.<br>Número de Solicitud: <strong>' . $verificarFolio[0]['Folio'] . '</strong>.</p><br><a href="' . $linkPDF . '">Documento PDF</a><br><p>Favor de verificar en Service Desk</p>';
                            $this->enviarCorreoConcluido(array($correoTI), 'Reasignación de Solicitud', $textoTI);

                            return $this->consultaCorrectivosSolicitudEquipo($datos['servicio']);
                        } else {
                            return 'faltaFolio';
                        }
                    } else {
                        return 'faltaFolio';
                    }
                } else {
                    return 'faltaFolio';
                }
            } else {
                return 'faltaFolio';
            }
        }
    }

    public function guardarInformacionEquipoRespaldo(array $datos) {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $CI = parent::getCI();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        if ($datos['operacion'] === '1') {
            $campoVariantes1 = $datos['equipo'];
            $campoVariantes2 = $datos['serie'];
            $esRespaldo = '1';
        } else {
            $campoVariantes1 = $datos['autoriza'];
            $campoVariantes2 = '';
            $esRespaldo = '0';
        }

        $dataInformacionGarantia = array(
            'IdServicio' => $datos['servicio'],
            'IdUsuario' => $usuario['Id'],
            'EsRespaldo' => $esRespaldo,
            'SolicitaEquipo' => '0',
            'campoVariantes1' => $campoVariantes1,
            'campoVariantes2' => $campoVariantes2,
            'Fecha' => $fecha
        );

        $dataCorrectivosProblemas = array(
            'IdServicio' => $datos['servicio'],
            'IdTipoProblema' => '3',
            'IdUsuario' => $usuario['Id'],
            'Fecha' => $fecha
        );

        $numeroInserccion = $this->DBP->insertarCorrectivoProblemasRespaldo($dataInformacionGarantia, $dataCorrectivosProblemas);

        $this->cambiarEstatus(array('servicio' => $datos['servicio'], 'estatus' => '3'));
        $this->cambiarEstatusServiceDesk($datos['servicio'], 'Problema');
        $this->InformacionServicios->guardarDatosServiceDesk($datos['servicio']);

        if ($datos['operacion'] === '2') {
            $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia_Correctivo_Autorizacion_Sin_Respaldo/';
            $archivos = setMultiplesArchivos($CI, 'evidenciasAutorizacion', $carpeta);

            if ($archivos) {
                $archivos = implode(',', $archivos);

                $consulta = $this->DBS->actualizarSeguimiento('t_correctivos_garantia_respaldo', array(
                    'Evidencia' => $archivos
                        ), array('Id' => $numeroInserccion)
                );
                if ($consulta) {
                    return $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_correctivos_garantia_respaldo WHERE IdServicio = "' . $datos['servicio'] . '" ORDER BY Id DESC LIMIT 1');
                } else {
                    return FALSE;
                }
            }
        } else {
            $this->enviarRetiroGarantiaRespaldo($datos, $numeroInserccion);
            return TRUE;
        }
    }

    public function guardarCrearSolicitarEquipoRespaldo(array $datos) {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $atiende = $this->DBST->getDatosAtiende($usuario['Id']);
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $dataInformacionGarantia = array(
            'IdServicio' => $datos['servicio'],
            'IdUsuario' => $usuario['Id'],
            'EsRespaldo' => '0',
            'SolicitaEquipo' => '1',
            'Fecha' => $fecha
        );
        $dataNuevoServicio = array(
            'Ticket' => $datos['ticket'],
            'IdSolicitud' => $datos['solicitud'],
            'IdTipoServicio' => '21',
            'IdSucursal' => $datos['sucursal'],
            'IdEstatus' => '1',
            'Solicita' => $usuario['Id'],
            'Atiende' => $datos['asignar'],
            'FechaCreacion' => $fecha,
            'Descripcion' => 'Solicitud de Equipos o Refacciones del ticket ' . $datos['ticket']
        );
        $dataCorrectivosSolicitudesEquipo = array(
            'IdServicioOrigen' => $datos['servicioAnterior'],
            'IdModelo' => $datos['equipo'],
            'Cantidad' => $datos['cantidad']
        );
        $dataCorrectivosProblemas = array(
            'IdServicio' => $datos['servicio'],
            'IdTipoProblema' => '3',
            'IdUsuario' => $usuario['Id'],
            'Fecha' => $fecha
        );

        $numeroServicio = $this->DBP->insertarServicioCorrectivoSolicitudGarantiaRespaldo($dataInformacionGarantia, $dataNuevoServicio, $dataCorrectivosSolicitudesEquipo, $dataCorrectivosProblemas);

        if (is_int($numeroServicio)) {
            $this->cambiarEstatus(array('servicio' => $datos['servicio'], 'estatus' => '3'));
            $verificarFolio = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                        (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio
                                                                    FROM t_servicios_ticket
                                                                    WHERE Id =  "' . $datos['servicio'] . '"');
            if (!empty($verificarFolio)) {
                if ($verificarFolio[0]['Folio'] !== NULL) {
                    if ($verificarFolio[0]['Folio'] !== '') {
                        if ($verificarFolio[0]['Folio'] !== '0') {
                            $key = $this->MSP->getApiKeyByUser($usuario['Id']);
                            $this->ServiceDesk->reasignarFolioSD($verificarFolio[0]['Folio'], '28801', $key);
                        }
                    }
                }
            }

            $data['departamento'] = $atiende['IdDepartamento'];
            $data['remitente'] = $usuario['Id'];
            $data['tipo'] = '7';
            $data['descripcion'] = 'La genero el servicio <b class="f-s-16">' . $numeroServicio . '</b> del ticket ' . $datos['ticket'];

            $this->Notificacion->setNuevaNotificacion(
                    $data, 'Nuevo servicio', 'El usuario <b>' . $usuario['Nombre'] . '</b> a generado el servicio "<strong>' . $numeroServicio . '</strong>" del ticket ' . $datos['ticket'] . ' en la Sucursal ' . $datos['sucursal'] . '.<br><br>
                        La fecha de creacion fue el ' . $fecha . '. <br><br> Por lo que se solicita que se atienda lo mas pronto posible el servicio.', $atiende);
            return $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                nombreUsuario(tst.Atiende) Atiende,
                                                                tst.FechaCreacion
                                                            FROM t_servicios_relaciones tsr
                                                            INNER JOIN t_servicios_ticket tst
                                                                ON tsr.IdServicioNuevo = tst.Id
                                                            WHERE tsr.IdServicioOrigen = "' . $datos['servicio'] . '" 
                                                            AND tst.IdTipoServicio = 21
                                                            ORDER BY tsr.Id DESC LIMIT 1');
        } else {
            return FALSE;
        }
    }

    public function guardarEnvioGarantia(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = null;
        $CI = parent::getCI();

        $idProblemaCorrectivo = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_correctivos_problemas WHERE IdServicio = "' . $datos['servicio'] . '" ORDER BY Id DESC LIMIT 1 ');
        $verificarCorrectivosEnviosEquipo = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_correctivos_envios_equipo WHERE IdProblemaCorrectivo = "' . $idProblemaCorrectivo[0]['Id'] . '"');
        $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia_Correctivo_Envio/';
        $archivos = setMultiplesArchivos($CI, 'evidenciasEnvioGarantia', $carpeta);

        if ($archivos) {
            $archivos = implode(',', $archivos);
            if (empty($verificarCorrectivosEnviosEquipo)) {
                $idCorrectivoEnvioEquipo = $this->DBS->insertarSeguimiento('t_correctivos_envios_equipo', array(
                    'IdProblemaCorrectivo' => $idProblemaCorrectivo[0]['Id'],
                    'IdUsuarioCapturaEnvio' => $usuario['Id'],
                    'FechaCapturaEnvio' => $fecha,
                    'IdTipoEnvio' => $datos['envia'],
                    'IdPaqueteriaConsolidado' => $datos['paqueteriaConsolidado'],
                    'Guia' => $datos['guia'],
                    'ComentariosEnvio' => $datos['comentarios']
                ));
                if (!empty($idCorrectivoEnvioEquipo)) {
                    $this->DBS->actualizarSeguimiento('t_correctivos_envios_equipo', array(
                        'EvidenciasEnvio' => $archivos
                            ), array('Id' => $idCorrectivoEnvioEquipo)
                    );
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                $correctivoEnvioEquipo = $this->DBS->actualizarSeguimiento('t_correctivos_envios_equipo', array(
                    'IdProblemaCorrectivo' => $idProblemaCorrectivo[0]['Id'],
                    'IdUsuarioCapturaEnvio' => $usuario['Id'],
                    'FechaCapturaEnvio' => $fecha,
                    'IdTipoEnvio' => $datos['envia'],
                    'IdPaqueteriaConsolidado' => $datos['paqueteriaConsolidado'],
                    'Guia' => $datos['guia'],
                    'ComentariosEnvio' => $datos['comentarios']
                        ), array('Id' => $verificarCorrectivosEnviosEquipo[0]['Id']));
                if ($correctivoEnvioEquipo) {
                    $this->DBS->actualizarSeguimiento('t_correctivos_envios_equipo', array(
                        'EvidenciasEnvio' => $archivos
                            ), array('Id' => $verificarCorrectivosEnviosEquipo[0]['Id'])
                    );
                    return TRUE;
                } else {
                    return 'Error al cargar la imagen contacto al encargado del Sistemas AdIST 3';
                }
            }
        }
    }

    public function guardarEntregaGarantia(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = null;
        $CI = parent::getCI();

        $idProblemaCorrectivo = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_correctivos_problemas WHERE IdServicio = "' . $datos['servicio'] . '" ORDER BY Id DESC LIMIT 1 ');
        $verificarCorrectivosEnviosEquipo = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_correctivos_envios_equipo WHERE IdProblemaCorrectivo = "' . $idProblemaCorrectivo[0]['Id'] . '"');
        $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia_Correctivo_Entrega/';
        $archivos = setMultiplesArchivos($CI, 'evidenciasEntregaEnvioGarantia', $carpeta);

        if ($archivos) {
            $archivos = implode(',', $archivos);
            if (!empty($verificarCorrectivosEnviosEquipo)) {
                $correctivoEnvioEquipo = $this->DBS->actualizarSeguimiento('t_correctivos_envios_equipo', array(
                    'IdUsuarioCapturaRecepcion' => $usuario['Id'],
                    'FechaCapturaRecepcion' => $fecha,
                    'Recibe' => $datos['recibe'],
                    'ComentariosEntrega' => $datos['comentarios']
                        ), array('Id' => $verificarCorrectivosEnviosEquipo[0]['Id']));
                if ($correctivoEnvioEquipo) {
                    $this->DBS->actualizarSeguimiento('t_correctivos_envios_equipo', array(
                        'EvidenciasEntrega' => $archivos
                            ), array('Id' => $verificarCorrectivosEnviosEquipo[0]['Id'])
                    );
                    return TRUE;
                } else {
                    return 'Error al cargar la imagen contacto al encargado del Sistemas AdIST 3';
                }
            } else {
                return 'Error al guardar. Favor de contactar al encargado del Sistema AdIST 3';
            }
        }
    }

    public function guardarReparacionSinEquipo(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = null;
        $CI = parent::getCI();
        $evidenciasAnteriores = '';
        $verificarCorrectivosGenerales = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_correctivos_generales WHERE IdServicio = "' . $datos['servicio'] . '"');
        $verificarCorrectivosDiagnostico = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_correctivos_diagnostico WHERE IdServicio = "' . $datos['servicio'] . '"');

        if (!empty($verificarCorrectivosGenerales)) {
            if (!empty($verificarCorrectivosDiagnostico)) {
                $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia_Correctivo_Reparacion_Sin_Equipo/';
                $archivos = setMultiplesArchivos($CI, 'evidenciasSolucionReparacionSinEquipo', $carpeta);

                $dataCorrectivosSoluciones = array(
                    'IdServicio' => $datos['servicio'],
                    'IdTipoSolucion' => '1',
                    'IdUsuario' => $usuario['Id'],
                    'Fecha' => $fecha,
                    'Observaciones' => $datos['observaciones']
                );
                if ($archivos) {
                    $IdCorrectivoSoluciones = $this->DBP->insertarServicioCorrectivoSolicitudesSolucionEquipo($dataCorrectivosSoluciones, $datos['solucion']);
                    $archivos = implode(',', $archivos);
                    if ($datos['evidencias'] !== NULL) {
                        if ($datos['evidencias'] !== '') {
                            if ($datos['idTipoSolucion'] === '1') {
                                $evidenciasAnteriores = $datos['evidencias'] . ',';
                            }
                        }
                    }

                    if (!empty($IdCorrectivoSoluciones)) {
                        $this->DBS->actualizarSeguimiento('t_correctivos_soluciones', array(
                            'Evidencias' => $evidenciasAnteriores . $archivos
                                ), array('Id' => $IdCorrectivoSoluciones)
                        );
                    } else {
                        return FALSE;
                    }
                } else {
                    $evidencias = $this->DBS->consultaGeneralSeguimiento('SELECT Evidencias FROM t_correctivos_soluciones WHERE IdServicio = ' . $datos['servicio'] . ' order by Fecha DESC LIMIT 1');
                    $IdCorrectivoSoluciones = $this->DBP->insertarServicioCorrectivoSolicitudesSolucionEquipo($dataCorrectivosSoluciones, $datos['solucion']);
                    if (!empty($IdCorrectivoSoluciones)) {
                        $this->DBS->actualizarSeguimiento('t_correctivos_soluciones', array(
                            'Evidencias' => $evidencias[0]['Evidencias']
                                ), array('Id' => $IdCorrectivoSoluciones)
                        );
                    } else {
                        return FALSE;
                    }
                }
            } else {
                return 'faltaDatosDiagnostico';
            }
        } else {
            return 'faltaDatosGenerales';
        }
    }

    public function guardarReparacionConRefaccion(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = null;
        $CI = parent::getCI();
        $evidenciasAnteriores = '';
        $verificarCorrectivosGenerales = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_correctivos_generales WHERE IdServicio = "' . $datos['servicio'] . '"');
        $verificarCorrectivosDiagnostico = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_correctivos_diagnostico WHERE IdServicio = "' . $datos['servicio'] . '"');

        if (!empty($verificarCorrectivosGenerales)) {
            if (!empty($verificarCorrectivosDiagnostico)) {
                $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia_Correctivo_Reparacion_Con_Refaccoion/';
                $archivos = setMultiplesArchivos($CI, 'evidenciasSolucionReparacionConRefaccion', $carpeta);

                $dataCorrectivosSoluciones = array(
                    'IdServicio' => $datos['servicio'],
                    'IdTipoSolucion' => '2',
                    'IdUsuario' => $usuario['Id'],
                    'Fecha' => $fecha,
                    'Observaciones' => $datos['observaciones']
                );

                if (isset($datos['usaStock']) && $datos['usaStock'] != 'false') {
                    $datosTablaReparacionRefaccion = $this->DBP->getDatosTablaReparacionRefaccionInventario($datos['datosTablaReparacionRefaccion']);
                } else {
                    if (is_array($datos['datosTablaReparacionRefaccion'])) {
                        $datosTablaReparacionRefaccion = $datos['datosTablaReparacionRefaccion'];
                    } else {
                        $datosTablaReparacionRefaccion = divideString($datos['datosTablaReparacionRefaccion'], 3);
                    }
                }


                if ($archivos) {
                    $IdCorrectivoSoluciones = $this->DBP->insertarServicioCorrectivoSolicitudesSolucionRefaccion($dataCorrectivosSoluciones, $datosTablaReparacionRefaccion);
                    $archivos = implode(',', $archivos);

                    if ($datos['evidencias'] !== NULL) {
                        if ($datos['evidencias'] !== '') {
                            if ($datos['idTipoSolucion'] === '2') {
                                $evidenciasAnteriores = $datos['evidencias'] . ',';
                            }
                        }
                    }

                    if (!empty($IdCorrectivoSoluciones)) {
                        $this->DBS->actualizarSeguimiento('t_correctivos_soluciones', array(
                            'Evidencias' => $evidenciasAnteriores . $archivos
                                ), array('Id' => $IdCorrectivoSoluciones)
                        );
                    } else {
                        return FALSE;
                    }
                } else {
                    $evidencias = $this->DBS->consultaGeneralSeguimiento('SELECT Evidencias FROM t_correctivos_soluciones WHERE IdServicio = ' . $datos['servicio'] . ' order by Fecha DESC LIMIT 1');
                    $IdCorrectivoSoluciones = $this->DBP->insertarServicioCorrectivoSolicitudesSolucionRefaccion($dataCorrectivosSoluciones, $datosTablaReparacionRefaccion);
                    if (!empty($IdCorrectivoSoluciones)) {
                        $this->DBS->actualizarSeguimiento('t_correctivos_soluciones', array(
                            'Evidencias' => $evidencias[0]['Evidencias']
                                ), array('Id' => $IdCorrectivoSoluciones)
                        );
                    } else {
                        return FALSE;
                    }
                }
            } else {
                return 'faltaDatosDiagnostico';
            }
        } else {
            return 'faltaDatosGenerales';
        }
    }

    public function guardarCambioEquipo(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = null;
        $CI = parent::getCI();
        $evidenciasAnteriores = '';
        $verificarCorrectivosGenerales = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_correctivos_generales WHERE IdServicio = "' . $datos['servicio'] . '"');
        $verificarCorrectivosDiagnostico = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_correctivos_diagnostico WHERE IdServicio = "' . $datos['servicio'] . '"');

        if (!empty($verificarCorrectivosGenerales)) {
            if (!empty($verificarCorrectivosDiagnostico)) {
                $sucursal = $this->DBS->consultaGeneralSeguimiento('SELECT IdSucursal FROM t_servicios_ticket WHERE Id = "' . $datos['servicio'] . '"');
                $censoGeneral = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                        IdServicio 
                                                                    FROM t_censos_generales
                                                                    WHERE IdSucursal = "' . $sucursal[0]['IdSucursal'] . '"
                                                                    ORDER BY Id DESC LIMIT 1');

                $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencia_Correctivo_Cambio_Equipo/';
                $archivos = setMultiplesArchivos($CI, 'evidenciasSolucionCambioEquipo', $carpeta);
                $dataCorrectivosSoluciones = array(
                    'IdServicio' => $datos['servicio'],
                    'IdTipoSolucion' => '3',
                    'IdUsuario' => $usuario['Id'],
                    'Fecha' => $fecha,
                    'Observaciones' => $datos['observaciones'],
                );

                $dataCenso = array(
                    'IdServicioCenso' => $censoGeneral[0]['IdServicio'],
                    'IdArea' => $verificarCorrectivosGenerales[0]['IdArea'],
                    'IdModelo' => $verificarCorrectivosGenerales[0]['IdModelo'],
                    'Punto' => $verificarCorrectivosGenerales[0]['Punto'],
                    'Terminal' => $verificarCorrectivosGenerales[0]['Terminal']
                );

                if ($archivos) {
                    $IdCorrectivoSoluciones = $this->DBP->insertarServicioCorrectivoSolicitudesSolucionCambio($dataCorrectivosSoluciones, $datos['equipo'], $datos['serie'], $dataCenso, $datos['idsInventario'], $datos['operacion']);
                    $archivos = implode(',', $archivos);
                    if ($datos['evidencias'] !== NULL) {
                        if ($datos['evidencias'] !== '') {
                            if ($datos['idTipoSolucion'] === '3') {
                                $evidenciasAnteriores = $datos['evidencias'] . ',';
                            }
                        }
                    }

                    if (!empty($IdCorrectivoSoluciones)) {
                        $this->DBS->actualizarSeguimiento('t_correctivos_soluciones', array(
                            'Evidencias' => $evidenciasAnteriores . $archivos
                                ), array('Id' => $IdCorrectivoSoluciones)
                        );
                    } else {
                        return FALSE;
                    }
                } else {
                    $evidencias = $this->DBS->consultaGeneralSeguimiento('SELECT Evidencias FROM t_correctivos_soluciones WHERE IdServicio = ' . $datos['servicio'] . ' order by Fecha DESC LIMIT 1');
                    $IdCorrectivoSoluciones = $this->DBP->insertarServicioCorrectivoSolicitudesSolucionCambio($dataCorrectivosSoluciones, $datos['equipo'], $datos['serie'], $dataCenso, $datos['idsInventario'], $datos['operacion']);
                    if (!empty($IdCorrectivoSoluciones)) {
                        $this->DBS->actualizarSeguimiento('t_correctivos_soluciones', array(
                            'Evidencias' => $evidencias[0]['Evidencias']
                                ), array('Id' => $IdCorrectivoSoluciones)
                        );
                    } else {
                        return FALSE;
                    }
                }
            } else {
                return 'faltaDatosDiagnostico';
            }
        } else {
            return 'faltaDatosGenerales';
        }
    }

    public function generarPDFImpericia(string $img, string $direccion, string $servicio, string $ticket) {
        $img = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $img));
        $data = base64_decode($img);
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccion, $data);
        $linkPdf = $this->getServicioToPdf(array('servicio' => $servicio), 'Impericia');
        $infoServicio = $this->getInformacionServicio($servicio);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $host = $_SERVER['SERVER_NAME'];

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $servicio . '/Pdf/Ticket_' . $ticket . '_Servicio_' . $servicio . '_' . $tipoServicio . 'Impericia.pdf';
        } else {
            $path = 'http://' . $host . '/' . $linkPdf['link'];
        }
        return $path;
    }

    public function enviarReporteImpericia(array $datos) {
        $descripcionDiagnostico = '';
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $direccion = '/storage/Archivos/imagenesFirmas/Correctivo/Impericia/' . str_replace(' ', '_', 'Firma_' . $datos['ticket'] . '_' . $datos['servicio']) . '.png';
        $datosDiagnostico = $this->InformacionServicios->consultaCorrectivosDiagnostico($datos['servicio']);

        if ($datosDiagnostico[0]['IdTipoDiagnostico'] === '4') {
            $descripcionDiagnostico .= 'Componente: <strong>' . $datosDiagnostico[0]['Componente'] . '</strong><br>';
        } else if ($datosDiagnostico[0]['IdTipoDiagnostico'] === '4' || $datosDiagnostico[0]['IdTipoDiagnostico'] === '3' || $datosDiagnostico[0]['IdTipoDiagnostico'] === '2') {
            $descripcionDiagnostico .= 'Tipo Falla: <strong>' . $datosDiagnostico[0]['NombreTipoFalla'] . '</strong><br>';
            $descripcionDiagnostico .= 'Falla: <strong>' . $datosDiagnostico[0]['NombreFalla'] . '</strong><br>';
        }
        $correo = $datos['correo'];

        if (is_array($correo)) {
            $correo = implode(",", $correo);
        }

        $consulta = $this->DBS->actualizarSeguimiento('t_correctivos_diagnostico', array(
            'Firma' => $direccion,
            'Gerente' => $datos['recibe'],
            'CopiasCorreo' => $correo,
            'FechaFirma' => $fecha
                ), array('Id' => $datosDiagnostico[0]['Id']));

        $path = $this->generarPDFImpericia($datos['img'], $direccion, $datos['servicio'], $datos['ticket']);

        $correoSupervisor = $this->consultaCorreoSupervisorXSucursal($datos['datosConcluir']['sucursal']);
        $consultaSucursal = $this->DBS->consultaGeneralSeguimiento('SELECT Nombre FROM cat_v3_sucursales WHERE Id = "' . $datos['datosConcluir']['sucursal'] . '"');

        $detallesServicio = $this->linkDetallesServicio($datos['servicio']);
        $linkDetallesServicio = '<br>Ver Detalles del Servicio <a href="' . $detallesServicio . '" target="_blank">Aquí</a>';
        $PDF = '<br>Ver PDF <a href="' . $path . '" target="_blank">Aquí</a>';
        $descripcionImpericia = $descripcionDiagnostico . 'Descripción: <strong>' . $datosDiagnostico[0]['Observaciones'] . '</strong><br>';
        $titulo = 'Reporte Correctivo - ' . $consultaSucursal[0]['Nombre'];
        $texto = '<p>Estimado(a) <strong>' . $datos['recibe'] . ',</strong> se le ha mandado el reporte que ha firmado.</p>' . $descripcionImpericia . $PDF . $linkDetallesServicio;

        $mensajeFirma = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $datos['correo'], $titulo, $mensajeFirma);

        $textoUsuario = '<p>Estimado(a) <strong>' . $usuario['Nombre'] . ',</strong> se le ha mandado el documento del reporte.</p>' . $descripcionImpericia . $PDF . $linkDetallesServicio;
        $mensajeAtiende = $this->Correo->mensajeCorreo($titulo, $textoUsuario);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($usuario['EmailCorporativo']), $titulo, $mensajeAtiende);

        $textoSupervisor = '<p>Estimado(a) <strong>' . $usuario['Nombre'] . ',</strong> se le ha mandado el documento del reporte del servicio correctivo que esta realizando el técnico ' . $usuario['Nombre'] . '.</p>' . $descripcionImpericia . $PDF . $linkDetallesServicio;
        $mensajeSupervisor = $this->Correo->mensajeCorreo($titulo, $textoSupervisor);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($correoSupervisor[0]['CorreoSupervisor']), $titulo, $mensajeSupervisor);

        $this->InformacionServicios->guardarDatosServiceDesk($datos['servicio']);

        if ($consulta) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function enviarRetiroGarantiaRespaldo(array $datos, string $idCorrectivoGarantiaRespaldo) {
        $dataNotificacion = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $img = $datos['img'];
        $img = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $img));
        $data = base64_decode($img);
        $direccion = '/storage/Archivos/imagenesFirmas/Correctivo/RetiroGarantiaRespaldo/' . str_replace(' ', '_', 'Firma_' . $datos['ticket'] . '_' . $datos['servicio']) . '.png';
        $correo = $datos['correo'];

        if (is_array($correo)) {
            $correo = implode(",", $correo);
        }

        $consulta = $this->DBS->insertarSeguimiento('t_equipos_garantia_respaldo', array(
            'IdGarantia' => $idCorrectivoGarantiaRespaldo,
            'IdModeloRetira' => $datos['equipoRetirado'],
            'IdModeloRespaldo' => $datos['equipo'],
            'SerieRetira' => $datos['serieRetirado'],
            'SerieRespaldo' => $datos['serie'],
            'NombreFirma' => $datos['recibe'],
            'CorreoCopiaFirma' => $correo,
            'Firma' => $direccion
        ));
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccion, $data);
        $linkPdf = $this->getServicioToPdf(array('servicio' => $datos['servicio']), 'RetiroGarantiaRespaldo');
        $infoServicio = $this->getInformacionServicio($datos['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $host = $_SERVER['SERVER_NAME'];

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '.pdf';
        } else {
            $path = 'http://' . $host . '/' . $linkPdf['link'];
        }


        $correoSupervisor = $this->consultaCorreoSupervisorXSucursal($datos['sucursal']);
        $detallesServicio = $this->linkDetallesServicio($datos['servicio']);
        $linkDetallesServicio = '<br>Ver Detalles del Servicio <a href="' . $detallesServicio . '" target="_blank">Aquí</a>';
        $PDF = '<br>Ver PDF <a href="' . $path . '" target="_blank">Aquí</a>';
        $titulo = 'Retiro a Garantía con Respaldo';
        $texto = '<p><strong>Estimado(a) ' . $datos['recibe'] . ',</strong> se le ha mandado el documento del retiro a garantía con respaldo que ha firmado.</p>' . $PDF . $linkDetallesServicio;

        $mensajeFirma = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $datos['correo'], $titulo, $mensajeFirma);

        $textoUsuario = '<p><strong>Estimado(a) ' . $correoSupervisor[0]['NombreSupervisor'] . ',</strong> se le ha mandado el documento del retiro a garantía con respaldo que ha recogido.</p>' . $PDF . $linkDetallesServicio;
        $mensajeAtiende = $this->Correo->mensajeCorreo($titulo, $textoUsuario);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($usuario['EmailCorporativo']), $titulo, $mensajeAtiende);

        $textoSupervisor = '<p><strong>Estimado(a) ' . $usuario['Nombre'] . ',</strong> se le ha mandado el documento del retiro a garantía con respaldo que ha retirado el técnico <strong>' . $usuario['Nombre'] . '</strong>.</p>' . $PDF . $linkDetallesServicio;
        $mensajeSupervisor = $this->Correo->mensajeCorreo($titulo, $textoSupervisor);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($correoSupervisor[0]['CorreoSupervisor']), $titulo, $mensajeSupervisor);

        $correoCordinadorPoliza = $this->DBS->consultaGeneralSeguimiento('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 46');
        $textoCoordinadorPoliza = '<p><strong>Cordinador de Poliza,</strong> se le ha mandado el documento del retiro a garantía con respaldo que ha retirado el técnico <strong>' . $usuario['Nombre'] . '</strong>.</p>' . $PDF . $linkDetallesServicio;
        $mensajeCoordinadorPoliza = $this->Correo->mensajeCorreo($titulo, $textoCoordinadorPoliza);
        foreach ($correoCordinadorPoliza as $key => $value) {
            $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($value['EmailCorporativo']), $titulo, $mensajeCoordinadorPoliza);
        }

        $dataNotificacion['departamento'] = '10';
        $dataNotificacion['remitente'] = $usuario['Id'];
        $dataNotificacion['tipo'] = '20';
        $dataNotificacion['descripcion'] = 'El servicio <b class="f-s-16">' . $datos['servicio'] . '</b> del ticket ' . $datos['ticket'] . 'se retiro un equipo con respaldo.';

        $this->Notificacion->setNuevaNotificacion(
                $dataNotificacion, $titulo, 'El usuario <b>' . $usuario['Nombre'] . '</b> a retirado un equipo para garantia y a dejado un respaldo.');

        if ($consulta) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function enviarEntregaEquipoGarantia(array $datos) {
        $dataNotificacion = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $img = $datos['img'];
        $img = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $img));
        $data = base64_decode($img);
        $correo = $datos['correo'];

        if (is_array($correo)) {
            $correo = implode(",", $correo);
        }
        $direccionURL = '/storage/Archivos/imagenesFirmas/Correctivo/AcuseEntrega/' . str_replace(' ', '_', 'Firma_' . $datos['ticket'] . '_' . $datos['servicio']) . '.png';
        $idProblemaCorrectivo = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_correctivos_problemas WHERE IdServicio = "' . $datos['servicio'] . '" ORDER BY Id DESC LIMIT 1 ');

        if ($datos['operacion'] === '2') {
            $recibe = 'IdUsuarioRecibe';
        } else {
            $recibe = 'NombreRecibe';
        }
        $consulta = $this->DBS->insertarSeguimiento('t_correctivos_entregas_equipo', array(
            'IdProblemaCorrectivo' => $idProblemaCorrectivo[0]['Id'],
            'IdUsuario' => $usuario['Id'],
            $recibe => $datos['recibe'],
            'CorreoCopia' => $correo,
            'Firma' => $direccionURL,
            'Fecha' => $fecha
        ));
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccionURL, $data);
        $linkPdf = $this->getServicioToPdf(array('servicio' => $datos['servicio']), 'AcuseEntrega');
        $infoServicio = $this->getInformacionServicio($datos['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $host = $_SERVER['SERVER_NAME'];

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '.pdf';
        } else {
            $path = 'http://' . $host . '/' . $linkPdf['link'];
        }

        $correoSupervisor = $this->consultaCorreoSupervisorXSucursal($datos['sucursal']);
        $detallesServicio = $this->linkDetallesServicio($datos['servicio']);
        $linkDetallesServicio = '<br>Ver Detalles del Servicio <a href="' . $detallesServicio . '" target="_blank">Aquí</a>';
        $PDF = '<br>Ver PDF <a href="' . $path . '" target="_blank">Aquí</a>';
        $titulo = 'Acuse de Entrega';
        $texto = '<p>Se le ha mandado una copia del documento de Acuse de Entrega que ha entregado el técnico' . $usuario['Nombre'] . '.</p>' . $PDF . $linkDetallesServicio;

        $mensajeFirma = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $datos['correo'], $titulo, $mensajeFirma);

        $textoUsuario = '<p><strong>Estimado(a) ' . $usuario['Nombre'] . ',</strong> se le ha mandado el documento Acuse de entrega del equipo que ha entregado.</p>' . $PDF . $linkDetallesServicio;
        $mensajeAtiende = $this->Correo->mensajeCorreo($titulo, $textoUsuario);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($usuario['EmailCorporativo']), $titulo, $mensajeAtiende);

        $textoSupervisor = '<p><strong>Estimado(a) ' . $correoSupervisor[0]['NombreSupervisor'] . ',</strong> se le ha mandado el documento Acuse de Entrega del equipo que ha entregado el técnico  <strong>' . $usuario['Nombre'] . '</strong>.</p>' . $PDF . $linkDetallesServicio;
        $mensajeSupervisor = $this->Correo->mensajeCorreo($titulo, $textoSupervisor);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($correoSupervisor[0]['CorreoSupervisor']), $titulo, $mensajeSupervisor);

        $correoCordinadorPoliza = $this->DBS->consultaGeneralSeguimiento('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 46');
        $textoCoordinadorPoliza = '<p><strong>Cordinador de Poliza,</strong> se le ha mandado el documento de la entrega de equipo del personal <strong>' . $usuario['Nombre'] . '</strong>.</p>' . $PDF . $linkDetallesServicio;
        $mensajeCoordinadorPoliza = $this->Correo->mensajeCorreo($titulo, $textoCoordinadorPoliza);
        foreach ($correoCordinadorPoliza as $key => $value) {
            $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($value['EmailCorporativo']), $titulo, $mensajeCoordinadorPoliza);
        }

        $dataNotificacionLaboratorio['departamento'] = '10';
        $dataNotificacionLaboratorio['remitente'] = $usuario['Id'];
        $dataNotificacionLaboratorio['tipo'] = '21';
        $dataNotificacionLaboratorio['descripcion'] = 'El servicio <b class="f-s-16">' . $datos['servicio'] . '</b> del ticket ' . $datos['ticket'] . 'se entrego un equipo.';

        $this->Notificacion->setNuevaNotificacion(
                $dataNotificacionLaboratorio, $titulo, 'El usuario <b>' . $usuario['Nombre'] . '</b> a entregado un equipo.');

        $dataNotificacionAlmacen['departamento'] = '16';
        $dataNotificacionAlmacen['remitente'] = $usuario['Id'];
        $dataNotificacionAlmacen['tipo'] = '21';
        $dataNotificacionAlmacen['descripcion'] = 'El servicio <b class="f-s-16">' . $datos['servicio'] . '</b> del ticket ' . $datos['ticket'] . 'se entrego un equipo.';

        $this->Notificacion->setNuevaNotificacion(
                $dataNotificacionAlmacen, $titulo, 'El usuario <b>' . $usuario['Nombre'] . '</b> a entregado un equipo.');

        $dataNotificacionLogistica['departamento'] = '17';
        $dataNotificacionLogistica['remitente'] = $usuario['Id'];
        $dataNotificacionLogistica['tipo'] = '21';
        $dataNotificacionLogistica['descripcion'] = 'El servicio <b class="f-s-16">' . $datos['servicio'] . '</b> del ticket ' . $datos['ticket'] . 'se entrego un equipo.';

        $this->Notificacion->setNuevaNotificacion(
                $dataNotificacionLogistica, $titulo, 'El usuario <b>' . $usuario['Nombre'] . '</b> a entregado un equipo.');

        if ($consulta) {
            return $this->DBB->consultaCorrectivoEntregasEquipo($datos['servicio']);
        } else {
            return FALSE;
        }
    }

    public function enviarSolucionCorrectivoSD(array $datos) {
        $this->enviar_Reporte_PDF($datos);

        $this->InformacionServicios->guardarDatosServiceDesk($datos['servicio'], TRUE);

        $verificarEstatusTicket = $this->consultaCorrectivosServiciosTicket($datos['ticket'], $datos['servicio']);

        if (!empty($verificarEstatusTicket)) {
            return 'faltanServicios';
        } else {
            return 'serviciosConcluidos';
        }
    }

    private function actualizarServicioSucursal(string $sucursal, string $servicio) {
        $this->DBS->actualizarSeguimiento('t_servicios_ticket', array(
            'IdSucursal' => $sucursal,
                ), array('Id' => $servicio)
        );
    }

    public function mostrarFormularioAntesYDespues(array $datos) {
        $data = [];
        $array = array('servicioCenso' => $datos['servicioCenso'],
            'servicio' => $datos['servicio'],
            'area' => $datos['area'],
            'punto' => $datos['punto']);
        $data['equipos'] = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM v_equipos');
        $data['equiposSAE'] = $this->Catalogo->catEquiposSAE('3', array('Flag' => '1'));
        $data['componentesEquipo'] = $this->Catalogo->catComponentesEquipo('3');
        $data['equiposSucursal'] = $this->consultaProblemasEquipos($array);
        $data['informacionPuntoCensado'] = $this->consultaAntesYDespues($datos['servicio'], $datos['area'], $datos['punto']);
        $data['evidenciaAntes'] = explode(',', $data['informacionPuntoCensado'][0]['EvidenciasAntes']);
        $data['evidenciaDespues'] = explode(',', $data['informacionPuntoCensado'][0]['EvidenciasDespues']);
        $data['idSucursal'] = $this->DBS->consultaGeneralSeguimiento('SELECT IdSucursal FROM t_mantenimientos_generales WHERE IdServicio = "' . $datos['servicio'] . '"');
        $data['problemasEquipo'] = $this->consultaProblemasEquiposServicio(array('servicio' => $datos['servicio'], 'area' => $datos['area'], 'punto' => $datos['punto']));
        $data['equipoFaltante'] = $this->consultaEquiposFaltantes($datos['servicio'], $datos['area'], $datos['punto']);
        $data['formulario'] = parent::getCI()->load->view('Poliza/Modal/formularioAntesYDespues', $data, TRUE);
        return $data;
    }

    public function sobreEscribirServicioCenso(string $servicio, string $sucursal) {
        $verificarCensoExistente = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_censos WHERE IdServicio = "' . $servicio . '"');
        if (empty($verificarCensoExistente)) {
            $this->DBS->consultaQuery('insert into t_censos
                                    select 
                                    null,
                                    "' . $servicio . '", 
                                    IdArea, 
                                    IdModelo, 
                                    Punto, 
                                    Serie, 
                                    Extra,
                                    1,
                                    0,
				    17,
				    null,
				    null
                                    from t_censos 
                                    where IdServicio = (
                                                    select IdServicio 
                                                    from t_censos_generales tcg inner join t_servicios_ticket tst
                                                    on tcg.IdServicio = tst.Id
                                                    WHERE tcg.IdSucursal = "' . $sucursal . '"
                                                    and tst.IdEstatus = 4
                                                    order by IdServicio desc limit 1)'
            );

            $this->DBS->queryBolean("insert into t_censos_puntos
                                    select
                                    null,
                                    IdServicio,
                                    IdArea,
                                    MAX(Punto) as Puntos
                                    from t_censos where IdServicio = '" . $servicio . "'
                                    group by IdArea");
        }
    }

    public function eliminarCenso(array $datos) {
        $consulta = $this->DBS->consultaQuery('DELETE FROM t_censos WHERE IdServicio = "' . $datos['servicio'] . '" AND Serie = "' . $datos['serie'] . '" AND Extra = "' . $datos['numeroTerminal'] . '"');
        if ($consulta) {
            return $this->consultaTodosCensoServicio($datos['servicio']);
        } else {
            return false;
        }
    }

    public function eliminarEquipoFaltante(array $datos) {
        $verificarExistente = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_mantenimientos_equipo_faltante WHERE IdServicio =  "' . $datos['servicio'] . '" AND IdArea = "' . $datos['area'] . '" AND Punto = "' . $datos['punto'] . '" AND IdModelo = "' . $datos['modelo'] . '" AND TipoItem = "' . $datos['tipoItem'] . '"');
        if (!empty($verificarExistente)) {
            $consulta = $this->DBS->consultaQuery('DELETE FROM t_mantenimientos_equipo_faltante WHERE Id =  "' . $verificarExistente[0]['Id'] . '"');
            if ($consulta) {
                return $this->consultaEquiposFaltantes($datos['servicio'], $datos['area'], $datos['punto']);
            } else {
                return false;
            }
        } else {
            return 'NoExiste';
        }
    }

    public function eliminarDetallesSolicitud(array $datos) {
        if ($datos['tipoSolicitud'] === 'refaccion') {
            $tabla = 't_correctivos_solicitudes_refaccion';
        } else {
            $tabla = 't_correctivos_solicitudes_equipo';
        }

        $idServicio = $this->DBS->consultaGeneralSeguimiento('SELECT IdServicio FROM ' . $tabla . ' WHERE Id =  "' . $datos['idSolicitud'] . '"');

        $consulta = $this->DBS->consultaQuery('DELETE FROM ' . $tabla . ' WHERE Id =  "' . $datos['idSolicitud'] . '"');
        if ($consulta) {
            $verificarServicio = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_servicios_ticket WHERE Id =  "' . $idServicio[0]['IdServicio'] . '"');
            if (!empty($verificarServicio)) {
                $idSolicitudes = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM ' . $tabla . ' WHERE IdServicio = "' . $idServicio[0]['IdServicio'] . '"');

                if (empty($idSolicitudes)) {
                    $usuario = $this->Usuario->getDatosUsuario();
                    $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
                    $data = array(
                        'IdEstatus' => '6'
                    );
                    $this->DBST->actualizarServicio('t_servicios_ticket', $data, array('Id' => $idServicio[0]['IdServicio']));

                    $data = array(
                        'IdUsuario' => $usuario['Id'],
                        'IdEstatus' => '6',
                        'IdServicio' => $idServicio[0]['IdServicio'],
                        'Nota' => 'Se cancela la solicitud',
                        'Fecha' => $fecha
                    );

                    $notas = $this->DBST->setNuevoElemento('t_notas_servicio', $data);
                    if (!empty($notas)) {
                        if ($datos['tipoSolicitud'] === 'refaccion') {
                            $consultaSolicitudes = $this->consultaCorrectivosSolicitudRefaccion($datos['servicio']);
                        } else {
                            $consultaSolicitudes = $this->consultaCorrectivosSolicitudEquipo($datos['servicio']);
                        }
                        return $consultaSolicitudes;
                    } else {
                        return FALSE;
                    }
                } else {
                    if ($datos['tipoSolicitud'] === 'refaccion') {
                        $consultaSolicitudes = $this->consultaCorrectivosSolicitudRefaccion($datos['servicio']);
                    } else {
                        $consultaSolicitudes = $this->consultaCorrectivosSolicitudEquipo($datos['servicio']);
                    }
                    return $consultaSolicitudes;
                }
            } else {
                if ($datos['tipoSolicitud'] === 'refaccion') {
                    $consultaSolicitudes = $this->consultaCorrectivosSolicitudRefaccion($datos['servicio']);
                } else {
                    $consultaSolicitudes = $this->consultaCorrectivosSolicitudEquipo($datos['servicio']);
                }
            }
        } else {
            return FALSE;
        }
    }

    public function eliminarProblemaEquipo(array $datos) {
        $arrayDatos = array('servicio' => $datos['servicio'],
            'area' => $datos['area'],
            'punto' => $datos['punto'],
            'modelo' => $datos['modelo']);

        $consultaEvidencias = $this->DBS->consultaGeneralSeguimiento('SELECT Evidencias FROM t_mantenimientos_problemas_equipo WHERE IdServicio =  "' . $datos['servicio'] . '" AND IdArea = "' . $datos['area'] . '" AND Punto = "' . $datos['punto'] . '" AND IdModelo = "' . $datos['modelo'] . '"');
        $archivos = explode(',', $consultaEvidencias[0]['Evidencias']);
        $consulta = $this->DBS->consultaQuery('DELETE FROM t_mantenimientos_problemas_equipo WHERE IdServicio =  "' . $datos['servicio'] . '" AND IdArea = "' . $datos['area'] . '" AND Punto = "' . $datos['punto'] . '" AND IdModelo = "' . $datos['modelo'] . '"');
        if ($consulta) {
            foreach ($archivos as $key => $value) {
                eliminarArchivo($value);
            }
            return $this->consultaProblemasEquiposServicio($arrayDatos);
        } else {
            return false;
        }
    }

    public function eliminarProblemaAdicional(array $datos) {
        $consultaEvidencias = $this->DBS->consultaGeneralSeguimiento('SELECT Evidencias FROM t_mantenimientos_problemas_adicionales WHERE Id =  "' . $datos['id'] . '"');
        $archivos = explode(',', $consultaEvidencias[0]['Evidencias']);
        $consulta = $this->DBS->consultaQuery('DELETE FROM t_mantenimientos_problemas_adicionales WHERE Id =  "' . $datos['id'] . '"');
        if ($consulta) {
            foreach ($archivos as $key => $value) {
                eliminarArchivo($value);
            }
            return $this->consultaProblemasAdicionales($datos['servicio']);
        } else {
            return false;
        }
    }

    public function eliminarEvidencia(array $datos) {
        $posicionInicial = strpos($datos['key'], 'Servicio-') + 9;
        $posicionFinal = strpos($datos['key'], '/', $posicionInicial);
        $diferencia = $posicionFinal - $posicionInicial;
        $servicio = substr($datos['key'], $posicionInicial, $diferencia);
        $posicionInicial = strpos($datos['id'], '-');
        if ($posicionInicial) {
            $columnaCampo = substr($datos['id'], 0, $posicionInicial);
        } else {
            $columnaCampo = $datos['id'];
        }

        switch ($columnaCampo) {
            case 'evidenciasAntes':
                $tabla = 't_mantenimientos_antes_despues';
                $columnaCampo = 'EvidenciasAntes';
                $consultaObtenerEvidencias = 'select EvidenciasAntes
                        from t_mantenimientos_antes_despues 
                        where IdServicio = "' . $servicio . '"
                        and EvidenciasAntes like "%' . $datos['key'] . '%"';
                $idProblemaAdicional = $this->DBS->consultaGeneralSeguimiento('select Id
                        from t_mantenimientos_antes_despues 
                        where IdServicio = "' . $servicio . '"
                        and EvidenciasAntes like "%' . $datos['key'] . '%"');
                $where = array('Id' => $idProblemaAdicional[0]['Id']);
                break;
            case 'evidenciasDespues':
                $tabla = 't_mantenimientos_antes_despues';
                $columnaCampo = 'EvidenciasDespues';
                $consultaObtenerEvidencias = 'select EvidenciasDespues
                        from t_mantenimientos_antes_despues 
                        where IdServicio = "' . $servicio . '"
                        and EvidenciasDespues like "%' . $datos['key'] . '%"';
                $idProblemaAdicional = $this->DBS->consultaGeneralSeguimiento('select Id
                        from t_mantenimientos_antes_despues 
                        where IdServicio = "' . $servicio . '"
                        and EvidenciasDespues like "%' . $datos['key'] . '%"');
                $where = array('Id' => $idProblemaAdicional[0]['Id']);
                break;
        }
        $evidencias = $this->DBS->consultaGeneralSeguimiento($consultaObtenerEvidencias);
        $evidencias = explode(',', $evidencias[0][$columnaCampo]);

        if (in_array($datos['key'], $evidencias)) {
            foreach ($evidencias as $key => $value) {
                if ($value === $datos['key']) {
                    unset($evidencias[$key]);
                }
            }

            $archivos = implode(',', $evidencias);
            $datosActualizar = array($columnaCampo => $archivos);
            $consulta = $this->DBS->actualizarSeguimiento($tabla, $datosActualizar, $where);

            if (!empty($consulta)) {
                eliminarArchivo($datos['key']);
            } else {
                return FALSE;
            }
        }
    }

    public function eliminarEvidenciaDiagnostico(array $datos) {
        $evidencias = $this->DBS->consultaGeneralSeguimiento('select 
                                                                Evidencias
                                                            from t_correctivos_diagnostico 
                                                            where IdServicio = "' . $datos['id'] . '"
                                                            and Evidencias like "%' . $datos['key'] . '%"
                                                            ORDER BY Id DESC LIMIT 1');
        $evidencias = explode(',', $evidencias[0]['Evidencias']);

        foreach ($evidencias as $key => $value) {
            if ($datos['key'] === $value) {
                unset($evidencias[$key]);
            }
        }
        if (eliminarArchivo($datos['key'])) {
            $evidencias = implode(',', $evidencias);
            $this->DBS->consultaQuery('update 
                                        t_correctivos_diagnostico
                                        set Evidencias = "' . $evidencias . '"
                                        where Id = (select Id from (select MAX(Id) as Id from t_correctivos_diagnostico where IdServicio = "' . $datos['id'] . '") as tf)
                                        ');
            if (!empty($consulta)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function eliminarEvidenciaSolucion(array $datos) {
        $evidencias = $this->DBS->consultaGeneralSeguimiento('select 
                                                                Evidencias
                                                            from t_correctivos_soluciones 
                                                            where IdServicio = "' . $datos['id'] . '"
                                                            and Evidencias like "%' . $datos['key'] . '%"
                                                            ORDER BY Id DESC LIMIT 1');
        $evidencias = explode(',', $evidencias[0]['Evidencias']);

        foreach ($evidencias as $key => $value) {
            if ($datos['key'] === $value) {
                unset($evidencias[$key]);
            }
        }

        if (eliminarArchivo($datos['key'])) {
            $evidencias = implode(',', $evidencias);
            $this->DBS->consultaQuery('update 
                                        t_correctivos_soluciones
                                        set Evidencias = "' . $evidencias . '"
                                        where Id = (select Id from (select MAX(Id) as Id from t_correctivos_soluciones where IdServicio = "' . $datos['id'] . '") as tf)
                                        ');
            if (!empty($consulta)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function eliminarEvidenciaEnviosEquipo(array $datos) {
        ($datos['id']['tipo'] === 'envio') ? $tipo = 'EvidenciasEnvio' : $tipo = 'EvidenciasEntrega';

        $idCorrectivoProblema = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                            Id
                                                                        FROM t_correctivos_problemas 
                                                                        WHERE IdServicio = "' . $datos['id']['servicio'] . '" 
                                                                        ORDER BY Id DESC LIMIT 1');
        $evidencias = $this->DBS->consultaGeneralSeguimiento('select 
                                                                ' . $tipo . '
                                                            from t_correctivos_envios_equipo 
                                                            where IdProblemaCorrectivo = "' . $idCorrectivoProblema[0]['Id'] . '"
                                                            and ' . $tipo . ' like "%' . $datos['key'] . '%"
                                                            ORDER BY Id DESC LIMIT 1');
        $evidencias = explode(',', $evidencias[0][$tipo]);

        foreach ($evidencias as $key => $value) {
            if ($datos['key'] === $value) {
                unset($evidencias[$key]);
            }
        }
        if (eliminarArchivo($datos['key'])) {
            $evidencias = implode(',', $evidencias);
            $this->DBS->consultaQuery('update 
                                        t_correctivos_envios_equipo
                                        set ' . $tipo . ' = "' . $evidencias . '"
                                        where Id = (select Id from (select MAX(Id) as Id from t_correctivos_envios_equipo where IdProblemaCorrectivo = "' . $idCorrectivoProblema[0]['Id'] . '") as tf)
                                        ');
            if (!empty($consulta)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function getServicioToPdf(array $servicio, string $nombreExtra = NULL) {
        $infoServicio = $this->getInformacionServicio($servicio['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $nombreExtra = (is_null($nombreExtra)) ? '' : $nombreExtra;
        $archivo = 'storage/Archivos/Servicios/Servicio-' . $servicio['servicio'] . '/Pdf/Ticket_' . $infoServicio[0]['Ticket'] . '_Servicio_' . $servicio['servicio'] . '_' . $tipoServicio . $nombreExtra . '.pdf ';
        $ruta = 'http://' . $_SERVER['HTTP_HOST'] . '/Phantom/Servicio/' . $servicio['servicio'] . '/' . $nombreExtra;
        $datosServicio = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                sucursal(IdSucursal) Sucursal,
                                                (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio
                                            FROM t_servicios_ticket
                                            WHERE Id = "' . $servicio['servicio'] . '"');
        $link = $this->Phantom->htmlToPdf($archivo, $ruta, $datosServicio[0]);
        return ['link' => $link];
    }

    public function cambiarEstatusServiceDesk(string $servicio, string $estatus) {
        $usuario = $this->Usuario->getDatosUsuario();
        $folio = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                            (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) AS Folio
                                                        FROM 
                                                            t_servicios_ticket
                                                        WHERE Id ="' . $servicio . '"');
        if (!empty($folio)) {
            if ($folio[0]['Folio'] !== NULL) {
                if ($folio[0]['Folio'] !== '') {
                    if ($folio[0]['Folio'] !== '0') {
                        $key = $this->MSP->getApiKeyByUser($usuario['Id']);
                        $this->ServiceDesk->cambiarEstatusServiceDesk($key, $estatus, $folio[0]['Folio']);
                    }
                }
            }
        }
    }

    public function getInformacionServicio(string $servicio) {
        $sentencia = ""
                . "select ts.Id as Solicitud, "
                . "nombreUsuario(ts.Solicita) as Solicitante, "
                . "ts.FechaCreacion as FechaSolicitud, "
                . "estatus(ts.IdEstatus) as EstatusSolicitud, "
                . "(select Descripcion from t_solicitudes_internas tsi where tsi.IdSolicitud = ts.Id) as DescripcionSolicitud, "
                . "tst.Ticket, "
                . "if(tst.IdSucursal is not null and tst.IdSucursal > 0, sucursal(tst.IdSucursal),'') as Sucursal, "
                . "tst.IdTipoServicio, "
                . "tipoServicio(tst.IdTipoServicio) as TipoServicio, "
                . "replace(tipoServicio(tst.IdTipoServicio),' ','') as NTipoServicio, "
                . "tst.FechaCreacion as FechaServicio, "
                . "estatus(tst.IdEstatus) as EstatusServicio, "
                . "tst.Descripcion as DescripcionServicio, "
                . "case "
                . " when ts.IdEstatus in (4,'4') then "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , ts.FechaCreacion, ts.FechaConclusion))*60) "
                . " when ts.IdEstatus in (6,'6') then "
                . "     '' "
                . " else "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , ts.FechaCreacion, now()))*60) "
                . "end as TiempoSolicitud, "
                . ""
                . "case "
                . " when tst.IdEstatus  in (4,'4') then "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , tst.FechaCreacion, tst.FechaConclusion))*60) "
                . " when tst.IdEstatus  in (6,'6') then "
                . "     '' "
                . " else "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , tst.FechaCreacion, now()))*60) "
                . "end as TiempoServicio "
                . "from t_servicios_ticket tst INNER JOIN t_solicitudes ts "
                . "on tst.IdSolicitud = ts.Id "
                . "where tst.Id = '" . $servicio . "';";
        return $this->DBS->consultaGeneralSeguimiento($sentencia);
    }

    public function enviarCorreoConcluido(array $correo, string $titulo, string $texto) {
        $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $correo, $titulo, $mensaje);
    }

    public function cambiarEstatus(array $datos) {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $this->DBS->actualizarSeguimiento('t_servicios_ticket', array(
            'IdEstatus' => $datos['estatus'],
            'FechaConclusion' => $fecha
                ), array('Id' => $datos['servicio'])
        );

        $this->DBP->actualizaInventariosMovimientosXConslusionCorrectivo($datos['servicio']);
    }

    public function solicitarMultimedia(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $folio = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                            (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) AS Folio
                                                        FROM 
                                                            t_servicios_ticket
                                                        WHERE Id ="' . $datos['servicio'] . '"');
        if (!empty($folio)) {
            if ($folio[0]['Folio'] !== NULL) {
                if ($folio[0]['Folio'] !== '') {
                    if ($folio[0]['Folio'] !== '0') {
                        $key = $this->MSP->getApiKeyByUser($usuario['Id']);

                        $datosExtra = array(
                            'Usuario' => $usuario['Id'],
                            'FechaCreacion' => $fecha,
                        );

                        $this->cambiarEstatus(array('servicio' => $datos['servicio'], 'estatus' => '3'));

                        $linkPDF = $this->cargarPDF($datos);

                        $this->DBP->insertarCorrectivosSolicitudesProblemas($datos, $datosExtra);
                        $this->asignarMultimedia($linkPDF, $folio[0]['Folio'], $key);
                        $this->DBS->actualizarSeguimiento('t_servicios_ticket', array(
                            'IdEstatus' => '3',
                                ), array('Id' => $datos['servicio'])
                        );

                        if ($datos['tipoSolicitud'] === 'equipo') {
                            $respuesta = $this->consultaCorrectivosSolicitudEquipo($datos['servicio']);
                        } else {
                            $respuesta = $this->consultaCorrectivosSolicitudRefaccion($datos['servicio']);
                        }

                        return $respuesta;
                    } else {
                        return 'faltaFolio';
                    }
                } else {
                    return 'faltaFolio';
                }
            } else {
                return 'faltaFolio';
            }
        } else {
            return 'faltaFolio';
        }
    }

    public function asignarMultimedia(string $linkPdf, string $folio, string $key) {
        $usuario = $this->Usuario->getDatosUsuario();
        $linkPDF = '<br>Ver PDF Resumen General <a href="' . $linkPdf . '" target="_blank">Aquí</a>';
        $this->ServiceDesk->cambiarEstatusServiceDesk($key, 'En Atención', $folio);
        $textoMultimedia = '<p><strong>Multimedia,</strong> el técnico <strong>' . $usuario['Nombre'] . '</strong> le ha reasignado la solicitud <strong>' . $folio . '</strong>.</p>' . $linkPDF;
        $this->enviarCorreoConcluido(array('ajimenez@siccob.com.mx'), 'Reasignación de Solicitud', $textoMultimedia);

        $this->ServiceDesk->reasignarFolioSD($folio, '9304', $key);
    }

    public function verificarDiagnostico(array $datos) {
        $verificarCorrectivosDiagnostico = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_correctivos_diagnostico WHERE IdServicio = "' . $datos['servicio'] . '"');

        if (!empty($verificarCorrectivosDiagnostico)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function cargarPDF(array $datos) {
        $host = $_SERVER['SERVER_NAME'];
        $linkPdf = $this->getServicioToPdf($datos);
        $infoServicio = $this->getInformacionServicio($datos['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '.pdf';
        } else {
            $path = 'http://' . $host . '/' . $linkPdf['link'];
        }

        return $path;
    }

    public function linkDetallesServicio(string $servicio) {
        $host = $_SERVER['SERVER_NAME'];

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $detallesServicio = 'https://siccob.solutions/Detalles/Servicio/' . $servicio;
        } else {
            $detallesServicio = 'http://' . $host . '/Detalles/Servicio/' . $servicio;
        }
        return $detallesServicio;
    }

    public function enviar_Reporte_PDF(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $host = $_SERVER['SERVER_NAME'];
        $titulo = 'Se concluyo el servicio';
        $infoServicio = $this->getInformacionServicio($datos['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $detallesServicio = $this->linkDetallesServicio($datos['servicio']);
        $linkDetallesServicio = '<br>Ver Detalles del Servicio <a href="' . $detallesServicio . '" target="_blank">Aquí</a>';
        $path = $this->cargarPDF($datos);
        $linkPDF = '<br>Ver PDF Resumen General <a href="' . $path . '" target="_blank">Aquí</a>';
        $datosDescripcionConclusion = $this->DBS->consultaGeneralSeguimiento('SELECT
                                            tst.Descripcion AS DescripcionServicio,
                                            tst.IdSolicitud,
                                            tsi.Asunto AS AsuntoSolicitud,
                                            tsi.Descripcion AS DescripcionSolicitud
                                           FROM t_servicios_ticket tst
                                           INNER JOIN t_solicitudes_internas tsi
                                           ON tsi.IdSolicitud = tst.IdSolicitud
                                           WHERE tst.Id = "' . $datos['servicio'] . '"');
        $descripcionConclusion = '<br><br>Solicitud: <strong>' . $datosDescripcionConclusion[0]['IdSolicitud'] . '</strong>
                <br>Asunto de la Solicitud: <strong>' . $datosDescripcionConclusion[0]['AsuntoSolicitud'] . '</strong>
                <br>Descripcion de la Solcitud: <strong>' . $datosDescripcionConclusion[0]['AsuntoSolicitud'] . '</strong>
                <br><br>Ticket: <strong>' . $datos['ticket'] . '</strong>
                <br><br>Servicio: <strong>' . $datos['servicio'] . '</strong>
                <br>Descripcion del Servicio: <strong>' . $datosDescripcionConclusion[0]['DescripcionServicio'] . '</strong>';

        $contadorEquiposFaltantes = $this->contadorEquiposFaltantes($datos['servicio']);

        if ($contadorEquiposFaltantes[0]['Contador'] > 0) {
            $linkPdfEquipoFaltante = $this->getServicioToPdf(array('servicio' => $datos['servicio']), '/EquipoFaltante');
            if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                $pathEquipoFaltante = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '.pdf';
            } else {
                $pathEquipoFaltante = 'http://' . $host . '/' . $linkPdfEquipoFaltante['link'];
            }
            $linkExtraEquiposFaltante = '<br>Ver PDF Equipo Faltante <a href="' . $pathEquipoFaltante . '" target="_blank">Aquí</a>';
        } else {
            $linkExtraEquiposFaltante = '';
        }

        $textoUsuario = '<p>Estimado(a) <strong>' . $usuario['Nombre'] . ',</strong> se le ha mandado el documento de la conclusión del servicio que realizo.</p>' . $linkPDF . $linkDetallesServicio . $descripcionConclusion;
        $this->enviarCorreoConcluido(array($usuario['EmailCorporativo']), $titulo, $textoUsuario);

        $datosSolicita = $this->DBS->consultaGeneralSeguimiento('SELECT
                                            (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = tst.Solicita) AS CorreoSolicita,
                                            nombreUsuario(tst.Solicita) NombreSolicita
                                            FROM t_servicios_ticket tst
                                            WHERE tst.Id = "' . $datos['servicio'] . '"');
        $textoSolicita = '<p>Estimado(a) <strong>' . $datosSolicita[0]['NombreSolicita'] . ',</strong> se le ha mandado el documento de la conclusión del servicio que ha solicitado.</p>' . $linkPDF . $linkDetallesServicio . $descripcionConclusion;
        $this->enviarCorreoConcluido(array($datosSolicita[0]['CorreoSolicita']), $titulo, $textoSolicita);

        $idArea = $this->DBS->consultaGeneralSeguimiento('SELECT
                                            cvds.IdArea
                                            FROM t_servicios_ticket tst
                                           INNER JOIN t_solicitudes ts
                                            ON tst.IdSolicitud = ts.Id
                                           INNER JOIN cat_v3_departamentos_siccob cvds
                                            ON ts.IdDepartamento = cvds.Id
                                           WHERE tst.Id = "' . $datos['servicio'] . '"');
        if ($idArea[0]['IdArea'] === '8') {
            $correoCordinadorPoliza = $this->DBS->consultaGeneralSeguimiento('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 46');
            $textoCoordinadorPoliza = '<p><strong>Cordinador de Poliza,</strong> se le ha mandado el documento de la conclusión del servicio que realizo el personal ' . $usuario['Nombre'] . '.</p>' . $linkPDF . $linkDetallesServicio . $descripcionConclusion;
            foreach ($correoCordinadorPoliza as $key => $value) {
                $this->enviarCorreoConcluido(array($value['EmailCorporativo']), $titulo, $textoCoordinadorPoliza);
            }
        }

        return $path;
    }

    public function contadorEquiposFaltantes(string $servicio) {
        return $this->DBS->consultaGeneralSeguimiento('SELECT COUNT(Id) AS Contador FROM t_mantenimientos_equipo_faltante WHERE IdServicio = "' . $servicio . '"');
    }

//----------------------   Seguimiento Equipos
    public function mostrarTabla() {
        $usuario = $this->Usuario->getDatosUsuario();
        $idPerfil = $usuario['IdPerfil'];

        if (in_array('306', $usuario['PermisosAdicionales']) || in_array('306', $usuario['Permisos'])) {
            $datosServicio = $this->DBP->consultaTablaServicioAllab(); // Todas las Solicitudes de equipo
        } else if (in_array('307', $usuario['PermisosAdicionales']) || in_array('307', $usuario['Permisos'])) {
            $datosServicio = $this->DBP->consultaTablaServicioAllabSupervisor($usuario['Id']); // Solicitudes de equipo por Zona del Supervisor
        } else if (in_array('308', $usuario['PermisosAdicionales']) || in_array('308', $usuario['Permisos'])) {
            $datosServicio = $this->DBP->consultaTablaServicioAllabTecnico($usuario['Id']); // Solicitudes de equipo por Tecnico
        } else if (in_array('309', $usuario['PermisosAdicionales']) || in_array('309', $usuario['Permisos'])) {
            switch ($idPerfil) {
                case '51':
                case '62': // Almacen
                    $datosServicio = $this->DBP->consultaTablaServicioAllabPerfilAlmacen(); // Todas las Solicitudes de equipo
                    break;
                case '38':
                case '56': //Laboratorio
                    $datosServicio = $this->DBP->consultaTablaServicioAllabPerfilLaboratorio(); // Todas las Solicitudes de equipo
                    break;
                case '41':
                case '52':
                case '60': // Logistica
                    $datosServicio = $this->DBP->consultaTablaServicioAllabPerfilLogistica(); // Todas las Solicitudes de equipo
                    break;
            }
        } else {
            $datosServicio = array();
        }

        if (!empty($datosServicio)) {
            return ['code' => 200, 'mensaje' => 'Correcto', 'datosTabla' => $datosServicio];
        } else {
            return ['code' => 500, 'mensaje' => 'No hay registros para mostrar'];
        }
    }

    public function mostrarVistaPorUsuario(array $datos = null) {
        $usuario = $this->Usuario->getDatosUsuario();
        $idPerfil = $usuario['IdPerfil'];
        $estatus = $this->DBP->estatusAllab($datos['idServicio']);
        $idEstatus = $estatus['IdEstatus'];
        $flag = $estatus['Flag'];
        $permisos = $usuario['Permisos'];
        $permisosAdionales = $usuario['PermisosAdicionales'];
        if (in_array('306', $usuario['PermisosAdicionales']) || in_array('306', $usuario['Permisos']) || in_array('307', $usuario['PermisosAdicionales']) || in_array('307', $usuario['Permisos'])) {
            return $this->formulariosTecnico($datos, $idEstatus, $flag, $permisos, $permisosAdionales);
        } else {
            switch ($idPerfil) {
                case '57': // Tecnico
                    return $this->formulariosTecnico($datos, $idEstatus, $flag, $permisos, $permisosAdionales);
                    break;
                case '51':
                case '62': // Almacen
                    if ($idEstatus === '2' && $flag === '0') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => $this->vistaSeguimientoSolicitudRefaccionEquipo($datos),
                            'formularioEnvioAlmacen' => [],
                            'formularioRecepcionAlmacen' => [],
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }
                    if ($idEstatus === '12' && $flag === '1') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }
                    if ($idEstatus === '32' && $flag === '1') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }
                    if ($idEstatus === '28' && $flag === '1') {
                        $departamentoEspera = "Laboratorio";
                        $textoEspera = "Esperando informacion del Departamento de Laboratorio";
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }
                    if ($idEstatus === '33' && $flag === '1') {
                        $departamentoEspera = "Laboratorio";
                        $textoEspera = "Esperando informacion del Departamento de Laboratorio";
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }
                    if ($idEstatus === '29' && $flag === '1') {
                        $departamentoEspera = "Laboratorio";
                        $textoEspera = "Esperando informacion del Departamento de Laboratorio (Historial y Refaccion)";
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }

                    if ($idEstatus === '39' && $flag === '1') {
                        $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                        if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                            $departamentoEspera = "Logistica";
                            $textoEspera = "Esperando informacion del Departamento de Logistica";
                        } else {
                            $departamentoEspera = "Técnico";
                            $textoEspera = "Esperando información del Técnico";
                        }
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }

                    if ($idEstatus === '34' && $flag === '1') {
                        $departamentoEspera = "Logistica";
                        $textoEspera = "Esperando informacion del Departamento de Logistica";
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }

                    if ($idEstatus === '30' && $flag === '1') {
                        $departamentoEspera = "Logistica";
                        $textoEspera = "Esperando informacion de envio del Departamento de Logistica";
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                            'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }
                    if ($idEstatus === '12' && $flag === '0') {
                        $departamentoEspera = "Logistica";
                        $textoEspera = "Esperando informacion de envio del Departamento de Logistica";
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                            'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }
                    if ($idEstatus === '36' && $flag === '1') {
                        $departamentoEspera = "Técnico";
                        $textoEspera = "Esperando información del Técnico";
                        $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                        if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                                'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                                'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                                'formularioEnvioSeguimientoLog' => $this->envioSeguimientoLogistica($datos),
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                        } else {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => [],
                                'formularioRecepcionAlmacen' => $this->vistaRefaccionEquipoUtilizadaAlmacen($datos),
                                'formularioRecepcionLab' => [],
                                'formularioHistorialRefaccion' => [],
                                'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                                'formularioEnvioSeguimientoLog' => $this->envioSeguimientoLogistica($datos),
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                        }
                    }
                    if ($idEstatus === '38' && $flag === '0') {
                        $departamentoEspera = "Logistica";
                        $textoEspera = "Esperando informacion del Departamento de Logistica";
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => [],
                            'formularioRecepcionAlmacen' => $this->vistaRefaccionEquipoUtilizadaAlmacen($datos),
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }
                    if ($idEstatus === '38' && $flag === '1') {
                        $departamentoEspera = "Técnico";
                        $textoEspera = "Esperando información del Técnico";
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => [],
                            'formularioRecepcionAlmacen' => $this->vistaRefaccionEquipoUtilizadaAlmacen($datos),
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }
                    break;
                case '38':
                case '56': //Laboratorio
                    if ($idEstatus === '2' && $flag === '0') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => [],
                            'formularioRecepcionAlmacen' => [],
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }
                    if ($idEstatus === '12' && $flag === '1') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }
                    if ($idEstatus === '28' && $flag === '1') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }
                    if ($idEstatus === '33' && $flag === '1') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => [],
                            'formularioRecepcionAlmacen' => [],
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }
                    if ($idEstatus === '29' && $flag === '1') {
                        $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                        if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                                'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                                'formularioRecepcionLog' => [],
                                'formularioEnvioSeguimientoLog' => [],
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => []);
                        } else {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => [],
                                'formularioRecepcionAlmacen' => [],
                                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                                'formularioRecepcionLog' => [],
                                'formularioEnvioSeguimientoLog' => [],
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => []);
                        }
                    }
                    if ($idEstatus === '2' && $flag === '1') {//falta historial
                        $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                        if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                                'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                                'formularioRecepcionLog' => [],
                                'formularioEnvioSeguimientoLog' => [],
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => []);
                        } else {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => [],
                                'formularioRecepcionAlmacen' => [],
                                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                                'formularioRecepcionLog' => [],
                                'formularioEnvioSeguimientoLog' => [],
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => []);
                        }
                    }
                    if ($idEstatus === '39' && $flag === '1') {
                        $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                        if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                            $departamentoEspera = "Logistica";
                            $textoEspera = "Esperando informacion del Departamento de Logistica";
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                                'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                                'formularioRecepcionLog' => [],
                                'formularioEnvioSeguimientoLog' => [],
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                        } else {
                            $departamentoEspera = "Técnico";
                            $textoEspera = "Esperando información del Técnico";
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => [],
                                'formularioRecepcionAlmacen' => [],
                                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                                'formularioRecepcionLog' => [],
                                'formularioEnvioSeguimientoLog' => [],
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                        }
                    }
                    if ($idEstatus === '34' && $flag === '1') {
                        $departamentoEspera = "Logistica";
                        $textoEspera = "Esperando informacion del Departamento de Logistica";
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }
                    if ($idEstatus === '30' && $flag === '1') {
                        $departamentoEspera = "Logistica";
                        $textoEspera = "Esperando informacion de envio del Departamento de Logistica";
                        $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                        if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                                'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                                'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                                'formularioEnvioSeguimientoLog' => [],
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                        } else {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => [],
                                'formularioRecepcionAlmacen' => $this->vistaRefaccionEquipoUtilizadaAlmacen($datos),
                                'formularioRecepcionLab' => [],
                                'formularioHistorialRefaccion' => [],
                                'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                                'formularioEnvioSeguimientoLog' => [],
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                        }
                    }
                    if ($idEstatus === '12' && $flag === '0') {
                        $departamentoEspera = "Logistica";
                        $textoEspera = "Esperando informacion de envio del Departamento de Logistica";
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                            'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }
                    if ($idEstatus === '36' && $flag === '1') {
                        $departamentoEspera = "Técnico";
                        $textoEspera = "Esperando información del Técnico";
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                            'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                            'formularioEnvioSeguimientoLog' => $this->envioSeguimientoLogistica($datos),
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }
                    break;
                case '41':
                case '52':
                case '60': // Logistica
                    if ($idEstatus === '37' && $flag === '1') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => $this->vistaDeGuia($datos),
                            'formularioEnvioAlmacen' => [],
                            'formularioRecepcionAlmacen' => [],
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }
                    if ($idEstatus === '26' && $flag === '1') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => $this->vistaDeGuia($datos),
                            'formularioEnvioAlmacen' => [],
                            'formularioRecepcionAlmacen' => [],
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }

                    if ($idEstatus === '27' && $flag === '1') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => $this->vistaDeGuia($datos),
                            'formularioEnvioAlmacen' => [],
                            'formularioRecepcionAlmacen' => [],
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }

                    if ($idEstatus === '12' && $flag === '1') {
                        $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                        if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                            $departamentoEspera = "Almacen";
                            $textoEspera = "Esperando informacion del Departamento de Almacen";
                        } else {
                            $departamentoEspera = "Laboratorio";
                            $textoEspera = "Esperando informacion del Departamenteo de Laboratorio";
                        }
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => [],
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                    }

                    if ($idEstatus === '39' && $flag === '1') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                            'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }

                    if ($idEstatus === '34' && $flag === '1') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                            'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                            'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                            'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                            'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }

                    if ($idEstatus === '30' && $flag === '1') {
                        $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                        if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                                'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                                'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                                'formularioEnvioSeguimientoLog' => $this->envioSeguimientoLogistica($datos),
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => []);
                        } else {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => [],
                                'formularioRecepcionAlmacen' => $this->vistaRefaccionEquipoUtilizadaAlmacen($datos),
                                'formularioRecepcionLab' => [],
                                'formularioHistorialRefaccion' => [],
                                'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                                'formularioEnvioSeguimientoLog' => $this->envioSeguimientoLogistica($datos),
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => []);
                        }
                    }

                    if ($idEstatus === '12' && $flag === '0') {
                        $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                        if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                                'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                                'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                                'formularioEnvioSeguimientoLog' => $this->envioSeguimientoLogistica($datos),
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => []);
                        }
                    }

                    if ($idEstatus === '36' && $flag === '1') {
                        $departamentoEspera = "Técnico";
                        $textoEspera = "Esperando información del Técnico";
                        $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                        if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                                'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                                'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                                'formularioEnvioSeguimientoLog' => $this->envioSeguimientoLogistica($datos),
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                        } else {
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => [],
                                'formularioRecepcionAlmacen' => $this->vistaRefaccionEquipoUtilizadaAlmacen($datos),
                                'formularioRecepcionLab' => [],
                                'formularioHistorialRefaccion' => [],
                                'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                                'formularioEnvioSeguimientoLog' => $this->envioSeguimientoLogistica($datos),
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera));
                        }
                    }

                    if ($idEstatus === '38' && $flag === '0') {
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => [],
                            'formularioRecepcionAlmacen' => $this->vistaRefaccionEquipoUtilizadaAlmacen($datos),
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => []);
                    }
                    break;
                default:
                    return "default";
            }
        }
    }

    public function formulariosTecnico(array $datos = null, string $idEstatus = null, string $flag = null, array $permisos, array $permisosAdicionales) {
        $usuario = $this->Usuario->getDatosUsuario();
        if ($idEstatus === '2' && $flag === '0') {
            $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
            switch ($equipoAllab[0]['IdTipoMovimiento']) {
                case '1':
                    return array('formularioValidacion' => $this->vistaValidacion($datos),
                        'formularioGuia' => [],
                        'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                        'formularioRecepcionAlmacen' => [],
                        'formularioRecepcionLab' => [],
                        'formularioHistorialRefaccion' => [],
                        'formularioRecepcionLog' => [],
                        'formularioEnvioSeguimientoLog' => [],
                        'formularioRecepcionTecnico' => [],
                        'PanelEspera' => [],
                        'permisos' => $permisos,
                        'permisosAdicionales' => $permisosAdicionales);
                    break;
                case '2':
                    $departamentoEspera = "Laboratorio";
                    $textoEspera = "Esperando información del Departamento de Laboratorio";
                    return array('formularioValidacion' => $this->vistaValidacion($datos),
                        'formularioGuia' => [],
                        'formularioEnvioAlmacen' => [],
                        'formularioRecepcionAlmacen' => [],
                        'formularioRecepcionLab' => [],
                        'formularioHistorialRefaccion' => [],
                        'formularioRecepcionLog' => [],
                        'formularioEnvioSeguimientoLog' => [],
                        'formularioRecepcionTecnico' => [],
                        'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                        'permisos' => $permisos,
                        'permisosAdicionales' => $permisosAdicionales);
                    break;
                case '3':
                    if (in_array('307', $usuario['PermisosAdicionales']) || in_array('307', $usuario['Permisos'])) {
                        $inventarioAlmacenesVirtuales = $this->verificarAlmacenesVirtuales($datos['idServicio']);

                        if (!empty($inventarioAlmacenesVirtuales)) {
                            $solicitudesRefaccion = $this->DBP->consultaEquiposAllabSolicitudesRefaccion($datos['idServicio']);

                            if ($solicitudesRefaccion[0]['IdEstatus'] === '9') {
                                return array('formularioValidacion' => $this->vistaValidacion($datos),
                                    'formularioGuia' => $this->vistaValidacionSupervisor($datos),
                                    'formularioEnvioAlmacen' => [],
                                    'formularioRecepcionAlmacen' => [],
                                    'formularioRecepcionLab' => [],
                                    'formularioHistorialRefaccion' => [],
                                    'formularioRecepcionLog' => [],
                                    'formularioEnvioSeguimientoLog' => [],
                                    'formularioRecepcionTecnico' => [],
                                    'PanelEspera' => [],
                                    'permisos' => $permisos,
                                    'permisosAdicionales' => $permisosAdicionales);
                            } else {
                                $departamentoEspera = "Almacén";
                                $textoEspera = "Esperando información de Almacén";
                                return array('formularioValidacion' => $this->vistaValidacion($datos),
                                    'formularioGuia' => [],
                                    'formularioEnvioAlmacen' => [],
                                    'formularioRecepcionAlmacen' => [],
                                    'formularioRecepcionLab' => [],
                                    'formularioHistorialRefaccion' => [],
                                    'formularioRecepcionLog' => [],
                                    'formularioEnvioSeguimientoLog' => [],
                                    'formularioRecepcionTecnico' => [],
                                    'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                                    'permisos' => $permisos,
                                    'permisosAdicionales' => $permisosAdicionales);
                            }
                        } else {
                            $departamentoEspera = "Almacén";
                            $textoEspera = "Esperando información de Almacén";
                            return array('formularioValidacion' => $this->vistaValidacion($datos),
                                'formularioGuia' => [],
                                'formularioEnvioAlmacen' => [],
                                'formularioRecepcionAlmacen' => [],
                                'formularioRecepcionLab' => [],
                                'formularioHistorialRefaccion' => [],
                                'formularioRecepcionLog' => [],
                                'formularioEnvioSeguimientoLog' => [],
                                'formularioRecepcionTecnico' => [],
                                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                                'permisos' => $permisos,
                                'permisosAdicionales' => $permisosAdicionales);
                        }
                    } else {
                        $solicitudesRefaccion = $this->DBP->consultaEquiposAllabSolicitudesRefaccion($datos['idServicio']);

                        if ($solicitudesRefaccion[0]['IdEstatus'] === '9') {
                            $departamentoEspera = "Supervisor";
                            $textoEspera = "Esperando información de su supervisor";
                        } else {
                            $departamentoEspera = "Almacén";
                            $textoEspera = "Esperando información de Almacén";
                        }
                        return array('formularioValidacion' => $this->vistaValidacion($datos),
                            'formularioGuia' => [],
                            'formularioEnvioAlmacen' => [],
                            'formularioRecepcionAlmacen' => [],
                            'formularioRecepcionLab' => [],
                            'formularioHistorialRefaccion' => [],
                            'formularioRecepcionLog' => [],
                            'formularioEnvioSeguimientoLog' => [],
                            'formularioRecepcionTecnico' => [],
                            'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                            'permisos' => $permisos,
                            'permisosAdicionales' => $permisosAdicionales);
                    }
                    break;
            }
        }

        if ($idEstatus === '26' && $flag === '1') {
            return array('formularioValidacion' => $this->vistaValidacion($datos),
                'formularioGuia' => $this->vistaDeGuia($datos),
                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                'formularioRecepcionAlmacen' => [],
                'formularioRecepcionLab' => [],
                'formularioHistorialRefaccion' => [],
                'formularioRecepcionLog' => [],
                'formularioEnvioSeguimientoLog' => [],
                'formularioRecepcionTecnico' => [],
                'PanelEspera' => [],
                'permisos' => $permisos,
                'permisosAdicionales' => $permisosAdicionales);
        }

        if ($idEstatus === '27' && $flag === '1') {
            return array('formularioValidacion' => $this->vistaValidacion($datos),
                'formularioGuia' => $this->vistaDeGuia($datos),
                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                'formularioRecepcionAlmacen' => [],
                'formularioRecepcionLab' => [],
                'formularioHistorialRefaccion' => [],
                'formularioRecepcionLog' => [],
                'formularioEnvioSeguimientoLog' => [],
                'formularioRecepcionTecnico' => [],
                'PanelEspera' => [],
                'permisos' => $permisos,
                'permisosAdicionales' => $permisosAdicionales);
        }

        if ($idEstatus === '37' && $flag === '1') {
            return array('formularioValidacion' => $this->vistaValidacion($datos),
                'formularioGuia' => $this->vistaDeGuia($datos),
                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                'formularioRecepcionAlmacen' => [],
                'formularioRecepcionLab' => [],
                'formularioHistorialRefaccion' => [],
                'formularioRecepcionLog' => [],
                'formularioEnvioSeguimientoLog' => [],
                'formularioRecepcionTecnico' => [],
                'PanelEspera' => [],
                'permisos' => $permisos,
                'permisosAdicionales' => $permisosAdicionales);
        }
        if ($idEstatus === '12' && $flag === '1') {
            $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
            if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                $departamentoEspera = "Almacén";
                $textoEspera = "Esperando informacion del Departamento de Almacén";
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                    'formularioRecepcionAlmacen' => [],
                    'formularioRecepcionLab' => [],
                    'formularioHistorialRefaccion' => [],
                    'formularioRecepcionLog' => [],
                    'formularioEnvioSeguimientoLog' => [],
                    'formularioRecepcionTecnico' => [],
                    'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            } else {
                $departamentoEspera = "Laboratorio";
                $textoEspera = "Esperando informacion del Departamenteo de Laboratorio";
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => [],
                    'formularioRecepcionAlmacen' => [],
                    'formularioRecepcionLab' => [],
                    'formularioHistorialRefaccion' => [],
                    'formularioRecepcionLog' => [],
                    'formularioEnvioSeguimientoLog' => [],
                    'formularioRecepcionTecnico' => [],
                    'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            }
        }

        if ($idEstatus === '32' && $flag === '1') {
            $departamentoEspera = "Almacén";
            $textoEspera = "Esperando informacion del Departamento de Almacén";
            $estatus = $datos['idEstatus'];
            return array('formularioValidacion' => $this->vistaValidacion($datos),
                'formularioGuia' => [],
                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                'formularioRecepcionAlmacen' => [],
                'formularioRecepcionLab' => [],
                'formularioHistorialRefaccion' => [],
                'formularioRecepcionLog' => [],
                'formularioEnvioSeguimientoLog' => [],
                'formularioRecepcionTecnico' => [],
                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                'permisos' => $permisos,
                'permisosAdicionales' => $permisosAdicionales);
        }

        if ($idEstatus === '28' && $flag === '1') {
            $departamentoEspera = "Laboratorio";
            $textoEspera = "Esperando informacion del Departamento de Laboratorio";
            $estatus = $datos['idEstatus'];
            return array('formularioValidacion' => $this->vistaValidacion($datos),
                'formularioGuia' => [],
                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                'formularioRecepcionLab' => [],
                'formularioHistorialRefaccion' => [],
                'formularioRecepcionLog' => [],
                'formularioEnvioSeguimientoLog' => [],
                'formularioRecepcionTecnico' => [],
                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                'permisos' => $permisos,
                'permisosAdicionales' => $permisosAdicionales);
        }

        if ($idEstatus === '33' && $flag === '1') {
            $departamentoEspera = "Laboratorio";
            $textoEspera = "Esperando informacion del Departamento de Laboratorio";
            $estatus = $datos['idEstatus'];

            $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);

            if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                    'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                    'formularioRecepcionLab' => [],
                    'formularioHistorialRefaccion' => [],
                    'formularioRecepcionLog' => [],
                    'formularioEnvioSeguimientoLog' => [],
                    'formularioRecepcionTecnico' => [],
                    'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            } else {
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => [],
                    'formularioRecepcionAlmacen' => [],
                    'formularioRecepcionLab' => [],
                    'formularioHistorialRefaccion' => [],
                    'formularioRecepcionLog' => [],
                    'formularioEnvioSeguimientoLog' => [],
                    'formularioRecepcionTecnico' => [],
                    'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            }
        }

        if ($idEstatus === '29' && $flag === '1') {
            $departamentoEspera = "Laboratorio";
            $textoEspera = "Esperando informacion del Departamento de Laboratorio (Historial y Refacción)";
            return array('formularioValidacion' => $this->vistaValidacion($datos),
                'formularioGuia' => [],
                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                'formularioHistorialRefaccion' => [],
                'formularioRecepcionLog' => [],
                'formularioEnvioSeguimientoLog' => [],
                'formularioRecepcionTecnico' => [],
                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                'permisos' => $permisos,
                'permisosAdicionales' => $permisosAdicionales);
        }

        if ($idEstatus === '2' && $flag === '1') {
            $departamentoEspera = "Laboratorio";
            $textoEspera = "Esperando informacion del Departamento de Laboratorio (Historial y Refacción)";

            $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);

            if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                    'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                    'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                    'formularioHistorialRefaccion' => [],
                    'formularioRecepcionLog' => [],
                    'formularioEnvioSeguimientoLog' => [],
                    'formularioRecepcionTecnico' => [],
                    'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            } else {
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => [],
                    'formularioRecepcionAlmacen' => [],
                    'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                    'formularioHistorialRefaccion' => [],
                    'formularioRecepcionLog' => [],
                    'formularioEnvioSeguimientoLog' => [],
                    'formularioRecepcionTecnico' => [],
                    'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            }
        }

        if ($idEstatus === '39' && $flag === '1') {
            $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
            if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                $departamentoEspera = "Logistica";
                $textoEspera = "Esperando informacion del Departamento de Logistica";
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                    'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                    'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                    'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                    'formularioRecepcionLog' => [],
                    'formularioEnvioSeguimientoLog' => [],
                    'formularioRecepcionTecnico' => [],
                    'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            } else {
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => [],
                    'formularioRecepcionAlmacen' => [],
                    'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                    'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                    'formularioRecepcionLog' => [],
                    'formularioEnvioSeguimientoLog' => [],
                    'formularioRecepcionTecnico' => $this->recepcionTecnico($datos),
                    'PanelEspera' => [],
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            }
        }

        if ($idEstatus === '35' && $flag === '1') {
            $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
            if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                $departamentoEspera = "Logistica";
                $textoEspera = "Esperando informacion del Departamento de Logistica";
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                    'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                    'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                    'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                    'formularioRecepcionLog' => [],
                    'formularioEnvioSeguimientoLog' => [],
                    'formularioRecepcionTecnico' => [],
                    'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            } else {
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => [],
                    'formularioRecepcionAlmacen' => [],
                    'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                    'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                    'formularioRecepcionLog' => [],
                    'formularioEnvioSeguimientoLog' => [],
                    'formularioRecepcionTecnico' => $this->recepcionTecnico($datos),
                    'PanelEspera' => [],
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            }
        }

        if ($idEstatus === '34' && $flag === '1') {
            $departamentoEspera = "Logistica";
            $textoEspera = "Esperando informacion del Departamento de Logistica";
            return array('formularioValidacion' => $this->vistaValidacion($datos),
                'formularioGuia' => [],
                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                'formularioRecepcionLog' => [],
                'formularioEnvioSeguimientoLog' => [],
                'formularioRecepcionTecnico' => [],
                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                'permisos' => $permisos,
                'permisosAdicionales' => $permisosAdicionales);
        }

        if ($idEstatus === '30' && $flag === '1') {
            $departamentoEspera = "Logistica";
            $textoEspera = "Esperando informacion de envio del Departamento de Logistica";
            $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
            if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                    'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                    'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                    'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                    'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                    'formularioEnvioSeguimientoLog' => [],
                    'formularioRecepcionTecnico' => [],
                    'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            } else {
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => [],
                    'formularioRecepcionAlmacen' => $this->vistaRefaccionEquipoUtilizadaAlmacen($datos),
                    'formularioRecepcionLab' => [],
                    'formularioHistorialRefaccion' => [],
                    'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                    'formularioEnvioSeguimientoLog' => [],
                    'formularioRecepcionTecnico' => [],
                    'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            }
        }

        if ($idEstatus === '12' && $flag === '0') {
            $departamentoEspera = "Logistica";
            $textoEspera = "Esperando informacion de envio del Departamento de Logistica";
            return array('formularioValidacion' => $this->vistaValidacion($datos),
                'formularioGuia' => [],
                'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                'formularioEnvioSeguimientoLog' => [],
                'formularioRecepcionTecnico' => [],
                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                'permisos' => $permisos,
                'permisosAdicionales' => $permisosAdicionales);
        }

        if ($idEstatus === '36' && $flag === '1') {
            $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);

            if ($equipoAllab[0]['IdTipoMovimiento'] === '1') {
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => $this->vistaEnvioAlmacen($datos),
                    'formularioRecepcionAlmacen' => $this->recepcionAlmacen($datos),
                    'formularioRecepcionLab' => $this->recepcionLaboratorio($datos),
                    'formularioHistorialRefaccion' => $this->revisionHistorial($datos),
                    'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                    'formularioEnvioSeguimientoLog' => $this->envioSeguimientoLogistica($datos),
                    'formularioRecepcionTecnico' => $this->recepcionTecnico($datos),
                    'PanelEspera' => [],
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            } else {
                return array('formularioValidacion' => $this->vistaValidacion($datos),
                    'formularioGuia' => [],
                    'formularioEnvioAlmacen' => [],
                    'formularioRecepcionAlmacen' => $this->vistaRefaccionEquipoUtilizadaAlmacen($datos),
                    'formularioRecepcionLab' => [],
                    'formularioHistorialRefaccion' => [],
                    'formularioRecepcionLog' => $this->recepcionLogistica($datos),
                    'formularioEnvioSeguimientoLog' => $this->envioSeguimientoLogistica($datos),
                    'formularioRecepcionTecnico' => $this->recepcionTecnico($datos),
                    'PanelEspera' => [],
                    'permisos' => $permisos,
                    'permisosAdicionales' => $permisosAdicionales);
            }
        }

        if ($idEstatus === '38' && $flag === '1') {
            return array('formularioValidacion' => $this->vistaValidacion($datos),
                'formularioGuia' => [],
                'formularioEnvioAlmacen' => [],
                'formularioRecepcionAlmacen' => $this->vistaRefaccionEquipoUtilizadaAlmacen($datos),
                'formularioRecepcionLab' => [],
                'formularioHistorialRefaccion' => [],
                'formularioRecepcionLog' => [],
                'formularioEnvioSeguimientoLog' => [],
                'formularioRecepcionTecnico' => $this->recepcionTecnico($datos),
                'PanelEspera' => [],
                'permisos' => $permisos,
                'permisosAdicionales' => $permisosAdicionales);
        }

        if ($idEstatus === '38' && $flag === '0') {
            $departamentoEspera = "Logistica";
            $textoEspera = "Esperando informacion del Departamento de Logistica";
            $estatus = $datos['idEstatus'];
            return array('formularioValidacion' => $this->vistaValidacion($datos),
                'formularioGuia' => [],
                'formularioEnvioAlmacen' => [],
                'formularioRecepcionAlmacen' => $this->vistaRefaccionEquipoUtilizadaAlmacen($datos),
                'formularioRecepcionLab' => [],
                'formularioHistorialRefaccion' => [],
                'formularioRecepcionLog' => [],
                'formularioEnvioSeguimientoLog' => [],
                'formularioRecepcionTecnico' => [],
                'PanelEspera' => $this->vistaEsperaInformacion($departamentoEspera, $textoEspera),
                'permisos' => $permisos,
                'permisosAdicionales' => $permisosAdicionales);
        }

        if (!$idEstatus) {
            return array('formularioValidacion' => $this->vistaValidacion($datos),
                'formularioGuia' => [],
                'formularioRecepcionTecnico' => [],
                'formularioEnvioAlmacen' => [],
                'formularioRecepcionAlmacen' => [],
                'formularioRecepcionLab' => [],
                'formularioHistorialRefaccion' => [],
                'formularioRecepcionLog' => [],
                'formularioEnvioSeguimientoLog' => [],
                'PanelEspera' => []);
        }
    }

    public function vistaEsperaInformacion(string $departamentoEspera, string $textoEspera) {
        $datosInfo['departamentoEspera'] = $departamentoEspera;
        $datosInfo['textoEspera'] = $textoEspera;
        return array('panelEspera' => parent::getCI()->load->view('Poliza/Modal/PanelEsperaInformacion', $datosInfo, TRUE));
    }

    public function vistaValidacion($datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $dataValidacion['ticketTecnico'] = $this->DBP->consultaTicketsUsuario(array('usuario' => $usuario['Id'], 'estatus' => '3'));
        $dataValidacion['datosValidacion'] = $this->DBP->consultaDatosValidacion($datos);
        $dataValidacion['tipoPerfiles'] = $this->DBP->mostrarTipoPersonaValida();
        $dataValidacion['listaEquipo'] = $this->DBP->mostrarEquipo();

        return array('formularioValidacion' => parent::getCI()->load->view('Poliza/Modal/1FormularioValidacionTecnico', $dataValidacion, TRUE));
    }

    public function vistaValidacionSupervisor(array $datos) {
        $data = array();

        $formulario = 'Poliza/Modal/10ValidacionSolicitudRefaccion';

        return array('formularioParaGuia' => parent::getCI()->load->view($formulario, $data, TRUE));
    }

    public function vistaSeguimientoSolicitudRefaccionEquipo(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $data = array();

        $equipoAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);

        if (empty($equipoAllab[0]['IdRefaccion'])) {
            $tipoProducto = '1';
        } else {
            $tipoProducto = '2';
        }

        $arrayInventarioAlmacen = array(
            'idUsuario' => $usuario['Id'],
            'tipoProducto' => $tipoProducto
        );

        $data['invetarioAlmacen'] = $this->DBP->consultaInventarioAlmacen($arrayInventarioAlmacen);

        $formulario = 'Poliza/Modal/12SeguimientoSolicitudRefaccionExistencia';

        return array('formularioParaGuia' => parent::getCI()->load->view($formulario, $data, TRUE));
    }

    public function vistaDeGuia(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();

        if ($usuario['IdPerfil'] === '41' || $usuario['IdPerfil'] === '52' || $usuario['IdPerfil'] === '60') {
            $dataSolicitudGuia['formularioEditable'] = TRUE;
        } else {
            $dataSolicitudGuia['formularioEditable'] = FALSE;
        }
        $dataSolicitudGuia['estatus'] = $this->DBP->estatusAllab($datos['idServicio']);
        $dataSolicitudGuia['datosSolicitudGuia'] = $this->DBP->consultaSolicitudGuiaTecnico($datos['idServicio']);
        return array('formularioParaGuia' => parent::getCI()->load->view('Poliza/Modal/2FormularioEnvioSinGuia', $dataSolicitudGuia, TRUE));
    }

    public function vistaEnvioAlmacen(array $datos) {
        $dataSolicitudGuia['estatus'] = $this->DBP->estatusAllab($datos['idServicio']);
        $dataSolicitudGuia['paqueterias'] = $this->DBP->mostrarPaqueterias();
        $dataSolicitudGuia['datosSolicitudGuia'] = $this->DBP->consultaSolicitudGuiaTecnico($datos['idServicio']);

        return array('formularioGuia' => parent::getCI()->load->view('Poliza/Modal/3FormularioEnvioConGuia', $dataSolicitudGuia, TRUE));
    }

    public function recepcionAlmacen(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $infoRecepcion = array('IdServicio' => $datos['idServicio'], 'IdDepartamento' => 1, 'IdEstatus' => 28);
        $datosRecepcionAlmacen['datosRecepcion'] = $this->DBP->consultaRecepcionAlmacen($infoRecepcion);
        $datosRecepcionAlmacen['usuario'] = $usuario['Nombre'];

        $formulario = array('formularioRecepcionAlmacen' => parent::getCI()->load->view('Poliza/Modal/4FormularioRecepcionAlmacen', $datosRecepcionAlmacen, TRUE));
        return $formulario;
    }

    public function recepcionLaboratorio(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $infoRecepcion = array('IdServicio' => $datos['idServicio'], 'IdDepartamento' => 2, 'IdEstatus' => 29);
        $datosRecepcionAlmacen['datosRecepcion'] = $this->DBP->consultaRecepcionAlmacen($infoRecepcion);
        $datosRecepcionAlmacen['usuario'] = $usuario['Nombre'];

        $formulario = array('formularioRecepcionLaboratorio' => parent::getCI()->load->view('Poliza/Modal/5FormularioRecepcionLaboratorio', $datosRecepcionAlmacen, TRUE));
        return $formulario;
    }

    public function revisionHistorial(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $data = [];

        $equipoRegistro = $this->DBP->consultaEquiposAllab($datos['idServicio']);
        $data['componentesEquipo'] = $this->DBP->consultaComponentesEquipoInvetario(array(
            'idModelo' => $equipoRegistro[0]['IdModelo'],
            'idUsuario' => $usuario['Id']));
        $data['listRefaccionesUtilizadasServicio'] = $this->DBP->consultaListaRefaccionesUtilizadasServicio($datos['idServicio']);

        $formulario = array('formularioRevisionHistorial' => parent::getCI()->load->view('Poliza/Modal/6FormularioRevisionHistorial', $data, TRUE));
        return $formulario;
    }

    public function recepcionLogistica(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $infoRecepcion = array('IdServicio' => $datos['idServicio'], 'IdDepartamento' => 3, 'IdEstatus' => 30);
        $datosRecepcionAlmacen['datosRecepcion'] = $this->DBP->consultaRecepcionAlmacen($infoRecepcion);
        $datosRecepcionAlmacen['usuario'] = $usuario['Nombre'];

        $formulario = array('formularioRecepcionLogistica' => parent::getCI()->load->view('Poliza/Modal/7FormularioRecepcionLogistica', $datosRecepcionAlmacen, TRUE));
        return $formulario;
    }

    public function envioSeguimientoLogistica(array $datos) {
        $informacion = array('IdServicio' => $datos['idServicio']);
        $datosEnvioLogistica['dondeRecibe'] = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM cat_v3_equipos_allab_tipo_lugar_recepcion WHERE Flag = "1"');
        $datosEnvioLogistica['paqueterias'] = $this->DBP->mostrarPaqueterias();
        $datosEnvioLogistica['sucursales'] = $this->Catalogo->catSucursales('3', array('Flag' => '1'));
        $datosEnvioLogistica['informacionEnvioLog'] = $this->DBP->consultaEnvioLogistica($informacion);

        if (!empty($datosEnvioLogistica)) {
            $formulario = array('formularioEnvioSeguimientoLog' => parent::getCI()->load->view('Poliza/Modal/8FormularioEnvioSeguimientoLogistica', $datosEnvioLogistica, TRUE));
            return $formulario;
        }
    }

    public function recepcionTecnico(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $infoRecepcion = array('IdServicio' => $datos['idServicio'], 'IdDepartamento' => 4, 'IdEstatus' => 36);
        $datosRecepcionAlmacen['datosRecepcion'] = $this->DBP->consultaRecepcionAlmacen($infoRecepcion);
        $datosRecepcionAlmacen['usuario'] = $usuario['Nombre'];

        $formulario = array('formularioRecepcionTecnico' => parent::getCI()->load->view('Poliza/Modal/9FormularioRecepcionTecnica', $datosRecepcionAlmacen, TRUE));
        return $formulario;
    }

    public function vistaRefaccionEquipoUtilizadaAlmacen(array $datos) {
        $data = array();
        $data['refaccionEquipoUtilizadoAlmacen'] = $this->DBP->consultaRefaccionEquipoUtilizadoAlmacen($datos);
        return array('formularioRecepcionAlmacen' => parent::getCI()->load->view('Poliza/Modal/13SeguimientoSolicitudRefaccionAlmacen', $data, TRUE));
    }

    public function agregarComentarioSeguimientosEquipos(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = $result = null;
        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['idServicio'] . '/solicitudesEquipo/Solicitud_' . $datos['id'] . '/Adjuntos/';
        $archivos = "";
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'archivosLabHistorial', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $datos['idUsuario'] = $usuario['Id'];
        $datos['archivos'] = $archivos;
        $datos['fecha'] = $fecha;

        $resultado = $this->DBP->insertarEquiposAllabRevicionLaboratorioHistorial($datos);

        return $resultado;
    }

    public function cargaComentariosAdjuntos(array $data) {
        $notas = $this->DBP->consultaComentariosAdjuntosSolicitudEquipo($data['id']);

        $datos = [
            'notas' => $notas,
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Tareas/Formularios/NotasAdjuntos', $datos, TRUE)
        ];
    }

    public function agregarRecepcionesProblemasSeguimientosEquipos(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = $result = null;
        $CI = parent::getCI();

        switch ($datos['tipoProblema']) {
            case 'almacen':
                $idDepartamento = '1';
                $idEstatus = '28';
                $nombreCarpeta = 'Almacen';
                $nombreInputArchivo = 'adjuntosProblemaAlm';
                $idEstatusProblema = '32';
                break;
            case 'laboratorio':
                $idDepartamento = '2';
                $idEstatus = '29';
                $nombreCarpeta = 'Laboratorio';
                $nombreInputArchivo = 'adjuntosProblemaLab';
                $idEstatusProblema = '33';
                break;
            case 'logistica':
                $idDepartamento = '3';
                $idEstatus = '30';
                $nombreCarpeta = 'Logistica';
                $nombreInputArchivo = 'adjuntosProblemaLog';
                $idEstatusProblema = '34';
                break;
            case 'tecnico':
                $idDepartamento = '4';
                $idEstatus = '31';
                $nombreCarpeta = 'Tecnico';
                $nombreInputArchivo = 'adjuntosProblemaTec';
                $idEstatusProblema = '35';
                break;
        }


        $carpeta = 'Servicios/Servicio-' . $datos['idServicio'] . '/solicitudesEquipo/Solicitud_' . $datos['id'] . '/RecepcionesProblemas/' . $nombreCarpeta . '/';
        $archivos = "";
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, $nombreInputArchivo, $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $datos['idUsuario'] = $usuario['Id'];
        $datos['fecha'] = $fecha;

        $idRecepcion = $this->DBP->consultaIdRegistro(array(
            'idRegistro' => $datos['id'],
            'idDepartamento' => $idDepartamento));

        if (empty($idRecepcion)) {
            $datos['idDepartamento'] = $idDepartamento;
            $datos['idEstatus'] = $idEstatusProblema;
            $datos['archivos'] = NULL;
            $resultado = $this->DBP->insertarEquiposAllabRecpciones($datos);
            $idRecepcion = $this->DBP->consultaIdRegistro(array(
                'idRegistro' => $datos['id'],
                'idDepartamento' => $idDepartamento));
        }
        $datos['archivos'] = $archivos;

        $datos['idRecepcion'] = $idRecepcion[0]['Id'];

        $datos['idEstatus'] = $idEstatusProblema;
        $datos['flag'] = '1';
        $resultado = $this->DBP->insertarEquiposAllabRecepcionesProblemas($datos);

        if ($resultado['code'] === 200) {
            return [
                'code' => 200,
                'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla()
            ];
        } else {
            return [
                'code' => 400
            ];
        }
    }

    public function cargaRecepcionesProblemas(array $data) {
        $notas = $this->DBP->consultaRecepcionesProblemasSolicitudEquipo($data);

        $datos = [
            'notas' => $notas,
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Tareas/Formularios/NotasAdjuntos', $datos, TRUE)
        ];
    }

    public function guardarRecepcionTecnico(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = $result = null;
        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['idServicio'] . '/solicitudesEquipo/Solicitud_' . $datos['id'] . '/RecepcionTecnico/';
        $archivos = "";
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'evidenciaRecepcionTecnico', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $datos['idEstatus'] = '31';
        $datos['idUsuario'] = $usuario['Id'];
        $datos['idDepartamento'] = '4';
        $datos['archivos'] = $archivos;
        $datos['fecha'] = $fecha;
        $datos['flag'] = '1';

        $idRecepcion = $this->DBP->consultaIdRegistro(array(
            'idRegistro' => $datos['id'],
            'idDepartamento' => $datos['idDepartamento']));

        if (empty($idRecepcion)) {
            $resultado = $this->DBP->insertarEquiposAllabRecepcionesCambiarEstatus($datos);
        } else {
            $resultado = $this->DBP->actualizarEquiposAllabRecepciones($datos);
        }

        if ($resultado['code'] === 200) {
            $datosAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
            $textoCorreo = '<p>Se ha concluido la solicitud de equipo del servicio: <strong>' . $datos['idServicio'] . '</strong>.</p>';
            $dataEmailProfiles = $this->creationOfTeamRequestEmailList(array('idStatus' => 31, 'movementType' => $datosAllab[0]['IdTipoMovimiento'], 'idTechnical' => $datosAllab[0]['IdUsuario']));
            $this->enviarCorreoConcluido($dataEmailProfiles, 'Seguimiento solicitud de equipo', $textoCorreo);
            $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => $datos['idEstatus']));
            $mensaje = ['mensaje' => "Correcto",
                'datos' => $formularios,
                'idServicio' => $datos['idServicio'],
                'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                'code' => 200];
            return $mensaje;
        } else {
            $mensaje = ['mensaje' => "Hay un problema con la información",
                'code' => 400];
            return $mensaje;
        }
    }

    public function guardarRecepcionLogistica(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();

        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = $result = null;
        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['idServicio'] . '/solicitudesEquipo/Solicitud_' . $datos['id'] . '/RecepcionLogistica/';
        $archivos = "";
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'evidenciaRecepcionLogistica', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $datos['idUsuario'] = $usuario['Id'];
        $datos['idDepartamento'] = '3';
        $datos['idEstatus'] = '30';
        $datos['archivos'] = $archivos;
        $datos['fecha'] = $fecha;
        $datos['flag'] = '1';

        $idRecepcion = $this->DBP->consultaIdRegistro(array(
            'idRegistro' => $datos['id'],
            'idDepartamento' => $datos['idDepartamento']));

        if (empty($idRecepcion)) {
            $resultado = $this->DBP->insertarEquiposAllabRecepcionesCambiarEstatus($datos);
        } else {
            $resultado = $this->DBP->actualizarEquiposAllabRecepciones($datos);
        }

        if ($resultado['code'] === 200) {
            $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => $datos['idEstatus']));
            $mensaje = ['mensaje' => "Correcto",
                'datos' => $formularios,
                'idServicio' => $datos['idServicio'],
                'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                'code' => 200];
            return $mensaje;
        } else {
            $mensaje = ['mensaje' => "Hay un problema con la información",
                'code' => 400];
            return $mensaje;
        }
    }

    public function guardarRecepcionAlmacen(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = $result = null;
        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['idServicio'] . '/solicitudesEquipo/Solicitud_' . $datos['id'] . '/RecepcionAlmacen/';
        $archivos = "";
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'evidenciaRecepcionAlmacen', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $datos['idUsuario'] = $usuario['Id'];
        $datos['idDepartamento'] = '1';
        $datos['idEstatus'] = '28';
        $datos['archivos'] = $archivos;
        $datos['fecha'] = $fecha;
        $datos['flag'] = '1';

        $idRecepcion = $this->DBP->consultaIdRegistro(array(
            'idRegistro' => $datos['id'],
            'idDepartamento' => $datos['idDepartamento']));

        if (empty($idRecepcion)) {
            $resultado = $this->DBP->insertarEquiposAllabRecepcionesCambiarEstatus($datos);
        } else {
            $resultado = $this->DBP->actualizarEquiposAllabRecepciones($datos);
        }

        if ($resultado['code'] === 200) {
            $datosAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
            $textoCorreo = '<p>Se le pide que le dé seguimiento a la solicitud de equipo del servicio: <strong>' . $datos['idServicio'] . '</strong>.</p>';
            $dataEmailProfiles = $this->creationOfTeamRequestEmailList(array('idStatus' => 28, 'movementType' => $datosAllab[0]['IdTipoMovimiento'], 'idTechnical' => $datosAllab[0]['IdUsuario']));
            $this->enviarCorreoConcluido($dataEmailProfiles, 'Seguimiento solicitud de equipo', $textoCorreo);
            $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => $datos['idEstatus']));
            $mensaje = ['mensaje' => "Correcto",
                'datos' => $formularios,
                'idServicio' => $datos['idServicio'],
                'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                'code' => 200];
            return $mensaje;
        } else {
            $mensaje = ['mensaje' => "Hay un problema con la información",
                'code' => 400];
            return $mensaje;
        }
    }

    public function guardarRecepcionLaboratorio(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = $result = null;
        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['idServicio'] . '/solicitudesEquipo/Solicitud_' . $datos['id'] . '/RecepcionLaboratorio/';
        $archivos = "";
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'evidenciaRecepcionLab', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $datos['idUsuario'] = $usuario['Id'];
        $datos['idDepartamento'] = '2';
        $datos['idEstatus'] = '29';
        $datos['archivos'] = $archivos;
        $datos['fecha'] = $fecha;
        $datos['flag'] = '1';

        $idRecepcion = $this->DBP->consultaIdRegistro(array(
            'idRegistro' => $datos['id'],
            'idDepartamento' => $datos['idDepartamento']));

        if (empty($idRecepcion)) {
            $resultado = $this->DBP->insertarEquiposAllabRecepcionesCambiarEstatus($datos);
        } else {
            $resultado = $this->DBP->actualizarEquiposAllabRecepciones($datos);
        }

        if ($resultado['code'] === 200) {
            $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => $datos['idEstatus']));
            $mensaje = ['mensaje' => "Correcto",
                'datos' => $formularios,
                'idServicio' => $datos['idServicio'],
                'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                'code' => 200];
            return $mensaje;
        } else {
            $mensaje = ['mensaje' => "Hay un problema con la información",
                'code' => 400];
            return $mensaje;
        }
    }

    public function consultaServiciosTecnico(array $datos) {
        $resultado = $this->DBP->consultaServiciosUsuario($datos);
        if (!empty($resultado)) {
            return $resultado;
        } else {
            return FALSE;
        }
    }

    public function mostrarNombrePersonalValida(array$datos) {
        $nombrePersonal = $this->DBP->mostrarNombrePersonalValida($datos['idTipoPersonal']);
        return $nombrePersonal;
    }

    public function mostrarRefaccionXEquipo(array $datos) {
        $refaccion = $this->DBP->mostrarRefaccionXEquipo($datos['idEquipo']);
        if (!empty($refaccion)) {
            return $refaccion;
        } else {
            return false;
        }
    }

    public function guardarValidacionTecnico(array $datos) {
        $idServicio = $datos['IdServicio'];
        $equipoAllab = $this->DBP->consultaEquiposAllab($idServicio);

        if (!empty($equipoAllab)) {
            $mensaje = ['mensaje' => "Ya existe una solicitud para este servicio",
                'code' => 500];
            return $mensaje;
        } else {
            $nuevaValidacion = $this->DBP->insertarValidacionTecnico($datos);
            if ($nuevaValidacion) {
                $equipoAllabNuevo = $this->DBP->consultaEquiposAllab($idServicio);
                $datosAllab = $this->DBP->consultaEquiposAllab($datos['IdServicio']);

                if ($datos['IdTipoMovimiento'] === '3') {
                    $inventarioAlmacenesVirtuales = $this->verificarAlmacenesVirtuales($idServicio);

                    if (!empty($inventarioAlmacenesVirtuales)) {
                        $estatusSolicitudRefaccion = '9';
                    } else {
                        $estatusSolicitudRefaccion = '7';
                    }

                    $usuario = $this->Usuario->getDatosUsuario();
                    $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

                    $arraySolicitudRefaccion = array(
                        'id' => $equipoAllabNuevo[0]['Id'],
                        'idUsuario' => $usuario['Id'],
                        'estatus' => $estatusSolicitudRefaccion,
                        'fechaEstatus' => $fecha
                    );

                    $this->DBP->insertarEquiposAllabSolicitudRefaccion($arraySolicitudRefaccion);

                    $textoCorreo = '<p>Se le pide que le dé seguimiento a la solicitud de equipo del servicio: <strong>' . $datos['IdServicio'] . '</strong>.</p>';
                    $dataEmails = $this->creatingSupervisorAndTechnicalEmailList(array('idTechnical' => $datosAllab[0]['IdUsuario']));
                    $this->enviarCorreoConcluido($dataEmails, 'Seguimiento solicitud de equipo', $textoCorreo);

                    $dataEmailProfiles = $this->creationOfTeamRequestEmailList(array('idStatus' => 2, 'movementType' => $datosAllab[0]['IdTipoMovimiento'], 'idTechnical' => $datosAllab[0]['IdUsuario']));
                    $this->enviarCorreoConcluido($dataEmailProfiles, 'Seguimiento solicitud de equipo', $textoCorreo);
                }

                if ($datosAllab[0]['IdTipoMovimiento'] === '2') {
                    $textoCorreo = '<p>Se le pide que le dé seguimiento a la solicitud de equipo del servicio: <strong>' . $datos['IdServicio'] . '</strong>.</p>';
                    $dataEmailProfiles = $this->creationOfTeamRequestEmailList(array('idStatus' => 2, 'movementType' => $datosAllab[0]['IdTipoMovimiento'], 'idTechnical' => $datosAllab[0]['IdUsuario']));
                    $this->enviarCorreoConcluido($dataEmailProfiles, 'Seguimiento solicitud de equipo', $textoCorreo);
                }

                $formulario = $this->mostrarVistaPorUsuario(array('idServicio' => $idServicio, 'idEstatus' => 2));
                $mensaje = ['mensaje' => "Se ha registrado un nuevo seguimiento",
                    'datos' => $formulario,
                    'idTabla' => $equipoAllabNuevo[0]['Id'],
                    'idServicio' => $idServicio,
                    'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                    'code' => 400];
                return $mensaje;
            } else {
                $mensaje = ['mensaje' => "Hay un problema al insertar la información",
                    'code' => 500];
                return $mensaje;
            }
        }
    }

    public function verificarAlmacenesVirtuales(string $idServicio) {
        $arrayEquiposAllab = $this->DBP->consultaEquiposAllab($idServicio);

        if (!empty($arrayEquiposAllab[0]['IdRefaccion'])) {
            $producto = $arrayEquiposAllab[0]['IdRefaccion'];
            $tipoProducto = '2';
        } else {
            $producto = $arrayEquiposAllab[0]['IdModelo'];
            $tipoProducto = '1';
        }

        $arrayValidacion = array(
            'idUsuario' => $arrayEquiposAllab[0]['IdUsuario'],
            'producto' => $producto,
            'tipoProducto' => $tipoProducto
        );

        $inventarioAlmacenesVirtuales = $this->DBP->consultaInventarioAlmacenesVirtuales($arrayValidacion);

        return $inventarioAlmacenesVirtuales;
    }

    public function mostrarEquipoDanado($idModelo) {
        $equipoDanado = $this->DBP->mostrarEquipoDanado($idModelo['idModelo']);
        return $equipoDanado;
    }

    public function guardarEnvioAlmacen(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $idAllab = $this->DBP->estatusAllab($datos['idServicio']);
        $datosAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);

        $info = array(
            'IdRegistro' => $idAllab['Id'],
            'IdUsuario' => $usuario['Id'],
            'IdEstatusEnvio' => 12,
            'IdPaqueteria' => $datos['IdPaqueteria'],
            'Guia' => $datos['Guia'],
            'Fecha' => $datos['Fecha'],
            'ArchivosEnvio' => null,
            'Solicitud' => $datosAllab[0]['IdTipoMovimiento'],
            'IdUsuarioSolicitud' => null,
            'IdEstatusSolicitud' => null,
            'FechaEstatusSolicitud' => null,
            'ArchivosSolicitud' => null
        );

        $textoCorreo = '<p>Se le pide que le dé seguimiento a la solicitud de equipo del servicio: <strong>' . $datos['idServicio'] . '</strong>.</p>';
        $dataEmailProfiles = $this->creationOfTeamRequestEmailList(array('idStatus' => 12, 'movementType' => $datosAllab[0]['IdTipoMovimiento'], 'idTechnical' => $datosAllab[0]['IdUsuario']));

        $datosEstatus = array(
            'idEstatus' => 12,
            'id' => $idAllab['Id'],
            'fecha' => $datos['Fecha'],
            'flag' => '1');

        if (!empty($_FILES)) {
            $CI = parent::getCI();
            $carpeta = 'Servicios/Servicio-' . $datos['idServicio'] . '/EvidenciasEquipo/';
            $archivos = implode(',', setMultiplesArchivos($CI, 'evidenciaEnvioGuia', $carpeta));

            if (!empty($archivos) && $archivos != '') {
                $info['ArchivosEnvio'] = $archivos;
                $insertar = $this->DBP->insertarEnvioGuia($info, $datosEstatus);
                if ($insertar['code'] === 200) {
                    $this->enviarCorreoConcluido($dataEmailProfiles, 'Seguimiento solicitud de equipo', $textoCorreo);
                    $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => 12));
                    $mensaje = ['mensaje' => "Se ha registrado un nuevo seguimiento",
                        'datos' => $formularios,
                        'idTabla' => $idAllab['Id'],
                        'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                        'code' => 200];
                    return $mensaje;
                } else {
                    return $insertar;
                }
            }
        } else {
            $insertar = $this->DBP->insertarEnvioGuia($info, $datosEstatus);
            if ($insertar['code'] === 200) {
                $this->enviarCorreoConcluido($dataEmailProfiles, 'Seguimiento solicitud de equipo', $textoCorreo);
                $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => 12));
                $mensaje = ['mensaje' => "Se ha registrado un nuevo seguimiento",
                    'datos' => $formularios,
                    'idTabla' => $idAllab['Id'],
                    'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                    'code' => 200];
                return $mensaje;
            } else {
                return $insertar;
            }
        }
    }

    public function guardarRefacionUtilizada(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $revisionLaboratorio = $this->DBP->consultaEquiposAllabRevicionLaboratorio($datos);

        $datos['idUsuario'] = $usuario['Id'];
        $datos['fecha'] = $fecha;

        if (!empty($revisionLaboratorio)) {
            $idRevision = $revisionLaboratorio[0]['Id'];
        } else {
            $idRevision = $this->DBP->insertarEquiposAllabRevicionLaboratorio($datos);
        }

        if (!empty($idRevision)) {
            $datos['idRevision'] = $idRevision;
            $datos['cantidad'] = $datos['cantidad'];
            $datos['flag'] = '1';
            $datos['idEstatus'] = '2';
            $resultado = $this->DBP->laboratorioRefacciones($datos);
            if ($resultado['code'] === 200) {
                $listaRefacciones = $this->DBP->consultaListaRefaccionesUtilizadasServicio($datos['idServicio']);
                $equipoRegistro = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                $componentesEquipo = $this->DBP->consultaComponentesEquipoInvetario(array(
                    'idModelo' => $equipoRegistro[0]['IdModelo'],
                    'idUsuario' => $usuario['Id']));
                $mensaje = ['mensaje' => "Se ha registrado correctamente",
                    'datos' => $listaRefacciones,
                    'componentesEquipo' => $componentesEquipo,
                    'code' => 200];
                return $mensaje;
            } else {
                $mensaje = ['mensaje' => "Hay un problema con la información",
                    'code' => 400];
                return $mensaje;
            }
        } else {
            $mensaje = ['mensaje' => "Hay un problema con la información",
                'code' => 400];
            return $mensaje;
        }
    }

    public function eliminarRefacionUtilizada(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $datos['flag'] = '0';
        $resultado = $this->DBP->flagearRefaccionUtilizada($datos);

        if (!empty($resultado)) {
            $listaRefacciones = $this->DBP->consultaListaRefaccionesUtilizadasServicio($datos['idServicio']);
            $equipoRegistro = $this->DBP->consultaEquiposAllab($datos['idServicio']);
            $componentesEquipo = $this->DBP->consultaComponentesEquipoInvetario(array(
                'idModelo' => $equipoRegistro[0]['IdModelo'],
                'idUsuario' => $usuario['Id']));
            $mensaje = ['mensaje' => "Se ha eliminado la refacción correctamente.",
                'datos' => $listaRefacciones,
                'componentesEquipo' => $componentesEquipo,
                'code' => 200];
            return $mensaje;
        } else {
            $mensaje = ['mensaje' => "Hay un problema con la información.",
                'code' => 400];
            return $mensaje;
        }
    }

    public function concluirRevicionLaboratorio(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $historialRegistro = $this->DBP->consultaHistorialRegistro($datos);
        $datosAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);

        if (!empty($historialRegistro)) {
            $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
            $datos['idEstatus'] = '39';
            $datos['flag'] = '1';
            $datos['fecha'] = $fecha;
            $datos['idUsuario'] = $usuario['Id'];

            $resultado = $this->DBP->actualizarEquiposAllabRevicionLaboratorio($datos);
            if ($resultado['code'] === 200) {
                $textoCorreo = '<p>Se le pide que le dé seguimiento a la solicitud de equipo del servicio: <strong>' . $datos['idServicio'] . '</strong>.</p>';
                $dataEmailProfiles = $this->creationOfTeamRequestEmailList(array('idStatus' => 39, 'movementType' => $datosAllab[0]['IdTipoMovimiento'], 'idTechnical' => $datosAllab[0]['IdUsuario']));
                $this->enviarCorreoConcluido($dataEmailProfiles, 'Seguimiento solicitud de equipo', $textoCorreo);
                $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => 39));
                $mensaje = ['mensaje' => "Se ha concluido correctamente.",
                    'datos' => $formularios,
                    'idServicio' => $datos['idServicio'],
                    'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                    'code' => 200];
                return $mensaje;
            } else {
                $mensaje = ['mensaje' => "Se produjo un error.",
                    'code' => 400];
                return $mensaje;
            }
        } else {
            $mensaje = ['mensaje' => "No hay registro de Historial.",
                'code' => 400];
            return $mensaje;
        }
    }

    public function guardarEnvioLogistica(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $datosAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
        $estatusAllab = 12;


        $datosInsertar = array(
            'IdRegistro' => $datos['id'],
            'IdUsuario' => $usuario['Id'],
            'IdEstatus' => $estatusAllab,
            'FechaEstatus' => $fecha,
            'IdPaqueteria' => $datos['paqueteria'],
            'Guia' => $datos['guia'],
            'FechaEnvio' => $datos['fechaEnvio'],
            'ArchivosEnvio' => null,
            'IdTipoLugarRecepcion' => null,
            'IdSucursal' => null,
            'FechaRecepcion' => null,
            'Recibe' => null,
            'ArchivosEntrega' => null
        );

        $datosEstatus = array(
            'idEstatus' => $estatusAllab,
            'id' => $datos['id'],
            'fecha' => $fecha,
            'flag' => '0');

        $correoTecnico = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                    (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = IdUsuario) CorreoTecnico,
                                                                    nombreUsuario(IdUsuario) Tecnico
                                                                FROM
                                                                    t_equipos_allab
                                                                WHERE Id = "' . $datos['id'] . '"');

        $arrayCorreos = array();

        foreach ($correoTecnico as $key => $value) {
            array_push($arrayCorreos, $value['CorreoTecnico']);
        }

        $textoCorreo = '<p><strong>' . $correoTecnico[0]['Tecnico'] . '</strong> esta en transito el equipo del servicio: <strong>' . $datos['idServicio'] . '</strong>.</p>';

        if (!empty($_FILES)) {
            $CI = parent::getCI();
            $carpeta = 'Servicios/Servicio-' . $datos['idServicio'] . '/EvidenciasEnvioLogistica/';
            $archivos = implode(',', setMultiplesArchivos($CI, 'evidenciaEnvio', $carpeta));

            if (!empty($archivos) && $archivos != '') {
                $datosInsertar['ArchivosEnvio'] = $archivos;
                $insertar = $this->DBP->insertarEnvioLogistica($datosInsertar, $datosEstatus);
                if ($insertar['code'] === 200) {
                    $this->enviarCorreoConcluido($arrayCorreos, 'Seguimiento solicitud de equipo', $textoCorreo);

                    $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => 39));
                    $mensaje = ['mensaje' => "Se ha concluido correctamente.",
                        'datos' => $formularios,
                        'idServicio' => $datos['idServicio'],
                        'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                        'tipoSolicitud' => $datosAllab[0]['IdTipoMovimiento'],
                        'code' => 200];
                    return $mensaje;
                } else {
                    $mensaje = ['mensaje' => $insertar,
                        'code' => 400];
                    return $mensaje;
                }
            }
        } else {
            $insertar = $this->DBP->insertarEnvioLogistica($datosInsertar, $datosEstatus);
            if ($insertar['code'] === 200) {
                $this->enviarCorreoConcluido($arrayCorreos, 'Seguimiento solicitud de equipo', $textoCorreo);

                $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => 39));
                $mensaje = ['mensaje' => "Se ha concluido correctamente.",
                    'datos' => $formularios,
                    'idServicio' => $datos['idServicio'],
                    'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                    'tipoSolicitud' => $datosAllab[0]['IdTipoMovimiento'],
                    'code' => 200];
                return $mensaje;
            } else {
                $mensaje = ['mensaje' => $insertar,
                    'code' => 400];
                return $mensaje;
            }
        }
    }

    public function guardarEntregaLogistica(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $datosActualizar = array(
            'IdEstatus' => 36,
            'FechaEstatus' => $fecha,
            'IdTipoLugarRecepcion' => $datos['tipoLugarRecepcion'],
            'IdSucursal' => $datos['sucursal'],
            'FechaRecepcion' => $datos['fechaRecepcion'],
            'Recibe' => $datos['recibe'],
            'ArchivosEntrega' => null
        );

        $datosEstatus = array(
            'idEstatus' => 36,
            'id' => $datos['id'],
            'fecha' => $fecha,
            'flag' => '1');

        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['idServicio'] . '/EvidenciasEntregaLogistica/';
        $archivos = implode(',', setMultiplesArchivos($CI, 'evidenciaEntregaLog', $carpeta));

        if (!empty($archivos) && $archivos != '') {
            $datosActualizar['ArchivosEntrega'] = $archivos;
            $resultado = $this->DBP->actualizarEnvioLogistica($datosActualizar, $datosEstatus);
            if ($resultado['code'] === 200) {
                $datosAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                $textoCorreo = '<p>Se le pide que le dé seguimiento a la solicitud de equipo del servicio: <strong>' . $datos['idServicio'] . '</strong>.</p>';
                $dataEmailProfiles = $this->creationOfTeamRequestEmailList(array('idStatus' => 36, 'movementType' => $datosAllab[0]['IdTipoMovimiento'], 'idTechnical' => $datosAllab[0]['IdUsuario']));
                $this->enviarCorreoConcluido($dataEmailProfiles, 'Seguimiento solicitud de equipo', $textoCorreo);
                $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => 12));
                $mensaje = ['mensaje' => "Se guardo correctamente la entrega.",
                    'datos' => $formularios,
                    'idTabla' => $datos['id'],
                    'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                    'code' => 200];
                return $mensaje;
            } else {
                $mensaje = ['mensaje' => "Se ha producido un error en la entrega.",
                    'code' => 400];
                return $mensaje;
            }
        }
    }

    public function guardarProblemaGuiaLogistica(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $archivos = $result = null;
        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['idServicio'] . '/solicitudesEquipo/Solicitud_' . $datos['id'] . '/DocumentacionGuia/';
        $archivos = "";
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'archivosProblemaGuia', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $idRegistro = $this->DBP->consultaSolicitudGuiaTecnico($datos['idServicio']);

        if ($datos['idEstatus'] === '37') {
            $estatusRegistro = '4';
        } else {
            $estatusRegistro = $datos['idEstatus'];
        }

        $datosRegistro = array(
            'IdRegistro' => $datos['id'],
            'IdUsuario' => $usuario['Id'],
            'IdEstatusEnvio' => $estatusRegistro,
            'Fecha' => $fecha,
            'ArchivosEnvio' => null,
            'Solicitud' => 1,
            'IdUsuarioSolicitud' => null,
            'IdEstatusSolicitud' => null,
            'FechaEstatusSolicitud' => null,
            'ArchivosSolicitud' => $archivos,
            'ComentariosSolicitud' => $datos['comentarios']
        );

        $datosEstatus = array(
            'idEstatus' => $datos['idEstatus'],
            'id' => $datos['id'],
            'fecha' => $fecha,
            'flag' => $datos['flag']);

        if (empty($idRegistro)) {
            $resultado = $this->DBP->insertarEnvioGuia($datosRegistro, $datosEstatus);
        } else {
            $resultado = $this->DBP->actualizarEnvioGuia($datosRegistro, $datosEstatus, $idRegistro[0]['Id']);
        }

        if ($resultado = 200) {
            if ($datos['idEstatus'] === '37') {
                $correoTecnico = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                                (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = IdUsuario) CorreoTecnico,
                                                                                nombreUsuario(IdUsuario) Tecnico
                                                                            FROM
                                                                                t_equipos_allab
                                                                            WHERE Id = "' . $datos['id'] . '"');

                $textoTecnico = '<p><strong>' . $correoTecnico[0]['Tecnico'] . '</strong> el departamento de logistica le mando el número de guía que solicito del servicio: <strong>' . $datos['idServicio'] . '</strong>.</p>';

                $this->enviarCorreoConcluido(array($correoTecnico[0]['CorreoTecnico']), 'Seguimiento solicitud de guía', $textoTecnico);
            }

            $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => $datos['idEstatus']));
            $mensaje = ['mensaje' => "Se guardo correctamente la entrega.",
                'datos' => $formularios,
                'idTabla' => $datos['id'],
                'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                'code' => 200];
            return $mensaje;
        } else {
            $mensaje = ['mensaje' => $resultado,
                'code' => 400];
            return $mensaje;
        }
    }

    public function solicitarGuia(array $datos) {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $datos['fecha'] = $fecha;
        $datos['idEstatus'] = 26;
        $datos['flag'] = '1';

        $resultado = $this->DBP->cambiarEsatus($datos);

        if ($resultado) {
            $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => 26));
            $mensaje = ['mensaje' => "Es correcto.",
                'datos' => $formularios,
                'idTabla' => $datos['id'],
                'idServicio' => $datos['idServicio'],
                'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                'code' => 200];
            return $mensaje;
        } else {
            $mensaje = ['mensaje' => $resultado,
                'code' => 400];
            return $mensaje;
        }
    }

    public function permisoNuevoRegistro() {
        $usuario = $this->Usuario->getDatosUsuario();

        if (in_array('305', $usuario['PermisosAdicionales']) || in_array('305', $usuario['Permisos'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function validarSolicitudEquipo(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $arraySolicitudRefaccion = array(
            'id' => $datos['id'],
            'idUsuario' => $usuario['Id'],
            'idEstatus' => $datos['idEstatus'],
            'fechaEstatus' => $fecha,
            'cobrable' => $datos['cobrable']
        );

        $resultado = $this->DBP->actualizarEquiposAllabSolicitudesRefaccion($arraySolicitudRefaccion);

        if (!empty($resultado)) {
            $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => 2));
            $mensaje = ['mensaje' => "Es correcto.",
                'datos' => $formularios,
                'idTabla' => $datos['id'],
                'idServicio' => $datos['idServicio'],
                'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                'code' => 200];
            return $mensaje;
        } else {
            $mensaje = ['mensaje' => $resultado,
                'code' => 400];
            return $mensaje;
        }
    }

    public function guardarSolicitudProducto(array $datos) {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $arrayCorreos = array();

        $solicitudesRefaccion = $this->DBP->consultaEquiposAllabSolicitudesRefaccion($datos['idServicio']);
        if (!empty($solicitudesRefaccion)) {
            $datos['idSolicitudRefaccion'] = $solicitudesRefaccion[0]['Id'];
            $datosEstatus = array(
                'idEstatus' => $datos['idEstatus'],
                'fecha' => $fecha,
                'flag' => $datos['flag'],
                'id' => $datos['id']);

            $resultado = $this->DBP->insertarRefaccionRefacciones($datos, $datosEstatus);

            if (!empty($resultado)) {
                $datosAllab = $this->DBP->consultaEquiposAllab($datos['idServicio']);
                $textoCorreo = '<p>Se le pide que le dé seguimiento a la solicitud de equipo del servicio: <strong>' . $datos['idServicio'] . '</strong>.</p>';
                $dataEmailProfiles = $this->validateDeliveryProductWarehouse(array('movementType' => $datosAllab[0]['IdTipoMovimiento'], 'idTechnical' => $datosAllab[0]['IdUsuario'], 'flag' => $datosAllab[0]['Flag']));
                $this->enviarCorreoConcluido($dataEmailProfiles, 'Seguimiento solicitud de equipo', $textoCorreo);

                $formularios = $this->mostrarVistaPorUsuario(array('idServicio' => $datos['idServicio'], 'idEstatus' => $datos['idEstatus']));
                $mensaje = ['mensaje' => "Es correcto.",
                    'datos' => $formularios,
                    'idTabla' => $datos['id'],
                    'idServicio' => $datos['idServicio'],
                    'tablaEquiposEnviadosSolicitados' => $this->mostrarTabla(),
                    'code' => 200];
                return $mensaje;
            } else {
                $mensaje = ['mensaje' => $resultado,
                    'code' => 400];
                return $mensaje;
            }
        } else {
            $mensaje = ['mensaje' => $solicitudesRefaccion,
                'code' => 400];
            return $mensaje;
        }
    }

    public function cargaAreasPuntosCenso(array $datos) {
        $areasPuntos = $this->DBCensos->getAreasPuntosCensos($datos['servicio']);
        $areasCliente = $this->DBCensos->getAreasClienteFaltantesCenso($datos['servicio']);
        $datos = [
            'areasPuntos' => $areasPuntos,
            'areasCliente' => $areasCliente
        ];
        return ['html' => parent::getCI()->load->view('Poliza/Modal/CensoAreasPuntos', $datos, TRUE)];
    }

    public function agregaAreaPuntosCenso(array $datos) {
        $result = $this->DBCensos->agregaAreaPuntosCenso($datos);
        return $result;
    }

    public function guardaCambiosAreasPuntos(array $datos) {
        $result = $this->DBCensos->guardaCambiosAreasPuntos($datos);
        return $result;
    }

    public function cargaEquiposPuntoCenso(array $datos) {
        $areasPuntos = $this->DBCensos->getAreasPuntosCensos($datos['servicio']);
        $puntosRevisados = $this->DBCensos->getPuntosCensoRevisados($datos['servicio']);
        $data = [
            'areasPuntos' => $areasPuntos,
            'puntosRevisados' => $puntosRevisados
        ];
        return ['html' => parent::getCI()->load->view('Poliza/Modal/CensoEquiposPuntoGroupArea', $data, TRUE)];
    }

    public function cargaFormularioCapturaCenso(array $datos) {
        $kitStandarArea = $this->DBCensos->getKitStandarArea($datos['area']);
        $modelosStandar = $this->DBCensos->getModelosStandarByArea($datos['area']);
        $equiposCensados = $this->DBCensos->getEquiposCensoByAreaPunto($datos);
        $nombreArea = $this->DBCensos->getNombreAreaById($datos['area']);
        $cliente = $this->DBCensos->getClienteByIdArea($datos['area']);
        $modelosEquipo = $this->DBCensos->getModelosGenerales();
        $estatusEquipoPrimeMX = $this->DBCensos->getEstatusEquipoPrimeMX();
        $soEquipoPrimeMX = $this->DBCensos->getSistemasOperativos();
        $data = [
            'kitStandarArea' => $kitStandarArea,
            'modelosStandar' => $modelosStandar,
            'equiposCensados' => $equiposCensados,
            'modelos' => $modelosEquipo,
            'nombreArea' => $nombreArea,
            'datosGenerales' => $datos,
            'cliente' => $cliente,
            'estatus' => $estatusEquipoPrimeMX,
            'so' => $soEquipoPrimeMX
        ];

        return ['html' => parent::getCI()->load->view('Poliza/Modal/FormularioCapturaCenso', $data, TRUE)];
    }

    public function cargaFormularioCapturaAdicionalesCenso(array $datos) {
        $equiposCensados = $this->DBCensos->getEquiposCensoByAreaPunto($datos);
        $nombreArea = $this->DBCensos->getNombreAreaById($datos['area']);
        $modelosEquipo = $this->DBCensos->getModelosGenerales();
        $data = [
            'equiposCensados' => $equiposCensados,
            'modelos' => $modelosEquipo,
            'nombreArea' => $nombreArea,
            'datosGenerales' => $datos
        ];

        return ['html' => parent::getCI()->load->view('Poliza/Modal/FormularioCapturaAdicionalesCenso', $data, TRUE)];
    }

    public function guardaEquiposPuntoCenso(array $datos) {
        $result = $this->DBCensos->guardaEquiposPuntoCenso($datos);
        return $result;
    }

    public function guardarEquipoAdicionalCenso(array $datos) {
        $result = $this->DBCensos->guardarEquipoAdicionalCenso($datos);
        return $result;
    }

    public function eliminarEquiposAdicionalesCenso(array $datos) {
        $result = $this->DBCensos->eliminarEquiposAdicionalesCenso($datos);
        return $result;
    }

    public function guardaCambiosEquiposAdicionalesCenso(array $datos) {
        $result = $this->DBCensos->guardaCambiosEquiposAdicionalesCenso($datos);
        return $result;
    }

    private function creationOfTeamRequestEmailList(array $dataToCreateEmailList) {
        $dataEmailProfiles = array();
        $listOfProfiles = $this->creationProfilesList($dataToCreateEmailList);
        $answerQueryProfiles = $this->DBP->consultPostByProfiles($listOfProfiles, $dataToCreateEmailList['idTechnical']);

        foreach ($answerQueryProfiles as $key => $value) {
            array_push($dataEmailProfiles, $value['EmailCorporativo']);
        }

        return $dataEmailProfiles;
    }

    private function creationProfilesList(array $dataToCreateEmailList) {
        switch ($dataToCreateEmailList['idStatus']) {
            case 2 :
                if ($dataToCreateEmailList['movementType'] === '2') {
                    $listOfProfiles = "'38','56'";
                } elseif ($dataToCreateEmailList['movementType'] === '3') {
                    $listOfProfiles = "'51','62'";
                }
                break;
            case 12 :
                if ($dataToCreateEmailList['movementType'] === '1') {
                    $listOfProfiles = "'51','62'";
                } else {
                    $listOfProfiles = "'38','56'";
                }
                break;
            case 28 :
                if ($dataToCreateEmailList['movementType'] === '1') {
                    $listOfProfiles = "'51','62','38','56'";
                }
                break;
            case 38 :
                if ($dataToCreateEmailList['movementType'] === '3') {
                    $listOfProfiles = "'51','62','41','52','60'";
                }
                break;
            case 31 :
            case 36 :
            case 39 :
                if ($dataToCreateEmailList['movementType'] === '1') {
                    $listOfProfiles = "'51','62','38','56','41','52','60'";
                } elseif ($dataToCreateEmailList['movementType'] === '3') {
                    $listOfProfiles = "'51','62','41','52','60'";
                } else {
                    $listOfProfiles = "'38','56'";
                }
                break;
            default :
                $listOfProfiles = "''";
                break;
        }

        return $listOfProfiles;
    }

    private function validateDeliveryProductWarehouse(array $dataToCreateEmailList) {
        if ($dataToCreateEmailList['flag'] === '1') {
            $dataEmailProfiles = $this->creationOfTeamRequestEmailList(array('idStatus' => 0, 'movementType' => $dataToCreateEmailList['movementType'], 'idTechnical' => $dataToCreateEmailList['idTechnical'], 'flag' => $dataToCreateEmailList['flag']));
        } else {
            $dataEmailProfiles = $this->creationOfTeamRequestEmailList(array('idStatus' => 38, 'movementType' => $dataToCreateEmailList['movementType'], 'idTechnical' => $dataToCreateEmailList['idTechnical'], 'flag' => $dataToCreateEmailList['flag']));
        }

        return $dataEmailProfiles;
    }

    private function creatingSupervisorAndTechnicalEmailList(array $dataToCreateEmailList) {
        $dataEmails = array();
        $answerQueryEmails = $this->DBP->consultSupervisorAndTechnicalMail($dataToCreateEmailList['idTechnical']);

        foreach ($answerQueryEmails as $key => $value) {
            array_push($dataEmails, $value['EmailCorporativo']);
        }

        return $dataEmails;
    }

}
