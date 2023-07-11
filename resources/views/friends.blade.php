@extends("layouts.layout")

@section("content")

    <input type="hidden" id="userToken" value="{{$loggedUser->remember_token}}">

    

    <div class="friends-main-screen">
        <div class="friends-list">
            @foreach ($friends as $friend)
                <x-friend_card :friend="$friend"/>
            @endforeach
        </div>

        <div class="friend-active-chat" id="activeChat" data-active-friend="">
            
        </div>
    </div>

    


@endsection