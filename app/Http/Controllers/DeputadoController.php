<?php
namespace App\Http\Controllers;

use App\Models\Deputado;
use Illuminate\Http\Request;

class DeputadoController extends Controller
{
    public function index(Request $request)
    {
        $query = Deputado::query();

        // Filtros
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }
        if ($request->filled('sigla_partido')) {
            $query->where('sigla_partido', $request->sigla_partido);
        }
        if ($request->filled('sigla_uf')) {
            $query->where('sigla_uf', $request->sigla_uf);
        }
        if ($request->filled('deputado_id')) {
            $query->where('deputado_id', $request->deputado_id);
        }

        $deputados = $query->paginate(20);

        $partidos = Deputado::select('sigla_partido')->distinct()->orderBy('sigla_partido')->pluck('sigla_partido');
        $ufs = Deputado::select('sigla_uf')->distinct()->orderBy('sigla_uf')->pluck('sigla_uf');
        return view('deputados.index', compact('deputados', 'partidos', 'ufs'));
    }

    public function autocomplete(Request $request)
    {
        $termo = $request->get('term');
        $deputados = Deputado::where('nome', 'like', "%$termo%")
            ->limit(10)
            ->pluck('nome');
        return response()->json($deputados);
    }
}
