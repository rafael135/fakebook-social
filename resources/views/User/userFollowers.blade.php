@extends("layouts.layout")

@section("content")
    <input id="userToken" type="hidden" value="{{$loggedUser->remember_token}}">
    

    <div class="flex flex-col mt-4 mb-4 pb-4 lg:max-w-4xl lg:mx-auto xl:max-w-6xl bg-gray-100 border border-solid border-gray-200 shadow-xl rounded-md">
        <h2 class="text-white text-2xl font-bold py-3 px-6 bg-slate-800 border-b border-solid border-b-gray-300 rounded-t-md">Seguidores de 
            <a class="text-blue-500 hover:underline hover:text-blue-600" href="{{route("user.profile", ["uniqueUrl" => $profile->uniqueUrl])}}">
                {{$profile->name}}
            </a>
        </h2>

        <div class="flex flex-col items-center gap-2 px-4 pt-4">
            
            @foreach ($followers as $follower)
                <x-profileFollower_card :follower="$follower" />
            @endforeach
            
        </div>
    </div>

    <script src="{{asset("assets/js/Profile/followersInteractions.js")}}"></script>
    

@endsection