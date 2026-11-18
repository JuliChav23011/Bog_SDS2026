
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publicaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->restrictOnDelete();

            $table->string('titulo', 255);
            $table->string('slug', 280)->unique();
            $table->date('fecha_actividad');
            $table->string('resumen', 300)->nullable();
            $table->text('contenido');

            $table->string('estado', 20)->default('borrador');
            $table->timestamp('fecha_publicacion')->nullable();

            $table->timestamps();

            $table->index(['estado', 'fecha_actividad']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publicaciones');
    }
};
