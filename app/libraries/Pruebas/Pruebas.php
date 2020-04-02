<?php

namespace Librerias\Pruebas;

use Controladores\Controller_Datos_Usuario as General;

class Pruebas extends General
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Modelos\Modelo_Pruebas::factory();
    }

    public function getActivePersonal()
    {
        $personal = $this->db->getActivePersonal();
        echo '
        <table border=1>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Perfil</th>
                    <th>Nombres</th>
                    <th>A. Paterno</th>
                    <th>A. Materno</th>
                    <th>Email</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
        ';
        foreach ($personal as $k => $v) {
            echo '
                <tr>
                    <td>' . $v['Id'] . '</td>
                    <td>' . $v['Perfil'] . '</td>
                    <td>' . $v['Nombres'] . '</td>
                    <td>' . $v['ApPaterno'] . '</td>
                    <td>' . $v['ApMaterno'] . '</td>
                    <td>' . $v['EmailCorporativo'] . '</td>
                    <td><img style="height:120px; width:120px;" src="' . $v['Foto'] . '" /></td>
                </tr>
            ';
        }
        echo '</tbody></table>';
    }

    public function updateBranchesGeocode()
    {
        $branches = $this->db->branches();
        $resultGeocode = [];
        $link = 'https://maps.googleapis.com/maps/api/geocode/json?';
        $apiKey = 'AIzaSyADBNovHdLJ5GEK6szq7cBmCcH9MV2zOEU';

        foreach ($branches as $k => $v) {

            $googleData = json_decode(file_get_contents($link . 'address=' . urlencode($v['Direccion']) . '&key=' . $apiKey), true);

            $this->db->updateBranchGeoloc($v['Id'],$googleData['results'][0]['geometry']['location']['lat'],$googleData['results'][0]['geometry']['location']['lng']);

            array_push($resultGeocode, [
                'Id' => $v['Id'],
                'Sucursal' => $v['Nombre'],
                'Direccion' => $v['Direccion'],
                'Lat' => $googleData['results'][0]['geometry']['location']['lat'],
                'Lng' => $googleData['results'][0]['geometry']['location']['lng']
            ]);
        }

        return $resultGeocode;
    }
}
