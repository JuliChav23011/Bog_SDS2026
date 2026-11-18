<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenPublicacion extends Model
{
    protected $table = 'imagenes_publicaciones';

    protected $fillable = [
        'publicacion_id',
        'ruta',
        'descripcion',
        'es_principal',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'es_principal' => 'boolean',
        ];
    }

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class, 'publicacion_id');
    }
}
