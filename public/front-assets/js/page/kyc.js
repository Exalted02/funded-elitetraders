/*
Author       : Dreamstechnologies
Template Name: SmartHR - Bootstrap Admin Template
Version      : 4.0
*/

$(document).ready(function() {
	/*let table = $('#kycTable').DataTable({
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
	var table = $('#kycTable').DataTable({
		pageLength: 50, // Set default records per page to 50
        processing: true,
        serverSide: true,
        ajax: {
            url: kycDataUrl,
            data: function(d) {
                //d.search_status = $('select[name=search_status]').val(); // add filter value to AJAX
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
            { data: 'client_name', name: 'get_client.first_name' },
            { data: 'email', name: 'get_client.email' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false }
        ],
		columnDefs: [
			{
				targets: -1, // last column (actions)
				className: 'text-end'
			}
		]
    });
	// Handle "select all"
	$('#checkKycAll').on('change', function () {
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
	$('#kycTable tbody').on('change', 'input.row-checkbox', function () {
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
		$('#checkKycAll').prop('checked', false);
		$('input.row-checkbox').prop('checked', false);
		updateSelectionMessage();
	});
	
	$(document).on('click','.edit-customer', function(){
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
	$(document).on('click','.change_multi_approve', function(){
		var status_typ = $(this).data('mode');
		if(selectedIds.length <=0)  {
			$('#confirmChkSelect').modal("show");	
		}else {
			if(status_typ == 'accept'){
				$('#status_type').text('accept');
			}else{
				$('#status_type').text('reject');
			}
			$('#kyc_status_btn').attr('data-mode', status_typ);
			$('#confirm_kyc_status_change').modal('show');
		}
	});
	$(document).on('click','#kyc_status_btn', function(){
		var button = $(this);
		button.prop('disabled', true);
		if(selectedIds.length <=0)  {
			$('#confirmChkSelect').modal("show");	
		}else {	
			var selected_values = selectedIds.join(",");
			var URL = $(this).data('url');
			var status_typ = $(this).data('mode');
			$.ajax({
				url: URL,
				type: "POST",
				data: {id:selected_values, status_typ:status_typ, selectAllMatching:selectAllMatching, _token: csrfToken},
				dataType: 'json',
				success: function(response) {
					$('#success_status_msg').modal('show');
					setTimeout(() => {
						window.location.reload();
					}, "2000");
				},
			});
		}
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
		
	});
	
	$(document).on('click','.kyc-documents-data', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		$.ajax({
			url: URL,
			type: "POST",
			data: { id: id, _token: csrfToken },
			dataType: 'json',
			success: function(response) {
					//console.log(response.documents_details);
					let doc = response.documents_details; 
					
					$('#email').html(doc.get_client.email);
					$('#trader_id').html(doc.id);
					$('#full_name').html(doc.get_client.first_name +' '+ doc.get_client.last_name);
					var created_date = doc.created_at;
					var formatted_date = dayjs(doc.created_at).format("DD MMM YY");
					$('#created_date').html(formatted_date);
					if(doc.status==1)
					{
						$('#accept').hide();
						$('#reject').hide();
						$('#pending').show();
					}
					
					if(doc.status==0)
					{
						$('#accept').hide();
						$('#reject').show();
						$('#pending').hide();
					}
					if(doc.status==2)
					{
						$('#accept').show();
						$('#reject').hide();
						$('#pending').hide();
					}
					
					var frontalFile = doc.frontal ?? null;
					if(frontalFile)
					{
						var frontalFilePath = response.forntal_path +'/'+ frontalFile;
						$('#view_frontal').attr("href", frontalFilePath).attr("download", frontalFile.split('/').pop());
					}
					
					var backFile = doc.back ?? null;
					if(backFile)
					{
						var backFilePath = response.back_path +'/'+ backFile;
						$('#view_back').attr("href", backFilePath).attr("download", backFile.split('/').pop());
					}
					
					var residenceFile = doc.residence ?? null;
					if(residenceFile)
					{
						var residenceFilePath = response.residence_path +'/'+ residenceFile;
						$('#view_residence').attr("href", residenceFilePath).attr("download", residenceFile.split('/').pop());
					}
					
					$('#reject_client_id').attr('data-id', doc.id);
					$('#accept_client_id').attr('data-id', doc.id);
					$('#view_details').modal('show');
			},
			error: function(xhr) {
				console.log(xhr.responseText); // Log errors
				alert('Something went wrong!');
			}
		});
	});
	
	$(document).on('click','#reject_client_id', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		var status_typ = $(this).data('mode');
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id,status_typ:status_typ, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				if(response.message)
				{
					$("#message-section").html('<div class="alert alert-danger">' + response.message + '</div>');
				}
				setTimeout(() => {
					window.location.reload();
				}, "2000");
			},
		});
	});
	$(document).on('click','#accept_client_id', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		var status_typ = $(this).data('mode');
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id,status_typ:status_typ, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				if(response.message)
				{
					 $("#message-section").html('<div class="alert alert-success">' + response.message + '</div>');
				}
				setTimeout(() => {
					window.location.reload();
				}, "2000");
			},
		});
	});
});
