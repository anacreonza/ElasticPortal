@extends('layouts.app')
<body>
    <div id="app">
        @component('layouts.navbar')
            {{-- @slot('enable_searchbar')
                enabled
            @endslot --}}
        @endcomponent

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
