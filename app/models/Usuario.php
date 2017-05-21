<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use Notifiable;
    /**
     * Nombre de la tabla en la base de datos
     * @var string
     */
    protected $table = 'usuarios';
    /**
     * Atributos que no se van a mostrar al devolver los registros de este modelo
     * @var array
     */
    protected $hidden = ['password'];
    /**
     * Atributos con valores por defecto
     * @var array
     */
    protected $attributes = ['habilitado' => true, 'esAdmin' => false];
    /**
     * Atributos rellenables desde el método fill() génerico de los modelos
     * @var array
     */
    protected $fillable = ['nombre', 'apellidos', 'telefono', 'nombreUsuario', 'password', 'email', 'habilitado', 'esAdmin'];
}
