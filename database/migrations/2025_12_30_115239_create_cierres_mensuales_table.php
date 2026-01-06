<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cierres_mensuales', function (Blueprint $table) {
            $table->id();

            // Relación con bancos
            $table->foreignId('banco_id')
                ->constrained('bancos')
                ->onDelete('cascade');

            // Datos del cierre
            $table->date('fecha_cierre');
            $table->decimal('saldo_inicial', 15, 2)->default(0);
            $table->decimal('saldo_final', 15, 2)->default(0);

            // Relación con movimientos
            $table->foreignId('ultimo_movimiento_id')
                ->nullable()
                ->constrained('movimientos')
                ->nullOnDelete(); // equivalente a onDelete('set null')

            // Totales
            $table->decimal('total_ingresos', 15, 2)->default(0);
            $table->decimal('total_egresos', 15, 2)->default(0);
            $table->integer('cantidad_movimientos')->default(0);

            // Índice compuesto con nombre explícito
            $table->index(['fecha_cierre', 'banco_id'], 'cierres_mensuales_fecha_banco_idx');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('cierres_mensuales', function (Blueprint $table) {
            // Eliminar la foreign key
            $table->dropForeign(['ultimo_movimiento_id']);

            // Eliminar las columnas agregadas
            $table->dropColumn([
                'ultimo_movimiento_id',
                'total_ingresos',
                'total_egresos',
                'cantidad_movimientos'
            ]);

            // Eliminar el índice compuesto
            $table->dropIndex('cierres_mensuales_fecha_banco_idx');
        });

        // Finalmente eliminar la tabla completa
        Schema::dropIfExists('cierres_mensuales');
    }
};
