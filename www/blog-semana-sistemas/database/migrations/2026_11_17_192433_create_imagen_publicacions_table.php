
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imagenes_publicaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('publicacion_id')
                ->constrained('publicaciones')
                ->cascadeOnDelete();

            $table->string('ruta');
            $table->string('descripcion')->nullable();
            $table->boolean('es_principal')->default(false);
            $table->integer('orden')->default(0);

            $table->timestamps();
        });

        DB::statement("
            CREATE UNIQUE INDEX imagen_principal_por_publicacion
            ON imagenes_publicaciones (publicacion_id)
            WHERE es_principal = true
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('imagenes_publicaciones');
    }
};
