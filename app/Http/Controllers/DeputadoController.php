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
            $query->where('sigla_partido', 'like', '%' . $request->sigla_partido . '%');
        }
        if ($request->filled('sigla_uf')) {
            $query->where('sigla_uf', 'like', '%' . $request->sigla_uf . '%');
        }

        $deputados = $query->paginate(20);

        return view('deputados.index', compact('deputados'));
    }
}
