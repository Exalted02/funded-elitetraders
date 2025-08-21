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
								<h4>Withdraw Simulated Funds</h4>
							</div>
							<hr class="mt-0">
							<small>Few Steps For Your Payout</small>
							<div class="row mt-3 identity-verification">
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-6">
									<div class="card employee-month-card flex-fill mb-0">
										<div class="card-body">
											<div class="statistic-header">
												<h4>Requirements For Withdrawl</h4>
											</div>
											<hr class="mt-0">
											@php
												//$percentage_maximum_drawdown = ($total_day > 0) ? ($trading_day / $total_day) * 100 : 0;
												//$percentage_calender_drawdown = ($total_day > 0) ? ($current_day / $total_day) * 100 : 0;
											@endphp
											<div class="stats-list">
												<div class="stats-info1">
													{{--<p class="d-flex justify-content-between mb-1"><small>{{$trading_day}} Trading Day</small> <small>{{$total_day}} Trading Days</small></p>--}}
													<p class="text-end mb-1"><small>{{$days_remaining}}</small></p>
													<div class="progress">
														<div class="progress-bar bg-info" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: {{$percent_bar}}%"></div>
													</div>
												</div>
											</div>
											{{--<div class="stats-list mt-4">
												<div class="stats-info1">
													<p class="d-flex justify-content-between mb-0"><small>{{$current_day}} Calendar Day</small> <small>{{$total_day}} Calendar Days</small></p>
													<div class="progress">
														<div class="progress-bar bg-info" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: {{$percentage_calender_drawdown}}%"></div>
													</div>
												</div>
											</div>--}}
											<div class="d-flex justify-content-between mt-5">
												<div>
													<span class="d-block">You Will Be Eligible At:</span>
												</div>
												<div>
													<span class="">{{change_date_format($eligible_date, 'Y-m-d', 'd M, y')}}</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-6">
									<div class="card employee-month-card flex-fill mb-0">
										<div class="card-body">
											<div class="statistic-header">
												<h4>Request Your Payout</h4>
											</div>
											<hr class="mt-0">
											<small>After the initial conditions, if profitable, you can request a payout of your shared profit</small>
											@if($eligible_date <= Carbon\Carbon::now()->format('Y-m-d') || Auth::user()->eligible_withdraw == 1)
												<div><button class="btn btn-primary w-100 mt-3 submit-withdraw" data-url="{{route('client.withdraw.withdraw-request-amount')}}">Submit A Withdrawl</button></div>
											@else
												<div><button class="btn btn-primary w-100 mt-3" disabled>You are not eligible now for Withdrawl</button></div>
											@endif
											<div class="d-flex justify-content-between mt-5">
												<div>
													<span class="d-block">Your Current Profit Split:</span>
												</div>
												<div>
													<span class="text-success">80%</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
                {{--<div class="col-lg-12">
					<div class="card employee-month-card flex-fill">
						<div class="card-body">
							<div class="statistic-header">
								<h4>Latest Payouts</h4>
							</div>
							<hr class="mt-0">
							<div class="employee-month-details d-flex align-items-center justify-content-between mb-0">
								<p>2 March 2025 | Withdrawal Of $9,927 USD</p>
								<button class="btn btn-sm btn-warning">
									<i class="la la-hourglass"></i> Pending
								</button>
							</div>
							<div class="employee-month-details d-flex align-items-center justify-content-between mb-0 mt-1">
								<p>2 March 2025 | Withdrawal Of $9,927 USD</p>
								<button class="btn btn-sm btn-success">
									<i class="las la-check-double"></i> Confirmed
								</button>
							</div>
							<div class="employee-month-details d-flex align-items-center justify-content-between mb-0 mt-1">
								<p>2 March 2025 | Withdrawal Of $9,927 USD</p>
								<button class="btn btn-sm btn-danger">
									<i class="las la-times-circle"></i></i> Rejected
								</button>
							</div>
							<div class="employee-month-details d-flex align-items-center justify-content-between mb-0 mt-1">
								<p>2 March 2025 | Withdrawal Of $9,927 USD</p>
								<button class="btn btn-sm btn-danger">
									<i class="las la-times-circle"></i></i> Rejected
								</button>
							</div>
						</div>
					</div>
				</div>--}}
			</div>
        </div>
        <!-- /Page Content -->

    </div>
    <!-- /Page Wrapper -->

@include('modal.client-withdraw-modal')
@include('modal.common')
@endsection 
@section('scripts')
@include('_includes.footer')
<script src="{{ url('front-assets/js/page/client-withdraw.js') }}"></script>
<!-- Chart JS -->
<script src="{{ url('front-assets/plugins/c3-chart/d3.v5.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/c3.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/chart-data.js') }}"></script>

@endsection

