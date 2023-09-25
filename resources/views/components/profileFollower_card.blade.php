<div class="profile-follower relative w-full flex bg-gray-200 shadow-sm border border-gray-400/40 rounded-md">
    <div class="flex-1 flex">
        <div class="flex justify-center items-center w-32 h-32 rounded-full overflow-hidden">
            <a class="group flex" href="{{route("user.profile", ["uniqueUrl" => $follower->uniqueUrl])}}">
                <img class="w-full h-auto" src="@if($follower->avatar != null) {{$follower->avatar_url}} @else https://flowbite.com/docs/images/people/profile-picture-5.jpg @endif " alt="">
            </a>
        </div>
        <div class="follower--name flex flex-col pt-3 ps-1">
            <a class="group flex" href="{{route("user.profile", ["uniqueUrl" => $follower->uniqueUrl])}}">
                <span class="text-2xl text-gray-800 capitalize">@if($follower->is_mine == false) {{$follower->name}} @else Eu @endif</span>
            </a>
            @if($follower->is_mine == false)
                <span data-user-id="{{$follower->id}}" onclick="followProfile(this)" class="absolute z-30 top-0 right-0 p-3 max-w-max rounded-tr-md rounded-bl-md text-white bg-blue-500 cursor-pointer hover:bg-blue-600">@if($follower->is_friend == true) Seguindo @else Seguir @endif</span>
            @endif
        </div>

        <div class="flex-1 relative flex flex-col items-end justify-end py-1.5 px-2">
            <a class="text-slate-800 p-0.5 rounded-md hover:bg-black/5" href="{{route("user.listFriends", ["uniqueUrl" => $follower->uniqueUrl])}}">
                <p>Seguidores: <span class="follower-count text-blue-500 p-0.5">{{ $follower->followers()->count() }}</span></p>
            </a>

            <a class="text-slate-800 p-0.5 rounded-md hover:bg-black/5" href="{{route("user.listFriends", ["uniqueUrl" => $follower->uniqueUrl])}}">
                <p>Seguindo: <span class="following-count text-blue-500 p-0.5">{{ $follower->following()->count() }}</span></p>
            </a>
        </div>
    </div>
</div>