<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;
use DateTime;
use Exception;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;

class Agenda extends General
{
    private $googleCalendarApi;
    private $service;
    private $db;
    private $calendarId = 'siccob.com.mx_f0i5hqidsq707u7h1g33d7a6ss@group.calendar.google.com';
    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->googleCalendarApi = new \Librerias\GoogleApi\GoogleCalendarApi;
        $this->service = $this->getCalendarService();
        $this->db = \Modelos\Modelo_Agenda::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function loadGoogleEvents(array $data = [])
    {
        $googleEvents = $this->getCalendarEvents($data);
        $myPendings = $this->createMyPendingServicesArray();
        $events = [];
        foreach ($googleEvents as $k => $v) {
            if (array_key_exists($v['id'], $myPendings)) {
                array_push($events, [
                    'id' => $myPendings[$v['id']],
                    'title' => $v['summary'],
                    'start' => $v['modelData']['start']['dateTime'],
                    'end' => $v['modelData']['end']['dateTime'],
                    'created' => $v['created'],
                    'updated' => $v['updated'],
                    'description' => $v['description'],
                    'link' => $v['htmlLink'],
                    'attendees' => $this->convertGoogleAttendees($v['attendees'])
                ]);
            }
        }

        return ['events' => $events];
    }

    private function createMyPendingServicesArray()
    {
        $servicesIds = [];
        $pendingServices = $this->db->getMyPendingServices();
        foreach ($pendingServices as $k => $v) {
            if ($v['CalendarId'] != "") {
                $servicesIds[$v['CalendarId']] = $v['Id'];
            }
        }
        return $servicesIds;
    }

    private function convertGoogleAttendees(array $googleAttendees = [])
    {
        $attendees = [];
        foreach ($googleAttendees as $k => $v) {
            array_push($attendees, $v['email']);
        }
        return $attendees;
    }

    private function getCalendarEvents(array $calendarOptions = [])
    {
        $referenceDate = date('Y-m-01');
        if (!empty($calendarOptions)) {
            $referenceDate = date('Y-m-d', strtotime($calendarOptions['initialDate']));
        }

        $dateMinFilter = date('Y-m-01 00:00:00', strtotime($referenceDate));
        $dateMaxFilter = date('Y-m-t 23:59:59', strtotime($referenceDate));

        $optParams = array(
            'maxResults' => 2500,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => $this->dateConvertToGoogleFormat($dateMinFilter),
            'timeMax' => $this->dateConvertToGoogleFormat($dateMaxFilter)
        );

        $results = $this->service->events->listEvents($this->calendarId, $optParams);
        $events = $results->getItems();

        return $events;
    }

    private function getCalendarService()
    {
        $client = $this->googleCalendarApi->getClient();
        return new Google_Service_Calendar($client);
    }

    private function dateConvertToGoogleFormat(string $date)
    {
        return date_format(date_create($date, timezone_open('America/Mexico_City')), 'Y-m-d\TH:i:sP');
    }

    public function editCalendarEvent(array $dataEvent)
    {
        $event = $this->service->events->get($this->calendarId, $dataEvent['googleEventId']);

        $event->setSummary($dataEvent['title']);
        $event->setDescription($dataEvent['description']);
        $event->setStart(
            new Google_Service_Calendar_EventDateTime(
                [
                    'dateTime' => $this->dateConvertToGoogleFormat($dataEvent['startDate']),
                    'timeZone' => 'America/Mexico_City'
                ]
            )
        );
        $event->setEnd(
            new Google_Service_Calendar_EventDateTime(
                [
                    'dateTime' => $this->dateConvertToGoogleFormat($dataEvent['endDate']),
                    'timeZone' => 'America/Mexico_City'
                ]
            )
        );
        $event->setAttendees($dataEvent['users']);

        try {
            $updateEvent = $this->service->events->update($this->calendarId, $event->getId(), $event);
            return ['id' => $updateEvent->id, 'link' => $updateEvent->htmlLink];
        } catch (Exception $e) {
            return [];
        }
    }

    public function addCalendarEvent(array $dataEvent)
    {
        $event = new Google_Service_Calendar_Event([
            'summary' => $dataEvent['title'],
            'description' => $dataEvent['description'],
            'start' => [
                'dateTime' => $this->dateConvertToGoogleFormat($dataEvent['startDate']),
                'America/Mexico_City'
            ],
            'end' => [
                'dateTime' => $this->dateConvertToGoogleFormat($dataEvent['endDate']),
                'America/Mexico_City'
            ],
            'attendees' => $dataEvent['users']
        ]);

        try {
            $event = $this->service->events->insert($this->calendarId, $event);
            return ['id' => $event->id, 'link' => $event->htmlLink];
        } catch (Exception $e) {
            return [];
        }
    }

    public function loadPendingServices()
    {
        $data = [
            'pendingServices' => $this->db->getMyPendingServices()
        ];
        return ['html' => parent::getCI()->load->view('Generales/Agenda/pendingServices.php', $data, TRUE)];
    }

    public function loadProgramServiceForm(array $data)
    {
        $data = [
            'pendingService' => $this->db->getMyPendingServices($data['serviceId'])[0],
            'eventHistory' => $this->db->getEventHistory($data['serviceId'])
        ];
        return ['html' => parent::getCI()->load->view('Generales/Agenda/programServiceForm.php', $data, TRUE)];
    }

    public function saveEvent(array $data)
    {
        $startDate = new DateTime($data['date'] . ' ' . $data['time']);
        $endDate = new DateTime($data['date'] . ' ' . $data['time']);
        $endDate->add(new \DateInterval("PT2H"));
        $dataEvent = [
            'googleEventId' => $data['googleEventId'],
            'title' => $data['title'],
            'description' => $data['description'],
            'startDate' => $startDate->format('Y-m-d H:i:s'),
            'endDate' => $endDate->format('Y-m-d H:i:s'),
            'users' => [
                ['email' => $this->db->getTechnicianEmailByService($data['serviceId'])]
            ]
        ];
        if ($data['googleEventId'] != "") {
            $googleEvent = $this->editCalendarEvent($dataEvent);
        } else {
            $googleEvent = $this->addCalendarEvent($dataEvent);
        }
        if (!empty($googleEvent)) {
            $updateData = [
                'serviceId' => $data['serviceId'],
                'tentative' => $startDate->format('Y-m-d H:i:s'),
                'googleEvent' => $googleEvent,
                'dataEvent' => $dataEvent
            ];
            $this->db->saveCalendarEvent($updateData);
        }

        $calendarOptions = [
            'initialDate' => $startDate->format('Y-m-d H:i:s')
        ];

        return array_merge(['goToDate' => $startDate->format('Y-m-d H:i:s')], $this->loadGoogleEvents($calendarOptions));
    }
}
