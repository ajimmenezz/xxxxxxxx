<?php
namespace Librerias\RH;

use Controladores\Controller_Base_General as General;
use Librerias\RH\PDFI as PDFI;

class Autorizar_permisos extends General{
    
    private $DBS;
    private $Correo;
    private $pdf;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Solicitud::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->pdf = new PDFI();
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
                 WHERE tpa.Id ="' . $datosPermiso['idPermiso'] . '"');
        
        return array('formulario' => parent::getCI()->load->view('RH/Modal/formularioRevisarAusencia', $informacionPermisoAusencia, TRUE));
    }
    
    public function cancelarPermiso(array $datosPermiso){
        
        $estadoPermiso = array('IdEstatus' => '10');
        switch ($datosPermiso['idPerfil']){
            case 21:
                $revisor = array (
                    'IdUsuarioRH' => $datosPermiso['idUser'], 'FechaAutorizacionRH' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')), 
                    'MotivoRechazo' => $datosPermiso[0]['motivoRechazo']
                    );
                break;
            case 37:
                $revisor = array (
                    'IdUsuarioContabilidad' => $datosPermiso['idUser'], 'FechaAutorizacionContabilidad' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')), 
                    'MotivoRechazo' => $datosPermiso[0]['motivoRechazo']
                    );
                break;
            case 44:
                $revisor = array (
                    'IdUsuarioDireccion' => $datosPermiso['idUser'], 'FechaAutorizacionDireccion' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')), 
                    'MotivoRechazo' => $datosPermiso[0]['motivoRechazo']
                    );
                break;
            default :
                $revisor = array (
                    'IdUsuarioJefe' => $datosPermiso['idUser'], 'FechaAutorizacionJefe' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')), 
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
        
        $this->agregarFirmasPDF($datosPermiso,$rechazado="Rechazado por: ",$resultado);
        
        return $this->DBS->actualizar('t_permisos_ausencia_rh', $resultado, array('Id' => $datosPermiso['idPermiso']));
    }
    
    public function autorizarPermiso(array $datosPermiso){
        $informacionPermiso = $this->DBS->consultaGral("SELECT IdUsuarioJefe, IdUsuarioRH, IdUsuarioContabilidad, IdUsuarioDireccion 
                FROM t_permisos_ausencia_rh WHERE Id='".$datosPermiso['idPermiso']."'");
        if ($informacionPermiso[0]['IdUsuarioJefe'] == NULL){
            switch ($datosPermiso['idPerfil']){
                case 21:
                    $revisor = array (
                        'IdUsuarioJefe' => $datosPermiso['idUser'], 'FechaAutorizacionJefe' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')),
                        'IdUsuarioRH' => $datosPermiso['idUser'], 'FechaAutorizacionRH' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                        );
                    $correoRevisorSig = array ('correoRevisorSig' => $this->DBS->consultaGral('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 37'));
                    break;
                case 37:
                    $revisor = array (
                        'IdUsuarioJefe' => $datosPermiso['idUser'], 'FechaAutorizacionJefe' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')),
                        'IdUsuarioContabilidad' => $datosPermiso['idUser'], 'FechaAutorizacionContabilidad' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                        );
                    $correoRevisorSig = array ('correoRevisorSig' => $this->DBS->consultaGral('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 44'));
                    break;
                default :
                    $revisor = array (
                        'IdUsuarioJefe' => $datosPermiso['idUser'], 'FechaAutorizacionJefe' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                        );
                    $correoRevisorSig = array ('correoRevisorSig' => $this->DBS->consultaGral('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 21'));
                    break;
            }
        }else{
            switch ($datosPermiso['idPerfil']){
                case 21:
                    $revisor = array (
                        'IdUsuarioRH' => $datosPermiso['idUser'], 'FechaAutorizacionRH' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                        );
                    $correoRevisorSig = array ('correoRevisorSig' => $this->DBS->consultaGral('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 37'));
                    break;
                case 37:
                    $revisor = array (
                        'IdUsuarioContabilidad' => $datosPermiso['idUser'], 'FechaAutorizacionContabilidad' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                        );
                    $correoRevisorSig = array ('correoRevisorSig' => $this->DBS->consultaGral('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 44'));
                    break;
            }
        }
        
        $infoCorreo = $this->informacionCorreo($datosPermiso['idPermiso']);
        $texto = '<p>El permiso de ausencia de <strong>' .$infoCorreo[0]['Nombre']. ',</strong> ha sido previamente <strong>Autorizado</strong>, se requiere su concentimiento o rechazo.</p><br><br>
                    Permiso Solicitado: <p>' .$infoCorreo['tipoAusencia']. ' '.$infoCorreo['motivoAusencia'].' para el día '.$infoCorreo[0]['FechaAusenciaDesde'].'</p><br><br>
                    Descroción: <p>' .$infoCorreo['descripcion']. '</p>';
        $mensaje = $this->Correo->mensajeCorreo('Permiso de Ausencia Autorizado', $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($correoRevisorSig['correoRevisorSig'][0]['EmailCorporativo']), 'Permiso de Ausencia', $mensaje);
        
        $this->agregarFirmasPDF($datosPermiso,$rechazado="Autorizado por: ", $motivo = array ('MotivoRechazo' => ""));
        
        return $this->DBS->actualizar('t_permisos_ausencia_rh', $revisor, array('Id' => $datosPermiso['idPermiso']));
    }
    
    public function conluirAutorizacion(array $datosPermiso){
        $estadoPermiso = array('IdEstatus' => '7');
        switch ($datosPermiso['idPerfil']){
            case 37:
                $revisor = array (
                    'IdUsuarioContabilidad' => $datosPermiso['idUser'], 'FechaAutorizacionContabilidad' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')),
                    'IdUsuarioDireccion' => $datosPermiso['idUser'], 'FechaAutorizacionDireccion' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                    );
                break;
            case 44:
                $revisor = array (
                    'IdUsuarioDireccion' => $datosPermiso['idUser'], 'FechaAutorizacionDireccion' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                    );
                break;
        }
        $resultado = array_merge($estadoPermiso, $revisor);
        
        $infoCorreo = $this->informacionCorreo($datosPermiso['idPermiso']);
        $texto = '<p>Estimado(a) <strong>' .$infoCorreo[0]['Nombre']. ',</strong> se ha <strong>Autorizado</strong> el permiso de ausencia.</p><br><br>
                    Permiso Solicitado: <p>' .$infoCorreo['tipoAusencia']. ' '.$infoCorreo['motivoAusencia'].' para el día '.$infoCorreo[0]['FechaAusenciaDesde'].'</p><br><br>
                    Descroción: <p>' .$infoCorreo['descripcion']. '</p>';
        $mensaje = $this->Correo->mensajeCorreo('Permiso de Ausencia Concluido', $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($infoCorreo[0]['EmailCorporativo']), 'Permiso de Ausencia', $mensaje);
        
        $this->agregarFirmasPDF($datosPermiso,$rechazado="Autorizado y Concluido por: ", $motivo = array ('MotivoRechazo' => ""));
        
        return $this->DBS->actualizar('t_permisos_ausencia_rh', $resultado, array('Id' => $datosPermiso['idPermiso']));       
    }
    
    public function informacionCorreo($idPermiso){
        $solicitante = $this->DBS->consultaGral('SELECT cu.EmailCorporativo, CONCAT(trp.Nombres, " ",trp.ApPaterno, " ",trp.ApMaterno) AS Nombre, 
                IdTipoAusencia, IdMotivoAusencia, FechaAusenciaDesde, Motivo, FolioDocumento FROM t_permisos_ausencia_rh AS tpar 
                INNER JOIN cat_v3_usuarios AS cu ON tpar.IdUsuario=cu.Id 
                INNER JOIN t_rh_personal AS trp ON tpar.IdUsuario=trp.IdUsuario 
                WHERE tpar.Id="'.$idPermiso.'"');
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
    
    public function agregarFirmasPDF(array $datosPermiso, string $estadoPermiso, array $datosFirmas){
        $direccionArchivo = $this->DBS->consultaGral("SELECT Archivo FROM t_permisos_ausencia_rh WHERE Id='".$datosPermiso['idPermiso']."'");
        $nombreJefe = $this->DBS->consultaGral('SELECT CONCAT(trp.Nombres," ",trp.ApPaterno," ",trp.ApMaterno) AS Nombre FROM cat_v3_usuarios AS cu 
                INNER JOIN t_rh_personal AS trp ON cu.Id=trp.Id WHERE cu.IdPerfil="' .$datosPermiso['idPerfil']. '"');
        
        $rutaArchivo = explode("/", $direccionArchivo[0]['Archivo']);
        $idUsusarioPermiso = explode("_", $rutaArchivo[1]);
        
        $paginasArchivo = $this->pdf->setSourceFile('../public/storage/Archivos/Permisos_Ausencia/Ausencia_'.$idUsusarioPermiso[1].'/'.$rutaArchivo[2]);
        
        $this->pdf->AddPage();
        $tplIdx = $this->pdf->importPage(1);
        $this->pdf->useTemplate($tplIdx, 0, 0, 210, 297,true);
        
        if ($datosFirmas['MotivoRechazo'] != ""){
            $this->pdf->SetFont('Arial','B',35);
            $this->pdf->SetTextColor(254,159,159);
            $this->pdf->RotatedText(30,210,'R e c h a z a d o',0);
        }
        if ($estadoPermiso == "Autorizado y Concluido por: "){
            $this->pdf->SetFont('Arial','B',35);
            $this->pdf->SetTextColor(147,240,252);
            $this->pdf->RotatedText(30,210,'A u t o r i z a d o',0);
        }
        
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->SetTextColor(0,0,0);
        switch ($datosPermiso['idPerfil']){
            case 21:
                $this->pdf->SetXY(15, 195);
                $this->pdf->Cell(0, 0, utf8_decode($estadoPermiso.$nombreJefe[0]['Nombre'].' el día '. mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))));
                if ($datosFirmas['MotivoRechazo'] != ""){
                    $this->pdf->SetFont("helvetica", "", 11);
                    $this->pdf->SetXY(15, 200);
                    $this->pdf->MultiCell(190, 4, utf8_decode($datosFirmas['MotivoRechazo']));
                }
                break;
            case 37:
                $this->pdf->SetXY(15, 200);
                $this->pdf->Cell(0, 0, utf8_decode($estadoPermiso.$nombreJefe[0]['Nombre'].' el día '. mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))));
                if ($datosFirmas['MotivoRechazo'] != ""){
                    $this->pdf->SetFont("helvetica", "", 11);
                    $this->pdf->SetXY(15, 205);
                    $this->pdf->MultiCell(190, 4, utf8_decode($datosFirmas['MotivoRechazo']));
                }
                break;
            case 44:
                $this->pdf->SetXY(15, 205);
                $this->pdf->Cell(0, 0, utf8_decode($estadoPermiso.$nombreJefe[0]['Nombre'].' el día '. mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))));
                if ($datosFirmas['MotivoRechazo'] != ""){
                    $this->pdf->SetFont("helvetica", "", 11);
                    $this->pdf->SetXY(15, 210);
                    $this->pdf->MultiCell(190, 4, utf8_decode($datosFirmas['MotivoRechazo']));
                }
                break;
            default :
                $this->pdf->SetXY(15, 190);
                $this->pdf->Cell(0, 0, utf8_decode($estadoPermiso.$nombreJefe[0]['Nombre'].' el día '. mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))));
                if ($datosFirmas['MotivoRechazo'] != ""){
                    $this->pdf->SetFont("helvetica", "", 11);
                    $this->pdf->SetXY(15, 195);
                    $this->pdf->MultiCell(190, 4, utf8_decode($datosFirmas['MotivoRechazo']));
                }
                break;
        }
        if($paginasArchivo>1){
            for ($i=2; $i<=$paginasArchivo; $i++){
                $this->pdf->AddPage();
                $tplIdx = $this->pdf->importPage($i);
                $this->pdf->useTemplate($tplIdx, 0, 0, 210, 297,true);
            }
        }
        
        
        $nombreDocumento = explode(".", $rutaArchivo[2]);
                
        $carpeta = $this->pdf->definirArchivo('Permisos_Ausencia/Ausencia_'.$idUsusarioPermiso[1], $nombreDocumento[0]);
        $this->pdf->Output('F', $carpeta, true);
    }
    
}

