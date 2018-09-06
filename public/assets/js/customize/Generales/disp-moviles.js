$(function () {

    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    var activeInfoWindow;
    var markers = [];    

    function initMap() {
        var location = new google.maps.LatLng(19.362531, -99.182961);
        var mapCanvas = document.getElementById('map');
        var mapOptions = {
            center: location,
            zoom: 5,
            panControl: false
        }

        var map = new google.maps.Map(mapCanvas, mapOptions);
        var bounds = new google.maps.LatLngBounds();

        var contentString = '';
        var usuariosLista = [];
        var markers = [];
        var infoWindows = [];
        evento.enviarEvento('/Generales/Dispositivos/GetMiradoreInfo', {}, '#panelMapa', function (respuesta) {
            var cont = 0;
            $.each(respuesta.data.Items.Device, function (k, v) {
                if (typeof v.User === 'undefined'){
                    return true;
                }
                if (typeof v.ReportedLocation !== 'undefined') {

                    cont++;

                    var foto = "/assets/img/siccob-logo.png";
                    if (v.User.Email in respuesta.fotos) {
                        foto = respuesta.fotos[v.User.Email];
                    }
                    var icon = {
                        url: foto, // url
                        scaledSize: new google.maps.Size(42, 42), // scaled size
                        origin: new google.maps.Point(0, 0), // origin
                        anchor: new google.maps.Point(0, 0) // anchor
                    };

                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(v.ReportedLocation.Latitude, v.ReportedLocation.Longitude),
                        map: map,
                        icon: icon
                    });

                    var loc = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
                    bounds.extend(loc);

                    markers[cont] = marker;

                    var fecha = new Date(v.ReportedLocation.FixTime);
                    fecha.setHours(fecha.getHours() - 5);
                    var datestring = ("0" + fecha.getDate()).slice(-2) + "/" + ("0" + (fecha.getMonth() + 1)).slice(-2) + "/" +
                            fecha.getFullYear() + " " + ("0" + fecha.getHours()).slice(-2) + ":" + ("0" + fecha.getMinutes()).slice(-2);

                    var stringPhone = 'Sin número registrado.';
                    if (typeof v.InvSIM !== 'undefined') {
                        stringPhone = '<a href="tel:' + v.InvSIM.PhoneNumber + '">' + v.InvSIM.PhoneNumber + '</a>';
                    }

                    contentString = '<div class="info-window">' +
                            '   <h4 class="text-center">' + v.User.Firstname + ' ' + v.User.Lastname + '</h4>' +
                            '   <div class="info-content">' +
                            '       <table class="table table-stripped">' +
                            '           <tr>' +
                            '               <td colspan="2">Fecha de ubicación: ' + datestring + ' hrs</td>' +
                            '           </tr>' +
                            '           <tr>' +
                            '               <td class="pull-right"><strong>Correo:</strong></td>' +
                            '               <td><a href="mailto:' + v.User.Email + '" target="_top">' + v.User.Email + '</a></td>' +
                            '           </tr>' +
                            '           <tr>' +
                            '               <td class="pull-right"><strong>Calle:</strong></td>' +
                            '               <td>' + v.ReportedLocation.StreetAddress + '</td>' +
                            '           </tr>' +
                            '           <tr>' +
                            '               <td class="pull-right"><strong>Código Postal:</strong></td>' +
                            '               <td>' + v.ReportedLocation.ZipCode + '</td>' +
                            '           </tr>' +
                            '           <tr>' +
                            '               <td class="pull-right"><strong>Ciudad:</strong></td>' +
                            '               <td>' + v.ReportedLocation.City + '</td>' +
                            '           </tr>' +
                            '           <tr>' +
                            '               <td class="pull-right"><strong>País:</strong></td>' +
                            '               <td>' + v.ReportedLocation.Country + '</td>' +
                            '           </tr>' +
                            '       </table>' +
                            '   </div>' +
                            '</div>';

                    var infowindow = new google.maps.InfoWindow({
                        content: contentString,
                        maxWidth: 400
                    });

                    infoWindows[cont] = infowindow;

                    marker.addListener('click', function () {
                        if (activeInfoWindow) {
                            activeInfoWindow.close();
                        }
                        infowindow.open(map, marker);
                        activeInfoWindow = infowindow;
                        map.panTo(this.getPosition());
                    });

                    usuariosLista.push({
                        'num': cont,
                        'nombre': v.User.Firstname + ' ' + v.User.Lastname,
                        'mail': v.User.Email,
                    });
                }
            });

            usuariosLista.sort(orderByName);
            $("#listUsuarios").empty().append('<option value="">Seleccionar . . .</option>')
            $.each(usuariosLista, function (k, v) {
                $("#listUsuarios").append('<option value="' + v.num + '">' + v.nombre + '</option>');
            });
            select.crearSelect('select');
            

            $("#listUsuarios").change(function () {
                if (activeInfoWindow) {
                    activeInfoWindow.close();
                }
                var userId = $(this).val();
                if (userId == "") {
                    $.each(markers, function (k, v) {
                        if (typeof v !== 'undefined') {
                            markers[k].setVisible(true);
                        }
                    });                    
                    map.fitBounds(bounds);                    
                    map.panToBounds(bounds);
                } else {
                    $.each(markers, function (k, v) {
                        if (typeof v !== 'undefined') {
                            markers[k].setVisible(false);
                        }
                    });
                    markers[userId].setVisible(true);
                    infoWindows[userId].open(map, markers[userId]);
                    activeInfoWindow = infoWindows[userId];
                    map.setZoom(17);
                    map.panTo(markers[userId].getPosition());
                }
            });
        });
    }

    google.maps.event.addDomListener(window, 'load', initMap);

    function orderByName(a, b) {
        if (a.nombre < b.nombre)
            return -1;
        if (a.nombre > b.nombre)
            return 1;
        return 0;
    }


});