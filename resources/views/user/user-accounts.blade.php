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
					<h3 class="page-title">User Accounts</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="">User</a></li>
						<li class="breadcrumb-item active">User</li>
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
		{{--<div class="filter-filelds" id="filter_inputs">
			<form name="search-frm" method="post" action="" id="search-product-code-frm">
				@csrf
				<div class="row filter-row">
					<div class="col-xl-2">  
						 <div class="input-block">
							 <select class="select" name="search_status">
								<option value="">{{ __('please_select') }}</option>
								<option value="1">Last 30 Days</option>
								<option value="0">Last 2 Months</option>
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
			</form>
		</div>--}}
		{{--<div class="filter-filelds">
			<div class="row filter-row">
				<div class="col-xl-2">  
					 <div class="input-block">
						 <select class="select" name="search_status">
							<option value="">{{ __('please_select') }}</option>
							<option value="1">Last 30 Days</option>
							<option value="0">Last 2 Months</option>
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
		{{--<div class="row">
			<div class="col-md-3 mb-2">
				<button type="button" class="btn btn-info multi-allow-withdraw"><i class="la la-plus m-r-5"></i> Allow withdraw</button>
			</div>
		</div>--}}
		<div class="row">
			<div class="col-md-3 mb-2">
				<button type="button" class="btn btn-info multi-send-email"><i class="la la-envelope m-r-5"></i> Send Email</button>
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
					<table class="table table-striped custom-table datatable1" id="userTable">
						<thead>
							<tr>
								<th>
									<label class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" id="checkUserAll">
									</label>
								</th>
								<th>Name</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Current Balance ({{get_currency_symbol()}})</th>
								<th>Created At</th>
								<th>Dashboard</th>
								<th>Status</th>
								<th>Withdraw Status</th>
								<th class="text-end">Actions</th>
							</tr>
						</thead>
						{{--<tbody>
						@foreach($list as $val)
							<tr>
								@if($list->count() > 0)
								<td>
									<label class="form-check form-check-inline">
										<input class="form-check-input row-checkbox" type="checkbox" value="{{ $val->id }}">
									</label>
								</td>
								@endif
								<td>{{$val->name ?? ''}}</td>
								<td>{{$val->email ?? ''}}</td>
								<td>{{$val->phone_number ?? ''}}</td>
								<td>{{$val->users_balances ?? ''}}</td>
								<td>{{change_date_format($val->created_at, 'Y-m-d H:i:s', 'd M y')}} </td>
								<td>
									<div class="action-label">
										<a class="btn btn-white btn-sm btn-rounded" href="{{ route('admin.impersonate', $val->id) }}" dada-id="{{ $val->id}}">
											<i class="fa-regular fa-circle-dot text-purple"></i> User Dashboard
										</a>
									</div>
								</td>
								<td>
								@if($val->status ==1)
									<div class="dropdown action-label">
										<a class="btn btn-white btn-sm badge-outline-success dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="fa-regular fa-circle-dot text-success"></i> {{ __('active') }}
										</a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('users.user-update-status') }}" data-type="1"><i class="fa-regular fa-circle-dot text-success"></i> {{ __('active') }}</a>
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('users.user-update-status') }}" data-type="0"><i class="fa-regular fa-circle-dot text-danger"></i> Suspend</a>
										</div>
									</div>
								 @else
									<div class="dropdown action-label">
										<a class="btn btn-white btn-sm badge-outline-danger dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="fa-regular fa-circle-dot text-danger"></i> Suspended
										</a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('users.user-update-status') }}" data-type="1"><i class="fa-regular fa-circle-dot text-success"></i> {{ __('active') }}</a>
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('users.user-update-status') }}" data-type="0"><i class="fa-regular fa-circle-dot text-danger"></i> Suspend</a>
										</div>
									</div> 
								 
								 @endif
								</td>
								<td>
								@if($val->eligible_withdraw ==1)
									<div class="dropdown action-label">
										<a class="btn btn-white btn-sm badge-outline-success dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="fa-regular fa-circle-dot text-success"></i> Allowed
										</a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item update-eligible-withdraw" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('users.user-allow-withdraw') }}" data-type="1"><i class="fa-regular fa-circle-dot text-success"></i> Allow</a>
											<a class="dropdown-item update-eligible-withdraw" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('users.user-allow-withdraw') }}" data-type="0"><i class="fa-regular fa-circle-dot text-danger"></i> Not Allow</a>
										</div>
									</div>
								 @else
									<div class="dropdown action-label">
										<a class="btn btn-white btn-sm badge-outline-danger dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="fa-regular fa-circle-dot text-danger"></i> Not Allowed
										</a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item update-eligible-withdraw" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('users.user-allow-withdraw') }}" data-type="1"><i class="fa-regular fa-circle-dot text-success"></i> Allowed</a>
											<a class="dropdown-item update-eligible-withdraw" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('users.user-allow-withdraw') }}" data-type="0"><i class="fa-regular fa-circle-dot text-danger"></i> Not Allowed</a>
										</div>
									</div> 
								 
								 @endif
								</td>
								<td class="text-end">
									<div class="dropdown dropdown-action">
										<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item edit-data" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('users.user-update-data') }}"><i class="fa-solid fa-pencil m-r-5"></i> {{ __('edit') }}</a>
											<a class="dropdown-item delete-data" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('users.get_delete_data') }}"><i class="fa-regular fa-trash-can m-r-5"></i> {{ __('delete') }}</a>
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
@include('modal.user-modal')
@include('modal.common')
@endsection 
@section('scripts')
@include('_includes.footer')
<script>
	const userDataUrl = "{{ route('users.data') }}";
</script>
<script src="{{ url('front-assets/js/page/user.js') }}"></script>
<link href="{{ url('front-assets/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    <script src="{{ url('front-assets/summernote/summernote-lite.min.js') }}"></script>
	<script>
		$('.summernote').summernote({
			height: 300,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'italic', 'underline']],
				['fontsize', ['fontsize']],
				['style', ['fontname', 'color']],
				['para', ['ul', 'ol', 'paragraph']],
				['height', ['height']],
				['insert', ['link', 'picture', 'video']],
				['view', ['codeview']],
			],
			callbacks: {
				onInit: function() {
					// $('.note-editable').css('background-color', 'white');
				}
			}
		});
	</script>
@endsection
