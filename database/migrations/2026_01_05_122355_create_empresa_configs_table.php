<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('empresa_configs', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_empresa')->default('OmniERP');
            $table->string('logo_path')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('rnc')->nullable();
            $table->text('footer_text')->nullable();
            $table->timestamps();
        });

        // Insertar un registro por defecto
        DB::table('empresa_configs')->insert([
            'nombre_empresa' => 'OmniERP',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('empresa_configs');
    }
};