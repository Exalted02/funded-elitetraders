<div class="modal custom-modal fade" id="delete_model" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon bg-danger" id="delete-prospect-msg">
						<i class="la la-trash-restore"></i>
					</div>
					<h3>{{ __('are_you_sure') }}, {{ __('you_want_delete') }}</h3>
					<p>{{ __('customer') }} "<span id="list_name"></span>" {{ __('from_your_account') }}</p>
					<div class="col-lg-12 text-center form-wizard-button">
						<a href="#" class="button btn-lights" data-bs-dismiss="modal">{{ __('not_now') }}</a>
						<a href="javascript:void(0);" class="btn btn-primary data-id-pcode-list" data-url="">{{ __('okay') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Add product code -->
<div id="add_challenge" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Create Challenge</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="step-one">
					<form id="frmTraderEmail" action="{{ route('challenges.check-email') }}">
						<div class="row">
							<div class="col-sm-12">
								<div class="input-block mb-3">
									<label class="col-form-label">Trader`s Email<span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="trader_email" id="trader_email" placeholder="Trader`s Email">
									<div class="invalid-feedback">Trader`s Email is required.</div>
								</div>
							</div>
						</div>					
						<div class="modal-btn delete-action mt-3">
							<div class="row">
								<div class="col-6">
									<a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-sm w-100 btn-secondary">Cancel</a>
								</div>
								<div class="col-6">
									<a href="javascript:void(0);" class="btn btn-sm w-100 btn-primary save-challenge-email">Next <i class="la la-arrow-circle-right"></i></a>
								</div>
							</div>
						</div>
					</form>	
				</div>
				<div id="step-two" style="display:none;">
					<input type="hidden" id="trader_challenge_amount_url" value="{{route('challenges.trader-challenge-amount')}}">
					<form id="frmChallenge" action="{{ route('challenges.challenge-submit') }}" enctype="multipart/form-data">
						<div class="row">
							<div class="col-sm-12">
								<div class="input-block mb-3">
									<label class="col-form-label">Trader`s Email<span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="traders_email" id="traders_email" placeholder="Trader`s Email">
									<div class="invalid-feedback"></div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="input-block mb-3">
									<label class="col-form-label">Trader`s First Name<span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="trader_first_name" id="trader_first_name" placeholder="Trader`s First Name">
									<div class="invalid-feedback"></div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="input-block mb-3">
									<label class="col-form-label">Trader`s Last Name<span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="trader_last_name" id="trader_last_name" placeholder="Trader`s Last Name">
									<div class="invalid-feedback"></div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="input-block mb-3">
									<label class="col-form-label">Trader`s Phone (Optional)</label>
									<input class="form-control" type="text" name="trader_phone_number" id="trader_phone_number" placeholder="Trader`s Phone (Optional)">
									<div class="invalid-feedback"></div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="input-block">
									<label class="col-form-label">Challenge<span class="text-danger">*</span></label>
									<select class="select" name="trader_challenge" id="trader_challenge"> 
										<option value="">Please select</option>
										@foreach($c_list as $c_list_val)
										<option value="{{$c_list_val->id}}">{{$c_list_val->title}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="input-block mb-3">
									<label class="col-form-label">Amount Paid<span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="trading_amount" id="trading_amount" placeholder="Amount Paid">
									<div class="invalid-feedback"></div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="input-block mb-3">
									<label class="col-form-label">Proof Document (Optional)</label>
									<input class="form-control" type="file" name="trading_document" id="trading_document" placeholder="Proof Document (Optional)">
									<div class="invalid-feedback"></div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="input-block mb-3">
									<label class="col-form-label">Comment</label>
									<textarea class="form-control" name="comment" id="comment" placeholder="Comment"></textarea>
									<div class="invalid-feedback"></div>
								</div>
							</div>
						</div>					
						<div class="modal-btn delete-action mt-3">
							<div class="row">
								<div class="col-6">
									<a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-sm w-100 btn-secondary">Cancel</a>
								</div>
								<div class="col-6">
									<a href="javascript:void(0);" class="btn btn-sm w-100 btn-primary save-challenge">Create <i class="la la-arrow-circle-right"></i></a>
								</div>
							</div>
						</div>
					</form>
				</div>				
			</div>
		</div>
	</div>
</div>
<!-- Add product code -->
<div id="import_challenge" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Import Challenge</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ route('challenges.challenge-import-submit') }}" method="post" enctype="multipart/form-data">
				@csrf
					<div class="row">
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">Upload CSV<span class="text-success">(Upload only xlsx or csv file)</span><span class="text-danger">*</span></label>
								<input class="form-control" type="file" name="import_excel" accept=".xlsx, .xls, .csv" required>
								<div class="invalid-feedback"></div>
							</div>
						</div>
					</div>					
					<div class="modal-btn delete-action mt-3">
						<div class="row">
							<div class="col-6">
								<a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-sm w-100 btn-secondary">Cancel</a>
							</div>
							<div class="col-6">
								<button class="btn btn-sm w-100 btn-primary" type="submit">Submit <i class="la la-arrow-circle-right"></i></button>
							</div>
						</div>
					</div>
				</form>							
			</div>
		</div>
	</div>
</div>
<!-- /Add product code -->

<!-- update Success message -->
<div class="modal custom-modal fade" id="updt_success_msg" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon">
						<i class="la la-pencil"></i>
					</div>
					<h3>{{ __('data_updated_successfully') }}!!!</h3>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- update Success message -->
<div class="modal custom-modal fade" id="success_msg" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon">
						<i class="la la-plus-circle"></i>
					</div>
					<h3>Challenge created successfully!!!</h3>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- View Details -->
<div class="modal custom-modal fade" id="view_details" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLgLabel">Challenge Details
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			
			<div class="modal-body challenge-info">
				
			</div>
		</div>
	</div>
</div>


<!-- Adjust Balance Model -->
<div class="modal custom-modal fade" id="adjust_balance_model" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLgLabel">Challenge Settings
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<ul class="personal-info">
					<li>
						<div class="title">Challenge Balance({{get_currency_symbol()}}) :</div>
						<div class="text"><span id="current_balance"></span></div>
						<input type="hidden" id="current_balance_val">
					</li>
					<li>
						<div class="title">Adjust Balance({{get_currency_symbol()}}) :</div>
						<div class="text"><span id="adjust_balance"></span></div>
					</li>
					<li class="dash-statistics">
						<div class="row stats-info w-100">
							<div class="col-12">
								<form id="frmAdjustBalance" action="{{ route('challenges.adjust-balance') }}">
									<input type="hidden" name="adjust_amount_challenge" id="adjust_amount_challenge">
									<input type="hidden" name="adjust_amount_user" id="adjust_amount_user">
									<div class="file-download">
										<a href="javascript:void(0)" class="color-white">Percent(%)<span class="text-danger">*</span></a>
										<input class="form-control" type="text" name="adjust_amount" id="adjust_amount" placeholder="Enter the amount here.">
										<div class="invalid-feedback"></div>
										<div class="text-start mt-2">The new balance will be approx : {{get_currency_symbol()}}<span id="new_amount">1525.36</span></div>
									</div>
								</form>
							</div>
						</div>
					</li>
				</ul>
				<div class="modal-btn delete-action">
					<div class="row">
						{{--<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-danger submit-adjust-balance" data-mode="remove"><i class="las la-times-circle"></i> Remove</a>
						</div>--}}
						<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-danger" data-bs-dismiss="modal"><i class="las la-times-circle"></i> Cancel</a>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-success submit-adjust-balance" data-mode="add"><i class="las la-check-double"></i> Add</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal custom-modal fade" id="adjust_multi_user_balance_model" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLgLabel">Challenge Settings
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>			
			<div class="modal-body">
				<ul class="personal-info">
					<li class="dash-statistics">
						<div class="row stats-info w-100">
							<div class="col-12">
								<form id="frmMultiAdjustBalance" action="{{ route('challenges.multi-adjust-balance') }}">
									<div class="file-download">
										<a href="javascript:void(0)" class="color-white">Percent(%) : </a>
										<input type="text" class="form-control" name="adjust_percent" id="adjust_percent" placeholder="Enter the percent(%) here.">
										<div class="invalid-feedback"></div>
									</div>
								</form>
							</div>
						</div>
					</li>
				</ul>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-danger" data-bs-dismiss="modal"><i class="las la-times-circle"></i> Cancel</a>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-success submit-multi-adjust-balance" data-mode="add"><i class="las la-check-double"></i> Add</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Update User Balance -->
<div class="modal custom-modal fade" id="adjust_balance_msg" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon">
						<i class="la la-pencil"></i>
					</div>
					<h3 class="adjust_balance_msg">Updated!!!</h3>
				</div>
			</div>
		</div>
	</div>
</div>