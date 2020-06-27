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

        if (isset($infoCourse['curso'])) {
//            $this->DBS->insertCourse($infoCourse['curso']);
            echo '<pre>';
            var_dump($infoCourse['curso']);
            echo '</pre>';
        }

        if (isset($infoCourse['temario'])) {
            if ($infoCourse['temario']['archivo']) {
                var_dump("importar excel");
            } else {
                echo '<pre>';
                var_dump($infoCourse['temario']['infoTabla']);
                echo '</pre>';
            }
        }

        if (isset($infoCourse['participantes'])) {
            echo '<pre>';
            foreach ($infoCourse['participantes'] as $value) {
                var_dump($value['idPerfil']);
            }
            echo '</pre>';
        }

        return $infoCourse['curso'];
    }

    public function deleteCourse($datos) {
        $resultQuery = $this->DBS->deleteCourse($datos['idCurso']);
        if ($resultQuery['code'] == 200) {
            return true;
        } else {
            return false;
        }
    }

    public function smartResponseTest(array $data = null) {
        //Your code... BD.. etc... 
        //return 1 value or an object if necessary

        $response = new \StdClass();
        $response->name = "Noe";
        $response->creationTime = 123456;
        $response->status = 1;
        $response->age = 28;
        $response->courses[] = array("foo", "bar", "hello", "world");

        return $response;
    }

}
