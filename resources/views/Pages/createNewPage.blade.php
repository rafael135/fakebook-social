@extends("layouts.layout")

@section("content")
    <div class="createNewPage-container">
        <form enctype="multipart/form-data" method="POST" action="{{route("page.createAction")}}">
            @csrf

            <div class="createNewPage-inputs">
                <input class="createNewPage-input" type="text" name="name" id="name" placeholder="Nome da página" />

                <textarea class="createNewPage-input" placeholder="Breve descrição da página" name="description" id="description"></textarea>

                <div class="createNewPage-checkBox">
                    <input type="checkbox" name="private" id="private" />
                    <label for="private">Tornar esta página privada</label>
                </div>

                <button class="" type="submit">Criar Página</button>

            </div>

            <div class="createNewPage-img">
                <div>
                    <img src="" id="image-preview" alt="Preview da imagem">
                    <input type="file" name="image" id="image" accept="image/png,image/jpeg" onchange="setImage(this)">
                    <label class="group" for="image" title="Selecionar imagem">
                        <svg xmlns="http://www.w3.org/2000/svg" class="hidden w-32 h-32 group-hover:block group-hover:text-slate-800" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                        </svg>
                    </label>
                </div>
                <span>Preview da imagem</span>
            </div>

            

        </form>
    </div>

    <script src="{{asset("assets/js/Page/newPageInteractions.js")}}"></script>
@endsection