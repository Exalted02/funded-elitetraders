@extends('layouts.app')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col-md-4">
					<h3 class="page-title">Certificate</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="">Certificate</a></li>
						<li class="breadcrumb-item active">Certificate</li>
					</ul>
				</div>
				<div class="col-md-8 float-end ms-auto">
					<div class="d-flex title-head">
						<a href="javascript:void(0)" class="btn add-btn" data-bs-toggle="modal"><i class="la la-plus-circle"></i> Create Certificate</a>
					</div>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		{{--<div class="filter-filelds">
			<form id="search-challenge">
			@csrf
				<div class="row filter-row">
					<div class="col-md-3">  
						 <div class="input-block">
							<select class="select" name="search_status">
								<option value="">{{ __('please_select') }}</option>
								<option value="0">On Challenge</option>
								<option value="1">Funded</option>
								<option value="2">Failed</option>
							</select>
						 </div>
					</div>
					<div class="col-xl-2 p-r-0">  
						<a href="javascript:void(0);" class="btn btn-success w-100 search-data"><i class="fa-solid fa-magnifying-glass"></i> {{ __('search') }} </a> 
					</div>
				</div>
			</form>
		</div>
		
		<div class="row">
			<div class="col-md-3 mb-2">
				<button type="button" class="btn btn-info multi-adjust-balance"><i class="la la-plus m-r-5"></i> Adjust balance</button>
			</div>
		</div>--}}
		<hr>
		
		<div class="row">
			{{--<div class="col-md-12 mb-2">
				<div id="selectionMessage" class="alert alert-info py-2 px-3 d-flex justify-content-between align-items-center" style="display: none !important;">
					<span id="selectionText"></span>
					<div>
						<a href="javascript:void(0);" id="selectAllMatchingLink" style="display: none;">Select all matching <span id="totalCount"></span> rows</a>
						<a href="javascript:void(0);" id="clearSelection" class="ms-3 text-danger">Clear selection</a>
					</div>
				</div>
			</div>--}}
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable1" id="certificateTable">
						<thead>
							<tr>
								{{--<th>
									<label class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" id="checkCertificateAll">
									</label>
								</th>--}}
								<th>Date</th>
								<th>Name</th>
								<th>Amount</th>
								<th>Certificate</th>
								<th class="text-end">Actions</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
	<!-- /Page Content -->
@include('modal.certificate-modal')
@include('modal.common')
@endsection 
@section('scripts')
@include('_includes.footer')
<script>
    const certificateDataUrl = "{{ route('certificate.data') }}";
</script>
<script src="{{ url('front-assets/js/page/certificate.js') }}"></script>
<script>
/*$(document).on('click','.search-data', function(){
	$('#search-challenge').submit();
});*/
</script>
@endsection
