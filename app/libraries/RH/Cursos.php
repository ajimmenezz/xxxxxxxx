<?php

namespace Librerias\RH;

use Controladores\Controller_Base_General as General;

class Cursos extends General{
    
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
    
    public function newCourse($infoCourse) {
        
    }

    public function smartResponseTest(array $data = null){
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
