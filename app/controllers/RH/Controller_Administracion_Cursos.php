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

              //  echo $this->input->post();
              
                // $resultado = $this->curso->newCourse($this->input->post());

                // echo json_encode($resultado);
                break;
            case 'Eliminar-Curso':
                $resultado = $this->curso->deleteCourse($this->input->post());
                if ($resultado) {
                    $response->onSuccess(HttpStatusCode::HTTP_OK);
                } else {
                    $response->onError("Error", "Error al eliminar el curso", HttpStatusCode::HTTP_BAD_REQUEST);
                }
                echo $response->toJsonString();
                break;
            case 'SmartResponse':
                $result = $this->curso->smartResponseTest();

                //Opcion A
                //enviamos el objeto o valor tal cual nos da la libreria, y le asignamos la llave resultado
                // $response->addData("resultado", $result);
                //Opcion B (recommended)
                //Enviamos una respuesta donde cada dato va con una respectiva llave
                $response->addData("nameFull", $result->name);
                $response->addData("age", $result->age);
                $response->addData("arrCourses", $result->courses);


                // $response->onSuccess(); 
                $response->onSuccess(HttpStatusCode::HTTP_CREATED);
                echo $response->toJsonString();
                break;
            default:
                $response->onError("NOT_FOUND", 'Solicitud no encontrada', HttpStatusCode::HTTP_NOT_FOUND);
                echo $response->toJsonString();
                break;
        }
    }

}
