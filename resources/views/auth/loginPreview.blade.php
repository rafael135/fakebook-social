@extends("layouts.layoutAuth")

@section("content")

    @php
        $emailError = null;
        $passwordError = null;

        if($errors != false) {
            $emailError = $errors->get("email");
            $passwordError = $errors->get("password");
        }
    @endphp


    <div class="pt-16 w-full min-h-screen flex justify-center items-center">
        <div class="form-container w-128 h-auto bg-gradient-to-t from-gray-100 from-70% to-gray-200 overflow-hidden border-solid border-b-gray-400/80 rounded-xl shadow-lg shadow-black/20">
            <h2 class="w-full bg-gradient-to-r from-blue-700 from-70% to-blue-600 text-2xl font-bold text-white text-center py-3">Login</h2>
            <form class="px-4 py-4 flex flex-col justify-center gap-2" method="POST" action='{{route("auth.loginAction")}}'>
                @csrf

                <input class="auth-input @if($emailError != null) error @endif" type="email" autocomplete="email" name="email" id="email" placeholder="@if($emailError == null) E-mail @else {{$emailError}} @endif" data-input-placeholder="E-mail">

                <input class="auth-input @if($passwordError != null) error @endif" type="password" autocomplete="current-password" name="password" id="password" placeholder="@if($passwordError == null) Senha @else {{$passwordError}} @endif" data-input-placeholder="Senha">

                <div class="mb-3">
                    <input class="auth-checkBox" type="checkbox" name="remember" id="remember">
                    <label class="auth-checkBox--label" for="remember">Lembrar-se de mim</label>
                </div>

                <span class="mb-2">
                    Não possui uma conta? <a href="{{route("auth.register")}}" class="auth-redirect">Clique aqui!</a>
                </span>
                

                <button type="submit" class="auth-button">Login</button>
            </form>
        </div>
    </div>

    <script src="{{asset("assets/js/Auth/authCleaner.js")}}"></script>

@endsection