<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use Notifiable;
    /**
     * Nombre de la tabla de usuarios
     * @var string
     */
    protected $table = 'usuarios';

    protected $hidden = [
        'clavePaso'
    ];

    protected $attributes = ['habilitado' => true, 'esAdmin' => false];

    //protected $guarded = ['id'];

    protected $fillable = ['nombreUsuario', 'clavePaso', 'email', 'habilitado', 'esAdmin'];
}
