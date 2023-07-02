@extends("layouts.layout")

@section("content")


    <div class="friends-main-screen">
        <div class="friends-list">
            @foreach ($friends as $friend)
                <x-friend_card :friend="$friend"/>
            @endforeach
        </div>

        <div class="friend-active-chat" id="activeChat" data-active-friend="">
            <div class="chat">
                <div id="template-msg" class="chat-msg">
                    <div class="msg-author">Teste</div>
                    <div class="msg-message">Mensagem de teste para tentar quebrar as linhas de proposito e ver se algo acontece dasdasdasdasdasdasdasdasdasdas asdsadasd</div>
                    <div class="msg-time">19:45</div>
                </div>

                <div id="template-msg" class="chat-msg mine">
                    <div class="msg-author">Teste</div>
                    <div class="msg-message">Mensagem de teste para tentar quebrar as linhas de proposito e ver se algo acontece dasdasdasdasdasdasdasdasdasdas asdsadasd</div>
                    <div class="msg-time">19:45</div>
                </div>
                
            </div>

            <div class="chat-input">
                <textarea name="message" id="messageInput"></textarea>
            </div>
        </div>
    </div>

    <script src="{{asset("assets/js/Chat/chatInteractions.js")}}"></script>


@endsection