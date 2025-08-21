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
					<h3 class="page-title">Payouts</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="">Payouts</a></li>
						<li class="breadcrumb-item active">Payouts</li>
					</ul>
				</div>
				{{--<div class="col-md-8 float-end ms-auto">
					<div class="d-flex title-head">
						<div class="view-icons">
							<a href="javascript:void(0);" class="list-view btn btn-link" id="filter_search"><i class="las la-filter"></i></a>
						</div>
					</div>
				</div>--}}
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		{{--<div class="filter-filelds">
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
		</div>--}}
		<div class="row">
			<div class="col-lg-6 mb-2">
				{{--<div class="btn-group">
					<button type="button" class="btn action-btn add-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" onclick="change_multi_status('1','Client_payout_request','{{url('change-multi-status')}}')">Accept</a></li>
						<li><a class="dropdown-item" onclick="change_multi_status('0','Client_payout_request','{{url('change-multi-status')}}')">Reject</a></li>
					</ul>
				</div>--}}
				<button type="button" class="btn btn-info multi-payout-status">Change Status</button>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
				<div class="card dash-widget">
					<div class="card-body">
						<span class="dash-widget-icon"><i class="fa-solid fa-money-bill-wave text-primary"></i></span>
						<div class="dash-widget-info">
							<h3 class="text-primary" id="pending_payout"></h3>
							<span>Pending Payouts</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
				<div class="card dash-widget">
					<div class="card-body">
						<span class="dash-widget-icon"><i class="fa-solid fa-xmark text-danger"></i></span>
						<div class="dash-widget-info">
							<h3 class="text-danger" id="rejected_payout"></h3>
							<span>Rejected Payouts</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
				<div class="card dash-widget">
					<div class="card-body">
						<span class="dash-widget-icon"><i class="fa-solid fa-check text-success"></i></span>
						<div class="dash-widget-info">
							<h3 class="text-success" id="accepted_payout"></h3>
							<span>Accepted Payouts</span>
						</div>
					</div>
				</div>
			</div>
		</div>
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
					<table class="table table-striped custom-table datatable--" id="payoutTable">
						<thead>
							<tr>
								<th>
									<label class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" id="checkAll">
									</label>
								</th>
								<th>Trader Email</th>
								<th>Crypto options</th>
								<th>Crypto address</th>
								<th>Crypto platform</th>
								<th>Phone number</th>
								<th>Experienced</th>
								<th>Created At</th>
								<th>Amount ({{get_currency_symbol()}})</th>
								<th>Status</th>
							</tr>
						</thead>
						{{--<tbody>
							@foreach($list as $val)
							<tr>
								<td>
									<label class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" name="chk_id" data-emp-id="{{ $val->id }}">
									</label>
								</td>
								<td>{{$val->get_user_details->email ?? ''}}</td>
								<td>{{change_date_format($val->created_at, 'Y-m-d H:i:s', 'd M y')}}</td>
								<td>{{$val->requested_amount ?? ''}}</td>
								<td>
									@if($val->status == 0)
									<button type="button" class="btn btn-sm btn-outline-primary rounded-pill payout-status" data-id="{{ $val->id }}" data-url="{{route('payouts.payout-details')}}">Pending</button>
									@elseif($val->status == 1)
									<button type="button" class="btn btn-sm btn-outline-success rounded-pill payout-status" data-id="{{ $val->id }}" data-url="{{route('payouts.payout-details')}}">Accept</button>
									@elseif($val->status == 2)
									<button type="button" class="btn btn-sm btn-outline-danger rounded-pill payout-status" data-id="{{ $val->id }}" data-url="{{route('payouts.payout-details')}}">Reject</button>
									@endif
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
@include('modal.payouts-modal')
@include('modal.common')
@endsection 
@section('scripts')
@include('_includes.footer')
<script>
    const payoutDataUrl = "{{ route('payouts.payoutdata') }}";
</script>
<script src="{{ url('front-assets/js/page/payouts.js') }}"></script>
@endsection
