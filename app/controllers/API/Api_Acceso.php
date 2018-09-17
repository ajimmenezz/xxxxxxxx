<?php

use React\EventLoop\Factory as EventLoop;
use React\Stomp\Factory as Stomp;
use Controladores\Controller_Base as Base;

/*
 * Clase encargada de para enviar el mensaje a rabbitmq.
 */

class Api_Acceso extends Base { 

    private $datosUsuario;
    private $registroUsuario;

    public function __construct() {
        parent::__construct();
        $this->datosUsuario = $this->usuario->getDatosUsuario();
        $this->registroUsuario = \Librerias\Generales\Registro_Usuario::factory();
    }

    public function index() {

        if ($this->input->post('respuestaRegistroLogueo') === "false") {
            $this->registroUsuario->registroSalida($this->input->post('logueo'));
        }

        $loop = EventLoop::create();
        $stomp = new Stomp($loop);

        //No borrar es para el inicio de sesion con socket
//        $stompClient = $stomp->createClient(['host' => '127.0.0.1', 'port' => 61613, 'vhost' => '/', 'login' => 'guest', 'passcode' => 'guest']);
//
//        $stompClient->connect()->then(
//                function ($client) use ($loop) {
//            $client->send('/topic/message', json_encode(array('servicio' => $this->input->post('tipoServicio'), 'usuario' => $this->datosUsuario['Nombre'])));
//            $loop->addTimer(0.5, [$loop, 'stop']);
//        }, function (\Exception $e) use ($loop) {
//            $loop->stop();
//            echo sprintf("No puede conectarse: %s\n", $e->getMessage());
//        }
//        );

        $loop->run();

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'mensaje enviado']));
    }

    public function manejarEvento(string $evento = null) {
        
    }

}
