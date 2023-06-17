@extends("layouts.layout")

@section("content")
    <div class="loggedUser-info">
        <input type="hidden" name="userToken" value="{{$loggedUser->remember_token}}">
    </div>

    <div class="user-main relative w-full">
        <div class="user-main-cover aspect-16/9 h-auto w-full">
            <img src="@php echo(Storage::url("users/12/600473.png")); @endphp" alt="">
        </div>

        <div class="user-main-avatar flex flex-row gap-4 bg-gray-800 border-solid border-t border-orange-600 opacity-90 absolute bottom-0 left-0 right-0 h-44">
            <div class="user-main-avatar-img w-32 h-32 sm:w-48 sm:h-48 md:w-56 md:h-56 lg:w-64 lg:h-64 rounded-full overflow-hidden -mt-16 sm:-mt-24 md:-mt-28 lg:-mt-32 ms-8 border-t border-solid border-orange-600">
                <img class="w-32 h-32 sm:w-48 sm:h-48 md:w-56 md:h-56 lg:w-64 lg:h-64" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="">
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

    <div class="profile-posts mt-4 flex w-full flex-col gap-3 items-center">
        @foreach ($profileUser->verified_posts as $post)
            <x-post :post="$post" />
        @endforeach
    </div>

    <script src="{{asset("assets/js/Profile/profileInteractions.js")}}"></script>
    <script src="{{asset("assets/js/Post/postInteractions.js")}}"></script>



@endsection