/*
Author       : Dreamstechnologies
Template Name: SmartHR - Bootstrap Admin Template
Version      : 4.0
*/

$(document).ready(function() {
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
	let selectedIds = [];
	let selectAllMatching = false;
	let lastPageCount = 0; // number of rows on current page
	let totalFilteredCount = 0; // total matching records
	let table = $('#payoutTable').DataTable({
		processing: true,
		serverSide: true,
		pageLength: 50,
		ajax: {
			url: payoutDataUrl,
			data: function(d) {
				d._token = '{{ csrf_token() }}';
			},
			dataSrc: function(json) {
				$('#pending_payout').text(json.summary.pending_payout);
				$('#accepted_payout').text(json.summary.accepted_payout);
				$('#rejected_payout').text(json.summary.rejected_payout);
				return json.data;
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
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'email', name: 'get_user_details.email' },
            { data: 'crypto_options', name: 'crypto_options' },
            { data: 'crypto_address', name: 'usdc_address' },
            { data: 'crypto_platform', name: 'crypto_platform' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'experienced', name: 'experienced' },
            { data: 'created_at', name: 'created_at' },
            { data: 'requested_amount', name: 'requested_amount' },
            { data: 'status', orderable: false, searchable: false },
        ],
	});
	
	// Handle "select all"
	$('#checkAll').on('change', function () {
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
	$('#payoutTable tbody').on('change', 'input.row-checkbox', function () {
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
		// console.log('clearSelection clicked');
		selectAllMatching = false;
		selectedIds = [];
		$('#checkAll').prop('checked', false);
		$('input.row-checkbox').prop('checked', false);
		updateSelectionMessage();
	});
	
	
	$(document).on('click','.payout-status', function(){
		var id= $(this).data('id');
		var URL = $(this).data('url');
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				$('#status_user').val(id);
				$('#usdc_address_val').text(response.result.usdc_address);
				$('#payout_single_status_model').modal('show');
			},
		});		
	});
	$(document).on('click','.multi-payout-status', function(){
		if(selectedIds.length <=0)  {
			$('#confirmChkSelect').modal("show");	
		}else {
			$('#payout_multi_status_model').modal('show');
		}
	});
	
	$(document).on('click','.update-status', function(){
		var TYPE_VAL = $(this).data('mode');
		let formData = new FormData($('#frmSingleStatusPayout')[0]);
		formData.append('_token', csrfToken);
		formData.append('type_val', TYPE_VAL);
		var URL = $('#frmSingleStatusPayout').attr('action');
		
		$.ajax({
			url: URL,
			type: "POST",
			data: formData,
			processData: false,  // Required for FormData
			contentType: false,
			dataType: 'json',
			success: function(response) {
				//alert(response);
				setTimeout(() => {
					window.location.reload();
				}, "1000");
			},
		});
	});
	$(document).on('click','.multi-update-status', function(){
		if(selectedIds.length <=0)  {
			$('#confirmChkSelect').modal("show");	
		}else {
			var selected_values = selectedIds.join(",");
			
			var TYPE_VAL = $(this).data('mode');
			let formData = new FormData($('#frmMultiStatusPayout')[0]);
			formData.append('_token', csrfToken);
			formData.append('type_val', TYPE_VAL);
			formData.append('users_id', selected_values);
			formData.append('selectAllMatching', selectAllMatching);
			var URL = $('#frmMultiStatusPayout').attr('action');
			
			$.ajax({
				url: URL,
				type: "POST",
				data: formData,
				processData: false,  // Required for FormData
				contentType: false,
				dataType: 'json',
				success: function(response) {
					//alert(response);
					setTimeout(() => {
						window.location.reload();
					}, "1000");
				},
			});
		}
	});
});
