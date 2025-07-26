<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filtros
        $deputadosQuery = \App\Models\Deputado::query();
        if ($request->filled('nome')) {
            $deputadosQuery->where('nome', 'like', '%' . $request->nome . '%');
        }
        if ($request->filled('sigla_partido')) {
            $deputadosQuery->where('sigla_partido', $request->sigla_partido);
        }
        if ($request->filled('sigla_uf')) {
            $deputadosQuery->where('sigla_uf', $request->sigla_uf);
        }
        if ($request->filled('deputado')) {
            $deputadosQuery->where('nome', 'like', '%' . $request->deputado . '%');
        }

        $deputadosIds = $deputadosQuery->pluck('id');

        $partidos = \App\Models\Deputado::select('sigla_partido')->distinct()->orderBy('sigla_partido')->pluck('sigla_partido');
        $ufs = \App\Models\Deputado::select('sigla_uf')->distinct()->orderBy('sigla_uf')->pluck('sigla_uf');

        // Ranking deputados que mais gastaram (com filtro)
        $rankingDeputados = \App\Models\Despesa::whereIn('deputado_id', $deputadosIds)
            ->selectRaw('deputado_id, SUM(valor_documento) as total')
            ->groupBy('deputado_id')
            ->orderByDesc('total')
            ->with('deputado') // sem :id,nome
            ->limit(10)
            ->get();

        // Ranking fornecedores (apenas despesas dos deputados filtrados)
        $rankingFornecedores = \App\Models\Despesa::whereIn('deputado_id', $deputadosIds)
            ->selectRaw('cnpj_cpf_fornecedor, nome_fornecedor, SUM(valor_documento) as total')
            ->groupBy('cnpj_cpf_fornecedor', 'nome_fornecedor')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Ranking tipos de despesa (apenas despesas dos deputados filtrados)
        $rankingTipos = \App\Models\Despesa::whereIn('deputado_id', $deputadosIds)
            ->selectRaw('tipo_despesa, SUM(valor_documento) as total')
            ->groupBy('tipo_despesa')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Ranking partidos (apenas despesas dos deputados filtrados)
        $rankingPartidos = \App\Models\Despesa::whereIn('deputado_id', $deputadosIds)
            ->join('deputados', 'despesas.deputado_id', '=', 'deputados.id')
            ->selectRaw('deputados.sigla_partido, SUM(valor_documento) as total')
            ->groupBy('deputados.sigla_partido')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'partidos', 'ufs', 'rankingDeputados', 'rankingFornecedores', 'rankingTipos', 'rankingPartidos'
        ));
    }
}
