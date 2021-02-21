<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>COFAT</title>
        @include('links')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div id="app">
            <div class="wrapper">
                @include('nav')
                @include('sidebar')
                <main>
                    @yield('content')
                </main>
                @include('footer')
            </div>

            {{-- <router-view></router-view> --}}
        </div>
        @include('scripts')   
    </body>
</html>
