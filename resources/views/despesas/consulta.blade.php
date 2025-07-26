{{-- filepath: resources/views/despesas/consulta.blade.php --}}
@extends('layouts.app')

@section('content')
<h1>
    Consulta de Despesas
    <span class="badge bg-success" style="font-size:1rem;">
        Total: R$ {{ number_format($total, 2, ',', '.') }}
    </span>
</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col">
        <input type="text" class="form-control" id="deputado" name="deputado" placeholder="Nome do Deputado" value="{{ request('deputado') }}">
    </div>
    <div class="col">
        <select class="form-control" name="mes">
            <option value="">Mês</option>
            @foreach([
                1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
            ] as $num => $nome)
                <option value="{{ $num }}" {{ request('mes') == $num ? 'selected' : '' }}>{{ $nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col">
        <select class="form-control" name="ano">
            <option value="">Ano</option>
            @foreach($anos as $ano)
                <option value="{{ $ano }}" {{ request('ano') == $ano ? 'selected' : '' }}>{{ $ano }}</option>
            @endforeach
        </select>
    </div>
    <div class="col">
        <select class="form-control" name="tipo_despesa">
            <option value="">Tipo Despesa</option>
            @foreach($tiposDespesa as $tipo)
                <option value="{{ $tipo }}" {{ request('tipo_despesa') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
            @endforeach
        </select>
    </div>
    <div class="col">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="{{ url('/consulta-despesas') }}" class="btn btn-secondary">Limpar</a>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Deputado</th>
            <th>Ano</th>
            <th>Mês</th>
            <th>Tipo Despesa</th>
            <th>Valor</th>
            <th>Fornecedor</th>
            <th>Documento</th>
        </tr>
    </thead>
    <tbody>
        @foreach($despesas as $despesa)
        <tr>
            <td>{{ $despesa->deputado->nome ?? 'N/A' }}</td>
            <td>{{ $despesa->ano }}</td>
            <td>
                @php
                    $meses = [
                        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                    ];
                @endphp
                {{ $meses[$despesa->mes] ?? $despesa->mes }}
            </td>
            <td>{{ $despesa->tipo_despesa }}</td>
            <td>R$ {{ number_format($despesa->valor_documento, 2, ',', '.') }}</td>
            <td>{{ $despesa->nome_fornecedor }}</td>
            <td>
                @if($despesa->url_documento)
                    <a href="{{ $despesa->url_documento }}" target="_blank" class="btn btn-sm btn-info">Ver Documento</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $despesas->links() }}
@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
$(function() {
    $("#deputado").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "{{ url('/deputados/autocomplete') }}",
                data: { term: request.term },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2
    });
});
</script>
@endpush
