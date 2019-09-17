<?php
namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Calendar extends Modelo_Base{
    private $usuario;

    public function __construct(){
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }
    public function consultaPermisos($fechaMinima, $fechaMaxima){     
        $query="
                select
                    perm.Id,
                    nombreUsuario(perm.IdUsuario) as Usuario,
                    Estatus(perm.IdEstatus) as Estatus,
                    tipoAusencia(perm.IdTipoAusencia) as Ausencia,
                    motivoAusencia(perm.IdMotivoAusencia ) as Motivo,
                    perm.Motivo as Descripcion,
                    perm.FechaAusenciaDesde as FechaAusenciaDesde,
                    perm.FechaAusenciaHasta as FechaAusenciaHasta,
                    perm.HoraEntrada as HoraEntrada,
                    perm.HoraSalida as HoraSalida,
                    perm.Archivo as Archivo,
                    catU.IdPerfil as IdPerfil,
                    (
                        select 
                            cds.Nombre 
                        from cat_v3_departamentos_siccob as cds 
                        join cat_perfiles as cp 
                            on cds.Id = cp.IdDepartamento 
                        where cp.Id = catU.IdPerfil
                    ) as Perfil,
                    catU.id as IdUsuario,
                    nombreUsuario(idUsuarioJefe) as AutorizacionJefe,
                    idUsuarioJefe,
                    nombreUsuario(idUsuarioRH) as AutorizacionRH,
                    nombreUsuario(idUsuarioContabilidad) as AutorizacionContabilidad,
                    tipoRechazo(IdRechazo) as Rechazo

                from t_permisos_ausencia_rh perm
                join 
                        cat_v3_usuarios catU
                on
                        catU.id= perm.IdUsuario";
//                where
//                FechaAusenciaDesde>='".$fechaMinima."'
//                and FechaAusenciaDesde<='".$fechaMaxima."'
//                ";
        $resultado= $this->consulta($query);
        return $resultado;
    }

    public function motivosCancelacion() {
        $query="SELECT Id, Nombre as text 
                from cat_v3_tipos_cancelacion_ausencia_personal 
                where Flag = 1";
        $resultado= $this->consulta($query);
        return $resultado;
    }
    
    public function getEmailJefeByIdUsusario($idUsuario){
        $resultado= $this->consulta("SELECT 
                                        EmailCorporativo 
                                    FROM cat_v3_usuarios 
                                    WHERE Id= (
                                        SELECT IdJefe 
                                        FROM cat_v3_usuarios 
                                        where Id = '".$idUsuario."'
                                    )");
        return $resultado;
    }
    
    public function getEmailJefe($idJefe){
        $resultado= $this->consulta("SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id='" . $idJefe . "'");
        return $resultado;
    }
    
    public function getCorreosRHContador() {
        $resultado = $this->consulta("SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil in(21, 37)");
        return $resultado;
    }
}
