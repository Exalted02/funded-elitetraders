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
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<hr>
		<div class="row">
			@php
				$start = new \DateTime('2024-06-01');
				$end = new \DateTime(); // current month

				$months = [];
				while ($start <= $end) {
					$value = $start->format('Y-m');      // e.g. 2023-04
					$label = $start->format('Y - F');    // e.g. 2023 - April
					$months[] = ['value' => $value, 'label' => $label];
					$start->modify('+1 month');
				}
			@endphp
			<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
				<form method="post" action="" id="randomPayoutSearch">
				@csrf
					<div class="input-block">
						<label class="">Month</label>
						<select class="select" name="searchMonth" id="searchMonth" >
							@foreach($months as $month)
								<option value="{{ $month['value'] }}" {{ $month['value'] === date('Y-m') ? 'selected' : '' }}>{{ $month['label'] }}</option>
							@endforeach
						</select>
					</div>
				</form>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
				<div class="card dash-widget">
					<div class="card-body">
						<div class="dash-widget-info1 text-start">
							<h4 class="mb-2">Number of payouts</h4>
							<span class="text-muted"><strong id="totalPayoutText"></strong></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
				<div class="card dash-widget">
					<div class="card-body">
						<div class="dash-widget-info1 text-start">
							<h4 class="mb-2">Payouts USD</h4>
							<span class="text-muted"><strong id="payoutUsdText"></strong></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
				<div class="card dash-widget">
					<div class="card-body">
						<div class="dash-widget-info1 text-start">
							<h4 class="mb-2">Highest payout</h4>
							<span class="text-muted"><strong id="highestPayoutText"></strong></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable--" id="randomPayoutTable">
						<thead>
							<tr>
								<th>Login</th>
								<th></th>
								<th>Profit</th>
								<th>Account size</th>
								<th>Country</th>
							</tr>
						</thead>
						{{--<tbody>
							@foreach($list as $val)
							<tr>
								<td>{{$val->login_id ?? ''}}</td>
								<td>								
									<button type="button" class="btn btn-sm btn-outline-success rounded-pill">{{$val->status ?? ''}}</button>
								</td>
								<td>{{get_currency_symbol()}}{{$val->profit ?? ''}}</td>
								<td>{{get_currency_symbol()}}{{$val->account_amount ?? ''}}</td>
								<td>{{$val->country_name ?? ''}}</td>
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
@include('modal.common')
@endsection 
@section('scripts')
@include('_includes.footer')
<script src="{{ url('front-assets/js/page/random-payouts.js') }}"></script>
<script>
	let payoutTable = $('#randomPayoutTable').DataTable({
		processing: true,
		serverSide: true,
		pageLength: 50,
		ajax: {
			url: "{{ route('payouts.data') }}",
			// type: 'POST',
			data: function(d) {
				d._token = '{{ csrf_token() }}';
				d.searchMonth = $('#searchMonth').val();
			},
			dataSrc: function(json) {
				$('#totalPayoutText').text(json.summary.total_payout > 0 ? '+ ' + json.summary.total_payout + ' Paid' : '0 Paid');
				$('#payoutUsdText').text(json.summary.payout_usd);
				$('#highestPayoutText').text(json.summary.highest_payout);
				return json.data;
			}
		},
		columns: [
			{ data: 'login_id', name: 'login_id' },
			{ data: 'status', name: 'status', orderable: false, searchable: false },
			{ data: 'profit', name: 'profit' },
			{ data: 'account_amount', name: 'account_amount' },
			{ data: 'country_name', name: 'country_name' },
		]
	});

	// Reload DataTable on filter change
	$('#searchMonth').on('change', function() {
		payoutTable.ajax.reload();
	});
</script>
@endsection
