<?php
namespace Librerias\RH;

use Controladores\Controller_Base_General as General;
use Librerias\RH\PDFI as PDFI;

class Autorizar_permisos extends General{
    
    private $DBS;
    private $Correo;
    private $pdf;
    private $Excel;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Solicitud::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->pdf = new PDFI();
        $this->Excel = new \Librerias\Generales\CExcel();
    }
    
    public function buscarSubordinados(int $idUsuario, int $IdPerfil){
        switch ($IdPerfil) {
            case '21':
            case '37':
                return $this->DBS->consultaGral('select tpar.Id, tpar.FechaDocumento, nombreUsuario(cu.Id) as Nombre, tpar.IdTipoAusencia, 
                            tpar.IdMotivoAusencia, tpar.FechaAusenciaDesde, tpar.FechaAusenciaHasta, tpar.HoraEntrada, tpar.HoraSalida,
                            tpar.IdEstatus, tpar.Archivo, tpar.IdUsuarioJefe, tpar.IdUsuarioRH, tpar.IdUsuarioContabilidad, tpar.IdUsuarioDireccion
                            from t_permisos_ausencia_rh tpar inner join cat_v3_usuarios cu on tpar.IdUsuario = cu.Id 
                            where DATE(FechaDocumento) BETWEEN CURDATE()-20 AND CURDATE() and cu.Id <> '.$idUsuario);
                break;
            default:
                return $this->DBS->consultaGral('select tpar.Id, tpar.FechaDocumento, cu.Nombre, tpar.IdTipoAusencia, 
                    tpar.IdMotivoAusencia, tpar.FechaAusenciaDesde, tpar.FechaAusenciaHasta, tpar.HoraEntrada, tpar.HoraSalida,
                    tpar.IdEstatus, tpar.Archivo, tpar.IdUsuarioJefe, tpar.IdUsuarioRH, tpar.IdUsuarioContabilidad, tpar.IdUsuarioDireccion
                    from t_permisos_ausencia_rh tpar inner join cat_v3_usuarios cu on tpar.IdUsuario = cu.Id 
                    where DATE(FechaDocumento) BETWEEN CURDATE()-20 AND CURDATE() and (cu.IdJefe = '.$idUsuario.') 
                    or ((select IdPerfil from cat_v3_usuarios where Id = '.$idUsuario.') = 21 and tpar.IdUsuarioJefe is not null and tpar.IdUsuarioRH is null) 
                    or ((select IdPerfil from cat_v3_usuarios where Id = '.$idUsuario.') = 37 and tpar.IdUsuarioJefe is not null and tpar.IdUsuarioRH is not null and tpar.IdUsuarioContabilidad is null) 
                    or ((select IdPerfil from cat_v3_usuarios where Id = '.$idUsuario.') = 44 and tpar.IdUsuarioJefe is not null and tpar.IdUsuarioRH is not null 
                    and tpar.IdUsuarioContabilidad is not null and tpar.IdUsuarioDireccion is null)');
                break;
        }
    }
    
    public function motivosRechazo() {
        return $this->DBS->consultaGral('select * from cat_v3_tipos_rechazos_ausencia_personal');
    }
    public function motivosCancelacion() {
        return $this->DBS->consultaGral('SELECT Id, Nombre 
                from cat_v3_tipos_cancelacion_ausencia_personal 
                where Flag = 1');
    }
    
    public function revisarPermiso(array $datosPermiso){
        $informacionPermisoAusencia['perfilUsuario'] = $datosPermiso['perfilUsuario'];
        $informacionPermisoAusencia['motivosRechazo'] = $this->motivosRechazo();
        $informacionPermisoAusencia['tipoCancelacion'] = $this->motivosCancelacion();
        $informacionPermisoAusencia['datosAusencia'] = $this->DBS->consultaGral('SELECT tpa.FechaDocumento, CONCAT(trp.Nombres, " ",trp.ApPaterno, " ",trp.ApMaterno) AS Nombre,
                 cp.Nombre AS Puesto, cds.Nombre AS Departamento, tpa.Id, IdEstatus, IdTipoAusencia, IdMotivoAusencia, FechaAusenciaDesde, FechaAusenciaHasta, 
                 HoraEntrada, HoraSalida, Motivo, FolioDocumento, Archivo, ArchivosOriginales, IdUsuarioJefe, IdUsuarioRH, IdUsuarioContabilidad, cmap.Cancelacion, cmap.NivelCancelacion 
                 FROM t_permisos_ausencia_rh AS tpa 
                 INNER JOIN t_rh_personal AS trp ON tpa.IdUsuario = trp.IdUsuario INNER JOIN cat_v3_usuarios AS cu ON tpa.IdUsuario=cu.Id 
                 INNER JOIN cat_perfiles AS cp ON cu.IdPerfil=cp.Id INNER JOIN cat_v3_departamentos_siccob AS cds ON cp.IdDepartamento=cds.Id
                 INNER JOIN cat_v3_motivos_ausencia_personal AS cmap ON cmap.Id = tpa.IdMotivoAusencia
                 WHERE tpa.Id ="' . $datosPermiso['idPermiso'] . '"');
        
        return array('formulario' => parent::getCI()->load->view('RH/Modal/formularioRevisarAusencia', $informacionPermisoAusencia, TRUE), 'consulta' => $informacionPermisoAusencia);
    }
    
    public function cancelarPermiso(array $datosPermiso){

        $estadoPermiso = array('IdEstatus' => '10');
        switch ($datosPermiso['idPerfil']){
            case 21:
                $revisor = array (
                    'IdUsuarioRH' => $datosPermiso['idUser'], 'FechaAutorizacionRH' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')), 
                    'IdRechazo' => $datosPermiso[0]['motivoRechazo']
                    );
                break;
            case 37:
                $revisor = array (
                    'IdUsuarioContabilidad' => $datosPermiso['idUser'], 'FechaAutorizacionContabilidad' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')), 
                    'IdRechazo' => $datosPermiso[0]['motivoRechazo']
                    );
                break;
            case 44:
                $revisor = array (
                    'IdUsuarioDireccion' => $datosPermiso['idUser'], 'FechaAutorizacionDireccion' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')), 
                    'IdRechazo' => $datosPermiso[0]['motivoRechazo'],
                    'MotivoRechazo' => "hay rechazo"
                    );
                break;
            default :
                $revisor = array (
                    'IdUsuarioJefe' => $datosPermiso['idUser'], 'FechaAutorizacionJefe' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')), 
                    'IdRechazo' => $datosPermiso[0]['motivoRechazo']
                    );
                break;
        }
        $resultado = array_merge($estadoPermiso, $revisor);
        
        $infoCorreo = $this->informacionCorreo($datosPermiso['idPermiso']);
        $texto = '<p>Estimado(a) <strong>' .$infoCorreo[0]['Nombre']. ',</strong> se ha <strong>Rechazado</strong> el permiso de ausencia.</p><br><br>
                    Permiso Solicitado: <p>' .$infoCorreo['tipoAusencia']. ' para el día '.$infoCorreo[0]['FechaAusenciaDesde'].'</p><br><br>
                    Motivo de Rechazo: <p><b>' . $datosPermiso[0]['textoRechazo'] . '</b> </p><br><br>
                    <a href="https://'.$_SERVER['SERVER_NAME'].'/storage/Archivos/'.$datosPermiso['archivo'].'">Archivo</a>';
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
                default :
                    $revisor = array (
                        'IdUsuarioJefe' => $datosPermiso['idUser'], 'FechaAutorizacionJefe' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                        );
                    $correoRevisorSig = array ('correoRevisorSig' => $this->DBS->consultaGral('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 21'));
                    break;
            }
        }
        
        $infoCorreo = $this->informacionCorreo($datosPermiso['idPermiso']);
        
        $texto = '<p>El permiso de ausencia de <strong>' .$infoCorreo[0]['Nombre']. ',</strong> ha sido previamente <strong>Autorizado</strong>, se requiere su concentimiento o rechazo.</p><br><br>
                    Permiso Solicitado: <p>' .$infoCorreo['tipoAusencia']. ' para el día '.$infoCorreo[0]['FechaAusenciaDesde'].'</p><br><br>
                    <a href="https://'.$_SERVER['SERVER_NAME'].'/storage/Archivos/'.$datosPermiso['archivo'].'">Archivo</a>';
        $mensaje = $this->Correo->mensajeCorreo('Permiso de Ausencia Autorizado', $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($correoRevisorSig['correoRevisorSig'][0]['EmailCorporativo']), 'Permiso de Ausencia', $mensaje);
        $this->agregarFirmasPDF($datosPermiso,$rechazado="Autorizado por: ", $motivo = array ('IdRechazo' => ""));
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
            default :
                $revisor = array (
                    'IdUsuarioDireccion' => $datosPermiso['idUser'], 'FechaAutorizacionDireccion' =>  mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                    );
                break;
        }
        $resultado = array_merge($estadoPermiso, $revisor);
        
        $infoCorreo = $this->informacionCorreo($datosPermiso['idPermiso']);
        $texto = '<p>Estimado(a) <strong>' .$infoCorreo[0]['Nombre']. ',</strong> se ha <strong>Autorizado</strong> el permiso de ausencia.</p><br><br>
                    Permiso Solicitado: <p>' .$infoCorreo['tipoAusencia']. ' para el día '.$infoCorreo[0]['FechaAusenciaDesde'].'</p><br><br>
                    <a href="https://'.$_SERVER['SERVER_NAME'].'/storage/Archivos/'.$datosPermiso['archivo'].'">Archivo</a>';
        $mensaje = $this->Correo->mensajeCorreo('Permiso de Ausencia Concluido', $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($infoCorreo[0]['EmailCorporativo']), 'Permiso de Ausencia', $mensaje);
        
        $this->agregarFirmasPDF($datosPermiso,$rechazado="Autorizado y Concluido por: ", $motivo = array ('IdRechazo' => ""));
        
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
        $resultado = array_merge($solicitante, $tipoAusencia);
        $arregloDatos = array_merge($resultado);
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
        $this->pdf->useTemplate($tplIdx, 0, 0, 210, 420, true);
        
        $this->pdf->SetXY(150, 2);
        $this->pdf->SetFillColor(255, 255, 255);
        $this->pdf->Cell(50, 20, '', 0, 0, 'C', True);
        if ($datosFirmas['IdRechazo'] != ""){
            $this->pdf->SetXY(25, 320);
            $this->pdf->SetFont('Arial','B',35);
            $this->pdf->SetTextColor(254,159,159);
            $this->pdf->Cell(30,30,'R e c h a z a d o');
        }
        if ($estadoPermiso == "Autorizado y Concluido por: "){
            $this->pdf->SetXY(25, 320);
            $this->pdf->SetFont('Arial','B',35);
            $this->pdf->SetTextColor(147,240,252);
            $this->pdf->Cell(30,30,'A u t o r i z a d o');
        }
        
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->SetTextColor(0,0,0);
        switch ($datosPermiso['idPerfil']){
            case 21:
//                $this->pdf->SetXY(110, 375);
//                $this->pdf->Cell(0, 0, utf8_decode($nombreJefe[0]['Nombre']));
                $this->pdf->SetXY(110, 379);
                $this->pdf->Cell(0, 0, utf8_decode(mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))));
                if ($datosFirmas['IdRechazo'] != ""){
                    $this->pdf->SetFont("helvetica", "", 11);
                    $this->pdf->SetXY(15, 367);
                    $this->pdf->MultiCell(190, 4, utf8_decode($datosPermiso[0]['textoRechazo']));
                }
                break;
            case 37:
//                $this->pdf->SetXY(150, 375);
//                $this->pdf->Cell(0, 0, utf8_decode($nombreJefe[0]['Nombre']));
                $this->pdf->SetXY(150, 379);
                $this->pdf->Cell(0, 0, utf8_decode(mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))));
                if ($datosFirmas['IdRechazo'] != ""){
                    $this->pdf->SetFont("helvetica", "", 11);
                    $this->pdf->SetXY(15, 367);
                    $this->pdf->MultiCell(190, 4, utf8_decode($datosPermiso[0]['textoRechazo']));
                }
                break;
            default :
