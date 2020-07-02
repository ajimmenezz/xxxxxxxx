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

    public function getMyCourses($perfil) {
        return $this->DBS->getMyCourses($perfil);
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

    public function newCourse($infoCourse) {
        $rutaImagen = null;
        if (isset($infoCourse['curso'])) {
            if (!empty($_FILES)) {
                $myArray = json_decode(json_encode($infoCourse['curso']), true);
                var_dump($myArray);
//                $rutaImagen = $this->guardarImagen($infoCourse['curso']);
            }
            $insertQuery = $this->DBS->insertCourse($infoCourse['curso'], $rutaImagen);
        }

        if ($insertQuery['code'] == 200) {
            foreach ($infoCourse['temario']['infoTabla'] as $value) {
                $this->DBS->insertTemaryCourse($value, $insertQuery['id']);
            }

            foreach ($infoCourse['participantes'] as $value) {
                $this->DBS->insertParticipantsCourse($value[0], $insertQuery['id']);
            }
        } else {
            return ['response' => false, 'code' => 400];
        }

        return ['response' => true, 'code' => 200];
    }
    
    public function guardarImagen($infoCourse) {
        if (!empty($_FILES)) {
            $CI = parent::getCI();
            $carpeta = 'Cursos/';
            $archivos = implode(',', setMultiplesArchivos($CI, 'evidenciasIncapacidad', $carpeta));
            return $archivos;
        } else {
            return false;
        }
    }

    public function getCourse($idCurso) {
        $curso = $this->DBS->getCourseById($idCurso['idCurso']);
        $temasCurso = $this->DBS->getTemaryById($idCurso['idCurso']);
        $perfilesCurso = $this->DBS->getPerfilById($idCurso['idCurso']);

        $infoCurso['curso'] = $curso[0];
        $infoCurso['temas'] = $temasCurso;
        $infoCurso['perfiles'] = $perfilesCurso;

        return $infoCurso;
    }

    public function editCourse($infoCourse) {
        if (isset($infoCourse['curso'])) {
            $updateQuery = $this->DBS->updateCourse($infoCourse['curso']);
        }
        return ['response' => true, 'code' => 200];
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
        if ($datos['tipoDato'] == 1) {
            $tabla = 't_curso_tema';
        } else {
            $tabla = 't_curso_relacion_perfil';
        }
        $resultQuery = $this->DBS->deleteElementById($datos['idCurso'], $tabla);
        if ($resultQuery['code'] == 200) {
            return true;
        } else {
            return false;
        }
    }

    public function addElementCourse($datos) {
        if ($datos['tipoDato'] == 1) {
            $resultQuery = $this->DBS->insertTemaryCourse($datos, $datos['idCurso']);
        } else {
            $resultQuery = $this->DBS->insertParticipantsCourse($datos, $datos['idCurso']);
        }

        if ($resultQuery['code'] == 200) {
            return true;
        } else {
            return false;
        }
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
            return $this->continueCourse($infoUsuario);
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

}
