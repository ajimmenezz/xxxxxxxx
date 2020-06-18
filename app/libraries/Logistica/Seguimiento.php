<?php

namespace Librerias\Logistica;

ini_set('max_execution_time', 3600);

use Controladores\Controller_Datos_Usuario as General;

class Seguimiento extends General {

    private $DBS;
    private $DBR;
    private $DBST;
    private $catalogo;
    private $libroExcel;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Loguistica_Seguimiento::factory();
        $this->DBR = \Modelos\Modelo_Rutas::factory();
        $this->DBST = \Modelos\Modelo_ServicioTicket::factory();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        parent::getCI()->load->helper(array('FileUpload', 'date'));
    }

    /*
     * Metodo para solo para actualizar datos 
     * 
     * @param array $datos recibe los datos para actualiazar
     * @return array devuelve una array con los valores de la consulta en caso de error un false.
     */

    public function verificarExistente(array $datos) {
        switch ($datos['operacion']) {
            case '1':
                $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_traficos_generales WHERE IdServicio = ' . $datos['servicio'] . ' AND IdClasificacion = ' . $datos['clasificacion']);
                if (!empty($consulta)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
                break;
            case '2':
                $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_traficos_generales WHERE IdServicio = ' . $datos['servicio'] . ' AND IdTipoTrafico = ' . $datos['tipoTrafico']);
                if (!empty($consulta)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
                break;
            case '3':
                $consulta = $this->DBS->consultaGeneralSeguimiento('
                    SELECT 
                        tte.*,
                        paqueteria(tte.IdPaqueteria) as NombrePaqueteria
                    FROM t_traficos_envios tte WHERE tte.IdServicio =' . $datos['servicio']);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            case '4':
                $consulta = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_traficos_recolecciones WHERE IdServicio = ' . $datos['servicio']);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            case '5':
                $consulta = $this->DBS->consultaGeneralSeguimiento('
                    SELECT 	
                        ttg.*,
                        (
                            select if(Flag = 0, null, IdRuta) 
                            from t_servicios_x_ruta 
                            where IdServicio = ttg.IdServicio order by Id desc limit 1 
                        ) as Ruta,
                        case ttg.IdTipoOrigen
                            when 1
                                then sucursal(ttg.IdOrigen)
                            when 2
                                then proveedor(ttg.IdOrigen)
                            when 3 
                                then ttg.OrigenDireccion
                        end as NombreOrigen,
                        case ttg.IdTipoDestino
                            when 1
                                then sucursal(ttg.IdDestino)
                            when 2
                                then proveedor(ttg.IdDestino)
                            when 3 
                                then ttg.DestinoDireccion
                        end as NombreDestino
                    FROM t_traficos_generales ttg 
                    WHERE ttg.IdServicio = ' . $datos['servicio']);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
        }
    }

    /*
     * Encargado de insertar o actualizar la informacion de un envio
     * 
     */

    public function actualizarEnvio(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fechaCaptura = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));


        if (empty($datos['seccion'])) {
            $tabla = 't_traficos_envios';
            $condicionDeBusqueda = $tabla . ' where IdServicio = ' . $datos['servicio'];
            $where = array('IdServicio' => $datos['servicio']);
        } else if ($datos['seccion'] === 'Distribucion') {
            $tabla = 't_traficos_distribuciones';
            $condicionDeBusqueda = $tabla . ' where IdServicio = ' . $datos['servicio'] . ' and Id = ' . $datos['destino'];
            $where = array('IdServicio' => $datos['servicio'], 'Id' => $datos['destino']);
        }

        $servicio = $this->DBS->consultaGeneralSeguimiento('select * from ' . $condicionDeBusqueda);

        if (!empty($servicio)) {
            if ($datos['tipoenvio'] === '1') {
                if (!empty($servicio[0]['UrlEnvio'])) {
                    $evidencias = explode(',', $servicio[0]['UrlEnvio']);
                    foreach ($evidencias as $key => $value) {
                        eliminarArchivo($value);
                    }
                }
                $consulta = $this->DBS->actualizarSeguimiento($tabla, array(
                    'IdUsuarioCaptura' => $usuario['Id'],
                    'FechaCaptura' => $fechaCaptura,
                    'IdTipoenvio' => $datos['tipoenvio'],
                    'IdPaqueteria' => $datos['idPaqueteria'],
                    'FechaEnvio' => $datos['fechaEnvio'],
                    'Guia' => $datos['guia'],
                    'ComentariosEnvio' => $datos['comentariosEnvio'],
                    'UrlEnvio' => '',
                    'FechaEntrega' => $datos['fechaEntrega'],
                    'NombreRecibe' => $datos['nombreRecibe'],
                    'ComentariosEntrega' => $datos['comentariosEntrega']
                        ), $where);
            } else if ($datos['tipoenvio'] === '2' || $datos['tipoenvio'] === '3') {
                $consulta = $this->DBS->actualizarSeguimiento($tabla, array(
                    'IdUsuarioCaptura' => $usuario['Id'],
                    'FechaCaptura' => $fechaCaptura,
                    'IdTipoenvio' => $datos['tipoenvio'],
                    'IdPaqueteria' => $datos['idPaqueteria'],
                    'FechaEnvio' => $datos['fechaEnvio'],
                    'Guia' => $datos['guia'],
                    'ComentariosEnvio' => $datos['comentariosEnvio'],
                    'FechaEntrega' => $datos['fechaEntrega'],
                    'NombreRecibe' => $datos['nombreRecibe'],
                    'ComentariosEntrega' => $datos['comentariosEntrega']
                        ), $where);
            }
        } else {

            $consulta = $this->DBS->insertarSeguimiento($tabla, array(
                'IdServicio' => $datos['servicio'],
                'IdUsuarioCaptura' => $usuario['Id'],
                'FechaCaptura' => $fechaCaptura,
                'IdTipoEnvio' => $datos['tipoenvio'],
                'IdPaqueteria' => $datos['idPaqueteria'],
                'FechaEnvio' => $datos['fechaEnvio'],
                'Guia' => $datos['guia'],
                'ComentariosEnvio' => $datos['comentariosEnvio'],
                'FechaEntrega' => $datos['fechaEntrega'],
                'NombreRecibe' => $datos['nombreRecibe'],
                'ComentariosEntrega' => $datos['comentariosEntrega']
            ));
        }

        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de guardar la evidencia de la informacion de un envio
     * 
     */

    public function setEvidencia(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fechaCaptura = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $recoleccion = TRUE;


        //Valida si existen archivos nuevos
        if (!empty($_FILES)) {

            $CI = parent::getCI();
            foreach ($_FILES as $key => $value) {
                $nombre = $key;
            }

            if ($nombre === 'evidenciasEnvio') {
                $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/EvidenciasEnvio/';
                $url = 'UrlEnvio';
                $recoleccion = FALSE;
                $tabla = 't_traficos_envios';
            } else if ($nombre === 'evidenciasEntregaEnvio') {
                $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/EvidenciasEntregaEnvio/';
                $url = 'UrlEntrega';
                $recoleccion = FALSE;
                $tabla = 't_traficos_envios';
            } else if ($nombre === 'evidenciasRecoleccion') {
                $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/EvidenciasRecoleccion/';
                $url = 'UrlRecoleccion';
                $tabla = 't_traficos_recolecciones';
            } else if ($nombre === 'evidenciasRecoleccionDistribucion') {
                $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/EvidenciasRecoleccionDistribucion/';
                $url = 'UrlRecoleccion';
                $tabla = 't_traficos_recolecciones';
            } else if ($nombre === 'evidenciasEnvioConsolidadoPaqueteriaDistribucion') {
                $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/EvidenciasEnvioConsolidadoPaqueteriaDistribucion/';
                $url = 'UrlEnvio';
                $tabla = 't_traficos_distribuciones';
            } else if ($nombre === 'evidenciasEntregaEnvioDistribucion') {
                $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/EvidenciasEntregaEnvioDistribucion/';
                $url = 'UrlEntrega';
                $tabla = 't_traficos_distribuciones';
            }

            $archivos = setMultiplesArchivos($CI, $nombre, $carpeta);

            if (array_key_exists('destino', $datos)) {
                $consulta = 'select ' . $url . ' from ' . $tabla . ' where IdServicio = ' . $datos['servicio'] . ' and Id= ' . $datos['destino'];
                $where = array('IdServicio' => $datos['servicio'], 'Id' => $datos['destino']);
            } else if ($recoleccion) {
                $consulta = 'select ' . $url . ' from ' . $tabla . ' where IdServicio = ' . $datos['servicio'];
                $where = array('IdServicio' => $datos['servicio']);
            } else {
                $consulta = 'select ' . $url . ' from ' . $tabla . ' where IdServicio = ' . $datos['servicio'];
                $where = array('IdServicio' => $datos['servicio']);
            }

            $servicio = $this->DBS->consultaGeneralSeguimiento($consulta);

            if (!empty($archivos)) {
                if (!empty($servicio)) {
                    $evidencias = explode(',', $servicio[0][$url]);
                    if (!empty($evidencias)) {
                        foreach ($evidencias as $value) {
                            array_push($archivos, $value);
                        }
                    }
                    $archivos = implode(',', $archivos);
                    $longitud = strlen($archivos);
                    $ultimaComa = strrpos($archivos, ',', -1);
                    if ($longitud === ($ultimaComa + 1)) {
                        $archivos = substr($archivos, 0, $ultimaComa);
                    }
                    $consulta = $this->DBS->actualizarSeguimiento($tabla, array($url => $archivos), $where);
                } else {
                    $archivos = implode(',', $archivos);
                    $consulta = $this->DBS->insertarSeguimiento($tabla, array(
                        'IdServicio' => $datos['servicio'],
                        'IdUsuarioCaptura' => $usuario['Id'],
                        'FechaCaptura' => $fechaCaptura,
                        $url => $archivos)
                    );
                }
                if (!empty($consulta)) {
                    return array();
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de guardar la evidencia de la informacion de un envio
     * 
     */

    public function eliminarEvidencia(array $datos) {

        $posicionInicial = strpos($datos['key'], 'Servicio-') + 9;
        $posicionFinal = strpos($datos['key'], '/', $posicionInicial);
        $diferencia = $posicionFinal - $posicionInicial;
        $servicio = substr($datos['key'], $posicionInicial, $diferencia);

        $posicionInicial = strpos($datos['id'], '-');

        if ($posicionInicial) {
            $columnaCampo = substr($datos['id'], 0, $posicionInicial);
        } else {
            $columnaCampo = $datos['id'];
        }

        switch ($columnaCampo) {
            case 'UrlRecoleccion':
                $tabla = 't_traficos_recolecciones';
                $consultaObtenerEvidencias = 'select ' . $datos['id'] . ' from t_traficos_recolecciones where IdServicio = ' . $servicio;
                $where = array('IdServicio' => $servicio);
                break;
            case 'UrlEntrega':
                $tabla = 't_traficos_envios';
                $consultaObtenerEvidencias = 'select ' . $datos['id'] . ' from t_traficos_envios where IdServicio = ' . $servicio;
                $where = array('IdServicio' => $servicio);
                break;
            case 'UrlEnvio':
                $tabla = 't_traficos_envios';
                $consultaObtenerEvidencias = 'select ' . $datos['id'] . ' from t_traficos_envios where IdServicio = ' . $servicio;
                $where = array('IdServicio' => $servicio);
                break;
            case 'UrlEntregaDistribucion':
                $tabla = 't_traficos_distribuciones';
                $destino = substr($datos['id'], $posicionInicial + 1);
                $columnaCampo = 'UrlEntrega';
                $consultaObtenerEvidencias = 'select UrlEntrega from t_traficos_distribuciones where IdServicio = ' . $servicio . ' and Id = ' . $destino;
                $where = array('IdServicio' => $servicio, 'Id' => $destino);
                break;
            case 'UrlEnvioDistribucion':
                $tabla = 't_traficos_distribuciones';
                $destino = substr($datos['id'], $posicionInicial + 1);
                $columnaCampo = 'UrlEnvio';
                $consultaObtenerEvidencias = 'select UrlEnvio from t_traficos_recolecciones where IdServicio = ' . $servicio . ' and Id = ' . $destino;
                $where = array('IdServicio' => $servicio, 'Id' => $destino);
                break;
        }

        $evidencias = $this->DBS->consultaGeneralSeguimiento($consultaObtenerEvidencias);
        $evidencias = explode(',', $evidencias[0][$columnaCampo]);

        if (in_array($datos['key'], $evidencias)) {

            foreach ($evidencias as $key => $value) {
                if ($value === $datos['key']) {
                    unset($evidencias[$key]);
                }
            }

            $archivos = implode(',', $evidencias);
            $datosActualizar = array($columnaCampo => $archivos);
            $consulta = $this->DBS->actualizarSeguimiento($tabla, $datosActualizar, $where);

            if (!empty($consulta)) {
                eliminarArchivo($datos['key']);
            } else {
                return FALSE;
            }
        }
    }

    /* Encargado de actualizar los datos recoleccion del seguimiento logistica
     * 
     */

    public function actualizarTraficoRecoleccion(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fechaCaptura = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $datosRecoleccion = $this->DBS->consultaGeneralSeguimiento('SELECT Id FROM t_traficos_recolecciones WHERE IdServicio = ' . $datos['servicio']);
        if (empty($datosRecoleccion)) {
            $consulta = $this->DBS->insertarSeguimiento('t_traficos_recolecciones', array(
                'IdServicio' => $datos['servicio'],
                'IdUsuarioCaptura' => $usuario['Id'],
                'FechaCaptura' => $fechaCaptura,
                'Fecha' => $datos['fecha'],
                'NombreEntrega' => $datos['entrega'],
                'ComentariosRecoleccion' => $datos['observaciones']
                    )
            );
            if (!empty($consulta)) {
                return true;
            } else {
                return false;
            }
        } else {
            $consulta = $this->DBS->actualizarSeguimiento('t_traficos_recolecciones', array(
                'IdServicio' => $datos['servicio'],
                'IdUsuarioCaptura' => $usuario['Id'],
                'FechaCaptura' => $fechaCaptura,
                'Fecha' => $datos['fecha'],
                'NombreEntrega' => $datos['entrega'],
                'ComentariosRecoleccion' => $datos['observaciones']
                    ), array('IdServicio' => $datos['servicio'])
            );
            if (!empty($consulta)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /* Encargado de decargar el formato de Equipos SAE en excel
     * 
     */

    public function descargarFormato(array $datos) {

        $numeroHoja = '';
        if ($datos['idTipoTrafico'] === '1') {
            $numeroHoja = '0';
        } else {
            $numeroHoja = '1';
        }

        $listaEquipos = array();
        $tiposEquipos = array();
        $this->libroExcel = new \Librerias\Generales\Excel('./storage/Archivos/Formatos_de_Archivo/Plantilla_Equipos_SAE.xlsx');
        $obtenerlistaEquipos = $this->catalogo->catEquiposSAE('3', array('Flag' => '1'));
        $obtenerTiposEquipos = $this->DBS->consultaGeneralSeguimiento('
                    select * from cat_v3_tipos_equipo_trafico where flag = 1 and Id in (1,4,5)
                ');

        if (!empty($obtenerlistaEquipos)) {
            if (!empty($obtenerTiposEquipos)) {
                foreach ($obtenerlistaEquipos as $value) {
                    array_push($listaEquipos, array($value['Nombre'], $value['Id'], utf8_encode($value['Clave'])));
                }
                foreach ($obtenerTiposEquipos as $value) {
                    array_push($tiposEquipos, array($value['Nombre'], $value['Id']));
                }
                $this->libroExcel->limpiarHojaDatos(2, 'A');
                $this->libroExcel->limpiarHojaDatos(2, 'B');
                $this->libroExcel->limpiarHojaDatos(2, 'C');
                $this->libroExcel->cargandoCatalogo($listaEquipos, 2, 'A2');
                $this->libroExcel->crearSelectCeldasExcel(1, 'E', '2', 100, 'CatalogoEquiposSAE!$A$2:$A$5000');
                $this->libroExcel->crearSelectCeldasExcel(0, 'E', '2', 100, 'CatalogoEquiposSAE!$A$2:$A$5000');
                $this->libroExcel->limpiarHojaDatos(3, 'A');
                $this->libroExcel->limpiarHojaDatos(3, 'B');
                $this->libroExcel->cargandoCatalogo($tiposEquipos, 3, 'A2');
                $this->libroExcel->crearSelectCeldasExcel(1, 'C', '2', 100, 'CatalogoTiposEquipo!$A$2:$A$10');
                $this->libroExcel->crearSelectCeldasExcel(0, 'C', '2', 100, 'CatalogoTiposEquipo!$A$2:$A$10');
                $this->libroExcel->crearSelectCeldasExcel(1, 'G', '2', 100, 'Numeracion!$A$1:$A$500');
                $this->libroExcel->crearSelectCeldasExcel(0, 'G', '2', 100, 'Numeracion!$A$1:$A$500');
                $this->libroExcel->hojaActiva($numeroHoja);
                $this->libroExcel->protegerHoja(0);
                $this->libroExcel->protegerHoja(1);
                $this->libroExcel->protegerHoja(2);
                $this->libroExcel->protegerHoja(3);
                $this->libroExcel->protegerHoja(4);
                $this->libroExcel->actualizarArchivoExcel();
                return '/storage/Archivos/Formatos_de_Archivo/Plantilla_Equipos_SAE.xlsx';
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de cargar el formato de Equipos SAE en Excel    
     */

    public function cargarFormato(array $datos) {
        $array = array();
        $hoja = null;
        if (!empty($_FILES)) {
            $CI = parent::getCI();
            foreach ($_FILES as $key => $value) {
                $nombre = $key;
                $excel = str_replace(" ", "_", $value['name'][0]);
            }
            $carpeta = "Servicios/Servicio-" . $datos['servicio'] . "/FormatoEquiposExcel";
            $CI = parent::getCI();
            $archivos = setMultiplesArchivos($CI, $nombre, $carpeta);
            if ($archivos != false) {
                $this->libroExcel = new \Librerias\Generales\Excel('storage/Archivos/' . $carpeta . '/' . $excel);
                if ($datos['tipoTrafico'] === '1') {
                    $hoja = '0';
                } else {
                    $hoja = '1';
                }
                if ($hoja != null) {
                    $totalCeldasC = $this->libroExcel->totalCeldasHoja($hoja, 'C');
                    if ($totalCeldasC > 1) {
                        $equipos = $this->libroExcel->obteniendoArregloExcel($hoja, 'A2:I' . $totalCeldasC);
                        foreach ($equipos as $valor) {
                            $insertar = FALSE;
                            if ($valor['IdTipoEquipo'] == 1) {
                                $idSerie = $this->DBS->consultaGeneralSeguimiento('SELECT '
                                        . 'Serie '
                                        . 'FROM t_traficos_equipo '
                                        . 'WHERE '
                                        . 'IdServicio = "' . $datos['servicio'] . '" '
                                        . 'AND '
                                        . 'Serie = "' . $valor['Serie'] . '"');
                                if (empty($idSerie)) {
                                    $insertar = TRUE;
                                } else {
                                    $sinSerie = strpos($idSerie[0]['Serie'], 'sin serie');
                                    if ($sinSerie !== false) {
                                        $serie = $this->DBS->consultaGeneralSeguimiento('SELECT Serie FROM t_traficos_equipo WHERE Serie LIKE "%sin serie%"');
                                        $ultimaSerie = array_pop($serie);
                                        $numeroConsecutivo = (int) substr($ultimaSerie['Serie'], 10, 1);
                                        $numeroConsecutivo = $numeroConsecutivo + 1;
                                        $valor['Serie'] = 'sin serie ' . $numeroConsecutivo;
                                        $insertar = TRUE;
                                    }
                                }
                                $data = array(
                                    'IdServicio' => $datos['servicio'],
                                    'IdTipoEquipo' => $valor['IdTipoEquipo'],
                                    'IdModelo' => $valor['IdModelo'],
                                    'Serie' => $valor['Serie'],
                                    'Cantidad' => $valor['Cantidad']
                                );
                            } else if ($valor['IdTipoEquipo'] == 5) {
                                $data = array(
                                    'IdServicio' => $datos['servicio'],
                                    'IdTipoEquipo' => $valor['IdTipoEquipo'],
                                    'IdModelo' => $valor['IdModelo'],
                                    'Cantidad' => $valor['Cantidad']
                                );
                                $insertar = TRUE;
                            } else {
                                $data = array(
                                    'IdServicio' => $datos['servicio'],
                                    'IdTipoEquipo' => $valor['IdTipoEquipo'],
                                    'DescripcionOtros' => $valor['DescripcionOtros'],
                                    'Cantidad' => $valor['Cantidad']
                                );
                                $insertar = TRUE;
                            }
                            if ($insertar) {
                                $consulta = $this->DBS->insertarSeguimiento('t_traficos_equipo', $data);
                            }
                        }

                        if (gettype($consulta) === 'integer') {
                            $listaEquiposActualizada = $this->DBS->consultaGeneralSeguimiento('select	
                                                                                    (select Nombre from cat_v3_equipos_sae where Id = tte.IdTipoEquipo) as Tipo,
                                                                                        if (tte.IdTipoEquipo <> 4, (select concat(Clave, " - ", Nombre ) from cat_v3_equipos_sae where Id = tte.IdModelo), tte.DescripcionOtros) as Nombre,
                                                                                        tte.Serie,
                                                                                        tte.Cantidad,
                                                                                        tte.IdTipoEquipo,
                                                                                        tte.IdModelo
                                                                                    from t_traficos_equipo tte 
                                                                                    where IdServicio = ' . $datos['servicio']
                            );
                            return $listaEquiposActualizada;
                        } else {
                            return FALSE;
                        }
                    } else {
                        return 'sinDatos';
                    }
                } else {
                    return 'seleccionarTipo';
                }
            } else {
                return 'formatoErroneo';
            }
        }
    }

    //Encargado de generar un destino nuevo
    public function setNuevoDestinoDistribucion(array $datos) {

        $usuario = $this->Usuario->getDatosUsuario();
        $fechaCaptura = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $datosDestino = null;

        if ($datos['tipoDestino'] !== '3') {
            $datosDestino = array(
                'IdServicio' => $datos['servicio'],
                'IdUsuarioCaptura' => $usuario['Id'],
                'IdEstatus' => '2',
                'FechaCaptura' => $fechaCaptura,
                'IdTipoDestino' => $datos['tipoDestino'],
                'IdDestino' => $datos['destino']
            );
        } else {
            $datosDestino = array(
                'IdServicio' => $datos['servicio'],
                'IdUsuarioCaptura' => $usuario['Id'],
                'IdEstatus' => '2',
                'FechaCaptura' => $fechaCaptura,
                'IdTipoDestino' => $datos['tipoDestino'],
                'DestinoDireccion' => $datos['destino']
            );
        }

        $Id = $this->DBS->insertarSeguimiento('t_traficos_distribuciones', $datosDestino);

        return array('identificadorDestino' => $Id, 'listaDestinos' => $this->DBST->obtenerListaEnviosDistribucion($datos['servicio']));
    }

    //Encargado de insertar el material del destino de distribucion
    public function setMaterialDestinoDistribucion(array $datos) {

        $datosMaterial = null;

        $this->limpiarRegistrosMaterialDistribucion($datos['identificadorDestino']);

        foreach ($datos['material'] as $value) {

            if ($value['tipoEquipo'] === '4') {
                $datosMaterial = array(
                    'IdDestino' => $datos['identificadorDestino'],
                    'IdTipoEquipo' => $value['tipoEquipo'],
                    'DescripcionOtros' => $value['material'],
                    'Cantidad' => $value['cantidad']
                );
            } else {
                $datosMaterial = array(
                    'IdDestino' => $datos['identificadorDestino'],
                    'IdTipoEquipo' => $value['tipoEquipo'],
                    'IdModelo' => $value['modelo'],
                    'Serie' => trim($value['serie']),
                    'Cantidad' => $value['cantidad']
                );
            }

            $this->DBS->insertarSeguimiento('t_traficos_equipo_x_destino', $datosMaterial);
        }

        return $this->DBST->obtenerEquiposFaltantesDistribuciones($datos['servicio']);
    }

    //Evento que sirve para limpiar los equipos de un destino de distribucion
    private function limpiarRegistrosMaterialDistribucion(string $IdDestino) {
        $this->DBS->eliminarDatos('t_traficos_equipo_x_destino', array('IdDestino' => $IdDestino));
    }

    //Encargado de obtener la informacion del destino de un servicio de distribucion
    public function obtenerInformacionDestinoDistribucion(array $datos) {

        $informacion = array();

        $informacionDestino = $this->DBS->consultaGeneralSeguimiento('select * from t_traficos_distribuciones where Id = ' . $datos['destino']);

        if (!empty($informacionDestino)) {
            foreach ($informacionDestino as $dato) {
                array_push($informacion, array(
                    'tipoEnvio' => $dato['IdTipoEnvio'],
                    'paqueteria' => $dato['IdPaqueteria'],
                    'fechaEnvio' => $dato['FechaEnvio'],
                    'guia' => $dato['Guia'],
                    'comentarioEnvio' => $dato['ComentariosEnvio'],
                    'evidenciaEnvio' => explode(',', $dato['UrlEnvio']),
                    'fechaEntrega' => $dato['FechaEntrega'],
                    'nombreRecibe' => $dato['NombreRecibe'],
                    'comentarioEntrega' => $dato['ComentariosEntrega'],
                    'evidenciaEntrega' => explode(',', $dato['UrlEntrega'])
                ));
            }

            return $informacion;
        } else {
            return TRUE;
        }
    }

    //Encargado de concluir  un destino de un servicio de trafico de tipo recoleccion
    public function concluirDestionoServicio(array $datos) {
        $data = array();
        $data['faltaCampo'] = FALSE;
        $respuesta = $this->actualizarEnvio($datos);
        $mensaje = null;

        $datosDestino = $this->DBS->consultaGeneralSeguimiento('select * from t_traficos_distribuciones where Id =' . $datos['destino']);

        if (!empty($datosDestino)) {
            foreach ($datosDestino as $dato) {

                if ($dato['IdTipoDestino'] === '' || $dato['IdTipoDestino'] === NULL) {
                    $data['faltaCampo'] = TRUE;
                } else if ($dato['IdTipoDestino'] === '1' || $dato['IdTipoDestino'] === '2') {
                    if ($dato['IdDestino'] === '' || $dato['IdDestino'] === NULL) {
                        $data['faltaCampo'] = TRUE;
                    }
                } else if ($dato['IdTipoDestino'] === '3') {
                    if ($dato['DestinoDireccion'] === '' || $dato['DestinoDireccion'] === NULL) {
                        $data['faltaCampo'] = TRUE;
                    }
                }

                if ($dato['FechaEnvio'] === '0000-00-00 00:00:00' || $dato['FechaEnvio'] === NULL) {
                    $data['faltaCampo'] = TRUE;
                }

                if ($dato['IdTipoEnvio'] === '' || $dato['IdTipoEnvio'] === NULL) {
                    $data['faltaCampo'] = TRUE;
                } else if ($dato['IdTipoEnvio'] === '1') {

                    if ($dato['FechaEntrega'] === '0000-00-00 00:00:00' || $dato['FechaEntrega'] === NULL) {
                        $data['faltaCampo'] = TRUE;
                    }

                    if ($dato['NombreRecibe'] === '' || $dato['NombreRecibe'] === NULL) {
                        $data['faltaCampo'] = TRUE;
                    }

                    if ($dato['ComentariosEntrega'] === '' || $dato['ComentariosEntrega'] === NULL) {
                        $data['faltaCampo'] = TRUE;
                    }

                    if ($dato['UrlEntrega'] === '' || $dato['UrlEntrega'] === NULL) {
                        $mensaje = 'No se agregado ninguna evidencia en la sección de Entrega. Recuerda que para subir una evidencia es necesario subirlo con el boton Subir Archivo.';
                        $data['faltaCampo'] = TRUE;
                    }
                } else if ($dato['IdTipoEnvio'] === '2' || $dato['IdTipoEnvio'] === '3') {

                    if ($dato['IdPaqueteria'] === '' || $dato['IdPaqueteria'] === NULL) {
                        $data['faltaCampo'] = TRUE;
                    }

                    if ($dato['Guia'] === '' || $dato['Guia'] === NULL) {
                        $data['faltaCampo'] = TRUE;
                    }

                    if ($dato['ComentariosEnvio'] === '' || $dato['ComentariosEnvio'] === NULL) {
                        $data['faltaCampo'] = TRUE;
                    }

                    if ($dato['UrlEnvio'] === '' || $dato['UrlEnvio'] === NULL) {
                        $mensaje = 'No se agregado ninguna evidencia en la seccion de Envio. Recuerda que para subir una evidencia es necesario subirlo con el boton Subir Archivo.';
                        $data['faltaCampo'] = TRUE;
                    }

                    if ($dato['FechaEntrega'] === '0000-00-00 00:00:00' || $dato['FechaEntrega'] === NULL) {
                        $data['faltaCampo'] = TRUE;
                    }

                    if ($dato['NombreRecibe'] === '' || $dato['NombreRecibe'] === NULL) {
                        $data['faltaCampo'] = TRUE;
                    }

                    if ($dato['ComentariosEntrega'] === '' || $dato['ComentariosEntrega'] === NULL) {
                        $data['faltaCampo'] = TRUE;
                    }

                    if ($dato['UrlEntrega'] === '' || $dato['UrlEntrega'] === NULL) {
                        $mensaje = 'No se agregado ninguna evidencia en la sección de Entrega. Recuerda que para subir una evidencia es necesario subirlo con el boton Subir Archivo.';
                        $data['faltaCampo'] = TRUE;
                    }
                }
            }

            if (!$data['faltaCampo']) {
                $actualizacion = $this->DBS->actualizarSeguimiento('t_traficos_distribuciones', array('IdEstatus' => '4'), array('Id' => $datos['destino']));
                if (!empty($actualizacion)) {
                    $data['listaDestinos'] = $this->DBST->obtenerListaEnviosDistribucion($datos['servicio']);
                    return $data;
                } else {
                    $data['mensaje'] = 'No se puedo concluir el destino, favor de volver a intentarlo.';
                    return $data;
                }
            } else {
                if (empty($mensaje)) {
                    $data['mensaje'] = 'Faltan campos por llenar, favor de validar que todos los campos obligatorios de los formularios esten llenos.';
                } else {
                    $data['mensaje'] = $mensaje;
                }
                return $data;
            }
        }
    }

    //Encargado de cancelar un destino de un servicio de trafico de tipo recoleccion
    public function cancelarDestinoDistribucion(array $datos) {
        $data = array();

        $this->DBS->eliminar('t_traficos_equipo_x_destino', array('IdDestino' => $datos['destino']));
        $this->DBS->actualizarSeguimiento('t_traficos_distribuciones', array('IdEstatus' => '6'), array('Id' => $datos['destino']));
        $data['listaDestinos'] = $this->DBST->obtenerListaEnviosDistribucion($datos['servicio']);
        return $data;
    }

    //Encargado de guardar la recoleccion de una distribucion
    public function setRecoleccionDistribucion(array $datos) {
        $recoleccionInsertada = $this->actualizarTraficoRecoleccion($datos);
        if ($recoleccionInsertada) {
            $this->setEvidencia($datos);
            return $this->DBST->getDatosRecoleccion($datos['servicio']);
        } else {
            return FALSE;
        }
    }

}
