@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="{{ url('front-assets/plugins/c3-chart/c3.min.css') }}">
@endsection
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid pb-0">
            <div class="row">
                <div class="col-lg-12">
					<div class="card employee-month-card flex-fill">
						<div class="card-body">
							<div class="statistic-header">
								<h4>Account Settings</h4>
							</div>
							<hr class="mt-0">
							<div class="row mb-0">
								<div class="col-md-6">
									<p class="mb-0">Your Email: {{ $user->email ?? ''}}</p>
									<p>Name: {{ $user->name ?? ''}}</p>
								</div>
								<div class="col-md-6 text-end">
									<button class="btn btn-warning account-update-password">
										<i class="las la-key"></i> Reset Your Password
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
                {{--<div class="col-lg-12">
					<div class="card employee-month-card flex-fill">
						<div class="card-body">
							<div class="statistic-header">
								<h4>Payment Details</h4>
							</div>
							<hr class="mt-0">
							<div class="employee-month-details mb-0">
								<p>$100K Chllange | Made On 1 March | Paid: 500$</p>
								<p>$200K Chllange | Made On 3 March | Paid: 970$</p>
							</div>
						</div>
					</div>
				</div>--}}
			</div>
        </div>
        <!-- /Page Content -->

    </div>
    <!-- /Page Wrapper -->

@include('modal.account-modal')
@endsection 
@section('scripts')
<!-- Chart JS -->
<script src="{{ url('front-assets/plugins/c3-chart/d3.v5.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/c3.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/chart-data.js') }}"></script>
<script>
$(document).ready(function() {
	$(document).on('click','.account-update-password', function(){
		$('#view_account').modal('show');
	});
	
	$(document).on('click','.save-account', function(){
		
		let firstname = $('#first_name').val().trim();
		let lastname = $('#last_name').val().trim();
		let pwd = $('#password').val().trim();
		let isValid = true;
		$('.invalid-feedback').hide();
		$('.form-control').removeClass('is-invalid');
		
		if (firstname === '') 
		{
			$('#first_name').addClass('is-invalid');
			$('#first_name').next('.invalid-feedback').show();
			isValid = false;
		}
		
		if (lastname === '') 
		{
			$('#last_name').addClass('is-invalid');
			$('#last_name').next('.invalid-feedback').show();
			isValid = false;
		}
		
		/*if (pwd === '') 
		{
			$('#password').addClass('is-invalid');
			$('#password').next('.invalid-feedback').show();
			isValid = false;
		}*/
		//if valid then ajax   _token=' + csrfToken
		if (isValid) {
			var URL = "{{  route('client.updateaccount') }}";
			$.ajax({
				url: URL,
				type: "POST",
				data: {first_name:firstname,last_name:lastname,password:pwd,_token:csrfToken},
				dataType: 'json',
				success: function(response) {
					if(response.message)
					{
						$("#message-section").html('<div class="alert alert-success">' + response.message + '</div>');
					}
					
					setTimeout(() => {
						window.location.reload();
					}, "2000");
				},
			})
		}
	
	});
});
</script>
@endsection

