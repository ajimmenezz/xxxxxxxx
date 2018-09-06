<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Proyectos2 extends Modelo_Base {

    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getSistemas(int $sistema = null) {
        $condicion = (!is_null($sistema)) ? " where Id = '" . $sistema . "'" : '';
        $consulta = $this->consulta("select  
                                    Id,
                                    Nombre,
                                    if(Flag = 1, 'Activo', 'Inactivo') as Estatus,
                                    Flag
                                    from cat_v3_sistemas_proyecto " . $condicion . "
                                    order by Nombre;");
        return $consulta;
    }

    public function agregarSistema(string $sistema) {
        $insert = $this->insertar("cat_v3_sistemas_proyecto", ['Nombre' => mb_strtoupper($sistema)]);
        if (!is_null($insert)) {
            return [
                'id' => $this->ultimoId()
            ];
        } else {
            return [
                'id' => null,
                'error' => $this->tipoError()
            ];
        }
    }

    public function editarSistema(array $datos) {
        $edit = $this->actualizar("cat_V3_sistemas_proyecto", ['Nombre' => mb_strtoupper($datos['sistema']), 'Flag' => $datos['estatus']], ['Id' => $datos['id']]);
        if (!is_null($edit)) {
            return [
                'datos' => $this->getSistemas($datos['id'])
            ];
        } else {
            return [
                'datos' => [],
                'error' => $this->tipoError()
            ];
        }
    }

    public function getTipos(int $tipo = null) {
        $condicion = (!is_null($tipo)) ? " where Id = '" . $tipo . "'" : '';
        $consulta = $this->consulta("select  
                                    Id,
                                    Nombre,
                                    if(Flag = 1, 'Activo', 'Inactivo') as Estatus,
                                    Flag
                                    from cat_v3_tipo_proyecto " . $condicion . "
                                    order by Nombre;");
        return $consulta;
    }

    public function agregarTipo(string $tipo) {
        $insert = $this->insertar("cat_v3_tipo_proyecto", ['Nombre' => mb_strtoupper($tipo)]);
        if (!is_null($insert)) {
            return [
                'id' => $this->ultimoId()
            ];
        } else {
            return [
                'id' => null,
                'error' => $this->tipoError()
            ];
        }
    }

    public function editarTipo(array $datos) {
        $edit = $this->actualizar("cat_v3_tipo_proyecto", ['Nombre' => mb_strtoupper($datos['tipo']), 'Flag' => $datos['estatus']], ['Id' => $datos['id']]);
        if (!is_null($edit)) {
            return [
                'datos' => $this->getTipos($datos['id'])
            ];
        } else {
            return [
                'datos' => [],
                'error' => $this->tipoError()
            ];
        }
    }

    public function getConceptos(int $concepto = null) {
        $condicion = (!is_null($concepto)) ? " where c.Id = '" . $concepto . "'" : '';
        $consulta = $this->consulta("select 
                                    c.Id,
                                    c.Nombre,
                                    s.Id as IdSistema,
                                    s.Nombre as Sistema,
                                    if(c.Flag = 1, 'Activo', 'Inactivo') as Estatus,
                                    c.Flag
                                    from cat_v3_conceptos_proyecto c
                                    inner join cat_v3_sistemas_proyecto s on c.IdSistema = s.Id " . $condicion . "
                                    order by Nombre;");
        return $consulta;
    }

    public function agregarConcepto(array $datos) {
        $consulta = $this->consulta("select "
                . "* "
                . "from cat_v3_conceptos_proyecto "
                . "where Nombre = '" . mb_strtoupper($datos['concepto']) . "' "
                . "and IdSistema = '" . $datos['sistema'] . "'");

        if (!empty($consulta)) {
            return [
                'id' => null,
                'error' => "El concepto ya se encuentra registrado en la Base de Datos"
            ];
        } else {
            $insert = $this->insertar("cat_v3_conceptos_proyecto", ['Nombre' => mb_strtoupper($datos['concepto']), 'IdSistema' => $datos['sistema']]);
            if (!is_null($insert)) {
                return [
                    'id' => $this->ultimoId()
                ];
            } else {
                return [
                    'id' => null,
                    'error' => $this->tipoError()
                ];
            }
        }
    }

    public function editarConcepto(array $datos) {
        $consulta = $this->consulta("select "
                . "* "
                . "from cat_v3_conceptos_proyecto "
                . "where Nombre = '" . mb_strtoupper($datos['concepto']) . "' "
                . "and IdSistema = '" . $datos['sistema'] . "' "
                . "and Id <> '" . $datos['id'] . "'");

        if (!empty($consulta)) {
            return [
                'id' => null,
                'error' => "El concepto ya se encuentra registrado en la Base de Datos"
            ];
        } else {
            $edit = $this->actualizar("cat_v3_conceptos_proyecto", ['IdSistema' => $datos['sistema'], 'Nombre' => mb_strtoupper($datos['concepto']), 'Flag' => $datos['estatus']], ['Id' => $datos['id']]);
            if (!is_null($edit)) {
                return [
                    'datos' => $this->getConceptos($datos['id'])
                ];
            } else {
                return [
                    'datos' => [],
                    'error' => $this->tipoError()
                ];
            }
        }
    }

    public function getAreas(int $area = null) {
        $condicion = (!is_null($area)) ? " where a.Id = '" . $area . "'" : '';
        $consulta = $this->consulta("select 
                                    a.Id,
                                    a.Nombre,
                                    a.IdConcepto,
                                    concat(c.Nombre, ' - ', (select Nombre from cat_v3_sistemas_proyecto where Id = c.IdSistema)) as Concepto,
                                    if(a.Flag = 1, 'Activo', 'Inactivo') as Estatus,
                                    a.Flag
                                    from cat_v3_areas_proyectos a 
                                    inner join cat_v3_conceptos_proyecto c on a.IdConcepto = c.Id " . $condicion . "
                                    order by Nombre;");
        return $consulta;
    }

    public function agregarArea(array $datos) {
        $consulta = $this->consulta("select "
                . "* "
                . "from cat_v3_areas_proyectos "
                . "where Nombre = '" . mb_strtoupper($datos['area']) . "' "
                . "and IdConcepto = '" . $datos['concepto'] . "'");

        if (!empty($consulta)) {
            return [
                'id' => null,
                'error' => "El Área ya se encuentra registrada en la Base de Datos"
            ];
        } else {
            $insert = $this->insertar("cat_v3_areas_proyectos", ['Nombre' => mb_strtoupper($datos['area']), 'IdConcepto' => $datos['concepto']]);
            if (!is_null($insert)) {
                return [
                    'id' => $this->ultimoId()
                ];
            } else {
                return [
                    'id' => null,
                    'error' => $this->tipoError()
                ];
            }
        }
    }

    public function editarArea(array $datos) {
        $consulta = $this->consulta("select "
                . "* "
                . "from cat_v3_areas_proyectos "
                . "where Nombre = '" . mb_strtoupper($datos['area']) . "' "
                . "and IdConcepto = '" . $datos['concepto'] . "' "
                . "and Id <> '" . $datos['id'] . "'");

        if (!empty($consulta)) {
            return [
                'id' => null,
                'error' => "El Área ya se encuentra registrada en la Base de Datos"
            ];
        } else {
            $edit = $this->actualizar("cat_v3_areas_proyectos", ['IdConcepto' => $datos['concepto'], 'Nombre' => mb_strtoupper($datos['area']), 'Flag' => $datos['estatus']], ['Id' => $datos['id']]);
            if (!is_null($edit)) {
                return [
                    'datos' => $this->getAreas($datos['id'])
                ];
            } else {
                return [
                    'datos' => [],
                    'error' => $this->tipoError()
                ];
            }
        }
    }

    public function getUbicaciones(int $ubicacion = null) {
        $condicion = (!is_null($ubicacion)) ? " where ubicaciones.Id = '" . $ubicacion . "'" : '';
        $consulta = $this->consulta("select 
                                    ubicaciones.Id,
                                    ubicaciones.Nombre,
                                    ubicaciones.IdArea,
                                    concat(areas.Nombre,' (',conceptos.Nombre,' - ',sistemas.Nombre,')') as Area,
                                    if(ubicaciones.Flag = 1, 'Activo', 'Inactivo') as Estatus,
                                    ubicaciones.Flag
                                    from cat_v3_ubicaciones_proyectos ubicaciones
                                    inner join cat_v3_areas_proyectos areas on ubicaciones.IdArea = areas.Id
                                    inner join cat_v3_conceptos_proyecto conceptos on areas.IdConcepto = conceptos.Id
                                    inner join cat_v3_sistemas_proyecto sistemas on conceptos.IdSistema = sistemas.Id 
                                    " . $condicion . " 
                                    order by Nombre, Area;");
        return $consulta;
    }

    public function agregarUbicacion(array $datos) {
        $consulta = $this->consulta("select "
                . "* "
                . "from cat_v3_ubicaciones_proyectos "
                . "where Nombre = '" . mb_strtoupper($datos['ubicacion']) . "' "
                . "and IdArea = '" . $datos['area'] . "'");

        if (!empty($consulta)) {
            return [
                'id' => null,
                'error' => "La Ubicación ya se encuentra registrada en la Base de Datos"
            ];
        } else {
            $insert = $this->insertar("cat_v3_ubicaciones_proyectos", ['Nombre' => mb_strtoupper($datos['ubicacion']), 'IdArea' => $datos['area']]);
            if (!is_null($insert)) {
                return [
                    'id' => $this->ultimoId()
                ];
            } else {
                return [
                    'id' => null,
                    'error' => $this->tipoError()
                ];
            }
        }
    }

    public function editarUbicacion(array $datos) {
        $consulta = $this->consulta("select "
                . "* "
                . "from cat_v3_ubicaciones_proyectos "
                . "where Nombre = '" . mb_strtoupper($datos['ubicacion']) . "' "
                . "and IdArea = '" . $datos['area'] . "' "
                . "and Id <> '" . $datos['id'] . "'");

        if (!empty($consulta)) {
            return [
                'id' => null,
                'error' => "La ubicación ya se encuentra registrada en la Base de Datos"
            ];
        } else {
            $edit = $this->actualizar("cat_v3_ubicaciones_proyectos", ['IdArea' => $datos['area'], 'Nombre' => mb_strtoupper($datos['ubicacion']), 'Flag' => $datos['estatus']], ['Id' => $datos['id']]);
            if (!is_null($edit)) {
                return [
                    'datos' => $this->getUbicaciones($datos['id'])
                ];
            } else {
                return [
                    'datos' => [],
                    'error' => $this->tipoError()
                ];
            }
        }
    }

    public function getAccesorios(int $concepto = null) {
        $condicion = (!is_null($concepto)) ? " where c.Id = '" . $concepto . "'" : '';
        $consulta = $this->consulta("select 
                                    c.Id,
                                    c.Nombre,
                                    s.Id as IdSistema,
                                    s.Nombre as Sistema,
                                    if(c.Flag = 1, 'Activo', 'Inactivo') as Estatus,
                                    c.Flag
                                    from cat_v3_accesorios_proyecto c
                                    inner join cat_v3_sistemas_proyecto s on c.IdSistema = s.Id " . $condicion . "
                                    order by Nombre;");
        return $consulta;
    }

    public function agregarAccesorio(array $datos) {
        $consulta = $this->consulta("select "
                . "* "
                . "from cat_v3_accesorios_proyecto "
                . "where Nombre = '" . mb_strtoupper($datos['accesorio']) . "' "
                . "and IdSistema = '" . $datos['sistema'] . "'");

        if (!empty($consulta)) {
            return [
                'id' => null,
                'error' => "El Accesorio ya se encuentra registrado en la Base de Datos"
            ];
        } else {
            $insert = $this->insertar("cat_v3_accesorios_proyecto", ['Nombre' => mb_strtoupper($datos['accesorio']), 'IdSistema' => $datos['sistema']]);
            if (!is_null($insert)) {
                return [
                    'id' => $this->ultimoId()
                ];
            } else {
                return [
                    'id' => null,
                    'error' => $this->tipoError()
                ];
            }
        }
    }

    public function editarAccesorio(array $datos) {
        $consulta = $this->consulta("select "
                . "* "
                . "from cat_v3_accesorios_proyecto "
                . "where Nombre = '" . mb_strtoupper($datos['accesorio']) . "' "
                . "and IdSistema = '" . $datos['sistema'] . "' "
                . "and Id <> '" . $datos['id'] . "'");

        if (!empty($consulta)) {
            return [
                'id' => null,
                'error' => "El Accesorio ya se encuentra registrado en la Base de Datos"
            ];
        } else {
            $edit = $this->actualizar("cat_v3_accesorios_proyecto", ['IdSistema' => $datos['sistema'], 'Nombre' => mb_strtoupper($datos['accesorio']), 'Flag' => $datos['estatus']], ['Id' => $datos['id']]);
            if (!is_null($edit)) {
                return [
                    'datos' => $this->getAccesorios($datos['id'])
                ];
            } else {
                return [
                    'datos' => [],
                    'error' => $this->tipoError()
                ];
            }
        }
    }

    public function getMaterial(int $material = null) {
        $condicion = (!is_null($material)) ? " where material.Id = '" . $material . "'" : '';
        $consulta = $this->consulta("select 
                                    material.Id,
                                    material.IdMaterial,
                                    material.IdAccesorio,
                                    (select concat('[',Clave,'] ',Nombre) from cat_v3_equipos_sae where Id = material.IdMaterial) as Material,
                                    (select Nombre from cat_v3_accesorios_proyecto where Id = material.IdAccesorio) as Accesorio
                                    from cat_v3_material_proyectos material " . $condicion . "
                                    order by Material, Accesorio;");
        return $consulta;
    }

    public function getMaterialSAE() {
        $consulta = $this->consulta("select 
                                    Id,
                                    concat('[',Clave,'] ',Nombre) as Nombre
                                    from cat_v3_equipos_sae
                                    order by Nombre;");
        return $consulta;
    }

    public function agregarMaterial(array $datos) {
        $consulta = $this->consulta("select "
                . "* "
                . "from cat_v3_material_proyectos "
                . "where IdMaterial = '" . $datos['material'] . "' "
                . "and IdAccesorio = '" . $datos['accesorio'] . "'");

        if (!empty($consulta)) {
            return [
                'id' => null,
                'error' => "El Material ya se encuentra registrado con el Accesorio en la Base de Datos"
            ];
        } else {
            $insert = $this->insertar("cat_v3_material_proyectos", ['IdMaterial' => mb_strtoupper($datos['material']), 'IdAccesorio' => $datos['accesorio']]);
            if (!is_null($insert)) {
                return [
                    'id' => $this->ultimoId()
                ];
            } else {
                return [
                    'id' => null,
                    'error' => $this->tipoError()
                ];
            }
        }
    }

    public function editarMaterial(array $datos) {
        $consulta = $this->consulta("select "
                . "* "
                . "from cat_v3_material_proyectos "
                . "where  IdMaterial = '" . $datos['material'] . "' "
                . "and IdAccesorio = '" . $datos['accesorio'] . "'"
                . "and Id <> '" . $datos['id'] . "'");

        if (!empty($consulta)) {
            return [
                'id' => null,
                'error' => "El Material ya se encuentra registrado con el Accesorio en la Base de Datos"
            ];
        } else {
            $edit = $this->actualizar("cat_v3_material_proyectos", ['IdMaterial' => $datos['material'], 'IdAccesorio' => $datos['accesorio']], ['Id' => $datos['id']]);
            if (!is_null($edit)) {
                return [
                    'datos' => $this->getMaterial($datos['id'])
                ];
            } else {
                return [
                    'datos' => [],
                    'error' => $this->tipoError()
                ];
            }
        }
    }

    public function getKits(int $kit = null, bool $activos = false) {
        $condicion = (!is_null($kit)) ? " and Id = '" . $kit . "'" : '';
        $condicion .= $activos ? " and Flag = 1 " : '';

        $consulta = $this->consulta("select * from cat_v3_kits_material_proyectos where 1=1 " . $condicion . " order by Nombre");
        if (!empty($consulta)) {
            $arrayKits = [];
            foreach ($consulta as $key => $value) {
                $materialKit = explode("|", $value['Material']);
                $material = [];
                foreach ($materialKit as $keyMaterial => $valueMaterial) {
                    $materialIndividual = explode(",", $valueMaterial);
                    $materialAux = $this->getMaterial($materialIndividual[0])[0];
                    array_push($material, [
                        'IdMaterial' => $materialIndividual[0],
                        'IdMaterialSAE' => $materialAux['IdMaterial'],
                        'IdAccesorio' => $materialAux['IdAccesorio'],
                        'Accesorio' => $materialAux['Accesorio'],
                        'Material' => $materialAux['Material'],
                        'Nombre' => $materialAux['Accesorio'] . ' - ' . $materialAux['Material'],
                        'Cantidad' => $materialIndividual[1]
                    ]);
                }

                array_push($arrayKits, [
                    'Id' => $value['Id'],
                    'Kit' => $value['Nombre'],
                    'Material' => $material
                ]);
            }

            return $arrayKits;
        } else {
            return $consulta;
        }
    }

    public function agregarKit(array $datos) {
        $consulta = $this->consulta("select "
                . "* "
                . "from cat_v3_kits_material_proyectos "
                . "where Nombre = '" . mb_strtoupper($datos['kit']) . "'");

        if (!empty($consulta)) {
            return [
                'id' => null,
                'error' => "Ya existe un Kit con ese Nombre en la Base de Datos"
            ];
        } else {
            $insert = $this->insertar("cat_v3_kits_material_proyectos", ['Nombre' => mb_strtoupper($datos['kit']), 'Material' => implode("|", $datos['material'])]);
            if (!is_null($insert)) {
                return [
                    'id' => $this->ultimoId()
                ];
            } else {
                return [
                    'id' => null,
                    'error' => $this->tipoError()
                ];
            }
        }
    }

    public function editarKit(array $datos) {
        $consulta = $this->consulta("select "
                . "* "
                . "from cat_v3_kits_material_proyectos "
                . "where  Nombre = '" . mb_strtoupper($datos['kit']) . "' "
                . "and Id <> '" . $datos['idKit'] . "'");

        if (!empty($consulta)) {
            return [
                'id' => null,
                'error' => "El Kit ya se encuentra registrado en la Base de Datos"
            ];
        } else {
            $edit = $this->actualizar("cat_v3_kits_material_proyectos", ['Material' => implode("|", $datos['material']), 'Nombre' => mb_strtoupper($datos['kit'])], ['Id' => $datos['idKit']]);
            if (!is_null($edit)) {
                return [
                    'datos' => $this->getKits($datos['idKit'])
                ];
            } else {
                return [
                    'datos' => [],
                    'error' => $this->tipoError()
                ];
            }
        }
    }

    public function getClientes() {
        $consulta = $this->consulta("select Id, Nombre from cat_v3_clientes order by Nombre");
        return $consulta;
    }

    public function getSucursalesEstados(int $cliente) {
        $consulta = $this->consulta("select
                                    cs.Id,
                                    concat(cs.Nombre,' (',(select Nombre from cat_v3_estados where Id = cs.IdEstado),')') as Nombre
                                    from cat_v3_sucursales cs where IdCliente = '" . $cliente . "' AND Flag = 1 order by Nombre;");
        return $consulta;
    }

    public function getLideres() {
        $consulta = $this->consulta("select 
                                    cu.Id,
                                    nombreUsuario(cu.Id) as Nombre,
                                    (select Nombre from cat_perfiles where Id = cu.IdPerfil) as Perfil
                                    from cat_v3_usuarios cu
                                    where IdPerfil in (24,26,27,42) 
                                    and Flag = 1
                                    order by Nombre;");
        return $consulta;
    }

    public function generaTicketsProyectoV2(int $cliente, int $cantidad = 1) {
        $ids = [];
        $estatus = true;
        $error = '';

        $headers = apache_request_headers();
        if ($headers['Host'] == "siccob.solutions") {
            $connection = parent::connectDBAdist2();
        } else {
            $connection = parent::connectDBAdist2P();
        }

        try {

            $connection->trans_begin();
            $query = 'insert into t_servicios set 
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
                    Prioridad = 0';

            for ($i = 0; $i < $cantidad; $i++) {
                $consulta = $connection->query($query);
                if ($consulta) {
                    array_push($ids, $connection->insert_id());
                } else {
                    $estatus = false;
                    $error = $connection->error_message();
                }
            }

            if ($connection->trans_status() === FALSE) {
                $connection->trans_rollback();
                return ['code' => 500, 'error' => $error];
            } else {
                $connection->trans_commit();
                return ['code' => 200, 'ids' => $ids];
            }
        } catch (Exception $e) {
            return ['code' => 500, 'error' => $e->getMessage()];
        }
    }

    public function generaProyecto(array $datos = [], array $tickets = []) {
        $numeroAletorio = rand(1, 100000);
        $grupo = strtoupper($datos['sistema'] . substr(trim(mb_strtoupper($datos['nombre'])), 0, 3) . $datos['cliente'] . $numeroAletorio);
        $fini = (isset($datos['fini']) && $datos['fini'] !== '') ? date('Y-m-d', strtotime($datos['fini'])) : '';
        $ffin = (isset($datos['ffin']) && $datos['ffin'] !== '') ? date('Y-m-d', strtotime($datos['ffin'])) : '';

        $connection = parent::connectDBPrueba();
        $connection->trans_begin();
        foreach ($datos['sucursal'] as $key => $value) {
            $connection->insert('t_proyectos', [
                'Ticket' => $tickets[$key],
                'Nombre' => mb_strtoupper($datos['nombre']),
                'IdSistema' => $datos['sistema'],
                'IdTipo' => $datos['tipo'],
                'IdUsuario' => $this->usuario['Id'],
                'IdSucursal' => $value,
                'IdEstatus' => 1,
                'Grupo' => $grupo,
                'Observaciones' => $datos['observaciones'],
                'FechaInicio' => $fini,
                'FechaTermino' => $ffin,
                'IdUsuarioModifica' => $this->usuario['Id']
            ]);

            $idProyecto = $connection->insert_id();
            if (isset($datos['lider']) && !empty($datos['lider'])) {
                foreach ($datos['lider'] as $key => $value) {
                    $connection->insert('t_lideres_proyecto', [
                        'IdUsuario' => $value,
                        'IdProyecto' => $idProyecto
                    ]);
                }
            }
        }

        if ($connection->trans_status() === FALSE) {
            $connection->trans_rollback();
            return ['code' => 500, 'error' => $connection->error_message()];
        } else {
            $connection->trans_commit();
            return ['code' => 200, 'tickets' => $tickets];
        }
    }

    public function getGeneralesProyecto(int $id) {
        $consulta = $this->consulta("select * from t_proyectos where Id = '" . $id . "'");
        return $consulta[0];
    }

    public function getClienteProyecto(int $id) {
        $consulta = $this->consulta("select Ticket from t_proyectos where Id = '" . $id . "'");
        $ticket = $consulta[0]['Ticket'];


        $headers = apache_request_headers();
        if ($headers['Host'] == "siccob.solutions") {
            $connection = parent::connectDBAdist2();
        } else {
            $connection = parent::connectDBAdist2P();
        }

        $query = "select Cliente from t_servicios where Id_Orden = '" . $ticket . "'";
        $consulta = $connection->query($query);
        $row = $consulta->result_array();
        return $row[0]['Cliente'];
    }

    public function getLideresProyecto(int $id) {
        $consulta = $this->consulta("select group_concat(IdUsuario) as Lideres from t_lideres_proyecto where IdProyecto = '" . $id . "'");
        return $consulta[0];
    }

    public function guardarGeneralesProyecto(array $datos) {
        $fini = (isset($datos['fini']) && $datos['fini'] !== '') ? date('Y-m-d', strtotime($datos['fini'])) : '';
        $ffin = (isset($datos['ffin']) && $datos['ffin'] !== '') ? date('Y-m-d', strtotime($datos['ffin'])) : '';

        $this->iniciaTransaccion();

        $edit = $this->actualizar("t_proyectos", [
            'Nombre' => mb_strtoupper($datos['nombre']),
            'IdSistema' => $datos['sistema'],
            'IdTipo' => $datos['tipo'],
            'IdUsuarioModifica' => $this->usuario['Id'],
            'IdSucursal' => $datos['sucursal'],
            'Observaciones' => $datos['observaciones'],
            'FechaInicio' => $fini,
            'FechaTermino' => $ffin
                ], ['Id' => $datos['id']]);
        if (!is_null($edit)) {

            $this->eliminar('t_lideres_proyecto', ['IdProyecto' => $datos['id']]);
            if (isset($datos['lider']) && !empty($datos['lider'])) {
                foreach ($datos['lider'] as $key => $value) {
                    $this->insertar('t_lideres_proyecto', [
                        'IdUsuario' => $value,
                        'IdProyecto' => $datos['id']
                    ]);
                }
            }

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
        } else {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        }
    }

    public function editarClienteProyecto(array $datos) {
        $headers = apache_request_headers();
        if ($headers['Host'] == "siccob.solutions") {
            $connection = parent::connectDBAdist2();
        } else {
            $connection = parent::connectDBAdist2P();
        }

        $connection->where(['Id_Orden' => $datos['ticket']]);
        $connection->update('t_servicios', ['Cliente' => $datos['cliente']]);
        return $connection->affected_rows();
    }

    public function getConceptosBySistema(int $sistema = null) {
        $condicion = (!is_null($sistema)) ? " and c.IdSistema = '" . $sistema . "'" : '';
        $consulta = $this->consulta("select 
                                    c.Id,
                                    c.Nombre
                                    from cat_v3_conceptos_proyecto c
                                    where c.Flag = 1 
                                     " . $condicion . " 
                                    order by Nombre;");
        return $consulta;
    }

    public function areasByConcepto(int $concepto = null) {
        $condicion = (!is_null($concepto)) ? " and a.IdConcepto = '" . $concepto . "'" : '';
        $consulta = $this->consulta("select 
                                    a.Id,
                                    a.Nombre
                                    from cat_v3_areas_proyectos a
                                    where a.Flag = 1 
                                     " . $condicion . " 
                                    order by Nombre;");
        return $consulta;
    }

    public function ubicacionesByArea(int $area = null) {
        $condicion = (!is_null($area)) ? " and u.IdArea = '" . $area . "'" : '';
        $consulta = $this->consulta("select 
                                    u.Id,
                                    u.Nombre
                                    from cat_v3_ubicaciones_proyectos u
                                    where u.Flag = 1 
                                     " . $condicion . " 
                                    order by Nombre;");
        return $consulta;
    }

    public function getTiposNodo() {
        $consulta = $this->consulta("select * from cat_v3_tipos_nodo_proyectos where Flag = 1 order by Nombre");
        return $consulta;
    }

    public function getAccesoriosBySistema(int $accesorios = null) {
        $condicion = (!is_null($accesorios)) ? " and acce.IdSistema = '" . $accesorios . "'" : '';
        $consulta = $this->consulta("select
                                    mat.Id,
                                    mat.IdAccesorio,
                                    acce.Nombre as Accesorio,
                                    mat.IdMaterial,
                                    (select concat(Clave,' ',Nombre) from cat_v3_equipos_sae where Id = mat.IdMaterial) as Material,
                                    concat(acce.Nombre,' - ',(select concat(Clave,' ',Nombre) from cat_v3_equipos_sae where Id = mat.IdMaterial)) as Nombre
                                    from cat_v3_material_proyectos mat
                                    inner join cat_v3_accesorios_proyecto acce on mat.IdAccesorio = acce.Id
                                    where acce.Flag = 1
                                    " . $condicion . "
                                    order by Nombre;");
        return $consulta;
    }

    public function guardarNodosUbicacion(array $datos) {
        $this->iniciaTransaccion();

        $ubicacion = $this->consulta("select "
                . "Id "
                . "from t_alcance_proyecto "
                . "where IdProyecto = '" . $datos['id'] . "' "
                . "and IdConcepto = '" . $datos['concepto'] . "' "
                . "and IdArea = '" . $datos['area'] . "' "
                . "and IdUbicacion = '" . $datos['ubicacion'] . "' "
                . "and Flag = 1");

        if (!empty($ubicacion)) {
            $idAlcance = $ubicacion[0]['Id'];
        } else {
            $insert = $this->insertar('t_alcance_proyecto', [
                'IdProyecto' => $datos['id'],
                'IdConcepto' => $datos['concepto'],
                'IdArea' => $datos['area'],
                'IdUbicacion' => $datos['ubicacion'],
                'Flag' => 1
            ]);

            $idAlcance = $this->ultimoId();
        }

        foreach ($datos['nodos'] as $key => $value) {
            if ($value['id'] !== '') {
                $this->actualizar('t_nodos_alcance_proyecto', [
                    'IdTipoNodo' => $value['tipo'],
                    'Nombre' => $value['nombre'],
                    'IdAccesorio' => $value['accesorio'],
                    'IdMaterial' => $value['material'],
                    'Cantidad' => $value['cantidad'],
                    'Flag' => 1
                        ], ['Id' => $value['id']]);
            } else {
                $this->insertar('t_nodos_alcance_proyecto', [
                    'IdAlcance' => $idAlcance,
                    'IdTipoNodo' => $value['tipo'],
                    'Nombre' => $value['nombre'],
                    'IdAccesorio' => $value['accesorio'],
                    'IdMaterial' => $value['material'],
                    'Cantidad' => $value['cantidad'],
                    'Flag' => 1
                ]);
            }
        }

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

    public function cargaUbicacionesProyecto(int $proyecto) {
        $consulta = $this->consulta("select 
                                    Id,
                                    (select Nombre from cat_v3_conceptos_proyecto where Id = IdConcepto) as Concepto,
                                    (select Nombre from cat_v3_areas_proyectos where Id = IdArea) as Area,
                                    (select Nombre from cat_v3_ubicaciones_proyectos where Id = IdUbicacion) as Ubicacion
                                    from t_alcance_proyecto
                                    where IdProyecto = '" . $proyecto . "'
                                    and Flag = 1;");
        return $consulta;
    }

    public function getNodosByUbicacion(array $datos) {
        $ubicacion = $this->consulta("select "
                . "Id "
                . "from t_alcance_proyecto "
                . "where IdProyecto = '" . $datos['id'] . "' "
                . "and IdConcepto = '" . $datos['concepto'] . "' "
                . "and IdArea = '" . $datos['area'] . "' "
                . "and IdUbicacion = '" . $datos['ubicacion'] . "' "
                . "and Flag = 1");

        if (!empty($ubicacion)) {
            $idAlcance = $ubicacion[0]['Id'];
            $nodos = $this->consulta("SELECT
                                        Id,
                                        IdTipoNodo,
                                        IdAccesorio,
                                        IdMaterial,
                                        (select Nombre from cat_v3_tipos_nodo_proyectos where Id = IdTipoNodo) as TipoNodo,
                                        Nombre as Nodo,
                                        (select Nombre from cat_v3_accesorios_proyecto where Id = IdAccesorio) as Accesorio,
                                        (select Nombre from cat_v3_equipos_sae where Id = IdMaterial) as Material,
                                        Cantidad
                                        from t_nodos_alcance_proyecto
                                        where IdAlcance = '" . $idAlcance . "' and Flag = 1;");
        } else {
            $nodos = [];
        }

        return $nodos;
    }

    public function eliminarNodo(array $datos) {
        $edit = $this->actualizar("t_nodos_alcance_proyecto", [
            'Flag' => 0
                ], ['Id' => $datos['id']]);
        if (!is_null($edit)) {
            return ['code' => 200];
        } else {
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        }
    }

    public function getAlcanceById(int $id) {
        $consulta = $this->consulta("select * from t_alcance_proyecto where Id = '" . $id . "'");
        return $consulta;
    }

    public function cargaMaterialProyectado(int $proyecto) {
        $consulta = $this->consulta("select                                     
                                    concat(cap.Nombre,' - ',sae.Nombre) as Material,
                                    sae.Clave,
                                    sum(tnap.Cantidad) as Total,
                                    sae.Unidad
                                    from t_nodos_alcance_proyecto tnap
                                    inner join t_alcance_proyecto tap on tap.Id = tnap.IdAlcance
                                    inner join cat_v3_equipos_sae sae on tnap.IdMaterial = sae.Id
                                    inner join cat_v3_accesorios_proyecto cap on tnap.IdAccesorio = cap.Id
                                    where tap.IdProyecto = '" . $proyecto . "'
                                    and tap.Flag = 1
                                    and tnap.Flag = 1
                                    group by sae.Clave;
                                    ");
        return $consulta;
    }

    public function cargaMaterialAsignadoSAE(int $almacen) {
        $query = "select 
                    inve.CVE_ART as Clave,
                    inve.DESCR as Material,
                    almacen.EXIST as Total,
                    inve.UNI_MED as Unidad
                    from MULT03 almacen
                    INNER JOIN INVE03 inve on almacen.CVE_ART = inve.CVE_ART
                    where almacen.CVE_ALM = '" . $almacen . "';";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function getTecnicosAsistentes() {
        $consulta = $this->consulta('
                select 
                    cu.Id, 
                    nombreUsuario(cu.Id) as Nombre,
                    rhp.NSS,
                    (select Nombre from cat_perfiles where Id = cu.IdPerfil) as Perfil,
                    cu.Flag
                from cat_v3_usuarios cu                 
                inner join t_rh_personal rhp 
                on cu.Id = rhp.IdUsuario 
                where cu.IdPerfil in (30,31,32,81,82) 
                order by Nombre');
        return $consulta;
    }

    public function getAsistentesProyecto(int $proyecto, int $id = null) {
        $condicion = (!is_null($id)) ? " and tap.Id = '" . $id . "'" : "";
        $consulta = $this->consulta("select 
                                    tap.Id,
                                    tap.IdUsuario,
                                    nombreUsuario(tap.IdUsuario) as Nombre,
                                    (select Nombre from cat_perfiles where Id = (select IdPerfil from cat_v3_usuarios where Id = tap.IdUsuario)) as Perfil,
                                    (select NSS from t_rh_personal where IdUsuario = tap.IdUsuario) as NSS
                                    from t_asistentes_proyecto tap
                                    where IdProyecto = '" . $proyecto . "' " . $condicion . " 
                                    order by Nombre");
        return $consulta;
    }

    public function guardaAsistenteProyecto(array $datos) {
        $insert = $this->insertar("t_asistentes_proyecto", [
            'IdUsuario' => $datos['tecnico'],
            'IdProyecto' => $datos['id']
        ]);
        if (!is_null($insert)) {
            return [
                'code' => 200,
                'id' => $this->ultimoId()
            ];
        } else {
            return [
                'id' => null,
                'code' => 500,
                'error' => $this->tipoError()
            ];
        }
    }

    public function eliminarAsistente(array $datos) {
        $this->iniciaTransaccion();

        $this->eliminar('t_asistentes_proyecto', ['Id' => $datos['id']]);

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

    public function getTareasPredecesoras(int $proyecto, int $id = null) {
        $condicion = (!is_null($id)) ? " and Id <> '" . $id . "'" : "";
        $consulta = "select 
                    Id,
                    Nombre,
                    Fin
                    from t_proyectos_tareas
                    where IdProyecto = '" . $proyecto . "'
                    and Fin <> ''
                    and Fin is not null " . $condicion . " order by Nombre asc";
        return $consulta;
    }

    public function guardaTarea(array $datos) {
        $fini = (isset($datos['fini']) && $datos['fini'] !== '') ? date('Y-m-d', strtotime($datos['fini'])) : '';
        $ffin = (isset($datos['ffin']) && $datos['ffin'] !== '') ? date('Y-m-d', strtotime($datos['ffin'])) : '';

        $connection = parent::connectDBPrueba();
        $connection->trans_begin();
        if (!isset($datos['idTarea'])) {
            $connection->insert('t_proyectos_tareas', [
                'IdProyecto' => $datos['id'],
                'IdPredecesora' => $datos['predecesora'],
                'Nombre' => mb_strtoupper($datos['nombre']),
                'Inicio' => $fini,
                'Fin' => $ffin,
                'IdLider' => $datos['lider'],
                'IdEstatus' => 1
            ]);
        }

        $idTarea = $connection->insert_id();
        if (isset($datos['tecnicos']) && !empty($datos['tecnicos'])) {
            foreach ($datos['tecnicos'] as $key => $value) {
                $connection->insert('t_proyectos_tarea_asistentes', [
                    'IdTecnico' => $value,
                    'IdTarea' => $idTarea
                ]);
            }
        }

        if ($connection->trans_status() === FALSE) {
            $connection->trans_rollback();
            return ['code' => 500, 'error' => $connection->error_message()];
        } else {
            $connection->trans_commit();
            return ['code' => 200, 'id' => $idTarea, 'tecnicos' => $this->getTecnicosTareaProyecto($idTarea)];
        }
    }

    public function getTecnicosTareaProyecto(int $id) {
        $consulta = $this->consulta("select
                                    IdTecnico as  Id,
                                    nombreUsuario(IdTecnico) as Nombre
                                    from t_proyectos_tarea_asistentes
                                    where IdTarea = '" . $id . "'
                                    and Flag = 1
                                    order by Nombre asc;");
        return $consulta;
    }

    public function cargaTareasProyecto(int $idProyecto = null) {
        $consulta = $this->consulta("select
                                    tpt.Id,
                                    tpt.Nombre,
                                    (select Nombre from t_proyectos_tareas where Id = tpt.IdPredecesora) as Predecesora,
                                    tpt.Inicio,
                                    tpt.Fin,
                                    nombreUsuario(tpt.IdLider) as Lider,
                                    (select GROUP_CONCAT(nombreUsuario(IdTecnico)) as Tecnicos from t_proyectos_tarea_asistentes where IdTarea = tpt.Id) as Tecnicos
                                    from t_proyectos_tareas tpt
                                    where tpt.IdProyecto = '" . $idProyecto . "'");
        return $consulta;
    }

}
