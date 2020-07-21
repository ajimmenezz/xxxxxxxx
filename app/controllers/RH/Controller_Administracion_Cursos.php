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
            case 'Secciones-Admintrador-Cursos':
                $datos = array();
                $datos['cursos'] = $this->curso->getCourses();
                $datos['perfiles'] = $this->curso->getProfile();
                $datos['certificados'] = $this->curso->getCertificate();
                $datos['tipoCursos'] = $this->curso->getTypeCourses();
                $resultado = array(
                    'NuevoCurso' => $this->load->view('RH/SeccionesCursos/NuevoCurso', $datos, TRUE),
                    'EditarCurso' => $this->load->view('RH/SeccionesCursos/EditarCurso', $datos, TRUE)
                );
                echo json_encode($resultado);
                break;
            case 'Nuevo-Curso':
                $datosPost = $this->input->post();
                $datos = json_decode($datosPost['extraData'], true);
                $resultado = $this->curso->newCourse($datos);
                if ($resultado['response']) {
                    $cursosActualizados = $this->curso->getCourses();
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("cursos", $cursosActualizados);
                } else {
                    $response->onError("Error", "Error al agregar el curso", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Obtener-Curso':
                $resultado = $this->curso->getCourse($this->input->post());
                if ($resultado) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("infoCurso", $resultado);
                } else {
                    $response->onError("Error", "Error al obtener el curso", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Editar-Curso':
                $datosPost = $this->input->post();
                $datos = json_decode($datosPost['extraData'], true);
                $resultado = $this->curso->editCourse($datos);
                if ($resultado['response']) {
                    $cursosActualizados = $this->curso->getCourses();
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("cursos", $cursosActualizados);
                } else {
                    $response->onError("Error", "Error al aditar el curso", HttpStatusCode::HTTP_BAD_REQUEST);
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
                    $response->onError("Error", "Error al realizar esta acción", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Agregar-ElementoCurso':
                $resultado = $this->curso->addElementCourse($this->input->post());
                if ($resultado['response']) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("info", $resultado['info']);
                    $response->addData("id", $resultado['id']);
                } else {
                    $response->onError("Error", "Error al realizar esta acció", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'Ver-Curso':
                $resultado = $this->curso->showCourse($this->input->post());
                if ($resultado) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("infoCurso", $resultado);
                } else {
                    $response->onError("Error", "Error al obtener la informacón", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'TemasCursoUsuario':
                $resultado = $this->curso->TemaryCourseByUser($this->input->post());
                if ($resultado) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                    $response->addData("infoUsuario", $resultado);
                } else {
                    $response->onError("Error", "Error al obtener los temas", HttpStatusCode::HTTP_BAD_REQUEST);
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
