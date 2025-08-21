<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('project_title') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /><!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ url('front-assets/img/favicon.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased login-body">
        <div class="row">
			<div class="col-md-6 login-bg">
				<img src="{{ asset('front-assets/img/login-bg.png') }}">
			</div>
			<div class="col-md-6 login-form">
				<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 login-screen">
					<div>
						<a href="/" class="logo-image">
							<x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
						</a>
					</div>

					<div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white1 shadow-md overflow-hidden sm:rounded-lg">
						{{ $slot }}
					</div>
				</div>
			</div>
        </div>
    </body>
</html>
