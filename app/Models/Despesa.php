<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    protected $table = 'despesas'; // opcional, se o nome da tabela for diferente do padrão

    protected $fillable = [
        'deputado_id',
        'ano',
        'mes',
        'tipo_despesa',
        'cod_documento',
        'tipo_documento',
        'data_documento',
        'num_documento',
        'valor_documento',
        'valor_glosa',
        'valor_liquido',
        'nome_fornecedor',
        'cnpj_cpf_fornecedor',
        'num_ressarcimento',
        'id_legislatura',
        'url_documento',
    ];

    public function deputado()
    {
        return $this->belongsTo(Deputado::class, 'deputado_id');
    }
}
