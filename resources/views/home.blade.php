@extends("layouts.layout")

@section("content")
    <form accordion class="max-w-5xl mx-auto" method="GET" action="{{route("search")}}">
        <div class="searchBar-primary">
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

            <div class="searchBar-accordion--btn group">
                <span class="text-white group-hover:text-white/80">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                        <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                    </svg>
                </span>
            </div>
        </div>

        <div class="searchBar-accordion--body">
            <!-- TODO -->
        </div>
    </form>

    <script src="{{asset("assets/js/Components/accordion.js")}}"></script>

    <div id="newPostForm" class="post-action mx-8 lg:mx-auto mt-4 p-4 bg-gradient-to-r from-blue-600 from-70% to-blue-700 rounded-md flex gap-4 justify-center items-center lg:max-w-5xl">
        <a class="flex-1 border text-decoration-none border-solid rounded-lg border-gray-200 bg-gray-50 text-gray-800 px-3 py-4 focus-visible:outline-0" contenteditable="true" id="newPost" aria-multiline="true" aria-placeholder="">
            <span contenteditable="false" class="">O que você está pensando hoje {{$loggedUser->name}}?</span>
        </a>

        <input type="hidden" id="userToken" name="userToken" value="{{$loggedUser->remember_token}}">

        <div class="btn-send text-white cursor-pointer" id="btn-send" onclick="addNewPost(this)">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
                <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
            </svg>
        </div>
    </div>

    <div class="posts px-4 py-4 w-full flex flex-col items-center gap-3">
        @foreach ($feedPosts as $post)
            <x-post :post="$post"/>
        @endforeach
    </div>

    @if($loggedUser->posts_count > 0)
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

    @if($feedPosts->count() > 0)
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
                    <span class="comment--reply" onclick="replyComment()">Responder</span>
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

    <script src="{{asset("assets/js/Post/postInteractions.js")}}"></script>

    

@endsection