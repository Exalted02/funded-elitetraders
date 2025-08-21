<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Kyc_documents;
//use App\Models\Cron_kyc_documents;

use Illuminate\Support\Facades\Mail;

class ChangeKycStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-kyc-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change kyc status to the user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
		//\Log::info('Send email triggered:'.date('Y-m-d H:i:s'));
		
		$kyc_data = Kyc_documents::where('email_status', 1)->with('get_client')->get();

		if ($kyc_data->isEmpty()) {
            $this->info('No email found.');
            return;
        }
		
		foreach ($kyc_data as $kyc_data_val) {
			$id = $kyc_data_val->id;
			// $client_dtls = Kyc_documents::with('get_client')->where('id', $id)->first();
			$client_name = $kyc_data_val->get_client->first_name.' '.$kyc_data_val->get_client->last_name;
			$APP_NAME  = env('APP_NAME');
			
			// $has_files = Kyc_documents::where('id',$id)->first();
			if($kyc_data_val->frontal != null && $kyc_data_val->back != null && $kyc_data_val->residence != null)
			{
				$model = Kyc_documents::find($id);
				$model->email_status = 0;
				$model->save();
				
				// send mail to client 
				$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="Expert funded" width="150">';
				if($kyc_data_val->status == 0)
				{
					$email_content = get_email(7);
				}
				if($kyc_data_val->status == 2)
				{
					$email_content = get_email(5);
				}
				if(!empty($email_content))
				{
					$maildata = [
						'subject' => $email_content->message_subject,
						'body' => str_replace(array("[LOGO]", "[SCREEN_NAME]"), array($logo, $APP_NAME), $email_content->message),
						'toEmails' => array($kyc_data_val->get_client->email),
					];
					try {
						send_email($maildata);
						
					} catch (\Exception $e) {
						//
					}
				}
			}
        }
    }
    /*public function handle()
    {
		//\Log::info('Send email triggered:'.date('Y-m-d H:i:s'));
		
		$kyc_data = Cron_kyc_documents::get();

		if ($kyc_data->isEmpty()) {
            $this->info('No email found.');
            return;
        }
		
		foreach ($kyc_data as $kyc_data_val) {
			$id = $kyc_data_val->kyc_documents_id;
			$client_dtls = Kyc_documents::with('get_client')->where('id', $id)->first();
			$client_name = $client_dtls->get_client->first_name.' '.$client_dtls->get_client->first_last;
			$APP_NAME  = env('APP_NAME');
			
			if($kyc_data_val->status_type == 0)
			{
				$has_files = Kyc_documents::where('id',$id)->first();
				if($has_files->frontal != null && $has_files->back != null && $has_files->residence != null)
				{
					$model = Kyc_documents::find($id);
					$model->status = 0;
					if($model->save()){
						$delete = Cron_kyc_documents::where('id', $kyc_data_val->id)->delete();
					}
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
							
						} catch (\Exception $e) {
							//
						}
					}
				}
			}
			
			if($kyc_data_val->status_type == 2)
			{
				$has_files = Kyc_documents::where('id',$id)->first();
				if($has_files->frontal != null && $has_files->back != null && $has_files->residence != null)
				{
					$model = Kyc_documents::find($id);
					$model->status = 2;
					if($model->save()){
						$delete = Cron_kyc_documents::where('id', $kyc_data_val->id)->delete();
					}
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
							
						} catch (\Exception $e) {
							//
						}
					}
				}
			}
        }
    }*/
}


?>