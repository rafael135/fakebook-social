@extends("layouts.layout")

@section("content")
    <div class="loggedUser-info">
        <input type="hidden" name="userToken" value="{{$loggedUser->remember_token}}">
    </div>

    <div class="user-main relative w-full">
        <div class="user-main-cover bg-slate-900 relative aspect-16/9 h-auto w-full">
            <img src="@if($profileUser->cover != null) {{$profileUser->cover_url}} @else  @endif" alt="">
            @if($profileUser->is_mine == true)
                <div data-modal-target="changeCover-modal" data-modal-toggle="changeCover-modal" class="group absolute top-0 bottom-0 left-0 right-0 flex justify-center bg-transparent items-center text-slate-700 transition-all duration-200 ease-in-out hover:bg-gray-500 hover:opacity-60 hover:cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="hidden w-48 h-48 group-hover:block group-hover:text-slate-800" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                    </svg>
                </div>
            @endif
        </div>

        <div class="user-main-avatar flex flex-row gap-4 bg-gray-800 border-solid border-t border-orange-600 opacity-90 absolute bottom-0 left-0 right-0 h-44">
            <div class="user-main-avatar-img relative w-32 h-32 sm:w-48 sm:h-48 md:w-56 md:h-56 lg:w-64 lg:h-64 rounded-full overflow-hidden -mt-16 sm:-mt-24 md:-mt-28 lg:-mt-32 ms-8 border-t border-solid border-orange-600">
                <img class="w-32 h-32 sm:w-48 sm:h-48 md:w-56 md:h-56 lg:w-64 lg:h-64" src="
                    @if($profileUser->avatar != null) {{$profileUser->avatar_url}} @else https://flowbite.com/docs/images/people/profile-picture-5.jpg @endif" alt="">

                @if($profileUser->is_mine == true)
                    <div data-modal-target="changeAvatar-modal" data-modal-toggle="changeAvatar-modal" class="group absolute top-0 bottom-0 left-0 right-0 flex justify-center items-center bg-transparent text-slate-700 transition-all duration-200 ease-in-out hover:bg-gray-500 hover:opacity-60 hover:cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="hidden w-16 h-16 sm:w-24 sm:h-24 md:w-28 md:h-28 lg:w-32 lg:h-32 group-hover:block group-hover:text-slate-800" width="16" height="16" fill="currentColor" class="bi bi-file-image" viewBox="0 0 16 16">
                            <path d="M8.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM3 2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v8l-2.083-2.083a.5.5 0 0 0-.76.063L8 11 5.835 9.7a.5.5 0 0 0-.611.076L3 12V2z"/>
                        </svg>
                    </div>

                    
                @endif
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

    @if($profileUser->is_mine == true)
        <!-- Change cover modal -->
        <div id="changeCover-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="changeCover-modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Escolha sua imagem de fundo</h3>
                        <form class="space-y-6" action="#">
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Change avatar modal -->
        <div id="changeAvatar-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="changeAvatar-modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Escolha sua foto de perfil</h3>
                        <form class="space-y-6" action="#">
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="profile-posts mt-4 flex w-full flex-col gap-3 items-center">
        @foreach ($profileUser->verified_posts as $post)
            <x-post :post="$post" />
        @endforeach
    </div>

    <script src="{{asset("assets/js/Profile/profileInteractions.js")}}"></script>
    <script src="{{asset("assets/js/Post/postInteractions.js")}}"></script>



@endsection