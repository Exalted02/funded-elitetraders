<div class="modal custom-modal fade" id="delete_model" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon bg-danger" id="delete-icon">
						<i class="la la-trash-restore"></i>
					</div>
					<h3>Are you sure, you want to delete</h3>
					<p>user "<span id="delete_modal_name_data"></span>" from your account?</p>
					<div class="col-lg-12 text-center form-wizard-button">
						<a href="#" class="button btn-lights" data-bs-dismiss="modal">{{ __('not_now') }}</a>
						<a href="javascript:void(0);" class="btn btn-primary final-delete-submit" data-url="{{ route('certificate.final_delete_submit') }}">{{ __('okay') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Add product code -->
<div id="add_certificate" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-title"></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="frmCertificate" action="{{ route('certificate.certificate-submit') }}">
					<div class="row">
						<input type="hidden" name="hid_id" id="hid_id">
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">Date<span class="text-danger">*</span></label>
								<input class="form-control floating datetimepicker" type="text" name="certificate_date" id="certificate_date">
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">Name<span class="text-danger">*</span></label>
								<input class="form-control" type="text" name="certificate_name" id="certificate_name" placeholder="Name">
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">Amount<span class="text-danger">*</span></label>
								<input class="form-control" type="text" name="certificate_amount" id="certificate_amount" placeholder="Amount">
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
								<a href="javascript:void(0);" class="btn btn-sm w-100 btn-primary save-challenge">Submit <i class="la la-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
				</form>		
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
					<h3>Certificate updated successfully!!!</h3>
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
					<h3>Certificate created successfully!!!</h3>
				</div>
			</div>
		</div>
	</div>
</div>
