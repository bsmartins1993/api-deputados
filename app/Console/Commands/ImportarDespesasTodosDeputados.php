<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Deputado;
use App\Jobs\ImportarDespesasDeputadoJob;
use App\Jobs\ImportarDeputadosJob;
use Illuminate\Support\Facades\Log;

class ImportarDespesasTodosDeputados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'despesas:importar-todos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa despesas de todos os deputados usando jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Iniciando importação de deputados e despesas.');

        $deputados = Deputado::all();
        $jobsDespesas = [];

        foreach ($deputados as $deputado) {
            $jobsDespesas[] = new ImportarDespesasDeputadoJob($deputado->id, $deputado->id_camara);
        }

        Log::info('Disparando job de deputados e encadeando jobs de despesas.');
        ImportarDeputadosJob::withChain($jobsDespesas)->dispatch();

        Log::info('Jobs encadeados disparados: deputados e depois despesas!');
        $this->info('Jobs encadeados disparados: deputados e depois despesas!');
    }
}
