<?php

namespace Librerias\Salas4D;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Proyectos\PDF as PDF;

class Seguimiento extends General {

    private $catalogo;
    private $DBC;
    private $DBCS;
    private $DBS;
    private $DBM;
    private $Correo;
    private $Phantom;
    private $InformacionServicios;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->DBC = \Modelos\Modelo_Catalogo::factory();
        $this->DBM = \Modelos\Modelo_ServicioTicket::factory();
        $this->DBCS = \Modelos\Modelo_Salas4D::factory();
        $this->DBS = \Modelos\Modelo_Loguistica_Seguimiento::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->Phantom = \Librerias\Generales\Phantom::factory();
        $this->InformacionServicios = \Librerias\WebServices\InformacionServicios::factory();

        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioTipoSistema(array $datos) {
        $data = array();

        if (!empty($datos)) {
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_x4d_tipos_sistema WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioTipoSistema', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioEquipo(array $datos) {
        $data = array();
        if (!empty($datos)) {
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_x4d_equipos WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioEquipo', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioMarca(array $datos) {
        $data = array();

        if (!empty($datos)) {
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_x4d_marcas WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioMarca', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioModelo(array $datos) {
        $data = array();

        $data['lineas'] = $this->catalogo->catX4DEquipos('3', array('Flag' => '1'));
        $data['marcas'] = $this->catalogo->catX4DMarcas('3', array('Flag' => '1'));

        if (!empty($datos)) {
            $data['ids'] = $this->catalogo->catConsultaGeneral("select                                                                 
                                                                cxele.IdEquipo,                                                                
                                                                cxele.IdMarca,                                                                
                                                                cxele.ClaveSAE                                                                
                                                                from cat_v3_x4d_elementos cxele 
                                                                inner join cat_v3_x4d_equipos cxe on cxele.IdEquipo = cxe.Id
                                                                inner join cat_v3_x4d_marcas cxm on cxele.IdMarca = cxm.Id
                                                                where cxele.Id = '" . $datos['id'] . "'");
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_x4d_elementos WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['ids'] = null;
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioModelo', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioComponente(array $datos) {
        $data = array();

        $data['elementos'] = $this->catalogo->catX4DModelos('3', array('Flag' => '1'));
        $data['marcas'] = $this->catalogo->catX4DMarcas('3', array('Flag' => '1'));

        if (!empty($datos)) {
            $data['ids'] = $this->catalogo->catConsultaGeneral("SELECT                                                                
                                                                cxs.IdElemento,
                                                                cxs.IdMarca,
                                                                cxs.ClaveSAE
                                                                from cat_v3_x4d_subelementos cxs where cxs.Id = '" . $datos['id'] . "';");
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_x4d_subelementos WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['ids'] = null;
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioComponente', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioUbicacion(array $datos) {
        $data = array();

        if (!empty($datos)) {
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_x4d_ubicaciones WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioUbicacion', $data, TRUE), 'datos' => $data);
    }

    public function multiselectX4D(string $operacion, array $where = null) {
        switch ($operacion) {
            //Muestra los datos de la tabla Equipos X4D
            case '1':
                $consulta = $this->DBC->getArticulos('cat_v3_x4d_equipos', $where);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            //Muestra los datos de la tabla Marcas X4D
            case '2':
                $consulta = $this->DBC->getArticulos('cat_v3_x4d_marcas', $where);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            //Muestra los datos de la tabla Municipios
            case '3':
                $consulta = $this->DBC->getArticulos('cat_v3_x4d_modelos', $where);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            default:
                break;
        }
    }

    public function obtenerActividadesSeguimientoJson($datos) {
        $arrayDB = $this->catalogo->catX4DActividadesSeguimiento('3', array('Flag' => '1'));
        $arraySistemas = $this->catalogo->catX4DTiposSistemaSegumiento('3', array('Flag' => '1'));
        $actividadesAutorizadas = $this->DBCS->getActividadesAutorizadasManttoSalas4D($datos['servicio']);

        $actividadesAutorizadasArray = [];
        if (!empty($actividadesAutorizadas) && isset($actividadesAutorizadas[0])) {
            $actividadesAutorizadasArray = explode(",", $actividadesAutorizadas[0]['Actividades']);
        }

        $json = [];
        foreach ($arraySistemas as $k1 => $v1) {
            array_push($json, [
                'id' => 'sistema-' . $v1['Id'],
                'parent' => '#',
                'text' => $v1['Nombre'],
                'li_attr' => ['sistema' => $v1['Id']],
                'state' => ['opened' => true, 'disabled' => true]
            ]);
        }

        foreach ($arrayDB as $key => $value) {
            array_push($json, [
                'id' => $value['Id'],
                'parent' => ($value['IdPadre'] != '' && $value['IdPadre'] != 0 ) ? $value['IdPadre'] : 'sistema-' . $value['IdSistema'],
                'text' => $value['Nombre'],
                'li_attr' => ['sistema' => $value['IdSistema']],
                'state' => ['opened' => true]
            ]);
        }

        return ['json' => $json, 'autorizadas' => $actividadesAutorizadasArray];
    }

    public function guardarX4DActividadesSeguimiento(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $arrayid = implode(',', $datos['arrayIds']);
        $arraydatos = array(
            'IdServicio' => $datos['tipoServicio'],
            'IdUsuario' => $usuario['Id'],
            'Fecha' => $fecha,
            'Actividades' => $arrayid,
            'Flag' => '1');
        $result = $this->DBCS->insertaActividadesTransaccion($arraydatos);
        return $result;
    }

    public function guardarX4DIdSeguimiento(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $arraydataids = array(
            'IdServicio' => $datos['servicio'],
            'IdActividad' => $datos['actividad'],
            'IdAtiende' => $datos['atiende'],
            'IdEstatus' => $datos['estatus'],
            'IdUsuario' => $usuario['Id'],
            'Fecha' => $fecha,
        );

        $consulta = $this->DBCS->insertaActividadesTransaccionids($arraydataids);

        if (!empty($consulta)) {
            if (in_array('223', $usuario['PermisosAdicionales'])) {
                $datos = $this->DBCS->getActividadesSeguimientoActividadesSalas4($datos['servicio']);
            } else if (in_array('223', $usuario['Permisos'])) {
                $datos = $this->DBCS->getActividadesSeguimientoActividadesSalas4($datos['servicio']);
            } else {
                $datos = $this->DBCS->getActividadesSeguimientoActividadesSalas4Usuario($datos['servicio'], $usuario['Id']);
            }

            return $datos;
        } else {
            return FALSE;
        }
    }

    public function guardarMantenimientoGeneral(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Salas4XD/SeguimientoActividad/';
        $archivos = setMultiplesArchivos($CI, 'archivosSeguimientoActividad', $carpeta);
        $archivos = implode(',', $archivos);

        $datosTabla = array();

        foreach (array_filter(explode('@', $datos['datosTabla']), "strlen") as $key => $value) {
            array_push($datosTabla, array_filter(explode(',', $value)));
        }

        $arrayDatos = array(
            'IdServicio' => $datos['servicio'],
            'IdActividad' => $datos['actividad'],
            'IdUsuario' => $usuario['Id'],
            'Fecha' => $fecha,
            'Observaciones' => $datos['observaciones'],
            'IdUbicacion' => $datos['ubicacion'],
            'IdElemento' => $datos['elemento'],
            'IdSubelemento' => $datos['subelemento'],
            'DatosProductos' => $datosTabla,
            'IdSucursal' => $datos['sucursal'],
            'Archivos' => $archivos,
            'IdSistema' => $datos['idSistema']);

        $result = $this->DBCS->insertaMantenimientoGeneral($arrayDatos);

        return $result;
    }

    public function obtenerX4DActivdadesSeguimiento(array $consultaActividad) {
        $consultaActividad = $this->DBCS->consultaActividades();
    }

    public function cargarActividadesSeguimiento(array $datos) {
        $data = [
            'actividades' => $this->DBCS->getActividadesSeguimientoSalas4D($datos['servicio']),
            'sistemas' => $this->catalogo->catX4DTiposSistemaSegumiento('3', array('Flag' => '1')),
            'usuarios' => $this->DBCS->getUsuariosDepartamento(),
            'idServicio' => $datos['servicio'],
            'idPadreAct' => $datos['idsPadre']
        ];
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioAsignacionActividades', $data, TRUE), 'datos' => $data);
    }

    public function informacionActividades(array $dato) {

        $informe = $this->DBCS->getInformeActividades($dato['idActividad'][0], $dato['idServicio'][0]);
        $htmlHistorialInforme = [];
        foreach ($informe as $key => $valor) {
            $producto = $this->DBCS->getProductosInforme($valor['Id']);

            if (!empty($producto)) {
                $htmlHistorialInforme[$valor['Id']] = '<div class="table-responsive">
                                                <table class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="all">Tipo Producto</th>
                                                            <th class="all">Producto</th>
                                                            <th class="all">Cantidad</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>';
                foreach ($producto as $k => $v) {
                    $htmlHistorialInforme[$valor['Id']] .= '<tr>
                                <td>' . $v['TipoProducto'] . '</td>
                                <td>' . $v['Producto'] . ' ' . $v['Serie'] . ' </td>
                                <td>' . $v['Cantidad'] . '</td>
                                </tr>';
                }
                $htmlHistorialInforme[$valor['Id']] .= '</tbody>
                                                </table>
                                            </div>';
            } else {
                $htmlHistorialInforme[$valor['Id']] = "";
            }
        }
        $data = [
            'actividad' => $informe,
            'productohtml' => $htmlHistorialInforme
        ];
        return array('informe' => parent::getCI()->load->view('Salas4D/Modal/InformeAsignacionActividades', $data, TRUE), 'datos' => $data);
    }

    public function enviarPDF(array $datos, string $nombreExtra = NULL) {
        $infoServicio = $this->DBCS->getInformacionServicio($datos['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $nombreExtra = (is_null($nombreExtra)) ? '' : $nombreExtra;
        $archivo = 'storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $infoServicio[0]['Ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . $nombreExtra . '.pdf ';
        $ruta = 'http://' . $_SERVER['HTTP_HOST'] . '/Phantom/Servicio/' . $datos['servicio'] . '/' . $nombreExtra;
        $link = $this->Phantom->htmlToPdf($archivo, $ruta);
        return ['link' => $link];
    }

    public function concluirServicioFirma(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $infoActividad = $this->DBCS->getActividadesSeguimientoSalas4D($datos['servicio']);
        $host = $_SERVER['SERVER_NAME'];
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $sucursal = $this->InformacionServicios->sucursalServicio($datos['servicio']);
        $titulo = 'Se concluyo el servicio' . $sucursal;
        $imgFirma = $datos['img'];
        $imgFirma = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $imgFirma));
        $linkPDF = '';
        $dataFirma = $dataFirmaTecnico = base64_decode($imgFirma);
        $correo = implode(",", $datos['correo']);
        $nombreServ = $this->getNombreServicio($datos['servicio']);
        $direccionFirma = '/storage/Archivos/imagenesFirmas/' . str_replace(' ', '_', 'Firma_' . $datos['ticket'] . '_' . $datos['servicio']) . '.png';
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccionFirma, $dataFirma);

        $arrayServicio = array(
            'Estatus' => '4',
            'FechaConclusion' => $fecha,
            'Firma' => $direccionFirma,
            'NombreFirma' => $datos['nombreFirma'],
            'CorreoCopiaFirma' => $correo,
            'FechaFirma' => $fecha,
            'servicio' => $datos['servicio'],
        );
        $correoEnviar = $this->DBCS->concluirServicio($arrayServicio);
        $linkPdf = $this->enviarPDF(array('servicio' => $datos['servicio']));
        $infoServicio = $this->DBCS->getInformacionServicio($datos['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['Id'] . '/Pdf/Ticket_' . $datos['Ticket'] . '_Servicio_' . $datos['Id'] . '_' . $tipoServicio . '.pdf';
        } else {
            $path = 'http://' . $host . '/' . $linkPdf['link'];
        }

        $linkPDF = '<br>Para descargar el archivo PDF de conclusión <a href="' . $path . '" target="_blank">dar click aqui</a>';
        $textoCorreo = '<p>Se notifica que el servicio de ' . $nombreServ . ' con numero de ticket se a concluido por ' . $usuario['Nombre'] . '<br>' . $linkPDF . '</p>';


        foreach ($correoEnviar as $key => $value) {
            $this->enviarCorreoConcluido(array($value['CorreoCopiaFirma']), $titulo, $textoCorreo);
            return TRUE;
        }
    }

    public function enviarCorreoConcluido(array $correo, string $titulo, string $texto) {
        $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $correo, $titulo, $mensaje);
    }

    public function getDetallesServicio4D(string $servicio, bool $esPdf = false) {
        $generalesSolicitud = $this->DBCS->getGeneralesSolicitudServicio($servicio);
        $generales = $this->getGeneralesSinClasificar($servicio, $esPdf);
        $infoActividad = $this->DBCS->getActividadesSeguimientoSalas4D($servicio);
        $productosServicio = $this->DBCS->getProductosServicio($servicio);
        $vistaAvance = array();
        $avances = array();

        foreach ($infoActividad as $valor) {

            $avances = $this->DBCS->getInformeActividades($valor['Id'], $servicio);

            $vistaAvance[$valor['Id']] = array();
            foreach ($avances as $v) {
                array_push($vistaAvance[$valor['Id']], $v);
                $tablaProductos = $this->DBCS->getProductosInforme($v['Id']);

                if (!empty($tablaProductos)) {
                    $vistaProductos[$v['Id']] = array();
                    foreach ($tablaProductos as $clave => $value) {
                        array_push($vistaProductos[$v['Id']], $value);
                    }
                } else {
                    $vistaProductos[$v['Id']] = '';
                }
            }
        }

        $data = [
            'solicitud' => $generalesSolicitud,
            'generales' => $generales,
            'infoActividad' => $infoActividad,
            'vistaAvance' => $vistaAvance,
            'vistaProductos' => $vistaProductos,
            'tablaProductosServicio' => $productosServicio
        ];

        if (!$esPdf) {
            return FALSE;
        } else {
            return parent::getCI()->load->view('Salas4D/Modal/serviciosSalas4DPdf', $data, TRUE);
        }
    }

    public function getNombreServicio(string $servicio) {
        $consulta = $this->DBCS->getNombreServicio($servicio);
        if (is_array($consulta)) {
            foreach ($consulta as $key => $value) {
                return $value['nombreServicio'];
            }
        } else {
            return "Sin Clasificar";
        }
    }

    public function getGeneralesSinClasificar(string $servicio, bool $esPdf = false) {
        $generales = $this->DBCS->getGeneralesSinClasificar($servicio);
        $returnArray = [
            'descripcion' => 'Sin Información',
            'archivos' => '',
            'fecha' => 'Sin Información'
        ];
        if (array_key_exists(0, $generales)) {
            $returnArray['descripcion'] = ($generales[0]['Descripcion'] !== '') ? $generales[0]['Descripcion'] : 'Sin Información';
            if ($esPdf) {
                $returnArray['archivos'] = ($generales[0]['Archivos'] !== '') ? $this->getHtmlArchivosPdf($generales[0]['Archivos'], $servicio) : '';
            } else {
                $returnArray['archivos'] = ($generales[0]['Archivos'] !== '') ? $this->getHtmlArchivosWeb($generales[0]['Archivos'], $servicio) : '';
            }
            $returnArray['fecha'] = ($generales[0]['Fecha'] !== '') ? $generales[0]['Fecha'] : 'Sin Información';
        }
        return $returnArray;
    }

    public function guardarDatosGeneralesSalas4xd(array $datos) {
        $consulta = $this->DBS->actualizarSeguimiento('t_servicios_ticket', array(
            'IdSucursal' => $datos['sucursal'],
                ), array('Id' => $datos['servicio'])
        );

        if ($consulta) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function mostrarFormularioSeguimientoActvidad(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $data = array();
        $elementos = '';
        $data['seguimientoActividades'] = NULL;
        $consulta = $this->catalogo->catConsultaGeneral('SELECT IdSucursal FROM t_servicios_ticket WHERE Id = "' . $datos['servicio'] . '"');
        $data['tipoProductos'] = $this->tipoProductos(array('id' => '5'));
        $data['sucursal'] = $consulta[0]['IdSucursal'];
        $data['ubicaciones'] = $this->catalogo->catConsultaGeneral('SELECT 
                                                                            IdUbicacion AS Id,
                                                                            (SELECT Nombre FROM cat_v3_x4d_ubicaciones WHERE Id = IdUbicacion) Nombre 
                                                                        FROM t_elementos_salas4D
                                                                        WHERE IdSucursal = "' . $consulta[0]['IdSucursal'] . '"
                                                                        AND IdSistema = "' . $datos['idSistema'] . '"
                                                                        GROUP BY IdUbicacion');
        $data['historialAvances'] = $this->catalogo->catConsultaGeneral('SELECT 
                                                                                tsma.Id,
                                                                                tsma.IdServicio,
                                                                                tsmaa.*,
                                                                                nombreUsuario(tsmaa.IdUsuario) NombreUsuario,
                                                                                (SELECT Nombre FROM cat_v3_x4d_ubicaciones WHERE Id = tsmaa.IdUbicacion) Ubicación,
                                                                                if(tsmaa.IdRegistroElemento is null or tsmaa.IdRegistroElemento = 0,"", (SELECT Nombre FROM cat_v3_x4d_elementos WHERE Id = tes.IdElemento)) Elemento,
                                                                                if(tsmaa.IdRegistroSubelemento is null or tsmaa.IdRegistroSubelemento = 0,"", (SELECT Nombre FROM cat_v3_x4d_subelementos WHERE Id = tss.IdSubelemento)) Subelemento
                                                                        FROM t_salas4d_mantto_actividades_avances tsmaa
                                                                        INNER JOIN t_salas4d_mantto_actividades tsma
                                                                        ON tsmaa.IdActividad = tsma.Id
                                                                        LEFT JOIN t_elementos_salas4d tes
                                                                        ON tes.Id = tsmaa.IdRegistroElemento
                                                                        LEFT JOIN t_subelementos_salas4d tss
                                                                        ON tss.Id = tsmaa.IdRegistroSubelemento
                                                                        WHERE tsma.Id = "' . $datos['actividad'] . '"
                                                                        ORDER BY tsmaa.Fecha DESC');


        $htmlHistorialAvances = '';
        if ($data['historialAvances']) {
            foreach ($data['historialAvances'] as $key => $value) {
                $productos = $this->catalogo->catConsultaGeneral('SELECT 
                                                                        (SELECT Nombre FROM cat_v3_tipos_producto_inventario WHERE Id = ti.IdTipoProducto) TipoProducto,
                                                                        CASE ti.IdTipoProducto
                                                                                WHEN 3 THEN (SELECT Nombre FROM cat_v3_x4d_elementos WHERE Id = ti.IdProducto) 
                                                                        WHEN 4 THEN (SELECT Nombre FROM cat_v3_x4d_subelementos WHERE Id = ti.IdProducto)
                                                                        WHEN 5 THEN (SELECT Nombre FROM cat_v3_equipos_sae WHERE Id = ti.IdProducto) 
                                                                        END as Producto,
                                                                        ti.Serie,
                                                                        tsmaap.Cantidad
                                                                FROM t_salas4d_mantto_actividades_avance_productos tsmaap
                                                                INNER JOIN t_inventario ti
                                                                ON tsmaap.IdRegistroInventario = ti.Id 
                                                                WHERE IdRegistroAvance = "' . $value['Id'] . '"');


                $htmlArchivos = '';
                if ($value['Archivos'] !== '' && $value['Archivos'] !== NULL) {
                    $htmlArchivos .= '';
                    $archivos = explode(",", $value['Archivos']);
                    foreach ($archivos as $k => $v) {
                        $pathInfo = pathinfo($v);
                        if (array_key_exists("extension", $pathInfo)) {
                            switch (strtolower($pathInfo['extension'])) {
                                case 'doc': case 'docx':
                                    $scr = '/assets/img/Iconos/word_icon.png';
                                    break;
                                case 'xls': case 'xlsx':
                                    $scr = '/assets/img/Iconos/excel_icon.png';
                                    break;
                                case 'pdf':
                                    $scr = '/assets/img/Iconos/pdf_icon.png';
                                    break;
                                case 'jpg': case 'jpeg': case 'bmp': case 'gif': case 'png':
                                    $scr = $v;
                                    break;
                                default :
                                    $scr = '/assets/img/Iconos/file_icon.png';
                                    break;
                            }
                        } else {
                            $scr = '/assets/img/Iconos/file_icon.png';
                        }
                        $htmlArchivos .= ''
                                . '<div class="evidencia">'
                                . ' <a class="m-l-5 m-r-5" href="' . $v . '" data-lightbox="image-' . $value['Id'] . '" data-title="' . $pathInfo['basename'] . '">'
                                . '     <img src="' . $scr . '" style="max-height:115px !important;" alt="' . $pathInfo['basename'] . '"  />'
                                . '     <p class="m-t-0">' . $pathInfo['basename'] . '</p>'
                                . ' </a>'
                                . '</div>';
                    }
                }

                if (!empty($value['Elemento']) && $value['Subelemento'] === '') {
                    $elementos = '<label>Elemento: <strong>' . $value['Elemento'] . '</strong></label>';
                } else if (!empty($value['Elemento']) && !empty($value['Subelemento'])) {
                    $elementos = '<label>Elemento: <strong>' . $value['Elemento'] . '</strong></label>
                                  <br>
                                  <label>Subelemento: <strong>' . $value['Subelemento'] . '</strong></label>';
                }

                $fecha = strftime('%A %e de %B, %G ', strtotime($value['Fecha'])) . date("h:ma", strtotime($value['Fecha']));
                $htmlHistorialAvances .= ''
                        . '<li class="media media-sm">'
                        . ' <div class="media-body">'
                        . '     <h5 class="media-heading">' . $value['NombreUsuario'] . '</h5>'
                        . '     <h6 class="f-w-600">' . $value['Fecha'] . '</h6>'
                        . '     <p class="f-w-600">' . $value['Observaciones'] . '</p>'
                        . '     ' . $htmlArchivos
                        . '     <br>'
                        . $elementos
                        . ' </div>'
                        . '</li>';

                if (!empty($productos)) {
                    $htmlHistorialAvances .= '<div class="table-responsive">
                                            <table id="data-table-actividades-asignadas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th class="all">Tipo Producto</th>
                                                        <th class="all">Producto</th>
                                                        <th class="all">Cantidad</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                    foreach ($productos as $key => $v) {
                        $htmlHistorialAvances .= '<tr>
                        <td>' . $v['TipoProducto'] . '</td>
                        <td>' . $v['Producto'] . ' ' . $v['Serie'] . ' </td>
                        <td>' . $v['Cantidad'] . '</td>
                        </tr>';
                    }
                    $htmlHistorialAvances .= '</tbody>
                                            </table>
                                        </div>';
                }
            }
        } else {
            $htmlHistorialAvances = 'No existe ningun avance.';
        }

        $data['htmlHistorialAvances'] = $htmlHistorialAvances;

        if ($datos['estatus'] === 'ABIERTO') {
            $consultaManttoActividades = $this->DBS->actualizarSeguimiento('t_salas4d_mantto_actividades', array(
                'IdEstatus' => '2',
                    ), array('Id' => $datos['actividad'])
            );

            if (!empty($consultaManttoActividades)) {
                if (in_array('223', $usuario['PermisosAdicionales'])) {
                    $data['seguimientoActividades'] = $this->DBCS->getActividadesSeguimientoActividadesSalas4($datos['servicio']);
                } else if (in_array('223', $usuario['Permisos'])) {
                    $data['seguimientoActividades'] = $this->DBCS->getActividadesSeguimientoActividadesSalas4($datos['servicio']);
                } else {
                    $data['seguimientoActividades'] = $this->DBCS->getActividadesSeguimientoActividadesSalas4Usuario($datos['servicio'], $usuario['Id']);
                }
            }
        }

        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioSeguimientoActividad', $data, TRUE), 'datos' => $data);
    }

    public function mostrarTipoProducto(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();

        $productos = $this->catalogo->catConsultaGeneral("select 
                                                            Id as IdRegistroInventario,
                                                            inve.Cantidad,
                                                            inve.Serie,
                                                            CASE inve.IdtipoProducto
                                                            WHEN 1 THEN
                                                                    modelo(inve.IdProducto)
                                                            WHEN 2 THEN
                                                                CONCAT(
                                                                    (select Nombre from cat_v3_componentes_equipo where Id = inve.IdProducto), 
                                                                    ' (',
                                                                    modelo((select IdModelo from cat_v3_componentes_equipo where Id = inve.IdProducto)),
                                                                    ')'
                                                                    )
                                                            WHEN 3 THEN
                                                                elementoSalas4D(inve.IdProducto)                                                    
                                                            WHEN 4 THEN
                                                                CONCAT(
                                                                    subelementoSalas4D(inve.IdProducto),
                                                                    ' [',
                                                                    elementoSalas4D((select IdElemento from cat_v3_x4d_subelementos where Id = inve.IdProducto)),
                                                                    ']'
                                                                )
                                                            WHEN 5 THEN 
                                                                (select concat('[',Clave,']  ',Nombre) as Nombre from cat_v3_equipos_sae productos where Id = inve.IdProducto)
                                                            END AS Producto
                                                        from t_inventario inve where inve.IdTipoProducto = " . $datos['tipoProducto'] . " and inve.IdAlmacen in (select Id from cat_v3_almacenes_virtuales where IdTipoAlmacen = 1 and IdReferenciaAlmacen = " . $usuario['Id'] . ") "
                . "                                     and inve.IdEstatus = 17"
                . "                                     AND inve.Cantidad > 0");


        return $productos;
    }

    public function elementosSeguimientoActividad(array $datos) {
        $consulta = $this->catalogo->catConsultaGeneral('SELECT 
                                                            Id,
                                                            (SELECT Nombre FROM cat_v3_x4d_elementos WHERE Id = IdElemento) Nombre,
                                                            (SELECT marca.Nombre FROM cat_v3_x4d_elementos as element INNER JOIN cat_v3_x4d_marcas AS marca on IdMarca = marca.Id WHERE element.Id = IdElemento) Marca,
                                                            Serie
                                                        FROM t_elementos_salas4d
                                                        WHERE IdSucursal = "' . $datos['sucursal'] . '"
                                                        AND IdUbicacion = "' . $datos['ubicacion'] . '"
                                                        AND IdSistema = "' . $datos['idSistema'] . '"
                                                        AND Flag = 1');
        return $consulta;
    }

    public function subelementosSeguimientoActividad(array $datos) {
        $consulta = $this->catalogo->catConsultaGeneral('SELECT 
                                                            Id,
                                                            (SELECT Nombre FROM cat_v3_x4d_subelementos WHERE Id = IdSubElemento) Nombre,
                                                            (SELECT marca.Nombre FROM cat_v3_x4d_subelementos as element INNER JOIN cat_v3_x4d_marcas AS marca on IdMarca = marca.Id WHERE element.Id = IdSubElemento) Marca,
                                                            Serie
                                                        FROM t_subelementos_salas4d
                                                        WHERE IdRegistroElemento = "' . $datos['elemento'] . '"
                                                        AND Flag = 1');

        return $consulta;
    }

    public function tipoProductos(array $datos) {
        $consulta = $this->catalogo->catConsultaGeneral('SELECT * FROM cat_v3_tipos_producto_inventario WHERE Flag = 1 AND Id = "' . $datos['id'] . ' "');

        return $consulta;
    }

    public function verificarSucursal(array $datos) {
        $consulta = $this->catalogo->catConsultaGeneral('SELECT IdSucursal FROM t_servicios_ticket WHERE Id = "' . $datos['servicio'] . '"');

        if (!empty($consulta[0]['IdSucursal'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function concluirActividad(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $consultaManttoActividades = $this->DBS->actualizarSeguimiento('t_salas4d_mantto_actividades', array(
            'IdEstatus' => '4',
                ), array('Id' => $datos['actividad'])
        );

        if (!empty($consultaManttoActividades)) {
            if (in_array('223', $usuario['PermisosAdicionales'])) {
                $actividadesSalas4D = $this->DBCS->getActividadesSeguimientoActividadesSalas4($datos['servicio']);
            } else if (in_array('223', $usuario['Permisos'])) {
                $actividadesSalas4D = $this->DBCS->getActividadesSeguimientoActividadesSalas4($datos['servicio']);
            } else {
                $actividadesSalas4D = $this->DBCS->getActividadesSeguimientoActividadesSalas4Usuario($datos['servicio'], $usuario['Id']);
            }
            return $actividadesSalas4D;
        } else {
            return FALSE;
        }
    }

    public function ActualizaEstatus(array $datos) {

        $consulta = $this->DBS->actualizarSeguimiento('t_salas4d_mantto_actividades', array(
            'IdEstatus' => '2',
                ), array('IdActividad' => $datos['idActividad'], 'IdAtiende' => $datos['idAtiende'])
        );
        if ($consulta) {
            $usuario = $this->Usuario->getDatosUsuario();
            if (in_array('223', $usuario['PermisosAdicionales'])) {
                $datos = $this->DBCS->getActividadesSeguimientoActividadesSalas4($datos['servicio']);
            } else if (in_array('223', $usuario['Permisos'])) {
                $datos = $this->DBCS->getActividadesSeguimientoActividadesSalas4($datos['servicio']);
            } else {
                $datos = $this->DBCS->getActividadesSeguimientoActividadesSalas4Usuario($datos['servicio'], $usuario['Id']);
            }

            return $datos;
        } else {
            return FALSE;
        }
    }

}
