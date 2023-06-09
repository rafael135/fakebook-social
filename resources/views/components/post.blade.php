<div class="post flex w-full flex-col border-solid border border-gray-400">
    <div class="post-header px-2 py-1 flex border-solid border-b border-gray-300">
        <a class="flex text-base" href="{{route("user.profile", ["uniqueUrl" => $post->user->uniqueUrl])}}">
            <div class="author-img bg-gray-800 rounded-full focus:ring-4 focus:ring-slate-700 dark:focus:ring-gray-600">
                <img class="w-12 h-12 max-w-none rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
            </div>
            <div class="author-info ms-1 flex flex-col">
                <div class="author--name">{{$post->user->name}}</div>
                <div class="post--createdAt text-xs mt-auto mb-1">{{$post->created_at->format("d/m/Y H:m")}}</div>
            </div>
        </a>
        <div class="more-opts flex justify-center items-center ms-auto h-full aspect-square cursor-pointer px-1 rounded hover:bg-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
            </svg>
        </div>
    </div>
    <div class="post-body px-2 w-full border-solid border-b border-gray-300">
        <div class="post--text p-2 my-2 rounded-lg text-gray-800 bg-gray-200">
            <p>{{$post->body}}</p>
        </div>
    </div>

    <div class="post-details py-2 px-3 flex">
        <div class="flex-1 flex justify-center items-center">
            <span class="like-btn font-semibold flex justify-center items-center text-gray-700 text-lg cursor-pointer hover:text-blue-600" onclick="likePost({{$post->id}})">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" class="bi bi-hand-thumbs-up-fill" viewBox="0 0 16 16">
                    <path d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
                </svg>
                <div class="ms-1">Curtir</div>
            </span>
        </div>

        <div class="flex-1 flex justify-center items-center">
            <span class="chat-btn font-semibold flex justify-center items-center text-gray-700 text-lg cursor-pointer hover:text-blue-600" onclick="openComments({{$post->id}})">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" class="bi bi-chat-left-fill" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                </svg>
                <div class="ms-1">Comentários</div>
            </span>
        </div>

        <div class="flex-1 flex justify-center items-center">
            <span class="share-btn font-semibold flex justify-center items-center text-gray-700 text-lg cursor-pointer hover:text-blue-600" onclick="sharePost({{$post->id}})">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" class="bi bi-share-fill" viewBox="0 0 16 16">
                    <path d="M11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5z"/>
                </svg>
                <div class="ms-1">Compartilhar</div>
            </span>
        </div>
    </div>
</div>