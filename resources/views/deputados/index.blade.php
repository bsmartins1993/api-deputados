{{-- filepath: \\wsl.localhost\Ubuntu-22.04\root\api-deputados\resources\views\deputados\index.blade.php --}}
@extends('layouts.app')

@section('content')
<h1>Deputados</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col">
        <input type="text" class="form-control" name="nome" id="busca-nome" placeholder="Nome" value="{{ request('nome') }}">
    </div>
    <div class="col">
        <input type="text" class="form-control" name="sigla_partido" id="busca-partido" placeholder="Partido" value="{{ request('sigla_partido') }}">
    </div>
    <div class="col">
        <input type="text" class="form-control" name="sigla_uf" id="busca-uf" placeholder="UF" value="{{ request('sigla_uf') }}">
    </div>
    <div class="col">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="{{ route('deputados.index') }}" class="btn btn-secondary">Limpar</a>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Foto</th>
            <th>Nome</th>
            <th>Partido</th>
            <th>UF</th>
            <th>Legislatura</th>
        </tr>
    </thead>
    <tbody>
        @foreach($deputados as $dep)
        <tr>
            <td><img src="{{ $dep->url_foto }}" width="50"></td>
            <td>{{ $dep->nome }}</td>
            <td>{{ $dep->sigla_partido }}</td>
            <td>{{ $dep->sigla_uf }}</td>
            <td>{{ $dep->id_legislatura }}</td>
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
@endsection
