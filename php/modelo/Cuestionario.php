<?php

namespace modelo;

use modelo\Pregunta;

require_once("../modelo/Pregunta.php");


class Cuestionario implements \JsonSerializable
{

    private $idCuestionarioRespondido;
    private $persona;
    private $temario;
    private $preguntas;
    private $fechaHoraInicio;
    private $fechaHoraFinal;
    private $tiempoRestante;
    private $unidadTiempo;
    private $txUnidadTiempo;
    private $txTiempoRestante;
    private $estadoCuestionarioRespondido;
    private $mensaje;

    /**
     * @return mixed
     */
    public function getIdCuestionarioRespondido()
    {
        return $this->idCuestionarioRespondido;
    }

    /**
     * @param mixed $idCuestionarioRespondido
     * @return Cuestionario
     */
    public function setIdCuestionarioRespondido($idCuestionarioRespondido)
    {
        $this->idCuestionarioRespondido = $idCuestionarioRespondido;
        return $this;
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
     * @return Cuestionario
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemario()
    {
        return $this->temario;
    }

    /**
     * @param mixed $temario
     * @return Cuestionario
     */
    public function setTemario($temario)
    {
        $this->temario = $temario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreguntas()
    {
        return $this->preguntas;
    }

    /**
     * @param mixed $preguntas
     * @return Cuestionario
     */
    public function setPreguntas($preguntas)
    {
        $this->preguntas = $preguntas;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaHoraInicio()
    {
        return $this->fechaHoraInicio;
    }

    /**
     * @param mixed $fechaHoraInicio
     * @return Cuestionario
     */
    public function setFechaHoraInicio($fechaHoraInicio)
    {
        $this->fechaHoraInicio = $fechaHoraInicio;
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
     * @return Cuestionario
     */
    public function setFechaHoraFinal($fechaHoraFinal)
    {
        $this->fechaHoraFinal = $fechaHoraFinal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTiempoRestante()
    {
        return $this->tiempoRestante;
    }

    /**
     * @param mixed $tiempoRestante
     * @return Cuestionario
     */
    public function setTiempoRestante($tiempoRestante)
    {
        $this->tiempoRestante = $tiempoRestante;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnidadTiempo()
    {
        return $this->unidadTiempo;
    }

    /**
     * @param mixed $unidadTiempo
     * @return Cuestionario
     */
    public function setUnidadTiempo($unidadTiempo)
    {
        $this->unidadTiempo = $unidadTiempo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTxUnidadTiempo()
    {
        return $this->txUnidadTiempo;
    }

    /**
     * @param mixed $txUnidadTiempo
     * @return Cuestionario
     */
    public function setTxUnidadTiempo($txUnidadTiempo)
    {
        $this->txUnidadTiempo = $txUnidadTiempo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTxTiempoRestante()
    {
        return $this->txTiempoRestante;
    }

    /**
     * @param mixed $txTiempoRestante
     * @return Cuestionario
     */
    public function setTxTiempoRestante($txTiempoRestante)
    {
        $this->txTiempoRestante = $txTiempoRestante;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstadoCuestionarioRespondido()
    {
        return $this->estadoCuestionarioRespondido;
    }

    /**
     * @param mixed $estadoCuestionarioRespondido
     * @return Cuestionario
     */
    public function setEstadoCuestionarioRespondido($estadoCuestionarioRespondido)
    {
        $this->estadoCuestionarioRespondido = $estadoCuestionarioRespondido;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * @param mixed $mensaje
     * @return Cuestionario
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
        return $this;
    }

    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}