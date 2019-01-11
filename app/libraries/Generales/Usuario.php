<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Catalogo
 *
 * @author AProgrammer
 */
class Usuario extends General {

    private $DBU;
    private $Catalogo;
    private $Notificacion;
    private $Correo;
    private $Solicitud;

    public function __construct() {
        parent::__construct();
        $this->DBU = \Modelos\Modelo_Usuario::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        $this->Notificacion = \Librerias\Generales\Notificacion::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->Solicitud = \Librerias\Generales\Solicitud::factory();
        parent::getCI()->load->helper('date');
        parent::getCI()->load->helper('conversionpalabra');
        parent::getCI()->load->helper(array('FileUpload'));
    }

    /*
     * Encargado de mandar la la informacion para la para la inserccion a las tablas cat_v3_usuarios y t_hr_personal
     * 
     * @return array inserta todos los datos
     */

    public function AltaPersonal(string $operacion, array $datos = null, array $where = null) {
        //var_dump($datos);
        $archivos = null;
        $CI = parent::getCI();
        //Inserta en la tabla
        if ($operacion === '1') {
            $nombres = conversionPalabra($datos['nombre']);
            $apellidoPaterno = conversionPalabra($datos['paterno']);
            $apellidoMaterno = conversionPalabra($datos['materno']);
            $nombreCompleto = $nombres . " " . $apellidoPaterno . " " . $apellidoMaterno;
            date_default_timezone_set('America/Mexico_City');
            $horaCaptura = mdate('%H:%i:%s', now('America/Mexico_City'));
            $fechaCaptura = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
            $password = $this->generarPassword();
            $usuario = substr($apellidoPaterno, 0, 2) . substr($apellidoMaterno, 0, 2) . substr($nombres, 0, 4);
            $varificarCorreo = $this->DBU->verficarDatoRepetido('cat_v3_usuarios', array('Email' => $datos['email']));
            $varificarCorreoCorporativo = $this->DBU->verficarDatoRepetido('cat_v3_usuarios', array('EmailCorporativo' => $datos['email']));
            if ($varificarCorreo === false) {
                return 'correo';
            } else if ($varificarCorreoCorporativo === false) {
                return 'correoCorporativo';
            }
            $verificarUsuario = $this->DBU->verficarDatoRepetido('cat_v3_usuarios', array('Usuario' => $usuario));
            if ($verificarUsuario === false) {
                $usuario = $usuario . chr(rand(ord("a"), ord("m")));
            }
            $idPersonal = $this->DBU->setAltaPersonal('cat_v3_usuarios', 't_rh_personal', array(
                'Usuario' => strtoupper($usuario),
                'Password' => md5($password),
                'IdPerfil' => $datos['perfil'],
                'Nombre' => $datos['nombre'],
                'Email' => trim($datos['email']),
                'IdJefe' => $datos['idJefe'],
                'Flag' => '1'
                    ), array(
                'ApPaterno' => $apellidoPaterno,
                'ApMaterno' => $apellidoMaterno,
                'Nombres' => $nombres,
                'Tel1' => $datos['movil'],
                'Tel2' => $datos['fijo'],
                'FechaNacimiento' => $datos['fechaNacimiento'],
                'CURP' => $datos['curp'],
                'RFC' => $datos['rfc'],
                'FechaAlta' => $datos['fechaIngreso'],
                'NSS' => $datos['noSeguroSocial'],
                'FechaCaptura' => $fechaCaptura,
                'HoraCaptura' => $horaCaptura
                    ),
                    //string para guardar el IdUsuario en la tabla t_rh_personal
                    'IdUsuario'
            );
            if (!empty($_FILES)) {
                $carpeta = 'fotoPersonal/' . $idPersonal . '/';
                $archivos = setMultiplesArchivos($CI, 'fotoPersonal', $carpeta);
                if ($archivos) {
                    $archivos = implode(',', $archivos);
                    $this->DBU->insertarFoto(array(
                        'UrlFoto' => $archivos
                            ), array('Id' => $idPersonal)
                    );
                }
            }
            $_FILES = null;
            $this->mandarNotificacionSolicitud($nombreCompleto);
            $titulo = 'Se creó tu cuenta en AdIST';
            $texto = '<b>Hola ' . $nombres . ' ' . $apellidoPaterno . ',</b><br><br>Tu usuario es: <b>' . strtoupper($usuario) . '</b><br>Tu password es: <b>' . $password . '</b><br><br>Haz click <a title="Siccob Solitions" href="http://siccob.solutions/">aqui</a>&nbsp;para acceder al sistema.';
            $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
            $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($datos['email']), $titulo, $mensaje);
            return $this->AltaPersonal('3');
        }
        //Actualiza en la tabla resumen personal
        elseif ($operacion === '2') {
            $varificarCorreo = $this->DBU->getPersonal('SELECT Id FROM cat_v3_usuarios WHERE Email = "' . $datos['email'] . '" AND Id <> "' . $datos['id'] . '"');
            $existeCorreoCorporativo = $this->DBU->getPersonal('SELECT Id FROM cat_v3_usuarios WHERE EmailCorporativo = "' . $datos['email'] . '" AND Id <> "' . $datos['id'] . '"');
            if (empty($varificarCorreo)) {
                if (empty($existeCorreoCorporativo)) {
                    $consulta = $this->DBU->ActualizarPersonal('t_rh_personal', array(
                        'ApPaterno' => $datos['paterno'],
                        'ApMaterno' => $datos['materno'],
                        'Nombres' => $datos['nombre'],
                        'FechaNacimiento' => $datos['fechaNacimiento'],
                        'Tel1' => $datos['movil'],
                        'Tel2' => $datos['fijo'],
                        'CURP' => $datos['curp'],
                        'NSS' => $datos['noSeguroSocial'],
                        'RFC' => $datos['rfc'],
                        'FechaAlta' => $datos['fechaNacimiento'],
                            ), array(
                        'Id' => $datos['id']
                    ));
                    $consulta = $this->DBU->ActualizarPersonal('cat_v3_usuarios', array(
                        'IdPerfil' => $datos['idPerfil'],
                        'Nombre' => $datos['nombre'],
                        'Email' => trim($datos['email']),
                        'IdJefe' => $datos['idJefe'],
                            ), array(
                        'Id' => $datos['usuario']
                    ));
                    if (!empty($_FILES)) {
                        $carpeta = 'fotoPersonal/' . $datos['id'] . '/';
                        $archivos = setMultiplesArchivos($CI, 'fotoActualizarPersonal', $carpeta);
                        if ($archivos) {
                            $archivos = implode(',', $archivos);
                            $this->DBU->insertarFoto(array(
                                'UrlFoto' => $archivos
                                    ), array('Id' => $datos['id'])
                            );
                        }
                    }
                    return $this->AltaPersonal('3');
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
        //Obtiene Informacion 
        elseif ($operacion === '3') {
            return $this->DBU->getPersonal('SELECT a.*, b.Email, c.Nombre as Perfil, d.Id AS IdDepartamento, d.Nombre AS Departamento, e.Id AS IdArea, e.Nombre AS Area FROM t_rh_personal a INNER JOIN cat_v3_usuarios b ON b.Id = a.IdUsuario INNER JOIN cat_perfiles c ON b.IdPerfil = c.Id INNER JOIN cat_v3_departamentos_siccob d ON c.IdDepartamento = d.Id INNER JOIN adist3_prod.cat_v3_areas_siccob e ON d.IdArea = e.Id');
        }
        //Obtiene datos para mandar al modal Actualizar Personal 
        elseif ($operacion === '4') {
            $data = array();
            $data['areas'] = $this->Catalogo->catAreas('3', array('Flag' => '1'));
            $data['departamentos'] = $this->Catalogo->catDepartamentos('3', array('Flag' => '1'));
            $data['perfiles'] = $this->Catalogo->catPerfiles('3');
            $data['infocatV3Usuarios'] = $this->Catalogo->catConsultaGeneral('select Id,nombreUsuario(Id)as Nombre from cat_v3_usuarios where Flag = 1 AND Id > 1 ORDER BY Nombre ASC');
            $data['paises'] = $this->Catalogo->catLocalidades('1');
            $data['estadoCivil'] = $this->Catalogo->catRhEdoCivil('3', array('Flag' => '1'));
            $data['sexo'] = $this->Catalogo->catRhSexo('3', array('Flag' => '1'));
            $data['nivelEstudio'] = $this->Catalogo->catRhNivelEstudio('3', array('Flag' => '1'));
            $data['documentosEstudio'] = $this->Catalogo->catRhDocumentosEstudio('3', array('Flag' => '1'));
            $data['habilidadesIdioma'] = $this->Catalogo->catRhHabilidadesIdioma('3', array('Flag' => '1'));
            $data['habilidadesSoftware'] = $this->Catalogo->catRhHabilidadesSoftware('3', array('Flag' => '1'));
            $data['nivelHabilidades'] = $this->Catalogo->catRhNivelHabilidad('3', array('Flag' => '1'));
            $data['habilidadesSistema'] = $this->Catalogo->catRhHabilidadesSistema('3', array('Flag' => '1'));

            if (!empty($datos['id'])) {
                $data['idArea'] = $this->Catalogo->catConsultaGeneral('SELECT e.Id as Area FROM t_rh_personal a INNER JOIN cat_v3_usuarios b ON b.Id = a.IdUsuario INNER JOIN cat_perfiles c ON b.IdPerfil = c.Id INNER JOIN cat_v3_departamentos_siccob d on c.IdDepartamento = d.Id INNER JOIN cat_v3_areas_siccob e ON d.IdArea = e.Id where a.id = ' . $datos['id']);
                $data['idDepartamento'] = $this->Catalogo->catConsultaGeneral('SELECT d.Id FROM t_rh_personal a INNER JOIN cat_v3_usuarios b ON b.Id = a.IdUsuario INNER JOIN cat_perfiles c ON b.IdPerfil = c.Id INNER JOIN cat_v3_departamentos_siccob d ON c.IdDepartamento = d.Id INNER JOIN cat_v3_areas_siccob e ON d.IdArea = e.Id where a.id = ' . $datos['id']);
                $data['idPerfil'] = $this->Catalogo->catConsultaGeneral('SELECT b.IdPerfil FROM t_rh_personal a INNER JOIN cat_v3_usuarios b ON b.Id = a.IdUsuario INNER JOIN cat_perfiles c ON b.IdPerfil = c.Id INNER JOIN cat_v3_departamentos_siccob d ON c.IdDepartamento = d.Id INNER JOIN cat_v3_areas_siccob e ON d.IdArea = e.Id where a.id = ' . $datos['id']);
                $data['urlFoto'] = $this->DBU->getPersonal('SELECT UrlFoto FROM t_rh_personal WHERE id = ' . $datos['id']);
                $data['consultaV3Usuarios'] = $this->DBU->getPersonal('SELECT IdJefe FROM cat_v3_usuarios WHERE id = ' . $datos['id']);
            }
            return array('formulario' => parent::getCI()->load->view('RH/Modal/Alta_Personal', $data, TRUE), 'datos' => $data);
        }
    }

    /*
     * Encargada de eliminar el evidencias de una solicitud. Esto se realiza atravez del plugin fileupload.
     * Donde se actualiza la solicitud una vez eliminada la evidencia.
     * 
     * @param array $evidencias Recibe el id de la solicitud y key del nombre del archivo
     * @return boolean Regresa true si se elimino y false en caso contrario.
     */

    public function eliminarFoto(array $datos) {
        $consulta = $this->DBU->actualizarPersonal(
                't_rh_personal', array('UrlFoto' => ''), array('Id' => $datos['id'])
        );
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Genera una password aleatorio que empieza con mayuscula, que contiene un guin y numeros 
     */

    private function generarPassword() {
        $minusculas = "abcdefghijklmnopqrstuvwxyz";
        $mayusculas = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numeros = "1234567890";
        $may = "";
        $min = "";
        $num = "";
        for ($i = 0; $i < 11; $i++) {
            $min .= substr($minusculas, rand(0, 26), 1);
            $may .= substr($mayusculas, rand(0, 26), 1);
            $num .= substr($numeros, rand(0, 10), 1);
        }
        $password = substr($may, 0, 2) . substr($min, 0, 5) . '-' . substr($num, 0, 3);
        return $password;
    }

    /*
     * mandar datos al objeto solicitud para la notificacion
     */

    private function mandarNotificacionSolicitud(string $nombre) {
        $datos = array();
        $datos['departamento'] = '19';
        $datos['tipo'] = '4';
        $datos['descripcion'] = $nombre . ' es miembro de SICCOB por lo cual se solicita creación de un su correo corporativo.';
        $datos['prioridad'] = '1';
        $datos['asunto'] = $nombre . ' es miembro de SICCOB por lo cual se solicita creación de un su correo corporativo.';

        $this->Solicitud->solicitudNueva($datos);
    }

    public function mostrarFormularioPerfilUsuario(array $datos) {
        $data = array();

        $data['campo'] = $datos['campo'];
        $data['input'] = $datos['input'];
        $data['nombreInput'] = $datos['nombreInput'];


        switch ($data['campo']) {
            case 'Tel1':
                $data['placeholder'] = '0445555555555';
                break;
            case 'Tel2':
                $data['placeholder'] = '015555555555';
                break;
            default:
                $data['placeholder'] = '';
        }

        if ($data['campo'] !== 'IdSexo') {
            return ['modal' => parent::getCI()->load->view('Configuracion/Modal/EditarPerfilUsuario.php', $data, TRUE)];
        } else {
            $html = '<div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600">Genero:</label>
                                <select id="selectPerfilGenero" class="form-control" style="width: 100% !important;">
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Femenino</option>
                                    <option value="2">Masculino</option>
                                </select>        
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div class="errorPerfilUsuario"></div>
                        </div>
                    </div>';
            return ['modal' => $html];
        }
    }

    public function actualizarPerfilUsuario(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $datosActualizar = array(
            'inputNuevo' => $datos['inputNuevo'],
            'campo' => $datos['campo'],
            'id' => $usuario['Id']
        );

        if ($datos['tabla'] === 'personal') {
            $consulta = $this->DBU->actualizarCampoTRHPersonal($datosActualizar);
        } else {
            $consulta = $this->DBU->actualizarCampoUsuario($datosActualizar);
        }

        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function mostrarFormularioCambiarFoto(array $datos) {
        $data = array();

        $html = '<div class="row">
                    <div class="col-md-12">                                    
                        <div class="form-group">
                            <label id="divArchivos">Foto *</label>
                            <input id="fotoUsuario"  name="fotoUsuario[]" type="file" multiple/>
                        </div>
                    </div>
                </div>
                <div class="row m-t-10">
                        <div class="col-md-12">
                            <div id="errorFotoUsuario"></div>
                        </div>
                </div>';
        return ['modal' => $html];
    }

    public function actualizarFotoUsuario(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $archivos = null;
        $CI = parent::getCI();
        $carpeta = 'fotoPersonal/' . $usuario['Id'] . '/';
        $archivos = setMultiplesArchivos($CI, 'fotoUsuario', $carpeta);

        if ($archivos) {
            $archivos = implode(',', $archivos);
            $this->DBU->insertarFoto(array(
                'UrlFoto' => $archivos
                    ), array('Id' => $usuario['Id'])
            );

            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function mostrarFormularioActualizarPasswordUsuario(array $datos) {
        $data = array();

        $html = '<form class="margin-bottom-0" id="formActualizarPassword" data-parsley-validate="true">
                    <div class="checkbox m-b-15"></div>
                    <div class="row m-b-15">
                        <div class="col-md-12">
                            <label id="divArchivos">Nuevo Password *</label>
                            <input type="password" class="form-control" placeholder="Nuevo Password" id="inputNuevoPsw" data-parsley-required="true" data-parsley-minlength="8" data-parsley-maxlength="15"/>
                        </div>
                    </div>
                    <div class="row m-b-15">
                        <div class="col-md-12">
                            <label id="divArchivos">Confirmar Password *</label>
                            <input type="password" class="form-control" placeholder="Confirmar Password" id="inputConfirmaNuevoPsw" data-parsley-required="true" data-parsley-equalto="#inputNuevoPsw"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="checkbox" id="alertRecuperar">
                                <!--muestra el mensaje de error para recuperar contraseña-->
                                <label class="alert hidden" role="alert"></label>
                            </div>
                        </div>
                    </div>
                    <!--Empezando mensaje-->
                    <div class="row">
                        <div class="col-md-120">
                            <div class="alert alert-warning fade in m-b-15">                            
                                Para definir password debe cumplir con los siguientes puntos:
                                <ul>
                                    <li>una mayúscula</li>
                                    <li>una minúscula</li>
                                    <li>un número</li>
                                    <li>la longitud mínima 8 y máxima 15</li>
                                </ul>                          
                            </div>                        
                        </div>
                    </div>
                    <!--Finalizando mensaje-->
                </form>
                <div class="row m-t-10">
                    <div class="col-md-12">
                        <div id="errorPasswordUsuario"></div>
                    </div>
                </div>';
        return ['modal' => $html];
    }

    public function actualizarTokenUsuario(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $i = 1;

        while ($i < 1000) {
            $token = bin2hex(random_bytes(64));
            $token = substr($token, 1, 32);
            $token = strtoupper($token);

            $verificarToken = $this->DBU->consultaTokenUsuarios(array('token' => $token));

            if (empty($verificarToken)) {
                $consulta = $this->DBU->actualizarCampoUsuario(array(
                    'campo' => 'Token',
                    'inputNuevo' => $token,
                    'id' => $usuario['Id']
                ));

                $i = 1000;
                return TRUE;
            }
            $i++;
        }
    }

}
