<?php

namespace Librerias\V2\PaquetesTicket;
use Modelos\Modelo_ServicioGeneralRedes as Modelo;

class Material{
    
    private $DBServicioGeneralRedes;
    private $id;
    public function __construct($idTecnico) {
        $this->DBServicioGeneralRedes=new Modelo();
        $this->id=$idTecnico;
        $this->getMaterialTecnico();
    }
    
    public function getMaterialTecnico() {
        $query="Select * from tableselect 
                    tInv.Cantidad as Cantidad,
                    tInv.Serie as Serie,
                    almVirt.Nombre as Almacen,
                    almVirt.IdTipoAlmacen as TipoAlmacen,
                    usuario.idPerfil as Perfil,
                    usuario.Nombre as Usuario,
                    usuario.emailCorporativo as email,
                    equiposSae.Nombre as equipo,
                    equiposSae.Clave as ClaveEquipo,
                    equiposSae.Linea as linea,
                    equiposSae.Unidad as unidad
                from 
                        t_inventario tInv
                join
                        cat_v3_almacenes_virtuales as almVirt
                on
                        almVirt.id = tInv.IdAlmacen
                join 
                        cat_v3_usuarios usuario
                on
                        almVirt.IdResponsable=usuario.Id
                join
                        cat_v3_equipos_sae equiposSae
                on
                        equiposSae.id=tInv.IdTipoProducto
                where 
                        tInv.IdEstatus=17
                AND     
                        usuario.id="+$this->id+"
                        ;";
        $consulta=$this->DBServicioGeneralRedes->consulta($query);
        
    }
    
}
