<div class="modal custom-modal fade" id="delete_model" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon bg-danger" id="delete-icon">
						<i class="la la-trash-restore"></i>
					</div>
					<h3>Are you sure, you want to delete</h3>
					<p>uaer "<span id="delete_modal_name_data"></span>" from your account?</p>
					<div class="col-lg-12 text-center form-wizard-button">
						<a href="#" class="button btn-lights" data-bs-dismiss="modal">{{ __('not_now') }}</a>
						<a href="javascript:void(0);" class="btn btn-primary final-delete-submit" data-url="{{ route('users.final_delete_submit') }}">{{ __('okay') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal custom-modal fade" id="multi_allow_withdraw_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<h3>Are you sure, you want to allow withdraw?</h3>
					<div class="modal-btn delete-action mt-5">
						<div class="row">
							<div class="col-6">
								<a href="javascript:void(0);" class="btn btn-sm w-100 btn-danger" data-bs-dismiss="modal"><i class="las la-times-circle"></i> Cancel</a>
							</div>
							<div class="col-6">
								<a id="submit_allow_withdraw" data-url="{{ route('users.multi-allow-withdraw-submit')}}" href="javascript:void(0);" class="btn btn-sm w-100 btn-success"><i class="las la-check-double"></i> Okay</a>
							</div>
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
					<h3>Data updated successfully!!!</h3>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- update status Success message -->
<div class="modal custom-modal fade" id="update_status" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon">
						<i class="la la-pencil"></i>
					</div>
					<h3>Account status updated successfully!!!</h3>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Edit user data -->
<div id="edit_user" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit User</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="frmUserSubmit" action="{{ route('users.user-data-submit') }}">
					<input type="hidden" name="id" id="id">
					<div class="row">
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">Email<span class="text-danger">*</span></label>
								<input class="form-control" type="text" name="email" id="email" placeholder="Email" disabled>
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">First Name<span class="text-danger">*</span></label>
								<input class="form-control" type="text" name="first_name" id="first_name" placeholder="First Name">
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">Last Name<span class="text-danger">*</span></label>
								<input class="form-control" type="text" name="last_name" id="last_name" placeholder="Last Name">
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">Phone (Optional)</label>
								<input class="form-control" type="text" name="phone_number" id="phone_number" placeholder="Phone (Optional)">
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">Password</label>
								<input class="form-control" type="password" name="password" id="password" placeholder="Password">
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
								<a href="javascript:void(0);" class="btn btn-sm w-100 btn-primary update-user">Update</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Edit user data -->


<!-- Adjust Balance Model -->
<div class="modal custom-modal fade" id="adjust_balance_model" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLgLabel">User Settings
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<ul class="personal-info">
					<li>
						<div class="title">Current Balance({{get_currency_symbol()}}) :</div>
						<div class="text"><span id="current_balance"></span></div>
						<input type="hidden" id="current_balance_val">
					</li>
					<li class="dash-statistics">
						<div class="row stats-info w-100">
							<div class="col-12">
								<form id="frmAdjustBalance" action="{{ route('users.adjust-balance') }}">
									<input type="hidden" name="adjust_amount_user" id="adjust_amount_user">
									<div class="file-download">
										<a href="javascript:void(0)" class="color-white">Amount({{get_currency_symbol()}})<span class="text-danger">*</span></a>
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
<div class="modal custom-modal fade" id="multi_send_email_model" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLgLabel">Send Email
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>			
			<div class="modal-body">
				<ul class="personal-info">
					<li class="dash-statistics">
						<div class="row stats-info1 w-100">
							<div class="col-12">
								<form id="frmMultiSendEmail" action="{{ route('users.multi-send-user-email') }}">
									<div class="file-download">
										<a href="javascript:void(0)" class="color-white">Message Subject : </a>
										<input type="text" class="form-control" name="message_subject" />
										<div class="invalid-feedback"></div>
									</div>
									<div class="file-download">
										<a href="javascript:void(0)" class="color-white">Message : </a>
										<textarea class="form-control summernote" name="message" id="message" ></textarea>
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
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-danger" data-bs-dismiss="modal"> Cancel</a>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-success submit-multi-send-user-email"> Submit</a>
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
				<h5 class="modal-title" id="exampleModalLgLabel">User Settings
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>			
			<div class="modal-body">
				<ul class="personal-info">
					<li class="dash-statistics">
						<div class="row stats-info w-100">
							<div class="col-12">
								<form id="frmMultiAdjustBalance" action="{{ route('users.multi-adjust-balance') }}">
									<div class="file-download">
										<a href="javascript:void(0)" class="color-white">Percent (%) : </a>
										<input type="text" class="form-control" name="adjust_percent" id="adjust_percent" placeholder="Type percent(%) here.">
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
<!-- Update User Balance -->
<div class="modal custom-modal fade" id="email_send_msg" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<h3 class="">Email sended!!!</h3>
				</div>
			</div>
		</div>
	</div>
</div>