@extends("layouts.layoutAuth")

@section("content")

    <div class="pt-16 w-full min-h-screen flex justify-center items-center">
        <div class="form-container w-80 h-auto bg-gradient-to-t from-slate-700 from-70% to-slate-800 overflow-hidden rounded-xl shadow-md">
            <h2 class="w-full bg-gradient-to-r from-slate-800 from-70% to-slate-900 text-2xl font-bold text-white text-center py-3">Login</h2>
            <form class="px-4 py-4 flex flex-col justify-center" method="POST" action='{{route("auth.loginAction")}}'>
                @csrf

                <input class="mb-3 border-transparent rounded-lg" type="email" autocomplete="email" name="email" id="email" placeholder="E-mail">

                <input class="mb-3 border-transparent rounded-lg" type="password" autocomplete="current-password" name="password" id="password" placeholder="Senha">

                <div class="mb-3">
                    <input class="rounded-full cursor-pointer me-[2px]" type="checkbox" name="remember" id="remember">
                    <label class="cursor-pointer text-white font-medium text-sm focus:ring-transparent selection:ring-transparent" for="remember">Lembrar-se de mim</label>
                </div>
                

                <button type="submit" class="button bg-blue-600 text-white px-4 py-2 block hover:bg-blue-700">Login</button>
            </form>
        </div>
    </div>

@endsection