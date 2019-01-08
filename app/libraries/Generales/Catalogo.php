<?php

namespace Librerias\Generales;

use Controladores\Controller_Base_General as General;

/**
 * Description of Catalogo
 *
 * @author AProgrammer
 */
class Catalogo extends General {

    private $DBC;

    public function __construct() {
        parent::__construct();
        $this->DBC = \Modelos\Modelo_Catalogo::factory();
        parent::getCI()->load->helper('date');
        parent::getCI()->load->helper('conversionpalabra');
    }

    /*
     * Metodo para definir operacion a realizar de catalogo areas cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catAreas(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $validar = array('Nombre' => $datos[0]);
                $consulta = $this->DBC->setArticulo('cat_v3_areas_siccob', array('Nombre' => $datos[0], 'Descripcion' => $datos[1], 'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $this->catAreas('3');
                } else {
                    return FALSE;
                }
                break;
            //Actualiza en la tabla
            case '2':
                //nombre de parametro para verificar que permiso no se repita
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_v3_areas_siccob', array(
                    'Nombre' => $datos[1],
                    'Descripcion' => $datos[2],
                    'Flag' => $datos[3]
                        ), array('Id' => $datos[0]),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos[1], $parametro
                );
                if (!empty($consulta)) {
                    return $this->catAreas('3');
                } else {
                    return FALSE;
                }
                break;
            //Obtiene Informacion 
            case '3';
                $flag = (is_null($datos['Flag'])) ? '' : ' AND Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_areas_siccob where Id not in (1,23)' . $flag);
                break;
            default :
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo perfiles
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catPerfiles(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $validar = array('Nombre' => $datos['nombre']);
                if (!empty($datos['permisos'])) {
                    $permisos = implode(',', $datos['permisos']);
                    $consulta = $this->DBC->setArticulo('cat_perfiles', array(
                        'Nombre' => $datos['nombre'],
                        'IdDepartamento' => $datos['departamento'],
                        'Permisos' => $permisos,
                        'Descripcion' => $datos['descripcion'],
                        'Clave' => $datos['clave'],
                        'Cantidad' => $datos['cantidad'],
                        'Nivel' => $datos['nivel'],
                        'Flag' => '1'), $validar);
                } else {
                    $consulta = $this->DBC->setArticulo('cat_perfiles', array(
                        'Nombre' => $datos['nombre'],
                        'IdDepartamento' => $datos['departamento'],
                        'Descripcion' => $datos['descripcion'],
                        'Clave' => $datos['clave'],
                        'Cantidad' => $datos['cantidad'],
                        'Nivel' => $datos['nivel'],
                        'Flag' => '1'), $validar);
                }
                if (!empty($consulta)) {
                    return $this->catPerfiles('3');
                } else {
                    return FALSE;
                }
                break;
            //Actualiza en la tabla
            case '2':
                //nombre de parametro para verificar que permiso no se repita
                $parametro = 'Nombre';
                if (!empty($datos['permisos'])) {
                    $consulta = $this->DBC->actualizarArticulo('cat_perfiles', array(
                        'Nombre' => $datos['nombre'],
                        'IdDepartamento' => $datos['departamento'],
                        'Permisos' => implode(',', $datos['permisos']),
                        'Descripcion' => $datos['descripcion'],
                        'Clave' => $datos['clave'],
                        'Cantidad' => $datos['cantidad'],
                        'Nivel' => $datos['nivel'],
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id']),
                            //Variable para mandar datos de restriccion para que no se repita el nombre
                            $datos['nombre'], $parametro
                    );
                } else {
                    $consulta = $this->DBC->actualizarArticulo('cat_perfiles', array(
                        'Nombre' => $datos['nombre'],
                        'IdDepartamento' => $datos['departamento'],
                        'Permisos' => '',
                        'Descripcion' => $datos['descripcion'],
                        'Clave' => $datos['clave'],
                        'Cantidad' => $datos['cantidad'],
                        'Nivel' => $datos['nivel'],
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id']),
                            //Variable para mandar datos de restriccion para que no se repita el nombre
                            $datos['nombre'], $parametro
                    );
                }
                if (!empty($consulta)) {
                    $tabla = $this->catPerfiles('3');
                    return $tabla;
                } else {
                    return FALSE;
                }
                break;
            //Obtiene Informacion 
            case '3':
                return $this->DBC->getJuntarTablas('SELECT u.Id, d.IdArea, u.IdDepartamento, p.Nombre AS Area, d.Nombre AS Departamento, u.Nombre, Cantidad, u.Clave, u.Permisos, u.Nivel, u.Descripcion, u.Flag FROM cat_perfiles u INNER JOIN cat_v3_departamentos_siccob d on u.IdDepartamento = d.Id JOIN cat_v3_areas_siccob p ON d.IdArea = p.Id where u.Id > 1');
                break;
            default :
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo permisos
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catPermisos(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $validar = array('Permiso' => $datos[1]);
                $consulta = $this->DBC->setArticulo('cat_v3_permisos', array('Nombre' => $datos[0], 'Permiso' => strtoupper($datos[1]), 'Descripcion' => $datos[2]), $validar);
                if (!empty($consulta)) {
                    return $this->catPermisos('3');
                } else {
                    return FALSE;
                }
                break;
            //Actualiza en la tabla
            case '2':
                //nombre de parametro para verificar que permiso no se repita
                $parametro = 'Permiso';
                $consulta = $this->DBC->actualizarArticulo('cat_v3_permisos', array(
                    'Nombre' => $datos[1],
                    'Permiso' => strtoupper($datos[2]),
                    'Descripcion' => $datos[3]
                        ), array('Id' => $datos[0]), $datos[2], $parametro
                );
                if (!empty($consulta)) {
                    return $this->catPermisos('3');
                } else {
                    return FALSE;
                }
                break;
            //Obtiene Informacion 
            case '3':
                return $this->DBC->getArticulos('cat_v3_permisos', $where);
                break;
            default :
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo usuarios
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catUsuarios(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            case '1':
                break;
            //actualiza los usuarios
            case '2' :
                $existeEmail = $this->DBC->getJuntarTablas('SELECT Id FROM cat_v3_usuarios WHERE Email = "' . $datos['email'] . '" AND Id <> "' . $datos['id'] . '"');
                $existeEmailCorporativo = $this->DBC->getJuntarTablas('SELECT Id FROM cat_v3_usuarios WHERE EmailCorporativo = "' . $datos['email'] . '" AND Id <> "' . $datos['id'] . '"');
                if (empty($existeEmail)) {
                    if (empty($existeEmailCorporativo)) {
                        if (!empty($datos['permisos'])) {
                            $permisos = implode(',', $datos['permisos']);
                        } else {
                            $permisos = '';
                        }
                        $consulta = $this->DBC->actualizarUnicoDato('cat_v3_usuarios', array(
                            'IdPerfil' => $datos['perfil'],
                            'EmailCorporativo' => $datos['email'],
                            'PermisosAdicionales' => $permisos,
                            'Flag' => $datos['estatus'],
                            'SDKey' => $datos['SDKey']
                                ), array('Id' => $datos['id'])
                        );
                        if (!empty($consulta)) {
                            $tabla = $this->catUsuarios('3');
                            return $tabla;
                        } else {
                            return FALSE;
                        }
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
                break;
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' AND a.Flag = "' . $datos['Flag'] . '"';
                if (!empty($where)) {
                    return $this->DBC->getJuntarTablas('SELECT 
                        a.Id as IdUsuario,
                        nombreUsuario(a.Id) AS Nombre,
                        a.Usuario,
                        b.Nombre as Perfil, 
                        a.EmailCorporativo, a.SDKey, 
                        b.IdDepartamento 
                        FROM cat_v3_usuarios a 
                        INNER JOIN cat_perfiles b ON b.Id = a.IdPerfil 
                        WHERE b.IdDepartamento = "' . $where['IdDepartamento'] . '"' . $flag);
                } else {
                    return $this->DBC->getJuntarTablas('SELECT
                                                            a.Id, 
                                                            a.Usuario, 
                                                            a.IdPerfil, 
                                                            b.Nombre as Perfil, 
                                                            a.PermisosAdicionales, 
                                                            nombreUsuario(a.Id) AS Nombre, 
                                                            a.Email, 
                                                            a.EmailCorporativo, 
                                                            a.Flag, 
                                                            a.SDKey 
                                                        FROM cat_v3_usuarios a 
                                                        INNER JOIN cat_perfiles b 
                                                            ON b.Id = a.IdPerfil 
                                                        WHERE a.Id > 1' . $flag . '
                                                        ORDER BY Nombre ASC');
                }
                break;
            case '4':
                $query = "SELECT Id, Nombre, Usuario, Email, EmailCorporativo FROM cat_v3_usuarios WHERE Email = '" . $datos['email'] . "' OR EmailCorporativo = '" . $datos['email'] . "'";
                $resultado = $this->DBC->getJuntarTablas($query);
                if (!empty($resultado)) {
                    return $resultado['0'];
                } else {
                    return '';
                }
                break;
            case '5':
                return $this->DBC->getJuntarTablas('select Id, nombreUsuario(Id) as Nombre from cat_v3_usuarios where Id <> 1 order by nombreUsuario(Id);');
                break;
            default :

                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo de departamentos
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catDepartamentos(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $validar = array('Nombre' => $datos['nombre']);
                $consulta = $this->DBC->setArticulo('cat_v3_departamentos_siccob', array('Nombre' => $datos['nombre'], 'IdArea' => $datos['area'], 'Descripcion' => $datos['descripcion'], 'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $this->catDepartamentos('3');
                } else {
                    return FALSE;
                }
                break;
            //Actualiza en la tabla
            case '2':
                //nombre de parametro para verificar que permiso no se repita
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_v3_departamentos_siccob', array(
                    'IdArea' => $datos['area'],
                    'Nombre' => $datos['nombre'],
                    'Descripcion' => $datos['descripcion'],
                    'Flag' => $datos['estatus']
                        ), array('Id' => $datos['id']),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos['nombre'], $parametro
                );
                if (!empty($consulta)) {
                    $tabla = $this->catDepartamentos('3');
                    return $tabla;
                } else {
                    return FALSE;
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' AND u.Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT u.Id, u.IdArea, p.Nombre AS Area, u.Nombre, u.Descripcion, u.Flag FROM cat_v3_departamentos_siccob u INNER JOIN cat_v3_areas_siccob p ON p.Id = u.IdArea where u.Id > 1' . $flag . ' order by u.Nombre');
                break;
            case '5':
                return $this->DBC->getJuntarTablas("select d.Id, concat(d.Nombre,' (',a.Nombre,')') as Departamento from cat_v3_departamentos_siccob d  inner join cat_v3_areas_siccob a on d.IdArea = a.Id where d.Id <> 1 ORDER BY d.Nombre;");
                break;
            default:
                break;
        }
    }

    /*
     * Metodo que se encarga de obtener los servicios de los departamentos
     * 
     */

