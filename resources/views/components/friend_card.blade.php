<div class="friend-card group">
    <div class="friend-img">
        <img src="{{($friend->avatar == null) ? "https://flowbite.com/docs/images/people/profile-picture-5.jpg" : $friend->avatar_url}}" alt="{{$friend->name}}">
    </div>

    <div class="friend-info group-hover:text-blue-500">
        <span class="friend-name">{{$friend->name}}</span>
    </div>

    <div class="friend-time">
        <div class="friend-time--header">Amigos desde</div>
        <span class="friend-time--date">{{$friend->updated_at->format("d/m/Y")}}</span>
    </div>

    <a class="friend-link" href="{{route("user.profile", ["uniqueUrl" => $friend->uniqueUrl])}}"></a>
</div>