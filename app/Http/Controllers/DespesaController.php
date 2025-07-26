<?php
namespace App\Http\Controllers;

use App\Models\Despesa;
use Illuminate\Http\Request;

class DespesaController extends Controller
{
    public function index(Request $request)
    {
        $query = Despesa::query();

        // Filtros
        if ($request->filled('deputado_id')) {
            $query->where('deputado_id', $request->deputado_id);
        }
        if ($request->filled('ano')) {
            $query->where('ano', $request->ano);
        }
        if ($request->filled('mes')) {
            $query->where('mes', $request->mes);
        }
        if ($request->filled('tipo_despesa')) {
            $query->where('tipo_despesa', 'like', '%' . $request->tipo_despesa . '%');
        }

        $despesas = $query->paginate(20);

        return view('despesas.consulta', compact('despesas'));
    }
}
