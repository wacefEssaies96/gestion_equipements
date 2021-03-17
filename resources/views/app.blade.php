<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>COFAT</title>
        @include('layouts.links')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div id="app">
            <div class="wrapper">
                @include('layouts.navbar')
                @include('layouts.sidebar')
                <main>
                    @yield('content')
                </main>
                @include('layouts.footer')
            </div>
        </div>
        @include('layouts.scripts')   
    </body>
</html>