    public function catServiciosDepartamento(string $operacion, array $datos = null, array $where = null) {
        $respuesta = array();
        switch ($operacion) {
            //Insertar nuevo servicio de departamento
            case '1':

                break;
            //Actaulizar servicio del departamento
            case '2':

                break;
            //Consultar los servicios del departamento
            case '3':
                $respuesta = $this->DBC->getJuntarTablas('call getServiciosDepartamento("' . $datos['departamento'] . '")');
                $this->DBC->limpiarFuncion();
                break;
        }
        return $respuesta;
    }

    /*
     * Metodo para definir operacion a realizar de catalogo clientes cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catClientes(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $validar = array('Nombre' => $datos['nombre']);
                $consulta = $this->DBC->setArticulo('cat_v3_clientes', array(
                    'Nombre' => $datos['nombre'],
                    'RazonSocial' => $datos['razonSocial'],
                    'IdPais' => $datos['pais'],
                    'IdEstado' => $datos['estado'],
                    'IdMunicipio' => $datos['municipio'],
                    'IdColonia' => $datos['colonia'],
                    'Calle' => $datos['calle'],
                    'NoExt' => $datos['ext'],
                    'NoInt' => $datos['int'],
                    'telefono1' => $datos['telefono1'],
                    'telefono2' => $datos['telefono2'],
                    'Email' => $datos['email'],
                    'Web' => $datos['pagina'],
                    'Representante' => $datos['representante'],
                        ), $validar);
                if (!empty($consulta)) {
                    return $this->catClientes('3');
                } else {
                    return FALSE;
                }
                break;
            //Actualiza en la tabla
            case '2':
                //nombre de parametro para verificar que permiso no se repita
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_v3_clientes', array(
                    'Nombre' => strtoupper($datos['nombre']),
                    'RazonSocial' => conversionPalabra($datos['razonSocial']),
                    'Representante' => $datos['representante'],
                    'IdPais' => $datos['pais'],
                    'IdEstado' => $datos['estado'],
                    'IdMunicipio' => $datos['municipio'],
                    'IdColonia' => $datos['colonia'],
                    'Calle' => $datos['calle'],
                    'NoExt' => $datos['ext'],
                    'NoInt' => $datos['int'],
                    'Telefono1' => $datos['telefono1'],
                    'Telefono2' => $datos['telefono2'],
                    'Web' => $datos['pagina'],
                    'Email' => $datos['email']
                        ), array('Id' => $datos['id']),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos['nombre'], $parametro
                );
                if (!empty($consulta)) {
                    return $this->catClientes('3');
                } else {
                    return FALSE;
                }
                break;
            //Obtiene Informacion 
            case '3':
                return $this->DBC->getJuntarTablas('SELECT a.Id, a.Nombre, a.RazonSocial, a.Representante, a.Calle, a.NoInt, a.NoExt, a.Telefono1, a.Telefono2, a.Email, a.Web, b.Id AS IdPais, b.Nombre AS Pais, c.Id AS IdEstado, c.Nombre AS Estado, d.Id AS IdColonia, d.Nombre AS Municipio, e.Id AS IdColonia, e.Nombre AS Colonia, e.CP FROM cat_v3_clientes a INNER JOIN cat_v3_paises b ON b.Id = a.IdPais INNER JOIN cat_v3_estados c ON a.IdEstado = c.Id INNER JOIN cat_v3_municipios d ON a.IdMunicipio = d.Id INNER JOIN cat_v3_colonias e ON a.IdColonia = e.Id');
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo sucursales cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catSucursales(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_sucursales '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND IdCliente = "' . $datos['cliente'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_sucursales', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'NombreCinemex' => conversionPalabra($datos['cinemex']),
                        'IdCliente' => $datos['cliente'],
                        'IdRegionCliente' => $datos['region'],
                        'IdPais' => $datos['pais'],
                        'IdEstado' => $datos['estado'],
                        'IdMunicipio' => $datos['municipio'],
                        'IdColonia' => $datos['colonia'],
                        'Calle' => $datos['calle'],
                        'NoExt' => $datos['ext'],
                        'NoInt' => $datos['int'],
                        'telefono1' => $datos['telefono1'],
                        'telefono2' => $datos['telefono2'],
                        'IdResponsable' => $datos['responsable'],
                        'Permiso' => '0',
                        'Flag' => '1',
                        'IdUnidadNegocio' => $datos['unidadNegocio']
                    ));
                    if (!empty($consulta)) {
                        return $this->catSucursales('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_sucursales '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . '" AND IdCliente = "' . $datos['cliente'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_sucursales', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'NombreCinemex' => conversionPalabra($datos['cinemex']),
                        'IdCliente' => $datos['cliente'],
                        'IdRegionCliente' => $datos['region'],
                        'IdPais' => $datos['pais'],
                        'IdEstado' => $datos['estado'],
                        'IdMunicipio' => $datos['municipio'],
                        'IdColonia' => $datos['colonia'],
                        'Calle' => $datos['calle'],
                        'NoExt' => $datos['ext'],
                        'NoInt' => $datos['int'],
                        'Telefono1' => $datos['telefono1'],
                        'Telefono2' => $datos['telefono2'],
                        'IdResponsable' => $datos['responsable'],
                        'Flag' => $datos['estatus'],
                        'IdUnidadNegocio' => $datos['unidadNegocio']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catSucursales('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' AND a.Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT 
                                                        a.*, 
                                                        cliente(IdCliente) AS Cliente,
                                                        (SELECT NOMBRE FROM cat_v3_regiones_cliente WHERE Id = a.IdRegionCliente) AS Region,
                                                        c.Id AS IdPais, 
                                                        c.Nombre AS Pais, 
                                                        d.Id AS IdEstado, 
                                                        d.Nombre AS Estado, 
                                                        e.Id AS IdMunicipio, 
                                                        e.Nombre AS Municipio, 
                                                        f.Id AS IdColonia, 
                                                        f.Nombre AS Colonia, 
                                                        f.CP, 
                                                        nombreUsuario(IdResponsable) AS Responsable,
                                                        (SELECT Nombre FROM cat_v3_unidades_negocio WHERE Id = a.IdUnidadNegocio) AS UnidadNegocio
                                                    FROM cat_v3_sucursales a  
                                                    INNER JOIN cat_v3_paises c 
                                                            ON c.Id = a.IdPais 
                                                    INNER JOIN cat_v3_estados d 
                                                            ON a.IdEstado = d.Id 
                                                    INNER JOIN cat_v3_municipios e 
                                                            ON a.IdMunicipio = e.Id 
                                                    INNER JOIN cat_v3_colonias f 
                                                            ON a.IdColonia = f.Id ' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo localidades
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @return boolean o array devuelve una array con  la consulta.
     */

    public function catLocalidades(string $operacion, array $where = null) {
        switch ($operacion) {
            //Muestra los datos de la tabla Paises
            case '1':
                $consulta = $this->DBC->getArticulos('cat_v3_paises', $where);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            //Muestra los datos de la tabla Estados
            case '2':
                $consulta = $this->DBC->getArticulos('cat_v3_estados', $where);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            //Muestra los datos de la tabla Municipios
            case '3':
                $consulta = $this->DBC->getArticulos('cat_v3_municipios', $where);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            //Muestra los datos de la tabla Colonias
            case '4';
                $consulta = $this->DBC->getArticulos('cat_v3_colonias', $where);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            //trae IdColonia, IdMunicipio, IdEstado y IdPais 
            case '5':
                $consulta = $this->DBC->getJuntarTablas('SELECT a.CP, a.Id AS IdColonia, b.Id AS IdMunicipio, c.Id AS IdEstado, d.Id AS IdPais FROM cat_v3_colonias a INNER JOIN cat_v3_municipios b ON b.Id = a.IdMunicipio INNER JOIN cat_v3_estados c ON b.IdEstado = c.Id INNER JOIN cat_v3_paises d ON c.IdPais = d.Id WHERE a.CP = ' . $where[0]);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Regiones de Logistica
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catRegionesLogistica(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $validar = array('Nombre' => $datos['nombre']);
                $sucursales = implode(',', $datos['sucursales']);
                $consulta = $this->DBC->setArticulo('cat_v3_regiones_logisticas', array('Nombre' => $datos['nombre'], 'Descripcion' => $datos['descripcion'], 'Sucursales' => $sucursales, 'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $this->catRegionesLogistica('3');
                } else {
                    return FALSE;
                }
                break;
            //Actualiza en la tabla
            case '2':
                //nombre de parametro para verificar que permiso no se repita
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_v3_regiones_logisticas', array(
                    'Nombre' => $datos['nombre'],
                    'Descripcion' => $datos['descripcion'],
                    'Sucursales' => implode(',', $datos['sucursales']),
                    'Flag' => $datos['estatus']
                        ), array('Id' => $datos['id']),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos['nombre'], $parametro
                );
                if (!empty($consulta)) {
                    $tabla = $this->catRegionesLogistica('3');
                    return $tabla;
                } else {
                    return FALSE;
                }
                break;
            //Obtiene Informacion 
            case '3':
                return $this->DBC->getJuntarTablas("select cvrl.Id, cvrl.Nombre, cvrl.Descripcion, (select replace(GROUP_CONCAT(Nombre),',','<br>') from cat_v3_sucursales cvs where Id regexp (replace(cvrl.Sucursales,',','|'))) as Sucursales, cvrl.Flag from cat_v3_regiones_logisticas cvrl");
                break;
            //Obtiene datos para mandar al modal Actualizar Perfil 
            case '4':
                $data = array();
                $data['sucursales'] = $this->catSucursales('3', array('Flag' => '1'));
                $data['idSucursales'] = $this->DBC->getJuntarTablas('SELECT Sucursales FROM cat_v3_regiones_logisticas WHERE Id = ' . $datos[0]);
                $data['flag'] = $this->DBC->getJuntarTablas('SELECT Flag FROM cat_v3_regiones_logisticas WHERE Id = ' . $datos[0]);
                return array('formulario' => parent::getCI()->load->view('Logistica/Modal/ActualizarRegiones', $data, TRUE), 'datos' => $data);
                break;
            default :
                break;
        }
    }

