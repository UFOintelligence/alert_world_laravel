<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('alerts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // Relación con el usuario que la reporta
        $table->string('titulo');
        $table->text('descripcion')->nullable();
        $table->string('tipo_alerta'); // Ej: robo, incendio, etc.
        $table->string('estado')->default('pendiente'); // pendiente, validada, rechazada
        $table->decimal('latitud', 10, 7);
        $table->decimal('longitud', 10, 7);
        $table->string('archivo')->nullable(); // Imagen o audio
        $table->timestamps();

        // Clave foránea
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
