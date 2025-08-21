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

<div class="modal custom-modal fade" id="confirm_kyc_status_change" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<h3>Are you sure, you want to <span id="status_type"></span> KYC?</h3>
					<div class="modal-btn delete-action mt-5">
						<div class="row">
							<div class="col-6">
								<a href="javascript:void(0);" class="btn btn-sm w-100 btn-danger" data-bs-dismiss="modal"><i class="las la-times-circle"></i> Cancel</a>
							</div>
							<div class="col-6">
								<a id="kyc_status_btn" data-url="{{ route('multi-kyc-doc-status-update')}}" href="javascript:void(0);" class="btn btn-sm w-100 btn-success"><i class="las la-check-double"></i> Okay</a>
							</div>
						</div>
					</div>
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
				<h5 class="modal-title" id="exampleModalLgLabel">KYC Submission
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			
			<div class="modal-body">
				<ul class="personal-info">
					<li>
						<div class="title">Email:</div>
						<div class="text"><span id="email"></span></div>
					</li>
					<li>
						<div class="title">Trader ID:</div>
						<div class="text"><span id="trader_id"></span></div>
					</li>
					<li>
						<div class="title">Full Name:</div>
						<div class="text"><span id="full_name"></span></div>
					</li>
					<li>
						<div class="title">Status:</div>
						<div class="text text-warning" id="pending">{{ __('Pending') }}</div>
						<div class="text text-success" id="accept">{{ __('Approved') }}</div>
						<div class="text text-danger" id="reject">{{ __('Reject') }}</div>
					</li>
					<li>
						<div class="title">Created At:</div>
						<div class="text"><span id="created_date"></span></div>
					</li>
					<li>
						<div class="title">Downloadable Documents:</div>
						<div class="text"></div>
					</li>
					<li class="dash-statistics">
						<div class="row stats-info">
							<div class="col-4">
								<div class="file-download text-center">
									<a href="javascript:void(0)" class="color-white">Frontal ID</a>
									<a id="view_frontal" class="btn btn-sm w-100 btn-info rounded-pill" href="#"><i class="la la-eye"></i> View</a>
								</div>
							</div>
							<div class="col-4">
								<div class="file-download text-center">
									<a href="javascript:void(0)" class="color-white">Back ID</a>
									<a id="view_back" class="btn btn-sm w-100 btn-info rounded-pill" href="#"><i class="la la-eye"></i> View</a>
								</div>
							</div>
							<div class="col-4">
								<div class="file-download text-center">
									<a href="javascript:void(0)" class="color-white">Residence ID</a>
									<a id="view_residence" class="btn btn-sm w-100 btn-info rounded-pill" href="#"><i class="la la-eye"></i> View</a>
								</div>
							</div>
						</div>
					</li>
				</ul>
				<div class="ms-4" id="message-section"></div>
				<div class="modal-btn delete-action mt-2">
					<div class="row">
						<div class="col-6">
						<a id="reject_client_id" href="javascript:void(0);" class="btn btn-sm w-100 btn-danger" data-url="{{ route('kyc-doc-status-update')}}" data-mode="reject"><i class="las la-times-circle"></i> Reject</a>
						
						{{--<a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-sm w-100 btn-danger"><i class="las la-times-circle"></i> Reject</a>--}}
						</div>
						<div class="col-6">
							<a id="accept_client_id" data-url="{{ route('kyc-doc-status-update')}}" href="javascript:void(0);" class="btn btn-sm w-100 btn-success" data-mode="accept"><i class="las la-check-double"></i> Accept</a>
						</div>
						<div class="col-12 mt-3">
							<a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-sm w-100 btn-secondary">Close</a>
						</div>
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
