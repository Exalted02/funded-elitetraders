<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Custom_user_email;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendUserEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-user-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to the user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
		//\Log::info('Send email triggered:'.date('Y-m-d H:i:s'));
		
		$email_data = Custom_user_email::get();

		if ($email_data->isEmpty()) {
            $this->info('No email found.');
            return;
        }
		
		foreach ($email_data as $email_data_val) {
			$user_email = User::where('id', $email_data_val->user_id)->first();
            // Send email			
			$emailata = [
				'subject' => $email_data_val->message_subject,
				'body' => str_replace(array(), array(), $email_data_val->message),
				'toEmails' => array($user_email->email),
				// 'bccEmails' => array('exaltedsol06@gmail.com','exaltedsol04@gmail.com'),
				// 'ccEmails' => array('exaltedsol04@gmail.com'),
				// 'files' => [public_path('images/logo.jpg'), public_path('css/app.css'),],
			];
			if(send_user_email($emailata)){
				$delete = Custom_user_email::where('id', $email_data_val->id)->delete();
			}
			
			 // Log or display message in console
            $this->info("Email sent to: {$user_email->email}");
        }
    }
}


?>