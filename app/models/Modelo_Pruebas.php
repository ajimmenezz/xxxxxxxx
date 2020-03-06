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

    public function getActivePersonal(){
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
}
