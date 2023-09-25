@extends("layouts.layout")

@section("content")
    <input id="userToken" type="hidden" value="{{$loggedUser->remember_token}}">
    

    

    
    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap gap-1 -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#profileFriends" role="tablist">
            <li class="flex-1" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg" id="follower-tab" data-tabs-target="#follower" type="button" role="tab" aria-controls="follower" aria-selected="false">Seguidores</button>
            </li>
            <li class="flex-1" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg" id="following-tab" data-tabs-target="#following" type="button" role="tab" aria-controls="following" aria-selected="false">Seguindo</button>
            </li>
        </ul>
    </div>
    <div id="profileFriends">
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="follower" role="tabpanel" aria-labelledby="follower-tab">
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
        </div>
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="following" role="tabpanel" aria-labelledby="following-tab">
            <div class="flex flex-col mt-4 mb-4 pb-4 lg:max-w-4xl lg:mx-auto xl:max-w-6xl bg-gray-100 border border-solid border-gray-200 shadow-xl rounded-md">
                <h2 class="text-white text-2xl font-bold py-3 px-6 bg-slate-800 border-b border-solid border-b-gray-300 rounded-t-md">
                    <a class="text-blue-500 hover:underline hover:text-blue-600" href="{{route("user.profile", ["uniqueUrl" => $profile->uniqueUrl])}}">
                        {{$profile->name}}
                    </a>
                    Segue
                </h2>
        
                <div class="flex flex-col items-center gap-2 px-4 pt-4">
                    
                    @foreach ($followings as $following)
                        <x-profileFollower_card :follower="$following" />
                    @endforeach
                    
                </div>
            </div>
        </div>
    </div>


    <script src="{{asset("assets/js/Profile/followersInteractions.js")}}"></script>
    

@endsection