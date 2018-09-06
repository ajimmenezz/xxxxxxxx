<?php
namespace Librerias\Websocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use React\Stomp\Protocol\Frame;

class Acceso implements MessageComponentInterface{

    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
//        $conn->user = new \stdClass();
//        $conn->user->Id = '12';
        $this->clients->attach($conn);
        
        //obteniendo el id del usuario
//        $conn->send('la conexion enviada es '.$conn->resourceId);
        echo "Conexion Nueva! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // $numRecv = count($this->clients) - 1;
        // echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
        //     , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        // foreach ($this->clients as $client)
        // {
        //     if ($from !== $client)
        //     {
        //         $client->send($msg);
        //         $client->UserId = ss;
        //     }
        // }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "La conexion {$conn->resourceId} se ha desconectado\n";
    }

    public function frame(Frame $frame) {
        foreach ($this->clients as $client) {
            $client->send($frame->body);
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

}
