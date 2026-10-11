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
        Schema::create('detalle_prestamos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->unsignedBigInteger('prestamo_id');
            $table->unsignedBigInteger('equipo_id');
            $table->date('fecha_devolucion_real')->nullable();
            $table->string('observaciones')->nullable();
            $table->foreign('prestamo_id')
                ->references('id')
                ->on('prestamos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('equipo_id')
                ->references('id')
                ->on('equipos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_prestamos');
    }
};
