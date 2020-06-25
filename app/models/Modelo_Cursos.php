<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Cursos extends Modelo_Base {

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

    public function getAllCertificate() {
        return $this->consulta("SELECT id, nombre from cat_curso_tipo_certificado where estatus = 1");
    }
    
    public function getTypeCourses() {
        return $this->consulta("SELECT id, nombre from cat_curso_tipo where estatus = 1");
    }

    public function insertCourse($datos) {
        $this->iniciaTransaccion();
        $this->insertar('t_curso', [
            'idTipoCurso' => $datos['certificado'],
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion']
        ]);
        
        $ultimo = $this->consulta("select last_insert_id() as Id");
        
        $this->insertar('t_curso_online', [
            'idCurso' => $ultimo[0]["Id"],
            'idTipoCertificado' => $datos['certificado'],
            'url' => $datos['url'],
            'costo' => $datos['costo']
        ]);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function getUsuarios() {
        return array('usuario' => 'Sara', 'edad' => '26');
    }

}
