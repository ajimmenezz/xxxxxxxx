function Socket() {
    var host = location.hostname;

    if (host !== 'siccob.solutions') {
        var socket = new WebSocket('ws://localhost:8081');
    } else if (host === 'pruebas.siccob.solutions') {
        var socket = new WebSocket('ws://pruebas.siccob.solutions:8081');
    } else {
        var socket = new WebSocket('wss://siccob.solutions:8081');
    }

    //Mensaje que la conexion es establecida
    socket.onopen = function (e) {
        console.log("Conexion establecida!");
//        socket.send('argumento');
    };



    this.getSocket = function () {
        return socket;
    };
}

//Recibe el mensaje del socket
Socket.prototype.socketMensaje = function () {
    var socket = this.getSocket();
    socket.onmessage = function (e) {
        var datos = jQuery.parseJSON(e.data);
        var usuario = datos.usuario;

        switch (datos.servicio) {
            case 'acceso':
                $.gritter.add({
                    title: usuario.toUpperCase(),
                    text: 'Sea conectado',
                    image: '/assets/img/user-3.jpg',
                    sticky: false,
                    time: ''
                });
                return false;
                break;
            case 'salir':
                $.gritter.add({
                    title: usuario.toUpperCase(),
                    text: 'A salido del sistema',
                    image: '/assets/img/user-2.jpg',
                    sticky: false,
                    time: ''
                });
                return false;
                break;

            default:

                break;
        }
    };
};

//Envia mensaje al socket
Socket.prototype.enviarMensajeSocket = function () {
    var socket = this.getSocket();
};

