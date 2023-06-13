@include("partials.header")

@include("partials.sidebar")

<script>var exports = {};</script>

<div class="sm:ml-64 flex justify-center">
    <div class="w-full max-w-full mt-14 bg-gray-100">
        @yield("content")
    </div>
</div>

@include("partials.footer")