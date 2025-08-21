/*
Author       : Dreamstechnologies
Template Name: SmartHR - Bootstrap Admin Template
Version      : 4.0
*/

$(document).ready(function() {
	let selectedIds = [];
	let selectAllMatching = false;
	let lastPageCount = 0; // number of rows on current page
	let totalFilteredCount = 0; // total matching records
	var table = $('#certificateTable').DataTable({
		pageLength: 50, // Set default records per page to 50
        processing: true,
        serverSide: true,
        ajax: {
            url: certificateDataUrl,
            data: function(d) {
                //d.search_status = $('select[name=search_status]').val(); // add filter value to AJAX
            }
        },
		drawCallback: function (settings) {
			lastPageCount = settings.json.data.length;
			
			// Restore checkboxes
			table.rows().every(function () {
				let row = $(this.node());
			});
		},
        columns: [
            { data: 'date', name: 'certificate_date' },
            { data: 'name', name: 'certificate_name' },
            { data: 'amount', name: 'certificate_amount' },
            { data: 'state', name: 'state', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ],
		columnDefs: [
			{
				targets: -1, // last column (actions)
				className: 'text-end'
			}
		]
    });
	
	$(document).on('click','.add-btn', function(){
		$('#modal-title').text('Create Certificate');
		$('#hid_id').val('');
		$('#certificate_date').val('');
		$('#certificate_name').val('');
		$('#certificate_amount').val('');
		$('#add_certificate').modal('show');
	});
	$(document).on('click','.edit-data', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				// console.log(response.result);
				$('#modal-title').text('Edit Certificate');
				$('#hid_id').val(response.result.id);
				$('#certificate_date').val(response.result_date);
				$('#certificate_name').val(response.result.certificate_name);
				$('#certificate_amount').val(response.result.certificate_amount);
				$('#add_certificate').modal('show');
			},
		});
	});
	
	$(document).on('click','.save-challenge', function(){
		var button = $(this);
		button.prop('disabled', true);
		let formData = new FormData($('#frmCertificate')[0]);
		formData.append('_token', csrfToken);
		var URL = $('#frmCertificate').attr('action');
		//alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: formData,
			processData: false,  // Required for FormData
			contentType: false,
			//dataType: 'json',
			success: function(response) {
				if (response.success) {
					if(response.type == 1){
						$('#success_msg').modal('show');
					}else{
						$('#updt_success_msg').modal('show');
					}
					setTimeout(() => {
						window.location.reload();
					}, "2000");
				}
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
	});
	$(document).on('click','.delete-data', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		//alert(id);alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {				
				$('.final-delete-submit').attr('data-id', response.result.id);
				$('#delete_modal_name_data').text(response.result.certificate_name);
				$('#delete_model').modal('show');
			},
		});
		
	});
	$(document).on('click','.final-delete-submit', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				if(response.result == 'success'){
					$('#delete-icon').html('<font color="green">Record Deleted Successfully</font>');
				}else{
					$('#delete-icon').html('<font color="red">Record not deleted</font>');
				}
				setTimeout(() => {
					window.location.reload();
				}, "2000");
			},
		});
		
	});
});
