<?php

namespace App\Traits;

use DateTimeInterface;

/**
 * Trait para la serialización de fechas de los modelos.
 */
trait TimestampSerializableTrait {

    /**
     * Asigna un formato específico a los campos de fecha.
     * 
     * @param DateTimeInterface $date Valor de la fecha.
     * @return string Fecha formateada.
     */
    protected function serializeDate(DateTimeInterface $date){
        return $date->format('Y-m-d H:i:s');
    }

}