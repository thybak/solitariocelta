<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    /**
     * Nombre de la tabla en la base de datos
     * @var string
     */
    protected $table = 'resultados';
    /**
     * Atributos rellenables desde el método fill() génerico de los modelos
     * @var array
     */
    protected $fillable = ['puntos', 'usuarioId'];
    /**
     * Deshabilita la necesidad de que en la tabla existan los campos created_at y updated_at
     * @var bool
     */
    public $timestamps = false;
}
