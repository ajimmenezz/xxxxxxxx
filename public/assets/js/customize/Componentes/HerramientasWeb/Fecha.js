class Fecha {

    formatoFecha(timestamp, mes = '') {
        let newDate, month, date = new Date(parseInt(timestamp));

        let monthNames = [
            "Enero", "Febrero", "Marzo",
            "Abril", "Mayo", "Junio", "Julio",
            "Agosto", "Septiembre", "Octubre",
            "Noviembre", "Diciembre"
        ];

        let day = date.getDate();
        let monthIndex = date.getMonth();
        let year = date.getFullYear();

        if (mes === 'mes') {
            newDate = year + '-' + monthNames[monthIndex] + '-' + day;
        } else {
            if ((monthIndex + 1) < 10) {
                month = '0' + (monthIndex + 1);
                newDate = year + '-' + month + '-' + day;
            } else {

                newDate = year + '-' + (monthIndex + 1) + '-' + day;
            }
        }

        return newDate;
    }
}


