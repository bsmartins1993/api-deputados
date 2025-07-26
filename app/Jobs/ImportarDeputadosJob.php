<?php

namespace App\Jobs;

use App\Models\Deputado;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportarDeputadosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = Http::get('https://dadosabertos.camara.leg.br/api/v2/deputados');
            $dados = $response->json()['dados'] ?? [];

            foreach ($dados as $item) {
                Deputado::updateOrCreate(
                    ['id_camara' => $item['id']],
                    [
                        'nome' => $item['nome'],
                        'sigla_partido' => $item['siglaPartido'],
                        'sigla_uf' => $item['siglaUf'],
                        'id_legislatura' => $item['idLegislatura'],
                        'url_foto' => $item['urlFoto'],
                    ]
                );
            }
            Log::info('Deputados importados com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao importar deputados: " . $e->getMessage());
        }
    }
}
