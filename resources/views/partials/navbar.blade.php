<nav class="fixed top-0 h-16 z-50 w-full border-b border-gray-900 bg-gradient-to-r from-slate-800 from-70% to-slate-900 dark:bg-gray-800 dark:border-gray-700">
    <div class="h-full flex items-center px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <a href="{{route("home")}}" class="flex ml-2 md:mr-24">
                    <!--<img src="https://flowbite.com/docs/images/logo.svg" class="h-8 mr-3" alt="FlowBite Logo" />-->
                    <span class="self-center text-xl font-semibold text-white sm:text-2xl whitespace-nowrap dark:text-white">
                        Fakebook
                    </span>
                </a>
            </div>

            <div class="text-white flex items-center font-bold text-2xl">
                
            </div>

            @if($loggedUser != false)
                <div class="flex items-center">
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
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="{{route("home")}}"
                                        class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Home</a>
                                </li>
                                <li>
                                    <a href="{{route("user.config")}}"
                                        class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Settings</a>
                                </li>
                                <li>
                                    <a href="{{route("user.logout")}}"
                                        class="block px-4 py-2 text-sm text-gray-100 hover:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Sign out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</nav>