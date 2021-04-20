<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>COFAT</title>
        @include('layouts.links')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div id="app">
            <div class="wrapper">
                <navbar :user="{{Auth::user()->load('notifications')}}"></navbar>
                @include('layouts.sidebar')
                <main>
                    <router-view :user="{{Auth::user()}}"></router-view>
                </main>
                @include('layouts.footer')
            </div>
        </div>
        @include('layouts.scripts')   
    </body>
</html>
