<?php
namespace Librerias\RH;

use Controladores\Controller_Base_General as General;

class Autorizar_permisos extends General{
    
    private $DBS;
    private $Correo;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Solicitud::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
    }
    
    public function buscarSubordinados(int $idUsuario){
        return $this->DBS->consultaGral('select tpar.Id, tpar.FechaDocumento, cu.Nombre, tpar.IdTipoAusencia, 
                tpar.IdMotivoAusencia, tpar.FechaAusenciaDesde, tpar.FechaAusenciaHasta, tpar.HoraEntrada, tpar.HoraSalida
                from t_permisos_ausencia_rh tpar inner join cat_v3_usuarios cu on tpar.IdUsuario = cu.Id 
                where tpar.IdEstatus = 9 and ((cu.IdJefe = '.$idUsuario.') and tpar.IdUsuarioJefe is null) 
                or ((select IdPerfil from cat_v3_usuarios where Id = '.$idUsuario.') = 21 and tpar.IdUsuarioJefe is not null and tpar.IdUsuarioRH is null) 
                or ((select IdPerfil from cat_v3_usuarios where Id = '.$idUsuario.') = 37 and tpar.IdUsuarioJefe is not null and tpar.IdUsuarioRH is not null and tpar.IdUsuarioContabilidad is null) 
                or ((select IdPerfil from cat_v3_usuarios where Id = '.$idUsuario.') = 44 and tpar.IdUsuarioJefe is not null and tpar.IdUsuarioRH is not null 
                and tpar.IdUsuarioContabilidad is not null and tpar.IdUsuarioDireccion is null)');
    }
    
    public function revisarPermiso(array $datosPermiso){
        $informacionPermisoAusencia['perfilUsuario'] = $datosPermiso['perfilUsuario'];
        $informacionPermisoAusencia['datosAusencia'] = $this->DBS->consultaGral('SELECT tpa.FechaDocumento, CONCAT(trp.Nombres, " ",trp.ApPaterno, " ",trp.ApMaterno) AS Nombre,
                 cp.Nombre AS Puesto, cds.Nombre AS Departamento, tpa.Id, IdEstatus, IdTipoAusencia, IdMotivoAusencia, FechaAusenciaDesde, FechaAusenciaHasta, 
                 HoraEntrada, HoraSalida, Motivo, FolioDocumento, Archivo, IdUsuarioJefe FROM t_permisos_ausencia_rh AS tpa 
                 INNER JOIN t_rh_personal AS trp ON tpa.IdUsuario = trp.IdUsuario INNER JOIN cat_v3_usuarios AS cu ON tpa.IdUsuario=cu.Id 
                 INNER JOIN cat_perfiles AS cp ON cu.IdPerfil=cp.Id INNER JOIN cat_v3_departamentos_siccob AS cds ON cp.IdDepartamento=cds.Id 
                 WHERE tpa.Id ='.$datosPermiso['idPermiso']);
        
        return array('formulario' => parent::getCI()->load->view('RH/Modal/formularioRevisarAusencia', $informacionPermisoAusencia, TRUE));
    }
    
    public function cancelarPermiso(array $datosPermiso){
        
        $estadoPermiso = array('IdEstatus' => '10');
        switch ($datosPermiso['idPerfil']){
            case 21:
                $revisor = array (
                    'IdUsuarioRH' => $datosPermiso['idUser'], 'FechaAutorizacionRH' => $datosPermiso['fecha'], 
                    'MotivoRechazo' => $datosPermiso[0]['motivoRechazo']
                    );
                break;
            case 37:
                $revisor = array (
                    'IdUsuarioContabilidad' => $datosPermiso['idUser'], 'FechaAutorizacionContabilidad' => $datosPermiso['fecha'], 
                    'MotivoRechazo' => $datosPermiso[0]['motivoRechazo']
                    );
                break;
            case 44:
                $revisor = array (
                    'IdUsuarioDireccion' => $datosPermiso['idUser'], 'FechaAutorizacionDireccion' => $datosPermiso['fecha'], 
                    'MotivoRechazo' => $datosPermiso[0]['motivoRechazo']
                    );
                break;
            default :
                $revisor = array (
                    'IdUsuarioJefe' => $datosPermiso['idUser'], 'FechaAutorizacionJefe' => $datosPermiso['fecha'], 
                    'MotivoRechazo' => $datosPermiso[0]['motivoRechazo']
                    );
                break;
        }
        $resultado = array_merge($estadoPermiso, $revisor);
        
        $infoCorreo = $this->informacionCorreo($datosPermiso['idPermiso']);
        $texto = '<p>Estimado(a) <strong>' .$infoCorreo[0]['Nombre']. ',</strong> se ha <strong>Rechazado</strong> el permiso de ausencia.</p><br><br>
                    Permiso Solicitado: <p>' .$infoCorreo['tipoAusencia']. ' '.$infoCorreo['motivoAusencia'].' para el día '.$infoCorreo[0]['FechaAusenciaDesde'].'</p><br><br>
                    Descroción: <p>' .$infoCorreo['descripcion']. '</p><br><br>
                    Motivo de Rechazo: <p><b>' . $datosPermiso[0]['motivoRechazo'] . '</b> </p>';
        $mensaje = $this->Correo->mensajeCorreo('Permiso de Ausencia Rechazado', $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($infoCorreo[0]['EmailCorporativo']), 'Permiso de Ausencia', $mensaje);
        
       //return $this->DBS->actualizar('t_permisos_ausencia_rh', $resultado, array('Id' => $datosPermiso['idPermiso']));
       return $texto;
    }
    
    public function autorizarPermiso(array $datosPermiso){
        $informacionPermiso = $this->DBS->consultaGral('SELECT IdUsuarioJefe, IdUsuarioRH, IdUsuarioContabilidad, IdUsuarioDireccion 
                FROM t_permisos_ausencia_rh WHERE Id='.$datosPermiso['idPermiso']);
        if ($informacionPermiso[0]['IdUsuarioJefe'] == NULL){
            switch ($datosPermiso['idPerfil']){
                case 21:
                    $revisor = array (
                        'IdUsuarioJefe' => $datosPermiso['idUser'], 'FechaAutorizacionJefe' => $datosPermiso['fecha'],
                        'IdUsuarioRH' => $datosPermiso['idUser'], 'FechaAutorizacionRH' => $datosPermiso['fecha']
                        );
                    $correoRevisorSig = array ('correoRevisorSig' => $this->DBS->consultaGral('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 37'));
                    break;
                case 37:
                    $revisor = array (
                        'IdUsuarioJefe' => $datosPermiso['idUser'], 'FechaAutorizacionJefe' => $datosPermiso['fecha'],
                        'IdUsuarioContabilidad' => $datosPermiso['idUser'], 'FechaAutorizacionContabilidad' => $datosPermiso['fecha']
                        );
                    $correoRevisorSig = array ('correoRevisorSig' => $this->DBS->consultaGral('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 44'));
                    break;
                default :
                    $revisor = array (
                        'IdUsuarioJefe' => $datosPermiso['idUser'], 'FechaAutorizacionJefe' => $datosPermiso['fecha']
                        );
                    $correoRevisorSig = array ('correoRevisorSig' => $this->DBS->consultaGral('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 21'));
                    break;
            }
        }else{
            switch ($datosPermiso['idPerfil']){
                case 21:
                    $revisor = array (
                        'IdUsuarioRH' => $datosPermiso['idUser'], 'FechaAutorizacionRH' => $datosPermiso['fecha']
                        );
                    $correoRevisorSig = array ('correoRevisorSig' => $this->DBS->consultaGral('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 37'));
                    break;
                case 37:
                    $revisor = array (
                        'IdUsuarioContabilidad' => $datosPermiso['idUser'], 'FechaAutorizacionContabilidad' => $datosPermiso['fecha']
                        );
                    $correoRevisorSig = array ('correoRevisorSig' => $this->DBS->consultaGral('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 44'));
                    break;
            }
        }
        
        $infoCorreo = $this->informacionCorreo($datosPermiso['idPermiso']);
        $texto = '<p>El permiso de ausencia de <strong>' .$infoCorreo[0]['Nombre']. ',</strong> ha sido previamente <strong>Autorizado</strong>, se requiere su concentimiento o rechazo.</p><br><br>
                    Permiso Solicitado: <p>' .$infoCorreo['tipoAusencia']. ' '.$infoCorreo['motivoAusencia'].' para el día '.$infoCorreo[0]['FechaAusenciaDesde'].'</p><br><br>
                    Descroción: <p>' .$infoCorreo['descripcion']. '</p>';
        $mensaje = $this->Correo->mensajeCorreo('Permiso de Ausencia Rechazado', $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($correoRevisorSig['correoRevisorSig'][0]['EmailCorporativo']), 'Permiso de Ausencia', $mensaje);
        
        return $this->DBS->actualizar('t_permisos_ausencia_rh', $revisor, array('Id' => $datosPermiso['idPermiso']));
    }
    
    public function conluirAutorizacion(array $datosPermiso){
        $estadoPermiso = array('IdEstatus' => '7');
        switch ($datosPermiso['idPerfil']){
            case 37:
                $revisor = array (
                    'IdUsuarioContabilidad' => $datosPermiso['idUser'], 'FechaAutorizacionContabilidad' => $datosPermiso['fecha'],
                    'IdUsuarioDireccion' => $datosPermiso['idUser'], 'FechaAutorizacionDireccion' => $datosPermiso['fecha']
                    );
                break;
            case 44:
                $revisor = array (
                    'IdUsuarioDireccion' => $datosPermiso['idUser'], 'FechaAutorizacionDireccion' => $datosPermiso['fecha']
                    );
                break;
        }
        $resultado = array_merge($estadoPermiso, $revisor);
        
        $infoCorreo = $this->informacionCorreo($datosPermiso['idPermiso']);
        $texto = '<p>Estimado(a) <strong>' .$infoCorreo[0]['Nombre']. ',</strong> se ha <strong>Autorizado</strong> el permiso de ausencia.</p><br><br>
                    Permiso Solicitado: <p>' .$infoCorreo['tipoAusencia']. ' '.$infoCorreo['motivoAusencia'].' para el día '.$infoCorreo[0]['FechaAusenciaDesde'].'</p><br><br>
                    Descroción: <p>' .$infoCorreo['descripcion']. '</p>';
        $mensaje = $this->Correo->mensajeCorreo('Permiso de Ausencia Rechazado', $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($infoCorreo[0]['EmailCorporativo']), 'Permiso de Ausencia', $mensaje);
        
        return $this->DBS->actualizar('t_permisos_ausencia_rh', $resultado, array('Id' => $datosPermiso['idPermiso']));
    }
    
    public function informacionCorreo($idPermiso){
        $solicitante = $this->DBS->consultaGral('SELECT cu.EmailCorporativo, CONCAT(trp.Nombres, " ",trp.ApPaterno, " ",trp.ApMaterno) AS Nombre, 
                IdTipoAusencia, IdMotivoAusencia, FechaAusenciaDesde, Motivo, FolioDocumento FROM t_permisos_ausencia_rh AS tpar 
                INNER JOIN cat_v3_usuarios AS cu ON tpar.IdUsuario=cu.Id INNER JOIN t_rh_personal AS trp ON tpar.IdUsuario=trp.IdUsuario WHERE tpar.Id='.$idPermiso);
        switch ($solicitante[0]['IdTipoAusencia']){
            case '1':
                $tipoAusencia = array (
                    'tipoAusencia' => 'Llegada Tarde'
                );
                break;
            case '2':
                $tipoAusencia = array (
                    'tipoAusencia' => 'Salida Temprano'
                );
                break;
            case '3':
                $tipoAusencia = array (
                    'tipoAusencia' => 'No Asistirá'
                );
                break;
        }
        switch ($solicitante[0]['IdMotivoAusencia']){
            case '1':
                $motivoAusencia = array (
                    'motivoAusencia' => 'con motivo Personal',
                    'descripcion' => $solicitante[0]['Motivo']
                );
                break;
            case '2':
                $motivoAusencia = array (
                    'motivoAusencia' => 'con motivo Trabajo/Comisión',
                    'descripcion' => $solicitante[0]['Motivo']
                );
                break;
            case '3':
                $motivoAusencia = array (
                    'motivoAusencia' => 'con motivo IMSS Cita Médica',
                    'descripcion' => $solicitante[0]['FolioDocumento']
                );
                break;
            case '4':
                $motivoAusencia = array (
                    'motivoAusencia' => 'con motivo IMSS Incapacidad',
                    'descripcion' => $solicitante[0]['FolioDocumento']
                );
                break;
        }
        $resultado = array_merge($solicitante, $tipoAusencia);
        $arregloDatos = array_merge($resultado, $motivoAusencia);
        return $arregloDatos;
    }
}

