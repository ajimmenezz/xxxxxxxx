<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Cursos extends Modelo_Base{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getAllCourses() {
        return $this->consulta("SELECT 
                                    cct.*, 
                                    tc.Nombre, 
                                    tc.Descripcion, 
                                    tc.FechaCreacion 
                                from cat_curso_tipo as cct 
                                left join t_curso as tc on cct.Id = tc.IdTipoCurso");
    }
    
    public function getUsuarios(){
        return array('usuario' => 'Sara', 'edad' => '26');
    }
}
