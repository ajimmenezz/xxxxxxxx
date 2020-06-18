<?php

namespace Librerias\Componentes;

class Coleccion {

    private $elemento = array();
    private $nombreColeccion;

    public function __construct(string $nombre = '') {
        $this->nombreColeccion = $nombre;               
    }

    public function agregar(string $clave, $objeto) {
        if ($clave == null) {
            $this->elemento[] = $objeto;
        } else {
            if (isset($this->elemento[$clave])) {                
                throw new \Exception("La clave $clave ya esta en uso en la colección $this->nombreColeccion ." );
            } else {
                $this->elemento[$clave] = $objeto;
            }
        }
    }

    public function borrar(string $clave) {
        if (isset($this->elemento[$clave])) {
            unset($this->elemento[$clave]);
        } else {
            throw new \Exception("Invalida la $clave no existe en la colección $this->nombreColeccion.");
        }
    }

    public function obtenerElemento(string $clave) {
        if (isset($this->elemento[$clave])) {
            return $this->elemento[$clave];
        } else {
            throw new \Exception("Invalida la $clave no existe en la colección $this->nombreColeccion.");
        }
    }
    
    public function actualizarElemento(string $clave, $valor) {
        if (isset($this->elemento[$clave])) {
            $this->elemento[$clave] = $valor;
        } else {
            throw new \Exception("Invalida la $clave no existe en la colección $this->nombreColeccion.");
        }
    }

    public function elementosKeys() {
        return array_keys($this->elemento);
    }
    
    public function elementos() {
        return $this->elemento;
    }

    public function longitud() {
        return count($this->elemento);
    }
    
    public function limpiarColeccion(){
        $this->elemento = array();
    }

}
