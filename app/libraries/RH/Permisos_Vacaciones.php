<?php

namespace Librerias\RH;

use Controladores\Controller_Base_General as General;
use Librerias\RH\PDFI as PDFI;
use Librerias\V2\PaquetesGenerales\Utilerias\Archivo as Archivo;

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
                                                cvmap.Id,
                                                cvmap.Archivo
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
        return $this->DBS->consultaGral('SELECT tpa.Id, tpa.FechaDocumento, tap.Nombre AS IdTipoAusencia, map.Nombre AS IdMotivoAusencia, map.Archivo AS ArchivoExtra,
                    tpa.FechaAusenciaDesde, tpa.FechaAusenciaHasta, tpa.HoraEntrada, tpa.HoraSalida, tpa.IdEstatus, tpa.Archivo, tpa.IdUsuarioJefe, 
                    tpa.IdUsuarioRH, tpa.IdUsuarioContabilidad, tpa.IdUsuarioDireccion 
                    FROM t_permisos_ausencia_rh AS tpa INNER JOIN cat_v3_tipos_ausencia_personal AS tap ON tpa.IdTipoAusencia = tap.Id
                    INNER JOIN cat_v3_motivos_ausencia_personal AS map ON tpa.IdMotivoAusencia = map.Id WHERE IdUsuario = "' . $idUsuario . '" 
                    AND tpa.FechaDocumento BETWEEN (SELECT (NOW() - INTERVAL 1 MONTH)) and (SELECT NOW()) order by FechaAusenciaDesde desc');
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

    public function correoRHContador() {
        return $this->DBS->consultaGral("SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil in(21, 37)");
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

        return ['ruta' => 'https://' . $_SERVER['SERVER_NAME'] . substr($carpeta, 1), 'correo' => $correoEnviado];
    }

    public function revisarArchivoAdjunto($datosPermisos) {
        set_error_handler( function ($errno, $errstr, $errfile, $errline) {

            switch ($errno) {
                case E_WARNING:
                    $this->error['tipo'] = 'Warning';
                    $this->error['codigo'] = 'PDF001';
                    $this->error['error'] = $errstr;
                    break;
            }

            throw new \Exception('Error al adjuntar');
        }, E_WARNING);
        
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
            if (in_array(pathinfo($nuevoNombreArchivo, PATHINFO_EXTENSION), ['JPG', 'JPEG', 'PNG', 'GIF', 'jpg', 'jpeg', 'png', 'gif'])) {
                $image = '../public/storage/Archivos/Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'] . '/evidenciasMedicas/' . $nuevoNombreArchivo;
                $this->pdf->AddPage();
                $this->pdf->Image($image, 10, 10, 50, 50, pathinfo($image, PATHINFO_EXTENSION), $image);
            } else {
                $paginasArchivo = $this->pdf->setSourceFile('../public/storage/Archivos/Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'] . '/evidenciasMedicas/' . $nuevoNombreArchivo);
                for ($i = 1; $i <= $paginasArchivo; $i++) {
                    $this->pdf->AddPage();
                    $tplIdx = $this->pdf->importPage($i);
                    $this->pdf->useTemplate($tplIdx, 10, 0, 190);
                }
            }
        } catch (\spl_object_hash $ex) {
            $this->pdf->AddPage();
            $this->pdf->SetFont("helvetica", "B", 11);
            $this->pdf->Cell(14, 0, "Error del objeto PDF");
        } catch (\Exception $ex){
            $this->pdf->AddPage();
            $this->pdf->SetFont("helvetica", "B", 11);
            $this->pdf->Cell(14, 0, "Error al Adjuntar el Archivo");
        }
        restore_error_handler();
    }

    public function guardarImagen($datosPermisos) {
        if (!empty($_FILES)) {
            $CI = parent::getCI();
            $carpeta = 'Permisos_Ausencia/Ausencia_' . $datosPermisos['idUsuario'] . '/evidenciasMedicas/';
            $archivos = implode(',', setMultiplesArchivos($CI, 'evidenciasIncapacidad', $carpeta));
            return $archivos;
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
                 HoraEntrada, HoraSalida, Motivo, FolioDocumento, tpa.Archivo, ArchivosOriginales, IdUsuarioJefe, cmap.Archivo AS Doc FROM t_permisos_ausencia_rh AS tpa 
                 INNER JOIN t_rh_personal AS trp ON tpa.IdUsuario = trp.IdUsuario INNER JOIN cat_v3_usuarios AS cu ON tpa.IdUsuario=cu.Id 
                 INNER JOIN cat_perfiles AS cp ON cu.IdPerfil=cp.Id INNER JOIN cat_v3_departamentos_siccob AS cds ON cp.IdDepartamento=cds.Id 
                 INNER JOIN cat_v3_motivos_ausencia_personal AS cmap ON tpa.IdMotivoAusencia = cmap.Id
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
            if($informacionPermisoAusencia['datosAusencia'][0]['Doc'] == 1) {
                return array('formulario' => parent::getCI()->load->view('RH/Modal/formularioActualizarAusencia', $informacionPermisoAusencia, TRUE));
            } else {
                return "En revision";
            }
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

        return ['ruta' => 'https://' . $_SERVER['SERVER_NAME'] . substr($carpeta, 1), 'correo' => $correoEnviado];
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

        return ['ruta' => 'https://' . $_SERVER['SERVER_NAME'] . substr($carpeta, 1), 'correo' => $correoEnviado];
    }

    public function revisarActualizarPermiso($datosPermisos) {
        if ($datosPermisos['evidenciaIncapacidad'] !== "") {
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
        $arregloCorreos = "";
        if ($datosPermisos['idUsuario'] == "") {
            $idJefe = $this->jefeDirectoidPermiso($datosPermisos['idPermiso']);
        } else {
            $idJefe = $this->jefeDirecto($datosPermisos['idUsuario']);
        }
        $correoJefe = $this->correoJefeDirecto($idJefe[0]['IdJefe']);
        $correoRHContador = $this->correoRHContador();
        foreach ($correoRHContador as $value) {
            $arregloCorreos .= $value["EmailCorporativo"] . ",";
        }
        $arregloCorreos .= $correoJefe[0]['EmailCorporativo'];
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
        
        $texto .= $datosPermisos['textoMotivoAusencia'];
                
        $texto .= ' para el día ' . $datosPermisos['fechaPermisoDesde'] . '</p><br><br>
                    <a href="https://' . $_SERVER['SERVER_NAME'] . substr($carpeta, 1) . '">Archivo</a><br><br><br>
                    <a href="https://' . $_SERVER['SERVER_NAME'] . '/RH/Autorizar_permisos">Sistema</a>';
        $mensaje = $this->Correo->mensajeCorreo('Permiso de Ausencia ' . $asunto, $texto);
        $correoEnviado = $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($arregloCorreos), 'Permiso de Ausencia', $mensaje);
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

        $this->pdf->AddPage();
        $this->pdf->SetMargins(30, 25, 30);
        $this->pdf->SetAutoPageBreak(true, 25);
        $this->pdf->SetTextColor(243, 18, 18);
        $this->pdf->SetXY(165, 10);
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(14, 0, "Falta Autorizar");

        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont("helvetica", "B", 15);
        $this->pdf->SetXY(40, 20);
        $this->pdf->Cell(0, 0, "CONTROL DE AUSENCIAS DEL PERSONAL");

        $this->pdf->SetFont("helvetica", "I", 10);
        $this->pdf->SetXY(15, 30);
        $this->pdf->Cell(83, 8, "FECHA DEL DOCUMENTO:", 1);

        $this->pdf->SetXY(110, 30);
        $this->pdf->Cell(80, 8, "FECHA DEL PERMISO:", 1);

        $this->pdf->SetXY(15, 43);
        $this->pdf->Cell(175, 8, "NOMBRE COMPLETO DEL EMPLEADO: ", 1);

        $this->pdf->SetXY(15, 51);
        $this->pdf->Cell(175, 8, "PUESTO: ", 1);

        $this->pdf->SetXY(15, 59);
        $this->pdf->Cell(175, 8, "DEPARTAMENTO: ", 1);

        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->SetFillColor(224, 224, 224);
        $this->pdf->SetXY(15, 70);
        $this->pdf->Cell(53, 9, "MOTIVOS", 0, 0, 'C', true);
        if ($datosPermisos['tipoAusencia'] == 3) {
            $this->pdf->SetXY(72, 70);
            $this->pdf->Cell(53, 9, "NO ASISTIR A TRABAJAR", 0, 0, 'C', true);
            $this->pdf->SetXY(95, 85);
            $this->pdf->Cell(30, 9, "X");
        } else {
            if ($datosPermisos['tipoAusencia'] == 1) {
                $this->pdf->SetXY(72, 85);
                $this->pdf->Cell(25, 9, utf8_decode($datosPermisos['horaAusencia']), 1, 0, 'C');
            } else {
                $this->pdf->SetXY(100, 85);
                $this->pdf->Cell(25, 9, utf8_decode($datosPermisos['horaAusencia']), 1, 0, 'C');
            }
            $this->pdf->SetXY(72, 70);
            $this->pdf->Cell(25, 9, "ENTRADA", 0, 0, 'C', true);
            $this->pdf->SetXY(72, 85);
            $this->pdf->Cell(25, 9, "", 1, 0, 'C');
            $this->pdf->SetXY(100, 70);
            $this->pdf->Cell(25, 9, "SALIDA", 0, 0, 'C', true);
            $this->pdf->SetXY(100, 85);
            $this->pdf->Cell(25, 9, "", 1, 0, 'C');
        }
        $this->pdf->SetXY(129, 70);
        $this->pdf->Cell(61, 9, "OBSERVACIONES", 0, 0, 'C', true);

        $this->pdf->Ln();
        $this->pdf->line(15, 130, 190, 130);
        $this->pdf->SetFont("helvetica", "I", 10);
        $this->pdf->SetXY(15, 140);
        $this->pdf->Cell(0, 0, "DESCRIPCION DE MOTIVOS: ");

        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->line(15, 207, 55, 207);
        $this->pdf->SetXY(20, 210);
        $this->pdf->Cell(0, 0, "JEFE INMEDIATO");

        $this->pdf->line(60, 207, 100, 207);
        $this->pdf->SetXY(60, 210);
        $this->pdf->Cell(0, 0, "RECURSOS HUMANOS");

        $this->pdf->line(105, 207, 145, 207);
        $this->pdf->SetXY(113, 210);
        $this->pdf->Cell(0, 0, "CONTADOR");

        $this->pdf->line(150, 207, 190, 207);
        $this->pdf->SetXY(160, 210);
        $this->pdf->Cell(0, 0, "DIRECTOR");

        //agregar datos del permiso
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->SetXY(62, 34);
        $this->pdf->Cell(0, 0, utf8_decode($fecha));

        $this->pdf->SetXY(150, 34);
        $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['fechaPermisoDesde'] . " " . $datosPermisos['fechaPermisoHasta']));

        $this->pdf->SetXY(82, 47);
        $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['nombre']));

        $this->pdf->SetXY(35, 55);
        $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['puesto']));

        $this->pdf->SetXY(47, 63);
        $this->pdf->Cell(0, 0, utf8_decode($datosPermisos['departamento']));

        $this->pdf->SetXY(15, 83);
        $this->pdf->MultiCell(53, 9, utf8_decode($datosPermisos['textoMotivoAusencia']));

        $this->pdf->SetXY(15, 145);
        $this->pdf->MultiCell(135, 4, utf8_decode($datosPermisos["descripcionAusencia"]));

        $this->pdf->SetFont("helvetica", "B", 7);
        $this->pdf->SetXY(129, 83);
        $observaciones = $this->observacionesMotivo($datosPermisos['motivoAusencia']);
        $this->pdf->MultiCell(53, 9, utf8_decode($observaciones[0]['Observaciones']), 0, 'L');
    }

    public function observacionesMotivo($idMotivo) {
        return $this->DBS->consultaGral("select Observaciones from cat_v3_motivos_ausencia_personal where Id = " . $idMotivo);
    }

}