//                $this->pdf->SetXY(55, 375);
//                $this->pdf->Cell(0, 0, utf8_decode($nombreJefe[0]['Nombre']));
                $this->pdf->SetXY(55, 379);
                $this->pdf->Cell(0, 0, utf8_decode(mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))));
                if ($datosFirmas['IdRechazo'] != ""){
                    $this->pdf->SetFont("helvetica", "", 11);
                    $this->pdf->SetXY(15, 367);
                    $this->pdf->MultiCell(190, 4, utf8_decode($datosPermiso[0]['textoRechazo']));
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
    
    public function exportExcel() {
        $permisos = $this->DBS->consultaGral('SELECT 
                                                nombreUsuario(tpa.IdUsuario) AS Nombre,
                                                CASE tpa.IdEstatus
                                                        WHEN 6 THEN "Cancelado"
                                                    WHEN 7 THEN "Autorizado"
                                                        WHEN 9 THEN "Pendiente por Autorizar"
                                                        WHEN 10 then "Rechazado"
                                                END as Estatus,
                                                CASE tpa.IdTipoAusencia
                                                        WHEN 1 THEN "Llegada tarde"
                                                    WHEN 2 THEN "Salida Temprano"
                                                        WHEN 3 THEN "No asistirá"
                                                END as TipoAusencia,
                                                cmap.Nombre AS MotivoAusencia,
                                                tpa.FechaDocumento,
                                                tpa.Motivo,
                                                tpa.FolioDocumento,
                                                tpa.FechaAusenciaDesde,
                                                tpa.FechaAusenciaHasta,
                                                tpa.HoraEntrada,
                                                tpa.HoraSalida,
                                                ctrap.Nombre AS TipoRechazo,
                                                nombreUsuario(tpa.IdUsuarioJefe) AS Jefe,
                                                tpa.FechaAutorizacionJefe,
                                                nombreUsuario(tpa.IdUsuarioRH) AS RecursosHumanos,
                                                tpa.FechaAutorizacionRH,
                                                nombreUsuario(tpa.IdUsuarioContabilidad) AS Contador,
                                                tpa.FechaAutorizacionContabilidad
                                            FROM t_permisos_ausencia_rh AS tpa
                                            INNER JOIN cat_v3_motivos_ausencia_personal AS cmap ON tpa.IdMotivoAusencia = cmap.Id
                                            LEFT JOIN cat_v3_tipos_rechazos_ausencia_personal AS ctrap ON tpa.IdRechazo = ctrap.Id');
        
        $this->Excel->createSheet('Permisos', 0);
        $this->Excel->setActiveSheet(0);
        $arrayTitulos = 
            ['Personal',
            'Estado',
            'Tipo Ausencia',
            'Motivo Ausencia',
            'Fecha de Tramite',
            'Motivo',
            'Folio IMSS',
            'Fecha de Ausencia (Desde)',
            'Fecha de Ausencia (Hasta)',
            'Hora de Entrada',
            'Hora de Salida',
            'Tipo de Rechazo',
            'Jefe Directo',
            'Fecha Revisión Jefe',
            'Recursos Humanos',
            'Fecha Revisión RH',
            'Contabilidad',
            'Fecha Revisión Contabilidad'];
        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        $arrayWidth = [30, 20, 20, 20, 15, 40, 15, 15, 15, 15, 15, 30, 30, 15, 30, 15, 30, 15];
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        $this->Excel->setTableTitle("A1", "L1", "Permisos Ausencia", array('titulo'));
        $arrayAlign = ['center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center'];

        $this->Excel->setTableContent('A', 2, $permisos, true, $arrayAlign);

        $time = date("ymd_H_i_s");
        $nombreArchivo = 'Reporte_Permisos_Ausencia_' . $time . '.xlsx';
        $nombreArchivo = trim($nombreArchivo);
        $ruta = '../public/storage/Archivos/RH/Reportes/' . $nombreArchivo;
        
        $path = "../public/storage/Archivos/RH/Reportes";
        if(!is_dir($path)){
            mkdir($path, 755, true);
        }
        $this->Excel->saveFile($ruta);
        
        return ['ruta' => 'https://' . $_SERVER['SERVER_NAME'] . '/storage/Archivos/RH/Reportes/' . $nombreArchivo ];
    }
    public function cancelarPermisoAutorizado(array $datosPermiso){
        
        $jefeDirecto = $this->DBS->consultaGral('SELECT IdJefe, EmailCorporativo 
                                                FROM cat_v3_usuarios 
                                                WHERE Id= (SELECT IdUsuario FROM t_permisos_ausencia_rh 
                                                where Id = '.$datosPermiso['idPermiso'].')');
        $correoJefeDirecto = $this->DBS->consultaGral('SELECT EmailCorporativo 
                                                        FROM cat_v3_usuarios 
                                                        WHERE Id = '. $jefeDirecto[0]['IdJefe']);
        $correosRevisores = $this->DBS->consultaGral('SELECT EmailCorporativo 
                                                        FROM cat_v3_usuarios 
                                                        WHERE IdPerfil in(21, 37)');
        $arregloCorreos = "";
        foreach ($correosRevisores as $value) {
            $arregloCorreos .= $value["EmailCorporativo"] . ",";
        }
        $arregloCorreos .= $correoJefeDirecto[0]["EmailCorporativo"].",".$jefeDirecto[0]["EmailCorporativo"];
                
        $texto = "Se Cancelo el PERMISO DE AUSENCIA de " . $datosPermiso["nombreUsuario"]
                . "<br>EL cual estaba solicitado para " . $datosPermiso["MotivoAusencia"] . " el día " . $datosPermiso["fechaAusencia"] . "
                    <br>El motivo de la Cancelación es: ".$datosPermiso["motivoCancelacion"].".";
        $mensaje = $this->Correo->mensajeCorreo('Cancelar Permiso de Ausencia ', $texto);
        $correoEnviado = $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($arregloCorreos), 'Cancelar Permiso de Ausencia', $mensaje);
        
        $consulta = $this->DBS->actualizar('t_permisos_ausencia_rh', array(
            'IdEstatus' => '6',
            'IdCancelacion' => $datosPermiso['idMotivoCancelacion']
                ), array('Id' => $datosPermiso['idPermiso']));
        
        return $consulta;
    }
}

