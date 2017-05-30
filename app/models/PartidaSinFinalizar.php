<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartidaSinFinalizar extends Model
{
    /**
     * Nombre de la tabla en la base de datos
     * @var string
     */
    protected $table = 'partidassinfinalizar';
    /**
     * Atributos rellenables desde el método fill() génerico de los modelos
     * @var array
     */
    protected $fillable = ['estadoJson', 'usuarioId'];
    /**
     * Deshabilita la necesidad de que en la tabla existan los campos created_at y updated_at
     * @var bool
     */
    public $timestamps = false;
    /**
     * Obtiene el usuario asociado con el registro
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usuario(){
        return $this->hasOne('\App\Models\Usuario', 'id', 'usuarioId');
    }
}
