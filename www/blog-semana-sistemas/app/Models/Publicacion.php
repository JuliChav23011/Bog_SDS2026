<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    protected $table = 'publicaciones';

    protected $fillable = [
        'categoria_id',
        'titulo',
        'slug',
        'fecha_actividad',
        'resumen',
        'contenido',
        'estado',
        'fecha_publicacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_actividad' => 'date',
            'fecha_publicacion' => 'datetime',
        ];
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenPublicacion::class, 'publicacion_id');
    }

    public function autores()
    {
        return $this->belongsToMany(
            User::class,
            'publicacion_usuario',
            'publicacion_id',
            'usuario_id'
        )->withTimestamps();
    }

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'publicacion_id');
    }
}
