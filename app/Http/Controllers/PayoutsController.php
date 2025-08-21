<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client_payout_request;
use App\Models\Random_payout;
use App\Models\User;

use Yajra\DataTables\Facades\DataTables;

class PayoutsController extends Controller
{
    public function index()
    {
		$data = [];
		/*$data['pending_payout'] = Client_payout_request::where('status', 0)->count();
		$data['accepted_payout'] = Client_payout_request::where('status', 1)->count();
		$data['rejected_payout'] = Client_payout_request::where('status', 2)->count();
		$data['list'] = Client_payout_request::with(['get_user_details'])->get();*/
		// dd($data['list']);
        return view('user.payouts', $data);
    }
	public function getPayoutsData(Request $request)
	{
		$query = Client_payout_request::with('get_user_details');
		
		// Clone for calculations
		$pending_payout = Client_payout_request::where('status', 0)->count();
		$rejected_payout = Client_payout_request::where('status', 2)->count();
		$accepted_payout = Client_payout_request::where('status', 1)->count();
		
		return DataTables::of($query)
			->addColumn('checkbox', function ($val) {
				return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $val->id . '">';
			})
			->addColumn('email', function ($val) {
				return e($val->get_user_details->email ?? '');
			})
			->addColumn('crypto_options', function ($val) {
				return e($val->crypto_options ?? '');
			})
			->addColumn('crypto_address', function ($val) {
				return e($val->usdc_address ?? '');
			})
			->addColumn('crypto_platform', function ($val) {
				return e($val->crypto_platform ?? '');
			})
			->addColumn('phone_number', function ($val) {
				return e($val->crypto_phone ?? '');
			})
			->addColumn('experienced', function ($val) {
				return $val->crypto_experience == 1 ? 'Yes' : ($val->crypto_experience === 0 ? 'No' : '');
			})
			->editColumn('created_at', function ($val) {
				return change_date_format($val->created_at, 'Y-m-d H:i:s', 'd M y');
			})
			->editColumn('requested_amount', function ($val) {
				return e($val->requested_amount);
			})
			->addColumn('status', function ($val) {
				$route = route('payouts.payout-details');
				$class = 'btn-outline-primary';
				$text = 'Pending';

				if ($val->status == 1) {
					$class = 'btn-outline-success';
					$text = 'Accept';
				} elseif ($val->status == 2) {
					$class = 'btn-outline-danger';
					$text = 'Reject';
				}

				return '<button type="button" class="btn btn-sm rounded-pill payout-status ' . $class . '" data-id="' . $val->id . '" data-url="' . $route . '">' . $text . '</button>';
			})
			->rawColumns(['checkbox', 'status'])
			->with([
				'summary' => [
					'pending_payout' => $pending_payout,
					'accepted_payout' => $accepted_payout,
					'rejected_payout' => $rejected_payout,
				]
			])
			->make(true);
	}
    public function random_payout_details(Request $request)
    {
		$data = [];
		/*$searchMonth = $request->post('searchMonth') ?? date('Y-m');
		$data['searchMonth'] = $searchMonth;
		
		// Extract year and month parts
		[$year, $month] = explode('-', $searchMonth);

		// Base query with optional month filter
		$query = Random_payout::query();
		if ($searchMonth) {
			$query->whereYear('created_at', $year)
				  ->whereMonth('created_at', $month);
		}

		// Use same query for all calculations
		$data['total_payout'] = (clone $query)->count();
		$data['payout_usd'] = (clone $query)->sum('profit');
		$data['highest_payout'] = (clone $query)->max('profit');
		$data['list'] = $query->get();*/
        return view('user.random-payouts', $data);
    }
	public function getRandomPayoutData(Request $request)
	{
		$searchMonth = $request->searchMonth ?? date('Y-m');

		[$year, $month] = explode('-', $searchMonth);
		$query = Random_payout::query()->orderBy('id', 'DESC');

		if ($searchMonth) {
			$query->whereYear('created_at', $year)
				  ->whereMonth('created_at', $month);
		}
		
		// Clone for calculations
		$count = (clone $query)->count();
		$sumProfit = (clone $query)->sum('profit');
		$maxProfit = (clone $query)->max('profit');
		
		return DataTables::of($query)
			->addColumn('status', function ($item) {
				return '<button type="button" class="btn btn-sm btn-outline-success rounded-pill">'
					. e($item->status) .
					'</button>';
			})
			->editColumn('profit', function ($item) {
				return get_currency_symbol() . number_format($item->profit, 2);
			})
			->editColumn('account_amount', function ($item) {
				return get_currency_symbol() . number_format($item->account_amount, 2);
			})
			->rawColumns(['status'])
			->with([
				'summary' => [
					'total_payout' => $count,
					'payout_usd' => get_currency_symbol() . number_format($sumProfit, 2),
					'highest_payout' => get_currency_symbol() . number_format($maxProfit, 2),
				]
			])
			->make(true);
	}
    public function update_status(Request $request)
    {
		$change_status = $request->type_val;
		
		$update = Client_payout_request::where('id', $request->status_user)->update(['status'=> $change_status, 'reason'=> $request->reason]);
		
		$data['result'] = $change_status;
		echo json_encode($data);
    }
    public function multi_update_status(Request $request)
    {
		$selectAllMatching = filter_var($request->selectAllMatching, FILTER_VALIDATE_BOOLEAN);
		if ($selectAllMatching) {
			// Apply filters to match DataTables
			$query = Client_payout_request::query();
			$cat_ids = $query->pluck('id')->toArray();
		} else {
			// Only selected rows on this page
			$cat_ids = explode(',',$request->users_id);
		}
				
		$change_status = $request->type_val;
		
		$update = Client_payout_request::whereIn('id', $cat_ids)->update(['status'=> $change_status, 'reason'=> $request->reason]);
		
		$data['result'] = $change_status;
		echo json_encode($data);
    }
    public function payout_details(Request $request)
    {
		$payout = Client_payout_request::where('id', $request->id)->first();
		
		$data['result'] = $payout;
		echo json_encode($data);
    }
}
