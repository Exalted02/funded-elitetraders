<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;
use App\Services\ChallengeService;
use Yajra\DataTables\Facades\DataTables;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
		$data = [];
		
        return view('user.certificate', $data);
    }
	public function getCertificate(Request $request)
	{
		$query = Certificate::where('status', 1);

		return DataTables::of($query)
			->addColumn('date', fn($row) => \Carbon\Carbon::parse($row->certificate_date)->format('d M y'))
			->editColumn('name', fn($row) => $row->certificate_name)
			->addColumn('amount', fn($row) => $row->certificate_amount)
			->addColumn('state', function ($row) {
				$dropdown = '
					<div class="action-label">
						<a class="btn btn-white btn-sm btn-rounded" target="_blank" href="'.route('certificate.view', $row->certificate_id).'">
							<i class="fa-regular fa-circle-dot text-purple"></i> See Certificate
						</a>
					</div>';

				return $dropdown;
			})
			->addColumn('actions', fn($row) => '
				<div class="dropdown dropdown-action text-end">
					<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="material-icons">more_vert</i>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item edit-data" href="javascript:void(0);" data-id="' . $row->id . '" data-url="' . route('certificate.certificate-update-data') . '">
							<i class="fa-solid fa-pencil m-r-5"></i> Edit
						</a>
						<a class="dropdown-item delete-data" href="javascript:void(0);" data-id="' . $row->id . '" data-url="' . route('certificate.get_delete_data') . '">
							<i class="fa-regular fa-trash-can m-r-5"></i> Delete
						</a>
					</div>
				</div>')
			->rawColumns(['state', 'actions'])
			->make(true);
	}
	
    public function viewCertificate($id)
    {
		$data['id'] = $id;
		return view('user.view-certificate', $data);
	}
	public function update_data(Request $request)
	{
		$user = Certificate::where('id', $request->id)->first();
		$data['result'] = $user;
		$data['result_date'] = change_date_format($user->certificate_date, 'Y-m-d', 'd-m-Y');
		echo json_encode($data);
	}
    public function certificate_submit(Request $request)
    {
		$request->validate([
            'certificate_date' => 'required',
            'certificate_name' => 'required',
			'certificate_amount' => 'required|numeric|min:1', // Ensure it's a number and greater than zero
        ],[],[
			'certificate_date' => 'date',
            'certificate_name' => 'name',
			'certificate_amount' => 'amount',
		]);
		
		if($request->post('hid_id') && $request->post('hid_id') != ''){
			$model = Certificate::find($request->post('hid_id'));
			$model->certificate_date = change_date_format($request->post('certificate_date'), 'd-m-Y', 'Y-m-d');
			$model->certificate_name = $request->post('certificate_name');
			$model->certificate_amount = $request->post('certificate_amount');
			
			$certificate_id = $model->certificate_id;
		}else{
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			$certificate_id = '';
			$length = 50;

			for ($i = 0; $i < $length; $i++) {
				$certificate_id .= $characters[random_int(0, strlen($characters) - 1)];
			}
			
			$model = new Certificate();
			$model->certificate_id = $certificate_id;
			$model->certificate_date = change_date_format($request->post('certificate_date'), 'd-m-Y', 'Y-m-d');
			$model->certificate_name = $request->post('certificate_name');
			$model->certificate_amount = $request->post('certificate_amount');
		}
		
		$challenge_service = resolve(ChallengeService::class);
		$attatchment = $challenge_service->generateVerificationCertificate($certificate_id, $model->certificate_name, $model->certificate_amount, $model->certificate_date);
		
		if($model->save()){
			if($request->post('hid_id') && $request->post('hid_id') != ''){
				return response()->json([
					'success' => true,
					'type' => 2,
					'message' => 'Certificate created successfully.'
				]);
			}else{
				return response()->json([
					'success' => true,
					'type' => 1,
					'message' => 'Certificate updated successfully.'
				]);
			}
		}else{
			return response()->json([
				'success' => false,
				'message' => 'Certificate not created.'
			]);
		}
    }	
	public function get_delete_data(Request $request)
	{
		$certificate = Certificate::where('id', $request->id)->first();
		$data['result'] = $certificate;
		echo json_encode($data);
	}
	public function final_delete_submit(Request $request)
	{
		$del = Certificate::find($request->id);
		$del->status = 2;
		if($del->save()){
			$data['result'] ='success';
		}else{
			$data['result'] ='error';
		}		
		echo json_encode($data);
	}
}
