@extends("layouts.layout")

@section("content")
    <div id="newPostForm" class="post-action mx-8 lg:mx-auto mt-4 p-4 bg-gradient-to-r from-blue-600 from-70% to-blue-700 rounded-md flex gap-4 justify-center items-center lg:w-10/12">
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

    <script src="{{asset("assets/js/Post/postInteractions.js")}}"></script>

    

@endsection