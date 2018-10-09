<?php

namespace Librerias\Documentacion;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Generales\PDF as PDF;

/**
 * Description of Videos
 *
 * @author Alberto Barcenas
 */
class Documentacion extends General {

    private $DBD;
    private $DBT;
    private $DBS;
    private $Correo;
    private $pdf;

    public function __construct() {
        parent::__construct();
        $this->DBD = \Modelos\Modelo_Documentacion::factory();
        $this->DBT = \Modelos\Modelo_Tesoreria::factory();
        $this->DBS = \Modelos\Modelo_ServicioTicket::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->pdf = new PDFAux();
    }

    /*
     * Metodo para mostrar la lista de videos de la capacitación
     * 
     * @param array $datos con el id de la capacitacion y filtrar los videos
     * @return array en forma de html
     */

    public function mostrarTecnicosCartaResponsiva() {
        $usuario = $this->Usuario->getDatosUsuario();
        if (in_array('285', $usuario['PermisosAdicionales']) || in_array('285', $usuario['Permisos'])) {
            $consulta = $this->DBD->consultaTecnicosCartasResponsivas();
        } else {
            $consulta = $this->DBD->consultaTecnicoCartaResponsiva(array('IdUsuario' => $usuario['Id']));
        }
        return $consulta;
    }

    public function validarCartaResponsiva(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();

        if ($usuario['IdPerfil'] === '57' || $usuario['IdPerfil'] === '64') {
            $cartaResponsiva = $this->DBS->getServicios('SELECT Archivo FROM t_responsiva_fondo_fijo WHERE IdUsuario = "' . $usuario['Id'] . '"');
            if (empty($cartaResponsiva)) {
                return TRUE;
            } else {
                return $cartaResponsiva[0]['Archivo'];
            }
        } else {
            $cartaResponsiva = $this->DBS->getServicios('SELECT Archivo FROM t_responsiva_fondo_fijo WHERE IdUsuario = "' . $datos['idUsuario'] . '"');
            return $cartaResponsiva[0]['Archivo'];
        }
    }

    public function guardarFirmaCartaResponsiva(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $host = $_SERVER['SERVER_NAME'];
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $imgFirma = $datos['img'];
        $imgFirma = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $imgFirma));
        $dataFirma = base64_decode($imgFirma);

        $direccionFirma = '/storage/Archivos/imagenesFirmas/CartaResponsiva/' . str_replace(' ', '_', 'Firma_Usuario_' . $usuario['Id'] . '_CartaResponsiva') . '.png';
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccionFirma, $dataFirma);

        $idCartaResponsiva = $this->DBD->guardarFirmaCartaResponsiva(array(
            'IdUsuario' => $usuario['Id'],
            'Fecha' => $fecha,
            'Firma' => $direccionFirma
        ));

        $pdf = $this->pdfCartaResponsiva(array(
            'direccionFirma' => $direccionFirma,
            'nombreTecnico' => $datos['nombreTecnico']
        ));

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'https://siccob.solutions/storage/Archivos/Usuarios/Usuario-' . $usuario['Id'] . '/Carta_Responsiva_Usuario_' . $usuario['Id'] . '.pdf';
        } else {
            $path = 'http://' . $host . '/' . $pdf;
        }

        $consulta = $this->DBD->actualizarFirmaCartaResponsiva(
                array('Archivo' => $path), array('Id' => $idCartaResponsiva));

        if ($consulta) {
            $titulo = 'Carta Responsiva(Fondo Fijo) - ' . $datos['nombreTecnico'];
            $linkPDF = '<br>Ver PDF Carta Responsiva (Fondo Fijo) <a href="' . $path . '" target="_blank">Aquí</a>';

            $correoTesoreria = $this->DBS->getServicios('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil in(36,63)');
            $textoTesoreria = '<p>Tesorería,</strong> se le ha mandado el documento de la carta responsiva que ha firmado sobre el fondo fijo la persona <strong>' . $datos['nombreTecnico'] . '</strong>.</p>' . $linkPDF;

            foreach ($correoTesoreria as $key => $value) {
                $this->enviarCorreoConcluido(array($value['EmailCorporativo']), $titulo, $textoTesoreria);
            }

            $correoTecnico = $this->DBT->consultaCorreoUsuario($usuario['Id']);
            $textoTecnico = '<p>Estimado <strong>' . $datos['nombreTecnico'] . ',</strong> se le ha mandado el documento de la carta responsiva que ha firmado sobre el fondo fijo.</p>' . $linkPDF;
            $this->enviarCorreoConcluido(array($correoTecnico), $titulo, $textoTecnico);

            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function pdfCartaResponsiva(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();

        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información.'
            ];
        } else {
            $pline1 = 9;
            $pline2 = 201;

            $fecha = date('d/m/Y');

            $this->pdf->AddPage();
            $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
            $this->pdf->SetXY(0, 18);
            $this->pdf->SetFont("helvetica", "B", 18);
            $this->pdf->Cell(0, 0, "Carta Responsiva", 0, 0, 'C');
            $this->pdf->SetXY(0, 10);
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(0, 0, $fecha, 0, 0, 'R');

            $this->pdf->SetFont('helvetica', '', 12);
            $height = 6;

            $text = utf8_decode("A través de la presente carta responsiva hago constar que el motivo de la carta es por la responsiva del FONDO FIJO Sirva éste como comprobante de entrega del fondo fijo, para uso exclusivo de atención de reportes asignados para el desempeño de mis actividades laborales. Conozco los montos pre-autorizados por cada concepto y debo entregar las comprobaciones correspondientes en las fechas establecidas.");
            $this->pdf->Ln('30');
            $this->pdf->MultiCell(190, 4, $text);

            $text = utf8_decode("Sin mas por el momento, quedo a sus ordenes.");
            $this->pdf->Ln('15');
            $this->pdf->MultiCell(190, 4, $text);

            $this->pdf->Image('.' . $datos['direccionFirma'], 90, 100, 40, 0, 'PNG');

            $this->pdf->SetXY(12, 120);
            $this->pdf->SetFont("helvetica", "B", 12);
            $this->pdf->Cell(0, 0, $datos['nombreTecnico'], 0, 0, 'C');

            $carpeta = $this->pdf->definirArchivo('Usuarios/Usuario-' . $usuario['Id'], 'Carta_Responsiva_Usuario_' . $usuario['Id']);
            $this->pdf->Output('F', $carpeta, true);
            $carpeta = substr($carpeta, 1);
            return $carpeta;
        }
    }

    public function enviarCorreoConcluido(array $correo, string $titulo, string $texto) {
        $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $correo, $titulo, $mensaje);
    }

}

class PDFAux extends PDF {

    function Footer() {
        $fecha = date('d/m/Y');
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Helvetica', 'I', 10);
        // Print centered page number
        $this->Cell(185, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'R');
    }

}
