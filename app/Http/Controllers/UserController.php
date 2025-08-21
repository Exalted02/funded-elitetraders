<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Adjust_users_balance;
use App\Models\Custom_user_email;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
		$data = [];
		//$data['list'] = User::where('status', '!=', 2)->where('user_type', 1)->get();
        return view('user.user-accounts', $data);
    }
	public function getUsers(Request $request)
	{
		$query = User::where('status', '!=', 2)->where('user_type', 1);
		
		return DataTables::of($query)
			->addColumn('checkbox', fn($row) => '<input type="checkbox" class="form-check-input row-checkbox" value="'.$row->id.'">')
			->editColumn('name', fn($row) => $row->name)
			->editColumn('email', fn($row) => $row->email)
			->addColumn('phone_number', fn($row) => $row->phone_number)
			->addColumn('users_balances', fn($row) => $row->users_balances)
			->addColumn('created_at', fn($row) => change_date_format($row->created_at, 'Y-m-d H:i:s', 'd M y'))
			->addColumn('dashboard_html', function ($user) {
				return '<div class="action-label">
							<a class="btn btn-white btn-sm btn-rounded" href="' . route('admin.impersonate', $user->id) . '" data-id="' . $user->id . '">
								<i class="fa-regular fa-circle-dot text-purple"></i> User Dashboard
							</a>
						</div>';
			})
			->addColumn('status_html', function ($user) {
				$status = $user->status == 1 ? 'Active' : 'Suspended';
				$btnClass = $user->status == 1 ? 'badge-outline-success' : 'badge-outline-danger';
				$iconClass = $user->status == 1 ? 'text-success' : 'text-danger';

				return '
					<div class="dropdown action-label">
						<a class="btn btn-white btn-sm ' . $btnClass . ' dropdown-toggle" href="#" data-bs-toggle="dropdown">
							<i class="fa-regular fa-circle-dot ' . $iconClass . '"></i> ' . $status . '
						</a>
						<div class="dropdown-menu">
							<a class="dropdown-item update-status" href="javascript:void(0);"
							   data-id="' . $user->id . '"
							   data-url="' . route('users.user-update-status') . '"
							   data-type="1">
							   <i class="fa-regular fa-circle-dot text-success"></i> Active
							</a>
							<a class="dropdown-item update-status" href="javascript:void(0);"
							   data-id="' . $user->id . '"
							   data-url="' . route('users.user-update-status') . '"
							   data-type="0">
							   <i class="fa-regular fa-circle-dot text-danger"></i> Suspend
							</a>
						</div>
					</div>';
			})
			->addColumn('withdraw_html', function ($user) {
				$label = $user->eligible_withdraw ? 'Allowed' : 'Not Allowed';
				$btnClass = $user->eligible_withdraw ? 'badge-outline-success' : 'badge-outline-danger';
				$iconClass = $user->eligible_withdraw ? 'text-success' : 'text-danger';

				return '
					<div class="dropdown action-label">
						<a class="btn btn-white btn-sm ' . $btnClass . ' dropdown-toggle" href="#" data-bs-toggle="dropdown">
							<i class="fa-regular fa-circle-dot ' . $iconClass . '"></i> ' . $label . '
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item update-eligible-withdraw" href="javascript:void(0);" data-id="' . $user->id . '" data-url="' . route('users.user-allow-withdraw') . '" data-type="1">
								<i class="fa-regular fa-circle-dot text-success"></i> Allow
							</a>
							<a class="dropdown-item update-eligible-withdraw" href="javascript:void(0);" data-id="' . $user->id . '" data-url="' . route('users.user-allow-withdraw') . '" data-type="0">
								<i class="fa-regular fa-circle-dot text-danger"></i> Not Allow
							</a>
						</div>
					</div>';
			})
			->addColumn('actions', function ($user) {
				return '
					<div class="dropdown dropdown-action text-end">
						<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
							<i class="material-icons">more_vert</i>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item edit-data" href="javascript:void(0);" data-id="' . $user->id . '" data-url="' . route('users.user-update-data') . '">
								<i class="fa-solid fa-pencil m-r-5"></i> Edit
							</a>
							<a class="dropdown-item delete-data" href="javascript:void(0);" data-id="' . $user->id . '" data-url="' . route('users.get_delete_data') . '">
								<i class="fa-regular fa-trash-can m-r-5"></i> Delete
							</a>
						</div>
					</div>';
			})
			->rawColumns(['checkbox', 'name', 'email', 'phone_number', 'users_balances', 'created_at', 'dashboard_html', 'status_html', 'withdraw_html', 'actions'])
			->make(true);
	}
	public function update_data(Request $request)
	{
		$user = User::where('id', $request->id)->first();
		$data['result'] = $user;
		echo json_encode($data);
	}
	public function submit_data(Request $request)
	{
		$request->validate([
            //'email' => 'required|email|unique:users,email,'.$request->id,
            'first_name' => 'required',
            'last_name' => 'required',
			'phone_number' => 'nullable|regex:/^[0-9]{10,15}$/',
        ]);
		
		$user = User::find($request->id);
		//$user->email = $request->email;
		$user->name = $request->first_name.' '.$request->last_name;
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->phone_number = $request->phone_number;
		if(!empty($request->password))
		{
			$user->password = Hash::make($request->password);
		}
		if($user->save()){			
			return response()->json([
				'success' => true,
				'message' => 'User data updated successfully.'
			]);
		}else{
			return response()->json([
				'success' => false,
				'message' => 'User data not updated.'
			]);
		}
	}
	public function update_status(Request $request)
	{
		// $status = User::where('id', $request->id)->first()->status;
		// $change_status = $status == 1 ? 0 : 1;
		
		$change_status = $request->type_val;
		
		$update = User::where('id', $request->id)->update(['status'=> $change_status]);
		
		$data['result'] = $change_status;
		echo json_encode($data);
	}
	public function get_delete_data(Request $request)
	{
		$user = User::where('id', $request->id)->first();
		$data['result'] = $user;
		echo json_encode($data);
	}
	public function final_delete_submit(Request $request)
	{
		$del = User::find($request->id);
		$del->status = 2;
		if($del->save()){
			$data['result'] ='success';
		}else{
			$data['result'] ='error';
		}		
		echo json_encode($data);
	}
	public function adjust_balance(Request $request)
	{
		$request->validate([
            'adjust_amount' => 'required',
        ],[
			'adjust_amount' => 'Amount is required.',
		]);
		
		$adj_balance = new Adjust_users_balance();
		$adj_balance->user_id = $request->adjust_amount_user;
		$adj_balance->amount_paid = $request->adjust_amount;
		if($request->type == 'add'){
			$adj_balance->type = 1;
		}else{
			$adj_balance->type = 0;
		}
		$adj_balance->status = 0;
		$adj_balance->save();
		
		$user = User::find($request->adjust_amount_user);
		if($request->type == 'add'){
			$user->users_balances = $user->users_balances + $request->adjust_amount;
		}else{
			$user->users_balances = $user->users_balances - $request->adjust_amount;
		}
		if($user->save()){
			$data['result'] ='success';
		}else{
			$data['result'] ='error';
		}		
		echo json_encode($data);
	}
	public function multi_adjust_balance(Request $request)
	{
		$request->validate([
            'adjust_percent' => 'required',
        ],[
			'adjust_percent' => 'Percent is required.',
		]);
		
		$cat_ids = explode(',',$request->users_id);
		foreach($cat_ids as $k=>$id_val){
			$user = User::find($id_val);
			if($user->users_balances > 0){
				$percentage_value = $user->users_balances * ($request->adjust_percent/100);
				
				$adj_balance = new Adjust_users_balance();
				$adj_balance->user_id = $id_val;
				$adj_balance->amount_paid = $percentage_value;
				$adj_balance->percentage_value = $request->adjust_percent;
				if($request->type_val == 'add'){
					$adj_balance->type = 1;
				}else{
					$adj_balance->type = 0;
				}
				$adj_balance->status = 0;
				$adj_balance->save();
			
				if($request->type_val == 'add'){
					$user->users_balances = $user->users_balances + $percentage_value;
				}else{
					$user->users_balances = $user->users_balances - $percentage_value;
				}
				$user->save();
			}
		}
		$data['result'] ='success';
		echo json_encode($data);
	}
	public function allow_withdraw(Request $request)
	{
		$change_status = $request->type_val;
		
		$update = User::where('id', $request->id)->update(['eligible_withdraw'=> $change_status]);
		
		$data['result'] = $change_status;
		echo json_encode($data);
	}
	public function multi_send_user_email(Request $request)
	{
		$selectAllMatching = filter_var($request->selectAllMatching, FILTER_VALIDATE_BOOLEAN);
		if ($selectAllMatching) {
			// Apply filters to match DataTables
			$query = User::query();
			$user_ids = $query->pluck('id')->toArray();
		} else {
			// Only selected rows on this page
			$user_ids = explode(',', $request->users_id);
		}
		
		foreach($user_ids as $user_id_val){
			$data[] = [
				'user_id'=> $user_id_val,
				'message_subject'=> $request->message_subject,
				'message'=> $request->message,
				'created_at'=> date('Y-m-d H:i:s'),
				'updated_at'=> date('Y-m-d H:i:s'),
			];
		}
		Custom_user_email::insert($data);
		
		$data['result'] ='success';
		echo json_encode($data);
	}
	public function multi_allow_withdraw_submit(Request $request)
	{
		$cat_ids = explode(',', $request->id);
		
		$update = User::whereIn('id', $cat_ids)->update(['eligible_withdraw'=> 1]);
		
		$data['result'] ='success';
		echo json_encode($data);
	}
	public function impersonateUser($id)
	{
		$user = User::find($id);

		if ($user) {
			Auth::loginUsingId($id);
			session(['impersonate_user_id' => $id]);
			session(['admin_id' => Auth::id()]);
			return redirect()->route('client.dashboard-challenge');
		}

		return redirect()->back()->with('error', 'User not found.');
	}
	public function back_to_admin()
	{
		$id = 1;
		Auth::loginUsingId($id);
		session()->forget('admin_id');
		session()->forget('impersonate_user_id');
		return redirect()->route('users.index')->with('success', 'You are back as Admin.');
	}
}