    /* Metodo que obtiene los lineas de los materiales
     * 
     */

    public function catLineaMaterial(string $operacion, array $where = null) {
        switch ($operacion) {
            //Inserta
            case '1':

                break;
            //Actualiza
            case '2':

                break;
            //Obtiene informacion
            case '3':
                $consulta = $this->DBC->getArticulos('cat_v3_lineas_equipo', array('Flag' => '1'));
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /*
     * Metodo que obtiene la lista de material
     * 
     */

    public function catMaterial(string $operacion, array $where = null) {
        switch ($operacion) {
            //Inserta
            case '1':

                break;
            //Actualiza
            case '2':

                break;
            //Obtiene informacion
            case '3':
                $consulta = $this->DBC->getJuntarTablas('
                    select
                        cvle.Id as linea,
                        cvmoe.Id,
                        cvmoe.Nombre,
                        cvmoe.NoParte
                    from 
                    cat_v3_lineas_equipo cvle inner join cat_v3_sublineas_equipo cvse
                    on cvle.Id = cvse.Linea
                    inner join cat_v3_marcas_equipo cvme 
                    on cvse.Id = cvme.Sublinea
                    inner join cat_v3_modelos_equipo cvmoe
                    on cvme.Id = cvmoe.Marca
                    where cvmoe.Flag = 1
                        ');
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo archivos formatos
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catArchivosFormatos(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta
            case '1':

                break;
            //Actualiza
            case '2':

                break;
            //Muestra los datos de la tabla Municipios
            case '3':
                $consulta = $this->DBC->getArticulos('cat_v3_archivos_formatos', $where);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            default :
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo tipos de campos
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catTiposCampo(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta
            case '1':
                break;
            //Actualiza
            case '2':
                break;
            //Muestra los datos de la tabla Municipios
            case '3':
                $consulta = $this->DBC->getArticulos('cat_v3_tipos_campo');
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            default :
                break;
        }
    }

    /*
     * Metodo para hacer una consulta algun catalogo
     * 
     * @param string $datos recibe la consulta en forma de string
     * @return array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catConsultaGeneral(string $datos) {
        $consulta = $this->DBC->getJuntarTablas($datos);
        if (isset($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Metodo para solo para actualizar 
     * 
     * @param string $tabla recibe el nombre de la tabla 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catActualizarUnicoDato(string $tabla, array $datos, array $where) {
        $consulta = $this->DBC->actualizarUnicoDato($tabla, $datos, $where);
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de la lista de todos los tipos de proyectos vigentes
     * 
     * @param string $operacion recibe el tipo de operacion que se va a realizar.
     * @param array $datos Recibe un arreglo de datos que se utilizan en la operacion 1 y 2
     * @param array $where Recibe un arreglo de definiendo las condiciones para la operacion 2.     
     * @return array regresa la informacion solicitada.
     * 
     */

    public function catTiposProyecto(string $operacion, array $datos = null, array $where = null) {
        $informacion = array();
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //Actualiza registro 
                break;
            case '3':
                //Obtiene informacion
                $consulta = $this->DBC->getArticulos('cat_tipos_proyecto', array('Flag' => '1'));
                if (!empty($consulta)) {
                    foreach ($consulta as $value) {
                        array_push($informacion, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
                    }
                }
                break;
        }
        return $informacion;
    }

    /*
     * Encargado de la lista de lideres de proyectos que estan activos
     * 
     * @return array regresa Id y Nombre del lider activo.
     */

    public function catLideres(string $operacion, array $datos = null, array $where = null) {
        $informacion = array();
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //Actualiza registro 
                break;
            case '3':
                //Obtiene informacion
                $consulta = $this->DBC->getJuntarTablas('
                    select 
                       catu.Id,
                       concat(trp.Nombres, " ",trp.ApPaterno, " ",trp.ApMaterno) as Nombre,
                       catu.IdPerfil as Perfil
                    from cat_v3_usuarios catu 
                    inner join t_rh_personal trp 
                    on catu.Id = trp.IdUsuario 
                    where catu.IdPerfil in (24,26,27,42) and Flag = 1');
                if (!empty($consulta)) {
                    foreach ($consulta as $value) {
                        array_push($informacion, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
                    }
                }
                break;
        }
        return $informacion;
    }

    /*
     * Encargado de obtener la lisata de catalogo de tareas de los proyectos.
     * 
     * @return array regresa Id y Nombre del lider activo.
     */

    public function catTareasProyectos(string $operacion, array $datos = null, array $where = null) {
        $informacion = array();
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //Actualiza registro 
                break;
            case '3':
                //Obtiene informacion
                $consulta = $this->DBC->getArticulos('cat_v3_tareas_proyectos', array('Flag' => '1'));
                if (!empty($consulta)) {
                    $informacion = $consulta;
                }
                break;
        }
        return $informacion;
    }

    /*
     * Encargado de la lista de todas las capacitaciones en video
     * 
     * @param string $operacion recibe el tipo de operacion que se va a realizar.
     * @param array $datos Recibe un arreglo de datos que se utilizan en la operacion 1 y 2
     * @param array $where Recibe un arreglo de definiendo las condiciones para la operacion 2.     
     * @return array regresa la informacion solicitada.
     * 
     */

    public function catCapacitacionesVideo(string $operacion, array $datos = null, array $where = null) {
        $informacion = array();
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //Actualiza registro 
                break;
            case '3':
                //Obtiene informacion
                $consulta = $this->DBC->getArticulos('cat_v3_capacitaciones', array('Flag' => '1'));
                if (!empty($consulta)) {
                    foreach ($consulta as $value) {
                        array_push($informacion, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
                    }
                }
                break;
        }
        return $informacion;
    }

    /*
     * Encargado de obetener el catalogo de tipos trafico
     * 
     * @param string $operacion recibe el tipo de operacion que se va a realizar.
     * @param array $datos Recibe un arreglo de datos que se utilizan en la operacion 1 y 2
     * @param array $where Recibe un arreglo de definiendo las condiciones para la operacion 2.     
     * @return array regresa la informacion solicitada.
     * 
     */

    public function catTiposTrafico(string $operacion, array $datos = null, array $where = null) {
        $informacion = array();
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //Actualiza registro 
                break;
            //Obtiene informacion
            case '3':
                $consulta = $this->DBC->getArticulos('cat_v3_tipos_trafico');
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            default :
                break;
        }
    }

    /*
     * Encargado de obetener el catalogo de prioridades
     * 
     * @param string $operacion recibe el tipo de operacion que se va a realizar.
     * @param array $datos Recibe un arreglo de datos que se utilizan en la operacion 1 y 2
     * @param array $where Recibe un arreglo de definiendo las condiciones para la operacion 2.     
     * @return array regresa la informacion solicitada.
     * 
     */

    public function catPrioridades(string $operacion, array $datos = null, array $where = null) {
        $informacion = array();
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //Actualiza registro 
                break;
            //Obtiene informacion
            case '3':
                $consulta = $this->DBC->getArticulos('cat_v3_prioridades');
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            default :
                break;
        }
    }

    /*
     * Encargado de obetener el catalogo de prioridades
     * 
     * @param string $operacion recibe el tipo de operacion que se va a realizar.
     * @param array $datos Recibe un arreglo de datos que se utilizan en la operacion 1 y 2
     * @param array $where Recibe un arreglo de definiendo las condiciones para la operacion 2.     
     * @return array regresa la informacion solicitada.
     * 
     */

    public function catTiposOrigenDestino(string $operacion, array $datos = null, array $where = null) {
        $informacion = array();
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //Actualiza registro 
                break;
            //Obtiene informacion
            case '3':
                $consulta = $this->DBC->getArticulos('cat_v3_tipos_origen_destino');
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            default :
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo proveedores cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catProveedores(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $validar = array('Nombre' => $datos['nombre']);
                $consulta = $this->DBC->setArticulo('cat_v3_proveedores', array(
                    'Nombre' => strtoupper($datos['nombre']),
                    'RazonSocial' => conversionPalabra($datos['razon']),
                    'IdPais' => $datos['pais'],
                    'IdEstado' => $datos['estado'],
                    'IdMunicipio' => $datos['municipio'],
                    'IdColonia' => $datos['colonia'],
                    'Calle' => $datos['calle'],
                    'NoExt' => $datos['ext'],
                    'NoInt' => $datos['int'],
                    'telefono1' => $datos['telefono1'],
                    'telefono2' => $datos['telefono2'],
                    'Flag' => '1',
                        ), $validar);
                if (!empty($consulta)) {
                    return $this->catProveedores('3');
                } else {
                    return FALSE;
                }
                break;
            //Actualiza en la tabla
            case '2':
                //nombre de parametro para verificar que permiso no se repita
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_v3_proveedores', array(
                    'Nombre' => strtoupper($datos['nombre']),
                    'RazonSocial' => conversionPalabra($datos['razon']),
                    'IdPais' => $datos['pais'],
                    'IdEstado' => $datos['estado'],
                    'IdMunicipio' => $datos['municipio'],
                    'IdColonia' => $datos['colonia'],
                    'Calle' => $datos['calle'],
                    'NoExt' => $datos['ext'],
                    'NoInt' => $datos['int'],
                    'Telefono1' => $datos['telefono1'],
                    'Telefono2' => $datos['telefono2'],
                    'Flag' => $datos['estatus']
                        ), array('Id' => $datos['id']),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos['nombre'], $parametro
                );
                if (!empty($consulta)) {
                    return $this->catProveedores('3');
                } else {
                    return FALSE;
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE cvp.Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT 
                                                    cvp.Id, 
                                                    cvp.Nombre, 
                                                    cvp.RazonSocial, 
                                                    cvp.Calle, 
                                                    cvp.NoInt, 
                                                    cvp.NoExt, 
                                                    cvp.Telefono1, 
                                                    cvp.Telefono2, 
                                                    cvp.Flag, 
                                                    cvpa.Id AS IdPais, 
                                                    cvpa.Nombre AS Pais, 
                                                    cve.Id AS IdEstado, 
                                                    cve.Nombre AS Estado, 
                                                    cvm.Id AS IdMunicipio, 
                                                    cvm.Nombre AS Municipio, 
                                                    cvc.Id AS IdColonia, 
                                                    cvc.Nombre AS Colonia, 
                                                    cvc.CP
                                                    FROM cat_v3_proveedores cvp INNER JOIN cat_v3_paises cvpa 
                                                    ON cvpa.Id = cvp.IdPais 
                                                    INNER JOIN cat_v3_estados cve 
                                                    ON cvp.IdEstado = cve.Id 
                                                    INNER JOIN cat_v3_municipios cvm 
                                                    ON cvp.IdMunicipio = cvm.Id 
                                                    INNER JOIN cat_v3_colonias cvc 
                                                    ON cvp.IdColonia = cvc.Id' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo de almacenes virtuales cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catAlmacenesVirtuales(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $validar = array('Nombre' => $datos[0], 'IdResponsable' => $datos[1], 'IdTipoAlmacen' => 4);
                $consulta = $this->DBC->setArticulo('cat_v3_almacenes_virtuales', array('Nombre' => $datos[0], 'IdResponsable' => $datos[1], 'IdTipoAlmacen' => 4, 'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $this->catAlmacenesVirtuales('3');
                } else {
                    return FALSE;
                }
                break;
            //Actualiza en la tabla
            case '2':
                //nombre de parametro para verificar que almacen no se repita
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_v3_almacenes_virtuales', array(
                    'Nombre' => strtoupper($datos[1]),
                    'Responsable' => $datos[2],
                    'Flag' => $datos[3]
                        ), array('Id' => $datos[0]),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos[1], $parametro
                );
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            //Obtiene Informacion 
            case '3';
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                $query = ''
                        . 'select '
                        . 'cav.Id, '
                        . 'cav.Nombre, '
                        . 'ctav.Nombre as Tipo, '
                        . 'cav.IdReferenciaAlmacen as Referencia, '
                        . 'cav.Flag '
                        . 'from cat_v3_almacenes_virtuales cav '
                        . 'inner join cat_v3_tipos_almacenes_virtuales ctav on cav.IdtipoAlmacen = ctav.Id';
                return $this->DBC->getJuntarTablas($query);
                break;
            default :
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo de lineas de equipo cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catLineasEquipo(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $validar = array('Nombre' => $datos[0]);
                $consulta = $this->DBC->setArticulo('cat_v3_lineas_equipo', array('Nombre' => $datos[0], 'Descripcion' => $datos[1], 'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            //Actualiza en la tabla
            case '2':
                //nombre de parametro para verificar que permiso no se repita
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_v3_lineas_equipo', array(
                    'Nombre' => strtoupper($datos[1]),
                    'Descripcion' => $datos[2],
                    'Flag' => $datos[3]
                        ), array('Id' => $datos[0]),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos[1], $parametro
                );
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            //Obtiene Informacion 
            case '3';
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_lineas_equipo' . $flag);
                break;
            default :
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo de sublíneas de equipo cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catSublineasEquipo(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                //Retorna la cantidad de sublíneas que existen en la base 
                //de datos con el mismo nombre y pertencen a la misma línea.
                $existe = $this->catSublineasEquipo('4', $datos);
                if (!in_array($existe, ['', null, 'null', 0, '0'])) {
                    return FALSE;
                } else {
                    $consulta = $this->DBC->setArticulo('cat_v3_sublineas_equipo', array('Linea' => $datos['linea'], 'Nombre' => strtoupper($datos['nombre']), 'Descripcion' => $datos['descripcion'], 'Flag' => '1'));
                    if (!empty($consulta)) {
                        return $this->catSublineasEquipo('3');
                    } else {
                        return FALSE;
                    }
                }
                break;
            case '2':
                $existe = $this->catSublineasEquipo('4', $datos);
                if (!in_array($existe, ['', null, 'null', 0, '0'])) {
                    return FALSE;
                } else {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_sublineas_equipo', array(
                        'Linea' => $datos['linea'],
                        'Nombre' => strtoupper($datos['nombre']),
                        'Descripcion' => $datos['descripcion'],
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id']));
                    if (!empty($consulta)) {
                        return $this->catSublineasEquipo('3');
                    } else {
                        return $this->catSublineasEquipo('3');
                    }
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE cvse.Flag = ' . $datos['Flag'];
                $query = "
                select 
                cvle.Id as IdLinea,
                cvle.Nombre as Linea,
                cvse.Id as IdSub,
                cvse.Nombre Sublinea,
                cvse.Descripcion,
                cvse.Flag
                from cat_v3_lineas_equipo cvle inner join cat_v3_sublineas_equipo cvse
                on cvle.Id = cvse.Linea " . $flag . "
                order by Flag desc, Linea, Sublinea
                ;";
                return $this->DBC->getJuntarTablas($query);
                break;
            case '4':
                if (array_key_exists('id', $datos)) {
                    $query = "select Id from cat_v3_sublineas_equipo where Linea = '" . $datos['linea'] . "' and Nombre = '" . $datos['nombre'] . "' and Id <> '" . $datos['id'] . "'";
                } else {
                    $query = "select Id from cat_v3_sublineas_equipo where Linea = '" . $datos['linea'] . "' and Nombre = '" . $datos['nombre'] . "'";
                }
                $resultado = $this->DBC->getJuntarTablas($query);
                if (!empty($resultado)) {
                    return $resultado['0']['Id'];
                } else {
                    return '';
                }
                break;
            default :
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo de marca de equipo cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catMarcasEquipo(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                //Retorna la cantidad de sublíneas que existen en la base 
                //de datos con el mismo nombre y pertencen a la misma línea.
                $arrayCampos = [
                    ['campo' => 'Sublinea', 'signo' => '=', 'valor' => $datos['sublinea']],
                    ['campo' => 'Nombre', 'signo' => '=', 'valor' => $datos['nombre']],
                ];
                $existe = $this->DBC->revisaSiExiste('cat_v3_marcas_equipo', $arrayCampos);
                if ($existe !== '') {
                    return FALSE;
                } else {
                    $consulta = $this->DBC->setArticulo('cat_v3_marcas_equipo', array('Sublinea' => $datos['sublinea'], 'Nombre' => strtoupper($datos['nombre']), 'Flag' => '1'));
                    if (!empty($consulta)) {
                        return $this->catMarcasEquipo('3');
                    } else {
                        return FALSE;
                    }
                }
                break;
            case '2':
                $arrayCampos = [
                    ['campo' => 'Sublinea', 'signo' => '=', 'valor' => $datos['sublinea']],
                    ['campo' => 'Nombre', 'signo' => '=', 'valor' => $datos['nombre']],
                    ['campo' => 'Id', 'signo' => '<>', 'valor' => $datos['id']]
                ];
                $existe = $this->DBC->revisaSiExiste('cat_v3_marcas_equipo', $arrayCampos);
                if ($existe !== '') {
                    return FALSE;
                } else {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_marcas_equipo', array(
                        'Sublinea' => $datos['sublinea'],
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id']));
                    if (!empty($consulta)) {
                        return $this->catMarcasEquipo('3');
                    } else {
                        return $this->catMarcasEquipo('3');
                    }
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE cvse.Flag = ' . $datos['Flag'];
                $query = "
                select 
                cvle.Id as IdLinea,                
                cvse.Id as IdSub,
                cvme.Id as IdMar,
                cvme.Nombre as Marca,
                cvse.Nombre as Sublinea,
                cvle.Nombre as Linea,                
                cvme.Flag,
                if(cvme.Flag = 0,'Inactivo','Activo') as Activacion
                from cat_v3_lineas_equipo cvle inner join cat_v3_sublineas_equipo cvse
                on cvle.Id = cvse.Linea
                inner join cat_v3_marcas_equipo cvme
                on cvse.Id = cvme.Sublinea " . $flag . " 
                order by Flag desc, Linea, Sublinea, Marca
                ;";
                return $this->DBC->getJuntarTablas($query);
                break;
            default :
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo de marca de equipo cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catModelosEquipo(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                //Retorna la cantidad de sublíneas que existen en la base 
                //de datos con el mismo nombre y pertencen a la misma línea.
                $arrayCampos = [
                    ['campo' => 'Marca', 'signo' => '=', 'valor' => $datos['marca']],
                    ['campo' => 'Nombre', 'signo' => '=', 'valor' => $datos['nombre']]
                ];
                $existe = $this->DBC->revisaSiExiste('cat_v3_modelos_equipo', $arrayCampos);
                if ($existe !== '') {
                    return FALSE;
                } else {
                    $consulta = $this->DBC->setArticulo('cat_v3_modelos_equipo', array('Marca' => $datos['marca'], 'Nombre' => strtoupper($datos['nombre']), 'NoParte' => strtoupper($datos['parte']), 'Flag' => '1'));
                    if (!empty($consulta)) {
                        $query = 'select * from v_equipos where Id = "' . $consulta['ultimoId'] . '"';
                        $datosModelo = $this->DBC->getJuntarTablas($query);
                        if (!empty($datosModelo)) {
                            $query = ""
                                    . "INSERT INTO Empresa03.dbo.INVE03 "
                                    . "(CVE_ART, DESCR, LIN_PROD, CON_SERIE, UNI_MED, UNI_EMP, TIEM_SURT, STOCK_MIN, STOCK_MAX, TIP_COSTEO, NUM_MON, COMP_X_REC, PEND_SURT, EXIST, COSTO_PROM, ULT_COSTO, CVE_OBS, TIPO_ELE, UNI_ALT, FAC_CONV, APART, CON_LOTE, CON_PEDIMENTO, PESO, VOLUMEN, CVE_ESQIMPU, VTAS_ANL_C, VTAS_ANL_M, COMP_ANL_C, COMP_ANL_M, BLK_CST_EXT, STATUS)"
                                    . "values "
                                    . "('I" . $datosModelo[0]['Id'] . "-P" . $datosModelo[0]['Parte'] . "','" . $datosModelo[0]['Equipo'] . "','ADST', 'N', 'pz', '1', '0', '0', '0', 'P','1','0','0','0','0','0','0','P','pz','1','0','N','N','0','0','1','0','0','0','0','N','A');";
                            \Librerias\Modelos\Base::connectDBSAE()->query($query);
                            $consultaSAE = \Librerias\Modelos\Base::connectDBSAE()->affected_rows();
                        }
                        return $this->catModelosEquipo('3');
                    } else {
                        return FALSE;
                    }
                }
                break;
            case '2':
                $arrayCampos = [
                    ['campo' => 'Marca', 'signo' => '=', 'valor' => $datos['marca']],
                    ['campo' => 'Nombre', 'signo' => '=', 'valor' => $datos['nombre']],
                    ['campo' => 'Id', 'signo' => '<>', 'valor' => $datos['id']]
                ];
                $existe = $this->DBC->revisaSiExiste('cat_v3_modelos_equipo', $arrayCampos);
                if ($existe !== '') {
                    return FALSE;
                } else {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_modelos_equipo', array(
                        'Marca' => $datos['marca'],
                        'Nombre' => strtoupper($datos['nombre']),
                        'NoParte' => strtoupper($datos['parte']),
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id']));
                    if (!empty($consulta)) {
                        $query = 'select * from v_equipos where Id = "' . $datos['id'] . '"';
                        $datosModelo = $this->DBC->getJuntarTablas($query);
                        if (!empty($datosModelo)) {
                            $query = ""
                                    . "UPDATE Empresa03.dbo.INVE03 "
                                    . "SET CVE_ART = 'I" . $datosModelo[0]['Id'] . "-P" . $datosModelo[0]['Parte'] . "', "
                                    . "DESCR = '" . $datosModelo[0]['Equipo'] . "', "
                                    . "STATUS = '" . (($datos['estatus'] == 1) ? 'A' : 'B') . "' "
                                    . "WHERE CVE_ART like 'I" . $datos['id'] . "-%' "
                                    . "AND LIN_PROD = 'ADST'";
                            \Librerias\Modelos\Base::connectDBSAE()->query($query);
                            $consultaSAE = \Librerias\Modelos\Base::connectDBSAE()->affected_rows();
                        }
                        return $this->catModelosEquipo('3');
                    } else {
                        return $this->catModelosEquipo('3');
                    }
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE cvmoe.Flag = ' . $datos['Flag'];
                $query = "
                select 
                cvle.Id as IdLinea,                
                cvse.Id as IdSub,
                cvme.Id as IdMar,
                cvmoe.Id as IdMod,
                cvmoe.Nombre as Modelo,
                cvmoe.NoParte as Parte,
                cvme.Nombre as Marca,
                cvse.Nombre as Sublinea,
                cvle.Nombre as Linea,                
                cvme.Flag,
                if(cvmoe.Flag = 0,'Inactivo','Activo') as Activacion
                from cat_v3_lineas_equipo cvle inner join cat_v3_sublineas_equipo cvse
                on cvle.Id = cvse.Linea
                inner join cat_v3_marcas_equipo cvme
                on cvse.Id = cvme.Sublinea
                inner join cat_v3_modelos_equipo cvmoe
                on cvme.Id = cvmoe.Marca " . $flag . "
                order by Flag desc, Linea, Sublinea, Marca, Modelo;";
                return $this->DBC->getJuntarTablas($query);
                break;
            case '4':
                $consulta = $this->DBC->consulta("select * from v_equipos order by Equipo;");
                return $consulta;
                break;
            default :
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo de componentes de equipo cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catComponentesEquipo(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                //Retorna la cantidad de sublíneas que existen en la base 
                //de datos con el mismo nombre y pertencen a la misma línea.
                $arrayCampos = [
                    ['campo' => 'IdModelo', 'signo' => '=', 'valor' => $datos['equipo']],
                    ['campo' => 'Nombre', 'signo' => '=', 'valor' => $datos['nombre']]
                ];
                $existe = $this->DBC->revisaSiExiste('cat_v3_componentes_equipo', $arrayCampos);
                if ($existe !== '') {
                    return FALSE;
                } else {
                    $consulta = $this->DBC->setArticulo('cat_v3_componentes_equipo', array('IdModelo' => $datos['equipo'], 'Nombre' => strtoupper($datos['nombre']), 'NoParte' => strtoupper($datos['parte']), 'Flag' => '1'));
                    if (!empty($consulta)) {
                        $query = 'select
                        (select Nombre from cat_v3_lineas_equipo where Id = 
                                (select Linea from cat_v3_sublineas_equipo where Id = 
                                        (select Sublinea from cat_v3_marcas_equipo where Id = 
                                                (select Marca from cat_v3_modelos_equipo where Id = cvce.IdModelo)
                                        )
                                )
                        ) as Linea,
                        (select Nombre from cat_v3_modelos_equipo where Id = cvce.IdModelo) as Modelo,
                        cvce.Id,
                        cvce.Nombre,
                        cvce.NoParte
                        from cat_v3_componentes_equipo cvce 
                        where cvce.Id = "' . $consulta['ultimoId'] . '"';
                        $datosComponente = $this->DBC->getJuntarTablas($query);
                        if (!empty($datosComponente)) {
                            $query = ""
                                    . "INSERT INTO Empresa03.dbo.INVE03 "
                                    . "(CVE_ART, DESCR, LIN_PROD, CON_SERIE, UNI_MED, UNI_EMP, TIEM_SURT, STOCK_MIN, STOCK_MAX, TIP_COSTEO, NUM_MON, COMP_X_REC, PEND_SURT, EXIST, COSTO_PROM, ULT_COSTO, CVE_OBS, TIPO_ELE, UNI_ALT, FAC_CONV, APART, CON_LOTE, CON_PEDIMENTO, PESO, VOLUMEN, CVE_ESQIMPU, VTAS_ANL_C, VTAS_ANL_M, COMP_ANL_C, COMP_ANL_M, BLK_CST_EXT, STATUS)"
                                    . "values "
                                    . "('IC" . $datosComponente[0]['Id'] . "-P" . $datosComponente[0]['NoParte'] . "','" . $datosComponente[0]['Nombre'] . " DE " . $datosComponente[0]['Linea'] . " " . $datosComponente[0]['Modelo'] . " ','ADSTC', 'N', 'pz', '1', '0', '0', '0', 'P','1','0','0','0','0','0','0','P','pz','1','0','N','N','0','0','1','0','0','0','0','N','A');";
                            \Librerias\Modelos\Base::connectDBSAE()->query($query);
                            $consultaSAE = \Librerias\Modelos\Base::connectDBSAE()->affected_rows();
                        }
                        return $this->catComponentesEquipo('3');
                    } else {
                        return FALSE;
                    }
                }
                break;
            case '2':
                $arrayCampos = [
                    ['campo' => 'IdModelo', 'signo' => '=', 'valor' => $datos['equipo']],
                    ['campo' => 'Nombre', 'signo' => '=', 'valor' => $datos['nombre']],
                    ['campo' => 'Id', 'signo' => '<>', 'valor' => $datos['id']]
                ];
                $existe = $this->DBC->revisaSiExiste('cat_v3_componentes_equipo', $arrayCampos);
                if ($existe !== '') {
                    return FALSE;
                } else {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_componentes_equipo', array(
                        'IdModelo' => $datos['equipo'],
                        'Nombre' => strtoupper($datos['nombre']),
                        'NoParte' => strtoupper($datos['parte']),
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id']));
                    if (!empty($consulta)) {
                        $query = "select
                        (select Nombre from cat_v3_lineas_equipo where Id = 
                                (select Linea from cat_v3_sublineas_equipo where Id = 
                                        (select Sublinea from cat_v3_marcas_equipo where Id = 
                                                (select Marca from cat_v3_modelos_equipo where Id = cvce.IdModelo)
                                        )
                                )
                        ) as Linea,
                        (select Nombre from cat_v3_modelos_equipo where Id = cvce.IdModelo) as Modelo,
                        cvce.Id,
                        cvce.Nombre,
                        cvce.NoParte
                        from cat_v3_componentes_equipo cvce 
                        where cvce.Id = '" . $datos['id'] . "'";
                        $datosComponente = $this->DBC->getJuntarTablas($query);
                        if (!empty($datosComponente)) {
                            $query = ""
                                    . "UPDATE Empresa03.dbo.INVE03 "
                                    . "SET CVE_ART = 'IC" . $datosComponente[0]['Id'] . "-P" . $datosComponente[0]['NoParte'] . "', "
                                    . "DESCR = '" . $datosComponente[0]['Nombre'] . " DE " . $datosComponente[0]['Linea'] . " " . $datosComponente[0]['Modelo'] . "', "
                                    . "STATUS = '" . (($datos['estatus'] == 1) ? 'A' : 'B') . "' "
                                    . "WHERE CVE_ART like 'IC" . $datos['id'] . "-%' "
                                    . "AND LIN_PROD = 'ADSTC'";
                            \Librerias\Modelos\Base::connectDBSAE()->query($query);
                            $consultaSAE = \Librerias\Modelos\Base::connectDBSAE()->affected_rows();
                        }
                        return $this->catComponentesEquipo('3');
                    } else {
                        return $this->catComponentesEquipo('3');
                    }
                }
                break;
            //Obtiene Informacion 
            case '3':
                $query = "
                select 
                cvce.IdModelo as IdMod,
                cvce.Id as IdCom,
                cvce.Nombre as Componente,
                cvce.NoParte as Parte,
                ve.Equipo,
                cvce.Flag,
                if(cvce.Flag = 0,'Inactivo','Activo') as Activacion

                from cat_v3_componentes_equipo cvce inner join v_equipos ve
                on cvce.IdModelo = ve.Id
                order by cvce.Flag desc, Equipo, Componente;";
                return $this->DBC->getJuntarTablas($query);
                break;
            default :
                break;
        }
    }

    /*
     * Encargado de listar los tipos de envio que hay
     * 
     */

    public function catVistaEquipo(string $operacion) {
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //Actualiza registro 
                break;
            case '3':
                //Obtiene la informacion
                $consulta = $this->DBC->getJuntarTablas('select Id, Equipo as Nombre from v_equipos order by Equipo');
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /*
     * Encargado de listar los tipos de envio que hay
     * 
     */

    public function catTiposEnvio(string $operacion) {
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //Actualiza registro 
                break;
            case '3':
                //Obtiene la informacion
                $consulta = $this->DBC->getJuntarTablas('select * from cat_v3_tipos_envio where Flag = 1');
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /*
     * Encargado de listar los tipos de envio que hay
     * 
     */

    public function catTiposConsolidados(string $operacion) {
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //Actualiza registro 
                break;
            case '3':
                //Obtiene la informacion
                $consulta = $this->DBC->getJuntarTablas('select * from cat_v3_consolidados where Flag = 1');
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /*
     * Encargado de listar los tipos de envio que hay
     * 
     */

    public function catTiposPaqueteria(string $operacion) {
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //Actualiza registro 
                break;
            case '3':
                //Obtiene la informacion
                $consulta = $this->DBC->getJuntarTablas('select * from cat_v3_paqueterias where Flag = 1');
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /*
     * Metodo para insertar, actualizar o mostrar la información de los equipos de SAE
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe los datos para la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catEquiposSAE(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
            //Actualiza en la tabla
            case '2':
            //Obtiene Informacion 
            case '3';
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_equipos_sae' . $flag);
                break;
            default :
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo areas de atencion cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catAreasAtencion(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_areas_atencion '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND IdCliente = "' . $datos['cliente'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_areas_atencion', array(
                        'Nombre' => $datos['nombre'],
                        'IdCliente' => $datos['cliente'],
                        'Descripcion' => $datos['descripcion'],
                        'Flag' => '1'
                    ));
                    if (!empty($consulta)) {
                        return $this->catAreasAtencion('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_areas_atencion '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . '" AND IdCliente = "' . $datos['cliente'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_areas_atencion', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'IdCliente' => $datos['cliente'],
                        'Descripcion' => $datos['descripcion'],
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catAreasAtencion('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT *, cliente(IdCliente) AS Cliente FROM cat_v3_areas_atencion' . $flag);
                break;
            default:
                break;
        }
    }

    /* Metodo para manejar eventos del catálogo de Estatus */

    public function catStatus(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
            //Actualiza en la tabla
            case '2':
            //Obtiene Informacion 
            case '3';
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('select Id, Nombre from cat_v3_estatus ' . $flag . ' order by Nombre');
                break;
            case '5';
                return $this->DBC->getJuntarTablas('select Id, Nombre from cat_v3_estatus where Id not in (11,13) order by Nombre');
                break;
            default :
                break;
        }
    }

    /* Metodo para manejar eventos del catálogo de Estatus */

    public function catTiposServicio(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
            //Actualiza en la tabla
            case '2':
            //Obtiene Informacion 
            case '3';
                return $this->DBC->getJuntarTablas("select s.Id, concat(s.Nombre,' (',if(d.Nombre is not null, d.Nombre,''),')') as Nombre from cat_v3_servicios_departamento s LEFT JOIN cat_v3_departamentos_siccob d on s.IdDepartamento = d.Id order by s.Nombre;");
                break;
            default :
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo regiones cliente cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catRegionesCliente(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_regiones_cliente '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND IdCliente = "' . $datos['cliente'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_regiones_cliente', array(
                        'IdCliente' => $datos['cliente'],
                        'IdResponsableInterno' => $datos['responsableInterno'],
                        'Nombre' => strtoupper($datos['nombre']),
                        'ResponsableCliente' => $datos['responsableCliente'],
                        'Email' => $datos['emailResposableCliente'],
                        'Flag' => '1',
                    ));
                    if (!empty($consulta)) {
                        return $this->catRegionesCliente('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_regiones_cliente '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . '" AND IdCliente = "' . $datos['cliente'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_regiones_cliente', array(
                        'IdCliente' => $datos['cliente'],
                        'IdResponsableInterno' => $datos['responsableInterno'],
                        'Nombre' => strtoupper($datos['nombre']),
                        'ResponsableCliente' => $datos['responsableCliente'],
                        'Email' => $datos['emailResposableCliente'],
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catRegionesCliente('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT
                                                        *, 
                                                        cliente(IdCliente) AS Cliente, 
                                                        nombreUsuario(IdResponsableInterno) AS ResponsableInterno 
                                                    FROM cat_v3_regiones_cliente ' . $flag . ' order by Nombre');
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Clasificacion de Fallas cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catClasificacionFallas(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_clasificaciones_falla '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_clasificaciones_falla', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'Descripcion' => $datos['descripcion'],
                        'Flag' => '1',
                    ));
                    if (!empty($consulta)) {
                        return $this->catClasificacionFallas('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_clasificaciones_falla '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_clasificaciones_falla', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'Descripcion' => $datos['descripcion'],
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catClasificacionFallas('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_clasificaciones_falla' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Tipo de Fallas cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catTiposFallas(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_tipos_falla '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" AND IdClasificacion = "' . $datos['clasificacion'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_tipos_falla', array(
                        'IdClasificacion' => $datos['clasificacion'],
                        'Nombre' => strtoupper($datos['nombre']),
                        'Descripcion' => $datos['descripcion'],
                        'Flag' => '1',
                    ));
                    if (!empty($consulta)) {
                        return $this->catTiposFallas('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_tipos_falla '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . ' "AND IdClasificacion = "' . $datos['clasificacion'] . '"');
                if (empty($verificarExistente) || $datos['estatus'] == 0) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_tipos_falla', array(
                        'IdClasificacion' => $datos['clasificacion'],
                        'Nombre' => strtoupper($datos['nombre']),
                        'Descripcion' => $datos['descripcion'],
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catTiposFallas('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' AND cvtf.Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT
                                                    cvtf.*, 
                                                    (SELECT Nombre FROM cat_v3_clasificaciones_falla WHERE Id = cvtf.IdClasificacion) AS Clasificacion 
                                                FROM cat_v3_tipos_falla cvtf ' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Fallas Equipo, cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catFallasEquipo(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_fallas_equipo '
                        . 'WHERE Nombre = "' . strtoupper($datos['falla']) . '" AND IdTipoFalla = "' . $datos['tipoFalla'] . '" AND IdModeloEquipo = "' . $datos['equipo'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_fallas_equipo', array(
                        'IdTipoFalla' => $datos['tipoFalla'],
                        'IdModeloEquipo' => $datos['equipo'],
                        'Nombre' => strtoupper($datos['falla']),
                        'Flag' => '1',
                    ));
                    if (!empty($consulta)) {
                        return $this->catFallasEquipo('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_fallas_equipo '
                        . 'WHERE Nombre = "' . strtoupper($datos['falla']) . '" '
                        . 'AND Id <> "' . $datos['id'] . ' " '
                        . 'AND IdTipoFalla = "' . $datos['tipoFalla'] . '" '
                        . 'AND IdModeloEquipo = "' . $datos['equipo'] . '"');
                if (empty($verificarExistente) || $datos['estatus'] == 0) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_fallas_equipo', array(
                        'IdTipoFalla' => $datos['tipoFalla'],
                        'IdModeloEquipo' => $datos['equipo'],
                        'Nombre' => strtoupper($datos['falla']),
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catFallasEquipo('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE cvfe.Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT 
                                                        cvfe.*,
                                                        (SELECT CONCAT ((SELECT Nombre FROM cat_v3_clasificaciones_falla WHERE Id = cvtf.IdClasificacion), " - ", cvtf.Nombre)) AS NombreTipoFalla,
                                                        (SELECT Equipo FROM v_equipos WHERE Id = cvfe.IdModeloEquipo) AS NombreEquipo
                                                    FROM cat_v3_fallas_equipo cvfe
                                                    INNER JOIN cat_v3_tipos_falla cvtf
                                                     ON cvtf.Id = cvfe.IdTipoFalla ' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Fallas Refaccion, cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catFallasRefaccion(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_fallas_refaccion '
                        . 'WHERE Nombre = "' . strtoupper($datos['falla']) . '" AND IdTipoFalla = "' . $datos['tipoFalla'] . '" AND IdRefaccion = "' . $datos['refaccion'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_fallas_refaccion', array(
                        'IdTipoFalla' => $datos['tipoFalla'],
                        'IdRefaccion' => $datos['refaccion'],
                        'Nombre' => strtoupper($datos['falla']),
                        'Flag' => '1',
                    ));
                    if (!empty($consulta)) {
                        return $this->catFallasRefaccion('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_fallas_refaccion '
                        . 'WHERE Nombre = "' . strtoupper($datos['falla']) . '" '
                        . 'AND Id <> "' . $datos['id'] . ' " AND IdTipoFalla = "' . $datos['tipoFalla'] . '" AND IdRefaccion = "' . $datos['refaccion'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_fallas_refaccion', array(
                        'IdTipoFalla' => $datos['tipoFalla'],
                        'IdRefaccion' => $datos['refaccion'],
                        'Nombre' => strtoupper($datos['falla']),
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catFallasRefaccion('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE cvfe.Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT 
                                                        cvfr.*,
                                                        cvce.Nombre AS NombreRefaccion,
                                                        (SELECT CONCAT ((SELECT Nombre FROM cat_v3_clasificaciones_falla WHERE Id = cvtf.IdClasificacion), " - ", cvtf.Nombre)) AS NombreTipoFalla,
                                                        (SELECT Equipo FROM v_equipos WHERE Id = cvce.IdModelo) AS NombreEquipo
                                                    FROM cat_v3_fallas_refaccion cvfr
                                                    INNER JOIN cat_v3_tipos_falla cvtf
                                                        ON cvtf.Id = cvfr.IdTipoFalla
                                                    INNER JOIN cat_v3_componentes_equipo cvce
                                                        ON cvce.Id = cvfr.IdRefaccion' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Soluciones de Equipo, cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catSolucionesEquipo(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_soluciones_equipo '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" AND IdModelo = "' . $datos['equipo'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_soluciones_equipo', array(
                        'IdModelo' => $datos['equipo'],
                        'Nombre' => $datos['nombre'],
                        'Descripcion' => $datos['descripcion'],
                        'Flag' => '1'
                    ));
                    if (!empty($consulta)) {
                        return $this->catSolucionesEquipo('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_soluciones_equipo '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . ' " AND Nombre = "' . $datos['nombre'] . '" AND IdModelo = "' . $datos['equipo'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_soluciones_equipo', array(
                        'IdModelo' => $datos['equipo'],
                        'Nombre' => $datos['nombre'],
                        'Descripcion' => $datos['descripcion'],
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catSolucionesEquipo('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE cvse.Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT 
                                                        cvse.*,
                                                        (SELECT Equipo FROM v_equipos WHERE Id = cvse.IdModelo) Equipo
                                                    FROM cat_v3_soluciones_equipo cvse' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Unidades Negocio, cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catUnidadeNegocio(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                break;
            //Actualiza en la tabla
            case '2':
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_unidades_negocio' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Cinemex Validadores, cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catCinemexValidadores(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                break;
            //Actualiza en la tabla
            case '2':
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($where['Flag'])) ? '' : ' WHERE Flag = ' . $where['Flag'];
                return $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_cinemex_validadores' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Tipos Sistemas Salas X4D cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catX4DTiposSistema(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_x4d_tipos_sistema '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_x4d_tipos_sistema', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => '1',
                    ));
                    if (!empty($consulta)) {
                        return $this->catX4DTiposSistema('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_x4d_tipos_sistema '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_x4d_tipos_sistema', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catX4DTiposSistema('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_x4d_tipos_sistema' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Equipos Salas X4D cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catX4DEquipos(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_x4d_equipos '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_x4d_equipos', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => '1',
                    ));
                    if (!empty($consulta)) {
                        return $this->catX4DEquipos('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_x4d_equipos '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_x4d_equipos', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catX4DEquipos('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_x4d_equipos' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Marcas Salas X4D cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catX4DMarcas(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_x4d_marcas '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" ');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_x4d_marcas', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => '1',
                    ));
                    if (!empty($consulta)) {
                        return $this->catX4DMarcas('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_x4d_marcas '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . '" ');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_x4d_marcas', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catX4DMarcas('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_x4d_marcas cvxm' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Modelos Salas X4D cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catX4DModelos(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_x4d_elementos '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND IdEquipo = "' . $datos['equipo'] . '" '
                        . 'AND IdMarca = "' . $datos['marca'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_x4d_elementos', array(
                        'IdEquipo' => $datos['equipo'],
                        'IdMarca' => $datos['marca'],
                        'ClaveSAE' => $datos['cvesae'],
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => '1',
                    ));
                    if (!empty($consulta)) {
                        return $this->catX4DModelos('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_x4d_elementos '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . ' " '
                        . 'AND IdEquipo = "' . $datos['equipo'] . '" '
                        . 'AND IdMarca = "' . $datos['marca'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_x4d_elementos', array(
                        'IdEquipo' => $datos['equipo'],
                        'IdMarca' => $datos['marca'],
                        'ClaveSAE' => $datos['cvesae'],
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catX4DModelos('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE cxele.Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('select 
                                                    cxele.Id,
                                                    cxele.Nombre,
                                                    cxele.IdEquipo as IdLinea,
                                                    cxe.Nombre as Linea,
                                                    cxele.IdMarca,
                                                    cxm.Nombre as Marca,
                                                    cxele.ClaveSAE,
                                                    cxele.Flag
                                                    from cat_v3_x4d_elementos cxele 
                                                    inner join cat_v3_x4d_equipos cxe on cxele.IdEquipo = cxe.Id
                                                    inner join cat_v3_x4d_marcas cxm on cxele.IdMarca = cxm.Id ' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Componentes Salas X4D cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catX4DComponentes(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_x4d_subelementos '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND IdElemento = "' . $datos['modelo'] . '" '
                        . 'AND IdMarca = "' . $datos['marca'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_x4d_subelementos', array(
                        'IdElemento' => $datos['modelo'],
                        'IdMarca' => $datos['marca'],
                        'ClaveSAE' => $datos['cvesae'],
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => '1',
                    ));
                    if (!empty($consulta)) {
                        return $this->catX4DComponentes('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_x4d_subelementos '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . ' " '
                        . 'AND IdMarca = "' . $datos['marca'] . '" '
                        . 'AND IdElemento = "' . $datos['modelo'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_x4d_subelementos', array(
                        'IdElemento' => $datos['modelo'],
                        'IdMarca' => $datos['marca'],
                        'ClaveSAE' => $datos['cvesae'],
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catX4DComponentes('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE cxs.Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas("SELECT
                                                    cxs.Id,
                                                    cxs.Nombre,
                                                    cxm.Nombre as Marca,
                                                    concat((select Nombre from cat_v3_x4d_equipos where Id = cxe.IdEquipo),' - ',(select Nombre from cat_v3_x4d_marcas where Id = cxe.IdMarca),' - ',cxe.Nombre) as Elemento,
                                                    cxs.ClaveSAE,
                                                    cxs.Flag
                                                    from cat_v3_x4d_subelementos cxs
                                                    inner join cat_v3_x4d_elementos cxe on cxs.IdElemento = cxe.Id
                                                    inner join cat_v3_x4d_marcas cxm on cxs.IdMarca = cxm.Id " . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Ubicaciones Salas X4D cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function catX4DUbicaciones(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_x4d_ubicaciones '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_x4d_ubicaciones', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => '1',
                    ));
                    if (!empty($consulta)) {
                        return $this->catX4DUbicaciones('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_x4d_ubicaciones '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_x4d_ubicaciones', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catX4DUbicaciones('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_x4d_ubicaciones' . $flag);
                break;
            default:
                break;
        }
    }

    /*
     * Metodo para definir operacion a realizar de catalogo Ubicaciones Salas X4D cuenta con tres
     * 
     * @param string $operacion recibe el numero de la operacion 
     * @param array $datos recibe el nombre de la tabla en BD
     * @param array $where recibe la condicion para la consulta de la tabla en la BD 
     * @return boolean o array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function traerinfobd(string $operacion, array $datos = null, array $where = null) {
        switch ($operacion) {
            //Inserta en la tabla
            case '1':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_cinemex_validadores '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->setArticulo('cat_v3_cinemex_validadores', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'Correo' => $datos['correo'],
                        'Flag' => '1',
                    ));
                    if (!empty($consulta)) {
                        return $this->catCinemexValidaciones('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Actualiza en la tabla
            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_cinemex_validadores '
                        . 'WHERE Nombre = "' . strtoupper($datos['nombre']) . '" '
                        . 'AND Id <> "' . $datos['id'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_cinemex_validadores', array(
                        'Nombre' => strtoupper($datos['nombre']),
                        'Correo' => $datos['correo'],
                        'Flag' => $datos['estatus']
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return $this->catCinemexValidaciones('3');
                    } else {
                        return FALSE;
                    }
                } else {
                    return 'Repetido';
                }
                break;
            //Obtiene Informacion 
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                return $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_cinemex_validadores' . $flag);
                break;
            default:
                break;
        }
    }

    //arbol de datos 

    public function catX4DActividadesMantenimiento(string $operacion, array $datos = null, array $where = null) {
        $informacion = array();
        switch ($operacion) {
            case '1':
                $validar = array('Nombre' => $datos['actividad'], 'IdSistema' => $datos['sistema'], 'IdPadre' => $datos['padre'], 'Flag' => '1');
                $consulta = $this->DBC->setArticulo('cat_v3_actividades_mantto_salas4d', array('Nombre' => strtoupper($datos['actividad']),
                    'IdSistema' => $datos['sistema'],
                    'IdPadre' => $datos['padre'],
                    'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $consulta['ultimoId'];
                } else {
                    return FALSE;
                }
                break;

            case '2':
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_actividades_mantto_salas4d '
                        . 'WHERE Nombre = "' . strtoupper($datos['actividad']) . '" '
                        . 'AND IdSistema = "' . $datos['sistema'] . '" '
                        . 'AND IdPadre = "' . $datos['padre'] . '" '
                        . 'AND Id <> "' . $datos['id'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_actividades_mantto_salas4d', array(
                        'Nombre' => strtoupper($datos['actividad'])
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return true;
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
                break;
            case '3':
                //Obtiene informacion
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                $consulta = $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_actividades_mantto_salas4d' . $flag);
                if (!empty($consulta)) {
                    $informacion = $consulta;
                }
                break;
        }
        return $informacion;
    }

    public function catX4DTiposSistemas(string $operacion, array $datos = null, array $where = null) {
        $informacion = array();
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //actualizar
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_actividades_mantto_salas4d '
                        . 'WHERE Nombre = "' . strtoupper($datos['actividad']) . '" '
                        . 'AND IdSistema = "' . $datos['sistema'] . '" '
                        . 'AND IdPadre = "' . $datos['padre'] . '" '
                        . 'AND Id <> "' . $datos['id'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_actividades_mantto_salas4d', array(
                        'Nombre' => strtoupper($datos['actividad'])
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return true;
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
                break;

            case '3':
                //Obtiene informacion
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];

                $consulta = $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_x4d_tipos_sistema' . $flag);
                if (!empty($consulta)) {
                    $informacion = $consulta;
                }
                break;
        }
        return $informacion;
    }

    //arbol de datos 

    public function catX4DActividadesSeguimiento(string $operacion, array $datos = null, array $where = null) {
        $informacion = array();
        switch ($operacion) {

            case '1':
                $validar = array('Nombre' => $datos['actividad'], 'IdSistema' => $datos['sistema'], 'IdPadre' => $datos['padre'], 'Flag' => '1');
                $consulta = $this->DBC->setArticulo('cat_v3_actividades_mantto_salas4d', array('Nombre' => strtoupper($datos['actividad']),
                    'IdSistema' => $datos['sistema'],
                    'IdPadre' => $datos['padre'],
                    'Flag' => '1'), $validar);


                if (!empty($consulta)) {
                    return $consulta['ultimoId'];
                } else {
                    return FALSE;
                }
                break;

            case '2':
                //actualizar
                $verificarExistente = $this->DBC->getJuntarTablas('SELECT '
                        . 'Id '
                        . 'FROM cat_v3_actividades_mantto_salas4d '
                        . 'WHERE Nombre = "' . strtoupper($datos['actividad']) . '" '
                        . 'AND IdSistema = "' . $datos['sistema'] . '" '
                        . 'AND IdPadre = "' . $datos['padre'] . '" '
                        . 'AND Id <> "' . $datos['id'] . '"');
                if (empty($verificarExistente)) {
                    $consulta = $this->DBC->actualizarUnicoDato('cat_v3_actividades_mantto_salas4d', array(
                        'Nombre' => strtoupper($datos['actividad'])
                            ), array('Id' => $datos['id'])
                    );
                    if (!empty($consulta)) {
                        return true;
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
                break;
            case '3':
                //Obtiene informacion
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                $consulta = $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_actividades_mantto_salas4d' . $flag);
                if (!empty($consulta)) {
                    $informacion = $consulta;
                }
                break;
        }
        return $informacion;
    }

    public function catX4DTiposSistemaSegumiento(string $operacion, array $datos = null, array $where = null) {
        $informacion = array();
        switch ($operacion) {
            case '1':
                //Inserta nuevo registro 
                break;
            case '2':
                //Actualiza registro 
                break;
            case '3':
                //Obtiene informacion
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                $consulta = $this->DBC->getJuntarTablas('SELECT * FROM cat_v3_x4d_tipos_sistema' . $flag);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
        return $informacion;
    }

    /* Metodo que obtiene los estados civiles
     * 
     */

    public function catRhEdoCivil(string $operacion, array $datos = null) {
        switch ($operacion) {
            //Inserta
            case '1':
                $validar = array('Nombre' => $datos['estadoCivil']);
                $consulta = $this->DBC->setArticulo('cat_rh_edo_civil', array(
                    'Nombre' => $datos['estadoCivil'],
                    'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $this->catRhEdoCivil('3', array('Flag' => '1'));
                } else {
                    return FALSE;
                }
                break;
            //Actualiza
            case '2':
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_rh_edo_civil', array(
                    'Nombre' => $datos['nombre'],
                    'Flag' => $datos['estatus']
                        ), array('Id' => $datos['id']),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos['nombre'], $parametro
                );
                if (!empty($consulta)) {
                    return $this->catRhEdoCivil('3');
                } else {
                    return FALSE;
                }
                break;
            //Obtiene informacion
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                $consulta = $this->DBC->getJuntarTablas('SELECT
                            *,
                            estatus(Flag) Estatus
                        FROM cat_rh_edo_civil' . $flag);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /* Metodo que obtiene el sexo
     * 
     */

    public function catRhSexo(string $operacion, array $datos = null) {
        switch ($operacion) {
            //Inserta
            case '1':
                $validar = array('Nombre' => $datos['sexo']);
                $consulta = $this->DBC->setArticulo('cat_rh_sexo', array(
                    'Nombre' => $datos['sexo'],
                    'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $this->catRhSexo('3', array('Flag' => '1'));
                } else {
                    return FALSE;
                }
                break;
            //Actualiza
            case '2':
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_rh_sexo', array(
                    'Nombre' => $datos['nombre'],
                    'Flag' => $datos['estatus']
                        ), array('Id' => $datos['id']),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos['nombre'], $parametro
                );
                if (!empty($consulta)) {
                    return $this->catRhSexo('3');
                } else {
                    return FALSE;
                }
                break;
            //Obtiene informacion
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                $consulta = $this->DBC->getJuntarTablas('SELECT
                            *,
                            estatus(Flag) Estatus
                        FROM cat_rh_sexo' . $flag);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /* Metodo que obtiene el nivel de estudio
     * 
     */

    public function catRhNivelEstudio(string $operacion, array $datos = null) {
        switch ($operacion) {
            //Inserta
            case '1':
                $validar = array('Nombre' => $datos['nivelEstudio']);
                $consulta = $this->DBC->setArticulo('cat_rh_nvl_estudio', array(
                    'Nombre' => $datos['nivelEstudio'],
                    'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $this->catRhNivelEstudio('3', array('Flag' => '1'));
                } else {
                    return FALSE;
                }
                break;
            //Actualiza
            case '2':
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_rh_nvl_estudio', array(
                    'Nombre' => $datos['nombre'],
                    'Flag' => $datos['estatus']
                        ), array('Id' => $datos['id']),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos['nombre'], $parametro
                );
                if (!empty($consulta)) {
                    return $this->catRhNivelEstudio('3');
                } else {
                    return FALSE;
                }
                break;
            //Obtiene informacion
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                $consulta = $this->DBC->getJuntarTablas('SELECT
                            *,
                            estatus(Flag) Estatus
                        FROM cat_rh_nvl_estudio' . $flag);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /* Metodo que obtiene los documentos de estudio
     * 
     */

    public function catRhDocumentosEstudio(string $operacion, array $datos = null) {
        switch ($operacion) {
            //Inserta
            case '1':
                $validar = array('Nombre' => $datos['documentoRecibido']);
                $consulta = $this->DBC->setArticulo('cat_rh_docs_estudio', array(
                    'Nombre' => $datos['documentoRecibido'],
                    'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $this->catRhDocumentosEstudio('3');
                } else {
                    return FALSE;
                }
                break;
            //Actualiza
            case '2':
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_rh_docs_estudio', array(
                    'Nombre' => $datos['nombre'],
                    'Flag' => $datos['estatus']
                        ), array('Id' => $datos['id']),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos['nombre'], $parametro
                );
                if (!empty($consulta)) {
                    return $this->catRhDocumentosEstudio('3');
                } else {
                    return FALSE;
                }
                break;
            //Obtiene informacion
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                $consulta = $this->DBC->getJuntarTablas('SELECT
                            *,
                            estatus(Flag) Estatus
                        FROM cat_rh_docs_estudio' . $flag);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /* Metodo que obtiene habilidades con el idioma
     * 
     */

    public function catRhHabilidadesIdioma(string $operacion, array $datos = null) {
        switch ($operacion) {
            //Inserta
            case '1':
                $validar = array('Nombre' => $datos['idioma']);
                $consulta = $this->DBC->setArticulo('cat_rh_habilidades_idioma', array(
                    'Nombre' => $datos['idioma'],
                    'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $this->catRhHabilidadesIdioma('3', array('Flag' => '1'));
                } else {
                    return FALSE;
                }
                break;
            //Actualiza
            case '2':
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_rh_habilidades_idioma', array(
                    'Nombre' => $datos['nombre'],
                    'Flag' => $datos['estatus']
                        ), array('Id' => $datos['id']),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos['nombre'], $parametro
                );
                if (!empty($consulta)) {
                    return $this->catRhHabilidadesIdioma('3');
                } else {
                    return FALSE;
                }
                break;
            //Obtiene informacion
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                $consulta = $this->DBC->getJuntarTablas('SELECT
                            *,
                            estatus(Flag) Estatus
                        FROM cat_rh_habilidades_idioma' . $flag);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /* Metodo que obtiene habilidades con software
     * 
     */

    public function catRhHabilidadesSoftware(string $operacion, array $datos = null) {
        switch ($operacion) {
            //Inserta
            case '1':
                $validar = array('Nombre' => $datos['software']);
                $consulta = $this->DBC->setArticulo('cat_rh_habilidades_software', array(
                    'Nombre' => $datos['software'],
                    'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $this->catRhHabilidadesSoftware('3', array('Flag' => '1'));
                } else {
                    return FALSE;
                }
                break;
            //Actualiza
            case '2':
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_rh_habilidades_software', array(
                    'Nombre' => $datos['nombre'],
                    'Flag' => $datos['estatus']
                        ), array('Id' => $datos['id']),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos['nombre'], $parametro
                );
                if (!empty($consulta)) {
                    return $this->catRhHabilidadesSoftware('3');
                } else {
                    return FALSE;
                }
                break;
            //Obtiene informacion
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                $consulta = $this->DBC->getJuntarTablas('SELECT
                            *,
                            estatus(Flag) Estatus
                        FROM cat_rh_habilidades_software' . $flag);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /* Metodo que obtiene el nivel de habilidad
     * 
     */

    public function catRhNivelHabilidad(string $operacion, array $datos = null) {
        switch ($operacion) {
            //Inserta
            case '1':
                $validar = array('Nombre' => $datos['nivelHabilidad']);
                $consulta = $this->DBC->setArticulo('cat_rh_nvl_habilidad', array(
                    'Nombre' => $datos['nivelHabilidad'],
                    'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $this->catRhNivelHabilidad('3', array('Flag' => '1'));
                } else {
                    return FALSE;
                }
                break;
            //Actualiza
            case '2':
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_rh_nvl_habilidad', array(
                    'Nombre' => $datos['nombre'],
                    'Flag' => $datos['estatus']
                        ), array('Id' => $datos['id']),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos['nombre'], $parametro
                );
                if (!empty($consulta)) {
                    return $this->catRhNivelHabilidad('3');
                } else {
                    return FALSE;
                }
                break;
            //Obtiene informacion
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                $consulta = $this->DBC->getJuntarTablas('SELECT
                            *,
                            estatus(Flag) Estatus
                        FROM cat_rh_nvl_habilidad' . $flag);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /* Metodo que obtiene el nivel de habilidad
     * 
     */

    public function catRhHabilidadesSistema(string $operacion, array $datos = null) {
        switch ($operacion) {
            //Inserta
            case '1':
                $validar = array('Nombre' => $datos['sistema']);
                $consulta = $this->DBC->setArticulo('cat_rh_habilidades_sistema', array(
                    'Nombre' => $datos['sistema'],
                    'Flag' => '1'), $validar);
                if (!empty($consulta)) {
                    return $this->catRhHabilidadesSistema('3', array('Flag' => '1'));
                } else {
                    return FALSE;
                }
                break;
            //Actualiza
            case '2':
                $parametro = 'Nombre';
                $consulta = $this->DBC->actualizarArticulo('cat_rh_habilidades_sistema', array(
                    'Nombre' => $datos['nombre'],
                    'Flag' => $datos['estatus']
                        ), array('Id' => $datos['id']),
                        //Variable para mandar datos de restriccion para que no se repita el nombre
                        $datos['nombre'], $parametro
                );
                if (!empty($consulta)) {
                    return $this->catRhHabilidadesSistema('3');
                } else {
                    return FALSE;
                }
                break;
            //Obtiene informacion
            case '3':
                $flag = (is_null($datos['Flag'])) ? '' : ' WHERE Flag = ' . $datos['Flag'];
                $consulta = $this->DBC->getJuntarTablas('SELECT
                            *,
                            estatus(Flag) Estatus
                        FROM cat_rh_habilidades_sistema' . $flag);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

}
