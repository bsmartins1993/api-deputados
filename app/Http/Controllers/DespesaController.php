<?php
namespace App\Http\Controllers;

use App\Models\Despesa;
use Illuminate\Http\Request;

class DespesaController extends Controller
{
    public function consulta(Request $request)
    {
        $query = Despesa::query();

        // Filtro pelo deputado_id (link da página de deputados)
        if ($request->filled('deputado_id')) {
            $query->where('deputado_id', $request->deputado_id);
        }

        // Filtro pelo nome do deputado (autocomplete/filtro manual)
        if ($request->filled('deputado')) {
            $query->whereHas('deputado', function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->deputado . '%');
            });
        }

        // Filtro por mês
        if ($request->filled('mes')) {
            $query->where('mes', $request->mes);
        }

        // Filtro por ano
        if ($request->filled('ano')) {
            $query->where('ano', $request->ano);
        }

        // Filtro por tipo de despesa
        if ($request->filled('tipo_despesa')) {
            $query->where('tipo_despesa', $request->tipo_despesa);
        }

        // Filtro pelo nome do fornecedor
        if ($request->filled('nome_fornecedor')) {
            $query->where('nome_fornecedor', $request->nome_fornecedor);
        }

        // Filtro pelo CNPJ do fornecedor
        if ($request->filled('cnpj_cpf_fornecedor')) {
            $query->where('cnpj_cpf_fornecedor', $request->cnpj_cpf_fornecedor);
        }

        $sort = $request->get('sort', 'ano');
        $order = $request->get('order', 'desc');

        // Ordenação pelo nome do deputado (join antes do clone)
        if ($sort === 'deputado') {
            $query->join('deputados', 'despesas.deputado_id', '=', 'deputados.id')
                  ->orderBy('deputados.nome', $order)
                  ->select('despesas.*');
        } else {
            $query->orderBy($sort, $order);
        }

        // Clone a query para o total depois de todos os filtros e joins
        $totalQuery = clone $query;

        $despesas = $query->paginate(20);

        // Arrays para filtros
        $anos = Despesa::select('ano')->distinct()->orderBy('ano', 'desc')->pluck('ano');
        $tiposDespesa = Despesa::select('tipo_despesa')->distinct()->orderBy('tipo_despesa')->pluck('tipo_despesa');
        $total = $totalQuery->sum('valor_documento');

        return view('despesas.consulta', compact('despesas', 'total', 'anos', 'tiposDespesa'));
    }

    public function index()
    {
        $despesas = Despesa::paginate(20);
        $anos = Despesa::select('ano')->distinct()->orderBy('ano', 'desc')->pluck('ano');
        $tiposDespesa = Despesa::select('tipo_despesa')->distinct()->orderBy('tipo_despesa')->pluck('tipo_despesa');
        $total = Despesa::sum('valor_documento');
        return view('despesas.consulta', compact('despesas', 'anos', 'tiposDespesa', 'total'));
    }
}
