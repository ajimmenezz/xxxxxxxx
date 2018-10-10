<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Documentacion extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function consultaTecnicosCartasResponsivas() {
        $consulta = $this->consulta('SELECT 
                                            cvu.Id,
                                        nombreUsuario(cvu.Id) AS Nombre
                                    FROM
                                        cat_v3_usuarios cvu
                                    INNER JOIN t_responsiva_fondo_fijo trf
                                    ON trf.IdUsuario = cvu.Id
                                    WHERE
                                        cvu.IdPerfil in (57,64)');
        return $consulta;
    }

    public function consultaTecnicoCartaResponsiva(array $datos) {
        $consulta = $this->consulta('SELECT
                                        Id,
                                        nombreUsuario(Id) AS Nombre
                                    FROM
                                        cat_v3_usuarios
                                    WHERE
                                        Id = "' . $datos['IdUsuario'] . '"');
        return $consulta;
    }

    public function guardarFirmaCartaResponsiva(array $datos) {
        $consulta = $this->insertar('t_responsiva_fondo_fijo', $datos);

        if (!empty($consulta)) {
            return parent::connectDBPrueba()->insert_id();
        } else {
            return FALSE;
        }
    }

    public function actualizarFirmaCartaResponsiva(array $datos, array $where) {
        $consulta = $this->actualizar('t_responsiva_fondo_fijo', $datos, $where);
        if (isset($consulta)) {
            return true;
        } else {
            return parent::tipoError();
        }
    }

}
