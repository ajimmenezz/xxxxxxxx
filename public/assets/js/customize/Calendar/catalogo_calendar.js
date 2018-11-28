var apiKey = 'AIzaSyALJX2tHXmF1vp_SCOowVY7HvJgF7hMBZ0';
var discoveryDocs = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];
var clientId = '251455867089-i5pdf36ctnr159sgm47vredtif4g97kr.apps.googleusercontent.com';
var scope = 'https://www.googleapis.com/auth/calendar';

function handleClientLoad() {
    var recurso = arguments[0];
    var clickBoton = arguments[1];

    gapi.load('client:auth2', {
        callback: function () {
            // inicialización gapi.client.
            if(clickBoton){
                initClient(recurso);
            }else{
                console.log("NA");
            }
        },
        onerror: function () {
            // Error de carga.
            alert('gapi.client fallo la carga!');
        },
        timeout: 7000, // 7 seconds.
        ontimeout: function () {
            // Tiempo de espera.
            alert('gapi.client no funciono!');
        }
    });
}

function initClient() {
    var recurso = arguments[0];
    gapi.client.init({
        apiKey: apiKey,
        client_id: clientId,
        discoveryDocs: discoveryDocs,
        scope: scope
    }).then(function () {
        updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get(),recurso);
    });
}

function updateSigninStatus(isSignedIn,recurso = null) {
    if (isSignedIn) {
//        console.log("sesion true");
        makeRequest(recurso);
    } else {
        handleAuthClick(recurso);
        console.log("sesion false");
//        console.log("no esta logeado " + isSignedIn);
    }
}

function handleAuthClick(event) {
    var recurso = arguments[0];
    gapi.auth2.getAuthInstance().signIn().then(
                result => makeRequest(recurso),
                e => console.log(`Cerro pop antes de dar permiso o iniciar sesion`)
            );
}

function makeRequest() {
    var recurso = arguments[0];
    gapi.client.request({
        'path': '/calendar/v3/calendars/primary/events',
        'method': 'POST',
        'body': recurso
    }).then(
//        writeResponse(resp.result);
        result => console.log(),
        e => console.log(`No registro evento`)
    );
}
