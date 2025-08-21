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

<!-- Status modal -->
<div class="modal custom-modal fade" id="payout_single_status_model" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLgLabel">Change Status
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>			
			<div class="modal-body">
				<ul class="personal-info">
					<li>
						<div class="title">USDC address :</div>
						<div class="text"><span id="usdc_address_val" class="usdc_address_val"></span></div>
					</li>
					<li class="dash-statistics">
						<div class="row stats-info w-100">
							<div class="col-12">
								<form id="frmSingleStatusPayout" action="{{ route('payouts.payouts-update-status') }}">
									<input type="hidden" name="status_user" id="status_user">
									<div class="file-download">
										<a href="javascript:void(0)" class="color-white">Reason : </a>
										<textarea class="form-control h-auto" name="reason" id="reason" rows="3" placeholder="Enter the reason here."></textarea>
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
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-danger update-status" data-mode="2"><i class="las la-times-circle"></i> Reject</a>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-success update-status" data-mode="1"><i class="las la-check-double"></i> Accept</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal custom-modal fade" id="payout_multi_status_model" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLgLabel">Change Status
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>			
			<div class="modal-body">
				<ul class="personal-info">
					<li class="dash-statistics">
						<div class="row stats-info w-100">
							<div class="col-12">
								<form id="frmMultiStatusPayout" action="{{ route('payouts.payouts-multi-update-status') }}">
									<div class="file-download">
										<a href="javascript:void(0)" class="color-white">Reason : </a>
										<textarea class="form-control h-auto" name="reason" id="reason" rows="3" placeholder="Enter the reason here."></textarea>
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
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-danger multi-update-status" data-mode="2"><i class="las la-times-circle"></i> Reject</a>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-success multi-update-status" data-mode="1"><i class="las la-check-double"></i> Accept</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
