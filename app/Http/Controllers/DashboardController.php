<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Adjust_users_balance;
use App\Models\Client_payout_request;
use App\Models\Challenge;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class DashboardController extends Controller
{
	public function dashboard_challenge()
	{
		session()->forget('last_selected_challenge');
		
		/////////Check failed challenge
		$account_message = false;
		$today_challenge_count = Challenge::where('user_id', Auth::id())->whereDate('created_at', Carbon::today())->count();
		$today_failed_challenge_count = Challenge::where('user_id', Auth::id())->where('status', 2)->whereDate('created_at', Carbon::today())->count();
		if($today_challenge_count > 0 && $today_challenge_count == $today_failed_challenge_count){
			$account_message = true;
		}
		/////////Check failed challenge

		$data = [];
		$tooltipData = [];
		$challenges = Challenge::with(['get_challenge_type'])->where('user_id', Auth::id())->get();

		foreach ($challenges as $challenge) {
			$baseAmount = $challenge->get_challenge_type->amount;
			$baseDate = change_date_format($challenge->created_at, 'Y-m-d H:i:s', 'd-m');
			
			$adjustments = Adjust_users_balance::selectRaw("DATE_FORMAT(created_at, '%d-%m') as date_label, amount_paid")
				->where('user_id', Auth::id())
				->where('challenge_id', $challenge->id)
				->where('type', 1)
				->orderBy('created_at')
				->get();

			$values = collect([$baseAmount]);
			$runningTotal = $baseAmount;
			$tooltipData = [];
			$tooltipData[] = [
				'balance' => $runningTotal,
				'target' => $baseAmount + ((10 / 100) * $baseAmount),
				'max_drawdown' => (10 / 100) * $baseAmount,
				'max_daily_loss' => (5 / 100) * $runningTotal,
				'equity' => $runningTotal,
			];

			foreach ($adjustments as $entry) {
				$runningTotal += $entry->amount_paid;
				$values->push($runningTotal);
				
				$tooltipData[] = [
					'balance' => $runningTotal,
					'target' => $baseAmount + ((10 / 100) * $baseAmount),
					'max_drawdown' => (10 / 100) * $baseAmount,
					'max_daily_loss' => (5 / 100) * $runningTotal,
					'equity' => $runningTotal,
				];
			}

			$challenge->chart_labels = collect([$baseDate])->merge($adjustments->pluck('date_label'));
			$challenge->chart_values = $values;
			$challenge->tooltip_data = $tooltipData;

			// You can also pass min/max if you want to
			$min = $values->min();
			$max = $values->max();
			$range = $max - $min;
			$buffer = $range * 0.3;

			$challenge->y_min = floor($min - $buffer);
			$challenge->y_max = ceil($max + $buffer);
		}


		return view('client.dashboard-challenge', ['challenge' => $challenges, 'account_message' => $account_message]);
	}


    /*public function dashboard_challenge()
    {
		session()->forget('last_selected_challenge');
		
		$data = [];
		//For Equity
		$challenges  = Challenge::with(['get_challenge_type'])->where('user_id', Auth::id())->get();
		
		//$data['challenge']  = $challenge;
		foreach ($challenges as $challenge) {
			$initialAmount = (float) $challenge->get_challenge_type->amount;
			
			$entries = Adjust_users_balance::selectRaw("DATE_FORMAT(created_at, '%d-%m') as date_label, amount_paid")
				->where('user_id', Auth::id())
				->where('challenge_id', $challenge->id)
				->where('type', 1)
				->orderBy('created_at')
				->get();
				
			$challenge->chart_labels = $entries->pluck('date_label');
			$challenge->chart_values = $entries->pluck('amount_paid')->map(fn($v) => (float)$v);
		}
		// dd($challenges);
		return view('client.dashboard-challenge', ['challenge' => $challenges]);
        //return view('client.dashboard-challenge', $data);
    }*/
    public function index($id='')
    {
		session()->put('last_selected_challenge', $id);		
		// dd(session()->get('last_selected_challenge'));
		
		$data = [];
		$labels = [];
		$values = [];
		$previous_chart_amount = 0;
		//For Equity
		// $equity = Challenge::with(['get_challenge_type'])->where('user_id', Auth::id())->whereDate('created_at', Carbon::today())->get();
		$equity = Challenge::with(['get_challenge_type'])->where('id', $id)->where('user_id', Auth::id())->get();
		$equity_amount = $equity_percent = $initial_amount = $amount_paid_balance = $challenge_status = $challenge_actual_amount = 0;
		foreach($equity as $equity_val){
			$adjust_users_balance = Adjust_users_balance::where('user_id', Auth::id())->where('challenge_id', $id)->where('type', 1)->sum('amount_paid');
			$equity_percent = ($adjust_users_balance / $equity_val->get_challenge_type->amount) * 100;
			// $equity_amount = $equity_amount + ($equity_val->amount_paid + $equity_val->get_challenge_type->amount);
			$equity_amount = $equity_amount + ($adjust_users_balance + $equity_val->get_challenge_type->amount);
			$initial_amount = $initial_amount + $equity_val->get_challenge_type->amount;
			// $amount_paid_balance = $amount_paid_balance + $equity_val->amount_paid;
			$amount_paid_balance = $adjust_users_balance;
			$challenge_status = $equity_val->status;
			$challenge_actual_amount = $equity_val->get_challenge_type->amount;
			
			$labels[] = change_date_format($equity_val->created_at, 'Y-m-d H:i:s', 'd-m-Y');
			$previous_chart_amount = $equity_val->get_challenge_type->amount;
			$values[] = $previous_chart_amount;
			
			$tooltipData[] = [
                'balance' => $previous_chart_amount,
                'target' => $challenge_actual_amount + ((10 / 100) * $challenge_actual_amount),
                'max_drawdown' => (10 / 100) * $challenge_actual_amount,
                'max_daily_loss' => (5 / 100) * $previous_chart_amount,
                'equity' => $previous_chart_amount, // example: 2% loss
            ];
		}
		
		//For eligible withdraw
		$eligible_withdraw = Adjust_users_balance::where('user_id', Auth::id())
					->where('created_at', '<', Carbon::now()->subDays(30))
					// ->where('type', '!=', 0)
					// ->where('status', '!=', 2)
					->where('type', 2)
					->where('status', 2)
					->sum('amount_paid');
		
		//For chart
		$entries = Adjust_users_balance::selectRaw("DATE_FORMAT(created_at, '%d-%m-%Y') as date_label, amount_paid, created_at")
		->where('user_id', Auth::id())
		->where('type', 1)
        ->where('challenge_id', $id)
        ->orderBy('created_at')
        ->get();

		

		foreach ($entries as $entry) {
			$labels[] = $entry->date_label;
			// $values[] = abs((float) $entry->amount_paid);
			$previous_chart_amount = $previous_chart_amount +  (float) $entry->amount_paid;
			$values[] = $previous_chart_amount;
			
			// dd($entry);
			// $adjust_users_balance_date = Adjust_users_balance::where('user_id', Auth::id())->where('challenge_id', $id)->where('type', 1)->where('created_at', '<', $entry->created_at)->sum('amount_paid');
			$tooltipData[] = [
                'balance' => $previous_chart_amount,
                'target' => $challenge_actual_amount + ((10 / 100) * $challenge_actual_amount),
                'max_drawdown' => (10 / 100) * $challenge_actual_amount,
                'max_daily_loss' => (5 / 100) * $previous_chart_amount,
                'equity' => $previous_chart_amount, // example: 2% loss
            ];
		}
		// dd($values);
		
		$data['equity_amount']  = $equity_amount;
		$data['equity_percent']  = $equity_percent;
		$data['initial_amount']  = $initial_amount;
		$data['amount_paid_balance']  = $amount_paid_balance;
		$data['eligible_withdraw']  = $equity_amount - $eligible_withdraw;
		// $data['total_balance']  = $equity_amount + $eligible_withdraw;
		$data['total_balance']  = $equity_amount;		
		$data['challenge_status']  = $challenge_status;
		
		$data['chartLabels']  = $labels;
		$data['chartData']  = $values;
		$data['tooltipData']  = $tooltipData;
		// dd($data['tooltipData']);
		
					
		//Check trade static data
		$challenge_val = Challenge::where('id', $id)->where('user_id', Auth::id())->first();
		if($challenge_val->trade_date != date('Y-m-d')){
			$trade_count = rand(1, 10);
			$trade_pair = getRandomSymbol();
			$trade_result = $equity_percent < 0 ? $equity_percent : '+' . $equity_percent;
			
			$update = Challenge::where('id', $id)
					->update(['trade_date' => date('Y-m-d'), 'trade_count' => $trade_count, 'trade_pair' => $trade_pair, 'trade_result' => $trade_result]);
		}
		$challenge_val = Challenge::where('id', $id)->where('user_id', Auth::id())->first();
		$data['challenge_val']  = $challenge_val;
		
		$adj_rec = [];
		$adjust_records = Adjust_users_balance::where('challenge_id', $id)->where('type', 1)
			->get()
			->groupBy(function ($item) {
				return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
			});
		// dd($adjust_records);
		$adjust_total_amount_with_challenge_amount = $challenge_actual_amount;
		foreach($adjust_records as $k=>$adjust_records_val){
			$adjust_daywise_users_balance = Adjust_users_balance::where('challenge_id', $id)->where('type', 1)->whereDate('created_at', $k)->sum('exact_amount_paid');
			
			/*$challenge_each_day = Adjust_users_balance::where('type', 1)->whereDate('created_at', $k)->pluck('challenge_id');
			$challenge_adjust_list = Challenge::with(['get_challenge_type'])->whereIn('id', $challenge_each_day)->get();
			$c_actual_amount = 0;
			foreach($challenge_adjust_list as $val){
				$c_actual_amount += $val->get_challenge_type->amount;
			}
			// $equity_daywise_percent = ($adjust_daywise_users_balance / $c_actual_amount) * 100;
			*/
			$equity_daywise_percent = ($adjust_daywise_users_balance / $adjust_total_amount_with_challenge_amount) * 100;
			$adjust_total_amount_with_challenge_amount += $adjust_daywise_users_balance;
			
			
			$adj_rec[$k]['trades'] = $adjust_records_val[0]->trade_count;
			$adj_rec[$k]['trade_pair'] = $adjust_records_val[0]->trade_pair;
			if($adjust_records_val[0]->trade_count == null){
				$exists_same_date_trade_count = Adjust_users_balance::where('type', 1)->whereDate('created_at', $k)->whereNotNull('trade_count')->first();
				$adjust_record = Adjust_users_balance::find($adjust_records_val[0]->id);
				if($exists_same_date_trade_count){
					$adjust_record->trade_count = $exists_same_date_trade_count->trade_count;
				}else{
					// $adjust_record->trade_count = rand(1, 10);
					$adjust_record->trade_count = generateLots($challenge_actual_amount);
				}
				$adjust_record->save();
				$adj_rec[$k]['trades'] = $adjust_record->trade_count;
			}
			if($adjust_records_val[0]->trade_pair == null){
				$exists_same_date_trade_pair = Adjust_users_balance::where('type', 1)->whereDate('created_at', $k)->whereNotNull('trade_pair')->first();
				$adjust_record = Adjust_users_balance::find($adjust_records_val[0]->id);
				if($exists_same_date_trade_pair){
					$adjust_record->trade_pair = $exists_same_date_trade_pair->trade_pair;
				}else{
					$adjust_record->trade_pair = getRandomSymbol();
				}
				$adjust_record->save();
				$adj_rec[$k]['trade_pair'] = $adjust_record->trade_pair;
			}
			
			$adj_rec[$k]['date'] = $k;
			$adj_rec[$k]['trade_result'] = $equity_daywise_percent < 0 ? number_format($equity_daywise_percent, 2, '.', ',') : '+' . number_format($equity_daywise_percent, 2, '.', ',');
		}
		$data['adj_rec'] = $adj_rec;
		
		$tradePairCount = [];
		foreach ($adj_rec as $adj_rec_item) {
			$pair = $adj_rec_item['trade_pair'];
			if (isset($tradePairCount[$pair])) {
				$tradePairCount[$pair]++;
			} else {
				$tradePairCount[$pair] = 1;
			}
		}
		$data['trade_pair_count'] = $tradePairCount;
		
		if($challenge_val->account_size_rand_number == null){
			$challenge_val->account_size_rand_number = generateLots($challenge_actual_amount);
			$challenge_val->save();
		}
		$data['lot_sizes'] = [
			change_date_format($challenge_val->created_at, 'Y-m-d H:i:s', 'd M y') => $challenge_val->account_size_rand_number,
		];
		
		// dd($tradePairCount);
		//Check trade static data
        return view('client.dashboard', $data);
    }
	/*private function generateLots($accountAmount)
	{
		if ($accountAmount == 25000) {
			// Only 25K gets a decimal range
			return $this->randomFloat(1, 2.5);
		} elseif ($accountAmount == 50000) {
			return rand(2, 4);
		} elseif ($accountAmount == 100000) {
			return rand(4, 6);
		} elseif ($accountAmount == 200000) {
			return rand(6, 8);
		} elseif ($accountAmount >= 300000) {
			return rand(8, 10);
		}

		return 1; // fallback if no match
	}

	private function randomFloat($min, $max, $decimals = 2)
	{
		$factor = pow(10, $decimals);
		return mt_rand($min * $factor, $max * $factor) / $factor;
	}*/
    public function account()
    {
		$data = [];
		$data['user']  = User::where('id', auth()->user()->id)->first();		
        return view('client.account', $data);
    }
    public function verification()
    {
		$data = [];		
        return view('client.verification', $data);
    }
    public function withdraw()
    {
		$data = [];
		/*
		// Get current year and month
		$now = Carbon::now();
		
		$totalDays = Carbon::now()->daysInMonth;
		$currentDay = Carbon::now()->day;
		$eligibleDate = Carbon::now()->addDays(35);
		
		$trading_day = Adjust_users_balance::where('user_id', Auth::id())->where('type', 1)
						->select(DB::raw('DATE(created_at) as adjust_date'))
						->groupBy(DB::raw('DATE(created_at)'))
						->get()
						->count();
		$data['total_day']  = $totalDays;
		$data['current_day']  = $currentDay;
		$data['trading_day']  = $trading_day;
		$data['eligible_date']  = $eligibleDate->toDateString();
		*/
		if(session()->get('last_selected_challenge') == ''){
			return redirect(RouteServiceProvider::CLIENT_HOME);
		}
		if(Auth::user()->eligible_withdraw == 0){
			/*$challenge = Challenge::where('id', session()->get('last_selected_challenge'))->first();
			$eligible_date = get_adddays_without_weekend($challenge->funded_date);*/
			
			$firstFundedDate = Challenge::where('user_id', Auth::id())->orderBy('funded_date', 'asc')
				->whereNotNull('funded_date')
				->value('funded_date');				
			$eligible_date = get_adddays_without_weekend($firstFundedDate);
			
			$days_remain = get_dayremain_without_weekend($eligible_date);
			$data['days_remaining']  = $days_remain.' Days remaining';
			$data['percent_bar']  = ((35-$days_remain) / 35) * 100;
			$data['eligible_date']  = $eligible_date;
		}else{
			$data['days_remaining']  = 'Ready for withdraw';
			$data['percent_bar']  = 100;
			$data['eligible_date']  = Carbon::now()->format('Y-m-d');
		}
		
		// dd($challenge->funded_date);
        return view('client.withdraw', $data);
    }
	
    public function withdraw_request_amount(Request $request)
    {
		/*$challenge = Challenge::where('id', session()->get('last_selected_challenge'))->first();
		$eligible_date = get_adddays_without_weekend($challenge->funded_date);*/
		
		$firstFundedDate = Challenge::where('user_id', Auth::id())->orderBy('funded_date', 'asc')
				->whereNotNull('funded_date')
				->value('funded_date');
		$eligible_date = get_adddays_without_weekend($firstFundedDate);
		
		if($eligible_date <= Carbon::now()->format('Y-m-d') || Auth::user()->eligible_withdraw == 1){
			$get_records = Adjust_users_balance::where('user_id', Auth::id())
				//->where('created_at', '<', Carbon::now()->subDays(35))
				/*->when(Auth::user()->eligible_withdraw == 0, function ($query) {
					$query->where('created_at', '<', Carbon::now()->subDays(35));
				})*/
				->where('type', 1)
				->where('challenge_id', session()->get('last_selected_challenge'))
				// ->where('type', '!=', 0)
				->where('status', 0)
				->pluck('id');
			
			$get_records_amount = Adjust_users_balance::where('user_id', Auth::id())
				//->where('created_at', '<', Carbon::now()->subDays(35))
				/*->when(Auth::user()->eligible_withdraw == 0, function ($query) {
					$query->where('created_at', '<', Carbon::now()->subDays(35));
				})*/
				->where('type', 1)
				->where('challenge_id', session()->get('last_selected_challenge'))
				// ->where('type', '!=', 0)
				->where('status', 0)
				->sum('amount_paid');
				
			$data['get_records'] = implode(', ', $get_records->toArray());
			$data['get_records_amount'] = $get_records_amount;
		}else{
			$data['get_records'] = '';
			$data['get_records_amount'] = 0;
		}
		echo json_encode($data);
    }
    public function withdraw_submit(Request $request)
    {
		$request->validate([
            'crypto_options'     => 'required|string',
			'usdc_address'       => 'required|string',
			'crypto_platform'    => 'required|string',
			'crypto_phone'       => 'required|regex:/^[0-9]{10,15}$/',
			'crypto_experience'  => 'required|in:0,1',
        ],[
			'usdc_address' => 'The Crypto address field is required.',
			'crypto_phone.regex' => 'Please enter a valid phone number.',
		]);
		
		$removeRandomMiddleLetter = $this->removeRandomMiddleLetter($request->usdc_address);
		
		$payout = new Client_payout_request();
		$payout->user_id = Auth::id();
		$payout->requested_amount = $request->withdrawable_balance_input;
		$payout->withdrawable_adjust_id = $request->withdrawable_id;
		$payout->status = 0;
		$payout->usdc_address = $request->usdc_address;
		$payout->usdc_edit_address = $removeRandomMiddleLetter;
		$payout->crypto_options = $request->crypto_options ?? null;
		$payout->crypto_platform = $request->crypto_platform ?? null;
		$payout->crypto_phone = $request->crypto_phone ?? null;
		$payout->crypto_experience = $request->crypto_experience ?? null;
		if($payout->save()){
			$ids = explode(', ', $request->withdrawable_id);
			
			$update = Adjust_users_balance::whereIn('id', $ids)
				->update(['status' => 1]);
			
			// send mail to client 
			$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="Expert funded" width="150">';
			$APP_NAME  = env('APP_NAME');
			$email_content = get_email(9);
			if(!empty($email_content))
			{
				$maildata = [
					'subject' => $email_content->message_subject,
					'body' => str_replace(array("[LOGO]", "[SCREEN_NAME]", "[AMOUNT]", "[CRYPTO_ADDRESS]"), array($logo, $APP_NAME, get_currency_symbol().$payout->requested_amount, $payout->usdc_edit_address), $email_content->message),
					'toEmails' => array(auth()->user()->email),
				];
				
				try {
					send_email($maildata);
				} catch (\Exception $e) {
					//
				}
			}
			
			$data['result'] ='success';	
			$data['message'] ='Great! We will initiate the payout after we see all rules have been followed, give us 5-7 business days to do this. In the meantime you can contact us on telegram <a href="https://t.me/expertpropfirmsupport" target="_blank">https://t.me/expertpropfirmsupport</a>';	
		}else{
			$data['result'] ='error';
		}
		
		echo json_encode($data);
    }
	function removeRandomMiddleLetter($str)
	{
		$length = strlen($str);
		if ($length < 3) return $str; // Too short to trim middle

		// Define middle range: ignore first and last 25%
		$start = floor($length * 0.25);
		$end = ceil($length * 0.75);

		// Loop until we find a letter in the middle
		while (true) {
			$randomIndex = rand($start, $end - 1);
			if (ctype_alpha($str[$randomIndex])) {
				// Remove the character
				return substr($str, 0, $randomIndex) . substr($str, $randomIndex + 1);
			}

			// Break if it loops too many times (to avoid infinite loop)
			static $tries = 0;
			if (++$tries > 20) break;
		}

		// Fallback: return unchanged if no letter found
		return $str;
	}
	public function update_client_account(Request $request)
	{
		$first_name = $request->first_name;
		$last_name = $request->last_name;
		$password = $request->password;
		$id = auth()->user()->id;
		
		$model = User::find($id);
		$model->first_name = $first_name;
		$model->last_name = $last_name;
		$model->name = $first_name.' '.$last_name;
		if(!empty($request->password))
		{
			$model->password = Hash::make($request->password);
		}
		$model->save();
		
		return response()->json(['message'=> 'Account updated successfully']);
	}
    public function account_data()
    {
		// $data = [];
		/*$user  = User::where('id', auth()->user()->id)->first();
		if($user->trading_account_id == null || $user->trading_account_pw == null){
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
			$trading_password = '';
			$length = 10;

			for ($i = 0; $i < $length; $i++) {
				$trading_password .= $characters[random_int(0, strlen($characters) - 1)];
			}
						
			$user->trading_account_id = mt_rand(10000000, 99999999);
			$user->trading_account_pw = $trading_password;
			$user->save();
		}*/
		$challenge = Challenge::where('id', session()->get('last_selected_challenge'))->first();
		if($challenge->client_id == null){
			$challenge->client_id = mt_rand(10000000, 99999999);
			$challenge->save();
		}
		if($challenge->client_pw == null){
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
			$trading_password = '';
			$length = 10;

			for ($i = 0; $i < $length; $i++) {
				$trading_password .= $characters[random_int(0, strlen($characters) - 1)];
			}
			
			$challenge->client_pw = $trading_password;
			$challenge->save();
		}
		$data['trading_account_id'] = $challenge->client_id;
		$data['trading_account_pw'] = $challenge->client_pw;
		
		return $data;
    }
}
