<?php


namespace modelo;


class Opcion implements \JsonSerializable
{

    private $id;
    private $orden;
    private $descripcion;
    private $isSeleccionada;
    private $tipodato;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Opcion
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return Opcion
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
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
     * @return Opcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getisSeleccionada()
    {
        return $this->isSeleccionada;
    }

    /**
     * @param mixed $isSeleccionada
     * @return Opcion
     */
    public function setIsSeleccionada($isSeleccionada)
    {
        $this->isSeleccionada = $isSeleccionada;
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
     * @return Opcion
     */
    public function setTipodato($tipodato)
    {
        $this->tipodato = $tipodato;
        return $this;
    }

    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}