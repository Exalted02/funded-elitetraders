<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Challenge;
use App\Models\Adjust_users_balance;
// use App\Models\Cron_adjust_balance;

use App\Events\ChallengeTradeEvent;
use App\Services\ChallengeService;

class MultiAdjustBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:multi-adjust-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Multi adjust balance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
		$adjust_balance = Adjust_users_balance::where('email_status', 1)->get();
		foreach($adjust_balance as $adjust_balance_val){
			$id_val = $adjust_balance_val->challenge_id;
			$request_adjust_percent = $adjust_balance_val->percentage_value;
			
			$get_challenge = Challenge::with(['get_challenge_type'])->where('id', $id_val)->first();
			
			$APP_NAME  = env('APP_NAME');
			$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="Expert funded" width="150">';
			//For Funded email
			if($get_challenge->funded_email_status == 1){
				$phase = $get_challenge->get_challenge_type->title;
				$email_content = get_email(4);
				if(!empty($email_content))
				{
					$maildata = [
						'subject' => $email_content->message_subject,
						'body' => str_replace(array("[LOGO]", "[PHASE]", "[SCREEN_NAME]", "[YEAR]"), array($logo, $phase, $APP_NAME, date('Y')), $email_content->message),
						'toEmails' => array($get_challenge->email),
						//'files' => array($attatchment),
					];
					if(env('CERTIFICATE_SEND')){
						$challenge_service = resolve(ChallengeService::class);
						
						$name = $get_challenge->first_name.' '.$get_challenge->last_name;
						// $attatchment = $challenge_service->generateCertificate($id_val, $name, $adjust_users_balance, $get_challenge->funded_date);
						$attatchment = public_path('certificate/'.$id_val.'.png');
						//dd($attatchment);
						if(!empty($attatchment)) {
							$maildata['files'] = [$attatchment];
						}
					}
					try {
						//if(send_user_email($maildata)){ //turn off as client said
							$get_challenge->funded_email_status = 0;
							$get_challenge->save();
						//}
					} catch (\Exception $e) {
						//
					}
				}
			}
			//For Funded email
			
			//For Up and Down balance email
			$email_content = get_email(8);
			if(!empty($email_content))
			{
				if($request_adjust_percent > 0){
					$get_type = 'up -';
				}else{
					$get_type = 'down';
				}
				$maildata = [
					'subject' => $email_content->message_subject,
					'body' => str_replace(array("[LOGO]", "[PERCENT_VALUE]", "[TYPE]", "[SCREEN_NAME]", "[YEAR]"), array($logo, $request_adjust_percent, $get_type, $APP_NAME, date('Y')), $email_content->message),
					'toEmails' => array($get_challenge->email),
				];
				try {
					//if(send_user_email($maildata)){ //turn off as client said
						$model = Adjust_users_balance::find($adjust_balance_val->id);
						$model->email_status = 0;
						$model->save();
					//}
				} catch (\Exception $e) {
					//
				}
			}
			//For Up and Down balance email
		}
    }
}
