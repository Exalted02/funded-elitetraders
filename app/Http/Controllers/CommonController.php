<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Client_payout_request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CommonController extends Controller
{
	public function change_multi_status(Request $request)
	{
		$modelClass = $request->model;
		$cat_ids = explode(',',$request->id);
		
		$updated = $modelClass::whereIn('id',$cat_ids)
				->update(['status'=>$request->status]);
		
        if($updated){
			$request->session()->flash('message','Status has been updated successfully.');
			return response()->json(['success'=>'Status has been updated successfully.']);
		}else{
			return response()->json(['success'=>'Status not updated.']);
		}
	}
}
