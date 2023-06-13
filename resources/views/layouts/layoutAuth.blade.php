@include("partials.header")

@include("partials.navbar")

<div class="flex min-h-screen justify-center bg-gray-100">
    @yield("content")
</div>

@include("partials.footer")