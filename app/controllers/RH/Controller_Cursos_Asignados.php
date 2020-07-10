<?php

use Controladores\Controller_Base as Base;
use Librerias\RH\Cursos as Cursos;
use Librerias\SmartResponse\SmartResponse;
use Librerias\SmartResponse\HttpStatusCode;

class Controller_Cursos_Asignados extends Base {

    private $curso;

    public function __construct() {
        parent::__construct();
        $this->curso = new Cursos();
    }

    public function manejarEvento(string $evento = null) {
        $response = new SmartResponse();

        switch ($evento) {
            case 'Comenzar-Curso':
                $resultado = $this->curso->startCourse($this->input->post());
                if ($resultado) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("temario", $resultado);
                } else {
                    $response->onError("Error", "Error al comenzar el curso", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Continuar-Curso':
                $resultado = $this->curso->TemaryCourseByUser($this->input->post());
                if ($resultado) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("temario", $resultado);
                } else {
                    $response->onError("Error", "Error al obtener la informacón", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Agregar-Avance':
                $resultado = $this->curso->addEvidence($this->input->post());
                if ($resultado) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("temario", $resultado);
                } else {
                    $response->onError("Error", "Error al agregar avances", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Ver-Evidencias':
                $resultado = $this->curso->showEvidence($this->input->post());
                if ($resultado) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("avance", $resultado);
                } else {
                    $response->onError("Error", "Error al obtener las evidencias", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            default:
                $response->onError("NOT_FOUND", 'Solicitud no encontrada', HttpStatusCode::HTTP_NOT_FOUND);
                echo $response->toJsonString();
                break;
        }
    }

}
