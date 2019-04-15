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

    public function obtenerMotivoAusencia() {
        return $this->DBS->consultaGral('SELECT Id, Nombre FROM cat_v3_motivos_ausencia_personal WHERE Flag = 1');
    }

    public function obtenerPermisosAusencia($idUsuario) {
        return $this->DBS->consultaGral('SELECT Id, FechaDocumento, IdTipoAusencia, IdMotivoAusencia, FechaAusenciaDesde, FechaAusenciaHasta, 
                HoraEntrada, HoraSalida, IdEstatus, IdUsuarioJefe, IdUsuarioRH, IdUsuarioContabilidad, IdUsuarioDireccion 
                FROM t_permisos_ausencia_rh WHERE IdUsuario ="' . $idUsuario . '"');
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

        $documento = 'PermisoAusencia' . date("G") . "-" . date("i");
        $carpeta = $this->pdf->definirArchivo('Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'], $documento);
        $this->pdf->Output('F', $carpeta, true);

        $idPermisoGenerado = $this->ajustarInformacionDBS($datosPermisos, $documento);

        $this->enviarCorreoPermiso($datosPermisos, $asunto = "Generado", $carpeta);

        $carpetaFolio = $this->agregarFolioPDF($idPermisoGenerado[0]['LAST_INSERT_ID()']);

        return $carpetaFolio;
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
            $paginasArchivo = $this->pdf->setSourceFile('../public/storage/Archivos/Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'] . '/' . $nuevoNombreArchivo);
            for ($i = 1; $i <= $paginasArchivo; $i++) {
                $this->pdf->AddPage();
                $tplIdx = $this->pdf->importPage($i);
                $this->pdf->useTemplate($tplIdx, 10, 0, 190);
            }
        } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $ex) {
            $ex->getMessage();
        }
    }

    public function guardarImagen($datosPermisos) {
        if (!empty($_FILES)) {
            $CI = parent::getCI();
            $carpeta = 'Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'] . '/';
            $archivos = implode(',', setMultiplesArchivos($CI, 'evidenciasIncapacidad', $carpeta));
        } else {
            return 'otraImagen';
        }
    }

    public function ajustarInformacionDBS($datosPermisos, $documento) {
        $archivo = 'Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'] . '/' . $documento . '.pdf';

        if ($datosPermisos['fechaPermisoHasta'] != $datosPermisos['fechaPermisoDesde']) {
            $fechaPermisoHasta = $datosPermisos["fechaPermisoHasta"];
        } else {
            $fechaPermisoHasta = "";
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
            'FechaAusenciaHasta' => $fechaPermisoHasta,
            'HoraEntrada' => $horaEntrada,
            'HoraSalida' => $horaSalida,
            'Motivo' => $datosPermisos['descripcionAusencia'],
            'FolioDocumento' => $datosPermisos['citaFolio'],
            'Archivo' => $archivo
                )
        );
        return $this->DBS->consultaGral('SELECT LAST_INSERT_ID()');
    }

    public function revisarInformacionAusencia($idPermiso) {
        $informacionPermisoAusencia['datosAusencia'] = $this->DBS->consultaGral('SELECT CONCAT(trp.Nombres, " ",trp.ApPaterno, " ",trp.ApMaterno) AS Nombre,
                 cp.Nombre AS Puesto, cds.Nombre AS Departamento, tpa.Id, IdEstatus, IdTipoAusencia, IdMotivoAusencia, FechaAusenciaDesde, FechaAusenciaHasta, 
                 HoraEntrada, HoraSalida, Motivo, FolioDocumento, Archivo, IdUsuarioJefe FROM t_permisos_ausencia_rh AS tpa 
                 INNER JOIN t_rh_personal AS trp ON tpa.IdUsuario = trp.IdUsuario INNER JOIN cat_v3_usuarios AS cu ON tpa.IdUsuario=cu.Id 
                 INNER JOIN cat_perfiles AS cp ON cu.IdPerfil=cp.Id INNER JOIN cat_v3_departamentos_siccob AS cds ON cp.IdDepartamento=cds.Id 
                 WHERE tpa.Id ="' . $idPermiso['idPermiso']. '"');
        $informacionPermisoAusencia['tiposAusencia'] = $this->obtenerTiposAusencia();
        $informacionPermisoAusencia['motivosAusencia'] = $this->obtenerMotivoAusencia();

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

        $this->enviarCorreoPermiso($datosPermisos, $asunto = "Actualizado", $carpeta);

        $carpetaFolio = $this->agregarFolioPDF($datosPermisos['idPermiso']);

        return $carpetaFolio;
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

        $this->enviarCorreoPermiso($datosPermisos, $asunto = "Actualizado", $carpeta);

        $carpetaFolio = $this->agregarFolioPDF($datosPermisos['idPermiso']);

        return $carpetaFolio;
    }

    public function revisarActualizarPermiso($datosPermisos) {

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
            'FolioDocumento' => $datosPermisos['citaFolio']
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
                $texto .= 'con motivo Personal';
                break;
            case '2':
                $texto .= 'con motivo Trabajo/Comisión';
                break;
            case '3':
                $texto .= 'con motivo IMSS Cita Médica';
                break;
            case '4':
                $texto .= 'con motivo IMSS Incapacidad';
                break;
        }
        $texto .= ' para el día ' . $datosPermisos['fechaPermisoDesde'] . '</p><br><br>
                    <a href="http://adist/storage/Archivos/'.$carpeta.'">Archivo</a>';
        $mensaje = $this->Correo->mensajeCorreo('Permiso de Ausencia ' . $asunto, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($correoJefe[0]['EmailCorporativo']), 'Permiso de Ausencia', $mensaje);
    }

    public function cancelarPermiso($idPermiso) {
        $this->DBS->actualizar('t_permisos_ausencia_rh', array(
            'IdEstatus' => '6'
                ), array('Id' => $idPermiso['idPermiso']));

        return $idPermiso['idPermiso'];
    }

    public function construirPDF($datosPermisos) {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        //encabezado del archivo PDF
        $this->pdf->AddPage();
        $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
        $this->pdf->SetXY(0, 18);
        $this->pdf->SetFont("helvetica", "B", 18);
        $this->pdf->Cell(0, 0, "Control de Ausencias del Personal", 0, 0, 'C');
        $this->pdf->SetXY(0, 27);
        $this->pdf->SetFont("helvetica", "", 9);
        $this->pdf->Cell(0, 0, "Soluciones Integrales para empresas Integrales", 0, 0, 'R');

        $this->pdf->Line(5, 32, 205, 32);

        //datos personales
        $this->pdf->SetXY(10, 40);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(14, 0, "Fecha de Tramite:");
        $this->pdf->RoundedRect(10, 43, 90, 6, 1, '1234');
        $this->pdf->SetXY(10, 46);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(0, 0, utf8_decode($fecha));

        $this->pdf->SetXY(110, 40);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(18, 0, "Nombre:");
        $this->pdf->RoundedRect(110, 43, 90, 6, 1, '1234');
        $this->pdf->SetXY(110, 46);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['nombre']));

        $this->pdf->SetXY(10, 56);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(14, 0, "Departamento:");
        $this->pdf->RoundedRect(10, 59, 90, 6, 1, '1234');
        $this->pdf->SetXY(10, 62);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['departamento']));

        $this->pdf->SetXY(110, 56);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(14, 0, "Puesto:");
        $this->pdf->RoundedRect(110, 59, 90, 6, 1, '1234');
        $this->pdf->SetXY(110, 62);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['puesto']));


        $this->pdf->Line(5, 70, 205, 70);

        //descripcion completa de ausencia
        $this->pdf->SetXY(10, 80);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(30, 0, utf8_decode("Tipo de Ausencia:"));
        $this->pdf->RoundedRect(10, 83, 60, 6, 1, '1234');
        $this->pdf->SetXY(10, 86);
        $this->pdf->SetFont("helvetica", "", 10);
        switch ($datosPermisos['tipoAusencia']) {
            case '1':
                $this->pdf->Cell(0, 0, utf8_decode("Llegada Tarde"));
                break;
            case '2':
                $this->pdf->Cell(0, 0, utf8_decode("Salida Temprano"));
                break;
            case '3':
                $this->pdf->Cell(0, 0, utf8_decode("No Asistirá"));
                break;
        }

        $this->pdf->SetXY(75, 80);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(30, 0, utf8_decode("Motivo de Ausencia:"));
        $this->pdf->RoundedRect(75, 83, 60, 6, 1, '1234');
        $this->pdf->SetXY(75, 86);
        $this->pdf->SetFont("helvetica", "", 10);
        switch ($datosPermisos['motivoAusencia']) {
            case '1':
                $this->pdf->Cell(0, 0, utf8_decode("Personal"));
                break;
            case '2':
                $this->pdf->Cell(0, 0, utf8_decode("Trabajo/Comisión"));
                break;
            case '3':
                $this->pdf->Cell(0, 0, utf8_decode("IMSS Cita Médica"));
                break;
            case '4':
                $this->pdf->Cell(0, 0, utf8_decode("IMSS Incapacidad"));
                break;
        }

        $this->pdf->SetXY(140, 80);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(30, 0, utf8_decode("Cita o Folio:"));
        $this->pdf->RoundedRect(140, 83, 60, 6, 1, '1234');
        if ($datosPermisos['citaFolio'] != "") {
            $this->pdf->SetXY(140, 86);
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['citaFolio']));
        }else{
            $this->pdf->SetXY(140, 86);
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(0, 0, utf8_decode("   ----------"));
        }

        $this->pdf->SetXY(10, 93);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(30, 0, utf8_decode("Fecha de Solicitud"));

        $this->pdf->SetXY(10, 97);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(30, 0, utf8_decode("Desde:"));
        $this->pdf->RoundedRect(10, 100, 60, 6, 1, '1234');
        $this->pdf->SetXY(10, 104);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(0, 0, utf8_decode($datosPermisos["fechaPermisoDesde"]));

        $this->pdf->SetXY(75, 97);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(30, 0, utf8_decode("Hasta:"));
        $this->pdf->RoundedRect(75, 100, 60, 6, 1, '1234');
        $this->pdf->SetXY(75, 104);
        $this->pdf->SetFont("helvetica", "", 10);
        if ($datosPermisos['fechaPermisoHasta'] != $datosPermisos['fechaPermisoDesde']) {
            $this->pdf->Cell(0, 0, utf8_decode($datosPermisos["fechaPermisoHasta"]));
        } else {
            $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['fechaPermisoDesde']));
        }

        switch ($datosPermisos['tipoAusencia']) {
            case '1':
                $this->pdf->SetXY(140, 97);
                $this->pdf->SetFont("helvetica", "B", 11);
                $this->pdf->Cell(30, 0, utf8_decode("Hora de Entrada:"));
                $this->pdf->RoundedRect(140, 100, 60, 6, 1, '1234');
                $this->pdf->SetXY(140, 104);
                $this->pdf->SetFont("helvetica", "", 10);
                $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['horaAusencia']));
                break;
            case '2':
                $this->pdf->SetXY(140, 97);
                $this->pdf->SetFont("helvetica", "B", 11);
                $this->pdf->Cell(30, 0, utf8_decode("Hora de Salida:"));
                $this->pdf->RoundedRect(140, 100, 60, 6, 1, '1234');
                $this->pdf->SetXY(140, 104);
                $this->pdf->SetFont("helvetica", "", 10);
                $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['horaAusencia']));
                break;
            case '3':
                $this->pdf->SetXY(140, 97);
                $this->pdf->SetFont("helvetica", "B", 11);
                $this->pdf->Cell(30, 0, utf8_decode("Hora:"));
                $this->pdf->RoundedRect(140, 100, 60, 6, 1, '1234');
                $this->pdf->SetXY(140, 104);
                $this->pdf->SetFont("helvetica", "", 10);
                $this->pdf->Cell(0, 0, "   ----------");
                break;
        }

        $this->pdf->SetXY(10, 111);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(30, 0, utf8_decode("Descripción de Ausencia:"));
        $this->pdf->RoundedRect(10, 114, 190, 40, 1, '1234');
        if ($datosPermisos['descripcionAusencia'] != "") {
            $this->pdf->SetXY(10, 116);
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->MultiCell(190, 4, utf8_decode($datosPermisos["descripcionAusencia"]));
        }else{
            switch ($datosPermisos['motivoAusencia']) {
                case '3':
                    $this->pdf->SetXY(10, 116);
                    $this->pdf->SetFont("helvetica", "", 10);
                    $this->pdf->MultiCell(190, 4, utf8_decode("IMSS Cita Médica"));
                    break;
                case '4':
                    $this->pdf->SetXY(10, 116);
                    $this->pdf->SetFont("helvetica", "", 10);
                    $this->pdf->MultiCell(190, 4, utf8_decode("IMSS Incapacidad"));
                    break;
            }
        }

        //pie de documento
        $this->pdf->SetFont("helvetica", "", 7);
        $this->pdf->SetXY(140, 276);
        $this->pdf->Cell(0, 0, utf8_decode("Fecha de Documento: " . $fecha));
    }

    public function agregarFolioPDF($idPermisoGenerado) {
        $this->pdfi = new PDFI();
        $direccionArchivo = $this->DBS->consultaGral("SELECT Archivo FROM t_permisos_ausencia_rh WHERE Id='" . $idPermisoGenerado ."'");

        $rutaArchivo = explode("/", $direccionArchivo[0]['Archivo']);
        $idUser = explode("_", $rutaArchivo[1]);
        $folioDocumento = $this->DBS->consultaGral("SELECT COUNT(Archivo) AS total FROM t_permisos_ausencia_rh WHERE IdUsuario='" . $idUser[1] ."'");

        $paginasArchivo = $this->pdfi->setSourceFile('../public/storage/Archivos/Permisos_Ausencia/' . $rutaArchivo[1] . '/' . $rutaArchivo[2]);

        $this->pdfi->AddPage();
        $tplIdx = $this->pdfi->importPage(1);
        $this->pdfi->useTemplate($tplIdx, 0, 0, 210, 297, true);

        $cuenta = strlen($folioDocumento[0]['total']);
        $cerosFolio = '';
        for ($i = $cuenta; $i < 10; $i++) {
            $cerosFolio .= '0';
        }
        $this->pdfi->SetFont("helvetica", "", 7);
        $this->pdfi->SetXY(10, 276);
        $this->pdfi->Cell(0, 0, utf8_decode("Folio: " . $cerosFolio . $folioDocumento[0]['total']));

        if ($paginasArchivo > 1) {
            for ($i = 2; $i <= $paginasArchivo; $i++) {
                $this->pdfi->AddPage();
                $tplIdx = $this->pdfi->importPage($i);
                $this->pdfi->useTemplate($tplIdx, 0, 0, 210, 297, true);
            }
        }

        $nombreDocumento = explode(".", $rutaArchivo[2]);

        $carpeta = $this->pdfi->definirArchivo('Permisos_Ausencia/' . $rutaArchivo[1], $nombreDocumento[0]);
        $this->pdfi->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);
        return $carpeta;
    }

}
