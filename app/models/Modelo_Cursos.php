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
                                where curso.estatus = 1 and usuarios.Flag = 1 group by curso.Nombre");
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
    
    public function getCourseById($idCurso) {
        return $this->consulta("SELECT 
                                    curso.*,
                                    cursoOnline.id AS idCursoOnline,
                                    cursoOnline.idCurso,
                                    cursoOnline.idTipoCertificado,
                                    cursoOnline.url,
                                    cursoOnline.costo
                                FROM t_curso AS curso
                                INNER JOIN t_curso_online AS cursoOnline ON cursoOnline.idCurso = curso.id
                                WHERE curso.id = " . $idCurso);
    }
    
    public function getTemaryById($idCurso) {
        return $this->consulta("SELECT * FROM t_curso_tema WHERE idCurso = " . $idCurso);
    }
    
    public function getPerfilById($idCurso) {
        return $this->consulta("SELECT * FROM t_curso_relacion_perfil WHERE idCurso = " . $idCurso . " AND estatus = 1");
    }

    public function insertCourse($infoCurso) {
        $this->iniciaTransaccion();
        $this->insertar('t_curso', [
            'idTipoCurso' => $infoCurso['certificado'],
            'nombre' => $infoCurso['nombre'],
            'descripcion' => $infoCurso['descripcion']
        ]);

        $ultimoCurso = $this->consulta("select last_insert_id() as Id");

        $this->insertar('t_curso_online', [
            'idCurso' => $ultimoCurso[0]["Id"],
            'idTipoCertificado' => $infoCurso['certificado'],
            'url' => $infoCurso['url'],
            'costo' => $infoCurso['costo']
        ]);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400, 'id' => 0];
        } else {
            $this->commitTransaccion();
            return ['code' => 200, 'id' => $ultimoCurso[0]["Id"]];
        }
    }
    
    public function updateCourse($infoCurso) {
        $this->iniciaTransaccion();
        $this->actualizar('t_curso', array(
            'idTipoCurso' => $infoCurso['certificado'],
            'nombre' => $infoCurso['nombre'],
            'descripcion' => $infoCurso['descripcion']
                ), array('id' => $infoCurso['idCurso']));
        
        $this->actualizar('t_curso_online', array(
            'idTipoCertificado' => $infoCurso['certificado'],
            'url' => $infoCurso['url'],
            'costo' => $infoCurso['costo']
                ), array('id' => $infoCurso['idCurso']));

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return true;
        } else {
            $this->commitTransaccion();
            return false;
        }
    }
    
    public function insertTemaryCourse($infoTema, $idCurso) {
        $this->iniciaTransaccion();
        
        $this->insertar('t_curso_tema', [
            'nombre' => $infoTema[0],
            'descripcion' => $infoTema[1],
            'porcentaje' => $infoTema[2],
            'idCurso' => $idCurso
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
    
    function insertParticipantsCourse($perfil, $idCurso) {
        $this->iniciaTransaccion();
        
        $this->insertar('t_curso_relacion_perfil', [
            'idCurso' => $idCurso,
            'idPerfil' => $perfil
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

    public function deleteCourse($idCurso) {
        $this->iniciaTransaccion();
        
        $this->actualizar('t_curso', array(
            'estatus' => 0
                ), array('id' => $idCurso));
        $this->actualizar('t_curso_online', array(
            'estatus' => 0
                ), array('idCurso' => $idCurso));

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }
    
    public function deleteElementById($id, $tabla) {
        $this->iniciaTransaccion();
        
        $this->actualizar($tabla, array(
            'estatus' => 0
                ), array('id' => $id));

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

}
