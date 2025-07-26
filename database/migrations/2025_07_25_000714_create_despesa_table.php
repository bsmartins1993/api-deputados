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
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deputado_id'); // FK para deputados
            $table->string('ano');
            $table->string('mes');
            $table->string('tipo_despesa');
            $table->string('cod_documento')->nullable();
            $table->string('tipo_documento')->nullable();
            $table->date('data_documento')->nullable();
            $table->string('num_documento')->nullable();
            $table->decimal('valor_documento', 12, 2)->nullable();
            $table->decimal('valor_glosa', 12, 2)->nullable();
            $table->decimal('valor_liquido', 12, 2)->nullable();
            $table->string('nome_fornecedor')->nullable();
            $table->string('cnpj_cpf_fornecedor', 20)->nullable();
            $table->string('num_ressarcimento')->nullable();
            $table->string('url_documento')->nullable();
            $table->timestamps();

            $table->foreign('deputado_id')->references('id')->on('deputados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
