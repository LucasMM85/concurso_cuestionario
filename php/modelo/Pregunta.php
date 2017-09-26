<?php

namespace modelo;


class Pregunta implements \JsonSerializable
{

    private $id;
    private $descripcion;
    private $orden;
    private $tipodato;
    private $opciones;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Pregunta
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     * @return Pregunta
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param mixed $orden
     * @return Pregunta
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipodato()
    {
        return $this->tipodato;
    }

    /**
     * @param mixed $tipodato
     * @return Pregunta
     */
    public function setTipodato($tipodato)
    {
        $this->tipodato = $tipodato;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOpciones()
    {
        return $this->opciones;
    }

    /**
     * @param mixed $opciones
     * @return Pregunta
     */
    public function setOpciones($opciones)
    {
        $this->opciones = $opciones;
        return $this;
    }

    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}