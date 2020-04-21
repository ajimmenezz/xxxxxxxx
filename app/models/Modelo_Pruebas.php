<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Pruebas extends Modelo_Base
{
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getActivePersonal()
    {
        return $this->consulta("
        select 
        cu.Id,
        (select nombre from cat_perfiles where Id = cu.IdPerfil) as Perfil,
        tp.Nombres,
        tp.ApPaterno,
        tp.ApMaterno,
        cu.EmailCorporativo,
        if(tp.UrlFoto is null or tp.UrlFoto = '', '', concat('http://siccob.solutions/',tp.UrlFoto)) as Foto
        from cat_v3_usuarios cu
        inner join t_rh_personal tp on cu.Id = tp.Idusuario
        where cu.IdPerfil in (39,46,51,52,57,59,64,83) 
        and cu.Flag = 1
        order by Nombre, ApPaterno, ApMaterno");
    }

    public function branches()
    {
        return $this->consulta("
        select
        cs.Id,
        cs.Nombre,
        concat(
            cs.Calle,
            ' ',
            if(NoExt like '%sn%', '', concat('#',cs.NoExt)),
            ', ',
            cc.Nombre,
            ', ',
            cm.Nombre,
            ', ',
            ce.Nombre
        ) as Direccion
        from cat_v3_sucursales cs
        inner join cat_v3_paises cp on cp.Id = cs.IdPais
        inner join cat_v3_estados ce on ce.Id = cs.IdEstado
        inner join cat_v3_municipios cm on cm.Id = cs.IdMunicipio
        inner join cat_v3_colonias cc on cc.Id = cs.IdColonia
        where cs.IdCliente = 1
        and cs.Flag = 1 
        and cs.Latitud is null");
    }

    public function updateBranchGeoloc($branchId, $lat, $lon)
    {
        $this->actualizar("cat_v3_sucursales", [
            'Latitud' => $lat, 
            'Longitud' => $lon
        ], ['Id' => $branchId]);
    }

    public function updateUserGeocode($userId, $lat, $lon)
    {
        $this->actualizar("t_rh_personal", [
            'Latitud' => $lat, 
            'Longitud' => $lon
        ], ['IdUsuario' => $userId]);
    }

    public function users(){
        return $this->consulta("
        select 
        cu.Id,
        nombreUsuario(cu.Id) as Usuario,
        tp.Domicilio
        from cat_v3_usuarios cu
        inner join t_rh_personal tp on cu.Id = tp.IdUsuario
        where cu.Flag  = 1
        and tp.Domicilio is not null
        and tp.Domicilio <> ''
        and Latitud is null");
    }
}
