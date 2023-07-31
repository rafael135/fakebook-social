@extends("layouts.layout")

@section("content")
    <div class="loggedUser-info">
        <input type="hidden" id="userToken" name="userToken" value="{{$loggedUser->remember_token}}">
    </div>

    <div class="user-main relative w-full">
        <div class="user-main-cover bg-slate-900 relative aspect-16/9 h-auto w-full">
            <img class="w-full h-auto aspect-16/9" src="@if($profileUser->cover != null) {{$profileUser->cover_url}} @else  @endif" alt="">
            @if($profileUser->is_mine == true)
                <div data-modal-target="changeCover-modal" data-modal-toggle="changeCover-modal" class="group absolute top-0 bottom-0 left-0 right-0 flex justify-center bg-transparent items-center text-slate-700 transition-all duration-200 ease-in-out hover:bg-gray-500 hover:opacity-60 hover:cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="hidden w-48 h-48 group-hover:block group-hover:text-slate-800" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                    </svg>
                </div>
            @endif
        </div>

        <div class="user-main-avatar flex flex-row gap-4 bg-gray-800 border-solid border-t border-orange-600 opacity-90 absolute bottom-0 left-0 right-0 h-44">
            <div class="user-main-avatar-img relative w-32 h-32 sm:w-48 sm:h-48 md:w-56 md:h-56 lg:w-64 lg:h-64 rounded-full overflow-hidden -mt-16 sm:-mt-24 md:-mt-28 lg:-mt-32 ms-8 border-t border-solid border-orange-600">
                <img class="w-32 h-32 sm:w-48 sm:h-48 md:w-56 md:h-56 lg:w-64 lg:h-64" src="
                    @if($profileUser->avatar != null) {{$profileUser->avatar_url}} @else https://flowbite.com/docs/images/people/profile-picture-5.jpg @endif" alt="">

                @if($profileUser->is_mine == true)
                    <div data-modal-target="changeAvatar-modal" data-modal-toggle="changeAvatar-modal" class="group absolute top-0 bottom-0 left-0 right-0 flex justify-center items-center bg-transparent text-slate-700 transition-all duration-200 ease-in-out hover:bg-gray-500 hover:opacity-60 hover:cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="hidden w-16 h-16 sm:w-24 sm:h-24 md:w-28 md:h-28 lg:w-32 lg:h-32 group-hover:block group-hover:text-slate-800" width="16" height="16" fill="currentColor" class="bi bi-file-image" viewBox="0 0 16 16">
                            <path d="M8.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM3 2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v8l-2.083-2.083a.5.5 0 0 0-.76.063L8 11 5.835 9.7a.5.5 0 0 0-.611.076L3 12V2z"/>
                        </svg>
                    </div>

                    
                @endif
            </div>
            <div class="user-main-info flex-1 flex flex-row gap-3 items-start text-white text-4xl mt-2">
                <span class="user-info--name">{{$profileUser->name}}</span>
                
                @if($profileUser->is_mine == false)
                    <button class="user-info--btn-follow mt-2 text-base bg-blue-600 opacity-100 text-white rounded-xl px-6 py-1 hover:bg-blue-700 active:bg-blue-800" data-following="{{($profileUser->is_friend == true) ? 'true' : 'false'}}" onclick="followProfile(this, {{$profileUser->id}})">
                        {{($profileUser->is_friend == true) ? "Seguindo" : "Seguir"}}
                    </button>
                @endif

                <div class="user-info--follow-count text-xl flex flex-col items-end ms-auto me-8">
                    <span id="user-info--follow-count--followers">Seguidores: <a href="{{route("user.followers", ["uniqueUrl" => $profileUser->uniqueUrl])}}" class="text-blue-500">{{$profileUser->followers()->count()}}</a></span>
                    <span>Seguindo: <a href="{{route("user.following", ["uniqueUrl" => $profileUser->uniqueUrl])}}" class="text-blue-500">{{$profileUser->following()->count()}}</a></span>
                </div>
            </div>
        </div>
    </div>

    @if($profileUser->is_mine == true)
        <!-- Change cover modal -->
        <div id="changeCover-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="changeCover-modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Escolha sua imagem de fundo</h3>
                        <form class="space-y-6" method="POST" enctype="multipart/form-data" id="form-coverImage" action="{{route("user.change.cover")}}">
                            @csrf

                            <!-- Input cover -->
                            <div class="flex items-center justify-center w-full">
                                <label for="coverInput" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Clique para fazer upload</span> ou arraste o arquivo</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG ou JPEG (MÁX. 2MB)</p>
                                    </div>
                                    <input id="coverInput" name="cover" type="file" accept="image/png,image/jpeg" onchange="changeCover(this)" class="hidden" />
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Change avatar modal -->
        <div id="changeAvatar-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="changeAvatar-modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Escolha sua foto de perfil</h3>
                        <form class="space-y-6" method="POST" enctype="multipart/form-data" id="form-avatarImage" action="{{route("user.change.avatar")}}">
                            @csrf

                            <!-- Input avatar -->
                            <div class="flex items-center justify-center w-full">
                                <label for="avatarInput" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Clique para fazer upload</span> ou arraste o arquivo</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG ou JPEG (MÁX. 2MB)</p>
                                    </div>
                                    <input id="avatarInput" name="avatar" type="file" accept="image/png,image/jpeg" onchange="changeAvatar(this)" class="hidden" />
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="profile-posts mt-4 flex w-full flex-col gap-3 pb-4 items-center">
        @foreach ($profileUser->verified_posts as $post)
            <x-post :post="$post" />
        @endforeach
    </div>

    @if(count($profileUser->verified_posts) > 0 && $profileUser->is_mine == true)
        <!-- Delete Post Modal -->
        <div id="deletePost-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="deletePost-modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-6 text-center">
                        <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Tem certeza que deseja deletar o post?</h3>
                        <button data-modal-hide="deletePost-modal" onclick="deletePost()" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Sim
                        </button>
                        <button data-modal-hide="deletePost-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Não</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(count($profileUser->verified_posts) > 0)

        <!-- Open Post Modal -->
        <div id="openPost-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-8 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full lg:max-w-7xl max-h-full rounded-md shadow bg-white">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="openPost-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>

                <div class="openedPost-author">
                    <div class="author--img">
                        <img id="openedPost-author--img" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="">
                    </div>

                    <div class="author-info">
                        <div class="author--name">
                            Rafael
                        </div>
                        <div class="author--createdAt">
                            10/07/2023 03:07
                        </div>
                    </div>
                </div>

                <div class="openedPost-body">
                    <div class="post--text p-2 my-2 rounded-lg text-gray-800 bg-gray-200">
                        <p>Teste</p>
                    </div>
                </div>

                <div id="template-comment" class="post--comment">
                    <div class="comment--avatar">
                        <img src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="">
                    </div>

                    <div class="comment--details">
                        <span class="comment--author">Joao Bolota</span>

                        <div class="comment--body">
                            Corpo longo de comentario longo para teste longo
                        </div>
                    </div>

                    <span class="comment--likes">120</span>
                    <span class="comment--reply">Responder</span>
                </div>

                <div class="openedPost-comments">
                    
                </div>

                <div class="openedPost-newComment--input">
                    <input id="newCommentInput" type="text" placeholder="Faça um comentário na postagem">
                </div>

                <div class="openedPost-details">
                    <div class="post--action">
                        <span class="like-btn" data-post-id="" onclick="likePost(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" class="bi bi-hand-thumbs-up-fill" viewBox="0 0 16 16">
                                <path d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
                            </svg>
                            <div class="ms-1">Curtir</div>
                        </span>
                    </div>
            
                    <div class="post--action">
                        <span class="chat-btn" data-post-id="" onclick="openComments(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" class="bi bi-chat-left-fill" viewBox="0 0 16 16">
                                <path d="M2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                            </svg>
                            <div class="ms-1">Comentários</div>
                        </span>
                    </div>
            
                    <div class="post--action">
                        <span class="share-btn" data-post-id="" onclick="sharePost(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" class="bi bi-share-fill" viewBox="0 0 16 16">
                                <path d="M11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5z"/>
                            </svg>
                            <div class="ms-1">Compartilhar</div>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    @endif

    <script src="{{asset("assets/js/Profile/profileInteractions.js")}}"></script>
    <script src="{{asset("assets/js/Post/postInteractions.js")}}"></script>



@endsection