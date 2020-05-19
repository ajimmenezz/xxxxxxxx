<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Usuario extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de insertar el personal sus datos en secccion informacion personal 
     * y crea tambien al usuario
     * @param array $datos insert los datos para crear el personal y usuario
     */

    public function setAltaPersonal(string $tabla, string $tabla2, array $datos, array $datos2, string $idDatos2) {
        parent::connectDBPrueba()->trans_start();

        $consulta = $this->insertar($tabla, $datos);
        $datos2[$idDatos2] = parent::connectDBPrueba()->insert_id();
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->insertar($tabla2, $datos2);
        $idPersonal = parent::connectDBPrueba()->insert_id();
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 1');
        parent::connectDBPrueba()->trans_complete();
        if (parent::connectDBPrueba()->trans_status() === FALSE) {
            return $this->registerError('Error al realizar operacion de actualizacion');
        }
        parent::connectDBPrueba()->trans_off();
        return $idPersonal;
    }

    /*
     * Encargado de actualiizar tabla
     * 
     * @return array regresa todos los datos de la tabla
     */

    public function ActualizarPersonal(string $tabla, array $datos, array $where) {
        $consulta = $this->actualizar($tabla, $datos, $where);
        return $consulta;
    }

    /*
     * Metodo para verificar si no hay o hay un dato en la BD
     * 
     * @param string $tabla recibe nombre de la tabla 
     * @param array $datos recibe el nombre de la tabla en BD
     * @return true si no hay un dato con ese nombre, false si hay un dato con ese nombre
     */

    public function verficarDatoRepetido(string $tabla, array $dato) {
        $consulta = $this->encontrar($tabla, $dato);
        if (empty($consulta)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Encargado de actualizar la tabla t_minuta
     *  $datos = datos para actualizar
     *  $where = id que necesitamos para saber que campos se modificaran
     */

    public function insertarFoto(array $datos, array $where) {
        $fotoAnterior = $this->consultaTRHPersonal(array('IdUsuario' => $where['Id']));
        $archivosAnteriores = explode(',', $fotoAnterior['UrlFoto']);

        if (!empty($archivosAnteriores[0])) {
            foreach ($archivosAnteriores as $key => $value) {
                eliminarArchivo($value);
            }
        }

        $consulta = $this->actualizar('t_rh_personal', $datos, $where);
        if (isset($consulta)) {
            return true;
        } else {
            return parent::tipoError();
        }
    }

    /*
     * Encargado de mandar los datos para visializar tabla especifica
     * 
     * @return array regresa todos los datos
     */

    public function getPersonal(string $sentencia) {
        $consulta = $this->consulta($sentencia);
        return $consulta;
    }

    public function consultaTRHPersonal(array $data) {
        $consulta = $this->consulta('SELECT 
                                        *,
                                        (SELECT Nombre FROM cat_rh_sexo WHERE Id = IdSexo) Genero,
                                        (SELECT Email FROM cat_v3_usuarios WHERE Id = IdUsuario) Email,
                                        (SELECT Token FROM cat_v3_usuarios WHERE Id = IdUsuario) Token,
                                        (SELECT SDKey FROM cat_v3_usuarios WHERE Id = IdUsuario) SDKey,
                                        (SELECT Firma FROM cat_v3_usuarios WHERE Id = IdUsuario) Firma
                                    FROM t_rh_personal 
                                    WHERE IdUsuario = "' . $data['IdUsuario'] . '"');
        return $consulta[0];
    }

    public function consultaTRHConduccion(array $data) {
        $consulta = $this->consulta('SELECT 
                                        *
                                    FROM t_rh_conduccion 
                                    WHERE IdUsuario = "' . $data['IdUsuario'] . '"');
        if (!empty($consulta)) {
            return $consulta[0];
        } else {
            return array();
        }
    }

    public function consultaTRHAcademicos(array $data) {
        $consulta = $this->consulta('SELECT 
                                        *,
                                        (SELECT Nombre FROM cat_rh_docs_estudio WHERE Id = IdDocumento) Documento,
                                        (SELECT Nombre FROM cat_rh_nvl_estudio WHERE Id = IdNivelEstudio) NivelEstudio
                                    FROM t_rh_academicos 
                                    WHERE IdUsuario = "' . $data['IdUsuario'] . '"
                                    AND Flag = "1"');
        return $consulta;
    }

    public function consultaTRHIdiomas(array $data) {
        $consulta = $this->consulta('SELECT 
                                        *,
                                        (SELECT Nombre FROM cat_rh_habilidades_idioma WHERE Id = Idioma) NombreIdioma,
                                        (SELECT Nombre FROM cat_rh_nvl_habilidad WHERE Id = Comprension) NivelComprension,
                                        (SELECT Nombre FROM cat_rh_nvl_habilidad WHERE Id = Lectura) NivelLectura,
                                        (SELECT Nombre FROM cat_rh_nvl_habilidad WHERE Id = Escritura) NivelEscritura
                                    FROM t_rh_idiomas 
                                    WHERE IdUsuario = "' . $data['IdUsuario'] . '"
                                    AND Flag = "1"');
        return $consulta;
    }

    public function consultaTRHSoftware(array $data) {
        $consulta = $this->consulta('SELECT 
                                        *,
                                        (SELECT Nombre FROM cat_rh_habilidades_software WHERE Id = IdSoftware) Software,
                                        (SELECT Nombre FROM cat_rh_nvl_habilidad WHERE Id = IdNivelHabilidad) Nivel
                                    FROM t_rh_software
                                    WHERE IdUsuario = "' . $data['IdUsuario'] . '"
                                    AND Flag = "1"');
        return $consulta;
    }

    public function consultaTRHSistemas(array $data) {
        $consulta = $this->consulta('SELECT 
                                        *,
                                        (SELECT Nombre FROM cat_rh_habilidades_sistema WHERE Id = IdSistema) Sistema,
                                        (SELECT Nombre FROM cat_rh_nvl_habilidad WHERE Id = IdNivelHabilidad) Nivel
                                    FROM t_rh_sistemas
                                    WHERE IdUsuario = "' . $data['IdUsuario'] . '"
                                    AND Flag = "1"');
        return $consulta;
    }

    public function consultaTRHDependientes(array $data) {
        $consulta = $this->consulta('SELECT 
                                        *
                                    FROM t_rh_dependientes
                                    WHERE IdUsuario = "' . $data['IdUsuario'] . '"
                                    AND Flag = "1"');
        return $consulta;
    }

    public function actualizarCampoTRHPersonal(array $datos) {
        $consulta = $this->actualizar('t_rh_personal', [
            $datos['campo'] => $datos['inputNuevo']], ['IdUsuario' => $datos['id']]);
        return $consulta;
    }

    public function actualizarCampoUsuario(array $datos) {
        $consulta = $this->actualizar('cat_v3_usuarios', [
            $datos['campo'] => $datos['inputNuevo']], ['Id' => $datos['id']]);
        return $consulta;
    }

    public function actualizarCampoTRHAcademicos(array $datos) {
        $consulta = $this->actualizar('t_rh_academicos', [
            'IdNivelEstudio' => $datos['nivelEstudio'],
            'Institucion' => $datos['institucion'],
            'IdDocumento' => $datos['documento'],
            'Desde' => $datos['desde'],
            'Hasta' => $datos['hasta'],
            'FechaMod' => $datos['fechaMod']], ['Id' => $datos['id']]);
        return $consulta;
    }

    public function actualizarCampoTRHIdiomas(array $datos) {
        $consulta = $this->actualizar('t_rh_idiomas', [
            'Idioma' => $datos['nivelIdioma'],
            'Comprension' => $datos['comprension'],
            'Lectura' => $datos['lectura'],
            'Escritura' => $datos['escritura'],
            'Comentarios' => $datos['comentarios'],
            'FechaMod' => $datos['fechaMod']], ['Id' => $datos['id']]);
        return $consulta;
    }

    public function actualizarCampoTRHSoftware(array $datos) {
        $consulta = $this->actualizar('t_rh_software', [
            'IdSoftware' => $datos['nivelSoftware'],
            'IdNivelHabilidad' => $datos['nivel'],
            'Comentarios' => $datos['comentarios'],
            'FechaMod' => $datos['fechaMod']], ['Id' => $datos['id']]);
        return $consulta;
    }

    public function actualizarCampoTRHSistemas(array $datos) {
        $consulta = $this->actualizar('t_rh_sistemas', [
            'IdSistema' => $datos['nivelSistema'],
            'IdNivelHabilidad' => $datos['nivel'],
            'Comentarios' => $datos['comentarios'],
            'FechaMod' => $datos['fechaMod']], ['Id' => $datos['id']]);
        return $consulta;
    }

    public function actualizarCampoTRHDependientes(array $datos) {
        $consulta = $this->actualizar('t_rh_dependientes', [
            'Nombre' => $datos['nombre'],
            'Parentesco' => $datos['parentesco'],
            'FechaNacimiento' => $datos['fechaNacimiento'],
            'FechaMod' => $datos['fechaMod']], ['Id' => $datos['id']]);
        return $consulta;
    }

    public function consultaTokenUsuarios(array $datos) {
        $consulta = $this->consulta('SELECT
                                        Token 
                                    FROM cat_v3_usuarios
                                    WHERE Token = "' . $datos['token'] . '"');
        return $consulta;
    }

    public function actualizarTRHPersonal(array $datos) {
        $consulta = $this->actualizar('t_rh_personal', [
            'FechaNacimiento' => $datos['fechaNacimiento'],
            'PaisNac' => $datos['pais'],
            'EstadoNac' => $datos['estado'],
            'MunicipioNac' => $datos['municipio'],
            'Nacionalidad' => $datos['nacionalidad'],
            'IdSexo' => $datos['sexo'],
            'IdEstadoCivil' => $datos['estadoCivil'],
            'Estatura' => $datos['estatura'],
            'Peso' => $datos['peso'],
            'Sangre' => $datos['tipoSangre'],
            'TallaCamisa' => $datos['tallaCamisa'],
            'TallaPantalon' => $datos['tallaPantalon'],
            'TallaZapatos' => $datos['tallaZapatos'],
            'CURP' => $datos['curp'],
            'NSS' => $datos['nss'],
            'RFC' => $datos['rfc'],
            'Afore' => $datos['numeroAfore'],
            'InstAfore' => $datos['institutoAfore']], ['IdUsuario' => $datos['id']]);
        return $consulta;
    }

    public function actualizarTRHAltaPersonal(array $datos) {
        $consulta = $this->actualizar('t_rh_personal', [
            'FechaNacimiento' => $datos['fechaNacimiento'],
            'PaisNac' => $datos['pais'],
            'EstadoNac' => $datos['estado'],
            'MunicipioNac' => $datos['municipio'],
            'Nacionalidad' => $datos['nacionalidad'],
            'IdSexo' => $datos['sexo'],
            'IdEstadoCivil' => $datos['estadoCivil'],
            'Estatura' => $datos['estatura'],
            'Peso' => $datos['peso'],
            'Sangre' => $datos['tipoSangre'],
            'TallaCamisa' => $datos['tallaCamisa'],
            'TallaPantalon' => $datos['tallaPantalon'],
            'TallaZapatos' => $datos['tallaZapatos'],
            'Afore' => $datos['numeroAfore'],
            'InstAfore' => $datos['institutoAfore']], ['IdUsuario' => $datos['id']]);
        return $consulta;
    }

    public function actualizarTRHConduccion(array $datos) {
        $consulta = $this->actualizar('t_rh_conduccion', [
            'Dominio' => $datos['dominio'],
            'NoLicencia' => $datos['numeroLicencia'],
            'TipoLicencia' => $datos['tipoLicencia'],
            'Antiguedad' => $datos['antiguedad'],
            'Expedicion' => $datos['vigenciaNumeroLicencia'],
            'Vigencia' => $datos['vigenciaTipoLicencia'],
            'FechaMod' => $datos['fechaMod']], ['IdUsuario' => $datos['idUsuario']]);
        return $consulta;
    }

    public function insertarTRHAcademicos(array $datos) {
        $consulta = $this->insertar('t_rh_academicos', ['IdUsuario' => $datos['idUsuario'],
            'IdNivelEstudio' => $datos['nivelEstudio'],
            'Institucion' => $datos['nombreInstituto'],
            'IdDocumento' => $datos['documentoRecibido'],
            'Desde' => $datos['desde'],
            'Hasta' => $datos['hasta'],
            'FechaCaptura' => $datos['fechaCaptura'],
            'Flag' => '1']);
        return $consulta;
    }

    public function insertarTRHIdiomas(array $datos) {
        $consulta = $this->insertar('t_rh_idiomas', ['IdUsuario' => $datos['idUsuario'],
            'Idioma' => $datos['idioma'],
            'Comprension' => $datos['comprension'],
            'Lectura' => $datos['lectura'],
            'Escritura' => $datos['escritura'],
            'Comentarios' => $datos['comentarios'],
            'FechaCaptura' => $datos['fechaCaptura'],
            'Flag' => '1']);
        return $consulta;
    }

    public function insertarTRHSoftware(array $datos) {
        $consulta = $this->insertar('t_rh_software', ['IdUsuario' => $datos['idUsuario'],
            'IdSoftware' => $datos['software'],
            'IdNivelHabilidad' => $datos['nivel'],
            'Comentarios' => $datos['comentarios'],
            'FechaCaptura' => $datos['fechaCaptura'],
            'Flag' => '1']);
        return $consulta;
    }

    public function insertarTRHSistemas(array $datos) {
        $consulta = $this->insertar('t_rh_sistemas', ['IdUsuario' => $datos['idUsuario'],
            'IdSistema' => $datos['sistema'],
            'IdNivelHabilidad' => $datos['nivel'],
            'Comentarios' => $datos['comentarios'],
            'FechaCaptura' => $datos['fechaCaptura'],
            'Flag' => '1']);
        return $consulta;
    }

    public function insertarTRHConduccion(array $datos) {
        $consulta = $this->insertar('t_rh_conduccion', ['IdUsuario' => $datos['idUsuario'],
            'Dominio' => $datos['dominio'],
            'NoLicencia' => $datos['numeroLicencia'],
            'TipoLicencia' => $datos['tipoLicencia'],
            'Antiguedad' => $datos['antiguedad'],
            'Expedicion' => $datos['vigenciaNumeroLicencia'],
            'Vigencia' => $datos['vigenciaTipoLicencia'],
            'FechaCaptura' => $datos['fechaCaptura'],
            'Flag' => '1']);
        return $consulta;
    }

    public function insertarTRHDependientes(array $datos) {
        $consulta = $this->insertar('t_rh_dependientes', ['IdUsuario' => $datos['idUsuario'],
            'Nombre' => $datos['nombre'],
            'Parentesco' => $datos['parentesco'],
            'FechaNacimiento' => $datos['vigencia'],
            'FechaCaptura' => $datos['fechaCaptura'],
            'Flag' => '1']);
        return $consulta;
    }

    public function eliminarTRH(array $datos) {
        $consulta = $this->actualizar($datos['tablaNombre'], [
            'Flag' => '0',
            'FechaMod' => $datos['fechaMod']], ['Id' => $datos['id']]);
        return $consulta;
    }

    public function bajaUsuarios(array $datos) {
        $consulta = $this->insertar('t_altas_bajas_personal', [
            'IdUsuario' => $datos['IdUsuario'],
            'IdPersonal' => $datos['IdPersonal'],
            'IdEstatus' => '47',
            'Fecha' => $datos['Fecha'],
            'FechaEstatus' => $datos['FechaEstatus']
        ]);
        return $consulta;
    }

    public function actualizarFirmaUsuario(array $datos) {
        $consulta = $this->actualizar('cat_v3_usuarios', [
            $datos['campo'] => $datos['firma']], ['Id' => $datos['id']]);
        return $consulta;
    }

    public function actualizarTRHCovid(array $datos) {
        $this->actualizar('t_rh_personal_covid', [
            'ViveConMayores' => $datos['viveConMayores'],
            'PulmonarAsma' => $datos['pulmonarAsma'],
            'Cardiaco' => $datos['cardiaco'],
            'Diabetes' => $datos['diabetes'],
            'Renal' => $datos['renal'],
            'Hepatica' => $datos['hepatica'],
            'TratamientoCancer' => $datos['tratamientoCancer'],
            'Fumador' => $datos['fumador'],
            'Transplantes' => $datos['transplantes'],
            'VIH' => $datos['VIH']], ['IdUsuario' => $datos['idUsuario']]);
    }

    public function insertarTRHCovid(array $datos) {
        $this->insertar('t_rh_personal_covid', [
            'IdUsuario' => $datos['idUsuario'],
            'PulmonarAsma' => $datos['pulmonarAsma'],
            'Cardiaco' => $datos['cardiaco'],
            'Diabetes' => $datos['diabetes'],
            'Renal' => $datos['renal'],
            'Hepatica' => $datos['hepatica'],
            'TratamientoCancer' => $datos['tratamientoCancer'],
            'Fumador' => $datos['fumador'],
            'Renal' => $datos['renal'],
            'Transplantes' => $datos['transplantes'],
            'VIH' => $datos['VIH']]);
    }

    public function consultaTRHCovid(array $datos) {
        $consulta = $this->consulta('SELECT
                                        * 
                                    FROM t_rh_personal_covid
                                    WHERE IdUsuario = "' . $datos['idUsuario'] . '"');
        return $consulta;
    }

}
