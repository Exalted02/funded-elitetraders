<!DOCTYPE html>
<html data-layout-mode="dark">
<head>
    <title>{{ __('project_title') }}</title>
	
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="{{ url('front-assets/img/favicon.png') }}">
    <style>
        body {
            text-align: center;
			margin: 0;
        }
		.container {
			padding: 0 5px;
		}
        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 1.25rem;
        }
		.verified-head {
			letter-spacing: .025em;
			font-weight: 600;
			font-size: 20px;
			padding: 1.25rem;
		}
		.verified-head i {
			color: #1AFF00;
			margin-right: .5rem;
		}
		.warning-text {
			font-size: 18px;
			padding: 0 1.25rem 1.25rem 1.25rem;
		}
		.warning-text i {
			margin-right: .5rem;
		}
    </style>
	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="{{ url('front-assets/plugins/fontawesome/css/fontawesome.min.css') }}">
	<link rel="stylesheet" href="{{ url('front-assets/plugins/fontawesome/css/all.min.css') }}">
	
	<!-- Main CSS -->
    <link rel="stylesheet" href="{{ url('front-assets/css/style.css') }}">
</head>
<body>
    <div class="container">
    <div class="verified-head"><i class="fa-regular fa-circle-check"></i>Certificate is verified by {{ __('project_title') }}</div>
    <div class="warning-text"><i class="fa-solid fa-triangle-exclamation"></i>Check the name on your certificate to make sure it's the one</div>
    <img src="{{ url('/verification-certificate/'.$id.'.png') }}" alt="Certificate">
	</div>
</body>
</html>
