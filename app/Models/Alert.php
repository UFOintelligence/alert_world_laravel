<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Alert extends Model
{
  protected $fillable = [
    'user_id',
    'titulo',
    'tipo_alerta',
    'descripcion',
    'latitud',
    'longitud',
    'archivo',
    'estado',
];


    /** @use HasFactory<\Database\Factories\AlertFactory> */
    use HasFactory;
    // App\Models\Alert.php
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

}
