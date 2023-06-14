@include("partials.header")

@include("partials.sidebar")

<script>var exports = {};</script>

<div class="lg:ml-64 flex justify-center">
    <div class="w-full max-w-full mt-16">
        @yield("content")
    </div>
</div>

@include("partials.footer")