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

    private function getCardSubscription($datos) {
        $url = 'https://cimos.com.mx/ws/OP/getSubscriptionDetail?customer=' . $datos['customer'] . '&suscription=' . $datos['suscription'];
        $result = json_decode(file_get_contents($url), true);
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

        $texto1 = utf8_decode("Con la firma de la presente suscripción de pago recurrente contrato general, el Cliente acepta los términos y condiciones estipulados a continuación: Al firmar este contrato, usted recibirá una copia del mismo. (En caso de que el Cliente sea menor de edad, deberá de firmar padre o tutor).
            
Servicios: SICCOB SOLUTIONS S.A. de C.V. dispone de espacio y equipo para practicar las actividades físicas conforme  al reglamento.

Pago: Con el pago de la mensualidad y/o paquete autorizo a SICCOB SOLUTIONS S.A DE C.V. a realizar cargos automáticos recurrentes y mensuales a la tarjeta de crédito y/o débito señalada,  esto con las tarifas correspondientes al precio de los servicios contratados. Para tal efecto, solicito y autorizo a la institución financiera emisora o a aquella institución afiliada a VISA, MasterCard y/o American Express , para que con base en el contrato de apertura de crédito, que tengo celebrado y con respecto del cual se me extendió la tarjeta o en su caso el número de tarjeta que por reposición de la anterior, por robo o extravío de la misma me haya asignado el Banco, se sirva pagar por mi cuenta a nombre de SICCOB SOLUTIONS S.A. de C.V. , los cargos por los conceptos, periodicidad y montos que se detallan en este documento. SICCOB SOLUTIONS S.A. de C.V. se obliga y es responsable de cumplir con: (i) la información generada correcta y oportuna de las cargos al tarjetahabiente, (ii) de la calidad y entrega de los productos y servicios ofrecidos liberando a la institución financiera emisora o a cualquier institución afiliada a VISA, MasterCard y/o American Express de toda reclamación que se genere por parte del tarjetahabiente. El tarjetahabiente NO podrá revocar la presente autorización, hasta el término total de la presente suscripción y contrato.

Inscripción: Será cobrada una tarifa de inscripción al momento de registrarse por primera vez como cliente nuevo en SICCOB SOLUTIONS S.A. de C.V.

Mensualidad: Corresponde al valor mensual que debe pagar el Cliente, permitiéndole a éste gozar de los beneficios del plan contratado. El valor de las mensualidades será cargado en el día establecido en la parte superior de este documento. Si por cualquier circunstancia el cargo en la tarjeta de crédito y/o débito no se efectúa en la fecha indicada precedentemente, se faculta ya a SICCOB SOLUTIONS S.A. de C.V. para ejercer su derecho de cobranza por alguna otra instancia, hasta que se realice el pago, lo cual podrá suceder en una fecha distinta a la antes indicada. Sin perjuicio de lo anterior, SICCOB SOLUTIONS S.A. de C.V. podrá realizar directamente el cobro Cliente. Una vez al año podrán reajustarse las mensualidades de acuerdo al cálculo de los costos de operación mismos que serán informados previo a la implementación del reajuste. La única forma de modificar el paquete es, si este, sufre un incremento en las clases, teniendo como condición la firma de un nuevo contrato.

Reajuste: Los valores previos en este contrato podrán estar sujetos a cambios, por el cálculo de los costos de operación o por cambios de estrategia de negocio, mismos que son del conocimiento y aceptación del Cliente.

Atraso de pago: Para el caso de que  el cargo sea rechazado por el Banco emisor de la tarjeta de crédito, SICCOB SOLUTIONS S.A. de C.V. efectuará un cargo por una cantidad equivalente al 5% sobre el monto no pagado, por concepto de pena; adicionalmente, se cobrará una penalización de 5% mensual sobre el monto no pagado dividido por días en el mes.

Plazo: Esta suscripción de pago recurrente y contrato tiene una vigencia de 6 (seis) meses obligatorios a partir de esta fecha y será autorrenovable cada cumplimiento del término. En caso de que el Cliente no desee renovar el contrato deberá de informar de esta situación a SICCOB SOLUTIONS S.A. DE C.V. por escrito con al menos 30 (treinta) días corridos de anticipación, en caso de que no lo haga se entenderá  que el contrato se renueva automáticamente y tácitamente por un nuevo periodo de 6 (seis) mes, y así sucesivamente hasta que el cliente cancele el contrato, aplicándose  las nuevas tarifas si proceden.");

        $this->pdf->MultiCell(87, 3, $texto1);

        $this->pdf->SetXY(110, 62);

        $texto1 = utf8_decode("Cancelación Anticipada: En caso de cancelación anticipada de esta suscripción de pago recurrente y contrato por parte del Cliente, será exigible las cuotas pendientes para el cumplimiento del contrato. 
En caso de incumplimiento de cualquier obligación del presente contrato y/o del reglamento de uso de servicios más adelante señalado, SICCOB SOLUTIONS S.A. de C.V. se reserva el derecho a continuar el servicio y/o renovación del mismo. 

Reembolsos: En caso de algún acuerdo extraordinario de cancelación, de las suscripción de pago recurrente y contrato, en los que proceda algún reembolso de parte de SICCOB SOLUTIONS S.A. de C.V. el Cliente, se le descontará un 25% del monto, por concepto de gastos de administración y el reembolso ocurrirá en un plazo máximo de 30 (treinta) días posteriores a la solicitud de cancelación. 

Cierre provisional / extensión: para uso de los días considerados como feriados y festivos no habrá extensión de plazo del presente contrato. Si por alguna razón SICCOB SOLUTIONS S.A. de C.V. efectuara el cierre total  y / o temporal de las instalaciones el alumno/cliente/usuario, queda asegurada, automáticamente  la prórroga del plan  por un periodo igual al cierre. Las restricciones en el uso de áreas y equipos sujetos a mantenimiento o eventos, no otorga derecho alguno de reducción, reembolso o bonificación de matrículas, anualidad y/o mensualidad.

Reglamento de Uso de Servicios: al firmar este contrato el Cliente confirma que ha leído y confirma que está de acuerdo con el Reglamento de Uso, el cual se anexa a esta suscripción de pago y contrato. Se entiende que forma parte integrante de esta suscripción recurrente y contrato y se encuentra para consulta en la página web: https://cimos.com.mx (Sin responsabilidad por cambios subsecuentes).

Responsabilidades: La responsabilidad de SICCOB SOLUTIONS S.A. de C.V. se limita a la seguridad de los equipos e instalaciones disponibles y a la  orientación y/o capacitación inicial. SICCOB SOLUTIONS S.A. de C.V. no se responsabilizará por daños sufridos durante la práctica de ejercicios físicos que no sean directamente resultantes de estos factores.

Sobre la declaración de salud: Todo Cliente que use los servicios del SICCOB SOLUTIONS S.A. de C.V. debe llevar a cabo una Evaluación de Salud previa al uso de las instalaciones y de la cual SICCOB SOLUTIONS S.A. de C.V. no se hace responsable. El Cliente declara en este acto, estar en plenas condiciones de salud, apto para realizar actividades físicas y no portar ninguna enfermedad contagiosa que pueda perjudicar a los demás usuarios. Es obligación del Cliente informar a SICCOB SOLUTIONS S.A. de C.V. por cualquier medio y de cualquier problema que tuviese y que le impida desarrollar adecuadamente con o sin riesgo para su salud una o más de las actividades que se desarrollan en SICCOB SOLUTIONS S.A. de C.V. en un plazo no superior a 3 (tres) días desde acaecido tal problema en caso de que fuera posterior a la firma del presente contrato. El no cumplimiento de dicha sugerencia por parte del alumno dentro de los 5 (cinco) días siguientes a su ingreso a SICCOB SOLUTIONS S.A. de C.V., implica que está optado por prescindir de la Evaluación  de Salud, sin necesidad de dejar constancia alguna por escrito, y en consecuencia libera a SICCOB SOLUTIONS S.A. de C.V. de toda responsabilidad ante cualquier situación de riesgo físico, lesiones e incluso fallecimiento, producto de la actividad que éste realice en CIMOS y que no sea imputable a negligencia por parte de SICCOB SOLUTIONS S.A. de C.V. o sus dependientes. SICCOB SOLUTIONS S.A. de C.V. no será responsable de las lesiones que afecten al Cliente producto de ejercicios no contemplados en la rutina diseñada por el entrenador, por falta de calentamiento, por manipulación indebida de las máquinas, o por lesiones y enfermedades preexistentes, o por cualquier otra causa imputable al alumno/cliente/usuario. El alumno/cliente/usuario libera a SICCOB SOLUTIONS S.A. de C.V. a sus propietarios, empleados y agentes de toda la responsabilidad a este respecto, sin restricción o limitación de ninguna naturaleza.
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

    public function searchByID(array $datos) {
        $clientes = $this->getFullClientes();
        $cliente = $clientes['clientes'][$datos['id']];

        $this->pdf->AddPage();
        $this->pdf->Image('https://cimos.com.mx/wp-content/uploads/2018/04/CIMOS1-3.png', 10, 15, 45, 0, 'PNG');
        $this->pdf->SetFont("helvetica", "B", 14);
        $this->pdf->Text(83, 16, "CIMOS");
        $this->pdf->SetFont("helvetica", "B", 9);
        $this->pdf->Text(70, 23, utf8_decode("Contrato de Prestación de"));
        $this->pdf->Text(73, 26, utf8_decode("Servicios para Clientes"));

        $this->pdf->SetFont("helvetica", '', 7);
        $this->pdf->Text(127, 14, utf8_decode("Razón Social: SICCOB SOLUTIONS S.A. de C.V."));
        $this->pdf->SetFont("helvetica", 'B', 7);
        $this->pdf->Text(127, 17, utf8_decode("Nombre Comercial: CIMOS (Centro Integral de Movimiento y Salud"));
        $this->pdf->SetFont("helvetica", '', 7);
        $this->pdf->Text(127, 20, utf8_decode("Sede: Plaza Grand San Francisco, Calz. Desierto de los Leones 5525"));
        $this->pdf->Text(127, 23, utf8_decode("Ciudad de México, C.P. 01729"));
        $this->pdf->Text(127, 29, utf8_decode("Fecha: " . date("F j, Y, g:i a")));
        $this->pdf->Text(127, 32, utf8_decode("Folio: CPS-" . sprintf("%'.011d\n", $datos['id'])));

        $this->pdf->Line(3, 35, 207, 35);
        $this->pdf->Line(3, 35, 3, 60);

        $this->pdf->Line(3, 60, 207, 60);
        $this->pdf->Line(207, 35, 207, 60);

        $fecha = ($cliente['Birtdate'] !== '') ? date('d/m/Y', strtotime(str_replace("T", "", $cliente['Birtdate']))) : 'Sin Fecha';
        $adress = ($cliente['Adress'] != '') ? $cliente['Adress'] : "Sin información";
        $city = ($cliente['City'] != '') ? $cliente['City'] : "Sin información";
        $state = ($cliente['State'] != '') ? $cliente['State'] : "Sin información";
        $cp = ($cliente['PostalCode'] != '') ? $cliente['PostalCode'] : "Sin información";
        $country = ($cliente['Country'] != '') ? $cliente['Country'] : "Sin información";
        $phone = ($cliente['Phone'] != '') ? $cliente['Phone'] : "Sin información";
        $mail = ($cliente['Mail'] != '') ? $cliente['Mail'] : "Sin información";

        $this->pdf->Text(10, 40, utf8_decode("NOMBRE(S): " . $cliente['Firstname']));
        $this->pdf->Text(77, 40, utf8_decode("APELLIDO(S): " . $cliente['Lastname']));
        $this->pdf->Text(147, 40, utf8_decode("FECHA DE NACIMIENTO: " . $fecha));
        $this->pdf->Text(10, 44, utf8_decode("DIRECCIÓN POSTAL: " . $adress));
        $this->pdf->Text(10, 48, utf8_decode("CIUDAD: " . $city));
        $this->pdf->Text(60, 48, utf8_decode("ESTADO: " . $state));
        $this->pdf->Text(110, 48, utf8_decode("CODIGO POSTAL: " . $cp));
        $this->pdf->Text(160, 48, utf8_decode("PAÍS: " . $country));
        $this->pdf->Text(10, 52, utf8_decode("TELEFONO: " . $phone));
        $this->pdf->Text(77, 52, utf8_decode("EMAIL: " . $mail));
        $this->pdf->Text(10, 56, utf8_decode("TIPO DE CONTRATO: NO RECURRENTE "));
        $this->pdf->Text(77, 56, utf8_decode("ID: " . $datos['id']));

        $this->pdf->SetXY(10, 62);

        $this->pdf->SetFont("helvetica", '', 7);

        $texto1 = utf8_decode("Con la firma del presente contrato de prestación de servicios el Cliente acepta los términos y condiciones estipulados a continuación: Al firmar este contrato, usted recibirá una copia del mismo. (En caso de que el Cliente sea menor de edad, deberá de firmar padre o tutor).
            
Servicios: SICCOB SOLUTIONS S.A. de C.V. dispone de espacio y equipo para practicar las actividades físicas conforme  al reglamento

Inscripción: Será cobrada una tarifa de inscripción al momento de registrarse por primera vez como cliente nuevo en SICCOB SOLUTIONS S.A. de C.V.

Pago: Con el pago de la mensualidad y/o paquete autorizo a SICCOB SOLUTIONS S.A DE C.V. a realizar cargo a la tarjeta de crédito, débito y/o efectivo señalada,  esto con las tarifas correspondientes al precio de los servicios contratados. Para tal efecto, solicito y autorizo a la institución financiera emisora o a aquella institución afiliada a VISA, MasterCard y/o American Express , para que con base en el contrato de apertura de crédito, que tengo celebrado y con respecto del cual se me extendió la tarjeta o en su caso el número de tarjeta que por reposición de la anterior, por robo o extravío de la misma me haya asignado el Banco, se sirva pagar por mi cuenta a nombre de SICCOB SOLUTIONS S.A. de C.V. , los cargos por los conceptos, periodicidad y montos que se detallan en este documento. SICCOB SOLUTIONS S.A. de C.V. se obliga y es responsable de cumplir con: (i) la información generada correcta y oportuna de las cargos al tarjetahabiente, (ii) de la calidad y entrega de los productos y servicios ofrecidos liberando a la institución financiera emisora o a cualquier institución afiliada a VISA, MasterCard y/o American Express de toda reclamación que se genere por parte del tarjetahabiente. El tarjetahabiente NO podrá revocar la presente autorización, hasta el término total de la presente suscripción y contrato.

Renovaciones: Corresponde al valor del pago del Cliente, permitiéndole a éste gozar de los beneficios del plan contratado. Si por cualquier circunstancia el cargo en la tarjeta de crédito y/o débito no se efectúa en la fecha indicada precedentemente, se faculta ya a SICCOB SOLUTIONS S.A. de C.V. para ejercer su derecho de cobranza por alguna otra instancia, hasta que se realice el pago, lo cual podrá suceder en una fecha distinta a la antes indicada. Sin perjuicio de lo anterior, SICCOB SOLUTIONS S.A. de C.V. podrá realizar directamente el cobro Cliente. Una vez al año podrán reajustarse las mensualidades de acuerdo al cálculo de los costos de operación mismos que serán informados previo a la implementación del reajuste. La única forma de modificar el paquete es, si este, sufre un incremento en las clases, teniendo como condición la firma de un nuevo contrato.

Reajuste: Los valores previos en este contrato podrán estar sujetos a cambios, por el cálculo de los costos de operación o por cambios de estrategia de negocio, mismos que son del conocimiento y aceptación del Cliente.

Atraso de pago: Para el caso de que  el cargo sea rechazado por el Banco emisor de la tarjeta de crédito, SICCOB SOLUTIONS S.A. de C.V. efectuará un cargo por una cantidad equivalente al 5% sobre el monto no pagado, por concepto de pena; adicionalmente, se cobrará una penalización de 5% mensual sobre el monto no pagado dividido por días en el mes.");

        $this->pdf->MultiCell(87, 3, $texto1);

        $this->pdf->SetXY(110, 62);

        $texto1 = utf8_decode("Plazo: Este contrato es de tiempo indefinido y limitado a los plazos de la mensualidad o paquete que aparece en la parte superior de dicho contrato y será autorrenovable cada vez que el cliente realice una nueva compra de mensualidad o paquete. 
            
Cancelación: En caso de solicitar cancelación del paquete contratado por el Cliente, no aplicara ningún rembolso total o parcial.

Cierre provisional / extensión: para uso de los días considerados como feriados y festivos no habrá extensión de plazo del presente contrato. Si por alguna razón SICCOB SOLUTIONS S.A. de C.V. efectuara el cierre total  y / o temporal de las instalaciones el alumno/cliente/usuario, queda asegurada, automáticamente  la prórroga del plan  por un periodo igual al cierre. Las restricciones en el uso de áreas y equipos sujetos a mantenimiento o eventos, no otorga derecho alguno de reducción, reembolso o bonificación de matrículas, anualidad y/o mensualidad.

Reglamento de Uso de Servicios: al firmar este contrato el Cliente confirma que ha leído y confirma que está de acuerdo con el Reglamento de Uso, el cual se anexa a esta suscripción de pago y contrato. Se entiende que forma parte integrante de esta suscripción recurrente y contrato y se encuentra para consulta en la página web: https://cimos.com.mx (Sin responsabilidad por cambios subsecuentes).

Responsabilidades: La responsabilidad de SICCOB SOLUTIONS S.A. de C.V. se limita a la seguridad de los equipos e instalaciones disponibles y a la  orientación y/o capacitación inicial. SICCOB SOLUTIONS S.A. de C.V. no se responsabilizará por daños sufridos durante la práctica de ejercicios físicos que no sean directamente resultantes de estos factores.

Sobre la declaración de salud: Todo Cliente que use los servicios del SICCOB SOLUTIONS S.A. de C.V. debe llevar a cabo una Evaluación de Salud previa al uso de las instalaciones y de la cual SICCOB SOLUTIONS S.A. de C.V. no se hace responsable. El Cliente declara en este acto, estar en plenas condiciones de salud, apto para realizar actividades físicas y no portar ninguna enfermedad contagiosa que pueda perjudicar a los demás usuarios. Es obligación del Cliente informar a SICCOB SOLUTIONS S.A. de C.V. por cualquier medio y de cualquier problema que tuviese y que le impida desarrollar adecuadamente con o sin riesgo para su salud una o más de las actividades que se desarrollan en SICCOB SOLUTIONS S.A. de C.V. en un plazo no superior a 3 (tres) días desde acaecido tal problema en caso de que fuera posterior a la firma del presente contrato. El no cumplimiento de dicha sugerencia por parte del alumno dentro de los 5 (cinco) días siguientes a su ingreso a SICCOB SOLUTIONS S.A. de C.V., implica que está optado por prescindir de la Evaluación  de Salud, sin necesidad de dejar constancia alguna por escrito, y en consecuencia libera a SICCOB SOLUTIONS S.A. de C.V. de toda responsabilidad ante cualquier situación de riesgo físico, lesiones e incluso fallecimiento, producto de la actividad que éste realice en CIMOS y que no sea imputable a negligencia por parte de SICCOB SOLUTIONS S.A. de C.V. o sus dependientes. SICCOB SOLUTIONS S.A. de C.V. no será responsable de las lesiones que afecten al Cliente producto de ejercicios no contemplados en la rutina diseñada por el entrenador, por falta de calentamiento, por manipulación indebida de las máquinas, o por lesiones y enfermedades preexistentes, o por cualquier otra causa imputable al alumno/cliente/usuario. El alumno/cliente/usuario libera a SICCOB SOLUTIONS S.A. de C.V. a sus propietarios, empleados y agentes de toda la responsabilidad a este respecto, sin restricción o limitación de ninguna naturaleza.
");

        $this->pdf->MultiCell(87, 3, $texto1);

        $this->pdf->Text(20, 255, utf8_decode("CIUDAD DE MÉXICO,____________________."));
        $this->pdf->Text(40, 265, "FIRMA DEL CLIENTE");
        $this->pdf->SetFont("helvetica", '', 6);
        $this->pdf->Text(135, 265, "NOMBRE Y FIRMA DEL TITULAR DE");
        $this->pdf->Text(135, 268, "TARJETA, RESPONSABLE DE PAGO.");

        $this->pdf->Line(125, 261, 185, 261);
        $this->pdf->Line(20, 261, 80, 261);

        $this->pdf->Text(88, 285, "AL FIRMAR ESTE CONTRATO DE PRESTACION DE SERVICIOS, ACEPTO LAS CLAUSULAS Y CONDICONES.");

        $carpeta = $this->pdf->definirArchivo('CIMOS/Contratos', 'CPS_' . $datos['id']);
        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);

        $arrayReturn['contratos'] = [$carpeta];

        $contratos = $this->getFullSuscripciones()['contracts'];
        $suscripciones = $this->getSuscripcionesActivas()['GetFullSubscriptions'];
        $suscripcionesCliente = [];

        foreach ($suscripciones as $key => $value) {
            if ($value['IdClienteMB'] == $datos['id']) {
                $cardData = $this->getCardSubscription(
                        [
                            'suscription' => $value['IdSuscripcionOP'],
                            'customer' => $value['IdClienteOP']
                        ]
                );

                $card = [
                    'brand' => '',
                    'number' => '',
                    'bank' => ''
                ];

                if ($cardData['error_code'] == 200) {
                    $card['brand'] = $cardData['card']['brand'];
                    $card['number'] = $cardData['card']['number'];
                    $card['bank'] = $cardData['card']['bank'];
                }


                $this->pdf = new PDF();
                $this->pdf->AddPage();
                $this->pdf->Image('https://cimos.com.mx/wp-content/uploads/2018/04/CIMOS1-3.png', 10, 15, 45, 0, 'PNG');
                $this->pdf->SetFont("helvetica", "B", 14);
                $this->pdf->Text(83, 16, "CIMOS");
                $this->pdf->SetFont("helvetica", "B", 9);
                $this->pdf->Text(70, 23, utf8_decode("Contrato de Pago Recurrente y"));
                $this->pdf->Text(70, 26, utf8_decode("Contrato General para Clientes"));

                $this->pdf->SetFont("helvetica", '', 7);
                $this->pdf->Text(130, 14, utf8_decode("Razón Social: SICCOB SOLUTIONS S.A. de C.V."));
                $this->pdf->SetFont("helvetica", 'B', 7);
                $this->pdf->Text(130, 17, utf8_decode("Nombre Comercial: CIMOS (Centro Integral de Movimiento y Salud"));
                $this->pdf->SetFont("helvetica", '', 7);
                $this->pdf->Text(130, 20, utf8_decode("Sede: Plaza Grand San Francisco, Calz. Desierto de los Leones 5525"));
                $this->pdf->Text(130, 23, utf8_decode("Ciudad de México, C.P. 01729"));
                $this->pdf->Text(130, 29, utf8_decode("Fecha de Inicio: " . date("F j, Y, g:i a", strtotime($value['Fecha']))));
                $this->pdf->Text(130, 32, utf8_decode("Folio: C-" . sprintf("%'.011d\n", $value['Id'])));

                $this->pdf->Line(3, 35, 207, 35);
                $this->pdf->Line(3, 35, 3, 63);

                $this->pdf->Line(3, 63, 207, 63);
                $this->pdf->Line(207, 35, 207, 63);

                $this->pdf->Text(10, 40, utf8_decode("NOMBRE(S): " . $cliente['Firstname']));
                $this->pdf->Text(77, 40, utf8_decode("APELLIDO(S): " . $cliente['Lastname']));
                $this->pdf->Text(147, 40, utf8_decode("FECHA DE NACIMIENTO: " . $fecha));
                $this->pdf->Text(10, 44, utf8_decode("DIRECCIÓN POSTAL: " . $adress));
                $this->pdf->Text(10, 48, utf8_decode("CIUDAD: " . $city));
                $this->pdf->Text(60, 48, utf8_decode("ESTADO: " . $state));
                $this->pdf->Text(100, 48, utf8_decode("CODIGO POSTAL: " . $cp));
                $this->pdf->Text(160, 48, utf8_decode("PAÍS: " . $country));
                $this->pdf->Text(10, 52, utf8_decode("TELEFONO: " . $phone));
                $this->pdf->Text(77, 52, utf8_decode("EMAIL: " . $mail));
                $this->pdf->Text(147, 52, utf8_decode("PAQUETE: " . $contratos[$value['IdContratoMB']]['Nombre']));
                $this->pdf->Text(10, 56, utf8_decode("MENSUALIDAD: $" . number_format(($value['CostoMB'] - $value['DescuentoMB']), 2, '.', ',')));
                $this->pdf->Text(77, 56, utf8_decode("FECHA DE COBRO: " . date('d', strtotime($value['Fecha']))) . " de cada mes");
                $this->pdf->Text(147, 56, utf8_decode("MÉTODO DE PAGO: TARJETA DEBITO/CRÉDITO"));
                $this->pdf->Text(10, 60, utf8_decode("TIPO DE CONTRATO: RECURRENTE"));
                $this->pdf->Text(60, 60, utf8_decode("BANCO: " . strtoupper($card['bank'])));
                $this->pdf->Text(100, 60, utf8_decode("BRAND: " . strtoupper($card['brand'])));
                $this->pdf->Text(147, 60, utf8_decode("# TARJETA: ".$card['number']));

                $this->pdf->SetXY(10, 65);

                $this->pdf->SetFont("helvetica", '', 6);

                $texto1 = utf8_decode("Con la firma de la presente suscripción de pago recurrente contrato general, el Cliente acepta los términos y condiciones estipulados a continuación: Al firmar este contrato, usted recibirá una copia del mismo. (En caso de que el Cliente sea menor de edad, deberá de firmar padre o tutor).
            
Servicios: SICCOB SOLUTIONS S.A. de C.V. dispone de espacio y equipo para practicar las actividades físicas conforme  al reglamento.

Pago: Con el pago de la mensualidad y/o paquete autorizo a SICCOB SOLUTIONS S.A DE C.V. a realizar cargos automáticos recurrentes y mensuales a la tarjeta de crédito y/o débito señalada,  esto con las tarifas correspondientes al precio de los servicios contratados. Para tal efecto, solicito y autorizo a la institución financiera emisora o a aquella institución afiliada a VISA, MasterCard y/o American Express , para que con base en el contrato de apertura de crédito, que tengo celebrado y con respecto del cual se me extendió la tarjeta o en su caso el número de tarjeta que por reposición de la anterior, por robo o extravío de la misma me haya asignado el Banco, se sirva pagar por mi cuenta a nombre de SICCOB SOLUTIONS S.A. de C.V. , los cargos por los conceptos, periodicidad y montos que se detallan en este documento. SICCOB SOLUTIONS S.A. de C.V. se obliga y es responsable de cumplir con: (i) la información generada correcta y oportuna de las cargos al tarjetahabiente, (ii) de la calidad y entrega de los productos y servicios ofrecidos liberando a la institución financiera emisora o a cualquier institución afiliada a VISA, MasterCard y/o American Express de toda reclamación que se genere por parte del tarjetahabiente. El tarjetahabiente NO podrá revocar la presente autorización, hasta el término total de la presente suscripción y contrato.

Inscripción: Será cobrada una tarifa de inscripción al momento de registrarse por primera vez como cliente nuevo en SICCOB SOLUTIONS S.A. de C.V.

Mensualidad: Corresponde al valor mensual que debe pagar el Cliente, permitiéndole a éste gozar de los beneficios del plan contratado. El valor de las mensualidades será cargado en el día establecido en la parte superior de este documento. Si por cualquier circunstancia el cargo en la tarjeta de crédito y/o débito no se efectúa en la fecha indicada precedentemente, se faculta ya a SICCOB SOLUTIONS S.A. de C.V. para ejercer su derecho de cobranza por alguna otra instancia, hasta que se realice el pago, lo cual podrá suceder en una fecha distinta a la antes indicada. Sin perjuicio de lo anterior, SICCOB SOLUTIONS S.A. de C.V. podrá realizar directamente el cobro Cliente. Una vez al año podrán reajustarse las mensualidades de acuerdo al cálculo de los costos de operación mismos que serán informados previo a la implementación del reajuste. La única forma de modificar el paquete es, si este, sufre un incremento en las clases, teniendo como condición la firma de un nuevo contrato.

Reajuste: Los valores previos en este contrato podrán estar sujetos a cambios, por el cálculo de los costos de operación o por cambios de estrategia de negocio, mismos que son del conocimiento y aceptación del Cliente.

Atraso de pago: Para el caso de que  el cargo sea rechazado por el Banco emisor de la tarjeta de crédito, SICCOB SOLUTIONS S.A. de C.V. efectuará un cargo por una cantidad equivalente al 5% sobre el monto no pagado, por concepto de pena; adicionalmente, se cobrará una penalización de 5% mensual sobre el monto no pagado dividido por días en el mes.

Plazo: Esta suscripción de pago recurrente y contrato tiene una vigencia de 6 (seis) meses obligatorios a partir de esta fecha y será autorrenovable cada cumplimiento del término. En caso de que el Cliente no desee renovar el contrato deberá de informar de esta situación a SICCOB SOLUTIONS S.A. DE C.V. por escrito con al menos 30 (treinta) días corridos de anticipación, en caso de que no lo haga se entenderá  que el contrato se renueva automáticamente y tácitamente por un nuevo periodo de 6 (seis) mes, y así sucesivamente hasta que el cliente cancele el contrato, aplicándose  las nuevas tarifas si proceden.");

                $this->pdf->MultiCell(87, 3, $texto1);

                $this->pdf->SetXY(110, 65);

                $texto1 = utf8_decode("Cancelación Anticipada: En caso de cancelación anticipada de esta suscripción de pago recurrente y contrato por parte del Cliente, será exigible las cuotas pendientes para el cumplimiento del contrato. 

En caso de incumplimiento de cualquier obligación del presente contrato y/o del reglamento de uso de servicios más adelante señalado, SICCOB SOLUTIONS S.A. de C.V. se reserva el derecho a continuar el servicio y/o renovación del mismo. 

Reembolsos: En caso de algún acuerdo extraordinario de cancelación, de las suscripción de pago recurrente y contrato, en los que proceda algún reembolso de parte de SICCOB SOLUTIONS S.A. de C.V. el Cliente, se le descontará un 25% del monto, por concepto de gastos de administración y el reembolso ocurrirá en un plazo máximo de 30 (treinta) días posteriores a la solicitud de cancelación. 

Cierre provisional / extensión: para uso de los días considerados como feriados y festivos no habrá extensión de plazo del presente contrato. Si por alguna razón SICCOB SOLUTIONS S.A. de C.V. efectuara el cierre total  y / o temporal de las instalaciones el alumno/cliente/usuario, queda asegurada, automáticamente  la prórroga del plan  por un periodo igual al cierre. Las restricciones en el uso de áreas y equipos sujetos a mantenimiento o eventos, no otorga derecho alguno de reducción, reembolso o bonificación de matrículas, anualidad y/o mensualidad.

Reglamento de Uso de Servicios: al firmar este contrato el Cliente confirma que ha leído y confirma que está de acuerdo con el Reglamento de Uso, el cual se anexa a esta suscripción de pago y contrato. Se entiende que forma parte integrante de esta suscripción recurrente y contrato y se encuentra para consulta en la página web: https://cimos.com.mx (Sin responsabilidad por cambios subsecuentes).

Responsabilidades: La responsabilidad de SICCOB SOLUTIONS S.A. de C.V. se limita a la seguridad de los equipos e instalaciones disponibles y a la  orientación y/o capacitación inicial. SICCOB SOLUTIONS S.A. de C.V. no se responsabilizará por daños sufridos durante la práctica de ejercicios físicos que no sean directamente resultantes de estos factores.

Sobre la declaración de salud: Todo Cliente que use los servicios del SICCOB SOLUTIONS S.A. de C.V. debe llevar a cabo una Evaluación de Salud previa al uso de las instalaciones y de la cual SICCOB SOLUTIONS S.A. de C.V. no se hace responsable. El Cliente declara en este acto, estar en plenas condiciones de salud, apto para realizar actividades físicas y no portar ninguna enfermedad contagiosa que pueda perjudicar a los demás usuarios. Es obligación del Cliente informar a SICCOB SOLUTIONS S.A. de C.V. por cualquier medio y de cualquier problema que tuviese y que le impida desarrollar adecuadamente con o sin riesgo para su salud una o más de las actividades que se desarrollan en SICCOB SOLUTIONS S.A. de C.V. en un plazo no superior a 3 (tres) días desde acaecido tal problema en caso de que fuera posterior a la firma del presente contrato. El no cumplimiento de dicha sugerencia por parte del alumno dentro de los 5 (cinco) días siguientes a su ingreso a SICCOB SOLUTIONS S.A. de C.V., implica que está optado por prescindir de la Evaluación  de Salud, sin necesidad de dejar constancia alguna por escrito, y en consecuencia libera a SICCOB SOLUTIONS S.A. de C.V. de toda responsabilidad ante cualquier situación de riesgo físico, lesiones e incluso fallecimiento, producto de la actividad que éste realice en CIMOS y que no sea imputable a negligencia por parte de SICCOB SOLUTIONS S.A. de C.V. o sus dependientes. SICCOB SOLUTIONS S.A. de C.V. no será responsable de las lesiones que afecten al Cliente producto de ejercicios no contemplados en la rutina diseñada por el entrenador, por falta de calentamiento, por manipulación indebida de las máquinas, o por lesiones y enfermedades preexistentes, o por cualquier otra causa imputable al alumno/cliente/usuario. El alumno/cliente/usuario libera a SICCOB SOLUTIONS S.A. de C.V. a sus propietarios, empleados y agentes de toda la responsabilidad a este respecto, sin restricción o limitación de ninguna naturaleza.
");

                $this->pdf->MultiCell(87, 3, $texto1);

                $this->pdf->Text(20, 255, utf8_decode("CIUDAD DE MÉXICO,____________________."));
                $this->pdf->Text(40, 265, "FIRMA DEL CLIENTE");
                $this->pdf->SetFont("helvetica", '', 6);
                $this->pdf->Text(135, 265, "NOMBRE Y FIRMA DEL TITULAR DE");
                $this->pdf->Text(135, 268, "TARJETA, RESPONSABLE DE PAGO.");

                $this->pdf->Line(125, 261, 185, 261);
                $this->pdf->Line(20, 261, 80, 261);

                $this->pdf->Text(88, 285, "AL FIRMAR ESTE CONTRATO DE PRESTACION DE SERVICIOS, ACEPTO LAS CLAUSULAS Y CONDICONES.");

                $carpeta = $this->pdf->definirArchivo('CIMOS/Contratos', 'CS_' . $value['Id']);
                $this->pdf->Output('F', $carpeta, true);
                $carpeta = substr($carpeta, 1);
                array_push($suscripcionesCliente, [
                    'Name' => date('Y-m-d', strtotime($value['Fecha'])) . ' ' . $contratos[$value['IdContratoMB']]['Nombre'],
                    'Link' => $carpeta
                ]);
            }
        }

        $arrayReturn['suscripciones'] = $suscripcionesCliente;

        return $arrayReturn;
    }

}
