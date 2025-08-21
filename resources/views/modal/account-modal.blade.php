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

<!-- View Details -->
<div class="modal custom-modal fade" id="view_account" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLgLabel">Update Account
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="ms-5" id="message-section"></div>
			<div class="modal-body">
				<ul class="personal-info">
					<li>
						<div class="title">First Name:</div>
						<div class="title"><input type="text" class="form-control" id="first_name" name="first_name">
						<div class="invalid-feedback">First name is required.</div>
						</div>
						
					</li>
					<li>
						<div class="title">Last Name:</div>
						<div class="title"><input type="text" id="last_name" class="form-control">
						<div class="invalid-feedback">Last name is required.</div>
						</div>
					</li>
					<li>
						<div class="title">Password:</div>
						<div class="title"><input type="password" id="password" class="form-control">
						<div class="invalid-feedback">Password is required.</div>
						</div>
					</li>
				</ul>
				<div class="ms-4" id="message-section"></div>
				<div class="modal-btn delete-action mt-2">
					<div class="row">
						<div class="col-12 mt-3">
						<button class="btn btn-primary mt-3 save-account">
							<i class="la la-file-alt"></i> Submit
						</button>
						{{--<a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-sm w-100 btn-secondary">Close</a>--}}
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
