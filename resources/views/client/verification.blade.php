@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="{{ url('front-assets/plugins/c3-chart/c3.min.css') }}">
@endsection
@section('content')
@php 
$data = App\Models\Kyc_documents::where('client_id', auth()->user()->id)->first();
$status = '';
$fileExtFrontal = '';
$fileExtBack = '';
$fileExtResidence = '';

$frontal_img = null;
$back_img = null;
$residence_img = null;
if(!empty($data->frontal))
{
	$frontal_img = $data->frontal;
	$expld = explode(".",$frontal_img);
	$fileExtFrontal = $expld[1];
}

if(!empty($data->back))
{
	$back_img = $data->back;
	$expld = explode(".",$back_img);
	$fileExtBack = $expld[1];
}

if(!empty($data->residence))
{
	$residence_img = $data->residence;
	$expld = explode(".",$residence_img);
	$fileExtResidence = $expld[1];
}

if(isset($data) && ($data->status == 0 || $data->status == 1 || $data->status == 2))
{
	$status = $data->status;
}
//echo $fileextension ; die;
@endphp
    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid pb-0">
            <div class="row">
                <div class="col-lg-12">
					<div class="card employee-month-card flex-fill">
						<div class="card-body">
							<div class="statistic-header">
								<h4>Verification Of Identity</h4>
							</div>
							<hr class="mt-0">
							<small>Your can submit your documents here</small>
							@if(session('success'))
								<div class="alert alert-success">
									{{ session('success') }}
								</div>
							@endif

							<form name="frm" action="{{ route('client.verification') }}" enctype="multipart/form-data" method="post">
							@csrf
							<div class="row mt-3 identity-verification">
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
									<div class="card dash-widget mb-0">
										<div class="card-body">
											<small>Frontal View (jpg, png or pdf)</small>
											
											<label class="dropzone hid-frontal-div" style="display: {{ $frontal_img == null ? 'block' : 'none' }};">
												<div class="upload-content">
													<i class="la la-file-upload frontal-la"></i>
													<span id="frontal-upl">UPLOAD HERE</span>
													
												</div>
												<input type="file" hidden name="frontal_view_file" id="frontal_view_file" accept="image/*,application/pdf">
											</label>
											
											<div id="frontalShowContainer" style="margin-top: 10px; display: {{ $frontal_img != null ? 'block' : 'none' }};">
											
											@if($fileExtFrontal == 'pdf')
												 <img id="frontalShowFile" src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg" style="width: 130px; height: 100px;">
											@else
												<img id="frontalShowFile" src="{{ url('uploads/kyc/' .auth()->user()->id .'/frontal/'.$frontal_img)}}">
											@endif
												@if($status==0)
												<a href="#" id="removeFrontalShowFile">×</a>
												@endif
											</div>
											
											<div id="frontalPreviewContainer" style="margin-top: 10px;">
											<img id="frontalPreview" src="">
											
											<a href="#" id="removeFrontalPreview">×</a>
											
											</div>
										</div>
										
									</div>
									@if ($errors->has('frontal_view_file'))
										<span class="text-danger">{{ $errors->first('frontal_view_file') }}</span>
									@endif
									<input type="hidden" id="hid_frontal_file_name" value="{{ $data->frontal ?? '' }}">
								</div>
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
									<div class="card dash-widget mb-0">
										<div class="card-body">
											<small>Back View (jpg, png or pdf)</small>
											<label class="dropzone hid-back-div" style="display: {{ $back_img == null ? 'block' : 'none' }};">
												<div class="upload-content">
													<i class="la la-file-upload"></i>
													<span>UPLOAD HERE</span>
												</div>
												<input type="file" hidden name="back_view_file" id="back_view_file" accept="image/*,application/pdf">
											</label>
											
											<div id="backShowContainer" style="margin-top: 10px; display: {{ $back_img != null ? 'block' : 'none' }};">
											
											@if($fileExtBack == 'pdf')
												 <img id="backShowFile" src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg" style="width: 130px; height: 100px;">
											@else
												<img id="backShowFile" src="{{ url('uploads/kyc/' .auth()->user()->id .'/back/'.$back_img)}}">
											@endif
											
											@if($status==0)
											<a href="#" id="removeBackShowFile">×</a>
										    @endif
											</div>
											
											<div id="backPreviewContainer" style="margin-top: 10px; display: none;">
												<img id="backPreview" src="" alt="Preview">
												<a href="#" id="removeBackPreview">×</a>
											</div>
										</div>
									</div>
									@if ($errors->has('back_view_file'))
										<span class="text-danger">{{ $errors->first('back_view_file') }}</span>
									@endif
									<input type="hidden" id="hid_back_file_name" value="{{ $data->back ?? '' }}">
								</div>
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
									<div class="card dash-widget mb-0">
										<div class="card-body">
											<small>Proof Of Residence (jpg, png or pdf)</small>
											<label class="dropzone hid-residence-div" style="display: {{ $residence_img == null ? 'block' : 'none' }};">
												<div class="upload-content">
													<i class="la la-file-upload"></i>
													<span>UPLOAD HERE</span>
												</div>
												<input type="file" hidden  name="residence_file" id="residence_file" accept="image/*,application/pdf">
											</label>
											
											<div id="residenceShowContainer" style="margin-top: 10px; display: {{ $residence_img != null ? 'block' : 'none' }};">
											@if($fileExtResidence == 'pdf')
												 <img id="residenceShowFile" src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg" style="width: 130px; height: 100px;">
											@else
												<img id="residenceShowFile" src="{{ url('uploads/kyc/' .auth()->user()->id .'/residence/'.$residence_img)}}">
											@endif
												@if($status==0)
												<a href="#" id="removeResidenceShowFile">×</a>
												@endif
											</div>
											
											<div id="residencePreviewContainer" style="margin-top: 10px; display: none;">
												<img id="residencePreview" src="" alt="Preview">
												<a href="#" id="removeResidencePreview">×</a>
											</div>
										</div>
									</div>
									@if ($errors->has('residence_file'))
										<span class="text-danger">{{ $errors->first('residence_file') }}</span>
									@endif
									<input type="hidden" id="hid_residence_file_name" value="{{ $data->residence ?? '' }}">
								</div>
							</div>
							
							<button class="btn btn-primary mt-3" style="display: {{ $status == '' ? 'block' : 'none' }};" id="kyc-submit">
								<i class="la la-file-alt"></i> Submit KYC
							</button>
							
							
							@if($status==1)
							<button class="btn btn-warning mt-3">
								<i class="las la-clock"></i> Pending KYC
							</button>
							@endif
							
							@if($status==0)
							<button class="btn btn-danger mt-3 ms-3" id="reject-status">
								<i class="las la-times-circle"></i> Rejected KYC (remove files and again uploads)
							</button>
							@endif
							
							@if($status==2)
								<button class="btn btn-success mt-3">
								<i class="las la-check-double"></i> Approved KYC
							</button>
							@endif
							
							</form>
						</div>
					</div>
				</div>
			</div>
        </div>
        <!-- /Page Content -->

    </div>
	<input type="hidden" id="no_of_delete" value="0">
	<input type="hidden" id="delete_kyc_doc" value="{{ route('client.delete-kyc') }}">
    <!-- /Page Wrapper -->

