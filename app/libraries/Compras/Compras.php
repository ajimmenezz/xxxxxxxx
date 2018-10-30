<?php

namespace Librerias\Compras;

use Controladores\Controller_Base_General as General;

class Compras extends General {

    private $catalogo;
    private $gapsi;
    private $DBSAE;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->gapsi = \Librerias\Gapsi\Catalogos::factory();
        $this->DBSAE = \Modelos\Modelo_SAE7::factory();
        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioOrdenCompra() {
        $data = array();
        $ultimaClaveDocumentacion = $this->DBSAE->consultaUltimaClaveDocumentacion();
        $ceros = $this->ceros(trim($ultimaClaveDocumentacion[0]['CVE_DOC']));
        $suma = trim($ultimaClaveDocumentacion[0]['CVE_DOC']) + 1;
        $stringClaveNueva = $this->generateRandomString($ceros, $suma);

        $data['claveNuevaDocumentacion'] = $stringClaveNueva;
        $data['proveedores'] = $this->DBSAE->consultaProveedoresSAE();
        $data['almacenes'] = $this->DBSAE->consultaAlmacenesSAE();
        $data['productos'] = $this->DBSAE->consultaProductosSAE();
        $data['clientes'] = $this->gapsi->getClientes();
        $data['tiposServicio'] = $this->gapsi->getTiposServicio();

        return array('formulario' => parent::getCI()->load->view('Compras/Formularios/formularioOrdenCompra', $data, TRUE), 'datos' => $data);
    }

    //Obtiene datos para mandar al modal Actualizar Usuario 
    public function mostrarFormularioUsuarios(array $datos) {
        $data = array();
        $data['perfiles'] = $this->catalogo->catPerfiles('3');
        $data['permisos'] = $this->catalogo->catPermisos('3');
        $data['idPerfil'] = $this->catalogo->catConsultaGeneral('SELECT IdPerfil FROM cat_v3_usuarios WHERE Id = \'' . $datos[0] . '\'');
        $data['permiso'] = $this->catalogo->catConsultaGeneral('SELECT PermisosAdicionales FROM cat_v3_usuarios WHERE Id = \'' . $datos[0] . '\'');
        $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_usuarios WHERE Id = \'' . $datos[0] . '\'');
        return array('formulario' => parent::getCI()->load->view('Administrador/Modal/ActualizarUsuario', $data, TRUE), 'datos' => $data);
    }

    public function mostrarDatosProyectosBeneficiarios(array $datos) {
        $data = array();
        $data['sucursales'] = $this->gapsi->sucursalesByProyecto(array('id' => $datos['id']));
        $data['beneficiarios'] = $this->gapsi->beneficiarioByTipo(array('id' => '2', 'proyecto' => $datos['id']));
        return $data;
    }

    public function consultaListaOrdenesCompra() {
        $consulta = $this->DBSAE->consultaListaOrdenesCompra();
        return $consulta;
    }

    public function ceros($cadena) {
        if (preg_match("/0([^0])/", $cadena, $match)) {
            return strpos($cadena, $match[1]);
        } else {
            return 0;
        }
    }

    public function generateRandomString(int $numeroCeros, int $suma) {
        $characters = '0';
        $stringSuma = (string) $suma;
        $stringNumeroCeros = '';
        for ($i = 0; $i < $numeroCeros; $i++) {
            $stringNumeroCeros .= $characters[0];
        }
        
       $resultado = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $stringNumeroCeros . $stringSuma;
        return $resultado;
    }

    public function guardarOrdenCompra(array $datos) {
        var_dump($datos);
        $arraySubtotal = $this->subtotalTablaPartidas($datos['datosTabla']);
        $consulta = $this->DBSAE->guardarOrdenCompra($datos, $arraySubtotal);
    }

    public function subtotalTablaPartidas(array $datos) {
        $subtotal = '0.00';
        foreach ($datos as $key => $value) {
            $subtotal = (double) $subtotal + $value['subtotalPartida'];
        }

        $subtotal = number_format($subtotal, 2, ".", "");
        $iva = number_format($subtotal * 16 / 100, 2, ".", "");

        return array('subtotal' => $subtotal, 'iva' => $iva);
    }

}
