<?php

namespace Librerias\Instalaciones;

use Controladores\Controller_Base_General as General;

class Instalaciones extends General
{

    private $DB;
    private $Correo;
    private $usuario;
    private $cliente;
    private $sucursal;
    private $area;
    private $productosSAE;
    private $pdf;

    public function __construct()
    {
        parent::__construct();
        $this->DB = \Modelos\Modelo_Instalaciones::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
        $this->cliente = \Librerias\Catalogos\Cliente::factory();
        $this->sucursal = \Librerias\Catalogos\Sucursal::factory();
        $this->area = \Librerias\Catalogos\AreaAtencion::factory();
        $this->productosSAE = \Librerias\Catalogos\ProductosSAE::factory();
        $this->pdf = new \Librerias\Generales\PDFAux();
    }

    public function getInstalacionesPendientes(int $idUsuario = null)
    {
        if (in_array(317, $this->usuario['Permisos']) || in_array(317, $this->usuario['PermisosAdicionales'])) {
            $idUsuario = null;
        }
        $pendientes = $this->DB->getInstalacionesPendientes($idUsuario);
        return $pendientes;
    }

    public function formularioSeguimientoInstalacion(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesServicio($datos['id'])[0];
            $cliente = [
                'IdCliente' => null
            ];
            if ($generales['IdSucursal'] != '') {
                $sucursal = $this->sucursal->get($generales['IdSucursal'])['result'][0];
                $cliente = $this->cliente->get($sucursal['IdCliente'])['result'][0];
            }

            $generales['IdCliente'] = $cliente['Id'];

            $data = [
                'generales' => $generales,
                'clientes' => $this->cliente->get()['result'],
                'sucursales' => $this->sucursal->get(null, 1, $cliente['Id'])['result'],
                'productosSAE' => $this->productosSAE->getFromAdist()
            ];

            switch ($data['generales']['IdTipoServicio']) {
                case 45:
                case '45':

                    break;
            }

            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('Instalaciones/Formularios/Seguimiento-' . $data['generales']['IdTipoServicio'], $data, TRUE),
                'tipoInstalacion' => $data['generales']['IdTipoServicio']
            ];
        }
    }

    public function iniciarInstalacion(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $iniciar = $this->DB->iniciarInstalacion($datos['id']);
            if ($iniciar['code'] != 200) {
                return $iniciar;
            } else {
                return $this->formularioSeguimientoInstalacion($datos);
            }
        }
    }

    public function getSucursalesXCliente(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del cliente. Intente de nuevo'
            ];
        } else {
            $sucursales = $this->sucursal->get(null, 1, $datos['id']);
            return $sucursales;
        }
    }

    public function guardarSucursalServicio(array $datos)
    {
        if (!isset($datos['id']) || !isset($datos['sucursal'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio o la sucursal. Intente de nuevo'
            ];
        } else {
            if ($this->tieneFirmas($datos['id'])) {
                $result = [
                    'code' => 500,
                    'message' => 'Los cambios no son permitidos debido a que el incidente ya fué firmado'
                ];
            } else {
                $result = $this->DB->guardarSucursalServicio($datos['id'], $datos['sucursal']);
            }
            return $result;
        }
    }

    public function instaladosLexmark(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesServicio($datos['id'])[0];
            $ubicaciones = [];
            if ($generales['IdSucursal'] != '') {
                $ubicaciones = $this->sucursal->ubicacionesCenso($generales['IdSucursal']);
            }

            $instalados = $this->DB->getEquiposInstaladosLexmark($datos['id']);

            if ($instalados['code'] == 200) {
                return [
                    'code' => 200,
                    'message' => 'Success',
                    'ubicaciones' => $ubicaciones,
                    'instalados' => $instalados['result'],
                    'firmas' => $this->tieneFirmas($datos['id'])
                ];
            } else {
                return $instalados;
            }
        }
    }

    public function guardarInstaladosLexmark(array $datos)
    {
        if (!isset($datos['servicio']) || !isset($datos['instalados'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio y los equipos instalados. Intente de nuevo'
            ];
        } else {
            return $this->DB->guardarInstaladosLexmark($datos);
        }
    }

    public function retiradosLexmark(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesServicio($datos['id'])[0];

            $censadas = [];
            if ($generales['IdSucursal'] != '') {
                $censadas = $this->DB->getKyocerasCensadas($generales['IdSucursal']);
            }

            $retirada = $this->DB->getImpresoraRetirada($datos['id']);

            if ($retirada['code'] == 200) {
                return [
                    'code' => 200,
                    'message' => 'Success',
                    'censadas' => $censadas,
                    'modelos' => $this->DB->getModelosKyocera(),
                    'estatus' => $this->DB->getEstatusRetiro(),
                    'retirada' => $retirada['result'],
                    'firmas' => $this->tieneFirmas($datos['id'])
                ];
            } else {
                return $retirada;
            }
        }
    }

    public function guardarRetiradosLexmark(array $datos)
    {
        if (!isset($datos['servicio']) || !isset($datos['retirados'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio y el equipo retirado. Intente de nuevo'
            ];
        } else {
            return $this->DB->guardarRetiradosLexmark($datos);
        }
    }

    public function subirArchivoInstalacion(array $datos)
    {
        $archivos = null;
        $CI = parent::getCI();
        $carpeta = 'instalaciones/' . $datos['id'] . '/';

        $archivos = '';
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'archivosInstalacion', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $datos = array_merge($datos, ['archivos' => $archivos]);
        $registrar = $this->DB->registrarArchivosInstalacion($datos);
        return $registrar;
    }

    public function subirArchivoRetiro(array $datos)
    {
        $archivos = null;
        $CI = parent::getCI();
        $carpeta = 'retiros/' . $datos['id'] . '/';

        $archivos = '';
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'archivosRetiro', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $datos = array_merge($datos, ['archivos' => $archivos]);
        $registrar = $this->DB->registrarArchivosRetiro($datos);
        return $registrar;
    }

    public function evidenciasInstalacion(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesServicio($datos['id'])[0];
            $evidenciasInstalacion = $this->DB->getEvidenciasInstalacion($datos['id']);

            return [
                'code' => 200,
                'message' => 'Success',
                'evidenciasInstalacion' => $this->DB->getTiposEvidencia($generales['IdTipoServicio'], $datos['id']),
                'infoEvidenciasInstalacion' => $evidenciasInstalacion,
                'firmas' => $this->tieneFirmas($datos['id'])
            ];
        }
    }

    public function evidenciasRetiro(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesServicio($datos['id'])[0];
            $evidenciasRetiro = $this->DB->getEvidenciasRetiro($datos['id']);

            return [
                'code' => 200,
                'message' => 'Success',
                'evidenciasRetiro' => $this->DB->getTiposEvidenciaRetiro($generales['IdTipoServicio'], $datos['id']),
                'infoEvidenciasRetiro' => $evidenciasRetiro,
                'firmas' => $this->tieneFirmas($datos['id'])
            ];
        }
    }

    public function eliminarEvidenciaInstalacion(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del archivo. Intente de nuevo'
            ];
        } else {
            $eliminar = $this->DB->eliminarEvidenciaInstalacion($datos['id']);
            return $eliminar;
        }
    }

    public function eliminarEvidenciaRetiro(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del archivo. Intente de nuevo'
            ];
        } else {
            $eliminar = $this->DB->eliminarEvidenciaRetiro($datos['id']);
            return $eliminar;
        }
    }

    public function guardarMaterial(array $datos)
    {
        if (!isset($datos['servicio']) || !isset($datos['clave']) || !isset($datos['producto']) || !isset($datos['cantidad'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio o del producto. Intente de nuevo'
            ];
        } else {
            $guardar = $this->DB->guardarMaterial($datos);
            return $guardar;
        }
    }

    public function eliminarMaterial(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del producto. Intente de nuevo'
            ];
        } else {
            $eliminar = $this->DB->eliminarMaterial($datos);
            return $eliminar;
        }
    }

    public function cargaMateriales(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $data = [
                'materiales' => $this->DB->materialesUtilizados($datos['id']),
                'productosSAE' => $this->productosSAE->getFromAdist(),
                'firmas' => $this->tieneFirmas($datos['id'])
            ];

            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('Instalaciones/Formularios/Materiales', $data, TRUE),
                'firmas' => $data['firmas']
            ];
        }
    }

    public function exportarInstalacion(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesServicio($datos['id']);
            $ruta = '';
            switch ($generales[0]['IdTipoServicio']) {
                case 45:
                case '45':
                    $ruta = $this->exportar45($datos['id']);
                    break;
            }

            return ['code' => 200, 'ruta' => $ruta];
        }
    }

    public function cargaFirmas(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $data = [
                'firmas' => $this->DB->getFirmasServicio($datos['id']),
                'faltantes' => $this->validarInformacionLexmark($datos['id'])
            ];
            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('Instalaciones/Formularios/Firmas', $data, TRUE)
            ];
        }
    }

    public function guardaFirma(array $datos)
    {
        if (!isset($datos['servicio']) || !isset($datos['firma']) || !isset($datos['tipo'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio o la firma. Intente de nuevo'
            ];
        } else {
            $img = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $datos['firma']));
            $data = base64_decode($img);

            if (!file_exists('./storage/Archivos/instalaciones/' . $datos['servicio'] . '/firmas')) {
                mkdir('./storage/Archivos/instalaciones/' . $datos['servicio'] . '/firmas', 0755, true);
            }

            $url = '/storage/Archivos/instalaciones/' . $datos['servicio'] . '/firmas/' . $datos['tipo'] . '.png';

            file_put_contents('.' . $url, $data);

            return $this->DB->guardarFirma($datos, $url);
        }
    }

    public function concluirServicio(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del archivo. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesServicio($datos['id']);
            $ruta = '';
            switch ($generales[0]['IdTipoServicio']) {
                case 45:
                case '45':
                    $ruta = $this->exportar45($datos['id']);
                    break;
            }

            $concluir = $this->DB->concluirServicio($datos['id']);
            $concluir = array_merge($concluir, ['ruta' => $ruta]);
            return $concluir;
        }
    }

    public function instaladosAntenas(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesServicio($datos['id'])[0];
            $ubicaciones = [];
            if ($generales['IdSucursal'] != '') {
                $ubicaciones = $this->area->get(null, 1, $generales['IdCliente']);
            }

            $modelosAntenas = $this->DB->getModelosAntenas();
            $modelosSwitch = $this->DB->getModelosSwitch();

            $antenasInstaladas = $this->DB->getAntenasInstaladas($datos['id']);

            if ($antenasInstaladas['code'] == 200) {
                return [
                    'code' => 200,
                    'message' => 'Success',
                    'ubicaciones' => $ubicaciones,
                    'antenas' => $modelosAntenas,
                    'switch' => $modelosSwitch,
                    'instalados' => $antenasInstaladas['result'],
                    'firmas' => $this->tieneFirmas($datos['id'])
                ];
            } else {
                return $antenasInstaladas;
            }
        }
    }

    public function guardarAntena(array $datos)
    {
        if (!isset($datos['servicio']) || !isset($datos['antena'])) {
            return [
                'code' => 500,
                'message' => 'No se ha recibido la información del servicio y la antena. Intente de nuevo'
            ];
        } else {
            return $this->DB->guardarAntena($datos);
        }
    }


    private function tieneFirmas(int $servicio)
    {
        $firmas = $this->DB->getFirmasServicio($servicio)[0];
        if ((!is_null($firmas['Firma']) && $firmas['Firma'] != '') || (!is_null($firmas['FirmaTecnico']) && $firmas['FirmaTecnico'] != '')) {
            return true;
        } else {
            return false;
        }
    }

    private function validarInformacionLexmark(int $servicio)
    {
        $generales = $this->DB->getGeneralesServicio($servicio)[0];
        $instalados =  $this->DB->getEquiposInstaladosLexmark($servicio)['result'];
        $retirados = $this->DB->getImpresoraRetirada($servicio)['result'];
        $evidenciasInstalacion = $this->DB->getTiposEvidencia($generales['IdTipoServicio'], $servicio);
        $evidenciasRetiro = $this->DB->getTiposEvidenciaRetiro($generales['IdTipoServicio'], $servicio);

        $errores = [];

        if (!isset($generales['IdSucursal']) || !is_numeric($generales['IdSucursal']) || $generales['IdSucursal'] <= 0) {
            array_push($errores, "No se ha seleccionado una sucursal");
        }
        if ($instalados['impresora']['IdArea'] == '') {
            array_push($errores, "Falta la ubicación de la impresora instalada.");
        }
        if ($instalados['impresora']['Serie'] == '') {
            array_push($errores, "Falta la Serie de la impresora instalada.");
        }
        if ($instalados['impresora']['IP'] == '') {
            array_push($errores, "Falta la IP de la impresora instalada.");
        }
        if ($instalados['impresora']['MAC'] == '') {
            array_push($errores, "Falta la MAC de la impresora instalada.");
        }
        if ($instalados['impresora']['Firmware'] == '') {
            array_push($errores, "Falta el Firmware de la impresora instalada.");
        }
        if ($instalados['impresora']['Contador'] == '') {
            array_push($errores, "Falta el Contador de copias de la impresora instalada.");
        }
        if ($retirados['impresora']['IdModelo'] == '') {
            array_push($errores, "Falta el modelo de la impresora retirada.");
        }
        if ($retirados['impresora']['IdEstatus'] == '') {
            array_push($errores, "Falta el estado de la impresora retirada.");
        }
        if ($retirados['impresora']['Serie'] == '') {
            array_push($errores, "Falta la serie de la impresora retirada.");
        }

        foreach ($evidenciasInstalacion as $key => $value) {
            array_push($errores, "Falta evidencia de instalación: " . $value['Nombre']);
        }

        foreach ($evidenciasRetiro as $key => $value) {
            array_push($errores, "Falta evidencia de retiro: " . $value['Nombre']);
        }

        return $errores;
    }



    private function exportar45(int $servicio)
    {
        $generales = $this->DB->getGeneralesServicio($servicio)[0];
        $instalados = $this->DB->getEquiposInstaladosLexmark($servicio)['result'];
        $retirados = $this->DB->getImpresoraRetirada($servicio)['result'];
        $evidenciasInstalacion = $this->evidenciasInstalacion(['id' => $servicio])['infoEvidenciasInstalacion'];
        $evidenciasRetiro = $this->evidenciasRetiro(['id' => $servicio])['infoEvidenciasRetiro'];
        $materiales = $this->DB->materialesUtilizados($servicio)['lista'];
        $firmas = $this->DB->getFirmasServicio($servicio)[0];

        /******************************* 
         * *****HEADERS DE PDF *********
         ********************************/
        $this->pdf->AddPage();
        $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
        $this->pdf->SetXY(0, 13);
        $this->pdf->SetFont("helvetica", "B", 15);
        $this->pdf->Cell(0, 0, utf8_decode("Resumen de Instalación Imp. Lexmark"), 0, 0, 'C');

        $this->pdf->SetXY(0, 20);
        $this->pdf->SetFont("helvetica", "I", 13);
        $this->pdf->Cell(0, 0, utf8_decode($generales['Sucursal']), 0, 0, 'C');

        $this->pdf->SetXY(0, 27);
        $this->pdf->SetFont("helvetica", "I", 13);
        $this->pdf->Cell(0, 0, date("Y/m/d", strtotime($generales['FechaInicio'])), 0, 0, 'C');


        /************************************* 
         * *****SECCIÓN INFO GENERAL *********
         **************************************/
        $this->pdf->SetFillColor(31, 56, 100);
        $this->pdf->SetTextColor(255, 255, 255);

        $this->pdf->SetXY(10, 36);
        $this->pdf->SetFont("helvetica", "BI", 10);
        $this->pdf->Cell(0, 6, utf8_decode("Información General"), 1, 0, 'L', true);

        $this->pdf->SetFillColor(217, 217, 217);
        $this->pdf->SetTextColor(10, 10, 10);

        $this->pdf->SetXY(10, 42);
        $this->pdf->SetFont("helvetica", "BI", 9);
        $this->pdf->Cell(25, 5, "Cliente:", 1, 0, 'R', true);

        $this->pdf->SetXY(35, 42);
        $this->pdf->SetFont("helvetica", "", 9);
        $this->pdf->Cell(0, 5, utf8_decode($generales['Cliente']), 1, 0, 'L', true);

        $this->pdf->SetXY(10, 47);
        $this->pdf->SetFont("helvetica", "BI", 9);
        $this->pdf->Cell(25, 5, "Sucursal:", 1, 0, 'R');

        $this->pdf->SetXY(35, 47);
        $this->pdf->SetFont("helvetica", "", 9);
        $this->pdf->Cell(0, 5, utf8_decode($generales['Sucursal']), 1, 0, 'L');

        /******************************************* 
         * ******HEADER EQUIPOS INSTALADOS *********
         ********************************************/

        $this->pdf->SetFillColor(31, 56, 100);
        $this->pdf->SetTextColor(255, 255, 255);

        $this->pdf->SetXY(10, 52);
        $this->pdf->SetFont("helvetica", "BI", 10);
        $this->pdf->Cell(0, 6, utf8_decode("Equipos Instalados"), 1, 0, 'L', true);

        $this->pdf->SetFillColor(191, 191, 191);
        $this->pdf->SetTextColor(10, 10, 10);

        /************************************* 
         * ******IMPRESORA INSTALADA *********
         **************************************/
        $this->pdf->SetXY(10, 58);
        $this->pdf->SetFont("helvetica", "BI", 13);
        $this->pdf->Cell(13, 35, "#1", 1, 0, 'C', true);

        $this->pdf->SetFillColor(217, 217, 217);
        $this->pdf->SetFont("helvetica", "BI", 9);

        $this->pdf->SetXY(23, 58);
        $this->pdf->Cell(36, 5, utf8_decode("Ubicación:"), 1, 0, 'R', true);

        $this->pdf->SetXY(23, 63);
        $this->pdf->Cell(36, 5, utf8_decode("Modelo de Equipo:"), 1, 0, 'R');

        $this->pdf->SetXY(23, 68);
        $this->pdf->Cell(36, 5, utf8_decode("Número de Serie:"), 1, 0, 'R', true);

        $this->pdf->SetXY(23, 73);
        $this->pdf->Cell(36, 5, utf8_decode("IP Asignada:"), 1, 0, 'R');

        $this->pdf->SetXY(23, 78);
        $this->pdf->Cell(36, 5, utf8_decode("MAC Address:"), 1, 0, 'R', true);

        $this->pdf->SetXY(23, 83);
        $this->pdf->Cell(36, 5, utf8_decode("Firmware:"), 1, 0, 'R');

        $this->pdf->SetXY(23, 88);
        $this->pdf->Cell(36, 5, utf8_decode("Contador:"), 1, 0, 'R', true);


        $this->pdf->SetFont("helvetica", "", 9);

        $this->pdf->SetXY(59, 58);
        $this->pdf->Cell(0, 5, utf8_decode($instalados['impresora']['Area'] . ' ' . $instalados['impresora']['Punto']), 1, 0, 'L', true);

        $this->pdf->SetXY(59, 63);
        $this->pdf->Cell(0, 5, strtoupper(utf8_decode($instalados['impresora']['Modelo'])), 1, 0, 'L');

        $this->pdf->SetXY(59, 68);
        $this->pdf->Cell(0, 5, strtoupper(utf8_decode($instalados['impresora']['Serie'])), 1, 0, 'L', true);

        $this->pdf->SetXY(59, 73);
        $this->pdf->Cell(0, 5, utf8_decode($instalados['impresora']['IP']), 1, 0, 'L');

        $this->pdf->SetXY(59, 78);
        $this->pdf->Cell(0, 5, strtoupper(utf8_decode($instalados['impresora']['MAC'])), 1, 0, 'L', true);

        $this->pdf->SetXY(59, 83);
        $this->pdf->Cell(0, 5, utf8_decode($instalados['impresora']['Firmware']), 1, 0, 'L');

        $this->pdf->SetXY(59, 88);
        $this->pdf->Cell(0, 5, utf8_decode($instalados['impresora']['Contador']), 1, 0, 'L', true);

        $this->pdf->SetXY(10, 93);
        $this->pdf->Cell(0, 2, "", 1, 0, 'C');

        /************************************* 
         ********* SUPRESOR INSTALADO *********
         **************************************/

        $x = 10;
        $y = 95;

        if ($instalados['supresor']['Area'] != '') {

            $this->pdf->SetFillColor(191, 191, 191);

            $this->pdf->SetXY($x, $y);
            // $this->pdf->SetXY(10, 95);
            $this->pdf->SetFont("helvetica", "BI", 13);
            $this->pdf->Cell(13, 15, "#2", 1, 0, 'C', true);

            $this->pdf->SetFillColor(217, 217, 217);
            $this->pdf->SetFont("helvetica", "BI", 9);

            $x = 23;
            $this->pdf->SetXY($x, $y);
            // $this->pdf->SetXY(23, 95);
            $this->pdf->Cell(36, 5, utf8_decode("Ubicación:"), 1, 0, 'R', true);

            $y += 5;
            $this->pdf->SetXY($x, $y);
            // $this->pdf->SetXY(23, 100);
            $this->pdf->Cell(36, 5, utf8_decode("Modelo de Equipo:"), 1, 0, 'R');

            $y += 5;
            $this->pdf->SetXY($x, $y);
            // $this->pdf->SetXY(23, 105);
            $this->pdf->Cell(36, 5, utf8_decode("Número de Serie:"), 1, 0, 'R', true);

            $this->pdf->SetFont("helvetica", "", 9);

            $x = 59;
            $y -= 10;
            $this->pdf->SetXY($x, $y);
            // $this->pdf->SetXY(59, 95);
            $this->pdf->Cell(0, 5, utf8_decode($instalados['supresor']['Area'] . ' ' . $instalados['supresor']['Punto']), 1, 0, 'L', true);

            $y += 5;
            $this->pdf->SetXY($x, $y);
            // $this->pdf->SetXY(59, 100);
            $this->pdf->Cell(0, 5, strtoupper(utf8_decode($instalados['supresor']['Modelo'])), 1, 0, 'L');

            $y += 5;
            $this->pdf->SetXY($x, $y);
            // $this->pdf->SetXY(59, 105);
            $this->pdf->Cell(0, 5, strtoupper(utf8_decode($instalados['supresor']['Serie'])), 1, 0, 'L', true);

            $x = 10;
            $y += 5;
            $this->pdf->SetXY($x, $y);
            // $this->pdf->SetXY(10, 110);
            $this->pdf->Cell(0, 2, "", 1, 0, 'C');
            $y += 2;
        }

        /******************************************** 
         ********* SECCIÓN EQUIPOS RETIRADOS *********
         *********************************************/

        $this->pdf->SetFillColor(31, 56, 100);
        $this->pdf->SetTextColor(255, 255, 255);


        $this->pdf->SetXY($x, $y);
        $this->pdf->SetFont("helvetica", "BI", 10);
        $this->pdf->Cell(0, 6, utf8_decode("Equipos Retirados"), 1, 0, 'L', true);
        $y += 6;

        $this->pdf->SetFillColor(191, 191, 191);
        $this->pdf->SetTextColor(10, 10, 10);

        /************************************ 
         * ******IMPRESORA RETIRADA *********
         *************************************/

        $this->pdf->SetXY($x, $y);
        $this->pdf->SetFont("helvetica", "BI", 13);
        $this->pdf->Cell(13, 15, "#1", 1, 0, 'C', true);
        $x += 13;

        $this->pdf->SetFillColor(217, 217, 217);
        $this->pdf->SetFont("helvetica", "BI", 9);

        $this->pdf->SetXY($x, $y);
        $this->pdf->Cell(36, 5, utf8_decode("Modelo del Equipo:"), 1, 0, 'R', true);
        $y += 5;

        $this->pdf->SetXY($x, $y);
        $this->pdf->Cell(36, 5, utf8_decode("Número de Serie:"), 1, 0, 'R');
        $y += 5;

        $this->pdf->SetXY($x, $y);
        $this->pdf->Cell(36, 5, utf8_decode("Estado del Equipo:"), 1, 0, 'R', true);
        $y -= 10;

        $this->pdf->SetFont("helvetica", "", 9);

        $x += 36;
        $this->pdf->SetXY($x, $y);
        $this->pdf->Cell(0, 5, strtoupper(utf8_decode($retirados['impresora']['Modelo'])), 1, 0, 'L', true);
        $y += 5;

        $this->pdf->SetXY($x, $y);
        $this->pdf->Cell(0, 5, strtoupper(utf8_decode($retirados['impresora']['Serie'])), 1, 0, 'L');
        $y += 5;

        $this->pdf->SetXY($x, $y);
        $this->pdf->Cell(0, 5, strtoupper(utf8_decode($retirados['impresora']['Estatus'])), 1, 0, 'L', true);
        $y += 5;

        $x = 10;
        $this->pdf->SetXY($x, $y);
        $this->pdf->Cell(0, 2, "", 1, 0, 'C');
        $y += 2;

        /************************************************* 
         ********* SECCIÓN EVIDENCIAS INSTALACION *********
         **************************************************/

        $totalEvidencias = count($evidenciasInstalacion);
        if ($totalEvidencias > 0) {

            $this->pdf->SetFillColor(31, 56, 100);
            $this->pdf->SetTextColor(255, 255, 255);

            $this->pdf->SetXY($x, $y);
            $this->pdf->SetFont("helvetica", "BI", 10);
            $this->pdf->Cell(0, 6, utf8_decode("Evidencias de Instalación"), 1, 0, 'L', true);
            $y += 6;

            $filas = ceil($totalEvidencias / 4);

            $this->pdf->SetTextColor(10, 10, 10);


            $indice = 0;
            for ($f = 1; $f <= $filas; $f++) {

                if (($y + 50) > 276) {
                    $this->pdf->AddPage();
                    $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
                    $x = 10;
                    $y = 32;
                    $this->pdf->SetTextColor(255, 255, 255);
                    $this->pdf->SetXY($x, $y);
                    $this->pdf->Cell(0, 6, utf8_decode("Evidencias de Instalación"), 1, 0, 'L', true);
                    $y += 6;
                }

                $this->pdf->SetXY($x, $y);
                for ($i = 1; $i <= 4; $i++) {
                    $evidencia = "";
                    $link = "";

                    if (isset($evidenciasInstalacion[$indice])) {
                        $evidencia = $evidenciasInstalacion[$indice]['Evidencia'];
                        $url = $evidenciasInstalacion[$indice]['Archivo'];
                        $link = 'Link To Image';
                        $this->pdf->Image('.' . $url, $x + 2.5, $y + 2.5, 42.5, 40, pathinfo($url, PATHINFO_EXTENSION), $url);
                    }

                    $this->pdf->SetTextColor(100, 100, 100);
                    $this->pdf->Cell(47.5, 45, $link, 1, 0, 'C');
                    $y += 45;
                    $this->pdf->SetXY($x, $y);
                    $this->pdf->SetFont("helvetica", "BI", 8);
                    $this->pdf->SetTextColor(20, 20, 20);
                    $this->pdf->Cell(47.5, 5, utf8_decode($evidencia), 1, 0, 'C');
                    $x += 47.5;
                    $y -= 45;
                    $this->pdf->SetXY($x, $y);
                    if ($i == 4) {
                        $x = 10;
                        $y += 50;
                    }
                    $indice++;
                }
            }
        }

        /********************************************* 
         ********* SECCIÓN EVIDENCIAS RETIRO *********
         *********************************************/

        $totalEvidencias = count($evidenciasRetiro);
        if ($totalEvidencias > 0) {

            if (($y + 56) > 276) {
                $this->pdf->AddPage();
                $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
                $x = 10;
                $y = 32;
            }

            $this->pdf->SetFillColor(31, 56, 100);
            $this->pdf->SetTextColor(255, 255, 255);

            $this->pdf->SetXY($x, $y);
            $this->pdf->SetFont("helvetica", "BI", 10);
            $this->pdf->Cell(0, 6, utf8_decode("Evidencias de Retiro"), 1, 0, 'L', true);
            $y += 6;

            $filas = ceil($totalEvidencias / 4);

            $this->pdf->SetTextColor(10, 10, 10);


            $indice = 0;
            for ($f = 1; $f <= $filas; $f++) {

                if (($y + 50) > 276) {
                    $this->pdf->AddPage();
                    $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
                    $x = 10;
                    $y = 32;
                    $this->pdf->SetTextColor(255, 255, 255);
                    $this->pdf->SetXY($x, $y);
                    $this->pdf->Cell(0, 6, utf8_decode("Evidencias de Retiro"), 1, 0, 'L', true);
                    $y += 6;
                }

                $this->pdf->SetXY($x, $y);
                for ($i = 1; $i <= 4; $i++) {
                    $evidencia = "";
                    $link = "";

                    if (isset($evidenciasRetiro[$indice])) {
                        $evidencia = $evidenciasRetiro[$indice]['Evidencia'];
                        $url = $evidenciasRetiro[$indice]['Archivo'];
                        $link = 'Link To Image';
                        $this->pdf->Image('.' . $url, $x + 2.5, $y + 2.5, 42.5, 40, pathinfo($url, PATHINFO_EXTENSION), $url);
                    }

                    $this->pdf->SetTextColor(100, 100, 100);
                    $this->pdf->Cell(47.5, 45, $link, 1, 0, 'C');
                    $y += 45;
                    $this->pdf->SetXY($x, $y);
                    $this->pdf->SetFont("helvetica", "BI", 8);
                    $this->pdf->SetTextColor(20, 20, 20);
                    $this->pdf->Cell(47.5, 5, utf8_decode($evidencia), 1, 0, 'C');
                    $x += 47.5;
                    $y -= 45;
                    $this->pdf->SetXY($x, $y);
                    if ($i == 4) {
                        $x = 10;
                        $y += 50;
                    }
                    $indice++;
                }
            }
        }

        /************************************************* 
         ********* SECCIÓN MATERIALES UTILIZADOS *********
         *************************************************/

        $totalMateriales = count($materiales);
        if ($totalMateriales > 0) {
            if (($y + 11) > 276) {
                $this->pdf->AddPage();
                $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
                $x = 10;
                $y = 32;
            }

            $this->pdf->SetFillColor(31, 56, 100);
            $this->pdf->SetTextColor(255, 255, 255);

            $this->pdf->SetXY($x, $y);
            $this->pdf->SetFont("helvetica", "BI", 10);
            $this->pdf->Cell(0, 6, utf8_decode("Materiales Utilizados"), 1, 0, 'L', true);
            $y += 6;

            $this->pdf->SetFillColor(217, 217, 217);
            $this->pdf->SetFont("helvetica", "B", 9);
            $this->pdf->SetTextColor(10, 10, 10);
            $fill = true;
            foreach ($materiales as $key => $value) {
                $this->pdf->SetXY($x, $y);
                $this->pdf->Cell(20, 5, $value['Cantidad'], 1, 0, 'C', $fill);
                $x += 20;
                $this->pdf->SetXY($x, $y);
                $this->pdf->Cell(0, 5, $value['Producto'], 1, 0, 'L', $fill);

                $x = 10;
                $y += 5;
                $fill = !$fill;
            }
        }


        /********************************** 
         ********* SECCIÓN FIRMAS *********
         **********************************/

        if ((!is_null($firmas['Firma']) && $firmas['Firma'] != '') || (!is_null($firmas['FirmaTecnico']) && $firmas['FirmaTecnico'] != '')) {
            if (($y + 56) > 276) {
                $this->pdf->AddPage();
                $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
                $x = 10;
                $y = 32;
            }

            $this->pdf->SetFillColor(31, 56, 100);
            $this->pdf->SetTextColor(255, 255, 255);

            $this->pdf->SetXY($x, $y);
            $this->pdf->SetFont("helvetica", "BI", 10);
            $this->pdf->Cell(0, 6, utf8_decode("Firmas del Servicio"), 1, 0, 'L', true);
            $y += 6;

            $this->pdf->SetFont("helvetica", "B", 9);
            $this->pdf->SetTextColor(10, 10, 10);

            $this->pdf->SetXY($x, $y);
            $this->pdf->Cell(95, 40, "", 1, 0, 'C');
            $gerente = '';
            if (!is_null($firmas['Firma']) && $firmas['Firma'] != '') {
                $this->pdf->Image('.' . $firmas['Firma'], $x + 7.5, $y + 2.5, 80, 35, pathinfo($firmas['Firma'], PATHINFO_EXTENSION));
                $gerente = utf8_decode($firmas['Gerente']);
            }

            $x += 95;
            $this->pdf->SetXY($x, $y);
            $this->pdf->Cell(95, 40, "", 1, 0, 'C');
            $tecnico = '';
            if (!is_null($firmas['FirmaTecnico']) && $firmas['FirmaTecnico'] != '') {
                $this->pdf->Image('.' . $firmas['FirmaTecnico'], $x + 7.5, $y + 2.5, 80, 35, pathinfo($firmas['FirmaTecnico'], PATHINFO_EXTENSION));
                $tecnico = utf8_decode($firmas['Tecnico']);
            }

            $this->pdf->SetFillColor(217, 217, 217);

            $x = 10;
            $y += 40;
            $this->pdf->SetXY($x, $y);
            $this->pdf->Cell(95, 5, $gerente, 1, 0, 'C', true);
            $x += 95;
            $this->pdf->SetXY($x, $y);
            $this->pdf->Cell(95, 5, $tecnico, 1, 0, 'C', true);

            $x = 10;
            $y += 5;
            $this->pdf->SetXY($x, $y);
            $this->pdf->Cell(95, 5, "Gerente Cinemex", 1, 0, 'C', true);
            $x += 95;
            $this->pdf->SetXY($x, $y);
            $this->pdf->Cell(95, 5, utf8_decode("Técnico Siccob / Lexmark"), 1, 0, 'C', true);
        }


        $carpeta = $this->pdf->definirArchivo('instalaciones/' . $servicio . '/PDF/', 'Instalación Imp. Lexmark ' . $generales['Sucursal']);
        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);
        return $carpeta;
    }
}
