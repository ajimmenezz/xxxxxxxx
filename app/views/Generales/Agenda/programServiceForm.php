<?php
$actualDate = new DateTime();
$data = [
    'title' => "",
    'minDate' => $actualDate->format("Y-m-d"),
    'maxDate' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 8, date("Y"))),
    'descripcion' => "",
    'serviceId' => "",
    'tentativeDate' => $actualDate->format("Y-m-d"),
    'tentativeTime' => $actualDate->format("H:i:s"),
    'calendarId' => ""
];

if (isset($pendingService)) {
    if ($pendingService['FechaTentativa'] != "") {
        $tentativeDate = new DateTime($pendingService['FechaTentativa']);
        $data['tentativeDate'] = $tentativeDate->format("Y-m-d");
        $data['tentativeTime'] = $tentativeDate->format("H:i:s");
    }

    $data['serviceId'] = $pendingService['Id'];
    $data['title'] = $pendingService['Ticket'] . '/' . ($pendingService['Folio'] > 0 ? $pendingService['Folio'] : 'Sin SD') . '  ' . $pendingService['TipoServicio'];
    $data['description'] = '' .
        'Ticket ' . $pendingService['Ticket'] . "\n" .
        ($pendingService['Folio'] > 0 ? 'Ticket SD ' . $pendingService['Folio'] : 'Sin SD') . "\n" .
        ($pendingService['Sucursal'] != '' ? $pendingService['Sucursal'] . ' - ' . $pendingService['Zona'] : '') . "\n" .
        'Atiende ' . $pendingService['Atiende'] . "\n" .
        "Asunto \n" .
        $pendingService['Asunto'] . "\n" .
        "Descripción \n" .
        $pendingService['Descripcion'];
    $data['calendarId'] = $pendingService['CalendarId'];
}
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
        <label f-s-10 f-w-500>Título del Evento:</label>
        <input type="hidden" id="eventServiceId" value="<?php echo $data['serviceId'] ?>" />
        <input type="hidden" id="googleEventId" value="<?php echo $data['calendarId'] ?>" />
        <input id="eventTitle" type="text" class="form-control f-s-18" value="<?php echo $data['title']; ?>" placeholder="Añade un título">
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-6 form-group">
        <label f-s-10 f-w-500>Fecha Tentativa:</label>
        <input id="eventDate" type="date" class="form-control" value="<?php echo $data['tentativeDate']; ?>" max="<?php echo $data['maxDate']; ?>" min="<?php echo $data['minDate']; ?>">
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6 form-group">
        <label f-s-10 f-w-500>Horario:</label>
        <select id="eventTime" class="form-control">
            <?php
            $dateTime = new DateTime('2020-01-01 09:00:00');
            $endDate = new DateTime('2020-01-01 22:00:00');
            do {
                $selected = '';
                if ($dateTime->format('H:i:s') == $data['tentativeTime']) {
                    $selected = 'selected';
                }
                echo '<option value="' . $dateTime->format('H:i:s') . '" ' . $selected . '>' . $dateTime->format('g:i a') . '</option>';
                $dateTime->add(new DateInterval('PT15M'));
            } while ($dateTime <= $endDate);
            ?>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
        <label f-s-10 f-w-500>Descripción:</label>
        <textarea style="white-space: pre-line;" class="form-control" id="eventDescription" rows="10"><?php echo $data['description']; ?></textarea>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php
        if ($pendingService['CalendarId'] != "") {
        ?>
            <div class="note note-warning">
                <h4>Ya existe un evento en Google Calendar</h4>
                <p class="f-s-13 f-w-600">
                    <a target="_blank" href="<?php echo $pendingService['CalendarLink']; ?>">Ir al evento en Google Calendar</a>
                    <br />
                    Si buscas cambiar la fecha y hora, el título o descripción, solo debes
                    capturar los datos en el formulario y guardar los cambios.
                </p>
            </div>
        <?php
        } else {
        ?>
            <div class="note note-success">
                <h4>Sin evento en Google Calendar</h4>
                <p class="f-s-13 f-w-600">
                    Este servicio no cuenta con ningún evento registrado en Google Calendar.
                    Cuando decidas guardar la información, se creará un evento que te permitirá
                    visualizarlo en tu cuenta de Google y en la interfaz de calendario de este módulo.
                </p>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<?php
if (isset($eventHistory) && count($eventHistory) > 0) {
?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Historial de Programación</h3>
                </div>
                <div class="panel-body">
                    <?php
                    foreach ($eventHistory as $k => $v) {
                        $fecha = new DateTime($v['Fecha']);
                        $fechaTentativa = new DateTime($v['FechaInicio']);
                        echo '
                        <p class="f-s-12">
                            <span class="f-w-600">' . $fecha->format("r") . '</span>
                            <br />
                            El servicio fué re-programado por <strong>' . $v['Usuario'] . '</strong>
                            <br />
                            Será atendido el <strong>' . $fechaTentativa->format("r") . '</strong>
                        </p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>