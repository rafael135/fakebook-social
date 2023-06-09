@extends("layouts.layout")

@section("content")

    <div class="posts flex flex-col items-center gap-3 bg-gray-50">
        @foreach ($feedPosts as $post)
            <x-post :post="$post"/>
        @endforeach
        
    </div>

    <script src="{{asset("assets/js/ajax/js/Post/postInteractions.js")}}"></script>

    

@endsection