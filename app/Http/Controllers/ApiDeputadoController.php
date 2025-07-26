<?php

namespace App\Http\Controllers;

use App\Models\Deputado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiDeputadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Deputado::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $deputado = Deputado::create($request->all());
        return response()->json($deputado, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Deputado::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $deputado = Deputado::findOrFail($id);
        $deputado->update($request->all());
        return response()->json($deputado, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Deputado::destroy($id);
        return response()->json(null, 204);
    }

    // Retorna as despesas de um deputado
    public function despesas($id)
    {
        $deputado = Deputado::findOrFail($id);
        return $deputado->despesas;
    }

    // Importa deputados de uma fonte externa
    public function importarExternos()
    {
        $response = Http::get('https://dadosabertos.camara.leg.br/api/v2/deputados');
        $dados = $response->json()['dados'];

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

        return response()->json(['mensagem' => 'Deputados importados com sucesso!']);
    }
}
