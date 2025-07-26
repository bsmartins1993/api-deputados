{{-- filepath: resources/views/despesas/consulta.blade.php --}}
@extends('layouts.app')

@section('content')
<h1>
    Consulta de Despesas
</h1>
<div class="alert alert-success text-center my-4" style="font-size:1.5rem;">
    <strong>Total das despesas filtradas:</strong>
    R$ {{ number_format($total, 2, ',', '.') }}
</div>
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

@php
    $sort = request('sort', 'ano');
    $order = request('order', 'desc');
    function sortIcon($col, $sort, $order) {
        if ($sort !== $col) return '';
        return '<span>' . ($order === 'asc' ? '▲' : '▼') . '</span>';
    }
@endphp
<table class="table table-striped">
    <thead>
        <tr>
            <th style="padding-right: 40px; min-width: 200px;">
                <a class="sort-link" href="{{ request()->fullUrlWithQuery(['sort' => 'deputado', 'order' => $sort == 'deputado' && $order == 'asc' ? 'desc' : 'asc']) }}">
                    Deputado {!! sortIcon('deputado', $sort, $order) !!}
                </a>
            </th>
            <th>
                <a class="sort-link" href="{{ request()->fullUrlWithQuery(['sort' => 'mes', 'order' => $sort == 'mes' && $order == 'asc' ? 'desc' : 'asc']) }}">
                    Mês {!! sortIcon('mes', $sort, $order) !!}
                </a>
            </th>
            <th>
                <a class="sort-link" href="{{ request()->fullUrlWithQuery(['sort' => 'ano', 'order' => $sort == 'ano' && $order == 'asc' ? 'desc' : 'asc']) }}">
                    Ano {!! sortIcon('ano', $sort, $order) !!}
                </a>
            </th>
            <th>
                <a class="sort-link" href="{{ request()->fullUrlWithQuery(['sort' => 'tipo_despesa', 'order' => $sort == 'tipo_despesa' && $order == 'asc' ? 'desc' : 'asc']) }}">
                    Tipo Despesa {!! sortIcon('tipo_despesa', $sort, $order) !!}
                </a>
            </th>
            <th style="padding-left: 40px; min-width: 120px; text-align: right;">
                <a class="sort-link" href="{{ request()->fullUrlWithQuery(['sort' => 'valor_documento', 'order' => $sort == 'valor_documento' && $order == 'asc' ? 'desc' : 'asc']) }}">
                    Valor {!! sortIcon('valor_documento', $sort, $order) !!}
                </a>
            </th>
            <th>Fornecedor</th>
            <th>Documento</th>
        </tr>
    </thead>
    <tbody>
        @foreach($despesas as $despesa)
        <tr>
            <td style="padding-right: 40px; min-width: 200px; text-align: left;">{{ $despesa->deputado->nome ?? 'N/A' }}</td>
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
            <td>{{ $despesa->ano }}</td>
            <td>{{ $despesa->tipo_despesa }}</td>
            <td style="padding-left: 40px; min-width: 120px; text-align: right;">R$ {{ number_format($despesa->valor_documento, 2, ',', '.') }}</td>
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

<style>
    .sort-link {
        color: inherit !important;
        text-decoration: none !important;
        font-weight: bold;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px; /* espaço entre texto e ícone */
    }
    .sort-link:hover {
        text-decoration: underline;
        color: #198754; /* cor de destaque do Bootstrap */
    }
</style>
