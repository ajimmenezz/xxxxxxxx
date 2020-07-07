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

    public function getMyCourses($idUsuario) {
        return $this->consulta("SELECT 
                                    curso.id, 
                                    curso.Nombre,
                                    curso.Descripcion,
                                    curso.estatus,
                                    estatus(curso.estatus) AS EstatusNombre,
                                    if(curso.estatus = 1, 'Disponible', 'No Disponible') AS EstatusNombre,
                                    relacion.fechaAsignacion,
                                    if( avance.fechaModificacion is null, 0, (select sum(cursoTema.porcentaje) from t_curso_tema as cursoTema
                                        left join t_curso_tema_relacion_avance_usuario as avanceUsuario on avanceUsuario.idTema = cursoTema.id
                                        where avanceUsuario.idUsuario = usuario.Id and cursoTema.idCurso = curso.id 
                                        group by cursoTema.idCurso
                                    )) as Porcentaje,
                                    avance.fechaModificacion
                                from t_curso as curso
                                left join t_curso_tema as tema on tema.idCurso = curso.id
                                left join t_curso_tema_relacion_avance_usuario as avance on avance.idTema = tema.id
                                inner join t_curso_relacion_perfil as relacion on relacion.idCurso = curso.id
                                inner join cat_v3_usuarios as usuario on usuario.IdPerfil = relacion.idPerfil
                                where usuario.Id = " . $idUsuario . "
                                group by curso.id");
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
        return $this->consulta("SELECT * FROM t_curso_tema WHERE idCurso = ".$idCurso);
    }

    public function getPerfilById($idCurso) {
        return $this->consulta("SELECT 
                                    relacionPerfil.*, 
                                    perfil.Nombre 
                                FROM t_curso_relacion_perfil as relacionPerfil
                                inner join cat_perfiles as perfil on perfil.Id = relacionPerfil.idPerfil
                                WHERE idCurso = " . $idCurso . " AND estatus = 1");
    }

    public function insertCourse($infoCurso) {
        $this->iniciaTransaccion();
        $this->insertar('t_curso', [
            'idTipoCurso' => $infoCurso['certificado'],
            'nombre' => $infoCurso['nombre'],
            'descripcion' => $infoCurso['descripcion'],
            'imagen' => $infoCurso['imagen']
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
            'descripcion' => $infoCurso['descripcion'],
            'imagen' => $infoCurso['imagen']
                ), array('id' => $infoCurso['idCurso']));

        $this->actualizar('t_curso_online', array(
            'idTipoCertificado' => $infoCurso['certificado'],
            'url' => $infoCurso['url'],
            'costo' => $infoCurso['costo']
                ), array('idCurso' => $infoCurso['idCurso']));

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

    public function deleteElementById($idCurso,$id,$tabla) {
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
    
    public function getTemaryCourseByUser($datos) {
        return $this->consulta("SELECT 
                                    tema.id, 
                                    tema.nombre, 
                                    tema.porcentaje, 
                                    avance.fechaModificacion, 
                                    avance.idUsuario, 
                                    avance.id as idAvance 
                                FROM t_curso_tema as tema
                                LEFT JOIN t_curso_tema_relacion_avance_usuario as avance on avance.idTema = tema.id
                                WHERE tema.idCurso = ".$datos['idCurso']." and avance.idUsuario = ".$datos['idUsuario']);
    }

    public function getDetailCourse($idCurso) {
        return $this->consulta("SELECT 
                                usuarios.Id,
                                nombreUsuario(usuarios.Id) as nombreUsuario,
                                perfiles.Nombre,
                                (select sum(tema.porcentaje) from t_curso_tema as tema
                                    left join t_curso_tema_relacion_avance_usuario as relacion on relacion.idTema = tema.id
                                    where relacion.fechaModificacion is not null and relacion.idUsuario = usuarios.Id
                                ) as Porcentaje
                            FROM t_curso_relacion_perfil AS avance
                            LEFT JOIN cat_perfiles AS perfiles ON perfiles.Id = avance.idPerfil
                            INNER JOIN cat_v3_usuarios AS usuarios ON usuarios.IdPerfil = perfiles.Id
                            WHERE avance.idCurso =".$idCurso." AND usuarios.Flag = 1");
    }

    public function insertStartCourse($infoUsuario) {
        $this->iniciaTransaccion();

        $this->insertar('t_curso_relacion_avance_usuario', [
            'idUsuario' => $infoUsuario["idUsuario"],
            'idCurso' => $infoUsuario["idCurso"]
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
    
    public function saveEvidance($info, $imagen) {
        $this->iniciaTransaccion();

        $this->insertar('t_curso_tema_relacion_usuario_evidencia', [
            'idAvanceUsuario' => $info["idTema"],
            'comentarios' => $info["comentarios"],
            'url' => $imagen
        ]);
        
        $ultimoAvance = $this->consulta("select last_insert_id() as Id");

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200, 'idAvance' => $ultimoAvance[0]["Id"]];
        }
    }
    
    public function getEvidenceByID($idEvidencia) {
        return $this->consulta("SELECT 
                                    avance.fechaModificacion, 
                                    evidencia.comentarios, 
                                    evidencia.url 
                                FROM t_curso_tema_relacion_avance_usuario as avance
                                INNER JOIN t_curso_tema_relacion_usuario_evidencia as evidencia on evidencia.idAvanceUsuario = avance.id
                                WHERE avance.id=".$idEvidencia);
    }
    
    public function getInfoUserCurse($datos) {
        return $this->consulta("SELECT 
                                    curso.nombre as Curso, 
                                    nombreUsuario(usuario.Id) as NOmbre, 
                                    perfil.Nombre as Perfil 
                                from t_curso_relacion_avance_usuario as relacionPerfil
                                inner join t_curso as curso on curso.id = relacionPerfil.idCurso
                                inner join cat_v3_usuarios as usuario on usuario.Id = relacionPerfil.idUsuario
                                inner join cat_perfiles as perfil on perfil.Id = usuario.IdPerfil
                                where curso.id = " . $datos['idCurso'] . " and usuario.Id = " . $datos['idUsuario']);
    }

}
