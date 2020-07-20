<?php

namespace Librerias\RH;

use Controladores\Controller_Base_General as General;

class Cursos extends General {

    private $DBS;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Cursos::factory();
    }

    public function getCourses() {
        return $this->DBS->getAllCourses();
    }

    public function getMyCourses($idUsuario) {
        $sumaAvance = 0;

        $datos['cursos'] = $this->DBS->getMyCourses($idUsuario);

        if (!empty($datos['cursos'])) {
            $datos['totalCursos'] = count($datos['cursos']);

            $puntosTotales = $datos['totalCursos'] * 100;

            foreach ($datos['cursos'] as $value) {
                $sumaAvance += $value['Porcentaje'];
            }
            $sumaAvance *= 100;
            $sumaAvance = $sumaAvance / $puntosTotales;
            $datos['avance'] = ceil($sumaAvance);
            $datos['feltante'] = 100 - $sumaAvance;
        } else {
            $datos['totalCursos'] = '0';
            $datos['avance'] = '0';
            $datos['feltante'] = '0';
        }

        return $datos;
    }

    public function getProfile() {
        return $this->DBS->getAllProfile();
    }

    public function getCertificate() {
        return $this->DBS->getAllCertificate();
    }

    public function getTypeCourses() {
        return $this->DBS->getTypeCourses();
    }

    public function newCourse(array $infoCourse) {
        $this->DBS->iniciaTransaccion();
        
        $rutaImagen = $this->guardarUnaImagen('image');

        if (!$rutaImagen) {
            $rutaImagen = NULL;
        }

        $datosCursos['nombre'] = $infoCourse['datosCurso'][0];
        $datosCursos['descripcion'] = $infoCourse['datosCurso'][1];
        $datosCursos['url'] = $infoCourse['datosCurso'][2];
        $datosCursos['certificado'] = $infoCourse['datosCurso'][3];
        $datosCursos['costo'] = $infoCourse['datosCurso'][4];
        $datosCursos['imagen'] = $rutaImagen;

        $insertQuery = $this->DBS->insertCourse($datosCursos);

        foreach ($infoCourse['temarios'] as $value) {
            $this->DBS->insertTemaryCourse(array(
                'nombre' => $value['tema'],
                'descripcion' => '',
                'porcentaje' => $value['porcentaje'],
                'idCurso' => $insertQuery['id']
            ));
        }

        foreach ($infoCourse['participantes'] as $value) {
            $this->DBS->insertParticipantsCourse(array(
                'idCurso' => $insertQuery['id'],
                'idPerfil' => $value
            ));
        }
        
        $cursos = $this->getCourses();

        $this->DBS->terminaTransaccion();
        
        if ($this->DBS->estatusTransaccion() === false) {
            $this->DBS->roolbackTransaccion();
            return ['response' => false, 'code' => 400, 'data' => $cursos];
        } else {
            $this->DBS->commitTransaccion();
            return ['response' => true, 'code' => 200];
        }
    }

    public function guardarUnaImagen(string $idEvidencia) {
        if (!empty($_FILES)) {
            $archivos = subirFichero('Cursos', $idEvidencia);
            return $archivos;
        } else {
            return false;
        }
    }

    public function guardarImagen(string $idEvidencia) {
        if (!empty($_FILES)) {
            $CI = parent::getCI();
            $carpeta = 'Cursos/';
            $archivos = implode(',', setMultiplesArchivos($CI, $idEvidencia, $carpeta));
            return $archivos;
        } else {
            return false;
        }
    }

    public function getCourse($idCurso) {
        $arrayIdPerfil = array();
        $arrayPerfiles = array();
        $curso = $this->DBS->getCourseById($idCurso['idCurso']);
        $temasCurso = $this->DBS->getTemaryById($idCurso['idCurso']);
        $perfilesCurso = $this->DBS->getPerfilById($idCurso['idCurso']);
        $datosPerfiles = $this->getProfile();
        $contador = 0;

        foreach ($perfilesCurso as $key => $value) {
            array_push($arrayIdPerfil, $value['idPerfil']);
        }

        foreach ($datosPerfiles as $key => $value) {
            if (!in_array($value['Id'], $arrayIdPerfil)) {
                $arrayPerfiles[$contador]['id'] = $value['Id'];
                $arrayPerfiles[$contador]['text'] = $value['Nombre'];
                $contador++;
            }
        }

        $infoCurso['curso'] = $curso[0];
        $infoCurso['temas'] = $temasCurso;
        $infoCurso['perfiles'] = $perfilesCurso;
        $infoCurso['selectPuesto'] = $arrayPerfiles;

        return $infoCurso;
    }

    public function editCourse($infoCourse) {
        $this->DBS->iniciaTransaccion();
        if (!empty($_FILES)) {
            $rutaImagen = $this->guardarImagen('evidenciasEditarCurso');

            if (!$rutaImagen) {
                $rutaImagen = NULL;
            }

            $datosCursos['imagen'] = $rutaImagen;
        } else {
            $datosCurso = $this->DBS->getCourseById($infoCourse['id']);
            $datosCursos['imagen'] = $datosCurso[0]['imagen'];
        }

        $datosCursos['nombre'] = $infoCourse['curso'][1];
        $datosCursos['url'] = $infoCourse['curso'][2];
        $datosCursos['descripcion'] = $infoCourse['curso'][3];
        $datosCursos['certificado'] = $infoCourse['curso'][4];
        $datosCursos['costo'] = $infoCourse['curso'][5];
        $datosCursos['idCurso'] = $infoCourse['id'];

        $this->DBS->updateCourse($datosCursos);

        $this->DBS->terminaTransaccion();

        if ($this->DBS->estatusTransaccion() === false) {
            $this->DBS->roolbackTransaccion();
            return false;
        } else {
            $this->DBS->commitTransaccion();
            return ['response' => true, 'code' => 200];
        }
    }

    public function deleteCourse($datos) {
        $resultQuery = $this->DBS->deleteCourse($datos['idCurso']);
        if ($resultQuery['code'] == 200) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteElementCourse($datos) {
        $this->DBS->iniciaTransaccion();
        if ($datos['tipoDato'] == 1) {
            $tabla = 't_curso_tema';
            $temariosCurso = $this->DBS->getTemaryById($datos['idCurso']);
            $totalTemasActivos = count($temariosCurso) - 1;
            $porcentajeTemasActivos = 100 / $totalTemasActivos;
            $this->DBS->updateTemaryCourseEdit(array('porcentaje' => $porcentajeTemasActivos), array('idCurso' => $datos['idCurso']));
        } else {
            $tabla = 't_curso_relacion_perfil';
        }

        $this->DBS->deleteElementById($datos['idCurso'], $datos['id'], $tabla);

        $this->DBS->terminaTransaccion();
        if ($this->DBS->estatusTransaccion() === false) {
            $this->DBS->roolbackTransaccion();
            return false;
        } else {
            $this->DBS->commitTransaccion();
            return true;
        }
    }

    public function addElementCourse($datos) {
        $temasCurso = [];
        $perfilesCurso = [];

        if ($datos['tipoDato'] == 1) {
            $resultQuery = $this->DBS->insertTemaryCourseEdit($datos['nombre'], $datos['descripcion'], $datos['porcentaje'], $datos['idCurso']);
            $this->DBS->updateTemaryCourseEdit(array('porcentaje' => $datos['porcentaje']), array('idCurso' => $datos['idCurso']));
            $temasCurso = $this->DBS->getTemaryById($datos['idCurso']);
        } else {
            $resultQuery = $this->DBS->insertParticipantsCourseEdit($datos['idPerfil'], $datos['idCurso']);
            $perfilesCurso = $this->DBS->getPerfilById($datos['idCurso']);
        }

        $info['temas'] = $temasCurso;
        $info['perfiles'] = $perfilesCurso;

        if ($resultQuery['code'] == 200) {
            return ['response' => true, 'info' => $info, 'id' => $resultQuery['id']];
        } else {
            return false;
        }
    }

    public function TemaryCourseByUser($datos) {
        $datosFaltanteAvance = $this->faltanteAvance($datos);
        $infoUsuario['temas'] = $datosFaltanteAvance['temp_array'];
        $infoUsuario['avance'] = $datosFaltanteAvance['avance'];
        $infoUsuario['faltante'] = $datosFaltanteAvance['faltante'];
        $infoUsuario['infoUsuario'] = $this->DBS->getInfoUserCurse($datos);
        $infoUsuario['infoCurso'] = $this->DBS->getCourseById($datos['idCurso']);
        $infoUsuario['cursos'] = $this->DBS->getMyCourses($datos['idUsuario']);

        return $infoUsuario;
    }

    public function faltanteAvance(array $datos) {
        $arregloCursos = [];
        $key_array = [];
        $temp_array = [];
        $i = 0;
        $avance = 0;
        $faltante = 0;
        $temasPorUsuario = $this->DBS->getTemaryCourseByUser($datos);
        $temasPorCurso = $this->DBS->getTemaryById($datos['idCurso']);

        $arregloCursos = array_merge($temasPorUsuario, $temasPorCurso);

        foreach ($arregloCursos as $val) {

            if (!in_array($val['id'], $key_array)) {
                $key_array[$i] = $val['id'];
                $temp_array[$i] = $val;
            }
            $i++;
        }

        foreach ($temp_array as $val) {
            if (isset($val['fechaModificacion'])) {
                $avance += $val['porcentaje'];
            } else {
                $faltante += $val['porcentaje'];
            }
        }

        return array('faltante' => ceil($faltante), 'avance' => ceil($avance), 'temp_array' => $temp_array);
    }

    public function showCourse($idCurso) {
        $sumaAvance = 0;
        $infoCourse = $this->DBS->getDetailCourse($idCurso['idCurso']);

        $informacion['perticipantes'] = $infoCourse;
        $informacion['total'] = count($infoCourse);

        $puntosTotales = $informacion['total'] * 100;

        foreach ($infoCourse as $value) {
            $sumaAvance += $value['Porcentaje'];
        }
        $sumaAvance *= 100;
        $sumaAvance = $sumaAvance / $puntosTotales;
        $informacion['avance'] = $sumaAvance;

        return $informacion;
    }

    public function startCourse($infoUsuario) {
        $resultQuery = $this->DBS->insertStartCourse($infoUsuario);

        if ($resultQuery['code'] == 200) {
            return $this->TemaryCourseByUser($infoUsuario);
        } else {
            return false;
        }
    }

    public function continueCourse($idCurso) {
        $resultQuery = $this->DBS->getTemaryById($idCurso['idCurso']);
        if ($resultQuery) {
            return $resultQuery;
        } else {
            return false;
        }
    }

    public function addEvidence(array $infoAvence) {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $rutaImagen = $this->guardarImagen('evidencias');
        $infoAvence['fechaModificacion'] = $fecha;
        $infoAvence['url'] = $rutaImagen;
        $resultQuery = $this->DBS->saveEvidance($infoAvence, $rutaImagen);

        if ($resultQuery['code'] == 200) {
            return $this->TemaryCourseByUser($infoAvence);
        } else {
            return false;
        }
    }

    public function showEvidence($infoAvence) {
        return $this->DBS->getEvidenceByID($infoAvence['idAvance']);
    }

}
