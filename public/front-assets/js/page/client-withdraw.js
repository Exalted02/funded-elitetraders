/*
Author       : Dreamstechnologies
Template Name: SmartHR - Bootstrap Admin Template
Version      : 4.0
*/

$(document).ready(function() {
	$('#usdc_address').on('input', function() {
        const val = $(this).val().trim();
        if (val !== '') {
            $('#payout_question').slideDown();
        } else {
            $('#payout_question').slideUp();
        }
    });
	$(document).on('click','.submit-withdraw', function(){
		var URL = $(this).data('url');
		$.ajax({
			url: URL,
			type: "POST",
			data: {_token: csrfToken},
			dataType: 'json',
			success: function(response) {
				$('#withdrawable_balance_text').text(response.get_records_amount);
				$('#withdrawable_balance_input').val(response.get_records_amount);
				$('#withdrawable_id').val(response.get_records);
				if(response.get_records_amount <= 0){
					$('#withdraw-submit-section').hide();
					$('#balance_form').hide();
					$('.no_balance_form').show();
					$('.submit-withdraw-request').prop('disabled', true);
				}else{
					$('#withdraw-submit-section').show();
					$('#balance_form').show();
					$('.no_balance_form').hide();
					$('.submit-withdraw-request').prop('disabled', false);
				}
				$('#submit_withdraw_request').modal('show');
			},
		});	
	});
	$(document).on('click','.submit-withdraw-request', function(){
		var button = $(this);
		button.prop('disabled', true);
		
		let withdrawable_balance_input = $('#withdrawable_balance_input').val();
		if(withdrawable_balance_input > 0){
			let formData = new FormData($('#frmWithdrawSubmit')[0]);
			formData.append('_token', csrfToken);
			var URL = $('#frmWithdrawSubmit').attr('action');
			//alert(URL);
			$.ajax({
				url: URL,
				type: "POST",
				data: formData,
				processData: false,  // Required for FormData
				contentType: false,
				dataType: 'json',
				success: function(response) {
					if(response.result == 'success'){
						$('#request_withdraw_msg_modal').modal('show');
						$('#request_withdraw_msg').html(response.message);
					}else{
						$('#request_withdraw_msg_modal').modal('show');
						$('#request_withdraw_msg').html('Request not sent.');
					}
					/*setTimeout(() => {
						window.location.reload();
					}, "1000");*/
				},
				error: function (xhr) {
					if (xhr.status === 422) {
						// alert(xhr.status);
						const errors = xhr.responseJSON.errors;
						$('.invalid-feedback').hide();
						$('.form-control').removeClass('is-invalid');
						
						$.each(errors, function(key, value) {
							// Check the key received from the server
							let fieldName = key.replace(/\./g, '\\.').replace(/\*/g, '');
							let field = $('[name="' + fieldName + '"]');
							
							if (field.length > 0) {
								field.addClass('is-invalid');
								if (field.is('select')) {
									//field.closest('.form-group').find('.invalid-feedback').show().text(value[0]);
									
									field.closest('.input-block').find('.invalid-feedback').show().text(value[0]);
									//alert(value[0]);
								} else {
									field.next('.invalid-feedback').show().text(value[0]);
								}
							} else {
								var fieldNames = key.split('.')[0]; // Get the base field name (e.g., product_sale_price)
								var index = key.split('.').pop();
								var inputField = $('input[name="' + fieldNames + '[]"]').eq(index);
								inputField.addClass('is-invalid');
								inputField.next('.invalid-feedback').show().text(value[0]);
							}
						});
					}else{
						
					}
					button.prop('disabled', false);
				}
			});
		}
	});
});
