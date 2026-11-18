<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    protected $table = 'evidencias';

    protected $fillable = [
        'publicacion_id',
        'usuario_id',
        'ruta',
        'descripcion',
        'fecha_evidencia',
    ];

    protected function casts(): array
    {
        return [
            'fecha_evidencia' => 'date',
        ];
    }

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class, 'publicacion_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
