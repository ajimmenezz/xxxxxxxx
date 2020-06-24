<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Cursos extends Modelo_Base{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getAllCourses() {
        return $this->consulta("SELECT 
                                    curso.Id, 
                                    curso.Nombre,
                                    tipoCurso.nombre as TipoCurso,
                                    curso.Descripcion,
                                    count(usuarios.Id) as Participantes,
                                    curso.estatus as Estatus
                                from t_curso as curso
                                inner join cat_curso_tipo as tipoCurso on tipoCurso.id = curso.idTipoCurso
                                left join t_curso_relacion_perfil as relacionPerfil on relacionPerfil.idCurso = curso.id
                                inner join cat_perfiles as perfiles on perfiles.Id = relacionPerfil.idPerfil
                                inner join cat_v3_usuarios as usuarios on usuarios.IdPerfil = perfiles.Id
                                where usuarios.Flag = 1 group by curso.Nombre");
    }
    
    public function getAllProfile() {
        return $this->consulta("select Id, Nombre from cat_perfiles");
    }
    
    public function getUsuarios(){
        return array('usuario' => 'Sara', 'edad' => '26');
    }
}
