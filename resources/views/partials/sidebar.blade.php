<nav class="fixed top-0 h-16 z-50 w-full border-b border-gray-900 bg-gradient-to-r from-slate-800 from-70% to-slate-900 dark:bg-gray-800 dark:border-gray-700">
    <div class="h-full w-full flex items-center px-3 py-3 lg:px-5 lg:pl-3">
        <div class="w-full flex items-center justify-between">
            <div class="flex items-center justify-start">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <a href="{{route("home")}}" class="flex ml-2 md:mr-24">
                    <!--<img src="https://flowbite.com/docs/images/logo.svg" class="h-8 mr-3" alt="FlowBite Logo" />-->
                    <span class="self-center text-xl font-semibold text-white sm:text-2xl whitespace-nowrap dark:text-white">
                        Fakebook
                    </span>
                </a>
            </div>

            <div class="text-white flex items-center font-bold text-2xl">
                
            </div>

            <div class="ms-auto flex items-center">
                <div class="flex items-center ml-3">
                    <div>
                        <button type="button"
                            class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-slate-700 dark:focus:ring-gray-600"
                            aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full"
                                src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                        </button>
                    </div>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-900 bg-gradient-to-r from-slate-800 from-70% to-slate-900 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                        id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm text-gray-100 dark:text-white" role="none">
                                @if($loggedUser != false)
                                    {{ $loggedUser->name }}
                                @endif
                            </p>
                            <p class="text-sm font-medium text-gray-100 truncate dark:text-gray-300" role="none">
                                @if($loggedUser != false)
                                    {{$loggedUser->email}}
                                @endif
                            </p>
                        </div>
                        @if($loggedUser != false)
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="{{route("home")}}"
                                        class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Inicio</a>
                                </li>
                                <li>
                                    <a href="{{route("user.config")}}"
                                        class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Configurações</a>
                                </li>
                                <li>
                                    <a href="{{route("user.logout")}}"
                                        class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Sair</a>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full border-r border-gray-900 bg-gradient-to-r from-slate-900 from-30% to-slate-800 lg:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-gradient-to-r from-slate-900 from-30% to-slate-800 dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            <li>
                
                <a href="{{route("home")}}"
                    class="flex items-center p-2 text-gray-100 rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                        <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                    </svg>
                    <span class="ml-3">Inicio</span>
                </a>
            </li>
            <li>
                
                <a href="{{route("user.profile", ["uniqueUrl" => $loggedUser->uniqueUrl])}}"
                    class="flex items-center p-2 text-gray-100 rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white" width="16" height="16" fill="currentColor" class="bi bi-person-square" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                        <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1v-1c0-1-1-4-6-4s-6 3-6 4v1a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12z"/>
                    </svg>
                    <span class="ml-3">Perfil</span>
                </a>
            </li>

            @if($loggedUser != false)
                <li>
                    <a href="{{route("user.friends")}}"
                        class="flex items-center p-2 text-gray-100 rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z"/>
                        </svg>
                        <span class="flex-1 ml-3 whitespace-nowrap">Amigos</span>
                    </a>
                </li>
                <li>
                    <a href="{{route("user.messages")}}"
                        class="flex items-center p-2 text-gray-100 rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-6 h-6 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white" fill="currentColor" class="bi bi-chat-left-fill" viewBox="0 0 16 16">
                            <path d="M2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                        </svg>
                        <span class="flex-1 ml-3 whitespace-nowrap">Mensagens</span>
                        <span
                            class="inline-flex items-center justify-center w-3 h-3 p-3 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span>
                    </a>
                </li>
            @endif

            @if($loggedUser == false)
                <li>
                    <a href="#"
                        class="flex items-center p-2 text-gray-100 rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700">
                        <svg aria-hidden="true"
                            class="flex-shrink-0 w-6 h-6 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="flex-1 ml-3 whitespace-nowrap">Sign In</span>
                    </a>
                </li>

                <li>
                    <a href="#"
                        class="flex items-center p-2 text-gray-100 rounded-lg dark:text-white hover:bg-slate-700 dark:hover:bg-gray-700">
                        <svg aria-hidden="true"
                            class="flex-shrink-0 w-6 h-6 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="flex-1 ml-3 whitespace-nowrap">Sign Up</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>