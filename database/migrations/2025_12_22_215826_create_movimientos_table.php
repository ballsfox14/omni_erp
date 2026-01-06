<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banco_id')->constrained('bancos')->onDelete('cascade');
            $table->timestamp('fecha');
            $table->foreignId('tipo_movimiento_id')->constrained('tipos_movimientos')->onDelete('restrict');
            $table->string('concepto', 200);
            $table->decimal('monto_debe', 15, 2)->default(0);
            $table->decimal('monto_haber', 15, 2)->default(0);
            $table->decimal('saldo_anterior', 15, 2)->default(0);
            $table->decimal('saldo_posterior', 15, 2)->default(0);
            $table->string('referencia', 100)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices para mejor rendimiento en consultas frecuentes
            $table->index(['fecha', 'banco_id']);
            $table->index(['banco_id', 'fecha']);
            $table->index('tipo_movimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};