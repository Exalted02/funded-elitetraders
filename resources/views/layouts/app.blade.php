<!DOCTYPE html>
<!--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">-->
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-sidebar="dark" data-sidebar-size="lg" data-layout-mode="blue" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default" data-topbar="light">--}}
<html lang="en" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-layout-mode="dark" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default" data-topbar="dark">
	<head>
        <!--<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
		<link href="{{ url('front-assets/css/responsive-media.css') }}" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])-->
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Smarthr - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamstechnologies - Bootstrap Admin Template">
        <title>{{ __('project_title') }}</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ url('front-assets/img/favicon.png') }}">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ url('front-assets/css/bootstrap.min.css') }}">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="{{ url('front-assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    	<link rel="stylesheet" href="{{ url('front-assets/plugins/fontawesome/css/all.min.css') }}">

		<!-- Lineawesome CSS -->
        <link rel="stylesheet" href="{{ url('front-assets/css/line-awesome.min.css') }}">
		<link rel="stylesheet" href="{{ url('front-assets/css/material.css') }}">

		<!-- Daterangepikcer CSS -->
		<link rel="stylesheet" href="{{ url('front-assets/plugins/daterangepicker/daterangepicker.css') }}">

		<!-- Bootstrap Tagsinput CSS -->
		<link rel="stylesheet" href="{{ url('front-assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">

		<!-- Datatable CSS -->
		<link rel="stylesheet" href="{{ url('front-assets/css/dataTables.bootstrap4.min.css') }}">
		
		<!-- Feather CSS -->
		<link rel="stylesheet" href="{{ url('front-assets/css/feather.css') }}">
		
		<!-- Datetimepicker CSS -->
		<link rel="stylesheet" href="{{ url('front-assets/css/bootstrap-datetimepicker.min.css') }}">
		
		<!-- Lineawesome CSS -->
		<link rel="stylesheet" href="{{ url('front-assets/css/line-awesome.min.css') }}">
		
		<!-- Select2 CSS -->
		<link rel="stylesheet" href="{{ url('front-assets/css/select2.min.css') }}">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="{{ url('front-assets/css/style.css') }}">
		<link rel="stylesheet" href="{{ url('front-assets/css/custom-css.css') }}">
		<link rel="stylesheet" href="{{ url('front-assets/plugins/morris/morris.css') }}">
		
		<!-- Toastr CSS -->
		<link href="{{ url('front-assets/toastr.css') }}" rel="stylesheet">
		
		<meta name="csrf-token" content="{{ csrf_token() }}">
		@yield('styles')
		<script>
        var csrfToken = "{{ csrf_token() }}"; // Declare the CSRF token
		</script>
    </head>
	<body>
		<div class="main-wrapper">
			@include('_includes/header')
			@include('_includes/sidebar')
			
				@yield('content')

			@include('_includes/footer')
		</div>
		<!-- Trading credentials -->
		<div class="modal custom-modal fade" id="trading_credentials_modal" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLgLabel">Trading Credentials
						</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					</div>
					<div class="modal-body">
						<ul class="personal-info">
							<li>
								<div class="title">Platform:</div>
								<div id="">MT5</div>								
							</li>
							<li>
								<div class="title">Server:</div>
								<div id="">Virtual Markets LTD</div>								
							</li>
							<li>
								<div class="title">Account Id:</div>
								<div id="trading-account-id"></div>								
							</li>
							<li>
								<div class="title">Account Password:</div>
								<div id="trading-account-password"></div>
							</li>
							<li class="mt-5">
								<div class="title1">⚠ Please don’t log in from multiple IP addresses. This could flag your account for copy trading.</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- jQuery -->
        <script src="{{ url('front-assets/js/jquery-3.7.1.min.js') }}"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="{{ url('front-assets/js/bootstrap.bundle.min.js') }}"></script>
		
		<!-- Slimscroll JS -->
		<script src="{{ url('front-assets/js/jquery.slimscroll.min.js') }}"></script>

		<!-- Datatable JS -->
		<script src="{{ url('front-assets/js/jquery.dataTables.min.js') }}"></script>
		<script src="{{ url('front-assets/js/dataTables.bootstrap4.min.js') }}"></script>

		<!-- Bootstrap Tagsinput JS -->
		<script src="{{ url('front-assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>

		<!-- Datetimepicker JS -->
		<script src="{{ url('front-assets/js/moment.min.js') }}"></script>
		<script src="{{ url('front-assets/js/bootstrap-datetimepicker.min.js') }}"></script>

		<!-- Daterangepikcer JS -->
		<script src="{{ url('front-assets/js/moment.min.js') }}"></script>
		<script src="{{ url('front-assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
		
		<!-- Select2 JS -->
		<script src="{{ url('front-assets/js/select2.min.js') }}"></script>
		
		 <!-- Theme Settings JS -->
		<script src="{{ url('front-assets/js/layout.js') }}"></script>
		<script src="{{ url('front-assets/js/theme-settings.js') }}"></script>
		<script src="{{ url('front-assets/js/greedynav.js') }}"></script>
		<!-- Custom JS -->
		<script src="{{ url('front-assets/js/app.js') }}"></script>
		<script src="{{ url('front-assets/js/page/multi-action.js') }}"></script>
		
		<!-- Toastr JS -->
		<script src="{{ url('front-assets/toastr.min.js') }}"></script>
		
		@if(request()->routeIs('client.dashboard-challenge','client.dashboard','client.account','client.verification','payouts.random-payout-details','client.withdraw.index'))
			<script src="{{ url('front-assets/pusher.min.js') }}"></script>
			<script>
				Pusher.logToConsole = false; //If true then data coming in console
				var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
					cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
					encrypted: true,
					authEndpoint: "{{url('broadcasting/auth')}}", // Required for Private Channels
					auth: {
						headers: {
							'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel's CSRF token
						}
					}
				});
				
				var channeltwo = pusher.subscribe('payout-notification.recent');
				channeltwo.bind('App\\Events\\RecentPayoutEvent', function(data) {
					// alert(data.message);
					// console.log(data);		
					toastr.options =
					{
						"closeButton" : true,
						"progressBar" : true,
						"positionClass" : "toast-bottom-right"
					}
					toastr.success(data.message);
				});
			</script>
		@endif
		
		<script type="text/javascript">
		$(document).on('click','.trading-credentials', function(){
			var URL = "{{ route('client.account-data') }}";
			$.ajax({
				url: URL,
				type: "POST",
				data: {_token: csrfToken},
				dataType: 'json',
				success: function(response) {
					// console.log(response);
					$("#trading-account-id").text(response.trading_account_id);
					$("#trading-account-password").text(response.trading_account_pw);
					$('#trading_credentials_modal').modal('show');
				},
			});
		
			/*var randomNumber = Math.floor(Math.random() * (99999999 - 10000000 + 1)) + 10000000;
			$("#trading-account-id").text(randomNumber);
			
			var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()";
			var password = "";
			for (var i = 0; i < 10; i++) {
				var randomIndex = Math.floor(Math.random() * chars.length);
				password += chars[randomIndex];
			}
			$("#trading-account-password").text(password);
		
			$('#trading_credentials_modal').modal('show');*/
		});
		$(function(){
			var url = "{{ route('changeLang') }}";
			$(document).on("click", ".languageChange a", function(e) {
				e.preventDefault();  // Prevent default action for anchor
				var languageCode = $(this).data('id');
				var selectedText = $(this).text().trim();
				$('#selectedLang').data('id', languageCode);  // Update the data-id
				$('#selectedLang img').attr('src', $(this).find('img').attr('src'));  // Update the flag icon
				$('#selectedLangText').text(selectedText); 
				//alert(languageCode);
				window.location.href = url + "?lang=" + languageCode;
			});
		});
			/*$(function(){
				var url = "{{ route('changeLang') }}";
				$(document).on("click", "ul.languageChange li a", function(e) {
					var languageCode = $(this).data('id');
					alert(languageCode);
					window.location.href = url + "?lang="+ $(this).data('id');
				});
			});*/		
		</script>
		<script>
			@if(Session::has('message'))
				var msg = "{{ session('message') }}";
				var type = 'success';
				toastr_msg(msg, type);
			@endif

			@if(Session::has('error'))
				var msg = "{{ session('error') }}";
				var type = 'error';
				toastr_msg(msg, type);
			@endif

			@if(Session::has('info'))
				var msg = "{{ session('info') }}";
				var type = 'info';
				toastr_msg(msg, type);
			@endif

			@if(Session::has('warning'))
				var msg = "{{ session('warning') }}";
				var type = 'warning';
				toastr_msg(msg, type);
			@endif
			function toastr_msg(msg, type){
				toastr.options =
				{
					"closeButton" : true,
					"progressBar" : true
				}
				toastr[type](msg);
			}
		</script>
		@yield('scripts')
		@yield('component-scripts')
	</body>
</html>