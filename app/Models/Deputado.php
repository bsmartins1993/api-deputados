<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deputado extends Model
{
    protected $table = 'deputados'; // opcional, se o nome da tabela for diferente do padrão

    protected $fillable = [
        'id_camara',
        'nome',
        'sigla_partido',
        'sigla_uf',
        'id_legislatura',
        'url_foto',
    ];

    public function despesas()
    {
        return $this->hasMany(Despesa::class);
    }
}
