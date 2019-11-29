<?php

namespace Modelos;

use \Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Censo extends Base{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getCensoComponente(string $idSucursal, string $componente) {              
        $consulta = $this->consulta('select 
	tc.Id,
	tc.IdArea,
	tc.IdModelo,
	areaAtencion(tc.IdArea) as Area,
	tc.Punto,
	tet.Equipo,
	tc.Serie
	from t_censos tc
	inner join (
		select
		Id,
		concat(
		linea(lineaByModelo(cme.Id)),
		" ",
		sublinea(sublineaByModelo(cme.Id)),
		" ",
		marca(marcaByModelo(cme.Id)),
		" ",
		Nombre
		) COLLATE utf8_general_ci
		as Equipo
		from cat_v3_modelos_equipo cme
	) as tet on tc.IdModelo = tet.Id

	where tet.Equipo like concat("%","'.$componente.'","%") group by IdModelo;');
        return $consulta;
    }
}
