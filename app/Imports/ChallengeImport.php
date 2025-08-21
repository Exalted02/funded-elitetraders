<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Challenge;
use App\Models\Challenge_type;
use App\Models\Adjust_users_balance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class ChallengeImport implements ToModel, WithHeadingRow
{
	public $lastRow = [];
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
		$this->lastRow[] = $row;
		// dd($row);
		/*if($row['email'] != ''){
			$user = User::where('email', $row['email'])->first();
			$password = '12345678';
			
			//Name start
			$full_name = $row['full_name'];
			$name_parts = explode(' ', $full_name);			
			$first_name = $name_parts[0]; // Get first name			
			$last_name = isset($name_parts[1]) ? implode(' ', array_slice($name_parts, 1)) : ''; // Get last name (everything else after first)
			
			//Amount start
			$amount = $row['amount'];
			$clean_amount = str_replace([',', '$'], '', $amount);
			
			$value = $row['phase'];
			preg_match('/(\d+)K/', $value, $matches);
			$number = isset($matches[1]) ? (int)$matches[1] * 1000 : 0;			
			
			//phase start
			$phase = $row['phase'];
			$challenge_type = Challenge_type::where('title', $phase)->first();
			if($challenge_type){
				$user_balance = $challenge_type->amount_paid;
				
				$challenge_type_id = $challenge_type->id;
			}else{
				$c_type = new Challenge_type();
				$c_type->title = $phase;
				$c_type->amount = $number;
				$c_type->amount_paid = 0;
				$c_type->status = 1;
				$c_type->status = 1;
				$c_type->created_at = date('Y-m-d H:i:s');
				$c_type->save();
				
				$user_balance = 0;
				$challenge_type_id = $c_type->id;
			}
			

			if($user){
				$user_id = $user->id;
				$user->users_balances = $user->users_balances + $user_balance;
				$user->save();
			}else{
				$model = new User();
				$model->email = $row['email'];
				$model->name = $row['full_name'];
				$model->first_name = $first_name;
				$model->last_name = $last_name;
				$model->password = Hash::make($password);
				$model->users_balances = $user_balance;
				$model->status = 1;
				$model->email_verified_at = date('Y-m-d h:i:s');
				$model->created_at = date('Y-m-d h:i:s');
				
				if($model->save()){
					$client_name = $model->first_name." ".$model->last_name;
					$APP_NAME  = env('APP_NAME');
					$logo = '<img src="' . url('front-assets/img/-logo1.jpg') . '" alt="Expert funded" width="150">';
					$email_content = get_email(1);
					if(!empty($email_content))
					{
						$maildata = [
							'subject' => $email_content->message_subject,
							'body' => str_replace(array("[LOGO]", "[NAME]", "[SCREEN_NAME]", "[EMAIL]", "[PASSWORD]", "[YEAR]", "[LINK_DASHBOARD]"), array($logo, $client_name, $APP_NAME, $model->email, $password, date('Y'), route('client.dashboard-challenge')), $email_content->message),
							'toEmails' => array($model->email),
						];
						try {
							send_email($maildata);
						} catch (\Exception $e) {
							//
						}
					}
				}
				
				$user_id = $model->id;
			}
			
			if($row['status'] == 'ON_CHALLENGE'){
				$challenge_status = 0;
			}else if($row['status'] == 'FUNDED'){
				$challenge_status = 1;
			}else{
				$challenge_status = 2;
			}
			
			$challenge=new Challenge();
			$challenge->client_id = $row['id_klienta'];
			$challenge->user_id = $user_id;
			$challenge->email = $row['email'];
			$challenge->first_name = $first_name;
			$challenge->last_name = $last_name;
			$challenge->challenge_id = $challenge_type_id;
			$challenge->amount_paid = 0;
			$challenge->status = $challenge_status;
			$challenge->created_at = date('Y-m-d h:i:s');
			$challenge->save();
			
			$adj_balance = new Adjust_users_balance();
			$adj_balance->user_id = $user_id;
			$adj_balance->challenge_id = $challenge->id;
			$adj_balance->amount_paid = 0;
			$adj_balance->type = 2;
			$adj_balance->status = 0;
			$adj_balance->save();
			
			$adj_balance1 = new Adjust_users_balance();
			$adj_balance1->user_id = $user_id;
			$adj_balance1->challenge_id = $challenge->id;
			$adj_balance1->amount_paid = $clean_amount - $number;
			$adj_balance1->type = 1;
			$adj_balance1->status = 0;
			$adj_balance1->save();
			
			return $challenge;
		}*/
    }
}
