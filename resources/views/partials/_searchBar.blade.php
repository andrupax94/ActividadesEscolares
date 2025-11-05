<form method="GET" action="{{ $action }}" class="searchBar  d-flex ">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar...">
    <button type="submit" class="btn btn-outline-primary">
        <i class="bi bi-search"></i> Buscar
    </button>
</form>
