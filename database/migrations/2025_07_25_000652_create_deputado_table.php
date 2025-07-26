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
        Schema::create('deputados', function (Blueprint $table) {
            $table->id(); // id local
            $table->integer('id_camara')->unique(); // id da Câmara
            $table->string('nome');
            $table->string('sigla_partido', 20)->nullable();
            $table->string('sigla_uf', 5)->nullable();
            $table->integer('id_legislatura')->nullable();
            $table->string('url_foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deputados');
    }
};
