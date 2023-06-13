@extends("layouts.layout")

@section("content")
    <div id="newPostForm" class="post-action mx-auto mt-4 p-4 bg-gradient-to-r from-blue-600 from-70% to-blue-700 rounded-md flex gap-4 justify-center items-center lg:w-10/12">
        <a class="flex-1 border text-decoration-none border-solid rounded-lg border-gray-200 bg-gray-50 text-gray-800 px-3 py-4 focus-visible:outline-0" contenteditable="true" id="newPost" aria-multiline="true" aria-placeholder="">
            <span contenteditable="false" class="">O que você está pensando hoje {{$loggedUser->name}}?</span>
        </a>

        <input type="hidden" name="userToken" value="{{$loggedUser->remember_token}}">

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

    <script src="{{asset("assets/js/ajax/js/Post/postInteractions.js")}}"></script>

    

@endsection