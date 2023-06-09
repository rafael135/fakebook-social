@include("partials.header")

@include("partials.sidebar")

<div class="p-4 sm:ml-64 flex justify-center">
    <div class="w-full lg:w-156 mt-14 bg-gray-100">
        @yield("content")
    </div>
</div>

@include("partials.footer")