<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Challenge;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class NewChallengeEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:new-challenge-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email for new challenge';

    /**
     * Execute the console command.
     */
    public function handle()
    {
		//\Log::info('Send email triggered:'.date('Y-m-d H:i:s'));
		
		$email_data = Challenge::where('new_challenge_email_status', 1)->get();

		if ($email_data->isEmpty()) {
            $this->info('No email found.');
            return;
        }
		
		$APP_NAME  = env('APP_NAME');
		$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="Expert funded" width="150">';
		foreach ($email_data as $challenge) {
			$challenge_type = Challenge_type::where('id', $challenge->challenge_id)->first();
			$user_email = User::where('id', $challenge->user_id)->first();
            // Send email	
			$email_content = get_email(10);
			$maildata = [
				'subject' => $email_content->message_subject,
				'body' => str_replace(array("[LOGO]", "[PRICE_SIZE]", "[PRICE]", "[DATE]", "[SCREEN_NAME]", "[YEAR]", "[LINK_LOGIN]"), array($logo, get_currency_symbol().$challenge_type->amount, get_currency_symbol().$challenge->amount_paid, date('d M y'), $APP_NAME, date('Y'), route('login')), $email_content->message),
				'toEmails' => array($user_email->email),
			];
			if(send_user_email($maildata)){
				$challenge->new_challenge_email_status = 0;
				$challenge->save();
			}
			
			 // Log or display message in console
            $this->info("Email sent to: {$user_email->email}");
        }
    }
}


?>