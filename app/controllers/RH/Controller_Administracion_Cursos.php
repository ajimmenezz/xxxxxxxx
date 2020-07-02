<?php

use Controladores\Controller_Base as Base;
use Librerias\RH\Cursos as Cursos;
use Librerias\SmartResponse\SmartResponse;
use Librerias\SmartResponse\HttpStatusCode;

class Controller_Administracion_Cursos extends Base {

    private $curso;

    public function __construct() {
        parent::__construct();
        $this->curso = new Cursos();
    }

    public function manejarEvento(string $evento = null) {
        $response = new SmartResponse();

        switch ($evento) {
            case 'Nuevo-Curso':
                $resultado = $this->curso->newCourse($this->input->post());
                if ($resultado['response']) {
                    $cursosActualizados = $this->curso->getCourses();
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("cursos", $cursosActualizados);
                } else {
                    $response->onError("Error", "Error al eliminar el curso", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Obtener-Curso':
                $resultado = $this->curso->getCourse($this->input->post());
                if ($resultado) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("infoCurso", $resultado);
                } else {
                    $response->onError("Error", "Error al eliminar el curso", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Editar-Curso':
                $resultado = $this->curso->editCourse($this->input->post());
                if ($resultado['response']) {
                    $cursosActualizados = $this->curso->getCourses();
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("cursos", $cursosActualizados);
                } else {
                    $response->onError("Error", "Error al eliminar el curso", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Eliminar-Curso':
                $resultado = $this->curso->deleteCourse($this->input->post());
                if ($resultado) {
                    $cursosActualizados = $this->curso->getCourses();
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("cursos", $cursosActualizados);
                } else {
                    $response->onError("Error", "Error al eliminar el curso", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Eliminar-ElementoCurso':
                $resultado = $this->curso->deleteElementCourse($this->input->post());
                if ($resultado) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                } else {
                    $response->onError("Error", "Error al eliminar el curso", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Agregar-ElementoCurso':
                $resultado = $this->curso->addElementCourse($this->input->post());
                if ($resultado) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                } else {
                    $response->onError("Error", "Error al eliminar el curso", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Ver-Curso':
                $resultado = $this->curso->showCourse($this->input->post());
                if ($resultado) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("infoCurso", $resultado);
                } else {
                    $response->onError("Error", "Error al eliminar el curso", HttpStatusCode::HTTP_BAD_REQUEST);
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
