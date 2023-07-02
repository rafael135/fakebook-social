<div class="friend" data-friend-id="{{$friend->id}}">
    <div class="friend-img">
        <img src="@if($friend->avatar != null) {{$friend->avatar}} @else https://flowbite.com/docs/images/people/profile-picture-5.jpg @endif" alt="">
    </div>

    <div class="friend-info">
        <div class="friend-name">{{$friend->name}}</div>
        <div class="friend-status"></div>
    </div>
</div>