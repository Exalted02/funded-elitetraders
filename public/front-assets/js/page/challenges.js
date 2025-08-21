/*
Author       : Dreamstechnologies
Template Name: SmartHR - Bootstrap Admin Template
Version      : 4.0
*/

$(document).ready(function() {
	/*let table = $('#challengeTable').DataTable({
        pageLength: 50, // Set default records per page to 50
		ordering: false, 
		language: {
			"lengthMenu": "Show _MENU_ entries",
			"zeroRecords": "No records found",
			"info": "Showing _START_ to _END_ of _TOTAL_ entries",
			"infoEmpty": "No entries available",
			"infoFiltered": "Filtered from _MAX_ total entries",
			"search": "Search",
			"paginate": {
				"first": "First",
				"last": "Last",
				"next": "Next",
				"previous": "Previous"
			},
		},
		columnDefs: [
			{ orderable: false, targets: 0 }, 
			{ orderable: false, targets: '_all' } 
		],
    });*/
	let selectedIds = [];
	let selectAllMatching = false;
	let lastPageCount = 0; // number of rows on current page
	let totalFilteredCount = 0; // total matching records
	var table = $('#challengeTable').DataTable({
		pageLength: 50, // Set default records per page to 50
        processing: true,
        serverSide: true,
        ajax: {
            url: challengeDataUrl,
            data: function(d) {
                d.search_status = $('select[name=search_status]').val(); // add filter value to AJAX
            }
        },
		drawCallback: function (settings) {
			lastPageCount = settings.json.data.length;
			totalFilteredCount = settings.json.recordsFiltered;
			updateSelectionMessage();

			// Restore checkboxes
			table.rows().every(function () {
				let row = $(this.node());
				let checkbox = row.find('input.row-checkbox');
				let id = checkbox.val();
				checkbox.prop('checked', selectedIds.includes(id));
			});

			// Check if all visible rows are selected
			let allChecked = table.rows({ search: 'applied' }).every(function () {
				let id = $(this.node()).find('input.row-checkbox').val();
				return selectedIds.includes(id);
			});
		},
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'trader_email', name: 'email' },
            { data: 'trader_name', name: 'first_name' },
            { data: 'challenge', name: 'get_challenge_type.title' },
            { data: 'balance', name: 'balance', orderable: false, searchable: false },
            { data: 'start_date', name: 'created_at' },
            { data: 'state', name: 'status', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ],
		columnDefs: [
			{
				targets: -1, // last column (actions)
				className: 'text-end'
			}
		]
    });

    // Reload table on filter submit
    $('.search-data').click(function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
	
	$('#checkChallengeAll').on('change', function () {
		let isChecked = $(this).is(':checked');

		table.rows({ search: 'applied' }).every(function () {
			let row = $(this.node());
			let checkbox = row.find('input[type="checkbox"]');
			let id = checkbox.val();

			checkbox.prop('checked', isChecked);
			if (isChecked && !selectedIds.includes(id)) {
				selectedIds.push(id);
			} else if (!isChecked) {
				selectedIds = selectedIds.filter(val => val !== id);
			}
		});

		selectAllMatching = false; // not yet global select
		updateSelectionMessage();
	});

	
	// Handle individual checkbox click
    $('#challengeTable tbody').on('change', 'input.row-checkbox', function () {
		let id = $(this).val();

		if ($(this).is(':checked')) {
			if (!selectedIds.includes(id)) selectedIds.push(id);
		} else {
			selectedIds = selectedIds.filter(val => val !== id);
			selectAllMatching = false; // if user unchecks anything
		}

		updateSelectionMessage();
	});
	function updateSelectionMessage() {
		if (selectedIds.length === 0 && !selectAllMatching) {
			$('#selectionMessage').hide();
			$('#selectionMessage').attr('style', 'display: none !important');
			return;
		}

		$('#selectionMessage').show();
		$('#selectionText').text(`All ${selectedIds.length} rows on this page are selected.`);
		$('#totalCount').text(totalFilteredCount);
		
		if (!selectAllMatching && selectedIds.length === lastPageCount && totalFilteredCount > lastPageCount) {
			$('#selectAllMatchingLink').show();
		} else {
			$('#selectAllMatchingLink').hide();
		}

		if (selectAllMatching) {
			$('#selectionText').text(`All ${totalFilteredCount} matching rows are selected.`);
		}
	}
	$('#selectAllMatchingLink').on('click', function () {
		selectAllMatching = true;
		$('#selectAllMatchingLink').hide();
		updateSelectionMessage();
	});

	$('#clearSelection').on('click', function () {
		console.log('clearSelection clicked');
		selectAllMatching = false;
		selectedIds = [];
		$('#checkChallengeAll').prop('checked', false);
		$('input.row-checkbox').prop('checked', false);
		updateSelectionMessage();
	});
	
	// On each page draw, restore checkbox states
    /*$('#challengeTable').on('draw.dt', function () {
        table.rows().every(function () {
            let row = $(this.node());
            let checkbox = row.find('input.row-checkbox');
            let id = checkbox.val();

            checkbox.prop('checked', selectedIds.includes(id));
        });

        // Also update checkAll if all are selected on current page
        let allChecked = table.rows({ search: 'applied' }).every(function () {
            let id = $(this.node()).find('input.row-checkbox').val();
            return selectedIds.includes(id);
        });

        $('#checkChallengeAll').prop('checked', allChecked);
    });*/
	
	$(document).on('click','.save-challenge-email', function(){
		let traderEmail = $('#trader_email').val().trim();
		//let createdDate = $('#created_date').val().trim();
		let isValid = true;
		$('.invalid-feedback').hide();
		$('.form-control').removeClass('is-invalid');
		if (traderEmail === '')
		{
			$('#trader_email').addClass('is-invalid');
			$('#trader_email').next('.invalid-feedback').show();
			isValid = false;
		}
		if (isValid) {		
			var URL = $('#frmTraderEmail').attr('action');
			//alert(URL);
			$.ajax({
				url: URL,
				type: "POST",
				data: {trader_email:traderEmail, _token: csrfToken},
				//dataType: 'json',
				success: function(response) {
					// console.log(response);
					if(response.success) {
						$('#step-one').hide();
						$('#step-two').show();
						$('#traders_email').val(traderEmail);
						$('#trader_first_name').val(response.data.first_name);
						$('#trader_last_name').val(response.data.last_name);
						$('#trader_phone_number').val(response.data.phone_number);
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
				}
			});
		}
	});	
	$(document).on('click','.save-challenge', function(){
		var button = $(this);
		button.prop('disabled', true);
		let formData = new FormData($('#frmChallenge')[0]);
		formData.append('_token', csrfToken);
		var URL = $('#frmChallenge').attr('action');
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
					$('#success_msg').modal('show');
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
	
	$(document).on('change', '#trader_challenge', function() {
		var id = $(this).val();
		var URL = $('#trader_challenge_amount_url').val();
		var currentInput = $(this);
		// alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				$('#trading_amount').val(response.amount);
			},
		});
		
	});
	$(document).on('click','.challenge-details', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		$.ajax({
			url: URL,
			type: "POST",
			data: { id: id, _token: csrfToken },
			dataType: 'json',
			success: function(response) {
				$('.challenge-info').html(response.html);
				$('#view_details').modal('show');
			},
			error: function(xhr) {
				console.log(xhr.responseText); // Log errors
				alert('Something went wrong!');
			}
		});
	});
	
	$(document).on('click','.update-status', function(){
		var TYPE_VAL = $(this).data('type');
		var URL = $(this).data('url');
		var id = $(this).data('id');
		
		$.ajax({
			url: URL,
			type: "POST",
			data: { id: id, type_val: TYPE_VAL, _token: csrfToken },
			dataType: 'json',
			success: function(response) {
				//alert(response);
				setTimeout(() => {
					window.location.reload();
				}, "1000");
			},
		});
	});
	
	
	$(document).on('click','.adjust-balance', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		//alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				// console.log(response);
				$('#current_balance').text(response.result.get_challenge_type.amount);
				$('#new_amount').text(response.result.get_challenge_type.amount + response.adjust_users_balance);
				$('#current_balance_val').val(response.result.get_challenge_type.amount);
				$('#adjust_amount_challenge').val(id);
				$('#adjust_amount_user').val(response.result.user_id);
				$('#adjust_balance').text(response.adjust_users_balance);
				$('#adjust_balance_model').modal('show');
				currentBalance = response.result.get_challenge_type.amount + response.adjust_users_balance;
				challengeBalance = response.result.get_challenge_type.amount;
			},
		});
	});
	/*$('#adjust_amount').on('input', function() {
      let val = $(this).val();

      // Remove invalid characters (allow digits and one decimal)
      val = val.replace(/[^0-9.]/g, '');

      // Only allow one decimal point
      let parts = val.split('.');
      if (parts.length > 2) {
        val = parts[0] + '.' + parts[1]; // Ignore extra decimals
      }

      $(this).val(val);

      // Update new amount
      let adjustAmount = parseFloat(val);
      if (!isNaN(adjustAmount)) {
        let newAmount = currentBalance + adjustAmount;
        $('#new_amount').text(newAmount.toFixed(2));
      } else {
        $('#new_amount').text(currentBalance.toFixed(2));
      }
    });*/
	$('#adjust_amount').on('input', function() {
	  let val = $(this).val();

	  // Allow only digits, one optional negative sign at the beginning, and one optional decimal point
	  val = val.replace(/[^0-9.-]/g, '');

	  // Only one minus at the beginning
	  if (val.indexOf('-') > 0) {
		val = val.replace(/-/g, ''); // Remove all minus signs if not at start
	  } else if ((val.match(/-/g) || []).length > 1) {
		val = '-' + val.replace(/-/g, ''); // Keep only first minus
	  }

	  // Only one decimal point
	  let parts = val.split('.');
	  if (parts.length > 2) {
		val = parts[0] + '.' + parts[1]; // Ignore extra decimals
	  }

	  $(this).val(val);

	  // Update new amount
	  let adjustAmount = parseFloat(val);
	  if (!isNaN(adjustAmount)) {
		let newAmount = currentBalance + (challengeBalance * (adjustAmount / 100));
		$('#new_amount').text(newAmount.toFixed(2));
	  } else {
		$('#new_amount').text(currentBalance.toFixed(2));
	  }
	});

	$(document).on('click','.submit-adjust-balance', function(){
		var button = $(this);
		button.prop('disabled', true);
		var type = $(this).data('mode');
		
		let formData = new FormData($('#frmAdjustBalance')[0]);
		// formData.append('user_id', $('adjust_amount_user').val());
		formData.append('type', type);
		formData.append('_token', csrfToken);
		var URL = $('#frmAdjustBalance').attr('action');
		//alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: formData,
			processData: false,  // Required for FormData
			contentType: false,
			//dataType: 'json',
			success: function(response) {
				if(type == 'add'){
					$('.adjust_balance_msg').text('User balance added successfully.');
				}else{
					$('.adjust_balance_msg').text('User balance removed successfully.');
				}
				$('#adjust_balance_msg').modal('show');
				setTimeout(() => {
					window.location.reload();
				}, "1000");
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
	$(document).on('click','.multi-adjust-balance', function(){
		/*var employee = [];
		$(".table input[name=chk_id]:checked").each(function() {  
			employee.push($(this).data('emp-id'));
		});
		console.log('Selected IDs:', selectedIds);
		console.log('Selected IDs employee:', employee);*/
		if(selectedIds.length <=0)  {
			$('#confirmChkSelect').modal("show");	
		}else {
			$('#adjust_multi_user_balance_model').modal('show');
		}
	});
	$('#adjust_percent').on('input', function() {
		let val = $(this).val();

		// Allow only digits, one optional negative sign at the beginning, and one optional decimal point
		val = val.replace(/[^0-9.-]/g, '');

		// Only one minus at the beginning
		if (val.indexOf('-') > 0) {
			val = val.replace(/-/g, ''); // Remove all minus signs if not at start
		} else if ((val.match(/-/g) || []).length > 1) {
			val = '-' + val.replace(/-/g, ''); // Keep only first minus
		}

		// Only one decimal point
		let parts = val.split('.');
		if (parts.length > 2) {
			val = parts[0] + '.' + parts[1]; // Ignore extra decimals
		}

		$(this).val(val);
      /*let val = $(this).val();

      // Remove invalid characters (allow digits and one decimal)
      val = val.replace(/[^0-9.]/g, '');

      // Only allow one decimal point
      let parts = val.split('.');
      if (parts.length > 2) {
        val = parts[0] + '.' + parts[1]; // Ignore extra decimals
      }

      $(this).val(val);*/
    });
	$(document).on('click','.submit-multi-adjust-balance', function(){
		var button = $(this);
		button.prop('disabled', true);
		/*var employee = [];
		$(".table input[name=chk_id]:checked").each(function() {  
			employee.push($(this).data('emp-id'));
		});*/
		if(selectedIds.length <=0) {
			$('#confirmChkSelect').modal("show");	
		}else{
			// var selected_values = employee.join(",");
			var selected_values = selectedIds.join(",");
			
			var TYPE_VAL = $(this).data('mode');
			let formData = new FormData($('#frmMultiAdjustBalance')[0]);
			formData.append('_token', csrfToken);
			formData.append('type_val', TYPE_VAL);
			formData.append('challenge_id', selected_values);
			formData.append('selectAllMatching', selectAllMatching);
			// Add individual filter parameters
			formData.append('filters[search_status]', $('select[name=search_status]').val());
			var URL = $('#frmMultiAdjustBalance').attr('action');
			
			$.ajax({
				url: URL,
				type: "POST",
				data: formData,
				processData: false,  // Required for FormData
				contentType: false,
				dataType: 'json',
				success: function(response) {
					if(TYPE_VAL == 'add'){
						$('.adjust_balance_msg').text('User balance added successfully.');
					}else{
						$('.adjust_balance_msg').text('User balance removed successfully.');
					}
					$('#adjust_balance_msg').modal('show');
					setTimeout(() => {
						window.location.reload();
					}, "1000");
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
	/*$(document).on('click','.edit-customer', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		//alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				
			},
		});
	}); 

	$(document).on('click','.delete-customer', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		//alert(id);alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				$('#delete_model').modal('show');
			},
		});
		
	});*/
});
