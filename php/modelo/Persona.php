<?php
namespace modelo;


class Persona implements \JsonSerializable
{

    private $id;
    private $apellidosNombres;
    private $dni;
    private $cuil;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Persona
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApellidosNombres()
    {
        return $this->apellidosNombres;
    }

    /**
     * @param mixed $apellidosNombres
     * @return Persona
     */
    public function setApellidosNombres($apellidosNombres)
    {
        $this->apellidosNombres = $apellidosNombres;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * @param mixed $dni
     * @return Persona
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCuil()
    {
        return $this->cuil;
    }

    /**
     * @param mixed $cuil
     * @return Persona
     */
    public function setCuil($cuil)
    {
        $this->cuil = $cuil;
        return $this;
    }


    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}