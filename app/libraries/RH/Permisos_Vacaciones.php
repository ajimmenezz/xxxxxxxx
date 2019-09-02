<?php

namespace Librerias\RH;

use Controladores\Controller_Base_General as General;
use Librerias\RH\PDFI as PDFI;

class Permisos_Vacaciones extends General {

    private $DBS;
    private $pdf;
    private $Correo;
    private $pdfi;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Solicitud::factory();
        $this->pdf = new PDFI();
        $this->Correo = \Librerias\Generales\Correo::factory();
    }

    public function buscarDepartamento() {
        return $this->DBS->consultaGral('SELECT Nombre, Id FROM adist3_prod.cat_v3_departamentos_siccob');
    }

    public function obtenerTiposAusencia() {
        return $this->DBS->consultaGral('SELECT Id, Nombre FROM cat_v3_tipos_ausencia_personal WHERE Flag = 1');
    }

    public function obtenerMotivoAusencia(array $datos) {
        return $this->DBS->consultaGral('SELECT
                                                tcmarcta.IdTipoAusencia,
                                                cvmap.Nombre,
                                                cvmap.Observaciones,
                                                cvmap.Id
                                        FROM
                                            cat_v3_tipos_ausencia_personal AS cvap
                                        INNER JOIN t_cat_motivos_ausencia_relacion_cat_tipos_ausencia AS tcmarcta
                                        ON tcmarcta.IdTipoAusencia = cvap.Id
                                        INNER JOIN cat_v3_motivos_ausencia_personal AS cvmap
                                        ON cvmap.Id = tcmarcta.IdMotivoAusencia
                                        WHERE cvmap.Flag = "1"
                                        AND tcmarcta.IdTipoAusencia = "' . $datos['tipoAusencia'] . '"
                                        ORDER BY cvmap.Nombre ASC');
    }

    public function obtenerMotivoRechazo() {
        return $this->DBS->consultaGral('select * from cat_v3_tipos_rechazos_ausencia_personal');
    }

    public function getDatos() {
        return array("idSesion", $_SESSION['id']);
    }

    public function obtenerPermisosAusencia($idUsuario) {
        return $this->DBS->consultaGral('SELECT tpa.Id, tpa.FechaDocumento, tap.Nombre AS IdTipoAusencia, map.Nombre AS IdMotivoAusencia, 
                    tpa.FechaAusenciaDesde, tpa.FechaAusenciaHasta, tpa.HoraEntrada, tpa.HoraSalida, tpa.IdEstatus, tpa.Archivo, tpa.IdUsuarioJefe, 
                    tpa.IdUsuarioRH, tpa.IdUsuarioContabilidad, tpa.IdUsuarioDireccion 
                    FROM t_permisos_ausencia_rh AS tpa INNER JOIN cat_v3_tipos_ausencia_personal AS tap ON tpa.IdTipoAusencia = tap.Id
                    INNER JOIN cat_v3_motivos_ausencia_personal AS map ON tpa.IdMotivoAusencia = map.Id WHERE IdUsuario = "' . $idUsuario . '" 
                    AND DATE(FechaDocumento) BETWEEN CURDATE()-20 AND CURDATE()');
    }

    public function jefeDirecto($idUsuario) {
        return $this->DBS->consultaGral(""
                        . "select "
                        . "IdJefe "
                        . "from cat_v3_usuarios "
                        . "where Id = '" . $idUsuario . "'");
    }

    public function jefeDirectoidPermiso($idPermiso) {
        return $this->DBS->consultaGral("
            SELECT 
            IdJefe FROM cat_v3_usuarios AS cu 
            INNER JOIN t_permisos_ausencia_rh AS tpar ON cu.Id=tpar.IdUsuario 
            WHERE tpar.Id='" . $idPermiso . "'");
    }

    public function correoJefeDirecto($idJefe) {
        return $this->DBS->consultaGral("SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id='" . $idJefe . "'");
    }

    public function generarPDF($datosPermisos) {
        $this->construirPDF($datosPermisos);

        if ($datosPermisos['evidenciaIncapacidad'] != "") {
            $this->revisarArchivoAdjunto($datosPermisos);
        }

        $documento = 'PermisoAusencia' . $datosPermisos["idUsuario"] . date("G") . "-" . date("i");
        $carpeta = $this->pdf->definirArchivo('Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'], $documento);
        $this->pdf->Output('F', $carpeta, true);

        $idPermisoGenerado = $this->ajustarInformacionDBS($datosPermisos, $documento);
        $correoEnviado = $this->enviarCorreoPermiso($datosPermisos, $asunto = "Generado", $carpeta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . $carpeta, 'correo' =>$correoEnviado];
    }

    public function revisarArchivoAdjunto($datosPermisos) {
        $this->guardarImagen($datosPermisos);

        $nombreArchivo = explode("\\", $datosPermisos['evidenciaIncapacidad']);
        $divideNombreArchivo = preg_split("/[\s-]+/", $nombreArchivo[2]);
        $concatenaNombre = "";
        if (count($divideNombreArchivo) < 2) {
            $nuevoNombreArchivo = $divideNombreArchivo[0];
        } else {
            for ($i = 0; $i < count($divideNombreArchivo); $i++) {
                $concatenaNombre .= $divideNombreArchivo[$i] . '_';
            }
            $nuevoNombreArchivo = substr($concatenaNombre, 0, -1);
        }

        try {
            $paginasArchivo = $this->pdf->setSourceFile('../public/storage/Archivos/Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'] . '/evidenciasMedicas/' . $nuevoNombreArchivo);
            for ($i = 1; $i <= $paginasArchivo; $i++) {
                $this->pdf->AddPage();
                $tplIdx = $this->pdf->importPage($i);
                $this->pdf->useTemplate($tplIdx, 10, 0, 190);
            }
        } catch (\InvalidArgumentException $ex) {
            $this->pdf->AddPage();
            $this->pdf->SetFont("helvetica", "B", 11);
            $this->pdf->Cell(14, 0, "Error al Adjuntar el Archivo");
        }
    }

    public function guardarImagen($datosPermisos) {
        if (!empty($_FILES)) {
            $CI = parent::getCI();
            $carpeta = 'Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'] . '/evidenciasMedicas/';
            $archivos = implode(',', setMultiplesArchivos($CI, 'evidenciasIncapacidad', $carpeta));
        } else {
            return 'otraImagen';
        }
    }

    public function ajustarInformacionDBS($datosPermisos, $documento) {
        $archivo = 'Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'] . '/' . $documento . '.pdf';
        if ($datosPermisos['evidenciaIncapacidad'] != "") {
            $nombreArchivo = explode("\\", $datosPermisos['evidenciaIncapacidad']);
            $divideNombreArchivo = preg_split("/[\s-]+/", $nombreArchivo[2]);
            $concatenaNombre = "";
            if (count($divideNombreArchivo) < 2) {
                $evidencia = 'Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'] . '/evidenciasMedicas/' . $divideNombreArchivo[0];
            } else {
                for ($i = 0; $i < count($divideNombreArchivo); $i++) {
                    $concatenaNombre .= $divideNombreArchivo[$i] . '_';
                }
                $evidencia = 'Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'] . '/evidenciasMedicas/' . substr($concatenaNombre, 0, -1);
            }
        } else {
            $evidencia = "";
        }

        switch ($datosPermisos['tipoAusencia']) {

            case '1':
                $horaEntrada = $datosPermisos['horaAusencia'];
                $horaSalida = "";
                break;
            case '2':
                $horaEntrada = "";
                $horaSalida = $datosPermisos['horaAusencia'];
                break;
            case '3':
                $horaEntrada = "";
                $horaSalida = "";
                break;
        }
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $this->DBS->insertar('t_permisos_ausencia_rh', array(
            'IdUsuario' => $datosPermisos['idUsuario'],
            'IdEstatus' => '9',
            'IdTipoAusencia' => $datosPermisos['tipoAusencia'],
            'IdMotivoAusencia' => $datosPermisos['motivoAusencia'],
            'FechaDocumento' => $fecha,
            'FechaAusenciaDesde' => $datosPermisos['fechaPermisoDesde'],
            'FechaAusenciaHasta' => $datosPermisos['fechaPermisoHasta'],
            'HoraEntrada' => $horaEntrada,
            'HoraSalida' => $horaSalida,
            'Motivo' => $datosPermisos['descripcionAusencia'],
            'FolioDocumento' => $datosPermisos['citaFolio'],
            'Archivo' => $archivo,
            'ArchivosOriginales' => $evidencia
                )
        );
        return $this->DBS->consultaGral('SELECT LAST_INSERT_ID()');
    }

    public function revisarInformacionAusencia($idPermiso) {
        $informacionPermisoAusencia['datosAusencia'] = $this->DBS->consultaGral('SELECT CONCAT(trp.Nombres, " ",trp.ApPaterno, " ",trp.ApMaterno) AS Nombre,
                 cp.Nombre AS Puesto, cds.Nombre AS Departamento, tpa.Id, IdEstatus, IdTipoAusencia, IdMotivoAusencia, FechaAusenciaDesde, FechaAusenciaHasta, 
                 HoraEntrada, HoraSalida, Motivo, FolioDocumento, Archivo, ArchivosOriginales, IdUsuarioJefe FROM t_permisos_ausencia_rh AS tpa 
                 INNER JOIN t_rh_personal AS trp ON tpa.IdUsuario = trp.IdUsuario INNER JOIN cat_v3_usuarios AS cu ON tpa.IdUsuario=cu.Id 
                 INNER JOIN cat_perfiles AS cp ON cu.IdPerfil=cp.Id INNER JOIN cat_v3_departamentos_siccob AS cds ON cp.IdDepartamento=cds.Id 
                 WHERE tpa.Id ="' . $idPermiso['idPermiso'] . '"');
        $informacionPermisoAusencia['tiposAusencia'] = $this->obtenerTiposAusencia();
        switch ($idPermiso['tipoAusencia']) {
            case 'Llegada tarde':
                $datos['tipoAusencia'] = 1;
                break;
            case 'Salida Temprano':
                $datos['tipoAusencia'] = 2;
                break;
            case 'No asistirá':
                $datos['tipoAusencia'] = 3;
                break;
        }
        $informacionPermisoAusencia['motivosAusencia'] = $this->obtenerMotivoAusencia($datos);

        if ($informacionPermisoAusencia['datosAusencia'][0]['IdEstatus'] == '9' && $informacionPermisoAusencia['datosAusencia'][0]['IdUsuarioJefe'] == NULL) {
            return array('formulario' => parent::getCI()->load->view('RH/Modal/formularioActualizarAusencia', $informacionPermisoAusencia, TRUE));
        } else {
            return "En revision";
        }
    }

    public function actualizarPermiso($datosPermisos) {

        $this->construirPDF($datosPermisos);
        $rutaArchivo = explode("/", $datosPermisos['pdf']);
        $nombreArchivo = explode(".", $rutaArchivo[2]);
        $carpeta = $this->pdf->definirArchivo($rutaArchivo[0] . "/" . $rutaArchivo[1], $nombreArchivo[0]);
        $this->pdf->Output('F', $carpeta, true);

        $this->revisarActualizarPermiso($datosPermisos);

        $correoEnviado = $this->enviarCorreoPermiso($datosPermisos, $asunto = "Actualizado", $carpeta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . $carpeta, 'correo' =>$correoEnviado];
    }

    public function actualizarPermisoArchivo($datosPermisos) {
        $this->construirPDF($datosPermisos);

        $rutaArchivo = explode("/", $datosPermisos['pdf']);
        $idusuario = explode("_", $rutaArchivo[1]);
        $revisor = array('idUsuario' => $idusuario[1]);
        $resultado = array_merge($datosPermisos, $revisor);

        $this->revisarArchivoAdjunto($resultado);

        $nombreArchivo = explode(".", $rutaArchivo[2]);
        $carpeta = $this->pdf->definirArchivo($rutaArchivo[0] . "/" . $rutaArchivo[1], $nombreArchivo[0]);
        $this->pdf->Output('F', $carpeta, true);

        $this->revisarActualizarPermiso($datosPermisos);


        $correoEnviado = $this->enviarCorreoPermiso($datosPermisos, $asunto = "Actualizado", $carpeta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . $carpeta, 'correo' =>$correoEnviado];
    }

    public function revisarActualizarPermiso($datosPermisos) {
        if ($datosPermisos['evidenciaIncapacidad'] !== "" && ($datosPermisos['motivoAusencia'] == '3' || $datosPermisos['motivoAusencia'] == '4')) {
            $nombreArchivo = explode("\\", $datosPermisos['evidenciaIncapacidad']);
            $divideNombreArchivo = preg_split("/[\s-]+/", $nombreArchivo[2]);
            $concatenaNombre = "";
            $idUsuario = explode("/", $datosPermisos['pdf']);
            if (count($divideNombreArchivo) < 2) {
                $evidencia = 'Permisos_Ausencia/' . $idUsuario[1] . '/evidenciasMedicas/' . $divideNombreArchivo[0];
            } else {
                for ($i = 0; $i < count($divideNombreArchivo); $i++) {
                    $concatenaNombre .= $divideNombreArchivo[$i] . '_';
                }
                $evidencia = 'Permisos_Ausencia/' . $idUsuario[1] . '/evidenciasMedicas/' . substr($concatenaNombre, 0, -1);
            }
        } else {
            $evidencia = "";
        }
        switch ($datosPermisos['tipoAusencia']) {
            case '1':
                $horaEntrada = $datosPermisos['horaAusencia'];
                $horaSalida = "";
                break;
            case '2':
                $horaEntrada = "";
                $horaSalida = $datosPermisos['horaAusencia'];
                break;
            case '3':
                $horaEntrada = "";
                $horaSalida = "";
                break;
        }
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $this->DBS->actualizar('t_permisos_ausencia_rh', array(
            'IdTipoAusencia' => $datosPermisos['tipoAusencia'],
            'IdMotivoAusencia' => $datosPermisos['motivoAusencia'],
            'FechaDocumento' => $fecha,
            'FechaAusenciaDesde' => $datosPermisos['fechaPermisoDesde'],
            'FechaAusenciaHasta' => $datosPermisos['fechaPermisoHasta'],
            'HoraEntrada' => $horaEntrada,
            'HoraSalida' => $horaSalida,
            'Motivo' => $datosPermisos['descripcionAusencia'],
            'FolioDocumento' => $datosPermisos['citaFolio'],
            'ArchivosOriginales' => $evidencia
                ), array('Id' => $datosPermisos['idPermiso']));
    }

    public function enviarCorreoPermiso($datosPermisos, $asunto, $carpeta) {
        if ($datosPermisos['idUsuario'] == "") {
            $idJefe = $this->jefeDirectoidPermiso($datosPermisos['idPermiso']);
        } else {
            $idJefe = $this->jefeDirecto($datosPermisos['idUsuario']);
        }
        $correoJefe = $this->correoJefeDirecto($idJefe[0]['IdJefe']);
        $texto = '<p>Se ha ' . $asunto . ' el permiso de ausencia por parte de <strong>' . $datosPermisos['nombre'] . ',</strong> 
                    se requiere su concentimiento o rechazo del mismo.</p><br><br>
                    Permiso Solicitado: <p>';
        switch ($datosPermisos['tipoAusencia']) {
            case '1':
                $texto .= 'Llegada Tarde ';
                break;
            case '2':
                $texto .= 'Salida Temprano ';
                break;
            case '3':
                $texto .= 'No Asistirá ';
                break;
        }
        switch ($datosPermisos['motivoAusencia']) {
            case '1':
                $texto .= 'CONSULTA MEDICO IMSS';
                break;
            case '2':
                $texto .= 'CONSULTA DENTISTA IMSS';
                break;
            case '3':
                $texto .= 'PERMISOS POR RAZONES DE TRABAJO EXTERNO';
                break;
            case '4':
                $texto .= 'PERMISOS POR CURSOS DE CAPACITACION';
                break;
            case '5':
                $texto .= 'ASUNTOS PERSONALES';
                break;
            case '6':
                $texto .= 'CONSULTA MEDICO PARTICULAR';
                break;
            case '7':
                $texto .= 'CONSULTA DENTISTA PARTICULAR';
                break;
            case '8':
                $texto .= 'INCAPACIDAD IMSS DEL TRABAJADOR';
                break;
            case '9':
                $texto .= 'CONSULTA MEDICO O DENTISTA IMSS';
                break;
            case '10':
                $texto .= 'ASUNTOS PERSONALES';
                break;
            case '11':
                $texto .= 'CONSULTA MEDICO PARTICULAR';
                break;
            case '12':
                $texto .= 'CONSULTA DENTISTA PARTICULAR';
                break;
        }
        $texto .= ' para el día ' . $datosPermisos['fechaPermisoDesde'] . '</p><br><br>
                    <a href="http://' . $_SERVER['SERVER_NAME'] . $carpeta . '">Archivo</a>';
        $mensaje = $this->Correo->mensajeCorreo('Permiso de Ausencia ' . $asunto, $texto);
        $correoEnviado = $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($correoJefe[0]['EmailCorporativo']), 'Permiso de Ausencia', $mensaje);
        return $correoEnviado;
    }

    public function cancelarPermiso($idPermiso) {
        $this->DBS->actualizar('t_permisos_ausencia_rh', array(
            'IdEstatus' => '6'
                ), array('Id' => $idPermiso['idPermiso']));

        return $idPermiso['idPermiso'];
    }

    public function construirPDF($datosPermisos) {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $paginasArchivo = $this->pdf->setSourceFile('../public/storage/Archivos/Archivos_Template/Control_ausencias_personal.pdf');
        $this->pdf->AddPage();
        $tplIdx = $this->pdf->importPage(1);
        $this->pdf->useTemplate($tplIdx, 0, 0, 210, 420, true);
        $this->pdf->SetTextColor(243, 18, 18);
        $this->pdf->SetXY(165, 10);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(14, 0, "Falta Autorizar");
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetXY(60, 48);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(0, 0, utf8_decode($fecha));
        $this->pdf->SetXY(148, 48);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['fechaPermisoDesde'] . $datosPermisos['fechaPermisoHasta']));
        $this->pdf->SetXY(80, 62);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['nombre']));
        $this->pdf->SetXY(37, 72);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['puesto']));
        $this->pdf->SetXY(48, 82);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['departamento']));

        $hora = explode(" ", $datosPermisos["horaAusencia"]);
        switch ($datosPermisos['tipoAusencia']) {
            case '1':
            case '3':
                $this->pdf->SetX(82);
                $x = $this->pdf->GetX();
                break;
            case '2':
                $this->pdf->SetX(107);
                $x = $this->pdf->GetX();
                break;
        }

        switch ($datosPermisos['motivoAusencia']) {
            case '1':
                $this->pdf->SetXY($x, 116);
                $this->pdf->Cell(0, 0, utf8_decode($hora[0]));
                break;
            case '2':
                $this->pdf->SetXY($x, 126);
                $this->pdf->Cell(0, 0, utf8_decode($hora[0]));
                break;
            case '3':
                $this->pdf->SetXY($x, 137);
                $this->pdf->Cell(0, 0, utf8_decode($hora[0]));
                break;
            case '4':
                $this->pdf->SetXY($x, 148);
                $this->pdf->Cell(0, 0, utf8_decode($hora[0]));
                break;
            case '5':
                $this->pdf->SetXY($x, 158);
                $this->pdf->Cell(0, 0, utf8_decode($hora[0]));
                break;
            case '6':
                $this->pdf->SetXY($x, 169);
                $this->pdf->Cell(0, 0, utf8_decode($hora[0]));
                break;
            case '7':
                $this->pdf->SetXY($x, 179);
                $this->pdf->Cell(0, 0, utf8_decode($hora[0]));
                break;
            case '8':
                $this->pdf->SetXY($x, 213);
                $this->pdf->Cell(0, 0, utf8_decode("X"));
                break;
            case '9':
                $this->pdf->SetXY($x, 223);
                $this->pdf->Cell(0, 0, utf8_decode("X"));
                break;
            case '10':
                $this->pdf->SetXY($x, 234);
                $this->pdf->Cell(0, 0, utf8_decode("X"));
                break;
            case '11':
                $this->pdf->SetXY($x, 244);
                $this->pdf->Cell(0, 0, utf8_decode("X"));
                break;
            case '12':
                $this->pdf->SetXY($x, 255);
                $this->pdf->Cell(0, 0, utf8_decode("X"));
                break;
        }

        $this->pdf->SetXY(55, 266);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->MultiCell(135, 4, utf8_decode($datosPermisos["descripcionAusencia"]));

        $folioDocumento = $this->DBS->consultaGral("SELECT COUNT(Archivo) AS total FROM t_permisos_ausencia_rh");
        $cuenta = strlen($folioDocumento[0]['total']);
        $cerosFolio = '';
        for ($i = $cuenta; $i < 10; $i++) {
            $cerosFolio .= '0';
        }
        $this->pdf->SetFont("helvetica", "", 7);
        $this->pdf->SetXY(14, 399);
        $this->pdf->Cell(0, 0, utf8_decode("Folio: " . $cerosFolio . $folioDocumento[0]['total']));
    }

    public function enviarCorreoSiccob() {
        $totalInacistencias = $this->DBS->consultaGral('SELECT CONCAT(trhp.Nombres, " ",trhp.ApPaterno, " ",trhp.ApMaterno) AS Nombre, 
                                    IdTipoAusencia, IdMotivoAusencia, FechaAusenciaDesde, FechaAusenciaHasta, HoraEntrada, HoraSalida, Motivo 
                                    FROM t_permisos_ausencia_rh AS tpa 
                                    INNER JOIN cat_v3_usuarios AS cu ON tpa.IdUsuario = cu.Id 
                                    INNER JOIN t_rh_personal AS trhp ON cu.Id = trhp.IdUsuario 
                                    where tpa.IdEstatus = 7 AND FechaAusenciaDesde = CURDATE()+1');

        $totalCorreos = $this->DBS->consultaGral("SELECT EmailCorporativo FROM cat_v3_usuarios WHERE EmailCorporativo <> 'null'");

        $texto = 'El día de mañana las siguientes personas estarán ausentes:<br><br>';
        if ($totalInacistencias != false) {
            foreach ($totalInacistencias as $inacistencias) {
                $texto .= '<strong>' . $inacistencias['Nombre'] . ',</strong> ';
                switch ($inacistencias['IdTipoAusencia']) {
                    case '1':
                        $texto .= 'Llegara a las ' . $inacistencias['HoraEntrada'];
                        break;
                    case '2':
                        $texto .= 'Saldrá a las  ' . $inacistencias['HoraSalida'];
                        break;
                    case '3':
                        $texto .= 'No Asistirá';
                        break;
                }
                switch ($inacistencias['IdMotivoAusencia']) {
                    case '1':
                        $texto .= ' por motivo Personal.';
                        break;
                    case '2':
                        $texto .= ' por motivo de Trabajo/Comisión.';
                        break;
                    case '3':
                        $texto .= ' por Cita Médica.';
                        break;
                    case '4':
                        $texto .= ' por motivo de IMSS Incapacidad.';
                        break;
                }
                $texto .= '<br>';
            }
            $mensaje = $this->Correo->mensajeCorreo('Ausencia de Personal', $texto);

            $respuestaCorreo = $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $totalCorreos, 'Ausencia de Personal', $mensaje);
        }
    }

    public function obtenerDatos() {
        $idUsuarioConsulta = $_SESSION['Id'];
        $idPerfilUsuarioConsulta = $_SESSION['Id'];

        return array('ID' => $idUsuarioConsulta, "Perfil" => $idPerfilUsuarioConsulta);
    }

}
