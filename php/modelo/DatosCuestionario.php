<?php
/**
 * Created by PhpStorm.
 * User: corvu
 * Date: 22/6/2017
 * Time: 11:52
 */

namespace modelo;


class DatosCuestionario implements \JsonSerializable
{

    private $idCuestionario;
    private $persona;
    private $cuit;
    private $fecha;
    private $cuestionario;
    private $tiempo;

    /**
     * @return mixed
     */
    public function getIdCuestionario()
    {
        return $this->idCuestionario;
    }

    /**
     * @param mixed $idCuestionario
     */
    public function setIdCuestionario($idCuestionario)
    {
        $this->idCuestionario = $idCuestionario;
    }

    /**
     * @return mixed
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * @param mixed $persona
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;
    }

    /**
     * @return mixed
     */
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * @param mixed $cuit
     */
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getCuestionario()
    {
        return $this->cuestionario;
    }

    /**
     * @param mixed $cuestionario
     */
    public function setCuestionario($cuestionario)
    {
        $this->cuestionario = $cuestionario;
    }

    /**
     * @return mixed
     */
    public function getTiempo()
    {
        return $this->tiempo;
    }

    /**
     * @param mixed $tiempo
     */
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;
    }

    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}