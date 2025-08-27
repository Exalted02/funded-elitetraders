@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="{{ url('front-assets/plugins/c3-chart/c3.min.css') }}">
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<style>
.tooltip-custom {
    background: rgba(0, 0, 0, 0.85);
    padding: 10px;
    border-radius: 8px;
    color: #fff;
    font-family: sans-serif;
}

</style>
@endsection
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid pb-0">
			@if($account_message)
			{{--<div class="alert alert-warning alert-dismissible fade show" role="alert">
				The challenge failed and the account is blocked.
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="las la-times"></i></button>
			</div>--}}
			@endif
			@if(count($challenge) > 0)
			<div class="multiple-items">
				@foreach($challenge as $k=>$val)
				@php
				$adjust_users_balance = App\Models\Adjust_users_balance::where('user_id', Auth::id())->where('challenge_id', $val->id)->where('type', 1)->sum('amount_paid');
				@endphp
				<div class="card employee-month-card flex-fill" style="margin-right: 15px;">
					<div class="dashboard-card-body">
						<div class="row">
							@if($val->status == 1)
							<div class="col-md-3 right-seperator">
								@php
									$scale_boost_trade_value = 12;
									$max_scale_boost_amt = $val->get_challenge_type->amount * ($scale_boost_trade_value/100);
									
									// calculate percentage progress
									$progress_percent = 0;
									if ($max_scale_boost_amt > 0) {
										$progress_percent = ($adjust_users_balance / $max_scale_boost_amt) * 100;
									}

									// cap at 100
									if ($progress_percent > 100) {
										$progress_percent = 100;
									}
								@endphp
								@if($max_scale_boost_amt <= $adjust_users_balance)
									<div class="reward-badge-container">
										<button class="btn btn-square btn-success"><i class="las la-check-double"></i> Rewarded</button>
									</div>
								@else
								<div class="scale-boost-inner-seperator">
									<div class="card-body-inner-content">
										<div class="text-center">
											<h3>Scale Boost</h3>
										</div>
										<div class="scale-boost mt-3">
											<i class="las la-stop-circle"></i>
											<span>Reach 12% on Funded Phase (not challenge)</span>
										</div>
										<div class="stats-list mt-3">
											<div class="stats-info1">
												
												<p class="mb-1">Current Progress: {{ round($progress_percent, 2) }}%</p>
												<div class="progress">
													<div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{ round($progress_percent, 2) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress_percent }}%"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="">
									<div class="card-body-inner-content">
										<div class="text-center">
											<h3 class="mb-2">Rewards</h3>
										</div>
										<div class="scale-boost scale-boost-green">
											<i class="las la-check-square"></i>
											<span>100% Funded Unlocked</span>
										</div>
										<div class="scale-boost scale-boost-green">
											<i class="las la-check-square"></i>
											<span>Instant 7 Day Payouts</span>
										</div>
										<div class="scale-boost scale-boost-green">
											<i class="las la-check-square"></i>
											<span>Get Double Funding You Have</span>
										</div>
									</div>
								</div>
								@endif
							</div>
							@endif
							<div class="col-md-{{ $val->status == 1 ? 3 : 5 }}">
								<div class="card-body-inner-content">
									@if($val->status == 0)
									<button class="btn btn-square btn-outline-warning"><i class="las la-clock"></i> On Challenge</button>
									@elseif($val->status == 3)
									<button class="btn btn-square btn-outline-warning"><i class="las la-clock"></i> Phase 2</button>
									@elseif($val->status == 1)
									<button class="btn btn-square btn-outline-success"><i class="las la-check-double"></i> Funded</button>
									@elseif($val->status == 2)
									<button class="btn btn-square btn-outline-danger"><i class="las la-times-circle"></i> Failed</button>
									@endif
									
									<div class="mt-3">
										<p>#{{$k+1}} - {{$val->get_challenge_type->title}}</p>
									</div>
									<div class="mt-3">
										<h3>Equity</h3>
										<h4 class="mt-1"><strong>{{get_currency_symbol()}}{{$adjust_users_balance + $val->get_challenge_type->amount}}</strong></h4>
									</div>
									<div class="mt-3">
										<h2>Balance</h2>
										<h3 class="mt-1"><strong>{{get_currency_symbol()}}{{$adjust_users_balance + $val->get_challenge_type->amount}}</strong></h3>
									</div>
									<div class="mt-3">
										<a href="{{ route('client.dashboard', [$val->id]) }}"><button class="btn btn-primary"><i class="las la-eye"></i> View Dashboard</button></a>
									</div>
								</div>
							</div>
							<div class="col-md-{{ $val->status == 1 ? 6 : 7 }}">
								<div class="card-body-inner-content">
									<div id="chart-sracked-{{$k}}"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
			@else
				<div class="text-center"><h1>No challenge found!!!</h1></div>
			@endif
        </div>
        <!-- /Page Content -->

    </div>
    <!-- /Page Wrapper -->

@endsection 
@section('scripts')
<!-- Chart JS -->
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="{{ url('front-assets/plugins/c3-chart/d3.v5.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/c3.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/chart-data.js') }}"></script>
<script>
$('.multiple-items').slick({
  infinite: false,
  slidesToShow: 1,
  slidesToScroll: 1,
  nextArrow: '<i class="las la-chevron-circle-right"></i>',
  prevArrow: '<i class="las la-chevron-circle-left"></i>',
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: true,
        // centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1,
		slidesToScroll: 1,
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: true,
        // centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1,
		slidesToScroll: 1,
      }
    }
  ]
});

// Sample dummy data array, ideally you'll pass this from the controller or use dynamic server data
var challenges = @json($challenge);

challenges.forEach((item, index) => {
	let chartId = `#chart-sracked-${index}`;

	c3.generate({
		bindto: chartId,
		data: {
			columns: [
				['data1', ...item.chart_values],
			],
			type: 'area-spline',
			colors: {
				data1:'#F175B1'
			},
			names: {
				'data1': 'Balance'
			}
		},
		axis: {
			x: {
				type: 'category',
				categories: item.chart_labels
			},
			y: {
				min: item.y_min,
				max: item.y_max,
				padding: {
					top: 0,
					bottom: 0
				},
				tick: {
					format: d3.format(",") // nicely formatted
				}
			}
		},
		legend: {
			show: false
		},
		padding: {
			bottom: 0,
			top: 0
		},
		tooltip: {
			contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
				let i = d[0].index;
				let data = item.tooltip_data[i];

				return `
					<div class='tooltip-custom text-white text-sm'>
						<strong>${item.chart_labels[i]}</strong><br>
						Balance: <strong>$${data.balance.toLocaleString()}</strong><br>
						Target: <strong>$${data.target.toLocaleString()}</strong><br>
						Max Drawdown:<strong>$${data.max_drawdown.toLocaleString()}</strong><br>
						Max Daily Loss:<strong>$${data.max_daily_loss.toLocaleString()}</strong><br>
						Equity: <strong>$${data.equity.toLocaleString()}</strong>
					</div>
				`;
			}
		}
	});
});

setTimeout(() => {
	d3.selectAll(".c3-axis-x text").style("fill", "#FFFFFF");
	d3.selectAll(".c3-axis-y text").style("fill", "#FFFFFF");
}, 500);
</script>
@endsection

