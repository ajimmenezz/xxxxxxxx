<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory as EventLoop;
use React\Socket\Server as Socket;
use React\Stomp\Factory as Stomp;
use Librerias\Websocket\Acceso;
/*
 * Servidor que se encarga de recibir los mensajes de rabbitmq por el puerto 8081 
 */
class Websocket_Acceso extends \CI_Controller {

    public function index() {
        $loop = EventLoop::create();
        $socket = new Socket($loop);
        $stomp = new Stomp($loop);
        $message = new Acceso();
        $websocket = new IoServer(new HttpServer(new WsServer($message)), $socket);

        $socket->listen(8081);

        $stompClient = $stomp->createClient(['host' => '127.0.0.1', 'port' => 61613, 'vhost' => '/', 'login' => 'guest', 'passcode' => 'guest']);

        $stompClient->connect()->then(
                function ($client) use ($message) {
            $client->subscribe('/topic/message', [$message, 'frame']);
        }, function (\Exception $e) use ($loop) {
            $loop->stop();
            echo sprintf("Could not connect: %s\n", $e->getMessage());
        }
        );

        $loop->run();
    }

}
