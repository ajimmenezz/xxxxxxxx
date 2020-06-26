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
            $montoFijo = $this->DBS->getServicios('SELECT * FROM cat_v3_fondo_fijo_usuarios WHERE IdUsuario = "' . $usuario['Id'] . '"');
            if (!empty($montoFijo)) {
                $direccionSiccob = $this->direccionSiccob();
                $cartaResponsiva = $this->DBS->getServicios('SELECT Archivo FROM t_responsiva_fondo_fijo WHERE IdUsuario = "' . $usuario['Id'] . '"');
                if (empty($cartaResponsiva)) {
                    return array(
                        'resultado' => TRUE,
                        'direccionSiccob' => $direccionSiccob,
                        'nombreUsuario' => $usuario['Nombre'],
                        'montoFijo' => $montoFijo[0]['Monto']
                    );
                } else {
                    return array(
                        'cartaResponsiva' => $cartaResponsiva[0]['Archivo'],
                        'resultado' => 'existePDF'
                    );
                }
            } else {
                return array(
                    'resultado' => 'faltaMonto'
                );
            }
        } else {
            $cartaResponsiva = $this->DBS->getServicios('SELECT Archivo FROM t_responsiva_fondo_fijo WHERE IdUsuario = "' . $datos['idUsuario'] . '"');
            if (!empty($cartaResponsiva)) {
                return array(
                    'cartaResponsiva' => $cartaResponsiva[0]['Archivo'],
                    'resultado' => 'existePDF'
                );
            } else {
                return array(
                    'resultado' => 'noExistePDF'
                );
            }
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
        } else if ($host === 'pruebas.siccob.solutions' || $host === 'www.pruebas.siccob.solutions') {
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
        $direccionSiccob = $this->direccionSiccob();
        $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $fechaCompleta = date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
        $montoFijo = $this->DBS->getServicios('SELECT * FROM cat_v3_fondo_fijo_usuarios WHERE IdUsuario = "' . $usuario['Id'] . '"');

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
            $this->pdf->Cell(0, 0, "CARTA RESPONSIVA", 0, 0, 'R');
            $this->pdf->SetXY(0, 10);
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(0, 0, $fechaCompleta, 0, 0, 'R');

            $this->pdf->SetFont('helvetica', '', 12);
            $height = 6;

            $text = utf8_decode("SICCOB SOLUTIONS S.A. DE C.V.");
            $this->pdf->Ln('30');
            $this->pdf->MultiCell(190, 4, $text);

            $text = utf8_decode($direccionSiccob);
            $this->pdf->Ln('1');
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->MultiCell(110, 4, $text);

            $text = utf8_decode("Ciudad de México a " . $fechaCompleta);
            $this->pdf->Ln('10');
            $this->pdf->MultiCell(190, 4, $text, 0, 'R');

            $text = utf8_decode("Recibo en este momento la cantidad, de:");
            $this->pdf->Ln('15');
            $this->pdf->MultiCell(190, 4, $text);

            $text = utf8_decode('$' . $montoFijo[0]['Monto'] . ' propiedad de SICCOB SOLUTIOS S.A. DE C.V.  Para la Creación de un Fondo Fijo "Revolvente", para Gastos Menores, de placas y tenencia. Mismo que recibo en Custodia, para su buen Uso, siendo Responsable del correcto manejo de él, y me comprometo a Devolverlo, en el instante que me sea requerido.');
            $this->pdf->Ln('1');
            $this->pdf->MultiCell(190, 4, $text);

            $text = utf8_decode('Hago constar, que he leído, y comprendido, el Procedimiento de Control Interno de la "Caja y fondo fijo" formulando por la Gerencia Administrativa Corporativa, el cual seguiré cabalmente.');
            $this->pdf->Ln('10');
            $this->pdf->MultiCell(190, 4, $text);

            $this->pdf->SetXY(12, 140);
            $this->pdf->SetFont("helvetica", "B", 12);
            $this->pdf->Cell(0, 0, 'RECIBO DE CONFORMIDAD', 0, 0, 'C');

            $this->pdf->Image('.' . $datos['direccionFirma'], 85, 155, 40, 0, 'PNG');

            $this->pdf->SetXY(12, 180);
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(0, 0, utf8_decode($datos['nombreTecnico']), 0, 0, 'C');

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

    public function direccionSiccob() {
        $direccionSiccob = $this->DBS->getServicios('SELECT 
                                                        CONCAT(
                                                                Calle, 
                                                                " #", NoExt,
                                                                " Col. ", (SELECT Nombre FROM cat_v3_colonias WHERE Id = IdColonia),
                                                                ", ", (SELECT Nombre FROM cat_v3_estados WHERE Id = IdEstado), ".") Direccion
                                                    FROM cat_v3_sucursales
                                                    WHERE Id = "194"');

        return $direccionSiccob[0]['Direccion'];
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
