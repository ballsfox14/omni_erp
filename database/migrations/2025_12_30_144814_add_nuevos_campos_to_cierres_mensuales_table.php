<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cierres_mensuales', function (Blueprint $table) {
            $table->foreignId('ultimo_movimiento_id')->nullable()->after('banco_id')
                  ->constrained('movimientos')->onDelete('set null');
            $table->decimal('total_ingresos', 15, 2)->default(0)->after('saldo_final');
            $table->decimal('total_egresos', 15, 2)->default(0)->after('total_ingresos');
            $table->integer('cantidad_movimientos')->default(0)->after('total_egresos');
            $table->index(['fecha_cierre', 'banco_id']);
        });
    }

    public function down(): void
    {
        Schema::table('cierres_mensuales', function (Blueprint $table) {
            $table->dropForeign(['ultimo_movimiento_id']);
            $table->dropColumn(['ultimo_movimiento_id', 'total_ingresos', 'total_egresos', 'cantidad_movimientos']);
            $table->dropIndex(['fecha_cierre', 'banco_id']);
        });
    }
};