<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Challenge;
use App\Models\Adjust_users_balance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
	public function welcome()
    {
		if(Auth::user()){
			return Redirect::route('login');
			// return view('dashboard');
		}else{
			return view('auth.login');
		}
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/login');
    }
	public function contact()
    {
		return view('contact');
    }
	public function run_script()
    {
		/*
		$get_challenges = Challenge::where('status', 1)->get();
		foreach($get_challenges as $val){
			$val->funded_date = change_date_format($val->updated_at, 'Y-m-d H:i:s', 'Y-m-d');
			$val->save();
			//dd($val);
		}*/
		/*$adjust_records = Adjust_users_balance::where('type', 1)->whereNotNull('trade_pair')
			->get()
			->groupBy(function ($item) {
				return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
			});
		foreach($adjust_records as $k=>$adjust_records_val){
			$adjust_daywise_users_balance = Adjust_users_balance::whereDate('created_at', $k)->whereNotNull('trade_pair')->first();
			foreach($adjust_records_val as $val){
				$val->trade_pair = $adjust_daywise_users_balance->trade_pair;
				$val->trade_count = $adjust_daywise_users_balance->trade_count;
				$val->save();
			}
		}*/
		$adjust_records = Adjust_users_balance::where('type', 1)->whereNotNull('percentage_value')
			->get();
		foreach($adjust_records as $k=>$adjust_records_val){
			$get_challenge = Challenge::with(['get_challenge_type'])->where('id', $adjust_records_val->challenge_id)->first();
			
			$adjust_records_val->exact_amount_paid = $get_challenge->get_challenge_type->amount * ($adjust_records_val->percentage_value/100);
			$adjust_records_val->save();
		}			
    }
}
