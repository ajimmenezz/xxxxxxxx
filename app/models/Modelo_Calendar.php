<?php
    namespace Modelos;

    use Librerias\Modelos\Base as Modelo_Base;

    class Modelo_Calendar extends Modelo_Base
    {
        private $usuario;

        public function __construct()
        {
            parent::__construct();
            $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
        }
        public function consultaPermisos($idPersona, $fechaMinima, $fechaMaxima)
        {
            
            $query="
                    select
                        perm.Id,
                        nombreUsuario(perm.IdUsuario) as Usuario,
                        Estatus(perm.IdEstatus) as Estatus,
                        tipoAusencia(perm.IdTipoAusencia) as Ausencia,
                        motivoAusencia(perm.IdMotivoAusencia ) as Motivo,
                        perm.Motivo as Justificacion,
                        perm.FechaAusenciaDesde as FechaAusenciaDesde,
                        perm.FechaAusenciaHasta as FechaAusenciaHasta,
                        perm.HoraEntrada as HoraEntrada,
                        perm.HoraSalida as HoraSalida,
                        perm.Archivo as Archivo,
                        nombreUsuario(catU.IdJefe) as Jefe,
                        catU.IdPerfil as IdPerfil,
                        catU.id as IdUsuario,
                        nombreUsuario(idUsuarioJefe) as AutorizacionJefe,
                        nombreUsuario(idUsuarioRH) as AutorizacionRH,
                        nombreUsuario(idUsuarioContabilidad) as AutorizacionContabilidad,
                        nombreUsuario(idUsuarioDireccion) as AutorizacionDireccion,
                        tipoRechazo(IdRechazo) as Rechazo
                        
					
                    from t_permisos_ausencia_rh perm
                    join 
                            cat_v3_usuarios catU
                    on
                            catU.id= perm.IdUsuario
                    where
                            catU.IdJefe= 3
                    and FechaAusenciaDesde>='".$fechaMinima."'
                    and FechaAusenciaDesde<='".$fechaMaxima."'
                    ";
          // var_dump($query);
            $resultado= $this->consulta($query);
            return $resultado;
        }

    }
