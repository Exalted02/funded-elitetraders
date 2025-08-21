<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kyc_documents;
//use App\Models\Cron_kyc_documents;

use Yajra\DataTables\Facades\DataTables;

class KycController extends Controller
{
    public function index()
    {
		$data = [];
		//$data['documents'] = Kyc_documents::with('get_client')->get();
		
        return view('user.kyc', $data);
    }
	public function getKycData(Request $request)
	{
		$documents = Kyc_documents::with('get_client');

		return DataTables::of($documents)
			->addColumn('checkbox', function ($doc) {
				return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $doc->id . '">';
			})
			->addColumn('client_name', function ($doc) {
				return e($doc->get_client->first_name . ' ' . $doc->get_client->last_name);
			})
			->addColumn('email', function ($doc) {
				return e($doc->get_client->email);
			})
			->editColumn('created_at', function ($doc) {
				return \Carbon\Carbon::parse($doc->created_at)->format('d M y');
			})
			->addColumn('status', function ($doc) {
				if ($doc->status == 1) {
					return '<div class="dropdown action-label"><span class="badge badge-soft-warning"><i class="las la-clock"></i> Pending</span></div>';
				} elseif ($doc->status == 2) {
					return '<div class="dropdown action-label"><span class="badge badge-soft-info"><i class="las la-check-double"></i> Approved</span></div>';
				} else {
					return '<div class="dropdown action-label"><span class="badge badge-soft-danger"><i class="las la-times-circle"></i> Reject</span></div>';
				}
			})
			->addColumn('action', function ($doc) {
				return '<div class="dropdown dropdown-action">
							<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown">
								<i class="material-icons">more_vert</i>
							</a>
							<div class="dropdown-menu dropdown-menu-right">
								<a class="dropdown-item kyc-documents-data" href="javascript:void(0)" data-id="' . $doc->id . '" data-url="' . route('kyc-document') . '">
									<i class="fa-regular fa-eye m-r-5"></i> See Details
								</a>
							</div>
						</div>';
			})
			->rawColumns(['checkbox', 'status', 'action'])
			->make(true);
	}
	public function kyc_document(Request $request)
	{
		$data = [];
		$id = $request->id;
		$documents = Kyc_documents::with('get_client')->where('id', $id)->first();
		
		return response()->json([
			'documents_details' => $documents,
			'forntal_path' => asset('uploads/kyc/'. $documents->get_client->id .'/frontal'),
			'back_path' => asset('uploads/kyc/'. $documents->get_client->id .'/back'),
			'residence_path' => asset('uploads/kyc/'. $documents->get_client->id .'/residence')
		]);
		
	}
	public function kyc_document_status_update(Request $request)
	{
		$id = $request->id;
		$status_typ = $request->status_typ;
		$client_dtls = Kyc_documents::with('get_client')->where('id', $id)->first();
		$client_name = $client_dtls->get_client->first_name.' '.$client_dtls->get_client->first_last;
		$APP_NAME  = env('APP_NAME');
		
		if($status_typ == 'reject')
		{
			$has_files = Kyc_documents::where('id',$id)->first();
			if($has_files->frontal != null && $has_files->back != null && $has_files->residence != null)
			{
				$model = Kyc_documents::find($id);
				$model->status = 0;
				$model->save();
				// send mail to client 
				$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="Expert funded" width="150">';

				$email_content = get_email(7);
				if(!empty($email_content))
				{
					$maildata = [
						'subject' => $email_content->message_subject,
						'body' => str_replace(array("[LOGO]", "[SCREEN_NAME]"), array($logo,$APP_NAME), $email_content->message),
						'toEmails' => array($client_dtls->get_client->email),
					];
					
					try {
						send_email($maildata);
						$this->info("Email sent to: {$client_dtls->get_client->email}");
					} catch (\Exception $e) {
						\Log::error("Failed to send email to {$client_dtls->get_client->email}: {$e->getMessage()}");
						
					}
				}
			}
			else{
				return response()->json(['message'=> 'You already rejected']);
			}
		}
		
		if($status_typ == 'accept')
		{
			$has_files = Kyc_documents::where('id',$id)->first();
			if($has_files->frontal != null && $has_files->back != null && $has_files->residence != null)
			{
				$model = Kyc_documents::find($id);
				$model->status = 2;
				$model->save();
				// send mail to client 
				$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="Expert funded" width="150">';
				$email_content = get_email(5);
				if(!empty($email_content))
				{
					$maildata = [
						'subject' => $email_content->message_subject,
						'body' => str_replace(array("[LOGO]", "[SCREEN_NAME]"), array($logo, $APP_NAME), $email_content->message),
						'toEmails' => array($client_dtls->get_client->email),
					];
					
					try {
						send_email($maildata);
						$this->info("Email sent to: {$client_dtls->get_client->email}");
					} catch (\Exception $e) {
						\Log::error("Failed to send email to {$client_dtls->get_client->email}: {$e->getMessage()}");
						
					}
				}
			}
			else{
				return response()->json(['message'=> 'This client did not upload all files']);
			}
		}
		
		$changeStatus = Kyc_documents::where('id',$id )->first()->status;
		return response()->json(['message'=>'','change_status'=> $changeStatus]);
	}
	public function multi_kyc_document_status_update(Request $request)
	{
		$selectAllMatching = filter_var($request->selectAllMatching, FILTER_VALIDATE_BOOLEAN);
		if ($selectAllMatching) {
			// Apply filters to match DataTables
			$query = Kyc_documents::query();
			$cat_ids = $query->pluck('id')->toArray();
		} else {
			// Only selected rows on this page
			$cat_ids = explode(',', $request->id);
		}
		$status_typ = $request->status_typ;
		/*if($status_typ == 'reject')
		{
			$int_status_typ = 0;
		}else{
			$int_status_typ = 2;
		}
		
		foreach($cat_ids as $val){
			$data[] = [
				'kyc_documents_id'=> $val,
				'status_type'=> $int_status_typ,
				'created_at'=> now(),
				'updated_at'=> now(),
			];
		}
		Cron_kyc_documents::insert($data);
		return response()->json(['message'=>'success']);*/
		
		foreach($cat_ids as $val){
			$id = $val;
			$client_dtls = Kyc_documents::with('get_client')->where('id', $id)->first();
			$client_name = $client_dtls->get_client->first_name.' '.$client_dtls->get_client->first_last;
			$APP_NAME  = env('APP_NAME');
			
			if($status_typ == 'reject')
			{
				$has_files = Kyc_documents::where('id',$id)->first();
				if($has_files->frontal != null && $has_files->back != null && $has_files->residence != null)
				{
					$model = Kyc_documents::find($id);
					$model->email_status = 1;
					$model->status = 0;
					$model->save();
					/*
					// send mail to client 
					$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="Expert funded" width="150">';

					$email_content = get_email(7);
					if(!empty($email_content))
					{
						$maildata = [
							'subject' => $email_content->message_subject,
							'body' => str_replace(array("[LOGO]", "[SCREEN_NAME]"), array($logo,$APP_NAME), $email_content->message),
							'toEmails' => array($client_dtls->get_client->email),
						];
						send_email($maildata);
					}*/
				}
			}
			
			if($status_typ == 'accept')
			{
				$has_files = Kyc_documents::where('id',$id)->first();
				if($has_files->frontal != null && $has_files->back != null && $has_files->residence != null)
				{
					$model = Kyc_documents::find($id);
					$model->email_status = 1;
					$model->status = 2;
					$model->save();
					/*
					// send mail to client 
					$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="Expert funded" width="150">';
					$email_content = get_email(5);
					if(!empty($email_content))
					{
						$maildata = [
							'subject' => $email_content->message_subject,
							'body' => str_replace(array("[LOGO]", "[SCREEN_NAME]"), array($logo, $APP_NAME), $email_content->message),
							'toEmails' => array($client_dtls->get_client->email),
						];						
						send_email($maildata);
					}*/
				}
			}
		
			$changeStatus = Kyc_documents::where('id',$id )->first()->status;
		}
		return response()->json(['message'=>'','change_status'=> $changeStatus]);
	}
}
