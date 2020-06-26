<?php

class Controller_Error extends CI_Controller {

    private $pagina = 'errors/personalizado/error_DB';

    public function __construct() {
        parent::__construct();
    }

    public function ErrorDB() {
        
        $datos = array();

        $datos['datos'] = array(
            'clave' => '602',
            'bontoRegresar' => '<a href="javascript:history.go(-1);" class="btn btn-success">Regresar</a>',
            'titulo' => 'Error Interno del Sistema',
            'descripcion' => 'Ocurrio un error interno en el sistema favor de volver a intentarlo en caso de persistir reportarlo al area de desarrollo de sistemas.'
        );
        
        $this->load->view($this->pagina, $datos);
    }

}
