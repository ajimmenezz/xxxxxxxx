class Socket {

    constructor() {
        this.host = location.hostname;
        this.socket = new WebSocket(this.abrirConexion());
        this.establecerConexion();
    }

    abrirConexion() {
        if (this.host.includes('siccob.solutions')) {
            return 'wss://siccob.solutions:8081';
        } else if (this.host.includes('pruebas.siccob.solutions')) {
            return 'wss://pruebas.siccob.solutions:8081';
        } else {
            return 'ws://localhost:8081';
        }
    }

    establecerConexion() {
        this.socket.onopen = (e) => {
            this.socket.send('valor');
            console.log('Conexion establecida');
        };
    }
}


