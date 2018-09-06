<?php

//Muestra solo los protoripos sin funciones

class Plantillas extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('Prototipos/prototipo_login');
    }
    
    public function viewPages($page) {
        $data['title'] = strtoupper($page);
        $this->load->view('Prototipos/'.$page,$data);
    }

}
