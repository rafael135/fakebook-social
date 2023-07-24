<form method="GET" class="searchBar-primary" action="{{route("search")}}">
    @csrf

    <div class="type-search">
        <select required title="Filtrar por" name="type" id="">
            <option disabled selected value="default">Filtrar por</option>
            <option value="group">Grupo</option>
            <option value="page">Página</option>
            <option value="profile">Perfil</option>
            <option value="post">Postagem</option>
        </select>
    </div>

    <div class="input-search">
        <input required name="searchTerm" type="text" placeholder="O que procura?">
    </div>
</form>