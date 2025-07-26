<?php

namespace App\Jobs;

use App\Models\Despesa;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportarDespesasDeputadoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $deputado_id;
    public $id_camara;

    /**
     * Create a new job instance.
     */
    public function __construct($deputado_id, $id_camara)
    {
        $this->deputado_id = $deputado_id;
        $this->id_camara = $id_camara;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info("Iniciando importação de despesas para deputado {$this->id_camara}");

            $url = "https://dadosabertos.camara.leg.br/api/v2/deputados/{$this->id_camara}/despesas";
            $response = Http::get($url);

            if (!$response->successful()) {
                Log::error("Falha ao importar despesas do deputado {$this->id_camara}: " . $response->status());
                return;
            }

            $dados = $response->json()['dados'] ?? [];

            foreach ($dados as $item) {
                \App\Models\Despesa::firstOrCreate(
                    [
                        'deputado_id' => $this->deputado_id,
                        'ano' => $item['ano'],
                        'mes' => $item['mes'],
                        'cod_documento' => $item['codDocumento'],
                    ],
                    [
                        'tipo_despesa' => $item['tipoDespesa'] ?? null,
                        'tipo_documento' => $item['tipoDocumento'] ?? null,
                        'data_documento' => $item['dataDocumento'] ?? null,
                        'num_documento' => $item['numDocumento'] ?? null,
                        'valor_documento' => $item['valorDocumento'] ?? null,
                        'valor_glosa' => $item['valorGlosa'] ?? null,
                        'valor_liquido' => $item['valorLiquido'] ?? null,
                        'nome_fornecedor' => $item['nomeFornecedor'] ?? null,
                        'cnpj_cpf_fornecedor' => $item['cnpjCpfFornecedor'] ?? null,
                        'num_ressarcimento' => $item['numRessarcimento'] ?? null,
                        'url_documento' => $item['urlDocumento'] ?? null,
                    ]
                );
            }
            Log::info("Despesas importadas para deputado {$this->id_camara}");
        } catch (\Exception $e) {
            Log::error("Erro ao importar despesas do deputado {$this->id_camara}: " . $e->getMessage());
        }
    }
}
