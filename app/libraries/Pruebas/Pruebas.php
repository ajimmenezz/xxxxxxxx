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
}
