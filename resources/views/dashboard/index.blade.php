@extends('layouts.app')

@section('content')
<h1>Dashboard - Deputados</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col">
        <input type="text" class="form-control" name="deputado" id="deputado" placeholder="Nome do Deputado" value="{{ request('deputado') }}">
    </div>
    <div class="col">
        <select class="form-control" name="sigla_partido">
            <option value="">Partido</option>
            @foreach($partidos as $partido)
                <option value="{{ $partido }}" {{ request('sigla_partido') == $partido ? 'selected' : '' }}>{{ $partido }}</option>
            @endforeach
        </select>
    </div>
    <div class="col">
        <select class="form-control" name="sigla_uf">
            <option value="">UF</option>
            @foreach($ufs as $uf)
                <option value="{{ $uf }}" {{ request('sigla_uf') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
            @endforeach
        </select>
    </div>
    <div class="col">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="{{ url('/dashboard') }}" class="btn btn-secondary">Limpar</a>
    </div>
</form>

<div class="row">
    <div class="col-md-3">
        <h3 class="text-center">Deputados que mais gastaram</h3>
        <ul class="list-group">
            @foreach($rankingDeputados as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ url('/consulta-despesas?deputado_id=' . $item->deputado_id) }}">
                        {{ $item->deputado->nome ?? 'N/A' }}
                    </a>
                    <span class="badge bg-success">R$ {{ number_format($item->total, 2, ',', '.') }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="col-md-3">
        <h3 class="text-center">Fornecedores</h3>
        <ul class="list-group">
            @foreach($rankingFornecedores as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ url('/consulta-despesas?' .
    http_build_query([
        'cnpj_cpf_fornecedor' => $item->cnpj_cpf_fornecedor,
        'sigla_partido' => request('sigla_partido'),
        'sigla_uf' => request('sigla_uf'),
        'deputado' => request('deputado')
    ])
) }}">
                        {{ $item->nome_fornecedor }}
                    </a>
                    <span class="badge bg-info">R$ {{ number_format($item->total, 2, ',', '.') }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="col-md-3">
        <h3 class="text-center">Tipos de Despesa</h3>
        <ul class="list-group">
            @foreach($rankingTipos as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ url('/consulta-despesas?tipo_despesa=' . urlencode($item->tipo_despesa)) }}">
                        {{ $item->tipo_despesa }}
                    </a>
                    <span class="badge bg-warning text-dark">R$ {{ number_format($item->total, 2, ',', '.') }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="col-md-3">
        <h3 class="text-center">Partidos que mais gastaram</h3>
        <ul class="list-group">
            @foreach($rankingPartidos as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ url('/consulta-despesas?' .
                        http_build_query([
                            'sigla_partido' => $item->sigla_partido,
                            'sigla_uf' => request('sigla_uf'),
                            'deputado' => request('deputado')
                        ])
                    ) }}">
                        {{ $item->sigla_partido }}
                    </a>
                    <span class="badge bg-primary">R$ {{ number_format($item->total, 2, ',', '.') }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
$(function() {
    $("#deputado").autocomplete({
        source: "{{ url('/deputados/autocomplete') }}"
    });
});
</script>
@endpush
