<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiDespesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Despesa::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $despesa = Despesa::create($request->all());
        return response()->json($despesa, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Despesa::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $despesa = Despesa::findOrFail($id);
        $despesa->update($request->all());
        return response()->json($despesa, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Despesa::destroy($id);
        return response()->json(null, 204);
    }

    public function importarExternos($id_deputado, $id_camara)
    {
        $url = "https://dadosabertos.camara.leg.br/api/v2/deputados/{$id_camara}/despesas";
        $response = Http::get($url);
        $dados = $response->json()['dados'];

        foreach ($dados as $item) {
            \App\Models\Despesa::updateOrCreate(
                [
                    'deputado_id' => $id_deputado,
                    'ano' => $item['ano'],
                    'mes' => $item['mes'],
                    'cod_documento' => $item['codDocumento'],
                    'tipo_despesa' => $item['tipoDespesa'],
                    'tipo_documento' => $item['tipoDocumento'],
                    'data_documento' => $item['dataDocumento'],
                    'num_documento' => $item['numDocumento'],
                    'valor_documento' => $item['valorDocumento'],
                    'valor_glosa' => $item['valorGlosa'],
                    'valor_liquido' => $item['valorLiquido'],
                    'nome_fornecedor' => $item['nomeFornecedor'],
                    'cnpj_cpf_fornecedor' => $item['cnpjCpfFornecedor'],
                    'num_ressarcimento' => $item['numRessarcimento'],
                    'url_documento' => $item['urlDocumento'],
                ]
            );
        }

        return response()->json(['mensagem' => 'Despesas importadas com sucesso!']);
    }
}
