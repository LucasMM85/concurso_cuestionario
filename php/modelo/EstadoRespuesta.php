<?php
/**
 * Created by PhpStorm.
 * User: corvu
 * Date: 23/6/2017
 * Time: 15:26
 */

namespace modelo;


class EstadoRespuesta implements \JsonSerializable
{

    private $idEstado;
    private $mensajeEstado;
    private $fechaHoraFinal;
    private $idOpcionCuestionarioRespondido;

    /**
     * @return mixed
     */
    public function getIdEstado()
    {
        return $this->idEstado;
    }

    /**
     * @param mixed $idEstado
     * @return EstadoRespuesta
     */
    public function setIdEstado($idEstado)
    {
        $this->idEstado = $idEstado;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMensajeEstado()
    {
        return $this->mensajeEstado;
    }

    /**
     * @param mixed $mensajeEstado
     * @return EstadoRespuesta
     */
    public function setMensajeEstado($mensajeEstado)
    {
        $this->mensajeEstado = $mensajeEstado;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaHoraFinal()
    {
        return $this->fechaHoraFinal;
    }

    /**
     * @param mixed $fechaHoraFinal
     * @return EstadoRespuesta
     */
    public function setFechaHoraFinal($fechaHoraFinal)
    {
        $this->fechaHoraFinal = $fechaHoraFinal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdOpcionCuestionarioRespondido()
    {
        return $this->idOpcionCuestionarioRespondido;
    }

    /**
     * @param mixed $idOpcionCuestionarioRespondido
     * @return EstadoRespuesta
     */
    public function setIdOpcionCuestionarioRespondido($idOpcionCuestionarioRespondido)
    {
        $this->idOpcionCuestionarioRespondido = $idOpcionCuestionarioRespondido;
        return $this;
    }

    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}