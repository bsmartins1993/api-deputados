{{-- filepath: \\wsl.localhost\Ubuntu-22.04\root\api-deputados\resources\views\deputados\index.blade.php --}}
@extends('layouts.app')

@section('content')
<h1>Deputados</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col">
        <input type="text" class="form-control" id="busca-nome" name="nome" placeholder="Nome do Deputado" value="{{ request('nome') }}">
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
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="{{ url('/deputados') }}" class="btn btn-secondary">Limpar</a>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Partido</th>
            <th>UF</th>
            <th>Despesas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($deputados as $deputado)
        <tr>
            <td>
                @if($deputado->url_foto)
                    <img src="{{ $deputado->url_foto }}" alt="Foto de {{ $deputado->nome }}" width="80">
                @endif
                {{ $deputado->nome }}
            </td>
            <td>{{ $deputado->sigla_partido }}</td>
            <td>{{ $deputado->sigla_uf }}</td>
            <td>
                <a href="{{ url('/consulta-despesas?deputado_id=' . $deputado->id) }}" class="btn btn-sm btn-info">
                    Ver Despesas
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $deputados->links() }}
@endsection

@section('scripts')
<script>
function buscarDeputados() {
    let nome = document.getElementById('busca-nome').value;
    let partido = document.getElementById('busca-partido').value;
    let uf = document.getElementById('busca-uf').value;
    let url = `/deputados?nome=${encodeURIComponent(nome)}&sigla_partido=${encodeURIComponent(partido)}&sigla_uf=${encodeURIComponent(uf)}`;
    fetch(url)
        .then(response => response.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');
            let novaTabela = doc.querySelector('table');
            document.querySelector('table').innerHTML = novaTabela.innerHTML;
        })
        .catch(() => {
            document.querySelector('table').innerHTML = '<tr><td colspan="5">Erro ao buscar dados.</td></tr>';
        });
}

document.getElementById('busca-nome').addEventListener('keyup', buscarDeputados);
document.getElementById('busca-partido').addEventListener('keyup', buscarDeputados);
document.getElementById('busca-uf').addEventListener('keyup', buscarDeputados);
</script>
@push('scripts')
<script>
$(function() {
    $("#busca-nome").autocomplete({
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
@endsection
