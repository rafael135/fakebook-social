@extends("layouts.layout")

@section("content")

    <input type="hidden" id="userToken" value="{{$loggedUser->remember_token}}">

    

    <div class="friends-main-screen">
        <h2 class="friends-list--header">Amigos</h2>
        <div class="friends-list">
            @foreach ($friends as $friend)
                <x-friend_card :friend="$friend"/>
            @endforeach
        </div>

        
    </div>

    


@endsection