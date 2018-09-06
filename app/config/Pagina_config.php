<?php

$config['Administrador/Dashboard'] = array(
    array('TiposProyectos', 'DBP', 'getTiposProyecto'),
    array('Sucursales', 'DBP', 'getSucursales'),
    array('Lideres', 'DBP', 'getLideres'),
    array('ProyectosSinAtender', 'DBP', 'getProyectosSinAtender')
);

$config['Proyectos/Nuevo'] = array(
    array('TiposProyectos', 'DBP', 'getTiposProyecto'),
    array('Sucursales', 'DBP', 'getSucursales'),
    array('Lideres', 'DBP', 'getLideres'),
    array('ProyectosSinAtender', 'DBP', 'getProyectosSinAtender')
);
