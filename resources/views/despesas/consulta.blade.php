{{-- filepath: \\wsl.localhost\Ubuntu-22.04\root\api-deputados\resources\views\despesas\consulta.blade.php --}}
@extends('layouts.app')

@section('content')
<h1>Consulta de Despesas</h1>
<form method="GET">
    <input type="number" name="deputado_id" placeholder="ID Deputado" value="{{ request('deputado_id') }}">
    <input type="number" name="ano" placeholder="Ano" value="{{ request('ano') }}">
    <input type="number" name="mes" placeholder="Mês" value="{{ request('mes') }}">
    <input type="text" name="tipo_despesa" placeholder="Tipo Despesa" value="{{ request('tipo_despesa') }}">
    <button type="submit">Buscar</button>
</form>
<table>
    <tr>
        <th>ID</th>
        <th>Deputado</th>
        <th>Ano</th>
        <th>Mês</th>
        <th>Tipo Despesa</th>
        <th>Valor</th>
        <th>Fornecedor</th>
        <th>Documento</th>
    </tr>
    @foreach($despesas as $despesa)
    <tr>
        <td>{{ $despesa->id }}</td>
        <td>{{ $despesa->deputado_id }}</td>
        <td>{{ $despesa->ano }}</td>
        <td>{{ $despesa->mes }}</td>
        <td>{{ $despesa->tipo_despesa }}</td>
        <td>R$ {{ number_format($despesa->valor_documento, 2, ',', '.') }}</td>
        <td>{{ $despesa->nome_fornecedor }}</td>
        <td>
            @if($despesa->url_documento)
                <a href="{{ $despesa->url_documento }}" target="_blank">Ver Documento</a>
            @endif
        </td>
    </tr>
    @endforeach
</table>
{{ $despesas->links() }}
@endsection