@endsection 
@section('scripts')
<!-- Chart JS -->
<script src="{{ url('front-assets/plugins/c3-chart/d3.v5.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/c3.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/chart-data.js') }}"></script>
<script>

document.addEventListener("DOMContentLoaded", function() {
    let fileInput = document.getElementById("frontal_view_file");
    let previewContainer = document.getElementById("frontalPreviewContainer");
    let previewImage = document.getElementById("frontalPreview");
    let removePreviewBtn = document.getElementById("removeFrontalPreview");
	let uploadLabel = document.querySelector(".hid-frontal-div"); // Upload label
	
    if (fileInput) {
        fileInput.addEventListener("change", function(event) {
            let file = event.target.files[0];

            if (file) {
				let fileType = file.type;
                let fileExtension = file.name.split('.').pop().toLowerCase();
				
                let reader = new FileReader();
                reader.onload = function(e) {
					
					if (fileType.includes("image")) {
                        // If the file is an image
                        previewImage.src = e.target.result;
						previewImage.style.width = "100%";
						previewImage.style.height = "60px";
                        previewImage.style.display = "block";
                    } else if (fileExtension === "pdf") {
                        // If the file is a PDF, show an icon instead
                        previewImage.src = "https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg"; // Default PDF icon
						
						previewImage.style.width = "130px";
						previewImage.style.height = "100px"; 
                        previewImage.style.display = "block";
                    }
					
                    /*if (previewImage) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = "block";
                    }*/
                    if (previewContainer) {
                        previewContainer.style.display = "block";
                    }
                    if (removePreviewBtn) {
                        removePreviewBtn.style.display = "flex"; // Show remove link
                    }
                    //$('.hid-frontal-div').hide(); // Hide upload label
					if (uploadLabel) {
                        uploadLabel.style.display = "none"; // Hide upload label
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Remove uploaded image preview
    if (removePreviewBtn) {
        removePreviewBtn.addEventListener("click", function(event) {
            event.preventDefault();
            if (previewContainer) {
                previewContainer.style.display = "none";
            }
            if (previewImage) {
                previewImage.src = "";
            }
            if (fileInput) {
                fileInput.value = ""; // Reset file input
            }
            //$('.hid-frontal-div').show(); // Show upload label again
			if (uploadLabel) {
                uploadLabel.style.display = "block"; // Show upload label again
            }
        });
    }

    // Remove already uploaded image from database
    let removeFrontalShowFile = document.getElementById("removeFrontalShowFile");
    if (removeFrontalShowFile) {
        removeFrontalShowFile.addEventListener("click", function(event) {
            event.preventDefault();
            //alert("Removing image..."); // Debugging
			var no_of_delete = $('#no_of_delete').val();
			if(no_of_delete < 3)
			{
				var add_no_of_delete = parseInt(no_of_delete) + parseInt(1);
				//alert(add_no_of_delete);
				$('#no_of_delete').val(add_no_of_delete);
				if(add_no_of_delete == 3)
				{
					$('#reject-status').hide();
					$('#kyc-submit').show();
				}
			}
			
            let frontalShowContainer = document.getElementById("frontalShowContainer");
            let frontalShowFile = document.getElementById("frontalShowFile");

            if (frontalShowContainer) {
                frontalShowContainer.style.display = "none";
            }
            if (frontalShowFile) {
                frontalShowFile.src = "";
            }
            if (fileInput) {
                fileInput.value = "";
            }
            //$('.hid-frontal-div').show(); // Show upload label again
			if (uploadLabel) {
                uploadLabel.style.display = "block"; // Show upload label again
            }
			
			// delete file from database 
			var hid_frontal_file_name = $('#hid_frontal_file_name').val();
			var file_name = hid_frontal_file_name;
			if(file_name != '')
			{
				var URL = $('#delete_kyc_doc').val();
				$.ajax({
					url: URL,
					type: "POST",
					data: {'field':'frontal',file_name:file_name, _token: csrfToken},
					dataType: 'json',
					success: function(response) {
						if(add_no_of_delete == 3)
						{
							$('#reject-status').hide();
							$('#kyc-submit').show();
						}
					},
				});
			}
        });
    }
});

/*document.getElementById("frontal_view_file").addEventListener("change", function(event) {
    let file = event.target.files[0];
	$('#frontalPreview').show();
	$('.hid-frontal-div').hide();
    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("frontalPreview").src = e.target.result;
            document.getElementById("frontalPreviewContainer").style.display = "block";
            document.getElementById("removeFrontalPreview").style.display = "flex"; // Show remove link
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById("removeFrontalPreview").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent default link behavior
	$('.hid-frontal-div').show();
    document.getElementById("frontalPreviewContainer").style.display = "none"; // Hide preview container
    document.getElementById("removeFrontalPreview").style.display = "none"; // Hide remove link
    document.getElementById("frontalPreview").src = ""; // Clear preview image
    document.getElementById("frontal_view_file").value = ""; // Reset file input
});*/

/*document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
        let removeButton = document.getElementById("removeFrontalShowFile");

        if (removeButton !== null) {
            removeButton.addEventListener("click", function(event) {
                event.preventDefault();
                alert('ok'); // Debugging - Ensure event is firing

                let frontalContainer = document.getElementById("frontalShowContainer");
                let frontalImage = document.getElementById("frontalShowFile");
                let fileInput = document.getElementById("frontal_view_file");

                if (frontalContainer) {
                    frontalContainer.style.display = "none";
                }
                if (frontalImage) {
                    frontalImage.src = "";
                }
                if (fileInput) {
                    fileInput.value = "";
                }
            });
        } else {
            console.log("Element #removeFrontalShowFile not found in DOM.");
        }
    }, 500); // Delay execution to ensure element is available
});*/

/*document.addEventListener("DOMContentLoaded", function() {
    let removeButton = document.getElementById("removeFrontalShowFile");

    if (removeButton) {
		
        removeButton.addEventListener("click", function(event) {
            event.preventDefault(); 
			alert('ok');
            document.getElementById("frontalShowContainer").style.display = "none";
            document.getElementById("frontalShowFile").src = ""; 
            let fileInput = document.getElementById("frontal_view_file");
            if (fileInput) {
                fileInput.value = ""; 
            }
        });
    }
});*/



/*document.getElementById("frontal_view_file").addEventListener("change", function(event) {
    let file = event.target.files[0];
	$('#frontalPreview').show();
    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("frontalPreview").src = e.target.result;
            document.getElementById("frontalPreviewContainer").style.display = "block";
        };
        reader.readAsDataURL(file);
    }
});*/


document.addEventListener("DOMContentLoaded", function() {
    let fileInput = document.getElementById("back_view_file");
    let previewContainer = document.getElementById("backPreviewContainer");
    let previewImage = document.getElementById("backPreview");
    let removePreviewBtn = document.getElementById("removeBackPreview");
	let uploadLabel = document.querySelector(".hid-back-div"); // Upload label
	
    if (fileInput) {
        fileInput.addEventListener("change", function(event) {
            let file = event.target.files[0];

            if (file) {
				
				let fileType = file.type;
                let fileExtension = file.name.split('.').pop().toLowerCase();
				
                let reader = new FileReader();
                reader.onload = function(e) {
					
					if (fileType.includes("image")) {
                        // If the file is an image
                        previewImage.src = e.target.result;
						previewImage.style.width = "100%";
						previewImage.style.height = "60px";
                        previewImage.style.display = "block";
                    } else if (fileExtension === "pdf") {
                        // If the file is a PDF, show an icon instead
                        previewImage.src = "https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg"; // Default PDF icon
						
						previewImage.style.width = "130px";
						previewImage.style.height = "100px"; 
                        previewImage.style.display = "block";
                    }
					
                    /*if (previewImage) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = "block";
                    }*/
                    if (previewContainer) {
                        previewContainer.style.display = "block";
                    }
                    if (removePreviewBtn) {
                        removePreviewBtn.style.display = "flex"; // Show remove link
                    }
                    //$('.hid-frontal-div').hide(); // Hide upload label
					if (uploadLabel) {
                        uploadLabel.style.display = "none"; // Hide upload label
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Remove uploaded image preview
    if (removePreviewBtn) {
        removePreviewBtn.addEventListener("click", function(event) {
            event.preventDefault();
            if (previewContainer) {
                previewContainer.style.display = "none";
            }
            if (previewImage) {
                previewImage.src = "";
            }
            if (fileInput) {
                fileInput.value = ""; // Reset file input
            }
            //$('.hid-frontal-div').show(); // Show upload label again
			if (uploadLabel) {
                uploadLabel.style.display = "block"; // Show upload label again
            }
        });
    }

    // Remove already uploaded image from database
    let removeBackShowFile = document.getElementById("removeBackShowFile");
    if (removeBackShowFile) {
        removeBackShowFile.addEventListener("click", function(event) {
            event.preventDefault();
            //alert("Removing image..."); // Debugging
			var no_of_delete = $('#no_of_delete').val();
			if(no_of_delete < 3)
			{
				var add_no_of_delete = parseInt(no_of_delete) + parseInt(1);
				//alert(add_no_of_delete);
				$('#no_of_delete').val(add_no_of_delete);
				if(add_no_of_delete == 3)
				{
					$('#reject-status').hide();
					$('#kyc-submit').show();
				}
			}
			
            let backShowContainer = document.getElementById("backShowContainer");
            let backShowFile = document.getElementById("backShowFile");

            if (backShowContainer) {
                backShowContainer.style.display = "none";
            }
            if (backShowFile) {
                backShowFile.src = "";
            }
            if (fileInput) {
                fileInput.value = "";
            }
            //$('.hid-frontal-div').show(); // Show upload label again
			if (uploadLabel) {
                uploadLabel.style.display = "block"; // Show upload label again
            }
			
			// delete file from database 
			var hid_back_file_name = $('#hid_back_file_name').val();
			var file_name = hid_back_file_name;
			if(file_name != '')
			{
				var URL = $('#delete_kyc_doc').val();
				$.ajax({
					url: URL,
					type: "POST",
					data: {'field':'back',file_name:file_name, _token: csrfToken},
					dataType: 'json',
					success: function(response) {
						if(add_no_of_delete == 3)
						{
							$('#reject-status').hide();
							$('#kyc-submit').show();
						}
					},
				});
			}
			
        });
    }
});



// residence start-----

document.addEventListener("DOMContentLoaded", function() {
    let fileInput = document.getElementById("residence_file");
    let previewContainer = document.getElementById("residencePreviewContainer");
    let previewImage = document.getElementById("residencePreview");
    let removePreviewBtn = document.getElementById("removeResidencePreview");
	let uploadLabel = document.querySelector(".hid-residence-div"); // Upload label
	
    if (fileInput) {
        fileInput.addEventListener("change", function(event) {
            let file = event.target.files[0];

            if (file) {
				let fileType = file.type;
                let fileExtension = file.name.split('.').pop().toLowerCase();
                let reader = new FileReader();
                reader.onload = function(e) {
					
					if (fileType.includes("image")) {
                        // If the file is an image
                        previewImage.src = e.target.result;
						previewImage.style.width = "100%";
						previewImage.style.height = "60px";
                        previewImage.style.display = "block";
                    } else if (fileExtension === "pdf") {
                        // If the file is a PDF, show an icon instead
                        previewImage.src = "https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg"; // Default PDF icon
						
						previewImage.style.width = "130px";
						previewImage.style.height = "100px"; 
                        previewImage.style.display = "block";
                    }
					
                    /*if (previewImage) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = "block";
                    }*/
                    if (previewContainer) {
                        previewContainer.style.display = "block";
                    }
                    if (removePreviewBtn) {
                        removePreviewBtn.style.display = "flex"; // Show remove link
                    }
                    //$('.hid-frontal-div').hide(); // Hide upload label
					if (uploadLabel) {
                        uploadLabel.style.display = "none"; // Hide upload label
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Remove uploaded image preview
    if (removePreviewBtn) {
        removePreviewBtn.addEventListener("click", function(event) {
            event.preventDefault();
            if (previewContainer) {
                previewContainer.style.display = "none";
            }
            if (previewImage) {
                previewImage.src = "";
            }
            if (fileInput) {
                fileInput.value = ""; // Reset file input
            }
            //$('.hid-frontal-div').show(); // Show upload label again
			if (uploadLabel) {
                uploadLabel.style.display = "block"; // Show upload label again
            }
        });
    }

    // Remove already uploaded image from database
    let removeResidenceShowFile = document.getElementById("removeResidenceShowFile");
    if (removeResidenceShowFile) {
        removeResidenceShowFile.addEventListener("click", function(event) {
            event.preventDefault();
            //alert("Removing image..."); // Debugging
			var no_of_delete = $('#no_of_delete').val();
			if(no_of_delete < 3)
			{
				var add_no_of_delete = parseInt(no_of_delete) + parseInt(1);
				//alert(add_no_of_delete);
				$('#no_of_delete').val(add_no_of_delete);
				
				if(add_no_of_delete == 3)
				{
					$('#reject-status').hide();
					$('#kyc-submit').show();
				}
			}
			
            let backShowContainer = document.getElementById("residenceShowContainer");
            let backShowFile = document.getElementById("residenceShowFile");

            if (backShowContainer) {
                backShowContainer.style.display = "none";
            }
            if (backShowFile) {
                backShowFile.src = "";
            }
            if (fileInput) {
                fileInput.value = "";
            }
            //$('.hid-frontal-div').show(); // Show upload label again
			if (uploadLabel) {
                uploadLabel.style.display = "block"; // Show upload label again
            }
			
			// delete file from database 
			// delete file from database 
			var hid_residence_file_name = $('#hid_residence_file_name').val();
			var file_name = hid_residence_file_name;
			if(file_name != '')
			{
				var URL = $('#delete_kyc_doc').val();
				$.ajax({
					url: URL,
					type: "POST",
					data: {'field':'residence',file_name:file_name, _token: csrfToken},
					dataType: 'json',
					success: function(response) {
						if(add_no_of_delete == 3)
						{
							$('#reject-status').hide();
							$('#kyc-submit').show();
						}
					},
				});
			}
        });
    }
});


// --- comment 28-03-2025--------------
/*document.getElementById("residence_file").addEventListener("change", function(event) {
    let file = event.target.files[0];
	$('.hid-residence-div').hide();
    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("residencePreview").src = e.target.result;
            document.getElementById("residencePreviewContainer").style.display = "block";
			document.getElementById("removeResidencePreview").style.display = "flex";
        };
        reader.readAsDataURL(file);
    }
});
document.getElementById("removeResidencePreview").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent default link behavior
	$('.hid-residence-div').show();
    document.getElementById("residencePreviewContainer").style.display = "none"; // Hide preview container
    document.getElementById("removeResidencePreview").style.display = "none"; // Hide remove link
    document.getElementById("residencePreview").src = ""; // Clear preview image
    document.getElementById("residence_file").value = ""; // Reset file input
});*/
</script>

@endsection

