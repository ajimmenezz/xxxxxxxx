<?php

namespace Librerias\WebServices;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Generales\PDF as PDF;

/**
 * Description of ServiceDesck
 *
 * @author Alonso
 */
class Cimos extends General {

    private $Url;
    private $Excel;
    private $pdf;
    private $DB;

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', 300);
        $this->Excel = new \Librerias\Generales\CExcel();
        $this->pdf = new PDF();
        $this->DB = new \Modelos\Modelo_CIMOS();
    }

    /*
     * Encargado de obtener todos lo folios asiganados al tecnico
     * 
     */

    public function getReporteCimos(array $data) {
        if (isset($data['id'])) {
            switch ($data['id']) {
                case 1:
                    return $this->getReporteSuscripciones();
                    break;
                case 2:
                    return $this->getReporteGastos();
                    break;
                default:
                    break;
            }
        }
    }

    public function getReporteCimosExcel(array $data) {
        if (isset($data['id'])) {
            switch ($data['id']) {
                case 1:
                    return $this->getReporteSuscripcionesExcel();
                    break;
                case 2:
                    return $this->getReporteGastosExcel();
                    break;
                default:
                    break;
            }
        }
    }

    private function getReporteSuscripciones() {
        $data = [
            'contratos' => $this->getFullSuscripciones(),
            'clientes' => $this->getFullClientes(),
            'suscripciones' => $this->getSuscripcionesActivas()
        ];
        return array(
            'html' => parent::getCI()->load->view('Cimos/Modal/ReporteSuscripcionesActivas', $data, TRUE)
        );
    }

    private function getReporteGastos() {
        $data = [
            'gastos' => $this->DB->getGastosCimosEvocal()
        ];
        return array(
            'html' => parent::getCI()->load->view('Cimos/Modal/ReporteGastosCimosEvocal', $data, TRUE)
        );
    }

    private function getFullSuscripciones() {
        $url = 'https://cimos.com.mx/ws/mb/GetFullSubscriptions';

        $opts = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded'
            )
        );

        $context = stream_context_create($opts);
        $result = json_decode(file_get_contents($url, false, $context), true);

        return $result;
    }

    private function getFullClientes() {
        $url = 'https://cimos.com.mx/ws/mb/GetFullClients';

        $opts = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded'
            )
        );

        $context = stream_context_create($opts);
        $result = json_decode(file_get_contents($url, false, $context), true);

        return $result;
    }

    private function getSuscripcionesActivas() {
        $url = 'https://cimos.com.mx/ws/OP/getFullSubscriptions';

        $opts = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded'
            )
        );

        $context = stream_context_create($opts);
        $result = json_decode(file_get_contents($url, false, $context), true);

        return $result;
    }

    public function getReporteSuscripcionesExcel() {
//        ini_set('memory_limit', '2048M');
//        set_time_limit('1200');        

        $data = [
            'contratos' => $this->getFullSuscripciones()['contracts'],
            'clientes' => $this->getFullClientes()['clientes'],
            'suscripciones' => $this->getSuscripcionesActivas()['GetFullSubscriptions']
        ];

        $suscripciones = [];
        foreach ($data['suscripciones'] as $key => $value) {
            array_push($suscripciones, [
                $value['IdClienteMB'],
                $data['clientes'][$value['IdClienteMB']]['Nombre'],
                $data['contratos'][$value['IdContratoMB']]['Nombre'],
                $data['contratos'][$value['IdContratoMB']]['Item']['Nombre'],
                "$" . number_format($value['CostoMB'], 2, '.', ','),
                "$" . number_format($value['DescuentoMB'], 2, '.', ','),
                "$" . number_format(($value['CostoMB'] - $value['DescuentoMB']), 2, '.', ','),
                $value['Fecha'],
                $value['Ciclo'],
                $value['FechaUltimoCobro'],
                $value['IdCargoOP'],
                $value['FechaVentaMB']
            ]);
        }

        /* Begin Hoja 1 */
        //Crea una hoja en la posición 0 y la nombra.
        $this->Excel->createSheet('Suscripciones', 0);
        //Selecciona la hoja creada y la marca como activa. Todas las modificaciones se harán en está hoja.
        $this->Excel->setActiveSheet(0);
        //Arreglo de los subtitulos de la tabla. LA posición es de izquierda a derecha.
        $arrayTitulos = [
            'ID Cliente',
            'Cliente',
            'Contrato',
            'Paquete Incluido',
            'Precio Real',
            'Descuento',
            'Precio por Contrato',
            'Fecha de Contratación',
            'Mes Actual',
            'Fecha Último Cobro',
            'ID Cargo OpenPay',
            'Fecha Venta en MB'];
        //Envía el arreglo de los subtitulos a la hoja activa.
        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        //Arreglo con el ancho por columna. 
        $arrayWidth = [15, 35, 35, 35, 15, 15, 15, 25, 15, 25, 25, 25];
        //Envía y setea los anchos de las columnas definidos en el arreglo de los anchos por columna.
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        //Setea el titulo de la tabla. Envía la celda de inicio y la final para que se combinen.
        $this->Excel->setTableTitle("A1", "L1", "Suscripciones Activas CIMOS", array('titulo'));
        //Arreglo de alineación por columna.
        $arrayAlign = ['center', '', '', '', 'center', 'center', 'center', 'center', 'center', 'center', '', 'center'];
        //Envía:
        //La letra donde comienza la tabla
        //El número de fila donde comenzará la tabla -1
        //El contenido en forma de arreglo
        //Boleano que define si la tabla llevará autofiltros o no
        //Arreglo con la alineación de las columnas.
        $this->Excel->setTableContent('A', 2, $suscripciones, true, $arrayAlign);
        /* End Hoja 1 */

        $time = date("ymd_H_i_s");
        $nombreArchivo = 'Reporte_Suscripciones_CIMOS_' . $time . '.xlsx';
        $nombreArchivo = trim($nombreArchivo);
        $ruta = 'storage/Archivos/CIMOS/Reportes/Suscripciones/' . $nombreArchivo;

        //Guarda la hoja envíandole la ruta y el nombre del archivo que se va a guardar.
        $this->Excel->saveFile($ruta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ruta];
    }

    public function getReporteGastosExcel() {
//        ini_set('memory_limit', '2048M');
//        set_time_limit('1200');        

        $gastos = $this->DB->getGastosCimosEvocal();


        /* Begin Hoja 1 */
        //Crea una hoja en la posición 0 y la nombra.
        $this->Excel->createSheet('Suscripciones', 0);
        //Selecciona la hoja creada y la marca como activa. Todas las modificaciones se harán en está hoja.
        $this->Excel->setActiveSheet(0);
        //Arreglo de los subtitulos de la tabla. LA posición es de izquierda a derecha.
        $arrayTitulos = [
            'Folio',
            'Fecha',
            'Fecha Captura',
            'Tipo',
            'Tipo Servicio',
            'Proyecto',
            'Sucursal',
            'Cliente',
            'Beneficiario',
            'Tipo Trans',
            'Descripción',
            'Importe',
            'Moneda',
            'Banco',
            'Ref. Bancaria',
            'Empresa',
            'Orden de Compra',
            'Ticket',
            'Autorización'];
        //Envía el arreglo de los subtitulos a la hoja activa.
        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        //Arreglo con el ancho por columna. 
        $arrayWidth = [15, 20, 20, 20, 25, 30, 30, 20, 30, 20, 50, 20, 15, 20, 15, 15, 15, 15, 30];
        //Envía y setea los anchos de las columnas definidos en el arreglo de los anchos por columna.
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        //Setea el titulo de la tabla. Envía la celda de inicio y la final para que se combinen.
        $this->Excel->setTableTitle("A1", "L1", "Gastos Cimos - EVocal", array('titulo'));
        //Arreglo de alineación por columna.
        $arrayAlign = ['center', 'center', 'center', '', '', '', '', '', '', '', 'justify', 'center', 'center', '', 'center', '', 'center', 'center', ''];
        //Envía:
        //La letra donde comienza la tabla
        //El número de fila donde comenzará la tabla -1
        //El contenido en forma de arreglo
        //Boleano que define si la tabla llevará autofiltros o no
        //Arreglo con la alineación de las columnas.
        $this->Excel->setTableContent('A', 2, $gastos, true, $arrayAlign);
        /* End Hoja 1 */

        $time = date("ymd_H_i_s");
        $nombreArchivo = 'Reporte_Gastos_CIMOS_Evocal_' . $time . '.xlsx';
        $nombreArchivo = trim($nombreArchivo);
        $ruta = 'storage/Archivos/CIMOS/Reportes/Gastos/' . $nombreArchivo;

        //Guarda la hoja envíandole la ruta y el nombre del archivo que se va a guardar.
        $this->Excel->saveFile($ruta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ruta];
    }

    public function makeContractPdf(array $datos) {
        $clientes = $this->getFullClientes();
        $this->pdf->AddPage();
        $this->pdf->Image('https://cimos.com.mx/wp-content/uploads/2018/04/CIMOS1-3.png', 10, 15, 45, 0, 'PNG');
        $this->pdf->SetFont("helvetica", "B", 14);
        $this->pdf->Text(83, 16, "CIMOS");
        $this->pdf->SetFont("helvetica", "B", 14);
        $this->pdf->Text(58, 26, "Contrato General del Alumno");

        $this->pdf->SetFont("helvetica", '', 7);
        $this->pdf->Text(130, 14, utf8_decode("Razón Social: SICCOB SOLUTIONS S.A. de C.V."));
        $this->pdf->Text(130, 17, utf8_decode("Nombre Comercial: CIMOS (Centro Integral de Movimiento y Salud"));
        $this->pdf->Text(130, 20, utf8_decode("Sede: Plaza Grand San Francisco, Calz. Desierto de los Leones 5525"));
        $this->pdf->Text(130, 23, utf8_decode("Ciudad de México, C.P. 01729"));
        $this->pdf->Text(130, 26, utf8_decode("Paquete: " . $datos['data'][3]));
        $this->pdf->Text(130, 29, utf8_decode("Fecha de Compra: " . $datos['data'][8]));
        $this->pdf->Text(130, 32, utf8_decode("Folio: C-" . sprintf("%'.011d\n", $datos['data'][0])));

        $this->pdf->Line(3, 35, 207, 35);
        $this->pdf->Line(3, 35, 3, 60);

        $this->pdf->Line(3, 60, 207, 60);
        $this->pdf->Line(207, 35, 207, 60);

        $fecha = ($clientes['clientes'][$datos['data'][1]]['Birtdate'] !== '') ? date('d/m/Y', strtotime(str_replace("T", "", $clientes['clientes'][$datos['data'][1]]['Birtdate']))) : 'Sin Fecha';
        $adress = $clientes['clientes'][$datos['data'][1]]['Adress'];
        $adress = ($adress != '') ? $adress : "Sin información";

        $city = $clientes['clientes'][$datos['data'][1]]['City'];
        $city = ($city != '') ? $city : "Sin información";

        $state = $clientes['clientes'][$datos['data'][1]]['State'];
        $state = ($state != '') ? $state : "Sin información";

        $cp = $clientes['clientes'][$datos['data'][1]]['PostalCode'];
        $cp = ($cp != '') ? $cp : "Sin información";

        $country = $clientes['clientes'][$datos['data'][1]]['Country'];
        $country = ($country != '') ? $country : "Sin información";

        $phone = $clientes['clientes'][$datos['data'][1]]['Phone'];
        $phone = ($phone != '') ? $phone : "Sin información";

        $mail = $clientes['clientes'][$datos['data'][1]]['Mail'];
        $mail = ($mail != '') ? $mail : "Sin información";



        $this->pdf->Text(10, 40, utf8_decode("NOMBRE(S): " . $clientes['clientes'][$datos['data'][1]]['Firstname']));
        $this->pdf->Text(77, 40, utf8_decode("APELLIDO(S): " . $clientes['clientes'][$datos['data'][1]]['Lastname']));
        $this->pdf->Text(147, 40, utf8_decode("FECHA DE NACIMIENTO: " . $fecha));
        $this->pdf->Text(10, 44, utf8_decode("DIRECCIÓN POSTAL: " . $adress));
        $this->pdf->Text(10, 48, utf8_decode("CIUDAD: " . $city));
        $this->pdf->Text(60, 48, utf8_decode("ESTADO: " . $state));
        $this->pdf->Text(110, 48, utf8_decode("CODIGO POSTAL: " . $cp));
        $this->pdf->Text(160, 48, utf8_decode("PAÍS: " . $country));
        $this->pdf->Text(10, 52, utf8_decode("TELEFONO: " . $phone));
        $this->pdf->Text(77, 52, utf8_decode("EMAIL: " . $mail));
        $this->pdf->Text(147, 52, utf8_decode("PAQUETE: " . $datos['data'][3]));
        $this->pdf->Text(10, 56, utf8_decode("MENSUALIDAD: " . $datos['data'][7]));
        $this->pdf->Text(77, 56, utf8_decode("FECHA DE COBRO: " . date('d', strtotime($datos['data'][8]))) . " de cada mes");
        $this->pdf->Text(147, 56, utf8_decode("MÉTODO DE PAGO: TARJETA DEBITO/CRÉDITO"));

        $this->pdf->SetXY(10, 62);

        $this->pdf->SetFont("helvetica", '', 6);

        $texto1 = utf8_decode("Con la firma del presente contrato el alumno/cliente/usuario acepta los términos y condiciones estipulados a continuación: Al firmar este contrato, usted recibirá una copia del mismo.
            
Servicios: CIMOS; Centro Integral de Movimiento y Salud dispone de espacio y equipo para practicar las actividades físicas conforme  al reglamento.

Pago: Con el pago de la mensualidad y/o paquete autorizo a SICCOB SOLUTIONS S.A DE C.V. a realizar cargos automáticos a la tarjeta de crédito y/o débito proporcionada,  esto con las tarifas correspondientes al precio de los servicios contratados. Para tal efecto, solicito y autorizo a la institución financiera emisora o a aquella institución afiliada a VISA, MasterCard y/o American Express , para que con base en el contrato de apertura de crédito, que tengo celebrado y con respecto del cual se me extendió la tarjeta o en su caso el número de tarjeta que por reposición de la anterior, por robo o extravío de la misma me haya asignado el Banco, se sirva pagar por mi cuenta a nombre de SICCOB SOLUTIONS S.A. de C.V. , los cargos por los conceptos, periodicidad y montos que se detallan en este documento. SICCOB SOLUTIONS S.A. de C.V. se obliga y es responsable de cumplir con: (i) la información generada correcta y oportuna de las cargos al tarjetahabiente, (ii) de la calidad y entrega de los productos y servicios ofrecidos liberando a la institución financiera emisora o a cualquier institución afiliada a VISA , MasterCard y/o American Express de toda reclamación que se genere por parte del tarjetahabiente. El tarjetahabiente podrá revocar la presente autorización mediante un comunicado por escrito o vía correo electrónico con 30 (treinta) días naturales de anticipación que recibirá SICCOB SOLUTIONS S.A. de C.V. el cual anotará la fecha de su recepción con la firma y nombre de quien recibe por CIMOS. En este caso SICCOB SOLUTIONS S.A. de C.V. deberá informar al tarjetahabiente la fecha en que dejara de surtir efecto la presente autorización.

El monto del paquete (servicio) puede cambiarse según las necesidades del cliente, siempre y cuando se respeten los acuerdos señalados en dicho contrato. Se notificara el cambio vía correo electrónico.

Inscripción: Será cobrada una tarifa de inscripción al momento de registrarse por primera vez como cliente nuevo en CIMOS; Centro Integral de Movimiento y Salud.

Mensualidad: Corresponde al valor mensual que debe pagar el alumno/cliente/usuario, permitiéndole a éste gozar de los beneficios del plan contratado. El valor de las mensualidades será cargado en el día establecido en la parte superior de este documento. Si por cualquier circunstancia el cargo en la tarjeta de crédito y/o débito no se efectúa en la fecha indicada precedentemente, se faculta ya a SICCOB SOLUTIONS S.A. de C.V. para insistir en el cobro por los medios antes indicados hasta que se realice el cargo, lo cual podrá realizarse en una fecha distinta a la antes indicada. Sin perjuicio de lo anterior, SICCOB SOLUTIONS S.A. de C.V. podrá realizar directamente el cobro al alumno/cliente/usuario. Una vez al año podrán reajustarse las mensualidades de acuerdo al cálculo de los costos de operación mismos que serán informados previo a la implementación del reajuste. 

Reajuste: Los valores previos en este contrato podrán estar sujetos a cambios, por el cálculo de los costos de operación, mismos que serán de conocimiento del alumno mediante una notificación del personal.

Atraso de pago: Para el caso de que  el cargo de que se trate  sea rechazado por el Banco emisor de la tarjeta de crédito, SICCOB SOLUTIONS S.A. de C.V. efectuará un cargo por una cantidad equivalente al 2% sobre el monto no pagado, por concepto de pena; adicionalmente, se cobrará una penalización de 1% mensual sobre el momento no pagado dividido por días en el mes.

Plazo: Este contrato tiene una vigencia de 12 (doce) meses a contar de esta fecha. En caso de que el alumno no desee renovar el contrato deberá de informar de esta situación a SICCOB SOLUTIONS S.A. DE C.V. por escrito con al menos 30 (treinta) días corridos de anticipación, en caso de que no lo haga se entenderá  que el contrato se renueva automáticamente y tácitamente por un nuevo periodo de 1 (un) mes, y así sucesivamente hasta que el cliente cancele el contrato, aplicándose  las nuevas tarifas si proceden.

Cancelación: En caso de cancelación de este contrato por parte de alumno/cliente/usuario, será retenida la totalidad de la cuota de mensualidad. Si la");

        $this->pdf->MultiCell(87, 3, $texto1);

        $this->pdf->SetXY(110, 62);

        $texto1 = utf8_decode("solicitud de cancelación se realiza antes de finalizar el periodo de 12 (doce) meses,  será retenida como penalización una tasa del 20% del valor correspondiente al periodo restante del tiempo del contrato. La cuota de mensualidad será retenida en su totalidad."
                . "No habrá retención de penalización, en caso de muerte, invalidez permanente comprobada en el alumno/cliente/usuario éste podrá solicitar la cancelación del contrato en cualquier momento, por medio de una carta  firmada y recibida  por parte de nuestro Staff  de recepción con atención  mínima de 30 días a la fecha del próximo cobro de mensualidad programado.
Un atraso superior a 60 (sesenta) días en el pago de mensualidades resultará en la rescisión automática del contrato. Será retenida como penalización una tasa del 20% del valor correspondiente al periodo restante del tiempo del contrato.
En caso de incumplimiento de cualquier obligación del presente contrato y/o del reglamento de uso de servicios más adelante señalado, dará lugar a la rescisión automática del mismo.

Reembolsos: En caso de cancelación del contrato, en los que proceda algún reembolso de parte de SICCOB SOLUTIONS S.A. de C.V. el alumno/cliente/usuario, se le descontará al alumno 20% del monto, por concepto de gastos de administración y el reembolso ocurrirá en un plazo máximo de 30 (treinta) días posteriores a la solicitud de cancelación. 

Cierre provisional / extensión: para uso de los días considerados como feriados y festivos no habrá extensión de plazo del presente contrato. Si por alguna razón CIMOS; Centro Integral de Movimiento y Salud efectuara el cierre total  y / o temporal de las instalaciones el alumno/cliente/usuario, queda asegurada, automáticamente  la prórroga del plan  por un periodo igual al cierre. Las restricciones en el uso de áreas y equipos sujetos a mantenimiento o eventos, no otorga derecho alguno de reducción, reembolso o bonificación de matrículas, anualidad y/o mensualidad.

Reglamento de Uso de Servicios: al firmar este contrato el alumno/cliente/usuario confirma que ha leído y confirma que está de acuerdo con el Reglamento de Uso , el cual se anexa a este contrato y se entiende que forma parte integrante de este contrato y se encuentra en la página web: https://cimos.com.mx/

Responsabilidades: La responsabilidad de CIMOS; Centro Integral de Movimiento y Salud se limita a la seguridad de los equipos e instalaciones disponibles y a la  orientación y/o capacitación inicial. SICCOB SOLUTIONS S.A. de C.V. no se responsabilizará por daños sufridos durante la práctica de ejercicios físicos que no sean directamente resultantes de estos factores.

Sobre la declaración de salud: Todo alumno/cliente/usuario que use los servicios del CIMOS; Centro Integral de Movimiento y Salud debe llevar a cabo una Evaluación de Salud previa al uso de las instalaciones y de la cual SICCOB SOLUTIONS S.A. de C.V. no se hace responsable. El alumno/cliente/usuario declara en este acto, estar en plenas condiciones de salud, apto para realizar actividades físicas y no portar ninguna enfermedad contagiosa que pueda perjudicar a los demás usuarios. Es obligación del alumno/cliente/usuario informar a SICCOB SOLUTIONS S.A. de C.V. por cualquier medio y de cualquier problema que tuviese y que le impida desarrollar adecuadamente con o sin riesgo para su salud una o más de las actividades que se desarrollan en CIMOS; Centro Integral de Movimiento y Salud  en un plazo no superior a 3 (tres) días desde acaecido tal problema en caso de que fuera posterior a la firma del presente contrato. El no cumplimiento de dicha sugerencia por parte del alumno dentro de los 5 (cinco) días siguientes a su ingreso a CIMOS; Centro Integral de Movimiento y Salud, implica que está optado por prescindir de la Evaluación  de Salud, sin necesidad de dejar constancia alguna por escrito, y en consecuencia libera a SICCOB SOLUTIONS S.A. de C.V. de toda responsabilidad ante cualquier situación de riesgo físico, lesiones e incluso fallecimiento, producto de la actividad que éste realice en CIMOS y que no sea imputable a negligencia por parte de SICCOB SOLUTIONS S.A. de C.V. o sus dependientes. SICCOB SOLUTIONS S.A. de C.V. no será responsable de las lesiones que afecten al alumno/cliente/usuario producto de ejercicios no contemplados en la rutina diseñada por el entrenador, por falta de calentamiento, por manipulación indebida de las máquinas, o por lesiones y enfermedades preexistentes, o por cualquier otra causa imputable al alumno/cliente/usuario. El alumno/cliente/usuario libera a SICCOB SOLUTIONS S.A. de C.V. a sus propietarios, empleados y agentes de toda la responsabilidad a este respecto, sin restricción o limitación de ninguna naturaleza.
");

        $this->pdf->MultiCell(87, 3, $texto1);

        $this->pdf->Text(20, 275, utf8_decode("CIUDAD DE MÉXICO,____________________."));
        $this->pdf->Text(30, 285, "FIRMA DEL ALUMNO/USUARIO/CLIENTE");
        $this->pdf->Text(145, 285, "FIRMA DEL TITULAR");

        $this->pdf->Line(135, 281, 175, 281);
        $this->pdf->Line(20, 281, 80, 281);

        $carpeta = $this->pdf->definirArchivo('CIMOS/Contratos', 'Contrato_' . $datos['data'][0]);
        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);
        return $carpeta;
    }

}
