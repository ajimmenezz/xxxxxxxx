<?php

namespace Librerias\Poliza;

use Controladores\Controller_Datos_Usuario as General;

class Reportes extends General {

    private $Excel;
    private $DBS;
    private $Catalogo;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Loguistica_Seguimiento::factory();
        $this->Excel = new \Librerias\Generales\CExcel();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        parent::getCI()->load->helper(array('FileUpload', 'date'));
    }

    public function consultaSucursalXRegionCliente(array $datos) {
        if (!empty($datos['zona'])) {
            $nuevoListaZonas = array();

            foreach ($datos['zona'] as $key => $value) {
                array_push($nuevoListaZonas, "'" . $value . "'");
            }

            $stringListaZonas = implode(",", $nuevoListaZonas);
            $consulta = $this->DBS->consulta("SELECT Id, Nombre FROM cat_v3_sucursales WHERE IdRegionCliente IN(" . $stringListaZonas . ")");

            return $consulta;
        } else {
            $consulta = $this->Catalogo->catSucursales("3", array('Flag' => '1'));

            return $consulta;
        }
    }

    public function exportaReporteProblemasFaltantesMantenimientos(array $datos = null) {
        $problemasSucursal = $datos['problemasSucursal'];
        $problemasZona = $datos['problemasZona'];
        $problemasAreaAtencion = $datos['problemasAreaAtencion'];
        $problemasEquipo = $datos['problemasEquipo'];
        $problemasSucursalEquipo = $datos['problemasSucursalEquipo'];
        $faltantesSucursal = $datos['faltantesSucursal'];
        $faltantesZona = $datos['faltantesZona'];
        $faltantesAreaAtencion = $datos['faltantesAreaAtencion'];
        $faltantesEquipo = $datos['faltantesEquipo'];
        $faltantesSucursalEquipo = $datos['faltantesSucursalEquipo'];

        /* Begin Hoja 1 */
        //Crea una hoja en la posición 0 y la nombra.
        $this->Excel->createSheet('Problemas por Sucursal', 0);
        //Selecciona la hoja creada y la marca como activa. Todas las modificaciones se harán en está hoja.
        $this->Excel->setActiveSheet(0);
        //Arreglo de los subtitulos de la tabla. La posición es de izquierda a derecha.
        $arrayTitulosProblemasSucursal = [
            'Sucursal',
            'Problemas'];
        //Envía el arreglo de los subtitulos a la hoja activa.
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosProblemasSucursal);
        //Arreglo con el ancho por columna. 
        $arrayWidthProblemasSucursal = [60, 20.14];
        //Envía y setea los anchos de las columnas definidos en el arreglo de los anchos por columna.
        $this->Excel->setColumnsWidth('A', $arrayWidthProblemasSucursal);
        //Arreglo de alineación por columna.
        $arrayAlignProblemasSucursal = ['', 'center'];
        //Envía:
        //La letra donde comienza la tabla
        //El número de fila donde comenzará la tabla -1
        //El contenido en forma de arreglo
        //Boleano que define si la tabla llevará autofiltros o no
        //Arreglo con la alineación de las columnas.
        $this->Excel->setTableContent('A', 1, $problemasSucursal, true, $arrayAlignProblemasSucursal);
        /* End Hoja 1 */

        /* Begin Hoja 2 */
        $this->Excel->createSheet('Problemas por Zona', 1);
        $this->Excel->setActiveSheet(1);
        $arrayTitulosProblemasZona = [
            'Zona',
            'Problemas',
            'Tickets'];
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosProblemasZona);
        $arrayWidthProblemasZona = [18, 33.71, 14];
        $this->Excel->setColumnsWidth('A', $arrayWidthProblemasZona);
        $arrayAlignProblemasZona = ['', 'center', 'center'];
        $this->Excel->setTableContent('A', 1, $problemasZona, true, $arrayAlignProblemasZona);
        /* End Hoja 2 */

        /* Begin Hoja 3 */
        $this->Excel->createSheet('Problemas por Area de Atencion', 2);
        $this->Excel->setActiveSheet(2);
        $arrayTitulosProblemasAreaAtencion = [
            'Área de Atención',
            'Problemas por Área'];
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosProblemasAreaAtencion);
        $arrayWidthProblemasAreaAtencion = [24.71, 37];
        $this->Excel->setColumnsWidth('A', $arrayWidthProblemasAreaAtencion);
        $arrayAlignProblemasAreaAtencion = ['', 'center'];
        $this->Excel->setTableContent('A', 1, $problemasAreaAtencion, true, $arrayAlignProblemasAreaAtencion);
        /* End Hoja 3 */

        /* Begin Hoja 4 */
        $this->Excel->createSheet('Problemas por Equipo', 3);
        $this->Excel->setActiveSheet(3);
        $arrayTitulosProblemasEquipo = [
            'Equipo',
            'Problemas por Equipo',
            'Zona 1',
            'Zona 2',
            'Zona 3',
            'Zona 4'];
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosProblemasEquipo);
        $arrayWidthProblemasEquipo = [50, 29, 12, 12, 12, 12];
        $this->Excel->setColumnsWidth('A', $arrayWidthProblemasEquipo);
        $arrayAlignProblemasEquipo = ['', 'center', 'center', 'center', 'center', 'center'];
        $this->Excel->setTableContent('A', 1, $problemasEquipo, true, $arrayAlignProblemasEquipo);
        /* End Hoja 4 */

        /* Begin Hoja 5 */
        $this->Excel->createSheet('Problemas por Sucursal y Equipo', 4);
        $this->Excel->setActiveSheet(4);
        $arrayTitulosProblemasSucursalEquipo = [
            'Sucursal',
            $datos['thProblemaSucursalEquipo1'],
            $datos['thProblemaSucursalEquipo2'],
            $datos['thProblemaSucursalEquipo3']];
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosProblemasSucursalEquipo);
        $arrayWidthProblemasSucursalEquipo = [31, 40, 40, 40];
        $this->Excel->setColumnsWidth('A', $arrayWidthProblemasSucursalEquipo);
        $arrayAlignProblemasSucursalEquipo = ['', 'center', 'center', 'center', 'center'];
        $this->Excel->setTableContent('A', 1, $problemasSucursalEquipo, true, $arrayAlignProblemasSucursalEquipo);
        /* End Hoja 5 */

        /* Begin Hoja 6 */
        $this->Excel->createSheet('Faltantes por Sucursal', 5);
        $this->Excel->setActiveSheet(5);
        $arrayTitulosFaltantesSucursal = [
            'Sucursal',
            'Equipo Faltantes'];
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosFaltantesSucursal);
        $arrayWidthFaltantesSucursal = [60, 20.14];
        $this->Excel->setColumnsWidth('A', $arrayWidthFaltantesSucursal);
        $arrayAlignFaltantesSucursal = ['', 'center'];
        $this->Excel->setTableContent('A', 1, $faltantesSucursal, true, $arrayAlignFaltantesSucursal);
        /* End Hoja 6 */

        /* Begin Hoja 7 */
        $this->Excel->createSheet('Faltantes por Zona', 6);
        $this->Excel->setActiveSheet(6);
        $arrayTitulosFaltantesZona = [
            'Zona',
            'Equipo Faltantes',
            'Tickets'];
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosFaltantesZona);
        $arrayWidthFaltantesZona = [18, 33.71, 14];
        $this->Excel->setColumnsWidth('A', $arrayWidthFaltantesZona);
        $arrayAlignFaltantesZona = ['', 'center', 'center'];
        $this->Excel->setTableContent('A', 1, $faltantesZona, true, $arrayAlignFaltantesZona);
        /* End Hoja 7 */

        /* Begin Hoja 8 */
        $this->Excel->createSheet('Faltantes por Area de Atencion', 7);
        $this->Excel->setActiveSheet(7);
        $arrayTitulosFaltantesAreaAtencion = [
            'Área de Atención',
            'Equipos Faltantes'];
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosFaltantesAreaAtencion);
        $arrayWidthFaltantesAreaAtencion = [24.71, 37];
        $this->Excel->setColumnsWidth('A', $arrayWidthFaltantesAreaAtencion);
        $arrayAlignFaltantesAreaAtencion = ['', 'center'];
        $this->Excel->setTableContent('A', 1, $faltantesAreaAtencion, true, $arrayAlignFaltantesAreaAtencion);
        /* End Hoja 8 */

        /* Begin Hoja 9 */
        $this->Excel->createSheet('Faltantes por Equipo', 8);
        $this->Excel->setActiveSheet(8);
        $arrayTitulosFaltantesEquipo = [
            'Equipo Faltante',
            'Faltantes por Equipo',
            'Zona 1',
            'Zona 2',
            'Zona 3',
            'Zona 4'];
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosFaltantesEquipo);
        $arrayWidthFaltantesEquipo = [50, 29, 12, 12, 12, 12];
        $this->Excel->setColumnsWidth('A', $arrayWidthFaltantesEquipo);
        $arrayAlignFaltantesEquipo = ['', 'center', 'center', 'center', 'center', 'center'];
        $this->Excel->setTableContent('A', 1, $faltantesEquipo, true, $arrayAlignFaltantesEquipo);
        /* End Hoja 9 */

        /* Begin Hoja 10 */
        $this->Excel->createSheet('Faltantes por Sucursal y Equipo', 9);
        $this->Excel->setActiveSheet(9);
        $arrayTitulosFaltanteSucursalEquipo = [
            'Sucursal',
            $datos['thFaltanteSucursalEquipo1'],
            $datos['thFaltanteSucursalEquipo2'],
            $datos['thFaltanteSucursalEquipo3']];
        $this->Excel->setTableSubtitles('A', 1, $arrayTitulosFaltanteSucursalEquipo);
        $arrayWidthFaltantesSucursalEquipo = [31, 40, 40, 40];
        $this->Excel->setColumnsWidth('A', $arrayWidthFaltantesSucursalEquipo);
        $arrayAlignFaltantesSucursalEquipo = ['', 'center', 'center', 'center', 'center'];
        $this->Excel->setTableContent('A', 1, $faltantesSucursalEquipo, true, $arrayAlignFaltantesSucursalEquipo);
        /* End Hoja 10 */

        $time = date("ymd_H_i_s");
        $nombreArchivo = 'Reportes_Problemas_Faltantes_Mantenimiento_Poliza' . $time . '.xlsx';
        $nombreArchivo = trim($nombreArchivo);
        $ruta = 'storage/Archivos/ReportesPoliza/' . $nombreArchivo;

        //Guarda la hoja envíandole la ruta y el nombre del archivo que se va a guardar.
        $this->Excel->saveFile($ruta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ruta];
    }

    public function getBuscarProductosCompras(array $datos = null) {
        $consulta = $this->DBSAE->consultaBDSAE("SELECT CVE_ART as Clave, DESCR as Nombre 
                                                FROM Empresa03.dbo.INVE03 as Productos 
                                                WHERE Productos.CVE_ART LIKE '%" . strtoupper($datos['producto']) . "%' 
                                                OR Productos.DESCR LIKE '%" . strtoupper($datos['producto']) . "%'
                                                OR Productos.CVE_ART LIKE '%" . $datos['producto'] . "%' 
                                                OR Productos.DESCR LIKE '%" . $datos['producto'] . "%'");
        return $consulta;
    }

    public function mostrarReporteProblemasFaltantesMantenimientos(array $datos) {
        $data = array();
        $nuevoListaZonas = array();
        $nuevoListaSucursales = array();
        $delimitacionSucursales = "";

        foreach ($datos['zonas'] as $key => $value) {
            array_push($nuevoListaZonas, "'" . $value . "'");
        }
        $stringListaZonas = implode(",", $nuevoListaZonas);
        if (!empty($datos['sucursales'])) {
            foreach ($datos['sucursales'] as $key => $value) {
                array_push($nuevoListaSucursales, "'" . $value . "'");
            }
            $stringListaSucursales = implode(",", $nuevoListaSucursales);
            $delimitacionSucursales = "and cs.Id in (" . $stringListaSucursales . ")";
        }
        $data['ProblemasXSucursal'] = $this->DBS->consulta("select 
                                                                        concat(cs.Nombre,' (Z',cs.IdRegionCliente,')') as Nombre,
                                                                        count(*) as Total
                                                                    from v_mantenimientos_problemas_equipo_pendientes vp 
                                                                    inner join t_servicios_ticket tst on vp.IdServicioMantenimiento = tst.Id
                                                                    inner join cat_v3_sucursales cs on vp.IdSucursal = cs.Id
                                                                    where tst.FechaInicio BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' /*Sustituir las fechas*/
                                                                    and cs.IdRegionCliente in (" . $stringListaZonas . ") /*Incluir un string con las zonas separadas por coma*/
                                                                    " . $delimitacionSucursales . "                                                                    
                                                                    group by cs.Id order by Total desc");
        $data['ProblemasXZona'] = $this->DBS->consulta("SELECT
                                                                Zona,
                                                                SUM(Total) as Problemas,
                                                                count(*) as Tickets
                                                                from (
                                                                        select 
                                                                        zona(cs.IdRegionCliente) as Zona,
                                                                        tst.Ticket,
                                                                        count(*) as Total
                                                                        from v_mantenimientos_problemas_equipo_pendientes vp 
                                                                        inner join t_servicios_ticket tst on vp.IdServicioMantenimiento = tst.Id
                                                                        inner join cat_v3_sucursales cs on vp.IdSucursal = cs.Id
                                                                        where tst.FechaInicio BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' /*Sustituir las fechas*/
                                                                        and cs.IdRegionCliente in (" . $stringListaZonas . ") /*Incluir un string con las zonas separadas por coma*/
                                                                        " . $delimitacionSucursales . "                                                                        
                                                                        group by tst.Ticket
                                                                ) as tf group by Zona order by Zona");
        $data['ProblemasXAreaAtencion'] = $this->DBS->consulta("select
                                                                        areaAtencion(vp.IdArea) as Area,
                                                                        count(*) as Total
                                                                        from v_mantenimientos_problemas_equipo_pendientes vp 
                                                                        inner join t_servicios_ticket tst on vp.IdServicioMantenimiento = tst.Id
                                                                        inner join cat_v3_sucursales cs on vp.IdSucursal = cs.Id
                                                                        where tst.FechaInicio BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' /*Sustituir las fechas*/
                                                                        and cs.IdRegionCliente in (" . $stringListaZonas . ") /*Incluir un string con las zonas separadas por coma*/
                                                                        " . $delimitacionSucursales . "                                                                        
                                                                        group by Area order by Total desc");
        $data['ProblemasXEquipo'] = $this->DBS->consulta("select 
                                                                    IdModelo as Id,
                                                                    Equipo,
                                                                    count(*) as Total,
                                                                    sum(if(Zona = 1, 1, 0)) as Zona1,
                                                                    sum(if(Zona = 2, 1, 0)) as Zona2,
                                                                    sum(if(Zona = 3, 1, 0)) as Zona3,
                                                                    sum(if(Zona = 4, 1, 0)) as Zona4
                                                                    from (
                                                                            select
                                                                            IdModelo,
                                                                            modelo(IdModelo) as Equipo,
                                                                            cs.IdRegionCliente as Zona
                                                                            from v_mantenimientos_problemas_equipo_pendientes vp 
                                                                            inner join t_servicios_ticket tst on vp.IdServicioMantenimiento = tst.Id
                                                                            inner join cat_v3_sucursales cs on vp.IdSucursal = cs.Id
                                                                            where tst.FechaInicio BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' /*Sustituir las fechas*/
                                                                            and cs.IdRegionCliente in (" . $stringListaZonas . ") /*Incluir un string con las zonas separadas por coma*/
                                                                            " . $delimitacionSucursales . "                                                                    
                                                                            ) as tf group by Id order by Total desc");
        (empty($data['ProblemasXEquipo'][0]['Id'])) ? $problemaXEquipo1 = "''" : $problemaXEquipo1 = $data['ProblemasXEquipo'][0]['Id'];
        (empty($data['ProblemasXEquipo'][1]['Id'])) ? $problemaXEquipo2 = "''" : $problemaXEquipo2 = $data['ProblemasXEquipo'][1]['Id'];
        (empty($data['ProblemasXEquipo'][2]['Id'])) ? $problemaXEquipo3 = "''" : $problemaXEquipo3 = $data['ProblemasXEquipo'][2]['Id'];
        $data['ProblemasXSucursalEquipo'] = $this->DBS->consulta("select 
                                                                        Sucursal,
                                                                        /*Sustituir por los primeros 3 id de los problemas por equipo*/
                                                                        sum(if(IdModelo = '" . $problemaXEquipo1 . "', Total, 0)) as mod1,
                                                                        sum(if(IdModelo = '" . $problemaXEquipo2 . "', Total, 0)) as mod2,
                                                                        sum(if(IdModelo = '" . $problemaXEquipo3 . "', Total, 0)) as mod3,
                                                                        (SELECT Equipo FROM v_equipos WHERE Id = '" . $problemaXEquipo1 . "') NombreEquipo1,
                                                                        (SELECT Equipo FROM v_equipos WHERE Id = '" . $problemaXEquipo2 . "') NombreEquipo2,
                                                                        (SELECT Equipo FROM v_equipos WHERE Id = '" . $problemaXEquipo3 . "') NombreEquipo3                                                                          
                                                                        from (
                                                                                select
                                                                                sucursal(cs.Id) as Sucursal,
                                                                                count(*) as Total,
                                                                                IdModelo
                                                                                from v_mantenimientos_problemas_equipo_pendientes vp 
                                                                                inner join t_servicios_ticket tst on vp.IdServicioMantenimiento = tst.Id
                                                                                inner join cat_v3_sucursales cs on vp.IdSucursal = cs.Id
                                                                                where tst.FechaInicio BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' /*Sustituir las fechas*/
                                                                                and cs.IdRegionCliente in (" . $stringListaZonas . ") /*Incluir un string con las zonas separadas por coma*/
                                                                                and IdModelo in ('" . $problemaXEquipo1 . "', '" . $problemaXEquipo2 . "', '" . $problemaXEquipo3 . "') /*Sustituir por los primeros 3 id de los problemas por equipo*/
                                                                                group by Sucursal, IdModelo
                                                                                " . $delimitacionSucursales . "
                                                                        ) as tf group by Sucursal order by Sucursal");
        $data['FaltantesXSucursal'] = $this->DBS->consulta("select 
                                                            concat(cs.Nombre,' (Z',cs.IdRegionCliente,')') as Nombre,
                                                            count(*) as Total
                                                            from t_mantenimientos_equipo_faltante tmef
                                                            inner join t_servicios_ticket tst on tmef.IdServicio = tst.Id
                                                            inner join cat_v3_sucursales cs on tst.IdSucursal = cs.Id
                                                            where tst.FechaInicio BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' /*Sustituir las fechas*/
                                                            and tmef.TipoItem = 1
                                                            and cs.IdRegionCliente in (" . $stringListaZonas . ") /*Incluir un string con las zonas separadas por coma*/
                                                            " . $delimitacionSucursales . "
                                                            group by cs.Id order by Total desc");
        $data['FaltantesXZona'] = $this->DBS->consulta("SELECT
                                                                    Zona,
                                                                    SUM(Total) as Faltantes,
                                                                    count(*) as Tickets
                                                                    from (
                                                                            select 
                                                                            zona(cs.IdRegionCliente) as Zona,
                                                                            tst.Ticket,
                                                                            count(*) as Total
                                                                            from t_mantenimientos_equipo_faltante tmef
                                                                            inner join t_servicios_ticket tst on tmef.IdServicio = tst.Id
                                                                            inner join cat_v3_sucursales cs on tst.IdSucursal = cs.Id
                                                                            where tst.FechaInicio BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' /*Sustituir las fechas*/
                                                                            and tmef.TipoItem = 1
                                                                            and cs.IdRegionCliente in (" . $stringListaZonas . ") /*Incluir un string con las zonas separadas por coma*/
                                                                            " . $delimitacionSucursales . "
                                                                            group by tst.Ticket
                                                                    ) as tf group by Zona order by Zona");
        $data['FaltantesXAreaAtencion'] = $this->DBS->consulta("select 
                                                                areaAtencion(tmef.IdArea) as Area,
                                                                count(*) as Total
                                                                from t_mantenimientos_equipo_faltante tmef
                                                                inner join t_servicios_ticket tst on tmef.IdServicio = tst.Id
                                                                inner join cat_v3_sucursales cs on tst.IdSucursal = cs.Id
                                                                where tst.FechaInicio BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' /*Sustituir las fechas*/
                                                                and tmef.TipoItem = 1
                                                                and cs.IdRegionCliente in (" . $stringListaZonas . ") /*Incluir un string con las zonas separadas por coma*/
                                                                " . $delimitacionSucursales . "
                                                                group by tmef.IdArea order by Total desc");
        $data['FaltantesXEquipo'] = $this->DBS->consulta("select 
                                                            IdModelo as Id,
                                                            Equipo,
                                                            count(*) as Total,
                                                            sum(if(Zona = 1, 1, 0)) as Zona1,
                                                            sum(if(Zona = 2, 1, 0)) as Zona2,
                                                            sum(if(Zona = 3, 1, 0)) as Zona3,
                                                            sum(if(Zona = 4, 1, 0)) as Zona4
                                                            from (
                                                                    select
                                                                    IdModelo,
                                                                    modelo(IdModelo) as Equipo,
                                                                    cs.IdRegionCliente as Zona
                                                                    from t_mantenimientos_equipo_faltante tmef
                                                                    inner join t_servicios_ticket tst on tmef.IdServicio = tst.Id
                                                                    inner join cat_v3_sucursales cs on tst.IdSucursal = cs.Id
                                                                    where tst.FechaInicio BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' /*Sustituir las fechas*/
                                                                    and tmef.TipoItem = 1
                                                                    and cs.IdRegionCliente in (" . $stringListaZonas . ") /*Incluir un string con las zonas separadas por coma*/
                                                                    " . $delimitacionSucursales . "
                                                            ) as tf group by Id order by Total desc");
        (empty($data['FaltantesXEquipo'][0]['Id'])) ? $faltantesXEquipo1 = "''" : $faltantesXEquipo1 = $data['FaltantesXEquipo'][0]['Id'];
        (empty($data['FaltantesXEquipo'][1]['Id'])) ? $faltantesXEquipo2 = "''" : $faltantesXEquipo2 = $data['FaltantesXEquipo'][1]['Id'];
        (empty($data['FaltantesXEquipo'][2]['Id'])) ? $faltantesXEquipo3 = "''" : $faltantesXEquipo3 = $data['FaltantesXEquipo'][2]['Id'];
        $data['FaltantesXSucursalEquipo'] = $this->DBS->consulta("select 
                                                                    Sucursal,
                                                                    /*Sustituir por los primeros 3 id de los problemas por equipo*/
                                                                    sum(if(IdModelo = " . $faltantesXEquipo1 . ", Total, 0)) as mod1,
                                                                    sum(if(IdModelo = " . $faltantesXEquipo2 . ", Total, 0)) as mod2,
                                                                    sum(if(IdModelo = " . $faltantesXEquipo3 . ", Total, 0)) as mod3,
                                                                    (SELECT Equipo FROM v_equipos WHERE Id = '" . $faltantesXEquipo1 . "') NombreEquipo1,
                                                                    (SELECT Equipo FROM v_equipos WHERE Id = '" . $faltantesXEquipo2 . "') NombreEquipo2,
                                                                    (SELECT Equipo FROM v_equipos WHERE Id = '" . $faltantesXEquipo3 . "') NombreEquipo3   
                                                                    from (
                                                                            select
                                                                            sucursal(cs.Id) as Sucursal,
                                                                            count(*) as Total,
                                                                            IdModelo
                                                                            from t_mantenimientos_equipo_faltante tmef
                                                                            inner join t_servicios_ticket tst on tmef.IdServicio = tst.Id
                                                                            inner join cat_v3_sucursales cs on tst.IdSucursal = cs.Id
                                                                            where tst.FechaInicio BETWEEN '" . $datos['desde'] . " 00:00:00' and '" . $datos['hasta'] . " 23:59:59' /*Sustituir las fechas*/
                                                                            and tmef.TipoItem = 1
                                                                            and cs.IdRegionCliente in (" . $stringListaZonas . ") /*Incluir un string con las zonas separadas por coma*/
                                                                            " . $delimitacionSucursales . "
                                                                            and IdModelo in ('" . $faltantesXEquipo1 . "', '" . $faltantesXEquipo2 . "', '" . $faltantesXEquipo3 . "') /*Sustituir por los primeros 3 id de los problemas por equipo*/
                                                                            group by Sucursal, IdModelo
                                                                    ) as tf group by Sucursal order by Sucursal");
        return array('formulario' => parent::getCI()->load->view('ReportesPoliza/Modal/ReporteProblemasFaltantesManttos', $data, TRUE), 'datos' => $data);
    }

}
