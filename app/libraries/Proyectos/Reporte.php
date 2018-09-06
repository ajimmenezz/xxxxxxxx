<?php

namespace Librerias\Proyectos;

use Librerias\Interfaces\Objeto_General as Objeto_General;
use Librerias\Proyectos\PDF as PDF;

class Reporte extends Objeto_General {

    private $pdf;

    public function __construct() {
        $this->pdf = new PDF();
    }

    public function generarElementos() {
        
    }

    public function reporteMaterialProyecto(array $datos) {
        $this->pdf->AddPage();
        $this->pdf->titulo('Solicitud de Material');
        $this->pdf->parrafo('Proyecto: ' . ucfirst(strtolower($datos['Complejo'])));
        $this->pdf->parrafo('Plaza: ' . ucfirst(strtolower($datos['Complejo'])));
        $this->pdf->parrafo('Ciudad/Estado: ' . ucfirst($datos['Direccion']['Municipio']) . ', '
                . ucfirst(strtolower($datos['Direccion']['Estado'])));
        $this->pdf->parrafo('Fecha de inicio: ' . $datos['Inicio']);
        $this->pdf->parrafo('Dirección: ' . ucfirst(strtolower($datos['Direccion']['Calle'])) . ', '
                . ucfirst(strtolower($datos['Direccion']['Colonia'])) . ', '
                . ucfirst(strtolower($datos['Direccion']['Estado'])) . ', '
                . ucfirst(strtolower($datos['Direccion']['Pais'])));
        $this->pdf->parrafo('Teléfono: ' . $datos['Direccion']['Telefono']);
        $this->pdf->subTitulo('Material');
        $this->pdf->parrafo('Se requiere el siguiente material : ');
        $this->pdf->table(array('Material', 'Numero de Parte', 'Total'), $datos['Material']);
        $this->pdf->parrafo('Sin más quedo a sus ordenes, indicándoles que ya el'
                . ' proyecto esta levantado en el sistema Adist3, Gracias.');
        $this->pdf->firma('Ing. Victor Ricardo Mojica Leines', 'Gerente de Operaciones');
        $carpeta = $this->pdf->definirArchivo($datos['carpeta'], 'reporteMaterial');
        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);
        return $carpeta;
    }

    public function reporteInicioProyecto(array $datos) {
        $lideres = '**********';
        $this->pdf->AddPage();
        $this->pdf->titulo('Inicio de Proyecto');
        $this->pdf->parrafo('Plaza: ' . ucfirst(strtolower($datos['Complejo'])));
        $this->pdf->parrafo('Ciudad/Estado: ' . ucfirst($datos['Direccion']['Municipio']) . ', '
                . ucfirst(strtolower($datos['Direccion']['Estado'])));
        $this->pdf->parrafo('Alcance: Voz y Datos / Proyección y Canalizaciones Especiales');
        $this->pdf->parrafo('Fecha de inicio: ' . $datos['Inicio']);
        $this->pdf->parrafo('Apertura: ' . $datos['Inicio']);
        $this->pdf->parrafo('Personal Solicitado: ' . $datos['TotalPersonal'] . ' Personas de planta');
        $this->pdf->parrafo('Dirección: ' . ucfirst(strtolower($datos['Direccion']['Calle'])) . ', '
                . ucfirst(strtolower($datos['Direccion']['Colonia'])) . ', '
                . ucfirst(strtolower($datos['Direccion']['Estado'])) . ', '
                . ucfirst(strtolower($datos['Direccion']['Pais'])));
        $this->pdf->subTitulo('Equipo Solicitado Personal');
        $this->pdf->parrafo('Botas, Chaleco, Casco, Identificación de la empresa, '
                . 'kit básico de herramientas incluyendo Lámparas de Minero, '
                . 'arnés de seguridad,línea de vida. Todo esto por integrante del equipo.');
        $this->pdf->subTitulo('Equipo adicional para la obra');
        $this->pdf->parrafo('1 juegos de andamios, de 6 cuerpos, con niveladores y '
                . 'ruedas, escaleras de 10 y 8 peldaños de tijera, Taladro y '
                . 'brocas, Sacabocados, Favor de contemplar los consumibles que '
                . 'se requieren por obra, validándolos con el líder.');
        $this->pdf->subTitulo('Personal en la obra');
        if (count($datos['TotalPersonal']) > 1) {
            $lideres = '';
            foreach ($datos['Lideres'] as $value) {
                $lideres .= $value;
                $lideres .= ', ';
            }
        } else if (count($datos['TotalPersonal']) === 1){
            $lideres = '';
            $lideres = $datos['Lideres'][0];
        }
        
        $this->pdf->parrafo('Esta obra contara con ' . $datos['TotalPersonal'] . ' participantes, Siendo el '
                . 'encargado o Líder del proyecto "' . $lideres . '" y estará '
                . 'acompañado de '. count($datos['Tecnicos']).' técnico(s).');
        $this->pdf->subTitulo('Hospedaje');
        $this->pdf->parrafo('Favor de buscar un hotel en la zona para "' . $lideres . '" y el '
                . 'técnico(s) que le acompañara, por favor me ayudan elaborando '
                . 'el presupuesto, favor de hacer sondeo en la zona para '
                . 'determinar si tenemos hoteles con el presupuesto establecido'
                . ' para este rubro.');
        $this->pdf->subTitulo('Material de Redes');
        $this->pdf->parrafo('Se envió ya la solicitud del material necesario, en este'
                . ' momento requerimos que cuando menos las bobinas de cable se '
                . 'encuentren en la plaza, "' . $lideres . '" se encarga '
                . 'de la recepción.');
        $this->pdf->AddPage();
        $this->pdf->subTitulo('Apoyos necesarios');
        $this->pdf->parrafo('Para el correcto desarrollo de este proyecto '
                . 'solicito el apoyo de las diferentes áreas para conocer lo '
                . 'siguiente:');
        $this->pdf->lista(array(
            'Estado Actual de las Compras de materiales.',
            'Disponibilidad de herramental solicitado y/o estado de la compra',
            'Elaboración del presupuesto que ejerceremos en Obra.',
            'Programa de envíos de material a la obra.'));
        $this->pdf->parrafo('Sin más quedo a sus ordenes, indicándoles que ya el'
                . ' proyecto esta levantado en el sistema Adist3 y Base Camp, solicitándoles su '
                . 'seguimiento y participación en el mismo, Gracias.');
        $this->pdf->firma('Ing. Victor Ricardo Mojica Leines', 'Gerente de Operaciones');
        $carpeta = $this->pdf->definirArchivo($datos['carpeta'], 'reporteInicioProyecto');
        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);
        return $carpeta;
    }

}
