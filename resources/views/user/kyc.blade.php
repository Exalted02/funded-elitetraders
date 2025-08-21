@extends('layouts.app')
@section('content')
@php 
 use Carbon\Carbon;
//echo "<pre>";print_r($documents);die;
@endphp
<!-- Page Wrapper -->
<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col-md-4">
					<h3 class="page-title">KYC</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="">KYC</a></li>
						<li class="breadcrumb-item active">KYC</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<div class="filter-filelds">
			<div class="row filter-row">
				<div class="col-xl-2">  
					 <div class="input-block">
						 <select class="select" name="search_status">
							<option value="">All History</option>
						</select>
					 </div>
				</div>
				<div class="col-xl-2">  
					 <div class="input-block">
						 <input type="search" class="form-control floating" name="search_name" placeholder="Search by email">
					 </div>
				</div>
				<div class="col-xl-2">  
				<a href="javascript:void(0);" class="btn btn-success w-100 search-data"><i class="fa-solid fa-magnifying-glass"></i> {{ __('search') }} </a> 
				</div>
				<div class="col-xl-2 p-r-0">
					<button type="reset" class="btn custom-reset w-100 reset-button" data-id="1">
						<i class="fa-solid fa-rotate-left"></i> Reset
					</button>
				</div>
			</div>
		</div>
		<div class="row custom-dropdown">
			<div class="col-lg-6 mb-2">
				<div class="row">
					<div class="col-lg-4">
						<div class="btn-group">
							<button type="button" class="btn action-btn add-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item text-danger change_multi_approve" data-mode="reject">Reject</a></li>
								<li><a class="dropdown-item text-success change_multi_approve" data-mode="accept">Accept</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12 mb-2">
				<div id="selectionMessage" class="alert alert-info py-2 px-3 d-flex justify-content-between align-items-center" style="display: none !important;">
					<span id="selectionText"></span>
					<div>
						<a href="javascript:void(0);" id="selectAllMatchingLink" style="display: none;">Select all matching <span id="totalCount"></span> rows</a>
						<a href="javascript:void(0);" id="clearSelection" class="ms-3 text-danger">Clear selection</a>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable1" id="kycTable">
						<thead>
							<tr>
								<th>
									<label class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" id="checkKycAll">
									</label>
								</th>
								<th>Full Name</th>
								<th>Email</th>
								<th>KYC Requested At</th>
								<th>Status</th>
								<th class="text-end">Actions</th>
							</tr>
						</thead>
						{{--<tbody>
						@foreach($documents as $document)
							<tr>
								@if($documents->count() > 0)
								<td>
									<label class="form-check form-check-inline">
										<input class="form-check-input row-checkbox" type="checkbox" value="{{ $document->id }}">
									</label>
								</td>
								@endif
								<td>{{ $document->get_client->first_name .''. $document->get_client->last_name }}</td>
								<td>{{ $document->get_client->email ?? '' }}</td>
								<td>{{ Carbon::parse($document->created_at ?? '')->format('d M y') }}</td>
								<td>
									<div class="dropdown action-label">
										@if($document->status == 1)
											<span class="badge badge-soft-warning"><i class="las la-clock"></i> {{ __('Pending') }}</span>
										@endif
										@if($document->status == 2)
											<span class="badge badge-soft-info"><i class="las la-check-double"></i>
											{{ __('Approved') }}</span>
										@endif
										@if($document->status == 0)
											<span class="badge badge-soft-danger"><i class="las la-times-circle"></i> {{ __('Reject') }}</span>
										@endif
									</div>
								</td>
								<td class="text-end">
									<div class="dropdown dropdown-action">
										<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
										<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item kyc-documents-data" href="javascript:void(0)" data-id="{{ $document->id}}" data-url="{{ route('kyc-document') }}"><i class="fa-regular fa-eye m-r-5"></i> See Details</a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>--}}
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
	<!-- /Page Content -->

@include('modal.kyc-modal')
@include('modal.common')
@endsection 
@section('scripts')
@include('_includes.footer')
<script>
    const kycDataUrl = "{{ route('kyc.data') }}";
</script>
<script src="{{ url('front-assets/js/page/kyc.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs/dayjs.min.js"></script>

@endsection
